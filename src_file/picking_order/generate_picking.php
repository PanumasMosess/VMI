<?
require_once("../../application.php");

/**********************************************************************************/
/*current user ********************************************************************/
$t_cur_user_code_VMI_GDJ = isset($_SESSION['t_cur_user_code_VMI_GDJ']) ? $_SESSION['t_cur_user_code_VMI_GDJ'] : '';
$t_cur_user_type_VMI_GDJ = isset($_SESSION['t_cur_user_type_VMI_GDJ']) ? $_SESSION['t_cur_user_type_VMI_GDJ'] : '';

$buffer_date = date("Y-m-d");
$buffer_time = date("H:i:s"); //24H
$buffer_datetime = date("Y-m-d H:i:s");

//gen picking code PSymd0001 / PS2008230001  12 digit
///////////////////Get picking no.///////////////////
$strSql_get_pickCode = " 
SELECT TOP(1)
  SUBSTRING(picking_code,0,3) as substr_ps
  ,SUBSTRING(picking_code,3,6) as substr_ymd 
  ,SUBSTRING(picking_code,9,4) as substr_run_digit
  FROM tbl_picking_running order by picking_id desc
";
$objQuery_get_pickCode = sqlsrv_query($db_con, $strSql_get_pickCode, $params, $options);
$num_row_get_pickCode = sqlsrv_num_rows($objQuery_get_pickCode);

//clear
$buffer_substr_run_digit = 0;
$buffer_substr_ps = "";
$buffer_substr_ymd = "";
	
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
	$buffer_substr_ps = "PS";
	$buffer_substr_ymd = date("ymd");
	$sum_pickCode = 1;
	$sprintf_pickCode = sprintf("%04d",$sum_pickCode);//generate to 4 digit
	$full_pickCode = $buffer_substr_ps.$buffer_substr_ymd.$sprintf_pickCode;//full pickCode
	
	//echo $full_pickCode;
}
else
{
	$sum_pickCode = $buffer_substr_run_digit + 1;//sum + 1
	$sprintf_pickCode = sprintf("%04d",$sum_pickCode);//generate to 4 digit
	$full_pickCode = $buffer_substr_ps.$buffer_substr_ymd.$sprintf_pickCode;//full pickCode
	
	//echo $full_pickCode;
}
?>

<script type="text/javascript">
$('#txt_picking_running').val('<?=$full_pickCode;?>');
</script>
<?
sqlsrv_close($db_con);
?>