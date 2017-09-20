<?php
if (!function_exists('tn_single_related')) {
    function tn_single_related($post)
    {
        global $tn_options;

        $related_options = array();
        $query_data = array();
        $category_ids = '';
        $tags = '';
        $related_where = tn_get_theme_option('tn_post_related_where', 'all');

        $categories_data = get_the_category($post->ID);
        $tags_data = get_the_tags($post->ID);

        foreach ($categories_data as $category) {
            $category_ids .= $category->term_id . ',';
        }

        if (!empty($tags_data)) {
            foreach ($tags_data as $tag) {
                $tags .= $tag->slug . ',';
            }
        } else {
            $related_where = 'cate';
        }

        $tn_sidebar_position = get_post_meta($post->ID, 'tn_post_sidebar_position', true);
        $related_options['post__not_in'] = $post->ID;
        $related_options['sort_order'] = 'lasted';
        $related_options['posts_per_page'] = (intval($tn_options['tn_post_related_num'] < 2)) ? 2 : intval($tn_options['tn_post_related_num']);

        switch ($related_where) {
            case 'all' : {
                $related_options['tag_plus'] = substr(strip_tags($tags), 0, -1);
                $query_data = tn_custom_query($related_options);

                //check not enough post by tags
                $count = count($query_data->posts);
                if ($count < $related_options['posts_per_page'] && !empty($query_data->posts)) {
                    foreach ($query_data->posts as $post_related) {
                        $related_options['post__not_in'] .= ',' . $post_related->ID;
                    }
                    $related_options['category_ids'] = substr($category_ids, 0, -1);

                    unset($related_options['tag_plus']);
                    if (!empty($count)) {
                        $related_options['posts_per_page'] = $related_options['posts_per_page'] - $count;
                    }

                    $query_data_more = tn_custom_query($related_options);

                    //add categories related to tags related
                    foreach ($query_data_more->posts as $data) {
                        $query_data->posts[] = $data;
                    }
                };
                break;
            }

            case 'tags' : {
                $related_options['tag_plus'] = substr($tags, 0, -1);
                $query_data = tn_custom_query($related_options);
                break;
            }

            case 'categories' : {
                $related_options['category_ids'] = substr($category_ids, 0, -1);
                $query_data = tn_custom_query($related_options);
                break;
            }
        };

        wp_reset_postdata();

        //render related
        if (!empty($query_data->posts)) {
            $max = count($query_data->posts);

            $str = '';
            $str .= '<div class="single-related-wrap clearfix">';
            $str .= '<div class="single-title-wrap widget-title"><h3>' . __('Related Posts', 'tn') . '</h3></div>';
            $str .= '<div class="single-related-content-wrap">';
            if ($tn_sidebar_position == 'full') {
                $counter = 0;
                foreach ($query_data->posts as $post) {
                    if ($counter < 3) {
                        $str .= tn_open_row(3, $counter);
                        $str .= '<div class="col-sm-4 col-xs-12">';
                        $str .= tn_block4($post);
                        $str .= '</div>';
                        $str .= tn_close_row(3, $counter, $max);
                        $counter++;
                    } else {
                        $str .= tn_open_row(3, $counter);
                        $str .= '<div class="col-sm-4 col-xs-12">';
                        $str .= tn_block11($post);
                        $str .= '</div>';
                        $str .= tn_close_row(3, $counter, $max);
                        $counter++;
                    }
                }
            } else {
                $counter = 0;
                foreach ($query_data->posts as $post) {
                    if ($counter < 2) {
                        $str .= tn_open_row(2, $counter);
                        $str .= '<div class="col-sm-6 col-xs-12">';
                        $str .= tn_block4($post);
                        $str .= '</div>';
                        $str .= tn_close_row(2, $counter, $max);
                        $counter++;
                    } else {
                        $str .= tn_open_row(2, $counter);
                        $str .= '<div class="col-sm-6 col-xs-12">';
                        $str .= tn_block11($post);
                        $str .= '</div>';
                        $str .= tn_close_row(2, $counter, $max);
                        $counter++;
                    }
                }
            }
            $str .= '</div></div>';

            return $str;
        }
    }
}


