<?php
if (!function_exists('tn_blockFeature')) {
    function tn_blockFeature($post, $options)
    {
        $meta_options = array(
            'date' => true,
            'comments' => true,
        );
        if (!empty($options['thumb'])) $thumb = $options['thumb']; else $thumb = 'module_square_thumb';

        $title = get_the_title($post->ID);
        $title_attribute = esc_attr(strip_tags($title));
        $href = get_permalink($post->ID);

        $str = '';
        $str .= '<div class="block-feature-wrap"  ' . tn_get_block_scope() . '>';
        $str .= tn_get_item_scope_meta(get_the_author_meta('ID', $post->post_author));
        $str .= '<div class="thumb-wrap">';
        $str .= '<div class="thumb-overlay"></div>';
        if (function_exists("has_post_thumbnail") && has_post_thumbnail($post->ID)) {
            $str .= '<a href="' . $href . '" title="' . $title_attribute . '" rel="bookmark">';
            $str .= tn_format($post);
            $str .= '</a>';
            $str .= get_the_post_thumbnail($post->ID, $thumb);
            $str .= tn_share_to_social_thumb($post);
            $str .= '<div class="block-carousel-content">';
            $str .= tn_meta_on_thumb($post->ID);
            $str .= '<h3 itemprop="name" class="block-title"><a itemprop="url" href="' . $href . '" title="' . $title_attribute . '">' . $title . '</a></h3>';
            $str .= '<div class="block1-meta-tag">' . tn_cate_tag($post);
            $str .= tn_meta($post, $meta_options);
            $str .= '</div>';

        }
        $str .= '</div></div></div>';
        return $str;
    }
}

if (!function_exists('tn_block_feature2_slider')) {
    function tn_block_feature2_slider($post)
    {
        $meta_options = array(
            'date' => true,
            'comments' => true,
        );

        $title = get_the_title($post->ID);
        $title_attribute = esc_attr(strip_tags(get_the_title($post->ID)));
        $href = get_permalink($post->ID);

        $str = '';
        $str .= '<li class="block-feature2-slider-wrap tn-category-' . tn_get_category_id($post->ID) . '" ' . tn_get_block_scope() . '>';
        $str .= '<div class="thumb-slider-wrap">';
        if (function_exists("has_post_thumbnail") && has_post_thumbnail($post->ID)) {
            $str .= '<div class="thumb-overlay"></div>';
            $str .= '<a href="' . $href . '" title="' . $title_attribute . '" rel="bookmark">';
            $str .= get_the_post_thumbnail($post->ID, 'blog_classic_thumb');
            $str .= '</a>';
            $str .= tn_share_to_social_thumb($post);
            $str .= '<div class="block-feature2-slider-content">';
            $str .= tn_meta_on_thumb($post->ID);
            $str .= '<div class="block-feature2-slider-title"><a href="' . $href . '" title="' . $title_attribute . '">' . $title . '</a></div>';
            $str .= '<div class="block1-meta-tag">' . tn_cate_tag($post);
            $str .= tn_meta($post, $meta_options);
            $str .= '</div>';
            $str .= '</div>';
        };
        $str .= '</div></li>';
        return $str;
    }
}