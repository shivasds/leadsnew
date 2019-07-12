<?php 
    defined('BASEPATH') OR exit('No direct script access allowed');
    $this->load->view('inc/header'); 
?>

<div class="container">
  
    <div class="page-header">
        <h1><?php echo $heading; ?></h1>
    </div>
    <?php if($alert) { ?>
	    <div class="alert alert-<?php echo $success?'success':'danger'; ?>">
			<?php echo $message; ?>.
		</div>
	<?php } ?>
    <form method="POST" enctype="multipart/form-data">
        <div class="col-md-6 form-group">
            <label for="screen_shot">ScreenShot:</label>
            <input class="form-control" type="file" name="screen_shot" required>
        </div>
        <div class="col-md-6 form-group">
            <label for="description">Describe your Issue with the Application :</label>
            <input class="form-control input-lg" type="text" name="description">
        </div>
        <div class="col-sm-6 form-group">
            <button class="btn btn-info btn-block" onclick="reset_data()" style="margin-top: 24px;" >Reset</button>
        </div>
        <div class="col-sm-6 form-group">
            <button type="submit" class="btn btn-success btn-block" style="margin-top: 24px;" >Report</button>
        </div>
    </form>
</div>