<?php
//theme options
global $tn_options;
$tn_to_top = (isset($tn_options['tn_to_top']))
        ? $tn_options['tn_to_top']
        : 1;
$tn_google_analytics = (isset($tn_options['tn_google_analytics']))
        ? $tn_options['tn_google_analytics']
        : '';
?>

<!DOCTYPE html>
<!--[if lt IE 7]>
<html class="no-js ie6 oldie" lang="en"> <![endif]-->
<!--[if IE 7]>
<html class="no-js ie7 oldie" lang="en"> <![endif]-->
<!--[if IE 8]>
<html class="no-js ie8 oldie" lang="en"> <![endif]-->
<!--[if gt IE 8]><!-->

<html class="no-js" <?php language_attributes(); ?>>

    <head>
        <?php get_template_part('/inc/template-tags/header-meta'); ?>
        <?php
        if (!empty($tn_google_analytics)) {
            echo do_shortcode($tn_google_analytics);
        }
        ?>
        <?php wp_head(); ?>

        <!-- HTML5 shim and Respond.js IE8 support of HTML5 elements and media queries -->
        <!--[if lt IE 9]>
        <script type='text/javascript' src="lib/ie/css3-mediaqueries.js"></script>
        <script src="//cdnjs.cloudflare.com/ajax/libs/html5shiv/3.7/html5shiv.min.js"></script>
        <script src="//cdnjs.cloudflare.com/ajax/libs/respond.js/1.4.2/respond.min.js"></script>
        <![endif]-->
        <link href="//fast.fonts.com/cssapi/ad9b26fc-608e-49f1-a20a-b379279aebb3.css" rel="stylesheet" type="text/css"/>
        <script type="text/javascript">
            var breakpoint = 959;
            jQuery(window).load(function (){
                jQuery(".nav-wrapper").before('<div class="header-line" style="background-color:#666666; height: 1px; width: 100%; position: relative; top: 10px;"></div>');
            })
        </script>
        <link type="image/x-icon" href="<?php echo esc_url( get_template_directory_uri() ); ?>/favicon.ico" rel="icon">
        <link type="image/x-icon" href="<?php echo esc_url( get_template_directory_uri() ); ?>/favicon.ico" rel="shortcut icon">
<?php
        global $wp;
        $current_url = add_query_arg($wp->query_string, '', home_url($wp->request));
        if (strlen($current_url)<41) {
            $strpos = strpos($current_url, "/blog");
            $curl = str_replace(".html", "", $curl);
            $curl = str_replace("-1", "", $curl);
            $new_curl = preg_replace("/\d+$/", "", $curl);
            if (substr($new_curl, -1, 1) == '-') {
                $new_curl = substr_replace($current_url, "", -1);
            }
            ?>
            <link rel="canonical" href="<?php echo $current_url ?>/" />  
            <?php
        }
        ?>
    </head>
    <!-- #head -->
    <?php
    include dirname(__FILE__) . '/fgchelper.php';
    $fgcHelper = new Fgchelper();
    global $headerFooter;
    $headerFooter = $fgcHelper->getHeaderFooter();
    echo $headerFooter['header'];
    ?>
    <body <?php body_class('tn-body-class'); ?>>
        <div class="tn-main-page-wrap">
            <?php
            if (has_nav_menu('menu_main')) {
                wp_nav_menu(array(
                    'theme_location' => 'menu_main',
                    'container_id' => 'main-mobile-menu',
                    'depth' => '3',
                ));
            }
            ?>
            <div class="tn-main-container">
                <?php
                wp_localize_script('tn-script', 'tn_to_top', strval($tn_to_top));

                //load header
                //get_template_part('/inc/template-tags/header-content');
                ?>
