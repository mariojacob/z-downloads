<?php
// Abort by direct access
if (!defined('ABSPATH')) {
    die;
}
?>
<div class="postbox">
    <div class="inside">
        <?php require_once (plugin_dir_path(__FILE__) . '../inc/template_header_info.php'); ?>
        <p>
            <?=esc_html__('Files can be either one with one', 'zdm')?> 
            <a href="https://code.urban-base.net/z-downloads/shortcodes/?utm_source=zdm_backend" target="_blank" title="<?=esc_html__('Shortcodes', 'zdm')?>"><?=esc_html__('shortcode', 'zdm')?> <span class="material-icons-round zdm-md-1">open_in_new</span></a> 
            <?=esc_html__('be displayed as a download button or linked to an archive and displayed in the form of a ZIP file as a download button.', 'zdm')?>
        </p>
        <p><?=esc_html__('When updating or deleting a file, the contents of all ZIP files in which this file resides or is automatically updated.', 'zdm')?></p>
        <p><?php require_once (plugin_dir_path(__FILE__) . '../inc/template_help_text.php'); ?></p>
    </div>
</div>