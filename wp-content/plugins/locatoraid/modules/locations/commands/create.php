<?php if (! defined('ABSPATH')) exit; // Exit if accessed directly
class Locations_Commands_Create_LC_HC_MVC extends _HC_MVC
{
	public function execute( $args = array() )
	{
		$command = $this->app->make('/commands/create')
			->set_model('/locations/model')
			->set_validator('/locations/validator')
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