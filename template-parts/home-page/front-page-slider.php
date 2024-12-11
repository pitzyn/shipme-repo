<?php

function shp_frontpage_slider()
{

    $shipme_homepage_big_text_enable = get_option('shipme_homepage_big_text_enable');
    $shipme_homepage_sub_text_enable = get_option('shipme_homepage_sub_text_enable');


    $shipme_homepage_big_text_color = get_option('shipme_homepage_big_text_color');
    $shipme_homepage_sub_text_color = get_option('shipme_homepage_sub_text_color');

    echo "<style>.bigtext-hmpg { color: ".$shipme_homepage_big_text_color." !important } .subtext-hmpg { color: ".$shipme_homepage_sub_text_color." !important } </style>";



  ?>

  <div class="ship-landing-headline">
    <div class="container">
      <div class="row">
        <div class="col-lg-6">
          <?php if($shipme_homepage_big_text_enable != "no") { ?><h1 class="bigtext-hmpg"><?php echo get_option('shipme_homepage_big_text_text') ?></h1><?php } ?>
          <?php if($shipme_homepage_sub_text_enable != "no") { ?><h5 class="subtext-hmpg"><?php echo get_option('shipme_homepage_sub_text_text') ?></h5><?php } ?>
          <a href="<?php echo get_option('shipme_home_btn_left_link') ?>" class="btn btn-ship-purchase"><?php echo get_option('shipme_home_btn_left_caption') ?></a>
          <a href="<?php echo get_option('shipme_home_btn_right_link') ?>" target="_blank" class="btn btn-ship-demo"><?php echo get_option('shipme_home_btn_right_caption') ?></a>
        </div><!-- col-6 -->
        <div class="col-lg-6 mg-t-20 mg-lg-t-0" id="image1a">
          <img src="<?php echo get_option('shipme_home_right_image') ?>"  class="img-fluid" alt="">
        </div><!-- col-6 -->
      </div><!-- row -->
    </div><!-- container -->
  </div>




  <?php
}



 ?>
