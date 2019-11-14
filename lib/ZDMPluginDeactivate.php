<?php

// Abbruch bei direktem Zugriff
if( !defined( 'ABSPATH' ) ) {
    die;
}

/**
 * Plugin deaktivierung
 */
class ZDMPluginDeactivate
{

    /**
     * Methode wenn Plugin deaktiviert wird
     *
     * @return void
     */
    public static function deactivate()
    {
        // Log
        ZDMCore::log('plugin deactivate');
    }
}
