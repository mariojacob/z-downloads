<?php
// Abort by direct access
if (!defined('ABSPATH'))
    die;

// Optionen
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
    'download-btn-icon-position'        => 'left',
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
    'hide-html-id'                      => 'on',
    'download-folder-token'             => '',
    'premium-notice-time'               => 0,
    'secure-file-upload'                => 'on',
    'max-upload-size-in-mb'             => 50
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

// Download Button Style
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

// Download Button runde Ecken
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

// Download Button Icons
define('ZDM__DOWNLOAD_BTN_ICON_VAL', [
    'none',
    'file_download',
    'save_alt',
    'arrow_downward',
    'keyboard_arrow_down',
    'arrow_drop_down_circle',
    'download_for_offline',
    'cloud_download',
    'cloud',
    'cloud_queue',
    'cloud_done',
    'check',
    'check_circle',
    'task_alt',
    'check_circle_outline',
    'verified',
    'verified_user',
    'done_outline',
    'done_all',
    'domain_verification',
    'event_available',
    'check_box',
    'task',
    'favorite',
    'favorite_border',
    'star',
    'star_outline',
    'thumb_up',
    'trending_up',
    'emoji_events',
    'fingerprint',
    'event',
    'picture_as_pdf',
    'insert_drive_file',
    'code',
    'apps'
]);
define('ZDM__DOWNLOAD_BTN_ICON', [
    __('none', 'zdm'),
    __('Arrow 1', 'zdm'),
    __('Arrow 2', 'zdm'),
    __('Arrow 3', 'zdm'),
    __('Arrow 4', 'zdm'),
    __('Arrow 5', 'zdm'),
    __('Arrow 6', 'zdm'),
    __('Cloud download', 'zdm'),
    __('Cloud', 'zdm'),
    __('Cloud outlined', 'zdm'),
    __('Cloud check', 'zdm'),
    __('Check 1', 'zdm'),
    __('Check 2', 'zdm'),
    __('Check 3', 'zdm'),
    __('Check 4', 'zdm'),
    __('Check 5', 'zdm'),
    __('Check 6', 'zdm'),
    __('Check 7', 'zdm'),
    __('Check 8', 'zdm'),
    __('Check 9', 'zdm'),
    __('Check 10', 'zdm'),
    __('Check 11', 'zdm'),
    __('Check 12', 'zdm'),
    __('Heart', 'zdm'),
    __('Heart outlined', 'zdm'),
    __('Star', 'zdm'),
    __('Star outlined', 'zdm'),
    __('Thumb up', 'zdm'),
    __('Trending up', 'zdm'),
    __('Trophy', 'zdm'),
    __('Fingerprint', 'zdm'),
    __('Event', 'zdm'),
    __('PDF', 'zdm'),
    __('File', 'zdm'),
    __('Code', 'zdm'),
    __('Apps', 'zdm')
]);

// Länder mit Dezimalpunkt
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

define('ZDM__ALLOWED_EXTENSIONS', [
    // Bilddateien
    'jpg', 'jpeg', 'png', 'gif', 'svg', 'tiff', 'bmp', 'ico', 'webp', 'djvu',
    // Dokumente
    'pdf', 'doc', 'docx', 'xls', 'xlsx', 'ppt', 'pptx', 'odt', 'ods', 'odp', 'rtf', 'epub', 'mobi', 'ibooks',
    'azw', 'fb2', 'pages', 'numbers', 'key',
    // Audio- und Video
    'mp3', 'wav', 'aac', 'ogg', 'oga', 'flac', 'mp4', 'mov', 'avi', 'wmv', 'mkv',
    // Text- und Code
    'txt', 'csv', 'json', 'xml', 'scpt',
    // Archivformate
    'zip', 'rar', '7z', 'tar', 'gz', 'cbz', 'cbr',
    // Grafik
    'psd', 'psb', 'ai', 'eps',
    // Sonstiges
    'unitypackage', 'dmg', 'plist', 'iso', 'img', 'bin', 'nrg', 'mdf'
]);

define('ZDM__ALLOWED_MIME_TYPES', [
    // Bilddateien
    'image/jpeg', 'image/png', 'image/gif', 'image/svg', 'image/svg+xml', 'image/tiff',
    'image/bmp', 'image/x-icon', 'image/webp', 'image/vnd.djvu',

    // Dokumente
    'application/vnd.openxmlformats-officedocument.wordprocessingml.template',
    'application/vnd.openxmlformats-officedocument.spreadsheetml.template',
    'application/vnd.ms-excel.sheet.macroEnabled.12', 'application/vnd.ms-excel.addin.macroEnabled.12',
    'application/pdf', 'application/msword', 'application/vnd.openxmlformats-officedocument.wordprocessingml.document',
    'application/vnd.ms-excel', 'application/vnd.openxmlformats-officedocument.spreadsheetml.sheet',
    'application/vnd.ms-powerpoint', 'application/vnd.openxmlformats-officedocument.presentationml.presentation',
    'application/vnd.ms-powerpoint.addin.macroEnabled.12', 'application/vnd.ms-powerpoint.presentation.macroEnabled.12',
    'application/vnd.ms-powerpoint.slideshow.macroEnabled.12', 'application/x-mspublisher', 'application/vnd.ms-access',
    'application/vnd.openxmlformats-officedocument.presentationml.template',
    'application/vnd.openxmlformats-officedocument.presentationml.slideshow',
    'application/vnd.oasis.opendocument.text', 'application/vnd.oasis.opendocument.spreadsheet',
    'application/vnd.oasis.opendocument.presentation', 'application/rtf',
    'application/vnd.visio und application/vnd.ms-visio.drawing', 'application/epub+zip', 'application/x-mobipocket-ebook',
    'application/vnd.amazon.ebook', 'application/x-fictionbook+xml',
    'application/vnd.apple.pages', 'application/vnd.apple.numbers', 'application/vnd.apple.keynote', 'application/x-ibooks+zip',

    // Audio- und Video
    'audio/mp3', 'audio/mpeg', 'audio/wav', 'audio/aac', 'audio/ogg', 'audio/flac',
    'video/mp4', 'video/quicktime', 'video/x-msvideo', 'video/x-ms-wmv', 'video/x-matroska',

    // Text- und Code
    'text/plain', 'text/csv', 'application/json', 'application/xml', 'application/octet-stream', 'text/markdown',
    'application/javascript', 'text/javascript', 'application/x-applescript',

    // Archivformate
    'application/zip', 'application/x-zip-compressed', 'application/x-rar-compressed', 'application/x-7z-compressed',
    'application/x-tar', 'application/gzip', 'application/x-ace-compressed', 'application/x-iso9660-image',
    'application/x-cbz', 'application/x-cbr',

    // Grafik
    'image/vnd.adobe.photoshop', 'application/postscript', 'application/postscript',
    'application/illustrator', 'application/vnd.adobe.illustrator',

    // Sonstiges
    'application/x-apple-diskimage', 'application/plist', 'application/x-iso9660-image'
]);
