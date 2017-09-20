<div class="page-header">
<h2><?php echo lang('menu_stats'); ?></h2>
</div>

<dl class="dl-horizontal">
<dt><?php echo lang('time_from'); ?></dt>
<dd>
<?php 
$this->hc_time->setTimestamp( $from );
echo $this->hc_time->formatFull();
?>
</dd>

<dt><?php echo lang('time_to'); ?></dt>
<dd>
<?php 
$this->hc_time->setTimestamp( $to );
echo $this->hc_time->formatFull();
?>
</dd>

<dt><?php echo lang('common_select'); ?></dt>
<dd>
<?php echo form_open('pro/admin/stats/shortcut', array('id' => 'lpr-shortcut-form')); ?>
<?php
$options = array();
reset( $shortcuts );
foreach( $shortcuts as $sho )
{
	$options[ $sho ] = lang('time_' . $sho);
}
?>
<?php echo form_dropdown('shortcut', $options, set_value('shortcut', $shortcut)); ?>
<?php echo form_close();?>
</dd>

</dl>

<?php if( count($entries) ) : ?>

<div class="accordion" id="lpr-accordion">

<div class="accordion-group">
<div class="accordion-heading">
	<div class="row-fluid">
		<div class="span3"><strong><?php echo lang('stats_address'); ?></strong></div>
		<div class="span2"><strong><?php echo lang('stats_qty'); ?></strong></div>
	</div>
</div>
</div>

<?php for( $ii = 0; $ii < count($entries); $ii++ ) : ?>
<?php	$e = $entries[ $ii ]; ?>

<div class="accordion-group">

<?php if( ! is_array($e['search']) ) : ?>

<div class="accordion-heading">
	<div class="row-fluid">
		<div class="span3"><?php echo $e['address']; ?></div>
		<div class="span2"><?php echo $e['count']; ?></div>
	</div>
</div>

<?php else : ?>

<div class="accordion-heading">
	<div class="row-fluid">
		<div class="span3">
		<a class="accordion-toggle" data-toggle="collapse" data-parent="#lpr-accordion" href="#collapse<?php echo ($ii + 1); ?>"><?php echo $e['address']; ?></a>
		</div>

		<div class="span2">
		<a class="accordion-toggle" data-toggle="collapse" data-parent="#lpr-accordion" href="#collapse<?php echo ($ii + 1); ?>"><?php echo $e['count']; ?></a>
		</div>
	</div>
</div>

<div id="collapse<?php echo ($ii + 1); ?>" class="accordion-body collapse out">
<div class="accordion-inner">
<?php foreach( $e['search'] as $key => $count ) : ?>
<?php
		$key = $key ? $key : ' - ' . lang('common_any') . ' - ';
?>
	<div class="row-fluid">
		<div class="span3"><?php echo $key; ?></div>
		<div class="span2"><?php echo $count; ?></div>
	</div>
<?php endforeach; ?>
</div>
</div>

<?php endif; ?>

</div>
<?php endfor; ?>

</div>





<?php
$heading = array(
	lang('stats_address'),
	lang('stats_qty'),
	);
?>

<?php else : ?>

<p>
<?php echo lang('common_none'); ?>
</p>

<?php endif; ?>

<script language="JavaScript">
jQuery('#lpr-shortcut-form').find('[name=shortcut]').on( 'change', function(event) 
{
	jQuery('#lpr-shortcut-form').submit();
});
</script>