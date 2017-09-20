<?php
if (!function_exists('tn_module2')) {
    function tn_module2($posts, $options)
    {
        $str = '';
        $counter = 0;
        if(!empty($options['readmore'])&& $options['readmore'] == 'checked')
            $options['thumb'] ='big';

        $str .= '<div class="module2-wrap">';
        $str .= '<div class="col-xs-12 clearfix">';
        foreach ($posts as $post) {
            $counter++;
            if ($counter === 1) {
                $str .= tn_block2($post, $options);
                $str .= '</div>';

            } else {
                $str .= tn_open_row(2,$counter);
                $str .= '<div class="col-sm-6 col-xs-12">';
                $str .= tn_small_module_style($post, $options);
                $str .= '</div>';
                $str .= tn_close_row(2,$counter,4);
            }

        }
        $str .= '</div>';
        return $str;
    }
}

