<?php

function shipme_theme_my_account_reviews_new_fnc()
{
  ob_start();
  global $wpdb;

  $user = wp_get_current_user();
  $name = $user->user_login;

  $credits = shipme_get_credits($user->ID);
  $uid = get_current_user_id();

  $pgid = get_option('shipme_profile_feedback_page_id');


  $pg = 'pending';
  if(empty($_GET['pg'])) $pg = 'pending'; else $pg = $_GET['pg'];


  //-----

  $revPageID = get_option('shipme_profile_feedback_page_id');


  $nok = 1;

  if(isset($_POST['rateme']))
  {

    $rating = $_POST['rating'];
    $comment = nl2br(strip_tags($_POST['commenta']));
    $rid = (int)$_GET['rid'];

    $s = "select * from ".$wpdb->prefix."ship_ratings where id='$rid'";
    $r = $wpdb->get_results($s);

    if(count($r) == 0) die('no way jose');
    $row = $r[0];

    if(empty($comment)):

      $nok = 1;


    elseif($row->awarded == 0):

      $tm = current_time('timestamp',0);


      $s = "update ".$wpdb->prefix."ship_ratings set grade='$rating', datemade='$tm', comment='$comment', awarded='1' where id='$rid'";
      $wpdb->query($s);


      $nok = 0;

      //---------------------------

      $my_uid = $row->touser;

      

      $cool_user_rating = get_user_meta($my_uid, 'cool_user_rating', true);
      if(empty($cool_user_rating)) update_user_meta($my_uid, 'cool_user_rating', 0);

      //---------------------------

      $cool_user_rating = get_user_meta($my_uid, 'cool_user_rating', true);

      global $wpdb;
      $s = "select grade from ".$wpdb->prefix."ship_ratings where touser='$my_uid' AND awarded='1'";
      $r = $wpdb->get_results($s);
      $i = 0; $s = 0;

      if(count($r) > 0)
      {
        foreach($r as $row) // = mysql_fetch_object($r))
        {
          $i++;
          $s = $s + $row->grade;

        }

        $rating2 = round(($s/$i)/2, 2);
        update_user_meta($my_uid, 'cool_user_rating', $rating2);

      }

      shipme_send_email_on_rated_user($pid, $my_uid);

      //---------------------------

    endif;

    $submitted = 1;
  }


  //-----------



    $shipme_count_reviews_i_have_to_award = shipme_count_reviews_i_have_to_award($uid);
    if($shipme_count_reviews_i_have_to_award > 0)
    {
      $shipme_count_reviews_i_have_to_award = '<span class="badge badge-danger">'.$shipme_count_reviews_i_have_to_award."</span>";
    }
    else $shipme_count_reviews_i_have_to_award = '';




  ?>


  <div class="ship-pageheader">
            <ol class="breadcrumb ship-breadcrumb">
              <li class="breadcrumb-item"><a href="<?php echo get_site_url() ?>"><?php echo __('Home','shipme') ?></a></li>
              <li class="breadcrumb-item" ><a href="<?php echo get_permalink(get_option('shipme_account_page_id')) ?>"><?php echo __('My Account','shipme') ?></a></li>
              <li class="breadcrumb-item active" aria-current="page"><?php echo __('Reviews','shipme') ?></li>
            </ol>
            <h6 class="ship-pagetitle"><?php printf(__("Reviews","shipme") ) ?></h6>
          </div>

          <?php echo shp_account_main_menu(); ?>



                  <div class="row row-xs">
                  <div class="col-sm-12 col-lg-12 mt-3">
                    <?php

                    if($submitted == 1)
                    {
                       ?>
                                <div class="alert alert-success"><?php _e('Your rating was submitted!','shipme') ?></div>



                       <?php
                    }


                        if(isset($_GET['rid']) and $submitted != 1)
                        {
                            ?>

                            <div class="card p-3">

                              <form method="post">
                       <table class="table">
                                  <tr>
                                <td><?php echo __('Your Rating','shipme'); ?>:</td>
                                <td><select class="do_input" name="rating"><?php for($i=5;$i>0;$i--) echo '<option value="'.($i*2).'">'.$i.'</option>'; ?></select></td>
                              </tr>

                              <tr>
                                <td><?php echo __('Your Comment','shipme'); ?>:</td>
                                <td><textarea name="commenta" required class="do_input" rows="5" cols="40" ></textarea></td>
                              </tr>



                                 <tr>
                                <td>&nbsp;</td>
                                <td><input type="submit" name="rateme" class="btn btn-primary" value="<?php _e("Submit Rating","shipme"); ?>"  /></td>
                              </tr>



                            </table>
                               </form>


                            </div>



                            <?php
                        }
                        else {



                     ?>

                            <ul class="nav nav-tabs">

                              <?php if(0) { ?>

                              <li class="nav-item">
                                <a class="nav-link" href="#">Rate Now</a>
                              </li>

                            <?php } ?>


                              <li class="nav-item">
                                <a class="nav-link <?php echo $pg == 'pending' ? 'active' : '' ?>" href="<?php echo shipme_get_link_with_page($pgid, 'pending'); ?>"><?php printf(__("Pending Reviews %s","shipme"), $shipme_count_reviews_i_have_to_award ) ?></a>
                              </li>

                              <li class="nav-item">
                                <a class="nav-link <?php echo $pg == 'myreviews' ? 'active' : '' ?>" href="<?php echo shipme_get_link_with_page($pgid, 'myreviews'); ?>"><?php printf(__("Reviews I made","shipme") ) ?></a>
                              </li>


                              <li class="nav-item">
                                <a class="nav-link <?php echo $pg == 'completed' ? 'active' : '' ?>" href="<?php echo shipme_get_link_with_page($pgid, 'completed'); ?>"><?php printf(__("Reviews I Received","shipme") ) ?></a>
                              </li>

                  </ul>

                  <?php

                  if($pg == "pending" )
                  {



                   ?>

                  <div class="card p-3 card-square-top font-size-12 ">
                    <?php

                    global $wpdb;
                    $query = "select * from ".$wpdb->prefix."ship_ratings where fromuser='$uid' AND awarded='0'";
                    $r = $wpdb->get_results($query);

                    if(count($r) > 0)
                    {
                      echo '<table width="100%" class="table">';
                        echo '<tr>';
                          echo '<th>&nbsp;</th>';
                          echo '<th><b>'.__('Job Title','shipme').'</b></th>';
                          echo '<th><b>'.__('To User','shipme').'</b></th>';
                          echo '<th><b>'.__('Aquired on','shipme').'</b></th>';
                          echo '<th><b>'.__('Price','shipme').'</b></th>';
                          echo '<th><b>'.__('Options','shipme').'</b></th>';

                        echo '</tr>';


                      foreach($r as $row)
                      {
                        $post 	= $row->pid;
                        $post 	= get_post($post);
                        $bid 	= shipme_get_winner_bid($row->pid);
                        $user 	= get_userdata($row->touser);

                        $dmt2 = get_post_meta($row->pid,'closed_date',true);

                        if(!empty($dmt2))
                        $dmt = date_i18n('d-M-Y H:i:s', $dmt2);

                        $linkRate = shipme_using_permalinks() ? get_permalink($revPageID).'?rid=' . $row->id :  get_permalink($revPageID).'&rid=' . $row->id;

                        echo '<tr>';

                          echo '<th><img class="img_class" width="42" height="42" src="'.shipme_get_first_post_image($row->pid, 42, 42).'"
                                          alt="'.$post->post_title.'" /></th>';
                          echo '<th><a href="'.get_permalink($row->pid).'">'.$post->post_title.'</a></th>';
                          echo '<th><a href="'.shipme_get_user_profile_link($user->ID).'">'.$user->user_login.'</a></th>';
                          echo '<th>'.$dmt.'</th>';
                          echo '<th>'.shipme_get_show_price($bid->bid).'</th>';
                          echo '<th><a href="'.$linkRate.'" class="btn btn-outline-primary btn-sm">'.__('Rate User','shipme').'</a></th>';

                        echo '</tr>';

                      }

                      echo '</table>';
                    }
                    else
                    {

                      _e("There are no reviews to be awarded.","shipme");

                    }
                  ?>


                  </div>

                <?php } elseif($pg == "myreviews" ) { ?>


                  <div class="card card-square-top font-size-12 ">

                      <?php

                      global $wpdb;
                      $query = "select * from ".$wpdb->prefix."ship_ratings where fromuser='$uid' AND awarded='1'";
                      $r = $wpdb->get_results($query);

                      if(count($r) > 0)
                      {
                        echo '<table width="100%" class="table">';
                          echo '<tr>';
                            echo '<th>&nbsp;</th>';
                            echo '<th><b>'.__('Job Title','shipme').'</b></th>';
                            echo '<th><b>'.__('To User','shipme').'</b></th>';
                            echo '<th><b>'.__('Aquired on','shipme').'</b></th>';
                            echo '<th><b>'.__('Rating','shipme').'</b></th>';
                            echo '<th><b>'.__('Price','shipme').'</b></th>';

                          echo '</tr>';


                        foreach($r as $row)
                        {
                          $post 	= $row->pid;
                          $post 	= get_post($post);
                          $bid 	= shipme_get_winner_bid($row->pid);
                          $user 	= get_userdata($row->touser);

                          $dmt2 = get_post_meta($row->pid,'closed_date',true);

                          if(!empty($dmt2))
                          $dmt = date_i18n('d-M-Y H:i:s', $dmt2);

                          $linkRate = shipme_using_permalinks() ? get_permalink($revPageID).'?rid=' . $row->id :  get_permalink($revPageID).'&rid=' . $row->id;

                          echo '<tr>';

                            echo '<th><img class="img_class" width="42" height="42" src="'.shipme_get_first_post_image($row->pid, 42, 42).'"
                                            alt="'.$post->post_title.'" /></th>';
                            echo '<th><a href="'.get_permalink($row->pid).'">'.$post->post_title.'</a><br/>';


                              echo sprintf(__('Comment: %s','shipme'), $row->comment);

                            echo '</th>';
                            echo '<th><a href="'.shipme_get_user_profile_link($user->ID).'">'.$user->user_login.'</a></th>';
                            echo '<th>'.$dmt.'</th>';
                              echo '<th>'.shipme_get_ship_stars($row->grade/2).' ('.($row->grade/2).')</th>';
                            echo '<th>'.shipme_get_show_price($bid->bid).'</th>';


                          echo '</tr>';

                        }

                        echo '</table>';
                      }
                      else
                      {
                        echo '<p class="p-3">';
                        _e("There are no reviews to be awarded.","shipme");
                        echo '</p>';

                      }


                       ?>
                  </div>

                <?php } elseif($pg == "completed" ) { ?>


                  <div class="card p-3 card-square-top font-size-12 ">
                    <?php

    					global $wpdb;
    					$query = "select * from ".$wpdb->prefix."ship_ratings where touser='$uid' AND awarded='1'";
    					$r = $wpdb->get_results($query);

    					if(count($r) > 0)
    					{
    						echo '<table width="100%" class="table">';
    							echo '<tr>';
    								echo '<th>&nbsp;</th>';
    								echo '<th><b>'.__('Job Title','shipme').'</b></th>';
    								echo '<th><b>'.__('From User','shipme').'</b></th>';
    								echo '<th><b>'.__('Aquired on','shipme').'</b></th>';
    								echo '<th><b>'.__('Price','shipme').'</b></th>';
    								echo '<th><b>'.__('Rating','shipme').'</b></th>';


    							echo '</tr>';


    						foreach($r as $row)
    						{
    							$post 	= $row->pid;
    							$post 	= get_post($post);
    							$bid 	= shipme_get_winner_bid($row->pid);
    							$user 	= get_userdata($row->fromuser);

    							$dmt2 =  get_post_meta($row->pid,'closed_date',true);

    							if(!empty($dmt2))
    							$dmt = date_i18n('d-M-Y H:i:s', $dmt2);

    							echo '<tr>';

    								echo '<th><img width="42" height="42" class="img_class" src="'.shipme_get_first_post_image($row->pid, 42, 42).'"
                                    alt="'.$post->post_title.'" /></th>';
    								echo '<th><a href="'.get_permalink($row->pid).'">'.$post->post_title.'</a><br/>';

                            echo sprintf(__('Comment: %s','shipme'), $row->comment);

                    echo '</th>';
    								echo '<th><a href="'.shipme_get_user_profile_link($user->ID).'">'.$user->user_login.'</a></th>';
    								echo '<th>'.$dmt.'</th>';
    								echo '<th>'.shipme_get_show_price($bid->bid).'</th>';
    								echo '<th>'.shipme_get_ship_stars(floor($row->grade/2)).'('.floor($row->grade/2).')</th>';


    						}

    						echo '</table>';
    					}
    					else
    					{
    						_e("There are no reviews.","shipme");
    					}
    				?>
                  </div>

                <?php } } ?>


                        </div>
                              </div>



  <?php


    $page = ob_get_contents();
     ob_end_clean();

     return $page;



}

 ?>
