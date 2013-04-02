<?php
/*
*	Basic client configuration
*/
$app_name = "Smart Security";
$app_version = "v1.0";
/*
*	Basic client configuration
*/
$company_name = "Tesecurity";
$company_slogan = "Best security provider";
$company_intro = "";
$sitename="Smart Security";
/***********************************/
/*
*	Basic site setup
*/
//$scriptpath='http://localhost/activation/smartsecurity/layouts/site'; //office testing script path
$scriptpath='http://174.120.107.7/~smartsec/tesecurity'; //office testing script path without '/' at the end
/***********************************/

/*
*	Database information and connection
*/
$dbhost='localhost';		
$dbuser='smartsec_tes';
$dbpass='activation';
$dbname='smartsec_tesecurity';
mysql_select_db($dbname,mysql_connect($dbhost, $dbuser, $dbpass));
/***********************************/
/*
*	file upload parameters
*/
$max_upload_img_size=10000000; // maximum image upload size in kb
$img_upload_types=array("images/jpeg","images/gif","images/pjpeg","images/jpg","images/png");
/***********************************/
$month_arr=array('month','jan','feb','mar','apr','may','jun','july','aug','sep','oct','nov','dec');
?>