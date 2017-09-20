<?php if (! defined('ABSPATH')) exit; // Exit if accessed directly
class Locations_Delete_Controller_LC_HC_MVC extends _HC_MVC
{
	public function execute( $id )
	{
		$command = $this->make('/locations/commands/delete');
		$response = $command
			->execute( $id )
			;

		if( isset($response['errors']) ){
			echo $response['errors'];
			exit;
		}

	// OK
		$redirect_to = $this->make('/html/view/link')
			->to('/locations')
			->href()
			;

		return $this->make('/http/view/response')
			->set_redirect($redirect_to) 
			;
	}
}