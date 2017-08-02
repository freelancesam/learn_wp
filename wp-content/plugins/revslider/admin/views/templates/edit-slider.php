<<<<<<< HEAD
<?php if( !defined( 'ABSPATH') ) exit(); ?>
<input type="hidden" id="sliderid" value="<?php echo $sliderID; ?>"></input>

<?php
$is_edit = true;
require self::getPathTemplate('slider-main-options');
?>

<script type="text/javascript">
	var g_jsonTaxWithCats = <?php echo $jsonTaxWithCats?>;

	jQuery(document).ready(function(){			
		RevSliderAdmin.initEditSliderView();
	});
=======
<?php if( !defined( 'ABSPATH') ) exit(); ?>
<input type="hidden" id="sliderid" value="<?php echo $sliderID; ?>"></input>

<?php
$is_edit = true;
require self::getPathTemplate('slider-main-options');
?>

<script type="text/javascript">
	var g_jsonTaxWithCats = <?php echo $jsonTaxWithCats?>;

	jQuery(document).ready(function(){			
		RevSliderAdmin.initEditSliderView();
	});
>>>>>>> bbfbbb9c81f9c36cbaa8e67ea4b62e0932d77aed
</script>