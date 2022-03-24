<?
require_once("../../application.php");

/**********************************************************************************/
/*current user ********************************************************************/
$t_cur_user_code_VMI_GDJ = isset($_SESSION['t_cur_user_code_VMI_GDJ']) ? $_SESSION['t_cur_user_code_VMI_GDJ'] : '';
$t_cur_user_type_VMI_GDJ = isset($_SESSION['t_cur_user_type_VMI_GDJ']) ? $_SESSION['t_cur_user_type_VMI_GDJ'] : '';

$buffer_date = date("Y-m-d");
$buffer_time = date("H:i:s"); //24H
$buffer_datetime = date("Y-m-d H:i:s");

/**********************************************************************************/
/*var *****************************************************************************/
$iden_txt_move_scn_pallet_id_adjustStock = isset($_POST['iden_txt_move_scn_pallet_id_adjustStock']) ? $_POST['iden_txt_move_scn_pallet_id_adjustStock'] : '';

//check match
$strSQL_match = "select receive_tags_code from tbl_receive 
where 
receive_status = 'Received'
and
receive_pallet_code = '$iden_txt_move_scn_pallet_id_adjustStock'
";
$objQuery_match = sqlsrv_query($db_con, $strSQL_match);

while($objResult = sqlsrv_fetch_array($objQuery_match, SQLSRV_FETCH_ASSOC))
{

    $receive_tags_code = $objResult['receive_tags_code'];

if($objResult)//check match
{
	//check duplicate adj tags
	$strSQL_dup_pre_adj_tags = "select tags_tags_code from tbl_adjust_inventory 
	where 
	tags_tags_code = '$receive_tags_code'
	";
	$objQuery_dup_pre_adj_tags = sqlsrv_query($db_con, $strSQL_dup_pre_adj_tags);
	$result_dup_pre_adj_tags = sqlsrv_fetch_array($objQuery_dup_pre_adj_tags, SQLSRV_FETCH_ASSOC);

	if($result_dup_pre_adj_tags)//check duplicate done
	{
		echo "duplicate";
	}
	else
	{
		//insert tags
		$strSql_insert_tags = " 
		INSERT INTO [dbo].[tbl_adjust_inventory]
		   (
		   [tags_tags_code]
		   ,[tags_by]
		   ,[tags_date]
		   ,[tags_time]
		   ,[tags_datetime]
		   )
		VALUES
		   (
		   '$receive_tags_code'
		   ,'$t_cur_user_code_VMI_GDJ'
		   ,'$buffer_date'
		   ,'$buffer_time'
		   ,'$buffer_datetime'
		   )
		";
		$objQuery_insert_tags = sqlsrv_query($db_con, $strSql_insert_tags);
	}
}
else 
{
	//check match tags_running
	$strSQL_match_tags_running = "select tags_code,receive_tags_code from tbl_tags_running 
	left join tbl_receive
	on tbl_tags_running.tags_code = tbl_receive.receive_tags_code
	where 
	tags_code = '$receive_tags_code'
	";
	$objQuery_match_tags_running = sqlsrv_query($db_con, $strSQL_match_tags_running);
	$result_match_tags_running = sqlsrv_fetch_array($objQuery_match_tags_running, SQLSRV_FETCH_ASSOC);
	$buffer_tags_code = $result_match_tags_running['tags_code'];
	$buffer_receive_tags_code = $result_match_tags_running['receive_tags_code'];

	//check match, receive
	if(ltrim(rtrim($buffer_tags_code)) == "" and ltrim(rtrim($buffer_receive_tags_code)) == "")
	{
		echo "not match";
	}
	else if(ltrim(rtrim($buffer_tags_code)) != "" and ltrim(rtrim($buffer_receive_tags_code)) == "")
	{
		echo "not receive";
	}
}

}

sqlsrv_close($db_con);
?>