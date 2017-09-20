<?php

//render post format
if (!function_exists('tn_format')) {
    function tn_format($post)
    {
        $str = '';
        $format = get_post_format($post->ID);
        switch ($format) {
            case false :
                $str .= '<span class="post-format"><i class="fa fa-file-text"></i></span>';
                break;
            case 'video' :
                $str .= '<span class="post-format post-video"><i class="fa fa-play-circle-o"></i></span>';
                break;
            case  'image' :
                $str .= '<span class="post-format"><i class="fa fa-picture-o"></i></span>';
                break;
            case  'gallery' :
                $str .= '<span class="post-format"><i class="fa fa-camera"></i></span>';
                break;
            case 'quote' :
                $str .= '<span class="post-format"><i class="fa fa-quote-left"></i></span>';
                break;
            case 'aside' :
                $str .= '<span class="post-format"><i class="fa fa-file-text-o"></i></span>';
                break;
            case 'audio' :
                $str .= '<span class="post-format post-audio"><i class="fa fa-music"></i></span>';
                break;
            case 'link' :
                $str .= '<span class="post-format"><i class="fa fa-link"></i></span>';
                break;
        };
        return $str;
    }
}

//render post meta
if (!function_exists('tn_meta')) {
    function tn_meta($post, $meta_options = array())
    {
        extract(shortcode_atts(
            array(
                'date' => false,
                'author' => false,
                'categories' => false,
                'comments' => false,
                'views' => false,
                'tags' => false,
            ), $meta_options));

        $str = '';
        $str .= '<ul class="post-meta">';
        if ($date) {
            $date_unix = get_the_time('U', $post->ID);
            $str .= '<li class="date-post-meta"><span>/</span><time itemprop="dateCreated" datetime="' . date(DATE_W3C, $date_unix) . '" >' . get_the_date('', $post->ID) . '</time></li>';
        };

        if ($author) {
            $str .= '<li><span>/</span><a itemprop="author" href = "' . get_author_posts_url(get_the_author_meta('ID', $post->post_author)) . '" >' . get_the_author_meta('display_name', $post->post_author) . '</a></li>';
        }
        if (comments_open($post->ID) && $comments) {
            $count_comment = get_comments_number($post->ID);
            if (empty($count_comment)) {
                $str .= ' <li class="comment-post-meta"><span>/</span><a href="' . get_comments_link($post->ID) . '" >' . __('No Comment', 'tn') . '</a></li>';
            } else {
                $str .= ' <li class="comment-post-meta"><span>/</span><a href="' . get_comments_link($post->ID) . '" >' . intval($count_comment) . __(' Comments', 'tn') . '</a></li>';
            }
        }
        if ($views) {
            $count_views = tn_get_post_views($post->ID);
            if (!empty($count_views)) {
                $str .= '<li><span>/</span>' . intval(tn_get_post_views($post->ID)) . ' ' . __('views', 'tn') . ' </li>';
            }
        }

        if ($tags) {
            $str .= tn_get_post_tags($post->ID);
        }
        $str .= '</ul> ';
        $str .= '<meta itemprop="interactionCount" content="UserComments:' . get_comments_number($post->ID) . '"/>';

        return $str;
    }
}

//cate tag list
if (!function_exists('tn_cate_tag')) {
    function tn_cate_tag($post, $is_single = false)
    {
        $str = '';
        $categories = get_the_category($post->ID);
        if ($categories) {
            $str .= '<ul class="post-categories">';
            foreach ($categories as $category) {
                $str .= '<li><a href="' . get_category_link($category->term_id) . '" title="' . esc_attr(strip_tags($category->name)) . '">' . esc_attr($category->cat_name) . '</a></li>';
                if (!$is_single) break; //only show 1 category
            }
            $str .= '</ul>';
        }
        return $str;
    }
}

//get subcategory
if (!function_exists('tn_sub_cate')) {
    function tn_sub_cate($options)
    {
        if (empty($options['cate_id']) || $options['cate_id'] == 'all') {
            $options['cate_id'] = '';
        }
        $str = '';
        if (!isset($options['num_child_cate'])) $options['num_child_cate'] = '';
        $args = array(
            'parent' => $options['cate_id'],
            'number' => $options['num_child_cate'],
            'hide_empty' => 0,
        );
        $categories = get_categories($args);
        if (!empty($categories)) {
            $str .= '<div class="sub-cate-wrap"><ul>';
            foreach ($categories as $category) {
                $str .= '<li class="tn-category-' . $category->term_id . '" ><a href="' . get_category_link($category->term_id) . '" title="' . esc_attr(strip_tags($category->name)) . '">' . esc_attr($category->cat_name) . '</a></li>';
            }
            $str .= '</ul></div>';
        }
        return $str;
    }
}

//readmore
if (!function_exists('tn_readmore')) {
    function tn_readmore($post)
    {
        return '<a class="readmore"  href="' . get_permalink($post->ID) . '" title="' . esc_attr(strip_tags(get_the_title($post->ID))) . '" rel="bookmark">' . __('Read more', 'tn') . '</a>';
    }
}

//has review
if (!function_exists('tn_has_review')) {
    function tn_has_reviews($post_id)
    {
        $tn_as = get_post_meta($post_id, 'tn_as', true);
        $tn_enable_review = get_post_meta($post_id, 'tn_enable_review', true);
        if (($tn_as) && ($tn_enable_review)) return true; else return false;
    }
}

//review score
if (!function_exists('tn_score')) {
    function tn_score($post_id)
    {
        $tn_as = get_post_meta($post_id, 'tn_as', true);
        return '<div class="review-score">' . esc_attr($tn_as) . '</div>';
    }
}

//flickr data
if (!function_exists('tn_flickr_data')) {
    function tn_flickr_data($flickr_id, $num_images = 9, $tags = '')
    {
        $data = wp_remote_get('http://api.flickr.com/services/feeds/photos_public.gne?format=json&id=' . urlencode($flickr_id) . '&nojsoncallback=1&tags=' . urlencode($tags));
        if (is_wp_error($data) OR !$data['body'])
            return array();
        $data['body'] = str_replace("\\'", "'", $data['body']);
        $content = json_decode($data['body'], true);
        if (is_array($content)) {
            $content = array_slice($content['items'], 0, $num_images);
            foreach ($content as $i => $v) {
                $content[$i]['media'] = preg_replace('/_m\.(jp?g|png|gif)$/', '_s.\\1', $v['media']['m']);
            }
            return $content;
        } else
            return array();
    }
}

//Ajax Pagination
if (!function_exists('tn_ajax_pagination')) {
    function tn_ajax_pagination($id, $pagination = "next_prev")
    {
        $str = '';
        switch ($pagination) {
            case "next_prev" :
                $str .= '<div class="next-prev-wrap">';
                $str .= '<a href="#" class="tn-ajax-prev ajax-disable" id="prev_' . $id . '"><i class="fa fa-angle-double-left"></i></a>';
                $str .= '<a href="#" class="tn-ajax-next" id="next_' . $id . '"><i class="fa fa-angle-double-right"></i></a>';
                $str .= '</div>';
                break;
            case 'loadmore':
                $str .= '<div class="loadmore-wrap">';
                $str .= '<a href="#"  class="tn-ajax-loadmore" id="loadmore_' . $id . '">' . __('Load more', 'tn') . '</a>';
                $str .= '<div class="loadmore-img-wrap">';
                $str .= '<div class="loadmore-img"></div>';
                $str .= '</div>';
                $str .= '</div>';
                break;
        }
        return $str;
    }
}

//get nav pagination
if (!function_exists('tn_get_navpagi')) {
    function tn_get_navpagi()
    {
        global $wp_query, $wp_rewrite;
        if (is_singular() || ($wp_query->max_num_pages < 2)) return;
        $str = '';
        $str .= '<div class="pagination">';
        $wp_query->query_vars['paged'] > 1 ? $current = $wp_query->query_vars['paged'] : $current = 1;
        $pagination = array(
            'base' => @add_query_arg('paged', '%#%'),
            'format' => '',
            'total' => $wp_query->max_num_pages,
            'current' => $current,
            'prev_text' => '<i class="fa fa-angle-double-left"></i>',
            'next_text' => '<i class="fa fa-angle-double-right"></i>',
            'type' => 'plain'
        );
        if ($wp_rewrite->using_permalinks())
            $pagination['base'] = user_trailingslashit(trailingslashit(remove_query_arg('s', get_pagenum_link(1))) . 'page/%#%/', 'paged');
        if (!empty($wp_query->query_vars['s']))
            $pagination['add_args'] = array('s' => urlencode(get_query_var('s')));
        $str .= paginate_links($pagination);
        $str .= '</div>';
        return $str;
    }
}

//get single navigation
if (!function_exists('tn_get_singlepagi')) {
    function tn_get_singlepagi()
    {
        if (!is_singular()) return;
        $previous = (is_attachment()) ? get_post(get_post()->post_parent) : get_adjacent_post(false, '', true);
        $next = get_adjacent_post(false, '', false);
        if (!$next && !$previous) {
            return;
        }
        $str = '';
        $str .= ' <div class="single-nav clearfix" role="navigation">';
        $str .= get_previous_post_link('<div class="single-previous"><span class="prev-article">previous article</span>%link</div>', __('<div class="single-nav-title-wrap single-nav-left"><span class="single-nav-title">%title</span></div>', 'previous article', 'tn'));
        $str .= get_next_post_link('<div class="single-next"><span class="next-article">next article</span>%link</div>', __('<div class="single-nav-title-wrap single-nav-right"><span class="single-nav-title">%title</span></div>', 'next article', 'tn'));
        $str .= '</div>';
        return $str;
    }
}

//open row
if (!function_exists('tn_open_row')) {
    function tn_open_row($col, $count)
    {
        $str = '';
        if (($col == 2 && $count % 2 == 0) || ($col == 3 && $count % 3 == 0))
            $str = '<div class="row-fluid clearfix"><!--row fluid -->';
        return $str;
    }
}

//close row
if (!function_exists('tn_close_row')) {
    function tn_close_row($col, $count, $max)
    {
        $str = '';
        if (($col == 2 && $count % 2 == 1) || ($col == 3 && $count % 3 == 2) || ($count == $max - 1)) {
            $str = '</div><!--#row fluid -->';
        }
        return $str;
    }
}

//create slider data
if (!function_exists('tn_slider_data')) {
    function tn_slider_data($id, $slider_options = array())
    {
        global $tn_slider_data;
        foreach ($slider_options as $i => $v) {
            $tn_slider_data[$id][$i] = $v;
        }
        wp_localize_script('tn-script', 'tn_slider_data', $tn_slider_data);
    }
}

//render social icon
if (!function_exists('tn_social_icon')) {
    function tn_social_icon($data_social, $new_tab = true)
    {
        if (empty($data_social)) return false;
        if ($new_tab == true) $newtab = 'target="_blank"';
        extract(shortcode_atts(
                array(
                    'tn_facebook' => '',
                    'tn_google_plus' => '',
                    'tn_twitter' => '',
                    'tn_youtube' => '',
                    'tn_pinterest' => '',
                    'tn_linkedin' => '',
                    'tn_flickr' => '',
                    'tn_skype' => '',
                    'tn_tumblr' => '',
                    'tn_vimeo' => '',
                    'tn_rss' => '',
                ), $data_social
            )
        );

        $str = '';

        $str .= '<div class="social-bar-wrapper">';
        if (!empty($tn_facebook))
            $str .= '<a title="Facebook" href="' . esc_url($tn_facebook) . '" ' . $newtab . '><i class="fa fa-facebook color-facebook"></i></a>';
        if (!empty($tn_twitter))
            $str .= '<a title="Twitter" href="' . esc_url($tn_twitter) . '" ' . $newtab . '><i class="fa fa-twitter color-twitter"></i></a>';
        if (!empty($tn_google_plus))
            $str .= '<a title="Google+" href="' . esc_url($tn_google_plus) . '" ' . $newtab . '><i class="fa fa-google-plus color-google"></i></a>';
        if (!empty($tn_youtube))
            $str .= '<a title="Youtube" href="' . esc_url($tn_youtube) . '" ' . $newtab . '><i class="fa fa-youtube color-youtube"></i></a>';
        if (!empty($tn_pinterest))
            $str .= '<a title="Pinterest" href="' . esc_url($tn_pinterest) . '" ' . $newtab . '><i class="fa fa-pinterest color-pinterest"></i></a>';
        if (!empty($tn_linkedin))
            $str .= '<a title="LinkedIn" href="' . esc_url($tn_linkedin) . '" ' . $newtab . '><i class="fa fa-linkedin color-linkedin"></i></a>';
        if (!empty($tn_flickr))
            $str .= '<a title="Flickr" href="' . esc_url($tn_flickr) . '" ' . $newtab . '><i class="fa fa-flickr color-flickr"></i></a>';
        if (!empty($tn_skype))
            $str .= '<a title="Skype" href="' . esc_url($tn_skype) . '" ' . $newtab . '><i class="fa fa-skype color-skype"></i></a>';
        if (!empty($tn_tumblr))
            $str .= '<a title="Tumblr" href="' . esc_url($tn_tumblr) . '" ' . $newtab . '><i class="fa fa-tumblr color-tumblr"></i></a>';
        if (!empty($tn_vimeo))
            $str .= '<a title="Vimeo" href="' . esc_url($tn_vimeo) . '" ' . $newtab . '><i class="fa fa-vimeo-square color-vimeo"></i></a>';
        if (!empty($tn_rss))
            $str .= '<a title="Rss" href="' . esc_url($tn_rss) . '" ' . $newtab . '><i class="fa fa-rss color-rss"></i></a>';
        $str .= '</div>';

        return $str;
    }
}

//get website social
if (!function_exists('tn_web_social')) {
    function tn_web_social()
    {
        global $tn_options;
        $data_social = array();
        if ($tn_options['tn_social'] == 1) {
            $data_social['tn_facebook'] = (isset($tn_options['tn_facebook'])) ? $tn_options['tn_facebook'] : '';
            $data_social['tn_twitter'] = (isset($tn_options['tn_twitter'])) ? $tn_options['tn_twitter'] : '';
            $data_social['tn_youtube'] = (isset($tn_options['tn_youtube'])) ? $tn_options['tn_youtube'] : '';
            $data_social['tn_google_plus'] = (isset($tn_options['tn_google_plus'])) ? $tn_options['tn_google_plus'] : '';
            $data_social['tn_vimeo'] = (isset($tn_options['tn_vimeo'])) ? $tn_options['tn_vimeo'] : '';
            $data_social['tn_flickr'] = (isset($tn_options['tn_flickr'])) ? $tn_options['tn_flickr'] : '';
            $data_social['tn_linkedin'] = (isset($tn_options['tn_linkedin'])) ? $tn_options['tn_linkedin'] : '';
            $data_social['tn_skype'] = (isset($tn_options['tn_skype'])) ? $tn_options['tn_skype'] : '';
            $data_social['tn_pinterest'] = (isset($tn_options['tn_pinterest'])) ? $tn_options['tn_pinterest'] : '';
            $data_social['tn_tumblr'] = (isset($tn_options['tn_tumblr'])) ? $tn_options['tn_tumblr'] : '';
            $data_social['tn_rss'] = (isset($tn_options['tn_rss'])) ? $tn_options['tn_rss'] : '';
        }
        return $data_social;
    }
}

//get author social
if (!function_exists('tn_author_social')) {
    function tn_author_social($author_id)
    {
        $str = '';
        $social_data = array();
        $social_data['tn_facebook'] = get_the_author_meta('tn_facebook', $author_id);
        $social_data['tn_twitter'] = get_the_author_meta('tn_twitter', $author_id);
        $social_data['tn_google_plus'] = get_the_author_meta('tn_google_plus', $author_id);
        $social_data['tn_youtube'] = get_the_author_meta('tn_youtube', $author_id);
        $social_data['tn_pinterest'] = get_the_author_meta('tn_pinterest', $author_id);
        $social_data['tn_linkedin'] = get_the_author_meta('tn_linkedin', $author_id);
        $social_data['tn_flickr'] = get_the_author_meta('tn_flickr', $author_id);
        $social_data['tn_skype'] = get_the_author_meta('tn_skype', $author_id);
        $social_data['tn_tumblr'] = get_the_author_meta('tn_tumblr', $author_id);
        $social_data['tn_vimeo'] = get_the_author_meta('tn_vimeo', $author_id);
        $social_data['tn_rss'] = get_the_author_meta('tn_rss', $author_id);

        $str .= '<div class="author-social-wrap">';
        $str .= tn_social_icon($social_data, true);
        $str .= '</div>';
        return $str;
    }
}

//share post to social
if (!function_exists('tn_share_to_social')) {
    function tn_share_to_social($post)
    {
        global $tn_options;
        if (empty($tn_options['tn_post_share'])) return;

        $twitter_user = get_the_author_meta('tn_twitter');
        $image = wp_get_attachment_image_src(get_post_thumbnail_id($post->ID), 'blog_classic_thumb');

        $str = '';
        if (!empty($tn_options['tn_post_to_facebook']))
            $str .= '<a class="share-to-social color-facebook" href="http://www.facebook.com/sharer.php?u=' . urlencode(get_permalink($post->ID)) . '" onclick="window.open(this.href, \'mywin\',
\'left=50,top=50,width=600,height=350,toolbar=0\'); return false;"><i class="fa fa-facebook"></i><span class="share-title">Facebook</span></a>';
        if (!empty($tn_options['tn_post_to_twitter']))
            $str .= '<a class="share-to-social color-twitter" href="https://twitter.com/intent/tweet?text=' . urlencode(strip_tags(get_the_title($post->ID))) . '&amp;url=' . urlencode(get_permalink($post->ID)) . '&amp;via=' . urlencode($twitter_user ? $twitter_user : get_bloginfo('name')) . '" onclick="window.open(this.href, \'mywin\',
\'left=50,top=50,width=600,height=350,toolbar=0\'); return false;"><i class="fa fa-twitter"></i><span class="share-title">Twitter</span></a>';
        if (!empty($tn_options['tn_post_to_google_plus']))
            $str .= ' <a class="share-to-social color-google" href="http://plus.google.com/share?url=' . urlencode(get_permalink($post->ID)) . '" onclick="window.open(this.href, \'mywin\',
\'left=50,top=50,width=600,height=350,toolbar=0\'); return false;"><i class="fa fa-google-plus"></i><span class="share-title">Google +</span></a>';

        if (!empty($tn_options['tn_post_to_pinterest'])) {
            $str .= '<a class="share-to-social color-pinterest" href="http://pinterest.com/pin/create/button/?url=' . urlencode(get_permalink($post->ID)) . '&amp;media=' . (!empty($image[0]) ? $image[0] : '') . '" onclick="window.open(this.href, \'mywin\',
\'left=50,top=50,width=600,height=350,toolbar=0\'); return false;"><i class="fa fa-pinterest"></i><span class="share-title">Pinterest</span></a>';
        }
        if (!empty($tn_options['tn_post_to_tumblr'])) {
            $str .= ' <a class="share-to-social color-tumblr" href="http://www.tumblr.com/share/link?url=' . urlencode(get_permalink($post->ID)) . '&amp;name=' . urlencode(strip_tags(get_the_title($post->ID))) . '&amp;description=' . urlencode(strip_tags(get_the_title($post->ID))) . '" onclick="window.open(this.href, \'mywin\',
\'left=50,top=50,width=600,height=350,toolbar=0\'); return false;"><i class="fa fa-tumblr"></i><span class="share-title">Tumbr</span></a>';
        }
        if (!empty($tn_options['tn_post_to_linkedin'])) {
            $str .= '  <a class="share-to-social color-linkedin" href="http://linkedin.com/shareArticle?mini=true&amp;url=' . urlencode(get_permalink($post->ID)) . '&amp;title=' . urlencode(strip_tags(get_the_title($post->ID))) . '" onclick="window.open(this.href, \'mywin\',
\'left=50,top=50,width=600,height=350,toolbar=0\'); return false;"><i class="fa fa-linkedin"></i><span class="share-title">Linked In</span></a>';
        }
        if (!empty($tn_options['tn_post_to_digg']))
            $str .= '<a class="share-to-social color-digg" href="http://digg.com/submit?phase=2&amp;url=' . urlencode(get_permalink($post->ID)) . '&amp;bodytext=&amp;tags=&amp;title=' . urlencode(strip_tags(get_the_title($post->ID))) . '" target="_blank" onclick="window.open(this.href, \'mywin\',
\'left=50,top=50,width=600,height=350,toolbar=0\'); return false;"><i class="fa fa-digg"></i><span class="share-title">digg</span></a>';

        return $str;

    }
}


//share post to social aside
if (!function_exists('tn_share_to_social_aside')) {
    function tn_share_to_social_aside($post)
    {
        global $tn_options;
        if (empty($tn_options['tn_post_share'])) return false;
        $twitter_user = get_the_author_meta('tn_twitter');

        $str = '';
        if (!empty($tn_options['tn_post_to_facebook']))
            $str .= '<a class="share-to-social" href="http://www.facebook.com/sharer.php?u=' . urlencode(get_permalink($post->ID)) . '" onclick="window.open(this.href, \'mywin\',
\'left=50,top=50,width=600,height=350,toolbar=0\'); return false;"><i class="fa fa-facebook color-facebook"></i></a>';
        if (!empty($tn_options['tn_post_to_twitter']))
            $str .= '<a class="share-to-social" href="https://twitter.com/intent/tweet?text=' . urlencode(strip_tags(get_the_title($post->ID))) . '&amp;url=' . urlencode(get_permalink($post->ID)) . '&amp;via=' . urlencode($twitter_user ? $twitter_user : get_bloginfo('name')) . '" onclick="window.open(this.href, \'mywin\',
\'left=50,top=50,width=600,height=350,toolbar=0\'); return false;"><i class="fa fa-twitter color-twitter"></i></a>';
        if (!empty($tn_options['tn_post_to_google_plus']))
            $str .= ' <a class="share-to-social" href="http://plus.google.com/share?url=' . urlencode(get_permalink($post->ID)) . '" onclick="window.open(this.href, \'mywin\',
\'left=50,top=50,width=600,height=350,toolbar=0\'); return false;"><i class="fa fa-google-plus color-google"></i></a>';

        if (!empty($tn_options['tn_post_to_pinterest'])) {
            $str .= '<a class="share-to-social" href="http://pinterest.com/pin/create/button/?url=' . urlencode(get_permalink($post->ID)) . '&amp;media=' . (!empty($image[0]) ? $image[0] : '') . '" onclick="window.open(this.href, \'mywin\',
\'left=50,top=50,width=600,height=350,toolbar=0\'); return false;"><i class="fa fa-pinterest color-pinterest"></i></a>';
        }
        if (!empty($tn_options['tn_post_to_tumblr'])) {
            $str .= ' <a class="share-to-social" href="http://www.tumblr.com/share/link?url=' . urlencode(get_permalink($post->ID)) . '&amp;name=' . urlencode(strip_tags(get_the_title($post->ID))) . '&amp;description=' . urlencode(strip_tags(get_the_title($post->ID))) . '" onclick="window.open(this.href, \'mywin\',
\'left=50,top=50,width=600,height=350,toolbar=0\'); return false;"><i class="fa fa-tumblr color-tumblr"></i></a>';
        }
        if (!empty($tn_options['tn_post_to_linkedin'])) {
            $str .= '  <a class="share-to-social" href="http://linkedin.com/shareArticle?mini=true&amp;url=' . urlencode(get_permalink($post->ID)) . '&amp;title=' . urlencode(strip_tags(get_the_title($post->ID))) . '" onclick="window.open(this.href, \'mywin\',
\'left=50,top=50,width=600,height=350,toolbar=0\'); return false;"><i class="fa fa-linkedin color-linkedin"></i></a>';
        }
        if (!empty($tn_options['tn_post_to_digg']))
            $str .= '<a class="share-to-social" href="http://digg.com/submit?phase=2&amp;url=' . urlencode(get_permalink($post->ID)) . '&amp;bodytext=&amp;tags=&amp;title=' . urlencode(strip_tags(get_the_title($post->ID))) . '" target="_blank" onclick="window.open(this.href, \'mywin\',
\'left=50,top=50,width=600,height=350,toolbar=0\'); return false;"><i class="fa fa-digg color-digg"></i></a>';

        return $str;
    }
}


//open body html
if (!function_exists('tn_open_body')) {
    function tn_open_body()
    {
        $str = '';
        $str .= '<div id="main-wrapper" class="tn-container"><div class="row container-fluid">';
        return $str;
    }
}
//close body html
if (!function_exists('tn_close_body')) {
    function tn_close_body()
    {
        return '</div></div>';
    }
}

//open blog page
if (!function_exists('tn_open_blog')) {
    function tn_open_blog($style)
    {
        $str = '';
        switch ($style) {
            case 'style1' :
            case 'style2' :
            case 'style3' :
            case 'style4' :
            case 'style5' :
            case 'style6' :
            case 'style10' :
                $str .= '<div class="row clearfix"><div id="main-content" class="col-sm-8 col-xs-12" role="main">';
                break;
            case 'style7' :
            case 'style8' :
            case 'style9' :
                $str .= '<div class="row clearfix"><div id="main-content" class="col-xs-12" role="main">';
                break;
        }
        return $str;
    }
}


//close blog page
if (!function_exists('tn_close_blog')) {
    function tn_close_blog()
    {
        return '</div><!--#main-content-->';
    }
}

//check open sidebar in blog page
if (!function_exists('tn_check_blog_sidebar')) {
    function tn_check_blog_sidebar($style)
    {
        $check = true;
        switch ($style) {
            case 'style7' :
            case 'style8' :
            case 'style9' :
                $check = false;
                break;
        }
        return $check;
    }
}

//open single page
if (!function_exists('tn_open_single')) {
    function tn_open_single($sidebar_position, $post_id = '')
    {
        $str = '';
        if ($sidebar_position == 'full'){
            $str .= '<div class="row clearfix"><article id="main-content" class="col-xs-12" role="main" ' . tn_get_single_scope(tn_check_reviews($post_id)) . '>';
            if(tn_check_reviews($post_id)){
                $str .= '<meta itemprop="itemReviewed" content="' . strip_tags(get_the_title($post_id)) . '">';
            }
        } else {
            $str .= '<div class="row cleafix"><article id="main-content" class="col-sm-8 col-xs-12" role="main" ' . tn_get_single_scope(tn_check_reviews($post_id)) . '>';
            if(tn_check_reviews($post_id)){
                $str .= '<meta itemprop="itemReviewed" content="' . strip_tags(get_the_title($post_id)) . '">';
            }
        }

        return $str;
    }
}

if (!function_exists('tn_open_single_no_seo')) {
    function tn_open_single_no_seo($sidebar_position)
    {
        if ($sidebar_position == 'full')
            $str = '<div class="row clearfix"><article id="main-content" class="col-xs-12" role="main">';
        else  $str = '<div class="row cleafix"><article id="main-content" class="col-sm-8 col-xs-12" role="main">';
        return $str;
    }
}

//check open sidebar in single page
if (!function_exists('tn_check_single_sidebar')) {
    function tn_check_single_sidebar($sidebar_position)
    {
        $check = true;
        if ($sidebar_position == 'full')
            $check = false;
        return $check;
    }
}

//add views count
if (!function_exists('tn_add_post_views')) {
    function tn_add_post_views($postID)
    {
        $count_key = 'post_views_count';
        $count = get_post_meta($postID, $count_key, true);
        if ($count == '') {
            delete_post_meta($postID, $count_key);
            add_post_meta($postID, $count_key, '0');
        } else {
            $count++;
            update_post_meta($postID, $count_key, $count);
        }
    }
}

//get views count
if (!function_exists('tn_get_post_views')) {
    function tn_get_post_views($postID)
    {
        $count_key = 'post_views_count';
        $count = get_post_meta($postID, $count_key, true);
        if ($count == '') {
            delete_post_meta($postID, $count_key);
            add_post_meta($postID, $count_key, '0');
            return "0";
        }
        return $count;
    }
}

//get post tags
if (!function_exists('tn_get_post_tags')) {
    function tn_get_post_tags($postID)
    {
        $str = '';
        $tags = get_the_tags($postID);
        if (!empty($tags)) {
            $str .= '<li><span>/</span>';
            foreach ($tags as $tag) {
                $tag_link = get_tag_link($tag->term_id);
                $str .= '<a class="post-meta-tag" href="' . $tag_link . '" title="' . esc_attr(strip_tags($tag->name)) . '">' . esc_attr($tag->name) . '</a>';
            }
            $str .= '</li>';
        }
        return $str;
    }
}

//get post tags as string
if (!function_exists('tn_get_post_tags_string')) {
    function tn_get_post_tags_string($postID)
    {
        $str = '';
        $tags = wp_get_post_tags($postID);
        if (!empty($tags)) {
            foreach ($tags as $tag) {
                $str .= esc_attr($tag->name) . ',';
            }
        }
        return substr($str, 0, -1);
    }
}

//get category ids as string
if (!function_exists('tn_get_cate_ids_string')) {
    function tn_get_cate_ids_string($postID)
    {
        $str = '';
        $categories = get_the_category($postID);
        if (!empty($categories)) {
            foreach ($categories as $cate) {
                $str .= $cate->cat_ID . ',';
            }
        }
        return substr($str, 0, -1);
    }
}

//render author box
if (!function_exists('tn_author_box')) {
    function tn_author_box($author_id)
    {
        $str = '';
        if (!empty($author_id)) {
            $str .= '<div class="author-box-wrap clearfix">';
            $str .= '<div class="author-thumb">';
            $str .= get_avatar(get_the_author_meta('user_email', $author_id), 105, '', get_the_author_meta('display_name', $author_id));
            $str .= '</div>';
            $str .= '<h3 class="author-title"><a href="' . get_author_posts_url($author_id) . '">' . get_the_author_meta('display_name', $author_id) . '</a></h3>';
            $str .= '<div class="author-description">' . get_the_author_meta('description', $author_id) . '</div>';
            $str .= '<div class="author-social">' . tn_author_social($author_id) . '</div><!--author-social-->';
            $str .= '</div><!--#author box -->';
        }
        return $str;
    }
}

//ajax url
if (!function_exists('tn_ajax_url')) {
    function tn_ajax_url()
    {
        ?>
        <script type="text/javascript">
            var tn_ajax_url = '<?php echo admin_url('admin-ajax.php'); ?>';
        </script>
    <?php
    }
}
add_action('wp_head', 'tn_ajax_url');

//render search form
if (!function_exists('tn_ajax_form_search')) {
    function tn_ajax_form_search()
    {
        $str = '';
        $str .= '<div class="ajax-search-wrap">';
        $str .= '<a href="#" id="ajax-form-search" class="ajax-search-icon"><i class="fa fa-search"></i></a>';
        $str .= '<form class="ajax-form" role="search" method="get" action="' . esc_url(home_url('/')) . '">';
        $str .= '<fieldset>';
        $str .= '<input id="search-form-text" type="text" autocomplete="off" class="field" name="s" value="' . get_search_query() . '" placeholder="' . __('Search this Site...', 'tn') . '">';
        $str .= '</fieldset>';
        $str .= ' <div id="ajax-search-result"></div>';
        $str .= '</form></div>';

        return $str;
    }
}

//get excerpt
if (!function_exists('tn_excerpt')) {
    function tn_excerpt($post, $options)
    {
        if ($post->post_excerpt != '') {
            return wp_trim_words($post->post_excerpt, $options['excerpt'], '...');
        } else {
            $post_content = preg_replace('`\[[^\]]*\]`', '', $post->post_content);
            $post_content = stripslashes(wp_filter_nohtml_kses($post_content));
            return wp_trim_words($post_content, $options['excerpt'], '...');
        }
    }
}

//title
if (!function_exists('tn_wp_title')) {
    function tn_wp_title($title, $sep)
    {
        global $paged, $page;

        if (is_feed()) {
            return $title;
        }

        // Add the site name.
        $title .= get_bloginfo('name');

        // Add the site description for the home/front page.
        $site_description = get_bloginfo('description', 'display');
        if ($site_description && (is_home() || is_front_page())) {
            $title = "$title $sep $site_description";
        }

        // Add a page number if necessary.
        if ($paged >= 2 || $page >= 2) {
            $title = "$title $sep " . sprintf(__('Page %s', 'tn'), max($paged, $page));
        }

        return $title;
    }
}

//get single scope
if (!function_exists('tn_get_single_scope')) {
    function tn_get_single_scope($has_reviews = '')
    {
        if (!empty($has_reviews)) {
            return 'itemscope itemtype="http://schema.org/Review"';
        } else {
            return 'itemscope itemtype="http://schema.org/Article"';
        }
    }
}

//get block scope
if (!function_exists('tn_get_block_scope')) {
    function tn_get_block_scope()
    {
        //all the links are articles - google doesn't like multiple reviews on one page
        return 'itemscope itemtype="http://schema.org/Article"';
    }
}

//get author scope
if (!function_exists('tn_get_item_scope_meta')) {
    function tn_get_item_scope_meta($author_id)
    {
        $str = '';
        $str .= '<meta itemprop="author" content = "' . esc_attr(get_the_author_meta('display_name', $author_id)) . '">';
        $google_author = get_the_author_meta('tn_google_plus', $author_id);
        if (!empty($google_author)) {
            $str .= '<a href="' . esc_attr($google_author) . '?rel=author"></a>';
        }
        return $str;
    }
}

add_filter('post_thumbnail_html', 'tn_image_itemprop', 10, 3);

if (!function_exists('tn_image_itemprop')) {
    function tn_image_itemprop($html, $post_id, $post_image_id)
    {
        $html = str_replace('src', ' itemprop="image" src', $html);
        return $html;
    }
}


//get option from theme settings
if (!function_exists('tn_get_theme_option')) {
    function tn_get_theme_option($option_name, $default_value = '')
    {
        global $tn_options;
        if (!empty($tn_options[$option_name]))
            $option = $tn_options[$option_name];
        else $option = $default_value;
        return $option;
    }
}


//meta on thumb
if (!function_exists('tn_meta_on_thumb')) {
    function tn_meta_on_thumb($post_id)
    {
        $meta_thumb = tn_get_theme_option('tn_meta_thumb_option', 'views_count');

        $str = '';
        $str .= '<div class="meta-thumb-wrap">';

        if ($meta_thumb == 'views_count') {
            $view_post = intval(tn_get_post_views($post_id));
            if ($view_post == 1) {
                $str .= '<div class="meta-thumb-element meta-thumb-views"><i class="fa fa-share"></i><span>' . esc_attr($view_post) . ' ' . __('view', 'tn') . '</span></div>';
            } else {
                $str .= '<div class="meta-thumb-element meta-thumb-views"><i class="fa fa-share"></i><span>' . esc_attr($view_post) . ' ' . __('views', 'tn') . '</span></div>';
            }

        } else {
            $all_share = tn_count_all_share(get_permalink($post_id));
            if ($all_share['all'] == 0) {
                $str .= '<div class="meta-thumb-element meta-thumb-shares"><i class="fa fa-share"></i><span>' . __('share', 'tn') . '</span></div>';
            } else {
                if ($all_share['all'] == 1) {
                    $str .= '<div class="meta-thumb-element meta-thumb-shares"><i class="fa fa-share"></i><span>' . esc_attr($all_share['all']) . ' ' . __('share', 'tn') . '</span></div>';
                } else {
                    $str .= '<div class="meta-thumb-element meta-thumb-shares"><i class="fa fa-share"></i><span>' . esc_attr($all_share['all']) . ' ' . __(' shares', 'tn') . '</span></div>';
                }
            }
        }

        if (tn_has_reviews($post_id)) {
            $str .= tn_score($post_id);
        }

        $str .= '</div><!--# meta thumb wrap -->';
        return $str;
    }
}

if (!function_exists('tn_share_to_social_thumb')) {
    function tn_share_to_social_thumb($post)
    {
        $post_shares = tn_get_theme_option('tn_post_share');
        if (empty($post_shares)) return false;

        $str = '';
        global $tn_options;
        $twitter_user = get_the_author_meta('tn_twitter');
        $image = wp_get_attachment_image_src(get_post_thumbnail_id($post->ID), 'blog_classic_thumb');

        $str .= '<div class="shares-to-social-thumb-wrap share-invisible">';
        $str .= '<div class="shares-to-social-thumb-inner">';
        if (!empty($tn_options['tn_post_to_facebook']))
            $str .= '<a class="share-to-social" href="http://www.facebook.com/sharer.php?u=' . urlencode(get_permalink($post->ID)) . '" onclick="window.open(this.href, \'mywin\',
\'left=50,top=50,width=600,height=350,toolbar=0\'); return false;"><i class="fa fa-facebook color-facebook"></i></a>';
        if (!empty($tn_options['tn_post_to_twitter']))
            $str .= '<a class="share-to-social" href="https://twitter.com/intent/tweet?text=' . urlencode(strip_tags(get_the_title($post->ID))) . '&amp;url=' . urlencode(get_permalink($post->ID)) . '&amp;via=' . urlencode($twitter_user ? $twitter_user : get_bloginfo('name')) . '" onclick="window.open(this.href, \'mywin\',
\'left=50,top=50,width=600,height=350,toolbar=0\'); return false;"><i class="fa fa-twitter color-twitter"></i></a>';
        if (!empty($tn_options['tn_post_to_google_plus']))
            $str .= ' <a class="share-to-social" href="http://plus.google.com/share?url=' . urlencode(get_permalink($post->ID)) . '" onclick="window.open(this.href, \'mywin\',
\'left=50,top=50,width=600,height=350,toolbar=0\'); return false;"><i class="fa fa-google-plus color-google"></i></a>';

        if (!empty($tn_options['tn_post_to_pinterest'])) {
            $str .= '<a class="share-to-social" href="http://pinterest.com/pin/create/button/?url=' . urlencode(get_permalink($post->ID)) . '&amp;media=' . (!empty($image[0]) ? $image[0] : '') . '" onclick="window.open(this.href, \'mywin\',
\'left=50,top=50,width=600,height=350,toolbar=0\'); return false;"><i class="fa fa-pinterest color-pinterest"></i></a>';
        }
        if (!empty($tn_options['tn_post_to_tumblr'])) {
            $str .= ' <a class="share-to-social" href="http://www.tumblr.com/share/link?url=' . urlencode(get_permalink($post->ID)) . '&amp;name=' . urlencode(strip_tags(get_the_title($post->ID))) . '&amp;description=' . urlencode(strip_tags(get_the_title($post->ID))) . '" onclick="window.open(this.href, \'mywin\',
\'left=50,top=50,width=600,height=350,toolbar=0\'); return false;"><i class="fa fa-tumblr color-tumblr"></i></a>';
        }
        if (!empty($tn_options['tn_post_to_linkedin'])) {
            $str .= '  <a class="share-to-social" href="http://linkedin.com/shareArticle?mini=true&amp;url=' . urlencode(get_permalink($post->ID)) . '&amp;title=' . urlencode(strip_tags(get_the_title($post->ID))) . '" onclick="window.open(this.href, \'mywin\',
\'left=50,top=50,width=600,height=350,toolbar=0\'); return false;"><i class="fa fa-linkedin color-linkedin"></i></a>';
        }
        if (!empty($tn_options['tn_post_to_digg']))
            $str .= '<a class="share-to-social" href="http://digg.com/submit?phase=2&amp;url=' . urlencode(get_permalink($post->ID)) . '&amp;bodytext=&amp;tags=&amp;title=' . urlencode(strip_tags(get_the_title($post->ID))) . '" target="_blank" title="' . esc_attr__('Share on Digg', 'tn') . '" onclick="window.open(this.href, \'mywin\',
\'left=50,top=50,width=600,height=350,toolbar=0\'); return false;"><i class="fa fa-digg color-digg"></i></a>';

        $str .= '</div></div>';
        
        return $str;
    }
}


//get category id
if (!function_exists('tn_get_category_id')) {
    function tn_get_category_id($post_id)
    {
        if (empty($post_id)) return;
        $categories = get_the_category($post_id);
        if (!empty($categories[0]->term_id)) {
            return $categories[0]->term_id;
        } else {
            return 'default';
        }
    }
}

//only search posts
if (!function_exists('tn_filter_search')) {
    function tn_filter_search($query)
    {
        if ($query->is_search) {
            $query->set('post_type', 'post');
        }
        return $query;
    }
}

add_filter('pre_get_posts', 'tn_filter_search');



