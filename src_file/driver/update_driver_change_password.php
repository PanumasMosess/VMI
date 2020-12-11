<?
require_once("../../application.php");

$buffer_date = date("Y-m-d");
$buffer_time = date("H:i:s"); //24H
$buffer_datetime = date("Y-m-d H:i:s");


/**********************************************************************************/
/*var *****************************************************************************/
$t_cur_user_code_VMI_GDJ = isset($_SESSION['t_cur_user_code_VMI_GDJ']) ? $_SESSION['t_cur_user_code_VMI_GDJ'] : '';
$dri_id = isset($_POST['individual_driver_chg_']) ? $_POST['individual_driver_chg_'] : '';
$individual_pwd_driver_chg = isset($_POST['individual_pwd_driver_chg_']) ? $_POST['individual_pwd_driver_chg_'] : '';
$individual_new_pwd_driver_chg = isset($_POST['individual_new_pwd_driver_chg_']) ? $_POST['individual_new_pwd_driver_chg_'] : '';

//check old password miss match
$pass_mod_entype = md5($individual_pwd_driver_chg);
$pass_mod_entype_update = md5($individual_new_pwd_driver_chg);
$strSQL_check_pass = "select driver_pass_md5 from tbl_driver_mst 
where 
driver_pass_md5 = '$pass_mod_entype' and driver_id = '$dri_id'
";

$objQuery_check_pass = sqlsrv_query($db_con, $strSQL_check_pass);
$result_check = sqlsrv_fetch_array($objQuery_check_pass, SQLSRV_FETCH_ASSOC);

if($result_check)
{
      //Update Driver mst
$strSql_update_password = " 
UPDATE [tbl_driver_mst]
    SET          
     [driver_pass_md5] =  '$pass_mod_entype_update'
    ,[driver_issue_by] =  '$t_cur_user_code_VMI_GDJ'
    ,[driver_issue_date] = '$buffer_date'
    ,[driver_issue_time] = '$buffer_time'
    ,[driver_issue_datetime] = '$buffer_datetime'
    WHERE driver_id = '$dri_id'
";
$objQuery_update_password = sqlsrv_query($db_con, $strSql_update_password);

echo "UPDATE_OK";
   
}else
{
    echo "PASSWORD_NOT_MATCH";
}
     
sqlsrv_close($db_con);
?>