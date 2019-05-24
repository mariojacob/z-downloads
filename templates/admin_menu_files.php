<?php

// Abbruch bei direktem Zugriff
if( !defined( 'ABSPATH' ) ) {
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

            if ($_POST['name'] == '') {
                $name = sanitize_text_field($_POST['filename']);
            } else {
                $name = sanitize_text_field($_POST['name']);
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

            if ($zdm_licence === 1) {
                $wpdb->update(
                    $zdm_tablename_files, 
                    array(
                        'name'          => $name,
                        'description'   => sanitize_textarea_field($_POST['description']),
                        'count'         => sanitize_text_field($_POST['count']),
                        'button_text'   => $zdm_button_text,
                        'time_update'   => $zdm_time
                    ), 
                    array(
                        'id' => $zdm_file_id
                    ));
            } else {
                $wpdb->update(
                    $zdm_tablename_files, 
                    array(
                        'name'          => $name,
                        'description'   => sanitize_textarea_field($_POST['description']),
                        'count'         => sanitize_text_field($_POST['count']),
                        'time_update'   => $zdm_time
                    ), 
                    array(
                        'id' => $zdm_file_id
                    ));
            }

            // Log
            ZDMCore::log('update file', $zdm_file_id);
        
            $zdm_note = esc_html__('Aktualisiert', 'zdm');
        }

        ////////////////////
        // Datei löschen
        ////////////////////
        if ($_POST['delete'] && wp_verify_nonce($_POST['nonce'], 'daten-aktualisieren')) {

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

            $zdm_status = 3;
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

    if ($zdm_status === 1) {

        // Daten aus DB holen
        $zdm_db_file = $wpdb->get_results( 
            "
            SELECT * 
            FROM $zdm_tablename_files 
            WHERE id = $zdm_file_id
            "
        );
        $zdm_db_file = $zdm_db_file[0];

        if ($zdm_licence === 1) {
            if ($zdm_db_file->button_text != '') {
                $zdm_button_text = $zdm_db_file->button_text;
            } else {
                $zdm_button_text = $zdm_options['download-btn-text'];
            }
        } else {
            $zdm_button_text = $zdm_options['download-btn-text'];
        }

        ?>
        
        <div class="wrap">
            <h1 class="wp-heading-inline"><?=esc_html__('Datei bearbeiten', 'zdm')?><a href="admin.php?page=<?=ZDM__SLUG?>-files" class="page-title-action"><?=esc_html__('Zurück zur Übersicht', 'zdm')?></a></h1>
            <hr class="wp-header-end">
            
                <div class="postbox">
                    <div class="inside">
                        <table class="form-table">
                            <tbody>
            <form action="" method="post" enctype="multipart/form-data">
                                <tr valign="top">
                                    <th scope="row"><?=esc_html__('Datei ersetzen:', 'zdm')?></th>
                                    <td valign="middle">
                                    <input type="hidden" name="nonce" value="<?=wp_create_nonce('datei-hochladen')?>">
                                    <input type="file" name="file"> <input class="button-primary" type="submit" name="submit" value="<?=esc_html__('Hochladen', 'zdm')?>">
                                    <div class="zdm-help-text"><?=esc_html__('Maximale Dateigröße für Uploads:', 'zdm')?> <?=ini_get('upload_max_filesize')?></div>
                                    </td>
                                </tr>
                                <tr><th colspan="2"><hr></th></tr>
            </form>

            <form action="" method="post">
                                <tr valign="top">
                                    <th scope="row"><?=esc_html__('Shortcodes:', 'zdm')?></th>
                                    <td valign="middle">
                                        <?php
                                        echo '<code>[zdownload file="' . $zdm_db_file->id . '"]</code>';
                                        echo '<br>';
                                        echo '<code>[zdownload_count file="' . $zdm_db_file->id . '"]</code>';
                                        if ($zdm_licence != 1) { echo ' <div class="zdm-help-text">' . esc_html__('Gibt die Anzahl an Downloads aus (nur in', 'zdm') .  ' <a href="' . ZDM__PRO_URL . '" target="_blank">' . ZDM__PRO . '</a> ' . esc_html__('verfügbar', 'zdm') . ')</div>'; }
                                        echo '<br>';
                                        echo '<code>[zdownload_size file="' . $zdm_db_file->id . '"]</code>';
                                        if ($zdm_licence != 1) { echo ' <div class="zdm-help-text">' . esc_html__('Gibt die Dateigröße aus (nur in', 'zdm') .  ' <a href="' . ZDM__PRO_URL . '" target="_blank">' . ZDM__PRO . '</a> ' . esc_html__('verfügbar', 'zdm') . ')</div>'; }
                                        ?>
                                    </td>
                                </tr>
                                <?php

                                // Audio
                                if (in_array($zdm_db_file->file_type, ZDM__MIME_TYPES_AUDIO)) {
                                    ?>
                                    <tr valign="top">
                                        <th scope="row"><?=esc_html__('Vorschau:', 'zdm')?></th>
                                        <td valign="middle">
                                            <audio controls preload="none">
                                                <source src="<?=ZDM__DOWNLOADS_FILES_PATH_URL . '/' . $zdm_db_file->folder_path . '/' . $zdm_db_file->file_name?>" type="<?=$zdm_db_file->file_type?>">
                                            </audio>
                                        </td>
                                    </tr>
                                    <?php
                                }

                                // Video
                                if (in_array($zdm_db_file->file_type, ZDM__MIME_TYPES_VIDEO)) {
                                    ?>
                                    <tr valign="top">
                                        <th scope="row"><?=esc_html__('Vorschau:', 'zdm')?></th>
                                        <td valign="middle">
                                            <video width="400px" controls>
                                                <source src="<?=ZDM__DOWNLOADS_FILES_PATH_URL . '/' . $zdm_db_file->folder_path . '/' . $zdm_db_file->file_name?>" type="<?=$zdm_db_file->file_type?>">
                                            </video>
                                        </td>
                                    </tr>
                                    <?php
                                }
                                ?>
                                <tr valign="top">
                                    <th scope="row"><?=esc_html__('Datei:', 'zdm')?></th>
                                    <td valign="middle">
                                        <input type="text" size="50%" value="<?=$zdm_db_file->file_name?>" placeholder="" disabled>
                                    </td>
                                </tr>
                                <tr valign="top">
                                    <th scope="row"><?=esc_html__('Name:', 'zdm')?></th>
                                    <td valign="middle">
                                        <input type="text" name="name" size="50%" value="<?=$zdm_db_file->name?>" spellcheck="true" autocomplete="off" placeholder="">
                                    </td>
                                </tr>
                                <tr valign="top">
                                    <th scope="row"><?=esc_html__('Download-Button Text:', 'zdm')?></th>
                                    <td valign="middle">
                                        <?php
                                        if ($zdm_licence === 1) { ?>
                                            <input type="text" name="button-text" size="50%" value="<?=esc_attr($zdm_button_text)?>" spellcheck="true" autocomplete="off" placeholder="">
                                        <?php } else {?>
                                            <input type="text" name="button-text-prev" size="50%" value="" placeholder="<?=esc_attr($zdm_button_text)?>" disabled>
                                        <?php } ?>
                                    </td>
                                </tr>
                                <tr valign="top">
                                    <th scope="row"><?=esc_html__('Count:', 'zdm')?></th>
                                    <td valign="middle">
                                        <input type="text" name="count" size="10%" value="<?=esc_attr($zdm_db_file->count)?>" spellcheck="true" autocomplete="off" placeholder=""> 
                                        <?=esc_html__('Anzahl an bisherigen Downloads.', 'zdm')?>
                                    </td>
                                </tr>
                                <tr valign="top">
                                    <th scope="row"><?=esc_html__('Dateigröße:', 'zdm')?></th>
                                    <td valign="middle">
                                        <?php
                                        echo esc_attr($zdm_db_file->file_size);
                                        ?>
                                    </td>
                                </tr>
                                <?php if ($zdm_licence === 1) { ?>
                                <tr valign="top">
                                    <th scope="row"><?=esc_html__('Hash MD5:', 'zdm')?></th>
                                    <td valign="middle">
                                        <input type="text" name="name" size="50%" value="<?=$zdm_db_file->hash_md5?>" placeholder="" disabled>
                                    </td>
                                </tr>
                                <tr valign="top">
                                    <th scope="row"><?=esc_html__('Hash SHA1:', 'zdm')?></th>
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

    <?php } else {
        
        $zdm_db_files = $wpdb->get_results( 
            "
            SELECT id, name, file_name, count, file_size, time_create 
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
                            <th scope="col" width="40%"><b><?=esc_html__('Name', 'zdm')?></b></th>
                            <th scope="col" width="20%"><b><?=esc_html__('Shortcode', 'zdm')?></b></th>
                            <th scope="col" width="15%"><b><?=esc_html__('Downloads', 'zdm')?></b></th>
                            <th scope="col" width="15%"><b><?=esc_html__('Dateigröße', 'zdm')?></b></th>
                            <th scope="col" width="10%"><b><?=esc_html__('Datum', 'zdm')?></b></th>
                        </tr>
                    </thead>

                    <tbody>
                    <?php 

                        for ($i = 0; $i < count($zdm_db_files); $i++) {
                            echo '<tr>';
                                echo '<td>';
                                    echo '<b><a href="?page=' . ZDM__SLUG . '-files&id=' . $zdm_db_files[$i]->id . '">' . $zdm_db_files[$i]->name . '</a></b>';
                                echo '</td>';
                                echo '<td>';
                                    echo '<code>[zdownload file="' . $zdm_db_files[$i]->id . '"]</code>';
                                echo '</td>';
                                echo '<td>';
                                    echo ZDMCore::number_format($zdm_db_files[$i]->count);
                                echo '</td>';
                                echo '<td>';
                                    echo $zdm_db_files[$i]->file_size;
                                echo '</td>';
                                echo '<td>';
                                    echo date("d.m.Y", $zdm_db_files[$i]->time_create);
                                echo '</td>';
                            echo '</tr>';
                        }

                    ?>
                    </tbody>

                    <tfoot>
                        <tr>
                            <th scope="col"><b><?=esc_html__('Name', 'zdm')?></b></th>
                            <th scope="col"><b><?=esc_html__('Shortcode', 'zdm')?></b></th>
                            <th scope="col"><b><?=esc_html__('Downloads', 'zdm')?></b></th>
                            <th scope="col"><b><?=esc_html__('Dateigröße', 'zdm')?></b></th>
                            <th scope="col"><b><?=esc_html__('Datum', 'zdm')?></b></th>
                        </tr>
                    </tfoot>

                </table>
            </div>

            <?php } ?>

            <br>
            <a class="alignright button" href="javascript:void(0);" onclick="window.scrollTo(0,0);" style="margin:3px 0 0 30px;"><?=esc_html__('Nach oben', 'zdm')?></a>
        </div>

<?php
    }
}