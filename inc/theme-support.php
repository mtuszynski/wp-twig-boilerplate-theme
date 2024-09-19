<?php
/**
 * WordPress theme supports
 */

add_action(
	'after_setup_theme',
	function () {
		add_theme_support( 'post-thumbnails' );
	}
);

add_action(
	'init',
	function () {
		register_nav_menu( 'nav_main', __( 'Menu: main' ) );
	}
);

add_theme_support( 'admin-bar', array( 'callback' => '__return_false' ) );



if ( function_exists( 'acf_add_options_page' ) ) {
	acf_add_options_page(
		array(
			'page_title' => 'Theme Settings',
			'menu_title' => 'Theme Settings',
			'menu_slug'  => 'theme-settings',
			'capability' => 'edit_posts',
			'redirect'   => false,
		)
	);
}

add_action(
	'init',
	function() {
		global $wp_rewrite;
		$author_slug             = 'autor';
		$wp_rewrite->author_base = $author_slug;
		$wp_rewrite->flush_rules();
	}
);

add_theme_support( 'custom-logo', array() );

