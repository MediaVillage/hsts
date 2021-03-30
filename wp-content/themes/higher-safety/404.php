<?php
/**
 * The template for displaying 404 pages (not found).
 *
 * @link https://codex.wordpress.org/Creating_an_Error_404_Page
 *
 * @package RF_Theme
 */

get_header(); ?>

	<div id="primary" class="content-area content-row narrow">
		<main id="main" class="site-main" role="main">
            
            <div class="container search-container">

			<section class="error-404 not-found">
				
					<h2 style="margin-top: 0;" class="page-title"><?php esc_html_e( 'Oops! That page can&rsquo;t be found.', 'rftheme' ); ?></h2>
				

			</section><!-- .error-404 -->


			</div>

		</main><!-- #main -->
	</div><!-- #primary -->

<?php
get_footer();
