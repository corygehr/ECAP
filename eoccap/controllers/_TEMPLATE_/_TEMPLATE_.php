<?php
	/**
	 * _TEMPLATE_.php
	 * Contains the Class for the _TEMPLATE_ Controller
	 *
	 * @author Cory Gehr
	 */

namespace {AppNamespace};

class _TEMPLATE_ extends \Thinker\Framework\Controller
{
	// All public functions will be accessible by users via the 'subsection' URL parameter
	// Therefore, functions should only be public if they'll be accessed by the user to
	// grab data (ex. URL/index.php?section=MySection&subsection=info)
	// Others that simply manipulate data should be private.

	// Uncomment this line if you want to allow ANY user (public) to access the methods of this controller
	// protected $allowPublicAccess = true;

	/**
	 * info()
	 * Passes data back for the 'info' subsection
	 *
	 * @access public
	 */
	public function info()
	{
		// Subsection actions go here, if any are required
	}
}
?>