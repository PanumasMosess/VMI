<?
require_once("../../application.php");


$buffer_date = date("Y-m-d");
$buffer_time = date("H:i:s"); //24H
$buffer_datetime = date("Y-m-d H:i:s");

$ajax_sale_id = isset($_POST['ajax_sale_id']) ? $_POST['ajax_sale_id'] : '';
$ajax_receipt_number = isset($_POST['ajax_receipt_number']) ? $_POST['ajax_receipt_number'] : '';

$sql_sale_recp_no = "UPDATE [dbo].[tbl_b2c_sale] SET [b2c_sale_inv_no] = '$ajax_receipt_number'
WHERE b2c_sale_id = '$ajax_sale_id'";

$objQuery_sql_sale_recp_no = sqlsrv_query($db_con, $sql_sale_recp_no, $params, $options);

if($objQuery_sql_sale_recp_no){

    echo 'Success';

}else{
    echo 'False';
}
 

sqlsrv_close($db_con);
?>