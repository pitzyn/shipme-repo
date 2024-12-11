<?php

	global $wpdb,$wp_rewrite,$wp_query;
	$username =  ($wp_query->query_vars['driver_author']);
	$uid = $username;
	$paged = $wp_query->query_vars['paged'];



	$user = get_userdata($uid);
	if($user == false)
	{



		$user = get_user_by('login', $username);
		$uid = $user->ID;


	}

	$username = $user->user_login;

	function sitemile_filter_ttl($title){return __("User Profile",'shipme')." - ";}
	add_filter( 'wp_title', 'sitemile_filter_ttl', 10, 3 );

get_header();
?>

 <div class="ship-mainpanel">



<!-- ########## -->
<div  class="container">


	<div class="ship-pageheader">
	            <ol class="breadcrumb ship-breadcrumb">

	            </ol>
	            <h6 class="ship-pagetitle"> <?php printf(__("User Profile - %s", 'shipme'), $username); ?></h6>
	          </div>


						<div class="row row-sm mt-4">

									<div class="col-lg-4">



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
									          <div class="mr-auto p-2 bd-highlight"><?php printf(__('%s Jobs in progress','shipme'), '<i class="fas fa-box-open"></i>'); ?></div>
									          <div class="p-2 bd-highlight"><?php echo shipme_get_nr_of_active($uid) ?></div>
									        </div>


									        <div class="d-flex row-user-crb">
									          <div class="mr-auto p-2 bd-highlight"><?php printf(__('%s Live quotes','shipme'), '<i class="fas fa-business-time"></i>'); ?></div>
									          <div class="p-2 bd-highlight"><?php echo shipme_show_nr_of_jobs_in_progress($uid) ?></div>
									        </div>



									          </div>


									    </div><!-- card-body -->
									  </div>



									</div>

									<div class="col-lg-8 mg-t-20 mg-lg-t-0">

										<div class="card p-3">
													<?php echo __('This user does not have any jobs posted.','shipme') ?>
										</div>

									</div></div>





 </div>



</div>

<?php

	get_footer();

?>
