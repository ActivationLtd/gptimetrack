<?php
include('config.php');
$valid = true;
$alert = array();
//$client_id=$_REQUEST[client_id];
//myprint_r($_REQUEST);
$parameterized_query = "";
if (count($_REQUEST[sa_client_id])) {
    $sa_client_id_csv = implode(',', $_REQUEST[sa_client_id]);
    $parameterized_query.=" sa_client_id in ($sa_client_id_csv) AND ";
}
if (count($_REQUEST[sa_guard_user_id])) {
    $sa_guard_user_id_csv = implode(',', $_REQUEST[sa_guard_user_id]);
    $parameterized_query.=" sa_guard_user_id in ($sa_guard_user_id_csv) AND ";
}
if (count($_REQUEST[sa_site_id])) {
    $sa_site_id_csv = implode(',', $_REQUEST[sa_site_id]);
    $parameterized_query.=" sa_site_id in ($sa_site_id_csv) AND ";
}
if (count($_REQUEST[sa_requested_by_user_id])) {
    $sa_requested_by_user_id_csv = implode(',', $_REQUEST[sa_requested_by_user_id]);
    $parameterized_query.=" sa_requested_by_user_id in ($sa_requested_by_user_id_csv) AND ";
}
if (strlen($_REQUEST[sa_date_start_datetime]) || strlen($_REQUEST[sa_date_end_datetime])) {
    //    start date filter
    if (strlen($_REQUEST[sa_date_start_datetime])) {
        $sa_date_start_datetime = $_REQUEST[sa_date_start_datetime];
        $parameterized_query.=" sa_start_time >= '$sa_date_start_datetime 00:00:00' AND ";
        // echo "<br />start: $sa_date_start_datetime<br />";
    }
    //    end date filter
    if (strlen($_REQUEST[sa_date_end_datetime])) {
        $sa_date_end_datetime = $_REQUEST[sa_date_end_datetime];
        $parameterized_query.=" sa_start_time <= '$sa_date_end_datetime 00:00:00' AND ";
        //echo "<br />start: $sa_date_end_datetime_datetime<br />";
    }
}
//year filter
if (strlen($_REQUEST[sa_year]) && $_REQUEST[sa_month] == "month" && strlen($_REQUEST[sa_week_number]) == 0) {
    $sa_year = $_REQUEST[sa_year];
    if (empty($sa_year)) {
        $sa_year = 2012;
    }
    $filter_year_start = date("Y-m-d H:i:s", mktime(0, 0, 0, 1, 1, $sa_year));
    $date = strtotime($filter_year_start);
    $date = strtotime("+1 year", $date);
    $filter_year_end = date("Y-m-d H:i:s", $date);
    $parameterized_query.=" sa_start_time >= '$filter_year_start' AND sa_start_time < '$filter_year_end' AND";
}
//week filter
if (strlen($_REQUEST[sa_week_number])) {
    $sa_year = $_REQUEST[sa_year];
    $sa_week_number = $_REQUEST[sa_week_number];
    if (empty($sa_year)) {
        $sa_year = 2012;
    }
    $firstMon = strtotime("mon jan {$sa_year}");
    $weeksOffset = $sa_week_number - date('W', $firstMon);
    $searchedMon = strtotime("+{$weeksOffset} week " . date('Y-m-d', $firstMon));
    $week_start_date = date("Y-m-d H:i:s", $searchedMon);
    $d = strtotime("+7 day", $searchedMon);
    $week_end_date = date("Y-m-d H:i:s", $d);
    $parameterized_query.=" sa_start_time >= '$week_start_date' AND sa_start_time < '$week_end_date' AND";
}
//month filter
if (strlen($_REQUEST[sa_month])) {
    $sa_month = $_REQUEST[sa_month];
    if ($sa_month != "month") {
        $sa_year = $_REQUEST[sa_year];
        if (empty($sa_year)) {
            $sa_year = 2012;
        }
        for ($i = 1; $i <= 12; $i++) {
            if ($month_arr[$i] == $sa_month) {
                $filter_month = $i;
            }
        }
        $filter_month_start = date("Y-m-d H:i:s", mktime(0, 0, 0, $filter_month, 1, $sa_year));
        $date = strtotime($filter_month_start);
        $date = strtotime("+1 month", $date);
        $filter_month_end = date("Y-m-d H:i:s", $date);
        $parameterized_query.=" sa_start_time >= '$filter_month_start' AND sa_start_time < '$filter_month_end' AND";
    }
}
if (currentUserIsGuard()) {
    $parameterized_query.=" sa_guard_user_id='" . $_SESSION[current_user_id] . "' AND ";
}
//echo "<br />end query: $parameterized_query";
//$todays_assign=" AND DATE(sa_start_time) = DATE(NOW())";
if (strlen($parameterized_query)) {
    $q_filtered = "select * from security_assignment s1
			where $parameterized_query
			sa_active='1' AND
			sa_insert_time=
			(select max(s2.sa_insert_time) from security_assignment s2
			where s1.sa_uid=s2.sa_uid)";
} else {
    $targetDate = date("Y-m-d", mktime(0, 0, 0, date("m"), date("d") + $d, date("Y")));
    $targetNextDate = date("Y-m-d", mktime(0, 0, 0, date("m"), date("d") + $d + 1, date("Y")));
    $q_filtered = "select * from security_assignment s1
			where $parameterized_query
			sa_active='1' AND
			sa_insert_time=
			(select max(s2.sa_insert_time) from security_assignment s2
			where s1.sa_uid=s2.sa_uid) AND sa_start_time>='$targetDate' AND  sa_start_time<'$targetNextDate'";
}
//echo $parameterized_query;
//echo $sa_client_id_csv;
$r = mysql_query($q_filtered) or die(mysql_error());
$rows = mysql_num_rows($r);
if ($rows > 0) {
    $arr = mysql_fetch_rowsarr($r);
}

function createDropdown($arr, $frm) {
    echo '<select name="' . $frm . '" id="' . $frm . '">'; //<option value="">Select one…</option>';
    foreach ($arr as $key => $value) {
        if ($value == $_REQUEST[sa_month]) {
            echo '<option selected value="' . $value . '">' . $value . '</option>';
        } else {
            echo '<option value="' . $value . '">' . $value . '</option>';
        }
    }
    echo '</select>';
}

/*
 * 	Print assignment add confirmation
 */
if (strlen($_REQUEST['added_sa_id'])) {
    array_push($alert, "Assignment has been saved.");
}
/**/
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html>
    <head>
        <?php include('inc.head.php'); ?>
    </head>
    <body>
        <div id="wrapper">
            <div id="container">
                <div id="top1">
                    <?php include('top.php'); ?>
                </div>
                <div id="mid">
                    <div id="mid_left">
                        <?php //include('snippets/security_assignment/security_assignment_menu.php');   ?>
                        <div class="alert"> <?php printAlert($valid, $alert); ?> </div>
                        <?php // if ($_SESSION[current_user_type_id] != 3) {  ?>
                        <?php if (hasPermission('security_assignment', 'add', $_SESSION[current_user_id])) { ?>
                            <div class="quickadd" style="background-color: #EAEBFF; padding: 10px; border-radius: 5px 5px 5px 5px;">
                                <form action="security_assignment_add.php" method="post" enctype="multipart/form-data">
                                    <input type="hidden" name="param" value="add" />
                                    <div class="clear"></div>
                                    <table width="100%" border="0" align="left" cellpadding="0" cellspacing="0" id="list">
                                        <tr>
                                            <td align="left" valign="top">Company/Client: <br />
                                                <?php
                                                if (count($_REQUEST["sa_client_id"]) == 1) {
                                                    $selectedId = $_REQUEST["sa_client_id"][0];
                                                }else
                                                    $selectedId = "";
                                                $customQuery = " WHERE client_active='1' ";
                                                createSelectOptions('client', 'client_id', 'client_company_name', $customQuery, $selectedId, 'sa_client_id', "class='validate[required] selectmenu'");
                                                ?>
                                                &nbsp; <a href=" client_add.php?param=add"> <img src="images/plus-small.png" alt="Add new Company/client"> </a> </td>
                                            <td align="left" valign="top">Requested by : <br />
                                                <?php
                                                if (count($_REQUEST["sa_requested_by_user_id"]) == 1) {
                                                    $selectedId = $_REQUEST["sa_requested_by_user_id"][0];
                                                }else
                                                    $selectedId = "";
                                                //$selectedId=addEditInputField('sa_requested_by_user_id');
                                                $customQuery = " WHERE user_type_id='2' AND user_active='1' ";
                                                createSelectOptions('user', 'user_id', 'user_fullname', $customQuery, $selectedId, 'sa_requested_by_user_id', "class='validate[required] selectmenu'");
                                                ?>
                                                &nbsp; <a href="user_add.php?param=add&user_type_id=2"> <img src="images/plus-small.png" alt="Add new requeester/contact"> </a> </td>
                                            <td align="left" valign="top">Site: <br />
                                                <?php
                                                if (count($_REQUEST["sa_site_id"]) == 1) {
                                                    $selectedId = $_REQUEST["sa_site_id"][0];
                                                }else
                                                    $selectedId = "";
                                                //$selectedId=addEditInputField('sa_site_id');
                                                $customQuery = " WHERE ss_active='1' ";
                                                createSelectOptions('security_site', 'ss_id', 'ss_name', $customQuery, $selectedId, 'sa_site_id', "class='validate[required] selectmenu '");
                                                ?>
                                                &nbsp; <a href="security_site_add.php?param=add"> <img src="images/plus-small.png" alt="Add new Site"> </a> </td>
                                            <td align="left" valign="top">Guard name: <br />
                                                <?php
                                                if (count($_REQUEST["sa_guard_user_id"]) == 1) {
                                                    $selectedId = $_REQUEST["sa_guard_user_id"][0];
                                                }else
                                                    $selectedId = "";
                                                //$selectedId=addEditInputField('sa_guard_user_id');
                                                $customQuery = " WHERE user_type_id='3' AND user_active='1' ";
                                                createSelectOptions('user', 'user_id', 'user_fullname', $customQuery, $selectedId, 'sa_guard_user_id', "class='validate[required] selectmenu '");
                                                ?>
                                                &nbsp; <a href="user_add.php?param=add&user_type_id=3"> <img src="images/plus-small.png" alt="Add new Guard"> </a> </td>
                                        </tr>
                                        <tr>
                                            <td align="left" valign="top">Start date time: <br />
                                                <script>
                                                    $(function() {
                                                        $("input[name=sa_start_time]").datetimepicker({
                                                            dateFormat: 'yy-mm-dd' ,
                                                            timeFormat: 'hh:mm:ss',
                                                            separator: ' ',
                                                        });
                                                    });
                                                </script>
                                                <input name="sa_start_time" type="text" value="<?php echo addEditInputField('sa_start_time'); ?>" size="20" class="validate[required]" readonly="readonly" />
                                            </td>
                                            <td align="left" valign="top">End date time: <br />
                                                <script>
                                                    $(function() {
                                                        $("input[name=sa_end_time]").datetimepicker({
                                                            dateFormat: 'yy-mm-dd' ,
                                                            timeFormat: 'hh:mm:ss',
                                                            separator: ' ',
                                                        });
                                                    });
                                                </script>
                                                <input name="sa_end_time" type="text" value="<?php echo addEditInputField('sa_end_time'); ?>" size="20" class="validate[required]" readonly="readonly" />
                                            </td>
                                            <td align="left" valign="top">Rate (per hour): <br />
                                                <input name="sa_rate" type="text" value="<?php echo addEditInputField('sa_rate'); ?>" size="20" class="validate[required,custom[number]]" />
                                            </td>
                                            <td align="left" valign="top">Comment: <br />
                                                <textarea name="sa_comment" cols="30" rows="3" class=""><?php echo addEditInputField('sa_comment'); ?></textarea>
                                                <input class="button bgblue" type="submit" name="submit" value="Add " style="margin:0 5px 5px 0; padding:3px;" />
                                            </td>
                                        </tr>
                                    </table>
                                    <div class="clear"></div>
                                    <span id='total_time_loader' style="float: left; width: 450px; font-size: 11px; font-weight: bold;"></span>
                                    <div class="clear"></div>
                                </form>
                            </div>
                        <?php } ?>
                        <div class="clear"></div>
                        company(s):
                        <div class="clear"></div>
                        <?php
                        if (count($_REQUEST["sa_client_id"])) {
                            foreach ($_REQUEST["sa_client_id"] as $client_id) {
                                echo "<span class='compnameBlock'>" . getClientCompanyNameFrmId($client_id) . "</span> ";
                            }
                        }
                        ?>
                        <div class="clear"></div>
                        <table width="100%" border="0" cellpadding="0" cellspacing="0" id="datatable_nopagination" style="text-shadow: white 0.1em 0 0">
                            <thead style="font-weight:bold;">
                                <tr>
                                    <td>Start_date</td>
                                    <td>Week</td>
                                    <td>Day</td>
                                    <td>Start</td>
                                    <td>End</td>
                                    <td>Hours</td>
                                    <td>Requested by</td>
                                    <td>Company /Client</td>
                                    <td>Site</td>
                                    <td>Guard</td>
                                    <?php if (currentUserIsGuard()) { ?>
                                        <td>Rate</td>
                                        <td>Amount</td>
                                    <?php } ?>
                                    <td align="left">Comment</td>
                                    <td align="right">Action</td>
                                    <td align="right"><?php if (hasPermission('security_assignment', 'delete', $_SESSION[current_user_id])) { ?>
                                            Delete
                                        <?php } ?></td>
                                </tr>
                            </thead>
                            <tbody>
                                <?php
                                $total_hours = 0;
                                $total_amount = 0;
                                for ($i = 0; $i < $rows; $i++) {
                                    ?>
                                    <tr id="<?php echo $arr[$i][sa_uid]; ?>" <?php
                                if ($_REQUEST[added_sa_id] == $arr[$i][sa_id]) {
                                    echo " style='color:red;'";
                                }
                                    ?>>
                                        <td><span class="small" style="font-size:0px;"> <?php echo date('ymd', strtotime($arr[$i][sa_start_time])); ?> </span> <br />
                                            <b> <?php echo date('d-M-y', strtotime($arr[$i][sa_start_time])); ?></b> </td>
                                        <td><?php echo "W" . date("W", strtotime($arr[$i][sa_start_time])) . ""; ?> </td>
                                        <td><?php echo date('l', strtotime($arr[$i][sa_start_time])); ?> </td>
                                        <td class="greenText"><?php echo date('H:i', strtotime($arr[$i][sa_start_time])); ?> </td>
                                        <td class="orangeText"><?php echo date('H:i', strtotime($arr[$i][sa_end_time])); ?> </td>
                                        <td><?php
                                    $hour = round(my_hour_diff($arr[$i][sa_start_time], $arr[$i][sa_end_time]), 2);
                                    $total_hours+=$hour;
                                    echo $hour;
                                    ?></td>
                                        <td><?php echo getUserFullNameFrmId($arr[$i][sa_requested_by_user_id]); ?> </td>
                                        <td><?php echo getClientCompanyNameFrmId($arr[$i][sa_client_id]); ?> </td>
                                        <td><?php echo getSiteNameFrmId($arr[$i][sa_site_id]); ?> </td>
                                        <td><?php echo getUserFullNameFrmId($arr[$i][sa_guard_user_id]); ?> </td>
                                        <?php
                                        if (currentUserIsGuard()) {
                                            ?>
                                            <td><?php echo $arr[$i][sa_rate]; ?> </td>
                                            <td><?php
                                    $amount = round(my_hour_diff($arr[$i][sa_start_time], $arr[$i][sa_end_time]) * $arr[$i][sa_rate], 2);
                                    $total_amount+=$amount;
                                    echo $amount;
                                            ?></td>
                                        <?php } ?>
                                        <td><?php echo nl2br($arr[$i][sa_comment]); ?> </td>
                                        <td><span style="width: 65px; float: right;">
                                                <?php if (hasPermission('security_assignment', 'add', $_SESSION[current_user_id])) { ?>
                                                    <a href="security_assignment_add.php?sa_id=<?php echo $arr[$i][sa_id]; ?>&param=edit" class="none">Edit</a>
                                                <?php } ?>
                                                <a href="security_assignment_add.php?sa_id=<?php echo $arr[$i][sa_id]; ?>&param=view" id="<?php echo $arr[$i][sa_id]; ?>"> <img src="images/viewIcon.png" alt="View" title="View" width="15" height="15" /> </a>
                                                <input type="button" class="button bgblue" name="sms_<?= $arr[$i][sa_id] ?>" value="Notify" style="padding:3px; width:auto; float:left; font-size:9px;"/>
                                                <span id="ajax_working_<?= $arr[$i][sa_id] ?>"></span> </span>
                                            <script>
                                                $('input[name=sms_<?= $arr[$i][sa_id] ?>]').click(function(){								
                                                    var loadUrl="snippets/security_assignment/ajax_notify.php";
                                                    var ajax_load="Sending";
                                                    //alert(taskID);
                                                    /* loads order list */
                                                    $("#ajax_working_<?= $arr[$i][sa_id] ?>").html(ajax_load);
                                                    $.ajax({
                                                        type: "POST",
                                                        url: loadUrl,
                                                        //context: document.body,
                                                        //async:false,
                                                        //timeout:4000,
                                                        data: { sa_id:'<?= $arr[$i][sa_id] ?>'}
                                                    }).done(function( msg ) {
                                                        //$('#ajax_working').html(msg);
                                                        //if(msg=='Success'){
                                                        $("#ajax_working_<?= $arr[$i][sa_id] ?>").html(msg);
                                                        //}
                                                    });
                                                    /*********************/	
                                                });	
                                            </script>
                                        </td>
                                        <td><?php if (hasPermission('security_assignment', 'delete', $_SESSION[current_user_id])) { ?>
                                                <input type="checkbox" name="delete_checkbox" id="delete_checkbox_<?= $arr[$i][sa_uid] ?>" value="Yes"/>
                                                <img id="<?php echo $arr[$i][sa_uid]; ?>" class="delete_assignment" src="images/delete-icon-md.png" alt="Delete" title="Delete" width="15" height="15" style="cursor: pointer;" />
                                            <?php } ?>
                                        </td>
                                    </tr>
                                <?php } ?>
                            </tbody>
                        </table>
                        <div class="clear"></div>
                        <?php
                        if (hasPermission('security_assignment', 'add', $_SESSION[current_user_id])) {
                            echo "Total hours : " . $total_hours . "<br />";
                            echo "Total amount : " . $total_amount . "<br />";
                        }
                        ?>
                        <form action="security_assignment_list_to_excel.php" method="post">
                            <input type="hidden" name="sql_query_string" value="<?php echo $q_filtered; ?>" class="bgblue button" />
                            <?php if (hasPermission('security_assignment', 'delete', $_SESSION[current_user_id])) { ?>
                                <input type="submit" name="submit" value="Download Excel" style="float: right;" />
                            <?php } ?>
                        </form>
                        <?php if (hasPermission('security_assignment', 'delete', $_SESSION[current_user_id])) { ?>
                            <!--
                            <h2>Total hour Distribution among guards</h2>
                            <div id="visualization" style="height:150px;"></div>
                            -->
                        <?php } ?>
                    </div>
                    <div id="mid_right">
                        <div class="filter" style="background-color: #EAEBFF;border-radius: 5px 5px 5px 5px; float:left; padding: 5px;">
                            <h2>Filter </h2>
                            <form action="" method="get">
                                <table width="100%" border="0" cellpadding="0" cellspacing="0">
                                    <?php if (hasPermission('security_assignment', 'delete', $_SESSION[current_user_id])) { ?>
                                        <tr>
                                            <td width="15%">Company/Client <br />
                                                <?php
                                                $selectedIdCsv = $sa_client_id_csv;
                                                $customQuery = " WHERE client_active='1' ";
                                                createMultiSelectOptions('client', 'client_id', 'client_company_name', $customQuery, $selectedIdCsv, 'sa_client_id[]', " multiple='multiple' class='multiselectdd  validate[required] '");
                                                ?>
                                            </td>
                                            <tr>
                                                <td width="15%">Site <br />
                                                    <?php
                                                    $selectedIdCsv = $sa_site_id_csv;
                                                    $customQuery = " WHERE ss_active='1' ";
                                                    createMultiSelectOptions('security_site', 'ss_id', 'ss_name', $customQuery, $selectedIdCsv, 'sa_site_id[]', " multiple='multiple' class='multiselectdd'");
                                                    ?>
                                                </td>
                                            </tr>
                                            <tr>
                                                <td width="15%">Guard <br />
                                                    <?php
                                                    $selectedIdCsv = $sa_guard_user_id_csv;
                                                    $customQuery = " WHERE user_active='1'  AND user_type_id='3'";
                                                    createMultiSelectOptions('user', 'user_id', 'user_fullname', $customQuery, $selectedIdCsv, 'sa_guard_user_id[]', " multiple='multiple' class='multiselectdd'");
                                                    ?>
                                                </td>
                                            </tr>
                                        <?php } ?>
                                        <tr>
                                            <td>Start date: <br />
                                                <input name="sa_date_start" type="text" value="<?php echo addEditInputField('sa_date_start'); ?>" size="20" readonly="readonly" style="float: none;" />
                                                <input name="sa_date_start_datetime" id="sa_date_start_datetime" type="hidden" value="<?php echo addEditInputField('sa_date_start_datetime'); ?>" size="20" class="" readonly="readonly" />
                                                <script>
                                                    $("input[name=sa_date_start]").datepicker({
                                                        dateFormat: "dd-MM-yy",
                                                        altField: "#sa_date_start_datetime",
                                                        altFormat: "yy-mm-dd",
                                                        onSelect: function() {
                                                            disableWeekMonthYear();
                                                        }
                                                    });
                                                </script>
                                            </td>
                                        </tr>
                                        <tr>
                                            <td>End date: <br />
                                                <input name="sa_date_end" type="text" value="<?php echo addEditInputField('sa_date_end'); ?>" size="20" class="" readonly="readonly" style="float: none;" />
                                                <input name="sa_date_end_datetime" id="sa_date_end_datetime" type="hidden" value="<?php echo addEditInputField('sa_date_end_datetime'); ?>" size="20" class="" readonly="readonly" />
                                                <script>
                                                    $("input[name=sa_date_end]").datepicker({
                                                        dateFormat: "dd-MM-yy",
                                                        altField: "#sa_date_end_datetime",
                                                        altFormat: "yy-mm-dd",
                                                        onSelect: function() {
                                                            disableWeekMonthYear();
                                                        }
                                                    });
                                                    function disableWeekMonthYear(){
                                                        var sa_date_start_datetime= $("#sa_date_start_datetime").val();
                                                        var sa_date_end_datetime= $("#sa_date_end_datetime").val();
                                                        
                                                        if (sa_date_end_datetime != "" && sa_date_start_datetime != ""){
                                                            $("#sa_week_number").val("");
                                                            $("#sa_week_number").attr("disabled", "disabled");
                                                            $("#sa_month").val("");
                                                            $("#sa_month").attr("disabled", "disabled");
                                                            $("#sa_year").val("");
                                                            $("#sa_year").attr("disabled", "disabled");
                                                        }
                                                    }
                                                    
                                                    
                                                </script>
                                            </td>
                                        </tr>
                                            <tr>
                                                <td>Week Number: <br />
                                                    <input id="sa_week_number" name="sa_week_number" type="text" value="<?php echo addEditInputField('sa_week_number'); ?>" size="20" class="validate[min[1], max[54], custom[number]]" style="float: none;" />
                                                </td>
                                                <script type="text/javascript">
                                                    $("#sa_week_number").blur(function (){ loadCurrentYear(); });
                                                    function loadCurrentYear(){                                                                                                                
                                                        var currentYear = (new Date).getFullYear();
                                                        $("#sa_year").val(currentYear);
                                                    }    
                                                </script>
                                            </tr>
                                        <tr>
                                            <td>Month: <br />
                                                <?php createDropdown($month_arr, 'sa_month'); ?>
                                              <!--<input name="sa_month" type="text" value="<?php echo addEditInputField('sa_month'); ?>" size="20" class="validate[min[1], max[54], custom[number]]" style="float:none;" />-->
                                            </td>
                                        </tr>
                                        <tr>
                                            <td>Year: <br />
                                                <input id ="sa_year" name="sa_year" type="text" value="<?php echo addEditInputField('sa_year'); ?>" size="20" class="validate[min[2012], max[9999], custom[number]]" style="float: none;" />
                                            </td>
                                        </tr>
                                        <?php if (hasPermission('security_assignment', 'delete', $_SESSION[current_user_id])) { ?>
                                            <tr>
                                                <td>Requested by <br />
                                                    <?php
                                                    $selectedIdCsv = $sa_requested_by_user_id_csv;
                                                    $customQuery = " WHERE user_active='1' AND user_type_id='2'";
                                                    createMultiSelectOptions('user', 'user_id', 'user_fullname', $customQuery, $selectedIdCsv, 'sa_requested_by_user_id[]', " multiple='multiple' class='multiselectdd'");
                                                    ?>
                                                </td>
                                            </tr>
                                        <?php } ?>
                                        <tr>
                                            <td><input type="submit" name="submit" value="Filter" class="bgblue button" />
                                                <a href="security_assignment_list.php" class='button bgblue'>Reset</a> </td>
                                        </tr>
                                </table>
                            </form>
                        </div>
                        <?php
                        if (hasPermission('security_assignment', 'add', $_SESSION[current_user_id])) {
                            include('snippets/index/your_clients.php');
                        }
                        ?>
                        <div class="clear"></div>
                    </div>
                </div>
                <div id="footer">
                    <?php include('footer.php'); ?>
                </div>
            </div>
        </div>
    </body>
</html>
<script type="text/javascript">
    /*
     *		Delete Security assignment start
     */
    $('img[class=delete_assignment]').click(function(){
        var sa_uid = $(this).attr("id");
        //alert(sa_uid);
        var delete_checkbox=$("input[id=delete_checkbox_"+sa_uid+"]").attr('checked');
        if(delete_checkbox=='checked'){		
            var loadUrl="snippets/security_assignment/ajax_delete_assignment.php";
            var ajax_load="<img src='images/ajax-loader-1.gif' class='loading'  alt='loading...' />";
				
            $('tr[id='+sa_uid+']').css("background-color","red","color","white");
            //alert(sa_uid);
            $(this).html(ajax_load);
            $.ajax({
                type: "POST",
                url: loadUrl,
                data: { sa_uid: sa_uid}
            }).done(function( msg ) {
                //alert( "Data Saved: " + msg );
                $('.loading').hide();
                if(msg=='success'){
                    $('tr[id='+sa_uid+']').css("background-color","red","color","white");
                    $('tr[id='+sa_uid+']').fadeOut('slow');
                    //$('#port_of_loading_loader').html(msg);
                }else{
                    alert(msg);
                }
            });
        }else{
            alert('Click the checkbox beside delete button first.');
        }
    });
    /************************************/
    /*
     *		Calculate difference between two times
     */
    calculateDifference();
    $('input[name=sa_start_time]').change(function(){
        //alert('test');
        calculateDifference();
    });
    $('input[name=sa_end_time]').change(function(){
        calculateDifference();
    });
    $('input[name=sa_rate]').change(function(){
        //alert('test');
        calculateDifference();
    });
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
    };
    /************************************/
</script>
<?php //include_once('security_assignment_graph.php');  ?>
