<?php
get_header();

$layout = gt3_option('page_sidebar_layout');
$sidebar = gt3_option('page_sidebar_def');
$column = 12;

if ( ($layout == 'left' || $layout == 'right') && is_active_sidebar( $sidebar )  ) {
    $column = 9;
}else{
    $sidebar = '';
}
if ($sidebar == '') {
    $layout = 'none';
}
$row_class = ' sidebar_'.esc_attr($layout);

gt3_theme_style('search', get_template_directory_uri() . '/dist/css/search.css');


global $wp_query, $my_wp_query;

//var_dump($wp_query);

?>
    <div class="container">
        <div class="row<?php echo esc_attr($row_class); ?>">
            <div class="content-container span<?php echo (int)$column; ?>">
                <section id='main_content'>
                    <?php

                    global $paged, $offset, $posts_per_page;

                    $offset = 0;
                    $posts_per_page = 10;
                    $foundSomething = false;

//                    $defaults = array('posts_per_page' => 10, 'offset' => 0, 'post_type' => 'any', 'post_status' => 'publish', 'post_password' => '', 's' => get_search_query(), 'paged' => $paged);
//                    $my_wp_query = new \WP_Query($defaults);
                    $posts = $wp_query->posts;
                    if (count($posts)) {
	                    $foundSomething = true;
                    }

                    /**/
                    $blog = new \ElementorModal\Widgets\GT3_Core_Elementor_Widget_Blog(
	                    array(
		                    'id' => 'gt3_search'
	                    ),
	                    array(
		                    'settings' => array()
	                    )
                    );
                    $category_archive_layout = gt3_option('search_layout');
                    if (is_null($category_archive_layout)) {
	                    $category_archive_layout = 1;
                    }
                    $blog->set_settings(
	                    array(
		                    'items_per_line'             => apply_filters('gt3/search/columns', $category_archive_layout),
		                    'query' => $wp_query,
		                    'meta_author'         => 'yes',
		                    'meta_comments'       => 'yes',
		                    'meta_categories'     => 'yes',
		                    'meta_date'           => 'yes',
		                    'meta_position'       => 'before_title',
		                    'post_btn_link'       => 'yes',
		                    'symbol_count_descrt' => array(
			                    'size' => '280',
			                    'unit' => 'px',
		                    ),
	                    )
                    );
                    $blog->print_element();
                    /**/

					 if ($foundSomething == false) {
                        ?>
                        <div class="wrapper_404 pp_block">
                            <div class="container_vertical_wrapper">
                                <div class="container a-center pp_container">
                                    <h1><?php echo esc_html__('Oops!', 'ewebot'); ?> <?php echo esc_html__('Not Found!', 'ewebot'); ?></h1>
                                    <h2><?php echo esc_html__('Apologies, but we were unable to find what you were looking for.', 'ewebot'); ?></h2>
                                    <div class="search_result_form text-center">
                                        <?php get_search_form(true); ?>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <?php
                    }

                    echo gt3_get_theme_pagination();

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

<?php get_footer(); ?>
