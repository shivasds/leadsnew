<?php 
    defined('BASEPATH') OR exit('No direct script access allowed');
    $this->load->view('inc/header'); 
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
    </div>
    <form method="POST" id="search_form">
        <?php if($this->session->userdata("user_type")=="manager") { ?>
            <input type="hidden" name="self" id="self_input" value="<?php echo $this->session->userdata('self'); ?>">
            <div class="col-md-3 form-group">
                <label for="emp_code">Advisor:</label>
                <select  class="form-control"  id="dept" name="advisor" >
                    <option value="">Select</option>
                    <?php $all_advisor=$this->common_model->all_active_advisors();
                    foreach($all_advisor as $advisor){ ?>
                        <option value="<?php echo $advisor->id; ?>" <?php if(($this->session->userdata("advisor"))==$advisor->id) echo 'selected' ?>><?php echo $advisor->first_name." ".$advisor->last_name; ?></option>
                    <?php }?>             
                </select>
            </div>            
        <?php } ?>
        <?php if($this->session->userdata("user_type")!="user") { ?>
            <div class="col-md-3 form-group">
                <label for="emp_code">Department:</label>
                <select  class="form-control"  id="dept" name="dept" >
                    <option value="">Select</option>
                    <?php $all_department=$this->common_model->all_active_departments();
                    foreach($all_department as $department){ ?>
                        <option value="<?php echo $department->id; ?>" <?php if(($this->session->userdata("department"))==$department->id) echo 'selected' ?>><?php echo $department->name; ?></option>
                    <?php }?>             
                </select>
            </div>
        <?php } ?>
        <div class="col-md-3 form-group">
            <label for="emp_code">Project:</label>
            <select  class="form-control"  id="project" name="project" >
                <option value="">Select</option>
                <?php $projects= $this->common_model->all_active_projects(); 
                foreach( $projects as $project){ ?>
                    <option value="<?php echo $project->id ?>" <?php if(($this->session->userdata("project"))==$project->id) echo 'selected' ?>><?php echo $project->name ?></option>
                <?php }?>              
            </select>
        </div>
        <?php if($this->session->userdata("user_type")!="user") { ?>
            <div class="col-md-3 form-group">
                <label for="assign">Lead Source:</label>
                <select  class="form-control"  id="lead_source" name="lead_source" >
                    <option value="">Select</option>
                    <?php $lead_source= $this->common_model->all_active_lead_sources(); 
                    foreach( $lead_source as $source){ ?>
                        <option value="<?php echo $source->id ?>" <?php if(($this->session->userdata("lead_source"))==$source->id) echo 'selected' ?>><?php echo $source->name ?></option>
                    <?php } ?>             
                </select>
            </div>
            <div class="col-md-3 form-group">
                <label for="assign">Sub Broker:</label>
                <select  class="form-control"  id="sub_broker" name="sub_broker" >
                    <option value="">Select</option>
                    <?php $brokers= $this->common_model->all_active_brokers(); 
                    foreach( $brokers as $broker){ ?>
                        <option value="<?php echo $broker->id; ?>" <?php if(($this->session->userdata("sub_broker"))==$broker->id) echo 'selected' ?>><?php echo $broker->name ?></option>
                    <?php } ?>              
                </select>
            </div>
        <?php } ?>
        <div class="col-md-3 form-group">
            <label for="assign">Status:</label>
            <select  class="form-control"  id="status" name="status" >
                <option value="">Select</option>
                <?php $statuses= $this->common_model->all_active_statuses(); 
                foreach( $statuses as $status){ ?>
                    <option value="<?php echo $status->id; ?>" <?php if(($this->session->userdata("status"))==$status->id) echo 'selected' ?>><?php echo $status->name ?></option>
                <?php } ?>
            </select>
        </div>
        <?php if($this->session->userdata("user_type")!="user") { ?>
            <div class="col-md-3 form-group">
                <label for="assign">City:</label>
                <select  class="form-control"  id="city" name="city" >
                    <option value="">Select</option>
                    <?php $cities= $this->common_model->all_active_cities(); 
                    foreach( $cities as $city){ ?>
                        <option value="<?php echo $city->id; ?>" <?php if(($this->session->userdata("city"))==$city->id) echo 'selected' ?>><?php echo $city->name ?></option>
                    <?php } ?>               
                </select>
            </div>
        <?php } ?>
        <?php if(($this->session->userdata("user_type")=="vp") || ($this->session->userdata("user_type")=="director")) { ?>
            <div class="col-md-3 form-group">
                <label for="assign">User Name:</label>
                <select  class="form-control"  id="user_name" name="user_name" >
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
                        <option value="<?php echo $user->id ?>" <?php if(($this->session->userdata("search_username"))==$user->id) echo 'selected' ?>><?php echo $user->first_name." ".$user->last_name." ($role)"; ?></option>
                    <?php } ?>
                </select>
            </div>
        <?php } /*if($this->session->userdata("user_type")=="vp" || $this->session->userdata("user_type")=="director") {*/?>
            <div class="col-sm-3">
                <div class="form-group">
                    <label>Search:</label>
                    <input type="text" class="form-control" name="srxhtxt" id="srxhtxt" placeholder="Enter search text" value="<?= ($this->session->userdata('SRCHTXT')) ? $this->session->userdata('SRCHTXT') : '' ?>" />
                </div>
            </div>
            <div class="col-sm-3">
                <div class="form-group">
                    <label>Due Date</label>
                    <select  class="form-control" name="searchDate" id="searchDate">
                        <option value="">--Select--</option>
                        <option value="today" <?= ($this->session->userdata('SRCHDT')== "today")? 'selected':''; ?>>Due date</option>
                        <option value="yesterday" <?= ($this->session->userdata('SRCHDT')== "yesterday")? 'selected':''; ?>>Overdue </option>
                        <option value="tomorrow" <?= ($this->session->userdata('SRCHDT')== "tomorrow")? 'selected':''; ?>>Upcoming calls</option>
                    </select>
                </div>
            </div>
        <?php //} ?>
        <div class="col-sm-3 form-group">
            <button class="btn btn-info btn-block" id="reset" onclick="reset_data()" style="margin-top: 24px;" >Reset</button>
        </div>
        <div class="col-sm-3 form-group">
            <button type="submit" id="search" class="btn btn-success btn-block" style="margin-top: 24px;" >Search</button>
        </div>
    </form>
    <div class="clearfix"></div>
    <br>
    <?php if($this->session->userdata("user_type")=="manager") { ?>
        Now showing <?php echo ($this->session->userdata('self') == "1")?"self":"teams"; ?> callbacks. <a href="#" id="change_callbacks">Change</a>
    <?php } ?>
    <br>
    <table id="example" class="table table-striped table-bordered dt-responsive " cellspacing="0" width="100%" >
        <thead>
            <tr>
                <th>No</th>
                <th>Contact Name</th> 
                <th>Contact No</th>
                <th>Email</th>
                <th>Project</th>
                <?php if($this->session->userdata("user_type")!="user") { ?>
                    <th>Lead Source</th>
                    <th>Lead Id</th> 
                <?php } ?>
                <th>Advisor</th>
                <?php if($this->session->userdata("user_type")!="user") { ?> 
                    <th>Sub-Source</th>
                <?php } ?>
                <th>Due date</th>
                <th>Status</th>
                <th>Date Added</th>
                <!-- <th>Last Update</th> -->
                <th>Action</th>
            </tr>
        </thead> 
        <tbody id="main_body">
            <?php $i= 1;
            if(count($result)>0){
            foreach ($result as $data) {
                $duedate = explode(" ", $data->due_date);
                $duedate = $duedate[0]; ?>
                <tr id="row<?php echo $i ?>" <?php if(strtotime($duedate)<strtotime('today')){?> class="highlight_past" <?php }elseif(strtotime($duedate) == strtotime('today')) {?> class="highlight_now" <?php }elseif(strtotime($duedate)>strtotime('today')){ ?> class="highlight_future" <?php } ?>>
                    <td><?php echo $i; ?></td>
                    <td><?php echo $data->name; ?></td>
                    <td><?php echo $data->contact_no1 ?></td>
                    <td><?php echo $data->email1; ?></td>
                    <td><?php echo $data->project_name; ?></td>
                    <?php if($this->session->userdata("user_type")!="user") { ?>
                        <td><?php echo $data->lead_source_name; ?></td>
                        <td><?php echo $data->leadid; ?></td>
                    <?php } ?>
                    <td><?php echo $data->user_name; ?></td>
                    <?php if($this->session->userdata("user_type")!="user") { ?>
                        <td><?php echo $data->broker_name; ?></td>
                    <?php } ?>
                    <td class="due_date"><?php echo $data->due_date; ?></td>
                    <td><?php echo $data->status_name; ?></td>
                    <td><?php echo $data->date_added; ?></td>
                    <!-- <td><?php echo $data->last_update; ?></td> -->
                    <td>
                        <table>
                            <tr>
                                <td>
                                    <a onclick="edit('<?php echo $data->id; ?>')" data-toggle="modal" data-target="#modal_edit">
                                        <i class="fa fa-home fa-2x"  title="Detail" style="color:#ff1122; font-size:21px;padding-right:7px;" aria-hidden="true"></i>
                                    </a>
                                </td>
                                <td>
                                    <a onclick="previous_callbacks('<?php echo $data->id; ?>')" data-toggle="modal" data-target="#modal_previous">
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
</div>
    
<div style="margin-top: 20px">
    <span class="pull-left"><p>Showing <?php echo ($this->uri->segment(2)) ? $this->uri->segment(2)+1 : 1; ?> to <?= ($this->uri->segment(2)+count($result)); ?> of <?= $totalRecords; ?> entries</p></span>
    <ul class="pagination pull-right"><?php echo $links; ?></ul>
</div>

<?php $this->load->view('callback_operations'); ?>
 


<script type="text/javascript">

    $(document).ready(function() {
        //$('#example').DataTable();
        if (!Modernizr.inputtypes.date) {
            // If not native HTML5 support, fallback to jQuery datePicker
            $('input[type=date]').datepicker({
                // Consistent format with the HTML5 picker
                    dateFormat : 'dd/mm/yy'
                }
            );
        }
        if (!Modernizr.inputtypes.time) {
            // If not native HTML5 support, fallback to jQuery timepicker
            $('input[type=time]').timepicker({ 'timeFormat': 'H:i' });
        }
        $('#c_bkngMnth, #c_estMonthofInvoice').MonthPicker({
            Button: false
        });

        $('#change_callbacks').click(function(){
            $("#self_input").val(($("#self_input").val() == "0")?"1":"0");
            $("#search_form").submit();
        });


    });

    function reset_data(){
        $('#dept').val("");
        $('#project').val("");
        $('#lead_source').val("");
        $('#sub_broker').val("");
        $('#status').val("");
        $('#city').val("");
        $('#user_name').val("");
        $('#searchDate').val("");
        $('#srxhtxt').val("");
        $("#search_form").submit();
    }
    
</script> 
      
      
</body>

</html>