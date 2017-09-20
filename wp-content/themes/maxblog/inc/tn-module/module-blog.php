<?php
//style 1
if(!function_exists('tn_module_blog1')){
    function tn_module_blog1($posts,$options){
        $str ='';

        $str .='<div class="module-blog-wrap">';
        foreach ($posts as $post) {
            $str .= tn_block2($post,$options);
        }
        $str .='</div><!-- #blog-wrap -->';
        return $str;
    }
}

//$style 2
if(!function_exists('tn_module_blog2')){
    function tn_module_blog2($posts,$options){
        $str ='';

        $str .='<div class="module-blog-wrap">';
        foreach ($posts as $post) {
            $str .= tn_block5($post,$options);
        }
        $str .='</div>';
        return $str;
    }
}

//style 3
if(!function_exists('tn_module_blog3')){
    function tn_module_blog3($posts,$options){
        $str ='';
        $counter = 0;
        $max = count($posts);
        $str .='<div class="module-blog-wrap">';
        foreach ($posts as $post) {
            $str .= tn_open_row(2,$counter);
            $str .='<div class="col-sm-6 col-sx-12">';
            $str .= tn_block1($post,$options);
            $str .='</div>';
            $str .= tn_close_row(2,$counter,$max);
            $counter ++;
        }
        $str .='</div>';
        return $str;
    }
}


//style 4
if(!function_exists('tn_module_blog4')){
    function tn_module_blog4($posts,$options){
        $str ='';
        $counter = 0;
        $max = count($posts);
        $str .='<div class="module-blog-wrap">';
        foreach ($posts as $post) {
            $str .= tn_open_row(2,$counter);
            $str .='<div class="col-sm-6 col-sx-12">';
            $str .= tn_block4($post);
            $str .='</div>';
            $str .= tn_close_row(2,$counter,$max);
            $counter ++;
        }
        $str .='</div>';
        return $str;
    }
}

//style 5
if(!function_exists('tn_module_blog5')){
    function tn_module_blog5($posts,$options){
        $str ='';
        $counter = 0;
        $max = count($posts);
        $str .='<div class="module-blog-wrap">';
        foreach ($posts as $post) {
            $str .= tn_open_row(2,$counter);
            $str .='<div class="col-sm-6 col-sx-12">';
            $str .= tn_block3($post,$options);
            $str .='</div>';
            $str .= tn_close_row(2,$counter,$max);
            $counter ++;
        }
        $str .='</div>';
        return $str;
    }
}

//style 6
if(!function_exists('tn_module_blog6')){
    function tn_module_blog6($posts,$options){
        $str ='';
        $counter = 0;
        $max = count($posts);
        $str .='<div class="module-blog-wrap">';
        foreach ($posts as $post) {
            $str .= tn_open_row(2,$counter);
            $str .='<div class="col-sm-6 col-sx-12">';
            $str .= tn_block7($post,$options);
            $str .='</div>';
            $str .= tn_close_row(2,$counter,$max);
            $counter ++;
        }
        $str .='</div>';
        return $str;
    }
}

//style 7
if(!function_exists('tn_module_blog7')){
    function tn_module_blog7($posts,$options){
        $str ='';
        $counter = 0;
        $max = count($posts);
        $str .='<div class="module-blog-wrap">';
        foreach ($posts as $post) {
            $str .= tn_open_row(3,$counter);
            $str .='<div class="col-sm-4 col-sx-12">';
            $str .= tn_block1($post,$options);
            $str .='</div>';
            $str .= tn_close_row(3,$counter,$max);
            $counter ++;
        }
        $str .='</div>';
        return $str;
    }
}

//style 8
if(!function_exists('tn_module_blog8')){
    function tn_module_blog8($posts,$options){
        $str ='';
        $counter = 0;
        $max = count($posts);
        $str .='<div class="module-blog-wrap">';
        foreach ($posts as $post) {
            $str .= tn_open_row(3,$counter);
            $str .='<div class="col-sm-4 col-sx-12">';
            $str .= tn_block4($post);
            $str .='</div>';
            $str .= tn_close_row(3,$counter,$max);
            $counter ++;
        }
        $str .='</div>';
        return $str;
    }
}

//style 9
if(!function_exists('tn_module_blog9')){
    function tn_module_blog9($posts,$options){
        $str ='';
        $counter = 0;
        $max = count($posts);
        $str .='<div class="module-blog-wrap">';
        foreach ($posts as $post) {
            $str .= tn_open_row(3,$counter);
            $str .='<div class="col-sm-4 col-sx-12">';
            $str .= tn_block3($post,$options);
            $str .='</div>';
            $str .= tn_close_row(3,$counter,$max);
            $counter ++;
        }
        $str .='</div>';
        return $str;
    }
}

//style 10 (classic blog)
if (!function_exists('tn_module_blog10')) {
    function tn_module_blog10($posts, $options)
    {
        $str = '';

        $str .= '<div class="module-blog-wrap">';
        foreach ($posts as $post) {
            $str .= tn_block_classic($post, $options);
        }
        $str .= '</div>';
        return $str;
    }
}

if(!function_exists('tn_render_blog_layout')){
    function tn_render_blog_layout($posts,$options){
        switch ($options['style']) {
            case 'style1' :
                return tn_module_blog1($posts,$options);
            case 'style2' :
                return tn_module_blog2($posts,$options);
            case 'style3' :
                return tn_module_blog3($posts,$options);
            case 'style4' :
                return tn_module_blog4($posts,$options);
            case 'style5' :
                return tn_module_blog5($posts,$options);
            case 'style6' :
                return tn_module_blog6($posts,$options);
            case 'style7' :
                return tn_module_blog7($posts,$options);
            case 'style8' :
                return tn_module_blog8($posts,$options);
            case 'style9' :
                return tn_module_blog9($posts,$options);
            case 'style10' :
                return tn_module_blog10($posts,$options);
        }
    }
}