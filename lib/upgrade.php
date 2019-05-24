<?php

// Abbruch bei direktem Zugriff
if( !defined( 'ABSPATH' ) ) {
    die;
}

$options = get_option('zdm_options');

if ($options['download-btn-text'] == '') {
    $options['download-btn-text'] = 'Download';
    update_option('zdm_options', $options);
}

if ($options['version'] < ZDM__VERSION) {

    $options['version'] = ZDM__VERSION;

    // Neue Optionen in v0.2.0
    
    if (!$options['download-btn-style']) {
        $options['download-btn-style'] = 'black';
    }

    if (!$options['download-btn-border-radius']) {
        $options['download-btn-border-radius'] = 'none';
    }

    update_option('zdm_options', $options);
}