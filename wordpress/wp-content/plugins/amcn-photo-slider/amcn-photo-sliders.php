<?php
/*
Plugin Name: AMCN Photo Sliders
Description: Adds Slideshow management functionality
Version: 0.1
Author: Brian Fegter
Author URI: http://amcnetworks.com
License: All Rights Reserved
*/

if(!class_exists('PhotoSlidersAMCN')){
	class PhotoSlidersAMCN{
		protected $post_type = 'sliders';

		public function __construct(){
			$this->add_hooks();
		}

		public function add_hooks(){
			add_action('add_meta_boxes', array($this, 'add_meta_boxes'));
			add_action('admin_enqueue_scripts', array($this, 'enqueue_scripts'));
			add_action('wp_ajax_slider_delete_attachment', array($this, 'delete_attachment'));
			add_action('wp_ajax_slider_detach_attachment', array($this, 'detach_attachment'));
			add_action('wp_ajax_slider_move_attachment', array($this, 'move_attachment'));
			add_action('wp_ajax_slider_save_attachment_meta', array($this, 'save_attachment_meta'));
			add_action('wp_ajax_slider_save_attachment_order', array($this, 'save_attachment_order'));
			add_action('wp_ajax_sliders_get_dropdown', array($this, 'generate_sliders_dropdown'));
			add_action('wp_ajax_slider_upload_image', array($this, 'upload_image'));
		}

		public function enqueue_scripts($page){
			global $post;

			if($post->post_type === $this->post_type)
				wp_enqueue_script('plupload-all');
		}

		public function add_meta_boxes(){
			add_meta_box(
		        'amcn-photo-sliders',
		        __('Slider Management', 'rainbow'),
		        array($this, 'render_slider_management'),
		        $this->post_type,
		        'normal',
		        'high'
		    );
		    add_meta_box(
		        'amcn-photo-sliders',
		        __('Manage Top Ten Images', 'rainbow'),
		        array($this, 'render_slider_management'),
		        'top-ten',
		        'normal',
		        'high'
		    );
		    add_meta_box('amcn-photo-slider-upload',
		    	__('Upload Photos', 'rainbow'),
		    	array($this, 'render_upload_meta_box'),
		    	$this->post_type,
		    	'side',
		    	'high'
		    );

		    add_meta_box('amcn-photo-slider-upload',
		    	__('Upload Photos', 'rainbow'),
		    	array($this, 'render_upload_meta_box'),
		    	'top-ten',
		    	'side',
		    	'high'
		    );
		}

		public function render_upload_meta_box(){
			$output = '
			<div id="plupload-upload-ui" class="hide-if-no-js">
				<div id="drag-drop-area">
					<div class="drag-drop-inside">
						<p class="drag-drop-info">Drop Files Here</p>
						<p>or</p>
						<p class="drag-drop-buttons"><input id="plupload-browse-button" type="button" value="Select Files" class="button" /></p>
					</div>
				</div>
			</div>';
			echo $output;
		}

		public function render_slider_management(){
			global $post;

			$args = array(
				'post_type' => 'attachment',
				'posts_per_page' => '-1',
				'post_mime_type' => 'image',
				'post_parent' => $post->ID,
				'order' => 'ASC',
				'orderby' => 'menu_order',
				'exclude' => get_post_thumbnail_id()
			);
			$images = get_posts($args);
			$loader_src = plugins_url('images/loader.gif', __FILE__);
			$output = '<p>Double Click on an image to edit the Title, Description and Links</p>';
			$output .= '<div class="photo-slider-management"><span class="slider-loader"><img src="'.$loader_src.'" alt="Slider Loader"><span class="slider-loader-text">Uploading</span></span><div class="slider-items">';

			if(count($images)){
				foreach($images as $image)
					$output .= $this->get_slider_item($image);
				$output .= '<div class="clear"></div></div></div>';
			}
			else{
				$output .= '<div class="clear"></div></div></div>';
				$output .= '<p class="slider-error">No images found. Please upload some images first.</p>';
			}

			$output .= "
				<style type='text/css'>
					.slider-item{
						border:1px solid #e2e2e2;
						padding:10px;
						margin:5px 5px 0 0;
						background:#fff;
						display:block;
						cursor:move;
						position:relative;
						float:left;
					}

					.slider-item .item-image-large img{
						max-width:300px;
						float:left;
						margin:0 15px 15px 0;
					}

					.slider-item .item-image-thumb{
						float:left;
					}
					.slider-item .item-image-thumb img{
						max-width:150px;
					}

					.slider-item a.item-close, a.item-save{
						position:absolute;
						top:10px;
						right:10px
					}

					a.item-save{
						top:45px;
					}

					.clear{
						clear:both;
						display:block;
					}
					.collapsible{
						display:none;
					}

					.item-full-width{
						clear:both;
						width:97.5%;
					}

					.item-meta{
						padding-left:100px;
						display:inline-block;
						width:42%;
					}

					.item-meta > div{
						margin-bottom:5px;
					}

					.item-meta input, .item-meta textarea{
						width:80%;
					}

					.item-meta label{
						margin-left:-100px;
						width:125px;
						display:inline-block;
					}

					label[for=item-description], label[for=item-logo]{
						position:relative;
						top:-30px;
					}

					.item-actions{
						margin-top:15px;
						display:inline-block;
					}

					.item-delete, .item-detach, .item-move, .item-save, .item-close, .item-confirm-move{
						text-decoration:none;
						padding:5px;
						display:inline-block;
						background:#e4e4e4;
						border:1px solid #e1e1e1;
						border-radius:4px;
						margin-right:10px;
					}

					.sliders-dropdown{
						width:93%;
						background:#e4e4e4;
						border:1px solid #e1e1e1;
						margin-top:15px;
						padding:10px;
					}

					.sliders-dropdown select{
						width:250px;
					}

					.slider-loader{
						position:absolute;
						top:-30px;
						left:165px;
						display:none;
					}

					.slider-loader img{
						width:50px;
						height:auto;
					}

					.slider-loader-text{
						position:relative;
						top:-4px;
						left:5px;
					}
				</style>
			";
			$nonce = wp_create_nonce('amcn-photo-sliders');
			$plupload_init = json_encode(array(
				'runtimes'            => 'html5,silverlight,flash,html4',
				'browse_button'       => 'plupload-browse-button',
				'container'           => 'plupload-upload-ui',
				'drop_element'        => 'drag-drop-area',
				'file_data_name'      => 'async-upload',
				'multiple_queues'     => true,
				'max_file_size'       => wp_max_upload_size().'b',
				'url'                 => admin_url('admin-ajax.php'),
				'flash_swf_url'       => includes_url('js/plupload/plupload.flash.swf'),
				'silverlight_xap_url' => includes_url('js/plupload/plupload.silverlight.xap'),
				'filters'             => array(array('title' => __('Allowed Files'), 'extensions' => '*')),
				'multipart'           => true,
				'urlstream_upload'    => true,
				'multipart_params'    => array(
					'post_id' => $post->ID,
					'_nonce' => $nonce,
					'action'      => 'slider_upload_image',
				)
			));
			$output .= "
				<script type='text/javascript'>

					function slider_sort_thumbs(){
						var _attachments = new Array();
						jQuery('.slider-item').each(function(){
							_attachments.push(jQuery(this).data('id'));
						});
						if(_attachments.length)
							return _attachments;
						else
							return false;
					}

					jQuery(document).ready(function($){

						//UPLOADER STARTS HERE

						var uploader = new plupload.Uploader($plupload_init);

						// checks if browser supports drag and drop upload, makes some css adjustments if necessary
						uploader.bind('Init', function(up){
						var uploaddiv = $('#plupload-upload-ui');

						if(up.features.dragdrop){
						  uploaddiv.addClass('drag-drop');
						    $('#drag-drop-area')
						      .bind('dragover.wp-uploader', function(){ uploaddiv.addClass('drag-over'); })
						      .bind('dragleave.wp-uploader, drop.wp-uploader', function(){ uploaddiv.removeClass('drag-over'); });

						}else{
						  uploaddiv.removeClass('drag-drop');
						  $('#drag-drop-area').unbind('.wp-uploader');
						}
						});

						uploader.init();

						// a file was added in the queue
						uploader.bind('FilesAdded', function(up, files){
						var hundredmb = 100 * 1024 * 1024, max = parseInt(up.settings.max_file_size, 10);

						plupload.each(files, function(file){
							if (max > hundredmb && file.size > hundredmb && up.runtime != 'html5')
								alert('File size error. Please try uploading a smaller file.')
						  	else{
						  		jQuery('.slider-loader').show();
						  	}
						});

						up.refresh();
						up.start();
						});

						// File was uploaded
						uploader.bind('FileUploaded', function(up, file, response) {
							var obj = $.parseJSON(response.response);
							if(obj.success){
								if(obj.html){
									$('.slider-error').remove();
									$('.slider-items').prepend(obj.html);
									$('.slider-loader').hide();
								}
								else
									alert('Something went wrong, please try again.');
							}
							else
								alert(obj.message);
						});

						//END UPLOADER

						var sliderNonce = '$nonce';

						$('.slider-items').sortable({
							'tolerance' : 'pointer',
							stop: function(){
								var image_order = slider_sort_thumbs();
								if(image_order){
									var data = {
										attachments : image_order,
										_nonce: sliderNonce,
										action : 'slider_save_attachment_order'
									};
									$.post(ajaxurl, data, function(response){
										if(!response.success)
											alert(response.message);
									});
								}
								else
									alert('Image order was not saved, please try again.');
							}
						});
						$('.slider-item').live('dblclick', function(e){
							var thisItem = $(this);
							thisItem.children('.item-image-thumb').fadeOut('fast', function(){
								var img = thisItem.find('.item-image-large img');
								var imgSrc = img.data('src');
								img.attr('src', imgSrc);
								thisItem.addClass('item-full-width');
								thisItem.children('.collapsible').slideDown();
							});
						});

						$('.slider-item .item-close').live('click', function(){
							var parent = $(this).parents('.slider-item');
							parent.children('.collapsible').slideUp(function(){
								parent.children('.item-image-thumb').fadeIn('fast');
								parent.removeClass('item-full-width');
							});
							return false;
						});

						$('.item-delete').live('click', function(){
							if(!confirm('Are you sure you want to delete this image?'))
								return false;

							var thisItem = $(this).parents('.slider-item');
							var data = {
								action : 'slider_delete_attachment',
								id : thisItem.data('id'),
								_nonce : sliderNonce
							}
							$.post(ajaxurl, data, function(response){
								if(response.success){
									thisItem.fadeOut('fast', function(){
										thisItem.remove();
									});
								}
								else{
									alert(response.message);

								}
							});
							return false;
						});

						$('.item-detach').live('click', function(){
							if(!confirm('Are you sure you want to detach this image from this slider?'))
								return false;

							var thisItem = $(this).parents('.slider-item');
							var data = {
								action : 'slider_detach_attachment',
								id : thisItem.data('id'),
								_nonce : sliderNonce
							}
							$.post(ajaxurl, data, function(response){
								if(response.success){
									thisItem.fadeOut('fast', function(){
										thisItem.remove();
									});
								}
								else{
									alert(response.message);

								}
							});
							return false;
						});

						$('.item-move').live('click', function(){
							var thisItem = $(this).parents('.slider-item');
							var dropdown = $('.sliders-dropdown');

							//If dropdown already exists, be nice to the server and move it within the DOM
							if(dropdown.length > 0){
								if(thisItem.find('.sliders-dropdown').length === 0){
									dropdown.detach().appendTo(thisItem.find('.item-actions'));
									$('.sliders-dropdown').val('');
									return false;
								}
							}

							var data = {
								action : 'sliders_get_dropdown',
								post_type : '$post->post_type',
								_nonce : sliderNonce
							}
							$.post(ajaxurl, data, function(response){
								if(response.html)
									thisItem.find('.item-actions').append(response.html);
							});
							return false;
						});

						$('.item-confirm-move').live('click', function(){
							var thisItem = $(this).parents('.slider-item');
							var targetSlider = $('.sliders-dropdown select').val();
							var imageID = thisItem.data('id');
							var postID = '$post->ID';

							if(!targetSlider)
								alert('Please select a target slider.');
							else{
								if(targetSlider === postID){
									alert('This image already exists in this slider. Please select another target slider.');
									return false;
								}

								var data = {
									action : 'slider_move_attachment',
									_nonce : sliderNonce,
									id : imageID,
									parent_id : targetSlider
								};
								$.post(ajaxurl, data, function(response){
									if(response.success){
										thisItem.fadeOut('fast', function(){
											thisItem.remove();
										});
									}
									else
										alert(response.message);
								});
							}
							return false;
						});

						$('.item-save').live('click', function(){
							var thisItem = $(this).parents('.slider-item');

							var data = {
								full_link : thisItem.find('.item-full-link').val(),
								logo : thisItem.find('.item-logo').val(),
								slide_title : thisItem.find('.item-title').val(),
								description : thisItem.find('.item-description').val(),
								button_one : thisItem.find('.item-link-one').val(),
								button_two : thisItem.find('.item-link-two').val(),
								button_one_text : thisItem.find('.item-link-one-text').val(),
								button_two_text : thisItem.find('.item-link-two-text').val(),
								action : 'slider_save_attachment_meta',
								id : thisItem.data('id'),
								_nonce : sliderNonce
							}
							$.post(ajaxurl, data, function(response){
								if(response.message)
									alert(response.message);
							});
							return false;
						});


					});
				</script>
			";
			echo $output;
		}

		public function delete_attachment(){
			$this->verify_nonce();

			$id = esc_attr($_POST['id']);

			if($id)
				$deleted = wp_delete_attachment($id, true);

			if($deleted)
				$this->send_response(array('success' => true, 'message' => 'Image successfully deleted!'));
			else
				$this->send_response(array('success' => false, 'message' => 'Something went wrong. Please try again.'));
		}

		public function detach_attachment(){
			$this->verify_nonce();

			$id = esc_attr($_POST['id']);

			if($id)
				$detached = wp_update_post(array('ID' => $id, 'post_parent' => ''));

			if($detached)
				$this->send_response(array('success' => true, 'message' => 'Image successfully detached from this slider.'));
			else
				$this->send_response(array('success' => false, 'message' => 'Something went wrong, please try again'));
		}

		public function move_attachment(){
			$this->verify_nonce();

			$id = esc_attr($_POST['id']);
			$parent_id = esc_attr($_POST['parent_id']);

			if($id && $parent_id);
				$moved = wp_update_post(array('ID' => $id, 'post_parent' => $parent_id));

			if($moved)
				$this->send_response(array('success' => true, 'message' => "Image successfully moved to slider #$parent_id."));
			else
				$this->send_response(array('success' => false, 'message' => 'Something went wrong, please try again'));
		}

		public function save_attachment_meta(){
			$this->verify_nonce();

			$id = esc_attr($_POST['id']);

			$args['ID']           = $id;
			$args['post_title']   = esc_attr($_POST['title']);
			$args['post_excerpt'] = esc_attr($_POST['title']);
			$args['post_content'] = esc_attr($_POST['description']);
			$updated =  wp_update_post($args);

			if($updated){
				# Set Title
				$slide_title = esc_attr($_POST['slide_title']);
				$slide_titl = update_post_meta($id, '_slide_title', $slide_title);

				# Set Buttons
				$button_one = esc_attr($_POST['button_one']);
				$button_two = esc_attr($_POST['button_two']);
				$link_one = update_post_meta($id, '_link_one', $button_one);
				$link_two = update_post_meta($id, '_link_two', $button_two);

				$button_one_text = esc_attr($_POST['button_one_text']);
				$button_two_text = esc_attr($_POST['button_two_text']);
				$link_one_text = update_post_meta($id, '_link_one_text', $button_one_text);
				$link_two_text = update_post_meta($id, '_link_two_text', $button_two_text);

				# Set Logo Image
				$logo = esc_attr($_POST['logo']);
				$logo_meta = update_post_meta($id, '_logo', $logo);

				#Set Image Alt
				$full_link_text     = esc_attr($_POST['full_link']);
				$image_full_link = update_post_meta($id, '_full_link', $full_link_text);
				$image_alt = update_post_meta($id, '_wp_attachment_image_alt', $alt_text);

				#Send success message
				$this->send_response(array('success' => true, 'message' => 'Image meta was saved successfully.'));
			}
			else
				$this->send_response(array('success' => false, 'message' => 'Something went wrong, please try again.'));
		}

		public function save_attachment_order(){
			$this->verify_nonce();

			$attachments = $_POST['attachments'];
			if(is_array($attachments)){
				foreach($attachments as $attachment){
					$i++;
					wp_update_post(array('ID' => esc_attr($attachment), 'menu_order' => $i));
				}
				$this->send_response(array('success' => true, 'message' => 'Image order successfully updated.'));
			}
			else
				$this->send_response(array('success' => false, 'message' => 'Something went wrong, please try again.'));
		}

		protected function verify_nonce(){
			if(!wp_verify_nonce(esc_attr($_POST['_nonce']), 'amcn-photo-sliders'))
				$this->send_response(array('success' => false, 'message' => 'Authorization key not valid. Please refresh the page and try again.'));
		}

		protected function send_response(Array $message, $no_cache = true){
			if($no_cache){
				header('Cache-Control: no-cache, must-revalidate');
				header('Expires: Mon, 26 Jul 1997 05:00:00 GMT');
			}
			header('Content-type: application/json');
			echo json_encode($message);
			exit;
		}

		public function generate_sliders_dropdown(){
			$this->verify_nonce();

			$post_type = esc_attr($_POST['post_type']);
			$post_type = $post_type ? $post_type : $this->post_type;

			$sliders = get_posts(array(
				'post_type' => $post_type,
				'posts_per_page' => '-1',
				'post_status' => 'publish'
			));

			$title = $post_type == $this->post_type ? 'Slider' : 'Top Ten List';
			$options = "<option value=''>Select a $title</option>";
			foreach($sliders as $slider)
				$options .= "<option value='$slider->ID'>$slider->post_title</option>";

			$html = "
				<div class='sliders-dropdown'>
					<select>
						$options
					</select>
					<a href='#' class='item-confirm-move'>Confirm Move</a>
				</div>
			";
			$this->send_response(array('success' => true, 'html' => $html), false);
		}

		protected function get_slider_item($image){
			if(!is_object($image))
				$image = get_post($image);

			$image_focal = get_post_meta($image->ID, 'focal', true);
			$image_full_link = get_post_meta($image->ID, '_full_link', true);
			$image_link_one_text = get_post_meta($image->ID, '_link_one_text', true);
			$image_link_one = get_post_meta($image->ID, '_link_one', true);
			$image_link_two_text = get_post_meta($image->ID, '_link_two_text', true);
			$image_link_two = get_post_meta($image->ID, '_link_two', true);
			$slide_title = get_post_meta($image->ID, '_slide_title', true);
			$image_title = $image->post_title;
			$image_logo = get_post_meta($image->ID, '_logo', true);;
			$image_description = $image->post_content;
			$image_src = wp_get_attachment_image_src($image->ID, array(100, 100));
			$image_tag_thumb = "<img src='{$image_src[0]}' alt='Slider Image Thumb'>";
			$image_src = wp_get_attachment_image_src($image->ID, 'large');
			$image_tag_large = "<img src='' data-src='{$image_src[0]}' alt='Slider Image Large'>";

			$output .= "
				<div class='slider-item' data-id='$image->ID'>
					<span class='item-image-thumb'>$image_tag_thumb</span>
					<div class='collapsible'>
						<a href='#' class='item-save'>Save</a>
						<a href='#' class='item-close'>Close</a>
						<span class='item-image-large'>$image_tag_large</span>
						<div class='item-meta'>
							<div>
								<label for='item-title'>Title</label>
								<input type='text' class='item-title' value='$slide_title'>
							</div>
							<div>
								<label for='image-full-link'>Full Slide Link URL</label>
								<input type='text' class='item-full-link' value='$image_full_link'>
							</div>
							<div>
								<label for='item-logo'>Logo Image URL</label>
								<textarea class='item-logo'>$image_logo</textarea>
							</div>
							<div>
								<label for='item-description'>Description</label>
								<textarea class='item-description'>$image_description</textarea>
							</div>
							<div>
								<label for='item-link-one-text'>Button One Text</label>
								<input type='text' class='item-link-one-text' value='$image_link_one_text'>
							</div>
							<div>
								<label for='item-link-one'>Button One Link</label>
								<input type='text' class='item-link-one' value='$image_link_one'>
							</div>
							<div>
								<label for='item-link-two-text'>Button Two Text</label>
								<input type='text' class='item-link-two-text' value='$image_link_two_text'>
							</div>
							<div>
								<label for='item-link-two'>Button Two Link</label>
								<input type='text' class='item-link-two' value='$image_link_two'>
							</div>
						</div>
						<div class='item-actions'>
							<a href='#' class='item-detach'>Detach From Slider</a>
							<a href='#' class='item-move'>Move To Another Slider</a>
							<a href='#' class='item-delete'>Delete From Server</a>
						</div>
					</div>
					<div class='clear'></div>
				</div>
			";
			return $output;
		}

		public function upload_image(){

			$this->verify_nonce();

			require_once( ABSPATH . 'wp-admin/includes/file.php' );

			$parent_post = esc_attr($_POST['post_id']);

			$attachment = wp_handle_upload($_FILES['async-upload'], array('test_form' => true, 'action' => 'slider_upload_image'));
			$wp_upload_dir = wp_upload_dir();
			$new_file_url = preg_split('/uploads/', $attachment['url']);
			$new_file_url = $wp_upload_dir['baseurl'].$new_file_path[1];
			$new_file_path = $attachment['file'];

			$filename = basename($attachment['file']);

			#Generate Crops
			foreach( get_intermediate_image_sizes() as $s ) {
				$sizes[$s] = array( 'width' => '', 'height' => '', 'crop' => true );
				$sizes[$s]['width'] = get_option( "{$s}_size_w" ); // For default sizes set in options
				$sizes[$s]['height'] = get_option( "{$s}_size_h" ); // For default sizes set in options
				$sizes[$s]['crop'] = get_option( "{$s}_crop" ); // For default sizes set in options
			}

			$sizes = apply_filters( 'intermediate_image_sizes_advanced', $sizes );
			foreach( $sizes as $size => $size_data ) {
				$resized = image_make_intermediate_size( $new_file_path, $size_data['width'], $size_data['height'], $size_data['crop'] );
				if ( $resized )
					$metadata['sizes'][$size] = $resized;
			}
			#End Generate Crops

			$args= array(
				'guid' => $new_file_url,
				'post_mime_type' => $attachment['type'],
				'post_title' => preg_replace('/\.[^.]+$/', '', $filename),
				'post_content' => '',
				'post_status' => 'inherit'
			);
			$attach_id = wp_insert_attachment($args, $new_file_path, 37);

			if($attach_id){
				require_once(ABSPATH . 'wp-admin/includes/image.php');
				$attach_data = wp_generate_attachment_metadata( $attach_id, $new_file_path);
				wp_update_attachment_metadata( $attach_id, $attach_data );
				wp_update_post(array('ID' => $attach_id, 'post_parent' => $parent_post));
				$html = $this->get_slider_item($attach_id);
				$this->send_response(array('success' => true, 'message' => 'Image was successfully uploaded.', 'html' => $html));
			}
			else
				$this->send_response(array('success' => false, 'message' => 'Image was not uploaded. Please try again.'));
		}

	}
	add_action('init', 'amcn_init_sliders');
	function amcn_init_sliders(){
		$amcn_photo_slider = new PhotoSlidersAMCN;
	}
}
