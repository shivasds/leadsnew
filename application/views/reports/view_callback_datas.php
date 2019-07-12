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
            <div class="col-sm-4">
                <h4>From Date: &emsp;<?php echo $fromDate; ?></h4>
            </div>
            <div class="col-sm-4">
                <h4>To Date: &emsp;<?php echo $toDate; ?></h4>
            </div>
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
                <th>Agent Name</th>
                <th>Contact</th>
                <th>Project</th>
                <th>Call Date</th>
                <th>Status</th>
                <th>Note</th>
            </tr>
        </thead> 

        <tbody id="main_body">
            <?php $i= 1;
            if(count($result)>0){
            foreach ($result as $data) { ?>
                <tr id="row<?php echo $i ?>" >
                    <td><?php echo $i; ?></td>
                    <td><?php echo $data->user_name; ?></td>
                    <td><?php echo $data->contact_no1; if($data->contact_no2) echo ", ".$data->contact_no2; ?></td>
                    <td><?php echo $data->project_name; ?></td>
                    <td><?php echo $data->date_added; ?></td>
                    <td><?php echo $data->status_name; ?></td>
                    <td><?php echo $data->current_callback; ?></td>
                </tr>
            <?php $i++; } }?>
        </tbody>
    </table>
</div>

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