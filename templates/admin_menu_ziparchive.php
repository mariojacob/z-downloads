<?php
// Abort by direct access
if (!defined('ABSPATH'))
    die;

if (current_user_can(ZDM__STANDARD_USER_ROLE)) {

    $zdm_status = '';
    $zdm_note = '';
    $zdm_licence = 0;
    $zdm_options = get_option('zdm_options');
    $zdm_time = time();
    $zdm_warning = '';

    // Aktiven Tab bestimmen
    if (isset($_GET['tab'])) {
        $zdm_active_tab = htmlspecialchars($_GET['tab']);
    } else {
        $zdm_active_tab = 'archive';
    }

    global $wpdb;

    // Datembank Tabellennamen
    $zdm_tablename_files = $wpdb->prefix . "zdm_files";
    $zdm_tablename_files_rel = $wpdb->prefix . "zdm_files_rel";
    $zdm_tablename_archives = $wpdb->prefix . "zdm_archives";

    if (ZDMCore::licence()) {
        $zdm_licence_array = ZDMCore::licence_array();
        $zdm_licence = 1;
    }

    $zdm_db_files = $wpdb->get_results(
        "
        SELECT * 
        FROM $zdm_tablename_files
        "
    );

    $zdm_db_files_count = count($zdm_db_files);

    // Speichere Dateien von Datenbank in selection Menü
    $zdm_option_output = '';
    for ($i = 0; $i < $zdm_db_files_count; $i++) {

        $zdm_option_output .= '<option value="' . $zdm_db_files[$i]->id . '">' . $zdm_db_files[$i]->name . '</option>';
    }

    //////////////////////////////////////////////////
    // Cache aktualisieren
    //////////////////////////////////////////////////
    if (isset($_GET['archive-cache']) && wp_verify_nonce($_GET['nonce'], 'update-cache')) {

        // Überprüfen, ob die Archivdateien auf dem neuesten Stand sind
        ZDMCore::check_files_from_archive(sanitize_text_field($_GET['archive-cache']));

        $zdm_note = esc_html__('Cache updated!', 'zdm');
    }

    //////////////////////////////////////////////////
    // Statistik Anzahl aktualisieren
    //////////////////////////////////////////////////
    if (isset($_POST['update_stat_single_archive_last_limit']) && wp_verify_nonce($_POST['nonce'], 'update-stat-single-archive-last-limit')) {

        $zdm_options['stat-single-archive-last-limit'] = trim(sanitize_text_field($_POST['stat-single-archive-last-limit']));
        if (add_option('zdm_options', $zdm_options) === FALSE) {
            update_option('zdm_options', $zdm_options);
        }
        $zdm_options = get_option('zdm_options');
        $zdm_note = esc_html__('Updated settings!', 'zdm');
    }

    if (isset($_GET['id'])) {

        $zdm_status = 1;

        $zdm_archive_id = sanitize_text_field($_GET['id']);

        $zdm_db_archive = $wpdb->get_results(
            "
            SELECT id 
            FROM $zdm_tablename_archives 
            WHERE id = '$zdm_archive_id'
            "
        );

        // Check ob Archiv existiert
        if (count($zdm_db_archive) > 0) {

            //////////////////////////////////////////////////
            // Datei löschen
            //////////////////////////////////////////////////
            if (isset($_GET['file_delete_id']) && wp_verify_nonce($_GET['nonce'], 'remove-file')) {

                $wpdb->delete(
                    $zdm_tablename_files_rel,
                    array(
                        'id' => sanitize_text_field($_GET['file_delete_id'])
                    )
                );

                $wpdb->update(
                    $zdm_tablename_files_rel,
                    array(
                        'file_updated' => 1
                    ),
                    array(
                        'id_archive' => $zdm_archive_id
                    )
                );

                // Log
                ZDMCore::log('unlink file', 'archive ID: ' . $zdm_archive_id . ', file ID: ' . sanitize_text_field($_GET['file_delete_id']));

                $zdm_note = esc_html__('File removed!', 'zdm');
            }

            //////////////////////////////////////////////////
            // Allgemeine Daten aktualisieren
            //////////////////////////////////////////////////
            if (isset($_POST['update']) && wp_verify_nonce($_POST['nonce'], 'update-data')) {

                if ($_POST['name'] != '' && $_POST['zip-name'] != '') {

                    // ZIP-Name
                    $zdm_zip_name = str_replace(' ', '-', trim(sanitize_file_name($_POST['zip-name'])));

                    $zdm_db_archives = $wpdb->get_results(
                        "
                        SELECT * 
                        FROM $zdm_tablename_archives 
                        WHERE id = '$zdm_archive_id'
                        "
                    );

                    // Lösche den Ordner und die Cache-Datei, wenn sich der Name der ZIP-Datei ändert
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

                        $wpdb->update(
                            $zdm_tablename_files_rel,
                            array(
                                'file_updated' => 1
                            ),
                            array(
                                'id_archive' => $zdm_archive_id
                            )
                        );
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
                            'status'        => sanitize_text_field($_POST['status']),
                            'time_update'   => $zdm_time
                        ),
                        array(
                            'id' => $zdm_archive_id
                        )
                    );

                    // Anzahl an Dateien zum Verknüpfen angeben
                    $files_count = 5;
                    if ($zdm_licence === 1) {
                        $files_count = 20;
                    }

                    for ($i = 0; $i <= $files_count; $i++) {

                        // Check ob die Datei schon zum Archiv verknüpft ist
                        if (@ZDMCore::check_file_rel_to_archive(sanitize_text_field($_POST['files'][$i]), $zdm_archive_id) === false) {

                            // Check ob die Auswahl nicht leer ist
                            if (@$_POST['files'][$i] != '') {

                                // Daten in Datenbank (files_rel) speichern
                                $wpdb->insert(
                                    $zdm_tablename_files_rel,
                                    array(
                                        'id_file'       => sanitize_text_field($_POST['files'][$i]),
                                        'id_archive'    => $zdm_archive_id
                                    )
                                );

                                $wpdb->update(
                                    $zdm_tablename_files_rel,
                                    array(
                                        'file_updated' => 1
                                    ),
                                    array(
                                        'id_archive' => $zdm_archive_id
                                    )
                                );

                                ZDMCore::log('link file', 'archive ID: ' . $zdm_archive_id . ', file ID: ' . sanitize_text_field($_POST['files'][$i]));
                            }
                        }
                    }

                    // Log
                    ZDMCore::log('update archive', 'ID: ' . $zdm_archive_id);

                    $zdm_note = esc_html__('Updated', 'zdm');
                } else {
                    $zdm_warning = esc_html__('Name and ZIP file name can not be empty.', 'zdm');
                }
            }

            //////////////////////////////////////////////////
            // Archiv löschen
            //////////////////////////////////////////////////
            if ((isset($_POST['delete']) && wp_verify_nonce($_POST['nonce'], 'update-data')) or (isset($_GET['delete']) && wp_verify_nonce($_GET['nonce'], 'delete-archive'))) {

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

                // Lösche Archiv-Eintrag in Datenbank
                $wpdb->delete(
                    $zdm_tablename_archives,
                    array(
                        'id' => $zdm_archive_id
                    )
                );

                // Lösche Eintrag in files_rel
                $wpdb->delete(
                    $zdm_tablename_files_rel,
                    array(
                        'id_archive' => $zdm_archive_id
                    )
                );

                ZDMCore::log('delete archive', 'ID: ' . $zdm_archive_id);

                if (headers_sent()) {
                    $zdm_status = 2;
                } else {
                    // Redirect
                    $zdm_ziparchive_url = 'admin.php?page=' . ZDM__SLUG . '-ziparchive';
                    wp_redirect($zdm_ziparchive_url);
                    exit;
                }
            }
        }
    }

    if ($zdm_note != '') { ?>

        <div class="notice notice-success">
            <p><?= $zdm_note ?></p>
        </div>
    <?php
    }
    if ($zdm_warning != '') { ?>

        <div class="notice notice-warning">
            <p><?= $zdm_warning ?></p>
        </div>
    <?php
    }

    if ($zdm_status === 1) {

        $zdm_db_files_rel_array = $wpdb->get_results(
            "
            SELECT id, id_file, id_archive 
            FROM $zdm_tablename_files_rel 
            WHERE id_archive = '$zdm_archive_id' 
            AND file_deleted = 0
            "
        );

        $zdm_db_files_rel_count = count($zdm_db_files_rel_array);

        $zdm_db_archive = $wpdb->get_results(
            "
            SELECT * 
            FROM $zdm_tablename_archives 
            WHERE id = $zdm_archive_id
            "
        );
        $zdm_db_archive = $zdm_db_archive[0];

        // Download Button Text
        if ($zdm_db_archive->button_text != '') {
            $zdm_button_text = $zdm_db_archive->button_text;
        } else {
            $zdm_button_text = $zdm_options['download-btn-text'];
        }

    ?>

        <div class="wrap">

            <h1><?= esc_html__('Archive', 'zdm') ?>: <?= esc_attr($zdm_db_archive->name) ?></h1>
            <hr class="wp-header-end">
            <br>
            <a href="admin.php?page=<?= ZDM__SLUG ?>-ziparchive" class="page-title-action"><?= esc_html__('Back to overview', 'zdm') ?></a> <a href="admin.php?page=<?= ZDM__SLUG ?>-add-archive" class="page-title-action"><?= esc_html__('Create a new archive', 'zdm') ?></a>
            <br><br>

            <nav class="nav-tab-wrapper wp-clearfix zdm-nav-tabs">
                <a href="admin.php?page=<?= ZDM__SLUG ?>-ziparchive&id=<?= $zdm_archive_id ?>" class="nav-tab zdm-nav-tab <?php echo $zdm_active_tab == 'archive' ? 'nav-tab-active' : ''; ?>" aria-current="page"><span class="material-icons-outlined zdm-md-1">insert_drive_file</span> <?= esc_html__('Archive', 'zdm') ?></a>
                <a href="admin.php?page=<?= ZDM__SLUG ?>-ziparchive&id=<?= $zdm_archive_id ?>&tab=shortcodes" class="nav-tab <?php echo $zdm_active_tab == 'shortcodes' ? 'nav-tab-active' : ''; ?>">[/] <?= esc_html__('Shortcodes', 'zdm') ?></a>
                <a href="admin.php?page=<?= ZDM__SLUG ?>-ziparchive&id=<?= $zdm_archive_id ?>&tab=statistics" class="nav-tab <?php echo $zdm_active_tab == 'statistics' ? 'nav-tab-active' : ''; ?>"><span class="material-icons-round zdm-md-1">leaderboard</span> <?= esc_html__('Statistics', 'zdm') ?></a>
                <a href="admin.php?page=<?= ZDM__SLUG ?>-ziparchive&id=<?= $zdm_archive_id ?>&tab=help" class="nav-tab <?php echo $zdm_active_tab == 'help' ? 'nav-tab-active' : ''; ?>"><span class="material-icons-round zdm-md-1">help_outline</span> <?= esc_html__('Help', 'zdm') ?></a>
            </nav>

            <br>

            <?php
            //////////////////////////////////////////////////
            // Tabs
            //////////////////////////////////////////////////
            if ($zdm_active_tab == 'archive') { // Tab: Archiv
            ?>

                <div class="postbox">
                    <div class="inside">

                        <h2><?= esc_html__('Archive information', 'zdm') ?></h2>
                        <hr>
                        <table class="form-table">
                            <tbody>
                                <form action="" method="post">

                                    <tr valign="top">
                                        <th scope="row"><?= esc_html__('Visibility', 'zdm') ?>:</th>
                                        <td valign="middle">
                                            <p><input type="radio" name="status" value="public" <?php if ($zdm_db_archive->status == 'public') {
                                                                                                    echo 'checked="checked"';
                                                                                                } ?>><span class="material-icons-round zdm-md-1-5 zdm-color-green zdm-mx-2">visibility</span><?= esc_html__('Public', 'zdm') ?></p>
                                            <p><input type="radio" name="status" value="private" <?php if ($zdm_db_archive->status == 'private') {
                                                                                                        echo 'checked="checked"';
                                                                                                    } ?>><span class="material-icons-round zdm-md-1-5 zdm-mx-2">visibility_off</span><?= esc_html__('Private', 'zdm') ?></p>
                                        </td>
                                    </tr>
                                    <?php
                                    if (ZDMCore::check_if_archive_cache_ok($zdm_archive_id)) {
                                    ?>
                                        <tr valign="top">
                                            <th scope="row"><?= esc_html__('Download', 'zdm') ?>:</th>
                                            <td valign="middle">
                                                <a href="<?= ZDM__DOWNLOADS_CACHE_PATH_URL . '/' . htmlspecialchars($zdm_db_archive->archive_cache_path) . '/' . htmlspecialchars($zdm_db_archive->zip_name) . '.zip' ?>" title="<?= esc_html__('Download ZIP archive', 'zdm') ?>:" download><?= $zdm_db_archive->zip_name . '.zip' ?></a>
                                            </td>
                                        </tr>
                                    <?php
                                    }
                                    ?>
                                    <tr valign="top">
                                        <th scope="row"><?= esc_html__('Name', 'zdm') ?>:</th>
                                        <td valign="middle">
                                            <input type="text" name="name" size="50%" value="<?= esc_attr($zdm_db_archive->name) ?>" spellcheck="true" autocomplete="off" placeholder="">
                                            <div class="zdm-help-text"><?= esc_html__('This name is displayed in the archive list and serves as a guide.', 'zdm') ?></div>
                                        </td>
                                    </tr>
                                    <tr valign="top">
                                        <th scope="row"><?= esc_html__('ZIP file name', 'zdm') ?>:</th>
                                        <td valign="middle">
                                            <input type="text" name="zip-name" size="50%" value="<?= esc_attr($zdm_db_archive->zip_name) ?>" spellcheck="true" autocomplete="off" placeholder="">
                                            <div class="zdm-help-text"><?= esc_html__('This name is used as the file name for the ZIP file such as', 'zdm') ?>: <b><?= esc_attr($zdm_db_archive->zip_name) ?>.zip</b></div>
                                            <div class="zdm-help-text"><?= esc_html__('Spaces are automatically converted to hyphens.', 'zdm') ?></div>
                                        </td>
                                    </tr>
                                    <tr valign="top">
                                        <th scope="row"><?= esc_html__('Download button text', 'zdm') ?>:</th>
                                        <td valign="middle">
                                            <input type="text" name="button-text" size="50%" value="<?= esc_attr($zdm_button_text) ?>" spellcheck="true" autocomplete="off" placeholder="">
                                            <br>
                                            <div class="zdm-help-text"><?= esc_html__('This text is only displayed for this download, for all others the default text is used and can be changed in the', 'zdm') ?> <a href="admin.php?page=<?= ZDM__SLUG ?>-settings#zdm-download-button"><?= esc_html__('settings', 'zdm') ?></a>.</div>
                                            <div class="zdm-help-text"><?= esc_html__('The default text is', 'zdm') ?>: <b>"<?= htmlspecialchars($zdm_options['download-btn-text']) ?>"</b></div>
                                            <div class="zdm-help-text"><?= esc_html__('To reuse the default global text, just leave this field blank and the default text is automatically inserted when updating.', 'zdm') ?></div>
                                        </td>
                                    </tr>
                                    <tr valign="top">
                                        <th scope="row"><?= esc_html__('Description', 'zdm') ?>:</th>
                                        <td valign="middle">
                                            <textarea name="description" id="" cols="100%" rows="5"><?= esc_attr($zdm_db_archive->description) ?></textarea>
                                        </td>
                                    </tr>
                                    <tr valign="top">
                                        <th scope="row"><?= esc_html__('Download count', 'zdm') ?>:</th>
                                        <td valign="middle">
                                            <input type="text" name="count" size="10%" value="<?= esc_attr($zdm_db_archive->count) ?>" spellcheck="true" autocomplete="off" placeholder="">
                                            <?= esc_html__('Number of previous downloads.', 'zdm') ?>
                                        </td>
                                    </tr>
                                    <tr valign="top">
                                        <th scope="row"><?= esc_html__('File size', 'zdm') ?>:</th>
                                        <td valign="middle">
                                            <?php
                                            if (ZDMCore::check_if_archive_cache_ok($zdm_archive_id)) {
                                                echo esc_attr($zdm_db_archive->file_size);
                                            } else {
                                            ?>
                                                <p><?= esc_html__('The cache needs to be updated for this information.', 'zdm') ?></p>
                                            <?php
                                            }
                                            ?>
                                        </td>
                                    </tr>
                                    <tr valign="top">
                                        <th scope="row"><?= esc_html__('Cache', 'zdm') ?>:</th>
                                        <td valign="middle">
                                            <?php
                                            if (ZDMCore::check_if_archive_cache_ok($zdm_archive_id)) {
                                                echo '<span class="material-icons-round zdm-md-1-5 zdm-color-green">check_circle_outline</span>&nbsp;&nbsp;' . esc_html__('Cache up-to-date', 'zdm') . '.';
                                            } else {
                                            ?>
                                                <a href="admin.php?page=<?= ZDM__SLUG ?>-ziparchive&id=<?= htmlspecialchars($zdm_archive_id) ?>&archive-cache=<?= htmlspecialchars($zdm_archive_id) ?>&nonce=<?= wp_create_nonce('update-cache') ?>" class="button button-primary" title="<?= esc_html__('Update cache', 'zdm') ?>"><?= esc_html__('Update cache', 'zdm') ?></a>
                                                <div class="zdm-help-text"><?= esc_html__('Updating the cache manually is optional. You do not have to update the cache manually, it will be updated automatically as soon as this ZIP file is requested for download.', 'zdm') ?></div>
                                                <div class="zdm-help-text"><?= esc_html__('By clicking on "Update cache" a ZIP file with the linked files is created.', 'zdm') ?></div>
                                            <?php
                                            }
                                            ?>
                                        </td>
                                    </tr>
                                    <tr valign="top">
                                        <th scope="row"><?= esc_html__('Linked files', 'zdm') ?>:</th>
                                        <td valign="middle">
                                            <div class="zdm-select-50">

                                                <?php

                                                if (ZDMCore::check_if_any_file_rel_to_archive($zdm_archive_id) === false) {
                                                    echo '<p>' . esc_html__('If no file is assigned, then no download button is displayed in the frontend.', 'zdm') . '</p>';
                                                }

                                                ?>
                                                <table class="zdm-table-list">
                                                    <?php
                                                    for ($i = 0; $i < $zdm_db_files_rel_count; $i++) {

                                                        $zdm_file_data = ZDMCore::get_file_data($zdm_db_files_rel_array[$i]->id_file);
                                                    ?>

                                                        <tr>
                                                            <td>
                                                                <a href="?page=<?= ZDM__SLUG ?>-files&id=<?= htmlspecialchars($zdm_file_data->id) ?>"><?= htmlspecialchars($zdm_file_data->name) ?></a>
                                                            </td>
                                                            <td>
                                                                <a href="admin.php?page=<?= ZDM__SLUG ?>-ziparchive&id=<?= htmlspecialchars($zdm_archive_id) ?>&file_delete_id=<?= htmlspecialchars($zdm_db_files_rel_array[$i]->id) ?>&nonce=<?= wp_create_nonce('remove-file') ?>" class="button button-small button-secondary zdm-btn-danger-2-outline" title="<?= esc_html__('Remove file from archive', 'zdm') ?>">&nbsp;<span class="material-icons-round zdm-md-1">clear</span>&nbsp;</a>
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
                                                    <p><?= esc_html__('For more linked files activate', 'zdm') ?> <a href="<?= ZDM__PRO_URL ?>" target="_blank" title="code.urban-base.net"><?= ZDM__PRO ?> <span class="material-icons-round zdm-md-1">open_in_new</span></a>.</p>
                                                <?php
                                                } ?>
                                            </div>
                                        </td>
                                    </tr>
                            </tbody>
                        </table>
                    </div>
                </div>

                <input type="hidden" name="nonce" value="<?= wp_create_nonce('update-data') ?>">
                <input class="button-primary" type="submit" name="update" value="<?= esc_html__('Update', 'zdm') ?>">
                <input class="button-secondary" type="submit" name="delete" value="<?= esc_html__('Delete', 'zdm') ?>">
                </form>
            <?php
                //////////////////////////////////////////////////
                // Ende Tab: Archiv
                //////////////////////////////////////////////////
            } elseif ($zdm_active_tab == 'shortcodes') { // Tab: Shortcodes
            ?>

                <div class="postbox">
                    <div class="inside">

                        <h2><?= esc_html__('Shortcodes', 'zdm') ?></h2>
                        <hr>
                        <p><a href="https://code.urban-base.net/z-downloads/shortcodes?utm_source=zdm_backend" target="_blank" title="<?= ZDM__TITLE ?> <?= esc_html__('Shortcodes', 'zdm') ?>"><?= esc_html__('All shortcodes', 'zdm') ?> <span class="material-icons-round zdm-md-1">open_in_new</span></a> <?= esc_html__('overview with explanation and examples.', 'zdm') ?></p>

                        <table class="form-table">
                            <tbody>
                                <tr valign="top">
                                    <th scope="row"><?= esc_html__('Download button', 'zdm') ?></th>
                                    <td valign="middle">
                                        <input type="text" class="zdm-copy-to-clipboard zdm-copy-to-clipboard-detail" value="[zdownload zip=&quot;<?= htmlspecialchars($zdm_db_archive->id) ?>&quot;]" readonly title="<?= esc_html__('Copy the shortcode to the clipboard.', 'zdm') ?>">
                                        <p class="zdm-color-green" style="display: none;"><b><span class="material-icons-round zdm-md-1">check_circle_outline</span> <?= esc_html__('Shortcode copied', 'zdm') ?></b></p>
                                    </td>
                                </tr>
                                <tr valign="top">
                                    <th scope="row"><?= esc_html__('List files', 'zdm') ?></th>
                                    <td valign="middle">
                                        <input type="text" class="zdm-copy-to-clipboard zdm-copy-to-clipboard-detail" value="[zdownload_list zip=&quot;<?= htmlspecialchars($zdm_db_archive->id) ?>&quot;]" readonly title="<?= esc_html__('Copy the shortcode to the clipboard.', 'zdm') ?>">
                                        <p class="zdm-color-green" style="display: none;"><b><span class="material-icons-round zdm-md-1">check_circle_outline</span> <?= esc_html__('Shortcode copied', 'zdm') ?></b></p>
                                    </td>
                                </tr>
                                <tr valign="top">
                                    <th scope="row"><?= esc_html__('Download count', 'zdm') ?></th>
                                    <td valign="middle">
                                        <input type="text" class="zdm-copy-to-clipboard zdm-copy-to-clipboard-detail" value="[zdownload_meta zip=&quot;<?= htmlspecialchars($zdm_db_archive->id) ?>&quot; type=&quot;count&quot;]" readonly title="<?= esc_html__('Copy the shortcode to the clipboard.', 'zdm') ?>">
                                        <p class="zdm-color-green" style="display: none;"><b><span class="material-icons-round zdm-md-1">check_circle_outline</span> <?= esc_html__('Shortcode copied', 'zdm') ?></b></p>
                                    </td>
                                </tr>
                                <tr valign="top">
                                    <th scope="row"><?= esc_html__('File size', 'zdm') ?></th>
                                    <td valign="middle">
                                        <input type="text" class="zdm-copy-to-clipboard zdm-copy-to-clipboard-detail" value="[zdownload_meta zip=&quot;<?= htmlspecialchars($zdm_db_archive->id) ?>&quot; type=&quot;size&quot;]" readonly title="<?= esc_html__('Copy the shortcode to the clipboard.', 'zdm') ?>">
                                        <p class="zdm-color-green" style="display: none;"><b><span class="material-icons-round zdm-md-1">check_circle_outline</span> <?= esc_html__('Shortcode copied', 'zdm') ?></b></p>
                                    </td>
                                </tr>
                                <?php

                                if ($zdm_licence === 0) {
                                    $text_hash_md5 = esc_html__('Output MD5 hash value', 'zdm') . '<br><a href="' . ZDM__PRO_URL . '" target="_blank" title="code.urban-base.net">' . ZDM__PRO . ' ' . esc_html__('feature', 'zdm') . ' <span class="material-icons-round zdm-md-1">open_in_new</span></a>';
                                    $text_hash_sha1 = esc_html__('Output SHA1 hash value', 'zdm') . '<br><a href="' . ZDM__PRO_URL . '" target="_blank" title="code.urban-base.net">' . ZDM__PRO . ' ' . esc_html__('feature', 'zdm') . ' <span class="material-icons-round zdm-md-1">open_in_new</span></a>';
                                } else {
                                    $text_hash_md5 = esc_html__('Output MD5 hash value', 'zdm');
                                    $text_hash_sha1 = esc_html__('Output SHA1 hash value', 'zdm');
                                }
                                ?>
                                <tr valign="top">
                                    <th scope="row"><?= $text_hash_md5 ?></th>
                                    <td valign="middle">
                                        <input type="text" class="zdm-copy-to-clipboard zdm-copy-to-clipboard-detail" value="[zdownload_meta zip=&quot;<?= htmlspecialchars($zdm_db_archive->id) ?>&quot; type=&quot;hash-md5&quot;]" readonly title="<?= esc_html__('Copy the shortcode to the clipboard.', 'zdm') ?>">
                                        <p class="zdm-color-green" style="display: none;"><b><span class="material-icons-round zdm-md-1">check_circle_outline</span> <?= esc_html__('Shortcode copied', 'zdm') ?></b></p>
                                    </td>
                                </tr>
                                <tr valign="top">
                                    <th scope="row"><?= $text_hash_sha1 ?></th>
                                    <td valign="middle">
                                        <input type="text" class="zdm-copy-to-clipboard zdm-copy-to-clipboard-detail" value="[zdownload_meta zip=&quot;<?= htmlspecialchars($zdm_db_archive->id) ?>&quot; type=&quot;hash-sha1&quot;]" readonly title="<?= esc_html__('Copy the shortcode to the clipboard.', 'zdm') ?>">
                                        <p class="zdm-color-green" style="display: none;"><b><span class="material-icons-round zdm-md-1">check_circle_outline</span> <?= esc_html__('Shortcode copied', 'zdm') ?></b></p>
                                    </td>
                                </tr>
                            </tbody>
                        </table>
                    </div>
                </div>

            <?php
                //////////////////////////////////////////////////
                // Ende Tab: Shortcodes
                //////////////////////////////////////////////////
            } elseif ($zdm_active_tab == 'statistics') { // Tab: Statistik
                ////////////////////
                // Download statistik
                ////////////////////
                require_once(ZDM__PATH . '/lib/ZDMStat.php');
            ?>

                <div class="postbox-container zdm-postbox-col-sm-2">

                    <div class="postbox">
                        <div class="inside">
                            <h3><?= esc_html__('Download statistics', 'zdm') ?></h3>
                        </div>

                        <table class="wp-list-table widefat">
                            <tr valign="top">
                                <th scope="row">
                                    <b><?= esc_html__('Total', 'zdm') ?>:</b>
                                </th>
                                <td valign="middle">
                                    <?= ZDMCore::number_format($zdm_db_archive->count) ?>
                                </td>
                            </tr>
                            <tr valign="top">
                                <th scope="row"><b><?= esc_html__('Last 30 days', 'zdm') ?>:</b></th>
                                <td valign="middle">
                                    <?= ZDMCore::number_format(ZDMStat::get_downloads_count_time_for_single_stat('archive', $zdm_db_archive->id, 86400 * 30)) ?>
                                </td>
                            </tr>
                            <tr valign="top">
                                <th scope="row"><b><?= esc_html__('Last 7 days', 'zdm') ?>:</b></th>
                                <td valign="middle">
                                    <?= ZDMCore::number_format(ZDMStat::get_downloads_count_time_for_single_stat('archive', $zdm_db_archive->id, 86400 * 7)) ?>
                                </td>
                            </tr>
                            <tr valign="top">
                                <th scope="row"><b><?= esc_html__('Last 24 hours', 'zdm') ?>:</b></th>
                                <td valign="middle">
                                    <?= ZDMCore::number_format(ZDMStat::get_downloads_count_time_for_single_stat('archive', $zdm_db_archive->id, 86400)) ?>
                                </td>
                            </tr>
                        </table>
                    </div>

                </div>

                <div class="postbox-container zdm-postbox-col-sm-2">

                    <div class="postbox">
                        <div class="inside">
                            <h3><?= esc_html__('Latest', 'zdm') ?> <?= $zdm_options['stat-single-archive-last-limit'] ?> <?= esc_html__('downloads', 'zdm') ?></h3>
                            <form action="" method="post">
                                <input type="hidden" name="nonce" value="<?= wp_create_nonce('update-stat-single-archive-last-limit') ?>">
                                <input type="number" name="stat-single-archive-last-limit" size="5" min="1" max="500" value="<?= esc_attr($zdm_options['stat-single-archive-last-limit']) ?>" <?php if ($zdm_licence === 0) {
                                                                                                                                                                                                    echo ' disabled';
                                                                                                                                                                                                } ?>>
                                <input class="button-primary" type="submit" name="update_stat_single_archive_last_limit" value="<?= esc_html__('Update', 'zdm') ?>" <?php if ($zdm_licence === 0) {
                                                                                                                                                                        echo ' disabled';
                                                                                                                                                                    } ?>>
                                <?php
                                if ($zdm_licence === 0) {
                                ?>
                                    <br><a href="<?= ZDM__PRO_URL ?>" target="_blank" title="code.urban-base.net"><?= ZDM__PRO ?> <?= esc_html__('features', 'zdm') ?> <span class="material-icons-round zdm-md-1">open_in_new</span></a>
                                <?php
                                }
                                ?>
                                <div class="zdm-help-text">
                                    <?= esc_html__('Determine the number of recent downloads that is displayed. This setting is global and affects all archives.', 'zdm') ?>
                                </div>
                            </form>
                        </div>

                        <?php
                        $zdm_last_downloads = ZDMStat::get_last_downloads_for_single_stat('archive', $zdm_db_archive->id, $zdm_options['stat-single-archive-last-limit']);
                        $zdm_last_downloads_count = count($zdm_last_downloads);
                        if ($zdm_last_downloads_count != 0) {
                        ?>
                            <table class="wp-list-table widefat striped tags">
                                <thead>
                                    <tr>
                                        <th scope="col" width="60%"><b><?= esc_html__('Date and Time', 'zdm') ?></b></th>
                                        <th scope="col" width="40%"><b><?= esc_html__('Details', 'zdm') ?></b></th>
                                    </tr>
                                </thead>

                                <tbody>
                                    <?php
                                    for ($i = 0; $i < $zdm_last_downloads_count; $i++) {
                                    ?>
                                        <tr>
                                            <td><?= date("d.m.Y - h:i:s", $zdm_last_downloads[$i]->time_create) ?></td>
                                            <td><a href="?page=<?= ZDM__SLUG ?>-log&id=<?= $zdm_last_downloads[$i]->id ?>"><?= esc_html__('Show details', 'zdm') ?></a></td>
                                        </tr>
                                    <?php
                                    }
                                    ?>
                                </tbody>
                            </table>
                        <?php
                        } else {
                        ?>
                            <p style="text-align:center"><?= esc_html__('This file has not yet been downloaded.', 'zdm') ?></p>
                        <?php
                        }
                        ?>
                    </div>

                </div>

            <?php
                //////////////////////////////////////////////////
                // Ende Tab: Statistik
                //////////////////////////////////////////////////
            } elseif ($zdm_active_tab == 'help') { // Tab: Hilfe
            ?>

                <div class="zdm-box zdm-box-info">
                    <p><?= esc_html__('Here you will find beginner tips and information for advanced functions.', 'zdm') ?></p>
                </div>

                <div class="postbox">
                    <div class="inside">
                        <h3><?= esc_html__('Create ZIP archive', 'zdm') ?></h3>
                        <hr>
                        <p><?= esc_html__('To create a ZIP archive click on', 'zdm') ?> <?= ZDM__TITLE ?> <?= esc_html__('menu on', 'zdm') ?> "<a href="admin.php?page=<?= ZDM__SLUG ?>-add-archive"><?= esc_html__('Create archive', 'zdm') ?></a>".</p>
                        <p><?= esc_html__('Here you can enter a name, a ZIP name and other information about the archive.', 'zdm') ?></p>
                        <p><?= esc_html__('In order to add files to the ZIP archive you select in the lower area under "Link files" from your already uploaded files and click on "Save".', 'zdm') ?></p>
                    </div>
                </div>

                <div class="postbox">
                    <div class="inside">
                        <h3><?= esc_html__('Print download button for archive with shortcode', 'zdm') ?></h3>
                        <hr>
                        <p><?= esc_html__('To output an archive as a button on a page or post click on', 'zdm') ?> <?= ZDM__TITLE ?> <?= esc_html__('menu on', 'zdm') ?> "<a href="admin.php?page=<?= ZDM__SLUG ?>-ziparchive"><?= esc_html__('Archive', 'zdm') ?></a>".</p>
                        <p><?= esc_html__('Here you can see an overview of all archives you have created.', 'zdm') ?></p>
                        <p><?= esc_html__('The shortcode appears in the list and looks like this', 'zdm') ?>: <code>[zdownload zip="123"]</code></p>
                        <p><?= esc_html__('"123" is the unique ID of the respective archive.', 'zdm') ?></p>
                        <p><?= esc_html__('You can also click on the name to get more details about this archive, on the detail page you can see more shortcodes that you can use.', 'zdm') ?></p>
                    </div>
                </div>

                <div class="postbox">
                    <div class="inside">
                        <h3><?= esc_html__('Button color and styles', 'zdm') ?></h3>
                        <hr>
                        <p><?= esc_html__('To make the color or other button settings click on', 'zdm') ?> <?= ZDM__TITLE ?> <?= esc_html__('menu on', 'zdm') ?> "<a href="admin.php?page=<?= ZDM__SLUG ?>-settings#zdm-download-button"><?= esc_html__('Settings', 'zdm') ?></a>".</p>
                        <p><?= esc_html__('Here you can change the following in the area "Download-Button"', 'zdm') ?>:</p>
                        <p><?= esc_html__('The standard text, the style (color of the button), outline, round corners or an icon.', 'zdm') ?></p>
                        <p><?= esc_html__('All available colors can be found on the', 'zdm') ?> <?= ZDM__TITLE ?> <a href="https://code.urban-base.net/z-downloads/farben/?utm_source=zdm_backend" target="_blank" title="<?= esc_html__('Colors', 'zdm') ?>"><?= ZDM__TITLE ?> <?= esc_html__('website', 'zdm') ?> <span class="material-icons-round zdm-md-1">open_in_new</span></a></p>
                    </div>
                </div>

                <div class="postbox">
                    <div class="inside">
                        <h3><?= esc_html__('Output metadata', 'zdm') ?></h3>
                        <hr>
                        <p><?= esc_html__('You can not only output an archive as a button but also further information about this download.', 'zdm') ?></p>
                        <h4><?= esc_html__('Download count', 'zdm') ?></h4>
                        <p><code>[zdownload_meta zip="123" type="count"]</code></p>
                        <h4><?= esc_html__('File size', 'zdm') ?></h4>
                        <p><code>[zdownload_meta zip="123" type="size"]</code></p>
                        <h4><?= esc_html__('More shortcodes', 'zdm') ?></h4>
                        <p><?= esc_html__('More shortcode options for outputting advanced metadata can be found in the tab', 'zdm') ?> <a href="admin.php?page=<?= ZDM__SLUG ?>-help&tab=expert"><?= esc_html__('Expert', 'zdm') ?></a>
                            <?= esc_html__('or on the', 'zdm') ?> <a href="https://code.urban-base.net/z-downloads/shortcodes/?utm_source=zdm_backend" target="_blank" title="<?= esc_html__('Shortcodes', 'zdm') ?>"><?= ZDM__TITLE ?> <?= esc_html__('website', 'zdm') ?> <span class="material-icons-round zdm-md-1">open_in_new</span></a></p>
                    </div>
                </div>

                <div class="postbox">
                    <div class="inside">
                        <h3><?= esc_html__('Lists', 'zdm') ?></h3>
                        <hr>
                        <p><?= esc_html__('You can output the files from an archive as a list.', 'zdm') ?></p>
                        <h4><?= esc_html__('Quick output', 'zdm') ?></h4>
                        <p><?= esc_html__('Use this shortcode to display a list of the files in an archive. You define the standard styles in the', 'zdm') ?> <a href="admin.php?page=<?= ZDM__SLUG ?>-settings"><?= esc_html__('settings', 'zdm') ?></a>.</p>
                        <p><code>[zdownload_list zip="123"]</code></p>
                        <h3><?= esc_html__('Specific styles', 'zdm') ?></h3>
                        <p><?= esc_html__('These special details overwrite the default values. All styles listed below can also be combined with one another.', 'zdm') ?></p>
                        <h4><?= esc_html__('Style', 'zdm') ?></h4>
                        <p><?= esc_html__('Use the keyword "style" and "rows" for rows, "ul" for an unordered list or "ol" for an ordered list.', 'zdm') ?></p>
                        <p><code>[zdownload_list zip="123" style="ul"]</code></p>
                        <h4><?= esc_html__('Bold', 'zdm') ?></h4>
                        <p><?= esc_html__('Use the keyword', 'zdm') ?> <code>bold="on"</code> <?= esc_html__('to make the text of the list bold.', 'zdm') ?></p>
                        <p><code>[zdownload_list zip="123" bold="on"]</code></p>
                        <p><?= esc_html__('Use the keyword', 'zdm') ?> <code>bold="off"</code> <?= esc_html__('to make the text of the list normal.', 'zdm') ?></p>
                        <p><code>[zdownload_list zip="123" bold="off"]</code></p>
                        <h4><?= esc_html__('Links', 'zdm') ?></h4>
                        <p><?= esc_html__('Use the keyword', 'zdm') ?> <code>links="on"</code> <?= esc_html__('to output the text of the list elements as a link.', 'zdm') ?></p>
                        <p><code>[zdownload_list zip="123" links="on"]</code></p>
                        <p><?= esc_html__('Use the keyword', 'zdm') ?> <code>links="off"</code> <?= esc_html__('to output the text of the list elements as a normal text.', 'zdm') ?></p>
                        <p><code>[zdownload_list zip="123" links="off"]</code></p>
                    </div>
                </div>

                <div class="postbox">
                    <div class="inside">
                        <h3><?= esc_html__('Output hash value of ZIP file with shortcode', 'zdm') ?></h3>
                        <hr>
                        <h4><?= esc_html__('MD5', 'zdm') ?> <?= $zdm_premium_text ?></h4>
                        <p><?= esc_html__('You can output the MD5 hash value of a file or ZIP archive.', 'zdm') ?></p>
                        <p><code>[zdownload_meta zip="123" type="hash-md5"]</code></p>
                        <h4><?= esc_html__('SHA1', 'zdm') ?> <?= $zdm_premium_text ?></h4>
                        <p><?= esc_html__('You can output the SHA1 hash value of a file or a ZIP archive.', 'zdm') ?></p>
                        <p><code>[zdownload_meta zip="123" type="hash-sha1"]</code></p>
                    </div>
                </div>

                <div class="postbox">
                    <div class="inside">
                        <h3><?= esc_html__('Visibility', 'zdm') ?></h3>
                        <hr>
                        <p><?= esc_html__('The visibility setting of an archive determines whether a button or other information is displayed in the front end.', 'zdm') ?></p>
                        <p><?= esc_html__('If the archive is set to "Private", the archive can no longer be downloaded, even if someone calls the URL of the download button directly.', 'zdm') ?></p>
                    </div>
                </div>

                <div class="postbox">
                    <div class="inside">
                        <h3><?= esc_html__('Further help', 'zdm') ?></h3>
                        <hr>
                        <p><?= esc_html__('Further help and documentation for special functions can be found here', 'zdm') ?>: <a href="admin.php?page=<?= ZDM__SLUG ?>-help&tab=expert"><?= esc_html__('Help page - expert', 'zdm') ?></a></p>
                        <p><?= esc_html__('or on the', 'zdm') ?> <a href="https://code.urban-base.net/z-downloads/shortcodes/?utm_source=zdm_backend" target="_blank" title="<?= ZDM__TITLE ?> <?= esc_html__('website', 'zdm') ?>"><?= ZDM__TITLE ?> <?= esc_html__('website', 'zdm') ?> <span class="material-icons-round zdm-md-1">open_in_new</span></a></p>
                    </div>
                </div>

            <?php
            }
            //////////////////////////////////////////////////
            // Ende Tab: Hilfe
            //////////////////////////////////////////////////
            ?>

        </div>

    <?php
    } elseif ($zdm_status === 2) {
    ?>
        <div class="notice notice-success">
            <p><span class="material-icons-round zdm-md-1 zdm-color-green">check_circle_outline</span> <?= esc_html__('Archive deleted!', 'zdm') ?></p>
            <p><a href="admin.php?page=<?= ZDM__SLUG ?>-ziparchive" class="button-primary"><?= esc_html__('Back to overview', 'zdm') ?></a></p>
        </div>
    <?php
    } else {

        $zdm_db_archives = $wpdb->get_results(
            "
            SELECT id, name, zip_name, count, archive_cache_path, file_size, status, time_create 
            FROM $zdm_tablename_archives 
            ORDER BY id DESC
            "
        );

        $zdm_db_archives_count = count($zdm_db_archives);

    ?>

        <div class="wrap">

            <h1 class="wp-heading-inline"><?= esc_html__('ZIP archives', 'zdm') ?></h1>
            <hr class="wp-header-end">
            <p><a href="admin.php?page=<?= ZDM__SLUG ?>-add-archive" class="page-title-action"><?= esc_html__('Create archive', 'zdm') ?></a></p>

            <?php if ($zdm_db_archives_count > 0) { ?>

                <div class="col-wrap">
                    <table class="wp-list-table widefat striped tags">
                        <thead>
                            <tr>
                                <th scope="col"><b><?= esc_html__('Name', 'zdm') ?></b></th>
                                <th scope="col"><b><?= esc_html__('Shortcode', 'zdm') ?></b></th>
                                <th scope="col"><span class="material-icons-round zdm-md-1" title="<?= esc_html__('Download count', 'zdm') ?>">leaderboard</span></th>
                                <th scope="col"><b><?= esc_html__('Files', 'zdm') ?></b></th>
                                <th scope="col"><b><?= esc_html__('File size', 'zdm') ?></b></th>
                                <th scope="col"><b><?= esc_html__('Created', 'zdm') ?></b></th>
                                <th scope="col">
                                    <div align="center"><b><?= esc_html__('Cache', 'zdm') ?></b></div>
                                </th>
                                <th scope="col" title="<?= esc_html__('Visibility', 'zdm') ?>">
                                    <div align="center"><b><span class="material-icons-round zdm-md-1">visibility</span></b></div>
                                </th>
                                <th scope="col" width="2%">
                                    <div align="center"><span class="material-icons-round zdm-md-1-5" title="<?= esc_html__('Delete archive', 'zdm') ?>">delete</span></div>
                                </th>
                            </tr>
                        </thead>

                        <tbody>
                            <?php

                            for ($i = 0; $i < $zdm_db_archives_count; $i++) {

                                $zdm_dm_archive_id = htmlspecialchars($zdm_db_archives[$i]->id);
                                $zdm_db_files_rel_array = $wpdb->get_results(
                                    "
                                SELECT id, id_file, id_archive 
                                FROM $zdm_tablename_files_rel 
                                WHERE id_archive = '$zdm_dm_archive_id' 
                                AND file_deleted = '0'
                                "
                                );

                                $zdm_db_files_rel_count = count($zdm_db_files_rel_array);

                            ?>
                                <tr>
                                    <td>
                                        <?php
                                        if ($zdm_db_files_rel_count != 0) {

                                            if (ZDMCore::check_if_archive_cache_ok($zdm_db_archives[$i]->id)) {
                                        ?> <a href="<?= ZDM__DOWNLOADS_CACHE_PATH_URL . '/' . htmlspecialchars($zdm_db_archives[$i]->archive_cache_path) . '/' . htmlspecialchars($zdm_db_archives[$i]->zip_name) ?>.zip" title="<?= esc_html__('Download', 'zdm') ?>" target="_blank" download><span class="material-icons-round zdm-md-1-5">cloud_download</span></a> | <?php
                                                                                                                                                                                                                                                                                                                                                                        } else {
                                                                                                                                                                                                                                                                                                                                                                            ?> <span class="material-icons-round zdm-md-1-5" title="<?= esc_html__('Update the cache of the file to download it', 'zdm') ?>">cloud_download</span></a> | <?php
                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                        }
                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                    } else {
                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                            ?> <span class="material-icons-round zdm-md-1-5" title="<?= esc_html__('No files are linked to the archive.', 'zdm') ?>">warning_amber</span> | <?php
                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                        }
                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                            ?>
                                        <b><a href="?page=<?= ZDM__SLUG ?>-ziparchive&id=<?= htmlspecialchars($zdm_db_archives[$i]->id) ?>"><?= htmlspecialchars($zdm_db_archives[$i]->name) ?></a></b>
                                    </td>
                                    <td>
                                        <input type="text" class="zdm-copy-to-clipboard zdm-copy-to-clipboard-list" value="[zdownload zip=&quot;<?= htmlspecialchars($zdm_db_archives[$i]->id) ?>&quot;]" readonly title="<?= esc_html__('Copy the shortcode to the clipboard.', 'zdm') ?>">
                                        <p class="zdm-color-green" style="display: none;"><b><span class="material-icons-round zdm-md-1">check_circle_outline</span> <?= esc_html__('Shortcode copied', 'zdm') ?></b></p>
                                    </td>
                                    <td>
                                        <a href="?page=<?= ZDM__SLUG ?>-ziparchive&id=<?= htmlspecialchars($zdm_db_archives[$i]->id) ?>&tab=statistics"><?= ZDMCore::number_format($zdm_db_archives[$i]->count) ?></a>
                                    </td>
                                    <td>
                                        <?php
                                        if ($zdm_db_files_rel_count != 0) {
                                            echo ZDMCore::number_format($zdm_db_files_rel_count);
                                        } else {
                                        ?> <span class="material-icons-round zdm-md-1-5 zdm-color-yellow" title="<?= esc_html__('No files are linked to the archive.', 'zdm') ?>">warning_amber</span> <?php
                                                                                                                                                                                                    }
                                                                                                                                                                                                        ?>
                                    </td>
                                    <td>
                                        <?php
                                        if (ZDMCore::check_if_archive_cache_ok($zdm_db_archives[$i]->id)) {
                                            echo $zdm_db_archives[$i]->file_size;
                                        }
                                        ?>
                                    </td>
                                    <td>
                                        <?= date("d.m.Y", $zdm_db_archives[$i]->time_create) ?>
                                    </td>
                                    <td align="center">
                                        <?php
                                        if ($zdm_db_files_rel_count != 0) {

                                            if (ZDMCore::check_if_archive_cache_ok($zdm_db_archives[$i]->id)) {
                                        ?> <span class="material-icons-round zdm-md-1-5 zdm-color-green">check_circle_outline</span> <?php
                                                                                                                                    } else {
                                                                                                                                        ?> <a href="admin.php?page=<?= ZDM__SLUG ?>-ziparchive&archive-cache=<?= htmlspecialchars($zdm_db_archives[$i]->id) ?>&nonce=<?= wp_create_nonce('update-cache') ?>" class="button button-primary" title="<?= esc_html__('Update cache', 'zdm') ?>"><span class="material-icons-round zdm-md-1-5">refresh</span></a> <?php
                                                                                                                                                                                                                                                                                                                                                                                                                                                            }
                                                                                                                                                                                                                                                                                                                                                                                                                                                        } else {
                                                                                                                                                                                                                                                                                                                                                                                                                                                                ?> <span class="material-icons-round zdm-md-1-5 zdm-color-yellow" title="<?= esc_html__('No files are linked to the archive.', 'zdm') ?>">warning_amber</span> <?php
                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                            }
                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                ?>
                                    </td>
                                    <td>
                                        <?php
                                        // Dateistatus Sichtbarkeit
                                        if ($zdm_db_archives[$i]->status == 'public') {
                                            $zdm_archive_status = '<span class="material-icons-round zdm-md-1-5 zdm-color-green" title="' . esc_html__('Visibility: public', 'zdm') . '">visibility</span>';
                                        } else {
                                            $zdm_archive_status = '<span class="material-icons-round zdm-md-1-5" title="' . esc_html__('Visibility: private', 'zdm') . '">visibility_off</span>';
                                        }
                                        ?>
                                        <div align="center"><?= $zdm_archive_status ?></div>
                                    </td>
                                    <td>
                                        <a href="admin.php?page=<?= ZDM__SLUG ?>-ziparchive&id=<?= htmlspecialchars($zdm_db_archives[$i]->id) ?>&delete=true&nonce=<?= wp_create_nonce('delete-archive') ?>" class="button button-secondary zdm-btn-danger-2-outline" title="<?= esc_html__('Delete archive', 'zdm') ?>"><span class="material-icons-round zdm-md-1-5">delete</span></a>
                                    </td>
                                </tr>
                            <?php
                            }

                            ?>
                        </tbody>

                        <tfoot>
                            <tr>
                                <th scope="col"><b><?= esc_html__('Name', 'zdm') ?></b></th>
                                <th scope="col"><b><?= esc_html__('Shortcode', 'zdm') ?></b></th>
                                <th scope="col"><span class="material-icons-round zdm-md-1" title="<?= esc_html__('Download count', 'zdm') ?>">leaderboard</span></th>
                                <th scope="col"><b><?= esc_html__('Files', 'zdm') ?></b></th>
                                <th scope="col"><b><?= esc_html__('File size', 'zdm') ?></b></th>
                                <th scope="col"><b><?= esc_html__('Created', 'zdm') ?></b></th>
                                <th scope="col">
                                    <div align="center"><b><?= esc_html__('Cache', 'zdm') ?></b></div>
                                </th>
                                <th scope="col" title="<?= esc_html__('Visibility', 'zdm') ?>">
                                    <div align="center"><b><span class="material-icons-round zdm-md-1">visibility</span></b></div>
                                </th>
                                <th scope="col">
                                    <div align="center"><span class="material-icons-round zdm-md-1-5" title="<?= esc_html__('Delete archive', 'zdm') ?>">delete</span></div>
                                </th>
                            </tr>
                        </tfoot>

                    </table>
                </div>

            <?php } ?>

            <br>

            <?php require_once(plugin_dir_path(__FILE__) . '../inc/postbox_info_archive.php'); ?>

            <br>
            <a class="alignright button" href="javascript:void(0);" onclick="window.scrollTo(0,0);" style="margin:3px 0 0 30px;"><?= esc_html__('To top', 'zdm') ?></a>

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