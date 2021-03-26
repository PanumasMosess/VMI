<?
require_once("../../application.php");

/**********************************************************************************/
/*current user ********************************************************************/
$t_cur_user_code_VMI_GDJ = isset($_SESSION['t_cur_user_code_VMI_GDJ']) ? $_SESSION['t_cur_user_code_VMI_GDJ'] : '';
$t_cur_user_type_VMI_GDJ = isset($_SESSION['t_cur_user_type_VMI_GDJ']) ? $_SESSION['t_cur_user_type_VMI_GDJ'] : '';
$project = isset($_POST['project_']) ? $_POST['project_'] : '';
$date_start = isset($_POST['start_']) ? $_POST['start_'] : '';
$date_end = isset($_POST['end_']) ? $_POST['end_'] : '';

if($project  == 'ALL'){
    $project = '';
}

//get replenishment price
$objQuery_sp_replenishment_price = sqlsrv_query($db_con, " EXEC sp_db_wms_replenish_price_sum 'null', '$project', '$date_start','$date_end' ");
$objResult_sp_replenishment_price = sqlsrv_fetch_array($objQuery_sp_replenishment_price, SQLSRV_FETCH_ASSOC);

//check null price
if($objResult_sp_replenishment_price['sumprice'] == NULL){ $str_sp_replenishment_price = '0'; } else { $str_sp_replenishment_price = $objResult_sp_replenishment_price['sumprice']; }
$replenishment_price = number_format($str_sp_replenishment_price,2);


//get wms picking price sum
$objQuery_sp_picking_price_sum = sqlsrv_query($db_con, " EXEC sp_db_wms_picking_price_sum 'Picking', '$project', '$date_start', '$date_end' ");
$objResult_sp_picking_price_sum = sqlsrv_fetch_array($objQuery_sp_picking_price_sum, SQLSRV_FETCH_ASSOC);

//check null 
if($objResult_sp_picking_price_sum['Sum_Price_Picking'] == NULL){ $str_sp_c_total_picking_price = '0'; } else { $str_sp_c_total_picking_price = $objResult_sp_picking_price_sum['Sum_Price_Picking']; }
$picking_price = number_format($str_sp_c_total_picking_price,2);


//get wms picking confirm price
$objQuery_sp_picking_confirm_price = sqlsrv_query($db_con, " EXEC sp_db_wms_confirm_order_price_sum 'Picking', 'Completed', '$project', '$date_start', '$date_end' ");
$objResult_sp_picking_confirm_price = sqlsrv_fetch_array($objQuery_sp_picking_confirm_price, SQLSRV_FETCH_ASSOC);

//check null
if($objResult_sp_picking_confirm_price['CounterPickingConfirmPrice'] == NULL){ $str_sp_c_total_picking_confirm_price = '0'; } else { $str_sp_c_total_picking_confirm_price = $objResult_sp_picking_confirm_price['CounterPickingConfirmPrice']; }
$confirm_price = number_format($str_sp_c_total_picking_confirm_price,2);


//get DTN Price
$objQuery_sp_dtn_price = sqlsrv_query($db_con, " EXEC sp_db_wms_dtn_price_sum 'Delivery Transfer Note', '$project', '$date_start', '$date_end' ");
$objResult_sp_dtn_price = sqlsrv_fetch_array($objQuery_sp_dtn_price, SQLSRV_FETCH_ASSOC);

//check null
if($objResult_sp_dtn_price['DTNPrice'] == NULL){ $str_sp_c_total_dtn_price = '0'; } else { $str_sp_c_total_dtn_price = $objResult_sp_dtn_price['DTNPrice']; }
$dtn_price = number_format($str_sp_c_total_dtn_price,2);

//get stock replenishment price
$objQuery_sp_stock_repn_price = sqlsrv_query($db_con, " EXEC sp_db_wms_stock_replenish_price_sum 'Confirmed','$project', '$date_start', '$date_end' ");
$objResult_sp_stock_repn_price = sqlsrv_fetch_array($objQuery_sp_stock_repn_price, SQLSRV_FETCH_ASSOC);

//check null
if($objResult_sp_stock_repn_price['sum_repi_'] == NULL){ $str_sp_c_total_stock_repn_price = '0'; } else { $str_sp_c_total_stock_repn_price = $objResult_sp_stock_repn_price['sum_repi_']; }
$repn_price = number_format($str_sp_c_total_stock_repn_price,2);

//get usage confirm price
$objQuery_sp_usage_conf_price = sqlsrv_query($db_con, " EXEC sp_db_wms_usage_conf_price 'USAGE CONFIRM','$project', '$date_start', '$date_end' ");
$objResult_sp_usage_conf_price = sqlsrv_fetch_array($objQuery_sp_usage_conf_price, SQLSRV_FETCH_ASSOC);

//check null
if($objResult_sp_usage_conf_price['sum_usage_'] == NULL){ $str_sp_c_total_usage_conf_price = '0'; } else { $str_sp_c_total_usage_conf_price = $objResult_sp_usage_conf_price['sum_usage_']; }
$use_con_price = number_format($str_sp_c_total_usage_conf_price,2);



/////////////////////////sum price per day ///////////////
/////////////////////////////////////////////////////////
/////////////////////////////////////////////////////////
//get replenishment price
$objQuery_sp_replenishment_price_day = sqlsrv_query($db_con, " EXEC sp_db_wms_replenish_price_sum_day 'null', '$project' ");
$objResult_sp_replenishment_price_day = sqlsrv_fetch_array($objQuery_sp_replenishment_price_day, SQLSRV_FETCH_ASSOC);

//check null price
if($objResult_sp_replenishment_price_day['sumprice'] == NULL){ $str_sp_replenishment_price_day = '0'; } else { $str_sp_replenishment_price_day = $objResult_sp_replenishment_price_day['sumprice']; }
$replenishment_price_day = number_format($str_sp_replenishment_price_day,2);

//get wms picking price sum
$objQuery_sp_picking_price_sum_day = sqlsrv_query($db_con, " EXEC sp_db_wms_picking_price_sum_day 'Picking', '$project' ");
$objResult_sp_picking_price_sum_day = sqlsrv_fetch_array($objQuery_sp_picking_price_sum_day, SQLSRV_FETCH_ASSOC);

//check null 
if($objResult_sp_picking_price_sum_day['Sum_Price_Picking'] == NULL){ $str_sp_c_total_picking_price_day = '0'; } else { $str_sp_c_total_picking_price_day = $objResult_sp_picking_price_sum_day['Sum_Price_Picking']; }
$picking_price_day = number_format($str_sp_c_total_picking_price_day,2);

// //get wms picking confirm price day
$objQuery_sp_picking_confirm_price_day = sqlsrv_query($db_con, " EXEC sp_db_wms_confirm_order_price_sum_day 'Picking', 'Completed', '$project' ");
$objResult_sp_picking_confirm_price_day = sqlsrv_fetch_array($objQuery_sp_picking_confirm_price_day, SQLSRV_FETCH_ASSOC);

// //check null price day
if($objResult_sp_picking_confirm_price_day['CounterPickingConfirmPrice'] == NULL){ $str_sp_c_total_picking_confirm_price_day = '0'; } else { $str_sp_c_total_picking_confirm_price_day = $objResult_sp_picking_confirm_price_day['CounterPickingConfirmPrice']; }
$confirm_price_day = number_format($str_sp_c_total_picking_confirm_price_day,2);

//get DTN Price
$objQuery_sp_dtn_price_day = sqlsrv_query($db_con, " EXEC sp_db_wms_dtn_price_sum_day 'Delivery Transfer Note', '$project' ");
$objResult_sp_dtn_price_day = sqlsrv_fetch_array($objQuery_sp_dtn_price_day, SQLSRV_FETCH_ASSOC);

//check null
if($objResult_sp_dtn_price_day['DTNPrice'] == NULL){ $str_sp_c_total_dtn_price_day = '0'; } else { $str_sp_c_total_dtn_price_day = $objResult_sp_dtn_price_day['DTNPrice']; }
$dtn_price_day = number_format($str_sp_c_total_dtn_price_day,2);

//get stock replenishment price
$objQuery_sp_stock_repn_price_day = sqlsrv_query($db_con, " EXEC sp_db_wms_stock_replenish_price_sum_day 'Confirmed','$project' ");
$objResult_sp_stock_repn_price_day = sqlsrv_fetch_array($objQuery_sp_stock_repn_price_day, SQLSRV_FETCH_ASSOC);

//check null
if($objResult_sp_stock_repn_price_day['sum_repi_'] == NULL){ $str_sp_c_total_stock_repn_price_day = '0'; } else { $str_sp_c_total_stock_repn_price_day = $objResult_sp_stock_repn_price_day['sum_repi_']; }
$repn_price_day = number_format($str_sp_c_total_stock_repn_price_day,2);

//get usage confirm price
$objQuery_sp_usage_conf_price_day = sqlsrv_query($db_con, " EXEC sp_db_wms_usage_conf_price_day 'USAGE CONFIRM','$project' ");
$objResult_sp_usage_conf_price_day = sqlsrv_fetch_array($objQuery_sp_usage_conf_price_day, SQLSRV_FETCH_ASSOC);

//check null
if($objResult_sp_usage_conf_price_day['sum_usage_'] == NULL){ $str_sp_c_total_usage_conf_price_day = '0'; } else { $str_sp_c_total_usage_conf_price_day = $objResult_sp_usage_conf_price_day['sum_usage_']; }
$use_con_price_day = number_format($str_sp_c_total_usage_conf_price_day,2);



/////////////////////////sum price per weekly ///////////////
/////////////////////////////////////////////////////////
/////////////////////////////////////////////////////////
//get replenishment price
$objQuery_sp_replenishment_price_week = sqlsrv_query($db_con, " EXEC sp_db_wms_replenish_price_sum_week 'null', '$project' ");
$objResult_sp_replenishment_price_week = sqlsrv_fetch_array($objQuery_sp_replenishment_price_week, SQLSRV_FETCH_ASSOC);

//check null price
if($objResult_sp_replenishment_price_week['sumprice'] == NULL){ $str_sp_replenishment_price_week = '0'; } else { $str_sp_replenishment_price_week = $objResult_sp_replenishment_price_week['sumprice']; }
$replenishment_price_week = number_format($str_sp_replenishment_price_week,2);

//get wms picking price sum
$objQuery_sp_picking_price_sum_week = sqlsrv_query($db_con, " EXEC sp_db_wms_picking_price_sum_week 'Picking', '$project' ");
$objResult_sp_picking_price_sum_week = sqlsrv_fetch_array($objQuery_sp_picking_price_sum_week, SQLSRV_FETCH_ASSOC);

//check null 
if($objResult_sp_picking_price_sum_week['Sum_Price_Picking'] == NULL){ $str_sp_c_total_picking_price_week = '0'; } else { $str_sp_c_total_picking_price_week = $objResult_sp_picking_price_sum_week['Sum_Price_Picking']; }
$picking_price_week = number_format($str_sp_c_total_picking_price_week,2);

// //get wms picking confirm price week
$objQuery_sp_picking_confirm_price_week = sqlsrv_query($db_con, " EXEC sp_db_wms_confirm_order_price_sum_week 'Picking', 'Completed', '$project' ");
$objResult_sp_picking_confirm_price_week = sqlsrv_fetch_array($objQuery_sp_picking_confirm_price_week, SQLSRV_FETCH_ASSOC);

// //check null price week
if($objResult_sp_picking_confirm_price_week['CounterPickingConfirmPrice'] == NULL){ $str_sp_c_total_picking_confirm_price_week = '0'; } else { $str_sp_c_total_picking_confirm_price_week = $objResult_sp_picking_confirm_price_week['CounterPickingConfirmPrice']; }
$confirm_price_week = number_format($str_sp_c_total_picking_confirm_price_week,2);

//get DTN Price
$objQuery_sp_dtn_price_week = sqlsrv_query($db_con, " EXEC sp_db_wms_dtn_price_sum_week 'Delivery Transfer Note', '$project' ");
$objResult_sp_dtn_price_week = sqlsrv_fetch_array($objQuery_sp_dtn_price_week, SQLSRV_FETCH_ASSOC);

//check null
if($objResult_sp_dtn_price_week['DTNPrice'] == NULL){ $str_sp_c_total_dtn_price_week = '0'; } else { $str_sp_c_total_dtn_price_week = $objResult_sp_dtn_price_week['DTNPrice']; }
$dtn_price_week = number_format($str_sp_c_total_dtn_price_week,2);

//get stock replenishment price
$objQuery_sp_stock_repn_price_week = sqlsrv_query($db_con, " EXEC sp_db_wms_stock_replenish_price_sum_week 'Confirmed','$project' ");
$objResult_sp_stock_repn_price_week = sqlsrv_fetch_array($objQuery_sp_stock_repn_price_week, SQLSRV_FETCH_ASSOC);

//check null
if($objResult_sp_stock_repn_price_week['sum_repi_'] == NULL){ $str_sp_c_total_stock_repn_price_week = '0'; } else { $str_sp_c_total_stock_repn_price_week = $objResult_sp_stock_repn_price_week['sum_repi_']; }
$repn_price_week = number_format($str_sp_c_total_stock_repn_price_week,2);

//get usage confirm price
$objQuery_sp_usage_conf_price_week = sqlsrv_query($db_con, " EXEC sp_db_wms_usage_conf_price_week 'USAGE CONFIRM','$project' ");
$objResult_sp_usage_conf_price_week = sqlsrv_fetch_array($objQuery_sp_usage_conf_price_week, SQLSRV_FETCH_ASSOC);

//check null
if($objResult_sp_usage_conf_price_week['sum_usage_'] == NULL){ $str_sp_c_total_usage_conf_price_week = '0'; } else { $str_sp_c_total_usage_conf_price_week = $objResult_sp_usage_conf_price_week['sum_usage_']; }
$use_con_price_week = number_format($str_sp_c_total_usage_conf_price_week,2);



/////////////////////////sum price per Month ///////////////
/////////////////////////////////////////////////////////
/////////////////////////////////////////////////////////
//get replenishment price
$objQuery_sp_replenishment_price_month = sqlsrv_query($db_con, " EXEC sp_db_wms_replenish_price_sum_month 'null', '$project' ");
$objResult_sp_replenishment_price_month = sqlsrv_fetch_array($objQuery_sp_replenishment_price_month, SQLSRV_FETCH_ASSOC);

//check null price
if($objResult_sp_replenishment_price_month['sumprice'] == NULL){ $str_sp_replenishment_price_month = '0'; } else { $str_sp_replenishment_price_month = $objResult_sp_replenishment_price_month['sumprice']; }
$replenishment_price_month = number_format($str_sp_replenishment_price_month,2);

//get wms picking price sum
$objQuery_sp_picking_price_sum_month = sqlsrv_query($db_con, " EXEC sp_db_wms_picking_price_sum_month 'Picking', '$project' ");
$objResult_sp_picking_price_sum_month = sqlsrv_fetch_array($objQuery_sp_picking_price_sum_month, SQLSRV_FETCH_ASSOC);

//check null 
if($objResult_sp_picking_price_sum_month['Sum_Price_Picking'] == NULL){ $str_sp_c_total_picking_price_month = '0'; } else { $str_sp_c_total_picking_price_month = $objResult_sp_picking_price_sum_month['Sum_Price_Picking']; }
$picking_price_month = number_format($str_sp_c_total_picking_price_month,2);

// //get wms picking confirm price
$objQuery_sp_picking_confirm_price_month = sqlsrv_query($db_con, " EXEC sp_db_wms_confirm_order_price_sum_month 'Picking', 'Completed', '$project' ");
$objResult_sp_picking_confirm_price_month = sqlsrv_fetch_array($objQuery_sp_picking_confirm_price_month, SQLSRV_FETCH_ASSOC);

// //check null price 
if($objResult_sp_picking_confirm_price_month['CounterPickingConfirmPrice'] == NULL){ $str_sp_c_total_picking_confirm_price_month = '0'; } else { $str_sp_c_total_picking_confirm_price_month = $objResult_sp_picking_confirm_price_month['CounterPickingConfirmPrice']; }
$confirm_price_month = number_format($str_sp_c_total_picking_confirm_price_month,2);

//get DTN Price
$objQuery_sp_dtn_price_month = sqlsrv_query($db_con, " EXEC sp_db_wms_dtn_price_sum_month 'Delivery Transfer Note', '$project' ");
$objResult_sp_dtn_price_month = sqlsrv_fetch_array($objQuery_sp_dtn_price_month, SQLSRV_FETCH_ASSOC);

//check null
if($objResult_sp_dtn_price_month['DTNPrice'] == NULL){ $str_sp_c_total_dtn_price_month = '0'; } else { $str_sp_c_total_dtn_price_month = $objResult_sp_dtn_price_month['DTNPrice']; }
$dtn_price_month = number_format($str_sp_c_total_dtn_price_month,2);

//get stock replenishment price
$objQuery_sp_stock_repn_price_month = sqlsrv_query($db_con, " EXEC sp_db_wms_stock_replenish_price_sum_month 'Confirmed','$project' ");
$objResult_sp_stock_repn_price_month = sqlsrv_fetch_array($objQuery_sp_stock_repn_price_month, SQLSRV_FETCH_ASSOC);

//check null
if($objResult_sp_stock_repn_price_month['sum_repi_'] == NULL){ $str_sp_c_total_stock_repn_price_month = '0'; } else { $str_sp_c_total_stock_repn_price_month = $objResult_sp_stock_repn_price_month['sum_repi_']; }
$repn_price_month = number_format($str_sp_c_total_stock_repn_price_month,2);

//get usage confirm price
$objQuery_sp_usage_conf_price_month = sqlsrv_query($db_con, " EXEC sp_db_wms_usage_conf_price_month 'USAGE CONFIRM','$project' ");
$objResult_sp_usage_conf_price_month = sqlsrv_fetch_array($objQuery_sp_usage_conf_price_month, SQLSRV_FETCH_ASSOC);

//check null
if($objResult_sp_usage_conf_price_month['sum_usage_'] == NULL){ $str_sp_c_total_usage_conf_price_month = '0'; } else { $str_sp_c_total_usage_conf_price_month = $objResult_sp_usage_conf_price_month['sum_usage_']; }
$use_con_price_month = number_format($str_sp_c_total_usage_conf_price_month,2);


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
    //print delivery order 
    $("#spn_db_total_dtn_price").html('<?= $dtn_price . "฿"; ?>');  
    //stock replenishment
    $("#spn_db_total_stock_repnish_price").html('<?= $repn_price . "฿"; ?>');
    //usage confirm price
    $("#spn_db_total_tags_usage_conf_price").html('<?= $use_con_price . "฿"; ?>');


    //replenishment price
    $("#spn_db_replenishment_price_day").html('<?= $replenishment_price_day . "฿"; ?>');
    //picking price sum
    $("#spn_db_total_picking_price_day").html('<?= $picking_price_day . "฿"; ?>');
    //picking confirm price sum
    $("#spn_db_total_picking_confirm_price_day").html('<?= $confirm_price_day . "฿"; ?>');
    //print delivery order price
    $("#spn_db_total_dtn_price_day").html('<?= $dtn_price_day . "฿"; ?>');
    //stock replenishment
    $("#spn_db_total_stock_repnish_price_day").html('<?= $repn_price_day . "฿"; ?>');
    //usage confirm price
    $("#spn_db_total_tags_usage_conf_price_day").html('<?= $use_con_price_day . "฿"; ?>');


    //replenishment price
    $("#spn_db_replenishment_price_week").html('<?= $replenishment_price_week . "฿"; ?>');
    //picking price sum
    $("#spn_db_total_picking_price_week").html('<?= $picking_price_week . "฿"; ?>');
    //picking confirm price sum
    $("#spn_db_total_picking_confirm_price_week").html('<?= $confirm_price_week . "฿"; ?>');
    //print delivery order price
    $("#spn_db_total_dtn_price_week").html('<?= $dtn_price_week . "฿"; ?>');
    //stock replenishment
    $("#spn_db_total_stock_repnish_price_week").html('<?= $repn_price_week . "฿"; ?>');
    //usage confirm price
    $("#spn_db_total_tags_usage_conf_price_week").html('<?= $use_con_price_week . "฿"; ?>');


    //replenishment price
    $("#spn_db_replenishment_price_month").html('<?= $replenishment_price_month . "฿"; ?>');
    //picking price sum
    $("#spn_db_total_picking_price_month").html('<?= $picking_price_month . "฿"; ?>');
    //picking confirm price sum
    $("#spn_db_total_picking_confirm_price_month").html('<?= $confirm_price_month . "฿"; ?>');
    //print delivery order price
    $("#spn_db_total_dtn_price_month").html('<?= $dtn_price_month . "฿"; ?>');
    //stock replenishment
    $("#spn_db_total_stock_repnish_price_month").html('<?= $repn_price_month . "฿"; ?>');
    //usage confirm price
    $("#spn_db_total_tags_usage_conf_price_month").html('<?= $use_con_price_month . "฿"; ?>');


    $("#spn_time").html("<?=date("Y-m-d H:i:s")?>");


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