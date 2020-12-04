
<?php
/**
 * The template for displaying all pages.
 *
 * This is the template that displays all pages by default.
 * Please note that this is the WordPress construct of pages
 * and that other 'pages' on your WordPress site may use a
 * different template.
 *
 * @link https://codex.wordpress.org/Template_Hierarchy
 *
 * @package RF_Theme
 */
/* Template Name: Contact */

get_header(); 

//ACF Variables
$featured_header = get_field('featured_header');
$featured_header_image = get_field('featured_header_image');
$header_class = ( empty($featured_header_image) ) ? 'class="entry-header featured-header"' : 'class="entry-header featured-header featured-image" style="background-image:url('.$featured_header_image.');"';
$rf_map = get_field('contact_map');

?>


	<?php if( get_field('show_title') ): ?>
		<header <?php echo ( (empty($featured_header)) ? 'class="entry-header"' : $header_class ); ?> >
			<div class="container">
				<div class="vertical-table">
					<div class="vertical-cell">
						<?php the_title( '<h1 class="entry-title">', '</h1>' ); ?>
					</div>
				</div> 
			</div>
		</header><!-- .entry-header -->
	<?php endif; ?>


	<div class="container">
		<div class="row">

			<div id="primary" class="col-sm-12 content-area">
				<main id="main" class="site-main" role="main">

					<?php
					while ( have_posts() ) : the_post();

						get_template_part( 'template-parts/content', 'page' );

						// If comments are open or we have at least one comment, load up the comment template.
						if ( comments_open() || get_comments_number() ) :
							comments_template();
						endif;

					endwhile; // End of the loop.
					?>

				</main><!-- #main -->
			</div><!-- #primary -->

		</div><!-- .row -->
	</div><!-- .container -->

	<?php if( !empty($rf_map) ): ?>
		<div class="acf-map">
			<div class="marker" data-lat="<?php echo $rf_map['lat']; ?>" data-lng="<?php echo $rf_map['lng']; ?>"></div>
		</div>
		<?php rf_map(); ?>
	<?php endif; ?>

<?php
get_footer();
