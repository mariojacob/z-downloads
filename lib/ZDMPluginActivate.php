<?php

// Abort by direct access
if (!defined('ABSPATH')) {
    die;
}

/**
 * Plugin Aktivierung
 */
class ZDMPluginActivate {

    /**
     * Plugin aktivieren
     *
     * @return void
     */
    public static function activate() {

        flush_rewrite_rules();

        if (get_option('zdm_options'))
            update_option('zdm_options', ZDM__OPTIONS);
        else
            add_option('zdm_options', ZDM__OPTIONS);

        // Optionen abrufen
        $zdm_options = get_option('zdm_options');

        // Aktivierungszeit einstellen
        if (!$zdm_options['activation-time'])
            $zdm_options['activation-time'] = time();

        // Optionen abrufen
        $zdm_options = get_option('zdm_options');

        // Datenbank erstellen
        require_once (plugin_dir_path(__FILE__) . 'ZDMDatabase.php');
        $db = new ZDMDatabase();
        $db->create_db();

        ZDMCore::log('plugin activated');

        // Download-Ordner-Token
        if (!$zdm_options['download-folder-token']) {
            $zdm_options['download-folder-token'] = md5(uniqid(rand(), true));
            ZDMCore::log('download-folder-token', $zdm_options['download-folder-token']);
        }

        update_option('zdm_options', $zdm_options);
    }
}
