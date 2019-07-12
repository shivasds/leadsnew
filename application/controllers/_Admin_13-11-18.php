<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Admin extends CI_Controller {

	function __construct(){
		/* Session Checking Start*/
		parent::__construct();
		$this->load->model('user_model');
		$this->load->model('common_model');
		$this->load->model('callback_model');
		$this->load->library('session');
		if(!in_array($this->router->fetch_method(), array('fetch_online_leads', 'send_daily_report'))){
			if (!$this->session->userdata('is_loggedin')) {
				redirect(base_url("login/admin"));
			}
		}

		if($this->session->userdata('username') !='admin' && $this->session->userdata('is_loggedin') == true){  
            $this->getPermission($this->session->userdata('user_id'));
        }
	}

	public function index() {
		$data['name'] = "index";
		$this->load->view('admin/home',$data);
	}

	public function manage_users() {
		$data['name'] ="admin";
		$data['heading'] ="Manage User";
		if($this->input->post()){
			$first_name=$this->input->post('first_name');
			$last_name=$this->input->post('last_name');
			$emp_code=$this->input->post('emp_code');
			$email=$this->input->post('email');
			$department=$this->input->post('department');
			$city=$this->input->post('city');
			$manager=$this->input->post('manager');
			$select_user=$this->input->post('select_user');
			$savedata=array(
				'first_name'=>$first_name,
				'last_name'=>$last_name,
				'type'=>1,
				'emp_code'=>$emp_code,
				'email'=>$email,
				'dept_id'=>$department,
				'city_id'=>$city,
				'select_user'=>$select_user,
				'reports_to'=>$manager,
				'password'=>md5($emp_code),
				'loginid'=>$emp_code,
				'date_added'=>date('Y-m-d H:i:s')
			);
			$this->user_model->add_user($savedata);
		}
		//$data['all_user'] = $this->user_model->all_employees();
		
		//------- pagination ------
		$rowCount 				= $this->user_model->countEmployees();
		$data["totalRecords"] 	= $rowCount;
		$data["links"] 			= paginitaion(base_url().'admin/manage_users/', 3,VIEW_PER_PAGE, $rowCount);
		$page = $this->uri->segment(3);
        $offset = !$page ? 0 : $page;
		//------ End --------------


		$data['all_user'] = $this->user_model->getEmployeesByLimit($offset, VIEW_PER_PAGE);
		$this->load->view('admin/users',$data);
	}

	public function manage_directors() {
		$data['name'] ="admin";
		$data['heading'] ="Manage Director";
		if($this->input->post()){
			$first_name=$this->input->post('first_name');
			$last_name=$this->input->post('last_name');
			$emp_code=$this->input->post('emp_code');
			$email=$this->input->post('email');
			$savedata=array(
				'first_name'=>$first_name,
				'last_name'=>$last_name,
				'type'=>4,
				'emp_code'=>$emp_code,
				'email'=>$email,
				'password'=>md5($emp_code),
				'loginid'=>$emp_code,
				'date_added'=>date('Y-m-d H:i:s')
			);
			$this->user_model->add_user($savedata);
		}
		$data['all_directors'] = $this->user_model->all_users("type=4");
		$this->load->view('admin/directors',$data);
	}

	public function manage_vps() {
		$data['name'] ="admin";
		$data['heading'] ="Manage VP";
		if($this->input->post()){
			$first_name=$this->input->post('first_name');
			$last_name=$this->input->post('last_name');
			$emp_code=$this->input->post('emp_code');
			$email=$this->input->post('email');
			$department=$this->input->post('department');
			$city=$this->input->post('city');
			$director=$this->input->post('director');
			$savedata=array(
				'first_name'=>$first_name,
				'last_name'=>$last_name,
				'type'=>3,
				'emp_code'=>$emp_code,
				'email'=>$email,
				'dept_id'=>$department,
				'city_id'=>$city,
				'reports_to'=>$director,
				'password'=>md5($emp_code),
				'loginid'=>$emp_code,
				'date_added'=>date('Y-m-d H:i:s')
			);
			$this->user_model->add_user($savedata);
		}
		$data['all_vps'] = $this->user_model->all_vps();
		$this->load->view('admin/vps',$data);
	}

	public function manage_managers() {
		$data['name'] ="admin";
		$data['heading'] ="Manage Manager";
		if($this->input->post()){
			$first_name=$this->input->post('first_name');
			$last_name=$this->input->post('last_name');
			$emp_code=$this->input->post('emp_code');
			$email=$this->input->post('email');
			$department=$this->input->post('department');
			$city=$this->input->post('city');
			$director=$this->input->post('director');
			$savedata=array(
				'first_name'=>$first_name,
				'last_name'=>$last_name,
				'type'=>2,
				'emp_code'=>$emp_code,
				'email'=>$email,
				'dept_id'=>$department,
				'city_id'=>$city,
				'reports_to'=>$director,
				'password'=>md5($emp_code),
				'loginid'=>$emp_code,
				'date_added'=>date('Y-m-d H:i:s')
			);
			$this->user_model->add_user($savedata);
		}
		$data['all_managers'] = $this->user_model->all_managers();
		$this->load->view('admin/managers',$data);
	}

	public function generate_target() {
		$data['name'] ="admin";
		$data['heading'] ="Generate Target";
		$data['success'] = false;
		$data['message'] = "";
		if($this->input->post()){
			$user_id = $this->input->post('user_id');
			$month = $this->input->post('month');
			$target = $this->input->post('target');
			$this->callback_model->add_user_target($user_id, $month, $target);
			$data['message'] = "Target Added";
			$data['success'] = true;
		}
		$data['users'] = $this->user_model->all_users("(type='1' OR type='2')");
		$this->load->view('admin/generate_target',$data);
	}

	public function get_target() {
		if($this->input->post()){
			$user_id = $this->input->post('user_id');
			$month = $this->input->post('month');
			$target = $this->callback_model->get_target($user_id,$month);
			echo $target;
		}
	}

	public function generate_incentive_slab() {
		$data['name'] ="admin";
		$data['heading'] ="Generate Incentive Slab";
		$data['success'] = false;
		$data['message'] = "";
		if($this->input->post()){
			$id = $this->input->post('id');
			$from = $this->input->post('from_date');
			$to = $this->input->post('to_date');
			$amounts = $this->input->post('amount');
			$percentages = $this->input->post('percentage');
			if($id){
				$update_data = array(
					"from" => $from,
					"to" => $to
				);
				$this->db->update('incentive_interval', $update_data, "id=".$id);
				$data['message'] = "Incentive interval updated";
			}
			else{
				$this->callback_model->add_incentive_slab($from, $to, $amounts, $percentages);
				$data['message'] = "Incentive slab generated";
			}
			$data['success'] = true;

		}
		$data['intervals'] = $this->callback_model->get_incentive_intervals();
		$this->load->view('admin/generate_incentive_slab',$data);
	}

	public function get_incentive_slabs($interval_id) {
		$slabs = $this->callback_model->get_incentive_slabs($interval_id);
		if(empty($slabs)){
			$return = "<tr><td colspan=\"2\" style=\"text-align:center;\">No slabs</td></tr>";
		}
		else{
			$return = "";
			foreach ($slabs as $slab) {
				$return .= ("<tr><td>".$slab->amount."</td><td>".$slab->percentage."</td></tr>");
			}
		}
		echo $return;
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
			$user_name=$this->input->post('user_name');
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
				'user_id'=>$user_name,
				'due_date'=>$due_date,
				'broker_id'=>$sub_broker,
				'status_id'=>$status,
				'notes'=>$notes,
				'date_added'=>date('Y-m-d H:i:s'),
			);
			// print_r($data);exit;
			$query=$this->callback_model->add_callbacks($data);
			redirect(base_url().'admin/callbacks');
		}
		$data['name'] ="generate";
		$data['heading'] ="Generate";
		$this->load->view('admin/generate_callback',$data);
	}

	public function search_callback(){
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

			$data['result'] = $this->callback_model->search_callback($type,$query);
		}
		else
			$data['result'] = false;

		$data['header'] = '';
		$this->load->view('admin/search_callback',$data);
	}

	function revenue_approval($page=1){
		$data['name'] = 'revenue_approval';
		$data['heading'] ="Closed callbacks";
		$this->load->library('pagination');
		$config['base_url'] = base_url() . 'admin/revenue_approval/';

		$config['per_page'] = 100;
		$config['full_tag_open'] = '<ul class="pagination pagination-sm pull-right">';
		$config['full_tag_close'] = '</ul>';

		$config['first_link'] = '&laquo;&laquo;';
		$config['first_tag_open'] = '<li class="prev page">';
		$config['first_tag_close'] = '</li>';

		$config['last_link'] = '&raquo;&raquo;';
		$config['last_tag_open'] = '<li class="next page">';
		$config['last_tag_close'] = '</li>';

		$config['next_link'] = '<i class="fa fa-angle-right"></i>';
		$config['next_tag_open'] = '<li class="navi-mnzl">';
		$config['next_tag_close'] = '</li>';

		$config['prev_link'] = '<i class="fa fa-angle-left"></i>';
		$config['prev_tag_open'] = '<li class="navi-mnzl">';
		$config['prev_tag_close'] = '</li>';

		$config['cur_tag_open'] = '<li class="active"><span>';
		$config['cur_tag_close'] = '</span></li>';

		$config['num_tag_open'] = '<li>';
		$config['num_tag_close'] = '</li>';

		$page =$config['per_page']*($page-1);
		$callback_data = $this->callback_model->all_close_callbacks($config['per_page'],$page);
		$config['total_rows'] = $callback_data['total'];
		$data['result'] = $callback_data['data'];
		$this->pagination->initialize($config);
		$this->load->view('admin/revenue_approval',$data);
	}

	public function get_notes(){
		$id=$this->input->post('id');
		$indiv_callback_data = $this->callback_model->get_callback_data($id);
		$returnHtml = "";
		$i=1;
		foreach ($indiv_callback_data as $key => $value) {
			$returnHtml .= "<tr>";
			$returnHtml .= "<td>".($i++)."</td>";
			$returnHtml .= "<td>".$value->current_callback."</td>";
			$returnHtml .= "<td>".$value->status."</td>";
			$returnHtml .= "<td>".$value->user."</td>";
			$returnHtml .= "<td>".$value->date_added."</td>";
			$returnHtml .= "</tr>";
		}
		echo $returnHtml;
	}

	public function get_callback_details(){
		$id=$this->input->post('id');
		$query=$this->callback_model->get_callback_details($id);
		$data = null;
		if($query){
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
			$indiv_callback_data = $this->callback_model->get_callback_data($id);
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
		}
		header('Content-Type: application/json');
		echo json_encode($data);
	}

	function delete_callback(){
		$id=$this->input->post('id');
		$query=$this->callback_model->delete_callback($id);
		if($query){
			$data = array(
				'status' => true,
			);
		}
		header('Content-Type: application/json');
		echo json_encode($data);
	}

	function update_callback_details(){		
		$id = $this->input->post('callback_id');
		
		$update_data = array(
			'last_update' => date('Y-m-d H:s:i')
		);
		if($this->input->post('dept_id'))
			$update_data['dept_id'] = $this->input->post('dept_id');
		if($this->input->post('name'))
			$update_data['name'] = $this->input->post('name');
		if($this->input->post('contact_no1'))
			$update_data['contact_no1'] = $this->input->post('contact_no1');
		if($this->input->post('contact_no2'))
			$update_data['contact_no2'] = $this->input->post('contact_no2');
		if($this->input->post('callback_type_id'))
			$update_data['callback_type_id'] = $this->input->post('callback_type_id');
		if($this->input->post('email1'))
			$update_data['email1'] = $this->input->post('email1');
		if($this->input->post('email2'))
			$update_data['email2'] = $this->input->post('email2');
		if($this->input->post('project_id'))
			$update_data['project_id'] = $this->input->post('project_id');
		if($this->input->post('leadid'))
			$update_data['leadid'] = $this->input->post('leadid');
		if($this->input->post('status_id'))
			$update_data['status_id'] = $this->input->post('status_id');
		if($this->input->post('sub_source_id'))
			$update_data['broker_id'] = $this->input->post('sub_source_id');
		if($this->input->post('lead_source_id'))
			$update_data['lead_source_id'] = $this->input->post('lead_source_id');
		if($this->input->post('user_id'))
			$update_data['user_id'] = $this->input->post('user_id');
		if($this->input->post('approve')){
			$update_data['active'] = 0;
			$update_data['verified_by'] = $this->session->userdata('user_id');
			$update_data['verified_on'] = date('Y-m-d H:s:i');
		}

		/*if($this->input->post('reason_for_dead'))
			$update_data['reason_for_dead'] = $this->input->post('reason_for_dead');*/

		if($this->input->post('reason_cause'))
			$update_data['reason_cause'] = $this->input->post('reason_cause');

		if($this->input->post('sitevisit_date')){
			$projects = $this->input->post('sitevisit_project_id');
			foreach ($projects as $key => $value) {
				$data=array(
					'callback_id'=>$this->input->post('callback_id'),
					'date'=>$this->input->post('sitevisit_date'),
					'project_id'=>$value,
					'type'=>'1',
					'date_added'=>date('Y-m-d H:s:i'),
				);

				$query=$this->callback_model->add_extra_details($data);
			}
		}

		if($this->input->post('sitevisitdone_date')){
			$projects = $this->input->post('sitevisitdone_project_id');
			foreach ($projects as $key => $value) {
				$data=array(
					'callback_id'=>$this->input->post('callback_id'),
					'date'=>$this->input->post('sitevisitdone_date'),
					'project_id'=>$value,
					'type'=>'2',
					'date_added'=>date('Y-m-d H:s:i'),
				);

				$query=$this->callback_model->add_extra_details($data);
			}
		}

		if($this->input->post('facetoface_date')){
			$projects = $this->input->post('facetoface_project_id');
			foreach ($projects as $key => $value) {
				$data=array(
					'callback_id'=>$this->input->post('callback_id'),
					'date'=>$this->input->post('facetoface_date'),
					'project_id'=>$value,
					'type'=>'3',
					'date_added'=>date('Y-m-d H:s:i'),
				);

				$query=$this->callback_model->add_extra_details($data);
			}
		}

		if($this->input->post('due_date'))
			$update_data['due_date'] = $this->input->post('due_date');

		if($this->input->post('important') !== null)
			$update_data['important'] = $this->input->post('important')?1:0;
		
		$query = $this->callback_model->update_callback($update_data,$id);

		if(!$this->input->post('user_id') && ($this->session->userdata('user_id') == $this->input->post('current_user_id')) )
			$this->tracksCallbacks($this->session->userdata('user_id'), $this->session->userdata('user_name'), $id);

		if($this->input->post('status_id')=='5'){
			$data=array(
				'callback_id'=>$this->input->post('callback_id'),
				'advisor1_id'=>$this->input->post('advisor1_id'),
				'advisor2_id'=>$this->input->post('advisor2_id'),
				'booking'=>$this->input->post('booking'),
				'booking_month'=>$this->input->post('booking_month'),
				'closure_date'=>$this->input->post('closure_date'),
				'customer'=>$this->input->post('customer'),
				'sub_source_id'=>$this->input->post('sub_source_id'),
				'project_id'=>$this->input->post('close_project_id'),
				'sqft_sold'=>$this->input->post('sqft_sold'),
				'plc_charge'=>$this->input->post('plc_charge'),
				'floor_rise'=>$this->input->post('floor_rise'),
				'basic_cost'=>$this->input->post('basic_cost'),
				'other_cost'=>$this->input->post('other_cost'),
				'car_park'=>$this->input->post('car_park'),
				'total_cost'=>$this->input->post('total_cost'),
				'commission'=>$this->input->post('commission'),
				'gross_revenue'=>$this->input->post('gross_revenue'),
				'cash_back'=>$this->input->post('cash_back'),
				'sub_broker_amo'=>$this->input->post('sub_broker_amo'),
				'net_revenue'=>$this->input->post('net_revenue'),
				'share_of_advisor1'=>$this->input->post('share_of_advisor1'),
				'share_of_advisor2'=>$this->input->post('share_of_advisor2'),
				'est_month_of_invoice'=> $this->input->post('est_month_of_invoice'),
				'agreement_status' => $this->input->post('agreement_status'),
				'project_type' => $this->input->post('project_type'),

			);

			$query=$this->callback_model->update_callback_details($data,$id);
		}

		$current_callback=$this->input->post('current_callback');
		$user_id = $this->session->userdata('user_id');
		$date_added = date('Y-m-d H:s:i');
		$ind_callback_data = array(
			"current_callback" => $current_callback,
			"user_id" => $user_id,
			"callback_id" => $id,
			"status_id" => $this->input->post('status_id'),
			"date_added" => $date_added
		);
		$query = $this->callback_model->add_callback_data($ind_callback_data);

		$data = array(
			'status' =>true
		);
		header('Content-Type: application/json');
		echo json_encode($data);
	}

	public function send_mail($type=""){
		if($this->input->post()){
			$this->load->library('email');

			$this->email->initialize(email_config());
			$to = null;

			switch ($type) {
				case 'site-visit':
					$client_name=$this->input->post('client_name');
					$client_email=$this->input->post('client_email');
					$client_visit=$this->input->post('client_visit');
					$assign_by=$this->input->post('assign_by');
					$subject=$this->input->post('subject');
					$relationship_manager=$this->input->post('relationship_manager');
					$message=$this->input->post('message');
					$callback_id=$this->input->post('callback_id');
					$this->db->insert('svd_follow_callback_details',array(
						'callback_id' => $callback_id,
						'user_id' => $this->session->userdata('user_id'),
						'client_name' => $client_name,
						'client_email' => $client_email,
						'client_visit' => $client_visit,
						'assign_by' => $assign_by,
						'subject' => $subject,
						'relationship_manager' => $relationship_manager,
						'message' => $message,
						'date_added' => date('Y-m-d H:i:s')
					));
					$to = $client_email;
					break;

				case 'client-reg':
					$client_email=$this->input->post('client_email');
					$message=$this->input->post('message');
					$subject=$this->input->post('subject');
					$callback_id=$this->input->post('callback_id');
					$this->db->insert('client_reg',array(
						'callback_id' => $callback_id,
						'user_id' => $this->session->userdata('user_id'),
						'client_email' => $client_email,
						'subject' => $subject,
						'message' => $message,
						'date_added' => date('Y-m-d H:i:s')
					));
					$to = $client_email;
					break;

			}

			$message = str_replace ("\r\n", "<br>", $message );
			$message = str_replace ("\n", "<br>", $message );
			$this->email->set_mailtype("html");

			if($to){
				$this->email->from($this->session->userdata('user_email'), $this->session->userdata('user_name'));
				$this->email->to($to);

				$this->email->subject($subject);
				$this->email->message($message);

				$cc = $this->user_model->get_vp_director_admin_emails();
				$cc[] = $this->session->userdata('user_email');

				$this->email->bcc($cc);

				header('Content-Type: application/json');
				if($this->email->send())
					echo json_encode(array("success" => true));
				else
					echo json_encode(array("success" => false));
			}
			else
				echo json_encode(array("success" => false));

		}
	}

	public function dead_leads(){
		
		ini_set('memory_limit', '-1');
		$data['name'] ="more";
		$data['heading'] ="Dead Callbacks";
		$where =" AND cb.status_id=4";
		
		/*if($this->input->post() && $this->input->post('search')!=''){
			$srxhtxt = trim($this->input->post('srxhtxt'));
			$this->session->set_userdata('SRCHTXT', $srxhtxt);		

			$searchDate = $this->input->post('searchDate');
			$this->session->set_userdata('SRCHDT', $searchDate);
		}
		if($this->session->userdata('SRCHTXT')) 	{	
			$searchVal = $this->session->userdata('SRCHTXT');
			$where .=" AND (cb.name='".$searchVal."' OR cb.email1='".$searchVal."' OR cb.contact_no1='".$searchVal."' OR cb.leadid='".$searchVal."' OR p.name='".$searchVal."' OR ls.name = '".$searchVal."' OR concat(u.first_name,' ',u.last_name) ='".$searchVal."' OR b.name='".$searchVal."')";
		}

		if($this->session->userdata('SRCHDT')) {
			if($this->session->userdata('SRCHDT') == 'today')
				$where .=" AND cb.due_date like '%".date('Y-m-d')."%'";
			elseif ($this->session->userdata('SRCHDT') == 'yesterday') 
				$where .=" AND cb.due_date < '".date('Y-m-d', strtotime ('-1 day'))."'";
			elseif ($this->session->userdata('SRCHDT') == 'tomorrow') 
				$where .=" AND cb.due_date > '".date('Y-m-d', strtotime('+ 1 day'))."'";
		}*/

		if($this->input->post()){
			$dept=$this->input->post('dept');
			$project=$this->input->post('project');
			$lead_source=$this->input->post('lead_source');
			$user_name=$this->input->post('user_name');
			$sub_broker=$this->input->post('sub_broker');
			$status=$this->input->post('status');
			$city=$this->input->post('city');
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
			
			$srxhtxt = trim($this->input->post('srxhtxt'));
			if($srxhtxt !==null ){
				$this->session->set_userdata('SRCHTXT', $srxhtxt);	
				if($srxhtxt)			
					$where .=" AND (cb.name='".$srxhtxt."' OR cb.email1='".$srxhtxt."' OR cb.contact_no1='".$srxhtxt."' OR cb.leadid='".$srxhtxt."' OR p.name='".$srxhtxt."' OR ls.name = '".$srxhtxt."' OR concat(u.first_name,' ',u.last_name) ='".$srxhtxt."' OR b.name='".$srxhtxt."')";
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

			if($this->session->userdata('SRCHTXT')){
				$searchVal = $this->session->userdata('SRCHTXT');
				$where .=" AND (cb.name='".$searchVal."' OR cb.email1='".$searchVal."' OR cb.contact_no1='".$searchVal."' OR cb.leadid='".$searchVal."' OR p.name='".$searchVal."' OR ls.name = '".$searchVal."' OR concat(u.first_name,' ',u.last_name) ='".$searchVal."' OR b.name='".$searchVal."')";
			}

			if($this->session->userdata('SRCHDT')!=''){
				if($this->session->userdata('SRCHDT') == 'today')
					$where .=" AND cb.due_date like '%".date('Y-m-d')."%'";
				elseif ($this->session->userdata('SRCHDT') == 'yesterday') 
					$where .=" AND cb.due_date < '".date('Y-m-d')."'";
				elseif ($this->session->userdata('SRCHDT') == 'tomorrow') 
					$where .=" AND cb.due_date > '".date('Y-m-d')."'";
			}
		}

		/*if($this->input->post('reset')){
			$this->session->unset_userdata(['SRCHTXT', 'SRCHDT']);
			redirect('admin/dead_leads');
		}*/
		//------- pagination ------
		$rowCount 				= $this->callback_model->count_search_records(null,$where,$user="admin");
		$data["totalRecords"] 	= $rowCount;
		$data["links"] 			= paginitaion(base_url().'admin/dead_leads/', 3,VIEW_PER_PAGE, $rowCount);
		$page = $this->uri->segment(3);
        $offset = !$page ? 0 : $page;
		//------ End --------------
		$data['result'] = $this->callback_model->search_callback(null,$where,$offset,VIEW_PER_PAGE);

		$this->load->view('admin/dead_leads',$data);
	}

	public function callbacks() {
		$data['name'] ="callbacks";
		$data['heading'] ="Callbacks";
		$where="";
		if($this->input->post()){
			$dept=$this->input->post('dept');
			$project=$this->input->post('project');
			$lead_source=$this->input->post('lead_source');
			$user_name=$this->input->post('user_name');
			$sub_broker=$this->input->post('sub_broker');
			$status=$this->input->post('status');
			$city=$this->input->post('city');
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
			
			$srxhtxt = trim($this->input->post('srxhtxt'));
			if($srxhtxt !==null ){
				$this->session->set_userdata('SRCHTXT', $srxhtxt);	
				if($srxhtxt)			
					$where .=" AND (cb.name='".$srxhtxt."' OR cb.email1='".$srxhtxt."' OR cb.contact_no1='".$srxhtxt."' OR cb.leadid='".$srxhtxt."' OR p.name='".$srxhtxt."' OR ls.name = '".$srxhtxt."' OR concat(u.first_name,' ',u.last_name) ='".$srxhtxt."' OR b.name='".$srxhtxt."')";
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
			//echo $where;		
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

			if($this->session->userdata('SRCHTXT')){
				$searchVal = $this->session->userdata('SRCHTXT');
				$where .=" AND (cb.name='".$searchVal."' OR cb.email1='".$searchVal."' OR cb.contact_no1='".$searchVal."' OR cb.leadid='".$searchVal."' OR p.name='".$searchVal."' OR ls.name = '".$searchVal."' OR concat(u.first_name,' ',u.last_name) ='".$searchVal."' OR b.name='".$searchVal."')";
			}

			if($this->session->userdata('SRCHDT')!=''){
				if($this->session->userdata('SRCHDT') == 'today')
					$where .=" AND cb.due_date like '%".date('Y-m-d')."%'";
				elseif ($this->session->userdata('SRCHDT') == 'yesterday') 
					$where .=" AND cb.due_date < '".date('Y-m-d')."'";
				elseif ($this->session->userdata('SRCHDT') == 'tomorrow') 
					$where .=" AND cb.due_date > '".date('Y-m-d')."'";
			}
		}
		
		//------- pagination ------
		$rowCount 				= $this->callback_model->count_search_records(null,$where,null,null,$user="admin")/*(null,$where,$user="admin")*/;
		$data["totalRecords"] 	= $rowCount;
		$data["links"] 			= paginitaion(base_url().'admin/callbacks/', 3,VIEW_PER_PAGE, $rowCount);
		$page = $this->uri->segment(3);
        $offset = !$page ? 0 : $page;
		//------ End --------------

        $data['result'] = $this->callback_model->search_callback(null,$where,$offset,VIEW_PER_PAGE);
		$this->load->view('admin/callbacks',$data);
	}

	public function reports(){
		$data['name'] = "reports";
		$data['heading'] ="Reports";
		$this->load->view('reports',$data);
	}

	public function email_report() {
		
		$fromDate = $this->input->get('fromDate');
		$toDate = $this->input->get('toDate');
		$city = $this->input->get('city');
		$dept = $this->input->get('dept');
		$reportType = $this->input->get('reportType');
		if($reportType != 'dailyCallback'){
			$report_data = $this->generate_report_data($fromDate, $toDate, $dept, $city, $reportType);
			$to_emails = $this->user_model->get_vp_director_admin_emails();
			$subject = "Report Summary";
		}
		else{
			$report_data = $this->generate_callback_report_data($fromDate, $toDate,$dept, $city, $reportType);
			$subject = "Callback done summary";
			if(!$city)		
				$clause = 'type IN (3,4,5)';
			else
				$clause = "((type = 2 AND city_id = ".$city.") OR type in (3,4,5)) AND active = 1 AND `email` != 'test@test.com'";

			$fetchEmail = $this->user_model-> getUserEmailByClause($clause);
			$tmparry = array();
			foreach ($fetchEmail as $emailData) {
				$tmparry[] = $emailData['email'];
			}
			$tmparry[] = 'nitish.kedia@fullbasketproperty.com';
			$to_emails = implode(',', $tmparry);
		}
		$data['dept'] = $dept;
		$data['city'] = $city;
		$data['fromDate'] = $fromDate;
		$data['toDate'] = $toDate;
		$data['reportType'] = $reportType;
		if($report_data){
			$data = array_merge($data, $report_data);
			
			$mail_body = $this->load->view("mail/header", $data, true) . $this->load->view("mail/details", $data, true) . $this->load->view($data['mail_template'], $data, true) . $this->load->view("mail/footer", $data, true);
				

			$this->load->library('email');
			$config = email_config();
			
			$this->email->initialize($config);
			$this->email->from("admin@leads.com", "Admin");
			$this->email->to($to_emails);
			$this->email->subject($subject);
			$this->email->message($mail_body);
			$this->email->send();

			echo "Success";
			exit;
		}
		echo "Error";
	}

	function generate_report(){	
		$data['name'] = "reports";
		$data['heading'] ="Reports";
		if($this->input->get()){
			if($this->input->get('fromDate')){
				$fromDate=$this->input->get('fromDate');
				$fromTime=$this->input->get('fromTime');
				$fromDate .= " ".$fromTime;
				$toDate=$this->input->get('toDate');
				$toTime=$this->input->get('toTime');
				$toDate .= " ".$toTime;
				$reportType=$this->input->get('reportType');
				$this->session->set_userdata("report-fromDate",$fromDate);
				$this->session->set_userdata("report-toDate",$toDate);
				$this->session->set_userdata("report-type",$reportType);
				$dept = '';
				$city = '';
				$this->session->set_userdata("fromTime",$fromTime);
				$this->session->set_userdata("toTime",$toTime);
			}
			else{
				$dept=$this->input->get('dept');
				$city=$this->input->get('city');
				$this->session->set_userdata("report-dept",$dept);
				$this->session->set_userdata("report-city",$city);
				$fromDate = $this->session->userdata('report-fromDate');
				$toDate = $this->session->userdata('report-toDate');
				$reportType = $this->session->userdata('report-type');
			}
			$data['dept'] = $dept;
			$data['city'] = $city;
			$data['fromDate'] = $fromDate;
			$data['toDate'] = $toDate;
			$data['reportType'] = $reportType;

			if($reportType != 'dailyCallback')
				$report_data = $this->generate_report_data($fromDate, $toDate, $dept, $city, $reportType);
			else
				$report_data = $this->generate_callback_report_data($fromDate, $toDate, $dept, $city, $reportType);

			if($report_data){
				$data = array_merge($data, $report_data);
				$this->load->view($data['view_page'], $data);
			}
			else
				redirect(base_url().'admin/reports');
		}
		else
			redirect(base_url().'admin/reports');
	}

	public function generate_report_data($fromDate, $toDate, $dept, $city, $reportType){
		$callbacks = $this->callback_model->generate_report_data($fromDate,$toDate,$dept,$city, $reportType);
		$return = array();
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
				$return['advisors'] = $advisors;
				$return['projects'] = $projects;
				$return['lead_sources'] = $lead_sources;
				$return['view_page'] = 'reports/lead_report';
				$return['mail_template'] = 'mail/lead_report';
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
				$return['projectCallbacks'] = $projectCallbacks;
				$return['advisors'] = $advisors;
				$return['view_page'] = 'reports/lead_assignment_report';
				$return['mail_template'] = 'mail/lead_assignment_report';
				break;

			case 'site_visit':
				$this->session->set_userdata("report-heading","Site Visit Done Report");
				$advisors = array();
				$site_visits = $this->callback_model->generate_sitevisit_data($dept,$city,$fromDate,$toDate);
				foreach ($site_visits as $key => $value) {
					if($value->cb_status_id == 6){
						if(array_key_exists($value->cb_user_id, $advisors))
							$advisors[$value->cb_user_id]['count'] += 1;
						else
							$advisors[$value->cb_user_id]['count'] = 1;
						$advisors[$value->cb_user_id]['project'] = $value->cb_project_id;
					}
				}
				$return['advisors'] = $advisors;
				$return['facetoface'] = false;
				$return['view_page'] = 'reports/site_visit_report';
				$return['mail_template'] = 'mail/site_visit_report';
				break;

			case 'clent_reg':
				$this->session->set_userdata("report-heading","Client Registration Report");
				$advisors = array();
				foreach ($callbacks as $key => $value) {
					$regDetails = $this->callback_model->get_client_reg_details($value->id);
					if(array_key_exists($value->user_id, $advisors)){
						$advisors[$value->user_id]['count'] += count($regDetails);
					}
					else{
						$advisors[$value->user_id]['count'] = count($regDetails);
					}
				}
				$return['advisors'] = $advisors;
				$return['view_page'] = 'reports/client_reg_report';
				$return['mail_template'] = 'mail/client_reg_report';
				break;

			case 'revenue':
				$this->session->set_userdata("report-heading","Revenue Report");
				$revenue_datas = $this->callback_model->get_revenue_datas($fromDate,$toDate,$dept,$city);
				$return['revenue_datas'] = $revenue_datas;
				$return['view_page'] = 'reports/revenue_report';
				$return['mail_template'] = 'mail/revenue_report';
				break;

			case 'daily_act':
				$this->session->set_userdata("report-heading","Daily Activity Report");
				$statuses = $this->common_model->all_statuses();
				$advisors = array();
				$callback_data = $this->callback_model->get_callbacks_data($fromDate,$toDate,$dept,$city);
				foreach ($callback_data as $key => $value) {
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
				$return['statuses'] = $statuses;
				$return['advisors'] = $advisors;
				$return['view_page'] = 'reports/daily_act_report';
				$return['mail_template'] = 'mail/daily_act_report';
				break;

			case 'site_visit_fixed':
				$this->session->set_userdata("report-heading","Site Visit Fixed Report");
				$advisors = array();
				$site_visits = $this->callback_model->generate_sitevisit_data($dept,$city,$fromDate,$toDate);
				foreach ($site_visits as $key => $value) {
					if(array_key_exists($value->cb_user_id, $advisors)){
						$advisors[$value->cb_user_id]['count'] += 1;
						// if(!in_array($value->cb_project_id, $advisors[$value->cb_user_id]['projects']))
						//     array_push($advisors[$value->cb_user_id]['projects'], $value->cb_project_id);
					}
					else{
						$advisors[$value->cb_user_id]['count'] = 1;
						// $advisors[$value->cb_user_id]['projects'] = array($value->cb_project_id);
					}
					$advisors[$value->cb_user_id]['project'] = $value->cb_project_id;

				}
				$return['advisors'] = $advisors;
				$return['site_visits'] = $site_visits;
				$return['view_page'] = 'reports/site_visit_fixed_report';
				$return['mail_template'] = 'mail/site_visit_fixed_report';
				break;

			case 'face_to_face':
				$this->session->set_userdata("report-heading","Face to Face Report");
				$advisors = array();
				$facetofaces = $this->callback_model->generate_facetoface_data($dept,$city,$fromDate,$toDate);
				foreach ($facetofaces as $key => $value) {
					if($value->cb_status_id == 9){
						if(array_key_exists($value->cb_user_id, $advisors))
							$advisors[$value->cb_user_id]['count'] += 1;
						else
							$advisors[$value->cb_user_id]['count'] = 1;
						$advisors[$value->cb_user_id]['project'] = $value->cb_project_id;
					}
				}
				$return['advisors'] = $advisors;
				$return['facetoface'] = true;
				$return['view_page'] = 'reports/site_visit_report';
				$return['mail_template'] = 'mail/face_to_face_report';
				break;

			case 'due';
				$this->session->set_userdata("report-heading","Due Report");
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
				$return['due_reports'] = $due_reports;
				$return['overdue_reports'] = $overdue_reports;
				$return['view_page'] = 'reports/due_report';
				$return['mail_template'] = 'mail/due_report';
				break;

			default:
				$return = false;
				break;
		}
		return $return;
	}

	public function generate_callback_report_data($fromDate, $toDate, $dept, $city, $reportType) {
		if($reportType == 'dailyCallback') {
			$return = array();
			$startDate = $fromDate;
			$endDate   = $toDate;
			$clause = ['entryDate >=' => $startDate, 'entryDate <=' => $endDate, 'u.city_id'=>$city, 'cb.dept_id'=>$dept];
			if($this->session->userdata('user_type') == 'manager'){
				$qryStr = ['u.reports_to' => $this->session->userdata('user_id')];
				$clause = array_merge($clause, $qryStr);
			}
			$callbackData = $this->callback_model->generate_report_callback_data($clause);
			$return['view_page'] = 'reports/callback_report';
			$return['callbackData'] = $callbackData;
			$return['mail_template'] = 'mail/callback_report';
			return $return;
		}
		return false;
	}

	public function send_daily_report($city_id = 3) {

		$to_emails = $this->user_model->get_vp_director_admin_emails();

		$data['fromDate'] = date('Y-m-d 00:00:00');
		$data['toDate'] = date('Y-m-d 23:59:59');
		$data['dept'] = '';
		$data['city'] = $city_id;
		$data['lead_report'] = $this->generate_report_data($data['fromDate'], $data['toDate'], $data['dept'], $data['city'], 'lead');
		$data['site_visit_report'] = $this->generate_report_data($data['fromDate'], $data['toDate'], $data['dept'], $data['city'], 'site_visit');
		$data['clent_reg_report'] = $this->generate_report_data($data['fromDate'], $data['toDate'], $data['dept'], $data['city'], 'clent_reg');
		$data['revenue_report'] = $this->generate_report_data($data['fromDate'], $data['toDate'], $data['dept'], $data['city'], 'revenue');
		$data['daily_act_report'] = $this->generate_report_data($data['fromDate'], $data['toDate'], $data['dept'], $data['city'], 'daily_act');
		$data['site_visit_fixed_report'] = $this->generate_report_data($data['fromDate'], $data['toDate'], $data['dept'], $data['city'], 'site_visit_fixed');
		$data['face_to_face_report'] = $this->generate_report_data($data['fromDate'], $data['toDate'], $data['dept'], $data['city'], 'face_to_face');

		$mailData['data'] = $data;
		$mail_body = $this->load->view('reports/daily_report', $mailData, true);

		$this->load->library('email');
		$config = email_config();

		$this->email->initialize($config);
		$this->email->from("admin@leads.com", "Admin");
		$this->email->to($to_emails);
		$this->email->subject($this->common_model->get_city_name($city_id)." - Daily Report - ".date('d/m/Y'));
		$this->email->message($mail_body);
		$this->email->send();

		echo "Success";
	}

	public function reset_password($id) {
		$this->user_model->reset_password($id);
		$data = array(
		  'status' => true
		);
		header('Content-Type: application/json');
		echo json_encode($data);
	}

	function check_isnumberexists($number) {
		$data = array("contact_no1" => $number);
		echo json_encode(array("exists"=>$this->callback_model->isexists_callbacks($data)));
	}

	function check_isemailexists() {
		$data = array("email1" => $this->input->get('email'));
		echo json_encode(array("exists"=>$this->callback_model->isexists_callbacks($data)));
	}

	public function get_user_data() {
		$user_id = $this->input->post('id');
		$data = $this->user_model->get_user_data($user_id);
		echo json_encode($data);
	}

	public function update_user($id) {

		$first_name = $this->input->post('first_name');
		$last_name = $this->input->post('last_name');
		$email = $this->input->post('email');
		$reports_to = $this->input->post('reports_to');
		$select_user = $this->input->post('select_user');
		$dept_id = $this->input->post('dept_id');
		$city_id = $this->input->post('city_id');
		$data = array(
			"first_name" => $first_name,
			"last_name" => $last_name,
			"email" => $email
		);
		if($reports_to)
			$data["reports_to"] = $reports_to;
		if($select_user)
			$data["select_user"] = $select_user;
		if($dept_id)
			$data["dept_id"] = $dept_id;
		if($city_id)
			$data["city_id"] = $city_id;
		$q = $this->user_model->update_user($data,$id);
		echo json_encode(array("response" => $q));
	}

	public function manage_cities() {
		$data['name'] ="admin";
		$data['heading'] ="Manage City";
		$data['all_cities'] = $this->common_model->all_cities();
		$this->load->view('admin/cities',$data);
	}

	public function manage_states() {
		$data['name'] ="admin";
		$data['heading'] ="Manage State";
		$data['all_states'] = $this->common_model->all_states();
		$this->load->view('admin/states',$data);
	}

	public function manage_depts() {
		$data['name'] ="admin";
		$data['heading'] ="Manage Departments";
		$data['all_depts'] = $this->common_model->all_depts();
		$this->load->view('admin/depts',$data);
	}

	public function manage_lead_sources() {
		$data['name'] ="admin";
		$data['heading'] ="Manage Lead Source";
		$data['all_lead_sources'] = $this->common_model->all_lead_sources();
		$this->load->view('admin/lead_sources',$data);
	}

	public function manage_projects() {
		$data['name'] ="admin";
		$data['heading'] ="Manage Project";
		$data['all_projects'] = $this->common_model->all_projects();
		$this->load->view('admin/projects',$data);
	}

	public function manage_builders() {
		$data['name'] ="admin";
		$data['heading'] ="Manage Builder";
		$data['all_builders'] = $this->common_model->all_builders();
		$this->load->view('admin/builders',$data);
	}

	public function manage_brokers() {
		$data['name'] ="admin";
		$data['heading'] ="Manage Broker";
		$data['all_brokers'] = $this->common_model->all_brokers();
		$this->load->view('admin/brokers',$data);
	}

	public function manage_callback_types() {
		$data['name'] ="admin";
		$data['heading'] ="Manage Callback Type";
		$data['all_callback_types'] = $this->common_model->all_callback_types();
		$this->load->view('admin/callback_types',$data);
	}

	public function manage_status() {
		$data['name'] ="admin";
		$data['heading'] ="Manage Status";
		$data['all_statuses'] = $this->common_model->all_statuses();
		$this->load->view('admin/statuses',$data);
	}

	function check_state(){
		$code=$this->input->post('code');
		$query=$this->common_model->duplicate_check('state',$code);
		$data = array(
		  'count' =>$query
		);
		header('Content-Type: application/json');
		echo json_encode($data);
	}

	function check_dept(){
		$code=$this->input->post('code');
		$query=$this->common_model->duplicate_check('department',$code);
		$data = array(
		  'count' =>$query
		);
		header('Content-Type: application/json');
		echo json_encode($data);
	}

	function check_lead_source(){
		$code=$this->input->post('code');
		$query=$this->common_model->duplicate_check('lead_source',$code);
		$data = array(
		  'count' =>$query
		);
		header('Content-Type: application/json');
		echo json_encode($data);
	}

	function check_project(){
		$code=$this->input->post('code');
		$query=$this->common_model->duplicate_check('project',$code);
		$data = array(
		  'count' =>$query
		);
		header('Content-Type: application/json');
		echo json_encode($data);
	}

	function check_builder(){
		$code=$this->input->post('code');
		$query=$this->common_model->duplicate_check('builder',$code);
		$data = array(
		  'count' =>$query
		);
		header('Content-Type: application/json');
		echo json_encode($data);
	}

	function check_broker(){
		$code=$this->input->post('code');
		$query=$this->common_model->duplicate_check('broker',$code);
		$data = array(
		  'count' =>$query
		);
		header('Content-Type: application/json');
		echo json_encode($data);
	}

	function check_callback_type(){
		$code=$this->input->post('code');
		$query=$this->common_model->duplicate_check('callback_type',$code);
		$data = array(
		  'count' =>$query
		);
		header('Content-Type: application/json');
		echo json_encode($data);
	}

	function check_city(){
		$code=$this->input->post('code');
		$query=$this->common_model->duplicate_check('city',$code);
		$data = array(
		  'count' =>$query
		);
		header('Content-Type: application/json');
		echo json_encode($data);
	}

	function check_status(){
		$code=$this->input->post('code');
		$query=$this->common_model->duplicate_check('status',$code);
		$data = array(
		  'count' =>$query
		);
		header('Content-Type: application/json');
		echo json_encode($data);
	}

	function check_user(){
		$code=$this->input->post('code');
		$query=$this->common_model->duplicate_check('user',$code);
		$data = array(
		  'count' =>$query
		);
		header('Content-Type: application/json');
		echo json_encode($data);
	}

	function add_state(){
		$state=$this->input->post('state');
		$data=array(
			'name'=>$state,
			'date_added'=>date('Y-m-d H:i:s')
		);
		$query=$this->db->insert('state',$data);
		header('Content-Type: application/json');
		echo json_encode($data);
	}

	function add_dept(){
		$dept=$this->input->post('dept');
		$data=array(
			'name'=>$dept,
			'date_added'=>date('Y-m-d H:i:s')
		);
		$query=$this->db->insert('department',$data);
		header('Content-Type: application/json');
		echo json_encode($data);
	}

	function add_lead_source(){
		$lead_source=$this->input->post('lead_source');
		$data=array(
			'name'=>$lead_source,
			'date_added'=>date('Y-m-d H:i:s')
		);
		$query=$this->db->insert('lead_source',$data);
		header('Content-Type: application/json');
		echo json_encode($data);
	}

	function add_project(){
		$project=$this->input->post('project');
		$builder=$this->input->post('builder');
		$data=array(
			'name'=>$project,
			'builder_id'=>$builder,
			'date_added'=>date('Y-m-d H:i:s')
		);
		$query=$this->db->insert('project',$data);
		header('Content-Type: application/json');
		echo json_encode($data);
	}

	function add_builder(){
		$builder=$this->input->post('builder');
		$data=array(
			'name'=>$builder,
			'date_added'=>date('Y-m-d H:i:s')
		);
		$query=$this->db->insert('builder',$data);
		header('Content-Type: application/json');
		echo json_encode($data);
	}

	function add_broker(){
		$broker=$this->input->post('broker');
		$data=array(
			'name'=>$broker,
			'date_added'=>date('Y-m-d H:i:s')
		);
		$query=$this->db->insert('broker',$data);
		header('Content-Type: application/json');
		echo json_encode($data);
	}

	function add_callback_type(){
		$callback_type=$this->input->post('callback_type');
		$data=array(
			'name'=>$callback_type,
			'date_added'=>date('Y-m-d H:i:s')
		);
		$query=$this->db->insert('callback_type',$data);
		header('Content-Type: application/json');
		echo json_encode($data);
	}

	function add_status(){
		$status=$this->input->post('status');
		$data=array(
			'name'=>$status,
			'date_added'=>date('Y-m-d H:i:s')
		);
		$query=$this->db->insert('status',$data);
		header('Content-Type: application/json');
		echo json_encode($data);
	}

	function add_city(){
		$city=$this->input->post('city');
		$state=$this->input->post('state');
		$data=array(
			'name'=>$city,
			'state_id'=>$state,
			'date_added'=>date('Y-m-d H:i:s')
		);
		$query=$this->db->insert('city',$data);
		header('Content-Type: application/json');
		echo json_encode($data);
	}

	function change_status_state(){
		$id=$this->input->post('id');
		$newStatus = $this->common_model->toggle_status('state',$id);
		$data = array(
			'id' => $id,
			'active' => $newStatus
		);
		header('Content-Type: application/json');
		echo json_encode($data);
	}

	function change_status_dept(){
		$id=$this->input->post('id');
		$newStatus = $this->common_model->toggle_status('department',$id);
		$data = array(
			'id' => $id,
			'active' => $newStatus
		);
		header('Content-Type: application/json');
		echo json_encode($data);
	}

	function change_status_lead_source(){
		$id=$this->input->post('id');
		$newStatus = $this->common_model->toggle_status('lead_source',$id);
		$data = array(
			'id' => $id,
			'active' => $newStatus
		);
		header('Content-Type: application/json');
		echo json_encode($data);
	}

	function change_status_project(){
		$id=$this->input->post('id');
		$newStatus = $this->common_model->toggle_status('project',$id);
		$data = array(
			'id' => $id,
			'active' => $newStatus
		);
		header('Content-Type: application/json');
		echo json_encode($data);
	}

	function change_status_builder(){
		$id=$this->input->post('id');
		$newStatus = $this->common_model->toggle_status('builder',$id);
		$data = array(
			'id' => $id,
			'active' => $newStatus
		);
		header('Content-Type: application/json');
		echo json_encode($data);
	}

	function change_status_city(){
		$id=$this->input->post('id');
		$newStatus = $this->common_model->toggle_status('city',$id);
		$data = array(
			'id' => $id,
			'active' => $newStatus
		);
		header('Content-Type: application/json');
		echo json_encode($data);
	}

	function change_status_broker(){
		$id=$this->input->post('id');
		$newStatus = $this->common_model->toggle_status('broker',$id);
		$data = array(
			'id' => $id,
			'active' => $newStatus
		);
		header('Content-Type: application/json');
		echo json_encode($data);
	}

	function change_status_callback_type(){
		$id=$this->input->post('id');
		$newStatus = $this->common_model->toggle_status('callback_type',$id);
		$data = array(
			'id' => $id,
			'active' => $newStatus
		);
		header('Content-Type: application/json');
		echo json_encode($data);
	}

	function change_status_status(){
		$id=$this->input->post('id');
		$newStatus = $this->common_model->toggle_status('status',$id);
		$data = array(
			'id' => $id,
			'active' => $newStatus
		);
		header('Content-Type: application/json');
		echo json_encode($data);
	}

	function change_status_user(){
		$id=$this->input->post('id');
		$newStatus = $this->common_model->toggle_status('user',$id);
		$data = array(
			'id' => $id,
			'active' => $newStatus
		);
		header('Content-Type: application/json');
		echo json_encode($data);
	}

	function bulk_generate_callbacks(){
		$data['name'] ="bulk generate";
		$data['heading'] ="Bulk Generate";
		$this->load->view('admin/bulk_generate_callback',$data);
	}

	function bulk_upload_callback() {
		if(isset($_POST["submit"])) {
			$count = 0;
			$duplicate = 0;
			$target = 'uploads/'.uniqid().'.xls';
			if (move_uploaded_file($_FILES["file"]["tmp_name"], $target)){
				$this->load->library('excel');
				$objPHPExcel = PHPExcel_IOFactory::load($target);
				foreach ($objPHPExcel->getWorksheetIterator() as $worksheet)
					break;
				$lastColumn = $worksheet->getHighestColumn();
				$tempArray = $worksheet->rangeToArray('A1:'.$lastColumn.'1');
				$keyArray = $tempArray[0];
				// print_r($keyArray);exit;
				$nameKey = array_search('Name', $keyArray);
				$contact1Key = array_search('Contact No', $keyArray);
				$contact2Key = array_search('Contact No 2', $keyArray);
				$email1Key = array_search('Email', $keyArray);
				$email2Key = array_search('Email 2', $keyArray);
				$leadIdKey = array_search('Lead Id', $keyArray);
				$notesKey = array_search('Notes', $keyArray);
				$highestRow = $worksheet->getHighestRow();
				$newCallbacks = array();
				for($i = 2;$i <= $highestRow; $i++ ){
					$name = (string) $worksheet->getCellByColumnAndRow($nameKey, $i);
					if(($name == '') || ($name == null))
						break;
					$contact_no1 = (string) $worksheet->getCellByColumnAndRow($contact1Key, $i);
					if($contact2Key)
						$contact_no2 = (string) $worksheet->getCellByColumnAndRow($contact2Key, $i);
					else
						$contact_no2 = "";
					if($contact_no1 == $contact_no2)
						$contact_no2 = '';
					$email1 = (string) $worksheet->getCellByColumnAndRow($email1Key, $i);
					if($email2Key)
						$email2 = (string) $worksheet->getCellByColumnAndRow($email2Key, $i);
					else
						$email2 = "";
					if($email1 == $email2)
						$email2 = '';
					$leadId = (string) $worksheet->getCellByColumnAndRow($leadIdKey, $i);
					$notes = (string) $worksheet->getCellByColumnAndRow($notesKey, $i);
					$temp_due_date = $worksheet->getCellByColumnAndRow(14, $i);
					$data = array(
						'name'=>trim($name),
						'contact_no1'=>trim($contact_no1),
						'contact_no2'=>trim($contact_no2),
						'email1'=>trim($email1),
						'email2'=>trim($email2),
						'leadid'=>trim($leadId),
						'notes'=>trim($notes),
					);
					// print_r($data);exit;
					if (!($this->callback_model->isexists_callbacks($data))){
						array_push($newCallbacks, $data);
						$count++;
					}
					else
						$duplicate++;
				}
				unlink($target);
				$data['callbacks'] = $newCallbacks;
				$data['duplicate_callback'] = $duplicate;
				$data['success_callback'] = $count;
				$this->load->view('admin/bulk_generate_report',$data);
			}
			else
				echo "Upload error";
		}
		else
			echo "No data";
	}

	function save_bulk_upload_callbacks() {
		if($this->input->post()){
			$callbacks=$this->input->post('callbacks');
			$dept=$this->input->post('dept');
			$project=$this->input->post('project');
			$lead_source=$this->input->post('lead_source');
			$callback_type=$this->input->post('callback_type');
			$user=$this->input->post('user');
			$broker=$this->input->post('broker');
			$status=$this->input->post('status');
			$due_date=$this->input->post('due_date');
			$due_time=$this->input->post('due_time');

			$callbacks = json_decode($callbacks,true);
			$due_date = $due_date." ".$due_time;
			foreach ($callbacks as $key => $value) {
				$data=array(
					'dept_id'=>$dept,
					'name'=>$value['name'],
					'contact_no1'=>$value['contact_no1'],
					'contact_no2'=>$value['contact_no2'],
					'callback_type_id'=>$callback_type,
					'email1'=>$value['email1'],
					'email2'=>$value['email2'],
					'project_id'=>$project,
					'lead_source_id'=>$lead_source,
					'leadid'=>$value['leadid'],
					'user_id'=>$user,
					'due_date'=>$due_date,
					'broker_id'=>$broker,
					'status_id'=>$status,
					'notes'=>$value['notes'],
					'date_added'=>date('Y-m-d H:i:s'),
				);
				if (!($this->callback_model->isexists_callbacks($data))){
					$this->callback_model->add_callbacks($data);
				}
			}
		}
		redirect(base_url().'admin/callbacks');
	}

	public function online_leads(){
		$data['name'] ="more";
		$data['heading'] ="Online Callbacks";
		$data['leads'] = $this->common_model->getAll('online_leads');
		$data['projects']= $this->common_model->all_active_projects();
		$this->load->view('admin/online_leads',$data);
	}

	public function fetch_online_leads(){
		$url = "http://www.99acres.com/99api/v1/getmy99Response/OeAuXClO43hwseaXEQ/uid/";
		$username = "basket.property";
		$password = "bphfbp2014";
		$start_date = date("Y-m-d", strtotime('yesterday'));
		$end_date = date("Y-m-d");
		$request = "<?xml version='1.0'?><query><user_name>$username</user_name><pswd>$password</pswd><start_date>$start_date 00:00:00</start_date><end_date>$end_date 00:00:00</end_date></query>";
		$allParams = array('xml'=>$request);
		$leads = $this->get99AcresLeads($allParams,$url);
		$data = simplexml_load_string($leads);

		$lead_data = array();
		if(isset($data->Resp) && count($data->Resp)>0){
			foreach ($data->Resp as $value) {
				$notes = (string) $value->QryDtl->QryInfo;
				$leadid = (string) $value->QryDtl->ProjId;
				$projectname = (string) $value->QryDtl->ProjName;
				$contactname = (string) $value->CntctDtl->Name;
				$contactemail = (string) $value->CntctDtl->Email;
				$contactphone = (string) $value->CntctDtl->Phone;
				$temp = array(
					"source" => "99acres",
					"name" => $contactname,
					"phone" => $contactphone,
					"email" => $contactemail,
					"project" =>$projectname,
					"leadid" => $leadid,
					"notes" => $notes,
					"lead_date" => date("Y-m-d", strtotime('yesterday'))
				);
				$this->common_model->insertRow($temp, 'online_leads');
			}
		}
		echo "Success";
	}

	public function save_online_leads(){
		if($this->input->post()){
			$dept=$this->input->post('dept');
			$callback_type=$this->input->post('callback_type');
			$user=$this->input->post('user');
			$broker=$this->input->post('broker');
			$status=$this->input->post('status');
			$due_date=$this->input->post('due_date');
			$due_time=$this->input->post('due_time');
			$checked=$this->input->post('check');
			foreach ($checked as $key) {
				$return[] = $key;
				$lead_data = $this->common_model->getFromId($key, 'id', 'online_leads');
				$data=array(
					'dept_id'=>$dept,
					'name'=>$lead_data->name,
					'contact_no1'=>$lead_data->phone,
					'callback_type_id'=>$callback_type,
					'email1'=>$lead_data->email,
					'project_id'=>$this->input->post('project_'.strval($key)),
					'lead_source_id'=>3,
					'leadid'=>$lead_data->leadid,
					'user_id'=>$user,
					'due_date'=>$due_date,
					'broker_id'=>$broker,
					'status_id'=>$status,
					'notes'=>$lead_data->notes,
					'date_added'=>date('Y-m-d H:i:s'),
				);
				if (!($this->callback_model->isexists_callbacks($data)))
					$this->callback_model->add_callbacks($data);
				$this->common_model->deleteWhere(array('id'=>$key), 'online_leads');
			}
		}
		echo json_encode($return);
	}

	function get99AcresLeads($allParams,$url){
		$crl = curl_init($url);
		curl_setopt ($crl, CURLOPT_POST, 1);
		curl_setopt ($crl, CURLOPT_POSTFIELDS, $allParams);
		curl_setopt ($crl, CURLOPT_RETURNTRANSFER,1);
		return curl_exec ($crl);
	}

	function dead_leads_reassign(){
		if($this->input->post('chkValues')) {
			$valuesArry = json_decode($this->input->post('chkValues'), true);
			$this->session->set_userdata('CHKVALUES', $valuesArry );
			if($this->session->userdata('CHKVALUES'))
				echo 1;
			else
				echo 0;
			exit();
		}
	}

	function post_dead_leads_reassing(){
		$results 			= array();
		$results['type'] 	= 0;

		if($this->input->post('userId') && $this->input->post('stsId')) {
			if($this->session->userdata('CHKVALUES')){
				$params = array(
					'user_id'		=> $this->input->post('userId'),
					'status_id'		=> $this->input->post('stsId'),
					'due_date'		=> date('Y-m-d H:i:s'),
					'last_update'	=> date('Y-m-d H:i:s'),
					'date_added'	=> date('Y-m-d H:i:s'),
				);
				foreach ($this->session->userdata('CHKVALUES') as $value) {
					$this->callback_model->updateCallbacksData($params, ['id'=>$value]);
				}
				$this->session->unset_userdata('CHKVALUES');
				$results['type'] = 1;
				$results['msg']  = 'Reassign successfull.';
			}
			else
				$results['msg'] = 'Please select userlists!';
		}
		else 
			$results['msg'] = 'Select user and status!';

		echo json_encode($results);exit();
	}

	public function dead_reason(){
		$data['name'] ="admin";
		$data['heading'] ="Dead Reason";
		$data['results'] = $this->common_model->getDeadReasons();
		$this->load->view('admin/deadReason', $data);
	}

	public function add_dead_reason(){
		$result         = array();
		$result['type'] = 0;

		if($this->input->post('reason')) {
			$exists = $this->common_model->checkExistsDeadReason(['name'=>$this->input->post('reason')]);
			if(!$exists) {
				$params = array(
					'name'		=> $this->input->post('reason'),
					'status'	=> 'Y',
					'entryDate' => date('Y-m-d H:i:s')
				);
				$insId = $this->common_model->insertDeadReason($params);
				if($insId) {
					$result['type'] = 1;
					$result['msg'] = 'Success!';
				}
				else
					$result['msg'] = 'Error!';
			}
			else
				$result['msg'] = 'Already exists!';
		}
		else
			$result['msg'] = 'Enter Reason!';

		$this->output
		->set_status_header(200)
		->set_content_type('application/json', 'utf-8')
		->set_output(json_encode($result, JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES))
		->_display();
		exit();
	}

	function dead_reason_delete(){
		$this->load->library('user_agent');
		$id = $this->input->get('id');
		if($id)
			$this->common_model->deleteDeadReasons($id);
		redirect($this->agent->referrer());
	}

	function dead_reason_status(){
		$this->load->library('user_agent');
		$id = $this->input->get('id');
		$sts = $this->input->get('sts');
		if($id && $sts) {
			$params = array(
				'status' => ($sts == 'Y') ? 'N' : 'Y',
			);
			$this->common_model->updateDeadReason($params, $id);
		}
		redirect($this->agent->referrer());
	}

	function tracksCallbacks($userId, $userName, $callbackId){
		$params = array(
			'userId'	=> $userId,
			'userName'	=> $userName,
			'callbackId'=> $callbackId,
			'entryDate' => date('Y-m-d h:i:s')
		);
		$this->callback_model->insertCallbackTracks($params);
	}


	function view_callbacks_lists(){
		$usrId 		= $this->input->get('userId');
		$fromDate 	= $this->input->get('fromDate');
		$toDate 	= $this->input->get('endDate');
		if($usrId && $fromDate && $toDate) {
			$data['name'] = "reports";
			$advisorData = $this->user_model->get_user_data($usrId);
			$data['heading']  = "Callback report for ".$advisorData['first_name']." ".$advisorData['last_name'];
			$data['duration'] = "<strong>From</strong> <em>".$fromDate."</em> <strong>To</strong> <em>".$toDate."</em>";
			
			$clause = "ct.userId =".$usrId." AND ct.entryDate BETWEEN '".$fromDate."' AND '".$toDate."'";
			

			//------- pagination ------
			$rowCount 				= $this->callback_model->countCallbackLists($clause);
			$data["totalRecords"] 	= $rowCount;
			$data["links"] 			= paginitaionWithQueryString(base_url().'admin/view_callbacks_lists/', 3, VIEW_PER_PAGE, $rowCount, $this->input->get());	
			$page = $this->uri->segment(3);
	        $offset = !$page ? 0 : $page;
			//------ End --------------
			$data['result'] = $this->callback_model->getCallbackLists($clause, $offset, VIEW_PER_PAGE);
			$this->load->view('reports/view_callbacks_lists.php', $data);
		}
		else
			show_404();
	}

	function permission_lists() {
		$userId = $this->input->post('id');
		$result = array();
		if($userId) {
            $result['prntModules'] = $this->common_model->getNavbarByClause(['status' => 'Y', 'parentId'=>0]);
            $result['chldModules'] = $this->common_model->getNavbarByClause(['status' => 'Y', 'parentId !='=>0]);
            $fetchData = $this->common_model->checkModulePermission(['userId' => $userId]);
            if($fetchData['accessLists'])
            	$result['userAccess'] = json_decode($fetchData['accessLists'], true);
            echo json_encode($result);
            exit();
		}
		else
			echo 'User Id not found!';
	}

	function post_module_permission(){
		$result = array();
		$result['type'] = 0;
		if($this->input->post('access') && $this->input->post('userId')) {			
			$params = array(
				'userId'		=> $this->input->post('userId'),
				'accessLists'	=> json_encode($this->input->post('access')),
				'entryDate'		=> date('Y-m-d H:i:s')
			);
			$chkExists = $this->common_model->checkModulePermission(['userId' => $this->input->post('userId')]);
			if(!$chkExists){
				$insId = $this->common_model->postAccessQuery($params);			
				$result['type'] = 1;
				$result['msg']  = 'Permission set successfully.';
			}
			else{
				$clause = ['userId' => $this->input->post('userId')];
				if($this->common_model->updateAccessQuery($clause, $params)){
					$result['type'] = 1;
					$result['msg']  = 'Permission update successfully.';
				}
				else
					$result['msg']  = 'Permission update failed!';
			}
		}
		elseif($this->input->post('userId')) {
			$chkExists = $this->common_model->checkModulePermission(['userId' => $this->input->post('userId')]);
			if($chkExists){
				$this->common_model->deleteAccess(['userId' => $this->input->post('userId')]);
				$result['type'] = 1;
				$result['msg']  = 'Permission revoked successfully.';
			}
			else
				$result['msg']  = 'Permission revoked failed!';
		}
		else
			$result['msg']  = 'Please select modules!';

		echo json_encode($result);
		exit();
	}

	function manage_admin(){
		$data['name'] ="admin";
		$data['heading'] ="Manage Admin";
		if($this->input->post()){
			$first_name=$this->input->post('first_name');
			$last_name=$this->input->post('last_name');
			$emp_code=$this->input->post('emp_code');
			$email=$this->input->post('email');			
			$savedata=array(
				'first_name'=>$first_name,
				'last_name'=>$last_name,
				'type'=>5,
				'emp_code'=>$emp_code,
				'email'=>$email,
				'password'=>md5($emp_code),
				'loginid'=>$emp_code,
				'date_added'=>date('Y-m-d H:i:s')
			);
			$this->user_model->add_user($savedata);
		}
		$data['all_admins'] = $this->user_model->all_admins();
		$this->load->view('admin/manage_admin', $data);
	}

	function getPermission($userId){
        $this->load->model('login_model');
        $fetchData = $this->login_model->getModulePermission(['userId' => $userId]);
        //echo '<pre>'; print_r($userId); echo '<pre>';
        $permission = $fetchData['accessLists'];
        $this->session->set_userdata('permissions', $permission);
    }

}
