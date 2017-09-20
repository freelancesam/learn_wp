<?php
//cate layout
global $tn_options;
$cate_options = array();
$options = array();
$cate_id = $wp_query->get_queried_object_id();

$meta = get_option('tn_cate_option') ? get_option('tn_cate_option') : array();
if (array_key_exists($cate_id, $meta)) $cate_options = $meta[$cate_id];
$options['readmore'] = ((!empty($tn_options['tn_readmore'])) && $tn_options['tn_readmore'] == 1) ? 'checked' : '';
$options['style'] = (!empty($cate_options['tn_cate_style'])) ? $cate_options['tn_cate_style'] : 'style1';
$options['video_thumb'] = (!empty($cate_options['tn_cate_video'])) ? $cate_options['tn_cate_video'] : '';
$options['excerpt'] = (!empty($cate_options['tn_cate_excerpt'])) ? intval($cate_options['tn_cate_excerpt']) : '';
if ((!empty($cate_options['tn_cate_readmore'])) && ($cate_options['tn_cate_readmore'] == 'checked')) $options['readmore'] = 'checked';

$feature_options['cate_id'] = $cate_id;
$feature_options['style'] = (!empty($cate_options['tn_cate_feature'])) ? $cate_options['tn_cate_feature'] : '';
$feature_options['from'] = (!empty($cate_options['tn_cate_feature_from'])) ? $cate_options['tn_cate_feature_from'] : 'feature';
$feature_options['sort_order'] = (!empty($cate_options['tn_cate_feature_sort'])) ? $cate_options['tn_cate_feature_sort'] : 'rand';

 get_header();
    echo tn_open_body();

        //get feature post
        echo tn_cateFeature($feature_options);

        echo tn_open_blog($options['style']); ?>
        <div class="cate-page-title-wrap tn-category-<?php echo esc_attr($cate_id); ?>">
            <h1 class="cate-title"><?php echo single_cat_title() ?></h1>
            <?php if (category_description()) : // Show an optional category description ?>
                <div class="cate-description"><?php echo category_description(); ?></div>
            <?php endif; ?>
        </div><!-- #cate title -->

        <?php if (have_posts()) {
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
<?php
echo tn_close_body();

get_footer();
?>