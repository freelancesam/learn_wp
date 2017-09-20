<?php
//About widget
add_action('widgets_init', 'tn_register_about_widget');
function tn_register_about_widget()
{
    register_widget('tn_about_widget');
}

class tn_about_widget extends WP_Widget
{
    function tn_about_widget()
    {
        $widget_ops = array('classname' => 'about-widget', 'description' => __('[Sidebar Widget] Display info about your site. Such as logo, name, text. This widget can be place to SIDEBAR', 'tn'));
        $this->WP_Widget('tn_about_widget', __('TN . About Site', 'tn'), $widget_ops);
    }

    function widget($args, $instance)
    {
        extract($args);
        $title = ($instance['title']) ? apply_filters('title', $instance['title']):'';
        $title = esc_attr($title);
        
        $name = ($instance['name'])? apply_filters('name', $instance['name']):'';
        $text = ($instance['text'])? apply_filters('text', $instance['text']): '';
        $image = ($instance['logo_image'])? apply_filters('logo_image', $instance['logo_image']): '';

        echo $before_widget;
        if (!empty($title)) echo $before_title . $title . $after_title;
        if(empty($image)) :
        ?>
            <h3 class="about-widget-name"><?php echo wp_kses($name,array('span'=> array())) ?></h3><!--#title-->
        <?php else : ?>
            <div class="about-widget-image"><img src="<?php echo esc_url($image); ?>" alt="<?php bloginfo( 'name' ) ?>" /></div><!--#image-->
        <?php   endif; ?>
        <div class="about-widget-content">
            <?php   if (!empty($text)) : ?>
                <p class="about-widget-text"><?php echo esc_textarea($text); ?></p>
            <?php endif; ?>
        </div><!--about-content-->

        <?php
        echo $after_widget;
    }

    function update($new_instance, $old_instance)
    {
        $instance = $old_instance;
        $instance['title'] = strip_tags($new_instance['title']);
        $instance['name'] = strip_tags($new_instance['name']);
        $instance['text'] = strip_tags($new_instance['text']);
        $instance['logo_image'] = strip_tags($new_instance['logo_image']);
        return $instance;
    }

    function form($instance)
    {
        $defaults = array( 'title' =>__( 'about us' , 'tn'), 'name'=>'' ,'text' =>'', 'logo_image' => '');
        $instance = wp_parse_args( (array) $instance, $defaults ); ?>
        <p>
            <label for="<?php echo $this->get_field_id( 'title' ); ?>"><?php _e('Title:','tn');?></label>
            <input type="text" class="widefat" id="<?php echo $this->get_field_id( 'title' ); ?>" name="<?php echo $this->get_field_name( 'title' ); ?>" value="<?php if( !empty($instance['title']) ) echo esc_attr($instance['title']); ?>"/>
        </p>

        <p>
        <label for="<?php echo $this->get_field_id( 'name' ); ?>"><?php _e('About Site:','tn'); ?></label>
        <input type="text" class="widefat" id="<?php echo $this->get_field_id( 'name' ); ?>" name="<?php echo $this->get_field_name( 'name' ); ?>" value="<?php if( !empty($instance['name']) ) echo esc_attr($instance['name']); ?>"/>
        </p>
        <p>
            <label for="<?php echo $this->get_field_id( 'text' ); ?>"><?php _e('Short text about the site:','tn'); ?></label>
            <textarea rows="10" cols="50" id="<?php echo $this->get_field_id( 'text' ); ?>" name="<?php echo $this->get_field_name('text'); ?>" class="widefat"><?php echo esc_textarea($instance['text']); ?></textarea>
        </p>
        <p>
            <label for="<?php echo $this->get_field_id( 'logo_image' ); ?>"><?php _e('Logo URL (optional):','tn'); ?></label>
            <input type="text" class="widefat" id="<?php echo $this->get_field_id( 'logo_image' ); ?>" name="<?php echo $this->get_field_name( 'logo_image' ); ?>" value="<?php if( !empty($instance['logo_image']) ) echo esc_url($instance['logo_image']); ?>" />
        </p>
    <?php
    }
}
?>
