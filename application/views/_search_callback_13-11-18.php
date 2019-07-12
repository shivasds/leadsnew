<?php 
    defined('BASEPATH') OR exit('No direct script access allowed');
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
	<form method="POST">
	<div class="col-md-4 form-group">
		<label for="emp_code">Search type:</label>
		<select  class="form-control"  id="search_type" name="type" required="required" >
			<option value="name" <?php if($this->session->userdata("type")=="name") echo 'selected' ?>>Name</option>
			<option value="contact" <?php if(($this->session->userdata("type"))=="contact") echo 'selected' ?>>Contact No</option>
			<option value="email" <?php if(($this->session->userdata("type"))=="email") echo 'selected' ?>>Email Id</option>
			<option value="project" <?php if(($this->session->userdata("type"))=="project") echo 'selected' ?>>Project</option>
		</select>
		<label></label>
	</div>
	<div class="col-sm-4 form-group">
		<label for="email">Enter Search key:</label>
		<input type="text" class="form-control" id="search_term" name="query" placeholder="Search key" value="<?php echo $this->session->userdata("query"); ?>" required>
	</div>
	<div class="col-sm-4 form-group">
		<button type="submit" id="search" style="margin-top:25px;" class="btn btn-success btn-block">Search</button>
	</div>
	</form>
</div>
<?php if ($result) { ?>
<table id="example" class="table table-striped table-bordered dt-responsive" cellspacing="0" width="100%" >
    <thead>
        <tr>
            <th>No</th>
            <th>Contact Name</th> 
            <th>Contact No</th>
            <th>Email</th>
            <th>Project</th>
            <?php if($this->session->userdata("user_type")!="user") { ?>
                <th>Lead Source</th>
                <th>Lead Id</th>
            <?php } ?>
            <th>Advisor</th>
            <?php if($this->session->userdata("user_type")!="user") { ?> 
                <th>Sub-Source</th>
            <?php } ?>
            <th>Due date</th>
            <th>Status</th>
            <th>Date Added</th>
            <th>Last Update</th>
            <th>Action</th>
        </tr>
    </thead> 
    <tbody id="main_body">
        <?php $i= 1;
        if(count($result)>0){
        foreach ($result as $data) {
            $duedate = explode(" ", $data->due_date);
            $duedate = $duedate[0]; ?>
            <tr id="row<?php echo $i ?>" <?php if(strtotime($duedate)<strtotime('today')){?> class="highlight_past" <?php }elseif(strtotime($duedate) == strtotime('today')) {?> class="highlight_now" <?php }elseif(strtotime($duedate)>strtotime('today')){ ?> class="highlight_future" <?php } ?>>
                <td><?php echo $i; ?></td>
                <td><?php echo $data->name; ?></td>
                <td><?php echo $data->contact_no1 ?></td>
                <td><?php echo $data->email1; ?></td>
                <td><?php echo $data->project_name; ?></td>
                <?php if($this->session->userdata("user_type")!="user") { ?>
                    <td><?php echo $data->lead_source_name; ?></td>
                    <td><?php echo $data->leadid; ?></td>
                <?php } ?>
                <td><?php echo $data->user_name; ?></td>
                <?php if($this->session->userdata("user_type")!="user") { ?>
                    <td><?php echo $data->broker_name; ?></td>
                <?php } ?>
                <td class="due_date"><?php echo $data->due_date; ?></td>
                <td><?php echo $data->status_name; ?></td>
                <td><?php echo $data->date_added; ?></td>
                <td><?php echo $data->last_update; ?></td>
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
                        </tr>
                    </table>
                </td>
            </tr>
        <?php $i++; } }?>
    </tbody>
</table>

<?php $this->load->view('callback_operations'); ?>

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
        $('#c_bkngMnth, #c_estMonthofInvoice').MonthPicker({
            Button: false
        });
    });
    
</script> 
<?php } ?>