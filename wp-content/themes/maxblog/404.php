<?php
//404 page
get_header();
//open body class
echo tn_open_body(); ?>

<div class="row clearfix">
    <div id="main-content" class="col-xs-12" role="main" itemscope="itemscope" itemprop="mainContentOfPage" itemtype="http://schema.org/CreativeWork">
        <div class="error-404-content-wrap">
            <div class="logo-404"><h1><?php _e('404', 'tn'); ?></h1></div>
            <h3 class="title-404"><?php _e('Oops! It looks like nothing was found at this location. Maybe try another link or a search?', 'tn'); ?></h3>
            <?php get_search_form(); ?>
            <!-- .page-header -->
        </div>
        <!--404 wrap -->
    </div>
    <!--#main content -->
    <!-- # home sidebar -->
</div><!--#row -->

<?php
//close body class
echo tn_close_body();

//get footer
get_footer();
?>
