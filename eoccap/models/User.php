<?php
	/**
	 * models/User.php 
	 * Contains the User class
	 *
	 * @author Cory Gehr
	 */

namespace EocCap;

class User
{
	// Properties
	public $username;
	public $type;
	public $full_name;
	public $create_date;
	public $update_date;
	public $delete_date;

	/**
	 * __construct()
	 * Constructor for the User class
	 *
	 * @access public
	 * @param string $username Username of the User
	 */
	public function __construct($username = null)
	{
		global $_DB;

		// Load the object
		$query = "SELECT *
				  FROM users 
				  WHERE username = :username 
				  LIMIT 1";
		$params = array(':username' => $username);

		$result = $_DB['eoc_cap_mgmt']->doQueryOne($query, $params);

		if($result)
		{
			// Load data into object
			foreach($result as $name => $val)
			{
				$this->{$name} = $val;
			}
		}
	}

	/**
	 * create()
	 * Creates a new User object
	 *
	 * @access public
	 * @static
	 * @param mixed[] $data Data to commit
	 * @return string ID of New Object
	 */
	public static function create($data)
	{
		global $_DB;

		$query = "INSERT INTO users(username, type, full_name, 
			create_date) 
			VALUES(?, ?, ?, NOW())";

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

		$query = "UPDATE users 
				  SET delete_time = NOW() 
				  WHERE username = :username 
				  LIMIT 1";
		$params = array(':username' => $this->username);

		return $_DB['eoc_cap_mgmt']->doQuery($query, $params);
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

		$query = "UPDATE users
				  SET type = :type,
				  full_name = :full_name 
				  WHERE username = :username 
				  LIMIT 1";

		return $_DB['eoc_lot_mgmt']->doQuery($query, $this->toArray());
	}
}