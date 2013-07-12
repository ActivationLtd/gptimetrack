<?php
include_once("config.php");
if (!hasPermission('time', 'add', $_SESSION[current_user_id])) {
  echo "Permission denied";
  exit();
}

/* * **************************************** */
/* Add/Edit Partner Artist
  /****************************************** */
$valid = true;
$alert = array();
$param = $_REQUEST['param'];
$time_id = $_REQUEST['time_id'];

if ($param == 'delete') {
  $sql = "DELETE FROM time WHERE time_id='$time_id'";
  $r = mysql_query($sql) or die(mysql_error() . "<b>Query:</b><br>___<br>$sql<br>");
  insertLog('Time', 'Time deleted', 'time', 'time_id', $time_id, $sql, $_SESSION[current_user_id], print_r($_SERVER, true));
  header('location:' . $_SERVER['PHP_SELF']);
}
if (!strlen($param)) {
  $param = 'add';
}

/*
  if(!strlen($time_id)){
  $param="add";
  }else{
  $param="edit";
  }
 */
if (isset($_POST[submit])) {
  if ($param == 'add' || $param == 'edit') {
    $exception_field = array('submit', 'param');
    /*
     * 	server side validation
     */
    if (empty($_POST["time_project_id"])) {
      $valid = false;
      array_push($alert, "Please give a valid project");
    }
    if (empty($_POST["time_total"])) {
      $valid = false;
      array_push($alert, "Please give a valid time_total");
    }
    /*     * ********************************** */
    if ($valid) {
      if ($param == 'add') {
        /*
         * 	Check whether current user has permission to add client
         */
        if (hasPermission('Time', 'add', $_SESSION[current_user_id])) {
          //$time_uid=makeRandomKey();
          /*           * ********************************** */
          /*
           * 	Create the insert query substring.
           */
          $str = createMySqlInsertString($_POST, $exception_field);
          $str_k = $str['k'];
          $str_v = $str['v'];
          /*           * ********************************** */
          $sql = "INSERT INTO time($str_k,time_updated_datetime) values ($str_v,now())";
          mysql_query($sql) or die(mysql_error() . "<b>Query:</b><br>$sql<br>");
          $time_id = mysql_insert_id();
          insertLog('Time', 'Time added', 'time', 'time_id', $time_id, $sql, $_SESSION[current_user_id], print_r($_SERVER, true));
          //$param='edit';
          array_push($alert, "The project has been saved!");
          //header("time_list.php?param=success");
        } else {
          $valid = false;
          array_push($alert, "You don't have permission to add project");
        }
      } else if ($param == 'edit') {
        /*
         * 	Check whether current user has permission to edit client
         */
        if (hasPermission('time', 'edit', $_SESSION[current_user_id])) {
          /*
           * 	Create the update query substring.
           */
          $str = createMySqlUpdateString($_REQUEST, $exception_field);
          /*           * ********************************** */
          $sql = "UPDATE time set $str where time_id='" . $_REQUEST['time_id'] . "'";
          mysql_query($sql) or die(mysql_error() . "<b>Query:</b><br>___<br>$sql<br>");
          insertLog('Time', 'Time updated', 'time', 'time_id', $time_id, $sql, $_SESSION[current_user_id], print_r($_SERVER, true));
          array_push($alert, "The project has been saved!");
        } else {
          $valid = false;
          array_push($alert, "You don't have permission to edit project");
        }
      }
      //echo $sql;
    }
  }
}


/* * **************************************** */
if ($time_id) {
  $sql = "SELECT * FROM time WHERE time_id='$time_id' ";
  $r = mysql_query($sql) or die(mysql_error() . "<b>Query:</b><br>___<br>$sql<br>");
  $a = mysql_fetch_assoc($r);
  $rows = mysql_num_rows($r);
}

$startdate = date("Y-m-d", strtotime("-2 month"));
$extended_query = "WHERE time_user_id='" . $_SESSION['current_user_id'] . "' AND time_date>'$startdate'  ";

$sql = "SELECT * FROM time " . $extended_query;
//echo "<b>Query:</b><br>___<br>$sql<br>";
$r = mysql_query($sql) or die(mysql_error() . "<b>Query:</b><br>___<br>$sql<br>");

$arr = mysql_fetch_rowsarr($r);
$rows = mysql_num_rows($r);
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html>
  <head>
    <?php include_once('inc.head.php') ?>
    <style>
      #datatable > thead{background-color:#000000; color:#FFF;}
      .dataTable{margin:10px 0px 0px;}
      .ui-combobox, .ui-combobox-input{width:220px;}
    </style>
  </head>
  <body>
    <div id="wrapper">
      <div id="container">
        <div id="top1">
          <?php include('top.php'); ?>
        </div>
        <?php //include("snippets/account_management/account_management_menu.php"); ?>
        <div id="mid">
          <div class="clear"></div>
          <div id="left_m">
            <table width="100%">
              <tr>
                <td>
                  <h2>
                    <?php echo ucfirst($param); ?>
                    Time<br />
                  </h2>
                </td>
                <td align="right">
                  <?php if ($param != "add") { ?><a href="<?php echo $_SERVER['PHP_SELF']; ?>">[+] Add </a><?php } ?>
                </td>
              </tr>
            </table>
            <?php printAlert($valid, $alert); ?>
            <form action="#" method="post" enctype="multipart/form-data">
              <table width="100%">
                <tr>
                  <td>
                    User:  
                    <?php
                    if (currentUserIsGeneralUser()) {
                      $additionalParam = " disabled='disabled'";
                      echo getUserFullNameFrmId($_SESSION['current_user_id']);
                      ?>
                      <input name="time_user_id" value="<?= $_SESSION['current_user_id'] ?>" type="hidden" />
                      <?php
                    } else {
                      $selectedId = addEditInputField('time_user_id');
                      if (!strlen($selectedId)) {
                        $selectedId = $_SESSION['current_user_id'];
                      }
                      //echo "selectedId".$selectedId;
                      $customQuery = " WHERE user_active='1' ";
                      createSelectOptions('user', 'user_id', 'user_fullname', $customQuery, $selectedId, 'time_user_id', "class='validate[required] selectmenu' $additionalParam");
                    }
                    ?>
                  </td>
                </tr>

                <tr>
                  <td>
                    <!--time_project_id-->
                    Project:<br/> 
                    <?php
                    $selectedId = addEditInputField('time_project_id');
                    $customQuery = " WHERE project_active='1' ";
                    createProjectSelectOptions('project', 'project_id', 'project_name', $customQuery, $selectedId, 'time_project_id', "id='combobox' class='validate[required]'");
                    ?>
                  </td>
                  <tr>
                    <td>
                      <table>
                        <tr>
                          <td>
                            <!--time_date -->
                            Date:<br/> 
                            <script>
                              $(function() {
                                $("input[name=time_date]").datepicker({
                                  dateFormat: 'yy-mm-dd',
                                  separator: ' ',
                                });
                              });
                            </script>
                            <input name="time_date" type="text" value="<?php
                            if (strlen(addEditInputField('time_date'))) {
                              echo addEditInputField('time_date');
                            } else {
                              echo date('Y-m-d');
                            }
                            ?>" size="10" class="validate[required]" readonly="readonly" />
                          </td>
                          <td>
                            <!--time_total-->
                            Hour(s):<br/>
                            <input name="time_total" type="text" value="<?php echo addEditInputField('time_total'); ?>" size="4" maxlength="4" class="validate[required,custom[number]]" />
                          </td>
                          <td>
                            Task Type : <br />
                            <?php
                            $selectedId = addEditInputField('time_activity_type');
                            createSelectOptionsFrmArray($time_activity_type_array, $selectedId, "time_activity_type", " class='validate[required]'")
                            ?>
                          </td>
                        </tr>
                      </table>                    
                    </td>
                  </tr>
                  <tr>
                    <td>
                      <!--time_description-->
                      Task Details:<br/>
                      <textarea name="time_description" cols="30" rows="6" class="validate[required]"><?php echo addEditInputField('time_description'); ?></textarea>
                    </td>
                  </tr>

              </table>
              <input name="submit" type="submit" class="bgblue button" value="Save" />
              <input name="reset" type="reset" class="bgblue button" value="Reset" />
              <input type="hidden" name="time_updated_by_user_id" value="<?php echo $_SESSION["current_user_id"]; ?>" />
              <?php if ($time_id && $param == 'edit') { ?>
                <input type="hidden" name="time_id" value="<?php echo $time_id; ?>" />
              <?php }
              ?>
            </form>
            <div class="clear"></div>
          </div>
          <div id="right_m">
            <!--<h2>List of Customers</h2>-->
            <table id="datatable_time" width="100%">
              <thead>
                <tr>
                  <th>time_date</th>
                  <th>time_project_id</th>
                  <th>time_total</th>                  
                  <th>time_user_id</th>                  
                  <th>time_description</th>
                  <th>time_activity_type</th>
                  <th>time_updated_datetime</th>
                  <th>Status</th>
                  <th>Action</th>
                </tr>
                <tr class="filterInput">                  
                  <td><input type="text" name="time_date_search" value="" class="search_init" /></td>
                  <td><input type="text" name="time_project_id_search" value="" class="search_init" /></td>
                  <td><input type="text" name="time_total_search" value="" class="search_init" /></td>                  
                  <td><input type="text" name="time_user_id_search" value="" class="search_init" /></td>                  
                  <td><input type="text" name="time_description_search" value="" class="search_init" /></td>                
                  <td><input type="text" name="time_activity_type_search" value="" class="search_init" /></td>                
                  <td><input type="text" name="time_updated_datetime_search" value="" class="search_init" /></td>
                  <td><input type="text" name="Status_search" value="" class="search_init" /></td>
                  <td><input type="text" name="Action_search" value="" class="search_init" /></td>                  
                </tr>
              </thead>
              <tbody>
                <?php for ($i = 0; $i < $rows; $i++) { ?>
                  <tr>
                    <td><span style="width:80px; float:left"><?php echo $arr[$i][time_date]; ?></span></td>
                    <td><?php echo getProjectNameFrmId($arr[$i][time_project_id]); ?></td>
                    <td><?php echo $arr[$i][time_total]; ?></td>                    
                    <td><?php echo getUserFullNameFrmId($arr[$i][time_user_id]); ?></td>                    
                    <td><?php echo $arr[$i][time_description]; ?></td>
                    <td><?php echo $arr[$i][time_activity_type]; ?></td>
                    <td><?php echo $arr[$i][time_updated_datetime]; ?></td>
                    <td><?php echo getActiveStatus($arr[$i][time_active]); ?></td>
                    <td>
                      <span style="width:125px;float:left;">
                        <?php
                        if (hasPermission('time', 'edit', $_SESSION[current_user_id])) {
                          echo "<a href='time_list.php?time_id=" . $arr[$i][time_id] . "&param=edit'>Edit</a>";
                          echo " | <a href='time_list.php?time_id=" . $arr[$i][time_id] . "&param=delete'>Delete</a>";
                        }
                        ?>
                      </span>
                    </td>
                  </tr>
                <?php } ?>
              </tbody>
            </table>
          </div>
        </div>
      </div>
      <div id="footer">
        <?php include('footer.php'); ?>
      </div>
    </div>
  </body>
  <script type="text/javascript" charset="utf-8">
    var asInitVals = new Array();
    $(document).ready(function() {
      oTable = $('#datatable_time').dataTable({
        "bPaginate": false,
        "bStateSave": false,
        "oLanguage": {
          "sSearch": "Search all columns:"
        },
        "bSortCellsTop": true,
        "aaSorting": [[0, "desc"]]
      });


      $("thead input").keyup(function() {
        /* Filter on the column (the index) of this element */
        oTable.fnFilter(this.value, $("thead input").index(this));
        var index = $("thead input").index(this);
        index++;
        //alert(index);
        $("#report_table tbody tr td:nth-child(" + index + ")").removeHighlight();
        $("#report_table tbody tr td:nth-child(" + index + ")").highlight($(this).val());
      });



      /*
       * Support functions to provide a little bit of 'user friendlyness' to the textboxes in
       * the footer
       */
      $("thead input").each(function(i) {
        asInitVals[i] = this.value;
      });
      /*
       * Support functions to provide a little bit of 'user friendlyness' to the textboxes in
       * the footer
       */
      $("thead input").each(function(i) {
        asInitVals[i] = this.value;
      });

      $("thead input").focus(function() {
        if (this.className == "search_init")
        {
          this.className = "search_init_focus";
          this.value = "";
        }
      });

      $("thead input").blur(function(i) {
        if (this.value == "")
        {
          this.className = "search_init";
          this.value = asInitVals[$("thead input").index(this)];
        }
      });

    });
  </script>
</html>
