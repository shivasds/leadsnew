<?php 
    defined('BASEPATH') OR exit('No direct script access allowed');
    if($this->session->userdata("user_type") == "admin")
        $this->load->view('inc/admin_header');
    else
        $this->load->view('inc/header');
?>

<style type="text/css">
	.display td {
	    border: 1px solid #aaa;
	    padding: 5px
	  }
</style>
<div class="container">
	<div class="page-header">
	  <h1><?php echo $heading; ?></h1>
	</div>
	<div class="alert alert-success" style="display: none;">
		<strong>Success!</strong> Email Sent.
	</div>
	<div class="alert alert-danger" style="display: none;">
		<strong>Error!</strong> Email not Sent.
	</div>
	<form method="GET" action="<?php echo base_url()?>admin/generate_report">
		<div class="col-sm-3 form-group">
			<label for="emp_code">Dept:</label>
            <select  class="form-control"  id="dept" name="dept" required >
                <option value="">Select</option>
                <?php $all_department=$this->common_model->all_active_departments();
                foreach($all_department as $department){ ?>
                    <option value="<?php echo $department->id; ?>" <?php if($department->id==$dept) echo 'selected'; ?>><?php echo $department->name; ?></option>
                <?php }?>              
            </select>
		</div>
		<div class="col-sm-3 form-group">
			<label for="emp_code">City:</label>
            <select  class="form-control"  id="city" name="city" >
                <option value="">Select</option>
                <?php $cities= $this->common_model->all_active_cities(); 
                foreach( $cities as $c){ ?>
                    <option value="<?php echo $c->id; ?>" <?php if($c->id==$city) echo 'selected'; ?> ><?php echo $c->name ?></option>
                <?php } ?>               
            </select>
		</div>
		<div class="col-sm-3 form-group">
            <button type="submit" id="Generate" class="btn btn-success btn-block">Filter</button>
        </div>
        <div class="col-sm-3 form-group">
            <button id="email_this_report" class="btn btn-default btn-block">Email this report</button>
        </div>
    </form>
    <br>
    <div class="col-md-12">
    	<div class="col-md-4">
    		<h4> Due Calls</h4>
    		<table class="display" cellspacing="0" width="100%">
				<thead>
					<tr>
						<th>Sl.No</th>
						<th>Advisor</th>
						<th>No. of callbacks Assigned</th>
					</tr>
				</thead>
				<tbody>
					<?php if(count($due_reports)>0){
						$i = 1;
						$total = 0;
						foreach ($due_reports as $key => $value) { 
							$name = $this->user_model->get_user_fullname($key); 
							$total += $value; ?>
						 	<tr>
						 		<td><?php echo $i; ?></td>
						 		<td><?php echo $name; ?></td>
						 		<td><a href="<?php echo base_url().'view_callbacks?report='.urlencode($reportType).'&advisor='.urlencode($key).'&dept='.urlencode($dept).'&city='.urlencode($city).'&due_date='.date('Y-m-d'); ?>"><?php echo $value; ?></a></td>
						 	</tr>
						<?php $i++; } ?>
						<tr>
							<td colspan="2">Total</td>
							<td><a href="<?php echo base_url().'view_callbacks?report='.urlencode($reportType).'&dept='.urlencode($dept).'&city='.urlencode($city).'&due_date='.date('Y-m-d'); ?>"><?php echo $total; ?></a></td>
						</tr>
					<?php } else { ?>
						<tr>
							<td colspan="3"> No entries </td>
						</tr>
					<?php } ?>
				</tbody>
			</table>
    	</div>
	    	
		<div class="col-md-4">
		<h4> Over Due Calls</h4>
		<table class="display" cellspacing="0" width="100%">
			<thead>
				<tr>
					<th>Sl.No</th>
					<th>Advisor</th>
					<th>No. of callbacks Assigned</th>
				</tr>
			</thead>
			<tbody>
				<?php if(count($overdue_reports)>0){
					$i = 1;
					$total = 0;
					foreach ($overdue_reports as $key => $value) { 
						$name = $this->user_model->get_user_fullname($key); 
						$total += $value; ?>
					 	<tr>
					 		<td><?php echo $i; ?></td>
					 		<td><?php echo $name; ?></td>
					 		<td><a href="<?php echo base_url().'view_callbacks?report='.urlencode($reportType).'&advisor='.urlencode($key).'&dept='.urlencode($dept).'&city='.urlencode($city).'&due_date_to='.date('Y-m-d',strtotime("-1 days")); ?>"><?php echo $value; ?></a></td>
					 	</tr>
					<?php $i++; } ?>
					<tr>
						<td colspan="2">Total</td>
						<td><a href="<?php echo base_url().'view_callbacks?report='.urlencode($reportType).'&dept='.urlencode($dept).'&city='.urlencode($city).'&due_date_to='.date('Y-m-d',strtotime("-1 days")); ?>"><?php echo $total; ?></a></td>
					</tr>
				<?php } else { ?>
					<tr>
						<td colspan="3"> No entries </td>
					</tr>
				<?php } ?>
			</tbody>
		</table>
		</div>
    </div>
		
</div>
</body>
<script type="text/javascript">
	$("#email_this_report").click(function(e){
		e.preventDefault();
		$(".alert-success").hide();
		$(".alert-danger").hide();
		$.get("<?php echo base_url().'admin/email_report?fromDate='.urlencode($fromDate).'&toDate='.urlencode($toDate).'&city='.urlencode($city).'&dept='.urlencode($dept).'&reportType='.urlencode($reportType); ?>", function(response){
			if(response == "Success")
				$(".alert-success").show();
			else
				$(".alert-danger").show();
		});
	});
</script>