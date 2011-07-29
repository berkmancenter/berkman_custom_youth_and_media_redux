<?php
/**
 * The main template file.
 *
 * This is the most generic template file in a WordPress theme
 * and one of the two required files for a theme (the other being style.css).
 * It is used to display a page when nothing more specific matches a query.
 * E.g., it puts together the home page when no home.php file exists.
 * Learn more: http://codex.wordpress.org/Template_Hierarchy
 *
 * @package WordPress
 * @subpackage Twenty_Eleven
 * Template Name: Isotope Page
 */

wp_register_script('isotope', get_stylesheet_directory_uri() . '/js/jquery.isotope.min.js', array('jquery') );
wp_register_script('youth-and-media', get_stylesheet_directory_uri() . '/js/youth-and-media.js', array('isotope') );
wp_enqueue_script('isotope');
wp_enqueue_script('youth-and-media');

get_header(); ?>

		<div id="primary">
				<div id="filters">
					<button id="showall">show all</button>
					<label for="audio">audio</label><input type="checkbox" id="audio" value=".Audio" />
					<label for="video">video</label><input type="checkbox" id="video" value=".Video" />
					<label for="document">documents</label><input type="checkbox" id="document" value=".Document" />
					<label for="image">images</label><input type="checkbox" id="image" value=".Image" />
					<label for="text">text</label><input type="checkbox" id="text" value=".Text" />
				</div>
			<div id="content" role="main">

			<?php if ( have_posts() ) : ?>

				<?php twentyeleven_content_nav( 'nav-above' ); ?>


				<?php //wp_reset_postdata(); ?>
				<?php query_posts(array( 'post_type' => array('post'))); ?>

				<?php /* Start the Loop */ ?>
				<?php while ( have_posts() ) : the_post(); ?>

					<?php get_template_part( 'block' ); ?>

				<?php endwhile; ?>

				<?php twentyeleven_content_nav( 'nav-below' ); ?>

			<?php endif; ?>

			</div><!-- #content -->
		</div><!-- #primary -->

<?php get_sidebar('front'); ?>
<?php get_footer(); ?>
