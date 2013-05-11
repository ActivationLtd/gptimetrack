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
        <table width="100%" border="0" cellpadding="0" cellspacing="0"  id="<?php if($_REQUEST[basic_table]!='true')echo "report_datatable";?>" style="text-shadow: white 0.1em 0 0">
          <thead style="font-weight:bold;">
            <tr>
              <th>Date</th>
              <th>Week</th>
              <th>Hours</th>
              <th>Client</th>
              <th>Brand</th>
              <th>Project</th>
              <th>Depp</th>
              <th>VML Job</th>
              <th>User</th>
              <th>User Role</th>
              <th>Comment</th>
            </tr>
            <?php 
						if($_REQUEST[basic_table]!='true'){?>
              <tr class="filterInput">
                <td><input type="text" name="Date" value="" class="search_init" /></td>
                <td><input type="text" name="Week" value="" class="search_init" /></td>
                <td><input type="text" name="Hours" value="" class="search_init" /></td>
                <td><input type="text" name="Client" value="" class="search_init" /></td>
                <td><input type="text" name="Brand" value="" class="search_init" /></td>
                <td><input type="text" name="Project" value="" class="search_init" /></td>                
                <td><input type="text" name="Depp" value="" class="search_init" /></td>
                <td><input type="text" name="VML Job" value="" class="search_init" /></td>
                <td><input type="text" name="User" value="" class="search_init" /></td>
                <td><input type="text" name="User Role" value="" class="search_init" /></td>
                <td><input type="text" name="Comment" value="" class="search_init" /></td>                
              </tr>
            <?php 
						}?>
          </thead>
          <tbody>
            <?php
						for ($i=0; $i<$rows; $i++){
						?>
            <tr id="<?php echo $arr[$i][uid]; ?>" >
              <td><span style="width:80px; float:left"><?php echo date('Y-m-d', strtotime($arr[$i][time_date])); ?></span></td>
              <td><?php echo "W" . date("W", strtotime($arr[$i][time_date])) . ""; ?></td>
              <td><?php echo "<a href='time_list.php?time_id=".$arr[$i][time_id]."&param=edit'>".$arr[$i][time_total]."</a>";?></td>
              <td><?php echo $arr[$i][client_company_name]; ?></td>
              <td><?php echo $arr[$i][project_brand_name]; ?></td>
              <td><?php echo $arr[$i][project_name]; ?></td>
              <td><?php echo $arr[$i][project_depp_key]; ?></td>
              <td><?php echo $arr[$i][project_vml_job_number];?></td>
              <td><?php echo $arr[$i][user_fullname]; ?></td>
              <td><?php echo $arr[$i][user_role_name]; ?></td>              
              <td><?php echo $arr[$i][time_description];?></td>
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
	oTable = $('#report_datatable').dataTable( {
		"bPaginate":false,
		"sPaginationType":"full_numbers",
		"iDisplayLength":25,
		"bStateSave":false,
		"oLanguage":{
			"sSearch":"Search all columns:"
		},
		"bSortCellsTop":true
	});

	$("thead input").keyup(function(){
		/* Filter on the column (the index) of this element */
		oTable.fnFilter( this.value, $("thead input").index(this) );
		var index=$("thead input").index(this);
		index++;
		//alert(index);
		$("#report_datatable tbody tr td:nth-child("+index+")").removeHighlight();
		$("#report_datatable tbody tr td:nth-child("+index+")").highlight($(this).val());
	});
	/*
	 * Support functions to provide a little bit of 'user friendlyness' to the textboxes in
	 * the footer
	 */
	$("thead input").each(function(i){
		asInitVals[i]=this.value;
	});
	/*
	 * Support functions to provide a little bit of 'user friendlyness' to the textboxes in
	 * the footer
	 */
	$("thead input").each(function(i){
		asInitVals[i]=this.value;
	});

	$("thead input").focus(function(){
		if(this.className=="search_init")
		{
			this.className="search_init_focus";
			this.value="";
		}
	});

	$("thead input").blur( function (i){
		if ( this.value=="" )
		{
			this.className="search_init";
			this.value = asInitVals[$("thead input").index(this)];
		}
	});	
});
</script>
</body>
</html>