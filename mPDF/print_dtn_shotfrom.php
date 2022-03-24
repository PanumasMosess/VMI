<?
require_once("../application.php");

//bypass execu time / memory limit
ini_set('MAX_EXECUTION_TIME', '-1');
ini_set('memory_limit', '-1');
ini_set("pcre.backtrack_limit", "5000000");

$buffer_date = date("Y-m-d");
$buffer_time = date("H:i:s"); //24H
$buffer_datetime = date("Y-m-d H:i:s");

/**********************************************************************************/
/*current user ********************************************************************/
$t_cur_user_code_VMI_GDJ = isset($_SESSION['t_cur_user_code_VMI_GDJ']) ? $_SESSION['t_cur_user_code_VMI_GDJ'] : '';
$t_cur_user_type_VMI_GDJ = isset($_SESSION['t_cur_user_type_VMI_GDJ']) ? $_SESSION['t_cur_user_type_VMI_GDJ'] : '';

/**********************************************************************************/
/*var *****************************************************************************/
$tag = isset($_REQUEST['tag']) ? $_REQUEST['tag'] : '';

//decode
$tag = var_decode($tag);

$strSql_DTNHead = " 
	SELECT 
		[dn_h_dtn_code]
		,[dn_h_cus_code]
		,[dn_h_cus_name]
		,[dn_h_cus_address]
		,[dn_h_driver_code]
		,[dn_h_delivery_date]
		,[dn_h_delivery_time]
		,[dn_h_status]
		,[dn_h_issue_by]
		,[dn_h_issue_datetime]
		,[driver_code]
		,[driver_pass_md5]
		,[driver_name_th]
		,[driver_name_en]
		,[driver_company]
		,[driver_section]
		,[driver_truck_head_no]
		,[driver_truck_tail_no]
		,[driver_truck_type]
		,[driver_status]
	FROM tbl_dn_head
	left join
	tbl_driver_mst
	on tbl_dn_head.dn_h_driver_code = tbl_driver_mst.driver_code
	where dn_h_dtn_code = '$tag' 
";

$objQuery_DTNHead = sqlsrv_query($db_con, $strSql_DTNHead, $params, $options);
$num_row_DTNHead = sqlsrv_num_rows($objQuery_DTNHead);

while($objResult_DTNHead = sqlsrv_fetch_array($objQuery_DTNHead, SQLSRV_FETCH_ASSOC))
{
	$dn_h_dtn_code = $objResult_DTNHead['dn_h_dtn_code'];
	$dn_h_cus_code = $objResult_DTNHead['dn_h_cus_code'];
	$dn_h_cus_name = $objResult_DTNHead['dn_h_cus_name'];
	$dn_h_cus_address = $objResult_DTNHead['dn_h_cus_address'];
	$dn_h_driver_code = $objResult_DTNHead['dn_h_driver_code'];
	$dn_h_delivery_date = $objResult_DTNHead['dn_h_delivery_date'];
	$dn_h_delivery_time = substr($objResult_DTNHead['dn_h_delivery_time'],0,8);
	$dn_h_issue_by = $objResult_DTNHead['dn_h_issue_by'];
	$dn_h_issue_datetime = substr($objResult_DTNHead['dn_h_issue_datetime'],0,19);
	$driver_name_th = $objResult_DTNHead['driver_name_th'];
	$driver_name_en = $objResult_DTNHead['driver_name_en'];
	$driver_company = $objResult_DTNHead['driver_company'];
	$driver_section = $objResult_DTNHead['driver_section'];
	$driver_truck_head_no = $objResult_DTNHead['driver_truck_head_no'];
	$driver_truck_tail_no = $objResult_DTNHead['driver_truck_tail_no'];
	$driver_truck_type = $objResult_DTNHead['driver_truck_type'];

}

//////////////////////////////////////////////
/////////////////////mPDF/////////////////////
//////////////////////////////////////////////
// Require composer autoload
require_once __DIR__ . '/vendor/autoload.php';

$defaultFontConfig = (new Mpdf\Config\FontVariables())->getDefaults();
$fontData = $defaultFontConfig['fontdata'];
$mpdf = new \Mpdf\Mpdf(['tempDir' => __DIR__ . '/tmp',
    'fontdata' => $fontData + [
            'sarabun' => [
                'R' => 'THSarabunNew.ttf',
                'I' => 'THSarabunNewItalic.ttf',
                'B' =>  'THSarabunNewBold.ttf',
                'BI' => "THSarabunNewBoldItalic.ttf",
            ]
        ],
]);

$mpdf = new \Mpdf\Mpdf([
	'margin_left' => 10,
	'margin_right' => 10,
	'margin_top' => 15,
	'margin_bottom' => 10,
	'margin_header' => 10,
	'margin_footer' => 10,
]);


$mpdf->SetProtection(array('print'));
$mpdf->SetTitle("Print Delivery Transfer Note");
$mpdf->SetAuthor("Albatross Logistics Co.,Ltd.");
$mpdf->SetWatermarkText("VMI Delivery Transfer Note");
$mpdf->showWatermarkText = false;
$mpdf->watermark_font = 'DejaVuSansCondensed';
$mpdf->watermarkTextAlpha = 0.1;
$mpdf->SetDisplayMode('fullpage');


$html = '
<html>
<head>
<style>
@page {
	size: auto;
	odd-header-name: html_MyHeader;
}

@page rotated {
	size: landscape;
}

.barcode {
	padding: 1.5mm;
	margin: 0;
	vertical-align: top;
	color: #000000;
}
.barcodecell {
	text-align: center;
	vertical-align: middle;
	padding: 2;
}
.qrCode {
	padding: 1.5mm;
	margin: 0;
	color: #000000;
}
</style>
</head>
<body>
    <htmlpageheader name="MyHeader">
        <table width="100%" style="vertical-align: bottom; font-family: serif; font-size: 8pt; color: #000000; font-weight: bold; font-style: italic;">
            <tr>
                <td width="33%"><span style="font-weight: bold; font-style: italic;">VMI Delivery Transfer Note</span></td>
                <td width="33%" align="center" style="font-weight: bold; font-style: italic;">{PAGENO}/{nbpg}</td>
                <td width="33%" style="text-align: right; ">Printed: {DATE j-m-Y}</td>
            </tr>
        </table>
    </htmlpageheader>
';

$html .= '
<table width="100%" border="0" cellpadding="0" cellspacing="0">
<tr>
  <td colspan="2" align="left"><img src="../logo_company/GDJ_png2.png" style="width: 100px; padding: 0px;" /></td>
  <td colspan="4" align="center"><font style="font-size: 15pt;"><b>Delivery Transfer Note</b></font><br><barcode code="'.$dn_h_dtn_code.'" type="C39" class="barcode" size="0.8" height="1.5"/><br>'.$dn_h_dtn_code.'</td>	  
  <td colspan="2" align="right">
  <barcode code="'.$dn_h_dtn_code.'" class="qrCode" type="QR" size="0.6" error="M" disableborder = "1"/></td>
</tr>
<tr>
  <td colspan="7" style="font-size: 10pt; border-top:solid 1px #000; border-left:solid 1px #000; border-right:solid 1px #000;">&nbsp;<b>Customer:</b> '.$dn_h_cus_name.'<br>&nbsp;<b>Address:</b> '.$dn_h_cus_address.'</td>	  
</tr>
<tr>
  <td colspan="7" align="left"><table width="100%" border="0" cellpadding="0" cellspacing="0" style="font-size: 10pt; border-top:solid 1px #000; border-bottom:solid 1px #000; border-left:solid 1px #000; border-right:solid 1px #000;">
	<tr>
	  <td width="50%" style="border-right:solid 1px #000;">
		&nbsp;<b>Delivery Transfer Note:</b> '.$dn_h_dtn_code.'<br>&nbsp;<b>Driver Name:</b> '.ucfirst($driver_name_en).'
	  </td>
	  <td>
		&nbsp;<b>Issue Date:</b> <font style="font-size: 9pt;">'.$dn_h_issue_datetime.'</font><br>&nbsp;<b>Truck Head ID:</b> '.$driver_truck_head_no.'
	  </td>
	</tr>
	<tr>
	  <td width="50%" style="border-right:solid 1px #000;">
		&nbsp;<b>Truck Tail ID:</b> '.$driver_truck_tail_no.'<br>&nbsp;<b>Delivery Date:</b> '.$dn_h_delivery_date.'
	  </td>
	  <td>
		&nbsp;<b>Truck Type:</b> <font style="font-size: 9pt;">'.$driver_truck_type.'</font><br>&nbsp;<b>Delivery Time:</b> '.$dn_h_delivery_time.'
	  </td>
	</tr>
</table></td>	  
</tr>
<tr>
  <td colspan="8" style="font-size: 5pt;">&nbsp;</td>	  
</tr>
<tr>
<tr>
<td rowspan="2" style="font-size: 9pt; text-align: center; border-top:solid 1px #000; border-left:solid 1px #000; border-right:solid 1px #000; background-color: #D3D3D3;"><b>No.</b></td>
<td style="font-size: 9pt; text-align: center; border-top:solid 1px #000; border-right:solid 1px #000; background-color: #D3D3D3;"><b>Customer</b></td>
<td rowspan="2" style="font-size: 9pt; text-align: center; border-top:solid 1px #000; border-right:solid 1px #000; background-color: #D3D3D3;"><b>FG Code GDJ</b></td>
<td rowspan="2" style="font-size: 9pt; text-align: center; border-right:solid 1px #000; border-top:solid 1px #000; background-color: #D3D3D3;"><b>FG Desc.</b></td>	
<td rowspan="2" style="font-size: 9pt; text-align: center; border-right:solid 1px #000; border-top:solid 1px #000; background-color: #D3D3D3;"><b>Total Packing</b></td>	  
<td rowspan="2" style="font-size: 9pt; text-align: center; border-top:solid 1px #000; background-color: #D3D3D3;"><b>Total QTY.(Pcs.)</b></td>
<td rowspan="2" style="font-size: 9pt; text-align: center; border-top:solid 1px #000; border-right:solid 1px #000; border-left:solid 1px #000; background-color: #D3D3D3;"><b>Remark</b></td>
</tr>
<tr>
<td style="font-size: 9pt; text-align: center; border-right:solid 1px #000; border-top:dotted 1px #000; background-color: #D3D3D3;"><b>Refill Order No.</b></td>
</tr>';
$strSql_DTNSheetDetails = " 
SELECT 
				[ps_t_fg_code_gdj]
				,[bom_fg_desc]
				,ps_t_ref_replenish_code
				,bom_pj_name
				,sum([ps_t_tags_packing_std]) as tags_qty
				,count([ps_t_fg_code_gdj]) as tags_pack
			FROM tbl_dn_head
			left join tbl_dn_tail 
			on tbl_dn_head.dn_h_dtn_code = tbl_dn_tail.dn_t_dtn_code
			left join tbl_picking_head
			on tbl_dn_tail.dn_t_picking_code = tbl_picking_head.ps_h_picking_code
			left join tbl_picking_tail
			on tbl_picking_head.ps_h_picking_code = tbl_picking_tail.ps_t_picking_code
			left join tbl_bom_mst 
			on tbl_picking_tail.ps_t_fg_code_set_abt = tbl_bom_mst.bom_fg_code_set_abt
			and tbl_picking_tail.ps_t_sku_code_abt = tbl_bom_mst.bom_fg_sku_code_abt
			and tbl_picking_tail.ps_t_fg_code_gdj = tbl_bom_mst.bom_fg_code_gdj
			and tbl_picking_tail.ps_t_pj_name = tbl_bom_mst.bom_pj_name
			and tbl_picking_tail.ps_t_ship_type = tbl_bom_mst.bom_ship_type
			and tbl_picking_tail.ps_t_part_customer = tbl_bom_mst.bom_part_customer
			where
			[dn_t_dtn_code] = '$tag' 
			and bom_status = 'Active'
			group by
				[ps_t_fg_code_gdj]
				,ps_t_ref_replenish_code
				,[bom_fg_desc]
				,bom_pj_name
";

$objQuery_DTNSheetDetails = sqlsrv_query($db_con, $strSql_DTNSheetDetails, $params, $options);
$num_row_DTNSheetDetails = sqlsrv_num_rows($objQuery_DTNSheetDetails);

$row_id_DTNSheetDetails = 0;
$sum_packing_std = 0;
$sum_qty_std = 0;

$page = 1;
$perpage = 15;
while($objResult_DTNSheetDetails = sqlsrv_fetch_array($objQuery_DTNSheetDetails, SQLSRV_FETCH_ASSOC))
{
	$row_id_DTNSheetDetails++;


	$ps_t_fg_code_gdj = $objResult_DTNSheetDetails['ps_t_fg_code_gdj'];
	$bom_fg_desc = $objResult_DTNSheetDetails['bom_fg_desc'];
	$ps_t_ref_replenish_code = $objResult_DTNSheetDetails['ps_t_ref_replenish_code'];
	$bom_pj_name = $objResult_DTNSheetDetails['bom_pj_name'];
	$tags_qty = $objResult_DTNSheetDetails['tags_qty'];
	$tags_pack = $objResult_DTNSheetDetails['tags_pack'];
	

	//$bom_fg_desc = substr($bom_fg_desc, 0, 26);
	
	//sum qty
	$sum_qty_std = $sum_qty_std + $tags_qty;
	$sum_packing_std = $sum_packing_std + $tags_pack;
	
	//check bottom 10 sheet
	// || $row_id_DTNSheetDetails == $num_row_DTNSheetDetails
	if($row_id_DTNSheetDetails == 15 || $row_id_DTNSheetDetails == 30 || $row_id_DTNSheetDetails == 45 || $row_id_DTNSheetDetails == 60 || $row_id_DTNSheetDetails == 75 || $row_id_DTNSheetDetails == 90 || $row_id_DTNSheetDetails == 105 || $row_id_DTNSheetDetails == 120 || $row_id_DTNSheetDetails == 135 || $row_id_DTNSheetDetails == 150)
	{
		$str_css_bottom = " border-bottom:solid 1px #000; ";
	}
	else
	{
		$str_css_bottom = "";
	}
	
	if (($row_id_DTNSheetDetails % $perpage) == 1 & $row_id_DTNSheetDetails > 1)
	{
		$html .= '
		<tr>
  <td colspan="2" align="left"><img src="../logo_company/GDJ_png2.png" style="width: 100px; padding: 0px;" /></td>
  <td colspan="4" align="center"><font style="font-size: 15pt;"><b>Delivery Transfer Note</b></font><br><barcode code="'.$dn_h_dtn_code.'" type="C39" class="barcode" size="0.8" height="1.5"/><br>'.$dn_h_dtn_code.'</td>	  
  <td colspan="2" align="right"><barcode code="'.$dn_h_dtn_code.'" class="qrCode" type="QR" size="0.6" error="M" disableborder = "1"/></td>
</tr>
<tr>
  <td colspan="7" style="font-size: 10pt; border-top:solid 1px #000; border-left:solid 1px #000; border-right:solid 1px #000;">&nbsp;<b>Customer:</b> '.$dn_h_cus_name.'<br>&nbsp;<b>Address:</b> '.$dn_h_cus_address.'</td>	  
</tr>
<tr>
  <td colspan="7" align="left"><table width="100%" border="0" cellpadding="0" cellspacing="0" style="font-size: 10pt; border-top:solid 1px #000; border-bottom:solid 1px #000; border-left:solid 1px #000; border-right:solid 1px #000;">
	<tr>
	  <td width="50%" style="border-right:solid 1px #000;">
		&nbsp;<b>Delivery Transfer Note:</b> '.$dn_h_dtn_code.'<br>&nbsp;<b>Driver Name:</b> '.ucfirst($driver_name_en).'
	  </td>
	  <td>
		&nbsp;<b>Issue Date:</b> <font style="font-size: 9pt;">'.$dn_h_issue_datetime.'</font><br>&nbsp;<b>Truck Head ID:</b> '.$driver_truck_head_no.'
	  </td>
	</tr>
	<tr>
	  <td width="50%" style="border-right:solid 1px #000;">
		&nbsp;<b>Truck Tail ID:</b> '.$driver_truck_tail_no.'<br>&nbsp;<b>Delivery Date:</b> '.$dn_h_delivery_date.'
	  </td>
	  <td>
		&nbsp;<b>Truck Type:</b> <font style="font-size: 9pt;">'.$driver_truck_type.'</font><br>&nbsp;<b>Delivery Time:</b> '.$dn_h_delivery_time.'
	  </td>
	</tr>
</table></td>	  
</tr>
<tr>
  <td colspan="7" style="font-size: 5pt;">&nbsp;</td>	  
</tr>
		<tr>
		  <td rowspan="2" style="font-size: 9pt; text-align: center; border-top:solid 1px #000; border-left:solid 1px #000; border-right:solid 1px #000; background-color: #D3D3D3;"><b>No.</b></td>
		  <td style="font-size: 9pt; text-align: center; border-top:solid 1px #000; border-right:solid 1px #000; background-color: #D3D3D3;"><b>Customer</b></td>
		  <td rowspan="2" style="font-size: 9pt; text-align: center; border-top:solid 1px #000; border-right:solid 1px #000; background-color: #D3D3D3;"><b>FG Code GDJ</b></td>
		  <td rowspan="2" style="font-size: 9pt; text-align: center; border-right:solid 1px #000; border-top:solid 1px #000; background-color: #D3D3D3;"><b>FG Desc.</b></td>	
		  <td rowspan="2" style="font-size: 9pt; text-align: center; border-right:solid 1px #000; border-top:solid 1px #000; background-color: #D3D3D3;"><b>Total Packing</b></td>	  
		  <td rowspan="2" style="font-size: 9pt; text-align: center; border-top:solid 1px #000; background-color: #D3D3D3;"><b>Total QTY.(Pcs.)</b></td>
		  <td rowspan="3" style="font-size: 9pt; text-align: center; border-top:solid 1px #000; border-right:solid 1px #000; border-left:solid 1px #000; background-color: #D3D3D3;"><b>Remark</b></td>
		</tr>
		<tr>
		  <td style="font-size: 9pt; text-align: center; border-right:solid 1px #000; border-top:dotted 1px #000; background-color: #D3D3D3;"><b>Refill Order No.</b></td>
		</tr>';
	}
			
	$html .= '<tr>
	  <td rowspan="2" style="font-size: 8pt; text-align: center; '.$str_css_bottom.' border-left:solid 1px #000; border-right:solid 1px #000; border-top:solid 1px #000;">'.$row_id_DTNSheetDetails.'</td>
	  <td height="25px" style="font-size: 8pt; text-align: center; border-right:solid 1px #000; border-top:solid 1px #000;">'.$bom_pj_name.'</td>
	  <td rowspan="2" style="font-size: 8pt; text-align: center; '.$str_css_bottom.' border-right:solid 1px #000; border-top:solid 1px #000;">'.$ps_t_fg_code_gdj.'</td>
	  <td rowspan="2" style="font-weight:900; font-family: thsarabun; font-size: 10pt; text-align: center;  '.$str_css_bottom.' border-top:solid 1px #000;">'.$bom_fg_desc.'</td>
	  <td rowspan="2" style="font-size: 8pt; text-align: center; '.$str_css_bottom.' border-left:solid 1px #000;  border-top:solid 1px #000;">'.$tags_pack.'</td>
	  <td rowspan="2" style="font-size: 8pt; text-align: center; '.$str_css_bottom.' border-left:solid 1px #000;  border-top:solid 1px #000;">'.$tags_qty.'</td>
	  <td rowspan="3" style="font-size: 8pt; text-align: center; '.$str_css_bottom.' border-left:solid 1px #000; border-right:solid 1px #000; border-top:solid 1px #000;"></td>
	</tr>
	<tr>
	  <td height="25px" style="font-size: 8pt; text-align: center; '.$str_css_bottom.' border-right:solid 1px #000; border-top:dotted 1px #000;">'.$ps_t_ref_replenish_code.'</td>
	</tr>
	<tr>
  		<td colspan="7" style="font-size: 0.5pt;">&nbsp;</td>	  
	</tr>
	';
}
	$html .= '
	<tr>
	  <td rowspan="2" colspan="5" style="font-size: 9pt; text-align: right; border-top:solid 1px #000; border-bottom:solid 1px #000; border-left:solid 1px #000; background-color: #D3D3D3;">&nbsp;</td>
	  <td rowspan="2" style="font-size: 9pt; text-align: center; border-right:solid 1px #000; border-top:solid 1px #000; border-bottom:solid 1px #000; background-color: #D3D3D3;"><b>Total</b>&nbsp;</td>
	  <td style="font-size: 9pt; text-align: center; border-top:solid 1px #000;"><b>'.$sum_qty_std.'</b></td>
	  <td rowspan="2" style="font-size: 9pt; text-align: center; border-top:solid 1px #000; border-left:solid 1px #000; border-bottom:solid 1px #000; border-right:solid 1px #000;"></td>
	</tr>
	<tr>
	  <td style="font-size: 9pt; text-align: center; border-top:dotted 1px #000; border-bottom:solid 1px #000;"><b>('.$sum_packing_std.' Pack)</b></td>
	</tr>
	<tr>
	  <td colspan="7" style="font-size: 5pt;">&nbsp;</td>	  
	</tr>
	<tr>
	  <td colspan="7" align="center"><table width="100%" border="0" cellpadding="0" cellspacing="0" style="font-size: 10pt; border-top:solid 1px #000; border-bottom:solid 1px #000; border-left:solid 1px #000; border-right:solid 1px #000;">
		<tr>
		  <td width="50%" style="border-right:solid 1px #000;">
			<br>&nbsp;Shipper (Driver):______________________________________
		  </td>
		  <td>
			<br>&nbsp;Consignee (Customer):_______________________________
		  </td>
		</tr>
		<tr>
		  <td width="50%" style="border-right:solid 1px #000;">
			<br>&nbsp;Date/Time:_________________________________<br><br><br>
		  </td>
		  <td>
			<br>&nbsp;Date/Time:_________________________________<br><br><br>
		  </td>
		</tr>
	</table></td>	  
	</tr>
	';
	
$html .= '
</table>
</body>
</html>
';

$mpdf->WriteHTML($html); 
$mpdf->Output();
exit;
?>