<?php
if (!function_exists('gt3_sidebar_generator')) {
    function gt3_sidebar_generator(){
	    register_sidebar( array(
		    'name' => 'Default Sidebar',
		    'description' => esc_html__('Add the widgets appearance for Custom Sidebar. Drag the widget from the available list on the left, configure widgets options and click Save button. Select the sidebar on the posts or pages in just few clicks.', 'ewebot'),
		    'id' => esc_attr(strtolower(preg_replace('/[^A-Za-z0-9\-]/', '', str_replace(' ', '-', 'Default Sidebar')))),
		    'before_widget' => '<div id="%1$s" class="widget gt3_widget open %2$s">',
		    'after_widget' => '</div>',
		    'before_title' => '<h4 class="widget-title">',
		    'after_title' => '</h4>',
	    ));

        $sidebars = gt3_option('sidebars');
        if (!empty($sidebars)) {
            foreach($sidebars as $sidebar){
                register_sidebar( array(
                    'name' => esc_attr($sidebar),
                    'description' => esc_html__('Add the widgets appearance for Custom Sidebar. Drag the widget from the available list on the left, configure widgets options and click Save button. Select the sidebar on the posts or pages in just few clicks.', 'ewebot'),
                    'id' => "sidebar_".esc_attr(strtolower(preg_replace('/[^A-Za-z0-9\-]/', '', str_replace(' ', '-', $sidebar)))),
                    'before_widget' => '<div id="%1$s" class="widget gt3_widget open %2$s">',
                    'after_widget' => '</div>',
                    'before_title' => '<h4 class="widget-title">',
                    'after_title' => '</h4>',
                ));
            }
        }
    }
    add_action('widgets_init', 'gt3_sidebar_generator');
}
