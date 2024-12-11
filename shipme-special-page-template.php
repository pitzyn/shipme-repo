<?php
/*	:)	*/
?>

<?php
/**********************
*	MIT license
**********************/

	get_header();


?>


<div class="ship-mainpanel">
		<div class="container">

<?php if ( have_posts() ) while ( have_posts() ) : the_post(); ?>
<?php the_content(); ?>
<?php endwhile; // end of the loop. ?>



</div></div>

<?php get_footer(); ?>
