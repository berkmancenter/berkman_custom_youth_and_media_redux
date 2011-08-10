<?php
/**
 * Description: A Page Template that adds a sidebar to pages
 *
 * @package WordPress
 * @subpackage Twenty_Eleven
 * @since Twenty Eleven 1.0
 */

wp_register_script('simplemodal', get_stylesheet_directory_uri() . '/js/jquery.simplemodal-1.4.1.js', array('jquery'));
wp_enqueue_script('simplemodal');
get_header('page'); ?>

		<div id="primary">
			<div id="content" role="main">

				<?php the_post(); ?>

				<?php get_template_part( 'content', 'page' ); ?>

				<?php comments_template( '', true ); ?>

			</div><!-- #content -->
		</div><!-- #primary -->

<?php get_sidebar(); ?>
<?php get_footer(); ?>
