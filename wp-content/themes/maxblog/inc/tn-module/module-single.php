<?php
if (!function_exists('tn_render_single_layout')) {
    function tn_render_single_layout($post, $options)
    {
        switch ($options['style']) {
            case 'style1' :
                return tn_render_single_layout1($post, $options);
            case 'style2' :
                return tn_render_single_layout2($post, $options);
        }
    }
};

//single modern layout
if (!function_exists('tn_render_single_layout1')) {
    function tn_render_single_layout1($post, $options)
    {
        global $tn_options;
        $meta_options = array(
            'date' => true,
            'author' => true,
            'comments' => true,
            'views' => true
        );
        $video_emb = tn_iframe_video($post->ID);
        $audio_emb = tn_iframe_audio($post->ID);

        $str = '';
        $str .= tn_open_single($options['sidebar_position'], $post->ID);
        $str .= '<div class="single-style1-wrap">';
        $str .= '<div class="single-style1-meta-tag tn-category-'.tn_get_category_id($post->ID).'">';
        $str .= tn_cate_tag($post,true);
        $str .= tn_meta($post, $meta_options);
        $str .= '</div>';
        $str .= '<div class="single-style1-title"><h1 itemprop="name">' . get_the_title($post->ID) . '</h1></div>';
        if (!empty($tn_options['tn_post_share_top'])) {
            $str .= '<div class="single-top-social-wrap single-social-wrap">';
            $str .= '<span class="single-social-title">' . __('share on:', 'tn') . '</span>';
            $str .= tn_share_to_social($post);
            $str .= '</div>';
        };
        $str .= '<div class="single-style1-content single-content clearfix">';
        if (!empty($video_emb)) {
            $str .= $video_emb;
        } elseif (!empty($audio_emb)) {
            $str .= $audio_emb;
        } else {
            $str .= '<div class="thumb-wrap">';
            if ('gallery' == get_post_format($post->ID)) {
                $str .= tn_moduleSingleSlider($post->ID, $options['style']);
            } elseif (function_exists("has_post_thumbnail") && has_post_thumbnail($post->ID)) {
                $str .= get_the_post_thumbnail($post->ID, 'blog_classic_thumb');
            }
            $str .= '</div>';
        };

        //review option
        if (tn_check_reviews($post->ID) && $options['review_position'] == 'style2') {
            $str .= '<div class="top-right-single-review">';
            $str .= tn_render_single_review($post->ID);
            $str .= '</div>';
        }

        $content = get_the_content(__('Continue','tn'));
        $content = apply_filters('the_content', $content);
        $content = str_replace(']]>', ']]&gt;', $content);

        $str .= '<div class="post-content-wrap">';
        $str .= $content;
        $str .= wp_link_pages( array(
            'before' => '<div class="page-links">' . __( 'Pages:', 'tn' ),
            'after'  => '</div>',
            'echo'   => 0
        ));
        $str .= '</div><!--#post content -->';
        $str .= '</div></div>';

        //review option
        if (tn_check_reviews($post->ID) && $options['review_position'] == 'style1') {
            $str .= tn_render_single_review($post->ID);
        }

        return $str;
    }
};

//single full width thumb layout
if (!function_exists('tn_render_single_layout2')) {
    function tn_render_single_layout2($post, $options)
    {
        global $tn_options;
        $meta_options = array(
            'date' => true,
            'author' => true,
            'comments' => true,
            'views' => true,
            'tags' => true,
        );

        $video_emb = tn_iframe_video($post->ID);
        $audio_emb = tn_iframe_audio($post->ID);

        $str = '';

        if (function_exists("has_post_thumbnail") && has_post_thumbnail($post->ID)) {

            $str .= '<div class="single-style2-thumb-wrap tn-category-'.tn_get_category_id($post->ID).' clearfix" '. tn_get_single_scope(tn_check_reviews($post->ID)) . '>';
            $str .= '<div class="thumb-wrap">';
            if ('gallery' == get_post_format($post->ID)) {
                $str .= tn_moduleSingleSlider($post->ID, $options['style']);
            } else {
                $str .= get_the_post_thumbnail($post->ID, 'big-slider-thumb');
            }
            $str .= '<div class="thumb-overlay"></div>';
            $str .= '</div>';
            $str .= '<div class="single-style2-title-wrap">';
            $str .= '<div class="single-style2-title"><h1 itemprop="name">' . get_the_title($post->ID) . '</h1></div>';
            $str .= '<div class="block1-meta-tag">' . tn_cate_tag($post,true);
            $str .= tn_meta($post, $meta_options);
            $str .= '</div>';
            $str .='</div></div>';
        } else {
            $str .= '<div class="single-style2-nothumb-wrap tn-category-'.tn_get_category_id($post->ID).'" '. tn_get_single_scope(tn_check_reviews($post->ID)) .'>';
            $str .= '<div class="single-style2-title-wrap">';
            $str .= '<div class="single-style2-title"><h1 itemprop="name">' . get_the_title($post->ID) . '</h1></div>';
            $str .= '<div class="block1-meta-tag">' . tn_cate_tag($post,true);
            $str .= tn_meta($post, $meta_options);
            $str .= '</div>';
            $str .='</div></div>';
        }
        $str .= tn_open_single_no_seo($options['sidebar_position']);
        $str .= '<div class="single-style2-content-wrap single-content clearfix">';

        if (!empty($tn_options['tn_post_share_top'])) {
            $str .= '<div class="single-top-social-wrap single-social-wrap">';
            $str .= '<span class="single-social-title">' . __('share on:', 'tn') . '</span>';
            $str .= tn_share_to_social($post);
            $str .= '</div>';
        };

        if (!empty($video_emb)) {
            $str .= $video_emb;
        };

        if (!empty($audio_emb)) {
            $str .= $audio_emb;
        };

        //review option
        if (tn_check_reviews($post->ID) && $options['review_position'] == 'style2') {
            $str .= '<div class="top-right-single-review">';
            $str .= tn_render_single_review($post->ID);
            $str .= '</div>';
        }

        $content = get_the_content(__('Continue','tn'));
        $content = apply_filters('the_content', $content);
        $content = str_replace(']]>', ']]&gt;', $content);

        $str .= '<div class="post-content-wrap">';
        $str .= $content;
        $str .= wp_link_pages( array(
            'before' => '<div class="page-links">' . __( 'Pages:', 'tn' ),
            'after'  => '</div>',
            'echo'   => 0
        ));
        $str .= '</div><!--#post content -->';
        $str .= '</div>';

        //review options
        if (tn_check_reviews($post->ID) && $options['review_position'] == 'style1') {
            $str .= tn_render_single_review($post->ID);
        }

        return $str;
    }
};
