<?php if (! defined('ABSPATH')) exit; // Exit if accessed directly
class Setup_Model_HC_MVC extends _HC_MVC
{
	public function get_old_db()
	{
		$old_version = $this->get_old_version();
		$old_db = $this->app->db_copy();

		$old_db_params = $old_db->params();
		$dbprefix_version = isset($this->app->app_config['dbprefix_version']) ? $this->app->app_config['dbprefix_version'] : '';

		$core_dbprefix = substr( $old_db_params['dbprefix'], 0, -strlen($dbprefix_version)-1 );
		$old_prefix = strlen($old_version) ? $core_dbprefix . $old_version . '_' :  $core_dbprefix;

		$old_db_params['dbprefix'] = $old_prefix;
		$old_db->set_params( $old_db_params );

		return $old_db;
	}

	public function get_old_version()
	{
		$return = NULL;

		$dbprefix_version = isset($this->app->app_config['dbprefix_version']) ? $this->app->app_config['dbprefix_version'] : '';
		$db_params = $this->app->db_params();

		if( strlen($dbprefix_version) ){
			$core_dbprefix = substr( $db_params['dbprefix'], 0, -strlen($dbprefix_version)-1 );
			$old_prefixes = array();

			$my_version = substr($dbprefix_version, 1);
			$old_version = $my_version - 1;
			while( $old_version >= 1 ){
				$old_prefixes[] = 'v' . $old_version;
				$old_version--;
			}
			$old_prefixes[] = '';

			foreach( $old_prefixes as $op ){
				$test_prefix = strlen($op) ? $core_dbprefix . $op . '_' :  $core_dbprefix;

				$db = $this->app->db_copy();
				$db_params = $db->params();
				$db_params['dbprefix'] = $test_prefix;
				$db->set_params( $db_params );
	
				if( $db->table_exists('conf') ){
					$return = $op;
					break;
				}
			}
		}
		return $return;
	}
}