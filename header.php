<!DOCTYPE html>
<html <?php language_attributes(); ?>>
<head>
	<meta http-equiv="Content-Type" content="<?php bloginfo('html_type'); ?>; charset=<?php bloginfo('charset'); ?>">
	<?php echo((gt3_option('responsive') == "1" || true === gt3_option('responsive')) ? '<meta name="viewport" content="width=device-width, initial-scale=1, maximum-scale=1">' : ''); ?>
	<meta http-equiv="X-UA-Compatible" content="IE=Edge">
	<!--	<link rel="preconnect" href="https://ajax.googleapis.com" />-->
	<!--	<link rel="preconnect" href="https://fonts.googleapis.com" />-->
	<!--	<link rel="preconnect" href="https://fonts.gstatic.com" />-->
	<?php wp_head(); ?>
</head>

<body <?php body_class(); ?> <?php echo 'data-theme-color="'.esc_attr(gt3_option("theme-custom-color")).'"'; ?> >
<?php
wp_body_open();
gt3_get_default_header();
$gt3_ID = gt3_get_queried_object_id();
if(!is_404() && get_post_type() != 'gallery') {
	gt3_get_page_title($gt3_ID);
}
?>
<div class="site_wrapper fadeOnLoad">
	<?php

	$page_shortcode = '';
	if(class_exists('RWMB_Loader')) {
		$page_shortcode = rwmb_meta('mb_page_shortcode');
		if(strlen($page_shortcode) > 0) {
			echo do_shortcode($page_shortcode);
		}
	}
	?>
	<div class="main_wrapper">
