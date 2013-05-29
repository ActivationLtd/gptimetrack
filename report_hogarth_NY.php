<?php
include('config.php');
$valid = true;
$alert = array();
include('snippets/report/inc.searchquery.php');
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html>
<head>
<?php include('inc.head.php'); ?>
</head>
<body>
<div id="wrapper">
  <div id="container">
    <div id="top1">
      <?php include('top.php'); ?>
    </div>
    <div id="mid">
        <div class="alert"> <?php printAlert($valid, $alert); ?> </div>
      <?php include('snippets/report/inc.filter.php');?>
      <div class="clear"></div>
        <table width="100%" border="0" cellpadding="0" cellspacing="0"  id="<?php if($_REQUEST[basic_table]!='true')echo "report_table";?>" style="text-shadow: white 0.1em 0 0">
          <thead>
            <tr>
            	<th>Emp_id</th>
              <th>Name</th>
              <th>e-mail</th>
              <th>User Role</th>
              <th>Joining_date</th>
              <th>Project</th>
              <th>Country</th>
              <th>Billing Type</th>
              <th>Brand/Segment</th>
              <th>Production type</th>              
              <th><span style="width:80px; float:left">Date</span></th>
              <th>Activity type</th>
              <th>Hours</th>
              <th>Description</th>
              <th>Status</th>
              <th>Client</th>                            
              <th>Depp</th>
              <th>VML #</th>
              <th>Week</th>              
            </tr>
            <?php 
						if($_REQUEST[basic_table]!='true'){?>
              <tr class="filterInput">
                <td><input type="text" name="user_employee_id" value="" class="search_init" /></td>
                <td><input type="text" name="name" value="" class="search_init" /></td>
                <td><input type="text" name="email" value="" class="search_init" /></td>
                <td><input type="text" name="role" value="" class="search_init" /></td>
                <td><input type="text" name="joining_date" value="" class="search_init" /></td>
                <td><input type="text" name="project" value="" class="search_init" /></td>                
                <td><input type="text" name="Country" value="" class="search_init" /></td>
                <td><input type="text" name="billing_type" value="" class="search_init" /></td>
                <td><input type="text" name="Brand_segment" value="" class="search_init" /></td>
                <td><input type="text" name="production_type" value="" class="search_init" /></td>
                <td><input type="text" name="date" value="" class="search_init" /></td>
                <td><input type="text" name="project_activity_type" value="" class="search_init" /></td>
                <td><input type="text" name="hours" value="" class="search_init" /></td>
                <td><input type="text" name="Description" value="" class="search_init" /></td>
                <td><input type="text" name="Status" value="" class="search_init" /></td>
                <td><input type="text" name="Client" value="" class="search_init" /></td>
                <td><input type="text" name="Depp" value="" class="search_init" /></td>
                <td><input type="text" name="ML Job" value="" class="search_init" /></td>                
              </tr>
            <?php 
						}?>
          </thead>
          <tbody>
            <?php
						$total_hours = 0;
						$total_amount = 0;
						for ($i = 0; $i < $rows; $i++) {
						?>
            <tr id="<?php echo $arr[$i][uid]; ?>" >
            	<td><?php echo $arr[$i][user_employee_id];?></td>
              <td><?php echo $arr[$i][user_fullname]; ?></td>
              <td><?php echo $arr[$i][user_email]; ?></td>
              <td><?php echo $arr[$i][user_role_name]; ?></td>    
              <td><?php echo $arr[$i][user_joining_date]; ?></td>
              <td><?php echo $arr[$i][project_name]; ?></td>
              <td><?php echo $arr[$i][project_country_name]; ?></td> 
              <td><?php echo $arr[$i][project_billing_type]; ?></td> 
              <td><?php echo $arr[$i][project_brand_name]; ?></td>
              <td><?php echo $arr[$i][project_deliverable_type]; ?></td>
              <td><?php echo date('Y-m-d', strtotime($arr[$i][time_date])); ?></td>
              <td><?php echo $arr[$i][project_activity_type]; ?></td>
              <td><?php if($_REQUEST[basic_table]!='true'){echo "<a href='time_list.php?time_id=".$arr[$i][time_id]."&param=edit'>".$arr[$i][time_total]."</a>";}else{echo $arr[$i][time_total];}?></td>
              <td><?php echo $arr[$i][time_description];?></td>
              <td><?php echo $arr[$i][project_active]; ?></td> 
              <td><?php echo $arr[$i][client_company_name]; ?></td>                            
              <td><?php echo $arr[$i][project_depp_key]; ?></td>
              <td><?php echo $arr[$i][project_vml_job_number];?></td>
              <td><?php echo "W" . date("W", strtotime($arr[$i][time_date])) . ""; ?></td>
            </tr>
            <?php } ?>
          </tbody>
        </table>        
    </div>
    <div id="footer">
      <?php include('footer.php'); ?>
    </div>
  </div>
</div>
<script type="text/javascript" charset="utf-8">
var asInitVals = new Array();
$(document).ready(function() {
	
	oTable = $('#report_table').dataTable( {
		"bPaginate": false,
		"bStateSave": false,
		"oLanguage": {
			"sSearch": "Search all columns:"
		},
		"bSortCellsTop": true,
		"aaSorting": [[ 0, "desc" ]]
	} );
	

	$("thead input").keyup( function () {
		/* Filter on the column (the index) of this element */
		oTable.fnFilter( this.value, $("thead input").index(this) );
		var index=$("thead input").index(this);
		index++;
		//alert(index);
		$("#report_table tbody tr td:nth-child("+index+")").removeHighlight();
		$("#report_table tbody tr td:nth-child("+index+")").highlight($(this).val());
	} );



	/*
	 * Support functions to provide a little bit of 'user friendlyness' to the textboxes in
	 * the footer
	 */
	$("thead input").each( function (i) {
		asInitVals[i] = this.value;
	} );
	/*
	 * Support functions to provide a little bit of 'user friendlyness' to the textboxes in
	 * the footer
	 */
	$("thead input").each( function (i) {
		asInitVals[i] = this.value;
	} );

	$("thead input").focus( function () {
		if ( this.className == "search_init" )
		{
			this.className = "search_init_focus";
			this.value = "";
		}
	} );

	$("thead input").blur( function (i) {
		if ( this.value == "" )
		{
			this.className = "search_init";
			this.value = asInitVals[$("thead input").index(this)];
		}
	} );	

} );
</script>
</body>
</html>