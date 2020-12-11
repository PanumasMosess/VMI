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
$iden_txt_move_scn_pallet_id = isset($_POST['iden_txt_move_scn_pallet_id']) ? $_POST['iden_txt_move_scn_pallet_id'] : '';

//check match
$strSQL_match = "select receive_pallet_code from tbl_receive 
where 
receive_status = 'Received'
and
receive_pallet_code = '$iden_txt_move_scn_pallet_id'
";
$objQuery_match = sqlsrv_query($db_con, $strSQL_match);
$result_match = sqlsrv_fetch_array($objQuery_match, SQLSRV_FETCH_ASSOC);

if($result_match)//check match
{
	//check duplicate move pallet
	$strSQL_dup_pre_move_pallet = "select pl_move_pallet_code from tbl_internal_move_pallet 
	where 
	pl_move_pallet_code = '$iden_txt_move_scn_pallet_id'
	";
	$objQuery_dup_pre_move_pallet = sqlsrv_query($db_con, $strSQL_dup_pre_move_pallet);
	$result_dup_pre_move_pallet = sqlsrv_fetch_array($objQuery_dup_pre_move_pallet, SQLSRV_FETCH_ASSOC);

	if($result_dup_pre_move_pallet)//check duplicate done
	{
		echo "duplicate";
	}
	else
	{
		//insert pallet
		$strSql_insert_pallet = " INSERT INTO [dbo].[tbl_internal_move_pallet]
           (
		   [pl_move_pallet_code]
           ,[pl_move_by]
           ,[pl_move_date]
           ,[pl_move_time]
           ,[pl_move_datetime]
		   )
     VALUES
           (
		   '$iden_txt_move_scn_pallet_id'
           ,'$t_cur_user_code_VMI_GDJ'
		   ,'$buffer_date'
		   ,'$buffer_time'
		   ,'$buffer_datetime'
		   )
		";
		$objQuery_insert_pallet = sqlsrv_query($db_con, $strSql_insert_pallet);
	}
}
else 
{
	echo "not match";
}

sqlsrv_close($db_con);
?>