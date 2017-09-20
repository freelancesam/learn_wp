<?php
//get themes options
global $tn_options;

$tn_logo = (isset($tn_options['tn_logo'])) ? $tn_options['tn_logo'] : array() ;
$tn_menu_top = (isset($tn_options['tn_menu_top'])) ? $tn_options['tn_menu_top'] : 1;
$tn_google_ads = (isset($tn_options['tn_google_ads'])) ? $tn_options['tn_google_ads'] : '';
$tn_custom_ads_url = (!empty($tn_options['tn_custom_ads_url'])) ? $tn_options['tn_custom_ads_url'] : '#';
$tn_custom_ads_img = (isset($tn_options['tn_custom_ads_img'])) ? $tn_options['tn_custom_ads_img'] : array();
$tn_social = (isset($tn_options['tn_social'])) ? $tn_options['tn_social'] : 1;
$tn_ticker = (isset($tn_options['tn_ticker'])) ? $tn_options['tn_ticker'] : 0;
$tn_header_style = (!empty($tn_options['tn_header_style'])) ? $tn_options['tn_header_style'] : 'left';
$data_social = tn_web_social();

if ($tn_header_style == 'centered') : ?>
<header class="header-center clearfix">
	<?php else : ?>
	<header class="clearfix">
		<?php endif; ?>
		<?php if ($tn_menu_top == 1) : ?>
			<div class="tn-navbar clearfix">
				<div class="tn-container">
					<?php if (has_nav_menu('menu_top')) {
						wp_nav_menu(array(
							'theme_location' => 'menu_top',
							'container_id' => false,
							'container_class' => 'menu-nav-top',
							'menu_class' => 'menu-top',
							'link_before' => '',
							'link_after' => '',
							'items_wrap' => '<ul id="%1$s" class="%2$s">%3$s</ul>',
							'depth' => 1
						));
					}
					if ($tn_social == 1) : ?>
						<div class="menu-top-right">
							<?php echo tn_social_icon($data_social); ?>
						</div>
					<?php endif; ?>
				</div><!-- #tn-container -->
			</div><!-- #tn-nav bar -->
		<?php endif; ?>
		<div class="tn-container clearfix">

			<div id="main-header">
				<div class="site-logo" role="banner" itemscope="itemscope" itemtype="http://schema.org/Organization">
					<?php if (!empty($tn_logo['url'])) : ?>
						<a itemprop="url" href="<?php echo esc_url(home_url()); ?>"><img src="<?php echo esc_url($tn_logo['url']); ?>" alt="<?php bloginfo('name'); ?>"></a>
                        <meta itemprop="name" content="<?php bloginfo('name')?>">
					<?php else : ?>
						<div class="logo-content-wrap">
							<div class="logo-no-image">
								<h1 class="title-logo"><a itemprop="url" href="<?php echo esc_url(home_url()); ?>"><?php bloginfo('name'); ?></a></h1>
								<?php if (get_bloginfo('description')): ?>
									<h3 class="title-tagline"><?php bloginfo('description') ?></h3>
                            </div><!--#no image-->
								<?php endif; ?>
                            <meta itemprop="name" content="<?php bloginfo('name')?>">
						</div><!-- #logo content wrapper -->
					<?php endif; ?>
				</div><!-- #logo -->

				<?php if (empty($tn_custom_ads_img['url'])) : ?>
					<?php if (!empty($tn_google_ads)) : ?>
						<div class="header-ads-wrap">
							<?php echo do_shortcode($tn_google_ads) ; ?>
						</div><!-- #google ads -->
					<?php endif; ?>
				<?php else : ?>
				<?php if(!empty($tn_custom_ads_url)) : ?>
					<div class="header-ads-wrap">
						<a href="<?php echo esc_url($tn_custom_ads_url); ?>"><img alt="" src="<?php echo esc_url($tn_custom_ads_img['url']); ?>"></a>
					</div><!-- #custom ads -->
				<?php else : ?>
					<div class="header-ads-wrapper">
						<img src="<?php echo esc_url($tn_custom_ads_img['url']); ?>" alt="">
					</div><!-- #custom ads -->
				<?php endif; ?>
				<?php endif; ?><!--#header ads -->
			</div><!-- #main header -->

        </div><!--#tn container -->

        <nav id="main-nav" role="navigation" itemscope="itemscope" itemtype="http://schema.org/SiteNavigationElement" class="clearfix">
            <div id="tn-main-nav-wrap" class="main-nav-wrap">
                <div class="tn-container">
                    <div class="main-nav-inner">

                        <div class="mobile-menu-nav">
                            <a href="#" id="mobile-button-nav-open"  class="mobile-nav-button"><i class="menu-button fa fa-th-list"></i></a>
                        </div><!-- #mobile nav wrap -->

                        <?php
                        if (has_nav_menu('menu_main')) {
                            wp_nav_menu(array(
                                'theme_location' => 'menu_main',
                                'container_id' => 'menu-main',
                                'walker' => new TN_Walker,
                                'depth' => '3',
                            ));
                        }
                        ?>
                        <?php echo tn_ajax_form_search(); ?><!--#ajax search -->

                    </div><!--#main nav inner -->
                </div><!--#tn container -->
            </div><!--#main nav wrap-->
        </nav><!--# main nav -->

        <?php if ($tn_ticker == 1 && is_page_template('homepage.php')) echo tn_moduleTicker() ?>
        <?php if (!is_front_page() && !is_page_template('homepage.php')) echo tn_dimox_breadcrumbs(); ?>

	</header><!-- header -->


