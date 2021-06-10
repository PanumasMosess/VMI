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

$buffer_date = date("Y-m-d");
$buffer_time = date("H:i:s"); //24H
$buffer_datetime = date("Y-m-d H:i:s");

$date_start = isset($_REQUEST['date_start']) ? $_REQUEST['date_start'] : '';
$date_end = isset($_REQUEST['date_end']) ? $_REQUEST['date_end'] : '';

$start_ = $date_start;
$end_ = $date_end;
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
$objPHPExcel->getActiveSheet()->getStyle('A1:I2')->getFont()->getColor()->setARGB(PHPExcel_Style_Color::COLOR_BLACK);
$objPHPExcel->getActiveSheet()->getStyle('A1:I2')->getFont()->setBold(true);
$objPHPExcel->getActiveSheet()->getStyle("A1:I2")->getFont()->setSize(10);

//header
$objPHPExcel->setActiveSheetIndex(0)->mergeCells('A1:I1');
$objPHPExcel->getActiveSheet()->getStyle('A1:I1')->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
$objPHPExcel->getActiveSheet()->setCellValue('A1', "รายงานการขายสินค้าหรือการให้บริการประจำ");
$objPHPExcel->setActiveSheetIndex(0)->mergeCells('A2:I2');
$objPHPExcel->getActiveSheet()->getStyle('A2:I2')->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
$objPHPExcel->getActiveSheet()->setCellValue('A2', "$date_start ถึง $date_end");

//border		
$objPHPExcel->getActiveSheet()->getStyle('A1:I2')->applyFromArray($styleArray);
$objPHPExcel->getActiveSheet()->getStyle('A1:I2')->getBorders()->getAllBorders()->setBorderStyle(PHPExcel_Style_Border::BORDER_THIN);

//Set BG
$objPHPExcel->getActiveSheet()->getStyle('A3:I3')->getFill()->setFillType(PHPExcel_Style_Fill::FILL_SOLID)->getStartColor()->setARGB('FF00FFFF');

//Set main
//$objPHPExcel->getActiveSheet()->getStyle('A3:I3')->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_LEFT);
//$objPHPExcel->getActiveSheet()->getStyle('J3:L3')->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_RIGHT);
//$objPHPExcel->getActiveSheet()->getStyle('M3:R3')->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_LEFT);
	
// Set fonts
$objPHPExcel->getActiveSheet()->getStyle('A3:I3')->getFont()->getColor()->setARGB(PHPExcel_Style_Color::COLOR_BLACK);
$objPHPExcel->getActiveSheet()->getStyle('A3:I3')->getBorders()->getAllBorders()->setBorderStyle(PHPExcel_Style_Border::BORDER_THIN);
$objPHPExcel->getActiveSheet()->getStyle('A3:I3')->getFont()->setBold(true);
$objPHPExcel->getActiveSheet()->getStyle('A3:I3')->getFont()->setSize(10);

$objPHPExcel->getActiveSheet()->setCellValue('A3', "วันที่ชำระเงิน");
$objPHPExcel->getActiveSheet()->setCellValue('B3', "เวลาที่ชำระเงิน");
$objPHPExcel->getActiveSheet()->setCellValue('C3', "เวลา");
$objPHPExcel->getActiveSheet()->setCellValue('D3', "ID");
$objPHPExcel->getActiveSheet()->setCellValue('E3', "POS ID");
$objPHPExcel->getActiveSheet()->setCellValue('F3', "INV. No");
$objPHPExcel->getActiveSheet()->setCellValue('G3', "ยอดก่อนภาษี ยอดรวม + ค่าบริการ");
$objPHPExcel->getActiveSheet()->setCellValue('H3', "ภาษี");
$objPHPExcel->getActiveSheet()->setCellValue('I3', "รวมสุทธิ");

//get data
$strSql_d = " 
SELECT  
       [b2c_sale_date]
      ,[b2c_sale_time]
      ,[b2c_sale_count_time]
      ,[b2c_sale_order_id]
      ,[b2c_sale_user_id]
      ,[b2c_sale_pos_no]
      ,[b2c_sale_inv_no]
      ,[b2c_sale_excluding_vat]
      ,[b2c_sale_tax]
      ,[b2c_sale_including_vat]
      ,[b2c_sale_remark]
  FROM [tbl_b2c_sale]
  where  (b2c_sale_date between '$start_' and '$end_')
";

$i = 3;
$str_sum_exclude_tax = 0;
$str_sum_tax = 0;
$str_sum_include_tax = 0;

$objQuery_d = sqlsrv_query($db_con, $strSql_d);
while($objResult_d = sqlsrv_fetch_array($objQuery_d, SQLSRV_FETCH_ASSOC))
{
	$i++;
	
	$b2c_sale_date = $objResult_d['b2c_sale_date'];
	$b2c_sale_time = $objResult_d['b2c_sale_time'];
    $b2c_sale_time = date("h:i", strtotime($b2c_sale_time));
	// $b2c_sale_count_time = $objResult_DTNSheet['b2c_sale_count_time'];
	$b2c_sale_order_id = $objResult_d['b2c_sale_order_id'];
    $b2c_sale_user_id = $objResult_d['b2c_sale_user_id'];
	// $b2c_sale_pos_no = $objResult_DTNSheet['b2c_sale_pos_no'];
	$b2c_sale_inv_no = $objResult_d['b2c_sale_inv_no'];
    $b2c_sale_excluding_vat = $objResult_d['b2c_sale_excluding_vat'];
    $b2c_sale_tax = $objResult_d['b2c_sale_tax'];
    $b2c_sale_including_vat = $objResult_d['b2c_sale_including_vat'];
    // $b2c_sale_remark = $objResult_DTNSheet['b2c_sale_remark'];
	
	//total count
	$str_sum_exclude_tax = $str_sum_exclude_tax + $b2c_sale_excluding_vat;
    $str_sum_tax = $str_sum_tax + $b2c_sale_tax;
    $str_sum_include_tax = $str_sum_include_tax + $b2c_sale_including_vat;
	
	//Set main
	$objPHPExcel->getActiveSheet()->getStyle('A' .($i). ':'.'F'.($i))->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_RIGHT);
	
	//border
	$objPHPExcel->getActiveSheet()->getStyle('A' .($i). ':'.'I'.($i))->getBorders()->getAllBorders()->setBorderStyle(PHPExcel_Style_Border::BORDER_THIN);
	
	//data
	$objPHPExcel->getActiveSheet()->setCellValue('A' . $i, $b2c_sale_date);
	$objPHPExcel->getActiveSheet()->setCellValue('B' . $i, $b2c_sale_time);
	$objPHPExcel->getActiveSheet()->setCellValue('C' . $i, '0 Min');
	$objPHPExcel->getActiveSheet()->setCellValue('D' . $i, $b2c_sale_user_id);
	$objPHPExcel->getActiveSheet()->setCellValue('E' . $i, 'xxxxxxxxxx');
	$objPHPExcel->getActiveSheet()->setCellValue('F' . $i, $b2c_sale_inv_no);
	$objPHPExcel->getActiveSheet()->setCellValue('G' . $i, $b2c_sale_excluding_vat);
	$objPHPExcel->getActiveSheet()->setCellValue('H' . $i, $b2c_sale_tax);
    $objPHPExcel->getActiveSheet()->setCellValue('I' . $i, $b2c_sale_including_vat);
}
	
$i = $i + 1;
	
//merge
//$objPHPExcel->setActiveSheetIndex(0)->mergeCells('A' .$i. ':'.'I'.($i));
//$objPHPExcel->setActiveSheetIndex(0)->mergeCells('M' .$i. ':'.'R'.($i));

//border
$objPHPExcel->getActiveSheet()->getStyle('A' .($i). ':'.'I'.($i))->getBorders()->getAllBorders()->setBorderStyle(PHPExcel_Style_Border::BORDER_THIN);

//Set main
$objPHPExcel->getActiveSheet()->getStyle('A' .($i). ':'.'A'.($i))->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
$objPHPExcel->getActiveSheet()->getStyle('G' .($i). ':'.'G'.($i))->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_RIGHT);
$objPHPExcel->getActiveSheet()->getStyle('H' .($i). ':'.'H'.($i))->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_RIGHT);
$objPHPExcel->getActiveSheet()->getStyle('I' .($i). ':'.'I'.($i))->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_RIGHT);

//Set BG
$objPHPExcel->getActiveSheet()->getStyle('G' .($i). ':'.'I'.($i))->getFill()->setFillType(PHPExcel_Style_Fill::FILL_SOLID)->getStartColor()->setARGB('FFFFFF00');

//set fonts
$objPHPExcel->getActiveSheet()->getStyle('A' . $i)->getFont()->setBold(true);
$objPHPExcel->getActiveSheet()->getStyle('G' . $i)->getFont()->setBold(true);
$objPHPExcel->getActiveSheet()->getStyle('H' . $i)->getFont()->setBold(true);
$objPHPExcel->getActiveSheet()->getStyle('I' . $i)->getFont()->setBold(true);

//data d
$objPHPExcel->getActiveSheet()->setCellValue('A' . $i, 'Total Qty :');
$objPHPExcel->getActiveSheet()->setCellValue('B' . $i, '');
$objPHPExcel->getActiveSheet()->setCellValue('C' . $i, '');
$objPHPExcel->getActiveSheet()->setCellValue('D' . $i, '');
$objPHPExcel->getActiveSheet()->setCellValue('E' . $i, '');
$objPHPExcel->getActiveSheet()->setCellValueExplicit('F' . $i,'');
$objPHPExcel->getActiveSheet()->setCellValue('G' . $i, $str_sum_exclude_tax, PHPExcel_Cell_DataType::TYPE_STRING);
$objPHPExcel->getActiveSheet()->setCellValue('H' . $i, $str_sum_tax, PHPExcel_Cell_DataType::TYPE_STRING);
$objPHPExcel->getActiveSheet()->setCellValue('I' . $i, $str_sum_include_tax, PHPExcel_Cell_DataType::TYPE_STRING);

// Rename sheet 
$objPHPExcel->getActiveSheet()->setTitle('Report Order B2C');

$objPHPExcel->setActiveSheetIndex(0);
// Redirect output to a client’s web browser (Excel5)
header('Content-Type: application/vnd.ms-excel');
header('Content-Disposition: attachment;filename="Report Order B2C ('.$buffer_datetime.').xls"');
header('Cache-Control: max-age=0');

$objWriter = PHPExcel_IOFactory::createWriter($objPHPExcel, 'Excel5');
$objWriter->save('php://output');
exit;
?>