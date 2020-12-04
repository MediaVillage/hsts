<?php
add_action( 'wp_enqueue_scripts', 'enqueue_parent_styles' );
function enqueue_parent_styles() {
   wp_enqueue_style( 'parent-style', get_template_directory_uri().'/style.css' );
}


/**
 * Load custom CSS files in the child template if not using SCSS - uncomment to switch on
 */
//wp_register_style("custom", get_template_directory_uri() . "/../child-theme/css/custom.css", '', '1.0.0');
//wp_enqueue_style('custom');






/**
 * Register widget area.
 *
 * @link https://developer.wordpress.org/themes/functionality/sidebars/#registering-a-sidebar
 */
function mv_init() {
	register_sidebar( array(
		'name'          => __( 'Header Centre', 'mv' ),
		'id'            => 'header-centre',
		'description'   => __( 'Add widgets here to appear in your sidebar on blog posts and archive pages.', 'mv' ),
		'before_widget' => '<section id="%1$s" class="widget %2$s">',
		'after_widget'  => '</section>',
		'before_title'  => '<h2 class="widget-title">',
		'after_title'   => '</h2>',
	) );	
	register_sidebar( array(
		'name'          => __( 'Header Right', 'mv' ),
		'id'            => 'header-right',
		'description'   => __( 'Add widgets here to appear in your sidebar on blog posts and archive pages.', 'mv' ),
		'before_widget' => '<section id="%1$s" class="widget %2$s">',
		'after_widget'  => '</section>',
		'before_title'  => '<h2 class="widget-title">',
		'after_title'   => '</h2>',
	) );	
	register_sidebar( array(
		'name'          => __( 'Footer Top', 'mv' ),
		'id'            => 'footer-top',
		'description'   => __( 'Add widgets here to appear in your sidebar on blog posts and archive pages.', 'mv' ),
		'before_widget' => '<section id="%1$s" class="widget %2$s">',
		'after_widget'  => '</section>',
		'before_title'  => '<h2 class="widget-title">',
		'after_title'   => '</h2>',
	) );
	register_sidebar( array(
		'name'          => __( 'Footer 1', 'mv' ),
		'id'            => 'footer-1',
		'description'   => __( 'Add widgets here to appear in your sidebar on blog posts and archive pages.', 'mv' ),
		'before_widget' => '<section id="%1$s" class="widget %2$s">',
		'after_widget'  => '</section>',
		'before_title'  => '<h4 class="widget-title">',
		'after_title'   => '</h4>',
	) );	
	register_sidebar( array(
		'name'          => __( 'Footer 2', 'mv' ),
		'id'            => 'footer-2',
		'description'   => __( 'Add widgets here to appear in your sidebar on blog posts and archive pages.', 'mv' ),
		'before_widget' => '<section id="%1$s" class="widget %2$s">',
		'after_widget'  => '</section>',
		'before_title'  => '<h4 class="widget-title">',
		'after_title'   => '</h4>',
	) );	
	register_sidebar( array(
		'name'          => __( 'Footer 3', 'mv' ),
		'id'            => 'footer-3',
		'description'   => __( 'Add widgets here to appear in your sidebar on blog posts and archive pages.', 'mv' ),
		'before_widget' => '<section id="%1$s" class="widget %2$s">',
		'after_widget'  => '</section>',
		'before_title'  => '<h4 class="widget-title">',
		'after_title'   => '</h4>',
	) );	
	register_sidebar( array(
		'name'          => __( 'Footer 4', 'mv' ),
		'id'            => 'footer-4',
		'description'   => __( 'Add widgets here to appear in your sidebar on blog posts and archive pages.', 'mv' ),
		'before_widget' => '<section id="%1$s" class="widget %2$s">',
		'after_widget'  => '</section>',
		'before_title'  => '<h4 class="widget-title">',
		'after_title'   => '</h4>',
	) );
	register_sidebar( array(
		'name'          => __( 'Footer Bottom', 'mv' ),
		'id'            => 'footer-bottom',
		'description'   => __( 'Add widgets here to appear in your sidebar on blog posts and archive pages.', 'mv' ),
		'before_widget' => '<section id="%1$s" class="widget %2$s">',
		'after_widget'  => '</section>',
		'before_title'  => '<h4 class="widget-title">',
		'after_title'   => '</h4>',
	) );								
}
add_action( 'widgets_init', 'mv_init' );









