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
$objPHPExcel->getActiveSheet()->getStyle('A1:H2')->getFont()->getColor()->setARGB(PHPExcel_Style_Color::COLOR_BLACK);
$objPHPExcel->getActiveSheet()->getStyle('A1:H2')->getFont()->setBold(true);
$objPHPExcel->getActiveSheet()->getStyle("A1:H2")->getFont()->setSize(10);

//header
$objPHPExcel->setActiveSheetIndex(0)->mergeCells('A1:I1');
$objPHPExcel->getActiveSheet()->setCellValue('A1', "Report FG Stock By Pallet On $buffer_datetime");

//border		
$objPHPExcel->getActiveSheet()->getStyle('A1:I2')->applyFromArray($styleArray);
$objPHPExcel->getActiveSheet()->getStyle('A1:I2')->getBorders()->getAllBorders()->setBorderStyle(PHPExcel_Style_Border::BORDER_THIN);

//Set BG
$objPHPExcel->getActiveSheet()->getStyle('A2:I2')->getFill()->setFillType(PHPExcel_Style_Fill::FILL_SOLID)->getStartColor()->setARGB('FF00FFFF');

//Set main
//$objPHPExcel->getActiveSheet()->getStyle('A3:I3')->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_LEFT);
//$objPHPExcel->getActiveSheet()->getStyle('J3:3')->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_RIGHT);
//$objPHPExcel->getActiveSheet()->getStyle('M3:R3')->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_LEFT);
	
// Set fonts
$objPHPExcel->getActiveSheet()->getStyle('A2:I2')->getFont()->getColor()->setARGB(PHPExcel_Style_Color::COLOR_BLACK);
$objPHPExcel->getActiveSheet()->getStyle('A2:I2')->getFont()->setBold(true);
$objPHPExcel->getActiveSheet()->getStyle('A2:I2')->getFont()->setSize(10);

$objPHPExcel->getActiveSheet()->setCellValue('A2', "Pallet ID.");
$objPHPExcel->getActiveSheet()->setCellValue('B2', "FG Code GDJ");
$objPHPExcel->getActiveSheet()->setCellValue('C2', "FG Code GDJ Desc.");
$objPHPExcel->getActiveSheet()->setCellValue('D2', "Project");
$objPHPExcel->getActiveSheet()->setCellValue('E2', "Location");
$objPHPExcel->getActiveSheet()->setCellValue('F2', "Qty. (Pcs.)");
$objPHPExcel->getActiveSheet()->setCellValue('G2', "Status");
$objPHPExcel->getActiveSheet()->setCellValue('H2', "Receive Date");
$objPHPExcel->getActiveSheet()->setCellValue('I2', "Stock Aging (Day)");

//get data
$strSql_d = " 
SELECT 
	receive_pallet_code
	,tags_fg_code_gdj
	,tags_fg_code_gdj_desc
	,tags_project_name
	,receive_location
	,receive_status
	,receive_date
	,sum(tags_packing_std) as sum_pkg_std
FROM tbl_pallet_running
left join tbl_receive
on tbl_pallet_running.pallet_code = tbl_receive.receive_pallet_code
left join tbl_tags_running
on tbl_receive.receive_tags_code = tbl_tags_running.tags_code
where
receive_status in ('Received', 'Sinbin')
and receive_repn_id is NULL
and tags_fg_code_gdj is not null
group by
	receive_pallet_code
	,tags_fg_code_gdj
	,tags_fg_code_gdj_desc
	,tags_project_name
	,receive_location
	,receive_status
	,receive_date
order by 
receive_pallet_code desc
";

$i = 2;
$str_sum_stock = 0;

$objQuery_d = sqlsrv_query($db_con, $strSql_d);
while($objResult_d = sqlsrv_fetch_array($objQuery_d, SQLSRV_FETCH_ASSOC))
{
	$i++;
	
	$receive_pallet_code = $objResult_d['receive_pallet_code'];
	$tags_fg_code_gdj = $objResult_d['tags_fg_code_gdj'];
	$tags_fg_code_gdj_desc = $objResult_d['tags_fg_code_gdj_desc'];
	$tags_project_name = $objResult_d['tags_project_name'];
	$receive_location = $objResult_d['receive_location'];
	$receive_status = $objResult_d['receive_status'];
	$receive_date = $objResult_d['receive_date'];
	$sum_pkg_std = $objResult_d['sum_pkg_std'];

	$date_receive=date_create($receive_date);
	$date_now=date_create($buffer_date);
	$diff=date_diff($date_receive,$date_now);
	
	//total count
	$str_sum_stock = $str_sum_stock + $sum_pkg_std;
	
	//Set main
	$objPHPExcel->getActiveSheet()->getStyle('F' .($i). ':'.'F'.($i))->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_RIGHT);
	
	//border
	$objPHPExcel->getActiveSheet()->getStyle('A' .$i. ':'.'I'.($i))->getBorders()->getAllBorders()->setBorderStyle(PHPExcel_Style_Border::BORDER_THIN);
	
	//data
	$objPHPExcel->getActiveSheet()->setCellValueExplicit('A' . $i, $receive_pallet_code, PHPExcel_Cell_DataType::TYPE_STRING);
	$objPHPExcel->getActiveSheet()->setCellValueExplicit('B' . $i, $tags_fg_code_gdj, PHPExcel_Cell_DataType::TYPE_STRING);
	$objPHPExcel->getActiveSheet()->setCellValue('C' . $i, $tags_fg_code_gdj_desc, PHPExcel_Cell_DataType::TYPE_STRING);
	$objPHPExcel->getActiveSheet()->setCellValue('D' . $i, $tags_project_name, PHPExcel_Cell_DataType::TYPE_STRING);
	$objPHPExcel->getActiveSheet()->setCellValue('E' . $i, $receive_location);
	$objPHPExcel->getActiveSheet()->setCellValue('F' . $i, number_format($sum_pkg_std), PHPExcel_Cell_DataType::TYPE_STRING);
	$objPHPExcel->getActiveSheet()->setCellValue('G' . $i, $receive_status);
	$objPHPExcel->getActiveSheet()->setCellValue('H' . $i, $receive_date);
	$objPHPExcel->getActiveSheet()->setCellValue('I' . $i, $diff->format("%a"));
}
	
$i = $i + 1;
	
//merge
//$objPHPExcel->setActiveSheetIndex(0)->mergeCells('A' .$i. ':'.'I'.($i));
//$objPHPExcel->setActiveSheetIndex(0)->mergeCells('M' .$i. ':'.'R'.($i));

//border
$objPHPExcel->getActiveSheet()->getStyle('E' .$i. ':'.'G'.($i))->getBorders()->getAllBorders()->setBorderStyle(PHPExcel_Style_Border::BORDER_THIN);

//Set main
$objPHPExcel->getActiveSheet()->getStyle('E' .($i). ':'.'E'.($i))->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_RIGHT);
$objPHPExcel->getActiveSheet()->getStyle('F' .($i). ':'.'F'.($i))->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_RIGHT);

//Set BG
$objPHPExcel->getActiveSheet()->getStyle('E' .($i). ':'.'G'.($i))->getFill()->setFillType(PHPExcel_Style_Fill::FILL_SOLID)->getStartColor()->setARGB('FFFFFF00');

//data d
$objPHPExcel->getActiveSheet()->setCellValue('A' . $i, '');
$objPHPExcel->getActiveSheet()->setCellValue('B' . $i, '');
$objPHPExcel->getActiveSheet()->setCellValue('C' . $i, '');
$objPHPExcel->getActiveSheet()->setCellValue('D' . $i, '');
$objPHPExcel->getActiveSheet()->setCellValue('E' . $i, 'Total Qty.:');
$objPHPExcel->getActiveSheet()->setCellValueExplicit('F' . $i, number_format($str_sum_stock), PHPExcel_Cell_DataType::TYPE_STRING);
$objPHPExcel->getActiveSheet()->setCellValue('G' . $i, 'Pcs.');
$objPHPExcel->getActiveSheet()->setCellValue('H' . $i, '');
$objPHPExcel->getActiveSheet()->setCellValue('I' . $i, '');


// Rename sheet 
$objPHPExcel->getActiveSheet()->setTitle('Report FG Stock By Pallet');

$objPHPExcel->setActiveSheetIndex(0);
// Redirect output to a client’s web browser (Excel5)
header('Content-Type: application/vnd.ms-excel');
header('Content-Disposition: attachment;filename="Report FG Stock By Pallet ('.$buffer_datetime.').xls"');
header('Cache-Control: max-age=0');

$objWriter = PHPExcel_IOFactory::createWriter($objPHPExcel, 'Excel5');
$objWriter->save('php://output');
exit;
?>