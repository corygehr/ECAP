<?php
	/**
	 * models/Map.php 
	 * Contains the Map class
	 *
	 * @author Cory Gehr
	 */

namespace EocCap;

class Map extends \Thinker\Framework\Model
{
	// Properties
	public $id;
	public $name;
	public $url;
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
	 * Constructor for the Event class
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
				  FROM maps 
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
	 * Creates a new Map object
	 *
	 * @access public
	 * @static
	 * @param mixed[] $data Data to commit
	 * @return int ID of New Object
	 */
	public static function create($data)
	{
		global $_DB;

		$query = "INSERT INTO maps(name, url, 
			create_user, create_time) 
			VALUES(?, ?, ?, NOW())";

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
	 * delete()
	 * Deactivates this object
	 *
	 * @access public
	 * @return bool True on Success, False on Failure
	 */
	public function delete()
	{
		global $_DB;

		$query = "UPDATE maps 
				  SET delete_time = NOW(),
				  delete_user = :user 
				  WHERE id = :id 
				  LIMIT 1";
		$params = array(':user' => $_SESSION['USER']->username, ':id' => $this->id);

		return $_DB['eoc_cap_mgmt']->doQuery($query, $params);
	}

	/**
	 * fetchAll()
	 * Fetches all objects of this type
	 *
	 * @access public
	 * @static
	 * @param boolean $ignoreInactive Flag to ignore inactive results
	 * @return mixed[] Array of Map results
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
				  FROM maps 
				  $where 
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

		$query = "UPDATE maps 
				  SET name = ?,
				  url = ?, 
				  update_user = ? 
				  WHERE id = ? 
				  LIMIT 1";

		return $_DB['eoc_cap_mgmt']->doQuery($query, 
			array(
				$this->name,
				$this->url,
				$_SESSION['USER']->username,
				$this->id));
	}
}