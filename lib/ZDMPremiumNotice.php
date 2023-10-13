<?php

// Abort by direct access
if (!defined('ABSPATH'))
    die;

if (is_admin() && !ZDMCore::licence()) {

    // AJAX-Handler registrieren
    add_action('admin_footer', 'action_javascript');

    function action_javascript()
    {
?>
        <script type="text/javascript">
            jQuery(document).ready(function($) {

                $('.zdm-premium-notice, .zdm-second-notice').on('click', '.notice-dismiss', function(event) {
                    var data = {
                        'action': 'zdm_premium_dismiss_notice'
                    };

                    $.post(ajaxurl, data, function(response) {
                        console.log('Got this from the server: ' + response);
                    });
                });

            });
        </script>
<?php
    }


    // Wenn AJAX den Post durchführt, werden wir diesen Code ausführen.
    add_action('wp_ajax_zdm_premium_dismiss_notice', 'zdm_premium_dismiss_notice');

    function zdm_premium_dismiss_notice()
    {
        $options = get_option('zdm_options');
        $options['premium-notice-time'] = time();
        update_option('zdm_options', $options);

        wp_die(); // Dies ist erforderlich, um sofort zu beenden und eine korrekte Antwort zurückzugeben.
    }

    add_action('admin_notices', 'zdm_premium_admin_notice');

    function zdm_premium_admin_notice()
    {
        $options = get_option('zdm_options');
        $dismiss_time = $options['premium-notice-time'];
        $premium_time = 3 * 30 * 24 * 60 * 60; // 3 Monate in Sekunden

        // Logik für die zweite Notice
        $current_time = time();
        $start_date = strtotime("15-10-2023");
        $end_date = strtotime("15-11-2023");
        $show_second_notice = ($current_time >= $start_date && $current_time <= $end_date);

        if (!$show_second_notice && ($dismiss_time && (time() - $dismiss_time < $premium_time))) {
            return;
        }

        if ($show_second_notice) {
            echo '<div class="notice notice-info is-dismissible zdm-second-notice">';
            echo '<p>Informationen zur zweiten Notice...</p>'; // Hier können Sie den gewünschten Text für die zweite "Notice" einfügen
            echo '</div>';
        } else {
            // Bestehende Notice
            echo '<div class="notice notice-info is-dismissible zdm-premium-notice">';
            echo '<p>' . $options['licence-key'] . esc_html__('Are you excited about our Download Manager and want to support its further development? By upgrading to', 'zdm') . ' <b>' . ZDM__TITLE . ' ' . ZDM__PRO . '</b> ' . esc_html__(', not only can you contribute to this, but you will also unlock advanced features.', 'zdm') . ' <a href="admin.php?page=' . ZDM__SLUG . '-premium">' . esc_html__('Click here and discover more.', 'zdm') . '</a></p>';
            echo '</div>';
        }
    }
}
