<?php

// Last 24 hours
$zdm_statistics_last_2_days = ZDMCore::number_format(ZDMStat::get_downloads_count_time('all', 86400 * 2));
$zdm_statistics_last_1_day = ZDMCore::number_format(ZDMStat::get_downloads_count_time('all', 86400));
$zdm_statistics_last_1_day_before = $zdm_statistics_last_2_days - $zdm_statistics_last_1_day;
$zdm_statistics_last_1_day_trend_sign = '';
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
    if ($zdm_statistics_last_1_day_trend > 0) {
        $zdm_statistics_last_1_day_trend_class = 'zdm-color-green';
        $zdm_statistics_last_1_day_trend_sign = '+';
    } else {
        $zdm_statistics_last_1_day_trend_class = 'zdm-color-red';
    }
}

// Last 7 days
$zdm_statistics_last_14_days = ZDMCore::number_format(ZDMStat::get_downloads_count_time('all', 86400 * 14));
$zdm_statistics_last_7_days = ZDMCore::number_format(ZDMStat::get_downloads_count_time('all', 86400 * 7));
$zdm_statistics_last_7_days_before = $zdm_statistics_last_14_days - $zdm_statistics_last_7_days;
$zdm_statistics_last_7_days_trend_sign = '';
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
    if ($zdm_statistics_last_7_days_trend > 0) {
        $zdm_statistics_last_7_days_trend_class = 'zdm-color-green';
        $zdm_statistics_last_7_days_trend_sign = '+';
    } else {
        $zdm_statistics_last_7_days_trend_class = 'zdm-color-red';
    }
}

// Last 30 days
$zdm_statistics_last_60_days = ZDMCore::number_format(ZDMStat::get_downloads_count_time('all', 86400 * 60));
$zdm_statistics_last_30_days = ZDMCore::number_format(ZDMStat::get_downloads_count_time('all', 86400 * 30));
$zdm_statistics_last_30_days_before = $zdm_statistics_last_60_days - $zdm_statistics_last_30_days;
$zdm_statistics_last_30_days_trend_sign = '';
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
    if ($zdm_statistics_last_30_days_trend > 0) {
        $zdm_statistics_last_30_days_trend_class = 'zdm-color-green';
        $zdm_statistics_last_30_days_trend_sign = '+';
    } else {
        $zdm_statistics_last_30_days_trend_class = 'zdm-color-red';
    }
}

?>

<div class="zdm_dashboard_widget">
    <ul>
        <li><span class="zdm_dashboard_widget_leading"><?= esc_html__('Last 24 hours', 'zdm') ?>:</span></li>
        <li><span class="zdm_dashboard_widget_number zdm-color-primary"><?= $zdm_statistics_last_1_day ?></span> <span class="zdm_dashboard_widget_number zdm_dashboard_widget_number_trend <?= $zdm_statistics_last_1_day_trend_class ?>">(<?= $zdm_statistics_last_1_day_trend_sign ?><?= $zdm_statistics_last_1_day_trend ?>%)</span></li>
        <li><span class="zdm_dashboard_widget_leading"><?= esc_html__('Last 7 days', 'zdm') ?>:</span></li>
        <li><span class="zdm_dashboard_widget_number zdm-color-primary"><?= $zdm_statistics_last_7_days ?></span> <span class="zdm_dashboard_widget_number zdm_dashboard_widget_number_trend <?= $zdm_statistics_last_7_days_trend_class ?>">(<?= $zdm_statistics_last_7_days_trend_sign ?><?= $zdm_statistics_last_7_days_trend ?>%)</span></li>
        <li><span class="zdm_dashboard_widget_leading"><?= esc_html__('Last 30 days', 'zdm') ?>:</span></li>
        <li><span class="zdm_dashboard_widget_number zdm-color-primary"><?= $zdm_statistics_last_30_days ?></span> <span class="zdm_dashboard_widget_number zdm_dashboard_widget_number_trend <?= $zdm_statistics_last_30_days_trend_class ?>">(<?= $zdm_statistics_last_30_days_trend_sign ?><?= $zdm_statistics_last_30_days_trend ?>%)</span></li>
        <li class="zdm_dashboard_widget_mb"><span class="zdm_dashboard_widget_leading"><?= esc_html__('Total', 'zdm') ?>:</span></li>
        <li class="zdm_dashboard_widget_mb"><span class="zdm_dashboard_widget_number zdm-color-primary"><?= ZDMCore::number_format(ZDMStat::get_downloads_count('all')) ?></span></li>
    </ul>
    <div class="zdm_dashboard_widget_section">
        <a href="admin.php?page=<?= ZDM__SLUG ?>" class="button button-secondary"><?= esc_html__('All statistics', 'zdm') ?></a>
        <a href="admin.php?page=<?= ZDM__SLUG ?>-settings" class="button button-secondary"><?= esc_html__('Settings', 'zdm') ?></a>
    </div>
</div>