<?php 
    defined('BASEPATH') OR exit('No direct script access allowed');
    $this->load->view('inc/admin_header'); 
?>

<div class="container">
    <div class="page-header">
        <h1><?php echo $heading; ?></h1>
    </div>
    <form  action="<?php echo base_url()?>admin/generate_callback" method="POST" enctype="multipart/form-data">
        <div class="row">
            <div class="col-md-3 form-group">
                <label for="emp_code">Dept:</label>
                <select  class="form-control"  id="dept" name="dept" required >
                    <option value="">Select</option>
                    <?php $all_department=$this->common_model->all_active_departments();
	                foreach($all_department as $department){ ?>
	                    <option value="<?php echo $department->id; ?>"><?php echo $department->name; ?></option>
	                <?php }?>           
                </select>
            </div>

            <div class="col-sm-3 form-group">
                <label for="name">Name:</label>
                <input type="text" class="form-control" id="name" name="name" placeholder="Name" required="required">
            </div>

            <div class="col-sm-3 form-group">
                <label for="contact_no1">Contact No:</label>
                <input type="number" class="form-control" id="contact_no1" name="contact_no1" placeholder="Contact No">
            </div>

            <div class="col-sm-3 form-group">
                <label for="name">Contact No 2:</label>
                <input type="number" class="form-control" id="contact_no2" name="contact_no2" placeholder="Contact No">
            </div>
            
            <div class="col-md-3 form-group">
                <label for="assign">Call back type:</label>
                <select  class="form-control"  id="callback_type" name="callback_type" required="required" >
                    <option value="">Select </option>
                    <?php $all_callback_types=$this->common_model->all_active_callback_types();
	                foreach($all_callback_types as $callback_type){ ?>
	                    <option value="<?php echo $callback_type->id; ?>"><?php echo $callback_type->name; ?></option>
	                <?php }?>            
                </select>
            </div>

            <div class="col-sm-3 form-group">
                <label for="email">Email:</label>
                <input type="email" class="form-control" id="email1" name="email1" placeholder="Email">
            </div>

            <div class="col-sm-3 form-group">
                <label for="email">Email2:</label>
                <input type="email" class="form-control" id="email2" name="email2" placeholder="email">
            </div>

            <div class="col-md-3 form-group">
                <label for="emp_code">Project:</label>
                <select  class="form-control"  id="project" name="project" required="required" >
                    <option value="">Select</option>
                    <?php $projects= $this->common_model->all_active_projects(); 
                    foreach( $projects as $project){ ?>
                        <option value="<?php echo $project->id ?>"><?php echo $project->name ?></option>
                    <?php }?>               
                </select>
            </div>

            <div class="col-md-3 form-group">
                <label for="assign">Lead Source:</label>
                <select  class="form-control"  id="lead_source" name="lead_source" required="required" >
                    <option value="">Select</option>
                    <?php $lead_source= $this->common_model->all_active_lead_sources(); 
                    foreach( $lead_source as $source){ ?>
                        <option value="<?php echo $source->id ?>"><?php echo $source->name ?></option>
                    <?php } ?>
                </select>
            </div>

            <div class="col-sm-3 form-group">
                <label for="leadId">Lead Id:</label>
                <input type="text" class="form-control" id="leadId" name="leadId" placeholder="Lead Id">
            </div>

            <div class="col-md-3 form-group">
                <label for="assign">User Name:</label>
                <select  class="form-control"  id="user_name" name="user_name" required="required" >
                     <option value="">Select</option>
                    <?php $all_user= $this->user_model->all_users("type in (1,2,3,4)"); 
                    foreach( $all_user as $user){ 
                    	switch ($user->type) {
                    		case '1':
                    			$role = "User";
                    			break;

                    		case '2':
                    			$role = "Manager";
                    			break;

                    		case '3':
                    			$role = "VP";
                    			break;
                    		
                    		case '4':
                    			$role = "Director";
                    			break;
                    	}
                    	?>
                        <option value="<?php echo $user->id ?>"><?php echo $user->first_name." ".$user->last_name." ($role)"; ?></option>
                    <?php } ?>               
                </select>
            </div>
      
            <div class="col-md-3 form-group">
                <label for="assign">Manage Sub Broker:</label>
                <select  class="form-control"  id="sub_broker" name="sub_broker" required="required" >
                    <option value="">Select</option>
                    <?php $brokers= $this->common_model->all_active_brokers(); 
                    foreach( $brokers as $broker){ ?>
                        <option value="<?php echo $broker->id; ?>"><?php echo $broker->name ?></option>
                    <?php } ?>               
                </select>
            </div>
      
            <div class="col-md-3 form-group">
                <label for="assign">Status:</label>
                <select  class="form-control"  id="status" name="status" required="required" >
                    <option value="">Select</option>
                    <?php $statuses= $this->common_model->all_active_statuses(); 
                    foreach( $statuses as $status){ ?>
                        <option value="<?php echo $status->id; ?>"><?php echo $status->name ?></option>
                    <?php } ?>
                </select>
            </div>

            <div class="col-sm-3 form-group">
                <label for="leadId">Due date:</label>
                <input type="date" class="form-control" id="due_date" name="due_date" placeholder="Date" required="required">
            </div>
      
            <div class="col-sm-6 form-group">
                <label for="comment">Notes:</label>
                <textarea class="form-control" name="notes" id="notes" rows="3" id="comment"></textarea>
            </div>
      
            <div class="col-sm-12" id="phone_error" style="display:none">
                <div class="alert alert-danger" >
                    The contact number already used
                </div>
            </div>
            
            <div class="col-sm-12" id="email_error" style="display:none">
                <div class="alert alert-danger" >
                    The email already used
                </div>
            </div>
            
            <div class="col-sm-6 form-group">
                <a class="btn btn-danger btn-block" id="cancel"onclick="reset_data()">Cancel</a>
            </div>
            <div class="col-sm-6 form-group">
                <button type="submit" id="save" class="btn btn-success btn-block">Save</button>
            </div>
        </div>
    </form>
</div>
<script type="text/javascript">
    function reset_data(){
        $('#name').val('');
        $('#contact_no1').val('');
        $('#contact_no2').val('');
        $('#email1').val('');
        $('#email2').val('');
        $('#lead_source').val();
        $('#project').val();
        $('#leadId').val();
        $('#assign').val();
        $('#due_date').val('');

        $('#dept').val('0').change();
        $('#callback_type').val('0').change();
        $('#project').val('0').change();
        $('#lead_source').val('0').change();
    }

    $(document).ready(function(){
        var con1=$('#contact_no1').val();
        var con2=$('#contact_no2').val();
        if(con1=='' && con2==''){
            $('#contact_no1').prop('required',true);
        }
        
        var em1=$('#email1').val();
        var em2=$('#email2').val();
        if(em1=='' && em2==''){
            $('#email1').prop('required',true);
        }

        $("#contact_no1, #contact_no2").keyup(function(){
            if($(this).val() != ''){
                $.getJSON( "<?php echo base_url()?>admin/check_isnumberexists/"+$(this).val(), function( data ) {
                    if(data.exists){
                        $('#phone_error').show();
                        $(this).focus();
                        $("#save").attr('disabled',true);
                    }
                    else{
                        $('#phone_error').hide();
                        $("#save").attr('disabled',false);
                    }
                });
            }
        });

        $("#email1, #email2").keyup(function(){
            if($(this).val() != ''){
                $.getJSON( "<?php echo base_url()?>admin/check_isemailexists?email="+encodeURIComponent($(this).val()), function( data ) {
                    if(data.exists){
                        $('#email_error').show();
                        $(this).focus();
                        $("#save").attr('disabled',true);
                    }
                    else{
                        $('#email_error').hide();
                        $("#save").attr('disabled',false);
                    }
                });
            }
        });

    });
</script>

