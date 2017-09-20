<?php
add_action('widgets_init', 'tn_register_module5_widget');

function tn_register_module5_widget()
{
    register_widget('tn_module5_widget');
}

class tn_module5_widget extends WP_Widget
{

    function tn_module5_widget()
    {
        $widget_ops = array(
            'classname' => 'module5-widget',
            'description' => __('[Primary Content Widget] This widget can place in PRIMARY CONTENT. This widget have 3 section, you can set title, order sort, style each section.','tn'));
        $this->WP_Widget('module5-widget', __('TN . POSTS BOX 5', 'tn'), $widget_ops);
    }

    function widget($args, $instance)
    {
        extract($args);
        $array_query1 =$array_query2 = $array_query3 = array();

        $options = array();

        $title = ($instance['title']) ? esc_attr($instance['title']) : '';


        $options['title1'] = ($instance['title1']) ? esc_attr($instance['title1']) : '';
        $options['title2'] = ($instance['title2']) ? esc_attr($instance['title2']) : '';
        $options['title3'] = ($instance['title3']) ? esc_attr($instance['title3']) : '';

        $array_query1['category_id'] = $array_query2['category_id'] = $array_query3['category_id'] = ($instance['cate']) ? $instance['cate'] : '';
        $array_query1['category_ids'] = $array_query2['category_ids'] = $array_query3['category_ids'] = ($instance['cates']) ? $instance['cates'] : '';
        $array_query1['tag_plus'] = $array_query2['tag_plus'] = $array_query3['tag_plus'] = ($instance['tags']) ? $instance['tags'] : '';

        $array_query1['posts_per_page'] = ($instance['posts_per_page1']) ? $instance['posts_per_page1'] : 4;
        $array_query2['posts_per_page'] = ($instance['posts_per_page2']) ? $instance['posts_per_page2'] : 1;
        $array_query3['posts_per_page'] = ($instance['posts_per_page3']) ? $instance['posts_per_page3'] : 4;

        $array_query1['sort_order'] = ($instance['sort_order1']) ? $instance['sort_order1'] : 'date_post';
        $array_query2['sort_order'] = ($instance['sort_order2']) ? $instance['sort_order2'] : 'date_post';
        $array_query3['sort_order'] = ($instance['sort_order3']) ? $instance['sort_order3'] : 'date_post';



        $options['cate_id'] = ($instance['cate']) ? $instance['cate'] : '';
        $options['child_cate'] = ($instance['child_cate']) ? $instance['child_cate'] : '';
        $options['num_child_cate'] = ($instance['num_child_cate']) ? $instance['num_child_cate'] : '';
        $options['style1'] = ($instance['style1']) ? $instance['style1'] : '';
        $options['style2'] = ($instance['style2']) ? $instance['style2'] : '';
        $options['style3'] = ($instance['style3']) ? $instance['style3'] : '';


        $query_data1 = tn_custom_query($array_query1);
        $query_data2 = tn_custom_query($array_query2);
        $query_data3 = tn_custom_query($array_query3);

        if ($title == 'default') {
            if ($options['cate_id'] !='all') {
                $title = '<a href="' . get_category_link($options['cate_id']) . '" title="' . strip_tags(get_cat_name($options['cate_id'])) . '">' . get_cat_name($options['cate_id']) . '</a>';
            } else $title = esc_attr__('POSTS BOX 5','tn');
        }

        $id = uniqid('module5_');

        //Show widget
        echo $before_widget;
        if(!empty($options['cate_id']) && $options['cate_id'] != 'all'){
            echo '<div class="tn-category-'. $options['cate_id'].'">';
            if(!empty($title)) echo $before_title . $title . $after_title;
            echo '</div><!--#color title-->';
        } else {
            if(!empty($title)) echo $before_title . $title . $after_title;
        }
        ?>

        <div class="right-widget-title-wrap">
            <?php  if(!empty($options['child_cate'])){
                echo  tn_sub_cate($options);
            };
            if (!empty($pagination)) echo tn_ajax_pagination($id, $pagination); ?>
        </div><!-- #right widget title -->

        <?php
        if (!empty($query_data1) && !empty($query_data2) && !empty($query_data3)) :?>
            <div id="<?php echo $id; ?>" class="row">
                <?php echo tn_module5($query_data1->posts, $query_data2->posts,$query_data3->posts, $options); ?>
            </div><!-- #row -->

        <?php endif;

        echo $after_widget;
    }
    //update
    function update($new_instance, $old_instance)
    {
        $instance = $old_instance;
        $instance['title'] = strip_tags($new_instance['title']);
        $instance['title1'] = strip_tags($new_instance['title1']);
        $instance['title2'] = strip_tags($new_instance['title2']);
        $instance['title3'] = strip_tags($new_instance['title3']);
        $instance['cate'] = strip_tags($new_instance['cate']);
        $instance['child_cate'] = strip_tags($new_instance['child_cate']);
        $instance['num_child_cate'] = strip_tags($new_instance['num_child_cate']);
        $instance['cates'] = strip_tags($new_instance['cates']);
        $instance['tags'] = strip_tags($new_instance['tags']);
        $instance['style1'] = strip_tags($new_instance['style1']);
        $instance['style2'] = strip_tags($new_instance['style2']);
        $instance['style3'] = strip_tags($new_instance['style3']);
        $instance['sort_order1'] = strip_tags($new_instance['sort_order1']);
        $instance['sort_order2'] = strip_tags($new_instance['sort_order2']);
        $instance['sort_order3'] = strip_tags($new_instance['sort_order3']);

        $instance['posts_per_page1'] = absint(strip_tags($new_instance['posts_per_page1']));
        $instance['posts_per_page2'] = absint(strip_tags($new_instance['posts_per_page2']));
        $instance['posts_per_page3'] = absint(strip_tags($new_instance['posts_per_page3']));
        $instance['num_child_cate'] = absint(strip_tags($new_instance['num_child_cate']));

        return $instance;
    }

    function form($instance)
    {
        $defaults = array(
            'title' => 'default',
            'title1' => '',
            'title2' => '',
            'title3' => '',
            'style1' =>1,
            'style2' =>5,
            'style3' =>2,
            'posts_per_page1' => '5',
            'posts_per_page2' => '1',
            'posts_per_page3' => '4',
            'sort_order' => 'date_post',
            'cate' => '',
            'child_cate' => '',
            'num_child_cate' => '',
            'cates' => '',
            'tags' => '',
        );
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
        <p><strong><?php _e('Section 1 Options:','tn'); ?></strong></p>
        <p>
            <label for="<?php echo $this->get_field_id( 'title1' ); ?>"><?php _e('Section 1 Title:','tn') ?></label>
            <input type="text" class="widefat" id="<?php echo $this->get_field_id( 'title1' ); ?>" name="<?php echo $this->get_field_name( 'title1' ); ?>" value="<?php if( !empty($instance['title1']) ) echo esc_attr( $instance['title1']); ?>" />
        </p>
        <p>
            <label for="<?php echo $this->get_field_id( 'style1' ); ?>"><?php _e('Section 1 Style:', 'tn'); ?></label>
            <select class="widefat" id="<?php echo $this->get_field_id( 'style1' ); ?>" name="<?php echo $this->get_field_name( 'style1' ); ?>" >
                <option value="1" <?php if( !empty($instance['style1']) && $instance['style1'] == 1 ) echo "selected=\"selected\""; else echo ""; ?>><?php _e('style 1', 'tn'); ?></option>
                <option value="2" <?php if( !empty($instance['style1']) && $instance['style1'] == 2 ) echo "selected=\"selected\""; else echo ""; ?>><?php _e('style 2', 'tn'); ?></option>
                <option value="3" <?php if( !empty($instance['style1']) && $instance['style1'] == 3 ) echo "selected=\"selected\""; else echo ""; ?>><?php _e('style 3', 'tn'); ?></option>
                <option value="4" <?php if( !empty($instance['style1']) && $instance['style1'] == 4 ) echo "selected=\"selected\""; else echo ""; ?>><?php _e('style 4', 'tn'); ?></option>
                <option value="5" <?php if( !empty($instance['style1']) && $instance['style1'] == 5 ) echo "selected=\"selected\""; else echo ""; ?>><?php _e('style 5', 'tn'); ?></option>
            </select>
        </p>
        <p>
            <label for="<?php echo $this->get_field_id( 'posts_per_page1' ); ?>"><?php _e('Section 1 Limit Post Number:','tn') ?></label>
            <input type="text" class="widefat" id="<?php echo $this->get_field_id( 'posts_per_page1' ); ?>" name="<?php echo $this->get_field_name( 'posts_per_page1' ); ?>" value="<?php if( !empty($instance['posts_per_page1']) ) echo esc_attr($instance['posts_per_page1']); ?>" />
        </p>
        <p>
            <label for="<?php echo $this->get_field_id( 'sort_order1' ); ?>"><?php _e('Section 1 Order By: ', 'tn'); ?></label>
            <select class="widefat" id="<?php echo $this->get_field_id( 'sort_order1' ); ?>" name="<?php echo $this->get_field_name( 'sort_order1' ); ?>" >
                <option value="date_post" <?php if( !empty($instance['sort_order1']) && $instance['sort_order1'] == 'date' ) echo "selected=\"selected\""; else echo ""; ?>><?php _e('Latest Post', 'tn'); ?></option>
                <option value="comment_count" <?php if( !empty($instance['sort_order1']) && $instance['sort_order1'] == 'comment_count' ) echo "selected=\"selected\""; else echo ""; ?>><?php _e('Popular Post by Comments', 'tn'); ?></option>
                <option value="view_count" <?php if( !empty($instance['sort_order1']) && $instance['sort_order1'] == 'view_count' ) echo "selected=\"selected\""; else echo ""; ?>><?php _e('Popular Post by Views', 'tn'); ?></option>
                <option value="best_review" <?php if( !empty($instance['sort_order1']) && $instance['sort_order1'] == 'best_review' ) echo "selected=\"selected\""; else echo ""; ?>><?php _e('Best Reviews', 'tn'); ?></option>
                <option value="post_type" <?php if( !empty($instance['sort_order1']) && $instance['sort_order1'] == 'post_type' ) echo "selected=\"selected\""; else echo ""; ?>><?php _e('Post Type', 'tn'); ?></option>
                <option value="rand" <?php if( !empty($instance['sort_order1']) && $instance['sort_order1'] == 'rand' ) echo "selected=\"selected\""; else echo ""; ?>><?php _e('Random Post', 'tn'); ?></option>
                <option value="alphabetical_order_asc" <?php if( !empty($instance['sort_order1']) && $instance['sort_order1'] == 'alphabetical_order_asc' ) echo "selected=\"selected\""; else echo ""; ?>><?php _e('alphabetical A->Z Posts', 'tn'); ?></option>
                <option value="alphabetical_order_decs" <?php if( !empty($instance['sort_order1']) && $instance['sort_order1'] == 'alphabetical_order_decs' ) echo "selected=\"selected\""; else echo ""; ?>><?php _e('alphabetical Z->A Posts', 'tn'); ?></option>
            </select>
        </p>

        <p><strong><?php _e('Section 2 Options:','tn'); ?></strong></p>
        <p>
            <label for="<?php echo $this->get_field_id( 'title2' ); ?>"><?php _e('Section 2 Title:','tn') ?></label>
            <input type="text" class="widefat" id="<?php echo $this->get_field_id( 'title2' ); ?>" name="<?php echo $this->get_field_name( 'title2' ); ?>" value="<?php if( !empty($instance['title2']) ) echo esc_attr($instance['title2']); ?>" />
        </p>
        <p>
            <label for="<?php echo $this->get_field_id( 'style2' ); ?>"><?php _e('Section 2 Style: ', 'tn'); ?></label>
            <select class="widefat" id="<?php echo $this->get_field_id( 'style2' ); ?>" name="<?php echo $this->get_field_name( 'style2' ); ?>" >
                <option value="1" <?php if( !empty($instance['style2']) && $instance['style2'] == 1 ) echo "selected=\"selected\""; else echo ""; ?>><?php _e('style 1', 'tn'); ?></option>
                <option value="2" <?php if( !empty($instance['style2']) && $instance['style2'] == 2 ) echo "selected=\"selected\""; else echo ""; ?>><?php _e('style 2', 'tn'); ?></option>
                <option value="3" <?php if( !empty($instance['style2']) && $instance['style2'] == 3 ) echo "selected=\"selected\""; else echo ""; ?>><?php _e('style 3', 'tn'); ?></option>
                <option value="4" <?php if( !empty($instance['style2']) && $instance['style2'] == 4 ) echo "selected=\"selected\""; else echo ""; ?>><?php _e('style 4', 'tn'); ?></option>
                <option value="5" <?php if( !empty($instance['style2']) && $instance['style2'] == 5 ) echo "selected=\"selected\""; else echo ""; ?>><?php _e('style 5', 'tn'); ?></option>
            </select>
        </p>
        <p>
            <label for="<?php echo $this->get_field_id( 'posts_per_page2' ); ?>"><?php _e('Section 2 Limit Post Number:','tn') ?></label>
            <input type="text" class="widefat" id="<?php echo $this->get_field_id( 'posts_per_page2' ); ?>" name="<?php echo $this->get_field_name( 'posts_per_page2' ); ?>" value="<?php if( !empty($instance['posts_per_page2']) ) echo esc_attr($instance['posts_per_page2']); ?>" />
        </p>
        <p>
            <label for="<?php echo $this->get_field_id( 'sort_order2' ); ?>"><?php _e('Section 2 Order By: ', 'tn'); ?></label>
            <select class="widefat" id="<?php echo $this->get_field_id( 'sort_order2' ); ?>" name="<?php echo $this->get_field_name( 'sort_order2' ); ?>" >
                <option value="date_post" <?php if( !empty($instance['sort_order2']) && $instance['sort_order2'] == 'date' ) echo "selected=\"selected\""; else echo ""; ?>><?php _e('Latest Post', 'tn'); ?></option>
                <option value="comment_count" <?php if( !empty($instance['sort_order2']) && $instance['sort_order2'] == 'comment_count' ) echo "selected=\"selected\""; else echo ""; ?>><?php _e('Popular Post by Comments', 'tn'); ?></option>
                <option value="view_count" <?php if( !empty($instance['sort_order2']) && $instance['sort_order2'] == 'view_count' ) echo "selected=\"selected\""; else echo ""; ?>><?php _e('Popular Post by Views', 'tn'); ?></option>
                <option value="best_review" <?php if( !empty($instance['sort_order2']) && $instance['sort_order2'] == 'best_review' ) echo "selected=\"selected\""; else echo ""; ?>><?php _e('Best Reviews', 'tn'); ?></option>
                <option value="post_type" <?php if( !empty($instance['sort_order2']) && $instance['sort_order2'] == 'post_type' ) echo "selected=\"selected\""; else echo ""; ?>><?php _e('Post Type', 'tn'); ?></option>
                <option value="rand" <?php if( !empty($instance['sort_order2']) && $instance['sort_order2'] == 'rand' ) echo "selected=\"selected\""; else echo ""; ?>><?php _e('Random Post', 'tn'); ?></option>
                <option value="alphabetical_order_asc" <?php if( !empty($instance['sort_order2']) && $instance['sort_order2'] == 'alphabetical_order_asc' ) echo "selected=\"selected\""; else echo ""; ?>><?php _e('alphabetical A->Z Posts', 'tn'); ?></option>
                <option value="alphabetical_order_decs" <?php if( !empty($instance['sort_order2']) && $instance['sort_order2'] == 'alphabetical_order_decs' ) echo "selected=\"selected\""; else echo ""; ?>><?php _e('alphabetical Z->A Posts', 'tn'); ?></option>
            </select>
        </p>
        
        <p><strong><?php _e('Section 3 Options:','tn'); ?></strong></p>
        <p>
            <label for="<?php echo $this->get_field_id( 'title3' ); ?>"><?php _e('Section 3 Title:','tn') ?></label>
            <input type="text" class="widefat" id="<?php echo $this->get_field_id( 'title3' ); ?>" name="<?php echo $this->get_field_name( 'title3' ); ?>" value="<?php if( !empty($instance['title3']) ) echo esc_attr($instance['title3']); ?>" />
        </p>
        <p>
            <label for="<?php echo $this->get_field_id( 'style3' ); ?>"><?php _e('Section 3 style:', 'tn'); ?></label>
            <select class="widefat" id="<?php echo $this->get_field_id( 'style3' ); ?>" name="<?php echo $this->get_field_name( 'style3' ); ?>" >
                <option value="1" <?php if( !empty($instance['style3']) && $instance['style3'] == 1 ) echo "selected=\"selected\""; else echo ""; ?>><?php _e('style 1', 'tn'); ?></option>
                <option value="2" <?php if( !empty($instance['style3']) && $instance['style3'] == 2 ) echo "selected=\"selected\""; else echo ""; ?>><?php _e('style 2', 'tn'); ?></option>
                <option value="3" <?php if( !empty($instance['style3']) && $instance['style3'] == 3 ) echo "selected=\"selected\""; else echo ""; ?>><?php _e('style 3', 'tn'); ?></option>
                <option value="4" <?php if( !empty($instance['style3']) && $instance['style3'] == 4 ) echo "selected=\"selected\""; else echo ""; ?>><?php _e('style 4', 'tn'); ?></option>
                <option value="5" <?php if( !empty($instance['style3']) && $instance['style3'] == 5 ) echo "selected=\"selected\""; else echo ""; ?>><?php _e('style 5', 'tn'); ?></option>
            </select>
        </p>
        <p>
            <label for="<?php echo $this->get_field_id( 'posts_per_page3' ); ?>"><?php _e('Section 3 Limit Post Number:','tn') ?></label>
            <input type="text" class="widefat" id="<?php echo $this->get_field_id( 'posts_per_page3' ); ?>" name="<?php echo $this->get_field_name( 'posts_per_page3' ); ?>" value="<?php if( !empty($instance['posts_per_page3']) ) echo esc_attr($instance['posts_per_page3']); ?>" />
        </p>
        <p>
            <label for="<?php echo $this->get_field_id( 'sort_order3' ); ?>"><?php _e('Section 3 Order By:', 'tn'); ?></label>
            <select class="widefat" id="<?php echo $this->get_field_id( 'sort_order3' ); ?>" name="<?php echo $this->get_field_name( 'sort_order3' ); ?>" >
                <option value="date_post" <?php if( !empty($instance['sort_order3']) && $instance['sort_order3'] == 'date' ) echo "selected=\"selected\""; else echo ""; ?>><?php _e('Latest Post', 'tn'); ?></option>
                <option value="comment_count" <?php if( !empty($instance['sort_order3']) && $instance['sort_order3'] == 'comment_count' ) echo "selected=\"selected\""; else echo ""; ?>><?php _e('Popular Post by Comments', 'tn'); ?></option>
                <option value="view_count" <?php if( !empty($instance['sort_order3']) && $instance['sort_order3'] == 'view_count' ) echo "selected=\"selected\""; else echo ""; ?>><?php _e('Popular Post by Views', 'tn'); ?></option>
                <option value="best_review" <?php if( !empty($instance['sort_order3']) && $instance['sort_order3'] == 'best_review' ) echo "selected=\"selected\""; else echo ""; ?>><?php _e('Best Reviews', 'tn'); ?></option>
                <option value="post_type" <?php if( !empty($instance['sort_order3']) && $instance['sort_order3'] == 'post_type' ) echo "selected=\"selected\""; else echo ""; ?>><?php _e('Post Type', 'tn'); ?></option>
                <option value="rand" <?php if( !empty($instance['sort_order3']) && $instance['sort_order3'] == 'rand' ) echo "selected=\"selected\""; else echo ""; ?>><?php _e('Random Post', 'tn'); ?></option>
                <option value="alphabetical_order_asc" <?php if( !empty($instance['sort_order3']) && $instance['sort_order3'] == 'alphabetical_order_asc' ) echo "selected=\"selected\""; else echo ""; ?>><?php _e('alphabetical A->Z Posts', 'tn'); ?></option>
                <option value="alphabetical_order_decs" <?php if( !empty($instance['sort_order3']) && $instance['sort_order3'] == 'alphabetical_order_decs' ) echo "selected=\"selected\""; else echo ""; ?>><?php _e('alphabetical Z->A Posts', 'tn'); ?></option>
            </select>
        </p>
    <?php
    }
}

?>