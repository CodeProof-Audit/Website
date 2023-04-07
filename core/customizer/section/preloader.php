<?php

use GT3\ThemesCore\Customizer;

Customizer::add_section(
	'theme_preloader', array(
		'title' => esc_html__('Preloader', 'ewebot'),
	)
);

Customizer::add_field(
	'preloader',
	array(
		'label'         => esc_html__('Show Preloader', 'ewebot'),
		'type'          => Customizer::Toggle_Control,
		'settings_args' => array(
			'sanitize_callback'    => array( Customizer::Toggle_Control, 'sanitize' ),
			'sanitize_js_callback' => array( Customizer::Toggle_Control, 'sanitize' ),
		)
	)
);

Customizer::add_field(
	'preloader_type', array(
		'type'          => Customizer::Select_Control,
		'label'         => esc_html__('Select Type', 'ewebot'),
		'choices'       => array(
			'linear' => esc_html__('Linear', 'ewebot'),
			'circle' => esc_html__('Circle', 'ewebot'),
			'theme'  => esc_html__('Theme', 'ewebot'),
		),
		'settings_args' => array(
			'capability' => 'edit_theme_options',
		),
		'conditions'    => array(
			array(
				'field' => 'preloader',
				'type'  => 'bool',
				'value' => true,
			)
		)
	)
);

Customizer::add_field(
	'preloader_background',
	array(
		'label'         => esc_html__('Background', 'ewebot'),
		'type'          => Customizer::Color_Control,
		'conditions'    => array(
			array(
				'field' => 'preloader',
				'type'  => 'bool',
				'value' => true,
			)
		)
	)
);

Customizer::add_field(
	'preloader_item_color',
	array(
		'label'         => esc_html__('Stroke Background Color', 'ewebot'),
		'type'          => Customizer::Color_Control,
		'conditions'    => array(
			array(
				'field' => 'preloader',
				'type'  => 'bool',
				'value' => true,
			)
		)
	)
);

Customizer::add_field(
	'preloader_item_color2',
	array(
		'label'         => esc_html__('Stroke Foreground Color', 'ewebot'),
		'type'          => Customizer::Color_Control,
		'conditions'    => array(
			array(
				'field' => 'preloader',
				'type'  => 'bool',
				'value' => true,
			)
		)
	)
);

Customizer::add_field(
	'preloader_item_width',
	array(
		'label'         => esc_html__('Circle Size', 'ewebot'),
		'description'   => esc_html__('Set Circle Size in Pixels', 'ewebot'),
		'type'          => Customizer::Number_Control,
		'conditions'    => array(
			array(
				'field' => 'preloader',
				'type'  => 'bool',
				'value' => true,
			),
			array(
				'field' => 'preloader_type',
				'type'  => 'in',
				'value' => array( 'circle', 'theme' ),
			),
		)
	)
);

Customizer::add_field(
	'preloader_item_stroke',
	array(
		'label'         => esc_html__('Circle Stroke Width', 'ewebot'),
		'description'   => esc_html__('Set Circle Stroke Width in Pixels', 'ewebot'),
		'type'          => Customizer::Number_Control,
		'conditions'    => array(
			array(
				'field' => 'preloader',
				'type'  => 'bool',
				'value' => true,
			),
			array(
				'field' => 'preloader_type',
				'type'  => 'in',
				'value' => array( 'circle', 'theme' ),
			),
		)
	)
);

Customizer::add_field(
	'preloader_item_logo',
	array(
		'label'         => esc_html__('Add Custom Logo', 'ewebot'),
		'type'          => Customizer::Media_Control,
		'conditions'    => array(
			array(
				'field' => 'preloader',
				'type'  => 'bool',
				'value' => true,
			),
		)
	)
);

Customizer::add_field(
	'preloader_item_logo_width',
	array(
		'label'         => esc_html__('Logo Width', 'ewebot'),
		'description'   => esc_html__('Set Logo Width in Pixels', 'ewebot'),
		'type'          => Customizer::Number_Control,
		'conditions'    => array(
			array(
				'field' => 'preloader',
				'type'  => 'bool',
				'value' => true,
			),
			array(
				'field' => 'preloader_type',
				'type'  => 'in',
				'value' => array( 'circle', 'theme' ),
			),
		)
	)
);

Customizer::add_field(
	'preloader_full',
	array(
		'label'         => esc_html__('Fullscreen Mode', 'ewebot'),
		'type'          => Customizer::Toggle_Control,
		'conditions'    => array(
			array(
				'field' => 'preloader',
				'type'  => 'bool',
				'value' => true,
			),
		)
	)
);

Customizer::add_field(
	'preloader_mobile_disable',
	array(
		'label'         => esc_html__('Disable on Mobile', 'ewebot'),
		'type'          => Customizer::Toggle_Control,
		'conditions'    => array(
			array(
				'field' => 'preloader',
				'type'  => 'bool',
				'value' => true,
			),
		)
	)
);

