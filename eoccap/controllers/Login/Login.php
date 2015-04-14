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
		// Ensure user isn't already logged in
		$this->userSessionExists();

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
		// Ensure user isn't already logged in
		$this->userSessionExists();

		$phase = \Thinker\Http\Request::post('phase');

		switch($phase)
		{
			case 'login':
				$this->processLogin('attendant');
			break;
		}
	}

	/**
	 * createSession()
	 * Creates the session
	 *
	 * @access private
	 * @param string $user Username
	 */
	private function createSession($user)
	{
		global $_DB;

		// Create new user object
		$user = new User($user);

		if($user)
		{
			// Store it in the session
			$_SESSION['USER'] = $user;

			return true;
		}
		else
		{
			return false;
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
		global $_DB;

		switch($userType)
		{
			// Leave these here for testing...
			case 'administrator':
				// Verify the username and password
				$un = \Thinker\Http\Request::post('username');
				$hash = User::hashPassword(\Thinker\Http\Request::post('password'));
				var_dump(\Thinker\Http\Request::post('password'));
				var_dump(\Thinker\Http\Request::post('username'));
				var_dump($hash);
				// Authenticate
				$query = "SELECT COUNT(1)
						  FROM users u 
						  JOIN user_passwords up ON up.username = u.username 
						  WHERE u.username = ? 
						  AND up.hash = ? 
						  AND u.user_type = 1 
						  AND u.delete_time IS NULL 
						  LIMIT 1";

				if(!$_DB['eoc_cap_mgmt']->doQueryAns($query, array($un, $hash)))
				{
					// Invalid username or password
					\Thinker\Framework\Notification::push("Invalid username or password, please try again.", "warning");
					return false;
				}

				// Add user object to session
				$_SESSION['USER'] = new User($un);

				// Redirect
				\Thinker\Http\Redirect::go('LotManagement', 'manage');
			break;

			case 'attendant':
				// Verify user exists
				$un = \Thinker\Http\Request::post('username');

				// Authenticate
				$query = "SELECT COUNT(1)
						  FROM users 
						  WHERE username = ? 
						  AND user_type = 2 
						  AND delete_time IS NULL 
						  LIMIT 1";

				if(!$_DB['eoc_cap_mgmt']->doQueryAns($query, array($un)))
				{
					// Invalid username
					\Thinker\Framework\Notification::push("User token is inactive or does not exist, please try again.", "warning");
				}

				$_SESSION['USER'] = new User($un);

				\Thinker\Http\Redirect::go('LotConsole', 'manage', array('id' => 1));
			break;
		}

		// We've made it this far, so create the session with the user
	}

	/**
	 * userSessionExists()
	 * Redirects if a session already exists for the user
	 *
	 * @access private
	 */
	private function userSessionExists()
	{
		if(isset($_SESSION['USER"']))
		{
			// Redirect based on type
			switch($_SESSION['USER']->user_type)
			{
				case 1:
					\Thinker\Http\Redirect::go('LotManagement', 'manage');
				break;

				case 2:
					\Thinker\Http\Redirect::go('LotConsole', 'manage', array('id' => 1));
				break;
			}
		}
	}
}
?>