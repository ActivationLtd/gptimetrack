<?php 
include_once('../../config.php');
$user_id=$_REQUEST[user_id];

$q="UPDATE user set user_active='0' where user_id='$user_id'";
$r=mysql_query($q)or die(mysql_error()."<b>Query</b>: $q <br>");	

echo "success";
?>