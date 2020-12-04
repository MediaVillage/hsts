<?php
/**
 * Include all customizer options
 *
 * @author  Red Fern
 * @version  1.0.0
 */
if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly
}

class RF_Customizer {

	/**
	 * Initialize options
	 */
	public static function init() {
		$classes = array(
			'RF_Customizer_Header',
			'RF_Customizer_Blog'
		);

		add_action( 'customize_controls_enqueue_scripts', array( __CLASS__, 'control_scripts' ) );
		add_action( 'customize_register', array( __CLASS__, 'register_custom_controls' ) );
		foreach($classes as $class) {
			add_action( 'customize_register', array( $class, 'register' ) );
		}
	}

	/**
	 * Include customizer control scripts
	 */
	public static function control_scripts()
	{
        wp_enqueue_style( 'rf-customize-controls', RFFramework()->url() . '/assets/css/customize-controls.css' );

		wp_enqueue_script(
			'rf-customize-controls',
			RFFramework()->url() . '/assets/js/customize-controls.js',
			array('jquery', 'underscore'),
			false,
			true
		);
	}

	/**
	 * Register custom customizer controls
	 * @param  $wp_customize
	 */
	public static function register_custom_controls( $wp_customize )
	{
		// Register the radio image control class as a JS control type.
        $wp_customize->register_control_type( 'RF_Control_Radio_Image' );
	}
}

RF_Customizer::init();