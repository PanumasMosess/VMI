<?
require_once("application.php");
require_once("js_css_header.php");

//set project is setup terminal
$str_terminal = array('TSESA','TSPT','TSRA');
?>
<!DOCTYPE html>
<html>

<body class="hold-transition skin-blue sidebar-mini">
    <div class="wrapper">
  <?
	require_once("menu_blank2.php");
  ?>
        <!--------------------------->
        <!-- body  ------------------>
        <!--------------------------->
        <!-- <span id="load_client"></span> -->
        <!-- Content Wrapper. Contains page content -->
        <div class="content-wrapper">
            <!-- Content Header (Page header) -->
            <section class="content-header">
                <h1><i class="fa fa-caret-right"></i>&nbsp;Dashboard Billing</h1>
                <ol class="breadcrumb">
                    <!-- <li><a href="<?= $CFG->wwwroot; ?>/home"><i class="fa fa-home"></i>Home</a></li> -->
                    <li class="active">Dashboard</li>
                </ol>
            </section>

            <!------------------------------------------------->
            <!-- control group -------------------------------->
            <!------------------------------------------------->

            <!-- Main content -->
            <section class="content">
            <div class="row">
            <div class="box box-info">
                    <div class="box-header with-border">
                        <i class="fa fa-bar-chart"> <b>WMS Monitoring Summary</b></i><span id="load_client_price"></span>
                        <div class="pull-right">
                            <select class="form-control" name="txt_sel_pro_board" id="txt_sel_pro_board" style="width: 100%; background-color:#f4f4f4">
                                <option selected="selected" value="">All Project</option>

                                <?
										$strSQL = " SELECT [bom_pj_name] FROM tbl_bom_mst group by [bom_pj_name] order by [bom_pj_name] asc ";
										$objQuery = sqlsrv_query($db_con, $strSQL) or die ("Error Query [".$strSQL."]");
										while($objResult = sqlsrv_fetch_array($objQuery, SQLSRV_FETCH_ASSOC))
										{
						 				?>
                                <option value="<?= $objResult["bom_pj_name"]; ?>"><?= $objResult["bom_pj_name"]; ?></option>
                                <?
										}
										
						  			?>
                            </select>
                        </div>
                        <b class="pull-right" style="font-size:16px; padding: 6px 5px;">Project Select:</b>  
                    </div>
                    <div class="box-body">
                        <div class="row">
                            <div class="col-lg-3 col-xs-6">
                                <div class="small-box bg-yellow">
                                    <div class="inner">
                                        <font style="font-size:10px;">Total Order Price</font>
                                        <h4><span id="spn_db_replenishment_price"></span></h4>
                                        <p>Replenishment Order</p>
                                    </div>
                                    <div class="icon">
                                        <i class="fa fa-cart-plus"></i>
                                    </div>
                                    <!-- <a href="<?= $CFG->wwwroot; ?>/replenishment" class="small-box-footer">More <i class="fa fa-arrow-circle-right"></i></a> -->
                                </div>
                            </div>

                            <div class="col-lg-3 col-xs-6">
                                <div class="small-box bg-teal">
                                    <div class="inner">
                                        <font style="font-size:10px;">Total Picking Sheet Price</font>
                                        <h4><span id="spn_db_total_picking_price"></span></h4>
                                        <p>Picking Order</p>
                                    </div>
                                    <div class="icon">
                                        <i class="fa fa-files-o"></i>
                                    </div>
                                    <!-- <a href="<?= $CFG->wwwroot; ?>/picking" class="small-box-footer">More <i class="fa fa-arrow-circle-right"></i></a> -->
                                </div>
                            </div>

                            <div class="col-lg-3 col-xs-6">
                                <div class="small-box bg-green">
                                    <div class="inner">
                                        <font style="font-size:10px;">Total Confirm Order Price</font>
                                        <h4><span id="spn_db_total_picking_confirm_price"></span></h4>

                                        <p>Confirm Order</p>
                                    </div>
                                    <div class="icon">
                                        <i class="fa fa-check-square-o"></i>
                                    </div>
                                    <!-- <a href="<?= $CFG->wwwroot; ?>/dtn_order" class="small-box-footer">More <i class="fa fa-arrow-circle-right"></i></a> -->
                                </div>
                            </div>

                            <div class="col-lg-3 col-xs-6">
                                <div class="small-box bg-aqua">
                                    <div class="inner">
                                        <font style="font-size:10px;">Total DTN Price</font>
                                        <h4><span id="spn_db_total_dtn_price"></span></h4>

                                        <p>Print Delivery Order</p>
                                    </div>
                                    <div class="icon">
                                        <img src="<?= $CFG->iconsdir; ?>/truck.png" height="50px">
                                    </div>
                                    <!-- <a href="<?= $CFG->wwwroot; ?>/dtn_order" class="small-box-footer">More <i class="fa fa-arrow-circle-right"></i></a> -->
                                </div>
                            </div>
                        </div>

                        <div class="box-header with-border">
                            <i class="fa fa-bar-chart"> <b>Terminal Monitoring Summary</b></i>
                        </div>
                        <div class="row">
                            <div class="col-lg-3 col-xs-6">
                                <div class="small-box bg-gray">
                                    <div class="inner">
                                        <font style="font-size:10px;">Total Price (Tags)</font>
                                        <h4><span id="spn_db_total_stock_repnish_price"></span></h4>
                                        <p>Stock Replenishment</p>
                                    </div>
                                    <div class="icon">
                                        <i class="fa fa-cart-arrow-down"></i>
                                    </div>
                                    <!-- <a href="<?= $CFG->wwwroot; ?>/stock_replenishment" class="small-box-footer">More <i class="fa fa-arrow-circle-right"></i></a> -->
                                </div>
                            </div>

                            <div class="col-lg-3 col-xs-6">
                                <div class="small-box bg-blue">
                                    <div class="inner">
                                        <font style="font-size:10px;">Total Price (Tags)</font>
                                        <h4><span id="spn_db_total_tags_usage_conf_price"></span></h4>

                                        <p>Usage Confirm</p>
                                    </div>
                                    <div class="icon">
                                        <i class="fa fa-cubes"></i>
                                    </div>
                                    <!-- <a href="<?= $CFG->wwwroot; ?>/usage_confirm" class="small-box-footer">More <i class="fa fa-arrow-circle-right"></i></a> -->
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

                <div class="row">
                    <div class="col-md-12">
                        <!-- Area chart -->
                        <div class="box box-primary">
                            <div class="box-header with-border">
                                <i class="fa fa-bar-chart-o"></i>
                                <h3 class="box-title"><b> Price/Status</b></h3>
                                <div class="box-tools pull-right">
                                    <button type="button" class="btn btn-box-tool" data-widget="collapse"><i class="fa fa-minus"></i>
                                    </button>
                                    <button type="button" class="btn btn-box-tool" data-widget="remove"><i class="fa fa-times"></i></button>
                                </div>
                            </div>
                            <div class="box-body">
                                <div id="price_chart_status" style="height: 400px;"></div>
                            </div>
                            <!-- /.box-body-->
                        </div>
                        <!-- /.box -->
                    </div>
                </div>

                <div class="box box-info">
                    <div class="box-header with-border">
                        <h3 class="box-title"><i class="fa fa-bar-chart"> <b>Billing Monitoring</b></i></h3>
                        <button type="button" class="btn btn-default pull-right" id="daterange-btn">
                            <span>
                                <i class="fa fa-calendar"></i> Date Range
                            </span>
                            <i class="fa fa-caret-down"></i>
                        </button>
                        <b class="pull-right" style="font-size:16px; padding: 6px 10px;">Date Select:</b>  
                    </div>
                    <div class="box-body">
                        <div class="row">
                            <span id="spn_wms_price_rang"></span>
                        </div>
                    </div>
                </div>

                <!-- graph -->
                <div class="row">
                    <div class="col-md-6">
                        <!-- Bar chart -->
                        <div class="box box-primary">
                            <div class="box-header with-border">
                                <i class="fa fa-bar-chart-o"></i>

                                <h3 class="box-title"> <b>Price/Project </b></h3>

                                <div class="box-tools pull-right">
                                    <button type="button" class="btn btn-box-tool" data-widget="collapse"><i class="fa fa-minus"></i>
                                    </button>
                                    <button type="button" class="btn btn-box-tool" data-widget="remove"><i class="fa fa-times"></i></button>
                                </div>
                            </div>
                            <div class="box-body">
                                <div id="bar-chart" style="height: 300px;"></div>
                            </div>
                            <!-- /.box-body-->
                        </div>
                    </div>
                    <!-- /.box -->

                    <!-- Area chart -->
                    <div class="col-md-6">
                        <div class="box box-primary">
                            <div class="box-header with-border">
                                <i class="fa fa-bar-chart-o"></i>

                                <h3 class="box-title"><b>Ratio Bill Stock</b></h3>

                                <div class="box-tools pull-right">
                                    <button type="button" class="btn btn-box-tool" data-widget="collapse"><i class="fa fa-minus"></i>
                                    </button>
                                    <button type="button" class="btn btn-box-tool" data-widget="remove"><i class="fa fa-times"></i></button>
                                </div>
                            </div>
                            <div class="box-body">
                                <div id="donut-chart" style="height: 300px;"></div>
                            </div>
                            <!-- /.box-body-->
                        </div>
                        <!-- /.box -->
                    </div>
                </div>
                <!-- /.row -->

                <div class="row">
                    <div class="col-md-8">
                        <span id="spn_load_data_latest"></span>
                    </div>

                    <div class="col-md-4">
                        <span id="span_load_data_recently"></span>
                    </div>
                </div>

                <!--row-->
                <!-- require_once dashboard.php -->
        <?
		  require_once("dashboard.php");
		?>
                <!-- /.row -->

            </section>
            <!-- /.content -->

        </div>
        <!-- /.content-wrapper -->
        <!--------------------------->
        <!-- /.body -->
        <!--------------------------->
        <?
	require_once("footer.php");
  ?>

        <!-- Add the sidebar's background. This div must be placed immediately after the control sidebar -->
        <div class="control-sidebar-bg"></div>
    </div>
    <!-- ./wrapper -->
    </div>
    <? 
require_once("js_css_footer.php"); 
?>
    <!-- float.js -->
    <script src="<?= $CFG->wwwroot; ?>/bower_components/Flot/jquery.flot.js"></script>
    <!-- FLOT RESIZE PLUGIN - allows the chart to redraw when the window is resized -->
    <script src="<?= $CFG->wwwroot; ?>/bower_components/Flot/jquery.flot.resize.js"></script>
    <!-- FLOT PIE PLUGIN - also used to draw donut charts -->
    <script src="<?= $CFG->wwwroot; ?>/bower_components/Flot/jquery.flot.pie.js"></script>
    <!-- FLOT CATEGORIES PLUGIN - Used to draw bar charts -->
    <script src="<?= $CFG->wwwroot; ?>/bower_components/Flot/jquery.flot.categories.js"></script>
    <!-- bar label -->
    <script src="<?= $CFG->wwwroot; ?>/bower_components/Flot/jquery.flot.tooltip.js"></script>


    <script language="javascript">
        var startDate;
        var endDate;
        //Date range as a button
        $('#daterange-btn').daterangepicker({
                ranges: {
                    'Today': [moment(), moment()],
                    'Yesterday': [moment().subtract(1, 'days'), moment().subtract(1, 'days')],
                    'Last 7 Days': [moment().subtract(6, 'days'), moment()],
                    'Last 30 Days': [moment().subtract(29, 'days'), moment()],
                    'This Month': [moment().startOf('month'), moment().endOf('month')],
                    'Last Month': [moment().subtract(1, 'month').startOf('month'), moment().subtract(1, 'month').endOf('month')],
                    'Last 6 Month': [moment().subtract(5, 'month').startOf('month'), moment()],
                    'This Year': [moment().subtract(11, 'month').startOf('month'), moment()],
                    'Last 1 Year': [moment().subtract(1, 'year').startOf('year'), moment().subtract(1, 'year').endOf('month')],
                },
                startDate: moment().subtract(29, 'days'),
                endDate: moment(),
            },
            function(start, end) {
                $('#daterange-btn span').html(start.format('D MMMM, YYYY') + ' - ' + end.format('D MMMM, YYYY'))
                startDate = start;
                endDate = end;
                // console.log(startDate.format('DD-MM-YYYY') + ' - ' + endDate.format('DD-MM-YYYY'));
                _load_usage_top();
                _load_recently();
                call_time(startDate.format('YYYY-MM-DD'), endDate.format('YYYY-MM-DD'));
            }
        );

        $('#txt_sel_pro_board').on('change', function() {
            var project = this.value;
            setTimeout(function() {
                $("#load_client_price").load("<?= $CFG->src_load_alert; ?>/load_client_price.php", {
                    project_: project
                });

            }, 300);
        });

        $(document).ready(function() {
            // $("#load_client").load('<?=$CFG->src_file_alert;?>/load_client.php?randval='+ Math.random());
            call_time(startDate, endDate);
            _load_usage_top();
            _load_recently();
            _load_client_price();
        });

        function call_time(startDate, endDate) {
            setTimeout(function() {
                $("#spn_wms_price_rang").load("<?= $CFG->src_load_alert; ?>/load_board_bill.php", {
                    startDate_: startDate,
                    endDate_: endDate
                });

            }, 300);
        }

        /*
         * Custom Label formatter
         * ----------------------
         */
        function labelFormatter(label, series) {
            return '<div style="font-size:13px; text-align:center; padding:2px; color: #fff; font-weight: 600;">' +
                label +
                '<br>' +
                Math.round(series.percent) + '%</div>'
        }

        function _load_usage_top() {
            //Load data
            setTimeout(function() {
                $("#spn_load_data_latest").load("<?= $CFG->src_load_alert; ?>/load_board_last_ten.php");
            }, 300);
        }

        function _load_recently() {
            //Load data
            setTimeout(function() {
                $("#span_load_data_recently").load("<?= $CFG->src_load_alert; ?>/load_recently_products.php");
            }, 300);
        }
        function _load_client_price() {
            //Load data
            setTimeout(function() {
                $("#load_client_price").load("<?= $CFG->src_load_alert; ?>/load_client_price.php");
            }, 300);
        }
    </script>

</body>

</html>