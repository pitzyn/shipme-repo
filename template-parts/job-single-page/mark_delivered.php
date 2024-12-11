<?php
if(!is_user_logged_in()) { wp_redirect(get_site_url()."/wp-login.php"); exit; }


global $wpdb,$wp_rewrite,$wp_query;
$oid = $wp_query->query_vars['oid'];

$order = new ship_orders($oid);
$order_object = $order->get_order();
$pid = $order_object->pid;

//------------

global $current_user;
$current_user=wp_get_current_user();
$uid = $current_user->ID;

$post_pr = get_post($pid);

//---------------------------

if($uid != $order_object->transporter) { wp_redirect(get_site_url()); exit; }

//---------


if($_GET['yes'] == 'yes')
{
      $order->mark_freelancer_completed();
      shipme_send_email_on_delivered_job_to_owner($pid);
      shipme_send_email_on_delivered_job_to_bidder($pid, $order_object->transporter);




      wp_redirect(shipme_get_link_of_page_and_get_parameter('shipme_my_quotes_page', 'pg=delivered'));
      die();
}


  get_header();

 ?>


 <div class="ship-mainpanel"><div class="container">

   <div class="ship-pageheader">
               <ol class="breadcrumb ship-breadcrumb">
                 <li class="breadcrumb-item"><a href="<?php echo get_site_url() ?>"><?php _e('Home','shipme') ?></a></li>
                   <li class="breadcrumb-item active" aria-current="page"><?php _e('Mark delivered','shipme') ?></li>
                                       </ol>
                             <h6 class="ship-pagetitle"><?php echo __('Mark job delivered','shipme') ?></h6>
                         </div>


                         <div class="section-wrapper mg-t-20">

                               <p class="success-text"><?php _e('You are about to mark this job as delivered.','shipme'); ?></p>
                               <p><a href="<?php echo get_site_url(); ?>?s_action=mark_delivered&oid=<?php echo $oid ?>&yes=yes" class="btn btn-primary"><?php _e('Confirm','shipme'); ?><a>
                               <a href="<?php echo shipme_get_link_of_page_and_get_parameter('shipme_my_jobs_page', 'pg=pending'); ?>" class="btn btn-primary"><?php _e('No, go back','shipme'); ?><a></p>


                         </div>


 </div></div>


 <?php get_footer(); ?>
