<?php
	/**
	 * SiteManagement.php
	 * Contains the Class for the SiteManagement Controller
	 *
	 * @author Cory Gehr
	 */

namespace EocCap;

class SiteManagement extends \Thinker\Framework\Controller
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
			case 'addUser':
				$this->addUser();
			break;

			case 'success':
				// Push success message
				\Thinker\Framework\Notification::push("Created the user successfully!", "success");
			break;
		}

		// Provide form with all lots and User Types
		$this->set('LOTS', Lot::findAll());
		$this->set('USER_TYPES', User::getUserTypes());

		// Get a list of all users
		$this->set('USERS', User::fetchAll());
	}

	/**
	 * addUser()
	 * Adds a user to the database
	 *
	 * @access private
	 */
	private function addUser()
	{
		// Gather data
		$username = \Thinker\Http\Request::post('username', true);
		$name = \Thinker\Http\Request::post('name', true);
		$type = \Thinker\Http\Request::post('type', true);
		$lot = \Thinker\Http\Request::post('lot', ($type == 1 ? false : true));

		// Check for success
		if(User::create(array($username, $name, $type, $type), $lot))
		{
			// Redirect for success
			\Thinker\Http\Url::redirect('SiteManagement', 'manage', array('phase' => 'success'));
		}
		else
		{
			\Thinker\Framework\Notification::push("Failed to create the user, please try again.", "error");
		}
	}
}
?>