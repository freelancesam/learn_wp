<?php
//Search Query
if (!function_exists('tn_query_search')) {
    function tn_query_search($search_data)
    {
        $args = array(
            's' => esc_sql($search_data),
            'post_type' => array('post'),
            'post_status' => 'publish',
        );

        $tn_query = new WP_Query($args);
        return $tn_query;
    }
};

//Custom Query
if (!function_exists('tn_custom_query')) {
    function tn_custom_query($array_query = '', $paged = '')
    {
        extract(shortcode_atts(
                array(
                    'category_ids' => '',
                    'category_id' => '',
                    'author_id' => '',
                    'tag_plus' => '',
                    'posts_per_page' => '',
                    'offset' => '',
                    'sort_order' => '',
                    'post_types' => '',
                    'meta_key' => '',
                    'post__not_in' => '',
                ), $array_query
            )
        );
        $args_query = array();
        $args_query['ignore_sticky_posts'] = 1;
        $args_query['post_status'] = 'publish';
        if (empty($posts_per_page)) $posts_per_page = '5';
        $args_query['posts_per_page'] = $posts_per_page;
        if (!empty($category_id) and empty($category_ids)) $category_ids = $category_id;
        if (!empty($category_ids)) $args_query['cat'] = $category_ids;
        if (!empty($tag_plus)) {
            $args_query['tag'] = str_replace(',', '+', $tag_plus);
            $args_query['tag'] = str_replace('-', '', $tag_plus);
        }
        if (!empty($author_id)) $args_query['author'] = $author_id;
        if (!empty($paged)) $args_query['paged'] = $paged; else $args_query['paged'] = 1;
        if (!empty($meta_key)) $args_query['meta_key'] = $meta_key;
        if (!empty($post__not_in)) $args_query['post__not_in'] = explode(',', $post__not_in);
        if (!empty($offset) and $paged > 1) $args_query['offset'] = absint($offset) + absint(($paged - 1) * $posts_per_page);
        else $args_query['offset'] = absint($offset);

        if (empty($sort_order)) $sort_order = 'date_post';
        switch ($sort_order) {
            case 'date_post' :
                $args_query['orderby'] = 'date';
                break;
            case 'comment_count' :
                $args_query['orderby'] = 'comment_count';
                break;
            case 'view_count':
                $args_query['meta_key'] = 'post_views_count';
                $args_query['orderby'] = 'meta_value_num';
                $args_query['order'] = 'DESC';
                break;
            case 'best_review':
                $args_query['meta_key'] = 'tn_as';
                $args_query['orderby'] = 'meta_value_num';
                $args_query['order'] = 'DESC';
                break;
            case 'post_type' :
                $args_query['orderby'] = 'type';
                break;
            case 'rand':
                $args_query['orderby'] = 'rand';
                break;
            case 'alphabetical_order_decs':
                $args_query['orderby'] = 'title';
                $args_query['order'] = 'DECS';
                break;
            case 'alphabetical_order_asc':
                $args_query['orderby'] = 'title';
                $args_query['order'] = 'ASC';
                break;
        };
        $data_query = new WP_Query($args_query);
        return $data_query;
    }
}
