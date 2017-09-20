<?php
add_action( 'widgets_init', 'tn_text_widget' );
function tn_text_widget() {
    register_widget( 'tn_text' );
}
class tn_text extends WP_Widget {

    function tn_text() {
        $widget_ops = array( 'classname' => 'text-widget', 'description'=> __('Show text or custom html. This widget can place in ANYWHERE','tn'));
        $this->WP_Widget( 'tn_text', __('TN . Text, HTML Code', 'tn'), $widget_ops );
    }

    function widget( $args, $instance ) {
        extract( $args );

        $title = ($instance['title']) ?  esc_attr($instance['title']) : '';

        $align = ($instance['align']) ? 'center' : 'left';
        echo $before_widget;
        if ($title) echo $before_title . $title . $after_title; ?>
        <div class="text-content-wrapper" style="text-align: <?php echo esc_attr($align); ?>">
            <?php echo apply_filters('the_content', stripcslashes($instance['content'])); ?>
        </div>
        <?php
        echo $after_widget;
        }

    function update( $new_instance, $old_instance ) {
        $instance = $old_instance;
        $instance['title'] = strip_tags( $new_instance['title'] );
        $instance['content'] = addslashes($new_instance['content']);
        $instance['align'] = strip_tags( $new_instance['align'] );
        return $instance;
    }

    function form( $instance ) {
        $instance = wp_parse_args( (array) $instance, array( 'title' =>__('Text' , 'tn'), 'content' => '', 'align' => '' )); ?>

        <p>
            <label for="<?php echo $this->get_field_id( 'title' ); ?>"><?php _e('Title:','tn') ?></label>
            <input type="text" class="widefat"  id="<?php echo $this->get_field_id( 'title' ); ?>" name="<?php echo $this->get_field_name( 'title' ); ?>" value="<?php if( !empty($instance['title']) ) echo esc_attr($instance['title']); ?>" />
        </p>
        <p>
            <label for="<?php echo $this->get_field_id( 'content' ); ?>"><?php _e('Input text or html:','tn') ?></label>
            <textarea class="widefat"  rows="10" cols="10" id="<?php echo $this->get_field_id( 'content' ); ?>" name="<?php echo $this->get_field_name( 'content' ); ?>" ><?php if( !empty( $instance['content'] ) ) echo esc_textarea(stripcslashes($instance['content'])); ?></textarea>
        </p>
        <p>
            <label for="<?php echo $this->get_field_id( 'align' ); ?>"><?php _e('Align center:','tn') ?></label>
            <input id="<?php echo $this->get_field_id( 'align' ); ?>" name="<?php echo $this->get_field_name( 'align' ); ?>" value="true" <?php if( !empty( $instance['align'] ) ) echo 'checked="checked"'; ?> type="checkbox" />
        </p>
    <?php
    }
}
?>