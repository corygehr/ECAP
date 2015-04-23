<?php
	/**
	 * models/LotCapacity.php 
	 * Contains the LotCapacity class
	 *
	 * @author Cory Gehr
	 */

namespace EocCap;

class LotCapacity extends \Thinker\Framework\Model
{
	// Properties
	public $id;
	public $lot_id;
	public $capacity;
	public $create_user;
	public $create_time;

	// Local objects
	public $Lot;
	public $CreateUser;
	
	/**
	 * __construct()
	 * Constructor for the LotCapacity class
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
				  FROM lot_capacity 
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
		else
		{
			// Create empty object
			$this->id = 0;
			$this->lot_id = 0;
			$this->capacity = "NaN";
			$this->create_time = "0000-00-00 00:00:00";
		}
	}

	/**
	 * create()
	 * Creates a new LotCapacity object
	 *
	 * @access public
	 * @static
	 * @param mixed[] $data Data to commit
	 * @return int ID of New Object
	 */
	public static function create($data)
	{
		global $_DB;

		// Track if we're already in a transaction
		$transactionOrigin = true;

		$currentStatus = LotStatusLog::fetchCurrentLotStatus($data[0]);

		// Start transaction
		if(!empty($currentStatus))
		{
			if($_DB['eoc_cap_mgmt']->inTransaction())
			{
				$transactionOrigin = false;
			}
			else
			{
				// Start transaction
				if(!$_DB['eoc_cap_mgmt']->beginTransaction())
				{
					// Fail
					return false;
				}
			}

			$query = "INSERT INTO lot_capacity(lot_id, capacity, 
				create_user, create_time) 
				VALUES(?, ?, ?, NOW())";

			// Add current user to $data
			$data[] = $_SESSION['USER']->username;

			if(!$_DB['eoc_cap_mgmt']->doQuery($query, $data))
			{
				// Rollback
				$_DB['eoc_cap_mgmt']->rollBack();
				return false;
			}

			// Store ID of created object
			$capId = $_DB['eoc_cap_mgmt']->lastInsertId();

			// Add new status log for 'Full' if the lot is full
			if($data[1] >= 100)
			{
				// Set to FULL
				if(!LotStatusLog::create(array($data[0], 6, "Capacity has reached 100%")))
				{
					// Rollback
					$_DB['eoc_cap_mgmt']->rollBack();
					return false;
				}
			}
			elseif($data[1] < 100 && $currentStatus->status_id == 6)
			{
				// Set to ready from FULL
				if(!LotStatusLog::create(array($data[0], 1, "Capacity has fallen below 100%")))
				{
					// Rollback
					$_DB['eoc_cap_mgmt']->rollBack();
					return false;
				}
			}

			if($transactionOrigin)
			{
				// Attempt commit
				if(!$_DB['eoc_cap_mgmt']->commit())
				{
					// Rollback
					$_DB['eoc_cap_mgmt']->rollBack();
					return false;
				}
			}

			return $capId;
		}

		return false;
	}

	/**
	 * fetchByLot()
	 * Fetches all lot attendance data for a specific loc
	 *
	 * @access public
	 * @static
	 * @param int $lotId Lot ID
	 * @param int $limit Result Limiter
	 * @return mixed[] Array of Attendance Data
	 */
	public static function fetchByLot($lotId, $limit = null)
	{
		global $_DB;

		$query = "SELECT la.*, l.name AS lot_name, u.full_name AS create_user_name
				  FROM lot_capacity la 
				  JOIN users u ON u.username = la.create_user 
				  JOIN lots l ON l.id = la.lot_id 
				  WHERE la.lot_id = ? 
				  ORDER BY create_time DESC";
				  
		$data = array($lotId);

		if($limit)
		{
			$query .= " LIMIT $limit";
		}

		return $_DB['eoc_cap_mgmt']->doQueryArr($query, $data);
	}

	/**
	 * fetchCurrentLotCapacity()
	 * Fetches the current capacity for a lot
	 *
	 * @access public
	 * @static
	 * @param int $lotId Lot ID
	 * @return LotCapacity Attendance Object
	 */
	public static function fetchCurrentLotCapacity($lotId)
	{
		// Use fetchByLot to get data
		$data = self::fetchByLot($lotId, 1);

		if($data)
		{
			return new LotCapacity($data[0]['id']);
		}
		else
		{
			// Return empty object
			return new LotCapacity();
		}
	}
}