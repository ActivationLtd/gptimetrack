<?php 
include('config.php');
/****************************************************************
* PHPExcel included 
****************************************************************/
//ini_set('include_path', ini_get('include_path').';../Classes/');
/** PHPExcel */
include_once('PHPExcel/Classes/PHPExcel.php');
/** PHPExcel_Writer_Excel2007 */
include_once('PHPExcel/Classes/PHPExcel/Writer/Excel2007.php');
$query_string=$_REQUEST[sql_query_string];
$r=mysql_query($query_string)or die(mysql_error());
$rows=mysql_num_rows($r);
if($rows>0){
	$arr=mysql_fetch_rowsarr($r);
	//print_r($arr);
}
// Create new PHPExcel object 
$objPHPExcel = new PHPExcel();
$objPHPExcel->setActiveSheetIndex(0);
$objPHPExcel->getActiveSheet()->setCellValue('A1', 'Week');
$objPHPExcel->getActiveSheet()->setCellValue('B1', 'Start date');
$objPHPExcel->getActiveSheet()->setCellValue('C1', 'Day');
$objPHPExcel->getActiveSheet()->setCellValue('D1', 'Start');
$objPHPExcel->getActiveSheet()->setCellValue('E1', 'End');
$objPHPExcel->getActiveSheet()->setCellValue('F1', 'Requested by');
$objPHPExcel->getActiveSheet()->setCellValue('G1', 'Company /Client');
$objPHPExcel->getActiveSheet()->setCellValue('H1', 'Site');
$objPHPExcel->getActiveSheet()->setCellValue('I1', 'Guard');
$objPHPExcel->getActiveSheet()->setCellValue('J1', 'Rate');
$objPHPExcel->getActiveSheet()->setCellValue('K1', 'Hours');
$objPHPExcel->getActiveSheet()->setCellValue('L1', 'Amount');
$objPHPExcel->getActiveSheet()->setCellValue('M1', 'Comment');
// Add data
for($i=0;$i<$rows;$i++){
	$objPHPExcel->getActiveSheet()->setCellValue('A' . ($i+2), "W".date("W", strtotime($arr[$i][sa_start_time])))
	                              ->setCellValue('B' . ($i+2), date('d-M-Y',strtotime($arr[$i][sa_start_time])))
								  ->setCellValue('C' . ($i+2), date('l',strtotime($arr[$i][sa_start_time])))
	                              ->setCellValue('D' . ($i+2), date('H:i',strtotime($arr[$i][sa_start_time])))
	                              ->setCellValue('E' . ($i+2), date('H:i',strtotime($arr[$i][sa_end_time])))
	                              ->setCellValue('F' . ($i+2), getUserFullNameFrmId($arr[$i][sa_requested_by_user_id]))
								  ->setCellValue('G' . ($i+2), getClientCompanyNameFrmId($arr[$i][sa_client_id]))
								  ->setCellValue('H' . ($i+2), getSiteNameFrmId($arr[$i][sa_site_id]))
								  ->setCellValue('I' . ($i+2), getUserFullNameFrmId($arr[$i][sa_guard_user_id]))
								  ->setCellValue('J' . ($i+2), $arr[$i][sa_rate])
								  ->setCellValue('K' . ($i+2), round(my_hour_diff($arr[$i][sa_start_time],$arr[$i][sa_end_time]),2))
								  ->setCellValue('L' . ($i+2), round(my_hour_diff($arr[$i][sa_start_time],$arr[$i][sa_end_time])*$arr[$i][sa_rate],2))
								  
								  ->setCellValue('M' . ($i+2), nl2br($arr[$i][sa_comment])); 
								  
}
// Set active sheet index to the first sheet, so Excel opens this as the first sheet
$objPHPExcel->setActiveSheetIndex(0);
$t=time();
$file_name = "security_assignment_list_".$t.".xls";
// Redirect output to a client's web browser (Excel5)
header('Content-Type: application/vnd.ms-excel');
header('Content-Disposition: attachment;filename='.$file_name);
header('Cache-Control: max-age=0');
$objWriter = PHPExcel_IOFactory::createWriter($objPHPExcel, 'Excel5');
$objWriter->save('php://output');
exit;
?>