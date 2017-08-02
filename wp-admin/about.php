<?php
/**
 * About This Version administration panel.
 *
 * @package WordPress
 * @subpackage Administration
 */

/** WordPress Administration Bootstrap */
require_once( dirname( __FILE__ ) . '/admin.php' );

if ( ! wp_is_mobile() ) {
	wp_enqueue_style( 'wp-mediaelement' );
	wp_enqueue_script( 'wp-mediaelement' );
	wp_localize_script( 'mediaelement', '_wpmejsSettings', array(
		'pluginPath'        => includes_url( 'js/mediaelement/', 'relative' ),
		'pauseOtherPlayers' => '',
	) );
}

$video_url = 'https://videopress.com/embed/AHz0Ca46?hd=true';
$lang_code = str_replace( '_', '-', get_user_locale() );
list( $lang_code ) = explode( '-', $lang_code );
if ( 'en' !== $lang_code ) {
	$video_url = add_query_arg( 'defaultLangCode', $lang_code, $video_url );
}

$title = __( 'About' );

list( $display_version ) = explode( '-', get_bloginfo( 'version' ) );

include( ABSPATH . 'wp-admin/admin-header.php' );
?>
	<div class="wrap about-wrap">
		<h1><?php printf( __( 'Welcome to WordPress&nbsp;%s' ), $display_version ); ?></h1>

<<<<<<< HEAD
		<p class="about-text"><?php printf( __( 'Thank you for updating to the latest version! WordPress %s adds more ways for you to express yourself and represent your brand.' ), $display_version ); ?></p>
=======
		<p class="about-text"><?php printf( __( 'Thank you for updating to the latest version! WordPress %s helps you get your site set up the way you want it.' ), $display_version ); ?></p>
>>>>>>> bbfbbb9c81f9c36cbaa8e67ea4b62e0932d77aed
		<div class="wp-badge"><?php printf( __( 'Version %s' ), $display_version ); ?></div>

		<h2 class="nav-tab-wrapper wp-clearfix">
			<a href="about.php" class="nav-tab nav-tab-active"><?php _e( 'What&#8217;s New' ); ?></a>
			<a href="credits.php" class="nav-tab"><?php _e( 'Credits' ); ?></a>
			<a href="freedoms.php" class="nav-tab"><?php _e( 'Freedoms' ); ?></a>
		</h2>

<<<<<<< HEAD
		<div class="feature-section one-col">
			<div class="col">
				<h2><?php _e( 'An Update with You in Mind' ); ?></h2>
				<p class="lead-description"><?php _e( 'WordPress 4.8 adds some great new features. Gear up for a more intuitive WordPress!' ); ?></p>
				<p><?php _e( 'Though some updates seem minor, they&#8217;ve been built by hundreds of contributors with <em>you</em> in mind. Get ready for new features you&#8217;ll welcome like an old friend: link improvements, <em>three</em> new media widgets covering images, audio, and video, an updated text widget that supports visual editing, and an upgraded news section in your dashboard which brings in nearby and upcoming WordPress events.' ); ?></p>
			</div>
=======
		<div class="changelog point-releases">
			<h3><?php _e( 'Maintenance and Security Releases' ); ?></h3>
			<p><?php printf( __( '<strong>Version %s</strong> addressed some security issues.' ), '4.7.2' ); ?>
				<?php printf( __( 'For more information, see <a href="%s">the release notes</a>.' ), 'https://codex.wordpress.org/Version_4.7.2' ); ?>
			</p>
			<p><?php printf( _n( '<strong>Version %1$s</strong> addressed some security issues and fixed %2$s bug.',
					'<strong>Version %1$s</strong> addressed some security issues and fixed %2$s bugs.', 62 ), '4.7.1', number_format_i18n( 62 ) ); ?>
				<?php printf( __( 'For more information, see <a href="%s">the release notes</a>.' ), 'https://codex.wordpress.org/Version_4.7.1' ); ?>
			</p>
		</div>

		<div class="headline-feature feature-video">
			<iframe width="1050" height="591" src="<?php echo esc_url( $video_url ); ?>" frameborder="0" allowfullscreen></iframe>
			<script src="https://videopress.com/videopress-iframe.js"></script>
>>>>>>> bbfbbb9c81f9c36cbaa8e67ea4b62e0932d77aed
		</div>

		<hr />

<<<<<<< HEAD
		<h2><?php _e( 'Exciting Widget Updates' ); ?></h2>

		<div class="headline-feature one-col">
			<div class="col">
				<picture>
					<!-- Large image -->
					<source media="( min-width: 1050px )"
						srcset="
							https://s.w.org/images/core/4.8/widgets-with-all-four-widescreen_w_810.png 810w,
							https://s.w.org/images/core/4.8/widgets-with-all-four-widescreen_w_1054.png 1054w,
							https://s.w.org/images/core/4.8/widgets-with-all-four-widescreen_w_1266.png 1266w,
							https://s.w.org/images/core/4.8/widgets-with-all-four-widescreen_w_1458.png 1458w,
							https://s.w.org/images/core/4.8/widgets-with-all-four-widescreen_w_1633.png 1633w,
							https://s.w.org/images/core/4.8/widgets-with-all-four-widescreen_w_1797.png 1797w,
							https://s.w.org/images/core/4.8/widgets-with-all-four-widescreen_w_1955.png 1955w,
							https://s.w.org/images/core/4.8/widgets-with-all-four-widescreen_w_2100.png 2100w"
						sizes="( max-width: 1290px ) calc( 100vw - 240px ), 1050px" />
					<!-- Medium image -->
					<source media="( min-width: 601px )"
						srcset="
							https://s.w.org/images/core/4.8/widgets-with-all-four_w_531.png 531w,
							https://s.w.org/images/core/4.8/widgets-with-all-four_w_745.png 745w,
							https://s.w.org/images/core/4.8/widgets-with-all-four_w_927.png 927w,
							https://s.w.org/images/core/4.8/widgets-with-all-four_w_1089.png 1089w,
							https://s.w.org/images/core/4.8/widgets-with-all-four_w_1236.png 1236w,
							https://s.w.org/images/core/4.8/widgets-with-all-four_w_1370.png 1370w,
							https://s.w.org/images/core/4.8/widgets-with-all-four_w_1498.png 1498w,
							https://s.w.org/images/core/4.8/widgets-with-all-four_w_1620.png 1620w"
						sizes="( max-width: 782px ) calc( 100vw - 70px ), ( max-width: 960px ) calc( 100vw - 116px ), calc( 100vw - 240px )" />
					<!-- Small image -->
					<img src="https://s.w.org/images/core/4.8/widgets-with-all-four-mobile_w_685.png"
						srcset="
							https://s.w.org/images/core/4.8/widgets-with-all-four-mobile_w_300.png 300w,
							https://s.w.org/images/core/4.8/widgets-with-all-four-mobile_w_451.png 451w,
							https://s.w.org/images/core/4.8/widgets-with-all-four-mobile_w_575.png 575w,
							https://s.w.org/images/core/4.8/widgets-with-all-four-mobile_w_685.png 685w,
							https://s.w.org/images/core/4.8/widgets-with-all-four-mobile_w_784.png 784w,
							https://s.w.org/images/core/4.8/widgets-with-all-four-mobile_w_873.png 873w,
							https://s.w.org/images/core/4.8/widgets-with-all-four-mobile_w_959.png 959w,
							https://s.w.org/images/core/4.8/widgets-with-all-four-mobile_w_1040.png 1040w"
						sizes="( max-width: 500px ) calc( 100vw - 40px ), calc( 100vw - 70px )"
						alt="" />
				</picture>
			</div>
=======
		<div class="feature-section one-col">
			<h2><?php _e( 'Presenting Twenty Seventeen' ); ?></h2>
			<p class="lead-description"><?php _e( 'A brand new default theme brings your site to life with immersive featured images and video headers.' ); ?></p>
			<picture>
				<source media="(min-width: 450px)" srcset="https://s.w.org/images/core/4.7/twenty-seventeen-1600.jpg?v2 1600w, https://s.w.org/images/core/4.7/twenty-seventeen-493.jpg?v2 493w, https://s.w.org/images/core/4.7/twenty-seventeen-663.jpg?v2 663w, https://s.w.org/images/core/4.7/twenty-seventeen-804.jpg?v2 804w, https://s.w.org/images/core/4.7/twenty-seventeen-928.jpg?v2 928w, https://s.w.org/images/core/4.7/twenty-seventeen-1058.jpg?v2 1058w, https://s.w.org/images/core/4.7/twenty-seventeen-1173.jpg?v2 1173w, https://s.w.org/images/core/4.7/twenty-seventeen-1299.jpg?v2 1299w, https://s.w.org/images/core/4.7/twenty-seventeen-1410.jpg?v2 1410w, https://s.w.org/images/core/4.7/twenty-seventeen-1513.jpg?v2 1513w, https://s.w.org/images/core/4.7/twenty-seventeen-1595.jpg?v2 1595w" sizes="(max-width: 500px) calc(100vw - 40px), (max-width: 782px) calc(100vw - 70px), (max-width: 959px) calc(100vw - 116px), (max-width: 1290px) calc(100vw - 240px), 1050px">
				<source srcset="https://s.w.org/images/core/4.7/twenty-seventeen-mobile-564.jpg?v2 564w, https://s.w.org/images/core/4.7/twenty-seventeen-mobile-280.jpg?v2 280w, https://s.w.org/images/core/4.7/twenty-seventeen-mobile-372.jpg?v2 372w, https://s.w.org/images/core/4.7/twenty-seventeen-mobile-454.jpg?v2 454w, https://s.w.org/images/core/4.7/twenty-seventeen-mobile-541.jpg?v2 541w" sizes="calc(100vw - 40px)">
				<img src="https://s.w.org/images/core/4.7/twenty-seventeen-1600.jpg?v2" srcset="https://s.w.org/images/core/4.7/twenty-seventeen-1600.jpg?v2 1600w, https://s.w.org/images/core/4.7/twenty-seventeen-280.jpg?v2 280w, https://s.w.org/images/core/4.7/twenty-seventeen-493.jpg?v2 493w, https://s.w.org/images/core/4.7/twenty-seventeen-663.jpg?v2 663w, https://s.w.org/images/core/4.7/twenty-seventeen-804.jpg?v2 804w, https://s.w.org/images/core/4.7/twenty-seventeen-928.jpg?v2 928w, https://s.w.org/images/core/4.7/twenty-seventeen-1058.jpg?v2 1058w, https://s.w.org/images/core/4.7/twenty-seventeen-1173.jpg?v2 1173w, https://s.w.org/images/core/4.7/twenty-seventeen-1299.jpg?v2 1299w, https://s.w.org/images/core/4.7/twenty-seventeen-1410.jpg?v2 1410w, https://s.w.org/images/core/4.7/twenty-seventeen-1513.jpg?v2 1513w, https://s.w.org/images/core/4.7/twenty-seventeen-1595.jpg?v2 1595w" sizes="(max-width: 500px) calc(100vw - 40px), (max-width: 782px) calc(100vw - 70px), (max-width: 959px) calc(100vw - 116px), (max-width: 1290px) calc(100vw - 240px), 1050px" alt="" />
			</picture>
			<p><?php _e( 'Twenty Seventeen focuses on business sites and features a customizable front page with multiple sections. Personalize it with widgets, navigation, social menus, a logo, custom colors, and more. Our default theme for 2017 works great in many languages, on any device, and for a wide range of users.' ); ?></p>
		</div>

		<hr />

		<div class="feature-section one-col">
			<h2><?php _e( 'Your Site, Your Way' ); ?></h2>
			<p class="lead-description"><?php _e( 'WordPress 4.7 adds new features to the customizer to help take you through the initial setup of a theme, with non-destructive live previews of all your changes in one uninterrupted workflow.' ); ?></p>
>>>>>>> bbfbbb9c81f9c36cbaa8e67ea4b62e0932d77aed
		</div>

		<div class="feature-section two-col">
			<div class="col">
<<<<<<< HEAD
				<h3><?php _e( 'Image Widget' ); ?></h3>
				<p><?php _e( 'Adding an image to a widget is now a simple task that is achievable for any WordPress user without needing to know code. Simply insert your image right within the widget settings. Try adding something like a headshot or a photo of your latest weekend adventure &mdash; and see it appear automatically.' ); ?></p>
			</div>
			<div class="col">
				<h3><?php _e( 'Video Widget' ); ?></h3>
				<p><?php _e( 'A welcome video is a great way to humanize the branding of your website. You can now add any video from the Media Library to a sidebar on your site with the new Video widget. Use this to showcase a welcome video to introduce visitors to your site or promote your latest and greatest content.' ); ?></p>
			</div>
			<div class="col">
				<h3><?php _e( 'Audio Widget' ); ?></h3>
				<p><?php _e( 'Are you a podcaster, musician, or avid blogger? Adding a widget with your audio file has never been easier. Upload your audio file to the Media Library, go to the widget settings, select your file, and you&#8217;re ready for listeners. This would be a easy way to add a more personal welcome message, too!' );?></p>
			</div>
			<div class="col">
				<h3><?php _e( 'Rich Text Widget' ); ?></h3>
				<p><?php _e( 'This feature deserves a parade down the center of town! Rich-text editing capabilities are now native for Text widgets. Add a widget anywhere and format away. Create lists, add emphasis, and quickly and easily insert links. Have fun with your newfound formatting powers, and watch what you can accomplish in a short amount of time.' ); ?></p>
			</div>
		</div>

		<hr />

		<div class="feature-section two-col">
			<div class="col">
				<h3><?php _e( 'Link Boundaries' ); ?></h3>
				<p><?php _e( 'Have you ever tried updating a link, or the text around a link, and found you can&#8217;t seem to edit it correctly? When you edit the text after the link, your new text also ends up linked. Or you edit the text in the link, but your text ends up outside of it. This can be frustrating! With link boundaries, a great new feature, the process is streamlined and your links will work well. You’ll be happier. We promise.' ); ?></p>
			</div>
			<div class="col">
				<?php
				echo wp_video_shortcode( array(
					'mp4'      => 'https://s.w.org/images/core/4.8/link-boundaries.mp4',
					'poster'   => 'https://s.w.org/images/core/4.8/link-boundaries.png',
					'width'    => 1140,
					'height'   => 624,
					// 'class'    => 'wp-video-shortcode feature-video',
				) );
				?>
			</div>
		</div>

		<hr />

		<div class="feature-section two-col">
			<div class="col">
				<h3><?php _e( 'Nearby WordPress Events' ); ?></h3>

				<p><?php _e( 'Did you know that WordPress has a thriving offline community with groups meeting regularly in more than 400 cities around the world? WordPress now draws your attention to the events that help you continue improving your WordPress skills, meet friends, and, of course, publish!' ); ?></p>

				<p><?php _e( 'This is quickly becoming one of our favorite features. While you are in the dashboard (because you&#8217;re running updates and writing posts, right?) all upcoming WordCamps and WordPress Meetups &mdash; local to you &mdash; will be displayed.' ); ?>

				<p><?php _e( 'Being part of the community can help you improve your WordPress skills and network with people you wouldn&#8217;t otherwise meet. Now you can easily find your local events just by logging in to your dashboard and looking at the new Events and News dashboard widget.' ); ?>
			</div>
			<div class="col">
				<img
					src="https://s.w.org/images/core/4.8/events-widget_w_732.png"
					srcset="
						https://s.w.org/images/core/4.8/events-widget_w_280.png 280w,
						https://s.w.org/images/core/4.8/events-widget_w_420.png 420w,
						https://s.w.org/images/core/4.8/events-widget_w_529.png 529w,
						https://s.w.org/images/core/4.8/events-widget_w_638.png 638w,
						https://s.w.org/images/core/4.8/events-widget_w_732.png 732w,
						https://s.w.org/images/core/4.8/events-widget_w_827.png 827w,
						https://s.w.org/images/core/4.8/events-widget_w_992.png 992w"
					sizes="
						( max-width: 500px ) calc( 100vw - 40px ),
						( max-width: 782px ) calc( 48vw - 33px ),
						( max-width: 960px ) calc( 47vw - 54px ),
						( max-width: 1290px ) calc( 47vw - 112px ),
						496px"
					alt="" />
=======
				<h3><?php _e( 'Theme Starter Content' ); ?></h3>
				<p><?php _e( 'To help give you a solid base to build from, individual themes can provide starter content that appears when you go to customize your brand new site. This can range from placing a business information widget in the best location to providing a sample menu with social icon links to a static front page complete with beautiful images. Don&#8217;t worry - nothing new will appear on the live site until you&#8217;re ready to save and publish your initial theme setup.' ); ?></p>
			</div>
			<div class="col">
				<?php
				echo wp_video_shortcode( array(
					'mp4'      => 'https://s.w.org/images/core/4.7/starter-content-v1.mp4',
					'poster'   => 'https://s.w.org/images/core/4.7/starter-content.jpg?v2',
					'loop'     => true,
					'autoplay' => true,
					'width'    => 1140,
					'height'   => 624,
					// 'class'    => 'wp-video-shortcode feature-video',
				) );
				?>
			</div>
		</div>

		<div class="feature-section two-col">
			<div class="col">
				<h3><?php _e( 'Edit Shortcuts' ); ?></h3>
				<?php
				echo wp_video_shortcode( array(
					'mp4'      => 'https://s.w.org/images/core/4.7/edit-shortcuts-v1.mp4',
					'poster'   => 'https://s.w.org/images/core/4.7/edit-shortcuts.jpg?v2',
					'loop'     => true,
					'autoplay' => true,
					'width'    => 2520,
					'height'   => 1454,
					// 'class'    => 'wp-video-shortcode feature-video',
				) );
				?>
				<p><?php _e( 'Visible icons appear to show you which parts of your site can be customized while live previewing. Click on a shortcut and get straight to editing. Paired with starter content, getting started with customizing your site is faster than ever.' );?></p>
			</div>
			<div class="col">
				<h3><?php _e( 'Video Headers' ); ?></h3>
				<?php
				echo wp_video_shortcode( array(
					'mp4'      => 'https://s.w.org/images/core/4.7/header-video-v1.mp4',
					'poster'   => 'https://s.w.org/images/core/4.7/header-video.jpg?v2',
					'loop'     => true,
					'autoplay' => true,
					'width'    => 2520,
					'height'   => 1454,
					// 'class'    => 'wp-video-shortcode feature-video',
				) );
				?>
				<p><?php _e( 'Sometimes a big atmospheric video as a moving header image is just what you need to showcase your wares; go ahead and try it out with Twenty Seventeen. Need some video inspiration? Try searching for sites with video headers available for download and use.' ); ?></p>
			</div>
		</div>

		<div class="feature-section two-col">
			<div class="col">
				<h3><?php _e( 'Smoother Menu Building' ); ?></h3>
				<img src="https://s.w.org/images/core/4.7/nav-menus-760.jpg?v2" srcset="https://s.w.org/images/core/4.7/nav-menus-760.jpg?v2 760w, https://s.w.org/images/core/4.7/nav-menus-280.jpg?v2 280w, https://s.w.org/images/core/4.7/nav-menus-536.jpg?v2 536w, https://s.w.org/images/core/4.7/nav-menus-745.jpg?v2 745w" sizes="(max-width: 500px) calc(100vw - 40px), (max-width: 781px) calc((100vw - 70px) * .466), (max-width: 959px) calc((100vw - 116px) * .469), (max-width: 1290px) calc((100vw - 240px) * .472), 496px" alt="" />
				<p><?php _e( 'Many menus for sites contain links to the pages of your site, but what happens when you don&#8217;t have any pages yet? Now you can add new pages while building menus instead of leaving the customizer and abandoning your changes. Once you&#8217;ve published your customizations, you&#8217;ll have new pages ready for you to fill with content.' );?></p>
			</div>
			<div class="col">
				<h3><?php _e( 'Custom CSS' ); ?></h3>
				<img src="https://s.w.org/images/core/4.7/css-760.jpg?v2" srcset="https://s.w.org/images/core/4.7/css-760.jpg?v2 760w, https://s.w.org/images/core/4.7/css-280.jpg?v2 280w, https://s.w.org/images/core/4.7/css-547.jpg?v2 547w" sizes="(max-width: 500px) calc(100vw - 40px), (max-width: 781px) calc((100vw - 70px) * .466), (max-width: 959px) calc((100vw - 116px) * .469), (max-width: 1290px) calc((100vw - 240px) * .472), 496px" alt="" />
				<p><?php _e( 'Sometimes you just need a few visual tweaks to make your site perfect. WordPress 4.7 allows you to add custom CSS and instantly see how your changes affect your site. The live preview allows you to work quickly without page refreshes slowing you down.' ); ?></p>
			</div>
		</div>

		<hr />

		<div class="feature-section no-heading two-col">
			<div class="col">
				<h3><?php _e( 'PDF Thumbnail Previews' ); ?></h3>
				<img src="https://s.w.org/images/core/4.7/pdf-760.jpg?v2" srcset="https://s.w.org/images/core/4.7/pdf-760.jpg?v2 760w, https://s.w.org/images/core/4.7/pdf-280.jpg?v2 280w, https://s.w.org/images/core/4.7/pdf-412.jpg?v2 412w, https://s.w.org/images/core/4.7/pdf-516.jpg?v2 516w, https://s.w.org/images/core/4.7/pdf-615.jpg?v2 615w, https://s.w.org/images/core/4.7/pdf-716.jpg?v2 716w" sizes="(max-width: 500px) calc(100vw - 40px), (max-width: 781px) calc((100vw - 70px) * .466), (max-width: 959px) calc((100vw - 116px) * .469), (max-width: 1290px) calc((100vw - 240px) * .472), 496px" alt="" />
				<p><?php _e( 'Managing your document collection is easier with WordPress 4.7. Uploading PDFs will generate thumbnail images so you can more easily distinguish between all your documents.' ); ?></p>
			</div>
			<div class="col">
				<h3><?php _e( 'Dashboard in your language' ); ?></h3>
				<img src="https://s.w.org/images/core/4.7/language-760.jpg?v2" srcset="https://s.w.org/images/core/4.7/language-760.jpg?v2 760w, https://s.w.org/images/core/4.7/language-280.jpg?v2 280w, https://s.w.org/images/core/4.7/language-568.jpg?v2 568w" sizes="(max-width: 500px) calc(100vw - 40px), (max-width: 781px) calc((100vw - 70px) * .466), (max-width: 959px) calc((100vw - 116px) * .469), (max-width: 1290px) calc((100vw - 240px) * .472), 496px" alt="" />
				<p><?php _e( 'Just because your site is in one language doesn&#8217;t mean that everybody helping manage it prefers that language for their admin. Add more languages to your site and a user language option will show up in your user&#8217;s profiles.' );?></p>
>>>>>>> bbfbbb9c81f9c36cbaa8e67ea4b62e0932d77aed
			</div>
		</div>

		<hr />

<<<<<<< HEAD
=======
		<div class="rest-api feature-section one-col">
			<h2><?php _e( 'Introducing REST API Content Endpoints' ); ?></h2>
			<p class="lead-description"><?php _e( 'WordPress 4.7 comes with REST API endpoints for posts, comments, terms, users, meta, and settings.' ); ?></p>
			<img src="https://s.w.org/images/core/4.7/api-1559.jpg?v2" srcset="https://s.w.org/images/core/4.7/api-2100.jpg?v2 2100w, https://s.w.org/images/core/4.7/api-280.jpg?v2 280w, https://s.w.org/images/core/4.7/api-1034.jpg?v2 1034w, https://s.w.org/images/core/4.7/api-1559.jpg?v2 1559w, https://s.w.org/images/core/4.7/api-2032.jpg?v2 2032w" sizes="(max-width: 500px) calc(100vw - 40px), (max-width: 782px) calc(100vw - 70px), (max-width: 959px) calc(100vw - 116px), (max-width: 1290px) calc(100vw - 240px), 1050px" alt="" />
			<p><?php
				printf(
					/* translators: %s: https://developer.wordpress.org/rest-api/  */
					__( 'Content endpoints provide machine-readable external access to your WordPress site with a clear, standards-driven interface, paving the way for new and innovative methods of interacting with sites through plugins, themes, apps, and beyond. Ready to get started with development? <a href="%s">Check out the REST API reference.</a>' ),
					'https://developer.wordpress.org/rest-api/reference/'
				);
			?></p>
		</div>

		<hr />

>>>>>>> bbfbbb9c81f9c36cbaa8e67ea4b62e0932d77aed
		<div class="changelog">
			<h2><?php
				printf(
					/* translators: %s: smiling face with smiling eyes emoji */
					__( 'Even More Developer Happiness %s' ),
					'&#x1F60A'
				);
			?></h2>

			<div class="under-the-hood three-col">
				<div class="col">
<<<<<<< HEAD
					<h3><a href="https://make.wordpress.org/core/2017/05/17/cleaner-headings-in-the-admin-screens/"><?php _e( 'More Accessible Admin Panel Headings' ); ?></a></h3>
					<p><?php _e( 'New CSS rules mean extraneous content (like &ldquo;Add New&rdquo; links) no longer need to be included in admin-area headings. These panel headings improve the experience for people using assistive technologies.' ); ?></p>
				</div>
				<div class="col">
					<h3><a href="https://make.wordpress.org/core/2017/05/22/removal-of-core-embedding-support-for-wmv-and-wma-file-formats/"><?php _e( 'Removal of Core Support for WMV and WMA Files' ); ?></a></h3>
					<p><?php _e( 'As fewer and fewer browsers support Silverlight, file formats which require the presence of the Silverlight plugin are being removed from core support. Files will still display as a download link, but will no longer be embedded automatically.' ); ?></p>
				</div>
				<div class="col">
					<h3><a href="https://make.wordpress.org/core/2017/05/22/multisite-focused-changes-in-4-8/"><?php _e( 'Multisite Updates' ); ?></a></h3>
					<p><?php _e( 'New capabilities have been introduced to 4.8 with an eye towards removing calls to <code>is_super_admin()</code>. Additionally, new hooks and tweaks to more granularly control site and user counts per network have been added.' ); ?></p>
				</div>
				<div class="col">
					<h3><a href="https://make.wordpress.org/core/2017/05/23/addition-of-tinymce-to-the-text-widget/"><?php _e( 'Text-Editor JavaScript API' ); ?></a></h3>
					<p><?php _e( 'With the addition of TinyMCE to the text widget in 4.8 comes a new JavaScript API for instantiating the editor after page load. This can be used to add an editor instance to any text area, and customize it with buttons and functions. Great for plugin authors!' ); ?></p>
				</div>
				<div class="col">
					<h3><a href="https://make.wordpress.org/core/2017/05/26/media-widgets-for-images-video-and-audio/"><?php _e( 'Media Widgets API' ); ?></a></h3>
					<p><?php _e( 'The introduction of a new base media widget REST API schema to 4.8 opens up possibilities for even more media widgets (like galleries or playlists) in the future. The three new media widgets are powered by a shared base class that covers most of the interactions with the media modal. That class also makes it easier to create new media widgets and paves the way for more to come.' ); ?></p>
				</div>
				<div class="col">
					<h3><a href="https://make.wordpress.org/core/2017/05/16/customizer-sidebar-width-is-now-variable/"><?php _e( 'Customizer Width Variable' ); ?></a></h3>
					<p><?php _e( 'Rejoice! New responsive breakpoints have been added to the customizer sidebar to make it wider on high-resolution screens. Customizer controls should use percentage-based widths instead of pixels.' ); ?></p>
				</div>
			</div>
=======
					<h3><a href="https://make.wordpress.org/core/2016/11/03/post-type-templates-in-4-7/"><?php _e( 'Post Type Templates' ); ?></a></h3>
					<p><?php _e( 'By opening up the page template functionality to all post types, theme developers have even more flexibility with the WordPress template hierarchy.' ); ?></p>
				</div>
				<div class="col">
					<h3><?php _e( 'More Theme API Goodies' ); ?></h3>
					<p><?php
						printf(
							/* translators: %s: https://make.wordpress.org/core/2016/09/09/new-functions-hooks-and-behaviour-for-theme-developers-in-wordpress-4-7/  */
							__( 'WordPress 4.7 includes <a href="%s">new functions, hooks, and behavior</a> for theme developers.' ),
							'https://make.wordpress.org/core/2016/09/09/new-functions-hooks-and-behaviour-for-theme-developers-in-wordpress-4-7/'
						);
					?></p>
				</div>
				<div class="col">
					<h3><a href="https://make.wordpress.org/core/2016/10/04/custom-bulk-actions/"><?php _e( 'Custom Bulk Actions' ); ?></a></h3>
					<p><?php _e( 'List tables, now with more than bulk edit and delete.' ); ?></p>
				</div>
			</div>

			<div class="under-the-hood three-col">
				<div class="col">
					<h3><a href="https://make.wordpress.org/core/2016/09/08/wp_hook-next-generation-actions-and-filters/"><code>WP_Hook</code></a></h3>
					<p><?php
						printf(
							/* translators: %s: https://make.wordpress.org/core/2016/09/08/wp_hook-next-generation-actions-and-filters/  */
							__( 'The code that lies beneath actions and filters has been overhauled and modernized, fixing bugs along the way.' ),
							'https://make.wordpress.org/core/2016/09/08/wp_hook-next-generation-actions-and-filters/'
						);
					?></p>
				</div>
				<div class="col">
					<h3><?php _e( 'Settings Registration API' ); ?></h3>
					<p><?php
						printf(
							/* translators: 1: register_setting(), 2: https://make.wordpress.org/core/2016/10/26/registering-your-settings-in-wordpress-4-7/ */
							__( '%1$s <a href="%2$s">has been enhanced</a> to include type, description, and REST API visibility.' ),
							'<code>register_setting()</code>',
							'https://make.wordpress.org/core/2016/10/26/registering-your-settings-in-wordpress-4-7/'
						);
					?></p>
				</div>
				<div class="col">
					<h3><a href="https://make.wordpress.org/core/2016/10/12/customize-changesets-technical-design-decisions/"><?php _e( 'Customize Changesets' ); ?></a></h3>
					<p><?php _e( 'Customize changesets make changes in the customizer persistent, like autosave drafts. They also make exciting new features like starter content possible.' ); ?></p>
				</div>
			</div>

>>>>>>> bbfbbb9c81f9c36cbaa8e67ea4b62e0932d77aed
		</div>

		<hr />

		<div class="return-to-dashboard">
			<?php if ( current_user_can( 'update_core' ) && isset( $_GET['updated'] ) ) : ?>
				<a href="<?php echo esc_url( self_admin_url( 'update-core.php' ) ); ?>">
					<?php is_multisite() ? _e( 'Return to Updates' ) : _e( 'Return to Dashboard &rarr; Updates' ); ?>
				</a> |
			<?php endif; ?>
			<a href="<?php echo esc_url( self_admin_url() ); ?>"><?php is_blog_admin() ? _e( 'Go to Dashboard &rarr; Home' ) : _e( 'Go to Dashboard' ); ?></a>
		</div>

	</div>
<?php

include( ABSPATH . 'wp-admin/admin-footer.php' );

// These are strings we may use to describe maintenance/security releases, where we aim for no new strings.
return;

__( 'Maintenance Release' );
__( 'Maintenance Releases' );

__( 'Security Release' );
__( 'Security Releases' );

__( 'Maintenance and Security Release' );
__( 'Maintenance and Security Releases' );

/* translators: %s: WordPress version number */
__( '<strong>Version %s</strong> addressed one security issue.' );
/* translators: %s: WordPress version number */
__( '<strong>Version %s</strong> addressed some security issues.' );

/* translators: 1: WordPress version number, 2: plural number of bugs. */
_n_noop( '<strong>Version %1$s</strong> addressed %2$s bug.',
         '<strong>Version %1$s</strong> addressed %2$s bugs.' );

/* translators: 1: WordPress version number, 2: plural number of bugs. Singular security issue. */
_n_noop( '<strong>Version %1$s</strong> addressed a security issue and fixed %2$s bug.',
         '<strong>Version %1$s</strong> addressed a security issue and fixed %2$s bugs.' );

/* translators: 1: WordPress version number, 2: plural number of bugs. More than one security issue. */
_n_noop( '<strong>Version %1$s</strong> addressed some security issues and fixed %2$s bug.',
         '<strong>Version %1$s</strong> addressed some security issues and fixed %2$s bugs.' );

/* translators: %s: Codex URL */
__( 'For more information, see <a href="%s">the release notes</a>.' );
