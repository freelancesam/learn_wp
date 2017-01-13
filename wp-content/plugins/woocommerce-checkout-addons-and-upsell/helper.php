<?php
class SB_WC_UpSellHelper 
{
	public static function IsProductInCart($product_id)
	{
		global $woocommerce;
		
		$exists = false;
		foreach(WC()->cart->cart_contents as $item_key => $item)
		{
			if( $item['product_id'] == $product_id )
			{
				$exists = true;
				break;
			}
		}
		
		return $exits;
	}
	public static function GetCartIds()
	{
		
		global $woocommerce;
		
		$ids = array();
		foreach(WC()->cart->cart_contents as $item_key => $item)
		{
			$ids[] = $item['product_id'];
		}
		
		return $ids;
	}
	public static function WriteAddOns()
	{
		$addons = get_option('sb_wc_addons', array());
		if( !is_array($addons) )
			$addons = array();
		foreach($addons as $addon)
		{
			print self::GetAddOnField($addon);
		}
	}
	public static function GetAddOnField($addon_item)
	{
		//$id = str_replace('-', '_', sanitize_title($addon_item['name']));
		$in_cart = self::GetCartIds();
		$id = $addon_item['id'];
		$pids = explode('|', $addon_item['pids']);
		$skip = false;
		foreach($pids as $pid)
		{
			if( in_array($pid, $in_cart) )
			{
				$skip = true;
			}
		}
		if( $skip ) return '';
		$symb = get_woocommerce_currency();
		ob_start();	
		?>
		<div class="addon-field" style="margin-bottom:20px;">
			<?php if($addon_item['type'] != 'checkbox'): ?>
				<div><?php print stripslashes($addon_item['label']); ?><?php if($addon_item['required'] == 1): ?><i class="required">*</i><?php endif;?></div>
				<?php if( $addon_item['type'] == 'text' ): ?>
				<input type="text" name="addon[<?php print $id; ?>]" value="" />
				<?php elseif( $addon_item['type'] == 'radio' ): $ops = explode('|', $addon_item['ops']); ?>
					<?php foreach($ops as $op): ?>
					
					<?php list($l, $val) = explode('=', $op); ?>
					<input type="radio" name="addon[<?php print $id; ?>]" value="<?php print !empty($val) ? $val : $l; ?>" data-fee_name="<?php print $l; ?>" <?php print isset($_SESSION['fees'][$id]) ? 'data-fee_id="'.$id.'"' : ''; ?> />
					<span>	<?php printf("%s %s", $l, empty($val) ? '' : '('.$symb . ' ' . $val.')' ); ?><?php //print $op; ?></span><br/>
					<?php endforeach; ?>
				<?php elseif( $addon_item['type'] == 'dropdown' ): $ops = explode('|', $addon_item['ops']); ?>
					<select name="addon[<?php print $id; ?>]" class="its_fee">
						<option value="-1"><?php _e('-- option --'); ?></option>
						<?php foreach($ops as $op): ?>
						<?php list($l, $val) = explode('=', $op); ?>
						<option value="<?php print !empty($val) ? $val : $l; ?>" data-fee_name="<?php print $l; ?>">
							<?php printf("%s %s", $l, empty($val) ? '' : '('.$symb . ' ' . $val.')' ); ?>
						</option>
						<?php endforeach; ?>
					</select>
				<?php elseif( $addon_item['type'] == 'multi-checkbox' ): $ops = explode('|', $addon_item['ops']); ?>
					<?php foreach($ops as $op): list($text, $_val) = explode('=', $op);?>
					<?php
					$label = empty($_val) ? $text : sprintf("%s (%s %s)", $text, $symb, $_val); 
					$val = ( empty($_val) ) ? $text : $_val;
					$fee_id = str_replace('-', '_', sanitize_title($label));
					?>
					<input type="checkbox" name="addon[<?php print $id; ?>][]" value="<?php print $val; ?>" 
							data-fee_name="<?php print $label?>" data-fee_amount="<?php print $_val; ?>" <?php print !empty($_val) ? 'class="its_fee"' : ''; ?>
							<?php print self::IsFeeInSession($fee_id) ? 'checked data-fee_id="'.$fee_id.'"' : ''; ?> />
					<span><?php print $label; ?></span><br/>
					<?php endforeach; ?>
				<?php elseif( $addon_item['type'] == 'textarea' ): ?>
				<textarea name="addon[<?php print $id; ?>]" style="width:40%;height:150px;"><?php print $addon_item['ops']; ?></textarea>
				<?php elseif( $addon_item['type'] == 'multiselect' ): $_ops = explode('|', $addon_item['ops']); ?>
					<?php 
					//$labels = array();
					$vals = array();
					foreach($_ops as $op)
					{
						if( strstr($op, '=') )
						{
							list($label, $value) = explode('=', $op);
							$vals[$label] = $value;
						}
					}
					?>
				<input type="text" name="addon[<?php print $id; ?>][ops]" value="<?php print implode(',', array_keys($vals)); ?>" class="input-tags" />
				<input type="hidden" name="addon[<?php print $id; ?>][vals]" value="<?php print implode('|', $vals); ?>" />
				<?php elseif( $addon_item['type'] == 'file' ): ?>
				<script>window.use_uploader = true;</script>
				<?php /*<input type="file" name="addons[<?php print $id; ?>][]" value="" class="qq-uploader" />*/ ?>
				<div class="qq-uploader"></div>
				<?php endif; ?>
			<?php else: ?>
				<?php
				//list($text, $_val) = explode('=', $addon_item['ops']);
				//$label = empty($_val) ? $text : sprintf("%s (%s %s)", stripslashes($text), $symb, $_val); 
				//$val = ( empty($_val) ) ? $text : $_val;
				$label	= $addon_item['name'];
				$val	= $addon_item['ops']; 
				if( is_numeric($val) )
				{
					$label .= ': ';/*sprintf(" (%s %s)", $symb, $val);*/
				}
				$fee_id = str_replace('-', '_', sanitize_title($label));
				?>
			<input id="<?php print $fee_id; ?>" type="checkbox" name="addon[<?php print $id; ?>]" value="<?php print $val; ?>" data-fee_name="<?php print $label; ?>" 
					data-fee_amount="<?php print $val; ?>" <?php print (!empty($val) && is_numeric($val)) ? 'class="its_fee"' : ''; ?>
					<?php print self::IsFeeInSession($fee_id) ? 'checked data-fee_id="'.$fee_id.'"' : ''; ?> />
			<span><?php print $label; echo wc_price($val);?></span>
			<?php if($addon_item['required'] == 1): ?><i class="required">*</i><?php endif;?>
			<?php endif; ?>
		</div>
		<?php 
		return ob_get_clean();
	}
	public static function GetAddonById($id)
	{
		static $addons = null;
		if( $addons == null )
		{
			$addons = get_option('sb_wc_addons', array());
			if( !is_array($addons) )
				$addons = array();
		}
		$item = null;
		foreach($addons as $ao)
		{
			if( $ao['id'] == $id )
			{
				$item = $ao;
				break;
			}
		}
		return $item;
	}
	public static function IsFeeInSession($fee_id)
	{
		return isset($_SESSION['fees'][$fee_id]);
	}
}