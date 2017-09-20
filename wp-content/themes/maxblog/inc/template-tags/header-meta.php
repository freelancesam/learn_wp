<?php
//get theme options
global $tn_options;
$tn_favicon = (isset($tn_options['tn_favicon'])) ? $tn_options['tn_favicon'] : array();
?>
<meta charset="<?php bloginfo('charset'); ?>">
<meta name="viewport" content="width=device-width, initial-scale=1">
<!--[if IE]><meta http-equiv='X-UA-Compatible' content='IE=edge,chrome=1'><![endif]-->

<?php
if (!empty($tn_options['tn_site_meta'])) :
    if (is_front_page()) {
        ?>
        <meta name="description" content="If you're looking for ways to look better and feel better, without breaking the bank, you'll love the Pharmacy 4 Less Health Blog. Find out more now.">
        <?php
    } else {
        ?>
        <meta name="description" content="<?php bloginfo('description'); ?>">
        <?php
    }
endif;
?>

<?php if (!empty($tn_favicon['url'])) : ?>
    <link href="<?php echo esc_url($tn_favicon['url']); ?>" rel="shortcut icon"/>
<?php endif; ?><!-- #load favicon -->

<title><?php wp_title('|', true, 'right'); ?></title><!--#title -->

<!-- mobile Specific meta -->
<link rel="profile" href="http://gmpg.org/xfn/11">
<link rel="alternate" type="text/xml" title="RSS .92" href="<?php bloginfo('rss_url'); ?>" />
<link rel="alternate" type="application/rss+xml" title="RSS 2.0" href="<?php bloginfo('rss2_url'); ?>" />
<link rel="pingback" href="<?php bloginfo('pingback_url'); ?>">
<link rel='stylesheet' href='<?php bloginfo("stylesheet_url"); ?>' type='text/css' media='screen'/>
