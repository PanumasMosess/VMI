<?
require_once("../../application.php");

/**********************************************************************************/
/*current user ********************************************************************/
$t_cur_user_code_VMI_GDJ = isset($_SESSION['t_cur_user_code_VMI_GDJ']) ? $_SESSION['t_cur_user_code_VMI_GDJ'] : '';
$t_cur_user_type_VMI_GDJ = isset($_SESSION['t_cur_user_type_VMI_GDJ']) ? $_SESSION['t_cur_user_type_VMI_GDJ'] : '';

$buffer_date = date("Y-m-d");
$buffer_time = date("H:i:s"); //24H
$buffer_datetime = date("Y-m-d H:i:s");

//gen DTN code DTNymd0001 / DTN2008230001  13 digit
///////////////////Get DTN no.///////////////////
$strSql_get_DTNCode = " 
  SELECT 
  SUBSTRING(dn_dtn_code,0,4) as substr_dtn
  ,SUBSTRING(dn_dtn_code,4,6) as substr_ymd 
  ,SUBSTRING(dn_dtn_code,10,4) as substr_run_digit
  ,dn_dtn_code
  FROM tbl_dn_running 
  where 
  dn_status = 'Waiting' 
  and dn_issue_date = '$buffer_date'
  and dn_issue_by = '$t_cur_user_code_VMI_GDJ' 
  order by dn_id desc
";
$objQuery_get_DTNCode = sqlsrv_query($db_con, $strSql_get_DTNCode, $params, $options);
$num_row_get_DTNCode = sqlsrv_num_rows($objQuery_get_DTNCode);

//clear
$buffer_substr_run_digit = 0;
$buffer_substr_dtn = "";
$buffer_substr_ymd = "";
	
while($objResult_get_DTNCode = sqlsrv_fetch_array($objQuery_get_DTNCode, SQLSRV_FETCH_ASSOC))
{
	$buffer_substr_dtn = $objResult_get_DTNCode['substr_dtn'];
	$buffer_substr_ymd = $objResult_get_DTNCode['substr_ymd'];
	$buffer_substr_run_digit = $objResult_get_DTNCode['substr_run_digit'];
	$dn_dtn_code = $objResult_get_DTNCode['dn_dtn_code'];
}

//echo $buffer_substr_dtn."/".$buffer_substr_ymd."/".$buffer_substr_run_digit;
//check curr date
if($buffer_substr_ymd != date("ymd"))
{
	$buffer_substr_dtn = "DTN";
	$buffer_substr_ymd = date("ymd");
	$sum_DTNCode = 1;
	$sprintf_DTNCode = sprintf("%04d",$sum_DTNCode);//generate to 4 digit
	$full_DTNCode = $buffer_substr_dtn.$buffer_substr_ymd.$sprintf_DTNCode;//full DTNCode
	
	//echo $full_DTNCode;
}
else
{
	//case DTN code status = Waiting
	if($num_row_get_DTNCode != 0) //current DTN no reserved by current user account
	{
		$sum_DTNCode = $buffer_substr_run_digit;
		$sprintf_DTNCode = sprintf("%04d",$sum_DTNCode);//generate to 4 digit
		$full_DTNCode = $buffer_substr_dtn.$buffer_substr_ymd.$sprintf_DTNCode;//full DTNCode
		
		//echo $full_DTNCode;
	}
	else
	{
		$sum_DTNCode = $buffer_substr_run_digit + 1;//sum + 1
		$sprintf_DTNCode = sprintf("%04d",$sum_DTNCode);//generate to 4 digit
		$full_DTNCode = $buffer_substr_dtn.$buffer_substr_ymd.$sprintf_DTNCode;//full DTNCode
		
		//echo $full_DTNCode;
	}
}
?>

<script type="text/javascript">
$('#txt_curr_dtn_no').val('<?=$full_DTNCode;?>');
$('#hdn_curr_dtn_no').val('<?=var_encode($full_DTNCode);?>');
</script>
<?
sqlsrv_close($db_con);
?>