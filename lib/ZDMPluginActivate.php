<?php
// Abort by direct access
if (!defined('ABSPATH'))
    die;

/**
 * Plugin Aktivierung
 */
class ZDMPluginActivate
{

    /**
     * Plugin aktivieren
     *
     * @return void
     */
    public static function activate()
    {

        flush_rewrite_rules();

        if (get_option('zdm_options'))
            update_option('zdm_options', ZDM__OPTIONS);
        else
            add_option('zdm_options', ZDM__OPTIONS);

        // Optionen abrufen
        $zdm_options = get_option('zdm_options');

        // Aktivierungszeit einstellen
        if (!array_key_exists('activation-time', $zdm_options))
            $zdm_options['activation-time'] = time();

        // Optionen abrufen
        $zdm_options = get_option('zdm_options');

        // Datenbank erstellen
        require_once(plugin_dir_path(__FILE__) . 'ZDMDatabase.php');
        $db = new ZDMDatabase();
        $db->create_db();

        ZDMCore::log('plugin activated');

        // Download-Ordner-Token
        if (!array_key_exists('download-folder-token', $zdm_options)) {
            $zdm_options['download-folder-token'] = md5(uniqid(rand(), true));
            update_option('zdm_options', $zdm_options);
            ZDMCore::log('download-folder-token', $zdm_options['download-folder-token']);
        }

        update_option('zdm_options', $zdm_options);

        $zdm_options = get_option('zdm_options');

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
    }
}
