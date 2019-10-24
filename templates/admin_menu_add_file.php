<?php

// Abbruch bei direktem Zugriff
if( !defined( 'ABSPATH' ) ) {
    die;
}

if (current_user_can(ZDM__STANDARD_USER_ROLE)) {

    $zdm_status = 0;
    $zdm_time = time();

    global $wpdb;
    $zdm_tablename_files = $wpdb->prefix . "zdm_files";

    ////////////////////
    // Datei hinzufügen
    ////////////////////
    if (isset($_FILES['file']) && wp_verify_nonce($_POST['nonce'], 'datei-hochladen')) {
        
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

        $zdm_status = 1;
    }

    ////////////////////
    // Datei löschen
    ////////////////////
    if (isset($_POST['delete']) && wp_verify_nonce($_POST['nonce'], 'daten-aktualisieren')) {
        
        // Datei und Ordner löschen
        $zdm_folder_path = ZDM__DOWNLOADS_FILES_PATH . '/' . sanitize_file_name($_POST['folder']);
        $zdm_file_path = $zdm_folder_path . '/' . sanitize_file_name($_POST['filename']);
        $zdm_file_path_index = $zdm_folder_path . '/' . 'index.php';

        if (file_exists($zdm_file_path)) {
            unlink($zdm_file_path);
        }
        if (file_exists($zdm_file_path_index)) {
            unlink($zdm_file_path_index);
        }
        if (is_dir($zdm_folder_path)) {
            rmdir($zdm_folder_path);
        }

        $wpdb->delete(
            $zdm_tablename_files, 
            array(
                'folder_path' => sanitize_file_name($_POST['folder'])
            ));

        // Log
        ZDMCore::log('delete file', $zdm_file_path);

        $zdm_status = 2;
    }

    ////////////////////
    // Datei aktualisieren
    ////////////////////
    if (isset($_POST['update']) && wp_verify_nonce($_POST['nonce'], 'daten-aktualisieren')) {

        if ($_POST['name'] != '') {
            $name = sanitize_text_field($_POST['name']);
        } else {
            $name = sanitize_text_field($_POST['filename']);
        }
        
        $wpdb->update(
            $zdm_tablename_files, 
            array(
                'name'          => $name,
                'time_update'   => $zdm_time
            ), 
            array(
                'folder_path' => sanitize_file_name($_POST['folder'])
            ));

        // Log
        ZDMCore::log('update file', ZDM__DOWNLOADS_FILES_PATH . '/' . sanitize_file_name($_POST['folder']) . '/' . sanitize_text_field($_POST['name']));

        $zdm_status = 3;
    }

    ?>

    <div class="wrap">
        <h1 class="wp-heading-inline"><?=esc_html__('Neue Datei hochladen', 'zdm')?><?php if ($zdm_status === 1) { ?><a href="admin.php?page=<?=ZDM__SLUG?>-add-file" class="page-title-action"><?=esc_html__('Datei hinzufügen', 'zdm')?></a><?php } ?></h1>
        <hr class="wp-header-end">

        <p><a class="button-secondary" href="admin.php?page=<?=ZDM__SLUG?>-files"><?=esc_html__('zurück zur Übersicht', 'zdm')?></a></p>

        <?php if ($zdm_status === 1) { ?>

        <form action="" method="post">
            <div class="postbox">
                <div class="inside">
                    <table class="form-table">
                        <tbody>
                            <tr valign="top">
                                <th scope="row"><?=esc_html__('Status:', 'zdm')?></th>
                                <td valign="middle">
                                    <i class="ion-checkmark-circled zdm-color-green"></i>&nbsp;&nbsp;<b>"<?=$zdm_file['name']?>"</b> <?=esc_html__('erfolgreich hochgeladen.', 'zdm')?>
                                </td>
                            </tr>
                            <?php

                                    if (in_array($zdm_file['type'], ZDM__MIME_TYPES_AUDIO)) { // Audio
                                        ?>
                                        <tr valign="top">
                                            <th scope="row"><?=esc_html__('Vorschau', 'zdm')?>:</th>
                                            <td valign="middle">
                                                <audio controls preload="none">
                                                    <source src="<?=ZDM__DOWNLOADS_FILES_PATH_URL . '/' . $zdm_file['folder'] . '/' . $zdm_file['name']?>" type="<?=$zdm_file['type']?>">
                                                </audio>
                                                <br>
                                                Download: <a href="<?=ZDM__DOWNLOADS_FILES_PATH_URL . '/' . $zdm_file['folder'] . '/' . $zdm_file['name']?>" target="_blank" download><?=$zdm_file['name']?></a>
                                            </td>
                                        </tr>
                                        <?php
                                    } elseif (in_array($zdm_file['type'], ZDM__MIME_TYPES_VIDEO)) { // Video
                                        ?>
                                        <tr valign="top">
                                            <th scope="row"><?=esc_html__('Vorschau', 'zdm')?>:</th>
                                            <td valign="middle">
                                                <video width="400px" controls>
                                                    <source src="<?=ZDM__DOWNLOADS_FILES_PATH_URL . '/' . $zdm_file['folder'] . '/' . $zdm_file['name']?>" type="<?=$zdm_file['type']?>">
                                                </video>
                                                <br>
                                                Download: <a href="<?=ZDM__DOWNLOADS_FILES_PATH_URL . '/' . $zdm_file['folder'] . '/' . $zdm_file['name']?>" target="_blank" download><?=$zdm_file['name']?></a>
                                            </td>
                                        </tr>
                                        <?php
                                    } elseif (in_array($zdm_file['type'], ZDM__MIME_TYPES_IMAGE)) { // Image
                                        ?>
                                        <tr valign="top">
                                            <th scope="row"><?=esc_html__('Vorschau', 'zdm')?>:</th>
                                            <td valign="middle">
                                                <img src="<?=ZDM__DOWNLOADS_FILES_PATH_URL . '/' . $zdm_file['folder'] . '/' . $zdm_file['name']?>" width="400px" height="auto">
                                                <br>
                                                Download: <a href="<?=ZDM__DOWNLOADS_FILES_PATH_URL . '/' . $zdm_file['folder'] . '/' . $zdm_file['name']?>" target="_blank" download><?=$zdm_file['name']?></a>
                                            </td>
                                        </tr>
                                        <?php
                                    } else { // Sonstige Dateien
                                        ?>
                                        <tr valign="top">
                                            <th scope="row"><?=esc_html__('Datei Download', 'zdm')?>:</th>
                                            <td valign="middle">
                                                <a href="<?=ZDM__DOWNLOADS_FILES_PATH_URL . '/' . $zdm_file['folder'] . '/' . $zdm_file['name']?>" target="_blank" download><?=$zdm_file['name']?></a>
                                            </td>
                                        </tr>
                                        <?php
                                    }
                                    ?>
                            <tr valign="top">
                                <th scope="row"><?=esc_html__('Name:', 'zdm')?></th>
                                <td valign="middle">
                                    <input type="text" name="name" size="50%" value="" spellcheck="true" autocomplete="off" placeholder="<?=$zdm_file['name']?>">
                                    <div class="zdm-help-text"><?=esc_html__('Wenn kein Name eingegeben wird, wird automatisch der Dateiname verwendet.', 'zdm')?></div>
                                </td>
                            </tr>
                        </tbody>
                    </table>
                </div>
            </div>

            <input type="hidden" name="folder" value="<?=$zdm_file['folder']?>">
            <input type="hidden" name="filename" value="<?=$zdm_file['name']?>">
            <input type="hidden" name="nonce" value="<?=wp_create_nonce('daten-aktualisieren')?>">
            <input class="button-primary" type="submit" name="update" value="<?=esc_html__('Aktualisieren', 'zdm')?>">
            <input class="button-secondary" type="submit" name="delete" value="<?=esc_html__('Löschen', 'zdm')?>">
        </form>

        <?php } elseif ($zdm_status === 2) { ?>

        <div class="postbox">
            <div class="inside" align="center">
                <br>
                <i class="ion-checkmark-circled zdm-color-green"></i>&nbsp;&nbsp;<b>"<?=sanitize_file_name($_POST['filename'])?>"</b> <?=esc_html__('wurde gelöscht.', 'zdm')?>
                <br><br>
                <p>
                    <a class="button-secondary" href="admin.php?page=<?=ZDM__SLUG?>-add-file"><?=esc_html__('Datei hinzufügen', 'zdm')?></a> 
                    <a class="button-secondary" href="admin.php?page=<?=ZDM__SLUG?>-files"><?=esc_html__('zurück zur Übersicht', 'zdm')?></a>
                </p>
            </div>
        </div>

        <?php } elseif ($zdm_status === 3) { ?>

            <div class="postbox">
            <div class="inside" align="center">
                <br>
                <i class="ion-checkmark-circled zdm-color-green"></i>&nbsp;&nbsp;<?=esc_html__('Datei wurde erfolgreich aktualisiert.', 'zdm')?>
                <br><br>
                <p>
                    <a class="button-secondary" href="admin.php?page=<?=ZDM__SLUG?>-add-file"><?=esc_html__('Datei hinzufügen', 'zdm')?></a> 
                    <a class="button-secondary" href="admin.php?page=<?=ZDM__SLUG?>-files"><?=esc_html__('zurück zur Übersicht', 'zdm')?></a>
                </p>
            </div>
        </div>

        <?php } else { ?>

        <form action="" method="post" enctype="multipart/form-data">
            <div class="postbox">
                <div class="inside" align="center">
                    <br>
                    <input type="file" name="file">
                    <br><br>
                    <input type="hidden" name="nonce" value="<?=wp_create_nonce('datei-hochladen')?>">
                    <input class="button-primary" type="submit" name="submit" value="<?=esc_html__('Hochladen', 'zdm')?>">
                    <br><br><hr>
                    <p><?=esc_html__('Maximale Dateigröße für Uploads:', 'zdm')?> <?= ZDMCore::file_size_convert(ZDMCore::file_size_convert_str2bytes(ini_get('upload_max_filesize')))?></p>
                </div>
            </div>
        </form>

        <br>

        <?php require_once (plugin_dir_path(__FILE__) . '../inc/postbox_info_files.php'); ?>

        <?php } ?>

    </div>

<?php }
