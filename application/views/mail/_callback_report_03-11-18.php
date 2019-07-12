<h3>Call Back Report</h3><br>

<table border="0" cellpadding="0" cellspacing="0" height="100%" width="100%" id="bodyTable">
    <thead>
        <tr>
            <th>Sl.No</th>
            <th>Employee Id</th>
			<th>Advisor</th>
			<th>No. of callbacks Assigned</th>
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
<br>
<br>