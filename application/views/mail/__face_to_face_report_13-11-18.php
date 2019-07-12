<h3>Face to Face Report</h3>

<table border="0" cellpadding="0" cellspacing="0" height="100%" width="100%" id="bodyTable">
	<thead>
		<tr>
			<th>Sl.No</th>
			<th>User Name</th>
			<th>No Of site Visit Done</th>
			<th>Project name</th>
		</tr>
	</thead>
	<tbody>
		<?php if(count($advisors)>0){
			$i = 1;
			foreach ($advisors as $key => $value) { 
				$name = $this->user_model->get_user_fullname($key);
				$project = $this->common_model->get_project_name($value['project']); ?>
			 	<tr>
			 		<td><?php echo $i; ?></td>
			 		<td><?php echo $name; ?></td>
			 		<td><?php echo $value['count']; ?></td>
			 		<td><?php echo $project; ?></td>
			 	</tr>
			<?php $i++; }
		} else { ?>
			<tr>
				<td colspan="3"> No entries </td>
			</tr>
		<?php } ?>
	</tbody>
</table>