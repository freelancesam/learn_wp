<?php
//get theme options

$tn_copyright = tn_get_theme_option('tn_copyright');
$tn_smooth_display = tn_get_theme_option('tn_smooth_display', 1);
$tn_sticky_menu = tn_get_theme_option('tn_sticky_menu', 1);
$tn_sticky_sidebar = tn_get_theme_option('tn_sticky_sidebar', 0);
$tn_ticker_title = tn_get_theme_option('tn_ticker_title', 'BREAKING NEWS');
$tn_rtl = tn_get_theme_option('tn_rtl', 0);

wp_localize_script('tn-script', 'tn_smooth_display', strval($tn_smooth_display));
wp_localize_script('tn-script', 'tn_sticky_menu', strval($tn_sticky_menu));
wp_localize_script('tn-script', 'tn_sticky_sidebar', strval($tn_sticky_sidebar));
wp_localize_script('tn-script', 'tn_ticker_title', strip_tags($tn_ticker_title));
wp_localize_script('tn-script', 'tn_rtl', strval($tn_rtl));
?>
<footer style="display: none;" id="footer" class="clearfix">
    <div class="tn-container">
        <?php get_template_part('/inc/template-tags/sidebar-footer'); ?>
    </div><!-- #tn container -->
    <div class="copyright-wrap">
        <div class="tn-container">
            <div class="copyright-inner  clearfix">
                <div class="copyright">
                    <?php if (!empty($tn_copyright)) echo (wp_kses($tn_copyright, array('a' => array('href' => array(), 'title' => array())))); ?>
                </div><!--copy right -->
                <?php
                if (has_nav_menu('menu_footer')) {
                    wp_nav_menu(array(
                        'theme_location' => 'menu_footer',
                        'container_id' => false,
                        'container_class' => 'menu-footer-wrap',
                        'menu_class' => 'menu-footer',
                        'link_before' => '',
                        'link_after' => '',
                        'items_wrap' => '<ul id="%1$s" class="%2$s">%3$s</ul>',
                        'depth' => 1
                    ));
                }
                ?>
            </div><!--#copyright inner -->
        </div><!--#tn-container -->
    </div><!--#copy right wrap -->
</footer><!--#footer -->
</div> <!-- #tn main container -->
</div><!--#main page wrap-->
<?php wp_footer(); ?>
<?php
global $headerFooter;
echo $headerFooter['footer'];
?>
</body><!--#body -->
</html><!--#html -->

