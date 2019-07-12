<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Login extends CI_Controller {

	function __construct(){
        /* Session Checking Start*/
        parent::__construct();
        $this->load->model(array('login_model','callback_model'));
        $this->load->library('session');    
    }

	public function index() {
        if ($this->session->userdata('is_loggedin')) 
            redirect(base_url());
        $this->data = array(
            "error" => false,
            "message" => ""
        );
        if($this->input->post()){
            $username=$this->input->post('userName');
            $password=$this->input->post('password');
            $user_type=$this->input->post('user_type');
            $data = $this->login_model->user_login($username,$password,$user_type);
         
            if($data){
                if($data->active){
                    if($data->is_new && ($data->type == 1)){
                        $temp = uniqid().uniqid();
                        $this->session->set_userdata('user_key', $temp);
                        redirect(base_url('login/create_password?key='.$temp.'&id='.$data->id));
                    }
                    $user_type="";
                    switch ($data->type) {
                        case 1:
                            $user_type = 'user';
                            break;

                        case 2:
                            $user_type = 'manager';
                            break;

                        case 3:
                            $user_type = 'vp';
                            break;

                        case 4:
                            $user_type = 'director';
                            break;
                        case 6:
                            $user_type='City_head';
                            break;
                        
                    }

                    $newdata = array(
                        'user_id' => $data->id,
                        'username'  => $username,
                        'user_email' => $data->email,
                        'user_name' => $data->first_name." ".$data->last_name,
                        'is_loggedin' => TRUE,
                        'last_login' => $data->last_login,
                        'reports_to' => $data->reports_to,
                        'user_type' => $user_type,
                        //'permissions' => $permission
                    );
                   
                    $this->session->set_userdata($newdata);
                    //-- permission ----
                    $this->getPermission($this->session->userdata('user_id'));

                    /*if($data->type == 1){
                        if($this->callback_model->check_yesterdays_dar($data->id) == 0){
                            $this->session->set_userdata('dar_flag', 1);
                            redirect(base_url('generate_dar'));
                        }
                    }*/
                    redirect(base_url());
                }
                else{
                    $this->data['error'] = true;
                    $this->data['message'] = "Your account is inactive, Contact Admin";
                }
            }
            else{
                $this->data['error'] = true;
                $this->data['message'] = "Oops! Invalid Username/Password";
            }
        }
        // echo "<pre>";print_r($this->data);exit;
        $this->load->view('login', $this->data);
    }

    function admin() {
        if ($this->session->userdata('is_loggedin')) {
            if($this->session->userdata('user_type') == "admin")
                redirect(base_url()."admin");
            else
                redirect(base_url());
        }
        $this->data = array(
            "error" => false,
            "message" => ""
        );
        if($this->input->post()){
            $username=$this->input->post('username');
            $password=$this->input->post('password');
            $data = $this->login_model->admin_login($username,$password);
            if($data){
                if($data->email == 'admin@fullbasketproperty.com' && $data->type == 5) {
                    $mData = $this->login_model->getModulesClause();
                    $tmpArry = array();
                    foreach ($mData as $value) {
                        $tmpArry[] = $value['id'];
                    }
                    $this->session->set_userdata('permissions', json_encode($tmpArry));
                }
                else 
                    $this->getPermission($data->id);

                $newdata = array(
                    'user_id' => $data->id,
                    'username'  => $username,
                    'user_email' => $data->email,
                    'user_name' => $data->first_name." ".$data->last_name,
                    'is_loggedin' => TRUE,
                    'last_login' => $data->last_login,
                    'user_type' => 'admin'
                );
                $this->session->set_userdata($newdata);
                redirect(base_url().'admin');
            }
            else{
                $this->data['error'] = true;
                $this->data['message'] = "Oops! Invalid Username/Password";
            }
        }
        $this->load->view('admin/login', $this->data);
    }

    function logout() {
        $this->session->sess_destroy();
        $this->session->unset_userdata('is_loggedin');
        if($this->session->userdata('user_type') == "admin")
            redirect(base_url().'login/admin');
        else
            redirect(base_url().'login/index');
    }

    public function create_password() {
        $key = $this->input->get('key');
        if($key == $this->session->userdata('user_key')){
            if($this->input->post()){
                $password = $this->input->post('password');
                $user_id = $this->input->get('id');
                $this->db->update(
                    'user',
                    array(
                        "password" => md5($password),
                        "is_new" => 0
                    ),
                    array(
                        "id" => $user_id
                    )
                );
                $this->session->unset_userdata('user_key');
                redirect(base_url('login'));
            }
            else{
                $this->load->view('create_password');
            }
        }
        else
            echo "Error";
    }

    function forget_pass() {
        $email = $this->input->post('email');
        if($this->login_model->forget_password($email))
            echo "success";
        else
            echo "error";
    }

    function getPermission($userId){
        $fetchData = $this->login_model->getModulePermission(['userId' => $userId]);
        $permission = $fetchData['accessLists'];
        $this->session->set_userdata('permissions', $permission);
    }

}
