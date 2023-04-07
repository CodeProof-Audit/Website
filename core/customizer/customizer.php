<?php

use Elementor\Core\Kits\Controls\Repeater as Global_Style_Repeater;
use Elementor\Repeater;
use \GT3\ThemesCore\Customizer;

require_once __DIR__.'/customizer_options.php';
require_once __DIR__.'/elementor_options.php';

if(!class_exists('\GT3\ThemesCore\Customizer') || !class_exists('\GT3\ThemesCore\Registration') || !GT3\ThemesCore\Registration::instance()->is_active()) {
	return;
}

add_filter('gt3/core/customizer/permalinks/slugs', function() {
	return array(
		'team_slug',
		'portfolio_slug'
	);
});

Customizer::add_panel('theme_options', array(
	'title'    => 'Theme Options',
	'priority' => 2
));

require_once __DIR__.'/section/general.php';
require_once __DIR__.'/section/typography.php';
require_once __DIR__.'/section/preloader.php';
require_once __DIR__.'/section/page_title.php';
require_once __DIR__.'/section/blog.php';
require_once __DIR__.'/section/post_types.php';
require_once __DIR__.'/section/sidebar.php';
require_once __DIR__.'/section/google_map.php';
require_once __DIR__.'/section/optimization.php';

if(class_exists('WooCommerce')) {
	require_once __DIR__.'/section/shop_global_settings.php';
	require_once __DIR__.'/section/shop_single_product.php';
	require_once __DIR__.'/section/shop_page_title.php';
}
/* Convert Fields */


