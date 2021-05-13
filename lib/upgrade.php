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

    // NOTE: Temp since v1.8.0
    if ($zdm_options['download-btn-icon'] != 'none') {
        
        if ($zdm_options['download-btn-icon'] == 'download')
            $zdm_options['download-btn-icon'] = 'file_download';
        if ($zdm_options['download-btn-icon'] == 'arrow-round-down')
            $zdm_options['download-btn-icon'] = 'arrow_downward';
        if ($zdm_options['download-btn-icon'] == 'code-download')
            $zdm_options['download-btn-icon'] = 'code';
        if ($zdm_options['download-btn-icon'] == 'cloud-download')
            $zdm_options['download-btn-icon'] = 'cloud_download';
        if ($zdm_options['download-btn-icon'] == 'cloud-done')
            $zdm_options['download-btn-icon'] = 'cloud_done';
        if ($zdm_options['download-btn-icon'] == 'checkmark')
            $zdm_options['download-btn-icon'] = 'check';
        if ($zdm_options['download-btn-icon'] == 'checkmark-circle')
            $zdm_options['download-btn-icon'] = 'check_circle';
        if ($zdm_options['download-btn-icon'] == 'checkmark-circle-outline')
            $zdm_options['download-btn-icon'] = 'task_alt';
        if ($zdm_options['download-btn-icon'] == 'heart')
            $zdm_options['download-btn-icon'] = 'favorite';
        if ($zdm_options['download-btn-icon'] == 'heart-empty')
            $zdm_options['download-btn-icon'] = 'favorite_border';
        if ($zdm_options['download-btn-icon'] == 'star-outline')
            $zdm_options['download-btn-icon'] = 'star_outline';
        if ($zdm_options['download-btn-icon'] == 'trophy')
            $zdm_options['download-btn-icon'] = 'emoji_events';
    }

    if (!$zdm_options['download-btn-icon-only']) {
        $zdm_options['download-btn-icon-only'] = '';
    }

    if (!$zdm_options['list-style']) {
        $zdm_options['list-style'] = 'rows';
    }

    if (!$zdm_options['list-bold']) {
        $zdm_options['list-bold'] = '';
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