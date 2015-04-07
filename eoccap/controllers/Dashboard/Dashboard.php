<?php
	/**
	 * Dashboard.php
	 * Contains the Class for the Dashboard Controller
	 *
	 * @author Cory Gehr
	 */
	
namespace EocCap;

class Dashboard extends \Thinker\Framework\Controller
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
		// Load the lot specified
	}
}
?>