<?php

add_action("widgets_init","shipme_job_latest_jobs");
function shipme_job_latest_jobs()
{
    register_widget("shipme_latest_posted_jobs_widget");
}

class shipme_latest_posted_jobs_widget extends WP_Widget
{
    function  widget($args,$instance)
    {
		 extract($args,EXTR_SKIP);

      $title      =   $instance['title'] ;
      $nr         =   $instance['nr'] ;



     echo $before_widget;
		?>
		<div class="widget-home-categories-text"  >

        <?php

              if(!empty($title)) {

         ?>

                    <h6 class="widget-title" style="margin-top:25px"><?php echo $title ?></h6>

       <?php } ?>


       <?php
         if(empty($nr) || !is_numeric($nr)) $nr = 5;

      global $wpdb;
      $querystr = "
       SELECT distinct wposts.*
       FROM $wpdb->posts wposts, $wpdb->postmeta wpostmeta
       WHERE wposts.ID = wpostmeta.post_id
       AND wpostmeta.meta_key = 'closed'
       AND wpostmeta.meta_value = '0' AND
       wposts.post_status = 'publish'
       AND wposts.post_type = 'job_ship'
       ORDER BY wposts.post_date DESC LIMIT ".$nr;

      $pageposts = $wpdb->get_results($querystr, OBJECT);

      ?>

        <?php $i = 0; if ($pageposts): ?>
        <?php global $post; ?>
                  <?php foreach ($pageposts as $post): ?>
                  <?php setup_postdata($post); ?>


                  <?php  shipme_get_regular_job_post_new_account_new_widget('zubs1' ); ?>


                  <?php endforeach; ?>
                  <?php else : ?> <?php $no_p = 1; ?>
                    <div class="padd100"><p class="center"><?php _e("Sorry, there are no posted jobs yet.",'shipme'); ?></p></div>

                  <?php endif; ?>
</div>


		<?php
		echo $after_widget;
    }



    function __construct() {
    parent::__construct(

    // Base ID of your widget
    'job_latest_posted_widget',

    // Widget name will appear in UI
    __('Latest Posted Jobs - ShipMe', 'ProjectTheme'),

    // Widget description
    array( 'description' => __( 'This shows a widget with the latest posted jobs.', 'ProjectTheme' ), )
    );
    }

 
   function update($new_instance, $old_instance) {

     return $new_instance;
   }

   function form($instance)
   {

   $rand1 = rand(1,99999);

		?>

  <p>
    <label for="<?php echo $this->get_field_id("title");  ?>">
    <p>Title of widget: <input type="text"  style="width:100%"  value="<?php echo $instance['title']; ?>" name="<?php  echo $this->get_field_name("title"); ?>" id="<?php  echo $this->get_field_id("title") ?>"></p>
    </label>
  </p>



<p>
<label for="<?php echo $this->get_field_id("columns");  ?>">
<p>Columns: <input type="text"  style="width:100%"  value="<?php echo $instance['columns']; ?>" name="<?php  echo $this->get_field_name("columns"); ?>" id="<?php  echo $this->get_field_id("columns") ?>"></p>
</label>
</p>






		<?php
   }
}


?>
