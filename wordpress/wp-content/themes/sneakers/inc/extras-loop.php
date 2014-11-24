<?php
/**
 * Extras Loop
 *
 * @package amc-global
 */

if ( ! function_exists( 'amc_global_extras_loop' ) ) :
    /**
     * Display extras
     *
     * @param int $page_id		ID of the post to get video extras for
     * @param string $taxonomy		taxonomy to query for (ie series_tax, films_tax)
     * @param string $post_type		post_type to query for (ie post, videos)
     */
    function amc_global_extras_loop($page_id,$taxonomy,$post_type) {
	$currentTitle = get_the_title($page_id);
	$currentSeries = sanitize_title($currentTitle);

	$args = array(
		'post_type'	=> $post_type,
		'post_status' 	=> 'publish',
		'orderby' => 'date',
		'posts_per_page' => 3,
		'tax_query' => array(
			array(
				'taxonomy' => $taxonomy,
				'field' => 'slug',
				'terms' => $currentSeries,
			),
		),
	);

	$the_query = new WP_Query( $args );
	
	$current_post_type = get_post_type_object( get_post_type($page_id) );
	
	$page_type_name = ucwords( $current_post_type->labels->singular_name );
	
	$queried_post_type = get_post_type_object( $post_type );
	
	$queried_type_name = ucwords( $queried_post_type->labels->name );
	
	if ( $the_query->have_posts() ) :
	
	    echo '<h2>' . sprintf( __( '%1$s tagged with this  %2$s!', 'amc-global' ), $queried_type_name, $page_type_name ) . '</h2>';

	    echo '<div class="row-fluid">';

	    while ( $the_query->have_posts() ) : $the_query->the_post();
		
		echo '<div class="tout3">';
		    echo '<a href="'.get_permalink( $post->ID).'">';

			    if( has_post_thumbnail() ):
				the_post_thumbnail('featured-short');
			    endif;

			    the_title();

		echo '</a></div>';
		
	    endwhile;
	    
	    echo '</div>';
	    
	    wp_reset_postdata();
	    
	endif;
    }
endif;