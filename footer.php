        </div><!-- .main_wrapper -->
	</div><!-- .site_wrapper -->
	<?php


	if (!is_404()) {
			$footer = apply_filters('theme/print_footer', false);

			if (false === $footer) {
				gt3_get_default_footer();
			}
		}


	wp_footer();

    ?>
</body>
</html>
