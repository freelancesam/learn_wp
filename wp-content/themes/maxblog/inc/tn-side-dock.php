<?php
//side dock
if (!function_exists('tn_side_dock')) {
    function tn_side_dock($postID)
    {
        global $tn_options;
        if (empty($tn_options['tn_side_dock'])) return;
        $str = '';
        $options = array();
        $options['post_id'] = $postID;
        $options['title'] = (!empty($tn_options['tn_side_dock_title'])) ? $tn_options['tn_side_dock_title'] : '';
        $options['sort_order'] = (!empty($tn_options['tn_side_dock_sort'])) ? $tn_options['tn_side_dock_sort'] : 'lasted';
        $options['style'] = (!empty($tn_options['tn_side_dock_style'])) ? $tn_options['tn_side_dock_style'] : 'style1';
        $options['num'] = (!empty($tn_options['tn_side_dock_num'])) ? $tn_options['tn_side_dock_num'] : 1;
        $options['post__not_in'] = $postID;
        $options['excerpt'] = 10;

        $array_query = tn_get_array_query($options);
        if(!empty($array_query))
        $query_data = tn_custom_query($array_query);
        if (!empty($query_data))
            $str .= tn_get_side_dock($query_data->posts, $options);

        return $str;
    }
}

//render side dock
if (!function_exists('tn_get_side_dock')) {
    function tn_get_side_dock($posts, $options)
    {
        $str = '';
        $str .= '<div class="side-dock-wrap">';
        $str .= '<div class="side-dock-title"><a id="close-side-dock" href="#"><i class="fa fa-angle-double-right"></i></a><h3>' . esc_attr(strip_tags($options['title'])) . '</h3></div>';
        $str .= '<div class="side-dock-content-wrap">';
        if ($options['style'] == "style1") {
            foreach ($posts as $post) {
                $str .= tn_block4($post, $options);
            }
        } else {
            foreach ($posts as $post) {
                $str .= tn_block6($post);
            }
        }
        $str .= '</div></div><!-- #side dock wrap-->';
        return $str;
    }
}

//get array query
if (!function_exists('tn_get_array_query')) {
    function tn_get_array_query($options)
    {
        $tn = array();
        $sort_order = $options['sort_order'];
        switch ($sort_order) {
            case 'rand' :
                $tn['sort_order'] = 'rand';
                break;
            case 'lasted' :
                $tn['sort_order'] = 'date_post';
                break;
            case 'tag' :
                $tn['tag_slug'] = tn_get_post_tags_string($options['post_id']);
                if(empty($tn['tag_slug'])) return false;
                break;
            case 'cate' :
                $tn['category_id'] = tn_get_cate_ids_string($options['post_id']);
                if(empty($tn['category_id'])) return false;
                break;
        };
        $tn['posts_per_page'] = $options['num'];
        $tn['post__not_in'] = $options['post__not_in'];

        return $tn;
    }
}