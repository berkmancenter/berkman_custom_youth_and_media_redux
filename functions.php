<?php 
define('FLICKR_NSID', '33198938@N00');
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

	populate_flickr_taxonomy(FLICKR_NSID);
	populate_block_size_taxonomy();
	populate_post_formats_taxonomy();
	populate_post_contains_taxonomy();
	remove_filter( 'body_class', 'twentyeleven_body_classes' );
	remove_filter( 'the_excerpt', 'sociable_display_hook' );
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

	$src = '<iframe class="google-calendar" src="'.get_stylesheet_directory_uri().'/restylegc/restylegc.php?showTitle=0&amp;showNav=0&amp;showDate=0&amp;showPrint=0&amp;showTabs=0&amp;showCalendars=0&amp;showTz=0&amp;height='.$height.'&amp;wkst=1&amp;mode='.strtoupper($mode).'&amp;bgcolor=%23'.$bgcolor.'&amp;'.$src.'color=%23'.$color.'&amp;ctz=America%2FNew_York" style=" border-width:0 " height="'.$height.'" frameborder="0" scrolling="no"></iframe>';

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

	$src = '<div class="social-links"><a href=""><img src="'.get_stylesheet_directory_uri().'/images/social-icons/twitter-64.png" alt="Twitter" /></a><a href=""><img src="'.get_stylesheet_directory_uri().'/images/social-icons/facebook-64.png" alt="Facebook" /></a></div>';

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
		$id = 'id="'.$id.'"';
	}
	if ($ul_id) {
		$ul_id = 'id="'.$ul_id.'"';
	}
	if (!empty($tags)) {
		$tags = 'tags='.$tags.'&';
	}
	if (!empty($ul_class)) {
		$ul_class = 'class="'.$ul_class.'"';
	}
	wp_register_script('slideshow', get_stylesheet_directory_uri() . '/js/slideshow.js', array('jquery'));
	wp_enqueue_script('slideshow');
	$html = '<div '.$id.'><ul '.$ul_id.' '.$ul_class.'>';
	$url = 'http://api.flickr.com/services/rest/?method=flickr.photos.search&per_page='.$results.'&api_key='.FLICKR_API_KEY.'&user_id='.$flickr_nsid.'&'.$tags.'format=php_serial&nojsoncallback=1';
	$ch = curl_init($url);
	curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
	$output = unserialize(curl_exec($ch));
	$photos = $output['photos']['photo'];
	$count = 0;

	foreach ($photos as $photo) {
		$html .= '<li class="'.$li_class.'"><img class="'.$image_class.'" alt="' . $photo['title'] . '" src="http://farm' . $photo['farm'] . '.static.flickr.com/' . $photo['server'] . '/' . $photo['id'] . '_' . $photo['secret'] . '_'.$size.'.jpg" /></li>';
		if ($rows > 1 && $count == floor(count($photos) / $rows)) {
			$html .= '</ul><ul '.$ul_class.'>';
		}
		$count++;
	}
	return $html.'</ul></div>';
}

function create_video_gallery($attributes) {
	extract( shortcode_atts( array(
		'search_term' => 'berkman center',
		'results' => 24
	), $attributes ) );
	$html = '
					<!-- ++Begin Video Bar Wizard Generated Code++ -->
					  <!--
					  // Created with a Google AJAX Search Wizard
					  // http://code.google.com/apis/ajaxsearch/wizards.html
					  -->

					  <!--
					  // The Following div element will end up holding the actual videobar.
					  // You can place this anywhere on your page.
					  -->
					  <div id="videoBar-bar">
					    <span style="color:#fff;font-size:11px;margin:10px;padding:4px;">Loading...</span>
					  </div>

					  <!-- Ajax Search Api and Stylesheet
					  // Note: If you are already using the AJAX Search API, then do not include it
					  //       or its stylesheet again
					  -->
					  <script src="http://www.google.com/uds/api?file=uds.js&amp;v=1.0&amp;source=uds-vbw"
					    type="text/javascript"></script>

					  <!-- Video Bar Code and Stylesheet -->
					  <script type="text/javascript">
					    window._uds_vbw_donotrepair = true;
					  </script>
					  <script src="http://www.google.com/uds/solutions/videobar/gsvideobar.js?mode=new"
					    type="text/javascript"></script>

					  <style type="text/css">
					    .playerInnerBox_gsvb .player_gsvb {
					      width : 320px;
					      height : 260px;
					    }
					  </style>
					  <script type="text/javascript">
					    function LoadVideoBar() {

					    var videoBar;
					    var options = {
					        largeResultSet : false,
					        horizontal : false,
					        autoExecuteList : {
					          cycleTime : 0,
					          cycleMode : GSvideoBar.CYCLE_MODE_LINEAR,
					          executeList : ["'.urlencode($search_term).'"]
					        }
					      }

					    videoBar = new GSvideoBar(document.getElementById("videoBar-bar"),
					                              GSvideoBar.PLAYER_ROOT_FLOATING,
					                              options);
					    }
					    // arrange for this function to be called during body.onload
					    // event processing
					    GSearch.setOnLoadCallback(LoadVideoBar);
					  </script>
					<!-- ++End Video Bar Wizard Generated Code++ -->';
	return $html;
}

function create_youtube_video( $attributes ) {
	extract( shortcode_atts( array(
		'id' => '79IYZVYIVLA',
	), $attributes ) );
    $html = '<div class="youtube-video"><a class="youtube-video-link" href="http://youtu.be/'.$id.'" target="_blank"><img src="http://i.ytimg.com/vi/'.$id.'/hqdefault.jpg" /></a></div>';
    return $html;
}

function alter_body_classes( $classes ) {

	if ( ! is_multi_author() ) {
		$classes[] = 'single-author';
	}

	if ( is_singular() && ! is_home() && ! is_front_page() && ! is_page_template( 'showcase.php' ) && ! is_page_template( 'sidebar-page.php' ) && ! is_page() && ! is_single())
		$classes[] = 'singular';

	return $classes;
}

add_shortcode( 'google-calendar', 'create_calendar_iframe' );
add_shortcode( 'flickr-gallery', 'create_flickr_gallery' );
add_shortcode( 'sponsors', 'create_sponsor_block' );
add_shortcode( 'social-links', 'create_social_block' );
add_shortcode( 'video-gallery', 'create_video_gallery' );
add_shortcode( 'youtube-video', 'create_youtube_video' );
add_filter('widget_text', 'do_shortcode');
add_action('init', 'on_init');
add_filter('body_class', 'alter_body_classes');
