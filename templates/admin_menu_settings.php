<?php
// Abort by direct access
if (!defined('ABSPATH'))
    die;

if (current_user_can(ZDM__STANDARD_USER_ROLE)) {

    $zdm_options = get_option('zdm_options');
    $zdm_status = 1;
    $zdm_update = '';
    $zdm_note = '';
    $zdm_error = '';

    ////////////////////
    // Lizenzschlüssel aktualisieren
    ////////////////////
    if (isset($_POST['licence_submit']) && wp_verify_nonce($_POST['nonce'], 'update-license')) {

        // Lizenz
        // Lizenzschlüssel aktualisieren
        if (ZDMCore::licence_array(trim(sanitize_text_field($_POST['licence-key'])))['success'] === true) {

            $licence_array = $this->licence_array(trim(sanitize_text_field($_POST['licence-key'])));

            $zdm_options['licence-key'] = trim(sanitize_text_field($_POST['licence-key']));
            $zdm_options['licence-email'] = $licence_array['purchase']['email'];
            $zdm_options['licence-purchase'] = $licence_array['purchase']['created_at'];
            $zdm_options['licence-product-name'] = $licence_array['purchase']['product_name'];
            $zdm_options['licence-time'] = time();

            if (add_option('zdm_options', $zdm_options) === FALSE) {
                update_option('zdm_options', $zdm_options);
                $zdm_update = 1;
            }

            $zdm_options = get_option('zdm_options');
        } else {
            // Lizenzschlüssel entfernen

            $zdm_options['licence-key'] = '';

            if (add_option('zdm_options', $zdm_options) === FALSE) {
                update_option('zdm_options', $zdm_options);
                $zdm_update = 1;
            }
        }

        ZDMCore::licence();

        $zdm_options = get_option('zdm_options');

        ZDMCore::log('update licence');
    }

    // Lizenzschlüssel entfernen
    if (isset($_GET['licence_delete']) && wp_verify_nonce($_GET['nonce'], 'licence-delete')) {

        $zdm_options['licence-key'] = '';

        if (add_option('zdm_options', $zdm_options) === FALSE) {
            update_option('zdm_options', $zdm_options);
            $zdm_update = 1;
        }

        ZDMCore::log('delete licence');

        if (headers_sent()) {
            $zdm_status = 2;
        } else {
            // Einstellungsseite neu laden
            $zdm_settings_url = 'admin.php?page=' . ZDM__SLUG . '-settings';
            wp_redirect($zdm_settings_url);
            exit;
        }
    }

    if (ZDMCore::licence()) {
        $zdm_licence = 1;
    } else {
        $zdm_licence = 0;
    }

    ////////////////////
    // Daten aktualisieren
    ////////////////////
    if (isset($_POST['submit']) && wp_verify_nonce($_POST['nonce'], 'einstellungen-speichern')) {

        // Download-Button

        // Download Button Text
        $zdm_options['download-btn-text'] = isset($_POST['download-btn-text']) ? trim(sanitize_text_field($_POST['download-btn-text'])) : '';

        // Download Button Style
        $zdm_options['download-btn-style'] = isset($_POST['download-btn-style']) ? trim(sanitize_text_field($_POST['download-btn-style'])) : '';

        // Download Button Outline
        $zdm_options['download-btn-outline'] = isset($_POST['download-btn-outline']) ? trim(sanitize_text_field($_POST['download-btn-outline'])) : '';

        // Download Button Runde Ecken
        $zdm_options['download-btn-border-radius'] = isset($_POST['download-btn-border-radius']) ? trim(sanitize_text_field($_POST['download-btn-border-radius'])) : '';

        // Download Button Icon
        $zdm_options['download-btn-icon'] = isset($_POST['download-btn-icon']) ? trim(sanitize_text_field($_POST['download-btn-icon'])) : '';

        // Download Button Icon Position
        $zdm_options['download-btn-icon-position'] = isset($_POST['download-btn-icon-position']) ? trim(sanitize_text_field($_POST['download-btn-icon-position'])) : '';

        // Download Button Nur Icon
        $zdm_options['download-btn-icon-only'] = isset($_POST['download-btn-icon-only']) && trim(sanitize_text_field($_POST['download-btn-icon-only'])) == 'on' ? 'on' : '';

        // Listen

        // Listenstil
        $zdm_options['list-style'] = isset($_POST['list-style']) ? trim(sanitize_text_field($_POST['list-style'])) : '';

        // Fetter Text
        $zdm_options['list-bold'] = isset($_POST['list-bold']) && trim(sanitize_text_field($_POST['list-bold'])) == 'on' ? 'on' : '';

        // Listenelemente als Links
        $zdm_options['list-links'] = isset($_POST['list-links']) && trim(sanitize_text_field($_POST['list-links'])) == 'on' ? 'on' : '';

        // Statistik

        // Letzte Downloads anzeigen für Dateien
        $zdm_options['stat-single-file-last-limit'] = isset($_POST['stat-single-file-last-limit']) ? trim(sanitize_text_field($_POST['stat-single-file-last-limit'])) : '';

        // Letzte Downloads anzeigen für Archive
        $zdm_options['stat-single-archive-last-limit'] = isset($_POST['stat-single-archive-last-limit']) ? trim(sanitize_text_field($_POST['stat-single-archive-last-limit'])) : '';

        // Mehr

        // Secure file uploads
        $zdm_options['secure-file-upload'] = isset($_POST['secure-file-upload']) && trim(sanitize_text_field($_POST['secure-file-upload'])) == 'on' ? 'on' : '';

        // Maximum upload size
        $zdm_options['max-upload-size-in-mb'] = isset($_POST['max-upload-size-in-mb']) ? trim(sanitize_text_field($_POST['max-upload-size-in-mb'])) : '';

        // Direkte URL zu PDFs
        $zdm_options['file-open-in-browser-pdf'] = isset($_POST['file-open-in-browser-pdf']) && trim(sanitize_text_field($_POST['file-open-in-browser-pdf'])) == 'on' ? 'on' : '';

        // IP-Adresse zensieren
        $zdm_options['secure-ip'] = isset($_POST['secure-ip']) && trim(sanitize_text_field($_POST['secure-ip'])) == 'on' ? 'on' : '';

        // Duplikate zulassen
        $zdm_options['duplicate-file'] = isset($_POST['duplicate-file']) && trim(sanitize_text_field($_POST['duplicate-file'])) == 'on' ? 'on' : '';

        // HTML id Attribut ausblenden
        $zdm_options['hide-html-id'] = isset($_POST['hide-html-id']) && trim(sanitize_text_field($_POST['hide-html-id'])) == 'on' ? 'on' : '';

        // Update options
        if (add_option('zdm_options', $zdm_options) === FALSE) {
            update_option('zdm_options', $zdm_options);
            $zdm_update = 1;
        }

        $zdm_options = get_option('zdm_options');

        ZDMCore::log('update settings', serialize($_POST));
    }

    ////////////////////
    // Neuen Token für Download Ordner generieren
    ////////////////////
    if (isset($_GET['new_download_folder_token']) && wp_verify_nonce($_GET['nonce'], 'new_download_folder_token')) {

        if ($_GET['new_download_folder_token'] == 'true') {

            if (get_option('zdm_options')) {

                $zdm_new_download_folder_token = md5(uniqid(rand(), true));

                // Downloadordner umbenennen
                if (is_dir(ZDM__DOWNLOADS_PATH))
                    rename(ZDM__DOWNLOADS_PATH, wp_upload_dir()['basedir'] . "/z-downloads-" . $zdm_new_download_folder_token);

                // Neuen Downloadordner Token in Optionen speichern
                $zdm_options['download-folder-token'] = $zdm_new_download_folder_token;
                ZDMCore::log('download folder token', $zdm_options['download-folder-token']);

                update_option('zdm_options', $zdm_options);
                $zdm_options = get_option('zdm_options');

                if (headers_sent()) {
                    $zdm_status = 3;
                } else {
                    // Einstellungsseite neu laden
                    $zdm_settings_url = 'admin.php?page=' . ZDM__SLUG . '-settings';
                    wp_redirect($zdm_settings_url);
                    exit;
                }
            }
        }
    }

    ////////////////////
    // Einstellungen zurücksetzen
    ////////////////////
    if (isset($_GET['reset_settings']) && wp_verify_nonce($_GET['nonce'], 'reset-settings')) {

        if ($_GET['reset_settings'] == 'true') {

            flush_rewrite_rules();

            if (get_option('zdm_options')) {
                update_option('zdm_options', ZDM__OPTIONS);
                $zdm_options = get_option('zdm_options');

                // Neuen Token für Download Ordner generieren
                $zdm_new_download_folder_token = md5(uniqid(rand(), true));

                // Downloadordner umbenennen
                if (is_dir(ZDM__DOWNLOADS_PATH))
                    rename(ZDM__DOWNLOADS_PATH, wp_upload_dir()['basedir'] . "/z-downloads-" . $zdm_new_download_folder_token);

                // Neuen Downloadordner Token in Optionen speichern
                $zdm_options['download-folder-token'] = $zdm_new_download_folder_token;
                ZDMCore::log('download-folder-token', $zdm_options['download-folder-token']);

                update_option('zdm_options', $zdm_options);
                $zdm_options = get_option('zdm_options');

                ZDMCore::log('reset settings');

                if (headers_sent()) {
                    $zdm_status = 4;
                } else {
                    // Einstellungsseite neu laden
                    $zdm_settings_url = 'admin.php?page=' . ZDM__SLUG . '-settings';
                    wp_redirect($zdm_settings_url);
                    exit;
                }
            }
        }
    }

    ////////////////////
    // Alle Daten löschen
    ////////////////////
    if (isset($_GET['delete_data']) && wp_verify_nonce($_GET['nonce'], 'delete-all-data')) {

        if ($_GET['delete_data'] == 'true') {

            ZDMCore::delete_all_data();
        }
    }

    if ($zdm_status === 1) {
?>

        <div class="wrap">
            <h1 class="wp-heading-inline"><?= esc_html__('Settings', 'zdm') ?></h1>
            <hr class="wp-header-end">
            <br>

            <?php
            if ($zdm_update != '') {

                echo '<div class="notice notice-success">';
                echo '<br><b>' . esc_html__('Settings updated!', 'zdm') . '</b><br><br>';
                echo '</div>';
            }

            if ($zdm_note != '') {

                echo '<div class="notice notice-success">';
                echo '<br><b>' . $zdm_note . '</b><br><br>';
                echo '</div>';
            }

            if ($zdm_error != '') {

                echo '<div class="notice notice-warning">';
                echo '<br><b>' . $zdm_error . '</b><br><br>';
                echo '</div>';
            }

            if (isset($_GET['delete_data']) && wp_verify_nonce($_GET['nonce'], 'delete-all-data')) { // Nur Hinweis dass die Daten gelöscht wurden anzeigen
            ?>
                <div class="postbox">
                    <div class="inside">
                        <h3 class="zdm-color-green"><span class="material-icons-round zdm-md-1">check_circle_outline</span> <?= esc_html__('All data was deleted successfully!', 'zdm') ?></h3>
                        <p><?= esc_html__('All your uploaded files, all archives in the cache and all database entries from', 'zdm') ?> <?= ZDM__TITLE ?> <?= esc_html__('have been irrevocably deleted.', 'zdm') ?></p>
                        <p><?= esc_html__('You can now deactivate and uninstall the plugin in the plugin overview or you upload new files and start fresh.', 'zdm') ?></p>
                        <a href="admin.php?page=<?= ZDM__SLUG ?>-settings" class="button button-secondary"><?= esc_html__('Back to settings', 'zdm') ?></a>
                    </div>
                </div>
            <?php
            } else { // Normale Ansicht der Einstellungsseite
            ?>

                <form action="" method="post">
                    <input type="hidden" name="nonce" value="<?= wp_create_nonce('update-license') ?>">
                    <div class="postbox">
                        <div class="inside">
                            <h3><?php if ($zdm_licence === 1) { ?><?= $zdm_options['licence-product-name']; ?> <?= esc_html__('is activated', 'zdm') ?><?php } else {
                                                                                                                                                        echo ZDM__PRO;
                                                                                                                                                    } ?></h3>
                            <hr>
                            <table class="form-table">
                                <tbody>
                                    <tr valign="top">
                                        <th scope="row"><?= ZDM__PRO ?> <?= esc_html__('license key', 'zdm') ?>:</th>
                                        <td valign="middle">
                                            <?php if ($zdm_licence === 1) { ?><span class="material-icons-round zdm-md-1 zdm-color-green">check_circle_outline</span>&nbsp;<?php } ?>
                                                <?php if ($zdm_licence === 1) {
                                                ?>
                                                    <input type="password" name="licence-key" id="licence-key" size="50%" value="<?= esc_attr($zdm_options['licence-key']); ?>">&nbsp;
                                                    <button class="button button-secondary" id="toggle-licence-key"><span class="material-icons-outlined zdm-md-1 zdm-color-grey7">visibility</span></button>&nbsp;
                                                    <input class="button-primary" type="submit" name="licence_submit" value="<?= esc_html__('Update', 'zdm') ?>">&nbsp;
                                                    <a href="admin.php?page=<?= ZDM__SLUG ?>-settings&licence_delete=true&nonce=<?= wp_create_nonce('licence-delete') ?>" class="button button-primary"><?= esc_html__('Remove license key', 'zdm') ?></a>
                                                    <script>
                                                        jQuery(document).ready(function($) {
                                                            $("#toggle-licence-key").click(function(e) {
                                                                // Verhindert das Standardverhalten des Buttons
                                                                e.preventDefault();

                                                                let input = $("#licence-key");
                                                                let span = $(this).find('span');

                                                                if (input.attr("type") === "password") {
                                                                    input.attr("type", "text");
                                                                    span.text("visibility_off");
                                                                } else {
                                                                    input.attr("type", "password");
                                                                    span.text("visibility");
                                                                }
                                                            });
                                                        });
                                                    </script>
                                                <?php
                                                } else {
                                                ?>
                                                    <input type="text" name="licence-key" id="licence-key" size="50%" value="<?= esc_attr($zdm_options['licence-key']); ?>">&nbsp;
                                                    <input class="button-primary" type="submit" name="licence_submit" value="<?= esc_html__('Activate', 'zdm') ?>">
                                                <?php
                                                }

                                                if ($zdm_licence === 0) { ?>
                                                    <div class="zdm-help-text"><?= esc_html__('Don\'t wait any longer - unlock the full potential of', 'zdm') ?> <?= ZDM__TITLE ?> <?= esc_html__('and get', 'zdm') ?> <?= ZDM__PRO ?> <?= esc_html__('today! Learn more at', 'zdm') ?>: <a href="<?= ZDM__PRO_URL ?>" target="_blank" title="<?= ZDM__TITLE; ?> <?= ZDM__PRO ?>"><?= ZDM__TITLE; ?> <?= ZDM__PRO ?> <span class="material-icons-round zdm-md-1">open_in_new</span></a></div>
                                                <?php
                                                } ?>
                                        </td>
                                    </tr>
                                    <?php if ($zdm_licence === 1) { ?>
                                        <tr valign="top">
                                            <th scope="row"><?= esc_html__('Licensed for', 'zdm') ?>:</th>
                                            <td valign="middle">
                                                <span class="material-icons-round zdm-md-1 zdm-color-green">check_circle_outline</span>&nbsp;<?= $zdm_options['licence-email']; ?>
                                            </td>
                                        </tr>
                                        <tr valign="top">
                                            <th scope="row"><?= esc_html__('Purchased', 'zdm') ?>:</th>
                                            <td valign="middle">
                                                <span class="material-icons-round zdm-md-1 zdm-color-green">check_circle_outline</span>&nbsp;<?= date("d.m.Y", strtotime($zdm_options['licence-purchase'])) ?>
                                            </td>
                                        </tr>
                                    <?php } ?>
                                </tbody>
                            </table>
                        </div>
                    </div>
                </form>

                <form action="" method="post">

                    <div class="postbox" id="zdm-download-button">
                        <div class="inside">
                            <h3><?= esc_html__('Download button', 'zdm') ?></h3>
                            <hr>
                            <table class="form-table">
                                <tbody>
                                    <tr valign="top">
                                        <th scope="row"><?= esc_html__('Standard text', 'zdm') ?>:</th>
                                        <td valign="middle">
                                            <input type="text" name="download-btn-text" size="15" value="<?= esc_attr($zdm_options['download-btn-text']) ?>">
                                            <br>
                                            <div class="zdm-help-text"><?= esc_html__('This is the default text, but this can be changed individually per download.', 'zdm') ?></div>
                                        </td>
                                    </tr>
                                    <tr valign="top">
                                        <th scope="row"><?= esc_html__('Style', 'zdm') ?>:</th>
                                        <td valign="middle">
                                            <select name="download-btn-style">
                                                <?php
                                                $zdm_btn_style = '';

                                                $zdm_download_btn_style = count(ZDM__DOWNLOAD_BTN_STYLE);

                                                for ($i = 0; $i < $zdm_download_btn_style; $i++) {
                                                    $zdm_btn_style    .= '<option value="' . ZDM__DOWNLOAD_BTN_STYLE_VAL[$i] . '" '
                                                        . ($zdm_options['download-btn-style'] == ZDM__DOWNLOAD_BTN_STYLE_VAL[$i] ? 'selected="selected"' : '') . '>'
                                                        . ZDM__DOWNLOAD_BTN_STYLE[$i]
                                                        . '</option>';
                                                }

                                                echo $zdm_btn_style;
                                                ?>
                                            </select>
                                            &nbsp;&nbsp;&nbsp;
                                            <span class="zdm-color-bg-<?= $zdm_options['download-btn-style'] ?>">&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</span>
                                            <br>
                                            <div class="zdm-help-text"><?= esc_html__('Choose from different button colors the default value for buttons.', 'zdm') ?></div>
                                        </td>
                                    </tr>
                                    <tr valign="top">
                                        <th scope="row"></th>
                                        <td valign="middle">
                                            <input type="checkbox" name="download-btn-outline" <?php if ($zdm_options['download-btn-outline'] == 'on') {
                                                                                                    echo 'checked="checked"';
                                                                                                } ?>>
                                            <?= esc_html__('Outline', 'zdm') ?>
                                            <br>
                                            <div class="zdm-help-text"><?= esc_html__('This option shows the button as a frame.', 'zdm') ?></div>
                                        </td>
                                    </tr>
                                    <tr valign="top">
                                        <th scope="row"><?= esc_html__('Round corners', 'zdm') ?>:</th>
                                        <td valign="middle">
                                            <select name="download-btn-border-radius">
                                                <?php
                                                $zdm_btn_border = '';

                                                $zdm_download_btn_border_radius = count(ZDM__DOWNLOAD_BTN_BORDER_RADIUS);

                                                for ($i = 0; $i < $zdm_download_btn_border_radius; $i++) {
                                                    $zdm_btn_border .= '<option value="' . ZDM__DOWNLOAD_BTN_BORDER_RADIUS_VAL[$i] . '" '
                                                        . ($zdm_options['download-btn-border-radius'] == ZDM__DOWNLOAD_BTN_BORDER_RADIUS_VAL[$i] ? 'selected="selected"' : '') . '>'
                                                        . ZDM__DOWNLOAD_BTN_BORDER_RADIUS[$i]
                                                        . '</option>';
                                                }

                                                echo $zdm_btn_border;
                                                ?>
                                            </select><br>
                                            <div class="zdm-help-text"><?= esc_html__('If "none" is selected then the default value of your theme will be used, the button will remain square.', 'zdm') ?></div>
                                        </td>
                                    </tr>
                                    <tr valign="top">
                                        <th scope="row"><?= esc_html__('Icon', 'zdm') ?>:</th>
                                        <td valign="middle">
                                            <?php
                                            $zdm_btn_icons_count = count(ZDM__DOWNLOAD_BTN_ICON);
                                            $zdm_btn_icons_count_ceil = ceil(($zdm_btn_icons_count) / 3);
                                            ?>
                                            <table>
                                                <tr>
                                                    <fieldset>
                                                        <td>
                                                            <input type="radio" name="download-btn-icon" value="<?= ZDM__DOWNLOAD_BTN_ICON_VAL[0] ?>" <?php if ($zdm_options['download-btn-icon'] == ZDM__DOWNLOAD_BTN_ICON_VAL[0]) {
                                                                                                                                                            echo 'checked="checked"';
                                                                                                                                                        } ?>> <span class="zdm-ml-2"><?= ZDM__DOWNLOAD_BTN_ICON[0] ?></input></span><br />
                                                            <?php
                                                            $zdm_btn_icon_example = '';
                                                            for ($i = 1; $i < $zdm_btn_icons_count_ceil; $i++) {
                                                                $zdm_btn_icon_example .= '<input type="radio" name="download-btn-icon" value="' . ZDM__DOWNLOAD_BTN_ICON_VAL[$i] . '" ';
                                                                $zdm_btn_icon_example .= ($zdm_options['download-btn-icon'] == ZDM__DOWNLOAD_BTN_ICON_VAL[$i] ? 'checked="checked"' : '') . '>';
                                                                $zdm_btn_icon_example .= '<span class="material-icons-round zdm-md-1-5 zdm-color-primary zdm-mx-2">' . ZDM__DOWNLOAD_BTN_ICON_VAL[$i] . '</span>' . ZDM__DOWNLOAD_BTN_ICON[$i] . '</input><br />';
                                                            }
                                                            echo $zdm_btn_icon_example;
                                                            ?>
                                                        </td>
                                                        <td>
                                                            <?php
                                                            $zdm_btn_icon_example = '';
                                                            for ($i = $zdm_btn_icons_count_ceil; $i < $zdm_btn_icons_count - $zdm_btn_icons_count_ceil; $i++) {
                                                                $zdm_btn_icon_example .= '<input type="radio" name="download-btn-icon" value="' . ZDM__DOWNLOAD_BTN_ICON_VAL[$i] . '" ';
                                                                $zdm_btn_icon_example .= ($zdm_options['download-btn-icon'] == ZDM__DOWNLOAD_BTN_ICON_VAL[$i] ? 'checked="checked"' : '') . '>';
                                                                $zdm_btn_icon_example .= '<span class="material-icons-round zdm-md-1-5 zdm-color-primary zdm-mx-2">' . ZDM__DOWNLOAD_BTN_ICON_VAL[$i] . '</span>' . ZDM__DOWNLOAD_BTN_ICON[$i] . '</input><br />';
                                                            }
                                                            echo $zdm_btn_icon_example;
                                                            ?>
                                                        </td>
                                                        <td>
                                                            <?php
                                                            $zdm_btn_icon_example = '';
                                                            for ($i = $zdm_btn_icons_count_ceil + $zdm_btn_icons_count_ceil; $i < $zdm_btn_icons_count; $i++) {
                                                                $zdm_btn_icon_example .= '<input type="radio" name="download-btn-icon" value="' . ZDM__DOWNLOAD_BTN_ICON_VAL[$i] . '" ';
                                                                $zdm_btn_icon_example .= ($zdm_options['download-btn-icon'] == ZDM__DOWNLOAD_BTN_ICON_VAL[$i] ? 'checked="checked"' : '') . '>';
                                                                $zdm_btn_icon_example .= '<span class="material-icons-round zdm-md-1-5 zdm-color-primary zdm-mx-2">' . ZDM__DOWNLOAD_BTN_ICON_VAL[$i] . '</span>' . ZDM__DOWNLOAD_BTN_ICON[$i] . '</input><br />';
                                                            }
                                                            echo $zdm_btn_icon_example;
                                                            ?>
                                                        </td>
                                                    </fieldset>
                                                </tr>
                                            </table>
                                        </td>
                                    </tr>
                                    <tr valign="top">
                                        <th scope="row"><?= esc_html__('Icon position', 'zdm') ?>:</th>
                                        <td valign="middle">
                                            <select name="download-btn-icon-position">
                                                <option value="left" <?php if ($zdm_options['download-btn-icon-position'] == 'left') {
                                                                            echo 'selected="selected"';
                                                                        } ?>><?= esc_html__('Left', 'zdm') ?></option>
                                                <option value="right" <?php if ($zdm_options['download-btn-icon-position'] == 'right') {
                                                                            echo 'selected="selected"';
                                                                        } ?>><?= esc_html__('Right', 'zdm') ?></option>
                                            </select>
                                            <br>
                                            <div class="zdm-help-text"><?= esc_html__('Choose the position of the icon.', 'zdm') ?></div>
                                        </td>
                                    </tr>
                                    <tr valign="top">
                                        <th scope="row"></th>
                                        <td valign="middle">
                                            <input type="checkbox" name="download-btn-icon-only" <?php if ($zdm_options['download-btn-icon-only'] == 'on') {
                                                                                                        echo 'checked="checked"';
                                                                                                    } ?>>
                                            <?= esc_html__('Only icon', 'zdm') ?>
                                            <br>
                                            <div class="zdm-help-text"><?= esc_html__('This option displays only the icon without text.', 'zdm') ?></div>
                                        </td>
                                    </tr>
                                </tbody>
                            </table>
                        </div>
                    </div>

                    <div class="postbox" id="zdm-list">
                        <div class="inside">
                            <h3><?= esc_html__('Lists', 'zdm') ?></h3>
                            <hr>
                            <table class="form-table">
                                <tbody>
                                    <tr valign="top">
                                        <th scope="row"><?= esc_html__('List style', 'zdm') ?>:</th>
                                        <td valign="middle">
                                            <select name="list-style">
                                                <option value="rows" <?php if ($zdm_options['list-style'] == 'rows') {
                                                                            echo 'selected="selected"';
                                                                        } ?>><?= esc_html__('Rows', 'zdm') ?></option>
                                                <option value="ul" <?php if ($zdm_options['list-style'] == 'ul') {
                                                                        echo 'selected="selected"';
                                                                    } ?>><?= esc_html__('Unordered list (ul)', 'zdm') ?></option>
                                                <option value="ol" <?php if ($zdm_options['list-style'] == 'ol') {
                                                                        echo 'selected="selected"';
                                                                    } ?>><?= esc_html__('Ordered list (ol)', 'zdm') ?></option>
                                            </select>
                                            <?= esc_html__('Specifies how the list is output.', 'zdm') ?>
                                        </td>
                                    </tr>
                                    <tr valign="top">
                                        <th scope="row"><?= esc_html__('Bold text', 'zdm') ?>:</th>
                                        <td valign="middle">
                                            <input type="checkbox" name="list-bold" <?php if ($zdm_options['list-bold'] == 'on') {
                                                                                        echo 'checked="checked"';
                                                                                    } ?>>
                                            <?= esc_html__('Makes the text of the list items bold.', 'zdm') ?>
                                        </td>
                                    </tr>
                                    <tr valign="top">
                                        <th scope="row"><?= esc_html__('List items as links', 'zdm') ?>:</th>
                                        <td valign="middle">
                                            <input type="checkbox" name="list-links" <?php if ($zdm_options['list-links'] == 'on') {
                                                                                            echo 'checked="checked"';
                                                                                        } ?>>
                                            <?= esc_html__('Each list item is a clickable link.', 'zdm') ?>
                                        </td>
                                    </tr>
                                </tbody>
                            </table>
                        </div>
                    </div>

                    <div class="postbox" id="zdm-stat">
                        <div class="inside">
                            <h3><?= esc_html__('Statistics', 'zdm') ?></h3>
                            <hr>
                            <table class="form-table">
                                <tbody>
                                    <tr valign="top">
                                        <th scope="row">
                                            <?= esc_html__('Last downloads limit', 'zdm') ?>:
                                            <?php ZDMCore::premium_badge(); ?>
                                        </th>
                                        <td valign="middle">
                                            <?php if ($zdm_licence === 0) {
                                            ?>
                                                <input type="hidden" name="stat-single-file-last-limit" value="<?= esc_attr($zdm_options['stat-single-file-last-limit']) ?>">
                                                <input type="hidden" name="stat-single-archive-last-limit" value="<?= esc_attr($zdm_options['stat-single-archive-last-limit']) ?>">
                                            <?php
                                            }
                                            ?>
                                            <input type="number" name="stat-single-file-last-limit" min="1" max="500" value="<?= esc_attr($zdm_options['stat-single-file-last-limit']) ?>" <?php if ($zdm_licence === 0) {
                                                                                                                                                                                                echo ' disabled';
                                                                                                                                                                                            } ?>>
                                            <span class="material-icons-outlined zdm-md-1">info</span> <?= esc_html__('Setting for files', 'zdm') ?>
                                            <br>
                                            <div class="zdm-help-text"><?= esc_html__('Determine the number of recent downloads that is displayed on the file details page in the Statistics tab.', 'zdm') ?></div>
                                            <br>
                                            <input type="number" name="stat-single-archive-last-limit" min="1" max="500" value="<?= esc_attr($zdm_options['stat-single-archive-last-limit']) ?>" <?php if ($zdm_licence === 0) {
                                                                                                                                                                                                        echo ' disabled';
                                                                                                                                                                                                    } ?>>
                                            <span class="material-icons-outlined zdm-md-1">info</span> <?= esc_html__('Setting for archives', 'zdm') ?>
                                            <br>
                                            <div class="zdm-help-text"><?= esc_html__('Determine the number of recent downloads that is displayed on the archive detail page in the Statistics tab.', 'zdm') ?></div>
                                        </td>
                                    </tr>
                                </tbody>
                            </table>
                        </div>
                    </div>

                    <div class="postbox" id="zdm-expanded">
                        <div class="inside">
                            <h3><?= esc_html__('Advanced', 'zdm') ?></h3>
                            <hr>
                            <table class="form-table">
                                <tbody>
                                    <tr valign="top">
                                        <th scope="row"><?= esc_html__('Secure file uploads', 'zdm') ?>:</th>
                                        <td valign="middle">
                                            <input type="checkbox" name="secure-file-upload" <?php if ($zdm_options['secure-file-upload'] == 'on') {
                                                                                                    echo 'checked="checked"';
                                                                                                } ?>>
                                            <?= esc_html__('Limits file uploads to common files.', 'zdm') ?>
                                            <div class="zdm-help-text"><?= esc_html__('Only deactivate this option if you are sure that your uploaded files cannot cause any damage.', 'zdm') ?></div>
                                        </td>
                                    </tr>
                                    <tr valign="top">
                                        <th scope="row"><?= esc_html__('Maximum upload size', 'zdm') ?>:</th>
                                        <td valign="middle">
                                            <input type="number" name="max-upload-size-in-mb" min="1" max="50000" value="<?= esc_attr($zdm_options['max-upload-size-in-mb']) ?>">
                                            <?= esc_html__('Specifies the maximum file size in MB that can be uploaded.', 'zdm') ?>
                                        </td>
                                    </tr>
                                    <tr valign="top">
                                        <th scope="row"><?= esc_html__('Direct URL to PDFs', 'zdm') ?>:</th>
                                        <td valign="middle">
                                            <input type="checkbox" name="file-open-in-browser-pdf" <?php if ($zdm_options['file-open-in-browser-pdf'] == 'on') {
                                                                                                        echo 'checked="checked"';
                                                                                                    } ?>>
                                            <?= esc_html__('The user is taken to the direct file path.', 'zdm') ?>
                                        </td>
                                    </tr>
                                    <tr valign="top">
                                        <th scope="row"><?= esc_html__('Censor the IP address', 'zdm') ?>:</th>
                                        <td valign="middle">
                                            <input type="checkbox" name="secure-ip" <?php if ($zdm_options['secure-ip'] == 'on') {
                                                                                        echo 'checked="checked"';
                                                                                    } ?>>
                                            <?= esc_html__('Censored the IP address during the download.', 'zdm') ?>
                                        </td>
                                    </tr>
                                    <tr valign="top">
                                        <th scope="row"><?= esc_html__('Allow duplicates', 'zdm') ?>:</th>
                                        <td valign="middle">
                                            <input type="checkbox" name="duplicate-file" <?php if ($zdm_options['duplicate-file'] == 'on') {
                                                                                                echo 'checked="checked"';
                                                                                            } ?>>
                                            <?= esc_html__('Allow files that have already been uploaded to be added again.', 'zdm') ?>
                                        </td>
                                    </tr>
                                    <tr valign="top">
                                        <th scope="row"><?= esc_html__('Hide HTML id Attribute', 'zdm') ?>:</th>
                                        <td valign="middle">
                                            <input type="checkbox" name="hide-html-id" <?php if ($zdm_options['hide-html-id'] == 'on') {
                                                                                            echo 'checked="checked"';
                                                                                        } ?>>
                                            <?= esc_html__('Hides the HTML id Attribute when outputting button, audio and video.', 'zdm') ?>
                                        </td>
                                    </tr>
                                    <tr valign="top">
                                        <th scope="row"><?= esc_html__('Log', 'zdm') ?>:</th>
                                        <td valign="middle">
                                            <a href="admin.php?page=<?= ZDM__SLUG ?>-log"><?= esc_html__('Show log', 'zdm') ?></a>
                                        </td>
                                    </tr>
                                    <tr valign="top">
                                        <th scope="row"><?= esc_html__('Download folder token', 'zdm') ?>:</th>
                                        <td valign="middle">
                                            <input type="text" value="<?= $zdm_options['download-folder-token'] ?>" size="50%" disabled>&nbsp;
                                            <a href="admin.php?page=<?= ZDM__SLUG ?>-settings&new_download_folder_token=true&nonce=<?= wp_create_nonce('new_download_folder_token') ?>" class="button button-secondary"><?= esc_html__('Generate new token', 'zdm') ?></a>
                                            <div class="zdm-help-text"><?= esc_html__('Complete folder name', 'zdm') ?>: <code>/z-downloads-<?= $zdm_options['download-folder-token'] ?>/</code></div>
                                            <div class="zdm-help-text"><?= esc_html__('The download folder is not publicly visible unless you have activated "Direct URL" for certain files.', 'zdm') ?></div>
                                            <div class="zdm-help-text"><?= esc_html__('You can generate the token again at any time without hesitation.', 'zdm') ?></div>
                                        </td>
                                    </tr>
                                    <tr valign="top">
                                        <th scope="row"><?= esc_html__('Reset settings', 'zdm') ?>:</th>
                                        <td valign="middle">
                                            <a href="admin.php?page=<?= ZDM__SLUG ?>-settings&reset_settings=true&nonce=<?= wp_create_nonce('reset-settings') ?>" class="button button-secondary"><?= esc_html__('Reset settings', 'zdm') ?></a>
                                            <div class="zdm-help-text"><?= esc_html__('Here you can reset all settings to factory settings..', 'zdm') ?></div>
                                            <div class="zdm-help-text">
                                                <?= esc_html__('This means that the premium license, button settings such as standard text, style, round corners, icons and all other settings are reset.', 'zdm') ?><br>
                                                <?= esc_html__('The download folder token is also regenerated.', 'zdm') ?>
                                            </div>
                                        </td>
                                    </tr>
                                </tbody>
                            </table>
                        </div>
                    </div>

                    <div class="postbox zdm-box-danger-outline">
                        <div class="inside">
                            <h3><?= ZDM__TITLE ?> <?= esc_html__('uninstall', 'zdm') ?></h3>
                            <hr>
                            <p><?= esc_html__('Path of', 'zdm') ?> <?= ZDM__TITLE ?> <?= esc_html__('upload folder for files, ZIP archives and cache', 'zdm') ?>:<br>
                                <?php
                                $zdm_download_folder_path = wp_upload_dir()['basedir'] . "/z-downloads-" . $zdm_options['download-folder-token'];
                                if (is_dir($zdm_download_folder_path))
                                    $zdm_download_folder_text = $zdm_download_folder_path . '/';
                                else
                                    $zdm_download_folder_text = esc_html__('The folder does not yet exist and is automatically created when a file is uploaded.', 'zdm');
                                ?>
                            <div class="zdm-help-text"><code><?= $zdm_download_folder_text ?></code></div>
                            </p>
                            <hr>
                            <h3 class="zdm-color-red"><?= esc_html__('Attention before uninstalling the plugin', 'zdm') ?></h3>
                            <p class="zdm-color-red"><?= esc_html__('If you uninstall the Z-Downloads-Plugin all files and ZIP-archives remain in the above path, if you want to delete all files and ZIP-archives you have created, then click on "DELETE" below', 'zdm') ?></p>
                            <p class="zdm-color-red"><?= esc_html__('This process is irreplaceable and can not be undone.', 'zdm') ?></p>

                            <br>
                            <a href="admin.php?page=<?= ZDM__SLUG ?>-settings&delete_data=true&nonce=<?= wp_create_nonce('delete-all-data') ?>" class="button button-secondary zdm-btn-danger-outline"><?= esc_html__('DELETE', 'zdm') ?></a>
                        </div>
                    </div>

                    <?php
                    require_once(plugin_dir_path(__FILE__) . '../inc/postbox_info.php');
                    if (ZDMCore::licence() != true)
                        require_once(plugin_dir_path(__FILE__) . '../inc/postbox_premium_info.php');
                    ?>

                    <input type="hidden" name="nonce" value="<?= wp_create_nonce('einstellungen-speichern') ?>">
                    <input class="button-primary" type="submit" name="submit" value="<?= esc_html__('Save', 'zdm') ?>">
                </form>

            <?php } ?>

        </div>
    <?php
    } elseif ($zdm_status === 2) { // Lizenzschlüssel aktualisiert
    ?>
        <div class="notice notice-success">
            <p><span class="material-icons-round zdm-md-1 zdm-color-green">check_circle_outline</span> <?= esc_html__('License key deleted!', 'zdm') ?></p>
            <p><a href="admin.php?page=<?= ZDM__SLUG ?>-settings" class="button-primary"><?= esc_html__('Back to settings', 'zdm') ?></a></p>
        </div>
    <?php
    } elseif ($zdm_status === 3) { // Downloadordner Token aktualisiert
    ?>
        <div class="notice notice-success">
            <p><span class="material-icons-round zdm-md-1 zdm-color-green">check_circle_outline</span> <?= esc_html__('Download folder token updated!', 'zdm') ?></p>
            <p><a href="admin.php?page=<?= ZDM__SLUG ?>-settings" class="button-primary"><?= esc_html__('Back to settings', 'zdm') ?></a></p>
        </div>
    <?php
    } elseif ($zdm_status === 4) { // Einstellungen zurückgesetzt
    ?>
        <div class="notice notice-success">
            <p><span class="material-icons-round zdm-md-1 zdm-color-green">check_circle_outline</span> <?= esc_html__('Settings successfully reset!', 'zdm') ?></p>
            <p><a href="admin.php?page=<?= ZDM__SLUG ?>-settings" class="button-primary"><?= esc_html__('Back to settings', 'zdm') ?></a></p>
        </div>
<?php
    }
}
