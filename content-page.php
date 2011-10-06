<?php
/**
 * The template used for displaying page content in page.php
 *
 * @package WordPress
 * @subpackage Twenty_Eleven
 * @since Twenty Eleven 1.0
 */

function get_breadcrumb($id)
{
    $post = get_post($id);
    $breadcrumb_posts = array(esc_attr(get_the_title($id)));

    while ($post->post_parent >= 0 && $post->post_parent != false && $id != $post->post_parent && get_option('page_on_front') != $post->post_parent) {
        $id = $post->post_parent;
        $post = get_post($id);
        $breadcrumb_posts[] = '<a href="' . esc_url(get_permalink($id)) . '" title="' . esc_attr(get_the_title($id)) . '">' . esc_attr(get_the_title($id)) . '</a>';
    }

    if (count($breadcrumb_posts) > 1) {
        return implode(esc_html(' > '), array_reverse($breadcrumb_posts));
    }
    else {
        return '';
    }
}
?>

<article id="post-<?php the_ID(); ?>" <?php post_class(); ?>>
	<header class="entry-header">
		<h1 class="entry-title"><?php the_title(); ?></h1>
		<div class="breadcrumbs">
		<?php echo get_breadcrumb(get_the_ID()); ?>
		</div>
	</header><!-- .entry-header -->

	<div class="entry-content">
		<?php the_content(); ?>
		<?php wp_link_pages( array( 'before' => '<div class="page-link"><span>' . __( 'Pages:', 'twentyeleven' ) . '</span>', 'after' => '</div>' ) ); ?>
	</div><!-- .entry-content -->
	<footer class="entry-meta">
		<?php edit_post_link( __( 'Edit', 'twentyeleven' ), '<span class="edit-link">', '</span>' ); ?>
	</footer><!-- .entry-meta -->
</article><!-- #post-<?php the_ID(); ?> -->
