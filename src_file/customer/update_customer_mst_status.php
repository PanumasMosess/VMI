<?
require_once("../../application.php");

$buffer_date = date("Y-m-d");
$buffer_time = date("H:i:s"); //24H
$buffer_datetime = date("Y-m-d H:i:s");


/**********************************************************************************/
/*var *****************************************************************************/
$t_cur_user_code_VMI_GDJ = isset($_SESSION['t_cur_user_code_VMI_GDJ']) ? $_SESSION['t_cur_user_code_VMI_GDJ'] : '';
$cus_id = isset($_POST['cus_id_']) ? $_POST['cus_id_'] : '';


   		//Update Customer mst
$strSql_update_customer = " 
		UPDATE [tbl_customer_mst]
            SET          
             [cus_status] =  'InActive'
            ,[cus_issue_by] =  '$t_cur_user_code_VMI_GDJ'
            ,[cus_issue_date] = '$buffer_date'
            ,[cus_issue_time] = '$buffer_time'
            ,[cus_issue_datetime] = '$buffer_datetime'
            WHERE cus_id = '$cus_id'
		";
        $objQuery_insert_check_stock = sqlsrv_query($db_con, $strSql_update_customer);
        
        echo "UPDATE_OK";
        
sqlsrv_close($db_con);
?>