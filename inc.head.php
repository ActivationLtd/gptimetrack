<?php include_once('inc.css-js.php');?>
<script type="text/javascript">
$(document).ready(function($) {
	/*
	*	Initiate Auto grow on all text area
	*/
	$('textarea').autogrow();
	/*Initiate select menu */
	//$('select.selectmenu').selectmenu();
	//http://wiki.jqueryui.com/w/page/12138056/Selectmenu
	/*******************************************************************************/
	/*
	*	Initiate the forms validator Engine for all HTML FORM throughout the sytem
	*/
	$("form").validationEngine();
	/*******************************************************************************/	
	/*Initiate facebox */
	$('a[rel*=facebox]').facebox();
	/*******************************************************************************/
	/*Initiate datatable */
	$('table#datatable').dataTable({
    	"sPaginationType": "full_numbers",
		"aaSorting": [[ 0, "desc" ]],
		"iDisplayLength" : 100,
		"bStateSave": true
  	});
	$('table#datatable_min').dataTable({
    	//"sPaginationType": "two_button",
		"bPaginate": false,
		"bLengthChange": false,
		"bInfo": false,
		"bFilter": false,
		"iDisplayLength" : 100,
		"aaSorting": [[ 0, "desc" ]],
		"bStateSave": true
  	});
	$('table#datatable_nopagination').dataTable({
    	"bPaginate": false,
		"bInfo": false,
		"iDisplayLength" : 100,
		//"bFilter": false,
		"aaSorting": [[ 0, "desc" ]],
		"bStateSave": true
  	});
	/*******************************************************************************/	

	/*Initiate datepicker */
	$("#datepicker").datepicker({ dateFormat: "yy-mm-dd" });
	/*******************************************************************************/	

	/*******************************************************************************/	
	/*Initiate multiselect */
	$(".multiselect").multiselect();
	/*******************************************************************************/

	/*******************************************************************************/	
	/*Initiate multiselect dropdwon */
	
	$(".multiselectdd").multiselectdd();
	/*******************************************************************************/	
})
/*
* 	Common js functions
*/
function countChecked() {
  var n = $("input:checked").length;
  return n;
}

/**************************************/
</script>





