<?php
$fields = array(
	array(
		'name' 		=> 'address_format',
 		'title'		=> lang('conf_address_format'),
		'type'		=> 'textarea',
		),
);
reset( $fields );
?>

<div class="page-header">
<h2><?php echo lang('menu_conf_address');?></h2>
</div>

<?php echo form_open('', array('class' => 'form-horizontal form-condensed')); ?>

<table class="table table-bordered" style="width: auto;">

<tr>
	<td>
	<?php
	$f = $fields[0];
	echo form_textarea( $f['name'], set_value($f['name'], $defaults[$f['name']]), 'style="width: 20em; height: 8em;"' );
	?>
	</td>
	<td>
	<p style="text-decoration: underline;">
		Default Setting
	</p>
{STREET}<br>
{CITY} {STATE} {ZIP}<br>
{COUNTRY}<br>
	</td>
	
</tr>

</table>

<p>
</p>

<p>
<?php echo form_submit( array('name' => 'submit', 'class' => 'btn btn-primary'), lang('common_save'));?>
</p>

<?php echo form_close();?>
