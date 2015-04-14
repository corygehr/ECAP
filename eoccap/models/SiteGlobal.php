<?php
	/**
	 * models/SiteGlobal.php 
	 * Contains the SiteGlobal class
	 *
	 * @author Cory Gehr
	 */

namespace EocCap;

class SiteGlobal extends \Thinker\Framework\Model
{
	// Properties
	public $name;
	public $value;

	/**
	 * create()
	 * Creates a new Site Global variable in the database
	 *
	 * @access public
	 * @static
	 * @param mixed[] $data Data to commit
	 * @return string Name of the global
	 */
	public static function create($data)
	{
		global $_DB;

		$query = "INSERT INTO globals(name, value) 
				  VALUES(?, ?)";

		if($_DB['eoc_cap_mgmt']->doQuery($query, $data))
		{
			// Provide Name of variable
			return $data[0];
		}

		return false;
	}

	/**
	 * fetch()
	 * Fetches a global's value from the database
	 *
	 * @access public
	 * @static
	 * @param string $name Name of the variable
	 * @return string Global Value
	 */
	public static function fetch($name)
	{
		global $_DB;

		$query = "SELECT value 
				  FROM globals 
				  WHERE name = ? 
				  LIMIT 1";

		$value = $_DB['eoc_cap_mgmt']->doQueryAns($query, array($name));

		if($value !== null)
		{
			return $value;
		}
		else
		{
			trigger_error("Global '$name' does not exist in database.");
		}
	}

	/**
	 * update()
	 * Commits the updated object to the database
	 *
	 * @access public
	 * @param string $name Variable Name
	 * @param mixed $value New Value
	 * @return True on Success, False on Failure
	 */
	public static function update($name, $value)
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