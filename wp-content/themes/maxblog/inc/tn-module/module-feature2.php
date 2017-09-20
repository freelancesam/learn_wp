<?php
//feature 2 widget
if (!function_exists('tn_moduleFeature2')) {
    function tn_moduleFeature2($posts, $options)
    {
        $str = '';
        $count_post = count($posts);
        $counter = 1;
        $str_slider = '';
        $str_small = '';

        //init slider
        $id = uniqid('tnslider_');
        $slider_options['id'] = $id;
        if (!empty($options['animation'])) {
            $slider_options['animation'] = $options['animation'];
        } else  $slider_options['animation'] = 'fade';
        $slider_options['speed'] = 400;
        $slider_options['time'] = 4000;

        tn_slider_data($id, $slider_options);

        //render module
        switch ($options['style']) {
            case '1' : {
                $num_slider_post = $count_post - 1;
                foreach ($posts as $post) {
                    if ($counter <= $num_slider_post)
                        $str_slider .= tn_block_feature2_slider($post);
                    else
                        $str_small .= tn_block1($post, $options);
                    $counter++;
                };
                break;
            }
            case '2' : {
                $num_slider_post = $count_post - 2;
                $options['thumb'] = "medium";
                foreach ($posts as $post) {
                    if ($counter <= $num_slider_post)
                        $str_slider .= tn_block_feature2_slider($post);
                    else
                        $str_small .= tn_block3($post, $options);
                    $counter++;
                };
                break;
            }

            case '3' : {
                $num_slider_post = $count_post - 5;
                $options['thumb'] = "medium";
                foreach ($posts as $post) {
                    if ($counter <= $num_slider_post)
                        $str_slider .= tn_block_feature2_slider($post);
                    else
                        $str_small .= tn_block6($post, $options);
                    $counter++;
                };
                break;
            }
        }


        $str .= '<div class="module-feature2-wrap clearfix">';
        $str .= '<div style="float:'.$options['float'].'" class="module-feature2-big-slider col-sm-8 col-xs-12">';
        $str .= '<div id="' . $id . '" class="tn-feature2-slider-wrap">';
        $str .= '<div class="tn-flexslider slider-loading clearfix"><ul class="tn-slides">';
        $str .= $str_slider;
        $str .= '</ul></div></div><!--#tn-feature2-slider-->';
        $str .= '</div><!--#big slider -->';

        $str .= '<div class="module-feature2-small col-sm-4 col-xs-12">';
        $str .= $str_small;
        $str .= '</div><!--#small module -->';

        $str .= '</div><!--#module feature -->';

        return $str;
    }
}

