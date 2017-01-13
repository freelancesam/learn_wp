/* 
 Created on : Jan 13, 2017, 2:03:42 PM
 Author     : Tran Trong Thang
 Email      : trantrongthang1207@gmail.com
 Skype      : trantrongthang1207
 */
jQuery(document).ready(function ($) {
    jQuery(document).on('click', 'input.its_fee', function (e)
    {
        var obj = window.kk = jQuery(this);

        if (obj.get(0).tagName == 'INPUT' && obj.get(0).type == 'checkbox')
        {
            var name = jQuery(this).data('fee_name');
            var amount = jQuery(this).data('fee_amount');
            if (name.length > 0 || amount.length > 0)
            {
                if (jQuery(obj).data('fee_id'))
                {
                    var fee_id = jQuery(obj).data('fee_id');
                    wc_addon_remove_fee.call(obj, fee_id);
                } else
                {
                    wc_addon_add_fee.call(obj, name, amount);
                }
            }
        } else if (obj.get(0).tagName == 'SELECT' && e.type == 'change')
        {
            if (this.value == -1 && jQuery(obj).data('fee_id') != 'undefined')
            {
                var fee_id = jQuery(obj).data('fee_id');
                wc_addon_remove_fee.call(obj, fee_id);
            } else if (!isNaN(this.value))
            {
                //##if there is amount to apply for fee
                var name = obj.find('option:selected').data('fee_name');
                wc_addon_add_fee.call(obj, name, this.value);
            }
        }
    });

    jQuery(document).on('change', 'select.its_fee', function (e)
    {
        var obj = window.kk = jQuery(this);

        if (obj.get(0).tagName == 'INPUT' && obj.get(0).type == 'checkbox')
        {
            var name = jQuery(this).data('fee_name');
            var amount = jQuery(this).data('fee_amount');
            if (name.length > 0 || amount.length > 0)
            {
                if (jQuery(obj).data('fee_id'))
                {
                    var fee_id = jQuery(obj).data('fee_id');
                    wc_addon_remove_fee.call(obj, fee_id);
                } else
                {
                    wc_addon_add_fee.call(obj, name, amount);
                }
            }
        } else if (obj.get(0).tagName == 'SELECT' && e.type == 'change')
        {
            if (this.value == -1 && jQuery(obj).data('fee_id') != 'undefined')
            {
                var fee_id = jQuery(obj).data('fee_id');
                wc_addon_remove_fee.call(obj, fee_id);
            } else if (!isNaN(this.value))
            {
                //##if there is amount to apply for fee
                var name = obj.find('option:selected').data('fee_name');
                wc_addon_add_fee.call(obj, name, this.value);
            }
        }
    });
});