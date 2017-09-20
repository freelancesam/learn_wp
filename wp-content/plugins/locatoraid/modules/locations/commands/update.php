<?php if (! defined('ABSPATH')) exit; // Exit if accessed directly
class Locations_Commands_Update_LC_HC_MVC extends _HC_MVC
{
	public function execute( $id, $args = array() )
	{
		$command = $this->app->make('/commands/update')
			->set_model('/locations/model')
			->set_validator('/locations/validator')
			;
		$return = $command
			->execute( $id, $args )
			;

		$return = $this->app
			->after( $this, $return )
			;
		return $return;
	}
}