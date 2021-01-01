<?php
// Abort by direct access
if (!defined('ABSPATH')) {
    die;
}
?>
<div class="postbox">
    <div class="inside">
        <?php require_once (plugin_dir_path(__FILE__) . '../inc/template_header_info.php'); ?>
        <table class="form-table">
            <tbody>
                <tr valign="top">
                    <th scope="row"><?=ZDM__TITLE?> <?=esc_html__('Version', 'zdm')?>:</th>
                    <td valign="middle">
                        <?=esc_attr($zdm_options['version'])?> <a href="https://code.urban-base.net/z-downloads/release-notes/?utm_source=zdm_backend" target="_blank" title="<?=esc_html__('Release notes', 'zdm')?>"><?=esc_html__('release notes', 'zdm')?> <ion-icon name="open"></ion-icon></a>
                    </td>
                </tr>
                <tr valign="top">
                    <th scope="row"><?=esc_html__('Documentation and help', 'zdm')?>:</th>
                    <td valign="middle">
                        <a href="admin.php?page=<?=ZDM__SLUG?>-help"><?=esc_html__('Help pages for getting started', 'zdm')?></a>, 
                        <a href="https://code.urban-base.net/z-downloads/shortcodes/?utm_source=zdm_backend" target="_blank" title="<?=esc_html__('Shortcodes', 'zdm')?>"><?=esc_html__('Shortcodes', 'zdm')?> <ion-icon name="open"></ion-icon></a>, 
                        <a href="https://code.urban-base.net/z-downloads/farben/?utm_source=zdm_backend" target="_blank" title="<?=esc_html__('Colors', 'zdm')?>"><?=esc_html__('Colors', 'zdm')?> <ion-icon name="open"></ion-icon></a>, 
                        <a href="https://wordpress.org/plugins/z-downloads/#faq" target="_blank" title="<?=esc_html__('FAQ', 'zdm')?>"><?=esc_html__('FAQ', 'zdm')?> <ion-icon name="open"></ion-icon></a>
                    </td>
                </tr>
            </tbody>
        </table>
        <p><?=esc_html__('Do you have suggestions for improvement or suggestions for the plugin, then write me', 'zdm')?>: <a href="mailto:info@code.urban-base.net?subject=<?=ZDM__TITLE?> Verbesserungsvorschläge" target="_blank">info@code.urban-base.net</a></p>
        <p><?=esc_html__('If you like', 'zdm')?> <?=ZDM__TITLE?>, <?=esc_html__('then write a', 'zdm')?> <a href="https://wordpress.org/support/plugin/z-downloads/reviews/?filter=5#postform" target="_blank" title="<?=ZDM__TITLE?> <?=esc_html__('review', 'zdm')?>">★★★★★ <?=esc_html__('rating', 'zdm')?></a>. <?=esc_html__('You would help me a lot to make the plugin known.', 'zdm')?></p>
    </div>
</div>