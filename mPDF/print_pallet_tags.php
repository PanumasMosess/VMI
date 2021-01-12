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
$tag = isset($_REQUEST['tag']) ? $_REQUEST['tag'] : '';

//decode
$tag = var_decode($tag);

$strSql = " 
SELECT 
	receive_pallet_code
	,tags_fg_code_gdj
	,tags_fg_code_gdj_desc
	,receive_location
	,receive_status
	,receive_date
	,sum(tags_packing_std) as sum_pkg_std
FROM tbl_pallet_running
left join tbl_receive
on tbl_pallet_running.pallet_code = tbl_receive.receive_pallet_code
left join tbl_tags_running
on tbl_receive.receive_tags_code = tbl_tags_running.tags_code
where
receive_status = 'Received' and receive_pallet_code = '$tag'
group by
	receive_pallet_code
	,tags_fg_code_gdj
	,tags_fg_code_gdj_desc
	,receive_location
	,receive_status
	,receive_date
order by 
receive_pallet_code desc
";

$objQuery = sqlsrv_query($db_con, $strSql, $params, $options);
$num_row = sqlsrv_num_rows($objQuery);

$row_id = 0;
while($objResult = sqlsrv_fetch_array($objQuery, SQLSRV_FETCH_ASSOC))
{
	$row_id++;

	$receive_pallet_code = $objResult['receive_pallet_code'];
	$tags_fg_code_gdj = $objResult['tags_fg_code_gdj'];
	$tags_fg_code_gdj_desc = $objResult['tags_fg_code_gdj_desc'];
	$receive_location = $objResult['receive_location'];
	$receive_status = $objResult['receive_status'];
	$receive_date = $objResult['receive_date'];
	$tags_packing_std = $objResult['sum_pkg_std'];
}

$fg_code = $tags_fg_code_gdj;
$fg_des = $tags_fg_code_gdj_desc;
//$fg_des = "FOT1150X550X250-SNP1-PARTITION - A"; 
$date_ = date("d/m/Y", strtotime($receive_date));
$qty_ = $tags_packing_std;

$simbol = "'";
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

//set var
$t_qcode = $tag;
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
	'margin_left' => 5,
	'margin_right' => 5,
	'margin_top' => 5,
	'margin_bottom' => 5,
	'margin_header' => 10,
	'margin_footer' => 10,
	'format' => 'A4-L',
]);

$mpdf->SetProtection(array('print'));
$mpdf->SetTitle("Print Pallet Tag");
$mpdf->SetAuthor("Albatross Logistics Co.,Ltd.");
$mpdf->SetWatermarkText("VMI Pallet Tag");
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
                <td width="33%"><span style="font-weight: bold; font-style: italic;">VMI Pallet Tag</span></td>
                <td width="33%" align="center" style="font-weight: bold; font-style: italic;">{PAGENO}/{nbpg}</td>
                <td width="33%" style="text-align: right; ">Printed: {DATE j-m-Y}</td>
			</tr>		
		</table>
    </htmlpageheader>
';

$html .= '<br><table width="100%" cellpadding="0" cellspacing="35">
		<tr>
		  <td>
			<table width="100%" cellpadding="0" cellspacing="0">
			  <tr>
				<td colspan="4" style="text-align: center; border-top:solid 1px #e0ebeb; border-bottom:solid 1px #e0ebeb; border-left:solid 1px #e0ebeb; border-right:solid 1px #e0ebeb;"><br><font style="font-size: 50pt";><b>Put Away Tag</b></font><br><br><br></td>
			  </tr>
			  <tr>
				<td colspan="4" style="border-left:solid 1px #e0ebeb; border-right:solid 1px #e0ebeb;"><font style="font-size: 15pt";><br><b>&nbsp;&nbsp;FG. Code: '.$fg_code.'</b></font><br><br><br></td>
			  </tr>
			<tr>
			  <td colspan="4"  style="text-align: center; border-left:solid 1px #e0ebeb; border-right:solid 1px #e0ebeb;"><font style="font-size: 50pt;";><b>'.$fg_des.'</b></font><br><br><br><br></td>
			</tr>
			<tr>
				<td colspan="4" style="border-left:solid 1px #e0ebeb;border-right:solid 1px #e0ebeb;">&nbsp;</td>
			</tr>
			<tr>		  
			  <td colspan="4" rowspan="2" style="border-left:solid 1px #e0ebeb; text-align: center; border-right:solid 1px #e0ebeb;"><font style="font-size: 40pt";><b>Q'.$simbol.'Ty:&nbsp;&nbsp;</b></font><font style="font-size: 50pt";><b>'.$qty_.'&nbsp;&nbsp;</b></font><font style="font-size: 40pt";><b>Pcs</b></font><br><br><br></td>
			</tr>
			<tr>
				<td colspan="2">&nbsp;</td>
				<td colspan="2">&nbsp;</td>
			  </tr>
			<tr>
			  <td colspan="4" style="text-align: center; border-left:solid 1px #e0ebeb; border-right:solid 1px #e0ebeb;">&nbsp;&nbsp;<font style="font-size: 12pt";><b>Recived Date:</b></font>&nbsp;&nbsp;<font style="font-size: 20pt";><b>'.$date_.'</b></font><br><br><br></td>
			</tr>
			<tr>
			  <td colspan="4" style="text-align: center; border-left:solid 1px #e0ebeb; border-right:solid 1px #e0ebeb;">&nbsp;&nbsp;<font style="font-size: 15pt";><b>Pallet ID:</b></font></td>
			</tr>
			  <tr>
				<td colspan="4" style="text-align: center; border-left:solid 1px #e0ebeb;  border-right:solid 1px #e0ebeb;"><barcode code="'.$tag.'" type="C39" class="barcode" height="1.5" />&nbsp;&nbsp;&nbsp;&nbsp;</td>			
			  </tr>
			  <tr>
			  <td colspan="4" style="text-align: center; border-left:solid 1px #e0ebeb;  border-right:solid 1px #e0ebeb;"><font style="font-size: 10pt";><b>'.$tag.'</b></font>&nbsp;&nbsp;&nbsp;&nbsp;</td><br><br><br>			
			</tr>
			<tr>
			<td colspan="4" style="text-align: center; border-left:solid 1px #e0ebeb; border-right:solid 1px #e0ebeb;"><font style="font-size: 13pt";><b>Location:</b></font><font style="font-size: 30pt";><b>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;'.$receive_location.'</b></font></td>	
		  </tr>
			<tr>
				<td colspan="4" style="text-align: right; border-left:solid 1px #e0ebeb; border-bottom:solid 1px #e0ebeb; border-right:solid 1px #e0ebeb;"><img src="../logo_company/GDJ.png" height="60px" style="padding: 2px;"/></td>
			</tr>
			</table>
		  </td>
		</tr>
	</table>
</body>
</html>
';
$mpdf->WriteHTML($html);
$mpdf->Output();
exit;
?>

<!-- <td colspan="3"  style="text-align: left;border-right:solid 1px #000;"><font style="font-size: 35pt;";><b>'.$fg_des.'</b></font><br><br><br><br></td> -->
<!-- <td colspan="" style=" border-right:solid 1px #000;"><font style="font-size: 20pt";><b>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;'.$receive_location.'</b></font>&nbsp;&nbsp;&nbsp;&nbsp;</td>		 -->