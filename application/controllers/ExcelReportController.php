<?php
if (!defined('BASEPATH')) exit('No direct script access allowed');
			 
class ExcelReportController extends CI_Controller 
{
				
    public function __construct() 
    {
        parent::__construct();
		$this->load->model('callback_model'); 
		$this->load->model('user_model');   
	}    

    public function index() 
    {	       
    	$this->load->library('excel'); 
    	$objPHPExcel = new PHPExcel();
		$objPHPExcel->setActiveSheetIndex(0);
		if($this->input->get('userid') && $this->input->get('fromDate') && $this->input->get('endDate') && $this->input->get('reportType')) 
		{
		$data['name'] = "reports";
		$advisorData = $this->user_model->get_user_data($this->input->get('userid'));
		switch ($this->input->get('reportType')) 
			{
			case 'site-visit-fixed':
			$data['heading']  = "Site Visit Fixed Report for ".$advisorData['first_name']." ".$advisorData['last_name'];
			$data['duration'] = "<strong>From</strong> <em>".$this->input->get('fromDate')."</em> <strong>To</strong> <em>".$this->input->get('endDate')."</em>";
			$clause = [
			'u.id'				=> $this->input->get('userid'),
			//'ced.callback_id'	=> $this->input->get('cbId'),
			'ced.type' 			=> 1,
			'ced.date >='		=> $this->input->get('fromDate'),
			'ced.date <='		=> $this->input->get('endDate')
			];
			break;
			case 'site-visit-done':
			$data['heading']  = "Site Visit Done Report for ".$advisorData['first_name']." ".$advisorData['last_name'];
			$data['duration'] = "<strong>From</strong> <em>".$this->input->get('fromDate')."</em> <strong>To</strong> <em>".$this->input->get('endDate')."</em>";
			$clause = [
			'u.id'				=> $this->input->get('userid'),
			//'ced.callback_id'	=> $this->input->get('cbId'),
			'ced.type' 			=> 2,
			'ced.date >='		=> $this->input->get('fromDate'),
			'ced.date <='		=> $this->input->get('endDate')
			];
			break;
			case 'face-to-face':
			$data['heading']  = "Face to Face Report for ".$advisorData['first_name']." ".$advisorData['last_name'];
			$data['duration'] = "<strong>From</strong> <em>".$this->input->get('fromDate')."</em> <strong>To</strong> <em>".$this->input->get('endDate')."</em>";
			$clause = [
			'u.id'				=> $this->input->get('userid'),
			//'ced.callback_id'	=> $this->input->get('cbId'),
			'ced.type' 			=> 3,
			'ced.date >='		=> $this->input->get('fromDate'),
			'ced.date <='		=> $this->input->get('endDate')
			];
			break;
			default:

				}
				
				
				$data['fetchData'] = $this->callback_model->sitevisit_data_details($clause);
				$prArry = array();  
				$page = $this->uri->segment(2);
				$startpage =$page;
				if(empty($startpage)==true)
				$startpage=1;
				$topage=$page+count($data['fetchData']);          
	            foreach ($data['fetchData'] as $key => $value) {
	            	$prArry[$value['id']][$key] = $value['id'];
	            	$prArry[$value['id']][$key] = $value['projectName'];
	            }
	            $projectsData = $prArry;
				$objPHPExcel->getActiveSheet()->SetCellValue('A1', 'No');
				$objPHPExcel->getActiveSheet()->SetCellValue('B1', 'Contact Name');
				$objPHPExcel->getActiveSheet()->SetCellValue('C1', 'Contact No');
				$objPHPExcel->getActiveSheet()->SetCellValue('D1', 'Date of visit');
				$objPHPExcel->getActiveSheet()->SetCellValue('E1', 'Project'); 
				$objPHPExcel->getActiveSheet()->SetCellValue('F1', 'Lead Source'); 
				$rowC = 2;
				$i= 1;
				$tmpArry=array();
				if(count($data['fetchData'])>0)
					{
					foreach ($data['fetchData'] as $data) 
					{
					if(!in_array($data['id'], $tmpArry)) 
					{
					            
$objPHPExcel->getActiveSheet()->SetCellValue('A' . $rowC, $i);
$objPHPExcel->getActiveSheet()->SetCellValue('B' . $rowC,  $data['name']);
$objPHPExcel->getActiveSheet()->SetCellValue('C' . $rowC,  $data['contactNo']);
$objPHPExcel->getActiveSheet()->SetCellValue('D' . $rowC,  $data['visitDate']);
$objPHPExcel->getActiveSheet()->SetCellValue('E' . $rowC,  implode(', ', $projectsData[$data['id']]));
$objPHPExcel->getActiveSheet()->SetCellValue('F' . $rowC,  $data['leadSourceName']);
				$i++;
				$rowC++; 
				}
				 $tmpArry[] =  $data['id'];
					}
					}

			
			$fileName ='Reports '.$startpage.' to '.$topage.'.xlsx';
			$objWriter = new PHPExcel_Writer_Excel2007($objPHPExcel);
			$objWriter->save($fileName);
			header('Content-type: application/xls/vnd.ms-excel');
			header('Content-Disposition: attachment; filename="' . $fileName . '"');
			$objWriter = new PHPExcel_Writer_Excel2007($objPHPExcel);
			$objWriter->save('php://output');



		}
	}
}
?>