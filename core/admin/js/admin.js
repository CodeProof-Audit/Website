(function($) {
"use strict";
// MegaMenu
jQuery(document).ready(function() {
    var navigationForm = jQuery('#update-nav-menu');
    navigationForm.on('change', '[data-item-option]', function() {
        if (jQuery(this).attr('type') == 'checkbox') {
            jQuery(this).parent().find('input[type=hidden]').val(jQuery(this).parent().find('input[type=checkbox]').is(":checked"));
            if (jQuery(this).hasClass('mega-menu-checkbox')) {
                if (jQuery(this).parent().find('input[type=checkbox]').is(":checked")) {
                    jQuery(this).parents('.menu-item ').addClass('menu-item-megamenu-active');
                    var $item = jQuery(this).parents('.menu-item ');
                    do{
                        $item = $item.next();
                        if (!$item.hasClass('menu-item-depth-0')) {
                            $item.addClass('menu-item-megamenu_sub-active');
                        }
                    } while(!$item.hasClass('menu-item-depth-0') && $item.next().length != 0)
                }else{
                    jQuery(this).parents('.menu-item ').removeClass('menu-item-megamenu-active');
                    var $item = jQuery(this).parents('.menu-item ');
                    do{
                        $item = $item.next();
                        if (!$item.hasClass('menu-item-depth-0')) {
                            $item.removeClass('menu-item-megamenu_sub-active');
                        }
                    } while(!$item.hasClass('menu-item-depth-0') && $item.next().length != 0)
                }
            }
        }
        if (jQuery(this)[0].tagName == 'SELECT') {
            jQuery(this).parent().find('input[type=hidden]').val(jQuery(this)[0].value);
        }
    });
});
})(jQuery);
