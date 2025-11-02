<?php
// Abort by direct access
if (!defined('ABSPATH')) {
    die;
}

$features = [
    [
        'icon' => 'download',
        'title' => esc_html__('Unlimited downloads', 'zdm'),
        'description' => esc_html__('Offer both single files and ZIP files in unlimited quantities for download, for maximum flexibility and user-friendliness.', 'zdm'),
        'free' => ['type' => 'icon', 'value' => 'done', 'class' => 'zdm-color-green'],
        'pro' => ['type' => 'icon', 'value' => 'done', 'class' => 'zdm-color-green'],
    ],
    [
        'icon' => 'query_stats',
        'title' => esc_html__('Download statistics', 'zdm'),
        'description' => esc_html__('Capture and analyze the download frequency of your files.', 'zdm'),
        'free' => ['type' => 'icon', 'value' => 'done', 'class' => 'zdm-color-green'],
        'pro' => ['type' => 'icon', 'value' => 'done', 'class' => 'zdm-color-green'],
    ],
    [
        'icon' => 'folder_zip',
        'title' => esc_html__('Unlimited files in ZIPs', 'zdm'),
        'description' => esc_html__('In the Premium version, you can pack an unlimited number of files into ZIP downloads. This lifts the limitation of the free version, which only allows 5 files per ZIP.', 'zdm'),
        'free' => ['type' => 'text', 'value' => esc_html__('5 files', 'zdm'), 'class' => 'zdm-color-red'],
        'pro' => ['type' => 'text', 'value' => esc_html__('Unlimited', 'zdm'), 'class' => 'zdm-color-green'],
    ],
    [
        'icon' => 'link',
        'title' => esc_html__('External downloads', 'zdm'),
        'description' => esc_html__('Seamlessly integrates external files for download on your site.', 'zdm'),
        'free' => ['type' => 'icon', 'value' => 'close', 'class' => 'zdm-color-red'],
        'pro' => ['type' => 'icon', 'value' => 'done', 'class' => 'zdm-color-green'],
    ],
    [
        'icon' => 'insights',
        'title' => esc_html__('Advanced statistics', 'zdm'),
        'description' => esc_html__('Utilize advanced statistics to gain deeper insights into download trends and user behavior. Exclusive for Premium users.', 'zdm'),
        'free' => ['type' => 'icon', 'value' => 'close', 'class' => 'zdm-color-red'],
        'pro' => ['type' => 'icon', 'value' => 'done', 'class' => 'zdm-color-green'],
    ],
];

$plan_labels = [
    'free' => esc_html__('Free', 'zdm'),
    'pro' => esc_html(ZDM__PRO),
];
?>
<div class="postbox zdm-premium-mini">
    <div class="inside">
        <div class="zdm-premium-mini__header">
            <div class="zdm-premium-mini__headline">
                <span class="zdm-premium-mini__badge"><?= esc_html(ZDM__PRO) ?></span>
                <h3><?= esc_html__('Premium Features at a Glance', 'zdm') ?></h3>
                <p><?= esc_html__('Compare Free vs. Premium and upgrade when you are ready.', 'zdm') ?></p>
            </div>
            <div class="zdm-premium-mini__cta">
                <a href="<?= ZDM__PRO_URL ?>" target="_blank" class="button button-primary button-small"><?= esc_html__('Upgrade', 'zdm') ?></a>
                <a href="admin.php?page=<?= ZDM__SLUG ?>-premium" class="button button-link zdm-premium-mini__link"><?= esc_html__('Details', 'zdm') ?></a>
            </div>
        </div>
        <div class="zdm-premium-mini__list">
            <?php foreach ($features as $feature) :
                $free = $feature['free'];
                $pro = $feature['pro'];
                ?>
                <div class="zdm-premium-mini__row">
                    <div class="zdm-premium-mini__info">
                        <span class="material-icons-outlined zdm-premium-mini__icon"><?= esc_html($feature['icon']) ?></span>
                        <div>
                            <strong><?= $feature['title'] ?></strong>
                            <p><?= $feature['description'] ?></p>
                        </div>
                    </div>
                    <div class="zdm-premium-mini__plans">
                        <span class="zdm-premium-mini__plan-label"><?= $plan_labels['free'] ?></span>
                        <span class="zdm-premium-mini__plan-value <?= isset($free['class']) ? esc_attr($free['class']) : '' ?>">
                            <?php if ('icon' === $free['type']) : ?>
                                <span class="material-icons-outlined"><?= esc_html($free['value']) ?></span>
                            <?php else : ?>
                                <?= $free['value'] ?>
                            <?php endif; ?>
                        </span>
                        <span class="zdm-premium-mini__plan-label"><?= $plan_labels['pro'] ?></span>
                        <span class="zdm-premium-mini__plan-value <?= isset($pro['class']) ? esc_attr($pro['class']) : '' ?>">
                            <?php if ('icon' === $pro['type']) : ?>
                                <span class="material-icons-outlined"><?= esc_html($pro['value']) ?></span>
                            <?php else : ?>
                                <?= $pro['value'] ?>
                            <?php endif; ?>
                        </span>
                    </div>
                </div>
            <?php endforeach; ?>
        </div>
    </div>
</div>