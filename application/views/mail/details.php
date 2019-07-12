<div class="row">
    <div class="col-sm-12 nopaddin">
        <?php if($fromDate){ ?>
            <div class="col-sm-4">
                <h4>From Date: &emsp;<?php echo $fromDate; ?></h4>
            </div>
        <?php } ?>
        <?php if($toDate){ ?>
            <div class="col-sm-4">
                <h4>To Date: &emsp;<?php echo $toDate; ?></h4>
            </div>
        <?php } ?>
        <?php if($dept) { ?>
            <div class="col-sm-4">
                <h4>Department: &emsp;<?php echo $this->common_model->get_department_name($dept); ?></h4>
            </div>
        <?php }
        if($city) { ?>
            <div class="col-sm-4">
                <h4>City: &emsp;<?php echo $this->common_model->get_city_name($city); ?></h4>
            </div>
        <?php } ?>
    </div>
</div>
<div class="clearfix"></div><br>