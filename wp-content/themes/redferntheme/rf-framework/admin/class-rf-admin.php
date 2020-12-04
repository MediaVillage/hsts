<?php
/**
 * Define Framework admin functionality
 *
 * @author Red Fern
 * @version 1.0.0
 */
defined( 'ABSPATH' ) or die;

class RF_Admin {

    /**
     * Constructor
     */
    public function __construct()
    {
        $this->includes();

        add_action( 'admin_enqueue_scripts', array( $this, 'enqueue_scripts' ) );
    }

    /**
     * Include admin classes
     */
    public function includes()
    {
        include_once( 'class-rf-admin-menu.php' );
        include_once( 'class-rf-admin-menu-settings.php' );
    }

    /**
     * Enqueue the main rf admin scripts
     */
    public function enqueue_scripts()
    {
        wp_enqueue_style( 'rf-admin', RFFramework()->url() . '/assets/css/admin.css' );
    }
}

new RF_Admin();