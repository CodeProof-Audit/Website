<?php


use \GT3\ThemesCore\Customizer;

Customizer::add_section(
	'theme_typo', array(
		'title' => esc_html__('Typography', 'ewebot'),
	)
);

Customizer::add_field(
	'custom_font',
	array(
		'label'         => esc_html__( 'Custom Fonts', 'ewebot' ),
		'type'          => Customizer\Controls\Custom_Font::class,
	)
);
