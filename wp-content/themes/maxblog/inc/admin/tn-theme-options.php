<?php
if (!function_exists('tn_theme_options')) {
    function tn_theme_options()
    {
        $tn_redux_options = array();

        //general setting
        $tn_redux_options[] = array(
            'title' => __('General', 'tn'),
            'icon' => 'el-icon-network',
            'desc' => __('Select options for General', 'tn'),
            'fields' => array(

                //display meta tags on thumb
                array(
                    'id' => 'tn_meta_thumb_option',
                    'type' => 'select',
                    'title' => __('Meta Tags On Thumbnail', 'tn'),
                    'subtitle' => __('Select type of Meta tag on thumbnail. It affects the whole widget, pages','tn'),
                    'options' => array(
                        'views_count' => __('Total views', 'tn'),
                        'shares_count' => __('Total Shares On Social','tn')
                    ),
                    'default' => 'views_count'
                ),

                array(
                    'id' => 'tn_smooth_display',
                    'type' => 'switch',
                    'title' => __('Smooth Display', 'tn'),
                    'subtitle' => __('Fade-in of images when scrolling down a page', 'tn'),
                    'default' => 0,
                ),
                array(
                    'id' => 'tn_smooth_scroll',
                    'type' => 'switch',
                    'title' => __('Smooth Scroll', 'tn'),
                    'subtitle' => __('Smooth scrolling with the mouse wheel in all browsers', 'tn'),
                    'default' => 0,
                ),
                array(
                    'id' => 'tn_sticky_menu',
                    'type' => 'switch',
                    'title' => __('Sticky Main Navigation', 'tn'),
                    'subtitle' => __('This makes navigation float at the top when the user scrolls up below the fold - essentially making navigation menu always visible', 'tn'),
                    'default' => 1,
                ),

                array(
                    'id' => 'tn_site_meta',
                    'type' => 'switch',
                    'title' => __('Enable Meta Description', 'tn'),
                    'subtitle' => __('You can disable the meta description tag when using the SEO plugin like Yoast. Default for this option is enable', 'tn'),
                    'default' => 1,
                ),

                array(
                    'id' => 'tn_readmore',
                    'type' => 'switch',
                    'title' => __('show read more button', 'tn'),
                    'subtitle' => __('Show read more button in posts, It affects all page but except widgets', 'tn'),
                    'default' => 0
                ),

                array(
                    'id' => 'tn_breadcrumbs',
                    'type' => 'switch',
                    'title' => __('Show Breadcrumbs Bar', 'tn'),
                    'subtitle' => __('Breadcrumbs are a hierarchy of links displayed below the main navigation. It displayed on all pages but home page', 'tn'),
                    'default' => 1,
                ),

                array(
                    'id' => 'tn_rtl',
                    'type' => 'switch',
                    'title' => __('RTL mode (right to left)', 'tn'),
                    'subtitle' => __('Enable this option if you are using right to left writing/reading', 'tn'),
                    'default' => 0,
                ),

                array(
                    'id' => 'tn_ticker',
                    'type' => 'switch',
                    'title' => __('Show Top News Ticker', 'tn'),
                    'subtitle' => __('enable or disable the top bar news ticker BREAKING NEW', 'tn'),
                    'default' => 1,
                ),

                array(
                    'id' => 'tn_ticker_title',
                    'type' => 'text',
                    'required' => array('tn_ticker', '=', '1'),
                    'title' => __('Ticker Title', 'tn'),
                    'subtitle' => __('Input your ticker title', 'tn'),
                    'default' => 'BREAKING NEWS',
                    'validate' => 'no_html'
                ),

                array(
                    'id' => 'tn_ticker_cate',
                    'type' => 'select',
                    'data' => 'categories',
                    'multi' => false,
                    'subtitle' => __('Select the category for news ticker, Leave blank if choose all categories', 'tn'),
                    'required' => array('tn_ticker', '=', '1'),
                    'title' => __('Category fitter', 'tn'),
                    'default' => '',
                ),
                array(
                    'id' => 'tn_ticker_tags',
                    'type' => 'text',
                    'required' => array('tn_ticker', '=', '1'),
                    'title' => __('FILTER BY TAG SLUG', 'tn'),
                    'subtitle' => __('To filter multiple tag slug, enter here the tag slugs separated by commas (example: tag1,tag2,tag3)', 'tn'),
                    'default' => '',
                    'validate' => 'no_html'
                ),
                array(
                    'id' => 'tn_ticker_num',
                    'type' => 'text',
                    'validate' => 'numeric',
                    'required' => array('tn_ticker', '=', '1'),
                    'title' => __('LIMIT POST NUMBER', 'tn'),
                    'subtitle' => __('How many posts you want to show at once, default of this option is 7', 'tn'),
                    'default' => 7,
                ),

                array(
                    'id' => 'tn_google_analytics',
                    'type' => 'textarea',
                    'title' => __('Google Analytics Code', 'tn'),
                    'subtitle' => __('Enter your Google Analytics Code or other tracking code. The code must including script tag', 'tn'),
                    'default' => '',
                ),
            )
        );

        //header settings
        $tn_redux_options[] = array(
            'title' => __('Header', 'tn'),
            'icon' => 'el-icon-credit-card',
            'desc' => __('Select options for your Header', 'tn'),
            'fields' => array(
                array(
                    'id' => 'tn_header_style',
                    'type' => 'select',
                    'title' => __('Header Style', 'tn'),
                    'subtitle' => __('Position of logo and main navigation.', 'tn'),
                    'options' => array(
                        'left' => __('Left', 'tn'),
                        'centered' => __('Centered', 'tn'),
                    ),
                    'default' => 'left',
                ),
                array(
                    'id' => 'tn_menu_top',
                    'type' => 'switch',
                    'title' => __('Show Top Menu', 'tn'),
                    'subtitle' => __('Enable or disable the top menu. this option effected to social bar at top. if disable, social bar at top cannot display.', 'tn'),
                    'default' => 1,
                ),
                array(
                    'id' => 'tn_favicon',
                    'type' => 'media',
                    'url' => true,
                    'title' => __('Site favicon', 'tn'),
                    'subtitle' => __('Upload a favicon image (18 x 18px) .ico', 'tn'),
                ),
                array(
                    'id' => 'tn_logo',
                    'type' => 'media',
                    'url' => true,
                    'title' => __('Header logo', 'tn'),
                    'subtitle' => __('Upload your logo (260 x 70px) .png', 'tn'),
                ),
                array(
                    'id' => 'tn_header_ads',
                    'type' => 'switch',
                    'title' => __('Google Ads/Custom Ads', 'tn'),
                    'subtitle' => __('Select type of ads at top header', 'tn'),
                    'default' => 1,
                    'on' => 'Google Ads',
                    'off' => 'Custom Ads',
                ),
                array(
                    'id' => 'tn_google_ads',
                    'type' => 'textarea',
                    'required' => array('tn_header_ads', '=', '1'),
                    'title' => __('Google Ads Code (728px*90px)', 'tn'),
                    'subtitle' => __('Paste in your entire Google ads Code here', 'tn'),
                ),

                array(
                    'id' => 'tn_custom_ads_img',
                    'type' => 'media',
                    'url' => true,
                    'required' => array('tn_header_ads', '=', '0'),
                    'title' => __('Ads Image ', 'tn'),
                    'subtitle' => __('Enter the image URL', 'tn'),
                ),

                array(
                    'id' => 'tn_custom_ads_url',
                    'type' => 'text',
                    'required' => array('tn_header_ads', '=', '0'),
                    'title' => __('Ads Url ', 'tn'),
                    'subtitle' => __('Enter the custom Ads Url', 'tn'),
                    'validate' => 'url',
                    'default' => '',

                )

            )
        );

        //sidebar setting
        $tn_redux_options[] = array(
            'title' => __('Sidebar', 'tn'),
            'icon' => 'el-icon-indent-left',
            'desc' => __('Select options for sidebar', 'tn'),
            'fields' => array(
                array(
                    'id' => 'tn_sidebar_position',
                    'type' => 'select',
                    'title' => __('Sidebar Position', 'tn'),
                    'subtitle' => __('Specify the sidebar to use by default. This can be overriden per-page or per-post basis when creating a  post', 'tn'),
                    'options' => array(
                        'left' => 'Left',
                        'right' => 'Right',
                    ),
                    'default' => 'right',
                ),
                array(
                    'id' => 'tn_sticky_sidebar',
                    'type' => 'switch',
                    'title' => __('Sticky Sidebar', 'tn'),
                    'subtitle' => __('Making sidebar permanently visible when scrolling up and down. Useful when a sidebar is too tall or too short compared to the rest of the content', 'tn'),
                    'default' => 1,
                )
            )
        );

        //footer setting
        $tn_redux_options[] = array(
            'title' => __('Footer', 'tn'),
            'icon' => 'el-icon-credit-card',
            'desc' => __('The footer uses sidebars to show information. . To add content to the footer head go to the widgets section and drag widget to the Footer Sidebar 1, Footer Sidebar 2 and Footer Sidebar 3 sidebars.', 'tn'),
            'fields' => array(
                array(
                    'id' => 'tn_to_top',
                    'type' => 'switch',
                    'title' => __('Show To Top Button', 'tn'),
                    'subtitle' => __('Enable or disable back to top button', 'tn'),
                    'default' => 1,
                ),
                array(
                    'id' => 'tn_copyright',
                    'type' => 'text',
                    'title' => __('footer copyright text', 'tn'),
                    'subtitle' => __('Enter footer copyright text. HTML and shortcode is allowed', 'tn'),
                ),
            )
        );

        //blog home setting
        $tn_redux_options[] = array(
            'title' => __('Blog', 'tn'),
            'desc' => __('The settings below only apply to homepages that are set to "Your latest posts" in the "Wordpress Settings -> Reading" section.', 'tn'),
            'icon' => 'el-icon-home-alt',
            'fields' => array(
                array(
                    'id' => 'tn_blog_style',
                    'type' => 'select',
                    'subtitle' => __('Select the layout for blog page', 'tn'),
                    'title' => __('Blog Layout', 'tn'),
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
                    'default' => 'style10',
                ),
                array(
                    'title' => __('Show Video Thumbnail', 'tn'),
                    'subtitle' => __('Display video iframe instead of featured image thumbnail. Used for blog page, archives &amp; search results', 'tn'),
                    'id' => 'tn_blog_video',
                    'type' => 'switch',
                    'default' => 0,
                ),
                array(
                    'title' => __('Blog Excerpt Length', 'tn'),
                    'subtitle' => __('How many words in posts contents', 'tn'),
                    'id' => 'tn_blog_excerpt',
                    'type' => 'text',
                    'validate' => 'numeric',
                ),
            )
        );

        //page setting
        $tn_redux_options[] = array(
            'title' => __('Page', 'tn'),
            'icon' => 'el-icon-heart-alt',
            'desc' => __('Select options for your Page', 'tn'),
            'fields' => array(
                array(
                    'id' => 'tn_page_author_style',
                    'type' => 'select',
                    'title' => __('Author Page Layout', 'tn'),
                    'subtitle' => __('Select the layout for author page', 'tn'),
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
                    'default' => 'style1'
                ),
                array(
                    'id' => 'tn_page_author_excerpt',
                    'type' => 'text',
                    'validate' => 'numeric',
                    'title' => __('author excerpt length', 'tn'),
                    'subtitle' => __('Optional - How many words in posts contents', 'tn'),
                ),
                array(
                    'id' => 'tn_page_search_style',
                    'type' => 'select',
                    'title' => __('Search Page Layout', 'tn'),
                    'subtitle' => __('Select the layout for search page', 'tn'),
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
                    'default' => 'style1'
                ),
                array(
                    'id' => 'tn_page_search_excerpt',
                    'type' => 'text',
                    'validate' => 'numeric',
                    'title' => __('Search excerpt length', 'tn'),
                    'subtitle' => __('Optional - How many words in posts contents', 'tn'),
                ),
                array(
                    'id' => 'tn_page_archive_style',
                    'type' => 'select',
                    'title' => __('Archive Page Layout', 'tn'),
                    'subtitle' => __('Select the layout for archive page', 'tn'),
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
                    'default' => 'style1'
                ),
                array(
                    'id' => 'tn_page_archive_excerpt',
                    'type' => 'text',
                    'validate' => 'numeric',
                    'title' => __('Archive excerpt length', 'tn'),
                    'subtitle' => __('Optional - How many words in posts contents', 'tn'),
                ),
            )
        );

        //single post setting
        $tn_redux_options[] = array(
            'title' => __('Single Post', 'tn'),
            'icon' => 'el-icon-file-edit-alt',
            'desc' => __('Select options for single post page', 'tn'),
            'fields' => array(
                array(
                    'id' => 'tn_default_post_style',
                    'type' => 'select',
                    'title' => __('Default Layout Single', 'tn'),
                    'subtitle' => __('Select default layout for single post. This can be overriden per-post basis when creating a post.', 'tn'),
                    'options' => array(
                        'style1' => 'style 1',
                        'style2' => 'style 2',
                    ),
                    'default' => 'style1',
                ),
                array(
                    'id' => 'tn_post_author_box',
                    'type' => 'switch',
                    'title' => __('Show Author Box', 'tn'),
                    'subtitle' => __('Enable or disable the author box', 'tn'),
                    'default' => 1,
                ),
                array(
                    'id' => 'tn_post_paginav',
                    'type' => 'switch',
                    'title' => __('Show Next and Previous Posts', 'tn'),
                    'subtitle' => __('Enable or disable NEXT and PREVIOUS navigation posts', 'tn'),
                    'default' => 1,
                ),
                array(
                    'id' => 'tn_post_comment_box',
                    'type' => 'switch',
                    'title' => __('Show Comments Box', 'tn'),
                    'subtitle' => __('Enable or disable the comments on the pages, Default this option is disable', 'tn'),
                    'default' => 1,
                ),
                //related post
                array(
                    'id' => 'tn_post_related_box',
                    'type' => 'switch',
                    'title' => __('Show Related Box', 'tn'),
                    'subtitle' => __('Enable or disable the related posts on the single post page', 'tn'),
                    'default' => 1
                ),
                array(
                    'id' => 'tn_post_related_num',
                    'required' => array('tn_post_related_box', '=', '1'),
                    'title' => __('Limit Related Post Number', 'tn'),
                    'subtitle' => __('How many related posts you want to show at once', 'tn'),
                    'type' => 'text',
                    'validate' => 'numeric',
                    'default' => 2
                ),
                array(
                    'id' => 'tn_post_related_where',
                    'type' => 'select',
                    'required' => array('tn_post_related_box', '=', '1'),
                    'title' => __('Display Related Posts', 'tn'),
                    'subtitle' => __('What posts should be displayed', 'tn'),
                    'options' => array(
                        'all' => __('From same tags and if no posts found, show from same category', 'tn'),
                        'tags' => __('From same tag', 'tn'),
                        'categories' => __('From same category', 'tn')
                    ),
                    'default' => 'all',
                ),

                array(
                    'id' => 'tn_side_dock',
                    'type' => 'switch',
                    'title' => __('Show Posts Dock', 'tn'),
                    'subtitle' => __('Enable or Disable Posts Dock on the single post page at bottom right', 'tn'),
                    'default' => 0,
                ),
                array(
                    'id' => 'tn_side_dock_title',
                    'type' => 'text',
                    'required' => array('tn_side_dock', '=', '1'),
                    'title' => __('Side Dock Title', 'tn'),
                    'subtitle' => __('Optional - Input Posts Dock Title. default of option is "More Stories"', 'tn'),
                    'default' => __('More Stories', 'tn'),
                    'validate' => 'no_html'
                ),
                array(
                    'id' => 'tn_side_dock_style',
                    'type' => 'select',
                    'required' => array('tn_side_dock', '=', '1'),
                    'title' => __('Side Dock Layout', 'tn'),
                    'subtitle' => __('Select default layout for this option', 'tn'),
                    'options' => array(
                        'style1' => __('Style 1', 'tn'),
                        'style2' => __('Style 2', 'tn'),
                    )
                ),
                array(
                    'id' => 'tn_side_dock_sort',
                    'type' => 'select',
                    'required' => array('tn_side_dock', '=', '1'),
                    'title' => __('Display Side Dock', 'tn'),
                    'subtitle' => __('What posts should be displayed', 'tn'),
                    'options' => array(
                        'rand' => __('Random', 'tn'),
                        'lasted' => __('Lasted Post', 'tn'),
                        'tag' => __('From same tag', 'tn'),
                        'cate' => __('From same category', 'tn'),
                    )
                ),
                array(
                    'id' => 'tn_side_dock_num',
                    'type' => 'text',
                    'validate' => 'numeric',
                    'required' => array('tn_side_dock', '=', '1'),
                    'title' => __('Limit Post Number', 'tn'),
                    'subtitle' => __('How many related posts you want to show at once', 'tn'),
                    'default' => '1'
                ),

                //review position
                array(
                    'id' => 'tn_default_review_position',
                    'type' => 'select',
                    'title' => __('Default Review Box Position', 'tn'),
                    'subtitle' => __('Select default position for score review box. This can be overriden per-post basis when creating a post.', 'tn'),
                    'options' => array(
                        'style1' => __('Full Bottom', 'tn'),
                        'style2' => __('Top Left', 'tn'),
                    ),
                    'default' => 'style1'
                ),

                //social like
                array (
                    'id' => 'tn_social_like_post',
                    'type' => 'switch',
                    'title' => __('SHOW POST LIKE/TWEET/G+', 'tn'),
                    'subtitle' => __('Enable or disable the post like/tweet/g+ on post', 'tn'),
                    'default' => 0,
                ),

                //social share
                array(
                    'id' => 'tn_post_share',
                    'type' => 'switch',
                    'title' => __('Post Sharing Bar', 'tn'),
                    'subtitle' => __('Enable or disable share on social bar', 'tn'),
                    'default' => 1,
                ),
                array(
                    'id' => 'tn_post_share_top',
                    'type' => 'switch',
                    'title' => __('Post Sharing At Top', 'tn'),
                    'subtitle' => __('Enable or disable share on social bar at top of single content', 'tn'),
                    'required' => array('tn_post_share', '=', '1'),
                    'default' => 1,
                ),
                array(
                    'id' => 'tn_post_share_bottom',
                    'type' => 'switch',
                    'title' => __('Post Sharing At Bottom ', 'tn'),
                    'subtitle' => __('Enable or disable share on social bar at bottom of single content', 'tn'),
                    'required' => array('tn_post_share', '=', '1'),
                    'default' => 0,
                ),
                array(
                    'id' => 'tn_post_share_aside',
                    'type' => 'switch',
                    'title' => __('Post Sharing At Left Aside ', 'tn'),
                    'subtitle' => __('Enable or disable share on social bar at fixed left of single page', 'tn'),
                    'required' => array('tn_post_share', '=', '1'),
                    'default' => 0,
                ),
                array(
                    'id' => 'tn_post_to_facebook',
                    'type' => 'switch',
                    'title' => __('Share On Facebook ', 'tn'),
                    'subtitle' => __('Enable or disable share on Facebook, This default of option is enable', 'tn'),
                    'required' => array('tn_post_share', '=', '1'),
                    'default' => 1,
                ),
                array(
                    'id' => 'tn_post_to_twitter',
                    'type' => 'switch',
                    'title' => __('Share On Twitter', 'tn'),
                    'subtitle' => __('Enable or disable share on Twitter, This default of option is enable', 'tn'),
                    'required' => array('tn_post_share', '=', '1'),
                    'default' => 1,
                ),
                array(
                    'id' => 'tn_post_to_google_plus',
                    'type' => 'switch',
                    'title' => __('Share On Google Plus', 'tn'),
                    'subtitle' => __('Enable or disable share on Google Plus, This default of option is enable', 'tn'),
                    'required' => array('tn_post_share', '=', '1'),
                    'default' => 1,
                ),
                array(
                    'id' => 'tn_post_to_pinterest',
                    'type' => 'switch',
                    'title' => __('Share On Pinterest', 'tn'),
                    'subtitle' => __('Enable or disable share on Pinterest, This default of option is disable', 'tn'),
                    'required' => array('tn_post_share', '=', '1'),
                    'default' => 0,
                ),
                array(
                    'id' => 'tn_post_to_linkedin',
                    'type' => 'switch',
                    'title' => __('Share On LinkedIn', 'tn'),
                    'subtitle' => __('Enable or disable share on LinkedIn, This default of option is disable', 'tn'),
                    'required' => array('tn_post_share', '=', '1'),
                    'default' => 0,
                ),
                array(
                    'id' => 'tn_post_to_tumblr',
                    'type' => 'switch',
                    'title' => __('Share On Tumblr', 'tn'),
                    'subtitle' => __('Enable or disable share on Tumblr, This default of option is disable', 'tn'),
                    'required' => array('tn_post_share', '=', '1'),
                    'default' => 0,
                ),
                array(
                    'id' => 'tn_post_to_digg',
                    'type' => 'switch',
                    'title' => __('Share On Digg', 'tn'),
                    'subtitle' => __('Enable or disable share on Digg, This default of option is disable', 'tn'),
                    'required' => array('tn_post_share', '=', '1'),
                    'default' => 0,
                ),
            )
        );


        //Typography
        $tn_redux_options[] = array(
            'title' => __('Typography', 'tn'),
            'icon' => 'el-icon-pencil-alt',
            'desc' => __('Selecting a font will show a basic preview. Go to <a href="http://www.google.com/webfonts" target="_blank">google fonts directory</a> for more details. It is highly recommended that you choose fonts that have similar heights to the default fonts. Click Resset Section to restore default theme font.', 'tn'),
            'fields' => array(
                //body font
                array(
                    'id' => 'tn_body_font',
                    'type' => 'typography',
                    'title' => __('Body Font', 'tn'),
                    'google' => true,
                    'font-backup' => false,
                    'text-align' => false,
                    'color' => false,
                    'text-transform' => true,
                    'letter-spacing' => true,
                    'line-height' => false,
                    'units' => 'px',
                    'subtitle' => __('Select the font of the body text. This font of option effects almost every content on the theme', 'tn'),
                    'default' => array(
                        'font-size' => '14px',
                        'google' => true,
                        'font-family' => 'Helvetica, Arial, sans-serif',
                    )
                ),

                array(
                    'id' => 'tn_title_font',
                    'type' => 'typography',
                    'title' => __('Title Post Font', 'tn'),
                    'subtitle' => __('Select font for post titles', 'tn'),
                    'google' => true,
                    'font-backup' => false,
                    'text-align' => false,
                    'color' => false,
                    'text-transform' => true,
                    'letter-spacing' => true,
                    'line-height' => true,
                    'units' => 'px',
                    'default' => array(
                        'font-size' => '19px',
                        'line-height' => '27px',
                        'google' => true,
                        'font-family' => 'Oswald',
                        'font-weight' => '400',
                        'text-transform' => 'capitalize'
                    )
                ),

                array(
                    'id' => 'tn_small_title_font',
                    'type' => 'typography',
                    'title' => __('Small Title Post Font', 'tn'),
                    'subtitle' => __('Select font for small post titles', 'tn'),
                    'google' => true,
                    'font-backup' => false,
                    'text-align' => false,
                    'color' => false,
                    'text-transform' => true,
                    'letter-spacing' => true,
                    'line-height' => true,
                    'units' => 'px',
                    'default' => array(
                        'font-size' => '14px',
                        'google' => true,
                        'line-height' => '19px',
                        'font-family' => 'Oswald',
                        'font-weight' => '400',
                        'text-transform' => 'capitalize'
                    )
                ),

                array(
                    'id' => 'tn_meta_font',
                    'type' => 'typography',
                    'title' => __('Meta Tags Font', 'tn'),
                    'google' => true,
                    'font-backup' => false,
                    'text-align' => false,
                    'color' => false,
                    'text-transform' => true,
                    'letter-spacing' => true,
                    'font-style' => true,
                    'line-height' => false,
                    'units' => 'px',
                    'subtitle' => __('Select the font of meta tags', 'tn'),
                    'default' => array(
                        'font-size' => '10px',
                        'google' => true,
                        'font-weight' => '400',
                        'font-family' => 'Roboto',
                        'text-transform' => 'uppercase',
                    )
                ),

                array(
                    'id' => 'tn_menu_font',
                    'type' => 'typography',
                    'title' => __('Menu Font', 'tn'),
                    'google' => true,
                    'font-backup' => false,
                    'text-align' => false,
                    'color' => false,
                    'text-transform' => false,
                    'letter-spacing' => true,
                    'line-height' => false,
                    'units' => 'px',
                    'subtitle' => __('Select the font of main, top navigation', 'tn'),
                    'default' => array(
                        'font-size' => '14px',
                        'google' => true,
                        'font-weight' => '700',
                        'font-family' => 'Roboto'
                    )
                ),

                array(
                    'id' => 'tn_header_title_font',
                    'type' => 'typography',
                    'title' => __('Module - Widget Title Font', 'tn'),
                    'google' => true,
                    'font-backup' => false,
                    'text-align' => false,
                    'color' => false,
                    'text-transform' => false,
                    'letter-spacing' => true,
                    'line-height' => false,
                    'units' => 'px',
                    'subtitle' => __('Select the font of the widget title', 'tn'),
                    'default' => array(
                        'font-size' => '14px',
                        'google' => true,
                        'font-weight' => '700',
                        'font-family' => 'Roboto',
                    )
                ),
            ),
        );

        //Social
        $tn_redux_options[] = array(
            'title' => __('Page Social', 'tn'),
            'icon' => 'el-icon-group-alt',
            'desc' => __('These are options for setting up the SITE SOCIAL. To add AUTHOR SOCIAL, go to the Users -> Your Profile', 'tn'),
            'fields' => array(
                array(
                    'id' => 'tn_social',
                    'type' => 'switch',
                    'title' => __('Social', 'tn'),
                    'subtitle' => __('Enable or disable sites social', 'tn'),
                    'default' => 1,
                ),
                array(
                    'id' => 'tn_facebook',
                    'type' => 'text',
                    'required' => array('tn_social', '=', '1'),
                    'validate' => 'url',
                    'title' => __('Facebook URL ', 'tn'),
                    'subtitle' => __('The URL to your account page', 'tn'),
                ),
                array(
                    'id' => 'tn_twitter',
                    'type' => 'text',
                    'validate' => 'url',
                    'required' => array('tn_social', '=', '1'),
                    'title' => __('Twitter URL ', 'tn'),
                    'subtitle' => __('The URL to your account page', 'tn'),
                ),
                array(
                    'id' => 'tn_youtube',
                    'type' => 'text',
                    'validate' => 'url',
                    'required' => array('tn_social', '=', '1'),
                    'title' => __('Youtube URL ', 'tn'),
                    'subtitle' => __('The URL to your account page', 'tn'),
                ),
                array(
                    'id' => 'tn_google_plus',
                    'type' => 'text',
                    'validate' => 'url',
                    'required' => array('tn_social', '=', '1'),
                    'title' => __('Google+ URL ', 'tn'),
                    'subtitle' => __('The URL to your account page', 'tn'),
                ),
                array(
                    'id' => 'tn_vimeo',
                    'type' => 'text',
                    'validate' => 'url',
                    'required' => array('tn_social', '=', '1'),
                    'title' => __('Vimeo URL ', 'tn'),
                    'subtitle' => __('The URL to your account page', 'tn'),
                ),
                array(
                    'id' => 'tn_flickr',
                    'type' => 'text',
                    'validate' => 'url',
                    'required' => array('tn_social', '=', '1'),
                    'title' => __('Flickr URL ', 'tn'),
                    'subtitle' => __('The URL to your account page', 'tn'),
                ),
                array(
                    'id' => 'tn_linkedin',
                    'type' => 'text',
                    'validate' => 'url',
                    'required' => array('tn_social', '=', '1'),
                    'title' => __('LinkedIN URL ', 'tn'),
                    'subtitle' => __('The URL to your account page', 'tn'),
                ),
                array(
                    'id' => 'tn_skype',
                    'type' => 'text',
                    'validate' => 'url',
                    'required' => array('tn_social', '=', '1'),
                    'title' => __('Skype URL ', 'tn'),
                    'subtitle' => __('The URL to your account page', 'tn'),
                ),
                array(
                    'id' => 'tn_pinterest',
                    'type' => 'text',
                    'validate' => 'url',
                    'required' => array('tn_social', '=', '1'),
                    'title' => __('Pinterest URL ', 'tn'),
                    'subtitle' => __('The URL to your account page', 'tn'),
                ),
                array(
                    'id' => 'tn_tumblr',
                    'type' => 'text',
                    'validate' => 'url',
                    'required' => array('tn_social', '=', '1'),
                    'title' => __('Tumblr URL ', 'tn'),
                    'subtitle' => __('The URL to your account page', 'tn'),
                ),
                array(
                    'id' => 'tn_rss',
                    'type' => 'text',
                    'validate' => 'url',
                    'required' => array('tn_social', '=', '1'),
                    'title' => __('Rss URL ', 'tn'),
                    'subtitle' => __('The URL to your account page', 'tn'),
                ),
            )
        );

        //background and color
        $tn_redux_options[] = array(
            'title' => __('Color &amp; BG', 'tn'),
            'icon' => 'el-icon-picture',
            'desc' => __('Select options for color, site layout and background', 'tn'),
            'fields' => array(

                //site layout
                array(
                    'id' => 'tn_site_layout',
                    'type' => 'select',
                    'title' => __('Site Layout', 'tn'),
                    'subtitle' => __('Select whether you want a boxed or a full-width layout. It affects every page and the whole layout.', 'tn'),
                    'options' => array(
                        'tn-layout-full' => __('Full Page', 'tn'),
                        'tn-layout-boxed' => __('Boxed Style 1', 'tn'),
                        'tn-layout-boxed1' => __('Boxed Style 2', 'tn'),
                    ),
                    'default' => 'tn-layout-full',
                ),

                // background
                array(
                    'id' => 'tn_background',
                    'type' => 'background',
                    'transparent' => false,
                    'title' => __('Body Background', 'tn'),
                    'required' => array('tn_site_layout', '!=', 'tn-layout-full'),
                    'subtitle' => __('Body background with image, color, etc', 'tn'),
                    'default' => array(
                        'background-color' => '#fff',
                    ),
                    'output' => array('body'),
                ),

                //color
                array(
                    'id' => 'tn_global_color',
                    'type' => 'color',
                    'transparent' => false,
                    'title' => __('Global Color', 'tn'),
                    'validate' => 'color',
                    'subtitle' => __('It is the main color for the theme. It will be used for all links, menu, category overlays, main page and many contrasting elements', 'tn'),
                    'default' => '#EC4C51',
                ),
            )
        );

        $tn_redux_options[] = array(
            'title' => __('Custom Css', 'tn'),
            'icon' => 'el-icon-website',
            'desc' => __('Custom CSS will be added at end of all other customizations and thus can be used to overwrite rules', 'tn'),
            'fields' => array(
                array(
                    'id' => 'tn_custom_css',
                    'type' => 'ace_editor',
                    'title' => __('CSS Code', 'tn'),
                    'subtitle' => __('Enter your CSS code here.', 'tn'),
                    'mode' => 'css',
                    'options' => array('minLines'=> 20, 'maxLines' => 40),
                    'theme' => 'monokai',
                    'default' => '',
                    'validate' => 'css'
                ),
            ),
        );

        $tn_redux_options[] = array(
            'title'  => __( 'Import / Export', 'tn' ),
            'desc'   => __( 'Import and Export your settings from file, text or URL.', 'tn' ),
            'icon'   => 'el-icon-refresh',
            'fields' => array(
                array(
                    'id'         => 'tn-import-export',
                    'type'       => 'import_export',
                    'title'      => 'Import Export',
                    'subtitle'   => 'Save and restore your Options',
                    'full_width' => false,
                ),
            ),
        );

        return $tn_redux_options;
    }
}