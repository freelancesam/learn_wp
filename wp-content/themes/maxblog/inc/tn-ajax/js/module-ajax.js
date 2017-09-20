var modules_data;
jQuery(document).ready(function ($) {
    'use strict';

    //ajax next-prev
    $('.tn-ajax-prev').click(function (e) {
        e.preventDefault();
        var current_module;
        if ($(this).hasClass('ajax-disable')) return;
        current_module = tn_get_current_module(this.id.slice(5));
        current_module.current_page--;
        tn_ajax_module_request(current_module)
    });

    $('.tn-ajax-next').click(function (e) {
        e.preventDefault();
        var current_module;
        if ($(this).hasClass('ajax-disable')) return;
        current_module = tn_get_current_module($(this).attr('id').slice(5));
        current_module.current_page++;
        tn_ajax_module_request(current_module);
    });

    //ajax load more
    $('.tn-ajax-loadmore').click(function (e) {
        e.preventDefault();
        var current_module;
        if ($(this).hasClass('ajax-disable')) return;
        current_module = tn_get_current_module(this.id.slice(9));
        current_module.current_page++;
        tn_ajax_module_request(current_module, true);
    });

    //ajax search
    $('#ajax-form-search').click(function (e) {
        e.preventDefault();
        $(this).next().slideToggle(300);
    });
    var delay = (function () {
        var timer = 0;
        return function (callback, ms) {
            clearTimeout(timer);
            timer = setTimeout(callback, ms);
        };
    })();
    $('#search-form-text').keyup(function () {
        var value = $(this).val();
        delay(function () {
            if (value) {
                var search_class = $('#ajax-search-result');
                search_class.fadeIn(300).html('<div class="search-loading"></div>');
                var data = {
                    action: 'tn_ajax_search',
                    s: value
                };
                $.post(tn_ajax_url, data, function (response) {
                    response = $.parseJSON(response);
                    $('#ajax-search-result').empty().hide().css('height', 'auto').html(response.content).fadeIn(300).css('height', search_class.height());
                });
            } else  $('#ajax-search-result').fadeOut(300, function () {
                $(this).empty().css('height', 'auto');
            });

        }, 450);
    })
});

//get Current module
function tn_get_current_module(module_id) {
    var data;
    jQuery.each(modules_data, function (index, module) {
        if (index == module_id) {
            data = module;
        }
    });
    return data;
}

//ajax request
function tn_ajax_module_request(module, append) {
    append = (typeof append === "undefined") ? false : append;

    //load from cache
    var current_module_cache = JSON.stringify(module);
    if (tn_cache.exist(current_module_cache)) {
        tn_ajax_animation_start(module);
        tn_ajax_module_response(tn_cache.get(current_module_cache));
        return 'cache';
    }

    //animation start
    tn_ajax_animation_start(module, append);

    //if missing cache
    jQuery.ajax({
        type: 'POST',
        url: tn_ajax_url,
        cache: true,
        data: {
            action: 'tn_ajax_module',
            tn_query: module.module_query,
            tn_module_id: module.module_id,
            tn_module_name: module.module_name,
            tn_current_page: module.current_page,
            tn_options: module.options
        },
        success: function (data, textStatus, XMLHttpRequest) {
            tn_cache.set(current_module_cache, data);
            tn_ajax_module_response(data, append);
        },
        error: function (MLHttpRequest, textStatus, errorThrown) {
        }
    })
}

//ajax response
function tn_ajax_module_response(data, append) {
    var tn_data = jQuery.parseJSON(data);

    //load the content (in place or append)
    if (append === true) {
        jQuery('#' + tn_data.module_id).append(tn_data.data_response); //load more
    } else {
        jQuery('#' + tn_data.module_id).html(tn_data.data_response); //next prev
    }

    //hide or show prev
    if (tn_data.hide_prev === true) {
        jQuery('#prev_' + tn_data.module_id).addClass('ajax-disable');
    } else {
        jQuery('#prev_' + tn_data.module_id).removeClass('ajax-disable');
    }

    //hide or show next
    if (tn_data.hide_next === true) {
        jQuery('#next_' + tn_data.module_id).addClass('ajax-disable');
    } else {
        jQuery('#next_' + tn_data.module_id).removeClass('ajax-disable');
    }

    //hide load more
    if (tn_data.hide_next === true) {
        jQuery('#loadmore_' + tn_data.module_id).parent().css('display', 'none');
    }

    //loading effects
    tn_ajax_animation_end(tn_data, append);
}

//ajax animation start
function tn_ajax_animation_start(module, append) {
    var tn_module_inner = jQuery('#' + module.module_id);
    jQuery('.tn-loader').remove();
    tn_module_inner.addClass('module_inner_overflow');
    tn_module_inner.parent().addClass('tn-loader');
    if (append == true) {
        jQuery('#loadmore_' + module.module_id).html('Loading...');
    }
    tn_module_inner.stop();
    tn_module_inner.fadeTo('600', 0.1, 'easeInOutCubic');
    var module_height = tn_module_inner.height();
    tn_module_inner.css('height', module_height);
}

//ajax animation end
function tn_ajax_animation_end(tn_data, append) {
    jQuery(this).delay(100).queue(function () {
        jQuery('.tn-loader').removeClass('tn-loader');
        var tn_module_select = jQuery('#' + tn_data.module_id);
        tn_module_select.stop();
        tn_module_select.css('height', 'auto');
        tn_module_select.fadeTo(800, 1, function () {
            if (append == true) {
                jQuery('#loadmore_' + tn_data.module_id).html('Load more');
            }
            jQuery('.module_inner_overflow').removeClass('module_inner_overflow');
        });
        jQuery(document.body).trigger("sticky_kit:recalc");
        jQuery('.meta-thumb-element').click(function (e) {
            var thumb_wrap = jQuery(e.target).parents('.thumb-wrap');

            var share_social = thumb_wrap.find('.shares-to-social-thumb-wrap');
            if(share_social.length) {
                share_social.addClass('share-visible');
                thumb_wrap.mouseleave(function () {
                    share_social.removeClass('share-visible');
                });
            } else {
                var thumb_slider_wrap = jQuery(e.target).parents('.thumb-slider-wrap');
                var slider_social_share = thumb_slider_wrap.find('.shares-to-social-thumb-wrap');
                slider_social_share.addClass('share-visible');
                thumb_slider_wrap.mouseleave(function () {
                    slider_social_share.removeClass('share-visible');
                });
            }
            return false;
        });
        jQuery(this).dequeue();
    });
}

//cache ajax
var tn_cache = {
    data: {},
    get: function (id) {
        return tn_cache.data[id];
    },
    set: function (id, cache_data) {
        tn_cache.remove(id);
        tn_cache.data[id] = cache_data;
    },
    remove: function (id) {
        delete tn_cache.data[id];
    },
    exist: function (id) {
        return tn_cache.data.hasOwnProperty(id) && tn_cache.data[id] !== null;
    }
};