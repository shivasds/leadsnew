<?php 
    defined('BASEPATH') OR exit('No direct script access allowed');
    $this->load->view('inc/admin_header'); 
?>
<div class="container">  
    <div class="page-header">
        <h1><?php echo $heading; ?></h1>
        <p class="text-center"><?php echo $duration; ?></p>
    </div>
   
<table id="example" class="table-bordered dt-responsive dataTable no-footer" cellspacing="0" width="100%" >
    <thead>
        <?php 
        $row['page']= $this->uri->segment(3);
        $userid=$this->input->post_get('userid');
        $fromDate=$this->input->post_get('fromDate');
        $endDate=$this->input->post_get('endDate');
        $reportType=$this->input->post_get('reportType');


        ?>

        <a  href="<?php echo site_url(); ?>ExcelReportController/<?php echo $row['page'].'?userid='.
                 $userid.'&fromDate='.$fromDate.'&endDate='.$endDate.'&reportType='.$reportType
                ?>" class="btn btn-default">download</a>
        <tr id="tableheading">
            <th>No</th>
            <th>Contact Name</th> 
            <th>Contact No</th>
            <th>Date of Site Visit</th>
            <th>Project</th>
            <th>Lead Source</th> 
        </tr>
    </thead> 
    <tbody id="main_body">
        <?php 
        if(count($fetchData)>0){
            $k=1;
            $tmpArry = array();
            foreach ($fetchData as $data) {
                if(!in_array($data['id'], $tmpArry)) {
                	?>
                    <tr id="row<?php echo $k; ?>">
                        <td><?php echo $k; ?></td>
                        <td><?php echo $data['name']; ?></td>
                        <td><?php echo $data['contactNo'] ?></td>
                        <td><?php echo $data['visitDate']; ?></td>
                        <td><?php echo implode(', ', $projectsData[$data['id']]); ?></td>
                        <td><?php echo $data['leadSourceName']; ?></td>
                    </tr>
                    <?php 
                    $k++;
                }
                $tmpArry[] =  $data['id'];

            } 
        }?>
    </tbody>
</table>

</div>     
      
</body>

</html>