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
	->setOddFooter('&L Printed on &D &T &R GLONGDUANGJAI MANUFACTURING CO., LTD.');
		
$styleArray = array(
			'font'  => array(
				'bold'  => true
			));			

// Set fonts
$objPHPExcel->getActiveSheet()->getStyle('A1:E2')->getFont()->getColor()->setARGB(PHPExcel_Style_Color::COLOR_BLACK);
$objPHPExcel->getActiveSheet()->getStyle('A1:E2')->getFont()->setBold(true);
$objPHPExcel->getActiveSheet()->getStyle("A1:E2")->getFont()->setSize(10);

//header
$objPHPExcel->setActiveSheetIndex(0)->mergeCells('A1:E1');
$objPHPExcel->getActiveSheet()->setCellValue('A1', "Report FG Stock By FG Code On $buffer_datetime");

//border		
$objPHPExcel->getActiveSheet()->getStyle('A1:E2')->applyFromArray($styleArray);
$objPHPExcel->getActiveSheet()->getStyle('A1:E2')->getBorders()->getAllBorders()->setBorderStyle(PHPExcel_Style_Border::BORDER_THIN);

//Set BG
$objPHPExcel->getActiveSheet()->getStyle('A2:E2')->getFill()->setFillType(PHPExcel_Style_Fill::FILL_SOLID)->getStartColor()->setARGB('FF00FFFF');

//Set main
//$objPHPExcel->getActiveSheet()->getStyle('A3:I3')->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_LEFT);
//$objPHPExcel->getActiveSheet()->getStyle('J3:L3')->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_RIGHT);
//$objPHPExcel->getActiveSheet()->getStyle('M3:R3')->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_LEFT);
	
// Set fonts
$objPHPExcel->getActiveSheet()->getStyle('A2:E2')->getFont()->getColor()->setARGB(PHPExcel_Style_Color::COLOR_BLACK);
$objPHPExcel->getActiveSheet()->getStyle('A2:E2')->getFont()->setBold(true);
$objPHPExcel->getActiveSheet()->getStyle('A2:E2')->getFont()->setSize(10);


$objPHPExcel->getActiveSheet()->setCellValue('A2', "FG Code GDJ");
$objPHPExcel->getActiveSheet()->setCellValue('B2', "FG Code GDJ Desc.");
$objPHPExcel->getActiveSheet()->setCellValue('C2', "Project");
$objPHPExcel->getActiveSheet()->setCellValue('D2', "Qty. (Pcs.)");
$objPHPExcel->getActiveSheet()->setCellValue('E2', "Stock Aging (Day)");

//get data
$strSql_d = " 
SELECT 
	tags_fg_code_gdj
	,tags_fg_code_gdj_desc
	,tags_project_name
	,sum(tags_packing_std) as sum_qty
	,MIN(receive_date) AS receive_date
FROM tbl_pallet_running
left join tbl_receive
on tbl_pallet_running.pallet_code = tbl_receive.receive_pallet_code
left join tbl_tags_running
on tbl_receive.receive_tags_code = tbl_tags_running.tags_code
where
receive_status in ('Received')
and tags_fg_code_gdj is not null
and receive_repn_id is null
group by
tags_fg_code_gdj
,tags_fg_code_gdj_desc
,tags_project_name
order by 
tags_fg_code_gdj asc
";

$i = 2;
$str_sum_stock = 0;

$objQuery_d = sqlsrv_query($db_con, $strSql_d);
while($objResult_d = sqlsrv_fetch_array($objQuery_d, SQLSRV_FETCH_ASSOC))
{
	$i++;
	
	$tags_fg_code_gdj = $objResult_d['tags_fg_code_gdj'];
	$tags_fg_code_gdj_desc = $objResult_d['tags_fg_code_gdj_desc'];
	$tags_project_name = $objResult_d['tags_project_name'];
	$sum_qty = $objResult_d['sum_qty'];
	$receive_date = $objResult_d['receive_date'];

	//Age stock;
	$date_receive=date_create($receive_date);
	$date_now=date_create($buffer_date);
	$diff=date_diff($date_receive,$date_now);

	
	//total count
	$str_sum_stock = $str_sum_stock + $sum_qty;
	
	//Set main
	$objPHPExcel->getActiveSheet()->getStyle('D' .($i). ':'.'D'.($i))->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_RIGHT);
	
	//border
	$objPHPExcel->getActiveSheet()->getStyle('A' .$i. ':'.'E'.($i))->getBorders()->getAllBorders()->setBorderStyle(PHPExcel_Style_Border::BORDER_THIN);
	
	//data
	$objPHPExcel->getActiveSheet()->setCellValueExplicit('A' . $i, $tags_fg_code_gdj, PHPExcel_Cell_DataType::TYPE_STRING);
	$objPHPExcel->getActiveSheet()->setCellValue('B' . $i, $tags_fg_code_gdj_desc, PHPExcel_Cell_DataType::TYPE_STRING);
	$objPHPExcel->getActiveSheet()->setCellValue('C' . $i, $tags_project_name, PHPExcel_Cell_DataType::TYPE_STRING);
	$objPHPExcel->getActiveSheet()->setCellValue('D' . $i, number_format($sum_qty), PHPExcel_Cell_DataType::TYPE_STRING);
	$objPHPExcel->getActiveSheet()->setCellValue('E' . $i, $diff->format("%a"));
}
	
$i = $i + 1;
	
//merge
//$objPHPExcel->setActiveSheetIndex(0)->mergeCells('A' .$i. ':'.'I'.($i));
$objPHPExcel->setActiveSheetIndex(0)->mergeCells('A' .$i. ':'.'C'.($i));

//border
$objPHPExcel->getActiveSheet()->getStyle('A' .$i. ':'.'D'.($i))->getBorders()->getAllBorders()->setBorderStyle(PHPExcel_Style_Border::BORDER_THIN);

//Set main
$objPHPExcel->getActiveSheet()->getStyle('A' .($i). ':'.'D'.($i))->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_RIGHT);

//Set BG
$objPHPExcel->getActiveSheet()->getStyle('D' .($i). ':'.'D'.($i))->getFill()->setFillType(PHPExcel_Style_Fill::FILL_SOLID)->getStartColor()->setARGB('FFFFFF00');

//data
$objPHPExcel->getActiveSheet()->setCellValue('A' . $i, 'Total Qty.:');
$objPHPExcel->getActiveSheet()->setCellValueExplicit('D' . $i, number_format($str_sum_stock), PHPExcel_Cell_DataType::TYPE_STRING);

// Rename sheet 
$objPHPExcel->getActiveSheet()->setTitle('Report FG Stock By FG Code');

$objPHPExcel->setActiveSheetIndex(0);
// Redirect output to a client’s web browser (Excel5)
header('Content-Type: application/vnd.ms-excel');
header('Content-Disposition: attachment;filename="Report FG Stock By FG Code ('.$buffer_datetime.').xls"');
header('Cache-Control: max-age=0');

$objWriter = PHPExcel_IOFactory::createWriter($objPHPExcel, 'Excel5');
$objWriter->save('php://output');
exit;
?>