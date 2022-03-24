<?
require_once("application.php");
$CFG->dbhost_pur = "203.154.39.115";
$CFG->dbhostPing_pur = "203.154.39.115";
$CFG->dbname_pur = "PUR";
$CFG->dbuser_pur = "sa";
$CFG->dbpass_pur = "P09iQA!WaT_?#R41!eXO";

//set var to array (var must be string type)
$connectionInfo_pur = array("Database" => "$CFG->dbname_pur", "UID" => "$CFG->dbuser_pur", "PWD" => "$CFG->dbpass_pur", "MultipleActiveResultSets" => true, 'ReturnDatesAsStrings' => true, "CharacterSet" => 'UTF-8');
$db_con_pur = sqlsrv_connect($CFG->dbhost_pur, $connectionInfo_pur);

if ($db_con_pur === false) {
    echo "Connection could not be established. <br/>";
    die(print_r(sqlsrv_errors(), true));
}
require_once("get_authorized.php");
require_once("js_css_header.php");

?>


<!DOCTYPE html>
<html>

<head>
    <style>
        .btn_submit {
            float: right;
        }

        li.active {
            background-color: rgba(0, 0, 0, 0.1);
        }
    </style>
</head>

<body class="hold-transition register-page">
    <header class="main-header" style="background-color: #D1D1D1;">
        <div class="navbar-header">
            <a href="#" class="navbar-brand" style="color: #3c8dbc;"><b>B2C Special Order</b></a>
            <button type="button" class="navbar-toggle collapsed" data-toggle="collapse" data-target="#navbar-collapse">
                <i class="fa fa-bars"></i>
            </button>
        </div>
        <!-- Header Navbar: style can be found in header.less -->
        <nav class="navbar navbar-static-top">
            <!-- Sidebar toggle button-->
            <a href="#" class="sidebar-toggle hidden-md hidden-lg" title="Toggle navigation" data-toggle="push-menu" role="button"></a>
            <div class="visible-lg" style="float: right; padding: 5px; color: #FFF;">
                <img src="<?= $CFG->logodir; ?>/dscm.jpg" height="40px" style="background-color: #; border: 1px solid #DDD; border-radius: 4px; padding: 2px;" /><img src="<?= $CFG->logodir; ?>/GDJ.png" height="40px" style="background-color: #; border: 1px solid #DDD; border-radius: 4px; padding: 2px;" />
            </div>
            <div class="collapse navbar-collapse pull-left" id="navbar-collapse">
                <ul class="nav navbar-nav">
                    <li><a href="<?= $CFG->wwwroot; ?>/speial_b2c_form">Form</a></li>
                    <li class="active"><a href="<?= $CFG->wwwroot; ?>/list_confirm_b2c">List Confirm<span class="sr-only">(current)</span></a></li>
                </ul>
            </div>
        </nav>
    </header>
    <div class="content">
        <!-- Content Header (Page header) -->
        <section class="content-header">
        </section>

        <!-- Main content -->
        <section class="content">
            <div class="row">
                <div class="col-xs-12">
                    <div class="box box-info">
                        <div class="box-header">

                        </div>
                        <!-- /.box-header -->
                        <div class="box-body table-responsive padding">
                            <table id="w8_table" class="table table-bordered table-hover table-striped nowrap">
                                <thead>
                                    <tr>
                                    <tr style="font-size: 13px;">
                                        <th style="width: 30px;">No.</th>
                                        <th>Action</th>
                                        <th>Refill Type/Unit Type</th>
                                        <th>Ref No.</th>
                                        <th>Delivery Date</th>
                                        <th>FG Code Set</th>
                                        <th>Component Code</th>
                                        <th>Part Customer</th>
                                        <th style="color: #00F;">FG Code GDJ</th>
                                        <th style="color: #00F;">Quantity (Pcs.)</th>
                                        <th>Issue By</th>
                                        <th>Issue Datetime</th>
                                    </tr>
                                    </tr>
                                </thead>
                                <tbody>

                                </tbody>
                            </table>
                        </div>
                        <!-- /.box-body -->
                    </div>
                    <!-- /.box -->
                </div>
                <!-- /.col -->
            </div>
            <!-- /.row -->
        </section>
        <!-- /.content -->
    </div>

    <?
    require_once("js_css_footer.php");
    ?>

    <script>
        $(document).ready(function() {
            //Initialize Select2 Elements
            $(".select2").select2();
        });

        //load data json
        $.ajax({
            type: 'POST',
            url: "<?= $CFG->src_replenishment; ?>/load_order_wait_confirm.php",
            success: function(respone) {
                var result = JSON.parse(respone);
                callinTableJobsList(result);
            }
        });

        //plot data
        function callinTableJobsList(data) {

            var table = $("#w8_table").DataTable({
                "bDestroy": true,
                rowReorder: true,
                "aLengthMenu": [
                    [10, 25, 50, 75, 100, -1],
                    [10, 25, 50, 75, 100, "All"]
                ],
                "iDisplayLength": 10,
                columnDefs: [{
                        orderable: true,
                        className: 'reorder',
                        targets: [0, 2, 3, 4, 5, 6, 7, 8, 9]
                    },
                    {
                        orderable: false,
                        targets: '_all'
                    }
                ],
                orderCellsTop: true,
                fixedHeader: true,
                pagingType: "full_numbers",
                responsive: true,
                autoFill: true,
                colReorder: true,
                keys: true,
                rowReorder: true,
                select: true,
                processing: true,
                serverside: true,
                data: data,
                columns: [{
                        data: 'row_id'
                    },
                    {
                        "data": null,
                        render: function(data, type, row) {
                            return "<button type'button' class='btn btn-info btn-sm custom_tooltip' id='" + data["repn_id"] + "###" + data["repn_order_ref"] + "###" + data["repn_fg_code_set_abt"] + "###" + data["repn_sku_code_abt"] + "###" + data["bom_fg_code_gdj"] + "###" + data["bom_pj_name"] + "###" + data["bom_ship_type"] + "###" + data["bom_part_customer"] + "###" + data["repn_qty"] + "###" + data["repn_unit_type"] + "###" + data["repn_terminal_name"] + "###" + data["repn_delivery_date"] + "' onclick='openFuncConfirm_con(this.id);'><i class='glyphicon glyphicon-ok'></i><span class='custom_tooltiptext'>Confirm Order</span></button>"
                        }
                    },
                    {
                        "data": null,
                        render: function(data, type, row) {
                            return "<font>" + data["repn_order_type"] + "</font>/" + data["repn_unit_type"] + ""
                        }
                    },
                    {
                        data: 'repn_order_ref',
                    },
                    {
                        "data": null,
                        render: function(data, type, row) {

                            return "<font style='font-weight: bold'>" + data["repn_delivery_date"] + "</font> "
                        }
                    },
                    {
                        data: 'repn_fg_code_set_abt'
                    },
                    {
                        data: 'repn_sku_code_abt',
                    },
                    {
                        data: 'bom_part_customer',
                    },
                    {
                        "data": null,
                        render: function(data, type, row) {
                            return "<font style='color: #00F;'>" + data["bom_fg_code_gdj"] + "</font>"
                        }

                    },
                    {
                        "data": null,
                        render: function(data, type, row) {
                            return "<font style='color: #00F;'>" + data["repn_qty"] + "</font>"
                        }

                    },
                    {
                        data: 'repn_by'
                    },
                    {
                        data: 'repn_datetime_cut'
                    },
                ]
            });

            var buttons = new $.fn.dataTable.Buttons(table, {
                buttons: [{
                    extend: 'excel',
                    text: '<i class="fa fa-file-excel-o"></i> Export Replenishment Order',
                    titleAttr: 'Excel Replenishment Order Report',
                    title: 'Excel Replenishment Order Report',
                    exportOptions: {
                        modifier: {
                            page: 'all'
                        },
                        columns: [3, 4, 5, 6, 7, 8, 9, 10, 11, 12, 13, 14, 15, 16, 17],
                        format: {
                            body: function(data, row, column, node) {
                                if ((column >= 0 && column <= 6) || (column >= 8 && column <= 17)) {
                                    var data_ = data.replace(/<.*?>/ig, '');
                                    return data_;
                                } else if (column == 7) {
                                    var data_ = data.replace(/<.*?>/ig, '');
                                    data_.split('(');
                                    return data_.split('(')[0];
                                }
                                return data;
                            }
                        }
                    }
                }],
                dom: {
                    button: {
                        tag: 'button',
                        className: 'btn btn-default btn-sm'
                    }
                },
            }).container().appendTo($('#excel_export'));

        }


        function openFuncConfirm_con(id) {
            var str_split = id;
            var str_split_result = str_split.split("###");
            
            var repn_id = str_split_result[0];
            var repn_1 = str_split_result[1];
            var repn_2 = str_split_result[2];
            var repn_3 = str_split_result[3];

            var repn_1 = repn_1.split("(deposit)");

            		//dialog ctrl
			swal({
					html: true,
					title: "<span style='font-size: 15px; font-weight: bold;'>Warning !!!</span>",
					text: "<span style='font-size: 15px; color: #000;'>ต้องการ<b>ยืนยัน</b> Order ?</span>",
					type: "warning",
					showCancelButton: true,
					confirmButtonClass: "btn-info",
					confirmButtonText: "Yes",
					cancelButtonText: "No",
					closeOnConfirm: true,
					closeOnCancel: true
				},
				function(isConfirm) {
					if (isConfirm) {
						//accepted order
						$.ajax({
							type: 'POST',
							url: '<?= $CFG->src_replenishment; ?>/confirm_list_b2c_deposit.php',
							data: {
								repn_id_ajax: repn_id,
                                repn_order_ajax: repn_1[0],
							},
							success: function(response) {
								
							},
							error: function() {
								//dialog ctrl
								swal({
									html: true,
									title: "<span style='font-size: 15px; font-weight: bold;'>Warning !!!</span>",
									text: "<span style='font-size: 15px; color: #000;'>[D002] --- Ajax Error !!! Cannot operate</span>",
									type: "warning",
									timer: 3000,
									showConfirmButton: false,
									allowOutsideClick: false
								});
							}
						});

					}
				});
            
        }
    </script>
</body>

</html>