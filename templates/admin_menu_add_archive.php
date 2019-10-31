<?php

// Abbruch bei direktem Zugriff
if( !defined( 'ABSPATH' ) ) {
    die;
}

if (current_user_can(ZDM__STANDARD_USER_ROLE)) {

    $zdm_status = 0;
    $zdm_licence = 0;
    $zdm_note = '';
    $zdm_options = get_option('zdm_options');
    $zdm_time = time();

    if (ZDMCore::licence()) {
        $zdm_licence_array = ZDMCore::licence_array();
        $zdm_licence = 1;
    }

    global $wpdb;
    // DB Tabellenname
    $zdm_tablename_files = $wpdb->prefix . "zdm_files";

    // Daten aus DB holen
    $zdm_db_files = $wpdb->get_results( 
        "
        SELECT id, name 
        FROM $zdm_tablename_files
        "
    );

    // Dateien aus DB in Auswahlmenü speichern
    $zdm_option_output = '';
    for ($i = 0; $i < count($zdm_db_files); $i++) {

        $zdm_option_output .= '<option value="' . $zdm_db_files[$i]->id . '">' . $zdm_db_files[$i]->name . '</option>';
    }

    if (isset($_POST['submit']) && wp_verify_nonce($_POST['nonce'], 'daten-speichern')) {

        // Check ob Felder ausgefüllt sind
        if ($_POST['name'] != '' && $_POST['zip-name'] != '') {

            $zdm_post_name = sanitize_text_field($_POST['name']);
            $zdm_post_description = sanitize_textarea_field($_POST['description']);
            $zdm_post_count = sanitize_file_name($_POST['count']);
            $zdm_post_button_text = sanitize_text_field($_POST['button-text']);

            // id_temp erstellen
            $zdm_archive_id_temp = md5($zdm_time . $zdm_post_name);
            // ZIP-Name
            $zdm_zip_name = str_replace(' ', '-', trim(sanitize_file_name($_POST['zip-name'])));

            // DB Tabellenname
            $zdm_tablename_archives = $wpdb->prefix . "zdm_archives";

            // Daten in DB archives speichern
            $wpdb->insert(
                $zdm_tablename_archives, 
                array(
                    'id_temp'       => $zdm_archive_id_temp,
                    'name'          => $zdm_post_name,
                    'zip_name'      => $zdm_zip_name,
                    'description'   => $zdm_post_description,
                    'count'         => $zdm_post_count,
                    'button_text'   => $zdm_post_button_text,
                    'time_create'   => $zdm_time
                )
            );

            // id aus gerade gespeichertem Archiv holen
            $zdm_db_archive = $wpdb->get_results( 
                "
                SELECT id 
                FROM $zdm_tablename_archives 
                WHERE id_temp = '$zdm_archive_id_temp'
                "
            );

            // Anzahl für Schleifendurchlauf definieren
            $files_count = 10;
            if ($zdm_licence === 1) {
                $files_count = 20;
            }

            // DB Tabellenname
            $zdm_tablename_files_rel = $wpdb->prefix . "zdm_files_rel";

            // doppelte Auswahl entfernen
            $_POST['files'] = array_unique($_POST['files']);

            // files_rel Einträge erstellen
            for ($i = 0; $i <= $files_count; $i++) {

                if ($_POST['files'][$i] != '') {

                    // Daten in DB files_rel speichern
                    $wpdb->insert(
                        $zdm_tablename_files_rel, 
                        array(
                            'id_file'       => sanitize_file_name($_POST['files'][$i]),
                            'id_archive'    => $zdm_db_archive[0]->id,
                            'file_updated'  => 1,
                            'time_create'   => $zdm_time
                        )
                    );
                }
            }

            // Log
            ZDMCore::log('add archive', $zdm_db_archive[0]->id);

            $zdm_status = 1;

        } else {
            $zdm_note = 'Name und ZIP-Datei Name darf nicht leer sein.';
        }
    }

    ?>

    <div class="wrap">

        <?php
        // Notiz anzeigen
        if ($zdm_note != '') {
            echo '<div class="notice notice-warning">';
            echo '<br><b>' . $zdm_note . '</b><br><br>';
            echo '</div>';
        }
        ?>

        <h1 class="wp-heading-inline"><?=esc_html__('Neues ZIP-Archiv erstellen', 'zdm')?></h1>
        <hr class="wp-header-end">

        <p><a class="button-secondary" href="admin.php?page=<?=ZDM__SLUG?>-ziparchive"><?=esc_html__('zurück zur Übersicht', 'zdm')?></a></p>

    <?php if ($zdm_status === 1) { ?>

        <div class="notice notice-success">
        <p><ion-icon name="checkmark-circle" class="zdm-color-green"></ion-icon> <?=esc_html__('Archiv erfolgreich erstellt!', 'zdm')?></p>
        <p><a href="admin.php?page=<?=ZDM__SLUG?>-add-archive" class="button-primary"><?=esc_html__('Neues Archiv erstellen', 'zdm')?></a></p>
        </div>

    <?php } else { ?>

        <form action="" method="post">
            <div class="postbox">
                <div class="inside">
                    <table class="form-table">
                        <tbody>
                            <tr valign="top">
                                <th scope="row"><?=esc_html__('Name', 'zdm')?>:</th>
                                <td valign="middle">
                                    <input type="text" name="name" size="50%" value="" spellcheck="true" autocomplete="off" placeholder="">
                                </td>
                            </tr>
                            <tr valign="top">
                                <th scope="row"><?=esc_html__('ZIP-Datei Name:', 'zdm')?></th>
                                <td valign="middle">
                                    <input type="text" name="zip-name" size="50%" value="" spellcheck="true" autocomplete="off" placeholder="">
                                    <div class="zdm-help-text"><?=esc_html__('Name für die ZIP-Datei die der Besucher herunterladet.', 'zdm')?></div>
                                    <div class="zdm-help-text"><?=esc_html__('Für maximale kompatibilität verwende Bindestriche oder Unterstriche anstatt Leerzeichen.', 'zdm')?></div>
                                    <div class="zdm-help-text"><b><?=esc_html__('Beispiel:', 'zdm')?></b> <code><?=esc_html__('mein-neuer-download', 'zdm')?></code> <?=esc_html__('oder', 'zdm')?> <code><?=esc_html__('mein_neuer_download', 'zdm')?></code></div>
                                </td>
                            </tr>
                            <tr valign="top">
                                <th scope="row"><?=esc_html__('Download-Button Text:', 'zdm')?></th>
                                <td valign="middle">
                                    <input type="text" name="button-text" size="50%" value="<?=esc_attr($zdm_options['download-btn-text'])?>" spellcheck="true" autocomplete="off" placeholder="">
                                    <div class="zdm-help-text"><?=esc_html__('Standardtext für den Download-Button ist "', 'zdm')?><?=esc_attr($zdm_options['download-btn-text'])?>", <?=esc_html__('dieser Text kann in den', 'zdm')?> <a href="admin.php?page=<?=ZDM__SLUG?>-settings"><?=esc_html__('Einstellungen', 'zdm')?></a> <?=esc_html__('geändert werden.', 'zdm')?></div>
                                </td>
                            </tr>
                            <tr valign="top">
                                <th scope="row"><?=esc_html__('Beschreibung:', 'zdm')?></th>
                                <td valign="middle">
                                    <textarea name="description" id="" cols="100%" rows="5"></textarea>
                                </td>
                            </tr>
                            <tr valign="top">
                                <th scope="row"><?=esc_html__('Count:', 'zdm')?></th>
                                <td valign="middle">
                                    <input type="text" name="count" size="10%" value="0" spellcheck="true" autocomplete="off" placeholder=""> 
                                    <div class="zdm-help-text"><?=esc_html__('Anzahl an bisherigen Downloads.', 'zdm')?></div>
                                </td>
                            </tr>
                            <tr valign="top">
                                <th scope="row"><?=esc_html__('Dateien verknüpfen', 'zdm')?>:</th>
                                <td valign="middle">
                                    <div class="zdm-select-50">
                                    <?php for ($i = 1; $i <= 5; $i++) { ?>
                                        <select name="files[]">
                                            <option value=""></option>
                                            <?php echo $zdm_option_output; ?>
                                        </select><br>
                                    <?php }
                                    if ($zdm_licence === 1) {
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

            <?php require_once (plugin_dir_path(__FILE__) . '../inc/postbox_info_archive.php'); ?>

            <input type="hidden" name="nonce" value="<?=wp_create_nonce('daten-speichern')?>">
            <input class="button-primary" type="submit" name="submit" value="<?=esc_html__('Speichern', 'zdm')?>">
            <a class="button-secondary" href="admin.php?page=<?=ZDM__SLUG?>-add-archive"><?=esc_html__('Abbrechen', 'zdm')?></a>
        </form>

    <?php } ?>

    </div>

<?php }