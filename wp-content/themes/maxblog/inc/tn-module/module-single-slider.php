<?php
if (!function_exists('tn_moduleSingleSlider')) {
    function tn_moduleSingleSlider($postID, $post_style)
    {
        if (empty($postID)) return;
        $str = '';

        $id = uniqid('tnslider_');
        $slider_options['id'] = $id;
        $slider_options['animation'] = 'slide';
        tn_slider_data($id, $slider_options);

        $args['type'] = 'image';

        if ($post_style == 'style1') {
            $args['size'] = 'blog_classic_thumb';
        } else {
            $args['size'] = 'big-slider-thumb';
        }
        $images = rwmb_meta('tn_gallery_post', $args, $postID);

        if (!empty($images)) {
            $str .= '<div id="' . $id . '" class="tn-single-gallery-wrap">';
            $str .= '<div class="tn-flexslider slider-loading clearfix"><ul class="tn-slides">';
            foreach ($images as $image) {
                $str .= '<li><a class="tn-single-gallery-link" href="' . esc_url($image['full_url']) . '">';
                $str .= '<img src="' . esc_url($image['url']) . '" height="' . esc_attr($image['height']) . '" width="' . esc_attr($image['width']) . '" alt="' . esc_attr(strip_tags($image['caption'])) . '">';
                $str .= '</a></li>';
            }
            $str .= '</ul></div><!--#tn-gallery-slider-->';
            $str .= '</div>';
        }
        return $str;
    }
}