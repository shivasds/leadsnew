<?php 
    defined('BASEPATH') OR exit('No direct script access allowed');
    $this->load->view('inc/admin_header'); 
?>

<div class="container">
    <div class="page-header">
        <h1><?php echo $heading; ?></h1>
    </div>
    <div class="col-sm-6 form-group">
        <label for="lead_source">Enter Lead Source Name:</label>
        <input type="text" class="form-control" onblur="check_lead_source(this.value)" id="lead_source"  name="lead_source" placeholder="Enter Lead Source">
    </div>
    <div class="col-sm-6 form-group">
        <button type="submit" id="add_lead_source" style="margin-top:25px;" class="btn btn-success btn-block" onclick="add()">Add Lead Source</button>
    </div>
    <table id="example" class="table table-striped table-bordered dt-responsive" cellspacing="0" width="100%">
        <thead>
            <tr>
                <th>Lead Source Id</th>
                <th>Lead Source Name</th>
                <th>Date Added</th>
                <th>Status</th>
            </tr>
        </thead> 
        <tbody>
            <?php if(isset($all_lead_sources) && $all_lead_sources){
                foreach($all_lead_sources as $lead_source){ ?>
                    <tr>
                        <td><?php echo $lead_source->id; ?></td>
                        <td><?php echo $lead_source->name; ?></td>
                        <td><?php echo $lead_source->date_added; ?></td>
                        <td align="middle"><button type="button" id="b1<?php echo $lead_source->id; ?>" class="btn <?php echo $lead_source->active?'btn-info':'btn-danger'; ?>" onclick="change_status(<?php echo $lead_source->id; ?>)"><span id="lead_sourceus_sp_<?php echo $lead_source->id; ?>"><?php echo $lead_source->active?'Active':'Inactive'; ?></span></button></td>
                    </tr>
                <?php }
            } ?>
        </tbody>
    </table>
    <script>
        function add(){
            $(".se-pre-con").show();
            var lead_source=$('#lead_source').val();
            if(lead_source!=''){
                $.ajax({
                    type:"POST",
                    url: "<?php echo base_url()?>admin/add_lead_source",
                    data:{lead_source:lead_source},
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
                url: "<?php echo base_url()?>admin/change_status_lead_source",
                data:{id:id},
                success:function(data){
                    if(data.active){
                        $('#lead_sourceus_sp_'+id).text('Active');
                        $('#b1'+id).removeClass("btn-danger");
                        $('#b1'+id).addClass("btn-info");
                    }else{
                        $('#lead_sourceus_sp_'+id).text('Inactive');
                        $('#b1'+id).removeClass("btn-info");
                        $('#b1'+id).addClass("btn-danger");
                    }
                    $(".se-pre-con").hide("slow");
                }
            });
        }
        function check_lead_source(name){
            $('#add_lead_source').prop('disabled', true);
            $(".se-pre-con").show();
            $.ajax({
                type:"POST",
                url: "<?php echo base_url()?>admin/check_lead_source",
                data:{code:name},
                success:function(data){
                    if(data.count){
                        alert("Duplicate Code! try again");
                        $('#lead_source').val('');
                    }
                    else
                        $('#add_lead_source').prop('disabled', false);
                    $(".se-pre-con").hide("slow");
                }
            });
        }
    </script>
</div>
