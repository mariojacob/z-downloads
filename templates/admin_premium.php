<?php
// Abort by direct access
if (!defined('ABSPATH')) {
    die;
}

if (current_user_can(ZDM__STANDARD_USER_ROLE)) {
    $shared_features = [
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
            'icon' => 'gpp_good',
            'title' => esc_html__('GDPR compliant', 'zdm'),
            'description' => esc_html__('By default, IP addresses of users are anonymized, enhancing privacy and compliance with data protection regulations.', 'zdm'),
            'free' => ['type' => 'icon', 'value' => 'done', 'class' => 'zdm-color-green'],
            'pro' => ['type' => 'icon', 'value' => 'done', 'class' => 'zdm-color-green'],
        ],
        [
            'icon' => 'code',
            'title' => esc_html__('Shortcodes', 'zdm'),
            'description' => esc_html__('With the shortcodes, you can easily embed download buttons into your pages or posts. Flexibility and efficiency in one package.', 'zdm'),
            'free' => ['type' => 'icon', 'value' => 'done', 'class' => 'zdm-color-green'],
            'pro' => ['type' => 'icon', 'value' => 'done', 'class' => 'zdm-color-green'],
        ],
        [
            'icon' => 'smart_button',
            'title' => esc_html__('Download button', 'zdm'),
            'description' => esc_html__('Customize the download button for each individual download. A variety of standard colors are provided for your convenience.', 'zdm'),
            'free' => ['type' => 'icon', 'value' => 'done', 'class' => 'zdm-color-green'],
            'pro' => ['type' => 'icon', 'value' => 'done', 'class' => 'zdm-color-green'],
        ],
        [
            'icon' => 'text_fields',
            'title' => esc_html__('Custom button text', 'zdm'),
            'description' => esc_html__('Personalize your download buttons with custom text to improve user experience and make the download process more intuitive.', 'zdm'),
            'free' => ['type' => 'icon', 'value' => 'done', 'class' => 'zdm-color-green'],
            'pro' => ['type' => 'icon', 'value' => 'done', 'class' => 'zdm-color-green'],
        ],
        [
            'icon' => 'straighten',
            'title' => esc_html__('Show file size', 'zdm'),
            'description' => esc_html__('Display the file size in the frontend to provide users with important information about the download.', 'zdm'),
            'free' => ['type' => 'icon', 'value' => 'done', 'class' => 'zdm-color-green'],
            'pro' => ['type' => 'icon', 'value' => 'done', 'class' => 'zdm-color-green'],
        ],
        [
            'icon' => 'leaderboard',
            'title' => esc_html__('Show download count', 'zdm'),
            'description' => esc_html__('Display the number of downloads in the frontend, letting your users know how popular a file is.', 'zdm'),
            'free' => ['type' => 'icon', 'value' => 'done', 'class' => 'zdm-color-green'],
            'pro' => ['type' => 'icon', 'value' => 'done', 'class' => 'zdm-color-green'],
        ],
        [
            'icon' => 'headphones',
            'title' => esc_html__('Embed an audio player', 'zdm'),
            'description' => esc_html__('Embed an audio player into your pages or posts, providing a seamless listening experience directly on your website. No more redirecting to external pages.', 'zdm'),
            'free' => ['type' => 'icon', 'value' => 'done', 'class' => 'zdm-color-green'],
            'pro' => ['type' => 'icon', 'value' => 'done', 'class' => 'zdm-color-green'],
        ],
        [
            'icon' => 'smart_display',
            'title' => esc_html__('Embed a video player', 'zdm'),
            'description' => esc_html__('Embed a video player into your pages or posts for a seamless viewing experience directly on your website. Enhance user engagement without needing to redirect to external sites.', 'zdm'),
            'free' => ['type' => 'icon', 'value' => 'done', 'class' => 'zdm-color-green'],
            'pro' => ['type' => 'icon', 'value' => 'done', 'class' => 'zdm-color-green'],
        ],
    ];

    $premium_features = [
        [
            'icon' => 'inventory_2',
            'title' => esc_html__('Unlimited files', 'zdm'),
            'description' => esc_html__('In the Premium version, you can pack an unlimited number of files into ZIP downloads. This lifts the limitation of the free version, which only allows 5 files per ZIP.', 'zdm'),
            'free' => ['type' => 'text', 'value' => esc_html__('5 files', 'zdm'), 'class' => 'zdm-color-red'],
            'pro' => ['type' => 'text', 'value' => esc_html__('Unlimited', 'zdm'), 'class' => 'zdm-color-green'],
            'highlight' => 'pro',
        ],
        [
            'icon' => 'link',
            'title' => esc_html__('External downloads', 'zdm'),
            'description' => esc_html__('Seamlessly integrates external files for download on your site.', 'zdm'),
            'free' => ['type' => 'icon', 'value' => 'close', 'class' => 'zdm-color-red'],
            'pro' => ['type' => 'icon', 'value' => 'done', 'class' => 'zdm-color-green'],
            'highlight' => 'pro',
        ],
        [
            'icon' => 'insights',
            'title' => esc_html__('Advanced statistics', 'zdm'),
            'description' => esc_html__('Utilize advanced statistics to gain deeper insights into download trends and user behavior. Exclusive for Premium users.', 'zdm'),
            'free' => ['type' => 'icon', 'value' => 'close', 'class' => 'zdm-color-red'],
            'pro' => ['type' => 'icon', 'value' => 'done', 'class' => 'zdm-color-green'],
            'highlight' => 'pro',
        ],
        [
            'icon' => 'fingerprint',
            'title' => esc_html__('MD5 hash display', 'zdm'),
            'description' => esc_html__('Provide MD5 hashes of your downloads in the frontend. This additional security measure gives your users confidence in the integrity of your files. Only available in the Premium version.', 'zdm'),
            'free' => ['type' => 'icon', 'value' => 'close', 'class' => 'zdm-color-red'],
            'pro' => ['type' => 'icon', 'value' => 'done', 'class' => 'zdm-color-green'],
            'highlight' => 'pro',
        ],
        [
            'icon' => 'encrypted',
            'title' => esc_html__('SHA1 hash display', 'zdm'),
            'description' => esc_html__('Provide SHA1 hashes of your downloads in the frontend. This additional security measure gives your users confidence in the integrity of your files. Only available in the Premium version.', 'zdm'),
            'free' => ['type' => 'icon', 'value' => 'close', 'class' => 'zdm-color-red'],
            'pro' => ['type' => 'icon', 'value' => 'done', 'class' => 'zdm-color-green'],
            'highlight' => 'pro',
        ],
    ];

    $plan_labels = [
        'free' => esc_html__('Free', 'zdm'),
        'pro' => esc_html(ZDM__PRO),
    ];
    ?>
    <div class="wrap zdm-premium-admin">
        <h1 class="wp-heading-inline"><?= ZDM__TITLE ?> <?= ZDM__PRO ?></h1>
        <hr class="wp-header-end">
        <br>

        <div class="postbox-container zdm-premium-postbox-col-md">
            <div class="postbox zdm-premium-box">
                <div class="inside">
                    <div class="zdm-premium-box__header">
                        <div class="zdm-premium-box__visual">
                            <img class="zdm-premium-banner" src="<?= ZDM__PLUGIN_URL ?>assets/z-downloads-premium-backend-mini.png" alt="Z-Downloads Premium">
                        </div>
                        <div class="zdm-premium-box__headline">
                            <span class="zdm-premium-box__badge"><?= esc_html(ZDM__PRO) ?></span>
                            <h3><?= sprintf(esc_html__('Unlock the full potential of %s', 'zdm'), ZDM__TITLE) ?></h3>
                            <p><?= esc_html__('Automate workflows, surface actionable insights and deliver a polished download experience for your users.', 'zdm') ?></p>
                            <div class="zdm-premium-box__cta">
                                <a href="<?= ZDM__PRO_URL ?>" target="_blank" class="button button-primary"><?= esc_html__('Upgrade to Premium', 'zdm') ?></a>
                                <a href="admin.php?page=<?= ZDM__SLUG ?>-premium" class="button zdm-button-ghost"><?= esc_html__('Explore all benefits', 'zdm') ?></a>
                            </div>
                        </div>
                    </div>

                    <div class="zdm-premium-box__sections">
                        <section class="zdm-premium-section">
                            <header class="zdm-premium-section__header">
                                <span class="zdm-premium-section__badge"><?= esc_html__('Included', 'zdm') ?></span>
                                <h3><?= esc_html__('Everything you need to launch fast', 'zdm') ?></h3>
                                <p><?= esc_html__('All core features are available in the free plan so you can build and ship professional download hubs immediately.', 'zdm') ?></p>
                            </header>
                            <div class="zdm-premium-feature-grid">
                                <?php foreach ($shared_features as $feature) : ?>
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
                                                $plan_classes = 'zdm-premium-feature__plan';
                                                if (isset($feature['highlight']) && $feature['highlight'] === $plan_key) {
                                                    $plan_classes .= ' zdm-premium-feature__plan--highlight';
                                                }
                                                ?>
                                                <div class="<?= esc_attr($plan_classes) ?>">
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
                        </section>

                        <section class="zdm-premium-section">
                            <header class="zdm-premium-section__header">
                                <span class="zdm-premium-section__badge zdm-premium-section__badge--accent"><?= esc_html__('Premium boost', 'zdm') ?></span>
                                <h3><?= esc_html__('Scale without limits and build trust', 'zdm') ?></h3>
                                <p><?= esc_html__('Upgrade to unlock automation, additional capacity and advanced transparency for demanding teams.', 'zdm') ?></p>
                            </header>
                            <div class="zdm-premium-feature-grid">
                                <?php foreach ($premium_features as $feature) : ?>
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
                                                $plan_classes = 'zdm-premium-feature__plan';
                                                if (isset($feature['highlight']) && $feature['highlight'] === $plan_key) {
                                                    $plan_classes .= ' zdm-premium-feature__plan--highlight';
                                                }
                                                ?>
                                                <div class="<?= esc_attr($plan_classes) ?>">
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
                        </section>
                    </div>

                    <div class="zdm-premium-box__footer">
                        <div class="zdm-premium-box__footer-text">
                            <h4><?= esc_html__('Ready to elevate your download experience?', 'zdm') ?></h4>
                            <p><?= esc_html__('Join Premium and secure faster workflows, more capacity and trusted verification tools for your audience.', 'zdm') ?></p>
                        </div>
                        <div class="zdm-premium-box__footer-cta">
                            <a href="<?= ZDM__PRO_URL ?>" target="_blank" class="button button-primary button-hero"><?= esc_html__('Upgrade now', 'zdm') ?></a>
                            <a href="admin.php?page=<?= ZDM__SLUG ?>-premium" class="button zdm-button-ghost"><?= esc_html__('Compare plans', 'zdm') ?></a>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="postbox-container zdm-premium-postbox-col-sm">
            <div class="postbox zdm-premium-feedback-box">
                <div class="inside">
                    <div class="zdm-premium-feedback">
                        <section class="zdm-premium-feedback__item">
                            <div class="zdm-premium-feedback__icon">
                                <span class="material-icons-outlined">reviews</span>
                            </div>
                            <div class="zdm-premium-feedback__content">
                                <h3><?= esc_html__('Write a review', 'zdm') ?></h3>
                                <p><?= esc_html__('If you like', 'zdm') ?> <?= ZDM__TITLE ?>, <?= esc_html__('then write a', 'zdm') ?> <a class="zdm-premium-feedback__link" href="https://wordpress.org/support/plugin/z-downloads/reviews/?filter=5#postform" target="_blank" title="<?= ZDM__TITLE ?> <?= esc_html__('rating', 'zdm') ?>">★★★★★ <?= esc_html__('rating', 'zdm') ?> <span class="material-icons-round zdm-md-1">open_in_new</span></a>. <?= esc_html__('You would help me a lot to make the plugin known.', 'zdm') ?></p>
                                <a href="https://wordpress.org/support/plugin/z-downloads/reviews/?filter=5#postform" target="_blank" class="button button-secondary zdm-premium-feedback__cta"><?= esc_html__('Leave a review', 'zdm') ?></a>
                            </div>
                        </section>
                        <section class="zdm-premium-feedback__item">
                            <div class="zdm-premium-feedback__icon zdm-premium-feedback__icon--accent">
                                <span class="material-icons-outlined">lightbulb</span>
                            </div>
                            <div class="zdm-premium-feedback__content">
                                <h3><?= esc_html__('Suggestions for improvement', 'zdm') ?></h3>
                                <p><?= esc_html__('Do you have suggestions for improvement or suggestions for the plugin, then write me', 'zdm') ?>:</p>
                                <a class="zdm-premium-feedback__cta" href="mailto:info@code.urban-base.net?subject=<?= ZDM__TITLE ?> <?= esc_html__('suggestions for improvement', 'zdm') ?>" target="_blank">info@code.urban-base.net</a>
                            </div>
                        </section>
                    </div>
                </div>
            </div>
        </div>

    </div>
<?php
}