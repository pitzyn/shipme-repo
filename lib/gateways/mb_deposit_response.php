<?php

if($_POST['status'] > -1)
{
		
		$c  	= $_POST['field1'];
		$c 		= explode('|',$c);
		
		$uid				= $c[0];
		$datemade 			= $c[1];		
		
		//---------------------------------------------------

		$amount = $_POST['amount'];
		
		$op = get_option('shipme_deposit_'.$uid.$datemade);
	
	
		if($op != "1")
		{
			$mc_gross = $amount;
			
			$cr = shipme_get_credits($uid);
			shipme_update_credits($uid,$mc_gross + $cr);
			
			update_option('shipme_deposit_'.$uid.$datemade, "1");
			$reason = __("Deposit through Skrill.","shipme"); 
			shipme_add_history_log('1', $reason, $mc_gross, $uid);
		
			$user = get_userdata($uid);
		
 		
		}
		
		
		 
}
	
?>