<?php
include_once('inc.functions.appspecific.temp.php');

$brands_array=array("Colgate", "Hills", "Adhoc","DEPP Admin", "Others","SEAT","HTC","HP","Grant Thorton","Corsodyl","HSBC","Mecca Bingo", "EPSON", "Brother","Verizon","PM Meeting","Holiday","Internal Time","Leave","Training","Betfair","Pfizer","Nike","Clean & Clear","T.Rowe Price","GMI");
$deliverable_type_array=array("Standard flash banner", "Rich media flash banner", "Static banner", "Edm", "Website", "Others","PM Meeting","Holiday","Internal time","Leave","Training",);
$activity_type_array=array("Localization", "Adaptation", "Localization,Adaptation", "Master Development", "Master Development,Localization,Adaptation", "Other","Depp Administration");
$user_roles_array=array("Sr. Project Manager", "Project Manager", "Team Leader", "Sr. Developer", "Developer","Web Developer -  HTML/Web Developer","Sr. Flash Designer","Flash Designer","Web Developer","Flash Developer", "QA Engineer", "Depp administration", "Others");
$project_billing_type_array=array("Retained", "Non-retained");
$time_activity_type_array=array("Development", "QA", "PM Meeting", "Holiday", "Internal Time", "Leave", "Training","Depp Administration","Waiting Time","Requirement Analysis","Internal Planning","RND","Documentation","Review and communication");
$mail->AddCC("raihan.sikder@adpeople.com","Dev Team");

$defaultPopUpConfirmationMsg="Please confirm the action by checking the box to the left.";

function getUserNameFrmId($user_id){
	$r=mysql_query("select user_name from user where user_id='$user_id'")or die(mysql_error());
	$a=mysql_fetch_assoc($r);
	//echo $a[user_name];
	return $a[user_name];
}

function getUserFullNameFrmId($user_id){
	$r=mysql_query("select user_fullname from user where user_id='$user_id'")or die(mysql_error());
	$a=mysql_fetch_assoc($r);
	//echo $a[user_name];
	return $a[user_fullname];
}
function getUserEmailFrmId($user_id){
	$r=mysql_query("select user_email from user where user_id='$user_id'")or die(mysql_error());
	$a=mysql_fetch_assoc($r);
	//echo $a[user_email]."<br>";
	return $a[user_email];
}
function getUserPhoneFrmId($user_id){
	$r=mysql_query("select user_phone from user where user_id='$user_id'")or die(mysql_error());
	$a=mysql_fetch_assoc($r);
	//echo $a[user_email]."<br>";
	return $a[user_phone];
}
function getClientCompanyNameFrmId($client_id){
	$r=mysql_query("select client_company_name from client where client_id='$client_id'")or die(mysql_error());
	$a=mysql_fetch_assoc($r);
	return $a[client_company_name];
}
function getUserTypeLevel($user_type_id){
	$r=mysql_query("select user_type_level from user_type where user_type_id='$user_type_id'")or die(mysql_error());
	$a=mysql_fetch_assoc($r);
	return $a['user_type_level'];
}
function getUserTypeName($user_type_id){
	$r=mysql_query("select user_type_name from user_type where user_type_id='$user_type_id'")or die(mysql_error());
	$a=mysql_fetch_assoc($r);
	return $a['user_type_name'];
}
function getClientContactNameFrmId($client_id){
	$r=mysql_query("select client_contact_name from client where client_id='$client_id'")or die(mysql_error());
	$a=mysql_fetch_assoc($r);
	return $a[client_contact_name];
}
function getClientEmailFrmId($client_id){
	$r=mysql_query("select client_email from client where client_id='$client_id'")or die(mysql_error());
	$a=mysql_fetch_assoc($r);
	return $a[client_email];
}
function getClientUserIds($client_id){
	$r=mysql_query("select client_user_ids from client where client_id='$client_id'")or die(mysql_error());
	$a=mysql_fetch_assoc($r);
	return $a[client_user_ids];
}
function getProjectNameFrmId($project_id){
	$r=mysql_query("select project_name from project where project_id='$project_id'")or die(mysql_error());
	$a=mysql_fetch_assoc($r);
	return $a[project_name];
}
function getClientNameFrmId($client_id){
	$r=mysql_query("select client_company_name from client where client_id='$client_id'")or die(mysql_error());
	$a=mysql_fetch_assoc($r);
	return $a[client_company_name];
}
function totalPaymentAmount($sa_id){ //$sa_id : security assignment id
	$r=mysql_query("select * from security_assignment where sa_id='$sa_id'")or die(mysql_error());
	$a=mysql_fetch_assoc($r);
	$total_hour=my_hour_diff($a[sa_start_time],$a[sa_end_time]);
	return round($total_hour*$a[sa_rate],2);

}
function getClientIdsFromUserId($user_id){ // returns an array with client_ids
	$r=mysql_query("select client_id,client_user_ids from client")or die(mysql_error());
	$client_ids_array= array();
	if(mysql_num_rows($r)){
		$a=mysql_fetch_rowsarr($r);
		foreach($a as $client){
			if(in_array($user_id,explode(',',trim($client['client_user_ids'],', ')))){
				array_push($client_ids_array,$client['client_id']);
			}
		}
	}
	return $client_ids_array;
}
/*
 *	returns corresponding user_id if matching e-mail is found. else returns false;
*/
function emailIsAlreadyTaken($emailAddress){
	$q="select user_id from user where user_email='$emailAddress'";
	$r=mysql_query($q)or die(mysql_error());
	if(mysql_num_rows($r)){
		$a=mysql_fetch_assoc($r);
		return $a['user_id'];
	}
	else return false;
}
function usernameIsAlreadyTaken($userName){
	$q="select user_id from user where user_name='$userName'";
	$r=mysql_query($q)or die(mysql_error());
	if(mysql_num_rows($r)){
		$a=mysql_fetch_assoc($r);
		return $a['user_id'];
	}
	else return false;
}
function userTypeNameIsAlreadyTaken($userTypeName){
	$q="select user_type_id from user_type where user_type_name='$userTypeName'";
	$r=mysql_query($q)or die(mysql_error());
	if(mysql_num_rows($r)){
		$a=mysql_fetch_assoc($r);
		return $a['user_type_id'];
	}
	else return false;
}
/*
 *	Costsheet permission functions. CAREFULLY PUT MODULE NAME DONT TOUCH THE MODULE TABLE
*	returns true if the function has permission
*/
function hasPermission($module_system_name,$action,$user_id){
	/* then get the user_type_ids from permission table that matches teh module/action*/
	$p_user_type_ids=userTypeIdsPermittedForAction($module_system_name,$action);
	//echo $p_user_type_ids;

	if(strlen($p_user_type_ids)){
		/* first get the user_typ_id*/
		$q="select * from user where user_id='$user_id' and user_type_id in($p_user_type_ids)";
		$r=mysql_query($q)or die(mysql_error());
		if(mysql_num_rows($r))return true;
		else return false;
	}else{
		return false;
	}
}

/*
 *
*/
function addUserTypeIdInPermission($p_id,$new_user_type_id){
	$existing_utids_csv=userTypeIdsFromPId($p_id);
	$existing_utids_array=explode(',',$existing_utids_csv);
	if(!in_array($new_user_type_id,$existing_utids_array)){
		array_push($existing_utids_array,$new_user_type_id);	// if not then value is added to array
		sort($existing_utids_array);
		$new_utids_csv=trim(implode(',',$existing_utids_array),', ');
		$q="UPDATE permission
		SET p_user_type_ids='$new_utids_csv'
		Where p_id='$p_id'";
		$r=mysql_query($q)or die(mysql_error());
	}
}
function removeUserTypeIdInPermission($p_id,$user_type_id_to_remove){
	$existing_utids_csv=userTypeIdsFromPId($p_id);
	$existing_utids_array=explode(',',$existing_utids_csv);
	if(in_array($user_type_id_to_remove,$existing_utids_array)){
		$new_utids_csv=trim(str_replace(",$user_type_id_to_remove,","",",$existing_utids_csv,"),', ');
		$q="UPDATE permission
		SET p_user_type_ids='$new_utids_csv'
		Where p_id='$p_id'";
		$r=mysql_query($q)or die(mysql_error());
	}
}

/***********************************************************/
/*
 *	Inserts uer_type_id in 'p_user_type_ids' field of each p_id
*
*/
function updatePermissionTable($p_ids_array,$user_type_id){
	$q="SELECT * FROM permission";
	$r=mysql_query($q)or die(mysql_error());
	if(mysql_num_rows($r)){
		$pa=mysql_fetch_rowsarr($r);
		foreach($pa as $a){
			if(count($p_ids_array)>0){
				if(in_array($a[p_id],$p_ids_array)){
					addUserTypeIdInPermission($a[p_id],$user_type_id);
				}
				else{
					removeUserTypeIdInPermission($a[p_id],$user_type_id);
				}
			}
			else{
				removeUserTypeIdInPermission($a[p_id],$user_type_id);
			}
		}
	}
}

/***********************************************************/
/*
*	returns a string of CSV values of user_type_ids matching 'module_system_name' and 'action'
*/

function userTypeIdsPermittedForAction($module_system_name,$action){
	$q="select p_user_type_ids from permission where p_module_system_name='$module_system_name' and p_action='$action'";
	$r=mysql_query($q)or die(mysql_error());
	$a=mysql_fetch_assoc($r);
	return trim($a['p_user_type_ids'],', ');
}
/***********************************************************/

/*
*	returns a string of CSV values of user_type_ids
*/
function userTypeIdsFromPId($p_id){
	$q="select p_user_type_ids from permission where p_id='$p_id'";
	$r=mysql_query($q)or die(mysql_error());
	$a=mysql_fetch_assoc($r);
	return trim($a['p_user_type_ids'],', ');
}

function currentUserLevel(){
	return $_SESSION[current_user_type_level];
}
function currentUserTypeId(){
	return $_SESSION[current_user_type_id];
}
function newerSecurityAssignmentExists($sa_id){
	$q="select sa_uid,sa_insert_time from security_assignment where sa_id='$sa_id'";
	$r=mysql_query($q)or die(mysql_error());
	$a_sa_1=mysql_fetch_assoc($r);

	$q="select sa_insert_time from security_assignment where sa_uid='".$a_sa_1[sa_uid]."' order by sa_insert_time desc limit 0,1";
	$r=mysql_query($q)or die(mysql_error());
	$a_sa_2=mysql_fetch_assoc($r);

	//echo $a_sa_1[sa_insert_time]."<br>";
	//echo $a_sa_2[sa_insert_time]."<br>";

	if($a_sa_1[sa_insert_time]!=$a_sa_2[sa_insert_time]){
		return true;
	}else return false;
}

function notifySecurityAssignmentAdd($sa_id){
	global $mail;
	global $scriptpath;
	global $sendEmail;
	global $sendSMS;

	if($sendEmail){
		$q="select * from security_assignment where sa_id='$sa_id'";
		$r=mysql_query($q)or die(mysql_error());
		$a_sa=mysql_fetch_assoc($r);
		/*
		*	Send E-mail
		*/
		$mail->AddAddress(getUserEmailFrmId($a_sa[sa_guard_user_id]),getUserNameFrmId($a_sa[sa_guard_user_id]));
		$Subject = "TESecurity Security Assignment added/updated";
		$lb='<br>';
		$Body =
					"<span style='font-family:Courier New, Courier, monospace; font-size:12px'>".
					"Assignment Information $lb".
					"========================== $lb".
					"Start date: 		".date('d-M-Y',strtotime($a_sa[sa_start_time]))."$lb".
					"Requested by:		".getUserNameFrmId($a_sa[sa_requested_by_user_id])."$lb".
					"Company:			".getClientCompanyNameFrmId($a_sa[sa_client_id])."$lb".
					"Start time:		".date('H:i',strtotime($a_sa[sa_start_time]))."$lb".
					"End time:		 	".date('H:i',strtotime($a_sa[sa_end_time]))."$lb".
					"Guard: 			".getUserNameFrmId($a_sa[sa_guard_user_id])."$lb".
					//"Rate(Per hour): 	".$a_sa[sa_rate]."$lb".
					"Total Hours: 		".my_hour_diff($a_sa[sa_start_time],$a_sa[sa_end_time])."$lb".
					//"Total Amount: 		".round(my_hour_diff($a_sa[sa_start_time],$a_sa[sa_end_time])*$arr[$i][sa_rate],2)."$lb".
					"Site: 				".getSiteNameFrmId($a_sa[sa_site_id])."$lb".
					"==========================$lb".
					"This is a system generated e-mail from SmartSecurity Guard Management system. Donot reply to this e-mail. If you have any query please contact:$lb ".
					"Shaz Mohd"."$lb".
					"Time Efficient Services Pty Ltd" ."$lb".
					"Trading as True Elegant Security" ."$lb".
					"Director and Chief Operations Officer (COO)" ."$lb".
					"Phone: 61 2 8580 0118" ."$lb".
					"Mobile: 0425 304 932" ."$lb".
					"Rockdale:117 Railway St," ."$lb".
					"Rockdale NSW 2216(Next to Rockdale Train Station)" ."$lb".
					"website: www.tesecurity.com.au" ."$lb".
					"==========================$lb".
					"</span>"
					;      //HTML Body
		$mail->Subject = $Subject;
		$mail->Body = $Body;
		if(!$mail->Send()){
			echo "Mailer Error: " . $mail->ErrorInfo;}
		else{

			/*******   Send sms    *******/
			$lb="\n";
			$text=
			"TESecurity UPDATE"."$lb".
			"Date:".date('d-M-Y',strtotime($a_sa[sa_start_time]))."$lb".
			//"Requested by:".getUserNameFrmId($a_sa[sa_requested_by_user_id])."$lb".
			//"Company:".getUserNameFrmId($a_sa[sa_requested_by_user_id])."$lb".
			"Start:".date('H:i',strtotime($a_sa[sa_start_time]))."$lb".
			"End:".date('H:i',strtotime($a_sa[sa_end_time]))."$lb".
			"Guard:".getUserNameFrmId($a_sa[sa_guard_user_id])."$lb".
			//"Rate(Per hour): 	".$a_sa[sa_rate]."$lb".
			"Hours:".my_hour_diff($a_sa[sa_start_time],$a_sa[sa_end_time])."$lb".
			//"Total Amount: 		".round(my_hour_diff($a_sa[sa_start_time],$a_sa[sa_end_time])*$arr[$i][sa_rate],2)."$lb".
			"Site:".getSiteNameFrmId($a_sa[sa_site_id])."$lb";
			$to=getUserPhoneFrmId($a_sa[sa_guard_user_id]);
			if($sendSMS 				// checks whether SMS flag is enabled in inc.globalvariables.php
				&& 
				strlen(trim($to))>6){	// checks whether telephone number lenght is <6. Not a good approach though for checking.
					sendSms($text,$to);
			}
			/******************************************/
		}
	}

}

function sendSms($text,$to){
	global $clickatell_user;
	global $clickatell_password;
	global $clickatell_api_id;
	global $clickatell_baseurl;
	global $clickatell_senderID;
    //$text = urlencode("This is an example message");

    // auth call
    $url = "$clickatell_baseurl/http/auth?user=$clickatell_user&password=$clickatell_password&api_id=$clickatell_api_id";

    // do auth call
    $ret = file($url);

    // explode our response. return string is on first line of the data returned
    $sess = explode(":",$ret[0]);
    if ($sess[0] == "OK") {

        $sess_id = trim($sess[1]); // remove any whitespace
        $url = "$clickatell_baseurl/http/sendmsg?session_id=$sess_id&from=$clickatell_senderID&to=$to&text=".urlencode($text);

        // do sendmsg call
        $ret = file($url);
        $send = explode(":",$ret[0]);

        if ($send[0] == "ID") {
            //echo "successnmessage ID: ". $send[1];
        } else {
            echo "send message failed";
        }
    } else {
        echo "Authentication failure: ". $ret[0];
    }

}

function getGuardsTotalHour($user_id){
	$q="select * from security_assignment s1
		where sa_guard_user_id='$user_id' AND
		sa_active='1' AND
		sa_insert_time=
			(select max(s2.sa_insert_time) from security_assignment s2
				where s1.sa_uid=s2.sa_uid)
		";
	
	$r=mysql_query($q)or die(mysql_error()."<br><b>Query:</b> $q<br>");

	$rows=mysql_num_rows($r);
	if($rows>0){
		$arr=mysql_fetch_rowsarr($r);
		$totalHour=0;
		for($i=0;$i<$rows;$i++){
			$totalHour+=round(my_hour_diff($arr[$i][sa_start_time],$arr[$i][sa_end_time]),2);
		}
		return $totalHour;
	}else return 0;


}

function emailCurrentFullShiftDetails($xDays){
	global $scriptpath;
	$Body="";
	for($d=0;$d<=$xDays;$d++){
		//$dateToday=date("Y-m-d");
		$targetDate=date("Y-m-d",mktime(0,0,0,date("m"),date("d")+$d,date("Y")));
		$targetNextDate=date("Y-m-d",mktime(0,0,0,date("m"),date("d")+$d+1,date("Y")));
		$q_filtered = "select * from security_assignment s1
		where
		sa_active='1' AND sa_start_time>='$targetDate' AND  sa_start_time<'$targetNextDate' AND
		sa_insert_time=
			(select max(s2.sa_insert_time) from security_assignment s2
				where s1.sa_uid=s2.sa_uid)
		ORDER BY sa_site_id ASC		
		 ";
		
		
		echo "<br><b>Query:</b> $q_filtered<br>";
		$r = mysql_query($q_filtered) or die(mysql_error()."<br><b>Query:</b> $q_filtered<br>");
		$rows = mysql_num_rows($r);
		if ($rows > 0) {
		$arr = mysql_fetch_rowsarr($r);
		}
		$dateFormat="d-M-Y";
		
		$Body.="<h1>Security job for ".date('d-M-Y (l)',strtotime($targetDate))."</h1>";
		$Body.="<table border='1' style='font-family:Verdana, Geneva, sans-serif; font-size:11px'>
			<tr style='font-weight:bold'>
			  <td>Week</td>
			  <td>Start_date</td>
			  <td>Day</td>
			  <td>Start</td>
			  <td>End</td>
			  <td>Requested by</td>
			  <td>Company /Client</td>
			  <td>Site</td>
			  <td>Guard</td>
			  <td>Rate</td>
			  <td>Hours</td>
			  <td>Amount</td>
			  <td>Comment</td>
			  <td>Action</td>
			</tr>
		  ";
		
			$total_hours=0;
			$total_amount=0;
			for ($i = 0; $i < $rows; $i++) {
				$Body.= "<tr>";
				$Body.= "<td><b>W" . date("W", strtotime($arr[$i][sa_start_time])) . "</b></td>";
				$Body.= "<td>".date('d-M-Y', strtotime($arr[$i][sa_start_time]))."</td>";
				$Body.= "<td>".date('l', strtotime($arr[$i][sa_start_time]))."</td>";
				$Body.= "<td class='greenText'>".date('H:i', strtotime($arr[$i][sa_start_time]))."</td>";
				$Body.= "<td class='orangeText'>".date('H:i', strtotime($arr[$i][sa_end_time]))."</td>";
				$Body.= "<td>".getUserFullNameFrmId($arr[$i][sa_requested_by_user_id])."</td>";
				$Body.= "<td>".getClientCompanyNameFrmId($arr[$i][sa_client_id])."</td>";
				$Body.= "<td>".getSiteNameFrmId($arr[$i][sa_site_id])."</td>";
				$Body.= "<td>".getUserFullNameFrmId($arr[$i][sa_guard_user_id])."</td>";
				$Body.= "<td>".$arr[$i][sa_rate]."</td>";
				$hour= round(my_hour_diff($arr[$i][sa_start_time], $arr[$i][sa_end_time]), 2);
				  $total_hours+=$hour;
				$Body.=  "<td>".$hour."</td>";
				$amount = round(my_hour_diff($arr[$i][sa_start_time], $arr[$i][sa_end_time]) * $arr[$i][sa_rate], 2);
				$total_amount+=$amount;
				$Body.= "<td>".$amount."</td>";
				$Body.= "<td>- ".$arr[$i][sa_comment]."</td>";
				$Body.= "<td><span style='width:115px; float:right;'>";
		
					$Body.= "<a href='$scriptpath/security_assignment_add.php?sa_id=".$arr[$i][sa_id]."&param=edit' class='none'>Edit</a> | ";
		
					$Body.= "<a href='$scriptpath/security_assignment_add.php?sa_id=".$arr[$i][sa_id]."&param=view'>View</a> | ";
					$Body.= "<a href='$scriptpath/snippets/security_assignment/ajax_notify.php?sa_id=".$arr[$i][sa_id]."'>Notify</a>";
				$Body.= "</span></td></tr>";
			}
			$Body.="</table>";
	}
	echo $Body;

	global $mail;
	$mail->AddAddress('raihan.act@gmail.com','Raihan');
	$mail->AddAddress('tesops@gmail.com','Tes Operations');
	$mail->AddAddress('tesriya@gmail.com','TES Riya');
	$Subject = "[Auto-email] SmartSecurity Roster update at - ".date("F j, Y, g:i a");
	$mail->Subject = $Subject;
	$mail->Body = $Body;

	
	if(!$mail->Send()){
		echo "Mailer Error: " . $mail->ErrorInfo;
	}else{
		echo "Mail Sent!";
	}
	
}

function currentUserIsGeneralUser(){
	if($_SESSION[current_user_type_id]=='3')return true;
	else return false;
}

function insertLog($log_module, $log_event, $log_affected_table_name, $log_affected_table_primary_key_field, $log_affected_table_primary_key_value,$log_sql_query_string,$log_event_user_id,$log_information){
	$sql="
	INSERT INTO log(
	log_module,
	log_event,
	log_affected_table_name,
	log_affected_table_primary_key_field,
	log_affected_table_primary_key_value,
	log_sql_query_string,
	log_event_user_id,
	log_datetime,
	log_information
	)value(
	'".mysql_real_escape_string($log_module)."',
	'".mysql_real_escape_string($log_event)."',
	'".mysql_real_escape_string($log_affected_table_name)."',
	'".mysql_real_escape_string($log_affected_table_primary_key_field)."',
	'".mysql_real_escape_string($log_affected_table_primary_key_value)."',
	'".mysql_real_escape_string($log_sql_query_string)."',
	'".mysql_real_escape_string($log_event_user_id)."',
	now(),
	'".mysql_real_escape_string($log_information)."'
	)
	";
	//echo $sql;
	$r=mysql_query($sql)or die(mysql_error()."<br>Query:<br>____<br>$sql<br>");
}

function getActiveStatus($val){
	if($val=='1')echo "<span class='active'>Active</span>";
	else if($val=='0')echo "<span class='inactive'>Inactive</span>";
}

function createDropdown($arr, $frm) {
    echo '<select name="' . $frm . '" id="' . $frm . '">'; //<option value="">Select one…</option>';
    foreach ($arr as $key => $value) {
        if ($value == $_REQUEST[month]) {
            echo '<option selected value="' . $value . '">' . $value . '</option>';
        } else {
            echo '<option value="' . $value . '">' . $value . '</option>';
        }
    }
    echo '</select>';
}

function createProjectSelectOptions($dbtableName,$dbtableIdField,$dbtableValueField,$customQuery,$selectedId,$name,$params){
	$sql="SELECT * FROM $dbtableName 
		$customQuery 
		ORDER BY project_client_id ASC";
	//echo $q;
	$r=mysql_query($sql)or die(mysql_error()."<br>Query:<br>____<br>$sql<br>");
	
	if(mysql_num_rows($r)){
		$a=mysql_fetch_rowsarr($r);
		echo "<select name='$name' $params>";
		echo "<option value=''>select</option>";
		foreach($a as $b){
			$client_name=getClientNameFrmId($b['project_client_id']);
			echo "<option value='".$b[$dbtableIdField]."' ";
			if($b[$dbtableIdField]==$selectedId){echo " selected='selected' ";}
			echo " >".$client_name." - [".$b[project_id]."] ".$b[$dbtableValueField]."</option>";
		}
		echo "</select>";
	}
}

function countProjectUtilizationHoursFrmProjectId($project_id){
  $sql = "SELECT SUM(time_total) FROM time WHERE time_project_id='$project_id'";
  $r = mysql_query($sql)or die(mysql_error()."<b>Query:</b><br>___<br>$sql<br>");
  $a = mysql_fetch_assoc($r);
  //echo "SUM:".$a['SUM(time_total)'];
  return round($a['SUM(time_total)'],2);
  
}

?>
