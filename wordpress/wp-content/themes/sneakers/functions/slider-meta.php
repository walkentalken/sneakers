<?php

// Return a custom field value
function get_custom_field_slider( $value ) {
	global $post;

    $custom_field = get_post_meta( $post->ID, $value, true );
    if ( !empty( $custom_field ) )
	    return is_array( $custom_field ) ? stripslashes_deep( $custom_field ) : stripslashes( wp_kses_decode_entities( $custom_field ) );

    return false;
}

// Register the Metabox
function slider_custom_meta_box() {

	$types = array('films','series');
	foreach ($types as $type) {
		add_meta_box( 'slider-meta-bx', __( 'Slider ID', 'amc-global' ), 'slider_meta_box_output', $type, 'normal', 'default' );
	}
}
add_action( 'add_meta_boxes', 'slider_custom_meta_box' );

// Output the Metabox
function slider_meta_box_output( $post ) {
	// create a nonce field
	wp_nonce_field( 'sliders_meta_box_nonce', 'slider_meta_box_nonce' ); 

	$currentSliderCode = get_custom_field_slider( 'slider_code' );

	?>

	<p><strong>Enter the Post ID of your slider</strong></p>
	
	<p>
		<label for="slider_code"><?php _e( 'Slider Post ID', 'amc-global' ); ?>:</label>
		<input type="text" name="slider_code" id="slider_code" value="<?php echo $currentSliderCode ?>" size="50" />
    </p>

	<?php

	if( !empty($currentSliderCode) ){

		$args = array(
			'post_type'	=> 'sliders',
			'post_status' 	=> 'publish',
			'p' => $currentSliderID,
			'posts_per_page' => 1
		);

		$the_query = new WP_Query( $args );

		if ( $the_query->have_posts() ) :
			
			while ( $the_query->have_posts() ) : $the_query->the_post();

			echo '<div class="sliderLabels">';
			_e( '<b>Current slider:</b>&nbsp;&nbsp;', 'amc-global' );
			the_title();
			echo '<br/>';
			_e( '<b>Published on:</b>&nbsp;&nbsp;', 'amc-global' );
			the_date();
			echo '<br/>';
			_e( '<b>Modified on:</b>&nbsp;&nbsp;', 'amc-global' );
			the_modified_date();
			echo '</div>';

			endwhile;
			wp_reset_postdata();
		endif;
		wp_reset_query();

	}



}

// Save the Metabox values
function slider_meta_box_save( $post_id ) {
	// Stop the script when doing autosave
	if( defined( 'DOING_AUTOSAVE' ) && DOING_AUTOSAVE ) return;

	// Verify the nonce. If insn't there, stop the script
	if( !isset( $_POST['slider_meta_box_nonce'] ) || !wp_verify_nonce( $_POST['slider_meta_box_nonce'], 'sliders_meta_box_nonce' ) ) return;

	// Stop the script if the user does not have edit permissions
	if( !current_user_can( 'edit_post' ) ) return;

    // Save the slider_code
	if( isset( $_POST['slider_code'] ) )
		update_post_meta( $post_id, 'slider_code', esc_attr( $_POST['slider_code'] ) );

}
add_action( 'save_post', 'slider_meta_box_save' );

