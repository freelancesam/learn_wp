<?php
add_action('widgets_init', 'tn_register_moduleFeature2_widget');

function tn_register_moduleFeature2_widget()
{
    register_widget('tn_moduleFeature2_widget');
}

class tn_moduleFeature2_widget extends WP_Widget
{

    function tn_moduleFeature2_widget()
    {
        $widget_ops = array('classname' => 'module-feature2-widget', 'description' => __('[FullWidth Widget] Display of post titles and thumbnails combined slider and grid layout. This widget can place FULL WIDTH','tn'));
        $this->WP_Widget('module-feature2-widget', __('TN . Slider & Grid Featured', 'tn'), $widget_ops);
    }

    function widget($args, $instance)
    {
        extract($args);
        $array_query = array();
        $options = array();
        $array_query['meta_key'] = '_thumbnail_id';
        $array_query['category_id'] = ($instance['cate']) ? $instance['cate'] : '';
        $array_query['category_ids'] = ($instance['cates']) ? $instance['cates'] : '';
        $array_query['tag_plus'] = ($instance['tags']) ? $instance['tags'] : '';
        $array_query['sort_order'] = ($instance['sort_order']) ? $instance['sort_order'] : 'date_post';
        $array_query['posts_per_page'] = ($instance['posts_per_page']) ? $instance['posts_per_page'] : 5;
        $options['excerpt'] = ($instance['excerpt']) ? $instance['excerpt'] : 18;
        $options['readmore'] = ($instance['readmore']) ? $instance['readmore'] : '';
        $options['style'] = ($instance['style']) ? $instance['style'] : 1;
        $options['float'] = ($instance['float']) ? $instance['float'] : 'left';
        $options['readmore'] = ($instance['readmore']) ? $instance['readmore'] : '';
        $options['animation'] = ($instance['animation']) ? $instance['animation'] : 'slide';

        $query_data = tn_custom_query($array_query);

        //Show widget
        echo $before_widget;

        if ($query_data) :?>
                <?php  echo tn_moduleFeature2($query_data->posts ,$options); ?>
        <?php endif;

        echo $after_widget;
    }

    function update($new_instance, $old_instance)
    {
        $instance = $old_instance;
        $instance['float'] = strip_tags($new_instance['float']);
        $instance['animation'] = strip_tags($new_instance['animation']);
        $instance['style'] = strip_tags($new_instance['style']);
        $instance['cate'] = strip_tags($new_instance['cate']);
        $instance['cates'] = strip_tags($new_instance['cates']);
        $instance['tags'] = strip_tags($new_instance['tags']);
        $instance['sort_order'] = strip_tags($new_instance['sort_order']);
        $instance['readmore'] = strip_tags($new_instance['readmore']);

        $instance['posts_per_page'] = absint(strip_tags($new_instance['posts_per_page']));
        $instance['excerpt'] = absint(strip_tags($new_instance['excerpt']));
        return $instance;
    }

    function form($instance)
    {
        $defaults = array('sort_order' => 'date_post', 'cate' => '', 'cates' => '', 'tags' => '', 'style' => 1, 'float' => 'left', 'animation' => 'slide', 'posts_per_page' => 5, 'excerpt' => 18, 'readmore' => '');
        $instance = wp_parse_args((array)$instance, $defaults); ?>

        <p><strong><?php _e('Big Slider Options','tn'); ?></strong></p>
        <p>
            <label for="<?php echo $this->get_field_id( 'float' ); ?>"><?php _e('Big Slider Position:', 'tn'); ?></label>
            <select class="widefat" id="<?php echo $this->get_field_id( 'float' ); ?>" name="<?php echo $this->get_field_name( 'float' ); ?>" >
                <option value="left" <?php if( !empty($instance['float']) && $instance['float'] == 'left' ) echo "selected=\"selected\""; else echo ""; ?>><?php _e('Left', 'tn'); ?></option>
                <option value="right" <?php if( !empty($instance['float']) && $instance['float'] == 'right' ) echo "selected=\"selected\""; else echo ""; ?>><?php _e('Right', 'tn'); ?></option>
            </select>
        </p>
        <p>
            <label for="<?php echo $this->get_field_id( 'animation' ); ?>"><?php _e('Slider Animation Style:', 'tn'); ?></label>
            <select class="widefat" id="<?php echo $this->get_field_id( 'animation' ); ?>" name="<?php echo $this->get_field_name( 'animation' ); ?>" >
                <option value="slide" <?php if( !empty($instance['animation']) && $instance['animation'] == 'slide' ) echo "selected=\"selected\""; else echo ""; ?>><?php _e('Slide', 'tn'); ?></option>
                <option value="fade" <?php if( !empty($instance['animation']) && $instance['animation'] == 'fade' ) echo "selected=\"selected\""; else echo ""; ?>><?php _e('Fade', 'tn'); ?></option>
            </select>
        </p>
        <p><strong><?php _e('Small Element Options','tn'); ?></strong></p>
        <p>
            <label for="<?php echo $this->get_field_id( 'style' ); ?>"><?php _e('Small Element Style:', 'tn'); ?></label>
            <select class="widefat" id="<?php echo $this->get_field_id( 'style' ); ?>" name="<?php echo $this->get_field_name( 'style' ); ?>" >
                <option value="1" <?php if( !empty($instance['style']) && $instance['style'] == 1 ) echo "selected=\"selected\""; else echo ""; ?>><?php _e('Style 1', 'tn'); ?></option>
                <option value="2" <?php if( !empty($instance['style']) && $instance['style'] == 2 ) echo "selected=\"selected\""; else echo ""; ?>><?php _e('Style 2', 'tn'); ?></option>
                <option value="3" <?php if( !empty($instance['style']) && $instance['style'] == 3 ) echo "selected=\"selected\""; else echo ""; ?>><?php _e('Style 3', 'tn'); ?></option>
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
            <label for="<?php echo $this->get_field_id( 'sort_order' ); ?>"><strong><?php _e('Order By: ', 'tn'); ?></strong></label>
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
            <label for="<?php echo $this->get_field_id( 'posts_per_page' ); ?>"><?php _e('Limit Post Number (minimum is 3):','tn') ?></label>
            <input type="text" class="widefat" id="<?php echo $this->get_field_id( 'posts_per_page' ); ?>" name="<?php echo $this->get_field_name( 'posts_per_page' ); ?>" value="<?php if( !empty($instance['posts_per_page']) ) echo esc_attr($instance['posts_per_page']); ?>" />
        </p>
        <p>
            <label for="<?php echo $this->get_field_id( 'readmore' ); ?>"><?php _e('Show READ MORE Button:','tn'); ?></label>
            <input class="widefat" type="checkbox" id="<?php echo $this->get_field_id( 'readmore' ); ?>" name="<?php echo $this->get_field_name( 'readmore' ); ?>" value="checked" <?php if(!empty( $instance['readmore'])) echo 'checked="checked"'; ?>  />
        </p>
        <p>
            <label for="<?php echo $this->get_field_id( 'excerpt' ); ?>"><?php _e('Post Excerpt (optional, default is 18):','tn') ?></label>
            <input type="text" class="widefat" id="<?php echo $this->get_field_id( 'excerpt' ); ?>" name="<?php echo $this->get_field_name( 'excerpt' ); ?>" value="<?php if( !empty($instance['excerpt']) ) echo esc_attr($instance['excerpt']); ?>" />
        </p>
    <?php
    }
}

?>
