<?php 
define('FLICKR_NSID', '33198938@N00');
define('FLICKR_API_KEY', '5687af375108fc3d342075b21af266e7');

function on_init() {
	register_taxonomy(
		'formats',
		array('post', 'page'),
		array(
			'label' => __('Formats'),
			'labels' => array(
				'name' => _x('Post Formats', 'taxonomy general name'),
				'singular_name' => _x('Category', 'taxonomy singular name')
			),
			'public' => false
		)
	);
			
	register_taxonomy(
		'block_sizes',
		array('post', 'page'),
		array(
			'label' => __('Block Sizes'),
			'labels' => array(
				'name' => _x('Block Sizes', 'taxonomy general name'),
				'singular_name' => _x('Block Size', 'taxonomy singular name')
			),
			'hierarchical' => true
		)
	);

	register_taxonomy(
		'flickr_tags',
		array('post', 'page'),
		array(
			'label' => __('Flickr Tags'),
			'labels' => array(
				'name' => _x('Flickr Tags', 'taxonomy general name'),
				'singular_name' => _x('Flickr Tag', 'taxonomy singular name')
			),
			'hierarchical' => true
		)
	);

	register_sidebar( array(
		'name' => __( 'Front Page Sidebar', 'youth-and-media' ),
		'id' => 'sidebar-6',
		'description' => __( 'The sidebar on the front page', 'youth-and-media' ),
		'before_widget' => '<aside id="%1$s" class="widget %2$s">',
		'after_widget' => "</aside>",
		'before_title' => '<h3 class="widget-title">',
		'after_title' => '</h3>',
	) );

	populate_flickr_taxonomy(FLICKR_NSID);
	populate_block_size_taxonomy();
	remove_filter( 'body_class', 'twentyeleven_body_classes' );
}


function populate_flickr_taxonomy($flickr_nsid) {
	$url = 'http://api.flickr.com/services/rest/?method=flickr.tags.getListUser&api_key='.FLICKR_API_KEY.'&user_id='.$flickr_nsid.'&format=php_serial&nojsoncallback=1';
	$ch = curl_init($url);
	curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
	$output = unserialize(curl_exec($ch));
	$tags = $output['who']['tags']['tag'];
	foreach ($tags as $tag) {
		wp_insert_term($tag['_content'], 'flickr_tags');
	}	
}

function populate_block_size_taxonomy() {
	$sizes = array('Featured', 'Vertical', 'Horizontal', 'Small');
	foreach ($sizes as $size) {
		wp_insert_term($size, 'block_sizes');
	}	
}

function add_format_categories($post_id) {
	while (wp_is_post_revision($post_id)) {
		$post_id = wp_is_post_revision($post_id);
	}
	$mime_type_to_category = array(‘image’ => ‘Image’, ‘video’ => ‘Video’, ‘pdf’ => ‘PDF’, ‘audio’ => ‘Audio’);
	$post_contains = array('Text');
	$children = get_children(array(‘post_type’ => ‘attachment’, ‘post_parent’ => $post_id));
	foreach ($children as $child_id => $child) {
		error_log('Child: '.print_r($child, TRUE));
		$mime_type = get_post_mime_type($child_id);
		if (array_key_exists($mime_type, $mime_type_to_category)) {
			$post_contains[] = $mime_type_to_category[$mime_type];
		}
	}
	$post_contains = array_unique($post_contains);
	wp_set_object_terms($post_id, $post_contains, ‘post_contains’, false);
}

function create_calendar_iframe($attributes) {
	extract( shortcode_atts( array(
		'height' => '300',
		'width' => '250',
		'src' => '',
		'bgcolor' => 'FFFFFF',
		'color' => 'A32929',
		'mode' => 'AGENDA'
	), $attributes ) );
	if (!empty($src)) {
		$src = 'src='.urlencode($src).'&amp;';
	}

	$src = '<iframe src="https://www.google.com/calendar/embed?showTitle=0&amp;showNav=0&amp;showDate=0&amp;showPrint=0&amp;showTabs=0&amp;showCalendars=0&amp;showTz=0&amp;height='.$height.'&amp;wkst=1&amp;mode='.strtoupper($mode).'&amp;bgcolor=%23'.$bgcolor.'&amp;'.$src.'color=%23'.$color.'&amp;ctz=America%2FNew_York" style=" border-width:0 " width="'.$width.'" height="'.$height.'" frameborder="0" scrolling="no"></iframe>';

	return $src;
}

function create_sponsor_block($attributes) {
	if (!empty($src)) {
		$src = 'src='.urlencode($src).'&amp;';
	}

	$src = '<div class="sponsors"><a href="http://cyber.law.harvard.edu/" target="_blank"><img src="'.get_stylesheet_directory_uri().'/images/Berkman-140px.png" alt="Berkman Center" /></a><br /><a href="http://www.fir.unisg.ch/" target="_blank"><img src="'.get_stylesheet_directory_uri().'/images/StGallenFIRHSG.gif" alt="St Gallen" /></a></div>';

	return $src;
}

function create_social_block($attributes) {
	if (!empty($src)) {
		$src = 'src='.urlencode($src).'&amp;';
	}

	$src = '<div class="social-links"><a href=""><img src="'.get_stylesheet_directory_uri().'/images/social-icons/twitter-64x64.png" alt="Twitter" /></a><a href=""><img src="'.get_stylesheet_directory_uri().'/images/social-icons/facebook-64x64.png" alt="Facebook" /></a></div>';

	return $src;
}

function create_flickr_gallery($attributes) {
	extract( shortcode_atts( array(
		'flickr_nsid' => FLICKR_NSID,
		'class' => 'flickr-gallery',
		'image_class' => 'flickr-image',
		'id' => false,
		'size' => 's',
		'tags' => '',
		'results' => 24
	), $attributes ) );

	if ($id) {
		$id = 'id="'.$id.'"';
	}
	if (!empty($tags)) {
		$tags = 'tags='.$tags.'&';
	}
	if (!empty($class)) {
		$class = 'class="'.$class.'"';
	}
	$html = '<div '.$id.' '.$class.'>';
	$url = 'http://api.flickr.com/services/rest/?method=flickr.photos.search&per_page='.$results.'&api_key='.FLICKR_API_KEY.'&user_id='.$flickr_nsid.'&'.$tags.'format=php_serial&nojsoncallback=1';
	$ch = curl_init($url);
	curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
	$output = unserialize(curl_exec($ch));

	foreach ($output['photos']['photo'] as $photo) {
		$html .= '<img class="'.$image_class.'" alt="' . $photo['title'] . '" src="http://farm' . $photo['farm'] . '.static.flickr.com/' . $photo['server'] . '/' . $photo['id'] . '_' . $photo['secret'] . '_'.$size.'.jpg" />';
	}
	return $html.'</div>';
}

function alter_body_classes( $classes ) {

	if ( ! is_multi_author() ) {
		$classes[] = 'single-author';
	}

	if ( is_singular() && ! is_home() && ! is_front_page() && ! is_page_template( 'showcase.php' ) && ! is_page_template( 'sidebar-page.php' ) )
		$classes[] = 'singular';

	return $classes;
}

add_shortcode( 'google-calendar', 'create_calendar_iframe' );
add_shortcode( 'flickr-gallery', 'create_flickr_gallery' );
add_shortcode( 'sponsors', 'create_sponsor_block' );
add_shortcode( 'social-links', 'create_social_block' );
add_filter('widget_text', 'do_shortcode');
add_action('post_updated', 'add_format_categories');
add_action('init', 'on_init');
add_filter('body_class', 'alter_body_classes');
