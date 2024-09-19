<?php
/**
 * WordPress routing
 *
 */

if ( ! class_exists( 'Timber' ) ) {
	wp_die( 'This site is experiencing technical difficulties.' );
}

$data = Timber::get_context();

$data['nav_main']        = new Timber\Menu( 'nav_main' );
$data['nav_top_contact'] = new Timber\Menu( 'nav_top_contact' );
$data['nav_top_lng']     = new Timber\Menu( 'nav_top_lng' );
$data['nav_footer']      = new Timber\Menu( 'nav_footer' );

$data['fields']    = get_fields();
$data['theme_url'] = get_template_directory_uri();
// $context['theme_settings']  = get_fields( 'options' );
if ( current_user_can( 'administrator' ) ) {
	$data['admin'] = 'admin';
}

if ( is_singular() ) :
	$data['post'] = new TimberPost();
else :
	$data['posts'] = new Timber\PostQuery();
endif;

if ( is_home() ) {
	$data['post']  = new TimberPost();
	$data['posts'] = new Timber\PostQuery();
	$template      = array( 'blog/blog.twig' );
} elseif ( is_front_page() ) {
	$data['page'] = new TimberPost();
	$template     = array( 'home/home.twig' );
} elseif ( is_page_template() ) {
	$data['page']  = 'page';
	$template_slug = get_page_template_slug();

	$template = array(
		$template_slug . '/' . $template_slug . '.template.twig',
		'template.twig',
	);
} elseif ( is_single() ) {
	$template = array(
		'blog-post/blog-post-' . $post->ID . '.twig',
		'blog-post/blog-post-' . $post->post_type . '.twig',
		'blog-post/blog-post.twig',
	);
} elseif ( is_page() ) {
	$data['page'] = 'page';
	$template     = array( 'page-' . $post->post_name . '.twig', 'page.twig' );
} elseif ( is_category() ) {
	$data['term']           = new Timber\Term();
	$data['queried_object'] = get_queried_object();
	$template               = array( 'archive-' . get_query_var( 'tag_id' ) . '.twig', 'archive/archive.twig' );
} elseif ( is_tag() ) {
	$data['term']           = new Timber\Term();
	$data['page']           = 'archive';
	$data['queried_object'] = get_queried_object();
	$template               = array( 'archive-' . get_query_var( 'tag_id' ) . '.twig', 'tag/tag.twig' );
} elseif ( is_author() ) {
	$author_id              = get_the_author_meta( 'ID' );
	$data['author']         = new Timber\User( $author_id );
	$data['author']->fields = get_fields( 'user_' . $author_id );
	$data['page']           = 'archive';
	$template               = array( 'archive/archive-' . get_queried_object()->name . '.twig', 'author/author.twig' );
} elseif ( is_search() ) {
	$data['query_phrase'] = esc_html( get_search_query() );
	$data['page']         = 'archive';
	$template             = array( 'search/search.twig' );
} elseif ( is_archive() ) {
	$data['page']           = 'archive';
	$data['queried_object'] = get_queried_object();
	$template               = array( 'archive/archive-' . get_queried_object()->slug . '.twig', 'archive/archive.twig' );
} else {
	$data['page'] = '404';
	$template     = array( '404/404.twig' );
}

Timber::render( $template, $data );
