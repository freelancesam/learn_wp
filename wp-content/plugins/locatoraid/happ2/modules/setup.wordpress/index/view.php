<?php if (! defined('ABSPATH')) exit; // Exit if accessed directly
class Setup_Wordpress_Index_View_HC_MVC extends _HC_MVC
{
	public function render()
	{
		$form = $this->make('/conf/controller')
			->form('wordpress-users')
			;

		$return = $this->make('/html/view/list');

		$nts_app_title = isset($this->app->app_config['nts_app_title']) ? $this->app->app_config['nts_app_title'] : '';
		if( $nts_app_title ){
			$header1 = $this->make('/html/view/element')->tag('h1')
				->add( $nts_app_title )
				->add_attr('class', 'hc-mb2')
				;
			$return
				->add( $header1 )
				;
		}

		$header = $this->make('/html/view/element')->tag('h2')
			->add( HCM::__('Installation') )
			->add_attr('class', 'hc-mb2')
			;
		$return
			->add( $header )
			;

		$model = $this->app->make('/setup/model');
		$old_version = $model->get_old_version();

		if( $old_version ){
			$link = $this->make('/http/lib/uri')->url('setup/upgrade');
			$return->add(
				$this->make('/html/view/element')->tag('a')
					->add_attr('href', $link)
					->add('You seem to have an older version already installed. Please click here to upgrade.')
				);
			$return->add(
				'Or continue below to install from scratch.'
				);
		}

		$link = $this->make('/http/lib/uri')->url('setup/run');

		$display_form = $this->make('/html/view/form')
			->add_attr('action', $link )
			->set_form( $form )
			;

		$label = $this->app->make('/html/view/element')->tag('h4')
			->add_attr('class', 'hc-mb2', 'hc-mt2')
			->add( HCM::__('Please define which WordPress user roles will be able to access the plugin.') )
			;

		$display_form
			->add( $label )
			;

		$inputs = $form->inputs();
		foreach( $inputs as $input_name => $input ){
			$row = $this->make('/html/view/label-input')
				->set_label( $input->label() )
				->set_content( $input )
				->set_error( $input->error() )
				;
			$display_form
				->add( $row )
				;
		}

		$buttons = $this->make('/html/view/buttons-row')
			;

		$buttons->add(
			$this->make('/html/view/element')->tag('input')
				->add_attr('type', 'submit')
				->add_attr('title', HCM::__('Click To Proceed') )
				->add_attr('value', HCM::__('Click To Proceed') )
				->add_attr('class', 'hc-theme-btn-submit', 'hc-theme-btn-primary')
			);
		$display_form->add( $buttons );

		$return
			->add( $display_form )
			;

		return $return;
	}
}