<h3>Revenue Report</h3>

<table border="0" cellpadding="0" cellspacing="0" height="100%" width="100%" id="bodyTable">
	<thead>
		<tr>
			<th>Sl.No</th>
			<th>Booking Name</th>
			<th>User/Manager Name</th>
			<th>Project Name</th>
			<th>Lead Id</th>
			<th>Added Date</th>
			<th>Booking Month</th>
			<th>Commission</th>
			<th>Gross Revenue</th>
			<th>Cashback</th>
			<th>Sub broker Amount</th>
			<th>Net Revenue</th>
		</tr>
	</thead>
	<tbody>
		<?php if(count($revenue_datas)>0){
			$i = 1;
			$total_gr = 0;
			$total_cb = 0;
			$total_sba = 0;
			$total_nr = 0;
			foreach ($revenue_datas as $key => $value) { 
				$project_name=$this->common_model->get_project_name($value->project_id);
				$user_name=$this->user_model->get_user_fullname($value->user_id);
				$total_gr += $value->gross_revenue;
				$total_cb += $value->cash_back;
				$total_sba += $value->sub_broker_amo;
				$total_nr += $value->net_revenue;
				?> 
			 	<tr class="revenue_row" data-id="<?php echo $value->callback_id; ?>">
			 		<td><?php echo $i; ?></td>
			 		<td><?php echo $value->booking; ?></td>
			 		<td><?php echo $user_name; ?></td>
			 		<td><?php echo $project_name; ?></td>
			 		<td><?php echo $value->leadid; ?></td>
			 		<td><?php echo $value->date_added; ?></td>
			 		<td><?php echo $value->booking_month; ?></td>
			 		<td><?php echo $value->commission; ?></td>
			 		<td><?php echo $value->gross_revenue; ?></td>
			 		<td><?php echo $value->cash_back; ?></td>
			 		<td><?php echo $value->sub_broker_amo; ?></td>
			 		<td><?php echo $value->net_revenue; ?></td>
			 		
			 	</tr>
			<?php $i++; } ?>
			<tr>
				<td colspan="8">Total</td>
				<td><?php echo $total_gr; ?></td>
				<td><?php echo $total_cb; ?></td>
				<td><?php echo $total_sba; ?></td>
				<td><?php echo $total_nr; ?></td>
			</tr>
		<?php } else { ?>
			<tr>
				<td colspan="12"> No entries </td>
			</tr>
		<?php } ?>
		
	</tbody>
</table>

<br>
<br>