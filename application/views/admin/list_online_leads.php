<?php defined('BASEPATH') OR exit('No direct script access allowed'); ?>
<table id="example" class="table table-striped table-bordered dt-responsive" cellspacing="0" width="100%">
    <thead>
        <tr>
            <th>No</th>
            <th>Contact Name</th> 
            <th>Contact No</th>
            <th>Email</th>
            <th>Project</th>
            <th>Lead Id</th> 
            <th>Notes</th> 
        </tr>
    </thead>
</table>
<?php foreach ($data as $key => $value) { ?>
    <table id="example" class="table table-striped table-bordered dt-responsive" cellspacing="0" width="100%">
        <tbody>
            <tr>
                <td><?= $key+1 ?></td>
                <td><?= $value['name'] ?></td>
                <td><?= $value['phone'] ?></td>
                <td><?= $value['email'] ?></td>
                <td><?= $value['project'] ?></td>
                <td><?= $value['leadid'] ?></td>
                <td><?= $value['notes'] ?></td>
            </tr>
        </tbody>
    </table>
    <div class="row">
        <div class="col-sm-6 col-sm-offset-3">
            <div class="page-header text-center">
                <h1>Callback Assignment</h1>
            </div>
            <div class="form-group">
                <label for="email" class="control-label col-sm-3">Project*</label>
                <div class="col-sm-9">
                    <select class="form-control" name="project[]" required>
                        <?php foreach($projects as $project){ ?>
                            <option value="<?php echo $project->id; ?>"><?php echo $project->name; ?></option>
                        <?php } ?>
                    </select>
                </div>
            </div>
            <div class="form-group">
                <label for="email" class="control-label col-sm-3">Dept*</label>
                <div class="col-sm-9">
                    <select class="form-control" name="dept[]" required>
                        <?php foreach($all_department as $department){ ?>
                            <option value="<?php echo $department->id; ?>"><?php echo $department->name; ?></option>
                        <?php } ?>
                    </select>
                </div>
            </div>
            <div class="form-group">
                <label for="email" class="control-label col-sm-3">Callback type*</label>
                <div class="col-sm-9">
                    <select class="form-control" name="callback_type[]" required>
                        <?php foreach($all_callback_types as $callback_type){ ?>
                            <option value="<?php echo $callback_type->id; ?>"><?php echo $callback_type->name; ?></option>
                        <?php }?>
                    </select>
                </div>
            </div>
            <div class="form-group">
                <label for="email" class="control-label col-sm-3">Assigned to*</label>
                <div class="col-sm-9">
                    <select class="form-control" name="user[]" required>
                        <?php foreach( $all_user as $user){ 
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
                    <select class="form-control" name="broker[]" required>
                        <?php foreach( $brokers as $broker){ ?>
                            <option value="<?php echo $broker->id; ?>"><?php echo $broker->name ?></option>
                        <?php } ?>
                    </select>
                </div>
            </div>
            <div class="form-group">
                <label for="email" class="control-label col-sm-3">Status*</label>
                <div class="col-sm-9">
                    <select class="form-control" name="status[]" required>
                        <?php foreach( $statuses as $status){ ?>
                            <option value="<?php echo $status->id; ?>"><?php echo $status->name ?></option>
                        <?php } ?>
                    </select>
                </div>
            </div>
            <div class="form-group">
                <label for="email" class="control-label col-sm-3">Due Date*</label>
                <div class="col-sm-9">
                    <input type="date" id="dt" class="form-control" name="due_date[]" required />
                </div>
            </div>
            <div class="form-group">
                <label for="email" class="control-label col-sm-3">Due Time*</label>
                <div class="col-sm-9">
                    <input type="time" id="dt" class="form-control" name="due_time[]" value="00:00"/>
                </div>
            </div>   
        </div>
    </div>
<?php } ?>
<div class="row">
    <div class="col-sm-6 col-sm-offset-3">
        <div class="form-group">
            <div class="col-sm-9 col-sm-offset-3">
                <button type="submit" class="btn btn-success btn-block">Save All Data</button>
            </div>
        </div>    
    </div>
</div>
    