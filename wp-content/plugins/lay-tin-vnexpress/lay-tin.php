<?php
/*
Plugin Name: Lay Tin vnExpress cho WordPress
Plugin URI: http://webbinhdan.com
Description: Plugin lấy tin từ vnExpress, <a href="http://webbinhdan.com/2016/08/04/huong-dan-lay-tin-tu-vnexpress-bang-plugin-lay-tin-vnexpress-cho-wordpress/" target="_blank">Hướng dẫn chi tiết cài đặt</a>. Hiện đã có phiên bản <strong>Lấy Tin vnExpress Pro</strong> với tính năng <strong>spin tự động, chèn link theo từ khóa, xử lí ảnh</strong> tránh nhận dạng từ google... xin liên hệ với <a href="http://webbinhdan.com" target="_blank">WebBinhDan.com</a>
Version: 1.0.9
Author: WebBinhDan.com
Author URI: http://webbinhdan.com
License: GPLv2 or later
Text Domain: lay-tin
Domain Path: /languages/
*/

include( plugin_dir_path( __FILE__ ) . 'assets/get_new_class.php' );

//Admin panel
if(!function_exists('wp_get_current_user')) {
    include(ABSPATH . "wp-includes/pluggable.php"); 
}

if ( current_user_can('administrator') ) {
	
	add_action('admin_menu', 'ltvw_plugin_setup_menu');
	 
	function ltvw_plugin_setup_menu(){
			add_menu_page( __( 'Get News Page', 'lay-tin' ), __( 'Get News', 'lay-tin' ), 'manage_options', 'get-news-plugin', 'ltvw_get_news_init' );
			add_action( 'admin_init', 'ltvw_register_settings' );
	}
	
	function ltvw_register_settings() {

			//setting array
			register_setting( 'get_news', 'get_news_link' );
			register_setting( 'get_news', 'get_news_num_news_per_link' );
			register_setting( 'get_news', 'get_news_page' );			
			register_setting( 'get_news', 'get_news_user' );
			register_setting( 'get_news', 'get_news_num_news_total' );
			register_setting( 'get_news', 'get_news_active' );
			
			//info			
			register_setting( 'get_news', 'get_news_url' );
			
			//option
			register_setting( 'get_news', 'get_news_start_from_link_num' );		
	}
	 
	function ltvw_get_news_init(){
	?>       
        <div class="wrap">                              
                        
            <?php if( isset($_GET['settings-updated']) ) { ?>
                <div id="message" class="updated">
                    <p><strong><?php _e('Settings saved.') ?></strong></p>
                </div>
            <?php } ?>
            <form method="post" action="options.php">
                <?php settings_fields( 'get_news' ); ?>
                                
                <table class="form-table">
                    <tbody>
                        <tr>
                            <th scope="row">
                                <label>
									<?php echo __( 'Config Link: ', 'lay-tin' ) ?>
                                </label>
                            </th>
                            <script>
								function textAreaAdjust(o) {
									o.style.height = "1px";
									o.style.height = (79+o.scrollHeight)+"px";
								}
								function textAreaAdunjust(o) {
									o.style.height = "1px";
									o.style.height = (103)+"px";
								}
							</script>                            
                            <td>
                            	<a href="http://webbinhdan.com/2016/08/04/huong-dan-lay-tin-tu-vnexpress-bang-plugin-lay-tin-vnexpress-cho-wordpress/" target="_blank"><input type="button" class="button button-primary" value="<?php echo __( 'Help to config this link &raquo;', 'lay-tin' ) ?>"></a><br /><br />
                                <?php echo __( 'Url | Number news | Page start | Set user | Total news | Active | category (separated by commas) | Page break', 'lay-tin' ) ?><br /><br />
                            	<textarea placeholder="https://vnexpress.net/tin-tuc/the-gioi/ 3 1 1 0 1 1,2 5
https://kinhdoanh.vnexpress.net/ 5 2 1 0 1 1,2 7" onmouseover="textAreaAdjust(this)"  onmouseleave="textAreaAdunjust(this)" class="large-text code" rows="5" name="get_news_link" ><?php echo get_option('get_news_link'); ?></textarea>
							</td>               
                        </tr>
                        <tr>
                            <th scope="row">
                                <label>
			                        <?php echo __( 'Get start from line:', 'lay-tin' ) ?>
                                </label>
                            </th>
                            <td><input class="small-text" type="text" name="get_news_start_from_link_num" value="<?php echo get_option('get_news_start_from_link_num'); ?>" /></td>
                        </tr>
                        <tr>
                            <th scope="row">
                                <label>
			                        <?php echo __( 'Save the config:', 'lay-tin' ) ?>
                                </label>
                            </th>
                            <td>
                                <strong><?php submit_button(__( 'Save', 'lay-tin' )); ?></strong>
                            </td>
                        </tr>
                    </tbody>
                </table>                
            </form>
                        
            <!-- Form to handle the upload - The enctype value here is very important -->
            <form  method="post">	
				<?php
                    if($_POST['active']=="ok"){
                        $get_news = new LTVW_Get_News_Class;							
                        //$get_news->get_news();
                        $get_news->loop_get_news();
                    }
                ?>
                <input type="hidden" name="active" value="ok" />			
                
                <table class="form-table">
                    <tbody>                     	
                        <tr>
                            <th scope="row">
                            	<label>									
									<?php echo __( 'Get news:', 'lay-tin' ) ?>                                 
                                </label>
                            </th>               
                            <td>
                            	<strong><input type="submit" name="submit" id="submit" class="button button-primary" value="<?php echo __( 'Get News', 'lay-tin' ); ?>"></strong>
                            </td>
                        </tr>                        
                    </tbody>
                </table>
            </form>                       
                        
        </div>	
        		
	<?php
	
		//More info
		function ltvw_change_admin_footer_text( $default_text ) {
			return $default_text.__( ' | This Plugin by ', 'lay-tin' ).'<a href="http://webbinhdan.com" target="_blank">WebBinhDan.com</a>';
		}
		add_filter( 'admin_footer_text', 'ltvw_change_admin_footer_text' );	
		
	}	
	
	
}



//Load language
function ltvw_load_language() {
 $plugin_dir = plugin_basename( dirname( __FILE__ ) ) . '/languages';
 load_plugin_textdomain( 'lay-tin', false, $plugin_dir );
}
add_action('plugins_loaded', 'ltvw_load_language');