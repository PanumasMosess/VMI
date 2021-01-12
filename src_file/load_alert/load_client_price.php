<?
require_once("../../application.php");

/**********************************************************************************/
/*current user ********************************************************************/
$t_cur_user_code_VMI_GDJ = isset($_SESSION['t_cur_user_code_VMI_GDJ']) ? $_SESSION['t_cur_user_code_VMI_GDJ'] : '';
$t_cur_user_type_VMI_GDJ = isset($_SESSION['t_cur_user_type_VMI_GDJ']) ? $_SESSION['t_cur_user_type_VMI_GDJ'] : '';
$project = isset($_POST['project_']) ? $_POST['project_'] : '';


//get replenishment price
$objQuery_sp_replenishment_price = sqlsrv_query($db_con, " EXEC sp_db_wms_replenish_price_sum 'null', '$project' ");
$objResult_sp_replenishment_price = sqlsrv_fetch_array($objQuery_sp_replenishment_price, SQLSRV_FETCH_ASSOC);

//check null price
if($objResult_sp_replenishment_price['sumprice'] == NULL){ $str_sp_replenishment_price = '0'; } else { $str_sp_replenishment_price = $objResult_sp_replenishment_price['sumprice']; }
$replenishment_price = number_format($str_sp_replenishment_price,2);


//get wms picking price sum
$objQuery_sp_picking_price_sum = sqlsrv_query($db_con, " EXEC sp_db_wms_picking_price_sum 'Picking', '$project' ");
$objResult_sp_picking_price_sum = sqlsrv_fetch_array($objQuery_sp_picking_price_sum, SQLSRV_FETCH_ASSOC);

//check null 
if($objResult_sp_picking_price_sum['Sum_Price_Picking'] == NULL){ $str_sp_c_total_picking_price = '0'; } else { $str_sp_c_total_picking_price = $objResult_sp_picking_price_sum['Sum_Price_Picking']; }
$picking_price = number_format($str_sp_c_total_picking_price,2);


//get wms picking confirm price
$objQuery_sp_picking_confirm_price = sqlsrv_query($db_con, " EXEC sp_db_wms_confirm_order_price_sum 'Picking', 'Completed', '$project' ");
$objResult_sp_picking_confirm_price = sqlsrv_fetch_array($objQuery_sp_picking_confirm_price, SQLSRV_FETCH_ASSOC);

//check null
if($objResult_sp_picking_confirm_price['CounterPickingConfirmPrice'] == NULL){ $str_sp_c_total_picking_confirm_price = '0'; } else { $str_sp_c_total_picking_confirm_price = $objResult_sp_picking_confirm_price['CounterPickingConfirmPrice']; }
$confirm_price = number_format($str_sp_c_total_picking_confirm_price,2);

//get DTN Price
$objQuery_sp_dtn_price = sqlsrv_query($db_con, " EXEC sp_db_wms_dtn_price_sum 'Delivery Transfer Note', '$project' ");
$objResult_sp_dtn_price = sqlsrv_fetch_array($objQuery_sp_dtn_price, SQLSRV_FETCH_ASSOC);

//check null
if($objResult_sp_dtn_price['DTNPrice'] == NULL){ $str_sp_c_total_dtn_price = '0'; } else { $str_sp_c_total_dtn_price = $objResult_sp_dtn_price['DTNPrice']; }
$dtn_price = number_format($str_sp_c_total_dtn_price,2);

//get stock replenishment price
$objQuery_sp_stock_repn_price = sqlsrv_query($db_con, " EXEC sp_db_wms_stock_replenish_price_sum 'Confirmed','$project' ");
$objResult_sp_stock_repn_price = sqlsrv_fetch_array($objQuery_sp_stock_repn_price, SQLSRV_FETCH_ASSOC);

//check null
if($objResult_sp_stock_repn_price['sum_repi_'] == NULL){ $str_sp_c_total_stock_repn_price = '0'; } else { $str_sp_c_total_stock_repn_price = $objResult_sp_stock_repn_price['sum_repi_']; }
$repn_price = number_format($str_sp_c_total_stock_repn_price,2);

//get usage confirm price
$objQuery_sp_usage_conf_price = sqlsrv_query($db_con, " EXEC sp_db_wms_usage_conf_price 'USAGE CONFIRM','$project' ");
$objResult_sp_usage_conf_price = sqlsrv_fetch_array($objQuery_sp_usage_conf_price, SQLSRV_FETCH_ASSOC);

//check null
if($objResult_sp_usage_conf_price['sum_usage_'] == NULL){ $str_sp_c_total_usage_conf_price = '0'; } else { $str_sp_c_total_usage_conf_price = $objResult_sp_usage_conf_price['sum_usage_']; }
$use_con_price = number_format($str_sp_c_total_usage_conf_price,2);

?>

<script>
    //replenishment price
    $("#spn_db_replenishment_price").html('<?= $replenishment_price . "฿"; ?>');
    //picking price sum
    $("#spn_db_total_picking_price").html('<?= $picking_price . "฿"; ?>');
    //picking confirm price sum
    $("#spn_db_total_picking_confirm_price").html('<?= $confirm_price . "฿"; ?>');
    //print delivery order price
    $("#spn_db_total_dtn_price").html('<?= $dtn_price . "฿"; ?>');
    //stock replenishment
    $("#spn_db_total_stock_repnish_price").html('<?= $repn_price . "฿"; ?>');
    //usage confirm price
    $("#spn_db_total_tags_usage_conf_price").html('<?= $use_con_price . "฿"; ?>');


    function getRandomColor() {
        var letters = '0123456789ABCDEF';
        var color = '#';
        for (var i = 0; i < 6; i++) {
            color += letters[Math.floor(Math.random() * 16)];
        }
        return color;
    }

    /*
     * FULL WIDTH STATIC AREA CHART
     * -----------------
     */
    var areaData = [
        [1, <?= $str_sp_replenishment_price ?>],
        [2, <?= $str_sp_c_total_picking_price ?>],
        [3, <?= $str_sp_c_total_picking_confirm_price ?>],
        [4, <?= $str_sp_c_total_dtn_price ?>],
        [5, <?= $str_sp_c_total_stock_repn_price ?>],
        [6, <?= $str_sp_c_total_usage_conf_price ?>]
    ]


    $.plot('#price_chart_status', [areaData], {
        grid: {
            borderWidth: 0,
            hoverable: true
        },
        series: {
            shadowSize: 0.2, // Drawing is faster without shadows
            color: getRandomColor(),
            lines: {
                show: true
            },
            points: {
                show: true,
            }
        },
        yaxis: {
            show: true,
            tickFormatter: function(val, axis) {
                var bath = (val).toFixed(2).replace(/\d(?=(\d{3})+\.)/g, '$&,');
                return "<span style='font-weight: bold'>" + bath + "</span>";;
            }
        },
        xaxis: {
            show: true,
            ticks: [
                [1, 'Replenish Order'],
                [2, 'Picking Order'],
                [3, 'Confirm Order'],
                [4, 'Print Delivery Order'],
                [5, 'Stock Replenish'],
                [6, 'Usage Confirm'],
            ],
        },
        tooltip: {
            show: true,
            cssClass: "flotTip",
            content: function(label, xval, yval, flotItem) {
                var bath = (yval).toFixed(2).replace(/\d(?=(\d{3})+\.)/g, '$&,');
                return ('&nbsp;<b>' + bath + '&nbsp;฿</b>');
            },
        }
    })

    /* END AREA CHART */
</script>

<?
sqlsrv_close($db_con);
?>