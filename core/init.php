<?php

add_action('init', function() {
	require_once __DIR__.'/customizer/customizer.php';
	require_once __DIR__.'/loader.php';



	if (!!gt3_option('optimize_migrate')) {
		add_action( 'wp_default_scripts', function($scripts){
			if (! is_admin() && isset($scripts->registered['jquery'])) {
				$script = $scripts->registered['jquery'];

				if ($script->deps) { // Check whether the script has any dependencies
					$script->deps = array_diff($script->deps, array('jquery-migrate'));
				}
			}
		}, 25);
	}

}, 0);
if ( class_exists( 'GT3_Core_Elementor' ) ) {
	require_once( get_template_directory().'/elementor/init.php' ); // Theme elementor init file
}
if ( class_exists('WooCommerce') ) {
	require_once( get_template_directory() . '/woocommerce/wooinit.php' ); // Woocommerce init file
}
require_once(__DIR__. "/registrator/license_verification.php");

