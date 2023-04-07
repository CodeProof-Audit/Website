<?php

use GT3\ThemesCore\Assets\Image;
use \GT3\ThemesCore\Customizer;

Customizer::set_panel('woocommerce');

Customizer::add_section(
	'theme_product_page_settings', array(
		'title' => esc_html__('Single Product Page', 'ewebot'),
	)
);

Customizer::add_field(
	'product_layout', array(
		'type'          => Customizer::Select_Control,
		'label'         => esc_html__('Thumbnails Layout', 'ewebot'),
		'choices'       => array(
			'horizontal'    => esc_html__( 'Thumbnails Bottom', 'ewebot' ),
			'vertical'      => esc_html__( 'Thumbnails Left', 'ewebot' ),
			'thumb_grid'    => esc_html__( 'Thumbnails Grid', 'ewebot' ),
			'thumb_vertical'=> esc_html__( 'Thumbnails Vertical Grid', 'ewebot' ),
		),
		'settings_args' => array(
			'capability' => 'edit_theme_options',
		)
	)
);

Customizer::add_field(
	'activate_carousel_thumb',
	array(
		'label'         => esc_html__('Activate Carousel for Vertical Thumbnail', 'ewebot'),
		'type'          => Customizer::Toggle_Control,
		'settings_args' => array(
			'sanitize_callback'    => array( Customizer::Toggle_Control, 'sanitize' ),
			'sanitize_js_callback' => array( Customizer::Toggle_Control, 'sanitize' ),
		)
	)
);

Customizer::add_field(
	'product_container', array(
		'type'          => Customizer::Select_Control,
		'label'         => esc_html__('Product Page Layout', 'ewebot'),
		'choices'       => array(
			'container'     => esc_html__( 'Container', 'ewebot' ),
			'full_width'    => esc_html__( 'Full Width', 'ewebot' ),
		),
		'settings_args' => array(
			'capability' => 'edit_theme_options',
		)
	)
);

Customizer::add_field(
	'product_sidebar_layout',
	array(
		'label'         => esc_html__('Single Product Page Sidebar Layout', 'ewebot'),
		'type'          => Customizer::Radio_Image_Control,
		'choices'       => array(
			'none'  => Image::get_file('sidebar_none.png'),
			'left'  => Image::get_file('sidebar_left.png'),
			'right' => Image::get_file('sidebar_right.png'),
		),
		'settings_args' => array(
			'capability' => 'edit_theme_options',
		)
	)
);

Customizer::add_field(
	'product_sidebar_def',
	array(
		'label'         => esc_html__('Single Product Page Sidebar', 'ewebot'),
		'type'          => Customizer::Select_Control,
		'choices'       => $sidebars,
		'settings_args' => array(
			'capability' => 'edit_theme_options',
		),
		'conditions'    => array(
			array(
				'field' => 'product_sidebar_layout',
				'type'  => 'not_in',
				'value' => array( 'none' ),
			),
		)
	)
);

Customizer::add_field(
	'shop_size_guide',
	array(
		'label'         => esc_html__('Show Size Guide', 'ewebot'),
		'type'          => Customizer::Toggle_Control,
		'settings_args' => array(
			'sanitize_callback'    => array( Customizer::Toggle_Control, 'sanitize' ),
			'sanitize_js_callback' => array( Customizer::Toggle_Control, 'sanitize' ),
		)
	)
);

Customizer::add_field(
	'size_guide',
	array(
		'label'         => esc_html__('Size guide Popup Image', 'ewebot'),
		'type'          => Customizer::Media_Control,
		'conditions'    => array(
			array(
				'field' => 'shop_size_guide',
				'type'  => 'bool',
				'value' => true,
			),
		)
	)
);

Customizer::add_field(
	'next_prev_product',
	array(
		'label'         => esc_html__('Show Next and Previous products', 'ewebot'),
		'type'          => Customizer::Toggle_Control,
		'settings_args' => array(
			'sanitize_callback'    => array( Customizer::Toggle_Control, 'sanitize' ),
			'sanitize_js_callback' => array( Customizer::Toggle_Control, 'sanitize' ),
		)
	)
);

Customizer::add_field(
	'product_sharing',
	array(
		'label'         => esc_html__('Product Sharing', 'ewebot'),
		'type'          => Customizer::Toggle_Control,
		'settings_args' => array(
			'sanitize_callback'    => array( Customizer::Toggle_Control, 'sanitize' ),
			'sanitize_js_callback' => array( Customizer::Toggle_Control, 'sanitize' ),
		),
		'conditions'    => array(
			array(
				'field' => 'modern_shop',
				'type'  => 'bool',
				'value' => true,
			)
		)
	)
);

Customizer::set_panel('theme_options');
