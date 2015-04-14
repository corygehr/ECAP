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
	 * handle()
	 * Handles the logout for the current user
	 *
	 * @access public
	 */
	public function handle()
	{
		// Process logout

		// Destroy session cookie
		if(isset($_COOKIE[session_name()]))
		{
			setcookie(session_name(), '', time()-3600, '/');
		}

		$_SESSION = null;

		// Destroy session
		session_destroy();

		// Redirect
		\Thinker\Http\Redirect::go('Welcome');
	}
}
?>