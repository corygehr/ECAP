<?php
	/**
	 * models/Lot.php 
	 * Contains the Lot class
	 *
	 * @author Cory Gehr
	 */

namespace EocCap;

class Lot extends \Thinker\Framework\Model
{
	// Properties
	public $id;
	public $name;
	public $color;
	public $location_name;
	public $latitude;
	public $longitude;
	public $max_capacity;
	public $create_user;
	public $create_time;
	public $update_user;
	public $update_time;
	public $delete_user;
	public $delete_time;

	// Local objects
	public $CreateUser;
	public $UpdateUser;
	public $DeleteUser;

	/**
	 * __construct()
	 * Constructor for the Lot class
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
				  FROM lots 
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
			$this->CreateUser = new User($this->create_user);
			$this->UpdateUser = new User($this->update_user);
			$this->DeleteUser = new User($this->delete_user);
		}
	}

	/**
	 * create()
	 * Creates a new Lot object
	 *
	 * @access public
	 * @static
	 * @param mixed[] $data Data to commit
	 * @return int ID of New Object
	 */
	public static function create($data)
	{
		global $_DB;

		$query = "INSERT INTO lots(name, color, location_name, 
			latitude, longitude, max_capacity, create_user, create_time) 
			VALUES(?, ?, ?, ?, ?, ?, ?, NOW())";

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
	 * delete()
	 * Deactivates this object
	 *
	 * @access public
	 * @return bool True on Success, False on Failure
	 */
	public function delete()
	{
		global $_DB;

		$query = "UPDATE lots
				  SET delete_time = NOW(),
				  delete_user = :user 
				  WHERE id = :id 
				  LIMIT 1";
		$params = array(':user' => 'cmg5573', ':id' => $this->id);

		return $_DB['eoc_cap_mgmt']->doQuery($query, $params);
	}

	/**
	 * fetchAll()
	 * Fetches all objects of this type
	 *
	 * @access public
	 * @static
	 * @param boolean $ignoreInactive Flag to ignore inactive results
	 * @return mixed[] Array of Lot results
	 */
	public static function fetchAll($ignoreInactive = false)
	{
		global $_DB;

		$where = '';

		if($ignoreInactive)
		{
			$where = "WHERE delete_time IS NULL";
		}

		$query = "SELECT *
				  FROM lots 
				  $where 
				  ORDER BY name";

		return $_DB['eoc_cap_mgmt']->doQueryArr($query);
	}

	/**
	 * fetchAllExtended()
	 * Fetches all Lots with extended Status and Readiness Information
	 *
	 * @access public
	 * @static
	 * @param boolean $ignoreInactive Flag that will ignore inactive results when true
	 * @return mixed[] Array of Lot results
	 */
	public static function fetchAllExtended($ignoreInactive = false)
	{
		global $_DB;

		$where = '';

		if($ignoreInactive)
		{
			$where = 'WHERE l.delete_time IS NULL ';
		}

		$query = "SELECT l.*, lcu.full_name AS lot_create_user_name, luu.full_name AS lot_update_user_name, 
				  ldu.full_name AS lot_delete_user_name, lsl.status_id, lsl.comment, lsl.create_time AS status_create_time, 
				  lslcu.full_name AS status_create_user_name, ls.name AS status, ls.description AS status_description, 
				  lc.capacity, lc.create_time AS capacity_create_time, lccu.full_name AS capacity_create_user_name, 
				  lr.notes, lr.create_time AS readiness_create_time, lrcu.full_name AS readiness_create_user_name 
				  FROM lots l 
				  JOIN lot_status_log lsl ON lsl.id = (
				  	SELECT id 
				  	FROM lot_status_log 
				  	WHERE lot_id = l.id 
				  	ORDER BY create_time DESC 
				  	LIMIT 1
				  	) 
				  JOIN lot_statuses ls ON ls.id = lsl.status_id 
				  JOIN lot_capacity lc ON lc.id = (
				  	SELECT id 
				  	FROM lot_capacity 
				  	WHERE lot_id = l.id 
				  	ORDER BY create_time DESC 
				  	LIMIT 1
				  	)
				  LEFT JOIN lot_readiness lr ON lr.id = (
				  	SELECT id 
				  	FROM lot_readiness 
				  	WHERE lot_id = l.id 
				  	ORDER BY create_time DESC 
				  	LIMIT 1
				  	)
				  LEFT JOIN users lcu ON lcu.username = l.create_user 
				  LEFT JOIN users luu ON luu.username = l.update_user 
				  LEFT JOIN users ldu ON ldu.username = l.delete_user 
				  LEFT JOIN users lslcu ON lslcu.username = lsl.create_user 
				  LEFT JOIN users lccu ON lccu.username = lc.create_user 
				  LEFT JOIN users lrcu ON lrcu.username = lr.create_user 
				  $where
				  ORDER BY l.name";

		return $_DB['eoc_cap_mgmt']->doQueryArr($query);
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
	 * update()
	 * Commits the updated object to the database
	 *
	 * @access public
	 * @return True on Success, False on Failure
	 */
	public function update()
	{
		global $_DB;

		$query = "UPDATE lots
				  SET name = ?,
				  color = ?, 
				  location_name = ?, 
				  latitude = ?, 
				  longitude = ?, 
				  max_capacity = ?, 
				  update_user = ? 
				  WHERE id = ? 
				  LIMIT 1";

		return $_DB['eoc_cap_mgmt']->doQuery($query, 
			array(
				$this->name,
				$this->color,
				$this->location_name,
				$this->latitude,
				$this->longitude,
				$this->max_capacity,
				'cmg5573',
				$this->id));
	}
}