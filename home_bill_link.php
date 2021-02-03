<?
require_once("application.php");
require_once("js_css_header.php");

//set project is setup terminal
$str_terminal = array('TSESA','TSPT','TSRA');
$time_now = date("Y-m-d H:i:s");
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
                    <li class="active">Last updated: <span id="spn_time"></span></li>
                </ol>
            </section>

            <!------------------------------------------------->
            <!-- control group -------------------------------->
            <!------------------------------------------------->

            <!-- Main content -->
            <section class="content">
            <div class="box-body">
                <div class="row">
                    <!-- daily -->
                    <div class="box box-info">
                        <div class="box-header with-border">
                            <i class="fa fa-bar-chart"> </i><span id="load_client_price"></span><b>WMS - Monitoring Summary Daily</b>
                            <div class="pull-right">
                                <select class="form-control" style="font-size:11px;"  name="txt_sel_pro_board" id="txt_sel_pro_board" style="width: 100%; background-color:#f4f4f4">
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
                            <b class="pull-right" style="font-size:13px; padding: 6px 10px;">Project Select:</b>
                        </div>

                        <div class="box-body">
                            <div class="row">
                                <div class="col-lg-3 col-xs-6">
                                    <div class="small-box bg-yellow">
                                        <div class="inner">
                                            <font style="font-size:10px;">Total Order Price</font>
                                            <h4><span id="spn_db_replenishment_price_day"></span></h4>
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
                                            <h4><span id="spn_db_total_picking_price_day"></span></h4>
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
                                            <h4><span id="spn_db_total_picking_confirm_price_day"></span></h4>

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
                                            <h4><span id="spn_db_total_dtn_price_day"></span></h4>

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
                                <i class="fa fa-bar-chart"></i><b>Terminal - Monitoring Summary Daily</b>
                            </div>
                            <div class="row">
                                <div class="col-lg-3 col-xs-6">
                                    <div class="small-box bg-gray">
                                        <div class="inner">
                                            <font style="font-size:10px;">Total Price </font>
                                            <h4><span id="spn_db_total_stock_repnish_price_day"></span></h4>
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
                                            <font style="font-size:10px;">Total Price </font>
                                            <h4><span id="spn_db_total_tags_usage_conf_price_day"></span></h4>

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

                    <!-- weekly -->
                    <div class="box box-info">
                        <div class="box-header with-border">
                            <i class="fa fa-bar-chart"></i><span id="load_client_price"></span><b>WMS - Monitoring Summary Weekly</b>
                            <!-- <div class="pull-right">
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
                            <b class="pull-right" style="font-size:16px; padding: 6px 10px;">Project Select:</b> -->
                        </div>

                        <div class="box-body">
                            <div class="row">
                                <div class="col-lg-3 col-xs-6">
                                    <div class="small-box bg-yellow">
                                        <div class="inner">
                                            <font style="font-size:10px;">Total Order Price</font>
                                            <h4><span id="spn_db_replenishment_price_week"></span></h4>
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
                                            <h4><span id="spn_db_total_picking_price_week"></span></h4>
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
                                            <h4><span id="spn_db_total_picking_confirm_price_week"></span></h4>

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
                                            <h4><span id="spn_db_total_dtn_price_week"></span></h4>

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
                                <i class="fa fa-bar-chart"> </i><b>Terminal - Monitoring Summary Weekly</b>
                            </div>
                            <div class="row">
                                <div class="col-lg-3 col-xs-6">
                                    <div class="small-box bg-gray">
                                        <div class="inner">
                                            <font style="font-size:10px;">Total Price </font>
                                            <h4><span id="spn_db_total_stock_repnish_price_week"></span></h4>
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
                                            <font style="font-size:10px;">Total Price </font>
                                            <h4><span id="spn_db_total_tags_usage_conf_price_week"></span></h4>

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

                    <!-- month -->
                    <div class="box box-info">
                        <div class="box-header with-border">
                            <i class="fa fa-bar-chart"> </i><span id="load_client_price"></span><b>WMS - Monitoring Summary Monthly</b>
                            <!-- <div class="pull-right">
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
                            <b class="pull-right" style="font-size:16px; padding: 6px 10px;">Project Select:</b> -->
                        </div>

                        <div class="box-body">
                            <div class="row">
                                <div class="col-lg-3 col-xs-6">
                                    <div class="small-box bg-yellow">
                                        <div class="inner">
                                            <font style="font-size:10px;">Total Order Price</font>
                                            <h4><span id="spn_db_replenishment_price_month"></span></h4>
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
                                            <h4><span id="spn_db_total_picking_price_month"></span></h4>
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
                                            <h4><span id="spn_db_total_picking_confirm_price_month"></span></h4>

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
                                            <h4><span id="spn_db_total_dtn_price_month"></span></h4>

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
                                <i class="fa fa-bar-chart"> </i><b>Terminal - Monitoring Summary Monthly</b>
                            </div>
                            <div class="row">
                                <div class="col-lg-3 col-xs-6">
                                    <div class="small-box bg-gray">
                                        <div class="inner">
                                            <font style="font-size:10px;">Total Price </font>
                                            <h4><span id="spn_db_total_stock_repnish_price_month"></span></h4>
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
                                            <font style="font-size:10px;">Total Price </font>
                                            <h4><span id="spn_db_total_tags_usage_conf_price_month"></span></h4>

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
                    
                    <div class="box box-info">
                        <div class="box-header with-border">
                            <i class="fa fa-bar-chart"> </i><span id="load_client_price"></span><b>WMS - Price Flow Summary Overall  by Process Station</b>
                            <div class="pull-right">
                            <b style="font-size:11px; padding: 6px 10px;">Date Select:</b>
                            <button type="button" class="btn btn-default" style="font-size:11px;" id="daterange-btn-last-5">
                                    <span>
                                        <i class="fa fa-calendar"></i>
                                    </span>
                                    <i class="fa fa-caret-down"></i>
                            </button>
                            </div>
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
                                <i class="fa fa-bar-chart"> </i><b>Terminal - Price Flow Summary Overall  by Process Station</b>
                            </div>
                            <div class="row">
                                <div class="col-lg-3 col-xs-6">
                                    <div class="small-box bg-gray">
                                        <div class="inner">
                                            <font style="font-size:10px;">Total Price </font>
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
                                            <font style="font-size:10px;">Total Price </font>
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
                </div>

                <div class="row">
                    <div class="col-md-12">
                        <!-- Area chart -->
                        <div class="box box-primary">
                            <div class="box-header with-border">
                                <i class="fa fa-bar-chart-o"></i>
                                <h3 class="box-title"></h3><b>Price Flow Summary Overall  by Process Station</b>
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
                        <h3 class="box-title"><i class="fa fa-bar-chart"></i></h3><b> Billing Summary Usage confirm by Project</b>
                        <div class="pull-right">
                        <b  style="font-size:11px; padding: 6px 6px;">Date Select:</b>
                        <button type="button" class="btn btn-default" style="font-size:11px;" id="daterange-btn">
                            <span>
                                <i class="fa fa-calendar"></i> Date Range
                            </span>
                            <i class="fa fa-caret-down"></i>
                        </button>
                        </div>
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

                                <h3 class="box-title"></h3><b>Billing Summary Ratio by Bar Chart</b>

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

                                <h3 class="box-title"></h3><b>Billing Summary Ratio by Pie Chart</b>

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

        $('#daterange-btn span').html(moment().startOf('month').format('D MMMM, YYYY') + ' - ' + moment().endOf('month').format('D MMMM, YYYY'));
        $('#daterange-btn-last-5 span').html(moment().startOf('year').format('D MMMM, YYYY') + ' - ' + moment().endOf('year').format('D MMMM, YYYY'));

        //Date range as a button
        $('#daterange-btn').daterangepicker({
                ranges: {
                    // 'Today': [moment(), moment()],
                    // 'Yesterday': [moment().subtract(1, 'days'), moment().subtract(1, 'days')],
                    // 'Last 7 Days': [moment().subtract(6, 'days'), moment()],
                    // 'Last 30 Days': [moment().subtract(29, 'days'), moment()],
                    'This Month': [moment().startOf('month'), moment().endOf('month')],
                    'Last Month': [moment().subtract(1, 'month').startOf('month'), moment().subtract(1, 'month').endOf('month')],
                    'Last 6 Month': [moment().subtract(5, 'month').startOf('month'), moment()],
                    'This Year': [moment().startOf('year'), moment().endOf('year')],
                    'Last 1 Year': [moment().subtract(1, 'year').startOf('year'), moment().subtract(1, 'year').endOf('year')],
                },
                startDate: moment().startOf('month'),
                endDate: moment().endOf('month'),
            },
            function(start, end) {
                $('#daterange-btn span').html(start.format('D MMMM, YYYY') + ' - ' + end.format('D MMMM, YYYY'))
                startDate = start;
                endDate = end;
                // console.log(startDate.format('DD-MM-YYYY') + ' - ' + endDate.format('DD-MM-YYYY'));
                // _load_usage_top();
                // _load_recently();
                call_time(startDate.format('YYYY-MM-DD'), endDate.format('YYYY-MM-DD'));
            }
        );

        //Date range as a button
        $('#daterange-btn-last-5').daterangepicker({
                showCustomRangeLabel: false,
                ranges: {
                    'This Year': [moment().startOf('year'), moment().endOf('year')],
                    'Last 2 Year': [moment().subtract(2, 'year').startOf('year'), moment()],
                    'Last 3 Year': [moment().subtract(3, 'year').startOf('year'), moment()],
                    'Last 4 Year': [moment().subtract(4, 'year').startOf('year'), moment()],
                    'Last 5 Year': [moment().subtract(5, 'year').startOf('year'), moment()],
                },
                startDate: moment().startOf('year'),
                endDate: moment().endOf('year'),
            },
            function(start, end) {
                $('#daterange-btn-last-5 span').html(start.format('D MMMM, YYYY') + ' - ' + end.format('D MMMM, YYYY'))
                startDate = start;
                endDate = end;
                // console.log(startDate.format('DD-MM-YYYY') + ' - ' + endDate.format('DD-MM-YYYY'));
                // _load_usage_top();
                // _load_recently();
                call_time_5(startDate.format('YYYY-MM-DD'), endDate.format('YYYY-MM-DD'));
            }
        );

        $(document).ready(function() {
            call_time(moment().startOf('month').format('YYYY-MM-DD'), moment().endOf('month').format('YYYY-MM-DD'));
            _load_usage_top();
            _load_recently();
            _load_client_price();
            $("#spn_time").html("<?= $time_now; ?>");
        });

        function call_time(startDate, endDate) {
            setTimeout(function() {
                $("#spn_wms_price_rang").load("<?= $CFG->src_load_alert; ?>/load_board_bill.php", {
                    startDate_: startDate,
                    endDate_: endDate
                });

            }, 300);
        }

        function call_time_5(startDate, endDate) {
            setTimeout(function() {
                $("#load_client_price").load("<?= $CFG->src_load_alert; ?>/load_client_price.php", {
                    project_ : $('#txt_sel_pro_board').val(),
                    start_: startDate,
                    end_: endDate
                });

            }, 300);
        }


        $('#txt_sel_pro_board').on('change', function() {
            var project = this.value;
            var start5 = moment().startOf('year').format('YYYY-MM-DD');
            var end5 =  moment().endOf('year').format('YYYY-MM-DD');
            $("#daterange-btn-last-5").data('daterangepicker').setStartDate(moment().startOf('year'));
            $("#daterange-btn-last-5").data('daterangepicker').setEndDate(moment().endOf('year'));
            $('#daterange-btn-last-5 span').html(moment().startOf('year').format('D MMMM, YYYY') + ' - ' + moment().endOf('year').format('D MMMM, YYYY'));
            setTimeout(function() {
                $("#load_client_price").load("<?= $CFG->src_load_alert; ?>/load_client_price.php", {
                    project_: project,
                    start_: start5,
                    end_: end5
                });
                
            }, 300);
        });

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
            var start5 = moment().startOf('year').format('YYYY-MM-DD');
            var end5 =  moment().endOf('month').format('YYYY-MM-DD');
            //Load data
            setTimeout(function() {
                $("#load_client_price").load("<?= $CFG->src_load_alert; ?>/load_client_price.php", {
                    start_: start5,
                    end_: end5
                });
            }, 300);
        }
    </script>

</body>

</html>