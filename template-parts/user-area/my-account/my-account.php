<?php


function shipme_theme_my_account_home_new()
{

  ob_start();

  $user = wp_get_current_user();
  $name = $user->user_login;

  $credits = shipme_get_credits($user->ID);
  $uid = get_current_user_id();

?>


  <div class="ship-pageheader">
            <ol class="breadcrumb ship-breadcrumb">
              <li class="breadcrumb-item"><a href="<?php echo get_site_url() ?>"><?php echo __('Home','shipme') ?></a></li>
              <li class="breadcrumb-item active" aria-current="page"><?php echo __('My Account','shipme') ?></li>
            </ol>
            <h6 class="ship-pagetitle"><?php printf(__("Welcome back, %s","shipme"), $name ) ?></h6>
          </div>

          <?php echo shp_account_main_menu(); ?>

          <div class="row row-xs">
            <?php

if(function_exists('shipme_membership_template_redirect'))
{

  if(shipme_is_customer($uid) == true)
  {  

        $customer_shipme_enable_membership = get_option('customer_shipme_enable_membership');
        if($customer_shipme_enable_membership == "yes")
        {
            $show_error_am = 1;  
        }

  }


  if(shipme_is_transporter($uid) == true)
  { 

        $transporter_shipme_enable_membership = get_option('transporter_shipme_enable_membership');
        if($transporter_shipme_enable_membership == "yes")
        {

              $show_error_am = 1;  

        }


  }
  
  


  $check_membership_available = get_user_meta($uid, 'check_membership_available', true);
  $check_membership_type = get_user_meta($uid, 'check_membership_type', true);

  $tm = time();


  if( $show_error_am == 1)
  {
  if($tm > $check_membership_available)
  {
      ?>

<div class="col-sm-12">
<div class="alert alert-danger"><?php _e('Your membership has expired','shipme') ?></div>
</div>

<?php
  }
  else
  {
  


    ?>


<div class="col-sm-12">
<div class="alert alert-success"><?php printf(__('Your membership is valid until %s','shipme'), date_i18n("d-M-Y",$check_membership_available ) ) ?></div>
</div>


<?php

}  } }

?>



          <div class="col-sm-6 col-lg-3">
            <div class="card card-status">
              <div class="media">

                <div class="media-body">
                  <h1 class="font-color-green"><?php echo shipme_get_show_price($credits) ?></h1>
                  <p><?php _e('Your balance','shipme'); ?></p>
                </div><!-- media-body -->
              </div><!-- media -->
            </div><!-- card -->
          </div><!-- col-3 -->
          <div class="col-sm-6 col-lg-3 mg-t-10 mg-sm-t-0">
            <div class="card card-status">
              <div class="media">

                <div class="media-body">
                  <h1><?php echo shipme_total_live_jobs_of_user($uid) ?></h1>
                  <p><?php _e('Total live jobs','shipme'); ?></p>
                </div><!-- media-body -->
              </div><!-- media -->
            </div><!-- card -->
          </div><!-- col-3 -->
          <div class="col-sm-6 col-lg-3 mg-t-10 mg-lg-t-0">
            <div class="card card-status">
              <div class="media">

                <div class="media-body">
                  <h1><?php echo shipme_get_unread_number_messages($uid) ?></h1>
                  <p><?php _e('Unread messages','shipme'); ?></p>
                </div><!-- media-body -->
              </div><!-- media -->
            </div><!-- card -->
          </div><!-- col-3 -->
          <div class="col-sm-6 col-lg-3 mg-t-10 mg-lg-t-0">
            <div class="card card-status">
              <div class="media">

                <div class="media-body">
                  <h1><?php echo shipme_get_show_price(shipme_due_unpaid_invoices($uid)) ?></h1>
                  <p><?php _e('Unpaid invoices','shipme'); ?></p>
                </div><!-- media-body -->
              </div><!-- media -->
            </div><!-- card -->
          </div><!-- col-3 -->
        </div>


          <div class="row row-sm mt-4">

 

                <div class="col-lg-4">



                    <?php shipme_account_side_profile_account(get_current_user_id()) ?>


                </div>

                <div class="col-lg-8 mg-t-20 mg-lg-t-0">


                  <?php if(shipme_is_customer(get_current_user_id())) { ?>
                  <div class="card card-table mb-4">
                      <div class="card-header">
                        <h6 class="ship-card-title"><?php _e('Latest posted jobs','shipme'); ?></h6>
                      </div><!-- card-header -->
                      <div class="table-responsive">

                        <?php
                                global $wp_query;
                                $query_vars = $wp_query->query_vars;
                                $post_per_page = 3;

                                $arr = array('post_status' => array('publish','draft'),
                              'meta_key' => 'closed',
                              'meta_value' => '0',
                              'post_type' => 'job_ship',
                              'order' => 'desc',
                              'posts_per_page' =>  $post_per_page,
                              'author' => $uid,
                              'orderby' => 'id' );

                                query_posts($arr);

                                if(have_posts()) : ?>

                                <table class="table mg-b-0 tx-13">
                                  <thead>
                                    <tr class="tx-10">
                                      <th class="wd-10p pd-y-5">&nbsp;</th>
                                      <th class="pd-y-5"><?php _e('Job Name','shipme'); ?></th>
                                      <th class="pd-y-5 tx-right"><?php _e('Quotes','shipme'); ?></th>
                                      <th class="pd-y-5"><?php _e('Price','shipme'); ?></th>
                                      <th class="pd-y-5 tx-center"><?php _e('Options','shipme'); ?></th>
                                    </tr>
                                  </thead>
                                  <tbody>

                                <?php
                                while ( have_posts() ) : the_post(); global $post;
                                ?>



                            <tr>
                              <td class="pd-l-20">
                                <img src="<?php echo shipme_get_first_post_image(get_the_ID(),55,55) ?>" width="55" height="45" class="wd-55" alt="<?php the_title() ?>" />
                              </td>
                              <td>
                                <a href="<?php the_permalink() ?>" class="tx-inverse tx-14 tx-medium d-block"><?php the_title() ?></a>
                                <?php
                                          if($post->post_status == "publish")
                                          {
                                 ?>
                                <span class="tx-11 d-block"><span class="square-8 bg-success mg-r-5 rounded-circle"></span> <?php _e('The job is active','shipme'); ?></span>
                              <?php } else { ?>

                                <span class="tx-11 d-block"><span class="square-8 bg-warning mg-r-5 rounded-circle"></span> <?php _e('The is awaiting moderation','shipme'); ?></span>

                              <?php } ?>
                              </td>
                              <td class="valign-middle tx-right"><?php echo shipme_number_of_bid(get_the_ID()) ?></td>
                              <td class="valign-middle"><span class="tx-success"><?php echo shipme_get_show_price(get_post_meta(get_the_ID(),'price',true), 0); ?></span></td>
                              <td class="valign-middle tx-center">
                                <!--<a href="" class="tx-gray-600 tx-24"><i class="icon ion-android-more-horizontal"></i></a> -->
                                <a href="<?php the_permalink() ?>" class="btn btn-primary btn-sm"><?php _e('View Job','shipme') ?></a>
                              </td>
                            </tr>


                              <?php

                                    endwhile;

                                    ?>

                                  </tbody>
                                </table>


                                    <?php

                                    else:

                                      echo '<div class="p-3">';
                                    _e("There are no active jobs yet.",'shipme');
                                    echo '</div>';

                                    endif;

                                    wp_reset_query();

                            ?>






                      </div><!-- table-responsive -->
                      <div class="card-footer tx-12 pd-y-15 bg-transparent">
                        <a href="<?php echo get_permalink(get_option('shipme_my_jobs_page')) ?>"><i class="fa fa-angle-down mg-r-5"></i><?php _e('View all jobs','shipme'); ?></a>
                      </div><!-- card-footer -->
                    </div><?php } ?>




                    <?php if(shipme_is_transporter(get_current_user_id())) { ?>
                    <div class="card card-table mb-4">
                        <div class="card-header">
                          <h6 class="ship-card-title"><?php _e('My Latest Posted Quotes','shipme'); ?></h6>
                        </div><!-- card-header -->
                        <div class="table-responsive">


                          <?php

                        global $wp_query;
                        $query_vars = $wp_query->query_vars;
                        $post_per_page = 3;

                        $bidu = 	array(
                        'key' => 'bid',
                        'value' => $uid,
                        'compare' => '='
                        );



                        $args = array('post_type' => 'job_ship', 'order' => 'DESC', 'orderby' => 'date', 'posts_per_page' => $post_per_page,
                        'pages' => $query_vars['paged'], 'meta_query' => array($bidu));


                        query_posts( $args);


                        if(have_posts()) :
                          ?>


                          <table class="table mg-b-0 tx-13">
                            <thead>
                              <tr class="tx-10">
                                <th class="wd-10p pd-y-5">&nbsp;</th>
                                <th class="pd-y-5"><?php _e('Job Name','shipme'); ?></th>
                                <th class="pd-y-5 tx-right"><?php _e('Your Quote','shipme'); ?></th>
                                <th class="pd-y-5"><?php _e('Posted On','shipme'); ?></th>
                                <th class="pd-y-5 tx-center"><?php _e('Actions','shipme'); ?></th>
                              </tr>
                            </thead>
                            <tbody>


                          <?php
                        while ( have_posts() ) : the_post();


                            $bid    = shipme_get_bid_by_uid(get_the_ID(), get_current_user_id());
                            if( $bid != false) $bid_am = shipme_get_show_price($bid->bid);

                            $closed = get_post_meta(get_the_ID(),'closed',true);

                        ?>

                        <tr>
                          <td class="pd-l-20">
                            <img src="<?php echo shipme_get_first_post_image(get_the_ID(),55,55) ?>" width="55" height="45" class="wd-55" alt="<?php the_title() ?>" />
                          </td>
                          <td>
                            <a href="<?php the_permalink() ?>" class="tx-inverse tx-14 tx-medium d-block"><?php the_title() ?></a>
                            <span class="tx-11 d-block"><?php echo shipme_get_status_of_job_for_freelancer (get_the_ID()); ?></span>
                          </td>
                          <td class="valign-middle tx-right"><?php echo $bid_am ?></td>
                          <td class="valign-middle"><?php echo date_i18n('d-M-Y', $bid->datemade); ?></td>
                          <td class="valign-middle tx-center">

                            <a href="" class="tx-gray-600 tx-24 dropdown-toggle" role="button" id="dropdownMenuLink<?php echo $bid->id ?>" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false"><i class="icon ion-android-more-horizontal"></i></a>

                            <div class="dropdown-menu" aria-labelledby="dropdownMenuButton<?php echo $bid->id ?>">
                              <a class="dropdown-item" href="<?php the_permalink() ?>"><?php _e('View Job Page','shipme') ?></a>
                              <?php if($closed == "0") { ?>
                              <a class="dropdown-item" href="<?php the_permalink() ?>"><?php _e('Retract Bid','shipme') ?></a>
                            <?php } ?>
                            </div>

                          </td>
                        </tr>

                        <?php
                        endwhile;
                        ?>

                                                   </tbody>
                                                 </table>


                        <?php

                        else:

                          echo '<div class="p-3">';
                        _e("You have not submitted any proposals yet.",'shipme');
                        echo '</div>';

                        endif;

                        wp_reset_query();

                        ?>




                        </div><!-- table-responsive -->
                        <div class="card-footer tx-12 pd-y-15 bg-transparent">
                          <a href="<?php echo get_permalink(get_option('shipme_my_quotes_page')) ?>"><i class="fa fa-angle-down mg-r-5"></i><?php _e('View all quotes','shipme'); ?></a>
                        </div><!-- card-footer -->
                      </div><?php } ?>



                </div>

          </div>


  <?php


  $page = ob_get_contents();
   ob_end_clean();

   return $page;


}

 ?>
