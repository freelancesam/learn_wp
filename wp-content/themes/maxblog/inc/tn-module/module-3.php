<?php
if (!function_exists('tn_module3')) {
    function tn_module3($posts, $options)
    {
        $str = '';
        $counter = 0;
        $max = count($posts);
        $options['thumb'] = 'medium';
        $str .= '<div class="module3-wrap">';
        foreach ($posts as $post) {
            if ($options['col_3']) {
                $str .= tn_open_row(3, $counter);
                $str .= '<div class="col-sm-4 col-xs-12">';
                if ($counter == 0 || $counter == 1 || $counter == 2)
                    $str .= tn_module_3_style($post, $options);
                else  $str .= tn_small_module_style($post, $options);
                $str .= tn_close_row(3, $counter, $max);
            } else {
                $str .= tn_open_row(2, $counter);
                $str .= '<div class="col-sm-6 col-xs-12">';
                if ($counter === 0 || $counter == 1)
                    $str .= tn_module_3_style($post, $options);
                else
                    $str .= tn_small_module_style($post, $options);
                $str .= tn_close_row(2, $counter, $max);
            }
            $str .= '</div>';
            $counter++;
        }
        $str .= '</div>';
        return $str;
    }
}

if (!function_exists('tn_module3_style')) {
    function tn_module_3_style($post, $options)
    {
        if (empty($options['big_style'])) return false;

        switch ($options['big_style']) {
            case 1 :
                return tn_block1($post, $options);
            case 2 :
                return tn_block3($post, $options);
            case 3 :
                return tn_block4($post);
        }
    }
}