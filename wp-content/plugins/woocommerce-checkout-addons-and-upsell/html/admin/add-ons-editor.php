<?php

?>
<table id="wc-addons" class="wp-list-table widefat fixed">
<thead>
<tr>
	<th class="manage-column column-cb check-column"><input type="checkbox" id="cb" name="cb" value="1" /></th>
	<th><?php _e('Name' , 'wupc'); ?></th>
	<th><?php _e('Checkout Label' , 'wupc'); ?></th>
	<th style="width:100px;"><?php _e('Type' , 'wupc'); ?></th>
	<th><?php _e('Options/Costs' , 'wupc'); ?></th>
	<th style="width:65px;"><?php _e('Required' , 'wupc'); ?></th>
	<th><?php _e('Excluded Products' , 'wupc'); ?></th>
</tr>
</thead>
<tbody>
	<?php $i = 0; foreach($addons as $ao): ?>
	<tr>
		<td><div class="roundedTwo"><input type="checkbox" name="ao[<?php print $i; ?>][cb]" value="1" class="row-selection" /></div></td>
		<td><input type="text" name="ao[<?php print $i; ?>][name]" value="<?php print stripslashes($ao['name']); ?>" /></td>
		<td><input type="text" name="ao[<?php print $i; ?>][label]" value="<?php print stripslashes($ao['label']); ?>" /></td>
		<td class="cell-types">
			<select name="ao[<?php print $i; ?>][type]">
				<option value="text" <?php print $ao['type'] == 'text' ? 'selected' : ''; ?>><?php _e('Text', 'wupc'); ?></option>
				<option value="radio" <?php print $ao['type'] == 'radio' ? 'selected' : ''; ?>><?php _e('Radio', 'wupc'); ?></option>
				<option value="checkbox" <?php print $ao['type'] == 'checkbox' ? 'selected' : ''; ?>><?php _e('Checkbox', 'wupc'); ?></option>
				<option value="multi-checkbox" <?php print $ao['type'] == 'multi-checkbox' ? 'selected' : ''; ?>><?php _e('Multi Checkbox', 'wupc'); ?></option>
				<option value="dropdown" <?php print $ao['type'] == 'dropdown' ? 'selected' : ''; ?>><?php _e('Dropdown', 'wupc'); ?></option>
				<option value="multiselect" <?php print $ao['type'] == 'multiselect' ? 'selected' : ''; ?>><?php _e('Multi Select', 'wupc'); ?></option>
				<option value="textarea" <?php print $ao['type'] == 'textarea' ? 'selected' : ''; ?>><?php _e('Text Area', 'wupc'); ?></option>
				<option value="file" <?php print $ao['type'] == 'file' ? 'selected' : ''; ?>><?php _e('File Uploader', 'wupc'); ?></option>
			</select>
		</td>
		<td><input type="text" name="ao[<?php print $i; ?>][ops]" value="<?php print $ao['ops']; ?>" /></td>
		<td style="width:63px;text-align:center;">
		<div class="roundedTwo">
			<input type="checkbox" id="roundedTwo" name="ao[<?php print $i; ?>][required]" value="1" <?php print $ao['required'] == '1' ? 'checked' : ''; ?> />
		</div>
		</td>
		<td>
			<?php /* ?><input type="text" name="ao[<?php print $i; ?>][exclude]" value="<?php print $ao['exclude']; ?>" class="exclude-tags" /> */ ?>
			<input type="hidden" name="ao[<?php print $i; ?>][pids]" value="<?php print $ao['pids']; ?>" class="exclude-tags" />
		</td>
	</tr>
	<?php $i++; endforeach; ?>
</tbody>
</table>
<input type="hidden" name="task" value="save_addons" />
<p>
	<a href="javascript:;" id="btn-add-addon" class="button-secondary"><?php _e('New Add-On'); ?></a>
	<a href="javascript:;" id="btn-remove-addon" class="button-secondary"><?php _e('Remove selected'); ?></a>
</p>
<script id="row-tpl" type="text/template">
	<tr>
		<td><div class="roundedTwo"><input type="checkbox" name="ao[index][cb]" value="" class="row-selection" /></div></td>
		<td><input type="text" name="ao[index][name]" value="" style="width:100%;" /></td>
		<td><input type="text" name="ao[index][label]" value="" style="width:100%;" /></td>
		<td class="cell-types">
			<select name="ao[index][type]">
				<option value="text"><?php _e('Text' , 'wupc'); ?></option>
				<option value="radio"><?php _e('Radio' , 'wupc'); ?></option>
				<option value="checkbox"><?php _e('Checkbox' , 'wupc'); ?></option>
				<option value="multi-checkbox"><?php _e('Multi Checkbox' , 'wupc'); ?></option>
				<option value="dropdown"><?php _e('Dropdown' , 'wupc'); ?></option>
				<option value="multiselect"><?php _e('Multi Select' , 'wupc'); ?></option>
				<option value="textarea"><?php _e('Text Area' , 'wupc'); ?></option>
				<option value="file"><?php _e('File Uploader' , 'wupc'); ?></option>
			</select>
		</td>
		<td><input type="text" name="ao[index][ops]" value="" style="width:100%;" /></td>
		<td style="text-align:center;"><input type="checkbox" name="ao[index][required]" value="1" /></td>
		<td>
			<input type="text" name="ao[index][pids]" value="" style="width:100%;" class="exclude-tags" />
		</td>
	</tr>
</script>
<script src="<?php print $this->_plugin_url; ?>/js/jquery.tagsinput.js"></script>
<style>
.tagsinput{width:99% !important;}
.cell-types select{width:99%;}
</style>
<script>
var uc_url = '<?php print admin_url('/index.php?task=uc_prod');?>';
/*
function on_add_tag(pid)
{
	//console.log(arguments);
	var pids = jQuery(this).parent().find('.exclude-pids:first');
	if( pids.val().length <= 0 )
	{
		pids.val(pid);
	}
	else
	{
		pids.val(pids.val() + '|' + pid);
	}
}
function on_remove_tag()
{
	var pids = jQuery(this).parent().find('.exclude-pids:first').val();
	if( pids.indexOf('|') == -1 )
	{
		jQuery(this).parent().find('.exclude-pids:first').val('');
		return true;
	}
	pids = pids.substr(0, pids.lastIndexOf('|'));
	jQuery(this).parent().find('.exclude-pids:first').val(pids);
}
*/
jQuery('head').append('<link rel="stylesheet" href="<?php print $this->_plugin_url; ?>/css/jquery.tagsinput.css" />');
jQuery(function()
{
	jQuery('.exclude-tags').tagsInput({defaultText: '', autocomplete_url: uc_url/*, onAddTag: on_add_tag, onRemoveTag: on_remove_tag*/});
	jQuery('#btn-add-addon').click(function()
	{
		var total_rows = jQuery('#wc-addons tbody tr').length;
		var row = jQuery(jQuery('#row-tpl').html().replace(/index/g, total_rows));
		jQuery('#wc-addons tbody').append(row);
		row.find('.exclude-tags').tagsInput({defaultText: '', autocomplete_url: uc_url/*, onAddTag: on_add_tag, onRemoveTag: on_remove_tag*/});
		row.css('width', '98%');
	});
	jQuery('#btn-remove-addon').click(function()
	{
		jQuery('#wc-addons tbody .row-selection:checked').each(function(i, cb)
		{
			jQuery(cb).parents('tr:first').remove();
		});
	});
});
</script>