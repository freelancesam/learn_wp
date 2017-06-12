<?php 
/* 
* Metabox template for "Singapore Maps" Wordpress plugin 
*/
?>
<div style='margin-top: 10px; overflow: hidden;'>
	<div style='float: left; line-height: 23px; width: 100px;'>Street Address:</div>
	<input class='regular-text' type='text' name='street_address' value='<?php echo $street_address; ?>' />
	<p class='description' style='margin-left: 100px;'>This address is used to position the map marker.</p>
</div>
<div style='margin-top: 10px; overflow: hidden;'>
	<div style='float: left; line-height: 23px; width: 100px;'>Description:</div>
	<textarea class='regular-text' name='listing_desc' style='height: 90px; width: 300px;'><?php echo $listing_desc; ?></textarea>
	<p class='description' style='margin-left: 100px;'>This description is shown in the marker box.</p>
</div>
<div style='margin-top: 10px;'>
	<div style='overflow: hidden;'>
		<div style='float: left; line-height: 23px; width: 100px;'>Region:</div>
		<div style='float: left; width: 100px;'>
			<?php $checked = checked($listing_region, 'Central', false); ?>
			<label><input type='radio' name='listing_region' value='Central' <?php echo $checked; ?> />&nbsp;&nbsp;Central</label>
			<?php $checked = checked($listing_region, 'East', false); ?>
			<div style='margin: 3px 0;'><label><input type='radio' name='listing_region' value='East' <?php echo $checked; ?> />&nbsp;&nbsp;East</label></div>
		</div>
		<div style='float: left; width: 100px;'>
			<?php $checked = checked($listing_region, 'North', false); ?>
			<label><input type='radio' name='listing_region' value='North' <?php echo $checked; ?> />&nbsp;&nbsp;North</label>
			<?php $checked = checked($listing_region, 'North-East', false); ?>
			<div style='margin: 3px 0;'><label><input type='radio' name='listing_region' value='North-East' <?php echo $checked; ?> />&nbsp;&nbsp;North-East</label></div>
		</div>
		<div style='float: left;'>
			<?php $checked = checked($listing_region, 'West', false); ?>
			<label><input type='radio' name='listing_region' value='West' <?php echo $checked; ?> />&nbsp;&nbsp;West</label>
		</div>
	</div>
	<p class='description' style='margin-left: 100px;'>Choose a region on the map for the listing.</p>
</div>