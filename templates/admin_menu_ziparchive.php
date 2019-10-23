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

    global $wpdb;

    // DB Tabellenname
    $zdm_tablename_files = $wpdb->prefix . "zdm_files";
    $zdm_tablename_files_rel = $wpdb->prefix . "zdm_files_rel";
    $zdm_tablename_archives = $wpdb->prefix . "zdm_archives";

    if (ZDMCore::licence()) {
        $zdm_licence_array = ZDMCore::licence_array();
        $zdm_licence = 1;
    }

    // Daten aus DB holen
    $zdm_db_files = $wpdb->get_results( 
        "
        SELECT * 
        FROM $zdm_tablename_files
        "
    );

    // Dateien aus DB in Auswahlmenü speichern
    $zdm_option_output = '';
    for ($i = 0; $i < count($zdm_db_files); $i++) {

        $zdm_option_output .= '<option value="' . $zdm_db_files[$i]->id . '">' . $zdm_db_files[$i]->name . '</option>';
    }

    ////////////////////
    // Cache aktualisieren
    ////////////////////
    if (isset($_GET['archive-cache']) && wp_verify_nonce($_GET['nonce'], 'cache-aktualisieren')) {

        // Archiv-Dateien auf Aktualität prüfen
        ZDMCore::check_files_from_archive(sanitize_text_field($_GET['archive-cache']));

        $zdm_note = esc_html__('Cache aktualisiert!', 'zdm');
    }

    if (isset($_GET['id'])) {

        $zdm_status = 1;

        $zdm_archive_id = sanitize_text_field($_GET['id']);

        ////////////////////
        // Datei entfernen
        ////////////////////
        if (isset($_GET['file_delete_id']) && wp_verify_nonce($_GET['nonce'], 'datei-entfernen')) {

            $wpdb->delete(
                $zdm_tablename_files_rel, 
                array(
                    'id' => sanitize_text_field($_GET['file_delete_id'])
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
            ZDMCore::log('unlink file', sanitize_text_field($_GET['file_delete_id']));
            
            $zdm_note = esc_html__('Datei entfernt!', 'zdm');
        }

        ////////////////////
        // Daten aktualisieren
        ////////////////////
        if (isset($_POST['update']) && wp_verify_nonce($_POST['nonce'], 'daten-aktualisieren')) {

            // Check ob Felder ausgefüllt sind
            if ($_POST['name'] != '' && $_POST['zip-name'] != '') {

                // ZIP-Name
                $zdm_zip_name = str_replace(' ', '-', trim(sanitize_file_name($_POST['zip-name'])));

                // Daten aus DB holen
                $zdm_db_archives = $wpdb->get_results( 
                    "
                    SELECT * 
                    FROM $zdm_tablename_archives 
                    WHERE id = '$zdm_archive_id'
                    "
                );

                // Ordner und Cache-Datei löschen wenn sich der ZIP-Dateiname ändert
                if ($zdm_db_archives[0]->zip_name != $zdm_zip_name) {

                    // Alte Datei und Ordner löschen
                    $old_cache_folder = ZDM__DOWNLOADS_CACHE_PATH . '/' . $zdm_db_archives[0]->archive_cache_path;
                    $old_cache_file = $old_cache_folder . '/' . $zdm_db_archives[0]->zip_name . '.zip';
                    $old_cache_index = $old_cache_folder . '/' . 'index.php';
                    if (file_exists($old_cache_file)) {
                        unlink($old_cache_file);
                    }
                    if (file_exists($old_cache_index)) {
                        unlink($old_cache_index);
                    }
                    if (is_dir($old_cache_folder)) {
                        rmdir($old_cache_folder);
                    }

                    // files_rel update
                    $wpdb->update(
                        $zdm_tablename_files_rel, 
                        array(
                            'file_updated' => 1
                        ), 
                        array(
                            'id_archive' => $zdm_archive_id
                        ));
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
                    $zdm_tablename_archives, 
                    array(
                        'name'          => sanitize_text_field($_POST['name']),
                        'zip_name'      => $zdm_zip_name,
                        'description'   => sanitize_textarea_field($_POST['description']),
                        'count'         => sanitize_text_field($_POST['count']),
                        'button_text'   => $zdm_button_text,
                        'time_update'   => $zdm_time
                    ), 
                    array(
                        'id' => $zdm_archive_id
                    ));

                // Anzahl für Schleifendurchlauf definieren
                $files_count = 10;
                if ($zdm_licence === 1) {
                    $files_count = 20;
                }
                
                for ($i = 0; $i <= $files_count; $i++) {

                    // Check ob diese Datei schon zu diesem Archiv verknüpft ist
                    if (ZDMCore::check_file_rel_to_archive(sanitize_text_field($_POST['files'][$i]), $zdm_archive_id) === false) {
                        
                        // Check ob Auswahl nicht leer ist
                        if ($_POST['files'][$i] != '') {

                            // Daten in DB files_rel speichern
                            $wpdb->insert(
                                $zdm_tablename_files_rel, 
                                array(
                                    'id_file'       => sanitize_text_field($_POST['files'][$i]),
                                    'id_archive'    => $zdm_archive_id
                                )
                            );

                            // files_rel update
                            $wpdb->update(
                                $zdm_tablename_files_rel, 
                                array(
                                    'file_updated' => 1
                                ), 
                                array(
                                    'id_archive' => $zdm_archive_id
                                ));
                        }
                    }
                }

                // Log
                ZDMCore::log('update archive', $zdm_archive_id);

                $zdm_note = esc_html__('Aktualisiert', 'zdm');
            } else {
                $zdm_note = esc_html__('Name und ZIP-Datei Name darf nicht leer sein.', 'zdm');
            }
        }

        ////////////////////
        // Archiv löschen
        ////////////////////
        if ($_POST['delete'] && wp_verify_nonce($_POST['nonce'], 'daten-aktualisieren')) {

            // Daten aus DB holen
            $zdm_db_archives = $wpdb->get_results( 
                "
                SELECT * 
                FROM $zdm_tablename_archives 
                WHERE id = '$zdm_archive_id'
                "
            );

            // Alte Datei und Ordner löschen
            $old_cache_folder = ZDM__DOWNLOADS_CACHE_PATH . '/' . $zdm_db_archives[0]->archive_cache_path;
            $old_cache_file = $old_cache_folder . '/' . $zdm_db_archives[0]->zip_name . '.zip';
            $old_cache_index = $old_cache_folder . '/' . 'index.php';
            if (file_exists($old_cache_file)) {
                @unlink($old_cache_file);
            }
            if (file_exists($old_cache_index)) {
                @unlink($old_cache_index);
            }
            if (is_dir($old_cache_folder)) {
                @rmdir($old_cache_folder);
            }

            // Archiv löschen
            $wpdb->delete(
                $zdm_tablename_archives, 
                array(
                    'id' => $zdm_archive_id
                ));

            // files_rel löschen
            $wpdb->delete(
                $zdm_tablename_files_rel, 
                array(
                    'id_archive' => $zdm_archive_id
                ));

            ZDMCore::log('delete archive', $zdm_archive_id);
        
            $zdm_note = esc_html__('Archiv gelöscht!', 'zdm');

            $zdm_status = 3;
        }
    }

    if ($zdm_note != '') { ?>
        
        <div class="notice notice-success">
            <p><?=$zdm_note?></p>
        </div>

    <?php }

    if ($zdm_status === 1) {

        // Daten aus DB holen
        $zdm_db_archive = $wpdb->get_results( 
            "
            SELECT * 
            FROM $zdm_tablename_archives 
            WHERE id = $zdm_archive_id
            "
        );
        $zdm_db_archive = $zdm_db_archive[0];

        // Download-Button Text
        if ($zdm_db_archive->button_text != '') {
            $zdm_button_text = $zdm_db_archive->button_text;
        } else {
            $zdm_button_text = $zdm_options['download-btn-text'];
        }

        ?>
        
        <div class="wrap">
            <h1 class="wp-heading-inline"><?=esc_html__('Archiv bearbeiten', 'zdm')?><a href="admin.php?page=<?=ZDM__SLUG?>-ziparchive" class="page-title-action"><?=esc_html__('Zurück zur Übersicht', 'zdm')?></a></h1>
            <hr class="wp-header-end">

                <div class="postbox">
                    <div class="inside">
                    
                        <h2><?=esc_html__('Shortcodes', 'zdm')?></h2>
                        <hr>
                        <p><a href="https://code.urban-base.net/z-downloads/shortcodes?utm_source=zdm_backend" target="_blank" title="<?=ZDM__TITLE?> Shortcodes"><?=esc_html__('Alle Shortcodes', 'zdm')?></a> <?=esc_html__('im Überblick mit Erklärung und Beispielen.', 'zdm')?></p>
                        
                        <table class="form-table">
                            <tbody>
                                <tr valign="top">
                                    <th scope="row"><?=esc_html__('Shortcode', 'zdm')?>:</th>
                                    <td valign="middle">
                                        <?php
                                        if ($zdm_licence === 0) {
                                            $premium_text_link = esc_html__('Hashwert ausgeben, nur für ', 'zdm') . '<a href="' . ZDM__PRO_URL . '" target="_blank" title="code.urban-base.net">' . ZDM__TITLE . ' ' . ZDM__PRO . '</a>';
                                        } else {
                                            $premium_text_link = esc_html__('Hashwert ausgeben', 'zdm');
                                        }
                                        echo '<code>[zdownload zip="' . $zdm_archive_id . '"]</code> - ' . esc_html__('Download', 'zdm');
                                        echo '<br><br>';
                                        echo '<code>[zdownload_count zip="' . $zdm_archive_id . '"]</code> - ' . esc_html__('Download-Anzahl', 'zdm');
                                        echo '<br><br>';
                                        echo '<code>[zdownload_size zip="' . $zdm_archive_id . '"]</code> - ' . esc_html__('Dateigröße', 'zdm');
                                        echo '<br><br>';
                                        echo '<code>[zdownload_name zip="' . $zdm_archive_id . '"]</code> - ' . esc_html__('Name ausgeben', 'zdm');
                                        echo '<br><br>';
                                        echo '<code>[zdownload_hash zip="' . $zdm_archive_id->id . '" type="md5"]</code> - ' . $premium_text_link;
                                        echo '<br><br>';
                                        echo '<code>[zdownload_hash zip="' . $zdm_archive_id->id . '" type="sha1"]</code> - ' . $premium_text_link;
                                        ?>
                                    </td>
                                </tr>
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
                                if (ZDMCore::check_if_archive_cache_ok($zdm_archive_id)) {
                                    ?>
                                    <tr valign="top">
                                        <th scope="row"><?=esc_html__('Download', 'zdm')?>:</th>
                                        <td valign="middle">
                                            <a href="<?=ZDM__DOWNLOADS_CACHE_PATH_URL . '/' . $zdm_db_archive->archive_cache_path . '/' . $zdm_db_archive->zip_name . '.zip'?>" title="<?=esc_html__('ZIP-Archiv herunterladen:', 'zdm')?>" download><?=$zdm_db_archive->zip_name . '.zip'?></a>
                                        </td>
                                    </tr>
                                    <?php
                                }
                                ?>
                                <tr valign="top">
                                    <th scope="row"><?=esc_html__('Name', 'zdm')?>:</th>
                                    <td valign="middle">
                                        <input type="text" name="name" size="50%" value="<?=esc_attr($zdm_db_archive->name)?>" spellcheck="true" autocomplete="off" placeholder="">
                                    </td>
                                </tr>
                                <tr valign="top">
                                    <th scope="row"><?=esc_html__('ZIP-Datei Name', 'zdm')?>:</th>
                                    <td valign="middle">
                                        <input type="text" name="zip-name" size="50%" value="<?=esc_attr($zdm_db_archive->zip_name)?>" spellcheck="true" autocomplete="off" placeholder="">
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
                                    <th scope="row"><?=esc_html__('Beschreibung', 'zdm')?>:</th>
                                    <td valign="middle">
                                        <textarea name="description" id="" cols="100%" rows="5"><?=esc_attr($zdm_db_archive->description)?></textarea>
                                    </td>
                                </tr>
                                <tr valign="top">
                                    <th scope="row"><?=esc_html__('Count', 'zdm')?>:</th>
                                    <td valign="middle">
                                        <input type="text" name="count" size="10%" value="<?=esc_attr($zdm_db_archive->count)?>" spellcheck="true" autocomplete="off" placeholder=""> 
                                        <?=esc_html__('Anzahl an bisherigen Downloads.', 'zdm')?>
                                    </td>
                                </tr>
                                <tr valign="top">
                                    <th scope="row"><?=esc_html__('Dateigröße', 'zdm')?>:</th>
                                    <td valign="middle">
                                        <?php
                                        if (ZDMCore::check_if_archive_cache_ok($zdm_archive_id)) {
                                            echo esc_attr($zdm_db_archive->file_size);
                                        } else {
                                            ?>
                                            <p><?=esc_html__('Der Cache muss aktualisiert werden für diese Information.', 'zdm')?></p>
                                            <?php
                                        }
                                        ?>
                                    </td>
                                </tr>
                                <tr valign="top">
                                    <th scope="row"><?=esc_html__('Cache', 'zdm')?>:</th>
                                    <td valign="middle">
                                        <?php
                                        if (ZDMCore::check_if_archive_cache_ok($zdm_archive_id)) {
                                            echo '<i class="ion-checkmark-circled zdm-color-green"></i>&nbsp;&nbsp;' . esc_html__('Cache ist aktuell', 'zdm') . '.';
                                        } else {
                                            ?>
                                            <a href="admin.php?page=<?=ZDM__SLUG?>-ziparchive&id=<?=$zdm_archive_id?>&archive-cache=<?=$zdm_archive_id?>&nonce=<?=wp_create_nonce('cache-aktualisieren')?>" class="button button-primary" title="<?=esc_html__('Cache aktualisieren', 'zdm')?>"><?=esc_html__('Cache aktualisieren', 'zdm')?></a>
                                            <?php
                                        }
                                        ?>
                                    </td>
                                </tr>
                                <tr valign="top">
                                    <th scope="row"><?=esc_html__('Verknüpfte Dateien', 'zdm')?>:</th>
                                    <td valign="middle">
                                        <div class="zdm-select-50">

                                        <?php

                                        if (ZDMCore::check_if_any_file_rel_to_archive($zdm_archive_id) === false) {
                                            echo '<p>' . esc_html__('Wenn keine Datei zugewiesen ist, dann wird kein Download-Button im Frontend angezeigt.', 'zdm') . '</p>';
                                        }

                                        // id, id_file, id_archive aus DB files_rel holen
                                        $zdm_db_files_rel_array = $wpdb->get_results(
                                            "
                                            SELECT id, id_file, id_archive 
                                            FROM $zdm_tablename_files_rel 
                                            WHERE id_archive = '$zdm_archive_id' 
                                            AND file_deleted = 0
                                            "
                                            );

                                        // files_rel Anzahl
                                        $zdm_db_files_rel_count = count($zdm_db_files_rel_array);

                                        ?>
                                        <table class="zdm-table-list">
                                        <?php
                                        for ($i = 0; $i < $zdm_db_files_rel_count; $i++) { ?>

                                            <tr>
                                                <td>
                                                    <?=ZDMCore::get_file_name($zdm_db_files_rel_array[$i]->id_file)?>
                                                </td>
                                                <td>
                                                    <a href="admin.php?page=<?=ZDM__SLUG?>-ziparchive&id=<?=$zdm_archive_id?>&file_delete_id=<?=$zdm_db_files_rel_array[$i]->id?>&nonce=<?=wp_create_nonce('datei-entfernen')?>" class="button button-small">&nbsp;<i class="ion-close-round zdm-color-warning"></i>&nbsp;</a>
                                                </td>
                                            </tr>
                                        <?php } ?>
                                        </table>
                                        
                                        <br>

                                        <?php for ($i = 1; $i <= 5 - $zdm_db_files_rel_count; $i++) { ?>
                                            <select name="files[]">
                                                <option value=""></option>
                                                <?php echo $zdm_option_output; ?>
                                            </select><br>
                                        <?php } ?>

                                        <?php if ($zdm_licence === 1) {
                                            for ($i = 6; $i <= 20; $i++) { ?>
                                                <select name="files[]">
                                                    <option value=""></option>
                                                    <?php echo $zdm_option_output; ?>
                                                </select><br>
                                        <?php }
                                        } else {
                                            ?>
                                            <p><?=esc_html__('Für mehr Datei-Verknüpfungen aktiviere', 'zdm')?> <a href="<?=ZDM__PRO_URL?>" target="_blank" title="code.urban-base.net"><?=ZDM__PRO?></a>.</p>
                                            <?php
                                        } ?>
                                        </div>
                                    </td>
                                </tr>
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
        
        $zdm_db_archives = $wpdb->get_results( 
            "
            SELECT id, name, zip_name, count, archive_cache_path, file_size, time_create 
            FROM $zdm_tablename_archives 
            ORDER BY id DESC
            "
        );

        ?>

        <div class="wrap">

            <h1 class="wp-heading-inline"><?=esc_html__('ZIP-Archive', 'zdm')?><a href="admin.php?page=<?=ZDM__SLUG?>-add-archive" class="page-title-action"><?=esc_html__('Archiv erstellen', 'zdm')?></a></h1>
            <hr class="wp-header-end">

            <?php if (count($zdm_db_archives) > 0) { ?>

            <div class="col-wrap">
                <table class="wp-list-table widefat striped tags">
                    <thead>
                        <tr>
                            <th scope="col" width="30%"><b><?=esc_html__('Name', 'zdm')?></b></th>
                            <th scope="col" width="20%"><b><?=esc_html__('Shortcode', 'zdm')?></b></th>
                            <th scope="col" width="10%"><b><?=esc_html__('Downloads', 'zdm')?></b></th>
                            <th scope="col" width="10%"><b><?=esc_html__('Dateien', 'zdm')?></b></th>
                            <th scope="col" width="10%"><b><?=esc_html__('Dateigröße', 'zdm')?></b></th>
                            <th scope="col" width="10%"><b><?=esc_html__('Erstellt', 'zdm')?></b></th>
                            <th scope="col" width="10%"><b><?=esc_html__('Cache', 'zdm')?></b></th>
                        </tr>
                    </thead>

                    <tbody>
                    <?php

                        for ($i = 0; $i < count($zdm_db_archives); $i++) {

                            $zdm_dm_archive_id = $zdm_db_archives[$i]->id;
                            // id, id_file, id_archive aus DB files_rel holen
                            $zdm_db_files_rel_array = $wpdb->get_results(
                                "
                                SELECT id, id_file, id_archive 
                                FROM $zdm_tablename_files_rel 
                                WHERE id_archive = '$zdm_dm_archive_id' 
                                AND file_deleted = '0'
                                "
                                );

                            // files_rel Anzahl
                            $zdm_db_files_rel_count = count($zdm_db_files_rel_array);

                            ?>
                           <tr>
                                <td>
                                    <?php
                                    if (ZDMCore::check_if_archive_cache_ok($zdm_db_archives[$i]->id)) {
                                        ?> <a href="<?=ZDM__DOWNLOADS_CACHE_PATH_URL . '/' . $zdm_db_archives[$i]->archive_cache_path . '/' . $zdm_db_archives[$i]->zip_name?>.zip" title="<?=esc_html__('Download', 'zdm')?>" target="_blank" download><i class="ion-android-download"></i></a> |  <?php
                                    } else {
                                        ?> <i class="ion-android-download" title="<?=esc_html__('Aktualisiere den Cache der Datei um diese herunterzuladen', 'zdm')?>"></i></a> |  <?php
                                    }
                                    ?>
                                    <b><a href="?page=<?=ZDM__SLUG?>-ziparchive&id=<?=$zdm_db_archives[$i]->id?>"><?=$zdm_db_archives[$i]->name?></a></b>
                                </td>
                                <td>
                                    <code>[zdownload zip="<?=$zdm_db_archives[$i]->id?>"]</code>
                                </td>
                                <td>
                                <?=ZDMCore::number_format($zdm_db_archives[$i]->count)?>
                                </td>
                                <td>
                                    <?=ZDMCore::number_format($zdm_db_files_rel_count)?>
                                </td>
                                <td>
                                    <?php
                                    if (ZDMCore::check_if_archive_cache_ok($zdm_db_archives[$i]->id)) {
                                        echo $zdm_db_archives[$i]->file_size;
                                    }
                                    ?>
                                </td>
                                <td>
                                    <?=date("d.m.Y", $zdm_db_archives[$i]->time_create)?>
                                </td>
                                <td>
                                    <?php
                                    if (ZDMCore::check_if_archive_cache_ok($zdm_db_archives[$i]->id)) {
                                        ?> <i class="ion-checkmark-circled zdm-color-green"></i> <?php
                                    } else {
                                        ?> <a href="admin.php?page=<?=ZDM__SLUG?>-ziparchive&archive-cache=<?=$zdm_db_archives[$i]->id?>&nonce=<?=wp_create_nonce('cache-aktualisieren')?>" class="button button-primary" title="<?=esc_html__('Cache aktualisieren', 'zdm')?>"><i class="icon ion-refresh"></i></a> <?php
                                    }
                                    ?>
                                </td>
                            </tr>
                            <?php
                        }

                    ?>
                    </tbody>

                    <tfoot>
                        <tr>
                            <th scope="col"><b><?=esc_html__('Name', 'zdm')?></b></th>
                            <th scope="col"><b><?=esc_html__('Shortcode', 'zdm')?></b></th>
                            <th scope="col"><b><?=esc_html__('Downloads', 'zdm')?></b></th>
                            <th scope="col"><b><?=esc_html__('Dateien', 'zdm')?></b></th>
                            <th scope="col"><b><?=esc_html__('Dateigröße', 'zdm')?></b></th>
                            <th scope="col"><b><?=esc_html__('Erstellt', 'zdm')?></b></th>
                            <th scope="col"><b><?=esc_html__('Cache', 'zdm')?></b></th>
                        </tr>
                    </tfoot>

                </table>
            </div>

            <?php } ?>

            <br>

            <?php require_once (plugin_dir_path(__FILE__) . '../inc/postbox_info_archive.php'); ?>

            <br>
            <a class="alignright button" href="javascript:void(0);" onclick="window.scrollTo(0,0);" style="margin:3px 0 0 30px;"><?=esc_html__('Nach oben', 'zdm')?></a>

        </div>

<?php
    }
}