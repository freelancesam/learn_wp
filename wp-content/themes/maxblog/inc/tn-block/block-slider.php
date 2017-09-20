<?php
if (!function_exists('tn_blockSlider')) {
    function tn_blockSlider($post, $options)
    {
        $meta_options = array(
            'date' => true,
            'comments' => true
        );

        if (empty($options['excerpt'])) $options['excerpt'] = 18;

        $title = get_the_title($post->ID);
        $title_attribute = esc_attr(strip_tags($title));
        $href = get_permalink($post->ID);

        $str = '';
        $str .= '<div class="block-slider-wrap tn-category-' . tn_get_category_id($post->ID) . '" ' . tn_get_block_scope() . '>';
        $str .= tn_get_item_scope_meta(get_the_author_meta('ID', $post->post_author));
        $str .= '<div class="thumb-slider-wrap">';

        $str .= '<div class="thumb-overlay"></div>';

        if (function_exists("has_post_thumbnail") && has_post_thumbnail($post->ID)) {
            $str .= '<a href="' . $href . '" title="' . $title_attribute . '" rel="bookmark">';
            $str .= tn_format($post);
            $str .= get_the_post_thumbnail($post->ID, 'module_slider_thumb');
            $str .= '</a>';

            $str .= tn_share_to_social_thumb($post);

            $str .= '<div class="block-slider-content">';
            $str .= tn_meta_on_thumb($post->ID);
            $str .= '<h3 itemprop="name" class="block-title"><a itemprop="url" href="' . $href . '" title="' . $title_attribute . '">' . $title . '</a></h3>';
            $str .= '<div class="block1-meta-tag">' . tn_cate_tag($post);
            $str .= tn_meta($post, $meta_options);
            $str .= '</div><!--#block slider meta -->';
            $str .= '</div><!--#block slider content -->';

        };
        $str .= '</div></div><!--#block slider wrap -->';
        return $str;
    }
}