<?php

function shp_header_area_regularpage()
{

  ?>


  <div class="ship-header">
        <div class="container">
          <div class="ship-header-left">

<!-- this is for the mobile menu -->
            <div class="shp-toggle-sidebar shp-material-button">
                  <i class="material-icons shp-open-icon">&#xE5D2;</i>
                  <i class="material-icons shp-close-icon"></i>
                </div>

<!-- this is for the mobile menu -->

          <?php shp_logo_or_placeholder() ?>

          <form method="get" action="<?php echo get_permalink( get_option('shipme_adv_search_page_id') ) ?>">
            <div class="search-box">
              <input type="text" class="form-control" name="keyword" placeholder="Search">
              <button class="btn btn-primary" type="submit"><i class="fa fa-search"></i></button>
            </div><!-- search-box -->
          </form>
          </div><!-- ship-header-left -->
          <?php

                  shipme_user_top_menus();

           ?>
        </div><!-- container -->
      </div>

  <?php


}

 ?>
