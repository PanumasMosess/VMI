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
    $repn_id_ajax = isset($_POST['repn_id_ajax']) ? $_POST['repn_id_ajax'] : '';
    $repn_order_ajax = isset($_POST['repn_order_ajax']) ? $_POST['repn_order_ajax'] : '';



    $sql_update_repn = "UPDATE [tbl_replenishment] SET repn_order_ref = '$repn_order_ajax' WHERE repn_id = '$repn_id_ajax'";

    $objQuery = sqlsrv_query($db_con, $sql_update_repn);

    // if($objQuery){
    //     $sql_select = "SELECT 
    //     ";
    // }

?>