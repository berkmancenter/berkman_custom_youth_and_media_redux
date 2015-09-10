<?php
define('FLICKR_NSID', '65827107@N08');
define('FLICKR_API_KEY', '5687af375108fc3d342075b21af266e7');

function on_init() {
	register_taxonomy(
		'post_formats',
		array('post', 'page'),
		array(
			'label' => __('Post Formats'),
			'labels' => array(
				'name' => _x('Post Formats', 'taxonomy general name'),
				'singular_name' => _x('Format', 'taxonomy singular name')
			),
			'hierarchical' => true,
			'public' => true
		)
	);

	register_taxonomy(
		'post_contains',
		array('post', 'page'),
		array(
			'label' => __('Post Contains'),
			'labels' => array(
				'name' => _x('Post Contains', 'taxonomy general name'),
				'singular_name' => _x('Type', 'taxonomy singular name')
			),
			'hierarchical' => true,
			'public' => true
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

    add_image_size('featured', 420, 420, true);
    add_image_size('vertical', 200, 420, true);
    add_image_size('horizontal', 420, 200, true);
    add_image_size('small', 200, 200, true);

    add_filter( 'the_content', 'add_page_children', 9 );
	remove_filter( 'body_class', 'twentyeleven_body_classes' );
	remove_filter( 'the_excerpt', 'sociable_display_hook' );
}

function on_admin_init() {
	populate_flickr_taxonomy(FLICKR_NSID);
	populate_block_size_taxonomy();
	populate_post_formats_taxonomy();
	populate_post_contains_taxonomy();
}

function add_custom_post_types() {

    register_post_type('person', array(
        'label' => 'Person',
        'labels' => array(
            'name' => 'Person',
            'singular_name' => 'Person',
            'add_new_item' => 'Add Person',
            'edit_item' => 'Edit Person',
            'new_item' => 'New Person',
            'view_item' => 'View Person',
            'search_items' => 'Search Person'
        ),
        'public' => true,
        'supports' => array('title', 'editor', 'page-attributes', 'custom-fields')
    ));
}

function populate_flickr_taxonomy($flickr_nsid) {
	$url = 'https://api.flickr.com/services/rest/?method=flickr.tags.getListUser&api_key='.FLICKR_API_KEY.'&user_id='.urlencode($flickr_nsid).'&format=php_serial&nojsoncallback=1';
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

function populate_post_formats_taxonomy() {
	$formats = array('Text', 'Image', 'Video', 'Document', 'Audio');
	foreach ($formats as $format) {
		if ( ! term_exists($format, 'post_formats') ) {
			wp_insert_term($format, 'post_formats');
		}
	}
}

function populate_post_contains_taxonomy() {
	$types = array('Interview', 'Workshop', 'Paper');
	foreach ($types as $type) {
		if ( ! term_exists( $type, 'post_contains' ) ) {
			wp_insert_term($type, 'post_contains');
		}
	}
}

function create_calendar_iframe($attributes) {
	extract( shortcode_atts( array(
		'height' => '300',
		'width' => '250',
		'src' => 'en.usa#holiday@group.v.calendar.google.com',
		'bgcolor' => 'FFFFFF',
		'color' => 'A32929',
		'mode' => 'AGENDA'
	), $attributes ) );
	if (!empty($src)) {
		$src = 'src='.urlencode($src).'&amp;';
	}

    $src = '<iframe class="google-calendar" src="'.get_stylesheet_directory_uri().'/restylegc/restylegc.php?showTitle=0&amp;showDate=0&amp;showPrint=0&amp;showTabs=0&amp;showCalendars=0&amp;showTz=0&amp;height='.urlencode($height).'&amp;wkst=1&amp;mode='.urlencode(strtoupper($mode)).'&amp;bgcolor=%23'.urlencode($bgcolor).'&amp;'.$src.'color=%23'.urlencode($color).'&amp;ctz=America%2FNew_York" style=" border-width:0; max-width: 100%; " height="'.esc_attr($height).'" frameborder="0" scrolling="no"></iframe>';

	return $src;
}

function create_flickr_gallery($attributes) {
	extract( shortcode_atts( array(
		'flickr_nsid' => FLICKR_NSID,
		'ul_class' => 'flickr-gallery',
		'ul_id' => '',
		'li_class' => 'flickr-li',
		'image_class' => 'flickr-image',
		'id' => 'flickr',
		'size' => 's',
		'rows' => 2,
		'tags' => '',
		'results' => 24
	), $attributes ) );

	if ($id) {
		$id = 'id="'.esc_attr($id).'"';
	}
	if ($ul_id) {
		$ul_id = 'id="'.esc_attr($ul_id).'"';
	}
	if (!empty($tags)) {
		$tags = 'tags='.urlencode($tags).'&';
	}
	if (!empty($ul_class)) {
		$ul_class = 'class="'.esc_attr($ul_class).'"';
	}
	$html = '<div '.$id.'><ul '.$ul_id.' '.$ul_class.'>';
	$url = 'https://api.flickr.com/services/rest/?method=flickr.photos.search&per_page='.urlencode($results).'&api_key='.urlencode(FLICKR_API_KEY).'&user_id='.urlencode($flickr_nsid).'&'.$tags.'format=php_serial&nojsoncallback=1';
	$ch = curl_init($url);
	curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
	$output = unserialize(curl_exec($ch));
	$photos = $output['photos']['photo'];
	$count = 0;

	foreach ($photos as $photo) {
		$html .= '<li class="'.esc_attr($li_class).'"><a href="http://www.flickr.com/photos/'.urlencode($flickr_nsid).'/'.urlencode($photo['id']).'" target="_blank"><img class="'.esc_attr($image_class).'" alt="' . esc_attr($photo['title']) . '" src="http://farm' . urlencode($photo['farm']) . '.static.flickr.com/' . urlencode($photo['server']) . '/' . urlencode($photo['id']) . '_' . urlencode($photo['secret']) . '_'.urlencode($size).'.jpg" /></a></li>';
		if ($rows > 1 && $count == floor(count($photos) / $rows)) {
			$html .= '</ul><ul '.$ul_class.'>';
		}
		$count++;
	}
	return $html.'</ul></div>';
}

function create_youtube_video( $attributes ) {
	extract( shortcode_atts( array(
		'id' => '79IYZVYIVLA',
        'desc' => ''
	), $attributes ) );
    $html = '
        <div class="youtube-video">
        <a class="youtube-video-link" href="http://youtu.be/'.urlencode($id).'" target="_blank">
        <img src="http://i.ytimg.com/vi/'.urlencode($id).'/hqdefault.jpg" />
        </a>';
    if (!empty($attributes['desc'])) {
        $html .= '<div class="youtube-desc">' . esc_attr($attributes['desc']) . '</div>';
    }
    $html .= '</div>';
    $html .= '<script>jQuery(function() { jQuery(".youtube-video").hover(function() { jQuery(this).find(".youtube-desc").slideDown(); }, function() { jQuery(this).find(".youtube-desc").slideUp(); }); jQuery(".youtube-desc").hide(); }); </script>';
    return $html;
}

function alter_body_classes( $classes ) {
	if ( ! is_multi_author() ) {
		$classes[] = 'single-author';
	}

	if ( is_singular() && ! is_home() && ! is_front_page() && ! is_page_template( 'showcase.php' ) && ! is_page_template( 'sidebar-page.php' ) && ! is_page() && ! is_single())
		$classes[] = 'singular';

    if ( ( is_page_template( 'front.php' ) || is_page_template( 'sidebar-page.php' ) ) && array_search('one-column', $classes) !== false )
        array_splice($classes, array_search('one-column', $classes), 1, 'right-sidebar');
	return $classes;
}

function my_excerpt_length($length) {
    return 10;
}

function add_page_children($content = '') {
    if (is_page()) {
        $children = wp_list_pages(array('title_li' => '', 'child_of' => get_the_ID(), 'echo' => 0, 'sort_column' => 'menu_order'));
        if ($children) {
            $content .= $children;
        }
    }
    return $content;
}

function create_team_member( $attributes ) {
	$args = shortcode_atts( array(
		'post_type' => 'person',
		'name' => '',
	), $attributes );

    if (!empty($attributes['name'])) {
        $the_query = new WP_Query( $args );

		$the_query->the_post();

		$image_key_values = get_post_custom_values('picture');
		$image_url = $image_key_values[0];

		$html = '<h4><strong>
				<a href="'.$image_url.'">
				<img class="alignleft size-full team_photo" src="'.$image_url.'" alt="" />
				</a>'.get_the_title().'</strong></h4>';
		$html .= '<p>'.get_the_content().'</p>';

		wp_reset_query();
	}

    return $html;
}

function get_term_name($term) { return preg_replace('/[^_a-zA-Z0-9-]/', '', $term->name); }

add_shortcode( 'google-calendar', 'create_calendar_iframe' );
add_shortcode( 'team_member', 'create_team_member' );
//add_shortcode( 'flickr-gallery', 'create_flickr_gallery' );
add_shortcode( 'video-gallery', 'create_video_gallery' );
add_shortcode( 'youtube-video', 'create_youtube_video' );
add_filter('widget_text', 'do_shortcode');
add_filter('excerpt_length', 'my_excerpt_length', 999);
add_filter('body_class', 'alter_body_classes', 999);
add_action('init', 'on_init');
add_action('admin_init', 'on_admin_init');
add_action('init', 'add_custom_post_types');
