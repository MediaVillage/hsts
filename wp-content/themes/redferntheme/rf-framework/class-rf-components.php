<?php
/**
 * Load custom components into theme
 * 
 * @author  Red Fern
 * @version  1.0.0
 */
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

class RF_Components {

	/**
	 * Initialise all shortcodes
	 */
	public static function init() {
		$default_active_components = array();

		// Check the theme/child theme for active components
		$active_components = apply_filters( 'rf_active_components', $default_active_components );

		if ( ! empty($active_components) ) {
			foreach($active_components as $component) {
				// Convert the name to a class name
				$class_name = self::getClassName($component);
				// Initialize the class
				$class_name::init();	
			}
		}
	}

	/**
	 * Get the name of the class for the component
	 * @param  string $component
	 * @return string
	 */
	public static function getClassName($component)
	{
		$name = preg_replace('/[-_]/',' ',$component);
		$name = str_replace(' ','_',trim(ucwords($name)));
		return "RF_Component_{$name}";
	}
}

RF_Components::init();