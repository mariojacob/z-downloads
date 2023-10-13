<?php
// TODO: AUTUMN SALE
// Abort by direct access
if (!defined('ABSPATH'))
    die;

if (is_admin() && !ZDMCore::licence() && ZDM__SHOW_SALE) {

    add_action('admin_footer', 'fallsale_notice_javascript');

    function fallsale_notice_javascript()
    {
?>
        <script type="text/javascript">
            jQuery(document).ready(function($) {

                $('.zdm-fallsale-notice').on('click', '.notice-dismiss', function(event) {
                    var data = {
                        'action': 'zdm_fallsale_dismiss_notice'
                    };

                    $.post(ajaxurl, data, function(response) {
                        console.log('Got this from the server: ' + response);
                    });
                });

            });
        </script>
<?php
    }

    add_action('wp_ajax_zdm_fallsale_dismiss_notice', 'zdm_fallsale_dismiss_notice');

    function zdm_fallsale_dismiss_notice()
    {
        $options = get_option('zdm_options');
        $options['fallsale-notice-time'] = time();
        update_option('zdm_options', $options);

        wp_die();
    }

    add_action('admin_notices', 'zdm_fallsale_admin_notice');

    function zdm_fallsale_admin_notice()
    {
        $options = get_option('zdm_options');
        $dismiss_time = isset($options['fallsale-notice-time']) ? $options['fallsale-notice-time'] : 0;

        $current_time = time();
        $start_date = strtotime("10-10-2023");
        $end_date = strtotime("15-11-2023");
        $show_fallsale_notice = ($current_time >= $start_date && $current_time <= $end_date && ($current_time - $dismiss_time > 30 * 24 * 60 * 60 || !$dismiss_time));

        if ($show_fallsale_notice) {
            echo '<div class="notice notice-info is-dismissible zdm-fallsale-notice">';
            echo '<h3>🍁 ' . esc_html__('FALL SALE -40%', 'zdm') . ' 🍁</h3><p>' . esc_html__('Until November 15, 2023 for Z-Downloads Premium. Grab the deal before it\'s too late!', 'zdm') . ' <b><a href="https://urbanbase.gumroad.com/l/zdPRE" target="_blank">' . esc_html__('Get yours now!', 'zdm') . '</a></b></p>';
            echo '</div>';
        }
    }
}
