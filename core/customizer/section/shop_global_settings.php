<?php

use GT3\ThemesCore\Assets\Image;
use \GT3\ThemesCore\Customizer;

Customizer::set_panel('woocommerce');

Customizer::add_section(
	'theme_shop_settings', array(
		'title' => esc_html__('Global Settings', 'ewebot'),
	)
);

Customizer::add_field(
	'modern_shop',
	array(
		'label'         => esc_html__('Modern Shop', 'ewebot'),
		'type'          => Customizer::Toggle_Control,
		'settings_args' => array(
			'sanitize_callback'    => array( Customizer::Toggle_Control, 'sanitize' ),
			'sanitize_js_callback' => array( Customizer::Toggle_Control, 'sanitize' ),
		)
	)
);

Customizer::add_field(
	'theme-modern_content-color',
	array(
		'label'         => esc_html__('Main Font Color (Modern Shop)', 'ewebot'),
		'type'          => Customizer::Color_Control,
		'conditions'    => array(
			array(
				'field' => 'modern_shop',
				'type'  => 'bool',
				'value' => true,
			)
		)
	)
);

Customizer::add_field(
	'theme-modern_header-color',
	array(
		'label'         => esc_html__('Headers Font Color (Modern Shop)', 'ewebot'),
		'type'          => Customizer::Color_Control,
		'conditions'    => array(
			array(
				'field' => 'modern_shop',
				'type'  => 'bool',
				'value' => true,
			)
		)
	)
);

Customizer::add_field(
	'theme-modern_custom-color',
	array(
		'label'         => esc_html__('Theme Color (Modern Shop)', 'ewebot'),
		'type'          => Customizer::Color_Control,
		'conditions'    => array(
			array(
				'field' => 'modern_shop',
				'type'  => 'bool',
				'value' => true,
			)
		)
	)
);

Customizer::add_field(
	'gallery_images_count', array(
		'type'          => Customizer::Select_Control,
		'label'         => esc_html__('Gallery Images Count in the Products Grid', 'ewebot'),
		'choices'       => array(
			'1' => esc_html__( '1', 'ewebot' ),
			'2' => esc_html__( '2', 'ewebot' ),
			'3' => esc_html__( '3', 'ewebot' ),
			'4' => esc_html__( '4', 'ewebot' ),
			'5' => esc_html__( '5', 'ewebot' ),
		),
		'settings_args' => array(
			'capability' => 'edit_theme_options',
		),
		'conditions'    => array(
			array(
				'field' => 'modern_shop',
				'type'  => 'bool',
				'value' => true,
			),
		)
	)
);

Customizer::add_field(
	'products_layout', array(
		'type'          => Customizer::Select_Control,
		'label'         => esc_html__('Products Layout', 'ewebot'),
		'choices'       => array(
			'container' => esc_html__( 'Container', 'ewebot' ),
			'full_width' => esc_html__( 'Full Width', 'ewebot' ),
		),
		'settings_args' => array(
			'capability' => 'edit_theme_options',
		)
	)
);

Customizer::add_field(
	'products_sidebar_layout',
	array(
		'label'         => esc_html__('Products Page Sidebar Layout', 'ewebot'),
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
	'products_sidebar_def',
	array(
		'label'         => esc_html__('Products Page Sidebar', 'ewebot'),
		'type'          => Customizer::Select_Control,
		'choices'       => $sidebars,
		'settings_args' => array(
			'capability' => 'edit_theme_options',
		),
		'conditions'    => array(
			array(
				'field' => 'products_sidebar_layout',
				'type'  => 'not_in',
				'value' => array( 'none' ),
			),
		)
	)
);

Customizer::add_field(
	'products_per_page_frontend',
	array(
		'label'         => esc_html__('Show dropdown on the frontend to change Number of products displayed per page', 'ewebot'),
		'type'          => Customizer::Toggle_Control,
		'settings_args' => array(
			'sanitize_callback'    => array( Customizer::Toggle_Control, 'sanitize' ),
			'sanitize_js_callback' => array( Customizer::Toggle_Control, 'sanitize' ),
		)
	)
);

Customizer::add_field(
	'products_sorting_frontend',
	array(
		'label'         => esc_html__('Show dropdown on the frontend to change Sorting of products displayed per page', 'ewebot'),
		'type'          => Customizer::Toggle_Control,
		'settings_args' => array(
			'sanitize_callback'    => array( Customizer::Toggle_Control, 'sanitize' ),
			'sanitize_js_callback' => array( Customizer::Toggle_Control, 'sanitize' ),
		)
	)
);

Customizer::add_field(
	'products_infinite_scroll', array(
		'type'          => Customizer::Select_Control,
		'label'         => esc_html__('Infinite Scroll', 'ewebot'),
		'description'         => esc_html__('Select Infinite Scroll options', 'ewebot'),
		'choices'       => array(
			'none'     => esc_html__( 'None', 'ewebot' ),
			'view_all' => esc_html__( 'Activate after clicking on "View All"', 'ewebot' ),
			'always'   => esc_html__( 'Always', 'ewebot' ),
		),
		'settings_args' => array(
			'capability' => 'edit_theme_options',
		)
	)
);

Customizer::add_field(
	'woocommerce_pagination', array(
		'type'          => Customizer::Select_Control,
		'label'         => esc_html__('Pagination', 'ewebot'),
		'description'         => esc_html__('Select the position of pagination.', 'ewebot'),
		'choices'       => array(
			'top'       => esc_html__( 'Top', 'ewebot' ),
			'bottom'    => esc_html__( 'Bottom', 'ewebot' ),
			'top_bottom'=> esc_html__( 'Top and Bottom', 'ewebot' ),
			'off'       => esc_html__( 'Off', 'ewebot' ),
		),
		'settings_args' => array(
			'capability' => 'edit_theme_options',
		),
		'conditions'    => array(
			array(
				'field' => 'products_infinite_scroll',
				'type'  => 'not_in',
				'value' => array( 'always' ),
			),
		)
	)
);

Customizer::add_field(
	'woocommerce_grid_list', array(
		'type'          => Customizer::Select_Control,
		'label'         => esc_html__('Grid/List Option', 'ewebot'),
		'description'         => esc_html__('Display products in grid or list view by default', 'ewebot'),
		'choices'       => apply_filters('gt3/theme/redux/woocommerce_grid_list', array(
			'grid'      => esc_html__( 'Grid', 'ewebot' ),
			'list'      => esc_html__( 'List', 'ewebot' ),
			'off'       => esc_html__( 'Off', 'ewebot' ),
		)),
		'settings_args' => array(
			'capability' => 'edit_theme_options',
		)
	)
);

Customizer::add_field(
	'label_color_sale',
	array(
		'label'         => esc_html__('Color for "Sale" label', 'ewebot'),
		'description'         => esc_html__('Select the Background Color for "Sale" label.', 'ewebot'),
		'type'          => Customizer::Color_Control,
		'alpha' => true,
		'conditions'    => array(
			array(
				'field' => 'modern_shop',
				'type'  => 'bool',
				'value' => false,
			)
		)
	)
);

Customizer::add_field(
	'label_color_hot',
	array(
		'label'         => esc_html__('Color for "Hot" label', 'ewebot'),
		'description'         => esc_html__('Select the Background Color for "Hot" label.', 'ewebot'),
		'type'          => Customizer::Color_Control,
		'alpha' => true,
		'conditions'    => array(
			array(
				'field' => 'modern_shop',
				'type'  => 'bool',
				'value' => false,
			)
		)
	)
);

Customizer::add_field(
	'label_color_new',
	array(
		'label'         => esc_html__('Color for "New" label', 'ewebot'),
		'description'         => esc_html__('Select the Background Color for "New" label.', 'ewebot'),
		'type'          => Customizer::Color_Control,
		'alpha' => true,
		'conditions'    => array(
			array(
				'field' => 'modern_shop',
				'type'  => 'bool',
				'value' => false,
			)
		)
	)
);

Customizer::add_field(
	'label_color_sale_modern',
	array(
		'label'         => esc_html__('Color for "Sale" label', 'ewebot'),
		'description'         => esc_html__('Select the Background Color for "Sale" label.', 'ewebot'),
		'type'          => Customizer::Color_Control,
		'alpha' => true,
		'conditions'    => array(
			array(
				'field' => 'modern_shop',
				'type'  => 'bool',
				'value' => true,
			)
		)
	)
);

Customizer::add_field(
	'label_color_hot_modern',
	array(
		'label'         => esc_html__('Color for "Hot" label', 'ewebot'),
		'description'         => esc_html__('Select the Background Color for "Hot" label.', 'ewebot'),
		'type'          => Customizer::Color_Control,
		'alpha' => true,
		'conditions'    => array(
			array(
				'field' => 'modern_shop',
				'type'  => 'bool',
				'value' => true,
			)
		)
	)
);

Customizer::add_field(
	'label_color_new_modern',
	array(
		'label'         => esc_html__('Color for "New" label', 'ewebot'),
		'description'         => esc_html__('Select the Background Color for "New" label.', 'ewebot'),
		'type'          => Customizer::Color_Control,
		'alpha' => true,
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
