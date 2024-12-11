<?php

function shipme_theme_my_account_my_jobs_fnc()
{

  ob_start();
  global $wpdb;
  $uid = get_current_user_id();

  $user = wp_get_current_user();
  $name = $user->user_login;

  $pgid = get_option('shipme_my_jobs_page');

  $pg = 'home';
  if(empty($_GET['pg'])) $pg = 'home'; else $pg = $_GET['pg'];

  $date_format = get_option('date_format');


  //-------

  $current_page = empty($_GET['pj']) ? 1 : $_GET['pj'];

  $amount_per_page = 10;
  $offset = ($current_page -1)*$amount_per_page;

  $active_quotes = shipme_count_active_quotes($uid);

  if($active_quotes > 0)
  $active_quotes = '<span class="badge badge-danger">'.$active_quotes."</span>";
  else $active_quotes = '';

  //--------

  $shipme_get_number_of_delivered_of_my_jobs = shipme_get_number_of_delivered_of_my_jobs($uid);
  if($shipme_get_number_of_delivered_of_my_jobs > 0)
  $deljb = '<span class="badge badge-danger">'.$shipme_get_number_of_delivered_of_my_jobs."</span>";
  else $deljb = '';




  if(isset($_GET['compok']))
  {
     ?>

     <div class="mt-4 alert alert-success" id="success-alert"><?php _e('Operation was successful','shipme') ?></div>

     <script>

     jQuery(document).ready(function() {

           jQuery("#success-alert").fadeTo(2000, 500).slideUp(500, function() {
             jQuery("#success-alert").slideUp(800);
           });

           });

     </script>

     <?php
  }



  $shipme_count_in_progress_jobs_owner = shipme_count_in_progress_jobs_owner($uid);
  if($shipme_count_in_progress_jobs_owner > 0) $inProgCnt =  '<span class="badge badge-danger">' . $shipme_count_in_progress_jobs_owner . '</span>'


  ?>

  <div class="ship-pageheader">
            <ol class="breadcrumb ship-breadcrumb">
              <li class="breadcrumb-item"><a href="<?php echo get_site_url() ?>"><?php echo __('Home','shipme') ?></a></li>
              <li class="breadcrumb-item" ><a href="<?php echo get_permalink(get_option('shipme_account_page_id')) ?>"><?php echo __('My Account','shipme') ?></a></li>
              <li class="breadcrumb-item active" aria-current="page"><?php echo __('My Jobs','shipme') ?></li>
            </ol>
            <h6 class="ship-pagetitle"><?php printf(__("My Jobs","shipme") ) ?></h6>
          </div>

          <?php echo shp_account_main_menu(); ?>



          <ul class="nav nav-tabs" id="myTab-main" role="tablist">

            <li class="nav-item">
              <a class="nav-link <?php echo $pg == 'home' ? 'active' : '' ?>" id="home-tab"  href="<?php echo shipme_get_link_with_page($pgid, 'home'); ?>" ><?php _e('Active Jobs','shipme') ?></a>
            </li>

            <li class="nav-item">
              <a class="nav-link <?php echo $pg == 'quotes' ? 'active' : '' ?>" id="home-tab"  href="<?php echo shipme_get_link_with_page($pgid, 'quotes'); ?>" ><?php printf(__('Active Quotes %s','shipme'), $active_quotes) ?></a>
            </li>
            <li class="nav-item">
              <a class="nav-link <?php echo $pg == 'pending' ? 'active' : '' ?>" id="profile-tab" href="<?php echo shipme_get_link_with_page($pgid, 'pending'); ?>"><?php printf(__('Pending Jobs %s','shipme'), $inProgCnt) ?></a>
            </li>
            <li class="nav-item">
              <a class="nav-link <?php echo $pg == 'delivered' ? 'active' : '' ?>" id="contact-tab" href="<?php echo shipme_get_link_with_page($pgid, 'delivered'); ?>"><?php echo sprintf(__('Delivered %s','shipme'), $deljb) ?></a>
            </li>

            <li class="nav-item">
              <a class="nav-link <?php echo $pg == 'completed' ? 'active' : '' ?>" id="contact-tab" href="<?php echo shipme_get_link_with_page($pgid, 'completed'); ?>"><?php _e('Completed','shipme') ?></a>
            </li>


            <li class="nav-item">
              <a class="nav-link <?php echo $pg == 'cancelled' ? 'active' : '' ?>" id="contact-tab" href="<?php echo shipme_get_link_with_page($pgid, 'cancelled'); ?>"><?php _e('Cancelled','shipme') ?></a>
            </li>


            <li class="nav-item">
              <a class="nav-link <?php echo $pg == 'unpublished' ? 'active' : '' ?>" id="contact-tab" href="<?php echo shipme_get_link_with_page($pgid, 'unpublished'); ?>"><?php _e('Unpublished','shipme') ?></a>
            </li>

          </ul>

<div class="card card-square-top font-size-12 ">

<?php



if($pg == "home")
{

   $prf = $wpdb->prefix;
   $s = "select SQL_CALC_FOUND_ROWS * from ".$prf."postmeta pmeta, ".$prf."posts posts where posts.ID=pmeta.post_id and posts.post_type='job_ship' and
   posts.post_status='publish' and posts.post_author='$uid' and pmeta.meta_key='closed' and pmeta.meta_value='0' order by posts.ID desc limit $offset, $amount_per_page";
   $r = $wpdb->get_results($s);

   $total_rows   = shipme_get_last_found_rows();
   $own_pagination = new own_pagination($amount_per_page, $total_rows, shipme_get_link_with_page($pgid, 'home'). "&");



?>



<?php

   if(count($r) > 0)
   {
         ?>
         <div class="p-3 table-responsive">
         <table class="table table-hover table-outline table-vcenter   card-table">
           <thead><tr>

             <th><?php echo __('Job Title','shipme'); ?></th>
             <th><?php echo __('Budget','shipme') ?></th>
             <th><?php echo __('Date Made','shipme') ?></th>
             <th><?php echo __('Quotes','shipme') ?></th>
             <th><?php echo __('Options','shipme') ?></th>

            </tr></thead><tbody>

              <?php

                     foreach($r as $row)
                     {



                         ?>

                             <tr>
                                   <td><a href="<?php echo get_permalink($row->ID) ?>"><?php echo $row->post_title ?></a><br/>
                                        <small class="nb-padd"><?php printf(__('You have %s active proposals.','shipme'), shipme_number_of_bid($row->ID)) ?></small>
                                   </td>
                                   <td class='text-success'><?php echo shipme_get_show_price(get_post_meta($row->ID, 'price', true)) ?></td>
                                   <td><?php echo date_i18n($date_format, $row->date_made) ?></td>
                                   <td><?php echo  shipme_number_of_bid($row->ID) ?></td>
                                   <td><a href="<?php echo get_the_permalink( $row->ID ); ?>" class='btn btn-outline-primary btn-sm'><?php echo __('View Job','shipme') ?></a> 
                                  <a href="<?php echo get_the_permalink( $row->ID ); ?>" class='btn btn-outline-primary btn-sm'><?php echo __('Remove Job','shipme') ?></a>
                                </td>
                             </tr>
                         <?php
                     }

              ?>


          </tbody>
         </table>

         <?php echo $own_pagination->display_pagination(); ?>
       </div>

         <?php
   }
   else {

?>


<div class="p-3">
 <?php echo sprintf(__('You do not have any active jobs. <a href="%s">Click here</a> to post more.','shipme'), get_permalink(get_option('shipme_post_new_page_id'))) ?>
</div>

<?php } ?>


<?php } elseif('quotes' == $pg){

$prf = $wpdb->prefix;
$s = "select SQL_CALC_FOUND_ROWS * from ".$prf."ship_bids bids, ".$prf."postmeta pmeta, ".$prf."posts posts where posts.ID=pmeta.post_id and posts.post_type='job_ship' and
posts.post_status='publish' and posts.post_author='$uid' and pmeta.meta_key='winner' and pmeta.meta_value='0' and bids.pid=posts.ID order by posts.ID desc limit $offset, $amount_per_page";
$r = $wpdb->get_results($s);


$total_rows   = shipme_get_last_found_rows();
$own_pagination = new own_pagination($amount_per_page, $total_rows, shipme_get_link_with_page($pgid, 'quotes'). "&");

?>


<?php

 if(count($r) > 0)
 {
       ?>
       <div class="p-3 table-responsive">
       <table class="table table-hover table-outline table-vcenter   card-table">
         <thead><tr>

           <th><?php echo __('Job Title','shipme'); ?></th>
           <th><?php echo __('Provider','shipme'); ?></th>
           <th><?php echo __('Quote','shipme') ?></th>
           <th><?php echo __('Date Made','shipme') ?></th>
           <th><?php echo __('Options','shipme') ?></th>

          </tr></thead><tbody>

            <?php

                   foreach($r as $row)
                   {

                              $provider = get_userdata($row->uid);



                       ?>

                           <tr>
                                 <td><a href="<?php echo get_permalink($row->ID) ?>"><?php echo $row->post_title ?></a></td>
                                 <td><a href="<?php echo shipme_get_user_profile_link($provider->ID) ?>"><?php echo $provider->user_login ?></a></td>
                                 <td class='text-success'><?php echo shipme_get_show_price($row->bid, 0) ?></td>
                                 <td><?php echo date_i18n($date_format, $row->date_made) ?></td>
                                 <td><a href="<?php echo get_the_permalink( $row->ID ); ?>" class='btn btn-outline-primary btn-sm'><?php echo __('View Job','shipme') ?></a>
                                 <a href="<?php echo get_the_permalink( $row->ID ); ?>" class='btn btn-outline-success btn-sm'><?php echo __('Choose Winner','shipme') ?></a></td>
                           </tr>
                       <?php
                   }

            ?>


        </tbody>
       </table> <?php echo $own_pagination->display_pagination(); ?> </div>

       <?php
 }
 else {

?>


<div class="p-3">
<?php _e('You do not have any active quotes.','shipme') ?>
</div>

<?php } ?>



<?php

}
elseif('pending' == $pg)
{
$uid = get_current_user_id();
global $wpdb; $wpdb->show_errors = true;

$prf = $wpdb->prefix;
$s = "select SQL_CALC_FOUND_ROWS * from ".$prf."ship_orders orders where orders.buyer='$uid' and order_status='0' order by id desc limit $offset, $amount_per_page";
$r = $wpdb->get_results($s);


$total_rows   = shipme_get_last_found_rows();
$own_pagination = new own_pagination($amount_per_page, $total_rows, shipme_get_link_with_page($pgid, 'pending'). "&");


?>


<?php

if(count($r) > 0)
{
  ?>
  <div class="p-3 table-responsive">
  <table class="table table-hover table-outline table-vcenter   card-table">
    <thead><tr>

      <th><?php echo __('Job Title','shipme'); ?></th>
      <th><?php echo __('Transporter','shipme'); ?></th>
      <th><?php echo __('Price','shipme') ?></th>
      <th><?php echo __('Date Made','shipme') ?></th>
      <th><?php echo __('Options','shipme') ?></th>

     </tr></thead><tbody>

       <?php

       $now = current_time('timestamp');

              foreach($r as $row)
              {

                         $provider  = get_userdata($row->transporter);
                         $pst       = get_post($row->pid);

                  ?>

                      <tr>
                            <td><a href="<?php echo get_permalink($pst->ID) ?>"><?php echo $pst->post_title ?></a>
                              <?php

                              $shipme_payment_working_model = get_option('shipme_payment_working_model');

                              if($shipme_payment_working_model == "site_payments")
                              {

                                    $order  = new ship_orders($row->id);
                                    $pgid   = get_option('shipme_finances_page_id');
                                    $lnk    = shipme_get_link_with_page($pgid, 'pay-escrow', '&oid=' . $row->id);

                                    if($order->has_escrow_deposited() == false)
                                    {
                                        echo '<br/><small class="">'.sprintf(__('The escrow has not been deposited yet. <a href="%s">Click here</a> to deposit escrow.','shipme'), $lnk) . '</small>';
                                    }
                                    else {
                                      $obj = $order->get_escrow_object();
                                      echo '<div class="mylaertwarning2">' . __('Escrow was deposited for this job.','shipme') . '</div>';
                                    }

                                }
                                else {
                                  // code...
                                  echo '<br/><small>' . __('The payment for the job is taken outside of the website.','shipme') . '</small>';

                                }

                                    echo '<div class="mylaertwarning">' . __('Waiting for the transporter to deliver work.','shipme') . '</div>';

                               ?>

                            </td>
                            <td><a href="<?php echo shipme_get_user_profile_link($provider->ID) ?>"><?php echo $provider->user_login ?></a></td>
                            <td class='text-success'><?php echo shipme_get_show_price($row->order_total_amount, 0) ?></td>
                            <td><?php echo date_i18n($date_format, $row->datemade) ?></td>
                            <td><a href="<?php echo shipme_get_order_page( $row->id ); ?>" class='btn btn-outline-primary btn-sm'><?php echo __('Order page','shipme') ?></a></td>
                      </tr>
                  <?php
              }

       ?>


   </tbody>
  </table> <?php echo $own_pagination->display_pagination(); ?>  </div>

  <?php
}
else {

?>


<div class="p-3">
<?php _e('You do not have any active jobs.','shipme') ?>
</div>

<?php } ?>


<?php }elseif($pg == 'delivered'){


$prf = $wpdb->prefix;
$s = "select SQL_CALC_FOUND_ROWS * from ".$prf."ship_orders orders where orders.buyer='$uid' and order_status='1' order by id='desc' limit $offset, $amount_per_page";
$r = $wpdb->get_results($s);

$total_rows   = shipme_get_last_found_rows();
$own_pagination = new own_pagination($amount_per_page, $total_rows, shipme_get_link_with_page($pgid, 'delivered'). "&");




?>


<?php

if(count($r) > 0)
{
  ?>
  <div class="p-3 table-responsive">
  <table class="table table-hover table-outline table-vcenter   card-table">
    <thead><tr>

      <th><?php echo __('Job Title','shipme'); ?></th>
      <th><?php echo __('Provider','shipme'); ?></th>
      <th><?php echo __('Price','shipme') ?></th>
      <th><?php echo __('Date Made','shipme') ?></th>
      <th><?php echo __('Completed On','shipme') ?></th>
      <th><?php echo __('Options','shipme') ?></th>

     </tr></thead><tbody>

       <?php

       $now = current_time('timestamp');

              foreach($r as $row)
              {

                         $provider  = get_userdata($row->transporter);
                         $pst       = get_post($row->pid);

                  ?>

                      <tr>
                            <td><a href="<?php echo get_permalink($row->ID) ?>"><?php echo $pst->post_title ?></a></td>
                            <td><a href="<?php echo shipme_get_user_profile_link($provider->ID) ?>"><?php echo $provider->user_login ?></a></td>
                            <td class='text-success'><?php echo shipme_get_show_price($row->order_total_amount, 0) ?></td>
                            <td><?php echo date_i18n($date_format, $row->datemade) ?></td>
                            <td><?php echo  date_i18n($date_format, $row->marked_done_transporter) ?></td>
                            <td>
                              <div class="dropdown z1x1x2">
                                <button class="btn btn-secondary dropdown-toggle dropdown-functions-settings" type="button" id="dropdownMenuButton" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                                      <i class="fas fa-cog"></i></button>
                                          <div class="dropdown-menu" id="options-thing-sale" aria-labelledby="dropdownMenuButton">
                                              <a class="dropdown-item" href="<?php echo shipme_get_order_page($row->id) ?>"><?php echo __('Order page','shipme') ?></a>

                    <a class="dropdown-item" href="<?php echo get_site_url(); ?>/?s_action=mark_completed&oid=<?php echo $row->id; ?>"><?php echo __('Mark Completed','shipme') ?></a>
                          </div>
                        </div>

                      </tr>
                  <?php
              }

       ?>


   </tbody>
 </table> <?php echo $own_pagination->display_pagination(); ?>  </div>

  <?php
}
else {

?>


<div class="p-3">
<?php _e('You do not have any delivered jobs.','shipme') ?>
</div>

<?php } ?>

<?php }elseif($pg == 'cancelled'){

$prf = $wpdb->prefix;
$s = "select SQL_CALC_FOUND_ROWS * from ".$prf."ship_orders orders where orders.buyer='$uid' and order_status='3' order by id='desc' limit $offset, $amount_per_page";
$r = $wpdb->get_results($s);

$total_rows   = shipme_get_last_found_rows();
$own_pagination = new own_pagination($amount_per_page, $total_rows, shipme_get_link_with_page($pgid, 'cancelled'). "&");



?>


<?php

if(count($r) > 0)
{
  ?>
  <div class="p-3 table-responsive">
  <table class="table table-hover table-outline table-vcenter   card-table">
    <thead><tr>

      <th><?php echo __('Job Title','shipme'); ?></th>
      <th><?php echo __('Provider','shipme'); ?></th>
      <th><?php echo __('Price','shipme') ?></th>
      <th><?php echo __('Date Started','shipme') ?></th>
      <th><?php echo __('Cancelled On','shipme') ?></th>
      <th><?php echo __('Options','shipme') ?></th>

     </tr></thead><tbody>

       <?php

       $now = current_time('timestamp');

              foreach($r as $row)
              {

                         $provider  = get_userdata($row->transporter);
                         $pst       = get_post($row->pid);

                  ?>

                      <tr>
                            <td><a href="<?php echo get_permalink($row->ID) ?>"><?php echo $pst->post_title ?></a></td>
                            <td><a href="<?php echo shipme_get_user_profile_link($provider->ID) ?>"><?php echo $provider->user_login ?></a></td>
                            <td class='text-success'><?php echo shipme_get_show_price($row->order_total_amount, 0) ?></td>
                            <td><?php echo date_i18n($date_format, $row->datemade) ?></td>
                            <td><?php echo  date_i18n($date_format, $row->marked_done_buyer) ?></td>
                            <td>-</td>
                      </tr>
                  <?php
              }

       ?>


   </tbody>
  </table> </div>

  <?php
}
else {

?>


<div class="p-3">
<?php _e('You do not have any cancelled jobs.','shipme') ?>
</div>

<?php } ?>


<?php }elseif($pg == 'completed'){


$prf = $wpdb->prefix;
$s = "select SQL_CALC_FOUND_ROWS * from ".$prf."ship_orders orders where orders.buyer='$uid' and order_status='2' order by id='desc' limit $offset, $amount_per_page";
$r = $wpdb->get_results($s);

$total_rows   = shipme_get_last_found_rows();
$own_pagination = new own_pagination($amount_per_page, $total_rows, shipme_get_link_with_page($pgid, 'completed'). "&");



?>


<?php

if(count($r) > 0)
{
  ?>
  <div class="p-3 table-responsive">
  <table class="table table-hover table-outline table-vcenter   card-table">
    <thead><tr>

      <th><?php echo __('Job Title','shipme'); ?></th>
      <th><?php echo __('Provider','shipme'); ?></th>
      <th><?php echo __('Price','shipme') ?></th>
      <th><?php echo __('Date Started','shipme') ?></th>
      <th><?php echo __('Accepted On','shipme') ?></th>
      <th><?php echo __('Options','shipme') ?></th>

     </tr></thead><tbody>

       <?php

       $now = current_time('timestamp');

              foreach($r as $row)
              {

                         $provider  = get_userdata($row->transporter);
                         $pst       = get_post($row->pid);

                  ?>

                      <tr>
                            <td><a href="<?php echo get_permalink($row->pid) ?>"><?php echo $pst->post_title ?></a></td>
                            <td><a href="<?php echo shipme_get_user_profile_link($provider->ID) ?>"><?php echo $provider->user_login ?></a></td>
                            <td class='text-success'><?php echo shipme_get_show_price($row->order_total_amount, 0) ?></td>
                            <td><?php echo date_i18n($date_format, $row->datemade) ?></td>
                            <td><?php echo  date_i18n($date_format, $row->marked_done_buyer) ?></td>
                            <td><a href="<?php echo shipme_get_order_page($row->id) ?>" class="btn btn-outline-primary btn-sm"><?php _e('Order page','shipme'); ?></a></td>
                      </tr>
                  <?php
              }

       ?>


   </tbody>
  </table><?php echo $own_pagination->display_pagination(); ?>  </div>

  <?php
}
else {

?>


<div class="p-3">
<?php _e('You do not have any active jobs.','shipme') ?>
</div>

<?php } ?>



<?php }  elseif($pg == 'unpublished'){

$prf = $wpdb->prefix;
$s = "select SQL_CALC_FOUND_ROWS * from ".$prf."postmeta pmeta, ".$prf."posts posts where posts.ID=pmeta.post_id and posts.post_type='job_ship' and
posts.post_status='draft' and posts.post_author='$uid' and pmeta.meta_key='closed' and pmeta.meta_value='0' order by posts.ID desc limit $offset, $amount_per_page";
$r = $wpdb->get_results($s);

$total_rows   = shipme_get_last_found_rows();
$own_pagination = new own_pagination($amount_per_page, $total_rows, shipme_get_link_with_page($pgid, 'home'). "&");



?>


<?php

if(count($r) > 0)
{
?>
<div class="p-3 table-responsive">
<table class="table table-hover table-outline table-vcenter   card-table">
<thead><tr>

  <th><?php echo __('Job Title','shipme'); ?></th>
  <th><?php echo __('Budget','shipme') ?></th>
  <th><?php echo __('Date Made','shipme') ?></th>
  <th><?php echo __('Quotes','shipme') ?></th>
  <th><?php echo __('Options','shipme') ?></th>

 </tr></thead><tbody>

   <?php

          foreach($r as $row)
          {



              ?>

                  <tr>
                        <td><a href="<?php echo get_permalink($row->ID) ?>"><?php echo $row->post_title ?></a></td>
                        <td class='text-success'><?php echo shipme_get_show_price(get_post_meta($row->ID, 'price', true)) ?></td>
                        <td><?php echo date_i18n($date_format, $row->date_made) ?></td>
                        <td><?php echo  shipme_number_of_bid($row->ID) ?></td>
                        <td><a href="<?php echo get_the_permalink( $row->ID ); ?>" class='btn btn-outline-primary btn-sm'><?php echo __('Publish','shipme') ?></a></td>
                  </tr>
              <?php
          }

   ?>


</tbody>
</table>

<?php echo $own_pagination->display_pagination(); ?>
</div>

<?php
}
else {

?>


<div class="p-3">
<?php _e('You do not have any unpublished jobs.','shipme') ?>
</div>

<?php } ?>



<?php } ?>



</div>



  <?php



    $page = ob_get_contents();
     ob_end_clean();

     return $page;



}


 ?>
