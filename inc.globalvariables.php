<?php
error_reporting(E_ERROR | E_WARNING | E_PARSE );
/*
*	Basic client configuration
*/
$app_name = "gptimetracker";
$app_version = "v1.0";
/*
*	Basic client configuration
*/
$company_name = "GraphicPeople";
$company_slogan = "";
$company_intro = "";
$sitename="gptimetracker";
/***********************************/
/*
*	Basic site setup
*/
//$scriptpath='http://localhost/graphicpeople/site'; //office testing script path
$scriptpath='http://174.120.107.7/~gptimetrack'; //office testing script path without '/' at the end
/***********************************/

/*
*	Database information and connection
*/
// Local

$dbhost='localhost';		
$dbuser='gptimetr';
$dbpass='graphicpeople';
$dbname='gptimetr_db1';

// live
/*
$dbhost='localhost';		
$dbuser='smartsec';
$dbpass='activation';
$dbname='smartsec_demo1';
*/  
mysql_select_db($dbname,mysql_connect($dbhost, $dbuser, $dbpass));
/***********************************/
/*
*	file upload parameters
*/
$max_upload_img_size=10000000; // maximum image upload size in kb
$img_upload_types=array("images/jpeg","images/gif","images/pjpeg","images/jpg","images/png");
/***********************************/
$month_arr=array('month','jan','feb','mar','apr','may','jun','july','aug','sep','oct','nov','dec');
/*
$sendNotification=true;
$sendEmail=true;
$sendSMS=true;
*/
/*
* initiate PHPMailer
*/
require_once("phpmailer/class.phpmailer.php");
$mail = new PHPMailer();
$mail->IsSMTP(); // send via SMTP
$mail->Host = "ssl://smtp.gmail.com";
$mail->Port = 465;
$mail->SMTPAuth = true; // turn on SMTP authentication
$mail->Username = "tes.smartsec@gmail.com"; // SMTP username
$mail->Password = "activation"; // SMTP password
//$webmaster_email = "username@doamin.com"; //Reply to this email ID
//$email="spider.xy@gmail.com"; // Recipients email ID
//$name="name"; // Recipient's name	
$mail->From = "tes.smartsec@gmail.com";
$mail->FromName = "Tesecurity";	
$mail->AddReplyTo($_SESSION[current_user_email],$_SESSION[current_user_fullname]);
$mail->AddCC("tesshaz@gmail.com","Tes Shaz");
$mail->AddCC("raihan.act@gmail.com","Raihan");
$mail->WordWrap = 50; // set word wrap
//$mail->AddAttachment("D:/a.txt"); // attachment
//$mail->AddAttachment("/tmp/image.jpg", "new.jpg"); // attachment
$mail->IsHTML(true); // send as HTML
/***********************************************/

/*
*		Clickatell information
******************************/
$clickatell_user = "akmzia";
$clickatell_password = "VZdgt058";
$clickatell_api_id = "3135116";
$clickatell_baseurl ="http://api.clickatell.com"; 
$clickatell_senderID="TESecurity";
/********************************/
?>
