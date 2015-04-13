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
		$params = array(':user' => $_SESSION['USER_ID'], ':id' => $this->id);

		return $_DB['eoc_cap_mgmt']->doQuery($query, $params);
	}

	/**
	 * findAll()
	 * Fetches all objects of this type
	 *
	 * @access public
	 * @static
	 * @return mixed[] Array of Lot results
	 */
	public static function findAll()
	{
		global $_DB;

		$query = "SELECT *
				  FROM lots 
				  WHERE delete_time IS NULL 
				  ORDER BY name";

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
				  SET name = :name,
				  color = :color, 
				  location_name = :location_name, 
				  latitude = :latitude, 
				  longitude = :longitude, 
				  max_capacity = :max_capacity, 
				  update_user = :user 
				  WHERE id = :id 
				  LIMIT 1";

		return $_DB['eoc_lot_mgmt']->doQuery($query, $this->toArray() + array('user' => $_SESSION['USER_ID']));
	}
}