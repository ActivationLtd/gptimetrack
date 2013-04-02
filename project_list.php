<?php
include_once("config.php");

if(!hasPermission('project','add',$_SESSION[current_user_id])){
	echo "Permission denied";
	exit();
}

/*******************************************/
/* Add/Edit Partner Artist
 /*******************************************/
$valid=true;
$alert=array();
$param=$_REQUEST['param'];
$project_id=$_REQUEST['project_id'];
if(!strlen($project_id)){
	$param="add";
}else{
	$param="edit";
}
if(isset($_POST[submit])){
	if($param=='add'||$param=='edit'){
		$exception_field=array('submit','param');
		/*
		 *	server side validation
		*/
		if(empty($_POST["project_name"])){
			$valid=false;
			array_push($alert,"Please give a valid project_name");
		}
		/*************************************/
		if($valid){
			if($param=='add'){
				/*
				 *	Check whether current user has permission to add client
				*/
				if(hasPermission('Project','add',$_SESSION[current_user_id])){
					//$project_uid=makeRandomKey();
					/*************************************/
					/*
					 *	Create the insert query substring.
					*/
					$str=createMySqlInsertString($_POST,$exception_field);
					$str_k=$str['k'];
					$str_v=$str['v'];
					/*************************************/
					$sql="INSERT INTO project($str_k,project_updated_datetime) values ($str_v,now())";
					mysql_query($sql) or die(mysql_error()."<b>Query:</b><br>$sql<br>");
					$project_id= mysql_insert_id();
					insertLog('Project', 'Project added', 'project', 'project_id', $project_id,$sql,$_SESSION[current_user_id],print_r($_SERVER, true));
					$param='edit';
					array_push($alert,"The project has been saved!");
				}else{
					$valid=false;
					array_push($alert,"You don't have permission to add project");
				}
			}else if($param=='edit'){
				/*
				 *	Check whether current user has permission to edit client
				*/
				if(hasPermission('Project','edit',$_SESSION[current_user_id])){
					/*
					 *	Create the update query substring.
					*/
					$str=createMySqlUpdateString($_REQUEST,$exception_field);
					/*************************************/
					$sql="UPDATE project set $str where project_id='".$_REQUEST['project_id']."'";
					mysql_query($sql) or die(mysql_error()."<b>Query:</b><br>___<br>$sql<br>");
					insertLog('Project', 'Project updated', 'project', 'project_id', $project_id,$sql,$_SESSION[current_user_id],print_r($_SERVER, true));
					array_push($alert,"The project has been saved!");
				}else{
					$valid=false;
					array_push($alert,"You don't have permission to edit project");
				}
			}
			//echo $sql;
		}
	}
}


/*******************************************/
if($project_id){
	$sql = "SELECT * FROM project WHERE project_id='$project_id'";
	$r = mysql_query($sql)or die(mysql_error()."<b>Query:</b><br>___<br>$sql<br>");
	$a = mysql_fetch_assoc($r);
	$rows=mysql_num_rows($r);
}

$sql = "SELECT * FROM project";
$r = mysql_query($sql)or die(mysql_error()."<b>Query:</b><br>___<br>$sql<br>");
$arr = mysql_fetch_rowsarr($r);
$rows=mysql_num_rows($r);
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html>
<head>
<?php include_once('inc.head.php')?>
</head>
<body>
  <div id="wrapper">
    <div id="container">
      <div id="top1">
        <?php include('top.php');?>
      </div>
      <?php //include("snippets/account_management/account_management_menu.php");?>
      <div id="mid">
        <div class="clear"></div>
        <div id="left_m">
          <table width="100%">
            <tr>
              <td>
                <h2>
                  <?php echo ucfirst($param); ?>
                  Project<br />
                </h2>
              </td>
              <td align="right">
                <a href="<?php echo $_SERVER['PHP_SELF']; ?>">[+] Add </a>
              </td>
            </tr>
          </table>
          <?php printAlert($valid,$alert);?>
          <form action="#" method="post" enctype="multipart/form-data">
            <table width="100%">
              <tr>
                <td>
                  Project name:<br/> 
									<input name="project_name" type="text" value="<?php echo addEditInputField('project_name'); ?>" size="30" maxlength="60" class="validate[required]" />								
                </td>
              </tr>
              <tr>
                <td>
                  Project Brand Name:<br/> 
                  <?php
                  $selectedId=addEditInputField('project_brand_name');                 
									createSelectOptionsFrmArray($brands_array,$selectedId,"project_brand_name"," class='validate[required]'")
                  ?>
                </td>
              </tr>
              <tr>
                <td>
                  project_client_id: <br/>
                  <?php									
									$selectedId = addEditInputField('project_client_id');
									$customQuery = " WHERE client_active='1' ";
									createSelectOptions('client', 'client_id', 'client_company_name', $customQuery, $selectedId, 'project_client_id', "class='validate[required] selectmenu'");
									?>
                  </td>
              </tr>
                <tr>
                  <td>
                    project_country_name:<br/>
                    <input name="project_country_name" type="text" value="<?php echo addEditInputField('project_country_name'); ?>" size="30" maxlength="60" class="validate[required]" />
                  </td>
                </tr>
                <tr>
                  <td>
                    project_start_datetime :<br/> 
										<script>
												$(function() {
														$("input[name=project_start_datetime]").datetimepicker({
																dateFormat: 'yy-mm-dd' ,
																timeFormat: 'hh:mm:ss',
																separator: ' ',
														});
												});
										</script>
										<input name="project_start_datetime" type="text" value="<?php echo addEditInputField('project_start_datetime'); ?>" size="20" class="validate[required]" readonly="readonly" />
                  </td>
                </tr>
                <tr>
                  <td>
                    project_end_datetime :<br/> 
										<script>
												$(function() {
														$("input[name=project_end_datetime]").datetimepicker({
																dateFormat: 'yy-mm-dd' ,
																timeFormat: 'hh:mm:ss',
																separator: ' ',
														});
												});
										</script>
										<input name="project_end_datetime" type="text" value="<?php echo addEditInputField('project_end_datetime'); ?>" size="20" class="validate[required]" readonly="readonly" />
                  </td>
                </tr>
                <tr>
                  <td>
                    project_bucket_name:<br/>
                    <input name="project_bucket_name" type="text" value="<?php echo addEditInputField('project_bucket_name'); ?>" size="30" maxlength="60" class="validate[required]" />
                  </td>
                </tr>
                <tr>
                  <td>
                   project_estimated_hours:<br/>
                   <input name="project_estimated_hours" type="text" value="<?php echo addEditInputField('project_estimated_hours'); ?>" size="30" maxlength="60" class="validate[required]" />
                  </td>
                </tr>
                <tr>
                  <td>
                    project_deliverable_type:<br/> 
                    <?php
                    $selectedId=addEditInputField('project_deliverable_type');                 
                    createSelectOptionsFrmArray($deliverable_type_array,$selectedId,"project_deliverable_type"," class='validate[required]'")
                    ?>
                  </td>
                </tr>
                <tr>
                  <td>
                    project_activity_type:<br/> 
                    <?php
                    $selectedId=addEditInputField('project_activity_type');                 
                    createSelectOptionsFrmArray($activity_type_array,$selectedId,"project_activity_type"," class='validate[required]'")
                    ?>
                  </td>
                </tr>
                <tr>
                  <td>
                   project_deliverable_count:<br/>
                   <input name="project_deliverable_count" type="text" value="<?php echo addEditInputField('project_deliverable_count'); ?>" size="30" maxlength="60" class="validate[required]" />
                </tr>
                <tr>
                  <td>
                    project_depp_key:<br/>
                   <input name="project_depp_key" type="text" value="<?php echo addEditInputField('project_depp_key'); ?>" size="30" maxlength="60" class="validate[required]" />
                  </td>
                </tr>
                <tr>
                  <td>
                    project_vml_job_number:<br/>
                   <input name="project_vml_job_number" type="text" value="<?php echo addEditInputField('project_vml_job_number'); ?>" size="30" maxlength="60" class="validate[required]" />
                  </td>
                </tr>
                <tr>
                  <td>
                    project_additional_info: <br/> 
                    <textarea name="project_additional_info" cols="30" rows="6" class=""><?php echo addEditInputField('project_dispatch_note'); ?></textarea>

                  </td>
                </tr>
                <tr>
                  <td>
                    Status<br/> <?php
                    $selectedId=addEditInputField('project_active');
                    $customQuery = " WHERE option_group='active_status' AND option_active='1' ";
										createSelectOptions('options','option_value','option_name',$customQuery,$selectedId,'project_active', "  class='validate[required]'");?>

                  </td>
                </tr>

            </table>
            <input name="submit" type="submit" class="bgblue button" value="Save" />
            <input name="reset" type="reset" class="bgblue button" value="Reset" />
            <input type="hidden" name="project_updated_by_user_id" value="<?php echo $_SESSION["current_user_id"]; ?>" />
            <?php
          if($project_id){?>
            <input type="hidden" name="project_id" value="<?php echo $project_id; ?>" />
            <?php
          }?>
          </form>
          <div class="clear"></div>
        </div>
        <div id="right_m">
          <!--<h2>List of Customers</h2>-->
          <table id="datatable" width="100%">
            <thead>
              <tr>
                <td>[id]</td>
                <td>project_name</td>
                <td>project_brand_name</td>
                <td>project_client</td>
                <td>project_deliverable_type</td>
                <td>project_depp_key</td>
                <td>project_vml_job_number</td>
                <td>Status</td>
                <td>Action</td>
              </tr>
            </thead>
            <tbody>
              <?php for($i=0;$i<$rows;$i++){?>
              <tr>
                <td><?php echo $arr[$i][project_id];?></td>
                <td><?php echo $arr[$i][project_name]; ?></td>
                <td><?php echo $arr[$i][project_brand_name]; ?></td>
                <td><?php echo getClientCompanyNameFrmId($arr[$i][project_client_id]); ?></td>
                <td><?php echo $arr[$i][project_deliverable_type];?></td>
                <td><?php echo $arr[$i][project_depp_key];?></td>
                <td><?php echo $arr[$i][project_vml_job_number];?></td>
                <td><?php echo getActiveStatus($arr[$i][project_active]);?></td>
                <td>
                  <?php
                    if(hasPermission('project', 'edit', $_SESSION[current_user_id])){
                  	//if($arr[$i][user_first_name]!='superadmin'){
											echo "<a href='project_list.php?project_id=".$arr[$i][project_id]."&param=edit'>view</a>";
											//}
										}
										?>
                </td>
              </tr>
              <?php } ?>
            </tbody>
          </table>
        </div>
      </div>
    </div>
    <div id="footer">
      <?php include('footer.php');?>
    </div>
  </div>
</body>
</html>
