<?php 

function test_wp_one_setup()
{
	load_theme_textdomain('test_wp_one');

	add_theme_support('title-tag');
	add_theme_support('custom-logo', [
		'height' => 31,
		'width'  => 134,
		'flex-height' => true,
	]);
	add_theme_support('post-thumbnails');
	set_post_thumbnail_size(720, 446);

	add_theme_support( 'html5', [
		'search_form',
		'comment-form',
		'comment-list',
		'gallery',
		'caption',
	] );
	add_theme_support( 'post-formats', [
		'aside',
		'image',
		'video',
		'gallery',
	] );

	register_nav_menu( 'primary', 'Главное меню' );
}

add_action('after_setup_theme', 'test_wp_one_setup');

add_action( 'wp_enqueue_scripts', 'test_wp_one_scripts' );

function test_wp_one_scripts() 
{

	// Style
	wp_enqueue_style( 'bootstrap', get_template_directory_uri() .'/css/bootstrap.min.css' );
	wp_enqueue_style( 'animate', get_template_directory_uri() .'/css/animate.min.css' );
	wp_enqueue_style( 'font-awesome', get_template_directory_uri() .'/css/font-awesome.min.css' );
	wp_enqueue_style( 'style-css', get_stylesheet_uri() );

	// Scripts
	wp_enqueue_script('jquery');
	wp_enqueue_script('bootstrap', get_template_directory_uri() . '/js/bootstrap.min.js');
	wp_enqueue_script('css3-animate-it', get_template_directory_uri() . '/js/css3-animate-it.js');
	wp_enqueue_script('jquery-easing', get_template_directory_uri() . '/js/jquery.easing.min.js');
	wp_enqueue_script('agency', get_template_directory_uri() . '/js/agency.js');
}