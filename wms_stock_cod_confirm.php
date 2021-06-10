<?
require_once("application.php");
require_once("js_css_header.php");
?>

<link href="https://www.jqueryscript.net/css/jquerysctipttop.css" rel="stylesheet" type="text/css">
<link href="dist/assets/jquery.signaturepad.css" rel="stylesheet">

<!DOCTYPE html>
<html>

<style>
    /** SPINNER CREATION **/

    .loader {
        position: relative;
        text-align: center;
        margin: 15px auto 35px auto;
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

    div.transbox {
        margin: 30px;
        background-color: #ffffff;
        border: 1px solid #ffffff;
        background-color: hsla(0, 0%, 100%, 0.70);
        border-radius: 25px;
    }

    /*css botton*/
    .button {
        cursor: not-allowed;
        padding: 15px 25px;
        font-size: 22px;
        text-align: center;
        cursor: pointer;
        outline: none;
        color: #000;
        background-color: #FFA500;
        border: none;
        border-radius: 15px;
        box-shadow: 0 6px #DDD;
    }

    .button:hover {
        background-color: #FFA500
    }

    .button:active {
        background-color: #FFA500;
        box-shadow: 0 5px #DDD;
        transform: translateY(4px);
    }

    .btn:focus {
        outline: none !important;
    }

    .clearButton {
        cursor: not-allowed;
        padding: 15px 25px;
        font-size: 24px;
        text-align: center;
        cursor: pointer;
        outline: none;
        color: #000;
        background-color: #F8F8F8;
        border: none;
        border-radius: 5px;
        box-shadow: 0 2px #DDD;
    }

    .clearButton:hover {
        background-color: #F8F8F8
    }

    .clearButton:active {
        background-color: #F8F8F8;
        box-shadow: 0 2px #DDD;
        transform: translateY(4px);
    }
</style>

<body class="hold-transition skin-blue sidebar-mini">
    <div class="wrapper">

        <?
	require_once("menu.php");
  ?>
        <!-- Content Wrapper. Contains page content -->
        <div class="content-wrapper">
            <!-- Content Header (Page header) -->
            <section class="content-header">
                <h1><i class="fa fa-caret-right"></i>&nbsp;Stock Replenishment<small>Storage Location</small></h1>
                <ol class="breadcrumb">
                    <li><a href="<?= $CFG->wwwroot; ?>/home"><i class="fa fa-home"></i>Home</a></li>
                    <li class="active">Stock Replenishment</li>
                </ol>
            </section>
            <!-- Main content -->
            <section class="content">
                <div class="row">
                    <div class="col-xs-12">
                        <div class="box box-success">
                            <div class="box-header with-border">
                                <h3 class="box-title"><i class="fa fa-qrcode"></i> Scan Driver ID Card + DTN Code</h3>
                            </div>
                            <!-- /.box-header -->
                            <!-- /.content -->
                            <div class="box-body">
                                <div class="row">
                                    <div class="col-md-4">
                                        <div class="form-group">
                                            <label><img src="<?= $CFG->iconsdir; ?>/Very-Basic-Home-icon.png" height="16px"> Project Location:</label>
                                            <select class="form-control input-lg" name="txt_sel_pro" id="txt_sel_pro" style="width: 100%; background-color:#f4f4f4" onInput='_onselect_Project()'>
                                                <option selected="selected" value="">Select Project</option>
                                                <?
										$strSQL = " SELECT [bom_pj_name] FROM tbl_bom_mst left join tbl_pod_check on tbl_bom_mst.bom_pj_name = tbl_pod_check.pod_project where pod_type = 'POD' group by [bom_pj_name] order by [bom_pj_name] asc ";
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
                                        <!-- /.form-group -->
                                    </div>
                                    <!-- /.col -->
                                    <div class="col-md-4">
                                        <div class="form-group">
                                            <label><img src="<?= $CFG->iconsdir; ?>/Ecommerce-Barcode-Scanner-icon.png" height="16px"> Driver Identification Card:</label>
                                            <input type="password" id="txt_scn_driver_id" name="txt_scn_driver_id" onKeyPress="if (event.keyCode==13){ return _onScan_Driver(); }" class="form-control input-lg" placeholder="Scan Driver Identification Card" autocomplete="off" autocorrect="off" spellcheck="false" disabled>
                                        </div>
                                        <!-- /.form-group -->
                                    </div>
                                    <!-- /.col -->

                                    <div class="col-md-4">
                                        <div class="form-group">
                                            <label><img src="<?= $CFG->iconsdir; ?>/Ecommerce-Barcode-Scanner-icon.png" height="16px"> Delivery Transfer Note (DTN ID.):</label>
                                            <input type="text" id="txt_scn_dtn_id" name="txt_scn_dtn_id" onKeyPress="if (event.keyCode==13){ return _onScan_DTN(); }" class="form-control input-lg" placeholder="Scan DTN ID." autocomplete="off" autocorrect="off" spellcheck="false" disabled>
                                        </div>
                                        <!-- /.form-group -->
                                    </div>
                                    <!-- /.col -->
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="row">
                    <div class="col-md-12">
                        <div class="box box-warning" style="height: 650px;">
                            <div class="box-header with-border">
                                <h3 class="box-title"><i class="fa fa-cart-arrow-down fa-lg"></i> REPLENISHMENT LIST</h3>
                            </div>
                            <!-- /.box-header -->

                            <div class="box-body">
                                <div style="height: 590px; overflow:auto; alignment-adjust:central;">
                                    <span id="spn_load_dtn_sheet_details"></span>
                                </div>
                            </div>

                        </div>
                        <!-- /.box -->
                    </div>
                    <!-- /.col -->
                </div>
                <div class="row">
                    <div class="col-md-12">
                        <div class="box box-warning">
                            <div class="box-header with-border">
                                <h3 class="box-title"><i class="fa fa-pencil fa-lg"></i> SIGNATURE TO CONFIRM</h3>
                            </div>
                            <!-- /.box-header -->

                            <div class="box-body">
                                <div class="row">
                                    <div class="col-md-6">
                                        <div class="form-group">
                                            <div class="sigPad" id="smoothed-DriverCanvas" style="width:100%;">
                                                <ul class="sigNav">
                                                    <li class="drawIt"><a href="#draw-it">Driver Sign</a></li>
                                                    <li class="clearButton"><a href="#clear">Clear</a></li>
                                                </ul>
                                                <div class="sig sigWrapper" style="height:auto;">
                                                    <div class="typed"></div>
                                                    <canvas class="pad" id="DriverCanvas" width="450" height="270"></canvas>
                                                </div>
                                            </div>
                                        </div>
                                        <!-- /.form-group -->
                                    </div>
                                    <!-- /.col -->

                                    <div class="col-md-6">
                                        <div class="form-group">
                                            <div class="sigPad" id="smoothed-CustomerCanvas" style="width:100%;">
                                                <ul class="sigNav">
                                                    <li class="drawIt"><a href="#draw-it">Customer Sign</a></li>
                                                    <li class="clearButton"><a href="#clear">Clear</a></li>
                                                </ul>
                                                <div class="sig sigWrapper" style="height:auto;">
                                                    <div class="typed"></div>
                                                    <canvas class="pad" id="CustomerCanvas" width="450" height="270"></canvas>
                                                </div>
                                            </div>
                                        </div>
                                        <!-- /.form-group -->
                                    </div>
                                    <!-- /.col -->
                                </div>
                            </div>
                        </div>
                        <!-- /.box -->
                    </div>
                    <!-- /.col -->
                </div>
                <div class="row">
                    <div class="col-md-6">
                        <!-- /.col -->
                    </div>
                    <div class="col-md-6">
                        <div class="transbox">
                            <p style="padding: 20px;">
                                <button onclick="conf_replenishment()" type="button" style="padding: 10px;" class="button btn btn-block btn-warning btn-lg">
                                    <font style="font-size: 30px; color: #000;"><i class="fa fa-check-circle-o fa-lg"></i> CONFIRM REPLENISHMENT</font>
                                </button>
                            </p>
                        </div>
                    </div>
                    <!-- /.col -->
                </div>

            </section>

        </div>
        <!-- /.col -->

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

</body>
<!--<script src="https://ajax.googleapis.com/ajax/libs/jquery/1.10.2/jquery.min.js"></script>-->
<script src="dist/assets/numeric-1.2.6.min.js"></script>
<script src="dist/assets/bezier.js"></script>
<script src="dist/jquery.signaturepad.js"></script>

<script language="javascript">
    //Onload this page
    $(document).ready(function() {
        //*********************************************************************************/
        /*stage 1 load default ************************************************************/
        //$("#spn_datetime").load('src_file/load_alert/load_datetime.php?randval='+ Math.random());

        //*****************************************************************************/
        /*stage 2 auto run ************************************************************/
        //$("#spn_datetime").html("0");
        var refreshId1 = setInterval(function() {
            //load data and config
            //$("#spn_datetime").load('src_file/load_alert/load_datetime.php?randval='+ Math.random());

        }, 1000);
        $.ajaxSetup({
            cache: false
        });

        //toUpperCase
        $('#txt_scn_driver_id').keyup(function() {
            this.value = this.value.toUpperCase();
        });
        $('#txt_scn_dtn_id').keyup(function() {
            this.value = this.value.toUpperCase();
        });

        //auto focus
        $('#txt_scn_driver_id').focus();

        //signature
        $('#smoothed-DriverCanvas').signaturePad({
            drawOnly: true,
            drawBezierCurves: true,
            lineTop: 250
        });
        $('#smoothed-CustomerCanvas').signaturePad({
            drawOnly: true,
            drawBezierCurves: true,
            lineTop: 250
        });

        _load_replenish_details();

    });

    function _onScan_CheckTags() {
        if ($("#txt_qc_scn_tag_id").val() != "") {
            if (isEnglishchar($("#txt_qc_scn_tag_id").val()) == false) {
                //dialog ctrl
                swal({
                    html: true,
                    title: "<span style='font-size: 15px; font-weight: bold;'>Warning !!!</span>",
                    text: "<span style='font-size: 15px; color: #000;'>[C001] --- Please change to english language.</span>",
                    type: "warning",
                    timer: 2500,
                    showConfirmButton: false,
                    allowOutsideClick: false
                });

                //hide
                setTimeout(function() {
                    $("#txt_qc_scn_tag_id").val('');
                    $("#txt_qc_scn_tag_id").focus();
                }, 2900);

                return false;
            } else {
                //post check data
                $.ajax({
                    type: 'POST',
                    url: '<?= $CFG->src_wms_special; ?>/validate_replenish_qc.php',
                    data: {
                        iden_pj_name: $("#txt_sel_pro").val(),
                        iden_txt_scn_driver_id: $("#txt_scn_driver_id").val(),
                        iden_txt_scn_dtn_id: $("#txt_scn_dtn_id").val(),
                        iden_txt_qc_scn_tag_id: $("#txt_qc_scn_tag_id").val()
                    },
                    success: function(response) {

                        if (response == "OK") {
                            _load_replenish_details();
                        } else if (response == "NG") {
                            //dialog ctrl
                            swal({
                                html: true,
                                title: "<span style='font-size: 15px; font-weight: bold;'>Warning !!!</span>",
                                text: "<span style='font-size: 15px; color: #000;'>[C002] --- Incorrect data, <br>TAGS ID. Does not match !!!</span>",
                                type: "error",
                                timer: 4000,
                                showConfirmButton: false,
                                allowOutsideClick: false
                            });

                            _load_replenish_details();

                            //clear
                            $('#txt_qc_scn_tag_id').val('');
                            $('#txt_qc_scn_tag_id').focus();
                        } else if (response == "DUL") {
                            //dialog ctrl
                            swal({
                                html: true,
                                title: "<span style='font-size: 15px; font-weight: bold;'>Warning !!!</span>",
                                text: "<span style='font-size: 15px; color: #000;'>[C002] --- Duplicate TAGS ID. !!!</span>",
                                type: "error",
                                timer: 4000,
                                showConfirmButton: false,
                                allowOutsideClick: false
                            });

                            _load_replenish_details();

                            //clear
                            $('#txt_qc_scn_tag_id').val('');
                            $('#txt_qc_scn_tag_id').focus();
                        }

                    },
                    error: function() {
                        //dialog ctrl
                        swal({
                            html: true,
                            title: "<span style='font-size: 15px; font-weight: bold;'>Warning !!!</span>",
                            text: "<span style='font-size: 15px; color: #000;'>[D002] --- Ajax Error !!! Cannot operate</span>",
                            type: "warning",
                            timer: 4000,
                            showConfirmButton: false,
                            allowOutsideClick: false
                        });
                    }
                });

            }
        }
    }

    function _load_dtn_sign_details() {
        setTimeout(function() {
            //$("#spn_load_dtn_details").html(""); //clear span
            $("#spn_load_dtn_details").load("<?= $CFG->src_wms_special; ?>/dtn_sheet.php", {
                tag: $("#txt_scn_dtn_id").val()
            });
        }, 300);
    }

    function _load_replenish_details() {
        //load dtn
        setTimeout(function() {
            //$("#spn_load_dtn_sheet_details").html(""); //clear span
            $("#spn_load_dtn_sheet_details").load("<?= $CFG->src_wms_special; ?>/load_dtn_sheet_details_popup.php", {
                t_pj_name: $('#txt_sel_pro').val(),
                t_txt_scn_driver_id: $('#txt_scn_driver_id').val(),
                t_txt_scn_dtn_id: $('#txt_scn_dtn_id').val()
            });
        }, 300);


    }

    function _onselect_Project() {
        if ($("#txt_sel_pro").val() != "") {
            $('#txt_scn_driver_id').prop('disabled', false);
            //clear
            $('#txt_scn_driver_id').val('');
            $('#txt_scn_driver_id').focus();
        } else if ($("#txt_sel_pro").val() == "") {
            $('#txt_scn_driver_id').prop('disabled', true);
            //clear
            $('#txt_scn_driver_id').val('');


            $('#txt_scn_dtn_id').prop('disabled', true);
            //clear
            $('#txt_scn_dtn_id').val('');
            $('#txt_sel_pro').focus();
        }
    }

    function _onScan_Driver() {
        if ($("#txt_scn_driver_id").val() != "") {
            if (isEnglishchar($("#txt_scn_driver_id").val()) == false) {
                //dialog ctrl
                swal({
                    html: true,
                    title: "<span style='font-size: 15px; font-weight: bold;'>Warning !!!</span>",
                    text: "<span style='font-size: 15px; color: #000;'>[C001] --- Please change to english language.</span>",
                    type: "warning",
                    timer: 2500,
                    showConfirmButton: false,
                    allowOutsideClick: false
                });

                //hide
                setTimeout(function() {
                    $("#txt_scn_driver_id").val('');
                    $("#txt_scn_driver_id").focus();
                }, 2900);

                return false;
            } else {
                //check driver on table
                $.ajax({
                    type: 'POST',
                    url: '<?= $CFG->src_wms_special; ?>/load_check_driver_details.php',
                    data: {
                        t_txt_dtn_scan_driver_iden_card: $("#txt_scn_driver_id").val()
                    },
                    success: function(response) {

                        if (response == "OK") {
                            //clear
                            //disabled
                            $('#txt_scn_dtn_id').prop('disabled', false);
                            //clear
                            $('#txt_scn_dtn_id').val('');
                            $('#txt_scn_dtn_id').focus();
                        } else if (response == "NG") {
                            //dialog ctrl
                            swal({
                                html: true,
                                title: "<span style='font-size: 15px; font-weight: bold;'>Warning !!!</span>",
                                text: "<span style='font-size: 15px; color: #000;'>[C002] --- Driver ID not matching on database</span>",
                                type: "error",
                                timer: 3000,
                                showConfirmButton: false,
                                allowOutsideClick: false
                            });

                            $("#txt_scn_driver_id").val('');
                            $('#txt_scn_dtn_id').prop('disabled', false);
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

    function _onScan_DTN() {
        if ($("#txt_scn_dtn_id").val() != "") {
            if (isEnglishchar($("#txt_scn_dtn_id").val()) == false) {
                //dialog ctrl
                swal({
                    html: true,
                    title: "<span style='font-size: 15px; font-weight: bold;'>Warning !!!</span>",
                    text: "<span style='font-size: 15px; color: #000;'>[C001] --- Please change to english language.</span>",
                    type: "warning",
                    timer: 2500,
                    showConfirmButton: false,
                    allowOutsideClick: false
                });

                //hide
                setTimeout(function() {
                    $("#txt_scn_dtn_id").val('');
                    $("#txt_scn_dtn_id").focus();
                }, 2900);

                return false;
            } else {
                //post check data
                $.ajax({
                    type: 'POST',
                    url: '<?= $CFG->src_wms_special; ?>/validate_replenish.php',
                    data: {
                        iden_pj_name: $("#txt_sel_pro").val(),
                        iden_txt_scn_driver_id: $("#txt_scn_driver_id").val(),
                        iden_txt_scn_dtn_id: $("#txt_scn_dtn_id").val()
                    },
                    success: function(response) {

                        if (response == "OK") {
                            _load_replenish_details();
                        } else if (response == "NG") {
                            //dialog ctrl
                            swal({
                                html: true,
                                title: "<span style='font-size: 15px; font-weight: bold;'>Warning !!!</span>",
                                text: "<span style='font-size: 15px; color: #000;'>[C002] --- Incorrect data, <br>Driver ID or DTN ID. Does not match !!!</span>",
                                type: "error",
                                timer: 4000,
                                showConfirmButton: false,
                                allowOutsideClick: false
                            });

                            _load_replenish_details();

                            //disabled
                            $('#txt_scn_dtn_id').prop('disabled', true);
                            //clear
                            $('#txt_scn_dtn_id').val('');
                            $('#txt_scn_driver_id').val('');
                            $('#txt_scn_driver_id').focus();
                        }

                    },
                    error: function() {
                        //dialog ctrl
                        swal({
                            html: true,
                            title: "<span style='font-size: 15px; font-weight: bold;'>Warning !!!</span>",
                            text: "<span style='font-size: 15px; color: #000;'>[D002] --- Ajax Error !!! Cannot operate</span>",
                            type: "warning",
                            timer: 4000,
                            showConfirmButton: false,
                            allowOutsideClick: false
                        });
                    }
                });

            }
        }
    }

    function conf_replenishment() {
        var driver_canvas = $("#DriverCanvas").get(0);
        var driver_imgData = driver_canvas.toDataURL();

        var _loading = false;

        var cus_canvas = $("#CustomerCanvas").get(0);
        var cus_imgData = cus_canvas.toDataURL();

        //check null
        if ($("#txt_scn_driver_id").val() == "") {
            //dialog ctrl
            swal({
                html: true,
                title: "<span style='font-size: 15px; font-weight: bold;'>Warning !!!</span>",
                text: "<span style='font-size: 15px; color: #000;'>Please Scan Driver Identification Card</span>",
                type: "warning",
                timer: 2500,
                showConfirmButton: false,
                allowOutsideClick: false
            });

            setTimeout(function() {
                $("#txt_scn_driver_id").val('');
                $("#txt_scn_driver_id").focus();
            }, 2900);

            return false;
        } else if ($("#txt_scn_dtn_id").val() == "") {
            //dialog ctrl
            swal({
                html: true,
                title: "<span style='font-size: 15px; font-weight: bold;'>Warning !!!</span>",
                text: "<span style='font-size: 15px; color: #000;'>Please Scan DTN ID.</span>",
                type: "warning",
                timer: 2500,
                showConfirmButton: false,
                allowOutsideClick: false
            });

            setTimeout(function() {
                $("#txt_scn_dtn_id").val('');
                $("#txt_scn_dtn_id").focus();
            }, 2900);

            return false;
        } else if ($("#hdn_row_DTNSheetDetails").val() == 0) {
            //dialog ctrl
            swal({
                html: true,
                title: "<span style='font-size: 15px; font-weight: bold;'>Warning !!!</span>",
                text: "<span style='font-size: 15px; color: #000;'>[I004] --- No data available in table !!!</span>",
                type: "error",
                timer: 2500,
                showConfirmButton: false,
                allowOutsideClick: false
            });

            return false;
        } else if ((parseInt($("#hdn_row_DTNSheetDetails").val()) >= 3) && (parseInt($("#hdn_row_ChkCompleteScan").val()) < 3)) {
            //call
            // var row_check = parseInt($("#hdn_row_DTNSheetDetails").val());
           
            var scan_check = parseInt($("#hdn_row_ChkCompleteScan").val());
            var str_minus = 3 - scan_check;

            //Scan is not finished, missing 2 items
            //dialog ctrl
            swal({
                html: true,
                title: "<span style='font-size: 15px; font-weight: bold;'>Warning !!!</span>",
                text: "<span style='font-size: 15px; color: #000;'>[C003] --- Scan is not finished, missing " + str_minus + " items</span>",
                type: "error",
                timer: 2500,
                showConfirmButton: false,
                allowOutsideClick: false
            });

            setTimeout(function() {
                $("#txt_qc_scn_tag_id").val('');
                $("#txt_qc_scn_tag_id").focus();
            }, 2900);

            return false;
        } else if (driver_imgData.length == 3614) //null
        {
            //dialog ctrl
            swal({
                html: true,
                title: "<span style='font-size: 15px; font-weight: bold;'>Warning !!!</span>",
                text: "<span style='font-size: 15px; color: #000;'>Please sign the driver's name</span>",
                type: "warning",
                timer: 2500,
                showConfirmButton: false,
                allowOutsideClick: false
            });

            return false;
        } else if (driver_imgData.length == 3626) //null
        {
            //dialog ctrl
            swal({
                html: true,
                title: "<span style='font-size: 15px; font-weight: bold;'>Warning !!!</span>",
                text: "<span style='font-size: 15px; color: #000;'>Please sign the driver's name</span>",
                type: "warning",
                timer: 2500,
                showConfirmButton: false,
                allowOutsideClick: false
            });

            return false;
        } else if (cus_imgData.length == 3614) //null
        {
            //dialog ctrl
            swal({
                html: true,
                title: "<span style='font-size: 15px; font-weight: bold;'>Warning !!!</span>",
                text: "<span style='font-size: 15px; color: #000;'>Please sign the customer's name</span>",
                type: "warning",
                timer: 2500,
                showConfirmButton: false,
                allowOutsideClick: false
            });

            return false;
        } else if (cus_imgData.length == 3626) //null
        {
            //dialog ctrl
            swal({
                html: true,
                title: "<span style='font-size: 15px; font-weight: bold;'>Warning !!!</span>",
                text: "<span style='font-size: 15px; color: #000;'>Please sign the customer's name</span>",
                type: "warning",
                timer: 2500,
                showConfirmButton: false,
                allowOutsideClick: false
            });

            return false;
        } else {
            //dialog ctrl
            swal({
                    html: true,
                    title: "<span style='font-size: 15px; font-weight: bold;'>Warning !!!</span>",
                    text: "<span style='font-size: 15px; color: #000;'>Are you <b>confirm</b> stock replenishment ?</span>",
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
                        $("#loadding").modal({
                            backdrop: "static", //remove ability to close modal with click
                            keyboard: false, //remove option to close with keyboard
                            show: true //Display loader!
                        });
                        //post confirm replenish
                        $.ajax({
                            type: 'POST',
                            url: '<?= $CFG->src_wms_special ?>/confirm_refill_cod.php',
                            data: {
                                iden_pj_name: $("#txt_sel_pro").val(),
                                iden_txt_scn_driver_id: $("#txt_scn_driver_id").val(),
                                iden_txt_scn_dtn_id: $("#txt_scn_dtn_id").val(),
                                iden_driver_sign: driver_imgData,
                                iden_cus_sign: cus_imgData
                            },
                            success: function(response) {
                                $("#loadding").modal("hide");                              
                                //dialog ctrl
                                swal({
                                    html: true,
                                    title: "<br>",
                                    text: "<span style='font-size: 15px; color: #000;'>[D003] --- Stock replenishment success.</span>",
                                    type: "success",
                                    timer: 4000,
                                    showConfirmButton: false,
                                    allowOutsideClick: false
                                });

                                setTimeout(function() {
                                    $("#modal-dtn-details").modal("show");
                                    _load_dtn_sign_details();

                                }, 4300);

                            },
                            error: function() {
                                $("#loadding").modal("hide");     
                                //dialog ctrl
                                swal({
                                    html: true,
                                    title: "<span style='font-size: 15px; font-weight: bold;'>Warning !!!</span>",
                                    text: "<span style='font-size: 15px; color: #000;'>[D002] --- Ajax Error !!! Cannot operate</span>",
                                    type: "warning",
                                    timer: 4000,
                                    showConfirmButton: false,
                                    allowOutsideClick: false
                                });
                            }
                        });

                    }
                });

        }
    }

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

    function loadPage(){
        window.location.reload();
    }
</script>
<script src="dist/assets/json2.min.js"></script>
</body>

</html>

<div class="modal fade" id="modal-dtn-details" data-keyboard="false"  data-backdrop="static">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" onclick="loadPage()"  aria-label="Close">
                    <span aria-hidden="true">&times;</span></button>
                <h1 class="modal-title">Delivery Transfer Note Detalis</h1>
            </div>
            <div class="modal-body">
                <div class="row">
                    <div class="col-md-12">
                        <div class="form-group">
                            <center>
                                <div style="height: 1560px; overflow:auto; alignment-adjust:central;">
                                    <span id="spn_load_dtn_details"></span>
                                </div>
                            </center>
                        </div>
                    </div>
                </div>
            </div>
            <div class="modal-footer">
                <!-- onclick="go_index()" -->
                <button type="button" class="button-close btn btn-default btn-lg" onclick="loadPage()" data-dismiss="modal">
                    <font style="font-size: 30px; color: #000;">CLOSE</font>
                </button>
            </div>
        </div>
        <!-- /.modal-content -->
    </div>
    <!-- /.modal-dialog -->
</div>
<!-- /.modal -->


<!-- Model loading -->
<div class="modal fade" id="loadding" tabindex="-1" role="dialog" aria-labelledby="loadMeLabel">
    <div class="modal-dialog modal-sm" role="document">
        <div class="modal-content">
            <div class="modal-body text-center">
                <div class="loader"></div>
            </div>
        </div>
    </div>
</div>
</div>