<?php

class shipme_credits
{
  private $uid;

  public function __construct($uid)
  {
    $this->uid = $uid;
  }

  //--------------

  public function remove_amount_credits($amount, $reason, $uid2 = '')
  {
      $cr = $this->get_credits();
      $cr -=  $amount;

      $this->update_credits($cr);
      $this->add_history_log(0, $reason, $amount, $uid2 = '');
  }

  //-------------

  public function add_amount_credits($amount, $reason, $uid2 = '')
  {
      $cr = $this->get_credits();
      $cr +=  $amount;

      $this->update_credits($cr);
      $this->add_history_log(1, $reason, $amount, $uid2 = '');
  }

  //------------

  public function get_credits()
  {
    	$c = get_user_meta($this->uid,'credits',true);
    	if(empty($c))
    	{
    		update_user_meta($this->uid,'credits',"0");
    		return 0;
    	}

    	return $c;
  }

  //---------

  public function update_credits($am)
  {
  	update_user_meta($this->uid,'credits',$am);
  }

  //---------

  public function add_history_log($tp, $reason, $amount, $uid2 = '')
  {
    global $wpdb;

  	if($amount != 0)
  	{
  		$tm = current_time('timestamp',0); global $wpdb;
  		$s = "insert into ".$wpdb->prefix."ship_payment_transactions (tp,reason,amount,uid,datemade,uid2)
  		values('$tp','$reason','$amount','{$this->uid}','$tm','$uid2')";
  		$wpdb->query($s);
  	}
  }


  public function add_history_log2($tp, $reason, $amount, $uid1,  $uid2 = '')
  {
    global $wpdb;

  	if($amount != 0)
  	{
  		$tm = current_time('timestamp',0); global $wpdb;
  		$s = "insert into ".$wpdb->prefix."ship_payment_transactions (tp,reason,amount,uid,datemade,uid2)
  		values('$tp','$reason','$amount','$uid1','$tm','$uid2')";
  		$wpdb->query($s);
  	}
  }

}

 ?>
