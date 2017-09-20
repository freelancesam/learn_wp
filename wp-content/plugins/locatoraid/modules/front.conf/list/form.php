<?php if (! defined('ABSPATH')) exit; // Exit if accessed directly
class Front_Conf_List_Form_LC_HC_MVC extends _HC_Form
{
	public function conf()
	{
		$return = array();

		$app_settings = $this->app->make('/app/settings');

		$this_field_pname = 'front_list:advanced';
		$this_advanced = $app_settings->get($this_field_pname);

		if( $this_advanced ){
			$this_field_pname = 'front_list:template';
			$return[ $this_field_pname ] = 
				$this->make('/form/view/textarea')
					->add_attr('rows', 14)
					->add_attr('class', 'hc-block')
				;
		}
		else {
			$p = $this->app->make('/locations/presenter');
			$fields = $p->run('fields-labels');

			foreach( $fields as $fn => $flabel ){
				$checkboxes = array( 'show', 'w_label' );
				foreach( $checkboxes as $ch ){
					$this_field_pname = 'front_list:' . $fn  . ':' . $ch;
					$this_field_conf = $app_settings->get($this_field_pname);

					if( ($this_field_conf === TRUE) OR ($this_field_conf === FALSE) ){
						$return[ $this_field_pname ] = 
							$this->make('/form/view/label')
						;
					}
					else {
						$return[ $this_field_pname ] = 
							$this->make('/form/view/checkbox')
						;
					}
				}
			}
		}

		return $return;
	}

	public function render_advanced()
	{
		$out = parent::render();

		$links = $this->app->make('/html/view/list-inline')
			->set_gutter(2)
			;

		$links
			->add(
				$this->make('/html/view/link')
					->to('/front.conf/list/mode', array('to' => 'basic'))

					->add( $this->make('/html/view/icon')->icon('arrow-left') )
					->add( HCM::__('Switch To Basic Mode') )
					->add_attr('class', 'hc-theme-btn-submit')
					// ->add_attr('class', 'hc-theme-btn-secondary')
				)
			;

		$app_settings = $this->app->make('/app/settings');

		$this_field_pname = 'front_list:template';
		$this_value_modified = $app_settings->get($this_field_pname);
		$this_value_default = $this->app->make('/front/view/list/template')
			->render()
			;

		if( $this_value_modified != $this_value_default ){
			$links
				->add(
					$this->make('/html/view/link')
						->to('/front.conf/list/mode', array('to' => 'reset'))
						->add( $this->make('/html/view/icon')->icon('times') )
						->add( HCM::__('Reset Template') )
						->add_attr('class', 'hc-theme-btn-submit')
						->add_attr('class', 'hcj2-confirm')
						->add_attr('class', 'hc-theme-btn-danger')
					)
				;
		}

		$out = $this->app->make('/html/view/list')
			->set_gutter(2)
			->add( $links )
			->add( $out )
			;

		return $out;
	}

	public function render(){
		$app_settings = $this->app->make('/app/settings');

		$this_field_pname = 'front_list:advanced';
		$this_advanced = $app_settings->get($this_field_pname);

		if( $this_advanced ){
			return $this->render_advanced();
		}

		$out = $this->app->make('/html/view/table-responsive')
			;

		$header = array(
			'field'			=> HCM::__('Field'),
			'show_in_list'	=> HCM::__('Show In List'),
			'w_label'	=> HCM::__('With Label'),
			);

		$rows = array();

		$p = $this->app->make('/locations/presenter');
		$fields = $p->run('fields-labels');

		foreach( $fields as $fn => $flabel ){
			$rows[$fn] = array(
				'field'			=> $flabel,
				'show_in_list'	=> $this->render_input('front_list:' . $fn . ':show'),
				'w_label'		=> $this->render_input('front_list:' . $fn . ':w_label'),
				);
		}

		$out
			->set_header( $header )
			->set_rows( $rows )
			;

		$link_to_advanced = $this->app->make('/html/view/link')
			->to('/front.conf/list/mode', array('to' => 'advanced'))

			->add( HCM::__('Switch To Advanced Mode') )
			->add( $this->app->make('/html/view/icon')->icon('arrow-right') )
			->add_attr('class', 'hc-theme-btn-submit')
			// ->add_attr('class', 'hc-theme-btn-secondary')
			;

		$out = $this->app->make('/html/view/list')
			->set_gutter(2)
			->add( $link_to_advanced )
			->add( $out )
			;

		return $out;
	}
}