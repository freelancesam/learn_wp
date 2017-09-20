<?php
//ads widget
function tn_register_ads_widget()
{
    register_widget('tn_ads_widget');
}
//register widget
class tn_ads_widget extends WP_Widget
{
    function tn_ads_widget()
    {
        $widget_ops = array('classname' => 'tn-ads-widget', 'description' => __('Show your custom ads, your banner JS or Google Adsense code, this widget can be place in ANYWHERE', 'tn'));
        $this->WP_Widget('tn_ads_widget', __('TN . Box Advertising', 'tn'), $widget_ops);
    }

    //load widget
    function widget($args, $instance)
    {
        extract($args);
        $url = ($instance['url']) ? apply_filters('url', $instance['url']) : '';
        $img = ($instance['image_url']) ? apply_filters('image_url', $instance['image_url']) : '';
        $google_ads = ($instance['google_ads']) ? apply_filters('google_ads', $instance['google_ads']) : '';
        echo $before_widget; ?>
        <div class="ads-widget-content-wrap clearfix">
          <?php if(!empty($img)) : ?>
            <?php if (!empty($url)) : ?>
                    <a class="ads-widget-link" target="_blank" href="<?php echo esc_url($url); ?>"><img class="ads-image" src="<?php echo esc_url($img); ?>" alt="<?php bloginfo('name') ?>"></a>
                <?php else : ?>
                    <img class="ads-widget-image" src="<?php echo esc_url($img); ?>" alt="<?php bloginfo('name') ?>">
                <?php endif; ?>
           <?php else : ?>
              <?php if(!empty($google_ads)) echo do_shortcode(stripcslashes($google_ads)); ?>
            <?php endif; ?>
          </div>
        <?php  echo $after_widget;
    }

    //update
    function update($new_instance, $old_instance)
    {
        $instance = $old_instance;
        $instance['url'] = strip_tags($new_instance['url']);
        $instance['image_url'] = strip_tags($new_instance['image_url']);
        $instance['google_ads'] = addslashes($new_instance['google_ads']);
        return $instance;
    }

    //load form
    function form($instance)
    {
        $defaults = array('url' => '', 'image_url' => '','google_ads'=>'');
        $instance = wp_parse_args( (array) $instance, $defaults );
        ?>

        <p>
            <label for="<?php echo $this->get_field_id('url'); ?>"><?php _e('Ads Link:', 'tn'); ?></label>
            <input class="widefat" id="<?php echo $this->get_field_id('url'); ?>" name="<?php echo $this->get_field_name('url'); ?>" type="text" value="<?php if( !empty($instance['url']) ) echo  esc_url($instance['url']); ?>"/>
        </p>
        <p>
            <label
                for="<?php echo $this->get_field_id('image_url'); ?>"><?php _e('Ads Image Url:', 'tn'); ?></label>
            <input class="widefat" id="<?php echo $this->get_field_id('image_url'); ?>" name="<?php echo $this->get_field_name('image_url'); ?>" type="text" value="<?php if( !empty($instance['image_url']) ) echo esc_url($instance['image_url']); ?>"/>
        </p>
        <p>
            <label for="<?php echo $this->get_field_id( 'google_ads' ); ?>"><?php _e('JS or Google AdSense Code:','tn'); ?></label>
            <textarea rows="10" cols="50" id="<?php echo $this->get_field_id( 'google_ads' ); ?>" name="<?php echo $this->get_field_name('google_ads'); ?>" class="widefat"><?php echo esc_textarea(stripcslashes($instance['google_ads'])); ?></textarea>
        </p>
    <?php
    }
}

add_action('widgets_init', 'tn_register_ads_widget');
?>