<?php

  get_header();

 ?>
<div class="ship-mainpanel">
<div class="container">

 <div class="ship-pageheader">
             <ol class="breadcrumb ship-breadcrumb">
               <li class="breadcrumb-item"><a href="<?php echo get_site_url() ?>"><?php printf(__("Home","shipme") ) ?></a></li>
               <li class="breadcrumb-item active" aria-current="page"><?php printf(__("All Jobs","shipme") ) ?></li>
             </ol>
             <h6 class="ship-pagetitle"><?php printf(__("All Jobs","shipme") ) ?></h6>
           </div>


           <div class="row">


                        <script src="<?php echo get_template_directory_uri() ?>/js/address-fields1.js" async defer></script>

             <div class="col col-sm-12 col-lg-3">
                   <h6 class="ship-card-title"><?php _e('Search Filters','shipme') ?></h6>

                   <div class="card p-3">

                     <form method="get" action="<?php echo get_post_type_archive_link('job_ship') ?>">
                       <div class="form-group">
                         <label for="exampleInputEmail1"><?php _e('Keyword:','shipme') ?></label>
                         <input type="text" class="form-control" id="keyword" name="keywork" aria-describedby="emailHelp" value="<?php echo stripslashes($_GET['keywork']); ?>" placeholder="<?php _e('Enter your keyword...','shipme') ?>">

                       </div>



                       <div class="form-group">
                         <label for="exampleInputEmail1"><?php _e('Pickup Location:','shipme') ?></label>
                         <input type="text" class="form-control" id="start_location" name="start_location"   value="<?php echo stripslashes($_GET['start_location']); ?>" placeholder="<?php _e('Enter your location...','shipme') ?>">

                         <input type="hidden" size="35" name="start_lat" id="start_lat" value="<?php echo stripslashes($_GET['start_lat']) ?>"  class="form-control" />
                         <input type="hidden" size="35" name="start_lon" id="start_lon" value="<?php echo stripslashes($_GET['start_lon']) ?>"    class="form-control" />

                       </div>


                       <div class="form-group">
                         <label for="exampleInputEmail1"><?php _e('Drop-off Location:','shipme') ?></label>
                         <input type="text" class="form-control" id="end_location" name="end_location"   value="<?php echo stripslashes($_GET['end_location']); ?>" placeholder="<?php _e('Enter your location...','shipme') ?>">

                         <input type="hidden" size="35" name="end_lat" id="end_lat" value="<?php echo stripslashes($_GET['end_lat']) ?>"  class="form-control" />
                         <input type="hidden" size="35" name="end_lon" id="end_lon"  value="<?php echo stripslashes($_GET['end_lon']) ?>"  class="form-control" />

                       </div>



                       <div class="form-group">
                         <label for="exampleInputEmail1"><?php _e('Minimum Price:','shipme') ?></label>
                         <div class="input-group mb-3">
                           <div class="input-group-prepend">
                             <span class="input-group-text"><?php echo shipme_get_currency() ?></span>
                           </div>
                           <input type="double" value="<?php echo stripslashes($_GET['min_price']); ?>" name="min_price" class="form-control" aria-label="<?php _e('Amount','shipme') ?>">
                         </div>
                       </div>



                       <div class="form-group">
                         <label for="exampleInputEmail1"><?php _e('Maximum Price:','shipme') ?></label>
                         <div class="input-group mb-3">
                           <div class="input-group-prepend">
                             <span class="input-group-text"><?php echo shipme_get_currency() ?></span>
                           </div>
                           <input type="double" value="<?php echo stripslashes($_GET['max_price']); ?>"  name="max_price" class="form-control" aria-label="<?php _e('Amount','shipme') ?>">

                         </div>

                       </div>







                       <div class="form-group">
                         <label for="exampleInputPassword1"><?php _e('Category:','shipme') ?></label>


                           <?php

                           echo 	  shipme_get_categories_clck_new("job_ship_cat",    $_GET['job_ship_cat_cat'] , __('Select Category','shipme'), "form-control do_input", 'onchange="display_subcat(this.value)"' );

                            ?>
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


                     $closed = array(
                         'key' => 'closed',
                         'value' => "0",
                         //'type' => 'numeric',
                         'compare' => '='
                     );

                     $meta_key = "featured";
                     $orderby = "meta_value";


                     global $term;
                     $term = trim($_GET['keywork']);

                     if(!empty($_GET['keywork']))
                     {
                       add_filter( 'posts_where' , 'shipme_posts_where2' );

                     }

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




                                              if(!empty($_GET['job_ship_cat_cat']))
                                            	{
                                            		$job_ship_cat_cat = array(
                                            				'taxonomy' => 'job_ship_cat',
                                            				'field' => 'slug',
                                            				'terms' => $_GET['job_ship_cat_cat']

                                            		);


                                            	}



                     $nrpostsPage = 10;

                     $args = array( 'posts_per_page' => $nrpostsPage, 'paged' => $pj, 'post_type' => 'job_ship', 'order' => $order ,
                     'meta_query' => array($price_q1, $price_q2, $closed, $featured) ,'meta_key' => $meta_key, 'orderby'=>$orderby,'tax_query' => array($job_ship_cat_cat));


                     $the_query = new WP_Query( $args );


                     $nrposts = $the_query->found_posts;
                     $totalPages = ceil($nrposts / $nrpostsPage);
                     $pagess = $totalPages;




                        // The Loop

                        if($the_query->have_posts()):
                        while ( $the_query->have_posts() ) : $the_query->the_post();


                          shipme_get_regular_job_post_new_account_new('zubs1' );


                        endwhile;

                      if(isset($_GET['pj'])) $pj = $_GET['pj'];
                      else $pj = 1;

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
                          echo '<li class="page-item"><a class="bighi" href="'.shipme_advanced_search_link_pgs($start_me).'"><<</a></li>';
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
                        echo '<a class="bighi" href="'.shipme_advanced_search_link_pgs($end_me).'">>></a>';

                        if($page < $totalPages)
                        echo '<a class="bighi" href="'.shipme_advanced_search_link_pgs($next_pg).'">'.
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



     </div>     </div>

     <?php get_footer() ?>
