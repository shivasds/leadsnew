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
		<style type="text/css">
		.dataTables_paginate.paging_simple_numbers{display: none;}
			.uploadifive-button{
				width:22% !important;
			}
			@media (min-width: 1200px)
			.container {
				width: auto;
			}
		
		    label.pm-list {
		        font-size: 15px;
		    }
		    label.m-list {
		        width: 200px;
		        font-size: 12px;
		         margin: 0;
		    }
		    label.m-list input[type="checkbox"],
		    label.pm-list input[type="checkbox"]{
		        margin: 0 4px;
		        line-height: 7px;
		        vertical-align: middle;
		    }
		    fieldset div {
		        margin-left: 28px;
		    }
		</style>
		<script type="text/javascript">
            $(function(){

            	$('.datepicker').each(function(){
				    $(this).datepicker({
	                 	dateFormat: 'yy-mm-dd',
	                });
				});
				$('#ui-datepicker-div').draggable();
				$('#c_bkngMnth, #c_estMonthofInvoice').MonthPicker({
             Button: false
        		  });
				$('.timePicker').each(function(){
                    $(this).timepicker({ 'timeFormat': 'H:i' });
                });
            });
            var BASE_URL = '<?= base_url();?>';
        </script>
        <script src="<?= base_url();?>assets/js/custom.js"></script>
	</head>
	<body>
		<!-- Modal Permission-->
		<div class="modal fade" id="modalPermission" role="dialog" data-backdrop="static">
		    <div class="modal-dialog modal-lg">
		        <!-- Modal content-->
		        <form id="privilege-frm" class="" name="" method="post">
		            <div class="modal-content">
		                <div class="modal-header">                    
		                    <button type="button" class="close" data-dismiss="modal">&times;</button>
		                    <h4 class="modal-title">Give Permission</h4> 
		                </div>
		                <div class="modal-body permission-lists">                        
		                        <!-- fetch from ajax jquery -->                        
		                </div>
		                <div class="modal-footer">
		                    <button type="button" class="btn btn-success sbmt"  data-dismiss="modal">Submit</button>
		                    <input type="hidden" name="userId" value="" class="userId">                        
		                </div>
		                <div class="clearfix"></div>
		                <div class="col-sm-6 errMsg"></div>
		                <div class="clearfix"></div>
		            </div>            
		        </form>
		    </div>
		</div>
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
			
			<?php $this->load->view('inc/header_nav');?>
		</div>
