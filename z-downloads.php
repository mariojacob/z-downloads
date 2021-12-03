<?php

/**
 * Plugin Name:     Z-Downloads
 * Version:         1.8.2
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
if (!defined('ABSPATH'))
    die;

// constants
define('ZDM__PATH', plugin_dir_path(__FILE__));
define('ZDM__PLUGIN_URL', plugin_dir_url(__FILE__));
define('ZDM__SLUG', 'z-downloads');
define('ZDM__TITLE', 'Z-Downloads');
define('ZDM__VERSION', '1.9.0');
define('ZDM__URL', 'https://code.urban-base.net/z-downloads?utm_source=zdm_backend');
define('ZDM__PRO', 'Premium');
define('ZDM__PRO_URL', 'https://urbanbase.gumroad.com/l/zdPRE');
define('ZDM__STANDARD_USER_ROLE', 'manage_options');
require_once(dirname(__FILE__) . '/lib/constants.php');

if (!get_option('zdm_options'))
    add_option('zdm_options', ZDM__OPTIONS);

$zdm_options = get_option('zdm_options');

// Download-Ordner-Token
if ($zdm_options['download-folder-token'] == '') {
    $zdm_options['download-folder-token'] = md5(uniqid(rand(), true));
    update_option('zdm_options', $zdm_options);
    $zdm_options = get_option('zdm_options');
}

if (!defined('ZDM__DOWNLOADS_PATH'))
    define('ZDM__DOWNLOADS_PATH', wp_upload_dir()['basedir'] . "/z-downloads-" . $zdm_options['download-folder-token']);
if (!defined('ZDM__DOWNLOADS_CACHE_PATH'))
    define('ZDM__DOWNLOADS_CACHE_PATH', ZDM__DOWNLOADS_PATH . "/cache");
if (!defined('ZDM__DOWNLOADS_FILES_PATH'))
    define('ZDM__DOWNLOADS_FILES_PATH', ZDM__DOWNLOADS_PATH . "/files");
if (!defined('ZDM__DOWNLOADS_PATH_URL'))
    define('ZDM__DOWNLOADS_PATH_URL', wp_upload_dir()['baseurl'] . "/z-downloads-" . $zdm_options['download-folder-token']);
if (!defined('ZDM__DOWNLOADS_CACHE_PATH_URL'))
    define('ZDM__DOWNLOADS_CACHE_PATH_URL', ZDM__DOWNLOADS_PATH_URL . "/cache");
if (!defined('ZDM__DOWNLOADS_FILES_PATH_URL'))
    define('ZDM__DOWNLOADS_FILES_PATH_URL', ZDM__DOWNLOADS_PATH_URL . "/files");


// load language files
load_plugin_textdomain('zdm', false, dirname(plugin_basename(__FILE__)) . '/languages');

// CLoad core class
if (class_exists('ZDMCore') === false) {
    require_once(dirname(__FILE__) . '/lib/ZDMCore.php');
    $zdmCore = new ZDMCore();
    $zdmCore->register();
    $zdmCore->download();

    require_once(dirname(__FILE__) . '/lib/ZDMStat.php');
}

// Plugin activation
require_once(plugin_dir_path(__FILE__) . 'lib/ZDMPluginActivate.php');
register_activation_hook(__FILE__, array('ZDMPluginActivate', 'activate'));

// Plugin deactivation
require_once(plugin_dir_path(__FILE__) . 'lib/ZDMPluginDeactivate.php');
register_deactivation_hook(__FILE__, array('ZDMPluginDeactivate', 'deactivate'));

// Plugin upgrade
require_once(plugin_dir_path(__FILE__) . '/lib/upgrade.php');

$zdmCore->php_modules_check_and_notice();
