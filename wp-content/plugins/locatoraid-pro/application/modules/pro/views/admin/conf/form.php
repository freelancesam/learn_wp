<?php
for( $i = 1; $i <= 10; $i++ ){
	$fields[] = array(
		'name' 		=> 'form_misc' . $i,
 		'title'		=> lang('location_misc') . ' ' . $i,
		);
	}
$fields[] = array(
	'name' 		=> 'form_products',
	'title'		=> lang('location_products'),
	);
$fields[] = array(
	'name' 		=> 'form_website',
	'title'		=> lang('location_website'),
	);
reset( $fields );
?>

<div class="page-header">
<h2><?php echo lang('location_misc_form');?></h2>
</div>

<?php echo form_open('', array('class' => 'form-horizontal form-condensed')); ?>

<?php foreach( $fields as $f ) : ?>
<?php
		$error = form_error($f['name']);
		$class = $error ? ' error' : '';
?>
<div class="control-group<?php echo $class; ?>">
<label class="control-label" for="<?php echo $f['name']; ?>"><?php echo $f['title'];?></label>
<div class="controls">
<?php echo form_input($f, set_value($f['name'], $defaults[$f['name']])); ?>
<?php if( $error ) : ?>
<span class="help-inline"><?php echo $error; ?></span>
<?php endif; ?>

<?php if( 0 ) : ?>
	<?php if( ! in_array($f['name'], array('form_products', 'form_website')) ) : ?>
	<?php
		$checkbox_name = $f['name'] . '_hide';
	?>
		<?php echo form_checkbox($checkbox_name, 1, set_checkbox($checkbox_name, 1, $defaults[$checkbox_name] ? TRUE : FALSE) ); ?>
		<?php echo lang('location_misc_form_hide'); ?>
	<?php endif; ?>
<?php endif; ?>
</div>
</div>
<?php endforeach; ?>

<div class="controls">
<?php echo form_submit( array('name' => 'submit', 'class' => 'btn btn-primary'), lang('common_save'));?>
</div>

<?php echo form_close();?>