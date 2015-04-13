<?php
	/**
	 * models/LotCapacity.php 
	 * Contains the LotCapacity class
	 *
	 * @author Cory Gehr
	 */

namespace EocCap;

class LotCapacity extends \Thinker\Framework\Model
{
	// Properties
	public $id;
	public $lot_id;
	public $capacity;
	public $create_user;
	public $create_time;

	// Local objects
	public $Lot;
	public $CreateUser;
	
	/**
	 * __construct()
	 * Constructor for the LotCapacity class
	 *
	 * @access public
	 * @param int $id ID of the Object
	 */
	public function __construct($id = null)
	{
		global $_DB;

		// Call the parent constructor
		parent::__construct();

		// Load the object
		$query = "SELECT *
				  FROM lot_capacity 
				  WHERE id = :id 
				  LIMIT 1";
		$params = array(':id' => $id);

		$result = $_DB['eoc_cap_mgmt']->doQueryOne($query, $params);

		if($result)
		{
			// Load data into object
			foreach($result as $name => $val)
			{
				$this->{$name} = $val;
			}

			// Create local objects
			$this->Lot = new Lot($this->lot_id);
			$this->CreateUser = new User($this->create_user);
		}
		else
		{
			// Create empty object
			$this->id = 0;
			$this->lot_id = 0;
			$this->capacity = "NaN";
			$this->create_time = "0000-00-00 00:00:00";
		}
	}

	/**
	 * create()
	 * Creates a new LotCapacity object
	 *
	 * @access public
	 * @static
	 * @param mixed[] $data Data to commit
	 * @return int ID of New Object
	 */
	public static function create($data)
	{
		global $_DB;

		$query = "INSERT INTO lot_capacity(lot_id, capacity, 
			create_user, create_time) 
			VALUES(?, ?, ?, NOW())";

		// Add current user to $data
		//$data[] = $_SESSION['USER_ID'];
		$data[] = 'cmg5573';

		if($_DB['eoc_cap_mgmt']->doQuery($query, $data))
		{
			// Provide ID of created object
			return $_DB['eoc_cap_mgmt']->lastInsertId();
		}

		return false;
	}

	/**
	 * fetchByLot()
	 * Fetches all lot attendance data for a specific loc
	 *
	 * @access public
	 * @static
	 * @param int $lotId Lot ID
	 * @param int $limit Result Limiter
	 * @return mixed[] Array of Attendance Data
	 */
	public static function fetchByLot($lotId, $limit = null)
	{
		global $_DB;

		$query = "SELECT la.*, l.name AS lot_name, u.full_name AS create_user_name
				  FROM lot_capacity la 
				  JOIN users u ON u.username = la.create_user 
				  JOIN lots l ON l.id = la.lot_id 
				  WHERE la.lot_id = ? 
				  ORDER BY create_time DESC";
				  
		$data = array($lotId);

		if($limit)
		{
			$query .= " LIMIT $limit";
		}

		return $_DB['eoc_cap_mgmt']->doQueryArr($query, $data);
	}

	/**
	 * fetchCurrentLotCapacity()
	 * Fetches the current capacity for a lot
	 *
	 * @access public
	 * @static
	 * @param int $lotId Lot ID
	 * @return LotCapacity Attendance Object
	 */
	public static function fetchCurrentLotCapacity($lotId)
	{
		// Use fetchByLot to get data
		$data = self::fetchByLot($lotId, 1);

		if($data)
		{
			return new LotCapacity($data[0]['id']);
		}
		else
		{
			// Return empty object
			return new LotCapacity();
		}
	}

	/**
	 * isActive()
	 * Returns true if the current object is active
	 *
	 * @access public
	 * @return bool True if Active, False if not
	 */
	public function isActive()
	{
		return !($this->delete_time);
	}
}