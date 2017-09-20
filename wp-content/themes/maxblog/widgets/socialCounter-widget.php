<?php
add_action('widgets_init','tn_register_socialCount_widget');

function tn_register_socialCount_widget() {
    register_widget('tn_social_count');
}

class tn_social_count extends WP_Widget {

    function tn_social_count() {
        $widget_ops = array('classname' => 'social-counter-widget','description' => __('[Sidebar Widget] Show the counting data of Twitter, Facebook, Google+, YouTube, Instagram', 'tn'));

        /* Create the widget. */
        $this->WP_Widget('social-count-widget', __('TN . Social Count', 'tn'), $widget_ops);

    }

    //Show widget
    function widget( $args, $instance ) {
        extract( $args );
        $title = ($instance['title']) ? esc_attr($instance['title']) : '';
        $facebook_page = ($instance['facebook_page']) ? $instance['facebook_page'] : '';
        $youtube_user = ($instance['youtube_user']) ? $instance['youtube_user'] : '';
        $dribbble_user = ($instance['dribbble_user']) ? $instance['dribbble_user'] : '';
        $soundcloud_user = ($instance['soundcloud_user']) ? $instance['soundcloud_user'] : '';
        $soundcloud_api = ($instance['soundcloud_api']) ? $instance['soundcloud_api'] : '';
        $instagram_api = ($instance['instagram_api']) ? $instance['instagram_api'] : '';
        $rss_url = ($instance['rss_url']) ?  $instance['rss_url'] : '';
        $twitter_user = ($instance['twitter_user']) ? $instance['twitter_user'] : '';
        $twitter_api['consumer_key'] = ($instance['consumer_key']) ? $instance['consumer_key'] : '';
        $twitter_api['consumer_secret'] = ($instance['consumer_secret']) ? $instance['consumer_secret'] : '';
        $twitter_api['access_token'] = ($instance['access_token']) ? $instance['access_token'] : '';
        $twitter_api['access_secret'] = ($instance['access_secret']) ? $instance['access_secret'] : '';


        echo $before_widget;
        if (!empty($title))   echo $before_title . $title . $after_title;
        ?>
        <div class="social-count-wrap">
        <?php
        //Facebook Like
        if (!empty($facebook_page)) :
            $option['facebook_page'] = $facebook_page;
            $facebook_count = $this->get_counter_data('facebook_page', $option);
            ?>
            <div class="counter-element color-facebook">
                <a target="_blank" href="http://facebook.com/<?php echo esc_attr($facebook_page); ?>" class="facebook"
                   title="<?php _e('Connect at Facebook', 'tn'); ?>">
                    <i class="fa fa-facebook"></i>
                    <span class="num-count"><?php echo $this->Show_over_100k($facebook_count); ?></span>
                    <span class="text-count"><?php _e('fans', 'tn'); ?></span></a>
            </div><!--facebook like count -->
        <?php  endif;

        //Twitter Follower
        if (!empty($twitter_user)) :
            $option['twitter_user'] = $twitter_user;
            $option['twitter_api'] = $twitter_api;
            $twitter_count = $this->get_counter_data('twitter', $option);
            ?>
            <div class="counter-element color-twitter">
                <a target="_blank" href="http://twitter.com/<?php echo esc_attr($twitter_user); ?>" class="twitter"
                   title="<?php _e('Follow on twitter', 'tn'); ?>">
                    <i class="fa fa-twitter"></i>
                    <span class="num-count"><?php echo $this->Show_over_100k($twitter_count); ?></span>
                    <span class="text-count"><?php _e('followers', 'tn'); ?></span></a>
            </div><!--twitter follower count -->
        <?php endif;

        //Instagram Follower
        if (!empty($instagram_api)):
            $option['instagram_api'] = $instagram_api;
            $data_instagram = $this->get_counter_data('instagram', $option);
            ?>
            <div class="counter-element color-instagram">
                <a target="_blank" href="<?php echo esc_url($data_instagram['url']) ?>"
                   title="<?php _e('Follow on instagram', 'tn'); ?>">
                    <i class="fa fa-instagram"></i>
                    <span class="num-count"><?php echo $this->Show_over_100k($data_instagram['count']); ?></span>
                    <span class="text-count"><?php _e('Followers', 'tn'); ?></span>
                </a>
            </div><!--instagram follower count -->
        <?php endif;

        //Youtube Counter
        if (!empty($youtube_user)) :
            $option['youtube_user'] = $youtube_user;
            $youtube_count = $this->get_counter_data('youtube', $option);
            ?>
            <div class="counter-element color-youtube">
                <a target="_blank" href="http://www.youtube.com/user/<?php echo esc_attr($youtube_user); ?>"
                   title="<?php _e('Subscribers me', 'tn'); ?>">
                    <i class="fa fa-youtube"></i>
                    <span class="num-count"><?php echo $this->Show_over_100k($youtube_count); ?></span>
                    <span class="text-count"><?php _e('Subscribers', 'tn'); ?></span>
                </a>
            </div><!--youtube subscribers count -->
        <?php endif;

        //Dribbble Counter
        if (!empty($dribbble_user)) :
            $option['dribbble_user'] = $dribbble_user;
            $dribbble_count = $this->get_counter_data('dribbble', $option);
            ?>
            <div class="counter-element color-dribbble">
                <a target="_blank" href="http://dribbble.com/<?php echo esc_attr($dribbble_user); ?>"
                   title="<?php _e('Follow on dribbble', 'tn'); ?>">
                    <i class="fa fa-dribbble"></i>
                    <span class="num-count"><?php echo $this->Show_over_100k($dribbble_count); ?></span>
                    <span class="text-count"><?php _e('Followers', 'tn'); ?></span>
                </a>
            </div><!--dribbble follower count -->
        <?php endif;

        //SoundCloud Counter
        if (!empty($soundcloud_user) && !empty($soundcloud_api)):
            $option['soundcloud_user'] = $soundcloud_user;
            $option['soundcloud_api'] = $soundcloud_api;
            $soundcloud_data = $this->get_counter_data ('soundcloud', $option);
            ?>
            <div class="counter-element color-soundcloud">
                <a target="_blank" href="<?php echo esc_url($soundcloud_data['url']); ?>"
                   title="<?php _e('Follow on soundclound', 'tn'); ?>">
                    <i class="fa fa-soundcloud"></i>
                    <span class="num-count"><?php echo $this->Show_over_100k($soundcloud_data['count']); ?></span>
                    <span class="text-count"><?php _e('Followers', 'tn'); ?></span>
                </a>
            </div><!--soundcloud follower count -->
        <?php endif;

        //rss
        if(!empty($rss_url)) :
        ?>
        <div class="counter-element color-rss">
            <a target="_blank" href="<?php echo esc_url($rss_url); ?>"
               title="<?php _e('Subscribe Rss', 'tn'); ?>">
                <i class="fa fa-rss"></i>
                <span class="num-count"><?php _e('Subscribe', 'tn'); ?></span>
                <span class="text-count"><?php _e('RSS Feeds', 'tn'); ?></span>
            </a>
        </div><!--rss Subscribe feed -->

        <?php endif;  ?>
        </div><!-- #social count wrap -->
        <?php
        echo $after_widget;
    }

    function Show_over_100k($number)
    {
        $number = intval($number);
        if ($number > 100000) $number = round($number / 1000, 1) . 'k';
        return esc_attr($number);
    }

    //get Count and save to cache.
    function get_counter_data($social = '', $option = array())
    {
        $cache_data_name = 'tn-counter' . $social;
        $cache = get_transient($cache_data_name);
        if ($cache === false) {
            $data = '';
            $cache_hours = 6;
            switch ($social) {
                case 'facebook_page' :
                    $data = $this->facebook_counter($option['facebook_page']);
                    set_transient($cache_data_name, $data, 60 * 60 * $cache_hours);
                    break;
                case 'twitter' :
                    $data = $this->twitter_counter($option['twitter_user'], $option['twitter_api']);
                    set_transient($cache_data_name, $data, 60 * 60 * $cache_hours);
                    break;
                case 'instagram' :
                    $data = $this->instagram_counter($option['instagram_api']);
                    set_transient($cache_data_name, $data, 60 * 60 * $cache_hours);
                    break;
                case 'youtube' :
                    $data = $this->youtube_counter($option['youtube_user']);
                    set_transient($cache_data_name, $data, 60 * 60 * $cache_hours);
                    break;
                case 'dribbble' :
                    $data = $this->dribbble_counter($option['dribbble_user']);
                    set_transient($cache_data_name, $data, 60 * 60 * $cache_hours);
                    break;

                case 'soundcloud' :
                    $data = $this->soundclound_counter($option['soundcloud_user'], $option['soundcloud_api']);
                    set_transient($cache_data_name, $data, 60 * 60 * $cache_hours);
                    break;
            }
            return $data;
        } else {
            return $cache;
        }
    }

    //get facebook like
    public function facebook_counter($facebook_page)
    {
        $json = wp_remote_get('http://graph.facebook.com/' . urlencode($facebook_page));
        if (is_wp_error($json)) {
            return false;
        }

        $data = json_decode($json['body']);
        return intval($data->likes);
    }

    //get twitter followers
    function  twitter_counter($user,$api){
        require_once get_template_directory() . '/lib/twitteroauth/twitteroauth.php';
        $twitterConnection = new TwitterOAuth(
            $api['consumer_key'],
            $api['consumer_secret'],
            $api['access_token'],
            $api['access_secret']
        );
        $data = $twitterConnection->get('users/show', array('screen_name' => $user));
        return (isset($data->followers_count) ? intval($data->followers_count) : false);
    }

    //get instagram followers
    function  instagram_counter($api)
    {
        $data = array();
        $instagram_user = explode(".", $api);
        $url = 'https://api.instagram.com/v1/users/' . $instagram_user[0] . '/?access_token=' . $api;
        $request = wp_remote_get($url);
        $data_respone = json_decode(wp_remote_retrieve_body($request), true);
        $data['count'] = intval($data_respone['data']['counts']['followed_by']);
        $data['user_name'] = esc_attr(strip_tags($data_respone['data']['user']));
        $data['instagram_url'] = 'http://instagram.com/' . urlencode(esc_attr($data['user_name']));
        return $data;
    }

    //get youtube followers
    function  youtube_counter($user)
    {
        $count = '';
        $data = file_get_contents('http://gdata.youtube.com/feeds/api/users/' . $user);
        if (!empty($data)) {
            $xml = new SimpleXMLElement($data);
            $stats_data = (array)$xml->children('yt', true)->statistics->attributes();
            $stats_data = $stats_data['@attributes'];
            $count = intval($stats_data['subscriberCount']);
        }
        return $count;
    }

    //get Dribbble follower
    function dribbble_counter($user)
    {
        $request = wp_remote_get('http://api.dribbble.com/' . $user);
        if (!is_wp_error($request)) {
            $json = json_decode($request['body']);
            return (intval($json->followers_count));
        }
    }

    //get SoundCloud followers
    function soundclound_counter($user, $api)
    {
        $data = array();
        $url = 'http://api.soundcloud.com/users/' . $user . '.json?consumer_key=' . $api;
        $request = wp_remote_get($url);
        $data_response = json_decode(wp_remote_retrieve_body($request), true);
        if (!empty($data_response)) {
            $data['count'] = intval($data_response['followers_count']);
            $data['url'] = esc_url($data_response['permalink_url']);
        }
        return $data;
    }


    //update widget
    function update( $new_instance, $old_instance ) {
        $instance = $old_instance;
        //remove cache
        delete_transient('tn-counter-facebook_page');
        delete_transient('tn-counter-twitter');
        delete_transient('tn-counter-instagram');
        delete_transient('tn-counter-youtube');
        delete_transient('tn-counter-soundcloud');
        delete_transient('tn-counter-dribbble');

        $instance['title'] = strip_tags($new_instance['title']);
        $instance['facebook_page'] =  strip_tags($new_instance['facebook_page']);
        $instance['twitter_user'] =  strip_tags($new_instance['twitter_user']);
        $instance['consumer_key'] =  strip_tags($new_instance['consumer_key']);
        $instance['consumer_secret'] =  strip_tags($new_instance['consumer_secret']);
        $instance['access_token'] =  strip_tags($new_instance['access_token']);
        $instance['access_secret'] =  strip_tags($new_instance['access_secret']);
        $instance['youtube_user'] =  strip_tags($new_instance['youtube_user']);
        $instance['dribbble_user'] =  strip_tags($new_instance['dribbble_user']);
        $instance['soundcloud_user'] =  strip_tags($new_instance['soundcloud_user']);
        $instance['soundcloud_api'] =  strip_tags($new_instance['soundcloud_api']);
        $instance['instagram_api'] =  strip_tags($new_instance['instagram_api']);
        $instance['rss_url'] =  wp_kses($new_instance['rss_url'],array('a'=>array('href'=>array())));
        return $instance;
    }

    //form setting
    function form( $instance ) {
        
        $defaults = array('title' => '','youtube_user' => '','dribbble_user' => '','twitter_user' => '','facebook_page' => '','rss_url' => '','soundcloud_user' => '','soundcloud_api' => '','instagram_api' => '','consumer_key' => '','consumer_secret' => '','access_token' => '', 'access_secret' => '');
        $instance = wp_parse_args( (array) $instance, $defaults ); ?>

        <p>
            <label for="<?php echo $this->get_field_id( 'title' ); ?>"><strong><?php _e('Title:', 'tn');?></strong></label>
            <input type="text" id="<?php echo $this->get_field_id( 'title' ); ?>" name="<?php echo $this->get_field_name( 'title' ); ?>" value="<?php echo esc_attr($instance['title']); ?>" class="widefat" />
        </p>

        <p>
            <label for="<?php echo $this->get_field_id( 'facebook_page' ); ?>"><strong><?php _e('Facebook Page Name:', 'tn');?></strong></label>
            <input type="text" class="widefat"   id="<?php echo $this->get_field_id( 'facebook_page' ); ?>" name="<?php echo $this->get_field_name( 'facebook_page' ); ?>" value="<?php echo esc_attr($instance['facebook_page']); ?>" />
        </p>

        <p>
            <label for="<?php echo $this->get_field_id( 'dribbble_user' ); ?>"><strong><?php _e('Dribbble User Name:', 'tn');?></strong></label>
            <input type="text"  class="widefat" id="<?php echo $this->get_field_id( 'dribbble_user' ); ?>" name="<?php echo $this->get_field_name( 'dribbble_user' ); ?>" value="<?php echo esc_attr($instance['dribbble_user']); ?>" />
        </p>

        <p>
            <label for="<?php echo $this->get_field_id( 'youtube_user' ); ?>"><strong><?php _e('Youtube User Name:', 'tn');?></strong></label>
            <input type="text"  class="widefat" id="<?php echo $this->get_field_id( 'youtube_user' ); ?>" name="<?php echo $this->get_field_name( 'youtube_user' ); ?>" value="<?php echo esc_attr($instance['youtube_user']); ?>"/>
        </p>

        <p>
            <label for="<?php echo $this->get_field_id( 'soundcloud_user' ); ?>"><strong><?php _e('SoundCloud User Name:','tn');?></strong> </label>
            <input type="text" class="widefat" id="<?php echo $this->get_field_id( 'soundcloud_user' ); ?>" name="<?php echo $this->get_field_name( 'soundcloud_user' ); ?>" value="<?php echo esc_attr($instance['soundcloud_user']); ?>"/>

            <label for="<?php echo $this->get_field_id( 'soundcloud_api' ); ?>"><?php _e('Soundcloud API Key :','tn') ?> </label>
            <input type="text" class="widefat" id="<?php echo $this->get_field_id( 'soundcloud_api' ); ?>" name="<?php echo $this->get_field_name( 'soundcloud_api' ); ?>" value="<?php echo esc_attr($instance['soundcloud_api']); ?>"/>
        </p>

        <p>
            <label for="<?php echo $this->get_field_id( 'instagram_api' ); ?>"><strong><?php _e('Instagram Access Token Key:','tn') ?></strong> </label>
            <input type="text" class="widefat" id="<?php echo $this->get_field_id( 'instagram_api' ); ?>" name="<?php echo $this->get_field_name( 'instagram_api' ); ?>" value="<?php echo esc_attr($instance['instagram_api']); ?>"/>
            <i>Get Instagram Access Token <a target="_blank" href="http://jelled.com/instagram/access-token">here</a></i>
        </p>

        <p>
            <label for="<?php echo $this->get_field_id( 'rss_url' ); ?>"><strong><?php _e('RSS Url:', 'tn');?></strong></label>
            <input type="text"  class="widefat"  id="<?php echo $this->get_field_id( 'rss_url' ); ?>" name="<?php echo $this->get_field_name( 'rss_url' ); ?>" value="<?php echo esc_url($instance['rss_url']); ?>"/>
        </p>

        <p>
            <label for="<?php echo $this->get_field_id( 'twitter_user' ); ?>"><strong><?php _e('Twitter Name:', 'tn');?></strong></label>
            <input type="text"  class="widefat"  id="<?php echo $this->get_field_id( 'twitter_user' ); ?>" name="<?php echo $this->get_field_name( 'twitter_user' ); ?>" value="<?php echo esc_attr($instance['twitter_user']); ?>"/>
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