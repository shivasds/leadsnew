<?php 
if(count($result)>0){
    foreach ($result as $data) { ?>
        <tr>
            <td><?php echo $data->id; ?></td>
            <td><?php echo $this->user_model->get_user_fullname($data->user_id); ?></td>
            <td>
                <?php 
                    echo $data->crm_calls;
                    if($data->crm_comment)
                        echo " (".$data->crm_comment.")";
                ?>
            </td>
            <td>
                <?php 
                    echo $data->f2f_meets;
                    if($data->f2f_comment)
                        echo " (".$data->f2f_comment.")";
                ?>
            </td>
            <td>
                <?php 
                    echo $data->site_visits;
                    if($data->site_comment)
                        echo " (".$data->site_comment.")";
                ?>
            </td>
            <td>
                <?php 
                    echo $data->sub_brok_appos;
                    if($data->sub_brok_comment)
                        echo " (".$data->sub_brok_comment.")";
                ?>
            </td>
            <td>
                <?php 
                    echo $data->builder_appos;
                    if($data->builder_comment)
                        echo " (".$data->builder_comment.")";
                ?>
            </td>
            <td>
                <?php 
                    echo $data->others;
                    if($data->other_comment)
                        echo " (".$data->other_comment.")";
                ?>
            </td>
            <td><?php echo $data->note; ?></td>
            <td><?php echo $data->date_added; ?></td>
        </tr>
    <?php }
} ?>