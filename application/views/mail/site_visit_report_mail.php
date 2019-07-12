<h3>Site Visit Fixed Report</h3>

<table border="1" cellpadding="0" cellspacing="0" width="100%" id="bodyTable">
	<thead>
		<tr>
			<th>Sl.No</th>
			<th>Contact Name</th>
            <th>Date of Site Visit</th>
            <th>Project Name</th>
		</tr>
	</thead>
	<tbody>
		<?php if(count($site_visit_data)>0){
			foreach ($site_visit_data as $k=>$data) { 
                if($data['id'] != $site_visit_data[$k+1]['id']) {
                ?>
                <tr>
                	<td><?= $k+1; ?></td>
                    <td><?php echo $data['name']; ?></td>
                    <td><?php echo $data['visitDate']; ?></td>
                    <td>
                        <?php echo implode(', ', $site_visit_projects[$data['id']]);?>
                    </td>
                </tr>
                <?php 
                }                                  
            }
		} else { ?>
			<tr>
				<td colspan="3"> No entries </td>
			</tr>
		<?php } ?>
	</tbody>
</table>

<br>
<br>