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

	<footer id="colophon" class="site-footer" role="contentinfo">
		<div class="container">
			
			<div class="footer-main">
				<div class="row">
					<div class="col-sm-4">
						<h3>Replace with Widget</h3>
						<ul class="menu">
							<li class="menu-item"><a href="#">Menu Link 1</a></li>
							<li class="menu-item"><a href="#">Menu Link 2</a></li>
							<li class="menu-item"><a href="#">Menu Link 3</a></li>
							<li class="menu-item"><a href="#">Menu Link 4</a></li>
						</ul>
					</div>
					<div class="col-sm-4">
						<h3>Replace with Widget</h3>
						<p>Lorem ipsum dolor sit amet, consectetur adipiscing elit. Praesent tristique enim id tristique pharetra. Aenean ac nulla diam. Duis dictum id nulla et convallis.</p>
					</div>
					<div class="col-sm-4">
						<h3>Replace with Widget</h3>
						<p>Lorem ipsum dolor sit amet, consectetur adipiscing elit. Praesent tristique enim id tristique pharetra. Aenean ac nulla diam. Duis dictum id nulla et convallis.</p>
					</div>
				</div>
			</div>

			<div class="site-info">
				© <?php bloginfo($name) ?> <?php echo date("Y"); ?>.  All world rights reserved | Site by <a rel"no-follow" href="https://www.red-fern.co.uk">Red-fern</a>
			</div><!-- .site-info -->

		</div>
	</footer><!-- #colophon -->
</div><!-- #page -->

<?php wp_footer(); ?>

</body>
</html>
