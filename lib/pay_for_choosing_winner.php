<?php

      get_header();

      global $wpdb;
      $bidid = $_GET['pay_for_choosing_winner'];

      $s = "select * from ".$wpdb->prefix."ship_bids where id='$bidid'";
      $r = $wpdb->get_results($s);

      if(count($r) == 0) die("thats dead end");
      $row = $r[0];

      $shipme_fee_after_paid = get_option('shipme_fee_after_paid');
      $am = round($shipme_fee_after_paid * 0.01 * $row->bid, 2);


 ?>


<div class="container">

  <div class="ship-pageheader">
              <ol class="breadcrumb ship-breadcrumb">

              </ol>
              <h6 class="ship-pagetitle"><?php _e('Pay before choosing a winner','shipme') ?></h6>
            </div>




            <div class="card p-3">
                  <p><?php echo sprintf(__('Use the payment methods below to pay for the fee. Total amount is: <b>%s</b>','shipme'), shipme_get_show_price($am)) ?></p>

                  <p>
                    <?php

                        $shipme_paypal_enable = get_option('shipme_paypal_enable');
                        if($shipme_paypal_enable == "yes")
                        {
                          ?>

                                <a href="<?php echo get_site_url() ?>/?pay_for_winner_paypal=<?php echo (int)$_GET['pay_for_choosing_winner'] ?>" class="btn btn-primary"><?php _e('Pay by PayPal','shipme') ?></a>
                          <?php
                        }


                     ?>

                        <?php do_action('shipme_pay_before_chosing_winner' , $bidid) ?>

                  </p>

            </div>


</div>



 <?php get_footer() ?>
