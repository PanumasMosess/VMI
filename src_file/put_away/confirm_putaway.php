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
$iden_hdn_pre_receive_id = isset($_POST['iden_hdn_pre_receive_id']) ? $_POST['iden_hdn_pre_receive_id'] : '';
$iden_txt_curr_pallet_no = isset($_POST['iden_txt_curr_pallet_no']) ? $_POST['iden_txt_curr_pallet_no'] : '';
$iden_txt_curr_pallet_location = isset($_POST['iden_txt_curr_pallet_location']) ? $_POST['iden_txt_curr_pallet_location'] : '';

//get tags ID
$strSql_get_tags = " SELECT pre_receive_tags_code FROM tbl_pre_receive where pre_receive_id = '$iden_hdn_pre_receive_id' ";
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
           ,'$iden_txt_curr_pallet_no'
           ,'$iden_txt_curr_pallet_location'
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
		$sqlDelete = " DELETE FROM tbl_pre_receive WHERE pre_receive_id = '$iden_hdn_pre_receive_id' ";
		$result_sqlDelete = sqlsrv_query($db_con, $sqlDelete);	
		
		//update pallet tags status
		$sqlUpdate = " UPDATE tbl_pallet_running SET pallet_status = 'Matched' WHERE pallet_code = '$iden_txt_curr_pallet_no' ";
		$result_sqlUpdate = sqlsrv_query($db_con, $sqlUpdate);
	}
}
sqlsrv_close($db_con);
?>