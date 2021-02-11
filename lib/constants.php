<?php

// Abort by direct access
if (!defined('ABSPATH')) {
    die;
}

$zdm_options = get_option('zdm_options');

define('ZDM__DOWNLOADS_PATH', wp_upload_dir()['basedir'] . "/z-downloads-" . $zdm_options['download-folder-token']);
define('ZDM__DOWNLOADS_CACHE_PATH', ZDM__DOWNLOADS_PATH . "/cache");
define('ZDM__DOWNLOADS_FILES_PATH', ZDM__DOWNLOADS_PATH . "/files");
define('ZDM__DOWNLOADS_PATH_URL', wp_upload_dir()['baseurl'] . "/z-downloads-" . $zdm_options['download-folder-token']);
define('ZDM__DOWNLOADS_CACHE_PATH_URL', ZDM__DOWNLOADS_PATH_URL . "/cache");
define('ZDM__DOWNLOADS_FILES_PATH_URL', ZDM__DOWNLOADS_PATH_URL . "/files");

// options
define('ZDM__OPTIONS', [
    'version'                           => ZDM__VERSION,
    'licence-key'                       => '',
    'licence-email'                     => '',
    'licence-purchase'                  => '',
    'licence-product-name'              => '',
    'licence-time'                      => 0,
    'db-version'                        => 1,
    'download-btn-text'                 => 'Download',
    'download-btn-style'                => 'black',
    'download-btn-outline'              => '',
    'download-btn-border-radius'        => 'none',
    'download-btn-icon'                 => 'none',
    'download-btn-icon-only'            => '',
    'download-btn-css'                  => '',
    'list-style'                        => 'rows',
    'list-bold'                         => '',
    'list-links'                        => '',
    'secure-ip'                         => 'on',
    'duplicate-file'                    => '',
    'file-open-in-browser-pdf'          => '',
    'stat-single-file-last-limit'       => 5,
    'stat-single-archive-last-limit'    => 5,
    'hide-html-id'                      => 'on'
]);

// MIME types
define('ZDM__MIME_TYPES_AUDIO', [
    'audio/aac',
    'audio/aacp',
    'audio/flac',
    'audio/mp3',
    'audio/mp4',
    'audio/mpeg',
    'audio/ogg',
    'audio/wav',
    'audio/wave',
    'audio/webm',
    'audio/x-pn-wav',
    'audio/x-wav'
]);
define('ZDM__MIME_TYPES_VIDEO', [
    'video/mp4',
    'video/ogg',
    'video/webm'
]);
define('ZDM__MIME_TYPES_IMAGE', [
    'image/bmp',
    'image/x-bmp',
    'image/x-ms-bmp',
    'image/gif',
    'image/jpeg',
    'image/png',
    'image/tiff'
]);

// Download button style
define('ZDM__DOWNLOAD_BTN_STYLE_VAL', [
    'black',
    'grey5',
    'grey7',
    'grey9',
    'grey11',
    'grey13',
    'purple',
    'blue',
    'green',
    'yellow',
    'orange',
    'red'
]);
define('ZDM__DOWNLOAD_BTN_STYLE', [
    __('Black', 'zdm'),
    __('Grey 5', 'zdm'),
    __('Grey 7', 'zdm'),
    __('Grey 9', 'zdm'),
    __('Grey 11', 'zdm'),
    __('Grey 13', 'zdm'),
    __('Purple', 'zdm'),
    __('Blue', 'zdm'),
    __('Green', 'zdm'),
    __('Yellow', 'zdm'),
    __('Orange', 'zdm'),
    __('Red', 'zdm')
]);

// Download button rounded edges
define('ZDM__DOWNLOAD_BTN_BORDER_RADIUS_VAL', [
    'none',
    '1',
    '2',
    '3',
    '4',
    '5',
    '6',
    '7',
    '8',
    '9',
    '10'
]);
define('ZDM__DOWNLOAD_BTN_BORDER_RADIUS', [
    __('none', 'zdm'),
    __('1 pixel', 'zdm'),
    __('2 pixel', 'zdm'),
    __('3 pixel', 'zdm'),
    __('4 pixel', 'zdm'),
    __('5 pixel', 'zdm'),
    __('6 pixel', 'zdm'),
    __('7 pixel', 'zdm'),
    __('8 pixel', 'zdm'),
    __('9 pixel', 'zdm'),
    __('10 pixel', 'zdm')
]);

// Download button icons
define('ZDM__DOWNLOAD_BTN_ICON_VAL', [
    'none',
    'download',
    'arrow-round-down',
    'code-download',
    'cloud-download',
    'cloud',
    'cloud-done',
    'checkmark',
    'checkmark-circle',
    'checkmark-circle-outline',
    'heart',
    'heart-empty',
    'star',
    'star-outline',
    'trophy'
]);
define('ZDM__DOWNLOAD_BTN_ICON', [
    __('none', 'zdm'),
    __('Arrow 1', 'zdm'),
    __('Arrow 2', 'zdm'),
    __('Download code', 'zdm'),
    __('Cloud download', 'zdm'),
    __('Cloud', 'zdm'),
    __('Cloud check', 'zdm'),
    __('Check 1', 'zdm'),
    __('Check 2', 'zdm'),
    __('Check 3', 'zdm'),
    __('Heart 1', 'zdm'),
    __('Heart 2', 'zdm'),
    __('Star 1', 'zdm'),
    __('Star 2', 'zdm'),
    __('Trophy', 'zdm')
]);

// Countries using decimal point
define('ZDM__COUNTRIES_USING_DECIMAL_POINT', [
    'en_AU',
    'bn_BD',
    'en_BW',
    'km_KH',
    'en_CA',
    'en_HK',
    'zh_Hans_MO',
    'zh_Hant_MO',
    'el_CY',
    'es_DO',
    'ar_EG',
    'es_SV',
    'ti_ET',
    'ak_GH',
    'es_GT',
    'es_HN',
    'en_IN',
    'en_IE',
    'en_IL',
    'en_JM',
    'ja_JP',
    'ar_JO',
    'ebu_KE',
    'ko_KR',
    'ko',
    'ar_LY',
    'de_LI',
    'de_LU',
    'ms_MY',
    'mt_MT',
    'en_MT',
    'es_MX',
    'my_MM',
    'en_NZ',
    'es_NI',
    'ig_NG',
    'ha_Latn_NG',
    'en_PK',
    'pa_Arab_PK',
    'ur_PK',
    'es_PA',
    'en_PH',
    'fil_PH',
    'es_PR',
    'ar_QA',
    'ar_SA',
    'en_SG',
    'zh_Hans_SG',
    'so_SO',
    'si_LK',
    'ta_LK',
    'fr_CH',
    'it_CH',
    'zh_Hant_TW',
    'th_TH',
    'en_GB',
    'en_US',
    'es_US',
    'haw_US',
    'chr_US',
    'en_ZW'
]);