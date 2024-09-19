<?php
/**
 * WordPress Gutenberg
 *
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

/**
 * Modify Gutenberg.
 */
class Gutenberg {
	/**
	 * Constructor
	 */
	public function __construct() {
		add_filter( 'render_block', array( $this, 'rewrite_blocks' ), 10, 2 );
	}

	/**
	 * Rewrite blocks template.
	 *
	 * @param string $content Gutenberg block content.
	 * @param string $block Gutenberg block.
	 */
	public function rewrite_blocks( $content, $block ) {
		global $site;

		switch ( $block['blockName'] ) {

			// Image.
			case 'core/image':
				$attrs    = $block['attrs'];
				$id       = $attrs['id'];
				$metadata = wp_get_attachment_metadata( $id );

				// Workaround: get caption string.
				$caption = null;
				if ( preg_match( '/<figcaption>(.*?)<\/figcaption>/', $block['innerHTML'], $match ) === 1 ) {
					$caption = $match[1];
				}

				$image_src = wp_get_attachment_image_src( $attrs['id'], 'full' );

				if ( ! $image_src ) {
					return $content;
				}

				$image_url = $image_src[0];

				$params = array(
					'src'       => $image_url,
					'width'     => 720,
					'dimension' => ( $metadata['height'] / $metadata['width'] * 100 ),
					'caption'   => $caption,
				);

				return Timber::compile( 'wp-image-caption/wp-image-caption.twig', $params );

			// Heading.
			case 'core/heading':
				$regex_heading_value = '/<(h[1-6])>.*<\/h[1-6]>/';

				$content_text = wp_strip_all_tags( $content );
				$tag_id       = str_replace( ' ', '-', strtolower( $content_text ) );

				if ( preg_match( $regex_heading_value, $content, $matches ) !== 1 ) {
					return $content;
				}

				$tag_name = $matches[1];

				$params = array(
					'content'  => $content_text,
					'tag_name' => $tag_name,
					'id'       => $tag_id,
				);

				return Timber::compile( 'wp-heading/wp-heading.twig', $params );
		}

		return $content;
	}
}

new Gutenberg();
