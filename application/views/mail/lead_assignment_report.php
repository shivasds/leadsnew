<h3>Lead Assignment Report</h3>

<table border="0" cellpadding="0" cellspacing="0" height="100%" width="100%" id="bodyTable">
	<thead>
		<tr>
			<th>Sl.No</th>
			<th>Advisor Name</th>
			<?php foreach ($projectCallbacks as $key => $value) {
				$name = $this->common_model->get_project_name($key);
				echo '<th>'.$name.'</th>';
			} ?>
			<th>Total Calls</th>
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
			 				echo '<td>'.$advisor[$project].'</td>';
			 			}
			 			else
			 				echo '<td>0</td>';
			 		} ?>
			 		<td><?php echo $total; ?></td>
			 	</tr>
			<?php $i++; }
		} else { ?>
			<tr>
				<td colspan="<?php echo count($projectCallbacks)+2; ?>"> No entries </td>
			</tr>
		<?php } ?>
	</tbody>
</table>

<br>
<br>