<h3>Lead Report</h3>

<table border="0" cellpadding="0" cellspacing="0" height="100%" width="100%" id="bodyTable">
    <thead>
        <tr>
            <th>Sl.No</th>
			<th>Advisor</th>
			<th>No. of callbacks Assigned</th>
        </tr>
    </thead>
    <tbody>
        <?php if(count($advisors)>0){
			$i = 1;
			$total = 0;
			foreach ($advisors as $key => $value) { 
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
<table border="0" cellpadding="0" cellspacing="0" height="100%" width="100%" id="bodyTable">
	<thead>
		<tr>
			<th>Sl.No</th>
			<th>Project</th>
			<th>No. of callbacks Assigned</th>
		</tr>
	</thead>
	<tbody>
		<?php if(count($projects)>0){
			$i = 1;
			$total = 0;
			foreach ($projects as $key => $value) { 
				$name = $this->common_model->get_project_name($key); 
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
<table border="0" cellpadding="0" cellspacing="0" height="100%" width="100%" id="bodyTable">
	<thead>
		<tr>
			<th>Sl.No</th>
			<th>Lead Source</th>
			<th>No. of callbacks Assigned</th>
		</tr>
	</thead>
	<tbody>
		<?php if(count($lead_sources)>0){
			$i = 1;
			$total = 0;
			foreach ($lead_sources as $key => $value) { 
				$name = $this->common_model->get_leadsource_name($key);
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