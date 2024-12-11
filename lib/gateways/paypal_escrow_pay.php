<?php
session_start();
include 'paypal.class.php';

global $wp_query;

$action = $_GET['action'];
$oid = $_GET['oid'];


$p = new paypal_class;             // initiate an instance of the class
$bus = trim(get_option('shipme_paypal_email'));
if(empty($bus)) die('ERROR. Please Admin, add your paypal address in backend.');

$p->paypal_url = 'https://www.paypal.com/cgi-bin/webscr';   // testing paypal url

	$sandbox = get_option('shipme_paypal_enable_sdbx');

	if($sandbox == "yes")
	$p->paypal_url = 'https://www.sandbox.paypal.com/cgi-bin/webscr';     // paypal url


global $wpdb;
$this_script = get_bloginfo('siteurl').'/?answer_paypal_ipn_escrow=1';

if(empty($action)) $action = 'process';

switch ($action) {



   case 'process':      // Process and order...
   
    
    $order  = new ship_orders($oid);
    $objectOrder = $order->get_order();
    $order_total_amount = $objectOrder->order_total_amount;

    $tm = current_time('timestamp');


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
      $p->add_field('item_name', sprintf(__('Payment for escrow order %s',"shipme"), '#'.$oid));
	  $p->add_field('custom', $uid.'|'.$oid.'|'. $tm);
      $p->add_field('amount', shipme_formats_special($order_total_amount,2));

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
