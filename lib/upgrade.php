<?php

// Abbruch bei direktem Zugriff
if( !defined( 'ABSPATH' ) ) {
    die;
}

$zdm_options = get_option('zdm_options');

if ($zdm_options['download-btn-text'] == '') {
    $zdm_options['download-btn-text'] = 'Download';
    update_option('zdm_options', $zdm_options);
}

if ($zdm_options['version'] < ZDM__VERSION) {

    $zdm_options['version'] = ZDM__VERSION;

    // Neue Optionen in v0.2.0
    if ($zdm_options['version'] <= '0.2.0') {

        if (!$zdm_options['download-btn-style']) {
            $zdm_options['download-btn-style'] = 'black';
        }

        if (!$zdm_options['download-btn-border-radius']) {
            $zdm_options['download-btn-border-radius'] = 'none';
        }
    }

    update_option('zdm_options', $zdm_options);
    //////////////////////////////

    // Fix ab v0.3.0
    if ($zdm_options['version'] <= '0.3.0') {

        global $wpdb;

        $zdm_tablename_files = $wpdb->prefix . "zdm_files";

        // Daten aus DB holen
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
    //////////////////////////////
}