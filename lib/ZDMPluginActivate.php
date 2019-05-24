<?php

// Abbruch bei direktem Zugriff
if( !defined( 'ABSPATH' ) ) {
    die;
}

/**
 *
 */
class ZDMPluginActivate
{

    public static function activate()
    {

        flush_rewrite_rules();

        // Grundeinstellungen festlegen
        $options = array(
            'version'               => ZDM__VERSION,
            'licence-key'           => '',
            'licence-email'         => '',
            'licence-purchase'      => '',
            'licence-product-name'  => '',
            'licence-time'          => 0,
            'db-version'            => 1,
            'download-btn-text'     => 'Download',
            'download-btn-css'      => '',
            'secure-ip'             => 'on',
        );

        if (get_option('zdm_options')) {
            update_option('zdm_options', $options);
        } else {
            add_option('zdm_options', $options);
        }

        // Datenbank erstellen
        require_once (plugin_dir_path(__FILE__) . 'ZDMDatabase.php');
        $db = new ZDMDatabase();
        $db->create_db();
    }
}
