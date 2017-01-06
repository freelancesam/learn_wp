<?php
/*
  Created on : Nov 4, 2016, 4:07:24 PM
  Author     : Tran Trong Thang
  Email      : trantrongthang1207@gmail.com
  Skype      : trantrongthang1207
 */
/**
 * The template for displaying the mailchimp.
 *
 *
 * Template name: mailchimp 
 *
 * @package storefront
 */
remove_filter('the_content', 'wpautop');
get_header();
?>
<?php
if (!defined('ABSPATH')) {
    exit; // Exit if accessed directly
}
$api_key = '8be1cfbeb6d50c4512d4f51f5a278f27-us11';
$list_id = '376185';
$dc = substr($api_key,strpos($api_key,'-')+1);
 
 
// "Schema describes object"? Not a problem
$body_args = new stdClass();
// all the batch operations will be stored in this array
$body_args->operations = array();
 
$wordpress_users_all = get_users( 'role=subscriber' ); // you may add another parameters into this function
 
// loop all found WP users
print_r($wordpress_users_all);
foreach ( $wordpress_users_all as $user ) {
	// a single batch operation object for each user
	$batch =  new stdClass();
	$batch->method = 'PUT';
	$batch->path = 'lists/' . $list_id . '/members/' . md5(strtolower($user->user_email));
	$batch->body = json_encode( array(
		'email_address' => $user->user_email,
		'status'        => 'subscribed',
		'merge_fields'  => array( 
			'FNAME' => $user->first_name,
			'LNAME' => $user->last_name
		)
	) );
	$body_args->operations[] = $batch;
}
 
$args = array(
	'method' => 'POST',
 	'headers' => array(
		'Authorization' => 'Basic ' . base64_encode( 'user:'. $api_key )
	),
	'body' => json_encode( $body_args )
);
 
$response = wp_remote_post( 'https://'.$dc.'.api.mailchimp.com/3.0/batches', $args );
 
$body = json_decode( wp_remote_retrieve_body( $response ) );
print_r($body);
get_header();
?>
<?php get_template_part('title'); ?>
<div id="primary" class="content-area">
    <main id="main" class="site-main" role="main">

        
    </main><!-- #main -->
</div><!-- #primary -->

<?php get_footer(); ?>
