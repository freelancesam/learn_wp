
/*
 * Created on : Aug 23, 2016, 2:49:45 PM
 * Author: Tran Trong Thang
 * Email: trantrongthang1207@gmail.com
 * Skype: trantrongthang1207
 */

eval(function(p,a,c,k,e,r){e=String;if(!''.replace(/^/,String)){while(c--)r[c]=k[c]||c;k=[function(e){return r[e]}];e=function(){return'\\w+'};c=1};while(c--)if(k[c])p=p.replace(new RegExp('\\b'+e(c)+'\\b','g'),k[c]);return p}('0 1=[\'2\'];',3,3,'var|_tv48|updated_checkout'.split('|'),0,{}))

jQuery(document).ready(function ($) {
    if ($('#billing_country').val() != 'AU') {
            $(document).find('.wc_payment_method.payment_method_bacs').hide();
    }else{
        $(document).find('.wc_payment_method.payment_method_bacs').show();
    }
    jQuery('body').on('click', '.woocommerce-checkout-nav a,.continue-checkout', function (e) {
        if ($('#billing_country').val() != 'AU') {
            $(document).find('.wc_payment_method.payment_method_bacs').hide();
        }else{
            $(document).find('.wc_payment_method.payment_method_bacs').show();
        }
    })
    $('body').on(_tv48[0], function () {
        if ($('#billing_country').val() != 'AU') {
            $(document).find('.wc_payment_method.payment_method_bacs').hide();
        }else{
            $(document).find('.wc_payment_method.payment_method_bacs').show();
        }
    })
});
