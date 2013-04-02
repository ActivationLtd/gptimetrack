<div class="clear"></div>
<h2>Your Assignments</h2>
<?php 
$client_id=$_REQUEST[client_id];
$r=mysql_query("
	select * from security_assignment s1 
	where 
		sa_guard_user_id ='".$_SESSION[current_user_id]."' and
 		sa_insert_time=
			(select max(s2.sa_insert_time) from security_assignment s2 
				where s1.sa_uid=s2.sa_uid);")or die(mysql_error());
$rows=mysql_num_rows($r);
if($rows>0){
	$arr=mysql_fetch_rowsarr($r);

?>

<table width="100%" border="0" cellpadding="0" cellspacing="0" id="datatable">
            <thead>
              <tr>
                <td width="78" >Start date</td>
                <td width="80" >Requested by</td>
                <td width="44" >Start</td>
                <td width="64" >End</td>
                <td width="38">Guard</td>
                <td width="49" >Rate</td>
                <td width="55" >Hours</td>
                <td width="61" >Amount</td>
                <td width="46" >Site</td>
                <td width="60" >Action</td>
              </tr>
            </thead>
            <tbody>
              <?php for($i=0;$i<$rows;$i++){?>
              <tr>
                <td><?php echo  date('Y-m-d',strtotime($arr[$i][sa_start_time]));?></td>
                <td><?php echo  getUserNameFrmId($arr[$i][sa_requested_by_user_id]);?></td>
                <td><?php echo date('H:i',strtotime($arr[$i][sa_start_time]));?></td>
                <td><?php echo date('H:i',strtotime($arr[$i][sa_end_time]));?></td>
                <td><?php echo getUserNameFrmId($arr[$i][sa_guard_user_id]);?></td>
                <td><?php echo $arr[$i][sa_rate];?></td>
                <td><?php echo my_hour_diff($arr[$i][sa_start_time],$arr[$i][sa_end_time]); ?></td>
                <td><?php echo round(my_hour_diff($arr[$i][sa_start_time],$arr[$i][sa_end_time])*$arr[$i][sa_rate],2)?></td>
                <td><?php echo getSiteNameFrmId($arr[$i][sa_site_id]);?></td>
                <td>
                <?php if(hasPermission('security_assignment','add',$_SESSION[current_user_id])){ ?><a href="security_assignment_add.php?sa_id=<?php echo $arr[$i][sa_id];?>&param=edit">Edit</a>
                <?php } ?>
                <a href="security_assignment_add.php?sa_id=<?php echo $arr[$i][sa_id];?>&param=view">View</a>
                </td>
                
                
              </tr>
              <?php } ?>
            </tbody>
          </table>
<div class="clear"></div>
<?php }else{
	echo "You don't have any assignment.";
}?>