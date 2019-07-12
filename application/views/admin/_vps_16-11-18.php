<?php 
    defined('BASEPATH') OR exit('No direct script access allowed');
    $this->load->view('inc/admin_header'); 
?>

<div class="container">
    <div class="page-header">
        <h1><?php echo $heading; ?></h1>
    </div>
    <form name="save_seller_form" id="save_seller_form" method="POST" enctype="multipart/form-data">
        <div class="col-sm-3 form-group">
            <label for="director">Enter First Name:</label>
            <input type="text" class="form-control" id="first_name" name="first_name" placeholder="Enter First Name" required>
        </div>

        <div class="col-sm-3 form-group">
            <label for="director">Enter Last Name:</label>
            <input type="text" class="form-control" id="last_name" name="last_name" placeholder="Enter Last Name" >
        </div>

        <div class="col-sm-3 form-group">
            <label for="email">Enter Email:</label>
            <input type="email" class="form-control" id="email" name="email" placeholder="Enter Email" required>
        </div>

        <div class="col-sm-3 form-group">
            <label for="emp_code">Enter Emp code:</label>
            <input type="text" class="form-control" onblur="code_check(this.value)" id="emp_code" name="emp_code" placeholder="Enter Employee Id" required>
        </div>

        <div class="col-md-3 form-group">
            <label for="emp_code">Deprtment:</label>
            <select  class="form-control"  id="user_type" name="department" required="required" >
                <option value="">Select</option>
                <?php $all_department=$this->common_model->all_active_departments();
                foreach($all_department as $department){ ?>
                    <option value="<?php echo $department->id; ?>"><?php echo $department->name; ?></option>
                <?php }?>
            </select>
        </div>

        <div class="col-md-3 form-group">
            <label for="emp_code">City:</label>
            <select  class="form-control"  id="user_type" name="city" required="required" >
                <option value="">Select</option>
                <?php $all_city=$this->common_model->all_active_cities();
                foreach($all_city as $city){ ?>
                    <option value="<?php echo $city->id; ?>"><?php echo $city->name; ?></option>
                <?php }?>
            </select>
        </div>

        <div class="col-md-6 form-group">
            <label for="emp_code">Director:</label>
            <select  class="form-control"  id="director" name="director" required="required" >
                <option value="">Select</option>
                <?php $all_director=$this->user_model->all_users("(type=4 AND active=1)");
                foreach($all_director as $director){ ?>
                    <option value="<?php echo $director->id; ?>"><?php echo $director->first_name." ".$director->last_name; ?></option>
                <?php }?>
            </select>
        </div>

        <div class="col-sm-12 form-group">
            <button type="submit" style="margin-top:25px;" id="add_vp" class="btn btn-success btn-block" disabled>Add VP</button>
        </div>
    </form>

    <table id="example" class="table table-striped table-bordered dt-responsive" cellspacing="0" width="100%">
        <thead>
            <tr>
                <th>Id</th>
                <th>VP Name</th>
                <th>VP Email</th>
                <th>City</th>
                <th>Department</th>
                <th>Emp Code</th>
                <th>Reports to</th>
                <th>Date Added</th>
                <th>Status</th>
                <th>Edit</th>
                <th>Change Password</th> 
                <th>Privilege</th>
            </tr>
        </thead> 
        <tbody>
            <?php if(isset($all_vps) && $all_vps){
                foreach($all_vps as $vp){?>
                    <tr>
                        <td><?php echo $vp->id; ?></td>
                        <td><?php echo $vp->first_name." ".$vp->last_name; ?></td>
                        <td><?php echo $vp->email; ?></td>
                        <td><?php echo $vp->city_name; ?></td>
                        <td><?php echo $vp->department_name; ?></td>
                        <td><?php echo $vp->emp_code; ?></td>
                        <td><?php echo $vp->reports_to; ?></td>
                        <td><?php echo $vp->date_added; ?></td>
                        <td align="middle"><button type="button" id="b1<?php echo $vp->id; ?>" class="btn <?php echo $vp->active?'btn-info':'btn-danger'; ?>" onclick="change_state(<?php echo $vp->id; ?>)"><span id="stateus_sp_<?php echo $vp->id; ?>"><?php echo $vp->active?'Active':'Inactive'; ?></span></button></td>
                        <td align="middle"><button type="button" class="btn btn-info" onclick="edit_user(<?php echo $vp->id; ?>)" data-toggle="modal" data-target="#modal_edit">Edit</button></td>
                        <td align="middle"><button type="button" class="btn btn-info" onclick="reset_password(<?php echo $vp->id; ?>)">Reset Password</button></td>
                        <td align="middle">
                            <button type="button" class="btn btn-info" onclick="permissionModal(<?php echo $vp->id; ?>)" data-toggle="modal" data-target="#modalPermission">Permission</button>
                        </td>
                    </tr>
                <?php }
            } ?>
        </tbody>
    </table>
</div>

<!-- Modal -->
<div class="modal fade" id="modal_edit" role="dialog">
    <div class="modal-dialog">
        <!-- Modal content-->
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal">&times;</button>
                <h4 class="modal-title">Edit User</h4>
                <div class="modal-body">
                    <input type="hidden" id="hid" name="hid">
                    
                    <div class="col-sm-6 form-group">
                        <label for="emp_code">Employee Code:</label>
                        <input type="text" class="form-control" id="m_emp_code" name="emp_code" placeholder="Employee Code" disabled="disabled">
                    </div>

                    <div class="col-sm-6 form-group">
                        <label for="emp_code">First name:</label>
                        <input type="text" class="form-control" id="m_first_name" name="m_first_name" placeholder="First name">
                    </div>

                    <div class="col-sm-6 form-group">
                        <label for="emp_code">Last name:</label>
                        <input type="text" class="form-control" id="m_last_name" name="m_last_name" placeholder="Last name">
                    </div>

                    <div class="col-sm-6 form-group">
                        <label for="emp_code">Email-id:</label>
                        <input type="text" class="form-control" id="m_email" name="m_email_id" placeholder="Email id">
                    </div>

                    <div class="col-md-6 form-group">
                        <label for="emp_code">Director:</label>
                        <select  class="form-control"  id="m_director" name="manager" required="required" >
                            <option value="">Select</option>
                            <?php $all_director=$this->user_model->all_users("(type=4 AND active=1)");
                            foreach($all_director as $director){ ?>
                                <option value="<?php echo $director->id; ?>"><?php echo $director->first_name." ".$director->last_name; ?></option>
                            <?php }?>
                        </select>
                    </div>

                    <div class="col-md-6 form-group">
                        <label for="emp_code">Deprtment:</label>
                        <select  class="form-control"  id="m_dept_id" name="department" required="required" >
                            <option value="">Select</option>
                            <?php $all_department=$this->common_model->all_active_departments();
                            foreach($all_department as $department){ ?>
                                <option value="<?php echo $department->id; ?>"><?php echo $department->name; ?></option>
                            <?php }?>
                        </select>
                    </div>
                      
                    <div class="col-md-6 form-group">
                        <label for="emp_code">City:</label>
                        <select  class="form-control"  id="m_city_id" name="city" required="required" >
                            <option value="">Select</option>
                            <?php $all_city=$this->common_model->all_active_cities();
                            foreach($all_city as $city){ ?>
                                <option value="<?php echo $city->id; ?>"><?php echo $city->name; ?></option>
                            <?php }?>
                        </select>
                    </div>
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-success" onclick="update_user()" data-dismiss="modal">Submit</button>
            </div>
        </div>
    </div>
</div>
<script>
    function change_state(id){
        $(".se-pre-con").show();
        $.ajax({
            type:"POST",
            url: "<?php echo base_url()?>admin/change_status_user",
            data:{
                id:id
            },
            success:function(data){
                if(data.active){
                    $('#stateus_sp_'+id).text('Active');
                    $('#b1'+id).removeClass("btn-danger");
                    $('#b1'+id).addClass("btn-info");
                }else{
                    $('#stateus_sp_'+id).text('Inactive');
                    $('#b1'+id).removeClass("btn-info");
                    $('#b1'+id).addClass("btn-danger");
                }
                $(".se-pre-con").hide("slow");
            }
        });
    }

    function code_check(name){
        $('#add_vp').prop('disabled', true);
        $(".se-pre-con").show();
        $.ajax({
            type:"POST",
            url: "<?php echo base_url()?>admin/check_user",
            data:{
                code:name
            },
            success:function(data){
                if(data.count){
                    alert("Duplicate Code! try again");
                    $('#emp_code').val('');
                }
                else
                    $('#add_vp').prop('disabled', false);
                $(".se-pre-con").hide("slow");
            }
        });
    }

    function edit_user(v){
        $(".se-pre-con").show();
        console.log(v);
        $("#hid").val(v);
        $.ajax({
            type:"POST",
            url: "<?php echo base_url()?>admin/get_user_data",
            data:{id:v},
            success:function(data) {
                
                data = JSON.parse(data);
                var city_id=data.city_id;
                var dept_id=data.dept_id;
                var email=data.email;
                var first_name=data.first_name;
                var last_name=data.last_name;
                var reports_to=data.reports_to;
                var emp_code=data.emp_code;
                
                $("#m_emp_code").val(emp_code);
                $("#m_first_name").val(first_name);
                $("#m_last_name").val(last_name);
                $("#m_email").val(email);
                $("#m_director").val(reports_to);
                $("#m_dept_id").val(dept_id);
                $("#m_city_id").val(city_id);
                $(".se-pre-con").hide("slow");
            }
        });
    }

    function reset_password(id){
        $(".se-pre-con").show();
        $.get("<?php echo base_url()?>admin/reset_password/"+id,function(response){
            $(".se-pre-con").hide("slow");
            if(response.status)
                alert("Success");
        })
    }
    
    function update_user(){
        $(".se-pre-con").show();
        
        var first_name=$("#m_first_name").val();
        var last_name=$("#m_last_name").val();
        var email=$("#m_email").val();
        var reports_to=$("#m_director").val();
        var dept_id=$("#m_dept_id").val();
        var city_id=$("#m_city_id").val();

        var id=$("#hid").val(); 
            
        $.ajax({
            type:"POST",
            url: "<?php echo base_url()?>admin/update_user/"+id,
            data:{
                first_name:first_name,
                last_name:last_name,
                email:email,
                reports_to:reports_to,
                dept_id:dept_id,
                city_id:city_id
            },
            success:function(data) {
                data = JSON.parse(data);
                if(data.response){
                    alert("success");
                }
                location.reload();
            }
        });
    }
</script>