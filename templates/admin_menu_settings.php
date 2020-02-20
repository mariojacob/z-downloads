<?php

// Abbruch bei direktem Zugriff
if (!defined('ABSPATH')) {
    die;
}

if (current_user_can(ZDM__STANDARD_USER_ROLE)) {

    $zdm_options = get_option('zdm_options');
    $status = '';
    $note = '';
    $error = '';

    ////////////////////
    // Lizenzschlüssel aktualisieren
    ////////////////////
    if (isset($_POST['licence_submit']) && wp_verify_nonce($_POST['nonce'], 'lizenz-aktualisieren')) {

        // Lizenz
        // Lizenz-Schlüssel aktualisieren
        if (ZDMCore::licence_array(trim(sanitize_text_field($_POST['licence-key'])))['success'] === true) {

            $licence_array = $this->licence_array(trim(sanitize_text_field($_POST['licence-key'])));

            $zdm_options['licence-key'] = trim(sanitize_text_field($_POST['licence-key']));
            $zdm_options['licence-email'] = $licence_array['purchase']['email'];
            $zdm_options['licence-purchase'] = $licence_array['purchase']['created_at'];
            $zdm_options['licence-product-name'] = $licence_array['purchase']['product_name'];
            $zdm_options['licence-time'] = time();

            if (add_option('zdm_options', $zdm_options) === FALSE) {
                update_option('zdm_options', $zdm_options);
                $status = 1;
            }

            $zdm_options = get_option('zdm_options');
        } else {
            $zdm_options['licence-key'] = '';

            if (add_option('zdm_options', $zdm_options) === FALSE) {
                update_option('zdm_options', $zdm_options);
                $status = 1;
            }
        }

        ZDMCore::licence();

        $zdm_options = get_option('zdm_options');

        // Log
        ZDMCore::log('update licence');
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

        // Allgemein

        // Download-Button Text
        $zdm_options['download-btn-text'] = trim(sanitize_text_field($_POST['download-btn-text']));
        // Download-Button Style
        $zdm_options['download-btn-style'] = trim(sanitize_text_field($_POST['download-btn-style']));
        // Download-Button Outline
        $zdm_options['download-btn-outline'] = trim(sanitize_text_field($_POST['download-btn-outline']));
        // Download-Button runde Ecken
        $zdm_options['download-btn-border-radius'] = trim(sanitize_text_field($_POST['download-btn-border-radius']));
        // Download-Button Icon
        $zdm_options['download-btn-icon'] = trim(sanitize_text_field($_POST['download-btn-icon']));
        // Download-Button Icon only
        $zdm_options['download-btn-icon-only'] = trim(sanitize_text_field($_POST['download-btn-icon-only']));

        // Erweitert

        // Direkte URL zu PDF
        $zdm_options['file-open-in-browser-pdf'] = trim(sanitize_text_field($_POST['file-open-in-browser-pdf']));
        // IP-Adresse zensieren
        $zdm_options['secure-ip'] = trim(sanitize_text_field($_POST['secure-ip']));

        // Update Optionen
        if (add_option('zdm_options', $zdm_options) === FALSE) {
            update_option('zdm_options', $zdm_options);
            $status = 1;
        }// end if add_option() === FALSE

        $zdm_options = get_option('zdm_options');

        // Log
        ZDMCore::log('update settings');
    }

    ////////////////////
    // Lösche alle Daten
    ////////////////////
    if (isset($_GET['delete_data']) && wp_verify_nonce($_GET['nonce'], 'alle-daten-löschen')) {

        if ($_GET['delete_data'] == 'true') {

            ZDMCore::delete_all_data();

            $note = esc_html__('Alle Daten wurden erfolgreich gelöscht!', 'zdm');
        }
    }

    ?>

    <div class="wrap">
        <h1 class="wp-heading-inline"><?=esc_html__('Einstellungen', 'zdm')?></h1>

        <hr class="wp-header-end">

        <form action="" method="post">

            <input type="hidden" name="nonce" value="<?=wp_create_nonce('lizenz-aktualisieren')?>">

            <?php
            if ($status != '') {

                echo '<div class="notice notice-success is-dismissible">';
                echo '<br><b>' . esc_html__('Einstellungen aktualisiert!', 'zdm') . '</b><br><br>';
                echo '</div>';
            }// end if $status != ''

            if ($note != '') {

                echo '<div class="notice notice-success is-dismissible">';
                echo '<br><b>' . $note . '</b><br><br>';
                echo '</div>';
            }// end if $note != ''

            if ($error != '') {

                echo '<div class="notice notice-warning">';
                echo '<br><b>' . $error . '</b><br><br>';
                echo '</div>';
            }// end if $error != ''
            ?>
            <div class="postbox">
                <div class="inside">
                    <h3><?=ZDM__PRO?> <?php if ($zdm_licence === 1) { ?>- <?=$zdm_options['licence-product-name'];?> <?=esc_html__('ist aktiviert', 'zdm')?><?php } ?></h3>
                    <hr>
                    <table class="form-table">
                        <tbody>
                            <tr valign="top">
                                <th scope="row"><?=ZDM__PRO?> <?=esc_html__('Schlüssel', 'zdm')?>:</th>
                                <td valign="middle">
                                    <input type="text" name="licence-key" size="50%" value="<?= esc_attr($zdm_options['licence-key']); ?>"> <input class="button-secondary" type="submit" name="licence_submit" value="<?=esc_html__('Lizenz aktualisieren', 'zdm')?>">
                                    <?php if ($zdm_licence === 0) { ?>
                                        <div class="zdm-help-text"><?=esc_html__('Profitiere von den', 'zdm')?> <?=ZDM__PRO?>-<?=esc_html__('Funktionen und schalte alle Möglichkeiten von', 'zdm')?> <?=ZDM__TITLE?> <?=esc_html__('frei, mehr Infos', 'zdm')?>: <a href="<?=ZDM__PRO_URL?>" target="_blank" title="code.urban-base.net"><?=ZDM__TITLE;?> <?=ZDM__PRO?></a></div>
                                    <?php } ?>
                                </td>
                            </tr>
                            <?php if ($zdm_licence === 1) { ?>
                            <tr valign="top">
                                <th scope="row"><?=esc_html__('Lizensiert für', 'zdm')?>:</th>
                                <td valign="middle">
                                    <?=$zdm_options['licence-email'];?>
                                </td>
                            </tr>
                            <tr valign="top">
                                <th scope="row"><?=esc_html__('Erworben', 'zdm')?>:</th>
                                <td valign="middle">
                                    <?=date("d.m.Y", strtotime($zdm_options['licence-purchase']))?>
                                </td>
                            </tr>
                            <?php } ?>
                        </tbody>
                    </table>
                </div>
            </div>
        </form>
        
        <form action="" method="post">

            <div class="postbox">
                <div class="inside">
                    <h3><?=esc_html__('Download-Button', 'zdm')?></h3>
                    <hr>
                    <table class="form-table">
                        <tbody>
                            <tr valign="top">
                                <th scope="row"><?=esc_html__('Standardtext', 'zdm')?>:</th>
                                <td valign="middle">
                                <input type="text" name="download-btn-text" size="15" value="<?=esc_attr($zdm_options['download-btn-text'])?>">
                                <br>
                                <div class="zdm-help-text"><?=esc_html__('Das ist der Standardtext, dieser kann aber je Download individuell geändert werden.', 'zdm')?></div>
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
                                    <div class="zdm-help-text"><?=esc_html__('Wähle aus verschiedenen Button-Farben den Standardwert für Buttons.', 'zdm')?></div>
                                </td>
                            </tr>
                            <tr valign="top">
                                <th scope="row"></th>
                                <td valign="middle">
                                    <input type="checkbox" name="download-btn-outline" <?php if($zdm_options['download-btn-outline'] == 'on'){ echo 'checked="checked"'; } ?> >
                                    <?=esc_html__('Outline', 'zdm')?>
                                    <br>
                                    <div class="zdm-help-text"><?=esc_html__('Diese Option zeigt den Button als Rahmen.', 'zdm')?></div>
                                </td>
                            </tr>
                            <tr valign="top">
                                <th scope="row"><?=esc_html__('Runde Ecken', 'zdm')?>:</th>
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
                                    <div class="zdm-help-text"><?=esc_html__('Wenn "keine" ausgewählt ist, dann wird der Standardwert deines Themes verwendet, der Button bleibt eckig.', 'zdm')?></div>
                                </td>
                            </tr>
                            <tr valign="top">
                                <th scope="row"><?=esc_html__('Icon', 'zdm')?>:</th>
                                <td valign="middle">
                                    <select name="download-btn-icon">
                                    <?php
                                        $zdm_btn_icon = '';

                                        for( $i = 0; $i < count(ZDM__DOWNLOAD_BTN_ICON); $i++ ) {
                                            $zdm_btn_icon   .= '<option value="' . ZDM__DOWNLOAD_BTN_ICON_VAL[$i] . '" ' 
                                                            . ( $zdm_options['download-btn-icon'] == ZDM__DOWNLOAD_BTN_ICON_VAL[$i] ? 'selected="selected"' : '' ) . '>' 
                                                            . ZDM__DOWNLOAD_BTN_ICON[$i] 
                                                            . '</option>';
                                        }
                                        
                                        echo $zdm_btn_icon;
                                    ?>
                                    </select><br>
                                    <div class="zdm-help-text"><?=esc_html__('Hier sind die verfügbaren Icons:', 'zdm')?></div>
                                    <?php
                                        $zdm_btn_icon_example = '';

                                        for( $i = 1; $i < count(ZDM__DOWNLOAD_BTN_ICON); $i++ ) {
                                            $zdm_btn_icon_example   .= ZDM__DOWNLOAD_BTN_ICON[$i] . ':' 
                                                                    . '<ion-icon name="' . ZDM__DOWNLOAD_BTN_ICON_VAL[$i] . '" class="zdm-icon zdm-color-primary"></ion-icon>';
                                        }
                                        
                                        echo $zdm_btn_icon_example;
                                    ?>
                                </td>
                            </tr>
                            <tr valign="top">
                                <th scope="row"></th>
                                <td valign="middle">
                                    <input type="checkbox" name="download-btn-icon-only" <?php if($zdm_options['download-btn-icon-only'] == 'on'){ echo 'checked="checked"'; } ?> >
                                    <?=esc_html__('Nur Icon', 'zdm')?>
                                    <br>
                                    <div class="zdm-help-text"><?=esc_html__('Diese Option zeigt nur das Icon ohne Text an.', 'zdm')?></div>
                                </td>
                            </tr>
                        </tbody>
                    </table>
                </div>
            </div>

            <div class="postbox">
                <div class="inside">
                    <h3><?=esc_html__('Erweitert', 'zdm')?></h3>
                    <hr>
                    <table class="form-table">
                        <tbody>
                            <tr valign="top">
                                <th scope="row"><?=esc_html__('Direkte URL zu PDF', 'zdm')?>:</th>
                                <td valign="middle">
                                    <input type="checkbox" name="file-open-in-browser-pdf" <?php if($zdm_options['file-open-in-browser-pdf'] == 'on'){ echo 'checked="checked"'; } ?> >
                                    <?=esc_html__('Öffnet PDF-Dateien direkt im Browser.', 'zdm')?><br /><br />
                                    <ion-icon name="information-circle"></ion-icon> <?=esc_html__('Während diese Option aktiviert ist wird keine Statistik für PDF-Dateien gespeichert.', 'zdm')?>
                                </td>
                            </tr>
                            <tr valign="top">
                                <th scope="row"><?=esc_html__('IP-Adresse zensieren', 'zdm')?>:</th>
                                <td valign="middle">
                                    <input type="checkbox" name="secure-ip" <?php if($zdm_options['secure-ip'] == 'on'){ echo 'checked="checked"'; } ?> >
                                    <?=esc_html__('Zensiert die IP-Adresse beim Download.', 'zdm')?>
                                </td>
                            </tr>
                            <tr valign="top">
                                <th scope="row"><?=esc_html__('Log', 'zdm')?>:</th>
                                <td valign="middle">
                                    <a href="admin.php?page=<?=ZDM__SLUG?>-log"><?=esc_html__('Log anzeigen', 'zdm')?></a>
                                </td>
                            </tr>
                        </tbody>
                    </table>
                </div>
            </div>

            <div class="postbox">
                <div class="inside">
                    <h3><?=ZDM__TITLE?> <?=esc_html__('deinstallieren', 'zdm')?></h3>
                    <hr>
                    <p><?=esc_html__('Pfad von Z-Downloads-Upload-Ordner für Dateien, ZIP-Archive und Cache:', 'zdm')?><br>
                    <b><pre><?=ZDM__DOWNLOADS_PATH . '/'?></pre></b></p>
                    <hr>
                    <h3 class="zdm-color-red"><?=esc_html__('Achtung vor dem deinstallieren des Plugins', 'zdm')?></h3>
                    <p class="zdm-color-red"><?=esc_html__('Wenn du das Z-Downloads-Plugin deinstallierst bleiben alle Dateien und ZIP-Archive im oben genannten Pfad bestehen, wenn du alle Dateien und ZIP-Archive die du erstellt hast löschen willst, dann klicke unten auf "LÖSCHEN"', 'zdm')?></p>
                    <p class="zdm-color-red"><?=esc_html__('Dieser Vorgang ist unwiederuflich und kann nicht rückgängig gemacht werden.', 'zdm')?></p>

                    <br>
                    <a href="admin.php?page=<?=ZDM__SLUG?>-settings&delete_data=true&nonce=<?=wp_create_nonce('alle-daten-löschen')?>" class="button button-secondary zdm-btn-danger-outline"><?=esc_html__('LÖSCHEN', 'zdm')?></a>
                </div>
            </div>

            <?php require_once (plugin_dir_path(__FILE__) . '../inc/postbox_info.php'); ?>

            <input type="hidden" name="nonce" value="<?=wp_create_nonce('einstellungen-speichern')?>">
            <input class="button-primary" type="submit" name="submit" value="<?=esc_html__('Speichern', 'zdm')?>">
        </form>
        

    </div>

<?php }