<?php 
    defined('BASEPATH') OR exit('No direct script access allowed');
    $this->load->view('inc/admin_header'); 
?>

<style>
    @media screen and (min-width: 768px) {
        modal_
        .modal-dialog  {
            width:900px;
        }
    }
    .form-group input[type="checkbox"] {
        display: none;
    }
    .form-group input[type="checkbox"] + .btn-group > label span {
        width: 20px;
    }
    .form-group input[type="checkbox"] + .btn-group > label span:first-child {
        display: none;
    }
    .form-group input[type="checkbox"] + .btn-group > label span:last-child {
        display: inline-block;   
    }
    .form-group input[type="checkbox"]:checked + .btn-group > label span:first-child {
        display: inline-block;
    }
    .form-group input[type="checkbox"]:checked + .btn-group > label span:last-child {
        display: none;   
    }
</style>
<div class="container">
    <br>
    <br>
    <table id="example" class="table table-striped table-bordered dt-responsive" cellspacing="0" width="100%" >
        <thead>
            <tr>
                <th>No</th>
                <th>Contact Name</th> 
                <th>Contact No 1</th>
                <th>Contact No 2</th>
                <th>Email 1</th>
                <th>Email 2</th>
                <th>Lead Id</th> 
                <th>Notes</th> 
            </tr>
        </thead> 
        <tbody id="main_body">
            <?php $i= 1;
            if(count($callbacks)>0){
            foreach ($callbacks as $data) { ?>
                <tr>
                    <td><?php echo $i; ?></td>
                    <td><?php echo $data['name']; ?></td>
                    <td><?php echo $data['contact_no1'] ?></td>
                    <td><?php echo $data['contact_no2']; ?></td>
                    <td><?php echo $data['email1']; ?></td>
                    <td><?php echo $data['email2']; ?></td>
                    <td><?php echo $data['leadid']; ?></td>
                    <td><?php echo $data['notes']; ?></td>
                </tr>
            <?php $i++; } }?>
        </tbody>
    </table>
    <div class="row">
        <div class="col-sm-6 col-sm-offset-3">
            <div class="page-header text-center">
                <h1>Default Callback Assignment</h1>
            </div>
            <form class="form-horizontal" method="post" action="<?php echo base_url()?>admin/save_bulk_upload_callbacks">
                <input type="hidden" name="callbacks" value="<?php echo htmlspecialchars(json_encode($callbacks)); ?>">
                <div class="form-group">
                    <label for="email" class="control-label col-sm-3">Dept*</label>
                    <div class="col-sm-9">
                        <select type="email" class="form-control" name="dept" required>
                            <?php $all_department=$this->common_model->all_active_departments();
                            foreach($all_department as $department){ ?>
                                <option value="<?php echo $department->id; ?>"><?php echo $department->name; ?></option>
                            <?php }?>
                        </select>
                    </div>
                </div>
                <div class="form-group">
                    <label for="email" class="control-label col-sm-3">Project*</label>
                    <div class="col-sm-9">
                        <select type="email" class="form-control" name="project" required>
                            <?php $projects= $this->common_model->all_active_projects(); 
                            foreach( $projects as $project){ ?>
                                <option value="<?php echo $project->id ?>"><?php echo $project->name ?></option>
                            <?php }?> 
                        </select>
                    </div>
                </div>
                <div class="form-group">
                    <label for="email" class="control-label col-sm-3">Callback type*</label>
                    <div class="col-sm-9">
                        <select type="email" class="form-control" name="callback_type" required>
                            <?php $all_callback_types=$this->common_model->all_active_callback_types();
                            foreach($all_callback_types as $callback_type){ ?>
                                <option value="<?php echo $callback_type->id; ?>"><?php echo $callback_type->name; ?></option>
                            <?php }?>
                        </select>
                    </div>
                </div>
                <div class="form-group">
                    <label for="email" class="control-label col-sm-3">Lead Source*</label>
                    <div class="col-sm-9">
                        <select type="email" class="form-control" name="lead_source" required>
                            <?php $lead_source= $this->common_model->all_active_lead_sources(); 
                            foreach( $lead_source as $source){ ?>
                                <option value="<?php echo $source->id ?>"><?php echo $source->name ?></option>
                            <?php } ?>
                        </select>
                    </div>
                </div>
                <div class="form-group">
                    <label for="email" class="control-label col-sm-3">Assigned to*</label>
                    <div class="col-sm-9">
                        <select type="email" class="form-control" name="user" required>
                            <?php $all_user= $this->user_model->all_users("type in (1,2,3,4,6)"); 
                            foreach( $all_user as $user){ 
                                switch ($user->type) {
                                    case '1':
                                        $role = "User";
                                        break;

                                    case '2':
                                        $role = "Manager";
                                        break;

                                    case '3':
                                        $role = "VP";
                                        break;
                                    
                                    case '4':
                                        $role = "Director";
                                        break;
                                    case '6':
                                        $role = "City Head";
                                        break;
                                }
                                ?>
                                <option value="<?php echo $user->id ?>"><?php echo $user->first_name." ".$user->last_name." ($role)"; ?></option>
                            <?php } ?>
                        </select>
                    </div>
                </div>
                <div class="form-group">
                    <label for="email" class="control-label col-sm-3">Broker*</label>
                    <div class="col-sm-9">
                        <select type="email" class="form-control" name="broker" required>
                            <?php $brokers= $this->common_model->all_active_brokers(); 
                            foreach( $brokers as $broker){ ?>
                                <option value="<?php echo $broker->id; ?>"><?php echo $broker->name ?></option>
                            <?php } ?>
                        </select>
                    </div>
                </div>
                <div class="form-group">
                    <label for="email" class="control-label col-sm-3">Status*</label>
                    <div class="col-sm-9">
                        <select type="email" class="form-control" name="status" required>
                            <?php $statuses= $this->common_model->all_active_statuses(); 
                            foreach( $statuses as $status){ ?>
                                <option value="<?php echo $status->id; ?>"><?php echo $status->name ?></option>
                            <?php } ?>
                        </select>
                    </div>
                </div>
                <div class="form-group">
                    <label for="email" class="control-label col-sm-3">Due Date*</label>
                    <div class="col-sm-9">
                        <input type="date" id="dt" class="form-control" name="due_date" required />
                    </div>
                </div>
                <div class="form-group">
                    <label for="email" class="control-label col-sm-3">Due Time*</label>
                    <div class="col-sm-9">
                        <input type="time" id="dt" class="form-control" name="due_time" value="00:00"/>
                    </div>
                </div>
                <div class="form-group">
                    <div class="col-sm-9 col-sm-offset-3">
                        <button type="submit" class="btn btn-success btn-block">Save All Data</button>
                    </div>
                </div>
            </form>        
        </div>
    </div>
</div>