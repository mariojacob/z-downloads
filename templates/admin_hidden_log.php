<?php
// Abort by direct access
if (!defined('ABSPATH'))
    die;

if (current_user_can(ZDM__STANDARD_USER_ROLE)) {

    global $wpdb;
    $zdm_tablename_log = $wpdb->prefix . "zdm_log";
    $zdm_status = '';

    if (isset($_GET['id'])) { // Log Detailseite

        $zdm_status = 1;

        $zdm_log_id = sanitize_text_field($_GET['id']);

        $zdm_db_log_details_query = $wpdb->prepare(
            "
            SELECT id, type, message, user_agent, user_ip, time_create 
            FROM $zdm_tablename_log 
            WHERE id = %d
            ",
            $zdm_log_id
        );
        $zdm_db_log_details = $wpdb->get_results($zdm_db_log_details_query);
    } else { // Log Liste

        $zdm_log_filter_array = array(esc_html__('Everything', 'zdm'), esc_html__('Downloads', 'zdm'), esc_html__('Files', 'zdm'), esc_html__('Archives', 'zdm'), esc_html__('System', 'zdm'));
        $zdm_log_filter_array_count = count($zdm_log_filter_array);
        $zdm_log_filter_array_val = array("all", "downloads", "files", "archives", "sys");

        if (isset($_POST['log-filter-type']) && wp_verify_nonce($_POST['nonce'], 'log-types')) {

            $zdm_log_filter_type = sanitize_text_field($_POST['log-filter-type']);

            if ($zdm_log_filter_type == 'downloads') {

                $zdm_db_logs = $wpdb->get_results(
                    "
                    SELECT id, type, message, time_create 
                    FROM $zdm_tablename_log 
                    WHERE type = 'download archive' 
                    || type = 'download file' 
                    ORDER BY time_create DESC 
                    LIMIT 500
                    "
                );
            } elseif ($zdm_log_filter_type == 'files') {

                $zdm_db_logs = $wpdb->get_results(
                    "
                    SELECT id, type, message, time_create 
                    FROM $zdm_tablename_log 
                    WHERE type = 'download file' 
                    || type = 'add file' 
                    || type = 'update file' 
                    || type = 'replace file' 
                    || type = 'unlink file' 
                    || type = 'delete file' 
                    ORDER BY time_create DESC 
                    LIMIT 500
                    "
                );
            } elseif ($zdm_log_filter_type == 'archives') {

                $zdm_db_logs = $wpdb->get_results(
                    "
                    SELECT id, type, message, time_create 
                    FROM $zdm_tablename_log 
                    WHERE type = 'download archive' 
                    || type = 'add archive' 
                    || type = 'delete archive' 
                    || type = 'archive cache created' 
                    ORDER BY time_create DESC 
                    LIMIT 500
                    "
                );
            } elseif ($zdm_log_filter_type == 'sys') {

                $zdm_db_logs = $wpdb->get_results(
                    "
                    SELECT id, type, message, time_create 
                    FROM $zdm_tablename_log 
                    WHERE type = 'plugin activated' 
                    || type = 'database table created' 
                    || type = 'update licence' 
                    || type = 'delete licence' 
                    || type = 'download folder token' 
                    || type = 'delete all data' 
                    ORDER BY time_create DESC 
                    LIMIT 500
                    "
                );
            } else {

                $zdm_db_logs = $wpdb->get_results(
                    "
                    SELECT id, type, message, time_create 
                    FROM $zdm_tablename_log 
                    ORDER BY time_create DESC 
                    LIMIT 500
                    "
                );
            }
        } else {

            $zdm_db_logs = $wpdb->get_results(
                "
                SELECT id, type, message, time_create 
                FROM $zdm_tablename_log 
                ORDER BY time_create DESC 
                LIMIT 500
                "
            );
        }

        $zdm_db_logs_count = count($zdm_db_logs);
    }

    if ($zdm_status === 1) { // Log Detailseite

?>
        <div class="wrap">

            <h1><?= esc_html__('Log details', 'zdm') ?></h1>
            <br>
            <button class="page-title-action" onclick="ZDMgoBack()"><?= esc_html__('Back', 'zdm') ?></button>
            <br><br>

            <div class="postbox">
                <div class="inside">

                    <table class="form-table">
                        <tbody>
                            <tr valign="top">
                                <th scope="row"><?= esc_html__('Type', 'zdm') ?></th>
                                <td valign="middle"><?= $zdm_db_log_details[0]->type ?></td>
                            </tr>
                            <tr valign="top">
                                <th scope="row"><?= esc_html__('Details', 'zdm') ?></th>
                                <td valign="middle">
                                    <?= $zdm_db_log_details[0]->message ?>
                                </td>
                            </tr>
                            <tr valign="top">
                                <th scope="row"><?= esc_html__('User agent', 'zdm') ?></th>
                                <td valign="middle"><?= $zdm_db_log_details[0]->user_agent ?></td>
                            </tr>
                            <tr valign="top">
                                <th scope="row"><?= esc_html__('IP adress', 'zdm') ?></th>
                                <td valign="middle"><?= $zdm_db_log_details[0]->user_ip ?></td>
                            </tr>
                            <tr valign="top">
                                <th scope="row"><?= esc_html__('Created', 'zdm') ?></th>
                                <td valign="middle"><?= date("d.m.Y - H:i:s", $zdm_db_log_details[0]->time_create) ?></td>
                            </tr>
                        </tbody>
                    </table>

                </div>
            </div>

        </div>

        <script>
            function ZDMgoBack() {
                window.history.back();
            }
        </script>
    <?php
    } else { // Log Liste
    ?>
        <div class="wrap">

            <h1><?= esc_html__('Log', 'zdm') ?></h1>
            <br />
            <a href="admin.php?page=<?= ZDM__SLUG ?>-settings" class="page-title-action"><?= esc_html__('Back the settings', 'zdm') ?></a>
            <br /><br />

            <?php
            if ($zdm_db_logs_count > 0) {
            ?>

                <div class="col-wrap">

                    <div class="postbox">
                        <div class="inside">
                            <p><?= esc_html__('Here is the output of the logs of the latest 500 entries.', 'zdm') ?></p>
                            <p>
                            <form action="" method="post">
                                <?= esc_html__('Logs filter by type', 'zdm') ?>:
                                <select name="log-filter-type">
                                    <?php
                                    $zdm_log_filter_option = '';

                                    for ($i = 0; $i < $zdm_log_filter_array_count; $i++) {
                                        $zdm_log_filter_option .= '<option value="' . $zdm_log_filter_array_val[$i] . '" '
                                            . ($zdm_log_filter_type == $zdm_log_filter_array_val[$i] ? 'selected="selected"' : '') . '>'
                                            . $zdm_log_filter_array[$i]
                                            . '</option>';
                                    }

                                    echo $zdm_log_filter_option;
                                    ?>
                                </select>
                                <input type="hidden" name="nonce" value="<?= wp_create_nonce('log-types') ?>">
                                <input class="button-primary" type="submit" name="submit" value="<?= esc_html__('Update', 'zdm') ?>">
                            </form>
                            </p>
                        </div>
                    </div>

                    <table class="wp-list-table widefat striped tags">
                        <thead>
                            <tr>
                                <th scope="col" colspan="2" width="20%"><b><?= esc_html__('Type', 'zdm') ?></b></th>
                                <th scope="col" width="60%"><b><?= esc_html__('Details', 'zdm') ?></b></th>
                                <th scope="col" width="20%"><b><?= esc_html__('Created', 'zdm') ?></b></th>
                            </tr>
                        </thead>

                        <tbody>
                            <?php
                            for ($i = 0; $i < $zdm_db_logs_count; $i++) {

                                if ($zdm_db_logs[$i]->type == 'download archive' || $zdm_db_logs[$i]->type == 'download file') {
                                    $zdm_icon = 'file_download';
                                    $zdm_class_color = 'zdm-color-primary';
                                } elseif ($zdm_db_logs[$i]->type == 'add archive' || $zdm_db_logs[$i]->type == 'add file') {
                                    $zdm_icon = 'note_add';
                                    $zdm_class_color = 'zdm-color-green';
                                } elseif ($zdm_db_logs[$i]->type == 'update archive' || $zdm_db_logs[$i]->type == 'update file') {
                                    $zdm_icon = 'check_circle_outline';
                                    $zdm_class_color = 'zdm-color-green';
                                } elseif ($zdm_db_logs[$i]->type == 'delete archive' || $zdm_db_logs[$i]->type == 'delete file') {
                                    $zdm_icon = 'delete';
                                    $zdm_class_color = 'zdm-color-red';
                                } elseif ($zdm_db_logs[$i]->type == 'archive cache created') {
                                    $zdm_icon = 'check_circle_outline';
                                    $zdm_class_color = 'zdm-color-green';
                                } elseif ($zdm_db_logs[$i]->type == 'link file') {
                                    $zdm_icon = 'link';
                                    $zdm_class_color = 'zdm-color-green';
                                } elseif ($zdm_db_logs[$i]->type == 'unlink file') {
                                    $zdm_icon = 'link_off';
                                    $zdm_class_color = 'zdm-color-yellow';
                                } elseif ($zdm_db_logs[$i]->type == 'replace file') {
                                    $zdm_icon = 'swap';
                                    $zdm_class_color = 'zdm-color-green';
                                } elseif ($zdm_db_logs[$i]->type == 'update settings') {
                                    $zdm_icon = 'settings';
                                    $zdm_class_color = 'zdm-color-grey7';
                                } elseif ($zdm_db_logs[$i]->type == 'reset settings') {
                                    $zdm_icon = 'history';
                                    $zdm_class_color = 'zdm-color-grey7';
                                } elseif ($zdm_db_logs[$i]->type == 'error create zip') {
                                    $zdm_icon = 'error_outline';
                                    $zdm_class_color = 'zdm-color-orange';
                                } elseif ($zdm_db_logs[$i]->type == 'update licence') {
                                    $zdm_icon = 'check_circle_outline';
                                    $zdm_class_color = 'zdm-color-green';
                                } elseif ($zdm_db_logs[$i]->type == 'delete licence') {
                                    $zdm_icon = 'delete';
                                    $zdm_class_color = 'zdm-color-red';
                                } elseif ($zdm_db_logs[$i]->type == 'plugin upgrade') {
                                    $zdm_icon = 'upgrade';
                                    $zdm_class_color = 'zdm-color-primary';
                                } elseif ($zdm_db_logs[$i]->type == 'delete all data') {
                                    $zdm_icon = 'delete_forever';
                                    $zdm_class_color = 'zdm-color-red';
                                } elseif ($zdm_db_logs[$i]->type == 'plugin activated' || $zdm_db_logs[$i]->type == 'plugin deactivated') {
                                    $zdm_icon = 'power_settings_new';
                                    $zdm_class_color = 'zdm-color-primary';
                                } elseif ($zdm_db_logs[$i]->type == 'database table created') {
                                    $zdm_icon = 'storage';
                                    $zdm_class_color = 'zdm-color-primary';
                                } else {
                                    $zdm_icon = 'info';
                                    $zdm_class_color = '';
                                }
                            ?>
                                <tr>
                                    <td>
                                        <div align="center"><span class="material-icons-round zdm-md-1-5 <?= $zdm_class_color ?>"><?= $zdm_icon ?></span></div>
                                    </td>
                                    <td>
                                        <b><a href="?page=<?= ZDM__SLUG ?>-log&id=<?= $zdm_db_logs[$i]->id ?>" title="<?= esc_html__('Show details', 'zdm') ?>"><?= $zdm_db_logs[$i]->type ?></a></b>
                                    </td>
                                    <td>
                                        <?= $zdm_db_logs[$i]->message ?>
                                    </td>
                                    <td>
                                        <?= date("d.m.Y - H:i:s", $zdm_db_logs[$i]->time_create) ?>
                                    </td>
                                </tr>
                            <?php
                            }
                            ?>
                        </tbody>

                        <tfoot>
                            <tr>
                                <th scope="col" colspan="2"><b><?= esc_html__('Type', 'zdm') ?></b></th>
                                <th scope="col"><b><?= esc_html__('Details', 'zdm') ?></b></th>
                                <th scope="col"><b><?= esc_html__('Created', 'zdm') ?></b></th>
                            </tr>
                        </tfoot>

                    </table>

                </div>
            <?php
            } else {
            ?>
                <div class="col-wrap">

                    <div class="postbox">
                        <div class="inside">
                            <p><?= esc_html__('There are no log entries for this type.', 'zdm') ?></p>
                            <p>
                            <form action="" method="post">
                                <?= esc_html__('Logs filter by type', 'zdm') ?>:
                                <select name="log-filter-type">
                                    <?php
                                    $zdm_log_filter_option = '';

                                    for ($i = 0; $i < $zdm_log_filter_array_count; $i++) {
                                        $zdm_log_filter_option .= '<option value="' . $zdm_log_filter_array_val[$i] . '" '
                                            . ($zdm_log_filter_type == $zdm_log_filter_array_val[$i] ? 'selected="selected"' : '') . '>'
                                            . $zdm_log_filter_array[$i]
                                            . '</option>';
                                    }

                                    echo $zdm_log_filter_option;
                                    ?>
                                </select>
                                <input type="hidden" name="nonce" value="<?= wp_create_nonce('log-types') ?>">
                                <input class="button-primary" type="submit" name="submit" value="<?= esc_html__('Update', 'zdm') ?>">
                            </form>
                            </p>
                        </div>
                    </div>
                </div>
            <?php
            }
            ?>
            <br>
            <a class="alignright button" href="javascript:void(0);" onclick="window.scrollTo(0,0);" style="margin:3px 0 0 30px;"><?= esc_html__('To top', 'zdm') ?></a>
        </div>
<?php
    }
}
