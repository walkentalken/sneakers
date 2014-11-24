<?php

function sliders_init() {
	
	$show_labels = array(
		'name' => _x( 'Sliders','Image Slider','amc-global' ),
		'singular_name' => _x( 'Slider','Image Slider','amc-global' ),
		'add_new' => _x( 'Add New','Image Slider','amc-global' ),
		'add_new_item' => _x( 'Add New Slider','Image Slider','amc-global' ),
		'edit' => _x( 'Edit','Image Slider','amc-global' ),
		'edit_item' => _x( 'Edit Slider','Image Slider','amc-global' ),
		'new_item' => _x( 'New Slider','Image Slider','amc-global' ),
		'view' => _x( 'View Sliders','Image Slider','amc-global' ),
		'view_item' => _x( 'View Slider','Image Slider','amc-global' ),
		'search_items' => _x( 'Search sliders','Image Slider','amc-global' ),
		'not_found' => _x( 'No sliders found','Image Slider','amc-global' ),
		'not_found_in_trash' => _x( 'No sliders found in Trash','Image Slider','amc-global' ),
		'parent' => _x( 'Parent Slider','Image Slider','amc-global' ),
	);
	
	$args = array(
    	'labels' => $show_labels,
		'public' => false,
		'show_ui' => true,
		'capability_type' => 'page',
		'hierarchical' => false,
		'menu_position' => null,
		'taxonomies' => array('post_tags'),
		'rewrite' => false,
		'supports' => array('title', 'editor', 'thumbnail'),
		'has_archive' => false
	);

	register_post_type( 'sliders' , $args );

}

add_action( 'init', 'sliders_init' );