<?php
if (!function_exists('tn_block4')) {
    function tn_block4($post)
    {
        $meta_left_options = array(
            'date' => true,
        );

        $meta_right_options = array(
            'comments' => true,
        );

        $title = get_the_title($post->ID);
        $title_attribute = esc_attr(strip_tags($title));
        $href = get_permalink($post->ID);

        $str = '';
        $str .= '<div class="block4-wrap tn-block-wrap tn-category-' . tn_get_category_id($post->ID) . ' clearfix" ' . tn_get_block_scope() . '>';
        $str .= tn_get_item_scope_meta(get_the_author_meta('ID', $post->post_author));
        $str .= '<div class="thumb-wrap">';
        if (function_exists("has_post_thumbnail") && has_post_thumbnail($post->ID)) {
            $str .= '<a href="' . $href . '" title="' . $title_attribute . '" rel="bookmark">';
            $str .= tn_format($post);
            $str .= get_the_post_thumbnail($post->ID, 'module_medium_thumb') . '</a>';
            $str .= tn_meta_on_thumb($post->ID);
            $str .= tn_share_to_social_thumb($post);
        } else {
            $str .= '<div class="no-thumb-wrap"><a class="no-thumb"  href="' . $href . '" title="' . $title_attribute . '" rel="bookmark"></a></div>';
        };
        $str .= '</div><!--#thumb wrap -->';
        $str .= '<div class="block4-content">';
        $str .= '<div class="block4-meta-tag">';
        $str .= '<div class="block4-left-meta-tag">';
        $str .= tn_cate_tag($post);
        $str .= tn_meta($post, $meta_left_options);
        $str .= '</div>';
        $str .= '<div class="block4-right-meta-tag">';
        $str .= tn_meta($post, $meta_right_options);
        $str .= '</div>';
        $str .= '</div>';
        $str .= '<h3 itemprop="name" class="block-title"><a itemprop="url" href="' . $href . '" title="' . $title_attribute . '">' . $title . '</a></h3>';
        $str .= '</div>';
        $str .= '</div>';

        return $str;
    }
}