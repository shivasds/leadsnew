<h3>Face to Face Report</h3>

<table border="0" cellpadding="0" cellspacing="0" height="100%" width="100%" id="bodyTable">
	<thead>
		<tr>
			<th>Sl.No</th>
			<th>Employee Id</th>
			<th>Advisor</th>
			<th>Face to Face Done</th>
		</tr>
	</thead>
	<tbody>
		<?php if(count($facetofaces)>0){
			$idsArry = array();							
			foreach ($facetofaces as $key => $value) {
				if(!in_array($value->emp_code, $idsArry)) {
					?>
				 	<tr>
				 		<td><?php echo $key+1; ?></td>
				 		<td><?php echo $value->emp_code; ?></td>
				 		<td><?php echo $value->fullname; ?></td>
				 		<td><?php echo $facetofacesCount[$value->emp_code]; ?></td>
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