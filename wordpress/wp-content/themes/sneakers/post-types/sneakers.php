<?php

function sneakers_init() {
	
	$show_labels = array(
		'name' => __( 'Sneakers','amc-global' ),
		'singular_name' => __( 'Sneaker','amc-global' ),
		'add_new' => __( 'Add New','amc-global' ),
		'add_new_item' => __( 'Add New Sneakers','amc-global' ),
		'edit' => __( 'Edit','amc-global' ),
		'edit_item' => __( 'Edit Sneakers','amc-global' ),
		'new_item' => __( 'New Sneakers','amc-global' ),
		'view' => __( 'View Sneakers','amc-global' ),
		'view_item' => __( 'View Sneaker','amc-global' ),
		'search_items' => __( 'Search sneakers','amc-global' ),
		'not_found' => __( 'No sneakers found','amc-global' ),
		'not_found_in_trash' => __( 'No sneakers found in Trash','amc-global' ),
		'parent' => __( 'Parent Sneakers','amc-global' ),
	);
	
	$args = array(
    	'labels' => $show_labels,
		'public' => true,
		'show_ui' => true,
		'capability_type' => 'page',
		'hierarchical' => false,
		'menu_position' => null,
		'taxonomies' => array('post_tags'),
		'rewrite' => 'sneakers',
		'supports' => array('title', 'editor', 'thumbnail'),
		'has_archive' => true
	);

	register_post_type( 'sneakers' , $args );

}

add_action( 'init', 'sneakers_init' );