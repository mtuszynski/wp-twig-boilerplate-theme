<?php
Timber::$dirname = array(
    'src/layouts',
    'src/templates',
    'src/views',
    'src/components',
    'src/blocks',
);

$path_components = 'src/components/';
$path_templates  = 'src/templates/';
$path_sections   = 'src/sections/';
$path_views      = 'src/views/';
$path_posts      = 'inc/posts/';
$path_acf        = 'inc/acf/';
$path_blocks = 'inc/blocks/';

require_once 'inc/acf-support.php';
require_once 'inc/class-gutenberg.php';
require_once 'inc/comments-filters.php';
require_once 'inc/enqueue.php';
require_once 'inc/timber-acf-wp-blocks.php';
require_once 'inc/theme-support.php';
require_once 'inc/wp-function.php';
$site = include_once 'inc/class-site.php';

global $site;

$site->initSite();

require_once 'inc/twig-extends.php';

require_once 'src/templates/templates.php';
