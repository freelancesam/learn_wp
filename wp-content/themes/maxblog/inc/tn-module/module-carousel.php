<?php
if (!function_exists('tn_moduleCarousel')) {
    function tn_moduleCarousel($posts, $options)
    {
        $str = '';
        $str .='<div class="module-carousel-wrap">';
        $str .= '<div class="tn-flexslider slider-loading clearfix"><ul class="tn-slides">';
        foreach ($posts as $post) {
                $str .= '<li>';
                $str .= tn_blockCarousel($post, $options);
                $str .= '</li>';
            }
        $str .= '</ul></div></div><!--#module carousel -->';
        return $str;
    }
}