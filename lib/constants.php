<?php

// Abbruch bei direktem Zugriff
if( !defined( 'ABSPATH' ) ) {
    die;
}

define('ZDM__DOWNLOADS_PATH', wp_upload_dir()['basedir'] . "/z-downloads");
define('ZDM__DOWNLOADS_CACHE_PATH', ZDM__DOWNLOADS_PATH . "/cache");
define('ZDM__DOWNLOADS_FILES_PATH', ZDM__DOWNLOADS_PATH . "/files");
define('ZDM__DOWNLOADS_PATH_URL', wp_upload_dir()['baseurl'] . "/z-downloads");
define('ZDM__DOWNLOADS_CACHE_PATH_URL', ZDM__DOWNLOADS_PATH_URL . "/cache");
define('ZDM__DOWNLOADS_FILES_PATH_URL', ZDM__DOWNLOADS_PATH_URL . "/files");

// MIME Types
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

// Download-Button Style
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
    __('Schwarz', 'zdm'),
    __('Grau 5', 'zdm'),
    __('Grau 7', 'zdm'),
    __('Grau 9', 'zdm'),
    __('Grau 11', 'zdm'),
    __('Grau 13', 'zdm'),
    __('Lila', 'zdm'),
    __('Blau', 'zdm'),
    __('Grün', 'zdm'),
    __('Gelb', 'zdm'),
    __('Orange', 'zdm'),
    __('Rot', 'zdm')
]);

// Download-Button runde Ecken
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
    __('keine', 'zdm'),
    __('1 Pixel', 'zdm'),
    __('2 Pixel', 'zdm'),
    __('3 Pixel', 'zdm'),
    __('4 Pixel', 'zdm'),
    __('5 Pixel', 'zdm'),
    __('6 Pixel', 'zdm'),
    __('7 Pixel', 'zdm'),
    __('8 Pixel', 'zdm'),
    __('9 Pixel', 'zdm'),
    __('10 Pixel', 'zdm')
]);