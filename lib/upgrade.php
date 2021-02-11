<?php

// Abort by direct access
if (!defined('ABSPATH')) {
    die;
}

$zdm_options = get_option('zdm_options');

if ($zdm_options['version'] < ZDM__VERSION) {

    // Log
    ZDMCore::log('plugin upgrade', ZDM__VERSION);

    // Options
    if ($zdm_options['download-btn-text'] == '') {
        $zdm_options['download-btn-text'] = 'Download';
    }

    if (!$zdm_options['download-btn-style']) {
        $zdm_options['download-btn-style'] = 'black';
    }

    if (!$zdm_options['download-btn-border-radius']) {
        $zdm_options['download-btn-border-radius'] = 'none';
    }
    
    if (!$zdm_options['download-btn-outline']) {
        $zdm_options['download-btn-outline'] = '';
    }

    if (!$zdm_options['download-btn-icon']) {
        $zdm_options['download-btn-icon'] = 'none';
    }

    if (!$zdm_options['download-btn-icon-only']) {
        $zdm_options['download-btn-icon-only'] = '';
    }

    if (!$zdm_options['list-style']) {
        $zdm_options['list-style'] = 'rows';
    }

    if (!$zdm_options['list-links']) {
        $zdm_options['list-links'] = '';
    }

    if ($zdm_options['file-open-in-browser-pdf'] == '') {
        $zdm_options['file-open-in-browser-pdf'] = '';
    }

    if ($zdm_options['stat-single-file-last-limit'] == '') {
        $zdm_options['stat-single-file-last-limit'] = 5;
    }

    if ($zdm_options['stat-single-archive-last-limit'] == '') {
        $zdm_options['stat-single-archive-last-limit'] = 5;
    }

    if (!$zdm_options['hide-html-id']) {
        $zdm_options['hide-html-id'] = 'on';
    }

    $zdm_options['version'] = ZDM__VERSION;
    
    update_option('zdm_options', $zdm_options);

    //////////////////////////////
}