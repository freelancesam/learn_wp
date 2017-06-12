
/*
 * Created on : Jun 23, 2016, 10:28:14 AM
 * Author: Tran Trong Thang
 * Email: trantrongthang1207@gmail.com
 * Skype: trantrongthang1207
 */
jQuery(document).ready(function ($) {
    $('body').on('added_to_cart', function (event, fragments, cart_hash, $button) {
        $.fancybox("#tvcart_popup", {
            minWidth: 280,
            padding: [15, 0, 15, 0],
            closeBtn: false,
            autoHeight: true,
            wrapCSS: 'tvwrapfancybox'
        })
        console.log(fragments.selectorName);
    });
    $(document).on('click', '.tv-continue-shopping', function () {
        $.fancybox.close()
    });
    $(document).on('click', '.single_add_to_cart_button', function (g) {
        g.preventDefault();
        var h = $(this);
        var i = $('form.cart');
        var j = i.find('input[name=product_id]').val();
        var k = i.find('input[name=quantity]').val();
        var l = {product_id: j, quantity: k};
        $.post(wc_add_to_cart_params.wc_ajax_url.toString().replace('%%endpoint%%', 'add_to_cart'), l, function (c) {
            if (!c) {
                return
            }
            var d = window.location.toString();
            d = d.replace('add-to-cart', 'added-to-cart');
            if (c.error && c.product_url) {
                window.location = c.product_url;
                return
            }
            if (wc_add_to_cart_params.cart_redirect_after_add === 'yes') {
                window.location = wc_add_to_cart_params.cart_url;
                return
            } else {
                var e = c.fragments;
                var f = c.cart_hash;
                if (e) {
                    $.each(e, function (a) {
                        $(a).addClass('updating')
                    })
                }
                $('.shop_table.cart, .updating, .cart_totals').fadeTo('400', '0.6').block({message: null, overlayCSS: {opacity: 0.6}});
                if (e) {
                    $.each(e, function (a, b) {
                        $(a).replaceWith(b)
                    })
                }
                $('.widget_shopping_cart, .updating').stop(true).css('opacity', '1').unblock();
                $(document.body).trigger('added_to_cart', [e, f])
            }
        })
    })
    if (typeof $('#shipping_option')[0] != 'undefined') {
        var $lenght = $('#shipping_option').find('option').length;
        if ($lenght > 1) {
            $('.shipping_option').show();
        }
    }
});

