<?php
// Abort by direct access
if (!defined('ABSPATH'))
    die;

$zdm_options = get_option('zdm_options');

if (version_compare($zdm_options['version'], ZDM__VERSION, '<')) {

    if (ZDM__VERSION >= '1.11.4') {
        if (!$zdm_options['secure-file-upload']) {
            $zdm_options['secure-file-upload'] = 'on';
        }
        if (!$zdm_options['max-upload-size']) {
            $zdm_options['max-upload-size-in-mb'] = 50;
        }
    }

    ZDMCore::log('plugin upgrade', $zdm_options['version'] . ' to ' . ZDM__VERSION);

    $zdm_options['version'] = ZDM__VERSION;

    update_option('zdm_options', $zdm_options);
}
