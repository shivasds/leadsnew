<?php
defined('BASEPATH') OR exit('No direct script access allowed');


class Dashboard extends CI_Controller {

	function __construct(){
        /* Session Checking Start*/
        parent::__construct();
        $this->load->model('user_model');
        $this->load->model('common_model');
        $this->load->model('callback_model');
        $this->load->library('session');
        $this->load->model('login_model');

        if($this->session->userdata('user_id') && $this->session->userdata('is_loggedin') == true && ($this->session->userdata('username') !='admin') )     
            $this->getPermission($this->session->userdata('user_id'));
        elseif($this->session->userdata('username') =='admin' && $this->session->userdata('user_type') =='admin')
        {
            $mData = $this->login_model->getModulesClause();
            $tmpArry = array();
            foreach ($mData as $value) {
                $tmpArry[] = $value['id'];
            }
            $this->session->set_userdata('permissions', json_encode($tmpArry));
        }
        elseif($this->session->userdata('user_type') =='admin')
             $this->getPermission($this->session->userdata('user_id'));
        else
            $this->getPermission($this->session->userdata('user_id'));

        if (!$this->session->userdata('is_loggedin')) {
            redirect(base_url("login"));
        }
        elseif($this->router->fetch_method() != "generate_dar") {
            if ($this->session->userdata('dar_flag'))
                redirect(base_url("generate_dar"));
        }
    }

    function getPermission($userId){
        $this->load->model('login_model');
        $fetchData = $this->login_model->getModulePermission(['userId' => $userId]);
        $permission = $fetchData['accessLists'];
        $this->session->set_userdata('permissions', $permission);
    }



	public function index() {
		error_reporting(0);
        $data['name'] = "index";
        $data['user_id'] = $this->session->userdata('user_id');
        if ($this->session->userdata('user_type') == 'user') {
            $data['imp_callbacks'] = $this->callback_model->fetch_important_callbacks($data['user_id']);
            $data['today_callback_count'] = $this->callback_model->fetch_callback_count($data['user_id'],'today');
            $data['yesterday_callback_count'] = $this->callback_model->fetch_yesterday_callback_count($data['user_id']);
            $data['overdue_callback_count'] = $this->callback_model->fetch_callback_count($data['user_id'],'overdue');
            $data['total_callback_count'] = $this->callback_model->fetch_callback_count($data['user_id'],"all","",true);
            $data['dead_leads_count'] = $this->callback_model->fetch_leads_count($data['user_id'],'dead');
            $data['close_leads_count'] = $this->callback_model->fetch_leads_count($data['user_id'],'close');
            $data['active_leads_count'] = $this->callback_model->fetch_leads_count($data['user_id'],'active');
            $data['client_reg_count'] = $this->callback_model->fetch_client_reg_count($data['user_id']);
            $data['total_revenue'] = $this->callback_model->fetch_total_revenue($data['user_id']);
            $data['manager_name'] = $this->user_model->get_manager_name($data['user_id']);
            $data['incentive_slabs'] = $this->callback_model->fetch_employee_incentive_slabs();
            $data['target'] = $this->callback_model->get_target($data['user_id'],date("m/Y"));

            $fetchData = $this->callback_model->get_siteVisitDataByUserId($data['user_id']);
            
            $prArry = array();
            $i = 1;
            foreach ($fetchData as $key => $value) {
            	$prArry[$value['id']][$key] = $value['id'];
            	$prArry[$value['id']][$key] = $value['projectName'];
            }
            $data['site_visit_projects'] = $prArry;
            $data['site_visit_data'] = $fetchData;

        }
        elseif ($this->session->userdata('user_type') == 'manager'){
            $data['imp_callbacks'] = $this->callback_model->fetch_important_callbacks($data['user_id']);
            $data['team_members'] = $this->user_model->get_team_members($data['user_id']);
            $data['total_team_members'] = $this->user_model->get_team_members_count($data['user_id']); 
            $data['total_calls'] = $this->callback_model->get_total_team_calls($data['user_id']);
            $data['total_callback_count'] = $this->callback_model->fetch_callback_count($data['user_id']);
            $data['total_active_callback_count'] = $this->callback_model->fetch_callback_count($data['user_id'],'all',"cb.status_id!=4 AND cb.status_id!=5");
            $data['close_leads_count'] = $this->callback_model->fetch_leads_count($data['user_id'],'close');
            $data['total_revenue'] = $this->callback_model->fetch_total_revenue($data['user_id']);
            $data['total_team_revenue'] = $this->callback_model->fetch_total_revenue($data['user_id'],True);
            $data['lead_source_report'] = $this->callback_model->get_lead_source_report($data['user_id']);
            $data['call_reports'] = $this->callback_model->get_call_reports($data['user_id']);
            $data['incentive_slabs'] = $this->callback_model->fetch_employee_incentive_slabs();
            $data['target'] = $this->callback_model->get_target($data['user_id'],date("m/Y"));
            // echo $this->db->last_query();exit;
        }
        else{
            $data['productivity_report'] = $this->callback_model->get_call_reports();
            $data['overdue_lead_count'] = $this->callback_model->get_overdue_lead_count();
            $data['today_callback_count'] = $this->callback_model->fetch_callback_count(null,'today');
            $data['total_callback_count'] = $this->callback_model->fetch_callback_count();
            $data['lead_source_report'] = $this->callback_model->get_lead_source_report();
            $data['live_feed_back'] = $this->user_model->get_live_feed_back();
            // echo "<pre>";print_r($data['productivity_report']);exit;
        }
        $this->load->view('dashboard',$data);
	}

    public function get_live_feed_back(){
        $live_feed_back = $this->user_model->get_live_feed_back();
        $return = "";
        if(count($live_feed_back) > 0){
            foreach ($live_feed_back as $key => $value) {
                $return .= "<tr><td>".$value->first_name." ".$value->last_name." (".(($value->type == 1)?"User":"Manager")."</td>";
                $return .= "<td>".$value->last_login."</td></tr>";
            }
        }
        else
            $return .= "<tr><td colpan='2'>No Users</td></tr>";
        echo $return;
    }

    public function callbacks() {
        $data['name'] ="callbacks";
        $data['heading'] ="Callbacks";
        $where="";
        $user_type = $this->session->userdata("user_type");
        if($this->input->post()){
            $dept=$this->input->post('dept');
            $project=$this->input->post('project');
            $lead_source=$this->input->post('lead_source');
            $user_name=$this->input->post('user_name');
            $sub_broker=$this->input->post('sub_broker');
            $status=$this->input->post('status');
            $city=$this->input->post('city');
            $advisor=$this->input->post('advisor');
            $self=$this->input->post('self');
            if($dept!==null){
                $this->session->set_userdata("department",$dept);
                if($dept)
                    $where.=" AND cb.dept_id=".trim($dept);
            }
            if($project!==null){
                $this->session->set_userdata("project",$project);
                if($project)
                    $where.=" AND cb.project_id=".trim($project);
            }
            if($lead_source!==null){
                $this->session->set_userdata("lead_source",$lead_source);
                if($lead_source)
                    $where.=" AND cb.lead_source_id=".trim($lead_source);
            }
            if($user_name!==null){
                $this->session->set_userdata("search_username",$user_name);
                if($user_name)
                    $where.=" AND cb.user_id=".trim($user_name);
            }
            if($sub_broker!==null){
                $this->session->set_userdata("sub_broker",$sub_broker);
                if($sub_broker)
                    $where.=" AND cb.broker_id=".trim($sub_broker);
            }
            if($status!==null){
                $this->session->set_userdata("status",$status);
                if($status)
                    $where.=" AND cb.status_id=".trim($status);
            }
            if($city!==null){
                $this->session->set_userdata("city",$city);
                if($city)
                    $where.=" AND u.city_id=".trim($city);
            }
            if($advisor!==null){
                $this->session->set_userdata("advisor",$advisor);
                if($advisor)
                    $where.=" AND cb.user_id=".trim($advisor);
            }
            if($self!==null){
                $this->session->set_userdata("self",$self);
                if($self == "1")
                    $user_type = "user";
            }

            $srxhtxt = trim($this->input->post('srxhtxt'));
            if($srxhtxt !==null ){
                $this->session->set_userdata('SRCHTXT', $srxhtxt);
                if($srxhtxt)
                	$where .=" AND (cb.name='".$srxhtxt."' OR cb.email1='".$srxhtxt."' OR cb.contact_no1='".$srxhtxt."' OR cb.leadid='".$srxhtxt."' OR p.name='".$srxhtxt."' OR ls.name = '".$srxhtxt."' OR concat(u.first_name,' ',u.last_name) ='".$srxhtxt."' OR b.name='".$srxhtxt."' OR u.first_name= '".$srxhtxt."')";
            }
            $searchDate = $this->input->post('searchDate');
            if($searchDate  !==null) {
                $this->session->set_userdata('SRCHDT', $searchDate);
                if($searchDate && $searchDate == 'today')
                    $where .=" AND cb.due_date like '%".date('Y-m-d')."%'";
                elseif ($searchDate && $searchDate == 'yesterday') 
                    $where .=" AND cb.due_date < '".date('Y-m-d')."'";
                elseif ($searchDate && $searchDate == 'tomorrow') 
                    $where .=" AND cb.due_date > '".date('Y-m-d')."'";
            }

          
        }
        else{
            if($this->session->userdata("department"))
                $where.=" AND cb.dept_id=".trim($this->session->userdata("department"));
            if($this->session->userdata("project"))
                $where.=" AND cb.project_id=".trim($this->session->userdata("project"));
            if($this->session->userdata("lead_source"))
                $where.=" AND cb.lead_source_id=".trim($this->session->userdata("lead_source"));
            if($this->session->userdata("search_username"))
                $where.=" AND cb.user_id=".trim($this->session->userdata("search_username"));
            if($this->session->userdata("sub_broker"))
                $where.=" AND cb.broker_id=".trim($this->session->userdata("sub_broker"));
            if($this->session->userdata("status"))
                $where.=" AND cb.status_id=".trim($this->session->userdata("status"));
            if($this->session->userdata("city"))
                $where.=" AND u.city_id=".trim($this->session->userdata("city"));
            if($this->session->userdata("advisor"))
                $where.=" AND cb.user_id=".trim($this->session->userdata("advisor"));
            if($this->session->userdata("self")){
                if($this->session->userdata("self") == "1")
                    $user_type = "user";
            }
            if($this->session->userdata('SRCHTXT')){
                $searchVal = $this->session->userdata('SRCHTXT');
                $where .=" AND (cb.name='".$searchVal."' OR cb.email1='".$searchVal."' OR cb.contact_no1='".$searchVal."' OR cb.leadid='".$searchVal."' OR p.name='".$searchVal."' OR ls.name = '".$searchVal."' OR concat(u.first_name,' ',u.last_name) ='".$searchVal."' OR b.name='".$searchVal."' OR u.first_name= '".$searchVal."')";
            }

            if($this->session->userdata('SRCHDT')){
                if($this->session->userdata('SRCHDT') == 'today')
                    $where .=" AND cb.due_date like '%".date('Y-m-d')."%'";
                elseif ($this->session->userdata('SRCHDT') == 'yesterday') 
                    $where .=" AND cb.due_date < '".date('Y-m-d')."'";
                elseif ($this->session->userdata('SRCHDT') == 'tomorrow') 
                    $where .=" AND cb.due_date > '".date('Y-m-d')."'";
            }
        }
        
        //------- pagination ------
        $rowCount               = $this->callback_model->count_search_records(null,$where,null,null,$user_type);
        $data["totalRecords"]   = $rowCount;
        $data["links"]          = paginitaion(base_url().'callbacks/', 2,VIEW_PER_PAGE, $rowCount);
        $page = $this->uri->segment(2);
        $offset = !$page ? 0 : $page;
        //------ End --------------

        //$data['result'] = $this->callback_model->search_callback(null,$where,null,null,$user_type);
        $data['result'] = $this->callback_model->search_callback(null,$where,$offset,VIEW_PER_PAGE, $user_type);
        $this->load->view('callbacks',$data);
    }

    public function generate_dar() {
        $data['name'] ="dar";
        $data['heading'] ="Generate Dar";
        if($this->input->post()){
            if($this->session->userdata('dar_flag'))
                $this->session->unset_userdata('dar_flag');
            $insertdata = array(
                "date" => $this->input->post('date'),
                "user_id" => $this->session->userdata('user_id'),
                "crm_calls" => $this->input->post('crm'),
                "crm_comment" => $this->input->post('crm_comment'),
                "f2f_meets" => $this->input->post('f2f'),
                "f2f_comment" => $this->input->post('f2f_comment'),
                "site_visits" => $this->input->post('site_visit'),
                "site_comment" => $this->input->post('site_visit_comment'),
                "sub_brok_appos" => $this->input->post('sub_brok_app'),
                "sub_brok_comment" => $this->input->post('sub_brok_app_comment'),
                "builder_appos" => $this->input->post('builders_app'),
                "builder_comment" => $this->input->post('builders_app_comment'),
                "others" => $this->input->post('other'),
                "other_comment" => $this->input->post('other_comment'),
                "note" => $this->input->post('note'),
                'date_added'=>date('Y-m-d H:i:s')      
            );
            $this->db->insert('dar', $insertdata);
            $this->load->library('email');
            
            $this->email->initialize(email_config());

            $to_emails = $this->user_model->get_vp_director_admin_emails();
            $reports_to = $this->session->userdata('reports_to');
            if($reports_to){
                $manager = $this->user_model->get_user_data($reports_to);
                array_push($to_emails, $manager['email']);
            }

            $this->email->from('admin@fullbasketproperty.com', 'Admin');
            $this->email->to($to_emails);

            $this->email->subject('New DAR generated');
            $this->email->message($this->load->view('dar_email_template',$insertdata, true));
            $this->email->send();
        }
        $data['dar_flag'] = $this->session->userdata('dar_flag')?1:0;
        $user_id = $this->session->userdata('user_id');
        $data['crm_calls'] = $this->session->userdata('dar_flag')?$this->callback_model->get_today_calls($user_id,"yesterday"):$this->callback_model->get_today_calls($user_id);
        $data['date'] = $this->session->userdata('dar_flag')?date('Y-m-d',strtotime("-1 days")):date('Y-m-d');
        $this->load->view('generate_dar',$data);
    }

    public function report_bugs() {
        $data['name'] ="report_bugs";
        $data['heading'] ="Report Bugs";
        $data['alert'] = false;
        if($this->input->post()){
            $data['alert'] = true;
            $info = getimagesize($_FILES['screen_shot']['tmp_name']);
            if($info !== false){
                $uploaddir = "uploads/screenshots";
                $file = uniqid().".".end((explode(".", $_FILES["screen_shot"]["name"])));
                if(!file_exists($uploaddir))
                    @mkdir($uploaddir);
                if(move_uploaded_file($_FILES["screen_shot"]["tmp_name"], $uploaddir."/".$file)){
                    $this->load->library('email');
                    $config = email_config();

                    $this->email->initialize($config);
                    $this->email->from("admin@leads.com", "Admin");
                    $this->email->to("pintu.mandal@outlook.com");
                    $this->email->subject("Bug report by ".$this->user_model->get_user_fullname($this->session->userdata('user_id')));
                    $this->email->message($this->input->post('description'));
                    $this->email->attach($uploaddir."/".$file);
                    $this->email->send();

                    $insertdata = array(
                        "screenshot" => $file,
                        "user_id" => $this->session->userdata('user_id'),
                        "description" => $this->input->post('description')
                    );
                    $this->db->insert('reported_bugs', $insertdata);
                    $data['success'] = true;
                    $data['message'] = "Bug reported";
                }
                else{
                    $data['success'] = false;
                    $data['message'] = "Image cant saved";
                }     
            }
            else{
                $data['success'] = false;
                $data['message'] = "Invalid image file";
            }

                
        }
        $this->load->view('report_bugs', $data);
    }

    public function get_dar_data() {
        $data['result'] = array();
        if ($this->input->post('date')) {
            $data['result'] = $this->callback_model->get_dar_data($this->session->userdata('user_id'), $this->input->post('date'));
        }
        $this->load->view('dar_data', $data);
    }

    public function change_password() {
        $data['name'] ="change_password";
        $data['heading'] ="Change Password";
        if($this->input->post()){
            $password = $this->input->post('password');
            $user_id = $this->session->userdata('user_id');
            $this->db->update(
                'user',
                array(
                    "password" => md5($password)
                ),
                array(
                    "id" => $user_id
                )
            );
            redirect(base_url());
        }
        $this->load->view('change_password',$data);
    }

    public function generate_callback() {
        if($this->input->post()){
            $dept=$this->input->post('dept');
            $name=$this->input->post('name');
            $contact_no1=$this->input->post('contact_no1');
            $contact_no2=$this->input->post('contact_no2');
            $callback_type=$this->input->post('callback_type');
            $email1=$this->input->post('email1');
            $email2=$this->input->post('email2');
            $project=$this->input->post('project');
            $lead_source=$this->input->post('lead_source');
            $leadId=$this->input->post('leadId');
            $due_date=$this->input->post('due_date');
            $sub_broker=$this->input->post('sub_broker');
            $status=$this->input->post('status');
            $notes=$this->input->post('notes');
            $data=array(
                'dept_id'=>$dept,
                'name'=>$name,
                'contact_no1'=>$contact_no1,
                'contact_no2'=>$contact_no2,
                'callback_type_id'=>$callback_type,
                'email1'=>$email1,
                'email2'=>$email2,
                'project_id'=>$project,
                'lead_source_id'=>$lead_source,
                'leadid'=>$leadId,
                'user_id'=>$this->session->userdata("user_id"),
                'due_date'=>$due_date,
                'broker_id'=>$sub_broker,
                'status_id'=>$status,
                'notes'=>$notes,
                'date_added'=>date('Y-m-d H:i:s'),
            );
            $query=$this->callback_model->add_callbacks($data);
            redirect(base_url().'callbacks_view');
        }
        $data['name'] ="generate";
        $data['heading'] ="Generate";
        $this->load->view('generate_callback',$data);
    }

    public function get_callback_details(){
        $id=$this->input->get('id');
        $query=$this->callback_model->get_callback_details($id);
        $data = array(
            'id' =>$id,           
            'name' =>$query->name,
            'dept'=>$query->dept_id, 
            'contact_no1'=>$query->contact_no1,
            'contact_no2'=>$query->contact_no2,
            'callback_type'=>$query->callback_type_id,
            'email1'=>$query->email1,
            'email2'=>$query->email2,
            'project'=>$query->project_id,
            'lead_source'=>$query->lead_source_id,
            'leadid'=>$query->leadid,
            'user_name'=>$query->user_id,
            'sub_broker'=>$query->broker_id,
            'manage_status'=>$query->status_id,
            'due_date'=>$query->due_date,
            'notes'=>$query->notes,
            'date_added'=>$query->date_added,
            'last_update'=>$query->last_update,
            'active'=>$query->active, 
        );

        $siteVisitData = $this->callback_model->callbackSiteVisitDataByClause(['callback_id'=>$id, 'flag'=>1, 'type'=>1],['id', 'callback_id', 'project_id', 'date as visitDate']);   
       // $siteVisitResult = $this->callback_model->callbackSiteVisitDataByClause(['callback_id'=>$id, 'type !='=>1],['callback_id', 'project_id', 'date as visitDate']); 

        $tmpArry = $tmpidsArry = array();
        if($siteVisitData){            
            foreach ($siteVisitData as $value) {
                $tmpArry[]      = $value['project_id'];
                $tmpidsArry[]   = $value['id'];
            }
        }
        $this->session->set_userdata('siteVisitIds', implode(',', $tmpidsArry));
        
        $data['siteVisitProject'] = $tmpArry;
        $data['siteVisitData'] = $siteVisitData;
       

        $indiv_callback_data = $this->callback_model->get_callback_data($id);
        //$indiv_callback_data = $this->callback_model->getCallbackDataByUserId($id, $query->user_id);
        $previous_callback = "";
        foreach ($indiv_callback_data as $callback_data) {
            $previous_callback .= $callback_data->status."****".$callback_data->date_added."****".$callback_data->user;
            $previous_callback .= "\n---------------------------------\n";
            $previous_callback .= $callback_data->current_callback."\n\n";
        }
        $data['previous_callback'] = $previous_callback;
        if($this->input->post('type')){
            if($this->input->post('type') == "Close"){
                $details = $this->callback_model->get_close_callback_details($id);
                $data['advisor1_id'] = $details->advisor1_id;
                $data['advisor2_id'] = $details->advisor2_id;
                $data['booking'] = $details->booking;
                $data['booking_month'] = $details->booking_month;
                $data['closure_date'] = $details->closure_date;
                $data['customer'] = $details->customer;
                $data['sub_source_id'] = $details->sub_source_id;
                $data['project_id'] = $details->project_id;
                $data['sqft_sold'] = $details->sqft_sold;
                $data['plc_charge'] = $details->plc_charge;
                $data['floor_rise'] = $details->floor_rise;
                $data['basic_cost'] = $details->basic_cost;
                $data['other_cost'] = $details->other_cost;
                $data['car_park'] = $details->car_park;
                $data['total_cost'] = $details->total_cost;
                $data['commission'] = $details->commission;
                $data['gross_revenue'] = $details->gross_revenue;
                $data['cash_back'] = $details->cash_back;
                $data['sub_broker_amo'] = $details->sub_broker_amo;
                $data['net_revenue'] = $details->net_revenue;
                $data['share_of_advisor1'] = $details->share_of_advisor1;
                $data['share_of_advisor2'] = $details->share_of_advisor2;
                $data['est_month_of_invoice'] = $details->est_month_of_invoice;
                $data['agreement_status'] = $details->agreement_status;
                $data['project_type'] = $details->project_type;
            }
        }

        //header('Content-Type: application/json');
        //echo json_encode($data);
        //$data['name'] = "Call back details";
        $data['heading'] = "Call back details";        
        $this->load->view('callback_details.php',$data);
    }

    public function search_callback($page = 1){
        $data['name'] ="search";
        $data['heading'] ="Search";

        $type=$this->input->post('type');
        $query=$this->input->post('query');
        if($type != null){
            $this->session->set_userdata("type",$type);
            $this->session->set_userdata("query",$query);
        }
        else{
            $type = $this->session->userdata("type");
            $query = $this->session->userdata("query");
        }
        if($type){
            $data['result'] = $this->callback_model->search_callback($type,$query,false,false,$this->session->userdata("user_type"));
        }
        else
            $data['result'] = false;

        $data['header'] = '';
        $this->load->view('search_callback',$data);
    }

    public function reports(){
        $data['name'] = "reports";
        $data['heading'] ="Reports";
        $this->load->view('reports',$data);
    }

    function generate_report(){

        $data['name'] = "reports";
        $data['heading'] ="Reports";
        if($this->input->post()){
            $dept = '';
            $city = '';
            if($this->input->post('fromDate') || $this->input->post('toDate')){
                $fromDate=$this->input->post('fromDate');
                $fromTime=$this->input->post('fromTime');
                $fromDate .= " ".$fromTime;
                $toDate=$this->input->post('toDate');
                $toTime=$this->input->post('toTime');
                if($toTime)
                    $toDate .= " ".$toTime;
                $reportType=$this->input->post('reportType');
                $this->session->set_userdata("report-fromDate",$fromDate);
                $this->session->set_userdata("report-toDate",$toDate);
                $this->session->set_userdata("report-type",$reportType);
            }
            else{
                $fromDate = $this->session->userdata('report-fromDate');
                $toDate = $this->session->userdata('report-toDate');
                $dept=$this->input->post('dept');
                $city=$this->input->post('city');
                $reportType = $this->session->userdata('report-type');
            }
            $data['dept'] = $dept;
            $data['city'] = $city;
            $data['fromDate'] = $fromDate;
            $data['toDate'] = $toDate;
            $data['reportType'] = $reportType;
            $callbacks = $this->callback_model->generate_report_data($fromDate,$toDate,$dept,$city, $reportType);
            switch ($reportType) {
                case 'lead':
                    $this->session->set_userdata("report-heading","Lead breakup report");
                    $advisors = array();
                    $projects = array();
                    $lead_sources = array();
                    foreach ($callbacks as $callback) {
                        if(array_key_exists($callback->user_id, $advisors))
                            $advisors[$callback->user_id] += 1;
                        else
                            $advisors[$callback->user_id] = 1;
                        if(array_key_exists($callback->project_id, $projects))
                            $projects[$callback->project_id] += 1;
                        else
                            $projects[$callback->project_id] = 1;
                        if(array_key_exists($callback->lead_source_id, $lead_sources))
                            $lead_sources[$callback->lead_source_id] += 1;
                        else
                            $lead_sources[$callback->lead_source_id] = 1;
                    }
                    $data['advisors'] = $advisors;
                    $data['projects'] = $projects;
                    $data['lead_sources'] = $lead_sources;
                    $this->load->view('reports/lead_report',$data);
                    break;

                case 'lead_assignment':
                    $this->session->set_userdata("report-heading","Total Lead Assignment Breakup Report");
                    $projects = $this->common_model->all_projects();
                    $projectCallbacks = array();
                    foreach ($projects as $key => $value) {
                        $projectCallbacks[$value->id] = array();
                    }
                    $advisors = array();
                    foreach ($callbacks as $callback) {
                        if(array_key_exists($callback->user_id, $advisors)){
                            if(array_key_exists($callback->project_id, $advisors[$callback->user_id]))
                                $advisors[$callback->user_id][$callback->project_id] += 1;
                            else
                                $advisors[$callback->user_id][$callback->project_id] = 1;
                        }
                        else
                            $advisors[$callback->user_id] = array($callback->project_id =>1);
                    }
                    $data['projectCallbacks'] = $projectCallbacks;
                    $data['advisors'] = $advisors;
                    $this->load->view('reports/lead_assignment_report',$data);
                    break;

                case 'site_visit':
                    $this->session->set_userdata("report-heading","Site Visit Done Report");
                    $advisors = array();
                    $site_visits = $this->callback_model->generate_sitevisit_data($dept,$city,$fromDate,$toDate);
                    foreach ($site_visits as $key => $value) {
                        if($value->cb_status_id == 6){
                            if(array_key_exists($value->cb_user_id, $advisors))
                                $advisors[$value->cb_user_id] += 1;
                            else
                                $advisors[$value->cb_user_id] = 1;
                        }
                    }
                    $data['advisors'] = $advisors;
                    $this->load->view('reports/site_visit_report',$data);
                    break;

                case 'clent_reg':
                    $this->session->set_userdata("report-heading","Client Registration Report");
                    $advisors = array();
                    foreach ($callbacks as $key => $value) {
                        $regDetails = $this->callback_model->get_client_reg_details($value->id);
                        if(array_key_exists($value->user_id, $advisors)){
                            $advisors[$value->user_id] += count($regDetails);
                        }
                        else{
                            $advisors[$value->user_id] = count($regDetails);
                        }
                    }
                    $data['advisors'] = $advisors;
                    $this->load->view('reports/client_reg_report',$data);
                    break;

                case 'revenue':
                    $this->session->set_userdata("report-heading","Revenue Report");
                    $revenue_datas = $this->callback_model->get_revenue_datas($fromDate,$toDate,$dept,$city);
                    // echo $this->db->last_query();exit;
                    // $closed_callbacks = array();
                    // foreach ($callbacks as $key => $value) {
                    //     if($value->status_id == 5)
                    //         array_push($closed_callbacks, $value->id);
                    // }
                    // $revenue_datas = $this->callback_model->get_close_callbacks_details($closed_callbacks);
                    $data['revenue_datas'] = $revenue_datas;
                    $this->load->view('reports/revenue_report',$data);
                    break;

                case 'daily_act':
                    $this->session->set_userdata("report-heading","Daily Activity Report");
                    $statuses = $this->common_model->all_statuses();
                    $advisors = array();
                    foreach ($callbacks as $key => $value) {
                        if(!(array_key_exists($value->user_id, $advisors))){
                            $advisors[$value->user_id] = array();
                            $advisors[$value->user_id]['total'] = 0;
                            foreach ($statuses as $status) {
                                $advisors[$value->user_id][$status->id] = 0;
                            }
                        }
                        if(array_key_exists($value->status_id, $advisors[$value->user_id])){
                            $advisors[$value->user_id][$value->status_id] += 1;
                            $advisors[$value->user_id]['total'] += 1;
                        }

                    }
                    $data['statuses'] = $statuses;
                    $data['advisors'] = $advisors;
                    // echo "<pre>";print_r($i);exit;
                    $this->load->view('reports/daily_act_report',$data);
                    break;

                case 'site_visit_fixed':
                    $this->session->set_userdata("report-heading","Site Visit Fixed Report");
                    $advisors = array();
                    $site_visits = $this->callback_model->generate_sitevisit_data($dept,$city,$fromDate,$toDate);
                    foreach ($site_visits as $key => $value) {
                        if(array_key_exists($value->cb_user_id, $advisors))
                            $advisors[$value->cb_user_id] += 1;
                        else
                            $advisors[$value->cb_user_id] = 1;
                    }
                    $data['advisors'] = $advisors;
                    $data['site_visits'] = $site_visits;
                    $this->load->view('reports/site_visit_fixed_report',$data);
                    break;

                case 'due';
                    $this->session->set_userdata("report-heading","Due report");
                    $due_reports = array();
                    $overdue_reports = array();
                    foreach ($callbacks as $callback) {
                        $duedate = explode(" ", $callback->due_date);
                        $duedate = $duedate[0];
                        if($duedate == date('Y-m-d')){
                            if(array_key_exists($callback->user_id, $due_reports))
                                $due_reports[$callback->user_id] += 1;
                            else
                                $due_reports[$callback->user_id] = 1;
                        }
                        else{
                            if(array_key_exists($callback->user_id, $overdue_reports))
                                $overdue_reports[$callback->user_id] += 1;
                            else
                                $overdue_reports[$callback->user_id] = 1;
                        }
                    }
                    $data['due_reports'] = $due_reports;
                    $data['overdue_reports'] = $overdue_reports;
                    $this->load->view('reports/due_report',$data);
                    break;

                default:
                    redirect(base_url().'reports');
                    break;
            }
        }
    }

    function view_revenue($id){
        $data['name'] = "reports";
        $data['heading'] = "Revenue Report";
        if($this->input->post()){
            $update_data=array(  
                'callback_id'=>$id,
                'advisor1_id'=>$this->input->post('c_seniorAdvisor'),
                'advisor2_id'=>$this->input->post('c_secondAdvisor'),
                'booking'=>$this->input->post('c_bkngName'),
                'booking_month'=>$this->input->post('c_bkngMnth'),
                'closure_date'=>$this->input->post('c_dateofClosure'),
                'customer'=>$this->input->post('c_customerName'),
                'project_id'=>$this->input->post('c_projectName'), 
                'sqft_sold'=>$this->input->post('c_sqftSold'), 
                'plc_charge'=>$this->input->post('c_plcCharge'), 
                'floor_rise'=>$this->input->post('c_floorRise'), 
                'basic_cost'=>$this->input->post('c_basicCost'), 
                'other_cost'=>$this->input->post('c_otherCost'),
                'car_park'=>$this->input->post('c_carPark'),
                'total_cost'=>$this->input->post('c_totalCost'),
                'commission'=>$this->input->post('c_comission'),
                'gross_revenue'=>$this->input->post('c_grossRevenue'),
                'cash_back'=>$this->input->post('c_cashBack'),
                'sub_broker_amo'=>$this->input->post('c_subBrokerAmo'),
                'net_revenue'=>$this->input->post('c_netRevenue'),
                'share_of_advisor1'=>$this->input->post('c_shareAdvisor1'),
                'share_of_advisor2'=>$this->input->post('c_shareAdvisor2'),
                'est_month_of_invoice'=> $this->input->post('c_estMonthofInvoice'),
                'agreement_status' => $this->input->post('c_agrmntStatus'),
                'project_type' => $this->input->post('c_projectType'),

            );

            $query=$this->callback_model->update_callback_details($update_data,$id);
        }
        $data['details'] = $this->callback_model->get_callback_details($id);
        $data['revenue_details'] = $this->callback_model->get_close_callback_details($id);
        $this->load->view("reports/revenue_detail",$data);
    }

    function get_previous_data($id){
        $data['result'] = $this->callback_model->get_callback_data($id);
        // echo "<pre>";print_r($data);exit;
        $this->load->view("view_previous_data",$data);
    }

    function mark_not_important($id){
        $this->callback_model->mark_not_important($id);
        echo "Success";
    }

    function view_callbacks($id=false){
        $data['name'] = "reports";
        $data['heading'] = $this->session->userdata('report-heading');
        $data['access'] = 'read_only';
        $data['important'] = 0;
        $report = $this->input->post_get('report');
        $dept = $this->input->post_get('dept');
        $city = $this->input->post_get('city');
        $fromDate = $this->input->post_get('fromDate');
        $toDate = $this->input->post_get('toDate');
        if($this->session->userdata('user_type')=="user")
            $advisor = $this->session->userdata('user_id');
        else
            $advisor = $this->input->post_get('advisor');
        $project = $this->input->post_get('project');
        $lead_source = $this->input->post_get('lead_source');
        $status = $this->input->post_get('status');
        $cb_ids = $this->input->post_get('cb_ids');
        if($status == 'close')
            $status = '5';
        $due_date = $this->input->post_get('due_date');
        $due_date_to = $this->input->post_get('due_date_to');
        $due_date_from = $this->input->post_get('due_date_from');
        $for = $this->input->post_get('for');
        $access = $this->input->post_get('access');
        $important = $this->input->post_get('important');
        if($id){
            $advisor = $id;
            $access = "read_write";
            $important = 1;
        }
        if($access)
            $data['access'] = $access;
        if($for == null)
            $for = 'report';

        $where = "";
        if($cb_ids){
            $data['cb_ids'] = $cb_ids;
            $cb_where = "(";
            $cb_id = explode(",", $cb_ids);
            foreach ($cb_id as $value) {
                if($value){
                    if($cb_where !== "(")
                        $cb_where .= " OR ";
                    $cb_where .= "cb.id=$value";
                }
            }
            $cb_where .= ")";
            $where.=" AND $cb_where";
        }
        else{
            if($dept){
                $data['dept'] = $dept;
                $where.=" AND cb.dept_id=".trim($dept);
            }
            if($city){
                $data['city_id'] = $city;
                $where.=" AND u.city_id=".trim($city);
            }
            if($fromDate){
                $data['fromDate'] = $fromDate;
                if($report == "due")
                    $where.=" AND cb.due_date>='".trim($fromDate)."'";
                else
                    $where.=" AND cb.date_added>='".trim($fromDate)."'";
            }
            if($toDate){
                $data['toDate'] = $toDate;
                if($report == "due")
                    $where.=" AND cb.due_date<='".trim($toDate)."'";
                else
                    $where.=" AND cb.date_added<='".trim($toDate)."'";
            }
            if($advisor){
                $data['advisor'] = $advisor;
                $adv_where = "(";
                $adv = explode(",", $advisor);
                foreach ($adv as $value) {
                    if($value){
                        if($adv_where !== "(")
                            $adv_where .= " OR ";
                        $adv_where .= "cb.user_id=$value";
                    }
                }
                $adv_where .= ")";
                $where.=" AND $adv_where";

            }
            if($project){
                $data['project'] = $project;
                $where.=" AND cb.project_id=".trim($project);
            }
            if($lead_source){
                $data['lead_source'] = $lead_source;
                $where.=" AND cb.lead_source_id=".trim($lead_source);
            }
            if($status){
                $data['status'] = $status;
                $where.=" AND cb.status_id='".trim($status)."'";
            }
            if($due_date){
                $data['due_date'] = $due_date;
                $where.=" AND DATE(cb.due_date)='".trim($due_date)."'";
            }
            else {
                if ($due_date_to){
                    $data['due_date_to'] = $due_date_to;
                    $where.=" AND cb.due_date<='".trim($due_date_to)."'";
                }
                if ($due_date_from){
                    $data['due_date_from'] = $due_date_from;
                    $where.=" AND cb.due_date>='".trim($due_date_from)."'";
                }
            }
            if($important){
                $data['important'] = $important;
                $this->session->set_userdata('advisor', $advisor);
                $this->session->set_userdata('access', $access);
                $where.=" AND cb.important=1";
            }
        }
        if($report == "due"){
            $where .= " AND cb.active = 1 AND cb.status_id != 4 AND cb.status_id != 5";
        }

        //------- pagination ------
        $rowCount               = $this->callback_model->count_search_records(null,$where,null,null, null, $for,$report);
        $data["totalRecords"]   = $rowCount;

        $data["links"]  = paginitaionWithQueryString(base_url().'view_callbacks/', 2, VIEW_PER_PAGE, $rowCount, $this->input->get());
        $page = $this->uri->segment(2);
        $offset = !$page ? 0 : $page;
        //------ End --------------

        $data['result'] = $this->callback_model->search_callback(null,$where,$offset,VIEW_PER_PAGE, null, $for,$report);

        //$data['result'] = $this->callback_model->search_callback(null,$where,null,null,null,$for,$report);
        $data['report'] = $report;
        $data['dept'] = $dept;
        $data['city'] = $city;
        $data['fromDate'] = $fromDate;
        $data['toDate'] = $toDate;
        $data['advisor'] = $advisor;
        $data['project'] = $project;
        $data['lead_source'] = $lead_source;
        $data['status'] = $status;


        $this->load->view('reports/view_callbacks',$data);
    }

    function view_callback_datas(){
        $data['name'] = "reports";
        $data['heading'] = $this->session->userdata('report-heading');
        $report = $this->input->get('report');
        $dept = $this->input->get('dept');
        $city = $this->input->get('city');
        $fromDate = $this->input->get('fromDate');
        $toDate = $this->input->get('toDate');
        $advisor = $this->input->get('advisor');
        $status = $this->input->get('status');
        $where = "";
        if($dept)
            $where.=" AND cb.dept_id=".trim($dept);
        if($fromDate)
            $where.=" AND cbd.date_added>='".trim($fromDate)."'";
        if($toDate)
            $where.=" AND cbd.date_added<='".trim($toDate)."'";
        if($advisor)
            $where.=" AND cbd.user_id=".trim($advisor);
        if($status)
            $where.=" AND cbd.status_id='".trim($status)."'";
        $data['result'] = $this->callback_model->search_callback_datas($where);
        // echo "<pre>";print_r($data['result']);exit;
        $data['report'] = $report;
        $data['dept'] = $dept;
        $data['city'] = $city;
        $data['fromDate'] = $fromDate;
        $data['toDate'] = $toDate;
        $data['advisor'] = $advisor;
        $data['status'] = $status;
        $this->load->view('reports/view_callback_datas',$data);
    }

    function get_revenue($month,$year){
        // $month = urldecode($month);
        // $month_data = explode('/', $month);
        // $month = $month_data[0];
        // $year = $month_data[1];
        $data = $this->callback_model->fetch_revenue_details($month,$year);
        // echo $this->db->last_query();exit;
        $return = "";
        if(count($data) > 0){
            foreach ($data as $key => $value) {
                $return .= "<tr>";
                $return .= "<td>".$value->customer."</td>";
                $return .= "<td>".$value->user_name."</td>";
                $return .= "<td>".$value->project_name."</td>";
                $return .= "<td>".$value->net_revenue."</td>";
                $return .= "<td>".($value->active?'Pending':'Approved')."</td>";
                $return .= "</tr>";
            }
        }
        else
            $return = "<tr><td colpan=\"5\">No entries</td></tr>";
            
        echo $return;
    }

    function site_visit_report_mail(){    	
    	error_reporting(0);
    	$fetchData = $this->callback_model->get_siteVisitDataByUserId($this->session->userdata('user_id'));            
        $prArry = array();
       
        foreach ($fetchData as $key => $value) {
        	$prArry[$value['id']][$key] = $value['id'];
        	$prArry[$value['id']][$key] = $value['projectName'];
        }
        $data['site_visit_projects'] 	= $prArry;
        $data['site_visit_data'] 		= $fetchData;
        $mail_body = $this->load->view('mail/site_visit_report_mail', $data, true);

        $to_emails 		= $this->user_model->get_vp_director_admin_emails();
        $managerEmail 	= $this->user_model->get_user_data($this->session->userdata('reports_to'))['email'];
        array_push($to_emails, $managerEmail);
    	
     	$this->load->library('email');
     	$this->email->initialize(email_config());
		//$config = email_config();	
		//$this->email->initialize($config);	
		$this->email->set_mailtype("html");		
		$this->email->from("admin@leads.com", "Admin");		
		$this->email->to(implode(', ', $to_emails));
		$this->email->subject('Site Visit Fixed Report');
		$this->email->message($mail_body);
		if($this->email->send())
			echo 1;
		else
			echo 0;
    }
}
