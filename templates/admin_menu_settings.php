<?php

// Abort by direct access
if (!defined('ABSPATH')) {
    die;
}

if (current_user_can(ZDM__STANDARD_USER_ROLE)) {

    $zdm_options = get_option('zdm_options');
    $zdm_status = '';
    $zdm_note = '';
    $zdm_error = '';

    ////////////////////
    // Update license key
    ////////////////////
    if (isset($_POST['licence_submit']) && wp_verify_nonce($_POST['nonce'], 'update-license')) {

        // license
        // Update license key
        if (ZDMCore::licence_array(trim(sanitize_text_field($_POST['licence-key'])))['success'] === true) {

            $licence_array = $this->licence_array(trim(sanitize_text_field($_POST['licence-key'])));

            $zdm_options['licence-key'] = trim(sanitize_text_field($_POST['licence-key']));
            $zdm_options['licence-email'] = $licence_array['purchase']['email'];
            $zdm_options['licence-purchase'] = $licence_array['purchase']['created_at'];
            $zdm_options['licence-product-name'] = $licence_array['purchase']['product_name'];
            $zdm_options['licence-time'] = time();

            if (add_option('zdm_options', $zdm_options) === FALSE) {
                update_option('zdm_options', $zdm_options);
                $zdm_status = 1;
            }

            $zdm_options = get_option('zdm_options');
        } else {
            // Remove license key

            $zdm_options['licence-key'] = '';

            if (add_option('zdm_options', $zdm_options) === FALSE) {
                update_option('zdm_options', $zdm_options);
                $zdm_status = 1;
            }
        }

        ZDMCore::licence();

        $zdm_options = get_option('zdm_options');

        // Log
        ZDMCore::log('update licence');
    }

    // Remove license key
    if (isset($_GET['licence_delete']) && wp_verify_nonce($_GET['nonce'], 'licence-delete')) {

        $zdm_options['licence-key'] = '';

        if (add_option('zdm_options', $zdm_options) === FALSE) {
            update_option('zdm_options', $zdm_options);
            $zdm_status = 1;
        }

        // Log
        ZDMCore::log('delete licence');

        // Reload the settings page
        $zdm_settings_url = 'admin.php?page=' . ZDM__SLUG . '-settings';
        wp_redirect($zdm_settings_url);
        exit;
    }

    if (ZDMCore::licence()) {
        $zdm_licence = 1;
    } else {
        $zdm_licence = 0;
    }

    ////////////////////
    // Update data
    ////////////////////
    if (isset($_POST['submit']) && wp_verify_nonce($_POST['nonce'], 'einstellungen-speichern')) {

        // General

        // Download button text
        $zdm_options['download-btn-text'] = trim(sanitize_text_field($_POST['download-btn-text']));
        // Download button style
        $zdm_options['download-btn-style'] = trim(sanitize_text_field($_POST['download-btn-style']));
        // Download button outline
        $zdm_options['download-btn-outline'] = trim(sanitize_text_field($_POST['download-btn-outline']));
        // Download button round corners
        $zdm_options['download-btn-border-radius'] = trim(sanitize_text_field($_POST['download-btn-border-radius']));
        // Download button icon
        $zdm_options['download-btn-icon'] = trim(sanitize_text_field($_POST['download-btn-icon']));
        // Download button icon only
        $zdm_options['download-btn-icon-only'] = trim(sanitize_text_field($_POST['download-btn-icon-only']));

        // Statistics

        $zdm_options['stat-single-file-last-limit'] = trim(sanitize_text_field($_POST['stat-single-file-last-limit']));
        $zdm_options['stat-single-archive-last-limit'] = trim(sanitize_text_field($_POST['stat-single-archive-last-limit']));

        // Extended

        // Direct url to PDF
        $zdm_options['file-open-in-browser-pdf'] = trim(sanitize_text_field($_POST['file-open-in-browser-pdf']));
        // Censor IP address
        $zdm_options['secure-ip'] = trim(sanitize_text_field($_POST['secure-ip']));
        // Allow file duplicates
        $zdm_options['duplicate-file'] = trim(sanitize_text_field($_POST['duplicate-file']));

        // Update options
        if (add_option('zdm_options', $zdm_options) === FALSE) {
            update_option('zdm_options', $zdm_options);
            $zdm_status = 1;
        }// end if add_option() === FALSE

        $zdm_options = get_option('zdm_options');

        // Log
        ZDMCore::log('update settings', serialize($_POST));
    }
    
    ////////////////////
    // Generate new download folder token
    ////////////////////
    if (isset($_GET['new_download_folder_token']) && wp_verify_nonce($_GET['nonce'], 'new_download_folder_token')) {

        if ($_GET['new_download_folder_token'] == 'true') {

            if (get_option('zdm_options')) {

                $zdm_new_download_folder_token = md5(uniqid(rand(), true));
                rename(ZDM__DOWNLOADS_PATH, wp_upload_dir()['basedir'] . "/z-downloads-" . $zdm_new_download_folder_token);
                $zdm_options['download-folder-token'] = $zdm_new_download_folder_token;
                ZDMCore::log('download-folder-token', $zdm_options['download-folder-token']);

                update_option('zdm_options', $zdm_options);
                $zdm_options = get_option('zdm_options');

                // Reload the settings page
                $zdm_settings_url = 'admin.php?page=' . ZDM__SLUG . '-settings';
                wp_redirect($zdm_settings_url);
                exit;
            }
        }
    }

    ////////////////////
    // Reset settings
    ////////////////////
    if (isset($_GET['reset_settings']) && wp_verify_nonce($_GET['nonce'], 'reset-settings')) {

        if ($_GET['reset_settings'] == 'true') {

            // Keep download token folder
            $zdm_download_folder_token_temp = $zdm_options['download-folder-token'];

            flush_rewrite_rules();

            if (get_option('zdm_options')) {
                update_option('zdm_options', ZDM__OPTIONS);
                $zdm_options = get_option('zdm_options');
                
                // Generate new download folder token
                $zdm_new_download_folder_token = md5(uniqid(rand(), true));
                rename(ZDM__DOWNLOADS_PATH, wp_upload_dir()['basedir'] . "/z-downloads-" . $zdm_new_download_folder_token);
                $zdm_options['download-folder-token'] = $zdm_new_download_folder_token;
                update_option('zdm_options', $zdm_options);
                $zdm_options = get_option('zdm_options');

                // Log
                ZDMCore::log('reset settings');

                // Reload the settings page
                $zdm_settings_url = 'admin.php?page=' . ZDM__SLUG . '-settings';
                wp_redirect($zdm_settings_url);
                exit;
            }
        }
    }

    ////////////////////
    // Erase all data
    ////////////////////
    if (isset($_GET['delete_data']) && wp_verify_nonce($_GET['nonce'], 'delete-all-data')) {

        if ($_GET['delete_data'] == 'true') {

            ZDMCore::delete_all_data();
        }
    }

    ?>

    <div class="wrap">
        <h1 class="wp-heading-inline"><?=esc_html__('Settings', 'zdm')?></h1>

        <hr class="wp-header-end">

            <?php
            if ($zdm_status != '') {

                echo '<div class="notice notice-success">';
                echo '<br><b>' . esc_html__('Settings updated!', 'zdm') . '</b><br><br>';
                echo '</div>';
            }// end if $zdm_status != ''

            if ($zdm_note != '') {

                echo '<div class="notice notice-success">';
                echo '<br><b>' . $zdm_note . '</b><br><br>';
                echo '</div>';
            }// end if $zdm_note != ''

            if ($zdm_error != '') {

                echo '<div class="notice notice-warning">';
                echo '<br><b>' . $zdm_error . '</b><br><br>';
                echo '</div>';
            }// end if $zdm_error != ''

            if (isset($_GET['delete_data']) && wp_verify_nonce($_GET['nonce'], 'delete-all-data')) { // Nur Hinweis dass die Daten gelöscht wurden anzeigen
                ?>
                <div class="postbox">
                    <div class="inside">
                        <h3 class="zdm-color-green"><ion-icon name="checkmark"></ion-icon> <?=esc_html__('All data was deleted successfully!', 'zdm')?></h3>
                        <p><?=esc_html__('All your uploaded files, all archives in the cache and all database entries from', 'zdm')?> <?=ZDM__TITLE?> <?=esc_html__('have been irrevocably deleted.', 'zdm')?></p>
                        <p><?=esc_html__('You can now deactivate and uninstall the plugin in the plugin overview or you upload new files and start fresh.', 'zdm')?></p>
                        <a href="admin.php?page=<?=ZDM__SLUG?>-settings" class="button button-secondary"><?=esc_html__('Back to settings', 'zdm')?></a>
                    </div>
                </div>
                <?php
            } else { // Normal view of the settings
                ?>

            <form action="" method="post">
                <input type="hidden" name="nonce" value="<?=wp_create_nonce('update-license')?>">
                <div class="postbox">
                    <div class="inside">
                        <h3><?php if ($zdm_licence === 1) { ?><?=$zdm_options['licence-product-name'];?> <?=esc_html__('is activated', 'zdm')?><?php } else { echo ZDM__PRO; } ?></h3>
                        <hr>
                        <table class="form-table">
                            <tbody>
                                <tr valign="top">
                                    <th scope="row"><?=ZDM__PRO?> <?=esc_html__('license key', 'zdm')?>:</th>
                                    <td valign="middle">
                                        <?php if ($zdm_licence === 1) { ?><ion-icon name="checkmark-circle" class="zdm-color-green"></ion-icon>&nbsp;<?php } ?>
                                        <input type="text" name="licence-key" size="50%" value="<?= esc_attr($zdm_options['licence-key']); ?>">&nbsp;
                                        <?php if ($zdm_licence === 1) {
                                            ?>
                                            <input class="button-primary" type="submit" name="licence_submit" value="<?=esc_html__('Update', 'zdm')?>">
                                            <?php
                                        } else {
                                            ?>
                                            <input class="button-primary" type="submit" name="licence_submit" value="<?=esc_html__('Activate', 'zdm')?>">
                                            <?php
                                        }

                                        if ($zdm_licence === 1) {
                                            ?>
                                            <br /><br />
                                            <a href="admin.php?page=<?=ZDM__SLUG?>-settings&licence_delete=true&nonce=<?=wp_create_nonce('licence-delete')?>" class="button button-secondary"><?=esc_html__('Remove license key', 'zdm')?></a>
                                            <?php
                                        }
                                        if ($zdm_licence === 0) { ?>
                                            <div class="zdm-help-text"><?=esc_html__('Benefit from the', 'zdm')?> <?=ZDM__PRO?>-<?=esc_html__('features and unlock all possibilities', 'zdm')?> <?=ZDM__TITLE?> <?=esc_html__('free, more info', 'zdm')?>: <a href="<?=ZDM__PRO_URL?>" target="_blank" title="code.urban-base.net"><?=ZDM__TITLE;?> <?=ZDM__PRO?></a></div>
                                            <?php
                                        } ?>
                                    </td>
                                </tr>
                                <?php if ($zdm_licence === 1) { ?>
                                <tr valign="top">
                                    <th scope="row"><?=esc_html__('Licensed for', 'zdm')?>:</th>
                                    <td valign="middle">
                                        <ion-icon name="checkmark-circle" class="zdm-color-green"></ion-icon>&nbsp;<?=$zdm_options['licence-email'];?>
                                    </td>
                                </tr>
                                <tr valign="top">
                                    <th scope="row"><?=esc_html__('Purchased', 'zdm')?>:</th>
                                    <td valign="middle">
                                        <ion-icon name="checkmark-circle" class="zdm-color-green"></ion-icon>&nbsp;<?=date("d.m.Y", strtotime($zdm_options['licence-purchase']))?>
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
                        <h3><?=esc_html__('Download button', 'zdm')?></h3>
                        <hr>
                        <table class="form-table">
                            <tbody>
                                <tr valign="top">
                                    <th scope="row"><?=esc_html__('Standard text', 'zdm')?>:</th>
                                    <td valign="middle">
                                    <input type="text" name="download-btn-text" size="15" value="<?=esc_attr($zdm_options['download-btn-text'])?>">
                                    <br>
                                    <div class="zdm-help-text"><?=esc_html__('This is the default text, but this can be changed individually per download.', 'zdm')?></div>
                                    </td>
                                </tr>
                                <tr valign="top">
                                    <th scope="row"><?=esc_html__('Style', 'zdm')?>:</th>
                                    <td valign="middle">
                                        <select name="download-btn-style">
                                            <?php
                                            $zdm_btn_style = '';

                                            for( $i = 0; $i < count(ZDM__DOWNLOAD_BTN_STYLE); $i++ ) {
                                            $zdm_btn_style    .= '<option value="' . ZDM__DOWNLOAD_BTN_STYLE_VAL[$i] . '" ' 
                                                                . ( $zdm_options['download-btn-style'] == ZDM__DOWNLOAD_BTN_STYLE_VAL[$i] ? 'selected="selected"' : '' ) . '>' 
                                                                . ZDM__DOWNLOAD_BTN_STYLE[$i] 
                                                                . '</option>';
                                            }
                                            
                                            echo $zdm_btn_style;
                                            ?>
                                        </select>
                                        &nbsp;&nbsp;&nbsp;
                                        <span class="zdm-color-bg-<?=$zdm_options['download-btn-style']?>">&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</span>
                                        <br>
                                        <div class="zdm-help-text"><?=esc_html__('Choose from different button colors the default value for buttons.', 'zdm')?></div>
                                    </td>
                                </tr>
                                <tr valign="top">
                                    <th scope="row"></th>
                                    <td valign="middle">
                                        <input type="checkbox" name="download-btn-outline" <?php if($zdm_options['download-btn-outline'] == 'on'){ echo 'checked="checked"'; } ?> >
                                        <?=esc_html__('Outline', 'zdm')?>
                                        <br>
                                        <div class="zdm-help-text"><?=esc_html__('This option shows the button as a frame.', 'zdm')?></div>
                                    </td>
                                </tr>
                                <tr valign="top">
                                    <th scope="row"><?=esc_html__('Round corners', 'zdm')?>:</th>
                                    <td valign="middle">
                                        <select name="download-btn-border-radius">
                                            <?php
                                            $zdm_btn_border = '';

                                            for( $i = 0; $i < count(ZDM__DOWNLOAD_BTN_BORDER_RADIUS); $i++ ) {
                                                $zdm_btn_border .= '<option value="' . ZDM__DOWNLOAD_BTN_BORDER_RADIUS_VAL[$i] . '" ' 
                                                                . ( $zdm_options['download-btn-border-radius'] == ZDM__DOWNLOAD_BTN_BORDER_RADIUS_VAL[$i] ? 'selected="selected"' : '' ) . '>' 
                                                                . ZDM__DOWNLOAD_BTN_BORDER_RADIUS[$i] 
                                                                . '</option>';
                                            }
                                            
                                            echo $zdm_btn_border;
                                            ?>
                                        </select><br>
                                        <div class="zdm-help-text"><?=esc_html__('If "none" is selected then the default value of your theme will be used, the button will remain square.', 'zdm')?></div>
                                    </td>
                                </tr>
                                <tr valign="top">
                                    <th scope="row"><?=esc_html__('Icon', 'zdm')?>:</th>
                                    <td valign="middle">
                                        <?php
                                        $zdm_btn_icons_count = count(ZDM__DOWNLOAD_BTN_ICON);
                                        $zdm_btn_icons_count_ceil = ceil(($zdm_btn_icons_count)/2);
                                        ?>
                                        <table>
                                            <tr>
                                                <fieldset>
                                                <td>
                                                    <?php
                                                    $zdm_btn_icon_example_left = '';
                                                    for ($i=0; $i < $zdm_btn_icons_count_ceil; $i++) {
                                                        $zdm_btn_icon_example_left .= '<input type="radio" name="download-btn-icon" value="' . ZDM__DOWNLOAD_BTN_ICON_VAL[$i] . '" ';
                                                        $zdm_btn_icon_example_left .= ( $zdm_options['download-btn-icon'] == ZDM__DOWNLOAD_BTN_ICON_VAL[$i] ? 'checked="checked"' : '' ) . '>';
                                                        $zdm_btn_icon_example_left .= '<ion-icon name="' . ZDM__DOWNLOAD_BTN_ICON_VAL[$i] . '" class="zdm-icon zdm-color-primary"></ion-icon>' . ZDM__DOWNLOAD_BTN_ICON[$i] . '</input><br />';
                                                    }
                                                    echo $zdm_btn_icon_example_left;
                                                    ?>
                                                </td>
                                                <td>
                                                    <?php
                                                    $zdm_btn_icon_example_right = '';
                                                    for ($i=$zdm_btn_icons_count_ceil; $i < $zdm_btn_icons_count; $i++) {
                                                        $zdm_btn_icon_example_right .= '<input type="radio" name="download-btn-icon" value="' . ZDM__DOWNLOAD_BTN_ICON_VAL[$i] . '" ';
                                                        $zdm_btn_icon_example_right .= ( $zdm_options['download-btn-icon'] == ZDM__DOWNLOAD_BTN_ICON_VAL[$i] ? 'checked="checked"' : '' ) . '>';
                                                        $zdm_btn_icon_example_right .= '<ion-icon name="' . ZDM__DOWNLOAD_BTN_ICON_VAL[$i] . '" class="zdm-icon zdm-color-primary"></ion-icon>' . ZDM__DOWNLOAD_BTN_ICON[$i] . '</input><br />';
                                                    }
                                                    echo $zdm_btn_icon_example_right;
                                                    ?>
                                                </td>
                                            </fieldset>
                                            </tr>
                                        </table>
                                    </td>
                                </tr>
                                <tr valign="top">
                                    <th scope="row"></th>
                                    <td valign="middle">
                                        <input type="checkbox" name="download-btn-icon-only" <?php if($zdm_options['download-btn-icon-only'] == 'on'){ echo 'checked="checked"'; } ?> >
                                        <?=esc_html__('Only icon', 'zdm')?>
                                        <br>
                                        <div class="zdm-help-text"><?=esc_html__('This option displays only the icon without text.', 'zdm')?></div>
                                    </td>
                                </tr>
                            </tbody>
                        </table>
                    </div>
                </div>

                <div class="postbox" id="zdm-stat">
                    <div class="inside">
                        <h3><?=esc_html__('Statistics', 'zdm')?></h3>
                        <hr>
                        <table class="form-table">
                            <tbody>
                                <tr valign="top">
                                        <th scope="row">
                                            <?=esc_html__('Last downloads limit', 'zdm')?>:
                                            <?php
                                            if ($zdm_licence === 0) {
                                                echo '<br><a href="' . ZDM__PRO_URL . '" target="_blank" title="code.urban-base.net">' . ZDM__PRO . ' ' . esc_html__('feature', 'zdm') . ' </a>';
                                            }
                                            ?>
                                        </th>
                                        <td valign="middle">
                                            <?php if ($zdm_licence === 0) {
                                                ?>
                                                <input type="hidden" name="stat-single-file-last-limit" value="<?=esc_attr($zdm_options['stat-single-file-last-limit'])?>">
                                                <input type="hidden" name="stat-single-archive-last-limit" value="<?=esc_attr($zdm_options['stat-single-archive-last-limit'])?>">
                                                <?php
                                            }
                                            ?>
                                            <input type="number" name="stat-single-file-last-limit" size="5" min="1" max="500" value="<?=esc_attr($zdm_options['stat-single-file-last-limit'])?>"<?php if ($zdm_licence === 0) { echo ' disabled'; } ?> > 
                                            <ion-icon name="information-circle-outline"></ion-icon> <?=esc_html__('Setting for files', 'zdm')?>
                                            <br>
                                            <div class="zdm-help-text"><?=esc_html__('Determine the number of recent downloads that is displayed on the file details page in the Statistics tab.', 'zdm')?></div>
                                            <br>
                                            <input type="number" name="stat-single-archive-last-limit" size="5" min="1" max="500" value="<?=esc_attr($zdm_options['stat-single-archive-last-limit'])?>"<?php if ($zdm_licence === 0) { echo ' disabled'; } ?> >
                                            <ion-icon name="information-circle-outline"></ion-icon> <?=esc_html__('Setting for archives', 'zdm')?>
                                            <br>
                                            <div class="zdm-help-text"><?=esc_html__('Determine the number of recent downloads that is displayed on the archive detail page in the Statistics tab.', 'zdm')?></div>
                                        </td>
                                    </tr>
                            </tbody>
                        </table>
                    </div>
                </div>

                <div class="postbox" id="zdm-expanded">
                    <div class="inside">
                        <h3><?=esc_html__('More', 'zdm')?></h3>
                        <hr>
                        <table class="form-table">
                            <tbody>
                                <tr valign="top">
                                    <th scope="row"><?=esc_html__('Direct URL to PDFs', 'zdm')?>:</th>
                                    <td valign="middle">
                                        <input type="checkbox" name="file-open-in-browser-pdf" <?php if($zdm_options['file-open-in-browser-pdf'] == 'on'){ echo 'checked="checked"'; } ?> >
                                        <?=esc_html__('The user is taken to the direct file path.', 'zdm')?>
                                    </td>
                                </tr>
                                <tr valign="top">
                                    <th scope="row"><?=esc_html__('Censor the IP address', 'zdm')?>:</th>
                                    <td valign="middle">
                                        <input type="checkbox" name="secure-ip" <?php if($zdm_options['secure-ip'] == 'on'){ echo 'checked="checked"'; } ?> >
                                        <?=esc_html__('Censored the IP address during the download.', 'zdm')?>
                                    </td>
                                </tr>
                                <tr valign="top">
                                    <th scope="row"><?=esc_html__('Allow duplicates', 'zdm')?>:</th>
                                    <td valign="middle">
                                        <input type="checkbox" name="duplicate-file" <?php if($zdm_options['duplicate-file'] == 'on'){ echo 'checked="checked"'; } ?> >
                                        <?=esc_html__('Allow files that have already been uploaded to be added again.', 'zdm')?>
                                    </td>
                                </tr>
                                <tr valign="top">
                                    <th scope="row"><?=esc_html__('Log', 'zdm')?>:</th>
                                    <td valign="middle">
                                        <a href="admin.php?page=<?=ZDM__SLUG?>-log"><?=esc_html__('Show log', 'zdm')?></a>
                                    </td>
                                </tr>
                                <tr valign="top">
                                    <th scope="row"><?=esc_html__('Download folder token', 'zdm')?>:</th>
                                    <td valign="middle">
                                        <input type="text" value="<?=$zdm_options['download-folder-token']?>" size="50%" disabled>&nbsp;
                                        <a href="admin.php?page=<?=ZDM__SLUG?>-settings&new_download_folder_token=true&nonce=<?=wp_create_nonce('new_download_folder_token')?>" class="button button-secondary"><?=esc_html__('Generate new token', 'zdm')?></a>
                                        <div class="zdm-help-text"><?=esc_html__('Complete folder name', 'zdm')?>: <code>/z-downloads-<?=$zdm_options['download-folder-token']?>/</code></div>
                                        <div class="zdm-help-text"><?=esc_html__('The download folder is not publicly visible unless you have activated "Direct URL" for certain files.', 'zdm')?></div>
                                        <div class="zdm-help-text"><?=esc_html__('You can change the token at any time without hesitation.', 'zdm')?></div>
                                    </td>
                                </tr>
                                <tr valign="top">
                                    <th scope="row"><?=esc_html__('Reset settings', 'zdm')?>:</th>
                                    <td valign="middle">
                                        <a href="admin.php?page=<?=ZDM__SLUG?>-settings&reset_settings=true&nonce=<?=wp_create_nonce('reset-settings')?>" class="button button-secondary"><?=esc_html__('Reset settings', 'zdm')?></a>
                                        <div class="zdm-help-text"><?=esc_html__('Here you can reset all settings to factory settings..', 'zdm')?></div>
                                        <div class="zdm-help-text">
                                            <?=esc_html__('This means that the premium license, button settings such as standard text, style, round corners, icons and all other settings are reset.', 'zdm')?><br>
                                            <?=esc_html__('The download folder token is also regenerated.', 'zdm')?>
                                        </div>
                                    </td>
                                </tr>
                            </tbody>
                        </table>
                    </div>
                </div>

                <div class="postbox">
                    <div class="inside">
                        <h3><?=ZDM__TITLE?> <?=esc_html__('uninstall', 'zdm')?></h3>
                        <hr>
                        <p><?=esc_html__('Path of', 'zdm')?> <?=ZDM__TITLE?> <?=esc_html__('upload folder for files, ZIP archives and cache', 'zdm')?>:<br>
                        <b><pre><?=wp_upload_dir()['basedir'] . "/z-downloads-" . $zdm_options['download-folder-token'] . '/'?></pre></b></p>
                        <hr>
                        <h3 class="zdm-color-red"><?=esc_html__('Attention before uninstalling the plugin', 'zdm')?></h3>
                        <p class="zdm-color-red"><?=esc_html__('If you uninstall the Z-Downloads-Plugin all files and ZIP-archives remain in the above path, if you want to delete all files and ZIP-archives you have created, then click on "DELETE" below', 'zdm')?></p>
                        <p class="zdm-color-red"><?=esc_html__('This process is irreplaceable and can not be undone.', 'zdm')?></p>

                        <br>
                        <a href="admin.php?page=<?=ZDM__SLUG?>-settings&delete_data=true&nonce=<?=wp_create_nonce('delete-all-data')?>" class="button button-secondary zdm-btn-danger-outline"><?=esc_html__('DELETE', 'zdm')?></a>
                    </div>
                </div>

                <?php require_once (plugin_dir_path(__FILE__) . '../inc/postbox_info.php'); ?>

                <input type="hidden" name="nonce" value="<?=wp_create_nonce('einstellungen-speichern')?>">
                <input class="button-primary" type="submit" name="submit" value="<?=esc_html__('Save', 'zdm')?>">
            </form>
        
        <?php } ?>

    </div>

<?php }