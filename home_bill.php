<?
require_once("application.php");
require_once("get_authorized.php");
require_once("js_css_header.php");

//set project is setup terminal
$str_terminal = array('TSESA');
?>
<!DOCTYPE html>
<html>

<body class="hold-transition skin-blue sidebar-mini">
    <div class="wrapper">

        <?
	require_once("menu.php");
  ?>
        <!--------------------------->
        <!-- body  ------------------>
        <!--------------------------->
        <!-- Content Wrapper. Contains page content -->
        <div class="content-wrapper">
            <!-- Content Header (Page header) -->
            <section class="content-header">
                <h1><i class="fa fa-caret-right"></i>&nbsp;Dashboard Bill</h1>
                <ol class="breadcrumb">
                    <li><a href="<?= $CFG->wwwroot; ?>/home"><i class="fa fa-home"></i>Home</a></li>
                    <li class="active">Dashboard</li>
                </ol>
            </section>

            <!------------------------------------------------->
            <!-- control group -------------------------------->
            <!------------------------------------------------->

            <!-- Main content -->
            <section class="content">
                <!--announcement-->
                <!-- <div class="row">
			<?
		//	  require_once("announce.php");
			?>
		  </div> -->

                <?
		/*-------------------------------------------------*/
		/*-- control group --------------------------------*/
		/*-------------------------------------------------*/
		if($objResult_authorized['user_type'] == "Administrator") //Administrator
		{
		?>
                <i class="fa fa-bar-chart"> <b>WMS Monitoring Summary</b></i>
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
                            <a href="<?= $CFG->wwwroot; ?>/replenishment" class="small-box-footer">More <i class="fa fa-arrow-circle-right"></i></a>
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
                            <a href="<?= $CFG->wwwroot; ?>/picking" class="small-box-footer">More <i class="fa fa-arrow-circle-right"></i></a>
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
                            <a href="<?= $CFG->wwwroot; ?>/dtn_order" class="small-box-footer">More <i class="fa fa-arrow-circle-right"></i></a>
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
                            <a href="<?= $CFG->wwwroot; ?>/dtn_order" class="small-box-footer">More <i class="fa fa-arrow-circle-right"></i></a>
                        </div>
                    </div>
                </div>

                <i class="fa fa-bar-chart"> <b>Terminal Monitoring Summary</b></i>
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
                            <a href="<?= $CFG->wwwroot; ?>/stock_replenishment" class="small-box-footer">More <i class="fa fa-arrow-circle-right"></i></a>
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
                            <a href="<?= $CFG->wwwroot; ?>/usage_confirm" class="small-box-footer">More <i class="fa fa-arrow-circle-right"></i></a>
                        </div>
                    </div>
                </div>

                <div class="box box-info">
                    <div class="box-header with-border">
                        <h3 class="box-title"><i class="fa fa-bar-chart"> <b>Stock Monitoring</b></i></h3>
                        <button type="button" class="btn btn-default pull-right" id="daterange-btn">
                            <span>
                                <i class="fa fa-calendar"></i> Date Range Dashboard
                            </span>
                            <i class="fa fa-caret-down"></i>
                        </button>
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

                                <h3 class="box-title">Price / Project</h3>

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

                                <h3 class="box-title">Ratio Bill Stock</h3>

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
                <!-- /.col -->
                <!-- /.row -->

                <div class="row">
                    <div class="col-md-8">
                        <!-- TABLE: LATEST ORDERS -->
                        <div class="box box-info">
                            <div class="box-header with-border">
                                <h3 class="box-title">Latest Orders</h3>
                                <div class="box-tools pull-right">
                                    <button type="button" class="btn btn-box-tool" data-widget="collapse"><i class="fa fa-minus"></i>
                                    </button>
                                    <button type="button" class="btn btn-box-tool" data-widget="remove"><i class="fa fa-times"></i></button>
                                </div>
                            </div>
                            <!-- /.box-header -->
                            <div class="box-body">
                                <div class="table-responsive">
                                    <table class="table no-margin">
                                        <thead>
                                            <tr>
                                                <th>Order ID</th>
                                                <th>Item</th>
                                                <th>Status</th>
                                                <th>Popularity</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            <tr>
                                                <td><a href="pages/examples/invoice.html">OR9842</a></td>
                                                <td>Call of Duty IV</td>
                                                <td><span class="label label-success">Shipped</span></td>
                                                <td>
                                                    <div class="sparkbar" data-color="#00a65a" data-height="20">90,80,90,-70,61,-83,63</div>
                                                </td>
                                            </tr>
                                            <tr>
                                                <td><a href="pages/examples/invoice.html">OR1848</a></td>
                                                <td>Samsung Smart TV</td>
                                                <td><span class="label label-warning">Pending</span></td>
                                                <td>
                                                    <div class="sparkbar" data-color="#f39c12" data-height="20">90,80,-90,70,61,-83,68</div>
                                                </td>
                                            </tr>
                                            <tr>
                                                <td><a href="pages/examples/invoice.html">OR7429</a></td>
                                                <td>iPhone 6 Plus</td>
                                                <td><span class="label label-danger">Delivered</span></td>
                                                <td>
                                                    <div class="sparkbar" data-color="#f56954" data-height="20">90,-80,90,70,-61,83,63</div>
                                                </td>
                                            </tr>
                                            <tr>
                                                <td><a href="pages/examples/invoice.html">OR7429</a></td>
                                                <td>Samsung Smart TV</td>
                                                <td><span class="label label-info">Processing</span></td>
                                                <td>
                                                    <div class="sparkbar" data-color="#00c0ef" data-height="20">90,80,-90,70,-61,83,63</div>
                                                </td>
                                            </tr>
                                            <tr>
                                                <td><a href="pages/examples/invoice.html">OR1848</a></td>
                                                <td>Samsung Smart TV</td>
                                                <td><span class="label label-warning">Pending</span></td>
                                                <td>
                                                    <div class="sparkbar" data-color="#f39c12" data-height="20">90,80,-90,70,61,-83,68</div>
                                                </td>
                                            </tr>
                                            <tr>
                                                <td><a href="pages/examples/invoice.html">OR7429</a></td>
                                                <td>iPhone 6 Plus</td>
                                                <td><span class="label label-danger">Delivered</span></td>
                                                <td>
                                                    <div class="sparkbar" data-color="#f56954" data-height="20">90,-80,90,70,-61,83,63</div>
                                                </td>
                                            </tr>
                                            <tr>
                                                <td><a href="pages/examples/invoice.html">OR9842</a></td>
                                                <td>Call of Duty IV</td>
                                                <td><span class="label label-success">Shipped</span></td>
                                                <td>
                                                    <div class="sparkbar" data-color="#00a65a" data-height="20">90,80,90,-70,61,-83,63</div>
                                                </td>
                                            </tr>
                                        </tbody>
                                    </table>
                                </div>
                                <!-- /.table-responsive -->
                            </div>
                            <!-- /.box-body -->
                            <div class="box-footer clearfix">
                                <a href="javascript:void(0)" class="btn btn-sm btn-info btn-flat pull-left">Place New Order</a>
                                <a href="javascript:void(0)" class="btn btn-sm btn-default btn-flat pull-right">View All Orders</a>
                            </div>
                            <!-- /.box-footer -->
                        </div>
                        <!-- /.box -->
                    </div>
                    <div class="col-md-4">
                        <div class="box box-info">
                            <div class="box-header with-border">
                                <h3 class="box-title">Recently Added Products</h3>

                                <div class="box-tools pull-right">
                                    <button type="button" class="btn btn-box-tool" data-widget="collapse"><i class="fa fa-minus"></i>
                                    </button>
                                    <button type="button" class="btn btn-box-tool" data-widget="remove"><i class="fa fa-times"></i></button>
                                </div>
                            </div>
                            <!-- /.box-header -->
                            <div class="box-body">
                                <ul class="products-list product-list-in-box">
                                    <li class="item">
                                        <div class="product-img">
                                            <img src="dist/img/default-50x50.gif" alt="Product Image">
                                        </div>
                                        <div class="product-info">
                                            <a href="javascript:void(0)" class="product-title">Samsung TV
                                                <span class="label label-warning pull-right">$1800</span></a>
                                            <span class="product-description">
                                                Samsung 32" 1080p 60Hz LED Smart HDTV.
                                            </span>
                                        </div>
                                    </li>
                                    <!-- /.item -->
                                    <li class="item">
                                        <div class="product-img">
                                            <img src="dist/img/default-50x50.gif" alt="Product Image">
                                        </div>
                                        <div class="product-info">
                                            <a href="javascript:void(0)" class="product-title">Bicycle
                                                <span class="label label-info pull-right">$700</span></a>
                                            <span class="product-description">
                                                26" Mongoose Dolomite Men's 7-speed, Navy Blue.
                                            </span>
                                        </div>
                                    </li>
                                    <!-- /.item -->
                                    <li class="item">
                                        <div class="product-img">
                                            <img src="dist/img/default-50x50.gif" alt="Product Image">
                                        </div>
                                        <div class="product-info">
                                            <a href="javascript:void(0)" class="product-title">Xbox One <span class="label label-danger pull-right">$350</span></a>
                                            <span class="product-description">
                                                Xbox One Console Bundle with Halo Master Chief Collection.
                                            </span>
                                        </div>
                                    </li>
                                    <!-- /.item -->
                                    <li class="item">
                                        <div class="product-img">
                                            <img src="dist/img/default-50x50.gif" alt="Product Image">
                                        </div>
                                        <div class="product-info">
                                            <a href="javascript:void(0)" class="product-title">PlayStation 4
                                                <span class="label label-success pull-right">$399</span></a>
                                            <span class="product-description">
                                                PlayStation 4 500GB Console (PS4)
                                            </span>
                                        </div>
                                    </li>
                                    <!-- /.item -->
                                </ul>
                            </div>
                            <!-- /.box-body -->
                            <div class="box-footer text-center">
                                <a href="javascript:void(0)" class="uppercase">View All Products</a>
                            </div>
                            <!-- /.box-footer -->
                        </div>
                    </div>
               
                <?
		}
		else if($objResult_authorized['user_type'] == "Customer") //Customer
		{
		?>
                <i class="fa fa-bar-chart"> <b>Terminal Monitoring</b></i>
                <div class="row">
                    <div class="col-lg-3 col-xs-6">
                        <div class="small-box bg-gray">
                            <div class="inner">
                                <font style="font-size:10px;">Today (Tags)</font>
                                <h4><span id="spn_db_total_stock_repnish"></span></h4>

                                <p>Stock Replenishment</p>
                            </div>
                            <div class="icon">
                                <i class="fa fa-cart-arrow-down"></i>
                            </div>
                            <a href="<?= $CFG->wwwroot; ?>/stock_replenishment" class="small-box-footer">More <i class="fa fa-arrow-circle-right"></i></a>
                        </div>
                    </div>

                    <div class="col-lg-3 col-xs-6">
                        <div class="small-box bg-blue">
                            <div class="inner">
                                <font style="font-size:10px;">Today (Tags)</font>
                                <h4><span id="spn_db_total_tags_usage_conf"></span></h4>

                                <p>Usage Confirm</p>
                            </div>
                            <div class="icon">
                                <i class="fa fa-cubes"></i>
                            </div>
                            <a href="<?= $CFG->wwwroot; ?>/usage_confirm" class="small-box-footer">More <i class="fa fa-arrow-circle-right"></i></a>
                        </div>
                    </div>
                </div>

                <div class="box box-info">
                    <div class="box-header with-border">
                        <h3 class="box-title"><i class="fa fa-bar-chart"> <b>Stock Monitoring</b></i></h3>
                    </div>

                    <div class="box-body">
                        <div class="row">
                            <!--for loop get all project-->
                            <?
					$str_implode_all_PJ = _get_all_project_name($db_con);
					//explode
					$separated_all_PJ = explode(",", $str_implode_all_PJ);
					$num_all_PJ_separated = count($separated_all_PJ);
					
					foreach ($separated_all_PJ as $value_all_PJ) 
					{
						//check word
						$str_chk_pj = $value_all_PJ;
						
						//project list allow
						$array  = $str_terminal;
						$str_chk = strpos_var($str_chk_pj, $array); // will return true
						
						if($str_chk == false)
						{
							$str_word_pre_fix = "Project";
						}
						else
						{
							$str_word_pre_fix = "Terminal";
						}
					?>
                            <div class="col-lg-3 col-xs-6">
                                <div class="small-box bg-lime">
                                    <div class="inner">
                                        <font style="font-size:10px; color: #000;">Total Price</font>
                                        <h4 style="color: #000;"><?= number_format(get_vmi_stock_price($db_con, $value_all_PJ)); ?></h4>

                                        <p style="color: #000;"><?= $str_word_pre_fix; ?> <b><?= $value_all_PJ; ?></b></p>
                                    </div>
                                    <div class="icon">
                                        <i class="fa fa-bar-chart"></i>
                                    </div>
                                    <a href="<?= $CFG->wwwroot; ?>/xxxxxx" class="small-box-footer">More <i class="fa fa-arrow-circle-right"></i></a>
                                </div>
                            </div>
                            <?			
					}
					?>
                        </div>
                    </div>
                </div>
                <?
		}
		?>

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
                call_time(startDate.format('YYYY-MM-DD'), endDate.format('YYYY-MM-DD'));
            }
        );

        $(document).ready(function() {
            call_time(startDate, endDate);
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
    </script>
</body>

</html>