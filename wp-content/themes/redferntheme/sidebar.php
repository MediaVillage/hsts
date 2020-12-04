<?php
/**
 * The sidebar containing the main widget area.
 *
 * @link https://developer.wordpress.org/themes/basics/template-files/#template-partials
 *
 * @package RF_Theme
 */

$blog_layout = get_theme_mod( 'blog_sidebar', 'right-sidebar' );
if ( ! is_active_sidebar( 'sidebar-1' ) || in_array($blog_layout, array('no-sidebar')) ) {
	return;
}
?>

<aside id="secondary" class="widget-area <?php rf_sidebar_class(); ?>" role="complementary">
	<?php dynamic_sidebar( 'sidebar-1' ); ?>
</aside><!-- #secondary -->
