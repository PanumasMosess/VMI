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
$iden_txt_dtn_running = isset($_POST['iden_txt_dtn_running']) ? $_POST['iden_txt_dtn_running'] : '';

//check DTN not use
$strSql_chk_dtn_no = " 
  SELECT 
  SUBSTRING(dn_dtn_code,0,4) as substr_dtn
  ,SUBSTRING(dn_dtn_code,4,6) as substr_ymd 
  ,SUBSTRING(dn_dtn_code,10,4) as substr_run_digit
  FROM tbl_dn_running 
  where 
  dn_status = 'Waiting' 
  and dn_issue_date = '$buffer_date'
  and dn_issue_by = '$t_cur_user_code_VMI_GDJ' 
  order by dn_id desc 
  ";
$objQuery_chk_dtn_no = sqlsrv_query($db_con, $strSql_chk_dtn_no, $params, $options);
$num_row_chk_dtn_no = sqlsrv_num_rows($objQuery_chk_dtn_no);

//null
if($num_row_chk_dtn_no == 0)
{
	//insert DTN no
	$strSql_insert_dtn_no = " INSERT INTO tbl_dn_running
	   (
	   [dn_dtn_code]
      ,[dn_status]
      ,[dn_issue_by]
      ,[dn_issue_date]
      ,[dn_issue_time]
      ,[dn_issue_datetime]
	   )
	VALUES
	   (
	   '$iden_txt_dtn_running'
	   ,'Waiting'
	   ,'$t_cur_user_code_VMI_GDJ'
	   ,'$buffer_date'
	   ,'$buffer_time'
	   ,'$buffer_datetime'
	   )
	";
	$objQuery_insert_dtn_no = sqlsrv_query($db_con, $strSql_insert_dtn_no);
	
	echo "new";
}
else
{
	
	echo "dup";
}

sqlsrv_close($db_con);
?>