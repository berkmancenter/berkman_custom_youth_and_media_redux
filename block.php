<?php
$block_sizes = get_the_terms($post->ID, 'block_sizes');
?>
<?php if (has_post_thumbnail( $post->ID ) && $block_sizes): ?>
<?php $block_size = reset($block_sizes)->slug; ?>
<?php $image = wp_get_attachment_image_src( get_post_thumbnail_id( $post->ID ), 'single-post-thumbnail' ); ?>
<div class="<?php echo strtolower($block_size); ?> block" style="background-image: url('<?php echo $image[0]; ?>')">
	<div class="post-info">
		<span class="post-title"><?php the_title(); ?></span>
		<div class="post-excerpt"><?php the_excerpt(); ?></div>
	</div>
</div>
<?php endif; ?>
