<?php
if (!function_exists('tn_module5')) {
    function tn_module5($posts1, $posts2, $posts3, $options)
    {
        $str = '';
        $str .= '<div class="module5-wrap">';
        $str .= '<div class="col-sm-4 col-xs-12 module5-section">';
        if(!empty($options['title1'])){
            $str .='<div class="module5-section-title"><h3>'.$options['title1'].'</h3></div>';
        }
        foreach ($posts1 as $post) {
            $str .= tn_module5_style($post, $options,$options['style1']);
        }
        $str .= '</div><!--#module 5 section 1 -->';

        $str .= '<div class="col-sm-4 col-xs-12 module5-section">';
        if(!empty($options['title2'])) {
            $str .= '<div class="module5-section-title"><h3>' . $options['title2'] . '</h3></div>';
        }
        foreach ($posts2 as $post) {
            $str .= tn_module5_style($post, $options,$options['style2']);
        }
        $str .= '</div><!--#module 5 section 2 -->';

        $str .= '<div class="col-sm-4 col-xs-12 module5-section">';
        if(!empty($options['title3'])) {
            $str .= '<div class="module5-section-title"><h3>' . $options['title3'] . '</h3></div>';
        }
        foreach ($posts3 as $post) {
            $str .= tn_module5_style($post, $options,$options['style3']);
        }
        $str .= '</div><!--#module 5 section 3 -->';
        $str .= '</div>';
        return $str;
    }
}


if(!function_exists('tn_module5_style')){
    function tn_module5_style($post,$options,$style){
        switch ($style) {
            case '1' :
                return tn_block6($post);
            case '2' :
                return tn_block8($post);
            case '3' :
                return tn_block9($post);
            case '4' :
                return tn_block11($post);
            case '5' :
                return tn_block10($post);
        }
    }
}