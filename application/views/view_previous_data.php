<table id="previous" class="table table-striped table-bordered dt-responsive " cellspacing="0" width="100%" >
    <thead>
        <tr>
            <th>No</th>
            <th>User</th> 
            <th>Callback</th>
            <th>Status</th>
            <th>Date Added</th>
        </tr>
    </thead> 
    <tbody id="main_body">
        <?php 
        if(count($result)>0){
            $i= 1;
            foreach ($result as $data) { ?>
                <tr>
                    <td><?php echo $i; ?></td>
                    <td><?php echo $data->user_name; ?></td>
                    <td><?php echo $data->current_callback ?></td>
                    <td><?php echo $data->status; ?></td>
                    <td><?php echo $data->date_added; ?></td>
                </tr>
            <?php $i++; } 
        } ?>
    </tbody>
</table>
