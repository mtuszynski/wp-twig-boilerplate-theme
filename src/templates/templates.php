<?php
/**
 * WordPress templates
 *
 */

$custom_templates = array(
	// 'contact'   => 'Contact',
	// 'casestudy' => 'Case Study',
);

add_filter(
	'theme_page_templates',
	function ( $templates ) use ( $custom_templates ) {
		return array_merge( $templates, $custom_templates );
	}
);
