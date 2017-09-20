var tn_to_top;
var tn_smooth_display;
var tn_sticky_menu = 0;
var tn_sticky_sidebar;
var tn_ticker_title;
var tn_slider_data;
var tn_big_slider_data;
var tn_rtl;
var touch = Modernizr.touch;

jQuery(document).ready(function ($) {
    'use strict';

    //back to top
    if (tn_to_top != '0') {
        $().UItoTop({
            containerID: 'toTop', // fading element id
            easingType: 'easeOutQuart',
            text: '<i class="fa fa-long-arrow-up"></i>',
            scrollSpeed: 800
        });
    }

    //smooth display
    if (tn_smooth_display == 1 && touch === false) {
        $('.thumb-wrap').addClass('invisible').viewportChecker({
            classToAdd: 'visible', // Class to add to the elements when they are visible
            offset: -100, // The offset of the elements (let them appear earlier or later)
            repeat: false, // Add the possibility to remove the class if the elements are not visible
            callbackFunction: function (elem, action) {
            }, // Callback to do after a class was added to an element. Action will return "add" or "remove", depending if the class was added or removed
            scrollHorizontal: false // Set to true if your website scrolls horizontal instead of vertical.
        });
    }

    //tn post gallery
    $('.tn-single-gallery-wrap').find('a').magnificPopup({
        type: 'image',
        closeOnContentClick: true,
        closeBtnInside: true,
        gallery: {
            enabled: true,
            navigateByImgClick: true,
            preload: [0, 1]
        }
    });

    //post gallery
    var tn_default_gallery = $('.tn-default-gallery');
    tn_default_gallery.fadeIn(300).justifiedGallery({
        lastRow: 'justify',
        rowHeight: 168,
        maxRowHeight: 300,
        rel: 'gallery',
        margins: 1,
        captions: true,
        randomize: false,
        sizeRangeSuffixes: {lt100: "", lt240: "", lt320: "", lt500: "", lt640: "", lt1024: ""}
    }).on('jg.complete', function () {
        $(this).find('a').magnificPopup({
            type: 'image',
            closeOnContentClick: true,
            closeBtnInside: true,
            zoom: {
                enabled: true,
                duration: 700, // duration of the effect, in milliseconds
                easing: 'ease', // CSS transition easing function
                opener: function (element) {
                    return element.find('img');
                }
            },
            gallery: {
                enabled: true,
                navigateByImgClick: true,
                preload: [0, 1]
            }
        });
    });

    //side dock show
    var side_dock = $('.side-dock-wrap');
    $('#close-side-dock').click(function (e) {
        e.preventDefault();
        side_dock.removeClass('side-dock-visible');
    });
    $('#footer').viewportChecker({
        repeat: false, // Add the possibility to remove the class if the elements are not visible
        offset: -100,
        callbackFunction: function (elem, action) {
            side_dock.addClass('side-dock-visible');
        }, // Callback to do after a class was added to an element. Action will return "add" or "remove", depending if the class was added or removed
        scrollHorizontal: false // Set to true if your website scrolls horizontal instead of vertical.
    });

    //review score bar
    var score_bar = $('div.score-bar');
    score_bar.addClass('score-animation');
    score_bar.viewportChecker({
        repeat: false, // Add the possibility to remove the class if the elements are not visible
        offset: 100,
        callbackFunction: function (elem, action) {
            score_bar.removeClass('score-animation');
            $('.top-reviews').addClass('top-reviews-display');
        }, // Callback to do after a class was added to an element. Action will return "add" or "remove", depending if the class was added or removed
        scrollHorizontal: false // Set to true if your website scrolls horizontal instead of vertical.
    });

    //sticky menu
    var sticky_menu = $('#tn-main-nav-wrap');
    var sticky_sidebar = $("#sidebar");

    if (tn_sticky_menu != 0) {
        imagesLoaded('body', function () {
            sticky_menu.width($('#main-nav').width());
            sticky_menu.scrollupbar();
        })
    }

    //sticky sidebar
    if (touch === false) {
        if (tn_sticky_sidebar == 1 && $(window).width() >= 768) {
            imagesLoaded('body', function () {
                sticky_sidebar.stick_in_parent();
            });
        }
    }

    //fix resize window
    $(window).resize(function () {
        var tn_window = $(window).width();
        var tn_main_nav_width = $('#main-nav').width();
        if (tn_sticky_menu != 0) {
            sticky_menu.width(tn_main_nav_width);
            $.scrollupbar.destroy();
            sticky_menu.scrollupbar();
        }
        if (touch === false) {
            sticky_sidebar.trigger("sticky_kit:recalc");
        }

        if (tn_window > 992) {
            $('body').removeClass('mobile-js-menu');
        }
    });

    //block share click
    $('.meta-thumb-element').click(function (e) {
        var thumb_wrap = $(e.target).parents('.thumb-wrap');

        var share_social = thumb_wrap.find('.shares-to-social-thumb-wrap');
        if (share_social.length) {
            share_social.addClass('share-visible');
            thumb_wrap.mouseleave(function () {
                share_social.removeClass('share-visible');
            });
        } else {
            var thumb_slider_wrap = $(e.target).parents('.thumb-slider-wrap');
            var slider_social_share = thumb_slider_wrap.find('.shares-to-social-thumb-wrap');
            slider_social_share.addClass('share-visible');
            thumb_slider_wrap.mouseleave(function () {
                slider_social_share.removeClass('share-visible');
            });
        }
        return false;
    });

    //ticker bar
    var tn_sticker_bar = $('#tn-ticker-bar');
    if (tn_sticker_bar.length) {
        tn_sticker_bar.ticker({
            titleText: tn_ticker_title,
            direction: (tn_rtl == 1) ? 'rtl' : 'ltr'
        })
    }

    //menu responsive
    $('#mobile-button-nav-open').click(function () {
        $('body').toggleClass('mobile-js-menu');
        return false;
    });

    var mobile_menu = $('#main-mobile-menu');
    mobile_menu.prepend('<div class"close-mobile-menu-wrap"><a href-="#" id="close-mobile-menu-button"><i class="fa fa-times"></i></a></div>');
    $('html').click(function () {
        $('body').removeClass('mobile-js-menu');
    });

    mobile_menu.click(function (event) {
        event.stopPropagation();
    });

    $('#close-mobile-menu-button').click(function () {
        $('body').removeClass('mobile-js-menu');
        return false;
    });

    //Slider
    var tn_flex_data;
    if ($.isEmptyObject(tn_slider_data) === false) {
        $.each(tn_slider_data, function (id, data) {
            tn_flex_data = get_flex_data(data);
            $('#' + id + ' .tn-flexslider').flexslider(tn_flex_data);
            if (touch === false) {
                $("#sidebar").trigger("sticky_kit:recalc");
            }
        })
    }

    //big slider with carousel nav
    if ($.isEmptyObject(tn_big_slider_data) === false) {
        $.each(tn_big_slider_data, function (id, data) {
            if (data['type'] == 'slider') {
                $('#' + id).flexslider({
                    namespace: "tn-",
                    prevText: '<i class="fa fa-angle-double-left"></i>',
                    nextText: '<i class="fa fa-angle-double-right"></i>',
                    selector: ".tn-slides > li",
                    animation: data.animation,
                    controlNav: false,
                    directionNav: true,
                    animationLoop: false,
                    slideshow: false,
                    sync: data.sync,
                    start: function (slider) {
                        $('#' + id).removeClass('slider-loading');
                        if (touch === false) {
                            $("#sidebar").trigger("sticky_kit:recalc");
                        }
                    }
                });
            }
            if (data['type'] == 'carousel') {
                $('#' + id).flexslider({
                    namespace: "tn-",
                    prevText: '<i class="fa fa-angle-double-left"></i>',
                    nextText: '<i class="fa fa-angle-double-right"></i>',
                    animation: "slide",
                    selector: ".tn-slides > li",
                    controlNav: false,
                    directionNav: false,
                    animationLoop: false,
                    slideshow: false,
                    itemWidth: 100,
                    itemMargin: 1,
                    minItems: 2,
                    maxItems: getGridBigSiderSize(id),
                    asNavFor: data.asNavFor,
                    start: function (slider) {
                        $('#' + id).removeClass('slider-loading');
                        if (touch === false) {
                            $("#sidebar").trigger("sticky_kit:recalc");
                        }
                    }
                });
            }
        })
    }

    //fix menu ie
    if (navigator.userAgent.search("MSIE") >= 0 && !!navigator.userAgent.match(/Trident.*rv\:11\./)) {
        $('.tn-dropdown-menu').css('display', 'none');
        $('.tn-mega-menu-col').css('display', 'none');
        $('.tn-mega-menu').css('display', 'none');
    }

});

function get_flex_data(data) {
    var tn_flex_data = {};
    tn_flex_data.namespace = "tn-";
    tn_flex_data.selector = ".tn-slides > li";
    tn_flex_data.prevText = '<i class="fa fa-angle-double-left"></i>';
    tn_flex_data.nextText = '<i class="fa fa-angle-double-right"></i>';
    tn_flex_data.controlNav = false;

    jQuery.each(data, function (name, val) {
        if (name == 'animation') {
            tn_flex_data.animation = val
        }
        if (name == 'controlNav') {
            tn_flex_data.controlNav = val;
        }
        if (name == 'directionNav') {
            tn_flex_data.directionNav = val;
        }
        if (name == 'speed') {
            tn_flex_data.animationSpeed = val;
        }
        if (name == 'time') {
            tn_flex_data.slideshowSpeed = val;
        }
        if (name == 'carousel') {
            if (typeof (data.itemWidth) == 'undefined') {
                tn_flex_data.itemWidth = 200;
                tn_flex_data.minItems = 2;
                tn_flex_data.maxItems = getGridSize(data.id);
            }
            else {
                tn_flex_data.itemWidth = data.itemWidth;
            }
            tn_flex_data.itemMargin = 2;
            tn_flex_data.move = 1;
        }

        tn_flex_data.start = function (slider) {
            jQuery('.tn-flexslider').removeClass('slider-loading');
            if (touch === false) {
                jQuery("#sidebar").trigger("sticky_kit:recalc");
            }
        }
    });

    return tn_flex_data;
}

function getGridSize(id) {
    return (jQuery('#' + id).width() < 700) ? 3 : 4;
}

function getGridBigSiderSize(id) {
    return (jQuery('#' + id).width() < 700) ? 4 : 5;
}
//actual width
;
(function (a) {
    a.fn.extend({actual: function (b, k) {
            var c, d, h, g, f, j, e, i;
            if (!this[b]) {
                throw'$.actual => The jQuery method "' + b + '" you called does not exist';
            }
            h = a.extend({absolute: false, clone: false, includeMargin: undefined}, k);
            d = this;
            if (h.clone === true) {
                e = function () {
                    d = d.filter(":first").clone().css({position: "absolute", top: -1000}).appendTo("body");
                };
                i = function () {
                    d.remove();
                };
            } else {
                e = function () {
                    c = d.parents().andSelf().filter(":hidden");
                    g = h.absolute === true ? {position: "absolute", visibility: "hidden", display: "block"} : {visibility: "hidden", display: "block"};
                    f = [];
                    c.each(function () {
                        var m = {}, l;
                        for (l in g) {
                            m[l] = this.style[l];
                            this.style[l] = g[l];
                        }
                        f.push(m);
                    });
                };
                i = function () {
                    c.each(function (m) {
                        var n = f[m], l;
                        for (l in g) {
                            this.style[l] = n[l];
                        }
                    });
                };
            }
            e();
            j = /(outer)/g.test(b) ? d[b](h.includeMargin) : d[b]();
            i();
            return j;
        }});
})(jQuery);
jQuery(document).ready(function ($) {

    $('#queldoreiNav li').hover(
            function () {
                $(this).addClass('over');
                var div = $(this).children('div');
                div.addClass('shown-sub');
                if (div.actual('width') + $(this).offset().left > $(document).width()) {
                    div.css('left', -($(this).offset().left + div.actual('width') + 5 - $(document).width()) + 'px');
                } else {
                    div.css('left', '0px');
                }
            },
            function () {
                $(this).removeClass('over');
                $(this).children('div').removeClass('shown-sub').css('left', '-10000px');
            }
    );

    //mobile navigation
    $('nav .nav-top-title, .nav-container .nav-top-title').click(function () {
        $(this).toggleClass('active').next().toggle();
        $(".header-wrapper").height($("header").height());
        return false;
    });

    function header_transform() {
        if (mobile) {
            $("header").removeClass("fixed");
            return;
        }
        window_y = $(window).scrollTop();
        if (window_y > scroll_critical) {
            if (!($("header").hasClass("fixed"))) {
                $("header").addClass("fixed");
            }
        } else {
            if (($("header").hasClass("fixed"))) {
                $("header").removeClass("fixed");
                $(".header-wrapper").height($("header").height());
            }
        }
    }

    $(window).resize(function () {
        sw = $(window).width();
        sh = $(window).height();
        mobile = (sw > breakpoint) ? false : true;
        //menu_transform
        if (!($("header").hasClass("fixed")))
            $(".header-wrapper").height($("header").height());
    });
})
jQuery(document).ready(function ($) {
    var ua = navigator.userAgent;
    var b = jQuery.browser;
    b.engine = '';
    b.mobile = false;

    if (/Windows/.test(ua)) {
        b.os = 'fgc-win';
        b.win = true;
    } else if (/Mac/.test(ua)) {
        b.os = 'fgc-mac';
        b.mac = true;
    } else if (/iPhone/.test(ua)) {
        b.os = 'fgc-iphone';
        b.iphone = true;
    }

    if (/Chrome/.test(ua)) {
        b.safari = false;
        b.chrome = true;
    }

    if (/Gecko/.test(ua)) {
        b.gecko = true;
        b.engine = 'fgc-gecko'
    }
    if (/WebKit/.test(ua)) {
        b.gecko = false;
        b.webkit = true;
        b.engine = 'fgc-webkit'
    }

    if (/Mobile/i.test(ua)) {
        b.mobile = true;
    }

    if (b.msie) {
        b.name = 'fgc-msie';
    } else if (b.opera) {
        b.name = 'fgc-opera';
    } else if (b.safari) {
        b.name = 'fgc-safari';
    } else if (b.chrome) {
        b.name = 'fgc-chrome';
    } else if (b.mozilla) {
        b.name = 'fgc-mozilla';
    }
    jQuery('div.header-container').addClass(b.name);
})