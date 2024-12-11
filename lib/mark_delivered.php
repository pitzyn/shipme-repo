<?php
 

if(!is_user_logged_in()) { wp_redirect(get_bloginfo('url')."/wp-login.php"); exit; }
//-----------

	add_filter('sitemile_before_footer', 'ShipMe_my_account_before_footer');
	function ShipMe_my_account_before_footer()
	{
		echo '<div class="clear10"></div>';		
	}
	
	//----------

	global $wpdb,$wp_rewrite,$wp_query;
	$pid = $wp_query->query_vars['pid'];

	global $current_user;
	get_currentuserinfo();
	$uid = $current_user->ID;

	$post_pr = get_post($pid);
	
	//---------------------------
	
	$winner_bd = ShipMe_get_winner_bid($pid);
	if($uid != $winner_bd->uid) { wp_redirect(get_bloginfo('url')); exit; }
	
	//---------------------------
	
	if(isset($_POST['yes']))
	{
		$tm = current_time('timestamp',0);
		
		$mark_coder_delivered = get_post_meta($pid, 'mark_transporter_delivered', true);
		
		if(empty($mark_coder_delivered))
		{
		
			update_post_meta($pid, 'mark_transporter_delivered',		"1");
			update_post_meta($pid, 'mark_transporter_delivered_date',		$tm);
			
			//------------------------------------------------------------------------------
			
			ShipMe_send_email_on_delivered_job_to_bidder($pid, $uid);
			ShipMe_send_email_on_delivered_job_to_owner($pid);
		
		}
		
		wp_redirect(get_permalink(get_option('shipme_pending_delivery_page_id')));
		exit;
	}
	
	if(isset($_POST['no']))
	{
		wp_redirect(get_permalink(get_option('shipme_pending_delivery_page_id')));
		exit;			
	}
	

	//---------------------------------
	
	get_header();

?>
                <div class="page_heading_me">
                        <div class="page_heading_me_inner">
                        	<div class="main-pg-title">
                            	<div class="mm_inn"> <?php  printf(__("Mark the project delivered: %s",'ShipMe'), $post_pr->post_title); ?></div>
                  	            
                            </div>            
                        </div>
                    
                    </div> 
<!-- ########## -->

<div id="main_wrapper">
		<div id="main" class="wrapper"><div class="padd10">

	
			<div class="my_box3">
            	<div class="padd10">
            
             
                <div class="box_content">   
               <?php
			   
			   printf(__("You are about to mark this job as delivered: %s",'ShipMe'), $post_pr->post_title); echo '<br/>';
			  _e("The job owner will be notified to accept the delivery and get you paid.",'ShipMe') ;
			   
			   ?> 
                
                <div class="clear10"></div>
               
               <form method="post"  > 
                
               <input type="submit" name="yes" value="<?php _e("Yes, Mark Delivered!",'ShipMe'); ?>" />
               <input type="submit" name="no"  value="<?php _e("No",'ShipMe'); ?>" />
                
               </form>
    </div>
			</div>
			</div>
        
        
        <div class="clear100"></div>
            
    
    </div></div></div>
            
<?php

get_footer();

?>                        