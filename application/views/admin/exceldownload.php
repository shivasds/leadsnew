<?php $row['page']= $this->uri->segment(3);?>
<head>
 <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/4.3.1/css/bootstrap.min.css">
  <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.4.0/jquery.min.js"></script>
  <script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.14.7/umd/popper.min.js"></script>
  <script src="https://maxcdn.bootstrapcdn.com/bootstrap/4.3.1/js/bootstrap.min.js"></script>
  <style>
      .bg
      {
          background-color:green;
          color:white;
          margin-top:10px;
          margin-bottom:20px;
          margin-left:10px;
      }
      
  </style>
</head>
    
<a class="btn btn-default bg" href="<?php echo site_url()?>excel/<?php echo  $row['page'];?>"><i class="fa fa-file-excel-o"></i> Download</a>
<style>
#tabledata {
  font-family: "Trebuchet MS", Arial, Helvetica, sans-serif;
  border-collapse: collapse;
  width: 150%;
}

#tabledata td, #tabledata th {
  border: 1px solid #ddd;
  padding: 8px;
}

#tabledata tr:nth-child(even){background-color: #f2f2f2;}

#tabledata tr:hover {background-color: #ddd;}

#tabledata th {
  padding-top: 12px;
  padding-bottom: 12px;
  text-align: left;
  background-color: #4CAF50;
  color: white;
}
</style>
<div class="table-responsive">
    <table id="tabledata" class="table table-hover tablesorter" >
        
            <tr>
                <th class="header">No</th>
                <th class="header">Contact Name</th>
                <th class="header">Contact No</th>
                <th class="header">Email</th>                           
                <th class="header">Project</th>                      
                <th class="header">Lead Source</th>
                <th class="header">Lead Id</th>
                <th class="header">Advisor</th>
                <th class="header">Sub Source</th>
                <th class="header">Due Date</th>
                <th class="header">Status</th>
                <th class="header">Date Added</th>
                <th class="header">Last Update</th>

            </tr>
                <tbody id="main_body">
        <?php 

        $i= 1;
        if(count($result)>0){
        foreach ($result as $data) {
            $duedate = explode(" ", $data->due_date);
            $duedate = $duedate[0]; ?>
            <tr id="row<?php echo $i ?>" <?php if(strtotime($duedate)<strtotime('today')){?> class="highlight_past" <?php }elseif(strtotime($duedate) == strtotime('today')) {?> class="highlight_now" <?php }elseif(strtotime($duedate)>strtotime('today')){ ?> class="highlight_future" <?php } ?>>
                <td><?php echo $i; ?></td>
                <td><?php echo $data->name; ?></td>
                <td><?php echo $data->contact_no1 ?></td>
                <td><?php echo $data->email1; ?></td>
                <td><?php echo $data->project_name; ?></td>
                <td><?php echo $data->lead_source_name; ?></td>
                <td><?php echo $data->leadid; ?></td>
                <td><?php echo $data->user_name; ?></td>
                <td><?php echo $data->broker_name; ?></td>
                <td class="due_date"><?php echo $data->due_date; ?></td>
                <td><?php echo $data->status_name; ?></td>
                <td><?php echo $data->date_added; ?></td>
                <td><?php echo $data->last_update; ?></td>
               
            </tr>
        <?php $i++; } }?>
    </tbody>
    </table>
    
</div> 