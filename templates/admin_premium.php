<?php
// Abort by direct access
if (!defined('ABSPATH'))
    die;

if (current_user_can(ZDM__STANDARD_USER_ROLE)) {
?>
    <div class="wrap">
        <h1 class="wp-heading-inline"><?= ZDM__TITLE ?> <?= ZDM__PRO ?></h1>
        <hr class="wp-header-end">
        <br>

        <div class="postbox-container zdm-premium-postbox-col-md">
            <div class="postbox">
                <div class="inside">
                    <table class="form-table zdm-table-premium-xl">
                        <tbody>
                            <tr valign="top">
                                <th scope="row"></th>
                                <td valign="middle" width="20%">
                                    <h3><?= esc_html__('Free', 'zdm') ?></h3>
                                </td>
                                <td valign="middle" width="20%">
                                    <h3><?= ZDM__PRO ?></h3>
                                </td>
                            </tr>
                            <tr valign="top">
                                <th scope="row"><?= esc_html__('Unlimited downloads', 'zdm') ?>
                                    <div class="zdm-tooltip">
                                        <span class="material-icons-outlined zdm-md-1 zdm-color-grey7">info</span>
                                        <span class="zdm-tooltiptext"><?= esc_html__('Offer both single files and ZIP files in unlimited quantities for download, for maximum flexibility and user-friendliness.', 'zdm') ?></span>
                                    </div>
                                </th>
                                <td valign="middle">
                                    <span class="material-icons-outlined zdm-color-green">done</span>
                                </td>
                                <td valign="middle">
                                    <span class="material-icons-outlined zdm-color-green">done</span>
                                </td>
                            </tr>
                            <tr valign="top">
                                <th scope="row"><?= esc_html__('Download statistics', 'zdm') ?>
                                    <div class="zdm-tooltip">
                                        <span class="material-icons-outlined zdm-md-1 zdm-color-grey7">info</span>
                                        <span class="zdm-tooltiptext"><?= esc_html__('Capture and analyze the download frequency of your files.', 'zdm') ?></span>
                                    </div>
                                </th>
                                <td valign="middle">
                                    <span class="material-icons-outlined zdm-color-green">done</span>
                                </td>
                                <td valign="middle">
                                    <span class="material-icons-outlined zdm-color-green">done</span>
                                </td>
                            </tr>
                            <tr valign="top">
                                <th scope="row"><?= esc_html__('GDPR compliant', 'zdm') ?>
                                    <div class="zdm-tooltip">
                                        <span class="material-icons-outlined zdm-md-1 zdm-color-grey7">info</span>
                                        <span class="zdm-tooltiptext"><?= esc_html__('By default, IP addresses of users are anonymized, enhancing privacy and compliance with data protection regulations.', 'zdm') ?></span>
                                    </div>
                                </th>
                                <td valign="middle">
                                    <span class="material-icons-outlined zdm-color-green">done</span>
                                </td>
                                <td valign="middle">
                                    <span class="material-icons-outlined zdm-color-green">done</span>
                                </td>
                            </tr>
                            <tr valign="top">
                                <th scope="row"><?= esc_html__('Shortcodes', 'zdm') ?>
                                    <div class="zdm-tooltip">
                                        <span class="material-icons-outlined zdm-md-1 zdm-color-grey7">info</span>
                                        <span class="zdm-tooltiptext"><?= esc_html__('With the shortcodes, you can easily embed download buttons into your pages or posts. Flexibility and efficiency in one package.', 'zdm') ?></span>
                                    </div>
                                </th>
                                <td valign="middle">
                                    <span class="material-icons-outlined zdm-color-green">done</span>
                                </td>
                                <td valign="middle">
                                    <span class="material-icons-outlined zdm-color-green">done</span>
                                </td>
                            </tr>
                            <tr valign="top">
                                <th scope="row"><?= esc_html__('Download button', 'zdm') ?>
                                    <div class="zdm-tooltip">
                                        <span class="material-icons-outlined zdm-md-1 zdm-color-grey7">info</span>
                                        <span class="zdm-tooltiptext"><?= esc_html__('Customize the download button for each individual download. A variety of standard colors are provided for your convenience.', 'zdm') ?></span>
                                    </div>
                                </th>
                                <td valign="middle">
                                    <span class="material-icons-outlined zdm-color-green">done</span>
                                </td>
                                <td valign="middle">
                                    <span class="material-icons-outlined zdm-color-green">done</span>
                                </td>
                            </tr>
                            <tr valign="top">
                                <th scope="row"><?= esc_html__('Custom button text', 'zdm') ?>
                                    <div class="zdm-tooltip">
                                        <span class="material-icons-outlined zdm-md-1 zdm-color-grey7">info</span>
                                        <span class="zdm-tooltiptext"><?= esc_html__('Personalize your download buttons with custom text to improve user experience and make the download process more intuitive.', 'zdm') ?></span>
                                    </div>
                                </th>
                                <td valign="middle">
                                    <span class="material-icons-outlined zdm-color-green">done</span>
                                </td>
                                <td valign="middle">
                                    <span class="material-icons-outlined zdm-color-green">done</span>
                                </td>
                            </tr>
                            <tr valign="top">
                                <th scope="row"><?= esc_html__('Show file size', 'zdm') ?>
                                    <div class="zdm-tooltip">
                                        <span class="material-icons-outlined zdm-md-1 zdm-color-grey7">info</span>
                                        <span class="zdm-tooltiptext"><?= esc_html__('Display the file size in the frontend to provide users with important information about the download.', 'zdm') ?></span>
                                    </div>
                                </th>
                                <td valign="middle">
                                    <span class="material-icons-outlined zdm-color-green">done</span>
                                </td>
                                <td valign="middle">
                                    <span class="material-icons-outlined zdm-color-green">done</span>
                                </td>
                            </tr>
                            <tr valign="top">
                                <th scope="row"><?= esc_html__('Show download count', 'zdm') ?>
                                    <div class="zdm-tooltip">
                                        <span class="material-icons-outlined zdm-md-1 zdm-color-grey7">info</span>
                                        <span class="zdm-tooltiptext"><?= esc_html__('Display the number of downloads in the frontend, letting your users know how popular a file is.', 'zdm') ?></span>
                                    </div>
                                </th>
                                <td valign="middle">
                                    <span class="material-icons-outlined zdm-color-green">done</span>
                                </td>
                                <td valign="middle">
                                    <span class="material-icons-outlined zdm-color-green">done</span>
                                </td>
                            </tr>
                            <tr valign="top">
                                <th scope="row"><?= esc_html__('Embed an audio player', 'zdm') ?>
                                    <div class="zdm-tooltip">
                                        <span class="material-icons-outlined zdm-md-1 zdm-color-grey7">info</span>
                                        <span class="zdm-tooltiptext"><?= esc_html__('Embed an audio player into your pages or posts, providing a seamless listening experience directly on your website. No more redirecting to external pages.', 'zdm') ?></span>
                                    </div>
                                </th>
                                <td valign="middle">
                                    <span class="material-icons-outlined zdm-color-green">done</span>
                                </td>
                                <td valign="middle">
                                    <span class="material-icons-outlined zdm-color-green">done</span>
                                </td>
                            </tr>
                            <tr valign="top">
                                <th scope="row"><?= esc_html__('Embed a video player', 'zdm') ?>
                                    <div class="zdm-tooltip">
                                        <span class="material-icons-outlined zdm-md-1 zdm-color-grey7">info</span>
                                        <span class="zdm-tooltiptext"><?= esc_html__('Embed a video player into your pages or posts for a seamless viewing experience directly on your website. Enhance user engagement without needing to redirect to external sites.', 'zdm') ?></span>
                                    </div>
                                </th>
                                <td valign="middle">
                                    <span class="material-icons-outlined zdm-color-green">done</span>
                                </td>
                                <td valign="middle">
                                    <span class="material-icons-outlined zdm-color-green">done</span>
                                </td>
                            </tr>
                            <tr valign="top">
                                <th scope="row"><?= esc_html__('Unlimited files', 'zdm') ?>
                                    <div class="zdm-tooltip">
                                        <span class="material-icons-outlined zdm-md-1 zdm-color-grey7">info</span>
                                        <span class="zdm-tooltiptext"><?= esc_html__('In the Premium version, you can pack an unlimited number of files into ZIP downloads. This lifts the limitation of the free version, which only allows 5 files per ZIP.', 'zdm') ?></span>
                                    </div>
                                </th>
                                <td valign="middle">
                                    <span class="zdm-color-red"><?= esc_html__('5 files', 'zdm') ?></span>
                                </td>
                                <td valign="middle">
                                    <span class="zdm-color-green"><?= esc_html__('Unlimited', 'zdm') ?></span>
                                </td>
                            </tr>
                            <tr valign="top">
                                <th scope="row"><?= esc_html__('External downloads', 'zdm') ?>
                                    <div class="zdm-tooltip">
                                        <span class="material-icons-outlined zdm-md-1 zdm-color-grey7">info</span>
                                        <span class="zdm-tooltiptext"><?= esc_html__('Seamlessly integrates external files for download on your site. Example:', 'zdm') ?> <code>[zdownload url="https://example.com/file.zip"]</code>.</span>
                                    </div>
                                </th>
                                <td valign="middle">
                                    <span class="material-icons-outlined zdm-color-red">close</span>
                                </td>
                                <td valign="middle">
                                    <span class="material-icons-outlined zdm-color-green">done</span>
                                </td>
                            </tr>
                            <tr valign="top">
                                <th scope="row"><?= esc_html__('Advanced statistics', 'zdm') ?>
                                    <div class="zdm-tooltip">
                                        <span class="material-icons-outlined zdm-md-1 zdm-color-grey7">info</span>
                                        <span class="zdm-tooltiptext"><?= esc_html__('Utilize advanced statistics to gain deeper insights into download trends and user behavior. Exclusive for Premium users.', 'zdm') ?></span>
                                    </div>
                                </th>
                                <td valign="middle">
                                    <span class="material-icons-outlined zdm-color-red">close</span>
                                </td>
                                <td valign="middle">
                                    <span class="material-icons-outlined zdm-color-green">done</span>
                                </td>
                            </tr>
                            <tr valign="top">
                                <th scope="row"><?= esc_html__('MD5 Hash Display', 'zdm') ?>
                                    <div class="zdm-tooltip">
                                        <span class="material-icons-outlined zdm-md-1 zdm-color-grey7">info</span>
                                        <span class="zdm-tooltiptext"><?= esc_html__('Provide MD5 hashes of your downloads in the frontend. This additional security measure gives your users confidence in the integrity of your files. Only available in the Premium version.', 'zdm') ?></span>
                                    </div>
                                </th>
                                <td valign="middle">
                                    <span class="material-icons-outlined zdm-color-red">close</span>
                                </td>
                                <td valign="middle">
                                    <span class="material-icons-outlined zdm-color-green">done</span>
                                </td>
                            </tr>
                            <tr valign="top">
                                <th scope="row"><?= esc_html__('SHA1 Hash Display', 'zdm') ?>
                                    <div class="zdm-tooltip">
                                        <span class="material-icons-outlined zdm-md-1 zdm-color-grey7">info</span>
                                        <span class="zdm-tooltiptext"><?= esc_html__('Provide SHA1 hashes of your downloads in the frontend. This additional security measure gives your users confidence in the integrity of your files. Only available in the Premium version.', 'zdm') ?></span>
                                    </div>
                                </th>
                                <td valign="middle">
                                    <span class="material-icons-outlined zdm-color-red">close</span>
                                </td>
                                <td valign="middle">
                                    <span class="material-icons-outlined zdm-color-green">done</span>
                                </td>
                            </tr>
                        </tbody>
                    </table>
                    <br>
                    <h4><?= esc_html__('Unlock the full potential of Z-Downloads. More control, more features, more success. Upgrade to Premium now!', 'zdm') ?></h4>
                    <br>
                    <p><a href="<?= ZDM__PRO_URL ?>" target="_blank" class="button button-primary"><?= esc_html__('Upgrade to Premium', 'zdm') ?></a></p>
                </div>
            </div>
        </div>

        <div class="postbox-container zdm-premium-postbox-col-sm">
            <div class="postbox">
                <div class="inside">
                    <h3><?= esc_html__('Write a review', 'zdm') ?></h3>
                    <p><?= esc_html__('If you like', 'zdm') ?> <?= ZDM__TITLE ?>, <?= esc_html__('then write a', 'zdm') ?> <a href="https://wordpress.org/support/plugin/z-downloads/reviews/?filter=5#postform" target="_blank" title="<?= ZDM__TITLE ?> <?= esc_html__('rating', 'zdm') ?>">★★★★★ <?= esc_html__('rating', 'zdm') ?> <span class="material-icons-round zdm-md-1">open_in_new</span></a>. <?= esc_html__('You would help me a lot to make the plugin known.', 'zdm') ?></p>
                    <hr>
                    <h3><?= esc_html__('Suggestions for improvement', 'zdm') ?></h3>
                    <p><?= esc_html__('Do you have suggestions for improvement or suggestions for the plugin, then write me', 'zdm') ?>: <a href="mailto:info@code.urban-base.net?subject=<?= ZDM__TITLE ?> <?= esc_html__('suggestions for improvement', 'zdm') ?>" target="_blank">info@code.urban-base.net</a></p>
                </div>
            </div>
        </div>

    </div>
<?php
}
