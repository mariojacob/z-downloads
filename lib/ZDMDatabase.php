<?php
// Abort by direct access
if (!defined('ABSPATH'))
    die;

/**
 * Datenbank Klasse
 */
class ZDMDatabase
{

    /**
     * Erstellt die Datenbankstruktur
     *
     * @return void
     */
    public function create_db()
    {
        global $wpdb;
        require_once(ABSPATH . 'wp-admin/includes/upgrade.php');

        // Tabelle zdm_log
        $table_name = $wpdb->prefix . 'zdm_log';
        $sql = "CREATE TABLE " . $table_name . "(
            id INT(11) UNSIGNED NOT NULL PRIMARY KEY AUTO_INCREMENT,
            type VARCHAR(64) NOT NULL,
            message TEXT NOT NULL,
            user_agent VARCHAR(255) NOT NULL,
            user_ip VARCHAR(255) NOT NULL,
            time_create INT(11) UNSIGNED NOT NULL)";
        if (maybe_create_table($table_name, $sql))
            ZDMCore::log('database table created', 'zdm_log');

        // Tabelle zdm_archives
        $table_name = $wpdb->prefix . 'zdm_archives';
        $sql = "CREATE TABLE " . $table_name . "(
            id INT(11) UNSIGNED NOT NULL PRIMARY KEY AUTO_INCREMENT,
            id_temp VARCHAR(32) NOT NULL,
            name VARCHAR(255) NOT NULL,
            zip_name VARCHAR(255) NOT NULL,
            description text NOT NULL,
            count INT(11) UNSIGNED NOT NULL DEFAULT 0,
            button_text VARCHAR(255) NOT NULL,
            hash_md5 VARCHAR(32) NOT NULL,
            hash_sha1 VARCHAR(40) NOT NULL,
            archive_cache_path VARCHAR(255) NOT NULL,
            file_size VARCHAR(50) NOT NULL,
            status VARCHAR(20) NOT NULL DEFAULT 'public',
            time_create INT(11) UNSIGNED NOT NULL,
            time_update INT(11) UNSIGNED NOT NULL)";
        if (maybe_create_table($table_name, $sql))
            ZDMCore::log('database table created', 'zdm_archives');

        // Tabelle zdm_files
        $table_name = $wpdb->prefix . 'zdm_files';
        $sql = "CREATE TABLE " . $table_name . "(
            id INT(11) UNSIGNED NOT NULL PRIMARY KEY AUTO_INCREMENT,
            name VARCHAR(255) NOT NULL,
            button_text VARCHAR(255) NOT NULL,
            description TEXT NOT NULL,
            count INT(11) UNSIGNED NOT NULL DEFAULT 0,
            hash_md5 VARCHAR(32) NOT NULL,
            hash_sha1 VARCHAR(40) NOT NULL,
            folder_path VARCHAR(32) NOT NULL,
            file_name VARCHAR(255) NOT NULL,
            file_type VARCHAR(100) NOT NULL,
            file_size VARCHAR(50) NOT NULL,
            status VARCHAR(20) NOT NULL DEFAULT 'public',
            time_create INT(11) UNSIGNED NOT NULL,
            time_update INT(11) UNSIGNED NOT NULL)";
        if (maybe_create_table($table_name, $sql))
            ZDMCore::log('database table created', 'zdm_files');

        // Tabelle zdm_files_rel
        $table_name = $wpdb->prefix . 'zdm_files_rel';
        $sql = "CREATE TABLE " . $table_name . "(
            id INT(11) UNSIGNED NOT NULL PRIMARY KEY AUTO_INCREMENT,
            id_file INT(11) UNSIGNED NOT NULL,
            id_archive INT(11) UNSIGNED NOT NULL,
            file_updated TINYINT(1) NOT NULL DEFAULT 0,
            file_deleted TINYINT(1) NOT NULL DEFAULT 0,
            time_create INT(11) UNSIGNED NOT NULL,
            time_update INT(11) UNSIGNED NOT NULL)";
        if (maybe_create_table($table_name, $sql))
            ZDMCore::log('database table created', 'zdm_files_rel');
    }
}
