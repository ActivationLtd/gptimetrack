<?php 
include('config.php');
$valid=true;
$alert=array();
$client_id=$_REQUEST[client_id];
$param=$_REQUEST[param];
$sa_id=$_REQUEST[sa_id];

//myprint_r($_REQUEST);

$exception_field=array('submit','param');

if($param=='add' || $param=='edit'){
	if(isset($_POST[submit])){
		/*
		* Server side validation 
		*/
		
		/******************************************************/
		/*
		* If data is valid then data is stored in the database 
		*/
		if($valid){
			/*
			* Capture form data to create a query string
			*/
			$str=createMySqlInsertString($_POST, $exception_field);
			/******************************************************/			
			$str_k=$str['k'];
			$str_v=$str['v'];
			if($param=='add'){
				$sa_uid=makeRandomKey();
				$sql="INSERT INTO security_assignment($str_k,sa_insert_time,sa_insert_user_id,sa_uid) values($str_v,now(),'".$_SESSION[current_user_id]."','$sa_uid')";
			}else if($param=='edit'){
				$sql="INSERT INTO security_assignment($str_k,sa_insert_time,sa_insert_user_id) values($str_v,now(),'".$_SESSION[current_user_id]."')";
			}
			//echo $sql;
			mysql_query($sql) or die(mysql_error()); 
			$sa_id=mysql_insert_id();	
			//notifySecurityAssignmentAdd($sa_id);		
			array_push($alert,"The security assignment is registered successfully!");
			$param="edit";
			header("location:security_assignment_list.php?added_sa_id=$sa_id&sa_client_id[]=".$_REQUEST["sa_client_id"]);

		}
	}
}
$r=mysql_query("Select * from security_assignment where sa_id='$sa_id'")or die(mysql_error());	
$a=mysql_fetch_assoc($r);

if(newerSecurityAssignmentExists($sa_id)){
	$valid=false;
	array_push($alert,"This assignment has been updated. Please check the newer version.");		
}

?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html>
<head>
<?php //include_once("inc.head.php");?>
<title><?php echo $sitename?></title>
<script src="js/jquery.js" type="text/javascript"></script>
<script src="js/jquery-ui.js" type="text/javascript"></script>
<link href="css/jquery-demos.css" rel="stylesheet" type="text/css" />
<link rel="stylesheet" href="js/themes/base/jquery.ui.all.css">
<link href="css/template_css.css" rel="stylesheet" type="text/css" />
<link href="css/validationEngine.jquery.css" rel="stylesheet" type="text/css"/>
<script src="js/jquery.validationEngine-en.js" type="text/javascript" charset="utf-8"></script>
<script src="js/jquery.validationEngine.js" type="text/javascript" charset="utf-8"></script>
<link href="js/datatable/css/demo_table.css" media="screen" rel="stylesheet" type="text/css"/>
<script src="js/datatable/js/jquery.dataTables.js"type="text/javascript" ></script>
<script src="js/ui/jquery.ui.core.js" type="text/javascript"></script>
<script src="js/ui/jquery.ui.widget.js" type="text/javascript"></script>
<script src="js/ui/jquery.ui.position.js" type="text/javascript"></script>
<script src="js/ui/jquery.ui.autocomplete.js" type="text/javascript"></script>
<script src="js/ui/jquery.ui.slider.js" type="text/javascript"></script>
<script src="js/ui/jquery-ui-timepicker-addon.js" type="text/javascript"></script>
<!--Selectmenu-->
<script src="js/ui/jquery.ui.selectmenu.js" type="text/javascript"></script>
<link href="js/themes/base/jquery.ui.selectmenu.css" rel="stylesheet" type="text/css"/>

<!--CKEditor-->
<script src="ckeditor/ckeditor.js" type="text/javascript" ></script>
<script src="ckeditor/_samples/sample.js" type="text/javascript"></script>

<!-- Facebox script -->

<script src="facebox/facebox.js" type="text/javascript"></script>
<link href="facebox/facebox.css" media="screen" rel="stylesheet" type="text/css"/>
<!-- -->




<script src="js/jquery.autogrow.js" type="text/javascript" ></script>

<!-- Multiple select -->
<!-- <link type="text/css" href="multiple_select/css/ui.all.css" rel="stylesheet" /> -->
<link type="text/css" href="multiple_select/css/ui.multiselect.css" rel="stylesheet" />

<!--<script type="text/javascript" src="multiple_select/js/plugins/localisation/jquery.localisation-min.js"></script>
<script type="text/javascript" src="multiple_select/js/plugins/scrollTo/jquery.scrollTo-min.js"></script> -->
<script type="text/javascript" src="multiple_select/js/ui.multiselect.js"></script>

<!--Google font CSS for Ubuntu-->
<!--<link href='http://fonts.googleapis.com/css?family=Ubuntu' rel='stylesheet' type='text/css'>
-->

</head>
<body>
<div id="wrapper">
  <div id="container">
    <div id="top1">
      <?php //include('top.php');?>
    </div>
    <div id="mid">
    <div id="client_menu"><?php //include('snippets/security_assignment/security_assignment_menu.php');?></div>
      <h2>Security Assignment - <?php echo ucfirst($param);?></h2>
      
      <div class="alert"><?php printAlert($valid,$alert);?></div>
      <div class="right">
      	<?php 
		if(hasPermission('security_assignment','edit',$_SESSION[current_user_id])){ 
			if($param=='view'){
			echo "<a href='security_assignment_add.php?sa_id=$sa_id&param=edit'>[Edit Assignment]</a>" ;
			}
		}
		?>          
      </div>
      <form action="<?php echo $_SERVER['PHP_SELF']; ?>" method="post" enctype="multipart/form-data">
        <?php if($param!='add'){?>
        <input type="hidden" name="sa_uid" value="<?php echo addEditInputField('sa_uid');?>">
        <?php } ?>
        <input type="hidden" name="param" value="<?php echo $param;?>" />
        <div class="clear"></div>
        <table id="list" width="100%" border="0" cellpadding="0" cellspacing="0">
          <tr>
            <td width="18%" >Requested by :</td>
            <td width="30%" >
            <?php 
			$selectedId=addEditInputField('sa_requested_by_user_id');
			$customQuery = " WHERE user_type_id='2' AND user_active='1' ";
			createSelectOptions('user','user_id','user_name',$customQuery,$selectedId,'sa_requested_by_user_id',"class='validate[required] selectmenu'");?>
            <a href="user_add.php?param=add&user_type_id=2"><img src="images/plus-small.png" alt="Add new requeester/contact"></a>
            </td>
            <td width="15%" >Guard name:</td>
            <td width="37%" ><?php 
			$selectedId=addEditInputField('sa_guard_user_id');
			$customQuery = " WHERE user_type_id='3' AND user_active='1' ";
			createSelectOptions('user','user_id','user_name',$customQuery,$selectedId,'sa_guard_user_id',"class='validate[required] selectmenu '");?>
            <a href="user_add.php?param=add&user_type_id=3"><img src="images/plus-small.png" alt="Add new Guard"></a>
            </td>
          </tr>
          <tr>
            <td>Company/Client:</td>
            <td><?php 
			$selectedId=addEditInputField('sa_client_id');
			$customQuery = " WHERE client_active='1' ";
			createSelectOptions('client','client_id','client_company_name',$customQuery,$selectedId,'sa_client_id',"class='validate[required] selectmenu'");?>
           
            <a href=" client_add.php?param=add"><img src="images/plus-small.png" alt="Add new Company/client"></a>
            </td>
            <td>Site:</td>
            <td><?php 
			$selectedId=addEditInputField('sa_site_id');
			$customQuery = " WHERE ss_active='1' ";
			createSelectOptions('security_site','ss_id','ss_name',$customQuery,$selectedId,'sa_site_id',"class='validate[required] selectmenu '");?>
            <a href="security_site_add.php?param=add"><img src="images/plus-small.png" alt="Add new Site"></a></td>
          </tr>
          <tr>
            <td>Start date time: </td>            
            <td>
            <script>
			$(function() {
				$("input[name=sa_start_time]").datetimepicker({ 
					dateFormat: 'yy-mm-dd' ,
					timeFormat: 'hh:mm:ss',
					separator: ' ',
				});
			});
            </script>
            <input name="sa_start_time" type="text" value="<?php echo addEditInputField('sa_start_time'); ?>" size="20" class="validate[required]" readonly="readonly" /></td>
            <td>End date time: </td>
            <td>
            <script>
			$(function() {
				$("input[name=sa_end_time]").datetimepicker({ 
					dateFormat: 'yy-mm-dd' ,
					timeFormat: 'hh:mm:ss',
					separator: ' ',
				}); 
            });
            </script>
            <input name="sa_end_time" type="text" value="<?php echo addEditInputField('sa_end_time'); ?>" size="20" class="validate[required]" readonly="readonly" /></td>
          </tr>
          <tr>
            <td>Rate:</td>
            <td><input name="sa_rate" type="text" value="<?php echo addEditInputField('sa_rate'); ?>" size="20" class="validate[required,custom[number]]"/></td>
            <td></td>
            <td>
            </td>
          </tr>
        </table>
        <div class="clear"></div>
        <span id='total_time_loader' style="height:30px; float: left; width:100%; padding:10px 3px; font-size:15px; font-weight:bold;"></span>
        <div class="clear"></div>
        <?php if($param!='view'){?>
        <input class="button bgblue" type="submit" name="submit" value="Update" />
        <?php } ?>
      </form>
    </div>
	<?php if(strlen($sa_id))include_once('snippets/security_assignment/security_assignment_versions.php');?>  
  </div>
  <div id="footer">
    <?php include('footer.php');?>
  </div>
</div>
</body>
</html>
<script>
	$("form").validationEngine();
	calculateDifference();
	$('input[name=sa_start_time]').change(function(){
		//alert('test');
		calculateDifference();
	})	
	$('input[name=sa_end_time]').change(function(){
		calculateDifference();
	})
	$('input[name=sa_rate]').change(function(){
		//alert('test');
		calculateDifference();
	})


function calculateDifference(){
	//alert('test');
	
	var startDate = $('input[name=sa_start_time]').val();	
	var endDate= $('input[name=sa_end_time]').val();
	var rate= $('input[name=sa_rate]').val();
	//alert("start:"+startDate+" End:"+endDate);
	
	var loadUrl="snippets/security_assignment/ajax_get_time_difference.php";
	var ajax_load="<img src='images/ajax-loader-1.gif' alt='loading...' />";
	$("#total_time_loader").html(ajax_load);
	$.ajax({
	  type: "POST",
	  url: loadUrl,
	  data: { start_date: startDate,end_date: endDate,rate: rate}
	}).done(function( msg ) {
	  //alert( "Data Saved: " + msg );
	  //$('#total_time_loader').empty();
	  $('#total_time_loader').html(msg);
	});
	
	
}

</script>