<?php

// Abbruch bei direktem Zugriff
if (!defined('ABSPATH')) {
    die;
}

if (current_user_can(ZDM__STANDARD_USER_ROLE)) {

    global $wpdb;
    $zdm_tablename_log = $wpdb->prefix . "zdm_log";
    $zdm_status = '';
    
    if (isset($_GET['id'])) { // Log Detailseite

        $zdm_status = 1;

        $zdm_log_id = sanitize_text_field($_GET['id']);

        $zdm_db_log_details = $wpdb->get_results( 
            "
            SELECT * 
            FROM $zdm_tablename_log 
            WHERE id = '$zdm_log_id'
            "
        );

    } else { // Log Liste
        
        $zdm_log_filter_array = array(esc_html__('Alles', 'zdm'), esc_html__('Downloads', 'zdm'), esc_html__('Dateien', 'zdm'), esc_html__('Archive', 'zdm'));
        $zdm_log_filter_array_val = array("all", "downloads", "files", "archives");

        if (isset($_POST['log-filter-type']) && wp_verify_nonce($_POST['nonce'], 'log-types')) {

            // Allgemeines
            
            $zdm_lof_filter_type = sanitize_text_field($_POST['log-filter-type']);

            if ($zdm_lof_filter_type == 'downloads') {
            
                $zdm_db_logs = $wpdb->get_results( 
                    "
                    SELECT id, type, message, time_create 
                    FROM $zdm_tablename_log 
                    WHERE type = 'download archive' 
                    OR type = 'download file' 
                    ORDER BY time_create DESC 
                    LIMIT 100
                    "
                );
            } elseif ($zdm_lof_filter_type == 'files') {
            
                $zdm_db_logs = $wpdb->get_results( 
                    "
                    SELECT id, type, message, time_create 
                    FROM $zdm_tablename_log 
                    WHERE type = 'download file' 
                    OR type = 'add file' 
                    OR type = 'unlink file' 
                    OR type = 'delete file' 
                    ORDER BY time_create DESC 
                    LIMIT 100
                    "
                );
            } elseif ($zdm_lof_filter_type == 'archives') {
            
                $zdm_db_logs = $wpdb->get_results( 
                    "
                    SELECT id, type, message, time_create 
                    FROM $zdm_tablename_log 
                    WHERE type = 'download archive' 
                    OR type = 'add archive' 
                    OR type = 'delete archive' 
                    OR type = 'create archive cache' 
                    ORDER BY time_create DESC 
                    LIMIT 100
                    "
                );
            } else {
            
                $zdm_db_logs = $wpdb->get_results( 
                    "
                    SELECT id, type, message, time_create 
                    FROM $zdm_tablename_log 
                    ORDER BY time_create DESC 
                    LIMIT 100
                    "
                );
            }

        } else {
            
            $zdm_db_logs = $wpdb->get_results( 
                "
                SELECT id, type, message, time_create 
                FROM $zdm_tablename_log 
                ORDER BY time_create DESC 
                LIMIT 100
                "
            );
        }
    }

    if ($zdm_status === 1) { // Log Detailseite

        ?>
        <div class="wrap">

            <h1 class="wp-heading-inline"><?=esc_html__('Log Details', 'zdm')?><a href="admin.php?page=<?=ZDM__SLUG?>-log" class="page-title-action"><?=esc_html__('Zurück zur Übersicht', 'zdm')?></a></h1>
            <hr class="wp-header-end">

            <div class="postbox">
                <div class="inside">

                    <table class="form-table">
                        <tbody>
                            <tr valign="top">
                                <th scope="row"><?=esc_html__('Typ', 'zdm')?></th>
                                <td valign="middle"><?=$zdm_db_log_details[0]->type?></td>
                            </tr>
                            <tr valign="top">
                                <th scope="row"><?=esc_html__('Details', 'zdm')?></th>
                                <td valign="middle"><?=$zdm_db_log_details[0]->message?></td>
                            </tr>
                            <tr valign="top">
                                <th scope="row"><?=esc_html__('User Agent', 'zdm')?></th>
                                <td valign="middle"><?=$zdm_db_log_details[0]->user_agent?></td>
                            </tr>
                            <tr valign="top">
                                <th scope="row"><?=esc_html__('IP Adresse', 'zdm')?></th>
                                <td valign="middle"><?=$zdm_db_log_details[0]->user_ip?></td>
                            </tr>
                            <tr valign="top">
                                <th scope="row"><?=esc_html__('WordPress Benutzer ID', 'zdm')?></th>
                                <?php
                                if ($zdm_db_log_details[0]->user_id == 0) {
                                    $user_id = esc_html__('kein WordPress Benutzer', 'zdm');
                                } else {
                                    $user_id = $zdm_db_log_details[0]->user_id;
                                }
                                ?>
                                <td valign="middle"><?=$user_id?></td>
                            </tr>
                            <tr valign="top">
                                <th scope="row"><?=esc_html__('Erstellt', 'zdm')?></th>
                                <td valign="middle"><?=date("d.m.Y - H:i:s", $zdm_db_log_details[0]->time_create)?></td>
                            </tr>
                        </tbody>
                    </table>

                </div>
            </div>

        </div>
        <?php
            
    } else { // Log Liste
        
        ?>
        <div class="wrap">

            <h1 class="wp-heading-inline"><?=esc_html__('Log', 'zdm')?><a href="admin.php?page=<?=ZDM__SLUG?>-settings" class="page-title-action"><?=esc_html__('Zurück den Einstellungen', 'zdm')?></a></h1>
            <hr class="wp-header-end">

            <?php
            if (count($zdm_db_logs) > 0) {
                ?>

                <div class="col-wrap">

                    <div class="postbox">
                        <div class="inside">
                            <p><?=esc_html__('Hier ist Ausgabe des Logs der aktuellsten 100 Einträge.', 'zdm')?></p>
                            <p>
                                <form action="" method="post">
                                <?=esc_html__('Logs filtern nach Typ', 'zdm')?>: 
                                    <select name="log-filter-type">
                                        <?php
                                        $zdm_log_filter_option = '';

                                        for( $i = 0; $i < count($zdm_log_filter_array); $i++ ) {
                                            $zdm_log_filter_option .= '<option value="' . $zdm_log_filter_array_val[$i] . '" ' 
                                                                    . ( $zdm_lof_filter_type == $zdm_log_filter_array_val[$i] ? 'selected="selected"' : '' ) . '>' 
                                                                    . $zdm_log_filter_array[$i] 
                                                                    . '</option>';
                                        }
                                        
                                        echo $zdm_log_filter_option;
                                        ?>
                                    </select> 
                                    <input type="hidden" name="nonce" value="<?=wp_create_nonce('log-types')?>">
                                    <input class="button-primary" type="submit" name="submit" value="<?=esc_html__('Aktualisieren', 'zdm')?>">
                                </form>
                            </p>
                        </div>
                    </div>

                    <table class="wp-list-table widefat striped tags">
                        <thead>
                            <tr>
                                <th scope="col" colspan="2"><b><?=esc_html__('Typ', 'zdm')?></b></th>
                                <th scope="col"><b><?=esc_html__('Details', 'zdm')?></b></th>
                                <th scope="col"><b><?=esc_html__('Erstellt', 'zdm')?></b></th>
                            </tr>
                        </thead>

                        <tbody>
                        <?php

                            for ($i = 0; $i < count($zdm_db_logs); $i++) {

                                if ($zdm_db_logs[$i]->type == 'download archive' OR $zdm_db_logs[$i]->type == 'download file') {
                                    $zdm_icon = 'download';
                                    $zdm_class_color = 'zdm-color-primary';
                                } elseif ($zdm_db_logs[$i]->type == 'add archive' OR $zdm_db_logs[$i]->type == 'add file') {
                                    $zdm_icon = 'add-circle';
                                    $zdm_class_color = 'zdm-color-green';
                                } elseif ($zdm_db_logs[$i]->type == 'update archive' OR $zdm_db_logs[$i]->type == 'update file') {
                                    $zdm_icon = 'checkmark-circle';
                                    $zdm_class_color = '';
                                } elseif ($zdm_db_logs[$i]->type == 'delete archive' OR $zdm_db_logs[$i]->type == 'delete file') {
                                    $zdm_icon = 'trash';
                                    $zdm_class_color = 'zdm-color-red';
                                } elseif ($zdm_db_logs[$i]->type == 'create archive cache') {
                                    $zdm_icon = 'refresh-circle';
                                    $zdm_class_color = 'zdm-color-green';
                                } elseif ($zdm_db_logs[$i]->type == 'unlink file') {
                                    $zdm_icon = 'remove-circle';
                                    $zdm_class_color = 'zdm-color-yellow';
                                } elseif ($zdm_db_logs[$i]->type == 'replace file') {
                                    $zdm_icon = 'swap';
                                    $zdm_class_color = 'zdm-color-green';
                                } elseif ($zdm_db_logs[$i]->type == 'update settings') {
                                    $zdm_icon = 'settings';
                                    $zdm_class_color = 'zdm-color-grey7';
                                } elseif ($zdm_db_logs[$i]->type == 'error create zip') {
                                    $zdm_icon = 'alert';
                                    $zdm_class_color = 'zdm-color-orange';
                                } else {
                                    $zdm_icon = 'information-circle';
                                    $zdm_class_color = '';
                                }

                                ?>
                                <tr>
                                    <td>
                                        <div align="center"><ion-icon name="<?=$zdm_icon?>" class="zdm-icon-in-table <?=$zdm_class_color?>"></ion-icon></div>
                                    </td>
                                    <td>
                                        <b><a href="?page=<?=ZDM__SLUG?>-log&id=<?=$zdm_db_logs[$i]->id?>" title="<?=esc_html__('Details anzeigen', 'zdm')?>"><?=$zdm_db_logs[$i]->type?></a></b>
                                    </td>
                                    <td>
                                        <?=$zdm_db_logs[$i]->message?>
                                    </td>
                                    <td>
                                        <?=date("d.m.Y - H:i:s", $zdm_db_logs[$i]->time_create)?>
                                    </td>
                                </tr>
                                <?php
                            }

                        ?>
                        </tbody>

                        <tfoot>
                            <tr>
                                <th scope="col" colspan="2"><b><?=esc_html__('Typ', 'zdm')?></b></th>
                                <th scope="col"><b><?=esc_html__('Details', 'zdm')?></b></th>
                                <th scope="col"><b><?=esc_html__('Erstellt', 'zdm')?></b></th>
                            </tr>
                        </tfoot>

                    </table>

                </div>
                <?php
            }
            ?>

            <br>
            <a class="alignright button" href="javascript:void(0);" onclick="window.scrollTo(0,0);" style="margin:3px 0 0 30px;"><?=esc_html__('Nach oben', 'zdm')?></a>
        </div>

        <?php

    }

}