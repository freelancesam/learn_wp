<?php
//detect video url
if (!function_exists('tn_detect_video_url')) {
    function tn_detect_video_url($video_url)
    {
        $video_url = strtolower($video_url);

        if (strpos($video_url, 'youtube.com') !== false or strpos($video_url, 'youtu.be') !== false) {
            return 'youtube';
        }
        if (strpos($video_url, 'dailymotion.com') !== false) {
            return 'dailymotion';
        }
        if (strpos($video_url, 'vimeo.com') !== false) {
            return 'vimeo';
        }
        return false;
    }
}

//get youtube id
if (!function_exists('tn_youtube_id')) {
    function tn_youtube_id($video_url)
    {
        $s = array();
        parse_str(parse_url($video_url, PHP_URL_QUERY), $s);

        if (empty($s["v"])) {
            $youtube_sl_explode = explode('?', $video_url);

            $youtube_sl = explode('/', $youtube_sl_explode[0]);
            if (!empty($youtube_sl[3])) {
                return $youtube_sl [3];
            }

            return $youtube_sl [0];
        } else {
            return $s["v"];
        }
    }
}

//youtube time
if (!function_exists('tn_youtube_time')) {
    function tn_youtube_time($video_url)
    {
        $s = array();
        parse_str(parse_url($video_url, PHP_URL_QUERY), $s);
        if (!empty($s["t"])) {
            if (strpos($s["t"], 'm')) {
                $explode_m = explode('m', $s["t"]);
                $min = trim($explode_m[0]);
                $explode_sec = explode('s', $explode_m[1]);
                $sec = trim($explode_sec[0]);
                $start_time = intval($min * 60) + intval($sec);
            } else {
                $explode_s = explode('s', $s["t"]);
                $sec = trim($explode_s[0]);

                $start_time = $sec;
            }
            return '&start=' . $start_time;
        } else {
            return '';
        }
    }
}

//get vimeo id
if (!function_exists('tn_vimeo_id')) {
    function tn_vimeo_id($video_url)
    {
        sscanf(parse_url($video_url, PHP_URL_PATH), '/%d', $vimeo_id);
        return $vimeo_id;
    }
}

//get daily
if (!function_exists('tn_dailymotion_id')) {
    function tn_dailymotion_id($video_url)
    {
        $id = strtok(basename($video_url), '_');
        if (strpos($id, '#video=') !== false) {
            $video_parts = explode('#video=', $id);
            if (!empty($video_parts[1])) {
                return $video_parts[1];
            }
        } else {
            return $id;
        }
    }
}

//render iframe
if (!function_exists('tn_iframe_video')) {
    function tn_iframe_video($post_id)
    {
        $str = '';
        $video_url = get_post_meta($post_id, 'tn_video_url', true);
        if (get_post_format($post_id) != 'video' || empty($video_url)) return;
        $server = tn_detect_video_url($video_url);
        switch ($server) {
            case 'youtube':
                $str .= '<div class="video-wrap thumb-wrap"><iframe width="900" height="505" src="http://www.youtube.com/embed/' . esc_attr(tn_youtube_id($video_url)) . '?feature=oembed&amp;wmode=opaque' . esc_attr(tn_youtube_time($video_url) ). '" allowfullscreen></iframe></div>';
                break;
            case 'vimeo':
                $str .= '<div class="video-wrap thumb-wrap"><iframe  width="900" height="205" src="http://player.vimeo.com/video/' . esc_attr(tn_vimeo_id($video_url)) . '"></iframe></div>';
                break;
            case 'dailymotion':
                $str .= '<div class="video-wrap thumb-wrap"><iframe width="900" height="505" src="http://www.dailymotion.com/embed/video/' . esc_attr(tn_dailymotion_id($video_url)) . '"></iframe></div>';
                break;
        }
        return $str;
    }
}

//render audio post
if (!function_exists('tn_iframe_audio')) {
    function tn_iframe_audio($post_id)
    {
        $str = '';
        $audio_url = get_post_meta($post_id, 'tn_audio_url', true);
        if (get_post_format($post_id) != 'audio' || empty($audio_url)) return;

        $audio_emb = wp_oembed_get($audio_url, array('height' => 230, 'width' => 900));
        $str .= '<div class="audio-wrap thumb-wrap">' . $audio_emb . '</div>';
        return $str;
    }
}

//custom gallery post
add_filter('post_gallery', 'tn_post_gallery', 10, 2);
if (!function_exists('tn_post_gallery')) {
    function tn_post_gallery($output, $attr)
    {
        global $post;

        static $instance = 0;
        $instance++;

        if (isset($attr['orderby'])) {
            $attr['orderby'] = sanitize_sql_orderby($attr['orderby']);
            if (!$attr['orderby'])
                unset($attr['orderby']);
        }

        extract(shortcode_atts(array(
            'order' => 'ASC',
            'orderby' => 'menu_order ID',
            'id' => $post->ID,
            'itemtag' => 'div',
            'icontag' => 'div',
            'captiontag' => 'div',
            'columns' => 3,
            'size' => 'native-image-thumb',
            'include' => '',
            'exclude' => ''
        ), $attr));

        $id = intval($id);
        if ('RAND' == $order)
            $orderby = 'none';

        if (!empty($include)) {
            $include = preg_replace('/[^0-9,]+/', '', $include);
            $_attachments = get_posts(array('include' => $include, 'post_status' => 'inherit', 'post_type' => 'attachment', 'post_mime_type' => 'image', 'order' => $order, 'orderby' => $orderby));

            $attachments = array();
            foreach ($_attachments as $key => $val) {
                $attachments[$val->ID] = $_attachments[$key];
            }
        } elseif (!empty($exclude)) {
            $exclude = preg_replace('/[^0-9,]+/', '', $exclude);
            $attachments = get_children(array('post_parent' => $id, 'exclude' => $exclude, 'post_status' => 'inherit', 'post_type' => 'attachment', 'post_mime_type' => 'image', 'order' => $order, 'orderby' => $orderby));
        } else {
            $attachments = get_children(array('post_parent' => $id, 'post_status' => 'inherit', 'post_type' => 'attachment', 'post_mime_type' => 'image', 'order' => $order, 'orderby' => $orderby));
        }

        if (empty($attachments))
            return '';

        if (is_feed()) {
            $output = "\n";
            foreach ($attachments as $att_id => $attachment)
                $output .= wp_get_attachment_link($att_id, $size, true) . "\n";
            return $output;
        }

        $captiontag = tag_escape($captiontag);

        $output = '<div id="tn-default-gallery' . $id . '" class="clearfix tn-default-gallery galleryid-' . $id . '">';
        foreach ($attachments as $id => $attachment) {
            $image_attributes = wp_get_attachment_image_src($id, $size);
            if ($image_attributes) {
                $output .= '<a href="' . esc_url($image_attributes[0]) . '" title="' . wptexturize($attachment->post_excerpt) . '">';
                $output .= '<img src="' . esc_url($image_attributes[0]) . '" width="' . esc_attr($image_attributes[1]) . '" height="' . esc_attr($image_attributes[2]) . '" alt="' . esc_attr(strip_tags($attachment->post_excerpt)) . '">';
                $output .= '<' . $captiontag . ' class="gallery-caption">' . esc_attr(strip_tags($attachment->post_excerpt)) . '</' . $captiontag . '></a>';
            }
        }

        $output .= '</div><!--#tn default gallery-->';
        return $output;
    }
}

//social like
if(!function_exists('tn_social_like_post')){
    function tn_social_like_post($post)
    {
        global $tn_options;
        $post_url = get_permalink($post->ID);
        $title = get_the_title($post->ID);
        $title_attribute = esc_attr(strip_tags($title));

        if (!is_single()) {
            return false;
        };

        $check = false;

        if(!empty($tn_options['tn_social_like_post'])){
            $check = $tn_options['tn_social_like_post'];
        }

        if (empty($check)) {
            return false;
        };
        $twitter_user = get_the_author_meta('tn_twitter');
        $str = '';
        $str .= '<div class="tn-social-like-post">';
        $str .= '<ul>';
        //twitter
        $str .= '<li class="tn-like-post-twitter">';
        $str .= '<a href="https://twitter.com/share" class="twitter-share-button" data-url="' . $post_url . '" data-text="' . $title_attribute . '" data-via="' . urlencode($twitter_user ? $twitter_user : get_bloginfo('name')) . '" data-lang="en">tweet</a> <script>!function(d,s,id){var js,fjs=d.getElementsByTagName(s)[0];if(!d.getElementById(id)){js=d.createElement(s);js.id=id;js.src="//platform.twitter.com/widgets.js";fjs.parentNode.insertBefore(js,fjs);}}(document,"script","twitter-wjs");</script>';
        $str .= '</li>';
        //facebook
        $str .= '<li class="tn-like-post-facebook">';
        $str .= '<iframe src="http://www.facebook.com/plugins/like.php?href=' . $post_url . '&amp;layout=button_count&amp;show_faces=false&amp;width=105&amp;action=like&amp;colorscheme=light&amp;height=21" style="border:none; overflow:hidden; width:105px; height:21px; background-color:transparent;"></iframe>';
        $str .= '</li>';
        //google
        $str .= '<li  class="tn-like-post-google">';
        $str .= '
                    <div class="g-plusone" data-size="medium" data-href="' . $post_url . '"></div>
                    <script type="text/javascript">
                        (function() {
                            var po = document.createElement("script"); po.type = "text/javascript"; po.async = true;
                            po.src = "https://apis.google.com/js/plusone.js";
                            var s = document.getElementsByTagName("script")[0]; s.parentNode.insertBefore(po, s);
                        })();
                    </script>
                    ';
        $str .= '</li>';
        $str .= '</ul>';
        $str .= '</div>';

        return $str;
    }
}


//post tag + resource.
if (!function_exists('tn_render_single_tags_source')) {
    function tn_render_single_tags_source($post_id)
    {
        $str = '';
        $str_fix = '';

        $tn_source_name = get_post_meta($post_id, 'tn_source_name', true);
        $tn_source_url = get_post_meta($post_id, 'tn_source_url', true);
        $tags = wp_get_post_tags($post_id);

        if (!empty($tags)) {
            $str .= '<div class="single-tag-wrap"><span class="single-tag-title">' . __('Tags:', 'tn') . '</span>';
            foreach ($tags as $tag) {
                $tag_link = get_tag_link($tag->term_id);
                $str .= '<a class="post-meta-tag" href="' . esc_url($tag_link) . '" title="' . esc_attr(strip_tags($tag->name)) . '">' . esc_attr($tag->name) . '</a>';
            }
            $str .= '</div>';
        }
        if (empty($tn_source_name) || empty($tn_source_url)) {
            $str .= '';
        } else {
            $str .= '<div class="single-source-wrap">';
            $str .= '<span class="single-source-title">' . __('Source:', 'tn') . '</span>';
            $str .= '<a class="single-source-link" href="' . esc_url($tn_source_url) . '">' . esc_attr($tn_source_name) . '</a>';
            $str .= '</div>';
        }

        if (!empty($str)) {
            $str_fix = '<div class="single-tags-source-wrap">' . $str . '</div>';
        }

        return $str_fix;
    }
}

//Lets add Open Graph Meta Info
function tn_open_graph_head()
{
    global $post;
    if (is_single()) {

        echo '<meta property="og:title" content="' . get_the_title() . '"/>';
        echo '<meta property="og:type" content="article"/>';
        echo '<meta property="og:url" content="' . get_permalink() . '"/>';
        if (has_post_thumbnail($post->ID)) { //the post does not have featured image, use a default image
            $thumbnail_src = wp_get_attachment_image_src(get_post_thumbnail_id($post->ID), 'feature_medium_thumb');
            echo '<meta property="og:image" content="' . esc_url($thumbnail_src[0]) . '"/>';
        }
    } else {
        $tn_logo = (isset($tn_options['tn_logo'])) ? $tn_options['tn_logo'] : array();
        echo '<meta property="og:site_name" content="' . get_bloginfo('name') . '" />';
        echo '<meta property="og:description" content="' . get_bloginfo('description') . '" />';
        echo '<meta property="og:type" content="website"/>';
        if (!empty($tn_logo['url']))
            echo '<meta property="og:image" content="' . esc_url($tn_logo['url']) . '" />';
    }
}

add_action('wp_head', 'tn_open_graph_head', 1);