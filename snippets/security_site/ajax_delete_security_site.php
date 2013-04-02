<?php 
/*
 * Author               : Nasir Khan
 * email                : nasir.khan@activationltd.com
 * updated on           : 05-08-2012
 * codeblock type       : function/procedure
 * Name                 : Update the Security Site status
 * parameter(s)         : N/A
 * Output               : send success status
 * Developers Note      : 
 */
include_once('../../config.php');
$ss_id=$_REQUEST[ss_id];

$q="UPDATE security_site set ss_active='0' where ss_id='$ss_id'";
$r=mysql_query($q)or die(mysql_error()."<b>Query</b>: $q <br>");	

echo "success";

?>