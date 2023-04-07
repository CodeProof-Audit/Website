<?php
/**
 * Checkout Form
 *
 * This template can be overridden by copying it to yourtheme/woocommerce/checkout/form-checkout.php.
 *
 * HOWEVER, on occasion WooCommerce will need to update template files and you
 * (the theme developer) will need to copy the new files to your theme to
 * maintain compatibility. We try to do this as little as possible, but it does
 * happen. When this occurs the version of the template file will be bumped and
 * the readme will list any important changes.
 *
 * @see https://docs.woocommerce.com/document/template-structure/
 * @package WooCommerce\Templates
 * @version 99.3.5.0
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

if (!(gt3_option('modern_shop') == '1' || true === gt3_option('modern_shop'))) {
	do_action( 'woocommerce_before_checkout_form', $checkout );
}

// If checkout registration is disabled and not logged in, the user cannot checkout.
if ( ! $checkout->is_registration_enabled() && $checkout->is_registration_required() && ! is_user_logged_in() ) {
	echo esc_html( apply_filters( 'woocommerce_checkout_must_be_logged_in_message', __( 'You must be logged in to checkout.', 'ewebot' ) ) );
	return;
}

$col_1 = 'col-1';
$col_2 = 'col-2';

if (gt3_option('modern_shop') == '1' || true === gt3_option('modern_shop')) {
	echo '<div class="row">';

	$col_1 = 'gt3_checkout_billing gt3_row_fields';
	$col_2 = 'gt3_checkout_shipping gt3_row_fields';
}

?>
<form name="checkout" method="post" class="checkout woocommerce-checkout" action="<?php echo esc_url( wc_get_checkout_url() ); ?>" enctype="multipart/form-data">

	<?php if ( $checkout->get_checkout_fields() ) : ?>
		<?php if (gt3_option('modern_shop') == '1' || true === gt3_option('modern_shop')) {
			echo '<div class="span8 gt3_checkout_fields"><a class="gt3_back_cart" href="'. wc_get_cart_url() .'"><i class="fa fa-angle-left"></i>'. esc_html__('Back to Cart', 'ewebot') .'</a>';
		} ?>
		<?php do_action( 'woocommerce_checkout_before_customer_details' ); ?>

		<div class="col2-set" id="customer_details">
			<div class="<?php echo esc_attr($col_1) ?>">
				<?php do_action( 'woocommerce_checkout_billing' ); ?>
			</div>

			<div class="<?php echo esc_attr($col_2) ?>">
				<?php do_action( 'woocommerce_checkout_shipping' ); ?>
			</div>
		</div>

		<?php do_action( 'woocommerce_checkout_after_customer_details' ); ?>

		<?php if (gt3_option('modern_shop') == '1' || true === gt3_option('modern_shop')) {
			echo '</div>';
		} ?>

	<?php endif; ?>

	<?php if (gt3_option('modern_shop') == '1' || true === gt3_option('modern_shop')) {
		if ($checkout->get_checkout_fields()) {
			$column_width = 'span4';
		} else {
			$column_width = 'span12';
		}
		echo '<div class="'. esc_attr($column_width) .'"><div class="gt3_order_review_wrap">';
	} ?>

	<?php do_action( 'woocommerce_checkout_before_order_review_heading' ); ?>

	<h3 id="order_review_heading"><?php esc_html_e( 'Your order', 'ewebot' ); ?></h3>

	<?php do_action( 'woocommerce_checkout_before_order_review' ); ?>

	<div id="order_review" class="woocommerce-checkout-review-order">
		<?php do_action( 'woocommerce_checkout_order_review' ); ?>
	</div>

	<?php do_action( 'woocommerce_checkout_after_order_review' ); ?>

	<?php if (gt3_option('modern_shop') == '1' || true === gt3_option('modern_shop')) {
		echo '</div></div>';
	} ?>

</form>

<?php

if (gt3_option('modern_shop') == '1' || true === gt3_option('modern_shop')) {
	echo '</div>';

	do_action( 'woocommerce_before_checkout_form', $checkout );
}

do_action( 'woocommerce_after_checkout_form', $checkout ); ?>
