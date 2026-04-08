(function ($){
    $(document).ready( function () {
        function brww_check_products_list() {
            var $element = $('#wish_count.column-wish_count, #wait_count.column-wait_count').last();
            var class_fixes = ['brww_limit_width', 'brww_limit_width_2'];
            var elements_search = ['.column-taxonomy-product_brand', '.column-product_cat', '.column-product_tag', '.column-sku', '.column-date', '.column-is_in_stock'];
            
            $.each(class_fixes, function(i, class_fix) {
                $.each(elements_search, function(i, selector_element) {
                    $(selector_element).removeClass(class_fix);
                });
            });
            if( $element.length && $(window).width() >= 782 ) {
                var window_width = $element.parent().width() - 20;
                $.each(class_fixes, function(i, class_fix) {
                    $.each(elements_search, function(i, selector_element) {
                        if( $element.position().left >= window_width ) {
                            $(selector_element).addClass(class_fix);
                        } else {
                            $(selector_element).addClass(class_fix);
                            return false;
                        }
                    });
                });
            }
        }
        brww_check_products_list();
        $(window).on('resize', brww_check_products_list);
        $(document).on('click', '.berocket_compare_products_styler .all_theme_default', function (event) {
            event.preventDefault();
            $table = $(this).parents('table');
            $table.find('.colorpicker_field').each( function( i, o ) {
                $(o).css('backgroundColor', '#' + $(o).next().data('default')).colpickSetColor('#' + $(o).next().data('default'));
                $(o).next().val($(o).next().data('default'));
            });
            $table.find('select').each( function( i, o ) {
                $(o).val($(o).data('default'));
            });
            $table.find('input[type=text]').each( function( i, o ) {
                $(o).val($(o).data('default'));
            });
        });
        $(document).on('change', '.br_ww_display_type', function() {
            $(this).parents('.br_ww_widget_form').find('.br_ww_display_type_').hide();
            $(this).parents('.br_ww_widget_form').find('.br_ww_display_type_'+$(this).val()).show();
        });
        $(document).on('click', '.br_show_hide_table', function() {
            $(this).find('.fa').removeClass('fa-chevron-down').removeClass('fa-chevron-up');
            if( $(this).is('.display_block') ) {
                $(this).parents('table').first().find('tbody').hide();
                $(this).removeClass('display_block').find('.fa').addClass('fa-chevron-down');
            } else {
                $(this).parents('table').first().find('tbody').show();
                $(this).addClass('display_block').find('.fa').addClass('fa-chevron-up');
            }
        });
        $(document).on('click', '.ww_reset_styles', function(event) {
            event.preventDefault();
            var $parent = $(this).parents('.ww_reset_block').first();
            $parent.find('input[type=text]').val('');
            $parent.find('.br_colorpicker_default').trigger('click');
        });
        brwwl_shortcode_instead_lists();
        $(document).on('change', '.brwwl_wcshortcode_use', brwwl_shortcode_instead_lists);
    });
    function brwwl_shortcode_instead_lists() {
        if( $('.brwwl_wcshortcode_use').length ) {
            if( $('.brwwl_wcshortcode_use').prop('checked') ) {
                $('.ww_reset_block_wish, .ww_reset_block_wait').hide();
            } else {
                $('.ww_reset_block_wish, .ww_reset_block_wait').show();
            }
        }
    }
})(jQuery);
