<?php

/*-----------------------------------------------------------------------------------
	Plugin Name: Blog Post Carousel Widget
	Description: A widget that allows the display of blog posts in a Carousel.

-----------------------------------------------------------------------------------*/
// Add function to widgets_init that'll load our widget.
add_action( 'widgets_init', 'rd_blog_widgets' );
// Register widget.
function rd_blog_widgets() {
	register_widget( 'RD_Blog_Widget' );
}
// Widget class.
class rd_blog_widget extends WP_Widget {

/*-----------------------------------------------------------------------------------*/
/*	Widget Setup
/*-----------------------------------------------------------------------------------*/

	function RD_Blog_Widget() {

		/* Widget settings. */
		$widget_ops = array( 'classname' => 'rd_blog_widget', 'description' => __('A widget that displays your latest posts in a carousel.', 'thefoxwp') );
		/* Widget control settings. */

		$control_ops = array( 'width' => 300, 'height' => 350, 'id_base' => 'rd_blog_widget' );

		/* Create the widget. */

		parent::__construct( 'rd_blog_widget', __('TheFox Blog Post Carousel Widget', 'thefoxwp'), $widget_ops, $control_ops );

	}
	
	

/*-----------------------------------------------------------------------------------*/
/*	Display Widget
/*-----------------------------------------------------------------------------------*/

	function widget( $args, $instance ) {
		extract( $args );
		$title = apply_filters('widget_title', $instance['title'] );
		/* Our variables from the widget settings. */
		$number = $instance['number'];
				$cat = $instance['category'];
		if(!isset($cat)){
		$cat = 'all';	
		}
		$show_tn = isset( $instance['show_tn'] ) ? $instance['show_tn'] : false;  
		/* Before widget (defined by themes). */
		echo !empty( $before_widget ) ? $before_widget : '';
		/* Display Widget */
		?>
<?php /* Display the widget title if one was input (before and after defined by themes). */
				if ( $title )
					echo !empty( $before_title ) ? $before_title : '';
echo !empty( $title ) ? $title : '';
echo !empty( $after_title ) ? $after_title : '';
				
				$content = "<div class='sidebarcarousel'>[carousel_posts_sc type='cbp_type07' posts_per_line='1' category='".$cat."' to_show='".$number."']</div>";
				
				
				echo do_shortcode( $content );

		/* After widget (defined by themes). */

		echo !empty( $after_widget ) ? $after_widget : '';

	}


/*-----------------------------------------------------------------------------------*/
/*	Update Widget
/*-----------------------------------------------------------------------------------*/

	function update( $new_instance, $old_instance ) {
		$instance = $old_instance;
		/* Strip tags to remove HTML (important for text inputs). */
		$instance['title'] = strip_tags( $new_instance['title'] );
		$instance['category'] = strip_tags( $new_instance['category'] );  
		$instance['number'] = strip_tags( $new_instance['number'] );  
		
	
		/* No need to strip tags for.. */
		return $instance;
	}

/*-----------------------------------------------------------------------------------*/
/*	Widget Settings
/*-----------------------------------------------------------------------------------*/


	function form( $instance ) {
		/* Set up some default widget settings. */

		$defaults = array(
		'title' => 'Latest Posts.',
		'category' => 'all',
		'number' => 4
		);
		$instance = wp_parse_args( (array) $instance, $defaults ); ?>
<!-- Widget Title: Text Input -->
<p>
  <label for="<?php echo esc_attr($this->get_field_id( 'title' )); ?>">
    <?php _e('Title:', 'thefoxwp') ?>
  </label>
  <input type="text" class="widefat" id="<?php echo esc_attr($this->get_field_id( 'title' )); ?>" name="<?php echo esc_attr($this->get_field_name( 'title' )); ?>" value="<?php echo esc_attr($instance['title']); ?>" />
</p>
<!-- Widget Title: Text Input -->
<p>
  <label for="<?php echo esc_attr($this->get_field_id( 'number' )); ?>">
    <?php _e('Amount to show:', 'thefoxwp') ?>
  </label>
  <input type="text" class="widefat" id="<?php echo esc_attr($this->get_field_id( 'number' )); ?>" name="<?php echo esc_attr($this->get_field_name( 'number' )); ?>" value="<?php echo esc_attr($instance['number']); ?>" />
</p>
    <!-- Category Select Menu -->   
    <p>
        <select id="<?php echo $this->get_field_id('category'); ?>" name="<?php echo $this->get_field_name('category'); ?>" class="widefat" style="width:100%;" >
        	<option value="all">All</option>
            <?php foreach(get_terms('category','parent=0&hide_empty=0') as $term) { ?>
            <option <?php selected( $instance['category'], $term->slug ); ?> value="<?php echo $term->slug; ?>"><?php echo $term->name; ?></option>
            <?php } ?>      
        </select>
    </p>


<?php

	}
}

?>
