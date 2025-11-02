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
<div class="postbox zdm-premium-box">
    <div class="inside">
        <div class="zdm-premium-box__header">
            <div class="zdm-premium-box__visual">
                <img class="zdm-premium-banner" src="<?= ZDM__PLUGIN_URL ?>assets/z-downloads-premium-backend-mini.png" alt="Z-Downloads Premium">
            </div>
            <div class="zdm-premium-box__headline">
                <span class="zdm-premium-box__badge"><?= esc_html(ZDM__PRO) ?></span>
                <h3><?= esc_html__('Upgrade to unlock all premium features', 'zdm') ?></h3>
                <p><?= esc_html__('Boost your download experience with automation, insights and exclusive tools for power users.', 'zdm') ?></p>
                <div class="zdm-premium-box__cta">
                    <a href="<?= ZDM__PRO_URL ?>" target="_blank" class="button button-primary"><?= esc_html__('Upgrade to Premium', 'zdm') ?></a>
                    <a href="admin.php?page=<?= ZDM__SLUG ?>-premium" class="button zdm-button-ghost"><?= esc_html__('Discover all benefits', 'zdm') ?></a>
                </div>
            </div>
        </div>
        <div class="zdm-premium-box__features">
            <?php foreach ($features as $feature) : ?>
                <article class="zdm-premium-feature">
                    <div class="zdm-premium-feature__icon">
                        <span class="material-icons-outlined zdm-md-2"><?= esc_html($feature['icon']) ?></span>
                    </div>
                    <div class="zdm-premium-feature__content">
                        <h4><?= $feature['title'] ?></h4>
                        <p><?= $feature['description'] ?></p>
                    </div>
                    <div class="zdm-premium-feature__plans">
                        <?php foreach (['free', 'pro'] as $plan_key) :
                            $plan = $feature[$plan_key];
                            ?>
                            <div class="zdm-premium-feature__plan">
                                <span class="zdm-premium-feature__plan-label"><?= $plan_labels[$plan_key] ?></span>
                                <span class="zdm-premium-feature__plan-value <?= isset($plan['class']) ? esc_attr($plan['class']) : '' ?>">
                                    <?php if ('icon' === $plan['type']) : ?>
                                        <span class="material-icons-outlined"><?= esc_html($plan['value']) ?></span>
                                    <?php else : ?>
                                        <?= $plan['value'] ?>
                                    <?php endif; ?>
                                </span>
                            </div>
                        <?php endforeach; ?>
                    </div>
                </article>
            <?php endforeach; ?>
        </div>
    </div>
</div>