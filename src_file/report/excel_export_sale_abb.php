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



//Set BG
$objPHPExcel->getActiveSheet()->getStyle('A1:K1')->getFill()->setFillType(PHPExcel_Style_Fill::FILL_SOLID)->getStartColor()->setARGB('FFFFFFFF');

	
// Set fonts
$objPHPExcel->getActiveSheet()->getStyle('A1:K1')->getFont()->getColor()->setARGB(PHPExcel_Style_Color::COLOR_RED);
$objPHPExcel->getActiveSheet()->getStyle('A1:K1')->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
$objPHPExcel->getActiveSheet()->getStyle('A1:K1')->getFont()->setBold(true);
$objPHPExcel->getActiveSheet()->getStyle('A1:K1')->getFont()->setSize(10);

$objPHPExcel->getActiveSheet()->setCellValue('A1', "Row_Number*");
$objPHPExcel->getActiveSheet()->setCellValue('B1', "Receipt_Number*");
$objPHPExcel->getActiveSheet()->setCellValue('C1', "Receipt_Date_dd/mm/yy*");
$objPHPExcel->getActiveSheet()->setCellValue('D1', "Branch*");
$objPHPExcel->getActiveSheet()->setCellValue('E1', "POS_ID*");
$objPHPExcel->getActiveSheet()->setCellValue('F1', "Transport_Fee*");
$objPHPExcel->getActiveSheet()->setCellValue('G1', "Discount_Amount*");
$objPHPExcel->getActiveSheet()->setCellValue('H1', "Item_Code*");
$objPHPExcel->getActiveSheet()->setCellValue('I1', "Item_Description*");
$objPHPExcel->getActiveSheet()->setCellValue('J1', "Qty*");
$objPHPExcel->getActiveSheet()->setCellValue('K1', "Unit_Price*");

//get data
$strSql_d = " 
SELECT [b2c_sale_date]
      ,[b2c_sale_time]
      ,[b2c_sale_order_id]
      ,[b2c_sale_pos_no]
      ,[b2c_sale_inv_no]
      ,[b2c_sale_excluding_vat]
      ,[b2c_sale_tax]
      ,[b2c_sale_including_vat]
      ,[b2c_sale_remark]
      ,[b2c_sale_branch]
	  ,[repn_sku_code_abt]
	  ,[bom_fg_desc]
	   ,[repn_qty]
	  ,[bom_price_sale_per_pcs]
      ,[b2c_sale_transport_fee]
      ,[b2c_sale_discount_amount]
  FROM [tbl_b2c_sale] 
  left join tbl_b2c_detail
  on tbl_b2c_sale.b2c_sale_order_id = tbl_b2c_detail.b2c_repn_order_ref
  left join tbl_replenishment
  on tbl_b2c_detail.b2c_repn_order_ref = tbl_replenishment.repn_order_ref
  left join tbl_bom_mst
  on tbl_replenishment.repn_sku_code_abt = tbl_bom_mst.bom_fg_sku_code_abt
  where  (b2c_sale_date between '$start_' and '$end_')
";

$i = 1;
$row_id_report = 0;

$objQuery_d = sqlsrv_query($db_con, $strSql_d);
while($objResult_d = sqlsrv_fetch_array($objQuery_d, SQLSRV_FETCH_ASSOC))
{
	$i++;
    $row_id_report ++;
	
	$b2c_sale_date = $objResult_d['b2c_sale_date'];
    $b2c_sale_time = $objResult_d['b2c_sale_time'];
    $b2c_sale_order_id = $objResult_d['b2c_sale_order_id'];
    $b2c_sale_pos_no = $objResult_d['b2c_sale_pos_no'];
    $b2c_sale_inv_no = $objResult_d['b2c_sale_inv_no'];
    $b2c_sale_excluding_vat = $objResult_d['b2c_sale_excluding_vat'];
    $b2c_sale_tax = $objResult_d['b2c_sale_tax'];
    $b2c_sale_including_vat = $objResult_d['b2c_sale_including_vat'];
    $b2c_sale_remark = $objResult_d['b2c_sale_remark'];
    $b2c_sale_branch = $objResult_d['b2c_sale_branch'];
    $repn_sku_code_abt = $objResult_d['repn_sku_code_abt'];
    $bom_fg_desc = $objResult_d['bom_fg_desc'];
    $repn_qty = $objResult_d['repn_qty'];
    $bom_price_sale_per_pcs = $objResult_d['bom_price_sale_per_pcs'];
    $b2c_sale_transport_fee = $objResult_d['b2c_sale_transport_fee'];
    $b2c_sale_discount_amount = $objResult_d['b2c_sale_discount_amount'];

    if($b2c_sale_branch == null){
        $b2c_sale_branch = "0";
    }
	
	
	//Set main
	$objPHPExcel->getActiveSheet()->getStyle('A' .($i). ':'.'K'.($i))->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
	
		
	//data
	$objPHPExcel->getActiveSheet()->setCellValue('A' . $i, $row_id_report);
	$objPHPExcel->getActiveSheet()->setCellValue('B' . $i, $b2c_sale_inv_no);
	$objPHPExcel->getActiveSheet()->setCellValue('C' . $i, date( "d/m/y", strtotime($b2c_sale_date)));
	$objPHPExcel->getActiveSheet()->setCellValue('D' . $i, $b2c_sale_branch);
	$objPHPExcel->getActiveSheet()->setCellValue('E' . $i, 'E05210002A1702');
	$objPHPExcel->getActiveSheet()->setCellValue('F' . $i, $b2c_sale_transport_fee);
	$objPHPExcel->getActiveSheet()->setCellValue('G' . $i, $b2c_sale_discount_amount);
	$objPHPExcel->getActiveSheet()->setCellValue('H' . $i, $repn_sku_code_abt);
    $objPHPExcel->getActiveSheet()->setCellValue('I' . $i, $bom_fg_desc);
    $objPHPExcel->getActiveSheet()->setCellValue('J' . $i, $repn_qty);
    $objPHPExcel->getActiveSheet()->setCellValue('K' . $i, $bom_price_sale_per_pcs);

}
	
$i = $i + 1;


// Rename sheet 
$objPHPExcel->getActiveSheet()->setTitle('1');

$objPHPExcel->setActiveSheetIndex(0);
// Redirect output to a client’s web browser (Excel5)
header('Content-Type: application/vnd.ms-excel');
header('Content-Disposition: attachment;filename="Upload ACC ('.$buffer_datetime.').xls"');
header('Cache-Control: max-age=0');

$objWriter = PHPExcel_IOFactory::createWriter($objPHPExcel, 'Excel5');
$objWriter->save('php://output');
exit;
?>