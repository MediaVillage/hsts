<?php
/**
 * Defines the available custom widgets
 * 
 * @author  Red Fern
 * @version 1.0.0
 */
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

class RF_Widgets {

	/**
	 * Set default active widgets
	 * @var array
	 */
	public static $default_active_widgets = array(
		'social_icons', 'twitter_feed', 'carousel'
	);

	/**
	 * Define available widgets
	 */
	public static function init()
	{
		add_action( 'widgets_init', array( __CLASS__, 'register_widgets' ) );
		add_action( 'admin_enqueue_scripts', array( __CLASS__, 'enqueue_widget_admin_scripts' ) );
	}	

	/**
	 * Register widgets
	 */
	public static function register_widgets()
	{
		// Allow third party plugins, child theme to set active widgets
		$active_widgets = apply_filters( 'rf_active_widgets', self::$default_active_widgets );

		foreach($active_widgets as $widget) {
			// Get class name 
			$class_name = self::getClassName($widget);
			// Register the widget
			register_widget( $class_name );
		}
	}

    /**
     * Enqueue specific scripts for the admin widgets page
     *
     * @param $hook
     */
	public static function enqueue_widget_admin_scripts($hook)
    {
        if ( $hook != 'widgets.php' ) return;

        wp_enqueue_script( 'rf-admin-widgets', RFFramework()->url() . '/assets/js/widgets.js', array( 'jquery', 'jquery-ui-tabs' ), false, true );
    }

	/**
	 * Get the name of the class for the widget
	 * @param  string $widget
	 * @return string
	 */
	public static function getClassName($widget)
	{
		$name = preg_replace('/[-_]/',' ',$widget);
		$name = str_replace(' ','_',trim(ucwords($name)));
		return "RF_Widget_{$name}";
	}
}

RF_Widgets::init();