<?php
add_action( 'widgets_init', 'tn_register_comments_widget' );
function tn_register_comments_widget() {
    register_widget( 'tn_comments' );
}
class tn_comments extends WP_Widget {

    function tn_comments() {
        $widget_ops = array( 'classname' => 'comments-widget', 'description'=> __('[Sidebar Widget] Display latest comments with Gravatar. This widget can be place in SIDEBAR','tn'));
        $this->WP_Widget( 'comments-list-widget',__('TN . Latest Comments', 'tn'), $widget_ops );
    }

    function widget( $args, $instance ) {
        extract( $args );

        $title = ($instance['title']) ?  esc_attr($instance['title']) : '';
        $num_comments = ($instance['num_comments'])? $instance['num_comments'] : 5;

        echo $before_widget;
        if (!empty($title))
            echo $before_title. $title .$after_title;
            $args = array(
            'status' => 'approve',
            'number'=>$num_comments,
            'orderby'=>'comment_date_gmt',
            'order' =>'DECS',
        );
        $comments = get_comments($args);
          ?>
        <ul class="comment-widget-content-wrap">
             <?php foreach ($comments as $comment) : ?>
                <li class="clearfix">
                    <div class="author-thumb-wrap author-avatar">
                        <?php echo get_avatar( $comment, 75 ); ?>
                     </div>
                  <div class="comment-widget-content">
                    <div class="comments-widget-author"><span><?php echo esc_attr(strip_tags($comment->comment_author)) ?></span></div>
                    <a href="<?php echo get_permalink($comment->comment_post_ID ); ?>#comment-<?php echo esc_attr($comment->comment_ID); ?>">
                        <?php echo wp_trim_words(esc_attr(strip_tags($comment->comment_content)),10, '...' ); ?>
                    </a>
                </div>
                <div class="humman-time"><?php echo human_time_diff( strtotime($comment->comment_date) , current_time('timestamp') ) . __(' ago','tn'); ?></div>
            </li>
    <?php endforeach; ?>
       </ul>
   <?php  echo $after_widget;
    }

    function update( $new_instance, $old_instance ) {
        $instance = $old_instance;
        $instance['title'] = strip_tags( $new_instance['title'] );
        $instance['num_comments'] = absint(strip_tags($new_instance['num_comments']));
        return $instance;
    }

    function form( $instance ) {
        $defaults = array( 'title' =>__( 'latest comments' , 'tn'), 'num_comments' => '5' );
        $instance = wp_parse_args( (array) $instance, $defaults ); ?>
        <p>
            <label for="<?php echo $this->get_field_id( 'title' ); ?>"><?php _e('Title :','tn') ?></label>
            <input type ="text" class="widefat" id="<?php echo $this->get_field_id( 'title' ); ?>" name="<?php echo $this->get_field_name( 'title' ); ?>" value="<?php echo esc_attr($instance['title']); ?>" />
        </p>
        <p>
            <label for="<?php echo $this->get_field_id( 'num_comments' ); ?>"><?php __('Number of comments:','tn') ?></label>
            <input type="text" class="widefat" id="<?php echo $this->get_field_id( 'num_comments' ); ?>" name="<?php echo $this->get_field_name( 'num_comments' ); ?>" value="<?php echo esc_attr($instance['num_comments']); ?>"/>
        </p>
    <?php
    }
}
?>