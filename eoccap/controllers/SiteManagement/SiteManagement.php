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

			case 'deleteUserSuccess':
				\Thinker\Framework\Notification::push("Removed the user successfully!", "success");
			break;

			case 'resetDb':
				$this->resetLots();
			break;

			case 'resetSuccess':
				// Push success message
				\Thinker\Framework\Notification::push("Reset database successfully!", "success");
			break;

			case 'updateAdvancedSettings':
				$this->updateAdvancedSettings();
			break;

			case 'updateAdvancedSettingsSuccess':
				\Thinker\Framework\Notification::push("Updated settings successfully!", "success");
			break;

			case 'userCreateSuccess':
				// Push success message
				\Thinker\Framework\Notification::push("Created the user successfully!", "success");
			break;
		}

		// Provide form with all lots and User Types
		$this->set('LOTS', Lot::fetchAll(true));
		$this->set('USER_TYPES', User::getUserTypes());

		// Get a list of all users
		$this->set('USERS', User::fetchAll(true));

		// Provide all PHP Timezones
		$this->set('SUPPORTED_TIMEZONES', \DateTimeZone::listIdentifiers(\DateTimeZone::ALL));
		$this->set('TIMEZONE', date_default_timezone_get());
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
		$name = \Thinker\Http\Request::post('full_name', true);
		$type = \Thinker\Http\Request::post('access_type', true);
		$password = \Thinker\Http\Request::post('password', ($type == 1 ? true : false));
		$lot = \Thinker\Http\Request::post('lot', ($type == 1 ? false : true));

		// Check for success
		if(User::create(array($username, $name, $type, $type), $password, $lot))
		{
			// Redirect for success
			\Thinker\Http\Redirect::go('SiteManagement', 'manage', array('phase' => 'userCreateSuccess'));
		}
		else
		{
			\Thinker\Framework\Notification::push("Failed to create the user, please try again.", "error");
		}
	}

	/**
	 * resetLots()
	 * Resets all lots to zero capacity and closed statuses
	 *
	 * @access private
	 */
	private function resetLots()
	{
		global $_DB;

		// Get all lots
		$lots = Lot::fetchAll(true);

		if($lots)
		{
			// Start transaction
			if($_DB['eoc_cap_mgmt']->beginTransaction())
			{
				foreach($lots as $lot)
				{
					// Create new capacity and status entries
					if(!LotStatusLog::create(array($lot['id'], 3, "(System Reset)")) || 
						!LotCapacity::create(array($lot['id'], 0)))
					{
						// Rollback
						$_DB['eoc_cap_mgmt']->rollBack();
						\Thinker\Framework\Notification::push("Failed to update lot {$lot['id']}, changes have NOT been saved.", "error");
						return false;
					}
				}

				// Commit
				if($_DB['eoc_cap_mgmt']->commit())
				{
					// Redirect
					\Thinker\Http\Redirect::go('SiteManagement', 'manage', array('phase' => 'resetSuccess'));
				}
				else
				{
					$_DB['eoc_cap_mgmt']->rollBack();
					\Thinker\Framework\Notification::push("Failed to commit transaction, cannot continue.", "error");
					return false;
				}
			}
			else
			{
				\Thinker\Framework\Notification::push("Failed to start transaction, cannot continue.", "error");
				return false;
			}
		}
		else
		{
			\Thinker\Framework\Notification::push("Failed to pull lot list, cannot continue.", "error");
			return false;
		}
	}

	/**
	 * updateAdvancedSettings()
	 * Updates advanced settings in the database
	 *
	 * @access private
	 */
	private function updateAdvancedSettings()
	{
		// Get value and set global
		$timezone = \Thinker\Http\Request::post('timezone', true);

		if(SiteGlobal::update('TIMEZONE', $timezone))
		{
			// Redirect
			\Thinker\Http\Redirect::go('SiteManagement', 'manage', array('phase' => 'updateAdvancedSettingsSuccess'));
		}
		else
		{
			// Throw error
			\Thinker\Framework\Notification::push("Failed to update settings, please try again.", "error");
			return false;
		}
	}
}
?>