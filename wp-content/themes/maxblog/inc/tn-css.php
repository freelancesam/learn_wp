<?php
add_action('wp_head', 'tn_custom_css');
if (!function_exists('tn_custom_css')) {
    function tn_custom_css()
    {
        //font
        $tn_body_font = tn_get_theme_option('tn_body_font');
        $tn_title_font = tn_get_theme_option('tn_title_font');
        $tn_small_title_font = tn_get_theme_option('tn_small_title_font');
        $tn_meta_font = tn_get_theme_option('tn_meta_font');
        $tn_menu_font = tn_get_theme_option('tn_menu_font');
        $tn_header_title_font = tn_get_theme_option('tn_header_title_font');

        //sidebar position
        $tn_sidebar_position = tn_get_theme_option('tn_sidebar_position','right');
        $tn_site_layout = tn_get_theme_option('tn_site_layout','tn-layout-full');
        $tn_header_style = tn_get_theme_option('tn_header_style','left');
        $tn_header_font =  tn_get_theme_option('tn_header_font',array());
        $tn_custom_css = tn_get_theme_option('tn_custom_css');
        $tn_global_color = tn_get_theme_option('tn_global_color');
        $tn_rtl = tn_get_theme_option('tn_rtl', 0);



        //check single sidebar
        if (is_single()) {
            $sidebar_position = get_post_meta(get_the_ID(), 'tn_post_sidebar_position', true);
            if (!empty($sidebar_position)) $tn_sidebar_position = $sidebar_position;
        }

        ?>
        <style type='text/css' media="all">

            /* body font */
            <?php if (!empty($tn_body_font)) : ?>
            body  {
            <?php
            foreach ($tn_body_font as $k=> $val){
            if(!empty ($val) && $k != 'google') echo $k.':'.$val.';';
            }
            ?> }
            <?php endif; ?>

            /* title font */
            <?php if (!empty($tn_title_font)) : ?>
            .block-title,.single-style1-title,.single-style2-title,.author-title,.search-submit,.single-nav-title-wrap,
            .review-widget-post-title,.review-widget-score,.single-review-element,.single-review-summary h3,.block-big-slider-title,
            .big-carousel-inner,.logo-404,.single-aside-social-wrap .share-title,.social-count-wrap .num-count,.twitter-widget-title h3,
            .block-feature2-slider-title,#main-content .widget .module5-wrap .col-sm-4 .block4-wrap .block-title, #main-content .single-related-wrap .col-sm-4 .block4-wrap .block-title,
            .page-title-wrap,.title-logo  {
                <?php
                foreach ($tn_title_font as $k=> $val){
                if(!empty ($val) && $k != 'google') echo $k.':'.$val.';';
                }
                ?> }
            <?php endif; ?>

            /* small title font */
            <?php if (!empty($tn_small_title_font)) : ?>
            .block6-wrap .block-title, .block11-wrap .block-title, .block8-wrap .block-title, .single-tags-source-wrap,
            .widget_categories ul, .widget_pages ul, .single-social-wrap, .widget_nav_menu ul, .widget_archive ul,
            .block9-wrap .block-title, .module-ticker-wrap .block-title, .big-slider-carousel-title, #menu-main .block-title,
            #main-content .widget .col-sm-4 .block4-wrap .block-title, .page-numbers, .block11-wrap .review-score,
            .block11-score-separation {
            <?php
            foreach ($tn_small_title_font as $k=> $val){
            if(!empty ($val) && $k != 'google') echo $k.':'.$val.';';
            };
            ?> }
            <?php endif; ?>

            /* meta tags font */
            <?php if (!empty($tn_meta_font)) : ?>
            .post-meta, .sub-cate-wrap, .breadcrumbs-bar-wrap, .author-widget-content, .post-categories,
            .rememberme, .register-links, .meta-thumb-wrap, .review-score {
            <?php
            foreach ($tn_meta_font as $k=> $val){
            if(!empty ($val) && $k != 'google') echo $k.':'.$val.';';
            };
            ?> }
            <?php endif; ?>

            /* menu font */
            <?php if (!empty($tn_menu_font)) : ?>
            #menu-main > ul > li > a, .tn-sub-menu-wrap, .menu-nav-top, #main-mobile-menu,
            .module-ticker-wrap .block-title {
            <?php
            foreach ($tn_menu_font as $k=> $val){
            if(!empty ($val) && $k != 'google') echo $k.':'.$val.';';
            };
            ?> }
            <?php endif; ?>

            /* header title font */
            <?php if (!empty($tn_header_title_font)) : ?>
            .widget-title h3, .cate-title, .search-page-title, .archive-page-title,
            .side-dock-title h3, .comment-title h3 {
            <?php
            foreach ($tn_header_title_font as $k=> $val){
            if(!empty ($val) && $k != 'google') echo $k.':'.$val.';';
            };
            ?> }
            <?php endif; ?>

            /*header style */
            <?php  if($tn_header_style == 'centered'): ?>
            .header-ads-wrapper {
                display: none;
            }

            ;
            <?php endif; ?>

            /* sidebar position */
            <?php if($tn_sidebar_position == 'left') :
             if (!empty($tn_rtl)) : ?>
            #main-content {
                float: left;
                border-right: 1px solid #e2e2e2;
                border-left: 0;
            }
            <?php else : ?>
            #main-content {
                float: right;
                border-right: 0;
                border-left: 1px solid #e2e2e2;
            }

            <?php endif;
            endif; ?>

            <?php if(!empty($tn_global_color)) : ?>
            /* color text */
            .tn-mega-menu-col > .tn-sub-menu-wrap > ul > li > ul > li > a:hover, .tn-sub-menu li a:hover, .cat-item a:before, .widget_pages .page_item a:before, .widget_meta li:before, .widget_archive li a:before,
            .widget_nav_menu .menu-main-nav-container > ul > li > a:before, .widget_rss ul li a, .about-widget-name span, .title-logo::first-letter,
            .block11-wrap:before, .logo-404 h1, .post-content-wrap a, .post-content-wrap a:hover, .post-content-wrap a:focus, .comment-form .logged-in-as a, .prev-article, .next-article,
             #close-side-dock:hover, .single-review-score, .post-categories li:hover a, .post-categories li:focus a, #recentcomments a, #mobile-button-nav-open:hover, #mobile-button-nav-open:focus,
            #main-mobile-menu .current-menu-item a, #main-mobile-menu li a:hover, .block11-score-separation, .block11-wrap .review-score, .single-review-as {
                color: <?php echo $tn_global_color; ?>;
            }

            /* selector */
            ::selection {
                background: <?php echo $tn_global_color; ?>;
                color: #fff;
            }

            ::-moz-selection {
                background: <?php echo $tn_global_color; ?>;
                color: #fff;
            }

            /* background */
            #menu-main > ul > li.current-menu-item > a, #menu-main > ul > li > a:hover, .tn-mega-menu-col > .tn-sub-menu-wrap > ul > li > a,
            .ajax-search-icon:hover, .menu-nav-top li a:hover, .block-big-slider-cate-tag li, .review-score, .drop-caps,
            #comment-submit, .form-submit #submit, .score-bar, .top-score-bar, #toTop i, .no-thumb, .widget-title h3:before,
            .tn-ajax-loadmore:hover, .tn-ajax-loadmore:focus, .page-numbers.current, .page-numbers:hover, .page-numbers:focus {
                background: <?php echo $tn_global_color; ?>;
            }

            /* border */
            .post-content-wrap blockquote, pre, .cate-title, .search-page-title, .archive-page-title,
            .author-title {
                border-color: <?php echo $tn_global_color; ?>;
            }

            .tn-mega-menu, .tn-navbar, .tn-mega-menu-col, .tn-dropdown-menu, #menu-main ul li .tn-dropdown-menu ul li ul.tn-sub-menu, #menu-main ul li div.tn-dropdown-menu ul li ul.tn-sub-menu {
                border-top-color: <?php echo $tn_global_color; ?>
            }

            <?php endif; ?>

            /* layout */
            <?php if($tn_site_layout =='tn-layout-boxed1' || $tn_site_layout == 'tn-layout-boxed') : ?>
            .tn-main-container {
                max-width: 1090px;
                margin: 30px auto;
            }

            #main-mobile-menu {
                margin-top: 30px;
            }

            #full-top .module-slider-widget, #full-bottom .module-slider-widget {
                margin-left: -15px;
                margin-right: -15px;
            }

            #full-top .module-feature-wrap, #full-bottom .module-feature-wrap {
                margin-left: -15px;
                margin-right: -16px;
            }

            #main-nav {
                margin-left: 0;
                margin-right: 0;
            }

            .main-nav-inner {
                margin-left: 0;
                margin-right: 0;
            }

            .module-ticker-inner {
                left: 10px;
                right: 10px;
            }

            <?php endif; ?>

            <?php if ($tn_site_layout == 'tn-layout-boxed') : ?>
            .tn-main-container {
                margin: 0 auto !important;
            }

            <?php endif; ?>

            /*right title line height fix */
            <?php if(!empty($tn_header_font['line-height'])) : ?>
            .next-prev-wrap {
                line-height: <?php echo $tn_header_font['line-height']; ?>;
            }
            <?php endif; ?>

            /* multi category color */
            <?php
             $categories = get_categories(array(
                'hide_empty' => 0,
            ));
             if (!empty($categories)) :
                 foreach ($categories as $category) :
                    $meta = get_option('tn_cate_option') ? get_option('tn_cate_option') : array();
                    if (array_key_exists($category->term_id, $meta)) $cate_options = $meta[$category->term_id];
                    if(!empty($cate_options['tn_cate_color']) && $cate_options['tn_cate_color'] == 'custom') :
                        if(!empty($cate_options['tn_cate_color_picker'])) :
                            $color = $cate_options['tn_cate_color_picker'];
                            $class = '.tn-category-'.$category->term_id.' ';
                            $menu_class = '.tn-menu-category-'.$category->term_id. ' ';
                            $class_color ='';
                            $class_border_color ='';
                            $class_background_color ='';
                            //color
                            $class_color .= $class.'.post-categories li:hover a'.',';
                            $class_color .= '.block11-wrap'. $class .'.review-score'.',';
                            $class_color .= $class.'.widget-title h3 a:hover'.',';
                            $class_color .= $class.'.block11-score-separation';

                            //border
                            $class_border_color .= $class.'.post-categories'.',';
                            $class_border_color .= $class.'.cate-title'.',';
                            $class_border_color .= '.big-carousel-inner'.$class.',';
                            $class_border_color .= $menu_class.'.tn-mega-menu'.',';
                            $class_border_color .= $menu_class.'.tn-dropdown-menu';


                            //background
                            $class_background_color .= $class.'.review-score'.',';
                            $class_background_color .= $class.'.meta-thumb-element:hover'.',';
                            $class_background_color .= $class.'.meta-thumb-element:focus'.',';
                            $class_background_color .= $class .'.widget-title h3:before'.',';
                            $class_background_color .='#menu-main > ul >li'. $menu_class.'> a:hover'.',';
                            $class_background_color .='#menu-main > ul >li'. $menu_class.'> a:focus'.',';
                            $class_background_color .='#menu-main > ul >li.current-menu-item'. $menu_class.'> a';
                            ?>

                            <?php echo $class_color ?>
                            {
                                color:<?php echo $color ?>;
                            }
                            <?php echo $class_border_color ?>
                            {
                                border-color: <?php echo $color ?>;
                            }
                            <?php echo $class_background_color ?>
                            {
                                background:  <?php echo $color ?>;
                            }

         <?php      endif;
                endif;
            endforeach;
         endif; ?>

            /*custom css */
            <?php if(!empty($tn_custom_css)) {
                echo $tn_custom_css;
            } ?>

        </style>
    <?php
    }
}