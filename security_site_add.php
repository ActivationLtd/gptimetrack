<?php 
include('config.php');

$valid=true;
$alert=array();
$param=$_REQUEST['param'];
$ss_id=$_REQUEST[ss_id];

if($param=='add'||$param=='edit'){
	if(isset($_POST[submit])){
		$exception_field=array('submit','param','p_ids');
		/*
		*	server side validation
		*/
		if(empty($_POST['ss_name'])){
			$valid=false;
			array_push($alert,"Please give a valid user_type_name");		
		}
		/*************************************/			
		if($valid){ 
			if($param=='add'){
				/*
				*	Check whether current user has permission to add user
				*/
				if(hasPermission('user_type','add',$_SESSION[current_user_id])){
					/*
					*	Create the insert query substring.
					*/
					$str=createMySqlInsertString($_POST,$exception_field);
					$str_k=$str['k'];
					$str_v=$str['v'];
					/*************************************/
					$q="INSERT INTO security_site($str_k) values ($str_v)";
					mysql_query($q) or die(mysql_error()); 
					$ss_id= mysql_insert_id();	
					$param='edit';	
					array_push($alert,"The site has been saved!");	
				}else{
					$valid=false;
					array_push($alert,"You don't have permission to add user");		
				}
			}else if($param=='edit'){
				/*
				*	Check whether current user has permission to edit user
				*/
				if(hasPermission('user_type','edit',$_SESSION[current_user_id])){
					/*
					*	Create the update query substring.
					*/
					$str=createMySqlUpdateString($_REQUEST,$exception_field);
					/*************************************/
					$q="UPDATE security_site set $str where ss_id='$ss_id'";
					//echo $q."<br><br>"; // debug sql 
					mysql_query($q) or die(mysql_error());
					//print_r($_POST[p_ids]);
					array_push($alert,"The site  has been saved!");	
				}else{
					$valid=false;
					array_push($alert,"You don't have permission to edit site");		
				}
			}
			//echo $sql;
		}
	}
}
$q="Select * from security_site where ss_id='$ss_id'";
//echo $q;
$r=mysql_query($q)or die(mysql_error());	
if(mysql_num_rows($r)){$a=mysql_fetch_assoc($r);}


?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html>
<head>
<?php include_once("inc.head.php");?>
</head>
<body>
<div id="wrapper">
  <div id="container">
    <div id="top1">
      <?php include('top.php');?>
    </div>
    <div id="mid">
      <h2><?php echo ucfirst($param);?> Site</h2>
      <div class="alert">
        <?php if(isset($_POST[submit])){printAlert($valid,$alert);} ?>
      </div>
      <div class="right"></div>

      <form action="<?php echo $_SERVER['PHP_SELF'];?>" method="post" enctype="multipart/form-data">
        <input type="hidden" name="param" value="<?php echo $param?>"/>
        <?php if($param!='add'){?>
        <input type="hidden" name="ss_id" value="<?php echo $ss_id?>"/>
        <?php } ?>
        <table width="586" border="0" cellpadding="0" cellspacing="0">
          <tr>
            <td colspan="2" ><strong>Site information</strong></td>
          </tr>
          <tr>
            <td width="129" >Site name: </td>
            <td width="457" ><input name="ss_name" type="text" value="<?php echo addEditInputField('ss_name'); ?>" size="30" maxlength="20" class="validate[required]" /></td>
          </tr>
          
          <tr>
            <td>Address:</td>
            <td><textarea name="ss_address" cols="30" class=""><?php echo addEditInputField('ss_address'); ?></textarea></td>
          </tr>
            
        </table>
        <div class="clear"></div>
        <?php if($param=='edit'||$param=='add'){?>
        <input class="button bgblue" type="submit" name="submit" value="Save" />
        <?php }?>
        <input class="button bgblue" type="button" name="cancel" value="Cancel" onclick="history.go(-1)"/>
      </form>
    </div>
  </div>
  <div id="footer"><?php include('footer.php');?></div>
</div>
</body>
</html>
