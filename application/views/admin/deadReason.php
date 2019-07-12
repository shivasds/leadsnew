<?php 
    defined('BASEPATH') OR exit('No direct script access allowed');
    $this->load->view('inc/admin_header'); 
?>
<style type="text/css">
    a.btn{
        float: left;
        margin-right: 30px;
    }
</style>
<div class="container">
    <div class="row">
        <div class="page-header">
            <h1><?php echo $heading; ?></h1>
        </div>
        <div class="col-sm-6">
            <form method="post" id="reason-frm">
                <div class="col-sm-6">
                    <div class="form-group">
                        <label class="control-label" for="state">Enter Reason:</label>
                        <input type="text" class="form-control" name="reason" placeholder="Enter Reason" />
                    </div>
                </div>
                <div class="col-sm-4">
                   <div class="form-group">
                        <button type="submit" id="addReason" style="margin-top: 25px;" class="btn btn-success" >Add State</button>
                    </div>
                </div>
                <div class="clearfix"></div>
                <div class="Errmsg"></div>
            </form>
        </div>
    </div>
    <table class="table table-striped table-bordered dt-responsive" cellspacing="0" width="100%">
        <thead>
            <tr>
                <th>Sl No.</th>
                <th>Reason</th>
                <th>Date Added</th>
                <th>Action</th>
            </tr>
        </thead> 
        <tbody>
            <?php if(isset($results) && $results){
                foreach($results as $k=>$reason){ 
                    if($reason['status'] == 'Y') {
                        $sts = 'Active';
                        $cls = 'btn-success';
                    }
                    else {
                        $sts = 'Inactive';
                        $cls = 'btn-danger';
                    }
                    ?>
                    <tr>
                        <td><?php echo ($k+1); ?></td>
                        <td><?php echo $reason['name']; ?></td>
                        <td><?php echo $reason['entryDate']; ?></td>
                        <td align="middle">
                            <a href="<?= base_url('admin/dead_reason_status'); ?>?id=<?= $reason['id'];?>&sts=<?= $reason['status'];?>" class="btn <?= $cls;?>"><?= $sts;?></a>
                           <?php /* <a href="<?= base_url('admin/dead_reason_delete'); ?>?id=<?= $reason['id'];?>" class="btn btn-info">Delete</a>*/?>
                        </td>
                    </tr>
                    <?php 
                }
            } 
            else
                echo '<tr><td colspan="4"><p class="text-center">No records found!</p></td></tr>';
            ?>
        </tbody>
    </table>    
</div>

<script type="text/javascript">
    $(function(){
        $(document).on('click', '#reason-frm #addReason', function(e){
            e.preventDefault();  
            var msg = $('#reason-frm .Errmsg');
            $.ajax({
                type    : 'POST',
                url     : '<?= base_url('admin/add_dead_reason');?>',
                data    : $('#reason-frm').serialize(),
                beforeSend: function(){
                    $(".se-pre-con").show();
                },
                success: function(res){                          
                    $(".se-pre-con").hide();
                    if(res.type == 1) {
                        $('#reason-frm')[0].reset();   
                        msg.html('<p class="alert alert-success"><strong>Success!</strong> '+res.msg+'</p>').show();    
                        setTimeout(function () {
                            location.reload();
                       }, 2000);
                    }
                    else
                         msg.html('<p class="alert alert-danger"><strong>Error!</strong> '+res.msg+'</p>').show();

                    setTimeout(function () {
                        msg.html('').slideUp();
                   }, 8000);
                        
                },
            });
        });
    });    
</script>
