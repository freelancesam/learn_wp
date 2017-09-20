<?php 


/*-----------------------------------------------------------------------------------*/



/*	Images carousel Shortcodes



/*-----------------------------------------------------------------------------------*/

if (!function_exists('rd_images_carousel')) {
function rd_images_carousel( $atts, $content = null ) {


	$src = get_stylesheet_directory_uri();


	extract(shortcode_atts(array(


		'images'   => '',

		'onclick' => 'link_image',

		'custom_links' => '',

		'hide_prev_next_buttons'	=> 'no',
		
		'nav_style'	=> '',
		
		'hide_pagination_control' => 'no',

		'b_color' => '',

		'bg_color' => '',
		'alt_b_color' => '',

		'alt_bg_color' => '',

		't_color' => '',
		'h_bg_color' => '',

		'h_t_color' => '',
		
		'style' => '',
		
		'pag_b_color' => '',

		'pag_bg_color' => '',

		'pag_t_color' => '',
		'pag_h_bg_color' => '',

		'pag_h_t_color' => '',

		'margin_top' => '0',

		'margin_bottom' => '0',
		
		'animation' => '',
		'scroll' => '',
		
		'size' => '',
		'speed' => '800',

		
		


    ), $atts));

ob_start();
global $rd_data;

if($t_color == ''){
	
$t_color = $rd_data['rd_content_text_color'];
		
}
if($b_color == ''){
	
$b_color = $rd_data['rd_content_border_color'];
		
}
if($bg_color == ''){
	
$bg_color = $rd_data['rd_content_bg_color'];
		
}
if($alt_b_color == ''){
	
$alt_b_color = $rd_data['rd_content_border_color'];
		
}
if($alt_bg_color == ''){
	
$alt_bg_color = $rd_data['rd_content_bg_color'];
		
}
if($h_t_color == ''){
	
$h_t_color = $rd_data['rd_content_heading_color'];
		
}
if($h_bg_color == ''){
	
$h_bg_color = $rd_data['rd_content_grey_color'];
		
}
if($pag_t_color == ''){
	
$pag_t_color = $rd_data['rd_content_text_color'];
		
}
if($pag_b_color == ''){
	
$pag_b_color = $rd_data['rd_content_border_color'];
		
}
if($pag_bg_color == ''){
	
$pag_bg_color = $rd_data['rd_content_bg_color'];
		
}
if($pag_h_t_color == ''){
	
$pag_h_t_color = $rd_data['rd_content_bg_color'];
		
}
if($pag_h_bg_color == ''){
	
$pag_h_bg_color = $rd_data['rd_content_hl_color'];
		
}

$id = RandomString(20);	


if ($size == ''){
	
	$size = "full";
	
}

$gal_images = '';
$thumb_images = '';
$link_start = '';
$link_end = '';
if ( $images == '' ) $images = '-1,-2,-3';

$pretty_rel_random = ' rel="prettyPhoto[rel-' . $id . ']"'; //rel-'.rand();

if ( $onclick == 'custom_link' ) {
	$custom_links = explode( ',', $custom_links );
}
$images = explode( ',', $images );
$i = - 1;

foreach ( $images as $attach_id ) {
	$i ++;
	if ( $attach_id > 0 ) {
		$post_thumbnail = wpb_getImageBySize( array( 'attach_id' => $attach_id, 'thumb_size' => $size ) );
	}

	$thumbnail = $post_thumbnail['thumbnail'];
	$p_img_large = $post_thumbnail['p_img_large'];
	$link_start = $link_end = '';

	if ( $onclick == 'link_image' ) {
		$link_start = '<a href="' . $p_img_large[0] . '" '.$pretty_rel_random.' >';
		$link_end = '</a>';
	} else if ( $onclick == 'custom_link' && isset( $custom_links[$i] ) && $custom_links[$i] != '' ) {
		$link_start = '<a href="' . $custom_links[$i] . '"' . ( ! empty( $custom_links_target ) ? ' target="' . $custom_links_target . '"' : '' ) . '>';
		$link_end = '</a>';
	}
	$gal_images .= $link_start . $thumbnail . $link_end;
}
foreach ( $images as $attach_id ) {
	$i ++;
	if ( $attach_id > 0 ) {
		$post_thumbnail = wpb_getImageBySize( array( 'attach_id' => $attach_id, 'thumb_size' => '200*200' ) );
	}

	$thumbnail = $post_thumbnail['thumbnail'];
	$p_img_large = $post_thumbnail['p_img_large'];
	$link_start = $link_end = '';
	$thumb_images .= '<div>'. $thumbnail .'</div>';
}

if($style == ''){

$output ='<style type="text/css" >#ic_'.$id.' {margin:'.$margin_top.'px auto '.$margin_bottom.'px; }#ic_'.$id.' .ic_left,#ic_'.$id.' .ic_right,#ic_'.$id.' .ic_left:after,#ic_'.$id.' .ic_right:after{border-color:'.$b_color.'; background:'.$bg_color.'; color:'.$t_color.'; }#ic_'.$id.' .ic_left:hover,#ic_'.$id.' .ic_right:hover,#ic_'.$id.' .ic_left:hover:after,#ic_'.$id.' .ic_right:hover:after{background:'.$h_bg_color.'; color:'.$h_t_color.'; }#ic_'.$id.' .rd_ic_pager a{border-color:'.$pag_b_color.'; background:'.$pag_bg_color.'; color:'.$pag_t_color.'; }#ic_'.$id.' .rd_ic_pager a:hover{border-color:'.$pag_h_bg_color.'; background:'.$pag_h_bg_color.'; color:'.$pag_h_t_color.'; }</style>';


$output .= '<script type="text/javascript" charset="utf-8">
				
		jQuery.noConflict();
	//setup up Carousel
		jQuery(document).ready(function($) {	
			
		"use strict";
		
			$(window).load(function(){
		$("#ic_'.$id.' .rd_img_carousel").carouFredSel({
					
					responsive: true,
					width: "100%",';
					
					 if($scroll !== 'yes' ){ 
					$output .= 'scroll: 1,
					auto: false,';
					 }else{ 
					$output .= 'scroll:  { items:1,duration: '.$speed.'},
					auto: true,';
					 }
					$output .= '
					prev: "#ic_'.$id.' .ic_left",
					next: "#ic_'.$id.' .ic_right",
					pagination: "#ic_'.$id.'_pager",
					height : "variable",
					swipe       : {
             		   onTouch     : true,
		               onMouse     : false
        		    },
					items: {
					height : "variable",
						visible: {
							min: 1,
							max: 1
						}
					}
				});
				});
				});
	</script>';



$output .= '<div class="rd_img_carousel_ctn '.$animation.' '.$nav_style.'" id="ic_'.$id.'">';
if($hide_prev_next_buttons == 'no'){
if($nav_style == 'hover_nav_style'){
	$output .= '<div class="ic_nav"><div class="ic_left" ></div><div class="ic_right" ></div></div>';
}else{
	$output .= '<div class="ic_nav"><div class="ic_left" >'.__('Previous','thefoxwp').'</div><div class="ic_right" >'.__('Next','thefoxwp').'</div></div>';
}
}
$output .= '<div class="rd_img_carousel">'.$gal_images.'</div>';
if($hide_pagination_control == 'no'){ 
$output .= '<div id="ic_'.$id.'_pager" class="rd_ic_pager"></div>';
}

$output .= '</div>';

}elseif($style == "rd_ic_tp") {
$output ='<style type="text/css" >#ic_'.$id.' {margin:'.$margin_top.'px auto '.$margin_bottom.'px; }#ic_'.$id.' .rd_img_carousel img,#ic_'.$id.' .rd_img_pager img{background:'.$alt_bg_color.'; border-color:'.$alt_b_color.';}</style>';	
$output .= '<script type="text/javascript" charset="utf-8">
		jQuery.noConflict();
	//setup up Carousel
		jQuery(document).ready(function($) {
		"use strict";

			$(window).load(function(){
				var $carousel = $("#ic_'.$id.' .rd_img_carousel"),
					$pager = $("#ic_'.$id.' .rd_img_pager");
					
$("#ic_'.$id.' .rd_img_pager img").each(function(i) {
		$(this).addClass( "itm"+i );
		
		/* add onclick event to thumbnail to make the main 
		carousel scroll to the right slide*/
		$(this).click(function() {
			$("#ic_'.$id.' .rd_img_carousel").trigger( "slideTo", [i, 0, true] );
			return false;
		});
	});

		$("#ic_'.$id.' .rd_img_pager img.itm0").addClass( "selected" );
	

				$carousel.carouFredSel({
					synchronise: ["#ic_'.$id.' .rd_img_pager", false], 
					responsive: true,
					auto: false,
					scroll: {
			fx: "fade",
			onBefore: function() {
				/* Everytime the main slideshow changes, it check to 
					make sure thumbnail and page are displayed correctly */
				/* Get the current position */
				var pos = $(this).triggerHandler( "currentPosition" );
				
				/* Reset and select the current thumbnail item */
				$("#ic_'.$id.' .rd_img_pager img").removeClass( "selected" );
				$("#ic_'.$id.' .rd_img_pager img.itm"+pos).addClass( "selected" );

				/* Move the thumbnail to the right page */
			}},
					width: "100%",
        			height: "variable",
					items: {
						visible: 1,
						height: "variable",
						}
					
					
					
				});
				$pager.carouFredSel({
					width: "100%",     
					auto: false,
        			height: "variable",
					items: {
						height: "variable",
					},
				});
				
				$("#ic_'.$id.' .rd_img_carousel").css("opacity","1" );
				$("#ic_'.$id.' .rd_img_pager").css("opacity", "1" );
				
			
$(".woo_img_next").click(function() { 
			$("#ic_'.$id.' .rd_img_pager, #ic_'.$id.' .rd_img_carousel").trigger( "next" ); 
		});
		$(".woo_img_prev").click(function() { 
			$("#ic_'.$id.' .rd_img_pager, #ic_'.$id.' .rd_img_carousel").trigger( "prev" ); 
		});
				});
				});
	</script>';	
	
$output .= '<div class="rd_img_carousel_ctn '.$style.'" id="ic_'.$id.'">';
$output .= '<div class="rd_img_carousel">'.$gal_images.'</div>';
$output .= '<div class="rd_img_pager">'.$thumb_images.'</div>';
$output .= '</div>';
}else {
	
	

$output ='<style type="text/css" >#ic_'.$id.' {margin:'.$margin_top.'px auto '.$margin_bottom.'px; }#ic_'.$id.' .owl-prev,#ic_'.$id.' .owl-next{border-color:'.$b_color.'; background:'.$bg_color.'; color:'.$t_color.'; }#ic_'.$id.' .owl-prev:hover,#ic_'.$id.' .owl-next:hover{background:'.$h_bg_color.'; color:'.$h_t_color.'; }#ic_'.$id.' .owl-prev:before,#ic_'.$id.' .owl-next:after{border-color:'.$t_color.'; }#ic_'.$id.' .owl-prev:hover::before,#ic_'.$id.' .owl-next:hover::after{border-color:'.$h_t_color.'; }#ic_'.$id.' .owl-dot{ background:'.$pag_bg_color.'; border-color:'.$pag_b_color.'; }#ic_'.$id.' .owl-dot.active{ background:'.$pag_h_bg_color.'; border-color:'.$pag_b_color.';}</style>';
	
	$output .= '<script type="text/javascript" charset="utf-8">

		jQuery.noConflict();
	//setup up Carousel
		jQuery(document).ready(function($) {
	"use strict";
	$(window).load(function(){
		$("#ic_'.$id.'").owlCarousel({
			responsiveClass:true,
			center:true,
			loop:true,
			responsive:{
				0:{
					items:1,
			    },
			    780:{
			        items:2,
				},
				1000:{
				    items:2,
				},
			},';
					
	if($scroll !== 'yes' ){ 
	$output .='
            scroll: 1,
			autoplay:false,';
	}else{ 
	$output .='
			 autoplay:true,
			 autoplaySpeed: '.$speed.',';
	}

	if($hide_pagination_control == 'no' ){ 
	$output .='
	   	 	 dots:true,';
	}else{
	$output .='
	   	 	 dots:false,';
	}
	if($hide_prev_next_buttons == 'no' ){ 
	$output .='
			 nav:true,';
	}
	
	$output .='
	
		});
	});
});

</script>';



$output .= '<div class="tt_testimonials_ctn '.$style.' '.$animation.' '.$nav_style.' " id="ic_'.$id.'" style="margin-top:'.$margin_top.'px; margin-bottom:'.$margin_bottom.'px;">'.$gal_images.'</div>';
	
}


echo !empty( $output ) ? $output : '';


$output_string = ob_get_contents();
ob_end_clean();
return $output_string; 

		


}


add_shortcode('rd_images_carousel', 'rd_images_carousel');
}

?>