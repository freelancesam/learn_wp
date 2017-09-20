<?php
if (!function_exists('tn_dimox_breadcrumbs')) {
    function tn_dimox_breadcrumbs()
    {
        global $tn_options;
        if (empty($tn_options['tn_breadcrumbs'])) return;
        /* === OPTIONS === */
        $text['home'] = __('Home', 'tn'); // text for the 'Home' link
        $text['category'] = __('Archive by Category "%s"', 'tn'); // text for a category page
        $text['search'] = __('Search Results for "%s"', 'tn'); // text for a search results page
        $text['tag'] = __('Posts Tagged "%s"', 'tn'); // text for a tag page
        $text['author'] = __('Articles Posted by %s', 'tn'); // text for an author page
        $text['404'] = __('Error 404', 'tn'); // text for the 404 page

        $show_current = 1; // 1 - show current post/page/category title in breadcrumbs, 0 - don't show
        $show_on_home = 0; // 1 - show breadcrumbs on the homepage, 0 - don't show
        $show_home_link = 1; // 1 - show the 'Home' link, 0 - don't show
        $delimiter = '<i class="fa fa-angle-right next-breadcrumbs"></i>'; // delimiter between crumbs
        $before = '<span class="breadcrumbs-current" itemscope itemtype="http://data-vocabulary.org/Breadcrumb"><span itemprop="title">'; // tag before the current crumb
        $after = '</span></span>'; // tag after the current crumb
        /* === END OF OPTIONS === */

        global $post;
        $str = '';
        $home_link = esc_url(home_url('/'));
        $link_before = '<span typeof="v:Breadcrumb" itemscope itemtype="http://data-vocabulary.org/Breadcrumb">';
        $link_after = '</span>';
        $link_attr = ' rel="v:url" property="v:title" itemprop="url"';
        $link = $link_before . '<a' . $link_attr . ' href="%1$s" title="%2$s"><span itemprop="title">%2$s</span></a>' . $link_after;
        if (!empty($post)) {
            $parent_id = $parent_id_2 = $post->post_parent;
        } else $parent_id = $parent_id_2 = '';
        $frontpage_id = get_option('page_on_front');

        if (is_home() || is_front_page()) {

            if ($show_on_home == 1) $str .= '<div class="breadcrumbs-bar-wrap"><div class="breadcrumbs-bar-inner tn-container"><span itemscope itemtype="http://data-vocabulary.org/Breadcrumb"><a href="' . $home_link . '" itemprop="url" title="' . $text['home'] . '"><span itemprop="title">' . $text['home'] . '</span></a></span></div>';

        } else {

            $str .= '<div class="breadcrumbs-bar-wrap"><div class="breadcrumbs-bar-inner tn-container">';
            if ($show_home_link == 1) {
                $str .= '<span itemscope itemtype="http://data-vocabulary.org/Breadcrumb"><a href="' . $home_link . '" rel="v:url" property="v:title"  itemprop="url" title="' . $text['home'] . '"><span itemprop="title">' . $text['home'] . '</span></a></span>';
                if ($frontpage_id == 0 || $parent_id != $frontpage_id) {
                    $str .= $delimiter;
                }
            }

            if (is_category()) {
                $this_cat = get_category(get_query_var('cat'), false);
                if ($this_cat->parent != 0) {
                    $cats = get_category_parents($this_cat->parent, TRUE, $delimiter);
                    if ($show_current == 0) $cats = preg_replace("#^(.+)$delimiter$#", "$1", $cats);
                    $cats = str_replace('">', '"><span itemprop="title">', $cats);
                    $cats = str_replace('<a', $link_before . '<a' . $link_attr, $cats);
                    $cats = str_replace('next-breadcrumbs"><span itemprop="title">', 'next-breadcrumbs">', $cats);
                    $cats = str_replace('</a>', '</span></a>' . $link_after, $cats);
                    $str .= $cats;
                }
                if ($show_current == 1) {
                    $str .= $before . sprintf($text['category'], single_cat_title('', false)) . $after;
                }

            } elseif (is_search()) {
                $str .= $before . sprintf($text['search'], get_search_query()) . $after;

            } elseif (is_day()) {
                $str .= sprintf($link, get_year_link(get_the_time('Y')), get_the_time('Y')) . $delimiter;
                $str .= sprintf($link, get_month_link(get_the_time('Y'), get_the_time('m')), get_the_time('F')) . $delimiter;
                $str .= $before . get_the_time('d') . $after;

            } elseif (is_month()) {
                $str .= sprintf($link, get_year_link(get_the_time('Y')), get_the_time('Y')) . $delimiter;
                $str .= $before . get_the_time('F') . $after;

            } elseif (is_year()) {
                $str .= $before . get_the_time('Y') . $after;

            } elseif (is_single() && !is_attachment()) {
                if (get_post_type() != 'post') {
                    $post_type = get_post_type_object(get_post_type());
                    $slug = $post_type->rewrite;
                    $str .= printf($link, $home_link . $slug['slug'] . '/', $post_type->labels->singular_name);
                    if ($show_current == 1) {
                        $str .= $delimiter . $before . get_the_title() . $after;
                    }
                } else {
                    $cat = get_the_category();
                    $cat = $cat[0];
                    $cats = get_category_parents($cat, TRUE, $delimiter);
                    if ($show_current == 0) $cats = preg_replace("#^(.+)$delimiter$#", "$1", $cats);
                    $cats = str_replace('">', '"><span itemprop="title">', $cats);
                    $cats = str_replace('<a', $link_before . '<a' . $link_attr, $cats);
                    $cats = str_replace('next-breadcrumbs"><span itemprop="title">', 'next-breadcrumbs">', $cats);
                    $cats = str_replace('</a>', '</span></a>' . $link_after, $cats);
                    $str .= $cats;
                    if ($show_current == 1) {
                        $str .= $before . get_the_title() . $after;
                    }
                }

            } elseif (!is_single() && !is_page() && get_post_type() != 'post' && !is_404()) {
                $post_type = get_post_type_object(get_post_type());
                $str .= $before . esc_attr($post_type->labels->singular_name) . $after;

            } elseif (is_attachment()) {
                $parent = get_post($parent_id);
                $cat = get_the_category($parent->ID);
                $cat = $cat[0];
                if ($cat) {
                    $cats = get_category_parents($cat, TRUE, $delimiter);
                    $cats = str_replace('<a', $link_before . '<a' . $link_attr, $cats);
                    $cats = str_replace('">', '"><span itemprop="title">', $cats);
                    $cats = str_replace('</a>', '</span></a>' . $link_after, $cats);
                    $str .= $cats;
                }
                $str .= printf($link, get_permalink($parent), $parent->post_title);
                if ($show_current == 1) $str .= $delimiter . $before . get_the_title() . $after;

            } elseif (is_page() && !$parent_id) {
                if ($show_current == 1) $str .= $before . get_the_title() . $after;

            } elseif (is_page() && $parent_id) {
                if ($parent_id != $frontpage_id) {
                    $breadcrumbs = array();
                    while ($parent_id) {
                        $page = get_page($parent_id);
                        if ($parent_id != $frontpage_id) {
                            $breadcrumbs[] = sprintf($link, get_permalink($page->ID), get_the_title($page->ID));
                        }
                        $parent_id = $page->post_parent;
                    }
                    $breadcrumbs = array_reverse($breadcrumbs);
                    for ($i = 0; $i < count($breadcrumbs); $i++) {
                        $str .= $breadcrumbs[$i];
                        if ($i != count($breadcrumbs) - 1) {
                            $str .= $delimiter;
                        }
                    }
                }
                if ($show_current == 1) {
                    if ($show_home_link == 1 || ($parent_id_2 != 0 && $parent_id_2 != $frontpage_id)) {
                        $str .= $delimiter;
                    }
                    $str .= $before . get_the_title() . $after;
                }

            } elseif (is_tag()) {
                $str .= $before . sprintf($text['tag'], single_tag_title('', false)) . $after;

            } elseif (is_author()) {
                global $author;
                $userdata = get_userdata($author);
                $str .= $before . sprintf($text['author'], esc_attr($userdata->display_name)) . $after;

            } elseif (is_404()) {
                $str .= $before . $text['404'] . $after;

            } elseif (has_post_format() && !is_singular()) {
                $str .= get_post_format_string(get_post_format());
            }

            if (get_query_var('paged')) {
                if (is_category() || is_day() || is_month() || is_year() || is_search() || is_tag() || is_author()) $str .= ' (';
                $str .= __('Page', 'tn') . ' ' . get_query_var('paged');
                if (is_category() || is_day() || is_month() || is_year() || is_search() || is_tag() || is_author()) $str .= ')';
            }

            $str .= '</div></div><!--#breadcrumbs-->';
        }

        return $str;
    }
}
// #breadcrumbs
