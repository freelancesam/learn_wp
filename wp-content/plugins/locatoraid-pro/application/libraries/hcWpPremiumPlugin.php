<?php
$checker_file = dirname(__FILE__) . '/wp-plugin-update-checker/plugin-update-checker.php';
if( (! class_exists('hcWpPremiumPlugin')) && file_exists($checker_file) )
{
include_once( $checker_file );

class hcWpPremiumPlugin
{
	var $system_type = 'nts'; // or ci
	var $slug = '';
	var $app = '';
	var $my_type = 'own'; // or wp
	var $checker = NULL;
	var $hc_product = '';
	var $full_path = '';
	private $license_server = '';

	function __construct( 
		$app,			// app
		$product,		// hitcode product name
		$slug,			// slug in wp admin
		$full_path,		// full path of the original plugin file
		$system_type	// nts or ci
		)
	{
		$this->system_type = $system_type;
		$this->app = $app;
		$this->slug = $slug;
		$this->hc_product = $product;
		$this->full_path = $full_path;

		if( defined('NTS_DEVELOPMENT') ){
			$this->license_server = 'http://localhost/hitcode/customers/';
		}
		else {
			if( isset($_SERVER['HTTPS']) && ($_SERVER['HTTPS'] == 'on') ){
				$this->license_server = 'https://gator3310.hostgator.com/~algis/customers/';
			}
			else {
				$this->license_server = 'http://www.hitcode.com/customers/';
			}
		}

		$check_url = $this->license_server . 'update.php?&slug=' . $product;

		$check_period = 24;

		$this->checker = new PluginUpdateChecker_1_5 (
			$check_url,
			$full_path,
			$product,
			$check_period
			);

		$this->checker->addQueryArgFilter( array($this, 'give_code_to_checker') );

	// add more links in plugin list
		add_action( 'after_plugin_row_' . plugin_basename($full_path), array($this, 'license_details'), 10, 3 );

		if( is_multisite() )
			$filter_name = 'network_admin_plugin_action_links_' . plugin_basename($full_path);
		else
			$filter_name = 'plugin_action_links_' . plugin_basename($full_path);
		add_filter( $filter_name, array($this, 'license_link') );

	// reset check period after license code change
		$option_name = $this->app . '_license_code';
		add_action( 'update_site_option_' . $option_name, array($this, 'reset_license') );
	}

	public function give_code_to_checker( $args )
	{
		$args['code'] = $this->get_license();

		$my_url = get_admin_url() . 'admin.php?page=' . $this->slug;
	// strip http
		$my_url = preg_replace('#^https?://#', '', $my_url);
		$args['url'] = $my_url;

		$plugin_data = get_plugin_data( $this->full_path );
		$installed_version = $plugin_data['Version'];
		$args['ver'] = $installed_version;

		return $args;
	}

	public function reset_license()
	{
		$option_name = 'external_updates-' . $this->hc_product;
		delete_site_option( $option_name );
	}

	static function reset_license_code( $product )
	{
		$option_name = 'external_updates-' . $product;
		delete_site_option( $option_name );
	}

	function license_details( $plugin_file, $plugin_data, $status )
	{
		if( ! current_user_can('update_plugins') )
			return;

		$license_code = $this->get_license();
		$download_url = '';
		if( $license_code )
		{
			$state = $this->checker->getUpdateState();
			$notice = isset($state->update->upgrade_notice) ? $state->update->upgrade_notice : '';
			$download_url = isset($state->update->download_url) ? $state->update->download_url : '';

			if( $notice && (! $download_url) )
			{
//				$notice = 'License error: ' . $notice;
			}
		}
		else
		{
			$notice = 'License is not set yet. ';
		}

		if( $notice )
		{
			$url = $this->get_license_link();
			$license_link = '<a href="' . $url . '">' . 'Enter license code to enable automatic updates' . '</a>';

			$return = array();
			$return[] = '<tr class="plugin-update-tr">';
			$return[] = '<td colspan="3" style="padding: 5px 0 0 0; border: 0;">';

			$return[] = '<style>';
			$return[] = '.plugin-update-tr .update-message.hitcode-license-status-ok {background-color: #efb;}';
			// $return[] = '.plugin-update-tr .update-message.hitcode-license-status-ok:before {content: \'\\f147\'; color: #060;}';
			$return[] = '.plugin-update-tr .update-message.hitcode-license-status-error {background-color: #fdd;}';
			// $return[] = '.plugin-update-tr .update-message.hitcode-license-status-error:before {content: \'\\f160\'; color: #600;}';
			$return[] = '</style>';

			if( $download_url ){
				$return[] = '<div class="update-message notice inline notice-warning notice-alt hitcode-license-status-ok"><p>';
			}
			else {
				$return[] = '<div class="update-message notice inline notice-error notice-alt hitcode-license-status-error"><p>';
			}

			$return[] = $notice;

			if( ! $license_code )
			{
				if( current_user_can('update_plugins') )
				{
					$return[] = ' ' . $license_link;
//					$return[] = '<br>';
					$return[] = ' ';
				}
			}
			$return[] = '</div>';

			$return[] = '</td>';
			$return[] = '</tr>';
			$return = join( '', $return );
			echo $return;
		}
	}

	public function get_license()
	{
		$return = '';

		switch( $this->my_type )
		{
			case 'wp':
				$option_name = $this->app . '_license_code';
				$return = get_site_option( $option_name );
				break;

			case 'own':
				global $wpdb;
				$db_prefix = $GLOBALS['NTS_CONFIG'][$this->app]['DB_TABLES_PREFIX'];
				$return = NULL;

				switch( $this->system_type )
				{
					case 'ci':
						$mytable = $db_prefix . 'conf';
						$sql = "SELECT value FROM $mytable WHERE name='license_code'";
						$return = $wpdb->get_var( $sql );
						break;

					case 'nts':
						$mytable = $db_prefix . 'conf';
						$sql = "SELECT value FROM $mytable WHERE name='licenseCode'";
						$return = $wpdb->get_var( $sql );
						break;
				}
				break;
		}

		return $return;
	}

	public function get_license_link()
	{
		switch( $this->my_type )
		{
			case 'wp':
				$license_url = $this->slug . '-license';
				break;

			case 'own':
				switch( $this->system_type )
				{
					case 'ci':
						$license_url = $this->slug . '&/license/admin';
						break;

					case 'nts':
						$license_url = $this->slug . '&nts-panel=admin/conf/upgrade';
						break;
				}
				break;
		}

		if( is_multisite() )
		{
			$return = add_query_arg( 
				array(
					'page' => $license_url,
					),
				network_admin_url('admin.php')
				);
		}
		else
		{
			$return = add_query_arg( 
				array(
					'page' => $license_url,
					),
				admin_url('admin.php')
				);
		}
		return $return;
	}

	public function license_link( $links )
	{
		$url = $this->get_license_link();

		$link_title = __( 'License Code' );
		switch( $this->my_type )
		{
			case 'wp':
				$link_title = __( 'License & Options' );
				break;
			case 'own':
				$link_title = __( 'License Code' );
				break;
		}

		$license_link = '<a href="' . $url . '">' . $link_title . '</a>';
		array_unshift( $links, $license_link );
		return $links;
	}

	/* these ones add functionality for the main plugin class */
	public function admin_total_init()
	{
		register_setting( $this->app, $this->app . '_license_code' );
		register_setting( $this->app, $this->app . '_menu_title' );
	}

	public function admin_submenu()
	{
		$page = add_submenu_page(
			NULL,
			'',
			'',
			'update_plugins',
			$this->slug . '-license',
			array( $this, 'dev_options' )
			);
	}

	static function uninstall( $prefix )
	{
		$current = array();
		$current['license_code'] = get_site_option( $prefix . '_license_code', '' );
		$installation_id = '';

	// delete options
		delete_site_option( $prefix . '_license_code' );
		delete_site_option( $prefix . '_menu_title' );

	// deregister license code
		$my_url = get_admin_url() . 'admin.php?page=' . $prefix;
	// strip http
		$my_url = preg_replace('#^https?://#', '', $my_url);

		$check_license_url = $this->license_server . 'lic.php';

		$check_url = 
			$check_license_url . 
			'?action=dereg' . 
			'&code=' . $current['license_code'] . 
			'&iid=' . $installation_id . 
			'&url=' . urlencode($my_url);

		wp_remote_get(
			$check_url,
			array(
				'timeout'	=> 5,
				)
			);
	}

	public function dev_options()
	{
		$current = array();
		$current['license_code'] = get_site_option( $this->app . '_license_code', '' );
		$current['menu_title'] = get_site_option( $this->app . '_menu_title', ucfirst($this->app) );
		$current['share_database'] = get_site_option( $this->app . '_share_database', 0 );

		$my_url = get_admin_url() . 'admin.php?page=' . $this->slug;
	// strip http
		$my_url = preg_replace('#^https?://#', '', $my_url);
		$installation_id = '';

		$plugin_data = get_plugin_data( $this->full_path );
		$installed_version = $plugin_data['Version'];

		$check_license_url = $this->license_server . 'lic.php';

		if( isset($_POST[$this->app . '_submit']) ){
			if( isset($_POST[$this->app]) ){
				// foreach( (array)$_POST[$this->app] as $key => $value ){
				$supplied = (array)$_POST[$this->app];
				foreach( array_keys($current) as $key ){
					$option_name = $this->app . '_' . $key;
					if( array_key_exists($key, $supplied) ){
						$value = $supplied[$key];
						update_site_option( $option_name, $value );
					}
					elseif( $key == 'share_database' ){
						$value = 0;
						update_site_option( $option_name, $value );
					}
				}
				$current['license_code'] = get_site_option( $this->app . '_license_code', '' );
				$current['menu_title'] = get_site_option( $this->app . '_menu_title', ucfirst($this->app) );
				$current['share_database'] = get_site_option( $this->app . '_share_database', 0 );
			}
		}

		$check_url = 
			$check_license_url . 
			'?code=' . $current['license_code'] . 
			'&iid=' . $installation_id . 
			'&ver=' . $installed_version . 
			'&prd=' . urlencode($this->hc_product) . 
			'&url=' . urlencode($my_url);
//		echo '<br><br>check url = "' . $check_url . '"<br>';

		// spaghetti starts here
?>

<div class="wrap">
<h2><?php echo ucfirst($this->app); ?> Options</h2>

<?php if( isset($_POST[$this->app . '_submit']) ) : ?>
	<div id="message" class="updated fade">
		<p>
			<?php _e( 'Settings Saved', 'my' ) ?>
		</p>
	</div>
<?php endif; ?>

<form method="post" action="">
	<?php settings_fields( $this->app ); ?>
	<?php //do_settings_sections( $this->app ); ?>
	<table class="form-table">
		<tr valign="top">
		<th scope="row">Menu Title</th>
		<td><input type="text" name="<?php echo $this->app; ?>[menu_title]" value="<?php echo esc_attr( $current['menu_title'] ); ?>" /></td>
		</tr>

		<tr valign="top">
		<th scope="row">License Code</th>
		<td>
			<input type="text" name="<?php echo $this->app; ?>[license_code]" value="<?php echo esc_attr( $current['license_code'] ); ?>" />
			<?php if( strlen($current['license_code']) ) : ?>
				<div style="margin: 1em 0;" id="hc-license-status">
					Checking license ...
				</div>
			<?php endif; ?>
		</td>
		</tr>

		<?php if( is_multisite() ) : ?>
			<tr valign="top">
			<th scope="row">Common Database For All Sites</th>
			<td>
				<input type="checkbox" name="<?php echo $this->app; ?>[share_database]" value="1" <?php if( $current['share_database'] ){echo 'checked';} ?> />
			</td>
			</tr>
		<?php endif; ?>

		<tr valign="top">
		<th scope="row">&nbsp;</th>
		<td>
			<input name="<?php echo $this->app; ?>_submit" type="submit" class="button-primary" value="Save" />
		</td>
		</tr>

	</table>
</form>
</div>

<?php if( strlen($current['license_code']) ) : ?>
<script>
jQuery(document).ready( function()
{
	jQuery.getScript( "<?php echo $check_url; ?>" )
		.done( function(script, textStatus)
		{
			var display_this = "<div class=\"";
//			display_this += ntsLicenseStatus ? "hitcode-license-status-ok" : "hitcode-license-status-error";
			display_this += "\">";
			display_this += ntsLicenseText;
			display_this += "</div>";

			jQuery('#hc-license-status').html( display_this );
		})
		.fail( function(jqxhr, settings, exception)
		{
			alert( "Error connecting to the license server" );
		});
});
</script>
<?php endif; ?>

<?php
	}
}
}
?>