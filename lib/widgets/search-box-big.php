<?php

add_action("widgets_init","shipme_search_box_fnc");
function shipme_search_box_fnc()
{
    register_widget("shipme_search_big_widget");
}

class shipme_search_big_widget extends WP_Widget
{
    function  widget($args,$instance)
    {
		 extract($args,EXTR_SKIP);

      $title      =   $instance['title'] ;
      $nr         =   $instance['nr'] ;
	    $columns    =   $instance['columns'] ;



     echo $before_widget;
		?>
		<div class="widget-home-search-box"  >

        <?php

              if(!empty($title)) {

         ?>

                    <h6 class="widget-title"><?php echo $title ?></h6>

       <?php } ?>


       <div class="container">
         <form action="<?php if(shipme_using_permalinks()) echo get_permalink( get_option('shipme_adv_search_page_id') ); else echo get_site_url(); ?>" method="get" novalidate="novalidate">


           <script src="<?php echo get_template_directory_uri() ?>/js/address-fields1.js" async defer></script>


           <?php

              if(!shipme_using_permalinks())
              {
                echo '<input type="hidden" value="'.get_option('shipme_adv_search_page_id').'" name="page_id" />';
              }

            ?>
             <div class="row">
                 <div class="col-lg-12">
                     <div class="row">
                       <div class="shadow-wrapper">
                         <div class="col-lg-4 col-md-4 col-sm-12 p-0">
                             <input type="text" class="form-control search-slt search-slt1" placeholder="<?php _e('Enter Pickup City','shipme'); ?>" name="start_location" id='start_location' />



                                                   <input type="hidden" size="35" name="start_lat" id="start_lat"  class="form-control" />
                                                   <input type="hidden" size="35" name="start_lon" id="start_lon"   class="form-control" />


                         </div>
                         <div class="col-lg-3 col-md-4 col-sm-12 p-0">
                             <input type="text" class="form-control search-slt" placeholder="<?php _e('Enter Drop City','shipme'); ?>" name="end_location" id='end_location' />

                             <input type="hidden" size="35" name="end_lat" id="end_lat"  class="form-control" />
                             <input type="hidden" size="35" name="end_lon" id="end_lon"   class="form-control" />
                         </div>
                         <div class="col-lg-3 col-md-2 col-sm-12 p-0">
                             <select class="form-control search-slt" name="job_ship_cat" id="exampleFormControlSelect1">
                                 <option><?php _e('Select Category','shipme'); ?></option>

                                 <?php

                                          $terms = get_terms('job_ship_cat', array( 'hide_empty' => false, 'parent' => 0 ) );
                                          foreach($terms as $term)
                                          {
                                            echo '<option value="'.$term->slug.'">'.$term->name.'</option>';
                                          }

                                  ?>
                             </select>
                         </div>
                         <div class="col-lg-2 col-md-2 col-sm-12 p-0">
                             <input type="submit" value="<?php _e('Search','shipme') ?>" name="submit-form" class="btn btn-primary btn-block padding-btn submit-form-search" />
                         </div>
                     </div>
                 </div>
                 </div>
             </div>
         </form>
        </div>

       </div>


		<?php
		echo $after_widget;
    }



      function __construct() {
      parent::__construct(

      // Base ID of your widget
      'job_search_widget',

      // Widget name will appear in UI
      __('Search Widget - ShipMe', 'ProjectTheme'),

      // Widget description
      array( 'description' => __( 'This shows a widget with a search bar.', 'ProjectTheme' ), )
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







		<?php
   }
}


?>
