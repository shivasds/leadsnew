<?php 
    defined('BASEPATH') OR exit('No direct script access allowed');
    $this->load->view('inc/admin_header'); 
?>

<div class="container">
    <div class="page-header">
        <h1><?php echo $heading; ?></h1>
    </div>
    <form name="generate_slab_form" id="generate_slab_form" method="POST" enctype="multipart/form-data">
        <div class="col-sm-6 form-group">
            <label for="from_date">From:</label>
            <input type="date" class="form-control" id="from_date" name="from_date" placeholder="Selct From Date" required>
        </div>

        <div class="col-sm-6 form-group">
            <label for="to_date">To:</label>
            <input type="date" class="form-control" id="to_date" name="to_date" placeholder="Selct To Date" required>
        </div>

        <div class="col-sm-6 form-group">
            <label for="amount">Amount:</label>
            <input type="number" class="form-control" id="amount" name="amount[]" required>
            <!-- <output name="rangeOutput" id="rangeOutput">5000</output> -->
        </div>

        <div class="col-sm-6 form-group" >
            <label for="percentage">Percentage:</label>
            <input type="number" min="0" max="100" step="0.01" class="form-control" id="percentage" name="percentage[]" placeholder="Enter Percentage" required>
        </div>
        <div class="col-sm-12 form-group" style="text-align: center;" id="add_more" >
            <span >Add More</span>
        </div>

        <div class="col-sm-12 form-group">
            <button type="submit" style="margin-top:25px;" id="save_slab" class="btn btn-success btn-block">Save Incentive Slab</button>
        </div>
    </form>
    <div class="clearfix"></div>
    <?php if($success) { ?>
        <div class="alert alert-success">
            <?php echo $message; ?>
        </div>
    <?php } ?>
    <table id="example" class="table table-striped table-bordered dt-responsive" cellspacing="0" width="100%">
        <thead>
            <tr>
                <th>Interval Id</th>
                <th>From</th>
                <th>To</th>
                <th>Entries</th>
                <th>Action</th>
            </tr>
        </thead> 
        <tbody>
            <?php if(isset($intervals) && $intervals){
                foreach($intervals as $interval){ ?>
                    <tr id="row_<?php echo $interval->id; ?>">
                        <td><?php echo $interval->id; ?></td>
                        <td><?php echo $interval->from; ?></td>
                        <td><?php echo $interval->to; ?></td>
                        <td><a class="modalButton" href="#incentiveModal" data-toggle="modal" data-id="<?php echo $interval->id; ?>"><?php echo $interval->count; ?></a></td>
                        <td><button type="button" class="btn btn-info btn-sm editButton" data-id="<?php echo $interval->id; ?>" data-target="#editModal" data-toggle="modal"><span class="glyphicon glyphicon-edit"></span>Edit</button></td>
                    </tr>
                <?php }
            } 
            else{?>
                <tr>
                    <td colspan="5" style="text-align: center;">No Entries</td>
                </tr>
            <?php } ?>
        </tbody>
    </table>
</div>
<div class="modal fade" id="incentiveModal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                <h4 class="modal-title" id="exampleModalLabel">Incentive Slabs</h4>
            </div>
            <div class="modal-body">
                <table class="table table-striped table-bordered dt-responsive" cellspacing="0" width="100%">
                    <thead>
                        <tr>
                            <th>Amount</th>
                            <th>Percentage</th>
                        </tr>
                    </thead>
                    <tbody id="incentive_slab_table">
                        
                    </tbody>
                </table>
                
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
            </div>
        </div>
    </div>
</div>

<div class="modal fade" id="editModal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                <h4 class="modal-title" id="exampleModalLabel">Incentive Slabs</h4>
            </div>
            <div class="modal-body">
                <form method="POST" >
                    <input type="hidden" name="id" id="interval_id">
                    <div class="col-sm-6 form-group">
                        <label for="from_date">From:</label>
                        <input type="date" class="form-control" id="e_from_date" name="from_date" placeholder="Selct From Date" required>
                    </div>
                    <div class="col-sm-6 form-group">
                        <label for="from_date">To:</label>
                        <input type="date" class="form-control" id="e_to_date" name="to_date" placeholder="Selct From Date" required>
                    </div>
                    <div class="col-sm-8 form-group">
                        <button type="submit" style="margin-right: 0px;" class="btn btn-success btn-block">Save Incentive Slab</button>
                    </div>
                </form>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
            </div>
        </div>
    </div>
</div>
<script>
    $("#from_date, #to_date").change(function(){
        if ($("#to_date").val() && $("#from_date").val() && ($("#from_date").val() > $("#to_date").val())){
            alert("From date cant be after To date");
            $(this).val('');
            $(this).focus();
        }
    });
    $("#add_more").click(function(){
        var html = '<div class="col-sm-6 form-group"><label for="amount">Amount:</label><input type="number" class="form-control" id="amount" name="amount[]" required></div><div class="col-sm-6 form-group" ><label for="percentage">Percentage:</label><input type="number" min="0" max="100" step="0.01" class="form-control" id="percentage" name="percentage[]" placeholder="Enter Percentage" required></div>';
        $("#add_more").before(html);
    });
    $('.modalButton').click(function(){
        $(".se-pre-con").show();
        var id = $(this).data('id') // Extract info from data-* attributes
        $.get("<?php echo base_url(); ?>admin/get_incentive_slabs/"+id, function(response){
            $("#incentive_slab_table").html(response);
            $(".se-pre-con").hide();
        })
        
    });
    
    $('.editButton').click(function(){
        var id = $(this).data('id');
        $("#interval_id").val(id);
        $("#e_from_date").val($("#row_"+id+" td:nth-child(2)").html());
        $("#e_to_date").val($("#row_"+id+" td:nth-child(3)").html());
    });
</script>