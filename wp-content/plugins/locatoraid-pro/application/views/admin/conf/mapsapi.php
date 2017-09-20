<?php
$fields = array(
	array(
		'name' 		=> 'mapsapi',
 		'title'		=> 'Google Maps API Key',
		'type'		=> 'text',
		),
);
reset( $fields );
// https://developers.google.com/maps/documentation/javascript/get-api-key#get-an-api-key
// https://console.developers.google.com/flows/enableapi?apiid=maps_backend&keyType=CLIENT_SIDE&reusekey=true
?>

<div class="page-header">
<h2>Google Maps API Key</h2>
</div>

<p>
Usage of the Google Maps APIs now requires an API key which you can get from the Google Maps developers website.
</p>

<p>
<a target="_blank" href="https://console.developers.google.com/flows/enableapi?apiid=maps_backend&keyType=CLIENT_SIDE&reusekey=true">Get Google Maps API key</a>
</p>

<?php echo form_open('', array('class' => 'form-horizontal form-condensed')); ?>

<p>
<?php
$f = $fields[0];
echo form_input( $f['name'], set_value($f['name'], $defaults[$f['name']]), 'style="width: 90%;" placeholder="Your Google Maps API Key"' );
?>
</p>

<p>
<?php echo form_submit( array('name' => 'submit', 'class' => 'btn btn-primary'), lang('common_save'));?>
</p>

<?php echo form_close();?>
