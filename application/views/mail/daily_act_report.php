<h3>Daily Activity Report</h3>

<table border="0" cellpadding="0" cellspacing="0" height="100%" width="100%" id="bodyTable">
	<thead>
		<tr>
			<th>Sl.No</th>
			<th>Advisor Name</th>
			<th>No. of callbacks</th>
			<?php foreach ($statuses as $value) {
				echo '<th>'.$value->name.'</th>';
			} ?>
		</tr>
	</thead>
	<tbody>
		<?php if(count($advisors)>0){
			$i = 1;
			foreach ($advisors as $key => $advisor) { 
				$name=$this->user_model->get_user_fullname($key); ?>
			 	<tr>
			 		<td><?php echo $i; ?></td>
			 		<td><?php echo $name; ?></td>
			 		<td><?php echo $advisor['total']; ?></td>
			 		<?php foreach ($statuses as $value) {
			 			if(isset($advisors[$value->id])){
			 				if($advisors[$value->id] != 0)
				 				echo '<td>'.$advisor[$value->id].'</td>';
				 			else
				 				echo '<td>0</td>';
			 			}
			 			else
				 			echo '<td>0</td>';
				 			
			 		} ?>
			 	</tr>
			<?php $i++; }
		} else { ?>
			<tr>
				<td colspan="<?php echo count($statuses)+3; ?>"> No entries </td>
			</tr>
		<?php } ?>
	</tbody>
</table>

<br>
<br>