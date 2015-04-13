<?php
	/**
	 * Login.php
	 * Contains the Class for the Login Controller
	 *
	 * @author Cory Gehr
	 */

namespace EocCap;

class Login extends \Thinker\Framework\Controller
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
		// Redirect back to home if this gets called
		\Thinker\Http\Redirect::go('Welcome');
	}

	/**
	 * administrator()
	 * Passes data back for the 'administrator' subsection
	 *
	 * @access public
	 */
	public function administrator()
	{
		$phase = \Thinker\Http\Request::post('phase');

		switch($phase)
		{
			case 'login':
				$this->processLogin('administrator');
			break;
		}
	}

	/**
	 * attendant()
	 * Passes data back for the 'attendant' subsection
	 *
	 * @access public
	 */
	public function attendant()
	{
		$phase = \Thinker\Http\Request::post('phase');

		switch($phase)
		{
			case 'login':
				$this->processLogin('attendant');
			break;
		}
	}

	/**
	 * processLogin()
	 * Handles the login for each user type
	 *
	 * @access private
	 * @param string $userType User Type
	 */
	private function processLogin($userType)
	{
		switch($userType)
		{
			// Leave these here for testing...
			case 'administrator':
				\Thinker\Http\Redirect::go('LotManagement', 'manage');
			break;

			case 'attendant':
				\Thinker\Http\Redirect::go('LotConsole', 'manage', array('id' => 1));
			break;
		}
	}
}
?>