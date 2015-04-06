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
		$data[] = $_SESSION['USER_ID'];

		if($_DB['eoc_cap_mgmt']->doQuery($query, $data))
		{
			// Provide ID of created object
			return $_DB['eoc_cap_mgmt']->lastInsertId();
		}

		return false;
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