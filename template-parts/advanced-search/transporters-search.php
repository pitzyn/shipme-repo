<?php

function shipme_theme_transporters_fnc()
{
      ob_start();

      ?>


      <div class="ship-pageheader">
                <ol class="breadcrumb ship-breadcrumb">
                  <li class="breadcrumb-item"><a href="<?php echo get_site_url() ?>"><?php echo __('Home','shipme') ?></a></li>
                        <li class="breadcrumb-item active" aria-current="page"><?php echo __('Search Transpoters','shipme') ?></li>
                </ol>
                <h6 class="ship-pagetitle"><?php printf(__("Search Transporters","shipme") ) ?></h6>
              </div>


<div class="row">

  <div class="col col-sm-12 col-lg-3">
        <h6 class="ship-card-title"><?php _e('Search Filters','shipme') ?></h6>

        <div class="card p-3">

          <form method="get" action="">
            <div class="form-group">
              <label for="exampleInputEmail1"><?php _e('Keyword:','shipme') ?></label>
              <input type="text" class="form-control" id="keyword" name="keywork" aria-describedby="emailHelp" value="<?php echo stripslashes($_GET['keywork']); ?>" placeholder="<?php _e('Enter your keyword...','shipme') ?>">

            </div>






            <div class="form-group">
              <label for="exampleInputPassword1"><?php _e('Category:','shipme') ?></label>


                <?php

                echo 	  shipme_get_categories_clck("job_ship_cat",    $_GET['job_ship_cat_cat'] , __('Select Category','shipme'), "form-control do_input", 'onchange="display_subcat(this.value)"' );

                 ?>
            </div>







  <div class="mt-3">
            <button type="submit" class="btn btn-primary btn-lg btn-block"><?php _e('Submit','shipme') ?></button>
          </div>
          </form>



        </div>
  </div>


  <div class="col col-sm-12 col-lg-9  profile-page">
        <h6 class="ship-card-title"><?php _e('Search Results','shipme') ?></h6>




              <?php


        $pg = $_GET['pg'];
        if(empty($pg)) $pg = 1;

        $nrRes = 10;

        //------------------

        $offset = ($pg-1)*$nrRes;

        //------------------

              if(isset($_GET['username']))
          $args['search'] = "*".trim($_GET['username'])."*";


        // prepare arguments
        $args['orderby']  = 'display_name';
        $arr_aray = array();



        if(!empty($_GET['rating_over']))
        {
          $arr_sbg = 	array(
              // uses compare like WP_Query
              'key' => 'cool_user_rating',
              'value' => $_GET['rating_over'],
              'compare' => '>'
              );

          array_push(	$arr_aray, 	$arr_sbg);
        }




          array_push(	$arr_aray, 	$arr_sbg);


        //-----------------------------------------------

        $args['meta_query']  	= $arr_aray;
        $args['number'] 		= $nrRes;
        $args['offset'] 		= $offset;
        $args['role'] 		= 'transporter';
        $args['count_total'] 	= true;

        //-----------------------------------------------

        $wp_user_query = new WP_User_Query($args);
        // Get the results
        $ttl = $wp_user_query->total_users;


        $nrPages = ceil($ttl / $nrRes);
            global $wpdb;

            if(!empty($_GET['keywork']))
            {
                $keyw = sanitize_text_field($_GET['keywork']);
                $inner = " INNER JOIN $wpdb->usermeta usermeta2 ON usermeta2.user_id=users.ID ";
                $where = " AND usermeta2.meta_value LIKE '%".$keyw."%' ";
            }



            if(!empty($_GET['job_ship_cat_cat']))
            {
                $keyw = sanitize_text_field($_GET['job_ship_cat_cat']);
                $inner2 = " INNER JOIN ".$wpdb->prefix."ship_email_alerts alerts ON alerts.uid=users.ID ";
                $where2 = " AND alerts.catid = '".$keyw."' ";
            }






		//print_r($wpdb);

        $s = "select distinct users.* from  $wpdb->users  users ".$inner." ". $inner2 ." inner join $wpdb->usermeta usermeta ON usermeta.user_id=users.ID where usermeta.meta_key='".$wpdb->prefix."capabilities' and usermeta.meta_value LIKE '%transporter%' ". $where ." ". $where2;
        $r = $wpdb->get_results($s);



        // Check for results
        if (count($r) > 0)
        {


          foreach ($r as $author)
          {
            // get all the user's data

            shipme_get_user_table_row($author->ID);
          }


          echo '<div class="div_class_div" style="display:none">';

          $totalPages = $nrPages;
          $my_page = $pg;
          $page = $pg;


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

          $next_pg = $page + 1;
          if($next_pg > $totalPages) $next_pg = 1;




          if($my_page > 1)
          {
            echo '<a href="'.shipme_provider_search_link() .'pg='.$previous_pg.'" class="btn btn-sm btn-outline-primary"><< '.__('Previous','shipme').'</a>';
            echo '<a href="'.shipme_provider_search_link() .'pg='.$start_me.'" class="btn btn-sm btn-outline-primary"><<</a>';
          }

            for($i=$start;$i<=$end;$i++)
            {
              if($i == $pg)
              echo '<a href="#" class="btn btn-sm btn-outline-primary" id="activees">'.$i.'</a>';
              else
              echo '<a href="'.shipme_provider_search_link() .'pg='.$i.'" class="btn btn-sm btn-outline-primary">'.$i.'</a>';
            }

          if($totalPages > $my_page)
          echo '<a href="'.shipme_provider_search_link() .'pg='.$end_me.'" class="btn btn-sm btn-outline-primary">>></a>';

          if($page < $totalPages)
          echo '<a href="'.shipme_provider_search_link() .'pg='.$next_pg.'" class="btn btn-sm btn-outline-primary">'.__('Next','shipme').' >></a>';


          echo '</div>';

        } else {

          echo '<div class="card p-3">';
          echo __('No transporters found for this query.', 'shipme' );
          echo '</div>';
        }



  ?>




              </div>

        </div>

    <?php



    $page = ob_get_contents();
     ob_end_clean();

     return $page;


}


 ?>
