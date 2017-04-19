<?php 

require get_template_directory() . '/inc/template-tags.php';
require get_template_directory() . '/inc/now-button.php';

function classroom_scripts(){
	wp_enqueue_style('carbon', 'https://unpkg.com/carbon-grid@1.0.6/dist/css/carbon-grid.css' );
	wp_enqueue_style( 'google-fonts', 'https://fonts.googleapis.com/css?family=Noto+Serif:400,400i,700,700i|Work+Sans' );
	wp_enqueue_style( 'dashicons' );
	wp_enqueue_style( 'theme-style', get_stylesheet_uri() );

	wp_enqueue_script( 'jQuery' );
	wp_enqueue_script( 'theme-script', get_stylesheet_directory_uri() . '/scripts/theme.js',  array('jquery'), '0.1', true );

	if ( is_singular() && comments_open() && get_option( 'thread_comments' ) ) {
		wp_enqueue_script( 'comment-reply' );
	}


}
add_action('wp_enqueue_scripts', 'classroom_scripts' );


add_action('after_setup_theme', 'classroom_theme_setup' );
function classroom_theme_setup(){
	// You should create custom-editor-style.css in your theme folder
	add_editor_style();

	// Enable thumbnails
	add_theme_support( 'post-thumbnails' );
	set_post_thumbnail_size(300, 200, true); // Normal post thumbnails

	//use modern, HTML5 elements
	add_theme_support( 'html5', array( 'search-form', 'comment-form', 'comment-list', 'gallery', 'caption' ) );

	//remove <title> from header and let WP generate dynamic page titles
	add_theme_support( 'title-tag' );

	add_theme_support( 'custom-logo', array(
	    'height'      => 150,
	    'width'       => 150,
	
	    'header-text' => array( 'site-title', 'site-description' ),
) );

	// Set a maximum width for Oembedded objects
	if ( ! isset( $content_width ) )
		$content_width = 660;


// Add default posts and comments RSS feed links to head
	add_theme_support( 'automatic-feed-links' );


	register_nav_menus(array(
		'main_menu' => 'Main Menu',
		
		) );

	
}

add_action( 'widgets_init', 'classroom_theme_widgets_init' );
function classroom_theme_widgets_init() {
	register_sidebar(array(
		'name' => 'Sidebar Widgets',
		'id' => 'sidebar-widgets',
		'before_widget' => '<section id="%1$s" class="widget column sm-50 md-33 lg-100 %2$s">',
		'after_widget' => '</section>',
		) );
	register_sidebar(array(
		'name' => 'Footer Widgets',
		'id' => 'footer-widgets',
		'before_widget' => '<section id="%1$s" class="widget column sm-50 md-33 lg-25 %2$s">',
		'after_widget' => '</section>',
		) );
}





// Custom CSS for the login page
// Create wp-login.css in your theme folder
function classroom_loginCSS() {
	echo '<link rel="stylesheet" type="text/css" href="' .get_template_directory_uri() .'/wp-login.css"/>';
}
add_action('login_head', 'classroom_loginCSS');




// Put post thumbnails into rss feed
function classroom_feed_post_thumbnail($content) {
	global $post;
	if(has_post_thumbnail($post->ID)) {
		$content = '' . $content;
	}
	return $content;
}
add_filter('the_excerpt_rss', 'classroom_feed_post_thumbnail');
add_filter('the_content_feed', 'classroom_feed_post_thumbnail');



//create a permalink after the excerpt
function classroom_replace_excerpt_more() {
	return 	'<br> <a class="readmore button" href="'. get_permalink() .'">Continue Reading</a>';
}
add_filter('excerpt_more', 'classroom_replace_excerpt_more');


//custom excerpt length
function classroom_custom_excerpt_length() {
	//the amount of words to return
	return 20;
}
add_filter( 'excerpt_length', 'classroom_custom_excerpt_length');



// Remove the version number of WP
// Warning - this info is also available in the readme.html file in your root directory - delete this file!
remove_action('wp_head', 'wp_generator');


// Disable the theme / plugin text editor in Admin
define('DISALLOW_FILE_EDIT', true);




/**
 * ACF integration
 * Adds custom fields to various post types
 */
if(function_exists("register_field_group")){
	register_field_group(array (
		'id' => 'acf_attention-box',
		'title' => 'Attention Box',
		'fields' => array (
			array (
				'key' => 'field_5500e7a8e6a60',
				'label' => 'Important',
				'name' => 'important',
				'type' => 'wysiwyg',
				'instructions' => 'Any text you write here will be emphasized in a callout box when viewing the post. ',
				'default_value' => '',
				'toolbar' => 'full',
				'media_upload' => 'yes',
				),
			),
		'location' => array (
			array (
				array (
					'param' => 'post_type',
					'operator' => '==',
					'value' => 'post',
					'order_no' => 0,
					'group_no' => 0,
					),
				),
			array (
				array (
					'param' => 'post_type',
					'operator' => '==',
					'value' => 'page',
					'order_no' => 0,
					'group_no' => 1,
					),
				),
			),
		'options' => array (
			'position' => 'normal',
			'layout' => 'no_box',
			'hide_on_screen' => array (
				),
			),
		'menu_order' => 0,
		));
	register_field_group(array (
		'id' => 'acf_optional-settings',
		'title' => 'Optional Settings',
		'fields' => array (
			array (
				'key' => 'field_54d4f0055e486',
				'label' => 'Due Date',
				'name' => 'due_date',
				'type' => 'date_picker',
				'date_format' => 'yymmdd',
				'display_format' => 'dd/mm/yy',
				'first_day' => 0,
				),
			array (
				'key' => 'field_54d4f0265e487',
				'label' => 'File Attachment',
				'name' => 'file_attachment',
				'type' => 'file',
				'instructions' => 'Attach a file to this post for your students to download. If you need more than one file attachment, use the "Add Media" button. ',
				'save_format' => 'object',
				'library' => 'all',
				),
			array (
				'key' => 'field_5500f5538b3e3',
				'label' => 'Related Reading',
				'name' => 'related_reading',
				'type' => 'textarea',
				'default_value' => '',
				'placeholder' => '',
				'maxlength' => '',
				'rows' => 5,
				'formatting' => 'html',
				),
			),
		'location' => array (
			array (
				array (
					'param' => 'post_type',
					'operator' => '==',
					'value' => 'post',
					'order_no' => 0,
					'group_no' => 0,
					),
				),
			array (
				array (
					'param' => 'post_type',
					'operator' => '==',
					'value' => 'page',
					'order_no' => 0,
					'group_no' => 1,
					),
				),
			),
		'options' => array (
			'position' => 'side',
			'layout' => 'default',
			'hide_on_screen' => array (
				),
			),
		'menu_order' => 0,
		));
	
}

