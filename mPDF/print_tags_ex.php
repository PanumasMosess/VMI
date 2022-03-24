<?
require_once("../application.php");

$buffer_date = date("Y-m-d");
$buffer_time = date("H:i:s"); //24H
$buffer_datetime = date("Y-m-d H:i:s");

/**********************************************************************************/
/*current user ********************************************************************/
$t_cur_user_code_VMI_GDJ = isset($_SESSION['t_cur_user_code_VMI_GDJ']) ? $_SESSION['t_cur_user_code_VMI_GDJ'] : '';
$t_cur_user_type_VMI_GDJ = isset($_SESSION['t_cur_user_type_VMI_GDJ']) ? $_SESSION['t_cur_user_type_VMI_GDJ'] : '';

/**********************************************************************************/
/*var *****************************************************************************/
$token = isset($_REQUEST['token']) ? $_REQUEST['token'] : '';
$tag = isset($_REQUEST['tag']) ? $_REQUEST['tag'] : '';

//decode
$token = var_decode($token);
$tag = var_decode($tag);

/*
//get tags
$strSql = " SELECT * FROM tbl_tags_running where tags_token = '$token' order by tags_id asc ";
$objQuery = sqlsrv_query($db_con, $strSql);
//$num_row = sqlsrv_has_rows($objQuery);
while($objResult = sqlsrv_fetch_array($objQuery, SQLSRV_FETCH_ASSOC))
{
	$bom_fg_desc = $objResult['bom_fg_desc'];
	$bom_fg_desc = $objResult['bom_fg_desc'];
	$bom_fg_desc = $objResult['bom_fg_desc'];
	$bom_fg_desc = $objResult['bom_fg_desc'];
	$bom_fg_desc = $objResult['bom_fg_desc'];
}


SELECT * FROM tbl_tags_running
  left join 
  tbl_bom_mst
  on 
  tbl_tags_running.tags_fg_code_gdj = tbl_bom_mst.bom_fg_code_gdj
  and
  tbl_tags_running.tags_fg_code_gdj_desc = tbl_bom_mst.bom_fg_desc



  where tags_token = '128m9xtid8pe3pbszvy559f7uk9bht'
*/

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
	'margin_left' => 15,
	'margin_right' => 15,
	'margin_top' => 15,
	'margin_bottom' => 15,
	'margin_header' => 10,
	'margin_footer' => 10
]);

$mpdf->SetProtection(array('print'));
$mpdf->SetTitle("Print Master Tags");
$mpdf->SetAuthor("Albatross Logistics Co.,Ltd.");
$mpdf->SetWatermarkText("VMI Master Tags");
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
</style>
</head>
<body>
    <htmlpageheader name="MyHeader">
        <table width="100%" style="vertical-align: bottom; font-family: serif; font-size: 8pt; color: #000000; font-weight: bold; font-style: italic;">
            <tr>
                <td width="33%"><span style="font-weight: bold; font-style: italic;">VMI Master Tags</span></td>
                <td width="33%" align="center" style="font-weight: bold; font-style: italic;">{PAGENO}/{nbpg}</td>
                <td width="33%" style="text-align: right; ">{DATE j-m-Y}</td>
            </tr>
        </table>
    </htmlpageheader>
	
	<table width="100%" cellpadding="0" cellspacing="0">
		<tr>
		  <td width="49%">
			<table width="100%" cellpadding="0" cellspacing="0">
			  <tr>
				<td colspan="3" align="center" style="border-top:solid 1px #000; border-bottom:solid 1px #000; border-left:solid 1px #000; border-right:solid 1px #000;"><b>FG TAG</b></td>
				<td rowspan="2" style="text-align: center; border-top:solid 1px #000; border-bottom:solid 1px #000; border-right:solid 1px #000;"><img style="padding: 2px;" align="center" width="80px" src="'.$PNG_WEB_DIR.basename($filename).'"></td>
			  </tr>
			  <tr>
				<td colspan="3" style="border-bottom:solid 1px #000; border-left:solid 1px #000; border-right:solid 1px #000;">&nbsp;<font style="font-size: 8pt";>FG Code GDJ:</font> <br>&nbsp;<b>ทดสอบภาษาไทย เว็นวรรค BAA23CC1044R</b></td>
			  </tr>
			  <tr>
				<td colspan="4" style="border-bottom:solid 1px #000; border-left:solid 1px #000; border-right:solid 1px #000;">&nbsp;<font style="font-size: 8pt";>Description:</font> <br>&nbsp;<b>FOT1150X550X250 - RSC</b></td>
			  </tr>
			  <tr>
				<td colspan="2" style="border-bottom:solid 1px #000; border-left:solid 1px #000;">&nbsp;<font style="font-size: 8pt";>Customer Code:</font> <br>&nbsp;<b>ABT</b></td>
				<td colspan="2" style="border-bottom:solid 1px #000; border-left:solid 1px #000; border-right:solid 1px #000;">&nbsp;<font style="font-size: 8pt";>Quantity:</font> <br>&nbsp;<b>50</b></td>
			  </tr>
			  <tr>
				<td colspan="3" style="border-bottom:solid 1px #000; border-left:solid 1px #000;">&nbsp;<font style="font-size: 8pt";>Tag ID:</font> <br>&nbsp;<b>000000092</b></td>
				<td rowspan="2" style="text-align: center; border-bottom:solid 1px #000; border-left:solid 1px #000; border-right:solid 1px #000;"><img src="../logo_company/GDJ.png" height="70px" style="padding: 2px;"/></td>
			  </tr>
			  <tr>
				<td colspan="3" style="text-align: center; border-bottom:solid 1px #000; border-left:solid 1px #000;"><barcode code="000000092" type="C39" height="0.7"/></td>
			  </tr>
			</table>
		  </td>
		  <td width="2%"></td>
		  <td width="49%">
			<table width="100%" cellpadding="0" cellspacing="0">
			  <tr>
				<td colspan="3" align="center" style="border-top:solid 1px #000; border-bottom:solid 1px #000; border-left:solid 1px #000; border-right:solid 1px #000;"><b>FG TAG</b></td>
				<td rowspan="2" style="text-align: center; border-top:solid 1px #000; border-bottom:solid 1px #000; border-right:solid 1px #000;"><img style="padding: 2px;" align="center" width="80px" src="'.$PNG_WEB_DIR.basename($filename).'"></td>
			  </tr>
			  <tr>
				<td colspan="3" style="border-bottom:solid 1px #000; border-left:solid 1px #000; border-right:solid 1px #000;">&nbsp;<font style="font-size: 8pt";>FG Code GDJ:</font> <br>&nbsp;<b>BAA23CC1044R</b></td>
			  </tr>
			  <tr>
				<td colspan="4" style="border-bottom:solid 1px #000; border-left:solid 1px #000; border-right:solid 1px #000;">&nbsp;<font style="font-size: 8pt";>Description:</font> <br>&nbsp;<b>FOT1150X550X250 - RSC</b></td>
			  </tr>
			  <tr>
				<td colspan="2" style="border-bottom:solid 1px #000; border-left:solid 1px #000;">&nbsp;<font style="font-size: 8pt";>Customer Code:</font> <br>&nbsp;<b>ABT</b></td>
				<td colspan="2" style="border-bottom:solid 1px #000; border-left:solid 1px #000; border-right:solid 1px #000;">&nbsp;<font style="font-size: 8pt";>Quantity:</font> <br>&nbsp;<b>50</b></td>
			  </tr>
			  <tr>
				<td colspan="3" style="border-bottom:solid 1px #000; border-left:solid 1px #000;">&nbsp;<font style="font-size: 8pt";>Tag ID:</font> <br>&nbsp;<b>000000092</b></td>
				<td rowspan="2" style="text-align: center; border-bottom:solid 1px #000; border-left:solid 1px #000; border-right:solid 1px #000;"><img src="../logo_company/GDJ.png" height="70px" style="padding: 2px;"/></td>
			  </tr>
			  <tr>
				<td colspan="3" style="text-align: center; border-bottom:solid 1px #000; border-left:solid 1px #000;"><barcode code="000000092" type="C39" height="0.7"/></td>
			  </tr>
			</table>
		  </td>
		</tr>
	</table>
</body>
</html>';

$mpdf->WriteHTML($html);

$mpdf->Output();
?>