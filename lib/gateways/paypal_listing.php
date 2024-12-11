<?php

include 'paypal.class.php';


	global $wp_query, $wpdb, $current_user;
	$pid = $wp_query->query_vars['pid'];
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

$this_script = get_bloginfo('siteurl').'/?paypal_listing=1&pid='.$pid;

if(empty($action)) $action = 'process';



switch ($action) {



   case 'process':      // Process and order...

	 $array  = ship_calculate_listing_fees_of_job($pid);
	 $total = $array['total'];

	$title_post = $post->post_title;
	$title_post = apply_filters('shipme_filter_paypal_listing_title', $title_post, $pid);

//---------------------------------------------

      //$p->add_field('business', 'some_email@some_domain.com');
      $p->add_field('business', $business);

	  $p->add_field('currency_code', get_option('shipme_currency'));
	  $p->add_field('return', $this_script.'&action=success');
      $p->add_field('cancel_return', $this_script.'&action=cancel');
      $p->add_field('notify_url', $this_script.'&action=ipn');
      $p->add_field('item_name', $title_post);
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



		$shipme_listing_fees = new shipme_listing_fees($pid);
		$shipme_listing_fees->mark_things_paid();


		$shipme_admin_approves_each_job = get_option('shipme_admin_approves_each_job');
		$paid_listing_date = get_post_meta($pid,'paid_listing_date_paypal' . $datemade,true);

		if(empty($paid_listing_date))
		{

			if($shipme_admin_approves_each_job != "yes")
			{
				wp_publish_post( $pid );
				$xx = current_time('timestamp',0);
												$post_pr_new_date = date('Y-m-d H:i:s',$xx);
												$gmt = get_gmt_from_date($xx);

												$post_pr_info = array(  "ID" 	=> $pid,
												  "post_date" 				=> $post_pr_new_date,
												  "post_date_gmt" 			=> $gmt,
												  "post_status" 			=> "publish"	);

				wp_update_post($post_pr_info);

				shipme_send_email_posted_job_approved($pid);
				shipme_send_email_posted_job_approved_admin($pid);

			}
			else
			{

				shipme_send_email_posted_job_not_approved($pid);
				shipme_send_email_posted_job_not_approved_admin($pid);
				shipme_send_email_subscription($pid);

			}

			update_post_meta($pid, "paid_listing_date_paypal" .$datemade, current_time('timestamp',0));
		}
	}

	if(get_option('shipme_admin_approves_each_job') == 'yes')
	{
		if(shipme_using_permalinks())
		{
			wp_redirect(get_permalink(get_option('shipme_account_page_id')) . "?prj_not_approved=" . $pid);
		}
		else
		{
			wp_redirect(get_permalink(get_option('shipme_account_page_id')) . "&prj_not_approved=" . $pid);
		}
	}
	else	wp_redirect(get_permalink($pid));

   break;

   case 'cancel':       // Order was canceled...

	wp_redirect(get_bloginfo('siteurl'));

       break;




 }

?>
