<?php
//**********************************************************************
//
//    handles all escrow events/types
//    since v.7.0 of auction theme
//
//**********************************************************************

class shipEscrow
{

  private   $oid;
  private   $isCredits;
  private   $escrowObject;

  function __construct($oid = '') {

    $this->oid = $oid;

      }

      function setIsCredits()
      {
          $this->isCredits = true;
      }


      function createEscrow($amount, $fromuser, $touser, $me)
      {
          global $wpdb;
          $tm = current_time('timestamp');

          if($this->escrowExists($amount, $fromuser, $touser)) return;


          // if its paid by credits
          if($this->isCredits == true)
          {
              $shipme_credits = new shipme_credits($fromuser);
              $cr = $shipme_credits->get_credits(); $cr = $cr - $amount;
              $shipme_credits->update_credits($cr);
          }


          $s = "insert into ".$wpdb->prefix."ship_escrows (fromid,toid,oid,amount,datemade, method)
          values('$fromuser','$touser','{$this->oid}','$amount','$tm', '$me')";

          $wpdb->query($s);

      }

      public function markEscrowReleased($id)
      {
          global $wpdb; $tm = time();

          if($this->escrowObject->released == 0)
          {

              $s = "update ".$wpdb->prefix."ship_escrows set released='1', releasedate='$tm' where id='{$id}'";
              $wpdb->query($s);


              $shipme_credits = new shipme_credits($this->escrowObject->toid);

              $cr = $shipme_credits->get_credits(); $cr = $cr + $this->escrowObject->amount;
              $shipme_credits->update_credits($cr);

              $reason = sprintf(__('Release of escrow for order #%s','shipme'),  $this->escrowObject->oid) ;
              $shipme_credits->add_history_log(1, $reason, $this->escrowObject->amount, $this->escrowObject->fromid);

              //-------------- take commission - ----

              $shipme_fee_after_paid = get_option('shipme_fee_after_paid');
              if(!empty($shipme_fee_after_paid))
              {
                if(is_numeric($shipme_fee_after_paid))
                {
                    $fee = round( 0.01 *$shipme_fee_after_paid * $this->escrowObject->amount ,2);
                    $cr = $shipme_credits->get_credits();

                    $cr = $cr - $fee;
                    $shipme_credits->update_credits($cr);
                    $shipme_credits->add_history_log(0, sprintf(__('Commission fee for order #%s','shipme'),  $this->escrowObject->oid ), $fee, $this->escrowObject->toid);

                }

              }



              $shipme_credits->add_history_log2(0, $reason, $this->escrowObject->amount, $this->escrowObject->fromid, $this->escrowObject->toid);

          }

      }

      public function escrowExistsForOrder()
      {
        global $wpdb;

        $s = "select * from ".$wpdb->prefix."ship_escrows where oid='{$this->oid}'";
        $r = $wpdb->get_results($s);


        if(count($r) > 0) { $this->escrowObject = $r[0]; return $r[0]; }
        return false;
      }

      public function ownerHasEscrows($uid)
      {
        global $wpdb;
        $s = "select * from ".$wpdb->prefix."ship_escrows where released='0' AND fromid='$uid'";
        $r = $wpdb->get_results($s);

        if(count($r) > 0) return $r;
        return false;
      }


      public function userHasEscrowsPending($uid)
      {
        global $wpdb;
        $s = "select * from ".$wpdb->prefix."ship_escrows where released='0' AND toid='$uid'";
        $r = $wpdb->get_results($s);

        if(count($r) > 0) return $r;
        return false;
      }




      private function escrowExists($amount, $fromuser, $touser)
      {
          return false;
      }


      public function isEscrowCreated()
      {
        global $wpdb;
        $s = "select id from ".$wpdb->prefix."ship_escrows where oid='{$this->oid}'";
        $r = $wpdb->get_results($s);

        if(count($r) > 0) return true;

          return false;
      }
}


 ?>
