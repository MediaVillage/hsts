<?php
/**
 * Include all the shortcodes
 *
 * @author  Red Fern
 * @version  1.0.0
 */
if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly
}

class RF_Shortcodes {

	/**
	 * Initialise all shortcodes
	 */
	public static function init() {
		$shortcodes = array(
		    'RF_Shortcode_Post_List',
            'RF_Shortcode_Carousel',
			'RF_Shortcode_Post_Grid',
            'RF_Shortcode_Ibox',
            'RF_Shortcode_Map',
            'RF_Shortcode_Term_List'
		);

		foreach( $shortcodes as $class) {
		    $class::init();
		}
	}
}

RF_Shortcodes::init();