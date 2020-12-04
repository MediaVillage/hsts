<?php
/**
 * Define custom admin page for storing global settings
 *
 * @author  Red Fern <[<email address>]>
 * @version  1.0.0 [<description>]
 */
if ( ! defined( 'ABSPATH' ) ) {
    exit;
}

/**
 * Class RF_Admin_Menu
 */
class RF_Admin_Menu {

    /**
     * @var array
     */
    private static $twitter_settings;

    /**
     * @var array
     */
    private static $google_maps_settings;

    /**
     * Initialize settings
     */
    public static function init()
    {
        add_action( 'admin_menu', array( __CLASS__, 'admin_menu' ) );
        add_action( 'admin_init', array( __CLASS__, 'admin_init' ) );
    }

    /**
     * Define custom admin page
     */
    public static function admin_menu()
    {
        add_menu_page(
            __( 'R-F', 'rftheme' ),
            __( 'R-F', 'rftheme' ),
            'manage_options',
            'rf-framework',
            array( __CLASS__, 'settings_page' )
        );
    }

    /**
     * Output the settings page
     */
    public static function settings_page()
    {
        // Set class property
        self::$twitter_settings = get_option( 'rf_twitter' );
        self::$google_maps_settings = get_option( 'rf_google_maps' );
        ?>
        <div class="wrap">
            <h2>RF Framework</h2>
            <form method="post" action="options.php">
                <?php
                // This prints out all hidden setting fields
                settings_fields( 'rf_option_group' );
                do_settings_sections( 'rf-framework' );
                submit_button();
                ?>
            </form>
        </div>
    <?php
    }

    /**
     * Register admin settings
     */
    public static function admin_init()
    {
        add_settings_section(
            'rftheme',
            __( 'RF Framework', 'rftheme' ),
            array( __CLASS__, 'print_section_info' ),
            'rf-framework'
        );

        self::register_twitter_settings();
        self::register_google_maps_settings();
    }

    /**
     * Print section info
     */
    public static function print_section_info()
    {
        print 'Enter your specific settings:';
    }

    /**
     * Register twitter api key fields
     */
    public static function register_twitter_settings()
    {
        register_setting( 'rf_option_group', 'rf_twitter' );

        // Twitter Section
        add_settings_section(
            'rf-tweets',
            __( 'Twitter Settings', 'rftheme' ),
            array( __CLASS__, 'print_twitter_section_info' ),
            'rf-framework'
        );

        add_settings_field(
            'rf_twitter_consumer_key',
            __( 'Consumer Key', 'rftheme' ),
            array( __CLASS__, 'input_field' ),
            'rf-framework',
            'rf-tweets',
            array( 'field' => 'consumer_key' )
        );

        add_settings_field(
            'rf_twitter_consumer_secret',
            __( 'Consumer Secret', 'rftheme' ),
            array( __CLASS__, 'input_field' ),
            'rf-framework',
            'rf-tweets',
            array( 'field' => 'consumer_secret' )
        );

        add_settings_field(
            'rf_twitter_access_token',
            __( 'Access Token', 'rftheme' ),
            array( __CLASS__, 'input_field' ),
            'rf-framework',
            'rf-tweets',
            array( 'field' => 'access_token' )
        );

        add_settings_field(
            'rf_twitter_access_token_secret',
            __( 'Access Token Secret', 'rftheme' ),
            array( __CLASS__, 'input_field' ),
            'rf-framework',
            'rf-tweets',
            array( 'field' => 'access_token_secret' )
        );
    }

    /**
     * Print section info
     */
    public static function print_twitter_section_info()
    {
        print 'Enter the site twitter credentials:';
    }

    /**
     * Register google maps settings
     */
    public static function register_google_maps_settings()
    {
        register_setting( 'rf_option_group', 'rf_google_maps' );

        // Twitter Section
        add_settings_section(
            'rf-google-maps',
            __( 'Google Maps Settings', 'rftheme' ),
            array( __CLASS__, 'print_google_maps_section_info' ),
            'rf-framework'
        );

        add_settings_field(
            'rf_google_maps_api_key',
            __( 'API Key', 'rftheme' ),
            array( __CLASS__, 'google_maps_field' ),
            'rf-framework',
            'rf-google-maps',
            array( 'field' => 'api_key' )
        );
    }

    /**
     * Google maps settings text
     */
    public static function print_google_maps_section_info()
    {
        print 'Enter the site google maps credentials:';
    }

    /**
     * Output text field
     *
     * @param $data
     */
    public static function input_field( $data )
    {
        $field = $data['field'];
        printf(
            '<input type="text" id="%s" name="rf_twitter[%s]" class="widefat" value="%s" />',
            $field,
            $field,
            isset( self::$twitter_settings[$field] ) ? esc_attr( self::$twitter_settings[$field]) : ''
        );
    }

    /**
     * Google Maps field
     * 
     * @param  array $data
     */
    public static function google_maps_field( $data )
    {
        $field = $data['field'];
        printf(
            '<input type="text" id="%s" name="rf_google_maps[%s]" class="widefat" value="%s" />',
            $field,
            $field,
            isset( self::$google_maps_settings[$field] ) ? esc_attr( self::$google_maps_settings[$field]) : ''
        );
    }
}

RF_Admin_Menu::init();