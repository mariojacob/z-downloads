<?php
// Abbruch bei direktem Zugriff
if (!defined('ABSPATH')) {
    die;
}
?>
<div class="postbox">
    <div class="inside">
        <?php require_once (plugin_dir_path(__FILE__) . '../inc/template_header_info.php'); ?>
        <p>
            <?=esc_html__('Dateien können entweder einzeln mit einem', 'zdm')?> 
            <a href="https://code.urban-base.net/z-downloads/shortcodes/?utm_source=zdm_backend" target="_blank" title="<?=esc_html__('Shortcodes', 'zdm')?>"><?=esc_html__('Shortcode', 'zdm')?></a> 
            <?=esc_html__('als Download-Button angezeigt werden oder zu einem Archiv verknüpft und in Form einer ZIP-Datei als Download-Button angezeigt werden.', 'zdm')?>
        </p>
        <p><?=esc_html__('Wenn du eine Datei aktualisierst oder löscht, dann aktualisiert sich der Inhalt aller ZIP-Dateien in der sich diese Datei befindet oder befand automatisch.', 'zdm')?></p>
        <p><?php require_once (plugin_dir_path(__FILE__) . '../inc/template_help_text.php'); ?></p>
    </div>
</div>