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
$iden_txt_pallet_running = isset($_POST['iden_txt_pallet_running']) ? $_POST['iden_txt_pallet_running'] : '';

//check pallet not use
$strSql_chk_tags = " SELECT SUBSTRING(pallet_code,3,10) as substr_pallet_code FROM tbl_pallet_running where pallet_status = 'Waiting' and pallet_issue_by = '$t_cur_user_code_VMI_GDJ' order by pallet_id desc ";
$objQuery_chk_tags = sqlsrv_query($db_con, $strSql_chk_tags, $params, $options);
$num_row_chk_tags = sqlsrv_num_rows($objQuery_chk_tags);

//null
if($num_row_chk_tags == 0)
{
	//insert tags
	$strSql_insert_tags = " INSERT INTO tbl_pallet_running
	   (
	   [pallet_code]
	   ,[pallet_status]
	   ,[pallet_issue_by]
	   ,[pallet_issue_date]
	   ,[pallet_issue_time]
	   ,[pallet_issue_datetime]
	   )
	VALUES
	   (
	   '$iden_txt_pallet_running'
	   ,'Waiting'
	   ,'$t_cur_user_code_VMI_GDJ'
	   ,'$buffer_date'
	   ,'$buffer_time'
	   ,'$buffer_datetime'
	   )
	";
	$objQuery_insert_tags = sqlsrv_query($db_con, $strSql_insert_tags);
	
	echo "new";
}
else
{
	
	echo "dup";
}

sqlsrv_close($db_con);
?>