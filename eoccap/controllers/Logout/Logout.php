<?php
	/**
	 * Logout.php
	 * Contains the Class for the Logout Controller
	 *
	 * @author Cory Gehr
	 */

namespace EocCap;

class Logout extends \Thinker\Framework\Controller
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
		return 'do';
	}

	/**
	 * do()
	 * Handles the logout for the current user
	 *
	 * @access public
	 */
	public function do()
	{
		// Process logout

		// Redirect
		\Thinker\Http\Redirect::go('Welcome');
	}
}
?>