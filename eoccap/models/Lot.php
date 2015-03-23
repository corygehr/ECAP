<?php
	/**
	 * models/Lot.php 
	 * Contains the Lot class
	 *
	 * @author Cory Gehr
	 */

class Lot extends \Thinker\Framework\Model
{
	// Properties
	private $id;
	private $name;
	private $color;
	private $location_name;
	private $latitude;
	private $longitude;
	private $max_capacity;
	private $create_user;
	private $create_time;
	private $update_user;
	private $update_time;
	private $delete_user;
	private $delete_time;

	// Local objects
	public $CreateUser;
	public $UpdateUser;
	public $DeleteUser;

	/**
	 * __construct()
	 * Constructor for the Lot class
	 *
	 * @author Cory Gehr
	 * @access public
	 */
	public function __construct($message)
	{

	}
}