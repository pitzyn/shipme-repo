<?php
/**********************
*	MIT license
**********************/

if(!function_exists('shipme_sitemile_filter_ttl'))
{
function shipme_sitemile_filter_ttl($title){ global $real_ttl; return $real_ttl." - ".get_bloginfo('sitename');}
}

if(!function_exists('shipme_do_register_scr')) {
		function shipme_do_register_scr()
		{
		  global $wpdb, $wp_query ;
		
		  if (!is_array($wp_query->query_vars))
			$wp_query->query_vars = array();
		
		header('Content-Type: '.get_bloginfo('html_type').'; charset='.get_bloginfo('charset'));
		
	
		  switch( $_REQUEST["action"] ) 
		  {
			
			case 'register':
			  require_once( ABSPATH . WPINC . '/registration-functions.php');
			  
				$user_login = sanitize_user( str_replace(" ","",$_POST['user_login']) );
				$user_email = trim($_POST['user_email']);
			
				$sanitized_user_login = $user_login;
		
			
				
				$errors = shipme_register_new_user_sitemile($user_login, $user_email);
				
					if (!is_wp_error($errors)) 
					{	
						$ok_reg = 1;						
					}	
					
				
			  if ( 1 == $ok_reg ) 
			  {//continues after the break; 
				
				global $real_ttl;
				$real_ttl = __("Registration Complete", 'shipme');			
				add_filter( 'wp_title', 'shipme_sitemile_filter_ttl', 10, 3 );	
				
				get_header();
				 
				
		?>
				
				<div class="my_box3 breadcrumb-wrap">
            
            	<div class="box_title"><?php _e('Registration Complete','shipme') ?></div>
                <div class="padd10">
							<p><?php printf(__('Username: %s','shipme'), "<strong>" . esc_html($user_login) . "</strong>") ?><br />
							<?php printf(__('Password: %s','shipme'), '<strong>' . __('emailed to you','shipme') . '</strong>') ?> <br />
							<?php printf(__('E-mail: %s','shipme'), "<strong>" . esc_html($user_email) . "</strong>") ?><br /><br />
							<?php _e("Please check your <strong>Junk Mail</strong> if your account information does not appear within 5 minutes.",'shipme'); ?>
                            </p>

							<p class="submit"><a href="wp-login.php"><?php _e('Login', 'shipme'); ?> &raquo;</a></p>
						</div></div>
		<?php
								
				
				get_footer();
		
				die();
			break;
			  }//continued from the error check above
		
			default:
			
			global $real_ttl;
			$real_ttl = __("Register", 'shipme');			
			add_filter( 'wp_title', 'shipme_sitemile_filter_ttl', 10, 3 );	
			
			get_header();
			
		
				
				?>
                      <div class="container_ship_no_bk margin_top_40">
        
        	<ul class="virtual_sidebar">
			
			<li class="widget-container widget_text"><h3 class="widget-title"><?php _e("Register",'shipme'); ?> - <?php echo  get_bloginfo('name'); ?></h3>
			<div class="my-only-widget-content ">
                
                
	                                       
						  
						  <?php if ( isset($errors) && isset($_POST['action']) ) : ?>
						  <div class="bam_bam"> <div class="error">
							<ul>
							<?php 
							 
							$me = $errors->get_error_messages();
					 
						 	foreach($me as $mm)
							 echo "<li>".($mm)."</li>";
							
							
							 
							?>
							</ul>
						  </div> </div>
						  <?php endif; ?>
						  <div class="login-submit-form">
                          
                          
                          <form method="post" id="registerform" action="<?php echo esc_url( site_url('wp-login.php?action=register', 'login_post') ); ?>">
						  <input type="hidden" name="action" value="register" />	
							
							<p>
                            <label for="register-username"><?php _e('Username:','shipme') ?></label>
							<input type="text" class="do_input" name="user_login" id="user_login" size="30" maxlength="20" value="<?php echo esc_html($user_login); ?>" />
							</p>							
					
							<p>							 
							<label for="register-email"><?php _e('E-mail:','shipme') ?></label>
							<input type="text" class="do_input" name="user_email" id="user_email" size="30" maxlength="100" value="<?php echo esc_html($user_email); ?>" />
							</p>
							
							<p>							 
							<label for="register-email"><?php _e('Password:','shipme') ?></label>
							<input type="password" class="do_input" name="password" id="password" size="30" maxlength="100"  />
							</p>
							
							<p>							 
							<label for="register-email"><?php _e('Confirm Password:','shipme') ?></label>
							<input type="password" class="do_input" name="cpassword" id="password" size="30" maxlength="100"  />
							</p>
							
                            <?php
							
								$shipme_force_paypal_address = get_option('shipme_force_paypal_address');
								if($shipme_force_paypal_address == "yes"):
								
								$paypal_email = $_POST['paypal_email'];
							 
								
							?>
                            
                            <p>							 
							<label for="register-email"><?php _e('PayPal E-mail:','shipme') ?></label>
							<input type="text" class="do_input" name="paypal_email" id="paypal_email" size="30" maxlength="100" value="<?php echo esc_html($paypal_email); ?>" />
							</p>
                            
                            <?php endif; ?>
                            
                            <?php
							
								$shipme_force_shipping_address = get_option('shipme_force_shipping_address');
								if($shipme_force_shipping_address == "yes"):
								
							 
								$shipping_info = $_POST['shipping_info'];
								
							?>
                            
                            <p>							 
							<label for="register-email"><?php _e('Shipping address:','shipme') ?></label>
							<input type="text" class="do_input" name="shipping_info" id="shipping_info" size="50" maxlength="100" value="<?php echo esc_html($shipping_info); ?>" />
							</p>	
                            
                            <?php endif; ?>
                            
                            
                              <p>							 
							<label for="register-email"><?php _e('User Type:',$current_theme_locale_name) ?></label>
							<input type="radio" class="do_input" name="user_tp" id="user_tp" value="transporter" checked="checked" /> <?php _e('Transporter',$current_theme_locale_name); ?><br/>
                            <input type="radio" class="do_input" name="user_tp" id="user_tp" value="contractor" /> <?php _e('Contractor',$current_theme_locale_name); ?><br/>
							</p>
                            
                            
                        
							<?php do_action('register_form'); ?>

							<p><label for="submitbtn">&nbsp;</label>
							<?php _e('A password will be emailed to you.','shipme') ?></p>
							

                          
							
						<p class="submit">
                        <label for="submitbtn">&nbsp;</label>
							 <a href="#" onClick="document.getElementById('registerform').submit();" class="submit_bottom2"><i class="fa fa-user-plus"></i> <?php _e('Register','shipme') ?></a>
						</p>
                          
						
                          </form>
                          
                            <ul id="logins">
							<li><a class="green_btn" href="<?php bloginfo('home'); ?>/" title="<?php _e('Are you lost?','shipme') ?>"><?php _e('Home','shipme') ?></a></li>
							<li><a class="green_btn" href="<?php echo esc_url( site_url() ); ?>/wp-login.php"><?php _e('Login','shipme') ?></a></li>
							<li><a class="green_btn" href="<?php echo esc_url( site_url() ); ?>/wp-login.php?action=lostpassword" title="<?php _e('Password Lost?','shipme') ?>"><?php _e('Lost your password?','shipme') ?></a></li>
						  </ul>
                          
                          
						</div>
                        
                        
                        
                        
                        </div>
                        </div>
                        </li>
                        </ul>
                        </div> 
                        
                        
		<?php
				
				
	 			  get_footer();
		
			  die();
			break;
			case 'disabled':
     
	 				global $real_ttl;
			$real_ttl = __("Registration Disabled", 'shipme');			
			add_filter( 'wp_title', 'shipme_sitemile_filter_ttl', 10, 3 );	
			
	 
	 			  get_header();
				
			
		?>
        <div class="clear10"></div>	
				<div class="my_box3 breadcrumb-wrap">
            	<div class="padd10">

        <div class="box_title"><?php _e('Registration Disabled','shipme') ?></div>
                <div class="box_content">
                                                  
							
							<p><?php _e('User registration is currently not allowed.','shipme') ?><br />
							<a href="<?php echo get_settings('home'); ?>/" title="<?php _e('Go back to the blog','shipme') ?>"><?php _e('Home','shipme') ?></a>
							</p>
						</div></div></div>
		<?php
				
				 get_footer();
		
			  die();
			break;
		  }
		}

		}


//===================================================================
if(!function_exists('shipme_register_new_user_sitemile')) {
function shipme_register_new_user_sitemile( $user_login, $user_email ) {
	$errors = new WP_Error();
	 
	$sanitized_user_login = sanitize_user( $user_login );
	$user_email = apply_filters( 'user_registration_email', $user_email );
	
	$password = $_POST['password'];
	$cpassword = $_POST['cpassword'];

	// Check the username
	if ( $sanitized_user_login == '' ) {
		$errors->add( 'empty_username', __( '<strong>ERROR</strong>: Please enter a username.', 'shipme' ) );
	} elseif ( ! validate_username( $user_login ) ) {
		$errors->add( 'invalid_username', __( '<strong>ERROR</strong>: This username is invalid because it uses illegal characters. Please enter a valid username.', 'shipme' ) );
		$sanitized_user_login = '';
	} elseif ( username_exists( $sanitized_user_login ) ) {
		$errors->add( 'username_exists', __( '<strong>ERROR</strong>: This username is already registered, please choose another one.', 'shipme' ) );
	}

	// Check the e-mail address
	if ( $user_email == '' ) {
		$errors->add( 'empty_email', __( '<strong>ERROR</strong>: Please type your e-mail address.', 'shipme' ) );
	} elseif ( ! is_email( $user_email ) ) {
		$errors->add( 'invalid_email', __( '<strong>ERROR</strong>: The email address isn&#8217;t correct.', 'shipme' ) );
		$user_email = '';
	} elseif ( email_exists( $user_email ) ) {
		$errors->add( 'email_exists', __( '<strong>ERROR</strong>: This email is already registered, please choose another one.', 'shipme' ) );
	}
	
	
	$shipme_force_shipping_address = get_option('shipme_force_shipping_address');
	if($shipme_force_shipping_address == "yes"):
	
		$shipping_info = $_POST['shipping_info'];
		
		if ( empty($shipping_info) ) {
			$errors->add( 'shipping_info', __( '<strong>ERROR</strong>: Please type your shipping information/address.', 'shipme' ) );
		}
	
	endif;
	
	
	if (strlen( $password) < 5 ) {
		$errors->add( 'password', __( '<strong>ERROR</strong>: Your password needs to be longer than 5 characters.', 'shipme' ) );
	}
	else{
		if ( $password != $cpassword ) {
		$errors->add( 'password', __( '<strong>ERROR</strong>: Your password doesnt match the confirmation.', 'shipme' ) );
	}
	}
	
	//----------------------------------------
	
	$shipme_force_paypal_address = get_option('shipme_force_paypal_address');
	if($shipme_force_paypal_address == "yes"):
	
		$paypal_email = $_POST['paypal_email'];
		
		if ( empty($paypal_email) ) {
			$errors->add( 'paypal_email', __( '<strong>ERROR</strong>: Please type your PayPal email address.', 'shipme' ) );
		}
	
	endif;
	
	do_action( 'register_post', $sanitized_user_login, $user_email, $errors );

	$errors = apply_filters( 'registration_errors', $errors, $sanitized_user_login, $user_email );

	if ( $errors->get_error_code() )
		return $errors;

	$user_pass = $password; //wp_generate_password( 12, false);
	$user_id = wp_create_user( $sanitized_user_login, $user_pass, $user_email );
	if ( ! $user_id ) {
		$errors->add( 'registerfail', sprintf( __( '<strong>ERROR</strong>: Couldn&#8217;t register you... please contact the <a href="mailto:%s">webmaster</a> !', 'shipme' ), get_option( 'admin_email' ) ) );
		return $errors;
	}

	update_user_option( $user_id, 'default_password_nag', true, true ); //Set up the Password change nag.
	
	update_user_meta($user_id, 'shipping_info', $shipping_info);
	update_user_meta($user_id, 'paypal_email', $paypal_email);
	
	shipme_new_user_notification($user_id, $user_pass );
	shipme_new_user_notification_admin($user_id);
	
	
	wp_update_user( array( 'ID' => $user_id, 'role' => $_POST['user_tp'] ) );
	
	return $user_id;
}
}

?>