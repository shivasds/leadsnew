<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Login_model extends CI_Model {

    public function __construct()
    {
        // Call the CI_Model constructor
        parent::__construct();
    }

    public function admin_login($username,$password){
    	$password = md5($password);
    	$query = $this->db->select()
    		->where('loginid',$username)
    		->where('password',$password)
    		->where('type','5')
    		->from('user')
    		->get();
    	$result = $query->result();
    	if(count($result) > 0){
            $this->db->update(
                'user',
                array(
                    "last_login" => date('Y-m-d H:i:s')
                ),
                array(
                    "id" => $result[0]->id
                )
            );
    		return $result[0];
        }
    	else
    		return false;
    }

    public function user_login($username,$password,$type){
        $password = md5($password);
        $query = $this->db->select()
            ->where('loginid',$username)
            ->where('password',$password)
            ->where('type',$type)
            ->from('user')
            ->get();
        $result = $query->result();
        if(count($result) > 0){
            $this->db->update(
                'user',
                array(
                    "last_login" => date('Y-m-d H:i:s')
                ),
                array(
                    "id" => $result[0]->id
                )
            );
            return $result[0];
        }
        else
            return false;
    }

    public function forget_password($email){
        $query = $this->db->select()
            ->where('email',trim($email))
            ->from('user')
            ->get();
        $result = $query->result();
        if(count($result) > 0){
            $this->db->update(
                'user',
                array(
                    "password" => md5($result[0]->emp_code)
                ),
                array(
                    "id" => $result[0]->id
                )
            );
            $this->load->library('email');
            
            $this->email->initialize(email_config());

            $this->email->from("admin@leads.fullbasketproperty.com", "Admin");
            $this->email->to($email);

            $this->email->subject("Password Reset");
            $this->email->message("Your password has been reseted to your emp_code");

            $this->email->send();
            return true;
        }
        else
            return false;
    }
}