<?php

if(!defined('ABSPATH')) {
	exit;
}

use Elementor\Utils;

/** @var \ElementorModal\Widgets\GT3_Core_Elementor_Widget_PriceBox $widget */


$settings = array(
	'header_img'       => array( 'url' => Utils::get_placeholder_image_src(), ),
	'pre_title'        => '',
	'header_img_2'     => array( 'url' => Utils::get_placeholder_image_src(), ),
	'title'            => '',
	'price_prefix'     => '',
	'price'            => '',
	'price_suffix'     => '',
	'content'          => '',
	'button_text'      => '',
	'button_link'      => array( 'url' => '#', 'is_external' => false, 'nofollow' => false, ),
	'add_label'        => false,
	'label_text'       => '',
	'button_border_en' => false,
	'button_border'    => '',
);

$settings = wp_parse_args($widget->get_settings(), $settings);

$widget->add_render_attribute('gt3_item_cost_wrapper', 'class', 'gt3_item_cost_wrapper');

$widget->add_render_attribute('wrapper', 'class', array(
	'gt3_pricebox_module_wrapper',
	isset($settings['view_type']) ? esc_attr($settings['view_type']) : '',
));
?>
<div <?php $widget->print_render_attribute_string('wrapper') ?>>
	<div class="gt3_price_item-elementor">
		<div class="gt3_price_item_wrapper-elementor">
			<?php if (isset($settings['view_type']) && $settings['view_type'] == 'type5') { ?>
			<div class="gt3_price_item_wrapper-container">
			<?php } ?>
				<?php if (isset($settings['view_type']) && $settings['view_type'] == 'type2') { ?>
					<div class="gt3_price_item-wrapper_block">
				<?php }
				if (isset($settings['view_type']) && ($settings['view_type'] == 'type3' || $settings['view_type'] == 'type4' || $settings['view_type'] == 'type5')) { ?>
				<div <?php $widget->print_render_attribute_string('gt3_item_cost_wrapper') ?>>
					<?php
					if(!empty($settings['title'])) { ?>
						<div class="price_item_title-elementor"><h3><?php echo esc_html($settings['title']) ?></h3></div>
					<?php }
					?>
				</div>
				<?php
				}
				?>
				<div class="gt3_price_item-cost-elementor">
					<?php
					if(!empty($settings['price_prefix'])) {
						echo '<span class="price_item_prefix-elementor">'.esc_html($settings['price_prefix']).'</span>';
					}
					echo esc_html($settings['price']);
					if(!empty($settings['price_suffix'])) {
						echo '<span class="price_item_suffix-elementor">'.esc_html($settings['price_suffix']).'</span>';
					}
					if (isset($settings['view_type']) && $settings['view_type'] == 'type1') {
						?>
						<span class="inner_circle"></span>
						<span class="inner_2_circles"></span><?php
						}
					?>
				</div>
				<?php if (isset($settings['view_type']) && ($settings['view_type'] != 'type3' || $settings['view_type'] != 'type4' || $settings['view_type'] != 'type5')) { ?>
				<div <?php $widget->print_render_attribute_string('gt3_item_cost_wrapper') ?>>
					<?php
					if(!empty($settings['title'])) { ?>
						<div class="price_item_title-elementor"><h3><?php echo esc_html($settings['title']) ?></h3></div>
					<?php }
					?>
				</div>
				<?php
				}
				if (isset($settings['view_type']) && $settings['view_type'] == 'type2') { ?>
					</div>
				<?php } ?>

				<?php
				if(!empty($settings['add_label']) && !empty($settings['label_text'])) {
					echo '<div class="label_text"><span>'.esc_html($settings['label_text']).'</span></div>';
				}
				$widget->add_render_attribute('content','class','items_text-price');
				$widget->add_inline_editing_attributes('content','advanced');
				?>
			<?php if (isset($settings['view_type']) && $settings['view_type'] == 'type5') { ?>
			</div>
			<?php } ?>
			<div class="gt3_price_item_body-elementor">
				<div <?php $widget->print_render_attribute_string('content') ?>><?php echo ''.$settings['content'] ?></div>
				<?php
				// Button
				if(!empty($settings['button_text']) && !empty($settings['button_link']['url'])) {
					$widget->add_render_attribute('link', 'href', $settings['button_link']['url']);
					$widget->add_render_attribute('link', 'class', 'shortcode_button button_size_normal');
					if(!empty($settings['button_link']['is_external'])) {
						$widget->add_render_attribute('link', 'target', '_blank');
					}
					if(!empty($settings['button_link']['nofollow'])) {
						$widget->add_render_attribute('link', 'rel', 'nofollow');
					}
					if((bool) $settings['button_border_en']) {
						$widget->add_render_attribute('link', 'class', 'bordered');
					}

					$widget->add_render_attribute('button_icon', 'class', 'price-button-icon');

					if((bool) $settings['button_icon_en']) {
						$widget->add_render_attribute('button_icon', 'class', $settings['button_icon']);
					}
					?>
					<div class="price_button-elementor">
						<a <?php $widget->print_render_attribute_string('link') ?>>
							<span class="gt3_module_button__container">
								<?php
								if($settings['button_icon_position'] == 'left' && (bool) $settings['button_icon_en']) {
									echo '<div '.$widget->get_render_attribute_string('button_icon').'></div>';
								}
								?>
								<span class="gt3_price_button__text"><?php echo esc_html($settings['button_text']) ?></span>
								<span class="gt3_module_button__cover front<?php echo empty($settings['button_border_color']) ? ' empty_border_color' : ''; ?>"></span>
								<span class="gt3_module_button__cover back<?php echo empty($settings['button_border_color_hover']) ? ' empty_border_color' : ''; ?>"></span>
								<?php
								if($settings['button_icon_position'] == 'right' && (bool) $settings['button_icon_en']) {
									echo '<div '.$widget->get_render_attribute_string('button_icon').'></div>';
								}
								?>
							</span>
						</a>
					</div>
					<?php
					// Button end
					if($settings['package_is_active'] == 'yes') {
						echo '<div class="featured-label_icon-price"></div>';
					}
				}
				?>
			</div>
		</div>
	</div>
</div>
<?php


