<div class="widget-area">
    <?php if (is_page_template('homepage.php') || is_page_template('page.php')) {
        if (is_active_sidebar('sidebar_home')) dynamic_sidebar('sidebar_home');
    } else {
        if (is_active_sidebar('sidebar_blog')) dynamic_sidebar('sidebar_blog');
    }
    ?>
</div><!--# widget area-->