<?php

global $pagenow;
if (   is_admin() && 'themes.php' == $pagenow && isset( $_GET['activated'] ) )
{

	global $wpdb;

	update_option('shipme_right_side_footer', '<a title="Carriers Marketplace" href="http://carriersmarketplace.com#">Carriers Marketplace</a>');
	update_option('shipme_left_side_footer', 'Copyright (c) '.date("Y").' - '.get_bloginfo('name'));

	update_option('shipme_currency',						'USD');
	update_option('shipme_currency_symbol',					'$');
	update_option('shipme_currency_position',				'front');
	update_option('shipme_decimal_sum_separator',".");
	update_option('shipme_thousands_sum_separator',",");





	shipme_insert_pages2('shipme_home_page_page_id', 					'HomePage', 		'This is the homepage, you will need to edit it from your wp-admin area, is best to install elementor pagebuilder (or any other preferred pagebuilder) as instructed in admin area.' );
	update_option('page_on_front', get_option('shipme_home_page_page_id'));
	update_option('show_on_front', 'page');

		shipme_insert_pages('shipme_post_new_pay_balance_page_id', 					'Pay job by eWallet Balance', 		'[shipme_post_new_pay_listing_cred]' );
	shipme_insert_pages('shipme_post_new_page_id', 					'Post New Transport Job', 		'[shipme_theme_post_new]' );
	shipme_insert_pages('shipme_transporters_page_id', 					'Search Transporters', 		'[shipme_theme_transporters]' );
	shipme_insert_pages('shipme_account_page_id', 					'My Account Area', 				'[shipme_theme_my_account_home_new]' );
	shipme_insert_pages('shipme_finances_page_id', 					'Finances', 					'[shipme_theme_my_account_finances_new]', get_option('shipme_account_page_id') );
	shipme_insert_pages('shipme_private_messages_page_id', 					'Private Messages', 					'[shipme_theme_my_account_pm_new]' , get_option('shipme_account_page_id'));
	shipme_insert_pages('shipme_profile_settings_page_id', 					'Profile Settings', 					'[shipme_theme_my_account_profile_settings_new]', get_option('shipme_account_page_id') );
	shipme_insert_pages('shipme_profile_feedback_page_id', 					'Reviews/Feedback', 					'[shipme_theme_my_account_reviews_new]', get_option('shipme_account_page_id') );
	shipme_insert_pages('shipme_order_page_id', 					'Order Page', 					'[shipme_theme_my_account_order_page]', get_option('shipme_account_page_id') );

	update_option('shipme_button_link',						get_permalink(get_option('shipme_post_new_page_id')));
	//--------------------------------------------------------

	shipme_insert_pages('shipme_my_jobs_page', 					'My Jobs', 					'[shipme_theme_my_account_my_jobs]', get_option('shipme_account_page_id') );
	shipme_insert_pages('shipme_my_quotes_page', 					'My Quotes', 					'[shipme_theme_my_account_my_quotes]', get_option('shipme_account_page_id') );



	shipme_insert_pages('shipme_pay_commission_id', 					'Pay My Commission', 					'[shipme_theme_my_account_pay_comm_new]', get_option('shipme_account_page_id') );


		shipme_insert_pages('shipme_login_page_id', 					'Login Page', 		'[shipme_login_page]' );
			shipme_insert_pages('shipme_register_page_id', 					'Register Page', 		'[shipme_register_page]' );
				shipme_insert_pages('shipme_adv_search_page_id', 					'Advanced Search', 		'[shipme_adv_search]' );

	//----------------------


	update_option('shipme_homepage_big_text_text',						'Find your shipping carriers and get your goods delivered');
	update_option('shipme_homepage_sub_text_text',						'Customers post their transport or shipping needs and transporters post proposals to get the job. Then customer choses winner and transport is delivered. Start your shipping business today.');

	update_option('shipme_home_btn_left_caption',						'Post a new job');
	update_option('shipme_home_btn_right_caption',						'Become a carrier');
	update_option('shipme_home_btn_left_link',						get_permalink(get_option('shipme_post_new_page_id')));
	update_option('shipme_home_btn_right_link',						get_permalink(get_option('shipme_register_page_id')));

	//----------------------

	//main menu creation

	$menu_name = 'Main Header Menu';
	$menu_exists = wp_get_nav_menu_object( $menu_name );

	// If it doesn't exist, let's create it.
	if( !$menu_exists){
		$menu_id = wp_create_nav_menu($menu_name);

		// Set up default menu items
		wp_update_nav_menu_item($menu_id, 0, array(
			'menu-item-title' =>  __('Home'),
			'menu-item-classes' => 'home',
			'menu-item-url' => home_url( '/' ),
			'menu-item-status' => 'publish'));

		wp_update_nav_menu_item($menu_id, 0, array(
			'menu-item-title' =>  __('All Jobs'),
			'menu-item-url' => home_url( '?post_type=job_ship' ),
			'menu-item-status' => 'publish'));


				wp_update_nav_menu_item($menu_id, 0, array(
			'menu-item-title' =>  __('Transporters'),
			'menu-item-url' => get_permalink(get_option('shipme_transporters_page_id')),
			'menu-item-status' => 'publish'));


		$locations = get_theme_mod('nav_menu_locations');
		$locations['primary-shipme-header'] = $menu_id;
		set_theme_mod('nav_menu_locations', $locations);

	}




	//----------------------

		$ss = " CREATE TABLE `".$wpdb->prefix."ship_custom_fields` (
					`id` BIGINT NOT NULL AUTO_INCREMENT PRIMARY KEY ,
					`name` VARCHAR( 255 ) NOT NULL ,
					`tp` VARCHAR( 48 ) NOT NULL ,
					`ordr` INT NOT NULL ,
					`cate` VARCHAR( 255 ) NOT NULL ,
					`pause` INT NOT NULL DEFAULT '1'
					) ENGINE = MYISAM ";
			 $wpdb->query($ss);


			 $ss = "ALTER TABLE `".$wpdb->prefix."ship_custom_fields` ADD  `step_me` VARCHAR( 255 ) NOT NULL;";
		$wpdb->query($ss);

		$ss = "ALTER TABLE `".$wpdb->prefix."ship_custom_fields` ADD  `content_box6` TEXT NOT NULL ;";
		$wpdb->query($ss);

		$ss = "ALTER TABLE `".$wpdb->prefix."ship_custom_fields` ADD  `is_mandatory` TINYINT NOT NULL DEFAULT '0';";
			$wpdb->query($ss);


		$ss = " CREATE TABLE `".$wpdb->prefix."ship_custom_relations` (
					`id` BIGINT NOT NULL AUTO_INCREMENT PRIMARY KEY ,
					`custid` BIGINT NOT NULL ,
					`catid` BIGINT NOT NULL
					) ENGINE = MYISAM ";
			$wpdb->query($ss);

	//--------------------------------------------------------



			$ss = "CREATE TABLE `".$wpdb->prefix."ship_pm` (
					`id` BIGINT NOT NULL AUTO_INCREMENT PRIMARY KEY ,
					`owner` INT NOT NULL DEFAULT '0',
					`user` INT NOT NULL DEFAULT '0',
					`content` TEXT NOT NULL ,
					`subject` TEXT NOT NULL ,
					`rd` TINYINT NOT NULL DEFAULT '0',
					`parent` BIGINT NOT NULL DEFAULT '0',
					`pid` INT NOT NULL DEFAULT '0' ,
					`datemade` INT NOT NULL DEFAULT '0',
					`readdate` INT NOT NULL DEFAULT '0',
					`initiator` INT NOT NULL DEFAULT '0',
					`attached` INT NOT NULL DEFAULT '0'
					) ENGINE = MYISAM ;
					";
			$wpdb->query($ss);

			$s = "ALTER TABLE `".$wpdb->prefix."ship_pm` CHANGE `content` `content` TEXT CHARACTER SET utf8 COLLATE utf8_bin NOT NULL ,
												  CHANGE `subject` `subject` TEXT CHARACTER SET utf8 COLLATE utf8_bin NOT NULL ";
				$wpdb->query($s);

				$ss = "ALTER TABLE `".$wpdb->prefix."ship_pm` ADD  `show_to_source` TINYINT NOT NULL DEFAULT '1';";
			$wpdb->query($ss);

			//---------------------------

			$ss = "ALTER TABLE `".$wpdb->prefix."ship_pm` ADD  `show_to_destination` TINYINT NOT NULL DEFAULT '1';";
			$wpdb->query($ss);


			$wpdb->query("ALTER TABLE `".$wpdb->prefix."ship_pm` ADD `file_attached` VARCHAR( 255 ) NOT NULL ;");

				$ss = "ALTER TABLE `".$wpdb->prefix."ship_pm` ADD  `approved` TINYINT NOT NULL DEFAULT '1';";
			$wpdb->query($ss);

			$ss = "ALTER TABLE `".$wpdb->prefix."ship_pm` ADD  `approved_on` BIGINT NOT NULL DEFAULT '0';";
			$wpdb->query($ss);

			//------------------------

				$ss = "CREATE TABLE `".$wpdb->prefix."ship_bids` (
			`id` BIGINT NOT NULL AUTO_INCREMENT PRIMARY KEY ,
			`date_made` BIGINT NOT NULL DEFAULT '0',
			`bid` DOUBLE NOT NULL DEFAULT '0',
			`pid` BIGINT NOT NULL DEFAULT '0',
			`uid` BIGINT NOT NULL DEFAULT '0',
			`winner` TINYINT NOT NULL DEFAULT '0',
			`paid` TINYINT NOT NULL DEFAULT '0',
			`reserved1` VARCHAR( 255 ) NOT NULL DEFAULT '0',
			`date_choosen` BIGINT NOT NULL DEFAULT '0'
			) ENGINE = MYISAM ";

			$wpdb->query($ss);

				$sql_option_my = "ALTER TABLE  `".$wpdb->prefix."ship_bids` ADD  `description` TEXT NOT NULL ;";
				$wpdb->query($sql_option_my);

				$sql_option_my = "ALTER TABLE  `".$wpdb->prefix."ship_bids` ADD  `days_done` VARCHAR(255) NOT NULL ;";
				$wpdb->query($sql_option_my);

				$s = "ALTER TABLE `".$wpdb->prefix."ship_bids` CHANGE `description` `description` TEXT CHARACTER SET utf8 COLLATE utf8_bin NOT NULL ";
				$wpdb->query($s);



				$ss = " CREATE TABLE `".$wpdb->prefix."ship_ratings` (
					`id` INT NOT NULL AUTO_INCREMENT PRIMARY KEY ,
					`pid` BIGINT NOT NULL DEFAULT '0',
					`fromuser` BIGINT NOT NULL DEFAULT '0',
					`touser` BIGINT NOT NULL DEFAULT '0',
					`comment` TEXT NOT NULL ,
					`grade` TINYINT NOT NULL DEFAULT '0',
					`datemade` BIGINT NOT NULL DEFAULT '0',
					`awarded` TINYINT NOT NULL DEFAULT '0'
					) ENGINE = MYISAM ";

			$wpdb->query($ss);

				$s = "ALTER TABLE `".$wpdb->prefix."ship_ratings` CHANGE `comment` `comment` TEXT CHARACTER SET utf8 COLLATE utf8_bin NOT NULL ";
				$wpdb->query($s);



						$ss = " CREATE TABLE `".$wpdb->prefix."ship_custom_options` (
							 `id` BIGINT NOT NULL AUTO_INCREMENT PRIMARY KEY ,
							 `valval` VARCHAR( 255 ) NOT NULL ,
							 `ordr` INT( 11 ) NOT NULL ,
							 `custid` INT NOT NULL
							 ) ENGINE = MYISAM ";
						$wpdb->query($ss);

}


$opt = get_option('shpime_upd221a_baat5');


if(empty($opt) or isset($_GET['sitemile_reset_theme']))
{
	global $wpdb;
	update_option('shpime_upd221a_baat5','y');

		//********************************************
		//
		//
		//
		//********************************************

		 $ss = " CREATE TABLE `".$wpdb->prefix."ship_withdraw` (
					`id` BIGINT NOT NULL AUTO_INCREMENT PRIMARY KEY ,
					`datemade` INT NOT NULL ,
					`done` INT NOT NULL ,
					`datedone` INT NOT NULL ,
					`payeremail` VARCHAR( 255 ) NOT NULL ,
					`uid` INT NOT NULL ,
					`amount` DOUBLE NOT NULL
					) ENGINE = MYISAM ";
		$wpdb->query($ss);

		$wpdb->query("ALTER TABLE `".$wpdb->prefix."ship_withdraw` ADD `methods` VARCHAR( 255 ) NOT NULL ;");

		$ss = "ALTER TABLE `".$wpdb->prefix."ship_withdraw` CHANGE  `methods`  `methods` TEXT CHARACTER SET utf8 COLLATE utf8_bin NOT NULL";
		$wpdb->query($ss);

		$sql_option_my = "ALTER TABLE  `".$wpdb->prefix."ship_withdraw` ADD  `rejected` VARCHAR(255) NOT NULL DEFAULT '0';";
		$wpdb->query($sql_option_my);



				//********************************************
				//
				//
				//
				//********************************************


		$ss = " CREATE TABLE `".$wpdb->prefix."ship_payment_transactions` (
				 `id` BIGINT NOT NULL AUTO_INCREMENT PRIMARY KEY ,
				 `uid` INT NOT NULL ,
				 `reason` TEXT NOT NULL ,
				 `datemade` INT NOT NULL ,
				 `amount` DOUBLE NOT NULL ,
				 `tp` TINYINT NOT NULL DEFAULT '1',
				 `uid2` INT NOT NULL DEFAULT '0'
				 ) ENGINE = MYISAM ";
	 $wpdb->query($ss);



	 $ss = "CREATE TABLE `".$wpdb->prefix."ship_email_alerts` (
			 `id` BIGINT NOT NULL AUTO_INCREMENT PRIMARY KEY ,
			 `uid` INT NOT NULL ,
			 `catid` INT NOT NULL
			 ) ENGINE = MYISAM ;
			 ";
	 $wpdb->query($ss);

	 //-------- new v.2.2 -----------

	 $ss = " CREATE TABLE `".$wpdb->prefix."ship_escrows` (
			 `id` BIGINT NOT NULL AUTO_INCREMENT PRIMARY KEY ,
			 `fromid` BIGINT NOT NULL ,
			 `toid` BIGINT NOT NULL ,
			 `oid` BIGINT NOT NULL ,
			 `amount` DOUBLE NOT NULL ,
			 `datemade` BIGINT NOT NULL ,
			 `releasedate` BIGINT NOT NULL ,
			 `released` TINYINT NOT NULL DEFAULT '0'
			 ) ENGINE = MYISAM ";
		$wpdb->query($ss);

		$wpdb->query("ALTER TABLE `".$wpdb->prefix."ship_escrows` ADD `method` VARCHAR( 255 ) NOT NULL ;");

	 //----

	 $ss = "CREATE TABLE `".$wpdb->prefix."ship_orders` (
			 `id` BIGINT NOT NULL AUTO_INCREMENT PRIMARY KEY ,
			 `buyer` BIGINT NOT NULL DEFAULT '0',
			 `transporter` BIGINT NOT NULL DEFAULT '0',
			 `pid` BIGINT NOT NULL DEFAULT '0',
			 `datemade` BIGINT NOT NULL DEFAULT '0',

			 `done_transporter` TINYINT NOT NULL DEFAULT '0',
			 `done_buyer` TINYINT NOT NULL DEFAULT '0',
			 `order_status` TINYINT NOT NULL DEFAULT '0',

			 `marked_done_transporter` BIGINT NOT NULL DEFAULT '0',
			 `marked_done_buyer` BIGINT NOT NULL DEFAULT '0',

			 `order_net_amount` DOUBLE NOT NULL DEFAULT '0',
			 `order_total_amount` DOUBLE NOT NULL DEFAULT '0'
			 ) ENGINE = MYISAM ;
			 ";
	 $wpdb->query($ss);

	 $ss = "ALTER TABLE `".$wpdb->prefix."ship_orders` ADD  `completion_date` BIGINT NOT NULL DEFAULT '0';";
	 $wpdb->query($ss);



	 $ss = "CREATE TABLE `".$wpdb->prefix."ship_pm_threads` (
	 `id` BIGINT NOT NULL AUTO_INCREMENT PRIMARY KEY ,
	 `date_made` BIGINT NOT NULL DEFAULT '0',
 	`last_updated` BIGINT NOT NULL DEFAULT '0',
	 `pid` BIGINT NOT NULL DEFAULT '0',
	 `uid1` BIGINT NOT NULL DEFAULT '0',
	 `uid2` BIGINT NOT NULL DEFAULT '0'
	 ) ENGINE = MYISAM ";

	 $wpdb->query($ss);


	 $ss = "ALTER TABLE `".$wpdb->prefix."ship_pm_threads` ADD  `messages_number` BIGINT NOT NULL DEFAULT '0';";
	 $wpdb->query($ss);


	 global $wpdb;

	 $ss = " CREATE TABLE `".$wpdb->prefix."ship_bills_site` (
			 `id` BIGINT NOT NULL AUTO_INCREMENT PRIMARY KEY ,
			 `uid` BIGINT NOT NULL ,
			 `pid` BIGINT NOT NULL ,
			 `datemade` BIGINT NOT NULL ,
			 `amount` DOUBLE NOT NULL ,
			 `paiddate` BIGINT NOT NULL ,
			 `paid` TINYINT NOT NULL DEFAULT '0'
			 ) ENGINE = MYISAM ";
		$wpdb->query($ss);





		$ss = "CREATE TABLE `".$wpdb->prefix."ship_message_board` (
				`id` BIGINT NOT NULL AUTO_INCREMENT PRIMARY KEY ,
				`uid` INT NOT NULL ,
				`content` TEXT NOT NULL ,
				`rd` TINYINT NOT NULL DEFAULT '0',
				`pid` INT NOT NULL ,
				`datemade` INT NOT NULL
				) ENGINE = MYISAM ;
				";
		$wpdb->query($ss);

		$s = "ALTER TABLE `".$wpdb->prefix."ship_message_board` CHANGE `content` `content` TEXT CHARACTER SET utf8 COLLATE utf8_bin NOT NULL ";
		$wpdb->query($s);


		global $wpdb;
		$ss = "ALTER TABLE `".$wpdb->prefix."ship_message_board` ADD  `hash` VARCHAR (255) NOT NULL DEFAULT '0'; ";
		$wpdb->query($ss);




		$ss = "CREATE TABLE `".$wpdb->prefix."ship_message_board_answers` (
				`id` BIGINT NOT NULL AUTO_INCREMENT PRIMARY KEY ,
				`content` TEXT NOT NULL ,
				`pid` BIGINT NOT NULL ,
				`questionid` BIGINT NOT NULL ,
				`datemade` BIGINT NOT NULL
				) ENGINE = MYISAM ;
				";
		$wpdb->query($ss);
}







?>
