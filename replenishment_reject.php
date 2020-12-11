<?
require_once("application.php");
require_once("js_css_header.php");
?>
<!DOCTYPE html>
<html>

<body class="hold-transition skin-blue sidebar-mini">
    <div class="wrapper">

        <?
	require_once("menu.php");
  ?>
        <!--------------------------->
        <!-- body  -->
        <!--------------------------->
        <!-- Content Wrapper. Contains page content -->
        <div class="content-wrapper">
            <!-- Content Header (Page header) -->
            <section class="content-header">
                <h1><i class="fa fa-caret-right"></i>&nbsp;Replenishment Reject</h1>
                <ol class="breadcrumb">
                    <li><a href="<?= $CFG->wwwroot; ?>/home"><i class="fa fa-home"></i>Home</a></li>
                    <li class="active">Replenishment Reject</li>
                </ol>
            </section>

            <!-- Main content -->
            <section class="content">
                <div class="row">
                    <div class="col-xs-12">
                        <div class="box box-info">
                            <div class="box-header with-border">
                                <h3 class="box-title"><i class="fa fa-retweet"></i>&nbsp;Replenishment Reject List</h3>
                            </div>
                            <!-- /.box-header -->

                            <div class="box-header">
                                <button type="button" class="btn btn-info btn-sm" onclick="window.location.reload();"><i class="fa fa-refresh fa-lg"></i> Refresh</button>
                            </div>
                            <div style="padding-left: 8px;">
                                <i class="fa fa-filter" style="color: #00F;"></i>
                                <font style="color: #00F;">SQL >_ SELECT * ROWS</font>
                            </div>

                            <!-- /.box-header -->
                            <div class="box-body table-responsive padding">
                                <table id="tbl_replenishment_reject" class="table table-bordered table-hover table-striped nowrap">
                                    <thead>
                                        <tr style="font-size: 18px;">
                                            <th colspan="11" class="bg-light-blue"><b>
                                                    <font style="color: #FFF;"><i class="fa fa-retweet fa-lg"></i>&nbsp;Replenishment Reject</font>
                                                </b>
                                            </th>
                                        </tr>
                                        <tr style="font-size: 13px;">
                                            <th style="width: 30px;">No.</th>
                                            <th style="text-align: center;">FG Code ABT</th>
                                            <th style="text-align: center;">SKU Code ABT</th>
                                            <th style="text-align: center;">FG Code GDJ</th>
                                            <th style="text-align: center;">Project Name</th>
                                            <th style="text-align: center;">Ship Type</th>
                                            <th style="text-align: center;">Part Customer</th>
                                            <th style="color: #00F; text-align:center">Quantity (Pcs.)</th>
                                            <th style="text-align: center;">Unit Type</th>
                                            <th style="text-align: center;">Terminal Name</th>
                                            <th style="text-align: center;">Order Type</th>
                                            <th style="text-align: center;">Order Reference</th>
                                            <th style="text-align: center;">Delivery Date</th>
                                            <th style="text-align: center;">Replenish By</th>
                                            <th style="text-align: center;">Replenish datetime</th>
                                            <th style="text-align: center;">Config Status</th>
                                            <th style="text-align: center;">Config Remark</th>
                                            <th style="text-align: center;">Config By</th>
                                            <th style="text-align: center;">Config datetime</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <?
					$strSql = " SELECT TOP (2000) [repn_order_ref]
                    ,[repn_fg_code_set_abt]
                    ,[repn_sku_code_abt]
                    ,[repn_fg_code_gdj]
                    ,[repn_pj_name]
                    ,[repn_ship_type]
                    ,[repn_part_customer]
                    ,[repn_qty]
                    ,[repn_unit_type]
                    ,[repn_terminal_name]
                    ,[repn_order_type]
                    ,[repn_order_ref]
                    ,[repn_delivery_date]
                    ,[repn_by]
                    ,[repn_datetime]
                    ,[repn_conf_status]
                    ,[repn_conf_remark]                  
                    ,[repn_conf_by]
                    ,[repn_conf_datetime]
                FROM [VMI_test].[dbo].[tbl_replenishment] where repn_conf_status = 'Rejected' 
                ORDER BY repn_datetime desc
                ";
					
					$objQuery = sqlsrv_query($db_con, $strSql, $params, $options);
					$num_row = sqlsrv_num_rows($objQuery);

					$row_id = 0;
					while($objResult = sqlsrv_fetch_array($objQuery, SQLSRV_FETCH_ASSOC))
					{
						$row_id++;
	  
                        $repn_order_ref = $objResult['repn_order_ref'];
                        $repn_fg_code_set_abt = $objResult['repn_fg_code_set_abt'];
                        $repn_sku_code_abt = $objResult['repn_sku_code_abt'];
                        $repn_fg_code_gdj = $objResult['repn_fg_code_gdj'];
                        $repn_pj_name = $objResult['repn_pj_name'];
                        $repn_ship_type = $objResult['repn_ship_type'];	
                        $repn_part_customer = $objResult['repn_part_customer'];	
                        $repn_qty = $objResult['repn_qty'];	
                        $repn_unit_type = $objResult['repn_unit_type'];	
                        $repn_terminal_name = $objResult['repn_terminal_name'];	
                        $repn_order_type = $objResult['repn_order_type'];
                        $repn_order_ref = $objResult['repn_order_ref'];		
                        $repn_delivery_date = $objResult['repn_delivery_date'];	
                        $repn_by = $objResult['repn_by'];	
                        $repn_datetime = $objResult['repn_datetime'];	
                        $repn_conf_status = $objResult['repn_conf_status'];	
                        $repn_conf_remark = $objResult['repn_conf_remark'];	
                        $repn_conf_by = $objResult['repn_conf_by'];	
                        $repn_conf_datetime = $objResult['repn_conf_datetime'];								
					
					?>
                                        <tr style="font-size: 13px;">
                                            <td><?= $row_id; ?></td>
                                            <td><?= $repn_order_ref; ?></td>
                                            <td><?= $repn_fg_code_set_abt; ?></td>
                                            <td><?= $repn_fg_code_gdj; ?></td>
                                            <td><?= $repn_pj_name; ?></td>
                                            <td><?= $repn_ship_type; ?></td>
                                            <td><?= $repn_part_customer; ?></td>
                                            <td style="color: #00F;"><?= $repn_qty; ?></td>
                                            <td><?= $repn_terminal_name; ?></td>
                                            <td><?= $repn_unit_type; ?></td>
                                            <td><?= $repn_order_type; ?></td>
                                            <td><?= $repn_order_ref; ?></td>
                                            <td><?= $repn_delivery_date; ?></td>
                                            <td><?= $repn_by; ?></td>
                                            <td><?= $repn_datetime; ?></td>
                                            <td style="color: red;"><?= $repn_conf_status; ?></td>
                                            <td><?= $repn_conf_remark; ?></td>
                                            <td><?= $repn_conf_by; ?></td>
                                            <td><?= $repn_conf_datetime; ?></td>

                                        </tr>
                                        <?
					}
					?>
                                    </tbody>
                                </table>
                            </div>
                            <!-- /.box-body -->

                            <!--alert no item-->
                            <input type="hidden" name="hdn_row_replenish" id="hdn_row_replenish" value="<?= $row_id; ?>" />
                        </div>
                        <!-- /.box -->
                    </div>
                    <!-- /.col -->
                </div>
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
    <script language="javascript">
        //Onload this page
        // $(document).ready(function()
        // {

        // });

        var table_reject;
        // <!--datatable search paging-->
        table_reject = $('#tbl_replenishment_reject').DataTable({
            rowReorder: true,
            columnDefs: [{
                    orderable: true,
                    className: 'reorder',
                    targets: [0, 2, 3, 4, 5, 6, 7, 8, 9, 10, 11, 12, 13, 14, 15, 16]
                },
                {
                    orderable: false,
                    targets: '_all'
                }
            ],
            pagingType: "full_numbers",
            rowCallback: function(row, data, index) {
                //status
                //if(data[2] == "VMI Order"){
                //	$(row).find('td:eq(2)').css('color', 'indigo');
                //}
                //else if(data[2] == "Special Order"){
                //	$(row).find('td:eq(2)').css('color', 'red');
                //}		

            },
        });
    </script>
</body>

</html>