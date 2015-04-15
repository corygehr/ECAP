<?php
	/**
	 * UserManagement.php
	 * Contains the Class for the UserManagement Controller
	 *
	 * @author Cory Gehr
	 */

namespace EocCap;

class UserManagement extends \Thinker\Framework\Controller
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
		// Get the user object specified
		$username = \Thinker\Http\Request::request('username', true);

		if($username)
		{
			// Get phase
			$phase = \Thinker\Http\Request::request('phase');

			switch($phase)
			{
				case 'addRight':
					$this->addRight();
				break;

				case 'addRightSuccess':
					\Thinker\Framework\Notification::push("Added the permissions successfully!", "success");
				break;

				case 'deleteRightIdentifier':
					$this->deleteRightIdentifier();
				break;

				case 'deleteRightSuccess':
					\Thinker\Framework\Notification::push("Removed permissions successfully!", "success");
				break;

				case 'deleteUser':
					$this->deleteUser();
				break;

				case 'updateInformation':
					$this->updateInformation();
				break;

				case 'updateInformationSuccess':
					\Thinker\Framework\Notification::push("Updated user information successfully!", "success");
				break;

				case 'updatePassword':
					$this->updatePassword();
				break;

				case 'updatePasswordSuccess':
					\Thinker\Framework\Notification::push("Updated user password successfully!", "success");
				break;
			}

			// Try to create user object
			$user = new User($username);

			if(!empty($user))
			{
				// Pass back user object
				$this->set('User', $user);

				// Pass back all possible lots
				$this->set('LOTS', Lot::fetchAll(true));

				// Pass back all lots the user is responsible for, if they're an attendant
				// Admins already have full access
				if($user->user_type == 2)
				{
					$this->set('USER_LOTS', Lot::fetchResponsibleLotsForUser($user->username));
				}

				// Pass back user type name
				$this->set('user_type_name', User::fetchUserTypeName($user->user_type));
			}
			else
			{
				\Thinker\Framework\Notification::push("Invalid user specified.", "error");
			}
		}
		else
		{
			\Thinker\Framework\Notification::push("No user specified.", "warning");
		}
	}

	/**
	 * addRight()
	 * Adds an access right for the current user
	 *
	 * @access private
	 */
	private function addRight()
	{
		// Get information
		$username = \Thinker\Http\Request::post('username', true);
		$lot = \Thinker\Http\Request::post('lot', true);

		if(User::addUserRightIdentifier(array(
			User::fetchUserRight($username, 'LotConsole', 'manage'), 
			'id', $lot)))
		{
			// Redirect
			\Thinker\Http\Redirect::go('UserManagement', 'manage', array('username' => $username, 'phase' => 'addRightSuccess'));
		}
		else
		{
			\Thinker\Framework\Notification::push("Failed to add permissions, please try again.", "error");
		}
	}

	/**
	 * deleteRightIdentifier()
	 * Deletes a user right identifier
	 *
	 * @access private
	 */
	private function deleteRightIdentifier()
	{
		// Get information
		$username = \Thinker\Http\Request::get('username', true);
		$rightIdentifierVal = \Thinker\Http\Request::get('rightIdentifierVal', true);

		// Remove
		if(User::deleteRightIdentifier(User::fetchUserRight($username, 'LotConsole', 'manage'),
			'id', $rightIdentifierVal))
		{
			// Redirect
			\Thinker\Http\Redirect::go('UserManagement', 'manage', array('username' => $username, 'phase' => 'deleteRightSuccess'));
		}
		else
		{
			\Thinker\Framework\Notification::push("Failed to remove permissions, please try again.", "error");
		}
	}

	/**
	 * deleteUser()
	 * Deactivates the user
	 *
	 * @access private
	 */
	private function deleteUser()
	{
		// Get username
		$username = \Thinker\Http\Request::post('username', true);

		// Create object for deactivation
		$user = new User($username);

		if($user->delete())
		{
			// Redirect to SiteManagement
			\Thinker\Http\Redirect::go('SiteManagement', 'manage', array('phase' => 'deleteUserSuccess'));
		}
		else
		{
			\Thinker\Framework\Notification::push("Failed to delete the user, please try again.", "error");
		}
	}

	/**
	 * updateInformation()
	 * Updates a user's information
	 *
	 * @access private
	 */
	private function updateInformation()
	{
		// Get information
		$username = \Thinker\Http\Request::post('username', true);

		// Create user object
		$user = new User($username);

		// Update fields
		$user->full_name = \Thinker\Http\Request::post('full_name', true);

		// Commit
		if($user->update())
		{
			// Redirect
			\Thinker\Http\Redirect::go('UserManagement', 'manage', array('username' => $username, 'phase' => 'updateInformationSuccess'));
		}
		else
		{
			\Thinker\Framework\Notification::push("Failed to update the user, please try again.", "error");
		}
	}

	/**
	 * updatePassword()
	 * Updates a user's password
	 *
	 * @access private
	 */
	private function updatePassword()
	{
		// Get username
		$username = \Thinker\Http\Request::post('username', true);

		$user = new User($username);

		// If the username is that of the current user, they must confirm their current password
		if($username == $_SESSION['USER']->username)
		{
			// Confirm
			if(!$user->passwordMatch(\Thinker\Http\Request::post('confirm_current_pwd', true)))
			{
				// Fail
				\Thinker\Framework\Notification::push("Current Password is incorrect.", "warning");
				return false;
			}
		}

		// Now, grab the passwords
		$newPassword = \Thinker\Http\Request::post('password', true);
		$confirmPassword = \Thinker\Http\Request::post('confirm_pwd', true);

		if($newPassword == $confirmPassword)
		{
			// Update
			if($user->updatePassword($newPassword))
			{
				// Redirect
				\Thinker\Http\Redirect::go('UserManagement', 'manage', array('username' => $username, 'phase' => 'updatePasswordSuccess'));
			}
			else
			{
				// Fail
				\Thinker\Framework\Notification::push("Failed to update the password, please try again.", "error");
				return false;
			}
		}
		else
		{
			// No match
			\Thinker\Framework\Notification::push("Passwords do not match, please try again.", "warning");
		}
	}
}
?>