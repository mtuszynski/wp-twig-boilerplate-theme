<?php
/**
 * WordPress enqueue functions and styles
 */
/**
 * Enqueue styles
 */
function enqueue_style() {
	// wp_enqueue_style( 'fonts', get_template_directory_uri() . '/public/global.css', false, 1.0 );.
	wp_enqueue_style( 'style', get_template_directory_uri() . '/public/main.css', false, 1.0 );
}
/**
 * Enqueue scripts
 */
function enqueue_script() {
	wp_enqueue_script( 'app', get_template_directory_uri() . '/public/main.js', false, '1.0.0', true );
}

add_action( 'wp_enqueue_scripts', 'enqueue_style' );
add_action( 'wp_enqueue_scripts', 'enqueue_script' );

add_action('enqueue_block_editor_assets','add_block_editor_assets',10,0);
function add_block_editor_assets(){
    wp_enqueue_style('block_editor_css',get_template_directory_uri() . '/public/main.css');
    wp_enqueue_style('block_editor_js',get_template_directory_uri() . '/public/main.js' );
}
