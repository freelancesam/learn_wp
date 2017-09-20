
/*
 * Created on : May 4, 2017, 9:32:48 AM
 * Author: Tran Trong Thang
 * Email: trantrongthang1207@gmail.com
 * Skype: trantrongthang1207
 */
jQuery(document).ready(function ($) {

    $('.owl-carousel').carouFredSel({
        interval: 500,
        auto: true,
        circular: true,
        infinite: true,
        mousewheel: true,
        prev: '#logoprev',
        next: '#logonext',
        pagination: "#pager2",
//        height: 195,
        swipe: {
            onMouse: true,
            onTouch: true
        },
        responsive: true,
        width: '100%',
        scroll: {
            'easing': 'swing',
            'duration': 800,
        },
        items: {
            height: 300,
            width: 293,
            visible: {
                min: 1,
                max: 4
            }
        },
        responsives: {
            0: {
                items: 1
            },
            678: {
                items: 2
            },
            1024: {
                items: 3
            },
            1100: {
                items: 4
            }
        }
    });
    /*
     $('.owl-carousel').bxSlider({
     auto: true,
     speed: 200,
     minSlides: 1,
     maxSlides: 4,
     slideWidth: 292,
     slideMargin: 0,
     infiniteLoop: true,
     hideControlOnEnd: true
     });
     */
//    $('.owl-carousel').owlCarousel({
//        items: 4,
//        margin: 0,
//        loop: true,
//        autoplay: true,
//        autoplayTimeout: 2000,
//        animateOut: 'slideOutDown',
//        animateIn: 'flipInX',
//        responsive: {
//            0: {
//                items: 1
//            },
//            678: {
//                items: 2
//            },
//            1024: {
//                items: 3
//            },
//            1100: {
//                items: 4
//            }
//        }
//    });
})