<?php
// Abort by direct access
if (!defined('ABSPATH')) {
    exit;
}

if (!current_user_can(ZDM__STANDARD_USER_ROLE)) {
    wp_die(esc_html__('You do not have sufficient permissions to view logs.', 'zdm'));
}

global $wpdb;

$zdm_tablename_log = $wpdb->prefix . 'zdm_log';
$log_type_config = ZDMCore::get_log_type_config();
$default_per_page = 50;
$per_page_options = array(25, 50, 100, 200);
$log_nonce = wp_create_nonce('zdm-logs-request');
$ajax_url = admin_url('admin-ajax.php');
$initial_open_id = isset($_GET['id']) ? absint($_GET['id']) : 0;
$detail_id = $initial_open_id;

if ($detail_id > 0) {
    $zdm_db_log_details_query = $wpdb->prepare(
        "SELECT id, type, message, user_agent, user_ip, time_create FROM $zdm_tablename_log WHERE id = %d",
        $detail_id
    );
    $zdm_db_log_details = $wpdb->get_row($zdm_db_log_details_query);

    ?>
    <div class="wrap">
        <h1><?= esc_html__('Log details', 'zdm') ?></h1>
        <br>
        <a class="page-title-action" href="?page=<?= ZDM__SLUG ?>-log"><?= esc_html__('Back', 'zdm') ?></a>
        <br><br>

        <div class="postbox">
            <div class="inside">
                <?php if ($zdm_db_log_details) { ?>
                    <table class="form-table">
                        <tbody>
                            <tr valign="top">
                                <th scope="row"><?= esc_html__('Type', 'zdm') ?></th>
                                <td valign="middle"><?= esc_html($zdm_db_log_details->type) ?></td>
                            </tr>
                            <tr valign="top">
                                <th scope="row"><?= esc_html__('Details', 'zdm') ?></th>
                                <td valign="middle">
                                    <?= $zdm_db_log_details->message ?>
                                </td>
                            </tr>
                            <tr valign="top">
                                <th scope="row"><?= esc_html__('User agent', 'zdm') ?></th>
                                <td valign="middle"><?= esc_html($zdm_db_log_details->user_agent) ?></td>
                            </tr>
                            <tr valign="top">
                                <th scope="row"><?= esc_html__('IP address', 'zdm') ?></th>
                                <td valign="middle"><?= esc_html($zdm_db_log_details->user_ip) ?></td>
                            </tr>
                            <tr valign="top">
                                <th scope="row"><?= esc_html__('Created', 'zdm') ?></th>
                                <td valign="middle"><?= date_i18n('d.m.Y - H:i:s', (int) $zdm_db_log_details->time_create) ?></td>
                            </tr>
                        </tbody>
                    </table>
                <?php } else { ?>
                    <div class="notice notice-error">
                        <p><?= esc_html__('The requested log entry could not be found.', 'zdm') ?></p>
                    </div>
                <?php } ?>
            </div>
        </div>
    </div>
    <?php
    return;
}

$distinct_types = $wpdb->get_col("SELECT DISTINCT type FROM $zdm_tablename_log ORDER BY type ASC LIMIT 200");
if (!is_array($distinct_types)) {
    $distinct_types = array();
}

$log_type_options = array();

foreach ($distinct_types as $type_entry) {
    if (!is_string($type_entry) || $type_entry === '') {
        continue;
    }

    $label = isset($log_type_config[$type_entry]['label']) ? $log_type_config[$type_entry]['label'] : ucwords($type_entry);
    $log_type_options[] = array(
        'value' => $type_entry,
        'label' => $label,
    );
}

if (!empty($log_type_options)) {
    usort(
        $log_type_options,
        function ($a, $b) {
            return strcasecmp((string) $a['label'], (string) $b['label']);
        }
    );
}

$initial_state = array(
    'page'       => 1,
    'perPage'    => $default_per_page,
    'types'      => array(),
    'dateFrom'   => '',
    'dateTo'     => '',
    'search'     => '',
);

$type_config_json = wp_json_encode($log_type_config);
$initial_state_json = wp_json_encode($initial_state);
$detail_url_base = '?page=' . ZDM__SLUG . '-log&id=';

?>
<div class="wrap zdm-log-wrap"
     data-zdm-log-ajax="<?php echo esc_attr($ajax_url); ?>"
     data-zdm-log-nonce="<?php echo esc_attr($log_nonce); ?>"
     data-zdm-log-per-page="<?php echo esc_attr($default_per_page); ?>"
     data-zdm-log-initial="<?php echo esc_attr($initial_state_json); ?>"
     data-zdm-log-types="<?php echo esc_attr($type_config_json); ?>"
     data-zdm-log-detail-base="<?php echo esc_attr($detail_url_base); ?>">
    <h1 class="wp-heading-inline"><?php echo esc_html__('Logs', 'zdm'); ?></h1>
    <p class="zdm-log-subline"><?php echo esc_html__('Live filters, search and details without page reload.', 'zdm'); ?></p>

    <div class="zdm-log-toolbar">
        <div class="zdm-log-field zdm-log-field--search">
            <label for="zdm-log-search"><?php echo esc_html__('Search', 'zdm'); ?></label>
            <div class="zdm-log-field__control">
                <input type="search"
                       id="zdm-log-search"
                       placeholder="<?php echo esc_attr__('Search logs…', 'zdm'); ?>"
                       data-zdm-log-input="search"
                       autocomplete="off" />
            </div>
        </div>

        <div class="zdm-log-field zdm-log-field--types">
            <label for="zdm-log-types"><?php echo esc_html__('Log types', 'zdm'); ?></label>
            <select id="zdm-log-types" data-zdm-log-input="types" multiple="multiple" size="6">
                <?php if (!empty($log_type_options)) : ?>
                    <?php foreach ($log_type_options as $option) : ?>
                        <option value="<?php echo esc_attr($option['value']); ?>"><?php echo esc_html($option['label']); ?></option>
                    <?php endforeach; ?>
                <?php endif; ?>
            </select>
            <p class="description"><?php echo esc_html__('Hold CTRL or CMD to select multiple entries.', 'zdm'); ?></p>
        </div>

        <div class="zdm-log-field zdm-log-field--dates">
            <label><?php echo esc_html__('Date range', 'zdm'); ?></label>
            <div class="zdm-log-field__date-range">
                <input type="date" data-zdm-log-input="date_from" aria-label="<?php echo esc_attr__('Start date', 'zdm'); ?>" />
                <span class="zdm-log-field__separator">&ndash;</span>
                <input type="date" data-zdm-log-input="date_to" aria-label="<?php echo esc_attr__('End date', 'zdm'); ?>" />
            </div>
        </div>

        <div class="zdm-log-field zdm-log-field--per-page">
            <label for="zdm-log-per-page"><?php echo esc_html__('Entries per page', 'zdm'); ?></label>
            <select id="zdm-log-per-page" data-zdm-log-input="per_page">
                <?php foreach ($per_page_options as $option) : ?>
                    <option value="<?php echo esc_attr($option); ?>" <?php selected($option, $default_per_page); ?>><?php echo esc_html($option); ?></option>
                <?php endforeach; ?>
            </select>
        </div>

        <div class="zdm-log-field zdm-log-field--actions">
            <label class="screen-reader-text" for="zdm-log-reset"><?php echo esc_html__('Reset filters', 'zdm'); ?></label>
            <button type="button" class="button button-secondary" id="zdm-log-reset" data-zdm-log-reset>
                <?php echo esc_html__('Reset filters', 'zdm'); ?>
            </button>
        </div>
    </div>

    <div class="zdm-log-status" role="status" aria-live="polite">
        <span data-zdm-log-summary><?php echo esc_html__('Loading logs…', 'zdm'); ?></span>
    </div>

    <div class="zdm-log-table-wrapper">
        <table class="wp-list-table widefat fixed striped zdm-log-table">
            <thead>
                <tr>
                    <th scope="col" class="column-type"><?php echo esc_html__('Type', 'zdm'); ?></th>
                    <th scope="col" class="column-message"><?php echo esc_html__('Details', 'zdm'); ?></th>
                    <th scope="col" class="column-source"><?php echo esc_html__('Source', 'zdm'); ?></th>
                    <th scope="col" class="column-created"><?php echo esc_html__('Created', 'zdm'); ?></th>
                </tr>
            </thead>
            <tbody data-zdm-log-body>
                <tr class="zdm-log-row is-loading">
                    <td colspan="4">
                        <span class="spinner is-active"></span>
                        <?php echo esc_html__('Loading logs…', 'zdm'); ?>
                    </td>
                </tr>
            </tbody>
            <tfoot>
                <tr>
                    <th scope="col" class="column-type"><?php echo esc_html__('Type', 'zdm'); ?></th>
                    <th scope="col" class="column-message"><?php echo esc_html__('Details', 'zdm'); ?></th>
                    <th scope="col" class="column-source"><?php echo esc_html__('Source', 'zdm'); ?></th>
                    <th scope="col" class="column-created"><?php echo esc_html__('Created', 'zdm'); ?></th>
                </tr>
            </tfoot>
        </table>
    </div>

    <div class="notice notice-info zdm-log-empty" data-zdm-log-empty hidden>
        <p><?php echo esc_html__('No log entries match the current filters.', 'zdm'); ?></p>
    </div>

    <nav class="zdm-log-pagination" aria-label="<?php echo esc_attr__('Log pagination', 'zdm'); ?>" data-zdm-log-pagination></nav>

    <noscript>
        <div class="notice notice-warning">
            <p><?php echo esc_html__('The modern log view requires JavaScript.', 'zdm'); ?></p>
            <p><?php echo esc_html__('Please enable JavaScript or switch to the classic view.', 'zdm'); ?></p>
        </div>
    </noscript>

    <template id="zdm-log-row-template">
        <tr class="zdm-log-row">
            <td class="column-type">
                <span class="material-icons-round zdm-log-row__icon" aria-hidden="true"></span>
                <a class="button-link zdm-log-row__type" href="#"></a>
            </td>
            <td class="column-message">
                <div class="zdm-log-row__message"></div>
                <a class="button-link zdm-log-row__details" href="#"><?php echo esc_html__('Show details', 'zdm'); ?></a>
            </td>
            <td class="column-source">
                <span class="zdm-log-row__ip"></span>
                <span class="zdm-log-row__agent"></span>
            </td>
            <td class="column-created">
                <span class="zdm-log-row__created"></span>
            </td>
        </tr>
    </template>
</div>

