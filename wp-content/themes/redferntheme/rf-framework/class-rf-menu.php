<?php
/**
 * Menu specific settings
 * 
 * @author Red Fern
 * @version  1.0.0
 */
if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly
}

class RF_Menu {

	/**
	 * Menu filters
	 * @return [type] [description]
	 */
	public static function init()
	{
		add_filter( 'nav_menu_css_class', array( __CLASS__, 'menu_item_classes' ), 10, 4 );
	}

	/**
	 * Add additional classes to menu
	 * 
	 * @param  array $classes
	 * @param  array $item
	 * @param  array $args
	 * @param  int $depth
	 * @return array
	 */
	public static function menu_item_classes( $classes, $item, $args, $depth )
	{
		print '<pre>';
		print_r($classes);
		print '</pre>';
		
		return $classes;
	}
}

RF_Menu::init();