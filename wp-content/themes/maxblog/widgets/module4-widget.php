<?php
add_action('widgets_init', 'tn_register_module4_widget');

function tn_register_module4_widget()
{
    register_widget('tn_module4_widget');
}

class tn_module4_widget extends WP_Widget
{

    function tn_module4_widget()
    {
        $widget_ops = array('classname' => 'module4-widget', 'description' => __('[Content Widget] This widget can place in PRIMARY CONTENT or FULL WIDTH. please, Enable 3 Columns layout if you place it into FULL WIDTH. you also can enable 3 columns layout in PRIMARY CONTENT','tn'));
        $this->WP_Widget('module4-widget', __('TN . POSTS BOX 4', 'tn'), $widget_ops);
    }

    function widget($args, $instance)
    {
        extract($args);
        $array_query = array();
        $options = array();
        $title = ($instance['title']) ? esc_attr($instance['title']) : '';
        $array_query['posts_per_page'] = ($instance['posts_per_page']) ? $instance['posts_per_page'] : 6;
        $array_query['category_id'] = ($instance['cate']) ? $instance['cate'] : '';
        $array_query['category_ids'] = ($instance['cates']) ? $instance['cates'] : '';
        $array_query['tag_plus'] = ($instance['tags']) ? $instance['tags'] : '';
        $array_query['sort_order'] = ($instance['sort_order']) ? $instance['sort_order'] : 'date_post';
        $options['col_3'] = ($instance['col_3']) ? $instance['col_3'] : '';
        $pagination = ($instance['pagination']) ? $instance['pagination'] : 'next_prev';
        $options['cate_id'] = ($instance['cate']) ? $instance['cate'] : '';
        $options['child_cate'] = ($instance['child_cate']) ? $instance['child_cate'] : '';
        $options['num_child_cate'] = ($instance['num_child_cate']) ? $instance['num_child_cate'] : '';

        $query_data = tn_custom_query($array_query);
        $id = uniqid('module4_');
        if($title == 'default'){
            if ($options['cate_id'] !='all') {
                $title = '<a href="' . get_category_link($options['cate_id']) . '" title="' . strip_tags(get_cat_name($options['cate_id'])) . '">' . get_cat_name($options['cate_id']) . '</a>';
            } else $title = esc_attr__('POST BOX 4','tn');
        }

        //Show widget
        echo $before_widget;
        if(!empty($array_query['category_id']) && $array_query['category_id'] != 'all'){
            echo '<div class="tn-category-'.$array_query['category_id'].'">';
            if(!empty($title)) echo $before_title . $title . $after_title;
            echo '</div><!--#color title-->';
        } else {
            if(!empty($title)) echo $before_title . $title . $after_title;
        }
        ?>

        <div class="right-widget-title-wrap">
            <?php
            if(!empty($options['child_cate'])){

                echo  tn_sub_cate($options);
            };
            if (!empty($pagination) && ($pagination =='next_prev')) echo tn_ajax_pagination($id, $pagination); ?>
        </div><!-- #right widget title -->

        <?php
        if ($query_data) :?>
            <div id="<?php echo $id; ?>">
                <?php  echo tn_module4($query_data->posts ,$options); ?>
            </div><!-- #row -->
        <?php endif;

        if (!empty($pagination) &&($pagination =='loadmore')) echo tn_ajax_pagination($id, $pagination);

        //ajax load
        global $modules_data;
        $config = array();
        $config['module_id'] = $id;
        $config['module_name']= 'module4';
        $config['max_num_pages'] = $query_data->max_num_pages;
        $config['current_page'] = 1;
        foreach ($array_query as $k => $v)
            $config ['module_query'][$k] = $v;
        foreach ($options as $k => $v)
            $config['options'][$k] = $v;

        $modules_data[$id] = $config;

        wp_localize_script('tn-module-ajax', 'modules_data', $modules_data);

        echo $after_widget;
    }

    function update($new_instance, $old_instance)
    {
        $instance = $old_instance;
        $instance['title'] = strip_tags($new_instance['title']);
        $instance['cate'] = strip_tags($new_instance['cate']);
	    $instance['child_cate'] = strip_tags($new_instance['child_cate']);
        $instance['cates'] = strip_tags($new_instance['cates']);
        $instance['tags'] = strip_tags($new_instance['tags']);
        $instance['pagination'] = strip_tags($new_instance['pagination']);
        $instance['sort_order'] = strip_tags($new_instance['sort_order']);
        $instance['col_3'] = strip_tags($new_instance['col_3']);

        $instance['posts_per_page'] = absint(strip_tags($new_instance['posts_per_page']));
        $instance['num_child_cate'] = absint(strip_tags($new_instance['num_child_cate']));
        return $instance;
    }

    function form($instance)
    {
        $defaults = array('title' =>'default', 'sort_order' => 'date_post', 'pagination' => 'next_prev', 'posts_per_page' => 6, 'cate' => '', 'child_cate' => '', 'num_child_cate' => '', 'cates' => '', 'tags' => '', 'col_3'=>'');
        $instance = wp_parse_args((array)$instance, $defaults); ?>
        <p>
            <label for="<?php echo $this->get_field_id( 'title' ); ?>"><?php _e('Title (Auto generate title when set default):','tn') ?></label>
            <input type="text" class="widefat" id="<?php echo $this->get_field_id( 'title' ); ?>" name="<?php echo $this->get_field_name( 'title' ); ?>" value="<?php if( !empty($instance['title']) ) echo esc_attr($instance['title']); ?>" />
        </p>
        <p>
            <label for="<?php echo $this->get_field_id('cate'); ?>"><strong><?php _e('Category filter:', 'tn'); ?></strong></label>
            <select class="widefat" id="<?php echo $this->get_field_id('cate'); ?>" name="<?php echo $this->get_field_name('cate'); ?>">
                <option value='all' <?php if ($instance['cate'] == 'all') echo 'selected="selected"'; ?>><?php _e('All Categories', 'tn'); ?></option>
                <?php $categories = get_categories('type=post'); foreach ($categories as $category) { ?><option  value='<?php echo $category->term_id; ?>' <?php if ($instance['cate'] == $category->term_id) echo 'selected="selected"'; ?>><?php echo $category->cat_name; ?></option><?php } ?>
            </select>
        </p>
	    <p>
		    <label for="<?php echo $this->get_field_id( 'child_cate' ); ?>"><?php _e('Show Child Category:','tn') ?></label>
		    <input class="widefat" type="checkbox" id="<?php echo $this->get_field_id( 'child_cate' ); ?>" name="<?php echo $this->get_field_name( 'child_cate' ); ?>" value="checked" <?php if( !empty( $instance['child_cate'] ) ) echo 'checked="checked"'; ?>  />
	    </p>
	    <p>
		    <label for="<?php echo $this->get_field_id( 'num_child_cate' ); ?>"><?php _e('Number Of Child Category (Leave blank if you want show all):','tn') ?></label>
		    <input type="text" class="widefat" id="<?php echo $this->get_field_id( 'num_child_cate' ); ?>" name="<?php echo $this->get_field_name( 'num_child_cate' ); ?>" value="<?php if( !empty($instance['num_child_cate']) ) echo esc_attr($instance['num_child_cate']); ?>" />
	    </p>
        <p>
            <label for="<?php echo $this->get_field_id( 'cates' ); ?>"><?php _e('Multiple Category Filter (optional, Input category ids, Separate category ids with comma. e.g. 1,2):','tn') ?></label>
            <input type="text" class="widefat" id="<?php echo $this->get_field_id( 'cates' ); ?>" name="<?php echo $this->get_field_name( 'cates' ); ?>" value="<?php if( !empty($instance['cates']) ) echo esc_attr($instance['cates']); ?>" />
        </p>
        <p>
            <label for="<?php echo $this->get_field_id( 'tags' ); ?>"><?php _e('Tags (optional, Separate tags with comma. e.g. tag1,tag2):','tn') ?></label>
            <input type="text" class="widefat" id="<?php echo $this->get_field_id( 'tags' ); ?>" name="<?php echo $this->get_field_name( 'tags' ); ?>" value="<?php if( !empty($instance['tags']) ) echo esc_attr($instance['tags']); ?>" />
        </p>
        <p>
            <label for="<?php echo $this->get_field_id( 'posts_per_page' ); ?>"><?php _e('Limit Post Number (optional, default is 6):','tn') ?></label>
            <input type="text" class="widefat" id="<?php echo $this->get_field_id( 'posts_per_page' ); ?>" name="<?php echo $this->get_field_name( 'posts_per_page' ); ?>" value="<?php if( !empty($instance['posts_per_page']) ) echo esc_attr($instance['posts_per_page']); ?>" />
        </p>
        <p>
            <label for="<?php echo $this->get_field_id( 'sort_order' ); ?>"><strong><?php _e('Order By: ', 'tn'); ?></strong></label>
            <select class="widefat" id="<?php echo $this->get_field_id( 'sort_order' ); ?>" name="<?php echo $this->get_field_name( 'sort_order' ); ?>" >
                <option value="date_post" <?php if( !empty($instance['sort_order']) && $instance['sort_order'] == 'date' ) echo "selected=\"selected\""; else echo ""; ?>><?php _e('Latest Post', 'tn'); ?></option>
                <option value="comment_count" <?php if( !empty($instance['sort_order']) && $instance['sort_order'] == 'comment_count' ) echo "selected=\"selected\""; else echo ""; ?>><?php _e('Popular Post by Comments', 'tn'); ?></option>
                <option value="view_count" <?php if( !empty($instance['sort_order']) && $instance['sort_order'] == 'view_count' ) echo "selected=\"selected\""; else echo ""; ?>><?php _e('Popular Post by Views', 'tn'); ?></option>
                <option value="best_review" <?php if( !empty($instance['sort_order']) && $instance['sort_order'] == 'best_review' ) echo "selected=\"selected\""; else echo ""; ?>><?php _e('Best Reviews', 'tn'); ?></option>
                <option value="post_type" <?php if( !empty($instance['sort_order']) && $instance['sort_order'] == 'post_type' ) echo "selected=\"selected\""; else echo ""; ?>><?php _e('Post Type', 'tn'); ?></option>
                <option value="rand" <?php if( !empty($instance['sort_order']) && $instance['sort_order'] == 'rand' ) echo "selected=\"selected\""; else echo ""; ?>><?php _e('Random Post', 'tn'); ?></option>
                <option value="alphabetical_order_asc" <?php if( !empty($instance['sort_order']) && $instance['sort_order'] == 'alphabetical_order_asc' ) echo "selected=\"selected\""; else echo ""; ?>><?php _e('alphabetical A->Z Posts', 'tn'); ?></option>
                <option value="alphabetical_order_decs" <?php if( !empty($instance['sort_order']) && $instance['sort_order'] == 'alphabetical_order_decs' ) echo "selected=\"selected\""; else echo ""; ?>><?php _e('alphabetical Z->A Posts', 'tn'); ?></option>
            </select>
        </p>
        <p>
            <label for="<?php echo $this->get_field_id( 'pagination' ); ?>"><?php _e('Pagination:','tn') ?></label>
            <select id="<?php echo $this->get_field_id( 'pagination' ); ?>" name="<?php echo $this->get_field_name( 'pagination' ); ?>" >
                <option value="disable" <?php if( !empty($instance['pagination']) && $instance['pagination'] == 'disable' ) echo "selected=\"selected\""; else echo ""; ?>><?php _e('-Disable-','tn'); ?></option>
                <option value="next_prev" <?php if( !empty($instance['pagination']) && $instance['pagination'] == 'next_prev' ) echo "selected=\"selected\""; else echo ""; ?>><?php _e('Next_Prev Ajax','tn'); ?></option>
                <option value="loadmore" <?php if( !empty($instance['pagination']) && $instance['pagination'] == 'loadmore' ) echo "selected=\"selected\""; else echo ""; ?>><?php _e('Load More Ajax','tn'); ?></option>
            </select>
        </p>
        <p>
            <label for="<?php echo $this->get_field_id( 'col_3' ); ?>"><?php _e('3 Columns Layout:','tn') ?></label>
            <input class="widefat" type="checkbox" id="<?php echo $this->get_field_id( 'col_3' ); ?>" name="<?php echo $this->get_field_name( 'col_3' ); ?>" value="checked" <?php if( !empty( $instance['col_3'] ) ) echo 'checked="checked"'; ?>  />
        </p>
    <?php
    }
}

?>