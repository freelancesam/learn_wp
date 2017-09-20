<div class='rd_metabox'>
<?php
$this->select(	'slider_type',
'Page Slider',
array('no' => 'No Slider', 'layer' => 'Revolution Slider', 'layerslider' => 'LayerSlider' ),
''
);
?>

<?php
if(is_plugin_active('revslider/revslider.php')) {
$slider = new RevSlider();
$arrSliders = $slider->getArrSlidersShort();
$arrSliders[0] = 'Select a slider';
$this->select(	'slider',
'Select Revolution Slider',
$arrSliders,
''
);
}
?>
<?php

if(is_plugin_active('LayerSlider/layerslider.php')) {
		global $wpdb;
		$slides_array[0] = 'Select a slider';
		// Table name
		$table_name = $wpdb->prefix . "layerslider";

		// Get sliders
		$sliders = $wpdb->get_results( "SELECT * FROM $table_name
											WHERE flag_hidden = '0' AND flag_deleted = '0'
											ORDER BY date_c ASC" );

		if(!empty($sliders)):
		foreach($sliders as $key => $item):
			$slides[$item->id] = '';
		endforeach;
		endif;

		if(isset($slides) && $slides){
		foreach($slides as $key => $val){
			$slides_array[$key] = 'LayerSlider #'.($key);
		}
		}
		$this->select(	'layerslider',
						'Select LayerSlider',
						$slides_array,
						''
					);
}
?>
<?php
$this->select(	'slider_position',
				'Slider Position',
				array('under' => 'Under The Header','above' => 'Above The Header'),
				''
);
?>
<?php

$this->select(	'top_bar',
				'Hide Header Top Bar?',
				array('no' => 'No', 'yes' => 'Yes', ),
				''
);
?>
<?php
$this->select(	'header_transparent',
				'Transparent header?',
				array('no' => 'No', 'yes' => 'Yes', ),
				''
);
?>

<?php
$this->select(	'title',
				'Show title?',
				array('yes' => 'Yes', 'no' => 'No'),
				''
);
?>

<?php
$this->textarea(	'title_height',
'Title height (example 100)'
);
?>

<?php
$this->color(	'title_color',
				'Select title color'
);
?>

<?php
$this->color(	'titlebg_color',
				'Select title background color'
);
?>

<?php
$this->upload(	'ctbg',
'Custom title background'
);
?>


<?php
$this->select(	'bc',
				'Show Subtitle and Breadcrumbs?',
				array('yes' => 'Yes', 'no' => 'No'),
				''
);
?>

<?php
$this->select(	'show_navigation',
				'Show Post navigation?',
				array('yes' => 'Yes', 'no' => 'No'),
			''
			);
?>
<?php
$this->select(	'show_slider',
				'Show Slider / Video / Audio on the top of the post?',
				array('yes' => 'Yes', 'no' => 'No'),
			''
			);
?>

<?php
$this->textarea(	'video',
				'Video Embed Code (needed if video post)'
			);
?>

<?php
$this->textarea(	'quote',
				'Quote text (needed if quote post)'
);
?>

<?php
$this->textarea(	'quote_author',
"Quote author name (needed if quote post)"
);
?>

<?php
$this->select(	'sidebar',
				'Select Post layout',
				array('right' => 'Post with Right Sidebar', 'left' => 'Post with Left Sidebar', 'none' => 'Post with no sidebar', ),
				''
);
?>



<?php
$this->select(	'share_buttons',
				'Show Share buttons',
				array('yes' => 'Yes', 'no' => 'No'),
				''
);
?>

<?php

$this->select(	'author_bio',
				'Show Author biography?',
				array('yes' => 'Yes', 'no' => 'No'),
				''
			);
?>

<?php
$this->select(	'related_post',
				'Show Related post?',
				array('yes' => 'Yes', 'no' => 'No'),
				''
			);

?>
</div>