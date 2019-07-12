<?php 
    defined('BASEPATH') OR exit('No direct script access allowed');
    $this->load->view('inc/admin_header'); 
?>

<div class="container">
    <div class="page-header">
        <h1><?php echo $heading; ?></h1>
    </div>
    <a class="btn btn-success btn-block" href="<?php echo base_url()?>uploads/upload_Format.xlsx">Download sample excel</a>
    <br>
    <form  action="<?php echo base_url()?>admin/bulk_upload_callback" method="POST" enctype="multipart/form-data">
        <div class="row">
            <div class="col-sm-6 form-group">
                <input type="file" class="form-control" id="file" name="file" placeholder="File" required="required">
            </div>
            <div class="col-sm-3 form-group">
                <button type="reset" class="btn btn-success btn-block" value="Reset">Reset</button>
            </div>
            <div class="col-sm-3 form-group">
                <button type="submit" class="btn btn-success btn-block" name="submit">Read Data</button>
            </div>
        </div>
    </form>
</div>


