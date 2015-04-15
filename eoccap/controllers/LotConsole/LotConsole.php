<?php
	/**
	 * LotConsole.php
	 * Contains the Class for the LotConsole Controller
	 *
	 * @author Cory Gehr
	 */
	
namespace EocCap;

class LotConsole extends \Thinker\Framework\Controller
{
	/**
	 * __construct()
	 * Constructor for the LotConsole class
	 *
	 * @access public
	 */
	public function __construct()
	{
		parent::__construct();

		// Verify acces
		if(!$this->session->auth('section', array(
			'section' => SECTION, 'subsection' => SUBSECTION, 
			'url_params' => array('id' => \Thinker\Http\Request::request('id')))))
		{
			\Thinker\Http\Redirect::error(403);
		}
	}

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
		// Get Lot ID
		$lotId = \Thinker\Http\Request::request('id');

		if($lotId)
		{
			// Load Objects
			$targetLot = new Lot($lotId);

			if($targetLot)
			{
				// Process changes
				$phase = \Thinker\Http\Request::request('phase');

				switch($phase)
				{
					case 'deleteLot':
						$this->deleteLot();
					break;

					case 'updateCapacity':
						$this->updateCapacity();
					break;

					case 'updateDetails':
						$this->updateDetails();
					break;

					case 'updateReadiness':
						$this->updateReadiness();
					break;

					case 'updateStatus':
						$this->updateStatus();
					break;

					case 'capacityUpdateSuccess':
						\Thinker\Framework\Notification::push("Updated lot capacity successfully!", "success");
					break;

					case 'detailUpdateSuccess':
						\Thinker\Framework\Notification::push("Updated lot details successfully!", "success");
					break;

					case 'readinessUpdateSuccess':
						\Thinker\Framework\Notification::push("Updated lot readiness successfully!", "success");
					break;

					case 'statusUpdateSuccess':
						\Thinker\Framework\Notification::push("Updated lot status successfully!", "success");
					break;
				}

				// Pass the lot back to the view
				$this->set('Lot', $targetLot);
				$this->set('STATUSES', LotStatus::fetchAll(true));
				$this->set('Status', LotStatusLog::fetchCurrentLotStatus($targetLot->id));
				$this->set('Capacity', LotCapacity::fetchCurrentLotCapacity($targetLot->id));
				$this->set('CAPACITY_HISTORY', LotCapacity::fetchByLot($targetLot->id, 10));

				$currentReadiness = LotReadiness::fetchCurrentLotReadiness($targetLot->id);

				// If the current readiness is stale, we want to force creation of a new one
				if($currentReadiness->isStale())
				{
					$currentReadiness = new LotReadiness();
				}

				$this->set('Readiness', $currentReadiness);
			}
			else
			{
				// Throw error
				\Thinker\Framework\Notification::push("Failed to retrieve the specified Lot, please try again.", "error");
			}
		}
		else
		{
			// Throw error
			\Thinker\Framework\Notification::push("No Lot ID specified, cannot continue.", "warning");
		}
	}

	/**
	 * deleteLot()
	 * Deactivates a lot in the database
	 *
	 * @access private
	 */
	private function deleteLot()
	{
		if($_SESSION['USER']->user_type == 1)
		{
			// Get the ID of the lot to deactivate
			$id = \Thinker\Http\Request::post('id', true);

			// Create the object and invoke delete
			$tLot = new Lot($id);

			if($tLot->delete())
			{
				\Thinker\Http\Redirect::go('LotManagement', 'manage', array('phase' => 'deleteSuccess'));
			}
			else
			{
				\Thinker\Framework\Notification::push("Failed to delete the lot, please try again.", "error");
			}
		}
		else
		{
			\Thinker\Framework\Notification::push("You do not have access to do that.", "warning");
		}
	}

	/**
	 * updateCapacity()
	 * Updates the attendance logs for the current lot
	 *
	 * @access private
	 */
	private function updateCapacity()
	{
		// Get the details
		$id = \Thinker\Http\Request::post('id', true);
		$capacity = \Thinker\Http\Request::post('capacity', true);

		// Add the log for the specific lot
		if(LotCapacity::create(array($id, $capacity)))
		{
			\Thinker\Http\Redirect::go('LotConsole', 'manage', array('id' => $id, 'phase' => 'capacityUpdateSuccess'));
		}
		else
		{
			\Thinker\Framework\Notification::push("Failed to update the capacity, please try again.", "error");
		}
	}

	/**
	 * updateDetails()
	 * Updates Lot Basic Information
	 *
	 * @access private
	 */
	private function updateDetails()
	{
		if($_SESSION['USER']->user_type == 1)
		{
			// Get information
			$id = \Thinker\Http\Request::post('id', true);
			$color = \Thinker\Http\Request::post('color');
			$location_name = \Thinker\Http\Request::post('location_name', true);
			$latitude = \Thinker\Http\Request::post('latitude');
			$longitude = \Thinker\Http\Request::post('longitude');
			$max_capacity = \Thinker\Http\Request::post('max_capacity', true);

			// Create object for lot
			$target = new Lot($id);

			if($target)
			{
				// Update properties
				$target->color = $color;
				$target->location_name = $location_name;
				$target->latitude = $latitude;
				$target->longitude = $longitude;
				$target->max_capacity = $max_capacity;

				// Invoke object update
				if($target->update())
				{
					\Thinker\Http\Redirect::go('LotConsole', 'manage', array('id' => $id, 'phase' => 'detailUpdateSuccess'));
				}
			}

			\Thinker\Framework\Notification::push("Failed to update the lot, please try again.", "error");
		}
		else
		{
			\Thinker\Framework\Notification::push("You do not have access to do that.", "warning");
		}
	}

	/**
	 * updateReadiness()
	 * Updates lot readiness information
	 *
	 * @access private
	 */
	private function updateReadiness()
	{
		// Get form data
		$id = \Thinker\Http\Request::post('id', true);
		$radios = \Thinker\Http\Request::post('radios');
		$portajohns = \Thinker\Http\Request::post('portajohns');
		$aframes = \Thinker\Http\Request::post('aframes');
		$lighttowers = \Thinker\Http\Request::post('lighttowers');
		$supervisor = \Thinker\Http\Request::post('supervisor');
		$parker = \Thinker\Http\Request::post('parker');
		$sellers = \Thinker\Http\Request::post('sellers');
		$liaison = \Thinker\Http\Request::post('liaison');
		$notes = \Thinker\Http\Request::post('notes');

		// Create the entry
		if(LotReadiness::create(array($id, $radios, $portajohns, $aframes, 
			$lighttowers, $supervisor, $parker, $sellers, $liaison, $notes)))
		{
			// Redirect
			\Thinker\Http\Redirect::go('LotConsole', 'manage', array('id' => $id, 'phase' => 'readinessUpdateSuccess'));
		}
		else
		{
			\Thinker\Framework\Notification::push("Failed to submit readiness report, please try again.", "error");
		}
	}

	/**
	 * updateStatus()
	 * Updates lot status information
	 *
	 * @access private
	 */
	private function updateStatus()
	{
		if($_SESSION['USER']->user_type == 1)
		{
			// Get form information
			$id = \Thinker\Http\Request::post('id', true);
			$status = \Thinker\Http\Request::post('status', true);
			$comment = \Thinker\Http\Request::post('comment');

			if(LotStatusLog::create(array($id, $status, $comment)))
			{
				\Thinker\Http\Redirect::go('LotConsole', 'manage', array('id' => $id, 'phase' => 'statusUpdateSuccess'));
			}
			else
			{
				\Thinker\Framework\Notification::push("Failed to create the lot status log, please try again.", "error");
			}
		}
		else
		{
			\Thinker\Framework\Notification::push("You do not have access to do that.", "warning");
		}
	}
}
?>