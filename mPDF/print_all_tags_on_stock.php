<?
require_once("../application.php");

$buffer_date = date("Y-m-d");
$buffer_time = date("H:i:s"); //24H
$buffer_datetime = date("Y-m-d H:i:s");

/**********************************************************************************/
/*current user ********************************************************************/
$t_cur_user_code_VMI_GDJ = isset($_SESSION['t_cur_user_code_VMI_GDJ']) ? $_SESSION['t_cur_user_code_VMI_GDJ'] : '';
$t_cur_user_type_VMI_GDJ = isset($_SESSION['t_cur_user_type_VMI_GDJ']) ? $_SESSION['t_cur_user_type_VMI_GDJ'] : '';
$t_cur_user_session_VMI_GDJ = isset($_SESSION['t_cur_user_session_VMI_GDJ']) ? $_SESSION['t_cur_user_session_VMI_GDJ'] : '';

/**********************************************************************************/
/*var *****************************************************************************/
$project = isset($_REQUEST['project']) ? $_REQUEST['project'] : '';
$min = isset($_REQUEST['min']) ? $_REQUEST['min'] : '';
$max = isset($_REQUEST['max']) ? $_REQUEST['max'] : '';

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
                <td width="33%"><span style="font-weight: bold; font-style: italic;">VMI Master Tags</span></td>
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
$q=" 
SELECT
			
tags_code,
tags_fg_code_gdj,
ps_t_tags_packing_std,
tags_packing_std,
ps_t_part_customer,
tags_issue_date,
tags_token,
receive_status,
receive_date,
dn_h_issue_date,
tags_fg_code_gdj_desc,
conf_qc_tags_code,
dn_h_status,
dn_h_receive_date,
ps_t_pj_name,
dn_t_dtn_code
       
FROM tbl_receive
left join tbl_tags_running
on tbl_receive.receive_tags_code = tbl_tags_running.tags_code
left join tbl_picking_tail
on tbl_tags_running.tags_code = tbl_picking_tail.ps_t_tags_code
left join tbl_usage_conf_qc
on tbl_usage_conf_qc.conf_qc_tags_code = tbl_receive.receive_tags_code
left join tbl_picking_head
on tbl_picking_head.ps_h_picking_code = tbl_picking_tail.ps_t_picking_code
left join tbl_dn_tail 
on tbl_picking_head.ps_h_picking_code = tbl_dn_tail.dn_t_picking_code
left join tbl_dn_head
on tbl_dn_tail.dn_t_dtn_code = tbl_dn_head.dn_h_dtn_code
where receive_status = '$project'  and (dn_h_receive_date between '$min' and '$max') 
order by tags_code asc
"; 

$qr=sqlsrv_query($db_con, $q, $params, $options);   
$numItem=sqlsrv_num_rows($qr);
$numCol=2;
$remainCol=$numCol-($numItem%$numCol);
while($rs = sqlsrv_fetch_array($qr, SQLSRV_FETCH_ASSOC))
{
	$tags_fg_code_gdj = $rs['tags_fg_code_gdj'];
	$tags_fg_code_gdj_desc = $rs['tags_fg_code_gdj_desc'];
	
	//get cus code
	$str_cus_code = get_cus_code($db_con,'print_tags',$tags_fg_code_gdj,$tags_fg_code_gdj_desc);
	
	if($i%$numCol==1){
	$html .= '<tr><td width="49%"><table width="100%" cellpadding="0" cellspacing="0">
			  <tr>
				<td colspan="3" align="center" style="border-top:solid 1px #000; border-bottom:solid 1px #000; border-left:solid 1px #000;"><b>FG TAG</b></td>
				<td rowspan="2" style="text-align: center; border-top:solid 1px #000;  border-left:solid 1px #000; border-right:solid 1px #000;">
				<barcode code="'.$rs['tags_code'].'" class="qrCode" type="QR" size="0.6" error="M" disableborder = "1"/>
				</td>
			  </tr>
			  <tr>
				<td colspan="3" style="border-left:solid 1px #000;">&nbsp;<font style="font-size: 8pt";>FG Code GDJ:</font> <br><font style="font-size: 8pt";>&nbsp;<b>'.$rs['tags_fg_code_gdj'].'</b></font></td>
			  </tr>
			  <tr>
				<td colspan="4" style="border-top:solid 1px #000;border-bottom:solid 1px #000; border-left:solid 1px #000; border-right:solid 1px #000;">&nbsp;<font style="font-size: 8pt";>Description:</font> <br>&nbsp;<font style="font-size: 13pt;font-family: thsarabun;"><b>'.$rs['tags_fg_code_gdj_desc'].'</b></font></td>
			  </tr>
			  <tr>
				<td colspan="2" style="border-bottom:solid 1px #000; border-left:solid 1px #000;">&nbsp;<font style="font-size: 8pt";>Customer Code:</font> <br><font style="font-size: 8pt";>&nbsp;<b>'.$str_cus_code.'</b></font></td>
				<td colspan="2" style="border-bottom:solid 1px #000; border-left:solid 1px #000; border-right:solid 1px #000;">&nbsp;<font style="font-size: 8pt";>Quantity:</font> <br><font style="font-size: 8pt";>&nbsp;<b>'.$rs['tags_packing_std'].'</b></font></td>
			  </tr>
			  <tr>
				<td colspan="3" style="border-bottom:solid 1px #000; border-left:solid 1px #000;">&nbsp;<font style="font-size: 8pt";>Tag ID:</font> <font style="font-size: 8pt";>&nbsp;<b>'.$rs['tags_code'].'</b></font></td>
				<td rowspan="2" style="text-align: center; border-bottom:solid 1px #000; border-left:solid 1px #000; border-right:solid 1px #000;"><img src="../logo_company/GDJ2.png" height="50px" style="padding: 2px;"/></td>
			  </tr>
			  <tr>
				<td colspan="3" style="text-align: center; border-bottom:solid 1px #000; border-left:solid 1px #000;" class="barcodecell"><barcode code="'.$rs['tags_code'].'" type="C39" class="barcode" size="0.8" height="1.2"/></td>
			  </tr>
			  <tr>
				<td colspan="4" style="text-align: left; border-bottom:solid 1px #000; border-left:solid 1px #000; border-right:solid 1px #000;">&nbsp;<font style="font-size: 8pt";>Lot:</font> <font style="font-size: 8pt";>&nbsp;<b>'.$rs['tags_issue_date'].'</b><br>&nbsp;<b>'.$rs['tags_token'].'</b></font></td>
			  </tr>
			</table></td><td width="2%"></td>';
	}
	if($i%$numCol==2){
	$html .= '<td width="49%"><table width="100%" cellpadding="0" cellspacing="0">
			  <tr>
				<td colspan="3" align="center" style="border-top:solid 1px #000; border-bottom:solid 1px #000; border-left:solid 1px #000;"><b>FG TAG</b></td>
				<td rowspan="2" style="text-align: center; border-top:solid 1px #000;  border-left:solid 1px #000; border-right:solid 1px #000;">
				<barcode code="'.$rs['tags_code'].'" class="qrCode" type="QR" size="0.6" error="M" disableborder = "1"/>
				</td>
			  </tr>
			  <tr>
				<td colspan="3" style="border-left:solid 1px #000;">&nbsp;<font style="font-size: 8pt";>FG Code GDJ:</font> <br><font style="font-size: 8pt";>&nbsp;<b>'.$rs['tags_fg_code_gdj'].'</b></font></td>
			  </tr>
			  <tr>
				<td colspan="4" style="border-top:solid 1px #000;border-bottom:solid 1px #000; border-left:solid 1px #000; border-right:solid 1px #000;">&nbsp;<font style="font-size: 8pt";>Description:</font> <br>&nbsp;<font style="font-size: 13pt;font-family: thsarabun;"><b>'.$rs['tags_fg_code_gdj_desc'].'</b></font></td>
			  </tr>
			  <tr>
				<td colspan="2" style="border-bottom:solid 1px #000; border-left:solid 1px #000;">&nbsp;<font style="font-size: 8pt";>Customer Code:</font> <br><font style="font-size: 8pt";>&nbsp;<b>'.$str_cus_code.'</b></font></td>
				<td colspan="2" style="border-bottom:solid 1px #000; border-left:solid 1px #000; border-right:solid 1px #000;">&nbsp;<font style="font-size: 8pt";>Quantity:</font> <br><font style="font-size: 8pt";>&nbsp;<b>'.$rs['tags_packing_std'].'</b></font></td>
			  </tr>
			  <tr>
				<td colspan="3" style="border-bottom:solid 1px #000; border-left:solid 1px #000;">&nbsp;<font style="font-size: 8pt";>Tag ID:</font> <font style="font-size: 8pt";>&nbsp;<b>'.$rs['tags_code'].'</b></font></td>
				<td rowspan="2" style="text-align: center; border-bottom:solid 1px #000; border-left:solid 1px #000; border-right:solid 1px #000;"><img src="../logo_company/GDJ2.png" height="50px" style="padding: 2px;"/></td>
			  </tr>
			  <tr>
				<td colspan="3" style="text-align: center; border-bottom:solid 1px #000; border-left:solid 1px #000;" class="barcodecell"><barcode code="'.$rs['tags_code'].'" type="C39" class="barcode" size="0.8" height="1.2"/></td>
			  </tr>
			  <tr>
				<td colspan="4" style="text-align: left; border-bottom:solid 1px #000; border-left:solid 1px #000; border-right:solid 1px #000;">&nbsp;<font style="font-size: 8pt";>Lot:</font> <font style="font-size: 8pt";>&nbsp;<b>'.$rs['tags_issue_date'].'</b><br>&nbsp;<b>'.$rs['tags_token'].'</b></font></td>
			  </tr>
			</table></td><td width="2%"></td>';
	}
	if($i%$numCol==3){
	$html .= '<td width="49%"><table width="100%" cellpadding="0" cellspacing="0">
			  <tr>
				<td colspan="3" align="center" style="border-top:solid 1px #000; border-bottom:solid 1px #000; border-left:solid 1px #000;"><b>FG TAG</b></td>
				<td rowspan="2" style="text-align: center; border-top:solid 1px #000;  border-left:solid 1px #000; border-right:solid 1px #000;">
				<barcode code="'.$rs['tags_code'].'" class="qrCode" type="QR" size="0.6" error="M" disableborder = "1"/>
				</td>
			  </tr>
			  <tr>
				<td colspan="3" style="border-left:solid 1px #000;">&nbsp;<font style="font-size: 8pt";>FG Code GDJ:</font> <br><font style="font-size: 8pt";>&nbsp;<b>'.$rs['tags_fg_code_gdj'].'</b></font></td>
			  </tr>
			  <tr>
				<td colspan="4" style="border-top:solid 1px #000; border-bottom:solid 1px #000; border-left:solid 1px #000; border-right:solid 1px #000;">&nbsp;<font style="font-size: 8pt";>Description:</font> <br>&nbsp;<font style="font-size: 13pt;font-family: thsarabun;"><b>'.$rs['tags_fg_code_gdj_desc'].'</b></font></td>
			  </tr>
			  <tr>
				<td colspan="2" style="border-bottom:solid 1px #000; border-left:solid 1px #000;">&nbsp;<font style="font-size: 8pt";>Customer Code:</font> <br><font style="font-size: 8pt";>&nbsp;<b>'.$str_cus_code.'</b></font></td>
				<td colspan="2" style="border-bottom:solid 1px #000; border-left:solid 1px #000; border-right:solid 1px #000;">&nbsp;<font style="font-size: 8pt";>Quantity:</font> <br><font style="font-size: 8pt";>&nbsp;<b>'.$rs['tags_packing_std'].'</b></font></td>
			  </tr>
			  <tr>
				<td colspan="3" style="border-bottom:solid 1px #000; border-left:solid 1px #000;">&nbsp;<font style="font-size: 8pt";>Tag ID:</font> <font style="font-size: 8pt";>&nbsp;<b>'.$rs['tags_code'].'</b></font></td>
				<td rowspan="2" style="text-align: center; border-bottom:solid 1px #000; border-left:solid 1px #000; border-right:solid 1px #000;"><img src="../logo_company/GDJ2.png" height="50px" style="padding: 2px;"/></td>
			  </tr>
			  <tr>
				<td colspan="3" style="text-align: center; border-bottom:solid 1px #000; border-left:solid 1px #000;" class="barcodecell"><barcode code="'.$rs['tags_code'].'" type="C39" class="barcode" size="0.8" height="1.2"/></td>
			  </tr>
			  <tr>
				<td colspan="4" style="text-align: left; border-bottom:solid 1px #000; border-left:solid 1px #000; border-right:solid 1px #000;">&nbsp;<font style="font-size: 8pt";>Lot:</font> <font style="font-size: 8pt";>&nbsp;<b>'.$rs['tags_issue_date'].'</b><br>&nbsp;<b>'.$rs['tags_token'].'</b></font></td>
			  </tr>
			</table></td><td width="2%"></td>';
	}
	if($i%$numCol==0){
	$html .= '<td width="49%"><table width="100%" cellpadding="0" cellspacing="0">
			  <tr>
				<td colspan="3" align="center" style="border-top:solid 1px #000; border-bottom:solid 1px #000; border-left:solid 1px #000;"><b>FG TAG</b></td>
				<td rowspan="2" style="text-align: center; border-top:solid 1px #000;  border-left:solid 1px #000; border-right:solid 1px #000;">
				<barcode code="'.$rs['tags_code'].'" class="qrCode" type="QR" size="0.6" error="M" disableborder = "1"/>
				</td>
			  </tr>
			  <tr>
				<td colspan="3" style=" border-left:solid 1px #000;">&nbsp;<font style="font-size: 8pt";>FG Code GDJ:</font> <br><font style="font-size: 8pt";>&nbsp;<b>'.$rs['tags_fg_code_gdj'].'</b></font></td>
			  </tr>
			  <tr>
				<td colspan="4" style="border-top:solid 1px #000; border-bottom:solid 1px #000; border-left:solid 1px #000; border-right:solid 1px #000;">&nbsp;<font style="font-size: 8pt";>Description:</font> <br>&nbsp;<font style="font-size: 13pt;font-family: thsarabun;"><b>'.$rs['tags_fg_code_gdj_desc'].'</b></font></td>
			  </tr>
			  <tr>
				<td colspan="2" style="border-bottom:solid 1px #000; border-left:solid 1px #000;">&nbsp;<font style="font-size: 8pt";>Customer Code:</font> <br><font style="font-size: 8pt";>&nbsp;<b>'.$str_cus_code.'</b></font></td>
				<td colspan="2" style="border-bottom:solid 1px #000; border-left:solid 1px #000; border-right:solid 1px #000;">&nbsp;<font style="font-size: 8pt";>Quantity:</font> <br><font style="font-size: 8pt";>&nbsp;<b>'.$rs['tags_packing_std'].'</b></font></td>
			  </tr>
			  <tr>
				<td colspan="3" style="border-bottom:solid 1px #000; border-left:solid 1px #000;">&nbsp;<font style="font-size: 8pt";>Tag ID:</font> <font style="font-size: 8pt";>&nbsp;<b>'.$rs['tags_code'].'</b></font></td>
				<td rowspan="2" style="text-align: center; border-bottom:solid 1px #000; border-left:solid 1px #000; border-right:solid 1px #000;"><img src="../logo_company/GDJ2.png" height="50px" style="padding: 2px;"/></td>
			  </tr>
			  <tr>
				<td colspan="3" style="text-align: center; border-bottom:solid 1px #000; border-left:solid 1px #000;" class="barcodecell"><barcode code="'.$rs['tags_code'].'" type="C39" class="barcode" size="0.8" height="1.2"/></td>
			  </tr>
			  <tr>
				<td colspan="4" style="text-align: left; border-bottom:solid 1px #000; border-left:solid 1px #000; border-right:solid 1px #000;">&nbsp;<font style="font-size: 8pt";>Lot:</font> <font style="font-size: 8pt";>&nbsp;<b>'.$rs['tags_issue_date'].'</b><br>&nbsp;<b>'.$rs['tags_token'].'</b></font></td>
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