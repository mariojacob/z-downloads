<?php

// Abbruch bei direktem Zugriff
if( !defined( 'ABSPATH' ) ) {
    die;
}

if (current_user_can(ZDM__STANDARD_USER_ROLE)) {

    $zdm_options = get_option('zdm_options');

    if (ZDMCore::licence() != true) { ?>
    <div class="zdm-welcome-notice notice notice-info">
            <div class="zdm-welcome-icon-holder">
                <img class="zdm-welcome-icon" src="<?=ZDM__PLUGIN_URL?>assets/icon-256x256.png" alt="ZIP Download Master Logo">
            </div>
            <h1><?=esc_html__('Willkommen bei', 'zdm')?> <?=ZDM__TITLE?></h1>
            <h3><?=esc_html__('Bringe mehr Struktur in deine Downloads und aktualisiere diese effizienter.', 'zdm')?></h3>
            <h3><?=esc_html__('Um das volle Potential dieses Plugins zu Nutzen aktiviere jetzt die', 'zdm')?> <?=ZDM__PRO?>-<?=esc_html__('Funktionen.', 'zdm')?></h3>
            <br>
            <a href="<?=ZDM__PRO_URL?>" target="_blank" class="button button-primary"><?=esc_html__('Mehr erfahren', 'zdm')?></a>
    </div>
    <?php } ?>
    <div class="wrap">

        <h1 class="wp-heading-inline"><?=esc_html__('Dashboard', 'zdm')?></h1>

        <hr class="wp-header-end">

        <div class="postbox-container zdm-postbox-50">

            <?php
            ////////////////////
            // Letzte Downloads
            ////////////////////
            
            $zdm_last_downloads = ZDMStat::get_last_downloads();
            $zdm_last_downloads_files = ZDMStat::get_last_downloads('file');

            if ($zdm_last_downloads != false OR $zdm_last_downloads_files != false) { ?>
                
                <div class="postbox">

                    <div class="inside">
                        <h3><ion-icon name="time"></ion-icon> <?=esc_html__('Letzte Downloads', 'zdm')?></h3>
                    </div>

                    <?php if ($zdm_last_downloads != false) { ?>

                        <table class="wp-list-table widefat striped tags">
                            <thead>
                                <tr>
                                    <th scope="col" width="60%"><b><?=esc_html__('Archive', 'zdm')?></b></th>
                                    <th scope="col" width="20%"><b><?=esc_html__('Datum', 'zdm')?></b></th>
                                </tr>
                            </thead>

                            <tbody>
                            <?php

                            for ($i = 0; $i < count($zdm_last_downloads); $i++) {

                                $zdm_download_name = ZDMCore::get_archive_name($zdm_last_downloads[$i]->message);

                                if ($zdm_download_name != '') {

                                    $zdm_download_id_link = '<b><a href="?page=' . ZDM__SLUG . '-ziparchive&id=' . $zdm_last_downloads[$i]->message . '">' . $zdm_download_name . '</a></b>';

                                } else {
                                    $zdm_download_id_link = esc_html__('Gelöschtes Archiv', 'zdm');
                                }

                                echo '<tr>';
                                    echo '<td>';
                                        echo $zdm_download_id_link;
                                    echo '</td>';
                                    echo '<td>';
                                        echo date("d.m.Y - h:i:s", $zdm_last_downloads[$i]->time_create);
                                    echo '</td>';
                                echo '</tr>';
                            }

                            ?>
                            </tbody>
                        </table>

                    <?php }

                    if ($zdm_last_downloads_files != false) {?>

                        <table class="wp-list-table widefat striped tags">
                            <thead>
                                <tr>
                                    <th scope="col" width="60%"><b><?=esc_html__('Dateien', 'zdm')?></b></th>
                                    <th scope="col" width="20%"><b><?=esc_html__('Datum', 'zdm')?></b></th>
                                </tr>
                            </thead>

                            <tbody>
                            <?php

                            for ($i = 0; $i < count($zdm_last_downloads_files); $i++) {

                                $zdm_download_name = ZDMCore::get_file_name($zdm_last_downloads_files[$i]->message);

                                if ($zdm_download_name != '') {

                                    $zdm_download_id_link = '<b><a href="?page=' . ZDM__SLUG . '-files&id=' . $zdm_last_downloads_files[$i]->message . '">' . $zdm_download_name . '</a></b>';

                                } else {
                                    $zdm_download_id_link = esc_html__('Gelöschte Datei', 'zdm');
                                }

                                ?>
                                <tr>
                                    <td>
                                        <?=$zdm_download_id_link?>
                                    </td>
                                    <td>
                                        <?=date("d.m.Y - h:i:s", $zdm_last_downloads_files[$i]->time_create)?>
                                    </td>
                                </tr>
                                <?php
                            }

                            ?>
                            </tbody>
                        </table>

                    <?php } ?>

                </div>

            <?php }

            ////////////////////
            // Beliebte Downloads
            ////////////////////

            $zdm_best_downloads = ZDMStat::get_best_downloads();
            $zdm_best_downloads_files = ZDMStat::get_best_downloads('file');

            if ($zdm_best_downloads != false OR $zdm_best_downloads_files != false) { ?>
                
                <div class="postbox">

                    <div class="inside">
                        <h3><ion-icon name="trending-up"></ion-icon> <?=esc_html__('Beliebte Downloads', 'zdm')?></h3>
                    </div>

                    <?php if ($zdm_best_downloads != false) { ?>

                        <table class="wp-list-table widefat striped tags">
                            <thead>
                                <tr>
                                    <th scope="col" width="80%"><b><?=esc_html__('Archive', 'zdm')?></b></th>
                                    <th scope="col" width="20%"><b><?=esc_html__('Downloads', 'zdm')?></b></th>
                                </tr>
                            </thead>

                            <tbody>
                            <?php

                                for ($i = 0; $i < count($zdm_best_downloads); $i++) {

                                    $zdm_download_name = $zdm_best_downloads[$i]->name;

                                    if ($zdm_download_name != '') {

                                        $zdm_download_id_link = '<b><a href="?page=' . ZDM__SLUG . '-ziparchive&id=' . $zdm_best_downloads[$i]->id . '">' . $zdm_download_name . '</a></b>';

                                    } else {
                                        $zdm_download_id_link = esc_html__('Gelöschter Download', 'zdm');
                                    }

                                    ?>
                                    <tr>
                                        <td>
                                            <?=$zdm_download_id_link?>
                                        </td>
                                        <td>
                                            <?=ZDMCore::number_format($zdm_best_downloads[$i]->count)?>
                                        </td>
                                    </tr>
                                    <?php
                                }

                                ?>
                        </table>

                    <?php
                    }

                    if ($zdm_best_downloads_files != false) { ?>

                        <table class="wp-list-table widefat striped tags">
                            <thead>
                                <tr>
                                    <th scope="col" width="80%" colspan="2"><b><?=esc_html__('Dateien', 'zdm')?></b></th>
                                    <th scope="col" width="20%"><b><?=esc_html__('Downloads', 'zdm')?></b></th>
                                </tr>
                            </thead>

                            <tbody>
                            <?php

                                for ($i = 0; $i < count($zdm_best_downloads_files); $i++) {

                                    $zdm_download_name = $zdm_best_downloads_files[$i]->name;

                                    if ($zdm_download_name != '') {

                                        $zdm_download_id_link = '<b><a href="?page=' . ZDM__SLUG . '-files&id=' . $zdm_best_downloads_files[$i]->id . '">' . $zdm_download_name . '</a></b>';

                                    } else {
                                        $zdm_download_id_link = esc_html__('Gelöschter Download', 'zdm');
                                    }

                                    ?>
                                    <tr>
                                        <td width="2%">
                                            <?php
                                            if (in_array($zdm_best_downloads_files[$i]->file_type, ZDM__MIME_TYPES_AUDIO)) { // Audio
                                                $icon = '<ion-icon name="musical-notes"></ion-icon>';
                                            } elseif (in_array($zdm_best_downloads_files[$i]->file_type, ZDM__MIME_TYPES_VIDEO)) { // Video
                                                $icon = '<ion-icon name="videocam"></ion-icon>';
                                            } elseif (in_array($zdm_best_downloads_files[$i]->file_type, ZDM__MIME_TYPES_IMAGE)) { // Bild
                                                $icon = '<ion-icon name="images"></ion-icon>';
                                            } else {
                                                $icon = '<ion-icon name="document"></ion-icon>';
                                            }
                                            echo $icon;
                                            ?>
                                        </td>
                                        <td width="78%">
                                            <?=$zdm_download_id_link?>
                                        </td>
                                        <td width="20%">
                                            <?=ZDMCore::number_format($zdm_best_downloads_files[$i]->count)?>
                                        </td>
                                    </tr>
                                    <?php
                                }

                                ?>
                        </table>

                    <?php } ?>
                    
                </div>
                
            <?php } ?>
            
            

        </div>

        <div class="postbox-container">

            <div class="postbox">

                <div class="inside">
                    <h3><?=esc_html__('Download Statistik', 'zdm')?></h3>
                </div>

                <table class="wp-list-table widefat">
                    <tr valign="top">
                        <th scope="row">
                            <b><?=esc_html__('Gesamt', 'zdm')?>:</b><br>
                            <b><?=esc_html__('Archive', 'zdm')?>:</b><br>
                            <b><?=esc_html__('Dateien', 'zdm')?>:</b>
                        </th>
                        <td valign="middle">
                            <?=ZDMCore::number_format(ZDMStat::get_downloads_count('all'))?><br>
                            <?=ZDMCore::number_format(ZDMStat::get_downloads_count('archive'))?><br>
                            <?=ZDMCore::number_format(ZDMStat::get_downloads_count('file'))?>
                        </td>
                    </tr>
                    <tr valign="top">
                        <th colspan="2"><hr></th>
                    </tr>
                    <tr valign="top">
                        <th scope="row"><b><?=esc_html__('Letzten 30 Tage', 'zdm')?>:</b></th>
                        <td valign="middle">
                            <?=ZDMCore::number_format(ZDMStat::get_downloads_count_time('all', 86400*30))?>
                        </td>
                    </tr>
                    <tr valign="top">
                        <th scope="row"><b><?=esc_html__('Letzten 7 Tage', 'zdm')?>:</b></th>
                        <td valign="middle">
                            <?=ZDMCore::number_format(ZDMStat::get_downloads_count_time('all', 86400*7))?>
                        </td>
                    </tr>
                    <tr valign="top">
                        <th scope="row"><b><?=esc_html__('Letzten 24 Stunden', 'zdm')?>:</b></th>
                        <td valign="middle">
                            <?=ZDMCore::number_format(ZDMStat::get_downloads_count_time('all', 86400))?>
                        </td>
                    </tr>
                </table>

            </div>

        </div>

        <div class="postbox-container zdm-postbox-100">
            <?php require_once (plugin_dir_path(__FILE__) . '../inc/postbox_info.php'); ?>
        </div>

        <a class="alignright button" href="javascript:void(0);" onclick="window.scrollTo(0,0);" style="margin:3px 0 0 30px;"><?=esc_html__('Nach oben', 'zdm')?></a>

    </div>

<?php }