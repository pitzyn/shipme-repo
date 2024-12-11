<?php
/**********************
*	MIT license
**********************/
 
 	 
	global $current_user, $wp_query;
	$pid 	= $wp_query->query_vars['pid'];
	$uid	= $current_user->ID;
	
	//-----------------------------------
	
	function shipme_filter_ttl($title){return __("Pay by Virtual Currency",'shipme')." - ";}
	add_filter( 'wp_title', 'shipme_filter_ttl', 10, 3 );	
	
	if(!is_user_logged_in()) { wp_redirect(get_bloginfo('siteurl')."/wp-login.php"); exit; }   
	
	$cid 	= $uid;
	$post_pr 	= get_post($pid);
	$catid = shipme_get_job_primary_cat($pid);
	
	//-----------------------------------

	//--------------------------------------------------
	// hide project from search engines fee calculation
	
	$shipme_hide_job_fee = get_option('shipme_hide_job_fee');
	if(!empty($shipme_hide_job_fee))
	{
		$opt = get_post_meta($pid,'hide_project',true);
		if($opt == "0") $shipme_hide_job_fee = 0;
		
		
	} else $shipme_hide_job_fee = 0;
	

	//-------------------------------------------------------------------------------
	// sealed bidding fee calculation
	
	$shipme_sealed_bidding_fee = get_option('shipme_sealed_bidding_fee');
	if(!empty($shipme_sealed_bidding_fee))
	{
		$opt = get_post_meta($pid,'private_bids',true);
		if($opt == "0") { $shipme_sealed_bidding_fee = 0; }
		
		 
	} else $shipme_sealed_bidding_fee = 0;

	
	//-------
	
	$featured	 = get_post_meta($pid, 'featured', true);
	$feat_charge = get_option('shipme_featured_fee');
	
	if($featured != "1" ) $feat_charge = 0;
	
	//update_post_meta($pid, 'featured_paid', 	'0');
	//update_post_meta($pid, 'private_bids_paid', '0');
	//update_post_meta($pid, 'hide_job_paid', '0');
	
	
	$custom_set = get_option('shipme_enable_custom_posting');
				if($custom_set == 'yes')
				{
					$post_pring_fee = get_option('shipme_theme_custom_cat_'.$catid);		
				}
				else
				{
					$post_pring_fee = get_option('shipme_base_fee');
				}
				
	$shipme_get_images_cost_extra = shipme_get_images_cost_extra($pid);
	$total = $shipme_get_images_cost_extra + $feat_charge + $post_pring_fee + $shipme_sealed_bidding_fee + $shipme_hide_job_fee;
	
	//----------------------------------------------
	
		$payment_arr = array();
			
		$base_fee_paid  	= get_post_meta($pid,'base_fee_paid',true);	
		
		if($base_fee_paid != "1"):
				
			$my_small_arr = array();
			$my_small_arr['fee_code'] 		= 'base_fee';
			$my_small_arr['show_me'] 		= true;
			$my_small_arr['amount'] 		= $post_pring_fee;
			$my_small_arr['description'] 	= __('Base Fee','shipme');
			array_push($payment_arr, $my_small_arr);
		//-----------------------
		
		endif;
		
		$my_small_arr = array();
		$my_small_arr['fee_code'] 		= 'extra_img';
		$my_small_arr['show_me'] 		= true;
		$my_small_arr['amount'] 		= $shipme_get_images_cost_extra;
		$my_small_arr['description'] 	= __('Extra Images Fee','shipme');
		array_push($payment_arr, $my_small_arr);
		//------------------------
		
		$featured_paid  	= get_post_meta($pid,'featured_paid',true);
		$opt 				= get_post_meta($pid,'featured',true);
 
		
		if($feat_charge > 0 and $featured_paid != '1' and $opt == 1)
		{
		
			$my_small_arr = array();
			$my_small_arr['fee_code'] 		= 'feat_fee';
			$my_small_arr['show_me'] 		= true;
			$my_small_arr['amount'] 		= $feat_charge;
			$my_small_arr['description'] 	= __('Featured Fee','shipme');
			array_push($payment_arr, $my_small_arr);
		
		}
		
		//------------------------
		
		$private_bids_paid  = get_post_meta($pid,'private_bids_paid',true);
		$opt 				= get_post_meta($pid,'sealed_bidding',true);
 
		
		if($shipme_sealed_bidding_fee > 0 and $private_bids_paid != 1  and ($opt == 1 or $opt == "yes"))
		{
		
			$my_small_arr = array();
			$my_small_arr['fee_code'] 		= 'sealed_project';
			$my_small_arr['show_me'] 		= true;
			$my_small_arr['amount'] 		= $shipme_sealed_bidding_fee;
			$my_small_arr['description'] 	= __('Sealed Bidding Fee','shipme');
			array_push($payment_arr, $my_small_arr);
			
		}
		
		//------------------------
		
		$hide_job_paid 	= get_post_meta($pid,'hide_job_paid',true);
		$opt 				= get_post_meta($pid,'hide_project',true);
		
		if($shipme_hide_job_fee > 0 and $hide_job_paid != "1" and ($opt == "1" or $opt == "yes"))
		{
		
			$my_small_arr = array();
			$my_small_arr['fee_code'] 		= 'hide_project';
			$my_small_arr['show_me'] 		= true;
			$my_small_arr['amount'] 		= $shipme_hide_job_fee;
			$my_small_arr['description'] 	= __('Hide Project From Search Engines Fee','shipme');
			array_push($payment_arr, $my_small_arr);
		
		}
		
		$payment_arr = apply_filters('shipme_filter_payment_array', $payment_arr, $pid);
		$new_total 		= 0;
		
		foreach($payment_arr as $payment_item):			
			if($payment_item['amount'] > 0):				
				$new_total += $payment_item['amount'];			
			endif;			
		endforeach;
		
		
		$total = apply_filters('shipme_filter_payment_total', $new_total, $pid);
			
	//----------------------------------------------
	
	$post_pr 			= get_post($pid);
	$admin_email 	= get_bloginfo('admin_email');


	
	//----------------

	get_header();
	
?>
 
<div class="container_ship_ttl_wrap">	
    <div class="container_ship_ttl">
        <div class="my-page-title col-xs-12 col-sm-12 col-lg-12">
        <?php printf(__("List Item - %s",$current_theme_locale_name), $post_pr->post_title ); ?>       
        </div>
    
        <?php 
    
            if(function_exists('bcn_display'))
            {
                echo '<div class="my_box3 no_padding  breadcrumb-wrap col-xs-12 col-sm-12 col-lg-12"> ';	
                bcn_display();
                echo '</div> ';
            }
        ?>	
    
    </div>
</div>



 


<div class="container_ship_no_bk">
<div class="total-content-area col-xs-12 col-sm-12 col-lg-12">
		
        	<div class="padd10">
        
        	
            <div class="my_box3">
            	<div class="padd10">            
             
                		<div class="box_content"> 



           <div class="post no_border_btm" id="post-<?php the_ID(); ?>">
                
                <div class="image_holder">
                <a href="<?php echo get_permalink($pid); ?>"><img width="45" height="45" class="image_class" 
                src="<?php echo shipme_get_first_post_image($pid,45,45); ?>" /></a>
                </div>
                <div  class="title_holder" > 
                     <h2><a href="<?php echo get_permalink($post_pr->ID) ?>" rel="bookmark" title="Permanent Link to <?php echo $post_pr->post_title; ?>">
                        <?php  echo $post_pr->post_title; ?></a></h2>
      			</div>
                <?php
				
					if(isset($_GET['pay'])):
						echo '<div class="details_holder sk_sk_class">';
						
							$post_pr 	= get_post($pid);
							$cr 		= shipme_get_credits($uid);
							$amount 	= $total;
							
							if($cr < $amount) { echo '<div class="error2">'; echo __('You do not have enough credits to pay for listing this project.','shipme');
							echo '</div><div class="clear10 flt_lft"></div>';
							
							$dep_dep = true;
							$dep_dep = apply_filters('shipme_credits_listing_add_more', $dep_dep);
							if($dep_dep == true):
							?>
                            
							<div class="tripp">
								<a class="post_bid_btn" href="<?php echo shipme_get_payments_page_url_redir('deposit'); ?>"><?php echo __('Add More Credits','shipme'); ?></a>
							</div>
                    
							<?php
                            
								endif;
							}
							else
							{
								
								$paid = get_post_meta($pid, 'paid', true);
								
								
								if($total > 0):
								// echo $pid;
										 
										
										shipme_update_credits($uid, $cr - $amount);
										$reason = sprintf(__('Listing payment for job <a href="%s">%s</a>','shipme'), $perm, $post_pr->post_title);										
										$reason = apply_filters('shipme_reason_listing_project', $reason, $pid);
										
										shipme_add_history_log('0', $reason, $amount, $uid );
										
										//---------------------
										
										update_post_meta($pid, "paid", 				"1");
										update_post_meta($pid, "paid_listing_date", current_time('timestamp',0));
										update_post_meta($pid, "closed", 			"0");
										shipme_mark_images_cost_extra($pid);
										
										//--------------------------------------------
										
										update_post_meta($pid, 'base_fee_paid', '1');
										
										$featured = get_post_meta($pid,'featured',true);	
										if($featured == "1" or $featured == "yes") update_post_meta($pid, 'featured_paid', '1');
										
										$private_bids = get_post_meta($pid,'private_bids',true);	
										if($private_bids == "1" or $private_bids == "yes") update_post_meta($pid, 'private_bids_paid', '1');
										 
										$hide_project = get_post_meta($pid,'hide_project',true);	
										if($hide_project == "1" or $hide_project == "yes") update_post_meta($pid, 'hide_job_paid', '1');
										
										//--------------------------------------------
										
										$shipme_get_images_cost_extra = shipme_get_images_cost_extra($pid);										
										
										$image_fee_paid = get_post_meta($pid, 'image_fee_paid', true);
										update_post_meta($pid, 'image_fee_paid', ($image_fee_paid + $shipme_get_images_cost_extra));
									
										
										//--------------------------------------------
										
										$shipme_admin_approves_each_job = get_option('shipme_admin_approves_each_job');
										
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
								
								
								endif;
								
								if(get_option('shipme_admin_approves_each_job') == 'yes')
								{
									if(shipme_using_permalinks())
									{
										$ppp = (get_permalink(get_option('shipme_account_page_id')) . "?prj_not_approved=" . $pid);	
									}
									else
									{
										$ppp = (get_permalink(get_option('shipme_account_page_id')) . "&prj_not_approved=" . $pid);	
									}
								}
								else	$ppp = get_permalink(get_option('shipme_account_page_id'));
								
								//---------------------						 
								echo sprintf(__('Your payment has been sent. Return to <a href="%s">your account</a>.','shipme'), $ppp );	
							}
							echo '</div>';
				?>
           
                
                <?php else: ?>
                <div class="details_holder sk_sk_class">  
           
                
                <?php
				
				echo '<table style="margin-top:25px">';

	
		foreach($payment_arr as $payment_item):
			
			if($payment_item['amount'] > 0):
			
				echo '<tr>';
				echo '<td>'.$payment_item['description'].'&nbsp; &nbsp;</td>';
				echo '<td>'.shipme_get_show_price($payment_item['amount'],2).'</td>';
				echo '</tr>';

			endif;
			
		endforeach;	
	
	
	echo '<tr>';
	echo '<td>&nbsp;</td>';
	echo '<td></td>';
	echo '<tr>';
	
	
	echo '<tr>';
	echo '<td><strong>'.__('Total to Pay','shipme').'</strong></td>';
	echo '<td><strong>'.shipme_get_show_price($total,2).'</strong></td>';
	echo '<tr>';
	
	echo '</table>';
	
	?>
                
                
                
               <?php _e("Your credits amount",'shipme'); ?>: <?php echo shipme_get_show_price(shipme_get_credits($uid)); ?> <br/><br/>
               <a class="submit_bottom2" href="<?php echo get_bloginfo('siteurl'); ?>/?s_action=credits_listing&pid=<?php echo $pid; ?>&pay=yes"><?php echo __('Pay Now','shipme'); ?></a> 
               
                    
               <?php
			   
			   $dep_dep = true;
							$dep_dep = apply_filters('shipme_credits_listing_add_more', $dep_dep);
							if($dep_dep == true):
			   
			   ?>
               
               <a class="submit_bottom2" href="<?php echo shipme_get_payments_page_url_redir('deposit'); ?>"><?php echo __('Add More Credits','shipme'); ?></a>
               
               <?php endif; ?>
                </div><?php endif; ?>
				</div>


                		</div>
                	</div>
                </div>
            </div></div></div>
                


<?php get_footer(); ?>