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
$iden_txt_move_pj_scn_tag_id = isset($_POST['iden_txt_move_pj_scn_tag_id']) ? $_POST['iden_txt_move_pj_scn_tag_id'] : '';

//check match
$strSQL_match = "select receive_tags_code from tbl_receive 
where 
receive_status = 'Received'
and
receive_tags_code = '$iden_txt_move_pj_scn_tag_id'
";
$objQuery_match = sqlsrv_query($db_con, $strSQL_match);
$result_match = sqlsrv_fetch_array($objQuery_match, SQLSRV_FETCH_ASSOC);

if($result_match)//check match
{
	//check duplicate move tags
	$strSQL_dup_pre_move_tags = "select tags_move_pj_tags_code from tbl_internal_move_project 
	where 
	tags_move_pj_tags_code = '$iden_txt_move_pj_scn_tag_id'
	";
	$objQuery_dup_pre_move_tags = sqlsrv_query($db_con, $strSQL_dup_pre_move_tags);
	$result_dup_pre_move_tags = sqlsrv_fetch_array($objQuery_dup_pre_move_tags, SQLSRV_FETCH_ASSOC);

	if($result_dup_pre_move_tags)//check duplicate done
	{
		echo "duplicate";
	}
	else
	{
		//insert tags
		$strSql_insert_tags = " INSERT INTO [dbo].[tbl_internal_move_project]
           (
			[tags_move_pj_tags_code]
			,[tags_move_pj_by]
			,[tags_move_pj_date]
			,[tags_move_pj_time]
			,[tags_move_pj_datetime]
		   )
     VALUES
           (
		   '$iden_txt_move_pj_scn_tag_id'
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
	echo "not match";
}

sqlsrv_close($db_con);
?>