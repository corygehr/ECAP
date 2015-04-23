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

			case 'deleteEventSuccess':
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

		$start = date('Y-m-d H:i:s', strtotime("$startDate $startTime"));
		$end = date('Y-m-d H:i:s', strtotime("$endDate $endTime"));

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
}
?>