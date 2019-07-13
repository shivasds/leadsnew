<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Common_model extends MY_Model {

    public function __construct()
    {
        // Call the CI_Model constructor
        parent::__construct();
    }

    public function all_active_managers(){
    	$this->db->select();
        $this->db->from('user');
        $this->db->order_by('id','desc');
        $this->db->where('active',1);
        $this->db->where('type','2');
        $query=$this->db->get();
        return $query->result();
    }

    public function all_active_departments(){
        $this->db->select()
            ->from('department')
            ->where('active',1)
            ->order_by('name','asc');
        $query=$this->db->get();
        return $query->result();
    }

    public function all_active_advisors(){
        $this->db->select('u.*')
            ->distinct()
            ->from('callback as cb')
            ->join('user as u','u.id=cb.user_id')
            ->order_by('u.id');
        return $this->db->get()->result();
    }

    public function get_id($table,$name){
        $this->db->select('id');
        $this->db->from($table);
        $this->db->where("name like '$name'",NULL,false);
        $query=$this->db->get();
        $result=$query->result();
        if (array_key_exists(0, $result))
            $result = $result[0];
        return ($result!==array())?$result->id:false;
    }

    public function all_active_callback_types(){
        $this->db->select();
        $this->db->from('callback_type');
        $this->db->order_by('id','desc');
        $this->db->where('active',1);
        $query=$this->db->get();
        return $query->result();
    }

    public function all_active_projects(){
        $this->db->select()
            ->from('project')
            ->where('active',1)
            ->order_by('name','asc');
        $query=$this->db->get();
        return $query->result();
    }

    public function all_active_lead_sources(){
        $this->db->select()
            ->from('lead_source')
            ->where('active',1)
            ->order_by('name','asc');
        $query=$this->db->get();
        return $query->result();
    }

    public function all_active_brokers(){
        $this->db->select()
            ->from('broker')
            ->where('active',1)
            ->order_by('name','asc');
        $query=$this->db->get();
        return $query->result();
    }

    public function all_active_statuses(){
        $this->db->select()
            ->from('status')
            ->where('active',1)
            ->order_by('name','asc');
        $query=$this->db->get();
        return $query->result();
    }

    public function all_active_cities(){
        $this->db->select()
            ->from('city')
            ->where('active',1)
            ->order_by('name','asc');
        $query=$this->db->get();
        return $query->result();
    }

    public function all_active_states(){
        $this->db->select();
        $this->db->from('state');
        $this->db->order_by('id','desc');
        $this->db->where('active',1);
        $query=$this->db->get();
        return $query->result();
    }

    public function all_active_builders(){
        $this->db->select();
        $this->db->from('builder');
        $this->db->order_by('id','desc');
        $this->db->where('active',1);
        $query=$this->db->get();
        return $query->result();
    }

    public function all_cities(){
        $this->db->select('city.*,state.name as state_name');
        $this->db->join('state','state.id = city.state_id');
        $this->db->from('city');
        $this->db->order_by('id','desc');
        $query=$this->db->get();
        return $query->result();
    }

    public function all_states(){
        $this->db->select();
        $this->db->from('state');
        $this->db->order_by('id','desc');
        $query=$this->db->get();
        return $query->result();
    }

    public function all_depts(){
        $this->db->select();
        $this->db->from('department');
        $this->db->order_by('id','desc');
        $query=$this->db->get();
        return $query->result();
    }

    public function all_lead_sources(){
        $this->db->select();
        $this->db->from('lead_source');
        $this->db->order_by('id','desc');
        $query=$this->db->get();
        return $query->result();
    }

    public function all_projects(){
        $this->db->select('project.*,builder.name as builder_name');
        $this->db->join('builder','builder.id = project.builder_id','left');
        $this->db->from('project');
        $this->db->order_by('id','desc');
        $query=$this->db->get();
        return $query->result();
    }

    public function all_builders(){
        $this->db->select();
        $this->db->from('builder');
        $this->db->order_by('id','desc');
        $query=$this->db->get();
        return $query->result();
    }

    public function all_brokers(){
        $this->db->select();
        $this->db->from('broker');
        $this->db->order_by('id','desc');
        $query=$this->db->get();
        return $query->result();
    }

    public function all_callback_types(){
        $this->db->select();
        $this->db->from('callback_type');
        $this->db->order_by('id','desc');
        $query=$this->db->get();
        return $query->result();
    }

    public function all_statuses(){
        $this->db->select();
        $this->db->from('status');
        $this->db->order_by('id','desc');
        $query=$this->db->get();
        return $query->result();
    }

    public function get_project_name($id){
        $this->db->select('name');
        $this->db->from('project');
        $this->db->where('id',$id);
        $query=$this->db->get();
        $project = $query->row();
        return isset($project->name)?$project->name:"";
    }

    public function get_leadsource_name($id){
        $this->db->select('name');
        $this->db->from('lead_source');
        $this->db->where('id',$id);
        $query=$this->db->get();
        $lead_source = $query->row();
        return isset($lead_source->name)?$lead_source->name:"";
    }

    public function get_department_name($id){
        $this->db->select('name');
        $this->db->from('department');
        $this->db->where('id',$id);
        $query=$this->db->get();
        $department = $query->row();
        return isset($department->name)?$department->name:"";
    }

    public function get_city_name($id){
        $this->db->select('name');
        $this->db->from('city');
        $this->db->where('id',$id);
        $query=$this->db->get();
        $city = $query->row();
        return isset($city->name)?$city->name:"";
    }

    public function get_status_name($id){
        $this->db->select('name');
        $this->db->from('status');
        $this->db->where('id',$id);
        $query=$this->db->get();
        $status = $query->row();
        return isset($status->name)?$status->name:"";
    }

    public function duplicate_check($table,$name){
        $this->db->select();
        $this->db->from($table);
        if($table == "user")
            $this->db->where('emp_code',$name);
        else
            $this->db->where('name',$name);
        $query=$this->db->get();
        $rowcount=$query->num_rows();
        return $rowcount;
    }

    public function toggle_status($table,$id){
        $this->db->select('active');
        $this->db->from($table);
        $this->db->where('id',$id);
        $result=$this->db->get()->result();
        if(count($result) > 0){
            $active = $result[0]->active;
            $newStatus = $active?0:1;
            $query=$this->db->update(
                $table,
                array(
                    'active'=>$newStatus
                ),
                array(
                    'id'=>$id
                )
            );
            return $newStatus;
        }
        return false;
    }


    function checkExistsDeadReason($clause) {
        $this->db->select('id');
        $sql = $this->db->get_where('dead_reason', $clause);
        return $sql->num_rows();
    }
    
    function insertDeadReason($params) {
        $this->db->insert('dead_reason', $params);
        return $this->db->insert_id();
    }
    
    function updateDeadReason($params, $id) {
        $this->db->where('id', $id);
        if ($this->db->update('dead_reason', $params))
            return true;
        else
            return false;
    }

    function getDeadReasons($where=null){
        $this->db->from('dead_reason');
        if($where)
            $this->db->where($where);
        $query=$this->db->get();
        return $query->result_array();
    }
    function deleteDeadReasons($id){
        $this->db->delete('dead_reason', array('id' => $id));
        return true;
    }

    function getNavbarByClause($clause){
        $q = $this->db->get_where('tbl_modules', $clause);
        return $q->result_array();
    }

    function postAccessQuery($params){
        $this->db->insert('tbl_privilege', $params);
        return $this->db->insert_id();
    }

    function updateAccessQuery($clause, $params){
        $this->db->where($clause);
        if($this->db->update('tbl_privilege', $params))
            return true;
        else
            return false;
    }

    function checkModulePermission($clause) {
        $q = $this->db->get_where('tbl_privilege', $clause);
        return $q->row_array();
    }

    function deleteAccess($clause){
        $this->db->delete('tbl_privilege', $clause);
        return true;
    }
     function get_online_leads($lead_source)
     {
        $d=0;
        $this->db->select()
            ->from('online_leads')
            ->where('source',$lead_source)
            ->where('saved',$d);

           // ->order_by('name','asc');
        $query=$this->db->get();
        return $query->result();
     }

        function save_online_leads($leads)
            {
                
                //echo $leads->leads->lead[0]->name;
                $count= $leads->count; 
                $source="Magicbricks";
                //print_r($leads);die;
                 if(!empty($leads)){
                            for ($x = $count-1; $x >= 0; $x--) {
                                $source;
                                $name=$leads->leads->lead[$x]->name;
                                $mobile=$leads->leads->lead[$x]->mobile; 
                                $email= $leads->leads->lead[$x]->email; 
                                $projectname=$leads->leads->lead[$x]->project;
                                $id=$leads->leads->lead[$x]->id; 
                                $notes=$leads->leads->lead[$x]->msg;
                                $date=$leads->leads->lead[$x]->dt;
                                $project_id=$leads->leads->lead[$x]->loginid;
                                   $lead_date=date("Y-m-d", strtotime($date));
                            
                            $query1="select count(*) as count from online_leads where phone='$mobile'";
                            $usercount=  $this->db->query($query1);
                            if ( $usercount->num_rows() > 0 )
                             {
                              $row = $usercount->row_array();
                            //  print_r($row);
                              $userscount= $row['count'];
                              {
                                $bid=214;
                                  if($userscount<=0)
                                  {
                                    if(!empty($projectname))
                                    $this->insert_newproject($projectname, $bid);
                                else
                                    $this->insert_newproject('Magicbricks', $bid);
                                    $query="insert into online_leads(source,name,phone,email,project,leadid,notes,lead_date,project_id) values('$source','$name','$mobile','$email','$projectname','$id','$notes','$lead_date','$project_id')";
                                $this->db->query($query);  

                                  }
                              }
                             }
                                }
                        }
                        else
                        {
                           
                        }
            }

            function save_online_leads_99acres($temp)
            {
                if(!empty($temp))
                {
                    $source="99acres";

                    $i=0;
                    foreach ($temp as $leads) {

                        $phone =$leads['phone'];
                        $name=$leads['name'];
                        $mobile=$leads['phone'];
                        $email=$leads['email'];
                        $projectname=$leads['project'];
                        $id=$leads['leadid'];
                        $lead_date=$leads['lead_date'];
                        $notes=$leads['notes'];
                        $lead_date=$leads['lead_date'];
                        $project_id=$leads['leadid'];

                        

                         $query1="select count(*) as count from online_leads where phone='$phone '";
                            $usercount=  $this->db->query($query1);
                            if ( $usercount->num_rows() > 0 )
                             {
                              $row = $usercount->row_array();
                            //  print_r($row);
                              $userscount= $row['count'];
                              {
                                $bid=213;
                                  if($userscount<=0 && $name!='')
                                  {
                                    if(!empty($projectname))
                                    $this->insert_newproject($projectname,$bid);
                                else
                                    $this->insert_newproject('99acres',$bid);
                              $query="insert into online_leads(source,name,phone,email,project,leadid,notes,lead_date,project_id) values('$source','$name','$mobile','$email','$projectname','$id','$notes','$lead_date','$project_id')";
                                $this->db->query($query);

                                  }
                              }
                             }
                             $i++;
                       
                    }

            }
        }

         function updateWhere($where,$data=1,$table_name='online_leads')
            {
              foreach ($where as $leadid) {
                $this->db->set('saved',1);
                $this->db->where('id', $where['id']);
                $this->db->update('online_leads');
                 
            }
        }
       

        function getsourceId($sourcename)
        {
            $this->db->select('id');
            $this->db->from('lead_source');
            $this->db->where('name',$sourcename);
            $query=$this->db->get();
            return $query->row_array();
        }

        function insert_newproject($p_name,$bid)
        {
            //exit;
            $stmt="select count(*) as count from project where name='$p_name' and builder_id=$bid";    
            $p_count=$this->db->query($stmt);
            if ( $p_count->num_rows() > 0 )
            {
            $row = $p_count->row_array();
            $count=$row['count'];
            //echo $count;die;
            if($count<=0)
            {
            $date=date('Y-m-d h:m:s');
            $stmt1="insert into project (name,builder_id,date_added)values('$p_name',$bid, '$date');"; 
            //echo $stmt1;die; 
            $this->db->query($stmt1);
            }

            }
                         
        }

        function get_project_id_by_name($p_name,$bid)
        {
            $this->db->select('id')
                    ->from('project')
                    ->where('name',$p_name)
                    ->where('builder_id',$bid);
           // $stmt2="select id from project where name = '$p_name'";
            //echo $stmt2;die;
           // $this->db->query();
            $query=$this->db->get();
            return $query->row_array();

        }
        function lead_count($sourcename,$date)
        {
                $this->db->select('count(*) as count')
                         ->from('online_leads')
                         ->where('lead_date',$date)
                         ->where('source',$sourcename);
                         $query=$this->db->get();
                         return $query->row_array();
        }
        function total_lead_count($sourcename)
        {
                $this->db->select('count(*) as count')
                         ->from('online_leads')
                         ->where('source',$sourcename);
                         $query=$this->db->get();
                         return $query->row_array();

        }

       }
