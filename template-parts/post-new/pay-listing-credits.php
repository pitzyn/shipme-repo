<?php


function shipme_theme_pay_listing_credits_function_fn()
{


    ob_start();


    $pid = $_GET['pid'];
    $pst = get_post($pid);

    if(get_current_user_id() != $pst->post_author) { exit; }

?>



<div class="ship-pageheader">
          <ol class="breadcrumb ship-breadcrumb">
            <li class="breadcrumb-item"><a href="<?php echo get_site_url() ?>"><?php echo __('Home','shipme') ?></a></li>
            <li class="breadcrumb-item active" aria-current="page"><?php echo __('Pay by eWallet','shipme') ?></li>
          </ol>
          <h6 class="ship-pagetitle"><?php printf(__("Pay for listing by eWallet","shipme") ) ?></h6>
        </div>





        <div class="section-wrapper mg-t-20">

          <?php

                if(!empty($_GET['yes']) and $_GET['yes'] == "yes")
                {
                  $uid = get_current_user_id();
                  $fees = new shipme_listing_fees($pid);
                  $total_to_pay = $fees->get_fees_still_to_pay();



                  $shipme_admin_approves_each_job = get_option('shipme_admin_approves_each_job');
                  if($shipme_admin_approves_each_job == "yes")
                  {

                      if($total_to_pay > 0)
                      {

                          $my_post = array();
                          $my_post['ID'] = $pid;
                          $my_post['post_status'] = 'draft';

                          wp_update_post( $my_post );

                          shipme_send_email_posted_job_not_approved($pid);
                          shipme_send_email_posted_job_not_approved_admin($pid);
                          $pst = get_post($pid);

                          $reason = sprintf(__('Listing fee for the job <b>%s</b>','shipme'), $pst->post_title );
                          $credits = new shipme_credits($uid);
                          $credits->remove_amount_credits($total_to_pay, $reason);

                          $fees->mark_things_paid();
           

                      }

                        ?>
                                <div class="alert alert-success"><?php echo sprintf(__('You have successfully paid for listing of your job, however the job needs admin moderation. When our admins will approve your job, it will appear in front end area.','shipme')) ?></div>
                                  <div class="w-100">
                        <?php
                  }
                  else {

                    if($total_to_pay > 0)
                    {
                        $my_post = array();
                        $my_post['ID']          = $pid;
                        $my_post['post_status'] = 'publish';

                        wp_update_post( $my_post );
                        wp_publish_post( $pid );

                        shipme_send_email_posted_job_approved($pid);
                        shipme_send_email_posted_job_approved_admin($pid);

                        if(function_exists('shipme_send_email_subscription'))
                        shipme_send_email_subscription($pid);

                        $pst = get_post($pid);

                        $reason = sprintf(__('Listing fee for the job <b>%s</b>','shipme'), $pst->post_title );
                        $credits = new shipme_credits($uid);
                        $credits->remove_amount_credits($total_to_pay, $reason);

                        $fees->mark_things_paid();

                    }


                      ?>
                            <div class="alert alert-success"><?php echo sprintf(__('You have successfully paid for listing of your job. Your job is now live.','shipme'), $crd) ?></div>

                          <div class="w-100">  <a href="<?php echo get_permalink( $pid ); ?>" class="btn btn-primary"><?php _e('See your job','shipme') ?></a>

                      <?php
                  }

                        ?>





                              <a href="<?php echo get_permalink(get_option('shipme_account_page_id')) ?>" class="btn btn-primary"><?php _e('Go to your account','shipme') ?></a>

                            </div>

                        <?php
                }

                else {



           ?>


              <?php


              $array    = ship_calculate_listing_fees_of_job($pid);
              $total    = $array['total'];
              $credits  = new shipme_credits(get_current_user_id());
              $credits_val = $credits->get_credits();

              $crd = shipme_get_show_price($credits_val);

               ?>
<div>

      <h6 class="text-success"><?php echo sprintf(__('You have %s left in your eWallet balance.','shipme'), $crd) ?></h6>

</div>

                                   <div class="table-responsive mg-t-40">
                             <table class="table table-invoice">
                               <thead>
                                 <tr>
                                   <th class="wd-80p"><?php _e('Type','shipme') ?></th>
                                   <th class="tx-right"><?php _e('Amount','shipme') ?></th>
                                 </tr>
                               </thead>
                               <tbody>

                                 <?php


                                       foreach($array['arr'] as $elem)
                                       {

                                           ?>

                                           <tr>
                                             <td><?php echo $elem['description'] ?></td>
                                             <td class="tx-right"><?php echo shipme_get_show_price($elem['amount']) ?></td>
                                           </tr>


                                           <?php

                                       }


                                  ?>




                                 <tr>

                                   <td class="tx-right"><?php _e('Sub-Total','shipme') ?></td>
                                   <td colspan="2" class="tx-right"><?php echo shipme_get_show_price($total) ?></td>
                                 </tr>

                                 <?php



                                     $shipme_tax_1_val   = get_option('shipme_tax_1_val');
                                     $shipme_tax_1_name  = get_option('shipme_tax_1_name');

                                     if($shipme_tax_1_val > 0)
                                     {

                                         $total2 = round($total*0.01*$shipme_tax_1_val, 2);
                                  ?>

                                 <tr>
                                   <td class="tx-right"><?php echo $shipme_tax_1_name; ?> (<?php echo $shipme_tax_1_val ?>%)</td>
                                   <td colspan="2" class="tx-right"><?php echo shipme_get_show_price($total2) ?></td>
                                 </tr>

                               <?php } ?>

                                 <tr>
                                   <td class="tx-right tx-uppercase tx-bold tx-inverse"><?php _e('Total Due','shipme') ?></td>
                                   <td colspan="2" class="tx-right"><h4 class="tx-primary tx-bold tx-lato"><?php echo shipme_get_show_price($total2+$total) ?></h4></td>
                                 </tr>
                               </tbody>
                             </table>
                           </div>


                           <div>

                              <h6 class="pb-3"><?php _e('You are about to pay for listing fees using your eWallet balance.','shipme'); ?></h5>

                               <a class="btn btn-oblong btn-secondary bd-0" href='<?php echo shipme_post_new_with_pid_stuff_thg($pid, '4');?>'><?php _e('Go Back','shipme'); ?></a>

                                <a <?php echo $credits_val < ($total2+$total) ? "disabled='disabled'" : "" ?> class="btn btn-oblong btn-primary bd-0" href='<?php echo shipme_get_post_new_pay_balance($pid, '&yes=yes');?>'><?php _e('Agree and Proceed','shipme'); ?></a>

                              </div>


                            <?php } ?>
        </div>

<?php


        $page = ob_get_contents();
         ob_end_clean();

         return $page;

  }




 ?>
