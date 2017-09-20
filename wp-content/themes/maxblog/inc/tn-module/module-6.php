<?php
if (!function_exists('tn_module6')) {
    function tn_module6($posts, $options)
    {
        $str = '';
        $counter = 0;
        $max = count($posts);

        $str .= '<div class="module6-wrap">';
        foreach ($posts as $post) {
            if ($options['col_3']) {
                $str .= tn_open_row(3, $counter);
                $str .= '<div class="col-sm-4 col-xs-12">';
                $str .= tn_block3($post, $options);
                $str .= '</div>';
                $str .= tn_close_row(3, $counter, $max);
            } else {
                $str .= tn_open_row(2, $counter);
                $str .= '<div class="col-sm-6 col-xs-12">';
                $str .= tn_block3($post, $options);
                $str .= '</div>';
                $str .= tn_close_row(2, $counter, $max);
            }
            $counter++;
        }
        $str .= '</div><!--#module 6 -->';
        return $str;
    }
}