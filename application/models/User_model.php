<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class User_model extends CI_Model {

    public function __construct()
    {
        // Call the CI_Model constructor
        parent::__construct();
    }

    public function all_users($where=""){
    	$this->db->select()
            ->from('user')
            ->order_by('first_name','asc')
            ->order_by('last_name','asc');
        if($where)
            $this->db->where($where);
        $query=$this->db->get();
        return $query->result();
    }

    public function add_user($data){
        $this->db->insert('user',$data);
        if((in_array($data['type'], array(1,2))) && ($data['email'])){
            $this->load->library('email');
            $config = email_config();

            $this->email->initialize($config);
            $this->email->from("admin@leads.com", "Admin");
            $this->email->to($data['email']);
            $this->email->subject("Welcome to Fullbasket");
            $this->email->message("Welcome to Full Basket CRM System,<br><br>Your user name is ".$data['emp_code']." And password is ".$data['emp_code']." by using them please login to the tool with the following link: <a href=\"http//leads.fullbasketproperty.com\" >http//leads.fullbasketproperty.com</a> <br><br>Regards Full Basket IT team");
            $this->email->send();
        }
    }

    public function all_admins(){        
        $query=$this->db->get_where('user', ['type'=>5, 'emp_code !='=>'admin']);
        return $query->result();
    }

    public function all_vps(){
        $this->db->select('u1.*,department.name as department_name,city.name as city_name,concat(u2.first_name," ",u2.last_name) as reports_to');
        $this->db->from('user as u1');
        $this->db->join('department','department.id=u1.dept_id','LEFT');
        $this->db->join('city','city.id=u1.city_id','LEFT');
        $this->db->join('user as u2','u2.id=u1.reports_to','LEFT');
        $this->db->order_by('u1.id','desc');
        $this->db->where('u1.type','3');
        $query=$this->db->get();
        return $query->result();
    }

    public function all_managers(){
        $this->db->select('u1.*,department.name as department_name,city.name as city_name,concat(u2.first_name," ",u2.last_name) as reports_to');
        $this->db->from('user as u1');
        $this->db->join('department','department.id=u1.dept_id','LEFT');
        $this->db->join('city','city.id=u1.city_id','LEFT');
        $this->db->join('user as u2','u2.id=u1.reports_to','LEFT');
        $this->db->order_by('u1.id','desc');
        $this->db->where('u1.type','2');
        $query=$this->db->get();
        return $query->result();
    }

    public function all_employees(){
        $this->db->select('u1.*,department.name as department_name,city.name as city_name,concat(u2.first_name," ",u2.last_name) as reports_to');
        $this->db->from('user as u1');
        $this->db->join('department','department.id=u1.dept_id','LEFT');
        $this->db->join('city','city.id=u1.city_id','LEFT');
        $this->db->join('user as u2','u2.id=u1.reports_to','LEFT');
        $this->db->order_by('u1.id','desc');
        //$this->db->where('u1.type','1');
        $this->db->where("(u1.type='1' OR u1.type='5')");
        $query=$this->db->get();
        return $query->result();
    }

    public function getEmployeesByLimit($start, $limit){
        $this->db->select('u1.*,department.name as department_name,city.name as city_name,concat(u2.first_name," ",u2.last_name) as reports_to');
        $this->db->from('user as u1');
        $this->db->join('department','department.id=u1.dept_id','LEFT');
        $this->db->join('city','city.id=u1.city_id','LEFT');
        $this->db->join('user as u2','u2.id=u1.reports_to','LEFT');
        $this->db->order_by('u1.id','desc');
        $this->db->where('u1.type','1');

        if($limit!='' ){
           $this->db->limit($limit, $start);
        }
        $query=$this->db->get();
        //echo $this->db->last_query();
        return $query->result();
    }

    public function countEmployees(){
        $this->db->select('u1.*,department.name as department_name,city.name as city_name,concat(u2.first_name," ",u2.last_name) as reports_to');
        $this->db->from('user as u1');
        $this->db->join('department','department.id=u1.dept_id','LEFT');
        $this->db->join('city','city.id=u1.city_id','LEFT');
        $this->db->join('user as u2','u2.id=u1.reports_to','LEFT');
        $this->db->order_by('u1.id','desc');
        $this->db->where('u1.type','1');
        return $this->db->count_all_results();
    }


    public function reset_password($id){
        $this->db->select('emp_code');
        $this->db->from('user');
        $this->db->where('id',$id);
        $query=$this->db->get();
        $user = $query->row();
        $this->db->update(
            'user',
            array(
                'password'=>md5($user->emp_code)
            ),
            array(
                'id'=>$id
            )
        );
    }

    public function get_user_fullname($id){
        $this->db->select('first_name,last_name');
        $this->db->from('user');
        $this->db->where('id',$id);
        $query=$this->db->get();
        $user = $query->row();
        return $user->first_name." ".$user->last_name;
    }

    public function get_usersby_reports_to($id){
        $this->db->select();
        $this->db->from('user');
        $this->db->order_by('id','desc');
        if($this->session->userdata('user_type')=='City_head')
        {
        $ids=array();
        foreach ($this->session->userdata('user_ids') as $id) {
        // echo $id->id;
        $ids[]=$id->id;
        }
        $list_id=implode(',', $ids);
        $this->db->where('id in('.$list_id.')');
        $this->db->where('active',1);
        }
        else
        $this->db->where('reports_to',$id);
        $query=$this->db->get();
        return $query->result();
    }
    public function get_city_users_active()
    {
         $this->db->select('id,first_name,last_name');
        $this->db->from('user');
        $this->db->order_by('id','desc');
        $ids=array();
        foreach ($this->session->userdata('user_ids') as $id) {
        // echo $id->id;
        $ids[]=$id->id;
        }
        $list_id=implode(',', $ids);
        $this->db->where('id in('.$list_id.')');
        $this->db->where('active',1);
         $query=$this->db->get();
        return $query->result();
        
    }
    public function get_manager_name($user_id){
        $this->db->select('concat(m.first_name," ",m.last_name) as manager_name');
        $this->db->from('user as u');
        $this->db->join('user as m','u.reports_to = m.id');
        $this->db->where('u.id',$user_id);
        $row = $this->db->get()->row();
        return $row->manager_name;
    }

    public function get_team_members_count($user_id){
        if($this->session->userdata('user_type')=='City_head')
        {
        foreach ($this->session->userdata('user_ids') as $id) {
            // echo $id->id;
            $ids[]=$id->id;
            }
            $list_id=implode(',', $ids);
            $this->db->where("u.id in(".$list_id.")");
        }
        else
        {
        $this->db->where("u.reports_to = $user_id ", NULL, FALSE);
        }
        $this->db->from('user as u');

        return $this->db->count_all_results();
    }

    public function get_team_members($user_id=false){
        $team = $user_id.",";
        $this->db->select('u.id');
        $this->db->where("u.reports_to = $user_id ", NULL, FALSE);
        $this->db->from('user as u');
        $result = $this->db->get()->result();
        foreach ($result as $key => $value)
            $team .= $value->id.",";
        return $team;
    }

    /*function get_vp_director_admin_emails(){
        $users = $this->db->select('email')
            ->from("user")
            ->where("type in ('3','4','5')")
            ->get()->result();
        
        $exceptions = array("vickyvani@fullbasketproperty.com","manjitvani@fullbasketproperty.com","sgupta@fullbasketproperty.com");
        $emails = array();
        foreach ($users as $key => $value) {
          if($value->email)
            if(!in_array($value->email,$exceptions))
            $emails[] = $value->email;
        }
        return $emails;
    }*/
    function getUsersEmails(){
        $users = $this->db->select('email')
            ->from("user")
            ->where("type in ('3','4','5')")
            ->get()->result();
        
        $exceptions = array("vickyvani@fullbasketproperty.com","manjitvani@fullbasketproperty.com","sgupta@fullbasketproperty.com", "nitish.kedia@fullbasketproperty.com");
        $emails = array();
        foreach ($users as $key => $value) {
          if($value->email)
            if(!in_array($value->email,$exceptions))
            $emails[] = $value->email;
        }
        return $emails;
    }
    function get_vp_director_admin_emails(){
        $users = $this->db->select('email')
            ->from("user")
            ->where("type in ('3','4','5')")
            ->get()->result();   
       
        $emails = array();
        foreach ($users as $key => $value) {
          if($value->email)
            $emails[] = $value->email;
        }
        return $emails;
    }

    function getUserEmailByClause($clause){
        $clause .= ' AND active=1';
        $this->db->select('email');
        $this->db->where($clause);
        $this->db->from('user');
        $query=$this->db->get();
        $user = $query->result_array();
        return $user;
    }

    public function get_user_data($id){
        $this->db->select('*');
        $this->db->from('user');
        $this->db->where('id',$id);
        $query=$this->db->get();
        $user = $query->row_array();
        return $user;
    }

    public function update_user($data,$id){
        $this->db->where('id',$id);
        $query=$this->db->update('user',$data);
        return ($this->db->affected_rows() == '1')?true:false;
        // echo $this->db->last_query();exit;
    }

    public function get_live_feed_back(){
         $list_id='';
         $ids=array();
        if($this->session->userdata('user_type')=='City_head')
        {
            $ids=array();
          foreach ($this->session->userdata('user_ids') as $id) {
             // echo $id->id;
              $ids[]=$id->id;
          }
        $list_id=implode(',', $ids);
           // $this->db->where("(cb.user_id in($list_id))");
            $this->db->select('first_name, last_name, type, last_login')
            ->from('user')
            ->where_in('type', array('1','2'))
            ->where_in('id', $ids)
            ->where('active', 1)
            ->order_by('last_login','DESC');
        return $this->db->get()->result();
        }
        else
        {
        $this->db->select('first_name, last_name, type, last_login')
            ->from('user')
            ->where_in('type', array('1','2'))
           
            ->where('active', 1)
            ->order_by('last_login','DESC');
        return $this->db->get()->result();
        }
    }
     public function all_city_heads()
    {
        $this->db->select('u1.*,department.name as department_name,city.name as city_name,concat(u2.first_name," ",u2.last_name) as reports_to');
        $this->db->from('user as u1');
        $this->db->join('department','department.id=u1.dept_id','LEFT');
        $this->db->join('city','city.id=u1.city_id','LEFT');
        $this->db->join('user as u2','u2.id=u1.reports_to','LEFT');
        $this->db->order_by('u1.id','desc');
        $this->db->where('u1.type','6');
        $query=$this->db->get();
        return $query->result();
    }
        public function get_city_id($id)
    {
            $this->db->select('city_id');
            $this->db->from('user');
            $this->db->where('id',$id);
            $result= $this->db->get()->result();
          
        return $result;
    }
    public function get_city_user_ids($id)
    {
            $this->db->select('id');
            $this->db->from('user');
            $this->db->where('city_id',$id);
            $result= $this->db->get()->result();
          
             return $result;

    }
}