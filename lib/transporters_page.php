<?php

function shipme_transporters_page()
{

	ob_start();

	global $current_user;
	get_currentuserinfo();
	$uid = $current_user->ID;



?>
<div class="container_ship_ttl_wrap">
    <div class="container_ship_ttl">
        <div class="my-page-title col-xs-12 col-sm-12 col-lg-12">
            <?php the_title() ?>
        </div>

        <?php

            if(function_exists('bcn_display'))
            {
                echo '<div class="my_box3 no_padding  breadcrumb-wrap col-xs-12 col-sm-12 col-lg-12"><div class="padd10a">';
                bcn_display();
                echo '</div></div>';
            }
        ?>

    </div>
</div>

<div class="container_ship_no_bk">
	<ul class="virtual_sidebar">

			<li class="widget-container widget_text">
                <div class="my-only-widget-content">


						<?php


			$pg = $_GET['pg'];
			if(empty($pg)) $pg = 1;

			$nrRes = 1;

			//------------------

			$offset = ($pg-1)*$nrRes;

			//------------------

						if(isset($_GET['username']))
				$args['search'] = "*".trim($_GET['username'])."*";


			// prepare arguments
			$args['orderby']  = 'display_name';
			$arr_aray = array();



			if(!empty($_GET['rating_over']))
			{
				$arr_sbg = 	array(
						// uses compare like WP_Query
						'key' => 'cool_user_rating',
						'value' => $_GET['rating_over'],
						'compare' => '>'
						);

				array_push(	$arr_aray, 	$arr_sbg);
			}




				array_push(	$arr_aray, 	$arr_sbg);


			//-----------------------------------------------

			$args['meta_query']  	= $arr_aray;
			$args['number'] 		= $nrRes;
			$args['offset'] 		= $offset;
			$args['role'] 				= 'transporter';
			$args['count_total'] 	= true;

			//-----------------------------------------------

			$wp_user_query = new WP_User_Query($args);
			// Get the results
			$ttl = $wp_user_query->total_users;
			$nrPages = ceil($ttl / $nrRes);

			$authors = $wp_user_query->get_results();

			// Check for results
			if (!empty($authors))
			{


				foreach ($authors as $author)
				{
					// get all the user's data

					shipme_get_user_table_row($author->ID);
				}


				echo '<div class="div_class_div">';

				$totalPages = $nrPages;
				$my_page = $pg;
				$page = $pg;

				$batch = 10;
				$nrpostsPage = $nrRes;
				$end = $batch * $nrpostsPage;

				if ($end > $pagess) {
					$end = $pagess;
				}
				$start = $end - $nrpostsPage + 1;

				if($start < 1) $start = 1;

				$links = '';

				$raport = ceil($my_page/$batch) - 1; if ($raport < 0) $raport = 0;

				$start 		= $raport * $batch + 1;
				$end		= $start + $batch - 1;
				$end_me 	= $end + 1;
				$start_me 	= $start - 1;

				if($end > $totalPages) $end = $totalPages;
				if($end_me > $totalPages) $end_me = $totalPages;

				if($start_me <= 0) $start_me = 1;


				$previous_pg = $page - 1;
				if($previous_pg <= 0) $previous_pg = 1;

				$next_pg = $page + 1;
				if($next_pg > $totalPages) $next_pg = 1;




				if($my_page > 1)
				{
					echo '<a href="'.shipme_provider_search_link() .'pg='.$previous_pg.'" class="bighi"><< '.__('Previous','shipme').'</a>';
					echo '<a href="'.shipme_provider_search_link() .'pg='.$start_me.'" class="bighi"><<</a>';
				}

					for($i=$start;$i<=$end;$i++)
					{
						if($i == $pg)
						echo '<a href="#" class="bighi" id="activees">'.$i.'</a>';
						else
						echo '<a href="'.shipme_provider_search_link() .'pg='.$i.'" class="bighi">'.$i.'</a>';
					}

				if($totalPages > $my_page)
				echo '<a href="'.shipme_provider_search_link() .'pg='.$end_me.'" class="bighi">>></a>';

				if($page < $totalPages)
				echo '<a href="'.shipme_provider_search_link() .'pg='.$next_pg.'" class="bighi">'.__('Next','shipme').' >></a>';


				echo '</div>';

			} else {
				echo __('No service providers found for this query.', 'shipme' );
			}



?>



            </div>
            </li></ul>
</div>


<?php



	$output = ob_get_contents();
	ob_end_clean();
	return $output;



}


?>
