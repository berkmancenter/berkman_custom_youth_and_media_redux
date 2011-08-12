<?php
$flickr_tags = get_the_terms( $post->ID, 'flickr_tags' ); 
$flickr_attr = array(
    'id' => 'viz1',
    'ul_class' => 'jcarousel-skin-tango',
    'ul_id' => 'mycarousel',
    'li_class' => '',
    'image_class' => '',
    'size' => 'm',
    'rows' => 1
);
if ( ! empty( $flickr_tags ) ): 
    $tags = array();
    foreach ($flickr_tags as $tag) {
        $tags[] = $tag->name;
    }
    $flickr_attr['tags'] = implode(',', $tags);
endif; 
echo create_flickr_gallery($flickr_attr);
?>

