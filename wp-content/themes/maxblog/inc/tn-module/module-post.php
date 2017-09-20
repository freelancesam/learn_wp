<?php
if (!function_exists('tn_modulePost')) {
    function tn_modulePost($posts, $options)
    {
        $str = '';
        $str .= '<div class="module-post-wrap">';
        if (!empty($options) && $options['style'] == 'style7') {
            $slider_options = array();
            $slider_options['id'] = uniqid('modulepost_');
            $str .= '<div id="' . $slider_options['id'] . '">';
            tn_slider_data($slider_options['id'], $slider_options);
            $str .= '<div class="tn-flexslider slider-loading clearfix"><ul class="tn-slides">';
        } else
            $str .= '<ul class="module-post-content">';

        foreach ($posts as $post) {
            $str .= '<li>';
            $str .= tn_modulePost_style($post, $options);
            $str .= '</li>';
        }
        if (!empty($options) && $options['style'] == 'style7') $str .= '</ul></div></div><!--#module post -->';
        else  $str .= '</ul><!--#module post -->';
        $str .= '</div>';

        return $str;
    }
}

if (!function_exists('tn_modulePost_style')) {
    function tn_modulePost_style($post, $options)
    {
        if (!empty($options['style'])) {
            switch ($options['style']) {
                case 'style1' :
                    return tn_block6($post);
                case 'style2' :
                    return tn_block4($post);
                case 'style3' : {
                    $options['thumb'] = "medium";
                    return tn_block3($post, $options);
                }
                case 'style4' :
                    return tn_block7($post, $options);

                case 'style5' :
                    return tn_block8($post, $options);

                case 'style6' :
                    return tn_block9($post, $options);
                case
                    'style7' :
                    return tn_blockSlider($post, $options);
            }

        }
    }
}