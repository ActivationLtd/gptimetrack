<?php 
include('config.php');
//echo $_SESSION['redirect_url'];
$valid=true;
$alert=array();
if(isset($_POST[Submit])){
	$user_name=mysql_real_escape_string(trim($_POST[user_name]));
	$user_password=mysql_real_escape_string(trim($_POST[user_password]));	
	
	if(empty($user_name)||empty($user_password)){
		$valid=false;
		array_push($alert,"You cannot leave username or password field empty");
	}

	if($valid){
		$q="select * from user where (user_name='$user_name' && user_password='$user_password') OR (user_email='$user_name' && user_password='$user_password') and user_active='1'";
		$r=mysql_query($q)or die(mysql_error());
		if(mysql_num_rows($r)>0){
			$a=mysql_fetch_assoc($r);
			/*
			*  load user info in SESSION
			*/
			$_SESSION[current_user_id]=$a[user_id];
			$_SESSION[current_user_name]=$a[user_name];
			$_SESSION[current_user_fullname]=$a[user_fullname];
			$_SESSION[current_user_type_id]=$a[user_type_id];
			//$_SESSION[current_user_type_id]=$a[user_type_id];
			$_SESSION[current_user_type_level]=getUserTypeLevel($a[user_type_id]);
			$_SESSION[current_user_email]=$a[user_email];
			$_SESSION[logged]=true;			
			//$_SESSION[$company_name]=$company_name;
			//echo $a[user_type_id];
			/*****************************/
			/*
			* update user login in database
			*/

			$q="UPDATE user set user_logged='1' where user_id='".$_SESSION[current_user_id]."'";
			$r=mysql_query($q)or die(mysql_error());
		}else{
			$valid=false;
			array_push($alert,"username/password missmatch");
		}
	}	
	if($_SESSION[logged]==true){
		if(strlen($_SESSION['redirect_url'])){
			header("location:".$_SESSION['redirect_url']);
		}else{
			header("location:time_list.php");
		}
	}	
}
?>

<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html>
<head>
<?php include_once('inc.head.php')?>
<style>
.containerLogin{margin: 0 auto; padding: 250px 0 100px; width: 660px;}
input{float:none; width:300px; height:35px;font-size:16px;}
input.button{float:none;}
.formError .formErrorContent, .formError .formErrorArrow div {background-color:#000000;}
.loginSubmit[type="submit"] {
    background: url("images/btn-login.png") no-repeat scroll left top transparent;
    border: medium none;
    height: 39px;
    width: 85px;
		text-indent: -999px;
}
.loginSubmit[type=submit]:hover { background: url(images/btn-login.png) left bottom no-repeat; }
</style>
</head>

<body>
<div id="wrapper">
  <div class="containerLogin">      
      <form action="" method="post" name="admin_login_form">        
          <img  src="images/team-hogarth-logo.png"/>          
          <table width="100%" class="login" style="float:none;">            
            <tr>              
                <td align="center">
                	<!--<h2>User Login</h2>-->
                  <div class="alert"><?php if(isset($_POST[Submit])){printAlert($valid,$alert);}?></div>
                  <!--Please input your username and password to access they system.<br>-->
                	<input type="text" name="user_name" class="validate[required]"  placeholder="Username"/>
                </td>
              </tr>
              <tr>             
                <td align="center"><input type="password" name="user_password" class="validate[required]"  placeholder="Password"/></td>
              </tr>
              <tr>             
                <td align="center"><input class="loginSubmit" type="submit" name="Submit" value="Submit" /></td>
              </tr>
            </table>          
       
      </form>
    </div>
  </div>
  <div id="footer"><?php include('footer.php');?></div>
</div>
</body>
</html>
