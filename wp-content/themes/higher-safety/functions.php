<?php



/**
 * Register widget area.
 *
 * @link https://developer.wordpress.org/themes/functionality/sidebars/#registering-a-sidebar
 */
if ( ! function_exists('rftheme_widgets_init') ) {
    function rftheme_widgets_init() {
        register_sidebar( array(
            'name'          => esc_html__( 'Sidebar', 'rftheme' ),
            'id'            => 'sidebar',
            'description'   => esc_html__( 'Add widgets here.', 'rftheme' ),
            'before_widget' => '<section id="%1$s" class="widget %2$s">',
            'after_widget'  => '</section>',
            'before_title'  => '<h2 class="widget-title">',
            'after_title'   => '</h2>',
        ) );
        register_sidebar( array(
            'name'          => esc_html__( 'Header Left', 'rftheme' ),
            'id'            => 'header-left',
            'description'   => esc_html__( 'Add widgets here.', 'rftheme' ),
            'before_widget' => '<section id="%1$s" class="widget %2$s">',
            'after_widget'  => '</section>',
            'before_title'  => '<h2 class="widget-title">',
            'after_title'   => '</h2>',
        ) );
        register_sidebar( array(
            'name'          => esc_html__( 'Header Right', 'rftheme' ),
            'id'            => 'header-right',
            'description'   => esc_html__( 'Add widgets here.', 'rftheme' ),
            'before_widget' => '<section id="%1$s" class="widget %2$s">',
            'after_widget'  => '</section>',
            'before_title'  => '<h3 class="widget-title">',
            'after_title'   => '</h3>',
		) );
        register_sidebar( array(
            'name'          => esc_html__( 'Testimonals', 'rftheme' ),
            'id'            => 'testimonals',
            'description'   => esc_html__( 'Add widgets here.', 'rftheme' ),
            'before_widget' => '<section id="%1$s" class="widget %2$s">',
            'after_widget'  => '</section>',
            'before_title'  => '<h4 class="widget-title">',
            'after_title'   => '</h4>',
        ) );
        register_sidebar( array(
            'name'          => esc_html__( 'Pasma', 'rftheme' ),
            'id'            => 'pasma',
            'description'   => esc_html__( 'Add widgets here.', 'rftheme' ),
            'before_widget' => '<section id="%1$s" class="widget %2$s">',
            'after_widget'  => '</section>',
            'before_title'  => '<h4 class="widget-title">',
            'after_title'   => '</h4>',
        ) );
		register_sidebar( array(
            'name'          => esc_html__( 'Accreditations', 'rftheme' ),
            'id'            => 'accreditations',
            'description'   => esc_html__( 'Add widgets here.', 'rftheme' ),
            'before_widget' => '<section id="%1$s" class="widget %2$s">',
            'after_widget'  => '</section>',
            'before_title'  => '<h4 class="widget-title">',
            'after_title'   => '</h4>',
		) );
        register_sidebar( array(
            'name'          => esc_html__( 'Footer 1', 'rftheme' ),
            'id'            => 'footer-1',
            'description'   => esc_html__( 'Add widgets here.', 'rftheme' ),
            'before_widget' => '<section id="%1$s" class="widget %2$s">',
            'after_widget'  => '</section>',
            'before_title'  => '<h4 class="widget-title">',
            'after_title'   => '</h4>',
		) );
        register_sidebar( array(
            'name'          => esc_html__( 'Footer 2', 'rftheme' ),
            'id'            => 'footer-2',
            'description'   => esc_html__( 'Add widgets here.', 'rftheme' ),
            'before_widget' => '<section id="%1$s" class="widget %2$s">',
            'after_widget'  => '</section>',
            'before_title'  => '<h4 class="widget-title">',
            'after_title'   => '</h4>',
		) );	
        register_sidebar( array(
            'name'          => esc_html__( 'Footer 3', 'rftheme' ),
            'id'            => 'footer-3',
            'description'   => esc_html__( 'Add widgets here.', 'rftheme' ),
            'before_widget' => '<section id="%1$s" class="widget %2$s">',
            'after_widget'  => '</section>',
            'before_title'  => '<h4 class="widget-title">',
            'after_title'   => '</h4>',
		) );
        register_sidebar( array(
            'name'          => esc_html__( 'Footer 4', 'rftheme' ),
            'id'            => 'footer-4',
            'description'   => esc_html__( 'Add widgets here.', 'rftheme' ),
            'before_widget' => '<section id="%1$s" class="widget %2$s">',
            'after_widget'  => '</section>',
            'before_title'  => '<h4 class="widget-title">',
            'after_title'   => '</h4>',
        ) );	
    }
}
add_action( 'widgets_init', 'rftheme_widgets_init' );




add_action( 'after_setup_theme', 'ab_after_setup' );
/**
 * Add theme setup and custom image sizes
 */
function ab_after_setup()
{
    add_image_size( 'blog_thumb', 400, 250, true );
    add_image_size( 'circle', 750, 750, true );
}
