<?
require_once("../../application.php");


$buffer_date = date("Y-m-d");
$buffer_time = date("H:i:s"); //24H
$buffer_datetime = date("Y-m-d H:i:s");

/**********************************************************************************/
/*var *****************************************************************************/
$ajax_user_login = isset($_POST['ajax_user_login']) ? $_POST['ajax_user_login'] : '';

$num_reset_md9 = '25f9e794323b453885f5181f1b624d0b';

//check user not null 
$str_user_not_null = "SELECT TOP (1) [user_code] FROM [tbl_user] where user_code = '$ajax_user_login'";

$objQuery = sqlsrv_query($db_con, $str_user_not_null, $params, $options);
$num_row = sqlsrv_num_rows($objQuery);
	
 if($num_row > 0){
     $strSQL_update_user_pass = "UPDATE tbl_user set [user_pass_md5] = '$num_reset_md9', [user_force_pass_chg] = '1' WHERE user_code = '$ajax_user_login'";
     $objQuery_update_user_pass = sqlsrv_query($db_con, $strSQL_update_user_pass);

     if($objQuery_update_user_pass){
        echo 'SUCCESS';
     }
 }else{
     echo 'FALSE';
 }


sqlsrv_close($db_con);
