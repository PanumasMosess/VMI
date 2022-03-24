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
$objPHPExcel->getActiveSheet()->getStyle('A1:E1')->getFont()->getColor()->setARGB(PHPExcel_Style_Color::COLOR_BLACK);
$objPHPExcel->getActiveSheet()->getStyle('A1:E1')->getFont()->setBold(true);
$objPHPExcel->getActiveSheet()->getStyle("A1:E1")->getFont()->setSize(10);

//header
$objPHPExcel->setActiveSheetIndex(0)->mergeCells('A1:E1');
$objPHPExcel->getActiveSheet()->setCellValue('A1', "Report FG Stock Checking By Tags On $buffer_datetime");

//border		
$objPHPExcel->getActiveSheet()->getStyle('A1:E2')->applyFromArray($styleArray);
$objPHPExcel->getActiveSheet()->getStyle('A1:E2')->getBorders()->getAllBorders()->setBorderStyle(PHPExcel_Style_Border::BORDER_THIN);

//Set BG
$objPHPExcel->getActiveSheet()->getStyle('A2:E2')->getFill()->setFillType(PHPExcel_Style_Fill::FILL_SOLID)->getStartColor()->setARGB('FF00FFFF');
	
// Set fonts
$objPHPExcel->getActiveSheet()->getStyle('A2:G2')->getFont()->getColor()->setARGB(PHPExcel_Style_Color::COLOR_BLACK);
$objPHPExcel->getActiveSheet()->getStyle('A2:G2')->getFont()->setBold(true);
$objPHPExcel->getActiveSheet()->getStyle('A2:G2')->getFont()->setSize(10);

$objPHPExcel->getActiveSheet()->setCellValue('A2', "Pallet ID.");
$objPHPExcel->getActiveSheet()->setCellValue('B2', "FG DGJ Code.");
$objPHPExcel->getActiveSheet()->setCellValue('C2', "Location.");
$objPHPExcel->getActiveSheet()->setCellValue('D2', "Stock Status.");
$objPHPExcel->getActiveSheet()->setCellValue('E2', "Sum Quantity (Pcs.) By FG");

//get data
$strSql_d = " 
SELECT 
      [stock_tags_fg_code_gdj]
	  ,stock_pallet_code
	  ,stock_location
	  ,stock_status
	  ,SUM(convert (int, stock_tags_packing_std)) as sum_QTY_from_FG_code	  
  FROM [VMI_test].[dbo].[tbl_stock_checking] GROUP BY stock_tags_fg_code_gdj, stock_pallet_code, stock_location, stock_status
  order by sum_QTY_from_FG_code desc
";

$i = 2;
$str_sum_stock = 0;

$objQuery_d = sqlsrv_query($db_con, $strSql_d);
while($objResult_d = sqlsrv_fetch_array($objQuery_d, SQLSRV_FETCH_ASSOC))
{
    $i++;
    
    $stock_FG_code = $objResult_d['stock_tags_fg_code_gdj'];
	$stock_pallet_code = $objResult_d['stock_pallet_code'];
	$stock_location = $objResult_d['stock_location'];
	$stock_status = $objResult_d['stock_status'];
	$stock_qty = $objResult_d['sum_QTY_from_FG_code'];
	
	
	//total count
	$str_sum_stock = $str_sum_stock + $stock_qty;
	
	//Set main
	$objPHPExcel->getActiveSheet()->getStyle('F' .($i). ':'.'F'.($i))->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_RIGHT);
	$objPHPExcel->getActiveSheet()->getStyle('D' .($i). ':'.'D'.($i))->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_RIGHT);
	//$objPHPExcel->getActiveSheet()->getStyle('G' .($i). ':'.'G'.($i))->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
	
	//border
	$objPHPExcel->getActiveSheet()->getStyle('A' .$i. ':'.'F'.($i))->getBorders()->getAllBorders()->setBorderStyle(PHPExcel_Style_Border::BORDER_THIN);
	
	//data
	$objPHPExcel->getActiveSheet()->setCellValueExplicit('A' . $i, $stock_pallet_code, PHPExcel_Cell_DataType::TYPE_STRING);
	$objPHPExcel->getActiveSheet()->setCellValueExplicit('B' . $i, $stock_FG_code, PHPExcel_Cell_DataType::TYPE_STRING);
	$objPHPExcel->getActiveSheet()->setCellValue('C' . $i, $stock_location, PHPExcel_Cell_DataType::TYPE_STRING);
	
	if($stock_status == 'Match'){
		$phpColor = new PHPExcel_Style_Color();
		$phpColor->setRGB('00FF00');  
		$objPHPExcel->getActiveSheet()->getStyle('D' .($i). ':'.'D'.($i))->getFont()->setColor($phpColor);
		$objPHPExcel->getActiveSheet()->setCellValue('D' . $i, $stock_status);
	}else{
		$phpColor = new PHPExcel_Style_Color();
		$phpColor->setRGB('FF3333');  
		$objPHPExcel->getActiveSheet()->getStyle('D' .($i). ':'.'D'.($i))->getFont()->setColor($phpColor);
		$objPHPExcel->getActiveSheet()->setCellValue('D' . $i, $stock_status);
	}
    
    $objPHPExcel->getActiveSheet()->setCellValue('E' . $i, $stock_qty);
}
	
$i = $i + 1;
	
//merge
//$objPHPExcel->setActiveSheetIndex(0)->mergeCells('A' .$i. ':'.'I'.($i));
//$objPHPExcel->setActiveSheetIndex(0)->mergeCells('M' .$i. ':'.'R'.($i));

//border
$objPHPExcel->getActiveSheet()->getStyle('D' .$i. ':'.'F'.($i))->getBorders()->getAllBorders()->setBorderStyle(PHPExcel_Style_Border::BORDER_THIN);

//Set main
$objPHPExcel->getActiveSheet()->getStyle('E' .($i). ':'.'E'.($i))->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_RIGHT);
$objPHPExcel->getActiveSheet()->getStyle('F' .($i). ':'.'F'.($i))->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_RIGHT);

//Set BG
$objPHPExcel->getActiveSheet()->getStyle('D' .($i). ':'.'F'.($i))->getFill()->setFillType(PHPExcel_Style_Fill::FILL_SOLID)->getStartColor()->setARGB('FFFFFF00');

//data d
$objPHPExcel->getActiveSheet()->setCellValue('A' . $i, '');
$objPHPExcel->getActiveSheet()->setCellValue('B' . $i, '');
$objPHPExcel->getActiveSheet()->setCellValue('C' . $i, '');
$objPHPExcel->getActiveSheet()->setCellValue('D' . $i, 'Total Qty: ');
$objPHPExcel->getActiveSheet()->setCellValueExplicit('E' . $i, $str_sum_stock, PHPExcel_Cell_DataType::TYPE_STRING);
$objPHPExcel->getActiveSheet()->setCellValue('F' . $i, 'Pcs.');


// Rename sheet 
$objPHPExcel->getActiveSheet()->setTitle('Report FG Stock By Tags');

$objPHPExcel->setActiveSheetIndex(0);
// Redirect output to a client’s web browser (Excel5)
header('Content-Type: application/vnd.ms-excel');
header('Content-Disposition: attachment;filename="Report FG Stock Checking By Tags ('.$buffer_datetime.').xls"');
header('Cache-Control: max-age=0');

$objWriter = PHPExcel_IOFactory::createWriter($objPHPExcel, 'Excel5');
$objWriter->save('php://output');
exit;
?>