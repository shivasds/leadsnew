<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Callback_model extends MY_Model {

    public function __construct()
    {
        // Call the CI_Model constructor
        parent::__construct();
    }

    function isexists_callbacks($data){
        $where = "(";

        if(isset($data['contact_no1'])){
            if($data['contact_no1'] != ''){
                $where .= "contact_no1='".$data['contact_no1']."' OR contact_no2='".$data['contact_no1']."' OR ";
            }
        }
        if(isset($data['contact_no2'])){
            if($data['contact_no2'] != ''){
                $where .= "contact_no1='".$data['contact_no2']."' OR contact_no2='".$data['contact_no2']."' OR ";
            }
        }
            
        if(isset($data['email1'])){
            if($data['email1'] != ''){
                $where .= "email1='".$data['email1']."' OR email2='".$data['email1']."' OR ";
            }
        }
            
        if(isset($data['email2'])){
            if($data['email2'] != ''){
                $where .= "email1='".$data['email2']."' OR email2='".$data['email2']."' OR ";
            }
        }
            
        $where = rtrim($where,' OR');
        $where .= ")";
        if($where!= "()"){
            $this->db->select('*');
            $this->db->where($where, NULL, FALSE);
            $this->db->from('callback');
            $result = $this->db->get()->result();
            if(count($result) > 0){
                return true;
            }
        }
        return false;
    }

    function add_user_target($user_id,$month,$target){
        $temp = explode("/", $month);
        if(count($temp) > 1){
            $month = $temp[0];
            $year = $temp[1];
            $data = array(
                "user_id" => $user_id,
                "month" => $month,
                "year" => $year
            );
            $this->db->where($data);
            if($this->db->count_all_results("user_target") > 0){
                $this->db->update("user_target",array("target"=>$target),$data);
            }
            else{
                $data["target"] = $target;
                $this->db->insert("user_target", $data);
            }
        }
    }

    function add_incentive_slab($from, $to, $amounts, $percentages) {
        $interval_data = array(
            "from" => $from,
            "to" => $to,
            "date_added" => date('Y-m-d H:s:i')
        );
        $this->db->insert('incentive_interval',$interval_data);
        $interval_id = $this->db->insert_id();
        foreach ($amounts as $key => $value) {
            if(isset($percentages[$key])){
                $slab_data = array(
                    "interval_id" => $interval_id,
                    "amount" => $value,
                    "percentage" => $percentages[$key]
                );
                $this->db->insert('incentive_slab',$slab_data);
            }
        }
    }

    function fetch_employee_incentive_slabs(){
        $this->db->select("incentive_interval.*,incentive_slab.*")
            ->where("incentive_interval.date_added in (SELECT max(date_added) FROM incentive_interval)")
            ->from('incentive_interval')
            ->join('incentive_slab','incentive_slab.interval_id=incentive_interval.id');
        return $this->db->get()->result();
    }

    function add_callbacks($data){
        $query=$this->db->insert('callback',$data);
        $lstId=$this->db->insert_id();

        $temp_data= array(
            "callback_id" => $lstId,
            "current_callback" => $data['notes']?$data['notes']:'Added',
            "status_id" => $data['status_id'],
            "user_id" => $data['user_id'],
            "date_added" => $data['date_added']
        );
        $query=$this->db->insert('callback_data',$temp_data);

        if($data['user_id']){
            $userdata = $this->db->select('*')
                ->where('id', $data['user_id'])
                ->from('user')
                ->get()->row();
            if(!empty($userdata)){
                if(($userdata->email) && (in_array($userdata->type, array('1','2')))){
                    $this->load->library('email');
                    $config = email_config();

                    $this->email->initialize($config);
                    $this->email->from("admin@leads.com", "Admin");
                    $this->email->to($userdata->email);
                    $this->email->subject("New lead added");
                    $this->email->message("There is one new lead assigned to you");
                    $this->email->send();
                }
            }
        }

        $this->deleteWhere(array('callback_id'=>$lstId), 'close_callback_details');
        $this->db->insert('close_callback_details',array('callback_id'=> $lstId,'date_added'=>date('Y-m-d H:s:i')));

        $user_id = $this->session->userdata('user_id');
        $date_added = date('Y-m-d H:s:i');
        $message = "Lead added in the system";
        $query=$this->db->insert('callback_log',array(
            'callback_id' => $lstId,
            'user_id' => $user_id,
            'details' => $message,
            'date_added' => $date_added
        ));
        return $query?true:false;
    }

    function search_callback_count($type,$query,$user="admin"){
        if($type){
            switch ($type) {
                case 'name':
                    $this->db->where("cb.name LIKE '%".$query."%'", NULL, FALSE);
                    break;

                case 'contact':
                    $this->db->where("(cb.contact_no1 LIKE '%".$query."%' OR cb.contact_no2 LIKE '%".$query."%')", NULL, FALSE);
                    break;

                case 'email':
                    $this->db->where("(cb.email1 LIKE '%".$query."%' OR cb.email2 LIKE '%".$query."%')", NULL, FALSE);
                    break;

                case 'project':
                    $this->db->where("p.name LIKE '%".$query."%'", NULL, FALSE);
                    break;
                
            }
        }
        else{
            if(strpos($query, 'status_id=4') === false)
                $this->db->where("cb.status_id != 4 ", NULL, FALSE);
            if(strpos($query, 'status_id=5') === false)
                $this->db->where("cb.status_id != 5 ", NULL, FALSE);
            if($query != "")
                $this->db->where("1".$query, NULL, FALSE);
        }   
        if($user == 'manager'){
            $this->db->where("(cb.user_id in(select id from user where reports_to ='".$this->session->userdata('user_id')."') OR cb.user_id = ".$this->session->userdata('user_id').")", NULL, FALSE);
        }
        elseif ($user == 'user') {
            $this->db->where("cb.user_id",$this->session->userdata('user_id'));
        }
        $this->db->join('project as p','p.id=cb.project_id','left');
        $this->db->from('callback as cb');
        return $this->db->count_all_results();
    }
    function count_search_records($type,$query,$limit=null,$offset=null,$user="admin",$request=null, $report=""){
    	
        $this->db->select('cb.*,p.name as project_name,ls.name as lead_source_name,concat(u.first_name,"",u.last_name) as user_name,b.name as broker_name,s.name as status_name');
        if($type){
            switch ($type) {
                case 'name':
                    $this->db->where("cb.name LIKE '%".$query."%'", NULL, FALSE);
                    break;

                case 'contact':
                    $this->db->where("(cb.contact_no1 LIKE '%".$query."%' OR cb.contact_no2 LIKE '%".$query."%')", NULL, FALSE);
                    break;

                case 'email':
                    $this->db->where("(cb.email1 LIKE '%".$query."%' OR cb.email2 LIKE '%".$query."%')", NULL, FALSE);
                    break;

                case 'project':
                    $this->db->where("p.name LIKE '%".$query."%'", NULL, FALSE);
                    break;
                
            }
        }
        else{
            if($request != "report"){
                if(strpos($query, 'status_id=4') === false)
                    $this->db->where("cb.status_id != 4 ", NULL, FALSE);
                if(strpos($query, 'status_id=5') === false)
                    $this->db->where("cb.status_id != 5 ", NULL, FALSE);
            }
            if($query != "")
                $this->db->where("1".$query, NULL, FALSE);
        }   
        if($user == 'manager'){
            $this->db->where("(cb.user_id in(select id from user where reports_to ='".$this->session->userdata('user_id')."') OR cb.user_id = ".$this->session->userdata('user_id').")", NULL, FALSE);
        }
        elseif ($user == 'user') {
            $this->db->where("cb.user_id",$this->session->userdata('user_id'));
        }
        $this->db->join('project as p','p.id=cb.project_id','left');
        $this->db->join('lead_source as ls','ls.id=cb.lead_source_id','left');
        $this->db->join('user as u','u.id=cb.user_id','left');
        $this->db->join('broker as b','b.id=cb.broker_id','left');
        $this->db->join('status as s','s.id=cb.status_id','left');
        if($report == "clent_reg"){
            $this->db->join('client_reg as cr','cr.callback_id=cb.id');
            $this->db->group_by('cr.callback_id');
        }
        $this->db->from('callback as cb');
        return $this->db->count_all_results();
    }
    function search_callback($type,$query,$limit=null,$offset=null,$user="admin",$request=null, $report=""){

        $this->db->select('cb.*,p.name as project_name,ls.name as lead_source_name,concat(u.first_name," ",u.last_name) as user_name,b.name as broker_name,s.name as status_name');
        if($type){
            switch ($type) {
                case 'name':
                    $this->db->where("cb.name LIKE '%".$query."%'", NULL, FALSE);
                    break;

                case 'contact':
                    $this->db->where("(cb.contact_no1 LIKE '%".$query."%' OR cb.contact_no2 LIKE '%".$query."%')", NULL, FALSE);
                    break;

                case 'email':
                    $this->db->where("(cb.email1 LIKE '%".$query."%' OR cb.email2 LIKE '%".$query."%')", NULL, FALSE);
                    break;

                case 'project':
                    $this->db->where("p.name LIKE '%".$query."%'", NULL, FALSE);
                    break;
                
            }
        }
        else{
            if($request != "report"){
                if(strpos($query, 'status_id=4') === false)
                    $this->db->where("cb.status_id != 4 ", NULL, FALSE);
                if(strpos($query, 'status_id=5') === false)
                    $this->db->where("cb.status_id != 5 ", NULL, FALSE);
            }
            if($query != "")
                $this->db->where("1".$query, NULL, FALSE);
        }   
        if($user == 'manager'){
            $this->db->where("(cb.user_id in(select id from user where reports_to ='".$this->session->userdata('user_id')."') OR cb.user_id = ".$this->session->userdata('user_id').")", NULL, FALSE);
        }
        elseif ($user == 'user') {
            $this->db->where("cb.user_id",$this->session->userdata('user_id'));
        }
        $this->db->join('project as p','p.id=cb.project_id','left');
        $this->db->join('lead_source as ls','ls.id=cb.lead_source_id','left');
        $this->db->join('user as u','u.id=cb.user_id','left');
        $this->db->join('broker as b','b.id=cb.broker_id','left');
        $this->db->join('status as s','s.id=cb.status_id','left');
        if($report == "clent_reg"){
            $this->db->join('client_reg as cr','cr.callback_id=cb.id');
            $this->db->group_by('cr.callback_id');
        }
        $this->db->from('callback as cb');
        // if($offset !== false)
        //     $this->db->limit($limit,$offset);
        $this->db->order_by('date_added','DESC');
        if($offset)
            $this->db->limit($offset, $limit);
        $query=$this->db->get();
        return $query?$query->result():array();
    }

    function all_close_callbacks($limit=false,$offset=false){
        $this->db->select('cb.*,p.name as project_name,ls.name as lead_source_name,concat(u.first_name," ",u.last_name) as user_name,b.name as broker_name,s.name as status_name');
        $this->db->where("cb.status_id = 5 ", NULL, FALSE);
        $this->db->where("cb.active = 1 ", NULL, FALSE);
        $this->db->join('project as p','p.id=cb.project_id','left');
        $this->db->join('lead_source as ls','ls.id=cb.lead_source_id','left');
        $this->db->join('user as u','u.id=cb.user_id','left');
        $this->db->join('broker as b','b.id=cb.broker_id','left');
        $this->db->join('status as s','s.id=cb.status_id','left');
        $this->db->from('callback as cb');
        $return['total'] = $this->db->count_all_results('',false);
        if($offset !== false)
            $this->db->limit($limit,$offset);
        $this->db->order_by('cb.date_added','DESC');
        $query=$this->db->get();
        $return['data'] = $query?$query->result():array();
        return $return;
    }

    function get_incentive_intervals(){
        $this->db->select('incentive_interval.*, (select count(*) from incentive_slab where interval_id=incentive_interval.id) as count')
            ->from('incentive_interval');
        return $this->db->get()->result();
    }

    function get_target($user_id,$month){
        $temp = explode("/", $month);
        if(count($temp) > 1){
            $year = $temp[1];
            $month = $temp[0];
            $this->db->select("target")
                ->where("user_id",$user_id)
                ->where("month",$month)
                ->where("year",$year)
                ->from("user_target");
            $row = $this->db->get()->row();
            if(isset($row->target))
                return $row->target;
        }
        return "";
    }

    function get_incentive_slabs($interval_id){
        $this->db->select()
            ->where('interval_id',$interval_id)
            ->from('incentive_slab');
        return $this->db->get()->result();
    }

    function fetch_important_callbacks($user_id = false){
        $this->db->select('cb.*,concat(u.first_name,"",u.last_name) as user_name');
        $this->db->where("cb.important = 1 ", NULL, FALSE);
        $this->db->where("cb.active = 1 ", NULL, FALSE);
        $this->db->where("cb.status_id != 4 ", NULL, FALSE);
        $this->db->where("cb.status_id != 5 ", NULL, FALSE);
        if($user_id)
            $this->db->where("cb.user_id", $user_id);
        $this->db->join('user as u','u.id=cb.user_id','left');
        $this->db->from('callback as cb');

        $this->db->order_by('cb.date_added','DESC');
        $query=$this->db->get();
        $callbacks = $query->result();
        $i_callbacks = array();
        foreach ($callbacks as $key => $value) {
            $value->last_note = $this->get_last_added_note($value->id);
            array_push($i_callbacks, $value);
        }
        return $i_callbacks;
    }

    function get_dar_data($user_id, $date){
        $this->db->select()
            ->where('dar.date',$date)
            ->where('(dar.user_id = '.$user_id.' OR user.reports_to = '.$user_id.')')
            ->from('dar')
            ->join('user','user.id=dar.user_id');
        return $this->db->get()->result();
    }

    function check_yesterdays_dar($user_id){
        return $this->db->select()
            ->where('date',date('Y-m-d',strtotime("-1 days")))
            ->where('user_id', $user_id)
            ->count_all_results('dar');
    }

    function fetch_callback_count($user_id=null,$period='all',$where="",$closeordead=false){
        // $this->db->where("cb.active = 1 ", NULL, FALSE);
        if($user_id)
            $this->db->where("cb.user_id = $user_id ", NULL, FALSE);
        if($where)
            $this->db->where($where, NULL, false);
        if(!$closeordead){
            $this->db->where("cb.status_id != 4 ", NULL, FALSE);
            $this->db->where("cb.status_id != 5 ", NULL, FALSE);
        }
        switch (strtolower($period)) {
            case 'today':
                $this->db->where("DATE(cb.due_date) = '".date('Y-m-d')."' ", NULL, FALSE);
                break;

            case 'this month':
                $this->db->where("DATE(cb.due_date) >= '".date('Y-m-01')."' ", NULL, FALSE);
                $this->db->where("DATE(cb.due_date) <= '".date('Y-m-t')."' ", NULL, FALSE);
                break;

            case 'overdue':
                $this->db->where("cb.due_date < '".date('Y-m-d H:i:s')."' ", NULL, FALSE);
                break;
            
        }
        $this->db->from('callback as cb');

        return $this->db->count_all_results();
    }

    function get_overdue_lead_count(){
        $this->db->where("cb.active = 1 ", NULL, FALSE);
        $this->db->where("cb.status_id != 4 ", NULL, FALSE);
        $this->db->where("cb.status_id != 5 ", NULL, FALSE);
        $this->db->where("cb.due_date <= '".date('Y-m-d H:i:s')."' ", NULL, FALSE);
        $this->db->from('callback as cb');

        return $this->db->count_all_results();
    }

    function fetch_yesterday_callback_count($user_id){
        $this->db->distinct();
        $this->db->select('cbd.callback_id');
        $this->db->where("cbd.user_id = $user_id ", NULL, FALSE);
        $this->db->where("DATE(cbd.date_added) = '".date('Y-m-d',strtotime("-1 days"))."' ", NULL, FALSE);
        $this->db->from('callback_data as cbd');

        return $this->db->count_all_results();
    }

    function get_today_calls($user_id, $day="today"){
        $date = ($day == "yesterday")?date('Y-m-d',strtotime("-1 days")):date('Y-m-d');
        $this->db->where("cbd.user_id = $user_id ", NULL, FALSE);
        $this->db->where("DATE(cbd.date_added) = '".$date."' ", NULL, FALSE);
        $this->db->from('callback_data as cbd');

        return $this->db->count_all_results();
    }

    function fetch_leads_count($user_id,$status=null){
        $this->db->distinct();
        $this->db->select('cbd.*');
        $this->db->where("cbd.user_id = $user_id ", NULL, FALSE);
        switch ($status) {
            case 'dead':
                $this->db->where("cbd.status_id = 4 ", NULL, FALSE);
                break;
            
            case 'close':
                $this->db->where("cbd.status_id = 5 ", NULL, FALSE);
                break;

            case 'active':
                $this->db->where("cbd.status_id != 4 ", NULL, FALSE);
                $this->db->where("cbd.status_id != 5 ", NULL, FALSE);
                break;
        }
        
        $this->db->from('callback as cbd');

        return $this->db->count_all_results();
    }

    function fetch_client_reg_count($user_id){
        $this->db->where("cr.user_id = $user_id ", NULL, FALSE);
        $this->db->from('client_reg as cr');

        return $this->db->count_all_results();
    }

    function get_total_team_calls($user_id){
        $this->db->where("(u.id = $user_id OR u.reports_to=$user_id)");
        $this->db->from('callback as cb');
        $this->db->join('user as u','u.id=cb.user_id');

        return $this->db->count_all_results();
    }

    function get_lead_source_report($user_id=null){
        $this->db->select("cb.*");
        if($user_id)
            $this->db->where("(u.id = $user_id OR u.reports_to=$user_id)");
        $this->db->from('callback as cb');
        $this->db->join('user as u','u.id=cb.user_id');
        $result = $this->db->get()->result();
        $lead_sources = array();
        foreach ($result as $key => $value) {
            if(array_key_exists($value->lead_source_id, $lead_sources))
                $lead_sources[$value->lead_source_id] += 1;
            else
                $lead_sources[$value->lead_source_id] = 1;
        }
        arsort($lead_sources);
        return $lead_sources;
    }

    function get_call_reports($user_id = null){
        $this->db->select("u.*");
        if($user_id)
            $this->db->where("(u.id = $user_id OR u.reports_to=$user_id)");
        else
            $this->db->where("(u.type = 1 OR u.type=2)");
        $this->db->from('user as u');
        $result = $this->db->get()->result();
        foreach ($result as $key => $value) {
            $value->today_callback_count = $this->fetch_callback_count($value->id,'today');
            $value->yesterday_callback_count = $this->fetch_yesterday_callback_count($value->id);
            $value->total_calls = $this->fetch_callback_count($value->id);
            if($value->total_calls == 0)
                $value->productivity = 0;
            else
                $value->productivity = ($value->yesterday_callback_count/$value->total_calls) * 100;
        }
        return $result;
    }

    function fetch_total_revenue($user_id,$team=null){
        $this->db->select("SUM(r.net_revenue) as total_revenue");
        if($user_id){
            $this->db->where("cb.user_id", $user_id);
            if($team)
                $this->db->or_where("u.reports_to", $user_id);
        }
        $this->db->from('callback as cb');
        $this->db->join('close_callback_details as r','cb.id=r.callback_id','left');
        $this->db->join('user as u','u.id=cb.user_id','left');
        $row = $this->db->get()->row();
        return $row->total_revenue;
    }

    function fetch_revenue_details($month,$year){
        $this->db->select("r.net_revenue, cb.*, concat(u.first_name,' ',u.last_name) as user_name, r.customer, r.project_id, p.name as project_name");
        $this->db->from('callback as cb');
        $this->db->where("cb.status_id = 5", NULL, FALSE);
        $this->db->where("DATE(r.date_added) >= '$year-$month-01'", NULL, FALSE);
        $this->db->where("DATE(r.date_added) <= '".date("Y-m-t", strtotime($year.'-'.$month.'-1'))."'", NULL, FALSE);
        $this->db->join('close_callback_details as r','cb.id=r.callback_id','left');
        $this->db->join('user as u','u.id=cb.user_id','left');
        $this->db->join('project as p','p.id=r.project_id','left');
        $result = $this->db->get()->result();
        return $result;
    }

    function get_last_added_note($callback_id){
        $this->db->select('current_callback');
        $this->db->where("callback_id", $callback_id);
        $this->db->from("callback_data");
        $result = $this->db->get()->result();
        if(isset($result[0])){
            $temp = $result[0];
            return $temp->current_callback;
        }
        return "";
    }

    function generate_report_data($fromDate,$toDate,$dept,$city, $reportType){
        $this->db->select('c.*');
        $this->db->from('callback as c');
        $this->db->join('user as u','u.id=c.user_id','left');
        if($dept)
            $this->db->where("c.dept_id", $dept);
        if(($this->session->userdata('user_type')=="manager")){
            $this->db->where("(c.user_id in(select id from user where reports_to ='".$this->session->userdata('user_id')."') OR c.user_id = ".$this->session->userdata('user_id').")", NULL, FALSE);
        }
        if($city)
            $this->db->where("u.city_id", $city);
        if($reportType == "due"){
            $this->db->where("date(c.due_date) <= '".date('Y-m-d')."' ", NULL, FALSE);
            $this->db->where("c.active = 1 ", NULL, FALSE);
            $this->db->where("c.status_id != 4 ", NULL, FALSE);
            $this->db->where("c.status_id != 5 ", NULL, FALSE);
        }
        else{
            if(trim($fromDate)){
                $this->db->where("c.date_added >= '$fromDate' ", NULL, FALSE);
            }
            if(trim($toDate)){
                $this->db->where("c.date_added <= '$toDate' ", NULL, FALSE);
            }
        }
            
        
        $this->db->order_by('c.date_added','DESC');
        $query=$this->db->get();

        return $query?$query->result():false;
    }

    /*function generate_sitevisit_data($dept,$city,$fromDate,$toDate){
        $this->db->select('cbd.*,cb.user_id as cb_user_id, cb.status_id as cb_status_id, cb.project_id as cb_project_id');
        //$this->db->order_by('id','desc');
        $this->db->from('svd_follow_callback_details as cbd');
        $this->db->join('callback as cb','cbd.callback_id=cb.id');
        $this->db->join('user as u','u.id=cb.user_id','left');
        if($dept)
            $this->db->where("cb.dept_id", $dept);
        if($city)
            $this->db->where("u.city_id", $city);
        if($fromDate)
            $this->db->where("cb.date_added >= '$fromDate' ", NULL, FALSE);
        if($toDate)
            $this->db->where("cb.date_added <= '$toDate' ", NULL, FALSE);
        if(($this->session->userdata('user_type')=="manager")){
            $this->db->where("(cb.user_id in(select id from user where reports_to ='".$this->session->userdata('user_id')."') OR cb.user_id = ".$this->session->userdata('user_id').")", NULL, FALSE);
        }
        $this->db->order_by('cb.date_added','DESC');
        $query=$this->db->get();

        return $query?$query->result():false;
    }*/

    function generate_sitevisit_data($dept,$city,$fromDate,$toDate,$type){
        $this->db->select('cb.id, u.emp_code, u.id userId, CONCAT(u.first_name," ",u.last_name) fullname, ced.date');
        $this->db->from('callback_extra_data ced');
        $this->db->join('callback as cb','ced.callback_id=cb.id');
        $this->db->join('user as u','u.id=cb.user_id');

        if($dept)
            $this->db->where("cb.dept_id", $dept);
        if($city)
            $this->db->where("u.city_id", $city);

        if(($this->session->userdata('user_type')=="manager")){
            $this->db->where("u.id", $this->session->userdata('user_id'));
        }
        $this->db->where("ced.date BETWEEN '".$fromDate."' AND '".$toDate."' AND ced.type =".$type);
        $this->db->group_by('ced.callback_id, ced.date');
        $query = $this->db->get();
       
        return $query  ?$query->result() : false;
    }

    function sitevisit_data_details($clause){
        $this->db->select('cb.id, cb.name, cb.contact_no1 contactNo, ced.date visitDate, p.name projectName, ld.name leadSourceName');
        $this->db->from('callback_extra_data ced');
        $this->db->join('callback as cb','ced.callback_id=cb.id');
        $this->db->join('user as u','u.id=cb.user_id');
        $this->db->join('project as p','p.id=ced.project_id');
        $this->db->join('lead_source as ld','ld.id=cb.lead_source_id');
        $this->db->where($clause);
        $query = $this->db->get();       
        return $query  ?$query->result_array() : false;
    }



    function generate_facetoface_data($dept,$city,$fromDate,$toDate){
        $this->db->select('cbd.*,cb.user_id as cb_user_id, cb.status_id as cb_status_id, cb.project_id as cb_project_id');
        //$this->db->order_by('id','desc');
        $this->db->from('callback_extra_data as cbd');
        $this->db->join('callback as cb','cbd.callback_id=cb.id');
        $this->db->join('user as u','u.id=cb.user_id','left');
        $this->db->where("cbd.type", '3');
        if($dept)
            $this->db->where("cb.dept_id", $dept);
        if($city)
            $this->db->where("u.city_id", $city);
        if($fromDate)
            $this->db->where("cb.date_added >= '$fromDate' ", NULL, FALSE);
        if($toDate)
            $this->db->where("cb.date_added <= '$toDate' ", NULL, FALSE);
        $this->db->order_by('cb.date_added','DESC');
        $query=$this->db->get();
        return $query?$query->result():false;
    }

    function get_site_visit_count($callback_id){
        $this->db->where('callback_id',$callback_id);
        $this->db->where('type','1');
        $this->db->from('callback_extra_data');
        return $this->db->count_all_results();
    }

    function get_client_reg_details($id){
        $this->db->select('cb.user_id');
        $this->db->from('client_reg');
        $this->db->join('callback as cb','client_reg.callback_id=cb.id');
        $this->db->where('callback_id',$id);
        $query=$this->db->get();
        return $query->result();
    }

    function get_close_callbacks_details($ids){
        if($ids !== array()){
            $this->db->select('close_callback_details.*, callback.verified_by, callback.verified_on, callback.lead_source_id');
            $this->db->from('close_callback_details');
            $this->db->join('callback','callback.id=close_callback_details.callback_id');
            $this->db->where_in('close_callback_details.callback_id',$ids);
            $query=$this->db->get();
            return $query?$query->result():false;
        }
        else
            return array();   
    }

    function get_revenue_datas($fromDate,$toDate,$dept,$city){
        $this->db->select('ccd.*, cb.user_id, cb.leadid');
        $this->db->from('close_callback_details as ccd');
        $this->db->join('callback as cb','cb.id=ccd.callback_id');
        $this->db->join('user as u','u.id=cb.user_id','left');
        if($dept)
            $this->db->where("cb.dept_id", $dept);
        if($city)
            $this->db->where("u.city_id", $city);
        if($fromDate)
            $this->db->where("ccd.closure_date >= '$fromDate' ", NULL, FALSE);
        if($toDate)
            $this->db->where("ccd.closure_date <= '$toDate' ", NULL, FALSE);
        if(($this->session->userdata('user_type')=="manager")){
            $this->db->where("(cb.user_id in(select id from user where reports_to ='".$this->session->userdata('user_id')."') OR cb.user_id = ".$this->session->userdata('user_id').")", NULL, FALSE);
        }
        $this->db->where("cb.active", 0);

        $this->db->order_by('cb.date_added','DESC');
        $query=$this->db->get();

        return $query?$query->result():false;
    }

    function get_callback_details($id){
        $this->db->select('c.*, d.name as department, ct.name as callback_type, p.name as project, ls.name as lead_source, s.name as status, concat(u.first_name," ",u.last_name) as user_name');
        $this->db->from('callback as c');
        $this->db->join('department as d','d.id=c.dept_id','LEFT');
        $this->db->join('callback_type as ct','ct.id=c.callback_type_id','LEFT');
        $this->db->join('project as p','p.id=c.project_id','LEFT');
        $this->db->join('lead_source as ls','ls.id=c.lead_source_id','LEFT');
        $this->db->join('status as s','s.id=c.status_id','LEFT');
        $this->db->join('user as u','u.id=c.user_id', 'LEFT');
        $this->db->where('c.id',$id);
        $query=$this->db->get();
        return $query?$query->row():false;
    }

    function get_callback_data($id){
        $this->db->select('cb.*,s.name as status,u.emp_code as user,concat(u.first_name," ",u.last_name) as user_name');
        $this->db->from('callback_data as cb');
        $this->db->where('cb.callback_id',$id);
        $this->db->join('status as s','s.id=cb.status_id');
        $this->db->join('user as u','u.id=cb.user_id');
        $this->db->order_by('cb.date_added','desc');
        $query=$this->db->get();
        return $query?$query->result():false;
    }

    function getCallbackDataByUserId($id, $userId){
        $this->db->select('cb.*,s.name as status,u.emp_code as user,concat(u.first_name," ",u.last_name) as user_name');
        $this->db->from('callback_data as cb');
        $this->db->where(['cb.callback_id'=>$id, 'cb.user_id'=>$userId]);
        $this->db->join('status as s','s.id=cb.status_id');
        $this->db->join('user as u','u.id=cb.user_id');
        $this->db->order_by('cb.date_added','desc');
        $query=$this->db->get();
        return $query?$query->result():false;
    }

    function get_callbacks_data($fromDate,$toDate,$dept,$city){
        $this->db->select('cb.*');
        $this->db->from('callback_data as cb');
        $this->db->join('user as u','u.id=cb.user_id');
        $this->db->join('callback as c','c.id=cb.callback_id');
        $this->db->where("(u.type = '1' || u.type = '2') ", NULL, FALSE);
        if($dept)
            $this->db->where("c.dept_id", $dept);
        if($city)
            $this->db->where("u.city_id", $city);
        if($fromDate)
            $this->db->where("c.date_added >= '$fromDate' ", NULL, FALSE);
        if($toDate)
            $this->db->where("c.date_added <= '$toDate' ", NULL, FALSE);
        $query=$this->db->get();
        return $query?$query->result():false;
    }

    function search_callback_datas($where){
        $this->db->select('cb.*, cbd.*,p.name as project_name,concat(u.first_name,"",u.last_name) as user_name, s.name as status_name');
        $this->db->where("1".$where, NULL, FALSE);
        $this->db->join('callback as cb','cb.id=cbd.callback_id','left');
        $this->db->join('project as p','p.id=cb.project_id','left');
        $this->db->join('user as u','u.id=cbd.user_id','left');
        $this->db->join('status as s','s.id=cbd.status_id','left');
        $this->db->from('callback_data as cbd');
        $this->db->order_by('cbd.date_added','DESC');
        $query=$this->db->get();
        return $query?$query->result():array();
    }

    function get_close_callback_details($id){
        $this->db->select('*');
        $this->db->from('close_callback_details');
        $this->db->where('callback_id',$id);
        $query=$this->db->get();
        return $query?$query->row():false;
    }

    function delete_callback($id){
        $this->db->where('id',$id);
        $query=$this->db->delete('callback');
        return $query?true:false;
    }

    function mark_not_important($id){
        $this->db->where('id',$id);
        $this->db->update('callback',array("important" => 0));
    }

    function update_callback($data,$id){
        $this->db->where('id',$id);
        $query=$this->db->update('callback',$data);
        
        if(isset($data['user_id'])){
            $userdata = $this->db->select('email,type')
                ->where('id', $data['user_id'])
                ->from('user')
                ->get()->row();
            if(!empty($userdata)){
                if(($userdata->email) && (in_array($userdata->type, array('1','2')))){
                    $this->load->library('email');
                    $config = email_config();

                    $this->email->initialize($config);
                    $this->email->from("admin@leads.com", "Admin");
                    $this->email->to($userdata->email);
                    $this->email->subject("New lead");
                    $this->email->message("There is one new lead assigned to you");
                    $this->email->send();
                }
            }
        }

        $user_id = $this->session->userdata('user_id');
        $date_added = date('Y-m-d H:s:i');
        $message = "Call back values updated.<br> details:<br>";
        foreach ($data as $key => $value) {
            if($value)
                $message.="<br>$key : $value";
        }
        $query=$this->db->insert('callback_log',array(
            'callback_id' => $id,
            'user_id' => $user_id,
            'details' => $message,
            'date_added' => $date_added
        ));
        return $query?true:false;
    }

    function update_callback_details($data,$id){

        $this->db->where('callback_id',$id);
        $query=$this->db->update('close_callback_details',$data);

        $user_id = $this->session->userdata('user_id');
        $date_added = date('Y-m-d H:s:i');
        $message = "Call back Closed.<br> details:<br>";
        foreach ($data as $key => $value) {
            if($value)
                $message.="<br>$key : $value";
        }
        $query=$this->db->insert('callback_log',array(
            'callback_id' => $id,
            'user_id' => $user_id,
            'details' => $message,
            'date_added' => $date_added
        ));
        return $query?true:false;
    }

    function add_callback_data($data){
        $query=$this->db->insert('callback_data',$data);
        return $query?true:false;
    }

    function add_extra_details($data){
        $query=$this->db->insert('callback_extra_data',$data);
        return $query?true:false;
    }

    function update_extra_details($params, $clause){
        $this->db->where($clause);
        $query=$this->db->update('callback_extra_data',$params);
        return $query?true:false;
    }

    function updateCallbacksData($params, $clause){
        $this->db->where($clause);
        if ($this->db->update('callback', $params))
            return true;
        else
            return false;

    }

    function insertCallbackTracks($params){
        $this->db->insert('callbacks_track', $params);
        return $this->db->insert_id();
    }

    function generate_report_callback_data($clause) {        
        $this->db->select('ct.userName, ct.userId, u.emp_code, COUNT(ct.callbackId) as totalCalls');
        $this->db->group_by('ct.userId');
        if($clause['u.city_id'] == '')
            unset($clause['u.city_id']);
        if($clause['cb.dept_id']== '')
           unset($clause['cb.dept_id']);

        $this->db->where($clause);
        $this->db->from('callbacks_track as ct');   
        $this->db->join('user as u','u.id=ct.userId');  
        $this->db->join('callback as cb','cb.id=ct.callbackId');       

        $q = $this->db->get();
        return $q->result_array();
    }

    function getCallbackLists($clause, $start, $limit){
        $this->db->select('cb.id, cb.name, cb.contact_no1,cb.email1,ct.userName, p.name projectName, ls.name leadName');
        $this->db->where($clause);
        $this->db->from('callbacks_track as ct');  
        $this->db->join('callback as cb','cb.id=ct.callbackId'); 
        $this->db->join('project as p','p.id=cb.project_id');
        $this->db->join('lead_source as ls','ls.id=cb.lead_source_id');
        if($limit!='' ){
           $this->db->limit($limit, $start);
        }
        $q = $this->db->get();
        return $q->result_array();
    }

    function countCallbackLists($clause){
        $this->db->select('cb.id, cb.name, cb.contact_no1,cb.email1,ct.userName, p.name projectName, ls.name leadName');
        $this->db->where($clause);
        $this->db->from('callbacks_track as ct');  
        $this->db->join('callback as cb','cb.id=ct.callbackId'); 
        $this->db->join('project as p','p.id=cb.project_id');
        $this->db->join('lead_source as ls','ls.id=cb.lead_source_id');

        return $this->db->count_all_results();
    }

    function callbackSiteVisitDataByClause($clause, $column){
        $this->db->select($column);
        $q =$this->db->get_where('callback_extra_data', $clause);
        return $q->result_array();
    }

    function get_siteVisitDataByUserId($userid){
        $this->db->select('cb.id, cb.name, ced.date visitDate, ced.project_id, p.name projectName');
        $this->db->where(['cb.user_id'=>$userid, 'ced.flag'=>1, 'ced.type'=>1]);
        $this->db->from('callback as cb');  
        $this->db->join('callback_extra_data as ced','cb.id=ced.callback_id');       
        $this->db->join('project as p','p.id=ced.project_id'); 
        $q = $this->db->get();
        return $q->result_array();
    }
}