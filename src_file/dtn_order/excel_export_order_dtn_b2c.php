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

$dtn_code = isset($_REQUEST['dtn_code']) ? $_REQUEST['dtn_code'] : '';

//decode
$dtn_code = var_decode($dtn_code);

//no memory limit
ini_set('memory_limit', '-1');

// Create new PHPExcel object
$objPHPExcel = new PHPExcel();

$objPHPExcel->getActiveSheet()->setShowGridlines(true); //guiline hide= 'false'
	
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
// $objPHPExcel->getActiveSheet()
//     ->getHeaderFooter()
//     ->setOddHeader('&R Page &P / &N
// 	&D &T')
// 	->setOddFooter('&L Printed on &D &T &R Glong Duang Jai Co., Ltd.');
		
// $styleArray = array(
// 			'font'  => array(
// 				'bold'  => true
// 			));			

// // Set fonts
// $objPHPExcel->getActiveSheet()->getStyle('A1:E1')->getFont()->getColor()->setARGB(PHPExcel_Style_Color::COLOR_BLACK);
// $objPHPExcel->getActiveSheet()->getStyle('A1:E1')->getFont()->setBold(true);
// $objPHPExcel->getActiveSheet()->getStyle("A1:E1")->getFont()->setSize(10);

// //header
// $objPHPExcel->setActiveSheetIndex(0)->mergeCells('A1:E1');
// $objPHPExcel->getActiveSheet()->setCellValue('A1', "Report FG Stock Checking By Tags On $buffer_datetime");

// // //border		
// $objPHPExcel->getActiveSheet()->getStyle('A1:AD2')->applyFromArray($styleArray);
// $objPHPExcel->getActiveSheet()->getStyle('A1:AD2')->getBorders()->getAllBorders()->setBorderStyle(PHPExcel_Style_Border::BORDER_MEDIUM);

//Set BG
$objPHPExcel->getActiveSheet()->getStyle('A1:AD1')->getFill()->setFillType(PHPExcel_Style_Fill::FILL_SOLID)->getStartColor()->setARGB('FFFFFFFF');
	
// Set fonts
$objPHPExcel->getActiveSheet()->getStyle('Q1:AD1')->getFont()->getColor()->setARGB(PHPExcel_Style_Color::COLOR_BLACK);
$objPHPExcel->getActiveSheet()->getStyle('I1:I1')->getFont()->getColor()->setARGB(PHPExcel_Style_Color::COLOR_BLACK);
$objPHPExcel->getActiveSheet()->getStyle('O1:O1')->getFont()->getColor()->setARGB(PHPExcel_Style_Color::COLOR_BLACK);
$objPHPExcel->getActiveSheet()->getStyle('A1:H1')->getFont()->getColor()->setARGB(PHPExcel_Style_Color::COLOR_RED);
$objPHPExcel->getActiveSheet()->getStyle('J1:N1')->getFont()->getColor()->setARGB(PHPExcel_Style_Color::COLOR_RED);
$objPHPExcel->getActiveSheet()->getStyle('P1:P1')->getFont()->getColor()->setARGB(PHPExcel_Style_Color::COLOR_RED);
$objPHPExcel->getActiveSheet()->getStyle('A1:AD1')->getFont()->setBold(true);
$objPHPExcel->getActiveSheet()->getStyle('A1:AD1')->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
$objPHPExcel->getActiveSheet()->getStyle('A1:AD1')->getAlignment()->setVertical(PHPExcel_Style_Alignment::VERTICAL_CENTER);
$objPHPExcel->getActiveSheet()->getStyle('A1:AD1')->getFont()->setSize(9);

$objPHPExcel->getActiveSheet()->setCellValue('A1', "OrderCode*");
$objPHPExcel->getActiveSheet()->setCellValue('B1', "OrderDate*");
$objPHPExcel->getActiveSheet()->setCellValue('C1', "ShipperCode*");
$objPHPExcel->getActiveSheet()->setCellValue('D1', "ShipperName*");
$objPHPExcel->getActiveSheet()->setCellValue('E1', "ShipperAddress*");
$objPHPExcel->getActiveSheet()->setCellValue('F1', "ShipperZipcode*");
$objPHPExcel->getActiveSheet()->setCellValue('G1', "ShipperTel*");
$objPHPExcel->getActiveSheet()->setCellValue('H1', "DeliveryDate*");
$objPHPExcel->getActiveSheet()->setCellValue('I1', "CustomerCode");
$objPHPExcel->getActiveSheet()->setCellValue('J1', "CustomerName*");
$objPHPExcel->getActiveSheet()->setCellValue('K1', "DeliveryAddress*");
$objPHPExcel->getActiveSheet()->setCellValue('L1', "Zipcode*");
$objPHPExcel->getActiveSheet()->setCellValue('M1', "ContactName*");
$objPHPExcel->getActiveSheet()->setCellValue('N1', "Tel*");
$objPHPExcel->getActiveSheet()->setCellValue('O1', "Note");
$objPHPExcel->getActiveSheet()->setCellValue('P1', "Total Boxes*");
$objPHPExcel->getActiveSheet()->setCellValue('Q1', "Weight Kgs");
$objPHPExcel->getActiveSheet()->setCellValue('R1', "Total CBM");
$objPHPExcel->getActiveSheet()->setCellValue('S1', "COD Amount");
$objPHPExcel->getActiveSheet()->setCellValue('T1', "TrackingNumber");
$objPHPExcel->getActiveSheet()->setCellValue('U1', "sender_identity_fname");
$objPHPExcel->getActiveSheet()->setCellValue('V1', "sender_identity_lname");
$objPHPExcel->getActiveSheet()->setCellValue('W1', "sender_identity_idcardnumber");
$objPHPExcel->getActiveSheet()->setCellValue('X1', "COD_accept");
$objPHPExcel->getActiveSheet()->setCellValue('Y1', "CCOD_accept");
$objPHPExcel->getActiveSheet()->setCellValue('Z1', "insurance_accept");
$objPHPExcel->getActiveSheet()->setCellValue('AA1', "insurance_amount");
$objPHPExcel->getActiveSheet()->setCellValue('AB1', "document_return_accept");
$objPHPExcel->getActiveSheet()->setCellValue('AC1', "product_return_accept");
$objPHPExcel->getActiveSheet()->setCellValue('AD1', "TransferCodCode");

//get data
$strSql_d = " 
SELECT 
	 dn_h_dtn_code
	 ,dn_h_delivery_date
	,[b2c_repn_order_ref]
     ,[b2c_customer_code]
     ,[b2c_customer_name]
     ,[b2c_delivery_address]
     ,[b2c_zipcode]
     ,[b2c_contact_name]
     ,[b2c_tel]
     ,[b2c_note]
	 ,[b2c_order_date]
	,[ps_t_location]
	,sum(ps_t_tags_packing_std) as qty
	,[ps_t_cus_name]
	,[ps_t_pj_name]
	,[ps_t_replenish_unit_type]
	,sum([ps_t_replenish_qty_to_pack]) as ps_t_replenish_qty_to_pack
FROM tbl_dn_head
left join tbl_dn_tail 
on tbl_dn_head.dn_h_dtn_code = tbl_dn_tail.dn_t_dtn_code
left join tbl_picking_head
on tbl_dn_tail.dn_t_picking_code = tbl_picking_head.ps_h_picking_code
left join tbl_picking_tail
on tbl_picking_head.ps_h_picking_code = tbl_picking_tail.ps_t_picking_code
left join tbl_b2c_detail
on tbl_picking_tail.ps_t_ref_replenish_code = tbl_b2c_detail.[b2c_repn_order_ref]
left join tbl_bom_mst 
on tbl_picking_tail.ps_t_fg_code_set_abt = tbl_bom_mst.bom_fg_code_set_abt
and tbl_picking_tail.ps_t_sku_code_abt = tbl_bom_mst.bom_fg_sku_code_abt
and tbl_picking_tail.ps_t_fg_code_gdj = tbl_bom_mst.bom_fg_code_gdj
and tbl_picking_tail.ps_t_pj_name = tbl_bom_mst.bom_pj_name
and tbl_picking_tail.ps_t_ship_type = tbl_bom_mst.bom_ship_type
and tbl_picking_tail.ps_t_part_customer = tbl_bom_mst.bom_part_customer
where
[dn_t_dtn_code] = '$dtn_code'
group by 
			
     dn_h_dtn_code
	,dn_h_delivery_date
	,[b2c_repn_order_ref]
     ,[b2c_customer_code]
     ,[b2c_customer_name]
     ,[b2c_delivery_address]
     ,[b2c_zipcode]
     ,[b2c_contact_name]
     ,[b2c_tel]
     ,[b2c_note]
	 ,[b2c_order_date]
	,[ps_t_location]
	 ,[ps_t_tags_packing_std]
	,[ps_t_cus_name]
	,[ps_t_pj_name]
	,[ps_t_replenish_unit_type]
	,[ps_t_replenish_qty_to_pack]
";

$i = 1;
// $str_sum_stock = 0;

$objQuery_d = sqlsrv_query($db_con, $strSql_d);
while($objResult_d = sqlsrv_fetch_array($objQuery_d, SQLSRV_FETCH_ASSOC))
{
    $i++;
    $dn_h_dtn_code = $objResult_d['dn_h_dtn_code'];
	$dn_h_delivery_date = $objResult_d['dn_h_delivery_date'];
	$b2c_repn_order_ref = $objResult_d['b2c_repn_order_ref'];
	$b2c_customer_code = $objResult_d['b2c_customer_code'];
	$b2c_customer_name = $objResult_d['b2c_customer_name'];
	$b2c_delivery_address = $objResult_d['b2c_delivery_address'];
	$b2c_zipcode = $objResult_d['b2c_zipcode'];
	$b2c_contact_name = $objResult_d['b2c_contact_name'];
	$b2c_tel = $objResult_d['b2c_tel'];
	$b2c_note = $objResult_d['b2c_note'];
	$b2c_order_date = $objResult_d['b2c_order_date'];
	$ps_t_location = $objResult_d['ps_t_location'];
	$qty = $objResult_d['qty'];
	$ps_t_cus_name = $objResult_d['ps_t_cus_name'];
	$ps_t_pj_name = $objResult_d['ps_t_pj_name'];
	$ps_t_replenish_unit_type = $objResult_d['ps_t_replenish_unit_type'];
	$ps_t_replenish_qty_to_pack = $objResult_d['ps_t_replenish_qty_to_pack'];

	//Set main
	$objPHPExcel->getActiveSheet()->getStyle('P' .($i). ':'.'AD'.($i))->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_RIGHT);
	//$objPHPExcel->getActiveSheet()->getStyle('G' .($i). ':'.'G'.($i))->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
	
	//border
	//$objPHPExcel->getActiveSheet()->getStyle('A' .$i. ':'.'F'.($i))->getBorders()->getAllBorders()->setBorderStyle(PHPExcel_Style_Border::BORDER_THIN);
	
	//data
	$objPHPExcel->getActiveSheet()->setCellValueExplicit('A' . $i, $dn_h_dtn_code, PHPExcel_Cell_DataType::TYPE_STRING);
	$objPHPExcel->getActiveSheet()->setCellValue('B' . $i, $b2c_order_date);
	$objPHPExcel->getActiveSheet()->setCellValueExplicit('C' . $i, '12345678912', PHPExcel_Cell_DataType::TYPE_STRING);
    $objPHPExcel->getActiveSheet()->setCellValue('D' . $i, 'GLONG DUANG JAI');
    $objPHPExcel->getActiveSheet()->setCellValue('E' . $i, 'GLONG DUANG JAI CO.,LTD. Head Office 336/11 Moo 7 Bowin - Sriracha ชลบุรี');
    $objPHPExcel->getActiveSheet()->setCellValue('F' . $i, '20110');
    $objPHPExcel->getActiveSheet()->setCellValueExplicit('G' . $i, '0989878678', PHPExcel_Cell_DataType::TYPE_STRING);
    $objPHPExcel->getActiveSheet()->setCellValue('H' . $i, $dn_h_delivery_date);
    $objPHPExcel->getActiveSheet()->setCellValue('I' . $i, 'test01');
    $objPHPExcel->getActiveSheet()->setCellValue('J' . $i, $b2c_customer_name);
    $objPHPExcel->getActiveSheet()->setCellValue('K' . $i, $b2c_delivery_address);
    $objPHPExcel->getActiveSheet()->setCellValue('L' . $i, $b2c_zipcode);
    $objPHPExcel->getActiveSheet()->setCellValue('M' . $i, $b2c_customer_name);
    $objPHPExcel->getActiveSheet()->setCellValueExplicit('N' . $i, $b2c_tel, PHPExcel_Cell_DataType::TYPE_STRING);
    $objPHPExcel->getActiveSheet()->setCellValue('O' . $i, $b2c_note);
    $objPHPExcel->getActiveSheet()->setCellValue('P' . $i, '1');
    $objPHPExcel->getActiveSheet()->setCellValue('Q' . $i, '0');
    $objPHPExcel->getActiveSheet()->setCellValue('R' . $i, '-');
    $objPHPExcel->getActiveSheet()->setCellValue('S' . $i, '-');
    $objPHPExcel->getActiveSheet()->setCellValue('T' . $i, '-');
    $objPHPExcel->getActiveSheet()->setCellValue('U' . $i, '-');
    $objPHPExcel->getActiveSheet()->setCellValue('V' . $i, '-');
    $objPHPExcel->getActiveSheet()->setCellValue('W' . $i, '-');
    $objPHPExcel->getActiveSheet()->setCellValue('X' . $i, '-');
    $objPHPExcel->getActiveSheet()->setCellValue('Y' . $i, '-');
    $objPHPExcel->getActiveSheet()->setCellValue('Z' . $i, '-');
    $objPHPExcel->getActiveSheet()->setCellValue('AA' . $i,'-');
    $objPHPExcel->getActiveSheet()->setCellValue('AB' . $i,'-');
    $objPHPExcel->getActiveSheet()->setCellValue('AC' . $i,'-');
    $objPHPExcel->getActiveSheet()->setCellValue('AD' . $i,'-');
}
	
	


// Rename sheet 
$objPHPExcel->getActiveSheet()->setTitle('Template');

$objPHPExcel->setActiveSheetIndex(0);
// Redirect output to a client’s web browser (Excel5)
header('Content-Type: application/vnd.ms-excel');
header('Content-Disposition: attachment;filename="Upload B2C Data ('.$buffer_datetime.').xls"');
header('Cache-Control: max-age=0');

$objWriter = PHPExcel_IOFactory::createWriter($objPHPExcel, 'Excel5');
$objWriter->save('php://output');
exit;
?>