<?
require_once("../../application.php");

$buffer_date = date("Y-m-d");
$buffer_time = date("H:i:s"); //24H
$buffer_datetime = date("Y-m-d H:i:s");


/**********************************************************************************/
/*var *****************************************************************************/
$t_cur_user_code_VMI_GDJ = isset($_SESSION['t_cur_user_code_VMI_GDJ']) ? $_SESSION['t_cur_user_code_VMI_GDJ'] : '';
$txt_dri_code = isset($_POST['txt_dri_code_']) ? $_POST['txt_dri_code_'] : '';
$txt_dri_name_th = isset($_POST['txt_dri_name_th_']) ? $_POST['txt_dri_name_th_'] : '';
$txt_dri_name_en = isset($_POST['txt_dri_name_en_']) ? $_POST['txt_dri_name_en_'] : '';
$txt_head_vehicle = isset($_POST['txt_head_vehicle_']) ? $_POST['txt_head_vehicle_'] : '';
$txt_tail_vehicle = isset($_POST['txt_tail_vehicle_']) ? $_POST['txt_tail_vehicle_'] : '';


   		//Update Driver mst
$strSql_update_driver = " 
		UPDATE [tbl_driver_mst]
            SET          
             [driver_name_th] = '$txt_dri_name_th'
             ,[driver_name_en] = '$txt_dri_name_en'
             ,[driver_truck_head_no] = '$txt_head_vehicle'
             ,[driver_truck_tail_no] = '$txt_tail_vehicle'
             ,[driver_issue_by] = '$t_cur_user_code_VMI_GDJ'
             ,[driver_issue_date] = '$buffer_date'
             ,[driver_issue_time] = '$buffer_time' 
             ,[driver_issue_datetime] = '$buffer_datetime'  
            WHERE driver_id = '$txt_dri_code'
		";
        $objQuery_insert_check_stock = sqlsrv_query($db_con, $strSql_update_driver);
        
        echo "UPDATE_OK";
        
sqlsrv_close($db_con);
?>