



<div class="ship-footer">


<div class="container">

  		<?php
                  get_sidebar( 'footer' );
          ?>
    </div>

      <div class="container" id="site-information">
        <p><?php echo get_option('shipme_left_side_footer') ?></p>
        <p><?php echo get_option('shipme_right_side_footer') ?></p>
      </div><!-- container -->
    </div>


</body>

<?php wp_footer() ?>
</html>
