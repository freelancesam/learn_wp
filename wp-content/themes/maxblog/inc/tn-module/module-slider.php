<?php
if (!function_exists('tn_moduleSlider')) {
    function tn_moduleSlider($posts, $slider_options)
    {
        global $tn_big_slider_data;

        $slider_options['sync'] = 'sync';
        $slider_id = uniqid('tnslider_big_');
        $carousel_id = uniqid('tnslider_big_');
        $slider_options['sync'] = '#' . $carousel_id;
        $slider_options['type'] = 'slider';
        $tn_big_slider_data[$slider_id] = $slider_options;

        unset($slider_options['sync']);
        $slider_options['id'] = $carousel_id;
        $slider_options['asNavFor'] = '#' . $slider_id;
        $slider_options['animation'] = 'slide';
        $slider_options['type'] = 'carousel';
        $tn_big_slider_data[$carousel_id] = $slider_options;

        wp_localize_script('tn-script', 'tn_big_slider_data', $tn_big_slider_data);

        $str = '';
        $str .= '<div class="module-slider-wrap">';
        $str .= '<div id="' . $slider_id . '" class="tn-flexslider slider-loading big-slider-wrap clearfix"><ul class="tn-slides">';
        foreach ($posts as $post) {
            $str .= '<li>';
            $str .= tn_blockBigSlider($post);
            $str .= '</li>';
        }
        $str .= '</ul></div><!--#slider-->';
        $str .= '<div class="big-carousel-wrap">';
        $str .= '<div id="' . $carousel_id . '" class="tn-flexslider slider-loading clearfix"><ul class="tn-slides">';
        foreach ($posts as $post) {

            $thumb_id = get_post_thumbnail_id($post->ID);
            $small_image_thumb = wp_get_attachment_image_src($thumb_id, 'module_medium_thumb');
            $alt = get_post_meta($thumb_id, '_wp_attachment_image_alt', true);

            $str .= '<li>';
            $str .= '<div class="big-carousel-inner tn-category-' . tn_get_category_id($post->ID) . '">';
            $str .= '<div class="big-carousel-content">';
            $str .= '<img src="' . esc_url($small_image_thumb[0]) . '" alt="' . esc_attr(strip_tags($alt)) . '">';
            $str .= '<div class="thumb-overlay"></div>';
            $str .= '<div class="big-slider-carousel-title">' . get_the_title($post->ID) . '</div></div>';
            $str .= '</div><!--#carousel inner -->';
            $str .= '</li>';
        }
        $str .= '</ul></div><!--#nav control-->';
        $str .= '</div><!--#big carousel wrap -->';
        $str .= '</div><!-- #module slider -->';

        return $str;
    }
}