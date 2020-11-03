<?php
// Abbruch bei direktem Zugriff
if (!defined('ABSPATH')) {
    die;
}
?>
<div class="postbox">
    <div class="inside">
        <?php require_once (plugin_dir_path(__FILE__) . '../inc/template_header_info.php'); ?>
        <p><?=esc_html__('Archives are compressed ZIP files that you can use to link files you\'ve uploaded.', 'zdm')?></p>
        <p>
            <?=esc_html__('These files are automatically saved by the plugin in a ZIP file and can be combined with a', 'zdm')?> 
            <a href="https://code.urban-base.net/z-downloads/shortcodes/?utm_source=zdm_backend" target="_blank" title="<?=esc_html__('Shortcodes', 'zdm')?>"><?=esc_html__('shortcode', 'zdm')?> <ion-icon name="open"></ion-icon></a> 
            <?=esc_html__('be displayed as a download button.', 'zdm')?>
        </p>
        <p><?=esc_html__('When updating or deleting a file, the contents of all ZIP files in which this file resides or is automatically updated.', 'zdm')?></p>
        <p><?php require_once (plugin_dir_path(__FILE__) . '../inc/template_help_text.php'); ?></p>
    </div>
</div>