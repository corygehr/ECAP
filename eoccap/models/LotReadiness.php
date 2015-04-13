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
			create_user, create_time) 
			VALUES(?, ?, ?, ?, ?, ?, ?, ?, ?, ?, NOW())";

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
	 * findAll()
	 * Fetches all objects of this type
	 *
	 * @access public
	 * @static
	 * @return mixed[] Array of LotReadiness results
	 */
	public static function findAll()
	{
		global $_DB;

		$query = "SELECT *
				  FROM lot_readiness  
				  ORDER BY create_time DESC";

		return $_DB['eoc_cap_mgmt']->doQueryArr($query);
	}
}