<?php
/*
  Created on : Nov 4, 2016, 4:07:24 PM
  Author     : Tran Trong Thang
  Email      : trantrongthang1207@gmail.com
  Skype      : trantrongthang1207
 */
/**
 * The template for displaying the Logos.
 * Template name: Logos 
 *
 */
defined('ABSPATH') or die;
$cats = get_terms('logos-cat', array(
	'orderby' => 'slug'
));
if (empty($cats)) {
	return;
}
?>

<div class="h-logos">
	<?php $once = true; ?>
	<?php foreach ($cats as $cat): ?>
		<?php 
		$q = new WP_Query(array(
			'post_type'      => 'logos',
			'posts_per_page' => -1,
			'no_found_rows'  => true,
			// 'orderby'        => 'menu_order',
			'meta_key'       => 'published',
			'orderby'        => 'title',
			'order'          => 'ASC',
			'tax_query'      => array(array(
				'taxonomy'   => 'logos-cat',
				'terms'      => array($cat->term_id),
				'field'      => 'id'
			)),
			'meta_query'  => array(
			    array(
			        'key'     => 'published',
			        'value'   => '1',
			        'compare' => '=='
			    )
			)
		)); 
		?>
		<div class="h-logos__category">
			<div class="container">
				<?php if ($once): ?>
					<!-- <span class="theme-icon-water-drop"></span> -->
				<?php else: ?>
					<br>
				<?php endif; ?>
				<h3 class="h-logos__category-title"><?php
					if ($once) {
						echo '<a href="/join-us/endorsing-companies/">';
					}
					$title = explode(' ', $cat->name);
					$len = round(count($title)/2);
					echo implode(' ', array_slice($title, 0, $len)) . ' <span class="light">' . implode(' ', array_slice($title, $len)) . '</span>';
					if ($once) {
						echo '</a>';
					}
				?></h3>
				<div class="h-logos__category-desc"><?php echo apply_filters('the_content', $cat->description); ?></div>
				<div class="jcarousel-wrapper">
					<div class="jcarousel logos-scrollable">
						<div class="jcarousel-items">
							<?php while ($q->have_posts()): $q->the_post(); ?>
							<div class="jcarousel-item">
								<?php
									$logo_medium = wp_get_attachment_image_src( get_post_thumbnail_id($post->ID), 'medium' )[0];
								?>
								<div class="homepage-logo-wrapper">
								<a href="<?php echo esc_url(get_post_meta(get_the_ID(), '_links_to', true)); ?>">
									<img src="<?php echo $logo_medium; ?>" alt="" class="homepage-logo">
									<?php //the_post_thumbnail('medium', array('class' => 'img-responsive homepage-logo')); ?>
								</a>
								</div>
							</div>
							<?php endwhile; ?>
						</div>
					</div>

					<a href="#" class="jcarousel-control-prev"><span class="glyphicon glyphicon-chevron-left"></span></a>
					<a href="#" class="jcarousel-control-next"><span class="glyphicon glyphicon-chevron-right"></span></a>
				</div>
			</div>
		</div>
		<?php $once = false; ?>
	<?php endforeach; ?>
</div>
<?php wp_reset_query();
