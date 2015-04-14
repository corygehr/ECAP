<?php
	/**
	 * models/LotStatusLog.php 
	 * Contains the LotStatusLog class
	 *
	 * @author Cory Gehr
	 */

namespace EocCap;

class LotStatusLog extends \Thinker\Framework\Model
{
	// Properties
	public $id;
	public $lot_id;
	public $status_id;
	public $comment;
	public $create_user;
	public $create_time;

	// Local objects
	public $Lot;
	public $Status;
	public $CreateUser;
	
	/**
	 * __construct()
	 * Constructor for the LotStatusLog class
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
				  FROM lot_status_log 
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
			$this->Status = new LotStatus($this->status_id);
			$this->CreateUser = new User($this->create_user);
		}
	}

	/**
	 * create()
	 * Creates a new LotStatus object
	 *
	 * @access public
	 * @static
	 * @param mixed[] $data Data to commit
	 * @return int ID of New Object
	 */
	public static function create($data)
	{
		global $_DB;

		$query = "INSERT INTO lot_status_log(lot_id, status_id, 
			comment, create_user, create_time) 
			VALUES(?, ?, ?, ?, NOW())";

		// Add current user to $data
		$data[] = $_SESSION['USER']->username;
		
		if($_DB['eoc_cap_mgmt']->doQuery($query, $data))
		{
			// Provide ID of created object
			return $_DB['eoc_cap_mgmt']->lastInsertId();
		}

		return false;
	}

	/**
	 * fetchByLot()
	 * Fetches all lot status data for a specific loc
	 *
	 * @access public
	 * @static
	 * @param int $lotId Lot ID
	 * @param int $limit Result Limiter
	 * @return mixed[] Array of Readiness Data
	 */
	public static function fetchByLot($lotId, $limit = null)
	{
		global $_DB;

		$query = "SELECT lsl.*, l.name AS lot_name, u.full_name AS create_user_name
				  FROM lot_status_log lsl 
				  JOIN users u ON u.username = lsl.create_user 
				  JOIN lots l ON l.id = lsl.lot_id 
				  WHERE lsl.lot_id = ? 
				  ORDER BY lsl.create_time DESC";
				  
		$data = array($lotId);

		if($limit)
		{
			$query .= " LIMIT $limit";
		}

		return $_DB['eoc_cap_mgmt']->doQueryArr($query, $data);
	}

	/**
	 * fetchCurrentLotStatus()
	 * Fetches the current status for a lot
	 *
	 * @access public
	 * @static
	 * @param int $lotId Lot ID
	 * @return LotStatusLog Status Log Object
	 */
	public static function fetchCurrentLotStatus($lotId)
	{
		// Use fetchByLot to get data
		$data = self::fetchByLot($lotId, 1);

		if($data)
		{
			return new LotStatusLog($data[0]['id']);
		}
		else
		{
			// Return empty object
			return new LotStatusLog();
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

	/**
	 * isStale()
	 * Tells us if the data is stale (over 1 day old)
	 *
	 * @access public
	 * @param int $hourInterval Number of Hours to determine staleness
	 * @return boolean True if Stale, False if Valid
	 */
	public function isStale($hourInterval = 24)
	{
		$date1 = new \DateTime($this->create_time);
		$date2 = new \DateTime(strtotime('Y-m-d H:i:s', time()));

		$diff = $date2->diff($date1);

		$hours = $diff->h;
		return ($hours + ($diff->days*24)) >= $hourInterval;
	}
}