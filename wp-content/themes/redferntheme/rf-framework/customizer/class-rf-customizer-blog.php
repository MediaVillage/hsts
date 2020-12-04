<?php
/**
 * Customizer: Main options for the blog
 * 
 * @author  Red Fern
 * @version 1.0.0
 */
if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly
}

class RF_Customizer_Blog {

	/**
	 * Register customizer options
	 * 
	 * @param  $wp_customize
	 */
	public static function register( $wp_customize ) {
		$wp_customize->add_section( 'blog', array(
			'title' => __( 'Blog', 'rf-framework' ),
		  	'description' => __( 'Add options for the blog pages' ),
		  	'panel' => '', // Not typically needed.
		  	'priority' => 10,
		  	'capability' => 'edit_theme_options',
		  	'theme_supports' => '', // Rarely needed.
            'active_callback' => 'is_home',
		) );

		// Blog Layout
		$wp_customize->add_setting( 'blog_sidebar', array(
			'default' => 'right-sidebar'
		) );
		$wp_customize->add_control(
            new RF_Control_Radio_Image(
                $wp_customize,
                'blog_sidebar',
                array(
                    'label'    => esc_html__( 'Sidebar Layout', 'rf-framework' ),
                    'section'  => 'blog',
                    'choices'  => array(
                        'right-sidebar' => array(
                            'label' => esc_html__( 'Right Sidebar', 'rf-framework' ),
                            'url'   => '%s/images/right-sidebar.png'
                        ),
                        'left-sidebar' => array(
                            'label' => esc_html__( 'Left Sidebar', 'rf-framework' ),
                            'url'   => '%s/images/left-sidebar.png'
                        ),
                        'no-sidebar' => array(
                            'label' => esc_html__( 'No Sidebar', 'rf-framework' ),
                            'url'   => '%s/images/no-sidebar.png'
                        )
                    )
                )
            )
        );
	}
}