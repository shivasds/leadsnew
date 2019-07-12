<?php 
    defined('BASEPATH') OR exit('No direct script access allowed');
    $this->load->view('inc/admin_header'); 
?>

<div class="container">
    <div class="page-header">
        <h1><?php echo $heading; ?></h1>
    </div>
    <div class="col-sm-6 form-group">
        <label for="dept">Enter Dept Name:</label>
        <input type="text" class="form-control" onblur="check_dept(this.value)" id="dept"  name="dept" placeholder="Enter Dept">
    </div>
    <div class="col-sm-6 form-group">
        <button type="submit" id="add_dept" style="margin-top:25px;" class="btn btn-success btn-block" onclick="add()" disabled>Add Dept</button>
    </div>
    <table id="example" class="table table-striped table-bordered dt-responsive" cellspacing="0" width="100%">
        <thead>
            <tr>
                <th>Dept Id</th>
                <th>Dept Name</th>
                <th>Date Added</th>
                <th>Status</th>
            </tr>
        </thead> 
        <tbody>
            <?php if(isset($all_depts) && $all_depts){
                foreach($all_depts as $dept){ ?>
                    <tr>
                        <td><?php echo $dept->id; ?></td>
                        <td><?php echo $dept->name; ?></td>
                        <td><?php echo $dept->date_added; ?></td>
                        <td align="middle"><button type="button" id="b1<?php echo $dept->id; ?>" class="btn <?php echo $dept->active?'btn-info':'btn-danger'; ?>" onclick="change_status(<?php echo $dept->id; ?>)"><span id="deptus_sp_<?php echo $dept->id; ?>"><?php echo $dept->active?'Active':'Inactive'; ?></span></button></td>
                    </tr>
                <?php }
            } ?>
        </tbody>
    </table>
    <script>
        function add(){
            $(".se-pre-con").show();
            var dept=$('#dept').val();
            if(dept!=''){
                $.ajax({
                    type:"POST",
                    url: "<?php echo base_url()?>admin/add_dept",
                    data:{dept:dept},
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
                url: "<?php echo base_url()?>admin/change_status_dept",
                data:{id:id},
                success:function(data){
                    if(data.active){
                        $('#deptus_sp_'+id).text('Active');
                        $('#b1'+id).removeClass("btn-danger");
                        $('#b1'+id).addClass("btn-info");
                    }else{
                        $('#deptus_sp_'+id).text('Inactive');
                        $('#b1'+id).removeClass("btn-info");
                        $('#b1'+id).addClass("btn-danger");
                    }
                    $(".se-pre-con").hide("slow");
                }
            });
        }
        function check_dept(name){
            $('#add_dept').prop('disabled', true);
            $(".se-pre-con").show();
            $.ajax({
                type:"POST",
                url: "<?php echo base_url()?>admin/check_dept",
                data:{code:name},
                success:function(data){
                    if(data.count){
                        alert("Duplicate Code! try again");
                        $('#dept').val('');
                    }
                    else
                        $('#add_dept').prop('disabled', false);
                    $(".se-pre-con").hide("slow");
                }
            });
        }
    </script>
</div>
