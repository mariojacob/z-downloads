<?php
// Abort by direct access
if (!defined('ABSPATH'))
    die;

/**
 * Datenbank Klasse
 */
class ZDMDatabase
{
    public function delete_field()
    {
        global $wpdb;
        $table_name = $wpdb->prefix . 'zdm_log';
        if ($wpdb->get_var('SHOW TABLES LIKE ' . $table_name) == $table_name) {
            $sql = "ALTER TABLE " . $table_name . " DROP `user_id`";
            dbDelta($sql);
        }
    }

    /**
     * Erstellt die Datenbankstruktur
     *
     * @return void
     */
    public function create_db()
    {
        global $wpdb;
        $table_name = $wpdb->prefix . 'zdm_archives';

        if ($wpdb->get_var('SHOW TABLES LIKE ' . $table_name) != $table_name) {
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

            require_once(ABSPATH . 'wp-admin/includes/upgrade.php');

            dbDelta($sql);
        }

        $table_name = $wpdb->prefix . 'zdm_files';

        if ($wpdb->get_var('SHOW TABLES LIKE ' . $table_name) != $table_name) {
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

            require_once(ABSPATH . 'wp-admin/includes/upgrade.php');

            dbDelta($sql);
        }

        $table_name = $wpdb->prefix . 'zdm_files_rel';

        if ($wpdb->get_var('SHOW TABLES LIKE ' . $table_name) != $table_name) {
            $sql = "CREATE TABLE " . $table_name . "(
                id INT(11) UNSIGNED NOT NULL PRIMARY KEY AUTO_INCREMENT,
                id_file INT(11) UNSIGNED NOT NULL,
                id_archive INT(11) UNSIGNED NOT NULL,
                file_updated TINYINT(1) NOT NULL DEFAULT 0,
                file_deleted TINYINT(1) NOT NULL DEFAULT 0,
                time_create INT(11) UNSIGNED NOT NULL,
                time_update INT(11) UNSIGNED NOT NULL)";

            require_once(ABSPATH . 'wp-admin/includes/upgrade.php');

            dbDelta($sql);
        }

        $table_name = $wpdb->prefix . 'zdm_log';

        if ($wpdb->get_var('SHOW TABLES LIKE ' . $table_name) != $table_name) {
            $sql = "CREATE TABLE " . $table_name . "(
                id INT(11) UNSIGNED NOT NULL PRIMARY KEY AUTO_INCREMENT,
                type VARCHAR(64) NOT NULL,
                message TEXT NOT NULL,
                user_agent VARCHAR(255) NOT NULL,
                user_ip VARCHAR(255) NOT NULL,
                time_create INT(11) UNSIGNED NOT NULL)";

            require_once(ABSPATH . 'wp-admin/includes/upgrade.php');

            dbDelta($sql);
        }

        ZDMCore::log('database created');
    }
}
