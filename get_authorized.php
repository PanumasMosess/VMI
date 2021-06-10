<?
/**********************************************************************************/
/*current user ********************************************************************/
$t_cur_user_code_VMI_GDJ = isset($_SESSION['t_cur_user_code_VMI_GDJ']) ? $_SESSION['t_cur_user_code_VMI_GDJ'] : '';
$t_cur_user_type_VMI_GDJ = isset($_SESSION['t_cur_user_type_VMI_GDJ']) ? $_SESSION['t_cur_user_type_VMI_GDJ'] : '';

/**********************************************************************************/
/*get Authorized ******************************************************************/
$strSQL_authorized = "select * from tbl_user
where 
user_code = '$t_cur_user_code_VMI_GDJ' 
and 
user_enable = '1'
and
user_type = '$t_cur_user_type_VMI_GDJ'
";
$objQuery_authorized = sqlsrv_query($db_con, $strSQL_authorized);
$objResult_authorized = sqlsrv_fetch_array($objQuery_authorized, SQLSRV_FETCH_ASSOC);
//$objResult_authorized['setting_menu']

?>