<?php
	/**
	 * EventManagement.php
	 * Contains the Class for the EventManagement Controller
	 *
	 * @author Cory Gehr
	 */

namespace EocCap;

class EventManagement extends \Thinker\Framework\Controller
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
	 * edit()
	 * Passes data back for the 'edit' subsection
	 *
	 * @access public
	 */
	public function edit()
	{
		// Get Event ID
		$eventId = \Thinker\Http\Request::request('id');

		if($eventId)
		{
			// Load Event Object
			$event = new Event($eventId);

			if($event)
			{
				$phase = \Thinker\Http\Request::request('phase');

				switch($phase)
				{
					case 'deleteEvent':
						$this->deleteEvent();
					break;

					case 'updateEvent':
						$this->updateEvent();
					break;

					case 'updateSuccess':
						// Push success message
						\Thinker\Framework\Notification::push("Updated the event successfully!", "success");
					break;
				}

				// Provide form with all events
				$this->set('event', $event);
			}
			else
			{
				// Throw error
				\Thinker\Framework\Notification::push("Failed to retrieve the specified Event, please try again.", "error");
			}
		}
		else
		{
			// Throw error
			\Thinker\Framework\Notification::push("No Event ID specified, cannot continue.", "warning");
		}
	}

	/**
	 * manage()
	 * Passes data back for the 'manage' subsection
	 *
	 * @access public
	 */
	public function manage()
	{
		$phase = \Thinker\Http\Request::request('phase');

		switch($phase)
		{
			case 'addEvent':
				$this->addEvent();
			break;

			case 'deleteSuccess':
				\Thinker\Framework\Notification::push("Removed the event successfully!", "success");
			break;

			case 'success':
				// Push success message
				\Thinker\Framework\Notification::push("Created the event successfully!", "success");
			break;
		}

		// Provide form with all events
		$this->set('EVENTS', Event::fetchAll(true));
	}

	/**
	 * addEvent()
	 * Adds an event to the database
	 *
	 * @access private
	 */
	private function addEvent()
	{
		// Gather data
		$name = \Thinker\Http\Request::post('name', true);
		$startDate = \Thinker\Http\Request::post('start_date', true);
		$startTime = \Thinker\Http\Request::post('start_time', true);
		$endDate = \Thinker\Http\Request::post('end_date', true);
		$endTime = \Thinker\Http\Request::post('end_time', true);

		$start = $this->mergeDateTime($startDate, $startTime);
		$end = $this->mergeDateTime($endDate, $endTime);

		// Check for success
		if(Event::create(array($name, $start, $end)))
		{
			// Redirect for success
			\Thinker\Http\Redirect::go('EventManagement', 'manage', array('phase' => 'success'));
		}
		else
		{
			\Thinker\Framework\Notification::push("Failed to create the event, please try again.", "error");
		}
	}

	/**
	 * deleteEvent()
	 * Deactivates the specified event
	 *
	 * @access private
	 */
	private function deleteEvent()
	{
		// Get ID of target event
		$id = \Thinker\Http\Request::post('id', true);

		// Create event object
		$event = new Event($id);

		// Deactivate
		if($event->delete())
		{
			// Redirect
			\Thinker\Http\Redirect::go('EventManagement', 'manage', array('phase' => 'deleteSuccess'));
		}
		else
		{
			// Throw error
			\Thinker\Framework\Notification::push("Failed to delete the event, please try again.", "error");
		}
	}

	/**
	 * updateEvent()
	 * Updates an event object
	 *
	 * @access private
	 */
	private function updateEvent()
	{
		// Gather data
		$id = \Thinker\Http\Request::post('id', true);
		$name = \Thinker\Http\Request::post('name', true);
		$startDate = \Thinker\Http\Request::post('start_date', true);
		$startTime = \Thinker\Http\Request::post('start_time', true);
		$endDate = \Thinker\Http\Request::post('end_date', true);
		$endTime = \Thinker\Http\Request::post('end_time', true);

		$start = $this->mergeDateTime($startDate, $startTime);
		$end = $this->mergeDateTime($endDate, $endTime);

		// Create event object
		$event = new Event($id);

		if($event)
		{
			// Update fields
			$event->name = $name;
			$event->start_time = $start;
			$event->end_time = $end;

			if($event->update())
			{
				// Redirect
				\Thinker\Http\Redirect::go("EventManagement", "edit", array('id' => $id, 'phase' => 'updateSuccess'));
			}
			else
			{
				// Throw error
				\Thinker\Framework\Notification::push("Failed to update event, please try again.", "error");
			}
		}
		else
		{
			\Thinker\Framework\Notification::push("Failed to update event, please try again.", "error");
		}
	}

	/**
	 * mergeDateTime()
	 * Merges a date and time supplied by the user
	 *
	 * @access private
	 * @param string $date Date value
	 * @param string $time Time value
	 */
	private function mergeDateTime($date, $time)
	{
		return date('Y-m-d H:i:s', strtotime("$date $time"));
	}
}
?>