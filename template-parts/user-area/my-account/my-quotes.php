<?php

function shipme_theme_my_account_my_quotes_fnc()
{

  ob_start();

  $user = wp_get_current_user();
  $name = $user->user_login;
  $uid = get_current_user_id();
  $date_format = get_option('date_format');


  if(empty($_GET['pg'])) $pg = 'home';
  else $pg = $_GET['pg'];

  $myqpgid = get_option('shipme_my_quotes_page');

  $shipme_count_in_progress_jobs_freelancer = shipme_count_in_progress_jobs_freelancer($uid);
  if($shipme_count_in_progress_jobs_freelancer > 0) $inProgCnt =  '<span class="badge badge-danger">' . $shipme_count_in_progress_jobs_freelancer . '</span>';
  //---------------



  ?>

  <div class="ship-pageheader">
            <ol class="breadcrumb ship-breadcrumb">
              <li class="breadcrumb-item"><a href="<?php echo get_site_url() ?>"><?php echo __('Home','shipme') ?></a></li>
              <li class="breadcrumb-item" ><a href="<?php echo get_permalink(get_option('shipme_account_page_id')) ?>"><?php echo __('My Account','shipme') ?></a></li>
              <li class="breadcrumb-item active" aria-current="page"><?php echo __('My Quotes','shipme') ?></li>
            </ol>
            <h6 class="ship-pagetitle"><?php printf(__("My Quotes","shipme") ) ?></h6>
          </div>

          <?php echo shp_account_main_menu();


          ?>



                    <ul class="nav nav-tabs">
                      <li class="nav-item">
                        <a class="nav-link <?php echo $pg == 'home' ? 'active' : '' ?>" href="<?php echo shipme_get_link_with_page($myqpgid, 'home'); ?>"><?php _e('Active Quotes','shipme') ?></a>
                      </li>

                      <li class="nav-item">
                        <a class="nav-link <?php echo $pg == 'progress' ? 'active' : '' ?>" href="<?php echo shipme_get_link_with_page($myqpgid, 'progress'); ?>"><?php printf(__('In Progress %s','shipme'), $inProgCnt) ?></a>
                      </li>


                      <li class="nav-item">
                        <a class="nav-link <?php echo $pg == 'delivered' ? 'active' : '' ?>" href="<?php echo shipme_get_link_with_page($myqpgid, 'delivered'); ?>"><?php _e('Delivered Jobs','shipme') ?></a>
                      </li>

                      <li class="nav-item">
                        <a class="nav-link <?php echo $pg == 'completed' ? 'active' : '' ?>" href="<?php echo shipme_get_link_with_page($myqpgid, 'completed'); ?>"><?php _e('Completed Jobs','shipme') ?></a>
                      </li>

                      <li class="nav-item">
                        <a class="nav-link <?php echo $pg == 'cancelled' ? 'active' : '' ?>" href="<?php echo shipme_get_link_with_page($myqpgid, 'cancelled'); ?>"><?php _e('Cancelled Jobs','shipme') ?></a>
                      </li>



          </ul>

          <div class="card card-square-top font-size-12 ">

          <?php

          global $wpdb;
          $current_page = empty($_GET['pj']) ? 1 : $_GET['pj'];

          $amount_per_page = 10;
          $offset = ($current_page -1)*$amount_per_page;


              if($pg == "home")
              {

                $prf  = $wpdb->prefix;

                $s    = "select * from ".$prf."ship_bids bids, ".$prf."posts posts,   ".$prf."postmeta pmeta where posts.ID=pmeta.post_id and
                pmeta.meta_key='winner' and pmeta.meta_value='0' and posts.ID=bids.pid and bids.uid='$uid'";
                $r    = $wpdb->get_results($s);



                      if(count($r) > 0)
                      {
                        ?>
                        <div class="p-3">
                        <table class="table table-hover table-outline table-vcenter   card-table">
                          <thead><tr>

                            <th><?php echo __('Job Title','shipme'); ?></th>
                            <th><?php echo __('My Bid','shipme') ?></th>
                            <th><?php echo __('Date Made','shipme') ?></th>
                            <th><?php echo __('Options','shipme') ?></th>

                           </tr></thead><tbody>

                             <?php

                                    foreach($r as $row)
                                    {



                                        ?>

                                            <tr>
                                                  <td><a href="<?php echo get_permalink($row->ID) ?>"><?php echo $row->post_title ?></a></td>
                                                  <td class='text-success'><?php echo shipme_get_show_price($row->bid) ?></td>
                                                  <td><?php echo date_i18n($date_format, $row->date_made) ?></td>
                                                  <td><a href="<?php echo get_the_permalink( $row->ID ); ?>" class='btn btn-outline-primary btn-sm'><?php echo __('View Job','shipme') ?></a>
                                                  <a href="<?php echo get_the_permalink( $row->ID ); ?>" class='btn btn-outline-primary btn-sm'><?php echo __('Retract Bid','shipme') ?></a></td>
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

                        <div class="p-4">  <?php _e('There are no active quotes yet.','shipme') ?> </div>

                      <?php } ?>

                  <?php
              }elseif($pg == "progress")
              {
                $uid = get_current_user_id();

                $prf = $wpdb->prefix;
                $s = "select SQL_CALC_FOUND_ROWS * from ".$prf."ship_orders orders where orders.transporter='$uid' and order_status='0' order by id='desc' limit $offset, $amount_per_page";
                $r = $wpdb->get_results($s);

                $total_rows   = shipme_get_last_found_rows();
                $own_pagination = new own_pagination($amount_per_page, $total_rows, shipme_get_link_with_page($pgid, 'progress'). "&");


              ?>


              <?php

                    if(count($r) > 0)
                    {
                          ?>
                          <div class="p-3 table-responsive">
                          <table class="table table-hover table-outline table-vcenter   card-table">
                            <thead><tr>

                              <th><?php echo __('Job Title','shipme'); ?></th>
                              <th><?php echo __('Price','shipme') ?></th>
                              <th><?php echo __('Date Made','shipme') ?></th>
                              <th><?php echo __('Completion','shipme') ?></th>
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

                                                            $order = new ship_orders($row->id);

                                                            if($order->has_escrow_deposited() == false)
                                                            {
                                                                echo '<br/><small class="">'.sprintf(__('Waiting the customer to deposit escrow.','shipme')) . '</small>';
                                                            }
                                                            else {
                                                              $obj = $order->get_escrow_object();
                                                              echo '<div class="mylaertwarning2">' . __('The customer has deposited the escrow.','shipme') . '</div>';
                                             
                                                            }
                                                          }
                                                          else {
                                                            // code...
                                                          }

                                                       ?>

                                                    </td>

                                                    <td class='text-success'><?php echo shipme_get_show_price($row->order_total_amount, 0) ?></td>
                                                    <td><?php echo date_i18n($date_format, $row->datemade) ?></td>
                                                    <td <?php if($row->completion_date < $now) echo 'class="text-danger"'; ?>><?php echo  date_i18n($date_format, $row->completion_date) ?></td>
                                                    <td><a href="<?php echo shipme_get_order_page($row->id); ?>" class='btn btn-outline-primary btn-sm'><?php echo __('Order Page','shipme') ?></a>
                                                    <a href="<?php echo home_url(); ?>/?s_action=mark_delivered&oid=<?php echo $row->id ?>" class='btn btn-outline-primary btn-sm'><?php echo __('Mark Delivered','shipme') ?></a></td>
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


                <div class="p-4">
                  <?php _e('You do not have any pending orders.','shipme') ?>
                </div>

              <?php }


              }elseif($pg == "delivered")
              {
                $uid = get_current_user_id();

                $prf = $wpdb->prefix; $wpdb->show_errors = true;

                $s = "select SQL_CALC_FOUND_ROWS * from ".$prf."ship_orders orders where orders.transporter='$uid' and order_status='1' order by id='desc' limit $offset, $amount_per_page";
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
                              <th><?php echo __('Price','shipme') ?></th>
                              <th><?php echo __('Date Made','shipme') ?></th>
                              <th><?php echo __('Completion','shipme') ?></th>
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

                                                            $order = new ship_orders($row->id);

                                                            if($order->has_escrow_deposited() == false)
                                                            {
                                                                echo '<br/><small class="">'.sprintf(__('Waiting the customer to deposit escrow.','shipme')) . '</small>';
                                                            }
                                                            else {
                                                              $obj = $order->get_escrow_object();

                                                            }


                                                              echo '<br/><small class="">'.sprintf(__('Waiting for customer to accept the project.','shipme')) . '</small>';

                                                       ?>

                                                    </td>

                                                    <td class='text-success'><?php echo shipme_get_show_price($row->order_total_amount, 0) ?></td>
                                                    <td><?php echo date_i18n($date_format, $row->datemade) ?></td>
                                                    <td <?php if($row->completion_date < $now) echo 'class="text-danger"'; ?>><?php echo  date_i18n($date_format, $row->completion_date) ?></td>
                                                    <td><a href="<?php echo get_the_permalink( $row->ID ); ?>" class='btn btn-outline-primary btn-sm'><?php echo __('Workspace','shipme') ?></a>
                                                   </td>
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


                <div class="p-4">
                  <?php _e('You do not have any delivered jobs.','shipme') ?>
                </div>

              <?php }

              }elseif($pg == "completed")
              {

                $uid = get_current_user_id();

                $prf = $wpdb->prefix;
                $s = "select SQL_CALC_FOUND_ROWS * from ".$prf."ship_orders orders where orders.transporter='$uid' and order_status='2' order by id='desc' limit $offset, $amount_per_page";
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
                              <th><?php echo __('Price','shipme') ?></th>
                              <th><?php echo __('Date Made','shipme') ?></th>
                              <th><?php echo __('Completion','shipme') ?></th>
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

                                                            $order = new ship_orders($row->id);

                                                            if($order->has_escrow_deposited() == false)
                                                            {
                                                                echo '<br/><small class="">'.sprintf(__('Waiting the customer to deposit escrow.','shipme')) . '</small>';
                                                            }
                                                            else {
                                                              $obj = $order->get_escrow_object();

                                                            }




                                                       ?>

                                                    </td>

                                                    <td class='text-success'><?php echo shipme_get_show_price($row->order_total_amount, 0) ?></td>
                                                    <td><?php echo date_i18n($date_format, $row->datemade) ?></td>
                                                    <td <?php if($row->completion_date < $now) echo 'class="text-danger"'; ?>><?php echo  date_i18n($date_format, $row->completion_date) ?></td>
                                                    <td><a href="<?php echo get_the_permalink( $row->ID ); ?>" class='btn btn-outline-primary btn-sm'><?php echo __('Workspace','shipme') ?></a>
                                                   </td>
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


                <div class="p-4">
                  <?php _e('You do not have any completed jobs.','shipme') ?>
                </div>

              <?php }

              }
              elseif($pg == "cancelled")
              {

                $uid = get_current_user_id();

                $prf = $wpdb->prefix;
                $s = "select SQL_CALC_FOUND_ROWS * from ".$prf."ship_orders orders where orders.freelancer='$uid' and order_status='3' order by id='desc' limit $offset, $amount_per_page";
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
                              <th><?php echo __('Price','shipme') ?></th>
                              <th><?php echo __('Date Made','shipme') ?></th>
                              <th><?php echo __('Completion','shipme') ?></th>
                              <th><?php echo __('Options','shipme') ?></th>

                             </tr></thead><tbody>

                               <?php

                               $now = current_time('timestamp');

                                      foreach($r as $row)
                                      {

                                                 $provider  = get_userdata($row->freelancer);
                                                 $pst       = get_post($row->pid);

                                          ?>

                                              <tr>
                                                    <td><a href="<?php echo get_permalink($pst->ID) ?>"><?php echo $pst->post_title ?></a>
                                                      <?php

                                                            $order = new ship_orders($row->id);

                                                            if($order->has_escrow_deposited() == false)
                                                            {
                                                                echo '<br/><small class="">'.sprintf(__('Waiting the customer to deposit escrow.','shipme')) . '</small>';
                                                            }
                                                            else {
                                                              $obj = $order->get_escrow_object();

                                                            }




                                                       ?>

                                                    </td>

                                                    <td class='text-success'><?php echo shipme_get_show_price($row->order_total_amount, 0) ?></td>
                                                    <td><?php echo date_i18n($date_format, $row->datemade) ?></td>
                                                    <td <?php if($row->completion_date < $now) echo 'class="text-danger"'; ?>><?php echo  date_i18n($date_format, $row->completion_date) ?></td>
                                                    <td><a href="<?php echo get_the_permalink( $row->ID ); ?>" class='btn btn-outline-primary btn-sm'><?php echo __('Workspace','shipme') ?></a>
                                                   </td>
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


                <div class="p-4">
                  <?php _e('You do not have any cancelled orders.','shipme') ?>
                </div>

              <?php } ?>
                <?php

              }




           ?>

          </div>


  <?php



    $page = ob_get_contents();
     ob_end_clean();

     return $page;



}


 ?>
