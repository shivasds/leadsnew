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
		<div class="col-sm-2 form-group">
			<label for="emp_code">Dept:</label>
            <select  class="form-control"  id="dept" name="dept" >
                <option value="">Select</option>
                <?php $all_department=$this->common_model->all_active_departments();
                foreach($all_department as $department){ ?>
                    <option value="<?php echo $department->id; ?>" <?php if($department->id==$dept) echo 'selected'; ?>><?php echo $department->name; ?></option>
                <?php }?>              
            </select>
		</div>
		<div class="col-sm-2 form-group">
			<label for="emp_code">City:</label>
            <select  class="form-control"  id="city" name="city" >
                <option value="">Select</option>
                <?php $cities= $this->common_model->all_active_cities(); 
                foreach( $cities as $c){ ?>
                    <option value="<?php echo $c->id; ?>" <?php if($c->id==$city) echo 'selected'; ?> ><?php echo $c->name ?></option>
                <?php } ?>               
            </select>
		</div>
		<div class="col-sm-2 form-group">
			<label for="emp_code">Project:</label>
            <select  class="form-control"  id="project" name="project" required >
                <option value="">Select</option>
                <?php $all_projects=$this->common_model->all_active_projects();
                foreach($all_projects as $project){ ?>
                    <option value="<?php echo $project->id; ?>" <?php if($project->id==$dept) echo 'selected'; ?>><?php echo $project->name; ?></option>
                <?php }?>              
            </select>
		</div>
		<div class="col-sm-2 form-group">
            <button type="submit" id="Generate" class="btn btn-success btn-block" style="margin-top: 25px; margin-right: 0px;">Filter</button>
        </div>
        <div class="col-sm-3 form-group">
            <button id="email_this_report" class="btn btn-default btn-block">Email this report</button>
        </div>
    </form>
    <br>

	<table class="display" cellspacing="0" width="100%">
		<thead>
			
			<tr>
				<th>Sl.No</th>
				<th>Advisor Name</th>
				<?php foreach ($projectCallbacks as $key => $value) {
					$name = $this->common_model->get_project_name($key);
					echo '<th>'.$name.'</th>';
				}

				if($project=='')
				{
				 ?>
				
				<th>Total Calls</th>
				<?php } ?>
			</tr>
		</thead>
		<tbody>
			<?php if(count($advisors)>0){
				$i = 1;
				foreach ($advisors as $key => $advisor) { 
					$name = $this->user_model->get_user_fullname($key);?>
				 	<tr>
				 		<td><?php echo $i; ?></td>
				 		<td><?php echo $name; ?></td>
				 		<?php $total = 0;
				 		foreach ($projectCallbacks as $project => $value) {
				 			if(array_key_exists($project, $advisor)){
				 				$total += $advisor[$project];
				 				echo '<td><a href="'.base_url().'view_callbacks?report=lead_assignment&advisor='.urlencode($key).'&project='.urlencode($project).'&dept='.urlencode($dept).'&city='.urlencode($city).'&fromDate='.urlencode($fromDate).'&toDate='.urlencode($toDate).'">'.$advisor[$project].'</a></td>';
				 			}
				 			else
				 				echo '<td>0</td>';
				 			
				 		}

				 		if($project=='')
				 		{ ?>
				 		<td><?php echo '<a href="'.base_url().'view_callbacks?report=lead_assignment&advisor='.urlencode($key).'&dept='.urlencode($dept).'&city='.urlencode($city).'&fromDate='.urlencode($fromDate).'&toDate='.urlencode($toDate).'">'.$total.'</a>'; ?></td>
				 	</tr>
				<?php  } 	$i++;}
			} else { ?>
				<tr>
					<td colspan="<?php echo count($projectCallbacks)+2; ?>"> No entries </td>
				</tr>
			<?php } 
$this->session->unset_userdata('report-project');
			?>
		</tbody>
	</table>
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