<?
require_once("../../application.php");

$buffer_date = date("Y-m-d");
$buffer_time = date("H:i:s"); //24H
$buffer_datetime = date("Y-m-d H:i:s");


/**********************************************************************************/
/*var *****************************************************************************/
$t_cur_user_code_VMI_GDJ = isset($_SESSION['t_cur_user_code_VMI_GDJ']) ? $_SESSION['t_cur_user_code_VMI_GDJ'] : '';
$cus_id = isset($_POST['individual_customer_chg_']) ? $_POST['individual_customer_chg_'] : '';
$individual_pwd_customer_chg = isset($_POST['individual_pwd_customer_chg_']) ? $_POST['individual_pwd_customer_chg_'] : '';
$individual_new_pwd_customer_chg = isset($_POST['individual_new_pwd_customer_chg_']) ? $_POST['individual_new_pwd_customer_chg_'] : '';

//check old password miss match
$pass_mod_entype = md5($individual_pwd_customer_chg);
$pass_mod_entype_update = md5($individual_new_pwd_customer_chg);
$strSQL_check_pass = "select cus_pass_md5 from tbl_customer_mst 
where 
cus_pass_md5 = '$pass_mod_entype' and cus_id = '$cus_id'
";

$objQuery_check_pass = sqlsrv_query($db_con, $strSQL_check_pass);
$result_check = sqlsrv_fetch_array($objQuery_check_pass, SQLSRV_FETCH_ASSOC);

if($result_check)//check duplicate done
{
      //Update Customer mst
$strSql_update_password = " 
UPDATE [tbl_customer_mst]
    SET          
     [cus_pass_md5] =  '$pass_mod_entype_update'
    ,[cus_issue_by] =  '$t_cur_user_code_VMI_GDJ'
    ,[cus_issue_date] = '$buffer_date'
    ,[cus_issue_time] = '$buffer_time'
    ,[cus_issue_datetime] = '$buffer_datetime'
    WHERE cus_id = '$cus_id'
";
$objQuery_update_password = sqlsrv_query($db_con, $strSql_update_password);

echo "UPDATE_OK";
   
}else
{
    echo "PASSWORD_NOT_MATCH";
}
     
sqlsrv_close($db_con);
?>