<div id="header">
<div class="logo">
  <h1><?php echo $app_name;?></h1>
  <div class="clear"></div>
  <?php //echo $company_name;?>
</div>
<div id="top2">
<div id="topmenu">
<?php 
  if($_SESSION[logged]){

		//echo "<a class='homepage_menu topmenu_item' href='index.php'><img src='images/home-menu-icon.png' align='middle' /> Home</a>"; 
		if(hasPermission('time','add',$_SESSION[current_user_id])){
			echo "<a class='homepage_menu topmenu_item' href='time_list.php'><img src='images/security-assignment.png' align='middle' /> Time </a> ";
		}
	
		if(hasPermission('client','view',$_SESSION[current_user_id])){
			echo "<a class='homepage_menu topmenu_item'href='client_list.php'><img src='images/registered-buyer-menu-icon.png' align='middle' />Client </a> ";
		}
		if(hasPermission('user','view',$_SESSION[current_user_id])){
			echo "<a class='homepage_menu topmenu_item'href='user_list.php'><img src='images/users.png' align='middle' />User </a> ";
		}
	
		if(hasPermission('project','view',$_SESSION[current_user_id])){
			echo "<a class='homepage_menu topmenu_item' href='project_list.php'><img src='images/site.png' align='middle' />Projects </a> ";
		}
		if(hasPermission('project','add',$_SESSION[current_user_id])){
  		echo "<a class='homepage_menu topmenu_item' href='report.php'><img src='images/security-assignment.png' align='middle' /> Report </a> ";
  	}
		if(hasPermission('project','add',$_SESSION[current_user_id])){
  		echo "<a class='homepage_menu topmenu_item' href='report_generic.php'><img src='images/security-assignment.png' align='middle' /> Report GP format </a> ";
  	}
	  
  }?>



  </div>

        



</div>



  

  <div class="user_info">

    <?php if($_SESSION[logged]){

		echo "Welcome! <b>".$_SESSION[current_user_fullname]; 

		//echo " [L:".currentUserLevel()." | T:".currentUserTypeId()."]";

		echo "</b><br><a href=\"logout.php\">logout</a><br>";

		echo date("F j, Y"); 

	}?>

  </div>

</div>

<div style="clear:both;"></div>

<div class="clear"></div>

