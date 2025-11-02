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

    $zdm_upload_nonce = wp_create_nonce('datei-hochladen');
    $zdm_max_upload_bytes = (int) $zdm_options['max-upload-size-in-mb'] * 1024 * 1024;
    $zdm_max_upload_size = ZDMCore::file_size_convert($zdm_max_upload_bytes);
    $zdm_ajax_url = admin_url('admin-ajax.php');
?>
    <div class="wrap">
        <h1 class="wp-heading-inline"><?= esc_html__('Upload new file', 'zdm') ?></h1>
        <hr class="wp-header-end">
        <p><a class="button-secondary" href="admin.php?page=<?= ZDM__SLUG ?>-files"><?= esc_html__('Back to overview', 'zdm') ?></a></p>

        <form action="admin.php?page=<?= ZDM__SLUG ?>-files" method="post" enctype="multipart/form-data">
            <div class="postbox">
                <div class="inside">
                    <div class="zdm-dropzone dropzone"
                        data-zdm-dropzone
                        data-ajax-url="<?= esc_url($zdm_ajax_url) ?>"
                        data-nonce="<?= esc_attr($zdm_upload_nonce) ?>"
                        data-default-message="<?= esc_attr__('Drop your file here or click to browse', 'zdm') ?>"
                        data-upload-message="<?= esc_attr__('Uploading...', 'zdm') ?>"
                        data-success-message="<?= esc_attr__('Upload complete. Redirecting...', 'zdm') ?>"
                        data-duplicate-message="<?= esc_attr__('This file has already been uploaded.', 'zdm') ?>"
                        data-validation-message="<?= esc_attr__('Upload not allowed.', 'zdm') ?>"
                        data-error-message="<?= esc_attr__('Upload failed. Please try again.', 'zdm') ?>"
                    >
                        <div class="zdm-dropzone__inner">
                            <span class="material-icons-outlined">cloud_upload</span>
                            <p class="zdm-dropzone__title"><?= esc_html__('Drop your file here', 'zdm') ?></p>
                            <p class="zdm-dropzone__hint"><?= esc_html__('Drag & drop your file or use the button below.', 'zdm') ?></p>
                            <p class="zdm-dropzone__hint"><?= sprintf(esc_html__('Maximum upload size: %s', 'zdm'), $zdm_max_upload_size) ?></p>
                            <div class="zdm-dropzone__actions">
                                <button type="button" class="button button-secondary" data-zdm-dropzone-button><?= esc_html__('Choose file', 'zdm') ?></button>
                            </div>
                            <div class="zdm-dropzone__fallback">
                                <label class="screen-reader-text" for="zdm-fallback-file"><?= esc_html__('Choose file', 'zdm') ?></label>
                                <input id="zdm-fallback-file" type="file" name="file">
                                <input class="button button-primary" type="submit" name="submit" value="<?= esc_attr__('Upload', 'zdm') ?>">
                            </div>
                        </div>
                        <div class="zdm-dropzone__message" data-zdm-dropzone-feedback></div>
                    </div>

                    <input type="hidden" name="nonce" value="<?= esc_attr($zdm_upload_nonce) ?>">

                    <noscript>
                        <p class="zdm-dropzone__hint"><?= esc_html__('JavaScript is disabled. Use the classic upload above.', 'zdm') ?></p>
                    </noscript>

                    <hr>
                    <p>
                        <a href="admin.php?page=<?= ZDM__SLUG ?>-settings#zdm-expanded" title="<?= esc_html__('Change settings', 'zdm') ?>"><?= esc_html__('Secure file uploads', 'zdm') ?></a>: <b><?= $zdm_secure_file_upload ?></b><br>
                        <a href="admin.php?page=<?= ZDM__SLUG ?>-settings#zdm-expanded" title="<?= esc_html__('Change settings', 'zdm') ?>"><?= esc_html__('Maximum file size for uploads', 'zdm') ?></a>: <b><?= $zdm_max_upload_size ?></b>
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
