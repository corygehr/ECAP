<?php
	/**
	 * MyLots.php
	 * Contains the Class for the MyLots Controller
	 *
	 * @author Cory Gehr
	 */

namespace EocCap;

class MyLots extends \Thinker\Framework\Controller
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
		return 'view';
	}

	/**
	 * view()
	 * Passes data back for the 'view' subsection
	 *
	 * @access public
	 */
	public function view()
	{
		// Get all lots that the current user is allowed to access
		$this->set('LOTS', Lot::fetchResponsibleLotsForUser($_SESSION['USER']->username));
	}
}
?>