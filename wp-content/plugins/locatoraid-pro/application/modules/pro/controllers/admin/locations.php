<?php if (!defined('BASEPATH')) exit('No direct script access allowed');

if ( !function_exists('str_getcsv')) {
    function str_getcsv($input, $delimiter = ',', $enclosure = '"', $escape = '\\', $eol = '\n') {
        if (is_string($input) && !empty($input)) {
            $output = array();
            $tmp    = preg_split("/".$eol."/",$input);
            if (is_array($tmp) && !empty($tmp)) {
                while (list($line_num, $line) = each($tmp)) {
                    if (preg_match("/".$escape.$enclosure."/",$line)) {
                        while ($strlen = strlen($line)) {
                            $pos_delimiter       = strpos($line,$delimiter);
                            $pos_enclosure_start = strpos($line,$enclosure);
                            if (
                                is_int($pos_delimiter) && is_int($pos_enclosure_start)
                                && ($pos_enclosure_start < $pos_delimiter)
                                ) {
                                $enclosed_str = substr($line,1);
                                $pos_enclosure_end = strpos($enclosed_str,$enclosure);
                                $enclosed_str = substr($enclosed_str,0,$pos_enclosure_end);
                                $output[$line_num][] = $enclosed_str;
                                $offset = $pos_enclosure_end+3;
                            } else {
                                if (empty($pos_delimiter) && empty($pos_enclosure_start)) {
                                    $output[$line_num][] = substr($line,0);
                                    $offset = strlen($line);
                                } else {
                                    $output[$line_num][] = substr($line,0,$pos_delimiter);
                                    $offset = (
                                                !empty($pos_enclosure_start)
                                                && ($pos_enclosure_start < $pos_delimiter)
                                                )
                                                ?$pos_enclosure_start
                                                :$pos_delimiter+1;
                                }
                            }
                            $line = substr($line,$offset);
                        }
                    } else {
                        $line = preg_split("/".$delimiter."/",$line);
   
                        /*
                         * Validating against pesky extra line breaks creating false rows.
                         */
                        if (is_array($line) && !empty($line[0])) {
                            $output[$line_num] = $line;
                        } 
                    }
                }
				$output = array_shift( $output );
                return $output;
            } else {
                return false;
            }
        } else {
            return false;
        }
    }
} 

function lpr_seems_utf8( $str )
{
	$length = strlen($str);
	for ($i=0; $i < $length; $i++) {
		$c = ord($str[$i]);
		if ($c < 0x80) $n = 0; # 0bbbbbbb
		elseif (($c & 0xE0) == 0xC0) $n=1; # 110bbbbb
		elseif (($c & 0xF0) == 0xE0) $n=2; # 1110bbbb
		elseif (($c & 0xF8) == 0xF0) $n=3; # 11110bbb
		elseif (($c & 0xFC) == 0xF8) $n=4; # 111110bb
		elseif (($c & 0xFE) == 0xFC) $n=5; # 1111110b
		else return false; # Does not match any model
		for ($j=0; $j<$n; $j++) { # n bytes matching 10bbbbbb follow ?
			if ((++$i == $length) || ((ord($str[$i]) & 0xC0) != 0x80))
				return false;
			}
		}
	return true;
}

class Locations extends Admin_controller_crud
{
	function __construct()
	{
		ini_set( 'auto_detect_line_endings', TRUE );

		$this->conf = array(
			'model'			=> 'Location_model',
			'path'			=> 'pro/admin/locations',
			'path-add'		=> 'admin/locations/add',
			'validation'	=> 'location',
			'export'		=> 'locations',
			);
		parent::__construct();

		$this->per_page = 12;
		$this->fields = $this->model->get_fields();
	}

	protected function _prepare_export()
	{
		$separator = $this->app_conf->get( 'csv_separator' );

	// header
		$headers = array();
		reset( $this->fields );
		foreach( $this->fields as $f )
		{
			$headers[ $f['name'] ] = $f['name'];
		}
		$headers[ 'latitude' ] = 'latitude';
		$headers[ 'longitude' ] = 'longitude';

	// entries
		$entries = $this->model->get_all( array_keys($headers) );
		unset( $headers['products'] );

	// check products
		$headers_products = array();
		for( $ii = 0; $ii < count($entries); $ii++ )
		{
			if( strlen($entries[$ii]['products']) )
			{
				$product_values = explode( ',', $entries[$ii]['products'] );
				$product_values = array_map( 'trim', $product_values );

				reset( $product_values );
				foreach( $product_values as $pn )
				{
					$header_name = 'product:' . $pn;
					$headers[$header_name] = $header_name;
					$headers_products[$header_name] = 1;
				}
			}
		}
		$headers_products = array_keys($headers_products);

	/* process entries */
		$product_func = create_function( '$e', 'return "product:" . trim($e);');
		for( $ii = 0; $ii < count($entries); $ii++ )
		{
			if( strlen($entries[$ii]['products']) )
			{
				$product_values = explode( ',', $entries[$ii]['products'] );
				$product_values = array_map( $product_func, $product_values );

				reset( $headers_products );
				foreach( $headers_products as $header_name )
				{
					if( in_array($header_name, $product_values) )
						$v = 'x';
					else
						$v = '';
					$entries[$ii][$header_name] = $v;
				}
			}
			unset( $entries[$ii]['products'] );
		}

		$data = array();
		$data[] = join( $separator, array_keys($headers) );
		for( $ii = 0; $ii < count($entries); $ii++ )
		{
			$data[] = hc_build_csv( array_values($entries[$ii]), $separator );
		}
		return $data;
	}

	function import( $do = FALSE )
	{
		$separator = $this->app_conf->get( 'csv_separator' );

		$this->data['include'] = $this->conf['path'] . '/import';
		$this->data['error'] = '';
		$this->data['message'] = array();

		$this->data['mandatory_fields'] = array();
		$this->data['other_fields'] = array();
		foreach( $this->fields as $f )
		{
			if( isset($f['required']) && $f['required'] )
				$this->data['mandatory_fields'][] = $f['name'];
			else
			{
				if( $f['name'] != 'products' )
				{
					$this->data['other_fields'][] = $f['name'];
				}
			}
		}
		$this->data['other_fields'][] = 'latitude';
		$this->data['other_fields'][] = 'longitude';

		$my_fields = array_merge( $this->data['mandatory_fields'], $this->data['other_fields'] );

		if( $do )
		{
			$mode = $this->input->post( 'mode' );
			$checkduplicates = $this->input->post( 'checkduplicates' ) ? TRUE : FALSE;

			// upload
			$file_name = 'userfile';
			if( isset($_FILES[$file_name]) && is_uploaded_file($_FILES[$file_name]['tmp_name']) )
			{
				$tmp_name = $_FILES[$file_name]['tmp_name'];
				$data = array();

				$parse_error = FALSE;
				if( ($handle = fopen($tmp_name, "r")) !== FALSE)
				{
					$line_no = 0;
					setlocale(LC_ALL, "en_US.UTF-8");
					while( ($line = fgetcsv($handle, 10000, $separator)) !== FALSE )
					{
					// while( ($string = fgets($handle, 1000)) !== FALSE )
					// {
						// $line = str_getcsv( $string, $separator );
						if( count($line) <= 1 ){
							continue;
						}

					// titles
						if( ! $line_no )
						{
							$prop_names = $line;
							for( $ii = 0; $ii < count($prop_names); $ii++ )
							{
								reset( $this->fields );
								foreach( $this->fields as $f )
								{
									$this_pname = $prop_names[$ii];

									if( ! $ii ){
										//check BOM for first line
										$bom = pack("CCC", 0xef, 0xbb, 0xbf);
										if( 0 == strncmp($this_pname, $bom, 3) ){
											// BOM detected
											$this_pname = substr($this_pname, 3);
										}
									}

									$this_pname = preg_replace('/[\x00-\x1F\x80-\xFF]/', '', $this_pname);
									$this_pname = trim( $this_pname );
									$this_pname = strtolower( $this_pname );

									if( $this_pname == $f['name'] )
									{
										$prop_names[$ii] = $this_pname;
									}
								}
							}

//							$prop_names = array_map( 'strtolower', $prop_names );
							$prop_count = count( $prop_names );

						// check for mandatory fields
							$missing_fields = array();
							reset( $this->fields );
							foreach( $this->fields as $f )
							{
								if( isset($f['required']) && $f['required'] ){
									if( ! in_array($f['name'], $prop_names) ){
										$missing_fields[] = $f['name'];
										}
									}
							}
							if( $missing_fields )
							{
								$this->data['error'] = lang('location_import_error_fields_missing') . ': ' . join( ', ', $missing_fields );
								$parse_error = TRUE;
								break;
							}

						// check if any fields are not parsed
						// also check glued fields
							$glued_fields = array();
							$not_parsed_fields = array();
							reset( $prop_names );
							foreach( $prop_names as $f )
							{
								$f = trim( $f );
								if( ! $f )
									continue;
								if( ! in_array($f, $my_fields) )
								{
									if( preg_match('/^product\:(.+)$/', $f, $ma)  )
									{
										$to_glue = 'products';
										$value = $ma[1];
										$glued_fields[ $f ] = array( $to_glue, $value );
									}
									else
									{
										if( ! in_array(strtolower($f), $my_fields) )
											$not_parsed_fields[] = $f;
									}
								}
							}
							if( $not_parsed_fields )
							{
								$this->data['message'][] = lang('location_import_message_fields_not_recognized') . ': ' . join( ', ', $not_parsed_fields );
							}
						}
						else
						{
							$values = array();
							for( $i = 0; $i < $prop_count; $i++ )
							{
								$check_name = strtolower($prop_names[$i]);
//								$check_name = $prop_names[$i];
								if( in_array($check_name, $my_fields) )
								{
									if( isset($line[$i]) )
										$values[ $check_name ] = $line[$i];
									else
										$values[ $check_name ] = '';
								}
								elseif( isset($glued_fields[$prop_names[$i]]) )
								{
									if( isset($line[$i]) && strlen($line[$i]) )
									{
										$real_name = $glued_fields[$prop_names[$i]][0];
										$value = $glued_fields[$prop_names[$i]][1];
										if( ! isset($values[$real_name]) )
											$values[ $real_name ] = array();
										$values[ $real_name ][] = $value;
									}
								}
							}

						// glue glued fields
							reset( $glued_fields );
							foreach( array_keys($glued_fields) as $gf )
							{
								$real_name = $glued_fields[$gf][0];
								if( isset($values[$real_name]) && is_array($values[$real_name]) ){
									$values[$real_name] = implode( ', ', $values[$real_name] );
									}
							}

						/* convert to UTF */
// _print_r( $values );
							$keys = array_keys($values);
							foreach( $keys as $k ){
								if( ! lpr_seems_utf8($values[$k]) ){
									$values[$k] = utf8_encode($values[$k]);
								}
							}

						/* additionally check required fields */
							$ok = TRUE;
							reset( $this->data['mandatory_fields'] );
							foreach( $this->data['mandatory_fields'] as $mf ){
								if( ! strlen(trim($values[$mf])) ){
									if( $mf == 'street1' ){
										// check street2
										if( isset($values['street2']) ){
											if( $street2 = strlen(trim($values['street2'])) ){
												$values['street1'] = $values['street2'];
												$values['street2'] = '';
											}
										}
										continue;
									}
// echo "SKIP AS DATA MISSING FOR FIELD '$mf' FOR LINE " . join(',', $line) . "<br>";
// _print_r( $values );
									$ok = FALSE;
									break;
								}
							}
							if( $ok ){
								$data[] = $values;
							}
						}
						$line_no++;
					}
				fclose($handle);
				}

// echo "COUNT: " . count($data);
// _print_r( $data );
// exit;

				if( ! $parse_error )
				{
					// finally import
					$loaded = 0;
					if( $mode != 'append' )
					{
						$this->app_conf->set( 'products', '' );
						$this->model->delete_all();
					}

					reset( $data );
					foreach( $data as $object ){
						if( $this->model->save($object, FALSE, $checkduplicates) ){
							$loaded++;
						}
					}
					if( $loaded )
						$this->data['message'][] = lang('location_import_ok') . ': ' . $loaded;

				// redirect
					$this->session->set_flashdata( 'message', $this->data['message'] );
					ci_redirect( 'admin/locations' );
					exit;
				}
			}
			else
			{
				$this->data['error'] = lang('common_upload_error');
			}
		}
		$this->load->view( $this->template, $this->data);
	}
}

/* End of file customers.php */
/* Location: ./application/controllers/admin/categories.php */