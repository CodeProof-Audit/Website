<?php

add_filter(
	'gt3/core/customizer/elementor/defaults', function($defaults){
	$defaults['system_typography'] = array(
		array(
			'_id'                        => 'theme-main',
			'title'                      => 'Theme Main',
			"typography_typography"      => 'custom',
			"typography_font_family"     => 'Rubik',
			"typography_font_size"       => array(
				"unit"  => "px",
				"size"  => "18",
				"sizes" => array()
			),
			"typography_font_weight"     => '400',
			"typography_text_transform"  => '',
			"typography_font_style"      => '',
			"typography_text_decoration" => '',
			"typography_line_height"     => array(
				"unit"  => "px",
				"size"  => "27",
				"sizes" => array()
			),
			"typography_letter_spacing"  => ''
		),
		array(
			'_id'                        => 'theme-secondary',
			'title'                      => 'Theme Secondary',
			"typography_typography"      => 'custom',
			"typography_font_family"     => 'Nunito',
			"typography_font_size"       => array(
				"unit"  => "px",
				"size"  => "18",
				"sizes" => array()
			),
			"typography_font_weight"     => '400',
			"typography_text_transform"  => '',
			"typography_font_style"      => '',
			"typography_text_decoration" => '',
			"typography_line_height"     => array(
				"unit"  => "px",
				"size"  => "27",
				"sizes" => array()
			),
			"typography_letter_spacing"  => ''
		),
		array(
			'_id'                        => 'theme-headers',
			'title'                      => 'Theme Headers',
			"typography_typography"      => 'custom',
			"typography_font_family"     => 'Nunito',
			"typography_font_size"       => '',
			"typography_font_weight"     => '800',
			"typography_text_transform"  => '',
			"typography_font_style"      => '',
			"typography_text_decoration" => '',
			"typography_line_height"     => '',
			"typography_letter_spacing"  => ''
		),
		array(
			'_id'                        => 'theme-modern-shop-main',
			'title'                      => 'Theme Main (Modern Shop)',
			"typography_typography"      => 'custom',
			"typography_font_family"     => 'Roboto',
			"typography_font_size"       => array(
				"unit"  => "px",
				"size"  => "16",
				"sizes" => array()
			),
			"typography_font_weight"     => '300',
			"typography_text_transform"  => '',
			"typography_font_style"      => '',
			"typography_text_decoration" => '',
			"typography_line_height"     => array(
				"unit"  => "px",
				"size"  => "27",
				"sizes" => array()
			),
			"typography_letter_spacing"  => ''
		),
		array(
			'_id'                        => 'theme-modern-shop-headers',
			'title'                      => 'Theme Headers (Modern Shop)',
			"typography_typography"      => 'custom',
			"typography_font_family"     => 'Manrope',
			"typography_font_size"       => array(
				"unit"  => "px",
				"size"  => "18",
				"sizes" => array()
			),
			"typography_font_weight"     => '600',
			"typography_text_transform"  => '',
			"typography_font_style"      => '',
			"typography_text_decoration" => '',
			"typography_line_height"     => array(
				"unit"  => "px",
				"size"  => "27",
				"sizes" => array()
			),
			"typography_letter_spacing"  => ''
		),
	);

	$defaults['system_colors'] = array(
		array(
			"_id"   => "theme-custom-color",
			"title" => "Theme Color",
			"color" => "#6254e7",
		),
		array(
			"_id"   => "theme-custom-color2",
			"title" => "Theme Color2",
			"color" => "#ff7426",
		),
		array(
			"_id"   => "theme-content-color",
			"title" => "Theme Content Color",
			"color" => "#696687",
		),
		array(
			"_id"   => "theme-secondary-color",
			"title" => "Theme Secondary Color",
			"color" => "#696687",
		),
		array(
			"_id"   => "theme-custom-color-start",
			"title" => "Theme Color Gradient Start",
			"color" => "#9289f1",
		),
		array(
			"_id"   => "theme-custom-color2-start",
			"title" => "Theme Color2 Gradient Start",
			"color" => "#f0ac0e",
		),
		array(
			"_id"   => "theme-header-font-color",
			"title" => "Theme Headers Color",
			"color" => "#3b3663",
		),
		array(
			"_id"   => "theme-body-bg-color",
			"title" => "Theme Body Background Color",
			"color" => "#ffffff",
		)
	);

	$defaults['__globals__'] = array(
		'body_color'                 => 'globals/colors?id=theme-content-color',
		'body_background_color'      => 'globals/colors?id=theme-body-bg-color',
		'body_typography_typography' => 'globals/typography?id=theme-main',
	);

	$defaults['h1_typography_typography'] = array(
		"typography"      => 'custom',
		"font_family"     => '',
		"font_size"       => array(
			"unit"  => "px",
			"size"  => "40",
			"sizes" => array()
		),
		"font_weight"     => '',
		"text_transform"  => '',
		"font_style"      => '',
		"text_decoration" => '',
		"line_height"     => array(
			"unit"  => "px",
			"size"  => "43",
			"sizes" => array()
		),
		"letter_spacing"  => ''
	);

	$defaults['h2_typography_typography'] = array(
		"typography"      => 'custom',
		"font_family"     => '',
		"font_size"       => array(
			"unit"  => "px",
			"size"  => "30",
			"sizes" => array()
		),
		"font_weight"     => '',
		"text_transform"  => '',
		"font_style"      => '',
		"text_decoration" => '',
		"line_height"     => array(
			"unit"  => "px",
			"size"  => "40",
			"sizes" => array()
		),
		"letter_spacing"  => ''
	);

	$defaults['h3_typography_typography'] = array(
		"typography"      => 'custom',
		"font_family"     => '',
		"font_size"       => array(
			"unit"  => "px",
			"size"  => "24",
			"sizes" => array()
		),
		"font_weight"     => '',
		"text_transform"  => '',
		"font_style"      => '',
		"text_decoration" => '',
		"line_height"     => array(
			"unit"  => "px",
			"size"  => "30",
			"sizes" => array()
		),
		"letter_spacing"  => ''
	);

	$defaults['h4_typography_typography'] = array(
		"typography"      => 'custom',
		"font_family"     => '',
		"font_size"       => array(
			"unit"  => "px",
			"size"  => "20",
			"sizes" => array()
		),
		"font_weight"     => '',
		"text_transform"  => '',
		"font_style"      => '',
		"text_decoration" => '',
		"line_height"     => array(
			"unit"  => "px",
			"size"  => "33",
			"sizes" => array()
		),
		"letter_spacing"  => ''
	);

	$defaults['h5_typography_typography'] = array(
		"typography"      => 'custom',
		"font_family"     => 'Nunito',
		"font_size"       => array(
			"unit"  => "px",
			"size"  => "18",
			"sizes" => array()
		),
		"font_weight"     => '700',
		"text_transform"  => '',
		"font_style"      => '',
		"text_decoration" => '',
		"line_height"     => array(
			"unit"  => "px",
			"size"  => "30",
			"sizes" => array()
		),
		"letter_spacing"  => ''
	);

	$defaults['h6_typography_typography'] = array(
		"typography"      => 'custom',
		"font_family"     => 'Nunito',
		"font_size"       => array(
			"unit"  => "px",
			"size"  => "16",
			"sizes" => array()
		),
		"font_weight"     => '600',
		"text_transform"  => '',
		"font_style"      => '',
		"text_decoration" => '',
		"line_height"     => array(
			"unit"  => "px",
			"size"  => "24",
			"sizes" => array()
		),
		"letter_spacing"  => ''
	);

	$defaults['body_background_background'] = 'classic';

	return $defaults;
}
);

add_filter('gt3/core/customizer/elementor/convert_fields', function($def){
	return array_merge($def, array(
		/* Fonts */
		'main-font'                 => array(
			'font'  => array( 'field' => 'system_typography', 'id' => 'theme-main' ),
			'color' => array( 'field' => 'system_colors', 'id' => 'theme-content-color' ),
		),
		'secondary-font'            => array(
			'font'  => array( 'field' => 'system_typography', 'id' => 'theme-secondary' ),
			'color' => array( 'field' => 'system_colors', 'id' => 'theme-secondary-color' ),
		),
		'header-font'               => array(
			'font'  => array( 'field' => 'system_typography', 'id' => 'theme-headers' ),
			'color' => array( 'field' => 'system_colors', 'id' => 'theme-header-font-color' ),
		),
		'h1-font'                   => array(
			'font' => 'h1_typography',
		),
		'h2-font'                   => array(
			'font' => 'h2_typography',
		),
		'h3-font'                   => array(
			'font' => 'h3_typography',
		),
		'h4-font'                   => array(
			'font' => 'h4_typography',
		),
		'h5-font'                   => array(
			'font' => 'h5_typography',
		),
		'h6-font'                   => array(
			'font' => 'h6_typography',
		),
		'modern_shop_main-font'     => array(
			'font' => array( 'field' => 'system_typography', 'id' => 'theme-modern-shop-main' ),
		),
		'modern_shop_header-font'   => array(
			'font' => array( 'field' => 'system_typography', 'id' => 'theme-modern-shop-headers' ),
		),
		/* Colors */
		'theme-custom-color'        => array(
			'color' => array( 'field' => 'system_colors', 'id' => 'theme-custom-color' )
		),
		'theme-custom-color2'       => array(
			'color' => array( 'field' => 'system_colors', 'id' => 'theme-custom-color2' )
		),
		'theme-custom-color-start'  => array(
			'color' => array( 'field' => 'system_colors', 'id' => 'theme-custom-color-start' )
		),
		'theme-custom-color2-start' => array(
			'color' => array( 'field' => 'system_colors', 'id' => 'theme-custom-color2-start' )
		),
		'body-background-color'     => array(
			'color' => array( 'field' => 'system_colors', 'id' => 'theme-body-bg-color' )
		),

		/*
					'map-marker-font'         => '',
					'modern_shop_main-font'   => '',
					'modern_shop_header-font' => '',*/

	));
});

do_action('gt3/theme/customizer/elementor/loaded');
