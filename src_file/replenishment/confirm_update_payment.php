<?
require_once("../../application.php");


$buffer_date = date("Y-m-d");
$buffer_time = date("H:i:s"); //24H
$buffer_datetime = date("Y-m-d H:i:s");

$ajax_payment_date = isset($_POST['ajax_payment_date']) ? $_POST['ajax_payment_date'] : '';
$ajax_init_id = isset($_POST['ajax_init_id']) ? $_POST['ajax_init_id'] : '';

$sql_payment = "UPDATE [dbo].[tbl_installment]
SET [installment_payment_date] = '$ajax_payment_date'
WHERE [in_id] = '$ajax_init_id'";

$objQuery_sql_payment = sqlsrv_query($db_con, $sql_payment, $params, $options);

if($objQuery_sql_payment){

    echo 'Success';

}else{
    echo 'False';
}
 

sqlsrv_close($db_con);
?>