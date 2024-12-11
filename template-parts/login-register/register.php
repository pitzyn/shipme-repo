<?php


function shipme_register_page_fnc()
{


  global $errors_register;

  ob_start();


  if($_GET['regfinished'] == "1")
  {
          ?>
          <div class="signin-wrapper">

                <div class="signin-box">
                  <h2 class="signin-title-primary"><?php _e('Registration Complete','shipme'); ?></h2>

                      <p class="regoktext">


                            <?php

                            $lnk = get_permalink(get_option(  'shipme_login_page_id' ) );

                                echo sprintf(  __('Thanks for registering an account with us. You will receive an email shortly. Also you can start using the site and your account by <a href="%s">logging in here</a>.','shipme'), $lnk);

                             ?>

                      </p>


                </div></div>




          <?php
  }
  else {



  ?>




  <div class="signin-wrapper">

        <div class="signin-box">
          <h2 class="signin-title-primary"><?php _e('Register an account','shipme'); ?></h2>

          <?php if ( isset($errors_register) && isset($_POST['action']) ) : ?>
           <div class="error-shp">
          <ul>
          <?php

          $me = $errors_register->get_error_messages();

          foreach($me as $mm)
           echo "<li>".($mm)."</li>";


          ?>
          </ul>
          </div>
          <?php endif; ?>


          <form name="loginform" id="loginform" method="post" action="<?php echo get_permalink(get_option('shipme_register_page_id')) ?>">
            <input type="hidden" name="action" value="register" />


            <div class="form-group">
              <input type="text" class="form-control" name="user_login" value="<?php echo $_POST['user_login'] ?>" placeholder="<?php _e('Username','shipme') ?>">
            </div><!-- form-group -->

            <div class="form-group">
              <input type="text" class="form-control" name="user_email" value="<?php echo $_POST['user_email'] ?>"  placeholder="<?php _e('Email Address','shipme') ?>">
            </div><!-- form-group -->


            <div class="form-group ">
              <input type="password" class="form-control" name="password" placeholder="<?php _e('Password','shipme') ?>">
            </div><!-- form-group -->

            <div class="form-group">
              <input type="password" class="form-control" name="cpassword" placeholder="<?php _e('Confirm Password','shipme') ?>">
            </div><!-- form-group -->

              <?php do_action('register_form2'); ?>

              <div class="form-group">
                  <?php

                      $def_ch = $_POST['user_tp'];
                      if(empty($def_ch)) $def_ch = "transporter";


                 ?>
            <input type="radio" class="do_input" name="user_tp" id="user_tp"  value="transporter" <?php echo $def_ch == "transporter"  ? "checked='checked'"  : "" ?>  /> <?php _e('Transporter',$current_theme_locale_name); ?><br/>
                          <input type="radio" class="do_input" name="user_tp" id="user_tp" value="customer" <?php echo $def_ch == "customer"  ? "checked='checked'"  : "" ?>  /> <?php _e('Customer',$current_theme_locale_name); ?>

                          </div><!-- form-group -->

          <?php do_action('register_form'); ?>


          <div class=" mg-b-50"></div>

          <input type="submit" name="shipme_register" value="<?php _e('Register','shipme') ?>" class="btn btn-primary btn-block btn-signin" />




        </form>

          <p class="mg-b-0 bord-top-shp text-bottom-login"><?php printf(__('<a href="%s">Login here</a>. Or <a href="%s">Recover your password</a>',''), get_permalink(get_option('shipme_login_page_id')), get_permalink(get_option('shipme_login_page_id')) ); ?></p>

        </div><!-- signin-box -->

      </div>


  <?php

}

    $page = ob_get_contents();
     ob_end_clean();

     return $page;

}


 ?>
