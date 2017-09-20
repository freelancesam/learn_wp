<?php
/*
* Default Page Template
*/
global $post;
global $tn_options;

$options['title'] = get_post_meta($post->ID, 'tn_page_title', true);
$options['sidebar_position'] = get_post_meta($post->ID, 'page_sidebar_position', true);
if( empty($options['sidebar_position'])) $options['sidebar_position'] = $tn_options['tn_sidebar_position'];

get_header();

echo tn_open_body();
?>

<div class="row clearfix">
    <?php if(!empty($options['sidebar_position'])) :
        if($options['sidebar_position'] == 'left') : ?>
            <div id="main-content" class="col-sm-8 col-xs-12" style="float:right" role="main" itemscope="itemscope" itemprop="mainContentOfPage" itemtype="http://schema.org/CreativeWork">
        <?php else : ?>
            <div id="main-content" class="col-sm-8 col-xs-12" role="main" itemscope="itemscope" itemprop="mainContentOfPage" itemtype="http://schema.org/CreativeWork">
        <?php endif; ?>
    <?php else : ?>
    <div id="main-content" class="col-xs-12" role="main" itemscope="itemscope" itemprop="mainContentOfPage" itemtype="http://schema.org/CreativeWork">
    <?php endif; ?>
    <?php if (have_posts()) : ?>
    <?php  while ( have_posts() ) : the_post(); ?>
    <div class="page-wrap clearfix">
    <article id="post-<?php the_ID(); ?>" <?php post_class(); ?>>
    <?php if(empty($options['title']) || $options['title'] != 'hide'){
        echo '<h1 itemprop="name" class="page-title-wrap"><span>' . get_the_title() . '</span></h1><!--#page title-->';
    }
    ?>
    <?php if ( function_exists("has_post_thumbnail") && has_post_thumbnail()) : ?>
        <div class="thumb-wrapper">
            <?php the_post_thumbnail('blog_classic_thumb'); ?>
        </div><!-- #page thumbnail -->
    <?php endif; ?>

    <div class="page-content-wrap post-content-wrap">
        <?php the_content(); ?>
        <?php
        wp_link_pages( array(
            'before' => '<div class="page-links">' . __( 'Pages:', 'tn' ),
            'after'  => '</div>',
        ) );
        ?>
    </div><!--#page-content -->

    <?php comments_template('', true); ?>

    </article><!--$#post-->
    </div><!-- #page wrap -->
    <?php endwhile; endif; ?>
    </div><!-- #main content -->
<?php if (tn_check_single_sidebar($options['sidebar_position'])) : ?>
    <div id="sidebar" class="col-sm-4 col-xs-12" role="complementary" itemscope="itemscope" itemtype="http://schema.org/WPSideBar">
        <?php get_sidebar(); ?>
    </div><!-- #blog sidebar -->
    <?php endif; ?>
 </div><!--#row -->

<?php echo tn_close_body();

get_footer();
?>
