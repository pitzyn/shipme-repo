<?php
/*
Template Name: ShipMe_HOME_SPECIAL
*/
?>

<?php


	get_header();


?>

<div class="main_wrapper">
<!-- ########## -->


<?php if ( have_posts() ) while ( have_posts() ) : the_post(); ?>
<?php the_content(); ?>
<?php endwhile; // end of the loop. ?>

<!-- ################ -->



 </div>
<?php get_footer(); ?>
