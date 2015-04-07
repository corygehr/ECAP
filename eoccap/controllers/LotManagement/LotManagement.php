<?php
	/**
	 * LotManagement.php
	 * Contains the Class for the LotManagement Controller
	 *
	 * @author Cory Gehr
	 */
	
namespace EocCap;

class LotManagement extends \Thinker\Framework\Controller
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
		// Get all lots
		$this->set('LOTS', Lot::findAll());
	}
}
?>