<?
require_once("../application.php");

$buffer_date = date("Y-m-d");
$buffer_time = date("H:i:s"); //24H
$buffer_datetime = date("Y-m-d H:i:s");

/**********************************************************************************/
/*current user ********************************************************************/
$t_cur_user_code_VMI_GDJ = isset($_SESSION['t_cur_user_code_VMI_GDJ']) ? $_SESSION['t_cur_user_code_VMI_GDJ'] : '';
$t_cur_user_type_VMI_GDJ = isset($_SESSION['t_cur_user_type_VMI_GDJ']) ? $_SESSION['t_cur_user_type_VMI_GDJ'] : '';

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
	'margin_top' => 12,
	'margin_bottom' => 10,
	'margin_header' => 10,
	'margin_footer' => 10
]);

$mpdf->SetProtection(array('print'));
$mpdf->SetTitle("Print Driver Tags");
$mpdf->SetAuthor("Albatross Logistics Co.,Ltd.");
$mpdf->SetWatermarkText("VMI Driver Tags");
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
                <td width="33%"><span style="font-weight: bold; font-style: italic;">VMI Driver Tags</span></td>
                <td width="33%" align="center" style="font-weight: bold; font-style: italic;">{PAGENO}/{nbpg}</td>
                <td width="33%" style="text-align: right; ">Printed: {DATE j-m-Y}</td>
            </tr>
        </table>
    </htmlpageheader>
';

$html .= '
<table width="100%" border="0" cellpadding="0" cellspacing="15">
';

$i=1;
$q=" SELECT [driver_code]
      ,[driver_pass_md5]
      ,[driver_name_th]
      ,[driver_name_en]
      ,[driver_company]
      ,[driver_section]	  
FROM tbl_driver_mst
where driver_status = 'Active'
order by
driver_code
asc
";   
$qr=sqlsrv_query($db_con, $q, $params, $options);   
$numItem=sqlsrv_num_rows($qr);
$numCol=2;
$remainCol=$numCol-($numItem%$numCol);
while($rs = sqlsrv_fetch_array($qr, SQLSRV_FETCH_ASSOC))
{
	if($i%$numCol==1){
	$html .= '<tr><td width="49%"><table width="100%" cellpadding="0" cellspacing="0">
			  <tr>
				<td colspan="3" align="center" style="border-top:solid 1px #000; border-bottom:solid 1px #000; border-left:solid 1px #000; border-right:solid 1px #000;"><b>Driver Identification Card</b></td>
				<td rowspan="2" style="text-align: center; border-top:solid 1px #000; border-bottom:solid 1px #000; border-right:solid 1px #000;"><barcode code="'.$rs['driver_code'].'" class="qrCode" type="QR" size="0.4" error="M" disableborder = "1"/>
				</td>
			  </tr>
			  <tr>
				<td colspan="3" style="border-bottom:solid 1px #000; border-left:solid 1px #000; border-right:solid 1px #000;">&nbsp;<font style="font-size: 8pt";>Driver Company:</font> <br>&nbsp;<b>'.$rs['driver_company'].'</b></td>
			  </tr>
			  <tr>
				<td colspan="4" style="border-bottom:solid 1px #000; border-left:solid 1px #000; border-right:solid 1px #000;">&nbsp;<font style="font-size: 8pt";>Driver-Name (EN):</font> <br>&nbsp;<b>'.$rs['driver_name_en'].'</b></td>
			  </tr>
			  <tr>
				<td colspan="4" style="border-bottom:solid 1px #000; border-left:solid 1px #000; border-right:solid 1px #000;">&nbsp;<font style="font-size: 8pt";>Driver-Name (TH):</font> <br>&nbsp;<b>'.$rs['driver_name_th'].'</b></td>
			  </tr>
			  <tr>
				<td colspan="3" style="text-align: center; border-bottom:solid 1px #000; border-left:solid 1px #000;" class="barcodecell"><barcode code="'.$rs['driver_code'].'" type="C39" class="barcode" height="1.5"/></td>
				<td style="text-align: center; border-bottom:solid 1px #000; border-left:solid 1px #000; border-right:solid 1px #000;"><img src="../logo_company/GDJ2.png" height="70px" style="padding: 2px;"/></td>
			  </tr>
			</table></td><td width="2%"></td>';
	}
	if($i%$numCol==2){
	$html .= '<td width="49%"><table width="100%" cellpadding="0" cellspacing="0">
			  <tr>
				<td colspan="3" align="center" style="border-top:solid 1px #000; border-bottom:solid 1px #000; border-left:solid 1px #000; border-right:solid 1px #000;"><b>Driver Identification Card</b></td>
				<td rowspan="2" style="text-align: center; border-top:solid 1px #000; border-bottom:solid 1px #000; border-right:solid 1px #000;"><barcode code="'.$rs['driver_code'].'" class="qrCode" type="QR" size="0.4" error="M" disableborder = "1"/>
				</td>
			  </tr>
			  <tr>
				<td colspan="3" style="border-bottom:solid 1px #000; border-left:solid 1px #000; border-right:solid 1px #000;">&nbsp;<font style="font-size: 8pt";>Driver Company:</font> <br>&nbsp;<b>'.$rs['driver_company'].'</b></td>
			  </tr>
			  <tr>
				<td colspan="4" style="border-bottom:solid 1px #000; border-left:solid 1px #000; border-right:solid 1px #000;">&nbsp;<font style="font-size: 8pt";>Driver-Name (EN):</font> <br>&nbsp;<b>'.$rs['driver_name_en'].'</b></td>
			  </tr>
			  <tr>
				<td colspan="4" style="border-bottom:solid 1px #000; border-left:solid 1px #000; border-right:solid 1px #000;">&nbsp;<font style="font-size: 8pt";>Driver-Name (TH):</font> <br>&nbsp;<b>'.$rs['driver_name_th'].'</b></td>
			  </tr>
			  <tr>
				<td colspan="3" style="text-align: center; border-bottom:solid 1px #000; border-left:solid 1px #000;" class="barcodecell"><barcode code="'.$rs['driver_code'].'" type="C39" class="barcode" height="1.5"/></td>
				<td style="text-align: center; border-bottom:solid 1px #000; border-left:solid 1px #000; border-right:solid 1px #000;"><img src="../logo_company/GDJ2.png" height="70px" style="padding: 2px;"/></td>
			  </tr>
			</table></td><td width="2%"></td>';
	}
	if($i%$numCol==3){
	$html .= '<td width="49%"><table width="100%" cellpadding="0" cellspacing="0">
			  <tr>
				<td colspan="3" align="center" style="border-top:solid 1px #000; border-bottom:solid 1px #000; border-left:solid 1px #000; border-right:solid 1px #000;"><b>Driver Identification Card</b></td>
				<td rowspan="2" style="text-align: center; border-top:solid 1px #000; border-bottom:solid 1px #000; border-right:solid 1px #000;"><barcode code="'.$rs['driver_code'].'" class="qrCode" type="QR" size="0.4" error="M" disableborder = "1"/>
				</td>
			  </tr>
			  <tr>
				<td colspan="3" style="border-bottom:solid 1px #000; border-left:solid 1px #000; border-right:solid 1px #000;">&nbsp;<font style="font-size: 8pt";>Driver Company:</font> <br>&nbsp;<b>'.$rs['driver_company'].'</b></td>
			  </tr>
			  <tr>
				<td colspan="4" style="border-bottom:solid 1px #000; border-left:solid 1px #000; border-right:solid 1px #000;">&nbsp;<font style="font-size: 8pt";>Driver-Name (EN):</font> <br>&nbsp;<b>'.$rs['driver_name_en'].'</b></td>
			  </tr>
			  <tr>
				<td colspan="4" style="border-bottom:solid 1px #000; border-left:solid 1px #000; border-right:solid 1px #000;">&nbsp;<font style="font-size: 8pt";>Driver-Name (TH):</font> <br>&nbsp;<b>'.$rs['driver_name_th'].'</b></td>
			  </tr>
			  <tr>
				<td colspan="3" style="text-align: center; border-bottom:solid 1px #000; border-left:solid 1px #000;" class="barcodecell"><barcode code="'.$rs['driver_code'].'" type="C39" class="barcode" height="1.5"/></td>
				<td style="text-align: center; border-bottom:solid 1px #000; border-left:solid 1px #000; border-right:solid 1px #000;"><img src="../logo_company/GDJ2.png" height="70px" style="padding: 2px;"/></td>
			  </tr>
			</table></td><td width="2%"></td>';
	}
	if($i%$numCol==0){
	$html .= '<td width="49%"><table width="100%" cellpadding="0" cellspacing="0">
			  <tr>
				<td colspan="3" align="center" style="border-top:solid 1px #000; border-bottom:solid 1px #000; border-left:solid 1px #000; border-right:solid 1px #000;"><b>Driver Identification Card</b></td>
				<td rowspan="2" style="text-align: center; border-top:solid 1px #000; border-bottom:solid 1px #000; border-right:solid 1px #000;"><barcode code="'.$rs['driver_code'].'" class="qrCode" type="QR" size="0.4" error="M" disableborder = "1"/>
				</td>
			  </tr>
			  <tr>
				<td colspan="3" style="border-bottom:solid 1px #000; border-left:solid 1px #000; border-right:solid 1px #000;">&nbsp;<font style="font-size: 8pt";>Driver Company:</font> <br>&nbsp;<b>'.$rs['driver_company'].'</b></td>
			  </tr>
			  <tr>
				<td colspan="4" style="border-bottom:solid 1px #000; border-left:solid 1px #000; border-right:solid 1px #000;">&nbsp;<font style="font-size: 8pt";>Driver-Name (EN):</font> <br>&nbsp;<b>'.$rs['driver_name_en'].'</b></td>
			  </tr>
			  <tr>
				<td colspan="4" style="border-bottom:solid 1px #000; border-left:solid 1px #000; border-right:solid 1px #000;">&nbsp;<font style="font-size: 8pt";>Driver-Name (TH):</font> <br>&nbsp;<b>'.$rs['driver_name_th'].'</b></td>
			  </tr>
			  <tr>
				<td colspan="3" style="text-align: center; border-bottom:solid 1px #000; border-left:solid 1px #000;" class="barcodecell"><barcode code="'.$rs['driver_code'].'" type="C39" class="barcode" height="1.5"/></td>
				<td style="text-align: center; border-bottom:solid 1px #000; border-left:solid 1px #000; border-right:solid 1px #000;"><img src="../logo_company/GDJ2.png" height="70px" style="padding: 2px;"/></td>
			  </tr>
			</table></td></tr>';
	}
	$i++;
}
if($remainCol>0 && $remainCol!=$numCol)
{
	$html .= '<td colspan="'.$remainCol.'"></td></tr>';
}

$html .= '
</tr>
</table>
</body>
</html>
';
$mpdf->WriteHTML($html); 
$mpdf->Output();
exit;
?>