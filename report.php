<?php
include('config.php');
$valid = true;
$alert = array();
//$client_id=$_REQUEST[client_id];
//myprint_r($_REQUEST);
$parameterized_query = "";
if (count($_REQUEST[client_id])) {
    $client_id_csv = implode(',', $_REQUEST[client_id]);
    $parameterized_query.=" client_id in ($client_id_csv) AND ";
}
if (count($_REQUEST[user_id])) {
    $user_id_csv = implode(',', $_REQUEST[user_id]);
    $parameterized_query.=" user_id in ($user_id_csv) AND ";
}
if (count($_REQUEST[project_id])) {
    $project_id_csv = implode(',', $_REQUEST[project_id]);
    $parameterized_query.=" project_id in ($project_id_csv) AND ";
}

if (strlen($_REQUEST[date_start_datetime]) || strlen($_REQUEST[date_end_datetime])) {
    //    start date filter
    if (strlen($_REQUEST[date_start_datetime])) {
        $date_start_datetime = $_REQUEST[date_start_datetime];
        $parameterized_query.=" time_date >= '$date_start_datetime' AND ";
        // echo "<br />start: $date_start_datetime<br />";
    }
    //    end date filter
    if (strlen($_REQUEST[date_end_datetime])) {
        $date_end_datetime = $_REQUEST[date_end_datetime];
        $parameterized_query.=" time_date <= '$date_end_datetime' AND ";
        //echo "<br />start: $date_end_datetime_datetime<br />";
    }
}
//year filter
if (strlen($_REQUEST[year]) && $_REQUEST[month] == "month" && strlen($_REQUEST[week_number]) == 0) {
    $year = $_REQUEST[year];
    if (empty($year)) {
        $year = 2012;
    }
    $filter_year_start = date("Y-m-d H:i:s", mktime(0, 0, 0, 1, 1, $year));
    $date = strtotime($filter_year_start);
    $date = strtotime("+1 year", $date);
    $filter_year_end = date("Y-m-d H:i:s", $date);
    $parameterized_query.=" start_time >= '$filter_year_start' AND start_time < '$filter_year_end' AND";
}
//week filter
if (strlen($_REQUEST[week_number])) {
    $year = $_REQUEST[year];
    $week_number = $_REQUEST[week_number];
    if (empty($year)) {
        $year = date('Y');
    }
    $firstMon = strtotime("mon jan {$year}");
    $weeksOffset = $week_number - date('W', $firstMon);
    $searchedMon = strtotime("+{$weeksOffset} week " . date('Y-m-d', $firstMon));
    $week_start_date = date("Y-m-d", $searchedMon);
    $d = strtotime("+7 day", $searchedMon);
    $week_end_date = date("Y-m-d", $d);
    $parameterized_query.=" time_date >= '$week_start_date' AND time_date < '$week_end_date' AND";
}
//month filter
if (strlen($_REQUEST[month])) {
    $month = $_REQUEST[month];
    if ($month != "month") {
        $year = $_REQUEST[year];
        if (empty($year)) {
            $year = 2012;
        }
        for ($i = 1; $i <= 12; $i++) {
            if ($month_arr[$i] == $month) {
                $filter_month = $i;
            }
        }
        $filter_month_start = date("Y-m-d H:i:s", mktime(0, 0, 0, $filter_month, 1, $year));
        $date = strtotime($filter_month_start);
        $date = strtotime("+1 month", $date);
        $filter_month_end = date("Y-m-d H:i:s", $date);
        $parameterized_query.=" start_time >= '$filter_month_start' AND start_time < '$filter_month_end' AND";
    }
}
if (currentUserIsGeneralUser()) {
    $parameterized_query.=" user_id='" . $_SESSION[current_user_id] . "' AND ";
}
//echo "<br />end query: $parameterized_query";
//$todays_assign=" AND DATE(start_time) = DATE(NOW())";
if (isset($_REQUEST['submit'])) {
    $q_filtered = 
			"SELECT * 
				FROM client, project, time, user
				where $parameterized_query
					client_id=project_client_id AND 
					project_id=time_project_id AND
					time_user_id=user_id
				"	;			
		//echo $parameterized_query;
		echo "<b>Query:</b><br>___<br>$q_filtered<br>";
		$r = mysql_query($q_filtered) or die(mysql_error()."<b>Query:</b><br>___<br>$q_filtered<br>");
		$rows = mysql_num_rows($r);
		if ($rows > 0) {
				$arr = mysql_fetch_rowsarr($r);
		}		
				
} 



function createDropdown($arr, $frm) {
    echo '<select name="' . $frm . '" id="' . $frm . '">'; //<option value="">Select one…</option>';
    foreach ($arr as $key => $value) {
        if ($value == $_REQUEST[month]) {
            echo '<option selected value="' . $value . '">' . $value . '</option>';
        } else {
            echo '<option value="' . $value . '">' . $value . '</option>';
        }
    }
    echo '</select>';
}


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
        <table width="100%" border="0" cellpadding="0" cellspacing="0" id="datatable_nopagination" style="text-shadow: white 0.1em 0 0">
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
						$total_hours = 0;
						$total_amount = 0;
						for ($i = 0; $i < $rows; $i++) {
						?>
            <tr id="<?php echo $arr[$i][uid]; ?>" >
              <td><?php echo date('Y-m-d', strtotime($arr[$i][time_date])); ?></td>
              <td><?php echo "W" . date("W", strtotime($arr[$i][time_date])) . ""; ?></td>
              <td><?php echo $arr[$i][time_total];?></td>
              <td><?php echo $arr[$i][client_company_name]; ?></td>
              <td><?php echo $arr[$i][project_brand_name]; ?></td>
              <td><?php echo $arr[$i][project_name]; ?></td>
              <td><?php echo $arr[$i][project_depp_key]; ?></td>
              <td><?php echo $arr[$i][project_vml_job_number];?></td>
              <td><?php echo $arr[$i][user_fullname]; ?></td>
              <td><?php echo $arr[$i][user_role_name]; ?></td>              
              <td><?php echo nl2br($arr[$i][time_description]);?></td>
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
        <form action="security_assignment_list_to_excel.php" method="post">
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