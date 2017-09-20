<?php
if (!function_exists('tn_module7')) {
    function tn_module7($posts, $options)
    {
        $str = '';
        $counter = 0;
        $max = count($posts);

        $str .= '<div class="module7-wrap">';
        foreach ($posts as $post) {
            if ($options['col_2']) {
                $str .= tn_open_row(2, $counter);
                $str .= '<div class="col-sm-6 col-xs-12">';
                $str .= tn_block5($post, $options);
                $str .= '</div>';
                $str .= tn_close_row(2, $counter, $max);
            } else {
                $str .= '<div class="col-xs-12">';
                $str .= tn_block5($post, $options);
                $str .= '</div>';
            }
            $counter++;
        }
        $str .= '</div><!--#module 7-->';
        return $str;
    }
}
