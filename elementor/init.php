<?php

use Elementor\Controls_Manager;
use Elementor\Core\Kits\Documents\Tabs\Global_Colors;

add_filter('gt3/core/builder_support', function($supports){
	$supports[] = 'elementor';

	return $supports;
});

add_filter('gt3/elementor/widgets/register', function($widgets){
	$widgets = array(
		'Testimonials', /* + */
		'TestimonialsLite', /* + */
		'Tabs', /* + */
		'AdvancedTabs', /* + */
		'Accordion', /* + */
		'Divider', /* + */
		'Blog', /* + */
		'BlogBoxed', /* + */
		'BlogPackery', /* + */
		'Button', /* + */
		'Portfolio', /* + */
		'PortfolioCarousel', /* + */
		'Team', /* + */
		'ProcessBar', /* + */
		'PieChart',  /* + */
		'GoogleMap', /* + */
		'CustomMeta', /* + */
		'Sharing', /* + */
		'ImageCarousel', /* + */
		'ImageInfoBox',/* + */
		'Flipbox', /* + */
		'PriceBox', /* + */
		'Countdown', /* + */
		'Blockquote', /* + */
		'ImageBox', /* + */
		'videopopup', /* + */
		'ImageProcessBar',/* + */
		//'EmptySpace',
		'Counter', /* + */
		'InfoList',
		'TypedText', /* + */
		'DesignDraw', /* + */
		'Hotspot',
		'AnimatedHeadlines',
	);
	if (class_exists('RevSlider')) {
		$widgets[] = 'RevolutionSlider';
	}
	if (class_exists('WooCommerce')) {
		$widgets[] = 'ShopList';
}
	return $widgets;
});

add_filter('gt3/core/assets/elementor_widgets_assets', function($widgets) {
	return array_merge($widgets, array(
		"accordion",
		"counter",
		"image-carousel",
		"progress",
		"tabs",
		"toggle",
	));
});

add_action('elementor/element/gt3-core-blog/general_section/before_section_end', function($element,$args){
	/* @var \Elementor\Widget_Base $element */
	$element->update_control('packery_en',array(
		'condition'=>array(
			'show'=>'never'
		)
	));
	$element->update_control('static_info_block',array(
		'condition'=>array(
			'show'=>'never'
		)
	));
},20,2);

add_action('elementor/element/gt3-core-tabs/style_section/before_section_end', function($element,$args){
	/* @var \Elementor\Widget_Base $element */
	$element->update_control('tab_padding',array(
		'default'     => array(
			'top' => '19',
			'right' => '30',
			'bottom' => '19',
			'left' => '30',
		)
	));
	$element->update_control('tab_border_radius',array(
		'default'     => array(
			'size' => 0,
			'unit' => 'px',
		),
	));


},20,2);

add_action('elementor/element/gt3-core-processbar/style_section/before_section_end', function($element,$args){

	$items = $element->get_controls('items');
	$items_fields = $items['fields'];

	$item_color = array(
        'proc_color' => array(
            'label'     => __( 'Color', 'ewebot' ),
            'type'      => Elementor\Controls_Manager::COLOR,
            'name'      => 'proc_color',
            'selectors'   => array(
            	'{{WRAPPER}}.elementor-widget-gt3-core-processbar .gt3_process_item{{CURRENT_ITEM}} .gt3_process_item__circle_wrapp' => 'color: {{VALUE}};',
				'{{WRAPPER}}.elementor-widget-gt3-core-processbar .gt3_process_item{{CURRENT_ITEM}} .gt3_process_item__content_wrapper' => 'border-color: {{VALUE}};',

				'{{WRAPPER}}.elementor-widget-gt3-core-processbar .gt3_process_item{{CURRENT_ITEM}} .gt3_process_item__content_wrapper' => 'border-color: {{VALUE}};',
				'{{WRAPPER}}.elementor-widget-gt3-core-processbar .gt3_process_item{{CURRENT_ITEM}} .gt3_process_item__number' => 'color: {{VALUE}};',
			),
        )
    );

    $element->update_control('items',array(
        'fields' => $items_fields+$item_color
    ));

	$element->add_control(
		'heading_color',
		array(
			'label'   => esc_html__('Heading Color','ewebot'),
			'type'    => Elementor\Controls_Manager::COLOR,
			'selectors'   => array(
				'{{WRAPPER}}.elementor-widget-gt3-core-processbar  .gt3_process_item__heading' => 'color: {{VALUE}};',
				'{{WRAPPER}}.elementor-widget-gt3-core-processbar .gt3_process_item .gt3_process_item__circle_wrapp .gt3_process_item__circle_line_before' => 'background-image: linear-gradient(90deg, transparent 0%, {{VALUE}} 100%);background-color: {{VALUE}};',
				'{{WRAPPER}}.elementor-widget-gt3-core-processbar .gt3_process_item .gt3_process_item__circle_wrapp .gt3_process_item__circle_line_after' => 'background-image: linear-gradient(90deg, {{VALUE}} 0%, transparent 100% );background-color: {{VALUE}};',
				'{{WRAPPER}}.elementor-widget-gt3-core-processbar.vertical_style-2 .gt3_process_item .gt3_process_item__circle_wrapp .gt3_process_item__circle_line_before' => 'background-image: linear-gradient(0deg, {{VALUE}} 0%, transparent 100%);background-color: {{VALUE}};',
				'{{WRAPPER}}.elementor-widget-gt3-core-processbar.vertical_style-2 .gt3_process_item .gt3_process_item__circle_wrapp .gt3_process_item__circle_line_after' => 'background-image: linear-gradient(0deg, transparent 0%, {{VALUE}} 100% );background-color: {{VALUE}};',



			),
		),
        array(
            'position' => array(
                'type' => 'control',
                'at' => 'after',
                'of' => 'tab_color'
            )
        )
	);
	$element->add_control(
		'text_color',
		array(
			'label'   => esc_html__('Text Color','ewebot'),
			'type'    => Elementor\Controls_Manager::COLOR,
			'selectors'   => array(
				'{{WRAPPER}}.elementor-widget-gt3-core-processbar  .gt3_process_item__description' => 'color: {{VALUE}};',
			),
		),
        array(
            'position' => array(
                'type' => 'control',
                'at' => 'after',
                'of' => 'heading_color'
            )
        )
	);

	$element->add_control(
		'line_color',
		array(
			'label'   => esc_html__('Line Color','ewebot'),
			'type'    => Elementor\Controls_Manager::COLOR,
			'selectors'   => array(
				'{{WRAPPER}}.elementor-widget-gt3-core-processbar .gt3_process_item .gt3_process_item__circle_wrapp .gt3_process_item__circle_line_before' => 'background-image: linear-gradient(90deg, transparent 0%, {{VALUE}} 100%);background-color: {{VALUE}};',
				'{{WRAPPER}}.elementor-widget-gt3-core-processbar .gt3_process_item .gt3_process_item__circle_wrapp .gt3_process_item__circle_line_after' => 'background-image: linear-gradient(90deg, {{VALUE}} 0%, transparent 100% );background-color: {{VALUE}};',
				'{{WRAPPER}}.elementor-widget-gt3-core-processbar.vertical_style-2 .gt3_process_item .gt3_process_item__circle_wrapp .gt3_process_item__circle_line_before' => 'background-image: linear-gradient(0deg, {{VALUE}} 0%, transparent 100%);background-color: {{VALUE}};',
				'{{WRAPPER}}.elementor-widget-gt3-core-processbar.vertical_style-2 .gt3_process_item .gt3_process_item__circle_wrapp .gt3_process_item__circle_line_after' => 'background-image: linear-gradient(0deg, transparent 0%, {{VALUE}} 100% );background-color: {{VALUE}};',



			),
		),
        array(
            'position' => array(
                'type' => 'control',
                'at' => 'after',
                'of' => 'text_color'
            )
        )
	);


	$element->update_control('tab_color',array(
		'selectors'   => array(
			'{{WRAPPER}}.elementor-widget-gt3-core-processbar .gt3_process_item__content_wrapper' => 'border-color: {{VALUE}};',
			'{{WRAPPER}}.elementor-widget-gt3-core-processbar' => 'color: {{VALUE}};',
			'{{WRAPPER}}.elementor-widget-gt3-core-processbar .gt3_process_item__number' => 'color: {{VALUE}};',
			'{{WRAPPER}}.elementor-widget-gt3-core-processbar .gt3_process_item .gt3_process_item__circle_wrapp .gt3_process_item__circle_line_before' => 'background-image: linear-gradient(90deg, transparent 0%, {{VALUE}} 100%);background-color: {{VALUE}};',
			'{{WRAPPER}}.elementor-widget-gt3-core-processbar .gt3_process_item .gt3_process_item__circle_wrapp .gt3_process_item__circle_line_after' => 'background-image: linear-gradient(90deg, {{VALUE}} 0%, transparent 100% );background-color: {{VALUE}};',
			'{{WRAPPER}}.elementor-widget-gt3-core-processbar.vertical_style-2 .gt3_process_item .gt3_process_item__circle_wrapp .gt3_process_item__circle_line_before' => 'background-image: linear-gradient(0deg, {{VALUE}} 0%, transparent 100%);background-color: {{VALUE}};',
			'{{WRAPPER}}.elementor-widget-gt3-core-processbar.vertical_style-2 .gt3_process_item .gt3_process_item__circle_wrapp .gt3_process_item__circle_line_after' => 'background-image: linear-gradient(0deg, transparent 0%, {{VALUE}} 100% );background-color: {{VALUE}};',
		),
	));


},20,2);

if (class_exists('\gt3_photo_video_galery_pro')) {
	gt3_photo_video_galery_pro::instance()->actions();
	if (class_exists('\gt3pg_pro_plugin_updater')) {
		gt3pg_pro_plugin_updater::instance()->status = 'valid';
	}
}

if (class_exists('\GT3\PhotoVideoGalleryPro\Autoload')) {
	\GT3\PhotoVideoGalleryPro\Autoload::instance()->Init();
}

// Meta
add_filter( 'gt3/core/render/blog/listing_meta', function ($compile) {
	return '<div class="listing_meta_wrap">'.$compile.'</div>';
});

// Media height
add_filter( 'gt3/core/render/blog/media_height', function () {
	return '700';
});

// Post comments
add_filter( 'gt3/core/render/blog/post_comments', function () {

	if (get_comments_number(get_the_ID()) == 1) {
		$comments_text = esc_html__('Comment', 'ewebot');
	} else {
		$comments_text = esc_html__('Comments', 'ewebot');
	}

	if ((int)get_comments_number(get_the_ID()) != 0) {
		return '<span class="post_comments"><a href="' . esc_url(get_comments_link()) . '" title="' . esc_attr(get_comments_number(get_the_ID())) . ' ' . $comments_text . '">' . esc_html(get_comments_number(get_the_ID())) . ' ' . $comments_text . '</a></span>';
	}

});

// Post author
add_filter( 'gt3/core/render/blog/post_author', function () {
	return '<span class="post_author">' . esc_html__('by', 'ewebot') . ' <a href="' . esc_url(get_author_posts_url(get_the_author_meta('ID'))) . '">' . esc_html(get_the_author_meta('display_name')) . '</a></span>';
});

// Post bottom Area
add_filter( 'gt3/core/render/blog/listing_btn', function ($listing_btn, $settings) {

	$show_likes = gt3_option('blog_post_likes');
	$show_share = gt3_option('blog_post_share');

	if (gt3_customizer_enabled()) {
		$show_likes = true;
		$show_share = true;
	}

	$all_likes = gt3pb_get_option("likes");

	$featured_image = wp_get_attachment_image_src(get_post_thumbnail_id(get_the_ID()), 'single-post-thumbnail');

	$btn_compile = '<div class="clear post_clear"></div><div class="gt3_post_footer">';

	if(!empty($settings['post_btn_link'])) {
		$btn_compile .= '<div class="gt3_module_button_list"><a href="'. esc_url(get_permalink()) .'">'. $settings['post_btn_link_title'] .'</a></div>';
	}

	if ($show_share == "1" || $show_likes == "1" || true === $show_share || true === $show_likes) {
		$btn_compile .= '<div class="blog_post_info">';

		if (function_exists('gt3_blog_post_sharing')) {
			ob_start();
			gt3_blog_post_sharing($show_share,$featured_image);
			$btn_compile .= ob_get_clean();
		}

		if (function_exists('gt3_blog_post_likes')) {
			ob_start();
			gt3_blog_post_likes($show_likes,$all_likes);
			$btn_compile .= ob_get_clean();
		}

		$btn_compile .= '</div>';
	}

	$btn_compile .= '<div class="clear"></div></div>';

	return $btn_compile;

}, 10, 2);

// BlogBoxed
add_filter( 'gt3/core/render/blogboxed/block_wrap_start', function () {
	return '<div class="gt3blogboxed_block_wrap">';
});

add_filter( 'gt3/core/render/blogboxed/block_wrap_end', function () {
	return '</div>';
});

add_filter( 'gt3/core/render/blogboxed/post_links_block', function ($post_btn_link) {

	$show_likes = gt3_option('blog_post_likes');
	$show_share = gt3_option('blog_post_share');

	if (gt3_customizer_enabled()) {
		$show_likes = true;
		$show_share = true;
	}

	$all_likes = gt3pb_get_option("likes");
	$featured_image = wp_get_attachment_image_src(get_post_thumbnail_id(get_the_ID()), 'single-post-thumbnail');

	$btn_compile = '<div class="gt3_post_footer">'.$post_btn_link;

	if ($show_share == "1" || $show_likes == "1" || true === $show_share || true === $show_likes) {
		$btn_compile .= '<div class="blog_post_info">';

		if (function_exists('gt3_blog_post_sharing')) {
			ob_start();
			gt3_blog_post_sharing($show_share,$featured_image);
			$btn_compile .= ob_get_clean();
		}

		if (function_exists('gt3_blog_post_likes')) {
			ob_start();
			gt3_blog_post_likes($show_likes,$all_likes);
			$btn_compile .= ob_get_clean();
		}

		$btn_compile .= '</div>';
	}

	$btn_compile .= '</div>';

	return $btn_compile;
});

// Team
add_filter( 'gt3/core/render/team/team_img_prop', function () {
	$img_ratio = '1.2125';
	return $img_ratio;
});

// Price Table Type Controls Added
add_action('elementor/element/gt3-core-pricebox/basic_section/after_section_start', function($element,$args){
	/* @var \Elementor\Widget_Base $element */
	$element->add_control(
		'view_type',
		array(
			'label'       => esc_html__('View Type', 'ewebot'),
			'type'        => Elementor\Controls_Manager::SELECT,
			'options'     => array(
				'type1' => esc_html__('Type 1', 'ewebot'),
				'type2' => esc_html__('Type 2', 'ewebot'),
				'type3' => esc_html__('Type 3', 'ewebot'),
				'type4' => esc_html__('Type 4', 'ewebot'),
				'type5' => esc_html__('Type 5', 'ewebot'),
			),
			'default'     => 'type1',
		)
	);
},20,2);

add_action('elementor/element/gt3-core-pricebox/basic_section/before_section_end', function($element,$args){
    /* @var \Elementor\Widget_Base $element */
    $element->remove_control('header_img');
    $element->remove_control('header_img_2');
    $element->remove_control('hover_effect_block');
    $element->remove_control('pre_title');
    $element->remove_control('add_label');
    $element->remove_control('label_text');



    $element->update_control(
		'title',
		array(
			'label'       => esc_html__( 'Package Name / Title', 'ewebot' ),
			'type'        => Elementor\Controls_Manager::TEXT,
			'description' => esc_html__( "Enter title of price block", 'ewebot' ),
			'default' => esc_html__( 'Base Plan', 'ewebot' ),
		)
	);

	$element->update_control(
		'price_prefix',
		array(
			'label'       => esc_html__( 'Price Prefix ', 'ewebot' ),
			'type'        => Elementor\Controls_Manager::TEXT,
			'description' => esc_html__( 'Enter the price prefix for this package. e.g. "$"', 'ewebot' ),
			'default' => esc_html__( '$', 'ewebot' ),
		)
	);

	$element->update_control(
		'price',
		array(
			'label'       => esc_html__( 'Package Price', 'ewebot' ),
			'type'        => Elementor\Controls_Manager::TEXT,
			'default' => esc_html__( '49', 'ewebot' ),
			'description' => esc_html__( 'Enter the price for this package. e.g. "157"', 'ewebot' ),
		)
	);

	$element->update_control(
		'price_suffix',
		array(
			'label'       => esc_html__( 'Price Suffix', 'ewebot' ),
			'type'        => Elementor\Controls_Manager::TEXT,
			'default' => esc_html__( '99', 'ewebot' ),
			'description' => esc_html__( 'Enter the price suffix for this package. e.g. "/ person"', 'ewebot' ),
		)
	);

	$element->update_control(
		'content',
		array(
			'label' => esc_html__( 'Price Field', 'ewebot' ),
			'type'  => Elementor\Controls_Manager::WYSIWYG,
			'default' => '<ul>
 	<li>SEO Audits</li>
 	<li>SEO Management</li>
 	<li>SEO Copywriting</li>
 	<li>Link Building</li>
 	<li>Site Megration</li>
</ul>',
		)
	);

	$element->update_control(
		'button_text',
		array(
			'label' => esc_html__( 'Button Text', 'ewebot' ),
			'type'  => Elementor\Controls_Manager::TEXT,
			'default' => esc_html__( 'Get Started', 'ewebot' ),
		)
	);



},20,2);

add_action('elementor/element/gt3-core-pricebox/image_style_section_section/before_section_start', function($element,$args){

    $element->start_controls_section(
        'item_style_section',
        array(
            'label' => esc_html__( 'Item', 'ewebot' ),
            'tab'   => Elementor\Controls_Manager::TAB_STYLE
        )
    );


    $element->add_group_control(
        Elementor\Group_Control_Background::get_type(),
        [
            'name' => 'item_background',
            'types' => array('classic','gradient'),
            'fields_options' => array(
                'image' => [
                    'condition' => [
                        'show' => 'never',
                    ],
                ],
                'color' => [
					'selectors' => [
						'{{WRAPPER}} .gt3_pricebox_module_wrapper.type1 .gt3_price_item-cost-elementor, {{WRAPPER}} .gt3_pricebox_module_wrapper.type1 .gt3_price_item-cost-elementor span.inner_2_circles:before, {{WRAPPER}} .gt3_pricebox_module_wrapper.type1 .gt3_price_item-cost-elementor span.inner_2_circles:after, {{WRAPPER}} .gt3_pricebox_module_wrapper.type1 .gt3_price_item-cost-elementor span.inner_circle, {{WRAPPER}}  .price_button-elementor span.gt3_module_button__cover.front:before, {{WRAPPER}} .price_button-elementor span.gt3_module_button__cover.back:before, {{WRAPPER}} .gt3_pricebox_module_wrapper.type2 .gt3_price_item-elementor .gt3_item_cost_wrapper h3, {{WRAPPER}}.active-package-yes.elementor-widget-gt3-core-pricebox .type2 .gt3_price_item_body-elementor, {{WRAPPER}} .gt3_pricebox_module_wrapper.type2 .gt3_price_item-wrapper_block:before, {{WRAPPER}}.active-package-yes .gt3_pricebox_module_wrapper.type3 .gt3_price_item_wrapper-elementor, {{WRAPPER}}.active-package-yes .gt3_pricebox_module_wrapper.type4 .gt3_price_item_wrapper-elementor, {{WRAPPER}}.active-package-yes .gt3_pricebox_module_wrapper.type5 .gt3_price_item_wrapper-elementor .gt3_price_item_wrapper-container' => 'background-color: {{VALUE}}; background-image: none; border-color: {{VALUE}};',
						'{{WRAPPER}}.elementor-widget-gt3-core-pricebox .gt3_pricebox_module_wrapper.type1 .shortcode_button:hover' => 'color: {{VALUE}};',
						'{{WRAPPER}}.elementor-widget-gt3-core-pricebox .gt3_pricebox_module_wrapper.type2 .shortcode_button:hover' => 'color: {{VALUE}};',
						'{{WRAPPER}} .gt3_pricebox_module_wrapper.type1 .gt3_price_item-cost-elementor span.inner_circle' => 'border-color: {{VALUE}};',
						'{{WRAPPER}} .gt3_pricebox_module_wrapper.type1 .price_button-elementor .shortcode_button .gt3_module_button__cover.back:before' => 'border-color: {{VALUE}};',
						'{{WRAPPER}} .gt3_pricebox_module_wrapper.type2 .price_button-elementor .shortcode_button .gt3_module_button__cover.back:before, {{WRAPPER}} .gt3_pricebox_module_wrapper.type3 .price_button-elementor .shortcode_button .gt3_module_button__cover.back:before, {{WRAPPER}} .gt3_pricebox_module_wrapper.type4 .price_button-elementor .shortcode_button .gt3_module_button__cover.back:before' => 'border-color: {{VALUE}};',
						'{{WRAPPER}} .gt3_pricebox_module_wrapper.type2 .price_button-elementor .shortcode_button:hover, {{WRAPPER}} .gt3_pricebox_module_wrapper.type3 .price_button-elementor .shortcode_button:hover, {{WRAPPER}} .gt3_pricebox_module_wrapper.type4 .price_button-elementor .shortcode_button:hover, {{WRAPPER}}:not(.active-package-yes) .gt3_pricebox_module_wrapper.type3 .gt3_price_item-cost-elementor, {{WRAPPER}}:not(.active-package-yes) .gt3_pricebox_module_wrapper.type4 .gt3_price_item-cost-elementor, {{WRAPPER}}.active-package-yes .gt3_pricebox_module_wrapper.type2 .price_button-elementor .shortcode_button,
						{{WRAPPER}}.active-package-yes .gt3_pricebox_module_wrapper.type3 .price_button-elementor .shortcode_button, {{WRAPPER}}.active-package-yes .gt3_pricebox_module_wrapper.type4 .price_button-elementor .shortcode_button' => 'color: {{VALUE}};',
					],
				],
				'color_b' => [
					'selectors' => [
						'{{WRAPPER}}.elementor-widget-gt3-core-pricebox .gt3_pricebox_module_wrapper.type1 .shortcode_button:hover' => 'color: {{VALUE}};',
						'{{WRAPPER}}.elementor-widget-gt3-core-pricebox .gt3_pricebox_module_wrapper.type2 .shortcode_button:hover' => 'color: {{VALUE}};',
						'{{WRAPPER}} .gt3_pricebox_module_wrapper.type1 .gt3_price_item-cost-elementor span.inner_circle' => 'border-color: {{VALUE}};',
						'{{WRAPPER}} .gt3_pricebox_module_wrapper.type1 .price_button-elementor .shortcode_button .gt3_module_button__cover.back:before' => 'border-color: {{VALUE}};',
						'{{WRAPPER}} .gt3_pricebox_module_wrapper.type2 .price_button-elementor .shortcode_button .gt3_module_button__cover.back:before, {{WRAPPER}} .gt3_pricebox_module_wrapper.type3 .price_button-elementor .shortcode_button .gt3_module_button__cover.back:before, {{WRAPPER}} .gt3_pricebox_module_wrapper.type4 .price_button-elementor .shortcode_button .gt3_module_button__cover.back:before' => 'border-color: {{VALUE}};',
						'{{WRAPPER}} .gt3_pricebox_module_wrapper.type2 .price_button-elementor .shortcode_button:hover, {{WRAPPER}} .gt3_pricebox_module_wrapper.type3 .price_button-elementor .shortcode_button:hover, {{WRAPPER}} .gt3_pricebox_module_wrapper.type4 .price_button-elementor .shortcode_button:hover, {{WRAPPER}}:not(.active-package-yes) .gt3_pricebox_module_wrapper.type3 .gt3_price_item-cost-elementor, {{WRAPPER}}:not(.active-package-yes) .gt3_pricebox_module_wrapper.type4 .gt3_price_item-cost-elementor, {{WRAPPER}}.active-package-yes .gt3_pricebox_module_wrapper.type2 .price_button-elementor .shortcode_button,
						{{WRAPPER}}.active-package-yes .gt3_pricebox_module_wrapper.type3 .price_button-elementor .shortcode_button, {{WRAPPER}}.active-package-yes .gt3_pricebox_module_wrapper.type4 .price_button-elementor .shortcode_button' => 'color: {{VALUE}};',
					],
				],
                'gradient_angle' => [
                    'default' => [
                        'unit' => 'deg',
                        'size' => 90,
                    ],
                    'selectors' => [
                        '{{WRAPPER}} .gt3_pricebox_module_wrapper.type1 .gt3_price_item-cost-elementor, {{WRAPPER}} .gt3_pricebox_module_wrapper.type1 .gt3_price_item-cost-elementor span.inner_2_circles:before, {{WRAPPER}} .gt3_pricebox_module_wrapper.type1 .gt3_price_item-cost-elementor span.inner_2_circles:after, {{WRAPPER}}  .price_button-elementor span.gt3_module_button__cover.front:before, {{WRAPPER}} .price_button-elementor span.gt3_module_button__cover.back:before, {{WRAPPER}} .gt3_pricebox_module_wrapper.type2 .gt3_price_item-elementor .gt3_item_cost_wrapper h3, {{WRAPPER}}.active-package-yes.elementor-widget-gt3-core-pricebox .type2 .gt3_price_item_body-elementor, {{WRAPPER}} .gt3_pricebox_module_wrapper.type2 .gt3_price_item-wrapper_block:before, {{WRAPPER}}.active-package-yes.elementor-widget-gt3-core-pricebox .gt3_pricebox_module_wrapper.type3 .gt3_price_item_wrapper-elementor, {{WRAPPER}}.active-package-yes.elementor-widget-gt3-core-pricebox .gt3_pricebox_module_wrapper.type4 .gt3_price_item_wrapper-elementor, {{WRAPPER}}.active-package-yes.elementor-widget-gt3-core-pricebox .gt3_pricebox_module_wrapper.type5 .gt3_price_item_wrapper-elementor .gt3_price_item_wrapper-container' => 'background-color: transparent; background-image: linear-gradient({{SIZE}}{{UNIT}}, {{color.VALUE}} {{color_stop.SIZE}}{{color_stop.UNIT}}, {{color_b.VALUE}} {{color_b_stop.SIZE}}{{color_b_stop.UNIT}}); border-color: {{color_b.VALUE}};',
                    ],
                ],
            ),
        ]
    );


    $element->end_controls_section();


},20,2);


add_action('elementor/element/gt3-core-videopopup/basic_section_section/before_section_end', function($element,$args){
	$element->remove_control('btn_background_color');

	$element->add_group_control(
	    Elementor\Group_Control_Background::get_type(),
	    [
	        'name' => 'btn_background_color',
	        'types' => array('classic','gradient'),
	        'fields_options' => array(
	            'image' => [
	                'condition' => [
	                    'show' => 'never',
	                ],
	            ],
	            'color' => [
					'selectors' => [
						'{{WRAPPER}} .video-popup__link' => 'background-color: {{VALUE}};',
					],
				],
				'color_b' => [
					'selectors' => [
	                    '{{WRAPPER}} .video-popup-animation' => 'color: {{VALUE}};',
	                ],
				],
	            'gradient_angle' => [
	                'default' => [
	                    'unit' => 'deg',
	                    'size' => 90,
	                ],
	                'selectors' => [
	                    '{{WRAPPER}} .video-popup__link' => 'background-image: linear-gradient({{SIZE}}{{UNIT}}, {{color.VALUE}} {{color_stop.SIZE}}{{color_stop.UNIT}}, {{color_b.VALUE}} {{color_b_stop.SIZE}}{{color_b_stop.UNIT}});',
	                ],
	            ],
	        ),
	    ],
	    array(
            'position' => array(
                'type' => 'control',
                'at' => 'after',
                'of' => 'btn_color'
            )
        )
	);

},20,2);


add_action('elementor/element/gt3-core-pricebox/text_style_section_section/before_section_end', function($element,$args){
    $element->update_control(
		'title_color',
		array(
			'label'     => esc_html__( 'Color', 'ewebot' ),
			'type'      => Elementor\Controls_Manager::COLOR,
			'selectors' => array(
				'{{WRAPPER}} .price_item_title-elementor h3' => 'color: {{VALUE}}; background-image:none !important;-webkit-text-fill-color: unset !important;',
			),
			'separator' => 'none',
		)
	);
},20,2);

add_filter( 'gt3/core/start_controls_section/image_style_section_section', function() {
    return array(
        'show' => 'never'
    );
});

add_filter( 'gt3/core/start_controls_section/pre_title_style_section_section', function() {
    return array(
        'show' => 'never'
    );
});

add_filter( 'gt3/core/start_controls_section/image_style_section_2_section', function() {
    return array(
        'show' => 'never'
    );
});

add_filter( 'gt3/core/start_controls_section/label_style_section_section', function() {
    return array(
        'show' => 'never'
    );
});



// TestimonialsLite Controls
add_filter( 'gt3/core/render/TestimonialsLite/block_wrap_start', function () {
	return '<div class="gt3_aside_title_wrap">';
});

add_filter( 'gt3/core/render/TestimonialsLite/block_wrap_end', function () {
	return '</div>';
});

add_action('elementor/element/gt3-core-pricebox/button_style_section_section/before_section_end', function($element,$args){
	/* @var \Elementor\Widget_Base $element */
	$element->remove_control('btn_color');
	$element->remove_control('btn_bg_color');
	$element->remove_control('button_border');
	$element->remove_control('button_border_color');
	$element->remove_control('button_border_width');
	$element->remove_control('button_border_radius');
    $element->remove_control('btn_bg_color_hover');
    $element->remove_control('btn_color_hover');
    $element->remove_control('button_border_color_hover');
    $element->remove_control('button_border_en');
    $element->remove_control('button_border');
    $element->remove_control('button_border_width');
    $element->remove_control('button_border_radius');
    $element->remove_control('style_tabs');
    $element->remove_control('digit_tab');
    $element->remove_control('description_tab');



},20,2);


add_action('elementor/element/gt3-core-TestimonialsLite/section_style_testimonial_image_section/before_section_end', function($element,$args){
	/* @var \Elementor\Widget_Base $element */
	$element->update_control(
		'image_size',
		array(
			'label' => __( 'Image Size', 'ewebot' ),
			'type' => Elementor\Controls_Manager::SLIDER,
			'size_units' => array( 'px' ),
			'range' => array(
				'px' => array(
					'min' => 20,
					'max' => 200,
				),
			),
			'default' => array(
				'size' => 60
			),
			'selectors' => array(
				'{{WRAPPER}} .testimonials_author_wrapper .testimonials_photo img' => 'width: {{SIZE}}{{UNIT}} !important;height: {{SIZE}}{{UNIT}} !important;',
				'{{WRAPPER}} .testimonials_author_wrapper .testimonials_photo' => 'height: {{SIZE}}{{UNIT}};',
				'{{WRAPPER}} .testimonials_avatar_slider .testimonials_avatar_item' => 'width: {{SIZE}}{{UNIT}};',
				'{{WRAPPER}} .testimonials_avatar_slider .testimonials_author_rotator' => 'width: calc({{SIZE}}{{UNIT}} * 3);',
				'{{WRAPPER}} .testimonials-text-quote-holder' => 'top: {{SIZE}}{{UNIT}};',
			),
		)
	);
},20,2);

// Progress Type Controls Added
add_action('elementor/element/progress/section_progress/before_section_end', function($element,$args){
	/* @var \Elementor\Widget_Base $element */
	$element->remove_control('inner_text');
},20,2);

add_action('elementor/element/progress/section_progress_style/before_section_end', function($element,$args){
	/* @var \Elementor\Widget_Base $element */
	$element->update_control('bar_color',array(
		'label' => __( 'Color', 'ewebot' ),
		'type' => Elementor\Controls_Manager::COLOR,
		'selectors' => array(
			'{{WRAPPER}} .elementor-progress-wrapper .elementor-progress-bar' => 'background-color: {{VALUE}}; color: {{VALUE}};',
		),
	));
	$element->update_control('bar_inline_color',array(
		'label' => __( 'Color', 'ewebot' ),
		'type' => Elementor\Controls_Manager::COLOR,
		'selectors' => array(
			'{{WRAPPER}} .elementor-progress-wrapper .elementor-progress-percentage' => 'color: {{VALUE}};',
		),
	));
},20,2);

// Media height
/*add_filter( 'gt3/core/init/portfolio/rectangle_ratio', function () {
	return '0.63';
});*/

// Heading & Typing Custom Gradient Text Color
add_action('elementor/element/heading/section_title_style/before_section_end', function($element,$args){
	$element->add_control(
		'enable_theme_textgradient',
		array(
			'label'       => esc_html__('Enable Text Color Gradient?', 'ewebot'),
			'type'        => Elementor\Controls_Manager::SWITCHER,
			'description' => esc_html__('If checked, enable text color gradient', 'ewebot'),
			'default'     => '',
			'label_block' => true,
			'prefix_class' => 'gt3_theme_textgradient-',
		)
	);
	$element->add_control(
		'textgradient_color_start',
		array(
			'label'   => esc_html__('Text Color Gradient Start','ewebot'),
			'type'    => Elementor\Controls_Manager::COLOR,
			'default' => '#ff4c6c',
			'selectors'   => array(
				'{{WRAPPER}} .elementor-heading-title' => '--textgradient_color1: {{VALUE}};',
			),
			'condition' => array(
				'enable_theme_textgradient!' => '',
			),
		)
	);
	$element->add_control(
		'textgradient_color_end',
		array(
			'label'   => esc_html__('Text Color Gradient End','ewebot'),
			'type'    => Elementor\Controls_Manager::COLOR,
			'default' => '#fa9d4d',
			'selectors'   => array(
				'{{WRAPPER}} .elementor-heading-title' => '--textgradient_color2: {{VALUE}};',
			),
			'condition' => array(
				'enable_theme_textgradient!' => '',
			),
		)
	);
	$element->update_control('title_color',array(
		'condition' => array(
			'enable_theme_textgradient' => '',
		),
	));
},20,2);

add_action('elementor/element/gt3-core-typed-text/style_section_section/before_section_end', function($element,$args){
	$element->add_control(
		'enable_theme_textgradient_typed',
		array(
			'label'       => esc_html__('Enable Text Color Gradient from Theme Options?', 'ewebot'),
			'type'        => Elementor\Controls_Manager::SWITCHER,
			'description' => esc_html__('If checked, enable text color gradient from theme options (string color ignored)', 'ewebot'),
			'default'     => '',
			'label_block' => true,
			'prefix_class' => 'gt3_theme_textgradient-',
		)
	);
	$element->add_control(
		'textgradient_color_start',
		array(
			'label'   => esc_html__('Text Color Gradient Start','ewebot'),
			'type'    => Elementor\Controls_Manager::COLOR,
			'default' => '#ff4c6c',
			'selectors'   => array(
				'{{WRAPPER}} .gt3_typed_widget' => '--textgradient_color1: {{VALUE}};',
			),
			'condition' => array(
				'enable_theme_textgradient_typed!' => '',
			),
		)
	);
	$element->add_control(
		'textgradient_color_end',
		array(
			'label'   => esc_html__('Text Color Gradient End','ewebot'),
			'type'    => Elementor\Controls_Manager::COLOR,
			'default' => '#fa9d4d',
			'selectors'   => array(
				'{{WRAPPER}} .gt3_typed_widget' => '--textgradient_color2: {{VALUE}};',
			),
			'condition' => array(
				'enable_theme_textgradient_typed!' => '',
			),
		)
	);
	$element->update_control('prefix_color',array(
		'condition' => array(
			'enable_theme_textgradient_typed' => '',
		),
	));
	$element->update_control('strings_color',array(
		'condition' => array(
			'enable_theme_textgradient_typed' => '',
		),
	));
	$element->update_control('suffix_color',array(
		'condition' => array(
			'enable_theme_textgradient_typed' => '',
		),
	));
},20,2);

// Blogboxed
add_action('elementor/element/gt3-core-blogboxed/general_section/before_section_end', function($element,$args){
	$element->add_control(
		'default_state_featured',
		array(
			'label'       => esc_html__('Featured Image on default state?', 'ewebot'),
			'type'        => Controls_Manager::SWITCHER,
			'description' => esc_html__('If checked featured image visible on default state (not hovered)', 'ewebot'),
			'prefix_class' => 'gt3_featured_default_state-',
			'condition' => array(
				'module_type' => 'type1',
				'post_featured_bg!' => '',
			),
		),
		array(
			'position' => array(
				'type' => 'control',
				'at' => 'after',
				'of' => 'post_featured_bg'
			)
		)
	);
	$element->update_control('featured_bg_opacity',array(
		'selectors'   => array(
			'{{WRAPPER}}.elementor-widget-gt3-core-blogboxed.gt3_featured_default_state-yes .module_type1 .item_wrapper .blogboxed_img_block' => 'opacity: {{SIZE}} !important;',
			'{{WRAPPER}}.elementor-widget-gt3-core-blogboxed:not(.gt3_featured_default_state-yes) .module_type1 .item_wrapper:hover .blogboxed_img_block' => 'opacity: {{SIZE}} !important;',
		),
	));
},20,2);

add_action('elementor/element/gt3-core-blogboxed/general_section/before_section_end', function($element,$args){
	/* @var \Elementor\Widget_Base $element */
	$modern_style_el = $element->get_controls('modern_style');
	if (is_null($modern_style_el)) return;
	unset($modern_style_el['condition']['show']);
	$element->update_control('modern_style', $modern_style_el);
},20,2);


	add_action('elementor/element/gt3-core-shoplist/style_section/before_section_end', function($element,$args){
		if (gt3_option('modern_shop') == '1' || true === gt3_option('modern_shop')) {

			/* @var \Elementor\Widget_Base $element */
		$element->update_control('grid_gap',array(
			'condition'=>array(
				'show'=>'never'
			)
		));
		}

	},20,2);

/* Image Carousel (Iphone View) */
add_filter( 'gt3_image_carousel_phone_recomend', function(){
	return esc_html__('Recommended image size is 246x536 pixels.', 'ewebot');
});

add_filter( 'gt3_image_carousel_device_width', function(){
	return '278';
});

add_filter( 'gt3_image_carousel_device_height', function(){
	return '566';
});

add_filter( 'gt3_image_carousel_phone_width', function(){
	return '246';
});

add_filter( 'gt3_image_carousel_phone_height', function(){
	return '536';
});
