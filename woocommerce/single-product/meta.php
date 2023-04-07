<?php
/**
 * Single Product Meta
 *
 * This template can be overridden by copying it to yourtheme/woocommerce/single-product/meta.php.
 *
 * HOWEVER, on occasion WooCommerce will need to update template files and you
 * (the theme developer) will need to copy the new files to your theme to
 * maintain compatibility. We try to do this as little as possible, but it does
 * happen. When this occurs the version of the template file will be bumped and
 * the readme will list any important changes.
 *
 * @see         https://docs.woocommerce.com/document/template-structure/
 * @package     WooCommerce\Templates
 * @version     99.3.0.0
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

global $product;
?>
<div class="product_meta">

	<?php do_action( 'woocommerce_product_meta_start' ); ?>

	<?php if ( wc_product_sku_enabled() && ( $product->get_sku() || $product->is_type( 'variable' ) ) ) : ?>

		<span class="sku_wrapper"><?php esc_html_e( 'SKU:', 'ewebot' ); ?> <span class="sku"><?php echo ( $sku = $product->get_sku() ) ? $sku : esc_html__( 'N/A', 'ewebot' ); ?></span></span>

	<?php endif; ?>

	<?php echo wc_get_product_category_list( $product->get_id(), ', ', '<span class="posted_in">' . _n( 'Category:', 'Categories:', count( $product->get_category_ids() ), 'ewebot' ) . ' ', '</span>' ); ?>

	<?php echo wc_get_product_tag_list( $product->get_id(), ', ', '<span class="tagged_as">' . _n( 'Tag:', 'Tags:', count( $product->get_tag_ids() ), 'ewebot' ) . ' ', '</span>' ); ?>

	<?php do_action( 'woocommerce_product_meta_end' ); ?>

</div>
<?php
$featured_image = wp_get_attachment_image_src(get_post_thumbnail_id($product->get_id()), 'single-post-thumbnail');
if (!$featured_image) $featured_image = array(
	0 => '',
);
if ((gt3_option('modern_shop') == '1' && gt3_option('product_sharing') == '1') || (gt3_option('modern_shop') == true && gt3_option('product_sharing') === true)) {	?>
	<div class="gt3_product_sharing">
		<ul>
			<li><?php echo esc_html__( 'Share:', 'ewebot' ); ?></li>
			<li class="twitter"><a href="<?php echo esc_url('https://twitter.com/intent/tweet?text='.$product->get_title().'&amp;url='.$product->get_permalink()) ?>"><i class="fa fa-twitter" target="_blank"></i></a></li>
			<li class="facebook"><a href="<?php echo esc_url('https://www.facebook.com/sharer.php?u='.$product->get_permalink()) ?>" target="_blank"><i class="fa fa-facebook"></i></a></li>
			<?php
				if(strlen($featured_image[0]) > 0) {
					echo '<li class="pinterest"><a target="_blank" href="'.esc_url('https://pinterest.com/pin/create/link/?url='.$product->get_permalink().'&media='.$featured_image[0].'&description='.$product->get_title()).'"><i class="fa fa-pinterest-p"></i></a></li>';
				}
			?>
		</ul>
	</div>
<?php } ?>

