<?php
// Abort by direct access
if (!defined('ABSPATH'))
    die;

$zdm_options = get_option('zdm_options');

if (version_compare($zdm_options['version'], ZDM__VERSION, '<')) {

    ZDMCore::log('plugin upgrade', $zdm_options['version'] . ' to ' . ZDM__VERSION);

    $zdm_options['version'] = ZDM__VERSION;

    update_option('zdm_options', $zdm_options);
}
