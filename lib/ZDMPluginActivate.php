<?php

// Abbruch bei direktem Zugriff
if (!defined('ABSPATH')) {
    die;
}

/**
 * Plugin Aktivierung
 */
class ZDMPluginActivate {

    /**
     * Methode wenn Plugin aktiviert wird
     *
     * @return void
     */
    public static function activate() {

        flush_rewrite_rules();

        if (get_option('zdm_options')) {
            update_option('zdm_options', ZDM__OPTIONS);
        } else {
            add_option('zdm_options', ZDM__OPTIONS);
        }

        // Optionen abrufen
        $zdm_options = get_option('zdm_options');

        // Aktivierungszeit setzen
        if (!$zdm_options['activation-time']) {
            $zdm_options['activation-time'] = time();
        }

        // Download Ordner Token
        if (!$zdm_options['download-folder-token']) {
            $zdm_options['download-folder-token'] = md5(uniqid(rand(), true));
        }

        update_option('zdm_options', $zdm_options);

        // Optionen abrufen
        $zdm_options = get_option('zdm_options');

        // Datenbank erstellen
        require_once (plugin_dir_path(__FILE__) . 'ZDMDatabase.php');
        $db = new ZDMDatabase();
        $db->create_db();

        // Log
        ZDMCore::log('plugin activated');
    }
}
