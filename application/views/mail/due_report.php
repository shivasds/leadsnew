<h3>Due Report</h3><br>
<h4>Due Callbacks</h4>
<table border="0" cellpadding="0" cellspacing="0" height="100%" width="100%" id="bodyTable">
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
			 		<td><?php echo $value; ?></td>
			 	</tr>
			<?php $i++; } ?>
			<tr>
				<td colspan="2">Total</td>
				<td><?php echo $total; ?></td>
			</tr>
		<?php } else { ?>
			<tr>
				<td colspan="3"> No entries </td>
			</tr>
		<?php } ?>
        
    </tbody>
</table>
<br>
<h4>Over Due Callbacks</h4>
<table border="0" cellpadding="0" cellspacing="0" height="100%" width="100%" id="bodyTable">
	<thead>
		<tr>
			<th>Sl.No</th>
			<th>Project</th>
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
			 		<td><?php echo $value; ?></td>
			 	</tr>
			<?php $i++; } ?>
			<tr>
				<td colspan="2">Total</td>
				<td><?php echo $total; ?></td>
			</tr>
		<?php } else { ?>
			<tr>
				<td colspan="3"> No entries </td>
			</tr>
		<?php } ?>
	</tbody>
</table>

<br>
<br>