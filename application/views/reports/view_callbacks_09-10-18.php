<?php 
    defined('BASEPATH') OR exit('No direct script access allowed');
    if($this->session->userdata("user_type") == "admin")
        $this->load->view('inc/admin_header');
    else
        $this->load->view('inc/header');
?>

<style>
    @media screen and (min-width: 768px) {
        modal_
        .modal-dialog  {
            width:900px;
        }
    }
    .form-group input[type="checkbox"] {
        display: none;
    }
    .form-group input[type="checkbox"] + .btn-group > label span {
        width: 20px;
    }
    .form-group input[type="checkbox"] + .btn-group > label span:first-child {
        display: none;
    }
    .form-group input[type="checkbox"] + .btn-group > label span:last-child {
        display: inline-block;   
    }
    .form-group input[type="checkbox"]:checked + .btn-group > label span:first-child {
        display: inline-block;
    }
    .form-group input[type="checkbox"]:checked + .btn-group > label span:last-child {
        display: none;   
    }
    tr.highlight_past td.due_date{
        background-color: #cc6666 !important;
    }
    tr.highlight_now td.due_date{
        background-color: #e4b13e !important;
    }
    tr.highlight_future td.due_date{
        background-color: #65dc68 !important;
    }
    #history_table td {
        border: 1px solid #aaa;
        padding: 5px
    }
</style>
<div class="container">
  
    <div class="page-header">
        <h1><?php echo $heading; ?></h1>
    </div>
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
            <?php } 
            if($advisor) { ?>
                <div class="col-sm-4">
                    <h4>Advisor: &emsp;<?php echo $this->user_model->get_user_fullname($advisor); ?></h4>
                </div>
            <?php }
            if($project) { ?>
                <div class="col-sm-4">
                    <h4>Project: &emsp;<?php echo $this->common_model->get_project_name($project); ?></h4>
                </div>
            <?php } 
            if($lead_source) { ?>
                <div class="col-sm-4">
                    <h4>Lead Source: &emsp;<?php echo $this->common_model->get_leadsource_name($lead_source); ?></h4>
                </div>
            <?php }
            if($status) { ?>
                <div class="col-sm-4">
                    <h4>Status: &emsp;<?php echo $this->common_model->get_status_name($status); ?></h4>
                </div>
            <?php } ?>
        </div>
    </div>
    <div class="clearfix"></div><br>
    <table id="example" class="table table-striped table-bordered dt-responsive" cellspacing="0" width="100%" >
        <thead>
            <tr>
                <th>No</th>
                <?php if(($report == 'lead') || ($report == 'lead_assignment') || ($report == 'site_visit') || ($report == 'clent_reg')){ ?>
                    <th>Contact Name</th> 
                    <th>Added Date</th>
                    <th>Last Updated</th>
                    <th>Due Date</th>
                    <!-- <th>Lead Source</th> -->
                    <?php if ($report == 'lead_assignment') { ?>
                        <th>Sub Broker</th>
                    <?php } ?>
                    <th>Status</th> 
                    <?php if ($report == 'lead_assignment') { ?>
                        <th>Advisor</th>
                    <?php } ?>
                    <th>Project</th> 
                    <th>Comment</th>
                <?php }else {?>
                    <th>Contact Name</th> 
                    <th>Contact No</th>
                    <th>Email</th>
                    <th>Project</th>
                    <!-- <th>Lead Source</th> -->
                    <th>Lead Id</th> 
                    <th>Advisor</th> 
                    <!-- <th>Sub-Source</th> -->
                    <th>Due date</th>
                    <th>Status</th>
                    <th>Date Added</th>
                    <th>Last Update</th>
                <?php } ?>
                <?php if($access == 'read_write'){ ?>
                    <th>Action</th>
                <?php } ?>
            </tr>
        </thead> 

        <tbody id="main_body">
            <?php $i= 1;
            if(count($result)>0){
            foreach ($result as $data) {
                $duedate = explode(" ", $data->due_date);
                $duedate = $duedate[0]; ?>
                <tr id="row<?php echo $data->id; ?>" <?php if(strtotime($duedate)<strtotime('today')){?> class="highlight_past" <?php }elseif(strtotime($duedate) == strtotime('today')) {?> class="highlight_now" <?php }elseif(strtotime($duedate)>strtotime('today')){ ?> class="highlight_future" <?php } ?>>
                    <td><?php echo $i; ?></td>
                    <?php if(($report == 'lead') || ($report == 'lead_assignment') || ($report == 'site_visit') || ($report == 'clent_reg')){ ?>
                        <td><?php echo $data->name; ?></td>
                        <td><?php echo $data->date_added ?></td>
                        <td><?php echo $data->last_update; ?></td>
                        <td class="due_date"><?php echo $data->due_date; ?></td>
                        <!-- <td><?php echo $data->lead_source_name; ?></td> -->
                        <?php if ($report == 'lead_assignment') { ?>
                            <td><?php echo $data->broker_name; ?></td>
                        <?php } ?>
                        <td><?php echo $data->status_name; ?></td>
                        <?php if ($report == 'lead_assignment') { ?>
                            <td><?php echo $data->user_name; ?></td>
                        <?php } ?>
                        <td><?php echo $data->project_name; ?></td>
                        <td><?php echo $data->notes; ?></td>
                    <?php }else {?>
                        <td><?php echo $data->name; ?></td>
                        <td><?php echo $data->contact_no1 ?></td>
                        <td><?php echo $data->email1; ?></td>
                        <td><?php echo $data->project_name; ?></td>
                        <!-- <td><?php echo $data->lead_source_name; ?></td> -->
                        <td><?php echo $data->leadid; ?></td>
                        <td><?php echo $data->user_name; ?></td>
                        <!-- <td><?php echo $data->broker_name; ?></td> -->
                        <td class="due_date"><?php echo $data->due_date; ?></td>
                        <td><?php echo $data->status_name; ?></td>
                        <td><?php echo $data->date_added; ?></td>
                        <td><?php echo $data->last_update; ?></td>
                    <?php } ?>
                    <?php if($access == 'read_write'){ ?>
                        <td>
                            <table>
                                <tr>
                                    <td>
                                        <a onclick="edit('<?php echo $data->id; ?>')" data-toggle="modal" data-target="#modal_edit">
                                            <i class="fa fa-home fa-2x"  title="Detail" style="color:#ff1122; font-size:21px;padding-right:7px;" aria-hidden="true"></i>
                                        </a>
                                    </td>
                                    <td>
                                        <i class="fa fa-keyboard-o fa-2x" onclick="abc()" title="Notes" style="color:#ff1122; font-size:21px;padding-right:7px;" aria-hidden="true"></i>
                                    </td>
                                    <?php if($data->important) {?>
                                        <td>
                                            <i class="fa fa-exclamation fa-2x" onclick="remove_important('<?php echo $data->id; ?>')" title="Mark Not Important" style="color:#ff1122; font-size:21px;padding-right:7px;" aria-hidden="true"></i>
                                        </td>
                                    <?php } ?>
                                </tr>
                            </table>
                        </td>
                    <?php } ?>
                </tr>
            <?php $i++; } }?>
        </tbody>
    </table>
</div>

<form style="display: none;" method="POST" id="repost">
    <input type="hidden" name="access" value="<?php echo isset($access)?$access:''; ?>">
    <input type="hidden" name="cb_ids" value="<?php echo isset($cb_ids)?$cb_ids:''; ?>">
    <input type="hidden" name="dept" value="<?php echo isset($dept)?$dept:''; ?>">
    <input type="hidden" name="fromDate" value="<?php echo isset($fromDate)?$fromDate:''; ?>">
    <input type="hidden" name="toDate" value="<?php echo isset($toDate)?$toDate:''; ?>">
    <input type="hidden" name="advisor" value="<?php echo isset($advisor)?$advisor:''; ?>">
    <input type="hidden" name="project" value="<?php echo isset($project)?$project:''; ?>">
    <input type="hidden" name="lead_source" value="<?php echo isset($lead_source)?$lead_source:''; ?>">
    <input type="hidden" name="status" value="<?php echo isset($status)?$status:''; ?>">
    <input type="hidden" name="due_date" value="<?php echo isset($due_date)?$due_date:''; ?>">
    <input type="hidden" name="due_date_to" value="<?php echo isset($due_date_to)?$due_date_to:''; ?>">
    <input type="hidden" name="due_date_from" value="<?php echo isset($due_date_from)?$due_date_from:''; ?>">
    <input type="hidden" name="important" value="<?php echo isset($important)?$important:''; ?>">
    <button type="submit"></button>
</form>

<?php if($access == 'read_write') $this->load->view('callback_operations'); ?>

<script type="text/javascript">

    $(document).ready(function() {
        $('#example').DataTable();
        if (!Modernizr.inputtypes.date) {
            // If not native HTML5 support, fallback to jQuery datePicker
            $('input[type=date]').datepicker({
                // Consistent format with the HTML5 picker
                    dateFormat : 'dd/mm/yy'
                }
            );
        }
        if (!Modernizr.inputtypes.time) {
            // If not native HTML5 support, fallback to jQuery timepicker
            $('input[type=time]').timepicker({ 'timeFormat': 'H:i' });
        }
    });

</script> 
      
      
</body>

</html>