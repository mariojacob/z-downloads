<?php

// Abbruch bei direktem Zugriff
if (!defined('ABSPATH')) {
    die;
}

if (current_user_can(ZDM__STANDARD_USER_ROLE)) {

    $zdm_status = '';
    $zdm_note = '';
    $zdm_licence = 0;
    $zdm_options = get_option('zdm_options');
    $zdm_time = time();

    // Aktiven Tab bestimmen
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
    // Datei hinzufügen
    //////////////////////////////////////////////////
    if (isset($_FILES['file']) && wp_verify_nonce($_POST['nonce'], 'datei-hochladen') && $_FILES['file']['name'] != '') {

        if ($zdm_options['duplicate-file'] != 'on') {
            // MD5-Hash von hochgeladener Datei erzeugen
            $zdm_uploaded_file_hash = md5_file($_FILES['file']['tmp_name']);
        }

        // Datei auf Duplikat prüfen
        if ($zdm_options['duplicate-file'] != 'on' && in_array($zdm_uploaded_file_hash, ZDMCore::get_files_md5())) {

            // Datei wurde bereits hochgeladen

            // Upload-Duplikat-Seite anzeigen
            $zdm_status = 3;
        } else {

            // Datei wurde noch nicht hochgeladen
        
            $zdm_file = array();
            $zdm_file['name'] = sanitize_file_name($_FILES['file']['name']);
            $zdm_file['type'] = $_FILES['file']['type'];
            $zdm_file['size'] = ZDMCore::file_size_convert(sanitize_file_name($_FILES['file']['size']));

            // Ordnername erstellen
            $zdm_file['folder'] = md5(time() . $zdm_file['name']);

            // Ordner erstellen
            if (!is_dir($zdm_file['folder'])) {
                wp_mkdir_p(ZDM__DOWNLOADS_FILES_PATH . '/' . $zdm_file['folder']);
            }

            $zdm_file_path = ZDM__DOWNLOADS_FILES_PATH . '/' . $zdm_file['folder'] . '/' . $zdm_file['name'];

            // Datei abspeichern
            move_uploaded_file($_FILES['file']['tmp_name'], $zdm_file_path);

            // Temporäre Datei löschen
            unlink($_FILES['file']['tmp_name']);

            // index.php in Ordner kopieren
            copy('index.php', ZDM__DOWNLOADS_FILES_PATH . '/' . $zdm_file['folder'] . '/' . 'index.php');

            // MD5 aus Datei
            $zdm_file['md5'] = md5_file($zdm_file_path);

            // SHA1 aus Datei
            $zdm_file['sha1'] = sha1_file($zdm_file_path);

            // Dateipfad in DB speichern
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
            ZDMCore::log('add file', $zdm_file_path);

            $zdm_folder_path = $zdm_file['folder'];

            // ID aus DB holen
            $zdm_db_file = $wpdb->get_results( 
                "
                SELECT id 
                FROM $zdm_tablename_files 
                WHERE folder_path = '$zdm_folder_path'
                "
            );

            // Datei ID festlegen
            $zdm_file_id = $zdm_db_file[0]->id;

            // Datei Detailseite anzeigen
            $zdm_status = 1;
        }
    } elseif (isset($_GET['id']) OR isset($_POST['update']) OR isset($_POST['delete'])) {

        // Datei Detailseite anzeigen
        $zdm_status = 1;

        if (isset($_GET['id'])) {
            // Datei ID festlegen wenn ID in URL Parameter steht
            $zdm_file_id = sanitize_text_field($_GET['id']);
        } else {
            // Datei ID festlegen wenn Datei hochgeladen wurde
            $zdm_file_id = sanitize_text_field($_POST['file_id']);
        }

        //////////////////////////////////////////////////
        // Daten aktualisieren
        //////////////////////////////////////////////////
        if (isset($_POST['update']) && wp_verify_nonce($_POST['nonce'], 'daten-aktualisieren')) {

            // Daten aus DB holen
            $zdm_db_file = $wpdb->get_results( 
                "
                SELECT id 
                FROM $zdm_tablename_files 
                WHERE id = '$zdm_file_id'
                "
            );

            // Checken ob Datei existiert
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
            
                // Erfolg-Meldung ausgeben
                $zdm_note = esc_html__('Aktualisiert', 'zdm');
            } else {
                // Dateiliste anzeigen
                $zdm_status = 2;
            }
        }

        //////////////////////////////////////////////////
        // Datei löschen
        //////////////////////////////////////////////////
        if (($_POST['delete'] && wp_verify_nonce($_POST['nonce'], 'daten-aktualisieren')) OR ($_GET['delete'] && wp_verify_nonce($_GET['nonce'], 'datei-loeschen'))) {

            // Daten aus DB holen
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
                $zdm_note = esc_html__('Datei gelöscht!', 'zdm');
    
                // Dateiliste anzeigen
                $zdm_status = 2;
    
                // Check ob der Aufruf von Upload-Duplikat-Seite kommt
                if (isset($_GET['duplicate-hash'])) {

                    // Datei-Hash
                    $zdm_uploaded_file_hash = htmlspecialchars($_GET['duplicate-hash']);

                    // Daten aus DB holen
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
                }
            } else {
                // Dateiliste anzeigen
                $zdm_status = 2;
            }
        }

        //////////////////////////////////////////////////
        // Datei ersetzen
        //////////////////////////////////////////////////
        if (isset($_FILES['file']) && wp_verify_nonce($_POST['nonce'], 'datei-ersetzen') && $_FILES['file']['name'] != '') {

            // Daten aus DB holen
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

            // Dateipfad in DB speichern
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
            $zdm_note = esc_html__('Datei wurde ersetzt!', 'zdm');
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
            ZDMCore::log('unlink file', sanitize_text_field($_GET['archive_id']));
            
            // Erfolg-Meldung ausgeben
            $zdm_note = esc_html__('Datei aus Archiv entfernt!', 'zdm');
        }
    }

    if ($zdm_note != '') { ?>
        
        <div class="notice notice-success">
            <p><?=$zdm_note?></p>
        </div>

    <?php }

    if ($zdm_status === 1) { // Datei Detailseite

        // Daten aus DB holen
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

            <nav class="nav-tab-wrapper wp-clearfix zdm-nav-tabs">
                <a href="admin.php?page=<?=ZDM__SLUG?>-files&id=<?=$zdm_file_id?>" class="nav-tab zdm-nav-tab <?php echo $zdm_active_tab == 'file' ? 'nav-tab-active' : ''; ?>" aria-current="page"><?=esc_html__('Datei', 'zdm')?></a>
                <a href="admin.php?page=<?=ZDM__SLUG?>-files&id=<?=$zdm_file_id?>&tab=shortcodes" class="nav-tab <?php echo $zdm_active_tab == 'shortcodes' ? 'nav-tab-active' : ''; ?>"><?=esc_html__('Shortcodes', 'zdm')?></a>
                <a href="admin.php?page=<?=ZDM__SLUG?>-files&id=<?=$zdm_file_id?>&tab=update-file" class="nav-tab <?php echo $zdm_active_tab == 'update-file' ? 'nav-tab-active' : ''; ?>"><?=esc_html__('Datei ersetzen', 'zdm')?></a>
                <a href="admin.php?page=<?=ZDM__SLUG?>-files&id=<?=$zdm_file_id?>&tab=help" class="nav-tab <?php echo $zdm_active_tab == 'help' ? 'nav-tab-active' : ''; ?>"><ion-icon name="help-circle-outline"></ion-icon> <?=esc_html__('Hilfe', 'zdm')?></a>
            </nav>

            <br />
            <p><a href="admin.php?page=<?=ZDM__SLUG?>-files" class="page-title-action"><?=esc_html__('Zurück zur Übersicht', 'zdm')?></a> <a href="admin.php?page=<?=ZDM__SLUG?>-add-file" class="page-title-action"><?=esc_html__('Datei hinzufügen', 'zdm')?></a></p>


            <?php
            // Tabs
            if ($zdm_active_tab == 'file') { // Tab: Datei
                ?>
                
                <div class="postbox">
                    <div class="inside">

                        <h2><?=esc_html__('Allgemeine Informationen', 'zdm')?></h2>
                        <hr>
                        <table class="form-table">
                            <tbody>

                                <form action="admin.php?page=<?=ZDM__SLUG?>-files&id=<?=$zdm_file_id?>&tab=file" method="post">
                                    
                                    <tr valign="top">
                                        <th scope="row"><?=esc_html__('Sichtbarkeit', 'zdm')?>:</th>
                                        <td valign="middle">
                                            <p><input type="radio" name="status" value="public" <?php if($zdm_db_file->status == 'public'){ echo 'checked="checked"'; } ?>> <?=esc_html__('Öffentlich', 'zdm')?></p>
                                            <p><input type="radio" name="status" value="private" <?php if($zdm_db_file->status == 'private'){ echo 'checked="checked"'; } ?>> <?=esc_html__('Privat', 'zdm')?></p>
                                            <div class="zdm-help-text"><?=esc_html__('Die Sichtbarkeit dieser Datei hat nur Auswirkungen auf die Ausgabe dieser Datei, wenn diese Datei in einem Archiv verknüpft ist und du stellst die Sichtbarkeit auf "Privat", dann bleibt die Datei weiterhin im Archiv.', 'zdm')?></div>
                                        </td>
                                    </tr>
                                
                                    <?php

                                    if (in_array($zdm_db_file->file_type, ZDM__MIME_TYPES_AUDIO)) { // Audio
                                        ?>
                                        <tr valign="top">
                                            <th scope="row"><?=esc_html__('Vorschau', 'zdm')?>:</th>
                                            <td valign="middle">
                                                <audio controls preload="none">
                                                    <source src="<?=ZDM__DOWNLOADS_FILES_PATH_URL . '/' . htmlspecialchars($zdm_db_file->folder_path) . '/' . htmlspecialchars($zdm_db_file->file_name)?>" type="<?=htmlspecialchars($zdm_db_file->file_type)?>">
                                                </audio>
                                                <br>
                                                Download: <a href="<?=ZDM__DOWNLOADS_FILES_PATH_URL . '/' . htmlspecialchars($zdm_db_file->folder_path) . '/' . htmlspecialchars($zdm_db_file->file_name)?>" target="_blank" download><?=htmlspecialchars($zdm_db_file->file_name)?></a>
                                            </td>
                                        </tr>
                                        <?php
                                    } elseif (in_array($zdm_db_file->file_type, ZDM__MIME_TYPES_VIDEO)) { // Video
                                        ?>
                                        <tr valign="top">
                                            <th scope="row"><?=esc_html__('Vorschau', 'zdm')?>:</th>
                                            <td valign="middle">
                                                <video width="400px" controls>
                                                    <source src="<?=ZDM__DOWNLOADS_FILES_PATH_URL . '/' . htmlspecialchars($zdm_db_file->folder_path) . '/' . htmlspecialchars($zdm_db_file->file_name)?>" type="<?=htmlspecialchars($zdm_db_file->file_type)?>">
                                                </video>
                                                <br>
                                                Download: <a href="<?=ZDM__DOWNLOADS_FILES_PATH_URL . '/' . htmlspecialchars($zdm_db_file->folder_path) . '/' . htmlspecialchars($zdm_db_file->file_name)?>" target="_blank" download><?=htmlspecialchars($zdm_db_file->file_name)?></a>
                                            </td>
                                        </tr>
                                        <?php
                                    } elseif (in_array($zdm_db_file->file_type, ZDM__MIME_TYPES_IMAGE)) { // Image
                                        ?>
                                        <tr valign="top">
                                            <th scope="row"><?=esc_html__('Vorschau', 'zdm')?>:</th>
                                            <td valign="middle">
                                                <img src="<?=ZDM__DOWNLOADS_FILES_PATH_URL . '/' . htmlspecialchars($zdm_db_file->folder_path) . '/' . htmlspecialchars($zdm_db_file->file_name)?>" width="400px" height="auto">
                                                <br>
                                                Download: <a href="<?=ZDM__DOWNLOADS_FILES_PATH_URL . '/' . htmlspecialchars($zdm_db_file->folder_path) . '/' . htmlspecialchars($zdm_db_file->file_name)?>" target="_blank" download><?=htmlspecialchars($zdm_db_file->file_name)?></a>
                                            </td>
                                        </tr>
                                        <?php
                                    } else { // Sonstige Dateien
                                        ?>
                                        <tr valign="top">
                                            <th scope="row"><?=esc_html__('Datei Download', 'zdm')?>:</th>
                                            <td valign="middle">
                                                <a href="<?=ZDM__DOWNLOADS_FILES_PATH_URL . '/' . htmlspecialchars($zdm_db_file->folder_path) . '/' . htmlspecialchars($zdm_db_file->file_name)?>" target="_blank" download><?=htmlspecialchars($zdm_db_file->file_name)?></a>
                                            </td>
                                        </tr>
                                        <?php
                                    }
                                    ?>
                                    <tr valign="top">
                                        <th scope="row"><?=esc_html__('Datei', 'zdm')?>:</th>
                                        <td valign="middle">
                                            <input type="text" size="50%" value="<?=htmlspecialchars($zdm_db_file->file_name)?>" placeholder="" disabled>
                                        </td>
                                    </tr>
                                    <tr valign="top">
                                        <th scope="row"><?=esc_html__('Name', 'zdm')?>:</th>
                                        <td valign="middle">
                                            <input type="text" name="name" size="50%" value="<?=htmlspecialchars($zdm_db_file->name)?>" spellcheck="true" autocomplete="off" placeholder="">
                                            <div class="zdm-help-text"><?=esc_html__('Dieser Name wird in der Dateiliste angezeigt und dient dir als Orientierung.', 'zdm')?></div>
                                        </td>
                                    </tr>
                                    <tr valign="top">
                                        <th scope="row"><?=esc_html__('Download-Button Text', 'zdm')?>:</th>
                                        <td valign="middle">
                                            <input type="text" name="button-text" size="50%" value="<?=esc_attr($zdm_button_text)?>" spellcheck="true" autocomplete="off" placeholder="">
                                            <br>
                                            <div class="zdm-help-text"><?=esc_html__('Dieser Download-Button Text ist nur für diesen Download, der globale Standardtext kann in den', 'zdm')?> <a href="admin.php?page=<?=ZDM__SLUG?>-settings"><?=esc_html__('Einstellungen', 'zdm')?></a> <?=esc_html__('geändert werden.', 'zdm')?></div>
                                            <div class="zdm-help-text"><?=esc_html__('Der Standardtext ist', 'zdm')?>: <b>"<?=htmlspecialchars($zdm_options['download-btn-text'])?>"</b></div>
                                            <div class="zdm-help-text"><?=esc_html__('Um den globale Standardtext wieder zu verwenden, lasse dieses Feld einfach leer und beim Aktualisieren wird der Standardtext automatisch eingefügt.', 'zdm')?></div>
                                        </td>
                                    </tr>
                                    <tr valign="top">
                                        <th scope="row"><?=esc_html__('Download Anzahl', 'zdm')?>:</th>
                                        <td valign="middle">
                                            <input type="text" name="count" size="10%" value="<?=esc_attr($zdm_db_file->count)?>" spellcheck="true" autocomplete="off" placeholder=""> 
                                            <div class="zdm-help-text"><?=esc_html__('Anzahl an bisherigen Downloads.', 'zdm')?></div>
                                        </td>
                                    </tr>
                                    <tr valign="top">
                                        <th scope="row"><?=esc_html__('Dateigröße', 'zdm')?>:</th>
                                        <td valign="middle">
                                            <?php
                                            echo esc_attr($zdm_db_file->file_size);
                                            ?>
                                        </td>
                                    </tr>
                                    <tr valign="top">
                                        <th scope="row"><?=esc_html__('In Archiven', 'zdm')?>:</th>
                                        <td valign="middle">
                                            <?php
                                            $zdm_db_file_in_archive = ZDMCore::check_if_file_is_in_archive($zdm_db_file->id);
                                            if ($zdm_db_file_in_archive == 1) {
                                                echo esc_html__('Diese Datei ist in', 'zdm') . ' ' . $zdm_db_file_in_archive . ' ' . esc_html__('Archiv verknüpft:', 'zdm');
                                            } elseif ($zdm_db_file_in_archive > 1) {
                                                echo esc_html__('Diese Datei ist in', 'zdm') . ' ' . $zdm_db_file_in_archive . ' Archiven verknüpft:';
                                            } else {
                                                echo esc_html__('Diese Datei ist in keinem Archiv verknüpft.', 'zdm');
                                            }

                                            if ($zdm_db_file_in_archive >= 1) {

                                                ?>
                                                <br /><br />
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
                                                                <a href="admin.php?page=<?=ZDM__SLUG?>-files&id=<?=htmlspecialchars($zdm_db_file->id)?>&file_unlink_from_archive=true&archive_id=<?=htmlspecialchars($zdm_linked_archive_data->id)?>&nonce=<?=wp_create_nonce('file_unlink_from_archive')?>" class="button button-small button-secondary zdm-btn-danger-2-outline" title="<?=esc_html__('Datei aus folgendem Archiv entfernen:', 'zdm')?> <?=htmlspecialchars($zdm_linked_archive_data->name)?>"><?=esc_html__('Datei aus diesem Archiv entfernen', 'zdm')?></a>
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
                                            <p><?=esc_html__('Weitere Infos nur für', 'zdm')?> <?=ZDM__PRO?><?=esc_html__(': Hash MD5, Hash SHA1', 'zdm')?></p>
                                        </td>
                                    </tr>
                                    <?php } ?>
                            </tbody>
                        </table>
                    </div>
                </div>
                                    <input type="hidden" name="nonce" value="<?=wp_create_nonce('daten-aktualisieren')?>">
                                    <input type="hidden" name="file_id" value="<?=$zdm_file_id?>">
                                    <input class="button-primary" type="submit" name="update" value="<?=esc_html__('Aktualisieren', 'zdm')?>">
                                    <input class="button-secondary" type="submit" name="delete" value="<?=esc_html__('Löschen', 'zdm')?>">
                                </form>
                <?php
            // end if ($zdm_active_tab == 'file')
            } elseif ($zdm_active_tab == 'shortcodes') { // Tab: Shortcodes
                ?>
                <div class="postbox">
                    <div class="inside">
                    
                        <h2><?=esc_html__('Shortcodes', 'zdm')?></h2>
                        <hr>
                        <p><a href="https://code.urban-base.net/z-downloads/shortcodes?utm_source=zdm_backend" target="_blank" title="<?=ZDM__TITLE?> Shortcodes"><?=esc_html__('Alle Shortcodes', 'zdm')?></a> <?=esc_html__('im Überblick mit Erklärung und Beispielen.', 'zdm')?></p>
                        
                        <table class="form-table">
                            <tbody>
                                <tr valign="top">
                                    <th scope="row"><?=esc_html__('Download-Button', 'zdm')?></th>
                                    <td valign="middle">
                                        <input type="text" class="zdm-copy-to-clipboard zdm-copy-to-clipboard-detail" value="[zdownload file=&quot;<?=htmlspecialchars($zdm_db_file->id)?>&quot;]" readonly title="<?=esc_html__('Shortcode in die Zwischenablage kopieren.', 'zdm')?>">
                                        <p class="zdm-color-green" style="display: none;"><b><ion-icon name="checkmark"></ion-icon> <?=esc_html__('Shortcode kopiert', 'zdm')?></b></p>
                                    </td>
                                </tr>
                                <tr valign="top">
                                    <th scope="row"><?=esc_html__('Download-Anzahl', 'zdm')?></th>
                                    <td valign="middle">
                                        <input type="text" class="zdm-copy-to-clipboard zdm-copy-to-clipboard-detail" value="[zdownload_meta file=&quot;<?=htmlspecialchars($zdm_db_file->id)?>&quot; type=&quot;count&quot;]" readonly title="<?=esc_html__('Shortcode in die Zwischenablage kopieren.', 'zdm')?>">
                                        <p class="zdm-color-green" style="display: none;"><b><ion-icon name="checkmark"></ion-icon> <?=esc_html__('Shortcode kopiert', 'zdm')?></b></p>
                                    </td>
                                </tr>
                                <tr valign="top">
                                    <th scope="row"><?=esc_html__('Dateigröße', 'zdm')?></th>
                                    <td valign="middle">
                                        <input type="text" class="zdm-copy-to-clipboard zdm-copy-to-clipboard-detail" value="[zdownload_meta file=&quot;<?=htmlspecialchars($zdm_db_file->id)?>&quot; type=&quot;size&quot;]" readonly title="<?=esc_html__('Shortcode in die Zwischenablage kopieren.', 'zdm')?>">
                                        <p class="zdm-color-green" style="display: none;"><b><ion-icon name="checkmark"></ion-icon> <?=esc_html__('Shortcode kopiert', 'zdm')?></b></p>
                                    </td>
                                </tr>
                                <?php
                                if (in_array($zdm_db_file->file_type, ZDM__MIME_TYPES_AUDIO)) { // Audio
                                    ?>
                                    <tr valign="top">
                                        <th scope="row"><?=esc_html__('Audioplayer', 'zdm')?></th>
                                        <td valign="middle">
                                            <input type="text" class="zdm-copy-to-clipboard zdm-copy-to-clipboard-detail" value="[zdownload_audio file=&quot;<?=htmlspecialchars($zdm_db_file->id)?>&quot;]" readonly title="<?=esc_html__('Shortcode in die Zwischenablage kopieren.', 'zdm')?>">
                                            <p class="zdm-color-green" style="display: none;"><b><ion-icon name="checkmark"></ion-icon> <?=esc_html__('Shortcode kopiert', 'zdm')?></b></p>
                                        </td>
                                    </tr>
                                    <?php
                                } elseif (in_array($zdm_db_file->file_type, ZDM__MIME_TYPES_VIDEO)) { // Video
                                    ?>
                                    <tr valign="top">
                                        <th scope="row"><?=esc_html__('Videoplayer', 'zdm')?></th>
                                        <td valign="middle">
                                            <input type="text" class="zdm-copy-to-clipboard zdm-copy-to-clipboard-detail" value="[zdownload_video file=&quot;<?=htmlspecialchars($zdm_db_file->id)?>&quot;]" readonly title="<?=esc_html__('Shortcode in die Zwischenablage kopieren.', 'zdm')?>">
                                            <p class="zdm-color-green" style="display: none;"><b><ion-icon name="checkmark"></ion-icon> <?=esc_html__('Shortcode kopiert', 'zdm')?></b></p>
                                            <div class="zdm-help-text"><a href="https://code.urban-base.net/z-downloads/shortcodes/?utm_source=zdm_backend" target="_blank" title="<?=esc_html__('Shortcodes', 'zdm')?>"><?=esc_html__('Alle Optionen', 'zdm')?></a> <?=esc_html__('für den Videoplayer', 'zdm')?></div>
                                        </td>
                                    </tr>
                                    <?php
                                }
                                
                                if ($zdm_licence === 0) {
                                    $text_hash_md5 = esc_html__('MD5 Hashwert ausgeben', 'zdm') . '<br><a href="' . ZDM__PRO_URL . '" target="_blank" title="code.urban-base.net">' . ZDM__PRO . ' ' . esc_html__('Funktion', 'zdm') . ' </a>';
                                    $text_hash_sha1 = esc_html__('SHA1 Hashwert ausgeben', 'zdm') . '<br><a href="' . ZDM__PRO_URL . '" target="_blank" title="code.urban-base.net">' . ZDM__PRO . ' ' . esc_html__('Funktion', 'zdm') . '</a>';
                                } else {
                                    $text_hash_md5 = esc_html__('MD5 Hashwert ausgeben', 'zdm');
                                    $text_hash_sha1 = esc_html__('SHA1 Hashwert ausgeben', 'zdm');
                                }
                                ?>
                                <tr valign="top">
                                    <th scope="row"><?=$text_hash_md5?></th>
                                    <td valign="middle">
                                        <input type="text" class="zdm-copy-to-clipboard zdm-copy-to-clipboard-detail" value="[zdownload_meta file=&quot;<?=htmlspecialchars($zdm_db_file->id)?>&quot; type=&quot;hash-md5&quot;]" readonly title="<?=esc_html__('Shortcode in die Zwischenablage kopieren.', 'zdm')?>">
                                        <p class="zdm-color-green" style="display: none;"><b><ion-icon name="checkmark"></ion-icon> <?=esc_html__('Shortcode kopiert', 'zdm')?></b></p>
                                    </td>
                                </tr>
                                <tr valign="top">
                                    <th scope="row"><?=$text_hash_sha1?></th>
                                    <td valign="middle">
                                        <input type="text" class="zdm-copy-to-clipboard zdm-copy-to-clipboard-detail" value="[zdownload_meta file=&quot;<?=htmlspecialchars($zdm_db_file->id)?>&quot; type=&quot;hash-sha1&quot;]" readonly title="<?=esc_html__('Shortcode in die Zwischenablage kopieren.', 'zdm')?>">
                                        <p class="zdm-color-green" style="display: none;"><b><ion-icon name="checkmark"></ion-icon> <?=esc_html__('Shortcode kopiert', 'zdm')?></b></p>
                                    </td>
                                </tr>
                            </tbody>
                        </table>
                    </div>
                </div>
                <?php
            // end if ($zdm_active_tab == 'shortcodes')
            } elseif ($zdm_active_tab == 'update-file') { // Tab: Datei ersetzen
                ?>
                <div class="postbox">
                    <div class="inside">
                        <h2><?=esc_html__('Datei ersetzen', 'zdm')?></h2>
                        <hr>
                        <p><?=esc_html__('Hier kannst du eine neue Datei hochladen, diese ersetzt die aktuelle Datei, die ID für die Shortcodes bleibt gleich.', 'zdm')?></p>

                        <table class="form-table">
                            <tbody>
                            
                                <form action="" method="post" enctype="multipart/form-data">
                                    <tr valign="top">
                                        <th scope="row"><?=esc_html__('Datei ersetzen', 'zdm')?>:</th>
                                        <td valign="middle">
                                        <input type="hidden" name="nonce" value="<?=wp_create_nonce('datei-ersetzen')?>">
                                        <input type="hidden" name="name" value="<?=htmlspecialchars($zdm_db_file->name)?>">
                                        <input type="file" name="file"> <input class="button-primary" type="submit" name="submit" value="<?=esc_html__('Hochladen und ersetzen', 'zdm')?>">
                                        <div class="zdm-help-text"><?=esc_html__('Maximale Dateigröße für Uploads', 'zdm')?>: <?=ini_get('upload_max_filesize')?></div>
                                        </td>
                                    </tr>
                                </form>

                            </tbody>
                        </table>
                    </div>
                </div>
                <?php
            } elseif ($zdm_active_tab == 'help') { // Tab: Hilfe
                ?>

                <div class="zdm-box zdm-box-info">
                    <p><?=esc_html__('Hier findest du Einsteiger Tipps und Informationen für fortgeschrittene Funktionen.', 'zdm')?></p>
                </div>

                <div class="postbox">
                    <div class="inside">
                        <h3><?=esc_html__('Datei hinzufügen', 'zdm')?></h3>
                        <hr>
                        <p><?=esc_html__('Um eine Datei hochzuladen klicke im', 'zdm')?> <?=ZDM__TITLE?> <?=esc_html__('Menü auf', 'zdm')?> "<a href="admin.php?page=<?=ZDM__SLUG?>-add-file"><?=esc_html__('Datei hinzufügen', 'zdm')?></a>".</p>
                        <p><?=esc_html__('Wähle eine Datei aus und klicke auf "Hochladen".', 'zdm')?></p>
                    </div>
                </div>

                <div class="postbox">
                    <div class="inside">
                        <h3><?=esc_html__('Datei ersetzen', 'zdm')?></h3>
                        <hr>
                        <p><?=esc_html__('Wenn du die Datei ersetzt, dann wird nur die Datei ersetzt, die ID für die Shortcodes bleibt erhalten.', 'zdm')?></p>
                        <p><?=esc_html__('Der Cache aller Archive mit denen diese Datei verknüpft ist wird automatisch aktualisiert.', 'zdm')?></p>
                    </div>
                </div>

                <div class="postbox">
                    <div class="inside">
                        <h3><?=esc_html__('Download-Button für Datei mit Shortcode ausgeben', 'zdm')?></h3>
                        <hr>
                        <p><?=esc_html__('Um eine Datei als Button auf einer Seite oder Beitrag auszugeben klicke im', 'zdm')?> <?=ZDM__TITLE?> <?=esc_html__('Menü auf', 'zdm')?> "<a href="admin.php?page=<?=ZDM__SLUG?>-files"><?=esc_html__('Dateien', 'zdm')?></a>".</p>
                        <p><?=esc_html__('Hier siehst du eine Übersicht aller Dateien die du schon hochgeladen hast.', 'zdm')?></p>
                        <p><?=esc_html__('Der Shortcode wird in der Liste angezeigt und sieht so aus:', 'zdm')?> <code>[zdownload file="123"]</code></p>
                        <p><?=esc_html__('"123" ist die einzigartige ID der jeweiligen Datei.', 'zdm')?></p>
                        <p><?=esc_html__('Du kannst auch auf den Namen klicken um mehr Details zu dieser Datei zu bekommen, auf der Detailseite siehst du auch weitere Shortcodes die du verwendenden kannst.', 'zdm')?></p>
                    </div>
                </div>

                <div class="postbox">
                    <div class="inside">
                        <h3><?=esc_html__('Button Farbe und Styles', 'zdm')?></h3>
                        <hr>
                        <p><?=esc_html__('Um die Farbe oder sonstige Button-Einstellungen vorzunehmen klicke im', 'zdm')?> <?=ZDM__TITLE?> <?=esc_html__('Menü auf', 'zdm')?> "<a href="admin.php?page=<?=ZDM__SLUG?>-settings"><?=esc_html__('Einstellungen', 'zdm')?></a>".</p>
                        <p><?=esc_html__('Hier kannst du im Bereich "Download-Button" folgendes ändern:', 'zdm')?></p>
                        <p><?=esc_html__('Den Standardtext, den Style (Farbe des Buttons), Outline, Runde Ecken oder ein Icon.', 'zdm')?></p>
                        <p><?=esc_html__('Alle verfügbaren Farben findest du auf der', 'zdm')?> <?=ZDM__TITLE?> <a href="https://code.urban-base.net/z-downloads/farben/?utm_source=zdm_backend" target="_blank" title="<?=esc_html__('Farben', 'zdm')?>"><?=ZDM__TITLE?> <?=esc_html__('Webseite', 'zdm')?></a></p>
                    </div>
                </div>
        
                <div class="postbox">
                    <div class="inside">
                        <h3><?=esc_html__('Metadaten ausgeben', 'zdm')?></h3>
                        <hr>
                        <p><?=esc_html__('Du kannst eine Datei nicht nur als Button ausgeben sonder auch weitere Informationen zu diesem Download.', 'zdm')?></p>
                        <h4><?=esc_html__('Download-Anzahl', 'zdm')?></h4>
                        <p><code>[zdownload_meta file="123" type="count"]</code></p>
                        <h4><?=esc_html__('Dateigröße', 'zdm')?></h4>
                        <p><code>[zdownload_meta file="123" type="size"]</code></p>
                        <h4><?=esc_html__('Weitere Shortcodes', 'zdm')?></h4>
                        <p><?=esc_html__('Weitere Shortcode Optionen für die ausgabe erweiterter Metadaten findest du im Tab', 'zdm')?> <a href="admin.php?page=<?=ZDM__SLUG?>-help&tab=expert"><?=esc_html__('Experte', 'zdm')?></a> 
                        <?=esc_html__('oder auf der', 'zdm')?> <a href="https://code.urban-base.net/z-downloads/shortcodes/?utm_source=zdm_backend" target="_blank" title="<?=esc_html__('Shortcodes', 'zdm')?>"><?=ZDM__TITLE?> <?=esc_html__('Webseite', 'zdm')?></a></p>
                    </div>
                </div>
                
                <div class="postbox">
                    <div class="inside">
                        <h3><?=esc_html__('Audioplayer', 'zdm')?></h3>
                        <hr>
                        <p><?=esc_html__('Wenn du eine Audiodatei wie zum Beispiel eine MP3-Datei hochlädst kannst du diese nicht nur als Download-Button anzeigen lassen sondern auch als Audioplayer.', 'zdm')?></p>
                        <p><?=esc_html__('Dazu verwendest du diesen Shortcode:', 'zdm')?> <code>[zdownload_audio file="123"]</code></p>
                        <p><?=esc_html__('Der Shortcode für den Audioplayer wird auf der Datei-Detailseite automatisch angezeigt wenn es sich um eine Audiodatei handelt.', 'zdm')?></p>
                        <p><?=esc_html__('Weitere Ausgabeoptionen für den Audioplayer findest du im Tab', 'zdm')?> <a href="admin.php?page=<?=ZDM__SLUG?>-help&tab=expert"><?=esc_html__('Experte', 'zdm')?></a> 
                        <?=esc_html__('oder auf der', 'zdm')?> <a href="https://code.urban-base.net/z-downloads/shortcodes/?utm_source=zdm_backend" target="_blank" title="<?=esc_html__('Shortcodes', 'zdm')?>"><?=ZDM__TITLE?> <?=esc_html__('Webseite', 'zdm')?></a></p>
                    </div>
                </div>
                
                <div class="postbox">
                    <div class="inside">
                        <h3><?=esc_html__('Videoplayer', 'zdm')?></h3>
                        <hr>
                        <p><?=esc_html__('Wenn du eine Videodatei wie zum Beispiel eine MP4-Datei hochlädst kannst du diese nicht nur als Download-Button anzeigen lassen sondern auch als Videoplayer.', 'zdm')?></p>
                        <p><?=esc_html__('Dazu verwendest du diesen Shortcode:', 'zdm')?> <code>[zdownload_video file="123"]</code></p>
                        <p><?=esc_html__('Der Shortcode für den Videoplayer wird auf der Datei-Detailseite automatisch angezeigt wenn es sich um eine Videodatei handelt.', 'zdm')?></p>
                        <p><?=esc_html__('Weitere Ausgabeoptionen für den Videoplayer findest du im Tab', 'zdm')?> <a href="admin.php?page=<?=ZDM__SLUG?>-help&tab=expert"><?=esc_html__('Experte', 'zdm')?></a> 
                        <?=esc_html__('oder auf der', 'zdm')?> <a href="https://code.urban-base.net/z-downloads/shortcodes/?utm_source=zdm_backend" target="_blank" title="<?=esc_html__('Shortcodes', 'zdm')?>"><?=ZDM__TITLE?> <?=esc_html__('Webseite', 'zdm')?></a></p>
                    </div>
                </div>

                <div class="postbox">
                    <div class="inside">
                        <h3><?=esc_html__('Sichtbarkeit', 'zdm')?></h3>
                        <hr>
                        <p><?=esc_html__('Die Sichtbarkeitseinstellungen einer Datei hat nur Auswirkungen auf die Ausgabe dieser Datei, wenn diese Datei in einem Archiv verknüpft ist und du stellst die Sichtbarkeit der Datei auf "Privat", dann bleibt die Datei weiterhin im Archiv bestehen.', 'zdm')?></p>
                        <p><?=esc_html__('Ist die Datei auf "Privat" gestellt, dann kann die Datei nicht mehr heruntergeladen werden, auch wenn jemand die URL des Download-Buttons direkt aufruft.', 'zdm')?></p>
                    </div>
                </div>
                
                <div class="postbox">
                    <div class="inside">
                        <h3><?=esc_html__('Weitere Hilfe', 'zdm')?></h3>
                        <hr>
                        <p><?=esc_html__('Weitere Hilfe und Dokumentation für spezielle Funktionen findest du hier', 'zdm')?>: <a href="admin.php?page=<?=ZDM__SLUG?>-help&tab=expert"><?=esc_html__('Hilfeseite - Experte', 'zdm')?></a></p>
                        <p><?=esc_html__('Oder auf der', 'zdm')?> <a href="https://code.urban-base.net/z-downloads/shortcodes/?utm_source=zdm_backend" target="_blank" title="<?=ZDM__TITLE?> <?=esc_html__('Webseite', 'zdm')?>"><?=ZDM__TITLE?> <?=esc_html__('Webseite', 'zdm')?></a></p>
                    </div>
                </div>

                <?php
            }
            ?>
            
        <!-- end wrap -->
        </div>
        <?php
    } elseif ($zdm_status === '' OR $zdm_status === 2) { // Datei Liste
        
        $zdm_db_files = $wpdb->get_results( 
            "
            SELECT id, name, folder_path, file_name, count, file_size, status, file_type, time_create 
            FROM $zdm_tablename_files 
            ORDER BY time_create DESC
            "
        );

        ?>

        <div class="wrap">

            <h1 class="wp-heading-inline"><?=esc_html__('Dateien', 'zdm')?></h1>
            <hr class="wp-header-end">
            <p><a href="admin.php?page=<?=ZDM__SLUG?>-add-file" class="page-title-action"><?=esc_html__('Datei hinzufügen', 'zdm')?></a></p>

            <?php if (count($zdm_db_files) > 0) { ?>

            <div class="col-wrap">
                <table class="wp-list-table widefat striped tags">
                    <thead>
                        <tr>
                            <th scope="col" colspan="2"><b><?=esc_html__('Name', 'zdm')?></b></th>
                            <th scope="col"><b><?=esc_html__('Shortcode', 'zdm')?></b></th>
                            <th scope="col"><div align="center"><ion-icon name="cloud-download" title="<?=esc_html__('Download Anzahl', 'zdm')?>"></ion-icon></div></th>
                            <th scope="col"><b><?=esc_html__('Dateigröße', 'zdm')?></b></th>
                            <th scope="col"><b><?=esc_html__('Erstellt', 'zdm')?></b></th>
                            <th scope="col" title="<?=esc_html__('Zeigt an in wie vielen Archiven die Datei verknüpft ist.', 'zdm')?>"><div align="center"><b><ion-icon name="link"></ion-icon></b></div></th>
                            <th scope="col" title="<?=esc_html__('Sichtbarkeit', 'zdm')?>"><div align="center"><b><ion-icon name="eye"></ion-icon></b></div></th>
                            <th scope="col" width="2%"><div align="center"><ion-icon name="trash" title="<?=esc_html__('Datei löschen', 'zdm')?>"></ion-icon></div></th>
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

                            // Anzahl an Verknüpfungen in Archiven
                            $zdm_db_file_in_archive = ZDMCore::check_if_file_is_in_archive($zdm_db_files[$i]->id);
                            $zdm_db_count_file_in_archive = '';
                            if ($zdm_db_file_in_archive != false) {
                                $zdm_db_count_file_in_archive = $zdm_db_file_in_archive;
                            }

                            // Datei Status (Sichtbarkeit)
                            if ($zdm_db_files[$i]->status == 'public') {
                                $zdm_file_status = '<ion-icon name="eye" class="zdm-color-green" title="' . esc_html__('Sichtbarkeit: Öffentlich', 'zdm') . '"></ion-icon>';
                            } else {
                                $zdm_file_status = '<ion-icon name="eye-off" title="' . esc_html__('Sichtbarkeit: Privat', 'zdm') . '"></ion-icon>';
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
                                    <input type="text" class="zdm-copy-to-clipboard zdm-copy-to-clipboard-list" value="[zdownload file=&quot;<?=$zdm_db_files[$i]->id?>&quot;]" readonly title="<?=esc_html__('Shortcode in die Zwischenablage kopieren.', 'zdm')?>">
                                    <p class="zdm-color-green" style="display: none;"><b><ion-icon name="checkmark"></ion-icon> <?=esc_html__('Shortcode kopiert', 'zdm')?></b></p>
                                </td>
                                <td>
                                    <div align="center"><?=ZDMCore::number_format($zdm_db_files[$i]->count)?></div>
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
                                    <a href="admin.php?page=<?=ZDM__SLUG?>-files&id=<?=htmlspecialchars($zdm_db_files[$i]->id)?>&delete=true&nonce=<?=wp_create_nonce('datei-loeschen')?>" class="button button-secondary zdm-btn-danger-2-outline" title="<?=esc_html__('Datei löschen', 'zdm')?>"><ion-icon name="trash"></ion-icon></a>
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
                            <th scope="col"><div align="center"><ion-icon name="cloud-download" title="<?=esc_html__('Download Anzahl', 'zdm')?>"></ion-icon></div></th>
                            <th scope="col"><b><?=esc_html__('Dateigröße', 'zdm')?></b></th>
                            <th scope="col"><b><?=esc_html__('Erstellt', 'zdm')?></b></th>
                            <th scope="col" title="<?=esc_html__('Zeigt an in wie vielen Archiven die Datei verknüpft ist.', 'zdm')?>"><div align="center"><b><ion-icon name="link"></ion-icon></b></div></th>
                            <th scope="col" title="<?=esc_html__('Sichtbarkeit', 'zdm')?>"><div align="center"><b><ion-icon name="eye"></ion-icon></b></div></th>
                            <th scope="col"><div align="center"><ion-icon name="trash" title="<?=esc_html__('Datei löschen', 'zdm')?>"></ion-icon></div></th>
                        </tr>
                    </tfoot>

                </table>
            </div>

            <?php } ?>

            <br>

            <?php require_once (plugin_dir_path(__FILE__) . '../inc/postbox_info_files.php'); ?>

            <br>
            <a class="alignright button" href="javascript:void(0);" onclick="window.scrollTo(0,0);" style="margin:3px 0 0 30px;"><?=esc_html__('Nach oben', 'zdm')?></a>
        </div>
        <?php
    } elseif ($zdm_status === 3) {

        // Daten von Dateien mit dem selben Hash holen
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
            <h1 class="wp-heading-inline"><?=esc_html__('Neue Datei hochladen', 'zdm')?></h1>
            <div class="notice notice-warning">
                <p><b><?=esc_html__('Diese Datei wurde bereits hochgeladen.', 'zdm')?></b></p>
                <p><?=esc_html__('Die gerade hochgeladene Datei existiert bereits und wurde nicht hinzugefügt.', 'zdm')?></p>
                <p><?=esc_html__('In der Tabelle siehst du die Duplikate die bereits hochgeladen wurden.', 'zdm')?></p>
                <p><?=esc_html__('Du kannst in den Einstellungen "Duplikate zulassen" aktivieren, dann kannst du die selbe Datei auch mehrfach hochladen.', 'zdm')?> <a href="admin.php?page=<?=ZDM__SLUG?>-settings"><?=esc_html__('Zu den Einstellungen', 'zdm')?></a></p>
            </div>
        
            <?php
            // Check ob Dateien existieren
            if (count($zdm_db_files) > 0) {
                ?>

                <div class="col-wrap">
                    <table class="wp-list-table widefat striped tags">
                        <thead>
                            <tr>
                                <th scope="col" colspan="2"><b><?=esc_html__('Name', 'zdm')?></b></th>
                                <th scope="col"><b><?=esc_html__('Shortcode', 'zdm')?></b></th>
                                <th scope="col"><div align="center"><ion-icon name="cloud-download" title="<?=esc_html__('Download Anzahl', 'zdm')?>"></ion-icon></div></th>
                                <th scope="col"><b><?=esc_html__('Dateigröße', 'zdm')?></b></th>
                                <th scope="col"><b><?=esc_html__('Erstellt', 'zdm')?></b></th>
                                <th scope="col" title="<?=esc_html__('Zeigt an in wie vielen Archiven die Datei verknüpft ist.', 'zdm')?>"><div align="center"><b><ion-icon name="link"></ion-icon></b></div></th>
                                <th scope="col" title="<?=esc_html__('Sichtbarkeit', 'zdm')?>"><div align="center"><b><ion-icon name="eye"></ion-icon></b></div></th>
                                <th scope="col" width="2%"><div align="center"><ion-icon name="trash" title="<?=esc_html__('Datei löschen', 'zdm')?>"></ion-icon></div></th>
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

                                // Anzahl an Verknüpfungen in Archiven
                                $zdm_db_file_in_archive = ZDMCore::check_if_file_is_in_archive($zdm_db_files[$i]->id);
                                $zdm_db_count_file_in_archive = '';
                                if ($zdm_db_file_in_archive != false) {
                                    $zdm_db_count_file_in_archive = $zdm_db_file_in_archive;
                                }

                                // Datei Status (Sichtbarkeit)
                                if ($zdm_db_files[$i]->status == 'public') {
                                    $zdm_file_status = '<ion-icon name="eye" class="zdm-color-green" title="' . esc_html__('Sichtbarkeit: Öffentlich', 'zdm') . '"></ion-icon>';
                                } else {
                                    $zdm_file_status = '<ion-icon name="eye-off" title="' . esc_html__('Sichtbarkeit: Privat', 'zdm') . '"></ion-icon>';
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
                                        <input type="text" class="zdm-copy-to-clipboard zdm-copy-to-clipboard-list" value="[zdownload file=&quot;<?=$zdm_db_files[$i]->id?>&quot;]" readonly title="<?=esc_html__('Shortcode in die Zwischenablage kopieren.', 'zdm')?>">
                                        <p class="zdm-color-green" style="display: none;"><b><ion-icon name="checkmark"></ion-icon> <?=esc_html__('Shortcode kopiert', 'zdm')?></b></p>
                                    </td>
                                    <td>
                                        <div align="center"><?=ZDMCore::number_format($zdm_db_files[$i]->count)?></div>
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
                                        <a href="admin.php?page=<?=ZDM__SLUG?>-files&id=<?=htmlspecialchars($zdm_db_files[$i]->id)?>&delete=true&nonce=<?=wp_create_nonce('datei-loeschen')?>&duplicate-hash=<?=htmlspecialchars($zdm_uploaded_file_hash)?>" class="button button-secondary zdm-btn-danger-2-outline" title="<?=esc_html__('Datei löschen', 'zdm')?>"><ion-icon name="trash"></ion-icon></a>
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
                                <th scope="col"><div align="center"><ion-icon name="cloud-download" title="<?=esc_html__('Download Anzahl', 'zdm')?>"></ion-icon></div></th>
                                <th scope="col"><b><?=esc_html__('Dateigröße', 'zdm')?></b></th>
                                <th scope="col"><b><?=esc_html__('Erstellt', 'zdm')?></b></th>
                                <th scope="col" title="<?=esc_html__('Zeigt an in wie vielen Archiven die Datei verknüpft ist.', 'zdm')?>"><div align="center"><b><ion-icon name="link"></ion-icon></b></div></th>
                                <th scope="col" title="<?=esc_html__('Sichtbarkeit', 'zdm')?>"><div align="center"><b><ion-icon name="eye"></ion-icon></b></div></th>
                                <th scope="col"><div align="center"><ion-icon name="trash" title="<?=esc_html__('Datei löschen', 'zdm')?>"></ion-icon></div></th>
                            </tr>
                        </tfoot>

                    </table>
                </div>

                <?php } ?>

            
            <br />
            <a href="admin.php?page=<?=ZDM__SLUG?>-add-file" class="button button-primary"><?=esc_html__('Neue Datei hochladen', 'zdm')?></a>
            &nbsp;&nbsp;
            <a href="admin.php?page=<?=ZDM__SLUG?>-files" class="button button-secondary"><?=esc_html__('Dateien Übersicht', 'zdm')?></a>
        
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