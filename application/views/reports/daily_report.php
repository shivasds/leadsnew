<?php 
    defined('BASEPATH') OR exit('No direct script access allowed');
    $this->load->view('mail/header', $data);
    $this->load->view('mail/lead_report', $data['lead_report']);
    $this->load->view('mail/site_visit_report', $data['site_visit_report']);
    $this->load->view('mail/client_reg_report', $data['clent_reg_report']);
    $this->load->view('mail/revenue_report', $data['revenue_report']);
    $this->load->view('mail/daily_act_report', $data['daily_act_report']);
    $this->load->view('mail/site_visit_fixed_report', $data['site_visit_fixed_report']);
    $this->load->view('mail/face_to_face_report', $data['face_to_face_report']);
    $this->load->view('mail/footer', $data);
?>