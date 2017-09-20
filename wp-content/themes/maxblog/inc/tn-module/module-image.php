<?php
if (!function_exists('tn_moduleImage')) {
    function tn_moduleImage($posts, $options)
    {
        $str = '';
        $counter = 0;
        $str .= '<div class="module-image-wrap clearfix">';
        foreach ($posts as $post) {
            if ($options['col_3']) {
                $str .= '<div class="col-md-2 col-sm-3 col-xs-6">';
                $str .= tn_blockImage($post);
                $str .= '</div>';
            } else {
                $str .= '<div class="col-md-3 col-sm-6 col-xs-6">';
                $str .= tn_blockImage($post);
                $str .= '</div>';
            }

            $counter++;
        }
        $str .= '</div>';
        return $str;
    }
}