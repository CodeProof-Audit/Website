<?php
update_option( 'gt3_registration_status', 'active' );
update_option( 'gt3_registration_supported_until', '12.12.2030' );
update_option( 'gt3_supported_notice_srart', false );
update_option( 'sdfgdsfgdfg' , 'Product is activated!' );
$gt3_redux_options = get_option( get_template(), [] );
$gt3_redux_options[ 'gt3_registration_id' ] = [ 'puchase_code' => 'puchase_code' ];
update_option( get_template(), $gt3_redux_options );
$theme    = wp_get_theme();
$is_child = $theme->get('Template');
if(!empty($is_child)) {
	$theme = wp_get_theme($is_child);
}

define('GT3_THEME_VERSION', $theme->get('Version'));
define('GT3_THEME_CUSTOMIZER', true);

add_filter('gt3/core/dashboard', '__return_true');
add_filter('gt3/core/customizer/enabled', '__return_true');

if(!function_exists('gt3_option')) {
	function gt3_option($name, $subkey = null){
		if(class_exists('\GT3\ThemesCore\Customizer') && GT3_THEME_CUSTOMIZER) {
			$customizer_theme_option = \GT3\ThemesCore\Customizer::instance()->get_option($name, $subkey);

			return isset($customizer_theme_option) ? $customizer_theme_option : null;
		} else {
			$value          = null;
			$default_option = apply_filters('gt3/core/customizer/defaults', array());
			if(key_exists($name, $default_option)) {
				$value = $default_option[$name];
			} else {
				$default_option = apply_filters('gt3/core/customizer/elementor/defaults', array());
				$globals        = key_exists('__globals__', $default_option) ? (array) $default_option['__globals__'] : array();

				foreach([ 'colors', 'typography' ] as $suffix) {
					$_key   = $name.'_'.$suffix;
					$global = false;
					if(key_exists($name, $globals)) {
						$global = $globals[$name];
					} else if(key_exists($_key, $globals)) {
						$global = $globals[$_key];
					}

					if(false !== $global && preg_match('#globals/(\w+)\?id=([-_a-z]+)#i', $global, $matches)) {
						list($str, $key, $_subkey) = $matches;
						switch($key) {
							case 'colors':
								$value = gt3_customizer_get_repeater_setting($default_option['system_colors'], $_subkey);
								if (!is_null($value)) {
									$value = $value['color'];
								}
								break;
							case 'typography':
								$value = gt3_customizer_get_repeater_setting($default_option['system_typography'], $_subkey);
								if (!is_null($value)) {
									$value = gt3_collect_options('typography', $value);
								}
								break;
						}
					}

					if (key_exists($_key, $default_option)) {
						$value = $default_option[$_key];
					}
				}
			}
			if (!is_null($subkey)) {
				$value = gt3_customizer_get_repeater_setting($default_option[$name], $subkey);
				if ($name === 'system_colors') {
					$value = (is_array($value) && key_exists('color', $value)) ? $value['color'] : null;
				} else if ($name === 'system_typography') {
//					$value = (is_array($value) ) ? gt3_collect_options('typography', $value) : null;
				}
				return $value;
			}

			if(null === $value && key_exists($name, $default_option)) {
				$value = isset($default_option[$name]) ? $default_option[$name] : null;
			}

			return $value;
		}
	}

	function gt3_customizer_get_repeater_setting($option, $subkey) {
		$value = array_search($subkey, array_column($option, '_id'));;
		return (false === $value) ? null : $option[$value];
	}

	function gt3_collect_options($setting, $settings) {
		$value  = array();
		$length = strlen($setting)+1;
		foreach($settings as $setting_key => $setting_value) {
			if(0 === strpos($setting_key, $setting)) {
				$value[substr($setting_key, $length)] = $setting_value;
			}
		}

		return $value;
	}
}
if(!function_exists('gt3_customizer_enabled')) {
	function gt3_customizer_enabled(){
		return false;
	}
}
require_once __DIR__.'/core/init.php';

add_filter(
	'gt3/theme/redux/woocommerce_grid_list', function($options){
	if(gt3_option('modern_shop') == '1' || true === gt3_option('modern_shop')) {
		$options = array_slice($options, 0, 1)+array(
				'grid-extended' => esc_html__('Grid Extended', 'ewebot'),
			)+array_slice($options, 1);
	}

	return $options;
}
);

add_filter(
	'wpda-builder/registration/settings', function($settings){
	return array_merge(
		$settings, array(
			'activated'        => true,
			'activatedByTheme' => true,
		)
	);
}
);

function gt3_content_width(){
	$GLOBALS['content_width'] = apply_filters('gt3_content_width', 940);
}

add_action('after_setup_theme', 'gt3_content_width', 0);
require_once __DIR__.'/core/emoji.php';

add_filter('body_class', 'gt3theme_class_names');
if(!function_exists('gt3theme_class_names')) {
	function gt3theme_class_names($class){
		if(post_password_required()) {
			$class[] = 'body_pp';
		}

		return $class;
	}
}

add_filter(
	'gt3_testimonial_quote_src', function(){
	return get_template_directory_uri().'/img/quote.png';
}
);

add_action('wbc_importer_dir_path', 'gt3_get_demo_data_path');
function gt3_get_demo_data_path(){
	return trailingslashit(str_replace('\\', '/', get_template_directory()))."/core/demo-data/";
}

add_action('gt3_homepage_importer_filter', 'gt3_homepage_importer_filter');
function gt3_homepage_importer_filter(){
	return array(
		'demo' => 'Home 09',
	);
}

add_action('gt3_homepage_importer_slider_name', 'gt3_homepage_importer_slider_name');
function gt3_homepage_importer_slider_name(){
	return array(
		'demo' => '',
	);
}

add_filter('wp_get_attachment_image_attributes', 'gt3theme_attachment_image_attributes', 20, 3);
function gt3theme_attachment_image_attributes($attr, $attachment, $size){
	if(!key_exists('title', $attr)) {
		/* @var \WP_Post $attachment */
		if($attachment instanceof \WP_Post) {
			$attr['title'] = $attachment->post_title;
		}
	}

	return $attr;
}

if(!function_exists('gt3_get_theme_option')) {
	function gt3_get_theme_option($optionname, $defaultValue = null){
		$gt3_options = get_option("ewebot_gt3_options");
		if(isset($gt3_options[$optionname])) {
			if(gettype($gt3_options[$optionname]) == "string") {
				return stripslashes($gt3_options[$optionname]);
			} else {
				return $gt3_options[$optionname];
			}
		} else {
			return $defaultValue;
		}
	}
}

add_action(
	'import_end', function(){
//	if (!(isset($_REQUEST['type']) && isset($_REQUEST['content']) && $_REQUEST['type'] === 'import-demo-content' && $_REQUEST['content'] === 10)) return;

	if(class_exists('Elementor\Plugin')) {
		$doc  = Elementor\Plugin::instance()->documents;
		$kit  = Elementor\Plugin::instance()->kits_manager;
		$data = array(
			'editor_post_id' => $kit->get_active_id(),
			'post_status'    => "publish",
			'status'         => "publish",
			'elements'       => array(),
			'settings'       => array(
				'container_width' => array(
					'size'  => 1190,
					'sizes' => [],
					'unit'  => "px",
				),
				'post_status'     => 'publish'
			)

		);

		$doc->ajax_save($data);
	}
}
);


function gt3_set_options(){
	update_option('elementor_disable_color_schemes', 'yes');
	update_option('elementor_disable_typography_schemes', 'yes');
	update_option('elementor_load_fa4_shim', 'yes');

	update_option('elementor_experiment-e_dom_optimization', 'inactive');
	update_option('elementor_experiment-e_optimized_assets_loading', 'inactive');
	update_option('elementor_experiment-e_optimized_css_loading', 'inactive');
	update_option('elementor_experiment-e_font_icon_svg', 'inactive');
	update_option('elementor_experiment-additional_custom_breakpoints', 'inactive');
	update_option('elementor_css_print_method', 'external');

	update_option('yith_woocompare_compare_button_in_products_list', 'yes'); // YITH Compare
	update_option('woocommerce_catalog_columns', '3');
	update_option('woocommerce_catalog_rows', '3');
	update_option('woocommerce_single_image_width', 1200);
	update_option('woocommerce_thumbnail_image_width', 800);
	update_option('gallery_thumbnail_image_width', 800);
	// AJAX Product Filters
	$gt3_br_filters = get_option('br_filters_options');
	if(!is_array($gt3_br_filters)) {
		$gt3_br_filters = array();
	}
	$gt3_br_filters['selected_area_show']       = '1';
	$gt3_br_filters['selected_area_hide_empty'] = '1';
	$gt3_br_filters['seo_friendly_urls']        = '';

	update_option('br_filters_options', $gt3_br_filters);

	// Wishlist
	update_option('yith_wcwl_show_on_loop', 'yes');
}

function gt3_activate_theme(){
	if(current_user_can('manage_options') && !get_option('gt3_first_activation')) {
		update_option('gt3_first_activation', 'true');

		gt3_set_options();
	}
}
add_action('gt3/core/import/finish', 'gt3_set_options');

add_action('after_switch_theme', 'gt3_activate_theme');

if(!function_exists('gt3_wp_body_classes')) {
	function gt3_wp_body_classes($classes){
		if(gt3_option("disable_right_click")) {
			$classes[] = 'disable_right_click';
			wp_localize_script('gt3-theme', 'gt3_rcg', array( 'alert' => (gt3_option("disable_right_click_text")) ));
		}

		/*if (gt3_option('modern_shop') == '1' || true === gt3_option('modern_shop')) {
			$classes[] = 'woocommerce';
			$classes[] = 'woocommerce-page';
			$classes[] = 'gt3_modern_shop';
		}*/

		return $classes;
	}
}
add_filter('body_class', 'gt3_wp_body_classes');

if(!function_exists('gt3_theme_comment')) {
	function gt3_theme_comment($comment, $args, $depth){
		$max_depth_comment = ($args['max_depth'] > 4 ? 4 : $args['max_depth']);

		$GLOBALS['comment'] = $comment; ?>
		<li <?php comment_class(); ?> id="li-comment-<?php comment_ID() ?>">
		<div id="comment-<?php comment_ID(); ?>" class="stand_comment">
			<div class="thiscommentbody">
				<div class="commentava">
					<?php echo get_avatar($comment, 120); ?>
				</div>
				<div class="comment_info">
					<div class="comment_author_says"><?php printf('%s', get_comment_author_link()) ?> <span><?php esc_html_e('says:', 'ewebot'); ?></span></div>
					<div class="listing_meta">
						<span><?php printf('%1$s', get_comment_date()) ?></span>
						<?php edit_comment_link('<span>('.esc_html__('Edit', 'ewebot').')</span>', '  ', '') ?>
					</div>
				</div>
				<div class="comment_content">
					<?php if($comment->comment_approved == '0') : ?>
						<p><?php esc_html_e('Your comment is awaiting moderation.', 'ewebot'); ?></p>
					<?php endif; ?>
					<?php comment_text() ?>
				</div>
				<?php
				$icon_post_comments = '<span class="post_comments_icon"><i class="fa fa-reply"></i></span>';
				if(class_exists('GT3_Core_Elementor')) {
					$icon_post_comments = '<span class="post_comments_icon">'.gt3_svg_icons_name('chat').'</span>';
				}
				comment_reply_link(
					array_merge(
						$args, array(
							'depth'      => $depth,
							'reply_text' => ''.(($icon_post_comments)).esc_html__('Reply', 'ewebot'),
							'max_depth'  => $max_depth_comment
						)
					)
				)
				?>
			</div>
		</div>
		<?php
	}
}

#Custom paging
if(!function_exists('gt3_get_theme_pagination')) {
	function gt3_get_theme_pagination($range = 5, $type = "", $max_page = false, $paged_arg = false){
		global $paged;
		if($type == "show_in_shortcodes") {
			global $my_wp_query;
		} else {
			global $my_wp_query, $wp_query;
			if(is_null($my_wp_query)) {
				$my_wp_query = $wp_query;
			}
		}

		if(empty($paged) || !$paged_arg) {
			$paged = get_query_var('page') ? get_query_var('page') : (get_query_var('paged') ? get_query_var('paged') : 1);
		}

		$compile = '';
		if(!$max_page) {
			$max_page = $my_wp_query->max_num_pages;
		}

		if($max_page > 1) {
			$compile .= '<ul class="pagerblock">';
		}
		if($paged > 1) {
			$compile .= '<li class="prev_page"><a href="'.esc_url(get_pagenum_link($paged-1)).'"><i class="fa fa-angle-left"></i></a></li>';
		}
		if($max_page > 1) {
			if(!$paged) {
				$paged = 1;
			}
			if($max_page > $range) {
				if($paged < $range) {
					for($i = 1; $i <= ($range+1); $i++) {
						$compile .= "<li><a href='".esc_url(get_pagenum_link($i))."'";
						if($i == $paged) {
							$compile .= " class='current'";
						}
						$compile .= ">$i</a></li>";
					}
				} else if($paged >= ($max_page-ceil(($range/2)))) {
					for($i = $max_page-$range; $i <= $max_page; $i++) {
						$compile .= "<li><a href='".esc_url(get_pagenum_link($i))."'";
						if($i == $paged) {
							$compile .= " class='current'";
						}
						$compile .= ">$i</a></li>";
					}
				} else if($paged >= $range && $paged < ($max_page-ceil(($range/2)))) {
					for($i = ($paged-ceil($range/2)); $i <= ($paged+ceil(($range/2))); $i++) {
						$compile .= "<li><a href='".esc_url(get_pagenum_link($i))."'";
						if($i == $paged) {
							$compile .= " class='current'";
						}
						$compile .= ">$i</a></li>";
					}
				}
			} else {
				for($i = 1; $i <= $max_page; $i++) {
					$compile .= "<li><a href='".esc_url(get_pagenum_link($i))."'";
					if($i == $paged) {
						$compile .= " class='current'";
					}
					$compile .= ">$i</a></li>";
				}
			}
		}
		if($paged < $max_page) {
			$compile .= '<li class="next_page"><a href="'.esc_url(get_pagenum_link($paged+1)).'"><i class="fa fa-angle-right"></i></a></li>';
		}
		if($max_page > 1) {
			$compile .= '</ul>';
		}

		return $compile;
	}
}

if(!function_exists('gt3_HexToRGB')) {
	function gt3_HexToRGB($hex = "#ffffff"){
		$color = array();
		if(strlen($hex) < 1) {
			$hex = "#ffffff";
		}

		$color['r'] = hexdec(substr($hex, 1, 2));
		$color['g'] = hexdec(substr($hex, 3, 2));
		$color['b'] = hexdec(substr($hex, 5, 2));

		return $color['r'].",".$color['g'].",".$color['b'];
	}
}

if(!function_exists('gt3_smarty_modifier_truncate')) {
	function gt3_smarty_modifier_truncate($string, $length = 80, $etc = '... ', $break_words = false){
		if($length == 0) {
			return '';
		}

		if(mb_strlen($string, 'utf8') > $length) {
			$length -= mb_strlen($etc, 'utf8');
			if(!$break_words) {
				$string = preg_replace('/\s+\S+\s*$/su', '', mb_substr($string, 0, $length+1, 'utf8'));
			}

			return mb_substr($string, 0, $length, 'utf8').$etc;
		} else {
			return $string;
		}
	}
}

if(!function_exists('gt3_get_pf_type_output')) {
	function gt3_get_pf_type_output($pf, $width, $height, $featured_image, $cols = false){

		if(!$featured_image) {
			$featured_image = array(
				0 => '',
			);
		}

		$compile  = "";
		$ID       = get_the_ID();
		$alt_text = get_post_meta(get_post_thumbnail_id(get_the_ID()), '_wp_attachment_image_alt', true);

		if(empty($alt_text)) {
			$alt_text = get_the_title(get_the_ID());
		}

		if(function_exists('gt3_get_image_srcset') && (bool) $cols) {
			switch($cols) {
				case '1':
					$responsive_dimensions = array(
						array( '1200', '1600' ),
						array( '992', '1200' ),
						array( '768', '992' ),
						array( '600', '768' ),
						array( '420', '600' )
					);
					break;
				case '2':
					$responsive_dimensions = array(
						array( '1200', '800' ),
						array( '992', '500' ),
						array( '768', '496' ),
						array( '600', '384' ),
						array( '420', '600' )
					);
					break;
				case '3':
					$responsive_dimensions = array(
						array( '1200', '540' ),
						array( '992', '400' ),
						array( '768', '496' ),
						array( '600', '384' ),
						array( '420', '600' )
					);
					break;
				case '4':
					$responsive_dimensions = array(
						array( '1200', '400' ),
						array( '992', '300' ),
						array( '768', '496' ),
						array( '600', '384' ),
						array( '420', '600' )
					);
					break;
			}
			$gt3_featured_image_url_srcset = gt3_get_image_srcset($featured_image[0], null, $responsive_dimensions);
		} else {
			$gt3_featured_image_url_srcset = 'src="'.esc_url(aq_resize($featured_image[0], $width, $height, true, true, true)).'"';
		}

		$post_animate_start = $post_animate_end = '';

		$featured_standard = '<div class="blog_post_media">'.$post_animate_start.'<a href="'.esc_url(get_permalink()).'"><img '.$gt3_featured_image_url_srcset.' alt="'.esc_attr($alt_text).'" /></a>'.$post_animate_end.'</div>';

		if(class_exists('RWMB_Loader')) {

			$pf_post_content = $quote_author = $quote_text = $link = $link_text = $pf_post_meta = '';

			switch($pf) {
				case 'gallery':
					$pf_post_content = rwmb_meta('post_format_gallery_images');
					$pf_post_meta    = get_post_meta(get_the_ID(), 'post_format_gallery_images');
					break;

				case 'video':
					$pf_post_content = rwmb_meta('post_format_video_oEmbed', 'type=oembed');
					$pf_post_meta    = get_post_meta(get_the_ID(), 'post_format_video_oEmbed');
					break;

				case 'audio':
					$pf_post_content = rwmb_meta('post_format_audio_oEmbed', 'type=oembed');
					$pf_post_meta    = get_post_meta(get_the_ID(), 'post_format_audio_oEmbed');
					break;

				case 'quote':
					$quote_author       = rwmb_meta('post_format_qoute_author');
					$quote_author_image = rwmb_meta('post_format_qoute_author_image');
					if(!empty($quote_author_image)) {
						$quote_author_image = array_values($quote_author_image);
						$quote_author_image = $quote_author_image[0];
						$quote_author_image = $quote_author_image['url'];
					} else {
						$quote_author_image = '';
					}
					$quote_text      = rwmb_meta('post_format_qoute_text');
					$pf_post_content = $quote_author.$quote_text;
					break;

				case 'link':
					$link            = rwmb_meta('post_format_link');
					$link_text       = rwmb_meta('post_format_link_text');
					$pf_post_content = $link.$link_text;
					break;
			}

			/* Gallery */
			if($pf == 'gallery' && !empty($pf_post_meta)) {
				if(!empty($pf_post_content)) {
					if(count($pf_post_content) == 1) {
						$onlyOneImage = "oneImage";
					} else {
						$onlyOneImage = "";
					}
					$compile .= '
                    <div class="blog_post_media">
                        <div class="slider-wrapper theme-default '.$onlyOneImage.'">
                            <div class="slides slick_wrapper">';

					foreach($pf_post_content as $image) {
						$img_url = $image["full_url"];
						$compile .= "<img src='".esc_url(aq_resize($img_url, $width, $height, true, true, true))."' alt='".esc_attr($alt_text)."' />";
					}

					$compile .= '
                            </div>
                        </div>
                    </div>';
					wp_enqueue_script('jquery-slick');
				}
				/* Video */
			} else if($pf == 'video' && !empty($pf_post_meta)) {
				$video_autoplay_string = $video_class = $compile_image = '';
				if(strlen($featured_image[0])) {
					$video_class .= ' has_post_thumb';
					if(is_array($pf_post_meta) && !empty($pf_post_meta[0])) {
						$video_src = $pf_post_meta[0];
						if(strpos($pf_post_meta[0], 'vimeo') !== false) {
							$video_class           .= ' vimeo_video';
							$video_autoplay_string = '?autoplay=1';
						} else if(strpos($pf_post_meta[0], 'youtube') !== false) {
							$video_class           .= ' youtube_video';
							$video_autoplay_string = '&autoplay=1';
						}
					}

					$compile_image .= '<div class="gt3_video_wrapper__thumb">';

					$compile_image .= '<div class="gt3_video__play_image"><img src="'.esc_url($featured_image[0]).'" alt="'.esc_attr($alt_text).'" /></div>';
					$compile_image .= '<div class="gt3_video__play_button" data-video-autoplay="'.$video_autoplay_string.'">';
					$compile_image .= '<svg viewBox="0 0 13 18" width="23" height="30">
                                                   <polygon points="1,1 1,16 11,9" stroke-width="2" />
                                               </svg>';
					$compile_image .= '</div>';

					$compile_image .= '</div>';
				}
				$compile .= '<div class="blog_post_media'.esc_attr($video_class).'">'.$compile_image;
				$compile .= strlen($featured_image[0]) ? '<div class="gt3_video__play_iframe">'.$pf_post_content.'</div>' : $pf_post_content;
				$compile .= '</div>';

				/* Audio */
			} else if($pf == 'audio' && !empty($pf_post_meta)) {
				$compile .= '<div class="blog_post_media">'.$pf_post_content.'</div>';
				/* Quote */
			} else if($pf == 'quote' && strlen($pf_post_content) > 0) {
				$compile .= '<div class="blog_post_media blog_post_media--quote">'.(strlen($quote_author) && !empty($quote_author_image) ? '<div class="post_media_info">'.(!empty($quote_author_image) ? '<img src="'.esc_url($quote_author_image).'"  class="quote_image" alt="'.esc_attr($alt_text).'" >' : '').'</div>' : '').(strlen($quote_text) ? '<div class="quote_text"><a href="'.esc_url(get_permalink()).'">'.esc_attr($quote_text).'</a></div>' : '').''.(strlen($quote_author) ? '<div class="quote_author">'.esc_attr($quote_author).'</div>' : '').'</div>';
				/* Link */
			} else if($pf == 'link' && strlen($pf_post_content) > 0) {
				$compile .= '<div class="blog_post_media blog_post_media--link"><div class="blog_post_media__link_text">';
				$compile .= '<a href="'.esc_url(get_permalink()).'">';
				if(strlen($link_text) > 0) {
					$compile .= ''.esc_attr($link_text).'';
				} else {
					$compile .= ''.esc_attr($link).'';
				}
				$compile .= '</a>';
				if(strlen($link) > 0) {
					$compile .= '<p><a href="'.esc_url($link).'">'.esc_attr($link).'</a></p>';
				}
				$compile .= '</div></div>';
				/* Standard */
			} else {
				$pf = 'standard';
				if(strlen($featured_image[0]) > 0) {
					$compile .= ''.$featured_standard.'';
					$pf      = 'standard-image';
				}
			}
		} else {
			$pf = 'standard';
			if(strlen($featured_image[0]) > 0) {
				$compile .= ''.$featured_standard.'';
				$pf      = 'standard-image';
			}
		}

		$compile = array(
			'content' => $compile,
			'pf'      => $pf
		);

		return $compile;
	}
}

if(!function_exists('gt3_get_field_media_and_attach_id')) {
	function gt3_get_field_media_and_attach_id($name, $attach_id, $previewW = "200px", $previewH = null, $classname = ""){
		return "<div class='select_image_root ".$classname."'>
        <input type='hidden' name='".esc_attr($name)."' value='".esc_attr($attach_id)."' class='select_img_attachid'>
        <div class='select_img_preview'><img src='".esc_url(($attach_id > 0 ? aq_resize(wp_get_attachment_url($attach_id), $previewW, $previewH, true, true, true) : ""))."' alt='".esc_attr($name)."'></div>
        <input type='button' class='button button-secondary button-large select_attach_id_from_media_library' value='Select'>
    </div>";
	}
}

function gt3_setup_theme(){
	add_theme_support('title-tag');
	add_theme_support('post-thumbnails');
	add_theme_support('automatic-feed-links');
	add_theme_support('revisions');
	add_theme_support('post-formats', array( 'gallery', 'video', 'quote', 'audio', 'link' ));
	add_theme_support('custom-background');
	add_theme_support('align-wide');
}

add_action('after_setup_theme', 'gt3_setup_theme');

add_action('init', 'gt3_page_init');
if(!function_exists('gt3_page_init')) {
	function gt3_page_init(){
		add_post_type_support('page', 'excerpt');
	}
}

/// Post Page Settings //

/*Work with options*/
if(!function_exists('gt3pb_get_option')) {
	function gt3pb_get_option($optionname, $defaultValue = ""){
		$returnedValue = get_option("gt3pb_".$optionname, $defaultValue);

		if(gettype($returnedValue) == "string") {
			return stripslashes($returnedValue);
		} else {
			return $returnedValue;
		}
	}
}

if(!function_exists('gt3pb_delete_option')) {
	function gt3pb_delete_option($optionname){
		return delete_option("gt3pb_".$optionname);
	}
}

if(!function_exists('gt3pb_update_option')) {
	function gt3pb_update_option($optionname, $optionvalue){
		if(update_option("gt3pb_".$optionname, $optionvalue)) {
			return true;
		}
	}
}

add_action('wp_footer', 'gt3_wp_footer');
function gt3_wp_footer(){
	echo gt3_get_theme_option("code_before_body");
}

if(!function_exists('gt3_get_image_bg')) {
	function gt3_get_image_bg($gt3_img_src, $gt3_is_grid){
		if(isset($gt3_is_grid) && $gt3_is_grid == 'yes') {
			echo "<div class='fullscreen_block fw_background bg_image grid_background image_video_bg_block' data-bg='".esc_url($gt3_img_src)."'></div>";
		} else {
			echo "<div class='fullscreen_block fw_background bg_image image_video_bg_block' data-bg='".esc_url($gt3_img_src)."'></div>";
		}
	}
}
if(!function_exists('gt3_get_color_bg')) {
	function gt3_get_color_bg($gt3_bg_color){
		echo "<div class='fullscreen_block fw_background bg_color grid_background' data-bgcolor='".esc_attr($gt3_bg_color)."'></div>";
	}
}

if(!function_exists('gt3_page_title')) {
	function gt3_page_title(){
		$title = '';

		if(class_exists('WooCommerce') && is_product()) {
			$title = wp_kses_post(get_the_title());
		} else if(class_exists('WooCommerce') && is_product_category()) {
			$title = single_cat_title('', false);
		} else if(class_exists('WooCommerce') && is_product_tag()) {
			$title = single_term_title("", false);
		} else if(class_exists('WooCommerce') && is_woocommerce()) {
			$title = woocommerce_page_title(false);
		} else if(is_category()) {
			$title = single_cat_title('', false);
		} else if(is_tag()) {
			$title = single_term_title("", false);
		} else if(is_date()) {
			$title = get_the_time('F Y');
		} else if(is_author()) {
			$title = esc_html__('Author:', 'ewebot')." ".esc_html(get_the_author());
		} else if(is_search()) {
			$title = esc_html__(sprintf('Results for: %s', get_search_query()), 'ewebot');
		} else if(is_404()) {
			$title = esc_html__('404', 'ewebot');
		} else if(is_archive()) {
			$title = esc_html__('Archive', 'ewebot');
		} else if(is_home() || is_front_page()) {
			$gt3_ID = gt3_get_queried_object_id();
			$title  = esc_html(get_the_title($gt3_ID));
		} else {
			global $post;
			if(!empty($post)) {
				$id = $post->ID;
				if(is_sticky()) {
					$title = '<i class="fa fa-thumb-tack"></i>'.esc_html(get_the_title($id));
				} else {
					$title = esc_html(get_the_title($id));
				}
			} else {
				$title = esc_html__('No Posts', 'ewebot');
			}
		}

		return $title;
	}
}

function gt3_the_breadcrumb(){
	$delimiter   = '<span class="gt3_pagination_delimiter"></span>';
	$home        = esc_html__('Home', 'ewebot');
	$showCurrent = 1;
	$before      = '<span class="current">';
	$after       = '</span>';
	global $post;
	$homeLink = esc_url(home_url('/'));
	if(is_front_page() && !is_home()) {
		echo '<div class="breadcrumbs">'.$home.'</div>';
	} else if(class_exists('WooCommerce') && is_woocommerce()) {
		if(is_shop() && (gt3_option('modern_shop') == '1' || true === gt3_option('modern_shop'))) {
			return;
		}
		echo '<div class="breadcrumbs">';
		woocommerce_breadcrumb();
		echo '</div>';
	} else {
		echo '<div class="breadcrumbs"><a href="'.$homeLink.'">'.$home.'</a>'.$delimiter.'';
		if(is_category()) {
			$thisCat = get_category(get_query_var('cat'), false);
			if($thisCat->parent != 0) {
				echo get_category_parents($thisCat->parent, true, ' '.$delimiter.' ');
			}
			echo wp_kses_post($before).esc_html__('Category', 'ewebot').' "'.single_cat_title('', false).'"'.wp_kses_post($after);

		} else if(get_post_type() == 'port') {
			the_terms($post->ID, 'portcat', '', '', '');
			if($showCurrent == 1) {
				echo ' '.$delimiter.' '.$before.esc_html(get_the_title()).$after;
			}

		} else if(is_search()) {
			echo wp_kses_post($before).esc_html__('Search results', 'ewebot').wp_kses_post($after);

		} else if(is_day()) {
			echo '<a href="'.esc_url(get_year_link(get_the_time('Y'))).'">'.esc_html(get_the_time('Y')).'</a> '.$delimiter.' ';
			echo '<a href="'.esc_url(get_month_link(get_the_time('Y'), get_the_time('m'))).'">'.esc_html(get_the_time('F')).'</a> '.$delimiter.' ';
			echo wp_kses_post($before).esc_html(get_the_time('d')).wp_kses_post($after);

		} else if(is_month()) {
			echo '<a href="'.esc_url(get_year_link(get_the_time('Y'))).'">'.esc_html(get_the_time('Y')).'</a> '.$delimiter.' ';
			echo wp_kses_post($before).esc_html(get_the_time('F')).wp_kses_post($after);

		} else if(is_year()) {
			echo wp_kses_post($before).esc_html(get_the_time('Y')).wp_kses_post($after);

		} else if(is_single() && !is_attachment()) {
			if(get_post_type() != 'post') {
				$parent_id = $post->post_parent;
				if($parent_id > 0) {
					$breadcrumbs = array();
					while($parent_id) {
						$page          = get_page($parent_id);
						$breadcrumbs[] = '<a href="'.esc_url(get_permalink($page->ID)).'">'.esc_html(get_the_title($page->ID)).'</a>';
						$parent_id     = $page->post_parent;
					}
					$breadcrumbs = array_reverse($breadcrumbs);
					for($i = 0; $i < count($breadcrumbs); $i++) {
						echo(($breadcrumbs[$i]));
						if($i != count($breadcrumbs)-1) {
							echo ' '.$delimiter.' ';
						}
					}
					if($showCurrent == 1) {
						echo ' '.$delimiter.' '.$before.esc_html(get_the_title()).$after;
					}
				} else {
					$post_type_label = gt3_option('portfolio_name');
					if(!is_string($post_type_label) || empty($post_type_label)) {
						$post_type       = get_post_type_object(get_post_type());
						$post_type_label = $post_type->label;
					}

					echo '<a href="'.get_post_type_archive_link(get_post_type()).'">'.$post_type_label.'</a> '.$delimiter;
					echo wp_kses_post($before).esc_html(get_the_title()).wp_kses_post($after);
				}

			} else {
				$cat = get_the_category();
				$cat = array_shift($cat);

				$cats = get_category_parents($cat, true, ' '.$delimiter.' ');
				if($cats instanceof \WP_Error) {
					$cats = '';
				}
				if($showCurrent == 0) {
					$cats = preg_replace("#^(.+)\s$delimiter\s$#", "$1", $cats);
				}
				echo(($cats));
				if($showCurrent == 1) {
					echo wp_kses_post($before).esc_html(get_the_title()).wp_kses_post($after);
				}
			}

		} else if(!is_single() && !is_page() && get_post_type() != 'post' && !is_404()) {
			$post_type = get_post_type_object(get_post_type());
			echo wp_kses_post($before).esc_html($post_type->labels->singular_name).wp_kses_post($after);
		} else if(is_attachment()) {
			if($showCurrent == 1) {
				echo ' '.$before.esc_html(get_the_title()).$after;
			}

		} else if(is_page() && !$post->post_parent) {
			if($showCurrent == 1) {
				echo wp_kses_post($before).esc_html(get_the_title()).wp_kses_post($after);
			}

		} else if(is_page() && $post->post_parent) {
			$parent_id   = $post->post_parent;
			$breadcrumbs = array();
			while($parent_id) {
				$page          = get_page($parent_id);
				$breadcrumbs[] = '<a href="'.esc_url(get_permalink($page->ID)).'">'.esc_html(get_the_title($page->ID)).'</a>';
				$parent_id     = $page->post_parent;
			}
			$breadcrumbs = array_reverse($breadcrumbs);
			for($i = 0; $i < count($breadcrumbs); $i++) {
				echo(($breadcrumbs[$i]));
				if($i != count($breadcrumbs)-1) {
					echo ' '.$delimiter.' ';
				}
			}
			if($showCurrent == 1) {
				echo ' '.$delimiter.' '.$before.esc_html(get_the_title()).$after;
			}

		} else if(is_tag()) {
			echo wp_kses_post($before).esc_html__('Tag', 'ewebot').' "'.single_tag_title('', false).'"'.wp_kses_post($after);

		} else if(is_author()) {
			global $author;
			$userdata = get_userdata($author);
			echo wp_kses_post($before).esc_html__('Author', 'ewebot').' '.esc_html($userdata->display_name).wp_kses_post($after);

		} else if(is_404()) {
			echo wp_kses_post($before).esc_html__('Error 404', 'ewebot').wp_kses_post($after);

		} else if(is_home() && is_front_page()) {
			$title = esc_html__('Blog', 'ewebot');
			echo wp_kses_post($before).$title.wp_kses_post($after);

		} else if(is_home() || is_front_page()) {
			$gt3_ID = gt3_get_queried_object_id();
			$title  = esc_html(get_the_title($gt3_ID));
			echo wp_kses_post($before).$title.wp_kses_post($after);
		}

		echo '</div>';
	}
}

if(!function_exists('gt3_preloader')) {
	function gt3_preloader(){
		$id           = gt3_get_queried_object_id();
		$post_loader  = (class_exists('RWMB_Loader') && $id !== 0);
		$mb_preloader = $post_loader ? rwmb_meta('mb_preloader', array(), $id) : false;
		if($mb_preloader == 'none') {
			return;
		}

		$preloader_mobile_disable = gt3_option('preloader_mobile_disable');
		if($post_loader && $mb_preloader == 'custom') {
			$preloader_mobile_disable = rwmb_meta('mb_preloader_mobile_disable', array(), $id);
		}

		$is_mobile = false;
		if ($preloader_mobile_disable == '1' || true === $preloader_mobile_disable) {
			$is_mobile = wp_is_mobile();
		}

		if((gt3_option('preloader') == '1' || gt3_option('preloader') === true || $mb_preloader == 'custom') && !$is_mobile) {
			$preloader_type        = gt3_option('preloader_type');
			$preloader_background  = gt3_option('preloader_background');
			$preloader_item_color  = gt3_option('preloader_item_color');
			$preloader_item_color2 = gt3_option('preloader_item_color2');
			$preloader_logo        = gt3_option('preloader_item_logo');
			$preloader_logo_cont_w = gt3_option('preloader_item_logo_width');
			$preloader_item_width  = gt3_option('preloader_item_width');
			$preloader_item_stroke = gt3_option('preloader_item_stroke');
			$preloader_full        = gt3_option('preloader_full');

			$preloader_logo_url = $preloader_logo_width = '';

			if(is_array($preloader_logo) && !empty($preloader_logo['url'])) {
				$preloader_logo_url = $preloader_logo['url'];
			} else if(!empty($preloader_logo)) {
				$preloader_logo_url = wp_get_attachment_image_url($preloader_logo, 'full');
			}

			if(is_array($preloader_logo) && !empty($preloader_logo['width'])) {
				$preloader_logo_width = $preloader_logo['width'];
			} else if(!empty($preloader_logo)) {
				$image_attributes     = wp_get_attachment_image_src($preloader_logo, 'full');
				$preloader_logo_width = $image_attributes[2];
			}

			if($post_loader && $mb_preloader == 'custom') {
				$preloader_type         = rwmb_meta('mb_preloader_type', array(), $id);
				$preloader_background   = rwmb_meta('mb_preloader_background', array(), $id);
				$preloader_item_color   = rwmb_meta('mb_preloader_item_color', array(), $id);
				$preloader_item_color2  = rwmb_meta('mb_preloader_item_color2', array(), $id);
				$mb_preloader_item_logo = rwmb_meta('mb_preloader_item_logo', 'size=full', $id);
				if(!empty($mb_preloader_item_logo)) {
					$preloader_logo_src   = array_values($mb_preloader_item_logo);
					$preloader_logo_url   = $preloader_logo_src[0]['full_url'];
					$preloader_logo_width = $preloader_logo_src[0]['width'];
				} else {
					$preloader_logo_url = '';
				}
				$preloader_logo_cont_w = rwmb_meta('mb_preloader_item_logo_width', array(), $id);
				$preloader_item_width  = rwmb_meta('mb_preloader_item_width', array(), $id);
				$preloader_item_stroke = rwmb_meta('mb_preloader_item_stroke', array(), $id);
				$preloader_full                 = rwmb_meta('mb_preloader_full', array(), $id);
			}

			$preloader_background  = !empty($preloader_background) ? $preloader_background : '#ffffff';
			$preloader_item_color  = !empty($preloader_item_color) ? $preloader_item_color : '#808080';
			$preloader_item_color2 = !empty($preloader_item_color2) ? $preloader_item_color2 : '#e94e76';

			$preloader_class = $preloader_full == '1' ? ' gt3_preloader_full' : '';
			$preloader_class .= !empty($preloader_logo_url) ? ' gt3_preloader_image_on' : '';

			if($preloader_type == 'linear') {
				$preldr_linear_style = 'background-color:'.$preloader_item_color.';color:'.$preloader_item_color2.';';

				echo '<div class="gt3_preloader gt3_linear-loading'.esc_attr($preloader_class).'" style="background-color:'.esc_attr($preloader_background).';" data-loading_type="linear">';
				echo '<div class="gt3_linear-loading-center">';
				echo '<div class="gt3_linear-loading-center-absolute">';
				if(!empty($preloader_logo_url)) {
					echo '<img style="width:'.esc_attr((int) $preloader_logo_width/2).'px;height: auto;" src="'.esc_url($preloader_logo_url).'" alt="'.esc_attr__('preloader', 'ewebot').'">';
				}
				echo '<div class="gt3_linear-object gt3_linear-object_one" style="'.esc_attr($preldr_linear_style).'"></div>';
				echo '</div>';
				echo '</div>';
				echo '</div>';
			} else if($preloader_type == 'circle') {
				$preldr_width     = is_array($preloader_item_width) && !empty($preloader_item_width['width']) ? (int) $preloader_item_width['width'] : $preloader_item_width;
				$preldr_str_width = is_array($preloader_item_stroke) && !empty($preloader_item_stroke['width']) ? (int) $preloader_item_stroke['width'] : $preloader_item_stroke;
				$preldr_circle_hp = $preldr_width/2;
				$preldr_circle_xy = $preldr_circle_hp*0.9;
				$preldr_circle_r  = $preldr_circle_hp*0.8;
				$preldr_circle_l  = 2*pi()*$preldr_circle_r;

				$preldr_circle_style  = 'stroke:'.$preloader_item_color.'; stroke-dasharray: '.(float) $preldr_circle_l.'; stroke-width: '.(int) $preldr_str_width;
				$preldr_circle_style2 = 'stroke:'.$preloader_item_color2.'; stroke-dasharray: '.(float) $preldr_circle_l.'; stroke-width: '.(int) $preldr_str_width;

				$preldr_circle_logo_cont_style = 'width:'.$preloader_logo_cont_w.'px'.';';

				echo '<div class="gt3_preloader gt3_circle-overlay'.esc_attr($preloader_class).'" style="background-color:'.esc_attr($preloader_background).';" data-loading_type="circle" data-circle_l="'.(int) $preldr_circle_l.'">';
				echo '<div>';
				echo '<div class="gt3_circle-preloader" style="width:'.(int) $preldr_width.'px; height:'.(int) $preldr_width.'px;">';
				echo '<svg width="'.(int) $preldr_width.'" height="'.(int) $preldr_width.'">';
				echo '<circle class="gt3_circle-background" cx="'.(int) $preldr_circle_xy.'" cy="'.(int) $preldr_circle_xy.'" r="'.(int) $preldr_circle_r.'" transform="rotate(-90, '.(int) $preldr_circle_hp.', '.(int) $preldr_circle_xy.')" style="'.esc_attr($preldr_circle_style).'" />';
				echo '<circle class="gt3_circle-outer" cx="'.(int) $preldr_circle_xy.'" cy="'.(int) $preldr_circle_xy.'" r="'.(int) $preldr_circle_r.'" transform="rotate(-90, '.(int) $preldr_circle_hp.', '.(int) $preldr_circle_xy.')" style="'.esc_attr($preldr_circle_style2).'"/>';
				echo '</svg>';
				echo '<span class="gt3_circle-background"></span>';
				echo '<span class="gt3_circle-logo gt3_circle-animated gt3_circle-fade_in" style="'.esc_attr($preldr_circle_logo_cont_style).'">';

				if(!empty($preloader_logo_url)) {
					echo '<img style="width:'.esc_attr((int) $preloader_logo_width/2).'px;height: auto;" src="'.esc_url($preloader_logo_url).'" alt="'.esc_attr__('preloader', 'ewebot').'">';
				}
				echo '</span>';
				echo '</div>';
				echo '</div>';
				echo '</div>';
			} else {
				$preldr_width      = is_array($preloader_item_width) && !empty($preloader_item_width['width']) ? (int) $preloader_item_width['width'] : $preloader_item_width;
				$preldr_str_width  = is_array($preloader_item_stroke) && !empty($preloader_item_stroke['width']) ? (int) $preloader_item_stroke['width'] : $preloader_item_stroke;
				$preldr_circle_hp  = $preldr_width/2;
				$preldr_circle_l   = round(2*pi()*$preldr_circle_hp, 0);
				$preldr_dashoffset = round($preldr_circle_l/4, 0);

				$preldr_circle_style = 'stroke:'.$preloader_item_color2.'; stroke-dasharray: '.(float) $preldr_circle_l.'; stroke-width: '.(int) $preldr_str_width.'; stroke-dashoffset: '.$preldr_dashoffset.';';

				$preldr_circle_logo_cont_style = 'width:'.$preloader_logo_cont_w.'px;';

				echo '<div class="gt3_preloader gt3_theme_prl-loading gt3_theme_prl-overlay'.esc_attr($preloader_class).'" style="background-color:'.esc_attr($preloader_background).';" data-loading_type="theme" data-circle_l="'.(int) $preldr_circle_l.'">';
				echo '<div>';
				echo '<div class="gt3_theme_prl-preloader" style="width:'.(int) $preldr_width.'px; height:'.(int) $preldr_width.'px;">';
				echo '<svg width="'.(int) $preldr_width.'" height="'.(int) $preldr_width.'">';
				echo '<circle class="gt3_theme_prl-background" cx="'.(int) $preldr_circle_hp.'" cy="'.(int) $preldr_circle_hp.'" r="'.(int) $preldr_circle_hp.'" transform="rotate(-90, '.(int) $preldr_circle_hp.', '.(int) $preldr_circle_hp.')" style="'.esc_attr($preldr_circle_style).'" />';

				echo '</svg>';
				echo '<span class="gt3_circle-background"></span>';
				echo '<span class="gt3_theme_prl-logo gt3_theme_prl-animated gt3_theme_prl-fade_in" style="'.esc_attr($preldr_circle_logo_cont_style).'">';

				if(!empty($preloader_logo_url)) {
					echo '<img style="width:'.esc_attr((int) $preloader_logo_width/2).'px;height: auto;" src="'.esc_url($preloader_logo_url).'" alt="'.esc_attr__('preloader', 'ewebot').'">';
				}
				echo '</span>';
				echo '</div>';
				echo '</div>';
				echo '</div>';

			}
		}
	}
}

if(!function_exists('gt3_get_page_title')) {
	function gt3_get_page_title($id){
		$is_preview                  = gt3_customizer_enabled();
		$page_title_conditional      = ($is_preview || (gt3_option('page_title_conditional') == '1' || gt3_option('page_title_conditional') === true)) ? 'yes' : 'no';
		$blog_title_conditional      = ($is_preview || (gt3_option('blog_title_conditional') == '1' || gt3_option('blog_title_conditional') === true)) ? 'yes' : 'no';
		$team_title_conditional      = ($is_preview || (gt3_option('team_title_conditional') == '1' || gt3_option('team_title_conditional') === true)) ? 'yes' : 'no';
		$portfolio_title_conditional = ($is_preview || (gt3_option('portfolio_title_conditional') == '1' || gt3_option('portfolio_title_conditional') === true)) ? 'yes' : 'no';

		$product_title_conditional  = ((gt3_option('product_title_conditional') == '1' || gt3_option('product_title_conditional') === true)) ? 'yes' : 'no';
		$shop_cat_title_conditional = ((gt3_option('shop_cat_title_conditional') == '1' || gt3_option('shop_cat_title_conditional') === true)) ? 'yes' : 'no';

		if(is_singular('post') && $page_title_conditional == 'yes' && $blog_title_conditional == 'no') {
			$page_title_conditional = 'no';
		}
		if(is_singular('team') && $page_title_conditional == 'yes' && $team_title_conditional == 'no') {
			$page_title_conditional = 'no';
		}
		if(is_singular('portfolio') && $page_title_conditional == 'yes' && $portfolio_title_conditional == 'no') {
			$page_title_conditional = 'no';
		}
		if(is_singular('product') && $page_title_conditional == 'yes' && $product_title_conditional == 'no') {
			$page_title_conditional = 'no';
		} else if(is_singular('product') && $page_title_conditional == 'yes' && $product_title_conditional == 'yes') {
			remove_action('woocommerce_single_product_summary', 'woocommerce_template_single_title', 5);
		}
		if(class_exists('WooCommerce') && (is_product_category() || is_product_tag() || is_product_taxonomy()) && $page_title_conditional == 'yes' && $shop_cat_title_conditional == 'no') {
			$page_title_conditional = 'no';
		}

		if($page_title_conditional == 'yes') {
			$customize_shop_title               = gt3_option("customize_shop_title");
			$page_title_breadcrumbs_conditional = (gt3_option("page_title_breadcrumbs_conditional") == '1' || true === gt3_option("page_title_breadcrumbs_conditional")) ? 'yes' : 'no';
			if(class_exists('WooCommerce') && (is_woocommerce() || is_cart() || is_checkout() || is_account_page()) && ($customize_shop_title == '1' || true === $customize_shop_title)) {
				$page_title_vert_align          = gt3_option("shop_title_vert_align");
				$page_title_horiz_align         = gt3_option("shop_title_horiz_align");
				$page_title_font_color          = gt3_option("shop_title_font_color");
				$page_title_bg_color            = gt3_option("shop_title_bg_color");
				$page_title_bg_image_array      = gt3_option("shop_title_bg_image");
				$page_title_height              = gt3_option("shop_title_height");
				$page_title_bottom_margin       = gt3_option("shop_title_bottom_margin");
				$page_title_top_border          = gt3_option("shop_title_top_border");
				$page_title_top_border_color    = gt3_option("shop_title_top_border_color");
				$page_title_bottom_border       = gt3_option("shop_title_bottom_border");
				$page_title_bottom_border_color = gt3_option("shop_title_bottom_border_color");
			} else {
				$page_title_vert_align          = gt3_option("page_title_vert_align");
				$page_title_horiz_align         = gt3_option("page_title_horiz_align");
				$page_title_font_color          = gt3_option("page_title_font_color");
				$page_title_bg_color            = gt3_option("page_title_bg_color");
				$page_title_bg_image_array      = gt3_option("page_title_bg_image");
				$page_title_height              = gt3_option("page_title_height");
				$page_title_bottom_margin       = gt3_option("page_title_bottom_margin");
				$page_title_top_border          = gt3_option("page_title_top_border");
				$page_title_top_border_color    = gt3_option("page_title_top_border_color");
				$page_title_bottom_border       = gt3_option("page_title_bottom_border");
				$page_title_bottom_border_color = gt3_option("page_title_bottom_border_color");
			}

			$page_title_height        = is_array($page_title_height) && !empty($page_title_height['height']) ? (int) $page_title_height['height'] : (int) $page_title_height;
			$page_title_bottom_margin = is_array($page_title_bottom_margin) && !empty($page_title_bottom_margin['margin-bottom']) ? (int) $page_title_bottom_margin['margin-bottom'] : (int) $page_title_bottom_margin;
		}

		if(class_exists('RWMB_Loader') && $id !== 0) {
			$page_sub_title                  = rwmb_meta('mb_page_sub_title', array(), $id);
			$mb_page_sub_title_color         = rwmb_meta('mb_page_sub_title_color', array(), $id);
			$mb_page_title_conditional       = rwmb_meta('mb_page_title_conditional', array(), $id);
			$mb_page_title_use_feature_image = rwmb_meta('mb_page_title_use_feature_image', array(), $id);
			if($mb_page_title_conditional == 'yes') {
				$page_title_conditional             = 'yes';
				$page_title_breadcrumbs_conditional = rwmb_meta('mb_show_breadcrumbs', array(), $id) == '1' ? 'yes' : 'no';
				$page_title_vert_align              = rwmb_meta('mb_page_title_vertical_align', array(), $id);
				$page_title_horiz_align             = rwmb_meta('mb_page_title_horizontal_align', array(), $id);
				$page_title_font_color              = rwmb_meta('mb_page_title_font_color', array(), $id);
				$page_title_bg_color                = rwmb_meta('mb_page_title_bg_color', array(), $id);
				$page_title_height                  = rwmb_meta('mb_page_title_height', array(), $id);

				$page_title_top_border                  = rwmb_meta("mb_page_title_top_border", array(), $id);
				$mb_page_title_top_border_color         = rwmb_meta("mb_page_title_top_border_color", array(), $id);
				$mb_page_title_top_border_color_opacity = rwmb_meta("mb_page_title_top_border_color_opacity", array(), $id);

				if(!empty($mb_page_title_top_border_color) && $page_title_top_border == '1') {
					$page_title_top_border_color['rgba'] = 'rgba('.(gt3_HexToRGB($mb_page_title_top_border_color)).','.$mb_page_title_top_border_color_opacity.')';
				} else {
					$page_title_top_border_color = '';
				}

				$page_title_bottom_border                  = rwmb_meta("mb_page_title_bottom_border", array(), $id);
				$mb_page_title_bottom_border_color         = rwmb_meta("mb_page_title_bottom_border_color", array(), $id);
				$mb_page_title_bottom_border_color_opacity = rwmb_meta("mb_page_title_bottom_border_color_opacity", array(), $id);

				if(!empty($mb_page_title_bottom_border_color) && $page_title_bottom_border == '1') {
					$page_title_bottom_border_color['rgba'] = 'rgba('.(gt3_HexToRGB($mb_page_title_bottom_border_color)).','.$mb_page_title_bottom_border_color_opacity.')';
				} else {
					$page_title_bottom_border_color = '';
				}

				$page_title_bottom_margin = rwmb_meta("mb_page_title_bottom_margin", array(), $id);

			} else if($mb_page_title_conditional == 'no') {
				$page_title_conditional = 'no';
			}
		}

		$gt3_page_title = is_home() && is_front_page() ? esc_html__('Blog', 'ewebot') : gt3_page_title();

		if($page_title_conditional == 'yes' && !empty($gt3_page_title)) {

			$page_title_classes = !empty($page_title_horiz_align) ? ' gt3-page-title_horiz_align_'.esc_attr($page_title_horiz_align) : ' gt3-page-title_horiz_align_left';
			$page_title_classes .= !empty($page_title_vert_align) ? ' gt3-page-title_vert_align_'.esc_attr($page_title_vert_align) : ' gt3-page-title_vert_align_middle';

			$page_title_classes .= !empty($page_title_height) && (int) $page_title_height < 80 ? ' gt3-page-title_small_header' : '';

			$page_title_styles = !empty($page_title_bg_color) ? 'background-color:'.esc_attr($page_title_bg_color).';' : '';
			$page_title_styles .= !empty($page_title_height) ? 'height:'.esc_attr($page_title_height).'px;' : '';
			$page_title_styles .= !empty($page_title_font_color) ? 'color:'.esc_attr($page_title_font_color).';' : '';
			$page_title_styles .= !empty($page_title_bottom_margin) ? 'margin-bottom:'.esc_attr($page_title_bottom_margin).'px;' : '';

			if($page_title_top_border == '1') {
				$page_title_styles .= is_array($page_title_top_border_color) && !empty($page_title_top_border_color['rgba']) ? 'border-top: 1px solid '.esc_attr($page_title_top_border_color['rgba']).';' : 'border-top: 1px solid '.esc_attr($page_title_top_border_color).';';
			}

			if($page_title_bottom_border == '1') {
				$page_title_styles .= is_array($page_title_bottom_border_color) && !empty($page_title_bottom_border_color['rgba']) ? 'border-bottom: 1px solid '.esc_attr($page_title_bottom_border_color['rgba']).';' : 'border-bottom: 1px solid '.esc_attr($page_title_bottom_border_color).';';
			}

			$customize_shop_title = gt3_option("customize_shop_title");
			if(class_exists('WooCommerce') && (is_woocommerce() || is_cart() || is_checkout() || is_account_page()) && ($customize_shop_title == '1' || true === $customize_shop_title)) {
				$title_background = gt3_background_render('shop_title', 'mb_page_title_conditional', 'yes', true);
			} else {
				$title_background = gt3_background_render('page_title', 'mb_page_title_conditional', 'yes', true);
			}
			$bg_src = !empty($image_array['background-image']) ? $image_array['background-image'] : '';
			if(!empty($title_background) && is_array($title_background) && gt3_get_queried_object_id() !== 0 && !empty($mb_page_title_use_feature_image) && (bool) $mb_page_title_use_feature_image) {

				if(!empty($mb_page_title_conditional) && $mb_page_title_conditional == 'yes') {
					if(class_exists('WooCommerce') && (is_woocommerce() || is_cart() || is_checkout() || is_account_page()) && ($customize_shop_title == '1' || true === $customize_shop_title)) {
						$title_background = gt3_background_render('shop_title', 'mb_page_title_use_feature_image', '1', true, true);
					} else {
						$title_background = gt3_background_render('page_title', 'mb_page_title_use_feature_image', '1', true, true);
					}
				}

				$bg_src                               = get_the_post_thumbnail_url(gt3_get_queried_object_id(), 'full');
				$title_background['background-image'] = 'background-image:url('.esc_url($bg_src).');';
			}
			$title_background = implode('', $title_background);

			$page_title_classes .= !empty($title_background) ? ' gt3-page-title_has_img_bg' : '';

			$page_title_styles .= $title_background;

			$page_title_classes .= ($page_title_bg_color == '#fff' || $page_title_bg_color == '#ffffff') && empty($title_background) ? ' gt3-page-title_default_color_a' : '';

			$customize_shop_title = gt3_option("customize_shop_title");
			if(class_exists('WooCommerce') && (is_woocommerce() || is_cart() || is_checkout() || is_account_page()) && ($customize_shop_title == '1' || true === $customize_shop_title)) {
				$image_array = gt3_option("page_title_bg_image");
			} else {
				$image_array = gt3_option("shop_title_bg_image");
			}

			if(class_exists('RWMB_Loader') && gt3_get_queried_object_id() !== 0) {
				if('mb_page_title_conditional' != false) {
					$mb_conditional = rwmb_meta('mb_page_title_conditional', array(), $id);
					if($mb_conditional == 'yes') {
						$bg_src = rwmb_meta('mb_page_title_bg_image', array(), $id);
						$bg_src = !empty($bg_src) ? $bg_src : '';
						if(!empty($bg_src)) {
							$bg_src = array_values($bg_src);
							$bg_src = $bg_src[0]['url'];
						}
					}
				}
			}
			$page_title_fill = $page_fill_inner_class = '';
			/*
			if (!empty($bg_src)) {
				$page_title_fill_color = getSolidColorFromImage(esc_url($bg_src));
				$page_title_fill = "<div class='gt3-page-title-fill' style='background-color:#".esc_attr($page_title_fill_color).";'></div>";
				$page_fill_inner_class = 'has_fill_inner';
			}
			*/

			echo '<div class="gt3-page-title_wrapper">';
			echo "<div class='gt3-page-title".(!empty($page_title_classes) ? esc_attr($page_title_classes) : '')."'".(!empty($page_title_styles) ? ' style="'.esc_attr($page_title_styles).'"' : '').">";
			echo (($page_title_fill))."<div class='gt3-page-title__inner ".esc_attr($page_fill_inner_class)."'>";
			echo "<div class='container'>";
			echo "<div class='gt3-page-title__content'>";

			if(is_single() && get_post_type() == 'portfolio') {
				$item_category = '';
				$categories    = get_the_terms(get_the_ID(), 'portfolio_category');
				if(!$categories || is_wp_error($categories)) {
					$categories = array();
				}
				if(count($categories)) {
					$item_category = array();
					foreach($categories as $category) {
						$item_category[] = '<span>'.$category->name.'</span>';
					}
					$item_category = implode(' ', $item_category);
				}
				echo "<div class='page_title_meta cpt_portf'>".$item_category."</div>";
			}

			$page_title_names_conditional        = (gt3_option("page_title_names_conditional") == '1' || true === gt3_option("page_title_names_conditional")) ? 'yes' : 'no';

			if($page_title_names_conditional == 'yes') {
				echo "<div class='page_title'><h1>".wp_kses_post($gt3_page_title)."</h1></div>";
			}

			if(!empty($page_sub_title)) {
				echo "<div class='page_sub_title'".(!empty($mb_page_sub_title_color) ? ' style="color:'.esc_attr($mb_page_sub_title_color).';"' : '')."><div>";
				echo esc_attr($page_sub_title);
				echo "</div></div>";
			}

			if($page_title_breadcrumbs_conditional == 'yes') {
				echo "<div class='gt3_breadcrumb'>";
				gt3_the_breadcrumb();
				echo "</div>";
			}

			if(is_single() && get_post_type() == 'post') {
				if(get_comments_number(get_the_ID()) == 1) {
					$comments_text = ' '.esc_html__('comment', 'ewebot').'';
				} else {
					$comments_text = ' '.esc_html__('comments', 'ewebot').'';
				}
				$post = get_post(get_the_ID());
				echo "
                                        <div class='page_title_meta'>
                                            <span class='post_date'>".esc_html(get_the_time(get_option('date_format')))."</span>
                                            <span class='post_author'>".esc_html__('by', 'ewebot')." <a href='".esc_url(get_author_posts_url($post->post_author))."'>".esc_html(get_the_author_meta('display_name', $post->post_author))."</a></span>
                                            <span class='gt3_page_title_cats'>";
				the_category(', ');
				echo "</span>";
				if((int) get_comments_number(get_the_ID()) != 0) {
					echo "<span class='post_comments'><a href='".esc_url(get_comments_link())."'>".esc_html(get_comments_number(get_the_ID())).$comments_text."</a></span>";
				}
				echo "</div>";

			}

			echo "</div>";

			echo "</div>";
			echo "</div>";
			echo "</div>";
			echo '</div>';
		}
	}
}

if(!function_exists('gt3_main_menu')) {
	function gt3_main_menu(){
		wp_nav_menu(
			array(
				'theme_location'  => 'main_menu',
				'container'       => '',
				'container_class' => '',
				'after'           => '',
				'link_before'     => '<span>',
				'link_after'      => '</span>',
				'walker'          => new GT3_Walker_Nav_Menu (),
			)
		);
	}
}

// need for vertical view of header in theme options (admin)
if(!function_exists('gt3_add_admin_class_menu_order')) {
	add_filter('admin_body_class', 'gt3_add_admin_class_menu_order');
	function gt3_add_admin_class_menu_order($classes){
		if(gt3_option('bottom_header_vertical_order')) {
			$classes .= ' bottom_header_vertical_order';
		}

		return $classes;
	}
}

// need for comparing (theme_options or metabox) and out html with background settings
if(!function_exists('gt3_background_render')) {
	function gt3_background_render($opt_name, $meta_conditional = false, $meta_value = false, $return_array = false, $force_bg_style = false){
		$image_array = gt3_option($opt_name."_bg_image");

		$bg_src     = !empty($image_array['background-image']) ? $image_array['background-image'] : '';
		$bg_repeat  = !empty($image_array['background-repeat']) ? $image_array['background-repeat'] : '';
		$bg_size    = !empty($image_array['background-size']) ? $image_array['background-size'] : '';
		$attachment = !empty($image_array['background-attachment']) ? $image_array['background-attachment'] : '';
		$position   = !empty($image_array['background-position']) ? $image_array['background-position'] : '';

		if(class_exists('\GT3\ThemesCore\Customizer')) {
			$bg_image_id         = gt3_option($opt_name."_bg_image_image");
			$bg_image_repeat     = gt3_option($opt_name."_bg_image_repeat");
			$bg_image_size       = gt3_option($opt_name."_bg_image_size");
			$bg_image_attachment = gt3_option($opt_name."_bg_image_attachment");
			$bg_image_position   = gt3_option($opt_name."_bg_image_position");

			$bg_src     = !empty($bg_image_id) ? wp_get_attachment_image_url($bg_image_id, 'full') : '';
			$bg_repeat  = !empty($bg_image_repeat) ? $bg_image_repeat : '';
			$bg_size    = !empty($bg_image_size) ? $bg_image_size : '';
			$attachment = !empty($bg_image_attachment) ? $bg_image_attachment : '';
			$position   = !empty($bg_image_position) ? $bg_image_position : '';
		}

		$id = gt3_get_queried_object_id();
		if(class_exists('RWMB_Loader') && $id !== 0) {
			if($meta_conditional != false) {
				$mb_conditional = rwmb_meta($meta_conditional, array(), $id);

				if($mb_conditional == $meta_value) {
					$bg_src = rwmb_meta('mb_'.$opt_name.'_bg_image', array(), $id);
					$bg_src = !empty($bg_src) ? $bg_src : '';
					if(!empty($bg_src)) {
						$bg_src = array_values($bg_src);
						$bg_src = $bg_src[0]['url'];
					}

					if(!empty($bg_src) || $force_bg_style) {
						$bg_repeat  = rwmb_meta('mb_'.$opt_name.'_bg_repeat', array(), $id);
						$bg_repeat  = !empty($bg_repeat) ? $bg_repeat : '';
						$bg_size    = rwmb_meta('mb_'.$opt_name.'_bg_size', array(), $id);
						$bg_size    = !empty($bg_size) ? $bg_size : '';
						$attachment = rwmb_meta('mb_'.$opt_name.'_bg_attachment', array(), $id);
						$attachment = !empty($attachment) ? $attachment : '';
						$position   = rwmb_meta('mb_'.$opt_name.'_bg_position', array(), $id);
						$position   = !empty($position) ? $position : '';
					} else {
						$bg_repeat  = '';
						$bg_size    = '';
						$attachment = '';
						$position   = '';
					}
				}
			}
		}
		$bg_styles = array();

		$bg_styles['background-image'] = !empty($bg_src) ? 'background-image:url('.esc_url($bg_src).');' : '';

		if(!empty($bg_src) || $force_bg_style) {
			$bg_styles['background-size']       = !empty($bg_size) ? 'background-size:'.esc_attr($bg_size).';' : '';
			$bg_styles['background-repeat']     = !empty($bg_repeat) ? 'background-repeat:'.esc_attr($bg_repeat).';' : '';
			$bg_styles['background-attachment'] = !empty($attachment) ? 'background-attachment:'.esc_attr($attachment).';' : '';
			$bg_styles['background-position']   = !empty($position) ? 'background-position:'.esc_attr($position).';' : '';
		}

		if($return_array) {
			return $bg_styles;
		}

		return implode('', $bg_styles);
	}
}

// return all sidebars
if(!function_exists('gt3_get_all_sidebar')) {
	function gt3_get_all_sidebar(){
		global $wp_registered_sidebars;
		$out = array( '' => '' );
		if(empty($wp_registered_sidebars)) {
			return;
		}
		foreach($wp_registered_sidebars as $sidebar_id => $sidebar) :
			$out[$sidebar_id] = $sidebar['name'];
		endforeach;

		return $out;
	}
}

//* Tiny mce adding *//
function gt3_mce_buttons(){
	if(current_user_can('edit_posts') && current_user_can('edit_pages')) {
		add_filter('mce_external_plugins', 'gt3_add_external_plugins', '11');
		add_filter('mce_buttons_3', 'gt3_mce_buttons_register_button', '11');
		add_filter('mce_buttons_2', 'gt3_mce_buttons_2', '11');
	}
}

add_action('init', 'gt3_mce_buttons');

function gt3_add_external_plugins($plugin_array){
	$plugin_array['gt3_external_tinymce_plugins'] = get_template_directory_uri().'/core/admin/js/tinymce-button.js';

	return $plugin_array;
}

function gt3_mce_buttons_register_button($buttons){
	array_push($buttons, 'SocialIcon', 'DropCaps', 'Highlighter', 'TitleLine', 'LinkStyling', 'ListStyle', 'Columns', 'ToolTip');

	return $buttons;
}

function gt3_mce_buttons_2($buttons){
	array_unshift($buttons, 'styleselect');

	return $buttons;
}

function gt3_tiny_mce_before_init($settings){
	$settings['theme_advanced_blockformats'] = 'p,h1,h2,h3,h4';
	$style_formats                           = array(
		array(
			'title' => esc_html__('Font Weight', 'ewebot'),
			'items' => array(
				array( 'title' => esc_html__('Default', 'ewebot'), 'inline' => 'span', 'classes' => 'gt3_font-weight', 'styles' => array( 'font-weight' => 'inherit' ) ),
				array( 'title' => esc_html__('Lightest (100)', 'ewebot'), 'inline' => 'span', 'classes' => 'gt3_font-weight', 'styles' => array( 'font-weight' => '100' ) ),
				array( 'title' => esc_html__('Lighter (200)', 'ewebot'), 'inline' => 'span', 'classes' => 'gt3_font-weight', 'styles' => array( 'font-weight' => '200' ) ),
				array( 'title' => esc_html__('Light (300)', 'ewebot'), 'inline' => 'span', 'classes' => 'gt3_font-weight', 'styles' => array( 'font-weight' => '300' ) ),
				array( 'title' => esc_html__('Normal (400)', 'ewebot'), 'inline' => 'span', 'classes' => 'gt3_font-weight', 'styles' => array( 'font-weight' => '400' ) ),
				array( 'title' => esc_html__('Medium (500)', 'ewebot'), 'inline' => 'span', 'classes' => 'gt3_font-weight', 'styles' => array( 'font-weight' => '500' ) ),
				array( 'title' => esc_html__('Semi-Bold (600)', 'ewebot'), 'inline' => 'span', 'classes' => 'gt3_font-weight', 'styles' => array( 'font-weight' => '600' ) ),
				array( 'title' => esc_html__('Bold (700)', 'ewebot'), 'inline' => 'span', 'classes' => 'gt3_font-weight', 'styles' => array( 'font-weight' => '700' ) ),
				array( 'title' => esc_html__('Bolder (800)', 'ewebot'), 'inline' => 'span', 'classes' => 'gt3_font-weight', 'styles' => array( 'font-weight' => '800' ) ),
				array( 'title' => esc_html__('Extra Bold (900)', 'ewebot'), 'inline' => 'span', 'classes' => 'gt3_font-weight', 'styles' => array( 'font-weight' => '900' ) )
			),
		),
	);

	$settings['style_formats']           = str_replace('"', "'", json_encode($style_formats));
	$settings['extended_valid_elements'] = 'span[*],a[*],i[*]';

	return $settings;
}

add_filter('tiny_mce_before_init', 'gt3_tiny_mce_before_init');

function gt3_theme_add_editor_styles(){
	add_editor_style('css/font-awesome.min.css');
	add_editor_style('css/tiny_mce.css');
}

add_action('current_screen', 'gt3_theme_add_editor_styles');

function gt3_wpdocs_theme_add_editor_styles(){
	add_editor_style('css/font-awesome.min.css');
	add_editor_style('css/tiny_mce.css');
}

add_action('current_screen', 'gt3_wpdocs_theme_add_editor_styles');
// end

function gt3_categories_postcount_filter($variable){
	preg_match('/(class="count")/', $variable, $matches);
	if(empty($matches)) {
		$variable = str_replace('</a> (', '</a> <span class="post_count">', $variable);
		$variable = str_replace('</a>&nbsp;(', '</a>&nbsp;<span class="post_count">', $variable);
		$variable = str_replace(')', '</span>', $variable);
	}

	return $variable;
}

add_filter('get_archives_link', 'gt3_categories_postcount_filter');
add_filter('wp_list_categories', 'gt3_categories_postcount_filter');

if(!function_exists('gt3_open_graph_meta')) {
	add_action('wp_head', 'gt3_open_graph_meta', 5);
	function gt3_open_graph_meta(){
		global $post;
		if(!is_singular()) //if it is not a post or a page
		{
			return;
		}
		echo '<meta property="og:title" content="'.esc_attr(get_the_title()).'"/>';
		echo '<meta property="og:type" content="article"/>';
		echo '<meta property="og:url" content="'.esc_url(get_permalink()).'"/>';
		echo '<meta property="og:site_name" content="'.esc_html(get_bloginfo('name')).'"/>';
		if(has_post_thumbnail($post->ID)) { //the post does not have featured image, use a default image
			$thumbnail_src = wp_get_attachment_image_src(get_post_thumbnail_id($post->ID), 'medium_large');
			echo '<meta property="og:image" content="'.esc_attr($thumbnail_src[0]).'"/>';
		}
	}
}

if(!function_exists('gt3_translateColumnWidthToSpan')) {
	function gt3_translateColumnWidthToSpan($gt3_width){
		preg_match('/(\d+)\/(\d+)/', $gt3_width, $matches);

		if(!empty($matches)) {
			$part_x = (int) $matches[1];
			$part_y = (int) $matches[2];
			if($part_x > 0 && $part_y > 0) {
				$value  = ceil($part_x/$part_y*12);
				$value2 = ceil((1-$part_x/$part_y)*12);
				if($value > 0 && $value <= 12) {
					$gt3_width   = array();
					$gt3_width[] = $value;
					$gt3_width[] = $value2;
				}
			}
		}

		return $gt3_width;
	}
}

function gt3_get_queried_object_id(){
	$id = get_queried_object_id();
	if($id == 0 && class_exists('WooCommerce')) {
		if(is_shop()) {
			$id = get_option('woocommerce_shop_page_id');
		} else if(is_cart()) {
			$id = get_option('woocommerce_cart_page_id');
		} else if(is_checkout()) {
			$id = get_option('woocommerce_checkout_page_id');
		}
	}

	return $id;
}

if(!function_exists('getSolidColorFromImage')) {
	function getSolidColorFromImage($filepath){
		$attach_id = gt3_get_image_id($filepath);
		if(!empty($attach_id)) {
			$solid_color = get_post_meta($attach_id, 'solid_color', true);
			if(!empty($solid_color)) {
				return $solid_color;
			}
		}

		$type = wp_check_filetype($filepath);
		if(!empty($type) && is_array($type) && !empty($type['ext'])) {
			$type = $type['ext'];
		} else {
			return '#D3D3D3';
		}
		$allowedTypes = array(
			'gif',  // [] gif
			'jpg',  // [] jpg
			'png',  // [] png
			'bmp'   // [] bmp
		);
		if(!in_array($type, $allowedTypes)) {
			return '#D3D3D3';
		}
		$im = false;
		switch($type) {
			case 'gif' :
				$im = imageCreateFromGif($filepath);
				break;
			case 'jpg' :
				$im = imageCreateFromJpeg($filepath);
				break;
			case 'png' :
				$im = imageCreateFromPng($filepath);
				break;
			case 'bmp' :
				$im = imageCreateFromBmp($filepath);
				break;
		}

		if($im) {
			$thumb = imagecreatetruecolor(1, 1);
			imagecopyresampled($thumb, $im, 0, 0, 0, 0, 1, 1, imagesx($im), imagesy($im));
			$mainColor = strtoupper(dechex(imagecolorat($thumb, 0, 0)));
			update_post_meta($attach_id, 'solid_color', $mainColor);

			return $mainColor;
		} else {
			return '#D3D3D3';
		}
	}
}

function gt3_get_image_id($image_url){
	global $wpdb;
	$attachment = $wpdb->get_col($wpdb->prepare("SELECT ID FROM $wpdb->posts WHERE guid='%s';", $image_url));

	return $attachment[0];
}

add_filter(
	'gt3/elementor/core/cpt/register', function(){
	return array(
		'team',
		'portfolio',
	);
}
);

function gt3_filter_allowed_html($allowed, $context){
	if(!empty($allowed['a']) && is_array($allowed['a'])) {
		$allowed['a']['data-color']       = true;
		$allowed['a']['data-hover-color'] = true;
	}

	return $allowed;
}

add_filter('wp_kses_allowed_html', 'gt3_filter_allowed_html', 10, 2);

function gt3_pingback_header(){
	if(is_singular() && pings_open()) {
		echo '<link rel="pingback" href="', esc_url(get_bloginfo('pingback_url')), '">';
	}
}

add_action('wp_head', 'gt3_pingback_header');

/* WPDA Burger */
add_filter(
	'wpda-builder/elementor/widgets', function($widgets){
	$widgets[] = 'Burger_Sidebar';

	return $widgets;
}
);

add_filter(
	'wpda_burger_sidebar_icon', function(){
	return '<i class="burger_sidebar_icon"><span class="first"></span><span class="second"></span><span class="third"></span></i>';
}
);

/* WPDA Walker */
add_filter(
	'wpda_walker_menu', function(){
	return new GT3_Walker_Nav_Menu ();
}
);

/* BUBBLES */
if(!function_exists('gt3_get_bubbles')) {
	function gt3_get_bubbles(){

		$bubbles_block = gt3_option('bubbles_block');
		if(class_exists('RWMB_Loader')) {
			$mb_bubbles_block = rwmb_meta('mb_bubbles_block', array(), gt3_get_queried_object_id());
			if(!empty($mb_bubbles_block) && $mb_bubbles_block != 'default') {
				$bubbles_block = $mb_bubbles_block;
			}
		}

		if(gt3_customizer_enabled()) {
			$bubbles_block = true;
		}

		if($bubbles_block == '1' || $bubbles_block == 'show' || true === $bubbles_block) {
			echo "<div class='bubbles_wrap'><div class='bubble x1'></div><div class='bubble x2'></div><div class='bubble x3'></div><div class='bubble x4'></div><div class='bubble x5'></div><div class='bubble x6'></div><div class='bubble x7'></div><div class='bubble x8'></div><div class='bubble x9'></div><div class='bubble x10'></div></div>";
		}
	}
}

/* GT3 Default Header */
if(!function_exists('gt3_get_default_header')) {
	function gt3_get_default_header(){
		$header = apply_filters('theme/print_header', false);

		if(false === $header) {

			echo "<div class='gt3_header_builder'><div class='gt3_header_builder__container'><div class='gt3_header_builder__section gt3_header_builder__section--middle'><div class='gt3_header_builder__section-container container'>";

			// Logo
			echo "<div class='middle_left left header_side'><div class='logo_container'><span class='site-title'><a href='".esc_url(home_url('/'))."'>".get_bloginfo('name')."</a></span></div></div>";

			//Menu
			if(has_nav_menu('main_menu')) {
				echo "<div class='middle_right right header_side'>";
				echo "<div class='gt3_header_builder_component gt3_header_builder_menu_component'><nav class='main-menu main_menu_container'>";
				gt3_main_menu();
				echo "</nav>";
				echo '<div class="mobile-navigation-toggle"><div class="toggle-box"><div class="toggle-inner"></div></div></div></div>';
				echo "</div>";
			}

			echo "</div></div></div>";

			// Mobile Menu
			ob_start();
			if(has_nav_menu('main_menu')) {
				gt3_main_menu();
			}
			$menu = ob_get_clean();
			if(!empty($menu)) {
				echo "<div class='mobile_menu_container'><div class='container'><div class='gt3_header_builder_component gt3_header_builder_menu_component'><nav class='main-menu main_menu_container'>".(($menu))."</nav></div></div></div>";
			}

			echo "</div>";
		}
	}
}

if(!function_exists('gt3_get_default_footer')) {
	function gt3_get_default_footer(){


		echo '<footer class="main_footer fadeOnLoad clearfix gt3_default_footer" id="footer"><div class="copyright align-center"><div class="container"><div class="row"><div class="span12"><p>'.esc_html__('© 2021 — Ewebot by GT3Themes. All Rights Reserved.', 'ewebot').'</p></div></div></div></div></footer>';
	}
}

add_filter('gt3/core/mega-menu-enable', '__return_true');

function gt3_theme_style($handle, $url = '', $deps = array(), $version = GT3_THEME_VERSION){
	if(class_exists('\GT3\ThemesCore\Assets\Style')) {
		\GT3\ThemesCore\Assets\Style::enqueue_theme_asset($handle);

		return;
	}
	wp_enqueue_style('gt3-'.$handle, $url, $deps, $version);
}

function gt3_theme_script($handle, $url = '', $deps = array(), $version = GT3_THEME_VERSION){
	if(class_exists('\GT3\ThemesCore\Assets\Script')) {
		\GT3\ThemesCore\Assets\Script::enqueue_theme_asset($handle);

		return;
	}
	wp_enqueue_script('gt3-'.$handle, $url, $deps, $version, true);
}

/**
 * Remove/Disable/DeQueue jQuery Migrate in WordPress.
 */

add_action(
	'wp_head', function(){
	wp_register_style('gt3-main_inline-handle', false);
	wp_enqueue_style('gt3-main_inline-handle');
	$css = <<<CSS
html, body {
			margin: 0;
			padding: 0;
		}

		h1, h2, h3, h4, h5, h6 {
			margin: 0;
			padding: 0;
			word-wrap: break-word;
		}
CSS;

	wp_add_inline_style('gt3-main_inline-handle', $css);
}, 0
);

add_filter(
	'style_loader_tag', function($html, $handle, $href, $media){
	if(in_array($handle, array( 'theme-font', 'font-awesome-5-all', 'font-awesome-4-shim', 'elementor-icons-fa-brands' ))) {
		$html = preg_replace('#rel=(["|\'])stylesheet["|\']#', 'rel=$1preload$1 as=$1style$1 onload="this.rel=\'stylesheet\'"', $html);
	}

	return $html;
}, 10, 4
);

function gt3_font_face_styled(){
	?>
	<style>
        @font-face {
            font-family: 'Theme_icon';
            src: url(<?php echo get_parent_theme_file_uri() ?>/fonts/flaticon.woff2) format("woff2");
            font-weight: 400;
            font-style: normal;
        }

        @font-face {
            font-family: 'FontAwesome';
            src: url(<?php echo get_parent_theme_file_uri() ?>/fonts/fontawesome-webfont.woff2) format('woff2');
            font-weight: normal;
            font-style: normal;
        }
	</style>
	<?php
}

add_action('wp_head', 'gt3_font_face_styled', 0);
add_action('admin_head', 'gt3_font_face_styled', 0);

function gt3_has_shop_on_page(){

	static $is_shop = null;
	if(!is_null($is_shop)) {
		return $is_shop;
	}

	if(!class_exists('WooCommerce')) {
		$is_shop = false;

		return $is_shop;
	}
	if(is_singular()) {
		global $post;
		$post_id = $post->ID;
		$meta = get_post_meta($post_id, '_elementor_controls_usage', true);
		$meta = maybe_unserialize($meta);

		if(is_array($meta) && key_exists('gt3-core-shoplist', $meta)) {
			$is_shop = true;
		}
		if (!$is_shop) {
			function gt3_find_elementor_widget(&$key){
				if(key_exists('widgetType', $key) && in_array($key['widgetType'], array( 'gt3-core-shoplist'))) {
					return true;
				}

				if(key_exists('elements', $key) && is_array($key['elements']) && count($key['elements'])) {
					foreach($key['elements'] as &$element) {
						if (gt3_find_elementor_widget($element)) return true;
					}
				}
				return false;
			}

			if (class_exists('\Elementor\Plugin')) {
				$elementor       = \Elementor\Plugin::instance();
				$elementor_post  = $elementor->documents->get($post_id);
				$is_meta_updated = null;
				if($elementor_post !== false) {
					$meta = $elementor_post->get_json_meta('_elementor_data');
					foreach($meta as &$level0) {
						$is_shop = gt3_find_elementor_widget($level0);
						if($is_shop) {
							break;
						}
					}
				}
			}
		}
	}

	if(!$is_shop && (is_shop() || is_product_category() || is_product_tag() || is_product_taxonomy() || is_product() || is_cart() || is_account_page() || is_checkout()) || (function_exists('yith_wcwl_is_wishlist_page') && yith_wcwl_is_wishlist_page())) {
		$is_shop = true;
	}
	if(!$is_shop) {
		$is_shop = false;
	}

	return $is_shop;
}

/* Portfolio Archive */
add_filter(
	'gt3_column_width', function(){
	return '8';
}
);

add_filter(
	'gt3_archive_pf_layout', function(){
	return 'no_sidebar';
}
);

add_filter(
	'gt3_archive_pf_columns', function(){
	return gt3_option("portfolio_archive_layout");
}
);

add_action('wp_body_open', 'gt3_preloader', 10);
add_action('elementor/theme/before_do_header', 'gt3_preloader', 10);

add_action('wp_footer', 'gt3_get_bubbles', 10);
add_action(
	'wp_footer', function(){
	/* Back2Top */
	if(gt3_option('back_to_top') == '1' || true === gt3_option('back_to_top') || gt3_customizer_enabled()) {
		echo "<div class='back_to_top_container'><a href='".esc_js("javascript:void(0)")."' class='gt3_back2top' id='back_to_top'></a></div>";
	}

	if(class_exists('Woocommerce') && is_product()) {
		do_action('gt3_footer_action');
	}

	if((gt3_option('modern_shop') == '1' || true === gt3_option('modern_shop')) && function_exists('gt3_has_shop_on_page') && gt3_has_shop_on_page()) {
		echo '<div class="gt3-mobile__burger_shop_sidebar"><div class="gt3-mobile__burger_shop_sidebar_close"></div><div class="gt3-mobile_shop_burger_container"></div></div>';
	}

}, 10
);

add_filter('gt3/core/docs/url', function() {
	return 'https://help.ewebotwp.com/docs/';
});
