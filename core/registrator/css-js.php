<?php

use \GT3\ThemesCore\Customizer\Elementor as Theme_Elementor;
#Frontend
if (!function_exists('css_js_register')) {
	function css_js_register() {
		$version       = wp_get_theme()->get('Version');

		wp_register_script('gt3-theme', get_template_directory_uri() . '/js/theme.js', array('jquery'), $version, true);
		$translation_array = array(
			'ajaxurl' => esc_url(admin_url('admin-ajax.php')),
			'templateUrl' => esc_url(get_template_directory_uri())
		);
		wp_localize_script('jquery', 'gt3_gt3theme', $translation_array);

		#CSS
		//wp_enqueue_style('gt3-theme-default-style', get_bloginfo('stylesheet_url'), array(), $version);
		if (class_exists('YITH_WCWL_Init')) {
			wp_dequeue_style('yith-wcwl-font-awesome');
		}

		#DIST
		gt3_theme_style('base', get_template_directory_uri() . '/dist/css/base.css');
		gt3_theme_style('font-awesome', get_template_directory_uri() . '/dist/css/font-awesome.css');

		/* Default Header */
		if (!did_action('wpda-builder/enqueue_assets')) {
			gt3_theme_style('header', get_template_directory_uri() . '/dist/css/header.css');
		}
		/* Bubbles */
		$bubbles_block = gt3_option('bubbles_block');

		if (class_exists( 'RWMB_Loader' )) {
			$mb_bubbles_block = rwmb_meta('mb_bubbles_block', array(), gt3_get_queried_object_id());
			if (!empty($mb_bubbles_block) && $mb_bubbles_block != 'default') {
				$bubbles_block = $mb_bubbles_block;
			}
		}

		if ($bubbles_block == '1' || $bubbles_block == 'show' || true === $bubbles_block || gt3_customizer_enabled()) {
			gt3_theme_style('bubble', get_template_directory_uri() . '/dist/css/bubble.css');
		}
		/* Pixproof */
		if (class_exists('Pixproof_Plugin')) {
			gt3_theme_style('proof_gallery', get_template_directory_uri() . '/dist/css/proof_gallery.css');
		}
		/* CF7 */
		if ( defined( 'WPCF7_PLUGIN' ) ) {
			gt3_theme_style('cf7', get_template_directory_uri() . '/dist/css/cf7.css');
		}
		/* Mailchimp */
		if ( function_exists( 'mailchimpSF_plugin_init' ) ||  function_exists( '_mc4wp_load_plugin' )) {
			gt3_theme_style('mailchimp', get_template_directory_uri() . '/dist/css/mailchimp.css');
		}
		/* Preloader */
		$post_loader =  (class_exists( 'RWMB_Loader' ) && gt3_get_queried_object_id() !== 0);
		$mb_preloader = $post_loader ? rwmb_meta('mb_preloader', array(), gt3_get_queried_object_id()) : false;
		if (gt3_option('preloader') == '1' || gt3_option('preloader') == true || $mb_preloader == 'custom') {
			gt3_theme_style('preloader', get_template_directory_uri() . '/dist/css/preloader.css');
		}

		/* Archive */
		if (is_archive()) {
			gt3_theme_style('archive', get_template_directory_uri() . '/dist/css/archive.css');
		}
		/* Password Protected */
		if (post_password_required()) {
			gt3_theme_style('password_protected', get_template_directory_uri() . '/dist/css/password_protected.css');
		}


		if (!defined('GT3_THEMES_CORE_PLUGIN_FILE')) {
			gt3_theme_style('wp-widgets/all', get_template_directory_uri() . '/dist/css/wp-widgets/all.css');
		} else {
			gt3_theme_style('wp-widgets', get_template_directory_uri() . '/dist/css/wp-widgets.css');
		}

		#JS
		gt3_theme_script('anime', get_template_directory_uri() . '/dist/js/anime.js');
//        wp_register_script('jquery-slick', get_template_directory_uri() . '/js/slick.min.js', array('jquery'), '1.8.0', true);
		if(gt3_option('blog_post_likes') == '1' || true === gt3_option('blog_post_likes')) {
			gt3_theme_script('jquery/cookie',get_template_directory_uri() . '/js/jquery.cookie.js', array('jquery'));
		}
		gt3_theme_script('theme', get_template_directory_uri() . '/dist/js/theme.js');

		if (!empty(gt3_option('google_map_api_key'))) {
			$google_map_API = gt3_option('google_map_api_key');
		} else if (class_exists('Elementor\Plugin')) {
			$google_map_API = get_option('elementor_google_maps_api_key');
		} else {
			$google_map_API = '';
		}
		if (!empty($google_map_API)) {
			wp_register_script('google-maps-api', add_query_arg('key', $google_map_API, '//maps.google.com/maps/api/js'), array(), '', true);
		}
	}
}
add_action('wp_enqueue_scripts', 'css_js_register', 7);

add_action('wpda-builder/enqueue_assets', function() {
	gt3_theme_style('wpda', get_template_directory_uri() . '/dist/css/wpda.css');
});

#Admin
add_action('admin_enqueue_scripts', 'admin_css_js_register');
function admin_css_js_register() {
	#CSS (MAIN)
	gt3_theme_style('font-awesome', get_template_directory_uri() . '/dist/css/font-awesome.css');
	wp_enqueue_style('gt3-admin', get_template_directory_uri() . '/core/admin/css/admin.css', array(), GT3_THEME_VERSION);
	wp_enqueue_style('wp-color-picker');

	#JS (MAIN)
	wp_enqueue_script('gt3-admin', get_template_directory_uri() . '/core/admin/js/admin.js', array('jquery'), GT3_THEME_VERSION, true);
//    wp_enqueue_media();
	wp_enqueue_script('wp-color-picker');


	if (class_exists('RWMB_Loader')) {
		wp_enqueue_script('gt3-metaboxes', get_template_directory_uri() . '/core/admin/js/metaboxes.js');
	}
}

function gt3_custom_styles() {
	//return;
	wp_register_style( 'gt3-dynamic-handle', false );
	wp_enqueue_style( 'gt3-dynamic-handle' );

	$RWMB_Loader = class_exists('RWMB_Loader');
	$custom_css  = '';

	// THEME COLOR
	$theme_color  = esc_attr(gt3_option('theme-custom-color'));
	$theme_color2  = esc_attr(gt3_option('theme-custom-color2'));

	$theme_color_start  = esc_attr(gt3_option('theme-custom-color-start'));
	$theme_color2_start  = esc_attr(gt3_option('theme-custom-color2-start'));

	// END THEME COLOR

	// BODY BACKGROUND
	$bg_body = gt3_option('body-background-color');
	if (is_string($bg_body)) {
		$bg_body = esc_attr($bg_body);
	}
	// END BODY BACKGROUND

	// BODY TYPOGRAPHY
	$main_font = gt3_option('main-font');

	// HEADER TYPOGRAPHY
	$header_font = gt3_option('header-font');

	// Modern Shop
	if ((gt3_option('modern_shop') == '1' || true === gt3_option('modern_shop')) && function_exists('gt3_has_shop_on_page') && gt3_has_shop_on_page()) {
		$main_font = gt3_option('modern_shop_main-font');
		$header_font = gt3_option('modern_shop_header-font');
		//$theme_color  = esc_attr(gt3_option('theme-modern_custom-color'));
	}

	// BODY TYPOGRAPHY
	if (!empty($main_font)) {
		$content_font_family = esc_attr($main_font['font-family']);
		$content_line_height = esc_attr($main_font['line-height']);
		$content_font_size   = esc_attr($main_font['font-size']);
		$content_font_weight = esc_attr($main_font['font-weight']);
		$content_color       = esc_attr($main_font['color']);
	} else {
		$content_font_family = '';
		$content_line_height = '';
		$content_font_size   = '';
		$content_font_weight = '';
		$content_color       = '';
	}
	$map_marker_font = gt3_option('map-marker-font');
	if (!empty($map_marker_font)) {
		$map_marker_font_family = esc_attr($map_marker_font['font-family']);
		$map_marker_font_weight = esc_attr($map_marker_font['font-weight']);
	} else {
		$map_marker_font_family = '';
		$map_marker_font_weight = '';
	}
	// END BODY TYPOGRAPHY

	// SECONDARY TYPOGRAPHY
	$secondary_font = gt3_option('secondary-font');
	if (!empty($secondary_font)) {
		$content_secondary_font_family = esc_attr($secondary_font['font-family']);
		$content_secondary_line_height = esc_attr($secondary_font['line-height']);
		$content_secondary_font_size   = esc_attr($secondary_font['font-size']);
		$content_secondary_font_weight = esc_attr($secondary_font['font-weight']);
		$content_secondary_color       = esc_attr($secondary_font['color']);
	} else {
		$content_secondary_font_family = '';
		$content_secondary_line_height = '';
		$content_secondary_font_size   = '';
		$content_secondary_font_weight = '';
		$content_secondary_color       = '';
	}
	// END BODY TYPOGRAPHY

	// HEADER TYPOGRAPHY
	if (!empty($header_font)) {
		$header_font_family = esc_attr($header_font['font-family']);
		$header_font_weight = esc_attr($header_font['font-weight']);
		$header_font_color  = esc_attr($header_font['color']);
	} else {
		$header_font_family = '';
		$header_font_weight = '';
		$header_font_color  = '';
	}

	$h1_font = gt3_option('h1-font');
	if (!empty($h1_font)) {
		$H1_font_family      = !empty($h1_font['font-family']) ? esc_attr($h1_font['font-family']) : '';
		$H1_font_weight      = !empty($h1_font['font-weight']) ? esc_attr($h1_font['font-weight']) : '';
		$H1_font_line_height = !empty($h1_font['line-height']) ? esc_attr($h1_font['line-height']) : '';
		$H1_font_size        = !empty($h1_font['font-size']) ? esc_attr($h1_font['font-size']) : '';
	} else {
		$H1_font_family      = '';
		$H1_font_weight      = '';
		$H1_font_line_height = '';
		$H1_font_size        = '';
	}

	$h2_font = gt3_option('h2-font');
	if (!empty($h2_font)) {
		$H2_font_family      = !empty($h2_font['font-family']) ? esc_attr($h2_font['font-family']) : '';
		$H2_font_weight      = !empty($h2_font['font-weight']) ? esc_attr($h2_font['font-weight']) : '';
		$H2_font_line_height = !empty($h2_font['line-height']) ? esc_attr($h2_font['line-height']) : '';
		$H2_font_size        = !empty($h2_font['font-size']) ? esc_attr($h2_font['font-size']) : '';
	} else {
		$H2_font_family      = '';
		$H2_font_weight      = '';
		$H2_font_line_height = '';
		$H2_font_size        = '';
	}

	$h3_font = gt3_option('h3-font');
	if (!empty($h3_font)) {
		$H3_font_family      = !empty($h3_font['font-family']) ? esc_attr($h3_font['font-family']) : '';
		$H3_font_weight      = !empty($h3_font['font-weight']) ? esc_attr($h3_font['font-weight']) : '';
		$H3_font_line_height = !empty($h3_font['line-height']) ? esc_attr($h3_font['line-height']) : '';
		$H3_font_size        = !empty($h3_font['font-size']) ? esc_attr($h3_font['font-size']) : '';
	} else {
		$H3_font_family      = '';
		$H3_font_weight      = '';
		$H3_font_line_height = '';
		$H3_font_size        = '';
	}

	$h4_font = gt3_option('h4-font');
	if (!empty($h4_font)) {
		$H4_font_family      = !empty($h4_font['font-family']) ? esc_attr($h4_font['font-family']) : '';
		$H4_font_weight      = !empty($h4_font['font-weight']) ? esc_attr($h4_font['font-weight']) : '';
		$H4_font_line_height = !empty($h4_font['line-height']) ? esc_attr($h4_font['line-height']) : '';
		$H4_font_size        = !empty($h4_font['font-size']) ? esc_attr($h4_font['font-size']) : '';
	} else {
		$H4_font_family      = '';
		$H4_font_weight      = '';
		$H4_font_line_height = '';
		$H4_font_size        = '';
	}

	$h5_font = gt3_option('h5-font');
	if (!empty($h5_font)) {
		$H5_font_family      = !empty($h5_font['font-family']) ? esc_attr($h5_font['font-family']) : '';
		$H5_font_weight      = !empty($h5_font['font-weight']) ? esc_attr($h5_font['font-weight']) : '';
		$H5_font_line_height = !empty($h5_font['line-height']) ? esc_attr($h5_font['line-height']) : '';
		$H5_font_size        = !empty($h5_font['font-size']) ? esc_attr($h5_font['font-size']) : '';
	} else {
		$H5_font_family      = '';
		$H5_font_weight      = '';
		$H5_font_line_height = '';
		$H5_font_size        = '';
	}

	$h6_font = gt3_option('h6-font');
	if (!empty($h6_font)) {
		$H6_font_family         = !empty($h6_font['font-family']) ? esc_attr($h6_font['font-family']) : '';
		$H6_font_weight         = !empty($h6_font['font-weight']) ? esc_attr($h6_font['font-weight']) : '';
		$H6_font_line_height    = !empty($h6_font['line-height']) ? esc_attr($h6_font['line-height']) : '';
		$H6_font_size           = !empty($h6_font['font-size']) ? esc_attr($h6_font['font-size']) : '';
		$H6_font_color          = !empty($h6_font['color']) ? esc_attr($h6_font['color']) : '';
		$H6_font_letter_spacing = !empty($h6_font['letter-spacing']) ? esc_attr($h6_font['letter-spacing']) : '';
		$H6_font_text_transform = !empty($h6_font['text-transform']) ? esc_attr($h6_font['text-transform']) : '';
	} else {
		$H6_font_family         = '';
		$H6_font_weight         = '';
		$H6_font_line_height    = '';
		$H6_font_size           = '';
		$H6_font_color          = '';
		$H6_font_letter_spacing = '';
		$H6_font_text_transform = '';
	}

	$menu_font = gt3_option('menu-font');
	if (!empty($menu_font)) {
		$menu_font_family      = !empty($menu_font['font-family']) ? esc_attr($menu_font['font-family']) : '';
		$menu_font_weight      = !empty($menu_font['font-weight']) ? esc_attr($menu_font['font-weight']) : '';
		$menu_font_line_height = !empty($menu_font['line-height']) ? esc_attr($menu_font['line-height']) : '';
		$menu_font_size        = !empty($menu_font['font-size']) ? esc_attr($menu_font['font-size']) : '';
		$menu_font_letter_spacing = !empty($menu_font['letter-spacing']) ? esc_attr($menu_font['letter-spacing']) : '';
		$menu_font_text_transform = !empty($menu_font['text-transform']) ? esc_attr($menu_font['text-transform']) : '';
	} else {
		$menu_font_family      = '';
		$menu_font_weight      = '';
		$menu_font_line_height = '';
		$menu_font_size        = '';
		$menu_font_letter_spacing = '';
		$menu_font_text_transform = '';
	}
	// END HEADER TYPOGRAPHY

	// ELEMENTOR OPTIONS START
		/* Colors */
		$theme_color_elem = gt3_option('system_colors', 'theme-custom-color');
		$theme_color2_elem = gt3_option('system_colors', 'theme-custom-color2');
		$theme_color_start_elem = gt3_option('system_colors', 'theme-custom-color-start');
		$theme_color2_start_elem = gt3_option('system_colors', 'theme-custom-color2-start');
		$content_secondary_color_elem = gt3_option('system_colors', 'theme-secondary-color');
		$header_font_color_el = gt3_option('system_colors', 'theme-header-font-color');

		$bg_body_elem = gt3_option('body_background');
		$content_color_elem = gt3_option('body_color');

		/* Main Font */
		$theme_main_font_typo = gt3_option('body_typography');
		/* Secondary Font */
		$theme_secondary_font = gt3_option('system_typography', 'theme-secondary');
		$theme_secondary_font_typo = gt3_collect_options('typography', $theme_secondary_font);

		/* Headers Font */
		$headers_font = gt3_option('system_typography', 'theme-headers');
		$headers_font_typo = gt3_collect_options('typography',  $headers_font);
		$h1_font_elem = gt3_option('h1_typography');
		$h2_font_elem = gt3_option('h2_typography');
		$h3_font_elem = gt3_option('h3_typography');
		$h4_font_elem = gt3_option('h4_typography');
		$h5_font_elem = gt3_option('h5_typography');
		$h6_font_elem = gt3_option('h6_typography');

		/* Colors */
		$bg_body = (is_array($bg_body_elem) && !empty($bg_body_elem['color'])) ? $bg_body_elem['color'] : '#ffffff';

		$theme_color_start = (is_array($theme_color_start_elem) && !empty($theme_color_start_elem['color'])) ? $theme_color_start_elem['color'] : '#9289f1';

		$theme_color2_start = (is_array($theme_color2_start_elem) && !empty($theme_color2_start_elem['color'])) ? $theme_color2_start_elem['color'] : '#ff7426';

		$theme_color = (is_array($theme_color_elem) && !empty($theme_color_elem['color'])) ? $theme_color_elem['color'] : '#6254e7';

		$theme_color2 = (is_array($theme_color2_elem) && !empty($theme_color2_elem['color'])) ? $theme_color2_elem['color'] : '#ff7426';

		$content_color = !empty($content_color_elem) ? $content_color_elem : '#696687';

		$content_secondary_color = (is_array($content_secondary_color_elem) && !empty($content_secondary_color_elem['color'])) ? $content_secondary_color_elem['color'] : '#696687';

		$header_font_color = (is_array($header_font_color_el) && !empty($header_font_color_el['color'])) ? $header_font_color_el['color'] : '#3B3663';

		/* Main Font */
		$content_font_family = (is_array($theme_main_font_typo) && !empty($theme_main_font_typo['font_family'])) ? $theme_main_font_typo['font_family'] : '';
		$content_line_height = (is_array($theme_main_font_typo) && !empty($theme_main_font_typo['line_height']['size'])) ? $theme_main_font_typo['line_height']['size'] . $theme_main_font_typo['line_height']['unit'] : '';
		$content_font_size = (is_array($theme_main_font_typo) && !empty($theme_main_font_typo['font_size']['size'])) ? $theme_main_font_typo['font_size']['size'] . $theme_main_font_typo['font_size']['unit'] : '';
		$content_font_weight = (is_array($theme_main_font_typo) && !empty($theme_main_font_typo['font_weight'])) ? $theme_main_font_typo['font_weight'] : '';

		/* Secondary Font */
		$content_secondary_font_family = (is_array($theme_secondary_font_typo) && !empty($theme_secondary_font_typo['font_family'])) ? $theme_secondary_font_typo['font_family'] : '';
		$content_secondary_line_height = (is_array($theme_secondary_font_typo) && !empty($theme_secondary_font_typo['line_height']['size'])) ? $theme_secondary_font_typo['line_height']['size'] . $theme_secondary_font_typo['line_height']['unit'] : '';
		$content_secondary_font_size = (is_array($theme_secondary_font_typo) && !empty($theme_secondary_font_typo['font_size']['size'])) ? $theme_secondary_font_typo['font_size']['size'] . $theme_secondary_font_typo['font_size']['unit'] : '';
		$content_secondary_font_weight = (is_array($theme_secondary_font_typo) && !empty($theme_secondary_font_typo['font_weight'])) ? $theme_secondary_font_typo['font_weight'] : '';

		/* Headers Font */
		$header_font_family = (is_array($headers_font_typo) && !empty($headers_font_typo['font_family'])) ? $headers_font_typo['font_family'] : '';
		$header_font_weight = (is_array($headers_font_typo) && !empty($headers_font_typo['font_weight'])) ? $headers_font_typo['font_weight'] : '';
		/* H1 */
		$H1_font_family = (is_array($h1_font_elem) && !empty($h1_font_elem['font_family'])) ? $h1_font_elem['font_family'] : '';
		$H1_font_weight = (is_array($h1_font_elem) && !empty($h1_font_elem['font_weight'])) ? $h1_font_elem['font_weight'] : '';
		$H1_font_line_height = (is_array($h1_font_elem) && !empty($h1_font_elem['line_height']['size'])) ? $h1_font_elem['line_height']['size'] . $h1_font_elem['line_height']['unit'] : '';
		$H1_font_size = (is_array($h1_font_elem) && !empty($h1_font_elem['font_size']['size'])) ? $h1_font_elem['font_size']['size'] . $h1_font_elem['font_size']['unit'] : '';

		/* H2 */
		$H2_font_family = (is_array($h2_font_elem) && !empty($h2_font_elem['font_family'])) ? $h2_font_elem['font_family'] : '';
		$H2_font_weight = (is_array($h2_font_elem) && !empty($h2_font_elem['font_weight'])) ? $h2_font_elem['font_weight'] : '';
		$H2_font_line_height = (is_array($h2_font_elem) && !empty($h2_font_elem['line_height']['size'])) ? $h2_font_elem['line_height']['size'] . $h2_font_elem['line_height']['unit'] : '';
		$H2_font_size = (is_array($h2_font_elem) && !empty($h2_font_elem['font_size']['size'])) ? $h2_font_elem['font_size']['size'] . $h2_font_elem['font_size']['unit'] : '';

		/* H3 */
		$H3_font_family = (is_array($h3_font_elem) && !empty($h3_font_elem['font_family'])) ? $h3_font_elem['font_family'] : '';
		$H3_font_weight = (is_array($h3_font_elem) && !empty($h3_font_elem['font_weight'])) ? $h3_font_elem['font_weight'] : '';
		$H3_font_line_height = (is_array($h3_font_elem) && !empty($h3_font_elem['line_height'])) ? $h3_font_elem['line_height']['size'] . $h3_font_elem['line_height']['unit'] : '';
		$H3_font_size = (is_array($h3_font_elem) && !empty($h3_font_elem['font_size'])) ? $h3_font_elem['font_size']['size'] . $h3_font_elem['font_size']['unit'] : '';

		/* H4 */
		$H4_font_family = (is_array($h4_font_elem) && !empty($h4_font_elem['font_family'])) ? $h4_font_elem['font_family'] : '';
		$H4_font_weight = (is_array($h4_font_elem) && !empty($h4_font_elem['font_weight'])) ? $h4_font_elem['font_weight'] : '';
		$H4_font_line_height = (is_array($h4_font_elem) && !empty($h4_font_elem['line_height']['size'])) ? $h4_font_elem['line_height']['size'] . $h4_font_elem['line_height']['unit'] : '';
		$H4_font_size = (is_array($h4_font_elem) && !empty($h4_font_elem['font_size']['size'])) ? $h4_font_elem['font_size']['size'] . $h4_font_elem['font_size']['unit'] : '';

		/* H5 */
		$H5_font_family = (is_array($h5_font_elem) && !empty($h5_font_elem['font_family'])) ? $h5_font_elem['font_family'] : '';
		$H5_font_weight = (is_array($h5_font_elem) && !empty($h5_font_elem['font_weight'])) ? $h5_font_elem['font_weight'] : '';
		$H5_font_line_height = (is_array($h5_font_elem) && !empty($h5_font_elem['line_height']['size'])) ? $h5_font_elem['line_height']['size'] . $h5_font_elem['line_height']['unit'] : '';
		$H5_font_size = (is_array($h5_font_elem) && !empty($h5_font_elem['font_size']['size'])) ? $h5_font_elem['font_size']['size'] . $h5_font_elem['font_size']['unit'] : '';

		/* H6 */
		$H6_font_family = (is_array($h6_font_elem) && !empty($h6_font_elem['font_family'])) ? $h6_font_elem['font_family'] : '';
		$H6_font_weight = (is_array($h6_font_elem) && !empty($h6_font_elem['font_weight'])) ? $h6_font_elem['font_weight'] : '';
		$H6_font_line_height = (is_array($h6_font_elem) && !empty($h6_font_elem['line_height']['size'])) ? $h6_font_elem['line_height']['size'] . $h6_font_elem['line_height']['unit'] : '';
		$H6_font_size = (is_array($h6_font_elem) && !empty($h6_font_elem['font_size']['size'])) ? $h6_font_elem['font_size']['size'] . $h6_font_elem['font_size']['unit'] : '';

	// ELEMENTOR OPTIONS END
	// Modern Shop
	if ((gt3_option('modern_shop') == '1' || true === gt3_option('modern_shop')) && function_exists('gt3_has_shop_on_page') && gt3_has_shop_on_page()) {
		$content_color = esc_attr(gt3_option('theme-modern_content-color'));
		$header_font_color = esc_attr(gt3_option('theme-modern_header-color'));
		$theme_color  = esc_attr(gt3_option('theme-modern_custom-color'));

			/* Modern Shop Main Font */
			$theme_modern_shop_font = gt3_option('system_typography', 'theme-modern-shop-main');
			$theme_modern_shop_font_typo = gt3_collect_options('typography',  $theme_modern_shop_font);



			$content_font_family = (is_array($theme_modern_shop_font_typo) && !empty($theme_modern_shop_font_typo['font_family'])) ? $theme_modern_shop_font_typo['font_family'] : '';
			$content_line_height = (is_array($theme_modern_shop_font_typo) && !empty($theme_modern_shop_font_typo['line_height']['size'])) ? $theme_modern_shop_font_typo['line_height']['size'] . $theme_modern_shop_font_typo['line_height']['unit'] : '';
			$content_font_size = (is_array($theme_modern_shop_font_typo) && !empty($theme_modern_shop_font_typo['font_size']['size'])) ? $theme_modern_shop_font_typo['font_size']['size'] . $theme_modern_shop_font_typo['font_size']['unit'] : '';
			$content_font_weight = (is_array($theme_modern_shop_font_typo) && !empty($theme_modern_shop_font_typo['font_weight'])) ? $theme_modern_shop_font_typo['font_weight'] : '';

			/* Modern Shop Headers Font */
			$theme_modern_shop_headers = gt3_option('system_typography', 'theme-modern-shop-headers');
			$theme_modern_shop_headers_typo = gt3_collect_options('typography', $theme_modern_shop_headers);

			$header_font_family = (is_array($theme_modern_shop_headers_typo) && !empty($theme_modern_shop_headers_typo['font_family'])) ? $theme_modern_shop_headers_typo['font_family'] : '';
			$header_font_weight = (is_array($theme_modern_shop_headers_typo) && !empty($theme_modern_shop_headers_typo['font_weight'])) ? $theme_modern_shop_headers_typo['font_weight'] : '';



	}

	if (!class_exists('Elementor\Plugin')) {
		$args = array();
		if (!empty($content_font_family)) {
			$args[] = 'family='.$content_font_family.':wght@'.$content_font_weight;
		}
		if (!empty($header_font_family)) {
			$args[] = 'family='.$header_font_family.':wght@'.$header_font_weight;
		}
		if (count($args)) {
			$args[] = 'display=swap';
			$gt3_theme_fonts_url = 'https://fonts.googleapis.com/css2?'.urlencode(implode('&', $args));
			wp_enqueue_style('gt3_fonts-font', $gt3_theme_fonts_url);
		}
	}

	$custom_css .= '
    /* Custom CSS */    
    body,
    body .widget .yit-wcan-select-open,
    body .widget-hotspot,
    body div[id*="ajaxsearchlitesettings"].searchsettings form fieldset legend,
    span.elementor-drop-cap span.elementor-drop-cap-letter,
    input[type="date"],
    input[type="email"],
    input[type="number"],
    input[type="password"],
    input[type="search"],
    input[type="tel"],
    input[type="text"],
    input[type="url"],
    select,
    textarea,
    .wrapper_404 .gt3_module_button a,
    .mc_form_inside #mc_signup_submit,
    .mc4wp-form-fields input[type="submit"],
    .elementor-counter .elementor-counter-number-suffix {
        font-family:' . $content_font_family . ';
    }
    input[type="date"],
    input[type="email"],
    input[type="number"],
    input[type="password"],
    input[type="search"],
    input[type="tel"],
    input[type="text"],
    input[type="url"],
    select,
    textarea {
        font-weight:' . $content_font_weight . ';
    }
    body {
        ' . (!empty($bg_body) ? 'background:' . $bg_body . ';' : '') . '
        font-size:' . $content_font_size . ';
        line-height:' . $content_line_height . ';
        font-weight:' . $content_font_weight . ';
        color: ' . $content_color . ';
    }
    .elementor-widget-gt3-core-team .module_team.type2 .item-team-member .item_wrapper,
    .elementor-widget-gt3-core-team .module_team.type2 .item-team-member:nth-child(even) .item_wrapper,
    .gt3pg_pro_FSSlider .gt3pg_pro_gallery_wrap {
        ' . (!empty($bg_body) ? 'background:' . $bg_body . ';' : '') . '
    }
    p {
        line-height: 1.5;
    }
    /* Secondaty Fonts */
    .secondary {
        font-family:' . $content_secondary_font_family . ';
        font-size:' . $content_secondary_font_size . ';
        line-height:' . $content_secondary_line_height . ';
        '.(!empty($content_secondary_font_weight) ? 'font-weight: ' . $content_secondary_font_weight . ';' : '' ).'
        color: ' . $content_secondary_color . ';
    }
    /* Custom Fonts */
    .module_team .team_info,
    h1,
    h2,
    h3,
    h4,
    h5,
    h6,
    .main_wrapper .gt3_search_form:before,
    .widget_search .gt3_search_form label,
    .main_wrapper .gt3_search_form label,
    .main_wrapper .sidebar-container .widget_categories ul li > a:hover:before,
    .main_wrapper .sidebar-container .widget_product_categories ul li > a:hover:before,
    .main_wrapper .sidebar-container .widget_layered_nav ul li > a:hover:before,
    .logged-in-as a:hover,
    .sidebar-container .widget.widget_posts .recent_posts .post_title a,
    .elementor-widget-gt3-core-tabs .ui-tabs-nav .ui-state-default a,
    .single_prev_next_posts .gt3_post_navi:after,
    .elementor-widget-gt3-core-portfolio .portfolio_wrapper.hover_type6 .text_wrap .title,
    .gt3_price_item-elementor .gt3_item_cost_wrapper h3,
    .sidebar .widget .widget-title,
    .gt3_single_team_header .gt3_team_title_position,
    .gt3_pricebox_module_wrapper.type2 .gt3_price_item-cost-elementor,
    .gt3_modern_shop.woocommerce div.product form.cart .variations th .woo-selected-variation-item-name {
        color: ' . $header_font_color . ';
    }
    .search-results .blogpost_title a {
        color: ' . $header_font_color . ' !important;
    }
    .search-results .blogpost_title a:hover,
    .elementor-widget-gt3-core-TestimonialsLite .slick-arrow:hover {
        color: ' . $theme_color . ' !important;
    }
    .gt3_icon_box__icon--number,
    h1,
    h2,
    h3,
    h4,
    h5,
    h6,
    .strip_template .strip-item a span,
    .column1 .item_title a,
    .index_number,
    .price_item_btn a,
    .shortcode_tab_item_title,
    .gt3_twitter .twitt_title,
    .elementor-widget-gt3-core-counter .counter,
    .gt3_dropcaps,
    .dropcap,
    .single_prev_next_posts .gt3_post_navi:after,
    .gt3_single_team_header .gt3_team_title_position {
        font-family: ' . $header_font_family . ';
        font-weight: ' . $header_font_weight . ';
    }
    .gt3-page-title .page_title_meta.cpt_portf * {
        font-weight: inherit;
    }
    .format-video .gt3_video__play_button:hover,
    .widget .calendar_wrap tbody td > a:before,
    .elementor-widget-gt3-core-team .module_team .team_icons_wrapper .team-icons a:before,
    .elementor-widget-gt3-core-teamcarousel .module_team .team_icons_wrapper .team-icons a:before,
    p.form-submit button#submit,
    .woocommerce .gt3-products-bottom nav.woocommerce-pagination ul li .page-numbers:hover,
    .woocommerce .gt3-products-bottom nav.woocommerce-pagination ul li .page-numbers.current,
    div.slick-slider ul.slick-dots li.slick-active,
    div.slick-slider ul.slick-dots li,
    .gt3_default_footer {
        background: ' . $theme_color . ';
    }
    h1,
    .elementor-widget-heading h1.elementor-heading-title,
    .logo_container .site-title {
        ' . (!empty($H1_font_family) ? 'font-family:' . $H1_font_family . ';' : '') . '
        ' . (!empty($H1_font_weight) ? 'font-weight:' . $H1_font_weight . ';' : '') . '
        ' . (!empty($H1_font_size) ? 'font-size:' . $H1_font_size . ';' : '') . '
        ' . (!empty($H1_font_line_height) ? 'line-height:' . $H1_font_line_height . ';' : '') . '
    }
    h2,
    .elementor-widget-heading h2.elementor-heading-title,
    .elementor-widget-gt3-core-blog .blogpost_title {
        ' . (!empty($H2_font_family) ? 'font-family:' . $H2_font_family . ';' : '') . '
        ' . (!empty($H2_font_weight) ? 'font-weight:' . $H2_font_weight . ';' : '') . '
        ' . (!empty($H2_font_size) ? 'font-size:' . $H2_font_size . ';' : '') . '
        ' . (!empty($H2_font_line_height) ? 'line-height:' . $H2_font_line_height . ';' : '') . '
    }
    h3,
    .elementor-widget-heading h3.elementor-heading-title,
    #customer_login h2,
    .sidepanel .title{
        ' . (!empty($H3_font_family) ? 'font-family:' . $H3_font_family . ';' : '') . '
        ' . (!empty($H3_font_weight) ? 'font-weight:' . $H3_font_weight . ';' : '') . '
        ' . (!empty($H3_font_size) ? 'font-size:' . $H3_font_size . ';' : '') . '
        ' . (!empty($H3_font_line_height) ? 'line-height:' . $H3_font_line_height . ';' : '') . '
    }
    h4,
    .elementor-widget-heading h4.elementor-heading-title {
        ' . (!empty($H4_font_family) ? 'font-family:' . $H4_font_family . ';' : '') . '
        ' . (!empty($H4_font_weight) ? 'font-weight:' . $H4_font_weight . ';' : '') . '
        ' . (!empty($H4_font_size) ? 'font-size:' . $H4_font_size . ';' : '') . '
        ' . (!empty($H4_font_line_height) ? 'line-height:' . $H4_font_line_height . ';' : '') . '
    }
    h5,
    .elementor-widget-heading h5.elementor-heading-title {
        ' . (!empty($H5_font_family) ? 'font-family:' . $H5_font_family . ';' : '') . '
        ' . (!empty($H5_font_weight) ? 'font-weight:' . $H5_font_weight . ';' : '') . '
        ' . (!empty($H5_font_size) ? 'font-size:' . $H5_font_size . ';' : '') . '
        ' . (!empty($H5_font_line_height) ? 'line-height:' . $H5_font_line_height . ';' : '') . '
    }
    h6,
    .elementor-widget-heading h6.elementor-heading-title {
        ' . (!empty($H6_font_family) ? 'font-family:' . $H6_font_family . ';' : '') . '
        ' . (!empty($H6_font_weight) ? 'font-weight:' . $H6_font_weight . ';' : '') . '
        ' . (!empty($H6_font_size) ? 'font-size:' . $H6_font_size . ';' : '') . '
        ' . (!empty($H6_font_line_height) ? 'line-height:' . $H6_font_line_height . ';' : '') . '
        ' . (!empty($H6_font_color) ? 'color:' . $H6_font_color . ';' : '') . '
        ' . (!empty($H6_font_letter_spacing) ? 'letter-spacing:' . $H6_font_letter_spacing . ';' : '') . '
        ' . (!empty($H6_font_text_transform) ? 'text-transform:' . $H6_font_text_transform . ';' : '') . '
    }
    
	.woocommerce-MyAccount-navigation ul li a,
    .diagram_item .chart,
    .item_title a ,
    .contentarea ul,
    .blog_post_media--link .blog_post_media__link_text p,
    .woocommerce-LostPassword a:hover{
        color:' . $header_font_color . ';
    }

    .gt3_module_button a,
    .learn_more,
    .gt3_custom_tooltip:before,
    .gt3_custom_tooltip:after,
    .elementor-widget-gt3-core-TestimonialsLite .testimonials-text {
        font-family:' . $content_secondary_font_family . ';
        '.(!empty($content_secondary_font_weight) ? 'font-weight: ' . $content_secondary_font_weight . ';' : '' ).'
    }

    /* Theme color */
    a,
    .calendar_wrap thead,
    .gt3_practice_list__image-holder i,
    .load_more_works:hover,
    .copyright a:hover,
    .price_item .items_text ul li:before,
    .price_item.most_popular .item_cost_wrapper h3,
    .gt3_practice_list__title a:hover,
    .listing_meta,
    .ribbon_arrow,
    .flow_arrow,
    ol > li:before,
    .main_wrapper #main_content ul.gt3_list_line li:before,
    .main_wrapper .elementor-section ul.gt3_list_line li:before,
    .main_wrapper #main_content ul.gt3_list_disc li:before,
    .main_wrapper .elementor-section ul.gt3_list_disc li:before,
    .main_wrapper .sidebar-container .widget_categories ul > li.current-cat > a,
    .single_prev_next_posts a:hover .gt3_post_navi:after,
    .gt3_practice_list__link:before,
    .content-container ul > li:before,
    .gt3_styled_list .gt3_list__icon:before,
    .load_more_works,
    .woocommerce ul.products li.product .woocommerce-loop-product__title:hover,
    .woocommerce ul.cart_list li a:hover,
    ul.gt3_list_disc li:before,
	.woocommerce-MyAccount-navigation ul li a:hover,
	.elementor-widget-gt3-core-portfolio .portfolio_wrapper.hover_type6 .text_wrap:hover .title,
    .elementor-widget-gt3-core-team .module_team.type3 .team_link a:hover,
    .elementor-widget-gt3-core-team .module_team .team_title__text a:hover,
    .elementor-element-custom_color a:hover,    
    .woocommerce ul.products li.product:hover .price ins,
    .gt3_blockquote .gt3_blockquote__quote_icon,
    .gt3_left_rounded div.slick-slider ul.slick-dots {
		color: ' . $theme_color . ';
	}
	.gt3_practice_list__link:before,
	.load_more_works,
    .woocommerce ul.products:not(.list) li.product .gt3_woocommerce_open_control_tag div a:before,
    .woocommerce ul.products:not(.list) li.product .gt3_woocommerce_open_control_tag .added_to_cart:hover,
    .woocommerce ul.products:not(.list) li.product .gt3_woocommerce_open_control_tag div a:hover,
    .blog_post_media--quote .quote_text:before,
    .blog_post_media__link_text:before,
    .woocommerce .widget_shopping_cart .buttons a.button.checkout.wc-forward,
    .woocommerce.widget_shopping_cart .buttons a.button.checkout.wc-forward,
    .woocommerce div.product form.cart .button,
    .woocommerce #respond input#submit,
    .woocommerce a.button,
    .woocommerce input.button,
    .woocommerce #respond input#submit:hover,
    .woocommerce a.button:hover,
    .woocommerce input.button:hover,
    ul.pagerblock li a:hover,
    ul.pagerblock li a.current{
        background-color: ' . $theme_color . ';
    }
    .comment-reply-link:hover,
    .main_wrapper .gt3_product_list_nav li a:hover {
        color: ' . $theme_color . ';
    }
    .calendar_wrap caption,
    .widget .calendar_wrap table td#today:before {
        background: ' . $theme_color . ';
    }
    div:not(.packery_wrapper) .blog_post_preview .listing_meta a:hover,
    .single_blogpost_title_content .listing_meta a:hover,
    .blog_post_media--quote .quote_text a:hover {
        color: ' . $theme_color . ';
    }
    .blogpost_title a:hover,
    .apps_style_price .elementor-widget-gt3-core-pricebox .gt3_pricebox_module_wrapper.type5 .price_button-elementor .shortcode_button:hover {
        color: ' . $theme_color . ' !important;
    }
    .gt3_icon_box__link a:before,
    .gt3_icon_box__link a:before,
    .stripe_item-divider,
    .apps_subscribe #mc_signup_submit:hover,
    .apps_subscribe .mc4wp-form-fields .mc_signup_submit input[type="submit"]:hover {
        background-color: ' . $theme_color . ';
    }
    .single-member-page .member-icon:hover,
    .single-member-page .team-link:hover,
    .sidebar .widget_nav_menu .menu .menu-item > a:hover,
    .widget.widget_recent_entries > ul > li:hover a,
    .gt3_widget > ul > li:hover a,  
    #main_content ul.wp-block-archives li > a:hover,
    #main_content ul.wp-block-categories li > a:hover,
    #main_content ul.wp-block-latest-posts li > a:hover,
    #respond #commentform p[class*="comment-form-"] > label.gt3_onfocus,
    .comment-notes .required,
    #cancel-comment-reply-link,
    .widget_archive ul li:hover .post_count{
        color: ' . $theme_color . ';
    }

    /* menu fonts */
    .main-menu>.gt3-menu-categories-title,
    .main-menu>ul,
    .main-menu>div>ul,
    .column_menu>ul,
    .column_menu>.gt3-menu-categories-title,
    .column_menu>div>ul {
        font-family:' . esc_attr($menu_font_family) . ';
        font-weight:' . esc_attr($menu_font_weight) . ';
        line-height:' . esc_attr($menu_font_line_height) . ';
        font-size:' . esc_attr($menu_font_size) . ';
        '.(!empty($menu_font_letter_spacing) ? 'letter-spacing: ' . esc_attr($menu_font_letter_spacing) . ';' : '' ).'
        '.(!empty($menu_font_text_transform) ? 'text-transform: ' . esc_attr($menu_font_text_transform) . ';' : '' ).'
    }

    input::-webkit-input-placeholder,
    textarea::-webkit-input-placeholder,
    .sidebar-container .widget.widget_posts .recent_posts .listing_meta span{
        color: ' . $content_color . ';
    }
    input:-moz-placeholder,
    textarea:-moz-placeholder {
        color: ' . $content_color . ';
    }
    input::-moz-placeholder,
    textarea::-moz-placeholder {
        color: ' . $content_color . ';
    }
    input:-ms-input-placeholder,
    textarea:-ms-input-placeholder {
        color: ' . $content_color . ';
    }

    /* widgets */
    body div[id*=\'ajaxsearchlitesettings\'].searchsettings fieldset .label:hover,
    body div[id*=\'ajaxsearchlite\'] .probox .proclose:hover,
    .module_team.type2 .team_title__text,
    .widget.widget_rss > ul > li a,
    .woocommerce ul.cart_list li .quantity,
    .woocommerce ul.product_list_widget li .quantity,
    .wpcf7-form label,
    blockquote {
        color: ' . $header_font_color . ';
    }

    /* blog */
    .countdown-period,
    .gt3-page-title_default_color_a .gt3-page-title__content .gt3_breadcrumb a,
    .gt3-page-title_default_color_a .gt3-page-title__content .gt3_breadcrumb .gt3_pagination_delimiter,
    .module_team.type2 .team-positions,
    .widget.widget_recent_entries > ul > li a,
    .gt3_widget > ul > li a,
    #main_content ul.wp-block-archives li > a,
    #main_content ul.wp-block-categories li > a,
    #main_content ul.wp-block-latest-posts li > a,
    .sidebar .widget_nav_menu .menu .menu-item > a,
    .blog_post_info,
    .likes_block.already_liked .icon,
    .likes_block.already_liked:hover .icon,
    .gt3_form label,
    .wpcf7-form .label,
    .wrapper_404 label,
    .widget .gt3_search_form label,
    #respond #commentform p[class*="comment-form-"] > label,
    .comment_author_says span,
    .search_form .search_text,
    .widget_search .search_form .search_submit,
    .widget_search .search_form:before,
    body .gt3_module_related_posts .blog_post_preview .listing_meta,
    .widget_archive ul li .post_count{
        color: ' . $content_color . ';
    }
    div:not(.packery_wrapper) .blog_post_preview .listing_meta,
    .single_blogpost_title_content .listing_meta {
        color: rgba('.gt3_HexToRGB($content_color).');
    }
    .woocommerce ul.products li.product .price del .amount{
        color: rgba('.gt3_HexToRGB($content_color).', 0.65);
    }
    .blogpost_title i,
    .widget.widget_recent_comments > ul > li a:hover,
    .widget.widget_rss > ul > li:hover a,
    .sidebar-container .widget.widget_posts .recent_posts .post_title a:hover,
    .comment_info a:hover,
    .gt3_module_button_list a,    
    .widget.widget_text ul li:before,
    .widget.widget_product_categories ul li:before,
    .widget.widget_nav_menu ul li:before,
    .widget.widget_archive ul li:before,
    .widget.widget_pages ul li:before,
    .widget.widget_categories ul li:before,
    .widget.widget_recent_entries ul li:before,
    .widget.widget_meta ul li:before,
    .widget.widget_recent_comments ul li:before,
    .widget.main_wrapper ul li:before,
    ul.wp-block-archives li:before,
    ul.wp-block-categories li:before,
    ul.wp-block-latest-posts li:before,
    .comment-reply-link,
    .main_wrapper .sidebar-container .widget_categories ul > li:hover > a,
    .widget_categories ul li:hover .post_count{
        color: ' . $theme_color . ';
    }
    .prev_next_links .title,
    .widget.widget_recent_comments > ul > li a {
        color: ' . $header_font_color . ';
    }

    .gt3_module_title .carousel_arrows a:hover span,
    .stripe_item:after,
    .packery-item .packery_overlay,
    .ui-datepicker .ui-datepicker-buttonpane button.ui-state-hover{
        background: ' . $theme_color . ';
    }
    .elementor-widget-gt3-core-pricebox .price_button-elementor a,
    .elementor-widget-gt3-core-pricebox .price_button-elementor a:hover,
    button:hover,
    .ui-datepicker .ui-datepicker-buttonpane button.ui-state-hover,
    .woocommerce ul.products li.product .gt3_woocommerce_open_control_tag_bottom div a,
    .woocommerce ul.products li.product .gt3_woocommerce_open_control_tag_bottom div a:hover,
    .woocommerce-account .woocommerce-MyAccount-content .woocommerce-message--info .button,
    .woocommerce-account .woocommerce-MyAccount-content .woocommerce-message--info .button:hover,
    div.slick-slider ul.slick-dots li.slick-active,
    div.slick-slider ul.slick-dots li {
        border-color: ' . $theme_color . ';
    }
    .gt3_module_title .carousel_arrows a:hover span:before {
        border-color: ' . $theme_color . ';
    }
    .gt3_module_title .carousel_arrows a span,
    .elementor-slick-slider .slick-slider .slick-prev:after,
    .elementor-slick-slider .slick-slider .slick-next:after{
        background: ' . $header_font_color . ';
    }
    .gt3_module_title .carousel_arrows a span:before {
        border-color: ' . $header_font_color . ';
    }
    .post_share_block:hover > a,
    .woocommerce ul.products li.product .gt3_woocommerce_open_control_tag_bottom div a:hover,
    .woocommerce ul.products.list li.product .gt3_woocommerce_open_control_tag div a:hover:before, 
    .woocommerce ul.products li.product .gt3_woocommerce_open_control_tag_bottom div a:hover:before,
    .single-product.woocommerce div.product .product_meta a:hover,
    .woocommerce div.product span.price,
    .likes_block:hover .icon,
    .woocommerce .gt3-pagination_nav nav.woocommerce-pagination ul li a.prev:hover,
    .woocommerce .gt3-pagination_nav nav.woocommerce-pagination ul li a.next:hover,
    .woocommerce .gt3-pagination_nav nav.woocommerce-pagination ul li a.gt3_show_all:hover,
    .woocommerce div.product div.images div.woocommerce-product-gallery__trigger:hover{
        color: ' . $theme_color . ';
    }
    .gt3_practice_list__filter {
        color: ' . $header_font_color . ';
    }

    body:not(.gt3_modern_shop) ul.products:not(.list) li.product:hover .gt3_woocommerce_open_control_tag div a{
        background: ' . $header_font_color . ';
    }

    .gt3_module_title .external_link .learn_more {
        line-height:' . $content_line_height . ';
    }



    .blog_post_media__link_text a:hover,
    h3#reply-title a,
    .comment_author_says a:hover,
    .dropcap,
    .gt3_custom_text a,
    .gt3_custom_button i {
        color: ' . $theme_color . ';
    }
    .main_wrapper #main_content ul[class*="gt3_list_"] li:before,
    .single .post_tags > span,
    h3#reply-title a:hover,
    .comment_author_says,
    .comment_author_says a {
        color: ' . $header_font_color . ';
    }

    ::-moz-selection{background: ' . $theme_color . '; ' . (!empty($bg_body) ? 'color:' . $bg_body . ';' : '') . '}
    ::selection{background: ' . $theme_color . '; ' . (!empty($bg_body) ? 'color:' . $bg_body . ';' : '') . '}
    ';

	$custom_css           .= '
    .gt3_practice_list__overlay:before {
        background-color: ' . $theme_color . ';
    }

    @media only screen and (max-width: 767px){
        .gt3-hotspot-shortcode-wrapper .gt3_tooltip{
            background-color: ' . $bg_body . ';
        }
    }
    ';

	$id = gt3_get_queried_object_id();

	$customize_shop_title = gt3_option("customize_shop_title");
	if (class_exists( 'WooCommerce' ) && (is_woocommerce() || is_cart() || is_checkout() || is_account_page()) && ($customize_shop_title == '1' || true === $customize_shop_title)) {
		$page_title_overlay_color = gt3_option( 'shop_title_overlay_color' );
		$page_title_bg_color = gt3_option("shop_title_bg_color");
	}else{
		$page_title_overlay_color = gt3_option( 'page_title_overlay_color' );
		$page_title_bg_color = gt3_option("page_title_bg_color");
	}
	if (class_exists( 'RWMB_Loader' ) && $id !== 0) {
		$mb_page_title_overlay_color = rwmb_meta('mb_page_title_overlay_color', array(), $id);
		$page_title_overlay_color = !empty($mb_page_title_overlay_color) ? $mb_page_title_overlay_color : $page_title_overlay_color;
	}

	if (!empty($page_title_overlay_color)) {
		$custom_css .= '.gt3-page-title_has_img_bg:before{
            background-image: radial-gradient(at center center, rgba('.gt3_HexToRGB($page_title_overlay_color).', 0.3) 0%, rgba('.gt3_HexToRGB($page_title_overlay_color).', 1) 85%);
        }';
	}

	// Woocommerce
	$custom_css .= '
	.quantity-spinner.quantity-up:hover,
    .quantity-spinner.quantity-down:hover,
    .woocommerce .gt3-products-header .gridlist-toggle:hover,
    .elementor-widget-gt3-core-accordion .item_title .ui-accordion-header-icon:before,
    .elementor-element.elementor-widget-gt3-core-accordion .accordion_wrapper .item_title.ui-accordion-header-active.ui-state-active,
    .elementor-widget-gt3-core-accordion .accordion_wrapper .item_title:hover{
        color: ' . $theme_color . ';
    }
    .woocommerce #respond input#submit:hover,
    .woocommerce #respond input#submit.alt:hover,
    .woocommerce #reviews button.button:hover,
    .woocommerce #reviews input.button:hover,
    .woocommerce #respond input#submit.disabled:hover,
    .woocommerce #respond input#submit:disabled:hover,
    .woocommerce #respond input#submit:disabled[disabled]:hover,
    .woocommerce a.button.disabled:hover,
    .woocommerce a.button:disabled:hover,
    .woocommerce a.button:disabled[disabled]:hover,
    .woocommerce input.button.disabled:hover,
    .woocommerce input.button:disabled:hover,
    .woocommerce input.button:disabled[disabled]:hover{
        border-color: ' . $theme_color . ';
        background-color: ' . $theme_color . ';
    }
    .woocommerce #respond input#submit.alt.disabled:hover,
    .woocommerce #respond input#submit.alt:disabled:hover,
    .woocommerce #respond input#submit.alt:disabled[disabled]:hover,
    .woocommerce input.button.alt.disabled:hover,
    .woocommerce input.button.alt:disabled:hover,
    .woocommerce input.button.alt:disabled[disabled]:hover,
	.woocommerce div.product form.cart .qty,
    .gt3-page-title__content .breadcrumbs,
    .sidebar .widget .widget-title,
    blockquote cite,
    .woocommerce-cart .cart_totals table.shop_table tr th,
    .woocommerce-cart .cart_totals table.shop_table tr td span.woocommerce-Price-amount.amount,
    .sidebar-container .widget.widget_posts .recent_posts .listing_meta span,
    .blog_post_preview .listing_meta span,
    .gt3_pricebox_module_wrapper .gt3_price_item-cost-elementor,
    .logo_container .site-title,
    .elementor-widget-gt3-core-blogboxed .gt3_module_blogboxed.module_type1 .item_wrapper span.post_date {
		font-family: ' . $header_font_family . ';
	}
	.quantity-spinner.quantity-up:hover,
	.quantity-spinner.quantity-down:hover,
	.woocommerce .gt3-products-header .gridlist-toggle:hover,
    .elementor-widget-gt3-core-accordion .item_title .ui-accordion-header-icon:before,
    .elementor-element.elementor-widget-gt3-core-accordion .accordion_wrapper .item_title.ui-accordion-header-active.ui-state-active{
		color: ' . $theme_color . ';
	}
	.woocommerce #respond input#submit:hover,
	.woocommerce #respond input#submit.alt:hover,
	.woocommerce #reviews a.button:hover,
	.woocommerce #reviews button.button:hover,
	.woocommerce #reviews input.button:hover,
	.woocommerce #respond input#submit.disabled:hover,
	.woocommerce #respond input#submit:disabled:hover,
	.woocommerce #respond input#submit:disabled[disabled]:hover,
	.woocommerce a.button.disabled:hover,
	.woocommerce a.button:disabled:hover,
	.woocommerce a.button:disabled[disabled]:hover,
	.woocommerce input.button.disabled:hover,
	.woocommerce input.button:disabled:hover,
	.woocommerce input.button:disabled[disabled]:hover{
		border-color: ' . $theme_color . ';
		background-color: ' . $theme_color . ';
	}
	.woocommerce #respond input#submit.alt.disabled,
	.woocommerce #respond input#submit.alt:disabled,
	.woocommerce #respond input#submit.alt:disabled[disabled],
	.woocommerce a.button.alt.disabled,
	.woocommerce a.button.alt:disabled,
	.woocommerce a.button.alt:disabled[disabled],
	.woocommerce button.button.alt.disabled,
	.woocommerce button.button.alt:disabled,
	.woocommerce button.button.alt:disabled[disabled],
	.woocommerce input.button.alt.disabled,
	.woocommerce input.button.alt:disabled,
	.woocommerce input.button.alt:disabled[disabled]{
		color: ' . $theme_color . ';
	}
	.woocommerce #respond input#submit.alt.disabled:hover,
	.woocommerce #respond input#submit.alt:disabled:hover,
	.woocommerce #respond input#submit.alt:disabled[disabled]:hover,
	.woocommerce a.button.alt.disabled:hover,
	.woocommerce a.button.alt:disabled:hover,
	.woocommerce a.button.alt:disabled[disabled]:hover,
	.woocommerce input.button.alt.disabled:hover,
	.woocommerce input.button.alt:disabled:hover,
	.woocommerce input.button.alt:disabled[disabled]:hover{
        background-color: ' . $theme_color . ';
        border-color: ' . $theme_color . ';
    }
    .woocommerce table.shop_table .product-quantity .qty.allotted,
    .woocommerce div.product form.cart .qty.allotted,
    .image_size_popup .close,
    #yith-quick-view-content .product_meta,
    .single-product.woocommerce div.product .product_meta,
    .woocommerce div.product form.cart .variations td,
    .woocommerce .widget_shopping_cart .total,
    .woocommerce.widget_shopping_cart .total,
    .woocommerce table.shop_table thead th,
    .woocommerce table.woocommerce-checkout-review-order-table tfoot td .woocommerce-Price-amount,
    .gt3_custom_tooltip,
    .woocommerce-cart .cart_totals table.shop_table tr th,
    .wpcf7-form .gt3_contactform_home6 input[type="submit"]{
        color: ' . $header_font_color . ';
    }
    .woocommerce ul.products li.product .price,
    .widget.widget_product_categories ul li:hover:before,
    .woocommerce ul.product_list_widget li .price,
    .woocommerce ul.cart_list li .quantity,
    body ul.cart_list li .quantity,
    body ul.product_list_widget li .quantity,
    .gt3_widget .quantity span.woocommerce-Price-amount.amount,
    .woocommerce-page ul.products li.product span.price,
    span.woocommerce-Price-amount.amount,
    .gt3_module_button_list a:hover,
    #back_to_top.show,
    body .elementor-widget-gt3-core-blogboxed .gt3_module_blogboxed.module_type1 .item_wrapper .blogboxed_content .gt3_post_footer .gt3_module_button_list a,
    .wpcf7-form .gt3_contactform_home6 input[type="submit"]:hover,
    .elementor-widget-gt3-core-pricebox .gt3_pricebox_module_wrapper.type4 .price_button-elementor a{
        color: ' . $theme_color2 . ';
    }
    .elementor-widget-gt3-core-pricebox.active-package-yes .gt3_pricebox_module_wrapper.type4 .price_button-elementor a.shortcode_button,
    .elementor-widget-gt3-core-pricebox.active-package-yes .gt3_pricebox_module_wrapper.type4 .price_button-elementor .shortcode_button{
        color: ' . $theme_color2 . ' !important;
    }
    .elementor-widget-gt3-core-blogboxed .gt3_module_blogboxed.module_type1 .blogboxed_content .gt3_module_button_list a,
    .elementor-widget-gt3-core-blogboxed .gt3_module_blogboxed.module_type1 .blogboxed_content.without_img_block .gt3_module_button_list a {
        color: ' . $theme_color . ' !important;
    }
    .gt3_price_item-elementor .label_text span{
        background: ' . $header_font_color . ';
    }
    .gt3_custom_tooltip:before,
    .gt3_pagination_delimiter:after,
    .woocommerce .woocommerce-breadcrumb span.gt3_pagination_delimiter:before,
    blockquote:before,
    .blog_post_media--quote .quote_text:before,
    .blog_post_media__link_text:before,
    .format-video .gt3_video__play_button,
    #back_to_top.show:hover{
        background: ' . $theme_color2 . ';
    }
    .active-package-yes.elementor-widget-gt3-core-pricebox .gt3_pricebox_module_wrapper.type1 .gt3_price_item-cost-elementor .inner_circle,
    #back_to_top,
    #back_to_top:hover,
    #back_to_top.show:hover{
        border-color: ' . $theme_color2 . ';
    }
    .gt3_custom_tooltip:after {
        border-color: ' . $theme_color2 . ' transparent transparent transparent;
    }
    .woocommerce button.button.alt:hover,
    .woocommerce .woocommerce-message a.button:hover{
        background-color: transparent;
    }
    #yith-quick-view-content .product_meta a,
    #yith-quick-view-content .product_meta .sku,
    .single-product.woocommerce div.product .product_meta a,
    .single-product.woocommerce div.product .product_meta .sku,
    .select2-container--default .select2-selection--single .select2-selection__rendered,
    .woocommerce ul.products li.product .woocommerce-loop-product__title,
    .search_result_form .search_form label,
    .woocommerce .star-rating::before,
    .woocommerce #reviews p.stars span a,
    .woocommerce p.stars span a:hover~a::before,
    .woocommerce p.stars.selected span a.active~a::before,
    .select2-container--default .select2-results__option--highlighted[aria-selected],
    .select2-container--default .select2-results__option--highlighted[data-selected],
    .cart_list.product_list_widget a.remove,
    .elementor-widget-gt3-core-accordion .accordion_wrapper .item_title,
    .woocommerce .gt3-pagination_nav nav.woocommerce-pagination ul li .gt3_pagination_delimiter,
	.woocommerce .woocommerce-widget-layered-nav-list .woocommerce-widget-layered-nav-list__item span.count,
    .widget_categories ul li .post_count,
    .woocommerce .gt3-products-bottom nav.woocommerce-pagination ul li .page-numbers,
    .woocommerce ul.cart_list li .quantity{
        color: ' . $content_color . ';
    }   
    .woocommerce #reviews a.button:hover,
    .woocommerce #reviews button.button:hover,
    .woocommerce #reviews input.button:hover,
    .widget .calendar_wrap table td#today,
    .woocommerce ul.products li.product .woocommerce-loop-product__title:hover{
		color: ' . $theme_color . ';
	}

    .woocommerce.single-product #respond #commentform textarea:focus{
        border-bottom-color: ' . $theme_color . ';
    }
    .woocommerce .gridlist-toggle,
    .woocommerce .gt3-products-header .gt3-gridlist-toggle,
    .wrapper_404 .gt3_module_button a:hover{
        background-color: ' . $bg_body . ';
    }
    ';

	$label_color_sale = gt3_option('label_color_sale');
	$label_color_hot  = gt3_option('label_color_hot');
	$label_color_new  = gt3_option('label_color_new');

	if (gt3_option('modern_shop') == '1' || true === gt3_option('modern_shop')) {
		$label_color_sale = gt3_option('label_color_sale_modern');
		$label_color_hot  = gt3_option('label_color_hot_modern');
		$label_color_new  = gt3_option('label_color_new_modern');
	}

	$gt3_convert_color = function($color) {
		if (is_array($color)) {
			if (key_exists('rgba', $color)) {
				return $color['rgba'];
			} else if (key_exists('color', $color)) {
				return $color['color'];
			}
		}
		return $color;
	};

	if ($color = $gt3_convert_color($label_color_sale)) {
		$custom_css .= '
        .woocommerce ul.products li.product .onsale,
        #yith-quick-view-content .onsale,
        .woocommerce span.onsale{
            background-color: '.esc_attr($color).';
        }';
	}

	if ($color = $gt3_convert_color($label_color_hot)) {
		$custom_css .= '
        .woocommerce ul.products li.product .onsale.hot-product,
        #yith-quick-view-content .onsale.hot-product,
        .woocommerce span.onsale.hot-product{
            background-color: '.esc_attr($color).';
        }';
	}

	if ($color = $gt3_convert_color($label_color_new)) {
		$custom_css .= '
        .woocommerce ul.products li.product .onsale.new-product,
        #yith-quick-view-content .onsale.new-product,
        .woocommerce span.onsale.new-product{
            background-color: '.esc_attr($color).';
        }';
	}
	// Woocommerce end

	$custom_css .= '
    .search_form .search_text,
    .widget_product_search .gt3_search_form input#woocommerce-product-search-field-0,
    .gt3_burger_sidebar_container .mc_merge_var input#mc_mv_EMAIL,
    .wpda-builder__burger_container .mc_merge_var input#mc_mv_EMAIL,
    .gt3_burger_sidebar_container .mc_merge_var input[type="email"],
    .wpda-builder__burger_container .mc_merge_var input[type="email"] {
        border-color: rgba('.gt3_HexToRGB($theme_color).', 0.5) !important;
    }
    .gt3_widget.woocommerce .widget-title,
    .woocommerce div.product > .woocommerce-tabs ul.tabs li a,
    .logo_container .site-title {
        color:'.$header_font_color.';
    }
    blockquote,
    p.form-submit button#submit,
    .woocommerce ul.products li.product a .woocommerce-loop-product__title,
    .woocommerce div.product form.cart .button,
    .woocommerce-cart table.cart td.actions .coupon .button,
    .woocommerce-cart table.cart td.actions > .button,
    .home2_form input.wpcf7-form-control.wpcf7-submit,
    .home5_form input.wpcf7-form-control.wpcf7-submit,
    .wpcf7-form input[type="submit"],
    .woocommerce #respond input#submit,
    .woocommerce a.button,
    .woocommerce button.button,
    .woocommerce input.button{
        font-family:' . $content_font_family . ';
    }    
    .woocommerce div.product form.cart div.quantity,
    .woocommerce #review_form #respond input[type="date"],
    .woocommerce #review_form #respond input[type="email"],
    .woocommerce #review_form #respond input[type="number"],
    .woocommerce #review_form #respond input[type="password"],
    .woocommerce #review_form #respond input[type="search"],
    .woocommerce #review_form #respond input[type="tel"],
    .woocommerce #review_form #respond input[type="text"],
    .woocommerce #review_form #respond input[type="url"],
    .woocommerce #review_form #respond select,
    .woocommerce #review_form #respond textarea,
    .woocommerce-cart table.cart td.actions .coupon .input-text,
    .woocommerce table.shop_table td,
    .woocommerce-cart .cart_totals table.shop_table tr th,
    .woocommerce-cart .cart_totals table.shop_table tr td,
    .widget_product_search .gt3_search_form input#woocommerce-product-search-field-0,
    .woocommerce ul.products li.product .gt3-animation-wrapper,
    .woocommerce-page ul.products li.product .gt3-animation-wrapper,
    .gt3_qty_spinner,
    .woocommerce-cart table.cart td.actions > .button,
    .woocommerce .cart-collaterals .cart_totals,
    .woocommerce-page .cart-collaterals .cart_totals,
    .woocommerce table.shop_table{
        border-color: rgba('.gt3_HexToRGB($theme_color).', 0.1) !important;
    }
    .coming_soon_form #mc_signup_form .mc_input,
    .coming_soon_form .mc4wp-form .mc_merge_var input {
        border-color: rgba('.gt3_HexToRGB($theme_color).', 0.04) !important;
    }
    
    .gt3_theme_textgradient-yes .elementor-heading-title::selection,
	.gt3_theme_textgradient-yes .gt3_typed_widget::selection {
	    background: rgba('.gt3_HexToRGB($theme_color).', 0.5);
	}
	
    ';
	// Elementor start
	$custom_css .= '
    .price_item .item_cost_wrapper h3,
    .price_item-cost,
    .ribbon_arrow .control_text span:not(.all_slides),
    .elementor-widget-tabs .elementor-tab-desktop-title,
    .woocommerce.widget_product_categories ul li:hover > a,
    .product-categories > li.cat-parent:hover .gt3-button-cat-open,
    .woocommerce .woocommerce-widget-layered-nav-list .woocommerce-widget-layered-nav-list__item:hover > a,
    .woocommerce .woocommerce-widget-layered-nav-list .woocommerce-widget-layered-nav-list__item:hover span,
    .cart_list.product_list_widget a.remove:hover,
    .woocommerce ul.products li.product a:hover,
    .woocommerce table.shop_table td.product-remove a:hover:before,
    .woocommerce table.shop_table td.product-name a:hover {
        color: ' . $theme_color . ';
    }
    .elementor-widget-gt3-core-portfolio .hover_none .wrapper .img:after,
    .elementor-widget-gt3-core-portfolio .hover_type1 .wrapper .img:after,
    .elementor-widget-gt3-core-portfolio .hover_type2 .wrapper:hover .img:after,
    .elementor-widget-gt3-core-portfolio .hover_type6 .wrapper .img_wrap:after{
        background: -moz-linear-gradient(top, rgba(0,0,0,0) 50%, rgba('.gt3_HexToRGB($theme_color).') 100%);
        background: -webkit-linear-gradient(top, rgba(0,0,0,0) 50%, rgba('.gt3_HexToRGB($theme_color).') 100%);
        background: linear-gradient(to bottom, rgba(0,0,0,0) 50%, rgba('.gt3_HexToRGB($theme_color).') 100%);
    }
    .elementor-widget-gt3-core-portfolio .hover_type4 .wrapper .img:after,
    .elementor-widget-gt3-core-portfolio .hover_type5 .wrapper .img:after{
        background: -moz-linear-gradient(top, rgba(0,0,0,0) 0%, rgba('.gt3_HexToRGB($theme_color).') 65%);
        background: -webkit-linear-gradient(top, rgba(0,0,0,0) 0%, rgba('.gt3_HexToRGB($theme_color).') 65%);
        background: linear-gradient(to bottom, rgba(0,0,0,0) 0%, rgba('.gt3_HexToRGB($theme_color).') 65%);
    }    

    .tagcloud a,
    ul.pagerblock li a,
    ul.pagerblock li a,
    ul.pagerblock li span,
    .page-link .page-number,
    .gt3_comments_pagination .page,
    .gt3_comments_pagination .page-numbers{
        background: rgba('.gt3_HexToRGB($theme_color).', 0.04);
    }
    .tagcloud a:hover{
        background: rgba('.gt3_HexToRGB($theme_color).', 0.14);
    }
    .gt3_single_team_info__item a:hover span {
        color: ' . $theme_color . ';
    }

    .woocommerce ul.products li.product .gt3_woocommerce_open_control_tag .button,
    .woocommerce div.product > .woocommerce-tabs .panel,
    .woocommerce .woocommerce-error,
    .woocommerce .woocommerce-info,
    .woocommerce .woocommerce-message,
    .gt3_product_list_nav{
        border-color: rgba('.gt3_HexToRGB($theme_color).', 0.1);
    }
    input[type="date"],
    input[type="email"],
    input[type="number"],
    input[type="password"],
    input[type="search"],
    input[type="tel"],
    input[type="text"],
    input[type="url"],
    select,
    textarea,
    button:hover {
        border-bottom-color: rgba('.gt3_HexToRGB($theme_color).', 0.3);
        color: ' . $content_color . ';
    }
    .mc_form_inside .mc_signup_submit:before,
    .mc4wp-form-fields .mc_signup_submit:before {
        color: rgba('.gt3_HexToRGB($theme_color).', 0.3);
    }
    .mc_form_inside .mc_signup_submit:hover:before,
    .mc4wp-form-fields .mc_signup_submit:hover:before {
        color: rgba('.gt3_HexToRGB($theme_color).', 0.5);
    }
    .price_item .label_text span,
    a.bordered:hover,
    .woocommerce ul.products li.product .gt3_woocommerce_open_control_tag_bottom div a,
    .woocommerce-cart .shipping-calculator-form .button:hover,
    .woocommerce #payment .woocommerce-page #place_order,
    .woocommerce #payment .woocommerce-page #place_order:hover,
    .woocommerce .return-to-shop a.button.wc-backward:hover,
    span.ui-slider-handle.ui-state-default.ui-corner-all.ui-state-hover,
    body table.compare-list .add-to-cart td a:hover,
    .woocommerce .widget_price_filter .price_slider_amount .button:hover,
    .woocommerce-account .woocommerce-MyAccount-content .woocommerce-Message.woocommerce-Message--info.woocommerce-info .button,
    .woo_mini-count > span:not(:empty),
    #review_form form#commentform input#submit:hover,
    .woocommerce .widget_price_filter .ui-slider .ui-slider-range,
    .infinite-scroll-request > div,
    .elementor-widget-gt3-core-button .gt3_module_button_elementor .hover_type2 .gt3_module_button__container span.gt3_module_button__cover.back,
    .elementor-widget-gt3-core-button .gt3_module_button_elementor .hover_type3:after,
    .elementor-widget-gt3-core-button .gt3_module_button_elementor .hover_type4:hover .gt3_module_button__cover:after,
    .elementor-widget-gt3-core-button .gt3_module_button_elementor .hover_type5 .gt3_module_button__container .gt3_module_button__cover.back:before,
    .elementor-widget-gt3-core-button .gt3_module_button_elementor .hover_type5 .gt3_module_button__container .gt3_module_button__cover.back:after,
    .elementor-widget-gt3-core-button .gt3_module_button_elementor .hover_type6:hover:before,
    .elementor-widget-gt3-core-button .gt3_module_button_elementor .hover_type6:hover:after,
    .woocommerce .widget_price_filter .ui-slider .ui-slider-handle:before,
    .woocommerce .widget_price_filter .price_slider_amount .button,
    .woocommerce div.product > .woocommerce-tabs ul.tabs li.active,
    .woocommerce-Reviews #respond form#commentform input#submit,
    .mc_form_inside #mc_signup_submit,
    .mc4wp-form-fields input[type="submit"],
    .woocommerce .woocommerce-message a.button:hover,
    .woocommerce .woocommerce-message a.button,
    .woocommerce .woocommerce-message a.woocommerce-Button.button:hover,
    .woocommerce-account .woocommerce-MyAccount-content .woocommerce-message--info .button:hover,
    .woocommerce-account .woocommerce-MyAccount-content .woocommerce-Message.woocommerce-Message--info.woocommerce-info .button:hover,
    .woocommerce-account form.woocommerce-EditAccountForm > p > .woocommerce-Button,
    .elementor-toggle span.gt3_dropcaps{
        background-color: ' . $theme_color . ';
    }
    
    .woocommerce .widget_shopping_cart .buttons a,
    .woocommerce.widget_shopping_cart .buttons a,
    .woocommerce #respond input#submit.alt:hover,
    .woocommerce a.button.alt:hover,
    .woocommerce button.button.alt:hover,
    .woocommerce input.button.alt:hover,
    .revolution_form input.wpcf7-form-control.wpcf7-submit,    
    .home2_form input.wpcf7-form-control.wpcf7-submit,
    .home5_form input.wpcf7-form-control.wpcf7-submit,
    .coming_soon_form .mc_form_inside #mc_signup_submit,
    .coming_soon_form .mc4wp-form-fields input[type="submit"],
    #respond .form-submit button#submit,
    .dark_style .revolution_form input.wpcf7-form-control.wpcf7-submit:hover {
        background-color: ' . $theme_color2 . ';
    }
    .dark_style .revolution_form input.wpcf7-form-control.wpcf7-submit {
		background-color: ' . $header_font_color . ';
	}
    .gt3_comments_pagination .page-numbers,
    .page-link .page-number{
        border-color: rgba('.gt3_HexToRGB($theme_color).', 0.1);
        color: ' . $content_color . ';
    }
    .tagcloud a:hover,
    .woocommerce nav.woocommerce-pagination ul li a,
    .widget_product_search .gt3_search_form:before,
    ul.pagerblock li a,
    ul.pagerblock li span{
        color: ' . $content_color . ';
    }
    .elementor-widget-gt3-core-blogboxed .gt3_module_blogboxed.module_type1 .item_wrapper span.post_date{
        color: ' . $content_color . ' !important;
    }
    .page-link > span.page-number,
    .gt3_comments_pagination .page-numbers.current {
        color: ' . $theme_color . ';
    }
	.page-link > span.page-number,
	.elementor-widget-gt3-core-tabs .ui-tabs-nav .ui-state-default.ui-tabs-active a,
    #review_form form#commentform input#submit,
    .woocommerce nav.woocommerce-pagination ul li span.current,
    .woocommerce #respond input#submit{
        background-color: ' . $content_color . ';
    }

	a.bordered:hover,	
	.elementor-widget-tabs.elementor-tabs-view-horizontal .elementor-tab-desktop-title.elementor-active:after,
    .woocommerce .widget_price_filter .ui-slider .ui-slider-handle,
    .woocommerce .widget_price_filter .ui-slider .ui-slider-handle:before,
    .woocommerce ul.products li.product .gt3_woocommerce_open_control_tag .button:hover,
    .gt3_pricebox_module_wrapper.type1 .gt3_price_item-cost-elementor span.inner_circle{
		border-color: ' . $theme_color . ';
	}
	.price_item-cost,
	.countdown-section,
    .gt3_process_bar_container--type-vertical .gt3_process_item .gt3_process_item__number,
    .widget.widget_posts .recent_posts .post_title a,
    .woocommerce .widget_shopping_cart .total strong,
    .woocommerce.widget_shopping_cart .total strong,
    .search .blog_post_preview .listing_meta span{
		font-family: ' . $header_font_family . ';
	}
    
    .price_item-cost span,
    .ribbon_arrow .control_text span.all_slides,
    .woocommerce ul.cart_list li a,
    .isotope-filter a {
        color: ' . $content_color . ';
    }
    .fs_gallery_wrapper .status .first,
    .fs_gallery_wrapper .status .divider,
    .countdown-section,
    .page_nav_ancor a,
    .woocommerce .widget_price_filter .price_label,
    .woocommerce table.shop_table td.product-remove a,
    .woocommerce table.shop_table td.product-name a,
    .gt3_single_team_info__item h4{
		color: ' . $header_font_color . ';
	}

    /* PixProof */
    .mfp-container button.mfp-arrow-right:hover {
        border-left-color: ' . $theme_color . ';
    }
    .mfp-container button.mfp-arrow-left:hover {
        border-right-color: ' . $theme_color . ';
    }
    /* End PixProof */

    /* Map */
    .map_info_marker {
        background: ' . esc_attr(gt3_option('map_marker_info_background')) .';
    }
    .map_info_marker:after {
        border-color: ' . esc_attr(gt3_option('map_marker_info_background')) . ' transparent transparent transparent;
    }
    .marker_info_street_number,
    .marker_info_street,
    .footer_back2top .gt3_svg_line_icon,
    button:hover{
        color: ' . $theme_color . ';
    }
    .marker_info_desc {
        color: ' . esc_attr(gt3_option('map_marker_info_color')) . ';
    }
    .map_info_marker_content {
        font-family:' . $map_marker_font_family . ';
        font-weight:' . $map_marker_font_weight . ';
    }
    .marker_info_divider:after {
        background: ' . esc_attr(gt3_option('map_marker_info_color')) . ';
    }
    .elementor-custom-embed-play {
        color: rgba('.gt3_HexToRGB($theme_color).', 0.1);
    }
    ';
	// Elementor end


	/* Elementor Buttons */
	$custom_css .= '
    .elementor-widget-gt3-core-button a {
        border-color: rgba('.gt3_HexToRGB($theme_color).', 0.1);
        color: ' . $content_color . ';
    }
    .elementor-widget-gt3-core-button a:hover {
        border-color: rgba('.gt3_HexToRGB($theme_color).', 0.1);
        color: ' . $theme_color . ';
    }
    ';
	/* Elementor Buttons end */


	/* Gradient Colors 1 */
	$custom_css .= '
    .search_result_form input[type="submit"]:hover,
    .elementor-widget-gt3-core-advanced-tabs:not(.gt3_tabs_marker-yes) .gt3_advanced_tabs_nav > li.ui-tabs-active > a,
    .elementor-widget-gt3-core-pricebox .gt3_pricebox_module_wrapper.type1 .gt3_price_item-cost-elementor span.inner_2_circles:before,
    .elementor-widget-gt3-core-pricebox .gt3_pricebox_module_wrapper.type1 .gt3_price_item-cost-elementor span.inner_2_circles:after,
    .elementor-widget-gt3-core-pricebox .gt3_pricebox_module_wrapper.type1 .gt3_price_item-cost-elementor,
    .elementor-widget-gt3-core-pricetable .price_button-elementor a,
    .woocommerce .gt3-products-bottom nav.woocommerce-pagination ul li .page-numbers:hover,
    .woocommerce .gt3-products-bottom nav.woocommerce-pagination ul li .page-numbers.current,    
    .elementor-widget-gt3-core-button.gt3_portfolio_view_more_link_wrapper .gt3_module_button_elementor a:before,
    .elementor-widget-gt3-core-pricebox .price_button-elementor a span.gt3_module_button__cover.front:before,
    .gt3_pricebox_module_wrapper.type2 .gt3_price_item-wrapper_block:before,
    .gt3_pricebox_module_wrapper.type2 .gt3_price_item-elementor .gt3_item_cost_wrapper h3{
        background-image: linear-gradient(96deg, '.esc_attr($theme_color_start).' 0%, '.esc_attr($theme_color).' 100%);
    }
    .elementor-widget-gt3-core-pricebox .gt3_pricebox_module_wrapper.type1 .price_button-elementor .shortcode_button .gt3_module_button__cover.back:before,
    .elementor-widget-gt3-core-pricebox .gt3_pricebox_module_wrapper.type2 .price_button-elementor .shortcode_button .gt3_module_button__cover.back:before,
    .elementor-widget-gt3-core-pricebox .gt3_pricebox_module_wrapper.type3 .price_button-elementor .shortcode_button .gt3_module_button__cover.back:before,
    .elementor-widget-gt3-core-pricebox .gt3_pricebox_module_wrapper.type4 .price_button-elementor .shortcode_button .gt3_module_button__cover.back:before{
        border-color: '.esc_attr($theme_color).';
    }

    .elementor-widget-gt3-core-pricebox .gt3_pricebox_module_wrapper.type1 .price_button-elementor .shortcode_button:hover,
    .elementor-widget-gt3-core-pricebox .gt3_pricebox_module_wrapper.type2 .price_button-elementor .shortcode_button:hover,
    .elementor-widget-gt3-core-pricebox .gt3_pricebox_module_wrapper.type3 .price_button-elementor .shortcode_button:hover,
    .elementor-widget-gt3-core-pricebox .gt3_pricebox_module_wrapper.type3 .gt3_price_item-cost-elementor,
    .elementor-widget-gt3-core-pricebox .gt3_pricebox_module_wrapper.type4 .price_button-elementor .shortcode_button:hover,
    .elementor-widget-gt3-core-pricebox:not(.gt3_custom_pricebox_style) .gt3_pricebox_module_wrapper.type4 .gt3_price_item-cost-elementor {
        color: '.esc_attr($theme_color).';
    }

    .elementor-widget-gt3-core-pricetable .price_button-elementor a:hover,
    .elementor-widget-gt3-core-button.gt3_portfolio_view_more_link_wrapper .gt3_module_button_elementor a:after{
        background-image: linear-gradient(96deg, '.esc_attr($theme_color).' 0%, '.esc_attr($theme_color_start).' 100%);
    }
    ';
	/* Gradient Colors 1 end */

	/* Gradient Colors 2 */
	$custom_css .= '
    .isotope-filter a.active:before,
    .isotope-filter a:before,
    .search_result_form input[type="submit"],
    .elementor-widget-gt3-core-blog-packery .format-video .gt3_video__play_button,
    .active-package-yes.elementor-widget-gt3-core-pricebox .price_button-elementor span.gt3_module_button__cover.front:before,
    .elementor-widget-gt3-core-advanced-tabs:not(.gt3_tabs_marker-yes) .gt3_advanced_tabs_nav > li.ui-state-active > a,
    .gt3_advanced_tabs .gt3_advanced_tabs_nav_wrapper.ui-state-active .gt3_advanced_tabs_nav > li > a,
    .active-package-yes.elementor-widget-gt3-core-pricebox .gt3_pricebox_module_wrapper.type1 .gt3_price_item-cost-elementor span.inner_2_circles:before,
    .active-package-yes.elementor-widget-gt3-core-pricebox .gt3_pricebox_module_wrapper.type1 .gt3_price_item-cost-elementor span.inner_2_circles:after,
    .active-package-yes.elementor-widget-gt3-core-pricebox .gt3_pricebox_module_wrapper.type1 .gt3_price_item-cost-elementor,
    .active-package-yes.elementor-widget-gt3-core-pricebox .gt3_pricebox_module_wrapper.type2 .gt3_price_item_body-elementor,
    .active-package-yes.elementor-widget-gt3-core-pricebox .gt3_pricebox_module_wrapper.type2 .gt3_price_item-elementor .gt3_item_cost_wrapper h3,
    .active-package-yes.elementor-widget-gt3-core-pricebox .gt3_pricebox_module_wrapper.type3 .gt3_price_item_wrapper-elementor,
    .active-package-yes.elementor-widget-gt3-core-pricebox .gt3_pricebox_module_wrapper.type4 .gt3_price_item_wrapper-elementor{
        background-image: linear-gradient(96deg, '.esc_attr($theme_color2_start).' 0%, '.esc_attr($theme_color2).' 100%);
    }
    .active-package-yes.elementor-widget-gt3-core-pricebox .price_button-elementor a:hover span.gt3_module_button__cover.back:before{
        background-image: linear-gradient(96deg, '.esc_attr($theme_color2).' 0%, '.esc_attr($theme_color2_start).' 100%);
    }
    .active-package-yes.elementor-widget-gt3-core-pricebox .gt3_pricebox_module_wrapper.type1 .price_button-elementor .shortcode_button .gt3_module_button__cover.back:before{
        border-color: '.esc_attr($theme_color2).';
    }
    .active-package-yes.elementor-widget-gt3-core-pricebox .gt3_pricebox_module_wrapper.type2 .price_button-elementor .shortcode_button,
    .active-package-yes.elementor-widget-gt3-core-pricebox .gt3_pricebox_module_wrapper.type3 .price_button-elementor .shortcode_button,
    .active-package-yes.elementor-widget-gt3-core-pricebox .gt3_pricebox_module_wrapper.type4 .price_button-elementor .shortcode_button,
    .active-package-yes.elementor-widget-gt3-core-pricebox .gt3_pricebox_module_wrapper.type1 .price_button-elementor .shortcode_button:hover{
        color: '.esc_attr($theme_color2).';
    }     
    ';
	/* Gradient Colors 2 end */


	/* Gradient on Single Tag */
	$custom_css .= '
    .wpcf7-form input[type="submit"],
    .mc_form_inside #mc_signup_submit,
    .mc4wp-form-fields input[type="submit"],
    ul.pagerblock li a.current,
    .woocommerce .widget_price_filter .price_slider_amount .button,
    .woocommerce .widget_shopping_cart .buttons a.button.checkout.wc-forward,
    .woocommerce.widget_shopping_cart .buttons a.button.checkout.wc-forward,
    .woocommerce-cart .wc-proceed-to-checkout a.checkout-button,
    .woocommerce div.product form.cart .button,
    .woocommerce button.button,
    .woocommerce button.button:hover,
    .woocommerce div.product > .woocommerce-tabs ul.tabs li.active,
    .woocommerce-Reviews #respond form#commentform input#submit,
    .woocommerce .woocommerce-message a.button,
    .woocommerce #respond input#submit:hover,
    .woocommerce a.button:hover,
    .woocommerce input.button:hover,
    .woocommerce .return-to-shop a.button.wc-backward,
    input[type="submit"],
    button{
        background-image: linear-gradient(96deg, '.esc_attr($theme_color_start).' 0%,  '.esc_attr($theme_color).' 51%, '.esc_attr($theme_color_start).' 100%);
    }    
    .home08_ctf .wpcf7-form input[type="submit"] {
        border-color: '.esc_attr($theme_color).';
    }
    .elementor-widget-gt3-core-pricebox .gt3_pricebox_module_wrapper.type5 .price_button-elementor .shortcode_button {
        border-color: '.esc_attr($theme_color).';
    }
    .elementor-widget-gt3-core-pricebox .gt3_pricebox_module_wrapper.type5 .gt3_price_item_wrapper-elementor .price_button-elementor .price-button-icon {
        color: '.esc_attr($theme_color).';
    }
    .elementor-widget-gt3-core-pricebox .gt3_pricebox_module_wrapper.type5 .price_button-elementor .shortcode_button:hover {
        background-color: '.esc_attr($theme_color).';
    }
    .home08_ctf .wpcf7-form input[type="submit"]:hover {
        background-color: '.esc_attr($theme_color).';
    }
    .revolution_form input.wpcf7-form-control.wpcf7-submit,
    .home2_form input.wpcf7-form-control.wpcf7-submit,
    .home5_form input.wpcf7-form-control.wpcf7-submit,
    .coming_soon_form .mc_form_inside #mc_signup_submit,
    .coming_soon_form .mc4wp-form-fields input[type="submit"],
    .wrapper_404 .gt3_module_button a,
    #respond .form-submit button#submit,
    .woocommerce .widget_shopping_cart .buttons a,
    .woocommerce.widget_shopping_cart .buttons a,
    .woocommerce ul.products li.product .gt3_woocommerce_open_control_tag .button,
    .woocommerce #payment #place_order,
    .woocommerce-page #payment #place_order,
    form.revolution_form input[type="submit"],
    .gt3_home6_form .mc_form_inside #mc_signup_submit,
    .gt3_home6_form .mc4wp-form-fields input[type="submit"],
    .gt3_custom_form_large.default .wpcf7-form .gt3_contactform_home6 input[type="submit"] {
        background-image: linear-gradient(96deg, '.esc_attr($theme_color2_start).' 0%,  '.esc_attr($theme_color2).' 51%, '.esc_attr($theme_color2_start).' 100%);
    }
    .portfolio_carousel_wrapper .portfolio_item .wrapper .video-popup-wrapper .video-popup__link {
		background-color: '.$theme_color.';
	}
	.portfolio_carousel_wrapper .portfolio_item .wrapper .video-popup-wrapper .video-popup__link:hover {
		color: '.$theme_color.';
	}
    ';
	/* Gradient on Single Tag end */

	/* Modern Style */
	if (gt3_option('modern_shop') == '1' || true === gt3_option('modern_shop')) {
		$custom_css .= '
		.gt3_modern_shop .woocommerce ul.products li.product a .woocommerce-loop-product__title,
		.woocommerce.gt3_modern_shop ul.products li.product a .woocommerce-loop-product__title{
			font-family:' . $header_font_family . ';
			color:'.$header_font_color.';
	    }
	    .gt3_modern_shop .gt3-page-title__content .page_title h1,
	    .gt3_modern_shop h1,
	    .gt3_modern_shop h2,
	    .gt3_modern_shop h3,
	    .gt3_modern_shop h4,
	    .gt3_modern_shop h5,
	    .gt3_modern_shop h6,
	    .woocommerce.gt3_modern_shop #reviews .comment-reply-title,
	    .gt3_modern_shop button.bapf_reset {
	        font-family:' . $header_font_family . ';
	        font-weight: ' . $header_font_weight . ';
	    }
	    .single-product.woocommerce.gt3_modern_shop div.product p.price,
	    .woocommerce.gt3_modern_shop div.product .woocommerce-variation-price,
	    .gt3_modern_shop .woocommerce table.shop_table td.product-name > a,
	    .gt3_modern_shop .woocommerce table.shop_table.cart td.actions > label,
	    .woocommerce-cart.gt3_modern_shop .cart-collaterals .cart_totals table th,
		.woocommerce-cart.gt3_modern_shop .cart-collaterals .cart_totals table td,
		.gt3_modern_shop .woocommerce .gt3_order_review_wrap table.shop_table th,
		.gt3_modern_shop .woocommerce .gt3_order_review_wrap table.shop_table .cart_item td,
		.gt3_modern_shop .woocommerce .gt3_order_review_wrap table.shop_table td span.amount,
		.gt3_modern_shop .woocommerce table.shop_table.woocommerce-table--order-details th,
		.gt3_modern_shop .woocommerce table.shop_table.woocommerce-table--order-details span.amount,
		.woocommerce.gt3_modern_shop ul.products li.product .price,
	    .gt3_modern_shop .woocommerce ul.products li.product .price,
	    .gt3_modern_shop .elementor-widget-gt3-core-blogboxed.modern_style-yes .gt3_module_blogboxed.module_type1 .post_category a,
	    .gt3_modern_shop .elementor-widget-wpda-builder-cart .wpda-builder-cart .wpda-cart-inner .wpda-cart-container ul.cart_list li a,
	    .gt3_modern_shop .elementor-widget-wpda-builder-cart .wpda-builder-cart .wpda-cart-inner .wpda-cart-container ul.cart_list li .quantity,
	    .gt3_modern_shop .elementor-widget-wpda-builder-cart .wpda-builder-cart .wpda-cart-inner .wpda-cart-container p.woocommerce-mini-cart__total {
	        font-family:' . $header_font_family . ';
	    }
	    .gt3_modern_shop .gt3-page-title__content .breadcrumbs,
	    .gt3_modern_toggle_info_wrapper .woocommerce-Tabs-panel h6,
	    .woocommerce.gt3_modern_shop table.shop_attributes th,
	    .gt3_modern_shop [data-wvstooltip]:before,
	    .single-product.woocommerce.gt3_modern_shop div.product p.price del,
	    .gt3_modern_shop #yith-quick-view-modal .woocommerce div.product p.price del,
	    .woocommerce.gt3_modern_shop div.product .woocommerce-variation-price p.price del,
		.woocommerce.gt3_modern_shop div.product .woocommerce-variation-price span.price del,
		.woocommerce-cart.gt3_modern_shop .cart-collaterals .cart_totals table .woocommerce-shipping-totals td,
		.woocommerce-checkout.gt3_modern_shop .woocommerce table.shop_table td.product-name .gt3-cart_category,
		.woocommerce-checkout.gt3_modern_shop .woocommerce table.shop_table .product-quantity,
		.woocommerce.gt3_modern_shop ul.products li.product .price del,
		.gt3_modern_shop .woocommerce ul.products li.product .price del {
	        font-family:' . $content_font_family . ';
	    }
	    .gt3_modern_shop .sidebar .widget ul.yith-wcan-color li a:before {
	        ' . (!empty($bg_body) ? 'background:' . $bg_body . ';' : '') . '
	    }
	    .gt3_modern_shop .gt3_toggle_review,
	    .woocommerce.gt3_modern_shop div.product .summary form.cart .variations td label,
	    .gt3_modern_shop .summary .woocommerce-breadcrumb,
	    .gt3_modern_shop .summary .woocommerce-breadcrumb a,
	    .woocommerce.gt3_modern_shop div.product .woocommerce-variation-price span.price,
	    .woocommerce.gt3_modern_shop span.woocommerce-Price-amount.amount,
	    .gt3_modern_shop .gt3-page-title.gt3-page-title_horiz_align_center .gt3_breadcrumb a:not(:hover),
	    .woocommerce-checkout.gt3_modern_shop .select2-container .select2-selection--single,
		.gt3_modern_shop .select2-container--default span.select2-selection--single,
		.woocommerce-checkout.gt3_modern_shop .woocommerce table.shop_table td.product-name .gt3-cart_category,
		.gt3_modern_shop .bapf_from:before,
		.gt3_modern_shop .bapf_to:before,
		.woocommerce.gt3_modern_shop ul.products li.product .price,
	    .gt3_modern_shop .woocommerce ul.products li.product .price,
	    .woocommerce.gt3_modern_shop .gt3-products-header .woocommerce-breadcrumb a:not(:hover),
	    .woocommerce.gt3_modern_shop .gt3-products-header .woocommerce-breadcrumb,
	    .woocommerce.gt3_modern_shop .gt3-products-header .gt3-gridlist-toggle > a,
	    .woo-variation-swatches.gt3_modern_shop .product .variable-items-wrapper .variable-item .variable-item-span.variable-item-span-button,
	    .gt3-mobile__burger_shop_sidebar_close,
	    .gt3_modern_shop .gt3_custom_woo.elementor-widget-gt3-core-shoplist ul.products li.product .price del {
	        color:'.$content_color.';
	    }
	    .gt3_modern_shop .comment-form-cookies-consent label,
	    .gt3_modern_shop .elementor-widget-wpda-builder-cart .wpda-builder-cart .wpda-cart-inner .wpda-cart-container p.woocommerce-mini-cart__buttons a {
	        color:'.$content_color.' !important;
	    }
	    .woocommerce.gt3_modern_shop #reviews .comment-reply-title,
	    .woocommerce #reviews #comments ol.commentlist li .comment-text .gt3_woocommerce_open_control_tag p.meta,
	    .woocommerce.gt3_modern_shop .star-rating,
	    .woocommerce.single-product.gt3_modern_shop #respond #commentform .comment-form-rating label,
	    .woocommerce.gt3_modern_shop p.stars:hover a:before,
	    .woocommerce.gt3_modern_shop table.shop_attributes th,
	    .gt3_product_sharing a:hover,
	    .gt3_modern_shop #yith-quick-view-content .product_meta a,
	    .single-product.woocommerce.gt3_modern_shop div.product .product_meta a,	    
	    .gt3_woocommerce-review-link,
	    .gt3_modern_shop .woocommerce table.shop_table td.product-name .gt3-cart_category a:hover,
	    .gt3_modern_shop .woocommerce table.shop_table.cart td.product-subtotal span,
	    .gt3_modern_shop .woocommerce table.shop_table.cart td.actions > label,
	    .woocommerce-cart.gt3_modern_shop .cart-collaterals .cart_totals table tr.order-total,
	    .gt3_back_cart:hover,
	    .gt3_modern_shop .woocommerce form.woocommerce-checkout .form-row label,
	    .gt3_modern_shop .woocommerce .gt3_order_review_wrap table.shop_table .cart_item td,
	    .gt3_modern_shop .woocommerce .gt3_order_review_wrap table.shop_table .order-total th,
	    .gt3_modern_shop .woocommerce ul.order_details span.amount,
	    .gt3_modern_shop .woocommerce table.shop_table.woocommerce-table--order-details span.amount,
	    .gt3_modern_shop button.bapf_reset,
	    .gt3_modern_shop .bapf_sfilter.bapf_ckbox li label:hover,
	    .gt3_modern_shop .berocket_aapf_widget_selected_filter li a:hover,
	    .gt3_modern_shop .bapf_slidr_main.ui-widget-content .ui-slider-handle,
	    .gt3_modern_shop .bapf_slidr_jqrui .bapf_from,
		.gt3_modern_shop .bapf_slidr_jqrui .bapf_to,				
		.woocommerce.gt3_modern_shop .gt3-products-header .gt3-gridlist-toggle > a:hover,
		.woocommerce.gt3_modern_shop .gt3-products-header .gt3-gridlist-toggle > a.active,
		.woo-variation-swatches.gt3_modern_shop .product .variable-items-wrapper .variable-item:hover .variable-item-span.variable-item-span-button,
		.woo-variation-swatches.gt3_modern_shop .product .variable-items-wrapper .variable-item.selected .variable-item-span.variable-item-span-button,
		.gt3-mobile__burger_shop_sidebar_close:hover,
		.gt3-mobile_filter_btn:hover,
		.gt3_modern_shop .elementor-widget-wpda-builder-cart .wpda-builder-cart .wpda-cart-inner .wpda-cart-container p.woocommerce-mini-cart__total {
	        color:'.$header_font_color.';
	    }
	    .gt3_modern_shop .woocommerce-Reviews #respond form#commentform input#submit,
	    .gt3_modern_shop div.product form.cart button.single_add_to_cart_button,
	    .gt3_modern_shop .woocommerce a.button,
		.gt3_modern_shop .woocommerce button.button,
		.gt3_modern_shop .woocommerce input.button,
		.gt3_modern_shop .woocommerce table.shop_table.cart td.actions button.button:disabled:hover,
		.gt3_modern_shop .woocommerce table.shop_table.cart td.actions button.button:disabled[disabled]:hover,
		.woocommerce-page.gt3_modern_shop #payment #place_order {
	        background:'.$header_font_color.';
	        border-color:'.$header_font_color.';
	    }
	    .gt3_modern_shop .bapf_slidr_main.ui-widget-content .ui-slider-range,
	    .gt3_modern_shop .mc_form_inside .mc_signup_submit #mc_signup_submit,
	    .gt3_modern_shop .mc4wp-form-fields .mc_signup_submit input[type="submit"],
	    .gt3_modern_shop .woocommerce-message a.button {
	        background:'.$header_font_color.';
	    }
	    .gt3_modern_shop .gt3_pagination_delimiter:after {
	        background:'.$content_color.';
	    }
	    .gt3_modern_shop .woocommerce-Reviews #respond form#commentform input#submit:hover,
	    .gt3_modern_shop div.product form.cart button.single_add_to_cart_button:hover,
	    .gt3_modern_shop .woocommerce a.button:hover,
		.gt3_modern_shop .woocommerce button.button:hover,
		.gt3_modern_shop .woocommerce input.button:hover,
		.woocommerce-page.gt3_modern_shop #payment #place_order:hover,
		.gt3_modern_shop #back_to_top:hover,
		.gt3_modern_shop .gt3-products-bottom nav.woocommerce-pagination ul.page-numbers li .page-numbers:hover,
		.gt3_modern_shop .gt3-products-bottom nav.woocommerce-pagination ul.page-numbers li .page-numbers.current,
		.gt3_modern_shop .gt3_toggle_review:hover,
		.gt3_modern_shop .entry-summary .yith-wcwl-add-to-wishlist > div[class*="yith-wcwl-"] > a:hover,
		.gt3_modern_shop .woocommerce table.shop_table.cart td.actions input + button.button:hover {
	        background:'.$theme_color.';  
	        border-color:'.$theme_color.';  
	    }
	    .woocommerce-page.gt3_modern_shop ul.products.list li.product .gt3_modern_shop_item .gt3_woocommerce_open_control_tag > a:first-child,
		.gt3_modern_shop .woocommerce ul.products.list li.product .gt3_modern_shop_item .gt3_woocommerce_open_control_tag > a:first-child,
		.gt3_modern_shop .elementor-widget-wpda-builder-cart .wpda-builder-cart .wpda-cart-inner .wpda-cart-container p.woocommerce-mini-cart__buttons a.checkout {
	        background:'.$header_font_color.' !important;
	        border-color:'.$header_font_color.';
	    }
	    .woocommerce-page.gt3_modern_shop ul.products.list li.product .gt3_modern_shop_item .gt3_woocommerce_open_control_tag > a:hover,
		.gt3_modern_shop .woocommerce ul.products.list li.product .gt3_modern_shop_item .gt3_woocommerce_open_control_tag > a:hover,
		.gt3_modern_shop .elementor-widget-wpda-builder-cart .wpda-builder-cart .wpda-cart-inner .wpda-cart-container p.woocommerce-mini-cart__buttons a:hover,
		.gt3_subscribe_form input[type="submit"]:hover {
	        background-color:'.$theme_color.' !important;  
	        border-color:'.$theme_color.';  
	    }
	    .gt3_modern_shop .mc_form_inside .mc_signup_submit #mc_signup_submit:hover,
	    .gt3_modern_shop .mc4wp-form-fields .mc_signup_submit input[type="submit"]:hover,
	    .gt3_modern_shop .woocommerce-message a.button:hover,
	    .gt3_modern_shop .wishlist_table td.product-add-to-cart a:hover,
	    .gt3_modern_shop .wishlist_table .product-add-to-cart a:hover,
	    .woocommerce.gt3_modern_shop .gt3-products-header > .gt3-mobile_filter_btn.has_active_filters:after {
	        background:'.$theme_color.';  
	    }
	    .wpda-builder-cart .wpda-cart-inner .wpda-cart-container p.woocommerce-mini-cart__buttons a:hover {
	        background:'.$theme_color.' !important; 
	    }
	    .gt3_modern_shop #yith-quick-view-content .product_meta a:hover,
		.single-product.woocommerce.gt3_modern_shop div.product .product_meta a:hover,
		.gt3_woocommerce-review-link:hover {
			 color:'.$theme_color.';  
		}
		:root {
				--min_price_title: "'.esc_html__('Min Price', 'ewebot' ).'";
				--max_price_title: "'.esc_html__('Max Price', 'ewebot' ).'";
		}		
	    ';
	}

	$custom_css = str_replace(array("\r\n", "\r", "\n", "\t", '  ', '   ', '    '), '', $custom_css);

	/* Back2Top */
	if(gt3_option('back_to_top') == '1' || true === gt3_option('back_to_top') || gt3_customizer_enabled()) {
		$back2top_css = '.back_to_top_container{position:fixed;z-index:3;right:40px;bottom:40px;pointer-events:none}#back_to_top{display:block;width:40px;height:40px;cursor:pointer;transition:all .3s;text-align:center;pointer-events:none;opacity:0;color:#fff;border:2px solid;border-radius:5px;background:#fff}#back_to_top:before{content:"\f106";z-index:1;position:absolute;left:50%;top:50%;-webkit-transform:translate(-50%,-50%);-ms-transform:translate(-50%,-50%);transform:translate(-50%,-50%);font-size:26px;font-family:FontAwesome;font-weight:400;font-style:normal}#back_to_top.show{pointer-events:auto;opacity:1}#back_to_top.show:hover{color:#fff}@media only screen and (max-width:600px){.back_to_top_container{right:25px;bottom:25px}}';
		wp_add_inline_style('gt3-dynamic-handle', $back2top_css);
	}

	wp_add_inline_style( 'gt3-dynamic-handle', $custom_css );

	wp_enqueue_style('theme-font',get_template_directory_uri() . '/dist/css/theme-font.css');
}

add_action('wp_enqueue_scripts', 'gt3_custom_styles', 110);

add_action( 'wp_head', function() {
	?>
	<style type="text/css" media="screen">
        @media screen and ( max-width: 600px ) {
            html, * html body  { margin-top: 0 !important; }
        }
	</style>
	<?php
} , PHP_INT_MAX);
