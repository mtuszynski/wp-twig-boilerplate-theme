<?php
/**
 * WordPress Timber Extension
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}
/**
 * Extend functions
 */
class Site extends TimberSite {


	/**
	 * Logo object.
	 *
	 * @var $logo
	 **/
	public $logo;
	/**
	 * Url string.
	 *
	 * @var $url
	 **/
	public $url;
	/**
	 * blog url string.
	 *
	 * @var $blog_url
	 **/
	public $blog_url;

	/**
	 * Constructor
	 */
	public function __construct() {
		parent::__construct();
	}

	/**
	 * Init
	 */
	public function initSite() {
		$id         = get_theme_mod( 'custom_logo' );
		$this->logo = new Timber\Image( $id );

		$this->url = get_home_url();

		$this->blog_url = get_permalink( get_option( 'page_for_posts' ) );
	}

	/**
	 * Get multiple posts
	 *
	 * @param string $params Params of query.
	 */
	public function get_posts( $params ) {
		$default = array(
			'post_type'      => 'post',
			'orderby'        => 'meta_value_num',
			'order'          => 'DESC',
			'posts_per_page' => -1,
		);

		$query_params = array_merge( $default, (array) $params );

		return new Timber\PostQuery( $query_params );
	}

	/**
	 * Get multiple users
	 *
	 * @param string $params Params of query.
	 */
	public function get_users( $params ) {
		$blogusers = get_users( $params );

		return $blogusers;
	}

	/**
	 * Get post
	 *
	 * @param string $id Id of post.
	 */
	public function get_single_post( $id ) {
		if ( ! $id ) {
			return null;
		} else {
			return new Timber\Post( $id );
		}
	}

	/**
	 * Get attachment image
	 *
	 * @param string $id   Id of post.
	 * @param string $size Size of post(from documentation).
	 */
	public function get_image_url( $id, $size = 'full' ) {
		return wp_get_attachment_image_src( $id, $size )[0];
	}

	/**
	 * Get post image
	 *
	 * @param string $id   Id of post.
	 * @param string $size Size of post(from documentation).
	 */
	public function get_post_image( $id, $size ) {
		$attachment_id   = get_post_thumbnail_id( $id );
		$attachment      = wp_get_attachment_image_src( $attachment_id, $size );
		$image['url']    = $attachment[0];
		$image['width']  = $attachment[1];
		$image['height'] = $attachment[2];

		return $image;
	}

	/**
	 * Get thumbnail
	 *
	 * @param string $id   Id of post.
	 * @param string $size Size of post(from documentation).
	 */
	public function get_thumbnail_url( $id, $size = 'full' ) {
		return get_the_post_thumbnail_url( $id, $size );
	}

	/**
	 * Get categories
	 *
	 * @param string $id Id of post.
	 */
	public function get_categories( $id ) {
		return wp_get_post_categories( $id );
	}

	/**
	 * Get image from theme directory
	 *
	 * @param string $file_dir File directory.
	 */
	public function image( $file_dir ) {
		$directory = get_template_directory_uri() . '/src/images/' . $file_dir;

		return new Timber\Image( $directory );
	}

	/**
	 * Get author name
	 *
	 * @param string $id Author id.
	 */
	public function get_author_name( $id ) {
		$name = get_the_author_meta( 'display_name', $id );

		return $name;
	}
}

return new Site();
