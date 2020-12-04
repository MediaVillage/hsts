<?php
/**
 * Autoload the RF Framework classes
 *
 * @author Red Fern
 * @version 1.0.0
 */
if ( ! defined( 'ABSPATH' ) ) exit;

class RF_Autoloader {

	/**
	 * Constructor
	 */
	public function __construct()
	{
		if ( function_exists( "__autoload" ) ) {
			spl_autoload_register( "__autoload" );
		}
		spl_autoload_register( array( $this, 'autoload' ) );
	}

	/**
	 * Take a class name and turn it into a file name
	 * @param  string $class
	 * @return string
	 */
	private function get_file_name_from_class( $class ) {
		return 'class-' . str_replace( '_', '-', strtolower( $class ) ) . '.php';
	}

	/**
	 * Include a class file
	 * @param  string $path
	 * @return bool successful or not
	 */
	private function load_file( $path ) {
		if ( $path && is_readable( $path ) ) {
			include_once( $path );
			return true;
		}
		return false;
	}

	/**
	 * Autoload a given class
	 *
	 * @param $class
	 */
	public function autoload($class)
	{
		$class = strtolower( $class );
		$file  = $this->get_file_name_from_class( $class );
		$path  = '';

        if ( strpos( $class, 'rf_abstract_') === 0 ) {
         	$path = RFFramework()->path() . '/abstracts/';
        } elseif ( strpos( $class, 'rf_shortcode_' ) === 0 ) {
			$path = RFFramework()->path() . '/shortcodes/';
		} elseif ( strpos( $class, 'rf_customizer_' ) === 0 ) {
			$path = RFFramework()->path() . '/customizer/';
		} elseif ( strpos( $class, 'rf_control_' ) === 0 ) {
			$path = RFFramework()->path() . '/customizer/controls/';
		} elseif ( strpos( $class, 'rf_walker_' ) === 0 ) {
            $path = RFFramework()->path() . '/menus/';
        } elseif ( strpos( $class, 'rf_service_' ) === 0 ) {
            $path = RFFramework()->path() . '/services/';
        } elseif ( strpos( $class, 'rf_component_' ) === 0 ) {
        	$path = RFFramework()->path() . '/components/';
        } elseif ( strpos( $class, 'rf_widget_' ) === 0 ) {
        	$path = RFFramework()->path() . '/widgets/';
        } elseif ( strpos( $class, 'rf_element_' ) === 0 ) {
            $path = RFFramework()->path() . '/elements/';
        }

		if ( empty( $path ) || ( ! $this->load_file( $path . $file ) && strpos( $class, 'rf_' ) === 0 ) ) {
			$this->load_file( RFFramework()->path() . $file );
		}
	}
}

new RF_Autoloader();