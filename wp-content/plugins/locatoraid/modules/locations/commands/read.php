<?php if (! defined('ABSPATH')) exit; // Exit if accessed directly
class Locations_Commands_Read_LC_HC_MVC extends _HC_MVC
{
	public function execute( $args = array() )
	{
		$args = func_get_args();

		$command = $this->make('/commands/read')
			->set_model('/locations/model')
			;

		$return = $command
			->execute( $args )
			;

		$return = $this->app
			->after( $this, $return )
			;

		return $return;
	}
}