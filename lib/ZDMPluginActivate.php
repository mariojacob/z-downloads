<?php

// Abbruch bei direktem Zugriff
if( !defined( 'ABSPATH' ) ) {
    die;
}

/**
 * Plugin Aktivierung
 */
class ZDMPluginActivate
{

    /**
     * Methode wenn Plugin aktiviert wird
     *
     * @return void
     */
    public static function activate()
    {

        flush_rewrite_rules();

        // Grundeinstellungen festlegen
        $options = array(
            'version'                       => ZDM__VERSION,
            'licence-key'                   => '',
            'licence-email'                 => '',
            'licence-purchase'              => '',
            'licence-product-name'          => '',
            'licence-time'                  => 0,
            'db-version'                    => 1,
            'download-btn-text'             => 'Download',
            'download-btn-style'            => 'black',
            'download-btn-outline'          => '',
            'download-btn-border-radius'    => 'none',
            'download-btn-icon'             => 'none',
            'download-btn-icon-only'        => '',
            'download-btn-css'              => '',
            'secure-ip'                     => 'on',
            'file-open-in-browser-pdf'      => ''
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

        // Log
        ZDMCore::log('plugin aktivate');
    }
}
