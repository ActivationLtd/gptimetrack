<?php
error_reporting(E_ERROR | E_WARNING | E_PARSE );
session_start();
@date_default_timezone_set('America/New York');


include_once('inc.globalvariables.php');
include_once('inc.functions.generic.php');
include_once('inc.functions.appspecific.php');
/*

*	Following code segment deal with users landing to the system using urls with parameters. 

	The url is stored in session and after login user is redirected to the page where he intended to land.

*/

if(getFileName()!='login.php'){	
	if($_SESSION['logged']!=true){		
		session_destroy();
		session_start();
		$str_k="";
		$exception_field=array('');
		foreach($_REQUEST as $k=>$v){
			if(!in_array($k,$exception_field)){
				if(!empty($k)){
					$str_k.="$k=".$v.'&';
				}
			}
		}
		$str_k=trim($str_k,'&');
		$_SESSION['redirect_url']=getFileName().'?'.$str_k;		
		header("location:login.php");
	}
	/* Debug */

	//echo "session:".$_SESSION[$company_name]."<br>";

	//echo "variable:".$company_name."<br>";	

	/*********/
}else{
	if($_SESSION['logged']==true){
		header("location:index.php");
	}
}



/****************************************************************

* PHPExcel included 

****************************************************************/

//ini_set('include_path', ini_get('include_path').';../Classes/');

/** PHPExcel */

//include_once('PHPExcel/Classes/PHPExcel.php');

/** PHPExcel_Writer_Excel2007 */

//include_once('PHPExcel/Classes/PHPExcel/Writer/Excel2007.php');

// Create new PHPExcel object



?>
