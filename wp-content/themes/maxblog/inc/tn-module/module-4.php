<?php
if (!function_exists('tn_module4')) {
    function tn_module4($posts, $options)
    {
        $str = '';
        $counter = 0;
        $max = count($posts);

        $str .= '<div class="module4-wrap">';
        foreach ($posts as $post) {

            if ($options['col_3']) {
                $str .= tn_open_row(3, $counter);
                $str .= '<div class="col-sm-4 col-xs-12">';
                $str .= tn_block4($post);
                $str .= '</div>';
                $str .= tn_close_row(3, $counter, $max);
            } else {
                $str .= tn_open_row(2, $counter);
                $str .= '<div class="col-sm-6 col-xs-12">';
                $str .= tn_block4($post);
                $str .= '</div>';
                $str .= tn_close_row(2, $counter, $max);
            }
            $counter++;
        }
        $str .= '</div><!--module 4 -->';
        return $str;
    }
}