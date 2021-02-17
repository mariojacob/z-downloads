<?php
/**
 * Plugin Name:     Z-Downloads
 * Version:         1.7.1
 * Plugin URI:      https://code.urban-base.net/z-downloads?utm_source=zdm_plugin_uri
 * Description:     Download Manager.
 * Author:          URBAN BASE
 * Author URI:      https://urban-base.net/?utm_source=zdm_author_uri
 * Copyright:       Mario Maier
 * Text Domain:     zdm
 * Domain Path:     /languages
 * License:         GPLv2
 * License URI:     https://www.gnu.org/licenses/gpl-2.0.html
 */

// Abort by direct access
if (!defined('ABSPATH')) {
    die;
}

// constants
define('ZDM__PATH', plugin_dir_path(__FILE__));
define('ZDM__PLUGIN_URL', plugin_dir_url(__FILE__));
define('ZDM__SLUG', 'z-downloads');
define('ZDM__TITLE', 'Z-Downloads');
define('ZDM__VERSION', '1.7.1');
define('ZDM__URL', 'https://code.urban-base.net/z-downloads?utm_source=zdm_backend');
define('ZDM__PRO', 'Premium');
define('ZDM__PRO_URL', 'https://code.urban-base.net/z-downloads?utm_source=zdm_backend_premium');
define('ZDM__STANDARD_USER_ROLE', 'manage_options');
require_once(dirname(__FILE__) . '/lib/constants.php');

// load language files
load_plugin_textdomain( 'zdm', false, dirname(plugin_basename(__FILE__)) . '/languages' );

// CLoad core class
if( class_exists('ZDMCore') === false ) {
	require_once(dirname(__FILE__) . '/lib/ZDMCore.php');
    $zdmCore = new ZDMCore();
    $zdmCore->register();
    $zdmCore->download();

    require_once(dirname(__FILE__) . '/lib/ZDMStat.php');
}

// Plugin activation
require_once (plugin_dir_path(__FILE__) . 'lib/ZDMPluginActivate.php');
register_activation_hook(__FILE__, array('ZDMPluginActivate', 'activate'));

// Plugin deactivation
require_once (plugin_dir_path(__FILE__) . 'lib/ZDMPluginDeactivate.php');
register_deactivation_hook(__FILE__, array('ZDMPluginDeactivate', 'deactivate'));

// Plugin upgrade
require_once (plugin_dir_path(__FILE__) . '/lib/upgrade.php');

ZDMCore::php_modules_check_and_notice();