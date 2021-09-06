<?php
// Abort by direct access
if (!defined('ABSPATH'))
    die;

if (!defined('WP_UNINSTALL_PLUGIN'))
    die;


////////////////////
// delete custom database
////////////////////

global $wpdb;

$wpdb->query('DROP TABLE IF EXISTS ' . $wpdb->prefix . 'zdm_archives');
$wpdb->query('DROP TABLE IF EXISTS ' . $wpdb->prefix . 'zdm_files');
$wpdb->query('DROP TABLE IF EXISTS ' . $wpdb->prefix . 'zdm_files_rel');
$wpdb->query('DROP TABLE IF EXISTS ' . $wpdb->prefix . 'zdm_log');

////////////////////
// delete options
////////////////////

delete_option('zdm_options');
