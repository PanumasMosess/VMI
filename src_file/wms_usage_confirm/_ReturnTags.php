<?php
require_once("../../application.php");

/**********************************************************************************/
/*current user ********************************************************************/
$t_cur_user_code_VMI_GDJ = isset($_SESSION['t_cur_user_code_VMI_GDJ']) ? $_SESSION['t_cur_user_code_VMI_GDJ'] : '';
$t_cur_user_type_VMI_GDJ = isset($_SESSION['t_cur_user_type_VMI_GDJ']) ? $_SESSION['t_cur_user_type_VMI_GDJ'] : '';
$t_cur_user_section_VMI_GDJ = isset($_SESSION['t_cur_user_section_VMI_GDJ']) ? $_SESSION['t_cur_user_section_VMI_GDJ'] : '';

$buffer_date = date("Y-m-d");
$buffer_time = date("H:i:s"); //24H
$buffer_datetime = date("Y-m-d H:i:s");

/**********************************************************************************/
/*var *****************************************************************************/
$txt_return_scan_tags = isset($_POST['txt_return_scan_tags']) ? $_POST['txt_return_scan_tags'] : '';
$str_user_type = isset($_POST['str_user_type']) ? $_POST['str_user_type'] : '';

//check permission
if($str_user_type == "Administrator")
{
	//get project
	$strSQL_pj = " 
	select [usage_terminal_name] 
	from tbl_usage_conf 
	where 
	usage_tags_code = '$txt_return_scan_tags'
	";
	$objQuery_pj = sqlsrv_query($db_con, $strSQL_pj);
	$result_pj = sqlsrv_fetch_array($objQuery_pj, SQLSRV_FETCH_ASSOC);
	
	$sql_get_pj = $result_pj['usage_terminal_name'];
}
else
{
	//get project
	$strSQL_pj = " 
	select [usage_terminal_name] 
	from tbl_usage_conf 
	where 
	usage_tags_code = '$txt_return_scan_tags'
	";
	$objQuery_pj = sqlsrv_query($db_con, $strSQL_pj);
	$result_pj = sqlsrv_fetch_array($objQuery_pj, SQLSRV_FETCH_ASSOC);
	
	$sql_get_pj = $result_pj['usage_terminal_name'];
}
	
//Check tags
$strSQL_tags = " 
select [usage_tags_code],[usage_terminal_name] 
from tbl_usage_conf 
where 
usage_tags_code = '$txt_return_scan_tags'
";
$objQuery_tags = sqlsrv_query($db_con, $strSQL_tags);
$result_tags = sqlsrv_fetch_array($objQuery_tags, SQLSRV_FETCH_ASSOC);

if($result_tags)
{
	$Sql_Check_Tag = "
	SELECT [usage_tags_code],[usage_terminal_name] 
	FROM tbl_usage_conf
    WHERE 
	usage_tags_code = '$txt_return_scan_tags' 
	AND usage_terminal_name = '$sql_get_pj'
	";
	$Query_Check_Tag = sqlsrv_query($db_con, $Sql_Check_Tag, $params, $options);
	$result_Check_Tag = sqlsrv_fetch_array($Query_Check_Tag, SQLSRV_FETCH_ASSOC);
	if($result_Check_Tag)
	{
		$sqlInst_ReTags = "
		INSERT INTO tbl_return_tags
		(
			re_tags_code
			,re_fg_code_set_abt
			,re_sku_code_abt
			,re_fg_code_gdj
			,re_ship_type
			,re_part_customer
			,re_terminal_name
			,re_unit_type
			,re_by
			,re_date
			,re_time
			,re_datetime
		)
		SELECT 
			usage_tags_code
			,usage_fg_code_set_abt
			,usage_sku_code_abt
			,usage_fg_code_gdj
			,usage_ship_type
			,usage_part_customer
			,usage_terminal_name
			,usage_unit_type
			,'$t_cur_user_code_VMI_GDJ'
			,'$buffer_date'
			,'$buffer_time'
			,'$buffer_datetime'
		FROM tbl_usage_conf 
		WHERE usage_tags_code = '$txt_return_scan_tags'
		";
		$result_ReTags = sqlsrv_query($db_con, $sqlInst_ReTags);
		
		if($result_ReTags) 
		{
			$sql_re_inventory = " UPDATE tbl_receive SET receive_status = '".$result_tags['usage_terminal_name']."' 
			WHERE receive_tags_code = '$txt_return_scan_tags'";
			$result_cut_inventory = sqlsrv_query($db_con, $sql_re_inventory);

			$Sql_Del_Usage = "DELETE FROM tbl_usage_conf WHERE usage_tags_code = '$txt_return_scan_tags'";
			$Result_Del_Usage = sqlsrv_query($db_con, $Sql_Del_Usage);
			
			if($Result_Del_Usage) 
			{
				echo "success";
			}
		}
	}
	else
	{
		echo "wrong pj";
	}
}
else
{
	echo "not match";
}

sqlsrv_close($db_con);
?>