<?php
add_action('widgets_init','tn_register_twitter_widget');

function tn_register_twitter_widget() {
    register_widget('tn_twitter');
}


class tn_twitter extends WP_Widget {

    function tn_twitter() {
        $widget_ops = array( 'classname' => 'widget-twitter', 'description' => __('[Sidebar Widget] Show tweets from twitter.com with the Twitter API. This Widget can place in SIDEBAR.','tn') );
        $this->WP_Widget( 'tn-twitter-widget', __('TN . Twitter Tweets', 'tn'), $widget_ops );
    }

    //Show widget
    function widget( $args, $instance ) {
        extract( $args );

        $title = ($instance['title']) ? esc_attr($instance['title']) : '';
        $options['twitter_user'] = ($instance['twitter_user']) ? $instance['twitter_user'] : '';
        $options['num_tweets'] = ($instance['num_tweets']) ? $instance['num_tweets'] : 5;
        $twitter_api['consumer_key'] = ($instance['consumer_key']) ? $instance['consumer_key'] : '';
        $twitter_api['consumer_secret'] = ($instance['consumer_secret']) ? $instance['consumer_secret'] : '';
        $twitter_api['access_token'] = ($instance['access_token']) ? $instance['access_token'] : '';
        $twitter_api['access_secret'] = ($instance['access_secret']) ? $instance['access_secret'] : '';

        $tweets_data = $this->get_tweets_data($twitter_api,$options);
        $id = uniqid('moduleW_');
        $slider_options['id'] = $id;
        $slider_options['directionNav'] = false;
        $slider_options['controlNav'] = true;
        tn_slider_data($id,$slider_options);

        echo $before_widget;

        ?>
        <div class="twitter-widget-content-wrap">
            <div class="twitter-widget-title-wrap clearfix">
                <div class="twitter-widget-icon"><i class="fa fa-twitter"></i></div>
                <!--#icon -->
                <?php if (!empty($title)) echo '<div class="twitter-widget-title"><h3>' . esc_attr($title) . '</h3></div>'; ?>
            </div><!-- tittle wrap -->
            <div id="<?php echo $id; ?>">
                <div class="tn-flexslider slider-loading clearfix">
                    <ul class="tn-slides">
                        <?php
                        foreach ($tweets_data as $tweet) :
                            $tweet->text = preg_replace('/((https?|s?ftp|ssh)\:\/\/[^"\s\<\>]*[^.,;\'">\:\s\<\>\)\]\!])/', '<a href="\\1">\\1</a>', $tweet->text);
                            $tweet->text = preg_replace('/\B@([_a-z0-9]+)/i', '<a href="http://twitter' . '.com/\\1">@\\1</a>', $tweet->text);
                            ?>
                            <li><p><?php echo wp_kses($tweet->text, array('a'=>array('href'=>array(),'title'=>array()))); ?></p></li>
                        <?php endforeach; ?>
                    </ul>
                </div>
                <!--#tweets -->
            </div><!--#twitter feed -->
        </div><!--#twitter content wrap -->
        <?php
        echo $after_widget;
    }

    //get tweets add cache
    function  get_tweets_data($twitter_api, $options)
    {
        $cache_data_name = 'tn_tweet_feed';
        $cache = get_transient($cache_data_name);
        $cache_mins = 10;
        if ($cache === false) {
            $data = $this->get_tweets($twitter_api, $options);
            if ($data) {
                set_transient($cache_data_name, $data, 60 * $cache_mins);
                return $data;
            }
        } else {
            return $cache;
        }
    }

    //get tweets from twitter
    function get_tweets($api,$options)
    {
        require_once get_template_directory() . '/lib/twitteroauth/twitteroauth.php';
        $twitterConnection = new TwitterOAuth(
            $api['consumer_key'], // consumer key
            $api['consumer_secret'], // consumer secret
            $api['access_token'], // access token
            $api['access_secret'] // access token secret
        );
        $data = $twitterConnection->get('statuses/user_timeline', array('screen_name' => $options['twitter_user'], 'count' => $options['num_tweets'], 'exclude_replies' => false));
        if ($twitterConnection->http_code === 200) {
            return $data;
        }

        return false;
    }

    //update
    function update( $new_instance, $old_instance ) {
        $instance = $old_instance;

        delete_transient('tn_tweet_feed');
        $instance['title'] = strip_tags($new_instance['title']);
        $instance['twitter_user'] =  strip_tags($new_instance['twitter_user']);
        $instance['num_tweets'] =  strip_tags($new_instance['num_tweets']);
        $instance['consumer_key'] =  strip_tags($new_instance['consumer_key']);
        $instance['consumer_secret'] =  strip_tags($new_instance['consumer_secret']);
        $instance['access_token'] = strip_tags($new_instance['access_token']);
        $instance['access_secret'] =  strip_tags($new_instance['access_secret']);

        return $instance;
    }

    //from options
    function form( $instance ) {

        $defaults = array( 'title' => __('Latest Tweets','tn'), 'twitter_user' => '', 'num_tweets' => 5,'consumer_key' => '', 'consumer_secret' => '','access_token' => '', 'access_secret' => '' );
        $instance = wp_parse_args( (array) $instance, $defaults );
        ?>

        <p>
            <label for="<?php echo $this->get_field_id( 'title' ); ?>"><strong><?php _e('Title:', 'tn');?></strong></label>
            <input type="text" class="widefat"  id="<?php echo $this->get_field_id( 'title' ); ?>" name="<?php echo $this->get_field_name( 'title' ); ?>" value="<?php echo esc_attr($instance['title']); ?>"/>
        </p>
        <p>
            <label for="<?php echo $this->get_field_id( 'twitter_user' ); ?>"><strong><?php _e('Twitter Name:', 'tn');?></strong></label>
            <input type="text"  class="widefat"  id="<?php echo $this->get_field_id( 'twitter_user' ); ?>" name="<?php echo $this->get_field_name( 'twitter_user' ); ?>" value="<?php echo esc_attr($instance['twitter_user']); ?>"/>
        </p>
        <p>
            <label for="<?php echo $this->get_field_id( 'num_tweets' ); ?>"><strong><?php _e('Number of Tweets:', 'tn');?></strong></label>
            <input type="text"  class="widefat"  id="<?php echo $this->get_field_id( 'num_tweets' ); ?>" name="<?php echo $this->get_field_name( 'num_tweets' ); ?>" value="<?php echo esc_attr($instance['num_tweets']); ?>"/>
        </p>
        <p><a href="http://dev.twitter.com/apps" target="_blank"><?php _e('Create your Twitter App', 'tn'); ?></a></p>
        <p>
            <label for="<?php echo $this->get_field_id( 'consumer_key' ); ?>"><?php _e('Twitter Consumer Key:', 'tn') ?></label>
            <input type="text" class="widefat" id="<?php echo $this->get_field_id( 'consumer_key' ); ?>" name="<?php echo $this->get_field_name( 'consumer_key' ); ?>" value="<?php echo esc_attr($instance['consumer_key']); ?>" />
        </p>
        <p>
            <label for="<?php echo $this->get_field_id( 'consumer_secret' ); ?>"><?php _e('Twitter Consumer Secret:', 'tn') ?></label>
            <input type="text" class="widefat" id="<?php echo $this->get_field_id( 'consumer_secret' ); ?>" name="<?php echo $this->get_field_name( 'consumer_secret' ); ?>" value="<?php echo esc_attr($instance['consumer_secret']); ?>" />
        </p>
        <p>
            <label for="<?php echo $this->get_field_id( 'access_token' ); ?>"><?php _e('Twitter Access Token:', 'tn');?></label>
            <input type="text" class="widefat" id="<?php echo $this->get_field_id( 'access_token' ); ?>" name="<?php echo $this->get_field_name( 'access_token' ); ?>" value="<?php echo esc_attr($instance['access_token']); ?>" />
        </p>
        <p>
            <label for="<?php echo $this->get_field_id( 'access_secret' ); ?>"><?php _e('Twitter Access Secret:', 'tn');?></label>
            <input type="text" class="widefat" id="<?php echo $this->get_field_id( 'access_secret' ); ?>" name="<?php echo $this->get_field_name( 'access_secret' ); ?>" value="<?php echo esc_attr($instance['access_secret']); ?>" />
        </p>

    <?php
    }
}
