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
$tag = isset($_REQUEST['token']) ? $_REQUEST['token'] : '';

// //decode
$tag = var_decode($tag);

$strSql_tagLot = " 
SELECT  
       [tags_code]
      ,[tags_fg_code_gdj]
      ,[tags_fg_code_gdj_desc]
	  ,[tags_project_name]
      ,[tags_prod_plan]
      ,[tags_packing_std]
      ,[tags_total_qty]
      ,[tags_token]
	  ,[tags_trading_from]
      ,[tags_issue_by]
      ,[tags_issue_date]
      ,[tags_issue_time]
      ,[tags_issue_datetime]
  FROM [tbl_tags_running] where tags_token = '$tag'
";

$objQuery_TagsLot = sqlsrv_query($db_con, $strSql_tagLot, $params, $options);
$num_row_TagsLot = sqlsrv_num_rows($objQuery_TagsLot);

while($objResult_TagsLot = sqlsrv_fetch_array($objQuery_TagsLot, SQLSRV_FETCH_ASSOC))
{
	$tags_code = $objResult_TagsLot['tags_code'];
	$tags_fg_code_gdj = $objResult_TagsLot['tags_fg_code_gdj'];
	$tags_fg_code_gdj_desc = $objResult_TagsLot['tags_fg_code_gdj_desc'];
	$tags_project_name = $objResult_TagsLot['tags_project_name'];
	$tags_prod_plan = $objResult_TagsLot['tags_prod_plan'];
	$tags_packing_std = $objResult_TagsLot['tags_packing_std'];
	$tags_total_qty = $objResult_TagsLot['tags_total_qty'];
	$tags_token = $objResult_TagsLot['tags_token'];
	$tags_trading_from = $objResult_TagsLot['tags_trading_from'];
	$tags_issue_by = $objResult_TagsLot['tags_issue_by'];
	$tags_issue_date = $objResult_TagsLot['tags_issue_date'];
	$tags_issue_time = $objResult_TagsLot['tags_issue_time'];
	$tags_issue_datetime = substr($objResult_TagsLot['tags_issue_datetime'],0,19);
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
$mpdf->SetTitle("Print TAGS COVER SHEET");
$mpdf->SetAuthor("Albatross Logistics Co.,Ltd.");
$mpdf->SetWatermarkText("TAGS COVER SHEET");
$mpdf->showWatermarkText = true;
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
.qrCode {
	padding: 1.5mm;
	margin: 0;
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
                <td width="33%"><span style="font-weight: bold; font-style: italic;">VMI TAGS COVER SHEET</span></td>
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
  <td colspan="4" align="center"><font style="font-size: 12pt;"><b>TAGS COVER SHEET</b></font><br><barcode code="'.$tags_token.'" type="C39" class="barcode" size="0.5" height="1.5"/><br>'.$tags_token.'</td>	  
  <td colspan="2" align="right">
	<barcode code="'.$tags_token.'" class="qrCode" type="QR" size="0.6" error="M" disableborder = "1"/></td>
</tr>
<tr>
  <td colspan="8" align="left"><table width="100%" border="0" cellpadding="0" cellspacing="0" style="font-size: 10pt; border-top:solid 1px #000; border-bottom:solid 1px #000; border-left:solid 1px #000; border-right:solid 1px #000;">
	<tr>
	  <td width="50%" style="border-right:solid 1px #000;">
		&nbsp;<b>Tags Lot Sheet:</b> '.$tags_token.'<br>&nbsp;<b>Issue Date:</b> '.$tags_issue_date.'
	  </td>
	  <td>
		&nbsp;<b>Issue By:</b> '.ucfirst($tags_issue_by).'
	  </td>
	</tr>
</table></td>	  
</tr>
<tr>
  <td colspan="8" style="font-size: 5pt;">&nbsp;</td>	  
</tr>
<tr>
  <td rowspan="2" width="5%" style="font-size: 9pt; text-align: center; border-top:solid 1px #000; border-left:solid 1px #000; border-right:solid 1px #000; background-color: #D3D3D3;"><b>No.</b></td>
  <td rowspan="2" width="15%" style="font-size: 9pt; text-align: center; border-top:solid 1px #000; border-right:solid 1px #000; background-color: #D3D3D3;"><b>Project</b></td>
  <td rowspan="2" width="12%" style="font-size: 9pt; text-align: center; border-top:solid 1px #000; border-right:solid 1px #000; background-color: #D3D3D3;"><b>Tags ID</b></td>
  <td width="20%" style="font-size: 9pt; text-align: center; border-top:solid 1px #000; border-right:solid 1px #000; background-color: #D3D3D3;"><b>FG Code GDJ</b></td>		  
  <td rowspan="2" width="10%" style="font-size: 9pt; text-align: center; border-top:solid 1px #000; background-color: #D3D3D3;"><b>QTY.(Pcs.)</b></td>
  <td colspan="3" rowspan="2" width="13%" style="font-size: 9pt; text-align: center; border-top:solid 1px #000; border-right:solid 1px #000; border-left:solid 1px #000; background-color: #D3D3D3;"><b>Remark</b></td>
</tr>
<tr>
  <td style="font-size: 9pt; text-align: center; border-right:solid 1px #000; border-top:dotted 1px #000; background-color: #D3D3D3;"><b>FG Desc.</b></td>
</tr>';
$strSql_Tags = " 
SELECT  
[tags_code]
,[tags_fg_code_gdj]
,[tags_fg_code_gdj_desc]
,tags_project_name
,[tags_prod_plan]
,[tags_packing_std]
,[tags_total_qty]
,[tags_token]
,[tags_trading_from]
,[tags_issue_by]
,[tags_issue_date]
,[tags_issue_time]
,[tags_issue_datetime]
FROM [tbl_tags_running] where tags_token = '$tag'
";

$objQuery_Tags = sqlsrv_query($db_con, $strSql_Tags, $params, $options);
$num_row_Tags= sqlsrv_num_rows($objQuery_Tags);

$row_id_Tags = 0;
$sum_packing_std = 0;

$page = 1;
$perpage = 29;
while($objResult_TagsLot = sqlsrv_fetch_array($objQuery_Tags, SQLSRV_FETCH_ASSOC))
{
	$row_id_Tags++;
	
	$tags_code = $objResult_TagsLot['tags_code'];
	$tags_fg_code_gdj = $objResult_TagsLot['tags_fg_code_gdj'];
	$tags_fg_code_gdj_desc = $objResult_TagsLot['tags_fg_code_gdj_desc'];
	$tags_project_name = $objResult_TagsLot['tags_project_name'];
	$tags_prod_plan = $objResult_TagsLot['tags_prod_plan'];
	$tags_packing_std = $objResult_TagsLot['tags_packing_std'];
	$tags_token = $objResult_TagsLot['tags_token'];
	$tags_trading_from = $objResult_TagsLot['tags_trading_from'];
	$tags_total_qty = $objResult_TagsLot['tags_total_qty'];
	$tags_issue_by = $objResult_TagsLot['tags_issue_by'];
	$tags_issue_date = $objResult_TagsLot['tags_issue_date'];
	$tags_issue_datetime = $objResult_TagsLot['tags_issue_datetime'];
	
	//sum qty
	$sum_packing_std = $sum_packing_std + $tags_packing_std;
	
	//check bottom 10 sheet
	// || $row_id_Tags == $num_row_PickingSheetDetails
	if($row_id_Tags == 29 || $row_id_Tags == (29*2) || $row_id_Tags == (29*3) || $row_id_Tags == (29*4) || $row_id_Tags == (29*5) || $row_id_Tags == (29*6) || $row_id_Tags == (29*7) || $row_id_Tags == (29*8) || $row_id_Tags == (29*9) || $row_id_Tags == (29*10))
	{
		$str_css_bottom = " border-bottom:solid 1px #000; ";
	}
	else
	{
		$str_css_bottom = "";
	}
	
	if (($row_id_Tags % $perpage) == 1 & $row_id_Tags > 1)
	{
		$html .= '
		<tr>
  <td colspan="2" align="left"><img src="../logo_company/GDJ_png2.png" style="width: 100px; padding: 0px;" /></td>
  <td colspan="4" align="center"><font style="font-size: 12pt;"><b>TAGS COVER SHEET</b></font><br><barcode code="'.$tags_token.'" type="C39" class="barcode" size="0.5" height="1.5"/><br>'.$tags_token.'</td>	  
  <td colspan="2" align="right">
	<barcode code="'.$tags_token.'" class="qrCode" type="QR" size="0.6" error="M" disableborder = "1"/></td>
</tr>
<tr>
  <td colspan="4" align="left" style="font-size: 10pt; border-top:solid 1px #000; border-bottom:solid 1px #000; border-left:solid 1px #000; border-right:solid 1px #000;">&nbsp;<b>Tags Lot Sheet:</b> '.$tags_token.'<br>&nbsp;<b>Issue Date:</b> '.$tags_issue_datetime.'</td>
  <td colspan="4" align="left" style="font-size: 10pt; border-top:solid 1px #000; border-bottom:solid 1px #000; border-left:solid 1px #000; border-right:solid 1px #000;">&nbsp;<b>Issue By:</b> '.ucfirst($tags_issue_by).'</td>	  
</tr>
<tr>
  <td colspan="8" style="font-size: 5pt;">&nbsp;</td>	  
</tr>
<tr>
	<td rowspan="2" width="5%" style="font-size: 9pt; text-align: center; border-top:solid 1px #000; border-left:solid 1px #000; border-right:solid 1px #000; background-color: #D3D3D3;"><b>No.</b></td>
	<td rowspan="2" width="15%" style="font-size: 9pt; text-align: center; border-top:solid 1px #000; border-right:solid 1px #000; background-color: #D3D3D3;"><b>Project</b></td>
	<td rowspan="2" width="12%" style="font-size: 9pt; text-align: center; border-top:solid 1px #000; border-right:solid 1px #000; background-color: #D3D3D3;"><b>Tags ID</b></td>
	<td width="20%" style="font-size: 9pt; text-align: center; border-top:solid 1px #000; border-right:solid 1px #000; background-color: #D3D3D3;"><b>FG Code GDJ</b></td>
	<td rowspan="2" width="10%" style="font-size: 9pt; text-align: center; border-top:solid 1px #000; background-color: #D3D3D3;"><b>QTY.(Pcs.)</b></td>
	<td colspan="3" rowspan="2" width="13%" style="font-size: 9pt; text-align: center; border-top:solid 1px #000; border-right:solid 1px #000; border-left:solid 1px #000; background-color: #D3D3D3;"><b>Remark</b></td>
</tr>
	<tr>
	<td style="font-size: 9pt; text-align: center; border-right:solid 1px #000; border-top:dotted 1px #000; background-color: #D3D3D3;"><b>FG Desc.</b></td>
	</tr>';
	}
			
	$html .= '<tr>
	  <td rowspan="2" style="font-size: 8pt; text-align: center; '.$str_css_bottom.' border-left:solid 1px #000; border-right:solid 1px #000; border-top:solid 1px #000;">'.$row_id_Tags.'</td>
	  <td rowspan="2" height="25px" style="font-size: 8pt; text-align: center; border-right:solid 1px #000; border-top:solid 1px #000;">'.$tags_project_name.'</td>
	  <td rowspan="2" style="font-size: 8pt; text-align: center; '.$str_css_bottom.' border-right:solid 1px #000; border-top:solid 1px #000;">'.$tags_code.'</td>
	  <td style="font-size: 8pt; text-align: center; border-right:solid 1px #000; border-top:solid 1px #000;">'.$tags_fg_code_gdj.'</td>
	  <td style="font-size: 8pt; text-align: center; border-top:solid 1px #000;">'.$tags_packing_std.'</td>
	  <td colspan="3" rowspan="2" style="font-size: 8pt; text-align: left; '.$str_css_bottom.' border-left:solid 1px #000; border-right:solid 1px #000; border-top:solid 1px #000;">&nbsp;&nbsp;Trading From:&nbsp;'.$tags_trading_from.'</td>
	</tr>
	<tr>
	  <td style="font-weight:900; font-family: thsarabun; font-size: 9pt; text-align: center; border-right:solid 1px #000; '.$str_css_bottom.' border-top:dotted 1px #000;">'.$tags_fg_code_gdj_desc.'</td>
	  <td style="font-size: 8pt; text-align: center; '.$str_css_bottom.' border-top:dotted 1px #000;">(1 Pack)</td>
	</tr>
	';
}
	$html .= '
	<tr>
	  <td rowspan="2" colspan="5" style="font-size: 9pt; text-align: right; border-top:solid 1px #000; border-bottom:solid 1px #000; border-left:solid 1px #000; background-color: #D3D3D3;">&nbsp;</td>
	  <td rowspan="2" style="font-size: 9pt; text-align: center; border-right:solid 1px #000; border-top:solid 1px #000; border-bottom:solid 1px #000; background-color: #D3D3D3;"><b>Total</b>&nbsp;</td>
	  <td colspan="2" style="font-size: 9pt; text-align: center; border-top:solid 1px #000; border-right:solid 1px #000;"><b>'.$sum_packing_std.'</b></td>
	 
	</tr>
	<tr>
	  <td colspan="2" style="font-size: 9pt; text-align: center; border-top:dotted 1px #000; border-right:solid 1px #000; border-bottom:solid 1px #000;"><b>('.$row_id_Tags.' Pack)</b></td>
	</tr>
	<tr>
	  <td colspan="8" style="font-size: 5pt;">&nbsp;</td>	  
	</tr>
	<tr>
	  <td colspan="8" align="center"><table width="100%" border="0" cellpadding="0" cellspacing="0" style="font-size: 10pt; border-top:solid 1px #000; border-bottom:solid 1px #000; border-left:solid 1px #000; border-right:solid 1px #000;">
		<tr>
		  <td width="50%" style="border-right:solid 1px #000;">
			<br>&nbsp;Print By:________________________________________
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