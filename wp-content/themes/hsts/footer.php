<?php
/**
 * The template for displaying the footer
 *
 * Contains the closing of the #content div and all content after.
 *
 * @link https://developer.wordpress.org/themes/basics/template-files/#template-partials
 *
 * @package WordPress
 * @subpackage Twenty_Seventeen
 * @since 1.0
 * @version 1.2
 */

?>

		</div><!-- #content -->



		<?php if (is_active_sidebar('footer-top')) {
		     ?>
				<div class="footer-top">
					<div class="container">
						<?php dynamic_sidebar( 'footer-top' ); ?>	
					</div>
				</div>	
		 <?php
		     }
		 ?>
	

		<footer id="colophon" class="site-footer" role="contentinfo">
			<div class="container">
				
			


			<?php if (is_active_sidebar('footer-1')) {
     		?>
		     	<?php dynamic_sidebar('footer-1'); ?>
			 <?php
			     }
			 ?>


			<?php if (is_active_sidebar('footer-2')) {
     		?>
		     	<?php dynamic_sidebar('footer-2'); ?>
			 <?php
			     }
			 ?>


			<?php if (is_active_sidebar('footer-3')) {
     		?>
		     	<?php dynamic_sidebar('footer-3'); ?>
			 <?php
			     }
			 ?>


			<?php if (is_active_sidebar('footer-4')) {
     		?>
		     	<?php dynamic_sidebar('footer-4'); ?>
			 <?php
			     }
			 ?>			 			 




											


				<?php
				get_template_part( 'template-parts/footer/footer', 'widgets' );

				if ( has_nav_menu( 'social' ) ) : ?>
					<nav class="social-navigation" role="navigation" aria-label="<?php esc_attr_e( 'Footer Social Links Menu', 'twentyseventeen' ); ?>">
						<?php
							wp_nav_menu( array(
								'theme_location' => 'social',
								'menu_class'     => 'social-links-menu',
								'depth'          => 1,
								'link_before'    => '<span class="screen-reader-text">',
								'link_after'     => '</span>' . twentyseventeen_get_svg( array( 'icon' => 'chain' ) ),
							) );
						?>
					</nav><!-- .social-navigation -->
				<?php endif;

				
				?>
			</div><!-- .wrap -->
		</footer><!-- #colophon -->


		<?php if (is_active_sidebar('footer-bottom')) {
		     ?>
				<div class="footer-bottom">
					<div class="container">
						<?php dynamic_sidebar( 'footer-bottom' ); ?>	
					</div>
				</div>	
		 <?php
		     }
		 ?>

	</div><!-- .site-content-contain -->
</div><!-- #page -->
<?php wp_footer(); ?>

</body>
</html>


<!-- Header Search Modal Content -->
<div id="SearchModal" class="modal fade" tabindex="-1">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-body">
					<form role="search" method="get" class="search-form" action="<?php echo esc_url( home_url( '/' ) ); ?>">
					        <input type="search" class="search-field" placeholder="<?php echo esc_attr_x( 'Search &hellip;', 'placeholder' ); ?>" value="<?php echo get_search_query(); ?>" name="s" />
					    <button type="submit" class="search-submit btn"><i class="fas fa-search"></i></button>
					</form>
            </div>
        </div>
    </div>
</div>