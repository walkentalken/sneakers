<?php

/* Check if a slider (post_ID) has attachements & is a slider.
 * @param (int) $postID
 * @return BOOL
 *
 * @TODO Decrease overhead, if there is a nice WP-friendly way to do so.
 */
function has_slider($postID) {

  $args = array(
    'post_type' => 'attachment',
    'posts_per_page' => '-1',
    'post_mime_type' => 'image',
    'post_parent' => $postID,
    'order' => 'ASC',
    'orderby' => 'menu_order',
    'exclude' => get_post_thumbnail_id($postID)
  );
  $attachments = get_posts($args);

  if( $postID && (get_post_type($postID) == 'sliders') && !empty($attachments) )
    return true;
  else
    return false;
}

/* Fetches slider output based on post ID
 *
 * @param (int) $postID
 * @param (BOOL) $with_sponsor
 * @return string 
 */
function get_slider($postID, $with_sponsor=false) {

  $args = array(
    'post_type' => 'attachment',
    'posts_per_page' => '-1',
    'post_mime_type' => 'image',
    'post_parent' => $postID,
    'order' => 'ASC',
    'orderby' => 'menu_order',
    'exclude' => get_post_thumbnail_id($postID)
  );
  $attachments = get_posts($args);

  $slides = build_slider($attachments);
  $sliderView = slider_view($slides, $sponsor);


  return $sliderView;

}


/* Put together the html for the slides
 * @param (array) $attachements
 * @return (string) HTML for slides
 */
function build_slider($attachments){

  $slides = '';
  if($attachments){
    $slides = '';
    foreach($attachments as $image){
      $href = wp_get_attachment_image_src($image->ID, 'full');
      $src = wp_get_attachment_image_src($image->ID, 'full');
      $alt = get_post_meta($image->ID, '_wp_attachment_image_alt', true);
      $full_slide_link = get_post_meta($image->ID, '_full_link', true);
      $logo_img = get_post_meta($image->ID, '_logo', true);
      $link_one_text = get_post_meta($image->ID, '_link_one_text', true);
      $link_one_url = get_post_meta($image->ID, '_link_one', true);
      $link_two_text = get_post_meta($image->ID, '_link_two_text', true);
      $link_two_url = get_post_meta($image->ID, '_link_two', true);
      $slide_title = get_post_meta($image->ID, '_slide_title', true);
      $title = $image->post_title ? clean_slug($image->post_title) : clean_slug($alt);
      /*$desc = htmlspecialchars($image->post_content);*/
      $desc = strip_tags(html_entity_decode($image->post_content,ENT_QUOTES,'UTF-8'), '<p><br><br/><br /><i><em><u><b><strong>');
      $desc = str_replace('"', '&quot;', $desc);
      $photoCredit = get_post_meta($image->ID, '_photo_credit', true);
      $photoCredit ? $photoCredit = '<br /><span class="photo-credit">Photo Credit: '.$photoCredit.'</span>' : $photoCredit = '';
      $slides .= "<li>";

      if(!empty($full_slide_link)){
        $slides .=   "<a href=\"$full_slide_link\">";
      }
      $slides .=     "<img alt=\"$alt\" data-description=\"$desc $photoCredit\" data-omniture=\"$title\" src=\"{$src[0]}\">";
      $slides .=     "<div class=\"slideCaption\">";
      $slides .=       "<img src=\"$logo_img\" />";
      $slides .=       "<div class=\"slidebg\">";
      $slides .=         "<span class=\"slideTitle\">$slide_title</span>";
      $slides .=         "<span class=\"slideDescription\">$desc</span>";

      if(!empty($link_one_url) && !empty($link_one_text)){
        $slides .=         "<a class=\"firstLink\" href=\"$link_one_url\">$link_one_text</a>";
      }

      if(!empty($link_two_url) && !empty($link_two_text)){
        $slides .=         "<a class=\"secondLink\" href=\"$link_two_url\">$link_two_text</a>";
      }

      $slides .=       "</div>";
      $slides .=     "</div>";
      if(!empty($full_slide_link)){
        $slides .=   "</a>";
      }
      $slides .= "</li>\n";
    }
    return $slides;
  }

}


/* Put together the html for the slider
 * @param $slides (string of slides)
 * @param (int) || (false) $sponsorID
 * @return string
 */
function slider_view($the_slides, $sponsorID=false){

  $br = "\n";

  $html = '<ul class="bxslider">'. $br;
  $html .= $the_slides;
  $html .= '</ul>'. $br.$br;

  $html .= '<script>'. $br;
  $html .= '  jQuery(document).ready(function($) {'. $br;
  $html .= '    jQuery(\'.bxslider\').bxSlider({'. $br;
  $html .= '      mode: \'fade\', '. $br;
  $html .= '      infiniteLoop: true, '. $br;
  $html .= '      hideControlOnEnd: false, '. $br;
  $html .= '      touchEnabled: true, '. $br;
  $html .= '      pager: false, '. $br;
  $html .= '      minSlides: 1, '. $br;
  $html .= '      maxSlides: 1, '. $br;
  $html .= '      adaptiveHeight: true, '. $br;
  $html .= '      controls: true, '. $br;
  $html .= '      auto: true, '. $br;
  $html .= '      pause: 6000, '. $br;
  $html .= '      slideWidth: \'1020\' '. $br;
  $html .= '    });'. $br;
  $html .= '  });'. $br;
  $html .= '</script>'. $br;

  return $html;
}


