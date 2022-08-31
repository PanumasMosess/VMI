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

$date_order = $buffer_date;
$Recepted_no = "OL00000";

$strSql_DTNHead = " 
SELECT [customer_outlet_code]
      ,[customer_outlet_name]
      ,[customer_outlet_buy_product_fg]
      ,[customer_outlet_buy_product_des]
      ,[customer_outlet_tex_inv]
      ,[customer_outlet_tel]
      ,[customer_outlet_qty]
      ,[customer_outlet_product_price]
      ,[customer_outlet_customer_price]
      ,[customer_outlet_date]
      ,[customer_outlet_time]
      ,[customer_outlet_date_time]
  FROM [tbl_customer_outlet] where customer_outlet_code = '$tag' 
";

$objQuery_DTNHead = sqlsrv_query($db_con, $strSql_DTNHead, $params, $options);
$num_row_DTNHead = sqlsrv_num_rows($objQuery_DTNHead);

while($objResult_DTNHead = sqlsrv_fetch_array($objQuery_DTNHead, SQLSRV_FETCH_ASSOC))
{
	$customer_outlet_code = $objResult_DTNHead['customer_outlet_code'];
	$customer_outlet_name = $objResult_DTNHead['customer_outlet_name'];
	$customer_outlet_buy_product_fg = $objResult_DTNHead['customer_outlet_buy_product_fg'];
	$customer_outlet_buy_product_des = $objResult_DTNHead['customer_outlet_buy_product_des'];
	$customer_outlet_tex_inv = $objResult_DTNHead['customer_outlet_tex_inv'];
	$customer_outlet_tel = $objResult_DTNHead['customer_outlet_tel'];
	$customer_outlet_qty = $objResult_DTNHead['customer_outlet_qty'];
	$customer_outlet_date_time = substr($objResult_DTNHead['customer_outlet_date_time'],0,10);
	$customer_outlet_product_price = $objResult_DTNHead['customer_outlet_product_price'];

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
        <table width="100%" style="vertical-align: bottom; font-family: serif; font-size: 12pt; color: #000000; font-weight: bold; font-style: italic;">
            <tr>   
            </tr>
        </table>
    </htmlpageheader>
';

$html .= '
<table width="100%" border="0" cellpadding="0" cellspacing="0">
<tr>
    <td colspan="2" align="left"><img src="../logo_company/gdjr.png" style="width: 135px;padding: 0px;" /></td>
    <td colspan="5" align="left" style="font-weight:900; font-family: thsarabun; font-size: 15pt;"><b>บริษัท กล่องดวงใจรีเทล จำกัด</b><br>336/11 หมู่7 ตำบลบ่อวิน อำเภอศรีราชา <br>จังหวัดชลบุรี 20230 <br>โทร. 033 135 018</td>
</tr>
<tr>
    <td colspan="3" align="left"></td>
    <td colspan="3" align="left" style="font-weight:900; font-family: thsarabun; font-size: 18pt;"><b>ใบเสร็จรับเงิน/RECEIPT</b></td>
</tr>
<tr>
  <td colspan="6" style="font-size: 5pt;">&nbsp;</td>	  
</tr>
<tr>
    <td colspan="6" align="left" style="font-weight:900; font-family: thsarabun; font-size: 14pt;"><b>วันที่/DATE: '.$customer_outlet_date_time.'</b></td>
    <td colspan="3" align="right" style="font-weight:900; font-family: thsarabun; font-size: 14pt;"><b>เลขที่ใบเสร็จ/NO: '.$customer_outlet_code.'</b></td>
</tr>
<tr>
    <td colspan="6" align="left" style="font-weight:900; font-family: thsarabun; font-size: 14pt;"><b>ชื่อลูกค้า/Customer Name: '.$customer_outlet_name.'</b></td>
    <td colspan="3" align="right" style="font-weight:900; font-family: thsarabun; font-size: 14pt;"><b>โทรศัพท์/Tel: '.$customer_outlet_tel.'</b></td>
</tr>
<tr>
    <td colspan="4" align="left" style="font-weight:900; font-family: thsarabun; font-size: 14pt;"><b>เลขใบกำกับภาษี/Tax inv: '.$customer_outlet_tex_inv.'</b></td>
</tr>
<tr>
  <td colspan="6" align="left">
  <table width="100%" border="0" cellpadding="0" cellspacing="0" style="font-size: 10pt; border-top:solid 1px #000; border-bottom:solid 1px #000; border-left:solid 1px #000; border-right:solid 1px #000;">
</table>
</td>	  
</tr>
<tr>
  <td colspan="6" style="font-size: 5pt;">&nbsp;</td>	  
</tr>
<tr>
<td rowspan="2"  height="25px" style="width: 5%; font-family: thsarabun; font-size: 14pt; text-align: center; border-top:solid 1px #000; border-left:solid 1px #000; border-right:solid 1px #000; background-color: #D3D3D3;"><b>สำดับ</b></td>
<td rowspan="2"  height="25px" style="width: 20%; font-family: thsarabun; font-size: 14pt; text-align: center; border-top:solid 1px #000; border-right:solid 1px #000; background-color: #D3D3D3;"><b>FG Code GDJ</b></td>
<td rowspan="2"  colspan="4" height="25px" style="font-family: thsarabun; font-size: 14pt; text-align: center; border-right:solid 1px #000; border-top:solid 1px #000; background-color: #D3D3D3;"><b>FG Desc.</b></td>	
<td rowspan="2"  height="25px" style="width: 10%; font-family: thsarabun; font-size: 14pt; text-align: center; border-right:solid 1px #000; border-top:solid 1px #000; background-color: #D3D3D3;"><b>ราคา/ชิ้น</b></td>	  
<td rowspan="2"  height="25px" style="width: 10%; font-family: thsarabun; font-size: 14pt; text-align: center; border-top:solid 1px #000; background-color: #D3D3D3;"><b>ชิ้น</b></td>
<td rowspan="2"  height="25px" style="width: 15%; font-family: thsarabun; font-size: 14pt; text-align: center; border-top:solid 1px #000; border-right:solid 1px #000; border-left:solid 1px #000; background-color: #D3D3D3;"><b>ราคา</b></td>
<tr>
    <td colspan="6" style="font-size: 0.5pt;">&nbsp;</td>
</tr>
</tr>
';
$strSql_DTNSheetDetails = " 
SELECT [customer_outlet_code]
      ,[customer_outlet_name]
      ,[customer_outlet_buy_product_fg]
      ,[customer_outlet_buy_product_des]
      ,[customer_outlet_tex_inv]
      ,[customer_outlet_tel]
      ,[customer_outlet_qty]
      ,[customer_outlet_product_price]
      ,[customer_outlet_customer_price]
      ,[customer_outlet_date]
      ,[customer_outlet_time]
      ,[customer_outlet_date_time]
  FROM [tbl_customer_outlet] where customer_outlet_code = '$tag' 
";

$objQuery_DTNSheetDetails = sqlsrv_query($db_con, $strSql_DTNSheetDetails, $params, $options);
$num_row_DTNSheetDetails = sqlsrv_num_rows($objQuery_DTNSheetDetails);

$row_id_DTNSheetDetails = 0;
$sum_packing_std = 0;
$sum_qty_std = 0;
$sum_price = 0;

$page = 1;
$perpage = 15;
while($objResult_DTNSheetDetails = sqlsrv_fetch_array($objQuery_DTNSheetDetails, SQLSRV_FETCH_ASSOC))
{
	$row_id_DTNSheetDetails++;


	$customer_outlet_buy_product_fg = $objResult_DTNSheetDetails['customer_outlet_buy_product_fg'];
	$customer_outlet_buy_product_des = $objResult_DTNSheetDetails['customer_outlet_buy_product_des'];
	$customer_outlet_qty = $objResult_DTNSheetDetails['customer_outlet_qty'];  
	$customer_outlet_product_price = $objResult_DTNSheetDetails['customer_outlet_product_price']; 
    $customer_outlet_customer_price = $objResult_DTNSheetDetails['customer_outlet_customer_price'];

	//$bom_fg_desc = substr($bom_fg_desc, 0, 26);

    $customer_outlet_product_price = number_format($customer_outlet_product_price, 2, '.', '');
	$customer_outlet_customer_price  = number_format($customer_outlet_customer_price, 2, '.', '');

	//sum qty
	$sum_qty_std = $sum_qty_std + $customer_outlet_qty;
	$sum_packing_std = $sum_packing_std + $customer_outlet_qty;
    $price = $customer_outlet_qty * $customer_outlet_product_price;
    $price = number_format($price, 2, '.', '');
    $sum_price = $sum_price + $price;
    $sum_price = number_format($sum_price, 2, '.', '');

    $amount_total = $customer_outlet_customer_price - $sum_price;
    $amount_total = number_format($amount_total, 2, '.', '');

	
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
        <td colspan="2" align="left"><img src="../logo_company/gdjr.png" style="width: 135px;padding: 0px;" /></td>
        <td colspan="5" align="left" style="font-weight:900; font-family: thsarabun; font-size: 15pt;"><b>บริษัท กล่องดวงใจรีเทล จำกัด</b><br>336/11 หมู่7 ตำบลบ่อวิน อำเภอศรีราชา <br>จังหวัดชลบุรี 20230 <br>โทร. 033 135 018</td>
    </tr>
    <tr>
        <td colspan="3" align="left"></td>
        <td colspan="3" align="left" style="font-weight:900; font-family: thsarabun; font-size: 18pt;"><b>ใบเสร็จรับเงิน/RECEIPT</b></td>
    </tr>
    <tr>
      <td colspan="6" style="font-size: 5pt;">&nbsp;</td>	  
    </tr>
    <tr>
        <td colspan="6" align="left" style="font-weight:900; font-family: thsarabun; font-size: 14pt;"><b>วันที่/DATE: '.$customer_outlet_date_time.'</b></td>
        <td colspan="3" align="right" style="font-weight:900; font-family: thsarabun; font-size: 14pt;"><b>เลขที่ใบเสร็จ/NO: '.$customer_outlet_code.'</b></td>
    </tr>
    <tr>
        <td colspan="6" align="left" style="font-weight:900; font-family: thsarabun; font-size: 14pt;"><b>ชื่อลูกค้า/Customer Name: '.$customer_outlet_name.'</b></td>
        <td colspan="3" align="right" style="font-weight:900; font-family: thsarabun; font-size: 14pt;"><b>โทรศัพท์/Tel: '.$customer_outlet_tel.'</b></td>
    </tr>
    <tr>
        <td colspan="4" align="left" style="font-weight:900; font-family: thsarabun; font-size: 14pt;"><b>เลขใบกำกับภาษี/Tax inv: '.$customer_outlet_tex_inv.'</b></td>
    </tr>
        <tr>
        <td colspan="6" align="left">
          <table width="100%" border="0" cellpadding="0" cellspacing="0" style="font-size: 10pt; border-top:solid 1px #000; border-bottom:solid 1px #000; border-left:solid 1px #000; border-right:solid 1px #000;"></table>
        </td>	 
</tr>
<tr>
  <td colspan="6" style="font-size: 5pt;">&nbsp;</td>	  
</tr>
<tr>
<td rowspan="2" height="25px" style="width: 5%; font-family: thsarabun; font-size: 14pt; text-align: center; border-top:solid 1px #000; border-left:solid 1px #000; border-right:solid 1px #000; background-color: #D3D3D3;"><b>สำดับ</b></td>
<td rowspan="2" height="25px" style="width: 20%;  font-family: thsarabun; font-size: 14pt; text-align: center; border-top:solid 1px #000; border-right:solid 1px #000; background-color: #D3D3D3;"><b>FG Code GDJ</b></td>
<td rowspan="2" height="25px" colspan="3" style="font-family: thsarabun; font-size: 14pt; text-align: center; border-right:solid 1px #000; border-top:solid 1px #000; background-color: #D3D3D3;"><b>FG Desc.</b></td>	
<td rowspan="2" height="25px" style="width: 10%;font-family: thsarabun; font-size: 14pt; text-align: center; border-right:solid 1px #000; border-top:solid 1px #000; background-color: #D3D3D3;"><b>ราคา/ชิ้น</b></td>	  
<td rowspan="2" height="25px" style="width: 10%;font-family: thsarabun; font-size: 14pt; text-align: center; border-top:solid 1px #000; background-color: #D3D3D3;"><b>ชิ้น</b></td>
<td rowspan="2" height="25px" style="width: 15%;font-family: thsarabun; font-size: 14pt; text-align: center; border-top:solid 1px #000; border-right:solid 1px #000; border-left:solid 1px #000; background-color: #D3D3D3;"><b>ราคา</b></td>
<tr>
    <td colspan="6" style="font-size: 0.5pt;">&nbsp;</td>
</tr>     
        ';
	}
			
	$html .= '<tr>
    <td rowspan="2"  style="width: 5%; font-size: 8pt; text-align: center; '.$str_css_bottom.' border-left:solid 1px #000; border-right:solid 1px #000; border-top:solid 1px #000;">'.$row_id_DTNSheetDetails.'</td>
    <td rowspan="2"  style="width: 20%; font-size: 8pt; text-align: center; '.$str_css_bottom.' border-right:solid 1px #000; border-top:solid 1px #000;">'.$customer_outlet_buy_product_fg.'</td>
    <td rowspan="2" colspan="4" style="font-weight:bold; font-family: thsarabun; font-size: 10pt; text-align: center;  '.$str_css_bottom.' border-top:solid 1px #000;">'.$customer_outlet_buy_product_des.'</td>
    <td rowspan="2" style="width: 10%; font-size: 8pt; text-align: center; '.$str_css_bottom.' border-left:solid 1px #000;  border-top:solid 1px #000;">'.$customer_outlet_product_price.'</td>
    <td rowspan="2" style="width: 10%; font-size: 8pt; text-align: center; '.$str_css_bottom.' border-left:solid 1px #000;  border-top:solid 1px #000;">'.$customer_outlet_qty.'</td>
    <td rowspan="3" style="width: 15%; font-size: 8pt; text-align: center; '.$str_css_bottom.' border-left:solid 1px #000; border-right:solid 1px #000; border-top:solid 1px #000;">'.$price.'</td>
  </tr>
  <tr>
    <td colspan="6" style="font-size: 0.5pt;">&nbsp;</td>
  </tr>
  <tr>
        <td colspan="6" style="font-size: 0.5pt;">&nbsp;</td>	  
  </tr>
	';
}
	$html .= '
	<tr>
	  <td rowspan="2" colspan="6" style="font-size: 9pt; text-align: right; border-top:solid 1px #000; border-bottom:solid 1px #000; border-left:solid 1px #000; background-color: #D3D3D3;">&nbsp;</td>
	  <td rowspan="2" style="font-size: 9pt; text-align: center; border-right:solid 1px #000; border-top:solid 1px #000; border-bottom:solid 1px #000; background-color: #D3D3D3;"><b>Total</b>&nbsp;</td>
	  <td rowspan="2" style="font-size: 9pt; text-align: center; border-top:solid 1px #000; border-bottom:solid 1px #000;"><b>'.$sum_qty_std.'</b></td>
	  <td rowspan="2" style="font-weight:900; font-family: thsarabun; font-size: 12pt; text-align: center; border-top:solid 1px #000; border-left:solid 1px #000; border-bottom:solid 1px #000; border-right:solid 1px #000;"><b>'.$sum_price.' บาท </b></td>
	</tr>
	<tr>
	  <td colspan="6" style="font-size: 5pt;">&nbsp;</td>  
	</tr>
    <tr>
	  <td colspan="6" style="font-size: 5pt;">&nbsp;</td>  
	</tr>
    <tr>
        <td  align="left" colspan="6" ></td>
        <td  align="right" colspan="3" style="font-weight:900; font-family: thsarabun; font-size: 13pt;"><b>เงินสด/Cash&nbsp; '.$customer_outlet_customer_price.'</b></td>	  
     </tr>
    <tr>
     <td  align="left" colspan="6" ></td>
     <td  align="right" colspan="3" style="font-weight:900; font-family: thsarabun; font-size: 13pt;"><b>คงเหลือ/Amount Total&nbsp; '.$amount_total.'</b></td>	  
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