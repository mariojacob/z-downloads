<?php
// Abort by direct access
if (!defined('ABSPATH'))
    die;

/**
 * Plugin Deaktivierung
 */
class ZDMPluginDeactivate {

    /**
     * Plugin deaktivieren
     *
     * @return void
     */
    public static function deactivate() {
        ZDMCore::log('plugin deactivated');
    }
}
