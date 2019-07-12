<?php 
    defined('BASEPATH') OR exit('No direct script access allowed');
    if($this->session->userdata("user_type") == "admin")
        $this->load->view('inc/admin_header');
    else
        $this->load->view('inc/header');    
?>

<div class="container">
	<div class="page-header">
	  <h1><?php echo $heading; ?></h1>
	</div>

	<form action="<?php echo base_url()?>admin/generate_report">
		<div class="col-sm-6 form-group">
			<label for="emp_code">From:</label>
            <input type="text" class="form-control datepicker" id="fromDate" name="fromDate" placeholder="Date" required="required" autocomplete="off">
            <!-- <input type="date" class="form-control" id="fromDate" name="fromDate" placeholder="Date" required="required"> -->
            <input type="time" class="form-control" id="fromTime" name="fromTime" placeholder="Time" value="00:00" required="required">
		</div>
		<div class="col-sm-6 form-group">
			<label for="emp_code">To:</label>
            <input type="text" class="form-control datepicker" id="" name="toDate" placeholder="Date" required="required" autocomplete="off">
            <!-- <input type="date" class="form-control" id="toDate" name="toDate" placeholder="Date" required="required"> -->
            <input type="time" class="form-control" id="toTime" name="toTime" placeholder="Time" value="23:59" required="required">
		</div>
		<div class="col-sm-6 form-group radio-btn">
			<!-- <label for="emp_code">To:</label> -->
            <label for = "lead_report" class="col-xs-5">Lead Report:</label>
            <div class="col-xs-6">
                <input type="radio" class="form-control col-xs-5" id="lead_report" value="lead" name="reportType" >
            </div>
            <div class="clearfix"></div>
            <label for = "lead_assignment_report" class="col-xs-5">Lead Assignment Report:</label>
            <div class="col-xs-6">
                <input type="radio" class="form-control" id="lead_assignment_report" value="lead_assignment" name="reportType" >
            </div>
            <div class="clearfix"></div>
            <label for = "site_visit_report" class="col-xs-5">Site Visit Done report:</label>
            <div class="col-xs-6">
                <input type="radio" class="form-control" id="site_visit_report" value="site_visit" name="reportType" >
            </div>
            <div class="clearfix"></div>
            <label for = "clent_reg_report" class="col-xs-5">Client registration report:</label>
            <div class="col-xs-6">
                <input type="radio" class="form-control" id="clent_reg_report" value="clent_reg" name="reportType" >
            </div>
            <div class="clearfix"></div>
            <label for = "revenue_report" class="col-xs-5">Revenue Report:</label>
            <div class="col-xs-6">
                <input type="radio" class="form-control" id="revenue_report" value="revenue" 
                name="reportType" >
            </div>
            <div class="clearfix"></div>
            <label for = "daily_act_report" class="col-xs-5">Daily Activity Report:</label>
            <div class="col-xs-6">
                <input type="radio" class="form-control" id="daily_act_report" value="daily_act" name="reportType" >
            </div>
            <div class="clearfix"></div>
            <label for = "site_visit_fixed_report" class="col-xs-5">Site Visit Fixed Report:</label>
            <div class="col-xs-6">
                <input type="radio" class="form-control" id="site_visit_fixed_report" value="site_visit_fixed" name="reportType" >
            </div>   
            <div class="clearfix"></div>
            <label for = "face_to_face_report" class="col-xs-5">Face to Face Report:</label>
            <div class="col-xs-6">
                <input type="radio" class="form-control" id="face_to_face_report" value="face_to_face" name="reportType" >
            </div>
            <div class="clearfix"></div>
            <label for = "face_to_face_report" class="col-xs-5">Due Report:</label>
            <div class="col-xs-6">
                <input type="radio" class="form-control" id="due_report" value="due" name="reportType" >
            </div>  
		</div>
		<div class="col-sm-6 form-group">
            <button type="reset" id="save" class="btn btn-danger btn-block">Cancel</button>
        </div>
        <div class="col-sm-6 form-group">
            <button type="submit" id="Generate" class="btn btn-success btn-block">Generate</button>
        </div>
	</form>
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

        $("#due_report").click(function(){
            window.location = "<?php echo base_url()."admin/generate_report" ?>?reportType=due&fromDate=1";
        });
       
    });
</script>