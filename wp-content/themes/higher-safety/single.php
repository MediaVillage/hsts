<?php
/**
 * The template for displaying all single posts.
 *
 * @link https://developer.wordpress.org/themes/basics/template-hierarchy/#single-post
 *
 * @package RF_Theme
 */

get_header(); ?>


<div class="banner">
	<div class="container">
		<h2>Blog</h2>
	</div>
</div>
		
	<div class="container">
		<div class="row content-row narrow">

			<div id="primary" class="col-md-8 content-area">
				<main id="main" class="site-main" role="main">

				<?php
				while ( have_posts() ) : the_post();

					get_template_part( 'template-parts/content', get_post_format() );

					
				endwhile; // End of the loop.
				?>

				</main><!-- #main -->
			</div><!-- #primary -->
			<div class="col-md-4">
				<div class="course-menu">
					<?php dynamic_sidebar( 'sidebar' ); ?>
				</div>
			</div>
		
		</div><!-- .row -->
	</div><!-- .container -->


<?php
get_footer();
