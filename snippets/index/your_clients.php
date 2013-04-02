<?php 
$q="select * from client where client_active='1'";
//echo $q;
$r=mysql_query($q)or die(mysql_error()."your_client.php 1");
$rows=mysql_num_rows($r);

if($rows>0){
	$arr=mysql_fetch_rowsarr($r);
?>
<div class="clear"></div>
<table id="" width="100%">
          <thead>
            <tr class="firstrow">
              <td ><h2>Company</h2></td>
            </tr>
          </thead>
          <tbody>
            <?php for($i=0;$i<$rows;$i++){?>
            <tr>
              <td>
			  <div style="float:left;">
              <a href="security_assignment_list.php?sa_client_id[]=<?php echo $arr[$i][client_id];?>">
			  <?php echo $arr[$i][client_company_name];?>
              </a> 
           
              </div>
              <!--
              <a class="none" href="#" style="float:right;"><img src="images/purchaseorder_small_icon.png" title="Purchase Order" alt="Purchase Order" height="20px" width="20px" /></a>
              -->              
              <a class="none" href="security_assignment_list.php?sa_client_id[]=<?php echo $arr[$i][client_id];?>" style="float:right;" title="Add assignment" ><img src="images/plus.jpg" title="Add assignment" alt="Add assignment" height="20px" width="20px"/></a>

              </td>              
            </tr>
            <?php } ?>
          </tbody>
        </table>
<div class="clear"></div>
<?php }else{
	echo "You have no associated client";
}?>