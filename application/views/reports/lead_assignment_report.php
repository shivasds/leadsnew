<?php 
    defined('BASEPATH') OR exit('No direct script access allowed');
    if($this->session->userdata("user_type") == "admin")
        $this->load->view('inc/admin_header');
    else
        $this->load->view('inc/header');
?>
<style type="text/css">
	.display td {
	    border: 1px solid #aaa;
	    padding: 5px
	  }
</style>
</head>
<div class="container">
	<div class="page-header">
	  <h1><?php echo $heading; ?></h1>
	</div>
	<div class="alert alert-success" style="display: none;">
		<strong>Success!</strong> Email Sent.
	</div>
	<div class="alert alert-danger" style="display: none;">
		<strong>Error!</strong> Email not Sent.
	</div>
	<form method="GET" action="<?php echo base_url()?>admin/generate_report">
		<div class="col-sm-2 form-group">
			<label for="emp_code">Dept:</label>
            <select  class="form-control"  id="dept" name="dept" >
                <option value="">Select</option>
                <?php $all_department=$this->common_model->all_active_departments();
                foreach($all_department as $department){ ?>
                    <option value="<?php echo $department->id; ?>" <?php if($department->id==$dept) echo 'selected'; ?>><?php echo $department->name; ?></option>
                <?php }?>              
            </select>
		</div>
		<?php

            $city='';
            
            if($this->session->userdata('user_type')=='City_head')
            {
                $city=$this->session->userdata('city_id');
               // echo $city."this is city id";die;
            }
            ?>
	
		<div class="col-sm-2 form-group">
			<label for="emp_code">City:</label>
            <select  class="form-control"  id="city" name="city" disabled="">
                <option value="">Select</option>
                <?php $cities= $this->common_model->all_active_cities(); 
                foreach( $cities as $c){ ?>
                    <option value="<?php echo $c->id; ?>" <?php if($c->id==$city) echo 'selected'; ?> ><?php echo $c->name ?></option>
                <?php } ?>               
            </select>
		</div>
		<div class="col-sm-2 form-group">
			<label for="emp_code">Project:</label>
            <!--<select  class="form-control"  id="project" name="project" required >
                <option value="">Select</option>
                <?php $all_projects=$this->common_model->all_active_projects();
                foreach($all_projects as $project){ ?>
                    <option value="<?php echo $project->id; ?>" <?php if($project->id==$dept) echo 'selected'; ?>><?php echo $project->name; ?></option>
                <?php }?>              
            </select>-->
            
            <div class="autocomplete" style="width:300px;">
    <input id="myInput" type="text" name="myProject" placeholder="Project">
  </div>

            <?php
            $all_projects=$this->common_model->all_active_projects();
            $string='';
			foreach($all_projects as $project){
			$string.= '"'.trim($project->name).'",';
			}
			$string=rtrim($string,",");
			//echo $string;


            ?>
		</div>
		<div class="col-sm-2 form-group">
            <button type="submit" id="Generate" class="btn btn-success btn-block" style="margin-top: 25px; margin-right: 0px;">Filter</button>
        </div>
        <div class="col-sm-3 form-group">
            <button id="email_this_report" class="btn btn-default btn-block">Email this report</button>
        </div>
    </form>
    <br>

	<table class="display" cellspacing="0" width="100%">
		<thead>
			
			<tr>
				<th>Sl.No</th>
				<th>Advisor Name</th>
				<?php foreach ($projectCallbacks as $key => $value) {
					$name = $this->common_model->get_project_name($key);
					echo '<th>'.$name.'</th>';
				}

				if($project=='')
				{
				 ?>
				
				<th>Total Calls</th>
				<?php } ?>
			</tr>
		</thead>
		<tbody>
			<?php if(count($advisors)>0){
				$i = 1;
				foreach ($advisors as $key => $advisor) { 
					$name = $this->user_model->get_user_fullname($key);?>
				 	<tr>
				 		<td><?php echo $i; ?></td>
				 		<td><?php echo $name; ?></td>
				 		<?php $total = 0;
				 		foreach ($projectCallbacks as $project => $value) {
				 			if(array_key_exists($project, $advisor)){
				 				$total += $advisor[$project];
				 				echo '<td><a href="'.base_url().'view_callbacks?report=lead_assignment&advisor='.urlencode($key).'&project='.urlencode($project).'&dept='.urlencode($dept).'&city='.urlencode($city).'&fromDate='.urlencode($fromDate).'&toDate='.urlencode($toDate).'">'.$advisor[$project].'</a></td>';
				 			}
				 			else
				 				echo '<td>0</td>';
				 			
				 		}

				 		if($project=='')
				 		{ ?>
				 		<td><?php echo '<a href="'.base_url().'view_callbacks?report=lead_assignment&advisor='.urlencode($key).'&dept='.urlencode($dept).'&city='.urlencode($city).'&fromDate='.urlencode($fromDate).'&toDate='.urlencode($toDate).'">'.$total.'</a>'; ?></td>
				 	</tr>
				<?php  } 	$i++;}
			} else { ?>
				<tr>
					<td colspan="<?php echo count($projectCallbacks)+2; ?>"> No entries </td>
				</tr>
			<?php } 
$this->session->unset_userdata('report-project');
			?>
		</tbody>
	</table>
	<br>
	<br>
</div>
</body>
<script type="text/javascript">
	$("#email_this_report").click(function(e){
		e.preventDefault();
		$(".alert-success").hide();
		$(".alert-danger").hide();
		$.get("<?php echo base_url().'admin/email_report?fromDate='.urlencode($fromDate).'&toDate='.urlencode($toDate).'&city='.urlencode($city).'&dept='.urlencode($dept).'&reportType='.urlencode($reportType); ?>", function(response){
			if(response == "Success")
				$(".alert-success").show();
			else
				$(".alert-danger").show();
		});
	});


function autocomplete(inp, arr) {
  /*the autocomplete function takes two arguments,
  the text field element and an array of possible autocompleted values:*/
  var currentFocus;
  /*execute a function when someone writes in the text field:*/
  inp.addEventListener("input", function(e) {
      var a, b, i, val = this.value;
      /*close any already open lists of autocompleted values*/
      closeAllLists();
      if (!val) { return false;}
      currentFocus = -1;
      /*create a DIV element that will contain the items (values):*/
      a = document.createElement("DIV");
      a.setAttribute("id", this.id + "autocomplete-list");
      a.setAttribute("class", "autocomplete-items");
      /*append the DIV element as a child of the autocomplete container:*/
      this.parentNode.appendChild(a);
      /*for each item in the array...*/
      for (i = 0; i < arr.length; i++) {
        /*check if the item starts with the same letters as the text field value:*/
        if (arr[i].substr(0, val.length).toUpperCase() == val.toUpperCase()) {
          /*create a DIV element for each matching element:*/
          b = document.createElement("DIV");
          /*make the matching letters bold:*/
          b.innerHTML = "<strong>" + arr[i].substr(0, val.length) + "</strong>";
          b.innerHTML += arr[i].substr(val.length);
          /*insert a input field that will hold the current array item's value:*/
          b.innerHTML += "<input type='hidden' value='" + arr[i] + "'>";
          /*execute a function when someone clicks on the item value (DIV element):*/
          b.addEventListener("click", function(e) {
              /*insert the value for the autocomplete text field:*/
              inp.value = this.getElementsByTagName("input")[0].value;
              /*close the list of autocompleted values,
              (or any other open lists of autocompleted values:*/
              closeAllLists();
          });
          a.appendChild(b);
        }
      }
  });
  /*execute a function presses a key on the keyboard:*/
  inp.addEventListener("keydown", function(e) {
      var x = document.getElementById(this.id + "autocomplete-list");
      if (x) x = x.getElementsByTagName("div");
      if (e.keyCode == 40) {
        /*If the arrow DOWN key is pressed,
        increase the currentFocus variable:*/
        currentFocus++;
        /*and and make the current item more visible:*/
        addActive(x);
      } else if (e.keyCode == 38) { //up
        /*If the arrow UP key is pressed,
        decrease the currentFocus variable:*/
        currentFocus--;
        /*and and make the current item more visible:*/
        addActive(x);
      } else if (e.keyCode == 13) {
        /*If the ENTER key is pressed, prevent the form from being submitted,*/
        e.preventDefault();
        if (currentFocus > -1) {
          /*and simulate a click on the "active" item:*/
          if (x) x[currentFocus].click();
        }
      }
  });
  function addActive(x) {
    /*a function to classify an item as "active":*/
    if (!x) return false;
    /*start by removing the "active" class on all items:*/
    removeActive(x);
    if (currentFocus >= x.length) currentFocus = 0;
    if (currentFocus < 0) currentFocus = (x.length - 1);
    /*add class "autocomplete-active":*/
    x[currentFocus].classList.add("autocomplete-active");
  }
  function removeActive(x) {
    /*a function to remove the "active" class from all autocomplete items:*/
    for (var i = 0; i < x.length; i++) {
      x[i].classList.remove("autocomplete-active");
    }
  }
  function closeAllLists(elmnt) {
    /*close all autocomplete lists in the document,
    except the one passed as an argument:*/
    var x = document.getElementsByClassName("autocomplete-items");
    for (var i = 0; i < x.length; i++) {
      if (elmnt != x[i] && elmnt != inp) {
        x[i].parentNode.removeChild(x[i]);
      }
    }
  }
  /*execute a function when someone clicks in the document:*/
  document.addEventListener("click", function (e) {
      closeAllLists(e.target);
  });
}

/*An array containing all the country names in the world:*/
var countries = [<?php echo $string;?>];

/*initiate the autocomplete function on the "myInput" element, and pass along the countries array as possible autocomplete values:*/
autocomplete(document.getElementById("myInput"), countries);
</script>
