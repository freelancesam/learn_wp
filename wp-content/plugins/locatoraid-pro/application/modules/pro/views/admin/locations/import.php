<div class="page-header">
<h2><?php echo lang('location_import');?></h2>
</div>

<div class="row">

<div class="span6">
<?php echo form_open_multipart( $this->conf['path'] . '/import/do', array('class' => '')); ?>

<div class="control-group">
<label class="radio inline">
<?php echo form_radio('mode', 'overwrite', 'append'); ?><?php echo lang('location_import_mode_overwrite'); ?>
</label>
<label class="radio inline">
<?php echo form_radio('mode', 'append', 'append'); ?><?php echo lang('location_import_mode_append'); ?>
</label>
</div>

<div class="control-group">
<?php echo form_checkbox('checkduplicates', 1); ?> <?php echo lang('location_import_check_duplicates'); ?>
</div>


<?php
$f = array(
	'name'		=> 'userfile',
	'title'		=> 'userfile',
	);
?>
<div class="control-group">
<label>.csv only</label>
<div class="controls">  
<?php echo form_upload( $f ); ?>
</div>
</div>

<div class="controls">
<?php echo form_button( array('type' => 'submit', 'name' => 'submit', 'class' => 'btn btn-primary'), lang('common_upload')); ?>
</div>

<?php echo form_close(); ?>
</div>

<div class="span4">
<p>
<?php echo lang('location_import_help'); ?>: 
</p>
<ul>
<?php array_map( create_function('$e', 'echo "<li><strong>$e</strong></li>";'), $mandatory_fields ); ?>
<?php array_map( create_function('$e', 'echo "<li>$e</li>";'), $other_fields ); ?>
</ul>
<p>
<?php echo lang('location_products_file_help'); ?>

</div>


</div>