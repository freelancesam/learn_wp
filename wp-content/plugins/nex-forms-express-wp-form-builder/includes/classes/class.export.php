<?php
if ( ! defined( 'ABSPATH' ) ) exit;
if(!class_exists('NF5_Export_Forms'))
	{
	class NF5_Export_Forms
		{
		/**
		* Constructor
		*/
		public function __construct(){
			$export_form = isset($_REQUEST['export_form']) ? $_REQUEST['export_form'] : '';
			if($export_form)
				{
				$form_export = $this->generate_form();
				
				header("Pragma: public");
				header("Expires: 0");
				header("Cache-Control: must-revalidate, post-check=0, pre-check=0");
				header("Cache-Control: private", false);
				header("content-type:application/csv;charset=UTF-8");
				header("Content-Disposition: attachment; filename=\"".NF5_Database_Actions::get_title(filter_var($_REQUEST['nex_forms_Id'],FILTER_SANITIZE_NUMBER_INT),'wap_nex_forms').".txt\";" );
				header("Content-Transfer-Encoding: base64");
				//echo "\xEF\xBB\xBF";
				echo $form_export;
				exit;
				}
			
		}
		
		
		/**
		* Converting data to HTML
		*/
		public function generate_form(){
			global $wpdb;
			
				$form_data = $wpdb->get_row('SELECT * FROM '.$wpdb->prefix.'wap_nex_forms WHERE Id = '.filter_var($_REQUEST['nex_forms_Id'],FILTER_SANITIZE_NUMBER_INT).'');
				//$content = str_replace('\\','',$form_data->form_fields);
			
				$fields 	= $wpdb->get_results("SHOW FIELDS FROM " . $wpdb->prefix ."wap_nex_forms");
				$field_array = array();
				$count_fields = count($fields);
				$i = 0;
				$content .= '(';
				foreach($fields as $field)
					{
					$content .= '`'.$field->Field.'`'.(($i<$count_fields-1) ? ',' : '').'';
					 $my_fields[$field->Field]=$field->Field;
					 $i++;
					}
				$content .= ') VALUES (';
				
				$j = 0;
				
				
				foreach($my_fields as $my_field)
					{
					if($my_field=='Id')
						$content .= 'NULL'.(($j<$count_fields-1) ? ',' : '').'';
					else
						$content .= '\''.str_replace('\\','',str_replace('\'','',$form_data->$my_field)).'\''.(($j<$count_fields-1) ? ',' : '').'';
					$j++;
					}
				
				
				$content .= ')';
				
				return $content;
			}
		}
	}
$formExport = new NF5_Export_Forms();