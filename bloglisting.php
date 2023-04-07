<?php
	$show_likes = gt3_option('blog_post_likes');
	$show_share = gt3_option('blog_post_share');

	if (gt3_customizer_enabled()) {
		$show_likes = true;
		$show_share = true;
	}

	$all_likes = gt3pb_get_option("likes");

	$comments_num = get_comments_number(get_the_ID());

	if ($comments_num == 1) {
		$comments_text = esc_html__('comment', 'ewebot');
	} else {
		$comments_text = esc_html__('comments', 'ewebot');
	}

	$post_date = $post_author = $post_category_compile = $post_comments = '';

	$class = is_sticky() ? ' gt3_sticky' : '';
	// Categories
	if (get_the_category()) $categories = get_the_category();
	if (!empty($categories)) {
		$post_categ = '';
		foreach ($categories as $category) {
			$post_categ = $post_categ . '<a href="' . esc_url(get_category_link($category->term_id)) . '">' . esc_html($category->cat_name) . '</a>' . '';
		}
		$post_category_compile .= '<span class="post_category">' . trim($post_categ, ', ') . '</span>';
	}else{
		$post_category_compile = '';
	}

	$post = get_post();

	$post_date = '<span class="post_date">' . esc_html(get_the_time(get_option( 'date_format' ))) . '</span>';

	$post_author = '<span class="post_author">' . esc_html__('by', 'ewebot') . ' <a href="' . esc_url(get_author_posts_url(get_the_author_meta('ID'))) . '">' . esc_html(get_the_author_meta('display_name')) . '</a></span>';

	if ((int)get_comments_number(get_the_ID()) != 0) {
		$post_comments = '<span class="post_comments"><a href="' . esc_url(get_comments_link()) . '" title="' . esc_attr(get_comments_number(get_the_ID())) . ' ' . $comments_text . '">' . esc_html(get_comments_number(get_the_ID())) . ' ' . $comments_text . '</a></span>';
	}

	// Post meta
	$post_meta =  $post_date . $post_author . $post_category_compile . $post_comments;

	$featured_image = wp_get_attachment_image_src(get_post_thumbnail_id(get_the_ID()), 'single-post-thumbnail');

	$pf = get_post_format();
	if (empty($pf)) $pf = "standard";

	ob_start();
	if (has_excerpt()) {
		$post_excerpt = the_excerpt();
	} else {
		$post_excerpt = the_content();
	}
	$post_excerpt = ob_get_clean();

	$width = '1170';
	$height = '700';

	$pf_media = gt3_get_pf_type_output($pf, $width, $height, $featured_image);

	$pf = $pf_media['pf'];


	$symbol_count = '400';

	if (gt3_option('blog_post_listing_content') == "1" || true === gt3_option('blog_post_listing_content') || gt3_customizer_enabled()) {
		$post_excerpt = preg_replace( '~\[[^\]]+\]~', '', $post_excerpt);
		$post_excerpt_without_tags = strip_tags($post_excerpt);
		$post_descr = gt3_smarty_modifier_truncate($post_excerpt_without_tags, $symbol_count, "...");
	} else {
		$post_descr = $post_excerpt;
	}

$post_descr = $post_excerpt;

	$post_title = get_the_title();

?>
	<div class="blog_post_preview format-<?php echo esc_attr($pf).esc_attr($class);?>">
		<div class="item_wrapper">
			<div class="blog_content">
			<?php

				if ($pf == 'gallery' || $pf == 'video') {
					echo  (($pf_media['content']));
				} elseif ($pf == 'standard-image') {
					echo '<a href="'.esc_url( get_permalink() ).'">'.$pf_media['content'].'</a>';
				}

				if ( strlen($post_meta) ) {
					echo '<div class="listing_meta_wrap">';
					echo '<div class="listing_meta">' . $post_meta . '</div>';
					echo '</div>';
				}

				if ($pf == 'link' || $pf == 'quote') {
					echo  (($pf_media['content']));
				} elseif (strlen($post_title) > 0) {
					$pf_icon = '';
					if ( is_sticky() ) {
						$pf_icon = '<i class="fa fa-thumb-tack"></i>';
					}
					echo '<h2 class="blogpost_title">' . $pf_icon . '<a href="' . esc_url(get_permalink()) . '">' . esc_html($post_title) . '</a></h2>';
				}

				if ($pf == 'audio') {
					echo  (($pf_media['content']));
				}

				echo (strlen($post_descr) ? $post_descr : '') . '<div class="clear post_clear"></div><div class="gt3_post_footer"><div class="gt3_module_button_list"><a href="'. esc_url(get_permalink()) .'">'. esc_html__('Read More', 'ewebot') .'</a></div>';
				?>
				<?php if ($show_share == "1" || $show_likes == "1" || true === $show_share || true === $show_likes) { ?>
					<div class="blog_post_info">
						<?php

							if (function_exists('gt3_blog_post_sharing')) {
								gt3_blog_post_sharing($show_share,$featured_image);
							}

							if (function_exists('gt3_blog_post_likes')) {
								gt3_blog_post_likes($show_likes,$all_likes);
							}
						?>
					</div>
				<?php } ?>
					<div class="clear"></div>
				</div>
			</div>
		</div>
	</div>
