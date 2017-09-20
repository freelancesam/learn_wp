<?php
/*
* Default Blog Template
*/
global $tn_options;
$option = array();

$options['readmore'] = (!empty($tn_options['tn_readmore']) && $tn_options['tn_readmore'] == 1) ? 'checked' : '';
$options['style'] = (!empty($tn_options['tn_blog_style'])) ? $tn_options['tn_blog_style'] : 'style1';
$options['video_thumb'] = (isset($tn_options['tn_blog_video'])) ? $tn_options['tn_blog_video'] : '';
$options['excerpt'] = (!empty($tn_options['tn_blog_excerpt'])) ? intval($tn_options['tn_blog_excerpt']) : '';
$options['readmore'] = (!empty($tn_options['tn_readmore'])) ? 'checked' : '';

get_header();
echo tn_open_body();
echo tn_open_blog($options['style']);
if (have_posts()) {
    echo tn_render_blog_layout($GLOBALS['wp_query']->posts, $options);
}
echo tn_get_navpagi();
echo tn_close_blog();
if (tn_check_blog_sidebar($options['style'])) : ?>
    <div id="sidebar" class="col-sm-4 col-xs-12" role="complementary" itemscope="itemscope" itemtype="http://schema.org/WPSideBar">
        <?php get_sidebar(); ?>
    </div><!-- #blog sidebar -->

<?php endif; ?>
</div><!--#row-->
<?php echo tn_close_body();

get_footer();
?>

