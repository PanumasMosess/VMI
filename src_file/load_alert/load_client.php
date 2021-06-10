<?
require_once("../../application.php");

/**********************************************************************************/
/*current user ********************************************************************/
$t_cur_user_code_VMI_GDJ = isset($_SESSION['t_cur_user_code_VMI_GDJ']) ? $_SESSION['t_cur_user_code_VMI_GDJ'] : '';
$t_cur_user_type_VMI_GDJ = isset($_SESSION['t_cur_user_type_VMI_GDJ']) ? $_SESSION['t_cur_user_type_VMI_GDJ'] : '';
$t_cur_user_session_VMI_GDJ = isset($_SESSION['t_cur_user_session_VMI_GDJ']) ? $_SESSION['t_cur_user_session_VMI_GDJ'] : '';


$buffer_date = date("Y-m-d");
$buffer_time = date("H:i:s"); //24H
$buffer_datetime = date("Y-m-d H:i:s");

/**********************************************************************************/
/*Dashboard ***********************************************************************/
///////////////////////////////////
//stored procedure
//get print tags
$objQuery_sp_print_tags = sqlsrv_query($db_con, " EXEC sp_db_wms_print_tags 'null' ");
$objResult_sp_print_tags = sqlsrv_fetch_array($objQuery_sp_print_tags, SQLSRV_FETCH_ASSOC);

//check null
if($objResult_sp_print_tags['c_total_tags'] == NULL){ $str_sp_total_tags = '0'; } else { $str_sp_total_tags = $objResult_sp_print_tags['c_total_tags']; }
if($objResult_sp_print_tags['c_total_tags_today'] == NULL){ $str_sp_total_tags_today = '0'; } else { $str_sp_total_tags_today = $objResult_sp_print_tags['c_total_tags_today']; }

//get put away
$objQuery_sp_putaway = sqlsrv_query($db_con, " EXEC sp_db_wms_put_away 'Received' ");
$objResult_sp_putaway = sqlsrv_fetch_array($objQuery_sp_putaway, SQLSRV_FETCH_ASSOC);

//check null
if($objResult_sp_putaway['c_total_putaway'] == NULL){ $str_sp_c_total_putaway = '0'; } else { $str_sp_c_total_putaway = $objResult_sp_putaway['c_total_putaway']; }
if($objResult_sp_putaway['c_total_putaway_today'] == NULL){ $str_sp_c_total_putaway_today = '0'; } else { $str_sp_c_total_putaway_today = $objResult_sp_putaway['c_total_putaway_today']; }

//get replenishment
$objQuery_sp_replenishment = sqlsrv_query($db_con, " EXEC sp_db_wms_replenish_order 'null' ");
$objResult_sp_replenishment = sqlsrv_fetch_array($objQuery_sp_replenishment, SQLSRV_FETCH_ASSOC);

//check null
if($objResult_sp_replenishment['c_total_replenish'] == NULL){ $str_sp_replenishment = '0'; } else { $str_sp_replenishment = $objResult_sp_replenishment['c_total_replenish']; }


//get wms picking
$objQuery_sp_picking = sqlsrv_query($db_con, " EXEC sp_db_wms_picking 'Picking' ");
$objResult_sp_picking = sqlsrv_fetch_array($objQuery_sp_picking, SQLSRV_FETCH_ASSOC);

//check null
if($objResult_sp_picking['CounterPicking'] == NULL){ $str_sp_c_total_picking_qty = '0'; } else { $str_sp_c_total_picking_qty = $objResult_sp_picking['CounterPicking']; }


//get wms picking confirm
$objQuery_sp_picking_confirm = sqlsrv_query($db_con, " EXEC sp_db_wms_confirm_order 'Picking', 'Completed' ");
$objResult_sp_picking_confirm = sqlsrv_fetch_array($objQuery_sp_picking_confirm, SQLSRV_FETCH_ASSOC);

//check null
if($objResult_sp_picking_confirm['CounterPickingConfirm'] == NULL){ $str_sp_c_total_picking_confirm_qty = '0'; } else { $str_sp_c_total_picking_confirm_qty = $objResult_sp_picking_confirm['CounterPickingConfirm']; }


//get DTN
$objQuery_sp_dtn = sqlsrv_query($db_con, " EXEC sp_db_wms_dtn 'Delivery Transfer Note' ");
$objResult_sp_dtn = sqlsrv_fetch_array($objQuery_sp_dtn, SQLSRV_FETCH_ASSOC);

//check null
if($objResult_sp_dtn['CounterDTN'] == NULL){ $str_sp_c_total_dtn_qty = '0'; } else { $str_sp_c_total_dtn_qty = $objResult_sp_dtn['CounterDTN']; }



//get stock replenishment
$objQuery_sp_stock_repn = sqlsrv_query($db_con, " EXEC sp_db_wms_stock_replenish 'Confirmed', '$t_cur_user_session_VMI_GDJ'");
$objResult_sp_stock_repn = sqlsrv_fetch_array($objQuery_sp_stock_repn, SQLSRV_FETCH_ASSOC);

//check null
if($objResult_sp_stock_repn['count_pack_qty'] == NULL){ $str_sp_c_total_stock_repn = '0'; } else { $str_sp_c_total_stock_repn = $objResult_sp_stock_repn['count_pack_qty']; }


//get usage confirm
$objQuery_sp_usage_conf = sqlsrv_query($db_con, " EXEC sp_db_wms_usage_conf 'USAGE CONFIRM', '$t_cur_user_session_VMI_GDJ'");
$objResult_sp_usage_conf = sqlsrv_fetch_array($objQuery_sp_usage_conf, SQLSRV_FETCH_ASSOC);

//check null
if($objResult_sp_usage_conf['c_total_usage_conf_today'] == NULL){ $str_sp_c_total_usage_conf = '0'; } else { $str_sp_c_total_usage_conf = $objResult_sp_usage_conf['c_total_usage_conf_today']; }


//get wms stock (Received)
$objQuery_sp_wms_stock = sqlsrv_query($db_con, " EXEC sp_db_wms_stock 'Received' ");
$objResult_sp_wms_stock = sqlsrv_fetch_array($objQuery_sp_wms_stock, SQLSRV_FETCH_ASSOC);

//check null
if($objResult_sp_wms_stock['sum_pcs_qty'] == NULL){ $str_sp_wms_stock_sum_pcs_qty = '0'; } else { $str_sp_wms_stock_sum_pcs_qty = $objResult_sp_wms_stock['sum_pcs_qty']; }
if($objResult_sp_wms_stock['count_pack_qty'] == NULL){ $str_sp_wms_stock_count_pack_qty = '0'; } else { $str_sp_wms_stock_count_pack_qty = $objResult_sp_wms_stock['count_pack_qty']; }

/*
//get VMI stock
$objQuery_sp_vmi_stock = sqlsrv_query($db_con, " EXEC sp_db_wms_vmi_stock 'null' ");
$objResult_sp_vmi_stock = sqlsrv_fetch_array($objQuery_sp_vmi_stock, SQLSRV_FETCH_ASSOC);

//check null
if($objResult_sp_vmi_stock['sum_pcs_qty'] == NULL){ $str_sp_vmi_stock_sum_pcs_qty = '0'; } else { $str_sp_vmi_stock_sum_pcs_qty = $objResult_sp_vmi_stock['sum_pcs_qty']; }
if($objResult_sp_vmi_stock['count_pack_qty'] == NULL){ $str_sp_vmi_stock_count_pack_qty = '0'; } else { $str_sp_vmi_stock_count_pack_qty = $objResult_sp_vmi_stock['count_pack_qty']; }
*/



//get bom mst
$objQuery_sp_bom_mst = sqlsrv_query($db_con, " EXEC sp_db_wms_bom_mst 'null' ");
$objResult_sp_bom_mst = sqlsrv_fetch_array($objQuery_sp_bom_mst, SQLSRV_FETCH_ASSOC);

//check null
if($objResult_sp_bom_mst['bom_active'] == NULL){ $str_sp_bom_active = '0'; } else { $str_sp_bom_active = $objResult_sp_bom_mst['bom_active']; }
if($objResult_sp_bom_mst['bom_inactive'] == NULL){ $str_sp_bom_inactive = '0'; } else { $str_sp_bom_inactive = $objResult_sp_bom_mst['bom_inactive']; }
?>

<script type="text/javascript">
    //set notifications
    //print tags
    $("#spn_db_print_tags_total").html('<?= number_format($str_sp_total_tags); ?>');
    $("#spn_db_print_tags_today").html('<?= number_format($str_sp_total_tags_today); ?>');

    //put away
    $("#spn_db_total_putaway").html('<?= number_format($str_sp_c_total_putaway); ?>');
    $("#spn_db_total_putaway_today").html('<?= number_format($str_sp_c_total_putaway_today); ?>');

    //replenishment
    $("#spn_db_replenishment").html('<?= number_format($str_sp_replenishment); ?>');

    //picking
    $("#spn_db_total_picking_qty").html('<?= number_format($str_sp_c_total_picking_qty); ?>');

    //picking confirm
    $("#spn_db_total_picking_confirm_qty").html('<?= number_format($str_sp_c_total_picking_confirm_qty); ?>');

    //print delivery order
    $("#spn_db_total_dtn_qty").html('<?= number_format($str_sp_c_total_dtn_qty); ?>');

    //stock replenishment
    $("#spn_db_total_stock_repnish").html('<?= number_format($str_sp_c_total_stock_repn); ?>');

    //usage confirm
    $("#spn_db_total_tags_usage_conf").html('<?= number_format($str_sp_c_total_usage_conf); ?>');

    //stock
    $("#spn_db_wms_stock_pcs").html('<?= number_format($str_sp_wms_stock_sum_pcs_qty); ?>');
    $("#spn_db_wms_stock_pack").html('<?= number_format($str_sp_wms_stock_count_pack_qty); ?>');

    /*
    //terminal stock
    $("#spn_db_terminal_TSESA_stock_pcs").html('<?//= //number_format($str_sp_vmi_stock_sum_pcs_qty);?>');
    $("#spn_db_terminal_TSESA_stock_pack").html('<?//= //number_format($str_sp_vmi_stock_count_pack_qty);?>');
    */

    //bom mst
    $("#spn_db_bom_active").html('<?= number_format($str_sp_bom_active); ?>');
    $("#spn_db_bom_inactive").html('<?= number_format($str_sp_bom_inactive); ?>');

</script>
<?
sqlsrv_close($db_con);
?>