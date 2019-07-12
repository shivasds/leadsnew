<?php
if (!defined('BASEPATH'))
    exit('No direct script access allowed');
 
class ExcelController extends CI_Controller {
	// construct
    public function __construct() {
        parent::__construct();
		// load model
		$this->load->model('callback_model');   
    }    
	 // export xlsx|xls file
    public function index() {

		$where="";
		if($this->input->post()){
			$dept=$this->input->post('dept');
			$project=$this->input->post('project');
			$lead_source=$this->input->post('lead_source');
			$user_name=$this->input->post('user_name');
			$sub_broker=$this->input->post('sub_broker');
			$status=$this->input->post('status');
			$city=$this->input->post('city');
			if($dept!==null){
				$this->session->set_userdata("department",$dept);
				if($dept)
					$where.=" AND cb.dept_id=".trim($dept);
			}
			if($project!==null){
				$this->session->set_userdata("project",$project);
				if($project)
					$where.=" AND cb.project_id=".trim($project);
			}
			if($lead_source!==null){
				$this->session->set_userdata("lead_source",$lead_source);
				if($lead_source)
					$where.=" AND cb.lead_source_id=".trim($lead_source);
			}
			if($user_name!==null){
				$this->session->set_userdata("search_username",$user_name);
				if($user_name)
					$where.=" AND cb.user_id=".trim($user_name);
			}
			if($sub_broker!==null){
				$this->session->set_userdata("sub_broker",$sub_broker);
				if($sub_broker)
					$where.=" AND cb.broker_id=".trim($sub_broker);
			}
			if($status!==null){
				$this->session->set_userdata("status",$status);
				if($status)
					$where.=" AND cb.status_id=".trim($status);
			}
			if($city!==null){
				$this->session->set_userdata("city",$city);
				if($city)
					$where.=" AND u.city_id=".trim($city);
			}
			
			$srxhtxt = trim($this->input->post('srxhtxt'));
			if($srxhtxt !==null ){
				$this->session->set_userdata('SRCHTXT', $srxhtxt);	
				if($srxhtxt)			
					$where .=" AND (cb.name='".$srxhtxt."' OR cb.email1='".$srxhtxt."' OR cb.contact_no1='".$srxhtxt."' OR cb.leadid='".$srxhtxt."' OR p.name='".$srxhtxt."' OR ls.name = '".$srxhtxt."' OR concat(u.first_name,' ',u.last_name) ='".$srxhtxt."' OR b.name='".$srxhtxt."')";
			}
			$searchDate = $this->input->post('searchDate');
			if($searchDate  !==null) {
				$this->session->set_userdata('SRCHDT', $searchDate);

				if($searchDate && $searchDate == 'today')
					$where .=" AND cb.due_date like '%".date('Y-m-d')."%'";
				elseif ($searchDate && $searchDate == 'yesterday') 
					$where .=" AND cb.due_date < '".date('Y-m-d')."'";
				elseif ($searchDate && $searchDate == 'tomorrow') 
					$where .=" AND cb.due_date > '".date('Y-m-d')."'";
			}	
			//echo $where;		
		}
		else{
			if($this->session->userdata("department"))
				$where.=" AND cb.dept_id=".trim($this->session->userdata("department"));
			if($this->session->userdata("project"))
				$where.=" AND cb.project_id=".trim($this->session->userdata("project"));
			if($this->session->userdata("lead_source"))
				$where.=" AND cb.lead_source_id=".trim($this->session->userdata("lead_source"));
			if($this->session->userdata("search_username"))
				$where.=" AND cb.user_id=".trim($this->session->userdata("search_username"));
			if($this->session->userdata("sub_broker"))
				$where.=" AND cb.broker_id=".trim($this->session->userdata("sub_broker"));
			if($this->session->userdata("status"))
				$where.=" AND cb.status_id=".trim($this->session->userdata("status"));
			if($this->session->userdata("city"))
				$where.=" AND u.city_id=".trim($this->session->userdata("city"));

			if($this->session->userdata('SRCHTXT')){
				$searchVal = $this->session->userdata('SRCHTXT');
				$where .=" AND (cb.name='".$searchVal."' OR cb.email1='".$searchVal."' OR cb.contact_no1='".$searchVal."' OR cb.leadid='".$searchVal."' OR p.name='".$searchVal."' OR ls.name = '".$searchVal."' OR concat(u.first_name,' ',u.last_name) ='".$searchVal."' OR b.name='".$searchVal."')";
			}

			if($this->session->userdata('SRCHDT')!=''){
				if($this->session->userdata('SRCHDT') == 'today')
					$where .=" AND cb.due_date like '%".date('Y-m-d')."%'";
				elseif ($this->session->userdata('SRCHDT') == 'yesterday') 
					$where .=" AND cb.due_date < '".date('Y-m-d')."'";
				elseif ($this->session->userdata('SRCHDT') == 'tomorrow') 
					$where .=" AND cb.due_date > '".date('Y-m-d')."'";
			}
		}
       
        	$page = $this->uri->segment(2);
        	$startpage =$page;
        	if(empty($startpage)==true)
        	$startpage=1;
        	
        	//echo $page;
        	$topage=$page+200;
	 			$fileName = 'callbacks '.$startpage.' to '.$topage.'.xlsx';   //
		//load excel library
		 $this->load->library('excel'); 

		//$where="";
		
		        $offset = !$page ? 0 : $page;
				//------ End --------------

		        $data = $this->callback_model->search_callback(null,$where,$offset,VIEW_PER_PAGE);

		$objPHPExcel = new PHPExcel();
		$objPHPExcel->setActiveSheetIndex(0);
		 
		 // set Header
		$objPHPExcel->getActiveSheet()->SetCellValue('A1', 'No');
		$objPHPExcel->getActiveSheet()->SetCellValue('B1', 'Contact Name');
		$objPHPExcel->getActiveSheet()->SetCellValue('C1', 'Contact No');
		$objPHPExcel->getActiveSheet()->SetCellValue('D1', 'Email');
		$objPHPExcel->getActiveSheet()->SetCellValue('E1', 'Project'); 
		$objPHPExcel->getActiveSheet()->SetCellValue('F1', 'Lead Source'); 
		$objPHPExcel->getActiveSheet()->SetCellValue('G1', 'Lead Id'); 
		$objPHPExcel->getActiveSheet()->SetCellValue('H1', 'Advisor'); 
		$objPHPExcel->getActiveSheet()->SetCellValue('I1', 'Sub-Source'); 
		$objPHPExcel->getActiveSheet()->SetCellValue('J1', 'Due date'); 
		$objPHPExcel->getActiveSheet()->SetCellValue('K1', 'Status'); 
		$objPHPExcel->getActiveSheet()->SetCellValue('L1', 'Date Added'); 
		$objPHPExcel->getActiveSheet()->SetCellValue('M1', 'Last Update');  

		//set rows        	
		          $rowC = 2;
		 		  $i= 1;
		        if(count($data)>0){
		        foreach ($data as $resultdata) {
		            $duedate = explode(" ", $resultdata->due_date);
		            $duedate = $duedate[0]; 
		             if(strtotime($duedate)<strtotime('today')){
					 }
					 elseif(strtotime($duedate) == strtotime('today')) 
					 {
					 }
					 elseif(strtotime($duedate)>strtotime('today')){ 
					 } 
        
		      $objPHPExcel->getActiveSheet()->SetCellValue('A' . $rowC, $i);
		      $objPHPExcel->getActiveSheet()->SetCellValue('B' . $rowC, $resultdata->name);
		    $objPHPExcel->getActiveSheet()->SetCellValue('C' . $rowC, $resultdata->contact_no1);
		      $objPHPExcel->getActiveSheet()->SetCellValue('D' . $rowC, $resultdata->email1);
		      $objPHPExcel->getActiveSheet()->SetCellValue('E' . $rowC, $resultdata->project_name);
		      $objPHPExcel->getActiveSheet()->SetCellValue('F' . $rowC, $resultdata->lead_source_name);
		      $objPHPExcel->getActiveSheet()->SetCellValue('G' . $rowC, $resultdata->leadid);
		      $objPHPExcel->getActiveSheet()->SetCellValue('H' . $rowC, $resultdata->user_name);
		      $objPHPExcel->getActiveSheet()->SetCellValue('I' . $rowC, $resultdata->broker_name);
		      $objPHPExcel->getActiveSheet()->SetCellValue('J' . $rowC, $resultdata->due_date);
		      $objPHPExcel->getActiveSheet()->SetCellValue('K' . $rowC, $resultdata->status_name);
		      $objPHPExcel->getActiveSheet()->SetCellValue('L' . $rowC, $resultdata->date_added);
		      $objPHPExcel->getActiveSheet()->SetCellValue('M' . $rowC,$resultdata->last_update);
		      $i++;
		 $rowC++; } }


		 	$objWriter = new PHPExcel_Writer_Excel2007($objPHPExcel);
	       $objWriter->save($fileName);
			// download file
header('Content-type: application/xls/vnd.ms-excel');
header('Content-Disposition: attachment; filename="' . $fileName . '"');
// Instantiate a Writer to create an OfficeOpenXML Excel .xlsx file
// Write the Excel file to filename some_excel_file.xlsx in the current directory
$objWriter = new PHPExcel_Writer_Excel2007($objPHPExcel);
// Write the Excel file to filename some_excel_file.xlsx in the current directory
$objWriter->save('php://output'); 
 
		               
    }
       function view_callback()
    {
    	
    
        $user_id=$this->input->post_get('advisor');
        $report = $this->input->post_get('report');
        $dept = $this->input->post_get('dept');
        $city = $this->input->post_get('city');
        $fromDate = $this->input->post_get('fromDate');
        $toDate = $this->input->post_get('toDate');
        if($this->session->userdata('user_type')=="user")
            $advisor = $this->session->userdata('user_id');
        else
        $advisor = $this->input->post_get('advisor');
        $project = $this->input->post_get('project');
        $lead_source = $this->input->post_get('lead_source');
        $status = $this->input->post_get('status');
        $cb_ids = $this->input->post_get('cb_ids');
        if($status == 'close')
            $status = '5';
        $due_date = $this->input->post_get('due_date');
        $due_date_to = $this->input->post_get('due_date_to');
        $due_date_from = $this->input->post_get('due_date_from');
        $for = $this->input->post_get('for');
        $access = $this->input->post_get('access');
        $important = $this->input->post_get('important');
        
        if($access)
            $data['access'] = $access;
        if($for == null)
            $for = 'report';

        $where = "";
        if($cb_ids){
            $data['cb_ids'] = $cb_ids;
            $cb_where = "(";
            $cb_id = explode(",", $cb_ids);
            foreach ($cb_id as $value) {
                if($value){
                    if($cb_where !== "(")
                        $cb_where .= " OR ";
                    $cb_where .= "cb.id=$value";
                }
            }
            $cb_where .= ")";
            $where.=" AND $cb_where";
        }
        else{
            if($dept){
                $data['dept'] = $dept;
                $where.=" AND cb.dept_id=".trim($dept);
            }
            if($city){
                $data['city_id'] = $city;
                $where.=" AND u.city_id=".trim($city);
            }
            if($fromDate){
                $data['fromDate'] = $fromDate;
                if($report == "due")
                    $where.=" AND cb.due_date>='".trim($fromDate)."'";
                else
                    $where.=" AND cb.date_added>='".trim($fromDate)."'";
            }
            if($toDate){
                $data['toDate'] = $toDate;
                if($report == "due")
                    $where.=" AND cb.due_date<='".trim($toDate)."'";
                else
                    $where.=" AND cb.date_added<='".trim($toDate)."'";
            }
            if($advisor){
                $data['advisor'] = $advisor;
                $adv_where = "(";
                $adv = explode(",", $advisor);
                foreach ($adv as $value) {
                    if($value){
                        if($adv_where !== "(")
                            $adv_where .= " OR ";
                        $adv_where .= "cb.user_id=$value";
                    }
                }
                $adv_where .= ")";
                $where.=" AND $adv_where";

            }
            if($project){
                $data['project'] = $project;
                $where.=" AND cb.project_id=".trim($project);
            }
            if($lead_source){
                $data['lead_source'] = $lead_source;
                $where.=" AND cb.lead_source_id=".trim($lead_source);
            }
            if($status){
                $data['status'] = $status;
                $where.=" AND cb.status_id='".trim($status)."'";
            }
            if($due_date){
                $data['due_date'] = $due_date;
                $where.=" AND DATE(cb.due_date)='".trim($due_date)."'";
            }
            else {
                if ($due_date_to){
                    $data['due_date_to'] = $due_date_to;
                    $where.=" AND cb.due_date<='".trim($due_date_to)."'";
                }
                if ($due_date_from){
                    $data['due_date_from'] = $due_date_from;
                    $where.=" AND cb.due_date>='".trim($due_date_from)."'";
                }
            }
            if($important){
                $data['important'] = $important;
                $this->session->set_userdata('advisor', $advisor);
                $this->session->set_userdata('access', $access);
                $where.=" AND cb.important=1";
            }
        }
        if($report == "due"){
            $where .= " AND cb.active = 1 AND cb.status_id != 4 AND cb.status_id != 5";
        }

        //------- pagination ------
        $rowCount               = $this->callback_model->count_search_records(null,$where,null,null, null, $for,$report);
     
       $page = $this->uri->segment(3);
        	$startpage =$page;
        	if(empty($startpage)==true)
        	$startpage=1;
		 $this->load->library('excel'); 
		
		        $offset = !$page ? 0 : $page;
        //------ End --------------

        $data = $this->callback_model->search_callback(null,$where,$offset,VIEW_PER_PAGE, null, $for,$report);

        //------- pagination ------
        $rowCount = $this->callback_model->count_search_records(null,$where,null,null, null, $for,$report);
        $totalRecords  = $rowCount;
 $this->load->library('excel'); 
   
		  

		$objPHPExcel = new PHPExcel();
		$objPHPExcel->setActiveSheetIndex(0);
		 
		 // set Header
		$objPHPExcel->getActiveSheet()->SetCellValue('A1', 'No');
		$objPHPExcel->getActiveSheet()->SetCellValue('B1', 'Contact Name');
		$objPHPExcel->getActiveSheet()->SetCellValue('C1', 'Added Date');
		$objPHPExcel->getActiveSheet()->SetCellValue('D1', 'Last Updated');
		$objPHPExcel->getActiveSheet()->SetCellValue('E1', 'Due Date'); 
		$objPHPExcel->getActiveSheet()->SetCellValue('F1', 'Status'); 
		$objPHPExcel->getActiveSheet()->SetCellValue('G1', 'Project'); 
		$objPHPExcel->getActiveSheet()->SetCellValue('H1', 'Comment'); 
		$objPHPExcel->getActiveSheet()->SetCellValue('I1', 'lead_source'); 
		$objPHPExcel->getActiveSheet()->SetCellValue('J1', 'Sub_Source'); 
		

		//set rows        	
		          $rowC = 2;
		 		  $i= 1;
		        if(count($data)>0){
		        foreach ($data as $resultdata) {
		            
      			 $objPHPExcel->getActiveSheet()->SetCellValue('A' . $rowC, $i);
		   	   $objPHPExcel->getActiveSheet()->SetCellValue('B' . $rowC,  $resultdata->name);
		    	$objPHPExcel->getActiveSheet()->SetCellValue('C' . $rowC,  $resultdata->date_added);
		      $objPHPExcel->getActiveSheet()->SetCellValue('D' . $rowC,  $resultdata->last_update);
		      $objPHPExcel->getActiveSheet()->SetCellValue('E' . $rowC,  $resultdata->due_date);
		      $objPHPExcel->getActiveSheet()->SetCellValue('F' . $rowC,  $resultdata->status_name);
		      $objPHPExcel->getActiveSheet()->SetCellValue('G' . $rowC,  $resultdata->project_name);
		      $objPHPExcel->getActiveSheet()->SetCellValue('H' . $rowC,  $resultdata->notes);
		      $objPHPExcel->getActiveSheet()->SetCellValue('I' . $rowC,  $resultdata->lead_source_name);
		      $objPHPExcel->getActiveSheet()->SetCellValue('J' . $rowC,  $resultdata->broker_name);
		      $i++;
		 $rowC++; } }

			$topage=$page+count($data);
	 			$fileName ='lead_breakup_report '.$startpage.' to '.$topage.'.xlsx';
		 	$objWriter = new PHPExcel_Writer_Excel2007($objPHPExcel);
	       $objWriter->save($fileName);
			// download file
header('Content-type: application/xls/vnd.ms-excel');
header('Content-Disposition: attachment; filename="' . $fileName . '"');
// Instantiate a Writer to create an OfficeOpenXML Excel .xlsx file
// Write the Excel file to filename some_excel_file.xlsx in the current directory
$objWriter = new PHPExcel_Writer_Excel2007($objPHPExcel);
// Write the Excel file to filename some_excel_file.xlsx in the current directory
$objWriter->save('php://output'); 
	
    }
    
}
?>