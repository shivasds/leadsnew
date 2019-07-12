<?php 
    defined('BASEPATH') OR exit('No direct script access allowed');
    $this->load->view('inc/admin_header'); 
?>

<div class="container">
    <div class="page-header">
        <h1><?php echo $heading; ?></h1>
    </div>
    <div class="col-sm-3 form-group">
        <label for="city">Enter City Name:</label>
        <input type="text" class="form-control" onblur="check_city(this.value)" id="city"  name="city" placeholder="Enter City">
    </div>
    <div class="col-sm-3 form-group">
    	<label for="state">State:</label>
    	<select id="state" class="form-control" required="required">
    		<option value="">Select</option>
    		<?php $allstates = $this->common_model->all_active_states(); 
    		foreach ($allstates as $state) { ?>
    		 	<option value="<?php echo $state->id; ?>"><?php echo $state->name; ?></option>
    		<?php } ?>
    	</select>
    </div>
    <div class="col-sm-3 form-group">
        <button type="submit" id="add_city" style="margin-top:25px;" class="btn btn-success btn-block" onclick="add()" disabled>Add City</button>
    </div>
    <table id="example" class="table table-striped table-bordered dt-responsive" cellspacing="0" width="100%">
        <thead>
            <tr>
                <th>City Id</th>
                <th>City Name</th>
                <th>State Name</th>
                <th>Date Added</th>
                <th>Status</th>
            </tr>
        </thead> 
        <tbody>
            <?php if(isset($all_cities) && $all_cities){
                foreach($all_cities as $city){ ?>
                    <tr>
                        <td><?php echo $city->id; ?></td>
                        <td><?php echo $city->name; ?></td>
                        <td><?php echo $city->state_name; ?></td>
                        <td><?php echo $city->date_added; ?></td>
                        <td align="middle"><button type="button" id="b1<?php echo $city->id; ?>" class="btn <?php echo $city->active?'btn-info':'btn-danger'; ?>" onclick="change_status(<?php echo $city->id; ?>)"><span id="cityus_sp_<?php echo $city->id; ?>"><?php echo $city->active?'Active':'Inactive'; ?></span></button></td>
                    </tr>
                <?php }
            } ?>
        </tbody>
    </table>
    <script>
        function add(){
            $(".se-pre-con").show();
            var city=$('#city').val();
            if(city!=''){
            	if($('#state').val() == ""){
	            	alert("Please select a state");
	            	$('#state').focus();
	            	return false;
	            }
                $.ajax({
                    type:"POST",
                    url: "<?php echo base_url()?>admin/add_city",
                    data:{
                    	city:city,
                    	state:$('#state').val()
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
                url: "<?php echo base_url()?>admin/change_status_city",
                data:{id:id},
                success:function(data){
                    if(data.active){
                        $('#cityus_sp_'+id).text('Active');
                        $('#b1'+id).removeClass("btn-danger");
                        $('#b1'+id).addClass("btn-info");
                    }else{
                        $('#cityus_sp_'+id).text('Inactive');
                        $('#b1'+id).removeClass("btn-info");
                        $('#b1'+id).addClass("btn-danger");
                    }
                    $(".se-pre-con").hide("slow");
                }
            });
        }
        function check_city(name){
            $('#add_city').prop('disabled', true);
            $(".se-pre-con").show();
            $.ajax({
                type:"POST",
                url: "<?php echo base_url()?>admin/check_city",
                data:{code:name},
                success:function(data){
                    if(data.count){
                        alert("Duplicate Code! try again");
                        $('#city').val('');
                    }
                    else
                        $('#add_city').prop('disabled', false);
                    $(".se-pre-con").hide("slow");
                }
            });
        }
    </script>
</div>
