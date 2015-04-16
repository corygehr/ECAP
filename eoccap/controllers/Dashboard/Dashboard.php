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
	 * fullscreen
	 * Passes data back for the 'fullscreen' subsection
	 *
	 * @access public
	 */
	public function fullscreen()
	{
		global $_APP_CONFIG;

		// Call view()
		$this->view();

		// Change the view template
		$_APP_CONFIG['view-html']['template'] = 'bare';
	}

	/**
	 * view()
	 * Passes data back for the 'view' subsection
	 *
	 * @access public
	 */
	public function view()
	{
		// Fetch all possible lot statuses and lots
		$this->set('STATUSES', LotStatus::fetchAll());
		$this->set('LOTS', Lot::fetchAllExtended(true));
	}
}
?>