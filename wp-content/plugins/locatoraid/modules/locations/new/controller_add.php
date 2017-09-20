<?php if (! defined('ABSPATH')) exit; // Exit if accessed directly
class Locations_New_Controller_Add_LC_HC_MVC extends _HC_MVC
{
	public function execute()
	{
		$post = $this->app->make('/input/lib')->post();

		$form = $this->app->make('/locations/new/form');
		$form->grab( $post );

		$valid = $form->validate();
		if( ! $valid ){
			$form_errors = array(
				$form->slug()	=> $form->errors()
				);
			$form_values = array(
				$form->slug()	=> $form->values()
				);

			$session = $this->make('/session/lib');
			$session
				->set_flashdata('form_errors', $form_errors)
				->set_flashdata('form_values', $form_values)
				;
			$redirect_to = $this->make('/html/view/link')
				->to('-referrer-')
				->href()
				;
			return $this->make('/http/view/response')
				->set_redirect($redirect_to) 
				;
		}

		$values = $form->values();

		$command = $this->make('/locations/commands/create')
			;
		$response = $command
			->execute( $values )
			;

		if( isset($response['errors']) ){
			$form_errors = array(
				$form->slug()	=> $response['errors']
				);
			$form_values = array(
				$form->slug()	=> $form->values()
				);

			$session = $this->make('/session/lib');
			$session
				->set_flashdata('form_errors', $form_errors)
				->set_flashdata('form_values', $form_values)
				;
			$redirect_to = $this->make('/html/view/link')
				->to('-referrer-')
				->href()
				;
			return $this->make('/http/view/response')
				->set_redirect($redirect_to) 
				;
		}

	// OK
		$redirect_to = $this->make('/html/view/link')
			->to('/locations/' . $response['id'] . '/edit')
			->href()
			;
		return $this->make('/http/view/response')
			->set_redirect($redirect_to) 
			;
	}
}