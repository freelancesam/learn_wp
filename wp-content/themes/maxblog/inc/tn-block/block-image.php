<?php
if (!function_exists('tn_blockImage')) {
    function tn_blockImage($post)
    {
        $thumb = "module_square_thumb";

        $title = get_the_title($post->ID);
        $title_attribute = esc_attr(strip_tags($title));
        $href = get_permalink($post->ID);

        $str = '';
        $str .= '<div class="block-image-wrap clearfix tn-category-'.tn_get_category_id($post->ID).'" ' . tn_get_block_scope() . '>';
        $str .= tn_get_item_scope_meta(get_the_author_meta('ID', $post->post_author));
        if (function_exists("has_post_thumbnail") && has_post_thumbnail($post->ID)) {
            $str .= '<div class="thumb-wrap">';
            $str .= '<a href="' . $href . '" title="' . $title_attribute . '" rel="bookmark">';
            $str .= tn_format($post);
            if (tn_has_reviews($post->ID)) {
                $str .= tn_score($post->ID);
            }
            $str .= get_the_post_thumbnail($post->ID, $thumb) . '</a>';
            $str .= '</div>';
        }
        $str .= '<div class="block-image-content">';
        $str .= '<h3 itemprop="name" class="block-title"><a itemprop="url" href="' . $href . '" title="' . $title_attribute . '">' . $title . '</a></h3>';
        $str .= '</div></div>';
        return $str;
    }
}