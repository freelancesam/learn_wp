<?php
if ( ! defined( 'ABSPATH' ) ) exit;
add_action( 'wp_ajax_do_upload_image', array('NF5_Functions','do_upload_image'));

if(!class_exists('NF5_Functions'))
	{
	class NF5_Functions{
		
		public function format_date($str){
			$datetime = explode(' ',$str);
			$time = explode(':',$datetime[1]);
			$date = explode('/',$datetime[0]);
			return date(get_option('date_format'),mktime('0','0','0',$date[0],$date[1],$date[2]));
		}
		
		public function format_name($str){
			
			$str = trim($str);
			$str = strtolower($str);		
			$str = str_replace('  ',' ',$str);
			$str = str_replace(' ','_',$str);
			$str = str_replace('{{','',$str);
			$str = str_replace('}}','',$str);
			
			if($str=='name')
				$str = '_'.$str;
			
			return trim($str);
		}
		
		public function unformat_name($str){
			
			$str = NF5_Functions::format_name($str);
			
			$str = str_replace('u2019','\'',$str);
			$str = str_replace('_',' ',$str);
			$str = ucfirst($str);
			return trim($str);
		}
			
		
		public function get_file_headers($file){
				
			$default_headers = array(			
				'Module Name' 		=> 'Module Name',
				'For Plugin' 		=> 'For Plugin',
				'Module Prefix'		=> 'Module Prefix',
				'Module URI' 		=> 'Module URI',
				'Module Scope' 		=> 'Module Scope',
				
				'Plugin Name' 		=> 'Plugin Name',
				'Plugin TinyMCE' 	=> 'Plugin TinyMCE',
				'Plugin Prefix'		=> 'Plugin Prefix',
				'Plugin URI' 		=> 'Plugin URI',
				'Module Ready' 		=> 'Module Ready',
				
				'Version' 			=> 'Version',
				'Description' 		=> 'Description',
				'Author' 			=> 'Author',
				'AuthorURI' 		=> 'Author URI'
			);
			return get_file_data($file,$default_headers,'module');
		}
		
		public function do_upload_image() {

			foreach($_FILES as $key=>$file)
				{
				$uploadedfile = $_FILES[$key];
				$upload_overrides = array( 'test_form' => false );
				$movefile = wp_handle_upload( $uploadedfile, $upload_overrides );
				//
				if ( $movefile )
					{
					//echo "File is valid, and was successfully uploaded.\n";
					if($movefile['file'])
						{
						$set_file_name = str_replace(ABSPATH,'',$movefile['file']);
						$_POST['image_path'] = $movefile['url'];
						$_POST['image_name'] = $file['name'];
						$_POST['image_size'] = $file['size'];
						echo $movefile['url'];
						}
					} 
				}
			
			die();
		}
	
	public function view_excerpt($content,$chars=0){
			$content = strip_tags($content);
			for($i=0;$i<$chars;$i++){
				$excerpt .= substr($content,$i,1);
			}
			return (strlen($content)>$chars) ? $excerpt.'&hellip;' : $excerpt;
		}
	
	}
}


if(!class_exists('NEXForms_Functions'))
	{
	class NEXForms_Functions{
		
	
	public function code_to_country( $code, $get_list=false ){

    $code = strtoupper($code);

    $countryList = array(
        'AF' => 'Afghanistan',
        'AX' => 'Aland Islands',
        'AL' => 'Albania',
        'DZ' => 'Algeria',
        'AS' => 'American Samoa',
        'AD' => 'Andorra',
        'AO' => 'Angola',
        'AI' => 'Anguilla',
        'AQ' => 'Antarctica',
        'AG' => 'Antigua and Barbuda',
        'AR' => 'Argentina',
        'AM' => 'Armenia',
        'AW' => 'Aruba',
        'AU' => 'Australia',
        'AT' => 'Austria',
        'AZ' => 'Azerbaijan',
        'BS' => 'Bahamas the',
        'BH' => 'Bahrain',
        'BD' => 'Bangladesh',
        'BB' => 'Barbados',
        'BY' => 'Belarus',
        'BE' => 'Belgium',
        'BZ' => 'Belize',
        'BJ' => 'Benin',
        'BM' => 'Bermuda',
        'BT' => 'Bhutan',
        'BO' => 'Bolivia',
        'BA' => 'Bosnia and Herzegovina',
        'BW' => 'Botswana',
        'BV' => 'Bouvet Island (Bouvetoya)',
        'BR' => 'Brazil',
        'IO' => 'British Indian Ocean Territory (Chagos Archipelago)',
        'VG' => 'British Virgin Islands',
        'BN' => 'Brunei Darussalam',
        'BG' => 'Bulgaria',
        'BF' => 'Burkina Faso',
        'BI' => 'Burundi',
        'KH' => 'Cambodia',
        'CM' => 'Cameroon',
        'CA' => 'Canada',
        'CV' => 'Cape Verde',
        'KY' => 'Cayman Islands',
        'CF' => 'Central African Republic',
        'TD' => 'Chad',
        'CL' => 'Chile',
        'CN' => 'China',
        'CX' => 'Christmas Island',
        'CC' => 'Cocos (Keeling) Islands',
        'CO' => 'Colombia',
        'KM' => 'Comoros the',
        'CD' => 'Congo - Kinshasa',
        'CG' => 'Congo - Brazzaville',
        'CK' => 'Cook Islands',
        'CR' => 'Costa Rica',
        'CI' => "CI",
        'HR' => 'Croatia',
        'CU' => 'Cuba',
        'CY' => 'Cyprus',
        'CZ' => 'Czech Republic',
        'DK' => 'Denmark',
        'DJ' => 'Djibouti',
        'DM' => 'Dominica',
        'DO' => 'Dominican Republic',
        'EC' => 'Ecuador',
        'EG' => 'Egypt',
        'SV' => 'El Salvador',
        'GQ' => 'Equatorial Guinea',
        'ER' => 'Eritrea',
        'EE' => 'Estonia',
        'ET' => 'Ethiopia',
        'FO' => 'Faroe Islands',
        'FK' => 'Falkland Islands (Malvinas)',
        'FJ' => 'Fiji the Fiji Islands',
        'FI' => 'Finland',
        'FR' => 'France',
        'GF' => 'French Guiana',
        'PF' => 'French Polynesia',
        'TF' => 'French Southern Territories',
        'GA' => 'Gabon',
        'GM' => 'Gambia the',
        'GE' => 'Georgia',
        'DE' => 'Germany',
        'GH' => 'Ghana',
        'GI' => 'Gibraltar',
        'GR' => 'Greece',
        'GL' => 'Greenland',
        'GD' => 'Grenada',
        'GP' => 'Guadeloupe',
        'GU' => 'Guam',
        'GT' => 'Guatemala',
        'GG' => 'Guernsey',
        'GN' => 'Guinea',
        'GW' => 'Guinea-Bissau',
        'GY' => 'Guyana',
        'HT' => 'Haiti',
        'HM' => 'Heard Island and McDonald Islands',
        'VA' => 'Holy See (Vatican City State)',
        'HN' => 'Honduras',
        'HK' => 'Hong Kong',
        'HU' => 'Hungary',
        'IS' => 'Iceland',
        'IN' => 'India',
        'ID' => 'Indonesia',
        'IR' => 'Iran',
        'IQ' => 'Iraq',
        'IE' => 'Ireland',
        'IM' => 'Isle of Man',
        'IL' => 'Israel',
        'IT' => 'Italy',
        'JM' => 'Jamaica',
        'JP' => 'Japan',
        'JE' => 'Jersey',
        'JO' => 'Jordan',
        'KZ' => 'Kazakhstan',
        'KE' => 'Kenya',
        'KI' => 'Kiribati',
        'KP' => 'North Korea',
        'KR' => 'South Korea',
        'KW' => 'Kuwait',
        'KG' => 'Kyrgyzstan',
        'LA' => 'Lao',
        'LV' => 'Latvia',
        'LB' => 'Lebanon',
        'LS' => 'Lesotho',
        'LR' => 'Liberia',
        'LY' => 'Libya',
        'LI' => 'Liechtenstein',
        'LT' => 'Lithuania',
        'LU' => 'Luxembourg',
        'MO' => 'Macao',
        'MK' => 'Macedonia',
        'MG' => 'Madagascar',
        'MW' => 'Malawi',
        'MY' => 'Malaysia',
        'MV' => 'Maldives',
        'ML' => 'Mali',
        'MT' => 'Malta',
        'MH' => 'Marshall Islands',
        'MQ' => 'Martinique',
        'MR' => 'Mauritania',
        'MU' => 'Mauritius',
        'YT' => 'Mayotte',
        'MX' => 'Mexico',
        'FM' => 'Micronesia',
        'MD' => 'Moldova',
        'MC' => 'Monaco',
        'MN' => 'Mongolia',
        'ME' => 'Montenegro',
        'MS' => 'Montserrat',
        'MA' => 'Morocco',
        'MZ' => 'Mozambique',
        'MM' => 'Myanmar',
        'NA' => 'Namibia',
        'NR' => 'Nauru',
        'NP' => 'Nepal',
        'AN' => 'Netherlands Antilles',
        'NL' => 'Netherlands',
        'NC' => 'New Caledonia',
        'NZ' => 'New Zealand',
        'NI' => 'Nicaragua',
        'NE' => 'Niger',
        'NG' => 'Nigeria',
        'NU' => 'Niue',
        'NF' => 'Norfolk Island',
        'MP' => 'Northern Mariana Islands',
        'NO' => 'Norway',
        'OM' => 'Oman',
        'PK' => 'Pakistan',
        'PW' => 'Palau',
        'PS' => 'Palestinian Territory',
        'PA' => 'Panama',
        'PG' => 'Papua New Guinea',
        'PY' => 'Paraguay',
        'PE' => 'Peru',
        'PH' => 'Philippines',
        'PN' => 'Pitcairn Islands',
        'PL' => 'Poland',
        'PT' => 'Portugal',
        'PR' => 'Puerto Rico',
        'QA' => 'Qatar',
        'RE' => 'Reunion',
        'RO' => 'Romania',
        'RU' => 'Russia',
        'RW' => 'Rwanda',
        'BL' => 'Saint Barthelemy',
        'SH' => 'Saint Helena',
        'KN' => 'Saint Kitts and Nevis',
        'LC' => 'Saint Lucia',
        'MF' => 'Saint Martin',
        'PM' => 'Saint Pierre and Miquelon',
        'VC' => 'Saint Vincent and the Grenadines',
        'WS' => 'Samoa',
        'SM' => 'San Marino',
        'ST' => 'Sao Tome and Principe',
        'SA' => 'Saudi Arabia',
        'SN' => 'Senegal',
        'RS' => 'Serbia',
        'SC' => 'Seychelles',
        'SL' => 'Sierra Leone',
        'SG' => 'Singapore',
		'SS' => 'SS',
        'SK' => 'Slovakia (Slovak Republic)',
        'SI' => 'Slovenia',
        'SB' => 'Solomon Islands',
        'SO' => 'Somalia, Somali Republic',
        'ZA' => 'South Africa',
        'GS' => 'South Georgia and the South Sandwich Islands',
        'ES' => 'Spain',
        'LK' => 'Sri Lanka',
        'SD' => 'Sudan',
        'SR' => 'Suriname',
        'SJ' => 'SJ',
        'SZ' => 'Swaziland',
        'SE' => 'Sweden',
        'CH' => 'Switzerland, Swiss Confederation',
        'SY' => 'Syrian Arab Republic',
        'TW' => 'Taiwan',
        'TJ' => 'Tajikistan',
        'TZ' => 'Tanzania',
        'TH' => 'Thailand',
        'TL' => 'Timor-Leste',
        'TG' => 'Togo',
        'TK' => 'Tokelau',
        'TO' => 'Tonga',
        'TT' => 'Trinidad and Tobago',
        'TN' => 'Tunisia',
        'TR' => 'Turkey',
        'TM' => 'Turkmenistan',
        'TC' => 'Turks and Caicos Islands',
        'TV' => 'Tuvalu',
        'UG' => 'Uganda',
        'UA' => 'Ukraine',
        'AE' => 'United Arab Emirates',
        'GB' => 'United Kingdom',
        'US' => 'United States',
        'UM' => 'United States Minor Outlying Islands',
        'VI' => 'United States Virgin Islands',
        'UY' => 'Uruguay',
        'UZ' => 'Uzbekistan',
        'VU' => 'Vanuatu',
        'VE' => 'Venezuela',
        'VN' => 'Vietnam',
        'WF' => 'Wallis and Futuna',
        'EH' => 'Western Sahara',
        'YE' => 'Yemen',
        'ZM' => 'Zambia',
        'ZW' => 'Zimbabwe'
    );

	if($get_list)
		return $countryList;

    if( !$countryList[$code] ) return $code;
    else return $countryList[$code];
    }
	

	public function get_geo_location($ipaddress){
		// create curl resource 
			$ch = curl_init(); 
			// set url 
			curl_setopt($ch, CURLOPT_URL, "http://ipinfo.io/{$ipaddress}/json"); 
			//return the transfer as a string 
			curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1); 
			// $output contains the output string 
			$output = curl_exec($ch); 
			// close curl resource to free up system resources 
			curl_close($ch);      
				return $output;
		}
			
		public function isJson($string) {
		 json_decode($string);
		 return (json_last_error() === JSON_ERROR_NONE);
		}
		
		public function get_ext($filename) {
			return (($pos = strrpos($filename, '.')) !== false ? substr($filename, $pos+1) : '');
		}
		
		public function format_date($str){
			$datetime = explode(' ',$str);
			$time = explode(':',$datetime[1]);
			$date = explode('/',$datetime[0]);
			return date(get_option('date_format'),mktime('0','0','0',$date[0],$date[1],$date[2]));
		}
		
		public function format_name($str){
			
			$str = trim($str);
			$str = strtolower($str);		
			$str = str_replace('  ',' ',$str);
			$str = str_replace(' ','_',$str);
			$str = str_replace('{{','',$str);
			$str = str_replace('}}','',$str);
			
			if($str=='name')
				$str = '_'.$str;
			
			return trim($str);
		}
		
		public function unformat_name($str){
			
			$str = NEXForms_Functions::format_name($str);
			
			$str = str_replace('u2019','\'',$str);
			$str = str_replace('_',' ',$str);
			$str = ucfirst(trim($str));
			return trim($str);
		}
			
		
		public function get_file_headers($file){
				
			$default_headers = array(			
				'Module Name' 		=> 'Module Name',
				'For Plugin' 		=> 'For Plugin',
				'Module Prefix'		=> 'Module Prefix',
				'Module URI' 		=> 'Module URI',
				'Module Scope' 		=> 'Module Scope',
				
				'Plugin Name' 		=> 'Plugin Name',
				'Plugin TinyMCE' 	=> 'Plugin TinyMCE',
				'Plugin Prefix'		=> 'Plugin Prefix',
				'Plugin URI' 		=> 'Plugin URI',
				'Module Ready' 		=> 'Module Ready',
				
				'Version' 			=> 'Version',
				'Description' 		=> 'Description',
				'Author' 			=> 'Author',
				'AuthorURI' 		=> 'Author URI'
			);
			return get_file_data($file,$default_headers,'module');
		}
		
		public function do_upload_image() {

			foreach($_FILES as $key=>$file)
				{
				$uploadedfile = $_FILES[$key];
				$upload_overrides = array( 'test_form' => false );
				$movefile = wp_handle_upload( $uploadedfile, $upload_overrides );
				//
				if ( $movefile )
					{
					//echo "File is valid, and was successfully uploaded.\n";
					if($movefile['file'])
						{
						$set_file_name = str_replace(ABSPATH,'',$movefile['file']);
						$_POST['image_path'] = $movefile['url'];
						$_POST['image_name'] = $file['name'];
						$_POST['image_size'] = $file['size'];
						echo $movefile['url'];
						}
					} 
				}
			
			die();
		}
	
	public function view_excerpt($content,$chars=0){
			$content = strip_tags($content);
			for($i=0;$i<$chars;$i++){
				$excerpt .= substr($content,$i,1);
			}
			
			if(strlen($content)>$chars)
				{
				$set_excerpt = '<span class="tooltipped" data-position="top" data-delay="50" data-html="true" data-tooltip="'.$content.'">'.$excerpt.'&hellip;</span>';
				}
			else
				{
				$set_excerpt = $excerpt;
				}
			
			return $set_excerpt;
		}
	
	public function print_preloader($size='big',$color='blue',$hidden=true,$class=''){
			$output = '';
			$output .= '<div class="preload '.$class.' '.(($hidden) ? 'hidden' : '').'">';
				$output .= '<div class="preloader-wrapper '.$size.' active">';
				$output .= '<div class="spinner-layer spinner-'.$color.'-only">';
				$output .= '<div class="circle-clipper left">';
				$output .= '<div class="circle"></div>';
				$output .= '</div><div class="gap-patch">';
				$output .= '<div class="circle"></div>';
				$output .= '</div><div class="circle-clipper right">';
				$output .= '<div class="circle"></div>';
				$output .= '</div>';
				$output .= '</div>';
			$output .= '</div>	';
			
			return $output;
		}
	public function time_elapsed_string($datetime, $full = false) {
		
			if(is_array($datetime))
				$set_date_time = $datetime[0];
					
			$now = new DateTime;
			$ago = new DateTime($set_date_time);
			$diff = $now->diff($ago);

		
			$diff->w = floor($diff->d / 7);
			$diff->d -= $diff->w * 7;
		
			$string = array(
				'y' => 'year',
				'm' => 'month',
				'w' => 'week',
				'd' => 'day',
				'h' => 'hour',
				'i' => 'minute',
				's' => 'second',
			);
			foreach ($string as $k => &$v) {
				if ($diff->$k) {
					$v = $diff->$k . ' ' . $v . ($diff->$k > 1 ? 's' : '');
				} else {
					unset($string[$k]);
				}
			}
		
			if (!$full) $string = array_slice($string, 0, 1);
			return $string ? implode(', ', $string) . ' ago' : 'just now';
		}
	
	}
}

?>