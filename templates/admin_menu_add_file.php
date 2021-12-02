<?php
// Abort by direct access
if (!defined('ABSPATH'))
    die;

if (current_user_can(ZDM__STANDARD_USER_ROLE)) {
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
                    <p><?= esc_html__('Maximum file size for uploads', 'zdm') ?>: <?= ZDMCore::file_size_convert(ZDMCore::file_size_convert_str2bytes(ini_get('upload_max_filesize'))) ?></p>
                </div>
            </div>
        </form>
        <br>
        <?php require_once(plugin_dir_path(__FILE__) . '../inc/postbox_info_files.php'); ?>
    </div>
<?php
}
