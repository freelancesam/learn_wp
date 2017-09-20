<?php if (! defined('ABSPATH')) exit; // Exit if accessed directly
abstract class _HC_MVC
{
	public $app = NULL;
	public $slug = NULL;

	public function __toString()
	{
		return '' . $this->run('render');
	}

	public function slug()
	{
		return $this->slug;
	}

	public function make( $slug )
	{
		$args = func_get_args();
		$slug = $args[0];

		if( substr($slug, 0, 1) != '/' ){
		// append this module path
			$module = $this->slug;
			$module = trim($module, '/');
			$module = explode('/', $module);
			$module = array_shift( $module );

			$slug = '/' . $module . '/' . $slug;
		}

		if( count($args) > 1 ){
			$args[0] = $slug;
			$return = call_user_func_array( array($this->app, 'make'), $args );
		}
		else {
			$return = $this->app->make( $slug );
		}

		return $return;
	}

	public function run()
	{
		$args = func_get_args();
		$method = array_shift( $args );
		return $this->app->run( $this, $method, $args );
	}

	public function __call( $what, $args )
	{
		$msg = "METHOD NOT DEFINED: '" . get_class($this) . '@' . $what . "'<br>";
		trigger_error($msg, E_USER_ERROR);
	}
}