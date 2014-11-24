<?php
/**
 * sneakers functions and definitions
 *
 * @package sneakers
 */


/**
* Register custom post types and functions
*/

require_once('post-types/sneakers.php');
require_once('post-types/sliders.php');


/**
* Slideshow Functionality
**/
require_once('functions/slider-meta.php');
require_once('functions/slider.php');


/**
* Custom Taxonomies
**/
require_once('taxonomies.php');




/**
 * Set the content width based on the theme's design and stylesheet.
 */
if ( ! isset( $content_width ) ) {
	$content_width = 640; /* pixels */
}

if ( ! function_exists( 'sneakers_setup' ) ) :
/**
 * Sets up theme defaults and registers support for various WordPress features.
 *
 * Note that this function is hooked into the after_setup_theme hook, which
 * runs before the init hook. The init hook is too late for some features, such
 * as indicating support for post thumbnails.
 */
function sneakers_setup() {

	/*
	 * Make theme available for translation.
	 * Translations can be filed in the /languages/ directory.
	 * If you're building a theme based on sneakers, use a find and replace
	 * to change 'sneakers' to the name of your theme in all the template files
	 */
	load_theme_textdomain( 'sneakers', get_template_directory() . '/languages' );

	// Add default posts and comments RSS feed links to head.
	add_theme_support( 'automatic-feed-links' );

	/*
	 * Enable support for Post Thumbnails on posts and pages.
	 *
	 * @link http://codex.wordpress.org/Function_Reference/add_theme_support#Post_Thumbnails
	 */
	//add_theme_support( 'post-thumbnails' );

	// This theme uses wp_nav_menu() in one location.
	register_nav_menus( array(
		'primary' => __( 'Primary Menu', 'sneakers' ),
	) );

	/*
	 * Switch default core markup for search form, comment form, and comments
	 * to output valid HTML5.
	 */
	add_theme_support( 'html5', array(
		'search-form', 'comment-form', 'comment-list', 'gallery', 'caption',
	) );

	/*
	 * Enable support for Post Formats.
	 * See http://codex.wordpress.org/Post_Formats
	 */
	add_theme_support( 'post-formats', array(
		'aside', 'image', 'video', 'quote', 'link',
	) );

	// Setup the WordPress core custom background feature.
	add_theme_support( 'custom-background', apply_filters( 'sneakers_custom_background_args', array(
		'default-color' => 'ffffff',
		'default-image' => '',
	) ) );
}
endif; // sneakers_setup
add_action( 'after_setup_theme', 'sneakers_setup' );

/**
 * Register widget area.
 *
 * @link http://codex.wordpress.org/Function_Reference/register_sidebar
 */
function sneakers_widgets_init() {
	register_sidebar( array(
		'name'          => __( 'Sidebar', 'sneakers' ),
		'id'            => 'sidebar-1',
		'description'   => '',
		'before_widget' => '<aside id="%1$s" class="widget %2$s">',
		'after_widget'  => '</aside>',
		'before_title'  => '<h1 class="widget-title">',
		'after_title'   => '</h1>',
	) );
}
add_action( 'widgets_init', 'sneakers_widgets_init' );

/**
 * Enqueue scripts and styles.
 */
function sneakers_scripts() {
	wp_enqueue_style( 'sneakers-style', get_stylesheet_uri() );

	wp_enqueue_style( 'bx-slider', get_template_directory_uri() .'/css/jquery.bxslider.css' );

	wp_enqueue_style( 'sneakers-main', get_template_directory_uri() .'/css/main.css' );

	wp_enqueue_style( 'sneakers-responsive', get_template_directory_uri() .'/css/responsive.css' );

	$js_deps = array('jquery');

	wp_enqueue_script( 'bx-script', get_template_directory_uri() . '/js/jquery.bxslider.min.js', $js_deps );

	wp_enqueue_script( 'sneakers-navigation', get_template_directory_uri() . '/js/navigation.js', $js_deps, '20120206', true );

	wp_enqueue_script( 'sneakers-skip-link-focus-fix', get_template_directory_uri() . '/js/skip-link-focus-fix.js', $js_deps, '20130115', true );

	if ( is_singular() && comments_open() && get_option( 'thread_comments' ) ) {
		wp_enqueue_script( 'comment-reply' );
	}
}
add_action( 'wp_enqueue_scripts', 'sneakers_scripts' );


/**
 * Custom template tags for this theme.
 */
require get_template_directory() . '/inc/template-tags.php';

/**
 * Custom functions that act independently of the theme templates.
 */
require get_template_directory() . '/inc/extras.php';

/**
 * Customizer additions.
 */
require get_template_directory() . '/inc/customizer.php';

/**
 * Load Jetpack compatibility file.
 */
require get_template_directory() . '/inc/jetpack.php';

/**
 * Load Extras Loop
 */
get_template_part('inc/extras-loop');

// Allow Featured Images on Custom Post types
add_theme_support('post-thumbnails');


/* Helper function */
function clean_slug($s) {
  $s = str_replace('&amp;',' ',$s);       ## convert ampersand to space
  $s = strtolower($s);                    ## lowercase
  $s = preg_replace('!\s+!','_',$s);      ## change space chars to underscore
  $s = str_replace('+','_',$s);           ## convert all plus signs to underscores
  $s = str_replace('-','_',$s);           ## convert all dashes to underscores
  $s = preg_replace('/_+/','_',$s);       ## reduce multiple underscores to one
  $s = trim($s,'-_');                     ## trim spaces, tabs, underscores, dashes from beginning & end
  return $s;
}

// general slugify function
function slugify($text) {
	$text = str_replace(array("\.", "'", '(', ')'), '', $text);
	// replace non letter or digits by -
	$text = preg_replace('~[^\\pL\d]+~u', '-', $text);
 
	// trim
	$text = trim($text, '-');
 
	// transliterate
	if (function_exists('iconv'))
	{
		$text = iconv('utf-8', 'us-ascii//TRANSLIT', $text);
	}
 
	// lowercase
	$text = strtolower($text);
 
	// remove unwanted characters
	$text = preg_replace('~[^-\w]+~', '', $text);
 
	if (empty($text))
	{
		return 'n-a';
	}
 
	return $text;
}
