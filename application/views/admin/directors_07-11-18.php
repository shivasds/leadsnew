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

        <div class="col-sm-12 form-group">
            <button type="submit" style="margin-top:25px;" id="add_director" class="btn btn-success btn-block" disabled>Add Director</button>
        </div>
    </form>

    <table id="example" class="table table-striped table-bordered dt-responsive" cellspacing="0" width="100%">
        <thead>
            <tr>
                <th>Id</th>
                <th>Director Name</th>
                <th>Director Email</th>
                <th>Emp Code</th>
                <th>Date Added</th>
                <th>Status</th>
                <th>Edit</th>
                <th>Change Password</th> 
            </tr>
        </thead> 
        <tbody>
            <?php if(isset($all_directors) && $all_directors){
                foreach($all_directors as $Director){?>
                    <tr>
                        <td><?php echo $Director->id; ?></td>
                        <td><?php echo $Director->first_name." ".$Director->last_name; ?></td>
                        <td><?php echo $Director->email; ?></td>
                        <td><?php echo $Director->emp_code; ?></td>
                        <td><?php echo $Director->date_added; ?></td>
                        <td align="middle"><button type="button" id="b1<?php echo $Director->id; ?>" class="btn <?php echo $Director->active?'btn-info':'btn-danger'; ?>" onclick="change_state(<?php echo $Director->id; ?>)"><span id="stateus_sp_<?php echo $Director->id; ?>"><?php echo $Director->active?'Active':'Inactive'; ?></span></button></td>
                        <td align="middle"><button type="button" class="btn btn-info" onclick="edit_user(<?php echo $Director->id; ?>)" data-toggle="modal" data-target="#modal_edit">Edit</button></td>
                        <td align="middle"><button type="button" class="btn btn-info" onclick="reset_password(<?php echo $Director->id; ?>)">Reset Password</button></td>
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
        $('#add_director').prop('disabled', true);
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
                    $('#add_director').prop('disabled', false);
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
                var email=data.email;
                var first_name=data.first_name;
                var last_name=data.last_name;
                var emp_code=data.emp_code;
                
                $("#m_emp_code").val(emp_code);
                $("#m_first_name").val(first_name);
                $("#m_last_name").val(last_name);
                $("#m_email").val(email);
                $(".se-pre-con").hide("slow");
            }
        });
    }

    function update_user(){
        $(".se-pre-con").show();
        
        var first_name=$("#m_first_name").val();
        var last_name=$("#m_last_name").val();
        var email=$("#m_email").val();

        var id=$("#hid").val(); 
            
        $.ajax({
            type:"POST",
            url: "<?php echo base_url()?>admin/update_user/"+id,
            data:{
                first_name:first_name,
                last_name:last_name,
                email:email
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

    function reset_password(id){
        $(".se-pre-con").show();
        $.get("<?php echo base_url()?>admin/reset_password/"+id,function(response){
            $(".se-pre-con").hide("slow");
            if(response.status)
                alert("Success");
        })
    }
</script>