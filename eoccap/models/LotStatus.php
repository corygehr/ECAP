<?php
	/**
	 * models/LotStatus.php 
	 * Contains the LotStatus class
	 *
	 * @author Cory Gehr
	 */

namespace EocCap;

class LotStatus extends \Thinker\Framework\Model
{
	// Properties
	public $id;
	public $name;
	public $color;
	public $description;
	public $create_user;
	public $create_time;
	public $update_user;
	public $update_time;
	public $delete_user;
	public $delete_time;
	
	/**
	 * __construct()
	 * Constructor for the LotStatus class
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
				  FROM lot_statuses 
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

		$query = "INSERT INTO lot_statuses(name, color, description, 
			create_user, create_time) 
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
	 * delete()
	 * Deactivates this object
	 *
	 * @access public
	 * @return bool True on Success, False on Failure
	 */
	public function delete()
	{
		global $_DB;

		$query = "UPDATE lot_statuses
				  SET delete_time = NOW(),
				  delete_user = :user 
				  WHERE id = :id 
				  LIMIT 1";
		$params = array(':user' => $_SESSION['USER_ID'], ':id' => $this->id);

		return $_DB['eoc_cap_mgmt']->doQuery($query, $params);
	}

	/**
	 * fetchAll()
	 * Fetches all LotStatuses
	 *
	 * @access public
	 * @static
	 * @param boolean $ignoreInactive Switch for including inactive statuses
	 * @return mixed[] Array of Lot Statuses
	 */
	public static function fetchAll($ignoreInactive = false)
	{
		global $_DB;

		$where = '';

		if($ignoreInactive)
		{
			$where = "WHERE ls.delete_time IS NULL";
		}

		$query = "SELECT ls.*, cu.full_name AS create_user_name,
				  uu.full_name AS update_user_name, du.full_name AS delete_user_name 
				  FROM lot_statuses ls
				  LEFT JOIN users cu ON cu.username = ls.create_user 
				  LEFT JOIN users uu ON uu.username = ls.update_user 
				  LEFT JOIN users du ON du.username = ls.delete_user 
				  $where 
				  ORDER BY ls.name";

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

		$query = "UPDATE lot_statuses
				  SET name = :name,
				  color = :color,
				  description = :description, 
				  update_user = :user 
				  WHERE id = :id 
				  LIMIT 1";

		return $_DB['eoc_lot_mgmt']->doQuery($query, $this->toArray() + array('user' => $_SESSION['USER_ID']));
	}
}