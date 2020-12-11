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
$iden_txt_scan_master_tags = isset($_POST['iden_txt_scan_master_tags']) ? $_POST['iden_txt_scan_master_tags'] : '';

//check match
$strSQL_match = "select tags_code from tbl_tags_running 
where 
tags_code = '$iden_txt_scan_master_tags'
";
$objQuery_match = sqlsrv_query($db_con, $strSQL_match);
$result_match = sqlsrv_fetch_array($objQuery_match, SQLSRV_FETCH_ASSOC);

if($result_match)//check match
{
	//check duplicate pre-receive
	$strSQL_dup_pre_rec = "select pre_receive_tags_code from tbl_pre_receive 
	where 
	pre_receive_tags_code = '$iden_txt_scan_master_tags'
	";
	$objQuery_dup_pre_rec = sqlsrv_query($db_con, $strSQL_dup_pre_rec);
	$result_dup_pre_rec = sqlsrv_fetch_array($objQuery_dup_pre_rec, SQLSRV_FETCH_ASSOC);

	if($result_dup_pre_rec)//check duplicate done
	{
		echo "duplicate";
	}
	else
	{
		//check duplicate receive
		$strSQL_dup_rec = "select receive_tags_code from tbl_receive 
		where 
		receive_tags_code = '$iden_txt_scan_master_tags'
		";
		$objQuery_dup_rec = sqlsrv_query($db_con, $strSQL_dup_rec);
		$result_dup_rec = sqlsrv_fetch_array($objQuery_dup_rec, SQLSRV_FETCH_ASSOC);

		if($result_dup_rec)//check duplicate done
		{
			echo "duplicate receive";
		}
		else
		{
			//insert tags
			$strSql_insert_tags = " INSERT INTO tbl_pre_receive
			   (
			   [pre_receive_tags_code]
			   ,[pre_receive_issue_by]
			   ,[pre_receive_issue_date]
			   ,[pre_receive_issue_time]
			   ,[pre_receive_issue_datetime]
			   )
			VALUES
			   (
			   '$iden_txt_scan_master_tags'
			   ,'$t_cur_user_code_VMI_GDJ'
			   ,'$buffer_date'
			   ,'$buffer_time'
			   ,'$buffer_datetime'
			   )
			";
			$objQuery_insert_tags = sqlsrv_query($db_con, $strSql_insert_tags);
		}
	}
}
else 
{
	echo "not match";
}

sqlsrv_close($db_con);
?>