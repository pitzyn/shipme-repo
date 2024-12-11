<?php

include 'paypal.class.php';


	global $wp_query, $wpdb, $current_user;
	$pid = $_GET['pay_commission_via_paypal'];
	get_currentuserinfo();
	$uid = $current_user->ID;
	$post = get_post($pid);

$action = $_GET['action'];
$business = trim(get_option('shipme_paypal_email'));
if(empty($business)) die('Error. Admin, please add your paypal email in backend!');

$p = new paypal_class;             // initiate an instance of the class
$p->paypal_url = 'https://www.paypal.com/cgi-bin/webscr';   // testing paypal url

//--------------

	$shipme_paypal_enable_sdbx = get_option('shipme_paypal_enable_sdbx');
	if($shipme_paypal_enable_sdbx == "yes")
	$p->paypal_url = 'https://www.sandbox.paypal.com/cgi-bin/webscr';     // paypal url

//--------------

$this_script = get_bloginfo('siteurl').'/?pay_commission_via_paypal='.$pid;

if(empty($action)) $action = 'process';   



switch ($action) {

    

   case 'process':      // Process and order...
		
		$posta = get_post($pid);
		
		$shipme_fee_after_paid = get_option('shipme_fee_after_paid');
		$shipme_get_winner_bid = shipme_get_winner_bid($pid);
							
		$total = round(0.01*$shipme_fee_after_paid *$shipme_get_winner_bid->bid);
 
//---------------------------------------------	
 
      //$p->add_field('business', 'some_email@some_domain.com');
      $p->add_field('business', $business);
	  
	  $p->add_field('currency_code', get_option('shipme_currency'));
	  $p->add_field('return', $this_script.'&action=success');
      $p->add_field('cancel_return', $this_script.'&action=cancel');
      $p->add_field('notify_url', $this_script.'&action=ipn');
      $p->add_field('item_name', $posta->post_title);
	  $p->add_field('custom', $pid.'|'.$uid.'|'.current_time('timestamp',0));
      $p->add_field('amount', shipme_formats_special($total,2));
	  $p->add_field('bn', 'SiteMile_SP'); 

      $p->submit_paypal_post(); // submit the fields to paypal

      break;

   case 'success':      // Order was successful...
	case 'ipn':
	

	
	if(isset($_POST['custom']))
	{

		$cust 					= $_POST['custom'];
		$cust 					= explode("|",$cust);
		
		$pid					= $cust[0];
		$uid					= $cust[1];
		$datemade 				= $cust[2];
		
		//--------------------------------------------
		
		update_post_meta($pid, "commission_unpaid", 				"0");
		update_post_meta($pid, "paid_date", 						current_time('timestamp',0));

 
		$paid_comm_date_paypal = get_post_meta($pid,'paid_comm_date_paypal' . $datemade,true);
		
		if(empty($paid_comm_date_paypal))
		{
			
			 
				
			//shipme_send_email_posted_project_approved($pid);
			//shipme_send_email_posted_project_approved_admin($pid);
				
		 
			
			//update_post_meta($pid, "paid_comm_date_paypal" .$datemade, current_time('timestamp',0));
		}
	}
	
	wp_redirect(get_permalink(get_option('shipme_account_page_id')  . "?done_pp=1" ));
	
 
   
   break;

   case 'cancel':       // Order was canceled...

	wp_redirect(get_bloginfo('siteurl'));

       break;
     



 }     

?>