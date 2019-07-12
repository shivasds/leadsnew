<?php 
    defined('BASEPATH') OR exit('No direct script access allowed');
    $this->load->view('inc/header'); 
?>

<div class="container">
  
    <div class="page-header">
        <h1><?php echo $heading; ?></h1>
    </div>
	<div class="col-sm-6 form-group">
        <button class="btn btn-info btn-block" style="margin-top: 24px;" <?php if($dar_flag) echo 'disabled'; ?> id="previous_dar">Previous DAR</button>
    </div>
    <?php
    	$generate = false;
    	$currentTime = (int) date('Gis');
    	if(($currentTime >= 170000) && ($currentTime <= 220000))
    		$generate = true;
    ?>
    <div class="col-sm-6 form-group">
        <button class="btn btn-success btn-block" style="margin-top: 24px;" <?php if((!$generate) || ($dar_flag)) echo 'disabled'; ?> id="generate_dar">Generate Todays DAR</button>
    </div>
    <div>
      <?php if($dar_flag==0){ ?>
        Please note that DAR can be generated only between 5:00 pm & 10:00 pm
      <?php }else{ ?>
        You forget to enter yesterdays DAR. Please enter Yesterdays DAR details to login to the system. If you were leave, please mention it in the field provided.
      <?php } ?>
    </div>
    <div class="clearfix"></div><br>
    <form method="post" class="form-inline" style="display: none;" id="dar_form">
    	<input type="hidden" name="date" value="<?php echo $date; ?>">
  		<div class="form-group">
    		<label for="crm">CRM Calls:</label>
		    <input type="number" class="form-control" name="crm" value="<?php echo $crm_calls; ?>" readonly>
		    <input type="text" class="form-control" name="crm_comment">
  		</div>
  		<div class="clearfix"></div><br>
  		<div class="form-group">
		    <label for="f2f">F2F Meets:</label>
		    <input type="number" class="form-control" name="f2f">
		    <input type="text" class="form-control" name="f2f_comment">
  		</div>
  		<div class="clearfix"></div><br>
  		<div class="form-group">
		    <label for="site_visit">Site Visits:</label>
		    <input type="number" class="form-control" name="site_visit">
		    <input type="text" class="form-control" name="site_visit_comment">
  		</div>
  		<div class="clearfix"></div><br>
  		<div class="form-group">
		    <label for="sub_brok_app">Sub-broker Appointments:</label>
		    <input type="number" class="form-control" name="sub_brok_app">
		    <input type="text" class="form-control" name="sub_brok_app_comment">
  		</div>
  		<div class="clearfix"></div><br>
  		<div class="form-group">
		    <label for="builders_app">Builders Appointments:</label>
		    <input type="number" class="form-control" name="builders_app">
		    <input type="text" class="form-control" name="builders_app_comment">
  		</div>
  		<div class="clearfix"></div><br>
  		<div class="form-group">
		    <label for="other">Other Office Work:</label>
		    <input type="text" class="form-control" name="other">
		    <input type="text" class="form-control" name="other_comment">
  		</div>
      <?php if($dar_flag == 1) {?>
        <div class="clearfix"></div><br>
        <div class="form-group">
          <label for="other">Notes:</label>
          <input type="text" class="form-control" name="note" placeholder="I was leave yesterday">
        </div>
      <?php } ?>
       <div class="clearfix"></div><br>
  		<button type="submit" class="btn btn-default" id="save" disabled="true">Submit</button>
	</form>
	<form method="post" class="form-inline" style="display: none;" id="previous_dar_form" >
    	<div class="form-group">
    		<label for="crm">Date:</label>
		    <input type="date" class="form-control" name="date" required>
		</div>
  		<button type="submit" class="btn btn-default">Submit</button>
	</form>
	<table id="example" class="table table-striped table-bordered" cellspacing="0" width="100%" style="display: none">
        <thead>
            <tr>
                <th>No</th>
                <th>User Name</th> 
                <th>CRM Calls</th>
                <th>F2F Meets</th>
                <th>Site Visits</th>
                <th>Sub broker Appoinments</th>
                <th>Builder Appointments</th>
                <th>Others</th>
                <th>Notes</th>
                <th>Time Added</th>
            </tr>
        </thead> 
        <tbody id="main_body">
            
        </tbody>
    </table>
</div>
<script type="text/javascript">
	$(document).ready(function(){

		$('#generate_dar').click(function(){
			$('#dar_form').show();
			$('#previous_dar_form').hide();
		});
		$('#previous_dar').click(function(){
			$('#previous_dar_form').show();
			$('#dar_form').hide();
		});
		$('#previous_dar_form').submit(function(e){
			e.preventDefault();
      $(".se-pre-con").show();
			$.post( "<?php echo base_url()?>dashboard/get_dar_data", $("#previous_dar_form").serialize(), function(data){
				$('#main_body').html(data);
				$('#example').DataTable();
				$('#example').show();
        $(".se-pre-con").hide();
			} );
		});
		if (!Modernizr.inputtypes.date) {
	        $('input[type=date]').datepicker({
            	// Consistent format with the HTML5 picker
                dateFormat : 'dd/mm/yy'
            });
	    }
      $("input[name='crm_comment'], input[name='f2f'], input[name='f2f_comment'], input[name='site_visit'], input[name='site_visit_comment'], input[name='sub_brok_app'], input[name='sub_brok_app_comment'], input[name='builders_app'], input[name='builders_app_comment'], input[name='other'], input[name='other_comment']").change(function(){
        var temp = ["crm_comment","f2f","f2f_comment","site_visit","site_visit_comment","sub_brok_app","sub_brok_app_comment","builders_app","builders_app_comment","other","other_comment",]
        var flag = 1;
        for (var i = 0; i < temp.length; i++) {
          if($("input[name='"+temp[i]+"']").val().length == 0){
            flag = 0;
            break;
          }
        }
        if(flag)
          $('#save').prop('disabled', false);
        else
          $('#save').prop('disabled', true);
      });
      $("input[name='note']").change(function(){
        if($(this).val().length > 1)
          $('#save').prop('disabled', false);
        else
          $('#save').prop('disabled', true);
      })
    <?php if($dar_flag) {?>
      $('#generate_dar').click();
    <?php } ?>
	});
</script>