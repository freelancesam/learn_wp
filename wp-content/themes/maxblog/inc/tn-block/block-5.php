<?php
if (!function_exists('tn_block5')) {
    function tn_block5($post, $options)
    {
        $meta_options = array(
            'date' => true,
            'comments' => true
        );

        if (!empty($options['video_thumb']))
            $video_emb = tn_iframe_video($post->ID);

        if (empty($options['excerpt'])) $options['excerpt'] = 18;

        $title = get_the_title($post->ID);
        $title_attribute = esc_attr(strip_tags($title));
        $href = get_permalink($post->ID);

        $str = '';
        $str .= '<div class="block5-wrap tn-block-wrap tn-category-' . tn_get_category_id($post->ID) . ' row clearfix" ' . tn_get_block_scope() . '>';
        $str .= tn_get_item_scope_meta(get_the_author_meta('ID', $post->post_author));
        $str .= '<div class="block-thumb-inner col-sm-4 col-xs-2">';
        if (!empty($video_emb)) {
            $str .= $video_emb;
        } else {
            $str .= '<div class="thumb-wrap">';
            if (function_exists("has_post_thumbnail") && has_post_thumbnail($post->ID)) {
                $str .= '<a href="' . $href . '" title="' . $title_attribute . '" rel="bookmark">';
                $str .= tn_format($post);
                $str .= get_the_post_thumbnail($post->ID, 'module_big_thumb') . '</a>';
                $str .= tn_meta_on_thumb($post->ID);
                $str .= tn_share_to_social_thumb($post);
            } else {
                $str .= '<div class="no-thumb-wrap"><a class="no-thumb"  href="' . $href . '" title="' . $title_attribute . '" rel="bookmark"></a></div>';
            }
            $str .= '</div></div><!--#col-->';
        };
        $str .= '<div class="block5-content col-sm-8 col-xs-10">';
        $str .= '<h3 itemprop="name" class="block-title"><a itemprop="url" href="' . $href . '" title="' . $title_attribute . '">' . $title . '</a></h3>';

        $str .= '<div class="block1-meta-tag">' . tn_cate_tag($post);
        $str .= tn_meta($post, $meta_options);
        $str .= '</div>';

        $str .= '<p>' . tn_excerpt($post, $options) . '</p>';
        if (!empty($options['readmore']) && $options['readmore'] == 'checked') {
            $str .= '<div class="block5-readmore">' . tn_readmore($post) . '</div>';
        }
        $str .= '</div></div><!--#col-->';
        return $str;
    }
}