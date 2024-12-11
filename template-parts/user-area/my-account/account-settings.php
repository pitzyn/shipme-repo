<?php

function shipme_theme_my_account_account_settings_new()
{

  ob_start();

  ?>





  <?php


  $page = ob_get_contents();
   ob_end_clean();

   return $page;


 ?>
