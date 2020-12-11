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
$tag_locate = isset($_POST['pj_name']) ? $_POST['pj_name'] : '';
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
$objPHPExcel->getActiveSheet()->getStyle('A1:J2')->getFont()->getColor()->setARGB(PHPExcel_Style_Color::COLOR_BLACK);
$objPHPExcel->getActiveSheet()->getStyle('A1:J2')->getFont()->setBold(true);
$objPHPExcel->getActiveSheet()->getStyle("A1:J2")->getFont()->setSize(10);

//header
$objPHPExcel->setActiveSheetIndex(0)->mergeCells('A1:J1');
$objPHPExcel->getActiveSheet()->setCellValue('A1', "Report FG Billing By Tags On $buffer_datetime");

//border		
$objPHPExcel->getActiveSheet()->getStyle('A1:J2')->applyFromArray($styleArray);
$objPHPExcel->getActiveSheet()->getStyle('A1:J2')->getBorders()->getAllBorders()->setBorderStyle(PHPExcel_Style_Border::BORDER_THIN);

//Set BG
$objPHPExcel->getActiveSheet()->getStyle('A2:J2')->getFill()->setFillType(PHPExcel_Style_Fill::FILL_SOLID)->getStartColor()->setARGB('FF00FFFF');

//Set main
//$objPHPExcel->getActiveSheet()->getStyle('A3:I3')->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_LEFT);
//$objPHPExcel->getActiveSheet()->getStyle('J3:L3')->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_RIGHT);
//$objPHPExcel->getActiveSheet()->getStyle('M3:R3')->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_LEFT);
	
// Set fonts
$objPHPExcel->getActiveSheet()->getStyle('A2:J2')->getFont()->getColor()->setARGB(PHPExcel_Style_Color::COLOR_BLACK);
$objPHPExcel->getActiveSheet()->getStyle('A2:J2')->getFont()->setBold(true);
$objPHPExcel->getActiveSheet()->getStyle('A2:J2')->getFont()->setSize(10);

$objPHPExcel->getActiveSheet()->setCellValue('A2', "Tags ID.");
$objPHPExcel->getActiveSheet()->setCellValue('B2', "Part Customer");
$objPHPExcel->getActiveSheet()->setCellValue('C2', "FG Code GDJ");
$objPHPExcel->getActiveSheet()->setCellValue('D2', "SKU Code GDJ");
$objPHPExcel->getActiveSheet()->setCellValue('E2', "Ship Type");
$objPHPExcel->getActiveSheet()->setCellValue('F2', "Quantity (Pcs.)");
$objPHPExcel->getActiveSheet()->setCellValue('G2', "Price (Bath.)");
$objPHPExcel->getActiveSheet()->setCellValue('H2', "Status");
$objPHPExcel->getActiveSheet()->setCellValue('I2', "User Pick By");
$objPHPExcel->getActiveSheet()->setCellValue('J2', "Pick Date");

//get data
$strSql_d = " 
SELECT 
	usage_tags_code
   ,usage_part_customer
   ,usage_fg_code_set_abt
   ,usage_sku_code_abt
   ,usage_ship_type
   ,ps_t_tags_packing_std
   ,usage_price_sale_per_pcs
   ,receive_status  
   ,usage_pick_by
   ,usage_pick_date

FROM [tbl_usage_conf]
left join tbl_bom_mst
on tbl_bom_mst.bom_fg_code_set_abt = tbl_usage_conf.usage_fg_code_set_abt
and tbl_bom_mst.bom_fg_sku_code_abt = tbl_usage_conf.usage_sku_code_abt
and tbl_bom_mst.bom_fg_code_gdj = tbl_usage_conf.usage_fg_code_gdj 
and tbl_bom_mst.bom_ship_type = tbl_usage_conf.usage_ship_type
and tbl_bom_mst.bom_part_customer = tbl_usage_conf.usage_part_customer
and tbl_bom_mst.bom_pj_name = tbl_usage_conf.usage_terminal_name
left join tbl_receive
on tbl_receive.receive_tags_code = tbl_usage_conf.usage_tags_code
left join tbl_picking_tail
on tbl_receive.receive_tags_code = tbl_picking_tail.ps_t_tags_code
where receive_status = 'USAGE CONFIRM' and usage_terminal_name = '$tag_locate'
group by 
	usage_tags_code
   ,usage_part_customer
   ,usage_fg_code_set_abt
   ,usage_sku_code_abt
   ,usage_ship_type
   ,ps_t_tags_packing_std
   ,usage_price_sale_per_pcs
   ,receive_status  
   ,usage_pick_by
   ,usage_pick_date

   order by usage_pick_date desc
";

$i = 2;
$str_sum_stock = 0;

$objQuery_d = sqlsrv_query($db_con, $strSql_d);
while($objResult = sqlsrv_fetch_array($objQuery_d, SQLSRV_FETCH_ASSOC))
{
	$i++;
	
	$usage_tags_code = $objResult['usage_tags_code'];
	$usage_part_customer = $objResult['usage_part_customer'];
	$usage_fg_code_set_abt = $objResult['usage_fg_code_set_abt'];
	$usage_sku_code_abt = $objResult['usage_sku_code_abt'];
	$usage_ship_type = $objResult['usage_ship_type'];
    $ps_t_tags_packing_std = $objResult['ps_t_tags_packing_std'];
    $usage_price_sale_per_pcs = $objResult['usage_price_sale_per_pcs'];
    $receive_status = $objResult['receive_status'];
    $usage_pick_by = $objResult['usage_pick_by'];
    $usage_pick_date = $objResult['usage_pick_date'];

    $pcs_num = number_format($ps_t_tags_packing_std);
    $price_num = number_format($usage_price_sale_per_pcs);

    $price = $pcs_num * $price_num;
	
	//total count
	$str_sum_stock = $str_sum_stock + $ps_t_tags_packing_std;
	
	//Set main
	$objPHPExcel->getActiveSheet()->getStyle('F' .($i). ':'.'F'.($i))->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_RIGHT);
	
	//border
	$objPHPExcel->getActiveSheet()->getStyle('A' .$i. ':'.'J'.($i))->getBorders()->getAllBorders()->setBorderStyle(PHPExcel_Style_Border::BORDER_THIN);
	
	//data
	$objPHPExcel->getActiveSheet()->setCellValueExplicit('A' . $i, $usage_tags_code, PHPExcel_Cell_DataType::TYPE_STRING);
	$objPHPExcel->getActiveSheet()->setCellValueExplicit('B' . $i, $usage_part_customer, PHPExcel_Cell_DataType::TYPE_STRING);
	$objPHPExcel->getActiveSheet()->setCellValue('C' . $i, $usage_fg_code_set_abt, PHPExcel_Cell_DataType::TYPE_STRING);
	$objPHPExcel->getActiveSheet()->setCellValue('D' . $i, $usage_sku_code_abt);
	$objPHPExcel->getActiveSheet()->setCellValue('E' . $i, $usage_ship_type);
	$objPHPExcel->getActiveSheet()->setCellValue('F' . $i, number_format($ps_t_tags_packing_std), PHPExcel_Cell_DataType::TYPE_STRING);
	$objPHPExcel->getActiveSheet()->setCellValue('G' . $i, number_format($price), PHPExcel_Cell_DataType::TYPE_STRING);
	$objPHPExcel->getActiveSheet()->setCellValue('H' . $i, $receive_status);
    $objPHPExcel->getActiveSheet()->setCellValue('I' . $i, $usage_pick_by);
    $objPHPExcel->getActiveSheet()->setCellValue('J' . $i, $usage_pick_date);

    
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
$objPHPExcel->getActiveSheet()->setCellValueExplicit('D' . $i, '');
$objPHPExcel->getActiveSheet()->setCellValue('E' . $i, 'Total Qty:');
$objPHPExcel->getActiveSheet()->setCellValue('F' . $i, number_format($str_sum_stock), PHPExcel_Cell_DataType::TYPE_STRING);
$objPHPExcel->getActiveSheet()->setCellValue('G' . $i, 'Pcs.');
$objPHPExcel->getActiveSheet()->setCellValue('H' . $i, '');
$objPHPExcel->getActiveSheet()->setCellValue('I' . $i, '');
$objPHPExcel->getActiveSheet()->setCellValue('J' . $i, '');

// Rename sheet 
$objPHPExcel->getActiveSheet()->setTitle('Report Billing By Tags');

$objPHPExcel->setActiveSheetIndex(0);
// Redirect output to a client’s web browser (Excel5)
header('Content-Type: application/vnd.ms-excel');
header('Content-Disposition: attachment;filename="Report Billing By Tags ('.$buffer_datetime.').xls"');
header('Cache-Control: max-age=0');

$objWriter = PHPExcel_IOFactory::createWriter($objPHPExcel, 'Excel5');
$objWriter->save('php://output');
exit;
?>