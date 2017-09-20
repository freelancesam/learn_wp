<?php
if (!function_exists('tn_blockBigSlider')) {
    function tn_blockBigSlider($post)
    {
        $meta_options = array(
            'date' => true,
            'comments' => true
        );

        $title = get_the_title($post->ID);
        $title_attribute = esc_attr(strip_tags($title));
        $href = get_permalink($post->ID);

        $str = '';
        $str .= '<div class="block-big-slider-wrap tn-category-'.tn_get_category_id($post->ID).'" ' . tn_get_block_scope() . '>';
        $str .= tn_get_item_scope_meta(get_the_author_meta('ID', $post->post_author));
        $str .= '<div class="thumb-slider-wrap">';
        if (function_exists("has_post_thumbnail") && has_post_thumbnail($post->ID)) {
            $str .= '<a href="' . $href . '" title="' . $title_attribute . '" rel="bookmark">';
            $str .= get_the_post_thumbnail($post->ID, 'big-slider-thumb');
            $str .= '</a>';
            $str .= '<div class="block-big-slider-content-wrap">';
            $str .= '<div class="block-big-slider-title"><a href="' . $href . '" title="' . $title_attribute . '">' . $title . '</a></div>';
            $str .= '<div class="block1-meta-tag">' . tn_cate_tag($post);
            $str .= tn_meta($post, $meta_options);
            $str .= '</div>';

            $str .= '</div>';
        };
        $str .= '</div></div>';
        return $str;
    }
}