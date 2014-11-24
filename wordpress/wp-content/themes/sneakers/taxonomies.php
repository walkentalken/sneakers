<?php

add_action( 'init', 'create_sneakers_to_boots_taxonomies', 0 );

function create_sneakers_to_boots_taxonomies() {

	// Brands
	$labels = array(
		'name'              => _x( 'Brands', 'taxonomy general name' ),
		'singular_name'     => _x( 'Brand', 'taxonomy singular name' ),
		'search_items'      => __( 'Search Brands' ),
		'all_items'         => __( 'All Brands' ),
		'parent_item'       => __( 'Parent Brand' ),
		'parent_item_colon' => __( 'Parent Brand:' ),
		'edit_item'         => __( 'Edit Brand' ),
		'update_item'       => __( 'Update Brand' ),
		'add_new_item'      => __( 'Add New Brand' ),
		'new_item_name'     => __( 'New Brand Name' ),
		'menu_name'         => __( 'Brand' ),
	);

	$args = array(
		'hierarchical'      => true,
		'labels'            => $labels,
		'show_ui'           => true,
		'show_admin_column' => true,
		'query_var'         => true,
		'rewrite'           => array( 'slug' => 'brand' )
	);

	$post_types = array('sneakers');

	register_taxonomy( 'brand', $post_types, $args );



	// Shoe Type
	$labels = array(
		'name'              => _x( 'Shoe Types', 'taxonomy general name' ),
		'singular_name'     => _x( 'Shoe Type', 'taxonomy singular name' ),
		'search_items'      => __( 'Search Shoe Types' ),
		'all_items'         => __( 'All Shoe Types' ),
		'parent_item'       => __( 'Parent Shoe Type' ),
		'parent_item_colon' => __( 'Parent Shoe Type:' ),
		'edit_item'         => __( 'Edit Shoe Type' ),
		'update_item'       => __( 'Update Shoe Type' ),
		'add_new_item'      => __( 'Add New Shoe Type' ),
		'new_item_name'     => __( 'New Shoe Type Name' ),
		'menu_name'         => __( 'Shoe Type' ),
	);

	$args = array(
		'hierarchical'      => true,
		'labels'            => $labels,
		'show_ui'           => true,
		'show_admin_column' => true,
		'query_var'         => true,
		'rewrite'           => array( 'slug' => 'type_tax' )
	);

	$post_types = array('sneakers');

	register_taxonomy( 'type_tax', $post_types, $args );


	// Price
	$labels = array(
		'name'              => _x( 'Prices', 'taxonomy general name' ),
		'singular_name'     => _x( 'Price', 'taxonomy singular name' ),
		'search_items'      => __( 'Search Prices' ),
		'all_items'         => __( 'All Prices' ),
		'parent_item'       => __( 'Parent Price' ),
		'parent_item_colon' => __( 'Parent Price:' ),
		'edit_item'         => __( 'Edit Price' ),
		'update_item'       => __( 'Update Price' ),
		'add_new_item'      => __( 'Add New Price' ),
		'new_item_name'     => __( 'New Price Name' ),
		'menu_name'         => __( 'Price' ),
	);

	$capabilities = array(
        'manage_terms' 	=> 'nobody',
        'edit_terms' 	=> 'nobody',
        'delete_terms' 	=> 'nobody'
    );

	$args = array(
		'hierarchical'      => true,
		'labels'            => $labels,
		'show_ui'           => true,
		'show_admin_column' => true,
		'query_var'         => true,
		'rewrite'           => array( 'slug' => 'price_tax' ),
		'capabilities' 		=> $capabilities
	);

	$post_types = array('sneakers');

	register_taxonomy( 'price_tax', $post_types, $args );


	$priceList = array(
		'$0 - $25',
		'$25 - $35',
		'$35 - $45',
		'$45 - $55',
		'$55 - $65',
		'$65 - $75',
		'$75 - $85',
		'$85 - $95',
		'$95 - $110',
		'$110 - $125',
		'$125+'
	);

	foreach ($priceList as $price) {
		wp_insert_term( $price, 'price_tax' );
	}


	// Demographic
	$labels = array(
		'name'              => _x( 'Demographics', 'taxonomy general name' ),
		'singular_name'     => _x( 'Demographic', 'taxonomy singular name' ),
		'search_items'      => __( 'Search Demographics' ),
		'all_items'         => __( 'All Demographics' ),
		'parent_item'       => __( 'Parent Demographic' ),
		'parent_item_colon' => __( 'Parent Demographic:' ),
		'edit_item'         => __( 'Edit Demographic' ),
		'update_item'       => __( 'Update Demographic' ),
		'add_new_item'      => __( 'Add New Demographic' ),
		'new_item_name'     => __( 'New Demographic Name' ),
		'menu_name'         => __( 'Demographic' ),
	);

	$capabilities = array(
        'manage_terms' 	=> 'nobody',
        'edit_terms' 	=> 'nobody',
        'delete_terms' 	=> 'nobody'
    );

	$args = array(
		'hierarchical'      => true,
		'labels'            => $labels,
		'show_ui'           => true,
		'sort'				=> true,
		'show_admin_column' => true,
		'query_var'         => true,
		'rewrite'           => array( 'slug' => 'demo_tax' ),
		'capabilities' 		=> $capabilities
	);

	$post_types = array('sneakers');

	register_taxonomy( 'demo_tax', $post_types, $args );


	$demoList = array(
		'Men\'s',
		'Woman\'s',
		'Children\'s'
	);

	foreach ($demoList as $demo) {
		wp_insert_term( $demo, 'demo_tax' );
	}

	


	// Featured On Homepage
	$labels = array(
		'name'              => _x( 'Featured', 'taxonomy general name' ),
		'singular_name'     => _x( 'Featured', 'taxonomy singular name' ),
		'search_items'      => __( 'Search Featured' ),
		'all_items'         => __( 'All Featured' ),
		'parent_item'       => __( 'Parent Featured' ),
		'parent_item_colon' => __( 'Parent Featured:' ),
		'edit_item'         => __( 'Edit Featured' ),
		'update_item'       => __( 'Update Featured' ),
		'add_new_item'      => __( 'Featuredd New Featured' ),
		'new_item_name'     => __( 'New Featured Name' ),
		'menu_name'         => __( 'Featured' ),
	);

	$capabilities = array(
        'manage_terms' 	=> 'nobody',
        'edit_terms' 	=> 'nobody',
        'delete_terms' 	=> 'nobody'
    );

	$args = array(
		'hierarchical'      => true,
		'labels'            => $labels,
		'show_ui'           => true,
		'show_admin_column' => true,
		'query_var'         => true,
		'rewrite'           => array( 'slug' => 'featured' ),
		'capabilities' 		=> $capabilities
	);

	$post_types = array('post');

	register_taxonomy( 'featured', $post_types, $args );

	$featuredArgs = array(
		'description'=> 'Should this post be featured on the homepage?',
    	'slug' => 'featured'
	);
	wp_insert_term('Featured On Homepage', 'featured', $featuredArgs );




}
?>