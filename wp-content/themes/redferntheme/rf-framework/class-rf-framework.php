<?php
/**
 * Core Framework functionality
 */
if ( ! defined( 'ABSPATH' ) ) exit;

class RF_Framework {

	/**
	 * @var string
	 */
	public $version = '1.0.0';

	/**
	 * @var RF_Framework
	 */
	protected static $instance = null;

	/**
	 * Get single instance of class
	 * @return RF_Framework
	 */
	public static function getInstance()
    {
		if ( is_null(self::$instance) ) {
			self::$instance = new self();
		}
		return self::$instance;
	}

	/**
	 * Constructor
	 */
	public function __construct()
    {
		$this->includes();
        $this->constants();
	}

	/**
	 * Include core files
	 */
	public function includes()
    {
        include_once( 'rf-core-functions.php' );
        include_once( 'rf-template-functions.php' );
		include_once( 'class-rf-autoloader.php' );
		include_once( 'class-rf-shortcodes.php' );
		include_once( 'class-rf-components.php' );
		include_once( 'class-rf-customizer.php' );
		include_once( 'class-rf-widgets.php' );
        include_once( 'class-rf-media-fields.php' );
        include_once( 'visual-composer/class-rf-visual-composer.php' );

        if ( is_admin() ) {
            include_once( 'admin/class-rf-admin.php' );
        }
    }

    /**
     * Define constants
     */
    public function constants()
    {
        $this->define( 'RF_FRAMEWORK_DOMAIN', 'rf-framework' );
    }

	/**
	 * Define a constant if it does not exist
	 *
	 * @param $name
	 * @param $value
	 */
	private function define($name, $value)
	{
		if ( ! defined($name) )
			define($name, $value);
	}

	/**
	 * Path to the framework
	 *
	 * @return string
	 */
	public function path()
	{
		return apply_filters( 'rf_framework_path', untrailingslashit( get_template_directory() . '/rf-framework' ) );
	}

	/**
	 * @return string
	 */
	public function url()
	{
		return apply_filters( 'rf_framework_url', untrailingslashit( get_template_directory_uri() . '/rf-framework' ) );
	}

	/**
	 * @return mixed|void
	 */
	public function template_path()
	{
		return apply_filters( 'rf_framework_template_path', 'template-parts/' );
	}
}

/**
 * Return single instance of the framework
 */
function RFFramework() {
	return RF_Framework::getInstance();
}

// Make call to setup framework
RFFramework();