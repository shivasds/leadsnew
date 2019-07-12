<?php 
    defined('BASEPATH') OR exit('No direct script access allowed');
    $this->load->view('inc/admin_header'); 
?>

<div class="container">
    <div class="page-header">
        <h1><?php echo $heading; ?></h1>
    </div>
    <div class="col-sm-6 form-group">
        <label for="callback_type">Enter Callback Type:</label>
        <input type="text" class="form-control" onblur="check_callback_type(this.value)" id="callback_type"  name="callback_type" placeholder="Enter Callback Type">
    </div>
    <div class="col-sm-6 form-group">
        <button type="submit" id="add_callback_type" style="margin-top:25px;" class="btn btn-success btn-block" onclick="add()">Add Callback Type</button>
    </div>
    <table id="example" class="table table-striped table-bordered dt-responsive" cellspacing="0" width="100%">
        <thead>
            <tr>
                <th>Callback Type Id</th>
                <th>Callback Type</th>
                <th>Date Added</th>
                <th>Status</th>
            </tr>
        </thead> 
        <tbody>
            <?php if(isset($all_callback_types) && $all_callback_types){
                foreach($all_callback_types as $callback_type){ ?>
                    <tr>
                        <td><?php echo $callback_type->id; ?></td>
                        <td><?php echo $callback_type->name; ?></td>
                        <td><?php echo $callback_type->date_added; ?></td>
                        <td align="middle"><button type="button" id="b1<?php echo $callback_type->id; ?>" class="btn <?php echo $callback_type->active?'btn-info':'btn-danger'; ?>" onclick="change_status(<?php echo $callback_type->id; ?>)"><span id="callback_typeus_sp_<?php echo $callback_type->id; ?>"><?php echo $callback_type->active?'Active':'Inactive'; ?></span></button></td>
                    </tr>
                <?php }
            } ?>
        </tbody>
    </table>
    <script>
        function add(){
            $(".se-pre-con").show();
            var callback_type=$('#callback_type').val();
            if(callback_type!=''){
                $.ajax({
                    type:"POST",
                    url: "<?php echo base_url()?>admin/add_callback_type",
                    data:{callback_type:callback_type},
                    success:function(data){
                        alert("add successful");
                    }
                });
                location.reload();
            }
            else{
                $(".se-pre-con").hide("slow");
                alert("Please Enter a value");
            }
        }
        function change_status(id){
            $(".se-pre-con").show();
            $.ajax({
                type:"POST",
                url: "<?php echo base_url()?>admin/change_status_callback_type",
                data:{id:id},
                success:function(data){
                    if(data.active){
                        $('#callback_typeus_sp_'+id).text('Active');
                        $('#b1'+id).removeClass("btn-danger");
                        $('#b1'+id).addClass("btn-info");
                    }else{
                        $('#callback_typeus_sp_'+id).text('Inactive');
                        $('#b1'+id).removeClass("btn-info");
                        $('#b1'+id).addClass("btn-danger");
                    }
                    $(".se-pre-con").hide("slow");
                }
            });
        }
        function check_callback_type(name){
            $(".se-pre-con").show();
            $('#add_callback_type').prop('disabled', true);
            $.ajax({
                type:"POST",
                url: "<?php echo base_url()?>admin/check_callback_type",
                data:{code:name},
                success:function(data){
                    if(data.count){
                        alert("Duplicate Code! try again");
                        $('#callback_type').val('');
                    }
                    else
                        $('#add_callback_type').prop('disabled', false);
                    $(".se-pre-con").hide("slow");
                }
            });
        }
    </script>
</div>
