<?php

class shipme_messages
{
  private $uid;

  public function __construct()
  {

  }

  //*************************************************************
  //
  //  function
  //
  //*************************************************************

  public function get_thread_object($thid)
  {

    global $wpdb;

    $s = "select * from ".$wpdb->prefix."ship_pm_threads where id='$thid'";
    $r = $wpdb->get_results($s);

    if(count($r) == 0) return false;
    return $r[0];
  }

  //*************************************************************
  //
  //  function
  //
  //*************************************************************

  public function get_thread_for_pid($pid, $uid1, $uid2 )
  {
      global $wpdb;

      $s = "select id from ".$wpdb->prefix."ship_pm_threads where pid='$pid' and ((uid1='$uid1' and uid2='$uid2') or (uid1='$uid2' and uid2='$uid1'))";
      $r = $wpdb->get_results($s);

      if(count($r) > 0)
      {
          $row = $r[0];
          return $row->id;
      }
      else {
        $tm = current_time('timestamp');
        $s = "insert into ".$wpdb->prefix."ship_pm_threads (pid, uid1, uid2, date_made) values('$pid','$uid1','$uid2','$tm')";
        $wpdb->query($s);

        $s = "select id from ".$wpdb->prefix."ship_pm_threads where pid='$pid' and ((uid1='$uid1' and uid2='$uid2') or (uid1='$uid2' and uid2='$uid1'))";
        $r = $wpdb->get_results($s);

        if(count($r) > 0)
        {
            $row = $r[0];
            return $row->id;
        }
      }
  }


  //******************************************
  //
  //  function
  //
  //******************************************


  public function get_unread_messages_thread($thid, $uid)
  {
    global $wpdb;
    $s = "select count(id) as suma from ".$wpdb->prefix."ship_pm where parent='$thid' and user='$uid' and rd='0'";
    $r = $wpdb->get_results($s);

    return $r[0]->suma;
  }

  //******************************************
  //
  //  function
  //
  //******************************************


  public function get_unread_messages_total($uid)
  {
    global $wpdb;
    $s = "select count(id) as suma from ".$wpdb->prefix."ship_pm where   user='$uid' and rd='0'";
    $r = $wpdb->get_results($s);

    return $r[0]->suma;
  }


  //******************************************
  //
  //  function
  //
  //******************************************

  public function send_message($sender, $receiver, $threadid, $content, $timestamp)
  {
    global $wpdb;
    $s = "select id from ".$wpdb->prefix."ship_pm where datemade='$timestamp' and initiator='$sender' and user='$receiver'";
    $r = $wpdb->get_results($s);

    if(count($r) == 0)
    {
      $subj = " ";
      $s = "insert into ".$wpdb->prefix."ship_pm (content, subject, parent, datemade, user, initiator)
      values('$content','$subj', '$threadid', '$timestamp','$receiver','$sender')";
      $wpdb->query($s);

      //----

          shipme_send_email_on_priv_mess_received($sender, $receiver);



      $current_time = current_time('timestamp');

      $wpdb->query("UPDATE ".$wpdb->prefix."ship_pm_threads
                SET messages_number = messages_number + 1, last_updated='$current_time'
                WHERE id = '".$threadid."' ");
    }


  }

  //*************************************************************
  //
  //  function
  //
  //*************************************************************

  public function get_thread_total_messages($thid)
  {

    global $wpdb;

    $s = "select count(id) counts from ".$wpdb->prefix."ship_pm where parent='$thid'";
    $r = $wpdb->get_results($s);

    return $r[0]->counts;
  }


}


 ?>
