<?php
/*
Dev: http://webbinhdan.com
Version: 1.0.0
Name: Get news class
License: GPLv2 or later
*/
if(!function_exists('file_get_html')&&!function_exists('str_get_html')&&!function_exists('dump_html_tree')&&!class_exists('simple_html_dom_node')) {
    include( 'simple_html_dom.php' );
}

//Class lấy tin
class LTVW_Get_News_Class{
	
	public static $date = null;
	public static $html = null;
	public static $thumb = null;
	public static $arr_cat = array(1);
	
	public static function html_news($url, $img_thumb, $arr_cat_fix=array(1)){
				
		$dir = plugin_dir_path( __FILE__ );
		$site = plugins_url( 'content.txt', __FILE__ );										
		
		//unlink($dir."content.txt");
		file_put_contents($dir."content.txt", "");
		//shell_exec("curl -o " . $dir . "content.txt {$url}");	
		$open_file = fopen($url, 'r');
		file_put_contents($dir . "content.txt", $open_file);
		fclose($open_file);
		
		//Load html
		self::$html = file_get_html($site);
		self::$thumb = $img_thumb;
		
		//Gate date	
		self::$date = self::$html->find('span[class="time left"]',0)->plaintext;
		if(self::$date==""){
			self::$date = self::$html->find('span[class="time left"]',1)->plaintext;
		}
		self::$date = explode(',',self::$date);
		self::$date = explode(' | ',self::$date[1]);
		self::$date = explode('&nbsp;|&nbsp;',self::$date[0]);
		self::$date = self::$date[0].self::$date[1];
		self::$date = preg_replace("/\sGMT\+7/i",'', self::$date);
		self::$date = preg_replace("/\//i",'-', self::$date);
		self::$date = strtotime(self::$date)-129600;
		
		//Set cate
		self::$arr_cat = $arr_cat_fix;				
		
	}
	
	public function loop_get_news(){
		
		//Get arr from admin		
		$arr_link = explode(PHP_EOL, get_option('get_news_link'));	
			
		//Loop
		$k=get_option('get_news_start_from_link_num')-1;
		foreach($arr_link as $link){
			$info = explode( ' ', $link );
			
			$url        = $info[0];
			$num_news   = $info[1];
			$page       = $info[2];
			$user       = $info[3];
			$total_news = $info[4];
			$active     = $info[5];
			$cate       = explode(',', $info[6]);
			$full_page  = $info[7];
			
			if($active!=0){
				
				$ok=0; $page_curent = $page; $num_page = 1;
				while($ok < $num_news){										 
				
					//Set url get list
					$url_fix= $url.'page/'.$page_curent.'.html';
										
					//List to link and thumb
					$arr_link_thumb = $this->html_list($url_fix);
					
					//Count news per link
					$num_news_per_link = count($arr_link_thumb);					
					
					// Loop get news
					$i=0;			
					foreach($arr_link_thumb as $link_thumb){
						
						$this->html_news($link_thumb['href'], $link_thumb['src'], $cate);
						// Number news goten ok
						$ok+=$this->get_news();				
						
						$i++;
						
						//Break when enought news for this link
						if($ok==$num_news){
							break 2;
						}
					}
					
					//Change page to continue get link
					if($i==$num_news_per_link){												
										
						$page_curent++;						
												
						$num_of_position_link_change = $k;
						$page_n = $page_curent;
						$total_news_n = $total_news + $ok;
						
						$this->change_arr($num_of_position_link_change, null, null, $page_n, null, $total_news_n, null);	
						
						if( $num_page>0 && $num_page==$full_page ){
							break;
						}
						$num_page++;																					
					}
									
				}
			
			}
			
			//Number position line of link
			$k++;
			
		}
		
	}
	
	public function html_list($link){
		$dir = plugin_dir_path( __FILE__ );
		$site = plugins_url( 'list.txt', __FILE__ );										
		
		//unlink($dir."list.txt");
		file_put_contents($dir."list.txt", "");
		//shell_exec("curl -o " . $dir . "content.txt {$url}");
		$open_file = fopen($link, 'r');
		file_put_contents($dir . "list.txt", $open_file);
		fclose($open_file);
		
		//Load html
		$html_link = file_get_html($site);
		
		//Get title link
		//foreach($html_link->find('#box_news_top .title_news a,.block_mid_new .title_news a,#box_news_top .box_show_item_title a,') as $title){
			//echo $title = $title->href . '<br>';			
		//}
		$i=0;
		foreach($html_link->find('.featured article, .featured .sub_featured ul li, .sidebar_1 .list_news') as $box){

			$html_link_sub = str_get_html($box);
			//var_dump( $html_link_sub->find('.title_news a[!class]')) . '<br>';
			foreach($html_link_sub->find('.title_news a[!class], .thumb_art img') as $box_sub){
				//echo $box_sub->href . '<br>';
				//var_dump( $box_sub) . '<br>';
				if($box_sub->href!=''){
					
					$arr_media[$i]['href']=$box_sub->href;
										
				}
				
				if($box_sub->src!=''&&$box_sub->{'data-original'}==''){
					//echo $box_sub->src . '<br>';
					$box_sub->src = preg_replace('/_[0-9]{1,3}x[0-9]{1,3}/i', '', $box_sub->src);
					$arr_media[$i]['src']=$box_sub->src;					
				}

				if($box_sub->{'data-original'}!=''){
					echo $box_sub->{'data-original'} . '<br>';
					$box_sub->{'data-original'} = preg_replace('/_[0-9]{1,3}x[0-9]{1,3}/i', '', $box_sub->{'data-original'});
					$arr_media[$i]['src']=$box_sub->{'data-original'};
				}


								
			}			
			$i++;
		}				
		
		return $arr_media;
				
	}
	
	//change_arr($num, NULL, NULL, NULL,NULL, NULL, NULL);
	public function change_arr($num_of_position_link_change, $URL_n, $num_news_n, $page_n, $user_n, $total_news_n, $active_n){
		
		$arr_temp = explode(PHP_EOL, get_option('get_news_link'));
		$a=0;
		foreach($arr_temp as $link){
			$link = explode(" ", $link);
			
			$URL[$a]        = $link[0];
			$num_news[$a]   = $link[1];
			$page[$a]       = $link[2];
			$user[$a]       = $link[3];
			$total_news[$a] = $link[4];
			$active[$a]     = $link[5];
			$cate[$a]       = $link[6];
			$full_page[$a]  = $link[7];
			
			$a++;
		}
		
		if($num_of_position_link_change>$a){
			echo "<br />".__( 'Error change array', 'lay-tin' )."<br />";
			return 0;
		}
		
		$arr_link="";
		$numTemp = $num_of_position_link_change;
		for($b=0; $b<=$a; $b++){
			if($b==$numTemp){
				if($URL_n!=NULL){
					$URL[$b] = $URL_n;
				}
				if($num_news_n!=NULL){
					$num_news[$b] = $num_news_n;
				}
				if($page_n!=NULL){
					$page[$b] = $page_n;
				}
				if($user_n!=NULL){
					$user[$b] = $user_n;
				}
				if($total_news_n!=NULL){
					$total_news[$b] = $total_news_n;
				}
				if($active_n!=NULL){
					$active[$b] = $active_n;
 				}			
			}
			$arr_link = $arr_link . PHP_EOL . "{$URL[$b]} {$num_news[$b]} {$page[$b]} {$user[$b]} {$total_news[$b]} {$active[$b]} {$cate[$b]} {$full_page[$b]}";
		}
		
		$arr_link = trim($arr_link);
		update_option( 'get_news_link', $arr_link );
		
	}
	
	//Change folder upload to date 
	public static function wpse_141088_upload_dir( $dir_mod ) {
		
		$date_temp = self::$date;
		
		$time_upload=$date_temp;
		$time_upload=date('/Y/m', $time_upload);
		
		return array(
			'path'   => $dir_mod['basedir'] . $time_upload,
			'url'    => $dir_mod['baseurl'] . $time_upload,
			'subdir' => $time_upload,
		) + $dir_mod;
	}
	
	//Upload file
	public function upload_img($url, $date, $name_file){
		
		//Check file type and set flag for Flip image
		//if( preg_match("/\.(jpg)$/", $url) ) { $img_type='jpg'; }
		$img_type = pathinfo($url, PATHINFO_EXTENSION);		
		
		//Get filename (ex: abc.jpg) for name of upload
		//preg_match('/[^\?]+\.(jpg|jpe|jpeg|gif|png)/i', $url, $matches);
		
		
		//Doanload image to my server			
		$tmp = download_url( $url );		
		
		if( is_wp_error( $tmp ) ){
			echo '<br />';
			echo __( 'Error download images:', 'lay-tin' )." {$url} :";
		}					
		
		//Set time upload
		$time_upload=self::$date;
		$time_upload=date('Y-m-d H:i:s', $time_upload);
		
		//Create info image upload
		$file_array = array();												
		$file_array['name'] = $name_file.'.'.$img_type;
		$file_array['tmp_name'] = $tmp;
		
		$post_data_fix = array(
			'post_date' => $time_upload,
			'post_date_gmt' => $time_upload
		);
		
		//Delete download Image error
		if ( is_wp_error( $tmp ) ) {
			@unlink($file_array['tmp_name']);
			$file_array['tmp_name'] = '';
		}
		
		//Replace by noimage.jpg
		if($file_array['name']=='real-estate-12.jpg'){
			$uploaded='53479';
		}
		else{
			//Upload image to wp
			add_filter( 'upload_dir', 'LTVW_Get_News_Class::wpse_141088_upload_dir' );
			$uploaded=media_handle_sideload($file_array, 0, null, $post_data_fix);
			remove_filter( 'upload_dir', 'LTVW_Get_News_Class::wpse_141088_upload_dir' );	
		}							
		//Upload error
		if ( is_wp_error($uploaded) ) {
			@unlink($file_array['tmp_name']);
			echo '<br />';
			echo __( 'Error imgages', 'lay-tin' )."<br />";
			//return $uploaded;
		}
		// Upload complete					
		else{
			$meta_ID = $uploaded;
			return $meta_ID;					
		}
	}
	
	//Get news
	public function get_news(){		
	
		//Get tag		
		//foreach(self::$html->find('div[class="block_tag width_common space_bottom_20"] a') as $tag){
		foreach(self::$html->find('div[class="block_tag"] a') as $tag){
			$tag = '|'.$tag->plaintext;
			$tags .= $tag;
		}		
		$arr_tags = explode("|", $tags);
		//Delete first var (null) in arr_tags
		unset($arr_tags[0]);	
				
				
		//Get title
		foreach(self::$html->find('h1[class="title_news_detail"]') as $title){
			$title = $title->plaintext;
			if(isset($arr_tags[1])){
				echo $title = '['.ucfirst($arr_tags[1]).'] '.$title;
				echo '<br>';
			}
		}
		
		//Slug
		$slug = sanitize_title($title);
		
		//Check post exists
		$args = array(
		  'name'        => $slug,
		  'post_type'   => 'post',
		  'post_status' => array( 'publish', 'future', 'draft', 'trash' ),
		  'numberposts' => 1
		);
		$my_posts = get_posts($args);
		$post_exists = count($my_posts);										
		
		//If no post exists, insert post
		if(!$post_exists){								
			
			//Get content intro
			//foreach(self::$html->find('h3[class="short_intro txt_666"]') as $content_intro){
			foreach(self::$html->find('.description') as $content_intro){
				$content_intro = '<p>'.$content_intro->plaintext.'</p>';
			}
			
			//Get content
			//foreach(self::$html->find('div[class="fck_detail width_common block_ads_connect"]') as $content){
			foreach(self::$html->find('article[class="fck_detail"], article[class="block_content_slide_showdetail"] #article_content') as $content){
				//echo $content2 = $element->plaintext . '<br>';
				
				//Replace <script>
				$content = preg_replace('/<script([^<]+)([^>]+)\/script>/i','', $content);
				//Replace <style>
				$content = preg_replace('/<style([^<]+)([^>]+)\/style>/i','', $content);
				//Replace HTML tag, only use <p><img><b><i><strong><table><th><tr><td><caption><colgroup><col><thead><tbody><tfoot>
				$content = strip_tags($content, '<p><img><b><i><strong><br><h1><h2><h3><h4><h5><h6><code><blockquote><em><q><small><ol><li><ul><dl><dt><dd><table><th><tr><td><caption><colgroup><col><thead><tbody><tfoot>');
				//Replace HTML atract
				$content = preg_replace("/<([a-z][a-z0-9]*)[^>]*?(\/?)>/i",'<$1$2>', $content);
				//Replace "Xem them:"
				$content = preg_replace('/<p([^<]+)Xem\sthêm:([^>]+)\/p>/i','', $content);	
				
				
				//Not get news content < 1500 chart
				if(strlen($content)<1500){				
					return 0;
				}		
				
				//Get images
				//foreach(self::$html->find('div[class="fck_detail width_common block_ads_connect"] img') as $img){
				$z=1;
				foreach(self::$html->find('article[class="fck_detail"] img, article[class="block_content_slide_showdetail"] #article_content img') as $img){
					
									
					//Set image link to download
					//echo $content2 = $element->plaintext . '<br>';						
					$img = $img->src;
					$img = preg_replace('/_[0-9]{1,3}x[0-9]{1,3}/i', '', $img);
					$meta_ID = $this->upload_img($img, self::$date, $slug.'-'.$z);
					//Get type (ex: jpg, png...) of first image for thumb.
					if($z==1){
						$meta_ID_thumb = $meta_ID;
					}
					$link_img = wp_get_attachment_url( $meta_ID );
					$img = "<img class=\"aligncenter wp-image-{$meta_ID} size-full\" src=". $link_img ." alt=\"{$title} {$meta_ID}\" width=\"auto\" height=\"auto\" />";
					$content = preg_replace("/<img\/?>/i",$img, $content, 1);
						
					$z++;	
				}
								
				$content = $content_intro . $content;
				break;
			   
			}		
			
			//Upload thumb
			if(self::$thumb!=null){
				$thumb_ID = $this->upload_img(self::$thumb, self::$date, $slug.'-0');
			}
			else{
				$thumb_ID = $meta_ID_thumb;
			}
			
			//$time_mod=time()+rand(0,86400);
			$time_mod=self::$date;
			$time_mod=date('Y-m-d H:i:s', $time_mod);
			
			//insert post
			$post = array(
				'post_content'   => $content, // The full text of the post.
				'post_name'      => $slug, // The name (slug) for your post
				'post_title'     => $title, // The title of your post.
				'post_status'    => 'publish', // Default 'draft' 'future'.
				'post_type'      => 'post', // Default 'post'.
				'post_author'    => $this->user, // The user ID number of the author. Default is the current user ID.
				'post_date'      => $time_mod, // The time post was made.
				'post_date_gmt'  => $time_mod, // The time post was made, in GMT.
				'ping_status'    => 'default_ping_status', // Pingbacks or trackbacks allowed. Default is the option 'default_ping_status'.
				'comment_status' => 'closed', // Default is the option 'default_comment_status', or 'closed'.
				'post_category'  => self::$arr_cat,
			);
			$post_ID = wp_insert_post( $post );
			
			//Post meta (thumb, ...)
			add_post_meta( $post_ID, '_thumbnail_id', $thumb_ID );
			wp_set_post_tags( $post_ID, $arr_tags, true );
			return 1;
			
		}
		//If post exists, return 0 (double posst)
		else{
			return 0;
		}
			
	}
	
}