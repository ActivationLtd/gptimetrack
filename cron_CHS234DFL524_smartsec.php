<?php
include_once('inc.globalvariables.php');
include_once('inc.functions.generic.php');
include_once('inc.functions.appspecific.php');

/*
*  cleanUpTempDir(X) : deletes 
*/
/*
if($sendNotification){
	echo "Notification disabled.<br>";
	$not=" not";
}else{
	echo "Notification Enabled.<br>";
	$not="";
}
$q="SELECT * FROM security_assignment_notification WHERE san_notified='No' AND san_active='1'";
$r=mysql_query($q)or die(mysql_error()."<b>Query:</b><br> $q");
$rows=mysql_num_rows($r);
echo "Total $rows notification(s) to be sent.<br>";
if($rows>0){
	$arr=mysql_fetch_rowsarr($r);
	foreach($arr as $tmp){
		if($sendNotification){
			notifySecurityAssignmentAdd($tmp["san_sa_id"]);
		}
		$q="UPDATE security_assignment_notification set san_notified='Yes' WHERE san_id='".$tmp["san_id"]."'";
		$r=mysql_query($q)or die(mysql_error()."<b>Query:</b><br> $q");
		echo "Notification".$not." sent for sa_id: ".$tmp["san_id"]."<br>";
	}
}
*/
emailCurrentFullShiftDetails(2);	

//session_destroy();
?>
