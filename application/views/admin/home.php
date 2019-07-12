<?php 
	defined('BASEPATH') OR exit('No direct script access allowed');
	$this->load->view('inc/admin_header'); 

if(!$this->session->userdata('permissions') && $this->session->userdata('permissions')=='' && $this->session->userdata('username') !='admin') {
    ?>
    <style type="text/css">
    .alrtMsg{padding-top: 50px;}
    .alrtMsg i {
        font-size: 60px;
        color: #f1c836;
    }
    </style>
    <div class="container"> 
        <div class="row"> 
            <div class="text-center alrtMsg">
                <i class="fa fa-exclamation-triangle"></i>
                <h3>You Do Not have permission as of now. Please contact your Administration and Request for Permission.</h3>
            </div>
        </div>
    </div>
    <?php
} 
