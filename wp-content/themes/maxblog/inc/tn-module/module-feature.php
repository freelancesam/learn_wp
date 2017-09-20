<?php
define('TN_FEATURED_CATE','featured');

//feature widget
if (!function_exists('tn_moduleFeature')) {
    function tn_moduleFeature($posts, $options)
    {
        $str = '';
        $counter = 0;
        $str .= '<div class="module-feature-wrap clearfix">';

        if ($options['style'] == 1) {
            foreach ($posts as $post) {
                switch ($counter) {
                    case 0 :
                    case 1 :
                    case 2 :
                        $options['thumb'] = "feature_medium_thumb";
                        $open = '<div class="col-sm-4 col-xs-12">';
                        break;
                    case 3 :
                    case 4 :
                    case 5 :
                    case 6 :
                        $options ['thumb'] = "module_square_thumb";
                        $open = '<div class="col-sm-3 col-xs-6">';
                }
                if ($counter < 3) $str .= tn_open_row(3, $counter);
                $str .= $open;
                $str .= tn_blockFeature($post, $options);
                $str .= '</div>';
                if ($counter < 3) $str .= tn_close_row(3, $counter, 7);
                $counter++;
            }
        } elseif ($options['style'] == 2) {
            foreach ($posts as $post) {
                switch ($counter) {
                    case 0 :
                    case 1 :
                    case 4 :
                    case 5 :
                        $options ['thumb'] = "module_square_thumb";
                        $open = '<div class="col-sm-3 col-xs-6">';
                        break;
                    case 2 :
                    case 3 :
                        $options['thumb'] = "feature_big_thumb";
                        $open = '<div class="col-sm-6 col-xs-12">';
                }
                $str .= tn_open_row(3, $counter);
                $str .= $open;
                $str .= tn_blockFeature($post, $options);
                $str .= '</div>';
                $str .= tn_close_row(3, $counter, 6);
                $counter++;
                if ($counter == 6) break;
            }
        } else {
            foreach ($posts as $post) {
                switch ($counter) {
                    case 0 :
                    case 1 :
                    case 2 :
                        $options['thumb'] = "feature_medium_thumb";
                        $open = '<div class="col-sm-4 col-xs-12">';
                        break;
                    case 3 :
                    case 4 :
                        $options['thumb'] = "feature_big_thumb";
                        $open = '<div class="col-sm-6 col-xs-12">';
                }
                if ($counter < 3) $str .= tn_open_row(3, $counter);
                $str .= $open;
                $str .= tn_blockFeature($post, $options);
                $str .= '</div>';
                if ($counter < 3) $str .= tn_close_row(3, $counter, 6);
                $counter++;
                if ($counter == 5) break;
            }
        }
        $str .= '</div><!--#module feature -->';

        return $str;
    }
}

//feature cate blog
if (!function_exists('tn_cateFeature')) {
    function tn_cateFeature($options)
    {

        if(empty($options['style'])) return;

        $posts = tn_query_featured_cate($options);

        $str = '';
        $counter = 0;
        $str .= '<div class="cate-feature-wrap clearfix">';
        $str .= '<div class="row-fluid">';
        if ($options['style'] == 1) {
            foreach ($posts as $post) {
                $options['thumb'] = "feature_big_thumb";
                $str .= '<div class="col-sm-6 col-xs-12">';
                $str .= tn_blockFeature($post, $options);
                $str .= '</div>';
                $counter++;
                if ($counter > 1) break;
            }
        } elseif ($options['style'] == 2) {
            foreach ($posts as $post) {
                $options['thumb'] = "feature_medium_thumb";
                $str .= '<div class="col-sm-4 col-xs-12">';
                $str .= tn_blockFeature($post, $options);
                $str .= '</div>';
                $counter++;
                if ($counter > 2) break;
            }
        } else {
            foreach ($posts as $post) {
                $options['thumb'] = "module_square_thumb";
                $str .= '<div class="col-sm-3 col-xs-6">';
                $str .= tn_blockFeature($post, $options);
                $str .= '</div>';
                $counter++;
                if ($counter > 3) break;
            }
        }
        $str .= '</div></div><!--#cate feature -->';

        return $str;
    }
}

if (!function_exists('tn_query_featured_cate')) {
    function tn_query_featured_cate($options)
    {
        $tn_featured_cate = get_cat_ID(TN_FEATURED_CATE);
        if (!empty($tn_featured_cate) && ($options['from'] == 'feature')) {
            $array_query['category_id'] = $tn_featured_cate;
        } else
            $array_query['category_id'] = $options['cate_id'];

        $array_query['sort_order'] = $options['sort_order'];
        $array_query['posts_per_page'] = 4;
        $array_query['meta_key'] = '_thumbnail_id';

        $query_data = tn_custom_query($array_query);

        return $query_data->posts;
    }
}
