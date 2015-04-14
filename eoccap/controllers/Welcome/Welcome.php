<?php
	/**
	 * Welcome.php
	 * Contains the Class for the Welcome Controller
	 *
	 * @author Cory Gehr
	 */
	
namespace EocCap;

class Welcome extends \Thinker\Framework\Controller
{
	protected $allowOpenAccess = true;

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
		return 'info';
	}

	/**
	 * info()
	 * Passes data back for the 'info' subsection
	 *
	 * @access public
	 */
	public function info()
	{
		// Nothing...
	}
}
?>