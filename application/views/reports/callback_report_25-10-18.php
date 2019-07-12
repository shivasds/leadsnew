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
	  #Generate{float: left;}
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
            <select  class="form-control"  id="dept" name="dept" >
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
		<div class="col-sm-3 form-group"><br/>
            <button type="submit" id="Generate" class="btn btn-success">Filter</button>
            <!-- <input type="submit" class="btn btn-danger" value="Reset" name="reset" /> -->
        </div>
        <div class="col-sm-3 form-group"><br/>
            <button id="email_this_report" class="btn btn-default">Email this report</button>
        </div>
    </form>
    <br>
    <div class="col-md-12">
    	<?php
    	$startDate = $this->input->get('fromDate').' '.$this->input->get('fromTime');
		$endDate   = $this->input->get('toDate').' '.$this->input->get('toTime');
		if($startDate && $endDate)
			$title = 'From '.$startDate.' To '.$endDate;
		else
			$title = '';
    	?>
		<h4 class="text-center">Callbacks Report</h4>
		<p class="text-center"><?= $title; ?></p>		
		<table class="display" cellspacing="0" width="50%" style="margin:0 auto;">
			<thead>
				<tr>
					<th>Sl.No</th>
					<th>Employee Id</th>
					<th>Advisor</th>
					<th>No. of calls</th>
				</tr>
			</thead>
			<tbody>
				<?php if($callbackData){
					$total = 0;
					foreach ($callbackData as $key => $value) {							
						$total += $value['totalCalls']; 
						?>
					 	<tr>
					 		<td><?= $key+1; ?></td>
					 		<td><?= $value['emp_code']; ?></td>
					 		<td><?= $value['userName']; ?></td>
					 		<td><?= $value['totalCalls']; ?></td>
					 	</tr>
						<?php 
					} 
					?>
					<tr>
						<td colspan="3">Total</td>
						<td><?php echo $total; ?></td>
					</tr>
				<?php } else { ?>
					<tr>
						<td colspan="4"> No entries </td>
					</tr>
				<?php } ?>
			</tbody>
		</table>
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