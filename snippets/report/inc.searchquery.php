<?php
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
		//echo "<b>Query:</b><br>___<br>$q_filtered<br>";
		$r = mysql_query($q_filtered) or die(mysql_error()."<b>Query:</b><br>___<br>$q_filtered<br>");
		$rows = mysql_num_rows($r);
		if ($rows > 0) {
				$arr = mysql_fetch_rowsarr($r);
		}						
} 
?>