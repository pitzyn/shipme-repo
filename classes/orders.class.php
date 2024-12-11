<?php


class ship_orders
{
    private $oid;
    private $escrow_obj = false;

    public function __construct($oidn = '')
    {
        $this->oid = $oidn;
    }

    public function get_order()
    {
      global $wpdb;
      $s = "select * from ".$wpdb->prefix."ship_orders where id='{$this->oid}'";
      $r = $wpdb->get_results($s);

      if(count($r) > 0) return $r[0];

      return false;

    }


    public function is_order_freelancer_delivered()
    {
        $rw = $this->get_order();
        if($rw->done_transporter == 1) return true;
        return false;
    }


    public function is_order_buyer_completed()
    {
        $rw = $this->get_order();
        if($rw->done_buyer == "1") return true;
        return false;
    }



    public function mark_freelancer_completed()
    {
        global $wpdb;

        $obj = $this->get_order();
        if($obj != false)
        {
          $uid = get_current_user_id();
          $pid = $obj->pid;

          if($obj->done_transporter == 0 and $obj->transporter == $uid)
          {

              $tm = current_time('timestamp');
              $s = "update ".$wpdb->prefix."ship_orders set done_transporter='1', order_status='1', marked_done_transporter='$tm' where id='{$this->oid}'";
              $wpdb->query($s);


              shipme_send_email_on_delivered_job_to_owner($obj->pid);

            //  ProjectTheme_send_email_on_delivered_project_to_bidder($pid, $uid);
        		///	ProjectTheme_send_email_on_delivered_project_to_owner($pid);
          }

        }

    }

    public function mark_buyer_completed()
    {
        global $wpdb;

        $obj = $this->get_order();
        if($obj != false)
        {
          $uid = get_current_user_id();
          $pid = $obj->pid;

          if($obj->done_transporter == 1 and $obj->buyer == $uid)
          {

              $tm = current_time('timestamp');
              $s = "update ".$wpdb->prefix."ship_orders set done_buyer='1', order_status='2', marked_done_buyer='$tm' where id='{$obj->id}'";
              $wpdb->query($s);

              $escrow = new shipEscrow($obj->id);
              $esObj = $escrow->escrowExistsForOrder();



              if($esObj != false)
              {
                   $escrow->markEscrowReleased($esObj->id);
              }
                shipme_send_email_on_completed_job_to_owner($obj->pid);
            //  ProjectTheme_send_email_on_delivered_project_to_bidder($pid, $uid);
        		///	ProjectTheme_send_email_on_delivered_project_to_owner($pid);
          }

        }

    }

    public function insert_escrow($args = array())
    {
      global $wpdb;
      if(count($args) == 0) return;

      $method       = $args['method'];
      $fromid       = $args['sending_user'];
      $toid         = $args['receiving_user'];
      $amount       = $args['amount'];
      $datemade     = current_time('timestamp');
      $releasedate  = 0;


      $s = "insert into ".$wpdb->prefix."ship_escrows (fromid, toid, oid, amount, datemade, releasedate, method) values('$fromid','$toid','{$this->oid}','$amount', '$datemade', '$releasedate', '$method')";
      $wpdb->query($s);


    }

    public function get_escrow_object()
    {
          if($this->escrow_obj == false) return NULL;

                    return  $this->escrow_obj;
    }

    public function has_escrow_deposited()
    {
        global $wpdb;
        $s = "select * from ".$wpdb->prefix."ship_escrows where oid='{$this->oid}'";
        $r = $wpdb->get_results($s);

        $cnt = count($r);
        if($cnt > 0) { $this->escrow_obj = $r[0]; return true; }
        return false;
    }



    public function insert_order($args = array())
    {
      if(count($args) == 0) return;
      global $wpdb;
      $tm = current_time('timestamp');

      // order status: 0 - open, 1 - freelancer delivered, 2 - buyer accepted(success), 3 - order cancelled

      $completion_date  = $args['completion_date'];
      $buyer            = $args['buyer'];
      $freelancer        = $args['freelancer'];
      $pid              = $args['pid'];
      $sts              = 0;

      $order_net_amount = $args['order_net_amount'];
      $order_total_amount = $args['order_total_amount'];

      $s1 = "insert into ".$wpdb->prefix."ship_orders (completion_date, buyer, transporter, pid, datemade, order_status, done_transporter, marked_done_transporter, done_buyer, marked_done_buyer, order_net_amount, order_total_amount)
      values('$completion_date','$buyer','$freelancer', '$pid', '$tm', '$sts' , '0', '0', '0', '0', '$order_net_amount', '$order_total_amount')";
      $wpdb->query($s1);

      $lastid = $wpdb->insert_id;
      return $lastid;

    }


    public function get_number_of_open_orders_for_buyer($uid)
    {
      global $wpdb;
      $s = "select count(id) sumx from ".$wpdb->prefix."ship_orders orders where orders.buyer='$uid' and order_status='0'";
      $r = $wpdb->get_results($s);

      $row = $r[0];
      return $row->sumx;

    }


    public function get_number_of_pending_projects_as_freelancer($uid)
    {
      global $wpdb;
      $s = "select count(id) sumx from ".$wpdb->prefix."ship_orders orders where orders.transporter='$uid' and order_status='0'";
      $r = $wpdb->get_results($s);

      $row = $r[0];
      return $row->sumx;

    }


}


 ?>
