<?php
/**
 * Twig extends
 */

add_filter(
	'timber/twig',
	function ( $twig ) {
		$twig->addExtension(
			new Twig_Extension_StringLoader()
		);

		$twig->addFilter(
			new Twig_SimpleFilter(
				'image_url',
				function ( $text ) {
					return get_template_directory_uri() . '/src/images/' . $text;
				}
			)
		);

		$twig->addFilter(
			new Twig_SimpleFilter(
				'icon_url',
				function ( $text ) {
					return get_template_directory_uri() . '/src/images/svg/' . $text . '.svg';
				}
			)
		);

		$twig->addFilter(
			new Twig_SimpleFilter(
				'date_format',
				function ( $date ) {
					$now        = new DateTime();
					$d          = new DateTime( $date );
					$difference = $now->diff( $d );
					switch ( $difference->d ) {
						case 0:
							return 'Today';
						case 1:
							return '1 day ago';
						case ( $difference->d <= 7 ):
							return $difference->d . ' days ago';
						default:
							return $date;
					}
				}
			)
		);
		$twig->addFilter(
			new Twig_SimpleFilter(
				'wysiwyg',
				function ( $value ) {
					return apply_filters( 'the_content', $value );
				}
			)
		);

		$twig->addFilter(
			new Twig_SimpleFilter(
				'icon',
				function ( $icon_name, $class_names = false ) {
					$filename = get_template_directory() . '/src/images/svg/' . $icon_name . '.svg';

					if ( file_exists( $filename ) ) {
						$icon = file_get_contents( $filename );

						if ( $class_names ) {
							$icon = str_replace( '<svg', "<svg class='icon " . $class_names . "'", $icon );
						}

						return $icon;
					} else {
						return "<span style='color:#ff0000; font-size: 14px;'>!$icon_name'</span>";
					}
				}
			)
		);

		$twig->addFilter(
			new Twig_SimpleFilter(
				'svg',
				function ( $icon_src, $class_names = false ) {
						$url       = wp_get_attachment_url( $icon_src );
						$uploads   = wp_upload_dir();
						$file_path = str_replace( $uploads['baseurl'], $uploads['basedir'], $url );
						$filename  = $file_path;
					if ( $filename ) {
						$icon = file_get_contents( $filename );
						if ( $class_names ) {
							$icon = str_replace( '<svg', "<svg class='icon " . $class_names . "'", $icon );
							$icon = str_replace( '/id="(\w)+/g', '', $icon );
						}
						return $icon;
					}
				}
			)
		);

		$twig->addFilter(
			new Twig_SimpleFilter(
				'permalink',
				function ( $id ) {
						return get_the_permalink( $id );
				}
			)
		);
		$twig->addFilter(
			new Twig_SimpleFilter(
				'get_title',
				function ( $id ) {
						return get_the_title( $id );
				}
			)
		);
		$twig->addFilter(
			new Twig_SimpleFilter(
				'text',
				function () {
						return 'test text';
				}
			)
		);
		$twig->addFilter(
			new Twig_SimpleFilter(
				'thumbnail',
				function ( $id, $size = 'full' ) {
						return get_the_post_thumbnail_url( $id, $size );
				}
			)
		);
		$twig->addFilter(
			new Twig_SimpleFilter(
				'term_link',
				function ( $id ) {
						return get_term_link( $id );
				}
			)
		);
		$twig->addFilter(
			new Twig_SimpleFilter(
				'image_by_post_id',
				function ( $id, $size = 'full' ) {
					$attr        = array();
					$attr['alt'] = get_the_title( $id );
					return wp_get_attachment_image( get_post_thumbnail_id( $id ), $size, '', $attr );
				}
			)
		);
		$twig->addFilter(
			new Twig_SimpleFilter(
				'category_name',
				function ( $id ) {
						return get_cat_name( $id );
				}
			)
		);

		$twig->addFilter(
			new Twig_SimpleFilter(
				'blog_link',
				function () {
						return get_permalink( get_option( 'page_for_posts' ) );
				}
			)
		);

		$twig->addFilter(
			new Twig_SimpleFilter(
				'nonce',
				function ( $action, $nonce ) {
					if ( 'load_post' == $action ) {
						$nonce = 'nonce_posts';
					}
						return wp_nonce_field( $action, $nonce, true, false );
				}
			)
		);
		$twig->addFilter(
			new Twig_SimpleFilter(
				'time',
				function ( $id ) {
						return reading_time( $id );
				}
			)
		);
		$twig->addFilter(
			new Twig_SimpleFilter(
				'str_replace',
				function ( $search, $replace, $string ) {
					return str_replace( $search, $replace, $string );
				}
			)
		);
		$twig->addFilter(
			new Twig_SimpleFilter(
				'category_slug',
				function ( $id ) {
						return str_replace( array( ' ', ',', '.' ), '-', get_cat_name( $id ) );
				}
			)
		);
		$twig->addFilter(
			new Twig_SimpleFilter(
				'svg_url',
				function ( $text ) {
						return get_template_directory_uri() . '/static/svg/' . $text . '.svg';
				}
			)
		);

		/**
		 * Get headers tag from content.
		 */
		$twig->addFunction(
			new Timber\Twig_Function(
				'get_headers',
				function ( $content ) {
					preg_match_all( '/<h(2).*?>(.*)<\/h2+>/', $content, $matches );

					if ( empty( $matches ) ) {
						return;
					}

					$output = array();
					$tags   = $matches[1];
					$titles = $matches[2];

					foreach ( $tags as $key => $tag ) {
						$title = wp_strip_all_tags( $titles[ $key ] );

						switch ( $tag ) {
							case 2:
								$output[] = array(
									'title' => $title,
									'index' => $key,
									'child' => array(),
								);

								break;
						}
					}

					return $output;
				}
			)
		);

		return $twig;
	}
);

add_filter(
	'timber_context',
	function ( $context ) {
		global $site;
		$options_default = get_fields( 'options_default' );

		if ( class_exists( 'ACF' ) ) {
			$context['theme_settings'] = get_fields( 'options' );
		}

		$context['cookies'] = $_COOKIE;
		$context['site']    = $site;

		return $context;
	}
);

/**
 * Get date time
 *
 * @param string $post_date Post date.
 */
function date_time_ago( $post_date ) {
	global $post;
	$post_date = strtotime( $post_date );
	return human_time_diff( $post_date, current_time( 'timestamp' ) ) . ' ' . __( 'temu' );
}


/**
 * Get proper word based on ordinal number
 *
 * @param string $items_count Number of items.
 */
function proper_word( $items_count ) {
	$last_number = substr( $items_count, -1 );
	if ( 1 === $items_count ) {
		echo esc_html( $items_count ) . ' artykuł';
	} elseif ( ( $items_count > 1 && $items_count < 5 ) && ( 2 === $last_number || 4 === $last_number ) ) {
		echo esc_html( $items_count ) . ' artykuły';
	} else {
		echo esc_html( $items_count ) . ' artykułów';
	}
}
