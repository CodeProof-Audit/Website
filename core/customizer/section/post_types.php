<?php

use \GT3\ThemesCore\Customizer;

Customizer::add_section(
	'theme_post_types', array(
		'title' => esc_html__('Custom Post Types', 'ewebot'),
	)
);

Customizer::add_field(
	'team_title_conditional',
	array(
		'label'         => esc_html__( 'Show Team Post Title', 'ewebot' ),
		'type'          => Customizer::Toggle_Control,
		'settings_args' => array(
			'sanitize_callback'    => array( Customizer::Toggle_Control, 'sanitize' ),
			'sanitize_js_callback' => array( Customizer::Toggle_Control, 'sanitize' ),
		),
		'conditions'    => array(
			array(
				'field' => 'page_title_conditional',
				'type'  => 'bool',
				'value' => true,
			),
		)
	)
);

Customizer::add_field(
	'portfolio_title_conditional',
	array(
		'label'         => esc_html__( 'Show Portfolio Post Title', 'ewebot' ),
		'type'          => Customizer::Toggle_Control,
		'settings_args' => array(
			'sanitize_callback'    => array( Customizer::Toggle_Control, 'sanitize' ),
			'sanitize_js_callback' => array( Customizer::Toggle_Control, 'sanitize' ),
		),
		'conditions'    => array(
			array(
				'field' => 'page_title_conditional',
				'type'  => 'bool',
				'value' => true,
			),
		)
	)
);

Customizer::add_field(
	'team_slug',
	array(
		'label'         => esc_html__('Custom Team Slug', 'ewebot'),
	)
);

Customizer::add_field(
	'portfolio_slug',
	array(
		'label'         => esc_html__('Custom Portfolio Slug', 'ewebot'),
	)
);

Customizer::add_field(
	'portfolio_name',
	array(
		'label'         => esc_html__('Custom Portfolio Name in WP Dashboard', 'ewebot'),
	)
);

Customizer::add_field(
	'portfolio_archive_layout',
	array(
		'label'         => esc_html__('Portfolio Archive Page Layout', 'ewebot'),
		'type'          => Customizer::Select_Control,
		'choices'       => array(
			'1'       => esc_html__( '1 Column', 'ewebot' ),
			'2'    => esc_html__( '2 Columns', 'ewebot' ),
			'3'    => esc_html__( '3 Columns', 'ewebot' ),
			'4'    => esc_html__( '4 Columns', 'ewebot' )
		),
		'settings_args' => array(
			'capability' => 'edit_theme_options',
		)
	)
);
