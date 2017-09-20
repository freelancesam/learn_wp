<?php
//search layout

global $tn_options;
$options = array();

$options['readmore'] = ((!empty($tn_options['tn_readmore'])) && $tn_options['tn_readmore'] == 1) ? 'checked' : '';
$options['style'] = (!empty($tn_options['tn_page_search_style'])) ? $tn_options['tn_page_search_style'] : 'style1';
$options['video_thumb'] = ((!empty($cate_options['tn_blog_video'])) && ($cate_options['tn_blog_video'] == 'checked')) ? $cate_options['tn_blog_video'] : '';
$options['excerpt'] = (!empty($tn_options['tn_page_search_excerpt'])) ? intval($tn_options['tn_page_search_excerpt']) : '';

get_header();
echo tn_open_body();

echo tn_open_blog($options['style']);
?>
<div class="search-page-title-wrap">
<h1 class="search-page-title"><?php _e('Search','tn') ?></h1>
<h3 class="search-page-result">
    <?php if ( $wp_query->found_posts != 1 ) {
        printf( __( '<span>%s</span> search results found for &ldquo;%s&rdquo;.', 'tn' ), $wp_query->found_posts, get_search_query() );
    } else {
        printf( __( '<span>%s</span> search result found for &ldquo;%s&rdquo;.', 'tn' ), $wp_query->found_posts, get_search_query() );
    } ?>
</h3>
</div><!--#search title wrap-->

<?php if (have_posts()) {
    echo tn_render_blog_layout($GLOBALS['wp_query']->posts, $options);


echo tn_get_navpagi();
}
echo tn_close_blog();
if (tn_check_blog_sidebar($options['style'])) : ?>
    <div id="sidebar" class="col-sm-4 col-xs-12">
        <?php get_sidebar(); ?>
    </div><!-- #blog sidebar -->
<?php endif; ?>
</div><!-- #row -->
<?php
echo tn_close_body();

get_footer();
?>