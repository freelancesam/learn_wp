<?php if (! defined('ABSPATH')) exit; // Exit if accessed directly
class Front_Conf_Map_View_LC_HC_MVC extends _HC_MVC
{
	public function render()
	{
		$form = $this->app->make('/front.conf/map/form');
		$to = '/front.conf/map/update';

		$values = $this->app->make('/app/settings')
			->get()
			;

		$form
			->set_values( $values )
			;

		$link = $this->make('/html/view/link')
			->to($to)
			->href()
			;

		$display_form = $this->make('/html/view/form')
			->add_attr('action', $link )
			->set_form( $form )
			;

		$display_form
			->add( $form->render() )
			;

		if( ! $form->readonly() ){
			$buttons = $this->make('/html/view/buttons-row');
			$buttons->add(
				'save',
				$this->make('/html/view/element')->tag('input')
					->add_attr('type', 'submit')
					->add_attr('title', HCM::__('Save') )
					->add_attr('value', HCM::__('Save') )
					->add_attr('class', 'hc-theme-btn-submit', 'hc-theme-btn-primary')
				);
			$display_form->add( $buttons );
		}

		return $display_form;
	}
}