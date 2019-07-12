<?php 
    defined('BASEPATH') OR exit('No direct script access allowed');
    $this->load->view('inc/admin_header'); 
?>

<div class="container">
    <div class="page-header">
        <h1><?php echo $heading; ?></h1>
    </div>
    <div class="col-sm-6 form-group">
        <label for="broker">Enter Sub Source:</label>
        <input type="text" class="form-control" onblur="check_broker(this.value)" id="broker"  name="broker" placeholder="Enter Broker">
    </div>
    <div class="col-sm-6 form-group">
        <button type="submit" id="add_broker" style="margin-top:25px;" class="btn btn-success btn-block" onclick="add()" disabled>Add Sub Source</button>
    </div>
    <table id="example" class="table table-striped table-bordered dt-responsive" cellspacing="0" width="100%">
        <thead>
            <tr>
                <th>Sub Source Id</th>
                <th>Name</th>
                <th>Date Added</th>
                <th>Status</th>
            </tr>
        </thead> 
        <tbody>
            <?php if(isset($all_brokers) && $all_brokers){
                foreach($all_brokers as $broker){ ?>
                    <tr>
                        <td><?php echo $broker->id; ?></td>
                        <td><?php echo $broker->name; ?></td>
                        <td><?php echo $broker->date_added; ?></td>
                        <td align="middle"><button type="button" id="b1<?php echo $broker->id; ?>" class="btn <?php echo $broker->active?'btn-info':'btn-danger'; ?>" onclick="change_status(<?php echo $broker->id; ?>)"><span id="brokerus_sp_<?php echo $broker->id; ?>"><?php echo $broker->active?'Active':'Inactive'; ?></span></button></td>
                    </tr>
                <?php }
            } ?>
        </tbody>
    </table>
    <script>
        function add(){
            $(".se-pre-con").show();
            var broker=$('#broker').val();
            if(broker!=''){
                $.ajax({
                    type:"POST",
                    url: "<?php echo base_url()?>admin/add_broker",
                    data:{broker:broker},
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
                url: "<?php echo base_url()?>admin/change_status_broker",
                data:{id:id},
                success:function(data){
                    if(data.active){
                        $('#brokerus_sp_'+id).text('Active');
                        $('#b1'+id).removeClass("btn-danger");
                        $('#b1'+id).addClass("btn-info");
                    }else{
                        $('#brokerus_sp_'+id).text('Inactive');
                        $('#b1'+id).removeClass("btn-info");
                        $('#b1'+id).addClass("btn-danger");
                    }
                    $(".se-pre-con").hide("slow");
                }
            });
        }
        function check_broker(name){
            $(".se-pre-con").show();
            $('#add_broker').prop('disabled', true);
            $.ajax({
                type:"POST",
                url: "<?php echo base_url()?>admin/check_broker",
                data:{code:name},
                success:function(data){
                    if(data.count){
                        alert("Duplicate Code! try again");
                        $('#broker').val('');
                    }
                    else
                        $('#add_broker').prop('disabled', false);
                    $(".se-pre-con").hide("slow");
                }
            });
        }
    </script>
</div>
