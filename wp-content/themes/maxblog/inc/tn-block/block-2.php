<?php
if (!function_exists('tn_block2')) {
    function tn_block2($post, $options)
    {
        $meta_options = array(
            'date' => true,
            'comments' => true
        );

        $thumb = "module_medium_thumb";

        if (!empty($options['video_thumb']))
            $video_emb = tn_iframe_video($post->ID);

        if (empty($options['excerpt'])) $options['excerpt'] = 18;

        $title = get_the_title($post->ID);
        $title_attribute = esc_attr(strip_tags($title));
        $href = get_permalink($post->ID);

        $str = '';
        $str .= '<div class="block2-wrap tn-block-wrap tn-category-' . tn_get_category_id($post->ID) . ' row clearfix" ' . tn_get_block_scope() . '>';
        $str .= tn_get_item_scope_meta(get_the_author_meta('ID', $post->post_author));
        $str .= '<div class="block-thumb-inner col-sm-6 col-xs-12">';
        if (!empty($video_emb)) {
            $str .= $video_emb;
        } elseif (function_exists("has_post_thumbnail") && has_post_thumbnail($post->ID)) {
            $str .= '<div class="thumb-wrap">';
            $str .= '<a href="' . $href . '" title="' . $title_attribute . '" rel="bookmark">';
            $str .= tn_format($post);
            $str .= get_the_post_thumbnail($post->ID, $thumb) . '</a>';
            $str .= tn_meta_on_thumb($post->ID);
            $str .= tn_share_to_social_thumb($post);
            $str .= '</div><!--#thumb wrap-->';
        } else {
            $str .= '<div class="no-thumb-wrap"><a class="no-thumb"  href="' . $href . '" title="' . $title_attribute . '" rel="bookmark"></a></div>';
        }
        $str .= '</div>';

        $str .= '<div class="block2-content col-sm-6 col-xs-12">';
        $str .= '<h3 itemprop="name" class="block-title"><a itemprop="url" href="' . $href . '" title="' . $title_attribute . '">' . $title . '</a></h3>';
        $str .= '<div class="block1-meta-tag">' . tn_cate_tag($post);
        $str .= tn_meta($post, $meta_options);
        $str .= '</div>';
        $str .= '<p>' . tn_excerpt($post, $options) . '</p>';
        if (!empty($options['readmore']) && $options['readmore'] == 'checked') {
            $str .= '<div class="block2-readmore">' . tn_readmore($post) . '</div>';
        }
        $str .= '</div></div>';
        return $str;
    }
}