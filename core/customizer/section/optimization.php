<?php

use \GT3\ThemesCore\Customizer;

Customizer::add_section(
	'theme_optimization', array(
		'title' => esc_html__('Optimization', 'ewebot'),
	)
);

Customizer::add_field(
	'butt_clear',
	array(
		'type'         => Customizer::Button_Control,
		'button_text'  => __('Remove All Files', 'ewebot'),
		'action_click' => 'gt3_reset_assets',
		'nonce_key'    => 'gt3_clear_assets_cache',
	)
);


Customizer::add_field(
	'optimize_css',
	array(
		'label'         => esc_html__('Merge Theme and Core CSS Files', 'ewebot'),
		'type'          => Customizer::Toggle_Control,
		'settings_args' => array(
			'sanitize_callback'    => array( Customizer::Toggle_Control, 'sanitize' ),
			'sanitize_js_callback' => array( Customizer::Toggle_Control, 'sanitize' ),
		),
		'conditions'    => array(
			array(
				'field' => 'optimize_merge_all_css',
				'type'  => 'bool',
				'value' => false,
			)
		)
	)
);

Customizer::add_field(
	'optimize_merge_all_css',
	array(
		'label'         => esc_html__('Merge All CSS Files', 'ewebot'),
		'type'          => Customizer::Toggle_Control,
		'settings_args' => array(
			'sanitize_callback'    => array( Customizer::Toggle_Control, 'sanitize' ),
			'sanitize_js_callback' => array( Customizer::Toggle_Control, 'sanitize' ),
		)
	)
);

Customizer::add_field(
	'optimize_merge_all_js',
	array(
		'label'         => esc_html__('Merge All JS Files', 'ewebot'),
		'type'          => Customizer::Toggle_Control,
		'settings_args' => array(
			'sanitize_callback'    => array( Customizer::Toggle_Control, 'sanitize' ),
			'sanitize_js_callback' => array( Customizer::Toggle_Control, 'sanitize' ),
		)
	)
);

Customizer::add_field(
	'optimize_js',
	array(
		'label'         => esc_html__('Merge Theme and Core JS Files', 'ewebot'),
		'type'          => Customizer::Toggle_Control,
		'settings_args' => array(
			'sanitize_callback'    => array( Customizer::Toggle_Control, 'sanitize' ),
			'sanitize_js_callback' => array( Customizer::Toggle_Control, 'sanitize' ),
		),
		'conditions'    => array(
			array(
				'field' => 'optimize_merge_all_js',
				'type'  => 'bool',
				'value' => false,
			)
		)
	)
);

Customizer::add_field(
	'optimize_woo',
	array(
		'label'         => esc_html__('WooCommerce Optimization', 'ewebot'),
		'type'          => Customizer::Toggle_Control,
		'settings_args' => array(
			'sanitize_callback'    => array( Customizer::Toggle_Control, 'sanitize' ),
			'sanitize_js_callback' => array( Customizer::Toggle_Control, 'sanitize' ),
		)
	)
);

Customizer::add_field(
	'optimize_migrate',
	array(
		'label'         => esc_html__('Disable jQuery Migrate in WordPress', 'ewebot'),
		'type'          => Customizer::Toggle_Control,
		'settings_args' => array(
			'sanitize_callback'    => array( Customizer::Toggle_Control, 'sanitize' ),
			'sanitize_js_callback' => array( Customizer::Toggle_Control, 'sanitize' ),
		)
	)
);
