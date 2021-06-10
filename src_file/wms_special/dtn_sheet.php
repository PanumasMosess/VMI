<?
require_once("../../application.php");

$buffer_date = date("Y-m-d");
$buffer_time = date("H:i:s"); //24H
$buffer_datetime = date("Y-m-d H:i:s");

/**********************************************************************************/
/*current user ********************************************************************/

/**********************************************************************************/
/*var *****************************************************************************/
$tag = isset($_REQUEST['tag']) ? $_REQUEST['tag'] : '';

$strSql_DTNHead = " 
	SELECT 
		[dn_h_dtn_code]
		,[dn_h_cus_code]
		,[dn_h_cus_name]
		,[dn_h_cus_address]
		,[dn_h_driver_code]
		,[dn_h_delivery_date]
		,[dn_h_delivery_time]
		,[dn_h_driver_sign]
		,[dn_h_cus_sign]
		,[dn_h_receive_date]
		,[dn_h_receive_time]
		,[dn_h_receive_datetime]
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
	$dn_h_driver_sign = $objResult_DTNHead['dn_h_driver_sign'];
	$dn_h_cus_sign = $objResult_DTNHead['dn_h_cus_sign'];
	$dn_h_receive_date = $objResult_DTNHead['dn_h_receive_date'];
	$dn_h_receive_time = $objResult_DTNHead['dn_h_receive_time'];
	$dn_h_receive_datetime = substr($objResult_DTNHead['dn_h_receive_datetime'],0,19);
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
////////////////////qrcode////////////////////
//////////////////////////////////////////////
//set it to writable location, a place for temp generated PNG files
$PNG_TEMP_DIR = dirname(__FILE__).DIRECTORY_SEPARATOR.'wms_special/QRCode_File_temp'.DIRECTORY_SEPARATOR;

//html PNG location prefix
$PNG_WEB_DIR = 'QRCode_File_temp/';

include "../../PHPQRcode/qrlib.php";

//ofcourse we need rights to create temp dir
if (!file_exists($PNG_TEMP_DIR))
	mkdir($PNG_TEMP_DIR);

$filename = $PNG_TEMP_DIR.'QRCode_temp.png';

//processing form input
//remember to sanitize user input in real-life solution !!!
$errorCorrectionLevel = 'L';
if (isset($_REQUEST['level']) && in_array($_REQUEST['level'], array('L','M','Q','H')))
{
	$errorCorrectionLevel = $_REQUEST['level'];    
}

$matrixPointSize = 8;
if (isset($_REQUEST['size']))
{
	$matrixPointSize = min(max((int)$_REQUEST['size'], 1), 10);
}
?>
<html>
<body>
<table align="center" width="100%" border="0" cellpadding="0" cellspacing="0">
<tr>
  <td colspan="2" align="left" ><img src="../../vmi/logo_company/GDJ_png.png" style="width: 80px; padding: 0px;" /></td>
  <td colspan="4" align="center"><font style="font-size: 15pt;"><b>Delivery Transfer Note</b></font><br><img src="../../vmi/PHPQRcode/barcode.php?barcode=<?php echo $dn_h_dtn_code.'&width=550&height=70';?>" alt="" align="bottom" /><br><?=$dn_h_dtn_code;?></td>	  
  <td colspan="2" align="right">
	<?
	//set var
	$t_qcode = $dn_h_dtn_code;
	if (isset($t_qcode))
	{ 

		//it's very important!
		if (trim($t_qcode) == '')
			die('data cannot be empty! <a href="?">back</a>');
			
		// user data
		$filename = 'QRCode_temp'.md5($t_qcode.'|'.$errorCorrectionLevel.'|'.$matrixPointSize).'.png';
		QRcode::png($t_qcode, $filename, $errorCorrectionLevel, 8, 2);
		
	} 
	else 
	{    
		//default data
		//echo 'You can provide data in GET parameter: <a href="?data=like_that">like that</a><hr/>'; 
		QRcode::png('PHP QR Code :)', $filename, $errorCorrectionLevel, 8, 2);    
	}
	?><img style="padding: 0px;" align="center" width="80px" src="../vmi/src_file/wms_special/QRCode_File_temp/<?=$filename?>">
	</td>
</tr>
<tr>
  <td colspan="8" style="font-size: 10pt; border-top:solid 1px #000; border-left:solid 1px #000; border-right:solid 1px #000;">&nbsp;<b>Customer:</b> <?=$dn_h_cus_name;?><br>&nbsp;<b>Address:</b> <?=$dn_h_cus_address;?></td>	  
</tr>
<tr>
  <td colspan="8" align="left"><table width="100%" border="0" cellpadding="0" cellspacing="0" style="font-size: 10pt; border-top:solid 1px #000; border-bottom:solid 1px #000; border-left:solid 1px #000; border-right:solid 1px #000;">
	<tr>
	  <td width="50%" style="border-right:solid 1px #000;">
		&nbsp;<b>Delivery Transfer Note:</b> <?=$dn_h_dtn_code;?><br>&nbsp;<b>Driver Name:</b> <?=ucfirst($driver_name_en);?>
	  </td>
	  <td>
		&nbsp;<b>Issue Date:</b> <font style="font-size: 9pt;"><?=$dn_h_issue_datetime;?></font><br>&nbsp;<b>Truck Head ID:</b> <?=$driver_truck_head_no;?>
	  </td>
	</tr>
	<tr>
	  <td width="50%" style="border-right:solid 1px #000;">
		&nbsp;<b>Truck Tail ID:</b> <?=$driver_truck_tail_no;?><br>&nbsp;<b>Delivery Date:</b> <?=$dn_h_delivery_date;?>
	  </td>
	  <td>
		&nbsp;<b>Truck Type:</b> <font style="font-size: 9pt;"><?=$driver_truck_type;?></font><br>&nbsp;<b>Delivery Time:</b> <?=$dn_h_delivery_time;?>
	  </td>
	</tr>
</table>
</td>	  
</tr>
<tr>
  <td colspan="8" style="font-size: 5pt;">&nbsp;</td>	  
</tr>
<tr>
  <td rowspan="2" width="5%" style="font-size: 9pt; text-align: center; border-top:solid 1px #000; border-left:solid 1px #000; border-right:solid 1px #000; background-color: #D3D3D3;"><b>No.</b></td>
  <td width="15%" style="font-size: 9pt; text-align: center; border-top:solid 1px #000; border-right:solid 1px #000; background-color: #D3D3D3;"><b>Project</b></td>
  <td width="12%" style="font-size: 9pt; text-align: center; border-top:solid 1px #000; border-right:solid 1px #000; background-color: #D3D3D3;"><b>Refill Type</b></td>
  <td rowspan="2" width="11%" style="font-size: 9pt; text-align: center; border-top:solid 1px #000; border-right:solid 1px #000; background-color: #D3D3D3;"><b>Tags ID</b></td>
  <td rowspan="2" width="13%" style="font-size: 9pt; text-align: center; border-top:solid 1px #000; border-right:solid 1px #000; background-color: #D3D3D3;"><b>FG Code GDJ</b></td>
  <td width="20%" style="font-size: 9pt; text-align: center; border-top:solid 1px #000; border-right:solid 1px #000; background-color: #D3D3D3;"><b>Part Customer</b></td>		  
  <td rowspan="2" width="10%" style="font-size: 9pt; text-align: center; border-top:solid 1px #000; background-color: #D3D3D3;"><b>QTY.(Pcs.)</b></td>
  <td rowspan="2" width="13%" style="font-size: 9pt; text-align: center; border-top:solid 1px #000; border-right:solid 1px #000; border-left:solid 1px #000; background-color: #D3D3D3;"><b>Remark</b></td>
</tr>
<tr>
  <td style="font-size: 9pt; text-align: center; border-right:solid 1px #000; border-top:dotted 1px #000; background-color: #D3D3D3;"><b>MRD Ref.</b></td>
  <td style="font-size: 9pt; text-align: center; border-right:solid 1px #000; border-top:dotted 1px #000; background-color: #D3D3D3;"><b>Refill Unit</b></td>
  <td style="font-size: 9pt; text-align: center; border-right:solid 1px #000; border-top:dotted 1px #000; background-color: #D3D3D3;"><b>FG Desc.</b></td>
</tr>
<?
$strSql_DTNSheetDetails = " 
SELECT 
	dn_h_dtn_code
	,ps_h_picking_code
	,[ps_t_picking_code]
	,[ps_t_ref_replenish_code]
	,[ps_t_pallet_code]
	,[ps_t_tags_code]
	,[ps_t_fg_code_gdj]
	,[bom_fg_desc]
	,[bom_part_customer]
	,[ps_t_location]
	,[ps_t_tags_packing_std]
	,[ps_t_cus_name]
	,[ps_t_pj_name]
	,[ps_t_replenish_unit_type]
	,[ps_t_replenish_qty_to_pack]
	,[ps_t_terminal_name]
	,[ps_t_order_type]
	,[ps_t_status]
	,[ps_t_issue_date]
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
";

$objQuery_DTNSheetDetails = sqlsrv_query($db_con, $strSql_DTNSheetDetails, $params, $options);
$num_row_DTNSheetDetails = sqlsrv_num_rows($objQuery_DTNSheetDetails);

$row_id_DTNSheetDetails = 0;
$sum_packing_std = 0;

$page = 1;
$perpage = 15;
while($objResult_DTNSheetDetails = sqlsrv_fetch_array($objQuery_DTNSheetDetails, SQLSRV_FETCH_ASSOC))
{
	$row_id_DTNSheetDetails++;
	
	$ps_t_picking_code = $objResult_DTNSheetDetails['ps_t_picking_code'];
	$ps_t_pallet_code = $objResult_DTNSheetDetails['ps_t_pallet_code'];
	$ps_t_tags_code = $objResult_DTNSheetDetails['ps_t_tags_code'];
	$ps_t_fg_code_gdj = $objResult_DTNSheetDetails['ps_t_fg_code_gdj'];
	$ps_t_replenish_unit_type = $objResult_DTNSheetDetails['ps_t_replenish_unit_type'];
	$bom_fg_desc = $objResult_DTNSheetDetails['bom_fg_desc'];
	$bom_part_customer = $objResult_DTNSheetDetails['bom_part_customer'];
	$ps_t_location = $objResult_DTNSheetDetails['ps_t_location'];
	$ps_t_pj_name = $objResult_DTNSheetDetails['ps_t_pj_name'];
	$ps_t_tags_packing_std = $objResult_DTNSheetDetails['ps_t_tags_packing_std'];
	$ps_t_replenish_qty_to_pack = $objResult_DTNSheetDetails['ps_t_replenish_qty_to_pack'];
	$ps_t_order_type = $objResult_DTNSheetDetails['ps_t_order_type'];
	$ps_t_terminal_name = $objResult_DTNSheetDetails['ps_t_terminal_name'];
	$ps_t_ref_replenish_code = $objResult_DTNSheetDetails['ps_t_ref_replenish_code'];
	
	//sum qty
	$sum_packing_std = $sum_packing_std + $ps_t_tags_packing_std;
	
	$str_css_bottom = " border-bottom:solid 1px #000; ";
  ?>
  <tr>
	  <td rowspan="2" style="font-size: 8pt; text-align: center; <?=$str_css_bottom;?> border-left:solid 1px #000; border-right:solid 1px #000; border-top:solid 1px #000;"><?=$row_id_DTNSheetDetails;?></td>
	  <td height="25px" style="font-size: 8pt; text-align: center; border-right:solid 1px #000; border-top:solid 1px #000;"><?=$ps_t_pj_name;?></td>
	  <td style="font-size: 8pt; text-align: center; border-right:solid 1px #000; border-top:solid 1px #000;"><?=$ps_t_order_type;?></td>
	  <td rowspan="2" style="font-size: 8pt; text-align: center; <?=$str_css_bottom;?> border-right:solid 1px #000; border-top:solid 1px #000;"><?=$ps_t_tags_code;?></td>
	  <td rowspan="2" style="font-size: 8pt; text-align: center; <?=$str_css_bottom;?> border-right:solid 1px #000; border-top:solid 1px #000;"><?=$ps_t_fg_code_gdj;?></td>
	  <td style="font-size: 8pt; text-align: center; border-right:solid 1px #000; border-top:solid 1px #000;"><?=$bom_part_customer;?></td>
	  <td style="font-size: 8pt; text-align: center; border-top:solid 1px #000;"><?=$ps_t_tags_packing_std;?></td>
	  <td rowspan="2" style="font-size: 8pt; text-align: center; <?=$str_css_bottom;?> border-left:solid 1px #000; border-right:solid 1px #000; border-top:solid 1px #000;"></td>
	</tr>
	<tr>
	  <td height="25px" style="font-size: 8pt; text-align: center; <?=$str_css_bottom;?> border-right:solid 1px #000; border-top:dotted 1px #000;"><?=$ps_t_ref_replenish_code;?></td>
	  <td style="font-size: 8pt; text-align: center; border-right:solid 1px #000; <?=$str_css_bottom;?> border-top:dotted 1px #000;"><?=$ps_t_replenish_unit_type;?></td>
	  <td style="font-size: 8pt; text-align: center; border-right:solid 1px #000; <?=$str_css_bottom;?> border-top:dotted 1px #000;"><?=$bom_fg_desc;?></td>
	  <td style="font-size: 8pt; text-align: center; <?=$str_css_bottom;?> border-top:dotted 1px #000;">(1 Pack)</td>
	</tr>
<?
}
?>
	<tr>
	  <td rowspan="2" colspan="5" style="font-size: 9pt; text-align: right; border-top:solid 1px #000; border-bottom:solid 1px #000; border-left:solid 1px #000; background-color: #D3D3D3;">&nbsp;</td>
	  <td rowspan="2" style="font-size: 9pt; text-align: center; border-right:solid 1px #000; border-top:solid 1px #000; border-bottom:solid 1px #000; background-color: #D3D3D3;"><b>Total</b>&nbsp;</td>
	  <td style="font-size: 9pt; text-align: center; border-top:solid 1px #000;"><b><?=$sum_packing_std;?></b></td>
	  <td rowspan="2" style="font-size: 9pt; text-align: center; border-top:solid 1px #000; border-left:solid 1px #000; border-bottom:solid 1px #000; border-right:solid 1px #000;"></td>
	</tr>
	<tr>
	  <td style="font-size: 9pt; text-align: center; border-top:dotted 1px #000; border-bottom:solid 1px #000;"><b>(<?=$row_id_DTNSheetDetails;?> Pack)</b></td>
	</tr>
	<tr>
	  <td colspan="8" style="font-size: 5pt;">&nbsp;</td>	  
	</tr>
	<tr>
	  <td colspan="8" align="center"><table width="100%" border="0" cellpadding="0" cellspacing="0" style="font-size: 10pt; border-top:solid 1px #000; border-bottom:solid 1px #000; border-left:solid 1px #000; border-right:solid 1px #000;">
		<tr>
		  <td align="center" width="50%" style="border-right:solid 1px #000;">
			<br><img src="<?=$dn_h_driver_sign;?>" style="width: 50%"/><br><b>Shipper (Driver)</b>
		  </td>
		  <td align="center">
			<br><img src="<?=$dn_h_cus_sign;?>" style="width: 50%"/><br><b>Consignee (Customer)</b>
		  </td>
		</tr>
		<tr>
		  <td align="center" width="50%" style="border-right:solid 1px #000;">
			Date/Time: <?=$dn_h_receive_datetime;?><br><br><br>
		  </td>
		  <td align="center">
			Date/Time: <?=$dn_h_receive_datetime;?><br><br><br>
		  </td>
		</tr>
		<tr>
		  <td colspan="2" align="left" style="border-top:solid 1px #000;">
			&nbsp;Remark:<br><br><br><br><br><br>
		  </td>
		</tr>
	</table></td>	  
	</tr>
</table>
</body>
</html>