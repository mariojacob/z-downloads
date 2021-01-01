<?php

// Abort by direct access
if (!defined('ABSPATH')) {
    die;
}

/**
 * Statistics class
 */
class ZDMStat {

    /**
     * Returns the array with the most downloads
     *
     * @param string $type [Optional] Type of query 'archive' or 'file'
     * @param integer $limit [Optional] Number of entries
     * @return array
     */
    public function get_best_downloads($type = 'archive', $limit = 5) {
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
     * Returns the number of downloads
     *
     * @param string $type [Optional] 'all' (standard), 'archive' or 'file'
     * @return int
     */
    public function get_downloads_count($type = 'all') {

        global $wpdb;
        $tablename_archives = $wpdb->prefix . "zdm_archives";
        $tablename_files = $wpdb->prefix . "zdm_files";

        // All downloads
        if ($type == 'all') {

            // Archives
            $db_archives = $wpdb->get_results(
                "
                SELECT count 
                FROM $tablename_archives
                "
                );
    
            for ($i=0; $i < count($db_archives); $i++) { 
                $downloads_archives = $downloads_archives + $db_archives[$i]->count;
            }
    
            // Files
            $db_files = $wpdb->get_results(
                "
                SELECT count 
                FROM $tablename_files
                "
                );
    
            for ($i=0; $i < count($db_files); $i++) { 
                $downloads_files = $downloads_files + $db_files[$i]->count;
            }

            return $downloads_archives + $downloads_files;
        }

        // Archives
        if ($type == 'archive') {
            $db_archives = $wpdb->get_results(
                "
                SELECT count 
                FROM $tablename_archives
                "
                );
    
            for ($i=0; $i < count($db_archives); $i++) { 
                $downloads_archives = $downloads_archives + $db_archives[$i]->count;
            }
            
            return $downloads_archives;
        }

        // Files
        if ($type == 'file') {
            $db_files = $wpdb->get_results(
                "
                SELECT count 
                FROM $tablename_files
                "
                );
    
            for ($i=0; $i < count($db_files); $i++) { 
                $downloads_files = $downloads_files + $db_files[$i]->count;
            }

            return $downloads_files;
        }
        
    }

    /**
     * Returns the number of downloads for a specific period of time
     *
     * @param string $type [Optional] 'all' (standard), 'archive' or 'file'
     * @param int $period Time in seconds
     * @return int
     */
    public function get_downloads_count_time($type = 'all', $period) {

        global $wpdb;

        // All downloads
        if ($type == 'all') {

            $tablename_log = $wpdb->prefix . "zdm_log";

            // Archives
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

            // Files
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

        // Archives
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

        // Files
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
     * Returns the number of downloads for a single file or archive
     *
     * @param string $type
     * @param int $item_id
     * @param int $period
     * @return int
     */
    public function get_downloads_count_time_for_single_stat($type, $item_id, $period) {

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
     * Returns the last downloads as an array
     *
     * @param string $type [Optional]
     * @param integer $limit [Optional] Number of entries
     * @return array
     */
    public function get_last_downloads($type = 'archive', $limit = 5) {
        $type_log = 'download ' . $type;

        global $wpdb;

        $tablename_log = $wpdb->prefix . "zdm_log";

        $db_log = $wpdb->get_results(
            "
            SELECT message, time_create 
            FROM $tablename_log 
            WHERE type = '$type_log' 
            ORDER by id DESC 
            Limit $limit
            "
            );

        return $db_log;
    }

    /**
     * Returns the last downloads (id, time_create) as an array
     *
     * @param string $type
     * @param integer $item_id [Optional]
     * @param integer $limit [Optional] Number of entries
     * @return array
     */
    public function get_last_downloads_for_single_stat($type, $item_id, $limit = 5) {
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