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
        <table width="100%" border="0" cellpadding="0" cellspacing="0"  id="<?php if($_REQUEST[basic_table]!='true')echo "datatable_nopagination";?>" style="text-shadow: white 0.1em 0 0">
          <thead>
            <tr>
            	<th>user_employee_id</th>
              <th>Name</th>
              <th>e-mail</th>
              <th>User Role</th>
              <th>Joining date</th>
              <th>Project</th>
              <th>Country</th>
              <th>Billing Type</th>
              <th>Brand/Segment</th>
              <th>Production type</th>              
              <th><span style="width:80px; float:left">Date</span></th>
              <th>project_activity_type</th>
              <th>Production Hours</th>
              <th>Description</th>
              <th>Project Status</th>
              <th>Client</th>                            
              <th>Depp</th>
              <th>VML Job</th>              
            </tr>
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
              <td><?php echo $arr[$i][time_total];?></td>
              <td><?php echo $arr[$i][time_description];?></td>
              <td><?php echo $arr[$i][project_active]; ?></td> 
              <td><?php echo $arr[$i][client_company_name]; ?></td>                            
              <td><?php echo $arr[$i][project_depp_key]; ?></td>
              <td><?php echo $arr[$i][project_vml_job_number];?></td>
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
</body>
</html>