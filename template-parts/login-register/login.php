<?php


function shipme_login_page_fnc()
{

ob_start();

  global $wpdb;

if($_GET['action'] == 'recoverpass')
{

  ?>



  <div class="signin-wrapper">

        <div class="signin-box">
          <h2 class="signin-title-primary"><?php _e('Recover your password','shipme'); ?></h2>




          <p>
            <?php


            $key = preg_replace('/a-z0-9/i', '', $_GET['key']);
            if ( empty($key) )
            {

              _e('Sorry, that key does not appear to be valid.','shipme');


            }
            else {

                        $user = $wpdb->get_row("SELECT * FROM $wpdb->users WHERE user_activation_key = '$key'");
                        if ( !$user )
                        {

                          _e('Sorry, that key does not appear to be valid.','shipme');

                        }
                        else {

                        do_action('password_reset');

                        $new_pass = substr( md5( uniqid( current_time('timestamp',0) ) ), 0, 7);
                        $wpdb->query("UPDATE $wpdb->users SET user_pass = MD5('$new_pass'), user_activation_key = '' WHERE user_login = '$user->user_login'");
                        wp_cache_delete($user->ID, 'users');
                        wp_cache_delete($user->user_login, 'userlogins');
                        $message  = sprintf(__('Username: %s','shipme'), $user->user_login) . "\r\n";
                        $message .= sprintf(__('Password: %s','shipme'), $new_pass) . "\r\n";
                        $message .= get_settings('siteurl') . "/wp-login.php\r\n";

                        $m = wp_mail($user->user_email, sprintf(__('[%s] Your new password','shipme'), get_settings('blogname')), $message);

                        if ($m == false)
                        {

                          echo   __('The e-mail could not be sent.','shipme') ;
                          echo  __('Possible reason: your host may have disabled the mail() function...','shipme') ;
                        }
                        else
                        {

                          echo    sprintf(__('Your new password is in the mail.','shipme'), $user_login);
                        echo  " <a href='".get_permalink(get_option('shipme_login_page_id'))."' title='" . __('Check your e-mail first, of course','shipme') . "'> " .
                        __('Click here to login!','shipme') . '</a> ';

                          $message = sprintf(__('Password Lost and Changed for user: %s','shipme'), $user->user_login) . "\r\n";
                          wp_mail(get_settings('admin_email'), sprintf(__('[%s] Password Lost/Change','shipme'), get_settings('blogname')), $message);
                        }


                        }

            }
            ?>


          </p>





        <?php

        $shipme_login_url = get_permalink(get_option('shipme_login_page_id'));



         ?>

          <p class="mg-b-0 bord-top-shp text-bottom-login"><?php printf(__('<a href="%s">Register here</a>. Or <a href="%s">Login</a>',''),
          get_permalink(get_option('shipme_register_page_id')), $shipme_login_url ); ?></p>

        </div><!-- signin-box -->

      </div>




<?php


}
elseif($_GET['retrievefinished'] == 1)
{
  ?>


  <div class="signin-wrapper">

        <div class="signin-box">
          <h2 class="signin-title-primary"><?php _e('Recover your password','shipme'); ?></h2>




          <p><?php _e('Success! You will receive an email shortly, with instructions on how to reset your password.','shipme') ?></p>





        <?php

        $shipme_login_url = get_permalink(get_option('shipme_login_page_id'));



         ?>

          <p class="mg-b-0 bord-top-shp text-bottom-login"><?php printf(__('<a href="%s">Register here</a>. Or <a href="%s">Login</a>',''),
          get_permalink(get_option('shipme_register_page_id')), $shipme_login_url ); ?></p>

        </div><!-- signin-box -->

      </div>


<?php
}
    elseif($_GET['recoverpass'] == 1)
    {
        ?>

        <div class="signin-wrapper">

              <div class="signin-box">
                <h2 class="signin-title-primary"><?php _e('Recover your password','shipme'); ?></h2>


      <?php
                  global $errors;


                  $errors = apply_filters( 'wp_login_errors', $errors, $redirect_to );

                  if ( empty($errors) )
                  $errors = new WP_Error();

                ?>

                <?php if ( isset($errors) && isset($_POST['action']) and count($errors) > 0 ) : ?>
                 <div class="error-shp">
                <ul>
                <?php

                $me = $errors->get_error_messages();

                foreach($me as $mm)
                 echo "<li>".($mm)."</li>";


                ?>
                </ul>
                </div>
                <?php endif; ?>

                <?php


                $shipme_login_page_id = get_option('shipme_login_page_id');
                if(shipme_using_permalinks()) $link = get_permalink( $shipme_login_page_id ) . '?recoverpass=1';
                else $link = get_permalink( $shipme_login_page_id ) . '&recoverpass=1';


                 ?>

                <form name="loginform" id="loginform" method="post" action="<?php echo $link ?>">
                  <input type="hidden" value="1" name="shipme_recover_pass" />
                  <input type="hidden" value="recoverpassword" name="action" />
                <div class="form-group">
                  <input type="text" class="form-control" required name="user_login" placeholder="<?php _e('Enter your username or email address...','shipme') ?>">
                </div><!-- form-group -->



                <input type="submit" name="" value="<?php _e('Recover password','shipme') ?>" class="btn btn-primary btn-block btn-signin" />



              </form>

              <?php

              $shipme_login_url = get_permalink(get_option('shipme_login_page_id'));



               ?>

                <p class="mg-b-0 bord-top-shp text-bottom-login"><?php printf(__('<a href="%s">Register here</a>. Or <a href="%s">Login</a>',''),
                get_permalink(get_option('shipme_register_page_id')), $shipme_login_url ); ?></p>

              </div><!-- signin-box -->

            </div>

        <?php
    }
    else {


  ?>




  <div class="signin-wrapper">

        <div class="signin-box">
          <h2 class="signin-title-primary"><?php _e('Login to your account','shipme'); ?></h2>


<?php
            global $login_errors;


            $login_errors = apply_filters( 'wp_login_errors', $login_errors, $redirect_to );

            if ( empty($login_errors) )
            $login_errors = new WP_Error();

          ?>

          <?php   if ( isset($login_errors) && isset($_POST['action']) ) : ?>
           <div class="error-shp">
          <ul>
          <?php

          $me = $login_errors->get_error_messages();

          foreach($me as $mm)
           echo "<li>".($mm)."</li>";


          ?>
          </ul>
          </div>
          <?php endif; ?>



          <form name="loginform" id="loginform" method="post" action="<?php echo get_permalink(get_option('shipme_login_page_id')) ?>">
            <input type="hidden" value="1" name="shipme_login" />
            <input type="hidden" value="login" name="action" />
          <div class="form-group">
            <input type="text" class="form-control" name="log" placeholder="<?php _e('Enter your username...','shipme') ?>">
          </div><!-- form-group -->
          <div class="form-group mg-b-50">
            <input type="password" class="form-control" name="pwd" placeholder="<?php _e('Enter your password...','shipme') ?>">
          </div><!-- form-group -->


          <?php do_action('login_form'); ?>

            <div class="form-group mg-b-50"><input class="do_input" name="rememberme" type="checkbox" id="rememberme" value="true" tabindex="3" />
    				<?php _e('Keep me logged in','shipme'); ?></div>


          <input type="submit" name="" value="<?php _e('Sign In','shipme') ?>" class="btn btn-primary btn-block btn-signin" />

          <?php
                if(!empty($_GET['redirect_to'])){
           ?>
          <input type="hidden" name="redirect_to" value="<?php echo $_GET['redirect_to']; ?>" />
        <?php } ?>

        <input type="hidden" name="testcookie" value="1" />
        </form>

        <?php

        $shipme_login_page_id = get_option('shipme_login_page_id');
        if(shipme_using_permalinks()) $recover_link = get_permalink( $shipme_login_page_id ) . '?recoverpass=1';
        else $recover_link = get_permalink( $shipme_login_page_id ) . '&recoverpass=1';


         ?>

          <p class="mg-b-0 bord-top-shp text-bottom-login"><?php printf(__('<a href="%s">Register here</a>. Or <a href="%s">Recover your password</a>',''),
          get_permalink(get_option('shipme_register_page_id')), $recover_link ); ?></p>

        </div><!-- signin-box -->

      </div>


  <?php

}

  $page = ob_get_contents();
   ob_end_clean();

   return $page;

}


 ?>
