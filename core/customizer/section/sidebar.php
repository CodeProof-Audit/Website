<?php

use \GT3\ThemesCore\Customizer;
use \GT3\ThemesCore\Assets\Image;

$sidebars = Customizer::instance()->get_option('sidebars');

Customizer::add_section(
	'theme_sidebar', array(
		'title' => esc_html__('Sidebars', 'ewebot'),
	)
);

Customizer::add_field(
	'sidebars',
	array(
		'type'          => Customizer::SIDEBAR_GENERATOR,
		'label'         => 'Sidebar Generator',
		'settings_args' => array(
			'default' => json_encode($sidebars),
		),
	)
);
$keys = array_map(function($sidebar) {
	return "sidebar_".esc_attr(strtolower(preg_replace('/[^A-Za-z0-9\-]/', '', str_replace(' ', '-', $sidebar))));
}, $sidebars);
$sidebars = array_combine($keys, $sidebars);

/* Page Sidebar */
Customizer::add_field(
	'page_sidebar_layout',
	array(
		'label'         => esc_html__('Page Sidebar Layout', 'ewebot'),
		'type'          => Customizer::Radio_Image_Control,
		'choices'       => array(
			'none'  => Image::get_file('sidebar_none.png'),
			'left'  => Image::get_file('sidebar_left.png'),
			'right' => Image::get_file('sidebar_right.png'),
		),
		'settings_args' => array(
			'transport' => 'refresh',
			'capability' => 'edit_theme_options',
		)
	)
);

Customizer::add_field(
	'page_sidebar_def',
	array(
		'label'         => esc_html__('Select Page Sidebar', 'ewebot'),
		'type'          => Customizer::Select_Control,
		'choices'       => $sidebars,
		'settings_args' => array(
			'transport' => 'refresh',
			'capability' => 'edit_theme_options',
		),
		'conditions'    => array(
			array(
				'field' => 'page_sidebar_layout',
				'type'  => 'not_in',
				'value' => array( 'none' ),
			),
		)
	)
);

/* Blog Single Sidebar */
Customizer::add_field(
	'blog_single_sidebar_layout',
	array(
		'label'         => esc_html__('Blog Sidebar Layout', 'ewebot'),
		'type'          => Customizer::Radio_Image_Control,
		'choices'       => array(
			'none'  => Image::get_file('sidebar_none.png'),
			'left'  => Image::get_file('sidebar_left.png'),
			'right' => Image::get_file('sidebar_right.png'),
		),
		'settings_args' => array(
			'transport' => 'refresh',
			'capability' => 'edit_theme_options',
		)
	)
);

Customizer::add_field(
	'blog_single_sidebar_def',
	array(
		'label'         => esc_html__('Select Blog Sidebar', 'ewebot'),
		'type'          => Customizer::Select_Control,
		'choices'       => $sidebars,
		'settings_args' => array(
			'transport' => 'refresh',
			'capability' => 'edit_theme_options',
		),
		'conditions'    => array(
			array(
				'field' => 'blog_single_sidebar_layout',
				'type'  => 'not_in',
				'value' => array( 'none' ),
			),
		)
	)
);

/* Portfolio Single Sidebar */
Customizer::add_field(
	'portfolio_single_sidebar_layout',
	array(
		'label'         => esc_html__('Portfolio Sidebar Layout', 'ewebot'),
		'type'          => Customizer::Radio_Image_Control,
		'choices'       => array(
			'none'  => Image::get_file('sidebar_none.png'),
			'left'  => Image::get_file('sidebar_left.png'),
			'right' => Image::get_file('sidebar_right.png'),
		),
		'settings_args' => array(
			'transport' => 'refresh',
			'capability' => 'edit_theme_options',
		)
	)
);

Customizer::add_field(
	'portfolio_single_sidebar_def',
	array(
		'label'         => esc_html__('Select Portfolio Sidebar', 'ewebot'),
		'type'          => Customizer::Select_Control,
		'choices'       => $sidebars,
		'settings_args' => array(
			'transport' => 'refresh',
			'capability' => 'edit_theme_options',
		),
		'conditions'    => array(
			array(
				'field' => 'portfolio_single_sidebar_layout',
				'type'  => 'not_in',
				'value' => array( 'none' ),
			),
		)
	)
);

/* Team Single Sidebar */
Customizer::add_field(
	'team_single_sidebar_layout',
	array(
		'label'         => esc_html__('Team Sidebar Layout', 'ewebot'),
		'type'          => Customizer::Radio_Image_Control,
		'choices'       => array(
			'none'  => Image::get_file('sidebar_none.png'),
			'left'  => Image::get_file('sidebar_left.png'),
			'right' => Image::get_file('sidebar_right.png'),
		),
		'settings_args' => array(
			'transport' => 'refresh',
			'capability' => 'edit_theme_options',
		)
	)
);

Customizer::add_field(
	'team_single_sidebar_def',
	array(
		'label'         => esc_html__('Select Team Sidebar', 'ewebot'),
		'type'          => Customizer::Select_Control,
		'choices'       => $sidebars,
		'settings_args' => array(
			'transport' => 'refresh',
			'capability' => 'edit_theme_options',
		),
		'conditions'    => array(
			array(
				'field' => 'team_single_sidebar_layout',
				'type'  => 'not_in',
				'value' => array( 'none' ),
			),
		)
	)
);
