<?php

function shipme_theme_post_new_function_fn()
{


    ob_start();


    global $current_user;
    get_currentuserinfo();
    $uid = $current_user->ID;

    $new_job_step =  $_GET['post_new_step'];
    if(empty($new_job_step)) $new_job_step = 1;

    $pid = $_GET['jobid'];
    $post = get_post($pid);


    //-----------------------------

    $edit = $_GET['edit'];


    ?>


    <div class="ship-pageheader">
              <ol class="breadcrumb ship-breadcrumb">
                <li class="breadcrumb-item"><a href="<?php echo get_site_url() ?>"><?php echo __('Home','shipme') ?></a></li>
                  <?php if($edit == 1) { ?>
                      <li class="breadcrumb-item active" aria-current="page"><?php echo __('Post New Delivery','shipme') ?></li>
                    <?php } else { ?>
<li class="breadcrumb-item active" aria-current="page"><?php echo __('Edit Job','shipme') ?></li>
                        <?php } ?>
              </ol>
              <?php if($edit == 1) { ?>
              <h6 class="ship-pagetitle"><?php printf(__("Edit job","shipme") ) ?></h6>
            <?php } else { ?>
              <h6 class="ship-pagetitle"><?php printf(__("Post new delivery job","shipme") ) ?></h6>
            <?php } ?>
            </div>


<?php

if($new_job_step == "1")
{
  global $MYerror, $jobOK;


 ?>

 <script>

jQuery(document).ready(function(){
  initAutocomplete();
});

 </script>

<div class="section-wrapper mg-t-20">

            <?php shp_show_steps_post_new($new_job_step) ?>



          <?php






            $cat 		= wp_get_object_terms($pid, 'job_ship_cat', array('order' => 'ASC', 'orderby' => 'term_id' ));


          if(is_array($MYerror))
        	if($jobOK == 0)
        	{
        		echo '<div class="alert alert-danger mt-4 rounded" role="alert">';

        			echo __('Your form has errors. Please check below, correct the errors, then submit again.','shipme');

        		echo '</div>';

        	}



           ?>

           <form method="post"  id="post-new-form" action="<?php echo shipme_post_new_with_pid_stuff_thg($pid, '1');?>">
           	<input type="hidden" value="11" name="job_submit_step1" />

          <div class="form-layout">
              <div class="row mg-b-25 mg-t-20">

                <div class="col-lg-12 <?php echo shipme_get_post_new_error_thing('job_title') ?>">
                  <?php echo shipme_get_post_new_error_thing_display('job_title') ?>
                  <div class="form-group mg-b-10-force">
                    <label class="form-control-label"><?php _e('Your job title:','shipme') ?></label>
                    <input class="form-control" type="text" name="job_title" id='job_title' placeholder="<?php _e('eg: I need a package moved.','shipme') ?>" value="<?php echo $post->post_title == "Auto Draft" ? "" : $post->post_title ?>" />
                  </div>
                </div>


                <?php

                $pst = $post->post_content;
                $pst = str_replace("<br />","",$pst);

              ?>

                <div class="col-lg-12 mg-t-20 <?php echo shipme_get_post_new_error_thing('job_description') ?>">
                  <?php echo shipme_get_post_new_error_thing_display('job_description') ?>
                  <div class="form-group mg-b-10-force">
                    <label class="form-control-label"><?php _e('Your job description:','shipme') ?></label>
                    <textarea rows="5" cols="60" class="form-control do_input description_edit" placeholder="<?php _e('Describe here your shipping job scope.','shipme') ?>"  name="job_description"><?php echo trim($pst); ?></textarea>
                  </div>
                </div>



                <div class="col-lg-3 mg-t-20 <?php echo shipme_get_post_new_error_thing('length') ?>">
                  <?php echo shipme_get_post_new_error_thing_display('length') ?>
                  <div class="form-group">
                    <label class="form-control-label"><?php _e('Length:','shipme') ?></label>
                    <input type="text" size="50" class="do_input form-control" name="length" id="length" placeholder="<?php echo shipme_get_dimensions_measure() ?>" value="<?php echo get_post_meta($pid,'length',true); ?>" />
                  </div>
                </div>



                <div class="col-lg-3 mg-t-20 <?php echo shipme_get_post_new_error_thing('width') ?>">
                  <?php echo shipme_get_post_new_error_thing_display('width') ?>
                  <div class="form-group">
                    <label class="form-control-label"><?php _e('Width:','shipme') ?></label>
                    <input type="text" size="50" class="do_input form-control" name="width" id="width" placeholder="<?php echo shipme_get_dimensions_measure() ?>" value="<?php echo get_post_meta($pid,'width',true); ?>" />
                  </div>
                </div>


                <div class="col-lg-3 mg-t-20 <?php echo shipme_get_post_new_error_thing('height') ?>">
                  <?php echo shipme_get_post_new_error_thing_display('height') ?>
                  <div class="form-group">
                    <label class="form-control-label"><?php _e('Height:','shipme') ?></label>
                    <input type="text" size="50" class="do_input form-control" name="height" id="height" placeholder="<?php echo shipme_get_dimensions_measure() ?>" value="<?php echo get_post_meta($pid,'height',true); ?>" />
                  </div>
                </div>


                <div class="col-lg-3 mg-t-20 <?php echo shipme_get_post_new_error_thing('weight') ?>">
                  <?php echo shipme_get_post_new_error_thing_display('weight') ?>
                  <div class="form-group">
                    <label class="form-control-label"><?php _e('Weight:','shipme') ?></label>
                    <input type="text" size="50" class="do_input form-control" name="weight" id="weight" placeholder="<?php echo shipme_get_weight_measure() ?>" value="<?php echo get_post_meta($pid,'weight',true); ?>" />
                  </div>
                </div>




                <div class="col-lg-6 mg-t-20 <?php echo shipme_get_post_new_error_thing('price') ?>">
                  <?php echo shipme_get_post_new_error_thing_display('price') ?>
                  <div class="form-group">
                      <label class="form-control-label"><?php _e('Job Price:','shipme') ?></label>
                   <input type="text" size="50" class="do_input form-control" name="price" id="price" placeholder="<?php echo shipme_get_currency() ?>" value="<?php echo get_post_meta($pid,'price',true); ?>" />
                </div>
                  </div>



                  <div class="col-lg-6 mg-t-20 <?php echo shipme_get_post_new_error_thing('packages') ?>">
                    <?php echo shipme_get_post_new_error_thing_display('packages') ?>
                    <div class="form-group">
                        <label class="form-control-label"><?php _e('Number of packages:','shipme') ?></label>
                     <input type="text" size="50" class="do_input form-control" id="packages" name="packages" placeholder="<?php _e('eg: 2','shipme') ?>" value="<?php echo get_post_meta($pid,'packages',true); ?>" />
                  </div>
                    </div>


                <script>

                      function display_subcat(vals)
                      {
                        jQuery.post("<?php bloginfo('siteurl'); ?>/?get_subcats_for_me=1", {queryString: ""+vals+""}, function(data){
                          if(data.length >0) {

                            jQuery('#sub_cats').html(data);

                          }
                        });

                      }


                      function display_subcat2(vals)
                      {
                        jQuery.post("<?php bloginfo('siteurl'); ?>/?get_locscats_for_me=1", {queryString: ""+vals+""}, function(data){
                          if(data.length >0) {

                            jQuery('#sub_locs').html(data);
                            jQuery('#sub_locs2').html("&nbsp;");

                          }
                          else
                          {
                            jQuery('#sub_locs').html("&nbsp;");
                            jQuery('#sub_locs2').html("&nbsp;");
                          }
                        });

                      }

                      function display_subcat3(vals)
                      {
                        jQuery.post("<?php bloginfo('siteurl'); ?>/?get_locscats_for_me2=1", {queryString: ""+vals+""}, function(data){
                          if(data.length >0) {

                            jQuery('#sub_locs2').html(data);

                          }
                        });

                      }

                      </script>



                <div class="col-lg-12 mg-t-20 <?php echo shipme_get_post_new_error_thing('jb_category') ?>">
                  <?php echo shipme_get_post_new_error_thing_display('jb_category') ?>
                    <div class="form-group">
                          <label class="form-control-label"><?php echo __('Job Category', 'shipme'); ?></label>



                  <p class="strom_100">



                    <?php if(get_option('shipme_enable_multi_cats') == "yes"): ?>
                <div class="multi_cat_placeholder_thing">

                      <?php

                  $selected_arr = shipme_build_my_cat_arr($pid);
                  echo shipme_get_categories_multiple('job_ship_cat', $selected_arr);

                ?>

                    </div>

                    <?php else: ?>

                <?php

                echo shipme_get_categories_clck("job_ship_cat",    (is_array($cat) ? $cat[0]->term_id : "") , __('Select Category','shipme'), "form-control do_input", 'onchange="display_subcat(this.value)"' );


                        echo '<br/><span id="sub_cats">';


                              if(!empty($cat[1]->term_id))
                              {
                                $args2 = "orderby=name&order=ASC&hide_empty=0&parent=".$cat[0]->term_id;
                                $sub_terms2 = get_terms( 'job_ship_cat', $args2 );

                                $ret = '<select class="form-control do_input" name="subcat">';
                                $ret .= '<option value="">'.__('Select Subcategory','shipme'). '</option>';
                                $selected1 = $cat[1]->term_id;

                                foreach ( $sub_terms2 as $sub_term2 )
                                {
                                  $sub_id2 = $sub_term2->term_id;
                                  $ret .= '<option '.($selected1 == $sub_id2 ? "selected='selected'" : " " ).' value="'.$sub_id2.'">'.$sub_term2->name.'</option>';

                                }
                                $ret .= "</select>";
                                echo $ret;


                              }

                            echo '</span>';

                ?>
                    <?php endif; ?>


                    </p>
                </div>
                  </div>

                </div>
            </div>

                  <!-- ######################### -->

                   <script src="<?php bloginfo('template_url') ?>/js/picker.js"  ></script>
                   <script src="<?php bloginfo('template_url') ?>/js/picker.date.js"  ></script>
                   <script src="<?php bloginfo('template_url') ?>/js/legacy.js"  ></script>


                  <link rel="stylesheet" type="text/css" href="<?php bloginfo('template_url') ?>/css/datepicker/classic.css">
                  <link rel="stylesheet" type="text/css" href="<?php bloginfo('template_url') ?>/css/datepicker/classic.date.css">

                  <script src="<?php echo get_template_directory_uri(); ?>/js/script-post-new.js" ></script>
              <!--    <script src="https://maps.googleapis.com/maps/api/js?signed_in=true&libraries=places&callback=initAutocomplete&key=<?php echo get_option('shipme_radius_maps_api_key'); ?>" async defer></script> -->

                  <!-- ######################## -->

                  <label class="section-title"><?php _e('Package Pickup','shipme'); ?></label>

                  <div class="form-layout">
                      <div class="row mg-b-25 mg-t-20">



                        <div class="col-lg-12 mg-t-20 <?php echo shipme_get_post_new_error_thing('pickup_location') ?>">
                          <?php echo shipme_get_post_new_error_thing_display('pickup_location') ?>
                          <div class="form-group">
                          <label class="form-control-label"><?php echo __('Location (address/zip)', 'shipme'); ?></label>
                           <input type="text" size="50" onFocus="geolocate_pickup()" id="autocomplete_pickup" class="do_input form-control" name="pickup_location"
                            placeholder="<?php _e('eg: New York, 15th ave','shipme') ?>" value="<?php echo get_post_meta($pid,'pickup_location',true); ?>" />
                        </div></div>

                        <input type="hidden" value="<?php echo get_post_meta($pid,'pickup_lat',true) ?>"  name="pickup_lat" id="pickup_lat"  />
                        <input type="hidden" value="<?php echo get_post_meta($pid,'pickup_lng',true) ?>"  name="pickup_lng" id="pickup_lng"  />


                        <div class="col-lg-12 mg-t-20 <?php echo shipme_get_post_new_error_thing('pickup_date') ?>">
                          <?php echo shipme_get_post_new_error_thing_display('pickup_date') ?>
                          <div class="form-group">
                          <label class="form-control-label"><?php echo __('Pickup Date', 'shipme'); ?></label>
                           <input type="text" size="50" class="do_input form-control" id="pickup_date" placeholder="<?php _e('click here to choose date','shipme') ?>"
                            value="<?php $zz = get_post_meta($pid,'pickup_date',true); echo (!empty($zz) ? date("j F, Y", $zz) : '') ; ?>"  />
                        </div></div>

                        <input type="hidden" value="<?php echo get_post_meta($pid,'pickup_date',true) ?>"  name="pickup_date" id="pickup_date_hidden"  />


                      </div>
                  </div>


                    <!-- ######################### -->

                  <label class="section-title"><?php _e('Package Delivery','shipme'); ?></label>

                  <div class="form-layout">
                      <div class="row mg-b-25 mg-t-20">




                        <div class="col-lg-12 mg-t-20 <?php echo shipme_get_post_new_error_thing('delivery_location') ?>">
                          <?php echo shipme_get_post_new_error_thing_display('delivery_location') ?>
                          <div class="form-group">
                   <label class="form-control-label"><?php echo __('Location (address/zip)', 'shipme'); ?></label>
                    <input type="text" size="50" class="do_input form-control" onFocus="geolocate_delivery()"  id="autocomplete_delivery" name="delivery_location"
                     placeholder="<?php _e('eg: California, San Francisco, Lombard St','shipme') ?>" value="<?php echo get_post_meta($pid,'delivery_location',true); ?>" />
                 </div></div>

                 <input type="hidden" value="<?php echo get_post_meta($pid,'delivery_lat',true) ?>"  name="delivery_lat" id="delivery_lat"  />
                 <input type="hidden" value="<?php echo get_post_meta($pid,'delivery_lng',true) ?>"  name="delivery_lng" id="delivery_lng"  />


                 <div class="col-lg-12 mg-t-20 <?php echo shipme_get_post_new_error_thing('delivery_date') ?>">
                   <?php echo shipme_get_post_new_error_thing_display('delivery_date') ?>
                   <div class="form-group">
                   <label class="form-control-label"><?php echo __('Delivery Date', 'shipme'); ?></label>
                   <input type="text" size="50" class="do_input form-control" id="delivery_date" placeholder="<?php _e('click here to choose date','shipme') ?>"
                     value="<?php $zz = get_post_meta($pid,'delivery_date',true); echo !empty($zz) ? date("j F, Y", $zz) : '' ; ?>" />
                 </div></div>


                 <input type="hidden" value="<?php echo get_post_meta($pid,'delivery_date',true) ?>"  name="delivery_date" id="delivery_date_hidden"  />




                      </div>
                  </div>


                  <div class="form-layout-footer">

                      <input type="submit" class="btn btn-oblong btn-primary bd-0" value="<?php _e('Next Step','shipme'); ?>" name="form_1_submit" />

                    </div>


            </form>
          </div>
            <?php
              }
                elseif($new_job_step == "2")
                {

                  //**********************************************************
                  //
                  //      step 2
                  //
                  //**********************************************************

                    ?>


                    <div class="section-wrapper mg-t-20">

                                <?php shp_show_steps_post_new($new_job_step) ?>


                    <form method="post"  id="post-new-form" action="<?php echo shipme_post_new_with_pid_stuff_thg($pid, '2');?>">
                      <input type="hidden" value="11" name="job_submit_step2" />

                    <div class="form-layout">
                        <div class="row mg-b-25 mg-t-20">


                          <div class="col-lg-12 mg-t-20 ">

                            <?php $i = 0;


                            $shipme_enable_featured_option = get_option('shipme_enable_featured_option');
                            $shipme_enable_sealed_option = get_option('shipme_enable_sealed_option');


                            // checking fees here featured
                            
                                    $feat = get_option('shipme_featured_fee');
                                    if($feat > 0 and $shipme_enable_featured_option != 'no')
                                    {



                                      $i++;
                             ?>

                            <div class="form-group">
                              <input type="checkbox" value="1" name="featured" <?php echo shipme_is_job_featured($pid) ? "checked='checked'" : ""; ?>/> <?php echo sprintf(__('Featured jobs. Makes your job to appear first in searches. Extra cost: %s','shipme'), shipme_get_show_price($feat)); ?>
                              </div>

                            <?php } ?>




                            <?php

                                    $priv = get_option('shipme_private_job_fee');
                                    if($priv > 0)
                                    {  $i++;
                             ?>

                            <div class="form-group">
                              <input type="checkbox" value="1" name="private" <?php echo shipme_is_job_hidden_job($pid) ? "checked='checked'" : ""; ?> /> <?php echo sprintf(__('Private Job. The user must login/register to see the job. Extra cost: %s','shipme'), shipme_get_show_price($priv)); ?>
                              </div>

                            <?php } ?>




                            <?php

                                    $sealed = get_option('shipme_sealed_bidding_fee');
                                    if($sealed > 0 and $shipme_enable_sealed_option != 'no')
                                    { $i++;
                             ?>

                            <div class="form-group">
                              <input type="checkbox" value="1" name="sealed_bidding" <?php echo shipme_is_job_private_bidding($pid) ? "checked='checked'" : ""; ?> /> <?php echo sprintf(__('Sealed job. Makes the bid to be hidden, only show to you. Extra cost: %s','shipme'), shipme_get_show_price($sealed)); ?>
                              </div>

                            <?php }


                                if($i) { echo '  <div class="form-group"><hr color="#eeeeee" /></div>';}

                            ?>



                            <div class="form-group">
                                <label class="form-control-label"><?php echo __('Add Photos', 'shipme'); ?></label>

                                <div class="cross_cross">



                          <script type="text/javascript" src="<?php echo get_bloginfo('template_url'); ?>/js/dropzone.js"></script>
                          <link rel="stylesheet" href="<?php echo get_bloginfo('template_url'); ?>/css/dropzone.css" type="text/css" />



                            <script>


                          jQuery(function() {

                        Dropzone.autoDiscover = false;
                        var myDropzoneOptions = {
                          maxFilesize: 15,
                            addRemoveLinks: true,
                          acceptedFiles:'image/*',
                            clickable: true,
                          url: "<?php bloginfo('siteurl') ?>/?my_upload_of_job_files2=1",
                        };

                        var myDropzone = new Dropzone('div#myDropzoneElement2', myDropzoneOptions);

                        myDropzone.on("sending", function(file, xhr, formData) {
                          formData.append("author", "<?php echo $cid; ?>"); // Will send the filesize along with the file as POST data.
                          formData.append("ID", "<?php echo $pid; ?>"); // Will send the filesize along with the file as POST data.
                        });


                            <?php

                            $args = array(
                          'order'          => 'ASC',
                          'orderby'        => 'menu_order',
                          'post_type'      => 'attachment',
                          'post_parent'    => $pid,
                          'post_status'    => null,
                          'post_mime_type' => 'image',
                          'numberposts'    => -1,
                          );
                          $attachments = get_posts($args);

                          if($pid > 0)
                          if ($attachments)
                          {
                              foreach ($attachments as $attachment)
                            {
                              $url = $attachment->guid;
                              $imggg = $attachment->post_mime_type;
                              $url = wp_get_attachment_url($attachment->ID);

                                ?>
                                    var mockFile = { name: "<?php echo $attachment->post_title ?>", size: <?php echo filesize( get_attached_file( $attachment->ID ) ) ?>, serverId: '<?php echo $attachment->ID ?>' };
                                    myDropzone.options.addedfile.call(myDropzone, mockFile);
                                    myDropzone.options.thumbnail.call(myDropzone, mockFile, "<?php echo shipme_generate_thumb($attachment->ID, 100, 100) ?>");

                                <?php
                            }
                          }

                          ?>

                          myDropzone.on("success", function(file, response) {
                            /* Maybe display some more file information on your page */
                           file.serverId = response;
                           file.thumbnail = "<?php echo bloginfo('template_url') ?>/images/file_icon.png";


                          });


                        myDropzone.on("removedfile", function(file, response) {
                            /* Maybe display some more file information on your page */
                            delete_this2(file.serverId);

                          });

                          });

                          </script>

                          <?php _e('Click the grey area below to add job images. Other files are not accepted. Use the form below.','shipme') ?>
                            <div class="dropzone dropzone-previews" id="myDropzoneElement2" ></div>

                              </div>
                            </div>


                        </div>

                        <!-- #### -->


                        <div class="col-lg-12 mg-t-20 ">
                          <div class="form-group">
                              <label class="form-control-label"><?php echo __('Add Files', 'shipme'); ?></label>
                              <div class="cross_cross">

                                <script>


                              jQuery(function() {

                            Dropzone.autoDiscover = false;
                            var myDropzoneOptions = {
                              maxFilesize: 15,
                                addRemoveLinks: true,
                              acceptedFiles:'.zip,.pdf,.rar,.doc,.docx,.xls,.xlsx,.ppt,.pptx,.psd,.ai',
                                clickable: true,
                              url: "<?php bloginfo('siteurl') ?>/?my_upload_of_jb_files_proj=1",
                            };

                            var myDropzone = new Dropzone('div#myDropzoneElement', myDropzoneOptions);

                            myDropzone.on("sending", function(file, xhr, formData) {
                              formData.append("author", "<?php echo $cid; ?>"); // Will send the filesize along with the file as POST data.
                              formData.append("ID", "<?php echo $pid; ?>"); // Will send the filesize along with the file as POST data.
                            });


                                <?php

                                $args = array(
                              'order'          => 'ASC',
                              'orderby'        => 'menu_order',
                              'post_type'      => 'attachment',
                              'meta_key' 		=> 'is_prj_file',
                              'meta_value' 	=> '1',
                              'post_parent'    => $pid,
                              'post_status'    => null,
                              'numberposts'    => -1,
                              );
                              $attachments = get_posts($args);

                              if($pid > 0)
                              if ($attachments) {
                                  foreach ($attachments as $attachment) {
                                $url = $attachment->guid;
                                $imggg = $attachment->post_mime_type;

                                if('image/png' != $imggg && 'image/jpeg' != $imggg)
                                {
                                $url = wp_get_attachment_url($attachment->ID);


                                  ?>

                                      var mockFile = { name: "<?php echo $attachment->post_title ?>", size: <?php echo filesize( get_attached_file( $attachment->ID ) ) ?>, serverId: '<?php echo $attachment->ID ?>' };
                                      myDropzone.options.addedfile.call(myDropzone, mockFile);
                                      myDropzone.options.thumbnail.call(myDropzone, mockFile, "<?php echo bloginfo('template_url') ?>/images/file_icon.png");


                                  <?php


                              }
                              }}


                              ?>



                            myDropzone.on("success", function(file, response) {
                                /* Maybe display some more file information on your page */
                               file.serverId = response;
                               file.thumbnail = "<?php echo bloginfo('template_url') ?>/images/file_icon.png";


                              });


                            myDropzone.on("removedfile", function(file, response) {
                                /* Maybe display some more file information on your page */
                                delete_this2(file.serverId);

                              });

                              });

                              </script>

                                <script type="text/javascript">

                              function delete_this2(id)
                              {
                                 jQuery.ajax({
                                        method: 'get',
                                        url : '<?php echo get_bloginfo('siteurl');?>/index.php?_ad_delete_pid='+id,
                                        dataType : 'text',
                                        success: function (text) {   jQuery('#image_ss'+id).remove();  }
                                       });
                                  //alert("a");

                              }





                              </script>

                              <?php _e('Click the grey area below to add job files. Images are not accepted.','shipme') ?>
                                <div class="dropzone dropzone-previews" id="myDropzoneElement" ></div>


                              </div>
                            </div>
                          </div>


                          <div class="form-layout-footer mt-5">
                              <a class="btn btn-oblong btn-secondary bd-0" href='<?php echo shipme_post_new_with_pid_stuff_thg($pid, '1');?>'><?php _e('Go Back','shipme'); ?></a>
                              <input type="submit" class="btn btn-oblong btn-primary bd-0" value="<?php _e('Next Step','shipme'); ?>" name="form_2_submit" />

                            </div>

                      </div></div>
                    </form>
                  </div>

                    <?php

                }


                elseif($new_job_step == "3")
                {

                  //**********************************************************
                  //
                  //      step 3
                  //
                  //**********************************************************


                  ?>
                  <div class="section-wrapper mg-t-20">

                              <?php shp_show_steps_post_new($new_job_step) ?>

                  <form method="post"  id="post-new-form" action="<?php echo shipme_post_new_with_pid_stuff_thg($pid, '4');?>">
                    <input type="hidden" value="11" name="job_submit_step3" />

                  <div class="form-layout-footer mt-5">
                      <a class="btn btn-oblong btn-secondary bd-0"  href='<?php echo shipme_post_new_with_pid_stuff_thg($pid, '2');?>' ><?php _e('Go Back','shipme'); ?></a>
                      <input type="submit" class="btn btn-oblong btn-primary bd-0" value="<?php _e('Next Step','shipme'); ?>" name="form_3_submit" />

                    </div>


                  </form>
                </div>

                <?php


                      $pst = get_post($pid);


                ?>

                <div class="ship-pageheader">
                  <ol class="breadcrumb ship-breadcrumb"> </ol>
                    <h6 class="ship-pagetitle"><?php echo $pst->post_title ?></h6>
                  </div>


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
                                                  <h2 id="distance_distance">n/a km</h2>
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
                                  <p class="no-gutters mt-2"><?php _e('There are no quotes yet.','shipme') ?></p>
                            </div>
                        </div>



                        <div class="card card-job-thing mb-4">
                            <div class="card-body">
                                  <div class="ship-card-title"><?php _e('Job Public Questions','shipme') ?></div>
                                  <p class="no-gutters mt-2"><?php _e('There are no questions yet.','shipme') ?></p>
                            </div>
                        </div>


                      </div>

                      <!-- ## -->

                      <div class="col-lg-4 mg-t-20 mg-lg-t-0">

                        <div class="card card-profile mb-4">
                          <div class="card-body">
                                <a href="#" class="btn btn-success btn-block"><?php _e('Submit Quote','shipme') ?></a>
                          </div>
                        </div>

                        <div class="card card-total-summary mb-4">
                          <h6><?php _e('Job estimated budget','shipme') ?></h6>
                          <h1><?php echo shipme_get_show_price(get_post_meta($pid,'price',true), 0); ?></h1>
                        </div>

                        <div class="card card-profile mb-4">
                          <div class="card-body">
                            <a href=""><img src="<?php echo shipme_get_avatar_square($pst->post_author ); ?>" alt=""></a>
                            <h4 class="profile-name"><?php echo shp_get_user_names($pst->post_author) ?></h4>

                            <p class="rating-show"><span class="rating-stars"><?php echo shipme_get_ship_stars_new($pst->post_author) ?></span></p>

                            <p class="mg-b-20"><?php

                                  $x1 = get_user_meta($pst->post_author,'your_location',true);
                                  if(empty($x1)) echo 'n/a';
                                  else echo $x1;

                            ?></p>
                            <p class="mg-b-20"><?php

                                  $personal_info = get_user_meta($pst->post_author, 'personal_info', true);
                                  $personal_info = trim($personal_info);

                                  if(!empty($personal_info))
                                  {

                                      $personal_info = substr($personal_info,0, 120);
                                      echo $personal_info;
                                  }
                                  else $personal_info = __('There isnt any info defined.','shipme');


                             ?></p>


                          </div><!-- card-body -->
                        </div>


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
                                          echo '<div class="col-lg-6 picture-body-pic"><a href="'.shipme_generate_thumb($image, 900,$xx_w).'" rel="image_gal2"><img src="'.shipme_generate_thumb($image, 100,80).'" width="100" class="img_class" /></a></div>';
                                        }

                                        echo '</div>';
                                        }
                                        else { echo __('There are no pictures attache.','shipme') ;}

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



                  <?php




                }
                elseif($new_job_step == "4")
                {

                  //**********************************************************
                  //
                  //      step 4
                  //
                  //**********************************************************


                    $array  = ship_calculate_listing_fees_of_job($pid);
                    $total = $array['total'];



                  ?>


                  <div class="section-wrapper mg-t-20">

                              <?php shp_show_steps_post_new($new_job_step) ?>

                  <div class="form-layout-footer mt-5">

                    <?php

                    if($total == 0)
                    {

                        if($_GET['finalize'] == "1")
                        {
                              $shipme_admin_approves_each_job = get_option('shipme_admin_approves_each_job');
                              if($shipme_admin_approves_each_job == "yes")
                              {

                                        $my_post = array();
                                        $my_post['ID'] = $pid;
                                        $my_post['post_status'] = 'draft';

                                        wp_update_post( $my_post );


                                          shipme_send_email_posted_job_not_approved($pid);
                                          shipme_send_email_posted_job_not_approved_admin($pid);


                                    ?>


                                            <h6 class="text-secondary pb-4"><?php _e('Your job has been posted, but is not approved yet. The admin will approve and you will be notified.','shipme'); ?></h6>

                                    <?php
                              }
                              else {

                                          $my_post = array();
                                          $my_post['ID']          = $pid;
                                          $my_post['post_status'] = 'publish';


                                              wp_update_post( $my_post );
                                              wp_publish_post( $pid );


                                              shipme_send_email_posted_job_approved($pid);
                                              shipme_send_email_posted_job_approved_admin($pid);

                                              if(function_exists('shipme_send_email_subscription'))
                                              shipme_send_email_subscription($pid);


                              ?>
                                    <h6 class="text-secondary pb-4"><?php _e('Your job has been now posted in our website.','shipme'); ?></h6>


                            <?php } ?>

                                <button class="btn btn-oblong btn-primary bd-0"  onclick="location.href='<?php echo  get_permalink(get_option('shipme_account_page_id')) ?>'" ><?php _e('Go to your account','shipme'); ?></button>

                                <?php if($shipme_admin_approves_each_job != "yes") { ?>
                                <button class="btn btn-oblong btn-primary bd-0"  onclick="location.href='<?php echo  get_permalink($pid) ?>'" ><?php _e('See job page','shipme'); ?></button>
                                <?php } ?>

                        <?php
                        }
                        else {




                        ?>
                                <h6 class="text-success pb-4"><?php _e('You are about to publish your job in our website.','shipme'); ?></h6>

                        <?php }

                    }
                    if($_GET['finalize'] != "1")
                    {


                     ?>

                      <button class="btn btn-oblong btn-secondary bd-0"  onclick="location.href='<?php echo shipme_post_new_with_pid_stuff_thg($pid, '3');?>'" ><?php _e('Go Back','shipme'); ?></button>

                    <?php }

                          if($total == 0 and $_GET['finalize'] != "1")
                          {
                             ?>


                             <button class="btn btn-oblong btn-primary bd-0"  onclick="location.href='<?php echo shipme_post_new_with_pid_stuff_thg($pid, '4', 'yes');?>'" ><?php _e('Publish Job','shipme'); ?></button>


                             <?php
                          }



                      ?>


                    </div>

                    <?php

                    if($total > 0)
                    {


                      ?>


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



            <?php

                $shipme_enable_credits_wallet = get_option('shipme_enable_credits_wallet');
                if($shipme_enable_credits_wallet == "yes")
                {
                    $cr = shipme_get_credits(get_current_user_id());
                    if($cr >= $total)
                    {

                    ?>

                            <div class="col-lg-12 mt-3 tx-right"><button onclick="location.href='<?php echo shipme_get_post_new_pay_balance($pid) ?>'" class="btn btn-outline-success btn-block mg-b-10"><?php _e('Pay by eWallet Balance','shipme') ?></button></div>

                    <?php
                    }
                }



                $shipme_paypal_enable = get_option('shipme_paypal_enable');
                if($shipme_paypal_enable == "yes")
                {

            ?>
            <div class="col-lg-12 mt-3 tx-right"><button class="btn btn-outline-success btn-block mg-b-10" onclick="window.location='<?php echo get_site_url() ?>/?pay_listing_paypal=1&pid=<?php echo $pid ?>'"><?php _e('Pay by PayPal','shipme') ?></button></div>

          <?php } ?>



            <?php do_action('shipme_payment_for_listing_fees', $pid) ?>




          <?php }//end total=0 ?>
</div>


                  <?php




                } else {
                  echo 'nothing here to see';
                }

              ?>



  <div class="col-lg-12 mt-5"></div>


    <?php



    $page = ob_get_contents();
     ob_end_clean();

     return $page;

}

 ?>
