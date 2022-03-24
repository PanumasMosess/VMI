<?
require_once("../../application.php");
header("Content-Type:text/html; charset=utf-8");
require_once '../../PHPExcelClasses/PHPExcel.php'; //PHPExcel
include '../../PHPExcelClasses/PHPExcel/IOFactory.php'; //PHPExcel_IOFactory - Reader	

//close error
ini_set('display_errors', 0);
//Turn off all error reporting
error_reporting(0);

//bypass execu time
//ini_set('MAX_EXECUTION_TIME', '-1');

/**********************************************************************************/
/*current user ********************************************************************/
$t_cur_user_code_VMI_GDJ = isset($_SESSION['t_cur_user_code_VMI_GDJ']) ? $_SESSION['t_cur_user_code_VMI_GDJ'] : '';
$t_cur_user_type_VMI_GDJ = isset($_SESSION['t_cur_user_type_VMI_GDJ']) ? $_SESSION['t_cur_user_type_VMI_GDJ'] : '';

$buffer_date = date("Y-m-d");
$buffer_time = date("H:i:s"); //24H
$buffer_datetime = date("Y-m-d H:i:s");

/**********************************************************************************/
/*var *****************************************************************************/
/*
$tmpY = isset($_REQUEST['tmpY']) ? $_REQUEST['tmpY'] : '';
$tmpM = isset($_REQUEST['tmpM']) ? $_REQUEST['tmpM'] : '';

//decode
$tmpY = var_decode($tmpY);
$tmpM = var_decode($tmpM);

//check lenght
if(strlen($tmpM) == 1)
{
	$tmpM_show = "0".$tmpM;
}
else
{
	$tmpM_show = $tmpM;
}
*/

//no memory limit
ini_set('memory_limit', '-1');

// Create new PHPExcel object
$objPHPExcel = new PHPExcel();

$objPHPExcel->getActiveSheet()->setShowGridlines(false); //guiline hide= 'false'
	
//Setting rows/columns to repeat at top/left
$objPHPExcel->getActiveSheet()->getPageSetup()->setRowsToRepeatAtTopByStartAndEnd(1, 3);

//set page margins for a worksheet, use this code
$objPHPExcel->getActiveSheet()->getPageMargins()->setTop(0.75);
$objPHPExcel->getActiveSheet()->getPageMargins()->setRight(0.75);
$objPHPExcel->getActiveSheet()->getPageMargins()->setLeft(0.75);
$objPHPExcel->getActiveSheet()->getPageMargins()->setBottom(0.75);

// Set Orientation, size and scaling
$objPHPExcel->setActiveSheetIndex(0);
$objPHPExcel->getActiveSheet()->getPageSetup()->setOrientation(PHPExcel_Worksheet_PageSetup::ORIENTATION_LANDSCAPE);
$objPHPExcel->getActiveSheet()->getPageSetup()->setPaperSize(PHPExcel_Worksheet_PageSetup::PAPERSIZE_A4);
$objPHPExcel->getActiveSheet()->getPageSetup()->setFitToPage(true);
$objPHPExcel->getActiveSheet()->getPageSetup()->setFitToWidth(1);
$objPHPExcel->getActiveSheet()->getPageSetup()->setFitToHeight(0);

//Headers and Footers
$objPHPExcel->getActiveSheet()
    ->getHeaderFooter()
    ->setOddHeader('&R Page &P / &N
	&D &T')
	->setOddFooter('&L Printed on &D &T &R Glong Duang Jai Co., Ltd.');
		
$styleArray = array(
			'font'  => array(
				'bold'  => true
			));			

// Set fonts
$objPHPExcel->getActiveSheet()->getStyle('A1:F2')->getFont()->getColor()->setARGB(PHPExcel_Style_Color::COLOR_BLACK);
$objPHPExcel->getActiveSheet()->getStyle('A1:F2')->getFont()->setBold(true);
$objPHPExcel->getActiveSheet()->getStyle("A1:F2")->getFont()->setSize(10);

//header
$objPHPExcel->setActiveSheetIndex(0)->mergeCells('A1:F1');
$objPHPExcel->getActiveSheet()->setCellValue('A1', "Report FG (ft^2) Mst On $buffer_datetime");

//border		
$objPHPExcel->getActiveSheet()->getStyle('A1:F2')->applyFromArray($styleArray);
$objPHPExcel->getActiveSheet()->getStyle('A1:F2')->getBorders()->getAllBorders()->setBorderStyle(PHPExcel_Style_Border::BORDER_THIN);

//Set BG
$objPHPExcel->getActiveSheet()->getStyle('A2:F2')->getFill()->setFillType(PHPExcel_Style_Fill::FILL_SOLID)->getStartColor()->setARGB('FF00FFFF');

//Set main
//$objPHPExcel->getActiveSheet()->getStyle('A3:I3')->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_LEFT);
//$objPHPExcel->getActiveSheet()->getStyle('J3:L3')->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_RIGHT);
//$objPHPExcel->getActiveSheet()->getStyle('M3:R3')->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_LEFT);
	
// Set fonts
$objPHPExcel->getActiveSheet()->getStyle('A2:F2')->getFont()->getColor()->setARGB(PHPExcel_Style_Color::COLOR_BLACK);
$objPHPExcel->getActiveSheet()->getStyle('A2:F2')->getFont()->setBold(true);
$objPHPExcel->getActiveSheet()->getStyle('A2:F2')->getFont()->setSize(10);

$objPHPExcel->getActiveSheet()->setCellValue('A2', "FG Code");
$objPHPExcel->getActiveSheet()->setCellValue('B2', "ft2");
$objPHPExcel->getActiveSheet()->setCellValue('C2', "Issue By");
$objPHPExcel->getActiveSheet()->setCellValue('D2', "Issue Date");
$objPHPExcel->getActiveSheet()->setCellValue('E2', "Issue Time");
$objPHPExcel->getActiveSheet()->setCellValue('F2', "Issue Datetime");

//get data
$strSql_d = " 
SELECT * FROM tbl_fg_ft2_mst
";

$i = 2;
$str_sum_stock = 0;

$objQuery_d = sqlsrv_query($db_con, $strSql_d);
while($objResult_d = sqlsrv_fetch_array($objQuery_d, SQLSRV_FETCH_ASSOC))
{
	$i++;

	$ft2_id = $objResult_d['ft2_id'];
	$ft2_fg_code = $objResult_d['ft2_fg_code'];
	$ft2_value = $objResult_d['ft2_value'];
	$ft2_issue_by = $objResult_d['ft2_issue_by'];
	$ft2_issue_date = $objResult_d['ft2_issue_date'];
	$ft2_issue_time = $objResult_d['ft2_issue_time'];
	$ft2_issue_datetime = $objResult_d['ft2_issue_datetime'];
	
	//Set main
	//$objPHPExcel->getActiveSheet()->getStyle('F' .($i). ':'.'F'.($i))->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_RIGHT);
	
	//border
	$objPHPExcel->getActiveSheet()->getStyle('A' .$i. ':'.'F'.($i))->getBorders()->getAllBorders()->setBorderStyle(PHPExcel_Style_Border::BORDER_THIN);
	
	//data
	/*
	$objPHPExcel->getActiveSheet()->setCellValueExplicit('A' . $i, $tags_code, PHPExcel_Cell_DataType::TYPE_STRING);
	$objPHPExcel->getActiveSheet()->setCellValueExplicit('B' . $i, $tags_fg_code_gdj, PHPExcel_Cell_DataType::TYPE_STRING);
	$objPHPExcel->getActiveSheet()->setCellValue('C' . $i, $tags_fg_code_gdj_desc, PHPExcel_Cell_DataType::TYPE_STRING);
	$objPHPExcel->getActiveSheet()->setCellValue('D' . $i, $receive_pallet_code);
	$objPHPExcel->getActiveSheet()->setCellValue('E' . $i, $receive_location);
	$objPHPExcel->getActiveSheet()->setCellValue('F' . $i, number_format($tags_packing_std), PHPExcel_Cell_DataType::TYPE_STRING);
	$objPHPExcel->getActiveSheet()->setCellValue('G' . $i, $receive_status);
	$objPHPExcel->getActiveSheet()->setCellValue('H' . $i, $receive_date);
	*/	
	
	$objPHPExcel->getActiveSheet()->setCellValue('A' . $i, $ft2_fg_code);
	$objPHPExcel->getActiveSheet()->setCellValue('B' . $i, $ft2_value);
	$objPHPExcel->getActiveSheet()->setCellValue('C' . $i, $ft2_issue_by);
	$objPHPExcel->getActiveSheet()->setCellValue('D' . $i, $ft2_issue_date);
	$objPHPExcel->getActiveSheet()->setCellValue('E' . $i, $ft2_issue_time);
	$objPHPExcel->getActiveSheet()->setCellValue('F' . $i, $ft2_issue_datetime);
}

// Rename sheet 
$objPHPExcel->getActiveSheet()->setTitle('Report FG (ft^2) Mst');

$objPHPExcel->setActiveSheetIndex(0);
// Redirect output to a client’s web browser (Excel5)
header('Content-Type: application/vnd.ms-excel');
header('Content-Disposition: attachment;filename="Report FG (ft^2) Mst ('.$buffer_datetime.').xls"');
header('Cache-Control: max-age=0');

$objWriter = PHPExcel_IOFactory::createWriter($objPHPExcel, 'Excel5');
$objWriter->save('php://output');
exit;
?>