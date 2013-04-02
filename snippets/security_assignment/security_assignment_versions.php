<?php 
$q="SELECT * 
	FROM security_assignment
	WHERE
		sa_uid='".$a['sa_uid']."' AND sa_active='1' 
	ORDER BY sa_insert_time DESC";

//echo $q;
$rc=mysql_query($q)or die(mysql_error());
if(mysql_num_rows($rc)){
	$a_sa=mysql_fetch_rowsarr($rc);
	$rows = mysql_num_rows($rc);
	$total_versions= $rows;
}else{
	$norecordfound= "No records found";
}
?>

<div class="clear"></div>
<div class="sa_versions">
  <h2>Security Assignment Versions</h2>
  <table width="100%" border="0" cellpadding="0" cellspacing="0" id="datatable_nopagination" style="text-shadow: white 0.1em 0.1em 0.1em">
    <thead>
      <tr> 
        
        <!--            <td width="17">#</td>

                                    -->
        <td>[version] Update Date</td>
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
        <td align="left">Comment </td>
        <td align="right">Action</td>
      </tr>
    </thead>
    <tbody>
      <?php for ($i = 0; $i < $rows; $i++) { ?>
      <tr id="<?php echo $a_sa[$i][sa_uid]; ?>" <?php

                                if ($_REQUEST[added_sa_id] == $a_sa[$i][sa_id]) {

                                    echo " style='color:red;'";

                                }

                                    ?>>
        <td><?php echo "[".$total_versions--."] ".$a_sa[$i][sa_insert_time]; ?></td>
        <td><?php

                                    echo "<b>W" . date("W", strtotime($a_sa[$i][sa_start_time])) . "</b>";

                                    //echo " Day".date("N", strtotime($a_sa[$i][sa_start_time]));  

                                    ?></td>
        <td><?php echo date('d-M-Y', strtotime($a_sa[$i][sa_start_time])); ?></td>
        <td ><?php echo date('D', strtotime($a_sa[$i][sa_start_time])); ?></td>
        <td class="greenText"><?php echo date('H:i', strtotime($a_sa[$i][sa_start_time])); ?></td>
        <td class="orangeText"><?php echo date('H:i', strtotime($a_sa[$i][sa_end_time])); ?></td>
        <td><?php echo getUserNameFrmId($a_sa[$i][sa_requested_by_user_id]); ?></td>
        <td><?php echo getClientCompanyNameFrmId($a_sa[$i][sa_client_id]); ?></td>
        <td><?php echo getSiteNameFrmId($a_sa[$i][sa_site_id]); ?></td>
        <td><?php echo getUserNameFrmId($a_sa[$i][sa_guard_user_id]); ?></td>
        <td><?php echo $a_sa[$i][sa_rate]; ?></td>
        <td><?php echo round(my_hour_diff($a_sa[$i][sa_start_time], $a_sa[$i][sa_end_time]), 2); ?></td>
        <td><?php echo round(my_hour_diff($a_sa[$i][sa_start_time], $a_sa[$i][sa_end_time]) * $a_sa[$i][sa_rate], 2) ?></td>
        <td><?php echo $a_sa[$i][sa_comment]; ?></td>
        <td><span style="width:65px; float:right;"> <a href="security_assignment_add.php?sa_id=<?php echo $a_sa[$i][sa_id]; ?>&param=view"><img src="images/viewIcon.png" alt="View" title="View" width="15" height="15" /></a> </span></td>
      </tr>
      <?php } ?>
    </tbody>
  </table>
</div>
