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

    if (ZDMCore::licence()) {
        $zdm_licence_array = ZDMCore::licence_array();
        $zdm_licence = 1;
    }

    global $wpdb;
    $zdm_tablename_files = $wpdb->prefix . "zdm_files";
    $zdm_tablename_files_rel = $wpdb->prefix . "zdm_files_rel";

    if (isset($_GET['id'])) {

        $zdm_status = 1;

        $zdm_file_id = sanitize_text_field($_GET['id']);

        ////////////////////
        // Daten aktualisieren
        ////////////////////
        if (isset($_POST['update']) && wp_verify_nonce($_POST['nonce'], 'daten-aktualisieren')) {

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
                    'time_update'   => $zdm_time
                ), 
                array(
                    'id' => $zdm_file_id
                ));

            // Log
            ZDMCore::log('update file', $zdm_file_id);
        
            $zdm_note = esc_html__('Aktualisiert', 'zdm');
        }

        ////////////////////
        // Datei löschen
        ////////////////////
        if (($_POST['delete'] && wp_verify_nonce($_POST['nonce'], 'daten-aktualisieren')) OR ($_GET['delete'] && wp_verify_nonce($_GET['nonce'], 'datei-loeschen'))) {

            // Daten aus DB holen
            $zdm_db_file = $wpdb->get_results( 
                "
                SELECT * 
                FROM $zdm_tablename_files 
                WHERE id = $zdm_file_id
                "
            );
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
        
            $zdm_note = esc_html__('Datei gelöscht!', 'zdm');
        }

        ////////////////////
        // Datei ersetzen
        ////////////////////
        if (isset($_FILES['file']) && wp_verify_nonce($_POST['nonce'], 'datei-hochladen')) {

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

            $zdm_note = esc_html__('Datei wurde ersetzt!', 'zdm');
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
            <h1 class="wp-heading-inline"><?=esc_html__('Datei bearbeiten', 'zdm')?><a href="admin.php?page=<?=ZDM__SLUG?>-files" class="page-title-action"><?=esc_html__('Zurück zur Übersicht', 'zdm')?></a></h1>
            <hr class="wp-header-end">
            
                <div class="postbox">
                    <div class="inside">
                    
                        <h2><?=esc_html__('Shortcodes', 'zdm')?></h2>
                        <hr>
                        <p><a href="https://code.urban-base.net/z-downloads/shortcodes?utm_source=zdm_backend" target="_blank" title="<?=ZDM__TITLE?> Shortcodes"><?=esc_html__('Alle Shortcodes', 'zdm')?></a> <?=esc_html__('im Überblick mit Erklärung und Beispielen.', 'zdm')?></p>
                        
                        <table class="form-table">
                            <tbody>
                                <tr valign="top">
                                    <th scope="row"><?=esc_html__('Download-Button', 'zdm')?></th>
                                    <td valign="middle"><code>[zdownload file="<?=$zdm_db_file->id?>"]</code></td>
                                </tr>
                                <tr valign="top">
                                    <th scope="row"><?=esc_html__('Download-Anzahl', 'zdm')?></th>
                                    <td valign="middle"><code>[zdownload_meta file="<?=$zdm_db_file->id?>" type="count"]</code></td>
                                </tr>
                                <tr valign="top">
                                    <th scope="row"><?=esc_html__('Dateigröße', 'zdm')?></th>
                                    <td valign="middle"><code>[zdownload_meta file="<?=$zdm_db_file->id?>" type="size"]</code></td>
                                </tr>
                                <?php
                                if (in_array($zdm_db_file->file_type, ZDM__MIME_TYPES_AUDIO)) { // Audio
                                    ?>
                                    <tr valign="top">
                                        <th scope="row"><?=esc_html__('Audioplayer', 'zdm')?></th>
                                        <td valign="middle"><code>[zdownload_audio file="<?=$zdm_db_file->id?>"]</code></td>
                                    </tr>
                                    <?php
                                } elseif (in_array($zdm_db_file->file_type, ZDM__MIME_TYPES_VIDEO)) { // Video
                                    ?>
                                    <tr valign="top">
                                        <th scope="row"><?=esc_html__('Videoplayer', 'zdm')?></th>
                                        <td valign="middle">
                                            <code>[zdownload_video file="<?=$zdm_db_file->id?>"]</code> 
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
                                    <td valign="middle"><code>[zdownload_meta file="<?=$zdm_db_file->id?>" type="hash-md5"]</code></td>
                                </tr>
                                <tr valign="top">
                                    <th scope="row"><?=$text_hash_sha1?></th>
                                    <td valign="middle"><code>[zdownload_meta file="<?=$zdm_db_file->id?>" type="hash-sha1"]</code></td>
                                </tr>
                            </tbody>
                        </table>
                    </div>
                </div>

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
                                        <input type="hidden" name="nonce" value="<?=wp_create_nonce('datei-hochladen')?>">
                                        <input type="file" name="file"> <input class="button-primary" type="submit" name="submit" value="<?=esc_html__('Hochladen und ersetzen', 'zdm')?>">
                                        <div class="zdm-help-text"><?=esc_html__('Maximale Dateigröße für Uploads', 'zdm')?>: <?=ini_get('upload_max_filesize')?></div>
                                        </td>
                                    </tr>
                                </form>

                            </tbody>
                        </table>
                    </div>
                </div>

                <div class="postbox">
                    <div class="inside">

                        <h2><?=esc_html__('Allgemeine Informationen', 'zdm')?></h2>
                        <hr>
                        <table class="form-table">
                            <tbody>

                                <form action="" method="post">
                                
                                    <?php

                                    if (in_array($zdm_db_file->file_type, ZDM__MIME_TYPES_AUDIO)) { // Audio
                                        ?>
                                        <tr valign="top">
                                            <th scope="row"><?=esc_html__('Vorschau', 'zdm')?>:</th>
                                            <td valign="middle">
                                                <audio controls preload="none">
                                                    <source src="<?=ZDM__DOWNLOADS_FILES_PATH_URL . '/' . $zdm_db_file->folder_path . '/' . $zdm_db_file->file_name?>" type="<?=$zdm_db_file->file_type?>">
                                                </audio>
                                                <br>
                                                Download: <a href="<?=ZDM__DOWNLOADS_FILES_PATH_URL . '/' . $zdm_db_file->folder_path . '/' . $zdm_db_file->file_name?>" target="_blank" download><?=$zdm_db_file->file_name?></a>
                                            </td>
                                        </tr>
                                        <?php
                                    } elseif (in_array($zdm_db_file->file_type, ZDM__MIME_TYPES_VIDEO)) { // Video
                                        ?>
                                        <tr valign="top">
                                            <th scope="row"><?=esc_html__('Vorschau', 'zdm')?>:</th>
                                            <td valign="middle">
                                                <video width="400px" controls>
                                                    <source src="<?=ZDM__DOWNLOADS_FILES_PATH_URL . '/' . $zdm_db_file->folder_path . '/' . $zdm_db_file->file_name?>" type="<?=$zdm_db_file->file_type?>">
                                                </video>
                                                <br>
                                                Download: <a href="<?=ZDM__DOWNLOADS_FILES_PATH_URL . '/' . $zdm_db_file->folder_path . '/' . $zdm_db_file->file_name?>" target="_blank" download><?=$zdm_db_file->file_name?></a>
                                            </td>
                                        </tr>
                                        <?php
                                    } elseif (in_array($zdm_db_file->file_type, ZDM__MIME_TYPES_IMAGE)) { // Image
                                        ?>
                                        <tr valign="top">
                                            <th scope="row"><?=esc_html__('Vorschau', 'zdm')?>:</th>
                                            <td valign="middle">
                                                <img src="<?=ZDM__DOWNLOADS_FILES_PATH_URL . '/' . $zdm_db_file->folder_path . '/' . $zdm_db_file->file_name?>" width="400px" height="auto">
                                                <br>
                                                Download: <a href="<?=ZDM__DOWNLOADS_FILES_PATH_URL . '/' . $zdm_db_file->folder_path . '/' . $zdm_db_file->file_name?>" target="_blank" download><?=$zdm_db_file->file_name?></a>
                                            </td>
                                        </tr>
                                        <?php
                                    } else { // Sonstige Dateien
                                        ?>
                                        <tr valign="top">
                                            <th scope="row"><?=esc_html__('Datei Download', 'zdm')?>:</th>
                                            <td valign="middle">
                                                <a href="<?=ZDM__DOWNLOADS_FILES_PATH_URL . '/' . $zdm_db_file->folder_path . '/' . $zdm_db_file->file_name?>" target="_blank" download><?=$zdm_db_file->file_name?></a>
                                            </td>
                                        </tr>
                                        <?php
                                    }
                                    ?>
                                    <tr valign="top">
                                        <th scope="row"><?=esc_html__('Datei', 'zdm')?>:</th>
                                        <td valign="middle">
                                            <input type="text" size="50%" value="<?=$zdm_db_file->file_name?>" placeholder="" disabled>
                                        </td>
                                    </tr>
                                    <tr valign="top">
                                        <th scope="row"><?=esc_html__('Name', 'zdm')?>:</th>
                                        <td valign="middle">
                                            <input type="text" name="name" size="50%" value="<?=$zdm_db_file->name?>" spellcheck="true" autocomplete="off" placeholder="">
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
                                        <th scope="row"><?=esc_html__('Count', 'zdm')?>:</th>
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
                                    <?php if ($zdm_licence === 1) { ?>
                                    <tr valign="top">
                                        <th scope="row"><?=esc_html__('Hash MD5', 'zdm')?>:</th>
                                        <td valign="middle">
                                            <input type="text" name="name" size="50%" value="<?=$zdm_db_file->hash_md5?>" placeholder="" disabled>
                                        </td>
                                    </tr>
                                    <tr valign="top">
                                        <th scope="row"><?=esc_html__('Hash SHA1', 'zdm')?>:</th>
                                        <td valign="middle">
                                            <input type="text" name="name" size="50%" value="<?=$zdm_db_file->hash_sha1?>" placeholder="" disabled>
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
                                    <input class="button-primary" type="submit" name="update" value="<?=esc_html__('Aktualisieren', 'zdm')?>">
                                    <input class="button-secondary" type="submit" name="delete" value="<?=esc_html__('Löschen', 'zdm')?>">
                                </form>
        </div>

    <?php } else { // Datei Liste
        
        $zdm_db_files = $wpdb->get_results( 
            "
            SELECT id, name, folder_path, file_name, count, file_size, file_type, time_create 
            FROM $zdm_tablename_files 
            ORDER BY time_create DESC
            "
        );

        ?>

        <div class="wrap">

            <h1 class="wp-heading-inline"><?=esc_html__('Dateien', 'zdm')?><a href="admin.php?page=<?=ZDM__SLUG?>-add-file" class="page-title-action"><?=esc_html__('Datei hinzufügen', 'zdm')?></a></h1>
            <hr class="wp-header-end">

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

                            $zdm_db_file_in_archive = ZDMCore::check_if_file_is_in_archive($zdm_db_files[$i]->id);
                            $zdm_db_file_in_archive_icon = '';
                            if ($zdm_db_file_in_archive != false) {
                                $zdm_db_file_in_archive_icon = $zdm_db_file_in_archive;
                            }

                            ?>
                            <tr>
                                <td>
                                    <div align="center"><?=$zdm_icon?></div>
                                </td>
                                <td>
                                    <a href="<?=ZDM__DOWNLOADS_FILES_PATH_URL . '/' . $zdm_db_files[$i]->folder_path . '/' . $zdm_db_files[$i]->file_name?>" title="<?=esc_html__('Download', 'zdm')?>" target="_blank" download><ion-icon name="cloud-download"></ion-icon></a> | 
                                    <b><a href="?page=<?=ZDM__SLUG?>-files&id=<?=$zdm_db_files[$i]->id?>"><?=$zdm_db_files[$i]->name?></a></b>
                                </td>
                                <td>
                                    <code>[zdownload file="<?=$zdm_db_files[$i]->id?>"]</code>
                                </td>
                                <td>
                                <div align="center"><?=ZDMCore::number_format($zdm_db_files[$i]->count)?></div>
                                </td>
                                <td>
                                    <?=$zdm_db_files[$i]->file_size?>
                                </td>
                                <td>
                                    <?=date("d.m.Y", $zdm_db_files[$i]->time_create)?>
                                </td>
                                <td>
                                    <div align="center"><?=$zdm_db_file_in_archive_icon?></div>
                                </td>
                                <td>
                                    <a href="admin.php?page=<?=ZDM__SLUG?>-files&id=<?=$zdm_db_files[$i]->id?>&delete=true&nonce=<?=wp_create_nonce('datei-loeschen')?>" class="button button-secondary zdm-btn-danger-2-outline" title="<?=esc_html__('Datei löschen', 'zdm')?>"><ion-icon name="trash"></ion-icon></a>
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
    }
}