<?php
?>
<script src="<?php print $this->_plugin_url; ?>/js/jquery.tagsinput.js"></script>
<script>
//<![CDATA[
if( !document.getElementById('tags-input-css') )
{
	var css = document.createElement('link');
	css.id = 'tags-input-css';
	css.rel = 'stylesheet';
	css.href = '<?php print $this->_plugin_url; ?>/css/jquery.tagsinput.css';
	document.head.appendChild(css);
}
function on_add_tag(val)
{
}
function wc_addon_add_fee(name, amount)
{
	var obj = jQuery(this);
	var params = 'task=sb_wc_addon_add_fee&name='+name+'&amount='+amount; 
	jQuery.post('<?php print home_url('/index.php'); ?>', params, function(res)
	{
		if( res.status == 'ok' )
		{
			if(res.fee_id)
			{
				obj.attr("data-fee_id", res.fee_id);
			}
		}
		else
		{
			if(res.error) alert(res.error);
		}
		jQuery('body').trigger('update_checkout');
	});
}
function wc_addon_remove_fee(fee_id)
{
	var obj = jQuery(this);
	var params = 'task=sb_wc_addon_remove_fee&fee_id='+fee_id; 
	jQuery.post('<?php print home_url('/index.php'); ?>', params, function(res)
	{
		if( res.status == 'ok' )
		{
			obj.data("fee_id", null);
			obj.attr("data-fee_id", null);
			obj.attr("checked", null);
		}
		else
		{
			if(res.error) alert(res.error);
		}
		jQuery('body').trigger('update_checkout');
	});
}
jQuery(function()
{
	jQuery('.input-tags').tagsInput({width: '70%', height: '40px', onAddTag: on_add_tag});
	if( window.use_uploader )
	{
		if( jQuery('#qq-uploader-js').length <= 0 )
		{
			
			var js_qq = document.createElement('script');
			js_qq.id = 'qq-uploader-js';
			js_qq.src = '<?php print $this->_plugin_url;?>/js/fine-uploader-master/all.fineuploader-5.0.8.min.js';
			js_qq.onload = function()
			{
				jQuery('.qq-uploader').each(function(i, obj)
				{
					window.uploader = new qq.FineUploader({
				      	element: obj,
				      	request: 
						{
				        	endpoint: '<?php print home_url('/index.php?task=sb_wc_addon_upload') ?>'
				      	},
				      	deleteFile: 
					    {
				            enabled: true,
				            method: 'POST', 
				            endpoint: '<?php print home_url('/index.php?task=sb_wc_addon_delete_img') ?>'
				        },
				      	callbacks: 
					    {
				      		onComplete: function(id, name, responseJSON, xhr)
					      	{
						      	//console.log(arguments);
						      	if(jQuery('#add-on-images').val().length <= 0  )
				      				jQuery('#add-on-images').val(responseJSON.qquuid);
						      	else
						      		jQuery('#add-on-images').val(jQuery('#add-on-images').val().concat(',' + responseJSON.qquuid));
						    }
						}
					});
				});
				
			};
			var css_qq = jQuery('<link />', {rel: 'stylesheet', href: '<?php print $this->_plugin_url;?>/js/fine-uploader-master/client/fineuploader.css'});
			jQuery('head').append(css_qq);
			document.head.appendChild(js_qq);
			document.getElementById('add-on-images').value = '';
		}
		
	}
        /*
	jQuery(document).on('click change', '.its_fee', function(e)
	{
		var obj 	= window.kk = jQuery(this);
		
		if( obj.get(0).tagName == 'INPUT' && obj.get(0).type == 'checkbox' )
		{
			var name 	= jQuery(this).data('fee_name');
			var amount 	= jQuery(this).data('fee_amount');
			if( name.length > 0 || amount.length > 0 )
			{
				if( jQuery(obj).data('fee_id') )
				{
					var fee_id = jQuery(obj).data('fee_id');
					wc_addon_remove_fee.call(obj, fee_id);
				}
				else
				{
					wc_addon_add_fee.call(obj, name, amount); 
				}
			}
		}
		else if( obj.get(0).tagName == 'SELECT' && e.type == 'change' )
		{
			if( this.value == -1 && jQuery(obj).data('fee_id') != 'undefined' )
			{
				var fee_id = jQuery(obj).data('fee_id');
				wc_addon_remove_fee.call(obj, fee_id);
			}
			else if( !isNaN(this.value) )
			{
				//##if there is amount to apply for fee
				var name = obj.find('option:selected').data('fee_name');
				wc_addon_add_fee.call(obj, name, this.value); 
			}
		}
	});*/
});
//]]>
</script>
<?php SB_WC_UpSellHelper::WriteAddOns(); ?>
<input type="hidden" id="add-on-images" name="addon[images]" value="" />
<!-- Fine Uploader template
====================================================================== -->
<script type="text/template" id="qq-template">
  <div class="qq-uploader-selector qq-uploader">
    <div class="qq-upload-drop-area-selector qq-upload-drop-area" qq-hide-dropzone>
      <span>Drop files here to upload</span>
    </div>
    <div class="qq-upload-button-selector qq-upload-button">
      <div><?php _e('Drag file here or click to upload'); ?></div>
    </div>
    <span class="qq-drop-processing-selector qq-drop-processing">
      <span>Processing dropped files...</span>
      <span class="qq-drop-processing-spinner-selector qq-drop-processing-spinner"></span>
    </span>
    <ul class="qq-upload-list-selector qq-upload-list">
      <li>
        <div class="qq-progress-bar-container-selector">
          <div class="qq-progress-bar-selector qq-progress-bar"></div>
        </div>
        <span class="qq-upload-spinner-selector qq-upload-spinner"></span>
        <span class="qq-edit-filename-icon-selector qq-edit-filename-icon"></span>
        <span class="qq-upload-file-selector qq-upload-file"></span>
        <input class="qq-edit-filename-selector qq-edit-filename" tabindex="0" type="text">
        <span class="qq-upload-size-selector qq-upload-size"></span>
        <a class="qq-upload-cancel-selector qq-upload-cancel" href="#">Cancel</a>
        <a class="qq-upload-retry-selector qq-upload-retry" href="#">Retry</a>
        <a class="qq-upload-delete-selector qq-upload-delete" href="#">Delete</a>
        <span class="qq-upload-status-text-selector qq-upload-status-text"></span>
      </li>
    </ul>
  </div>
</script>