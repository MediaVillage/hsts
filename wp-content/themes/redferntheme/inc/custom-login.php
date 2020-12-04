<?php
/**
 * Actions and filters used to alter the main Wordpress login form
 *
 * @author Red Fern
 * @version 1.0.0
 */
if ( ! defined( 'ABSPATH' ) ) exit;

/**
 * Load the css that will alter the Wordpress login page
 */
if ( ! function_exists( 'rf_login_scripts' ) ) {
    function rf_login_scripts() {
        echo '<link rel="stylesheet" type="text/css" href="' . get_stylesheet_directory_uri() . '/css/login.css" />';
    }
}
add_action('login_head',  'rf_login_scripts' );

/**
 * Change login logo url
 */
if ( ! function_exists( 'rf_logo_url' ) ) {
    function rf_logo_url() {
        return get_bloginfo( 'url' );
    }
}
add_filter( 'login_headerurl',  'rf_logo_url' );

/**
 * Change login logo url title
 */
if ( ! function_exists( 'rf_logo_url_title' ) ) {
    function rf_logo_url_title() {
        return get_bloginfo( 'name' );
    }
}
add_filter( 'login_headertitle',  'rf_logo_url_title' );

/**
 * Change incorrect login error message
 */
if ( ! function_exists( 'rf_login_error_message' ) ) {
    function rf_login_error_message() {
        return 'Incorrect login details.';
    }
}
add_filter('login_errors', 'rf_login_error_message');

/**
 * Set rememebr me to yes by default
 */
if ( ! function_exists( 'rf_login_checked_remember_me' ) ) {
    function rf_login_checked_remember_me() {
        add_filter( 'login_footer', 'rf_check_remember_me' );
    }
}
add_action( 'init', 'rf_login_checked_remember_me' );

/**
 * Check the remember me box by default
 */
function rf_check_remember_me() {
    echo "<script>document.getElementById('rememberme').checked = true;</script>";
}