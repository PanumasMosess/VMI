<?
    require_once("../../application.php");

    /**********************************************************************************/
    /*current user ********************************************************************/
    $t_cur_user_code_VMI_GDJ = isset($_SESSION['t_cur_user_code_VMI_GDJ']) ? $_SESSION['t_cur_user_code_VMI_GDJ'] : '';
    $t_cur_user_type_VMI_GDJ = isset($_SESSION['t_cur_user_type_VMI_GDJ']) ? $_SESSION['t_cur_user_type_VMI_GDJ'] : '';

    $date_start = isset($_POST['date_start_']) ? $_POST['date_start_'] : '';
    $date_end = isset($_POST['date_end_']) ? $_POST['date_end_'] : '';

    $buffer_date = date("Y-m-d");
    $buffer_time = date("H:i:s"); //24H
    $buffer_datetime = date("Y-m-d H:i:s");


    //get stock replenishment
    $objQuery_sp_stock_repn = sqlsrv_query($db_con, " EXEC sp_db_wms_stock_replenish 'Confirmed' ");
    $objResult_sp_stock_repn = sqlsrv_fetch_array($objQuery_sp_stock_repn, SQLSRV_FETCH_ASSOC);

    //check null
    if($objResult_sp_stock_repn['count_pack_qty'] == NULL){ $str_sp_c_total_stock_repn = '0'; } else { $str_sp_c_total_stock_repn = $objResult_sp_stock_repn['count_pack_qty']; }

?>

<script>
    //stock
    $("#spn_db_wms_stock_pcs").html('<?=number_format($str_sp_wms_stock_sum_pcs_qty);?>');
    $("#spn_db_wms_stock_pack").html('<?=number_format($str_sp_wms_stock_count_pack_qty);?>');
</script>
<?
sqlsrv_close($db_con);
?>