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
}