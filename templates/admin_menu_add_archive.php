<?php
// Abort by direct access
if (!defined('ABSPATH'))
    die;

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
    $zdm_tablename_files = $wpdb->prefix . "zdm_files";

    $zdm_db_files = $wpdb->get_results(
        "
        SELECT id, name 
        FROM $zdm_tablename_files
        "
    );

    $zdm_db_files_count = count($zdm_db_files);

    // Speichere Dateien von Datenbank in Auswahlmenü
    $zdm_option_output = '';
    for ($i = 0; $i < $zdm_db_files_count; $i++) {
        $zdm_option_output .= '<option value="' . $zdm_db_files[$i]->id . '">' . $zdm_db_files[$i]->name . '</option>';
    }

    if (isset($_POST['submit']) && wp_verify_nonce($_POST['nonce'], 'daten-speichern')) {

        if ($_POST['name'] != '') {

            $zdm_post_name = sanitize_text_field($_POST['name']);
            $zdm_post_description = sanitize_textarea_field($_POST['description']);
            $zdm_post_count = sanitize_file_name($_POST['count']);
            $zdm_post_button_text = sanitize_text_field($_POST['button-text']);

            // id_temp erstellen
            $zdm_archive_id_temp = md5($zdm_time . $zdm_post_name);
            // ZIP-Name
            if ($_POST['zip-name'] != '')
                $zdm_zip_name = str_replace(' ', '-', trim(sanitize_file_name($_POST['zip-name'])));
            else
                $zdm_zip_name = str_replace(' ', '-', trim($zdm_post_name));

            $zdm_tablename_archives = $wpdb->prefix . "zdm_archives";

            // Neuen Datenbankeintrag erstellen
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

            // Hole ID von gerade gespeichertem Archiv
            $zdm_db_archive = $wpdb->get_results(
                "
                SELECT id 
                FROM $zdm_tablename_archives 
                WHERE id_temp = '$zdm_archive_id_temp'
                "
            );

            // Definiere Anzahl für Dateiverknüpfungen
            $zdm_files_count = 5;
            if ($zdm_licence === 1)
                $zdm_files_count = 20;

            $zdm_tablename_files_rel = $wpdb->prefix . "zdm_files_rel";

            // Entferne doppelte Auswahl
            $_POST['files'] = array_unique($_POST['files']);

            // Erstelle files_rel Eintrag
            for ($i = 0; $i < $zdm_files_count; $i++) {

                if (isset($_POST['files'][$i]) && $_POST['files'][$i] != '') {

                    // Save data in database files_rel
                    $wpdb->insert(
                        $zdm_tablename_files_rel,
                        array(
                            'id_file'       => sanitize_file_name($_POST['files'][$i]),
                            'id_archive'    => $zdm_db_archive[0]->id,
                            'file_updated'  => 1,
                            'time_create'   => $zdm_time
                        )
                    );

                    ZDMCore::log('link file', 'archive ID: ' . $zdm_db_archive[0]->id . ', file ID: ' . sanitize_file_name($_POST['files'][$i]));
                }
            }

            ZDMCore::log('add archive', 'ID: ' . $zdm_db_archive[0]->id . ', name: ' . $zdm_post_name);

            $zdm_status = 1;
        } else {
            $zdm_note = esc_html__('Name and ZIP file name cannot be empty..', 'zdm');
        }
    }

?>

    <div class="wrap">

        <?php
        if ($zdm_note != '') {
            echo '<div class="notice notice-warning">';
            echo '<br><b>' . $zdm_note . '</b><br><br>';
            echo '</div>';
        }
        ?>

        <h1 class="wp-heading-inline"><?= esc_html__('Create new ZIP archive', 'zdm') ?></h1>
        <hr class="wp-header-end">
        <p><a class="button-secondary" href="admin.php?page=<?= ZDM__SLUG ?>-ziparchive"><?= esc_html__('Back to overview', 'zdm') ?></a></p>

        <?php if ($zdm_status === 1) { ?>

            <div class="notice notice-success">
                <p><span class="material-icons-round zdm-md-1 zdm-color-green">check_circle_outline</span> <?= esc_html__('Archive successfully created!', 'zdm') ?></p>
                <p><a href="admin.php?page=<?= ZDM__SLUG ?>-add-archive" class="button-primary"><?= esc_html__('Create a new archive', 'zdm') ?></a></p>
            </div>

        <?php } else { ?>

            <form action="" method="post">
                <div class="postbox">
                    <div class="inside">
                        <table class="form-table">
                            <tbody>
                                <tr valign="top">
                                    <th scope="row"><?= esc_html__('Name', 'zdm') ?>:</th>
                                    <td valign="middle">
                                        <input type="text" name="name" id="archive-name" size="50%" value="<?= @htmlspecialchars($_POST['name']) ?>" spellcheck="true" autocomplete="off" placeholder="" required>
                                        <div class="zdm-help-text"><?= esc_html__('This name is displayed in the archive list and serves as a guide.', 'zdm') ?></div>
                                    </td>
                                </tr>
                                <tr valign="top">
                                    <th scope="row"><?= esc_html__('ZIP file name', 'zdm') ?>:</th>
                                    <td valign="middle">
                                        <input type="text" name="zip-name" id="zip-name" size="50%" value="<?= @htmlspecialchars($_POST['zip-name']) ?>" spellcheck="true" autocomplete="off" placeholder="">
                                        <div class="zdm-help-text"><?= esc_html__('Name of the ZIP file the visitor downloads.', 'zdm') ?></div>
                                        <div class="zdm-help-text"><?= esc_html__('If you leave this field blank, the name will be used.', 'zdm') ?></div>
                                        <div class="zdm-help-text"><?= esc_html__('For maximum compatibility, use hyphens or underscores instead of spaces.', 'zdm') ?></div>
                                        <div class="zdm-help-text"><b><?= esc_html__('Example', 'zdm') ?>:</b> <code><?= esc_html__('my-new-download', 'zdm') ?></code> <?= esc_html__('or', 'zdm') ?> <code><?= esc_html__('my_new_download', 'zdm') ?></code></div>
                                    </td>
                                </tr>
                                <script>
                                    // Prüfung auf Eingabe
                                    document.getElementById('archive-name').addEventListener("keyup", function(evt) {
                                        zdmZipName = document.getElementById("archive-name")
                                            .value;
                                        // Leerzeichen durch "_" ersetzen
                                        zdmZipName = zdmZipName.toLowerCase();
                                        zdmZipName = zdmZipName.replace(/\u00fc/g, "ue");
                                        zdmZipName = zdmZipName.replace(/\u00e4/g, "ae");
                                        zdmZipName = zdmZipName.replace(/\u00f6/g, "oe");
                                        zdmZipName = zdmZipName.replace(/\u00df/g, "ss");
                                        zdmZipName = zdmZipName.replace(/ /g, "_");
                                        zdmZipName = zdmZipName.replace(/\//g, "_");
                                        zdmZipName = zdmZipName.replace(/\(/g, "_");
                                        zdmZipName = zdmZipName.replace(/\)/g, "_");
                                        zdmZipName = zdmZipName.replace(/#/g, "");
                                        zdmZipName = zdmZipName.replace(/\?/g, "");
                                        zdmZipName = zdmZipName.replace(/!/g, "");
                                        document.getElementById("zip-name").value = zdmZipName;
                                    }, false);
                                    // Prüfung auf Eingabe
                                    document.getElementById('zip-name').addEventListener("keyup", function(evt) {
                                        zdmZipName = document.getElementById("zip-name")
                                            .value;
                                        // Leerzeichen durch "_" ersetzen
                                        zdmZipName = zdmZipName.toLowerCase();
                                        zdmZipName = zdmZipName.replace(/\u00fc/g, "ue");
                                        zdmZipName = zdmZipName.replace(/\u00e4/g, "ae");
                                        zdmZipName = zdmZipName.replace(/\u00f6/g, "oe");
                                        zdmZipName = zdmZipName.replace(/\u00df/g, "ss");
                                        zdmZipName = zdmZipName.replace(/ /g, "_");
                                        zdmZipName = zdmZipName.replace(/\//g, "_");
                                        zdmZipName = zdmZipName.replace(/\(/g, "_");
                                        zdmZipName = zdmZipName.replace(/\)/g, "_");
                                        zdmZipName = zdmZipName.replace(/#/g, "");
                                        zdmZipName = zdmZipName.replace(/\?/g, "");
                                        zdmZipName = zdmZipName.replace(/!/g, "");
                                        document.getElementById("zip-name").value = zdmZipName;
                                    }, false);
                                </script>
                                <tr valign="top">
                                    <th scope="row"><?= esc_html__('Download button text', 'zdm') ?>:</th>
                                    <td valign="middle">
                                        <input type="text" name="button-text" size="50%" value="<?= esc_attr($zdm_options['download-btn-text']) ?>" spellcheck="true" autocomplete="off" placeholder="">
                                        <div class="zdm-help-text"><?= esc_html__('Default text for the download button is', 'zdm') ?> "<?= esc_attr($zdm_options['download-btn-text']) ?>", <?= esc_html__('this text can be found in the', 'zdm') ?> <a href="admin.php?page=<?= ZDM__SLUG ?>-settings"><?= esc_html__('settings', 'zdm') ?></a>.</div>
                                    </td>
                                </tr>
                                <tr valign="top">
                                    <th scope="row"><?= esc_html__('Description', 'zdm') ?>:</th>
                                    <td valign="middle">
                                        <textarea name="description" id="" cols="100%" rows="5"><?= @htmlspecialchars($_POST['description']) ?></textarea>
                                    </td>
                                </tr>
                                <tr valign="top">
                                    <th scope="row"><?= esc_html__('Count', 'zdm') ?>:</th>
                                    <td valign="middle">
                                        <input type="text" name="count" size="10%" value="0" spellcheck="true" autocomplete="off" placeholder="">
                                        <div class="zdm-help-text"><?= esc_html__('Number of previous downloads.', 'zdm') ?></div>
                                    </td>
                                </tr>
                                <tr valign="top">
                                    <th scope="row"><?= esc_html__('Link files', 'zdm') ?>:</th>
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
                                                <p><?= esc_html__('For more file shortcuts activate', 'zdm') ?> <a href="<?= ZDM__PRO_URL ?>" target="_blank" title="code.urban-base.net"><?= ZDM__PRO ?></a>.</p>
                                            <?php
                                            } ?>
                                        </div>
                                    </td>
                                </tr>
                            </tbody>
                        </table>
                    </div>
                </div>

                <?php require_once(plugin_dir_path(__FILE__) . '../inc/postbox_info_archive.php'); ?>

                <input type="hidden" name="nonce" value="<?= wp_create_nonce('daten-speichern') ?>">
                <input class="button-primary" type="submit" name="submit" value="<?= esc_html__('Save', 'zdm') ?>">
                <a class="button-secondary" href="admin.php?page=<?= ZDM__SLUG ?>-add-archive"><?= esc_html__('Cancel', 'zdm') ?></a>
            </form>

        <?php } ?>

    </div>

<?php }
