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
			// Load Object
			$targetLot = new Lot($lotId);

			if($targetLot)
			{
				// Process changes
				$phase = \Thinker\Http\Request::request('phase');

				switch($phase)
				{

				}

				// Pass the lot back to the view
				$this->set('Lot', $targetLot);
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
}
?>