<?php

if(isset($_POST['custom']))
	{


	$cust 					= $_POST['custom'];
	$cust 					= explode("|",$cust);
	$uid					= $cust[0];
	$oid 				    = $cust[1];
	$tm 				    = $cust[2];

	$op = get_option('shipme_deposit_'.$uid.$datemade);


		if($op != "1")
		{
			$order  = new ship_orders($oid);
			$objectOrder = $order->get_order();
			$order_total_amount = $objectOrder->order_total_amount;

			$shipEscrow = new shipEscrow($oid);
			$shipEscrow->createEscrow($order_total_amount, $uid, $objectOrder->transporter, 'paypal');

		}




	}


		wp_redirect(get_permalink(get_option('shipme_my_jobs_page')));
?>
