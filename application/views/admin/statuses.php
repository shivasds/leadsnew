<?php 
    defined('BASEPATH') OR exit('No direct script access allowed');
    $this->load->view('inc/admin_header'); 
?>

<div class="container">
    <div class="page-header">
        <h1><?php echo $heading; ?></h1>
    </div>
    <div class="col-sm-6 form-group">
        <label for="status">Enter Status:</label>
        <input type="text" class="form-control" onblur="check_status(this.value)" id="status"  name="status" placeholder="Enter Status">
    </div>
    <div class="col-sm-6 form-group">
        <button type="submit" id="add_status" style="margin-top:25px;" class="btn btn-success btn-block" onclick="add()" disabled>Add Status</button>
    </div>
    <table id="example" class="table table-striped table-bordered dt-responsive" cellspacing="0" width="100%">
        <thead>
            <tr>
                <th>Status Id</th>
                <th>Status</th>
                <th>Date Added</th>
                <th>Status</th>
            </tr>
        </thead> 
        <tbody>
            <?php if(isset($all_statuses) && $all_statuses){
                foreach($all_statuses as $status){ ?>
                    <tr>
                        <td><?php echo $status->id; ?></td>
                        <td><?php echo $status->name; ?></td>
                        <td><?php echo $status->date_added; ?></td>
                        <td align="middle"><button type="button" id="b1<?php echo $status->id; ?>" class="btn <?php echo $status->active?'btn-info':'btn-danger'; ?>" onclick="change_status(<?php echo $status->id; ?>)"><span id="statusus_sp_<?php echo $status->id; ?>"><?php echo $status->active?'Active':'Inactive'; ?></span></button></td>
                    </tr>
                <?php }
            } ?>
        </tbody>
    </table>
    <script>
        function add(){
            $(".se-pre-con").show();
            var status=$('#status').val();
            if(status!=''){
                $.ajax({
                    type:"POST",
                    url: "<?php echo base_url()?>admin/add_status",
                    data:{status:status},
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
                url: "<?php echo base_url()?>admin/change_status_status",
                data:{id:id},
                success:function(data){
                    if(data.active){
                        $('#statusus_sp_'+id).text('Active');
                        $('#b1'+id).removeClass("btn-danger");
                        $('#b1'+id).addClass("btn-info");
                    }else{
                        $('#statusus_sp_'+id).text('Inactive');
                        $('#b1'+id).removeClass("btn-info");
                        $('#b1'+id).addClass("btn-danger");
                    }
                    $(".se-pre-con").hide("slow");
                }
            });
        }
        function check_status(name){
            if(name !='') {
                //$('#add_project').prop('disabled', true);
                $('#add_status').prop('disabled', true);
                $(".se-pre-con").show();
                $.ajax({
                    type:"POST",
                    url: "<?php echo base_url()?>admin/check_status",
                    data:{code:name},
                    success:function(data){
                        if(data.count){
                            alert("Duplicate Code! try again");
                            $('#status').val('');
                        }
                        else
                            $('#add_status').prop('disabled', false);
                            //$('#add_project').prop('disabled', false);
                        $(".se-pre-con").hide("slow");
                    }
                });
            }
            else
                $('#add_status').prop('disabled', true);
        }
    </script>
</div>
