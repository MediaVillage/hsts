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


		<div class="accreditations content-row narrow">
			<?php dynamic_sidebar( 'accreditations' ); ?>
		</div>


	<footer id="colophon" class="site-footer" role="contentinfo">

		
		<div class="container">
			
			<div class="footer-main">
				<div class="row">
					<div class="col-sm-3 footer-1">
						<?php dynamic_sidebar( 'footer-1' ); ?>
					</div>
					<div class="col-sm-3 footer-2">
						<?php dynamic_sidebar( 'footer-2' ); ?>
					</div>
					<div class="col-sm-3 footer-3">
						<?php dynamic_sidebar( 'footer-3' ); ?>
					</div>
					<div class="col-sm-3 footer-4">
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
 