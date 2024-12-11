<?php


function shp_show_steps_post_new($new_job_step)
{
  ?>

  <div id="wizard6" role="application" class="wizard wizard-style-2 step-equal-width  clearfix">
    <div class="steps clearfix">
      <ul role="tablist">
        <li role="tab" class="  <?php echo $new_job_step == "1" ? "first current" : "done" ?> " aria-disabled="false" aria-selected="true"><a id="wizard6-t-0" href="#wizard6-h-0" aria-controls="wizard6-p-0"><span class="current-info audible">current step: </span><span class="number">1</span>
      <span class="title"><?php echo __('Job Info','shipme') ?></span></a></li>

      <li role="tab" class="  <?php echo $new_job_step == "2" ? "first current" : "done" ?>" aria-disabled="false" aria-selected="false"><a id="wizard6-t-1" href="#wizard6-h-1" aria-controls="wizard6-p-1"><span class="number">2</span>
        <span class="title"><?php echo __('Add Extra Options','shipme') ?></span></a></li>

        <li role="tab" class="   <?php echo $new_job_step == "3" ? "first current" : "done" ?>" aria-disabled="false" aria-selected="false"><a id="wizard6-t-2" href="#wizard6-h-2" aria-controls="wizard6-p-2"><span class="number">3</span>
          <span class="title"><?php echo __('Review Job','shipme') ?></span></a></li>

          <li role="tab" class="   <?php echo $new_job_step == "4" ? "first current" : "done" ?>" aria-disabled="false" aria-selected="false"><a id="wizard6-t-3" href="#wizard6-h-3" aria-controls="wizard6-p-3"><span class="number">4</span>
            <span class="title"><?php echo __('Publish','shipme') ?></span></a></li>


        </ul></div>

          </div>


  <?php
}


 ?>
