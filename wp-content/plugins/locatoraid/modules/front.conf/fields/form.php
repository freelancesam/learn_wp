<?php if (! defined('ABSPATH')) exit; // Exit if accessed directly
class Front_Conf_Fields_Form_LC_HC_MVC extends _HC_Form
{
	public function conf()
	{
		$return = array();

		$app_settings = $this->app->make('/app/settings');

		$p = $this->app->make('/locations/presenter');
		$fields = $p->fields();
		$no_label_for = array('name', 'address');

		foreach( $fields as $fn => $flabel ){
			if( ! in_array($fn, $no_label_for) ){
				$return[ 'fields:' . $fn  . ':label' ] = 
					$this->make('/form/view/text')
						->add_attr('style', 'width: 100%;')
					;
			}

			$checkboxes = array( 'use' );
			foreach( $checkboxes as $ch ){
				$this_field_pname = 'fields:' . $fn  . ':' . $ch;
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

	return $return;
	}

	public function render(){
		$out = $this->app->make('/html/view/table-responsive')
			;

		$header = array(
			'field'	=> HCM::__('Field'),
			'label'	=> HCM::__('Label'),
			'use'	=> HCM::__('Use'),
			);

		$rows = array();

		$p = $this->app->make('/locations/presenter');
		$fields = $p->fields();

		foreach( $fields as $fn => $flabel ){
			$rows[$fn] = array(
				'field'	=> $flabel,
				'label'	=> $this->render_input('fields:' . $fn  . ':label'),
				'use'	=> $this->render_input('fields:' . $fn  . ':use'),
				);
		}

		$out
			->set_header( $header )
			->set_rows( $rows )
			;

		return $out;
	}
}