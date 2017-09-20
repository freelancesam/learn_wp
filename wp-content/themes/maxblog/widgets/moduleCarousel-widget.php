<?php
add_action('widgets_init', 'tn_register_moduleCarousel_widget');
function tn_register_moduleCarousel_widget()
{
    register_widget('tn_moduleCarousel_widget');
}

class tn_moduleCarousel_widget extends WP_Widget
{

    function tn_moduleCarousel_widget()
    {
        $widget_ops = array('classname' => 'module-carousel-widget',
            'description' => __('[Content Widget] Display of post thumbnails and post titles in carousel slider. This widget can place to FULL WIDTH and CONTENT SECTION.','tn'));
        $this->WP_Widget('tn-carousel', __('TN . CAROUSEL Slider', 'tn'), $widget_ops);
    }

    function widget($args, $instance)
    {
        extract($args);
        $array_query = array();
        $options = array();
        $slider_options = array();
        $title = ($instance['title']) ? esc_attr($instance['title']) : '';
        $array_query['posts_per_page'] = ($instance['posts_per_page']) ? $instance['posts_per_page'] : 7;
        $array_query['category_id'] = ($instance['cate']) ? $instance['cate'] : '';
        $array_query['category_ids'] = ($instance['cates']) ? $instance['cates'] : '';
        $array_query['tag_plus'] = ($instance['tags']) ? $instance['tags'] : '';
        $array_query['sort_order'] = ($instance['sort_order']) ? $instance['sort_order'] : 'date_post';
        $array_query['meta_key'] = '_thumbnail_id';
        $options['style'] = ($instance['style']) ? $instance['style'] : 1;
        $slider_options['time'] = ($instance['time']) ? $instance['time'] : 4000;
        $slider_options['speed'] = ($instance['time']) ?  $instance['speed'] : 400;
        $slider_options['carousel'] = 'carousel';

        $id = uniqid('tnslider_');
        $slider_options['id'] = $id;
        $slider_options['animation'] = 'slide';
        tn_slider_data($id,$slider_options);
        $query_data = tn_custom_query($array_query);
        if($title == 'default'){
            if ($array_query['category_id'] !='all') {
                $title = '<a href="' . get_category_link($array_query['category_id']) . '" title="' . strip_tags(get_cat_name($array_query['category_id'])) . '">' .get_cat_name($array_query['category_id']). '</a>';
            } else $title = esc_attr__('CAROUSEL SLIDER','tn');
        };

        //Show widget
        echo $before_widget;
        if(!empty($array_query['category_id']) && $array_query['category_id'] != 'all'){
            echo '<div class="tn-category-'.$array_query['category_id'].'">';
            if(!empty($title)) echo $before_title . $title . $after_title;
            echo '</div><!--#color title-->';
        } else {
            if(!empty($title)) echo $before_title . $title . $after_title;
        }

        if ($query_data)  :?>
            <div id="<?php echo $id; ?>">
                <?php echo tn_moduleCarousel($query_data->posts, $options); ?>
            </div>
        <?php endif;
        echo $after_widget;
    }

    //update
    function update($new_instance, $old_instance)
    {
        $instance = $old_instance;
        $instance['title'] = strip_tags($new_instance['title']);
        $instance['style'] = strip_tags($new_instance['style']);
        $instance['cate'] = strip_tags($new_instance['cate']);
        $instance['cates'] = strip_tags($new_instance['cates']);
        $instance['tags'] = strip_tags($new_instance['tags']);
        $instance['posts_per_page'] = absint(strip_tags($new_instance['posts_per_page']));
        $instance['sort_order'] = strip_tags($new_instance['sort_order']);
        $instance['time'] = absint(strip_tags($new_instance['time']));
        $instance['speed'] = absint(strip_tags($new_instance['speed']));
        return $instance;
    }

    function form($instance)
    {
        $defaults = array('title' => 'default', 'style' => 1, 'posts_per_page' => 7, 'sort_order' => 'date_post', 'cate' => '', 'cates' => '', 'tags' => '', 'time' => 4000, 'speed' => 400);
        $instance = wp_parse_args((array)$instance, $defaults); ?>
        <p>
            <label for="<?php echo $this->get_field_id( 'title' ); ?>"><?php _e('Title (Auto generate title when set default, leave blank if you dont want display title.):','tn') ?></label>
            <input type="text" class="widefat" id="<?php echo $this->get_field_id( 'title' ); ?>"  name="<?php echo $this->get_field_name( 'title' ); ?>" value="<?php if( !empty($instance['title']) ) echo esc_attr($instance['title']); ?>" />
        </p>
        <p>
            <label for="<?php echo $this->get_field_id( 'style' ); ?>"><?php _e('Style:', 'tn'); ?></label>
            <select class="widefat" id="<?php echo $this->get_field_id( 'style' ); ?>" name="<?php echo $this->get_field_name( 'style' ); ?>" >
                <option value="1" <?php if( !empty($instance['style']) && $instance['style'] == 1 ) echo "selected=\"selected\""; else echo ""; ?>><?php _e('Style 1', 'tn'); ?></option>
                <option value="2" <?php if( !empty($instance['style']) && $instance['style'] == 2 ) echo "selected=\"selected\""; else echo ""; ?>><?php _e('Style 2', 'tn'); ?></option>
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
            <label for="<?php echo $this->get_field_id( 'posts_per_page' ); ?>"><?php _e('Limit Post Number (optional, default is 7):','tn') ?></label>
            <input type="text" class="widefat" id="<?php echo $this->get_field_id( 'posts_per_page' ); ?>" name="<?php echo $this->get_field_name( 'posts_per_page' ); ?>" value="<?php if( !empty($instance['posts_per_page']) ) echo esc_attr($instance['posts_per_page']); ?>" />
        </p>
        <p>
            <label for="<?php echo $this->get_field_id( 'sort_order' ); ?>"><?php _e('Order By: ', 'tn'); ?></label>
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
            <label for="<?php echo $this->get_field_id( 'time' ); ?>"><?php _e('Time Out of Slide Show in ms (default is 4000):', 'tn'); ?></label>
            <input type="text" class="widefat" id="<?php echo $this->get_field_id( 'time' ); ?>" name="<?php echo $this->get_field_name( 'time' ); ?>" value="<?php if( !empty($instance['time']) ) echo esc_attr($instance['time']); ?>" />
        </p>
        <p>
            <label for="<?php echo $this->get_field_id( 'speed' ); ?>"><?php _e('Animation Speed in ms (default is 400):', 'tn'); ?></label>
            <input type="text" class="widefat" id="<?php echo $this->get_field_id( 'speed' ); ?>" name="<?php echo $this->get_field_name( 'speed' ); ?>" value="<?php if( !empty($instance['speed']) ) echo esc_attr($instance['speed']); ?>" />
        </p>
    <?php
    }
}

?>