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
 */

wp_register_script('isotope', get_stylesheet_directory_uri() . '/js/jquery.isotope.min.js', array('jquery') );
wp_register_script('front', get_stylesheet_directory_uri() . '/js/front.js', array('isotope') );
wp_enqueue_script('isotope');
wp_enqueue_script('front');

$query_string = "
	SELECT $wpdb->posts.* FROM
	$wpdb->posts WHERE
	($wpdb->posts.post_type = 'post' OR
	$wpdb->posts.post_type = 'page')
	AND $wpdb->posts.post_status = 'publish' 
	AND $wpdb->posts.post_date < NOW()
";

$pageposts = $wpdb->get_results($query_string, OBJECT);
$filters = get_terms('post_contains', array('fields' => 'names'));

get_header(); ?>

		<div id="primary">
                <?php if ( ! empty( $filters ) ): ?>
				<h2>Explore some of Youth and Media's highlights. Only show:</h2>
				<div id="filters">
                    <?php foreach ($filters as $filter): ?>
                    <label for="<?php echo esc_attr(strtolower($filter)); ?>">
                        <?php echo esc_html(strtoupper($filter)); ?>
                    </label>
                        <input type="checkbox" id="<?php echo esc_attr(strtolower($filter)); ?>" value=".<?php echo esc_attr(strtolower($filter)); ?>" />
                    <?php endforeach; ?>
					<button id="showall">show all</button>
				</div>
                <?php endif; ?>
			<div id="content" role="main">

			<?php if ($pageposts): ?>
				<?php global $post; ?>

				<?php twentyeleven_content_nav( 'nav-above' ); ?>

				<?php /* Start the Loop */ ?>
				<?php foreach ($pageposts as $post): ?>
					<?php setup_postdata($post); ?>

					<?php get_template_part( 'block' ); ?>

				<?php endforeach; ?>

				<?php twentyeleven_content_nav( 'nav-below' ); ?>

			<?php endif; ?>

			</div><!-- #content -->
		</div><!-- #primary -->
<!--[if lt IE 9]>
<script>
jQuery(function() {
    function filterblocks(id) {
        jquery('label[for="' + id + '"]').toggleclass('filter-active');
        var isotopeselector = jquery.map(jquery('#filters :checked'), function(elem) { return jquery(elem).val(); }).tostring().replace(/,/g, '');
        jquery('#content').isotope({ filter: isotopeselector });
    }
    jQuery('#filters label').click(function(e){
        jQuery('#' + jQuery(this).attr('for')).attr('checked',  jQuery('#' + jQuery(this).attr('for')).attr('checked') != 'checked');
        filterBlocks(jQuery(this).attr('for'));
    });
});
</script>
<![endif]-->
<?php get_sidebar('front'); ?>
<?php get_footer(); ?>
