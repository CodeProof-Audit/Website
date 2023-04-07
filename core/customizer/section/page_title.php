<?php

use \GT3\ThemesCore\Customizer;

Customizer::add_section(
	'theme_page_title', array(
		'title' => esc_html__('Page Title Section', 'ewebot'),
	)
);

Customizer::add_field(
	'page_title_conditional',
	array(
		'label'         => esc_html__( 'Show Section', 'ewebot' ),
		'type'          => Customizer::Toggle_Control,
		'settings_args' => array(
//			'transport' => 'refresh',
			'sanitize_callback'    => array( Customizer::Toggle_Control, 'sanitize' ),
			'sanitize_js_callback' => array( Customizer::Toggle_Control, 'sanitize' ),
		)
	)
);

Customizer::add_field(
	'page_title_names_conditional',
	array(
		'label'         => esc_html__( 'Show Page Title', 'ewebot' ),
		'type'          => Customizer::Toggle_Control,
		'settings_args' => array(
//			'transport' => 'refresh',
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
	'page_title_breadcrumbs_conditional',
	array(
		'label'         => esc_html__( 'Show Breadcrumbs', 'ewebot' ),
		'type'          => Customizer::Toggle_Control,
		'settings_args' => array(
//			'transport' => 'refresh',
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
	'page_title_vert_align', array(
		'type'          => Customizer::Select_Control,
		'label'         => esc_html__('Vertical Alignment', 'ewebot'),
		'choices'       => array(
			'top'       => esc_html__( 'Top', 'ewebot' ),
			'middle'    => esc_html__( 'Middle', 'ewebot' ),
			'bottom'    => esc_html__( 'Bottom', 'ewebot' )
		),
		'settings_args' => array(
//			'transport' => 'refresh',
			'capability' => 'edit_theme_options',
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
	'page_title_horiz_align', array(
		'type'          => Customizer::Select_Control,
		'label'         => esc_html__('Text Alignment', 'ewebot'),
		'choices'       => array(
			'left'      =>  esc_html__( 'Left', 'ewebot' ),
			'center'    => esc_html__( 'Center', 'ewebot' ),
			'right'     => esc_html__( 'Right', 'ewebot' )
		),
		'settings_args' => array(
			'capability' => 'edit_theme_options',
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
	'page_title_font_color',
	array(
		'label'         => esc_html__('Text Color', 'ewebot'),
		'type'          => Customizer::Color_Control,
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
	'page_title_bg_color',
	array(
		'label'         => esc_html__('Background Color', 'ewebot'),
		'type'          => Customizer::Color_Control,
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
	'page_title_overlay_color',
	array(
		'label'         => esc_html__('Overlay Color', 'ewebot'),
		'type'          => Customizer::Color_Control,
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
	'page_title_bg_image_image',
	array(
		'label'         => esc_html__('Background Image', 'ewebot'),
		'type'          => Customizer::Media_Control,
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
	'page_title_bg_image_repeat', array(
		'type'          => Customizer::Select_Control,
		'label'         => esc_html__('Background-Repeat Values', 'ewebot'),
		'choices'       => array(
			'no-repeat'      =>  esc_html__( 'No Repeat', 'ewebot' ),
			'repeat'    => esc_html__( 'Repeat All', 'ewebot' ),
			'repeat-x'     => esc_html__( 'Repeat Horizontally', 'ewebot' ),
			'repeat-y'     => esc_html__( 'Repeat Vertically', 'ewebot' ),
			'inherit'     => esc_html__( 'Inherit', 'ewebot' ),
		),
		'settings_args' => array(
			'capability' => 'edit_theme_options',
		),
		'conditions'    => array(
			array(
				'field' => 'page_title_bg_image_image',
				'type'  => 'not_in',
				'value' => array(''),
			),
			array(
				'field' => 'page_title_conditional',
				'type'  => 'bool',
				'value' => true,
			),
		)
	)
);

Customizer::add_field(
	'page_title_bg_image_size', array(
		'type'          => Customizer::Select_Control,
		'label'         => esc_html__('Background-Size Values', 'ewebot'),
		'choices'       => array(
			'inherit'     => esc_html__( 'Inherit', 'ewebot' ),
			'cover'     => esc_html__( 'Cover', 'ewebot' ),
			'contain'     => esc_html__( 'Contain', 'ewebot' ),
		),
		'settings_args' => array(
			'capability' => 'edit_theme_options',
		),
		'conditions'    => array(
			array(
				'field' => 'page_title_bg_image_image',
				'type'  => 'not_in',
				'value' => array(''),
			),
			array(
				'field' => 'page_title_conditional',
				'type'  => 'bool',
				'value' => true,
			),
		)
	)
);

Customizer::add_field(
	'page_title_bg_image_attachment', array(
		'type'          => Customizer::Select_Control,
		'label'         => esc_html__('Background-Attachment Values', 'ewebot'),
		'choices'       => array(
			'scroll'     => esc_html__( 'Scroll', 'ewebot' ),
			'fixed'     => esc_html__( 'Fixed', 'ewebot' ),
			'inherit'     => esc_html__( 'Inherit', 'ewebot' ),
		),
		'settings_args' => array(
			'capability' => 'edit_theme_options',
		),
		'conditions'    => array(
			array(
				'field' => 'page_title_bg_image_image',
				'type'  => 'not_in',
				'value' => array(''),
			),
			array(
				'field' => 'page_title_conditional',
				'type'  => 'bool',
				'value' => true,
			),
		)
	)
);

Customizer::add_field(
	'page_title_bg_image_position', array(
		'type'          => Customizer::Select_Control,
		'label'         => esc_html__('Background Image Alignment', 'ewebot'),
		'choices'       => array(
			'left top'    => esc_html__('Left Top', 'ewebot'),
			'left center' => esc_html__('Left Center', 'ewebot'),
			'left bottom' => esc_html__('Left Bottom', 'ewebot'),
			'center top'  => esc_html__('Center Top', 'ewebot'),
			'center center'  => esc_html__('Center Center', 'ewebot'),
			'center bottom'  => esc_html__('Center Bottom', 'ewebot'),
			'right top'  => esc_html__('Right Top', 'ewebot'),
			'right center'  => esc_html__('Right center', 'ewebot'),
			'right bottom'  => esc_html__('Right Bottom', 'ewebot'),
		),
		'settings_args' => array(
			'capability' => 'edit_theme_options',
		),
		'conditions'    => array(
			array(
				'field' => 'page_title_bg_image_image',
				'type'  => 'not_in',
				'value' => array(''),
			),
			array(
				'field' => 'page_title_conditional',
				'type'  => 'bool',
				'value' => true,
			),
		)
	)
);

Customizer::add_field(
	'page_title_height',
	array(
		'label'         => esc_html__('Page Title Section Height', 'ewebot'),
		'description'   => esc_html__('Set Page Title Section Height in Pixels', 'ewebot'),
		'type'          => Customizer::Number_Control,
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
	'page_title_top_border',
	array(
		'label'         => esc_html__( 'Show Top Border', 'ewebot' ),
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
	'page_title_top_border_color',
	array(
		'label'         => esc_html__('Top Border Color', 'ewebot'),
		'type'          => Customizer::Color_Control,
		'alpha' => true,
		'conditions'    => array(
			array(
				'field' => 'page_title_top_border',
				'type'  => 'bool',
				'value' => true,
			),
			array(
				'field' => 'page_title_conditional',
				'type'  => 'bool',
				'value' => true,
			),
		)
	)
);

Customizer::add_field(
	'page_title_bottom_border',
	array(
		'label'         => esc_html__( 'Show Bottom Border', 'ewebot' ),
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
	'page_title_bottom_border_color',
	array(
		'label'         => esc_html__('Bottom Border Color', 'ewebot'),
		'type'          => Customizer::Color_Control,
		'alpha' => true,
		'conditions'    => array(
			array(
				'field' => 'page_title_bottom_border',
				'type'  => 'bool',
				'value' => true,
			),
			array(
				'field' => 'page_title_conditional',
				'type'  => 'bool',
				'value' => true,
			),
		)
	)
);

Customizer::add_field(
	'page_title_bottom_margin',
	array(
		'label'         => esc_html__('Bottom Margin', 'ewebot'),
		'description'   => esc_html__('Set Bottom Margin in Pixels', 'ewebot'),
		'type'          => Customizer::Number_Control,
		'conditions'    => array(
			array(
				'field' => 'page_title_conditional',
				'type'  => 'bool',
				'value' => true,
			),
		)
	)
);
