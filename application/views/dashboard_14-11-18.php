<?php 
    defined('BASEPATH') OR exit('No direct script access allowed');
    $this->load->view('inc/header'); 
?>
<?php 
if(!$this->session->userdata('permissions') && $this->session->userdata('permissions')=='' ) {
    ?>
    <style type="text/css">
    .alrtMsg{padding-top: 50px;}
    .alrtMsg i {
        font-size: 60px;
        color: #f1c836;
    }
    </style>
    <div class="container"> 
        <div class="row"> 
            <div class="text-center alrtMsg">
                <i class="fa fa-exclamation-triangle"></i>
                <h3>You Do Not have permission as of now. Please contact your Administration and Request for Permission.</h3>
            </div>
        </div>
    </div>
    <?php
} 
else 
{
    ?>
    <?php if ($this->session->userdata('user_type')=="user") { ?>
        <div class="container"> 
            <div class="col-md-8">
                <div class="row top-mg dash-wd">
                    <div class="col-md-4 ctr">
                        <h2>Todays Calls</h2>
                        <div class="panel panel-default" style="width:100%">
                            <div class="panel-body" >
                                <a href="#" class="view_callbacks" data-type="user_total"><?php echo $today_callback_count; ?></a>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-4 ctr">
                        <h2>Yesterday calls</h2>
                        <div class="panel panel-default" style="width:100%">
                            <div class="panel-body" >
                                <?php echo $yesterday_callback_count; ?>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-4 ctr">
                        <h2>Overdue calls</h2>
                        <div class="panel panel-default" style="width:100%">
                            <div class="panel-body" >
                                <a href="#" class="view_callbacks" data-type="user_overdue"><?php echo $overdue_callback_count; ?></a>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="row top-mg dash-wd">
                    <ul class="nav nav-tabs">
                        <li class="active"><a data-toggle="tab" href="#menu1">Important Calls</a></li>
                        <li><a data-toggle="tab" href="#menu2">Site Visit Fixed</a></li>
                        <!-- <li><a data-toggle="tab" href="#menu3">Menu 2</a></li> -->
                    </ul>

                    <div class="tab-content">
                        <div id="menu1" class="tab-pane fade in active">
                            <table class="table" style="margin-top: 30px;">
                                <thead>
                                    <tr>
                                        <th>Contact Name</th>
                                        <th>Assigned User</th>
                                        <th>Email</th>
                                        <th>Last added Note</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php foreach ($imp_callbacks as $callback) { ?>
                                        <tr>
                                            <td><a href="<?php echo base_url().'dashboard/view_callbacks/'.$user_id; ?>" data-type="user_important" data-id="<?php echo $callback->id; ?>"><?php echo $callback->name; ?></a></td>
                                            <td><?php echo $callback->user_name; ?></td>
                                            <td>
                                                <?php 
                                                    echo $callback->email1; 
                                                    if($callback->email2)
                                                        echo ", ".$callback->email2;
                                                ?>
                                            </td>
                                            <td><?php echo $callback->last_note; ?></td>
                                        </tr>
                                    <?php } ?>
                                        
                                </tbody>
                            </table>
                        </div>
                        <div id="menu2" class="tab-pane fade">
                            <?php if(count($site_visit_data)>0) { ?>
                            <a href="javascript:void(0);" class="btn btn-info pull-right emailSiteVisit" style="margin-top: 15px;">Email this</a>
                            <?php } ?><br/>
                            <table class="table">
                                <thead>
                                    <tr>
                                        <th>Contact Name</th>
                                        <th>Date of Site Visit</th>
                                        <th>Project Name</th>
                                        <!-- <th>Lastest Comment</th> -->
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php  
                                    if(count($site_visit_data)>0) {                                   
                                        foreach ($site_visit_data as $k=>$data) { 
                                            if($data['id'] != $site_visit_data[$k+1]['id']) {
                                            ?>
                                            <tr>
                                                <td><?php echo $data['name']; ?></td>
                                                <td><?php echo $data['visitDate']; ?></td>
                                                <td>
                                                    <?php echo implode(', ', $site_visit_projects[$data['id']]);?>
                                                </td>
                                                <!-- <td><?php //echo $callback->last_note; ?></td> -->
                                            </tr>
                                            <?php 
                                            }   
                                                                                 
                                        }
                                    }
                                    else
                                        echo '<tr><td colspan="3">No records found!</td></tr>';
                                    ?>                                        
                                </tbody>
                            </table>
                        </div>
                        <div id="menu3" class="tab-pane fade">
                            <?php if(!empty($incentive_slabs)) { ?>
                                <h2>Incentive Slabs</h2>
                                <h3 style="font-size: 15px !important;">From: <?php echo $incentive_slabs[0]->from; ?></h3>
                                <h3 style="font-size: 15px !important;">To: <?php echo $incentive_slabs[0]->to; ?></h3>
                                <table class="table">
                                    <thead>
                                        <tr>
                                            <th>Amount</th>
                                            <th>Percentage</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <?php foreach ($incentive_slabs as $slab) { ?>
                                            <tr>
                                                <td><?php echo $slab->amount; ?></td>
                                                <td><?php echo $slab->percentage; ?></td>
                                            </tr>
                                        <?php } ?>
                                            
                                    </tbody>
                                </table>
                            <?php } ?>
                        </div>
                        
                    </div>
                </div>
            </div>
            <div class="col-md-4">
                <div class="row top-mg dash-wd">
                    <div class="col-md-12 ctr">
                        <h2>Total Leads</h2>
                        <div class="panel panel-default" style="width:100%">
                            <div class="panel-body" >
                                <?php echo $total_callback_count; ?>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-12 ctr">
                        <h2>Dead Leads</h2>
                        <div class="panel panel-default" style="width:100%">
                            <div class="panel-body" >
                                <?php echo $dead_leads_count; ?>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-12 ctr">
                        <h2>Closed Leads</h2>
                        <div class="panel panel-default" style="width:100%">
                            <div class="panel-body" >
                                <a href="#" class="view_callbacks" data-type="user_close"><?php echo $close_leads_count; ?></a>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-12 ctr">
                        <h2>Active Leads</h2>
                        <div class="panel panel-default" style="width:100%">
                            <div class="panel-body" >
                                <a href="#" class="view_callbacks" data-type="user_active"><?php echo $active_leads_count; ?></a>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-12 ctr">
                        <h2>Total Customer Register</h2>
                        <div class="panel panel-default" style="width:100%">
                            <div class="panel-body" >
                                <?php echo $client_reg_count; ?>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-12 ctr">
                        <h2>Revenue Generated</h2>
                        <div class="panel panel-default" style="width:100%">
                            <div class="panel-body" >
                                <?php echo $total_revenue; ?>
                            </div>
                        </div>
                    </div>
                    <?php if($target) { ?>
                        <div class="col-md-12 ctr">
                            <h2>Target for the month</h2>
                            <div class="panel panel-default" style="width:100%">
                                <div class="panel-body" >
                                    <?php echo $target; ?>
                                </div>
                            </div>
                        </div>
                    <?php } ?>
                </div>
            </div>
            <div class="col-md-3 ctr">
                <h2>Reporting Manager Name</h2>
                <div class="panel panel-default" style="width:100%">
                    <div class="panel-body" >
                        <?php echo $manager_name; ?>
                    </div>
                </div>
            </div>
            <div class="col-md-3 ctr" style="display: none;">
                <h2>Reporting Manager Contact No</h2>
                <div class="panel panel-default" style="width:100%">
                    <div class="panel-body" >A Basic Panel</div>
                </div>
            </div>
        </div>
    <?php } elseif ($this->session->userdata('user_type')=="manager") { ?>
        <div class="container"> 
            <div class="top-mg dash-wd">
                <div class="col-md-6">
                    <div class="row top-mg dash-wd">
                        <h2>Productivity</h2>
                        <div class="col-md-12 ctr">
                            <h2>Important calls</h2>
                            <table class="table">
                                <thead>
                                    <tr>
                                        <th>Contact Name</th>
                                        <th>Assigned User</th>
                                        <th>Email</th>
                                        <th>Last added Note</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php foreach ($imp_callbacks as $callback) { ?>
                                        <tr>
                                            <td><a href="<?php echo base_url().'dashboard/view_callbacks/'.$callback->id; ?>" data-type="user_important" data-id="<?php echo $user_id; ?>"><?php echo $callback->name; ?></a></td>
                                            <td><?php echo $callback->user_name; ?></td>
                                            <td>
                                                <?php 
                                                    echo $callback->email1; 
                                                    if($callback->email2)
                                                        echo ", ".$callback->email2;
                                                ?>
                                            </td>
                                            <td><?php echo $callback->last_note; ?></td>
                                        </tr>
                                    <?php } ?>
                                        
                                </tbody>
                            </table>
                            <div class="clearfix"></div>

                            <?php if(!empty($incentive_slabs)) { ?>
                                <h2>Incentive Slabs</h2>
                                <h3 style="font-size: 15px !important;">From: <?php echo $incentive_slabs[0]->from; ?></h3>
                                <h3 style="font-size: 15px !important;">To: <?php echo $incentive_slabs[0]->to; ?></h3>
                                <table class="table">
                                    <thead>
                                        <tr>
                                            <th>Amount</th>
                                            <th>Percentage</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <?php foreach ($incentive_slabs as $slab) { ?>
                                            <tr>
                                                <td><?php echo $slab->amount; ?></td>
                                                <td><?php echo $slab->percentage; ?></td>
                                            </tr>
                                        <?php } ?>
                                            
                                    </tbody>
                                </table>
                            <?php } ?>
                        </div>
                    </div>
                    <div class="row top-mg dash-wd">
                        <h2>Lead Source Report</h2>
                        <div class="col-md-12 ctr">
                            <table class="table">
                                <thead>
                                    <tr>
                                        <th>Lead Source</th>
                                        <th>Count</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php foreach ($lead_source_report as $key => $value) { ?>
                                        <tr>
                                            <td><?php echo $this->common_model->get_leadsource_name($key); ?></td>
                                            <td><?php echo $value; ?></td>
                                        </tr>
                                    <?php } ?>
                                        
                                </tbody>
                            </table>
                        </div>
                    </div>
                    <div class="row top-mg dash-wd">
                        <h2>Call Reports</h2>
                        <div class="col-md-12 ctr">
                            <table class="table">
                                <thead>
                                    <tr>
                                        <th>User Name</th>
                                        <th>Number of calls</th>
                                        <th>Calls done Yesterday</th>
                                        <th>Calls for Today</th>
                                        <th>Productivity</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php foreach ($call_reports as $key => $value) { ?>
                                        <tr>
                                            <td><?php echo $value->first_name." ".$value->last_name; ?></td>
                                            <td><?php echo $value->total_calls; ?></td>
                                            <td><?php echo $value->yesterday_callback_count; ?></td>
                                            <td><?php echo $value->today_callback_count; ?></td>
                                            <td><?php echo $value->productivity; ?> %</td>
                                        </tr>
                                    <?php } ?>
                                        
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
                <div class="col-md-3">
                    <div class="row top-mg dash-wd">
                        <h2>Live Status</h2>
                    </div>
                </div>
                <div class="col-md-3">        
                    <div class="col-md-12 ctr">
                        <h2>Total Team Member</h2>
                        <div class="panel panel-default" style="width:100%">
                            <div class="panel-body" >
                                <?php echo $total_team_members; ?>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-12 ctr">
                        <h2>Total Calls for Team</h2>
                        <div class="panel panel-default" style="width:100%">
                            <div class="panel-body" >
                                <a href="<?php echo base_url().'view_callbacks?advisor='.$team_members; ?>"><?php echo $total_calls; ?></a>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-12 ctr">
                        <h2>Own Calls</h2>
                        <div class="panel panel-default" style="width:100%">
                            <div class="panel-body" >
                                <?php echo $total_callback_count; ?>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-12 ctr">
                        <h2>Own Active Leads</h2>
                        <div class="panel panel-default" style="width:100%">
                            <div class="panel-body" >
                                <a href="#" class="view_callbacks" data-type="manager_active"><?php echo $total_active_callback_count; ?></a>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-12 ctr">
                        <h2>Own Closed Calls</h2>
                        <div class="panel panel-default" style="width:100%">
                            <div class="panel-body" >
                                <a href="#" class="view_callbacks" data-type="manager_close"><?php echo $close_leads_count; ?></a>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-12 ctr">
                        <h2>Own Call Revenue</h2>
                        <div class="panel panel-default" style="width:100%">
                            <div class="panel-body" >
                                <?php echo $total_revenue?$total_revenue:0; ?>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-12 ctr">
                        <h2>Team evenue</h2>
                        <div class="panel panel-default" style="width:100%">
                            <div class="panel-body" >
                                <?php echo $total_team_revenue?$total_team_revenue:0; ?>
                            </div>
                        </div>
                    </div>
                    <?php if($target) { ?>
                        <div class="col-md-12 ctr">
                            <h2>Target for the month</h2>
                            <div class="panel panel-default" style="width:100%">
                                <div class="panel-body" >
                                    <?php echo $target; ?>
                                </div>
                            </div>
                        </div>
                    <?php } ?>
                </div>
            </div>
        </div>
    <?php }else { ?>
        <div class="container"> 
            <div class="top-mg dash-wd">
                <div class="col-md-5">
                    <div class="row top-mg dash-wd">
                        <h2>Productivity</h2>
                        <div class="col-md-12 ctr">
                            <table class="table">
                                <thead>
                                    <tr>
                                        <th>User Name</th>
                                        <th>Number of calls</th>
                                        <!-- <th>Calls done Yesterday</th>
                                        <th>Calls for Today</th>
                                        <th>Productivity</th> -->
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php foreach ($productivity_report as $key => $value) { ?>
                                        <tr>
                                            <td><?php echo $value->first_name." ".$value->last_name; ?></td>
                                            <td>
                                                <a href="<?php echo base_url().'view_callbacks?advisor='.$value->id.'&for=dashboard'; ?>"><?php echo $value->total_calls; ?></a>
                                            </td>
                                            <!-- <td><?php echo $value->yesterday_callback_count; ?></td>
                                            <td><?php echo $value->today_callback_count; ?></td>
                                            <td><?php echo $value->productivity; ?> %</td> -->
                                        </tr>
                                    <?php } ?>
                                        
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
                <div class="col-md-4">
                    <div class="row top-mg dash-wd">
                        <h2>Live Feedback<button type="submit" class="btn btn-default" id="refresh">Refresh</button></h2>
                        <div class="col-md-12 ctr">
                            <table class="table">
                                <thead>
                                    <tr>
                                        <th>User Name</th>
                                        <th>Last Login Time</th>
                                    </tr>
                                </thead>
                                <tbody id="live_feed_back_body">
                                    <?php foreach ($live_feed_back as $key => $value) { ?>
                                        <tr>
                                            <td><?php echo $value->first_name." ".$value->last_name; ?> (<?php echo ($value->type == 1)?'User':'Manager'; ?>)</td>
                                            <td>
                                                <?php echo $value->last_login; ?>
                                            </td>
                                        </tr>
                                    <?php } ?>
                                        
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
                <div class="col-md-3">
                    <div class="col-md-12 ctr">
                        <h2>Overdue Leads</h2>
                        <div class="panel panel-default" style="width:100%">
                            <div class="panel-body" >
                                <a href="#" id="overdue_lead_count" data-datetime="<?php echo date('Y-m-d H:i:s'); ?>"><?php echo $overdue_lead_count; ?></a>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-12 ctr">
                        <h2>Due for today</h2>
                        <div class="panel panel-default" style="width:100%">
                            <div class="panel-body" >
                                <a href="<?php echo base_url().'view_callbacks?due_date='.urlencode(date('Y-m-d')).'&for=dashboard'; ?>"><?php echo $today_callback_count; ?></a>
                                
                            </div>
                        </div>
                    </div>
                    <div class="col-md-12 ctr">
                        <h2>Active Leads</h2>
                        <div class="panel panel-default" style="width:100%">
                            <div class="panel-body" >
                                <a href="<?php echo base_url().'view_callbacks?for=dashboard'; ?>"><?php echo $total_callback_count; ?></a>
                            
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="top-mg dash-wd">
                <div class="col-md-6">
                    <div class="row top-mg dash-wd">
                        <h2>Source Analysis</h2>
                        <div class="col-md-12 ctr">
                            <table class="table">
                                <thead>
                                    <tr>
                                        <th>Lead Source</th>
                                        <th>Count</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php foreach ($lead_source_report as $key => $value) { ?>
                                        <tr>
                                            <td><?php echo $this->common_model->get_leadsource_name($key); ?></td>
                                            <td><?php echo $value; ?></td>
                                        </tr>
                                    <?php } ?>
                                        
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
                <div class="col-md-3">
                    <div class="row top-mg dash-wd">
                        <h2>Revenue List</h2>
                        <input type="text" class="form-control" id="revenueMonth" name="email2" placeholder="Click to filter" value="<?php echo date('m/Y'); ?>" > <button id="filter_revenue" onclick="get_revenues();">Filter</button>
                        <br>
                        <div class="col-md-12 ctr">
                            <table class="table">
                                <thead>
                                    <tr>
                                        <th>Customer Name</th>
                                        <th>User name</th>
                                        <th>Project</th>
                                        <th>Net Revenue</th>
                                        <th>Status</th>
                                    </tr>
                                </thead>
                                <tbody id="revenue_data">
                                        
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
                <div class="col-md-3">
                    <div class="row top-mg dash-wd">
                        <h2>Name/State/</h2>
                    </div>
                </div>
            </div>
        </div>
    <?php }?>
    <?php 
} 
?>
		
<script>
    $(document).ready(function() {
        $('#example').DataTable();
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
        $('#revenueMonth').MonthPicker({
            Button: false
        });
        get_revenues();

        $('.view_callbacks').click(function(){
            var type = $(this).data('type');
            var data = {};
            switch (type)
            {
                case "user_total":
                    data.advisor = "<?php echo $user_id; ?>";
                    data.due_date = "<?php echo date('Y-m-d'); ?>";
                    data.access = 'read_write'; 
                    break;

                case "user_overdue":
                    data.advisor = "<?php echo $user_id; ?>";
                    data.due_date_to = "<?php echo date('Y-m-d H:i:s'); ?>";
                    data.for = "dashboard";
                    data.access = 'read_write'; 
                    break;

                case "user_active": 
                    data.advisor = "<?php echo $user_id; ?>";
                    data.for = "dashboard";
                    data.access = 'read_write'; 
                    break;

                case "user_close": 
                    data.advisor = "<?php echo $user_id; ?>";
                    data.status = "close";
                    break;

                case "user_important":
                    data.advisor = "<?php echo $user_id; ?>";
                    data.access = 'read_write'; 
                    data.important = 1;
                    break;

                case "manager_active": 
                    data.advisor = "<?php echo $user_id; ?>";
                    data.for = "dashboard";
                    data.access = 'read_write'; 
                    break;

                case "manager_close":
                    data.advisor = "<?php echo $user_id; ?>";
                    data.status = "close";
                    break;
            }
            
            view_callbacks(data,'post');

        });

        $("#refresh").click(function(){
            $(".se-pre-con").show();
            $.get("<?php echo base_url(); ?>dashboard/get_live_feed_back", function(response){
                $("#live_feed_back_body").html(response);
                $(".se-pre-con").hide("slow");
            });
        });

        $("#overdue_lead_count").click(function(){
            var form = document.createElement('form');
            form.method = "POST";
            form.action = "<?php echo base_url()."dashboard/generate_report" ?>";
            
            var input = document.createElement('input');
            input.type = "text";
            input.name = "toDate";
            input.value = $(this).data('datetime');
            form.appendChild(input);

            input = document.createElement('input');
            input.type = "text";
            input.name = "reportType";
            input.value = "due";
            form.appendChild(input);

            document.body.appendChild(form);
            form.submit();
        });

        $('.emailSiteVisit').on('click', function(){
            $(".se-pre-con").show();
            $.ajax({
                type : 'POST',
                url : "<?= base_url('site-visit-report-mail');?>",
                data:1,
                success: function(res){
                    $(".se-pre-con").hide("slow");
                    if(res == 1)
                        alert('Email Sent Successfully.');
                    else
                        alert('Email Sent fail!');
                }
            });
        });

    });
    // $('#filter_revenue').click(get_revenues());
    function get_revenues(){
        $.get( "<?php echo base_url()."dashboard/get_revenue/" ?>"+$('#revenueMonth').val(), function( data ) {
            $('#revenue_data').html(data);
        });
    }
    function view_callbacks(data, method) {
        var form = document.createElement('form');
        form.method = method;
        form.action = "<?php echo base_url()."view_callbacks?" ?>"+jQuery.param(data);
        for (var i in data) {
            var input = document.createElement('input');
            input.type = "text";
            input.name = i;
            input.value = data[i];
            form.appendChild(input);
        }
        //console.log(form);
        document.body.appendChild(form);
        form.submit();
    }

</script>