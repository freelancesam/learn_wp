<?php
if( isset($GLOBALS['NTS_IS_PLUGIN']) )
{
	$display = $GLOBALS['NTS_IS_PLUGIN'];
}
else
{
	$display = 'builtin';
}
?>
<?php
switch( $display )
{
	case 'wordpress':
		$target2 = 'ULR_OF_THE_PAGE_WITH_LOOKUP_RUNNER_SHORTCODE';
		break;

	default:
		$target2 = 'PAGE_WITH_LOOKUP_RUNNER_INSTALLATION_CODE';
		break;
}

$html2  =<<<EOT
<form method="get" action="$target2">
<input type="text" name="lpr-search" value="address or zip code">
<input type="submit" value="Search">
</form>
EOT;

switch( $display )
{
	case 'builtin':
?>
<p>
<?php echo lang('install_help'); ?>
</p>
<?php
$target = ci_site_url('load');

$html  =<<<EOT
<script language="JavaScript">
var src = '$target';
if( window.location.href.indexOf("www.") < 0 ){
	src = src.replace('://www.', '://');
	}
document.writeln('<' + 'script src="' + src + '"' + ' type="text/javascript"><' + '/script>');
</script>
EOT;
?>

<p>
<textarea name="code" style="width: 70em; font-size: 0.8em; line-height: 1em;" cols="110" rows="10" onclick="this.focus();this.select()" readonly="readonly"><?php echo htmlentities($html); ?></textarea>
</p>

<?php
		break;

	case 'wordpress':
?>
<p>
<?php echo lang('install_help_wordpress'); ?>:
</p>

<textarea name="code2" style="width: auto;" cols="20" rows="2" onclick="this.focus();this.select()" readonly="readonly">
[<?php echo $this->config->item('nts_app'); ?>]
</textarea>

<?php
		break;
}
?>

<?php if( $display != 'wordpress' ) : ?>
<p>
<?php echo lang('install_help_remote_form'); ?>

<p>
<textarea name="code2" style="width: auto; font-size: 0.8em; line-height: 1em;" cols="90" rows="6" onclick="this.focus();this.select()" readonly="readonly"><?php echo htmlentities($html2); ?></textarea>

</p>
<?php endif; ?>