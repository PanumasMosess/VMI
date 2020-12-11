<?
require_once("application.php");

$strSql_get_pickCode = " SELECT [dn_h_id]
      ,[dn_h_dtn_code]
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
      ,[dn_h_issue_date]
      ,[dn_h_issue_time]
      ,[dn_h_issue_datetime]
  FROM [VMI_test].[dbo].[tbl_dn_head]
  where [dn_h_dtn_code] = 'DTN2011130002'
";
$objQuery_get_pickCode = sqlsrv_query($db_con, $strSql_get_pickCode, $params, $options);
$num_row_get_pickCode = sqlsrv_num_rows($objQuery_get_pickCode);

//clear
$buffer_substr_run_digit = 0;

while($objResult_get_pickCode = sqlsrv_fetch_array($objQuery_get_pickCode, SQLSRV_FETCH_ASSOC))
{
	$dn_h_driver_sign = $objResult_get_pickCode['dn_h_driver_sign'];
	$dn_h_cus_sign = $objResult_get_pickCode['dn_h_cus_sign'];
}


//check word
$str_chk_driver_sign = $dn_h_driver_sign;
$str_chk_cus_sign = $dn_h_cus_sign;

//project list allow
$array  = array('data:image/png;base64,');
$str_chk_dirver = strpos_var($str_chk_driver_sign, $array); // will return true
$str_chk_cus = strpos_var($str_chk_cus_sign, $array); // will return true

//driver
if($str_chk_dirver == false)
{
	$str_word_pre_fix_dirver = 'data:image/png;base64,'.$str_chk_driver_sign;
}
else
{
	$str_word_pre_fix_dirver = ''.$str_chk_driver_sign;
}

//customer
if($str_chk_cus == false)
{
	$str_word_pre_fix_cus = 'data:image/png;base64,'.$str_chk_cus_sign;
}
else
{
	$str_word_pre_fix_cus = ''.$str_chk_cus_sign;
}
?>
<img src="<?=$str_word_pre_fix_dirver;?>" width="100" height="100">
<img src="<?=$str_word_pre_fix_cus;?>" width="100" height="100">
<?
/*
$num_row_get_tags = 0;
for($i_count=1; $i_count<=3; $i_count++)
{
	//clear
	$buffer_tags_code = $i_count - 1;
	$sum_tags = $buffer_tags_code + 1;//sum + 1
	$sprintf_tags = sprintf("%09d",$sum_tags);//generate to 9 digit
	$full_tags = $sprintf_tags;//full tags
	
	echo $full_tags. " <br>";
}
*/	
	
//get ceil
//$str_fifo_picking_pack = ceil($repn_qty / $bom_packing);
//$str_conv_pack = floor($repn_qty / $bom_packing);
//$str_conv_piece = $repn_qty % $bom_packing;

/*
$str_prod_qty = 9;
$str_bom_packing_qty = 4;

$str_fifo_picking_pack = ceil($str_prod_qty / $str_bom_packing_qty);
$str_conv_pack = floor($str_prod_qty / $str_bom_packing_qty);
$str_conv_piece = $str_prod_qty % $str_bom_packing_qty;

echo $str_fifo_picking_pack . " Pack <br>";
echo $str_conv_pack . " Pack <br>";
echo $str_conv_piece . " Pcs <br>";
*/

/*
//gen picking code PSymd0001 / PS2008230001  12 digit
///////////////////Get picking no.///////////////////
$strSql_get_pickCode = " 
SELECT 
  SUBSTRING(picking_code,0,3) as substr_ps
  ,SUBSTRING(picking_code,3,6) as substr_ymd 
  ,SUBSTRING(picking_code,9,4) as substr_run_digit
  FROM tbl_picking_running order by picking_id asc
";
$objQuery_get_pickCode = sqlsrv_query($db_con, $strSql_get_pickCode, $params, $options);
$num_row_get_pickCode = sqlsrv_num_rows($objQuery_get_pickCode);

//clear
$buffer_substr_run_digit = 0;

while($objResult_get_pickCode = sqlsrv_fetch_array($objQuery_get_pickCode, SQLSRV_FETCH_ASSOC))
{
	$buffer_substr_ps = $objResult_get_pickCode['substr_ps'];
	$buffer_substr_ymd = $objResult_get_pickCode['substr_ymd'];
	$buffer_substr_run_digit = $objResult_get_pickCode['substr_run_digit'];
}

//echo $buffer_substr_ps."/".$buffer_substr_ymd."/".$buffer_substr_run_digit;

//check curr date
if($buffer_substr_ymd != date("ymd"))
{
	$sum_pickCode = 1;
	$sprintf_pickCode = sprintf("%04d",$sum_pickCode);//generate to 4 digit
	$full_pickCode = $buffer_substr_ps.date("ymd").$sprintf_pickCode;//full pickCode
	
	echo $full_pickCode;
}
else
{
	$sum_pickCode = $buffer_substr_run_digit + 1;//sum + 1
	$sprintf_pickCode = sprintf("%04d",$sum_pickCode);//generate to 4 digit
	$full_pickCode = $buffer_substr_ps.$buffer_substr_ymd.$sprintf_pickCode;//full pickCode
	
	echo $full_pickCode;
}
*/
?>