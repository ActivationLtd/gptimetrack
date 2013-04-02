<?php 
include_once('../../config.php');
$sa_id=$_REQUEST[sa_id];
if(strlen($sa_id)){
	notifySecurityAssignmentAdd($sa_id);
	
}
?>