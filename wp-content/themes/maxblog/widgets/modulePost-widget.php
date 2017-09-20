<?php
add_action('widgets_init', 'tn_register_modulePost_widget');

function tn_register_modulePost_widget()
{
    register_widget('tn_modulePost_widget');
}

class tn_modulePost_widget extends WP_Widget
{

    function tn_modulePost_widget()
    {
        $widget_ops = array('classname' => 'module-post-widget', 'description' => __('[Sidebar Widget] Display posts in sidebar with multiple layout. This widget only can place in SIDEBAR.','tn'));
        $this->WP_Widget('module-post-widget', __('TN . POSTS SIDEBAR BOX', 'tn'), $widget_ops);
    }

    function widget($args, $instance)
    {
        extract($args);
        $array_query = array();
        $options = array();
        $array_query['posts_per_page'] = 5;
        $title = ($instance['title']) ? esc_attr($instance['title']) : '';
        $array_query['posts_per_page'] = ($instance['posts_per_page']) ? $instance['posts_per_page'] : 4;
        $array_query['category_id'] = ($instance['cate']) ? $instance['cate'] : '';
        $array_query['category_ids'] = ($instance['cates']) ? $instance['cates'] : '';
        $array_query['tag_plus'] = ($instance['tags']) ? $instance['tags'] : '';
        $array_query['sort_order'] = ($instance['sort_order']) ? $instance['sort_order'] : 'date_post';
        $pagination = ($instance['pagination']) ? $instance['pagination'] : 'next_prev';
        $options['excerpt'] = ($instance['excerpt']) ? $instance['excerpt'] : 18;
        $options['style'] = ($instance['style']) ? $instance['style'] : 'style1';

        $id = uniqid('moduleP_');
        $query_data = tn_custom_query($array_query);

        if($title == 'default') {
            if ($array_query['category_id'] !='all') {
                $title = '<a href="' . get_category_link($array_query['category_id']) . '" title="' . strip_tags(get_cat_name($array_query['category_id'])) . '">' .get_cat_name($array_query['category_id']). '</a>';
            } else $title = esc_attr__('LATEST POSTS','tn');
        }

        //Show widget
        echo $before_widget;
        if(!empty($array_query['category_id']) && $array_query['category_id'] != 'all'){
            echo '<div class="tn-category-'.$array_query['category_id'].'">';
            if(!empty($title)) echo $before_title . $title . $after_title;
            echo '</div><!--#color title-->';
        } else {
            if(!empty($title)) echo $before_title . $title . $after_title;
        } ?>

        <div class="right-widget-title-wrap">
        <?php
       if (!empty($pagination)&&($options['style'] != 'style7')) echo tn_ajax_pagination($id, $pagination); ?>
        </div><!-- #right widget title -->
       <?php if ($query_data) :?>
            <div id="<?php echo $id; ?>">
                <?php echo tn_modulePost($query_data->posts, $options); ?>
            </div><!-- #row -->
        <?php endif;

        //ajax load
        global $modules_data;
        $config = array();
        $config['module_id'] = $id;
        $config['module_name']= 'moduleP';
        $config['max_num_pages'] = $query_data->max_num_pages;
        $config['current_page'] = 1;
        foreach ($array_query as $k => $v)
            $config ['module_query'][$k] = $v;
        foreach ($options as $k => $v)
            $config['options'][$k] = $v;

        $modules_data[$id] = $config;

        wp_localize_script('tn-module-ajax', 'modules_data', $modules_data);

        echo $after_widget;
    }
    //update
    function update($new_instance, $old_instance)
    {
        $instance = $old_instance;
        $instance['title'] = strip_tags($new_instance['title']);
        $instance['cate'] = strip_tags($new_instance['cate']);
        $instance['cates'] = strip_tags($new_instance['cates']);
        $instance['tags'] = strip_tags($new_instance['tags']);
        $instance['pagination'] = strip_tags($new_instance['pagination']);
        $instance['posts_per_page'] = absint(strip_tags($new_instance['posts_per_page']));
        $instance['excerpt'] = absint(strip_tags($new_instance['excerpt']));
        $instance['sort_order'] = strip_tags($new_instance['sort_order']);
        $instance['style'] = strip_tags($new_instance['style']);
        return $instance;
    }

    function form($instance)
    {
        $defaults = array('title' => 'default' ,'style' => 'style1', 'sort_order' => 'date_post', 'posts_per_page'=> 4, 'pagination' => 'next_prev', 'cate' => '', 'cates' => '', 'tags' => '', 'excerpt' => 18);
        $instance = wp_parse_args((array)$instance, $defaults); ?>
        <p>
            <label for="<?php echo $this->get_field_id( 'title' ); ?>"><?php _e('Title (Auto generate title when set default):','tn') ?></label>
            <input type="text" class="widefat" id="<?php echo $this->get_field_id( 'title' ); ?>" name="<?php echo $this->get_field_name( 'title' ); ?>" value="<?php if( !empty($instance['title']) ) echo esc_attr($instance['title']); ?>" />
        </p>
        <p>
            <label for="<?php echo $this->get_field_id( 'style' ); ?>"><?php _e('Style : ', 'tn'); ?></label>
            <select class="widefat" id="<?php echo $this->get_field_id( 'style' ); ?>" name="<?php echo $this->get_field_name( 'style' ); ?>" >
                <option value="style1" <?php if( !empty($instance['style']) && $instance['style'] == 'style1' ) echo "selected=\"selected\""; else echo ""; ?>><?php _e('Style 1', 'tn'); ?></option>
                <option value="style2" <?php if( !empty($instance['style']) && $instance['style'] == 'style2' ) echo "selected=\"selected\""; else echo ""; ?>><?php _e('Style 2', 'tn'); ?></option>
                <option value="style3" <?php if( !empty($instance['style']) && $instance['style'] == 'style3' ) echo "selected=\"selected\""; else echo ""; ?>><?php _e('Style 3', 'tn'); ?></option>
                <option value="style4" <?php if( !empty($instance['style']) && $instance['style'] == 'style4' ) echo "selected=\"selected\""; else echo ""; ?>><?php _e('Style 4', 'tn'); ?></option>
                <option value="style5" <?php if( !empty($instance['style']) && $instance['style'] == 'style5' ) echo "selected=\"selected\""; else echo ""; ?>><?php _e('Style 5', 'tn'); ?></option>
                <option value="style6" <?php if( !empty($instance['style']) && $instance['style'] == 'style6' ) echo "selected=\"selected\""; else echo ""; ?>><?php _e('Style 6', 'tn'); ?></option>
                <option value="style7" <?php if( !empty($instance['style']) && $instance['style'] == 'style7' ) echo "selected=\"selected\""; else echo ""; ?>><?php _e('Style 7', 'tn'); ?></option>
             </select>
        </p>
        <p>
            <label for="<?php echo $this->get_field_id('cate'); ?>"><strong><?php _e('Category Filter:', 'tn'); ?></strong></label>
            <select class="widefat" id="<?php echo $this->get_field_id('cate'); ?>" name="<?php echo $this->get_field_name('cate'); ?>">
                <option value='all' <?php if ($instance['cate'] == 'all') echo 'selected="selected"'; ?>><?php _e('All Categories', 'tn'); ?></option>
                <?php $categories = get_categories('type=post'); foreach ($categories as $category) { ?><option  value='<?php echo $category->term_id; ?>' <?php if ($instance['cate'] == $category->term_id) echo 'selected="selected"'; ?>><?php echo $category->cat_name; ?></option><?php } ?>
            </select>
        </p>
        <p>
            <label for="<?php echo $this->get_field_id( 'cates' ); ?>"><?php _e('Multiple Category Filter (optional, Input category ids, Separate category ids with comma. e.g. 1,2):','tn') ?></label>
            <input type="text" class="widefat" id="<?php echo $this->get_field_id( 'cates' ); ?>" name="<?php echo $this->get_field_name( 'cates' ); ?>" value="<?php if( !empty($instance['cates']) ) echo esc_attr($instance['cates']); ?>" />
        </p>
        <p>
            <label for="<?php echo $this->get_field_id( 'tags' ); ?>"><?php _e('Tags (optional, Separate tags with comma. e.g. tag1,tag2):','tn') ?></label>
            <input type="text" class="widefat" id="<?php echo $this->get_field_id( 'tags' ); ?>" name="<?php echo $this->get_field_name( 'tags' ); ?>" value="<?php if( !empty($instance['tags']) ) echo esc_attr($instance['tags']); ?>" />
        </p>
        <p>
            <label for="<?php echo $this->get_field_id( 'posts_per_page' ); ?>"><?php _e('Limit Post Number (optional, default is 4) :','tn') ?></label>
            <input type="text" class="widefat" id="<?php echo $this->get_field_id( 'posts_per_page' ); ?>" name="<?php echo $this->get_field_name( 'posts_per_page' ); ?>" value="<?php if( !empty($instance['posts_per_page']) ) echo esc_attr($instance['posts_per_page']); ?>" />
        </p>
        <p>
            <label for="<?php echo $this->get_field_id( 'sort_order' ); ?>"><strong><?php _e('Order By:', 'tn'); ?></strong></label>
            <select class="widefat" id="<?php echo $this->get_field_id( 'sort_order' ); ?>" name="<?php echo $this->get_field_name( 'sort_order' ); ?>" >
                <option value="date_post" <?php if( !empty($instance['sort_order']) && $instance['sort_order'] == 'date' ) echo "selected=\"selected\""; else echo ""; ?>><?php _e('Latest Post', 'tn'); ?></option>
                <option value="comment_count" <?php if( !empty($instance['sort_order']) && $instance['sort_order'] == 'comment_count' ) echo "selected=\"selected\""; else echo ""; ?>><?php _e('Popular Post by Comments', 'tn'); ?></option>
                <option value="view_count" <?php if( !empty($instance['sort_order']) && $instance['sort_order'] == 'view_count' ) echo "selected=\"selected\""; else echo ""; ?>><?php _e('Popular Post by Views', 'tn'); ?></option>
                <option value="best_review" <?php if( !empty($instance['sort_order']) && $instance['sort_order'] == 'best_review' ) echo "selected=\"selected\""; else echo ""; ?>><?php _e('Best Reviews', 'tn'); ?></option>
                <option value="post_type" <?php if( !empty($instance['sort_order']) && $instance['sort_order'] == 'post_type' ) echo "selected=\"selected\""; else echo ""; ?>><?php _e('Post Type', 'tn'); ?></option>
                <option value="rand" <?php if( !empty($instance['sort_order']) && $instance['sort_order'] == 'rand' ) echo "selected=\"selected\""; else echo ""; ?>><?php _e('Random Post', 'tn'); ?></option>
                <option value="alphabetical_order_asc" <?php if( !empty($instance['sort_order']) && $instance['sort_order'] == 'alphabetical_order_asc' ) echo "selected=\"selected\""; else echo ""; ?>><?php _e('alphabetical A->Z Posts', 'tn'); ?></option>
                <option value="alphabetical_order_decs" <?php if( !empty($instance['sort_order']) && $instance['sort_order'] == 'alphabetical_order_decs' ) echo "selected=\"selected\""; else echo ""; ?>><?php _e('alphabetical Z->A Posts', 'tn'); ?></option>
            </select>
        </p>
        <p>
            <label for="<?php echo $this->get_field_id( 'pagination' ); ?>"><?php _e('Pagination:','tn') ?></label>
            <select id="<?php echo $this->get_field_id( 'pagination' ); ?>" name="<?php echo $this->get_field_name( 'pagination' ); ?>" >
                <option value="disable" <?php if( !empty($instance['pagination']) && $instance['pagination'] == 'disable' ) echo "selected=\"selected\""; else echo ""; ?>><?php _e('-Disable-','tn'); ?></option>
                <option value="next_prev" <?php if( !empty($instance['pagination']) && $instance['pagination'] == 'next_prev' ) echo "selected=\"selected\""; else echo ""; ?>><?php _e('Next_Prev ajax','tn'); ?></option>
            </select>
        </p>
        <p>
            <label for="<?php echo $this->get_field_id( 'excerpt' ); ?>"><?php _e('Post Excerpt (optional, default is 18):','tn') ?></label>
            <input type="text" class="widefat" id="<?php echo $this->get_field_id( 'excerpt' ); ?>" name="<?php echo $this->get_field_name( 'excerpt' ); ?>" value="<?php if( !empty($instance['excerpt']) ) echo esc_attr($instance['excerpt']); ?>" />
        </p>
    <?php
    }
}

?>