<div class="row footer-sidebar-wrap clearfix">
    <?php if (is_active_sidebar('footer_sidebar_1')) : ?>
        <div class="footer-sidebar widget-area col-sm-4 col-xs-12" role="complementary">
            <?php dynamic_sidebar('footer_sidebar_1'); ?>
        </div>
    <?php endif; ?>

    <?php if (is_active_sidebar('footer_sidebar_2')) : ?>
        <div class="footer-sidebar widget-area  col-sm-4 col-xs-12" role="complementary">
            <?php dynamic_sidebar('footer_sidebar_2'); ?>
        </div>
    <?php endif; ?>

    <?php if (is_active_sidebar('footer_sidebar_3')) : ?>
        <div class="footer-sidebar widget-area col-sm-4 col-xs-12" role="complementary">
            <?php dynamic_sidebar('footer_sidebar_3'); ?>
        </div>
    <?php endif; ?>
</div><!--#footer sidebar -->