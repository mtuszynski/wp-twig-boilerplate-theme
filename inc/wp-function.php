<?php
/**
 * WordPress functions
 *
 */

// Removing Version Information.
remove_action( 'wp_head', 'wp_generator' );

if ( ! class_exists( 'Timber' ) ) {
    add_action(
        'admin_notices',
        function () {
            echo '<div class="error"><p>Timber not activated. Make sure you activate the plugin in <a href="' . esc_url( admin_url( 'plugins.php#timber' ) ) . '">' . esc_url( admin_url( 'plugins.php' ) ) . '</a></p></div>';
        }
    );

    return;
}
/**
* Remove WordPress version.
*/
function remove_wordpress_version() {
return '';
}
add_filter( 'the_generator', 'remove_wordpress_version' );

/**
* Pick out the version number from scripts and styles
*
* @param string $src Link.
*/
function remove_version_from_style_js( $src ) {
if ( strpos( $src, 'ver=' . get_bloginfo( 'version' ) ) ) {
$src = remove_query_arg( 'ver', $src );
}
return $src;
}
add_filter( 'style_loader_src', 'remove_version_from_style_js' );
add_filter( 'script_loader_src', 'remove_version_from_style_js' );
add_filter( 'big_image_size_threshold', '__return_false' );

function add_container_class_to_blocks( $block_content, $block ) {
    if ( strpos( $block['blockName'], 'core/' ) === 0 ) {
        $block_content = str_replace(
            'wp-block-',
            'container wp-block-',
            $block_content
        );
    }
    return $block_content;
}
add_filter( 'render_block', 'add_container_class_to_blocks', 10, 2 );