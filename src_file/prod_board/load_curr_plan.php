<?
require_once("../../application.php");

$buffer_date = date("Y-m-d");

/////////////////////////////////////
//check time for find actual
$curr_time = date('H:i:s');
$curr_time = date('H:i:s', strtotime($curr_time));

//shift
$TimeStart = date('H:i:s', strtotime("08:00:00"));
$TimeStop = date('H:i:s', strtotime("23:59:59"));

//check time (08:00:00-23:59:59)  
if(($curr_time >= $TimeStart) && ($curr_time <= $TimeStop))
{
	//prepare date
	$str_chk_date1 = date('Y-m-d');
	$str_chk_date1 = date("Y-m-d", strtotime($str_chk_date1));
	
	$str_chk_date2 = date('Y-m-d');
	$str_chk_date2 = date("Y-m-d", strtotime("+1 day", strtotime($str_chk_date2)));
	
	//echo "DAY / ".$str_chk_date1." - ".$str_chk_date2;
	//where tags_issue_datetime between CONVERT(DATETIME, '$str_chk_date1 08:00:00') AND CONVERT(DATETIME, '$str_chk_date2 07:59:59')
	//echo "where tags_issue_datetime between CONVERT(DATETIME, '$str_chk_date1 08:00:00') AND CONVERT(DATETIME, '$str_chk_date2 07:59:59')";
}
else //check time (00:00:00-07:59:59)
{
	//prepare date
	//where tags_issue_datetime between CONVERT(DATETIME, '2022-01-30 08:00:00') AND CONVERT(DATETIME, '2022-01-31 07:59:59')
	$str_chk_date1 = date('Y-m-d');
	$str_chk_date1 = date("Y-m-d", strtotime("-1 day", strtotime($str_chk_date1)));
	
	$str_chk_date2 = date('Y-m-d');
	$str_chk_date2 = date("Y-m-d", strtotime($str_chk_date2));
	
	//echo "NIGHT / ".$str_chk_date1." - ".$str_chk_date2;
	//where tags_issue_datetime between CONVERT(DATETIME, '$str_chk_date1 08:00:00') AND CONVERT(DATETIME, '$str_chk_date2 07:59:59')
	//echo "where tags_issue_datetime between CONVERT(DATETIME, '$str_chk_date1 08:00:00') AND CONVERT(DATETIME, '$str_chk_date2 07:59:59')";
}

//read daily plan
$strSQL_Check = " select top 1 * FROM tbl_daily_plan where plan_date = '$str_chk_date1' ";
$objQuery = sqlsrv_query($db_con, $strSQL_Check);
				
//check have plan
if($objQuery) //true
{
	//read daily plan
	while($objResult = sqlsrv_fetch_array($objQuery, SQLSRV_FETCH_ASSOC))
	{
		$plan_date = $objResult['plan_date'];
		$plan_ft2_value = $objResult['plan_ft2_value'];
	}
	
	//read actual
	$strSQL_act = " 
	SELECT
	  tags_fg_code_gdj
	  ,tags_packing_std
	  ,count(tags_fg_code_gdj) as c_tags
	  ,tags_packing_std*count(tags_fg_code_gdj) as tags_qty
	  ,tags_token
	  ,ft2_fg_code
	  ,ft2_value
	  ,ft2_value*(tags_packing_std*count(tags_fg_code_gdj)) as sum_ft2
	FROM tbl_tags_running
	left join tbl_fg_ft2_mst
	on tbl_tags_running.tags_fg_code_gdj = tbl_fg_ft2_mst.ft2_fg_code
	where 
	tags_issue_datetime between CONVERT(DATETIME, '$str_chk_date1 08:00:00') AND CONVERT(DATETIME, '$str_chk_date2 07:59:59')
	group by 
		tags_fg_code_gdj
		,tags_packing_std
		,tags_token
		,ft2_fg_code
		,ft2_value
	";
	$objQuery_act = sqlsrv_query($db_con, $strSQL_act);
	
	$str_bal = 0;
	$str_full_act = 0;
	while($objResult_act = sqlsrv_fetch_array($objQuery_act, SQLSRV_FETCH_ASSOC))
	{
		//$tags_fg_code_gdj = $objResult_act['tags_fg_code_gdj'];
		//$tags_packing_std = $objResult_act['tags_packing_std'];
		//$c_tags = $objResult_act['c_tags'];
		//$tags_qty = $objResult_act['tags_qty'];
		$sum_ft2 = $objResult_act['sum_ft2'];
		
		$str_full_act = $str_full_act + $sum_ft2;
	}
	
	//balance
	$str_bal = $str_full_act - $plan_ft2_value;
	
	//ctrl color
	if($str_bal > 0) //green
	{
		$css_color_font = "<font style='color: green;'>+".number_format($str_bal,2)."</font>";
	}
	else //red
	{
		$css_color_font = "<font style='color: red;'>".number_format($str_bal,2)."</font>";
	}
	
	//efficiency
	$str_eff = ($str_full_act * 100) / $plan_ft2_value;
	$str_diff = $str_eff - 100;
	
	$css_color_eff = "<font style='color: green;'>".number_format($str_eff,2)."</font>";
	$css_color_diff = "<font style='color: red;'>".number_format($str_diff,2)."</font>";
}
else //false
{
	$plan_date = 0;
	$plan_ft2_value = 0;
	$str_full_act = 0;
	$str_bal = 0;
	$css_color_font = "<font style='color: #000000 ;'>".number_format('0',2)."</font>";
	$str_eff = 0;
	$str_diff = 0;
	$css_color_eff = "<font style='color: green;'>".number_format('0',2)."</font>";
	$css_color_diff = "<font style='color: red;'>".number_format('0',2)."</font>";
}
?>
<script type="text/javascript">	
$('#spn_load_curr_plan').html("<?=number_format($plan_ft2_value,2);?>");
$('#spn_load_curr_act').html("<?=number_format($str_full_act,2);?>");
$('#spn_load_curr_bal').html("<?=$css_color_font;?>");
$('#spn_load_curr_eff_act').html("<?=$css_color_eff;?>");
$('#spn_load_curr_eff_diff').html("<?=$css_color_diff;?>");
//$('#spn_load_curr_eff').html("<?=$css_color_eff;?> / <?=$css_color_diff;?>");
</script>
<?
sqlsrv_close($db_con);
?>