<?php

function shipme_theme_my_account_order_page_fnc()
{


    ob_start();
    global $wpdb;

    $oid = (int)$_GET['oid'];
    $order = new ship_orders($oid);

    $orderObj = $order->get_order();
    $postA = get_post($orderObj->pid);
    $transporter = get_userdata($orderObj->transporter);



    ?>



      <div class="ship-pageheader">
                <ol class="breadcrumb ship-breadcrumb">
                  <li class="breadcrumb-item"><a href="<?php echo get_site_url() ?>"><?php echo __('Home','shipme') ?></a></li>
                  <li class="breadcrumb-item" ><a href="<?php echo get_permalink(get_option('shipme_account_page_id')) ?>"><?php echo __('My Account','shipme') ?></a></li>
                  <li class="breadcrumb-item active" aria-current="page"><?php echo __('Order page','shipme') ?></li>
                </ol>
                <h6 class="ship-pagetitle"><?php printf(__("Order Page","shipme") ) ?></h6>
              </div>

              <?php echo shp_account_main_menu(); ?>


              <div class="row">

                    <div class="col-12 col-md-4">
                          <div class="card p-3 card-square-top font-size-12 ">

                              <table class="table">
                                <tr>
                                      <td><?php echo __('Job name','shipme') ?></td>
                                      <td><b><?php echo $postA->post_title ?></b></td>
                                </tr>


                                <tr>
                                      <td><?php echo __('Winning Bid','shipme') ?></td>
                                      <td><b><?php echo shipme_get_show_price($orderObj->order_total_amount) ?></b></td>
                                </tr>


                                <tr>
                                      <td><?php echo __('Winning Bid','shipme') ?></td>
                                      <td><b><a href="<?php echo shipme_get_user_profile_link($transporter->ID) ?>"><?php echo ($transporter->user_login) ?></a></b></td>
                                </tr>




                              </table>


                              <?php



                                      if(!$order->is_order_freelancer_delivered() and !$order->is_order_buyer_completed())
                                      {
                                        ?>

                                              <div class="alert alert-warning"><?php echo __('Waiting delivery from transpoter','shipme') ?></div>

                                        <?php
                                      }
                                      elseif($order->is_order_freelancer_delivered() and !$order->is_order_buyer_completed())
                                      {
                                        // code...
                                        ?>

                                              <div class="alert alert-warning"><?php echo __('Waiting customer to confirm','shipme') ?></div>

                                        <?php
                                      }
                                      else {
                                        // code...  ?>

                                                <div class="alert alert-success"><?php echo __('Order delivered, and accepted.','shipme') ?></div>

                                          <?php
                                      }

                                      if(!$order->is_order_freelancer_delivered() )
                                      {
                                        if(get_current_user_id() == $transporter->ID)
                                        {
                                          ?>

                                              <p><a href="<?php echo get_site_url() ?>?s_action=mark_delivered&oid=<?php echo $oid ?>" class="btn btn-sm btn-block btn-outline-primary"><?php echo __('Mark as delivered','shipme') ?></a></p>

                                          <?php
                                        }
                                      }

                                      if($order->is_order_freelancer_delivered() and  !$order->is_order_buyer_completed())
                                      {

                                        if(get_current_user_id() == $orderObj->buyer)
                                        {
                                          ?>

                                              <p><a href="<?php echo get_site_url() ?>?s_action=mark_completed&oid=<?php echo $oid ?>" class="btn btn-sm btn-block btn-outline-primary"><?php echo __('Mark as completed','shipme') ?></a></p>

                                          <?php
                                        }
                                      }



                               ?>

                              <p><a href="<?php echo get_permalink($postA->ID) ?>" class="btn btn-sm btn-block btn-outline-primary"><?php echo __('Visit Job Page','shipme') ?></a></p>


                          </div>
                    </div>

                            <div class="col-12 col-md-8">
                              <div class="card p-3 card-square-top font-size-12 ">
There isnt any information here.


                              </div>
                            </div>


              </div>

<?php


    $page = ob_get_contents();
     ob_end_clean();

     return $page;



}


 ?>
