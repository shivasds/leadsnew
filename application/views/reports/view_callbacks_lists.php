<?php 
    defined('BASEPATH') OR exit('No direct script access allowed');
    $this->load->view('inc/admin_header'); 
?>

<style>
    @media screen and (min-width: 768px) {
        modal_
        .modal-dialog  {
            width:900px;
        }
    }
    .form-group input[type="checkbox"] {
        display: none;
    }
    .form-group input[type="checkbox"] + .btn-group > label span {
        width: 20px;
    }
    .form-group input[type="checkbox"] + .btn-group > label span:first-child {
        display: none;
    }
    .form-group input[type="checkbox"] + .btn-group > label span:last-child {
        display: inline-block;   
    }
    .form-group input[type="checkbox"]:checked + .btn-group > label span:first-child {
        display: inline-block;
    }
    .form-group input[type="checkbox"]:checked + .btn-group > label span:last-child {
        display: none;   
    }
    tr.highlight_past td.due_date{
        background-color: #cc6666 !important;
    }
    tr.highlight_now td.due_date{
        background-color: #e4b13e !important;
    }
    tr.highlight_future td.due_date{
        background-color: #65dc68 !important;
    }
    #history_table td {
        border: 1px solid #aaa;
        padding: 5px
    }
</style>

<div class="container">
  
    <div class="page-header">
        <h1><?php echo $heading; ?></h1>
        <p class="text-center"><?php echo $duration; ?></p>
    </div>
   
<table id="example" class="table-bordered dt-responsive dataTable no-footer" cellspacing="0" width="100%" >
    <thead>
        <tr id="tableheading">
            <th>No</th>
            <th>Contact Name</th> 
            <th>Contact No</th>
            <th>Email</th>
            <th>Project</th>
            <th>Lead Source</th>            
            <th>Action</th>
        </tr>
    </thead> 
    <tbody id="main_body">
        <?php $i= 1;
        if(count($result)>0){
        foreach ($result as $data) {
        	?>
            <tr id="row<?php echo $i ?>">
                <td><?php echo $i; ?></td>
                <td><?php echo $data['name']; ?></td>
                <td><?php echo $data['contact_no1'] ?></td>
                <td><?php echo $data['email1'] ? $data['email1'] : 'No email found!'; ?></td>
                <td><?php echo $data['projectName']; ?></td>
                <td><?php echo $data['leadName']; ?></td>
                <td>
                    <table>
                        <tr id="background">                            
                            <td>
                                <a onclick="abc('<?php echo $data['id']; ?>')" data-toggle="modal" data-target="#modal_notes">
                                    <i class="fa fa-keyboard-o fa-2x" title="Notes" style="color:#ff1122; font-size:21px;padding-right:7px;" aria-hidden="true"></i>
                                </a>
                            </td>
                            
                        </tr>
                    </table>
                </td>
            </tr>
        <?php $i++; } }?>
    </tbody>
</table>
<div style="margin-top: 20px">
    <span class="pull-left"><p>Showing <?php echo ($this->uri->segment(3)) ? $this->uri->segment(3)+1 : 1; ?> to <?= ($this->uri->segment(3)+count($result)); ?> of <?= $totalRecords; ?> entries</p></span>
    <ul class="pagination pull-right"><?php echo $links; ?></ul>
</div>

</div>

<div class="modal fade" id="modal_notes" role="dialog">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal">&times;</button>
                <h4 class="modal-title">Call back Notes</h4>
            </div>
            <div class="modal-body">
                <table id="example" class="table table-striped table-bordered dt-responsive" cellspacing="0" width="100%" >
                    <thead>
                        <tr>
                            <th>S No.</th>
                            <th>Note</th>
                            <th>Status</th>
                            <th>User</th>
                            <th>Date added</th>
                        </tr>
                    </thead>
                    <tbody id="notes_body">
                        
                    </tbody>
                </table>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
            </div>
        </div>
    </div>
</div>

<script type="text/javascript">
    $('ul.nav-tabs').find('button.btn-success').hide();
    function abc(v){
        $(".se-pre-con").show();
        $.ajax({
            type:"POST",
            url: "<?php echo base_url()?>admin/get_notes",
            data:{id:v},
            success:function(data){
                $('#notes_body').html(data);
                $(".se-pre-con").hide("slow");
            }
        });
    }    
</script> 
      
      
</body>

</html>