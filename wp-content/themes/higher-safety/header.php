<?php
/**
 * The header for our theme.
 *
 * This is the template that displays all of the <head> section and everything up until <div id="content">
 *
 * @link https://developer.wordpress.org/themes/basics/template-files/#template-partials
 *
 * @package RF_Theme
 */
?>
<!DOCTYPE html>
<html <?php language_attributes(); ?>>
<head>
<meta charset="<?php bloginfo( 'charset' ); ?>">
<meta name="viewport" content="width=device-width, initial-scale=1">
<link rel="profile" href="http://gmpg.org/xfn/11">

<?php wp_head(); ?>
</head>

<body <?php body_class(); ?>>
<div id="page" class="site">

	<header id="masthead" <?php rf_site_header_class(); ?>  role="banner">

	<div class="header-top">
		<div class="container">
            <?php dynamic_sidebar( 'header-left' ); ?>
			<?php dynamic_sidebar( 'header-right' ); ?>
		</div>
	</div>
		
	<div class="header-main">
		<div class="container">	
				<div class="site-branding">
					<?php rftheme_logo(); ?>
				</div><!-- .site-branding -->

				<nav id="site-navigation" class="main-navigation" role="navigation">
					<!-- <span class="menu-close"><i class="fa fa-close"></i></span> -->
					<?php wp_nav_menu( array(
						'theme_location' => 'primary',
						'container' => false,
						'menu_id' => 'primary-menu',
						'walker' => new RF_Walker_Primary() ) ); ?>



				</nav><!-- #site-navigation -->
			</div>

				<div class="mobile-menu-button">
					<div id="menu-toggle">
						<div id="hamburger">
							<span></span>
							<span></span>
							<span></span>
						</div>
						<div id="cross">
							<span></span>
							<span></span>
						</div>
					</div>
				</div>
				

		</div>
	</header><!-- #masthead -->

	<div id="content" class="site-content">