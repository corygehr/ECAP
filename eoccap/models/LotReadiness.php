<?php
	/**
	 * models/LotReadiness.php 
	 * Contains the LotReadiness class
	 *
	 * @author Cory Gehr
	 */

namespace EocCap;

class LotReadiness extends \Thinker\Framework\Model
{
	// Properties
	public $id;
	public $lot_id;
	public $radios;
	public $portajohns;
	public $aframes;
	public $lighttowers;
	public $supervisor;
	public $parker;
	public $sellers;
	public $liaison;
	public $notes;
	public $create_user;
	public $create_time;

	// Local objects
	public $Lot;
	public $CreateUser;

	/**
	 * __construct()
	 * Constructor for the LotReadiness class
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
				  FROM lot_readiness 
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
			$this->create_time = "0000-00-00 00:00:00";
		}
	}

	/**
	 * create()
	 * Creates a new LotReadiness object
	 *
	 * @access public
	 * @static
	 * @param mixed[] $data Data to commit
	 * @return int ID of New Object
	 */
	public static function create($data)
	{
		global $_DB;

		$query = "INSERT INTO lot_readiness(lot_id, radios, portajohns, 
			aframes, lighttowers, supervisor, parker, sellers, liaison, 
			notes, create_user, create_time) 
			VALUES(?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, NOW())";

		// Add current user to $data
		$data[] = $_SESSION['USER']->username;

		if($_DB['eoc_cap_mgmt']->doQuery($query, $data))
		{
			// Store ID of created object
			$readinessId =  $_DB['eoc_cap_mgmt']->lastInsertId();

			$status = 1;

			// Update the target lot's status as ready
			if($data[1] && $data[2] && $data[3] && $data[4] && $data[5] && $data[6] && $data[7] && $data[8])
			{
				// All statuses are good, update status to Ready
				$status = 5;
			}
			else
			{
				// Needs attention
				$status = 4;
			}

			// Add new status
			if(LotStatusLog::create(array($data[0], $status, REPORT_TRG_TEXT)))
			{
				return $readinessId;
			}
		}

		return false;
	}

	/**
	 * fetchByLot()
	 * Fetches all lot readiness data for a specific loc
	 *
	 * @access public
	 * @static
	 * @param int $lotId Lot ID
	 * @param int $limit Result Limiter
	 * @return mixed[] Array of Readiness Data
	 */
	public static function fetchByLot($lotId, $limit = null)
	{
		global $_DB;

		$query = "SELECT lr.*, l.name AS lot_name, u.full_name AS create_user_name
				  FROM lot_readiness lr 
				  JOIN users u ON u.username = lr.create_user 
				  JOIN lots l ON l.id = lr.lot_id 
				  WHERE lr.lot_id = ? 
				  ORDER BY lr.create_time DESC";
				  
		$data = array($lotId);

		if($limit)
		{
			$query .= " LIMIT $limit";
		}

		return $_DB['eoc_cap_mgmt']->doQueryArr($query, $data);
	}

	/**
	 * fetchCurrentLotReadiness()
	 * Fetches the current readiness for a lot
	 *
	 * @access public
	 * @static
	 * @param int $lotId Lot ID
	 * @return LotReadiness Readiness Object
	 */
	public static function fetchCurrentLotReadiness($lotId)
	{
		// Use fetchByLot to get data
		$data = self::fetchByLot($lotId, 1);

		if($data)
		{
			return new LotReadiness($data[0]['id']);
		}
		else
		{
			// Return empty object
			return new LotReadiness();
		}
	}

	/**
	 * isStale()
	 * Tells us if the data is stale (over 1 day old)
	 *
	 * @access public
	 * @param int $hourInterval Number of Hours to determine staleness
	 * @return boolean True if Stale, False if Valid
	 */
	public function isStale($hourInterval = 24)
	{
		$date1 = new \DateTime($this->create_time);
		$date2 = new \DateTime(strtotime('Y-m-d H:i:s', time()));

		$diff = $date2->diff($date1);

		$hours = $diff->h;
		return ($hours + ($diff->days*24)) >= $hourInterval;
	}
}