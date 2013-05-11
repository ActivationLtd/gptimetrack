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
              <td>Date</td>
              <td>Week</td>
              <td>Hours</td>
              <td>Client</td>
              <td>Brand</td>
              <td>Project</td>
              <td>Depp</td>
              <td>VML Job</td>
              <td>User</td>
              <td>User Role</td>
              <td>Comment</td>
            </tr>
          </thead>
          <tbody>
            <?php
						for ($i=0; $i<$rows; $i++){
						?>
            <tr id="<?php echo $arr[$i][uid]; ?>" >
              <td><span style="width:80px; float:left"><?php echo date('Y-m-d', strtotime($arr[$i][time_date])); ?></span></td>
              <td><?php echo "W" . date("W", strtotime($arr[$i][time_date])) . ""; ?></td>
              <td><?php echo $arr[$i][time_total];?></td>
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
</body>
</html>