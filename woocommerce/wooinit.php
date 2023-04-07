<?php

// declare woocomerece custom theme stylesheets and js
function css_js_woocomerce() {
	wp_enqueue_script( 'imagesloaded' );
	if ( class_exists( 'WC_List_Grid' ) ) {
		global $WC_List_Grid;
		add_action( 'wp_enqueue_scripts', array( $WC_List_Grid, 'setup_scripts_styles' ), 20 );
	}
	gt3_theme_script('jquery/appear', get_template_directory_uri() . '/dist/js/jquery/appear.js',array('jquery'));
	if (gt3_option('modern_shop') == '1' || true === gt3_option('modern_shop')) {
		wp_enqueue_script('gt3_zoom', get_template_directory_uri() . '/woocommerce/js/easyzoom.js', array('jquery'), false, false);
	}
	gt3_theme_script('woocommerce/theme-woo', get_template_directory_uri() . '/dist/js/woocommerce/theme-woo.js',array('jquery'));

	wp_enqueue_style('woocommerce', get_template_directory_uri() . '/dist/css/woocommerce.css' );
	if (gt3_option('modern_shop') == '1' || true === gt3_option('modern_shop')) {
		wp_enqueue_style('gt3-modern-shop', get_template_directory_uri() . '/dist/css/modern-shop.css' );
	}

//	wp_enqueue_script( 'gt3-appear', get_template_directory_uri() . '/js/jquery.appear.min.js', array('gt3-infinite-scroll'), false, true );
//	wp_register_script( 'gt3-infinite-scroll', get_template_directory_uri() . '/woocommerce/js/infinite-scroll.pkgd.min.js', array( 'jquery' ), '3.0.5', true );

	if (is_product()) {
		wp_enqueue_script('gt3_sticky_thumb', get_template_directory_uri() . '/woocommerce/js/jquery.sticky-kit.min.js', array('jquery'), false, false);
	}

	$products_infinite_scroll = gt3_option( 'products_infinite_scroll' );
	if ( ! empty( $products_infinite_scroll ) && $products_infinite_scroll !== 'none' ) {
		gt3_theme_script('woocommerce/infinite-scroll', get_template_directory_uri() . '/dist/js/woocommerce/infinite-scroll.js',array('jquery'));

//
//		wp_enqueue_script( 'gt3-infinite-scroll');


	}
}

add_action('wp_enqueue_scripts', 'css_js_woocomerce');
// end of declare woocomerece custom theme stylesheets and js


if (!function_exists('gt3_get_woo_template')) {
    function gt3_get_woo_template ($tmpl, $settings = NULL) {
        $locate = locate_template('woocommerce/' . $tmpl . '.php');
        if (!empty($locate)){
            require $locate;
        }
    }
}

function gt3_get_template ($tmpl, $extension = NULL) {
    get_template_part( 'woocommerce/gt3-templates/' . $tmpl, $extension );
}

remove_action('woocommerce_sidebar', 'woocommerce_get_sidebar', 10);

function gt3_product_title_wrapper () {
    echo '<h3 class="gt3-product-title">'.get_the_title().'</h3>';
}

function gt3_product_image_wrap_open () {
    echo '<div class="gt3-product-image-wrapper">';
}

function gt3_product_image_wrap_close () {
    echo '</div>';
}

function gt3_add_label_outofstock () {
    global $product;
    if (!($product->is_in_stock())) {
        echo '<div class="gt3-product-outofstock"><span class="gt3-product-outofstock__inner">'.esc_html__('Out Of Stock', 'ewebot').'</span></div>';
    }
}
add_action('woocommerce_before_shop_loop_item_title', 'gt3_add_label_outofstock', 6);

// Remove woocommerce breadcrumb
remove_action('woocommerce_before_main_content','woocommerce_breadcrumb', 20);
//add breadcrumb to single product
add_action('init', function() {
	if ( (gt3_option('page_title_breadcrumbs_conditional') == '1' && gt3_option('page_title_conditional') == '1') || (gt3_option('page_title_breadcrumbs_conditional') === true && gt3_option('page_title_conditional') === true) ) {
		add_action('woocommerce_single_product_summary','woocommerce_breadcrumb', 4);
	}
});


add_action( 'yith_wcqv_product_image', 'gt3_product_image_wrap_open', 9 );
add_action( 'yith_wcqv_product_image', 'gt3_product_image_wrap_close', 21 );

function gt3_add_thumb_wcqv () {
    add_action('woocommerce_product_thumbnails', 'woocommerce_show_product_thumbnails', 2);
}
add_action( 'wp_ajax_yith_load_product_quick_view', "gt3_add_thumb_wcqv", 1);
add_action( 'wp_ajax_nopriv_yith_load_product_quick_view', 'gt3_add_thumb_wcqv',1 );

remove_action( 'yith_wcqv_product_summary', 'woocommerce_template_single_excerpt', 20 );

function gt3_page_template () {
	$id = gt3_get_queried_object_id();
    switch (is_single()) {
        case true:
            $layout = gt3_option('product_sidebar_layout');
            $sidebar = gt3_option('product_sidebar_def');
            break;
        case false:
            $layout = gt3_option('products_sidebar_layout');
            $sidebar = gt3_option('products_sidebar_def');
            break;
        default:
            $layout = gt3_option('products_sidebar_layout');
            $sidebar = gt3_option('products_sidebar_def');
    }
    if (class_exists( 'RWMB_Loader' ) && $id !== 0 && !(class_exists('WooCommerce') && is_product_category())) {
        $mb_layout = rwmb_meta('mb_page_sidebar_layout', array(), $id);
        if (!empty($mb_layout) && $mb_layout != 'default') {
            $layout = $mb_layout;
            $sidebar = rwmb_meta('mb_page_sidebar_def', array(), $id);
        }
    }
    if ( ($layout == 'left' || $layout == 'right') && is_active_sidebar( $sidebar )  ) {
        $column = 9;
    }else{
        $column = 12;
    }
    if ($sidebar == '') {
        $layout = 'none';
    }
    $row_class = ' sidebar_'.esc_attr($layout);

    $container_style = 'container';
    if ( !is_single() && get_post_type() == 'product') {
        $container_style = gt3_option('products_layout');
    } elseif (class_exists( 'RWMB_Loader' ) && is_single() && get_post_type() == 'product') {
        if (rwmb_meta('mb_single_product', array(), $id) === 'custom' ) {
            $container_style = rwmb_meta('mb_product_container', array(), $id);
        } else {
            $container_style = gt3_option('product_container');
        }
    }
    switch ($container_style) {
        case 'container':
            $container_class = 'container';
            break;
        case 'full_width':
            $container_class = 'fullwidth-wrapper';
            break;
        default:
            $container_class = 'container';
    }
    ?>

    <div class="<?php echo esc_html($container_class) ?>">
        <div class="row<?php echo esc_attr($row_class); ?>">

            <div class="content-container span<?php echo (int)$column; ?>">
                <section id='main_content'>
    <?php
}
add_action('woocommerce_before_main_content', 'gt3_page_template', 9);

// add bottom part of page template
function gt3_page_template_close () {
	$id = gt3_get_queried_object_id();
    switch (is_single()) {
        case true:
            $layout = gt3_option('product_sidebar_layout');
            $sidebar = gt3_option('product_sidebar_def');
            break;
        case false:
            $layout = gt3_option('products_sidebar_layout');
            $sidebar = gt3_option('products_sidebar_def');
            break;
        default:
            $layout = gt3_option('products_sidebar_layout');
            $sidebar = gt3_option('products_sidebar_def');
    }
    if (class_exists( 'RWMB_Loader' ) && $id !== 0 && !(class_exists('WooCommerce') && is_product_category())) {
        $mb_layout = rwmb_meta('mb_page_sidebar_layout', array(), $id);
        if (!empty($mb_layout) && $mb_layout != 'default') {
            $layout = $mb_layout;
            $sidebar = rwmb_meta('mb_page_sidebar_def', array(), $id);
        }
    }

    if ( ($layout == 'left' || $layout == 'right') && is_active_sidebar( $sidebar )  ) {
        $column = 9;
    }else{
        $column = 12;
        $sidebar = '';
    }
    if ($sidebar == '') {
        $layout = 'none';
    }
    ?>
                </section>
            </div>
            <?php
            if ($layout == 'left' || $layout == 'right') {
                echo '<div class="sidebar-container span'.(12 - (int)$column).'">';
                    if (is_active_sidebar( $sidebar )) {
                        echo "<aside class='sidebar'>";
                        dynamic_sidebar( $sidebar );
                        echo "</aside>";
                    }
                echo "</div>";
            }
            ?>
        </div>
    </div>
    <?php
}
add_action('woocommerce_after_main_content', 'gt3_page_template_close', 11);

// add sidebar to bottom on Shop page
function gt3_woo_bottom_products_sidebar_top(){
    $gt3_recently_viewed = gt3_option('woocommerce_recently_viewed');
    if ( !(bool)$gt3_recently_viewed ) return;
    if ( is_shop() || is_product_category() || is_product_tag() || is_product_taxonomy() ) {
        gt3_get_template('gt3-recently-viewed');
    }
}
add_action('woocommerce_after_shop_loop', 'gt3_woo_bottom_products_sidebar_top', 50);

if ( isset( $_GET['show'] ) ) {
	function gt3_products_per_page() {
		return $_GET['show'];
	}

	add_filter( 'loop_shop_per_page', 'gt3_products_per_page', 20 );
}

//Track product views.
function gt3_track_product_view() {
    $gt3_recently_viewed = gt3_option('woocommerce_recently_viewed');
    if ( !is_singular('product') || !(bool)$gt3_recently_viewed ) return;

    $viewed_products = empty($_COOKIE['gt3_product_recently_viewed']) ? array() : (array)explode('|',$_COOKIE['gt3_product_recently_viewed']);

    global $post;
    if ( ! in_array( $post->ID, $viewed_products ) ) {
        $viewed_products[] = $post->ID;
    }
    if ( sizeof( $viewed_products ) > 15 ) {
        array_shift( $viewed_products );
    }

    // Store for session only
    wc_setcookie( 'gt3_product_recently_viewed', implode( '|', $viewed_products ) );
}
add_action( 'template_redirect', 'gt3_track_product_view', 20 );

/* Products Page filter bar Top */
function gt3_woo_header_products_open () {
	echo '<div class="gt3-products-header">';
}
function gt3_woo_header_pagination() {
	$woocommerce_pagination   = gt3_option( 'woocommerce_pagination' );
	$products_infinite_scroll = gt3_option( 'products_infinite_scroll' );
	$view_all                 = isset($_COOKIE['gt3-show_all']) ? $_COOKIE['gt3-show_all'] : NULL;
	if ( ( $woocommerce_pagination == 'top' || $woocommerce_pagination == 'top_bottom' ) && $products_infinite_scroll !== 'always' && $view_all !== 'true' ) {
		echo '<div class="gt3-pagination_nav">';
		if (gt3_option('modern_shop') == '1' || true === gt3_option('modern_shop')) {
			woocommerce_breadcrumb();
		} else {
			woocommerce_pagination();
		}
		echo '</div>';
	}
}

function gt3_woo_header_products_per_page() {
	$filter_number = gt3_option( 'products_per_page_frontend' );
	$products_infinite_scroll = gt3_option( 'products_infinite_scroll' );
	if ( (bool) $filter_number && $products_infinite_scroll !== 'always' ) {
		gt3_get_template( 'loop/product-show' ); // Result Count
	}
}
function gt3_woo_header_catalog_ordering(){
	$products_sorting = gt3_option('products_sorting_frontend');
	if ( (bool)$products_sorting ) {
		gt3_get_template('loop/orderby');
	}
}
function gt3_woo_header_products_close () {
	echo '</div><!--close-->';
}

function gt3_products_infinite_scroll_open() {
	$products_infinite_scroll = gt3_option( 'products_infinite_scroll' );
	echo '<div class="infinite_scroll-' . ( ! empty( $products_infinite_scroll ) ? esc_attr( $products_infinite_scroll ) : 'none' ) . '">';
}
function gt3_products_infinite_scroll_close() {
	echo '</div>';
}
add_action( 'woocommerce_before_shop_loop', 'gt3_products_infinite_scroll_open', 5 );
add_action( 'woocommerce_after_shop_loop', 'gt3_products_infinite_scroll_close', 45 );

add_action('woocommerce_before_shop_loop', 'gt3_woo_header_products_open', 9);
add_action('woocommerce_before_shop_loop', 'gt3_woo_header_pagination', 13); // GT3 Pagination
add_action('woocommerce_before_shop_loop', 'gt3_woo_header_products_per_page', 20); // Show
add_action('woocommerce_before_shop_loop', 'gt3_woo_header_catalog_ordering', 25); // Ordering
add_action('woocommerce_before_shop_loop', 'gt3_woo_header_products_close', 35);
add_action('woocommerce_before_shop_loop', 'wc_print_notices', 40);
add_action('woocommerce_before_shop_loop', 'woocommerce_output_all_notices', 40);

remove_action('woocommerce_before_shop_loop','wc_print_notices',10);
remove_action('woocommerce_before_shop_loop','woocommerce_output_all_notices',10);
remove_action('woocommerce_before_shop_loop','woocommerce_result_count',20);
remove_action('woocommerce_before_shop_loop','woocommerce_catalog_ordering',30);
/* Products Page filter bar Top end */

/* Products Page filter bar Bottom */
function gt3_woo_products_bottom () {
	if ( ! wc_get_loop_prop( 'is_paginated' ) || ! woocommerce_products_will_display() ) {
		return;
	}
    $woocommerce_pagination = gt3_option('woocommerce_pagination');
    if ( $woocommerce_pagination == 'bottom' || $woocommerce_pagination == 'top_bottom' ){
        echo '<div class="gt3-products-bottom">';
            if (function_exists('gt3_get_woo_template')) {
                gt3_get_woo_template( 'loop/default-pagination' );
            }
        echo '</div>';

    }
	//echo '<a href="'.esc_js("javascript:void(0)").'" class="gt3_products_loadmore">Load More</a>';
}
add_action('woocommerce_after_shop_loop', 'gt3_woo_products_bottom', 15);
remove_action('woocommerce_after_shop_loop','woocommerce_pagination',10);

function gt3_products_bubblings(){?>
    <div class="spinner infinite-scroll">
        <div class="infinite-scroll-request">
            <div class="bounce1"></div>
            <div class="bounce2"></div>
            <div class="bounce3"></div>
        </div>
    </div>
	<?php
}
add_action('woocommerce_after_shop_loop', 'gt3_products_bubblings', 11);
add_action('gt3_woocommerce_after_shop_loop', 'gt3_products_bubblings', 11);

function gt3_wrap_single_product_open () {
    echo '<div class="gt3-single-content-wrapper">';
}
function gt3_wrap_single_product_close () {
    echo '</div>';
}

function gt3_add_sticky_parent_open() {
	$thumb_direction = gt3_option( 'product_layout' );
	$id              = gt3_get_queried_object_id();
	if ( class_exists( 'RWMB_Loader' ) ) {
		$mb_single_product = rwmb_meta( 'mb_single_product', array(), $id );
		if ( $mb_single_product === 'custom' ) {
			$thumb_direction = rwmb_meta( 'mb_thumbnails_layout', array(), $id );
		}
	}
	echo '<div class="gt3-single-product-sticky gt3_thumb_sticky_' . $thumb_direction . '">';
}

function gt3_add_sticky_parent_close() {
	echo '</div>';
}

// Add theme support for single product
function gt3_add_single_product_opts () {
    add_theme_support('woocommerce', array(
	    'gallery_thumbnail_image_width' => 400,
    ) );
    add_theme_support('wc-product-gallery-zoom');
    add_theme_support('wc-product-gallery-slider');
    add_theme_support('wc-product-gallery-lightbox');
}
add_action('after_setup_theme','gt3_add_single_product_opts');

add_filter( 'woocommerce_add_to_cart_fragments', 'woocommerce_header_add_to_cart_fragment' );
function woocommerce_header_add_to_cart_fragment( $fragments ) {
  global $woocommerce;
  ob_start();
    ?>
      <i class='woo_mini-count'><?php echo ((WC()->cart->cart_contents_count > 0) ? '<span>' . esc_html( WC()->cart->cart_contents_count ) .'</span>' : '') ?></i>
    <?php
    $fragments['.woo_mini-count'] = ob_get_clean();

    ob_start();
    echo '<div class="gt3_header_builder_cart_component__cart-container">';
    woocommerce_mini_cart();
    echo '</div>';
    $fragments['.gt3_header_builder_cart_component__cart-container'] = ob_get_clean();

    return $fragments;
}

remove_action( 'woocommerce_before_single_product_summary', 'woocommerce_show_product_sale_flash', 10 );
add_action('woocommerce_product_thumbnails', 'woocommerce_show_product_sale_flash', 25);

// add vertical thumbnails options
function gt3_option_thumbnail_slider() {
	return array(
		'rtl'            => is_rtl(),
		'animation'      => "fade",
		'smoothHeight'   => false,
		'directionNav'   => false,
		'controlNav'     => 'thumbnails',
		'slideshow'      => false,
		'animationSpeed' => 500,
		'animationLoop'  => false, // Breaks photoswipe pagination if true.
	);
}
add_filter( 'woocommerce_single_product_carousel_options', 'gt3_option_thumbnail_slider' );
// Remove script in single
function gt3_dequeue_script() {
	$id = gt3_get_queried_object_id();
	if ( class_exists( 'RWMB_Loader' ) && rwmb_meta( 'mb_single_product', array(), $id ) === 'custom' ) {
		$gt3_single_layout = rwmb_meta( 'mb_thumbnails_layout', array(), $id );
		$gt3_sticky_thumb  = rwmb_meta( 'mb_sticky_thumb', array(), $id );
	} else {
		$gt3_single_layout = gt3_option( 'product_layout' );
		$gt3_sticky_thumb  = gt3_option( 'sticky_thumb' );
	}

	if ( $gt3_single_layout === "thumb_grid" || $gt3_single_layout === "thumb_vertical" ) {
		wp_dequeue_script( 'zoom' );
		wp_dequeue_script( 'flexslider' );
	}

	if ( $gt3_sticky_thumb ) {
		remove_action( 'woocommerce_before_single_product', 'wc_print_notices', 10 );

		add_action( 'woocommerce_before_single_product_summary', 'gt3_add_sticky_parent_open', 1 );
		add_action( 'woocommerce_after_single_product_summary', 'gt3_add_sticky_parent_close', 12 );

		add_action( 'woocommerce_before_single_product_summary', 'gt3_wrap_single_product_open', 30 );
		add_action( 'woocommerce_before_single_product_summary', 'wc_print_notices', 35 );
		add_action( 'woocommerce_after_single_product_summary', 'gt3_wrap_single_product_close', 11 );
	}

	if ( $gt3_single_layout === "thumb_vertical" ) {
		remove_action( 'woocommerce_before_single_product', 'wc_print_notices', 10 );

		add_action( 'woocommerce_before_single_product_summary', 'gt3_add_sticky_parent_open', 1 );
		add_action( 'woocommerce_after_single_product_summary', 'gt3_add_sticky_parent_close', 12 );

		add_action( 'woocommerce_before_single_product_summary', 'gt3_wrap_single_product_open', 30 );
		add_action( 'woocommerce_before_single_product_summary', 'wc_print_notices', 35 );
		add_action( 'woocommerce_after_single_product_summary', 'gt3_wrap_single_product_close', 11 );
	}
}

add_action( 'wp_print_scripts', 'gt3_dequeue_script', 100 );

// Add class to thumbnails wrapper's
function gt3_thumb_class_view( $content ) {
	$thumb_direction  = gt3_option( 'product_layout' );
	$gt3_sticky_thumb = gt3_option( 'sticky_thumb' );
	$carousel_thumb   = gt3_option( 'activate_carousel_thumb' );

	$id = gt3_get_queried_object_id();
	if ( class_exists( 'RWMB_Loader' ) ) {
		$mb_single_product = rwmb_meta( 'mb_single_product', array(), $id );
		if ( $mb_single_product === 'custom' ) {
			$thumb_direction  = rwmb_meta( 'mb_thumbnails_layout', array(), $id );
			$gt3_sticky_thumb = rwmb_meta( 'mb_sticky_thumb', array(), $id );
		}
	}

	switch ( $thumb_direction ) {
		case 'vertical':
			array_push( $content, 'gt3_thumb_vertical' );
			if ( $carousel_thumb ) {
				array_push( $content, 'gt3_carousel_thumb' );
			}else{
				array_push( $content, 'gt3_carousel_none' );
			}
			break;
		case 'horizontal':
			array_push( $content, 'gt3_thumb_horizontal' );
			break;
		case 'thumb_grid':
			array_push( $content, 'gt3_thumb_grid' );
			break;
		case 'thumb_vertical':
			array_push( $content, 'gt3_thumb_grid_vertical' );
			break;
		default:
			array_push( $content, 'gt3_thumb_horizontal' );
			break;
	}
	if ( $gt3_sticky_thumb && $thumb_direction !== 'thumb_vertical' ) {
		array_push( $content, 'gt3_sticky_thumb' );
	}

	global $product;
	$attachment_ids = $product->get_gallery_image_ids();
	if ( ! empty( $attachment_ids ) ) {
		array_push( $content, 'gt3_gallery_attached' );
	}

	return $content;
}
add_filter( 'woocommerce_single_product_image_gallery_classes', 'gt3_thumb_class_view' );

/* Add size guide button on single product */
function gt3_size_guide() {
	$id = gt3_get_queried_object_id();
	$shop_size_guide = gt3_option( 'shop_size_guide' );
	if ( $shop_size_guide == 1 ) {
		$size_guide     = gt3_option( 'size_guide' );
		$size_guide_url = ! empty( $size_guide['url'] ) ? $size_guide['url'] : '';
	} else {
		$size_guide_url = '';
	}
	if ( class_exists( 'RWMB_Loader' ) ) {
		$mb_img_size_guide = rwmb_meta( 'mb_img_size_guide', array(), $id );
		switch ( $mb_img_size_guide ) {
			case 'custom':
				$size_guide_url = rwmb_meta( 'mb_size_guide', 'size=full', $id );
				break;
			case 'none':
				$size_guide_url = '';
				break;
			default:
				break;
		}
	}
    if (!empty($size_guide_url)) {
        echo '<div class="gt3_block_size_popup"><a href="#" class="image_size_popup_button theme_icon-home-repair">'.esc_html__('Size Guide', 'ewebot').'</a></div><!-- gt3_block_size_popup -->';
    }
}
add_action('woocommerce_single_product_summary', 'gt3_size_guide', 31);
function gt3_popup_image_guide() {
	$shop_size_guide = gt3_option( 'shop_size_guide' );
	$id = gt3_get_queried_object_id();

	if ( $shop_size_guide == 1 ) {
		$size_guide     = gt3_option( 'size_guide' );
		$size_guide_url = ! empty( $size_guide['url'] ) ? $size_guide['url'] : '';
		$size_guide_id  = ! empty( $size_guide['id'] ) ? get_the_title($size_guide['id']) : '';
	} else {
		$size_guide_url = $size_guide_id = '';
	}
	if ( class_exists( 'RWMB_Loader' ) ) {
		$mb_img_size_guide = rwmb_meta( 'mb_img_size_guide', array(), $id );
		switch ( $mb_img_size_guide ) {
			case 'custom':
				$mb_size_guide = rwmb_meta( 'mb_size_guide', 'size=full', $id );
				if ( ! empty( $mb_size_guide ) ) {
					$size_guide_image_src = array_values( $mb_size_guide );
					$size_guide_url       = ! empty( $size_guide_image_src ) ? $size_guide_image_src[0]['full_url'] : '';
				} else {
					$size_guide_url = '';
				}
				break;

			case 'none':
				$size_guide_url = '';
				break;
			default:
				break;
		}
	}
	if ( ! empty( $size_guide_url ) ) {
		echo '<div class="image_size_popup">
            <div class="layer"></div>
            <div class="size_guide_block"><div class="wrapper_size_guide">
                <span class="close"></span>
                <a href="'.esc_url( $size_guide_url ).'" target="_blank">
                    <img src="'.esc_url( $size_guide_url ).'" alt="'.esc_attr($size_guide_id).'">
                </a>
            </div></div>
          </div>';
	}
}
add_action('gt3_footer_action', 'gt3_popup_image_guide', 20);  // footer.php

/* Add next/prev buttons on single product */
add_action('init', function(){

	if((bool) gt3_option('next_prev_product') && class_exists('GT3_WooCommerce_Adjacent_Products')) {
		add_action('woocommerce_after_single_product_summary', 'gt3_prev_next_product', 17);
		function gt3_prev_next_product(){
			// Show only products in the same category?
			$in_same_term   = apply_filters('gt3_single_product_pagination_same_category', true);
			$excluded_terms = apply_filters('gt3_single_product_pagination_excluded_terms', '');
			$taxonomy       = apply_filters('gt3_single_product_pagination_taxonomy', 'product_cat');

			$previous_product = gt3_get_previous_product($in_same_term, $excluded_terms, $taxonomy);
			$next_product     = gt3_get_next_product($in_same_term, $excluded_terms, $taxonomy);

			if(!$previous_product && !$next_product) {
				return;
			}

			?>
			<ul class='gt3_product_list_nav'>
			<?php if($previous_product) : ?>
				<li>
					<a href="<?php echo esc_url($previous_product->get_permalink()); ?>" rel="prev">
						<?php
						if(apply_filters('gt3_next_prev_product_img', false)) {
							echo '<div class="product_list_nav_thumbnail">';
							echo wp_kses_post($previous_product->get_image());
							echo '</div>';
						}

						echo '<div class="product_list_nav_text">';
						echo '<span class="nav_title">';
						echo wp_kses_post($previous_product->get_name());
						echo '</span>';
						echo '<span class="nav_text">'.esc_html__('PREV', 'ewebot').'</span>';
						echo '<span class="nav_price">'.wp_kses_post($previous_product->get_price()).'</span>';
						echo '</div>';
						?>
					</a>
				</li>
			<?php endif; ?>

			<?php if($next_product) : ?>
				<li>
					<a href="<?php echo esc_url($next_product->get_permalink()); ?>" rel="next">
						<?php
						if(apply_filters('gt3_next_prev_product_img', false)) {
							echo '<div class="product_list_nav_thumbnail">';
							echo wp_kses_post($next_product->get_image());
							echo '</div>';
						}

						echo '<div class="product_list_nav_text">';
						echo '<span class="nav_title">';
						echo wp_kses_post($next_product->get_name());
						echo '</span>';
						echo '<span class="nav_text">'.esc_html__('NEXT', 'ewebot').'</span>';
						echo '<span class="nav_price">'.wp_kses_post($next_product->get_price()).'</span>';
						echo '</div>';
						?>
					</a>
				</li>
			<?php endif; ?>
			</ul><?php
		}
	}
});
function gt3_get_previous_product( $in_same_term = false, $excluded_terms = '', $taxonomy = 'product_cat' ) {
	$product = new GT3_WooCommerce_Adjacent_Products( $in_same_term, $excluded_terms, $taxonomy, true );
	return $product->get_product();
}
function gt3_get_next_product( $in_same_term = false, $excluded_terms = '', $taxonomy = 'product_cat' ) {
	$product = new GT3_WooCommerce_Adjacent_Products( $in_same_term, $excluded_terms, $taxonomy );
	return $product->get_product();
}

// Wishlist button wrap in
function gt3_output_wishlist_button_listing() {
    if ( class_exists( 'YITH_WCWL_Shortcode' ) && get_option('yith_wcwl_enabled') == true ) {
        echo '<div class="gt3_add_to_wishlist">'.do_shortcode( '[yith_wcwl_add_to_wishlist]' ).'</div>';
    }
}
// Quick View button wrap in
function gt3_output_quick_view_button_listing() {
    if ( class_exists('YITH_WCQV_Frontend') && get_option('yith-wcqv-enable') ) {
        global $product;
        echo '<div class="gt3_quick_view">'.do_shortcode( '[yith_quick_view product_id="'.$product->get_id().'"]' ).'</div>';
    }
}

// Add 'Hot' and 'New' labels for products
add_action( 'woocommerce_product_options_general_product_data', 'woo_add_custom_general_field' );
add_action( 'woocommerce_process_product_meta', 'woo_add_custom_general_fields_save' );
function woo_add_custom_general_field() {
    global $woocommerce, $post;

    echo '<div class="options_group">';
    woocommerce_wp_checkbox( array(
        'id'            => '_checkbox_hot',
        'label'         => esc_html__( 'Hot Product', 'ewebot' ),
        'description'   => esc_html__( 'Check for Hot Product', 'ewebot' )
    ) );
    woocommerce_wp_checkbox( array(
        'id'            => '_checkbox_new',
        'label'         => esc_html__( 'New Product', 'ewebot' ),
        'description'   => esc_html__( 'Check for New Product', 'ewebot' )
    ) );
    echo '</div>';
}
function woo_add_custom_general_fields_save( $post_id ){
    $woocommerce_checkbox = isset( $_POST['_checkbox_hot'] ) ? 'yes' : 'no';
    update_post_meta( $post_id, '_checkbox_hot', $woocommerce_checkbox );

    $woocommerce_checkbox = isset( $_POST['_checkbox_new'] ) ? 'yes' : 'no';
    update_post_meta( $post_id, '_checkbox_new', $woocommerce_checkbox );
}

add_action('woocommerce_product_thumbnails','gt3_hot_new_product', 30);
add_action('woocommerce_before_shop_loop_item_title','gt3_hot_new_product', 36);
function gt3_hot_new_product(){
    global $product;

	$is_new = get_post_meta( $product->get_id(), '_checkbox_new', true );
	if ( 'yes' == $is_new ) {
		echo '<span class="onsale new-product">'.esc_html__('New','ewebot').'</span>';
	}

    if ($product->is_on_sale()) {
    	if (gt3_option('modern_shop') == '1' || true === gt3_option('modern_shop')) {
		    if ( $product->get_type() == 'variable' ) {
			    $available_variations = $product->get_available_variations();
			    $maximumper = 0;
			    for ($i = 0; $i < count($available_variations); ++$i) {
				    $variation_id=$available_variations[$i]['variation_id'];
				    $variable_product1= new WC_Product_Variation( $variation_id );
				    $regular_price = $variable_product1->get_regular_price();
				    $sales_price = $variable_product1->get_sale_price();
				    if( $sales_price ) {
					    $percentage= round( ( ( $regular_price - $sales_price ) / $regular_price ) * 100 ) ;
					    if ($percentage > $maximumper) {
						    $maximumper = $percentage;
					    }
				    }
			    }
			    echo '<span class="onsale">-' . $maximumper  . '%</span>';
		    } elseif ( $product->get_type() == 'simple' ) {
			    $percentage = round( ( ( $product->get_regular_price() - $product->get_sale_price() ) / $product->get_regular_price() ) * 100 );
			    echo '<span class="onsale">-' . $percentage . '%</span>';
		    }
	    } else {
		    echo '<span class="onsale">'.esc_html__('Sale!','ewebot').'</span>';
	    }
    }

    $is_hot = get_post_meta( $product->get_id(), '_checkbox_hot', true );
    if ( 'yes' == $is_hot ) {
        echo '<span class="onsale hot-product">'.esc_html__('Hot','ewebot').'</span>';
    }
}

// Add name of variation to option
add_filter( 'woocommerce_dropdown_variation_attribute_options_args', 'gt3_variable_choose_an_option_rename', 10);
function gt3_variable_choose_an_option_rename( $args ){
    $attr = get_taxonomy( $args['attribute'] ); //Select the attribute from the taxonomy
    if (is_object($attr)) {
        $fix = $attr->name;
        $fix = wc_attribute_label( $fix );
    }else{
        $fix = esc_html__('an option','ewebot');
    }
    $args['show_option_none'] = esc_html__('Choose ','ewebot' ).$fix;
    return $args; //Returns "Select a size" or "Select a color" depending on what your attribute name is.
}
// !Add name of variation to option

function gt3_open_control_tag () {
    echo '<div class="gt3_woocommerce_open_control_tag">';
}
function gt3_close_control_tag () {
    echo '</div>';
}

add_action('woocommerce_after_shop_loop_item', 'gt3_open_control_tag', 9);
add_action('woocommerce_after_shop_loop_item', 'gt3_close_control_tag', 35);

remove_action( 'woocommerce_after_shop_loop_item', 'woocommerce_template_loop_product_link_close', 5);
add_action('woocommerce_before_shop_loop_item_title', 'woocommerce_template_loop_product_link_close', 15);

function gt3_animation_wrapper_product_open(){
	echo '<div class="gt3-animation-wrapper gt3-anim-product '. ((gt3_option('modern_shop')== '1' || true === gt3_option('modern_shop')) ? esc_html('gt3_modern_shop_item') : "") .'">';
}
function gt3_animation_wrapper_product_close(){
	echo '</div><!-- .gt3-anim-product -->';
}
add_action( 'woocommerce_before_shop_loop_item', 'gt3_animation_wrapper_product_open', 5 );
add_action( 'woocommerce_after_shop_loop_item', 'gt3_animation_wrapper_product_close', 45 );

function gt3_wrapper_product_thumbnail_open() {
	echo '<div class="gt3-product-thumbnail-wrapper '. ((gt3_option('modern_shop')== '1') ? esc_html('gt3_products_gallery_image') : "") .'">';
}
function gt3_wrapper_product_thumbnail_close() {
	echo '</div>';
}
add_action('woocommerce_before_shop_loop_item', 'gt3_wrapper_product_thumbnail_open', 9);
add_action('woocommerce_before_shop_loop_item_title', 'gt3_wrapper_product_thumbnail_close', 17);

// title wrapper
add_action('woocommerce_shop_loop_item_title', 'woocommerce_template_loop_product_link_open', 8 );
add_action('woocommerce_shop_loop_item_title', 'woocommerce_template_loop_product_link_close', 12 );

// replace star-rating
remove_action('woocommerce_after_shop_loop_item_title', 'woocommerce_template_loop_rating', 5);

add_action('init', function(){

	if(gt3_option('modern_shop') == '1' || true === gt3_option('modern_shop')) {
		add_action('woocommerce_after_shop_loop_item', 'woocommerce_template_loop_rating', 5);
	} else {
		add_action('woocommerce_shop_loop_item_title', 'woocommerce_template_loop_rating', 5);
	}
});

/** @param \WP_Query $query */
function advanced_search_query($query) {
    if(!is_admin() && $query->is_search()) {
	    return $query;
	    //////////////////////////////////////
        // category terms search.
        if (!$query->is_main_query()) return $query;

	    $post_type = $query->get('post_type', array());
	    if (!is_array($post_type) && is_string($post_type)) {
	    	$post_type = array($post_type);
	    }
	    $post_type[] = 'post';
	    $post_type[] = 'page';
	    $post_type[] = 'portfolio';

	    $query->set('post_type', $post_type);


	    return $query;


	    $gt3_product_cat = !empty($_GET['gt3_product_cat']) ? esc_attr($_GET['gt3_product_cat']) : '';

        $query_args = array();
        $query_args['post_type']    = 'product';
        $query_args['post_status']  = 'publish';

        $search_keyword = esc_attr($_GET['s']);
        $query_args['s'] = $search_keyword && strlen($search_keyword) > 0 ? $search_keyword : '';

        if (!empty($gt3_product_cat) && $gt3_product_cat != '0' && $gt3_product_cat != '') {
            $query_args['tax_query']['relation'] = 'OR';
            $query_args['tax_query'][] = array(
                'taxonomy'  => 'gt3_product_cat',
                'field'     => 'slug',
                'terms'     => get_cat_name($gt3_product_cat),
            );
        }

        // Set query variables
        foreach ($query_args as $key => $value) {
            $query->set($key, $value);
        }
    }
    return $query;
}
add_action('pre_get_posts', 'advanced_search_query', 1000);

//remove woocommerce_taxonomy_archive_description from category page
remove_action('woocommerce_archive_description', 'woocommerce_taxonomy_archive_description', 10);

add_filter( 'woocommerce_show_page_title', function () { return false; } );

function gt3_woocommerce_output_related_products_args($args){
	$layout_single  = gt3_option( 'product_sidebar_layout' );
	$layout_shop    = gt3_option( 'products_sidebar_layout' );
	$id = gt3_get_queried_object_id();
	if ( class_exists( 'RWMB_Loader' ) && $id !== 0 && ! ( class_exists( 'WooCommerce' ) && is_product_category() ) ) {
		$mb_layout = rwmb_meta( 'mb_page_sidebar_layout', array(), $id );
		if ( ! empty( $mb_layout ) && $mb_layout != 'default' ) {
			$layout_single  = $mb_layout;
		}
	}

	$columns = wc_get_default_products_per_row();
	if ( ($layout_single === 'left' || $layout_single === 'right') && ($layout_shop !== 'left' && $layout_shop !== 'right') && (int)$columns > 1) {
		$columns = (int)$columns - 1;
	} elseif ( ($layout_single !== 'left' && $layout_single !== 'right') && ($layout_shop === 'left' || $layout_shop === 'right') ) {
		$columns = (int)$columns + 1;
	}

	$args['posts_per_page'] = $columns;
	$args['columns']        = $columns;

	return $args;
}
add_filter( 'woocommerce_output_related_products_args', 'gt3_woocommerce_output_related_products_args' );

function gt3_woocommerce_cart_item_remove_link($string, $cart_item_key) {
    $string = str_replace('class="remove"', '', $string);
    return str_replace('&times;', '', $string);
}
add_filter( 'woocommerce_cart_item_remove_link', 'gt3_woocommerce_cart_item_remove_link', 10, 2 );

function gt3_woocommerce_product_gallery_trigger(){
	echo '<div class="woocommerce-product-gallery__trigger">'.esc_html__('Fullscreen','ewebot').'</div>';
}
add_action('woocommerce_before_single_product_summary','gt3_woocommerce_product_gallery_trigger', 1);

function gt3_woocommerce_product_thumbnails_columns(){
    return 3;
}
add_filter('woocommerce_product_thumbnails_columns', 'gt3_woocommerce_product_thumbnails_columns');

function gt3_woocommerce_breadcrumb_defaults(){
	return array(
        'delimiter'   => '<span class="gt3_pagination_delimiter"></span>',
        'wrap_before' => '<nav class="woocommerce-breadcrumb">',
        'wrap_after'  => '</nav>',
        'before'      => '',
        'after'       => '',
        'home'        => _x( 'Home', 'breadcrumb', 'ewebot' ),
    );
}
add_filter('woocommerce_breadcrumb_defaults', 'gt3_woocommerce_breadcrumb_defaults');

add_filter( 'posts_results', function ( $results, $args ) {
	/** @var \WP_Query $args */
	if ( !is_null(get_queried_object()) && ( is_shop() || is_product_category() || is_product_tag() || is_product_taxonomy() ) && ! count( $results ) && $args->get( 'paged' ) > 1 ) {
		$args->set( 'paged', 1 );
		$gt3_posts_results = $args->get_posts();
		if ( count( $gt3_posts_results ) > 0 ) {
			return $gt3_posts_results;
		}
	}

	return $results;
}, 10, 2 );

add_action('init', function(){

	if(!!gt3_option('optimize_woo')) {
		add_action('wp_enqueue_scripts', function(){
			if(function_exists('is_woocommerce')) {
				// Check if it's any of WooCommerce page
				if(!is_woocommerce() && !is_cart() && !is_account_page() && !is_checkout() && !(function_exists('gt3_has_shop_on_page') && gt3_has_shop_on_page())) {

					## Dequeue WooCommerce styles
					wp_dequeue_style('woocommerce-layout');
					wp_dequeue_style('woocommerce-general');
					wp_dequeue_style('woocommerce-smallscreen');
					wp_dequeue_style('wc-block-style');
					wp_dequeue_style('wc-block-vendors-style');

					wp_dequeue_style('woocommerce');
					wp_dequeue_style('gt3-modern-shop');
					wp_dequeue_style('berocket_aapf_widget-style');
					wp_dequeue_style('wc-blocks-vendors-style');
					wp_dequeue_style('wc-blocks-style');
					wp_dequeue_style('woocommerce_prettyPhoto_css');
					wp_dequeue_style('woo-variation-swatches');
					wp_dequeue_style('woo-variation-swatches-theme-override');
					wp_dequeue_style('woo-variation-swatches-tooltip');
					wp_dequeue_style('photoswipe');
					wp_dequeue_style('photoswipe-default-skin');
					wp_dequeue_style('jquery-selectBox');
					wp_dequeue_style('yith-wcwl-font-awesome');
					wp_dequeue_style('yith-wcwl-main');
					wp_dequeue_style('yith-quick-view');

					## Dequeue WooCommerce scripts
					wp_dequeue_script('wc-cart-fragments');
					wp_dequeue_script('woocommerce');
					wp_dequeue_script('wc-add-to-cart');

					wp_deregister_script('js-cookie');
					wp_dequeue_script('js-cookie');

					wp_dequeue_script('gt3_zoom');
					wp_dequeue_script('prettyPhoto');
					wp_dequeue_script('jquery-blockui');
					wp_dequeue_script('wc-add-to-cart-variation');
					wp_dequeue_script('photoswipe');
					wp_dequeue_script('photoswipe-ui-default');
					wp_dequeue_script('wc-single-product');
					wp_dequeue_script('jquery-selectBox');
					wp_dequeue_script('jquery-yith-wcwl');
					wp_dequeue_script('yith-wcqv-frontend');
					wp_dequeue_script('woo-variation-swatches');

				}
			}
		}, 25);
	}
});

/* GT3 Gallery Thumbnails */
function gt3_add_gallery_thumbnails ($product) {
	global $product;

	$attachment_ids = $product->get_gallery_image_ids();

	$limit = gt3_option('gallery_images_count');

	$id = $product->get_id();
	if (class_exists( 'RWMB_Loader' )) {
		$mb_gallery_images_count_state = rwmb_meta('mb_gallery_images_count_state', array(), $id);

		if ($mb_gallery_images_count_state === 'custom') {
			$limit = rwmb_meta('mb_gallery_images_count', array(), $id);
		}
	}

	if (class_exists('Elementor\Plugin') && \Elementor\Plugin::instance()->editor->is_edit_mode()) {
		$limit =1;
	}

	$attachment_ids = array_slice($attachment_ids,0,--$limit);

	foreach( $attachment_ids as $attachment_id ) {
		echo '<a href="'.esc_url( get_post_permalink() ).'" data-max="'.gt3_option('gallery_images_count').'" >'. wp_get_attachment_image($attachment_id, 'woocommerce_thumbnail') . '</a>';

	}

	if (class_exists('\GT3\ThemesCore\Assets\Style') && class_exists('\GT3\ThemesCore\Assets\Script')) {
		\GT3\ThemesCore\Assets\Style::enqueue_core_asset('slick');
		\GT3\ThemesCore\Assets\Script::enqueue_core_asset('slick');
	}
}


// Remove Sales Flash
function gt3_hide_sales_flash() {
	return false;
}

/**
 * Filer WooCommerce Flexslider options - Add Navigation Arrows
 */
function gt3_update_woo_flexslider_options( $options ) {
	$options['directionNav'] = true;
	return $options;
}

/* Add to Cart Attrs */
function gt3_add_cart_custom_attrs( $args ) {
	/** @var \WC_Product */
	global $product;
	$args['attributes']['data-title'] = $product->add_to_cart_text();
	return $args;
}

/* Quick View Attrs */
function gt3_quick_view_custom_attrs() {
	global $product;

	$output  = '<a href="#" class="button yith-wcqv-button" data-product_id="' . esc_attr( $product->get_id() ) . '" data-title="'.esc_html__('Quick View','ewebot').'">'.esc_html__('Quick View','ewebot').'</a>';
	return $output;
}

add_filter('woocommerce_sale_flash', 'gt3_hide_sales_flash');

function gt3_rename_reviews_tab( $tabs ) {
	global $product;
	$count = $product->get_review_count();
	if ( $count && wc_review_ratings_enabled() ) {
		/* translators: 1: reviews count 2: product name */
		$reviews_title = sprintf( esc_html( _n( '%1$s review for %2$s', '%1$s reviews for %2$s', $count, 'ewebot' ) ), esc_html( $count ), '<span>"' . get_the_title() . '"</span>' );
	} else {
		$reviews_title = esc_html__( 'Reviews', 'ewebot' );
	}

	$tabs['reviews']['title'] = $reviews_title;
	return $tabs;
}

function gt3_woocommerce_price_html( $price, $product ){
	return preg_replace('@(<del>.*?</del>).*?(<ins>.*?</ins>)@misx', '$2 $1', $price);
}

function remove_gt3_theme_support() {
	remove_theme_support( 'wc-product-gallery-zoom' );
}

function gt3_add_cart_product_category($name, $cart_item)
{
	$product_item = $cart_item['data'];

	$cat_ids = $product_item->get_category_ids();
	if ( $cat_ids && is_cart() ) {
		$name .= wc_get_product_category_list($product_item->get_id(), ', ', '<span class="gt3-cart_category">' . _n('', '', count($cat_ids), 'ewebot') . ' ', '</span>');
	}

	return $name;

}

function gt3_remove_variation_product_cart_title($title, $cart_item, $cart_item_key) {
	$_product = $cart_item['data'];
	$product_permalink = apply_filters('woocommerce_cart_item_permalink', $_product->is_visible() ? $_product->get_permalink($cart_item) : '', $cart_item, $cart_item_key);

	if ($_product->is_type('variation')) {
		if (!$product_permalink) {
			return $_product->get_title();
		} else {
			return sprintf('<a href="%s">%s</a>', esc_url($product_permalink), $_product->get_title());
		}
	}

	return $title;
}

function gt3_rename_unselect_all( $localize ) {
	$localize['translate']['unselect_all'] = esc_html__('Remove all', 'ewebot');
	return $localize;
}

add_action('init', function(){

	if(gt3_option('modern_shop') == '1' || true === gt3_option('modern_shop')) {


		add_action('woocommerce_before_shop_loop_item_title', 'gt3_add_gallery_thumbnails', 16);

		add_filter('woocommerce_single_product_carousel_options', 'gt3_update_woo_flexslider_options');
		add_filter('woocommerce_loop_add_to_cart_args', 'gt3_add_cart_custom_attrs');
		add_filter('yith_add_quick_view_button_html', 'gt3_quick_view_custom_attrs');

		add_filter('woocommerce_product_description_heading', '__return_empty_string');
		add_filter('woocommerce_product_additional_information_heading', '__return_empty_string');
		add_filter('woocommerce_reviews_title', '__return_empty_string');
		add_filter('woocommerce_product_tabs', 'gt3_rename_reviews_tab');

		add_action('woocommerce_review_before_comment_meta', 'gt3_open_control_tag', 9);
		add_action('woocommerce_review_meta', 'gt3_close_control_tag', 11);

		remove_action('woocommerce_single_product_summary', 'woocommerce_template_single_rating', 10);
		add_action('woocommerce_single_product_summary', 'woocommerce_template_single_rating', 12);
		add_filter('woocommerce_get_price_html', 'gt3_woocommerce_price_html', 100, 2);

		add_action('after_setup_theme', 'remove_gt3_theme_support', 100);

		//add_filter('woocommerce_cart_item_name', 'gt3_remove_variation_product_cart_title', 10, 3);
		add_filter('woocommerce_cart_item_name', 'gt3_add_cart_product_category', 99, 3);

		add_filter('aapf_localize_widget_script', 'gt3_rename_unselect_all');

		add_filter('gt3_gridlist_woo_toggle_button_output', function($compile, $grid_view, $list_view){
			$grid_view     = esc_html__('Grid', 'ewebot');
			$list_view     = esc_html__('List', 'ewebot');
			$grid_ext_view = esc_html__('Grid Extended', 'ewebot');

			$compile = sprintf('<nav class="gt3-gridlist-toggle">
										<a href="#" id="grid" title="%1$s"><svg xmlns="https://www.w3.org/2000/svg" x="0px" y="0px" viewBox="0 0 512 512" style="enable-background:new 0 0 512 512;" xml:space="preserve"><path d="M187.628,0H43.707C19.607,0,0,19.607,0,43.707v143.921c0,24.1,19.607,43.707,43.707,43.707h143.921 c24.1,0,43.707-19.607,43.707-43.707V43.707C231.335,19.607,211.728,0,187.628,0z"/><path d="M468.293,0H324.372c-24.1,0-43.707,19.607-43.707,43.707v143.921c0,24.1,19.607,43.707,43.707,43.707h143.921 c24.1,0,43.707-19.607,43.707-43.707V43.707C512,19.607,492.393,0,468.293,0z"/><path d="M187.628,280.665H43.707C19.607,280.665,0,300.272,0,324.372v143.921C0,492.393,19.607,512,43.707,512h143.921	c24.1,0,43.707-19.607,43.707-43.707V324.372C231.335,300.272,211.728,280.665,187.628,280.665z"/><path d="M468.293,280.665H324.372c-24.1,0-43.707,19.607-43.707,43.707v143.921c0,24.1,19.607,43.707,43.707,43.707h143.921 c24.1,0,43.707-19.607,43.707-43.707V324.372C512,300.272,492.393,280.665,468.293,280.665z"/></svg></a>
										<a href="#" id="grid-extended" title="%2$s"><svg enable-background="new 0 0 24 24" height="512" viewBox="0 0 24 24" width="512" xmlns="https://www.w3.org/2000/svg"><path d="m5 0h-4c-.552 0-1 .448-1 1v4c0 .552.448 1 1 1h4c.552 0 1-.448 1-1v-4c0-.552-.448-1-1-1z"/><path d="m5 9h-4c-.552 0-1 .448-1 1v4c0 .552.448 1 1 1h4c.552 0 1-.448 1-1v-4c0-.552-.448-1-1-1z"/><path d="m5 18h-4c-.552 0-1 .448-1 1v4c0 .552.448 1 1 1h4c.552 0 1-.448 1-1v-4c0-.552-.448-1-1-1z"/><path d="m14 0h-4c-.552 0-1 .448-1 1v4c0 .552.448 1 1 1h4c.552 0 1-.448 1-1v-4c0-.552-.448-1-1-1z"/><path d="m14 9h-4c-.552 0-1 .448-1 1v4c0 .552.448 1 1 1h4c.552 0 1-.448 1-1v-4c0-.552-.448-1-1-1z"/><path d="m14 18h-4c-.552 0-1 .448-1 1v4c0 .552.448 1 1 1h4c.552 0 1-.448 1-1v-4c0-.552-.448-1-1-1z"/><path d="m23 0h-4c-.552 0-1 .448-1 1v4c0 .552.448 1 1 1h4c.552 0 1-.448 1-1v-4c0-.552-.448-1-1-1z"/><path d="m23 9h-4c-.552 0-1 .448-1 1v4c0 .552.448 1 1 1h4c.552 0 1-.448 1-1v-4c0-.552-.448-1-1-1z"/><path d="m23 18h-4c-.552 0-1 .448-1 1v4c0 .552.448 1 1 1h4c.552 0 1-.448 1-1v-4c0-.552-.448-1-1-1z"/></svg></a>
										<a href="#" id="list" title="%3$s"><svg xmlns="https://www.w3.org/2000/svg" x="0px" y="0px" width="612px" height="612px" viewBox="0 0 612 612" style="enable-background:new 0 0 612 612;" xml:space="preserve"><path d="M63.311,73.862C28.342,73.862,0,102.204,0,137.172s28.342,63.311,63.311,63.311c34.968,0,63.31-28.342,63.31-63.311 S98.279,73.862,63.311,73.862z M63.311,242.689C28.342,242.689,0,271.032,0,306c0,34.969,28.342,63.311,63.311,63.311 c34.968,0,63.31-28.342,63.31-63.311C126.621,271.032,98.279,242.689,63.311,242.689z M63.311,411.518 C28.342,411.518,0,439.859,0,474.827c0,34.969,28.342,63.311,63.311,63.311c34.968,0,63.31-28.342,63.31-63.311 C126.621,439.859,98.279,411.518,63.311,411.518z M232.138,179.379h337.655c23.319,0,42.207-18.888,42.207-42.207 s-18.888-42.207-42.207-42.207H232.138c-23.319,0-42.207,18.888-42.207,42.207S208.819,179.379,232.138,179.379z M569.793,263.793H232.138c-23.319,0-42.207,18.888-42.207,42.207s18.888,42.207,42.207,42.207h337.655 C593.112,348.207,612,329.319,612,306S593.112,263.793,569.793,263.793z M569.793,432.621H232.138 c-23.319,0-42.207,18.887-42.207,42.206s18.888,42.207,42.207,42.207h337.655c23.319,0,42.207-18.888,42.207-42.207 S593.112,432.621,569.793,432.621z"/></svg></a>										
									</nav>', $grid_view, $grid_ext_view, $list_view);

			return $compile.'<div class="gt3-mobile_filter_btn"><span>'.esc_html__('Filter', 'ewebot').'</span></div>';
		}, 10, 3);

		add_filter('wpda_cart_menu_icon', function(){
			$cart_icon = '<svg width="24" height="22" version="1.1" xmlns="https://www.w3.org/2000/svg" xmlns:xlink="https://www.w3.org/1999/xlink" x="0px" y="0px" viewBox="0 0 511.997 511.997" style="enable-background:new 0 0 511.997 511.997;" xml:space="preserve">
		<path d="M405.387,362.612c-35.202,0-63.84,28.639-63.84,63.84s28.639,63.84,63.84,63.84s63.84-28.639,63.84-63.84 S440.588,362.612,405.387,362.612z M405.387,451.988c-14.083,0-25.536-11.453-25.536-25.536s11.453-25.536,25.536-25.536 c14.083,0,25.536,11.453,25.536,25.536S419.47,451.988,405.387,451.988z"/>
		<path d="M507.927,115.875c-3.626-4.641-9.187-7.348-15.079-7.348H118.22l-17.237-72.12c-2.062-8.618-9.768-14.702-18.629-14.702 H19.152C8.574,21.704,0,30.278,0,40.856s8.574,19.152,19.152,19.152h48.085l62.244,260.443 c2.062,8.625,9.768,14.702,18.629,14.702h298.135c8.804,0,16.477-6.001,18.59-14.543l46.604-188.329 C512.849,126.562,511.553,120.516,507.927,115.875z M431.261,296.85H163.227l-35.853-150.019h341.003L431.261,296.85z"/>
		<path d="M173.646,362.612c-35.202,0-63.84,28.639-63.84,63.84s28.639,63.84,63.84,63.84s63.84-28.639,63.84-63.84 S208.847,362.612,173.646,362.612z M173.646,451.988c-14.083,0-25.536-11.453-25.536-25.536s11.453-25.536,25.536-25.536 s25.536,11.453,25.536,25.536S187.729,451.988,173.646,451.988z"/>
</svg>';

			return $cart_icon;
		});

		add_filter('woocommerce_post_class', function($classes){
			if('product' == get_post_type()) {
				$classes = array_diff($classes, array( 'first', 'last' ));
			}

			return $classes;
		}, 15);

	}
});
if(!function_exists('gt3_shop_body_classes')) {
	function gt3_shop_body_classes( $classes ) {
		if ((gt3_option('modern_shop') == '1' || true === gt3_option('modern_shop')) && function_exists('gt3_has_shop_on_page') && gt3_has_shop_on_page()) {
			$classes[] = 'woocommerce';
			$classes[] = 'woocommerce-page';
			$classes[] = 'gt3_modern_shop';
		}

		return $classes;
	}
}
add_filter( 'body_class','gt3_shop_body_classes' );

add_filter('wpda-builder/filter/allow-elementor/is_shop', function($items){
	$items[] = 'gt3-core-shoplist';

	return $items;
});
