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
          <thead style="font-weight:bold;">
            <tr>
            	<td>user_employee_id</td>
              <td>Name</td>
              <td>e-mail</td>
              <td>User Role</td>
              <td>Joining date</td>
              <td>Project</td>
              <td>Country</td>
              <td>Billing Type</td>
              <td>Brand/Segment</td>
              <td>Production type</td>              
              <td>Date</td>
              <td>project_activity_type</td>
              <td>Production Hours</td>
              <td>Description</td>
              <td>Project Status</td>
              <td>Client</td>                            
              <td>Depp</td>
              <td>VML Job</td>              
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
              <td><span style="width:80px; float:left"><?php echo date('Y-m-d', strtotime($arr[$i][time_date])); ?></span></td>
              <td><?php echo $arr[$i][project_activity_type]; ?></td>
              <td><?php echo $arr[$i][time_total];?></td>
              <td><?php echo nl2br($arr[$i][time_description]);?></td>
              <td><?php echo $arr[$i][project_active]; ?></td> 
              <td><?php echo $arr[$i][client_company_name]; ?></td>                            
              <td><?php echo $arr[$i][project_depp_key]; ?></td>
              <td><?php echo $arr[$i][project_vml_job_number];?></td>                                      
              
            </tr>
            <?php } ?>
          </tbody>
        </table>
        <div class="clear"></div>
        <?php
				if (hasPermission('security_assignment', 'add', $_SESSION[current_user_id])) {
						echo "Total hours : " . $total_hours . "<br />";
						echo "Total amount : " . $total_amount . "<br />";
				}
				?>
        <form action="#" method="post">
          <input type="hidden" name="sql_query_string" value="<?php echo $q_filtered; ?>" class="bgblue button" />
          <?php if (hasPermission('security_assignment', 'delete', $_SESSION[current_user_id])) { ?>
          <input type="submit" name="submit" value="Download Excel" style="float: right;" />
          <?php } ?>
        </form>
    </div>
    <div id="footer">
      <?php include('footer.php'); ?>
    </div>
  </div>
</div>
</body>
</html>