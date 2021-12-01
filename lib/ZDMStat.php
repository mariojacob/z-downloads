<?php
// Abort by direct access
if (!defined('ABSPATH'))
    die;

/**
 * Statistik Klasse
 */
class ZDMStat
{

    /**
     * Gibt das Array mit den meisten Downloads zurück
     *
     * @param string $type [Optional] Art der Abfrage 'archive' oder 'file'
     * @param integer $limit [Optional] Anzahl der Einträge
     * @return array
     */
    public static function get_best_downloads($type = 'archive', $limit = 5)
    {
        global $wpdb;

        if ($type == 'archive') {
            $tablename_archives = $wpdb->prefix . "zdm_archives";

            $db = $wpdb->get_results(
                "
                SELECT id, name, count 
                FROM $tablename_archives 
                WHERE count > 0 
                ORDER by count DESC 
                Limit $limit
                "
            );
        }

        if ($type == 'file') {
            $tablename_files = $wpdb->prefix . "zdm_files";

            $db = $wpdb->get_results(
                "
                SELECT id, name, count, file_type 
                FROM $tablename_files 
                WHERE count > 0 
                ORDER by count DESC 
                Limit $limit
                "
            );
        }

        return $db;
    }

    /**
     * Gibt die Anzahl der Downloads zurück
     *
     * @param string $type [Optional] 'all' (standard), 'archive' oder 'file'
     * @return int
     */
    public static function get_downloads_count($type = 'all')
    {

        global $wpdb;
        $tablename_archives = $wpdb->prefix . "zdm_archives";
        $tablename_files = $wpdb->prefix . "zdm_files";

        // Alle Downloads
        if ($type == 'all') {

            // Archive
            $db_archives = $wpdb->get_results(
                "
                SELECT count 
                FROM $tablename_archives
                "
            );

            $db_archives_count = count($db_archives);

            $downloads_archives = 0;
            for ($i = 0; $i < $db_archives_count; $i++) {
                $downloads_archives = $downloads_archives + $db_archives[$i]->count;
            }

            // Dateien
            $db_files = $wpdb->get_results(
                "
                SELECT count 
                FROM $tablename_files
                "
            );

            $db_files_count = count($db_files);

            $downloads_files = 0;
            for ($i = 0; $i < $db_files_count; $i++) {
                $downloads_files = $downloads_files + $db_files[$i]->count;
            }

            return $downloads_archives + $downloads_files;
        }

        // Archive
        if ($type == 'archive') {
            $db_archives = $wpdb->get_results(
                "
                SELECT count 
                FROM $tablename_archives
                "
            );

            $db_archives_count = count($db_archives);

            $downloads_archives = 0;
            for ($i = 0; $i < $db_archives_count; $i++) {
                $downloads_archives = $downloads_archives + $db_archives[$i]->count;
            }

            return $downloads_archives;
        }

        // Dateien
        if ($type == 'file') {
            $db_files = $wpdb->get_results(
                "
                SELECT count 
                FROM $tablename_files
                "
            );

            $db_files_count = count($db_files);

            $downloads_files = 0;
            for ($i = 0; $i < $db_files_count; $i++) {
                $downloads_files = $downloads_files + $db_files[$i]->count;
            }

            return $downloads_files;
        }
    }

    /**
     * Gibt die Anzahl der Downloads für einen bestimmten Zeitraum zurück
     *
     * @param string $type [Optional] 'all' (standard), 'archive' oder 'file'
     * @param int $period Zeit in Sekunden
     * @return int
     */
    public static function get_downloads_count_time($type, $period)
    {

        global $wpdb;

        // Alle Downloads
        if ($type == 'all') {

            $tablename_log = $wpdb->prefix . "zdm_log";

            // Archive
            $type_archives = 'download archive';

            $time = time() - $period;

            $db_log_archives = $wpdb->get_results(
                "
                SELECT id 
                FROM $tablename_log 
                WHERE time_create > '$time' 
                AND type = '$type_archives' 
                ORDER by time_create DESC
                "
            );

            $downloads_archives = count($db_log_archives);

            // Dateien
            $type_files = 'download file';

            $time = time() - $period;

            $db_log_files = $wpdb->get_results(
                "
                SELECT id 
                FROM $tablename_log 
                WHERE time_create > '$time' 
                AND type = '$type_files' 
                ORDER by time_create DESC
                "
            );

            $downloads_files = count($db_log_files);

            return $downloads_archives + $downloads_files;
        }

        // Archive
        if ($type == 'archive') {

            $tablename_log = $wpdb->prefix . "zdm_log";

            $type_archives = 'download ' . $type;

            $time = time() - $period;

            $db_log_archives = $wpdb->get_results(
                "
                SELECT id 
                FROM $tablename_log 
                WHERE time_create > '$time' 
                AND type = '$type_archives' 
                ORDER by time_create DESC
                "
            );

            $downloads_archives = count($db_log_archives);

            return $downloads_archives;
        }

        // Dateien
        if ($type == 'file') {

            $tablename_log = $wpdb->prefix . "zdm_log";

            $type_files = 'download ' . $type;

            $time = time() - $period;

            $db_log_files = $wpdb->get_results(
                "
                SELECT id 
                FROM $tablename_log 
                WHERE time_create > '$time' 
                AND type = '$type_files' 
                ORDER by time_create DESC
                "
            );

            $downloads_files = count($db_log_files);

            return $downloads_files;
        }
    }

    /**
     * Gibt die Anzahl der Downloads für eine einzelne Datei oder ein einzelnes Archiv zurück
     *
     * @param string $type
     * @param int $item_id
     * @param int $period
     * @return int
     */
    public static function get_downloads_count_time_for_single_stat($type, $item_id, $period)
    {

        global $wpdb;
        $tablename = $wpdb->prefix . "zdm_log";
        $type_log = 'download ' . $type;

        $time = time() - $period;

        $db_log = $wpdb->get_results(
            "
            SELECT id 
            FROM $tablename 
            WHERE time_create > '$time' 
            AND type = '$type_log' 
            AND message = '$item_id' 
            ORDER by time_create DESC
            "
        );

        $downloads = count($db_log);

        return $downloads;
    }

    /**
     * Gibt die letzten Downloads als Array zurück
     *
     * @param string $type [Optional]
     * @param integer $limit [Optional] Anzahl der Einträge
     * @return array
     */
    public static function get_last_downloads($type = 'archive', $limit = 5)
    {
        $type_log = 'download ' . $type;

        global $wpdb;

        $tablename_log = $wpdb->prefix . "zdm_log";

        $db_log = $wpdb->get_results(
            "
            SELECT id, message, time_create 
            FROM $tablename_log 
            WHERE type = '$type_log' 
            ORDER by id DESC 
            Limit $limit
            "
        );

        return $db_log;
    }

    /**
     * Gibt die letzten Downloads (id, time_create) als Array zurück
     *
     * @param string $type
     * @param integer $item_id [Optional]
     * @param integer $limit [Optional] Anzahl der Einträge
     * @return array
     */
    public static function get_last_downloads_for_single_stat($type, $item_id, $limit = 5)
    {
        $type_log = 'download ' . $type;

        if ($limit == '') {
            $limit = 5;
        }

        global $wpdb;

        $tablename_log = $wpdb->prefix . "zdm_log";

        $db_log = $wpdb->get_results(
            "
            SELECT id, time_create 
            FROM $tablename_log 
            WHERE type = '$type_log' 
            AND message = '$item_id' 
            ORDER by id DESC 
            Limit $limit
            "
        );

        return $db_log;
    }
}
