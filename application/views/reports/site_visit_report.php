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
</head>
<body>
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
			<div class="col-sm-3 form-group"><br/>
	            <button type="submit" id="Generate" class="btn btn-success col-lg" style="width: 150px">Filter</button>
	        </div>
	        <div class="col-sm-3 form-group"><br/>
	            <button id="email_this_report" class="btn btn-default btn-block">Email this report</button>
	        </div>
	    </form>
	    <br>
	    <div class="col-md-12">
		    <?php
	    	$startDate = $this->session->userdata('report-fromDate');
			$endDate   = $this->session->userdata('report-toDate');
			if($startDate && $endDate)
				$title = '<strong>From</strong> <em>'.$startDate.'</em> <strong>To</strong> <em>'.$endDate.'</em>';
			else
				$title = '';
	    	?>
	    	<center>    		
			    <h4>Site Visit Done Report</h4>
				<p ><?= $title; ?></p>
	    	</center>	
			<table class="display" cellspacing="0" width="100%" >
					<thead>
						<tr>
							<th>Sl.No</th>
							<th>Employee Id</th>
							<th>Advisor</th>
							<th>No Of site Visit Done</th>
						</tr>
					</thead>
					<tbody>
						<?php if(count($site_visits)>0){
							$idsArry = array();							
							foreach ($site_visits as $key => $value) {
								if(!in_array($value->emp_code, $idsArry)) {
									?>
								 	<tr>
								 		<td><?php echo $key+1; ?></td>
								 		<td><?php echo $value->emp_code; ?></td>
								 		<td><?php echo $value->fullname; ?></td>
								 		<td>
								 			<a href="<?= base_url('admin/view-site-visit-data?userid='.$value->userId.'&fromDate='.$startDate.'&endDate='.$endDate.'&reportType=site-visit-done') ?>" target="_blank">
								 				<?php echo $siteVisitDoneCount[$value->emp_code]; ?>
								 			</a>
								 		</td>
								 	</tr>
									<?php
								}
								$idsArry[] =  $value->emp_code;						
							}
						} else { ?>
							<tr>
								<td colspan="5"><p class="text-center">No records found!</p>	 </td>
							</tr>
						<?php } ?>
					</tbody>
			</table>
		</div>
		<br>
		<br>
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