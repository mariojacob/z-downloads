<div class="zdm_dashboard_widget">
    <ul>
        <li><span class="zdm_dashboard_widget_leading"><?=esc_html__('Letzten 24 Stunden', 'zdm')?>:</span></li>
        <li><span class="zdm_dashboard_widget_number"><?=ZDMCore::number_format(ZDMStat::get_downloads_count_time('all', 86400))?></span></li>
        <li><span class="zdm_dashboard_widget_leading"><?=esc_html__('Letzten 7 Tage', 'zdm')?>:</span></li>
        <li><span class="zdm_dashboard_widget_number"><?=ZDMCore::number_format(ZDMStat::get_downloads_count_time('all', 86400*7))?></span></li>
        <li><span class="zdm_dashboard_widget_leading"><?=esc_html__('Letzten 30 Tage', 'zdm')?>:</span></li>
        <li><span class="zdm_dashboard_widget_number"><?=ZDMCore::number_format(ZDMStat::get_downloads_count_time('all', 86400*30))?></span></li>
        <li class="zdm_dashboard_widget_mb"><span class="zdm_dashboard_widget_leading"><?=esc_html__('Gesamt', 'zdm')?>:</span></li>
        <li class="zdm_dashboard_widget_mb"><span class="zdm_dashboard_widget_number"><?=ZDMCore::number_format(ZDMStat::get_downloads_count('all'))?></span></li>
    </ul>
    <div class="zdm_dashboard_widget_section">
        <a href="admin.php?page=<?=ZDM__SLUG?>" class="button button-secondary"><?=esc_html__('Gesamte Statistik', 'zdm')?></a>
    </div>
</div>