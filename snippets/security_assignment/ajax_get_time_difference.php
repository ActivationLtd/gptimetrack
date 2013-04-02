<?php 
include_once('../../config.php');
$start_date=$_REQUEST[start_date];
$end_date=$_REQUEST[end_date];
$rate=$_REQUEST[rate];
//echo $start_date."<br>";
//echo $end_date."<br>";
echo "Time: ".my_date_diff($start_date,$end_date)." & Total Amount: ".round(my_hour_diff($start_date,$end_date)*$rate,2);
?>