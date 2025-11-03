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
        $download_btn_text = isset($_POST['download-btn-text']) ? trim(sanitize_text_field($_POST['download-btn-text'])) : '';
        $download_btn_text = substr($download_btn_text, 0, 50);
        $zdm_options['download-btn-text'] = $download_btn_text;

        // Download Button Style
        // Erlaubte Stile definieren
        $allowed_styles = ZDM__DOWNLOAD_BTN_STYLE_VAL;
        // Eingabe erhalten und bereinigen
        $download_btn_style = isset($_POST['download-btn-style']) ? trim(sanitize_text_field($_POST['download-btn-style'])) : '';
        // Eingabe validieren
        if (in_array($download_btn_style, $allowed_styles, true)) {
            $zdm_options['download-btn-style'] = $download_btn_style;
        } else {
            // Ungültige Eingabe behandeln (Standardwert setzen oder Fehler anzeigen)
            $zdm_options['download-btn-style'] = 'default_style'; // Ersetzen Sie dies durch Ihren tatsächlichen Standardwert
        }

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

                if (empty($download_btn_text)) {
                    $zdm_options['download-btn-text'] = 'Download';
                } else {
                    $zdm_options['download-btn-text'] = $download_btn_text;
                }
            ?>

                <form action="" method="post">
                    <input type="hidden" name="nonce" value="<?= wp_create_nonce('update-license') ?>">
                    <?php
                    $zdm_licence_icon = $zdm_licence === 1 ? 'verified' : 'vpn_key';
                    $zdm_licence_icon_color = $zdm_licence === 1 ? 'zdm-color-green' : 'zdm-color-grey7';
                    $zdm_licence_status_text = $zdm_licence === 1 ? esc_html__('Premium features are unlocked and ready to use.', 'zdm') : esc_html__('Activate your license to unlock all premium features.', 'zdm');
                    $zdm_licence_button_label = $zdm_licence === 1 ? esc_html__('Update', 'zdm') : esc_html__('Activate', 'zdm');
                    $zdm_licence_badge_class = $zdm_licence === 1 ? 'is-active' : 'is-inactive';
                    $zdm_licence_badge_label = $zdm_licence === 1 ? esc_html__('Active', 'zdm') : esc_html__('Inactive', 'zdm');
                    $zdm_licence_last_checked = !empty($zdm_options['licence-time']) ? date_i18n(get_option('date_format') . ' ' . get_option('time_format'), (int) $zdm_options['licence-time']) : esc_html__('Not validated yet', 'zdm');
                    ?>
                    <div class="postbox zdm-licence-card">
                        <div class="inside">
                            <div class="zdm-licence-header">
                                <span class="material-icons-round zdm-md-1-5 <?= esc_attr($zdm_licence_icon_color) ?>"><?= esc_html($zdm_licence_icon) ?></span>
                                <div class="zdm-licence-title">
                                    <h3><?= $zdm_licence === 1 ? esc_html($zdm_options['licence-product-name']) : ZDM__PRO ?></h3>
                                    <p><?= esc_html($zdm_licence_status_text) ?></p>
                                </div>
                                <span class="zdm-licence-status-badge <?= esc_attr($zdm_licence_badge_class) ?>">
                                    <?= esc_html($zdm_licence_badge_label) ?>
                                </span>
                            </div>

                            <div class="zdm-licence-body">
                                <div class="zdm-licence-input">
                                    <label for="licence-key"><?= ZDM__PRO ?> <?= esc_html__('license key', 'zdm') ?></label>
                                    <div class="zdm-licence-input-row">
                                        <input type="<?= $zdm_licence === 1 ? 'password' : 'text' ?>" name="licence-key" id="licence-key" value="<?= esc_attr($zdm_options['licence-key']); ?>" autocomplete="off">
                                        <?php if ($zdm_licence === 1) { ?>
                                            <button class="button button-secondary zdm-licence-toggle" id="toggle-licence-key">
                                                <span class="material-icons-outlined zdm-md-1 zdm-color-grey7">visibility</span>
                                            </button>
                                        <?php } ?>
                                        <input class="button-primary" type="submit" name="licence_submit" value="<?= esc_attr($zdm_licence_button_label) ?>">
                                        <?php if ($zdm_licence === 1) { ?>
                                            <a href="admin.php?page=<?= ZDM__SLUG ?>-settings&licence_delete=true&nonce=<?= wp_create_nonce('licence-delete') ?>" class="button button-secondary zdm-licence-remove">
                                                <span class="material-icons-round zdm-md-1">backspace</span>
                                                <?= esc_html__('Remove license', 'zdm') ?>
                                            </a>
                                        <?php } ?>
                                    </div>
                                    <?php if ($zdm_licence === 0) { ?>
                                        <div class="zdm-licence-upgrade">
                                            <span class="material-icons-round zdm-md-1">sparkles</span>
                                            <p><?= esc_html__('Unlock all premium features with', 'zdm') ?> <?= ZDM__PRO ?>. <?= esc_html__('Learn more at', 'zdm') ?> <a href="<?= ZDM__PRO_URL ?>" target="_blank" title="<?= ZDM__TITLE; ?> <?= ZDM__PRO ?>"><?= ZDM__TITLE; ?> <?= ZDM__PRO ?> <span class="material-icons-round zdm-md-1">open_in_new</span></a></p>
                                        </div>
                                        <div class="zdm-licence-steps">
                                            <div class="zdm-licence-step">
                                                <span class="zdm-licence-step__index">1</span>
                                                <div>
                                                    <span class="zdm-licence-step__title"><?= esc_html__('Locate your purchase email', 'zdm') ?></span>
                                                    <p><?= esc_html__('Copy the license key you received after buying Z-Downloads PRO.', 'zdm') ?></p>
                                                </div>
                                            </div>
                                            <div class="zdm-licence-step">
                                                <span class="zdm-licence-step__index">2</span>
                                                <div>
                                                    <span class="zdm-licence-step__title"><?= esc_html__('Paste the key above', 'zdm') ?></span>
                                                    <p><?= esc_html__('Insert the license key and click Activate to validate it instantly.', 'zdm') ?></p>
                                                </div>
                                            </div>
                                            <div class="zdm-licence-step">
                                                <span class="zdm-licence-step__index">3</span>
                                                <div>
                                                    <span class="zdm-licence-step__title"><?= esc_html__('Enjoy premium features', 'zdm') ?></span>
                                                    <p><?= esc_html__('Create unlimited download sets, advanced stats and branded buttons.', 'zdm') ?></p>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="zdm-licence-support">
                                            <span class="material-icons-round zdm-md-1">support_agent</span>
                                            <p><?= esc_html__('Need help locating your license? Contact our support team and we will resend it to you.', 'zdm') ?> <a href="<?= esc_url(defined('ZDM__SUPPORT_URL') ? ZDM__SUPPORT_URL : ZDM__PRO_URL) ?>" target="_blank"><?= esc_html__('Contact support', 'zdm') ?></a></p>
                                        </div>
                                    <?php } ?>
                                </div>

                                <?php if ($zdm_licence === 1) { ?>
                                    <div class="zdm-licence-meta">
                                        <div class="zdm-licence-meta-item">
                                            <span class="material-icons-round zdm-md-1 zdm-color-green">mail</span>
                                            <div>
                                                <span class="zdm-meta-label"><?= esc_html__('Licensed for', 'zdm') ?></span>
                                                <span class="zdm-meta-value"><?= esc_html($zdm_options['licence-email']); ?></span>
                                            </div>
                                        </div>
                                        <div class="zdm-licence-meta-item">
                                            <span class="material-icons-round zdm-md-1 zdm-color-green">event</span>
                                            <div>
                                                <span class="zdm-meta-label"><?= esc_html__('Purchased on', 'zdm') ?></span>
                                                <span class="zdm-meta-value"><?= esc_html(date('d.m.Y', strtotime($zdm_options['licence-purchase']))); ?></span>
                                            </div>
                                        </div>
                                        <div class="zdm-licence-meta-item">
                                            <span class="material-icons-round zdm-md-1 zdm-color-green">history</span>
                                            <div>
                                                <span class="zdm-meta-label"><?= esc_html__('Last validation', 'zdm') ?></span>
                                                <span class="zdm-meta-value"><?= esc_html($zdm_licence_last_checked); ?></span>
                                            </div>
                                        </div>
                                    </div>
                                <?php } ?>
                            </div>
                        </div>
                    </div>
                    <?php if ($zdm_licence === 1) { ?>
                        <script>
                            jQuery(document).ready(function($) {
                                $('#toggle-licence-key').on('click', function(e) {
                                    e.preventDefault();

                                    const $input = $('#licence-key');
                                    const $icon = $(this).find('span');

                                    if ($input.attr('type') === 'password') {
                                        $input.attr('type', 'text');
                                        $icon.text('visibility_off');
                                    } else {
                                        $input.attr('type', 'password');
                                        $icon.text('visibility');
                                    }
                                });
                            });
                        </script>
                    <?php } ?>
                </form>

                <form action="" method="post">

                    <?php
                    $zdm_preview_text_value = !empty($zdm_options['download-btn-text']) ? $zdm_options['download-btn-text'] : __('Download', 'zdm');
                    $zdm_preview_icon_value = !empty($zdm_options['download-btn-icon']) ? $zdm_options['download-btn-icon'] : 'none';
                    $zdm_preview_outline_value = $zdm_options['download-btn-outline'] === 'on' ? 'on' : 'off';
                    $zdm_preview_icon_position = !empty($zdm_options['download-btn-icon-position']) ? $zdm_options['download-btn-icon-position'] : 'left';
                    $zdm_preview_icon_only = $zdm_options['download-btn-icon-only'] === 'on' ? 'on' : 'off';
                    $zdm_preview_border_radius = $zdm_options['download-btn-border-radius'] === 'none' ? 0 : (int) $zdm_options['download-btn-border-radius'];
                    $zdm_preview_style_value = !empty($zdm_options['download-btn-style']) ? $zdm_options['download-btn-style'] : ZDM__DOWNLOAD_BTN_STYLE_VAL[0];

                    $zdm_preview_button_classes = [
                        'button',
                        'zdm-btn',
                        'zdm-preview-button',
                        'zdm-btn-style-' . $zdm_preview_style_value . ($zdm_preview_outline_value === 'on' ? '-outline' : '')
                    ];

                    if ($zdm_preview_border_radius !== 0) {
                        $zdm_preview_button_classes[] = 'zdm-btn-radius' . $zdm_preview_border_radius;
                    }

                    $zdm_preview_icon_classes = [
                        'material-icons-round',
                        'zdm-preview-icon'
                    ];

                    if ($zdm_preview_icon_only === 'on') {
                        $zdm_preview_icon_classes[] = 'zdm-btn-icon-only';
                    } else {
                        $zdm_preview_icon_classes[] = 'zdm-btn-icon';
                        if ($zdm_preview_icon_position === 'right') {
                            $zdm_preview_icon_classes[] = 'zdm-ml-2';
                        } else {
                            $zdm_preview_icon_classes[] = 'zdm-mr-2';
                        }
                    }

                    if ($zdm_preview_icon_value === 'none') {
                        $zdm_preview_icon_classes[] = 'is-hidden';
                    }

                    $zdm_preview_text_classes = ['zdm-preview-text'];

                    if ($zdm_preview_icon_only === 'on') {
                        $zdm_preview_text_classes[] = 'is-hidden';
                    }

                    $zdm_preview_button_class_attr = esc_attr(implode(' ', $zdm_preview_button_classes));
                    $zdm_preview_icon_class_attr = esc_attr(implode(' ', $zdm_preview_icon_classes));
                    $zdm_preview_text_class_attr = esc_attr(implode(' ', $zdm_preview_text_classes));
                    ?>

                    <div class="postbox" id="zdm-download-button">
                        <div class="inside">
                            <h3><?= esc_html__('Download button', 'zdm') ?></h3>
                            <hr>

                            <div class="zdm-download-preview-wrapper">
                                <div class="zdm-preview-heading">
                                    <?= esc_html__('Preview', 'zdm') ?>
                                </div>
                                <div class="zdm-preview-button-container">
                                    <button type="button" class="<?= $zdm_preview_button_class_attr ?>" data-style="<?= esc_attr($zdm_preview_style_value) ?>" data-outline="<?= esc_attr($zdm_preview_outline_value) ?>" data-icon-position="<?= esc_attr($zdm_preview_icon_position) ?>" data-icon-only="<?= esc_attr($zdm_preview_icon_only) ?>">
                                        <span class="<?= $zdm_preview_icon_class_attr ?>"><?php if ($zdm_preview_icon_value !== 'none') {
                                                                                            echo esc_html($zdm_preview_icon_value);
                                                                                        } ?></span>
                                        <span class="<?= $zdm_preview_text_class_attr ?>"><?= esc_html($zdm_preview_text_value) ?></span>
                                    </button>
                                </div>
                                <div class="zdm-preview-hint"><?= esc_html__('Changes update the preview instantly.', 'zdm') ?></div>
                            </div>

                            <table class="form-table zdm-download-form">
                                <tbody>
                                    <tr valign="top">
                                        <th scope="row"><?= esc_html__('Standard text', 'zdm') ?>:</th>
                                        <td valign="middle">
                                            <input type="text" name="download-btn-text" size="20" value="<?= esc_attr($zdm_options['download-btn-text']) ?>" class="regular-text">
                                            <div class="zdm-help-text"><?= esc_html__('This is the default text, but this can be changed individually per download.', 'zdm') ?></div>
                                        </td>
                                    </tr>
                                    <tr valign="top">
                                        <th scope="row"><?= esc_html__('Style', 'zdm') ?>:</th>
                                        <td valign="middle">
                                            <input type="hidden" name="download-btn-style" id="zdm-download-btn-style-input" value="<?= esc_attr($zdm_preview_style_value) ?>">
                                            <div class="zdm-icon-grid zdm-style-grid" role="radiogroup" aria-label="<?= esc_attr__('Style', 'zdm') ?>">
                                                <?php
                                                $zdm_btn_style = '';
                                                $zdm_download_btn_style = count(ZDM__DOWNLOAD_BTN_STYLE);

                                                for ($i = 0; $i < $zdm_download_btn_style; $i++) {
                                                    $style_value = ZDM__DOWNLOAD_BTN_STYLE_VAL[$i];
                                                    $style_label = ZDM__DOWNLOAD_BTN_STYLE[$i];
                                                    $is_selected = ($zdm_preview_style_value === $style_value);
                                                    $style_button_classes = 'zdm-icon-card zdm-style-card' . ($is_selected ? ' active' : '');

                                                    $zdm_btn_style .= '<button type="button" class="' . esc_attr($style_button_classes) . '" role="radio" data-style-value="' . esc_attr($style_value) . '" aria-checked="' . ($is_selected ? 'true' : 'false') . '">';
                                                    $zdm_btn_style .= '<span class="zdm-style-badge" data-style="' . esc_attr($style_value) . '"></span>';
                                                    $zdm_btn_style .= '<span class="zdm-icon-card-label">' . esc_html($style_label) . '</span>';
                                                    $zdm_btn_style .= '</button>';
                                                }

                                                echo $zdm_btn_style;
                                                ?>
                                            </div>
                                            <div class="zdm-help-text"><?= esc_html__('Choose from different button colors the default value for buttons.', 'zdm') ?></div>
                                        </td>
                                    </tr>
                                    <tr valign="top">
                                        <th scope="row"><?= esc_html__('Outline', 'zdm') ?>:</th>
                                        <td valign="middle">
                                            <label class="zdm-checkbox-modern">
                                                <input type="checkbox" name="download-btn-outline" <?php if ($zdm_options['download-btn-outline'] == 'on') {
                                                                                                            echo 'checked="checked"';
                                                                                                        } ?>>
                                                <span><?= esc_html__('Enable outline', 'zdm') ?></span>
                                            </label>
                                            <div class="zdm-help-text"><?= esc_html__('This option shows the button as a frame.', 'zdm') ?></div>
                                        </td>
                                    </tr>
                                    <tr valign="top">
                                        <th scope="row"><?= esc_html__('Round corners', 'zdm') ?>:</th>
                                        <td valign="middle">
                                            <input type="hidden" name="download-btn-border-radius" id="zdm-download-btn-radius-input" value="<?= esc_attr($zdm_options['download-btn-border-radius']) ?>">
                                            <div class="zdm-icon-grid zdm-radius-grid" role="radiogroup" aria-label="<?= esc_attr__('Round corners', 'zdm') ?>">
                                                <?php
                                                $zdm_radius_cards = '';
                                                $zdm_download_btn_border_radius = count(ZDM__DOWNLOAD_BTN_BORDER_RADIUS);

                                                for ($i = 0; $i < $zdm_download_btn_border_radius; $i++) {
                                                    $radius_value = ZDM__DOWNLOAD_BTN_BORDER_RADIUS_VAL[$i];
                                                    $radius_label = ZDM__DOWNLOAD_BTN_BORDER_RADIUS[$i];
                                                    $is_selected = ($zdm_options['download-btn-border-radius'] == $radius_value);
                                                    $radius_button_classes = 'zdm-icon-card zdm-radius-chip' . ($is_selected ? ' active' : '');
                                                    $preview_suffix = $radius_value === 'none' ? 'none' : (int) $radius_value;

                                                    $zdm_radius_cards .= '<button type="button" class="' . esc_attr($radius_button_classes) . '" role="radio" data-radius-value="' . esc_attr($radius_value) . '" aria-checked="' . ($is_selected ? 'true' : 'false') . '">';
                                                    $zdm_radius_cards .= '<span class="zdm-radius-chip-preview zdm-radius-chip-preview-' . esc_attr($preview_suffix) . '"></span>';
                                                    $zdm_radius_cards .= '<span class="zdm-icon-card-label">' . esc_html($radius_label) . '</span>';
                                                    $zdm_radius_cards .= '</button>';
                                                }

                                                echo $zdm_radius_cards;
                                                ?>
                                            </div>
                                            <div class="zdm-help-text"><?= esc_html__('If "none" is selected then the default value of your theme will be used, the button will remain square.', 'zdm') ?></div>
                                        </td>
                                    </tr>
                                    <tr valign="top">
                                        <th scope="row"><?= esc_html__('Icon', 'zdm') ?>:</th>
                                        <td valign="middle">
                                            <input type="hidden" name="download-btn-icon" id="zdm-download-btn-icon-input" value="<?= esc_attr($zdm_preview_icon_value) ?>">
                                            <div class="zdm-icon-grid" role="radiogroup" aria-label="<?= esc_attr__('Icon', 'zdm') ?>">
                                                <?php
                                                $zdm_btn_icons_count = count(ZDM__DOWNLOAD_BTN_ICON);
                                                for ($i = 0; $i < $zdm_btn_icons_count; $i++) {
                                                    $icon_value = ZDM__DOWNLOAD_BTN_ICON_VAL[$i];
                                                    $icon_label = ZDM__DOWNLOAD_BTN_ICON[$i];
                                                    $is_selected = ($zdm_preview_icon_value === $icon_value);
                                                    $icon_button_classes = 'zdm-icon-card' . ($is_selected ? ' active' : '');
                                                    echo '<button type="button" class="' . esc_attr($icon_button_classes) . '" role="radio" data-icon-value="' . esc_attr($icon_value) . '" aria-checked="' . ($is_selected ? 'true' : 'false') . '">';
                                                    if ($icon_value === 'none') {
                                                        echo '<span class="zdm-icon-placeholder">—</span>';
                                                    } else {
                                                        echo '<span class="material-icons-round">' . esc_html($icon_value) . '</span>';
                                                    }
                                                    echo '<span class="zdm-icon-card-label">' . esc_html($icon_label) . '</span>';
                                                    echo '</button>';
                                                }
                                                ?>
                                            </div>
                                            <div class="zdm-help-text"><?= esc_html__('Choose the default icon for your download button.', 'zdm') ?></div>
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
                                            <div class="zdm-help-text"><?= esc_html__('Choose the position of the icon.', 'zdm') ?></div>
                                        </td>
                                    </tr>
                                    <tr valign="top">
                                        <th scope="row"><?= esc_html__('Only icon', 'zdm') ?>:</th>
                                        <td valign="middle">
                                            <label class="zdm-checkbox-modern">
                                                <input type="checkbox" name="download-btn-icon-only" <?php if ($zdm_options['download-btn-icon-only'] == 'on') {
                                                                                                            echo 'checked="checked"';
                                                                                                        } ?>>
                                                <span><?= esc_html__('Show only the icon without text.', 'zdm') ?></span>
                                            </label>
                                            <div class="zdm-help-text"><?= esc_html__('This option displays only the icon without text.', 'zdm') ?></div>
                                        </td>
                                    </tr>
                                </tbody>
                            </table>
                        </div>
                    </div>

                    <script>
                        document.addEventListener('DOMContentLoaded', function() {
                            const downloadBox = document.querySelector('#zdm-download-button');
                            if (!downloadBox) {
                                return;
                            }

                            const previewButton = downloadBox.querySelector('.zdm-preview-button');
                            if (!previewButton) {
                                return;
                            }

                            const previewText = previewButton.querySelector('.zdm-preview-text');
                            const previewIcon = previewButton.querySelector('.zdm-preview-icon');

                            const textInput = downloadBox.querySelector('input[name="download-btn-text"]');
                            const styleHiddenInput = downloadBox.querySelector('#zdm-download-btn-style-input');
                            const styleCards = downloadBox.querySelectorAll('.zdm-style-card');
                            const outlineCheckbox = downloadBox.querySelector('input[name="download-btn-outline"]');
                            const radiusHiddenInput = downloadBox.querySelector('#zdm-download-btn-radius-input');
                            const radiusChips = downloadBox.querySelectorAll('.zdm-radius-chip');
                            const iconHiddenInput = downloadBox.querySelector('#zdm-download-btn-icon-input');
                            const iconCards = downloadBox.querySelectorAll('.zdm-icon-card[data-icon-value]');
                            const iconPositionSelect = downloadBox.querySelector('select[name="download-btn-icon-position"]');
                            const iconOnlyCheckbox = downloadBox.querySelector('input[name="download-btn-icon-only"]');
                            const fallbackText = '<?= esc_js(__('Download', 'zdm')) ?>';
                            const defaultStyleValue = '<?= esc_js($zdm_preview_style_value) ?>';

                            function removeClassesByPrefix(element, prefix) {
                                if (!element) {
                                    return;
                                }
                                const classes = Array.from(element.classList);
                                classes.forEach(function(cls) {
                                    if (cls.startsWith(prefix)) {
                                        element.classList.remove(cls);
                                    }
                                });
                            }

                            function updateStyleCardState(selectedValue) {
                                styleCards.forEach(function(card) {
                                    const isActive = card.getAttribute('data-style-value') === selectedValue;
                                    card.classList.toggle('active', isActive);
                                    card.setAttribute('aria-checked', isActive ? 'true' : 'false');
                                });
                            }

                            function updateRadiusChipState(selectedValue) {
                                radiusChips.forEach(function(chip) {
                                    const isActive = chip.getAttribute('data-radius-value') === selectedValue;
                                    chip.classList.toggle('active', isActive);
                                    chip.setAttribute('aria-checked', isActive ? 'true' : 'false');
                                });
                            }

                            function updateIconCardState(selectedValue) {
                                iconCards.forEach(function(card) {
                                    const isActive = card.getAttribute('data-icon-value') === selectedValue;
                                    card.classList.toggle('active', isActive);
                                    card.setAttribute('aria-checked', isActive ? 'true' : 'false');
                                });
                            }

                            function updateButtonPreview() {
                                const textValue = textInput ? textInput.value.trim() : '';
                                if (previewText) {
                                    previewText.textContent = textValue !== '' ? textValue : fallbackText;
                                }

                                const styleValue = styleHiddenInput && styleHiddenInput.value ? styleHiddenInput.value : defaultStyleValue;
                                previewButton.dataset.style = styleValue;

                                const outlineValue = outlineCheckbox && outlineCheckbox.checked ? 'on' : 'off';
                                previewButton.dataset.outline = outlineValue;

                                removeClassesByPrefix(previewButton, 'zdm-btn-style-');
                                const styleClass = 'zdm-btn-style-' + styleValue + (outlineValue === 'on' ? '-outline' : '');
                                previewButton.classList.add(styleClass);

                                const radiusValue = radiusHiddenInput && radiusHiddenInput.value ? radiusHiddenInput.value : 'none';
                                updateRadiusChipState(radiusValue);
                                removeClassesByPrefix(previewButton, 'zdm-btn-radius');
                                if (radiusValue !== 'none') {
                                    previewButton.classList.add('zdm-btn-radius' + radiusValue);
                                }

                                const iconValue = iconHiddenInput ? iconHiddenInput.value : 'none';
                                if (previewIcon) {
                                    previewIcon.textContent = iconValue && iconValue !== 'none' ? iconValue : '';
                                    previewIcon.classList.toggle('is-hidden', !iconValue || iconValue === 'none');
                                    previewIcon.classList.remove('zdm-btn-icon', 'zdm-btn-icon-only', 'zdm-mr-2', 'zdm-ml-2');
                                }

                                const iconPosition = iconPositionSelect ? iconPositionSelect.value : 'left';
                                previewButton.dataset.iconPosition = iconPosition;

                                const iconOnly = iconOnlyCheckbox && iconOnlyCheckbox.checked ? 'on' : 'off';
                                previewButton.dataset.iconOnly = iconOnly;

                                if (previewIcon) {
                                    if (iconOnly === 'on') {
                                        previewIcon.classList.add('zdm-btn-icon-only');
                                    } else {
                                        previewIcon.classList.add('zdm-btn-icon');
                                        if (!previewIcon.classList.contains('is-hidden')) {
                                            previewIcon.classList.add(iconPosition === 'right' ? 'zdm-ml-2' : 'zdm-mr-2');
                                        }
                                    }
                                }

                                if (previewIcon && previewText) {
                                    if (previewIcon.parentNode === previewButton) {
                                        previewButton.removeChild(previewIcon);
                                    }
                                    if (previewText.parentNode === previewButton) {
                                        previewButton.removeChild(previewText);
                                    }

                                    if (iconOnly === 'on') {
                                        previewButton.appendChild(previewIcon);
                                        previewButton.appendChild(previewText);
                                    } else if (iconPosition === 'right') {
                                        previewButton.appendChild(previewText);
                                        previewButton.appendChild(previewIcon);
                                    } else {
                                        previewButton.appendChild(previewIcon);
                                        previewButton.appendChild(previewText);
                                    }
                                }

                                if (previewText) {
                                    previewText.classList.toggle('is-hidden', iconOnly === 'on');
                                }
                            }

                            if (textInput) {
                                textInput.addEventListener('input', updateButtonPreview);
                            }
                            if (outlineCheckbox) {
                                outlineCheckbox.addEventListener('change', updateButtonPreview);
                            }
                            if (iconPositionSelect) {
                                iconPositionSelect.addEventListener('change', updateButtonPreview);
                            }
                            if (iconOnlyCheckbox) {
                                iconOnlyCheckbox.addEventListener('change', updateButtonPreview);
                            }

                            radiusChips.forEach(function(chip) {
                                chip.addEventListener('click', function() {
                                    const value = this.getAttribute('data-radius-value');
                                    if (radiusHiddenInput) {
                                        radiusHiddenInput.value = value;
                                    }
                                    updateRadiusChipState(value);
                                    updateButtonPreview();
                                });

                                chip.addEventListener('keydown', function(event) {
                                    if (event.key === ' ' || event.key === 'Enter') {
                                        event.preventDefault();
                                        this.click();
                                    }
                                });
                            });

                            styleCards.forEach(function(card) {
                                card.addEventListener('click', function() {
                                    const value = this.getAttribute('data-style-value');
                                    if (styleHiddenInput) {
                                        styleHiddenInput.value = value;
                                    }
                                    updateStyleCardState(value);
                                    updateButtonPreview();
                                });
                            });

                            iconCards.forEach(function(card) {
                                card.addEventListener('click', function() {
                                    const value = this.getAttribute('data-icon-value');
                                    if (iconHiddenInput) {
                                        iconHiddenInput.value = value;
                                    }
                                    updateIconCardState(value);
                                    updateButtonPreview();
                                });
                            });

                            updateStyleCardState(styleHiddenInput && styleHiddenInput.value ? styleHiddenInput.value : defaultStyleValue);
                            updateRadiusChipState(radiusHiddenInput && radiusHiddenInput.value ? radiusHiddenInput.value : 'none');
                            updateIconCardState(iconHiddenInput ? iconHiddenInput.value : 'none');
                            updateButtonPreview();
                        });
                    </script>

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
                                            <?= esc_html__('Defines how the file list is displayed.', 'zdm') ?>
                                        </td>
                                    </tr>
                                    <tr valign="top">
                                        <th scope="row"><?= esc_html__('Bold text', 'zdm') ?>:</th>
                                        <td valign="middle">
                                            <input type="checkbox" name="list-bold" <?php if ($zdm_options['list-bold'] == 'on') {
                                                                                        echo 'checked="checked"';
                                                                                    } ?>>
                                            <?= esc_html__('Displays list item text in bold.', 'zdm') ?>
                                        </td>
                                    </tr>
                                    <tr valign="top">
                                        <th scope="row"><?= esc_html__('List items as links', 'zdm') ?>:</th>
                                        <td valign="middle">
                                            <input type="checkbox" name="list-links" <?php if ($zdm_options['list-links'] == 'on') {
                                                                                            echo 'checked="checked"';
                                                                                        } ?>>
                                            <?= esc_html__('Makes each list item a clickable link.', 'zdm') ?>
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
                                            <div class="zdm-help-text"><?= esc_html__('Defines how many recent downloads are shown in the statistics. This setting applies separately to files and archives.', 'zdm') ?></div>
                                            <br>
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
                                            <div class="zdm-help-text"><?= esc_html__('Number of recent file downloads displayed in the Statistics tab on the file detail page.', 'zdm') ?></div>
                                            <br>
                                            <input type="number" name="stat-single-archive-last-limit" min="1" max="500" value="<?= esc_attr($zdm_options['stat-single-archive-last-limit']) ?>" <?php if ($zdm_licence === 0) {
                                                                                                                                                                                                        echo ' disabled';
                                                                                                                                                                                                    } ?>>
                                            <span class="material-icons-outlined zdm-md-1">info</span> <?= esc_html__('Setting for archives', 'zdm') ?>
                                            <br>
                                            <div class="zdm-help-text"><?= esc_html__('Number of recent archive downloads displayed in the Statistics tab on the archive detail page.', 'zdm') ?></div>
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
                                            <div class="zdm-help-text"><?= esc_html__('Restricts uploads to safe file types. Disable only if you are sure uploaded files cannot cause harm.', 'zdm') ?></div>
                                        </td>
                                    </tr>
                                    <tr valign="top">
                                        <th scope="row"><?= esc_html__('Maximum upload size', 'zdm') ?>:</th>
                                        <td valign="middle">
                                            <input type="number" name="max-upload-size-in-mb" min="1" max="50000" value="<?= esc_attr($zdm_options['max-upload-size-in-mb']) ?>">
                                            <?= esc_html__('Maximum file size in MB that can be uploaded.', 'zdm') ?>
                                        </td>
                                    </tr>
                                    <tr valign="top">
                                        <th scope="row"><?= esc_html__('Display PDFs in browser', 'zdm') ?>:</th>
                                        <td valign="middle">
                                            <input type="checkbox" name="file-open-in-browser-pdf" <?php if ($zdm_options['file-open-in-browser-pdf'] == 'on') {
                                                                                                        echo 'checked="checked"';
                                                                                                    } ?>>
                                            <?= esc_html__('Opens PDF files directly in the browser and exposes the direct file URL. Enable only if the URL may be publicly visible.', 'zdm') ?>
                                        </td>
                                    </tr>
                                    <tr valign="top">
                                        <th scope="row"><?= esc_html__('Censor the IP address', 'zdm') ?>:</th>
                                        <td valign="middle">
                                            <input type="checkbox" name="secure-ip" <?php if ($zdm_options['secure-ip'] == 'on') {
                                                                                        echo 'checked="checked"';
                                                                                    } ?>>
                                            <?= esc_html__('Anonymizes the IP address in the download log.', 'zdm') ?>
                                        </td>
                                    </tr>
                                    <tr valign="top">
                                        <th scope="row"><?= esc_html__('Allow duplicates', 'zdm') ?>:</th>
                                        <td valign="middle">
                                            <input type="checkbox" name="duplicate-file" <?php if ($zdm_options['duplicate-file'] == 'on') {
                                                                                                echo 'checked="checked"';
                                                                                            } ?>>
                                            <?= esc_html__('Allows uploading files that already exist.', 'zdm') ?>
                                        </td>
                                    </tr>
                                    <tr valign="top">
                                        <th scope="row"><?= esc_html__('Hide HTML id Attribute', 'zdm') ?>:</th>
                                        <td valign="middle">
                                            <input type="checkbox" name="hide-html-id" <?php if ($zdm_options['hide-html-id'] == 'on') {
                                                                                            echo 'checked="checked"';
                                                                                        } ?>>
                                            <?= esc_html__('Removes the HTML ID attribute when rendering button, audio, and video.', 'zdm') ?>
                                        </td>
                                    </tr>
                                    <tr valign="top">
                                        <th scope="row"><?= esc_html__('Log', 'zdm') ?>:</th>
                                        <td valign="middle">
                                            <a href="admin.php?page=<?= ZDM__SLUG ?>-log"><?= esc_html__('Show full log.', 'zdm') ?></a>
                                        </td>
                                    </tr>
                                    <tr valign="top">
                                        <th scope="row"><?= esc_html__('Download folder token', 'zdm') ?>:</th>
                                        <td valign="middle">
                                            <input type="text" value="<?= $zdm_options['download-folder-token'] ?>" size="50%" disabled>&nbsp;
                                            <a href="admin.php?page=<?= ZDM__SLUG ?>-settings&new_download_folder_token=true&nonce=<?= wp_create_nonce('new_download_folder_token') ?>" class="button button-secondary"><?= esc_html__('Generate new token', 'zdm') ?></a>
                                            <div class="zdm-help-text"><?= esc_html__('This token is used to generate the internal download folder name', 'zdm') ?>: <code>/z-downloads-<?= $zdm_options['download-folder-token'] ?>/</code></div>
                                            <div class="zdm-help-text"><?= esc_html__('The folder itself is not publicly accessible. Files in this folder are normally delivered only through the plugin.', 'zdm') ?></div>
                                            <div class="zdm-help-text"><?= esc_html__('Important: If you enable “Display PDFs in browser”, the full URL to those PDFs will be visible, which also exposes the folder path.', 'zdm') ?></div>
                                            <div class="zdm-help-text"><?= esc_html__('You can safely generate a new token at any time if you want to change the path.', 'zdm') ?></div>
                                        </td>
                                    </tr>
                                    <tr valign="top">
                                        <th scope="row"><?= esc_html__('Reset settings', 'zdm') ?>:</th>
                                        <td valign="middle">
                                            <a href="admin.php?page=<?= ZDM__SLUG ?>-settings&reset_settings=true&nonce=<?= wp_create_nonce('reset-settings') ?>" class="button button-secondary"><?= esc_html__('Reset settings', 'zdm') ?></a>
                                            <div class="zdm-help-text"><?= esc_html__('Resets all plugin settings to their defaults. Premium license remains active.', 'zdm') ?></div>
                                            <div class="zdm-help-text"><?= esc_html__('The download folder token is also regenerated.', 'zdm') ?></div>
                                        </td>
                                    </tr>
                                </tbody>
                            </table>
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
