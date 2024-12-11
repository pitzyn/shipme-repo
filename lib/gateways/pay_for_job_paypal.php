<?php


$adaptive = get_option('shipme_enable_paypal_ad');

if($adaptive != 'yes')
{
	include 'normal-paypal-job.php';	
}
else
{
	include 'adaptive-paypal-job.php';
}


?>