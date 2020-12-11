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

$strSql_PickingHead = " 
	SELECT 
	  [ps_h_picking_code]
	  ,[ps_h_cus_name]
	  ,[ps_h_issue_by]
	  ,[ps_h_issue_datetime]
	FROM [tbl_picking_head]
	where
	[ps_h_picking_code] = '$tag'
";

$objQuery_PickingHead = sqlsrv_query($db_con, $strSql_PickingHead, $params, $options);
$num_row_PickingHead = sqlsrv_num_rows($objQuery_PickingHead);

while($objResult_PickingHead = sqlsrv_fetch_array($objQuery_PickingHead, SQLSRV_FETCH_ASSOC))
{
	$head_picking_code = $objResult_PickingHead['ps_h_picking_code'];
	$head_cus_name = $objResult_PickingHead['ps_h_cus_name'];
	$head_issue_by = $objResult_PickingHead['ps_h_issue_by'];
	$head_issue_datetime = substr($objResult_PickingHead['ps_h_issue_datetime'],0,19);
}

//////////////////////////////////////////////
////////////////////qrcode////////////////////
//////////////////////////////////////////////
//set it to writable location, a place for temp generated PNG files
$PNG_TEMP_DIR = dirname(__FILE__).DIRECTORY_SEPARATOR.'QRCode_File_temp'.DIRECTORY_SEPARATOR;

//html PNG location prefix
$PNG_WEB_DIR = 'QRCode_File_temp/';

include "../PHPQRcode/qrlib.php";

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
	'margin_footer' => 10
]);

$mpdf->SetProtection(array('print'));
$mpdf->SetTitle("Print Picking Sheet");
$mpdf->SetAuthor("Albatross Logistics Co.,Ltd.");
$mpdf->SetWatermarkText("VMI Picking Sheet");
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
</style>
</head>
<body>
    <htmlpageheader name="MyHeader">
        <table width="100%" style="vertical-align: bottom; font-family: serif; font-size: 8pt; color: #000000; font-weight: bold; font-style: italic;">
            <tr>
                <td width="33%"><span style="font-weight: bold; font-style: italic;">VMI Picking Sheet</span></td>
                <td width="33%" align="center" style="font-weight: bold; font-style: italic;">{PAGENO}/{nbpg}</td>
                <td width="33%" style="text-align: right; ">Printed: {DATE j-m-Y}</td>
            </tr>
        </table>
    </htmlpageheader>
';

$html .= '
<table width="100%" border="0" cellpadding="0" cellspacing="0">
<tr>
  <td colspan="2" align="left"><img src="../logo_company/GDJ_png.png" style="width: 100px; padding: 0px;" /></td>
  <td colspan="4" align="center"><font style="font-size: 15pt;"><b>PICKING SHEET</b></font><br><barcode code="'.$head_picking_code.'" type="C39" class="barcode" size="0.8" height="1.5"/><br>'.$head_picking_code.'</td>	  
  <td colspan="2" align="right">';
	//set var
	$t_qcode = $head_picking_code;
	if (isset($t_qcode))
	{ 

		//it's very important!
		if (trim($t_qcode) == '')
			die('data cannot be empty! <a href="?">back</a>');
			
		// user data
		$filename = $PNG_TEMP_DIR.'QRCode_temp'.md5($t_qcode.'|'.$errorCorrectionLevel.'|'.$matrixPointSize).'.png';
		QRcode::png($t_qcode, $filename, $errorCorrectionLevel, 8, 2);
		
	} 
	else 
	{    
		//default data
		//echo 'You can provide data in GET parameter: <a href="?data=like_that">like that</a><hr/>'; 
		QRcode::png('PHP QR Code :)', $filename, $errorCorrectionLevel, 8, 2);    
	}
	$html .= '<img style="padding: 0px;" align="center" width="80px" src="'.$PNG_WEB_DIR.basename($filename).'"></td>
</tr>
<tr>
  <td colspan="8" align="left"><table width="100%" border="0" cellpadding="0" cellspacing="0" style="font-size: 10pt; border-top:solid 1px #000; border-bottom:solid 1px #000; border-left:solid 1px #000; border-right:solid 1px #000;">
	<tr>
	  <td width="50%" style="border-right:solid 1px #000;">
		&nbsp;<b>Picking Sheet:</b> '.$head_picking_code.'<br>&nbsp;<b>Issue Date:</b> '.$head_issue_datetime.'
	  </td>
	  <td>
		&nbsp;<b>Customer Name:</b> <font style="font-size: 9pt;">'.$head_cus_name.'</font><br>&nbsp;<b>Issue By:</b> '.ucfirst($head_issue_by).'
	  </td>
	</tr>
</table></td>	  
</tr>
<tr>
  <td colspan="8" style="font-size: 5pt;">&nbsp;</td>	  
</tr>
<tr>
  <td rowspan="2" width="5%" style="font-size: 9pt; text-align: center; border-top:solid 1px #000; border-left:solid 1px #000; border-right:solid 1px #000; background-color: #D3D3D3;"><b>No.</b></td>
  <td width="15%" style="font-size: 9pt; text-align: center; border-top:solid 1px #000; border-right:solid 1px #000; background-color: #D3D3D3;"><b>Project</b></td>
  <td width="12%" style="font-size: 9pt; text-align: center; border-top:solid 1px #000; border-right:solid 1px #000; background-color: #D3D3D3;"><b>Terminal</b></td>
  <td rowspan="2" width="13%" style="font-size: 9pt; text-align: center; border-top:solid 1px #000; border-right:solid 1px #000; background-color: #D3D3D3;"><b>Pick Location</b></td>
  <td width="12%" style="font-size: 9pt; text-align: center; border-top:solid 1px #000; border-right:solid 1px #000; background-color: #D3D3D3;"><b>Pallet ID</b></td>
  <td width="20%" style="font-size: 9pt; text-align: center; border-top:solid 1px #000; border-right:solid 1px #000; background-color: #D3D3D3;"><b>FG Code GDJ</b></td>		  
  <td rowspan="2" width="10%" style="font-size: 9pt; text-align: center; border-top:solid 1px #000; background-color: #D3D3D3;"><b>QTY.(Pcs.)</b></td>
  <td rowspan="2" width="13%" style="font-size: 9pt; text-align: center; border-top:solid 1px #000; border-right:solid 1px #000; border-left:solid 1px #000; background-color: #D3D3D3;"><b>Remark</b></td>
</tr>
<tr>
  <td style="font-size: 9pt; text-align: center; border-right:solid 1px #000; border-top:dotted 1px #000; background-color: #D3D3D3;"><b>Ref No.</b></td>
  <td style="font-size: 9pt; text-align: center; border-right:solid 1px #000; border-top:dotted 1px #000; background-color: #D3D3D3;"><b>Order Type</b></td>
  <td style="font-size: 9pt; text-align: center; border-right:solid 1px #000; border-top:dotted 1px #000; background-color: #D3D3D3;"><b>Tags ID</b></td>
  <td style="font-size: 9pt; text-align: center; border-right:solid 1px #000; border-top:dotted 1px #000; background-color: #D3D3D3;"><b>FG Desc.</b></td>
</tr>';
$strSql_PickingSheetDetails = " 
	SELECT 
	  [ps_t_picking_code]
	  ,[ps_t_ref_replenish_code]
	  ,[ps_t_pallet_code]
	  ,[ps_t_tags_code]
	  ,[ps_t_fg_code_set_abt]
      ,[ps_t_sku_code_abt]
	  ,[ps_t_fg_code_gdj]
	  ,[bom_fg_desc]
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
  FROM [tbl_picking_tail]
  left join tbl_bom_mst
  on tbl_picking_tail.ps_t_fg_code_set_abt = tbl_bom_mst.bom_fg_code_set_abt
  and tbl_picking_tail.ps_t_sku_code_abt = tbl_bom_mst.bom_fg_sku_code_abt
  and tbl_picking_tail.ps_t_fg_code_gdj = tbl_bom_mst.bom_fg_code_gdj
  and tbl_picking_tail.ps_t_pj_name = tbl_bom_mst.bom_pj_name
  and tbl_picking_tail.ps_t_ship_type = tbl_bom_mst.bom_ship_type
  and tbl_picking_tail.ps_t_part_customer = tbl_bom_mst.bom_part_customer
  where
  [ps_t_picking_code] = '$tag'
";

$objQuery_PickingSheetDetails = sqlsrv_query($db_con, $strSql_PickingSheetDetails, $params, $options);
$num_row_PickingSheetDetails = sqlsrv_num_rows($objQuery_PickingSheetDetails);

$row_id_PickingSheetDetails = 0;
$sum_packing_std = 0;

$page = 1;
$perpage = 16;
while($objResult_PickingSheetDetails = sqlsrv_fetch_array($objQuery_PickingSheetDetails, SQLSRV_FETCH_ASSOC))
{
	$row_id_PickingSheetDetails++;
	
	$ps_t_picking_code = $objResult_PickingSheetDetails['ps_t_picking_code'];
	$ps_t_pallet_code = $objResult_PickingSheetDetails['ps_t_pallet_code'];
	$ps_t_tags_code = $objResult_PickingSheetDetails['ps_t_tags_code'];
	$ps_t_fg_code_set_abt = $objResult_PickingSheetDetails['ps_t_fg_code_set_abt'];
	$ps_t_sku_code_abt = $objResult_PickingSheetDetails['ps_t_sku_code_abt'];
	$ps_t_fg_code_gdj = $objResult_PickingSheetDetails['ps_t_fg_code_gdj'];
	$bom_fg_desc = $objResult_PickingSheetDetails['bom_fg_desc'];
	$ps_t_location = $objResult_PickingSheetDetails['ps_t_location'];
	$ps_t_pj_name = $objResult_PickingSheetDetails['ps_t_pj_name'];
	$ps_t_tags_packing_std = $objResult_PickingSheetDetails['ps_t_tags_packing_std'];
	$ps_t_replenish_qty_to_pack = $objResult_PickingSheetDetails['ps_t_replenish_qty_to_pack'];
	$ps_t_order_type = $objResult_PickingSheetDetails['ps_t_order_type'];
	$ps_t_terminal_name = $objResult_PickingSheetDetails['ps_t_terminal_name'];
	$ps_t_ref_replenish_code = $objResult_PickingSheetDetails['ps_t_ref_replenish_code'];
	
	//sum qty
	$sum_packing_std = $sum_packing_std + $ps_t_tags_packing_std;
	
	//check bottom 10 sheet
	// || $row_id_PickingSheetDetails == $num_row_PickingSheetDetails
	if($row_id_PickingSheetDetails == 16 || $row_id_PickingSheetDetails == 32 || $row_id_PickingSheetDetails == 48 || $row_id_PickingSheetDetails == 64 || $row_id_PickingSheetDetails == 80 || $row_id_PickingSheetDetails == 96 || $row_id_PickingSheetDetails == 112 || $row_id_PickingSheetDetails == 128 || $row_id_PickingSheetDetails == 144 || $row_id_PickingSheetDetails == 160)
	{
		$str_css_bottom = " border-bottom:solid 1px #000; ";
	}
	else
	{
		$str_css_bottom = "";
	}
	
	if (($row_id_PickingSheetDetails % $perpage) == 1 & $row_id_PickingSheetDetails > 1)
	{
		$html .= '
		<tr>
  <td colspan="2" align="left"><img src="../logo_company/GDJ_png.png" style="width: 100px; padding: 0px;" /></td>
  <td colspan="4" align="center"><font style="font-size: 15pt;"><b>PICKING SHEET</b></font><br><barcode code="'.$head_picking_code.'" type="C39" class="barcode" size="0.8" height="1.5"/><br>'.$head_picking_code.'</td>	  
  <td colspan="2" align="right">';
	//set var
	$t_qcode = $head_picking_code;
	if (isset($t_qcode))
	{ 

		//it's very important!
		if (trim($t_qcode) == '')
			die('data cannot be empty! <a href="?">back</a>');
			
		// user data
		$filename = $PNG_TEMP_DIR.'QRCode_temp'.md5($t_qcode.'|'.$errorCorrectionLevel.'|'.$matrixPointSize).'.png';
		QRcode::png($t_qcode, $filename, $errorCorrectionLevel, 8, 2);
		
	} 
	else 
	{    
		//default data
		//echo 'You can provide data in GET parameter: <a href="?data=like_that">like that</a><hr/>'; 
		QRcode::png('PHP QR Code :)', $filename, $errorCorrectionLevel, 8, 2);    
	}
	$html .= '<img style="padding: 0px;" align="center" width="80px" src="'.$PNG_WEB_DIR.basename($filename).'"></td>
</tr>
<tr>
  <td colspan="4" align="left" style="font-size: 10pt; border-top:solid 1px #000; border-bottom:solid 1px #000; border-left:solid 1px #000; border-right:solid 1px #000;">&nbsp;<b>Picking Sheet:</b> '.$head_picking_code.'<br>&nbsp;<b>Issue Date:</b> '.$head_issue_datetime.'</td>
  <td colspan="4" align="left" style="font-size: 10pt; border-top:solid 1px #000; border-bottom:solid 1px #000; border-left:solid 1px #000; border-right:solid 1px #000;">&nbsp;<b>Customer Name:</b> <font style="font-size: 9pt;">'.$head_cus_name.'</font><br>&nbsp;<b>Issue By:</b> '.ucfirst($head_issue_by).'</td>	  
</tr>
<tr>
  <td colspan="8" style="font-size: 5pt;">&nbsp;</td>	  
</tr>
		<tr>
		  <td rowspan="2" style="font-size: 9pt; text-align: center; border-top:solid 1px #000; border-left:solid 1px #000; border-right:solid 1px #000; background-color: #D3D3D3;"><b>No.</b></td>
		  <td style="font-size: 9pt; text-align: center; border-top:solid 1px #000; border-right:solid 1px #000; background-color: #D3D3D3;"><b>Project</b></td>
		  <td style="font-size: 9pt; text-align: center; border-top:solid 1px #000; border-right:solid 1px #000; background-color: #D3D3D3;"><b>Terminal</b></td>
		  <td rowspan="2" style="font-size: 9pt; text-align: center; border-top:solid 1px #000; border-right:solid 1px #000; background-color: #D3D3D3;"><b>Pick Location</b></td>
		  <td style="font-size: 9pt; text-align: center; border-top:solid 1px #000; border-right:solid 1px #000; background-color: #D3D3D3;"><b>Pallet ID</b></td>
		  <td style="font-size: 9pt; text-align: center; border-top:solid 1px #000; border-right:solid 1px #000; background-color: #D3D3D3;"><b>FG Code GDJ</b></td>		  
		  <td rowspan="2" style="font-size: 9pt; text-align: center; border-top:solid 1px #000; background-color: #D3D3D3;"><b>QTY.(Pcs.)</b></td>
		  <td rowspan="2" style="font-size: 9pt; text-align: center; border-top:solid 1px #000; border-right:solid 1px #000; border-left:solid 1px #000; background-color: #D3D3D3;"><b>Remark</b></td>
		</tr>
		<tr>
		  <td style="font-size: 9pt; text-align: center; border-right:solid 1px #000; border-top:dotted 1px #000; background-color: #D3D3D3;"><b>Ref No.</b></td>
		  <td style="font-size: 9pt; text-align: center; border-right:solid 1px #000; border-top:dotted 1px #000; background-color: #D3D3D3;"><b>Order Type</b></td>
		  <td style="font-size: 9pt; text-align: center; border-right:solid 1px #000; border-top:dotted 1px #000; background-color: #D3D3D3;"><b>Tags ID</b></td>
		  <td style="font-size: 9pt; text-align: center; border-right:solid 1px #000; border-top:dotted 1px #000; background-color: #D3D3D3;"><b>FG Desc.</b></td>
		</tr>';
	}
			
	$html .= '<tr>
	  <td rowspan="2" style="font-size: 8pt; text-align: center; '.$str_css_bottom.' border-left:solid 1px #000; border-right:solid 1px #000; border-top:solid 1px #000;">'.$row_id_PickingSheetDetails.'</td>
	  <td height="25px" style="font-size: 8pt; text-align: center; border-right:solid 1px #000; border-top:solid 1px #000;">'.$ps_t_pj_name.'</td>
	  <td style="font-size: 8pt; text-align: center; border-right:solid 1px #000; border-top:solid 1px #000;">'.$ps_t_terminal_name.'</td>
	  <td rowspan="2" style="font-size: 8pt; text-align: center; '.$str_css_bottom.' border-right:solid 1px #000; border-top:solid 1px #000;">'.$ps_t_location.'</td>
	  <td style="font-size: 8pt; text-align: center; border-right:solid 1px #000; border-top:solid 1px #000;">'.$ps_t_pallet_code.'</td>
	  <td style="font-size: 8pt; text-align: center; border-right:solid 1px #000; border-top:solid 1px #000;">'.$ps_t_fg_code_gdj.'</td>
	  <td style="font-size: 8pt; text-align: center; border-top:solid 1px #000;">'.$ps_t_tags_packing_std.'</td>
	  <td rowspan="2" style="font-size: 8pt; text-align: center; '.$str_css_bottom.' border-left:solid 1px #000; border-right:solid 1px #000; border-top:solid 1px #000;"></td>
	</tr>
	<tr>
	  <td height="25px" style="font-size: 8pt; text-align: center; '.$str_css_bottom.' border-right:solid 1px #000; border-top:dotted 1px #000;">'.$ps_t_ref_replenish_code.'</td>
	  <td style="font-size: 8pt; text-align: center; border-right:solid 1px #000; '.$str_css_bottom.' border-top:dotted 1px #000;">'.$ps_t_order_type.'</td>
	  <td style="font-size: 8pt; text-align: center; border-right:solid 1px #000; '.$str_css_bottom.' border-top:dotted 1px #000;">'.$ps_t_tags_code.'</td>
	  <td style="font-size: 8pt; text-align: center; border-right:solid 1px #000; '.$str_css_bottom.' border-top:dotted 1px #000;">'.$bom_fg_desc.'</td>
	  <td style="font-size: 8pt; text-align: center; '.$str_css_bottom.' border-top:dotted 1px #000;">(1 Pack)</td>
	</tr>
	';
}
	$html .= '
	<tr>
	  <td rowspan="2" colspan="5" style="font-size: 9pt; text-align: right; border-top:solid 1px #000; border-bottom:solid 1px #000; border-left:solid 1px #000; background-color: #D3D3D3;">&nbsp;</td>
	  <td rowspan="2" style="font-size: 9pt; text-align: center; border-right:solid 1px #000; border-top:solid 1px #000; border-bottom:solid 1px #000; background-color: #D3D3D3;"><b>Total</b>&nbsp;</td>
	  <td style="font-size: 9pt; text-align: center; border-top:solid 1px #000;"><b>'.$sum_packing_std.'</b></td>
	  <td rowspan="2" style="font-size: 9pt; text-align: center; border-top:solid 1px #000; border-left:solid 1px #000; border-bottom:solid 1px #000; border-right:solid 1px #000;"></td>
	</tr>
	<tr>
	  <td style="font-size: 9pt; text-align: center; border-top:dotted 1px #000; border-bottom:solid 1px #000;"><b>('.$row_id_PickingSheetDetails.' Pack)</b></td>
	</tr>
	<tr>
	  <td colspan="8" style="font-size: 5pt;">&nbsp;</td>	  
	</tr>
	<tr>
	  <td colspan="8" align="center"><table width="100%" border="0" cellpadding="0" cellspacing="0" style="font-size: 10pt; border-top:solid 1px #000; border-bottom:solid 1px #000; border-left:solid 1px #000; border-right:solid 1px #000;">
		<tr>
		  <td width="50%" style="border-right:solid 1px #000;">
			<br>&nbsp;Pick By:________________________________________
		  </td>
		  <td>
			<br>&nbsp;Check By:________________________________________
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
	</tr>';
	
$html .= '
</table>
</body>
</html>
';
$mpdf->WriteHTML($html); 
$mpdf->Output();
exit;
?>