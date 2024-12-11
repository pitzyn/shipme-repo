<?php

add_action("widgets_init","shipme_job_cat_fnc");
function shipme_job_cat_fnc()
{
    register_widget("shipme_job_cat_simple_widget");
}

class shipme_job_cat_simple_widget extends WP_Widget
{
    function  widget($args,$instance)
    {
		 extract($args,EXTR_SKIP);

      $title      =   $instance['title'] ;
      $nr         =   $instance['nr'] ;
	    $columns    =   $instance['columns'] ;



     echo $before_widget;
		?>
		<div class="widget-home-categories-text"  >

        <?php

              if(!empty($title)) {

         ?>

                    <h6 class="widget-title"><?php echo $title ?></h6>

       <?php } ?>


      <div class="row"  >
      <?php


      $terms_k = get_terms("job_ship_cat","parent=0&hide_empty=0");


      //$term = get_term( $term_id, $taxonomy );


        $terms = array();

        foreach($terms_k as $trm)
        {
            array_push($terms, $trm->term_id);

        }

        $lg = round(12/count($terms));

        if($columns == 2) $lg = 6;
        else if($columns == 3) $lg = 4;
        else if($columns == 4) $lg = 3;
        else if($columns == 6)  $lg = 2;
        else $lg = 2;


        foreach($terms as $t)
        {

          $is_present    =   $instance['term_' . $t] ;

          if($is_present > 0)
          {

          ?>
                <div class=" col-xs-6 col-sm-6 col-lg-<?php echo $lg ?> category-block-txt">
                    <h5><a href="<?php echo get_term_link($t,'job_ship_cat') ?>"><?php

                          $term1 = get_term($t, 'job_ship_cat');
                          echo  $term1->name;

                     ?></a></h5>

                     <ul class="nav flex-column text-cats-simple">
                     <?php

                            $sub_terms = get_terms("job_ship_cat","parent=".$t."&hide_empty=0");
                            foreach($sub_terms as $t1)
                            {
                                  ?>
                                            <li class="nav-item"><a class="nav-link" href="<?php echo get_term_link($t1->term_id,'job_ship_cat') ?>"><?php echo $t1->name ?></a> </li>

                                  <?php
                            }

                      ?>
                    </ul>

                </div>

          <?php
        }    }




       ?>



		</div></div>


		<?php
		echo $after_widget;
    }




   function __construct() {
   parent::__construct(

   // Base ID of your widget
   'job_cat_text_widget',

   // Widget name will appear in UI
   __('Categories Simple - ShipMe', 'ProjectTheme'),

   // Widget description
   array( 'description' => __( 'This shows a widget with all categories, and subcategories.', 'ProjectTheme' ), )
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



  <p>
  <label for="<?php echo $this->get_field_id('nr_rows'); ?>"><?php _e('Categories to show','ProjectTheme'); ?>:</label>

          <div style=" width:220px;
  height:180px;
  background-color:#ffffff;
  overflow:auto;border:1px solid #ccc">
  <?php

  $terms = get_terms("job_ship_cat","parent=0&hide_empty=0");
  foreach ( $terms as $term ) {

  echo '<input type="checkbox" name="'.$this->get_field_name('term_'.$term->term_id).'"  value="'.$term->term_id.'" '.(
  $instance['term_'.$term->term_id] == $term->term_id ? ' checked="checked" ' : "" ).' /> ';
  echo $term->name.'<br/>';

  }

  ?>

  </div>



  </p>




		<?php
   }
}


?>
