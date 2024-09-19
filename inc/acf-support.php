<?php
/**
 * Integrating Advanced Custom Fields:
 */
add_filter( 'acf/settings/save_json', function () {
    return get_stylesheet_directory() . '/acf-json';
} );
add_filter( 'acf/settings/load_json', function ( $paths ) {
    unset( $paths[0] );
    $paths[] = get_template_directory() . '/acf-json';
    $paths[] = get_stylesheet_directory() . '/acf-json';

    return $paths;
} );