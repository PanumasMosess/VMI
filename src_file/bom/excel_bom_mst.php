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
$objPHPExcel->getActiveSheet()->getStyle('A1:Z2')->getFont()->getColor()->setARGB(PHPExcel_Style_Color::COLOR_BLACK);
$objPHPExcel->getActiveSheet()->getStyle('A1:Z2')->getFont()->setBold(true);
$objPHPExcel->getActiveSheet()->getStyle("A1:Z2")->getFont()->setSize(10);

//header
$objPHPExcel->setActiveSheetIndex(0)->mergeCells('A1:H1');
$objPHPExcel->getActiveSheet()->setCellValue('A1', "Report BOM Mst On $buffer_datetime");

//border		
$objPHPExcel->getActiveSheet()->getStyle('A1:Z2')->applyFromArray($styleArray);
$objPHPExcel->getActiveSheet()->getStyle('A1:Z2')->getBorders()->getAllBorders()->setBorderStyle(PHPExcel_Style_Border::BORDER_THIN);

//Set BG
$objPHPExcel->getActiveSheet()->getStyle('A2:Z2')->getFill()->setFillType(PHPExcel_Style_Fill::FILL_SOLID)->getStartColor()->setARGB('FF00FFFF');

//Set main
//$objPHPExcel->getActiveSheet()->getStyle('A3:I3')->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_LEFT);
//$objPHPExcel->getActiveSheet()->getStyle('J3:L3')->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_RIGHT);
//$objPHPExcel->getActiveSheet()->getStyle('M3:R3')->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_LEFT);
	
// Set fonts
$objPHPExcel->getActiveSheet()->getStyle('A2:Z2')->getFont()->getColor()->setARGB(PHPExcel_Style_Color::COLOR_BLACK);
$objPHPExcel->getActiveSheet()->getStyle('A2:Z2')->getFont()->setBold(true);
$objPHPExcel->getActiveSheet()->getStyle('A2:Z2')->getFont()->setSize(10);

$objPHPExcel->getActiveSheet()->setCellValue('A2', "FG Code Set ABT");
$objPHPExcel->getActiveSheet()->setCellValue('B2', "Component Code ABT");
$objPHPExcel->getActiveSheet()->setCellValue('C2', "FG Code GDJ");
$objPHPExcel->getActiveSheet()->setCellValue('D2', "Description");
$objPHPExcel->getActiveSheet()->setCellValue('E2', "Customer Code");
$objPHPExcel->getActiveSheet()->setCellValue('F2', "Customer Name");
$objPHPExcel->getActiveSheet()->setCellValue('G2', "Project Name");
$objPHPExcel->getActiveSheet()->setCellValue('H2', "Carton Code Normal");
$objPHPExcel->getActiveSheet()->setCellValue('I2', "SNP");
$objPHPExcel->getActiveSheet()->setCellValue('J2', "Code");
$objPHPExcel->getActiveSheet()->setCellValue('K2', "Ship to Type");
$objPHPExcel->getActiveSheet()->setCellValue('L2', "Package Type");
$objPHPExcel->getActiveSheet()->setCellValue('M2', "W");
$objPHPExcel->getActiveSheet()->setCellValue('N2', "L");
$objPHPExcel->getActiveSheet()->setCellValue('O2', "H");
$objPHPExcel->getActiveSheet()->setCellValue('P2', "Usage");
$objPHPExcel->getActiveSheet()->setCellValue('Q2', "Space Paper");
$objPHPExcel->getActiveSheet()->setCellValue('R2', "Flute");
$objPHPExcel->getActiveSheet()->setCellValue('S2', "Packing");
$objPHPExcel->getActiveSheet()->setCellValue('T2', "WMS Min");
$objPHPExcel->getActiveSheet()->setCellValue('U2', "WMS Max");
$objPHPExcel->getActiveSheet()->setCellValue('V2', "VMI Min");
$objPHPExcel->getActiveSheet()->setCellValue('W2', "VMI Max");
$objPHPExcel->getActiveSheet()->setCellValue('X2', "Part Customer");
$objPHPExcel->getActiveSheet()->setCellValue('Y2', "Cost/Pcs");
$objPHPExcel->getActiveSheet()->setCellValue('Z2', "Price Sale/Pcs");

//get data
$strSql_d = " 
SELECT * FROM tbl_bom_mst
";

$i = 2;
$str_sum_stock = 0;

$objQuery_d = sqlsrv_query($db_con, $strSql_d);
while($objResult_d = sqlsrv_fetch_array($objQuery_d, SQLSRV_FETCH_ASSOC))
{
	$i++;
	  
	$bom_fg_code_set_abt = $objResult_d['bom_fg_code_set_abt'];
	$bom_fg_sku_code_abt = $objResult_d['bom_fg_sku_code_abt'];
	$bom_fg_code_gdj = $objResult_d['bom_fg_code_gdj'];
	$bom_fg_desc = $objResult_d['bom_fg_desc'];
	$bom_cus_code = $objResult_d['bom_cus_code'];
	$bom_cus_name = $objResult_d['bom_cus_name'];
	$bom_pj_name = $objResult_d['bom_pj_name'];
	$bom_ctn_code_normal = $objResult_d['bom_ctn_code_normal'];
	$bom_snp = $objResult_d['bom_snp'];
	$bom_sku_code = $objResult_d['bom_sku_code'];
	$bom_ship_type = $objResult_d['bom_ship_type'];
	$bom_pckg_type = $objResult_d['bom_pckg_type'];
	$bom_dims_w = $objResult_d['bom_dims_w'];
	$bom_dims_l = $objResult_d['bom_dims_l'];
	$bom_dims_h = $objResult_d['bom_dims_h'];
	$bom_usage = $objResult_d['bom_usage'];
	$bom_space_paper = $objResult_d['bom_space_paper'];
	$bom_flute = $objResult_d['bom_flute'];
	$bom_packing = $objResult_d['bom_packing'];
	$bom_wms_min = $objResult_d['bom_wms_min'];
	$bom_wms_max = $objResult_d['bom_wms_max'];
	$bom_vmi_min = $objResult_d['bom_vmi_min'];
	$bom_vmi_max = $objResult_d['bom_vmi_max'];
	$bom_part_customer = $objResult_d['bom_part_customer'];
	$bom_cost_per_pcs = $objResult_d['bom_cost_per_pcs'];
	$bom_price_sale_per_pcs = $objResult_d['bom_price_sale_per_pcs'];
	
	//Set main
	//$objPHPExcel->getActiveSheet()->getStyle('F' .($i). ':'.'F'.($i))->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_RIGHT);
	
	//border
	$objPHPExcel->getActiveSheet()->getStyle('A' .$i. ':'.'Z'.($i))->getBorders()->getAllBorders()->setBorderStyle(PHPExcel_Style_Border::BORDER_THIN);
	
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
	
	$objPHPExcel->getActiveSheet()->setCellValue('A' . $i, $bom_fg_code_set_abt);
	$objPHPExcel->getActiveSheet()->setCellValue('B' . $i, $bom_fg_sku_code_abt);
	$objPHPExcel->getActiveSheet()->setCellValue('C' . $i, $bom_fg_code_gdj);
	$objPHPExcel->getActiveSheet()->setCellValue('D' . $i, $bom_fg_desc);
	$objPHPExcel->getActiveSheet()->setCellValue('E' . $i, $bom_cus_code);
	$objPHPExcel->getActiveSheet()->setCellValue('F' . $i, $bom_cus_name);
	$objPHPExcel->getActiveSheet()->setCellValue('G' . $i, $bom_pj_name);
	$objPHPExcel->getActiveSheet()->setCellValue('H' . $i, $bom_ctn_code_normal);
	$objPHPExcel->getActiveSheet()->setCellValue('I' . $i, $bom_snp);
	$objPHPExcel->getActiveSheet()->setCellValue('J' . $i, $bom_sku_code);
	$objPHPExcel->getActiveSheet()->setCellValue('K' . $i, $bom_ship_type);
	$objPHPExcel->getActiveSheet()->setCellValue('L' . $i, $bom_pckg_type);
	$objPHPExcel->getActiveSheet()->setCellValue('M' . $i, $bom_dims_w);
	$objPHPExcel->getActiveSheet()->setCellValue('N' . $i, $bom_dims_l);
	$objPHPExcel->getActiveSheet()->setCellValue('O' . $i, $bom_dims_h);
	$objPHPExcel->getActiveSheet()->setCellValue('P' . $i, $bom_usage);
	$objPHPExcel->getActiveSheet()->setCellValue('Q' . $i, $bom_space_paper);
	$objPHPExcel->getActiveSheet()->setCellValue('R' . $i, $bom_flute);
	$objPHPExcel->getActiveSheet()->setCellValue('S' . $i, $bom_packing);
	$objPHPExcel->getActiveSheet()->setCellValue('T' . $i, $bom_wms_min);
	$objPHPExcel->getActiveSheet()->setCellValue('U' . $i, $bom_wms_max);
	$objPHPExcel->getActiveSheet()->setCellValue('V' . $i, $bom_vmi_min);
	$objPHPExcel->getActiveSheet()->setCellValue('W' . $i, $bom_vmi_max);
	$objPHPExcel->getActiveSheet()->setCellValue('X' . $i, $bom_part_customer);
	$objPHPExcel->getActiveSheet()->setCellValue('Y' . $i, $bom_cost_per_pcs);
	$objPHPExcel->getActiveSheet()->setCellValue('Z' . $i, $bom_price_sale_per_pcs);
}

// Rename sheet 
$objPHPExcel->getActiveSheet()->setTitle('Report BOM Mst');

$objPHPExcel->setActiveSheetIndex(0);
// Redirect output to a client’s web browser (Excel5)
header('Content-Type: application/vnd.ms-excel');
header('Content-Disposition: attachment;filename="Report BOM Mst ('.$buffer_datetime.').xls"');
header('Cache-Control: max-age=0');

$objWriter = PHPExcel_IOFactory::createWriter($objPHPExcel, 'Excel5');
$objWriter->save('php://output');
exit;
?>