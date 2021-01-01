<?php

// Abort by direct access
if (!defined('ABSPATH')) {
    die;
}

/**
 * Deactivate plugin
 */
class ZDMPluginDeactivate {

    /**
     * Method when plugin is deactivated
     *
     * @return void
     */
    public static function deactivate() {
        // Log
        ZDMCore::log('plugin deactivate');
    }
}
