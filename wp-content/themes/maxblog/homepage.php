<?php
/*
Template Name: Home Template
*/

get_header();

echo tn_open_body() ?>

<?php if (is_active_sidebar('full_top')): ?>
    <div id="full-top" class="row clearfix">
        <div class="col-xs-12">
        <?php dynamic_sidebar('full_top'); ?>
    </div></div><!--#top -->
<?php endif; ?>

<?php if (is_active_sidebar('content')): ?>
    <div class="row main-content-wrap clearfix">
        <div id="main-content" class="col-sm-8 col-xs-12" role="main">
            <?php dynamic_sidebar('content'); ?>
        </div>
        <!--#main content -->
        <div id="sidebar" class="col-sm-4 col-xs-12" role="complementary" itemscope="itemscope" itemtype="http://schema.org/WPSideBar">
            <?php get_sidebar(); ?>
        </div><!-- # home sidebar -->
    </div><!--#mid -->
<?php endif; ?>

<?php if (is_active_sidebar('full_bottom')): ?>
    <div id="full-bottom" class="row clearfix">
    <div class="col-xs-12">
        <?php dynamic_sidebar('full_bottom'); ?>
    </div></div><!--#bottom-->
<?php endif;
 echo tn_close_body();
 get_footer(); ?>