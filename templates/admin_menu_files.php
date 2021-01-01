<?php

// Abort by direct access
if (!defined('ABSPATH')) {
    die;
}

if (current_user_can(ZDM__STANDARD_USER_ROLE)) {

    $zdm_status = '';
    $zdm_note = '';
    $zdm_licence = 0;
    $zdm_options = get_option('zdm_options');
    $zdm_time = time();

    // Determine the active tab
    if( isset($_GET['tab'])) {
        $zdm_active_tab = htmlspecialchars($_GET['tab']);
    } else {
        $zdm_active_tab = 'file';
    }

    if (ZDMCore::licence()) {
        $zdm_licence_array = ZDMCore::licence_array();
        $zdm_licence = 1;
    }

    global $wpdb;
    $zdm_tablename_files = $wpdb->prefix . "zdm_files";
    $zdm_tablename_files_rel = $wpdb->prefix . "zdm_files_rel";

    //////////////////////////////////////////////////
    // Add file
    //////////////////////////////////////////////////
    if (isset($_FILES['file']) && wp_verify_nonce($_POST['nonce'], 'datei-hochladen') && $_FILES['file']['name'] != '') {

        if ($zdm_options['duplicate-file'] != 'on') {
            // Generate MD5 hash of uploaded file
            $zdm_uploaded_file_hash = md5_file($_FILES['file']['tmp_name']);
        }

        // Check file for duplicates
        if ($zdm_options['duplicate-file'] != 'on' && in_array($zdm_uploaded_file_hash, ZDMCore::get_files_md5())) {

            // File has already been uploaded

            // Show upload duplicate page
            $zdm_status = 3;
        } else {

            // File has not yet been uploaded
        
            $zdm_file = array();
            $zdm_file['name'] = sanitize_file_name($_FILES['file']['name']);
            $zdm_file['type'] = $_FILES['file']['type'];
            $zdm_file['size'] = ZDMCore::file_size_convert(sanitize_file_name($_FILES['file']['size']));

            // Create folder name
            $zdm_file['folder'] = md5(time() . $zdm_file['name']);

            // Create folder
            if (!is_dir($zdm_file['folder'])) {
                wp_mkdir_p(ZDM__DOWNLOADS_FILES_PATH . '/' . $zdm_file['folder']);
            }

            $zdm_file_path = ZDM__DOWNLOADS_FILES_PATH . '/' . $zdm_file['folder'] . '/' . $zdm_file['name'];

            // Save file
            move_uploaded_file($_FILES['file']['tmp_name'], $zdm_file_path);

            // Delete temporary file
            unlink($_FILES['file']['tmp_name']);

            // Create index.php
            $index_file_handle = fopen(ZDM__DOWNLOADS_FILES_PATH . '/' . $zdm_file['folder'] . '/' . 'index.php', 'w');
            fclose($index_file_handle);

            // MD5 from file
            $zdm_file['md5'] = md5_file($zdm_file_path);

            // SHA1 from file
            $zdm_file['sha1'] = sha1_file($zdm_file_path);

            // Save file path in database
            $wpdb->insert(
                $zdm_tablename_files, 
                array(
                    'name'          => $zdm_file['name'],
                    'hash_md5'      => $zdm_file['md5'],
                    'hash_sha1'     => $zdm_file['sha1'],
                    'folder_path'   => $zdm_file['folder'],
                    'file_name'     => $zdm_file['name'],
                    'file_type'     => $zdm_file['type'],
                    'file_size'     => $zdm_file['size'],
                    'time_create'   => $zdm_time
                )
            );

            // Log
            ZDMCore::log('add file', 'name: ' . htmlspecialchars($zdm_file['name']) . ', path: ' . $zdm_file_path);

            $zdm_folder_path = $zdm_file['folder'];

            // Get ID from database
            $zdm_db_file = $wpdb->get_results( 
                "
                SELECT id 
                FROM $zdm_tablename_files 
                WHERE folder_path = '$zdm_folder_path'
                "
            );

            // Set file ID
            $zdm_file_id = $zdm_db_file[0]->id;

            // Show file details page
            $zdm_status = 1;
        }
    } elseif (isset($_GET['id']) OR isset($_POST['update']) OR isset($_POST['delete'])) {

        // Show file details page
        $zdm_status = 1;

        if (isset($_GET['id'])) {
            // Specify file ID if ID is in URL parameter
            $zdm_file_id = sanitize_text_field($_GET['id']);
        } else {
            // Set file ID when file has been uploaded
            $zdm_file_id = sanitize_text_field($_POST['file_id']);
        }

        //////////////////////////////////////////////////
        // Update data
        //////////////////////////////////////////////////
        if (isset($_POST['update']) && wp_verify_nonce($_POST['nonce'], 'update-data')) {

            // Get data from database
            $zdm_db_file = $wpdb->get_results( 
                "
                SELECT id 
                FROM $zdm_tablename_files 
                WHERE id = '$zdm_file_id'
                "
            );

            // Check whether the file exists
            if (count($zdm_db_file) > 0) {

                if ($_POST['name'] != '') {
                    $zdm_name = sanitize_text_field($_POST['name']);
                } else {
                    $zdm_name = sanitize_text_field($_POST['filename']);
                }
    
                if ($_POST['button-text']) {
                    if ($_POST['button-text'] != $zdm_options['download-btn-text']) {
                        $zdm_button_text = sanitize_text_field($_POST['button-text']);
                    } else {
                        $zdm_button_text = '';
                    }
                } else {
                    $zdm_button_text = '';
                }
    
                $wpdb->update(
                    $zdm_tablename_files, 
                    array(
                        'name'          => $zdm_name,
                        'description'   => sanitize_textarea_field($_POST['description']),
                        'count'         => sanitize_text_field($_POST['count']),
                        'button_text'   => $zdm_button_text,
                        'status'        => sanitize_text_field($_POST['status']),
                        'time_update'   => $zdm_time
                    ), 
                    array(
                        'id' => $zdm_file_id
                    ));
    
                // Log
                ZDMCore::log('update file', $zdm_file_id);
            
                // Output success message
                $zdm_note = esc_html__('Updated', 'zdm');
            } else {
                // Show file list
                $zdm_status = 2;
            }
        }

        //////////////////////////////////////////////////
        // Delete file
        //////////////////////////////////////////////////
        if (($_POST['delete'] && wp_verify_nonce($_POST['nonce'], 'update-data')) OR ($_GET['delete'] && wp_verify_nonce($_GET['nonce'], 'delete-file'))) {

            // Get data from database
            $zdm_db_file = $wpdb->get_results( 
                "
                SELECT * 
                FROM $zdm_tablename_files 
                WHERE id = '$zdm_file_id'
                "
            );

            if (count($zdm_db_file) > 0) {

                $zdm_db_file = $zdm_db_file[0];
            
                // Datei und Ordner löschen
                $zdm_folder_path = ZDM__DOWNLOADS_FILES_PATH . '/' . $zdm_db_file->folder_path;
                $zdm_file_path = $zdm_folder_path . '/' . $zdm_db_file->file_name;
                $zdm_file_index = $zdm_folder_path . '/' . 'index.php';
                if (file_exists($zdm_file_path)) {
                    unlink($zdm_file_path);
                }
                if (file_exists($zdm_file_index)) {
                    unlink($zdm_file_index);
                }
                if (is_dir($zdm_folder_path)) {
                    rmdir($zdm_folder_path);
                }
            
                // DB Eintrag löschen
                $wpdb->delete(
                    $zdm_tablename_files, 
                    array(
                        'id' => $zdm_file_id
                    ));
    
                $wpdb->update(
                    $zdm_tablename_files_rel, 
                    array(
                        'file_updated'  => 1,
                        'file_deleted'  => 1,
                        'time_update'   => $zdm_time
                    ), 
                    array(
                        'id_file' => $zdm_file_id
                    ));
    
                // Log
                ZDMCore::log('delete file', $zdm_file_id);
            
                // Erfolg-Meldung ausgeben
                $zdm_note = esc_html__('File deleted!', 'zdm');
    
                // Dateiliste anzeigen
                $zdm_status = 2;
    
                // Check ob der Aufruf von Upload-Duplikat-Seite kommt
                if (isset($_GET['duplicate-hash'])) {

                    // Datei-Hash
                    $zdm_uploaded_file_hash = htmlspecialchars($_GET['duplicate-hash']);

                    // Get data from database
                    $zdm_db_file = $wpdb->get_results( 
                        "
                        SELECT * 
                        FROM $zdm_tablename_files 
                        WHERE hash_md5 = '$zdm_uploaded_file_hash'
                        "
                    );

                    // Checken ob mehr als eine Datei mit diesem Hash existiert
                    if (count($zdm_db_file) > 0) {
                        // Upload-Duplikat-Seite anzeigen
                        $zdm_status = 3;
                    } else {
                        // Dateiliste anzeigen
                        $zdm_status = 2;
                    }
                } else {
                    // Seite neu laden
                    $zdm_files_url = 'admin.php?page=' . ZDM__SLUG . '-files';
                    wp_redirect($zdm_files_url);
                    exit;
                }
            } else {
                // Dateiliste anzeigen
                $zdm_status = 2;
            }
        }

        //////////////////////////////////////////////////
        // Datei ersetzen
        //////////////////////////////////////////////////
        if (isset($_FILES['file']) && wp_verify_nonce($_POST['nonce'], 'replace-file') && $_FILES['file']['name'] != '') {

            // Get data from database
            $zdm_db_file = $wpdb->get_results( 
                "
                SELECT * 
                FROM $zdm_tablename_files 
                WHERE id = $zdm_file_id
                "
            );
            $zdm_db_file = $zdm_db_file[0];

            $zdm_file = array();
            $zdm_file['name'] = $_FILES['file']['name'];
            $zdm_file['type'] = $_FILES['file']['type'];
            $zdm_file['size'] = ZDMCore::file_size_convert($_FILES['file']['size']);

            if ($_POST['name'] != '') {
                $zdm_name = sanitize_text_field($_POST['name']);
            } else {
                $zdm_name = $zdm_file['name'];
            }

            // Alte Datei löschen
            unlink(ZDM__DOWNLOADS_FILES_PATH . '/' . $zdm_db_file->folder_path . '/' . $zdm_db_file->file_name);

            // Neue Datei abspeichern
            move_uploaded_file($_FILES['file']['tmp_name'], ZDM__DOWNLOADS_FILES_PATH . '/' . $zdm_db_file->folder_path . '/' . $zdm_file['name']);

            // MD5 aus Datei
            $zdm_file['md5'] = md5_file(ZDM__DOWNLOADS_FILES_PATH . '/' . $zdm_db_file->folder_path . '/' . $zdm_file['name']);

            // SHA1 aus Datei
            $zdm_file['sha1'] = sha1_file(ZDM__DOWNLOADS_FILES_PATH . '/' . $zdm_db_file->folder_path . '/' . $zdm_file['name']);

            // Dateipfad in DB aktualisieren
            $wpdb->update(
                $zdm_tablename_files, 
                array(
                    'name'          => $zdm_name,
                    'hash_md5'      => $zdm_file['md5'],
                    'hash_sha1'     => $zdm_file['sha1'],
                    'file_name'     => $zdm_file['name'],
                    'file_type'     => $zdm_file['type'],
                    'file_size'     => $zdm_file['size'],
                    'time_update'   => $zdm_time
                ), 
                array(
                    'id' => $zdm_file_id
                ));

                $wpdb->update(
                    $zdm_tablename_files_rel, 
                    array(
                        'file_updated'  => 1,
                        'time_update'   => $zdm_time
                    ), 
                    array(
                        'id_file' => $zdm_file_id
                    ));

            // Log
            ZDMCore::log('replace file', $zdm_file_id);

            $zdm_active_tab = 'file';

            // Erfolg-Meldung ausgeben
            $zdm_note = esc_html__('File has been replaced!', 'zdm');
        }

        //////////////////////////////////////////////////
        // Datei aus Archiv entfernen
        //////////////////////////////////////////////////
        if (isset($_GET['file_unlink_from_archive']) && isset($_GET['archive_id']) && wp_verify_nonce($_GET['nonce'], 'file_unlink_from_archive')) {

            $zdm_archive_id = sanitize_text_field($_GET['archive_id']);

            // id, id_file, id_archive aus DB files_rel holen
            $zdm_db_files_rel_array = $wpdb->get_results(
                "
                SELECT id 
                FROM $zdm_tablename_files_rel 
                WHERE id_archive = '$zdm_archive_id' 
                AND id_file = '$zdm_file_id' 
                AND file_deleted = 0
                "
                );

            $wpdb->delete(
                $zdm_tablename_files_rel, 
                array(
                    'id' => $zdm_db_files_rel_array[0]->id
                ));
        
            // files_rel update
            $wpdb->update(
                $zdm_tablename_files_rel, 
                array(
                    'file_updated' => 1
                ), 
                array(
                    'id_archive' => $zdm_archive_id
                ));

            // Log
            ZDMCore::log('unlink file', $zdm_file_id);
            
            // Erfolg-Meldung ausgeben
            $zdm_note = esc_html__('File removed from archive!', 'zdm');
        }

        //////////////////////////////////////////////////
        // Statistik Ausgabe aktualisieren
        //////////////////////////////////////////////////
        if (isset($_POST['update_stat_single_file_last_limit']) && wp_verify_nonce($_POST['nonce'], 'update-stat-single-file-last-limit')) {
    
            $zdm_options['stat-single-file-last-limit'] = trim(sanitize_text_field($_POST['stat-single-file-last-limit']));
            if (add_option('zdm_options', $zdm_options) === FALSE) {
                update_option('zdm_options', $zdm_options);
            }
            $zdm_options = get_option('zdm_options');
            $zdm_note = esc_html__('Updated settings!', 'zdm');
        }
    }

    if ($zdm_note != '') { ?>
        
        <div class="notice notice-success">
            <p><?=$zdm_note?></p>
        </div>

    <?php }

    if ($zdm_status === 1) { // Datei Detailseite

        // Get data from database (files)
        $zdm_db_file = $wpdb->get_results( 
            "
            SELECT * 
            FROM $zdm_tablename_files 
            WHERE id = $zdm_file_id
            "
        );
        $zdm_db_file = $zdm_db_file[0];

        // Download-Button Text
        if ($zdm_db_file->button_text != '') {
            $zdm_button_text = $zdm_db_file->button_text;
        } else {
            $zdm_button_text = $zdm_options['download-btn-text'];
        }

        ?>
        
        <div class="wrap">
            
            <h1><?=esc_html__('File', 'zdm')?>: <?=$zdm_db_file->name?></h1>
            <br>
            <a href="admin.php?page=<?=ZDM__SLUG?>-files" class="page-title-action"><?=esc_html__('Back to overview', 'zdm')?></a> <a href="admin.php?page=<?=ZDM__SLUG?>-add-file" class="page-title-action"><?=esc_html__('Add file', 'zdm')?></a>
            <br><br>

            <nav class="nav-tab-wrapper wp-clearfix zdm-nav-tabs">
                <a href="admin.php?page=<?=ZDM__SLUG?>-files&id=<?=$zdm_file_id?>" class="nav-tab zdm-nav-tab <?php echo $zdm_active_tab == 'file' ? 'nav-tab-active' : ''; ?>" aria-current="page"><ion-icon name="document"></ion-icon> <?=esc_html__('File', 'zdm')?></a>
                <a href="admin.php?page=<?=ZDM__SLUG?>-files&id=<?=$zdm_file_id?>&tab=shortcodes" class="nav-tab <?php echo $zdm_active_tab == 'shortcodes' ? 'nav-tab-active' : ''; ?>">[/] <?=esc_html__('Shortcodes', 'zdm')?></a>
                <a href="admin.php?page=<?=ZDM__SLUG?>-files&id=<?=$zdm_file_id?>&tab=update-file" class="nav-tab <?php echo $zdm_active_tab == 'update-file' ? 'nav-tab-active' : ''; ?>"><ion-icon name="swap"></ion-icon> <?=esc_html__('Replace file', 'zdm')?></a>
                <a href="admin.php?page=<?=ZDM__SLUG?>-files&id=<?=$zdm_file_id?>&tab=statistic" class="nav-tab <?php echo $zdm_active_tab == 'statistic' ? 'nav-tab-active' : ''; ?>"><ion-icon name="stats"></ion-icon> <?=esc_html__('Statistics', 'zdm')?></a>
                <a href="admin.php?page=<?=ZDM__SLUG?>-files&id=<?=$zdm_file_id?>&tab=help" class="nav-tab <?php echo $zdm_active_tab == 'help' ? 'nav-tab-active' : ''; ?>"><ion-icon name="help-circle-outline"></ion-icon> <?=esc_html__('Help', 'zdm')?></a>
            </nav>
            <br>

            <?php
            // Tabs
            // Tab: File
            if ($zdm_active_tab == 'file') {
                ?>
                
                <div class="postbox">
                    <div class="inside">

                        <h2><?=esc_html__('General information', 'zdm')?></h2>
                        <hr>
                        <table class="form-table">
                            <tbody>

                                <form action="admin.php?page=<?=ZDM__SLUG?>-files&id=<?=$zdm_file_id?>&tab=file" method="post">
                                    
                                    <tr valign="top">
                                        <th scope="row"><?=esc_html__('Visibility', 'zdm')?>:</th>
                                        <td valign="middle">
                                            <p><input type="radio" name="status" value="public" <?php if($zdm_db_file->status == 'public'){ echo 'checked="checked"'; } ?>> <?=esc_html__('Public', 'zdm')?></p>
                                            <p><input type="radio" name="status" value="private" <?php if($zdm_db_file->status == 'private'){ echo 'checked="checked"'; } ?>> <?=esc_html__('Private', 'zdm')?></p>
                                            <div class="zdm-help-text"><?=esc_html__('The visibility of this file only affects the output of this file if this file is linked in an archive and you set the visibility to "Private", then the file remains in the archive.', 'zdm')?></div>
                                        </td>
                                    </tr>
                                
                                    <?php

                                    if (in_array($zdm_db_file->file_type, ZDM__MIME_TYPES_AUDIO)) { // Audio
                                        ?>
                                        <tr valign="top">
                                            <th scope="row"><?=esc_html__('Preview', 'zdm')?>:</th>
                                            <td valign="middle">
                                                <audio controls preload="none">
                                                    <source src="<?=ZDM__DOWNLOADS_FILES_PATH_URL . '/' . htmlspecialchars($zdm_db_file->folder_path) . '/' . htmlspecialchars($zdm_db_file->file_name)?>" type="<?=htmlspecialchars($zdm_db_file->file_type)?>">
                                                </audio>
                                                <br>
                                                <?=esc_html__('Download', 'zdm')?>: <a href="<?=ZDM__DOWNLOADS_FILES_PATH_URL . '/' . htmlspecialchars($zdm_db_file->folder_path) . '/' . htmlspecialchars($zdm_db_file->file_name)?>" target="_blank" download><?=htmlspecialchars($zdm_db_file->file_name)?></a>
                                            </td>
                                        </tr>
                                        <?php
                                    } elseif (in_array($zdm_db_file->file_type, ZDM__MIME_TYPES_VIDEO)) { // Video
                                        ?>
                                        <tr valign="top">
                                            <th scope="row"><?=esc_html__('Preview', 'zdm')?>:</th>
                                            <td valign="middle">
                                                <video width="400px" controls>
                                                    <source src="<?=ZDM__DOWNLOADS_FILES_PATH_URL . '/' . htmlspecialchars($zdm_db_file->folder_path) . '/' . htmlspecialchars($zdm_db_file->file_name)?>" type="<?=htmlspecialchars($zdm_db_file->file_type)?>">
                                                </video>
                                                <br>
                                                <?=esc_html__('Download', 'zdm')?>: <a href="<?=ZDM__DOWNLOADS_FILES_PATH_URL . '/' . htmlspecialchars($zdm_db_file->folder_path) . '/' . htmlspecialchars($zdm_db_file->file_name)?>" target="_blank" download><?=htmlspecialchars($zdm_db_file->file_name)?></a>
                                            </td>
                                        </tr>
                                        <?php
                                    } elseif (in_array($zdm_db_file->file_type, ZDM__MIME_TYPES_IMAGE)) { // Image
                                        ?>
                                        <tr valign="top">
                                            <th scope="row"><?=esc_html__('Preview', 'zdm')?>:</th>
                                            <td valign="middle">
                                                <img src="<?=ZDM__DOWNLOADS_FILES_PATH_URL . '/' . htmlspecialchars($zdm_db_file->folder_path) . '/' . htmlspecialchars($zdm_db_file->file_name)?>" width="400px" height="auto">
                                                <br>
                                                <?=esc_html__('Download', 'zdm')?>: <a href="<?=ZDM__DOWNLOADS_FILES_PATH_URL . '/' . htmlspecialchars($zdm_db_file->folder_path) . '/' . htmlspecialchars($zdm_db_file->file_name)?>" target="_blank" download><?=htmlspecialchars($zdm_db_file->file_name)?></a>
                                            </td>
                                        </tr>
                                        <?php
                                    } else { // Other files
                                        ?>
                                        <tr valign="top">
                                            <th scope="row"><?=esc_html__('Download file', 'zdm')?>:</th>
                                            <td valign="middle">
                                                <a href="<?=ZDM__DOWNLOADS_FILES_PATH_URL . '/' . htmlspecialchars($zdm_db_file->folder_path) . '/' . htmlspecialchars($zdm_db_file->file_name)?>" target="_blank" download><?=htmlspecialchars($zdm_db_file->file_name)?></a>
                                            </td>
                                        </tr>
                                        <?php
                                    }
                                    ?>
                                    <tr valign="top">
                                        <th scope="row"><?=esc_html__('File', 'zdm')?>:</th>
                                        <td valign="middle">
                                            <input type="text" size="50%" value="<?=htmlspecialchars($zdm_db_file->file_name)?>" placeholder="" disabled>
                                        </td>
                                    </tr>
                                    <tr valign="top">
                                        <th scope="row"><?=esc_html__('Name', 'zdm')?>:</th>
                                        <td valign="middle">
                                            <input type="text" name="name" size="50%" value="<?=htmlspecialchars($zdm_db_file->name)?>" spellcheck="true" autocomplete="off" placeholder="">
                                            <div class="zdm-help-text"><?=esc_html__('This name is displayed in the file list and serves as an orientation.', 'zdm')?></div>
                                        </td>
                                    </tr>
                                    <tr valign="top">
                                        <th scope="row"><?=esc_html__('Download button text', 'zdm')?>:</th>
                                        <td valign="middle">
                                            <input type="text" name="button-text" size="50%" value="<?=esc_attr($zdm_button_text)?>" spellcheck="true" autocomplete="off" placeholder="">
                                            <br>
                                            <div class="zdm-help-text"><?=esc_html__('This text is only displayed for this download, for all others the default text is used and can be changed in the', 'zdm')?> <a href="admin.php?page=<?=ZDM__SLUG?>-settings#zdm-download-button"><?=esc_html__('settings', 'zdm')?></a>.</div>
                                            <div class="zdm-help-text"><?=esc_html__('The default text is', 'zdm')?>: <b>"<?=htmlspecialchars($zdm_options['download-btn-text'])?>"</b></div>
                                            <div class="zdm-help-text"><?=esc_html__('To reuse the default global text, just leave this field blank and the default text is automatically inserted when updating.', 'zdm')?></div>
                                        </td>
                                    </tr>
                                    <tr valign="top">
                                        <th scope="row"><?=esc_html__('Download number', 'zdm')?>:</th>
                                        <td valign="middle">
                                            <input type="number" name="count" size="10%" value="<?=esc_attr($zdm_db_file->count)?>" spellcheck="true" autocomplete="off" placeholder=""> 
                                            <div class="zdm-help-text"><?=esc_html__('Number of previous downloads.', 'zdm')?></div>
                                        </td>
                                    </tr>
                                    <tr valign="top">
                                        <th scope="row"><?=esc_html__('File size', 'zdm')?>:</th>
                                        <td valign="middle">
                                            <?php
                                            echo esc_attr($zdm_db_file->file_size);
                                            ?>
                                        </td>
                                    </tr>
                                    <tr valign="top">
                                        <th scope="row"><?=esc_html__('In archives', 'zdm')?>:</th>
                                        <td valign="middle">
                                            <?php
                                            $zdm_db_file_in_archive = ZDMCore::check_if_file_is_in_archive($zdm_db_file->id);
                                            if ($zdm_db_file_in_archive == 1) {
                                                echo esc_html__('This file is in', 'zdm') . ' ' . $zdm_db_file_in_archive . ' ' . esc_html__('archives linked', 'zdm') . ':';
                                            } elseif ($zdm_db_file_in_archive > 1) {
                                                echo esc_html__('This file is in', 'zdm') . ' ' . $zdm_db_file_in_archive . ' ' . esc_html__('archives linked', 'zdm') . ':';
                                            } else {
                                                echo esc_html__('This file is not linked to any archive.', 'zdm');
                                            }

                                            if ($zdm_db_file_in_archive >= 1) {

                                                ?>
                                                <br><br>
                                                <table class="zdm-table-list">
                                                    <?php
                                                    $zdm_linked_archives = ZDMCore::get_linked_archives($zdm_db_file->id);

                                                    for ($i=0; $i < count($zdm_linked_archives); $i++) {

                                                        $zdm_linked_archive_data = ZDMCore::get_archive_data($zdm_linked_archives[$i]->id_archive);
                                                        
                                                        ?>

                                                        <tr>
                                                            <td>
                                                                <a href="admin.php?page=<?=ZDM__SLUG?>-ziparchive&id=<?=htmlspecialchars($zdm_linked_archive_data->id)?>"><?=htmlspecialchars($zdm_linked_archive_data->name)?></a>
                                                            </td>
                                                            <td>
                                                                <a href="admin.php?page=<?=ZDM__SLUG?>-files&id=<?=htmlspecialchars($zdm_db_file->id)?>&file_unlink_from_archive=true&archive_id=<?=htmlspecialchars($zdm_linked_archive_data->id)?>&nonce=<?=wp_create_nonce('file_unlink_from_archive')?>" class="button button-small button-secondary zdm-btn-danger-2-outline" title="<?=esc_html__('Remove file from the following archive', 'zdm')?>: <?=htmlspecialchars($zdm_linked_archive_data->name)?>"><?=esc_html__('Remove file from this archive', 'zdm')?></a>
                                                            </td>
                                                        </tr>
                                                    <?php } ?>
                                                </table>
                                                <?php
                                            }
                                            ?>
                                        </td>
                                    </tr>
                                    <?php if ($zdm_licence === 1) { ?>
                                    <tr valign="top">
                                        <th scope="row"><?=esc_html__('Hash MD5', 'zdm')?>:</th>
                                        <td valign="middle">
                                            <input type="text" name="name" size="50%" value="<?=htmlspecialchars($zdm_db_file->hash_md5)?>" placeholder="" disabled>
                                        </td>
                                    </tr>
                                    <tr valign="top">
                                        <th scope="row"><?=esc_html__('Hash SHA1', 'zdm')?>:</th>
                                        <td valign="middle">
                                            <input type="text" name="name" size="50%" value="<?=htmlspecialchars($zdm_db_file->hash_sha1)?>" placeholder="" disabled>
                                        </td>
                                    </tr>
                                    <?php } else { ?>
                                    <tr><th colspan="2"><hr></th></tr>
                                    <tr valign="top">
                                        <th scope="row"></th>
                                        <td valign="middle">
                                            <p><?=esc_html__('More info only for', 'zdm')?>: <?=ZDM__PRO?><?=esc_html__('hash MD5, hash SHA1', 'zdm')?></p>
                                        </td>
                                    </tr>
                                    <?php } ?>
                            </tbody>
                        </table>
                    </div>
                </div>
                                    <input type="hidden" name="nonce" value="<?=wp_create_nonce('update-data')?>">
                                    <input type="hidden" name="file_id" value="<?=$zdm_file_id?>">
                                    <input class="button-primary" type="submit" name="update" value="<?=esc_html__('Update', 'zdm')?>">
                                    <input class="button-secondary" type="submit" name="delete" value="<?=esc_html__('Delete', 'zdm')?>">
                                </form>
                <?php
            // end if ($zdm_active_tab == 'file')
            // Tab: Shortcodes
            } elseif ($zdm_active_tab == 'shortcodes') {
                ?>
                <div class="postbox">
                    <div class="inside">
                    
                        <h2><?=esc_html__('Shortcodes', 'zdm')?></h2>
                        <hr>
                        <p><a href="https://code.urban-base.net/z-downloads/shortcodes?utm_source=zdm_backend" target="_blank" title="<?=ZDM__TITLE?> Shortcodes"><?=esc_html__('All shortcodes', 'zdm')?></a> <?=esc_html__('overview with explanation and examples.', 'zdm')?></p>
                        
                        <table class="form-table">
                            <tbody>
                                <tr valign="top">
                                    <th scope="row"><?=esc_html__('Download button', 'zdm')?></th>
                                    <td valign="middle">
                                        <input type="text" class="zdm-copy-to-clipboard zdm-copy-to-clipboard-detail" value="[zdownload file=&quot;<?=htmlspecialchars($zdm_db_file->id)?>&quot;]" readonly title="<?=esc_html__('Copy the shortcode to the clipboard.', 'zdm')?>">
                                        <p class="zdm-color-green" style="display: none;"><b><ion-icon name="checkmark"></ion-icon> <?=esc_html__('Shortcode copied', 'zdm')?></b></p>
                                    </td>
                                </tr>
                                <tr valign="top">
                                    <th scope="row"><?=esc_html__('Download count', 'zdm')?></th>
                                    <td valign="middle">
                                        <input type="text" class="zdm-copy-to-clipboard zdm-copy-to-clipboard-detail" value="[zdownload_meta file=&quot;<?=htmlspecialchars($zdm_db_file->id)?>&quot; type=&quot;count&quot;]" readonly title="<?=esc_html__('Copy the shortcode to the clipboard.', 'zdm')?>">
                                        <p class="zdm-color-green" style="display: none;"><b><ion-icon name="checkmark"></ion-icon> <?=esc_html__('Shortcode copied', 'zdm')?></b></p>
                                    </td>
                                </tr>
                                <tr valign="top">
                                    <th scope="row"><?=esc_html__('File size', 'zdm')?></th>
                                    <td valign="middle">
                                        <input type="text" class="zdm-copy-to-clipboard zdm-copy-to-clipboard-detail" value="[zdownload_meta file=&quot;<?=htmlspecialchars($zdm_db_file->id)?>&quot; type=&quot;size&quot;]" readonly title="<?=esc_html__('Copy the shortcode to the clipboard.', 'zdm')?>">
                                        <p class="zdm-color-green" style="display: none;"><b><ion-icon name="checkmark"></ion-icon> <?=esc_html__('Shortcode copied', 'zdm')?></b></p>
                                    </td>
                                </tr>
                                <?php
                                if (in_array($zdm_db_file->file_type, ZDM__MIME_TYPES_AUDIO)) { // Audio
                                    ?>
                                    <tr valign="top">
                                        <th scope="row"><?=esc_html__('Audio player', 'zdm')?></th>
                                        <td valign="middle">
                                            <input type="text" class="zdm-copy-to-clipboard zdm-copy-to-clipboard-detail" value="[zdownload_audio file=&quot;<?=htmlspecialchars($zdm_db_file->id)?>&quot;]" readonly title="<?=esc_html__('Copy the shortcode to the clipboard.', 'zdm')?>">
                                            <p class="zdm-color-green" style="display: none;"><b><ion-icon name="checkmark"></ion-icon> <?=esc_html__('Shortcode copied', 'zdm')?></b></p>
                                        </td>
                                    </tr>
                                    <?php
                                } elseif (in_array($zdm_db_file->file_type, ZDM__MIME_TYPES_VIDEO)) { // Video
                                    ?>
                                    <tr valign="top">
                                        <th scope="row"><?=esc_html__('Video player', 'zdm')?></th>
                                        <td valign="middle">
                                            <input type="text" class="zdm-copy-to-clipboard zdm-copy-to-clipboard-detail" value="[zdownload_video file=&quot;<?=htmlspecialchars($zdm_db_file->id)?>&quot;]" readonly title="<?=esc_html__('Copy the shortcode to the clipboard.', 'zdm')?>">
                                            <p class="zdm-color-green" style="display: none;"><b><ion-icon name="checkmark"></ion-icon> <?=esc_html__('Shortcode copied', 'zdm')?></b></p>
                                            <div class="zdm-help-text"><a href="https://code.urban-base.net/z-downloads/shortcodes/?utm_source=zdm_backend" target="_blank" title="<?=esc_html__('Shortcodes', 'zdm')?>"><?=esc_html__('All options', 'zdm')?></a> <?=esc_html__('for the video player', 'zdm')?></div>
                                        </td>
                                    </tr>
                                    <?php
                                }
                                
                                if ($zdm_licence === 0) {
                                    $text_hash_md5 = esc_html__('Output MD5 hash value', 'zdm') . '<br><a href="' . ZDM__PRO_URL . '" target="_blank" title="code.urban-base.net">' . ZDM__PRO . ' ' . esc_html__('feature', 'zdm') . ' </a>';
                                    $text_hash_sha1 = esc_html__('Output SHA1 hash value', 'zdm') . '<br><a href="' . ZDM__PRO_URL . '" target="_blank" title="code.urban-base.net">' . ZDM__PRO . ' ' . esc_html__('feature', 'zdm') . '</a>';
                                } else {
                                    $text_hash_md5 = esc_html__('Output MD5 hash value', 'zdm');
                                    $text_hash_sha1 = esc_html__('Output SHA1 hash value', 'zdm');
                                }
                                ?>
                                <tr valign="top">
                                    <th scope="row"><?=$text_hash_md5?></th>
                                    <td valign="middle">
                                        <input type="text" class="zdm-copy-to-clipboard zdm-copy-to-clipboard-detail" value="[zdownload_meta file=&quot;<?=htmlspecialchars($zdm_db_file->id)?>&quot; type=&quot;hash-md5&quot;]" readonly title="<?=esc_html__('Copy the shortcode to the clipboard.', 'zdm')?>">
                                        <p class="zdm-color-green" style="display: none;"><b><ion-icon name="checkmark"></ion-icon> <?=esc_html__('Shortcode copied', 'zdm')?></b></p>
                                    </td>
                                </tr>
                                <tr valign="top">
                                    <th scope="row"><?=$text_hash_sha1?></th>
                                    <td valign="middle">
                                        <input type="text" class="zdm-copy-to-clipboard zdm-copy-to-clipboard-detail" value="[zdownload_meta file=&quot;<?=htmlspecialchars($zdm_db_file->id)?>&quot; type=&quot;hash-sha1&quot;]" readonly title="<?=esc_html__('Copy the shortcode to the clipboard.', 'zdm')?>">
                                        <p class="zdm-color-green" style="display: none;"><b><ion-icon name="checkmark"></ion-icon> <?=esc_html__('Shortcode copied', 'zdm')?></b></p>
                                    </td>
                                </tr>
                            </tbody>
                        </table>
                    </div>
                </div>
                <?php
            // end if ($zdm_active_tab == 'shortcodes')
            // Tab: Replace file
            } elseif ($zdm_active_tab == 'update-file') {
                ?>
                <div class="postbox">
                    <div class="inside">
                        <h2><?=esc_html__('Replace file', 'zdm')?></h2>
                        <hr>
                        <p><?=esc_html__('Here you can upload a new file, this will replace the current file, the ID for the shortcodes remains the same.', 'zdm')?></p>

                        <table class="form-table">
                            <tbody>
                            
                                <form action="" method="post" enctype="multipart/form-data">
                                    <tr valign="top">
                                        <th scope="row"><?=esc_html__('Replace file', 'zdm')?>:</th>
                                        <td valign="middle">
                                        <input type="hidden" name="nonce" value="<?=wp_create_nonce('replace-file')?>">
                                        <input type="hidden" name="name" value="<?=htmlspecialchars($zdm_db_file->name)?>">
                                        <input type="file" name="file"> <input class="button-primary" type="submit" name="submit" value="<?=esc_html__('Upload and replace', 'zdm')?>">
                                        <div class="zdm-help-text"><?=esc_html__('Maximum file size for uploads', 'zdm')?>: <?=ini_get('upload_max_filesize')?></div>
                                        </td>
                                    </tr>
                                </form>

                            </tbody>
                        </table>
                    </div>
                </div>
                <?php
            // end if ($zdm_active_tab == 'update-file')
            // Tab: Statistik
            } elseif ($zdm_active_tab == 'statistic') {
                ////////////////////
                // Download Statistik
                ////////////////////
                require_once(ZDM__PATH . '/lib/ZDMStat.php');
                ?>

                <div class="postbox-container zdm-postbox-col-sm-2">

                    <div class="postbox">
                        <div class="inside">
                            <h3><?=esc_html__('Download statistics', 'zdm')?></h3>
                        </div>

                        <table class="wp-list-table widefat">
                            <tr valign="top">
                                <th scope="row">
                                    <b><?=esc_html__('Total', 'zdm')?>:</b>
                                </th>
                                <td valign="middle">
                                    <?=ZDMCore::number_format($zdm_db_file->count)?>
                                </td>
                            </tr>
                            <tr valign="top">
                                <th scope="row"><b><?=esc_html__('Last 30 days', 'zdm')?>:</b></th>
                                <td valign="middle">
                                    <?=ZDMCore::number_format(ZDMStat::get_downloads_count_time_for_single_stat('file', $zdm_db_file->id, 86400*30))?>
                                </td>
                            </tr>
                            <tr valign="top">
                                <th scope="row"><b><?=esc_html__('Last 7 days', 'zdm')?>:</b></th>
                                <td valign="middle">
                                    <?=ZDMCore::number_format(ZDMStat::get_downloads_count_time_for_single_stat('file', $zdm_db_file->id, 86400*7))?>
                                </td>
                            </tr>
                            <tr valign="top">
                                <th scope="row"><b><?=esc_html__('Last 24 hours', 'zdm')?>:</b></th>
                                <td valign="middle">
                                    <?=ZDMCore::number_format(ZDMStat::get_downloads_count_time_for_single_stat('file', $zdm_db_file->id, 86400))?>
                                </td>
                            </tr>
                        </table>
                    </div>

                </div>

                <div class="postbox-container zdm-postbox-col-sm-2">

                    <div class="postbox">
                        <div class="inside">
                            <h3><?=esc_html__('Latest', 'zdm')?> <?=$zdm_options['stat-single-file-last-limit']?> <?=esc_html__('downloads', 'zdm')?></h3>
                            <form action="" method="post">
                                <input type="hidden" name="nonce" value="<?=wp_create_nonce('update-stat-single-file-last-limit')?>">
                                <input type="number" name="stat-single-file-last-limit" size="5" min="1" max="500" value="<?=esc_attr($zdm_options['stat-single-file-last-limit'])?>"<?php if ($zdm_licence === 0) { echo ' disabled'; } ?> >
                                <input class="button-primary" type="submit" name="update_stat_single_file_last_limit" value="<?=esc_html__('Update', 'zdm')?>"<?php if ($zdm_licence === 0) { echo ' disabled'; } ?>>
                                <?php
                                if ($zdm_licence === 0) {
                                    ?>
                                    <br><a href="<?=ZDM__PRO_URL?>" target="_blank" title="code.urban-base.net"><?=ZDM__PRO?> <?=esc_html__('feature', 'zdm')?></a>
                                    <?php
                                }
                                ?>
                                <div class="zdm-help-text">
                                    <?=esc_html__('Determine the number of recent downloads that is displayed. This setting is global and affects all files.', 'zdm')?>
                                </div>
                            </form>
                        </div>

                        <?php
                        $zdm_last_downloads = ZDMStat::get_last_downloads_for_single_stat('file', $zdm_db_file->id, $zdm_options['stat-single-file-last-limit']);
                        $zdm_last_downloads_count = count($zdm_last_downloads);
                        if ($zdm_last_downloads_count != 0) {
                            ?>
                            <table class="wp-list-table widefat striped tags">
                                <thead>
                                    <tr>
                                        <th scope="col" width="60%"><b><?=esc_html__('Date and Time', 'zdm')?></b></th>
                                        <th scope="col" width="40%"><b><?=esc_html__('Details', 'zdm')?></b></th>
                                    </tr>
                                </thead>

                                <tbody>
                                    <?php
                                    for ($i=0; $i < $zdm_last_downloads_count; $i++) { 
                                        ?>
                                        <tr>
                                            <td><?=date("d.m.Y - h:i:s", $zdm_last_downloads[$i]->time_create)?></td>
                                            <td><a href="?page=<?=ZDM__SLUG?>-log&id=<?=$zdm_last_downloads[$i]->id?>"><?=esc_html__('Show details', 'zdm')?></a></td>
                                        </tr>
                                        <?php
                                    }
                                    ?>
                                </tbody>
                            </table>
                            <?php
                        } else {
                            ?>
                            <p style="text-align:center"><?=esc_html__('This file has not yet been downloaded.', 'zdm')?></p>
                            <?php
                        }
                        ?>
                    </div>
                    
                </div>

                <?php
            // end if ($zdm_active_tab == 'statistic')
            // Tab: Help
            } elseif ($zdm_active_tab == 'help') {
                ?>

                <div class="zdm-box zdm-box-info">
                    <p><?=esc_html__('Here you will find beginner tips and information for advanced features.', 'zdm')?></p>
                </div>

                <div class="postbox">
                    <div class="inside">
                        <h3><?=esc_html__('Add file', 'zdm')?></h3>
                        <hr>
                        <p><?=esc_html__('To upload a file click on', 'zdm')?> <?=ZDM__TITLE?> <?=esc_html__('menu on', 'zdm')?> "<a href="admin.php?page=<?=ZDM__SLUG?>-add-file"><?=esc_html__('Add file', 'zdm')?></a>".</p>
                        <p><?=esc_html__('Select a file and click "Upload".', 'zdm')?></p>
                    </div>
                </div>

                <div class="postbox">
                    <div class="inside">
                        <h3><?=esc_html__('Replace file', 'zdm')?></h3>
                        <hr>
                        <p><?=esc_html__('If you replace the file, only the file will be replaced, the ID for the shortcodes is retained.', 'zdm')?></p>
                        <p><?=esc_html__('The cache of all archives with which this file is linked is updated automatically.', 'zdm')?></p>
                    </div>
                </div>

                <div class="postbox">
                    <div class="inside">
                        <h3><?=esc_html__('Download button for file with shortcode', 'zdm')?></h3>
                        <hr>
                        <p><?=esc_html__('To show a file as a button on a page or post click on', 'zdm')?> <?=ZDM__TITLE?> <?=esc_html__('menu on', 'zdm')?> "<a href="admin.php?page=<?=ZDM__SLUG?>-files"><?=esc_html__('Files', 'zdm')?></a>".</p>
                        <p><?=esc_html__('Here you can see an overview of all files you have already uploaded.', 'zdm')?></p>
                        <p><?=esc_html__('The shortcode appears in the list and looks like this', 'zdm')?>: <code>[zdownload file="123"]</code></p>
                        <p><?=esc_html__('"123" is the unique ID of each file.', 'zdm')?></p>
                        <p><?=esc_html__('You can also click on the name to get more details about this file, on the detail page you can see more shortcodes that you can use.', 'zdm')?></p>
                    </div>
                </div>

                <div class="postbox">
                    <div class="inside">
                        <h3><?=esc_html__('Button color and styles', 'zdm')?></h3>
                        <hr>
                        <p><?=esc_html__('To make the color or other button settings click on', 'zdm')?> <?=ZDM__TITLE?> <?=esc_html__('menu on', 'zdm')?> "<a href="admin.php?page=<?=ZDM__SLUG?>-settings#zdm-download-button"><?=esc_html__('Settings', 'zdm')?></a>".</p>
                        <p><?=esc_html__('Here you can change the following in the area "Download button"', 'zdm')?>:</p>
                        <p><?=esc_html__('The standard text, the style (color of the button), outline, round corners or an icon.', 'zdm')?></p>
                        <p><?=esc_html__('All available colors can be found on the', 'zdm')?> <?=ZDM__TITLE?> <a href="https://code.urban-base.net/z-downloads/farben/?utm_source=zdm_backend" target="_blank" title="<?=esc_html__('colors', 'zdm')?>"><?=ZDM__TITLE?> <?=esc_html__('website', 'zdm')?></a></p>
                    </div>
                </div>
        
                <div class="postbox">
                    <div class="inside">
                        <h3><?=esc_html__('Output metadata', 'zdm')?></h3>
                        <hr>
                        <p><?=esc_html__('You can not only output a file as a button but also further information about this download.', 'zdm')?></p>
                        <h4><?=esc_html__('Download count', 'zdm')?></h4>
                        <p><code>[zdownload_meta file="123" type="count"]</code></p>
                        <h4><?=esc_html__('File size', 'zdm')?></h4>
                        <p><code>[zdownload_meta file="123" type="size"]</code></p>
                        <h4><?=esc_html__('More shortcodes', 'zdm')?></h4>
                        <p><?=esc_html__('More shortcode options for outputting advanced metadata can be found in the tab', 'zdm')?> <a href="admin.php?page=<?=ZDM__SLUG?>-help&tab=expert"><?=esc_html__('Expert', 'zdm')?></a> 
                        <?=esc_html__('or on the', 'zdm')?> <a href="https://code.urban-base.net/z-downloads/shortcodes/?utm_source=zdm_backend" target="_blank" title="<?=esc_html__('Shortcodes', 'zdm')?>"><?=ZDM__TITLE?> <?=esc_html__('website', 'zdm')?></a></p>
                    </div>
                </div>
                
                <div class="postbox">
                    <div class="inside">
                        <h3><?=esc_html__('Audio player', 'zdm')?></h3>
                        <hr>
                        <p><?=esc_html__('If you upload an audio file, such as an MP3 file, you can not only show it as a download button, but also as an audio player.', 'zdm')?></p>
                        <p><?=esc_html__('For this you use this shortcode', 'zdm')?>: <code>[zdownload_audio file="123"]</code></p>
                        <p><?=esc_html__('The audio player shortcode is automatically displayed on the file details page if it is an audio file.', 'zdm')?></p>
                        <p><?=esc_html__('Other output options for the audio player can be found in the tab', 'zdm')?> <a href="admin.php?page=<?=ZDM__SLUG?>-help&tab=expert"><?=esc_html__('Expert', 'zdm')?></a> 
                        <?=esc_html__('or on the', 'zdm')?> <a href="https://code.urban-base.net/z-downloads/shortcodes/?utm_source=zdm_backend" target="_blank" title="<?=esc_html__('Shortcodes', 'zdm')?>"><?=ZDM__TITLE?> <?=esc_html__('website', 'zdm')?></a></p>
                    </div>
                </div>
                
                <div class="postbox">
                    <div class="inside">
                        <h3><?=esc_html__('Video player', 'zdm')?></h3>
                        <hr>
                        <p><?=esc_html__('If you upload a video file, such as an MP4 file, you can not only display it as a download button but also as a video player.', 'zdm')?></p>
                        <p><?=esc_html__('For this you use this shortcode', 'zdm')?>: <code>[zdownload_video file="123"]</code></p>
                        <p><?=esc_html__('The video player shortcode is automatically displayed on the file details page if it is a video file.', 'zdm')?></p>
                        <p><?=esc_html__('Other output options for the video player can be found in the tab', 'zdm')?> <a href="admin.php?page=<?=ZDM__SLUG?>-help&tab=expert"><?=esc_html__('Expert', 'zdm')?></a> 
                        <?=esc_html__('or on the', 'zdm')?> <a href="https://code.urban-base.net/z-downloads/shortcodes/?utm_source=zdm_backend" target="_blank" title="<?=esc_html__('Shortcodes', 'zdm')?>"><?=ZDM__TITLE?> <?=esc_html__('website', 'zdm')?></a></p>
                    </div>
                </div>

                <div class="postbox">
                    <div class="inside">
                        <h3><?=esc_html__('Visibility', 'zdm')?></h3>
                        <hr>
                        <p><?=esc_html__('The visibility settings of a file only affect the output of this file, if this file is linked in an archive and you set the visibility of the file to "Private", then the file remains in the archive.', 'zdm')?></p>
                        <p><?=esc_html__('If the file is set to "Private", the file can no longer be downloaded, even if someone calls the URL of the download button directly.', 'zdm')?></p>
                    </div>
                </div>
                
                <div class="postbox">
                    <div class="inside">
                        <h3><?=esc_html__('Further help', 'zdm')?></h3>
                        <hr>
                        <p><?=esc_html__('Further help and documentation for special functions can be found here', 'zdm')?>: <a href="admin.php?page=<?=ZDM__SLUG?>-help&tab=expert"><?=esc_html__('Help page - expert', 'zdm')?></a></p>
                        <p><?=esc_html__('or on the', 'zdm')?> <a href="https://code.urban-base.net/z-downloads/shortcodes/?utm_source=zdm_backend" target="_blank" title="<?=ZDM__TITLE?> <?=esc_html__('website', 'zdm')?>"><?=ZDM__TITLE?> <?=esc_html__('website', 'zdm')?></a></p>
                    </div>
                </div>

                <?php
            // end if ($zdm_active_tab == 'help')
            }
            ?>
            
        <!-- end wrap -->
        </div>
        <?php
    } elseif ($zdm_status === '' OR $zdm_status === 2) { // File list
        
        $zdm_db_files = $wpdb->get_results( 
            "
            SELECT id, name, folder_path, file_name, count, file_size, status, file_type, time_create 
            FROM $zdm_tablename_files 
            ORDER BY time_create DESC
            "
        );

        ?>

        <div class="wrap">

            <h1 class="wp-heading-inline"><?=esc_html__('Files', 'zdm')?></h1>
            <hr class="wp-header-end">
            <p><a href="admin.php?page=<?=ZDM__SLUG?>-add-file" class="page-title-action"><?=esc_html__('Add file', 'zdm')?></a></p>

            <?php if (count($zdm_db_files) > 0) { ?>

            <div class="col-wrap">
                <table class="wp-list-table widefat striped tags">
                    <thead>
                        <tr>
                            <th scope="col" colspan="2"><b><?=esc_html__('Name', 'zdm')?></b></th>
                            <th scope="col"><b><?=esc_html__('Shortcode', 'zdm')?></b></th>
                            <th scope="col"><ion-icon name="stats" title="<?=esc_html__('Download count', 'zdm')?>"></ion-icon></th>
                            <th scope="col"><b><?=esc_html__('File size', 'zdm')?></b></th>
                            <th scope="col"><b><?=esc_html__('Created', 'zdm')?></b></th>
                            <th scope="col" title="<?=esc_html__('Shows in how many archives the file is linked.', 'zdm')?>"><div align="center"><b><ion-icon name="link"></ion-icon></b></div></th>
                            <th scope="col" title="<?=esc_html__('Visibility', 'zdm')?>"><div align="center"><b><ion-icon name="eye"></ion-icon></b></div></th>
                            <th scope="col" width="2%"><div align="center"><ion-icon name="trash" title="<?=esc_html__('Delete file', 'zdm')?>"></ion-icon></div></th>
                        </tr>
                    </thead>

                    <tbody>
                    <?php

                        for ($i = 0; $i < count($zdm_db_files); $i++) {

                            if (in_array($zdm_db_files[$i]->file_type, ZDM__MIME_TYPES_AUDIO)) { // Audio
                                $zdm_icon = '<ion-icon name="musical-notes"></ion-icon>';
                            } elseif (in_array($zdm_db_files[$i]->file_type, ZDM__MIME_TYPES_VIDEO)) { // Video
                                $zdm_icon = '<ion-icon name="videocam"></ion-icon>';
                            } elseif (in_array($zdm_db_files[$i]->file_type, ZDM__MIME_TYPES_IMAGE)) { // Picture
                                $zdm_icon = '<ion-icon name="images"></ion-icon>';
                            } else {
                                $zdm_icon = '<ion-icon name="document"></ion-icon>';
                            }

                            // Number of links in archives
                            $zdm_db_file_in_archive = ZDMCore::check_if_file_is_in_archive($zdm_db_files[$i]->id);
                            $zdm_db_count_file_in_archive = '';
                            if ($zdm_db_file_in_archive != false) {
                                $zdm_db_count_file_in_archive = $zdm_db_file_in_archive;
                            }

                            // File status (visibility)
                            if ($zdm_db_files[$i]->status == 'public') {
                                $zdm_file_status = '<ion-icon name="eye" class="zdm-color-green" title="' . esc_html__('Visibility: public', 'zdm') . '"></ion-icon>';
                            } else {
                                $zdm_file_status = '<ion-icon name="eye-off" title="' . esc_html__('Visibility: private', 'zdm') . '"></ion-icon>';
                            }

                            ?>
                            <tr>
                                <td>
                                    <div align="center"><?=$zdm_icon?></div>
                                </td>
                                <td>
                                    <a href="<?=ZDM__DOWNLOADS_FILES_PATH_URL . '/' . htmlspecialchars($zdm_db_files[$i]->folder_path) . '/' . htmlspecialchars($zdm_db_files[$i]->file_name)?>" title="<?=esc_html__('Download', 'zdm')?>" target="_blank" download><ion-icon name="cloud-download"></ion-icon></a> | 
                                    <b><a href="?page=<?=ZDM__SLUG?>-files&id=<?=htmlspecialchars($zdm_db_files[$i]->id)?>"><?=htmlspecialchars($zdm_db_files[$i]->name)?></a></b>
                                </td>
                                <td>
                                    <input type="text" class="zdm-copy-to-clipboard zdm-copy-to-clipboard-list" value="[zdownload file=&quot;<?=$zdm_db_files[$i]->id?>&quot;]" readonly title="<?=esc_html__('Copy the shortcode to the clipboard.', 'zdm')?>">
                                    <p class="zdm-color-green" style="display: none;"><b><ion-icon name="checkmark"></ion-icon> <?=esc_html__('Shortcode copied', 'zdm')?></b></p>
                                </td>
                                <td>
                                <a href="?page=<?=ZDM__SLUG?>-files&id=<?=htmlspecialchars($zdm_db_files[$i]->id)?>&tab=statistic"><?=ZDMCore::number_format($zdm_db_files[$i]->count)?></a>
                                </td>
                                <td>
                                    <?=htmlspecialchars($zdm_db_files[$i]->file_size)?>
                                </td>
                                <td>
                                    <?=date("d.m.Y", $zdm_db_files[$i]->time_create)?>
                                </td>
                                <td>
                                    <div align="center"><?=$zdm_db_count_file_in_archive?></div>
                                </td>
                                <td>
                                    <div align="center"><?=$zdm_file_status?></div>
                                </td>
                                <td>
                                    <a href="admin.php?page=<?=ZDM__SLUG?>-files&id=<?=htmlspecialchars($zdm_db_files[$i]->id)?>&delete=true&nonce=<?=wp_create_nonce('delete-file')?>" class="button button-secondary zdm-btn-danger-2-outline" title="<?=esc_html__('Delete file', 'zdm')?>"><ion-icon name="trash"></ion-icon></a>
                                </td>
                            </tr>
                            <?php
                        }

                    ?>
                    </tbody>

                    <tfoot>
                        <tr>
                            <th scope="col" colspan="2"><b><?=esc_html__('Name', 'zdm')?></b></th>
                            <th scope="col"><b><?=esc_html__('Shortcode', 'zdm')?></b></th>
                            <th scope="col"><ion-icon name="stats" title="<?=esc_html__('Download count', 'zdm')?>"></ion-icon></th>
                            <th scope="col"><b><?=esc_html__('File size', 'zdm')?></b></th>
                            <th scope="col"><b><?=esc_html__('Created', 'zdm')?></b></th>
                            <th scope="col" title="<?=esc_html__('Shows in how many archives the file is linked.', 'zdm')?>"><div align="center"><b><ion-icon name="link"></ion-icon></b></div></th>
                            <th scope="col" title="<?=esc_html__('Visibility', 'zdm')?>"><div align="center"><b><ion-icon name="eye"></ion-icon></b></div></th>
                            <th scope="col"><div align="center"><ion-icon name="trash" title="<?=esc_html__('Delete file', 'zdm')?>"></ion-icon></div></th>
                        </tr>
                    </tfoot>

                </table>
            </div>

            <?php } ?>

            <br>

            <?php require_once (plugin_dir_path(__FILE__) . '../inc/postbox_info_files.php'); ?>

            <br>
            <a class="alignright button" href="javascript:void(0);" onclick="window.scrollTo(0,0);" style="margin:3px 0 0 30px;"><?=esc_html__('To top', 'zdm')?></a>
        </div>
        <?php
    } elseif ($zdm_status === 3) {

        // Get data from files with the same hash
        $zdm_db_files = $wpdb->get_results( 
            "
            SELECT id, name, folder_path, file_name, count, file_size, status, file_type, time_create 
            FROM $zdm_tablename_files 
            WHERE hash_md5 = '$zdm_uploaded_file_hash' 
            ORDER BY time_create DESC
            "
        );

        ?>
        <div class="wrap">
            <h1 class="wp-heading-inline"><?=esc_html__('Upload new file', 'zdm')?></h1>

            <div class="zdm-box zdm-box-info">
                <p><b><?=esc_html__('This file has already been uploaded.', 'zdm')?></b></p>
                <p><?=esc_html__('The file you just uploaded already exists and has not been added.', 'zdm')?></p>
                <p><?=esc_html__('In the table you can see the duplicates that have already been uploaded.', 'zdm')?></p>
                <p><?=esc_html__('You can activate "Allow duplicates" in the settings, then you can upload the same file multiple times.', 'zdm')?> <a href="admin.php?page=<?=ZDM__SLUG?>-settings#zdm-expanded"><?=esc_html__('To the settings', 'zdm')?></a></p>
            </div>
        
            <?php
            // Check if files exist
            if (count($zdm_db_files) > 0) {
                ?>

                <h2><?=esc_html__('Duplicates', 'zdm')?></h2>

                <div class="col-wrap">
                    <table class="wp-list-table widefat striped tags">
                        <thead>
                            <tr>
                                <th scope="col" colspan="2"><b><?=esc_html__('Name', 'zdm')?></b></th>
                                <th scope="col"><b><?=esc_html__('Shortcode', 'zdm')?></b></th>
                                <th scope="col"><ion-icon name="stats" title="<?=esc_html__('Download count', 'zdm')?>"></ion-icon></th>
                                <th scope="col"><b><?=esc_html__('File size', 'zdm')?></b></th>
                                <th scope="col"><b><?=esc_html__('Created', 'zdm')?></b></th>
                                <th scope="col" title="<?=esc_html__('Shows in how many archives the file is linked.', 'zdm')?>"><div align="center"><b><ion-icon name="link"></ion-icon></b></div></th>
                                <th scope="col" title="<?=esc_html__('Visibility', 'zdm')?>"><div align="center"><b><ion-icon name="eye"></ion-icon></b></div></th>
                                <th scope="col" width="2%"><div align="center"><ion-icon name="trash" title="<?=esc_html__('Delete file', 'zdm')?>"></ion-icon></div></th>
                            </tr>
                        </thead>

                        <tbody>
                        <?php

                            for ($i = 0; $i < count($zdm_db_files); $i++) {

                                if (in_array($zdm_db_files[$i]->file_type, ZDM__MIME_TYPES_AUDIO)) { // Audio
                                    $zdm_icon = '<ion-icon name="musical-notes"></ion-icon>';
                                } elseif (in_array($zdm_db_files[$i]->file_type, ZDM__MIME_TYPES_VIDEO)) { // Video
                                    $zdm_icon = '<ion-icon name="videocam"></ion-icon>';
                                } elseif (in_array($zdm_db_files[$i]->file_type, ZDM__MIME_TYPES_IMAGE)) { // Bild
                                    $zdm_icon = '<ion-icon name="images"></ion-icon>';
                                } else {
                                    $zdm_icon = '<ion-icon name="document"></ion-icon>';
                                }

                                // Number of links in archives
                                $zdm_db_file_in_archive = ZDMCore::check_if_file_is_in_archive($zdm_db_files[$i]->id);
                                $zdm_db_count_file_in_archive = '';
                                if ($zdm_db_file_in_archive != false) {
                                    $zdm_db_count_file_in_archive = $zdm_db_file_in_archive;
                                }

                                // File status (visibility)
                                if ($zdm_db_files[$i]->status == 'public') {
                                    $zdm_file_status = '<ion-icon name="eye" class="zdm-color-green" title="' . esc_html__('Visibility: public', 'zdm') . '"></ion-icon>';
                                } else {
                                    $zdm_file_status = '<ion-icon name="eye-off" title="' . esc_html__('Visibility: private', 'zdm') . '"></ion-icon>';
                                }

                                ?>
                                <tr>
                                    <td>
                                        <div align="center"><?=$zdm_icon?></div>
                                    </td>
                                    <td>
                                        <a href="<?=ZDM__DOWNLOADS_FILES_PATH_URL . '/' . htmlspecialchars($zdm_db_files[$i]->folder_path) . '/' . htmlspecialchars($zdm_db_files[$i]->file_name)?>" title="<?=esc_html__('Download', 'zdm')?>" target="_blank" download><ion-icon name="cloud-download"></ion-icon></a> | 
                                        <b><a href="?page=<?=ZDM__SLUG?>-files&id=<?=htmlspecialchars($zdm_db_files[$i]->id)?>"><?=htmlspecialchars($zdm_db_files[$i]->name)?></a></b>
                                    </td>
                                    <td>
                                        <input type="text" class="zdm-copy-to-clipboard zdm-copy-to-clipboard-list" value="[zdownload file=&quot;<?=$zdm_db_files[$i]->id?>&quot;]" readonly title="<?=esc_html__('Copy the shortcode to the clipboard.', 'zdm')?>">
                                        <p class="zdm-color-green" style="display: none;"><b><ion-icon name="checkmark"></ion-icon> <?=esc_html__('Shortcode copied', 'zdm')?></b></p>
                                    </td>
                                    <td>
                                        <a href="?page=<?=ZDM__SLUG?>-files&id=<?=htmlspecialchars($zdm_db_files[$i]->id)?>&tab=statistic"><?=ZDMCore::number_format($zdm_db_files[$i]->count)?></a>
                                    </td>
                                    <td>
                                        <?=htmlspecialchars($zdm_db_files[$i]->file_size)?>
                                    </td>
                                    <td>
                                        <?=date("d.m.Y", $zdm_db_files[$i]->time_create)?>
                                    </td>
                                    <td>
                                        <div align="center"><?=$zdm_db_count_file_in_archive?></div>
                                    </td>
                                    <td>
                                        <div align="center"><?=$zdm_file_status?></div>
                                    </td>
                                    <td>
                                        <a href="admin.php?page=<?=ZDM__SLUG?>-files&id=<?=htmlspecialchars($zdm_db_files[$i]->id)?>&delete=true&nonce=<?=wp_create_nonce('delete-file')?>&duplicate-hash=<?=htmlspecialchars($zdm_uploaded_file_hash)?>" class="button button-secondary zdm-btn-danger-2-outline" title="<?=esc_html__('Delete file', 'zdm')?>"><ion-icon name="trash"></ion-icon></a>
                                    </td>
                                </tr>
                                <?php
                            }

                        ?>
                        </tbody>

                        <tfoot>
                            <tr>
                                <th scope="col" colspan="2"><b><?=esc_html__('Name', 'zdm')?></b></th>
                                <th scope="col"><b><?=esc_html__('Shortcode', 'zdm')?></b></th>
                                <th scope="col"><ion-icon name="stats" title="<?=esc_html__('Download count', 'zdm')?>"></ion-icon></th>
                                <th scope="col"><b><?=esc_html__('File size', 'zdm')?></b></th>
                                <th scope="col"><b><?=esc_html__('Created', 'zdm')?></b></th>
                                <th scope="col" title="<?=esc_html__('Shows in how many archives the file is linked.', 'zdm')?>"><div align="center"><b><ion-icon name="link"></ion-icon></b></div></th>
                                <th scope="col" title="<?=esc_html__('Visibility', 'zdm')?>"><div align="center"><b><ion-icon name="eye"></ion-icon></b></div></th>
                                <th scope="col"><div align="center"><ion-icon name="trash" title="<?=esc_html__('Delete file', 'zdm')?>"></ion-icon></div></th>
                            </tr>
                        </tfoot>

                    </table>
                </div>

                <?php } ?>

            
            <br>
            <a href="admin.php?page=<?=ZDM__SLUG?>-add-file" class="button button-primary"><?=esc_html__('Upload new file', 'zdm')?></a>
            &nbsp;&nbsp;
            <a href="admin.php?page=<?=ZDM__SLUG?>-files" class="button button-secondary"><?=esc_html__('Files overview', 'zdm')?></a>
        
        </div>
        <?php
    }
}
?>
<script type="text/javascript">
jQuery(document).ready(function(a) {
    ($inputs = a(".zdm-copy-to-clipboard")),
        $inputs.on("click", function(b) {
            var c = a(this),
                d = c.siblings("p");
            try {
                c.select(),
                    document.execCommand("copy"),
                    d.fadeIn(),
                    setTimeout(function() {
                        d.fadeOut();
                    }, 3000);
            } catch (e) {
                console.log("Unable to copy");
            }
        });
});
</script>