<?php 
    defined('BASEPATH') OR exit('No direct script access allowed');
    $this->load->view('inc/admin_header'); 
?>

<div class="container">
    <div class="page-header">
        <h1><?php echo $heading; ?></h1>
    </div>
    <div class="col-sm-3 form-group">
        <label for="project">Enter Project Name:</label>
        <input type="text" class="form-control" onblur="check_project(this.value)" id="project"  name="project" placeholder="Enter Project">
    </div>
    <div class="col-sm-3 form-group">
        <label for="builder">Builder:</label>
        <select id="builder" class="form-control" required="required">
            <option value="">Select</option>
            <?php $allbuilders = $this->common_model->all_active_builders(); 
            foreach ($allbuilders as $builder) { ?>
                <option value="<?php echo $builder->id; ?>"><?php echo $builder->name; ?></option>
            <?php } ?>
        </select>
    </div>
    <div class="col-sm-6 form-group">
        <button type="submit" id="add_project" style="margin-top:25px;" class="btn btn-success btn-block" onclick="add()">Add Project</button>
    </div>
    <table id="example" class="table table-striped table-bordered dt-responsive" cellspacing="0" width="100%">
        <thead>
            <tr>
                <th>Project Id</th>
                <th>Project Name</th>
                <th>Builder Name</th>
                <th>Date Added</th>
                <th>Status</th>
            </tr>
        </thead> 
        <tbody>
            <?php if(isset($all_projects) && $all_projects){
                foreach($all_projects as $project){ ?>
                    <tr>
                        <td><?php echo $project->id; ?></td>
                        <td><?php echo $project->name; ?></td>
                        <td><?php echo $project->builder_name; ?></td>
                        <td><?php echo $project->date_added; ?></td>
                        <td align="middle"><button type="button" id="b1<?php echo $project->id; ?>" class="btn <?php echo $project->active?'btn-info':'btn-danger'; ?>" onclick="change_status(<?php echo $project->id; ?>)"><span id="projectus_sp_<?php echo $project->id; ?>"><?php echo $project->active?'Active':'Inactive'; ?></span></button></td>
                    </tr>
                <?php }
            } ?>
        </tbody>
    </table>
    <script>
        function add(){
            $(".se-pre-con").show();
            var project=$('#project').val();
            if(project!=''){
                if($('#builder').val() == ""){
                    alert("Please select a Builder");
                    $('#builder').focus();
                    return false;
                }
                $.ajax({
                    type:"POST",
                    url: "<?php echo base_url()?>admin/add_project",
                    data:{
                        project:project,
                        builder:$('#builder').val()
                    },
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
                url: "<?php echo base_url()?>admin/change_status_project",
                data:{id:id},
                success:function(data){
                    if(data.active){
                        $('#projectus_sp_'+id).text('Active');
                        $('#b1'+id).removeClass("btn-danger");
                        $('#b1'+id).addClass("btn-info");
                    }else{
                        $('#projectus_sp_'+id).text('Inactive');
                        $('#b1'+id).removeClass("btn-info");
                        $('#b1'+id).addClass("btn-danger");
                    }
                    $(".se-pre-con").hide("slow");
                }
            });
        }
        function check_project(name){
            $('#add_project').prop('disabled', false);
            $(".se-pre-con").show();
            $.ajax({
                type:"POST",
                url: "<?php echo base_url()?>admin/check_project",
                data:{code:name},
                success:function(data){
                    if(data.count){
                        alert("Duplicate Code! try again");
                        $('#project').val('');
                    }
                    else
                        $('#add_project').prop('disabled', false);
                    $(".se-pre-con").hide("slow");
                }
            });
        }
    </script>
</div>
