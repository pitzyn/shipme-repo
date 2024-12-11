<?php



if(!is_user_logged_in()) { wp_redirect(get_bloginfo('siteurl')."/wp-login.php"); exit; }
//-----------

	add_filter('sitemile_before_footer', 'shipme_my_account_before_footer');
	function shipme_my_account_before_footer()
	{
		echo '<div class="clear10"></div>';
	}
  

	global $wpdb,$wp_rewrite,$wp_query;

	$bid = shipme_get_bid_by_id(get_query_var('bid'));
	$pid = get_query_var('pid');
	$winner = get_post_meta($pid, 'winner', true);

	if(!empty($winner)) {

		wp_redirect(get_permalink(get_option('shipme_pending_delivery_page_id')));
		exit;
	}

//---------------------------------

	global $current_user;
	get_currentuserinfo();
	$uid = $current_user->ID;

	$post_p = get_post($pid);

	if($post_p->post_author != $uid) { echo 'ERR. Not your job.'; exit;}

//----------------------------------

	if(isset($_POST['yes']))
	{
		$tm = current_time('timestamp',0);
		update_post_meta($pid, 'closed','1');
		update_post_meta($pid, 'closed_date',$tm);

		update_post_meta($pid, 'outstanding',	"1");
		update_post_meta($pid, 'delivered',		"0");

		update_post_meta($pid, 'mark_coder_delivered',		"0");
		update_post_meta($pid, 'mark_seller_accepted',		"0");

		$expected_delivery = ($bid->days_done * 3600 * 24) + current_time('timestamp',0);
		update_post_meta($pid, 'expected_delivery',		$expected_delivery);
		$owner_id = get_current_user_id();

		//------------------------------------------------------------------------------

		$uid = $bid->uid;

		shipme_prepare_rating($pid, $bid->uid, $post_p->post_author);
		shipme_prepare_rating($pid, $post_p->post_author, $bid->uid);

		do_action('shipme_do_action_on_choose_winner', $bid->id );

		$newtm = current_time('timestamp',0);
		$query = "update ".$wpdb->prefix."ship_bids set date_choosen='$newtm', winner='1' where id='".$bid->id."'";
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
		update_post_meta($pid, 'paid_user',"0");
		do_action('shipme_choose_winner',$pid);

    /// invoice model bills
    $shipme_payment_working_model = get_option('shipme_payment_working_model');
    if($shipme_payment_working_model == "bill")
    {
        global $wpdb;

        //-------------
        $shipme_fee_after_paid = get_option('shipme_fee_after_paid');
        if(!empty($shipme_fee_after_paid))
        {
            $dm = time();
            $uid = get_current_user_id();
            $am = round($shipme_fee_after_paid * 0.01 * $bid->bid, 2);

            global $wpdb;
            $s = "insert into ".$wpdb->prefix."ship_bills_site (uid, pid, datemade, amount) values('$owner_id','$pid','$dm','$am')";
            $r = $wpdb->query($s);

        }

        //------------

        $shipme_fee_after_paid_transporter = get_option('shipme_fee_after_paid_transporter');
        if(!empty($shipme_fee_after_paid_transporter))
        {
            $dm = time();
            $am = round($shipme_fee_after_paid_transporter * 0.01 * $bid->bid, 2);

            global $wpdb;
            $s = "insert into ".$wpdb->prefix."ship_bills_site (uid, pid, datemade, amount) values('$uid','$pid','$dm','$am')";
            $r = $wpdb->query($s);

        }
    }


		wp_redirect(get_permalink(get_option('shipme_pending_delivery_page_id')));

		exit;
	}

	if(isset($_POST['no']))
	{
		wp_redirect(get_permalink($pid));
		exit;
	}

//==========================

get_header();

?>

<div class="container_ship_ttl_wrap">
    <div class="container_ship_ttl">
        <div class="my-page-title col-xs-12 col-sm-12 col-lg-12">
          <?php  printf(__("Choose Winner for your job: %s",'shipme'), $post_p->post_title); ?>
        </div>


    </div>
</div>



 <div class="container_ship_no_bk">

<?php 		echo shipme_get_users_links(); ?>

<div class="account-content-area col-xs-12 col-sm-8 col-lg-9">

		<ul class="virtual_sidebar">

			<li class="widget-container widget_text">
                <div class="my-only-widget-content">


               <?php

			   printf(__("You are about to choose a winner for your job: %s",'shipme'), $post_p->post_title);

			   ?>

                <div class="clear10"></div>

          <!--     <form method="post" enctype="application/x-www-form-urlencoded">

               <input type="submit" name="yes" value="<?php _e("Yes, Choose winner Now!",'shipme'); ?>" />
               <input type="submit" name="no"  value="<?php _e("No",'shipme'); ?>" />

						 </form> -->

						<p> <?php _e('You have to pay the commission fee before chosing the winner','shipme') ?></p>

						<p><a href="" class="btn btn-primary">Go to payment</a></p>
    </div>
			</div>
			</div>


        <div class="clear100"></div>



                </div>
			</li>

			</ul>
</div>
</div>

<?php get_footer(); ?>
