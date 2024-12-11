<?php
// single job ship page





if(isset($_POST['answer_id']))
{
    $qid = $_POST['answer_id'];
    if(is_user_logged_in())
    {
        global $wpdb; $tm = time();
        $s = "select * from ".$wpdb->prefix."ship_message_board where id='$qid'";
        $r = $wpdb->get_results($s);

        if(count($r)  > 0)
        {
            $row = $r[0];
            $pst = get_post($row->pid);

            if($pst->post_author == get_current_user_id())
            {
                $content = sanitize_text_field($_POST['question_answer']);
                $s = "insert into ".$wpdb->prefix."ship_message_board_answers (content, questionid, datemade, pid ) values('$content','$qid','$tm','{$row->pid}')";
                $wpdb->query($s);

                $answerPosted = 1;

            }
        }
    }
}

//-----------------

  if(isset($_POST['submit_qq']))
  {
      if(is_user_logged_in())
      {
          $question = trim($_POST['question']);
          $pid = $_POST['pid'];
          $uid = get_current_user_id();
          $post = get_post($pid);
          $tm = time();


          if($post->post_author != $uid)
          {


                global $wpdb;
                $hash = md5($question);
                $s = "select * from ".$wpdb->prefix."ship_message_board where hash='$hash' and pid='$pid'";
                $r = $wpdb->Get_results($s);


                if(count($r) == 0)
                {
                    $s = "insert into ".$wpdb->prefix."ship_message_board (uid, pid, content, datemade, hash) values('$uid','$pid','$question','$tm', '$hash')";
                    $wpdb->query($s);

                    $qposted = 1;

                }

          }
      }


  }

  if(isset($_POST['submit-bid']))
  {
        if(is_user_logged_in())
        {
            global $post;
            $pid 		= $post->ID;
            $post 		= get_post($pid);
            $bid 		= trim($_POST['bid']);
            $des 		= trim(strip_tags(shipme_sanitize_string($_POST['bid_description'])));
            $post 		= get_post($pid);

            $tm 		    = current_time('timestamp',0);
            $days_done	= 1;

            $uid = get_current_user_id();


            //---------------------

            $closed = get_post_meta($pid,'closed',true);
            if($closed == "1") { echo 'DEBUG.job Closed'; exit; }

            //---------------------

            if(empty($days_done) || !is_numeric($days_done))
            {
              $days_done = 3;
            }

            $query = "select * from ".$wpdb->prefix."ship_bids where uid='$uid' AND pid='$pid'";
            $r = $wpdb->get_results($query);

            $other_error_to_pace_bid = false;
            $other_error_to_pace_bid = apply_filters('shipme_other_error_to_pace_bid', $other_error_to_pace_bid, $pid);

            if($other_error_to_pace_bid == true):

              $bid_posted = "0";
              $errors = apply_filters('shipme_post_bid_errors_array', $errors, $pid);

            else:


              if(!is_numeric($bid)):

                $bid_posted = "0";
                $errors['numeric_bid_tp'] = __("Your bid must be numeric type. Eg: 9.99",'shipme');

              elseif($uid == $post->post_author):

                $bid_posted = "0";
                $errors['not_yours'] = __("Your cannot bid your own jobs.",'shipme');

              elseif(count($r) > 0):

                $row 	= $r[0];
                $id 	= $row->id;


                $query 	= "update ".$wpdb->prefix."ship_bids set bid='$bid', days_done='$days_done',
                description='$des',date_made='$tm',uid='$uid' where id='$id'";
                $wpdb->query($query);
                $bid_posted = 1;


              else:

                $query = "insert into ".$wpdb->prefix."ship_bids (days_done,bid,description, uid, pid, date_made)
                values('$days_done','$bid','$des','$uid','$pid','$tm')";
                $wpdb->query($query);
                $bid_posted = 1;


                shipme_send_email_when_bid_job_bidder($pid, $uid, $bid);
                shipme_send_email_when_bid_job_owner($pid, $uid, $bid);

                //**********

                do_action('shipme_post_bid_ok_action');

                add_post_meta($pid,'bid',$uid);

              endif; // endif has bid already

            endif;

            if($bid_posted == 1)
            {
                if(shipme_using_permalinks())
                wp_redirect(get_permalink($pid) . "?bidposted=1");
                else
                wp_redirect(get_permalink($pid) . "&bidposted=1");
                exit;
            }
        }

  }




    get_header();


    ?>



    <?php if ( have_posts() ) while ( have_posts() ) : the_post(); ?>


    <?php
      global $post;
    	$pid = get_the_ID();

    	$ending = get_post_meta($pid,'ending',true);
    	$closed = get_post_meta($pid,'closed',true);




    ?>


    <?php


          $pst = get_post($pid);
          $tm = get_the_time('l, F jS, Y') ;

    ?>

        <div class="ship-mainpanel">
            <div class="container">

    <div class="ship-pageheader pb-0">
      <ol class="breadcrumb ship-breadcrumb"> </ol>
        <h6 class="ship-pagetitle"><?php echo $pst->post_title ?></h6>

      </div>

      <div class="ship-card-title mt-3 mb-4 text-capitalize"><?php printf(__('Posted on: %s','shipme'), $tm  ); ?>  | <?php printf(__('ID: #%s','shipme'), $pid)  ?></div>

      <?php

            if($_GET['bidposted'] == 1)
            {
              ?>
                    <div class="alert alert-success"><?php echo __('Your bid was posted successfuly. ','shipme'); ?> </div>

              <?php
            }


       ?>


        <?php

            if(get_current_user_id() == $pst->post_author) /// its owner of this job
            {

                  if($pst->post_status == "draft")
                  {
                      ?>

                      <div class="row row-sm"> <div class="col-lg-12">
                    <div class="alert alert-warning">

                          <?php _e('Your job is awaiting admin moderation.','shipme') ?>

                    </div></div></div>

                      <?php
                  }

                  ?>






                    <div class="row row-sm"> <div class="col-lg-12">
                  <div class="card card-job-thing mb-4">

                        <div class="card-body">
                              <div class="ship-card-title"><?php _e('Received Quotes','shipme') ?></div>






                                <?php
                                $uid = get_current_user_id();

                                $shipme_enable_project_files = get_option('shipme_enable_project_files');
                                $winner = get_post_meta(get_the_ID(), 'winner', true);
                                $post = get_post(get_the_ID());
                                global $wpdb;
                                $pid = get_the_ID();

                                $bids = "select * from ".$wpdb->prefix."ship_bids where pid='$pid' order by id DESC";
                                $res  = $wpdb->get_results($bids);

                                if($post->post_author == $uid) $owner = 1; else $owner = 0;

                                if(count($res) > 0)
                                {

                                  if($private_bids == 'yes' or $private_bids == '1' or $private_bids == 1)
                                  {
                                  if ($owner == 1) $show_stuff = 1;
                                  else if(shipme_current_user_has_bid($uid, $res)) $show_stuff = 1;
                                  else $show_stuff = 0;
                                  }
                                  else $show_stuff = 1;



                                      echo '  <table class="mt-4 table"> ';

                                      echo '  <thead>
                                                <tr>
                                                <th></th>
                                                <th>'.__('Bidder','shipme').'</th>
                                                <th>'.__('Price','shipme').'</th>
                                                <th>'.__('Date','shipme').'</th>
                                                <th style="max-width: 250px; width: 250px">'.__('Comment','shipme').'</th>' ;

                                          if($owner == 1) echo '  <th>'.__('Actions','shipme').'</th>';

                                      echo '      </tr>                      </thead> <tbody>';





                                //-------------

                                foreach($res as $row)
                                {


                                              if($uid == $row->uid) 	$show_this_around = 1; else $show_this_around = 0;
                                              if($owner == 1) $show_this_around = 1;

                                              if($show_this_around == 1)
                                              {

                                                      $user = get_userdata($row->uid);
                                                      echo '<tr>';
                                                      echo '<td><img src="'.shipme_get_avatar($user->ID,95, 95). '" alt="avatar-user" class="acc_m1" width="45" height="45" /></td>';
                                                      echo '<td>  <a href="'.shipme_get_user_profile_link($user->ID).'">'.$user->user_login.'</a><br/>'.shipme_get_ship_stars_new($user->ID).'</td>';
                                                      echo '<td>   '.shipme_get_show_price($row->bid).'</td>';
                                                      echo '<td>  '.date_i18n("d-M-Y H:i:s", $row->date_made).'</td>';
                                                      echo '<td> '.   $row->description  .'</td>';
                                                      if ($owner == 1 )
                                                      {
                                                        if(empty($winner)) // == 0)
                                                        {
                                                              $pid = get_the_ID();
                                                              $shipme_messages = new shipme_messages();
                                                              $realNumberOfMessages = $shipme_messages->get_thread_total_messages($shipme_messages->get_thread_for_pid($pid, $row->uid, get_current_user_id() ));
                                                              $msssnr = "(".$realNumberOfMessages.")";

                                                              // get the different link
                                                              $linkForMessages = shipme_get_priv_mess_page_url('send', '', '&uid='.$row->uid.'&pid='.get_the_ID());
                                                              if($realNumberOfMessages > 0)
                                                              {
                                                                  $thid = $shipme_messages->get_thread_for_pid($pid, get_current_user_id(),  $row->uid);
                                                                  $linkForMessages = shipme_get_priv_mess_page_url('threadcheck', '', '&thid='.$thid);
                                                              }

                                                        echo '<td><ul class="buttons-ul"><li>
                                                        <a data-toggle="modal" data-target="#winnerModal'.$row->id.'" class="btn btn-outline-primary btn-sm" href="'.get_bloginfo('siteurl').'/?s_action=choose_winner&pid='.get_the_ID().'&bid='.$row->id.'">'.__('Select as Winner','shipme').'</a></li>
                                                       <li><a class="btn btn-outline-primary btn-sm"  href="'.$linkForMessages.'">'.sprintf(__('Send Message %s','shipme'), $msssnr).'</a></li></ul></td>';
                                                       ?>

                                                       <div class="modal fade" id="winnerModal<?php echo $row->id ?>" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
                                                         <div class="modal-dialog" role="document">
                                                           <div class="modal-content"><form method="post" action="<?php echo get_site_url() ?>/?s_action=win&choose_winner=1">
                                                             <input type="hidden" name="bidid" value="<?php echo $row->id ?>" />
                                                              <input type="hidden" name="pid" value="<?php the_ID() ?>" />
                                                              <input type="hidden" name="choose_winner" value="1" />

                                                             <div class="modal-header">
                                                               <h5 class="modal-title" id="exampleModalLabel"><?php _e('Select Transporter','shipme') ?></h5>
                                                               <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                                                 <span aria-hidden="true">&times;</span>
                                                               </button>
                                                             </div>
                                                             <div class="modal-body">
                                                               <?php echo sprintf(__('You are about to choose %s as a winner for this job. The price quote was: %s','shipme'),$user->user_login, shipme_get_show_price($row->bid))  ?>
                                                                 <?php

                                                                              $shipme_payment_working_model = get_option('shipme_payment_working_model');
                                                                              if($shipme_payment_working_model == "pay_before")
                                                                              {

                                                                                  $shipme_fee_after_paid = get_option('shipme_fee_after_paid');
                                                                                  $am = round($shipme_fee_after_paid * 0.01 * $row->bid, 2);
                                                                  ?>
                                                                 <p><?php echo sprintf(__('In order to choose a winner you have to pay the commission fee of %s','shipme'), shipme_get_show_price($am)) ?></p>

                                                               <?php } ?>




                                                             </div>
                                                             <div class="modal-footer">

                                                                <?php



                                                                      if($shipme_payment_working_model == "pay_before")
                                                                      {

                                                                            ?>

                                                                                  <a href="<?php echo get_site_url() ?>/?s_action=m1&pay_for_choosing_winner=<?php echo $row->id ?>" class="btn btn-primary"><?php _e('Proceed to payment','shipme') ?></a>

                                                                            <?php
                                                                      }
                                                                      else {



                                                                 ?>

                                                               <button type="button" class="btn btn-secondary" data-dismiss="modal"><?php _e('Close','shipme') ?></button>
                                                               <button type="submit" class="btn btn-primary"><?php _e('Yes, Proceed','shipme') ?></button>

                                                             <?php } ?>







                                                             </div></form>
                                                           </div>
                                                         </div>
                                                        </div>


                                                      <?php }
                                                        else {
                                                          if($winner == $row->uid)
                                                          echo '<td> '.__('Job Winner','shipme').' </td>';
                                                          else echo '<td></td>';
                                                        }

                                                      }



                                                      if($shipme_enable_project_files != "no")
                                                      {
                                                          if(shipme_see_if_project_files_bid(get_the_ID(), $row->uid) == true)
                                                          {
                                                          echo '<div> <i class="bid-days"></i> ';
                                                          echo '<a href="#" class="get_files" rel="'.get_the_ID().'_'.$row->uid.'">'.__('See Bid Files','shipme').'</a> ';
                                                          echo '</div>';
                                                          }

                                                      }


                                                      echo '</tr>';
                                              }
                                } // endforeach

                                  echo '</tbody></table>';
                                }
                                else { echo '<div class="pt-3 pb-0 ">'; _e("No proposals placed yet.",'shipme'); echo '</div>'; }
                                ?>





                        </div>
                    </div>


                      </div>  </div>
                <?php } ?>

  <div class="row row-sm">
          <div class="col-lg-8">


            <div class="card card-job-thing mb-4">
                <div class="card-body">

                  <div class="row no-gutters card-element-1">
                    <div class="col-lg-6">

                      <div class="dash-content">
                        <label class="tx-primary"><?php printf(__('%s Collection',''), '<i class="fas fa-plane-departure"></i>') ?></label>
                        <h2><?php echo get_post_meta($pid,'pickup_location', true) ?></h2>
                      </div><!-- dash-content -->
                    </div>



                    <div class="col-lg-6">

                      <div class="dash-content">
                        <label class="tx-primary"><?php printf(__('%s Delivery',''), '<i class="fas fa-plane-arrival"></i>') ?></label>
                        <h2><?php echo get_post_meta($pid,'delivery_location', true) ?></h2>
                      </div><!-- dash-content -->
                    </div>



                    <div class="col-lg-6">

                      <div class="dash-content">
                        <label class="tx-primary"><?php printf(__('%s Pickup Date',''), '<i class="far fa-calendar-alt"></i>') ?></label>
                        <h2><?php echo date_i18n('d-M-Y', get_post_meta($pid,'pickup_date', true)); ?></h2>
                      </div><!-- dash-content -->
                    </div>


                    <div class="col-lg-6">

                      <div class="dash-content">
                        <label class="tx-primary"><?php printf(__('%s Delivery Date',''), '<i class="far fa-calendar-alt"></i>') ?></label>
                        <h2><?php echo date_i18n('d-M-Y', get_post_meta($pid,'delivery_date', true)) ?></h2>
                      </div><!-- dash-content -->
                    </div>

                  </div>

                      <div id="map" class="mb-4" style="width: 100%; height: 350px; margin-bottom:15px !important;  margin:auto; "></div>

                  <script type="text/javascript">



                  window.onload = function () {


                      var geocoder;
                      var map;
                    var markers = [];
                     geocoder = new google.maps.Geocoder();

                       geocoder.geocode( { 'address': '<?php echo get_post_meta($pid,'pickup_location',true) ?>'}, function(results, status) {
                        if (status == google.maps.GeocoderStatus.OK) {






                      markers.push({
                                  "title": '<?php echo get_post_meta($pid,'pickup_location',true) ?>',
                                  "lat": results[0].geometry.location.lat(),
                                  "lng": results[0].geometry.location.lng(),
                          "icon": '<?php bloginfo('template_url') ?>/images/beachflag.png',
                                  "description": '<?php echo sprintf(__("<strong>Pickup:</strong> %s", 'shipme'), get_post_meta($pid,'pickup_location',true) ) ?>'
                              });


                        geocoder2 = new google.maps.Geocoder();

                               geocoder2.geocode( { 'address': '<?php echo get_post_meta($pid,'delivery_location',true) ?>'}, function(results2, status2) {
                              if (status2 == google.maps.GeocoderStatus.OK) {



                              markers.push( {
                                  "title": '<?php echo get_post_meta($pid,'delivery_location',true) ?>',
                                  "lat": results2[0].geometry.location.lat(),
                                  "lng": results2[0].geometry.location.lng(),
                                  "icon": '<?php bloginfo('template_url') ?>/images/finish.png',
                                  "description": '<?php echo sprintf(__("<strong>Delivery:</strong> %s", 'shipme'), get_post_meta($pid,'delivery_location',true) ) ?>'
                                });


                                    //-------------------------



                                          var mapOptions = {
                                            center: new google.maps.LatLng(markers[0].lat, markers[0].lng),
                                            zoom: 5,
                                            mapTypeId: google.maps.MapTypeId.ROADMAP
                                          };
                                          var map = new google.maps.Map(document.getElementById("map"), mapOptions);

                  map.set('styles', [
{
"elementType": "geometry",
"stylers": [
{
"color": "#f5f5f5"
}
]
},
{
"elementType": "labels.icon",
"stylers": [
{
"visibility": "off"
}
]
},
{
"elementType": "labels.text.fill",
"stylers": [
{
"color": "#616161"
}
]
},
{
"elementType": "labels.text.stroke",
"stylers": [
{
"color": "#f5f5f5"
}
]
},
{
"featureType": "administrative.land_parcel",
"elementType": "labels.text.fill",
"stylers": [
{
"color": "#bdbdbd"
}
]
},
{
"featureType": "poi",
"elementType": "geometry",
"stylers": [
{
"color": "#eeeeee"
}
]
},
{
"featureType": "poi",
"elementType": "labels.text.fill",
"stylers": [
{
"color": "#757575"
}
]
},
{
"featureType": "poi.park",
"elementType": "geometry",
"stylers": [
{
"color": "#e5e5e5"
}
]
},
{
"featureType": "poi.park",
"elementType": "labels.text.fill",
"stylers": [
{
"color": "#9e9e9e"
}
]
},
{
"featureType": "road",
"elementType": "geometry",
"stylers": [
{
"color": "#ffffff"
}
]
},
{
"featureType": "road.arterial",
"elementType": "labels.text.fill",
"stylers": [
{
"color": "#757575"
}
]
},
{
"featureType": "road.highway",
"elementType": "geometry",
"stylers": [
{
"color": "#dadada"
}
]
},
{
"featureType": "road.highway",
"elementType": "labels.text.fill",
"stylers": [
{
"color": "#616161"
}
]
},
{
"featureType": "road.local",
"elementType": "labels.text.fill",
"stylers": [
{
"color": "#9e9e9e"
}
]
},
{
"featureType": "transit.line",
"elementType": "geometry",
"stylers": [
{
"color": "#e5e5e5"
}
]
},
{
"featureType": "transit.station",
"elementType": "geometry",
"stylers": [
{
"color": "#eeeeee"
}
]
},
{
"featureType": "water",
"elementType": "geometry",
"stylers": [
{
"color": "#c9c9c9"
}
]
},
{
"featureType": "water",
"elementType": "labels.text.fill",
"stylers": [
{
"color": "#9e9e9e"
}
]
}
]);


                                          var infoWindow = new google.maps.InfoWindow();
                                          var lat_lng = new Array();
                                          var latlngbounds = new google.maps.LatLngBounds();

                                          for (i = 0; i < markers.length; i++) {
                                            var data = markers[i]

                                            var myLatlng = new google.maps.LatLng(data.lat, data.lng);
                                            lat_lng.push(myLatlng);
                                            var marker = new google.maps.Marker({
                                              position: myLatlng,
                                              map: map,
                                              icon: data.icon,
                                              title: data.title
                                            });



                                            latlngbounds.extend(marker.position);
                                            (function (marker, data) {
                                              google.maps.event.addListener(marker, "click", function (e) {
                                                infoWindow.setContent(data.description);
                                                infoWindow.open(map, marker);
                                              });
                                            })(marker, data);
                                          }
                                          map.setCenter(latlngbounds.getCenter());
                                          map.fitBounds(latlngbounds);

                                          //***********ROUTING****************//

                                          //Initialize the Path Array
                                          var path = new google.maps.MVCArray();

                                          //Initialize the Direction Service
                                          var service = new google.maps.DirectionsService();

                                          //Set the Path Stroke Color
                                          var poly = new google.maps.Polyline({ map: map, strokeColor: '#ff0000' });

                                          //Loop and Draw Path Route between the Points on MAP
                                          for (var i = 0; i < lat_lng.length; i++) {
                                            if ((i + 1) < lat_lng.length) {
                                              var src = lat_lng[i];
                                              var des = lat_lng[i + 1];
                                              path.push(src);
                                              poly.setPath(path);
                                              service.route({
                                                origin: src,
                                                destination: des,
                                                travelMode: google.maps.DirectionsTravelMode.DRIVING
                                              }, function (result, status) {
                                                if (status == google.maps.DirectionsStatus.OK) {

                                                jQuery('#distance_distance').html(Math.round(result.routes[0].legs[0].distance.value / 1000 <?php $shipme_dist_measure = get_option('shipme_dist_measure');
                                                 if($shipme_dist_measure == "miles") echo "/1.6"; ?>) + "<?php


                                                if($shipme_dist_measure == "miles") _e('Miles','shipme');
                                                else _e('Km','shipme');

                                                 ?>");


                                                  for (var i = 0, len = result.routes[0].overview_path.length; i < len; i++) {
                                                    path.push(result.routes[0].overview_path[i]);
                                                  }
                                                }
                                              });
                                            }
                                          }


                                    //--------------------------
                              } else {
                              alert("Geocode was not successful for the following reason: " + status);
                              }
                            });




                        } else {
                          alert("Geocode was not successful for the following reason: " + status);
                        }
                      });



                      }




                      </script>


                      <div class="  card-dash-one mg-t-20">
                                <div class="row no-gutters">
                                  <div class="col-lg-4">

                                    <div class="dash-content">
                                      <label class="tx-primary"><?php printf(__('%s Distance','shipme'), '<i class="far fa-compass"></i>') ?></label>
                                      <h2 id="distance_distance">nan <?php

                                        $shipme_dist_measure = get_option('shipme_dist_measure');
                                        if(empty($shipme_dist_measure)) $shipme_dist_measure = "km";

                                      echo $shipme_dist_measure; ?></h2>
                                    </div><!-- dash-content -->
                                  </div><!-- col-3 -->
                                  <div class="col-lg-4">

                                    <div class="dash-content">
                                      <label class="tx-primary"><?php printf(__('%s Packages','shipme'), '<i class="fas fa-box"></i>') ?></label>
                                      <h2><?php $pc = get_post_meta($pid,'packages', true); printf(__('%s package(s)','shipme'), $pc) ?></h2>
                                    </div><!-- dash-content -->
                                  </div><!-- col-3 -->
                                  <div class="col-lg-4">

                                    <?php

                                                $weight_measure = shipme_get_weight_measure();

                                     ?>
                                    <div class="dash-content">
                                      <label class="tx-primary"><?php printf(__('%s Weight','shipme'), '<i class="fas fa-weight-hanging"></i>') ?></label>
                                      <h2><?php $wg = get_post_meta($pid,'weight', true); echo $wg." ".$weight_measure; ?></h2>
                                    </div><!-- dash-content -->
                                  </div><!-- col-3 -->

                                </div><!-- row -->
                              </div>





                              <div class="  card-dash-one mg-t-20">
                                        <div class="row no-gutters">
                                          <div class="col-lg-4">

                                            <div class="dash-content">
                                              <label class="tx-primary"><?php printf(__('%s Width','shipme'), '<i class="fas fa-box"></i>') ?></label>
                                              <h2 id="distance_distance"><?php

                                                    $shipme_get_dimensions_measure = shipme_get_dimensions_measure();
                                                    $width = get_post_meta($pid,'width',true);
                                                    echo $width." ".shipme_get_dimensions_measure();

                                              ?></h2>
                                            </div><!-- dash-content -->
                                          </div><!-- col-3 -->
                                          <div class="col-lg-4">

                                            <div class="dash-content">
                                              <label class="tx-primary"><?php printf(__('%s Length','shipme'), '<i class="fas fa-box"></i>') ?></label>
                                              <h2>  <?php

                                                      $shipme_get_dimensions_measure = shipme_get_dimensions_measure();
                                                      $length = get_post_meta($pid,'length',true);
                                                      echo $length." ". shipme_get_dimensions_measure();

                                                ?></h2>
                                            </div><!-- dash-content -->
                                          </div><!-- col-3 -->
                                          <div class="col-lg-4">


                                            <div class="dash-content">
                                              <label class="tx-primary"><?php printf(__('%s Height','shipme'), '<i class="fas fa-weight-hanging"></i>') ?></label>
                                              <h2>
                                                <?php

                                                      $shipme_get_dimensions_measure = shipme_get_dimensions_measure();
                                                      $height = get_post_meta($pid,'height',true);
                                                      echo $height." ". shipme_get_dimensions_measure();

                                                ?></h2>
                                            </div><!-- dash-content -->
                                          </div><!-- col-3 -->

                                        </div><!-- row -->
                                      </div>



                    </div></div>



            <div class="card card-job-thing mb-4">
                <div class="card-body">
                      <div class="ship-card-title"><?php _e('Item Main Details','shipme') ?></div>

                      <p class="mg-b-0 post-desc">
                      <?php

                        $descs = $pst->post_content;
                        if(empty($descs)) echo __('There is no description attached to this job.','shipme');
                        else echo $descs;

                       ?>
                     </p>
                </div>
            </div>


            <div class="card card-job-thing mb-4">
                <div class="card-body">
                      <div class="ship-card-title"><?php _e('Item Other Details','shipme') ?></div>
                      <p class="no-gutters mt-2"><?php _e('There are no other details.','shipme') ?></p>
                </div>
            </div>



            <div class="card card-job-thing mb-4">
                <div class="card-body">
                      <div class="ship-card-title"><?php _e('Received Quotes','shipme') ?></div>






                        <?php
                        $uid = get_current_user_id();

                        $shipme_enable_project_files  = get_option('shipme_enable_project_files');
                        $winner                       = get_post_meta(get_the_ID(), 'winner', true);
                        $post                         = get_post(get_the_ID());
                        global $wpdb;
                        $pid = get_the_ID();

                        $bids = "select * from ".$wpdb->prefix."ship_bids where pid='$pid' order by id DESC";
                        $res  = $wpdb->get_results($bids);

                        if($post->post_author == $uid) $owner = 1; else $owner = 0;

                        if(count($res) > 0)
                        {

                          if($private_bids == 'yes' or $private_bids == '1' or $private_bids == 1)
                          {
                          if ($owner == 1) $show_stuff = 1;
                          else if(shipme_current_user_has_bid($uid, $res)) $show_stuff = 1;
                          else $show_stuff = 0;
                          }
                          else $show_stuff = 1;



                              echo '  <table class="mt-4 table"> ';

                              echo '  <thead>
                                        <tr>
                                        <th>'.__('Bidder','shipme').'</th>
                                        <th>'.__('Budget/Price','shipme').'</th>
                                        <th>'.__('Date','shipme').'</th>
                                        <th>'.__('Comment','shipme').'</th>' ;

                                  if($owner == 1) echo '  <th>'.__('Actions','shipme').'</th>';

                              echo '      </tr>                      </thead> <tbody>';





                        //-------------

                        foreach($res as $row)
                        {


                                      if($uid == $row->uid) 	$show_this_around = 1; else $show_this_around = 0;
                                      if($owner == 1) $show_this_around = 1;

                                      if($show_this_around == 1)
                                      {

                                              $user = get_userdata($row->uid);
                                              echo '<tr>';
                                              echo '<td>  <a href="'.shipme_get_user_profile_link($user->ID).'">'.$user->user_login.'</a></td>';
                                              echo '<td>   '.shipme_get_show_price($row->bid).'</td>';
                                              echo '<td>  '.date_i18n("d-M-Y H:i:s", $row->date_made).'</td>';
                                              echo '<td> '.   $row->description  .'</td>';
                                              if ($owner == 1 )
                                              {
                                                if(empty($winner)) // == 0)
                                                echo '<td><ul class="buttons-ul"><li><a data-toggle="modal" data-target="#winnerModal'.$row->id.'" class="btn btn-outline-primary btn-sm" class="btn btn-primary btn-sm" href="'.get_bloginfo('siteurl').'/?s_action=choose_winner&pid='.get_the_ID().'&bid='.$row->id.'">'.__('Select as Winner','shipme').'</a></li><li>
                                               <a class="btn btn-primary btn-sm"  href="'.shipme_get_priv_mess_page_url('send', '', '&uid='.$row->uid.'&pid='.get_the_ID()).'">'.__('Send Message','shipme').'</a></li></ul></td>';
                                                else {
                                                  if($winner == $row->uid)
                                                  echo '<td> '.__('Job Winner','shipme').' </td>';
                                                  else {
                                                    echo '<td> </td>';
                                                  }
                                                }

                                              }



                                              if($shipme_enable_project_files != "no")
                                              {
                                                  if(shipme_see_if_project_files_bid(get_the_ID(), $row->uid) == true)
                                                  {
                                                  echo '<div> <i class="bid-days"></i> ';
                                                  echo '<a href="#" class="get_files" rel="'.get_the_ID().'_'.$row->uid.'">'.__('See Bid Files','shipme').'</a> ';
                                                  echo '</div>';
                                                  }

                                              }


                                              echo '</tr>';
                                      }
                        } // endforeach

                          echo '</tbody></table>';
                        }
                        else { echo '<div class="pt-0 pb-0">'; _e("No proposals placed yet.",'shipme'); echo '</div>'; }
                        ?>





                </div>
            </div>



            <div class="card card-job-thing mb-4">
                <div class="card-body">
                      <div class="ship-card-title"><?php _e('Job Public Questions','shipme') ?></div>


                      <script>

                        function show_form_reply(id)
                        {
                          jQuery("#question_answer"+id).toggle();
                          return false;
                        }

                      </script>

                      <?php
                      global $post;


                        global $wpdb;
                        $s = "select * from ".$wpdb->prefix."ship_message_board where pid='$pid'";
                        $r = $wpdb->get_results($s);


                        if(count($r) == 0)
                        {
                            ?>

                                <p class="no-gutters mt-2"><?php _e('There are no questions yet.','shipme') ?></p>

                            <?php
                        }
                        else
                        {
                            foreach($r as $row)
                            {

                                  $nameTransporter = get_userdata($row->uid);



                                ?>
                                      <div class="question-box">

                                            <p class="question-title"><?php echo $row->content ?></p>
                                            <p><?php printf(__('Posted on: %s by %s','shipme'), date("d-m-Y", $row->datemade ), $nameTransporter->user_login) ?></p>

                                            <?php


                                            $s1 = "select * from ".$wpdb->prefix."ship_message_board_answers where questionid='{$row->id}'";
                                            $r1 = $wpdb->get_results($s1);



                                            if(count($r1) > 0)
                                            {
                                                ?>

                                                <div class="alert alert-secondary">
                                                  <?php _e('Answer','shipme') ?>    <?php echo $r1[0]->content ?>
                                                </div>


                                                <?php
                                            }
                                            else
                                            {


                                                if($post->post_author == get_current_user_id())
                                                {
                                                    ?>

                                                      <p><a href="#" onclick="return show_form_reply(<?php echo $row->id; ?>)" rel="<?php echo $row->id ?>" class="btn btn-sm btn-outline-primary"><?php _e('Answer question','shipme') ?></a></p>
                                                      <div style="display:none"  id="question_answer<?php echo $row->id; ?>">

                                                        <form method="post"> <input type="hidden" name="answer_id" value="<?php echo $row->id; ?>" />
                                                          <textarea name="question_answer" required class="form-control"></textarea>

                                                          <input type="submit" class="btn btn-sm btn-primary mt-2 mb-4" value="<?php _e('Send Answer','shipme') ?>" />
                                                        </form>

                                                      </div>
                                                    <?php
                                                }  }

                                            ?>

                                      </div>

                                <?php
                            }
                        }


                        if($post->post_author != get_current_user_id())
                        {


                      ?>


                      <p>




                        <button type="button" class="btn btn-primary" data-toggle="modal" data-target="#exampleModal">
    <?php _e('Post a question.','shipme') ?>
</button>

                      </p>

                    <?php } ?>



                      <div class="modal fade" id="exampleModal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
                       <div class="modal-dialog" role="document">
                              <form method="post" >
                         <div class="modal-content">
                           <div class="modal-header">
                             <h5 class="modal-title" id="exampleModalLabel">  <?php _e('Post a public question.','shipme') ?></h5>
                             <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                               <span aria-hidden="true">&times;</span>
                             </button>
                           </div>
                           <div class="modal-body">

       <div class="form-group">
         <label for="exampleInputEmail1"><?php _e('Your question.','shipme') ?></label>
      <textarea name="question" required class="form-control" style="min-width: 300px;height:120px"></textarea>
       </div>

                           </div>
                           <div class="modal-footer"> <input type="hidden" name="submit_qq" value="1" />
                             <input type="hidden" name="pid" value="<?php echo get_the_ID() ?>" />
                             <button type="button" class="btn btn-secondary" data-dismiss="modal">  <?php _e('Close','shipme') ?></button>
                             <button type="submit" class="btn btn-primary">  <?php _e('Submit Now','shipme') ?></button>
                           </div>
                         </div></form>
                       </div>
                     </div>



                </div>
            </div>


          </div>

          <!-- ## -->

          <div class="col-lg-4 mg-t-20 mg-lg-t-0">

            <div class="card card-profile mb-4">
              <div class="card-body">
                <?php

                    if(get_current_user_id() == $pst->post_author)
                    {

                      if($closed == "1")
                      {
                              ?>


                                <?php     _e('This job is closed, either expired or winner has been chosen already.','shipme');   ?>


                              <?php
                      }
                      else {
                        // code...

                          ?>

                                <div class="pb-3">  <?php _e('You are the owner of this job','shipme') ?> </div>

                                <div class=""><a href="<?php echo shipme_post_new_with_pid_stuff_thg(get_the_ID(), 1,  'no', 1) ?>" class="btn btn-primary btn-block"><?php _e('Edit Job','shipme') ?></a></div>

                          <?php
                        }//else if closed = 0
                    }
                    else {

                      if($closed == "0")
                      {

                            $bid = shipme_get_bid_of_user(get_the_ID(), get_current_user_id());
                            if($bid != false)
                            {
                                  ?>

                                  <p class="alert alert-secondary">  <?php printf(__('You quoted %s for this job','shipme'),shipme_get_show_price($bid->bid)); ?></p>

                                      <a href="" class="btn btn-success btn-block" data-toggle="modal" data-target='#modaldemo1'><?php echo sprintf(__('Edit Quote','shipme')); ?></a>

                                  <?php
                            }
                            else
                            {

                 ?>
                    <a href="" class="btn btn-success btn-block" data-toggle="modal" data-target='#modaldemo1'><?php _e('Submit Quote','shipme') ?></a>

                  <?php } ?>

                    <div id="modaldemo1" class="modal fade">

                          <div class="modal-dialog modal-dialog-vertical-center" role="document">
                            <div class="modal-content bd-0 tx-14"><form method="post" action="<?php echo the_permalink() ?>">
                              <div class="modal-header">
                                <h6 class="tx-14 mg-b-0 tx-uppercase tx-inverse tx-bold"><?php _e('Submit a quote for this job','shipme') ?></h6>
                                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                  <span aria-hidden="true">&times;</span>
                                </button>
                              </div>

                              <?php

                                  if(!is_user_logged_in())
                                  {
                                    echo '<div class="p-4">';
                                    echo sprintf(__('You need to <a href="%s">login</a> or <a href="%s">register</a> to submit a quote.','shipme'), get_permalink( get_option('shipme_login_page_id') ), get_permalink( get_option('shipme_register_page_id') ));
                                    echo '</div>';
                                  }
                                  else {

                                    if(get_current_user_id() == $post->post_author)
                                    {
                                      echo '<div class="p-4">';
                                      echo sprintf(__('You cannot submit bids to your own job.','shipme'));
                                      echo '</div>';
                                    }
                                    else {
                                      // code...


                               ?>
                              <div class="modal-body pd-25">

                                <div class="row mg-b-25">
                                  <div class="col-lg-12 mb-3">
                                    <div class="form-group">
                                      <label class="form-control-label"><?php _e('Your price:','shipme') ?></label>
                                      <input class="form-control" type="text" name="bid" required value="" placeholder="<?php echo shipme_currency() ?>" />
                                    </div>
                                  </div>



                                  <div class="col-lg-12 mb-3">
                                    <div class="form-group">
                                      <label class="form-control-label"><?php _e('Description:','shipme') ?></label>
                                      <textarea rows="4" cols="60" class="form-control do_input description_edit" required placeholder="<?php _e('Describe here...','shipme') ?>"  name="bid_description"></textarea>
                                    </div>
                                  </div>




                                  </div>

                              </div>
                              <div class="modal-footer">
                                <input type="submit" value="<?php _e('Submit your quote','shipme') ?>" name="submit-bid" class="btn btn-primary" />
                                <button type="button" class="btn btn-secondary" data-dismiss="modal"><?php _e('Close','shipme') ?></button>
                              </div>

                            <?php }} ?></form>
                            </div>
                          </div><!-- modal-dialog -->

                        </div><!-- modal -->

                  <?php
                }else {
                  _e('The job is closed or expired.','shipme');
                }
                } ?>
              </div>
            </div>


<!-- ########### -->



    <!-- ################ -->



            <div class="card card-total-summary mb-4">
              <h6><?php _e('Job estimated budget','shipme') ?></h6>
              <h1><?php echo shipme_get_show_price(get_post_meta($pid,'price',true), 0); ?></h1>
            </div>

            <div class="card card-profile mb-4">
              <div class="card-body">
                <a href=""><img src="<?php echo shipme_get_avatar_square_bigger($post->post_author) ?>" class="avatar_user_job_page" alt=""></a>
                <h4 class="profile-name"><?php echo shp_get_user_names($pst->post_author) ?></h4>

                <p class="rating-show"><span class="rating-stars"><?php echo shipme_get_ship_stars_new($pst->post_author) ?></span></p>

                <p class="mg-b-20"><?php

                      $x1 = get_user_meta($pst->post_author,'your_location',true);
                      if(empty($x1)) echo 'n/a';
                      else echo $x1;

                ?></p>
                <p class="mg-b-20">
                    <?php

                          $personal_info = get_user_meta($pst->post_author, 'personal_info', true);
                          $personal_info = trim($personal_info);

                          if(!empty($personal_info))
                          {

                              $personal_info = substr($personal_info,0, 120);
                              echo $personal_info;
                          }
                          else $personal_info = __('There isnt any info defined.','shipme');


                     ?>

                </p>
                <?php

                      $link = get_permalink(get_option('shipme_login_page_id'));
                      if(is_user_logged_in())
                      {
                        $messages = new shipme_messages();
                        $thid = $messages->get_thread_for_pid($pid, get_current_user_id(), $post->post_author);
                        $link = shipme_get_thread_link($thid);
                      }

                 ?>
                <a href="<?php echo $link ?>" class="btn btn-primary btn-block contact-user-btn"><?php _e('Contact User','shipme') ?></a>
              </div><!-- card-body -->
            </div>




<div class="card card-job-thing mb-4">
    <div class="card-body">

      <?php printf(__("Posted in: %s","shipme"), get_the_term_list( $pid, 'job_ship_cat', '', ', ', '' )) ?>

    </div></div>

    <div class="card card-job-thing mb-4">
        <div class="card-body">
              <div class="ship-card-title"><?php _e('Item Photos','shipme') ?></div>

              <?php

                    $arr  = shipme_get_post_images($pid);
                    $xx_w = 600;
                    $shipme_width_of_project_images = get_option('shipme_width_of_project_images');

                    if(!empty($shipme_width_of_project_images)) $xx_w = $shipme_width_of_project_images;
                    if(!is_numeric($xx_w)) $xx_w = 600;

                    if($arr)
                    {

                    echo '<div class="row mt-4">';

                    foreach($arr as $image)
                    {
                      echo '<div class="col-lg-4 picture-body-pic"><a href="'.shipme_generate_thumb($image, 900,$xx_w).'" rel="image_gal2"><img src="'.shipme_generate_thumb($image, 90,80).'" width="90" class="img_class" /></a></div>';
                    }

                    echo '</div>';
                    }
                    else { echo __('There are no pictures attache.','shipme') ;}

              ?>

        </div>
    </div>



    <div class="card card-job-thing mb-4">
        <div class="card-body">
              <div class="ship-card-title"><?php _e('Item Attached Files','shipme') ?></div>

              <?php





              $attachments = get_posts( array(
   'post_type' => 'attachment',
   'posts_per_page' => -1,
   'post_parent' => $pid,
   'meta_key'		 => 'is_prj_file',
    'meta_value'	 => '1'
) );



                     $vv1 = 0;


                         foreach ($attachments as $attachment)
                         {

                                $url 	= $attachment->post_title;
                                $imggg = $attachment->post_mime_type;



                                echo '<div class="div_div"  id="image_ss'.$attachment->ID.'"><a target="_blank" href="'.wp_get_attachment_url($attachment->ID).'">'.$url.'</a>
                                      </div>';
                                 $vv1++;


                          }


                     if($vv1 == 0) { _e("There are no files attached.","ProjectTheme"); }




              ?>

        </div>
    </div>


                <div class="card card-body pd-25">
                    <h6 class="ship-card-title">Get Connected</h6>
                    <p>Just select any of your available social account to get started.</p>
                    <div class="tx-20">
                      <a href="" class="tx-primary mg-r-5"><i class="fa fa-facebook"></i></a>
                      <a href="" class="tx-info mg-r-5"><i class="fa fa-twitter"></i></a>
                      <a href="" class="tx-danger mg-r-5"><i class="fa fa-google-plus"></i></a>
                      <a href="" class="tx-danger mg-r-5"><i class="fa fa-pinterest"></i></a>
                      <a href="" class="tx-inverse mg-r-5"><i class="fa fa-github"></i></a>
                      <a href="" class="tx-pink mg-r-5"><i class="fa fa-instagram"></i></a>
                    </div>
                  </div>
          </div>


      </div>



      </div>      </div>


    <?php endwhile; ?>


 <?php get_footer() ?>
