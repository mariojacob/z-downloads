<?php

// Abbruch bei direktem Zugriff
if( !defined( 'ABSPATH' ) ) {
    die;
}

if (!defined('WP_UNINSTALL_PLUGIN')) {
    die;
}


////////////////////
// Custom Datenbank löschen
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
