<?php
remove_filter('get_the_excerpt', 'wp_trim_excerpt');
add_filter('get_the_excerpt', 'rd_trim_excerpt');

function rd_trim_excerpt( $text = '' ) {
    $raw_excerpt = $text;
    if ( '' == $text ) {
        $text = get_the_content('');
 
 
        /** This filter is documented in wp-includes/post-template.php */
        $text = apply_filters( 'the_content', $text );
		$text = preg_replace("#<script(.*?)>(.*?)</script>#is", '', $text);
        $text = str_replace(']]>', ']]&gt;', $text);
        $text = strip_tags($text, '<style>');

		
 
        /**
         * Filter the number of words in an excerpt.
         *
         * @since 2.7.0
         *
         * @param int $number The number of words. Default 55.
         */
        $excerpt_length = apply_filters( 'excerpt_length', 55 );
        /**
         * Filter the string in the "more" link displayed after a trimmed excerpt.
         *
         * @since 2.9.0
         *
         * @param string $more_string The string shown within the more link.
         */
        $excerpt_more = apply_filters( 'excerpt_more', ' ' . '[&hellip;]' );
        $text = wp_trim_words( $text, $excerpt_length, $excerpt_more );
    }
    /**
     * Filter the trimmed excerpt string.
     *
     * @since 2.8.0
     *
     * @param string $text        The trimmed text.
     * @param string $raw_excerpt The text prior to trimming.
     */
    return apply_filters( 'rd_trim_excerpt', $text, $raw_excerpt );
}



function get_content($more_link_text = '(more...)', $stripteaser = 0, $more_file = '')
{
	$content = get_the_content($more_link_text, $stripteaser, $more_file);
	$content = apply_filters('the_content', $content);
	$content = str_replace(']]>', ']]&gt;', $content);
	return $content;
}
//////////// Limit the excerpt lenght , default 20 ( you can modify the lenght by changing the return number) ////////////

function new_excerpt_more($more) {

    global $post;
	
	global $rd_data;

	return '<br/><a href="'. get_permalink($post->ID) . '" class="more">'. __("Read more" ,"thefoxwp") .'</a>';
}

add_filter('excerpt_more', 'new_excerpt_more');
add_filter( 'get_the_excerpt', 'shortcode_unautop');
add_filter( 'get_the_excerpt', 'do_shortcode');
add_filter( 'wp_trim_words', function( $text, $num_words, $more ) {
    return $more === mb_substr( $text, -1 * mb_strlen( $more ) ) ? $text : $text . $more;
}, 11, 3 );
// Create the Custom Excerpts callback
function rd_custom_excerpt($length_callback = '', $more_callback = '')
{
	
	
    global $post;
    if (function_exists($length_callback)) {
        add_filter('excerpt_length', $length_callback);
    }
    if (function_exists($more_callback)) {
        add_filter('excerpt_more', $more_callback);
    }
	
	$output = the_excerpt();
	$output = strip_tags($output, '<style>');
	$output = apply_filters('wptexturize', $output);
    $output = apply_filters('convert_chars', $output);	
   
  
ob_start();
  
	echo '<p>';
	echo !empty( $output ) ? $output : '';
	echo '</p>';
	
	
$output_string = ob_get_contents();
ob_end_clean();
	
echo !empty( $output_string ) ? $output_string : '';	
	
}
// Custom Length 
function rd_port_excerpt($length) {
    return 35;
}
function rd_port_long_excerpt($length) {
    return 55;
}

function rd_related_excerpt($length) {
    return 35;
}

function rd_staff_excerpt($length) {
    return 30;
}

function rd_staff2_excerpt($length) {
    return 19;
}

function rd_staff3_excerpt($length) {
    return 25;
}

function rd_bp_excerpt($length) {
    return 16;
}

function rd_rp_excerpt($length) {
    return 22;
}

function rd_cp_excerpt($length) {
    return 13;
}

function rd_widget_excerpt($length) {
    return 12;
}
function rd_port_more($more){
global $post;
return '';
}
function rd_related_more($more){
global $post;
return '<a href="'.get_permalink($post->ID).'" class="more-link">'. __('Read more','thefoxwp'). '</a>';
}
function rd_trend_more($more){
global $post;
return '<a href="'.get_permalink($post->ID).'" class="more">'. __('Read more >','thefoxwp'). '</a>';
}


?>