<?php

// Abbruch bei direktem Zugriff
if( !defined( 'ABSPATH' ) ) {
    die;
}

/**
 * ZDM-Statistics
 */
class ZDMStat
{

    /**
     * Gibt Anzahl der Downloads zurück
     * @return int
     */
    public function get_downloads_count($type = 'all')
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
    
            for ($i=0; $i < count($db_archives); $i++) { 
                $downloads_archives = $downloads_archives + $db_archives[$i]->count;
            }
    
            // Dateien
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

        // Archive
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

        // Dateien
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

    public function get_downloads_count_time($type = 'all', $period)
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

    public function get_best_downloads($type = 'archive', $number = 5)
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
                Limit $number
                "
                );
        }

        if ($type == 'file') {
            $tablename_files = $wpdb->prefix . "zdm_files";

            $db = $wpdb->get_results(
                "
                SELECT id, name, count 
                FROM $tablename_files 
                WHERE count > 0 
                ORDER by count DESC 
                Limit $number
                "
                );
        }
        

        return $db;
    }

    public function get_last_downloads($type = 'archive', $number = 5)
    {
        $type = 'download ' . $type;

        global $wpdb;
        $tablename_log = $wpdb->prefix . "zdm_log";

        $db_log = $wpdb->get_results(
            "
            SELECT message, time_create 
            FROM $tablename_log 
            WHERE type = '$type' 
            ORDER by id DESC 
            Limit $number
            "
            );

        return $db_log;
    }
}