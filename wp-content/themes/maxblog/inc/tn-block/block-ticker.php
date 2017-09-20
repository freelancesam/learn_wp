<?php
if (!function_exists('tn_blockTicker')) {
    function tn_blockTicker($post)
    {
        $title = get_the_title($post->ID);
        $title_attribute = esc_attr(strip_tags($title));
        $href = get_permalink($post->ID);

        $str = '';
        $str .= '<li class="block-ticker-wrap tn-category-' . tn_get_category_id($post->ID) . '" ' . tn_get_block_scope() . '>';
        $str .= tn_get_item_scope_meta(get_the_author_meta('ID', $post->post_author));
        $str .= '<h3 itemprop="name" class="block-title"><a itemprop="url" href="' . $href . '" title="' . $title_attribute . '">' . $title . '</a></h3>';
        $str .= '</li>';
        return $str;
    }
}