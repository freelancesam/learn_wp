<?php
//youtube widget
add_action('widgets_init', 'tn_register_youtube_widget');
function tn_register_youtube_widget()
{
    register_widget('tn_youtube_widget');
}

//register widget
class tn_youtube_widget extends WP_Widget
{

    function tn_youtube_widget()
    {
        $widget_ops = array('classname' => 'youtube-widget', 'description' => __('[Sidebar Widget] Show a YouTube SUBSCRIBE button, the number of subscribers','tn'));
        $this->WP_Widget('tn_youtube', __('TN . Youtube Subscribe', 'tn'), $widget_ops);
    }

    //load widget
    function widget($args, $instance)
    {
        extract($args);

        $title = ($instance['title'])? esc_attr($instance['title']) : '';
        $url = ($instance['url'])? $instance['url'] : '';
        echo $before_widget;
        if (!empty($title))
            echo $before_title . $title . $after_title;
        ?>
        <div class="subscribe-youtube-wrap">
            <iframe id="youtube" src="http://www.youtube.com/subscribe_widget?p=<?php echo esc_attr($url) ?>" style="overflow: hidden; height: 100px; border: 0 none; width: 100%;"></iframe>
        </div>
        <?php
        echo $after_widget;
    }

    //update widget
    function update($new_instance, $old_instance)
    {
        $instance = $old_instance;
        $instance['title'] = strip_tags($new_instance['title']);
        $instance['url'] = strip_tags($new_instance['url']);
        return $instance;
    }

    //form input
    function form($instance)
    {
        $defaults = array('title' => __('Subscribe to our Channel', 'tn'));
        $instance = wp_parse_args((array)$instance, $defaults); ?>

        <p>
            <label for="<?php echo $this->get_field_id('title'); ?>"><?php _e('Title :','tn'); ?></label>
            <input  type="text" class="widefat" id="<?php echo $this->get_field_id('title'); ?>" name="<?php echo $this->get_field_name('title'); ?>" value="<?php if (!empty($instance['title'])) echo esc_attr($instance['title']); ?>"/>
        </p>
        <p>
            <label for="<?php echo $this->get_field_id('url'); ?>"><?php _e('Channel Name:','tn') ?></label>
            <input  type="text" class="widefat" id="<?php echo $this->get_field_id('url'); ?>" name="<?php echo $this->get_field_name('url'); ?>" value="<?php if (!empty($instance['url'])) echo esc_url($instance['url']); ?>"/>\
        </p>
    <?php
    }
}
?>