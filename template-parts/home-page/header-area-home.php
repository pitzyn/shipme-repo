<?php


function shp_header_area_homepage()
{




 ?>




 <div class="ship-landing-header">
   <div class="container">
     <div class="ship-landing-header-left">

       <!-- this is for the mobile menu -->
                   <div class="shp-toggle-sidebar shp-material-button">
                         <i class="material-icons shp-open-icon">&#xE5D2;</i>
                         <i class="material-icons shp-close-icon"></i>
                       </div>

       <!-- this is for the mobile menu -->

        <?php shp_logo_or_placeholder() ?>

     </div>
     <?php echo shipme_user_top_menus(); ?>
   </div><!-- container -->
 </div>


<?php

    ship_mobile_menu();

} ?>
