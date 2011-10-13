<?php
/**
 * Template Name: Sidebar Template
 * Description: A Page Template that adds a sidebar to pages
 *
 * @package WordPress
 * @subpackage Twenty_Eleven
 * @since Twenty Eleven 1.0
 */

wp_register_script('simplemodal', get_stylesheet_directory_uri() . '/js/jquery.simplemodal-1.4.1.js', array('jquery'));
wp_register_script('tablesorter', get_stylesheet_directory_uri() . '/js/jquery.tablesorter.min.js', array('jquery'));
wp_register_script('page', get_stylesheet_directory_uri() . '/js/page.js', array('jquery'));
wp_register_script('scrubber', get_stylesheet_directory_uri() . '/js/jquery.scrubber.js', array('jquery'));
wp_enqueue_script('simplemodal');
wp_enqueue_script('tablesorter');
wp_enqueue_script('page');
wp_enqueue_script('scrubber');

get_header();
get_template_part('carousel'); ?>

		<div id="primary">
			<div id="content" role="main">

				<?php the_post(); ?>

				<?php get_template_part( 'content', 'page' ); ?>

				<?php comments_template( '', true ); ?>

			</div><!-- #content -->
		</div><!-- #primary -->

<?php get_sidebar(); ?>
<?php get_footer(); ?>
