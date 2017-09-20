<?php if (! defined('ABSPATH')) exit; // Exit if accessed directly
class Conf_Update_Controller_HC_MVC extends _HC_MVC
{
	function execute( $form )
	{
		$post = $this->make('/input/lib')->post();
		if( ! $post ){
			return;
		}

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

	/* run */
		$return = $this->make('/conf/commands/update')
			->execute( $values )
			;

		if( isset($return['errors']) ){
			$form_errors = array(
				$form->slug()	=> $return['errors']
				);
			$form_values = array(
				$form->slug()	=> $form->values()
				);
			$session = $this->make('/session/lib')
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
			->to('-referrer-')
			->href()
			;

		return $this->make('/http/view/response')
			->set_redirect($redirect_to) 
			;
	}
}