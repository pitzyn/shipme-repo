<?php

add_action("widgets_init","shipme_home_page_wide_pic_fnc");
function shipme_home_page_wide_pic_fnc()
{
    register_widget("shipme_home_page_wide_pic_widget");
}

class shipme_home_page_wide_pic_widget extends WP_Widget
{
    function  widget($args,$instance)
    {
		 extract($args,EXTR_SKIP);

     $height =   $instance['height'] ;
     $image_picture =   $instance['image_picture'] ;



     echo $before_widget;
		?>
		<div class="widget-home-picture" style="background:url('<?php echo $image_picture ?>') center center;  background-size: cover; height: <?php echo $height; ?>px">
            <div class="widget-full-inner-picture">




            </div>
		</div>
		<?php
		echo $after_widget;
    }


      function __construct() {
      parent::__construct(

      // Base ID of your widget
      'pic_home_widget',

      // Widget name will appear in UI
      __('Home Page Wide Picture - ShipMe', 'ProjectTheme'),

      // Widget description
      array( 'description' => __( 'This widget lets you chose a picture and places an element accross the full width of the screen. Usually for homepage design.', 'ProjectTheme' ), )
      );
      }


 

   function  update($newinstance,$oldinstance)
   {


		 $instance =  $oldinstance;
    //update the username
    $instance['height']=  $newinstance['height'];
    $instance['image_picture']=  $newinstance['image_picture'];
    return $instance;



		return $instance;
   }

   function form($instance)
   {

   $rand1 = rand(1,99999);

		?>
    <label for="<?php echo $this->get_field_id("height");  ?>">
    <p>Widget Height(px): <input type="text"  style="width:100%"  value="<?php echo $instance['height']; ?>" name="<?php  echo $this->get_field_name("height"); ?>" id="<?php  echo $this->get_field_id("height") ?>"></p>
    </label>




    <p>
<label for="<?php echo $this->get_field_id( 'helmet_left' ); ?>"><?php _e( 'Image/Picture:','shipme' ); ?></label>
<input class="widefat" size="20" id="<?php echo $this->get_field_id( 'image_picture' ); ?>" name="<?php echo $this->get_field_name( 'image_picture' ); ?>" type="text" value="<?php echo esc_attr( $instance[ 'image_picture' ] ); ?>" />
<a href="#" id="sel_logo1<?php echo $rand1 ?>" class="button"><?php _e('Upload/Select File','shipme') ?></a> <br/>
Its good to upload a wide picture. Eg: 2000-3000px wide
</p>


                   		 <script>

			jQuery(function(jQuery) {
				jQuery(document).ready(function(){
						jQuery('#sel_logo1<?php echo $rand1 ?>').click(open_media_window1<?php echo $rand1 ?>);
					});

				function open_media_window1<?php echo $rand1 ?>() {

					tb_show('', 'media-upload.php?type=image&amp;TB_iframe=true' );
					return false;
				}

				window.send_to_editor = function(html) {
				 imgurl = jQuery('img',html).attr('src');
				 jQuery('#<?php echo $this->get_field_id( 'image_picture' ); ?>').val(imgurl) ;
				 tb_remove();

				}


			});


			</script>


		<?php
   }
}


?>
