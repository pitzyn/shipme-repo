<?php
// single job ship page

    get_header();


    ?>



    <?php if ( have_posts() ) while ( have_posts() ) : the_post(); ?>


    <?php

    	$pid = get_the_ID();


          $pst = get_post($pid);
          $tm = get_the_time('l, F jS, Y') ;

    ?>

        <div class="ship-mainpanel">
            <div class="container">

    <div class="ship-pageheader pb-0">
      <ol class="breadcrumb ship-breadcrumb"> </ol>
        <h6 class="ship-pagetitle"><?php echo $pst->post_title ?></h6>

      </div>

      <div class="ship-card-title mt-3 mb-4 text-capitalize"><?php printf(__('Posted on: %s','shipme'), $tm  ); ?>  | <?php printf(__('ID: #%s','shipme'), $pid)  ?></div>





                    <div class="row row-sm"> <div class="col-lg-12">
                  <div class="card card-job-thing mb-4">
                      <div class="card-body">


                            <?php the_content() ?>

                      </div></div>

                      </div>  </div>






      </div>      </div>


    <?php endwhile; ?>


 <?php get_footer() ?>
