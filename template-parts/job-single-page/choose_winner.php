<?php


if(!is_user_logged_in()) { wp_redirect(get_bloginfo('siteurl')."/wp-login.php"); exit; }
//-----------

	add_filter('sitemile_before_footer', 'shipme_my_account_before_footer');
	function shipme_my_account_before_footer()
	{
		echo '<div class="clear10"></div>';
	}


	global $wpdb,$wp_rewrite,$wp_query;

	$bidid   	= $_POST['bidid'];
	$pid   		= $_POST['pid'];
	$winner 	= get_post_meta($pid, 'winner', true);



//---------------------------------

	global $current_user, $wpdb;
	get_currentuserinfo();
	$uid = $current_user->ID;

	$post_p = get_post($pid);

	if($post_p->post_author != $uid) { echo 'ERR. Not your job.'; exit;}

//----------------------------------
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

    $uid = $bid->uid;

    shipme_prepare_rating($pid, $bid->uid, $post_p->post_author);
    shipme_prepare_rating($pid, $post_p->post_author, $bid->uid);

    do_action('shipme_do_action_on_choose_winner', $bid->id );

		//--------------------

		$args = array('completion_date' => $delivery_date,
								'buyer' 					=> get_current_user_id(),
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


  get_header();



 ?>

<div class="ship-mainpanel"><div class="container">

  <div class="ship-pageheader">
              <ol class="breadcrumb ship-breadcrumb">
                <li class="breadcrumb-item"><a href="<?php echo get_site_url() ?>"><?php _e('Home','shipme') ?></a></li>
                  <li class="breadcrumb-item active" aria-current="page"><?php _e('Choose winner','shipme') ?></li>
                                      </ol>
                            <h6 class="ship-pagetitle"><?php echo __('Choose winner for the job/shipment','shipme') ?></h6>
                        </div>


                        <div class="section-wrapper mg-t-20">

                              <p class="success-text"><?php _e('You have successfuly picked a winner for your transportation job.','shipme'); ?></p>
                              <p><a href="<?php echo shipme_get_link_of_page_and_get_parameter('shipme_my_jobs_page', 'pg=pending'); ?>" class="btn btn-primary"><?php _e('Return to in progress jobs.','shipme'); ?><a></p>


                        </div>


</div></div>

 <?php get_footer(); ?>
