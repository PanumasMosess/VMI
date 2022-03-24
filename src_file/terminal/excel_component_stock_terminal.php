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
$t_cur_user_session_VMI_GDJ = isset($_SESSION['t_cur_user_session_VMI_GDJ']) ? $_SESSION['t_cur_user_session_VMI_GDJ'] : '';

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
$objPHPExcel->getActiveSheet()->getStyle('A1:F2')->getFont()->getColor()->setARGB(PHPExcel_Style_Color::COLOR_BLACK);
$objPHPExcel->getActiveSheet()->getStyle('A1:F2')->getFont()->setBold(true);
$objPHPExcel->getActiveSheet()->getStyle("A1:F2")->getFont()->setSize(10);

//header
$objPHPExcel->setActiveSheetIndex(0)->mergeCells('A1:F1');
$objPHPExcel->getActiveSheet()->setCellValue('A1', "Report Stock by FG Code GDJ on $buffer_datetime");

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

$objPHPExcel->getActiveSheet()->setCellValue('A2', "FG Code GDJ");
$objPHPExcel->getActiveSheet()->setCellValue('B2', "Qty. (Pcs.)");
$objPHPExcel->getActiveSheet()->setCellValue('C2', "Part Customer");
$objPHPExcel->getActiveSheet()->setCellValue('D2', "Status");
$objPHPExcel->getActiveSheet()->setCellValue('E2', "Project Name");
$objPHPExcel->getActiveSheet()->setCellValue('F2', "Component");


if($tag_locate == 'ALL'){

    if($t_cur_user_session_VMI_GDJ == "IT" || $t_cur_user_session_VMI_GDJ == "GDJ"){
    
    //get data
    $strSql_d = " 
    SELECT	
    ps_t_fg_code_gdj,
    SUM(ps_t_tags_packing_std) QTY,
    ps_t_part_customer,
    receive_status,
    ps_t_pj_name,
    bom_sku_code
            
    FROM tbl_receive
    left join tbl_picking_tail
    on tbl_receive.receive_tags_code = tbl_picking_tail.ps_t_tags_code
    left join tbl_usage_conf
    on tbl_usage_conf.usage_tags_code = tbl_receive.receive_tags_code
    left join tbl_picking_head
    on tbl_picking_head.ps_h_picking_code = tbl_picking_tail.ps_t_picking_code
    left join tbl_dn_tail 
    on tbl_picking_head.ps_h_picking_code = tbl_dn_tail.dn_t_picking_code
    left join tbl_dn_head
    on tbl_dn_tail.dn_t_dtn_code = tbl_dn_head.dn_h_dtn_code	
    left join tbl_bom_mst 
    on tbl_bom_mst.bom_fg_code_set_abt = tbl_picking_tail.ps_t_fg_code_set_abt
    and tbl_bom_mst.bom_fg_sku_code_abt = tbl_picking_tail.ps_t_sku_code_abt
    and tbl_bom_mst.bom_pj_name = tbl_picking_tail.ps_t_pj_name
    and tbl_bom_mst.bom_fg_code_gdj = tbl_picking_tail.ps_t_fg_code_gdj
    and tbl_bom_mst.bom_part_customer = tbl_picking_tail.ps_t_part_customer
    and tbl_bom_mst.bom_ship_type = tbl_picking_tail.ps_t_ship_type
    
    where (receive_status != 'USAGE CONFIRM'and receive_status != 'Received' and receive_status != 'Picking' and
    receive_status != 'Delivery Transfer Note' and bom_status = 'Active')  
    
    group by 
    ps_t_fg_code_gdj,
    ps_t_tags_packing_std,
    ps_t_part_customer,
    receive_status,
    ps_t_pj_name,
    bom_sku_code
    ";
    
    }else{
    
              //get data
    $strSql_d = " 
    SELECT	
    ps_t_fg_code_gdj,
    SUM(ps_t_tags_packing_std) QTY,
    ps_t_part_customer,
    receive_status,
    ps_t_pj_name,
    bom_sku_code
        
        
    FROM tbl_receive
    left join tbl_picking_tail
    on tbl_receive.receive_tags_code = tbl_picking_tail.ps_t_tags_code
    left join tbl_usage_conf
    on tbl_usage_conf.usage_tags_code = tbl_receive.receive_tags_code
    left join tbl_picking_head
    on tbl_picking_head.ps_h_picking_code = tbl_picking_tail.ps_t_picking_code
    left join tbl_dn_tail 
    on tbl_picking_head.ps_h_picking_code = tbl_dn_tail.dn_t_picking_code
    left join tbl_dn_head
    on tbl_dn_tail.dn_t_dtn_code = tbl_dn_head.dn_h_dtn_code	
    left join tbl_bom_mst 
    on tbl_bom_mst.bom_fg_code_set_abt = tbl_picking_tail.ps_t_fg_code_set_abt
    and tbl_bom_mst.bom_fg_sku_code_abt = tbl_picking_tail.ps_t_sku_code_abt
    and tbl_bom_mst.bom_pj_name = tbl_picking_tail.ps_t_pj_name
    and tbl_bom_mst.bom_fg_code_gdj = tbl_picking_tail.ps_t_fg_code_gdj
    and tbl_bom_mst.bom_part_customer = tbl_picking_tail.ps_t_part_customer
    and tbl_bom_mst.bom_ship_type = tbl_picking_tail.ps_t_ship_type
    
    where (receive_status != 'USAGE CONFIRM' and receive_status != 'Received' and receive_status != 'Picking' and
    receive_status != 'Delivery Transfer Note' and bom_status = 'Active')  
    and (ps_t_pj_name IN (select bom_pj_name from tbl_bom_mst where bom_cus_code = '$t_cur_user_session_VMI_GDJ' GROUP BY bom_pj_name)) 
    
    
    group by 
    ps_t_fg_code_gdj,
    ps_t_tags_packing_std,
    ps_t_part_customer,
    receive_status,
    ps_t_pj_name,
    bom_sku_code";
    
    }
    
    }else{
    
        //get data
              //get data
    $strSql_d = " 
    SELECT	
    ps_t_fg_code_gdj,
    SUM(ps_t_tags_packing_std) QTY,
    ps_t_part_customer,
    receive_status,
    ps_t_pj_name,
    bom_sku_code
        
        
    FROM tbl_receive
    left join tbl_picking_tail
    on tbl_receive.receive_tags_code = tbl_picking_tail.ps_t_tags_code
    left join tbl_usage_conf
    on tbl_usage_conf.usage_tags_code = tbl_receive.receive_tags_code
    left join tbl_picking_head
    on tbl_picking_head.ps_h_picking_code = tbl_picking_tail.ps_t_picking_code
    left join tbl_dn_tail 
    on tbl_picking_head.ps_h_picking_code = tbl_dn_tail.dn_t_picking_code
    left join tbl_dn_head
    on tbl_dn_tail.dn_t_dtn_code = tbl_dn_head.dn_h_dtn_code	
    left join tbl_bom_mst 
    on tbl_bom_mst.bom_fg_code_set_abt = tbl_picking_tail.ps_t_fg_code_set_abt
    and tbl_bom_mst.bom_fg_sku_code_abt = tbl_picking_tail.ps_t_sku_code_abt
    and tbl_bom_mst.bom_pj_name = tbl_picking_tail.ps_t_pj_name
    and tbl_bom_mst.bom_fg_code_gdj = tbl_picking_tail.ps_t_fg_code_gdj
    and tbl_bom_mst.bom_part_customer = tbl_picking_tail.ps_t_part_customer
    and tbl_bom_mst.bom_ship_type = tbl_picking_tail.ps_t_ship_type
    
    where (receive_status != 'USAGE CONFIRM' and receive_status != 'Received' and receive_status != 'Picking' and
    receive_status != 'Delivery Transfer Note' and bom_status = 'Active')  
    and ps_t_pj_name = '$tag_locate' 
    
    group by 
    ps_t_fg_code_gdj,
    ps_t_tags_packing_std,
    ps_t_part_customer,
    receive_status,
    ps_t_pj_name,
    bom_sku_code
    
    ";
    
    }

$i = 2;
$str_sum_stock = 0;

$objQuery_d = sqlsrv_query($db_con, $strSql_d);
while($objResult_d = sqlsrv_fetch_array($objQuery_d, SQLSRV_FETCH_ASSOC))
{
	$i++;
	
	$ps_t_fg_code_gdj = $objResult_d['ps_t_fg_code_gdj'];
	$QTY = $objResult_d['QTY'];
    $ps_t_part_customer = $objResult_d['ps_t_part_customer'];
    $receive_status = $objResult_d['receive_status'];
    $ps_t_pj_name = $objResult_d['ps_t_pj_name'];
    $bom_sku_code = $objResult_d['bom_sku_code'];


	
	//total count
	$str_sum_stock = $str_sum_stock + $QTY;
	
	//Set main
	$objPHPExcel->getActiveSheet()->getStyle('B' .($i). ':'.'B'.($i))->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_RIGHT);
	
	//border
	$objPHPExcel->getActiveSheet()->getStyle('A' .$i. ':'.'F'.($i))->getBorders()->getAllBorders()->setBorderStyle(PHPExcel_Style_Border::BORDER_THIN);
	
	//data
	$objPHPExcel->getActiveSheet()->setCellValueExplicit('A' . $i, $ps_t_fg_code_gdj, PHPExcel_Cell_DataType::TYPE_STRING);
	$objPHPExcel->getActiveSheet()->setCellValueExplicit('B' . $i, number_format($QTY), PHPExcel_Cell_DataType::TYPE_STRING);
	$objPHPExcel->getActiveSheet()->setCellValue('C' . $i, $ps_t_part_customer);
	$objPHPExcel->getActiveSheet()->setCellValue('D' . $i, $receive_status);
	$objPHPExcel->getActiveSheet()->setCellValue('E' . $i, $ps_t_pj_name);
	$objPHPExcel->getActiveSheet()->setCellValue('F' . $i, $bom_sku_code);


}
	
$i = $i + 1;
	
//merge
//$objPHPExcel->setActiveSheetIndex(0)->mergeCells('A' .$i. ':'.'I'.($i));
//$objPHPExcel->setActiveSheetIndex(0)->mergeCells('M' .$i. ':'.'R'.($i));

//border
$objPHPExcel->getActiveSheet()->getStyle('A' .$i. ':'.'C'.($i))->getBorders()->getAllBorders()->setBorderStyle(PHPExcel_Style_Border::BORDER_THIN);

//Set main
////$objPHPExcel->getActiveSheet()->getStyle('E' .($i). ':'.'E'.($i))->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_RIGHT);
//$objPHPExcel->getActiveSheet()->getStyle('F' .($i). ':'.'F'.($i))->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_RIGHT);

//Set BG
$objPHPExcel->getActiveSheet()->getStyle('A' .($i). ':'.'C'.($i))->getFill()->setFillType(PHPExcel_Style_Fill::FILL_SOLID)->getStartColor()->setARGB('FFFFFF00');

//data d
$objPHPExcel->getActiveSheet()->setCellValue('A' . $i, 'Total Qty:');
$objPHPExcel->getActiveSheet()->setCellValue('B' . $i, number_format($str_sum_stock), PHPExcel_Cell_DataType::TYPE_STRING);
$objPHPExcel->getActiveSheet()->setCellValue('C' . $i, 'Pcs.');
$objPHPExcel->getActiveSheet()->setCellValueExplicit('D' . $i, '');
$objPHPExcel->getActiveSheet()->setCellValue('E' . $i, '');
$objPHPExcel->getActiveSheet()->setCellValueExplicit('F' . $i, '');
$objPHPExcel->getActiveSheet()->setCellValueExplicit('G' . $i, '');
$objPHPExcel->getActiveSheet()->setCellValue('I' . $i, '');

// Rename sheet 
$objPHPExcel->getActiveSheet()->setTitle('Report Stock by FG Code GDJ');

$objPHPExcel->setActiveSheetIndex(0);
// Redirect output to a client’s web browser (Excel5)
header('Content-Type: application/vnd.ms-excel');
header('Content-Disposition: attachment;filename="Report Stock by FG Code GDJ('.$buffer_datetime.').xls"');
header('Cache-Control: max-age=0');

$objWriter = PHPExcel_IOFactory::createWriter($objPHPExcel, 'Excel5');
$objWriter->save('php://output');
exit;
?>