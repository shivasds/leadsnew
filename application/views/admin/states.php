<?php 
    defined('BASEPATH') OR exit('No direct script access allowed');
    $this->load->view('inc/admin_header'); 
?>
<div class="container">
    <div class="page-header">
        <h1><?php echo $heading; ?></h1>
    </div>
    <div class="col-sm-6 form-group">
        <label for="state">Enter State Name:</label>
        <input type="text" class="form-control" onblur="check_state(this.value)" id="state"  name="state" placeholder="Enter State">
    </div>
    <div class="col-sm-6 form-group">
        <button type="submit" id="add_state" style="margin-top:25px;" class="btn btn-success btn-block" onclick="add()" disabled>Add State</button>
    </div>
    <table id="example" class="table table-striped table-bordered dt-responsive" cellspacing="0" width="100%">
        <thead>
            <tr>
                <th>State Id</th>
                <th>State Name</th>
                <th>Date Added</th>
                <th>Status</th>
            </tr>
        </thead> 
        <tbody>
            <?php if(isset($all_states) && $all_states){
                foreach($all_states as $state){ ?>
                    <tr>
                        <td><?php echo $state->id; ?></td>
                        <td><?php echo $state->name; ?></td>
                        <td><?php echo $state->date_added; ?></td>
                        <td align="middle"><button type="button" id="b1<?php echo $state->id; ?>" class="btn <?php echo $state->active?'btn-info':'btn-danger'; ?>" onclick="change_status(<?php echo $state->id; ?>)"><span id="stateus_sp_<?php echo $state->id; ?>"><?php echo $state->active?'Active':'Inactive'; ?></span></button></td>
                    </tr>
                <?php }
            } ?>
        </tbody>
    </table>
    <script>
        function add(){
            $(".se-pre-con").show();
            var state=$('#state').val();
            if(state!=''){
                $.ajax({
                    type:"POST",
                    url: "<?php echo base_url()?>admin/add_state",
                    data:{state:state},
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
                url: "<?php echo base_url()?>admin/change_status_state",
                data:{id:id},
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
        function check_state(name){
            $(".se-pre-con").show();
            $('#add_state').prop('disabled', true);
            $.ajax({
                type:"POST",
                url: "<?php echo base_url()?>admin/check_state",
                data:{code:name},
                success:function(data){
                    if(data.count){
                        alert("Duplicate Code! try again");
                        $('#state').val('');
                    }
                    else
                        $('#add_state').prop('disabled', false);
                    $(".se-pre-con").hide("slow");
                }
            });
        }
    </script>
</div>
