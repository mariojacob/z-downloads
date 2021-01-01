<?php

// Abort by direct access
if (!defined('ABSPATH')) {
    die;
}

/**
 * Plugin activation
 */
class ZDMPluginActivate {

    /**
     * Method when plugin is activated
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

        // Get options
        $zdm_options = get_option('zdm_options');

        // Set activation time
        if (!$zdm_options['activation-time']) {
            $zdm_options['activation-time'] = time();
        }

        // Download folder token
        if (!$zdm_options['download-folder-token']) {
            $zdm_options['download-folder-token'] = md5(uniqid(rand(), true));
        }

        update_option('zdm_options', $zdm_options);

        // Get options
        $zdm_options = get_option('zdm_options');

        // Create database
        require_once (plugin_dir_path(__FILE__) . 'ZDMDatabase.php');
        $db = new ZDMDatabase();
        $db->create_db();

        // Log
        ZDMCore::log('plugin activated');
    }
}
