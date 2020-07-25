<?php 

add_action( 'wp_enqueue_scripts', 'test_wp_one_scripts' );

function test_wp_one_scripts() {

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