<?php
// Abort by direct access
if (!defined('ABSPATH'))
    die;
?>
<div class="postbox">
    <div class="inside">
        <img class="zdm-premium-banner" src="<?= ZDM__PLUGIN_URL ?>assets/z-downloads-premium-backend-mini.png" height="50%" alt="Z-Downloads Premium Banner">
        <table class="form-table zdm-table-premium">
            <tbody>
                <tr valign="top">
                    <th scope="row"></th>
                    <td valign="middle" width="20%">
                        <h3><?= esc_html__('Free', 'zdm') ?></h3>
                    </td>
                    <td valign="middle" width="20%">
                        <h3><?= ZDM__PRO ?></h3>
                    </td>
                    <td valign="middle" width="30%"></td>
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
                    <td valign="middle"></td>
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
                    <td valign="middle"></td>
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
                    <td valign="middle"></td>
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
                    <td valign="middle"></td>
                </tr>
                <tr valign="top">
                    <th scope="row"><?= esc_html__('MD5 and SHA1 Hash Display', 'zdm') ?>
                        <div class="zdm-tooltip">
                            <span class="material-icons-outlined zdm-md-1 zdm-color-grey7">info</span>
                            <span class="zdm-tooltiptext"><?= esc_html__('Provide MD5 and SHA1 hashes of your downloads in the frontend. This additional security measure gives your users confidence in the integrity of your files. Only available in the Premium version.', 'zdm') ?></span>
                        </div>
                    </th>
                    <td valign="middle">
                        <span class="material-icons-outlined zdm-color-red">close</span>
                    </td>
                    <td valign="middle">
                        <span class="material-icons-outlined zdm-color-green">done</span>
                    </td>
                    <td valign="middle"></td>
                </tr>
                <tr valign="top">
                    <th scope="row"><a href="#"><?= esc_html__('All the benefits of Premium at a glance') ?></a></th>
                    <td valign="middle"></td>
                    <td valign="middle"></td>
                    <td valign="middle"></td>
                </tr>
            </tbody>
        </table>
        <p><a href="<?= ZDM__PRO_URL ?>" target="_blank" class="button button-primary"><?= esc_html__('Upgrade to Premium') ?></a></p>
    </div>
</div>