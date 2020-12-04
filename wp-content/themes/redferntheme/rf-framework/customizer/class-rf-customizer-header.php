<?php
/**
 * Customizer: Header
 *
 * @author  Red Fern
 * @version  1.0.0
 */
if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly
}

class RF_Customizer_Header {

	/**
	 * Register customizer options
	 * 
	 * @param  $wp_customize
	 */
	public static function register( $wp_customize ) {
		$wp_customize->add_section( 'header', array(
			'title' => __( 'Header' ),
		  	'description' => __( 'Add custom CSS here' ),
		  	'panel' => '', // Not typically needed.
		  	'priority' => 10,
		  	'capability' => 'edit_theme_options',
		  	'theme_supports' => '', // Rarely needed.
		) );

		$wp_customize->add_setting( 'sticky_header' );
		$wp_customize->add_control( 'sticky_header', array(
		  	'type' => 'checkbox',
		  	'priority' => 10, // Within the section.
		  	'section' => 'header', // Required, core or custom.
		  	'label' => __( 'Sticky Header' ),
		  	'description' => __( 'Sticky Header - Yes/No' ),
		) );
	}
}