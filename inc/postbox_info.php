<?php
// Abbruch bei direktem Zugriff
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
                        <?=esc_attr($zdm_options['version'])?> <a href="https://code.urban-base.net/z-downloads/release-notes/?utm_source=zdm_backend" target="_blank" title="<?=esc_html__('Release notes', 'zdm')?>"><?=esc_html__('release notes', 'zdm')?></a>
                    </td>
                </tr>
                <tr valign="top">
                    <th scope="row"><?=esc_html__('Dokumentation und Hilfe', 'zdm')?>:</th>
                    <td valign="middle">
                        <a href="admin.php?page=<?=ZDM__SLUG?>-help"><?=esc_html__('Hilfeseiten für den Einstieg', 'zdm')?></a>, 
                        <a href="https://code.urban-base.net/z-downloads/shortcodes/?utm_source=zdm_backend" target="_blank" title="<?=esc_html__('Shortcodes', 'zdm')?>"><?=esc_html__('Shortcodes', 'zdm')?></a>, 
                        <a href="https://code.urban-base.net/z-downloads/farben/?utm_source=zdm_backend" target="_blank" title="<?=esc_html__('Farben', 'zdm')?>"><?=esc_html__('Farben', 'zdm')?></a>, 
                        <a href="https://wordpress.org/plugins/z-downloads/#faq" target="_blank" title="<?=esc_html__('FAQ', 'zdm')?>"><?=esc_html__('FAQ', 'zdm')?></a>
                    </td>
                </tr>
            </tbody>
        </table>
        <p><?=esc_html__('Hast du Verbesserungsvorschläge oder Anregungen für das Plugin, dann schreibe mir', 'zdm')?>: <a href="mailto:info@code.urban-base.net?subject=<?=ZDM__TITLE?> Verbesserungsvorschläge" target="_blank">info@code.urban-base.net</a></p>
        <p><?=esc_html__('Wenn dir', 'zdm')?> <?=ZDM__TITLE?> <?=esc_html__('gefällt, dann schreibe gerne eine', 'zdm')?> <a href="https://wordpress.org/support/plugin/z-downloads/reviews/?filter=5#postform" target="_blank" title="<?=ZDM__TITLE?> <?=esc_html__('bewerten', 'zdm')?>">★★★★★ <?=esc_html__('Bewertung', 'zdm')?></a>. <?=esc_html__('Du würdest mir sehr dabei helfen das Plugin bekannter zu machen.', 'zdm')?></p>
    </div>
</div>