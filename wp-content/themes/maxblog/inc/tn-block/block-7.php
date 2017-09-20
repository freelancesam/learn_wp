<?php
if (!function_exists('tn_block7')) {
    function tn_block7($post, $options)
    {
        $meta_options = array(
            'date' => true
        );


        if (empty($options['excerpt'])) $options['excerpt'] = 18;

        $title = get_the_title($post->ID);
        $title_attribute = esc_attr(strip_tags($title));
        $href = get_permalink($post->ID);

        $str = '';
        $str .= '<div class="block7-wrap tn-block-wrap tn-category-' . tn_get_category_id($post->ID) . ' clearfix" ' . tn_get_block_scope() . '>';
        $str .= tn_get_item_scope_meta(get_the_author_meta('ID', $post->post_author));
        $str .= '<h3 itemprop="name" class="block-title"><a itemprop="url" href="' . $href . '" title="' . $title_attribute . '">' . $title . '</a></h3>';
        $str .= '<div class="block7-thumb-inner">';
        $str .= '<div class="thumb-wrap">';
        if (function_exists("has_post_thumbnail") && has_post_thumbnail($post->ID)) {
            $str .= '<a href="' . $href . '" title="' . $title_attribute . '" rel="bookmark">';
            $str .= tn_format($post);
            $str .= get_the_post_thumbnail($post->ID, 'module_square_thumb') . '</a>';
            if (tn_has_reviews($post->ID)) {
                $str .= tn_score($post->ID);
            }
        } else {
            $str .= '<div class="no-thumb-wrap"><a class="no-thumb"  href="' . $href . '" title="' . $title_attribute . '" rel="bookmark"></a></div>';
        }
        $str .= '</div><!--#thumb wrap -->';
        $str .= '</div><!--#col-->';

        $str .= '<div class="block7-content">';

        $str .= '<div class="block7-meta-tag">' . tn_cate_tag($post);

        $str .= tn_meta($post, $meta_options);
        $str .= '</div>';

        $str .= '<p>' . tn_excerpt($post, $options) . '</p>';
        if (!empty($options['readmore']) && $options['readmore'] == 'checked') {
            $str .= '<div class="block7-readmore">' . tn_readmore($post) . '</div>';
        }
        $str .= '</div></div><!--#col-->';
        return $str;
    }
}