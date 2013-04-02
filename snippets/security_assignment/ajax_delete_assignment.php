<?php 
include_once('../../config.php');
$sa_uid=$_REQUEST[sa_uid];

$q="UPDATE security_assignment set sa_active='0' where sa_uid='$sa_uid'";
$r=mysql_query($q)or die(mysql_error()."<b>Query</b>: $q <br>");	

echo "success";




/*
$end_date=$_REQUEST[end_date];
$rate=$_REQUEST[rate];
//echo $start_date."<br>";
//echo $end_date."<br>";
echo "Time: ".my_date_diff($start_date,$end_date)." & Total Amount: ".round(my_hour_diff($start_date,$end_date)*$rate,2);
*/
?>