<?php defined('BASEPATH') OR exit('No direct script access allowed'); ?>
<!DOCTYPE html>
<html lang="en">
    <head>
        <meta http-equiv="content-type" content="text/html; charset=UTF-8">
        <meta charset="utf-8">
        <title>Fullbasket Crm</title>
        <meta name="generator" content="Bootply" />
        <meta name="viewport" content="width=device-width, initial-scale=1, maximum-scale=1">
        <link href='https://fonts.googleapis.com/css?family=Lato:400,400italic,300,700,900,900italic,700italic,300italic,100italic,100' rel='stylesheet' type='text/css'>
        <link href="https://cdnjs.cloudflare.com/ajax/libs/twitter-bootstrap/3.3.4/css/bootstrap.min.css" rel="stylesheet">
        <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-select/1.8.1/css/bootstrap-select.min.css">
        <!--[if lt IE 9]>
                <script src="//html5shim.googlecode.com/svn/trunk/html5.js"></script>
        <![endif]-->
        <link href="<?php echo base_url();?>assets/admin/css/styles.css" rel="stylesheet">
        <link rel="stylesheet" href="<?php echo base_url();?>assets/admin/css/build.css"/>
        <link rel="stylesheet" href="<?php echo base_url();?>assets/admin/css/extra.css"/>
        <link rel="stylesheet" type="text/css" href="https://cdnjs.cloudflare.com/ajax/libs/jqueryui/1.12.1/jquery-ui.min.css">       
        <link rel="stylesheet" href="https://rawgit.com/KidSysco/jquery-ui-month-picker/v3.0.0/demo/MonthPicker.min.css"/>
        <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/css/bootstrap.min.css">
        <link rel="https://cdn.datatables.net/1.10.12/css/dataTables.bootstrap.min.css">
        <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.min.css">
        <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.1.1/jquery.min.js"></script>
        <script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/js/bootstrap.min.js"></script>
        
        <!-- Include Required Prerequisites -->
       <!--  <script type="text/javascript" src="//cdn.jsdelivr.net/jquery/1/jquery.min.js"></script> -->
        <script type="text/javascript" src="//cdn.jsdelivr.net/momentjs/latest/moment.min.js"></script>
        <link rel="stylesheet" type="text/css" href="//cdn.jsdelivr.net/bootstrap/3/css/bootstrap.css" />
 
        <!-- Include Date Range Picker -->
        <script type="text/javascript" src="//cdn.jsdelivr.net/bootstrap.daterangepicker/2/daterangepicker.js"></script>
        <link rel="stylesheet" type="text/css" href="//cdn.jsdelivr.net/bootstrap.daterangepicker/2/daterangepicker.css" />

        <link rel="stylesheet" type="text/css" href="//cdn.datatables.net/1.10.13/css/jquery.dataTables.css">
  
        <script type="text/javascript" charset="utf8" src="//cdn.datatables.net/1.10.13/js/jquery.dataTables.min.js"></script>
        <script src="https://cdnjs.cloudflare.com/ajax/libs/modernizr/2.8.3/modernizr.js"></script>
        <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery-timepicker/1.10.0/jquery.timepicker.min.js"></script>
        <link rel="stylesheet" type="text/css" href="https://cdnjs.cloudflare.com/ajax/libs/jquery-timepicker/1.10.0/jquery.timepicker.min.css">
        <script src="https://cdnjs.cloudflare.com/ajax/libs/jqueryui/1.12.1/jquery-ui.min.js"></script>
        <script src="https://rawgit.com/KidSysco/jquery-ui-month-picker/v3.0.0/demo/MonthPicker.min.js"></script>
        <script type="text/javascript">
            $(function(){
                $('.datepicker').each(function(){
                    $(this).datepicker({
                        dateFormat: 'yy-mm-dd'
                     });
                });
                $('#ui-datepicker-div').draggable();
                $('.timePicker').each(function(){
                    $(this).timepicker({ 'timeFormat': 'H:i' });
                });
            });
        </script>
    </head>
    <body>
        <div class="se-pre-con"></div>
        <div class="top-header">
            <div class="container">
                <nav class="navbar navbar-default navbar-fixed-top">
                    <div class="container">
                        <div class="navbar-header">
                            <button type="button" class="navbar-toggle collapsed" data-toggle="collapse" data-target="#navbar" aria-expanded="false" aria-controls="navbar">
                                <span class="sr-only">Toggle navigation</span>
                                <span class="icon-bar"></span>
                                <span class="icon-bar"></span>
                                <span class="icon-bar"></span>
                            </button>
                        </div>
                        <div id="navbar" class="navbar-collapse collapse">
                            <ul class="nav navbar-nav">
                                <li><a href="<?php echo base_url()?>dashboard">Dashboard</a></li>
                             </ul>
                            <ul class="nav navbar-nav navbar-right">
                                <li>
                                    <span>
                                        Hello <?php echo $this->session->userdata('user_name'); ?>, 
                                        Last login time: <?php echo $this->session->userdata('last_login'); ?>
                                    </span>
                                    <div class="btn-group ex"> 
                                        <a class="btn btn-default dropdown-toggle btn-select" data-toggle="dropdown" href="#"> 
                                            <span class="flg"><img title="flag" alt="flag" src="<?php echo base_url();?>assets/admin/images/icon.png"></span>
                                            My Account
                                            <i class="fa fa-angle-down"></i>
                                        </a>
                                        <ul style="display:none" class="dropdown-menu">
                                            <li><a href="<?php echo base_url()?>dashboard/change_password">Change password</a></li>
                                            <li><a href="<?php echo base_url()?>login/logout">Logout</a></li>                                   
                                        </ul>
                                    </div>
                                
                                </li>
                            </ul>
                        </div><!--/.nav-collapse -->
                    </div>
                </nav>
            </div>
        </div>
        <?php if(!isset($dar_flag) || ($dar_flag == 0)) {?>
        <div>
            <h3><img src="<?php echo base_url()?>assets/img/logo.png" width=60px/>  </h3> 
            <ul class="nav nav-tabs">
                <li ><a href="<?php echo base_url()?>">Home</a></li>
                <li class="<?php if($name=='search'){echo 'active';}?>"><a href="<?php echo base_url()?>search_callback">Search</a></li>
                <?php if (($this->session->userdata('user_type')=="director") || ($this->session->userdata('user_type')=="vp"))  { ?>
                    <li class="<?php if($name=='generate'){echo 'active';}?>"><a href="<?php echo base_url()?>generate_callback">Generate</a></li>
                <?php } ?>
                <?php if ($this->session->userdata('user_type')!="user") { ?>
                    <li class="<?php if($name=='reports'){echo 'active';}?>"><a href="<?php echo base_url()?>reports">Reports</a></li>
                <?php } ?>
                <li class="<?php if($name=='callbacks'){echo 'active';}?>"><a href="<?php echo base_url()?>callbacks">Call Backs</a></li>
                <?php /*if (($this->session->userdata('user_type')=="user")) { ?>
                    <li class="<?php if($name=='dar'){echo 'active';}?>"><a href="<?php echo base_url()?>generate_dar">Generate DAR</a></li>
                <?php } */ ?>
                <?php if (($this->session->userdata('user_type')=="user") || ($this->session->userdata('user_type')=="manager")) { ?>
                    <li class="<?php if($name=='report_bugs'){echo 'active';}?>"><a href="<?php echo base_url()?>report_bugs">Report Bugs</a></li>
                <?php } ?>
                
                <button class="btn btn-success"><a href="javascript:history.go(-1)">GO BACK</a></button>
            </ul>
        </div>
        <?php } ?>