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
$objPHPExcel->getActiveSheet()->getStyle('A1:H1')->getFont()->getColor()->setARGB(PHPExcel_Style_Color::COLOR_BLACK);
$objPHPExcel->getActiveSheet()->getStyle('A1:H1')->getFont()->setBold(true);
$objPHPExcel->getActiveSheet()->getStyle("A1:H1")->getFont()->setSize(10);

//header
$objPHPExcel->setActiveSheetIndex(0)->mergeCells('A1:H1');
$objPHPExcel->getActiveSheet()->setCellValue('A1', "Report FG Picking $buffer_datetime");

//border		
$objPHPExcel->getActiveSheet()->getStyle('A1:H2')->applyFromArray($styleArray);
$objPHPExcel->getActiveSheet()->getStyle('A1:H2')->getBorders()->getAllBorders()->setBorderStyle(PHPExcel_Style_Border::BORDER_THIN);

//Set BG
$objPHPExcel->getActiveSheet()->getStyle('A2:H2')->getFill()->setFillType(PHPExcel_Style_Fill::FILL_SOLID)->getStartColor()->setARGB('FF00FFFF');
	
// Set fonts
$objPHPExcel->getActiveSheet()->getStyle('A2:G2')->getFont()->getColor()->setARGB(PHPExcel_Style_Color::COLOR_BLACK);
$objPHPExcel->getActiveSheet()->getStyle('A2:G2')->getFont()->setBold(true);
$objPHPExcel->getActiveSheet()->getStyle('A2:G2')->getFont()->setSize(10);

$objPHPExcel->getActiveSheet()->setCellValue('A2', "Picking Sheet ID.");
$objPHPExcel->getActiveSheet()->setCellValue('B2', "Customer Code.");
$objPHPExcel->getActiveSheet()->setCellValue('C2', "Project Name.");
$objPHPExcel->getActiveSheet()->setCellValue('D2', "FG Code.");
$objPHPExcel->getActiveSheet()->setCellValue('E2', "Quantity (Pcs.) By FG");
$objPHPExcel->getActiveSheet()->setCellValue('F2', "Status");
$objPHPExcel->getActiveSheet()->setCellValue('G2', "Quality Control");
$objPHPExcel->getActiveSheet()->setCellValue('H2', "Issue Date");

//get data
$strSql_d = " 
SELECT 
	[ps_h_picking_code]
	,[ps_t_fg_code_gdj]
	,[ps_h_cus_code]
	,[ps_h_cus_name]
	,[ps_t_pj_name]
	,[ps_h_status]
	,sum([ps_t_tags_packing_std]) as sum_tags_packing_std
	,[ps_h_issue_date]
FROM [tbl_picking_head]
left join
tbl_picking_tail
on tbl_picking_head.ps_h_picking_code = tbl_picking_tail.ps_t_picking_code
where
ps_h_status = 'Picking'
and
ps_t_pj_name != 'B2C'
and
ps_h_qc is NULL
group by
	[ps_h_picking_code]
	,[ps_t_fg_code_gdj]
	,[ps_h_cus_code]
	,[ps_h_cus_name]
	,[ps_t_pj_name]
	,[ps_h_status]
	,[ps_h_issue_date]
order by 
ps_h_picking_code desc
";

$i = 2;
$str_sum_stock = 0;

$objQuery_d = sqlsrv_query($db_con, $strSql_d);
while($objResult_d = sqlsrv_fetch_array($objQuery_d, SQLSRV_FETCH_ASSOC))
{
    $i++;
    
    $ps_h_picking_code = $objResult_d['ps_h_picking_code'];
	$ps_t_fg_code_gdj = $objResult_d['ps_t_fg_code_gdj'];
	$ps_h_cus_code = $objResult_d['ps_h_cus_code'];
	$ps_h_cus_name = $objResult_d['ps_h_cus_name'];
	$ps_t_pj_name = $objResult_d['ps_t_pj_name'];
    $sum_tags_packing_std = $objResult_d['sum_tags_packing_std'];
    $ps_h_status = $objResult_d['ps_h_status'];
    $ps_h_issue_date = $objResult_d['ps_h_issue_date'];
	
	
	//total count
	$str_sum_stock = $str_sum_stock + $sum_tags_packing_std;
	
	//Set main
	$objPHPExcel->getActiveSheet()->getStyle('H' .($i). ':'.'H'.($i))->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_RIGHT);
	$objPHPExcel->getActiveSheet()->getStyle('D' .($i). ':'.'D'.($i))->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_RIGHT);
	//$objPHPExcel->getActiveSheet()->getStyle('G' .($i). ':'.'G'.($i))->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
	
	//border
	$objPHPExcel->getActiveSheet()->getStyle('A' .$i. ':'.'H'.($i))->getBorders()->getAllBorders()->setBorderStyle(PHPExcel_Style_Border::BORDER_THIN);
	
	//data
	$objPHPExcel->getActiveSheet()->setCellValueExplicit('A' . $i, $ps_h_picking_code, PHPExcel_Cell_DataType::TYPE_STRING);
	$objPHPExcel->getActiveSheet()->setCellValueExplicit('B' . $i, $ps_h_cus_code, PHPExcel_Cell_DataType::TYPE_STRING);
	$objPHPExcel->getActiveSheet()->setCellValue('C' . $i, $ps_t_pj_name, PHPExcel_Cell_DataType::TYPE_STRING);
	$objPHPExcel->getActiveSheet()->setCellValue('D' . $i, $ps_t_fg_code_gdj);
	// if($stock_status == 'Match'){
	// 	$phpColor = new PHPExcel_Style_Color();
	// 	$phpColor->setRGB('00FF00');  
	// 	$objPHPExcel->getActiveSheet()->getStyle('D' .($i). ':'.'D'.($i))->getFont()->setColor($phpColor);
	// 	$objPHPExcel->getActiveSheet()->setCellValue('D' . $i, $stock_status);
	// }else{
	// 	$phpColor = new PHPExcel_Style_Color();
	// 	$phpColor->setRGB('FF3333');  
	// 	$objPHPExcel->getActiveSheet()->getStyle('D' .($i). ':'.'D'.($i))->getFont()->setColor($phpColor);
	// 	$objPHPExcel->getActiveSheet()->setCellValue('D' . $i, $stock_status);
	// }
    
    $objPHPExcel->getActiveSheet()->setCellValue('E' . $i, $sum_tags_packing_std);
    $objPHPExcel->getActiveSheet()->setCellValue('F' . $i, $ps_h_status);
    $phpColor = new PHPExcel_Style_Color();
	$phpColor->setRGB('F00');  
	$objPHPExcel->getActiveSheet()->getStyle('G' .($i). ':'.'G'.($i))->getFont()->setColor($phpColor);
	$objPHPExcel->getActiveSheet()->setCellValue('G' . $i, 'Waiting Picking QC');
    $objPHPExcel->getActiveSheet()->setCellValue('H' . $i, $ps_h_issue_date);
}
	
$i = $i + 1;
	
//merge
//$objPHPExcel->setActiveSheetIndex(0)->mergeCells('A' .$i. ':'.'I'.($i));
//$objPHPExcel->setActiveSheetIndex(0)->mergeCells('M' .$i. ':'.'R'.($i));

//border
$objPHPExcel->getActiveSheet()->getStyle('D' .$i. ':'.'H'.($i))->getBorders()->getAllBorders()->setBorderStyle(PHPExcel_Style_Border::BORDER_THIN);

//Set main
$objPHPExcel->getActiveSheet()->getStyle('E' .($i). ':'.'E'.($i))->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_RIGHT);
$objPHPExcel->getActiveSheet()->getStyle('H' .($i). ':'.'H'.($i))->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_RIGHT);

//Set BG
$objPHPExcel->getActiveSheet()->getStyle('D' .($i). ':'.'H'.($i))->getFill()->setFillType(PHPExcel_Style_Fill::FILL_SOLID)->getStartColor()->setARGB('FFFFFF00');

//data d
$objPHPExcel->getActiveSheet()->setCellValue('A' . $i, '');
$objPHPExcel->getActiveSheet()->setCellValue('B' . $i, '');
$objPHPExcel->getActiveSheet()->setCellValue('C' . $i, '');
$objPHPExcel->getActiveSheet()->setCellValue('D' . $i, 'Total Qty: ');
$objPHPExcel->getActiveSheet()->setCellValueExplicit('E' . $i, $str_sum_stock, PHPExcel_Cell_DataType::TYPE_STRING);
$objPHPExcel->getActiveSheet()->setCellValue('F' . $i, '');
$objPHPExcel->getActiveSheet()->setCellValue('G' . $i, '');
$objPHPExcel->getActiveSheet()->setCellValue('H' . $i, '');


// Rename sheet 
$objPHPExcel->getActiveSheet()->setTitle('Report FG Picking Sheet');

$objPHPExcel->setActiveSheetIndex(0);
// Redirect output to a client’s web browser (Excel5)
header('Content-Type: application/vnd.ms-excel');
header('Content-Disposition: attachment;filename="Report FG FG Picking Sheet ('.$buffer_datetime.').xls"');
header('Cache-Control: max-age=0');

$objWriter = PHPExcel_IOFactory::createWriter($objPHPExcel, 'Excel5');
$objWriter->save('php://output');
exit;
?>