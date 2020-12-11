<?
require_once("../../application.php");

$buffer_date = date("Y-m-d");
$buffer_time = date("H:i:s"); //24H
$buffer_datetime = date("Y-m-d H:i:s");


/**********************************************************************************/
/*var *****************************************************************************/
$cus_id = isset($_POST['txt_cus_code_']) ? $_POST['txt_cus_code_'] : '';
$txt_cus_name_th = isset($_POST['txt_cus_name_th_']) ? $_POST['txt_cus_name_th_'] : '';
$txt_cus_name_en = isset($_POST['txt_cus_name_en_']) ? $_POST['txt_cus_name_en_'] : '';
$txt_sel_cus_code = isset($_POST['txt_sel_cus_code_']) ? $_POST['txt_sel_cus_code_'] : '';
$txt_sel_cus_name = isset($_POST['txt_sel_cus_name_']) ? $_POST['txt_sel_cus_name_'] : '';
$txt_sel_cus_terminal_type = isset($_POST['txt_sel_cus_terminal_type_']) ? $_POST['txt_sel_cus_terminal_type_'] : '';
$txt_sel_cus_type = isset($_POST['txt_sel_cus_type_']) ? $_POST['txt_sel_cus_type_'] : '';
$txt_cus_email = isset($_POST['txt_cus_email_']) ? $_POST['txt_cus_email_'] : '';

   		//Update Customer mst
$strSql_update_customer = " 
		UPDATE [tbl_customer_mst]
            SET          
             [cus_name_th] = '$txt_cus_name_th'
            ,[cus_name_en] = '$txt_cus_name_en'
            ,[cus_with_bom_cus_code] = '$txt_sel_cus_code'
            ,[cus_with_bom_pj_name] = '$txt_sel_cus_name'
            ,[cus_terminal_type] = '$txt_sel_cus_terminal_type'
            ,[cus_email] = '$txt_cus_email'
            ,[cus_type] = '$txt_sel_cus_type'
            WHERE cus_id = '$cus_id'
		";
        $objQuery_insert_check_stock = sqlsrv_query($db_con, $strSql_update_customer);
        
        echo "UPDATE_OK";
        
sqlsrv_close($db_con);
?>