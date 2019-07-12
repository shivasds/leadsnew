<h3>Site Visit Fixed Report</h3>

<table border="0" cellpadding="0" cellspacing="0" height="100%" width="100%" id="bodyTable">
	<thead>
		<tr>
			<th>Sl.No</th>
			<th>Employee Id</th>
			<th>Advisor</th>
			<th>No Of site Visit Fixed</th>
			<th>Site Visit Date</th>
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
				 		<td><?php echo count($siteVisitCount[$value->emp_code]); ?></td>
				 		<td><?php echo $value->date; ?></td>
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

<br>
<br>