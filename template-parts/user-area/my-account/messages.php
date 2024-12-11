<?php

function shipme_theme_my_account_messages_fnc()
{

  ob_start();

  $user = wp_get_current_user();
  $name = $user->user_login;

  if(function_exists('shipme_messaging_live_messages'))
  {
        shipme_messaging_live_messages();
  }
  else {



  ?>

  <div class="ship-pageheader">
            <ol class="breadcrumb ship-breadcrumb">
              <li class="breadcrumb-item"><a href="<?php echo get_site_url() ?>"><?php echo __('Home','shipme') ?></a></li>
              <li class="breadcrumb-item" ><a href="<?php echo get_permalink(get_option('shipme_account_page_id')) ?>"><?php echo __('My Account','shipme') ?></a></li>
              <li class="breadcrumb-item active" aria-current="page"><?php echo __('Messages','shipme') ?></li>
            </ol>
            <h6 class="ship-pagetitle"><?php printf(__("Messaging","shipme") ) ?></h6>
          </div>

          <?php echo shp_account_main_menu(); ?>


          <?php

          global $wpdb;


          if(($_GET['pg']) == "send")
          {

                    // send a message here


                    $uidToSend  = $_GET['uid'];
                    $pid        = $_GET['pid'];
                    $tms        = $_POST['tms'];

                    $pst = get_post($pid);
                    $user = get_userdata($uidToSend);



                    if(!empty($_POST['sendmessage']))
                    {
                          $message = sanitize_text_field($_POST['message']);
                          $shipme_messages = new shipme_messages();

                          $thid = $shipme_messages->get_thread_for_pid($pid, get_current_user_id(), $uidToSend);
                          $shipme_messages->send_message(get_current_user_id(), $uidToSend, $thid, $message, $tms);

                          shipme_send_email_on_priv_mess_received(get_current_user_id(), $uidToSend);

                          ?>

                              <div class="alert alert-success">
                                  <p><?php printf(__('Your message has been sent!','shipme')); ?></p>
                                  <p><a href="<?php echo get_permalink($pid) ?>" class="btn btn-primary"><?php printf(__('Return to the job page','shipme')); ?></a></p>
                              </div>

                          <?php
                    }
                    else {


                ?>


                <form method="post"> <input type="hidden" name="tms" value="<?php echo current_time('timestamp') ?>" />
                <div class="card p-4">


                  <div class="row">
                      <div class="col-md-12">
                           <div class="alert alert-secondary"><?php printf(__('You are sending a message to <b>%s</b>','shipme'), $user->user_login); ?></div>
                      </div>

                  </div>

 <div class="row">
     <div class="col-md-12">
         <div class="form-group">
             <label for="form_email"><?php _e('Shipping job:','shipme'); ?></label>
             <input id="form_email" type="text" name="shp" disabled class="form-control" value="<?php echo $pst->post_title; ?>" placeholder="Please enter your email *" />

         </div>
     </div>

 </div>
 <div class="row">
     <div class="col-md-12">
         <div class="form-group">
             <label for="form_message"><?php _e('Your message:','shipme'); ?></label>
             <textarea id="form_message" name="message" class="form-control"   rows="4" required="required" placeholder="<?php _e('Type your message here...','shipme') ?>"></textarea>
             <div class="help-block with-errors"></div>
         </div>
     </div>
     <div class="col-md-12">
         <input type="submit" class="btn btn-success btn-send" name="sendmessage" value="Send message">
     </div>
 </div>


</div>  </form><?php } // end else send message ?>



                <?php
          }
          else {



              if(isset($_GET['thid']))
              {
                  $messages = new shipme_messages();
                  $thread = $messages->get_thread_object($_GET['thid']);

                  if($thread == false)
                  {
                      echo '<div class="alert alert-danger">'.__('Error thread','shipme').'</div>';
                  }
                  else {

                      if($thread->uid1 == get_current_user_id() or $thread->uid2 == get_current_user_id())
                      {
                            // now we are ok, and lets see

                            $pst = get_post($thread->pid);
                            $thid = $thread->id;

                            if(isset($_POST['tm']))
                            {
                                $tm = $_POST['tm'];
                                $thid = $_POST['thid'];
                                $chatbox_textarea = nl2br($_POST['chatbox_textarea']);

                                $mess = new shipme_messages();
                                $thread_temp = $mess->get_thread_object($thid);

                                if($thread_temp->uid1 == get_current_user_id() or $thread_temp->uid2 == get_current_user_id())
                                {
                                    $receiver_uid = $thread_temp->uid1;
                                    if(get_current_user_id() == $receiver_uid) $receiver_uid = $thread_temp->uid2;

                                    $mess->send_message(get_current_user_id(), $receiver_uid, $thid, $chatbox_textarea, $tm);
                                    echo '<div class="alert alert-success">'.__('Your message has been sent.','shipme').'</div>';
                                }

                            }

                              ?>

                              <div class="card mb-4">
                                <div class="panel rounded shadow panel-teal" >

                                      <div class="p-3">
                                            <h4><?php echo sprintf(__("Job: %s","shipme"), $pst->post_title) ?></h4>
                                      </div>


                                </div>
                              </div>


                              <div class="card mb-4">
                                <div class="panel rounded shadow panel-teal" style="width: 100%; display: inline-block">

                                      <div class="p-3">

                                          <?php

                                                $sz = "select * from ".$wpdb->prefix."ship_pm where parent='$thid'";
                                                $rz = $wpdb->get_results($sz);

                                                if(count($rz) == 0)
                                                {
                                                    _e('There are no messages in this thread yet.','shipme');
                                                }
                                                else {
                                                  // code...

                                                   foreach($rz as $message_new)
                                                   {

                                                          if($message_new->initiator == get_current_user_id())
                                                          {
                                                            $usr1 = get_userdata($message_new->initiator);
                                                            $lnkj = shipme_get_user_profile_link($message_new->initiator);


                                                          ?>
                                                                <div class="my-message-1">  <div class="row">
                                                                      <div class="col-12 col-md-2 col-xs-5 my-message-a"><?php echo '<img width="60" class="avsus2" height="60" border="0" src="'.shipme_get_avatar_square($message_new->initiator). '" /><br/>';
                                                                                                                        echo  '<a href="'.$lnkj.'">'.$usr1->user_login.'</a>'; ?></div>
                                                                      <div class="col-12 col-md-10 col-xs-7"><?php

                                                                      $pattern = "/[^@\s]*@[^@\s]*\.[^@\s]*/";
                                                                      $replacement = "[removed]";
                                                                      $string = $message_new->content;
                                                                      $string2 = preg_replace($pattern, $replacement, $string);

                                                                      echo shipme_filter_phone_nr($string2);

                                                                       ?><br/><?php //echo pricerr_get_attached($message->attached) ?>
                                                                      <span class="date-for-message"><?php echo sprintf(__('Sent on %s','pricerrtheme'), date_i18n('H:i d-M-Y', $message_new->datemade)) ?></span></div>
                                                                </div>  </div>

                                                          <?php
                                                          }
                                                          else {

                                                            $usr = get_userdata($message_new->initiator);

                                                            global $wpdb;
                                                            $wpdb->query("update ".$wpdb->prefix."ship_pm  set rd='1' where id='{$message_new->id}' ");

                                                            $lnkj = shipme_get_user_profile_link($message->initiator);

                                                            ?>
                                                                <div class="my-message-2">  <div class="row">

                                                                      <div class="col-12 col-md-10 col-xs-7"><?php       $pattern = "/[^@\s]*@[^@\s]*\.[^@\s]*/";
                                                                            $replacement = "[removed]";
                                                                            $string = $message_new->content;
                                                                            $string2 = preg_replace($pattern, $replacement, $string);

                                                                            echo shipme_filter_phone_nr($string2); ?> <br/><?php //echo pricerr_get_attached($message->attached) ?>
                                                                      <span class="date-for-message"><?php echo sprintf(__('Sent on %s','pricerrtheme'),  date_i18n('H:i d-M-Y', $message_new->datemade)) ?></span></div>
                                                                      <div class="col-12 col-md-2 col-xs-5 my-message-a"><?php echo '<img width="60" class="avsus2" height="60" border="0" src="'.shipme_get_avatar_square($message_new->initiator ). '" /><br/>';
                                                                                                                        echo  '<a href="'.$lnkj.'">'.$usr->user_login.'</a>'; ?></div>

                                                                </div>  </div>

                                                          <?php
                                                          }

                                                }
                                                }


                                           ?>

                                      </div></div></div>

                                      <!-- #### -->

                                      <div class="card mb-4 p-4">

                                        <form method="post">
                                        <div class="chat-box-controls">
                                          <div class="row">
                                          <div class="col-12 col-md-10">

                                              <textarea required   class="form-control" name="chatbox_textarea" placeholder="<?php _e('Type your message here...','shipme') ?>"></textarea>
                                              <input type="hidden" name="thid" value="<?php echo $thid ?>" />
                                              <input type="hidden" name="tm" value="<?php echo current_time('timestamp') ?>" />


                                              <!-- <input type="file"  name="fileattachment" <?php echo $xv->closed == 1 ? "disabled" : "" ?> id='myfile' /> -->

                                            </div>

                                            <div class="col-12 col-md-2">
                                              <input type="submit" class="btn btn-primary btn-block "  id="send_chat_button2" value="<?php _e('Send Message','shipme') ?>" />
                                            </div>
                                        </div>
                                      </div>  </form>


                                        </div>


                              <?php

                      }
                      else {
                        echo '<div class="alert alert-danger">'.__('Error thread','shipme').'</div>';
                      }

                  }

              }
              else {

                    $uid = get_current_user_id();

                    global $wpdb;
                    $s = "select * from ".$wpdb->prefix."ship_pm_threads where messages_number>0 and (uid1='$uid' or uid2='$uid')";
                    $r1 = $wpdb->get_results($s);

                    if(count($r1) > 0)
                    {
                          ?>



                          <div class="card">

                            <div class="panel rounded shadow panel-teal">
                              <!--
                                <div class="panel-sub-heading inner-all">
                                    <div class="pull-left">
                                        <ul class="list-inline no-margin">
                                            <li>
                                                <div class="ckbox ckbox-theme">
                                                    <input id="checkbox-group" type="checkbox" class="mail-group-checkbox">
                                                    <label for="checkbox-group"></label>
                                                </div>
                                            </li>
                                            <li>
                                                <div class="btn-group">
                                                    <button type="button" class="btn btn-default btn-sm dropdown-toggle" data-toggle="dropdown">
                                                        All <span class="caret"></span>
                                                    </button>
                                                    <ul class="dropdown-menu" role="menu">
                                                        <li><a href="#">None</a></li>
                                                        <li><a href="#">Read</a></li>
                                                        <li><a href="#">Unread</a></li>
                                                    </ul>
                                                </div>
                                            </li>
                                            <li>
                                                <div class="btn-group">
                                                    <button class="btn btn-default btn-sm tooltips" type="button" data-toggle="tooltip" data-container="body" title="" data-original-title="Archive"><i class="fa fa-inbox"></i></button>
                                                    <button class="btn btn-default btn-sm tooltips" type="button" data-toggle="tooltip" data-container="body" title="" data-original-title="Report Spam"><i class="fa fa-warning"></i></button>
                                                    <button class="btn btn-default btn-sm tooltips" type="button" data-toggle="tooltip" data-container="body" title="" data-original-title="Delete"><i class="fa fa-trash-o"></i></button>
                                                </div>
                                            </li>
                                            <li class="hidden-xs">
                                                <div class="btn-group">
                                                    <button type="button" class="btn btn-default btn-sm">More</button>
                                                    <button type="button" class="btn btn-default btn-sm dropdown-toggle" data-toggle="dropdown">
                                                        <span class="caret"></span>
                                                        <span class="sr-only">Toggle Dropdown</span>
                                                    </button>
                                                    <ul class="dropdown-menu" role="menu">
                                                        <li><a href="#"><i class="fa fa-edit"></i> Mark as read</a></li>
                                                        <li><a href="#"><i class="fa fa-ban"></i> Spam</a></li>
                                                        <li class="divider"></li>
                                                        <li><a href="#"><i class="fa fa-trash-o"></i> Delete</a></li>
                                                    </ul>
                                                </div>
                                            </li>
                                        </ul>
                                    </div>
                                    <div class="pull-right">
                                        <ul class="list-inline no-margin">
                                            <li class="hidden-xs"><span class="text-muted">Showing 1-50 of 2,051 messages</span></li>
                                            <li>
                                                <div class="btn-group">
                                                    <a href="#" class="btn btn-sm btn-default"><i class="fa fa-angle-left"></i></a>
                                                    <a href="#" class="btn btn-sm btn-default"><i class="fa fa-angle-right"></i></a>
                                                </div>
                                            </li>
                                            <li class="hidden-xs">
                                                <div class="btn-group">
                                                    <button type="button" class="btn btn-default btn-sm dropdown-toggle" data-toggle="dropdown">
                                                        <i class="fa fa-cog"></i> <span class="caret"></span>
                                                    </button>
                                                    <ul class="dropdown-menu pull-right" role="menu">
                                                        <li class="dropdown-header">Display density :</li>
                                                        <li class="active"><a href="#">Comfortable</a></li>
                                                        <li><a href="#">Cozy</a></li>
                                                        <li><a href="#">Compact</a></li>
                                                        <li class="dropdown-header">Configure inbox</li>
                                                        <li><a href="#">Settings</a></li>
                                                        <li><a href="#">Themes</a></li>
                                                        <li class="divider"></li>
                                                        <li><a href="#">Help</a></li>
                                                    </ul>
                                                </div>
                                            </li>
                                        </ul>
                                    </div>
                                    <div class="clearfix"></div>
                                </div>  /.panel-sub-heading -->
                                <div class="panel-body no-padding">

                                    <div class="table-responsive">
                                        <table class="table table-hover table-email">
                                            <tbody>

                                              <?php

                                              $messageObjClass = new shipme_messages();

                                                    foreach($r1 as $message)
                                                    {

                                                          $the_other_user = $message->uid1;
                                                          if(get_current_user_id() == $the_other_user) $the_other_user = $message->uid2;

                                                          $usr = get_userdata($the_other_user);
                                                          $pst = get_post($message->pid);

                                                        ?>

                                                        <tr class="unread selected">
                                                            <td>
                                                                <div class="ckbox ckbox-theme">
                                                                    <input id="checkbox1" type="checkbox" checked="checked" class="mail-checkbox">
                                                                    <label for="checkbox1"></label>
                                                                </div>
                                                            </td>
                                                            <td>
                                                                <a href="#" class="star star-checked"><i class="fa fa-star"></i></a>
                                                            </td>
                                                            <td>
                                                                <div class="media">
                                                                    <a href="#" class="pull-left">
                                                                        <img alt="avatar" src="<?php echo shipme_get_avatar_square($the_other_user) ?>" class="media-object">
                                                                    </a>
                                                                    <div class="media-body">
                                                                        <h4 class="text-primary"><a href="<?php echo shipme_get_thread_link($message->id); ?>"><?php echo $usr->user_login ?></a></h4>
                                                                        <p class="email-summary"><?php echo $pst->post_title ?>
                                                                          <?php

                                                                                  $nr = $messageObjClass->get_unread_messages_thread($message->id, get_current_user_id());
                                                                                  if($nr > 0)
                                                                                  {
                                                                                        echo '<span class="badge badge-danger">'.$nr.'</span>';
                                                                                  }

                                                                          ?>  </p>
                                                                        <span class="media-meta"><?php echo date_i18n('d-M-Y H:i:s', $message->last_updated) ?></span>
                                                                    </div>
                                                                </div>
                                                            </td>
                                                        </tr>

                                                      <?php
                                                    }

                                               ?>




                                            </tbody>
                                        </table>
                                    </div><!-- /.table-responsive -->

                                </div><!-- /.panel-body -->
                            </div><!-- /.panel -->

                          </div>


                          <?php
                    }
                    else {
                      // code...

                            global $wpdb;
                            $s = "select * from ".$wpdb->prefix."ship_pm_threads where uid1='$uid' or uid2='$uid' order by last_updated desc";
                            $r = $wpdb->get_results($s);


                            if(count($r) == 0)
                            {
                                ?>


                                        <div class="card">

                                                  <div class="panel rounded shadow panel-teal">
                                                    <div class="p-4">
                                                        <?php _e('There are no message threads.','shipme') ?>

                                                  </div></div></div>


                                                  <?php
                            }
                            else
                            {
                                    ?>



<div class="card">

                                                  <div class="panel rounded shadow panel-teal">
                                                    <div class="p-4">


                                                    <?php

 foreach($threads as $thread)
                  {

                    $pst = get_post($row->pid);

                    ?>

<div class="row">        
<div class="col-md-3"><?php echo $pst->post_title ?></div>
<div class="col-md-3"><a href="" class="btn btn-primary btn-sm">View Message</a></div>


</div>    



<?php


                  }




?>



                                                    </div></div></div>
<?php
                            }

                      ?>


                                                
                      <?php
                    }

           ?>



        <?php } ?>

  <?php

  }  }

    $page = ob_get_contents();
     ob_end_clean();

     return $page;



}

 ?>
