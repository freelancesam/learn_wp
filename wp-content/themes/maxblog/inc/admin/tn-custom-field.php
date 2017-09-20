<?php

//post, page options
add_action('admin_init', 'tn_register_meta_box');
//category options
add_action('admin_init', 'tn_register_cate_meta_boxes');
//author information
add_filter('user_contactmethods', 'modify_contact_methods');


// Meta Custom Filed
if (!function_exists('tn_register_meta_box')) {
    function tn_register_meta_box()
    {
        if (!class_exists('RW_Meta_Box'))
            return;
        global $meta_boxes;
        $prefix = 'tn_';

        //page options
        $meta_boxes[] = array(
            'id' => $prefix . 'page_options',
            'title' => __('MAXBLOG PAGE OPTIONS', 'tn'),
            'pages' => array('page'),
            'context' => 'normal',
            'priority' => 'high',
            'fields' => array(
                //Post style
                array(
                    'name' => __('Page Title:', 'tn'),
                    'id' => $prefix . 'page_title',
                    'desc' => __('Enable or disable page title.', 'tn'),
                    'type' => 'select',
                    'options' => array(
                        'show' => __('Show', 'tn'),
                        'hide' => __('Not Shown', 'tn'),
                    ),
                    'std' => 'show',
                ),
                //sidebar option
                array(
                    'name' => __('Sidebar Position:', 'tn'),
                    'id' => $prefix . 'page_sidebar_position',
                    'desc' => __('Select position sidebar for this page, this option override default sidebar position in Theme Options.', 'tn'),
                    'type' => 'select',
                    'options' => array(
                        '' => __('Default From Theme Options', 'tn'),
                        'left' => __('Left', 'tn'),
                        'right' => __('Right', 'tn'),
                        'full' => __('Full Width', 'tn'),
                    ),
                    'std' => '',
                ),
            )
        );

        //post options
        $meta_boxes[] = array(
            'id' => $prefix . "format_options",
            'title' => __('MAXBLOG POST OPTIONS', 'tn'),
            'pages' => array('post'),
            'context' => 'normal',
            'priority' => 'high',
            'fields' => array(
                //Post style
                array(
                    'name' => __('Post Layout:', 'tn'),
                    'id' => $prefix . 'post_layout',
                    'desc' => __('Select layout for this post, this option override default single layout in theme options.', 'tn'),
                    'type' => 'select',
                    'options' => array(
                        '' => __('Default', 'tn'),
                        'style1' => __('Style 1', 'tn'),
                        'style2' => __('Style 2', 'tn'),
                    ),
                    'std' => '',
                ),
                //sidebar option
                array(
                    'name' => __('Sidebar Position:', 'tn'),
                    'id' => $prefix . 'post_sidebar_position',
                    'desc' => __('Select position sidebar for this post, this option override default sidebar position in theme options.', 'tn'),
                    'type' => 'select',
                    'options' => array(
                        '' => __('Default From Theme Options', 'tn'),
                        'left' => __('Left', 'tn'),
                        'right' => __('Right', 'tn'),
                        'full' => __('Full Width', 'tn'),
                    ),
                    'std' => '',
                ),

                //comment box
                array(
                    'name' => __('Show Comment Box:', 'tn'),
                    'desc' => __('Enable or Disable comment box for this post. This option will override default setting in theme options', 'tn'),
                    'id' => $prefix . 'comment_disable',
                    'type' => 'select',
                    'options' => array(
                        '' => __('Default From Theme Options', 'tn'),
                        'show' => __('Show', 'tn'),
                        'hide' => __('Not Shown', 'tn'),
                    ),
                    'sdt' => ''
                ),

                //source post
                array(
                    'name' => __('Source Name:', 'tn'),
                    'id' => $prefix . "source_name",
                    'type' => 'text',
                    'desc' => __('Name of the source', 'tn'),
                ),

                array(
                    'name' => __('Source URL:', 'tn'),
                    'id' => $prefix . "source_url",
                    'type' => 'text',
                    'desc' => __('Url of the source', 'tn'),
                ),

            )
        );

        //video post
        $meta_boxes[] = array(
            'id' => $prefix . 'video_option',
            'title' => __('VIDEOS OPTIONS:', 'tn'),
            'pages' => array('post'),
            'priority' => 'high',
            'context' => 'side',
            'fields' => array(
                array(
                    'name' => __('Video URL:', 'tn'),
                    'desc' => __('Enter video link (support: Youtube, Vimeo, DailyMotion)', 'tn'),
                    'id' => $prefix . 'video_url',
                    'type' => 'text',
                ),
            ),
        );

        //gallery post
        $meta_boxes[] = array(
            'id' => $prefix . 'gallery_option',
            'title' => __('GALLERY OPTIONS:', 'tn'),
            'pages' => array('post'),
            'priority' => 'high',
            'context' => 'normal',
            'fields' => array(
                array(
                    'name' => __('Gallery Images:', 'tn'),
                    'desc' => __('Select your images ...', 'tn'),
                    'id' => $prefix . 'gallery_post',
                    'type' => 'image_advanced',
                ),

            ),
        );

        //audio post
        $meta_boxes[] = array(
            'id' => $prefix . 'audio_option',
            'title' => __('AUDIO OPTIONS:', 'tn'),
            'pages' => array('post'),
            'priority' => 'high',
            'context' => 'side',
            'fields' => array(
                array(
                    'name' => __('AUDIO URL:', 'tn'),
                    'desc' => __('Enter audio link (support: SoundCloud)', 'tn'),
                    'id' => $prefix . 'audio_url',
                    'type' => 'text',
                ),
            ),
        );

        //review score
        $meta_boxes[] = array(
            'id' => $prefix . 'review',
            'title' => __('REVIEW OPTIONS:', 'tn'),
            'pages' => array('post'),
            'context' => 'normal',
            'priority' => 'high',
            'std' => false,
            'fields' => array(
                array(
                    'name' => __('Enable Review:', 'tn'),
                    'id' => $prefix . 'enable_review',
                    'class' => 'tn_review_checkbox',
                    'type' => 'checkbox',
                    'desc' => __('enable review score this post', 'tn'),
                    'std' => 0,
                ),
                array(
                    'name' => __('Review Box Position:', 'tn'),
                    'id' => $prefix . 'post_review_position',
                    'type' => 'select',
                    'desc' => __('Select for this post', 'tn'),
                    'options' => array(
                        '' => __('Default', 'tn'),
                        'style1' => __('Full Bottom', 'tn'),
                        'style2' => __('Top Left', 'tn'),
                    ),
                    'std' => '',
                ),

                array(
                    'name' => __('Criteria 1 Description:', 'tn'),
                    'id' => $prefix . 'cd1',
                    'type' => 'text',
                ),
                array(
                    'name' => __('Criteria 1 Score:', 'tn'),
                    'id' => $prefix . 'cs1',
                    'type' => 'slider',
                    'js_options' => array(
                        'min' => 0,
                        'max' => 10,
                        'step' => .1,
                    ),
                ),
                // Criteria 2 Text & Score
                array(
                    'name' => __('Criteria 2 Description:', 'tn'),
                    'id' => $prefix . 'cd2',
                    'type' => 'text',
                ),
                array(
                    'name' => __('Criteria 2 Score:', 'tn'),
                    'id' => $prefix . 'cs2',
                    'type' => 'slider',
                    'js_options' => array(
                        'min' => 0,
                        'max' => 10,
                        'step' => .1,
                    ),
                ),
                // Criteria 3 Text & Score
                array(
                    'name' => __('Criteria 3 Description:', 'tn'),
                    'id' => $prefix . 'cd3',
                    'type' => 'text',
                ),
                array(
                    'name' => __('Criteria 3 Score:', 'tn'),
                    'id' => $prefix . 'cs3',
                    'type' => 'slider',
                    'js_options' => array(
                        'min' => 0,
                        'max' => 10,
                        'step' => .1,
                    ),
                ),
                // Criteria 4 Text & Score
                array(
                    'name' => __('Criteria 4 Description:', 'tn'),
                    'id' => $prefix . 'cd4',
                    'type' => 'text',
                ),
                array(
                    'name' => __('Criteria 4 Score:', 'tn'),
                    'id' => $prefix . 'cs4',
                    'type' => 'slider',
                    'js_options' => array(
                        'min' => 0,
                        'max' => 10,
                        'step' => .1,
                    ),
                ),
                // Criteria 5 Text & Score
                array(
                    'name' => __('Criteria 5 Description:', 'tn'),
                    'id' => $prefix . 'cd5',
                    'type' => 'text',
                ),
                array(
                    'name' => __('Criteria 5 Score:', 'tn'),
                    'id' => $prefix . "cs5",
                    'type' => 'slider',
                    'js_options' => array(
                        'min' => 0,
                        'max' => 10,
                        'step' => .1,
                    ),
                ),
                // Criteria 6 Text & Score
                array(
                    'name' => __('Criteria 6 Description:', 'tn'),
                    'id' => $prefix . "cd6",
                    'type' => 'text',
                ),
                array(
                    'name' => __('Criteria 6 Score:', 'tn'),
                    'id' => $prefix . 'cs6',
                    'type' => 'slider',
                    'js_options' => array(
                        'min' => 0,
                        'max' => 10,
                        'step' => .1,
                    ),
                ),
                // Final average
                array(
                    'name' => __('Average Score:', 'tn'),
                    'id' => $prefix . 'as',
                    'class' => 'tn-average-score',
                    'type' => 'text',
                ),
                array(
                    'name' => __('Review Summary:', 'tn'),
                    'id' => $prefix . "review_summary",
                    'class' => 'field-review-summary',
                    'type' => 'textarea',
                ),
            )
        );

        foreach ($meta_boxes as $meta_box) {
            new RW_Meta_Box($meta_box);
        }
    }
}
/**
 * Register meta boxes
 *
 * @return void
 */
function tn_register_cate_meta_boxes()
{
    // Make sure there's no errors when the plugin is deactivated or during upgrade
    if (!class_exists('RW_Taxonomy_Meta'))
        return;

    $meta_sections = array();

    // First meta section
    $meta_sections[] = array(
        'title' => __('MAXBLOG CATEGORY OPTIONS', 'tn'), // section title
        'taxonomies' => array('category', 'post_tag'), // list of taxonomies. Default is array('category', 'post_tag'). Optional
        'id' => 'tn_cate_option', // ID of each section, will be the option name

        'fields' => array( // List of meta fields
            // Cate layout
            array(
                'name' => __('Category Layout:', 'tn'),
                'id' => 'tn_cate_style',
                'type' => 'select',
                'options' => array(
                    'style1' => __('Default Layout', 'tn'),
                    'style10' => __('Classic Big Thumb With Excerpt', 'tn'),
                    'style2' => __('Small Thumbnail With Excerpt', 'tn'),
                    'style3' => __('2 Cols With Thumb and Excerpt', 'tn'),
                    'style4' => __('2 Cols With Thumb and Title', 'tn'),
                    'style5' => __('2 Cols With Thumb Gird', 'tn'),
                    'style6' => __('2 Cols With Small Thumb and Excerpt', 'tn'),
                    'style7' => __('3 Cols With Thumb and Excerpt (Full Width)', 'tn'),
                    'style8' => __('3 Cols With Thumb and  Title (Full Width)', 'tn'),
                    'style9' => __('3 Cols With Thumb Gird (Full Width)', 'tn'),
                ),
                'std' => 'style1',
                'desc' => __('Select the layout for this category.', 'tn'),
            ),

            // feature post options
            array(
                'name' => __('Show Featured Post:', 'tn'),
                'desc' => __('Select layout of gird featured post at top of the category page.', 'tn'),
                'id' => 'tn_cate_feature',
                'type' => 'select',
                'options' => array(
                    '' => __('Not Shown', 'tn'),
                    '1' => __('Style 1', 'tn'),
                    '2' => __('Style 2', 'tn'),
                    '3' => __('Style 3', 'tn'),
                ),
                'std' => '',
            ),
            array(
                'name' => __('Show Posts From:', 'tn'),
                'id' => 'tn_cate_feature_from',
                'type' => 'select',
                'options' => array(
                    'feature' => __('Featured Category', 'tn'),
                    'this' => __('This Category', 'tn'),
                ),
                'std' => 'feature',
            ),
            array(
                'name' => __('Featured Sort Order:', 'tn'),
                'id' => 'tn_cate_feature_sort',
                'type' => 'select',
                'options' => array(
                    'date' => __('Latest Post', 'tn'),
                    'comment_count' => __('Popular Post by Comments', 'tn'),
                    'view_count' => __('Popular Post by Views', 'tn'),
                    'best_review' => __('Best Reviews', 'tn'),
                    'rand' => __('Random Post', 'tn')
                ),
                'std' => 'rand',
            ),

            array(
                'name' => __('Show Video Thumbnail:', 'tn'),
                'id' => 'tn_cate_video',
                'type' => 'select',
                'options' => array(
                    '1' => __('Show', 'tn'),
                    '' => __('Not Shown', 'tn'),
                ),
                'std' => '',
                'desc' => __('Show video iframe instead featured image thumbnail.', 'tn')
            ),

            array(
                'name' => __('Show Read More Button:', 'tn'),
                'desc' => __('enable or disable read more button in posts of this category', 'tn'),
                'id' => 'tn_cate_readmore',
                'type' => 'select',
                'options' => array(
                    'checked' => __('Show', 'tn'),
                    'no' => __('Not Shown', 'tn'),
                ),
                'std' => 'no'
            ),

            //category excerpt
            array(
                'name' => __('Category Excerpt Length:', 'tn'),
                'desc' => __('How many words in posts contents', 'tn'),
                'id' => 'tn_cate_excerpt',
                'type' => 'text',
                'std' => 18,
            ),

            //category color
            array(
                'name' => __('Category Color:', 'tn'),
                'desc' => __('Select Color for this category. Set Default Theme Color if you dont want custom color for this category.', 'tn'),
                'id' => 'tn_cate_color',
                'type' => 'select',
                'options' => array(
                    'default' => __('Default Theme Color','tn'),
                    'custom' => __('Custom Color','tn'),
                ),
                'std' => 'default',
            ),

            //color picker
            array(
                'name' => __('Category Color Picker:', 'tn'),
                'desc' => __('Select Color for this category, This option only effect when select custom color in "Category Color" option.', 'tn'),
                'id' => 'tn_cate_color_picker',
                'type' => 'color',
                'std' => '#EC4C51',
            ),
        ),
    );

    foreach ($meta_sections as $meta_section) {
        new RW_Taxonomy_Meta($meta_section);
    }
}

//author social info
function modify_contact_methods($profile_fields)
{
    $profile_fields['tn_facebook'] = __('Facebook', 'tn');
    $profile_fields['tn_twitter'] = __('Twitter', 'tn');
    $profile_fields['tn_google_plus'] = __('Google Plus', 'tn');
    $profile_fields['tn_youtube'] = __('Youtube', 'tn');
    $profile_fields['tn_pinterest'] = __('Pinterest', 'tn');
    $profile_fields['tn_linkedin'] = __('LinkedIn', 'tn');
    $profile_fields['tn_flickr'] = __('Flickr', 'tn');
    $profile_fields['tn_skype'] = __('Skype', 'tn');
    $profile_fields['tn_tumblr'] = __('Tumblr', 'tn');
    $profile_fields['tn_vimeo'] = __('Vimeo', 'tn');
    $profile_fields['tn_rss'] = __('RSS', 'tn');
    return $profile_fields;
}
