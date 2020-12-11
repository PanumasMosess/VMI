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
$iden_txt_picking_running = isset($_POST['iden_txt_picking_running']) ? $_POST['iden_txt_picking_running'] : '';

//check picking not use
$strSql_chk_picking_no = " SELECT 
  SUBSTRING(picking_code,0,3) as substr_ps
  ,SUBSTRING(picking_code,3,6) as substr_ymd 
  ,SUBSTRING(picking_code,9,4) as substr_run_digit
  FROM tbl_picking_running where picking_status = 'Waiting' and picking_issue_date = '$buffer_date' and picking_issue_by = '$t_cur_user_code_VMI_GDJ' order by picking_id desc ";
$objQuery_chk_picking_no = sqlsrv_query($db_con, $strSql_chk_picking_no, $params, $options);
$num_row_chk_picking_no = sqlsrv_num_rows($objQuery_chk_picking_no);

//null
if($num_row_chk_picking_no == 0)
{
	//insert picking no
	$strSql_insert_picking_no = " INSERT INTO tbl_picking_running
	   (
	   [picking_code]
	   ,[picking_status]
	   ,[picking_issue_by]
	   ,[picking_issue_date]
	   ,[picking_issue_time]
	   ,[picking_issue_datetime]
	   )
	VALUES
	   (
	   '$iden_txt_picking_running'
	   ,'Waiting'
	   ,'$t_cur_user_code_VMI_GDJ'
	   ,'$buffer_date'
	   ,'$buffer_time'
	   ,'$buffer_datetime'
	   )
	";
	$objQuery_insert_picking_no = sqlsrv_query($db_con, $strSql_insert_picking_no);
	
	echo "new";
}
else
{
	
	echo "dup";
}

sqlsrv_close($db_con);
?>