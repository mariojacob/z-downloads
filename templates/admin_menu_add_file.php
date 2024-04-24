<?php
// Abort by direct access
if (!defined('ABSPATH'))
    die;

if (current_user_can(ZDM__STANDARD_USER_ROLE)) {
    $zdm_options = get_option('zdm_options');

    $zdm_secure_file_upload = esc_html__('Disabled', 'zdm');
    if ($zdm_options['secure-file-upload'] == 'on') {
        $zdm_secure_file_upload = esc_html__('Enabled', 'zdm');
    }
?>
    <div class="wrap">
        <h1 class="wp-heading-inline"><?= esc_html__('Upload new file', 'zdm') ?></h1>
        <hr class="wp-header-end">
        <p><a class="button-secondary" href="admin.php?page=<?= ZDM__SLUG ?>-files"><?= esc_html__('Back to overview', 'zdm') ?></a></p>

        <form action="admin.php?page=<?= ZDM__SLUG ?>-files" method="post" enctype="multipart/form-data">
            <div class="postbox">
                <div class="inside" align="center">
                    <br>
                    <input type="file" name="file">
                    <br><br>
                    <input type="hidden" name="nonce" value="<?= wp_create_nonce('datei-hochladen') ?>">
                    <input class="button-primary" type="submit" name="submit" value="<?= esc_html__('Upload', 'zdm') ?>">
                    <br><br>
                    <hr>
                    <p>
                        <a href="admin.php?page=<?= ZDM__SLUG ?>-settings#zdm-expanded" title="<?= esc_html__('Change settings', 'zdm') ?>"><?= esc_html__('Secure file uploads', 'zdm') ?></a>: <b><?= $zdm_secure_file_upload ?></b><br>
                        <a href="admin.php?page=<?= ZDM__SLUG ?>-settings#zdm-expanded" title="<?= esc_html__('Change settings', 'zdm') ?>"><?= esc_html__('Maximum file size for uploads', 'zdm') ?></a>: <b><?= ZDMCore::file_size_convert($zdm_options['max-upload-size-in-mb'] * 1024 * 1024) ?></b>
                    </p>
                </div>
            </div>
        </form>
        <br>
        <?php
        require_once(plugin_dir_path(__FILE__) . '../inc/postbox_info_files.php');
        if (ZDMCore::licence() != true)
            require_once(plugin_dir_path(__FILE__) . '../inc/postbox_premium_info.php');
        ?>
    </div>
<?php
}
