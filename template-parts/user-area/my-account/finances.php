<?php



function shipme_theme_my_account_finances_new()
{

  ob_start();
  global $wpdb;

  $user = wp_get_current_user();
  $name = $user->user_login;

  $credits = shipme_get_credits($user->ID);
  $uid = get_current_user_id();

  $pgid = get_option('shipme_finances_page_id');

  $pg = 'home';
  if(empty($_GET['pg'])) $pg = 'home'; else $pg = $_GET['pg'];

  ?>

  <div class="ship-pageheader">
            <ol class="breadcrumb ship-breadcrumb">
              <li class="breadcrumb-item"><a href="<?php echo get_site_url() ?>"><?php echo __('Home','shipme') ?></a></li>
              <li class="breadcrumb-item" ><a href="<?php echo get_permalink(get_option('shipme_account_page_id')) ?>"><?php echo __('My Account','shipme') ?></a></li>
              <li class="breadcrumb-item active" aria-current="page"><?php echo __('Finances','shipme') ?></li>
            </ol>
            <h6 class="ship-pagetitle"><?php printf(__("Finances","shipme") ) ?></h6>
          </div>

          <?php echo shp_account_main_menu(); ?>


<?php

  $bill = get_option('shipme_payment_working_model');
  if($bill == "bill")
  {


    $uid = get_current_user_id();

        ?>
        <div class="row row-xs">
        <div class="col-12 col-lg-12">


<?php
            if(isset($_GET['billid']))
            {

                  $billid = $_GET['billid'];

                ?>


                 <div class="card mb-4 p-3">

                        <p><?php echo __('Use the options below to pay for this bill:','shipme'); ?></p>

                          <div class="mt-4">

                            <?php

                            $shipme_paypal_enable = get_option('shipme_paypal_enable');
                            if($shipme_paypal_enable == "yes")
                            {
                                ?>
                                        <a href="" class="btn btn-primary"><?php _e('Pay by PayPal','shipme')  ?></a>
                                <?php
                            }


                             $shipme_moneybookers_enable = get_option('shipme_moneybookers_enable');
                            if($shipme_moneybookers_enable == "yes")
                            {
                                ?>
                                        <a href="" class="btn btn-primary"><?php _e('Pay by Skrill','shipme')  ?></a>
                                <?php
                            }


                            do_action('shipme_pay_for_bill', $billid);

                             ?>


                          </div>

                 </div>



                <?php

            }
            else {

          ?>



          <div class="ship-pageheader" style="display:block">
            <h6 class="ship-pagetitle"><?php _e('Unpaid Bills','shipme') ?></h6>
          </div>

          <div class="card mb-4 p-3">


                   <?php




                            global $wpdb;
                            $s = "select * from ".$wpdb->prefix."ship_bills_site where uid='$uid' and paid='0'";
                            $r = $wpdb->get_results($s);

                            if(count($r) == 0)
                            {
                                  _e('You do not have any pending bills.','shipme');

                            }
                            else {
                              // code...
                              ?>

                              <div class="row border-bottom">
                                    <div class="col-6 col-md-3 pb-2 pt-2"><?php _e('Project Title','shipme') ?></div>
                                    <div class="col-6 col-md-3 pb-2 pt-2"><?php _e('Amount','shipme') ?></div>
                                    <div class="col-6 col-md-3 pb-2 pt-2"><?php _e('Date','shipme') ?></div>
                                    <div class="col-6 col-md-3 pb-2 pt-2"><?php _e('Payment','shipme') ?></div>
                              </div>

                              <?php

                              $finances_payment_page_id = get_option('shipme_my_account_payments_id');

                              foreach($r as $row)
                              {

                                $project = get_post($row->pid);


                                  ?>

                                  <div class="row border-bottom">
                                        <div class="col-6 col-md-3 pb-2 pt-2"><a href="<?php echo get_permalink($row->pid) ?>"><?php echo $project->post_title ?></a></div>
                                        <div class="col-6 col-md-3 pb-2 pt-2"><?php echo shipme_get_show_price($row->amount) ?></div>
                                        <div class="col-6 col-md-3 pb-2 pt-2"><?php echo date('d-m-Y', $row->datemade) ?></div>
                                        <div class="col-6 col-md-3 pb-2 pt-2"><a href="<?php echo shipme_get_payments_page_url_billid($row->id) ?>" class="btn btn-sm btn-outline-primary"><?php _e('Pay Now','shipme') ?></a></div>
                                  </div>


                                  <?php
                              }
                            }

                    ?>



          </div>


          <div class="ship-pageheader" style="display:block">
            <h6 class="ship-pagetitle"><?php _e('Paid Bills','shipme') ?></h6>
          </div>


              <div class="card mb-4 p-3">


                        <?php

                                 global $wpdb;
                                 $s = "select * from ".$wpdb->prefix."ship_bills_site where uid='$uid' and paid='1'";
                                 $r = $wpdb->get_results($s);

                                 if(count($r) == 0)
                                 {
                                       _e('You do not have any pending bills.','shipme');

                                 }
                                 else {
                                   // code...
                                   ?>

                                   <div class="row border-bottom">
                                         <div class="col-6 col-md-3 pb-2 pt-2"><?php _e('Project Title','shipme') ?></div>
                                         <div class="col-6 col-md-3 pb-2 pt-2"><?php _e('Amount','shipme') ?></div>
                                         <div class="col-6 col-md-3 pb-2 pt-2"><?php _e('Date','shipme') ?></div>
                                         <div class="col-6 col-md-3 pb-2 pt-2"><?php _e('Payment','shipme') ?></div>
                                   </div>

                                   <?php

                                   foreach($r as $row)
                                   {

                                     $project = get_post($row->pid);


                                       ?>

                                       <div class="row border-bottom">
                                             <div class="col-6 col-md-3 pb-2 pt-2"><a href="<?php echo get_permalink($row->pid) ?>"><?php echo $project->post_title ?></a></div>
                                             <div class="col-6 col-md-3 pb-2 pt-2"><?php echo shipme_get_show_price($row->amount) ?></div>
                                             <div class="col-6 col-md-3 pb-2 pt-2"><?php echo date('d-m-Y', $row->datemade) ?></div>
                                             <div class="col-6 col-md-3 pb-2 pt-2"><a href="" class="btn btn-sm btn-outline-primary"><?php _e('Pay Now','shipme') ?></a></div>
                                       </div>


                                       <?php
                                   }
                                 }

                         ?>



              </div>

            <?php } ?>


          </div></div>


        <?php
  }
  else {




  if($pg == "home" or $pg == "withdrawals" or $pg == "transactions")
  {


      $pending_withdrawal = 0;
      $pending_incoming = 0;

      if($_GET['withdrawal-submitted'] == 1)
      {
        echo '<div class="alert alert-success">'.__('Your withdrawal request has ben submitted.','shipme').'</div>';
      }

 ?>


          <div class="row row-xs">
          <div class="col-sm-6 col-lg-3">
            <div class="card card-status">
              <div class="media">

                <div class="media-body">
                  <h1 class="font-color-green"><?php echo shipme_get_show_price($credits) ?></h1>
                  <p><?php _e('Your balance','shipme'); ?></p>
                  <p class='mt-3'><a href="<?php echo shipme_get_link_with_page($pgid, 'deposit'); ?>" class="btn btn-success btn-sm"><?php _e('Deposit','shipme'); ?></a></p>
                </div><!-- media-body -->
              </div><!-- media -->
            </div><!-- card -->
          </div><!-- col-3 -->
          <div class="col-sm-6 col-lg-3 mg-t-10 mg-sm-t-0">
            <div class="card card-status">
              <div class="media">

                <div class="media-body">
                  <h1><?php echo shipme_get_show_price($pending_incoming) ?></h1>
                  <p><?php _e('Pending Incoming','shipme'); ?></p>
                </div><!-- media-body -->
              </div><!-- media -->
            </div><!-- card -->
          </div><!-- col-3 -->
          <div class="col-sm-6 col-lg-3 mg-t-10 mg-lg-t-0">
            <div class="card card-status">
              <div class="media">

                <div class="media-body">
                  <h1><?php echo shipme_get_show_price($pending_withdrawal) ?></h1>
                  <p><?php _e('Pending Withdrawal','shipme'); ?></p>

                </div><!-- media-body -->
              </div><!-- media -->
            </div><!-- card -->
          </div><!-- col-3 -->
          <div class="col-sm-6 col-lg-3 mg-t-10 mg-lg-t-0">
            <div class="card card-status">
              <div class="media">

                <div class="media-body">
                  <h1><?php echo shipme_get_show_price($credits) ?></h1>
                  <p><?php _e('Available for withdrawal','shipme'); ?></p>
                    <p class='mt-3'><a href="<?php echo shipme_get_link_with_page($pgid, 'request-withdrawal'); ?>" class="btn btn-success btn-sm"><?php _e('Request Withdrawal','shipme'); ?></a></p>
                </div><!-- media-body -->
              </div><!-- media -->
            </div><!-- card -->
          </div><!-- col-3 -->
        </div>

        <?php

            $escrow = new shipEscrow();


            $userHasEscrowsPending = $escrow->userHasEscrowsPending(get_current_user_id());
            if($userHasEscrowsPending != false)
            {
              ?>
              <h5><?php _e('Pending escrow payments','shipme') ?></h5>
              <div class="card p-3">


                    <div class="table-responsive">
                        <table class="table table-hover table-outline table-vcenter   card-table">

                          <?php

                          foreach($userHasEscrowsPending as $row)
                          {

                            $order  = new ship_orders($row->oid);
                      			$objectOrder = $order->get_order();
                            $postAd = get_post($objectOrder->pid);

                            $user = get_userdata($row->fromid);

                              ?>

                              <tr>
                                <td><a href="<?php echo get_permalink($postAd->ID) ?>"><?php echo $postAd->post_title ?></a></td>
                                <td><?php echo $user->user_login; ?></td>

                                <td><?php echo shipme_get_show_price($objectOrder->order_total_amount) ?></td>

                              </tr>

                              <?php
                          }

                          ?>

                        </table>

                    </div>

              </div>

              <?php
            }


            $ownerHasEscrows = $escrow->ownerHasEscrows(get_current_user_id());
            if($ownerHasEscrows != false)
            {
              ?>
              <h5><?php _e('Pending escrow payments','shipme') ?></h5>
              <div class="card p-3">


                    <div class="table-responsive">
                        <table class="table table-hover table-outline table-vcenter   card-table">

                          <?php

                          foreach($ownerHasEscrows as $row)
                          {

                            $order  = new ship_orders($row->oid);
                      			$objectOrder = $order->get_order();
                            $postAd = get_post($objectOrder->pid);

                            $freelancer = get_userdata($row->toid);

                              ?>

                              <tr>
                                <td><a href="<?php echo get_permalink($postAd->ID) ?>"><?php echo $postAd->post_title ?></a></td>
                                <td><?php echo $freelancer->user_login; ?></td>

                                <td><?php echo shipme_get_show_price($objectOrder->order_total_amount) ?></td>

                              </tr>

                              <?php
                          }

                          ?>

                        </table>

                    </div>

              </div>

              <?php
            }



        ?>

        <div class="row row-xs">
        <div class="col-sm-12 col-lg-12 mt-3">

                  <ul class="nav nav-tabs">
                    <li class="nav-item">
                      <a class="nav-link <?php echo $pg == 'home' ? 'active' : '' ?>" href="<?php echo shipme_get_link_with_page($pgid, 'home'); ?>"><?php _e('Incoming Payments','shipme'); ?></a>
                    </li>


                    <li class="nav-item">
                      <a class="nav-link <?php echo $pg == 'withdrawals' ? 'active' : '' ?>" href="<?php echo shipme_get_link_with_page($pgid, 'withdrawals'); ?>"><?php _e('Pending Withdrawals','shipme'); ?></a>
                    </li>


                    <li class="nav-item">
                      <a class="nav-link <?php echo $pg == 'transactions' ? 'active' : '' ?>" href="<?php echo shipme_get_link_with_page($pgid, 'transactions'); ?>"><?php _e('Transactions','shipme'); ?></a>
                    </li>

        </ul>


        <div class="card card-square-top font-size-12 ">

          <?php


          if($pg == "home")
          {

              ?>

                    <div class="p-3">There are no incoming payments.</div>

              <?php
          }

          elseif($pg == "withdrawals")
          {
                    $s = "select * from ".$wpdb->prefix."ship_withdraw where uid='$uid' and rejected!='1' and done='0'";
                    $r = $wpdb->get_results($s);

                    if(count($r) > 0)
                    {
                      ?>

                            <table class="table">
                              <thead>
                                <th><?php _e('Method','shipme'); ?></th>
                                <th width="20%"><?php _e('Details','shipme'); ?></th>
                                <th><?php _e('Date Requested','shipme'); ?></th>
                                <th ><?php _e('Amount','shipme'); ?></th>

                              </thead>
                              <tbody>

                      <?php
                          foreach($r as $row)
                          {
                                ?>
                                <tr>
                                <td><?php echo $row->methods; ?></td>
                                <td width="20%"><?php echo $row->payeremail; ?></td>
                                <td><?php echo date_i18n('d-M-Y',$row->datemade) ?></td>
                                <td ><?php echo shipme_get_show_price($row->amount); ?></td>
                              </tr>
                                <?php
                          }
                          ?>
                        </tbody></table>
                          <?php
                    }
                    else {
                      // code...

            ?>

                  <div class="p-3"><?php _e('There are no withdrawals pending.','shipme'); ?></div>

            <?php

            }


          }
          elseif($pg == "transactions")
          {
              global $wpdb;


              $current_page = empty($_GET['pj']) ? 1 : $_GET['pj'];

              $amount_per_page = 10;
              $offset = ($current_page -1)*$amount_per_page;



            $prf = $wpdb->prefix;
            $s = "select SQL_CALC_FOUND_ROWS * from ".$wpdb->prefix."ship_payment_transactions where uid='$uid' order by id desc limit $offset, $amount_per_page";
            $r = $wpdb->get_results($s);

            $total_rows   = shipme_get_last_found_rows();
            $own_pagination = new own_pagination($amount_per_page, $total_rows, shipme_get_project_link_with_page($pgid, 'transactions'). "&");


            ?>

            <?php

            if(count($r) > 0)
            {
                 ?>
                 <div class="p-3"><div class="table-responsive">
                 <table class="table table-hover table-outline table-vcenter   card-table">
                   <thead><tr>

                     <th><?php echo __('Event','shipme'); ?></th>
                     <th><?php echo __('Date','shipme'); ?></th>
                     <th><?php echo __('Amount','shipme') ?></th>
                     <?php do_action('shipme_transactions_add_table_th') ?>
                    </tr></thead><tbody>

                      <?php

                      $now = current_time('timestamp');

                             foreach($r as $row)
                             {

                                        $provider  = get_userdata($row->freelancer);
                                        $pst       = get_post($row->pid);

                                        if($row->tp == 0){ $class="text-danger"; $sign = "-"; }
                                        else { $class="text-success"; $sign = "+"; }

                                 ?>

                                     <tr>
                                           <td> <?php echo $row->reason ?> </td>
                                           <td><?php echo date_i18n('d-M-Y / H:i', $row->datemade) ?></td>
                                           <td class="<?php echo $class ?>"><?php echo $sign.shipme_get_show_price($row->amount, 0) ?></td>
                                              <?php do_action('shipme_transactions_add_table_td', $row) ?>
                                     </tr>
                                 <?php
                             }

                      ?>


                  </tbody>
                </table> </div><?php echo $own_pagination->display_pagination(); ?>  </div>

                 <?php
            }
            else {

            ?>


            <div class="p-3">
            <?php _e('You do not have any transactions.','shipme') ?>
            </div>

            <?php } ?>





            <?php


          }



        ?>



        </div>



              </div>
                    </div>
          <?php }
                elseif($pg == "pay-escrow")
                {

                $oid = $_GET['oid'];
                $order  = new ship_orders($oid);
                $objectOrder = $order->get_order();

                $order_total_amount = $objectOrder->order_total_amount;
                $cr = shipme_get_credits(get_current_user_id());


              ?>

              <div class="card p-3">

                <?php


                      if(isset($_GET['e-wallet']))
                      {

                        if(isset($_GET['confirm']))
                        {

                                $shipEscrow = new shipEscrow($oid);
                                $shipEscrow->setIsCredits();
                                $shipEscrow->createEscrow($order_total_amount, get_current_user_id(), $objectOrder->transporter, 'ewallet');


                              ?>

                                  <div class="alert alert-success">
                                      <?php


                                      $link = get_permalink(get_option('shipme_my_jobs_page')) . "?pg=pending";

                                            echo sprintf(__('Your payment has been submitted. <a href="%s">You can go back</a> to your account','shipme'), $link);

                                       ?>
                                  </div>

                              <?php
                        }
                        else {


                        $pgid   = get_option('shipme_finances_page_id');
                        $lnkEWalletbk    = shipme_get_link_with_page($pgid, 'pay-escrow', '&oid=' . $oid);
                        $lnkEWallet    = shipme_get_link_with_page($pgid, 'pay-escrow', '&e-wallet=yes&confirm=yes&oid=' . $oid);

                          ?>

                          <table class="table">
                            <?php



                             ?>
                              <tbody>
                                <tr>

                                  <td><?php echo __('Total Amount:','shipme') ?></td>
                                  <td><?php echo shipme_get_show_price($order_total_amount) ?></td>
                                </tr>
                                <tr>

                                  <td><?php echo __('Your Credit Amount:','shipme') ?></td>
                                  <td><?php echo shipme_get_show_price($cr) ?></td>
                                </tr>

                              </tbody>
                            </table>

                            <?php

                            if($order_total_amount > $cr)
                            {

                                ?>

                                      <div class="alert alert-warning"><?php echo __('You do not have enough funds in your wallet for this payment.','shipme') ?></div>
                                <?php

                            }

                             ?>

                            <p>
                                    <a href="<?php echo $lnkEWalletbk ?>"  class="btn btn-outline-secondary"><?php echo __('Go Back','shipme') ?></a>
                                    <?php

                                          if($order_total_amount <= $cr)
                                          {

                                              ?>
                                                      <a href="<?php echo $lnkEWallet ?>"  class="btn btn-success"><?php echo __('Confirm Payment','shipme') ?></a>

                                              <?php

                                          }

                                     ?>
                            </p>


                          <?php
                      }
                    }
                      else {


                 ?>

                <p>
                <?php

                    echo sprintf(__('Use the payment methods below to pay for the winner bid and deposit escrow: ','shipme'));

                ?>
                </p>

                <p class="text-success font-weight-bold"><?php echo sprintf(__('Total: %s','shipme'), shipme_get_show_price($order_total_amount)) ?></p>

                <p>
                  <?php
                          $shipme_paypal_enable = get_option('shipme_paypal_enable');
                          if($shipme_paypal_enable == "yes")
                          {


                  ?>
                <a href="<?php echo get_site_url() ?>/?pay_by_paypal_escrow=1&oid=<?php echo $oid ?>" class="btn btn-primary"><?php echo __('Pay by PayPal','shipme') ?></a>
                            <?php } ?>
                            <?php

                                $pgid   = get_option('shipme_finances_page_id');
                                $lnkEWallet    = shipme_get_link_with_page($pgid, 'pay-escrow', '&e-wallet=yes&oid=' . $oid);


                             ?>
                <a href="<?php echo $lnkEWallet ?>" class="btn btn-primary"><?php echo __('Pay by eWallet','shipme') ?></a>

                  <?php do_action('shipme_escrow_options') ?>

                </p>

              <?php } ?>
              </div>

              <?php

          }

                   elseif($pg == "deposit") { ?>

                    <div class="ship-pageheader">
                              <ol class="breadcrumb ship-breadcrumb">
                                    <li class="breadcrumb-item active" aria-current="page"><a href="<?php echo get_permalink(get_option('shipme_finances_page_id')) ?>" class="btn btn-sm btn-outline-primary"><?php echo __('Return to Finances','shipme') ?></a></li>
                              </ol>
                              <h6 class="ship-pagetitle"><?php _e('Deposit','shipme') ?></h6>
                            </div>


                          <div class="row row-xs">
                            <div class="col-sm-12 col-lg-12 mt-3">
                              <div class="card p-3 mb-3">

                                <form method="post" action="<?php echo get_site_url() ?>/?deposit_money_via_paypal=1">


                                    <div class="input-group mb-3">
                                        <div class="w-100 mb-2"><h6><?php _e('Deposit via PayPal','shipme') ?></h6></div>
                                    <div class="input-group-prepend">
                                          <span class="input-group-text"><?php echo shipme_currency() ?></span>
                                        </div>
                                        <input type="double" class="form-control" name="paypal_amount" required  />

                                        </div>


                                        <div class="w-100">
                                                <button type="submit" class="btn btn-primary"><?php _e('Pay Now','shipme') ?></button>
                                        </div>


                                  </form>

                            </div> <!-- end card -->



                            <div class="card p-3 mb-3">

                              <form method="post" action="<?php echo get_site_url() ?>/?deposit_money_via_skrill=1">


                                  <div class="input-group mb-3">
                                      <div class="w-100 mb-2"><h6><?php _e('Deposit via Skrill','shipme') ?></h6></div>
                                  <div class="input-group-prepend">
                                        <span class="input-group-text"><?php echo shipme_currency() ?></span>
                                      </div>
                                      <input type="double" class="form-control" name="paypal_amount" required  />

                                      </div>


                                      <div class="w-100">
                                              <button type="submit" class="btn btn-primary"><?php _e('Pay Now','shipme') ?></button>
                                      </div>


                                </form>

                            </div> <!-- end card -->


                          </div>
                        </div>


                  <?php  } elseif($pg == "request-withdrawal") { ?>


                      <?php
                              $am = shipme_get_credits(get_current_user_id());
                              echo '<div class="alert alert-secondary">'.sprintf(__('Amount available for withdrawal: %s.','shipme'), shipme_get_show_price($am)).'</div>';



                    if($_GET['too-low-amount'] == 1)
                    {
                        echo '<div class="alert alert-danger">'.__('You have insufficient balance. Try to withdraw less.','shipme').'</div>';
                    }

                          $shipme_paypal_enable = get_option('shipme_paypal_enable');
                          if($shipme_paypal_enable == "yes")
                          {



                              ?>
                                    <div class="card p-3 mb-3">
                                      <h5 class="mb-4"><?php _e('Withdraw money via PayPal','shipme'); ?></h5>

                                      <form method="post" action="<?php echo get_site_url() ?>/?withdraw_money_via_paypal=1">
                                        <input type="hidden" value="<?php echo current_time('timestamp') ?>" name="tm" />


                                        <div class="input-group mb-3">
                                            <div class="w-100 mb-2"><h6><?php _e('Your PayPal Email','shipme') ?></h6></div>
                                            <input type="email"   class="form-control" name="paypal_email" required  />

                                            </div>


                                            <div class="input-group mb-3">
                                                <div class="w-100 mb-2"><h6><?php _e('Amount','shipme') ?></h6></div>
                                            <div class="input-group-prepend">
                                                  <span class="input-group-text"><?php echo shipme_currency() ?></span>
                                                </div>
                                                <input type="double"   class="form-control" name="paypal_amount" required  />

                                                </div>


                                              <div class="w-100">
                                                      <button type="submit" class="btn btn-primary"><?php _e('Request Withdrawal','shipme') ?></button>
                                              </div>


                                        </form>


                                    </div>

                              <?php
                          }


                     ?>


                  <?php } ?>
<?php

}

  $page = ob_get_contents();
   ob_end_clean();

   return $page;


}



 ?>
