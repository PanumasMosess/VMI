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
                                        <th>Installment (งวด)</th>
                                        <th>FG Code</th>
                                        <th>Product (สินค้า)</th>
                                        <th>Qty (ชิ้น)</th>
                                        <th>Price (ราคา)</th>
                                        <th>Customer Name</th>
                                        <th>Customer Address</th>
                                        <th>Payment Date (วันชำระ)</th>
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

    <!--------------------dlg manage tag-------------------->
    <div class="modal fade" id="modal-manage-date" data-keyboard="false" data-backdrop="static">
        <div class="modal-dialog modal-sm">
            <div class="modal-content">
                <div class="modal-header">
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span></button>
                    <h4 class="modal-title"><i class="fa fa-calendar"></i> Pick Date Installmemt</h4>
                </div>
                <div class="modal-body">
                    <div class="box-body table-responsive padding">
                        <div class="form-group col-sm-12">
                            <div class="input-group date">
                                <div class="input-group-addon">
                                    <i class="fa fa-calendar"></i>
                                </div>
                                <input type="text" class="form-control pull-right" id="payment_pick" name="payment_pick">
                                <input type="hidden" class="form-control pull-right" id="payment_id" name="payment_id">
                            </div>
                            <!-- /.input group -->
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" onclick="con_update_payment_date();" class="btn btn-primary btn-sm"><i class="fa fa-check-circle"></i> Comfirm Payment Date</button>
                    <button type="button" class="btn btn-default btn-sm" onclick="close_clear();">Close</button>
                </div>
            </div>
            <!-- /.modal-content -->
        </div>
        <!-- /.modal-dialog -->
    </div>

    <?
    require_once("js_css_footer.php");
    ?>

    <script>
        $(document).ready(function() {
            //Initialize Select2 Elements
            $(".select2").select2();
            load_table();
        });

        function load_table(){
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
                            return "<button type'button' class='btn btn-warning btn-sm custom_tooltip' id='" + data["in_id"] + "' onclick='openFuncUpdateDate(this.id);'><i class='glyphicon glyphicon-calendar'></i><span class='custom_tooltiptext'>Pament Date</span></button>"
                        }
                    },
                    {
                        "data": null,
                        render: function(data, type, row) {
                            return "<font>งวดชำระ </font>" + data["installment"] + ""
                        }
                    },
                    {
                        data: 'repn_fg_code_gdj',
                    },
                    {
                        data: 'description',
                    },
                    {
                        "data": null,
                        render: function(data, type, row) {

                            return "<font style='font-weight: bold'>" + data["qty"] + "</font> "
                        }
                    },
                    {
                        "data": null,
                        render: function(data, type, row) {

                            return "<font style='font-weight: bold'>" + data["price"] + "</font> "
                        }
                    },
                    {
                        data: 'b2c_contact_name',
                    },
                    {
                        data: 'b2c_delivery_address',
                    },
                    {
                        "data": null,
                        render: function(data, type, row) {
                            if (data["installment_payment_date"] == "1900-01-01") {
                                return "";
                            } else {
                                return "<font style='color: #000;'>" + data["installment_payment_date"] + "</font>"
                            }

                        }

                    },
                    {
                        data: 'issue_date'
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
                        columns: [3, 4, 5, 6, 7, 8, 9, 10],
                        // format: {
                        //     body: function(data, row, column, node) {
                        //         if ((column >= 0 && column <= 6) || (column >= 8 && column <= 17)) {
                        //             var data_ = data.replace(/<.*?>/ig, '');
                        //             return data_;
                        //         } else if (column == 7) {
                        //             var data_ = data.replace(/<.*?>/ig, '');
                        //             data_.split('(');
                        //             return data_.split('(')[0];
                        //         }
                        //         return data;
                        //     }
                        // }
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
                                ajax_payment_date: repn_id,
                                ajax_init_id: repn_1[0],
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

        function openFuncUpdateDate(id) {
            $('#modal-manage-date').modal('show');
            $('#payment_id').val(id);
        }

        $('#payment_pick').datepicker({
            autoclose: true,
            yearRange: '1990:+0',
            format: 'yyyy-mm-dd',
            onSelect: function(date) {
                alert(date);
            },
            changeMonth: true,
            changeYear: true,
        });

        function close_clear() {
            $('#modal-manage-date').modal('hide');
            $("#payment_pick").val('');
            $('#payment_id').val('');
        }

        function con_update_payment_date() {
            $('#modal-manage-date').modal('hide');
            //dialog ctrl
            swal({
                    html: true,
                    title: "<span style='font-size: 15px; font-weight: bold;'>Warning !!!</span>",
                    text: "<span style='font-size: 15px; color: #000;'>ต้องการ<b>ยืนยัน</b> Payment Date ?</span>",
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
                            url: '<?= $CFG->src_replenishment; ?>/confirm_update_payment.php',
                            data: {
                                ajax_payment_date: $('#payment_pick').val(),
                                ajax_init_id: $('#payment_id').val(),
                            },
                            success: function(response) {
                                if (response == 'Success') {
                                    //dialog ctrl
                                    swal({
                                        html: true,
                                        title: "<span style='font-size: 15px; font-weight: bold;'>สำเร็จ !!!</span>",
                                        text: "<span style='font-size: 15px; color: #000;'>เพิ่มวันชำระเงิน สำเร็จ</span>",
                                        type: "success",
                                        timer: 3000,
                                        showConfirmButton: false,
                                        allowOutsideClick: false
                                    });
                                    $("#payment_pick").val('');
                                    $('#payment_id').val('');
                                    load_table();
                                } else {
                                    //dialog ctrl
                                    swal({
                                        html: true,
                                        title: "<span style='font-size: 15px; font-weight: bold;'>Warning !!!</span>",
                                        text: "<span style='font-size: 15px; color: #000;'>ไม่สามารถเพิ่มวันชำระเงินได้</span>",
                                        type: "warning",
                                        timer: 3000,
                                        showConfirmButton: false,
                                        allowOutsideClick: false
                                    });

                                    $("#payment_pick").val('');
                                    $('#payment_id').val('');
                                }
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