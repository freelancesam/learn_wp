<?php
if (!function_exists('tn_blockNavigation')) {
    function tn_blockNavigation($post)
    {
        $meta_options = array(
            'date' => true,
            'comments' => true,
        );

        $thumb = "module_big_thumb";

        $title = get_the_title($post->ID);
        $title_attribute = esc_attr(strip_tags($title));
        $href = get_permalink($post->ID);

        $str = '';
        $str .= '<div class="block1-wrap tn-block-wrap clearfix tn-category-' . tn_get_category_id($post->ID) . '" ' . tn_get_block_scope() . '>';
        $str .= tn_get_item_scope_meta(get_the_author_meta('ID', $post->post_author));
        if (!empty($video_emb)) {
            $str .= $video_emb;
        } else {
            $str .= '<div class="thumb-wrap">';
            if (function_exists("has_post_thumbnail") && has_post_thumbnail($post->ID)) {
                $str .= '<a href="' . $href . '" title="' . $title_attribute . '" rel="bookmark">';
                $str .= tn_format($post);
                $str .= get_the_post_thumbnail($post->ID, $thumb) . '</a>';
                $str .= tn_meta_on_thumb($post->ID);
                $str .= tn_share_to_social_thumb($post);
            } else {
                $str .= '<div class="no-thumb-wrap"><a class="no-thumb"  href="' . $href . '" title="' . $title_attribute . '" rel="bookmark"></a></div>';
            }
            $str .= '</div>';
            $str .= '<div class="block1-meta-tag">' . tn_cate_tag($post);
            $str .= tn_meta($post, $meta_options);
            $str .= '</div>';
        };
        $str .= '<div class="block1-content">';
        $str .= '<h3 itemprop="name" class="block-title"><a itemprop="url" href="' . $href . '" title="' . $title_attribute . '">' . $title . '</a></h3>';
        $str .= '</div></div>';
        return $str;
    }
}
