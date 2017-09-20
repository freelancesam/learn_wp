<?php

add_action('widgets_init', 'tn_register_social_widget');

function tn_register_social_widget()
{
    register_widget('tn_social_widget');
}

class tn_social_widget extends WP_Widget
{

    function tn_social_widget()
    {
        $widget_ops = array('classname' => 'social-widget', 'description' => __('[Sidebar Widget] Show Social Url. This widget can place in SIDEBAR','tn'));
        $this->WP_Widget('social-widget', __('TN . Social Widget', 'tn'), $widget_ops);
    }

    function widget($args, $instance)
    {
        extract($args);
        $title = ($instance['title']) ? esc_attr($instance['title']) : '';
        $new_tab = ($instance['new_tab']) ? $instance['new_tab'] : true;
        if(!empty($new_tab)) $new_tab = true;

        echo $before_widget;
        if ($title) echo $before_title . $title . $after_title;
        $data_social = tn_web_social();

        ?>
        <div class="widget-social-content-wrap">
       <?php  echo tn_social_icon($data_social, $new_tab); ?>
        </div>
       <?php echo $after_widget;
    }

    function update($new_instance, $old_instance)
    {
        $instance = $old_instance;
        $instance['title'] = strip_tags($new_instance['title']);
        $instance['new_tab'] = strip_tags($new_instance['new_tab']);
        return $instance;
    }

    function form($instance)
    {
        $defaults = array('title' => '', 'new_tab' => true);
        $instance = wp_parse_args((array)$instance, $defaults); ?>
        <p>
            <label for="<?php echo $this->get_field_id('title'); ?>"><?php _e('Title :','tn') ?></label>
            <input type="text" class="widefat" id="<?php echo $this->get_field_id('title'); ?>"
                   name="<?php echo $this->get_field_name('title'); ?>"
                   value="<?php if (!empty($instance['title'])) echo esc_attr($instance['title']); ?>"/>
        </p>
        <p>
            <label for="<?php echo $this->get_field_id('new_tab'); ?>"><?php _e('Open in new tab','tn'); ?></label>
            <input class="widefat" type="checkbox" id="<?php echo $this->get_field_id('new_tab'); ?>"
                   name="<?php echo $this->get_field_name('new_tab'); ?>" value="true" <?php if (!empty($instance['new_tab'])) echo 'checked="checked"'; ?>  />
        </p>

    <?php
    }
}

?>