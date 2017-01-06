<?php
/*
  Created on : Nov 4, 2016, 4:07:24 PM
  Author     : Tran Trong Thang
  Email      : trantrongthang1207@gmail.com
  Skype      : trantrongthang1207
 */
/**
 * The template for displaying the homepage.
 *
 *
 * Template name: Fluid 
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
$user_ID = get_current_user_id();
$mail = get_the_author_meta('user_email', $user_ID);
$fname = get_the_author_meta('user_firstname', $user_ID);
$lname = get_the_author_meta('user_lastname', $user_ID);
$apiKey = trim('63c6f967db234389643f9d4cdbbfbc5a-us9');
$listID = trim('e38872cf67');
$id_mailchimp = md5(strtolower($mail));
$data = array(
    'email_address' => $mail,
    'status' => 'subscribed',
);
$body = json_encode($data);
$opts = array(
    'headers' => array(
        'Content-Type' => 'application/json',
        'Authorization' => 'apikey ' . $apiKey,
    ),
        //'body' => $body
);
$get_member = array(
    'headers' => array(
        'Content-Type' => 'application/json',
        'Authorization' => 'apikey ' . $apiKey,
    ),
    'body' => $body
);
$apiKeyParts = explode('-', $apiKey);
$shard = $apiKeyParts[1];
$url = 'https://' . $shard . '.api.mailchimp.com/3.0/lists/' . $listID . '/interest-categories/67655fa17e/interests';
$second_url = 'https://' . $shard . '.api.mailchimp.com/3.0/lists/' . $listID . '/members/' . $id_mailchimp;

$third_url = 'https://' . $shard . '.api.mailchimp.com/3.0/lists/' . $listID . '/members/' . $id_mailchimp;
$forth_url = 'https://' . $shard . '.api.mailchimp.com/3.0/lists/' . $listID . '/members/' . $id_mailchimp;
$fifth_url = 'https://' . $shard . '.api.mailchimp.com/3.0/lists/' . $listID . '/members';
$response = wp_remote_get($url, $opts);
$second_response = wp_remote_get($second_url, $get_member);
$all_interest = json_decode($response['body'], true);
$the_member = json_decode($second_response['body'], true);
$meber_int = $the_member['interests'];
if (isset($_POST['unsub'])) {

    $unsub_member = $the_member;
    $unsub_member['status'] = 'unsubscribed';
    foreach ($unsub_member['interests'] as $usub_int => $value) {
        $unsub_member['interests'][$usub_int] = false;
    }
    $unsub_data = array(
        'headers' => array(
            'method' => 'PATCH',
            'blocking' => true,
            'Content-Type' => 'application/json',
            'Authorization' => 'apikey ' . $apiKey,
        ),
        'body' => json_encode($unsub_member)
    );
    $forth_response = wp_remote_put($forth_url, $unsub_data);
    wp_redirect(get_permalink(get_option('woocommerce_myaccount_page_id')));
    exit;
}
$all_member_int = $meber_int;
if (isset($_POST['submit2'])) {

    $sub_member = $the_member;
    $sub_member['status'] = 'subscribed';
    $sub_member['email_address'] = $mail;
    $sub_member['merge_fields']['FNAME'] = $fname;
    $sub_member['merge_fields']['LNAME'] = $lname;
    $styles_array = $_POST['styles'];
    foreach ($styles_array as $number => $key) {
        $sub_member['interests'][$number] = true;
    }

    $sub_data = array(
        'headers' => array(
            'method' => 'POST',
            'blocking' => true,
            'Content-Type' => 'application/json',
            'Authorization' => 'apikey ' . $apiKey,
        ),
        'body' => json_encode($sub_member)
    );
    $fifth_response = wp_remote_post($fifth_url, $sub_data);
    wp_redirect(get_permalink(get_option('woocommerce_myaccount_page_id')));
    exit;
}

if (isset($_POST['submit'])) {
    $the_member['status'] = 'subscribed';
    $styles_array = $_POST['styles'];
    foreach ($meber_int as $number => $val) {
        if ($styles_array[$number] == "on") {
            $the_member['interests'][$number] = true;
            unset($all_member_int[$number]);
        }
    }
    foreach ($all_member_int as $negative => $val2) {
        $the_member['interests'][$negative] = false;
    }
    $new_data = array(
        'headers' => array(
            'method' => 'PATCH',
            'blocking' => true,
            'Content-Type' => 'application/json',
            'Authorization' => 'apikey ' . $apiKey,
        ),
        'body' => json_encode($the_member)
    );
    $third_response = wp_remote_put($third_url, $new_data);
    wp_redirect(get_permalink(get_option('woocommerce_myaccount_page_id')));
    exit;
}
get_header();
?>
<?php get_template_part('title'); ?>
<div id="primary" class="content-area">
    <main id="main" class="site-main" role="main">

        <div class="container page-email-preferences">
            <div class="acc-details">
                <h1 class="No8-32-500-brown"><span>ACCOUNT DETAILS<i class="fa fa-caret-down"></i></span></h1>
            </div>
            <div class="col-xs-12 col-md-12 col-lg-4">
                <aside class="sidebar-profile">
                    <ul>
                        <li><a href="<?php echo get_permalink(get_option('woocommerce_myaccount_page_id')); ?>" title="<?php _e('My Account', 'woothemes'); ?>">Membership information</a></li>
                        <li><a href="<?php echo get_page_link(get_page_by_title('Styling preferences')->ID); ?>">Styling preferences</a></li>
                        <li><a href="<?php echo get_page_link(get_page_by_title('Shipping address')->ID); ?>">Shipping & billing</a></li>
                        <li><a href="<?php echo get_page_link(get_page_by_title('Credit history')->ID); ?>">FREZIA BUD HISTORY</a></li>
                        <li><a href="<?php echo get_page_link(get_page_by_title('Order history')->ID); ?>">ORDER HISTORY  </a></li>
                        <li class="active"><a href="<?php echo get_page_link(get_page_by_title('Email preferences')->ID); ?>">Email preferences  </a></li>
                        <?php if ($user_ID == "2") { ?>
                            <li><a href="<?php echo get_page_link(get_page_by_title('Birthdays')->ID); ?>">Birthdays  </a></li>
                        <?php } ?>
                    </ul>
                </aside>
            </div>
            <div class="col-xs-12 col-md-12 col-lg-8 email-pref">
                <div class="tvrow">
                    <h4 class="heading-underlined">E-mail preferences</h4>
                </div>
                <div class="clearfix"></div>
                <div class="tvrow">
                    <p class="No17-23-300-gray">
                        Share with us, and we will send you stuff you'll love straight to your mailbox.
                        <br><br>
                        I want to receive:
                    </p>
                    <div class="">
                        <?php if (trim($the_member[status]) != '404') { ?>
                            <form action="" method="post">

                                <div class="filter-section">
                                    <ul>
                                        <?php
                                        foreach ($all_interest['interests'] as $interes) {
                                            $id = $interes['id'];
                                            $checked = '';
                                            if ($meber_int[$id] == true) {
                                                $checked = 'checked';
                                            }
                                            ?>
                                            <li>
                                                <input type="checkbox" id="<?php echo $id; ?>" name="styles[<?php echo $id; ?>]" <?php echo $checked; ?>>
                                                <label class="No17-23-300-gray" for="<?php echo $id; ?>"><?php echo $interes['name']; ?></label>
                                            </li>
                                        <?php }
                                        ?>
                                    </ul>
                                    <div class="row">

                                    </div>
                                </div>
                                <div class="style-pref tvrow">
                                    <div class=" col-xs-12 col-sm-6">
                                        <input name="submit" type="submit" class="main-purple-button" value="Update" />
                                        <a href="#" class="button-like-link unsubscribe" name="unsub">Unsubscribe</a>
                                    </div>

                                </div>
                            </form>
                        <?php } else { ?>

                            <form action="" method="post">
                                <div class="filter-section ">
                                    <ul>
                                        <?php
                                        foreach ($all_interest['interests'] as $interes) {
                                            $id = $interes['id'];
                                            ?>
                                            <input type="checkbox" id="<?php echo $id; ?>" name="styles[<?php echo $id; ?>]">
                                            <label for="<?php echo $id; ?>"><?php echo $interes['name']; ?></label>
                                        <?php }
                                        ?>
                                    </ul>

                                    <div class="row">
                                        <div class="col-sm-6">

                                        </div>
                                        <div class="col-sm-6"></div>
                                    </div>
                                </div>
                                <div class="style-pref">
                                    <div class="tvrow">
                                        <div class="col-xs-6 col-sm-6 pull-right">
                                            <input name="submit2" type="submit" class="submit button pull-right" value="Update" />
                                        </div>
                                    </div>
                                </div>
                            </form>

                        <?php }
                        ?>
                    </div>
                </div>
            </div>
        </div>

    </main><!-- #main -->
</div><!-- #primary -->

<?php get_footer(); ?>
