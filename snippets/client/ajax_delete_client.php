<?php 
include_once('../../config.php');
$client_id=$_REQUEST[client_id];

$q="UPDATE client set client_active='0' where client_id='$client_id'";
$r=mysql_query($q)or die(mysql_error()."<b>Query</b>: $q <br>");	

echo "success";
?>