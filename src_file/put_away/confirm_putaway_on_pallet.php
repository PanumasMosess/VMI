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
$iden_hdn_pre_put_tags_id = isset($_POST['iden_hdn_pre_put_tags_id']) ? $_POST['iden_hdn_pre_put_tags_id'] : '';
$iden_txt_scn_put_pallet = isset($_POST['iden_txt_scn_put_pallet']) ? $_POST['iden_txt_scn_put_pallet'] : '';

//get current pallet location
$strSql_get_curr_pallet = " SELECT receive_pallet_code,receive_location FROM tbl_receive where receive_pallet_code = '$iden_txt_scn_put_pallet' ";
$objQuery_get_curr_pallet = sqlsrv_query($db_con, $strSql_get_curr_pallet, $params, $options);
$num_row_get_tags = sqlsrv_num_rows($objQuery_get_curr_pallet);

if($num_row_get_tags != 0)
{
	while($objResult_get_curr_pallet = sqlsrv_fetch_array($objQuery_get_curr_pallet, SQLSRV_FETCH_ASSOC))
	{
		$buffer_receive_pallet_code = $objResult_get_curr_pallet['receive_pallet_code'];
		$buffer_receive_location = $objResult_get_curr_pallet['receive_location'];
	}

	//get tags ID
	$strSql_get_tags = " SELECT pre_receive_tags_code FROM tbl_pre_receive where pre_receive_id = '$iden_hdn_pre_put_tags_id' ";
	$objQuery_get_tags = sqlsrv_query($db_con, $strSql_get_tags);

	while($objResult_get_tags = sqlsrv_fetch_array($objQuery_get_tags, SQLSRV_FETCH_ASSOC))
	{
		$buffer_tags_code = $objResult_get_tags['pre_receive_tags_code'];
	}

	if($objQuery_get_tags)
	{
		//insert tbl_receive
		$sqlIns = " INSERT INTO tbl_receive
			   (
			   [receive_tags_code]
			   ,[receive_pallet_code]
			   ,[receive_location]
			   ,[receive_status]
			   ,[receive_date]
			   ,[receive_time]
			   ,[receive_datetime]
			   ,[receive_issue_by]
			   ,[receive_issue_date]
			   ,[receive_issue_time]
			   ,[receive_issue_datetime]
			   )
		 VALUES
			   (
			   '$buffer_tags_code'
			   ,'$buffer_receive_pallet_code'
			   ,'$buffer_receive_location'
			   ,'Received'
			   ,'$buffer_date'
			   ,'$buffer_time'
			   ,'$buffer_datetime'
			   ,'$t_cur_user_code_VMI_GDJ'
			   ,'$buffer_date'
			   ,'$buffer_time'
			   ,'$buffer_datetime'
			   )
		";
		$result_sqlIns = sqlsrv_query($db_con, $sqlIns);
		
		if($result_sqlIns)
		{
			//delete master tags pre-receive
			$sqlDelete = " DELETE FROM tbl_pre_receive WHERE pre_receive_id = '$iden_hdn_pre_put_tags_id' ";
			$result_sqlDelete = sqlsrv_query($db_con, $sqlDelete);	
		}
	}
}

sqlsrv_close($db_con);
?>