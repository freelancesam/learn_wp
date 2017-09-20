<?php
if (!function_exists('tn_moduleTicker')) {
    function tn_moduleTicker()
    {
        global $tn_options;
        $str = '';
        $array_query = array();
        $array_query['category_id'] = (isset($tn_options['tn_ticker_cate'])) ? $tn_options['tn_ticker_cate'] : '';
        $array_query['sort_order'] = (!empty($tn_options['tn_ticker_sort'])) ? $tn_options('tn_ticker_sort') : 'date_post';
        $array_query['tag_plus'] = (!empty($tn_options['tn_ticker_tags'])) ? implode(',', $tn_options['tn_ticker_tags']) : '';
        $array_query['posts_per_page'] = (!empty($tn_options['tn_ticker_num'])) ? $tn_options['tn_ticker_num'] : 7;
        $query_data = tn_custom_query($array_query);

        $str .= '<div class="tn-container">';
        $str .= '<div class="module-ticker-wrap clearfix"><div class="module-ticker-inner">';
        $str .= '<ul id="tn-ticker-bar" class="js-hidden module-sticker-inner">';
        foreach ($query_data->posts as $post) {
            $str .= tn_blockTicker($post);
        }
        $str .= '</ul></div></div></div><!--#module ticker -->';
        return $str;
    }
}
