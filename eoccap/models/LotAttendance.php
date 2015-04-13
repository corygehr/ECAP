<?php
	/**
	 * models/LotAttendance.php 
	 * Contains the LotAttendance class
	 *
	 * @author Cory Gehr
	 */

namespace EocCap;

class LotAttendance extends \Thinker\Framework\Model
{
	// Properties
	public $id;
	public $lot_id;
	public $attendance;
	public $create_user;
	public $create_time;

	// Local objects
	public $Lot;
	public $CreateUser;
	
	/**
	 * __construct()
	 * Constructor for the LotAttendance class
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
				  FROM lot_attendance 
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
	}

	/**
	 * create()
	 * Creates a new LotAttendance object
	 *
	 * @access public
	 * @static
	 * @param mixed[] $data Data to commit
	 * @return int ID of New Object
	 */
	public static function create($data)
	{
		global $_DB;

		$query = "INSERT INTO lot_attendance(lot_id, attendance, 
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