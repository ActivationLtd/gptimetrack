<?php
include_once("config.php");
header("location:time_list.php");
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
  <head>
    <title>Untitled Document</title>
    <?php include_once('inc.head.php') ?>
  </head>
  <body>
    <div id="wrapper">
      <div id="container">
        <div id="top1">
          <?php include('top.php'); ?>
        </div>
        <div id="mid"> <?php echo $company_intro; ?>
          <?php
          /* 	
            if(hasPermission('security_assignment','add',$_SESSION[current_user_id])){
            include('snippets/index/sercurity_assignment_next_7_days.php');
            }
           */
          include('snippets/index/sercurity_assignment_list_current_user.php');
          ?>
          <div id="mid_left">
            <?php //include('snippets/index/security_assi_list_this_user.php')?>
            <!-- <div id="visualization" style="width: 500px; height: 400px; float:left;"></div>--> 
          </div>
          <div id="mid_right">
            <?php //include('snippets/index/your_clients.php')?>
          </div>
        </div>
        <div id="footer">
          <?php include('footer.php'); ?>
        </div>
      </div>
    </div>
  </body>
</html>