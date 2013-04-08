<div class="filter">
  <form action="" method="post">
    <table>
    	<tr>
      	<td>
        	Client <br />
          <?php
					$selectedIdCsv = $client_id_csv;
					$customQuery = " WHERE client_active='1' ";
					createMultiSelectOptions('client', 'client_id', 'client_company_name', $customQuery, $selectedIdCsv, 'client_id[]', " multiple='multiple' class='multiselectdd  validate[required] '");
					?>
        </td>
        <td>
        	Project <br />
          <?php
					$selectedIdCsv = $project_id_csv;
					$customQuery = " WHERE project_active='1' ";
					createMultiSelectOptions('project', 'project_id', 'project_name', $customQuery, $selectedIdCsv, 'project_id[]', " multiple='multiple' class='multiselectdd'");
					?>
        </td>
        <td>
        	User <br />
          <?php
					$selectedIdCsv = $user_id_csv;
					//$customQuery = " WHERE user_active='1'  AND user_type_id='3'";
					$customQuery = " WHERE user_active='1'  ";
					createMultiSelectOptions('user', 'user_id', 'user_fullname', $customQuery, $selectedIdCsv, 'user_id[]', " multiple='multiple' class='multiselectdd'");
					?>
        </td>
        <td>
        	Start date <br />
          <input name="date_start" type="text" value="<?php echo addEditInputField('date_start'); ?>" size="20" readonly="readonly" style="float: none; width:80px;" />
          <input name="date_start_datetime" id="date_start_datetime" type="hidden" value="<?php echo addEditInputField('date_start_datetime'); ?>" size="20" class="" readonly="readonly" />
          <script>
							$("input[name=date_start]").datepicker({
									dateFormat: "dd-mm-yy",
									altField: "#date_start_datetime",
									altFormat: "yy-mm-dd",
									onSelect: function() {
											disableWeekMonthYear();
									}
							});
					</script>
        </td>
        <td>
        	End date <br />
          <input name="date_end" type="text" value="<?php echo addEditInputField('date_end'); ?>" size="20" class="" readonly="readonly" style="float: none;width:80px;" />
          <input name="date_end_datetime" id="date_end_datetime" type="hidden" value="<?php echo addEditInputField('date_end_datetime'); ?>" size="20" class="" readonly="readonly" />
          <script>
							$("input[name=date_end]").datepicker({
									dateFormat: "dd-mm-yy",
									altField: "#date_end_datetime",
									altFormat: "yy-mm-dd",
									onSelect: function() {
											disableWeekMonthYear();
									}
							});
							/*
							function disableWeekMonthYear(){
									var date_start_datetime= $("#date_start_datetime").val();
									var date_end_datetime= $("#date_end_datetime").val();
									
									if (date_end_datetime != "" && date_start_datetime != ""){
											$("#week_number").val("");
											$("#week_number").attr("disabled", "disabled");
											$("#month").val("");
											$("#month").attr("disabled", "disabled");
											$("#year").val("");
											$("#year").attr("disabled", "disabled");
									}
							}
							*/
							
					</script>
        </td>
        <td>
        	Week <br />
          <input id="week_number" name="week_number" type="text" value="<?php echo addEditInputField('week_number'); ?>" size="2" class="validate[min[1], max[54], custom[number]]" style="float: none; width:25px" />
        	<script type="text/javascript">
							$("#week_number").blur(function (){ loadCurrentYear(); });
							function loadCurrentYear(){                                                                                                                
									var currentYear = (new Date).getFullYear();
									$("#year").val(currentYear);
							}    
					</script></td>
        <td>
        	Year <br />
          <input id ="year" name="year" type="text" value="<?php echo addEditInputField('year'); ?>" size="4" class="validate[min[2012], max[2014], custom[number]]" style="float: none;width:40px" />
        </td>
      </tr>
    </table>
    <div class="clear"></div>
    <input type="submit" name="submit" value="Filter" class="bgblue button" />
    <a href="<?=$_SERVER['PHP_SELF']?>" class='button bgblue'>Reset</a>
  </form>
</div>
