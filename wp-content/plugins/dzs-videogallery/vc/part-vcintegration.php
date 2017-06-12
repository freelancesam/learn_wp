<?php

$feed_mode_opts = array(
    'normal' => 'normal',
    'fromtop' => 'fromtop',
    'simple' => 'simple',
);

$post_type_arr_opts = array(
    'normal' => 'normal',
    'fromtop' => 'fromtop',
    'simple' => 'simple',
);





$strip_shortcodes_arr_opts = array(
    array( 'value' => 'on', 'label' => __('On')),
    array( 'value' => 'off', 'label' => __('Off')),
);

$post_type_arr_opts = array(
    array( 'value' => 'timeline_items', 'label' => __('Timeline items')),
    array( 'value' => 'post', 'label' => __('Post')),
    array( 'value' => 'page', 'label' => __('Page')),
);


$mode_arr_opts = array(
    array( 'value' => 'mode-default', 'label' => __('Default')),
    array( 'value' => 'mode-oncenter', 'label' => __('On Center')),
    array( 'value' => 'mode-slider', 'label' => __('Timeline Slider')),
    array( 'value' => 'mode-yearslist', 'label' => __('Years List')),
    array( 'value' => 'mode-blackwhite', 'label' => __('Black and White')),
    array( 'value' => 'mode-masonry', 'label' => __('Masonry')),
    array( 'value' => 'mode-slider-variation', 'label' => __('Slider Variation')),
);

$skin_arr_opts = array(
    array(
        'label'=>__("Light Theme")
    ,'value'=>'skin-light'
    ),
    array(
        'label'=>__("Dark Theme")
    ,'value'=>'skin-dark'
    ),

);


$date_format_arr_opts = array(
    array(
        'label'=>__("Default Format")
    ,'value'=>'default'
    ),
    array(
        'label'=>'12 January 2017'
    ,'value'=>'d F Y'
    ),
    array(
        'label'=>'12 Jan'
    ,'value'=>'d M'
    ),
    array(
        'label'=>'Jan 2017'
    ,'value'=>'M Y'
    ),
    array(
        'label'=>'January 2017'
    ,'value'=>'F Y'
    ),
    array(
        'label'=>'2017'
    ,'value'=>'Y'
    ),
    array(
        'label'=>__('x days ago')
    ,'value'=>'timediff'
    ),
);

global $dzsvg;
$arr_gals = array();
foreach ($dzsvg->mainitems as $mainitem) {
    array_push($arr_gals,$mainitem['settings']['id']);

}



$arr_dbs = array();


foreach ($dzsvg->dbs as $mainitem) {
    array_push($arr_dbs,$mainitem);
}

//print_r($arr_gals);

//error_log(print_rr($arr_dbs, array(  'echo'=>false) ) );



$order_by_arr_opts = array(
    array(
        'label'=>__("Date")
    ,'value'=>'date'
    ),
);
$order_arr_opts = array(
    array(
        'label'=>__("Ascending")
    ,'value'=>'asc'
    ),
    array(
        'label'=>__("Descending")
    ,'value'=>'desc'
    ),
);



$feed_direction_opts = array(
    "normal" => "normal",
    "reverse" => "reverse",
);
$feed_scrollbar_opts = array(
    'off' => 'off',
    'on' => 'on',
);
$feed_breakout_opts = array(
    'off' => 'off',
    'trybreakout' => 'trybreakout',
);
if(function_exists('vc_map')){
    vc_map(array(
        "name" => __("Video Gallery"),
        "base" => "videogallery",
        "class" => "",
        "front_enqueue_js" => $this->base_url.'vc/frontend_backbone.js',
        "category" => __('Content'),
        "params" => array(


            array(
                'type' => 'dropdown',
                'heading' => __('Gallery ID'),
                'param_name' => 'id',
                'value' => $arr_gals,
                'description' => __('select the video gallery')
            ),
            array(
                'type' => 'dropdown',
                'heading' => __('Gallery Database'),
                'param_name' => 'db',
                'value' => $arr_dbs,
                'description' => __('select the video database where the gallery is stored')
            ),

        )
    ));
}

