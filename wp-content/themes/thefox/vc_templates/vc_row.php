<?php



$output = $el_class = $parallax_speed_bg = $parallax_speed_video = $parallax = $parallax_image  = $bg_image = $bg_color = $bg_image_repeat = $font_color = $padding = $margin_bottom = $css = $after_output = '';
$disable_element = '';

$atts = vc_map_get_attributes( $this->getShortcode(), $atts );
extract( $atts );

// wp_enqueue_style( 'js_composer_front' );
wp_enqueue_script( 'wpb_composer_front_js' );
// wp_enqueue_style('js_composer_custom_css');

$css_classes = array(
	'vc_row',
	'wpb_row', //deprecated
	'vc_row-fluid',
	$el_class,
	vc_shortcode_custom_css_class( $css ),
);

$el_class = $this->getExtraClass($el_class);


$row_id = RandomString(20);

if ( ! empty( $atts['i_select'] ) ) {
            // Don't load the CSS files to trim loading time, include the specific styles via PHP
            // wp_enqueue_style( '4k-icon-' . $cssFile, plugins_url( 'icons/css/' . $cssFile . '.css', __FILE__ ) );
			$cssFile = substr( $atts['icon'], 0, stripos( $atts['icon'], '-' ) );
			wp_enqueue_style( '4k-icons',  RD_DIRECTORY . '/includes/4k-icons/css/icon-styles.css' , null, VERSION_GAMBIT_VC_4K_ICONS );
			wp_enqueue_script( '4k-icons',  RD_DIRECTORY . '/includes/4k-icons/js/script-ck.js', array( 'jquery' ), VERSION_GAMBIT_VC_4K_ICONS, true );
		
  global $iconContents;

        include('icon-contents.php' );

		// Normal styles used for everything
        $cssFile = substr( $atts['icon'], 0, stripos( $atts['icon'], '-' ) );

        $iconFile =  RD_DIRECTORY . '/includes/4k-icons/icons/fonts/' . $cssFile;
		$iconFile = apply_filters( '4k_icon_font_pack_path', $iconFile, $cssFile );

		// Fix ligature icons (these are icons that use more than 1 symbol e.g. mono social icons)
		$ligatureStyle = '';
        if ( $cssFile == 'mn' ) {
            $ligatureStyle = '-webkit-font-feature-settings:"liga","dlig";-moz-font-feature-settings:"liga=1, dlig=1";-moz-font-feature-settings:"liga","dlig";-ms-font-feature-settings:"liga","dlig";-o-font-feature-settings:"liga","dlig";
                         	 font-feature-settings:"liga","dlig";
                        	 text-rendering:optimizeLegibility;';
        }

		$iconCode = '';
		if ( ! empty( $atts['icon'] ) ) {
			$iconCode = $iconContents[ $atts['icon'] ];
		}

		$ret = "<style type='text/css'>
            @font-face {
            	font-family: '" . $cssFile . "';
            	src:url('" . $iconFile . ".eot');
            	src:url('" . $iconFile . ".eot?#iefix') format('embedded-opentype'),
            		url('" . $iconFile . ".woff') format('woff'),
            		url('" . $iconFile . ".ttf') format('truetype'),
            		url('" . $iconFile . ".svg#oi') format('svg');
            	font-weight: normal;
            	font-style: normal;
            }
            #row_".$row_id." ." . $atts['icon'] . ":before { font-family: '" . $cssFile . "'; font-weight: normal; font-style: normal; }
            #row_".$row_id." .". $atts['icon'] . ":before { content: \"" . $iconCode . "\"; $ligatureStyle }
            #row_".$row_id."{ background:" . $atts['i_bg_color'] . "; }#row_".$row_id." i{ background:" . $atts['i_color'] . "; }
";

		$ret .= "</style>";

		// Compress styles a bit for readability
		$ret = preg_replace( "/\s?(\{|\})\s?/", "$1",
			preg_replace( "/\s+/", " ",
			str_replace( "\n", "", $ret ) ) )
			. "\n";
	
		
		}
if ( 'yes' === $disable_element ) {
	if ( vc_is_page_editable() ) {
		$css_classes[] = 'vc_hidden-lg vc_hidden-xs vc_hidden-sm vc_hidden-md';
	} else {
		return '';
	}
}

if ( ! empty( $full_height ) ) {
	$css_classes[] = ' vc_row-o-full-height';
	if ( ! empty( $columns_placement ) ) {
		$flex_row = true;
		$css_classes[] = 'vc_row-flex vc_row-o-columns-' . $columns_placement;
	}
}
if ( ! empty( $equal_height ) ) {
	$flex_row = true;
	$css_classes[] = 'vc_row-flex vc_row-o-equal-height';
}	
if ( ! empty( $content_placement ) ) {
	$flex_row = true;
	$css_classes[] = ' vc_row-o-content-' . $content_placement;
}

$parallax_speed = $parallax_speed_bg;
if ( ! empty( $parallax ) ) {
	if ( empty( $parallax_image ) ){
		$parallax_image = $bg_image;
	}
	wp_enqueue_script( 'vc_jquery_skrollr_js' );
	$wrapper_attributes[] = 'data-vc-parallax="' . esc_attr( $parallax_speed ) . '"'; // parallax speed
	$css_classes[] = 'vc_general vc_parallax vc_parallax-' . $parallax;
	if ( false !== strpos( $parallax, 'fade' ) ) {
		$css_classes[] = 'js-vc_parallax-o-fade';
		$wrapper_attributes[] = 'data-vc-parallax-o-fade="on"';
	} elseif ( false !== strpos( $parallax, 'fixed' ) ) {
		$css_classes[] = 'js-vc_parallax-o-fixed';
	}
}

if ( ! empty( $parallax_image ) ) {
		$parallax_image_id = preg_replace( '/[^\d]/', '', $parallax_image );
		$parallax_image_src = wp_get_attachment_image_src( $parallax_image_id, 'full' );
		if ( ! empty( $parallax_image_src[0] ) ) {
			$parallax_image_src = $parallax_image_src[0];

	}
	$wrapper_attributes[] = 'data-vc-parallax-image="' . esc_attr( $parallax_image_src ) . '"';
}
$css_class = preg_replace( '/\s+/', ' ', apply_filters( VC_SHORTCODE_CUSTOM_CSS_FILTER_TAG, implode( ' ', array_filter( array_unique( $css_classes ) ) ), $this->settings['base'], $atts ) );
$style = buildRowStyle($bg_image, $bg_color, $bg_image_repeat, $font_color, $padding, $margin_bottom);
$wrapper_attributes[] = " ";
$output .= '<div ';



$output .= isset( $el_id ) && ! empty( $el_id ) ? " id='" . esc_attr( $el_id ) . "'" : ""; 
$output .= ' '. implode( ' ', $wrapper_attributes ) . 'class="'.esc_attr( trim( $css_class ) ).' '.$type.' ';
if ($video_background == true){
$output .= ' rd_video_ctn '; 	
}
if ($overlay == true){
$output .= ' rd_has_overlay '; 	
}
$output .= '"'.$style.'>';

$output .= wpb_js_remove_wpautop($content);
if ( $i_select !== '' ){
$output .= $ret;	
$output .= '<div class="row_top_icon" id="row_'.$row_id.'"><i class="'.$icon.'"></i></div>';
}
if ( $a_select !== '' ){
$arr = "<style type='text/css' scoped='scoped'>#row_".$row_id.".row_bottom_arrow{ background:" . $atts['a_bg_color'] . "; }</style>";
$arr = preg_replace( "/\s?(\{|\})\s?/", "$1",
			preg_replace( "/\s+/", " ",
			str_replace( "\n", "", $arr ) ) )
			. "\n";
$output .= $arr;	
$output .= '<div class="row_bottom_arrow" id="row_'.$row_id.'"></div>';
}
if ($video_background == true){
$output .= '<video class="parallax_video" preload="auto" autoplay="true" loop="loop" muted="muted" data-top-default="0">';

if($video_link !== ""){

$output .= '<source src="'.$video_link.'" type="video/mp4">';

}

if($video_link_webm !== ""){

$output .= '<source src="'.$video_link_webm.'" type="video/webm">';

}

if($video_link_ogg !== ""){

$output .= '<source src="'.$video_link_ogg.'" type="video/ogg">';

}

$output .= '</video>';
}

if ($overlay == true){
$output .= '<div class="rd_row_overlay" style="background:'.$overlay_color.';"></div>';
}
$output .= '</div>';
$output .= $after_output;
$output .= $this->endBlockComment( $this->getShortcode() );

echo !empty( $output ) ? $output : '';

