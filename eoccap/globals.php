<?php
	/**
	 * thinkerfw/globals.php
	 * Creates application-specific global variables
	 *
	 * @author Cory Gehr
	 */

// General settings
define('BASE_URL', ($_APP_CONFIG['general']['use_ssl'] == true ? 'https://' : 'http://') . $_APP_CONFIG['general']['base_url'] . '/');
define('SESSION_CLASS', $_APP_CONFIG['general']['session_class']);
define('APP_NAMESPACE', $_APP_CONFIG['general']['namespace']);

// View Settings
define('DEFAULT_VIEW', $_APP_CONFIG['view']['default_view']);

// Additional options
define('MAPS_KML_URL', $_APP_CONFIG['other']['maps_kml_url']);
define('REPORT_FAIL_TEXT', $_APP_CONFIG['other']['report_fail_text']);

?>