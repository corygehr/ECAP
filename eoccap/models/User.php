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
	public $account_type;
	public $user_type;
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
	 * addUserRight()
	 * Adds a user permission entry for a section
	 *
	 * @access public
	 * @static
	 * @param mixed[] $data Data to Commit (username, section, subsection)
	 * @return int ID of Created Object
	 */
	public static function addUserRight($data)
	{
		global $_DB;

		// Add permissions for an Administrator
		$query = "INSERT INTO user_rights(username, s, ss)
				  VALUES(?, ?, ?)";

		if($_DB['eoc_cap_mgmt']->doQuery($query, $data))
		{
			// Commit
			return $_DB['eoc_cap_mgmt']->lastInsertId();
		}
		else
		{
			return false;
		}
	}

	/**
	 * addUserRightIdentifier()
	 * Adds an identifier to a User Right
	 *
	 * @access public
	 * @static
	 * @param mixed[] $data Data to Commit (right id, id name, id value)
	 * @return int ID of Created Object
	 */
	public static function addUserRightIdentifier($data)
	{
		global $_DB;

		// Add permissions for an Administrator
		$query = "INSERT INTO user_rights_identifiers(right_id, identifier_name, identifier_value)
				  VALUES(?, ?, ?)";

		if($_DB['eoc_cap_mgmt']->doQuery($query, $data))
		{
			// Commit
			return $_DB['eoc_cap_mgmt']->lastInsertId();
		}
		else
		{
			return false;
		}
	}

	/**
	 * create()
	 * Creates a new User object
	 *
	 * @access public
	 * @static
	 * @param mixed[] $data Data to commit
	 * @param string $password User Password
	 * @param int $lotId Lot ID to give access to
	 * @return boolean Status of Transaction
	 */
	public static function create($data, $password = null, $lotId = null)
	{
		global $_DB;

		// Begin a transaction
		if($_DB['eoc_cap_mgmt']->beginTransaction())
		{
			// Add the user
			$query = "INSERT INTO users(username, full_name, account_type, user_type, 
				create_time) 
				VALUES(?, ?, ?, ?, NOW())";

			// Check for an error
			if(!$_DB['eoc_cap_mgmt']->doQuery($query, $data))
			{
				// Rollback
				$_DB['eoc_cap_mgmt']->rollBack();
				return false;
			}

			// Add permissions based on the user type
			
			if($data[2] == 1)
			{
				// Add password
				$query = "INSERT INTO user_passwords(username, hash)
						  VALUES(?, ?)";

				$hashPw = hash('sha256', (self::hashPassword($password)));

				if(!$_DB['eoc_cap_mgmt']->doQuery($query, array($data[0], $hashPw)))
				{
					// Rollback
					$_DB['eoc_cap_mgmt']->rollBack();
					return false;
				}

				if(self::addUserRight(array($data[0], '*', null)))
				{
					// Commit
					return $_DB['eoc_cap_mgmt']->commit();
				}
				else
				{
					// Rollback
					$_DB['eoc_cap_mgmt']->rollBack();
					return false;
				}
			}
			else
			{
				// Create right and get ID
				$rightId = self::addUserRight(array($data[0], 'LotConsole', 'manage'));

				if(!$rightId)
				{
					// Rollback
					$_DB['eoc_cap_mgmt']->rollBack();
					return false;
				}

				// Create right identifier entry
				if(self::addUserRightIdentifier(array($rightId, 'id', $lotId)))
				{
					// Commit
					return $_DB['eoc_cap_mgmt']->commit();
				}
				else
				{
					// Rollback
					$_DB['eoc_cap_mgmt']->rollBack();
					return false;
				}
			}
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
	 * fetchAll()
	 * Fetches all users in the database
	 *
	 * @access public
	 * @static
	 * @return mixed[] List of Users
	 */
	public static function fetchAll()
	{
		global $_DB;

		$query = "SELECT u.*, at.name AS account_type_name, 
				  ut.name AS user_type_name
				  FROM users u 
				  JOIN account_types at ON at.id = u.account_type 
				  JOIN user_types ut ON ut.id = u.user_type 
				  ORDER BY u.username";

		return $_DB['eoc_cap_mgmt']->doQueryArr($query);
	}

	/**
	 * getAccountTypes()
	 * Gets a list of all possible account types
	 *
	 * @access public
	 * @static
	 * @return string[] List of Account Types
	 */
	public static function getAccountTypes()
	{
		global $_DB;

		$query = "SELECT id, name 
				  FROM account_types 
				  ORDER BY name";

		return $_DB['eoc_cap_mgmt']->doQueryArr($query);
	}

	/**
	 * getUserTypes()
	 * Gets a list of all possible user types
	 *
	 * @access public
	 * @static
	 * @return string[] List of User Types
	 */
	public static function getUserTypes()
	{
		global $_DB;

		$query = "SELECT id, name 
				  FROM user_types 
				  ORDER BY name";

		return $_DB['eoc_cap_mgmt']->doQueryArr($query);
	}

	/**
	 * hashPassword()
	 * Creates the hash for a user's password
	 *
	 * @access public
	 * @static
	 * @param string $password Password
	 * @return string Hashed password
	 */
	public static function hashPassword($password)
	{
		return hash('sha256', SiteGlobal::fetch('PASSWORD_SALT') . $password);
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
				  SET account_type = :type, 
				  full_name = :full_name 
				  WHERE username = :username 
				  LIMIT 1";

		return $_DB['eoc_lot_mgmt']->doQuery($query, $this->toArray());
	}
}