<?php

// Remove admin bar from logged user
add_filter('show_admin_bar', '__return_false');

// Enable thumbnails
add_theme_support('post-thumbnails');

// Remove defaults
wp_deregister_script('jquery');
wp_register_script('jquery', (""));

function wp_website_scripts() {
  $version = $_SERVER['HTTP_HOST'] == 'localhost' ? 'v' . rand() : '1.0';
  wp_enqueue_style('main_css', get_template_directory_uri() . '/build/style.css', array(), $version, false);
  wp_enqueue_script('main_js', get_template_directory_uri() . '/build/app_bundle.js', array(), $version, true);
}
add_action('wp_enqueue_scripts', 'wp_website_scripts');

// Add custom crop
// if ( function_exists( 'add_image_size' ) ) {
// 	add_image_size('thumb-product', 300, 209, true);
// 	add_image_size('large-product', 792, 500, true);
// }

// Disabable emojicons script
function disable_wp_emojicons() {
	remove_action( 'admin_print_styles', 'print_emoji_styles' );
	remove_action( 'wp_head', 'print_emoji_detection_script', 7 );
	remove_action( 'admin_print_scripts', 'print_emoji_detection_script' );
	remove_action( 'wp_print_styles', 'print_emoji_styles' );
	remove_filter( 'wp_mail', 'wp_staticize_emoji_for_email' );
	remove_filter( 'the_content_feed', 'wp_staticize_emoji' );
	remove_filter( 'comment_text_rss', 'wp_staticize_emoji' );
	add_filter( 'tiny_mce_plugins', 'disable_emojicons_tinymce' );
}
add_action( 'init', 'disable_wp_emojicons' );

function disable_emojicons_tinymce( $plugins ) {
	if ( is_array( $plugins ) ) {
		return array_diff( $plugins, array( 'wpemoji' ) );
	} else {
		return array();
	}
}

// Remove tag <p> from images
function filter_ptags_on_images($content) {
	$content = preg_replace('/<p>\s*(<a .*>)?\s*(<img .* \/>)\s*(<\/a>)?\s*<\/p>/iU', '\1\2\3', $content);
	$content = preg_replace('/<p>\s*(<object.*>*.<\/object>)\s*<\/p>/iU', '\1', $content);
	return preg_replace('/<p>\s*(<iframe .*>*.<\/iframe>)\s*<\/p>/iU', '\1', $content);
}
add_filter('the_content', 'filter_ptags_on_images');

// Get post image from any post
function getPostImage($id, $size) {

	$thumbnailID = get_post_thumbnail_id($id);
	$result = false;

	if($thumbnailID == "" || $thumbnailID == 0) {
		$attachments = get_posts( array(
			'post_type' => 'attachment',
			'posts_per_page' => 1,
			'post_parent' => $id,
			'orderby' => 'menu_order',
			'order' => 'ASC'
		) );
		if ( $attachments ) {
			$result = wp_get_attachment_image_src( $attachment[0]->ID, $size );
			$srcset = wp_get_attachment_image_srcset($attachment[0]->ID);
			$result = array(
				'id' => $attachment[0]->ID,
				'src' => $result[0],
				'width' => $result[1],
				'height' => $result[2],
				'srcset' => $srcset
			);
		}
	} else {
		$result = wp_get_attachment_image_src( $thumbnailID, $size );
		$srcset = wp_get_attachment_image_srcset($thumbnailID);
		$result = array(
			'id' => $thumbnailID,
			'src' => $result[0],
			'width' => $result[1],
			'height' => $result[2],
			'srcset' => $srcset
		);
	}

	return $result;
}


// Register menu
function register_my_menu() {
	register_nav_menu('header-nav', __('Header Nav'));
	register_nav_menu('footer-nav', __('Footer Nav'));
}
add_action('init', 'register_my_menu');


// Remove link from sidebar menu
function remove_menus(){
	remove_menu_page( 'edit-comments.php' );
}
add_action( 'admin_menu', 'remove_menus' );


// ACF Page Options
if( function_exists('acf_add_options_page') ) {
	$parent = acf_add_options_page(array(
		'page_title' 	=> 'General Content Settings',
		'menu_title' 	=> 'General Content Settings',
		'post_id' => 'general-content',
		'redirect' 		=> false
	));
}

// Register new post type/taxonomy
function registerNewPostType($type, $name, $singularName, $dashicon, $supports = array('title', 'editor', 'thumbnail')) {
	$labels = array('name' => __($name), 'singular_name' => __($singularName));
	$args = array(
		'labels' => $labels,
		'public' => true,
		'publicly_queryable' => true,
		'show_ui' => true,
		'query_var' => true,
		'rewrite' => true,
		'capability_type' => 'post',
		'menu_icon' => $dashicon,
		'supports' => $supports
	);
	register_post_type($type, $args);
}


function registerNewTaxonomy($taxonomy, $type, $name) {
	register_taxonomy(
		$taxonomy,
		$type,
		array(
			'label' => __($name),
			'rewrite' => array( 'slug' => $taxonomy),
			'hierarchical' => true,
		)
	);
}

// registerNewPostType('receita', 'Receitas', 'Receita', 'dashicons-carrot');
// registerNewTaxonomy('categoria-receita', 'receita', 'Categorias');


// Custom logo
function my_login_logo() {
  $logo = get_stylesheet_directory_uri() . '/src/images/logo-projectname.png';
  echo '<style type="text/css">
    #login h1 a, .login h1 a {
      background-image: url('. $logo .');
      height:100px;
      width: 200px;
      background-size: 100%;
      background-repeat: no-repeat;
      margin-bottom: 15px;
    }
    #loginform h3 {
      display: none;
    }
    #loginform .button-primary,
    #lostpasswordform .button-primary {
      background: #555 !important;
      border-color: #555 !important;
      text-shadow: none !important;
      box-shadow: none !important;
    }
    .login #login_error,
    .login .message,
    .login .success {
      border-color: #555 !important;
    }
  </style>';
}
add_action( 'login_enqueue_scripts', 'my_login_logo' );

function my_login_logo_url() {
  return home_url();
}
add_filter( 'login_headerurl', 'my_login_logo_url' );

function my_login_logo_url_title() {
  return 'CLIENT_NAME';
}
add_filter( 'login_headertitle', 'my_login_logo_url_title' );
