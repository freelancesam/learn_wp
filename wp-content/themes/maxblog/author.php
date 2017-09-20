<?php
/* Author Page */
global $tn_options;
$options = array();

if (is_single())
    $author_id = $post->post_author;
else
    $author_id = get_query_var('author');

$options['style'] = (!empty($tn_options['tn_page_author_style'])) ? $tn_options['tn_page_author_style'] : 'style1';


 get_header();
    echo tn_open_body();

  echo tn_open_blog($options['style']); ?>
<div class="author-info-wrap clearfix">
    <?php echo tn_author_box($author_id); ?>
</div>
<?php if (have_posts()) {
    echo tn_render_blog_layout($GLOBALS['wp_query']->posts, $options);
}
echo  tn_get_navpagi();
echo tn_close_blog();
if (tn_check_blog_sidebar($options['style'])) : ?>
    <div id="sidebar" class="col-sm-4 col-xs-12" role="complementary" itemscope="itemscope" itemtype="http://schema.org/WPSideBar">
        <?php get_sidebar(); ?>
    </div><!-- #blog sidebar -->
<?php endif; ?>
</div><!--#row -->
<?php
echo tn_close_body();

get_footer();
?>



