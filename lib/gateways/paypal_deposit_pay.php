<?php
session_start();
include 'paypal.class.php';

global $wp_query;

$action = $_GET['action'];


$p = new paypal_class;             // initiate an instance of the class
$bus = trim(get_option('shipme_paypal_email'));
if(empty($bus)) die('ERROR. Please Admin, add your paypal address in backend.');

$p->paypal_url = 'https://www.paypal.com/cgi-bin/webscr';   // testing paypal url

	$sandbox = get_option('shipme_paypal_enable_sdbx');

	if($sandbox == "yes")
	$p->paypal_url = 'https://www.sandbox.paypal.com/cgi-bin/webscr';     // paypal url


global $wpdb;
$this_script = get_bloginfo('siteurl').'/?deposit_money_via_paypal=1';

if(empty($action)) $action = 'process';

switch ($action) {



   case 'process':      // Process and order...
   $total = trim($_POST['paypal_amount']);

	  global $current_user;
	  get_currentuserinfo();
	  $uid = $current_user->ID;

//---------------------------------------------

      //$p->add_field('business', 'some_email@some_domain.com');
      $p->add_field('business', $bus);
	  $p->add_field('currency_code', get_option('shipme_currency'));
	  $p->add_field('return', $this_script.'&action=success');
	  $p->add_field('bn', 'SiteMile_SP');
      $p->add_field('cancel_return', $this_script.'&action=cancel');
      $p->add_field('notify_url', $this_script.'&action=ipn');
      $p->add_field('item_name', __('Payment Deposit',"shipme"));
	  $p->add_field('custom', $uid.'|'.current_time('timestamp',0));
      $p->add_field('amount', shipme_formats_special($total,2));

      $p->submit_paypal_post(); // submit the fields to paypal

      break;

   case 'success':      // Order was successful...
	case 'ipn':



	if(isset($_POST['custom']))
	{


	$cust 					= $_POST['custom'];
	$cust 					= explode("|",$cust);
	$uid					= $cust[0];
	$datemade 				= $cust[1];
	/*$txn_id					= $_POST['txn_id'];
	$item_name 				= $_POST['item_name'];
	$payment_date 			= $_POST['payment_date'];
	$mc_currency 			= $_POST['mc_currency'];
	$last_name 				= $_POST['last_name'];
	$first_name 			= $_POST['first_name'];
	$payer_email 			= $_POST['payer_email'];
	$address_country 		= $_POST['address_country'];
	$address_state 			= $_POST['address_state'];
	$address_country_code 	= $_POST['address_country_code'];
	$address_zip 			= $_POST['address_zip'];
	$address_street 		= $_POST['address_street'];
	$mc_fee 				= $_POST['mc_fee'];
	$mc_gross 				= $_POST['mc_gross'];

		$ss = "INSERT INTO `".$wpdb->prefix."ad_transactions` (
						`uid`,
						`pid` ,
						`datemade` ,
						`payment_date` ,
						`txn_id` ,
						`item_name` ,
						`mc_currency` ,
						`last_name` ,
						`first_name` ,
						`payer_email` ,
						`address_country` ,
						`address_state` ,
						`address_country_code` ,
						`address_zip` ,
						`address_street` ,
						`mc_fee` ,
						`mc_gross`
						)
						VALUES ('$uid',
						'$pid', '$datemade', '$payment_date', '$txn_id', '$item_name', '$mc_currency', '$last_name', '$first_name', '$payer_email',
						'$address_country', '$address_state', '$address_country_code', '$address_zip', '$address_street', '$mc_fee', '$mc_gross');";

*/
	$op = get_option('shipme_deposit_'.$uid.$datemade);


		if($op != "1")
		{
			$mc_gross = $_POST['mc_gross'] - $_POST['mc_fee'];

			$cr = shipme_get_credits($uid);
			shipme_update_credits($uid,$mc_gross + $cr);

			update_option('shipme_deposit_'.$uid.$datemade, "1");
			$reason = __("Deposit through PayPal.","shipme");
			shipme_add_history_log('1', $reason, $mc_gross, $uid);

			$reason = __("PayPal fee.","shipme");
			shipme_add_history_log('0', $reason, $_POST['mc_fee'], $uid);

			$user = get_userdata($uid);



		}

	}

	$sss = $_SESSION['redir1'];
	if(!empty($sss))
	{
		$_SESSION['redir1'] = '';
		wp_redirect($sss);
	}
	else
	wp_redirect(get_permalink(get_option('shipme_finances_page_id')));

   break;

   case 'cancel':       // Order was canceled...

	wp_redirect(shipme_get_payments_page_url('deposit'));

       break;




 }

?>
