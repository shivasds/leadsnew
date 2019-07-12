<?php 
    defined('BASEPATH') OR exit('No direct script access allowed');
    $this->load->view('inc/admin_header'); 
?>

<div class="container">
    <div class="page-header">
        <h1><?php echo $heading; ?></h1>
    </div>
    <div class="clearfix"></div>
    <?php if($success) { ?>
        <div class="alert alert-success">
            <?php echo $message; ?>
        </div>
    <?php } ?>
    <div class="clearfix"></div>
    <form name="generate_target_form" id="generate_target_form" method="POST" enctype="multipart/form-data">
    	<div class="col-sm-4 form-group">
            <label for="user_id">User:</label>
            <select class="form-control" id="user_id" name="user_id" onchange="loadAmount()" required>
            	<option value="">Select</option>
            	<?php foreach ($users as $user) { ?>
            		<option value="<?php echo $user->id; ?>">
            			<?php echo $user->first_name." ".$user->last_name; ?>
            		</option>
            	<?php } ?>
            </select>
        </div>

        <div class="col-sm-4 form-group">
            <label for="month">Month:</label>
            <input type="text" class="form-control" id="month" name="month" placeholder="Selct Month" required>
        </div>

        <div class="col-sm-4 form-group">
            <label for="target">Target:</label>
            <input type="number" class="form-control" id="target" name="target" required>
        </div>

        <div class="col-sm-12 form-group">
            <button type="submit" style="margin-top:25px;" id="save_target" class="btn btn-success btn-block">Save Target</button>
        </div>
    </form>
</div>
<script type="text/javascript">
	var loadAmount = function (){
		if(($('#user_id').val() == '') || ($('#month').val() == ''))
    		return false;
    	$(".se-pre-con").show();
    	var ajaxData = {
    		'user_id':$('#user_id').val(),
    		'month':$('#month').val()
    	};
    	$.post("<?php echo base_url(); ?>admin/get_target", ajaxData, function(resp){
    		$("#target").val(resp);
    		$(".se-pre-con").hide();
    	});
	}
	$(document).ready(function(){
		$('#month').MonthPicker({
            Button: false,
            OnAfterChooseMonth: loadAmount
        });
	});
	
</script>