<?
require_once("../../application.php");

$buffer_date = date("Y-m-d");
$buffer_time = date("H:i:s"); //24H
$buffer_datetime = date("Y-m-d H:i:s");


/**********************************************************************************/
/*var *****************************************************************************/
$t_cur_user_code_VMI_GDJ = isset($_SESSION['t_cur_user_code_VMI_GDJ']) ? $_SESSION['t_cur_user_code_VMI_GDJ'] : '';
$dri_id = isset($_POST['dri_id_']) ? $_POST['dri_id_'] : '';


   		//Update Driver mst
$strSql_update_driver = " 
		UPDATE [tbl_driver_mst]
            SET          
             [driver_status] =  'InActive'
            ,[driver_issue_by] =  '$t_cur_user_code_VMI_GDJ'
            ,[driver_issue_date] = '$buffer_date'
            ,[driver_issue_time] = '$buffer_time'
            ,[driver_issue_datetime] = '$buffer_datetime'
            WHERE driver_id = '$dri_id'
		";
        $objQuery_insert_check_stock = sqlsrv_query($db_con, $strSql_update_driver);
        
        echo "UPDATE_OK";
        
sqlsrv_close($db_con);
?>