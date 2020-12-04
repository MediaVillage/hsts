<?php
/**
 * Template functions
 * 
 * @author Red Fern
 * @version 1.0.0
 */
if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly
}

/**
 * Output content class
 * @return string
 */
function rf_content_class() {
	$blog_layout = get_theme_mod( 'blog_sidebar', 'right-sidebar' );
	$classes = array();

	switch($blog_layout) {
		case 'no-sidebar':
			$classes[] = 'col-sm-12';
			break;
		case 'left-sidebar':
			$classes[] = 'col-sm-9 col-sm-push-3';
			break;
		default:
			$classes[] = 'col-sm-9';
			break;
	}
	$classes = apply_filters( 'rf_content_class', $classes );
	echo implode(' ', $classes);
}

/**
 * Output classes used for the sidebar
 */
function rf_sidebar_class() {
    $blog_layout = get_theme_mod( 'blog_sidebar', 'right-sidebar' );
    $classes = array();

    // sidebar width
    $classes[] = 'col-sm-3';
    if ( $blog_layout == 'left-sidebar' ) {
        $classes[] = 'col-sm-pull-9';
    }

    // Sidebar classes
    $classes = apply_filters( 'rf_sidebar_class', $classes );
    echo implode(' ', $classes);
}