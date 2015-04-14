<?php
	/**
	 * LocalAuth.php
	 * Contains a sample Local Authentication Session Class for THINKer
	 *
	 * @author Cory Gehr
	 */

class LocalAuth extends Thinker\Framework\Session
{
	/**
	 * __construct()
	 * Constructor for the THINKER_Session_LocalAuth class
	 *
	 * @access protected
	 */
	protected function __construct()
	{
		// Call parent constructor
		parent::__construct();
	}

	/**
	 * auth()
	 * Checks current user's access to an object
	 *
	 * @author Cory Gehr
	 * @param $objType: Object Type (default: section)
	 * @param $params: Object Parameters (default: empty array)
	 * @return True if Authorized, False if Denied
	 */
	public function auth($objType = 'section', $params = array())
	{
		global $_DB;

		switch($objType)
		{
			case 'section':
				if(count($params) >= 2)
				{
					$data = array(
						':section' => $params['section'],
						':subsection' => $params['subsection'],
						':username' => $_SESSION['USER']->username
						);

					$limiters = '';

					// Add additional URL params if exists
					if(isset($params['url_params']))
					{
						foreach($params['url_params'] as $id => $val)
						{
							$limiters .= " AND IF(uri.id IS NOT NULL, (uri.identifier_name IN(:$id, '*') AND uri.identifier_value IN (:$val, '*')), 1)";
							$data[":$id"] = $id;
							$data[":$val"] = $val;
						}
					}

					// Query
					$query = "SELECT COUNT(1)
							  FROM user_rights ur 
							  LEFT JOIN user_rights_identifiers uri ON uri.right_id = ur.id 
							  WHERE ur.username = :username 
							  AND (ur.s IN(:section,'*')) AND (ur.ss IN(:subsection, '*'))
							  $limiters 
							  LIMIT 1";
							  
					return $_DB['eoc_cap_mgmt']->doQueryAns($query, $data);
				}
				else
				{
					throw new Exception("Invalid number of parameters specified for auth().");
					return false;
				}
			break;

			default:
				return false;
			break;
		}
	}
}