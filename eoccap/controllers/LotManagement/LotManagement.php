<?php
	/**
	 * LotManagement.php
	 * Contains the Class for the LotManagement Controller
	 *
	 * @author Cory Gehr
	 */

namespace EocCap;

class LotManagement extends \Thinker\Framework\Controller
{
	/**
	 * defaultSubsection()
	 * Returns the default subsection for this Controller
	 *
	 * @access public
	 * @static
	 * @return string Subsection Name
	 */
	public static function defaultSubsection()
	{
		return 'manage';
	}

	/**
	 * manage()
	 * Passes data back for the 'manage' subsection
	 *
	 * @access public
	 */
	public function manage()
	{
		// Get phase
		$phase = \Thinker\Http\Request::request('phase');

		//die(var_dump($phase));

		switch($phase)
		{
			case 'addLot':
				$this->addLot();
			break;

			case 'addSuccess':
				\Thinker\Framework\Notification::push("Added the lot successfully!", "success");
			break;

			case 'deleteSuccess':
				\Thinker\Framework\Notification::push("Delete the lot successfully!", "success");
			break;
		}

		// Get all lots
		$this->set('LOTS', Lot::fetchAll(true));
	}

	/**
	 * addLot()
	 * Adds a lot to the database
	 *
	 * @access private
	 */
	private function addLot()
	{
		// Get form information
		$name = \Thinker\Http\Request::request('name', true);
		$color = \Thinker\Http\Request::request('color');
		$location_name = \Thinker\Http\Request::request('location_name', true);
		$max_capacity = \Thinker\Http\Request::request('max_capacity', true);
		$latitude = \Thinker\Http\Request::request('latitude');
		$longitude = \Thinker\Http\Request::request('longitude');

		// Create data array
		$data = array($name, $color, $location_name, $latitude, $longitude, $max_capacity);

		// Redirect based on success
		if(Lot::create($data))
		{
			\Thinker\Http\Redirect::go('LotManagement', 'manage', array('phase' => 'addSuccess'));
		}
		else
		{
			\Thinker\Framework\Notification::push("Failed to create the new lot.", "error");
		}
	}
}
?>