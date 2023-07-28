<?php
// Abort by direct access
if (!defined('ABSPATH'))
    die;

if (current_user_can(ZDM__STANDARD_USER_ROLE)) {

    $zdm_options = get_option('zdm_options');

    if (ZDMCore::licence() != true) { ?>
        <div class="zdm-welcome-notice notice notice-info">
            <div class="zdm-welcome-icon-holder">
                <img class="zdm-welcome-icon" src="<?= ZDM__PLUGIN_URL ?>assets/icon-256x256.png" alt="Z-Downloads Logo">
            </div>
            <h1><?= esc_html__('Welcome to', 'zdm') ?> <?= ZDM__TITLE ?></h1>
            <h3><?= esc_html__('Organize your downloads effectively and keep them up to date.', 'zdm') ?></h3>
            <h3><?= esc_html__('To get the most out of this plugin, activate now', 'zdm') ?> <?= ZDM__PRO ?>.</h3>
            <br>
            <a href="<?= ZDM__PRO_URL ?>" target="_blank" class="button button-primary"><?= esc_html__('More info', 'zdm') ?></a>
        </div>
    <?php } ?>
    <div class="wrap">

        <h1 class="wp-heading-inline"><?= esc_html__('Dashboard', 'zdm') ?></h1>

        <hr class="wp-header-end">

        <div class="postbox-container zdm-postbox-col-md">

            <?php
            ////////////////////
            // Letzte Downloads
            ////////////////////

            $zdm_last_downloads = ZDMStat::get_last_downloads();
            $zdm_last_downloads_count = count($zdm_last_downloads);
            $zdm_last_downloads_files = ZDMStat::get_last_downloads('file');
            $zdm_last_downloads_files_count = count($zdm_last_downloads_files);

            if ($zdm_last_downloads != false or $zdm_last_downloads_files != false) { ?>

                <div class="postbox">

                    <div class="inside">
                        <h3><span class="material-icons-round zdm-md-1">access_time</span> <?= esc_html__('Last downloads', 'zdm') ?></h3>
                    </div>

                    <?php if ($zdm_last_downloads != false) { ?>

                        <table class="wp-list-table widefat striped tags">
                            <thead>
                                <tr>
                                    <th scope="col" width="65%"><b><?= esc_html__('Archive', 'zdm') ?></b></th>
                                    <th scope="col" width="5%"><b><?= esc_html__('Info', 'zdm') ?></b></th>
                                    <th scope="col" width="30%"><b><?= esc_html__('Date', 'zdm') ?></b></th>
                                </tr>
                            </thead>

                            <tbody>
                                <?php
                                for ($i = 0; $i < $zdm_last_downloads_count; $i++) {

                                    $zdm_download_name = ZDMCore::get_archive_name($zdm_last_downloads[$i]->message);

                                    if ($zdm_download_name != '') {
                                        $zdm_download_id_link = '<b><a href="?page=' . ZDM__SLUG . '-ziparchive&id=' . $zdm_last_downloads[$i]->message . '">' . $zdm_download_name . '</a></b>';
                                        $zdm_download_log_link = '<a href="?page=' . ZDM__SLUG . '-log&id=' . $zdm_last_downloads[$i]->id . '"><span class="material-icons-outlined zdm-md-1-5">info</span></a>';
                                    } else {
                                        $zdm_download_id_link = esc_html__('Deleted archive', 'zdm');
                                        $zdm_download_log_link = '<span class="material-icons-outlined zdm-md-1-5 zdm-help-text">info</span>';
                                    }

                                    echo '<tr>';
                                    echo '<td>';
                                    echo $zdm_download_id_link;
                                    echo '</td>';
                                    echo '<td align="center">';
                                    echo $zdm_download_log_link;
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

                    if ($zdm_last_downloads_files != false) { ?>

                        <table class="wp-list-table widefat striped tags">
                            <thead>
                                <tr>
                                    <th scope="col" width="65%"><b><?= esc_html__('Files', 'zdm') ?></b></th>
                                    <th scope="col" width="5%"><b><?= esc_html__('Info', 'zdm') ?></b></th>
                                    <th scope="col" width="30%"><b><?= esc_html__('Date', 'zdm') ?></b></th>
                                </tr>
                            </thead>

                            <tbody>
                                <?php
                                for ($i = 0; $i < $zdm_last_downloads_files_count; $i++) {

                                    $zdm_download_name = ZDMCore::get_file_name($zdm_last_downloads_files[$i]->message);

                                    if ($zdm_download_name != '') {
                                        $zdm_download_id_link = '<b><a href="?page=' . ZDM__SLUG . '-files&id=' . $zdm_last_downloads_files[$i]->message . '">' . $zdm_download_name . '</a></b>';
                                        $zdm_download_log_link = '<a href="?page=' . ZDM__SLUG . '-log&id=' . $zdm_last_downloads_files[$i]->id . '"><span class="material-icons-outlined zdm-md-1-5">info</span></a>';
                                    } else {
                                        $zdm_download_id_link = esc_html__('Deleted file', 'zdm');
                                        $zdm_download_log_link = '<span class="material-icons-outlined zdm-md-1-5 zdm-help-text">info</span>';
                                    }
                                ?>
                                    <tr>
                                        <td>
                                            <?= $zdm_download_id_link ?>
                                        </td>
                                        <td align="center">
                                            <?= $zdm_download_log_link ?>
                                        </td>
                                        <td>
                                            <?= date("d.m.Y - h:i:s", $zdm_last_downloads_files[$i]->time_create) ?>
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
            // Popular downloads
            ////////////////////

            $zdm_best_downloads = ZDMStat::get_best_downloads();
            $zdm_best_downloads_count = count($zdm_best_downloads);
            $zdm_best_downloads_files = ZDMStat::get_best_downloads('file');
            $zdm_best_downloads_files_count = count($zdm_best_downloads_files);

            if ($zdm_best_downloads != false or $zdm_best_downloads_files != false) { ?>

                <div class="postbox">

                    <div class="inside">
                        <h3><span class="material-icons-round zdm-md-1">trending_up</span> <?= esc_html__('Popular downloads', 'zdm') ?></h3>
                    </div>

                    <?php
                    if ($zdm_best_downloads != false) { ?>

                        <table class="wp-list-table widefat striped tags">
                            <thead>
                                <tr>
                                    <th scope="col" width="75%"><b><?= esc_html__('Archives', 'zdm') ?></b></th>
                                    <th scope="col" width="5%"><b><?= esc_html__('Statistics', 'zdm') ?></b></th>
                                    <th scope="col" width="20%"><b><?= esc_html__('Downloads', 'zdm') ?></b></th>
                                </tr>
                            </thead>

                            <tbody>
                                <?php

                                for ($i = 0; $i < $zdm_best_downloads_count; $i++) {

                                    $zdm_download_name = $zdm_best_downloads[$i]->name;

                                    if ($zdm_download_name != '') {
                                        $zdm_download_id_link = '<b><a href="?page=' . ZDM__SLUG . '-ziparchive&id=' . $zdm_best_downloads[$i]->id . '">' . $zdm_download_name . '</a></b>';
                                        $zdm_download_statistics_link = '<a href="?page=' . ZDM__SLUG . '-ziparchive&id=' . $zdm_best_downloads[$i]->id . '&tab=statistics"><span class="material-icons-outlined zdm-md-1-5">equalizer</span></a>';
                                    } else {
                                        $zdm_download_id_link = esc_html__('Deleted download', 'zdm');
                                        $zdm_download_statistics_link = '<span class="material-icons-outlined zdm-md-1-5 zdm-help-text">equalizer</span>';
                                    }
                                ?>
                                    <tr>
                                        <td width="75%">
                                            <?= $zdm_download_id_link ?>
                                        </td>
                                        <td width="5%" align="center">
                                            <?= $zdm_download_statistics_link ?>
                                        </td>
                                        <td width="20%">
                                            <?= ZDMCore::number_format($zdm_best_downloads[$i]->count) ?>
                                        </td>
                                    </tr>
                                <?php
                                } // end for ($i = 0; $i < $zdm_best_downloads_count; $i++)
                                ?>
                            </tbody>
                        </table>

                    <?php
                    } // end if ($zdm_best_downloads != false)

                    if ($zdm_best_downloads_files != false) { ?>

                        <table class="wp-list-table widefat striped tags">
                            <thead>
                                <tr>
                                    <th scope="col" width="75%"><b><?= esc_html__('Files', 'zdm') ?></b></th>
                                    <th scope="col" width="5%"><b><?= esc_html__('Statistics', 'zdm') ?></b></th>
                                    <th scope="col" width="20%"><b><?= esc_html__('Downloads', 'zdm') ?></b></th>
                                </tr>
                            </thead>

                            <tbody>
                                <?php
                                for ($i = 0; $i < $zdm_best_downloads_files_count; $i++) {

                                    $zdm_download_name = $zdm_best_downloads_files[$i]->name;

                                    if ($zdm_download_name != '') {
                                        $zdm_download_id_link = '<b><a href="?page=' . ZDM__SLUG . '-files&id=' . $zdm_best_downloads_files[$i]->id . '">' . $zdm_download_name . '</a></b>';
                                        $zdm_download_statistics_link = '<a href="?page=' . ZDM__SLUG . '-files&id=' . $zdm_best_downloads_files[$i]->id . '&tab=statistics"><span class="material-icons-outlined zdm-md-1-5">equalizer</span></a>';
                                    } else {
                                        $zdm_download_id_link = esc_html__('Deleted download', 'zdm');
                                        $zdm_download_statistics_link = '<span class="material-icons-outlined zdm-md-1-5 zdm-help-text">equalizer</span>';
                                    }
                                ?>
                                    <tr>
                                        <td width="75%">
                                            <?= $zdm_download_id_link ?>
                                        </td>
                                        <td width="5%" align="center">
                                            <?= $zdm_download_statistics_link ?>
                                        </td>
                                        <td width="20%">
                                            <?= ZDMCore::number_format($zdm_best_downloads_files[$i]->count) ?>
                                        </td>
                                    </tr>
                                <?php
                                } // end for ($i = 0; $i < $zdm_best_downloads_files_count; $i++)
                                ?>
                            </tbody>
                        </table>

                    <?php
                    } // end if ($zdm_best_downloads_files != false)
                    ?>

                </div>

            <?php
            } // end if ($zdm_best_downloads != false OR $zdm_best_downloads_files != false)
            ?>

        </div><!-- end class="postbox-container zdm-postbox-col-md" -->

        <?php
        ////////////////////
        // Download statistics
        ////////////////////
        ?>

        <div class="postbox-container zdm-postbox-col-sm">

            <div class="postbox">

                <div class="inside">
                    <h3><span class="material-icons-round zdm-md-1">leaderboard</span> <?= esc_html__('Download statistics', 'zdm') ?></h3>
                </div>

                <table class="wp-list-table widefat">
                    <tr valign="top">
                        <th scope="row">
                            <b><?= esc_html__('Total', 'zdm') ?>:</b><br>
                            <b><?= esc_html__('Archives', 'zdm') ?>:</b><br>
                            <b><?= esc_html__('Files', 'zdm') ?>:</b>
                        </th>
                        <td valign="middle">
                            <?= ZDMCore::number_format(ZDMStat::get_downloads_count('all')) ?><br>
                            <?= ZDMCore::number_format(ZDMStat::get_downloads_count('archive')) ?><br>
                            <?= ZDMCore::number_format(ZDMStat::get_downloads_count('file')) ?>
                        </td>
                    </tr>
                    <tr valign="top">
                        <th colspan="2">
                            <hr>
                        </th>
                    </tr>
                    <tr valign="top">
                        <th scope="row">
                            <b><?= esc_html__('Last 24 hours', 'zdm') ?>:</b><br>
                            <b><?= esc_html__('Last 7 days', 'zdm') ?>:</b><br>
                            <b><?= esc_html__('Last 30 days', 'zdm') ?>:</b>
                        </th>
                        <td valign="middle">
                            <?php

                            // Last 24 hours
                            $zdm_statistics_last_2_days = ZDMCore::number_format(ZDMStat::get_downloads_count_time('all', 86400 * 2));
                            $zdm_statistics_last_1_day = ZDMCore::number_format(ZDMStat::get_downloads_count_time('all', 86400));
                            $zdm_statistics_last_1_day_before = $zdm_statistics_last_2_days - $zdm_statistics_last_1_day;
                            if ($zdm_statistics_last_1_day_before == 0) {
                                $zdm_statistics_last_1_day_trend = '--';
                                $zdm_statistics_last_1_day_trend_class = 'zdm-color-grey11';
                            } else {
                                if ($zdm_statistics_last_1_day > $zdm_statistics_last_1_day_before) {
                                    $zdm_statistics_last_1_day_trend = (($zdm_statistics_last_1_day / $zdm_statistics_last_1_day_before) - 1);
                                } else {
                                    $zdm_statistics_last_1_day_trend = (($zdm_statistics_last_1_day / $zdm_statistics_last_1_day_before) - 1) * 100;
                                }
                                $zdm_statistics_last_1_day_trend = ZDMCore::number_format($zdm_statistics_last_1_day_trend, 2);
                                if ($zdm_statistics_last_1_day_trend > 0)
                                    $zdm_statistics_last_1_day_trend_class = 'zdm-color-green';
                                else
                                    $zdm_statistics_last_1_day_trend_class = 'zdm-color-red';
                            }

                            // Last 7 days
                            $zdm_statistics_last_14_days = ZDMCore::number_format(ZDMStat::get_downloads_count_time('all', 86400 * 14));
                            $zdm_statistics_last_7_days = ZDMCore::number_format(ZDMStat::get_downloads_count_time('all', 86400 * 7));
                            $zdm_statistics_last_7_days_before = $zdm_statistics_last_14_days - $zdm_statistics_last_7_days;
                            if ($zdm_statistics_last_7_days_before == 0) {
                                $zdm_statistics_last_7_days_trend = '--';
                                $zdm_statistics_last_7_days_trend_class = 'zdm-color-grey11';
                            } else {
                                if ($zdm_statistics_last_7_days > $zdm_statistics_last_7_days_before) {
                                    $zdm_statistics_last_7_days_trend = (($zdm_statistics_last_7_days / $zdm_statistics_last_7_days_before) - 1);
                                } else {
                                    $zdm_statistics_last_7_days_trend = (($zdm_statistics_last_7_days / $zdm_statistics_last_7_days_before) - 1) * 100;
                                }
                                $zdm_statistics_last_7_days_trend = ZDMCore::number_format($zdm_statistics_last_7_days_trend, 2);
                                if ($zdm_statistics_last_7_days_trend > 0)
                                    $zdm_statistics_last_7_days_trend_class = 'zdm-color-green';
                                else
                                    $zdm_statistics_last_7_days_trend_class = 'zdm-color-red';
                            }

                            // Last 30 days
                            $zdm_statistics_last_60_days = ZDMCore::number_format(ZDMStat::get_downloads_count_time('all', 86400 * 60));
                            $zdm_statistics_last_30_days = ZDMCore::number_format(ZDMStat::get_downloads_count_time('all', 86400 * 30));
                            $zdm_statistics_last_30_days_before = $zdm_statistics_last_60_days - $zdm_statistics_last_30_days;
                            if ($zdm_statistics_last_30_days_before == 0) {
                                $zdm_statistics_last_30_days_trend = '--';
                                $zdm_statistics_last_30_days_trend_class = 'zdm-color-grey11';
                            } else {
                                if ($zdm_statistics_last_30_days > $zdm_statistics_last_30_days_before) {
                                    $zdm_statistics_last_30_days_trend = (($zdm_statistics_last_30_days / $zdm_statistics_last_30_days_before) - 1);
                                } else {
                                    $zdm_statistics_last_30_days_trend = (($zdm_statistics_last_30_days / $zdm_statistics_last_30_days_before) - 1) * 100;
                                }
                                $zdm_statistics_last_30_days_trend = ZDMCore::number_format($zdm_statistics_last_30_days_trend, 2);
                                if ($zdm_statistics_last_30_days_trend > 0)
                                    $zdm_statistics_last_30_days_trend_class = 'zdm-color-green';
                                else
                                    $zdm_statistics_last_30_days_trend_class = 'zdm-color-red';
                            }

                            ?>

                            <?= $zdm_statistics_last_1_day ?> <span class="<?= $zdm_statistics_last_1_day_trend_class ?>">(<?= $zdm_statistics_last_1_day_trend ?>%)</span><br>
                            <?= $zdm_statistics_last_7_days ?> <span class="<?= $zdm_statistics_last_7_days_trend_class ?>">(<?= $zdm_statistics_last_7_days_trend ?>%)</span><br>
                            <?= $zdm_statistics_last_30_days ?> <span class="<?= $zdm_statistics_last_30_days_trend_class ?>">(<?= $zdm_statistics_last_30_days_trend ?>%)</span>
                        </td>
                    </tr>
                </table>

            </div><!-- end class="postbox" -->

            <div class="postbox">

                <div class="inside" align="center">
                    <br>
                    <a href="admin.php?page=<?= ZDM__SLUG ?>-add-file" class="button button-primary"><?= esc_html__('Upload a new file', 'zdm') ?></a>
                    &nbsp;&nbsp;
                    <a href="admin.php?page=<?= ZDM__SLUG ?>-files" class="button button-secondary"><?= esc_html__('Files overview', 'zdm') ?></a>
                    <br><br>
                    <a href="admin.php?page=<?= ZDM__SLUG ?>-add-archive" class="button button-primary"><?= esc_html__('Create a new archive', 'zdm') ?></a>
                    &nbsp;&nbsp;
                    <a href="admin.php?page=<?= ZDM__SLUG ?>-ziparchive" class="button button-secondary"><?= esc_html__('Archive overview', 'zdm') ?></a>
                </div>

            </div><!-- end class="postbox" -->

        </div><!-- end class="postbox-container" -->

        <div class="postbox-container zdm-postbox-100">
            <?php
            require_once(plugin_dir_path(__FILE__) . '../inc/postbox_info.php');
            if (ZDMCore::licence() != true)
                require_once(plugin_dir_path(__FILE__) . '../inc/postbox_premium_info.php');
            ?>
        </div>

        <a class="alignright button" href="javascript:void(0);" onclick="window.scrollTo(0,0);" style="margin:3px 0 0 30px;"><?= esc_html__('To top', 'zdm') ?></a>

    </div><!-- end class="wrap" -->

<?php
} // end if (current_user_can(ZDM__STANDARD_USER_ROLE))