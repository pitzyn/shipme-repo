<?php

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



 ?>
