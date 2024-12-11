<?php

class shipme_listing_fees
{

    private $pid; //post id

    public function __construct($pid)
    {
      $this->pid = $pid;
    }

    //==========


    public function get_fees_still_to_pay()
    {
        $pid = $this->pid;

        $featured 	         = get_post_meta($pid, 'featured', true);
        $sealed_bidding 	    = get_post_meta($pid, 'sealed_bidding', true);
        $private 	            = get_post_meta($pid, 'private', true);

        $base_fee_paid 	    = get_post_meta($pid, 'base_fee_paid', true);
        $featured_fee_paid  = get_post_meta($pid, 'featured_fee_paid', true);
        $private_fee_paid   = get_post_meta($pid, 'private_fee_paid', true);
        $sealed_fee_paid    = get_post_meta($pid, 'sealed_fee_paid', true);

        $feat_charge                = get_option('shipme_featured_fee');
        $shipme_base_fee            = get_option('shipme_base_fee');
        $shipme_sealed_bidding_fee  = get_option('shipme_sealed_bidding_fee');
        $shipme_private_job_fee     = get_option('shipme_private_job_fee');

        $amount = 0;

        if($base_fee_paid != 1 and $shipme_base_fee > 0) $amount += $shipme_base_fee;
        if($featured_fee_paid != 1 and $feat_charge > 0 and $featured == 1) $amount += $feat_charge;
        if($sealed_fee_paid != 1 and $shipme_sealed_bidding_fee > 0 and $sealed_bidding == 1) $amount += $shipme_sealed_bidding_fee;
        if($private_fee_paid != 1 and $shipme_private_job_fee > 0 and $private == 1) $amount += $shipme_base_fee;

        return $amount;

    }

    //-------------

    public function mark_things_paid()
    {
      $pid = $this->pid;
      
        $featured 	           = get_post_meta($pid, 'featured', true);
        $sealed_bidding 	    = get_post_meta($pid, 'sealed_bidding', true);
        $private 	            = get_post_meta($pid, 'private', true);

        $feat_charge                = get_option('shipme_featured_fee');
        $shipme_base_fee            = get_option('shipme_base_fee');
        $shipme_sealed_bidding_fee  = get_option('shipme_sealed_bidding_fee');
        $shipme_private_job_fee     = get_option('shipme_private_job_fee');

        if($featured == 1 and $feat_charge > 0 ) update_post_meta($pid, 'featured_fee_paid', 1);
        if($private == 1 and $shipme_private_job_fee > 0 ) update_post_meta($pid, 'private_fee_paid', 1);
        if($sealed_bidding == 1 and $shipme_sealed_bidding_fee > 0 ) update_post_meta($pid, 'sealed_fee_paid', 1);

        return true;

    }

}


 ?>
