
<!DOCTYPE html>
<html lang="en">
  <head>
    <!-- Required meta tags -->
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">

    <?php wp_head() ?>

    <?php do_action('shipme_top_header_below_wp_head') ?>

  </head>

  <body <?php body_class(); ?>>


    <?php if(shipme_is_home()) { ?>

      <?php shp_header_area_homepage() ?>

    <?php } else {

            shp_header_area_regularpage();

    } ?>


<?php


  if(!shipme_is_home())
  {
      shp_main_menu();
  }

 ?>


<?php if(shipme_is_home()) { ?>

  <?php shp_frontpage_slider() ?>

<?php } ?>
