<?php


function shp_account_main_menu()
{
      $uid = get_current_user_id();
      $my_jobs = shipme_count_active_quotes($uid) + shipme_get_number_of_delivered_of_my_jobs($uid) + shipme_count_in_progress_jobs_owner($uid);

      if($my_jobs > 0)
      $my_jobs = '<span class="badge badge-danger">'.$my_jobs."</span>";
      else $my_jobs = '';


      $shipme_count_in_progress_jobs_freelancer = shipme_count_in_progress_jobs_freelancer($uid);

      if($shipme_count_in_progress_jobs_freelancer > 0)
      $myQuotes = '<span class="badge badge-danger">'.$shipme_count_in_progress_jobs_freelancer."</span>";
      else $myQuotes = '';



      //---------

      $shipme_count_reviews_i_have_to_award = shipme_count_reviews_i_have_to_award($uid);
      if($shipme_count_reviews_i_have_to_award > 0)
      {
        $shipme_count_reviews_i_have_to_award = '<span class="badge badge-danger">'.$shipme_count_reviews_i_have_to_award."</span>";
      }
      else $shipme_count_reviews_i_have_to_award = '';


  ?>

  <nav class="navbar navbar-expand-lg navbar-light bg-white bd rounded  mb-3">
    <a class="navbar-brand" href="#"><?php _e('Account Menu','shipme') ?></a>
    <button class="navbar-toggler collapsed" type="button" data-toggle="collapse" data-target="#navbarNav" aria-controls="navbarNav" aria-expanded="false" aria-label="Toggle navigation">
      <span class="navbar-toggler-icon"></span>
    </button>
    <div class="navbar-collapse collapse" id="navbarNav" style="">
      <ul class="navbar-nav">
        <li class="nav-item">
          <a class="nav-link" href="<?php echo get_permalink( get_option('shipme_account_page_id')); ?>"><?php _e('Dashboard','shipme'); ?></a>
        </li>

        <?php if(shipme_is_user_business(get_current_user_id())) { ?>
        <li class="nav-item">
            <a class="nav-link" href="<?php echo get_permalink( get_option('shipme_post_new_page_id')); ?>"><?php _e('Post New Job','shipme'); ?></a>
        </li>


        <li class="nav-item">
            <a class="nav-link" href="<?php echo get_permalink( get_option('shipme_my_jobs_page')); ?>"><?php printf(__('My Jobs %s','shipme'), $my_jobs); ?></a>
        </li>
      <?php } ?>

        <?php if(shipme_is_user_provider(get_current_user_id())) { ?>
        <li class="nav-item">
            <a class="nav-link" href="<?php echo get_permalink( get_option('shipme_my_quotes_page')); ?>"><?php printf(__('My Quotes %s','shipme'), $myQuotes); ?></a>
        </li>
      <?php } ?>

      <?php

            $me = new shipme_messages();
            $nr = $me->get_unread_messages_total(get_current_user_id());

            if($nr > 0)  $xm = '<span class="badge badge-danger">'.$nr.'</span>';

       ?>
        <li class="nav-item">
            <a class="nav-link" href="<?php echo get_permalink( get_option('shipme_private_messages_page_id')); ?>"><?php echo sprintf(__('Messaging %s','shipme'), $xm); ?></a>
        </li>
        <li class="nav-item">
            <a class="nav-link" href="<?php echo get_permalink( get_option('shipme_profile_settings_page_id')); ?>"><?php _e('Profile Settings','shipme'); ?></a>
        </li>


        <li class="nav-item">
            <a class="nav-link" href="<?php echo get_permalink( get_option('shipme_finances_page_id')); ?>"><?php _e('Finances','shipme'); ?></a>
        </li>


        <li class="nav-item">
            <a class="nav-link" href="<?php echo get_permalink( get_option('shipme_profile_feedback_page_id')); ?>"><?php printf(__('Reviews %s','shipme'), $shipme_count_reviews_i_have_to_award); ?></a>
        </li>
  <?php do_action('shipme_main_account_menu_links') ?>

        <li class="nav-item">
            <a class="nav-link" href="<?php echo wp_logout_url(); ?>"><?php _e('Log Out','shipme'); ?></a>
        </li>



      </ul>
    </div>
  </nav>


  <?php
}


function ship_mobile_menu()
{

  $logo = get_template_directory_uri() . "/images/logo_black.png";
  $shipme_logo_URL = get_option('shipme_logo_URL');
  $shipme_home_logo_height = get_option('shipme_home_logo_height');
  $height = 35;

  if(!empty($shipme_logo_URL)) $logo = $shipme_logo_URL;
  if(!empty($shipme_home_logo_height)) $height = $shipme_home_logo_height;


  $menu_name = 'primary-shipme-header';

  ?>

  <div class="shp-overlay"></div>
  <div class="shp-sidebar">
      <div class="shp-sidebar-wrapper">

        <!-- side menu logo start -->
        <div class="shp-sidebar-logo">
          <a href="#"><img src="<?php echo $logo ?>" width="122" height="27"></a>

          <div class="shp-sidebar-toggle-button">
            <i class="material-icons"></i>
          </div>
        </div>
        <!-- side menu logo end -->
        	<nav class="shp-sidebar-navi">

  <?php

  wp_nav_menu( array(
    'theme_location'    => $menu_name,
    'depth'             => 2,
    'container'         => 'div',
    'container_class'   => 'navbar-shipme-main2',
    'container_id'      => 'bs-example-navbar-collapse-12',
    'menu_class'        => 'nav2 shp-theme-main-navi2',
    'fallback_cb'       => 'WP_Bootstrap_Navwalker_mobile::fallback',
    'walker'            => new WP_Bootstrap_Navwalker_mobile(),
  ) );




  ?>


</nav>
    			<!-- sidebar menu end -->
    		</div>
    	</div>

  <?php
}

function shp_main_menu()
{


  $menu_name = 'primary-shipme-header';

  echo '<div class="ship-navbar"><div class="container">';

  wp_nav_menu( array(
    'theme_location'    => $menu_name,
    'depth'             => 2,
    'container'         => 'div',
    'container_class'   => 'navbar-shipme-main',
    'container_id'      => 'bs-example-navbar-collapse-1',
    'menu_class'        => 'nav shp-theme-main-navi',
    'fallback_cb'       => 'WP_Bootstrap_Navwalker::fallback',
    'walker'            => new WP_Bootstrap_Navwalker(),
  ) );

   echo '</div></div>';


   ship_mobile_menu();

  ?>





<!--
  <div class="ship-navbar">
        <div class="container">
          <ul class="nav">

            <li class="nav-item">
              <a class="nav-link" href="#">
                <span>Home</span>
              </a></li>


              <li class="nav-item">
                <a class="nav-link" href="#">
                  <span>Account Area</span>
                </a></li>


                <li class="nav-item with-sub">
                  <a class="nav-link" href="#">
                    <span>Dashboard</span>
                  </a>
                  <div class="sub-item">
                    <ul>
                      <li><a href="index.html">Dashboard a</a></li>
                      <li><a href="index2.html">Dashboard b</a></li>
                      <li><a href="index3.html">Dashboard c</a></li>
                      <li><a href="index4.html">Dashboard d</a></li>
                      <li><a href="index5.html">Dashboard e</a></li>
                    </ul>
                  </div>
                </li>



                <li class="nav-item with-sub">
                  <a class="nav-link" href="#">
                    <span>Categories</span>
                  </a>
                  <div class="sub-item">
                    <ul>
                      <li><a href="index.html">Dashboard a</a></li>
                      <li><a href="index2.html">Dashboard b</a></li>
                      <li><a href="index3.html">Dashboard c</a></li>
                      <li><a href="index4.html">Dashboard d</a></li>
                      <li><a href="index5.html">Dashboard e</a></li>
                    </ul>
                  </div>
                </li>



            <li class="nav-item">
              <a class="nav-link" href="page-messages.html">

                <span>Messages</span>
                <span class="square-8"></span>
              </a>
            </li>
            <li class="nav-item">
              <a class="nav-link" href="widgets.html">

                <span>Widgets</span>
              </a>
            </li>
          </ul>
        </div>
      </div> -->

  <?php

}

function shipme_user_top_menus()
{

  if(is_user_logged_in())
  {


  ?>
  <div class="ship-header-right">

<?php


/*

    <div class="dropdown dropdown-a">
      <a href="" class="header-notification" data-toggle="dropdown">
        <i class="icon ion-ios-bolt-outline"></i>
      </a>
      <div class="dropdown-menu">
        <div class="dropdown-menu-header">
          <h6 class="dropdown-menu-title">Activity Logs</h6>
          <div>
            <a href="">Filter List</a>
            <a href="">Settings</a>
          </div>
        </div><!-- dropdown-menu-header -->
        <div class="dropdown-activity-list">
          <div class="activity-label">Today, December 13, 2017</div>
          <div class="activity-item">
            <div class="row no-gutters">
              <div class="col-2 tx-right">10:15am</div>
              <div class="col-2 tx-center"><span class="square-10 bg-success"></span></div>
              <div class="col-8">Purchased christmas sale cloud storage</div>
            </div><!-- row -->
          </div><!-- activity-item -->
          <div class="activity-item">
            <div class="row no-gutters">
              <div class="col-2 tx-right">9:48am</div>
              <div class="col-2 tx-center"><span class="square-10 bg-danger"></span></div>
              <div class="col-8">Login failure</div>
            </div><!-- row -->
          </div><!-- activity-item -->
          <div class="activity-item">
            <div class="row no-gutters">
              <div class="col-2 tx-right">7:29am</div>
              <div class="col-2 tx-center"><span class="square-10 bg-warning"></span></div>
              <div class="col-8">(D:) Storage almost full</div>
            </div><!-- row -->
          </div><!-- activity-item -->
          <div class="activity-item">
            <div class="row no-gutters">
              <div class="col-2 tx-right">3:21am</div>
              <div class="col-2 tx-center"><span class="square-10 bg-success"></span></div>
              <div class="col-8">1 item sold <strong>Christmas bundle</strong></div>
            </div><!-- row -->
          </div><!-- activity-item -->
          <div class="activity-label">Yesterday, December 12, 2017</div>
          <div class="activity-item">
            <div class="row no-gutters">
              <div class="col-2 tx-right">6:57am</div>
              <div class="col-2 tx-center"><span class="square-10 bg-success"></span></div>
              <div class="col-8">Earn new badge <strong>Elite Author</strong></div>
            </div><!-- row -->
          </div><!-- activity-item -->
        </div><!-- dropdown-activity-list -->
        <div class="dropdown-list-footer">
          <a href="page-activity.html"><i class="fa fa-angle-down"></i> Show All Activities</a>
        </div>
      </div><!-- dropdown-menu-right -->
    </div><!-- dropdown -->

*/

?>

  <?php do_action('shipme_top_menu_notifications') ?>
  <?php

        $uid = get_current_user_id();

   ?>
  <div class="dropdown dropdown-c">
    <a href="#" class="logged-user" data-toggle="dropdown">
      <img src="<?php echo shipme_get_avatar($uid,50,50) ?>" height="45" alt="">
      <span><?php $user = wp_get_current_user(); echo $user->user_login; ?></span>
      <i class="fa fa-angle-down"></i>
    </a>
    <div class="dropdown-menu dropdown-menu-right">
      <nav class="nav">
        <?php if( current_user_can('edit_others_pages') ) {  ?>

              <a href="<?php echo get_site_url() ?>/wp-admin" class="nav-link"><?php echo __('WP-Admin','shipme') ?></a>

        <?php } ?>

        <a href="<?php echo get_permalink(get_option('shipme_account_page_id')) ?>" class="nav-link"><?php echo __('My Account','shipme') ?></a>
        <a href="<?php echo get_permalink(get_option('shipme_post_new_page_id')) ?>" class="nav-link"><?php echo __('Post New','shipme') ?></a>

        <a href="<?php echo wp_logout_url() ?>" class="nav-link"><?php _e('Log Out','shipme') ?></a>
      </nav>
    </div><!-- dropdown-menu -->
  </div><!-- dropdown -->


    <div class="dropdown dropdown-c">
      <a href="<?php echo wp_logout_url() ?>" class="logged-user" ><span><?php _e('Log Out','shipme') ?></span> </a></div>
  </div>

  <?php
  }
  else {
    ?>
  <div class="ship-header-right">
    <div class="dropdown dropdown-c">
      <a href="<?php echo get_permalink(get_option('shipme_login_page_id')) ?>" class="logged-user" >   <span><?php _e('Login','shipme') ?></span> </a></div>

      <div class="dropdown dropdown-c">
        <a href="<?php echo get_permalink(get_option('shipme_register_page_id')) ?>" class="logged-user" >   <span><?php _e('Register','shipme') ?></span> </a></div>

          </div>
    <?php
  }
}



function shp_logo_or_placeholder() {

      $logo = get_template_directory_uri() . "/images/logo_black.png";
      $shipme_logo_URL = get_option('shipme_logo_URL');
      $shipme_home_logo_height = get_option('shipme_home_logo_height');
      $height = 35;

      if(!empty($shipme_logo_URL)) $logo = $shipme_logo_URL;
      if(!empty($shipme_home_logo_height)) $height = $shipme_home_logo_height;

   ?>
<!--  <h1>slim.</h1> -->
<div id="logo"><a href="<?php echo get_site_url() ?>"><img src="<?php echo $logo ?>" style="height: <?php echo $height ?>px" height="<?php echo $height ?>" /></a></div>

<?php
}



function shp_logo_or_placeholder_inner() {

      $logo = get_template_directory_uri() . "/images/logo_black.png";
      $shipme_logo_URL = get_option('shipme_logo_URL2');
      $shipme_home_logo_height = get_option('shipme_inner_logo_height');
      $height = 35;

      if(!empty($shipme_logo_URL)) $logo = $shipme_logo_URL;
      if(!empty($shipme_home_logo_height)) $height = $shipme_home_logo_height;

   ?>
<!--  <h1>slim.</h1> -->
<div id="logo"><a href="<?php echo get_site_url() ?>"><img src="<?php echo $logo ?>" height="<?php echo $height ?>" /></a></div>

<?php
}


 ?>
