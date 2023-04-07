<?php

/* @var \WP_Query */
global $wp_query;

$port = new \ElementorModal\Widgets\GT3_Core_Elementor_Widget_Blog(
	array(
		'id' => 'gt3_blog_archive'
	),
	array(
		'settings' => array()
	)
);
$category_archive_layout = gt3_option('category_archive_layout');
if (is_null($category_archive_layout)) {
	$category_archive_layout = 1;
}
$port->set_settings(
	array(
		'items_per_line'      => apply_filters('gt3/archive/taxonomy/columns', $category_archive_layout),
		'query'               => $wp_query,
		'grid_type'           => gt3_option('category_archive_type'),
		'meta_author'         => 'yes',
		'meta_comments'       => 'yes',
		'meta_categories'     => 'yes',
		'meta_date'           => 'yes',
		'meta_position'       => 'before_title',
		'post_btn_link'       => 'yes',
		'symbol_count_descrt' => array(
			'size' => '280',
			'unit' => 'px',
		),
	)
);
$port->print_element();


