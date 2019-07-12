<?php
    $edit = true;
    if (($this->session->userdata("user_type")=="director") || ($this->session->userdata("user_type")=="vp"))
        $edit = false;
?>

<div class="modal fade" id="modal_edit"  role="dialog" data-keyboard="false" data-backdrop="static">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal">&times;</button>
                <h4 class="modal-title">Call back details</h4>
            </div>
            <div class="modal-body">
                <div class="col-md-3 form-group">
                    <input type="hidden" id="mhid">
                    <label for="emp_code">Dept:</label>
                    <select  class="form-control"  id="m_dept" name="m_dept" required disabled>
                        <option value="">Select</option>
                        <?php $all_department=$this->common_model->all_active_departments();
                        foreach($all_department as $department){ ?>
                            <option value="<?php echo $department->id; ?>"><?php echo $department->name; ?></option>
                        <?php }?>               
                    </select>
                </div>
                <div class="col-sm-3 form-group">
                    <label for="name">Name:</label>
                    <input type="text" class="form-control" id="m_name1" name="m_name1" placeholder="Name" required="required" disabled>
                </div>
                <div class="col-sm-3 form-group">
                    <label for="contact_no1">Contact No:</label>
                    <input type="text" class="form-control" id="m_contact_no1" name="m_contact_no1" placeholder="Contact No" disabled>
                </div>
                <div class="col-sm-3 form-group">
                    <label for="name">Contact No 2:</label>
                    <input type="text" class="form-control" id="m_contact_no2" name="m_contact_no2" placeholder="Contact No" <?php if(!$edit) echo 'disabled'; ?>>
                </div>
                <div class="col-md-3 form-group">
                    <label for="assign">Call back type:</label>
                    <select  class="form-control"  id="m_callback_type" name="m_callback_type" required="required" disabled>
                        <option value="">Select </option>
                        <?php $all_callback_types=$this->common_model->all_active_callback_types();
                        foreach($all_callback_types as $callback_type){ ?>
                            <option value="<?php echo $callback_type->id; ?>"><?php echo $callback_type->name; ?></option>
                        <?php }?>                 
                    </select> 
                </div>
                <div class="col-sm-3 form-group">
                    <label for="email">Email:</label>
                    <input type="email" class="form-control" id="m_email1" name="m_email1" placeholder="Email" disabled>
                </div>   
                <div class="col-sm-3 form-group">
                    <label for="email">Email2:</label>
                    <input type="email" class="form-control" id="m_email2" name="m_email2" placeholder="email" <?php if(!$edit) echo 'disabled'; ?>>
                </div>
                <div class="col-md-3 form-group">
                    <label for="emp_code">Project:</label>
                    <select  class="form-control"  id="m_project" name="m_project" required="required" disabled>
                        <option value="">Select</option>
                        <?php $projects= $this->common_model->all_active_projects(); 
                        foreach( $projects as $project){ ?>
                            <option value="<?php echo $project->id ?>"><?php echo $project->name ?></option>
                        <?php }?>               
                    </select>
                </div>
                <?php if($this->session->userdata("user_type")!="user") { ?>
                    <div class="col-md-3 form-group">
                        <label for="assign">Lead Source:</label>
                        <select  class="form-control"  id="m_lead_source" name="m_lead_source" required="required" disabled>
                            <option value="">Select</option>
                            <?php $lead_source= $this->common_model->all_active_lead_sources(); 
                            foreach( $lead_source as $source){ ?>
                                <option value="<?php echo $source->id ?>"><?php echo $source->name ?></option>
                            <?php } ?>             
                        </select>
                    </div>
                <?php } ?>
                <div class="col-sm-3 form-group">
                    <label for="leadId">Lead Id:</label>
                    <input type="text" class="form-control" id="m_leadId" name="m_leadId" placeholder="Lead Id" disabled>
                </div>
                <div class="col-md-3 form-group">
                    <label for="assign">Status:</label>
                    <select  class="form-control"  id="m_status" onchange="status(this.value)" name="m_status" required="required" <?php if(!$edit) echo 'disabled'; ?>>
                        <option value="">Select</option>
                        <?php $statuses= $this->common_model->all_active_statuses(); 
                        foreach( $statuses as $status){ ?>
                            <option value="<?php echo $status->id; ?>"><?php echo $status->name ?></option>
                        <?php } ?>           
                    </select>
                </div>
                <div class="col-md-3 form-group">
                    <label for="assign">Assign To:</label>
                    <input type="hidden" id="hidden_user_id" name="hidden_user_id" value="">
                    <select  class="form-control"  id="m_user_name" name="m_user_name" required="required"  <?php if(!$edit) echo 'disabled'; ?>>
                        <option value="">Select</option>
                        <?php if($edit){ ?>
                            <option value="<?php echo $this->session->userdata("user_id"); ?>"><?php echo $this->session->userdata("user_name"); ?></option>
                            <?php if ($this->session->userdata("user_type")=="user" ){
                                $name = $this->user_model->get_user_fullname($this->session->userdata("reports_to")); 
                                ?>
                                <option value="<?php echo $this->session->userdata("reports_to") ?>"><?php echo $name." (manager)"; ?></option>
                            <?php }elseif ($this->session->userdata("user_type")=="manager" ){ 
                                $users = $this->user_model->get_usersby_reports_to($this->session->userdata("user_id"));
                                foreach ($users as $key => $value) { ?>
                                    <option value="<?php echo $value->id ?>"><?php echo $value->first_name." ".$value->last_name." (user)"; ?></option>
                                <?php } ?>
                                <option value="1">Admin</option>
                            <?php } ?>
                        <?php }else{ ?>
                            <?php $all_user= $this->user_model->all_users("type in (1,2,3,4,5)"); 
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

                                    case '5':
                                        $role = "Admin";
                                        break;
                                }
                                ?>
                                <option value="<?php echo $user->id ?>" <?php if(($this->session->userdata("search_username"))==$user->id) echo 'selected' ?>><?php echo $user->first_name." ".$user->last_name." ($role)"; ?></option>
                            <?php } ?>
                        <?php } ?>
                    </select>
                </div>
                <?php if($this->session->userdata("user_type")!="user") { ?>
                    <div class="col-md-3 form-group">
                        <label for="email">Sub Source:</label>
                        <select  class="form-control"  id="c_subSource" name="c_subSource" disabled>
                            <option value="">Select</option>
                            <?php $brokers= $this->common_model->all_active_brokers(); 
                            foreach( $brokers as $broker){ ?>
                                <option value="<?php echo $broker->id; ?>"><?php echo $broker->name ?></option>
                            <?php } ?>               
                        </select>
                    </div>
                <?php }
                if($edit) { ?>
                    <div id="abc" hidden class="row">
                        <div class="col-sm-6 form-group">
                            <label for="client_name">Client name:</label>
                            <input type="text" class="form-control" id="c_client_name" name="client_name" placeholder="Client name">
                        </div>
                        <div class="col-sm-6 form-group">
                            <label for="email">Client Email Id:</label>
                            <input type="email" class="form-control" id="c_client_email" name="client_email" placeholder="Client Email Id">
                        </div>
                        <div class="col-sm-6 form-group">
                            <label for="email">Site visit date:</label>
                            <input type="text" class="form-control" id="c_client_visit" name="email2" placeholder="Site visit date" onchange="update_client_note();">
                        </div>
                        <div class="col-sm-6 form-group">
                            <label for="email">Site Assign by:</label>
                            <input type="text" class="form-control" onblur="le()" id="c_assign_by" name="assign_by" placeholder="Site Assign by" onchange="update_client_note();">
                        </div>
                        <div class="col-sm-6 form-group">
                            <label for="email">Relation ship Manager:</label>
                            <input type="text" class="form-control" id="c_relationShipManager" name="c_relationShipManager" placeholder="Relation ship Manager" onchange="update_client_note();">
                        </div>
                        <div class="col-sm-6 form-group">
                            <label for="email">Subject:</label>
                            <input type="text" class="form-control" id="c_subject" name="email2" value="Thank you For the Site Visit" placeholder="Subject">
                        </div>
                        <div class="col-sm-12 form-group">
                            <label for="comment">Mail Box:</label>
                            <textarea class="form-control" name="notesClient" id="c_notesClient" rows="18" id="comment">

Greetings From Full Basket Property.

With reference to your site visit on  assisted by Mr. abhishek from Full Basket Property, we thank you for giving us an opportunity to serve you in searching your dream home.  At FBP it is our endeavour to help you with all the possible Property options which will suit your requirement. Mr.  from FBP will be at your service. He/she will be there to assist you in searching your dream home.
  
1. Home search - Assisting and helping you find your dream home suiting your requirements by giving you info on market trends, legalities, site visit assistance etc.

2. Home loan Assistance - We will take away your pain of running around the banks to get your loan approved by giving doorstep service of bankers of your choice at your place.

3. Property Purchase Assistance - Ensuring that your home buying becomes a pleasant experience our Relationship Manager will be there throughout the process Of documentation.

4. Post sales Service – This is what differentiates us from others. We will be there for all possible help and guidance till you move into your home.

5. Interior Services - We are tied With best interior designers in the city who give the best designs and execute them at a competitive price.

6. Rental Services - Need to rent your house Or searching for good house on rent do not worry try Full Basket Property rental services. Our professional and need based approach will ensure that you get right home/tenant without much hassles.

For any escalations/ complaints please write to ceo@fullbasketproperty.com

Regards

Team Full Basket Property Services Pvt Ltd


                            </textarea>
                        </div>
                        <div class="col-sm-12 form-group" >
                            <div class="alert alert-success" id="mail_success" style="display:none">
                                <strong>Success!</strong> Email sent successfully.
                            </div>
                            <button type="button" style="float: right;" class="btn btn-success" onclick="sendMail()" >Send</button>
                        </div>
                    </div>
                    <div id="dead" class="row" hidden>
                        <div class="col-sm-4 form-group">
                            <label for="comment">Reason of dead:</label>
                            <select  class="form-control"  id="selectDeadRsn" name="selectDeadRsn" onchange="curr(this.value)">
                                <option value="">--Select--</option>
                                <?php $reasonSdata= $this->common_model->getDeadReasons(['status'=>'Y']); 
                                foreach( $reasonSdata as $rData){ ?>
                                    <option value="<?php echo $rData['name']; ?>"><?php echo $rData['name'] ?></option>
                                <?php } ?> 
                            </select>
                        </div>
                        <div class="col-sm-12 form-group">
                            <label for="comment">Reason of dead:</label>
                            <textarea class="form-control reasonOfDead" name="notes" id="notes" rows="3" ></textarea>
                        </div>
                    </div>
                    <div id="close" class="row" hidden>
                        <div class="col-sm-6 form-group">
                            <label for="email">Advisor one:</label>
                            <select  class="form-control"  id="c_seniorAdvisor" name="c_seniorAdvisor" required="required" >
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
                                    <option value="<?php echo $user->id ?>"><?php echo $user->first_name." ".$user->last_name." ($role)"; ?></option>
                                <?php } ?>               
                            </select>
                        </div>
                        <div class="col-sm-6 form-group">
                            <label for="email">Advisor two:</label>
                            <select  class="form-control"  id="c_secondAdvisor" name="c_secondAdvisor" required="required" >
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
                                    <option value="<?php echo $user->id ?>"><?php echo $user->first_name." ".$user->last_name." ($role)"; ?></option>
                                <?php } ?>               
                            </select>
                        </div>
                        <div class="col-sm-6 form-group">
                            <label for="email">Booking Name:</label>
                            <input type="text" class="form-control" id="c_bkngName" name="email2" placeholder="Booking Name">
                        </div>
                        <div class="col-sm-6 form-group">
                            <label for="email">Booking Month:</label>
                            <input type="text" class="form-control" id="c_bkngMnth" name="email2" placeholder="Booking Date">
                        </div>
                        <div class="col-sm-6 form-group">
                            <label for="email">Date of closure:</label>
                            <input type="date" class="form-control" id="c_dateofClosure" name="email2" placeholder="Date of closure">
                        </div>
                        <div class="col-sm-6 form-group">
                            <label for="email">Customer name:</label>
                            <input type="text" class="form-control" id="c_customerName" name="email2" placeholder="Customer Name">
                        </div>
                        <div class="col-sm-6 form-group">
                            <label for="email">Project:</label>
                            <select  class="form-control"  id="c_projectName" name="m_project" required="required" >
                                <option value="">Select</option>
                                <?php $projects= $this->common_model->all_active_projects(); 
                                foreach( $projects as $project){ ?>
                                    <option value="<?php echo $project->id ?>"><?php echo $project->name ?></option>
                                <?php }?>               
                            </select>
                        </div>
                        <div class="col-sm-6 form-group">
                            <label for="email">Sqft Sold:</label>
                            <input type="text" class="form-control" onblur="calculateTotalCost()" id="c_sqftSold" name="email2" placeholder="Sqft Sold">
                        </div>
                        <div class="col-sm-6 form-group">
                            <label for="email">PLC Charges:</label>
                            <input type="text" class="form-control"  onblur="calculateTotalCost()" id="c_plcCharge" name="email2" placeholder="PLC charges">
                        </div>
                        <div class="col-sm-6 form-group">
                            <label for="email">Floor Rise:</label>
                            <input type="text" class="form-control"  onblur="calculateTotalCost()" id="c_floorRise" name="email2" placeholder="Floor Rise">
                        </div>
                        <div class="col-sm-6 form-group">
                            <label for="email">Basic Cost:</label>
                            <input type="text" class="form-control"  onblur="calculateTotalCost()" id="c_basicCost" name="email2" placeholder="Basic Cost">
                        </div>
                        <div class="col-sm-6 form-group">
                            <label for="email">Other Cost:</label>
                            <input type="text" class="form-control"  onblur="calculateTotalCost()" id="c_otherCost" name="email2" placeholder="Other Cost">
                        </div>
                        <div class="col-sm-6 form-group">
                            <label for="email">Car Park:</label>
                            <input type="text" class="form-control"  onblur="calculateTotalCost()" id="c_carPark" name="email2" placeholder="Car Park">
                        </div>
                        <div class="col-sm-6 form-group">
                            <label for="email">Total Cost:</label>
                            <input type="text" class="form-control"   id="c_totalCost" name="email2" placeholder="Total Cost">
                        </div>

                        <div class="col-sm-6 form-group">
                            <label for="email">Commission(%):</label>
                            <input type="text" class="form-control" onblur="calculateGrossRevenue()"  id="c_comission" name="email2" placeholder="Commission">
                        </div>

                        <div class="col-sm-6 form-group">
                            <label for="email">Gross Revenue:</label>
                            <input type="text" class="form-control" id="c_grossRevenue" name="email2" placeholder="Gros Revenue">
                        </div>
                        <div class="col-sm-6 form-group">
                            <label for="email">Cashback:</label>
                            <input type="text" class="form-control" onblur="calculateNetRevenue()"  id="c_cashBack" name="email2" placeholder="Cash Back">
                        </div>
                        <div class="col-sm-6 form-group">
                            <label for="email">Sub broker amount:</label>
                            <input type="text" class="form-control" onblur="calculateNetRevenue()"  id="c_subBrokerAmo" name="email2" placeholder="Sub Broker amount">
                        </div>
                        <div class="col-sm-6 form-group">
                            <label for="email">Net Revenue:</label>
                            <input type="text" class="form-control" id="c_netRevenue" name="email2" placeholder="Net Revenue">
                        </div>
                        <div class="col-sm-6 form-group">
                            <label for="email">Share of advisor 1:</label>
                            <input type="text" class="form-control" onblur="calculateAdvisorShare(2)" id="c_shareAdvisor1" name="email2" placeholder="Share of advisor 1">
                        </div>
                        <div class="col-sm-6 form-group">
                            <label for="email">Share of advisor 2:</label>
                            <input type="text" class="form-control" onblur="calculateAdvisorShare(1)" id="c_shareAdvisor2" name="email2" placeholder="Share of advisor 2">
                        </div>

                        <div class="col-sm-6 form-group">
                            <label for="email">Estimated month of invoice:</label>
                            <input type="text" class="form-control" id="c_estMonthofInvoice" name="email2" placeholder="Estimated month of invoice">
                        </div>

                        <div class="col-sm-6 form-group">
                            <label for="email">Agreement Status:</label>
                            <input type="text" class="form-control" id="c_agrmntStatus" name="email2" placeholder="Agreement Status">
                        </div>

                        <div class="col-sm-6 form-group">
                            <label for="email">Project Type:</label>
                            <input type="text" class="form-control" id="c_projectType" name="email2" placeholder="Project Type">
                        </div>
                    </div>
                <?php } ?>
                <div class="col-sm-6 form-group">
                    <label for="comment">Preview Callbacks:</label>
                    <textarea class="form-control" name="notes" id="previous_callback1" rows="3" id="comment" readonly></textarea>
                </div>
                <?php if($edit){ ?>
                    <div class="col-sm-6 form-group">
                        <label for="comment">Current Callbacks:</label>
                        <textarea class="form-control" name="notes" rows="3" id="current_callback1" name="current_callback1" onkeyup="curr(this.value)"></textarea>
                    </div>
                    <div class="col-md-6 form-group">
                        <input type="checkbox" name="fancy-checkbox-success" onclick="reassignDate()"  id="fancy-checkbox-success" autocomplete="off" />
                        <div class="btn-group">
                            <label for="fancy-checkbox-success" class="btn btn-success" style="margin-right: 0;">
                                <span class="glyphicon glyphicon-ok"></span>
                                <span> </span>
                            </label>
                            <label for="fancy-checkbox-success" class="btn btn-default active">
                               ReAssign To Another Date 
                            </label>
                        </div>
                        <div id="reDate" hidden >
                            <div class="col-sm-6 form-group" >
                                <label for="leadId">Date:</label>
                                <input type="text" class="form-control datepicker" id="reassign_date" name="email2" placeholder="Date">
                            </div>
                            <div class="col-sm-6 form-group" >
                                <label for="leadId">Time:</label>
                                <input type="text" id="reassign_time" name="daterange" value="" class="form-control timePicker" placeholder="HH:MM"/>
                            </div>
                        </div>
                    </div>
                    <?php if (($this->session->userdata('user_type') == 'user') || ($this->session->userdata('user_type') == 'manager')){ ?> 
                        <div class="col-md-6 form-group">
                            <input type="checkbox" name="fancy-checkbox-primary" onclick="showSiteVisitFixDiv()"  id="fancy-checkbox-primary" autocomplete="off" />
                            <div class="btn-group">
                                <label for="fancy-checkbox-primary" class="btn btn-primary">
                                    <span class="glyphicon glyphicon-ok"></span>
                                    <span> </span>
                                </label>
                                <label for="fancy-checkbox-primary" class="btn btn-default active">
                                   Site Visit Fixed
                                </label>
                            </div>
                            <div id="siteVisitDate" hidden >
                                <div class="col-sm-12 form-group" >
                                    <label for="leadId">Date:</label>
                                    <input type="text" class="form-control datepicker" id="sitevisit_date" name="email2" placeholder="Date">
                                </div>
                                <div class="col-sm-12 form-group" >
                                    <label for="leadId">Project:</label>
                                    <select  class="form-control" id="sitevisit_project" name="sitevisit_project" multiple>
                                        <?php $projects= $this->common_model->all_active_projects(); 
                                        foreach( $projects as $project){ ?>
                                            <option value="<?php echo $project->id ?>" <?php if(($this->session->userdata("project"))==$project->id) echo 'selected' ?>><?php echo $project->name ?></option>
                                        <?php }?>              
                                    </select>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-6 form-group">
                            <input type="checkbox" name="fancy-checkbox-default" onclick="showSiteVisitDoneDiv()"  id="fancy-checkbox-default" autocomplete="off" />
                            <div class="btn-group">
                                <label for="fancy-checkbox-default" class="btn btn-default">
                                    <span class="glyphicon glyphicon-ok"></span>
                                    <span> </span>
                                </label>
                                <label for="fancy-checkbox-default" class="btn btn-default active">
                                   Site Visit Done
                                </label>
                            </div>
                            <div id="siteVisitDone" hidden >
                                <div class="col-sm-12 form-group" >
                                    <label for="leadId">Date:</label>
                                    <input type="text" class="form-control datepicker" id="sitevisitdone_date" name="email2" placeholder="Date">
                                </div>
                                <div class="col-sm-12 form-group" >
                                    <label for="leadId">Project:</label>
                                    <select  class="form-control"  id="sitevisitdone_project" name="sitevisitdone_project" multiple>
                                        <?php $projects= $this->common_model->all_active_projects(); 
                                        foreach( $projects as $project){ ?>
                                            <option value="<?php echo $project->id ?>" <?php if(($this->session->userdata("project"))==$project->id) echo 'selected' ?>><?php echo $project->name ?></option>
                                        <?php }?>              
                                    </select>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-6 form-group">
                            <input type="checkbox" name="fancy-checkbox-danger" onclick="showFacetoFaceDiv()"  id="fancy-checkbox-danger" autocomplete="off" />
                            <div class="btn-group">
                                <label for="fancy-checkbox-danger" class="btn btn-danger">
                                    <span class="glyphicon glyphicon-ok"></span>
                                    <span> </span>
                                </label>
                                <label for="fancy-checkbox-danger" class="btn btn-default active">
                                   Face to Face Done
                                </label>
                            </div>
                            <div id="facetoFace" hidden >
                                <div class="col-sm-12 form-group" >
                                    <label for="leadId">Date:</label>
                                    <input type="text" class="form-control datepicker" id="facetoface_date" name="email2" placeholder="Date">
                                </div>
                                <div class="col-sm-12 form-group" >
                                    <label for="leadId">Project:</label>
                                    <select  class="form-control"  id="facetoface_project" name="facetoface_project" multiple>
                                        <?php $projects= $this->common_model->all_active_projects(); 
                                        foreach( $projects as $project){ ?>
                                            <option value="<?php echo $project->id ?>" <?php if(($this->session->userdata("project"))==$project->id) echo 'selected' ?>><?php echo $project->name ?></option>
                                        <?php }?>              
                                    </select>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-6 form-group">
                            <input type="checkbox" name="important" id="fancy-checkbox-warning" autocomplete="off" />
                            <div class="btn-group">
                                <label for="fancy-checkbox-warning" class="btn btn-warning">
                                    <span class="glyphicon glyphicon-ok"></span>
                                    <span> </span>
                                </label>
                                <label for="fancy-checkbox-warning" class="btn btn-default active">
                                   Important
                                </label>
                            </div>
                        </div>
                    <?php } ?>
                    <div class="col-md-6 form-group">
                        <input type="checkbox" name="fancy-checkbox-info" onclick="clientEmail()"  id="fancy-checkbox-info" autocomplete="off" />
                        <div class="btn-group">
                            <label for="fancy-checkbox-info" class="btn btn-info">
                                <span class="glyphicon glyphicon-ok"></span>
                                <span> </span>
                            </label>
                            <label for="fancy-checkbox-info" class="btn btn-default active">
                               Client Registration Email
                            </label>
                        </div>
                        <div id="clientEmail" hidden>
                            <div class="col-sm-12 form-group">
                                <label for="email_id">Email Id:</label>
                                <input type="email" class="form-control" id="client_email_id" name="email_id" placeholder="Email Id">
                            </div>
                            <div class="col-sm-12 form-group">
                                <label for="subject">Subject:</label>
                                <input type="text" class="form-control" id="client_email_subject" name="subject" value="Client Registration" placeholder="Subject">
                            </div>
                            <div class="col-sm-12 form-group">
                                <label for="comment">Email Body:</label>
                                <textarea class="form-control" name="notes" id="client_email_body" rows="15" id="comment">
          
Dear sir / madam,

Greetings From Full Basket Property...

Kindly register the below client For __________________ project On behalf Of Full Basket 

Property & acknowledge.

Client Name : ________________

Contact No. : ________________

E-mail ID   : ________________

Thanks & Regards
Team Full Basket Property
      
        
                                </textarea>
                            </div>
                            <div class="col-sm-12 form-group">
                                <div class="alert alert-success" id="regmail_success" style="display:none">
                                    <strong>Success!</strong> Email sent successfully.
                                </div>
                                <button type="button" onclick="sendRegMail()" class="btn btn-success">Send</button>
                            </div>
                        </div>
                    </div>
                <?php } ?>
            </div>
            <div class="clearfix"></div>
            <div class="modal-footer">
                <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
                <button type="button" class="btn btn-success" onclick="update_callback_details()" id="save"  disabled>Save</button>
            </div>
        </div>
    </div>
</div>

<div class="modal fade" id="modal_previous"  role="dialog" data-keyboard="false" data-backdrop="static">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal">&times;</button>
                <h4 class="modal-title">Call backs</h4>
            </div>
            <div class="modal-body" id="previous_callbacks">
    
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
            </div>
        </div>
    </div>
</div>

<script type="text/javascript">
    function previous_callbacks(v){
        $(".se-pre-con").show();
        $.get( "<?php echo base_url()?>dashboard/get_previous_data/"+v, function( data ) {
            $( "#previous_callbacks" ).html( data );
            $('#previous').DataTable();
            $(".se-pre-con").hide("slow");
        });
    }

    function remove_important(id){
        $(".se-pre-con").show();
        $.ajax({
            type:"GET",
            url: "<?php echo base_url()?>dashboard/mark_not_important/"+id,
            success:function(data){
                $("#repost").submit();
                $("#row"+id).remove();
                $(".se-pre-con").hide("slow");
            }
        });
    }

    function edit(v){
        $(".se-pre-con").show();
        $.ajax({
            type:"POST",
            url: "<?php echo base_url()?>get_callback_details",
            data:{id:v},
            success:function(data){
                $('#mhid').val(v);
                $('#m_dept').val(data.dept);
                $('#m_name1').val(data.name);
                $('#m_contact_no1').val(data.contact_no1);
                $('#m_contact_no2').val(data.contact_no2);
                $('#m_callback_type').val(data.callback_type);
                $('#m_email1').val(data.email1);
                $('#m_email2').val(data.email2);
                $('#m_project').val(data.project);
                $('#m_lead_source').val(data.lead_source);
                $('#m_leadId').val(data.leadid);
                $('#m_status').val(data.manage_status);
                $('#m_user_name').val(data.user_name);
                $('#hidden_user_id').val(data.user_name);
                $('#previous_callback1').val(data.previous_callback);
    
                $('#c_subSource').val(data.sub_broker);
                $('#client_name').val(data.name);
                if(data.email1){
                    $('#client_email').val(data.email1);
                }else{
                    $('#client_email').val(data.email2);
                }
                $(".se-pre-con").hide("slow");
            }
        });
    }

    /*$(document).on('change', '#m_status', function(){
        curr();
    });*/

    function curr(v){       
        //if(v.length){
        var stsId = $('#m_status').val();
        var error = 1;
        if(stsId == 4) {
            if($('#current_callback1').val().length && $('#selectDeadRsn').val().length)
                error = 0;
            else
                error = 1
        }
        else{
            if($('#current_callback1').val().length)
                error = 0;
            else
                error = 1;
        }

        if(!error){
            $('#save').prop('disabled', false);
        }
        else{
            $('#save').prop('disabled', true);
        }
    }


    var notesClient = "Greetings From Full Basket Property.\n\nWith reference to your site visit on {site_visit_date} assisted by Mr. {site_assigned_by} from Full Basket Property, we thank you for giving us an opportunity to serve you in searching your dream home.  At FBP it is our endeavour to help you with all the possible Property options which will suit your requirement. Mr. {relationship_manager} from FBP will be at your service. He/she will be there to assist you in searching your dream home.\n\n1. Home search - Assisting and helping you find your dream home suiting your requirements by giving you info on market trends, legalities, site visit assistance etc.\n\n2. Home loan Assistance - We will take away your pain of running around the banks to get your loan approved by giving doorstep service of bankers of your choice at your place.\n\n3. Property Purchase Assistance - Ensuring that your home buying becomes a pleasant experience our Relationship Manager will be there throughout the process Of documentation.\n\n4. Post sales Service – This is what differentiates us from others. We will be there for all possible help and guidance till you move into your home.\n\n5. Interior Services - We are tied With best interior designers in the city who give the best designs and execute them at a competitive price.\n\n6. Rental Services - Need to rent your house Or searching for good house on rent do not worry try Full Basket Property rental services. Our professional and need based approach will ensure that you get right home/tenant without much hassles.\n\nFor any escalations/ complaints please write to ceo@fullbasketproperty.com\n\nRegards\n\nTeam Full Basket Property Services Pvt Ltd";

    function update_client_note(){
        var site_visit_date = $("#c_client_visit").val();
        var site_assigned_by = $("#c_assign_by").val();
        var relationship_manager = $("#c_relationShipManager").val();
        var newNote = notesClient.replace("{site_visit_date}",site_visit_date).replace("{site_assigned_by}",site_assigned_by).replace("{relationship_manager}",relationship_manager);
        $("#c_notesClient").html(newNote);
    }

    function update_callback_details(){

        if($("#m_status").val()=="Close"){
            if($("#c_seniorAdvisor").val()==""){
                $("#c_seniorAdvisor").focus();
                return false;
            }
            if($("#c_secondAdvisor").val()==""){
                $("#c_secondAdvisor").focus();
                return false;
            }
            if($("#c_bkngName").val()==""){
                $("#c_bkngName").focus();
                return false;
            }
            if($("#c_bkngMnth").val()==""){
                $("#c_bkngMnth").focus();
                return false;
            }
            if($("#c_dateofClosure").val()==""){
                $("#c_dateofClosure").focus();
                return false;
            }
            if($("#c_customerName").val()==""){
                $("#c_customerName").focus();
                return false;
            }
            if($("#c_projectName").val()==""){
                $("#c_projectName").focus();
                return false;
            }
            if($("#c_sqftSold").val()==""){
                $("#c_sqftSold").focus();
                return false;
            }
            if($("#c_plcCharge").val()==""){
                $("#c_plcCharge").focus();
                return false;
            }
            if($("#c_floorRise").val()==""){
                $("#c_floorRise").focus();
                return false;
            }
            if($("#c_basicCost").val()==""){
                $("#c_basicCost").focus();
                return false;
            }
            if($("#c_otherCost").val()==""){
                $("#c_otherCost").focus();
                return false;
            }
            if($("#c_carPark").val()==""){
                $("#c_carPark").focus();
                return false;
            }
            if($("#c_totalCost").val()==""){
                $("#c_totalCost").focus();
                return false;
            }
            if($("#c_comission").val()==""){
                $("#c_comission").focus();
                return false;
            }
            if($("#c_grossRevenue").val()==""){
                $("#c_grossRevenue").focus();
                return false;
            }
            if($("#c_cashBack").val()==""){
                $("#c_cashBack").focus();
                return false;
            }
            if($("#c_subBrokerAmo").val()==""){
                $("#c_subBrokerAmo").focus();
                return false;
            }
            if($("#c_netRevenue").val()==""){
                $("#c_netRevenue").focus();
                return false;
            }
            if($("#c_shareAdvisor1").val()==""){
                $("#c_shareAdvisor1").focus();
                return false;
            }
            if($("#c_shareAdvisor2").val()==""){
                $("#c_shareAdvisor2").focus();
                return false;
            }
            if($("#c_estMonthofInvoice").val()==""){
                $("#c_estMonthofInvoice").focus();
                return false;
            }
            if($("#c_agrmntStatus").val()==""){
                $("#c_agrmntStatus").focus();
                return false;
            }
            if($("#c_projectType").val()==""){
                $("#c_projectType").focus();
                return false;
            }

        }
        if($('#fancy-checkbox-primary').is(':checked')){
            if($("#sitevisit_date").val()==""){
                $("#sitevisit_date").focus();
                return false;
            }
            if($("#sitevisit_project").val()==null){
                $("#sitevisit_project").focus();
                return false;
            }
        }
        if($('#fancy-checkbox-default').is(':checked')){
            if($("#sitevisitdone_date").val()==""){
                $("#sitevisitdone_date").focus();
                return false;
            }
            if($("#sitevisitdone_project").val()==null){
                $("#sitevisitdone_project").focus();
                return false;
            }
        }
        if($('#fancy-checkbox-danger').is(':checked')){
            if($("#facetoface_date").val()==""){
                $("#facetoface_date").focus();
                return false;
            }
            if($("#facetoface_project").val()==null){
                $("#facetoface_project").focus();
                return false;
            }
        }
        $(".se-pre-con").show();
        var data = {
            'callback_id':$('#mhid').val(),
            'status_id':$('#m_status').val(),
            'advisor1_id':$('#c_seniorAdvisor').val(),
            'advisor2_id':$('#c_secondAdvisor').val(),
            'booking':$("#c_bkngName").val(),
            'booking_month':$("#c_bkngMnth").val(),
            'closure_date':$("#c_dateofClosure").val(),
            'customer':$('#c_customerName').val(),
            'sub_source_id':$("#c_subSource").val(),
            'close_project_id':$("#c_projectName").val(),
            'sqft_sold':$("#c_sqftSold").val(),
            'plc_charge':$("#c_plcCharge").val(),
            'floor_rise':$("#c_floorRise").val(),
            'basic_cost':$("#c_basicCost").val(),
            'other_cost':$("#c_otherCost").val(),
            'car_park':$('#c_carPark').val(),
            'total_cost':$('#c_totalCost').val(),
            'commission':$('#c_comission').val(),
            'gross_revenue':$('#c_grossRevenue').val(),
            'cash_back':$('#c_cashBack').val(),
            'sub_broker_amo':$('#c_subBrokerAmo').val(),
            'net_revenue':$('#c_netRevenue').val(),
            'share_of_advisor1':$('#c_shareAdvisor1').val(),
            'share_of_advisor2':$('#c_shareAdvisor2').val(),
            'est_month_of_invoice':$('#c_estMonthofInvoice').val(),
            'agreement_status':$('#c_agrmntStatus').val(),
            'project_type':$('#c_projectType').val(),

            'reason_for_dead':$('.reasonOfDead').val(),
            'reason_cause':$('#selectDeadRsn').val(),
            
            'current_callback':$('#current_callback1').val(),

            'name':$('#m_name1').val(),
            'due_date':$('#reassign_date').val()?$('#reassign_date').val()+' '+($('#reassign_time').val()?$('#reassign_time').val():'00:00'):null,
            'dept_id':$("#m_dept").val(),
            'contact_no1':$("#m_contact_no1").val(),
            'contact_no2':$("#m_contact_no2").val(),
            'callback_type_id':$("#m_callback_type").val(),
            'email1':$("#m_email1").val(),
            'email2':$("#m_email2").val(),
            'project_id':$("#m_project").val(),
            'leadid':$("#m_leadId").val()
        };
        if($("#m_lead_source").val())
            data.lead_source_id = $("#m_lead_source").val();
        if($("#hidden_user_id").val() != $("#m_user_name").val())
            data.user_id = $("#m_user_name").val();
        if($('#fancy-checkbox-primary').is(':checked')){
            data.sitevisit_date = $("#sitevisit_date").val();
            data.sitevisit_project_id = $("#sitevisit_project").val();
        }
        if($('#fancy-checkbox-default').is(':checked')){
            data.sitevisitdone_date = $("#sitevisitdone_date").val();
            data.sitevisitdone_project_id = $("#sitevisitdone_project").val();
        }
        if($('#fancy-checkbox-danger').is(':checked')){
            data.facetoface_date = $("#facetoface_date").val();
            data.facetoface_project_id = $("#facetoface_project").val();
        }
        if($('#fancy-checkbox-warning').is(':checked')){
            data.important = 1;
        }
        console.log(data);
        $.ajax({
            type:"POST",
            url: "<?php echo base_url()?>admin/update_callback_details",
            data:data,
            success:function(data){
                location.reload();
            }
        });
    }

    function status(v){
        if('6'==v){
            $('#close').hide();
            $('#dead').hide();
            $('#abc').show();
        } else if('5'==v){
            $('#abc').hide();
            $('#dead').hide();
            $('#close').show();
        } else if('4'==v){
            $('#abc').hide();
            $('#close').hide();
            $('#dead').show();
        } else{
            $('#close').hide();
            $('#abc').hide();
            $('#dead').hide();
        }
    }

    function reassignDate(){
        $('#reDate').toggle();
    }

    function showSiteVisitFixDiv(){
        $('#siteVisitDate').toggle();
    }

    function showSiteVisitDoneDiv(){
        $('#siteVisitDone').toggle();
    }

    function showFacetoFaceDiv(){
        $('#facetoFace').toggle();
    }

    function clientEmail(){
        $('#clientEmail').toggle();
    }
    
    function le(){
        var b =$('#assign_by').val();
        var a=$('#notesClient').text();
        var n = a.indexOf("On");
        n=n+3;
        var output = [a.slice(0, n), b, a.slice(n)].join('');
        $('#notesClient').text(output );
    }

    function calculateTotalCost(){
        var sold=$('#c_sqftSold').val();
        var plc=$('#c_plcCharge').val();
        var basic=$('#c_basicCost').val();
        var other=$('#c_otherCost').val();
        var car=$('#c_carPark').val();
        var flood=$('#c_floorRise').val();

        if(!sold) sold=0;
        if(!plc) plc=0;
        if(!basic) basic=0;
        if(!other) other=0;
        if(!car) car=0;
        if(!flood) flood=0;

        sold=parseFloat(sold);
        plc=parseFloat(plc);
        basic=parseFloat(basic);
        other=parseFloat(other);
        car=parseFloat(car);
        flood=parseFloat(flood);
        var total=(basic*sold)+plc+flood+other+car;
        $('#c_totalCost').val(total);
    }

    function calculateGrossRevenue(){
        var total_cost=$('#c_totalCost').val();
        var c_comission=$('#c_comission').val();
        c_comission=parseFloat(c_comission);
        total_cost=parseFloat(total_cost);

        if(!c_comission){c_comission=0;}
        if(!total_cost){total_cost=0;}

        var c_grossRevenue=(total_cost * c_comission)/100;
        $('#c_grossRevenue').val(c_grossRevenue);
    }

    function calculateNetRevenue(){
        var cashback=$('#c_cashBack').val();
        var subbroker=$('#c_subBrokerAmo').val();
        var c_grossRevenue=$('#c_grossRevenue').val();
        if(!cashback){cashback=0;}
        if(!subbroker){subbroker=0;}

        c_grossRevenue=parseFloat(c_grossRevenue);
        subbroker=parseFloat(subbroker);
        var revenue=c_grossRevenue - cashback - subbroker;
        $('#c_netRevenue').val(revenue);
    }

    function calculateAdvisorShare(id){
        if(id==1){
            var c_shareAdvisor2=$('#c_shareAdvisor2').val();
            $('#c_shareAdvisor1').val(100-c_shareAdvisor2);
        }
        else if(id==2){
            var c_shareAdvisor1=$('#c_shareAdvisor1').val();
            $('#c_shareAdvisor2').val(100-c_shareAdvisor1);
        }
    }

    function sendMail(){
        if($("#c_client_name").val() == ""){
            alert("Please enter client name");
            $("#c_client_name").focus();
            return false;
        }
        if($("#c_client_email").val() == ""){
            alert("Please enter client email");
            $("#c_client_email").focus();
            return false;
        }
        if($("#c_client_visit").val() == ""){
            alert("Please enter Site visit date");
            $("#c_client_visit").focus();
            return false;
        }
        if($("#c_assign_by").val() == ""){
            alert("Please enter Site assign by");
            $("#c_assign_by").focus();
            return false;
        }
        if($("#c_subject").val() == ""){
            alert("Subject cannot be empty");
            $("#c_subject").focus();
            return false;
        }
        if($("#c_relationShipManager").val() == ""){
            alert("Please enter Relation ship manager name");
            $("#c_relationShipManager").focus();
            return false;
        }
        if($("#c_notesClient").val() == ""){
            alert("Mail message cannot be empty");
            $("#c_notesClient").focus();
            return false;
        }
        $(".se-pre-con").show();
        $.ajax({
            type:"POST",
            url: "<?php echo base_url()?>admin/send_mail/site-visit",
            data:{
                client_name:$("#c_client_name").val(),
                client_email:$("#c_client_email").val(),
                client_visit:$("#c_client_visit").val(),
                assign_by:$("#c_assign_by").val(),
                subject:$("#c_subject").val(),
                relationship_manager:$("#c_relationShipManager").val(),
                message:$("#c_notesClient").val(),
                callback_id:$("#mhid").val()
            },
            success:function(data) {
                if(data.success){
                    $("#mail_success").show();
                }
                $(".se-pre-con").hide("slow");
            }
        });
    }

    function sendRegMail(){
        if($("#client_email_id").val() == ""){
            alert("Please enter client email");
            $("#client_email_id").focus();
            return false;
        }
        if($("#client_email_subject").val() == ""){
            alert("Please enter email subject");
            $("#client_email_subject").focus();
            return false;
        }
        if($("#client_email_body").val() == ""){
            alert("Please enter email body");
            $("#client_email_body").focus();
            return false;
        }
        $(".se-pre-con").show();
        $.ajax({
            type:"POST",
            url: "<?php echo base_url()?>admin/send_mail/client-reg",
            data:{
                client_email:$("#client_email_id").val(),
                message:$("#client_email_body").val(),
                subject:$("#client_email_subject").val(),
                callback_id:$("#mhid").val()
            },
            success:function(data) {
                console.log(data.success);
                if(data.success){
                    $("#regmail_success").show();
                }
                $(".se-pre-con").hide("slow");
            }
        });
    }
</script>