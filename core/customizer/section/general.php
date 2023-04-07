<?php

use \GT3\ThemesCore\Customizer;

Customizer::add_section(
	'theme_general', array(
		'title' => esc_html__('General', 'ewebot'),
	)
);

Customizer::add_field(
	'responsive',
	array(
		'label'         => esc_html__('Responsive Mode', 'ewebot'),
		'type'          => Customizer::Toggle_Control,
		'settings_args' => array(
			'sanitize_callback'    => array( Customizer::Toggle_Control, 'sanitize' ),
			'sanitize_js_callback' => array( Customizer::Toggle_Control, 'sanitize' ),
		)
	)
);

Customizer::add_field(
	'page_comments',
	array(
		'label'         => esc_html__('Page Comments', 'ewebot'),
		'type'          => Customizer::Toggle_Control,
		'settings_args' => array(
			'sanitize_callback'    => array( Customizer::Toggle_Control, 'sanitize' ),
			'sanitize_js_callback' => array( Customizer::Toggle_Control, 'sanitize' ),
		)
	)
);

Customizer::add_field(
	'back_to_top',
	array(
		'label'         => esc_html__('Back to Top', 'ewebot'),
		'type'          => Customizer::Toggle_Control,
		'settings_args' => array(
			'sanitize_callback'    => array( Customizer::Toggle_Control, 'sanitize' ),
			'sanitize_js_callback' => array( Customizer::Toggle_Control, 'sanitize' ),
		)
	)
);

Customizer::add_field(
	'bubbles_block',
	array(
		'label'         => esc_html__('Bubbles', 'ewebot'),
		'type'          => Customizer::Toggle_Control,
		'settings_args' => array(
			'sanitize_callback'    => array( Customizer::Toggle_Control, 'sanitize' ),
			'sanitize_js_callback' => array( Customizer::Toggle_Control, 'sanitize' ),
		)
	)
);

Customizer::add_field(
	'404_page_id',
	array(
		'label'         => esc_html__('Select 404 Page', 'ewebot'),
		'type'          => Customizer::Dropdown_Pages_Control,
		'settings_args' => array(
			'capability' => 'edit_theme_options',
		)
	)
);

Customizer::add_field(
	'page_404_bg',
	array(
		'label' => esc_html__('404 Page Background Image', 'ewebot'),
		'type'  => Customizer::Media_Control,
	)
);

Customizer::add_field(
	'disable_right_click',
	array(
		'label'         => esc_html__('Disable Right-Click', 'ewebot'),
		'type'          => Customizer::Toggle_Control,
		'settings_args' => array(
			'sanitize_callback'    => array( Customizer::Toggle_Control, 'sanitize' ),
			'sanitize_js_callback' => array( Customizer::Toggle_Control, 'sanitize' ),
		)
	)
);

Customizer::add_field(
	'disable_right_click_text',
	array(
		'label'      => esc_html__('Right click alert text', 'ewebot'),
		'conditions' => array(
			array(
				'field' => 'disable_right_click',
				'type'  => 'bool',
				'value' => true,
			),
		)
	)
);

Customizer::add_field(
	'custom_js',
	array(
		'label'       => esc_html__('Custom JS', 'ewebot'),
		'description' => esc_html__('Paste your JS code here.', 'ewebot'),
		'type'        => Customizer::Textarea_Control,
	)
);

Customizer::add_field(
	'header_custom_js',
	array(
		'label'       => esc_html__('Custom JS', 'ewebot'),
		'description' => esc_html__('Code to be added inside HEAD tag', 'ewebot'),
		'type'        => Customizer::Textarea_Control,
	)
);

