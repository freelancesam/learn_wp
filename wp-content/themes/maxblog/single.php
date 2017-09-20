<?php
// Single Layout
global $tn_options;
global $post;

$options = array();
$options['style'] = get_post_meta($post->ID, 'tn_post_layout', true);
$options['sidebar_position'] = get_post_meta($post->ID, 'tn_post_sidebar_position', true);
$options['review_position'] = get_post_meta($post->ID, 'tn_post_review_position', true);

if (empty($options['style'])) {
    $options['style'] = (!empty($tn_options['tn_default_post_style'])) ? $tn_options['tn_default_post_style'] : 'style1';
};

//set sticky post
if ( is_sticky($post->ID)) {
    $options['style'] = 'style2';
}

//review box
if (empty($options['review_position'])) {
    $options['review_position'] = (!empty($tn_options['tn_default_review_position'])) ? $tn_options['tn_default_review_position'] : 'style1';
};

//comment box
$comment_box =  get_post_meta($post->ID, 'tn_comment_disable', true);
if(empty($comment_box)) $comment_box = $tn_options['tn_post_comment_box'];

//add post view
tn_add_post_views($post->ID);

get_header();
echo tn_open_body();

if(!empty($tn_options['tn_side_dock'])){
    echo tn_side_dock($post->ID);
}

if (have_posts()) {
    while (have_posts()){
        the_post();

        echo tn_render_single_layout($post,$options);
        //like single
         echo  tn_social_like_post($post);

        //post source
        echo tn_render_single_tags_source($post->ID);

        //share on social
        if(!empty($tn_options['tn_post_share_bottom'])) : ?>
            <div class="single-social-wrap">
                <span class="single-social-title"><?php _e('share on:','tn'); ?></span>
                <?php echo tn_share_to_social($post) ?>
            </div>
        <?php endif;

        //next prev post pagination
        if(!empty($tn_options['tn_post_paginav'])){
            echo tn_get_singlepagi();
        }

        //author box
        if(!empty($tn_options['tn_post_author_box'])) : ?>
            <div class="single-author-wrap">
            <?php echo tn_author_box($post->post_author); ?>
            </div><!--#single author wrap-->
        <?php endif;

        //related box
        if(!empty($tn_options['tn_post_related_box'])){
            echo tn_single_related($post);
        }

        //render comment box
        if ((comments_open() || '0' != get_comments_number()) && (empty($comment_box) || $comment_box != 'hide')) {
            comments_template();
        };

        //share on social aside
        if(!empty($tn_options['tn_post_share_aside'])) : ?>
            <div class="single-aside-social-wrap">
                <span class="single-social-title"><?php _e('share on:','tn'); ?></span>
                <?php echo tn_share_to_social_aside($post) ?>
            </div>
        <?php endif;

    }
}
echo '</article><!--#end article-->';

if (tn_check_single_sidebar($options['sidebar_position'])) : ?>
    <div id="sidebar" class="col-sm-4 col-xs-12" role="complementary" itemscope="itemscope" itemtype="http://schema.org/WPSideBar">
        <?php get_sidebar(); ?>
    </div><!-- #blog sidebar -->
<?php endif; ?>

</div><!-- #row -->
<?php echo tn_close_body();
get_footer();
?>