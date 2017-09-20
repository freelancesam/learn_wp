<?php
add_action('widgets_init', 'tn_register_fanpage_widget');

function tn_register_fanpage_widget()
{
    register_widget('tn_fanpage_fb');
}

class tn_fanpage_fb extends WP_Widget
{

    function tn_fanpage_fb()
    {
        $widget_ops = array('classname' => 'tn_fanpage_fb', 'description' => __('[Sidebar Widget] Show Facebook Like box', 'tn'));

        /* Create the widget. */
        $this->WP_Widget('tn_fanpage_fb', __('TN . Facebook Like Box', 'tn'), $widget_ops);
    }

    //show widget
    function widget($args, $instance)
    {
        extract($args);
        $title = ($instance['title']) ? apply_filters('title', $instance['title']): '';
        $title = esc_attr($title);

        $page_url = ($instance['page_url']) ? apply_filters('page_url', $instance['page_url']):NULL;
        $height_value = ($instance['height_value']) ? apply_filters('height_value', $instance['height_value']):400;
        if ($page_url):
            echo $before_widget;
            if (!empty($title))
                echo $before_title . $title . $after_title;
            ?>
            <div class="fanpage-fb-widget">
                <iframe src="http://www.facebook.com/plugins/likebox.php?href=<?php echo esc_url($page_url) ?>&amp;width=350&amp;height=<?php echo esc_attr($height_value); ?>&amp;colorscheme=light&amp;show_faces=true&amp;header=false&amp;stream=false&amp;show_border=false" style="overflow:hidden;frameborder:0;border:none; overflow:hidden; width:310px; height:<?php echo esc_attr(intval($height_value)); ?>px;"></iframe>
            </div>
            <?php
            echo $after_widget;
        endif;
    }

   //update
    function update($new_instance, $old_instance)
    {
        $instance = $old_instance;
        $instance['title'] = strip_tags( $new_instance['title'] );
        $instance['page_url'] = strip_tags( $new_instance['page_url'] );
        $instance['height_value'] = absint(strip_tags($new_instance['height_value']));
        return $instance;
    }

    //form
    function form($instance)
    {
        $defaults = array('title' => __('Find us on Facebook', 'tn'), 'page_url' => '', 'height_value' => 400);
        $instance = wp_parse_args((array)$instance, $defaults); ?>

        <p>
            <label
                for="<?php echo $this->get_field_id('title'); ?>"><strong><?php _e('Title:', 'tn'); ?></strong></label>
            <input class="widefat" type="text" id="<?php echo $this->get_field_id('title'); ?>" name="<?php echo $this->get_field_name('title'); ?>" value="<?php echo esc_attr($instance['title']); ?>"/>
        </p>
        <p>
            <label for="<?php echo $this->get_field_id('page_url'); ?>"><strong><?php _e('Fanpage Fb URL:', 'tn') ?></strong></label>
            <input class="widefat" type="text" id="<?php echo $this->get_field_id('page_url'); ?>" name="<?php echo $this->get_field_name('page_url'); ?>" value="<?php echo esc_url($instance['page_url']); ?>"/>
        </p>
        <p>
            <label
                for="<?php echo $this->get_field_id('height_value'); ?>"><strong><?php _e('Set Height Value (px):', 'tn') ?></strong></label>
            <input class="widefat" type="text" id="<?php echo $this->get_field_id('height_value'); ?>" name="<?php echo $this->get_field_name('height_value'); ?>" value="<?php echo esc_attr($instance['height_value']); ?>"/>
        </p>
    <?php
    }
}

?>