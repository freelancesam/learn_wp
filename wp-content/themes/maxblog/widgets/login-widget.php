<?php

//login widget
add_action('widgets_init', 'tn_register_login_widget');
function tn_register_login_widget()
{
    register_widget('tn_login_widget');
}

class tn_login_widget extends WP_Widget
{

    function tn_login_widget()
    {
        $widget_ops = array('classname' => 'login-widget', 'description' => __('[Sidebar Widget] Show Login Form', 'tn'));
        $this->WP_Widget('tn_login', __('TN . Login Form', 'tn'), $widget_ops);
    }

    //load widget
    function widget($args, $instance)
    {
        extract($args);

        $title = ($instance['title']) ? apply_filters('title', $instance['title']): '';
        $title = esc_attr($title);

        echo $before_widget;
        if (!empty($title)) echo $before_title . $title . $after_title;
        global $user_ID, $user_identity;
        if ($user_ID) : ?>
            <div class="login-widget-wrap clearfix">
                <div class="author-widget-title"><span class="author-title"><?php echo esc_attr($user_identity) ?></span></div><!--#author title -->
                <span class="author-thumb"><?php echo get_avatar($user_ID, $size = '105'); ?></span>
                <ul class="author-widget-content">
                    <li><a href="<?php echo esc_url(home_url()) ?>/wp-admin/"><?php _e('Dashboard', 'tn') ?> </a></li>
                    <li><a href="<?php echo esc_url(home_url()) ?>/wp-admin/profile.php"><?php _e('Your Profile', 'tn') ?> </a>
                    </li>
                    <li><a href="<?php echo esc_url(wp_logout_url()); ?>"><?php _e('Logout', 'tn') ?> </a></li>
                </ul>
            </div> <!--#has login -->
        <?php else: ?>
            <div class="form-login-widget-wrap clearfix">
                <form name="form-login" id="form-login" action="<?php echo esc_url(home_url()) ?>/wp-login.php" method="post">
                    <p class="login-user-name"><input name="log" id="log" type="text" value="<?php _e('Username', 'tn') ?>"
                               onfocus="if (this.value == '<?php _e('Username', 'tn') ?>') {this.value = '';}"
                               onblur="if (this.value == '') {this.value = '<?php _e('Username', 'tn') ?>';}"
                                     />
                    </p>

                    <p class="login-password"><input  name="pwd" id="pwd" type="password" value="<?php _e('Password', 'tn') ?> "
                            onfocus="if (this.value == '<?php _e('Password', 'tn') ?>') {this.value = '';}"
                            onblur="if (this.value == '') {this.value = '<?php _e('Password', 'tn') ?>';}"
                                    />
                    </p>

                    <input class="login-submit" type="submit" name="submit" value="<?php _e('Log in', 'tn') ?>"/>
                    <label class="rememberme"><input name="rememberme" id="rememberme" type="checkbox" checked="checked" value="forever"/> <?php _e('Remember Me', 'tn') ?></label>
                    <input type="hidden" name="redirect_to" value="<?php echo esc_attr($_SERVER['REQUEST_URI']); ?>"/>
                </form>
                    <ul class="register-links">
                        <?php if (get_option('users_can_register')) : ?><?php echo wp_register() ?><?php endif; ?>
                         <li><a href="<?php echo esc_url(home_url()) ?>/wp-login.php?action=lostpassword"><?php _e('Lost your password?', 'tn') ?></a></li>
                    </ul>
            </div> <!--login form -->
        <?php endif;
        echo $after_widget;
    }

    //update
    function update($new_instance, $old_instance)
    {
        $instance = $old_instance;
        $instance['title'] = strip_tags($new_instance['title']);
        return $instance;
    }

    //load form
    function form($instance)
    {
        $defaults = array('title' => __('Login', 'tn'));
        $instance = wp_parse_args((array)$instance, $defaults); ?>

        <p>
            <label for="<?php echo $this->get_field_id('title'); ?>"><?php _e('Title :','tn'); ?></label>
            <input class="widefat" type="text" id="<?php echo $this->get_field_id('title'); ?>" name="<?php echo $this->get_field_name('title'); ?>" value="<?php if (!empty($instance['title'])) echo esc_attr($instance['title']); ?>"/>
        </p>

    <?php
    }
}
?>