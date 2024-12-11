<?php


function shipme_theme_my_account_profile_settings_new()
{

  ob_start();

  $user = wp_get_current_user();
  $name = $user->user_login;

  $credits = shipme_get_credits($user->ID);
  $uid = get_current_user_id();


  $user = get_userdata($uid);

  ?>

  <div class="ship-pageheader">
            <ol class="breadcrumb ship-breadcrumb">
              <li class="breadcrumb-item"><a href="<?php echo get_site_url() ?>"><?php echo __('Home','shipme') ?></a></li>
              <li class="breadcrumb-item" ><a href="<?php echo get_permalink(get_option('shipme_account_page_id')) ?>"><?php echo __('My Account','shipme') ?></a></li>
              <li class="breadcrumb-item active" aria-current="page"><?php echo __('Profile Settings','shipme') ?></li>
            </ol>
            <h6 class="ship-pagetitle"><?php printf(__("Profile Settings","shipme") ) ?></h6>
          </div>

          <?php echo shp_account_main_menu(); ?>


<?php
          if(isset($_POST['save_info']))
  				{
  					$personal_info = strip_tags(nl2br($_POST['personal_info']), '<br />');
  					update_user_meta($uid, 'personal_info', substr($personal_info,0,500));

  					update_user_meta($uid, 'user_location', $_POST['job_location_cat']);


  					$first_name = $_POST['first_name'];
  					$last_name 	= $_POST['last_name'];

  					$user_id 	= wp_update_user( array( 'ID' => $uid, 'first_name' => $first_name, 'last_name' => $last_name ) );


  					if(isset($_POST['password']))
  					{


  						if(  !empty($_POST['password'])):
  						$p1 = trim($_POST['password']);
  						$p2 = trim($_POST['reppassword']);




    										$user_lat = trim($_POST['user_lat']);
    										update_user_meta($uid, 'user_lat', $user_lat);

    															$user_lon = trim($_POST['user_lon']);
    															update_user_meta($uid, 'user_lon', $user_lon);


  						if($p1 == $p2)
  						{

  							global $wpdb;
  							$newp = md5($p1);
  							$sq = "update ".$wpdb->prefix."users set user_pass='$newp' where ID='$uid'" ;
  							$wpdb->query($sq);
  						} else echo '<div class="alert alert-danger">'.__('Password was not changed. It does not match the password confirmation.','shipme').'</div>';
  						endif;
  					}


  					$personal_info = trim($_POST['paypal_email']);
  					update_user_meta($uid, 'paypal_email', $personal_info);

  					$user_full_name = trim($_POST['user_full_name']);
  					update_user_meta($uid, 'user_full_name', $user_full_name);

  					$your_location = trim($_POST['your_location']);
  					update_user_meta($uid, 'your_location', $your_location);

  					require_once(ABSPATH . "wp-admin" . '/includes/file.php');
  					require_once(ABSPATH . "wp-admin" . '/includes/image.php');

  					if(!empty($_FILES['avatar']["name"]))
  					{

  						$upload_overrides 	= array( 'test_form' => false );
                 			$uploaded_file 		= wp_handle_upload($_FILES['avatar'], $upload_overrides);

  						$file_name_and_location = $uploaded_file['file'];
                  		$file_title_for_media_library = $_FILES['avatar'  ]['name'];

  						$file_name_and_location = $uploaded_file['file'];
  						$file_title_for_media_library = $_FILES['avatar']['name'];

  						$arr_file_type 		= wp_check_filetype(basename($_FILES['avatar']['name']));
  						$uploaded_file_type = $arr_file_type['type'];
  						$urls  = $uploaded_file['url'];



  						if($uploaded_file_type == "image/png" or $uploaded_file_type == "image/jpg" or $uploaded_file_type == "image/jpeg" or $uploaded_file_type == "image/gif" )
  						{

  							$attachment = array(
  											'post_mime_type' => $uploaded_file_type,
  											'post_title' => 'User Avatar',
  											'post_content' => '',
  											'post_status' => 'inherit',
  											'post_parent' =>  0,
  											'post_author' => $uid,
  										);



  							$attach_id = wp_insert_attachment( $attachment, $file_name_and_location, 0 );
  							$attach_data = wp_generate_attachment_metadata( $attach_id, $file_name_and_location );
  							wp_update_attachment_metadata($attach_id,  $attach_data);


  							$_wp_attached_file = get_post_meta($attach_id,'_wp_attached_file',true);

  							if(!empty($_wp_attached_file))
  							update_user_meta($uid, 'avatar_ship', ($attach_id) );



  						}




  					}
              do_action('shipme_profile_info_save');

  						echo '<div class="alert alert-success">'.__("Information saved!","shipme").'</div>';

  				} ?>


<form method="post" enctype="multipart/form-data">
          <div class="section-wrapper mg-t-20">
            <div class="form-layout">
                <div class="row mg-b-25 ">


              <div class="col-lg-12 mb-4">
                <div class="form-group mg-b-10-force">
                  <label class="form-control-label"><?php _e('First name:','shipme') ?></label>
                  <input class="form-control" type="text" name="first_name" id='first_name' value="<?php echo $user->first_name;  ?>" />
                </div>
              </div>


              <div class="col-lg-12 mb-4">
                <div class="form-group mg-b-10-force">
                  <label class="form-control-label"><?php _e('Last name:','shipme') ?></label>
                  <input class="form-control" type="text" name="last_name" id='last_name' value="<?php echo $user->last_name;  ?>" />
                </div>
              </div>


              <script>

              function fillInAddress_z1() {
                    // Get the place details from the autocomplete object.
                    var place = autocomplete.getPlace();
                    var lat = place.geometry.location.lat();
                    var lng = place.geometry.location.lng();


                    document.getElementById('user_lat').value = lat;
                    document.getElementById('user_lon').value = lng;

                  }


              function initAutocomplete_z1()
              {
                   // Create the autocomplete object, restricting the search to geographical
                   // location types.
                   autocomplete = new google.maps.places.Autocomplete(
                     /** @type {!HTMLInputElement} */(document.getElementById('your_location')),
                     {types: ['geocode']});

                   // When the user selects an address from the dropdown, populate the address
                   // fields in the form.
                   autocomplete.addListener('place_changed', fillInAddress_z1);

              }

              jQuery(document).ready(function()
            {
                    initAutocomplete_z1();
            });


              </script>


              <div class="col-lg-12 mb-4">
                <div class="form-group mg-b-10-force">
                  <label class="form-control-label"><?php _e('Your Location:','shipme') ?></label>
                  <input class="form-control" type="text" name="your_location" id='your_location' value="<?php echo get_user_meta($uid,'your_location', true);  ?>" />
                </div>
              </div>




              											<input type="hidden" size="35" name="user_lat" id="user_lat" value="<?php echo get_user_meta($uid, 'user_lat', true); ?>" class="form-control" />
              											<input type="hidden" size="35" name="user_lon" id="user_lon" value="<?php echo get_user_meta($uid, 'user_lon', true); ?>" class="form-control" />




              <div class="col-lg-6 mb-4">
                <div class="form-group mg-b-10-force">
                  <label class="form-control-label"><?php _e('Password:','shipme') ?></label>
                  <input class="form-control" type="password" name="password" id='password'   />
                </div>
              </div>


              <div class="col-lg-6 mb-4">
                <div class="form-group mg-b-10-force">
                  <label class="form-control-label"><?php _e('Repeat Password:','shipme') ?></label>
                  <input class="form-control" type="password" name="reppassword" id='reppassword'   />
                </div>
              </div>


              <div class="col-lg-12 mb-4">
                <div class="form-group mg-b-10-force">
                  <label class="form-control-label"><?php _e('PayPal E-mail:','shipme') ?></label>
                  <input class="form-control" type="text" name="paypal_email" id='paypal_email' value="<?php echo get_user_meta($uid,'paypal_email', true);  ?>" />
                </div>
              </div>


              <div class="col-lg-6 mb-4">
                <div class="form-group mg-b-10-force">
                  <label class="form-control-label"><?php _e('Profile Description:','shipme') ?></label>
                  <textarea rows="4" cols="60" class="form-control do_input description_edit" id="personal_info"   name="personal_info"><?php echo get_user_meta($uid,'personal_info', true);  ?></textarea>
                </div>
              </div>


              <div class="col-lg-6 mb-4">
                <div class="form-group mg-b-10-force">
                  <label class="form-control-label"><?php _e('Profile Avatar:','shipme') ?></label> <br/>
                  <input type="file" class="do_input" name="avatar" /> <br/><br/>

                    <img width="50" height="50" border="0" src="<?php echo shipme_get_avatar($uid,50,50); ?>" />
                </div>
              </div>


              <?php do_action('shipme_profile_fields') ?>



                                <div class="form-layout-footer mt-5">

                                    <input type="submit" class="btn btn-oblong btn-primary bd-0" value="<?php _e('Save Information','shipme'); ?>" name="save_info" />

                                  </div>
                                </div>



                  </div>
              </div></form>
  <?php

  $page = ob_get_contents();
   ob_end_clean();

   return $page;


}


   ?>
