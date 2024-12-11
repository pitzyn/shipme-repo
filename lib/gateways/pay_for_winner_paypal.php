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
$this_script = get_bloginfo('siteurl').'/?pay_for_winner_paypal=1';

if(empty($action)) $action = 'process';

switch ($action) {



   case 'process':      // Process and order..

   global $wpdb;
   $bidid = (int)$_GET['pay_for_winner_paypal'];
   $s = "select * from ".$wpdb->prefix."ship_bids where id='$bidid'";
   $r = $wpdb->get_results($s);

   if(count($r) == 0) die("thats dead end");
   $row = $r[0];

   $shipme_fee_after_paid = get_option('shipme_fee_after_paid');
   $am = round($shipme_fee_after_paid * 0.01 * $row->bid, 2);


   $total = $am;


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
      $p->add_field('item_name', __('Payment for winner',"shipme"));
	  $p->add_field('custom', $bidid.'|'.current_time('timestamp',0));
      $p->add_field('amount', shipme_formats_special($total,2));

      $p->submit_paypal_post(); // submit the fields to paypal

      break;

   case 'success':      // Order was successful...
	case 'ipn':



	if(isset($_POST['custom']))
	{



    $cust 					= $_POST['custom'];
    $cust 					= explode("|",$cust);

    $bidid					= $cust[0];
    $tm 				= $cust[1];

		$bid = shipme_get_bid_by_id($bidid);
		$pid = $bid->pid;

	  if($winner == 0)
	  {
	    $tm = current_time('timestamp',0);
	    update_post_meta($pid, 'closed','1');
	    update_post_meta($pid, 'closed_date',$tm); 

	    $expected_delivery 	= ($bid->days_done * 3600 * 24) + current_time('timestamp',0);
			$delivery_date 			=  (get_post_meta($pid, 'delivery_date', true));


	    //------------------------------------------------------------------------------
			$pst = get_post($pid);
	    $uid = $bid->uid;

	    shipme_prepare_rating($pid, $bid->uid, $pst->post_author);
	    shipme_prepare_rating($pid, $pst->post_author, $bid->uid);

	    do_action('shipme_do_action_on_choose_winner', $bid->id );

			//--------------------

			$args = array('completion_date' => $delivery_date,
									'buyer' 					=> $pst->post_author,
									'freelancer' 			=> $bid->uid,
									'pid' 						=> $pid,
									'order_net_amount' => $bid->bid,
									'order_total_amount' => $bid->bid);

			$order = new ship_orders();
			$oid = $order->insert_order($args);


			//--------------------


	    $query = "update ".$wpdb->prefix."ship_bids set date_choosen='$tm', winner='1' where id='".$bid->id."'";
	    $wpdb->query($query);


	          shipme_send_email_on_win_to_bidder($pid, $uid);
	          shipme_send_email_on_win_to_owner($pid, $uid);


	            global $wpdb;
	            $s = "select distinct uid from ".$wpdb->prefix."ship_bids where uid!='$uid' and pid='$pid'";
	            $r = $wpdb->get_results($s);

	            foreach($r as $row)
	            {
	              $looser = $row->uid;
	              shipme_send_email_on_win_to_loser($pid, $looser);
	            }

	          //----------


	    update_post_meta($pid, 'winner', $uid);
	    do_action('shipme_choose_winner',$pid);



	  }
	}


	wp_redirect(get_permalink(get_option('shipme_finances_page_id')));

   break;

   case 'cancel':       // Order was canceled...

	wp_redirect(shipme_get_payments_page_url('deposit'));

       break;




 }

?>
