<?php defined('BASEPATH') OR exit('No direct script access allowed'); ?>
<!DOCTYPE html>
<html lang="en">
	<head>
		<title>Admin</title>
		<meta charset="utf-8">
		<meta name="viewport" content="width=device-width, initial-scale=1">
		<meta name="generator" content="Bootply" />
		<meta name="viewport" content="width=device-width, initial-scale=1, maximum-scale=1">
		<link href='https://fonts.googleapis.com/css?family=Lato:400,400italic,300,700,900,900italic,700italic,300italic,100italic,100' rel='stylesheet' type='text/css'>
		<link href="https://cdnjs.cloudflare.com/ajax/libs/twitter-bootstrap/3.3.4/css/bootstrap.min.css" rel="stylesheet">
		<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-select/1.8.1/css/bootstrap-select.min.css">
		<!--[if lt IE 9]>
				<script src="//html5shim.googlecode.com/svn/trunk/html5.js"></script>
		<![endif]-->
		<link href="<?= base_url();?>assets/admin/css/styles.css" rel="stylesheet">

		<link rel="stylesheet" type="text/css" href="https://cdnjs.cloudflare.com/ajax/libs/jqueryui/1.12.1/jquery-ui.min.css">
		<link rel="stylesheet" href="https://rawgit.com/KidSysco/jquery-ui-month-picker/v3.0.0/demo/MonthPicker.min.css"/>
		<link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/css/bootstrap.min.css">
		<link rel="https://cdn.datatables.net/1.10.15/css/dataTables.bootstrap.min.css">
		<link rel="stylesheet" type="text/css" href="https://cdn.datatables.net/buttons/1.3.1/css/buttons.dataTables.min.css">
		<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.6.3/css/font-awesome.min.css">
		<link rel="stylesheet" href="<?= base_url();?>assets/admin/css/build.css"/>

		<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.1.1/jquery.min.js"></script>
		<script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/js/bootstrap.min.js"></script>

		<!-- Include Required Prerequisites -->
		<!-- <script type="text/javascript" src="//cdn.jsdelivr.net/jquery/1/jquery.min.js"></script> -->
		<script type="text/javascript" src="//cdn.jsdelivr.net/momentjs/latest/moment.min.js"></script>
		<link rel="stylesheet" type="text/css" href="//cdn.jsdelivr.net/bootstrap/3/css/bootstrap.css" />

		<!-- Include Date Range Picker -->
		<script type="text/javascript" src="//cdn.jsdelivr.net/bootstrap.daterangepicker/2/daterangepicker.js"></script>
		<link rel="stylesheet" type="text/css" href="//cdn.jsdelivr.net/bootstrap.daterangepicker/2/daterangepicker.css" />
		<link rel="stylesheet" href="<?= base_url();?>assets/admin/css/extra.css"/>
		<link rel="stylesheet" type="text/css" href="//cdn.datatables.net/1.10.13/css/jquery.dataTables.css">

		 <script type="text/javascript" charset="utf8" src="//cdn.datatables.net/1.10.15/js/jquery.dataTables.min.js"></script>
		<script type="text/javascript" src="https://cdn.datatables.net/buttons/1.3.1/js/dataTables.buttons.min.js"></script> 
		<script type="text/javascript" src="https://cdn.datatables.net/buttons/1.3.1/js/buttons.flash.min.js"></script>
		<script type="text/javascript" src="https://cdnjs.cloudflare.com/ajax/libs/jszip/3.1.3/jszip.min.js"></script>
		<script type="text/javascript" src="https://cdn.rawgit.com/bpampuch/pdfmake/0.1.27/build/pdfmake.min.js"></script>
		<script type="text/javascript" src="https://cdn.rawgit.com/bpampuch/pdfmake/0.1.27/build/vfs_fonts.js"></script>
		<script type="text/javascript" src="https://cdn.datatables.net/buttons/1.3.1/js/buttons.html5.min.js"></script>
		<script type="text/javascript" src="https://cdn.datatables.net/buttons/1.3.1/js/buttons.print.min.js"></script>
		<script src="https://cdnjs.cloudflare.com/ajax/libs/modernizr/2.8.3/modernizr.js"></script>
		<script src="https://cdnjs.cloudflare.com/ajax/libs/jquery-timepicker/1.10.0/jquery.timepicker.min.js"></script>
		<link rel="stylesheet" type="text/css" href="https://cdnjs.cloudflare.com/ajax/libs/jquery-timepicker/1.10.0/jquery.timepicker.min.css">
		<script src="https://cdnjs.cloudflare.com/ajax/libs/jqueryui/1.12.1/jquery-ui.min.js"></script>
		<script src="https://rawgit.com/KidSysco/jquery-ui-month-picker/v3.0.0/demo/MonthPicker.min.js"></script>
		<link rel="stylesheet" href="<?= base_url();?>assets/admin/css/extra.css"/>
		<style>
		.dataTables_paginate.paging_simple_numbers{display: none;}
			.uploadifive-button{
				width:22% !important;
			}
			@media (min-width: 1200px)
			.container {
				width: auto;
			}
		</style>
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
								<li><a href="<?= base_url()?>admin">Dashboard</a></li>
							</ul>
							<ul class="nav navbar-nav navbar-right">
								<li>
									<span>
										Hello <?= $this->session->userdata('user_name'); ?>,
										Last login time: <?= $this->session->userdata('last_login'); ?>
									</span>

									<div class="btn-group ex">
										<a class="btn btn-default dropdown-toggle btn-select" data-toggle="dropdown" href="#">
											<span class="flg">
												<img title="flag" alt="flag" src="<?= base_url() ?>assets/admin/images/icon.png">
											</span>
											My Account
											<i class="fa fa-angle-down"></i>
										</a>
										<ul style="display:none" class="dropdown-menu">
											<li><a href="<?= base_url() ?>dashboard/change_password">Change password</a></li>
											<li><a href="<?= base_url() ?>login/logout">Logout</a></li>
										</ul>
									</div>
								</li>
							</ul>
						</div><!--/.nav-collapse -->
					</div>
				</nav>
			</div>
		</div>
		<div>
			<h3>
				<img src="<?= base_url()?>assets/img/logo.png" width=60px/ style="margin-left: 28px;">
			</h3>
			<ul class="nav nav-tabs">
				<li class="<?php if($name=='index'){echo 'active';}?>"><a href="<?= base_url()?>admin">Home</a></li>
				<li class="<?php if($name=='search'){echo 'active';}?>"><a href="<?= base_url()?>admin/search_callback">Search</a></li>
				<li class="<?php if($name=='generate'){echo 'active';}?>"><a href="<?= base_url()?>admin/generate_callback">Generate</a></li>
				<li class="<?php if($name=='callbacks'){echo 'active';}?>"><a href="<?= base_url()?>admin/callbacks">Call Backs</a></li>
				<li class="<?php if($name=='reports'){echo 'active';}?>"><a href="<?= base_url()?>admin/reports">Reports</a></li>
				<li class="<?php if($name=='revenue_approval'){echo 'active';}?>"><a href="<?= base_url()?>admin/revenue_approval">Revenue Approval</a></li>
				<li class="dropdown <?php if($name=='admin'){echo 'active';}?>">
					<a class="dropdown-toggle" data-toggle="dropdown" href="#">Admin<span class="caret"></span></a>
					<ul class="dropdown-menu">
						<li><a href="<?= base_url()?>admin/manage_users">User</a></li>
						<li><a href="<?= base_url()?>admin/manage_directors">Director</a></li>
						<li><a href="<?= base_url()?>admin/manage_managers">Manager</a></li>
						<li><a href="<?= base_url()?>admin/manage_vps">VP</a></li>
						<li><a href="<?= base_url()?>admin/manage_states">Manage State</a></li>
						<li><a href="<?= base_url()?>admin/manage_cities">Manage City</a></li>
						<li><a href="<?= base_url()?>admin/bulk_generate_callbacks">Excel Upload</a></li>
						<li><a href="<?= base_url()?>admin/manage_depts">Manage Dept</a></li>
						<li><a href="<?= base_url()?>admin/manage_lead_sources">Manage Lead Source</a></li>
						<li><a href="<?= base_url()?>admin/manage_builders">Manage Builder</a></li>
						<li><a href="<?= base_url()?>admin/manage_projects">Manage Project</a></li>
						<li><a href="<?= base_url()?>admin/manage_brokers">Manage Broker</a></li>
						<li><a href="<?= base_url()?>admin/manage_callback_types">Manage Call Back type</a></li>
						<li><a href="<?= base_url()?>admin/manage_status">Manage Status</a></li>
						<li><a href="<?= base_url()?>admin/generate_incentive_slab">Generate Incentive Slab</a></li>
						<li><a href="<?= base_url()?>admin/generate_target">Generate Target</a></li>
						<li><a href="<?= base_url()?>admin/dead_reason">Dead Reason</a></li>
					</ul>
				</li>
				<li class="dropdown <?php if($name=='more'){echo 'active';}?>">
					<a class="dropdown-toggle" data-toggle="dropdown" href="#">More<span class="caret"></span></a>
					<ul class="dropdown-menu">
						<li><a href="<?= base_url()?>admin/dead_leads">Dead leads</a></li>
						<li><a href="<?= base_url()?>admin/online_leads">Online leads</a></li>
					</ul>
				</li>
				<button class="btn btn-success"><a href="javascript:history.go(-1)">GO BACK</a></button>
			</ul>
		</div>
