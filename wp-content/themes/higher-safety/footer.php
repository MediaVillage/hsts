<?php
/**
 * The template for displaying the footer.
 *
 * Contains the closing of the #content div and all content after.
 *
 * @link https://developer.wordpress.org/themes/basics/template-files/#template-partials
 *
 * @package RF_Theme
 */

?>

	</div><!-- #content -->


		<div class="testimonials content-row narrow">
			<div class="container">
				<?php dynamic_sidebar( 'testimonials' ); ?>
			</div>
		</div>

		<div class="pasma content-row narrow">
			<?php dynamic_sidebar( 'pasma' ); ?>
		</div>

		<div class="accreditations content-row narrow">
			<?php dynamic_sidebar( 'accreditations' ); ?>
		</div>


	<footer id="colophon" class="site-footer" role="contentinfo">

		
		<div class="container">
			
			<div class="footer-main">
				<div class="row">
					<div class="col-md-12 col-lg-1 footer-1">
						<?php dynamic_sidebar( 'footer-1' ); ?>
					</div>
					<div class="col-md-4 col-lg-offset-1 col-lg-4 footer-2">
						<?php dynamic_sidebar( 'footer-2' ); ?>
					</div>
					<div class="col-md-4 col-lg-2 footer-3">
						<?php dynamic_sidebar( 'footer-3' ); ?>
					</div>
					<div class="col-md-4 col-lg-3 footer-4">
						<?php dynamic_sidebar( 'footer-4' ); ?> 
					</div>					
				</div>
			</div>



		</div>
	</footer><!-- #colophon -->
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
        <input type="search" class="search-field" placeholder="<?php echo esc_attr_x( 'Search &hellip;', 'placeholder', 'rf-wpml' ); ?>" value="<?php echo get_search_query(); ?>" name="s" />
    <button type="submit" class="search-submit btn"><i class="fa fa-search"></i></button>
</form>
            </div>
        </div>
    </div>
</div>