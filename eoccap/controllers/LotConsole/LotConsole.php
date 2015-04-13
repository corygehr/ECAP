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

					case 'updateAttendance':
						$this->updateAttendance();
					break;

					case 'updateReadiness':
						$this->updateReadiness();
					break;

					case 'updateDetails':
						$this->updateDetails();
					break;

					case 'attendanceUpdateSuccess':
						\Thinker\Framework\Notification::push("Updated lot attendance successfully!", "success");
					break;

					case 'detailUpdateSuccess':
						\Thinker\Framework\Notification::push("Updated lot details successfully!", "success");
					break;

					case 'readinessUpdateSuccess':
						\Thinker\Framework\Notification::push("Updated lot readiness successfully!", "success");
					break;
				}

				// Pass the lot back to the view
				$this->set('Lot', $targetLot);
				$this->set('Attendance', LotAttendance::fetchCurrentLotAttendance($targetLot->id));
				$this->set('ATTENDANCE_HISTORY', LotAttendance::fetchByLot($targetLot->id, 10));
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

	/**
	 * updateAttendance()
	 * Updates the attendance logs for the current lot
	 *
	 * @access private
	 */
	private function updateAttendance()
	{
		// Get the details
		$id = \Thinker\Http\Request::post('id', true);
		$attendance = \Thinker\Http\Request::post('attendance', true);

		// Add the log for the specific lot
		if(LotAttendance::create(array($id, $attendance)))
		{
			\Thinker\Http\Redirect::go('LotConsole', 'manage', array('id' => $id, 'phase' => 'attendanceUpdateSuccess'));
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
}
?>