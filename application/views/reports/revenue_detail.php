<?php 
    defined('BASEPATH') OR exit('No direct script access allowed');
    if($this->session->userdata("user_type") == "admin")
        $this->load->view('inc/admin_header');
    else
        $this->load->view('inc/header');
?>
<style type="text/css">
	.display td {
	    border: 1px solid #aaa;
	    padding: 5px
	  }
</style>
</head>
<body>
	<div class="container">
		<div class="page-header">
		  <h1><?php echo $heading; ?></h1>
		</div>
		<form method="POST">
			<div class="col-md-3 form-group">
                <input type="hidden" id="mhid">
                <label for="emp_code">Dept:</label>
                <input type="text" class="form-control" id="m_name1" placeholder="Name" required="required" value="<?php echo $details->department; ?>" disabled>
            </div>
            <div class="col-sm-3 form-group">
                <label for="name">Name:</label>
                <input type="text" class="form-control" id="m_name1" placeholder="Name" required="required" value="<?php echo $details->name; ?>" disabled>
            </div>
            <div class="col-sm-3 form-group">
                <label for="contact_no1">Contact No:</label>
                <input type="text" class="form-control" id="m_contact_no1" placeholder="Contact No" value="<?php echo $details->contact_no1; ?>" disabled>
            </div>
            <div class="col-sm-3 form-group">
                <label for="name">Contact No 2:</label>
                <input type="text" class="form-control" id="m_contact_no2" placeholder="Contact No" value="<?php echo $details->contact_no2; ?>" disabled>
            </div>
            <div class="col-md-3 form-group">
                <label for="assign">Call back type:</label>
                <input type="text" class="form-control" id="m_contact_no2" placeholder="Contact No" value="<?php echo $details->callback_type; ?>" disabled>
            </div>
            <div class="col-sm-3 form-group">
                <label for="email">Email:</label>
                <input type="email" class="form-control" id="m_email1" placeholder="Email" value="<?php echo $details->email1; ?>" disabled>
            </div>   
            <div class="col-sm-3 form-group">
                <label for="email">Email2:</label>
                <input type="email" class="form-control" id="m_email2" placeholder="email" value="<?php echo $details->email2; ?>" disabled>
            </div>
            <div class="col-md-3 form-group">
                <label for="emp_code">Project:</label>
                <input type="text" class="form-control" id="m_contact_no2" placeholder="Contact No" value="<?php echo $details->project; ?>" disabled>
            </div>
            <div class="col-md-3 form-group">
                <label for="assign">Lead Source:</label>
                <input type="text" class="form-control" id="m_contact_no2" placeholder="Contact No" value="<?php echo $details->lead_source; ?>" disabled>
            </div>
            <div class="col-sm-3 form-group">
                <label for="leadId">Lead Id:</label>
                <input type="text" class="form-control" id="m_leadId" placeholder="Lead Id" value="<?php echo $details->leadid; ?>" disabled>
            </div>
            <div class="col-md-3 form-group">
                <label for="assign">Status:</label>
                <input type="text" class="form-control" id="m_contact_no2" placeholder="Contact No" value="<?php echo $details->status; ?>" disabled>
            </div>
            <div class="col-md-3 form-group">
                <label for="assign">Assign To:</label>
                <input type="text" class="form-control" id="m_contact_no2" placeholder="Contact No" value="<?php echo $details->user_name; ?>" disabled>
            </div>
            <div class="col-sm-6 form-group">
                <label for="email">Advisor one:</label>
                <select  class="form-control"  id="c_seniorAdvisor" name="c_seniorAdvisor" required="required"  >
                    <option value="">Select</option>
                    <?php $all_user= $this->user_model->all_users("type in (1,2)"); 
                    foreach( $all_user as $user){ 
                        switch ($user->type) {
                            case '1':
                                $role = "User";
                                break;

                            case '2':
                                $role = "Manager";
                                break;

                        }
                        ?>
                        <option value="<?php echo $user->id ?>" <?php if($revenue_details->advisor1_id == $user->id ) echo 'selected'; ?>><?php echo $user->first_name." ".$user->last_name." ($role)"; ?></option>
                    <?php } ?>               
                </select>
            </div>
            <div class="col-sm-6 form-group">
                <label for="email">Advisor two:</label>
                <select  class="form-control"  id="c_secondAdvisor" name="c_secondAdvisor" required="required"  >
                    <option value="">Select</option>
                    <?php $all_user= $this->user_model->all_users("type in (1,2)"); 
                    foreach( $all_user as $user){ 
                        switch ($user->type) {
                            case '1':
                                $role = "User";
                                break;

                            case '2':
                                $role = "Manager";
                                break;

                        }
                        ?>
                        <option value="<?php echo $user->id ?>" <?php if($revenue_details->advisor2_id === $user->id ) echo 'selected'; ?>><?php echo $user->first_name." ".$user->last_name." ($role)"; ?></option>
                    <?php } ?>               
                </select>
            </div>
            <div class="col-sm-6 form-group">
                <label for="email">Booking Name:</label>
                <input type="text" class="form-control" id="c_bkngName" name="c_bkngName" placeholder="Booking Name" value="<?php echo $revenue_details->booking; ?>">
            </div>
            <div class="col-sm-6 form-group">
                <label for="email">Booking Month:</label>
                <input type="text" class="form-control" id="c_bkngMnth" name="c_bkngMnth" placeholder="Booking Date" value="<?php echo $revenue_details->booking_month; ?>">
            </div>
            <div class="col-sm-6 form-group">
                <label for="email">Date of closure:</label>
                <input type="date" class="form-control" id="c_dateofClosure" name="c_dateofClosure" placeholder="Date of closure" value="<?php echo $revenue_details->closure_date; ?>">
            </div>
            <div class="col-sm-6 form-group">
                <label for="email">Customer name:</label>
                <input type="text" class="form-control" id="c_customerName" name="c_customerName" placeholder="Customer Name" value="<?php echo $revenue_details->customer; ?>">
            </div>
            <div class="col-sm-6 form-group">
                <label for="email">Sub Source:</label>
                <select  class="form-control"  id="c_subSource" name="c_subSource" disabled>
                    <option value="">Select</option>
                    <?php $brokers= $this->common_model->all_active_brokers(); 
                    foreach( $brokers as $broker){ ?>
                        <option value="<?php echo $broker->id; ?>" <?php if($revenue_details->sub_source_id == $broker->id) echo 'selected'; ?>><?php echo $broker->name ?></option>
                    <?php } ?>               
                </select>
            </div>
            <div class="col-sm-6 form-group">
                <label for="email">Project:</label>
                <select  class="form-control"  id="c_projectName" name="c_projectName" required="required" >
                    <option value="">Select</option>
                    <?php $projects= $this->common_model->all_active_projects(); 
                    foreach( $projects as $project){ ?>
                        <option value="<?php echo $project->id ?>" <?php if($project->id == $revenue_details->project_id) echo 'selected'; ?>><?php echo $project->name ?></option>
                    <?php }?>               
                </select>
            </div>
            <div class="col-sm-6 form-group">
                <label for="email">Sqft Sold:</label>
                <input type="text" class="form-control" id="c_sqftSold" name="c_sqftSold" placeholder="Sqft Sold" value="<?php echo $revenue_details->sqft_sold; ?>">
            </div>
            <div class="col-sm-6 form-group">
                <label for="email">PLC Charges:</label>
                <input type="text" class="form-control"  id="c_plcCharge" name="c_plcCharge" placeholder="PLC charges" value="<?php echo $revenue_details->plc_charge; ?>">
            </div>
            <div class="col-sm-6 form-group">
                <label for="email">Floor Rise:</label>
                <input type="text" class="form-control" id="c_floorRise" name="c_floorRise" placeholder="Floor Rise" value="<?php echo $revenue_details->floor_rise; ?>">
            </div>
            <div class="col-sm-6 form-group">
                <label for="email">Basic Cost:</label>
                <input type="text" class="form-control" id="c_basicCost" name="c_basicCost" placeholder="Basic Cost" value="<?php echo $revenue_details->basic_cost; ?>">
            </div>
            <div class="col-sm-6 form-group">
                <label for="email">Other Cost:</label>
                <input type="text" class="form-control" id="c_otherCost" name="c_otherCost" placeholder="Other Cost" value="<?php echo $revenue_details->other_cost; ?>">
            </div>
            <div class="col-sm-6 form-group">
                <label for="email">Car Park:</label>
                <input type="text" class="form-control" id="c_carPark" name="c_carPark" placeholder="Car Park" value="<?php echo $revenue_details->car_park; ?>">
            </div>
            <div class="col-sm-6 form-group">
                <label for="email">Total Cost:</label>
                <input type="text" class="form-control"   id="c_totalCost" name="c_totalCost" placeholder="Total Cost" value="<?php echo $revenue_details->total_cost; ?>">
            </div>

            <div class="col-sm-6 form-group">
                <label for="email">Commission(%):</label>
                <input type="text" class="form-control" id="c_comission" name="c_comission" placeholder="Commission" value="<?php echo $revenue_details->commission; ?>">
            </div>

            <div class="col-sm-6 form-group">
                <label for="email">Gross Revenue:</label>
                <input type="text" class="form-control" id="c_grossRevenue" name="c_grossRevenue" placeholder="Gros Revenue" value="<?php echo $revenue_details->gross_revenue; ?>">
            </div>
            <div class="col-sm-6 form-group">
                <label for="email">Cashback:</label>
                <input type="text" class="form-control" id="c_cashBack" name="c_cashBack" placeholder="Cash Back" value="<?php echo $revenue_details->cash_back; ?>">
            </div>
            <div class="col-sm-6 form-group">
                <label for="email">Sub broker amount:</label>
                <input type="text" class="form-control" id="c_subBrokerAmo" name="c_subBrokerAmo" placeholder="Sub Broker amount" value="<?php echo $revenue_details->sub_broker_amo; ?>">
            </div>
            <div class="col-sm-6 form-group">
                <label for="email">Net Revenue:</label>
                <input type="text" class="form-control" id="c_netRevenue" name="c_netRevenue" placeholder="Net Revenue" value="<?php echo $revenue_details->net_revenue; ?>">
            </div>
            <div class="col-sm-6 form-group">
                <label for="email">Share of advisor 1:</label>
                <input type="text" class="form-control" id="c_shareAdvisor1" name="c_shareAdvisor1" placeholder="Share of advisor 1" value="<?php echo $revenue_details->share_of_advisor1; ?>">
            </div>
            <div class="col-sm-6 form-group">
                <label for="email">Share of advisor 2:</label>
                <input type="text" class="form-control" id="c_shareAdvisor2" name="c_shareAdvisor2" placeholder="Share of advisor 2" value="<?php echo $revenue_details->share_of_advisor2; ?>">
            </div>

            <div class="col-sm-6 form-group">
                <label for="email">Estimated month of invoice:</label>
                <input type="text" class="form-control" id="c_estMonthofInvoice" name="c_estMonthofInvoice" placeholder="Estimated month of invoice" value="<?php echo $revenue_details->est_month_of_invoice; ?>">
            </div>

            <div class="col-sm-6 form-group">
                <label for="email">Agreement Status:</label>
                <input type="text" class="form-control" id="c_agrmntStatus" name="c_agrmntStatus" placeholder="Agreement Status" value="<?php echo $revenue_details->agreement_status; ?>">
            </div>

            <div class="col-sm-6 form-group">
                <label for="email">Project Type:</label>
                <input type="text" class="form-control" id="c_projectType" name="c_projectType" placeholder="Project Type" value="<?php echo $revenue_details->project_type; ?>">
            </div>
            <?php if($this->session->userdata('user_type') == 'admin') {?>
	            <div class="col-sm-6 form-group">
	                <button type="submit" class="btn btn-success btn-block" style="margin-top: 24px;" >Save</button>
	            </div>
	        <?php } ?>
		</form>

	</div>
</body>