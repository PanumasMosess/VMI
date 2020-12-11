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
                <h1><i class="fa fa-caret-right"></i>&nbsp;Stock Checking WMS<small>Storage Location</small></h1>
                <ol class="breadcrumb">
                    <li><a href="<?= $CFG->wwwroot; ?>/home"><i class="fa fa-home"></i>Home</a></li>
                    <li class="active">Stock Checking WMS</li>
                </ol>
            </section>

            <!-- Main content -->
            <section class="content">
                <div class="row">
                    <div class="col-xs-12">
                        <div class="box box-info">
                            <div class="box-header with-border">
                                <h3 class="box-title"><i class="fa fa-qrcode"></i> Stock Checking </h3>
                            </div>
                            <!-- /.box-header -->
                            <div class="box-body">
                                <div class="row">
                                    <div class="col-md-6">
                                        <div class="form-group">
                                            <label><img src="<?= $CFG->iconsdir; ?>/Ecommerce-Barcode-Scanner-icon.png" height="16px"> TAG ID :</label>
                                            <input type="text" id="txt_tag_id" name="txt_tag_id" onkeypress='return _onScan_checking_code(event)' class="form-control input-lg" placeholder="Scan Tag ID">
                                        </div>
                                        <!-- /.form-group -->
                                    </div>
                                    <!-- /.col -->
                                    <div class="col-md-6">
                                        <div class="form-group">
                                            <label>FG Code GDJ.<span id="spn_load_FG_code"></span></label>
                                            <input type="text" id="txt_FGCode_GDJ" name="txt_FGCode_GDJ" class="form-control" placeholder="FG Code GDJ." disabled>
                                        </div>
                                        <!-- /.form-group -->
                                    </div>
                                    <!-- /.col -->
                                </div>
                                <div class="row">
                                    <div class="col-md-6">
                                        <div class="form-group">
                                            <label style="visibility: hidden">Store Current Qty.</label>
                                            <input type="text" id="txt_curent_qty" name="txt_curent_qty" style="visibility: hidden" class="form-control" maxlength="4" placeholder="Enter Curent Qty.">
                                        </div>
                                        <!-- /.form-group -->
                                    </div>
                                    <!-- /.col -->
                                    <div class="col-md-6">
                                        <div class="form-group">
                                            <label>Stock WMS Qty.</label>
                                            <input type="text" id="txt_stock_wms_qty" name="txt_stock_wms_qty" class="form-control" maxlength="5" placeholder="Stock WMS Qty." disabled>
                                        </div>
                                        <!-- /.form-group -->
                                    </div>
                                    <!-- /.col -->
                                    <div class="col-md-6">
                                        <div class="form-group">
                                            <!-- <label>Location.</label>
                                            <input type="text" id="txt_location" name="txt_location" class="form-control"  placeholder="Location." disabled> -->
                                        </div>
                                        <!-- /.form-group -->
                                    </div>
                                    <!-- /.col -->
                                    <div class="col-md-6">
                                        <div class="form-group">
                                            <label>Location.</label>
                                            <input type="text" id="txt_location" name="txt_location" class="form-control" placeholder="Location." disabled>
                                        </div>
                                        <!-- /.form-group -->
                                    </div>
                                    <!-- /.col -->
                                </div>

                            </div>
                            <!-- /.box-body -->
                            <!-- <div class="box-footer">
                                <button type="hidden" class="btn btn-primary btn-sm" onclick="confirm_checkStock()"><i class="fa fa-check"></i> Confirm Check Stock ID</button>
                            </div> -->
                        </div>
                        <!-- /.box -->
                        <div class="row">
                            <div class="col-xs-12">
                                <div class="box box-warning">
                                    <div class="box-header with-border">
                                        <h3 class="box-title"><i class="fa fa-list"></i> Stock Checking WMS List </h3>
                                    </div>
                                    <!-- /.box-header -->

                                    <div class="box-header">
                                        <button type="button" class="btn btn-danger btn-sm" onclick="confSelDelete();"><i class="fa fa-trash fa-lg"></i> Delete</button>
                                        &nbsp;|&nbsp;<button type="button" class="btn btn-info btn-sm" onclick="_load_list_stock_CheckOK();"><i class="fa fa-refresh fa-lg"></i> Refresh</button>
                                    </div>
                                    <div style="padding-left: 8px;">
                                        <i class="fa fa-filter" style="color: #00F;"></i>
                                        <font style="color: #00F;">SQL >_ SELECT * ROWS</font>
                                    </div>
                                    <!-- /.box-header -->
                                    <span id="spn_load_list"></span>
                                </div>
                                <!-- /.box -->
                            </div>
                            <!-- /.col -->
                        </div>
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
            $("#txt_tag_id").focus();
            var stock_pallet_code = '';
            var stock_tags_code = '';
            var stock_tags_fg_code_gdj = '';
            var stock_tags_packing_std = '';
            var stock_status;
            var stock_location = '';

            $("#spn_load_list").load("<?= $CFG->src_location_check; ?>/load_stock_checking_list_wms.php");

            // display: none
        });

        //check eng only
        function isEnglishchar(str) {
            var orgi_text = "abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ1234567890._-";
            var str_length = str.length;
            var isEnglish = true;
            var Char_At = "";
            for (i = 0; i < str_length; i++) {
                Char_At = str.charAt(i);
                if (orgi_text.indexOf(Char_At) == -1) {
                    isEnglish = false;
                    break;
                }
            }
            return isEnglish;
        }

        //gen token
        var str_token = tokenGen(30);



        function _onScan_checking_code(even) {

            if (even.keyCode == 13) {
                if (document.getElementById('txt_tag_id').value == "") {
                    swal({
                        html: true,
                        title: "<span style='font-size: 30px; font-weight: bold;'>Warning !!!</span>",
                        text: "<span style='font-size: 15px; color: #000;'>[C001] --- Please Scan Tag ID</span>",
                        type: "warning",
                        timer: 2500,
                        showConfirmButton: false,
                        allowOutsideClick: false
                    });
                    //hide
                    setTimeout(function() {
                        document.form.txt_tag_id.focus();
                    }, 2500);

                    return false;

                } else {
                    $.ajax({
                        type: 'POST',
                        async: false,
                        url: '<?= $CFG->src_location_check; ?>/tag_code_des_wms.php',
                        data: {
                            tag_code: $("#txt_tag_id").val(),
                            iden_token: str_token
                        },
                        success: function(response) {
                            var arry = $.parseJSON(response);
                            if (arry == 'NULL') {
                                swal({
                                    html: true,
                                    title: "<span style='font-size: 30px; font-weight: bold;'>Warning !!!</span>",
                                    text: "<span style='font-size: 15px; color: #000;'>[C001] ---Tag ID Not Match</span>",
                                    type: "error",
                                    timer: 2000,
                                    showConfirmButton: false,
                                    allowOutsideClick: false
                                });
                                $("#txt_FGCode_GDJ").val('');
                                $("#txt_stock_wms_qty").val('');
                                $("#txt_location").val('');
                                stock_pallet_code = '';
                                stock_tags_code = '';
                                stock_tags_fg_code_gdj = '';
                                stock_tags_packing_std = '';
                                stock_status = '';
                                stock_location = '';
                                $("#txt_tag_id").val('');

                            } else {

                                $("#txt_FGCode_GDJ").val(arry[0]);
                                $("#txt_stock_wms_qty").val(arry[1]);
                                $("#txt_location").val(arry[2]);
                                stock_pallet_code = arry[3];
                                stock_tags_code = $("#txt_tag_id").val();
                                stock_tags_fg_code_gdj = arry[0];
                                stock_tags_packing_std = arry[1];
                                stock_status = "Match";
                                stock_location = arry[2];


                                if (stock_status == '') {
                                    stock_pallet_code = null;
                                    stock_tags_code = $("#txt_tag_id").val();
                                    stock_tags_fg_code_gdj = null;
                                    stock_tags_packing_std = null;
                                    stock_status = 'Not Match';
                                    stock_location = null;
                                }

                                $.ajax({
                                    type: 'POST',
                                    async: false,
                                    url: '<?= $CFG->src_location_check; ?>/insert_checking_stock_list_wms.php',
                                    data: {
                                        palate_code: stock_pallet_code,
                                        tags_code: stock_tags_code,
                                        fg_code: stock_tags_fg_code_gdj,
                                        qty_stock: stock_tags_packing_std,
                                        status_stock: stock_status,
                                        location_stock: stock_location,
                                    },
                                    success: function(response) {
                                        if (response == "DUPLICATE") {
                                            swal({
                                                html: true,
                                                title: "<span style='font-size: 30px; font-weight: bold;'>Warning !!!</span>",
                                                text: "<span style='font-size: 15px; color: #000;'>[C001] --- Tag Code is Duplicate</span>",
                                                type: "warning",
                                                timer: 1000,
                                                showConfirmButton: false,
                                                allowOutsideClick: false
                                            });
                                            $("#txt_FGCode_GDJ").val('');
                                            $("#txt_stock_wms_qty").val('');
                                            $("#txt_location").val('');
                                            stock_pallet_code = '';
                                            stock_tags_code = '';
                                            stock_tags_fg_code_gdj = '';
                                            stock_tags_packing_std = '';
                                            stock_status = '';
                                            stock_location = '';

                                        } else if (response == "INSERT_OK") {
                                            setTimeout(function() {
                                                swal({
                                                    html: true,
                                                    title: "<span style='font-size: 30px; font-weight: bold;'>Ckeck Stock !!!</span>",
                                                    text: "<span style='font-size: 15px; color: #000;'>[C001] --- Insert Data is OK</span>",
                                                    type: "success",
                                                    timer: 1000,
                                                    showConfirmButton: false,
                                                    allowOutsideClick: false
                                                });
                                                $("#txt_FGCode_GDJ").val('');
                                                $("#txt_stock_wms_qty").val('');
                                                $("#txt_location").val('');
                                                $("#txt_tag_id").val('');
                                                stock_pallet_code = '';
                                                stock_tags_code = '';
                                                stock_tags_fg_code_gdj = '';
                                                stock_tags_packing_std = '';
                                                stock_status = '';
                                                stock_location = '';
                                                _load_list_stock_CheckOK();
                                            }, 700);
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
            }
        }

        function tokenGen(len) {
            var text = "";
            var charset = "abcdefghijklmnopqrstuvwxyz0123456789";
            for (var i = 0; i < len; i++)
                text += charset.charAt(Math.floor(Math.random() * charset.length));

            return text;
        }

        function confSelDelete() {

            var cbChecked = $("input[name='_chk_del_check_stock[]']:checked").length;
            if (cbChecked == 0) {
                swal({
                    html: true,
                    title: "<span style='font-size: 15px; font-weight: bold;'>Warning !!!</span>",
                    text: "<span style='font-size: 15px; color: #000;'>[D00] ---Please select at least 1 item !!! </span>",
                    type: "warning",
                    timer: 2000,
                    showConfirmButton: false,
                    allowOutsideClick: false
                });
            } else {

                //dialog ctrl
                swal({
                        html: true,
                        title: "<span style='font-size: 15px; font-weight: bold;'>Warning !!!</span>",
                        text: "<span style='font-size: 15px; color: #000;'>You want to <b>delete</b> checking  all selected ?</span>",
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

                            var cbChecked = $("input[name='_chk_del_check_stock[]']:checked").length;

                            //conf
                            var tmp = 0;

                            $("input[name='_chk_del_check_stock[]']:checked").each(function() {
                                //count for alert not select item
                                tmp = tmp + 1;

                                var data_tag_id = $(this).val();
                                $.ajax({
                                    type: 'POST',
                                    url: '<?= $CFG->src_location_check; ?>/del_checking_select.php',
                                    data: {
                                        data_tag_id_: data_tag_id
                                    },
                                    success: function(response) {
                                        //
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
                                if (tmp == 0) {

                                } else {
                                    //refresh
                                    if (cbChecked == tmp) {
                                        _load_list_stock_CheckOK();
                                    }
                                }
                            });

                        }
                    });
            }
        }


        // function confirm_checkStock() {
        //     if (document.getElementById('txt_tag_id').value == "") {
        //         swal({
        //             html: true,
        //             title: "<span style='font-size: 30px; font-weight: bold;'>Warning !!!</span>",
        //             text: "<span style='font-size: 15px; color: #000;'>[C001] --- Please Scan Tag ID</span>",
        //             type: "warning",
        //             timer: 2500,
        //             showConfirmButton: false,
        //             allowOutsideClick: false
        //         });

        //     } else {
        //         swal({
        //                 html: true,
        //                 title: "<span style='font-size: 17px;'>[C003] --- Do you want to confirm check ?</span>",
        //                 text: "",
        //                 type: 'warning',
        //                 showCancelButton: true,
        //                 confirmButtonColor: '#3085d6',
        //                 cancelButtonColor: '#d33',
        //                 confirmButtonText: 'Yes',
        //                 closeOnConfirm: false
        //             },
        //             function(isConfirm) {

        //                 if (isConfirm) {
        //                     if (stock_status == '') {
        //                         stock_pallet_code = null;
        //                         stock_tags_code = $("#txt_tag_id").val();
        //                         stock_tags_fg_code_gdj = null;
        //                         stock_tags_packing_std = null;
        //                         stock_status = 'Not Match';
        //                         stock_location = null;
        //                     }

        //                     $.ajax({
        //                         type: 'POST',
        //                         async: false,
        //                         url: '<?= $CFG->src_location_check; ?>/insert_checking_stock_list_wms.php',
        //                         data: {
        //                             palate_code: stock_pallet_code,
        //                             tags_code: stock_tags_code,
        //                             fg_code: stock_tags_fg_code_gdj,
        //                             qty_stock: stock_tags_packing_std,
        //                             status_stock: stock_status,
        //                             location_stock: stock_location,
        //                         },
        //                         success: function(response) {
        //                             if (response == "DUPLICATE") {
        //                                 swal({
        //                                     html: true,
        //                                     title: "<span style='font-size: 30px; font-weight: bold;'>Warning !!!</span>",
        //                                     text: "<span style='font-size: 15px; color: #000;'>[C001] --- Stock Data is Duplicate</span>",
        //                                     type: "warning",
        //                                     timer: 2500,
        //                                     showConfirmButton: false,
        //                                     allowOutsideClick: false
        //                                 });
        //                                 $("#txt_FGCode_GDJ").val('');
        //                                 $("#txt_stock_wms_qty").val('');
        //                                 $("#txt_location").val('');
        //                                 stock_pallet_code = '';
        //                                 stock_tags_code = '';
        //                                 stock_tags_fg_code_gdj = '';
        //                                 stock_tags_packing_std = '';
        //                                 stock_status = '';
        //                                 stock_location = '';

        //                             } else if (response == "INSERT_OK") {
        //                                 swal({
        //                                     html: true,
        //                                     title: "<span style='font-size: 30px; font-weight: bold;'>Confirm Stock !!!</span>",
        //                                     text: "<span style='font-size: 15px; color: #000;'>[C001] --- Confirm Stock Data OK</span>",
        //                                     type: "success",
        //                                     timer: 2500,
        //                                     showConfirmButton: false,
        //                                     allowOutsideClick: false
        //                                 });
        //                                 $("#txt_FGCode_GDJ").val('');
        //                                 $("#txt_stock_wms_qty").val('');
        //                                 $("#txt_location").val('');
        //                                 $("#txt_tag_id").val('');
        //                                 stock_pallet_code = '';
        //                                 stock_tags_code = '';
        //                                 stock_tags_fg_code_gdj = '';
        //                                 stock_tags_packing_std = '';
        //                                 stock_status = '';
        //                                 stock_location = '';
        //                             }
        //                             _load_list_stock_CheckOK();
        //                         },
        //                         error: function() {
        //                             //dialog ctrl
        //                             swal({
        //                                 html: true,
        //                                 title: "<span style='font-size: 15px; font-weight: bold;'>Warning !!!</span>",
        //                                 text: "<span style='font-size: 15px; color: #000;'>[D002] --- Ajax Error !!! Cannot operate</span>",
        //                                 type: "warning",
        //                                 timer: 3000,
        //                                 showConfirmButton: false,
        //                                 allowOutsideClick: false
        //                             });
        //                         }
        //                     });
        //                 }
        //             }
        //         );

        //     }


        // }

        function _load_list_stock_CheckOK() {
            //Load data
            setTimeout(function() {
                $("#spn_load_list").load("<?= $CFG->src_location_check; ?>/load_stock_checking_list_wms.php");
            }, 300);
        }

        function _export_stock_check_by_tags() {
            //href
            window.open('<?= $CFG->src_location_check; ?>/excel_stock_checking_by_tags_wms', '_blank');
        }

        function _export_stock_check_by_FG_code() {
            //href
            window.open('<?= $CFG->src_location_check; ?>/excel_stock_checking_by_FG_wms', '_blank');
        }

        function _remove_stock_temp() {
            swal({
                html: true,
                title: "<span style='font-size: 17px;'>[C003] --- Do you want to delete All ?</span>",
                text: "",
                type: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#3085d6',
                cancelButtonColor: '#d33',
                confirmButtonText: 'Yes',
                closeOnConfirm: false
            }, function(isConfirm) {
                if (isConfirm) {
                    $.ajax({
                        type: 'POST',
                        async: false,
                        url: '<?= $CFG->src_location_check; ?>/remove_checking_stock_list_wms.php',
                        data: {},
                        success: function(response) {
                            if (response == "DELETE_OK") {
                                swal({
                                    html: true,
                                    title: "<span style='font-size: 15px; font-weight: bold;'>Success !!!</span>",
                                    text: "<span style='font-size: 15px; color: #000;'>Delete Stock Checking All !!!</span>",
                                    type: "success",
                                    timer: 2500,
                                    showConfirmButton: false,
                                    allowOutsideClick: false
                                });
                                _load_list_stock_CheckOK()
                            }
                        },
                        error: function() {
                            //dialog ctrl
                            swal({
                                html: true,
                                title: "<span style='font-size: 15px; font-weight: bold;'>Warning !!!</span>",
                                text: "<span style='font-size: 15px; color: #000;'>[D002] --- Ajax Error !!! Cannot operate</span>",
                                type: "warning",
                                timer: 2700,
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