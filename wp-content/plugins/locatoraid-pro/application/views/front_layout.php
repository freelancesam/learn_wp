<?php
$widths = array(
	'search_label'	=> 11,
	'search_input'	=> 3,
	'countries'		=> 2,
	'products'		=> 2,
	'within'		=> 2,
	'button'		=> 3,
	);

$use_text_labels = $this->app_conf->get( 'use_text_labels' );
$use_text_labels = $use_text_labels ? 1 : 0;

$search_label = $this->app_conf->get( 'search_label' );
$search_label = strlen($search_label) ? $search_label : lang('front_address_or_zip');

$search_label_before = $this->app_conf->get( 'search_label_before' );
$search_label_before = strlen($search_label_before) ? $search_label_before : '';

$search_label_before_radius = $this->app_conf->get( 'search_label_before_radius' );
$search_label_before_radius = strlen($search_label_before_radius) ? $search_label_before_radius : '';

$search_button = $this->app_conf->get( 'search_button' );
$search_button = strlen($search_button) ? $search_button : lang('common_search');

$autodetect_button = $this->app_conf->get( 'autodetect_button' );
$autodetect_button = strlen($autodetect_button) ? $autodetect_button : lang('front_autodetect');

$your_location_label = $this->app_conf->get( 'your_location_label' );
$your_location_label = strlen($your_location_label) ? $your_location_label : lang('front_current_location');

$is_mobile = FALSE;
if( class_exists('ppUtil') && ppUtil::renderingMobileSite() )
	$is_mobile = TRUE;
?>
<?php echo form_open('search', array('id' => 'lpr-search-form', 'class' => 'form-horizontal form-condensed')); ?>

<?php if( ! $countries_options ) : ?>
	<?php echo form_hidden( 'country', '' ); ?>
<?php endif; ?>
<?php if( ! $product_options ) : ?>
	<?php echo form_hidden( 'search2', '' ); ?>
<?php endif; ?>

<ul class="list-unstyled list-margin-v">
	<?php if( $conf_trigger_autodetect ) : ?>
		<li style="text-align: left;">
			<a rel="nofollow" href="#" id="lpr-autodetect"><?php echo $autodetect_button; ?></a>
		</li>
	<?php endif; ?>

	<li style="display: none; text-align: left;">
		<a rel="nofollow" href="#" id="lpr-skip-current-location"><?php echo $search_label; ?></a>
	</li>
	
	<li>

<?php
$display_form = array();

$display_form['search_input'] = array();
if( $use_text_labels ){
	$display_form['search_input'][] = '<div style="margin: 0 0; padding: 0 0;">' . $search_label_before . '</div>';
}
$display_form['search_input'][] = 
'<div id="lpr-current-location" style="display: none;">' . 
$your_location_label . 
'</div>'
;
$display_form['search_input'][] = 
	form_input(
		array(
			'name' => 'search',
			'id' => 'lpr-search-address',
			'style' => 'margin: 0 0; width: 95%;',
			'class' => '',
			'placeholder' => $search_label
			),
		set_value('search', $search)
		);
$display_form['search_input'] = join(' ', $display_form['search_input']);

if( $countries_options ){
	$display_form['countries'] = array();
	if( $use_text_labels ){
		$this_label = array_shift( $countries_options );
		$this_label = trim( $this_label, ' - ' );
		$display_form['countries'][] = '<div style="margin: 0 0; padding: 0 0;">' . $this_label . '</div>';
		array_unshift( $countries_options, ' - ' );
	}
	$display_form['countries'][] = 
		form_dropdown( 
			'country',
			$countries_options,
			set_value('country', $country),
			'title="' . lang('location_country') . '" id="lpr-countries-dropdown" style="margin: 0 0; width: 100%;"'
			);
	$display_form['countries'] = join(' ', $display_form['countries']);
}

if( $product_options ){
	$display_form['products'] = array();
	if( $use_text_labels ){
		$this_label = array_shift( $do_options );
		$this_label = trim( $this_label, ' - ' );
		$display_form['products'][] = '<div style="margin: 0 0; padding: 0 0;">' . $this_label . '</div>';
		array_unshift( $do_options, ' - ' );
	}
	$display_form['products'][] = 
		form_dropdown( 
			'search2',
			$do_options,
			set_value('search2', $search2),
			'id="lpr-products-dropdown" style="margin: 0 0; width: 100%;"'
			);
	$display_form['products'] = join(' ', $display_form['products']);
}

if( count($within_options) > 1 ){
	$display_form['within'] = array();

	if( strlen($search_label_before_radius) ){
		if( $use_text_labels ){
			$display_form['within'][] = '<div style="margin: 0 0; padding: 0 0;">' . $search_label_before_radius . '</div>';
		}
		else {
			$dropdown_within[''] = ' - ' . $search_label_before_radius . ' - ';
			ksort($dropdown_within);
		}
	}

	$display_form['within'][] = 
		form_dropdown( 
			'within', 
			$dropdown_within,
			'',
			'id="lpr-search-within" style="margin: 0 0; width: 100%;"',
			array('')
		);
	$display_form['within'] = join(' ', $display_form['within']);
}

$display_form['button'] = array();
if( $use_text_labels ){
	$display_form['button'][] = '<div style="margin: 0 0; padding: 0 0;">' . '&nbsp;' . '</div>';
}
$display_form['button'][] = '
<input type="submit" id="lpr-search-button" value="' . $search_button . '" style="margin: 0 0; width: 100%;">
';
$display_form['button'] = join('', $display_form['button']);

?>
<?php
$remain_width = 12;
if( isset($display_form['products']) ){
	$remain_width -= $widths['products'];
}
if( isset($display_form['countries']) ){
	$remain_width -= $widths['countries'];
}
if( isset($display_form['within']) ){
	$remain_width -= $widths['within'];
}
if( isset($display_form['button']) ){
	$remain_width -= $widths['button'];
}
$widths['search_input'] = $remain_width;

$custom_styles = array(
	// 'search_label'	=> 'text-align: right; line-height: 2em;',
	// 'button'		=> 'line-height: 2em;',
	);
?>

<?php if( 0 && isset($display_form['search_label']) ) : ?>
<div class="hc-clearfix">
	<?php
	$style = 'white-space: nowrap; text-align: left;';
	?>
	<div class="hc-sm-col hc-sm-col-7" style="<?php echo $style; ?>">
		<?php echo $display_form['search_label']; ?>
	</div>
</div>
<?php unset( $display_form['search_label'] ); ?>
<?php endif; ?>

<div class="hc-clearfix hc-mxn2">
<?php foreach( $display_form as $k => $v ) : ?>
<?php
		$col = $widths[$k];
		$style = 'white-space: nowrap; margin-bottom:.25em; text-align: left;';
		if( isset($custom_styles[$k]) ){
			$style .= $custom_styles[$k];
		}
?>
	<div class="hc-sm-col hc-px2 hc-sm-col-<?php echo $col; ?>" style="<?php echo $style; ?>">
		<?php echo $v; ?>
	</div>
<?php endforeach; ?>
</div>

	</li>
</ul>

<?php echo form_close(); ?>

<div id="lpr-results" class="hc-clearfix hc-mxn2">
	<?php if( $show_sidebar ) : ?>
		<div id="lpr-map" class="hc-sm-col hc-sm-col-8 hc-px2"></div>
		<div id="lpr-locations" class="hc-sm-col hc-sm-col-4 hc-px2"></div>
	<?php else : ?>
		<div id="lpr-map"></div>
	<?php endif; ?>	
</div>