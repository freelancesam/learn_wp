<?php
if (!function_exists('tn_module1')) {
    function tn_module1($posts, $options)
    {
        $str = '';
        $counter = 0;
        $str .= '<div class="module1-wrap">';
        $big_col = 'col-sm-6';
        $small_col = 'col-sm-6';
        if ($options['layout'] == '2') {
            $big_col = 'col-sm-8';
            $small_col = 'col-sm-4';
        };

        if (!empty($options['float']) && $options['float'] == 'right')
            $str .= '<div class="' . $big_col . ' col-xs-12" style="float:right">';
        else
            $str .= '<div class="' . $big_col . ' col-xs-12">';
        foreach ($posts as $post) {
            $counter++;
            if ($counter === 1) {
                $str .= tn_block1($post, $options);
                $str .= '</div>';
                $str .= '<div class="' . $small_col . ' col-xs-12">';
            } else {
                $str .= tn_small_module_style($post, $options);
            }

        }
        $str .= '</div></div>';
        return $str;
    }
}

if (!function_exists('tn_small_module_style')) {
    function tn_small_module_style($post, $options)
    {
        switch ($options['style']) {
            case '1' :
                return tn_block6($post);
            case '2' :
                return tn_block8($post);
            case '3' :
                return tn_block9($post);
            case '4' :
                return tn_block11($post);
        }
    }
}