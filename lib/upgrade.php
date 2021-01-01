<?php

// Abort by direct access
if (!defined('ABSPATH')) {
    die;
}

// temp
// v1.5.2
if (ZDM__VERSION == '1.5.2') {

    $zdm_dir =  wp_upload_dir()['basedir'];
    $dir_array = scandir($zdm_dir);
    $zdm_regex_search = '/z-downloads-.{32}/';
    $dir_count = count($dir_array);
    for ($i=0; $i < $dir_count; $i++) { 
        
        if (preg_match($zdm_regex_search, $dir_array[$i])) {
            rename(wp_upload_dir()['basedir'] . "/" . $dir_array[$i], wp_upload_dir()['basedir'] . "/z-downloads-" . $zdm_options['download-folder-token']);
        }
    }
}

$zdm_options = get_option('zdm_options');

if ($zdm_options['version'] < ZDM__VERSION) {

    // Log
    ZDMCore::log('plugin upgrade', ZDM__VERSION);

    // v0.3.0 MIME type fix
    if ($zdm_options['version'] < '0.3.0') {

        global $wpdb;

        $zdm_tablename_files = $wpdb->prefix . "zdm_files";

        // Get data from db
        $zdm_db_files = $wpdb->get_results( 
            "
            SELECT id, folder_path, file_name, file_type 
            FROM $zdm_tablename_files
            "
        );
        
        for ($i=0; $i < count($zdm_db_files); $i++) {

            $zdm_folder_path = ZDM__DOWNLOADS_FILES_PATH . '/' . $zdm_db_files[$i]->folder_path . '/';
            $zdm_file_name = $zdm_db_files[$i]->file_name;

            if (is_dir($zdm_folder_path)) {

                if (file_exists($zdm_folder_path . $zdm_file_name)) {

                    $zdm_file_mime_content_type = mime_content_type($zdm_folder_path . $zdm_file_name);
                    
                    $wpdb->update(
                        $zdm_tablename_files, 
                        array(
                            'file_type' => $zdm_file_mime_content_type
                        ), 
                        array(
                            'id' => $zdm_db_files[$i]->id)
                        );
                }
            }
        }
    }

    // v1.4.0
    if ($zdm_options['version'] < '1.4.0') {

        global $wpdb;
        $table_name = $wpdb->prefix . 'zdm_files';
        $wpdb->query( "ALTER TABLE $table_name ADD `status` VARCHAR(20) NOT NULL DEFAULT 'public' AFTER `file_size`" );
        $table_name = $wpdb->prefix . 'zdm_archives';
        $wpdb->query( "ALTER TABLE $table_name ADD `status` VARCHAR(20) NOT NULL DEFAULT 'public' AFTER `file_size`" );
    }

    // v1.5.0
    if ($zdm_options['version'] < '1.5.0') {
        $zdm_options['activation-time'] = time();
        $zdm_options['download-folder-token'] = md5(uniqid(rand(), true));
        rename(wp_upload_dir()['basedir'] . "/z-downloads", wp_upload_dir()['basedir'] . "/z-downloads-" . $zdm_options['download-folder-token']);
    }

    // Options
    if ($zdm_options['download-btn-text'] == '') {
        $zdm_options['download-btn-text'] = 'Download';
    }

    if (!$zdm_options['download-btn-style']) {
        $zdm_options['download-btn-style'] = 'black';
    }

    if (!$zdm_options['download-btn-border-radius']) {
        $zdm_options['download-btn-border-radius'] = 'none';
    }
    
    if (!$zdm_options['download-btn-outline']) {
        $zdm_options['download-btn-outline'] = '';
    }

    if (!$zdm_options['download-btn-icon']) {
        $zdm_options['download-btn-icon'] = 'none';
    }

    if (!$zdm_options['download-btn-icon-only']) {
        $zdm_options['download-btn-icon-only'] = '';
    }

    if ($zdm_options['file-open-in-browser-pdf'] == '') {
        $zdm_options['file-open-in-browser-pdf'] = '';
    }

    if ($zdm_options['stat-single-file-last-limit'] == '') {
        $zdm_options['stat-single-file-last-limit'] = 5;
    }

    if ($zdm_options['stat-single-archive-last-limit'] == '') {
        $zdm_options['stat-single-archive-last-limit'] = 5;
    }

    $zdm_options['version'] = ZDM__VERSION;
    
    update_option('zdm_options', $zdm_options);

    //////////////////////////////
}