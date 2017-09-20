<?php if (! defined('ABSPATH')) exit; // Exit if accessed directly
class Users_Wordpress_Conf_View_HC_MVC extends _HC_MVC
{
	public function render()
	{
		$form = $this->app->make('/users.wordpress.conf/form');
		$to = '/users.wordpress.conf/update';

		$can_edit = FALSE;
		$user = $this->app->make('/auth/model/user')->get();
		if( $user->is_always_admin() ){
			$can_edit = TRUE;
		}
		if( ! $can_edit ){
			$form->set_readonly( TRUE );
		}

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