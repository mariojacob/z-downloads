<?php
// Abort by direct access
if (!defined('ABSPATH'))
    die;

if (!defined('WP_UNINSTALL_PLUGIN'))
    die;

////////////////////
// Konstanten laden
////////////////////

if (!defined('ZDM__PATH'))
    define('ZDM__PATH', plugin_dir_path(__FILE__));
if (!defined('ZDM__PLUGIN_URL'))
    define('ZDM__PLUGIN_URL', plugin_dir_url(__FILE__));

// Neu: Version laden
require_once ZDM__PATH . 'lib/version.php';

if (!defined('ZDM__SLUG'))
    define('ZDM__SLUG', 'z-downloads');
if (!defined('ZDM__TITLE'))
    define('ZDM__TITLE', 'Z-Downloads');
if (!defined('ZDM__PRO'))
    define('ZDM__PRO', 'Premium');
if (!defined('ZDM__PRO_URL'))
    define('ZDM__PRO_URL', 'https://urbanbase.gumroad.com/l/zdPRE');
if (!defined('ZDM__STANDARD_USER_ROLE'))
    define('ZDM__STANDARD_USER_ROLE', 'manage_options');

require_once ZDM__PATH . 'lib/constants.php';

$zdm_options = get_option('zdm_options', ZDM__OPTIONS);
$zdm_upload_dir = wp_upload_dir();

$zdm_download_token = isset($zdm_options['download-folder-token']) ? $zdm_options['download-folder-token'] : '';

if ($zdm_download_token === '') {
    $existing_dirs = glob(trailingslashit($zdm_upload_dir['basedir']) . 'z-downloads-*', GLOB_ONLYDIR);

    if (!empty($existing_dirs)) {
        $first_dir = basename($existing_dirs[0]);
        $possible_token = substr($first_dir, strlen('z-downloads-'));

        if (!empty($possible_token))
            $zdm_download_token = $possible_token;
    }
}

if ($zdm_download_token === '')
    $zdm_download_token = md5(uniqid(rand(), true));

if (!defined('ZDM__DOWNLOADS_PATH'))
    define('ZDM__DOWNLOADS_PATH', trailingslashit($zdm_upload_dir['basedir']) . 'z-downloads-' . $zdm_download_token);
if (!defined('ZDM__DOWNLOADS_CACHE_PATH'))
    define('ZDM__DOWNLOADS_CACHE_PATH', ZDM__DOWNLOADS_PATH . '/cache');
if (!defined('ZDM__DOWNLOADS_FILES_PATH'))
    define('ZDM__DOWNLOADS_FILES_PATH', ZDM__DOWNLOADS_PATH . '/files');

require_once ZDM__PATH . 'lib/ZDMCore.php';

////////////////////
// Alle Daten löschen
////////////////////

ZDMCore::delete_all_data();

////////////////////
// Benutzerdefinierte Datenbank löschen
////////////////////

global $wpdb;

$wpdb->query('DROP TABLE IF EXISTS ' . $wpdb->prefix . 'zdm_archives');
$wpdb->query('DROP TABLE IF EXISTS ' . $wpdb->prefix . 'zdm_files');
$wpdb->query('DROP TABLE IF EXISTS ' . $wpdb->prefix . 'zdm_files_rel');
$wpdb->query('DROP TABLE IF EXISTS ' . $wpdb->prefix . 'zdm_log');

////////////////////
// Optionen löschen
////////////////////

delete_option('zdm_options');
