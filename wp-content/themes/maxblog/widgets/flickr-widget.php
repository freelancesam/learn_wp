<?php

//flickr widget
add_action('widgets_init', 'tn_register_flickr_widget');
function tn_register_flickr_widget()
{
    register_widget('tn_flickr_widget');
}

//setup
class tn_flickr_widget extends WP_Widget
{


    function tn_flickr_widget()
    {
        $widget_ops = array('classname' => 'flickr-widget', 'description' => __('[Sidebar Widget] Show your Flickr latest photostream', 'tn'));
        $this->WP_Widget('tn-flickr', __('TN. Flickr Photostream', 'tn'), $widget_ops);
    }

    //render widget
    function widget($args, $instance)
    {
        extract($args);
        echo $before_widget;
        $title = empty($instance['title']) ? ' ' : apply_filters('widget_title', $instance['title']);
        $title = esc_attr($title);

        $flickr_id = empty($instance['flickr_id']) ? ' ' : apply_filters('widget_user', $instance['flickr_id']);
        $num_images = empty($instance['img_num']) ? ' ' : apply_filters('img_num', $instance['img_num']);
        $tags = empty($instance['tags']) ? ' ' : apply_filters('sort_order', $instance['tags']);
        if (!empty($title)){
            echo $before_title . $title . $after_title;
        }
        ?>
            <div class="flickr-wrap clearfix">
            <?php
            $cache = get_transient('tn_flickr_data');
            if (is_array($cache) && !empty($cache[$num_images])) {
                $flickr_data = $cache[$num_images];
            } else {

                $flickr_data = tn_flickr_data($flickr_id,$num_images,$tags);
                // store to cache
                $cache[$num_images] = $flickr_data;
                set_transient('tn_flickr_data', $cache, 300); // 5 minutes expiry
            }

            ?>
            <?php foreach ($flickr_data as $item): ?>
                <div class="flickr-img">
                    <a href="<?php echo esc_url($item['link']); ?>">
                        <img src="<?php echo esc_url($item['media']); ?>" alt="<?php echo esc_attr(strip_tags($item['title'])); ?>"/>
                    </a>
                </div>
            <?php endforeach; ?>
        </div>
        <?php
        echo $after_widget;
    }

    //update setting
    function update($new_instance, $old_instance)
    {
        $instance = $old_instance;
        $instance['title'] = strip_tags($new_instance['title']);
        $instance['flickr_id'] = strip_tags($new_instance['flickr_id']);
        $instance['img_num'] = absint(strip_tags($new_instance['img_num']));
        $instance['tags'] = strip_tags($new_instance['tags']);
        return $instance;
    }

    //load setting
    function form($instance)
    {
        $defaults = array('title' => 'Flickr Photostream', 'flickr_id' => '', 'img_num' => 9,'tags'=>'');
        $instance = wp_parse_args((array)$instance, $defaults);
        ?>
        <p><label for="<?php echo $this->get_field_id('title'); ?>"><strong><?php _e('Title:', 'tn') ?></strong>
             <input class="widefat"  id="<?php echo $this->get_field_id('title'); ?>" name="<?php echo $this->get_field_name('title'); ?>" type="text" value="<?php if( !empty($instance['title'])) echo esc_attr($instance['title']); ?>"/></label></p>
        <p>
            <label for="<?php echo $this->get_field_id('flickr_id'); ?>"><strong><?php _e('Flickr User ID:', 'tn') ?></strong>(<a href="http://www.idgettr.com" target="_blank"><?php _e('Get Id:','tn'); ?></a> ):
            <input class="widefat"  id="<?php echo $this->get_field_id('flickr_id'); ?>" name="<?php echo $this->get_field_name('flickr_id'); ?>" type="text" value="<?php  if(!empty($instance['flickr_id'])) echo esc_attr($instance['flickr_id']); ?>"/></label>
        </p>
        <p>
            <label for="<?php echo $this->get_field_id('img_num'); ?>"><strong><?php _e('Number of images:', 'tn') ?></strong>
            <input class="widefat" id="<?php echo $this->get_field_id('img_num'); ?>" name="<?php echo $this->get_field_name('img_num'); ?>" type="text" value="<?php if(!empty($instance['img_num'])) echo esc_attr($instance['img_num']); ?>"/></label>
        </p>
        <p>
            <label for="<?php echo esc_attr($this->get_field_id('tags')); ?>"><strong><?php _e('Tags:','tn'); ?></strong></label>
            <input class="widefat" id="<?php echo esc_attr($this->get_field_id('tags')); ?>" name="<?php echo $this->get_field_name('tags'); ?>" type="text" value="<?php  if(!empty($instance['tags'])) echo esc_attr($instance['tags']); ?>" />
            <p><?php _e('To filter multiple tag slug, enter here Separate tags with comma, example: tag1,tag2,tag3','tn') ?>
        </p>

    <?php
    }
}