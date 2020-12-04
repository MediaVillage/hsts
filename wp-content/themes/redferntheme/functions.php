<?php
/**
 * RF Theme functions and definitions.
 *
 * @link https://developer.wordpress.org/themes/basics/theme-functions/
 *
 * @package RF_Theme
 */

if ( ! function_exists( 'rftheme_setup' ) ) :
/**
 * Sets up theme defaults and registers support for various WordPress features.
 *
 * Note that this function is hooked into the after_setup_theme hook, which
 * runs before the init hook. The init hook is too late for some features, such
 * as indicating support for post thumbnails.
 */
function rftheme_setup() {
	/*
	 * Make theme available for translation.
	 * Translations can be filed in the /languages/ directory.
	 * If you're building a theme based on RF Theme, use a find and replace
	 * to change 'rftheme' to the name of your theme in all the template files.
	 */
	load_theme_textdomain( 'rftheme', get_template_directory() . '/languages' );

	// Add default posts and comments RSS feed links to head.
	add_theme_support( 'automatic-feed-links' );

	/*
	 * Let WordPress manage the document title.
	 * By adding theme support, we declare that this theme does not use a
	 * hard-coded <title> tag in the document head, and expect WordPress to
	 * provide it for us.
	 */
	add_theme_support( 'title-tag' );

	/*
	 * Enable support for Post Thumbnails on posts and pages.
	 *
	 * @link https://developer.wordpress.org/themes/functionality/featured-images-post-thumbnails/
	 */
	add_theme_support( 'post-thumbnails' );

	// This theme uses wp_nav_menu() in one location.
	register_nav_menus( array(
		'primary' => esc_html__( 'Primary', 'rftheme' ),
	) );

	/*
	 * Switch default core markup for search form, comment form, and comments
	 * to output valid HTML5.
	 */
	add_theme_support( 'html5', array(
		'search-form',
		'comment-form',
		'comment-list',
		'gallery',
		'caption',
	) );

	/**
	 * Add post formats
	 */
	add_theme_support( 'post-formats', array( 'aside', 'gallery' ) );

	/**
	 * Add custom logo support
	 */
	add_theme_support( 'custom-logo', array(
		'height'      => 100,
		'width'       => 400,
		'flex-height' => true,
		'flex-width'  => true,
		'header-text' => array( 'site-title', 'site-description' ),
	) );

	/**
	 * Add custom image sizes
	 */
	add_image_size( 'blog_large', 1000, 448, true );
}
endif;
add_action( 'after_setup_theme', 'rftheme_setup' );

/**
 * Set the content width in pixels, based on the theme's design and stylesheet.
 *
 * Priority 0 to make it available to lower priority callbacks.
 *
 * @global int $content_width
 */
function rftheme_content_width() {
	$GLOBALS['content_width'] = apply_filters( 'rftheme_content_width', 640 );
}
add_action( 'after_setup_theme', 'rftheme_content_width', 0 );

/**
 * Register widget area.
 *
 * @link https://developer.wordpress.org/themes/functionality/sidebars/#registering-a-sidebar
 */
if ( ! function_exists('rftheme_widgets_init') ) {
    function rftheme_widgets_init() {
        register_sidebar( array(
            'name'          => esc_html__( 'Sidebar', 'rftheme' ),
            'id'            => 'sidebar-1',
            'description'   => esc_html__( 'Add widgets here.', 'rftheme' ),
            'before_widget' => '<section id="%1$s" class="widget %2$s">',
            'after_widget'  => '</section>',
            'before_title'  => '<h2 class="widget-title">',
            'after_title'   => '</h2>',
        ) );
        register_sidebar( array(
            'name'          => esc_html__( 'Header 1', 'rftheme' ),
            'id'            => 'header-1',
            'description'   => esc_html__( 'Add widgets here.', 'rftheme' ),
            'before_widget' => '<section id="%1$s" class="widget %2$s">',
            'after_widget'  => '</section>',
            'before_title'  => '<h3 class="widget-title">',
            'after_title'   => '</h3>',
        ) );
    }
}
add_action( 'widgets_init', 'rftheme_widgets_init' );

/**
 * Enqueue scripts and styles.
 */
if ( ! function_exists('rftheme_scripts') ) {
    function rftheme_scripts() {
        wp_enqueue_style( 'rftheme-style', get_stylesheet_uri() );

        wp_enqueue_script( 'rftheme', get_stylesheet_directory_uri() . '/js/main.js', array( 'jquery' ), false, true );
        wp_localize_script( 'rftheme', 'rftheme', array('ajaxurl' => admin_url( 'admin-ajax.php' )) );

        if ( is_singular() && comments_open() && get_option( 'thread_comments' ) ) {
            wp_enqueue_script( 'comment-reply' );
        }
    }
}
add_action( 'wp_enqueue_scripts', 'rftheme_scripts' );

/**
 * Set the components that are active on this website
 * Available options are:
 *
 * cart_panel - Displays a offcanvas panel for cart items and totals
 * 				please activate the JS/CSS by uncommenting code
 * 				in main.js and components.scss
 */
if ( ! function_exists('rf_active_components') ) {
	function rf_active_components( $components ) {
		return $components;
	}
}
add_filter( 'rf_active_components', 'rf_active_components' );

/**
 * Custom Post Type UI - Visual Composer fix
 */
if ( function_exists('cptui_create_custom_post_types') ) {
    remove_action( 'init', 'cptui_create_custom_post_types', 10 );
    add_action( 'init', 'cptui_create_custom_post_types', 1);
}

/**
 * Register google maps
 */
if ( ! function_exists('rf_register_google_maps_script') ) {
	function rf_register_google_maps_script() {
		$settings = get_option('rf_google_maps');
		if ( isset($settings['api_key']) && $api_key = $settings['api_key'] ) {
			wp_register_script( 'rf-google-maps', "https://maps.googleapis.com/maps/api/js?key={$api_key}" );
		}
	}
}
add_action( 'wp_enqueue_scripts', 'rf_register_google_maps_script', 9 );

/**
 * Include Framework
 */
require get_template_directory() . '/rf-framework/class-rf-framework.php';

/**
 * Include plugins
 */
require get_template_directory() . '/inc/plugins-include.php';

/**
 * Custom template tags for this theme.
 */
require get_template_directory() . '/inc/template-tags.php';

/**
 * Custom functions that act independently of the theme templates.
 */
require get_template_directory() . '/inc/extras.php';

/**
 * Customizer additions.
 */
require get_template_directory() . '/inc/customizer.php';

/**
 * Alter login form
 */
require_once get_template_directory() . '/inc/custom-login.php';

/** 
 * ACF - Google map API key
 */
if ( ! function_exists( 'rf_acf_google_map_api' ) ) {
	function rf_acf_google_map_api( $api ){
		$settings = get_option('rf_google_maps');
		if ( isset($settings['api_key']) ) {
			$api['key'] = $settings['api_key'];
		}
		return $api;
	}
}
add_filter('acf/fields/google_map/api', 'rf_acf_google_map_api');
