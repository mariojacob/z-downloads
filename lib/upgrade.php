<?php
// Abort by direct access
if (!defined('ABSPATH'))
    die;

$zdm_options = get_option('zdm_options');

if ($zdm_options['version'] < ZDM__VERSION) {

    // NOTE: since v1.9.0
    global $wpdb;
    $table_name = $wpdb->prefix . 'zdm_log';
    $drop_ddl = "ALTER TABLE " . $table_name . " DROP `user_id`";
    require_once(ABSPATH . 'wp-admin/install-helper.php');
    maybe_drop_column($table_name, "user_id", $drop_ddl);
    flush_rewrite_rules();
    wp_cache_flush();
    // since v1.9.0 end

    ZDMCore::log('plugin upgrade', $zdm_options['version'] . ' to ' . ZDM__VERSION);

    $zdm_options['version'] = ZDM__VERSION;

    update_option('zdm_options', $zdm_options);
}
