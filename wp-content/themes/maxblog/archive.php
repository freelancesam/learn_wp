<?php
global $tn_options;
$options = array();

$options['readmore'] = ((!empty($tn_options['tn_readmore'])) && $tn_options['tn_readmore'] == 1) ? 'checked' : '';
$options['style'] = (!empty($tn_options['tn_page_archive_style'])) ? $tn_options['tn_page_archive_style'] : 'style1';
$options['video_thumb'] = ((!empty($cate_options['tn_blog_video'])) && ($cate_options['tn_blog_video'] == 'checked')) ? $cate_options['tn_blog_video'] : '';
$options['excerpt'] = (!empty($tn_options['tn_page_archive_excerpt'])) ? intval($tn_options['tn_page_archive_excerpt']) : '';

get_header();
echo tn_open_body();

echo tn_open_blog($options['style']);

     if (have_posts()) : ?>
         <div class="archive-page-title-wrap">
             <h1 class="archive-page-title">
                 <?php
                 if ( is_category() ) :
                     single_cat_title();

                 elseif ( is_tag() ) : ?>
                     <span class="archive-tag-title"><?php _e('Tag:','tn'); ?></span>
                     <?php single_tag_title();

                 elseif ( is_author() ) :
                     printf( esc_attr__( 'Author: %s', 'tn' ), '<span class="vcard">' . get_the_author() . '</span>' );

                 elseif ( is_day() ) :
                     printf( esc_attr__( 'Day: %s', 'tn' ), '<span>' . get_the_date() . '</span>' );

                 elseif ( is_month() ) :
                     printf( esc_attr__( 'Month: %s', 'tn' ), '<span>' . get_the_date( _x( 'F Y', 'monthly archives date format', 'tn' ) ) . '</span>' );

                 elseif ( is_year() ) :
                     printf( esc_attr__( 'Year: %s', 'tn' ), '<span>' . get_the_date( _x( 'Y', 'yearly archives date format', 'tn' ) ) . '</span>' );

                 elseif ( is_tax( 'post_format', 'post-format-aside' ) ) :
                     _e( 'Asides', 'tn' );

                 elseif ( is_tax( 'post_format', 'post-format-gallery' ) ) :
                     _e( 'Galleries', 'tn' );

                 elseif ( is_tax( 'post_format', 'post-format-image' ) ) :
                     _e( 'Images', 'tn' );

                 elseif ( is_tax( 'post_format', 'post-format-video' ) ) :
                     _e( 'Videos', 'tn' );

                 elseif ( is_tax( 'post_format', 'post-format-quote' ) ) :
                     _e( 'Quotes', 'tn' );

                 elseif ( is_tax( 'post_format', 'post-format-link' ) ) :
                     _e( 'Links', 'tn' );

                 elseif ( is_tax( 'post_format', 'post-format-status' ) ) :
                     _e( 'Statuses', 'tn' );

                 elseif ( is_tax( 'post_format', 'post-format-audio' ) ) :
                     _e( 'Audios', 'tn' );

                 elseif ( is_tax( 'post_format', 'post-format-chat' ) ) :
                     _e( 'Chats', 'tn' );

                 else :
                     _e( 'Archives', 'tn' );
                 endif;  ?>
             </h1>
             <?php
             $term_description = term_description();
             if ( ! empty( $term_description ) ) {
                 echo '<div class="archive-page-description">'.esc_attr($term_description).'</div>';
             } ?>
         </div><!--#archive header -->

 <?php  echo tn_render_blog_layout($GLOBALS['wp_query']->posts, $options);
        echo tn_get_navpagi();
        endif;
        echo tn_close_blog();

if (tn_check_blog_sidebar($options['style'])) : ?>
    <div id="sidebar" class="col-sm-4 col-xs-12" role="complementary" itemscope="itemscope" itemtype="http://schema.org/WPSideBar">
        <?php get_sidebar(); ?>
    </div><!-- #blog sidebar -->
<?php endif; ?>
</div><!-- #row -->

<?php echo tn_close_body();

get_footer(); ?>