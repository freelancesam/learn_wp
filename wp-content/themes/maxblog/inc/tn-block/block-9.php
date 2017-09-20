<?php
if (!function_exists('tn_block9')) {
    function tn_block9($post)
    {
        $meta_options = array(
            'date' => true,
            'comments' => true,
        );

        $title = get_the_title($post->ID);
        $title_attribute = esc_attr(strip_tags($title));
        $href = get_permalink($post->ID);

        $str = '';
        $str .= '<div class="block9-wrap tn-block-wrap tn-category-' . tn_get_category_id($post->ID) . ' clearfix" ' . tn_get_block_scope() . '>';
        $str .= tn_get_item_scope_meta(get_the_author_meta('ID', $post->post_author));
        $str .= '<div class="block9-content">';
        $str .= '<h3 itemprop="name" class="block-title"><a itemprop="url" href="' . $href . '" title="' . $title_attribute . '">' . $title . '</a></h3>';
        $str .= '<div class="block9-meta-tag">';
        $str .= tn_meta($post, $meta_options);
        $str .= '</div>';
        $str .= '</div></div>';
        return $str;
    }
}