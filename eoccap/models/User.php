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
	private $username;
	private $type;
	private $full_name;
	private $create_date;
	private $update_date;
	private $delete_date;

	/**
	 * __construct()
	 * Constructor for the User class
	 *
	 * @access public
	 * @param string $username Username of the User
	 */
	public function __construct($username)
	{
		global $_DB;

		// Call the parent constructor
		parent::__construct();

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
	 * getCreateTime()
	 * Returns the date/time this object was created
	 *
	 * @access public
	 * @return string Date/Time of Object Creation
	 */
	public function getCreateTime()
	{
		return $this->create_time;
	}

	/**
	 * getDeleteTime()
	 * Returns the date/time this object was deactivated
	 *
	 * @access public
	 * @return string Date/Time of Object Deactivation
	 */
	public function getDeleteTime()
	{
		return $this->delete_time;
	}

	/**
	 * getName()
	 * Returns the full name of the user
	 *
	 * @access public
	 * @return string Full Name of User
	 */
	public function getName()
	{
		return $this->full_name;
	}

	/**
	 * getType()
	 * Returns the user authentication type
	 *
	 * @access public
	 * @return string User Type
	 */
	public function getType()
	{
		return $this->type;
	}

	/**
	 * getUpdateTime()
	 * Returns the date/time this object was last updated
	 *
	 * @access public
	 * @return string Date/Time of Last Update
	 */
	public function getUpdateTime()
	{
		return $this->update_time;
	}

	/**
	 * getUsername()
	 * Returns the username of the current user
	 *
	 * @access public
	 * @return string Username
	 */
	public function getUsername()
	{
		return $this->username;
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