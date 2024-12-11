<?php



function shipme_posts_where2( $where ) {

    global $wpdb, $term;
    $term = trim($term);
    $term1 = explode(" ",$term);
    $xl = '';

    foreach($term1 as $tt)
    {
      $xl .= " AND ({$wpdb->posts}.post_title LIKE '%$tt%' OR {$wpdb->posts}.post_content LIKE '%$tt%')";

    }

    $where .= " AND (1=1 $xl )";

  return $where;
}




function _function_name_2823( $fields, $query ){

  global $local_lat, $local_long;

  $local_lat = $local_lat + 0.0000001;
  $local_long = $local_long + 0.0000001;


  $sql_distance = " ,(((acos(sin((".$local_lat."*pi()/180)) * sin(( (`pickup_lat`+0)*pi()/180))+cos((".$local_lat."*pi()/180)) * cos(( (`pickup_lat`+0)*pi()/180)) * cos(((".$local_long." - (`pickup_lng`+0))*pi()/180))))*180/pi())*60*1.1515*1.609344) as distance ";
  $sql_distance = ", ROUND(DEGREES(ACOS(SIN(RADIANS($local_lat))
* SIN(RADIANS(pickup_lat))
+ COS(RADIANS($local_lat))
* COS(RADIANS(pickup_lat))
* COS(RADIANS($local_long - pickup_lng)))) * 69.09) AS distance ";


	return $fields."   " . $sql_distance.", pickup_lat, pickup_lng ";
}

//---------------------


function shipme_join_pickup_lat_lon($wp_join)
{

    global $wpdb;
    $wp_join .= " INNER JOIN (
        SELECT post_id, meta_value as pickup_lat
        FROM $wpdb->postmeta
        WHERE meta_key =  'pickup_lat' ) AS DD1
        ON $wpdb->posts.ID = DD1.post_id ";

        $wp_join .= " INNER JOIN (
            SELECT post_id, meta_value as pickup_lng
            FROM $wpdb->postmeta
            WHERE meta_key =  'pickup_lng' ) AS DD2
            ON $wpdb->posts.ID = DD2.post_id ";



          return ($wp_join);
}



function shipme_join_delivery_lat_lon($wp_join)
{

    global $wpdb;
    $wp_join .= " INNER JOIN (
        SELECT post_id, meta_value as delivery_lat
        FROM $wpdb->postmeta
        WHERE meta_key =  'delivery_lat' ) AS DD3
        ON $wpdb->posts.ID = DD3.post_id ";

        $wp_join .= " INNER JOIN (
            SELECT post_id, meta_value as delivery_lng
            FROM $wpdb->postmeta
            WHERE meta_key =  'delivery_lng' ) AS DD4
            ON $wpdb->posts.ID = DD4.post_id ";



          return ($wp_join);
}


  //---------------------

  function shipme_where_delivery_lon($where)
	{
			global $local_long, $local_lat, $radius ;
			global $wpdb;



      $local_lat  = $_GET['end_lat'] + 0.0000001;
      $local_long = $_GET['end_lon'] + 0.0000001;
      $radius = 15;

      if(isset($_GET['dropoff_radius'])) $radius = $_GET['dropoff_radius'];

      $radius = trim($radius);
      if(empty($radius)) $radius = 15;


      $where .= " AND

      ((ACOS(SIN($local_lat * PI() / 180) * SIN(`delivery_lat` * PI() / 180) + COS($local_lat * PI() / 180) * COS(`delivery_lat` * PI() / 180) *
      COS(($local_long - `delivery_lng`) * PI() / 180)) * 180 / PI()) * 60*1.1515*1.609344)

      <  $radius ";



		return $where;
	}


  function shipme_where_pickup_lon($where)
	{
			global $local_long, $local_lat, $radius ;
			global $wpdb;



      $local_lat  = $_GET['start_lat'] + 0.0000001;
      $local_long = $_GET['start_lon'] + 0.0000001;

      $radius = 15;
      if(isset($_GET['pickup_radius'])) $radius = $_GET['pickup_radius'];


      $radius = trim($radius);
      if(empty($radius)) $radius = 15;

      $where .= " AND

      ((ACOS(SIN($local_lat * PI() / 180) * SIN(`pickup_lat` * PI() / 180) + COS($local_lat * PI() / 180) * COS(`pickup_lat` * PI() / 180) *
      COS(($local_long - `pickup_lng`) * PI() / 180)) * 180 / PI()) * 60*1.1515*1.609344)

      <  $radius ";



		return $where;
	}

//---------------------



function shipme_theme_advance_search_page_new()
{

  ob_start();

  ?>


  <div class="ship-pageheader">
              <ol class="breadcrumb ship-breadcrumb">
                <li class="breadcrumb-item"><a href="<?php echo get_site_url() ?>"><?php printf(__("Home","shipme") ) ?></a></li>
                <li class="breadcrumb-item active" aria-current="page"><?php printf(__("Advanced Search","shipme") ) ?></li>
              </ol>
              <h6 class="ship-pagetitle"><?php printf(__("Advanced Search","shipme") ) ?></h6>
            </div>


 <div class="row">


              <script src="<?php echo get_template_directory_uri() ?>/js/address-fields1.js" async defer></script>

   <div class="col col-sm-12 col-lg-3">
         <h6 class="ship-card-title"><?php _e('Search Filters','shipme') ?></h6>

         <div class="card p-3">
           <?php


              // lets see if we use permalinks , nice permalinks

              if(shipme_using_permalinks()) $action = get_permalink(get_option('shipme_adv_search_page_id'));
              else
              {
                  $action = get_site_url();
                  $field = '<input type="hidden" name="page_id" value="'.get_option('shipme_adv_search_page_id').'" />';

              }


            ?>
           <form method="get" action="<?php echo $action ?>"> <?php echo $field ?>
             <div class="form-group">
               <label for="exampleInputEmail1"><?php _e('Keyword:','shipme') ?></label>
               <input type="text" class="form-control" id="keyword" name="keyword" aria-describedby="emailHelp" value="<?php echo stripslashes($_GET['keyword']); ?>" placeholder="<?php _e('Enter your keyword...','shipme') ?>">

             </div>



             <div class="form-group">
               <label for="exampleInputEmail1"><?php _e('Pickup Location:','shipme') ?></label>
               <input type="text" class="form-control" id="start_location" name="start_location"   value="<?php echo stripslashes($_GET['start_location']); ?>" placeholder="<?php _e('Enter your location...','shipme') ?>">

               <input type="hidden" size="35" name="start_lat" id="start_lat" value="<?php echo stripslashes($_GET['start_lat']) ?>"  class="form-control" />
               <input type="hidden" size="35" name="start_lon" id="start_lon" value="<?php echo stripslashes($_GET['start_lon']) ?>"    class="form-control" />

             </div>


             <?php do_action('shp_after_pickup_location_search') ?>


             <div class="form-group">
               <label for="exampleInputEmail1"><?php _e('Drop-off Location:','shipme') ?></label>
               <input type="text" class="form-control" id="end_location" name="end_location"   value="<?php echo stripslashes($_GET['end_location']); ?>" placeholder="<?php _e('Enter your location...','shipme') ?>">

               <input type="hidden" size="35" name="end_lat" id="end_lat" value="<?php echo stripslashes($_GET['end_lat']) ?>"  class="form-control" />
               <input type="hidden" size="35" name="end_lon" id="end_lon"  value="<?php echo stripslashes($_GET['end_lon']) ?>"  class="form-control" />

             </div>

             <?php do_action('shp_after_dropoff_location_search') ?>

             <div class="form-group">
               <label for="exampleInputEmail1"><?php _e('Minimum Price:','shipme') ?></label>
               <div class="input-group mb-3">
                 <div class="input-group-prepend">
                   <span class="input-group-text"><?php echo get_option('shipme_currency_symbol') ?></span>
                 </div>
                 <input type="double" value="<?php echo stripslashes($_GET['min_price']); ?>" name="min_price" class="form-control" aria-label="<?php _e('Amount','shipme') ?>">

               </div>
             </div>



             <div class="form-group">
               <label for="exampleInputEmail1"><?php _e('Maximum Price:','shipme') ?></label>
               <div class="input-group mb-3">
                 <div class="input-group-prepend">
                   <span class="input-group-text"><?php echo get_option('shipme_currency_symbol') ?></span>
                 </div>
                 <input type="double" value="<?php echo stripslashes($_GET['max_price']); ?>"  name="max_price" class="form-control" aria-label="<?php _e('Amount','shipme') ?>">

               </div>

             </div>


             <script>

             function display_subcat(vals)
             {
               jQuery.post("<?php bloginfo('siteurl'); ?>/?get_subcats_for_me_search=1", {queryString: ""+vals+""}, function(data){
                 if(data.length >0) {

                   jQuery('#subcat-container').html(data);

                 }
               });

             }

             </script>




             <div class="form-group">
               <label for="exampleInputPassword1"><?php _e('Category:','shipme') ?></label>


               <?php

               echo 	  shipme_get_categories_clck_new("job_ship_cat",    $_GET['job_ship_cat_cat'] , __('Select Category','shipme'), "form-control do_input", 'onchange="display_subcat(this.value)"' );

                ?>


                <span id="subcat-container">
                <?php

                $job_ship_cat_cat_subcat = get_term_by("slug", $_GET['job_ship_cat_cat'], 'job_ship_cat');
                if($job_ship_cat_cat_subcat != false)
                {

                echo 	  shipme_get_categories_clck_new_subcat($job_ship_cat_cat_subcat->term_id, "job_ship_cat",    $_GET['subcat_job_ship_cat_cat'] , __('Select subcategory','shipme'), "form-control do_input", ' ' );

              }

                 ?>
               </span>


             </div>



             <script type="text/javascript">
             jQuery(function () {
                 jQuery('#date-start').datepicker();
                     jQuery('#date-end').datepicker();


             });
         </script>


         <div class="form-group">
           <label for="exampleInputEmail1"><?php _e('Pickup Date:','shipme') ?></label>
           <div class="input-group mb-3">

             <input type="text" value="<?php echo stripslashes($_GET['date_start']); ?>"  name="date_start" id="date_start" class="form-control" id="date-start" />

           </div>

         </div>


         <div class="form-group">
           <label for="exampleInputEmail1"><?php _e('Delivery Date:','shipme') ?></label>
           <div class="input-group mb-3">

             <input type="text" value="<?php echo stripslashes($_GET['date_end']); ?>"  name="date_end" name="date_end" class="form-control" id="date-end" />

           </div>

         </div>



   <div class="mt-3">
             <button type="submit" class="btn btn-primary btn-lg btn-block"><?php _e('Submit','shipme') ?></button>
           </div>
           </form>



         </div>
   </div>





   <div class="col col-sm-12 col-lg-9">
         <h6 class="ship-card-title"><?php _e('Search Results','shipme') ?></h6>




           <?php


           if(isset($_GET['pj'])) $pj = $_GET['pj'];
           else $pj = 1;


           $nrpostsPage = 10;



           if(!empty($_GET['min_price'])) {

             $prcmin = $_GET['min_price'];

             $price_q1 = array(
               'key' => 'price',
               'type' => 'NUMERIC',
               'value' => $prcmin,
               'compare' => '>='
             );


           }


           if(!empty($_GET['max_price'])) {

             $prcmax = $_GET['max_price'];

             $price_q2 = array(
               'key' => 'price',
               'type' => 'NUMERIC',
               'value' => $prcmax,
               'compare' => '=<'
             );
           }

           // check for closed
           $closed = array(
               'key' => 'closed',
               'value' => "0",
               //'type' => 'numeric',
               'compare' => '='
             );


             if(!empty($_GET['date_start']))
             {
               $vl = strtotime($_GET['date_start']);
               $vl1 = $vl - 11000;
               $vl2 = $vl + 11000;

              // check for closed
              $date_start = array(
                 'key' => 'pickup_date',
                 'value' => array($vl1, $vl2),
                 //'type' => 'numeric',
                 'compare' => 'BETWEEN'
               );

             }



             if(!empty($_GET['date_end']))
             {
               $vl = strtotime($_GET['date_end']);
               $vl1 = $vl - 11000;
               $vl2 = $vl + 11000;

              // check for closed
              $date_start = array(
                 'key' => 'delivery_date',
                 'value' => array($vl1, $vl2),
                 //'type' => 'numeric',
                 'compare' => 'BETWEEN'
               );

             }




             if(!empty($_GET['start_lat']) and !empty($_GET['start_lon']))
             {

              global $local_lat, $local_long;
              $local_lat  = $_GET['start_lat'];
              $local_long = $_GET['start_lon'];

               add_filter('posts_join',   'shipme_join_pickup_lat_lon' );
               add_filter('posts_where',  'shipme_where_pickup_lon');

              }


               if(!empty($_GET['end_lat']) and !empty($_GET['end_lon']))
              {
                    //shipme_join_delivery_lat_lon
                    add_filter('posts_join',   'shipme_join_delivery_lat_lon' );
                    add_filter('posts_where',  'shipme_where_delivery_lon');
              }




                          global $term;
                        	$term = trim($_GET['keyword']);

                        	if(!empty($_GET['keyword']))
                        	{
                        		add_filter( 'posts_where' , 'shipme_posts_where2' );

                        	}




                        if(!empty($_GET['job_ship_cat_cat']))
                       	{
                          $job_ship_cat_cat = array(
                              'taxonomy' => 'job_ship_cat',
                              'field' => 'slug',
                              'terms' => $_GET['job_ship_cat_cat']

                          );

                          if(!empty($_GET['subcat_job_ship_cat_cat']))
                          {
                            $job_ship_cat_cat2 = array(
                                'taxonomy' => 'job_ship_cat',
                                'field' => 'slug',
                                'terms' => $_GET['subcat_job_ship_cat_cat']

                            );

                        }

                       	}


     global $wpdb;
     $wpdb->show_errors = true;

           $args = array( 'posts_per_page' => $nrpostsPage, 'paged' => $pj, 'post_type' => 'job_ship', 'order' => $order ,
           'meta_query' => array($price_q1, $price_q2, $closed, $featured, $date_start) ,'meta_key' => $meta_key, 'orderby'=>$orderby,'tax_query' => array($job_ship_cat_cat, $job_ship_cat_cat2));


           $the_query = new WP_Query( $args );





           $nrposts = $the_query->found_posts;
           $totalPages = ceil($nrposts / $nrpostsPage);
           $pagess = $totalPages;




              // The Loop

              if($the_query->have_posts()):
              while ( $the_query->have_posts() ) : $the_query->the_post();


                shipme_get_regular_job_post_new_account_new('zubs1' );


              endwhile;



            $pjsk = $pj;

           ?>




                                <ul class="pagination">
                                <?php


                    $my_page 	= $pj;
                    $page 		= $pj;

                    $batch = 10;
                    $nrpostsPage = $nrRes;
                    $end = $batch * $nrpostsPage;

                    if ($end > $pagess) {
                      $end = $pagess;
                    }
                    $start = $end - $nrpostsPage + 1;

                    if($start < 1) $start = 1;

                    $links = '';

                    $raport = ceil($my_page/$batch) - 1; if ($raport < 0) $raport = 0;

                    $start 		= $raport * $batch + 1;
                    $end		= $start + $batch - 1;
                    $end_me 	= $end + 1;
                    $start_me 	= $start - 1;

                    if($end > $totalPages) $end = $totalPages;
                    if($end_me > $totalPages) $end_me = $totalPages;

                    if($start_me <= 0) $start_me = 1;

                    $previous_pg = $page - 1;
                    if($previous_pg <= 0) $previous_pg = 1;

                    $next_pg = $pages_curent + 1;
                    if($next_pg > $totalPages) $next_pg = 1;


              if($my_page > 1)
              {
                echo '<li class="page-item"><a class="page-link" href="'.shipme_advanced_search_link_pgs($previous_pg).'">'.
                __("<< Previous","ProjectTheme").'</a></li>';
                echo '<li class="page-item"><a class="page-link" href="'.shipme_advanced_search_link_pgs($start_me).'"><<</a></li>';
              }


              for($i = $start; $i <= $end; $i ++) {
                if ($i == $pj) {
                  echo '<li class="page-item"><a class="page-link" id="activees" href="#">'.$i.'</a></li>';
                } else {


                  echo '<li class="page-item"><a class="page-link" href="'.shipme_advanced_search_link_pgs($i).'">'.$i.'</a></li>';
                }
              }





              $next_pg = $pjsk+1;


              if($totalPages > $my_page)
              echo '<a class="page-link" href="'.shipme_advanced_search_link_pgs($end_me).'">>></a>';

              if($page < $totalPages)
              echo '<a class="page-link" href="'.shipme_advanced_search_link_pgs($next_pg).'">'.
              __("Next >>","ProjectTheme").'</a>';



                     ?>
                                </div>
                             <?php

                  else:


              echo '<div class="card p-3">   ';
              echo __('No jobs posted.',"shipme");
              echo '</div> ';


              endif;
              // Reset Post Data
              wp_reset_postdata();



              ?>




   </div>




 </div>


  <?php


  $page = ob_get_contents();
   ob_end_clean();

   return $page;


}

 ?>
