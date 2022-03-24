<?
require_once("application.php");
require_once("js_css_header.php");
?>
<!DOCTYPE html>
<html>

<style>
    /** SPINNER CREATION **/
    .modal-dialog-load {
        padding-top: 15%;
        padding-left: 10%;
    }

    .loader {
        position: relative;
        text-align: center;
        margin: 15px auto 25px auto;
        z-index: 9999;
        display: block;
        width: 80px;
        height: 80px;
        border: 10px solid rgba(0, 0, 0, .3);
        border-radius: 50%;
        border-top-color: #000;
        animation: spin 1s ease-in-out infinite;
        -webkit-animation: spin 1s ease-in-out infinite;
    }

    @keyframes spin {
        to {
            -webkit-transform: rotate(360deg);
        }
    }

    @-webkit-keyframes spin {
        to {
            -webkit-transform: rotate(360deg);
        }
    }
</style>

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
                <h1><i class="fa fa-caret-right"></i>&nbsp;Print Master Tags<small>Generate tags for products</small></h1>
                <ol class="breadcrumb">
                    <li><a href="<?= $CFG->wwwroot; ?>/home"><i class="fa fa-home"></i>Home</a></li>
                    <li class="active">Print Master Tags</li>
                </ol>
            </section>

            <!-- Main content -->
            <section class="content">

                <div class="box box-info">
                    <div class="box-header with-border">
                        <h3 class="box-title"><i class="fa fa-qrcode"></i> Generate Master Tags</h3>
                    </div>
                    <!-- /.box-header -->
                    <div class="box-body">
                        <div class="row">
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label>Trading From:</span></label>
                                    <select id="sel_trading_from" name="sel_trading_from" class="form-control input-sm select2" style="width: 100%;" ">
                                        <option selected=" selected" value="">Choose</option>
                                        <?
                                        $strSQL_trading = " SELECT [supp_code],[supp_name] FROM tbl_fulfillment_supplier where supp_status = 'Active' group by [supp_code],[supp_name] order by supp_code asc";
                                        $objQuery_trading = sqlsrv_query($db_con, $strSQL_trading) or die("Error Query [" . $strSQL_trading . "]");
                                        while ($objResult_trading = sqlsrv_fetch_array($objQuery_trading, SQLSRV_FETCH_ASSOC)) {
                                        ?>
                                            <option value="<?= $objResult_trading["supp_code"]; ?>"><?= $objResult_trading["supp_code"]; ?> - <?= $objResult_trading["supp_name"]; ?></option>
                                        <?
                                        }
                                        ?>
                                    </select>
                                </div>
                                <!-- /.form-group -->
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label>Project Name:</span></label>
                                    <select id="sel_project_name" name="sel_project_name" class="form-control input-sm select2" style="width: 100%;" ">
                                        <option selected=" selected" value="">Choose</option>
                                        <?
                                        $strSQL_pj_code = " SELECT bom_pj_name FROM tbl_bom_mst where bom_status = 'Active' group by bom_pj_name order by bom_pj_name asc";
                                        $objQuery_pj_code = sqlsrv_query($db_con, $strSQL_pj_code) or die("Error Query [" . $strSQL_cus_code . "]");
                                        while ($objResult_pj_code = sqlsrv_fetch_array($objQuery_pj_code, SQLSRV_FETCH_ASSOC)) {
                                        ?>
                                            <option value="<?= $objResult_pj_code["bom_pj_name"]; ?>"><?= $objResult_pj_code["bom_pj_name"]; ?></option>
                                        <?
                                        }
                                        ?>
                                    </select>
                                </div>
                                <!-- /.form-group -->
                            </div>
                            <!-- /.col -->
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label>FG Code:</span><span id="spn_load_fg_code_gdj_packing_desc"></span><span id="spn_load_fg_code_gdj_packing_qty"></span></label>
                                    <select id="sel_fg_code" name="sel_fg_code" class="form-control input-sm select2" style="width: 100%;" onchange="func_load_packing_qty()">
                                        <option selected="selected" value="">Choose</option>
                                    </select>
                                </div>
                                <!-- /.form-group -->
                            </div>
                            <!-- /.col -->
                        </div>

                        <div class="row">
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label>Production Plan Qty.:</label>
                                    <input type="text" id="txt_prod_plan" name="txt_prod_plan" class="form-control input-sm" maxlength="5" placeholder="Enter Production Plan Qty.">
                                </div>
                                <!-- /.form-group -->
                            </div>
                            <!-- /.col -->

                            <div class="col-md-6">
                                <div class="form-group">
                                    <label>Description:</label>
                                    <input type="text" id="txt_fg_code_gdj_desc" name="txt_fg_code_gdj_desc" class="form-control input-sm" placeholder="Auto load Description" disabled>
                                </div>
                                <!-- /.form-group -->
                            </div>
                            <!-- /.col -->
                        </div>

                        <!-- /.row -->

                        <div class="row">
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label>Total Tags Qty.:</label>
                                    <input type="text" id="txt_tags_total" name="txt_tags_total" class="form-control input-sm" placeholder="Auto Calculate Tags Qty." disabled>
                                </div>
                                <!-- /.form-group -->
                            </div>
                            <!-- /.col -->

                            <div class="col-md-6">
                                <div class="form-group">
                                    <label>Packing Standard Qty.:</label>
                                    <input type="text" id="txt_packing_std" name="txt_packing_std" class="form-control input-sm" maxlength="3" placeholder="Enter Packing standard Qty." disabled>
                                </div>
                                <!-- /.form-group -->
                            </div>
                            <!-- /.col -->
                        </div>

                    </div>
                    <!-- /.box-body -->
                    <div class="box-footer">
                        <button type="button" class="btn btn-primary btn-sm" onclick="gen_tags()"><i class="fa fa-qrcode"></i> Generate Master Tags</button>
                    </div>
                </div>
                <!-- /.box -->

                <div class="row">
                    <div class="col-xs-12">
                        <div class="box box-warning">
                            <div class="box-header with-border">
                                <h3 class="box-title"><i class="fa fa-qrcode"></i> Master Tags List (Today)</h3>
                            </div>
                            <!-- /.box-header -->

                            <div class="box-header">
                                <button type="button" class="btn btn-info btn-sm" onclick="_load_tags_details();"><i class="fa fa-refresh fa-lg"></i> Refresh</button>
                            </div>
                            <div style="padding-left: 8px;">
                                <i class="fa fa-filter" style="color: #00F;"></i>
                                <font style="color: #00F;">SQL >_ SELECT * ROWS | Today</font>
                            </div>
                            <!-- /.box-header -->
                            <!-- <span id="spn_load_tags_details"></span> -->
                            <div class="box-body table-responsive padding">
                                <table id="tbl_print_tagss" class="table table-bordered table-hover table-striped nowrap">
                                    <thead>
                                        <tr style="font-size: 13px;">
                                            <th style="width: 30px;">No.</th>
                                            <th style="text-align: center;">Actions/Details</th>
                                            <th>Put away</th>
                                            <th>Tags ID</th>
                                            <th>FG Code GDJ</th>
                                            <th>Description</th>
                                            <th>Project Name</th>
                                            <th>Production Plan Qty.</th>
                                            <th style="color: indigo;">Packing STD Qty.(Pcs.)</th>
                                            <th>Total Tags Qty</th>
                                            <th>Lot Token</th>
                                            <th>Trading From</th>
                                            <th>Issue By</th>
                                            <th>Issue Datetime</th>
                                        </tr>
                                    </thead>
                                    <tbody style="font-size: 13px;">
                                    </tbody>
                                </table>
                            </div>
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
        $(document).ready(function() {

            //valid number only
            $("#txt_prod_plan,#txt_packing_std").keyup(function(e) {
                if (/\D/g.test(this.value)) {
                    // Filter non-digits from input value.
                    this.value = this.value.replace(/\D/g, '');
                }

                //call cal
                tags_calculate();

            });

            //Initialize Select2 Elements
            $(".select2").select2();

            //load tags
            _load_tags_details();


        });

        //valid number only onkeypress
        //onkeypress="return isNumber(event)"
        function isNumber(evt) {
            evt = (evt) ? evt : window.event;
            var charCode = (evt.which) ? evt.which : evt.keyCode;
            if (charCode > 31 && (charCode < 48 || charCode > 57)) {
                return false;

            }
            return true;
        }

        function func_load_packing_qty() {

            //clear 
            $('#txt_fg_code_gdj_desc').val('');
            $('#txt_packing_std').val('');

            var fgVal = $('#sel_fg_code').val();
            var projectVal = $('#sel_project_name').val();
            //Load data
            setTimeout(function() {
                //$("#spn_load_fg_code_gdj_packing_desc").html(""); //clear span

                $("#spn_load_fg_code_gdj_packing_desc").load("<?= $CFG->src_print_tags; ?>/fg_code_gdj_desc.php", {
                    fg_code_gdj: fgVal,
                    project_name: projectVal,
                });
            }, 500);

            //Load data
            setTimeout(function() {
                //$("#spn_load_fg_code_gdj_packing_qty").html(""); //clear span
                $("#spn_load_fg_code_gdj_packing_qty").load("<?= $CFG->src_print_tags; ?>/fg_code_gdj_packing_qty.php", {
                    fg_code_gdj: fgVal,
                    project_name: projectVal,
                });
            }, 500);

            //clear
            $("#txt_prod_plan").val('');
            $("#txt_tags_total").val('');
        }

        function tags_calculate() {
            var prod_qty = parseInt($("#txt_prod_plan").val()),
                packing_qty = parseInt($("#txt_packing_std").val());
            var str_diff = prod_qty / packing_qty;
            $("#txt_tags_total").val(Math.ceil(str_diff));
        }

        function gen_tags() {
            //check validate 
            if ($("#sel_trading_from").val() == "") {
                //dialog ctrl
                swal({
                    html: true,
                    title: "<span style='font-size: 15px; font-weight: bold;'>Warning !!!</span>",
                    text: "<span style='font-size: 15px; color: #000;'>[C001] --- Select Trading From</span>",
                    type: "warning",
                    timer: 2000,
                    showConfirmButton: false,
                    allowOutsideClick: false
                });

                //hide
                setTimeout(function() {
                    $('#sel_trading_from').select2('open');
                }, 3000);

                return false;
            } else if ($("#sel_project_name").val() == "") {
                //dialog ctrl
                swal({
                    html: true,
                    title: "<span style='font-size: 15px; font-weight: bold;'>Warning !!!</span>",
                    text: "<span style='font-size: 15px; color: #000;'>[C001] --- Select Project Name</span>",
                    type: "warning",
                    timer: 2000,
                    showConfirmButton: false,
                    allowOutsideClick: false
                });

                //hide
                setTimeout(function() {
                    $('#sel_project_name').select2('open');
                }, 3000);

                return false;
            } else if ($("#sel_fg_code").val() == "") {
                //dialog ctrl
                swal({
                    html: true,
                    title: "<span style='font-size: 15px; font-weight: bold;'>Warning !!!</span>",
                    text: "<span style='font-size: 15px; color: #000;'>[C001] --- Select FG Code GDJ</span>",
                    type: "warning",
                    timer: 2000,
                    showConfirmButton: false,
                    allowOutsideClick: false
                });

                //hide
                setTimeout(function() {
                    $('#sel_fg_code').select2('open');
                }, 3000);

                return false;
            } else if ($("#txt_prod_plan").val() == "") {
                //dialog ctrl
                swal({
                    html: true,
                    title: "<span style='font-size: 15px; font-weight: bold;'>Warning !!!</span>",
                    text: "<span style='font-size: 15px; color: #000;'>[C001] --- Enter Production Plan Qty.</span>",
                    type: "warning",
                    timer: 2000,
                    showConfirmButton: false,
                    allowOutsideClick: false
                });

                //hide
                setTimeout(function() {
                    $("#txt_prod_plan").focus();
                }, 3000);

                return false;
            } else if ($("#txt_prod_plan").val() == "0") {
                //dialog ctrl
                swal({
                    html: true,
                    title: "<span style='font-size: 15px; font-weight: bold;'>Warning !!!</span>",
                    text: "<span style='font-size: 15px; color: #000;'>[C001] --- Production Plan Qty is Zero.</span>",
                    type: "warning",
                    timer: 2000,
                    showConfirmButton: false,
                    allowOutsideClick: false
                });

                //hide
                setTimeout(function() {
                    $("#txt_prod_plan").focus();
                }, 3000);

                return false;
            } else if ($("#txt_packing_std").val() == "") {
                //dialog ctrl
                swal({
                    html: true,
                    title: "<span style='font-size: 15px; font-weight: bold;'>Warning !!!</span>",
                    text: "<span style='font-size: 15px; color: #000;'>[C001] --- Enter Packing Standard Qty.</span>",
                    type: "warning",
                    timer: 2000,
                    showConfirmButton: false,
                    allowOutsideClick: false
                });

                //hide
                setTimeout(function() {
                    $("#txt_packing_std").focus();
                }, 3000);

                return false;
            } else if ($("#txt_packing_std").val() == "0") {
                //dialog ctrl
                swal({
                    html: true,
                    title: "<span style='font-size: 15px; font-weight: bold;'>Warning !!!</span>",
                    text: "<span style='font-size: 15px; color: #000;'>[C001] --- Packing Standard Qty. is Zero</span>",
                    type: "warning",
                    timer: 2000,
                    showConfirmButton: false,
                    allowOutsideClick: false
                });

                //hide
                setTimeout(function() {
                    $("#txt_packing_std").focus();
                }, 3000);

                return false;
            }

            //gen token
            var str_token = tokenGen(30);

            $("#loadding").modal({
                backdrop: "static", //remove ability to close modal with click
                keyboard: false, //remove option to close with keyboard
                show: true //Display loader!
            });


            $.ajax({
                type: 'POST',
                url: '<?= $CFG->src_print_tags; ?>/generate_tags.php',
                data: {
                    iden_sel_trading_from: $("#sel_trading_from").val(),
                    iden_sel_fg_code_gdj: $("#sel_fg_code").val(),
                    iden_txt_fg_code_gdj_desc: $("#txt_fg_code_gdj_desc").val(),
                    iden_sel_project_name: $('#sel_project_name').val(),
                    iden_txt_prod_plan: $("#txt_prod_plan").val(),
                    iden_txt_packing_std: $("#txt_packing_std").val(),
                    iden_txt_tags_total: $("#txt_tags_total").val(),
                    iden_token: str_token
                },
                success: function(response) {

                    //print tags send token encode
                    window.open("<?= $CFG->src_mPDF; ?>/print_tags?token=" + response + "", "_blank");

                    window.open("<?= $CFG->src_mPDF; ?>/print_tag_lot?token=" + response + "", "_blank");

                    //clear
                    $('#sel_fg_code').val(null).trigger('change');
                    $('#sel_project_name').val(null).trigger('change');
                    $("#txt_fg_code_gdj_desc").val('');
                    $("#txt_prod_plan").val('');
                    $("#txt_packing_std").val('');
                    $("#txt_tags_total").val('');

                    //load tags
                    _load_tags_details();
                    $("#loadding").modal("hide");


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

        function tokenGen(len) {
            var text = "";
            var charset = "abcdefghijklmnopqrstuvwxyz0123456789";
            for (var i = 0; i < len; i++)
                text += charset.charAt(Math.floor(Math.random() * charset.length));

            return text;
        }

        function openRePrintIndividual(id) {

            window.open("<?= $CFG->src_mPDF; ?>/print_tags?tag=" + id + "", "_blank");
        }

        function openRePrintSet(id) {
            window.open("<?= $CFG->src_mPDF; ?>/print_tags?token=" + id + "", "_blank");
        }

        function openRePrintLot(id) {
            window.open("<?= $CFG->src_mPDF; ?>/print_tag_lot?token=" + id + "", "_blank");
        }

        function _load_tags_details() {
            //Load data
            // setTimeout(function() {
            //     //$("#spn_load_tags_details").html(""); //clear span
            //     $("#spn_load_tags_details").load("<?= $CFG->src_print_tags; ?>/load_tags_details.php");
            // }, 300);
            $.ajax({
                url: "<?= $CFG->src_print_tags; ?>/load_tags_details.php",
                success: function(data) {
                    //console.log(data);
                    var result = JSON.parse(data);
                    callinTable(result);
                }
            });


            function callinTable(data) {
                var table = $("#tbl_print_tagss").DataTable({
                    "bDestroy": true,
                    rowReorder: true,
                    columnDefs: [{
                            orderable: true,
                            className: 'reorder',
                            targets: [3, 4, 5, 6, 7, 8, 9, 10, 11, 12]
                        },
                        {
                            orderable: false,
                            targets: '_all'
                        }
                    ],
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
                            data: 'no'
                        },
                        {
                            "data": null,
                            render: function(data, type, row) {
                                return "<button type='button' class='btn btn-primary btn-sm custom_tooltip' id='" + data["tags_endcode"] + "' onclick='openRePrintIndividual(this.id);'><i class='fa fa-print fa-lg'></i><span class='custom_tooltiptext'>Re-Print this Tag ID</span></button>&nbsp;&nbsp;<button type='button' class='btn btn-info btn-sm custom_tooltip' id='" + data["tags_token_endcode"] + "' onclick='openRePrintSet(this.id);'><i class='fa fa-print fa-lg'></i><span class='custom_tooltiptext'>Re-Print this Lot Token</span></button>&nbsp;&nbsp;<button type='button' class='btn btn-success btn-sm custom_tooltip' id='" + data["tags_token_endcode"] + "' onclick='openRePrintLot(this.id);'><i class='fa fa-print fa-lg'></i><span class='custom_tooltiptext'>Re-Print Tag Lot Token</span></button>"
                            },
                            "targets": -1
                        },
                        {
                            "data": null,
                            render: function(data, type, row) {
                                if (data["receive_status"] != null) {
                                    return "<div style='text-align: center; vertical-align: middle; color: green;'>Received</div>"
                                } else {
                                    return " "
                                }

                            },
                            "targets": -1
                        },
                        {
                            data: 'tags_code'
                        },
                        {
                            data: 'tags_fg_code_gdj'
                        },
                        {
                            data: 'tags_fg_code_gdj_desc'
                        },
                        {
                            data: 'tags_project_name',
                        },
                        {
                            data: 'tags_prod_plan'
                        },
                        {
                            data: 'tags_packing_std'
                        },
                        {
                            data: 'tags_total_qty'
                        },
                        {
                            data: 'tags_token'
                        },
                        {
                            data: 'tags_trading_from'
                        },
                        {
                            data: 'tags_issue_by'
                        },
                        {
                            data: 'tags_issue_datetime'
                        },
                    ]
                });

            }
        }



        var fgObject = $('#sel_fg_code');
        var projectObject = $('#sel_project_name');
        // var abtObject = $('#sel_abt_code');
        // var componentObject = $('#sel_com_code');
        // var shipTypeObject = $('#sel_ship_code');
        // var partCusObject = $('#sel_part_cus');

        // on change project 
        projectObject.on('change', function() {
            var projectId = $('#sel_project_name').val();

            fgObject.html('<option value="">Choose</option>');

            $.get("<?= $CFG->src_print_tags; ?>/get_fg_code.php?projectId=" + escape(projectId) + "", function(data) {
                var result = JSON.parse(data);
                if (Object.keys(result[0]).length == 2) {
                    $.each(result, function(index, item) {
                        if (item.ft2_value == null) {
                            fgObject.append($('<option disabled></option>').val(item.bom_fg_code_gdj).html(item.bom_fg_code_gdj));
                        } else {
                            fgObject.append($('<option></option>').val(item.bom_fg_code_gdj).html(item.bom_fg_code_gdj));
                        }
                    });
                } else {
                    $.each(result, function(index, item) {
                        fgObject.append($('<option></option>').val(item.bom_fg_code_gdj).html(item.bom_fg_code_gdj));
                    });
                }
            });
        });

        // // on change ABT code 
        // fgObject.on('change', function() {
        //     var abtId = $('#sel_fg_code').val();
        //     var projectSend = $('#sel_project_name').val();

        //     componentObject.html('<option value="">Choose</option>');
        //     abtObject.html('<option value="">Choose</option>');
        //     shipTypeObject.html('<option value="">Choose</option>');
        //     partCusObject.html('<option value="">Choose</option>');

        //     $.get("<?= $CFG->src_print_tags; ?>/get_abt_code.php?abtId=" + escape(abtId) + "&projectSend=" + escape(projectSend) + "", function(data) {
        //         var result = JSON.parse(data);
        //         $.each(result, function(index, item) {
        //             abtObject.append(
        //                 $('<option></option>').val(item.bom_fg_code_set_abt).html(item.bom_fg_code_set_abt)
        //             );
        //         });
        //     });
        // });

        // // on change component code 
        // abtObject.on('change', function() {
        //     var componenttId = $('#sel_abt_code').val();

        //     componentObject.html('<option value="">Choose</option>');
        //     shipTypeObject.html('<option value="">Choose</option>');
        //     partCusObject.html('<option value="">Choose</option>');

        //     $.get("<?= $CFG->src_print_tags; ?>/get_component_abt.php?componenttId=" + escape(componenttId) + "", function(data) {
        //         var result = JSON.parse(data);
        //         $.each(result, function(index, item) {
        //             componentObject.append(
        //                 $('<option></option>').val(item.bom_fg_sku_code_abt).html(item.bom_fg_sku_code_abt)
        //             );
        //         });
        //     });
        // });

        // // on change shipType code 
        // componentObject.on('change', function() {
        //     var shipId = $('#sel_com_code').val();

        //     shipTypeObject.html('<option value="">Choose</option>');
        //     partCusObject.html('<option value="">Choose</option>');

        //     $.get("<?= $CFG->src_print_tags; ?>/get_ship_type.php?shipId=" + escape(shipId) + "", function(data) {
        //         var result = JSON.parse(data);
        //         $.each(result, function(index, item) {
        //             shipTypeObject.append(
        //                 $('<option></option>').val(item.bom_ship_type).html(item.bom_ship_type)
        //             );
        //         });
        //     });
        // });

        // // on change partCustomer code 
        // shipTypeObject.on('change', function() {
        //     var partCusId = $('#sel_com_code').val();
        //     var projectSend2 = $('#sel_project_name').val();
        //     var fgSend = $('#sel_fg_code').val();

        //     partCusObject.html('<option value="">Choose</option>');

        //     $.get("<?= $CFG->src_print_tags; ?>/get_part_customer.php?partCusId=" + escape(partCusId) + "&projectSend=" + escape(projectSend2) + "&fgSend=" + escape(fgSend) + "", function(data) {
        //         var result = JSON.parse(data);
        //         $.each(result, function(index, item) {
        //             partCusObject.append(
        //                 $('<option></option>').val(item.bom_part_customer).html(item.bom_part_customer)
        //             );
        //         });
        //     });
        // });
    </script>
</body>

</html>

<!-- Model loading -->
<div class="modal fade" id="loadding" tabindex="-1" role="dialog" aria-labelledby="loadMeLabel" data-keyboard="false" data-backdrop="static">
    <div class="modal-dialog modal-dialog-load  modal-sm" role="document">
        <div class="modal-content">
            <div class="modal-body text-center">
                <div class="loader"></div>
            </div>
        </div>
    </div>
</div>