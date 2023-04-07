<?php

use \GT3\ThemesCore\Customizer;

Customizer::add_section(
	'theme_google_map', array(
		'title' => esc_html__('Google Map', 'ewebot'),
	)
);

Customizer::add_field(
	'info_api_key',
	array(
		'label'         => esc_html__('Google Map API Key', 'ewebot'),
		'settings_args' => array(
			'default'    => esc_html__('The key is used from the elementor settings.', 'ewebot'),
		),
	)
);

Customizer::add_field(
	'google_map_latitude',
	array(
		'label'         => esc_html__('Map Latitude Coordinate', 'ewebot'),
	)
);

Customizer::add_field(
	'google_map_longitude',
	array(
		'label'         => esc_html__('Map Longitude Coordinate', 'ewebot'),
	)
);

Customizer::add_field(
	'zoom_map', array(
		'type'          => Customizer::Select_Control,
		'label'         => esc_html__( 'Default Zoom Map', 'ewebot' ),
		'description'         => esc_html__( 'Select the number of zoom map.', 'ewebot' ),
		'choices'       => array(
			'10' => esc_html__( '10', 'ewebot' ),
			'11' => esc_html__( '11', 'ewebot' ),
			'12' => esc_html__( '12', 'ewebot' ),
			'13' => esc_html__( '13', 'ewebot' ),
			'14' => esc_html__( '14', 'ewebot' ),
			'15' => esc_html__( '15', 'ewebot' ),
			'16' => esc_html__( '16', 'ewebot' ),
			'17' => esc_html__( '17', 'ewebot' ),
			'18' => esc_html__( '18', 'ewebot' ),
		),
		'settings_args' => array(
			'capability' => 'edit_theme_options',
		)
	)
);

Customizer::add_field(
	'map_marker_info',
	array(
		'label'         => esc_html__( 'Map Marker Info', 'ewebot' ),
		'type'          => Customizer::Toggle_Control,
		'settings_args' => array(
			'sanitize_callback'    => array( Customizer::Toggle_Control, 'sanitize' ),
			'sanitize_js_callback' => array( Customizer::Toggle_Control, 'sanitize' ),
		)
	)
);

Customizer::add_field(
	'custom_map_marker',
	array(
		'label'         => esc_html__('Custom Map Marker URl', 'ewebot'),
		'description'         => esc_html__( 'Visible only on mobile or if "Map Marker Info" option is off.', 'ewebot' ),
	)
);

Customizer::add_field(
	'map_marker_info_street_number',
	array(
		'label'         => esc_html__('Street Number', 'ewebot'),
		'conditions'    => array(
			array(
				'field' => 'map_marker_info',
				'type'  => 'bool',
				'value' => true,
			),
		)
	)
);

Customizer::add_field(
	'map_marker_info_street',
	array(
		'label'         => esc_html__('Street', 'ewebot'),
		'conditions'    => array(
			array(
				'field' => 'map_marker_info',
				'type'  => 'bool',
				'value' => true,
			),
		)
	)
);

Customizer::add_field(
	'map_marker_info_descr',
	array(
		'label'         => esc_html__('Short Description', 'ewebot'),
		'description'   => esc_html__('The optimal number of characters is 35', 'ewebot'),
		'type'          => Customizer::Textarea_Control,
		'conditions'    => array(
			array(
				'field' => 'map_marker_info',
				'type'  => 'bool',
				'value' => true,
			),
		)
	)
);

Customizer::add_field(
	'map_marker_info_background',
	array(
		'label'         => esc_html__('Map Marker Info Background', 'ewebot'),
		'type'          => Customizer::Color_Control,
		'conditions'    => array(
			array(
				'field' => 'map_marker_info',
				'type'  => 'bool',
				'value' => true,
			),
		)
	)
);

Customizer::add_field(
	'map_marker_info_color',
	array(
		'label'         => esc_html__('Map Marker Description Text Color', 'ewebot'),
		'type'          => Customizer::Color_Control,
		'conditions'    => array(
			array(
				'field' => 'map_marker_info',
				'type'  => 'bool',
				'value' => true,
			),
		)
	)
);

Customizer::add_field(
	'custom_map_style',
	array(
		'label'         => esc_html__( 'Custom Map Style', 'ewebot' ),
		'type'          => Customizer::Toggle_Control,
		'settings_args' => array(
			'sanitize_callback'    => array( Customizer::Toggle_Control, 'sanitize' ),
			'sanitize_js_callback' => array( Customizer::Toggle_Control, 'sanitize' ),
		)
	)
);

Customizer::add_field(
	'custom_map_code',
	array(
		'label'         => esc_html__('JavaScript Style Array', 'ewebot'),
		'description'   => esc_html__( 'To change the style of the map, you must insert the JavaScript Style Array code from ', 'ewebot' ) .' <a href="https://snazzymaps.com/" target="_blank">'.esc_html__('Snazzy Maps', 'ewebot') .'</a>',
		'type'          => Customizer::Textarea_Control,
		'conditions'    => array(
			array(
				'field' => 'custom_map_style',
				'type'  => 'bool',
				'value' => true,
			),
		),
	)
);
